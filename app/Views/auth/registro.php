<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Tienda</title>
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

        <div class="auth-panel-message order-md-1">
            <h2>Hola</h2>
            <p class="mt-3">
                Si ya tienes una cuenta, inicia sesión.
            </p>
            <a href="<?= site_url('login') ?>" class="btn btn-auth-secondary mt-4 w-50 mx-auto">
                <i class="bi bi-arrow-left me-2"></i> Iniciar Sesión
            </a>
        </div>
        
        <div class="auth-panel-form order-md-2">
            <h2>Regístrate aquí</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger p-2" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success p-2" role="alert">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>


            <form action="<?= site_url('registro/guardar') ?>" method="post"> 
                <?= csrf_field() ?>

                <h4 class="mb-3">Datos de la Tienda</h4>
                <div class="mb-3">
                    <label for="nombre_tienda" class="form-label">Nombre de tu Negocio/Tienda</label>
                    <input type="text" name="nombre_tienda" id="nombre_tienda" class="form-control 
                                         <?= $validation->hasError('nombre_tienda') ? 'is-invalid' : '' ?>" 
                                         value="<?= old('nombre_tienda') ?>" required>
                    <?php if ($validation->hasError('nombre_tienda')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('nombre_tienda') ?></div>
                    <?php endif; ?>
                </div>

                <h4 class="mt-4 mb-3">Datos del Administrador</h4>
                
                <div class="mb-3">
                    <label for="usuario_nombre" class="form-label">Nombre Completo del Administrador</label>
                    <input type="text" name="usuario_nombre" id="usuario_nombre" class="form-control 
                                         <?= $validation->hasError('usuario_nombre') ? 'is-invalid' : '' ?>" 
                                         value="<?= old('usuario_nombre') ?>" required>
                    <?php if ($validation->hasError('usuario_nombre')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('usuario_nombre') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="hobby" class="form-label">Tu Hobby o Pasatiempo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-controller"></i></span>
                        <input type="text" name="hobby" id="hobby" class="form-control 
                                             <?= $validation->hasError('hobby') ? 'is-invalid' : '' ?>" 
                                             value="<?= old('hobby') ?>" >
                    </div>
                    <?php if ($validation->hasError('hobby')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('hobby') ?></div>
                    <?php endif; ?>
                    <small class="text-muted small">Qué te gusta hacer en tu tiempo libre.</small>
                </div>

                <div class="mb-3">
                    <label for="usuario_email" class="form-label">Email (Usuario de Login)</label>
                    <input type="email" name="usuario_email" id="usuario_email" class="form-control 
                                         <?= $validation->hasError('usuario_email') ? 'is-invalid' : '' ?>" 
                                         value="<?= old('usuario_email') ?>" required>
                    <?php if ($validation->hasError('usuario_email')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('usuario_email') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="usuario_password" class="form-label">
                        Contraseña <span class="text-danger fw-bold small" style="font-size: 0.85em;">(Mínimo 8 caracteres)</span>
                    </label>
                    <input type="password" name="usuario_password" id="usuario_password" class="form-control 
                                         <?= $validation->hasError('usuario_password') ? 'is-invalid' : '' ?>" 
                                         required>
                    <?php if ($validation->hasError('usuario_password')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('usuario_password') ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="pass_confirm" class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control 
                                         <?= $validation->hasError('pass_confirm') ? 'is-invalid' : '' ?>" 
                                         required>
                    <?php if ($validation->hasError('pass_confirm')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('pass_confirm') ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-auth-submit w-100 mt-3">Registrar</button>
                
                <p class="text-center text-muted small mt-4">O usa tu cuenta</p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-secondary rounded-circle" disabled><i class="bi bi-facebook"></i></button>
                    <button type="button" class="btn btn-outline-secondary rounded-circle" disabled><i class="bi bi-google"></i></button>
                    <button type="button" class="btn btn-outline-secondary rounded-circle" disabled><i class="bi bi-tiktok"></i></button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>