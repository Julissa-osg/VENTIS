<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Nuevo Producto
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 text-gray-800 fw-bold">
                <i class="bi bi-box-seam me-2 text-primary"></i> Registrar Producto
            </h2>
            <a href="<?= site_url('tienda/productos') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Formulario de Alta</h6>
            </div>
            <div class="card-body p-4">
                
                <form action="<?= site_url('tienda/productos/guardar') ?>" method="post"> 
                    <?= csrf_field() ?>

                    <h5 class="text-secondary mb-3"><i class="bi bi-info-circle me-2"></i>Información Básica</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="nombre" class="form-label fw-bold">Nombre del Producto</label>
                            <input type="text" name="nombre" id="nombre" class="form-control form-control-lg <?= $validation->hasError('nombre') ? 'is-invalid' : '' ?>" value="<?= old('nombre') ?>" placeholder="Ej: Arroz Costeño 1kg" required>
                            <div class="invalid-feedback"><?= $validation->getError('nombre') ?></div>
                        </div>

                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-bold">Código de Barras / SKU</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" name="sku" id="sku" class="form-control <?= $validation->hasError('sku') ? 'is-invalid' : '' ?>" value="<?= old('sku') ?>" placeholder="Escanea o escribe..." required>
                                <div class="invalid-feedback"><?= $validation->getError('sku') ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="stock" class="form-label fw-bold">Stock Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-boxes"></i></span>
                                <input type="number" name="stock" id="stock" class="form-control <?= $validation->hasError('stock') ? 'is-invalid' : '' ?>" value="<?= old('stock') ?? 0 ?>" min="0" required>
                                <div class="invalid-feedback"><?= $validation->getError('stock') ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="precio_venta" class="form-label fw-bold text-success">Precio Venta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">$</span>
                                <input type="number" step="0.01" name="precio_venta" id="precio_venta" class="form-control <?= $validation->hasError('precio_venta') ? 'is-invalid' : '' ?>" value="<?= old('precio_venta') ?>" placeholder="0.00" required>
                                <div class="invalid-feedback"><?= $validation->getError('precio_venta') ?></div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="text-warning mb-3">
                        <i class="bi bi-star-fill me-2"></i>Datos Avanzados (Zona VIP)
                    </h5>

                    <?php if ($is_vip): ?>
                        <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded mb-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="precio_costo" class="form-label fw-bold text-dark">Precio de Costo (Compra)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-warning text-dark border-warning">$</span>
                                        <input type="number" step="0.01" name="precio_costo" id="precio_costo" class="form-control border-warning <?= $validation->hasError('precio_costo') ? 'is-invalid' : '' ?>" value="<?= old('precio_costo') ?? 0 ?>" required>
                                    </div>
                                    <small class="text-muted">Calcula tu ganancia real.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="ubicacion_mapa" class="form-label fw-bold text-dark">Ubicación Física</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-warning text-dark border-warning"><i class="bi bi-geo-alt-fill"></i></span>
                                        <input type="text" name="ubicacion_mapa" id="ubicacion_mapa" class="form-control border-warning <?= $validation->hasError('ubicacion_mapa') ? 'is-invalid' : '' ?>" value="<?= old('ubicacion_mapa') ?>" placeholder="Ej: Pasillo 4, Estante B">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary d-flex align-items-center" role="alert">
                            <i class="bi bi-lock-fill fs-4 me-3"></i>
                            <div>
                                <strong>Función Bloqueada.</strong> 
                                <br>Gestionar el costo y la ubicación exacta es exclusivo para tiendas VIP. 
                                <a href="<?= site_url('tienda/vip/canjear') ?>" class="alert-link">¡Hazte VIP aquí!</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg shadow">
                            <i class="bi bi-save me-2"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>