<?php

namespace App\Controllers\Admin; 

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    // Constructor para cargar los modelos que vamos a usar
    protected $codigoVipModel;
    protected $tiendaModel;


    public function __construct()
    {
        // Cargamos los Modelos
        $this->codigoVipModel = new \App\Models\CodigoVipModel();
	    $this->tiendaModel = new \App\Models\TiendaModel();

        $this->usuarioModel = new \App\Models\UsuarioModel();

       
    }

    
    public function index()
    {
        
        return view('admin/dashboard');
    }

    
    public function generarCodigos()
    {
        
        $data['validation'] = \Config\Services::validation();

       
        return view('admin/codigos_vip/generar', $data); 
    }

    
    public function crearCodigos()
    {
       
        if (! $this->request->is('post') || ! $this->validate([
            'cantidad'      => 'required|integer|greater_than[0]|less_than_equal_to[100]',
            'precio'        => 'required|numeric|greater_than_equal_to[0]',
            'duracion_dias' => 'required|integer|greater_than[0]',
        ])) {
            return redirect()->back()->withInput();
        }

        $cantidad = $this->request->getPost('cantidad');
        $precio = $this->request->getPost('precio');
        $duracionDias = $this->request->getPost('duracion_dias');
        $codigosGenerados = [];

        for ($i = 0; $i < $cantidad; $i++) {
            $codigo = $this->generarCodigoUnico(); 

            $data = [
                'codigo'        => $codigo,
                'precio'        => $precio,
                'duracion_dias' => $duracionDias,
                'estado'        => 'Activo',
            ];

            
            if ($this->codigoVipModel->insert($data)) {
                $codigosGenerados[] = $codigo;
            } else {
                
                log_message('error', 'Fallo al guardar código VIP: ' . json_encode($data));
            }
        }

        return redirect()->to(site_url('admin/codigos'))->with('message', 'Se generaron ' . count($codigosGenerados) . ' códigos VIP exitosamente.');
    }


    public function listarCodigos()
    {
        
        $data['codigos'] = $this->codigoVipModel->findAll();

        
        return view('admin/codigos_vip/listar', $data);
    }

   
    private function generarCodigoUnico(int $length = 10): string
    {
        $bytes = random_bytes(ceil($length / 2));
        $codigo = substr(bin2hex($bytes), 0, $length);

        
        while ($this->codigoVipModel->where('codigo', $codigo)->first()) {
            $bytes = random_bytes(ceil($length / 2));
            $codigo = substr(bin2hex($bytes), 0, $length);
        }

        return strtoupper($codigo); 
    }




        // Listar todas las tiendas (Dashboard de tiendas del Super Admin)
    public function listarTiendas()
    {
        $data['tiendas'] = $this->tiendaModel->findAll();

        // En un proyecto real, cargarías la vista de Listado de Tiendas.
        return view('admin/tiendas/listar', $data);
    }

    // Muestra el formulario para crear una nueva tienda
    public function crearTienda()
    {
        
        $data['validation'] = \Config\Services::validation();

        
        return view('admin/tiendas/crear', $data);
    }

    ///////
    ///////
    public function guardarTienda()
    {
        // 1. Verificar si la solicitud es POST
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        // 2. Validar datos de la Tienda y del nuevo Usuario Admin
        if (! $this->validate([
            
            'nombre'      => 'required|max_length[100]',
            'propietario' => 'required|max_length[100]',
            'ruc'         => 'permit_empty|max_length[20]',

            
            'admin_nombre' => 'required|max_length[100]',
            'admin_email'  => 'required|valid_email|is_unique[usuarios.email]|max_length[150]',
            'admin_password' => 'required|min_length[8]',
        ])) {
            
            return redirect()->back()->withInput();
        }

        $usuarioModel = new \App\Models\UsuarioModel();

        
        $this->tiendaModel->db->transStart();

        try {
            // A. Crear la Tienda
            $tiendaData = [
                'nombre'      => $this->request->getPost('nombre'),
                'propietario' => $this->request->getPost('propietario'),
                'ruc'         => $this->request->getPost('ruc'),
                'estado_vip'  => 0, 
                'estado'      => 1, 
            ];

            // Guardar la tienda
            $this->tiendaModel->insert($tiendaData);
            $idTienda = $this->tiendaModel->getInsertID();

            // B. Crear el Usuario Administrador de Tienda
            $usuarioData = [
                'id_tienda' => $idTienda, // Relacionar la Tienda con su Dueño
                'nombre'    => $this->request->getPost('admin_nombre'),
                'email'     => $this->request->getPost('admin_email'),
                'password'  => $this->request->getPost('admin_password'), // El modelo lo hashea automáticamente
                'rol'       => 'Admin Tienda',
                'estado'    => 1, // Usuario activo
            ];

            // Guardar el usuario (esto también dispara el hash de password en el modelo)
            $usuarioModel->insert($usuarioData);

            // 5. Finalizar Transacción
            $this->tiendaModel->db->transComplete();

            if ($this->tiendaModel->db->transStatus() === false) {
                 throw new \Exception('La transacción falló y se revirtió.');
            }

            return redirect()->to(site_url('admin/tiendas'))->with('message', 'Tienda y Administrador creados con éxito!');

        } catch (\Exception $e) {
            
            $this->tiendaModel->db->transRollback(); 
            log_message('error', 'Error al crear tienda y usuario: ' . $e->getMessage());

            return redirect()->to(site_url('admin/tiendas/crear'))->with('error', 'Error al guardar la tienda y su administrador. Inténtelo de nuevo.');
        }
    }

    // --------------------------------------------------------------------
    //  EDITAR Y ELIMINAR TIENDAS
    // --------------------------------------------------------------------

    // 1. Muestra el formulario de edición (GET)
    public function editarTienda($id = null)
    {
        // Buscamos la tienda por su ID
        $tienda = $this->tiendaModel->find($id);

        if (!$tienda) {
            return redirect()->to(site_url('admin/tiendas'))->with('error', 'La tienda solicitada no existe.');
        }

        $data = [
            'tienda' => $tienda,
            'validation' => \Config\Services::validation()
        ];

        
        return view('admin/tiendas/editar', $data);
    }

    // 2. Procesa la actualización de datos (POST)
    public function actualizarTienda()
    {
        // Validamos que sea POST
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $id = $this->request->getPost('id');

        // Validamos los datos (Igual que al crear, pero sin datos de usuario/password)
        if (! $this->validate([
            'nombre'      => 'required|max_length[100]',
            'propietario' => 'required|max_length[100]',
            'ruc'         => 'permit_empty|max_length[20]',
            'estado'      => 'required|in_list[0,1]' 
        ])) {
            
            return redirect()->back()->withInput();
        }

        // Preparamos los datos
        $data = [
            'nombre'      => $this->request->getPost('nombre'),
            'propietario' => $this->request->getPost('propietario'),
            'ruc'         => $this->request->getPost('ruc'),
            'estado'      => $this->request->getPost('estado'), 
        ];

        $this->tiendaModel->update($id, $data);

        return redirect()->to(site_url('admin/tiendas'))->with('success', 'La tienda se actualizó correctamente.');
    }

    // 3. Eliminar (Suspender) Tienda (POST)
    public function eliminarTienda($id = null)
    {
       
        
        $tienda = $this->tiendaModel->find($id);

        if ($tienda) {
            // Opción A: Suspender (Recomendado)
            $this->tiendaModel->update($id, ['estado' => 0]);
            
           

            return redirect()->to(site_url('admin/tiendas'))->with('success', 'La tienda ha sido desactivada correctamente.');
        }

        return redirect()->to(site_url('admin/tiendas'))->with('error', 'No se pudo encontrar la tienda.');
    }


    // -----------------------------------------------------------
    //  MÓDULO DE GESTIÓN DE USUARIOS
    // -----------------------------------------------------------

    public function listarUsuarios()
    {
        // Hacemos un JOIN para traer el nombre de la tienda en lugar de solo el ID
        $usuarios = $this->usuarioModel
            ->select('usuarios.*, tiendas.nombre as nombre_tienda')
            ->join('tiendas', 'tiendas.id = usuarios.id_tienda', 'left')
            ->findAll();

        $data = ['usuarios' => $usuarios];
        return view('admin/usuarios/listar', $data);
    }

    public function editarUsuario($id = null)
    {
        $usuario = $this->usuarioModel->find($id);
        
        if (!$usuario) {
            return redirect()->to(site_url('admin/usuarios'))->with('error', 'Usuario no encontrado');
        }

        // Necesitamos la lista de tiendas por si queremos cambiarlo de tienda
        $tiendas = $this->tiendaModel->findAll();

        $data = [
            'usuario' => $usuario,
            'tiendas' => $tiendas,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/usuarios/editar', $data);
    }

    public function actualizarUsuario()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $id = $this->request->getPost('id');

        // Reglas básicas
        $rules = [
            'nombre' => 'required|max_length[100]',
            'email'  => "required|valid_email|is_unique[usuarios.email,id,$id]", // Email único excepto para este mismo usuario
            'rol'    => 'required',
            'estado' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'email'     => $this->request->getPost('email'),
            'rol'       => $this->request->getPost('rol'),
            'id_tienda' => $this->request->getPost('id_tienda'),
            'estado'    => $this->request->getPost('estado'),
        ];

        // LÓGICA DE CONTRASEÑA:
        // Solo la actualizamos si el admin escribió algo en el campo.
        $nuevaPassword = $this->request->getPost('password');
        if (!empty($nuevaPassword)) {
            // El modelo se encarga de hashear (si tienes beforeInsert/Update)
            // Si no, hazlo aquí: $data['password'] = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            $data['password'] = $nuevaPassword; 
        }

        $this->usuarioModel->update($id, $data);

        return redirect()->to(site_url('admin/usuarios'))->with('success', 'Usuario actualizado correctamente.');
    }





}
