<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Editar Producto
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 text-gray-800 fw-bold">
                <i class="bi bi-pencil-fill me-2 text-warning"></i> Editar Producto
            </h2>
            <a href="<?= site_url('tienda/productos') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-lg me-1"></i> Cancelar
            </a>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0">
            <div class="card-body p-4">
                
                <form action="<?= site_url('tienda/productos/actualizar') ?>" method="post"> 
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= esc($producto['id']) ?>">

                    <div class="mb-4 text-center">
                        <span class="badge bg-light text-dark border px-3 py-2">Editando: <strong><?= esc($producto['nombre']) ?></strong></span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="nombre" class="form-label fw-bold">Nombre del Producto</label>
                            <input type="text" name="nombre" id="nombre" class="form-control <?= $validation->hasError('nombre') ? 'is-invalid' : '' ?>" value="<?= old('nombre') ?? esc($producto['nombre']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-bold">Código de Barras (SKU)</label>
                            <input type="text" name="sku" id="sku" class="form-control bg-light <?= $validation->hasError('sku') ? 'is-invalid' : '' ?>" value="<?= old('sku') ?? esc($producto['codigo_barras']) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label for="stock" class="form-label fw-bold">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control <?= $validation->hasError('stock') ? 'is-invalid' : '' ?>" value="<?= old('stock') ?? esc($producto['stock_actual']) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label for="precio_venta" class="form-label fw-bold text-success">Precio Venta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-success">$</span>
                                <input type="number" step="0.01" name="precio_venta" id="precio_venta" class="form-control border-success fw-bold <?= $validation->hasError('precio_venta') ? 'is-invalid' : '' ?>" value="<?= old('precio_venta') ?? esc($producto['precio_venta']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Zona VIP</h6>

                    <?php if ($is_vip): ?>
                        <div class="row g-3 bg-light p-3 rounded border">
                            <div class="col-md-6">
                                <label for="precio_costo" class="form-label">Costo ($)</label>
                                <input type="number" step="0.01" name="precio_costo" id="precio_costo" class="form-control <?= $validation->hasError('precio_costo') ? 'is-invalid' : '' ?>" value="<?= old('precio_costo') ?? esc($producto['precio_compra']) ?? 0 ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="ubicacion_mapa" class="form-label">Ubicación</label>
                                <input type="text" name="ubicacion_mapa" id="ubicacion_mapa" class="form-control <?= $validation->hasError('ubicacion_mapa') ? 'is-invalid' : '' ?>" value="<?= old('ubicacion_mapa') ?? esc($producto['ubicacion_mapa']) ?>">
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 small">
                            <i class="bi bi-lock-fill me-1"></i> Desbloquea campos de Costo y Ubicación haciéndote VIP.
                        </div>
                    <?php endif; ?>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>