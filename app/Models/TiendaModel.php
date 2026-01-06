<?php

namespace App\Models;

use CodeIgniter\Model;

class TiendaModel extends Model
{
    protected $table            = 'tiendas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_usuario_dueno',
        'nombre',
        'ruc',
        'propietario',
        'estado_vip',
        'fecha_expiracion_vip',
        'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nombre'      => 'required|max_length[100]',
        'propietario' => 'required|max_length[100]',
        'ruc'         => 'permit_empty|max_length[20]',
        'estado_vip'  => 'permit_empty|in_list[0,1]',
    ];
}
