<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ? $this->renderSection('title') : 'SistemaVentas' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">

    <?= $this->renderSection('css') ?>
</head>
<body>

<div id="wrapper">
    <div id="sidebar-wrapper">
        <div class="sidebar-heading">
            <i class="bi bi-fire me-2"></i> MiVenta
        </div>
        <div class="list-group list-group-flush">
            
            <?php 
            // Detectar ruta actual para marcar el menú activo
            $uri = service('uri');
            $segment1 = $uri->getSegment(1); 
            $segment2 = ($uri->getTotalSegments() >= 2) ? $uri->getSegment(2) : '';
            // Verificamos si estamos en la zona de Admin
            $esAdmin = ($segment1 == 'admin'); 
            ?>

            <?php if ($esAdmin): ?>
                <div class="small text-muted text-uppercase px-3 py-2">Administración</div>
                
                <a href="<?= site_url('admin/dashboard') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="<?= site_url('admin/tiendas') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'tiendas') ? 'active' : '' ?>">
                    <i class="bi bi-shop me-2"></i> Gestión Tiendas
                </a>
                <a href="<?= site_url('admin/usuarios') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'usuarios') ? 'active' : '' ?>">
                    <i class="bi bi-people-fill me-2"></i> Gestión Usuarios
                </a>
                <a href="<?= site_url('admin/codigos') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'codigos') ? 'active' : '' ?>">
                    <i class="bi bi-ticket-perforated me-2"></i> Códigos VIP
                </a>

            <?php else: ?>
                <div class="small text-muted text-uppercase px-3 py-2">Mi Negocio</div>

                <a href="<?= site_url('tienda/dashboard') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-grid-fill me-2"></i> Inicio
                </a>
                <a href="<?= site_url('tienda/ventas') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'ventas') ? 'active' : '' ?>">
                    <i class="bi bi-calculator-fill me-2"></i> TPV (Vender)
                </a>
                <a href="<?= site_url('tienda/productos') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'productos') ? 'active' : '' ?>">
                    <i class="bi bi-box-seam me-2"></i> Inventario
                </a>
                <a href="<?= site_url('tienda/reportes') ?>" class="list-group-item list-group-item-action <?= ($segment2 == 'reportes') ? 'active' : '' ?>">
                    <i class="bi bi-graph-up me-2"></i> Reportes
                </a>
                <a href="<?= site_url('tienda/vip/canjear') ?>" class="list-group-item list-group-item-action <?= ($uri->getTotalSegments() >= 3 && $uri->getSegment(3) == 'canjear') ? 'active' : '' ?>">
                    <i class="bi bi-star me-2"></i> Zona VIP
                </a>
            <?php endif; ?>

            <div class="mt-4 pt-3 border-top border-secondary">
                <a href="<?= site_url('logout') ?>" class="list-group-item list-group-item-action text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg top-header border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i> Hola, <strong><?= session()->get('nombre') ?? 'Usuario' ?></strong>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="<?= site_url('perfil') ?>">
                                    <i class="bi bi-person-badge me-2"></i> Mi Perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>