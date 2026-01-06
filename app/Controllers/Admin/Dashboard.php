<?php

namespace App\Controllers\Admin; // IMPORTANTE: Usamos un namespace para la subcarpeta Admin

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    /**
     * Muestra la vista principal del Super Administrador.
     */
    public function index()
    {
        // 1. Verificar si el usuario está logeado y tiene el rol correcto (Superadmin)
        // Aunque esto se hará mejor con un Filter, es bueno tener una capa aquí.
        // Asumimos que la sesión ya contiene 'nombre' y 'rol' (Superadmin)

        $data = [
            'title' => 'Dashboard | Super Admin',
            // Puedes pasar datos adicionales si los necesitas en la vista
        ];
        
        // 2. Carga la vista que diseñamos
        return view('admin/dashboard', $data);
    }
}