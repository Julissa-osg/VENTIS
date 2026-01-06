<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Listado de Tiendas
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">

<style>
    /* Tarjeta contenedora */
    .card-tiendas {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    /* Buscador y selectores de DataTables */
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e9ecef;
        border-radius: 20px;
        padding: 6px 15px;
        margin-left: 10px;
        outline: none;
        transition: all 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    /* Botón principal de acción */
    .btn-crear {
        background-color: #4e73df;
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.15s ease;
    }
    .btn-crear:hover {
        background-color: #2e59d9;
        transform: translateY(-1px);
        color: white;
    }

    /* Badges de estado */
    .badge-vip {
        background-color: #f6c23e; /* Amarillo dorado */
        color: #856404;
    }
    .badge-base {
        background-color: #858796;
        color: white;
    }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">
            <i class="bi bi-shop-window me-2 text-primary"></i> Gestión de Tiendas
        </h1>
        <p class="text-muted mb-0 mt-1">Administra todas las tiendas registradas y sus estados de membresía.</p>
    </div>
    <a href="<?= site_url('admin/tiendas/crear') ?>" class="btn btn-crear">
        <i class="bi bi-plus-lg me-2"></i> Nueva Tienda
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-5" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card card-tiendas mb-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tablaAdminTiendas" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0 rounded-start">ID</th>
                        <th class="border-0">Nombre Tienda</th>
                        <th class="border-0">Propietario</th>
                        <th class="border-0">RUC</th>
                        <th class="text-center border-0">Plan</th>
                        <th class="text-center border-0">Estado</th>
                        <th class="text-center border-0 rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tiendas)): ?>
                        <?php foreach ($tiendas as $tienda): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-secondary">#<?= $tienda['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-shop text-primary"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?= $tienda['nombre'] ?></span>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-person text-muted me-1"></i><?= $tienda['propietario'] ?>
                            </td>
                            <td class="text-muted small font-monospace">
                                <?= $tienda['ruc'] ?? '---' ?>
                            </td>
                            <td class="text-center">
                                <?php if($tienda['estado_vip']): ?>
                                    <span class="badge badge-vip rounded-pill px-3 py-2 shadow-sm">
                                        <i class="bi bi-star-fill me-1"></i> VIP
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-base rounded-pill px-3 py-2">Base</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($tienda['estado']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">
                                        <i class="bi bi-check-circle me-1"></i> Activa
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25">
                                        <i class="bi bi-x-circle me-1"></i> Baja
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('admin/tiendas/editar/' . $tienda['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar detalles">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <form action="<?= site_url('admin/tiendas/eliminar/' . $tienda['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Confirma que desea SUSPENDER esta tienda?\nEsta acción impedirá el acceso a sus usuarios.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-1" title="Suspender Tienda">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializa DataTables con opciones visuales mejoradas
        $('#tablaAdminTiendas').DataTable({
            responsive: true,
            lengthMenu: [10, 25, 50],
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json' 
            },
            // Ordenar por defecto por ID (Columna 0)
            order: [[0, 'asc']],
            // Ocultar la opción de "Procesando"
            processing: false,
            // Clases de Bootstrap para la tabla
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>'
        });

        // Inicializar Tooltips (globos de ayuda al pasar mouse)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<?= $this->endSection() ?>