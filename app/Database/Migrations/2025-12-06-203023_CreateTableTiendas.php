<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTiendas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_usuario_dueno' => [ // Dueño de la tienda, relacionado con la tabla 'usuarios'
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true, // Permite que la tienda exista antes que el Super Admin.
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'ruc' => [ // ¡CORREGIDO Y AÑADIDO!
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'propietario' => [ // ¡AÑADIDO!
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'estado_vip' => [ // ¡AÑADIDO!
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'fecha_expiracion_vip' => [ 
                'type' => 'DATETIME',
                'null' => true,
            ],
            'estado' => [ 
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1, // 1: Activa, 0: Suspendida
            ],
            'created_at' => [
                'type' => 'DATETIME', 
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME', 
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME', 
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);
        
        // --- LÍNEA DE FOREIGN KEY ELIMINADA TEMPORALMENTE ---
        // $this->forge->addForeignKey('id_usuario_dueno', 'usuarios', 'id');

        $this->forge->createTable('tiendas');
    }

    public function down()
    {
        $this->forge->dropTable('tiendas');
    }
}