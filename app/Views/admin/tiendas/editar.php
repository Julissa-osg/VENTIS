<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
    Editar Tienda
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Editar Tienda</h1>
        <p class="text-muted">Modificando datos de: <strong><?= esc($tienda['nombre']) ?></strong></p>
    </div>
    <div class="col-auto">
        <a href="<?= site_url('admin/tiendas') ?>" class="btn btn-dark">
            <i class="bi bi-arrow-left me-2"></i> Cancelar
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body p-4">

        <form action="<?= site_url('admin/tiendas/actualizar') ?>" method="post"> 
            <?= csrf_field() ?>
            
            <input type="hidden" name="id" value="<?= esc($tienda['id']) ?>">

            <h4 class="text-secondary fw-bold border-bottom pb-2 mb-4">Información General</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre de la Tienda</label>
                    <input type="text" name="nombre" class="form-control" 
                        value="<?= old('nombre', $tienda['nombre']) ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="propietario" class="form-label">Propietario</label>
                    <input type="text" name="propietario" class="form-control" 
                        value="<?= old('propietario', $tienda['propietario']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ruc" class="form-label">RUC</label>
                    <input type="text" name="ruc" class="form-control" 
                        value="<?= old('ruc', $tienda['ruc']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado Operativo</label>
                    <select name="estado" class="form-select">
                        <option value="1" <?= $tienda['estado'] == 1 ? 'selected' : '' ?>>Activa</option>
                        <option value="0" <?= $tienda['estado'] == 0 ? 'selected' : '' ?>>Suspendida / Inactiva</option>
                    </select>
                </div>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle-fill"></i> Nota: Para cambiar el administrador o contraseña, usa el módulo de "Usuarios". Aquí solo se editan los datos del comercio.
            </div>

            <hr class="my-4">

            <button type="submit" class="btn btn-warning w-100 fw-bold">
                <i class="bi bi-save-fill me-2"></i> Guardar Cambios
            </button>

        </form>
    </div>
</div>

<?= $this->endSection() ?>