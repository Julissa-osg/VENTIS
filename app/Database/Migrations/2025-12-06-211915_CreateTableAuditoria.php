<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableAuditoria extends Migration
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
            'id_tienda' => [ // A qué tienda corresponde esta acción.
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_usuario' => [ // Quién hizo la acción (el dueño o el vendedor).
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'accion' => [ // Descripción de la acción (Ej: "Eliminó producto ID 5", "Cambió precio de la camisa").
                'type'       => 'TEXT',
            ],
            'ip_address' => [ // Para mayor seguridad (rastreo de origen).
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);
        $this->forge->addKey('id', true);

        // --- LÍNEAS DE FOREIGN KEY ELIMINADAS TEMPORALMENTE ---
        // $this->forge->addForeignKey('id_tienda', 'tiendas', 'id');
        // $this->forge->addForeignKey('id_usuario', 'usuarios', 'id');

        $this->forge->createTable('auditoria');
    }

    public function down()
    {
        $this->forge->dropTable('auditoria');
    }
}
