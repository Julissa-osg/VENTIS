<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUsuarios extends Migration
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
            'id_tienda' => [ // <--- AÑADIDO: Crucial para relacionar el usuario con su tienda
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true, // Nullable porque el SuperAdmin crea la tienda después
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [ // Usaremos esto para el Login
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'rol' => [ // <--- CORREGIDO: Se llamaba 'perfil', ahora 'rol' para coincidir con Seeder
                'type'       => 'ENUM',
                'constraint' => ['Super Admin', 'Admin Tienda', 'Vendedor'], // Aseguramos coincidencia con Seeder
                'default'    => 'Admin Tienda',
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        
        // NO agregamos ForeignKey a 'tiendas' aquí porque la tabla 'tiendas' 
        // se crea DESPUÉS de esta (según la fecha del archivo).
        
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
