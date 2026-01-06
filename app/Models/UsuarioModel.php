<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true; // Usamos soft deletes (columna deleted_at)
    protected $protectFields    = true;

    // Campos permitidos para ser insertados o actualizados
    protected $allowedFields = [
        'id_tienda',
        'nombre',
        'email',

        'hobby',
        
        'password', // Se hashea antes de guardarse
        'rol',
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
        'email'     => 'required|valid_email|is_unique[usuarios.email,id,{id}]|max_length[150]',
        'password'  => 'required|min_length[8]',
        'nombre'    => 'required|max_length[100]',
        'rol'       => 'required|in_list[Super Admin,Admin Tienda,Vendedor]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks de Eventos (Hooks)
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword']; // Antes de insertar, hashear la contraseña
    protected $beforeUpdate   = ['hashPassword']; // Antes de actualizar, hashear la contraseña

    /**
     * Hashea la contraseña antes de guardarla en la base de datos.
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data)
    {
        if (! isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
}
