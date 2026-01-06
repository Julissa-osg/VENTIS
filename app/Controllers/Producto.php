<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Producto extends BaseController
{
    protected $productoModel;

    public function __construct()
    {
        // Cargamos el modelo necesario
        $this->productoModel = new \App\Models\ProductoModel();
    }

    // LISTADO DE PRODUCTOS
    public function index()
    {
        $idTienda = session()->get('id_tienda');
        $productos = $this->productoModel->where('id_tienda', $idTienda)->findAll();
        
        $data = [
            'productos' => $productos,
            // Usamos is_vip para saber qué columnas mostrar en el listado
            'is_vip' => session()->get('is_vip') ?? 0, 
        ];

        return view('tienda/productos/listado', $data);
    }


    public function crear()
    {
        $data = [
            'validation' => \Config\Services::validation(),
            // Usamos is_vip para saber qué campos mostrar en el formulario
            'is_vip' => session()->get('is_vip') ?? 0, 
        ];

        return view('tienda/productos/crear', $data);
    }

    // PROCESA EL FORMULARIO Y GUARDA EL NUEVO PRODUCTO
    public function guardar()
    {
        if (! $this->request->is('post')) {
            return redirect()->to(site_url('tienda/productos'));
        }

        $idTienda = session()->get('id_tienda');
        $isVip = session()->get('is_vip') ?? 0;

        // 1. Definición de reglas de validación (base)
        $validationRules = [
            'nombre'       => 'required|max_length[100]',
            // CLAVE: is_unique valida contra la columna REAL de la DB: 'productos.codigo_barras'
            'sku'          => 'required|is_unique[productos.codigo_barras]|max_length[50]', 
            'stock'        => 'required|integer|greater_than_equal_to[0]',
            'precio_venta' => 'required|numeric|greater_than[0]',
        ];
        
        // 2. Agregar reglas VIP
        if ($isVip) {
            $validationRules['precio_costo'] = 'required|numeric|greater_than_equal_to[0]';
            $validationRules['ubicacion_mapa'] = 'permit_empty|max_length[255]';
        }

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput();
        }

        // 3. Preparar datos para inserción (Mapeando POST -> DB)
        $data = [
            'id_tienda'    => $idTienda,
            'nombre'       => $this->request->getPost('nombre'),
            'codigo_barras'=> $this->request->getPost('sku'),       // POST 'sku' -> DB 'codigo_barras'
            'stock_actual' => $this->request->getPost('stock'),     // POST 'stock' -> DB 'stock_actual'
            'precio_venta' => $this->request->getPost('precio_venta'),
            'estado'       => 1,
        ];

        // 4. Agregar datos VIP
        if ($isVip) {
            $data['precio_compra']  = $this->request->getPost('precio_costo'); // POST 'precio_costo' -> DB 'precio_compra'
            $data['ubicacion_mapa'] = $this->request->getPost('ubicacion_mapa');
        } else {
            // Asignar valores por defecto a los campos VIP si no es VIP
            $data['precio_compra']  = 0; 
            $data['ubicacion_mapa'] = null;
        }

        // 5. Guardar el producto
        if ($this->productoModel->insert($data)) {
            return redirect()->to(site_url('tienda/productos'))->with('success', 'Producto "' . $data['nombre'] . '" creado con éxito.');
        } else {
            return redirect()->back()->with('error', 'Error al guardar el producto.');
        }
    } // <--- Llave de cierre corregida para guardar()


    // MUESTRA EL FORMULARIO DE EDICIÓN PRE-CARGADO
    public function editar(int $id)
    {
        // 1. Verificar si el producto existe y pertenece a la tienda logueada
        $idTienda = session()->get('id_tienda');
        $producto = $this->productoModel->where('id', $id)
                                        ->where('id_tienda', $idTienda)
                                        ->first();
        
        if (!$producto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Producto no encontrado o acceso denegado.');
        }

        $data = [
            'producto'   => $producto,
            'validation' => \Config\Services::validation(),
            'is_vip'     => session()->get('is_vip') ?? 0, 
        ];

        return view('tienda/productos/editar', $data);
    }

    // PROCESA EL FORMULARIO Y ACTUALIZA EL PRODUCTO
    public function actualizar()
    {
        if (! $this->request->is('post')) {
            return redirect()->to(site_url('tienda/productos'));
        }

        $id = $this->request->getPost('id');
        $isVip = session()->get('is_vip') ?? 0;

        // 1. Definición de reglas de validación
        $validationRules = [
            'id'           => 'required|is_natural_no_zero',
            'nombre'       => 'required|max_length[100]',
            // is_unique ignora el ID actual del producto que se está editando
            'sku'          => "required|is_unique[productos.codigo_barras,id,{$id}]|max_length[50]", 
            'stock'        => 'required|integer|greater_than_equal_to[0]',
            'precio_venta' => 'required|numeric|greater_than[0]',
        ];
        
        if ($isVip) {
            $validationRules['precio_costo'] = 'required|numeric|greater_than_equal_to[0]';
            $validationRules['ubicacion_mapa'] = 'permit_empty|max_length[255]';
        }

        if (! $this->validate($validationRules)) {
            // Si falla, redirigir al formulario de edición
            return redirect()->to(site_url('tienda/productos/editar/' . $id))->withInput();
        }

        // 2. Preparar datos para actualización (Mapeando POST -> DB)
        $data = [
            'nombre'       => $this->request->getPost('nombre'),
            'codigo_barras'=> $this->request->getPost('sku'),
            'stock_actual' => $this->request->getPost('stock'),
            'precio_venta' => $this->request->getPost('precio_venta'),
        ];

        // 3. Agregar datos VIP
        if ($isVip) {
            $data['precio_compra']  = $this->request->getPost('precio_costo');
            $data['ubicacion_mapa'] = $this->request->getPost('ubicacion_mapa');
        } 
        
        // 4. Ejecutar la actualización
        $this->productoModel->update($id, $data);

        return redirect()->to(site_url('tienda/productos'))->with('success', 'Producto "' . $data['nombre'] . '" actualizado con éxito.');
    }

public function buscarProductoApi()
    {
        // 1. Solo permitir peticiones POST (AJAX)
        if (! $this->request->is('post')) {
            // Devolver error si no es POST
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Método no permitido.']);
        }

        $idTienda = session()->get('id_tienda');
        $sku = $this->request->getPost('sku');

        if (empty($sku)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El código SKU no puede estar vacío.']);
        }

        // 2. Buscar el producto en la DB
        $producto = $this->productoModel
                         ->where('id_tienda', $idTienda)
                         ->where('codigo_barras', $sku)
                         ->where('estado', 1) // Solo productos activos
                         ->first();

        // 3. Devolver la respuesta
        if ($producto) {
            if ($producto['stock_actual'] <= 0) {
                // Producto encontrado, pero sin stock
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Producto agotado.',
                    'producto' => $producto
                ]);
            }
            
            // Éxito: Producto encontrado y con stock
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Producto encontrado.', 
                'producto' => $producto
            ]);
        } else {
            // Error: Producto no encontrado
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'SKU o Código de Barras no encontrado.'
            ]);
        }
    }












} // <--- Llave final de cierre de la clase Producto
