<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Generar Códigos VIP
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-magic me-2"></i>Generador de Códigos VIP</h4>
                </div>
                <div class="card-body">
                    
                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php $validation = \Config\Services::validation(); ?>

                    <form action="<?= site_url('admin/codigos/crear') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="cantidad" class="form-label fw-bold">Cantidad de Códigos a Generar</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                    <input type="number" name="cantidad" id="cantidad" class="form-control form-control-lg 
                                           <?= $validation->hasError('cantidad') ? 'is-invalid' : '' ?>" 
                                           value="<?= old('cantidad', 1) ?>" min="1" max="100" required>
                                </div>
                                <small class="text-muted">Máximo 100 códigos por lote.</small>
                                <?php if ($validation->hasError('cantidad')): ?>
                                    <div class="text-danger mt-1"><?= $validation->getError('cantidad') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label fw-bold">Precio ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio" id="precio" class="form-control 
                                           <?= $validation->hasError('precio') ? 'is-invalid' : '' ?>" 
                                           value="<?= old('precio') ?>" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duracion_dias" class="form-label fw-bold">Duración (Días)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    <input type="number" name="duracion_dias" id="duracion_dias" class="form-control 
                                           <?= $validation->hasError('duracion_dias') ? 'is-invalid' : '' ?>" 
                                           value="<?= old('duracion_dias', 30) ?>" min="1" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-cogs me-2"></i> Generar Códigos Ahora
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>