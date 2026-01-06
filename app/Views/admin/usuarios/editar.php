<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?> Detalle de Usuario <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800 fw-bold">Detalles y Edición</h1>
    <a href="<?= site_url('admin/usuarios') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Información del Perfil</h6>
            </div>
            <div class="card-body">
                <form action="<?= site_url('admin/usuarios/actualizar') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?= esc($usuario['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($usuario['email']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol de Usuario</label>
                            <select name="rol" class="form-select">
                                <option value="Super Admin" <?= $usuario['rol'] == 'Super Admin' ? 'selected' : '' ?>>Super Admin</option>
                                <option value="Admin Tienda" <?= $usuario['rol'] == 'Admin Tienda' ? 'selected' : '' ?>>Admin Tienda</option>
                                <option value="Vendedor" <?= $usuario['rol'] == 'Vendedor' ? 'selected' : '' ?>>Vendedor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tienda Asignada</label>
                            <select name="id_tienda" class="form-select">
                                <option value="0">-- Ninguna / Global --</option>
                                <?php foreach($tiendas as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= $usuario['id_tienda'] == $t['id'] ? 'selected' : '' ?>>
                                        <?= esc($t['nombre']) ?> (ID: <?= $t['id'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado de la Cuenta</label>
                        <select name="estado" class="form-select">
                            <option value="1" <?= $usuario['estado'] == 1 ? 'selected' : '' ?>>Activo (Permitir Acceso)</option>
                            <option value="0" <?= $usuario['estado'] == 0 ? 'selected' : '' ?>>Bloqueado / Inactivo</option>
                        </select>
                    </div>

                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Cambiar Contraseña</label>
                        <input type="password" name="password" class="form-control border-danger" placeholder="Dejar en blanco para MANTENER la contraseña actual">
                        <small class="text-muted">Solo escribe aquí si deseas restablecer la clave del usuario.</small>
                    </div>

                    <button type="submit" class="btn btn-main-action w-100 mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-secondary text-white">
                <h6 class="m-0 fw-bold">Metadatos</h6>
            </div>
            <div class="card-body">
                <p><strong>ID Usuario:</strong> #<?= $usuario['id'] ?></p>
                <p><strong>Fecha Registro:</strong> <br> <?= $usuario['created_at'] ?? 'No registrada' ?></p>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Al cambiar el Rol a "Super Admin", asegúrate de quitarle la asignación de Tienda (poner en Global).
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>