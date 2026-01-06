<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Listado VIP
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">

<style>
    /* --- ESTILOS PERSONALIZADOS PARA DATATABLES --- */
    
    /* El input de buscar */
    .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
        outline: none;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .dataTables_filter input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    /* El selector de "Mostrar X registros" */
    .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
    }

    /* La paginación (Los botones de abajo) */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3rem 0.8rem;
        margin-left: 2px;
        border-radius: 5px;
        border: 1px solid transparent; 
    }

    /* Botón actual (Página 1, 2...) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #0d6efd !important; /* Azul Bootstrap */
        color: white !important;
        border: 1px solid #0d6efd;
        font-weight: bold;
    }

    /* Botones al pasar el mouse (Hover) */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important; /* Gris clarito */
        color: #0d6efd !important;
        border: 1px solid #dee2e6;
    }
    
    /* Espaciado general */
    .dataTables_wrapper {
        padding-top: 10px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold">Gestión de Códigos VIP</h2>
        <a href="<?= site_url('admin/codigos/generar') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Generar Nuevos
        </a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaAdminCodigos" class="table table-hover align-middle stripe">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Precio</th>
                            <th>Días</th>
                            <th>Estado</th>
                            <th>Tienda Uso</th>
                            <th>Fecha Creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($codigos as $codigo): ?>
                        <tr>
                            <td><?= $codigo['id'] ?></td>
                            <td>
                                <code class="fs-5 text-primary fw-bold"><?= $codigo['codigo'] ?></code>
                            </td>
                            <td>$<?= number_format($codigo['precio'], 2) ?></td>
                            <td><?= $codigo['duracion_dias'] ?></td>
                            <td>
                                <?php if($codigo['estado'] == 'Activo'): ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php elseif($codigo['estado'] == 'Usado'): ?>
                                    <span class="badge bg-secondary">Usado</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?= $codigo['estado'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $codigo['id_tienda_uso'] ? '<span class="fw-bold">Tienda #' . $codigo['id_tienda_uso'].'</span>' : '<span class="text-muted">-</span>' ?>
                            </td>
                            <td class="small text-muted"><?= date('d/m/Y', strtotime($codigo['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaAdminCodigos').DataTable({
            // Configuración de idioma (Español)
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Documentos",
                "infoEmpty": "Mostrando 0 to 0 of 0 Documentos",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            // Ordenar por defecto por la primera columna (ID) descendente
            order: [[0, 'desc']] 
        });
    });
</script>
<?= $this->endSection() ?>