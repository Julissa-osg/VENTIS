<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?> Zona VIP <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        
        <?php if($tienda['estado_vip']): ?>
            <div class="card border-warning shadow text-center">
                <div class="card-body py-5">
                    <i class="bi bi-star-fill text-warning display-1"></i>
                    <h2 class="mt-3 fw-bold text-dark">¡Eres Miembro VIP!</h2>
                    <p class="lead">Disfrutas de todas las funciones avanzadas.</p>
                    <hr>
                    <p class="text-muted">Tu membresía expira el: <strong><?= date('d/m/Y', strtotime($tienda['fecha_expiracion_vip'])) ?></strong></p>
                </div>
            </div>

        <?php else: ?>
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="m-0"><i class="bi bi-gem me-2"></i>Activar Membresía VIP</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-center text-muted mb-4">Ingresa el código que te proporcionó el administrador para desbloquear funciones exclusivas (Costos, Ganancias, Ubicaciones).</p>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= site_url('tienda/vip/procesar') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Código de Activación</label>
                            <input type="text" name="codigo" class="form-control form-control-lg text-center text-uppercase" placeholder="XXXX-XXXX-XXXX" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark">
                                <i class="bi bi-unlock-fill me-2"></i> Canjear Código
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>