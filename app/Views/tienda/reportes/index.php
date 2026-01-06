<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?> Reportes de Ventas <?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold"><i class="bi bi-file-earmark-bar-graph me-2"></i>Reportes de Ventas</h1>
</div>

<div class="card shadow mb-4 border-left-primary">
    <div class="card-body">
        <form action="<?= site_url('tienda/reportes') ?>" method="get" class="row align-items-end">
            <div class="col-md-4">
                <label class="fw-bold">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="<?= $fechaInicio ?>">
            </div>
            <div class="col-md-4">
                <label class="fw-bold">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control" value="<?= $fechaFin ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Filtrar Resultados
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 fw-bold text-primary">Detalle de Transacciones</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaReportes" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Venta</th>
                        <th>Fecha y Hora</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Método</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($ventas)): ?>
                        <tr><td colspan="7" class="text-center">No hay ventas en este rango de fechas.</td></tr>
                    <?php else: ?>
                        <?php foreach($ventas as $v): ?>
                        <tr>
                            <td>#<?= str_pad($v['id'], 6, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['created_at'])) ?></td>
                            <td>
                                <?php if($v['cliente_nombre']): ?>
                                    <?= esc($v['cliente_nombre']) ?> <br>
                                    <small class="text-muted"><?= esc($v['doc_cliente'] ?? '') ?></small>
                                <?php else: ?>
                                    Público General
                                <?php endif; ?>
                            </td>
                            <td><?= esc($v['vendedor']) ?></td>
                            <td><?= esc($v['metodo_pago']) ?></td>
                            <td class="fw-bold">S/ <?= number_format($v['total'], 2) ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('tienda/ventas/boleta/' . $v['id']) ?>" target="_blank" class="btn btn-sm btn-info text-white" title="Ver Boleta">
                                    <i class="bi bi-receipt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaReportes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json'
            },
            responsive: true,
            order: [[ 0, "desc" ]], // Ordenar por ID descendente
            
            // CONFIGURACIÓN DE LOS BOTONES DE EXPORTACIÓN
            dom: 'Bfrtip', // Define dónde aparecen los botones (B)
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    title: 'Reporte de Ventas - MiVenta'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    title: 'Reporte de Ventas',
                    orientation: 'landscape', // Horizontal para que quepan las columnas
                    pageSize: 'A4'
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="bi bi-filetype-csv"></i> CSV',
                    className: 'btn btn-primary btn-sm',
                    title: 'Reporte_Ventas'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Imprimir (HTML)',
                    className: 'btn btn-secondary btn-sm',
                    title: '<h1>Reporte de Ventas</h1>'
                }
            ]
        });
    });
</script>
<?= $this->endSection() ?>