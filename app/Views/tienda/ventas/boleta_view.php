<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?= $venta['id'] ?></title>
    <style>
        /* Ajustes para impresora térmica (58mm o 80mm) */
        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; padding: 0; }
        }
        
        body {
            font-family: 'Courier New', Courier, monospace; /* Fuente tipo máquina */
            font-size: 12px;
            margin: 0 auto;
            padding: 10px;
            width: 280px; /* Ancho estándar de ticket */
            background-color: #fff;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .linea {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
            width: 100%;
        }
        
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 2px 0; vertical-align: top; }
        
        .producto {
            display: block;
            font-weight: bold;
            font-size: 11px;
        }
        
        .info-tienda { font-size: 14px; font-weight: bold; text-transform: uppercase; }
        .info-legal { font-size: 10px; color: #333; }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center">
        <div class="info-tienda"><?= esc($tienda['nombre']) ?></div>
        <div><?= esc($tienda['propietario']) ?></div>
        <?php if(!empty($tienda['ruc'])): ?>
            <div>RUC: <?= esc($tienda['ruc']) ?></div>
        <?php endif; ?>
        <div class="linea"></div>
        
        <div style="text-align: left;">
            Ticket: #<?= str_pad($venta['id'], 6, '0', STR_PAD_LEFT) ?><br>
            Fecha: <?= date('d/m/Y H:i', strtotime($venta['created_at'])) ?><br>
            Cajero: <?= esc($vendedor['nombre'] ?? 'Sistema') ?><br>
            Cliente: <?= esc($venta['cliente_nombre']) ?>
        </div>
    </div>

    <div class="linea"></div>

    <table>
        <thead>
            <tr>
                <th width="10%">Cant.</th>
                <th width="60%" style="text-align: left;">Descripción</th>
                <th class="text-right" width="30%">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $d): ?>
            <?php 
                // LÓGICA DE NOMBRE INTELIGENTE
                // 1. Intentar usar el nombre guardado en el historial (nueva columna)
                $nombreMostrar = $d['nombre_producto'];
                
                // 2. Si está vacío (ventas viejas), usar el nombre del catálogo actual
                if (empty($nombreMostrar)) {
                    $nombreMostrar = $d['nombre_catalogo'];
                }

                // 3. Si sigue vacío (producto borrado), mostrar genérico
                if (empty($nombreMostrar)) {
                    $nombreMostrar = "Producto Eliminado (ID: " . $d['id_producto'] . ")";
                }
            ?>
            <tr>
                <td class="text-center" style="vertical-align: top;"><?= $d['cantidad'] ?></td>
                <td>
                    <span class="producto">
                        <?= esc($nombreMostrar) ?>
                    </span>
                    <small style="color: #555;">x $<?= number_format($d['precio_unitario_venta'], 2) ?></small>
                </td>
                <td class="text-right" style="vertical-align: bottom;">
                    $<?= number_format($d['cantidad'] * $d['precio_unitario_venta'], 2) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="linea"></div>

    <div class="text-right">
        <span style="font-size: 16px; font-weight: bold;">TOTAL: $<?= number_format($venta['total'], 2) ?></span>
    </div>
    
    <div class="linea"></div>
    
    <div class="text-center info-legal">
        <br>
        ¡Gracias por su compra!<br>
        Conservar este ticket para reclamos.
    </div>

    <script>
        // Opcional: Cerrar ventana automáticamente después de imprimir
        // setTimeout(function(){ window.close(); }, 1000);
    </script>
</body>
</html>