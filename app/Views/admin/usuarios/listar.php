<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?> Gestión de Usuarios <?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row mb-4 align-items-center">
    <div class="col">
        <h1 class="h3 text-gray-800 fw-bold">Usuarios del Sistema</h1>
        <p class="text-muted">Administra el acceso y roles del personal.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tablaUsuarios" class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Tienda Asignada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td class="fw-bold"><?= esc($u['nombre']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= esc($u['rol']) ?></span></td>
                        <td><?= esc($u['nombre_tienda'] ?? 'Sin Tienda') ?></td>
                        <td>
                            <?php if($u['estado'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= site_url('admin/usuarios/editar/'.$u['id']) ?>" class="btn btn-sm btn-main-action">
                                <i class="bi bi-eye-fill"></i> Ver Más / Editar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#tablaUsuarios').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json' }
        });
    });
</script>
<?= $this->endSection() ?>