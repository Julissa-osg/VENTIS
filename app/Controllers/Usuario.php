<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\TiendaModel;

class Usuario extends BaseController
{
    protected $usuarioModel;
    protected $tiendaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->tiendaModel = new TiendaModel();
    }

    // 1. Ver el Perfil
    public function miPerfil()
    {
        // CORRECCIÓN: Usamos 'id_usuario' que es como lo guardaste en Auth.php
        $idUsuario = session()->get('id_usuario'); 
        
        if (!$idUsuario) {
            return redirect()->to(site_url('login'));
        }

        $usuario = $this->usuarioModel->find($idUsuario);
        
        // Si tiene tienda asignada, buscamos su nombre
        $tienda = null;
        if (!empty($usuario['id_tienda'])) {
            $tienda = $this->tiendaModel->find($usuario['id_tienda']);
        }

        $data = [
            'usuario' => $usuario,
            'tienda'  => $tienda,
            'validation' => \Config\Services::validation()
        ];

        return view('perfil/index', $data);
    }

    // 2. Actualizar Datos Generales (Nombre/Email)
    public function actualizarDatos()
    {
        // CORRECCIÓN: Usamos 'id_usuario'
        $idUsuario = session()->get('id_usuario'); 
        
        // Validación
        if (!$this->validate([
            'nombre' => 'required|min_length[3]',
            'email'  => "required|valid_email|is_unique[usuarios.email,id,$idUsuario]"
        ])) {
            return redirect()->back()->withInput()->with('error', 'Datos inválidos o email ya registrado.');
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'email'  => $this->request->getPost('email')
        ];

        $this->usuarioModel->update($idUsuario, $data);

        // Actualizar la sesión para que el nombre cambie en la barra superior al instante
        // NOTA: No necesitamos cambiar 'id_usuario', solo el nombre
        session()->set(['nombre' => $data['nombre']]);

        return redirect()->back()->with('success', 'Tus datos personales se actualizaron correctamente.');
    }

    // 3. Cambio ESTRICTO de Contraseña
    public function cambiarPassword()
    {
        // CORRECCIÓN: Usamos 'id_usuario'
        $idUsuario = session()->get('id_usuario');
        
        $passActual = $this->request->getPost('pass_actual');
        $passNueva  = $this->request->getPost('pass_nueva');
        $passConfirm = $this->request->getPost('pass_confirm');

        // 1. Validar inputs básicos
        if (!$this->validate([
            'pass_actual' => 'required',
            'pass_nueva'  => 'required|min_length[6]',
            'pass_confirm'=> 'matches[pass_nueva]'
        ])) {
            return redirect()->back()->with('error_pass', 'Las contraseñas no coinciden o son muy cortas.');
        }

        // 2. Verificar que la contraseña ACTUAL sea correcta en la BD
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!password_verify($passActual, $usuario['password'])) {
            return redirect()->back()->with('error_pass', 'La contraseña actual ingresada es INCORRECTA.');
        }

        // 3. Todo OK: Actualizamos
        // IMPORTANTE: Si tu modelo NO tiene 'beforeUpdate' para hashear, descomenta la siguiente línea:
        // $passNueva = password_hash($passNueva, PASSWORD_DEFAULT);
        
        $this->usuarioModel->update($idUsuario, ['password' => $passNueva]);

        return redirect()->back()->with('success_pass', '¡Contraseña cambiada con éxito! Úsala en tu próximo inicio de sesión.');
    }

    public function actualizarTienda()
    {
        $idUsuario = session()->get('id_usuario');
        $idTienda  = session()->get('id_tienda');
        $rol       = session()->get('rol');

        // Seguridad: Solo el Admin de Tienda (o Super Admin) puede editar esto
        if (!$idTienda || ($rol !== 'Admin Tienda' && $rol !== 'Super Admin')) {
            return redirect()->back()->with('error', 'No tienes permisos para editar la tienda.');
        }

        // Validación
        if (!$this->validate([
            'nombre_tienda' => 'required|max_length[100]',
            'propietario'   => 'required|max_length[100]',
            'ruc'           => 'permit_empty|numeric|max_length[11]|min_length[11]', // RUC suele ser 11 dígitos
            'direccion'     => 'permit_empty|max_length[200]', // Asumiendo que tengas este campo (opcional)
            'telefono'      => 'permit_empty|max_length[20]'   // Asumiendo que tengas este campo (opcional)
        ])) {
            return redirect()->back()->withInput()->with('error', 'Error en los datos de la tienda. Verifica el RUC (11 números).');
        }

        $data = [
            'nombre'      => $this->request->getPost('nombre_tienda'),
            'propietario' => $this->request->getPost('propietario'),
            'ruc'         => $this->request->getPost('ruc'),
            // Si tu base de datos tiene dirección y teléfono, descomenta esto:
            // 'direccion'   => $this->request->getPost('direccion'),
            // 'telefono'    => $this->request->getPost('telefono'),
        ];

        $this->tiendaModel->update($idTienda, $data);

        return redirect()->back()->with('success', '¡Información de la tienda actualizada correctamente!');
    }


}