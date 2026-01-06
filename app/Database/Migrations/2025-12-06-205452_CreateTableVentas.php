<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableVentas extends Migration
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
            'id_tienda' => [ // Relación crucial con la tienda que realiza la venta (Multi-Tenencia).
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_usuario' => [ // El vendedor que hizo la transacción (para auditoría).
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'cliente_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'metodo_pago' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Efectivo',
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1, // 1: Completada, 0: Cancelada
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            // Las ventas generalmente no necesitan updated_at o deleted_at,
            // pero si necesitas historial de cancelaciones, 'estado' es suficiente.
        ]);
        $this->forge->addKey('id', true);

        // --- LÍNEAS DE FOREIGN KEY ELIMINADAS TEMPORALMENTE ---
        // $this->forge->addForeignKey('id_tienda', 'tiendas', 'id');
        // $this->forge->addForeignKey('id_usuario', 'usuarios', 'id');

        $this->forge->createTable('ventas');
    }

    public function down()
    {
        $this->forge->dropTable('ventas');
    }
}