<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SistemaVentas | Inicio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="custom-background">

<div id="landing-container" class="d-flex flex-column">

<header class="notch-menu mx-auto mt-4 p-3 rounded-pill shadow-lg">
    <nav class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand text-light fw-bold" href="<?= base_url('/') ?>">
            <i class="bi bi-shop me-2"></i> MiVenta
        </a>

        <ul class="nav">
            <li class="nav-item"><a class="nav-link menu-link" href="<?= base_url('/') ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link menu-link" href="#">Productos</a></li>
            <li class="nav-item"><a class="nav-link menu-link" href="#">Contacto</a></li>
        </ul>

        <div class="d-flex">
            <a href="<?= base_url('registro') ?>" class="btn btn-action-primary me-2">
                Comenzar <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </nav>
</header>

<main class="container-fluid flex-grow-1 d-flex">
    <div class="row w-100 align-items-center">

        <div class="col-md-6 d-flex align-items-end p-5">
            <div class="align-self-end">
                <h1 class="display-1 fw-bolder text-light shadow-text">
                    Glow Lab <br> 2030:
                </h1>
                <p class="text-light-50 fs-5 mt-3 shadow-text">
                    Centro de Comando Glow.
                </p>
            </div>
        </div>

        <div class="col-md-6 d-flex justify-content-center">
            <div class="carousel-placeholder p-4 rounded-3 shadow-lg">
                <h4 class="text-light">Panel Holístico 2030:</h4>
                <p class="text-light-50">
                    Controla cada ángulo de tu negocio con una interfaz intuitiva,
                    diseñada para la máxima claridad visual.
                </p>
                <a href="<?= base_url('login') ?>" class="btn btn-action-secondary mt-3">
                    Ir a Iniciar Sesión
                </a>
            </div>
        </div>

    </div>
</main>

<footer class="container-fluid p-3">
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <a href="<?= base_url('login') ?>" class="btn btn-action-circle shadow-lg me-3" title="Iniciar Sesión">
                <i class="bi bi-person-fill fs-4"></i>
            </a>
        </div>
    </div>
</footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
