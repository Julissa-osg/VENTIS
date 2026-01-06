<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditoriaModel extends Model
{
    protected $table          = 'auditoria';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = '';
    protected $deletedField   = '';

    protected $allowedFields = [
        'id_tienda',
        'id_usuario',
        'accion',
        'ip_address'
    ];

    protected $validationRules = [
        'id_tienda'     => 'required|integer',
        'id_usuario'    => 'required|integer',
        'accion'        => 'required',
        'ip_address'    => 'required|max_length[50]',
    ];
}
