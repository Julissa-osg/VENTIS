<?php

namespace App\Models;

use CodeIgniter\Model;

class CodigoVipModel extends Model
{
    protected $table           = 'codigos_vip';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $useSoftDeletes  = false;

    protected $allowedFields   = [
        'codigo',
        'precio',
        'duracion_dias',
        'id_tienda_uso',
        'fecha_uso',
        'estado'
    ];

    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = '';
    protected $deletedField   = '';

    protected $validationRules = [
        'codigo'        => 'required|is_unique[codigos_vip.codigo]|max_length[50]',
        'precio'        => 'required|numeric|greater_than_equal_to[0]',
        'duracion_dias' => 'required|integer|greater_than[0]',
        'estado'        => 'permit_empty|in_list[Activo,Usado,Inactivo]'
    ];
}
