<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\DetalleVentaModel;
use App\Models\ProductoModel;
use App\Models\TiendaModel;   // <-- Necesario para datos de la boleta
use App\Models\UsuarioModel;  // <-- Necesario para datos de la boleta
use App\Models\ClienteModel;  // <-- Necesario para buscar clientes
use CodeIgniter\Controller;

class Venta extends BaseController
{
    protected $ventaModel;
    protected $detalleVentaModel;
    protected $productoModel;
    protected $tiendaModel;
    protected $usuarioModel;
    protected $clienteModel;
    protected $db;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
        $this->detalleVentaModel = new DetalleVentaModel();
        $this->productoModel = new ProductoModel();
        $this->tiendaModel = new TiendaModel();
        $this->usuarioModel = new UsuarioModel();
        // Si no tienes ClienteModel creado, usa la tabla directa, pero asumiré que existe o usaremos DB builder
        $this->db = \Config\Database::connect();
    }

    // VISTA TPV
    public function index()
    {
        $idTienda = session()->get('id_tienda');

        // FILTRO DE SEGURIDAD: Solo productos de ESTA tienda
        $data['productos'] = $this->productoModel
                                  ->where('id_tienda', $idTienda)
                                  ->where('estado', 1)
                                  ->findAll();

        $data['title'] = 'Punto de Venta';
        return view('tienda/ventas/tpv', $data);
    }

    // PROCESAR VENTA (AJAX)
    public function guardarVenta() 
    {
        $this->response->setContentType('application/json');

       
        
        $data = $this->request->getJSON(true); 
        $items = $data['items'] ?? null;
        $totalVenta = $data['total'] ?? 0;
        
        // Datos del cliente recibidos del TPV
        $idCliente = (isset($data['cliente_id']) && $data['cliente_id'] > 0) ? $data['cliente_id'] : null;
        $clienteNombre = $data['cliente_nombre'] ?? 'Público General';
        
        $idTienda = session()->get('id_tienda');
        $idUsuario = session()->get('id_usuario');

        if (!$items || count($items) === 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Carrito vacío.']);
        }

        $this->db->transBegin();

        try {
            // 1. Guardar Venta
            $ventaData = [
                'id_tienda'      => $idTienda,
                'id_usuario'     => $idUsuario,
                'id_cliente'     => $idCliente,
                'cliente_nombre' => $clienteNombre,
                'total'          => $totalVenta,
                'metodo_pago'    => 'Efectivo',
                'estado'         => 1,
                'created_at'     => date('Y-m-d H:i:s'),
            ];
            
            $this->ventaModel->insert($ventaData);
            $ventaId = $this->ventaModel->getInsertID();

            // 2. Guardar Detalles y Descontar Stock
            foreach ($items as $item) {
                // Verificar Stock en tiempo real
                $productoActual = $this->productoModel
                                       ->where('id', $item['id'])
                                       ->where('id_tienda', $idTienda)
                                       ->first();

                if (!$productoActual || $productoActual['stock_actual'] < $item['cantidad']) {
                    $this->db->transRollback();
                    return $this->response->setJSON([
                        'status' => 'error', 
                        'message' => 'Stock insuficiente para: ' . ($productoActual['nombre'] ?? 'Producto')
                    ]);
                }

                // --- AQUÍ ESTABA EL ERROR DE COLUMNAS ---
                $detalleData = [
                    'id_venta'              => $ventaId,
                    'id_producto'           => $item['id'],
                    'nombre_producto'       => $item['nombre'], // (Esta es la que agregaste con SQL)
                    'cantidad'              => $item['cantidad'],
                    
                    // Nombres CORRECTOS según tu backup SQL:
                    'precio_unitario_venta' => $item['precio'],           
                    'precio_unitario_costo' => $productoActual['precio_compra'], 
                    'ganancia_unitaria'     => ($item['precio'] - $productoActual['precio_compra']) * $item['cantidad']
                ];
                // ----------------------------------------

                $this->detalleVentaModel->insert($detalleData);

                // Descontar Stock
                $this->productoModel->set('stock_actual', 'stock_actual - ' . $item['cantidad'], false)
                                    ->where('id', $item['id'])
                                    ->update();
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error en base de datos.']);
            } else {
                $this->db->transCommit();
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Venta guardada.', 
                    'venta_id' => $ventaId
                ]);
            }

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // API BUSCAR PRODUCTO
    public function buscarProductoApi()
    {
        // ... (Tu código anterior estaba bien aquí, solo asegúrate de filtrar por id_tienda)
        $sku = $this->request->getPost('sku');
        $idTienda = session()->get('id_tienda');
        
        $producto = $this->productoModel->where('codigo_barras', $sku)
                                        ->where('id_tienda', $idTienda)
                                        ->where('estado', 1)
                                        ->first();
        if($producto) {
            return $this->response->setJSON(['status'=>'success', 'producto'=>$producto]);
        }
        return $this->response->setJSON(['status'=>'error', 'message'=>'No encontrado']);
    }

    // NUEVO: API BUSCAR CLIENTE POR DNI
    public function buscarClienteApi()
    {
        $dni = $this->request->getPost('dni');
        $idTienda = session()->get('id_tienda');

        // Buscamos en la tabla clientes usando Query Builder directo para no depender del Modelo si no existe
        $cliente = $this->db->table('clientes')
                            ->where('documento', $dni)
                            ->where('id_tienda', $idTienda)
                            ->get()->getRowArray();

        if ($cliente) {
            return $this->response->setJSON(['status' => 'success', 'cliente' => $cliente]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cliente no encontrado']);
        }
    }

    // GENERAR BOLETA (Mejorado)
    public function generarBoleta($ventaId)
    {
        $idTienda = session()->get('id_tienda');
        
        // 1. Buscamos la venta y verificamos seguridad
        $venta = $this->ventaModel->find($ventaId);

        if (!$venta || $venta['id_tienda'] != $idTienda) {
            return "Venta no encontrada o acceso denegado.";
        }

        // 2. OBTENER DETALLES (LA CORRECCIÓN ESTÁ AQUÍ)
        // Usamos 'left' join para que si el producto se borró, no rompa la boleta.
        // Traemos 'nombre_producto' (historial) y 'nombre_catalogo' (actual).
        $detalles = $this->detalleVentaModel
                         ->select('detalle_venta.*, productos.nombre as nombre_catalogo')
                         ->join('productos', 'productos.id = detalle_venta.id_producto', 'left')
                         ->where('id_venta', $ventaId)
                         ->findAll();

        $tienda = $this->tiendaModel->find($idTienda);
        
        // Usamos el modelo de usuario para obtener el nombre del vendedor
        $vendedor = $this->usuarioModel->find($venta['id_usuario']);

        $data = [
            'venta' => $venta,
            'detalles' => $detalles,
            'tienda' => $tienda,
            'vendedor' => $vendedor
        ];

        return view('tienda/ventas/boleta_view', $data); 
    }

    public function crearClienteApi()
    {
        $idTienda = session()->get('id_tienda');
        $dni = $this->request->getPost('modal_dni'); // Recibimos del modal
        $nombre = $this->request->getPost('modal_nombre');
        
        // Validación básica
        if (empty($dni) || empty($nombre)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'DNI y Nombre son obligatorios']);
        }

        // Verificar si ya existe el DNI (Validación manual para manejar el error amigablemente)
        $existe = $this->db->table('clientes')->where('documento', $dni)->countAllResults();
        if ($existe > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Este DNI ya está registrado en el sistema.']);
        }

        $data = [
            'id_tienda' => $idTienda,
            'nombre'    => $nombre,
            'documento' => $dni,
            // Opcionales según tu tabla:
            'direccion' => $this->request->getPost('modal_direccion') ?? null,
            'telefono'  => $this->request->getPost('modal_telefono') ?? null,
            'email'     => $this->request->getPost('modal_email') ?? null,
            'created_at'=> date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('clientes')->insert($data);
            $nuevoId = $this->db->insertID();
            
            // Devolvemos los datos para auto-seleccionar al cliente en el TPV
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Cliente creado correctamente',
                'cliente' => [
                    'id' => $nuevoId,
                    'nombre' => $nombre,
                    'documento' => $dni
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }





}