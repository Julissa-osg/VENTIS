<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableDetalleVenta extends Migration
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
            'id_venta' => [ // Clave foránea a la cabecera (la factura general).
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_producto' => [ // El producto vendido.
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'cantidad' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'precio_unitario_venta' => [ // El precio que se vendió.
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'precio_unitario_costo' => [ // El costo del preventista al momento de la venta.
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'ganancia_unitaria' => [ // Venta - Costo. Clave para los reportes de ganancia.
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id', true);

        // --- LÍNEAS DE FOREIGN KEY ELIMINADAS TEMPORALMENTE ---
        // $this->forge->addForeignKey('id_venta', 'ventas', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_producto', 'productos', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('detalle_venta');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_venta');
    }
}
