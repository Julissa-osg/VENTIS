<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Inventario
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
<style>
    /* Estilo para las columnas VIP */
    .col-vip {
        background-color: #fff3cd !important; /* Fondo amarillito suave */
    }
    .badge-stock-low {
        background-color: #e74a3b;
        color: white;
    }
    .badge-stock-ok {
        background-color: #1cc88a;
        color: white;
    }
    /* Estilos Buscador redondeado */
    .dataTables_filter input {
        border: 2px solid #e3e6f0;
        border-radius: 20px;
        padding: 5px 15px;
        outline: none;
    }
    .dataTables_filter input:focus {
        border-color: #4e73df;
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">
            <i class="bi bi-boxes me-2 text-primary"></i> Mi Inventario
        </h1>
        <p class="text-muted mb-0">Gestión de productos y existencias.</p>
    </div>
    <a href="<?= site_url('tienda/productos/crear') ?>" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i> Nuevo Producto
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4 border-0">
    <div class="card-body">
        
        <?php if (empty($productos)): ?>
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-1 text-gray-300"></i>
                <p class="lead mt-3 text-gray-800">Tu inventario está vacío.</p>
                <a href="<?= site_url('tienda/productos/crear') ?>" class="btn btn-primary mt-2">Registrar Primer Producto</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table id="tablaProductos" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th class="text-center">Stock</th>
                            <th>Precio Venta</th>
                            
                            <?php if ($is_vip): ?>
                                <th class="col-vip text-dark border-bottom-warning"><i class="bi bi-star-fill text-warning me-1"></i> Costo</th>
                                <th class="col-vip text-dark border-bottom-warning"><i class="bi bi-graph-up-arrow text-success me-1"></i> Ganancia</th>
                                <th class="col-vip text-dark border-bottom-warning"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Ubicación</th>
                            <?php endif; ?>

                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($producto['nombre']) ?></div>
                                    <small class="text-muted">ID: <?= $producto['id'] ?></small>
                                </td>
                                <td><span class="font-monospace text-secondary bg-light px-2 rounded"><?= esc($producto['codigo_barras']) ?></span></td>
                                
                                <td class="text-center">
                                    <?php if($producto['stock_actual'] <= 5): ?>
                                        <span class="badge badge-stock-low rounded-pill">Bajo: <?= $producto['stock_actual'] ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-stock-ok rounded-pill"><?= $producto['stock_actual'] ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="fw-bold text-success">$<?= number_format($producto['precio_venta'], 2) ?></td>

                                <?php if ($is_vip): ?>
                                    <?php 
                                        $ganancia = $producto['precio_venta'] - $producto['precio_compra'];
                                        $claseGanancia = $ganancia > 0 ? 'text-success' : 'text-danger';
                                    ?>
                                    <td class="col-vip">$<?= number_format($producto['precio_compra'], 2) ?></td>
                                    <td class="col-vip fw-bold <?= $claseGanancia ?>">
                                        $<?= number_format($ganancia, 2) ?>
                                    </td>
                                    <td class="col-vip small"><?= esc($producto['ubicacion_mapa'] ?? '-') ?></td>
                                <?php endif; ?>

                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('tienda/productos/editar/' . $producto['id']) ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" disabled title="Eliminar (Próximamente)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!$is_vip): ?>
                <div class="mt-3 p-2 bg-light border rounded text-center small text-muted">
                    <i class="bi bi-lock-fill me-1"></i> Desbloquea el análisis de costos, márgenes de ganancia y ubicación de productos con <a href="<?= site_url('tienda/vip/canjear') ?>">Membresía VIP</a>.
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaProductos').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json' 
            },
            // Ordenar por nombre (Columna 0)
            order: [[0, 'asc']],
            // Estilos DOM de Bootstrap
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>'
        });
    });
</script>

<?= $this->endSection() ?>