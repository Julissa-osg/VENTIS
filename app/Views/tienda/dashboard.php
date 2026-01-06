<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?> Panel Principal <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid p-0">
    
    <div class="alert alert-light border shadow-sm mt-2 d-flex justify-content-between align-items-center" role="alert">
        <div>
            <h4 class="alert-heading mb-1 text-primary fw-bold">¡Hola, <?= esc($nombre_usuario) ?>!</h4>
            <p class="mb-0 text-muted">
                Estás gestionando: <strong>Mi Tienda #<?= esc($id_tienda) ?></strong> | Rol: <span class="badge bg-secondary"><?= esc($rol) ?></span>
            </p>
        </div>
        <div class="d-none d-md-block">
            <i class="bi bi-person-circle text-gray-300" style="font-size: 2.5rem;"></i>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-2">
            <div class="card border-start border-4 border-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Vendido Hoy</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">S/ <?= number_format($totalHoy, 2) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-2">
            <div class="card border-start border-4 border-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Transacciones</div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?= $ventasHoy ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-2">
            <div class="card border-start border-4 border-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">Alerta Stock</div>
                            <div class="h3 mb-0 fw-bold text-gray-800"><?= $stockBajo ?></div>
                            <small class="text-danger">Productos por agotarse</small>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle-fill fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-white shadow overflow-hidden">
                <div class="card-body p-4 d-flex justify-content-between align-items-center position-relative">
                    <div style="z-index: 2;">
                        <h2 class="fw-bold text-warning"><i class="bi bi-cart4 me-2"></i>Punto de Venta</h2>
                        <p class="lead mb-3">Registra ventas, emite boletas y gestiona la caja rápidamente.</p>
                        <a href="<?= site_url('tienda/ventas') ?>" class="btn btn-warning fw-bold text-dark px-4 shadow">
                            IR A VENDER AHORA <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                    <i class="bi bi-shop position-absolute" style="font-size: 12rem; right: -30px; bottom: -60px; opacity: 0.15; color: white;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-bar-chart-line-fill me-2"></i>Ventas de la Semana</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="graficoSemana"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 fw-bold text-info"><i class="bi bi-pie-chart-fill me-2"></i>Top 5 Productos</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                        <canvas id="graficoTop"></canvas>
                    </div>
                    <div class="mt-4 text-center small text-muted">
                        Productos con más unidades vendidas
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3 text-muted fw-bold text-uppercase border-bottom pb-2">Accesos Directos</h5>
    <div class="row">
        
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam text-primary mb-3" style="font-size: 2rem;"></i>
                    <h5 class="card-title fw-bold">Inventario</h5>
                    <p class="card-text small text-muted">Añadir productos, editar precios y stock.</p>
                    <a href="<?= site_url('tienda/productos') ?>" class="btn btn-outline-primary btn-sm w-100 stretched-link">Gestionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text text-info mb-3" style="font-size: 2rem;"></i>
                    <h5 class="card-title fw-bold">Reportes</h5>
                    <p class="card-text small text-muted">Historial de ventas y exportación (PDF/Excel).</p>
                    <a href="<?= site_url('tienda/reportes') ?>" class="btn btn-outline-info btn-sm w-100 stretched-link">Ver Reportes</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill text-warning mb-3" style="font-size: 2rem;"></i>
                    <h5 class="card-title fw-bold">Zona VIP</h5>
                    <p class="card-text small text-muted">Canjear códigos promocionales.</p>
                    <a href="<?= site_url('tienda/vip/canjear') ?>" class="btn btn-outline-warning text-dark btn-sm w-100 stretched-link">Acceder</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- GRÁFICO 1: VENTAS SEMANA (Barras) ---
    const ctxSemana = document.getElementById('graficoSemana').getContext('2d');
    new Chart(ctxSemana, {
        type: 'line', // 'line' se ve elegante para tendencias
        data: {
            labels: <?= $graficoFechas ?>,
            datasets: [{
                label: 'Ingresos (S/)',
                data: <?= $graficoTotales ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3, // Curvatura de la línea
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) { return 'S/ ' + value; }
                    },
                    grid: { borderDash: [2], drawBorder: false }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // --- GRÁFICO 2: TOP PRODUCTOS (Dona) ---
    const ctxTop = document.getElementById('graficoTop').getContext('2d');
    new Chart(ctxTop, {
        type: 'doughnut',
        data: {
            labels: <?= $topNombres ?>,
            datasets: [{
                data: <?= $topCantidades ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%', // Hace el agujero de la dona más grande
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { boxWidth: 15 }
                }
            }
        },
    });
</script>
<?= $this->endSection() ?>