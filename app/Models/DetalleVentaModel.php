<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleVentaModel extends Model
{
    protected $table      = 'detalle_venta';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    // ESTOS CAMPOS COINCIDEN EXACTAMENTE CON TU BASE DE DATOS
    protected $allowedFields = [
        'id_venta', 
        'id_producto', 
        'cantidad', 
        'precio_unitario_venta',
        'precio_unitario_costo',
        'ganancia_unitaria'
    ];

    protected $useTimestamps = false;
}
