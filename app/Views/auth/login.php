<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>"> 
</head>
<body>

<div class="auth-container">
    <div class="position-absolute top-0 start-0 p-3">
    <a href="<?= base_url() ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver al Inicio
    </a>
</div>
    <div class="auth-card">
    
        <div class="auth-panel-form order-md-2"> <h2>Inicia Sesión aquí</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger p-2" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login/auth') ?>" method="post"> 
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control 
                                <?= $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                value="<?= old('email') ?>" required>
                    <?php if ($validation->hasError('email')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control 
                                <?= $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                required>
                    <?php if ($validation->hasError('password')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-end mt-2">
                        <a href="#" class="text-muted small">¿Olvidaste la contraseña?</a>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-auth-submit w-100 mt-3">Iniciar Sesión</button>
                
                <p class="text-center text-muted small mt-4">O usa tu cuenta</p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-secondary rounded-circle" disabled><i class="bi bi-facebook"></i></button>
                    <button type="button" class="btn btn-outline-secondary rounded-circle" disabled><i class="bi bi-google"></i></button>
                    <button type="button" class="btn btn-outline-secondary rounded-circle" disabled><i class="bi bi-tiktok"></i></button>
                </div>
            </form>
        </div>

        <div class="auth-panel-message order-md-1"> <h2>¡Comienza tu viaje ahora!</h2>
            <p class="mt-3">
                Si aún no tienes una cuenta, únete a nosotros y comienza a gestionar tu negocio de ventas.
            </p>
            <a href="<?= site_url('registro') ?>" class="btn btn-auth-secondary mt-4 w-50 mx-auto">
                Registrarse <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>