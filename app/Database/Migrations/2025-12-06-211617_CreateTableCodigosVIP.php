<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCodigosVIP extends Migration
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
            'codigo' => [ // El código único que se ingresa (ej: MESVIP30).
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'precio' => [ // El precio que cuesta el código (ej: 5.00 soles).
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'duracion_dias' => [ // Duración que otorga el código (ej: 30, 365).
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_tienda_uso' => [ // Guarda qué tienda usó el código. Null si no ha sido usado.
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'fecha_uso' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['Activo', 'Usado', 'Inactivo'],
                'default'    => 'Activo',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);

        // --- LÍNEA DE FOREIGN KEY ELIMINADA TEMPORALMENTE ---
        // $this->forge->addForeignKey('id_tienda_uso', 'tiendas', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('codigos_vip');
    }

    public function down()
    {
        $this->forge->dropTable('codigos_vip');
    }
}