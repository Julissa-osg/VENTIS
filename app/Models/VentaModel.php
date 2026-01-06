<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table          = 'ventas';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    
    // Campos permitidos
    protected $allowedFields  = [
        'id_tienda',
        'id_usuario',
        'cliente_nombre',
        'total',
        'metodo_pago',
        'estado'
    ];

    // Configuración de Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Dejamos vacío ya que la tabla 'ventas' no usa updated_at
}
