<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id_tienda', 
        'nombre', 
        'codigo_barras', 
        'stock_actual', // ESTE CAMPO ES CRÍTICO
        'precio_compra', 
        'precio_venta', 
        'ubicacion_mapa', 
        'estado'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // *** EL MÉTODO DECREMENT EXISTE EN EL MODELO BASE DE CI4 ***
    // Si no tienes una versión propia de decrement, el código del controlador
    // debe funcionar si el campo 'stock_actual' está correcto.
}
