<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Super Admin | Panel Principal
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Panel de Control Central</h1>
            <p class="text-muted">Bienvenido, <strong class="text-dark"><?= session()->get('nombre') ?></strong>. Tienes el control total.</p>
        </div>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-house-door-fill"></i> Inicio
        </a>
    </div>

    <div class="row">
        
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 5px solid var(--color-acento-vibrante) !important;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-uppercase mb-1" style="color: var(--color-acento-vibrante);">
                                Administración
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">Gestión de Tiendas</div>
                            <p class="mb-0 small text-muted mt-2">Crear, editar y suspender tiendas del sistema.</p>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shop fa-2x text-gray-300" style="font-size: 2.5rem; color: #ccc;"></i>
                        </div>
                    </div>
                    <a href="<?= site_url('admin/tiendas') ?>" class="btn btn-sm btn-dark mt-3 w-100">
                        Ir a Tiendas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 5px solid #28a745 !important;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Marketing & Promociones
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">Códigos VIP</div>
                            <p class="mb-0 small text-muted mt-2">Generar códigos de activación para clientes.</p>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-ticket-perforated-fill" style="font-size: 2.5rem; color: #ccc;"></i>
                        </div>
                    </div>
                    <a href="<?= site_url('admin/codigos') ?>" class="btn btn-sm btn-success mt-3 w-100">
                        Gestionar Códigos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 fw-bold text-primary">Resumen del Sistema</h6>
        </div>
        <div class="card-body">
            <p>Desde aquí puedes monitorear el rendimiento global. Selecciona una opción arriba para comenzar.</p>
        </div>
    </div>

</div>

<?= $this->endSection() ?>