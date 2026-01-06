<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // No hay 'deleted_at' en clientes

    protected $allowedFields = [
        'id_tienda', 
        'nombre', 
        'documento', 
        'direccion', 
        'telefono', 
        'email'
    ];

    // Dates
    protected $useTimestamps = true; // Usaremos created_at y updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
