<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?> Mi Perfil y Negocio <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-xl-4">
        <div class="card shadow mb-4 text-center border-0">
            <div class="card-body pb-5">
                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center mx-auto mb-4" 
                     style="width: 120px; height: 120px; font-size: 3rem; border: 4px solid var(--color-acento-vibrante);">
                    <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                </div>
                
                <h3 class="fw-bold text-gray-800"><?= esc($usuario['nombre']) ?></h3>
                <span class="badge bg-warning text-dark px-3 py-2 mb-3"><?= esc($usuario['rol']) ?></span>

                <div class="text-start mt-4 px-4">
                    <p class="text-muted mb-2"><i class="bi bi-envelope-fill me-2"></i> <?= esc($usuario['email']) ?></p>
                    <p class="text-muted mb-2"><i class="bi bi-calendar-check me-2"></i> Miembro desde: <br> <?= date('d/m/Y', strtotime($usuario['created_at'])) ?></p>
                    
                    <?php if($tienda): ?>
                        <div class="alert alert-light border mt-3 text-center">
                            <small class="text-uppercase text-muted fw-bold">Tienda Actual</small><br>
                            <h5 class="fw-bold mt-1 text-primary"><?= esc($tienda['nombre']) ?></h5>
                            <?php if(!empty($tienda['ruc'])): ?>
                                <span class="badge bg-light text-dark border">RUC: <?= esc($tienda['ruc']) ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">Sin RUC Registrado</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        
        <?php if(session()->get('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->get('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->get('error')): ?>
            <div class="alert alert-danger"><?= session()->get('error') ?></div>
        <?php endif; ?>

        <div class="card shadow border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <ul class="nav nav-tabs card-header-tabs" id="perfilTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold text-dark" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button">
                            <i class="bi bi-person-lines-fill me-2"></i> Mis Datos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-dark" id="seguridad-tab" data-bs-toggle="tab" data-bs-target="#seguridad" type="button">
                            <i class="bi bi-shield-lock-fill me-2"></i> Seguridad
                        </button>
                    </li>
                    
                    <?php if(!empty($tienda) && ($usuario['rol'] == 'Admin Tienda' || $usuario['rol'] == 'Super Admin')): ?>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-primary" id="tienda-tab" data-bs-toggle="tab" data-bs-target="#tienda" type="button">
                            <i class="bi bi-shop-window me-2"></i> Mi Negocio
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content" id="perfilTabsContent">
                    
                    <div class="tab-pane fade show active" id="datos" role="tabpanel">
                        <form action="<?= site_url('perfil/actualizar') ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control form-control-lg" value="<?= esc($usuario['nombre']) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted">Correo Electrónico (Login)</label>
                                <input type="email" name="email" class="form-control form-control-lg" value="<?= esc($usuario['email']) ?>" required>
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-muted">Nombre hobby</label>
                                <input type="text" name="hobby" class="form-control form-control-lg" value="<?= esc($usuario['hobby']) ?>" required>
                            </div>


                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-save me-2"></i> Guardar Cambios Personales
                            </button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="seguridad" role="tabpanel">
                        <?php if(session()->get('success_pass')): ?>
                            <div class="alert alert-success"><i class="bi bi-check-all"></i> <?= session()->get('success_pass') ?></div>
                        <?php endif; ?>
                        
                        <?php if(session()->get('error_pass')): ?>
                            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?= session()->get('error_pass') ?></div>
                        <?php endif; ?>

                        <form action="<?= site_url('perfil/password') ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contraseña Actual</label>
                                <input type="password" name="pass_actual" class="form-control" required placeholder="Ingresa tu clave actual para verificar">
                            </div>
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="password" name="pass_nueva" class="form-control" required placeholder="Mínimo 6 caracteres">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" name="pass_confirm" class="form-control" required placeholder="Repite la nueva clave">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger px-4 mt-2">
                                <i class="bi bi-key-fill me-2"></i> Actualizar Contraseña
                            </button>
                        </form>
                    </div>

                    <?php if(!empty($tienda) && ($usuario['rol'] == 'Admin Tienda' || $usuario['rol'] == 'Super Admin')): ?>
                    <div class="tab-pane fade" id="tienda" role="tabpanel">
                        
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                            <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
                            <div>
                                Estos datos aparecerán en las <strong>Boletas de Venta</strong> y reportes. 
                                Asegúrate de que el RUC sea correcto.
                            </div>
                        </div>

                        <form action="<?= site_url('perfil/tienda') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold">Nombre Comercial de la Tienda</label>
                                    <input type="text" name="nombre_tienda" class="form-control" value="<?= esc($tienda['nombre']) ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold text-primary">RUC (11 Dígitos)</label>
                                    <input type="text" name="ruc" class="form-control border-primary" value="<?= esc($tienda['ruc']) ?>" 
                                           placeholder="Ej: 20600123456" maxlength="11" pattern="\d{11}" title="Debe contener 11 dígitos numéricos">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Nombre del Propietario / Razón Social</label>
                                <input type="text" name="propietario" class="form-control" value="<?= esc($tienda['propietario']) ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary px-4 mt-2">
                                <i class="bi bi-shop me-2"></i> Actualizar Datos del Negocio
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>