<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    // Cargar modelos necesarios
    protected $usuarioModel;
    protected $tiendaModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->tiendaModel = new \App\Models\TiendaModel();
    }

    // Muestra el formulario de registro
    public function registro()
    {
        $data['validation'] = \Config\Services::validation();
        return view('auth/registro', $data);
    }

    // Procesa el formulario de registro
    public function guardarRegistro()
    {
        // 1. Verificar POST
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        // 2. Validar la entrada (CRÍTICO: Incluye 'usuario_nombre')
        if (! $this->validate([
            'nombre_tienda'    => 'required|max_length[100]',
            'usuario_nombre'   => 'required|max_length[100]', // ESTE CAMPO ES VITAL
            'usuario_email'    => 'required|valid_email|is_unique[usuarios.email]|max_length[150]',
            'usuario_password' => 'required|min_length[8]',
            'pass_confirm'     => 'required_with[usuario_password]|matches[usuario_password]',

            
            'hobby' => 'permit_empty|max_length[100]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Verifica los campos del formulario.');
        }
        
        // 3. Iniciar Transacción (Asegura que AMBOS se guarden o NINGUNO)
        $this->tiendaModel->db->transStart();

        try {
            // A. Crear la Tienda
            $tiendaData = [
                'nombre'      => $this->request->getPost('nombre_tienda'),
                'propietario' => $this->request->getPost('usuario_nombre'), // El usuario es el propietario
                'estado_vip'  => 0,
                'estado'      => 1,
            ];

            $this->tiendaModel->insert($tiendaData);
            $idTienda = $this->tiendaModel->getInsertID();

            // B. Crear el Usuario (Admin Tienda)
            $usuarioData = [
                'id_tienda' => $idTienda, // Relacionar la Tienda
                'nombre'    => $this->request->getPost('usuario_nombre'),
                'email'     => $this->request->getPost('usuario_email'),

                'hobby'     => $this->request->getPost('hobby'),
                
                'password'  => $this->request->getPost('usuario_password'), 
                'rol'       => 'Admin Tienda',
                'estado'    => 1,
            ];

            // El UsuarioModel hashea la contraseña automáticamente (callback beforeInsert)
            $this->usuarioModel->insert($usuarioData);
            $idUsuario = $this->usuarioModel->getInsertID();
            
            // C. VINCULAR LA TIENDA AL DUEÑO (Mejora la integridad de la BD)
            // Esto asegura que tiendas.id_usuario_dueno se llene (aunque sea NULLable)
            $this->tiendaModel->update($idTienda, ['id_usuario_dueno' => $idUsuario]);

            // D. Finalizar Transacción
            $this->tiendaModel->db->transComplete();

            if ($this->tiendaModel->db->transStatus() === false) {
                 // Esto solo se activa por un fallo de BD muy raro después del commit
                 throw new \Exception('La transacción falló y se revirtió después del commit.');
            }

            // Éxito: Redirigir al login
            return redirect()->to(site_url('login'))->with('success', '¡Registro exitoso! Por favor, inicia sesión.');

        } catch (\Exception $e) {
            // 4. Manejo de Errores
            $this->tiendaModel->db->transRollback(); 
            log_message('error', 'Error al autoregistro: ' . $e->getMessage());
            
            // Si es un error de BD (ej. email duplicado a pesar de la validación), redirige con input
            return redirect()->to(site_url('registro'))->withInput()->with('error', 'Error al guardar. Inténtelo de nuevo.');
        }
    }

    // --- EL RESTO DE TUS MÉTODOS (login, autenticar, logout) SÍ ESTÁN CORRECTOS ---

    public function login()
    {
        $data['validation'] = \Config\Services::validation();
        return view('auth/login', $data);
    }

    public function autenticar()
    {
        // ... Lógica de Autenticación ...
        if (! $this->request->is('post')) { return redirect()->back(); }
        // ... (Tu código de autenticación es correcto) ...
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $usuario = $this->usuarioModel->where('email', $email)->first();

        if (! $usuario || ! password_verify($password, $usuario['password'])) {
            return redirect()->back()->with('error', 'Email o contraseña incorrectos.')->withInput();
        }
        
        if ($usuario['estado'] == 0) {
            return redirect()->back()->with('error', 'Tu cuenta está inactiva. Contacta al soporte.')->withInput();
        }

        $tienda = $this->tiendaModel->find($usuario['id_tienda']);
        $isVip = $tienda['estado_vip'] ?? 0;

        $sessionData = [
            'id_usuario' => $usuario['id'],
            'id_tienda'  => $usuario['id_tienda'],
            'nombre'     => $usuario['nombre'],
            'email'      => $usuario['email'],
            'rol'        => $usuario['rol'],
            'is_vip'     => $isVip,
            'isLoggedIn' => true,
        ];

        session()->set($sessionData);
        
        if ($usuario['rol'] === 'Super Admin') {
            return redirect()->to(site_url('admin'));
        }
        return redirect()->to(site_url('tienda'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Has cerrado sesión correctamente.');
    }
}