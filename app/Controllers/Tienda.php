<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentaModel;
use App\Models\ProductoModel;
use App\Models\DetalleVentaModel;

class Tienda extends BaseController
{
    protected $ventaModel;
    protected $productoModel;
    protected $detalleVentaModel; // Necesario para el Top Productos
    protected $db;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
        $this->productoModel = new ProductoModel();
        $this->detalleVentaModel = new DetalleVentaModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $idTienda = session()->get('id_tienda');
        $hoy = date('Y-m-d');

        // --- 1. TARJETAS SUPERIORES (KPIs) ---
        // Total dinero hoy
        $totalHoy = $this->ventaModel->where('id_tienda', $idTienda)
                                     ->like('created_at', $hoy)
                                     ->selectSum('total')
                                     ->first()['total'] ?? 0;

        // Cantidad ventas hoy
        $ventasHoy = $this->ventaModel->where('id_tienda', $idTienda)
                                      ->like('created_at', $hoy)
                                      ->countAllResults();

        // Stock Bajo
        $stockBajo = $this->productoModel->where('id_tienda', $idTienda)
                                         ->where('stock_actual <=', 5)
                                         ->where('estado', 1)
                                         ->countAllResults();

        // --- 2. GRÁFICO 1: VENTAS ÚLTIMOS 7 DÍAS ---
        $sqlGrafico = "SELECT DATE(created_at) as fecha, SUM(total) as total 
                       FROM ventas 
                       WHERE id_tienda = ? 
                       AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                       GROUP BY DATE(created_at)
                       ORDER BY fecha ASC";
        
        $query = $this->db->query($sqlGrafico, [$idTienda]);
        $datosGrafico = $query->getResultArray();

        $fechas = [];
        $totales = [];
        foreach($datosGrafico as $fila) {
            $fechas[] = date('d/m', strtotime($fila['fecha'])); 
            $totales[] = $fila['total'];
        }

        // --- 3. GRÁFICO 2: TOP 5 PRODUCTOS MÁS VENDIDOS ---
        // Hacemos una consulta uniendo detalle_venta y ventas para filtrar por tienda
        $sqlTop = "SELECT d.nombre_producto, SUM(d.cantidad) as cantidad_total
                   FROM detalle_venta d
                   JOIN ventas v ON v.id = d.id_venta
                   WHERE v.id_tienda = ?
                   GROUP BY d.id_producto
                   ORDER BY cantidad_total DESC
                   LIMIT 5";

        $queryTop = $this->db->query($sqlTop, [$idTienda]);
        $topProductos = $queryTop->getResultArray();

        $topNombres = [];
        $topCantidades = [];
        foreach($topProductos as $prod) {
            $topNombres[] = substr($prod['nombre_producto'], 0, 15) . '...'; // Acortar nombre
            $topCantidades[] = $prod['cantidad_total'];
        }

        // Enviamos todo a la vista
        $data = [
            'nombre_usuario' => session()->get('nombre'), // Recuperamos esto para tu saludo
            'rol'            => session()->get('rol'),
            'id_tienda'      => $idTienda,
            'totalHoy'       => $totalHoy,
            'ventasHoy'      => $ventasHoy,
            'stockBajo'      => $stockBajo,
            'graficoFechas'  => json_encode($fechas),
            'graficoTotales' => json_encode($totales),
            'topNombres'     => json_encode($topNombres),
            'topCantidades'  => json_encode($topCantidades)
        ];

        return view('tienda/dashboard', $data);
    }
}