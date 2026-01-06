<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeys extends Migration
{
    public function up()
    {
        // 1. Relaciones de la tabla USUARIOS
        $this->forge->addForeignKey('id_tienda', 'tiendas', 'id', 'CASCADE', 'CASCADE', 'fk_usuarios_tiendas');

        // 2. Relaciones de la tabla TIENDAS
        $this->forge->addForeignKey('id_usuario_dueno', 'usuarios', 'id', 'RESTRICT', 'CASCADE', 'fk_tiendas_usuarios');

        // 3. Relaciones de la tabla PRODUCTOS
        $this->forge->addForeignKey('id_tienda', 'tiendas', 'id', 'CASCADE', 'CASCADE', 'fk_productos_tiendas');

        // 4. Relaciones de la tabla VENTAS
        $this->forge->addForeignKey('id_tienda', 'tiendas', 'id', 'RESTRICT', 'CASCADE', 'fk_ventas_tiendas');
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id', 'RESTRICT', 'RESTRICT', 'fk_ventas_usuarios');

        // 5. Relaciones de la tabla DETALLE_VENTA
        $this->forge->addForeignKey('id_venta', 'ventas', 'id', 'CASCADE', 'CASCADE', 'fk_detalle_venta_ventas');
        $this->forge->addForeignKey('id_producto', 'productos', 'id', 'RESTRICT', 'RESTRICT', 'fk_detalle_venta_productos');

        // 6. Relaciones de la tabla CODIGOS_VIP
        $this->forge->addForeignKey('id_tienda_uso', 'tiendas', 'id', 'SET NULL', 'CASCADE', 'fk_codigos_vip_tiendas');

        // 7. Relaciones de la tabla AUDITORIA
        $this->forge->addForeignKey('id_tienda', 'tiendas', 'id', 'CASCADE', 'CASCADE', 'fk_auditoria_tiendas');
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id', 'CASCADE', 'CASCADE', 'fk_auditoria_usuarios');

        // NO es necesario llamar a process() o createTable(), solo basta con addForeignKey.
    }

    public function down()
    {
        // ... (La función down() puede quedar igual ya que dropForeignKey() sí existe)

        // 1. Drop Foreign Keys de AUDITORIA
        $this->forge->dropForeignKey('auditoria', 'fk_auditoria_tiendas');
        $this->forge->dropForeignKey('auditoria', 'fk_auditoria_usuarios');

        // 2. Drop Foreign Keys de CODIGOS_VIP
        $this->forge->dropForeignKey('codigos_vip', 'fk_codigos_vip_tiendas');

        // 3. Drop Foreign Keys de DETALLE_VENTA
        $this->forge->dropForeignKey('detalle_venta', 'fk_detalle_venta_ventas');
        $this->forge->dropForeignKey('detalle_venta', 'fk_detalle_venta_productos');

        // 4. Drop Foreign Keys de VENTAS
        $this->forge->dropForeignKey('ventas', 'fk_ventas_tiendas');
        $this->forge->dropForeignKey('ventas', 'fk_ventas_usuarios');

        // 5. Drop Foreign Keys de PRODUCTOS
        $this->forge->dropForeignKey('productos', 'fk_productos_tiendas');

        // 6. Drop Foreign Keys de TIENDAS
        $this->forge->dropForeignKey('tiendas', 'fk_tiendas_usuarios');

        // 7. Drop Foreign Keys de USUARIOS
        $this->forge->dropForeignKey('usuarios', 'fk_usuarios_tiendas');
    }
}
