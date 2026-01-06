<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Instanciar Modelos
        $tiendaModel = model('App\Models\Tienda');
        $usuarioModel = model('App\Models\Usuario');

        // 2. Datos del SUPER ADMIN
        // -----------------------------------------------------
        // IMPORTANTE: CAMBIA ESTOS VALORES POR LOS QUE USARÁS REALMENTE
        $superAdminEmail = 'miestudio@gmail.com';
        $superAdminPassword = '1005KIROHURA2025EK';
        // -----------------------------------------------------

        // 3. Insertar la Tienda de Administración (Tienda ID 1)
        // Esta tienda estará ligada al Super Admin inicialmente.
        $tiendaData = [
            'nombre'         => 'Admin Global Sede Central',
            'ruc'            => '00000000000', // RUC ficticio
            'propietario'    => 'Super Admin',
            'estado_vip'     => 1, // Siempre VIP para el Super Admin
            'fecha_expiracion_vip' => '2099-12-31 23:59:59',
            'id_usuario_dueno' => null, // Lo actualizaremos después de insertar el usuario
        ];

        // Guardar la tienda
        $tiendaModel->insert($tiendaData);
        $tiendaId = $tiendaModel->getInsertID();

        // 4. Insertar el Usuario SUPER ADMIN (Usuario ID 1)
        // El Modelo Usuario hasheará automáticamente esta contraseña antes de guardarla.
        $userData = [
            'id_tienda'  => $tiendaId, // Asignamos la tienda que acabamos de crear
            'nombre'     => 'Rodolfo - SUPER ADMIN',
            'email'      => $superAdminEmail,
            'password'   => $superAdminPassword,
            'rol'        => 'Super Admin', // Rol de máximo acceso
            'estado'     => 1, // Activo
        ];

        // Guardar el usuario
        $usuarioModel->insert($userData);
        $usuarioId = $usuarioModel->getInsertID();

        // 5. Actualizar la Tienda para asignar el Dueño (el Super Admin)
        $tiendaModel->update($tiendaId, ['id_usuario_dueno' => $usuarioId]);

        echo "\n✅ Seeder Completado: Super Admin y Tienda Global creados con ID Tienda: $tiendaId y ID Usuario: $usuarioId\n";
    }
}
