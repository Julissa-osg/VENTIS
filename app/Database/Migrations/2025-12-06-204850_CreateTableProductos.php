<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProductos extends Migration
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
            'id_tienda' => [ // CRUCIAL para Multi-Tenencia
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'codigo_barras' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'stock_actual' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'precio_compra' => [ // Costo del preventista (Para calcular GANANCIA).
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'precio_venta' => [ // Precio al público.
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'ubicacion_mapa' => [ // Para tu idea futura (guarda coordenadas o ID de estante).
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1, // 1: Activo, 0: Inactivo/Eliminado
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
        // La agregaremos en el archivo final 'AddForeignKeys'.

        $this->forge->createTable('productos');
    }

    public function down()
    {
        $this->forge->dropTable('productos');
    }
}