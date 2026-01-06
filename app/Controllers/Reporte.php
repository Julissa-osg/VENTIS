<?php

namespace App\Controllers; // Ajusta el namespace si lo tienes en subcarpeta

use App\Controllers\BaseController;
use App\Models\VentaModel;

class Reporte extends BaseController
{
    protected $ventaModel;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
    }

    public function index()
    {
        $idTienda = session()->get('id_tienda');
        
        // 1. Obtener fechas del filtro (o usar el mes actual por defecto)
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin    = $this->request->getGet('fecha_fin') ?? date('Y-m-d');

        // 2. Consulta Avanzada: Ventas + Nombre Cliente + Nombre Vendedor
        // Usamos Query Builder para unir tablas si es necesario
        $db = \Config\Database::connect();
        $builder = $db->table('ventas v');
        $builder->select('v.*, u.nombre as vendedor, c.documento as doc_cliente');
        $builder->join('usuarios u', 'u.id = v.id_usuario');
        $builder->join('clientes c', 'c.id = v.id_cliente', 'left'); // Left join por si es cliente "Público General"
        $builder->where('v.id_tienda', $idTienda);
        $builder->where("DATE(v.created_at) BETWEEN '$fechaInicio' AND '$fechaFin'");
        $builder->orderBy('v.created_at', 'DESC');
        
        $ventas = $builder->get()->getResultArray();

        $data = [
            'ventas'      => $ventas,
            'fechaInicio' => $fechaInicio,
            'fechaFin'    => $fechaFin
        ];

        return view('tienda/reportes/index', $data);
    }
}