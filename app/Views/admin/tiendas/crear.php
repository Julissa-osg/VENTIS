<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Crear Nueva Tienda
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">
            <i class="bi bi-shop me-2"></i> Crear Nueva Tienda
        </h1>
        <p class="text-muted">Estás creando una nueva unidad de negocio y su administrador principal.</p>
    </div>
    <div class="col-auto">
        <a href="<?= site_url('admin/tiendas') ?>" class="btn btn-dark">
            <i class="bi bi-arrow-left me-2"></i> Volver al Listado
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body p-4">

        <form action="<?= site_url('admin/tiendas/guardar') ?>" method="post"> 
            <?= csrf_field() ?>

            <h4 class="text-secondary fw-bold border-bottom pb-2 mb-4">Datos de la Tienda</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre de la Tienda <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control 
                        <?= $validation->hasError('nombre') ? 'is-invalid' : '' ?>" 
                        value="<?= old('nombre') ?>" required>
                    <?php if ($validation->hasError('nombre')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('nombre') ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="propietario" class="form-label">Nombre del Propietario <span class="text-danger">*</span></label>
                    <input type="text" name="propietario" id="propietario" class="form-control 
                        <?= $validation->hasError('propietario') ? 'is-invalid' : '' ?>" 
                        value="<?= old('propietario') ?>" required>
                    <?php if ($validation->hasError('propietario')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('propietario') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-5">
                <label for="ruc" class="form-label">RUC (Opcional)</label>
                <input type="text" name="ruc" id="ruc" class="form-control 
                    <?= $validation->hasError('ruc') ? 'is-invalid' : '' ?>" 
                    value="<?= old('ruc') ?>">
                <?php if ($validation->hasError('ruc')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('ruc') ?></div>
                <?php endif; ?>
            </div>
            
            <h4 class="text-secondary fw-bold border-bottom pb-2 mb-4">Datos del Administrador de Tienda</h4>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="admin_nombre" class="form-label">Nombre del Admin <span class="text-danger">*</span></label>
                    <input type="text" name="admin_nombre" id="admin_nombre" class="form-control 
                        <?= $validation->hasError('admin_nombre') ? 'is-invalid' : '' ?>" 
                        value="<?= old('admin_nombre') ?>" required>
                    <?php if ($validation->hasError('admin_nombre')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('admin_nombre') ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="admin_email" class="form-label">Email del Admin (Login) <span class="text-danger">*</span></label>
                    <input type="email" name="admin_email" id="admin_email" class="form-control 
                        <?= $validation->hasError('admin_email') ? 'is-invalid' : '' ?>" 
                        value="<?= old('admin_email') ?>" required>
                    <?php if ($validation->hasError('admin_email')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('admin_email') ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="admin_password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="admin_password" id="admin_password" class="form-control 
                        <?= $validation->hasError('admin_password') ? 'is-invalid' : '' ?>" 
                        required>
                    <?php if ($validation->hasError('admin_password')): ?>
                        <div class="invalid-feedback"><?= $validation->getError('admin_password') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <hr class="my-4">

            <button type="submit" class="btn btn-main-action btn-lg w-100">
                <i class="bi bi-cloud-check-fill me-2"></i> Crear Tienda y Habilitar Acceso
            </button>

        </form>
    </div>
</div>

<?= $this->endSection() ?>