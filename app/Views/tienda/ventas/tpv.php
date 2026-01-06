<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
Punto de Venta
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-7">
        
        <div class="card shadow-sm mb-3 border-start border-primary border-4">
            <div class="card-body py-3">
                <label class="fw-bold mb-1"><i class="bi bi-person-badge me-2"></i>Datos del Cliente</label>
                <div class="input-group">
                    <input type="text" id="dni_cliente" class="form-control" placeholder="DNI / Documento" style="max-width: 150px;">
                    <button class="btn btn-outline-primary" type="button" id="btnBuscarCliente">
                        <i class="bi bi-search"></i>
                    </button>
                    <input type="text" id="nombre_cliente_display" class="form-control bg-light fw-bold text-primary" value="Público General" readonly>
                    <input type="hidden" id="cliente_id_hidden" value="">
                    <input type="hidden" id="cliente_nombre_hidden" value="Público General">
                </div>
                <small class="text-muted" id="msg_cliente"></small>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <label class="fw-bold mb-1"><i class="bi bi-upc-scan me-2"></i>Buscar Producto (SKU)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-barcode"></i></span>
                    <input type="text" id="sku_producto" class="form-control form-control-lg" placeholder="Escanear código de barras...">
                    <button class="btn btn-primary" id="btnBuscarProducto">Añadir</button>
                </div>
                <div id="error-busqueda-producto" class="text-danger mt-2 fw-bold small" style="display:none;"></div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-cart3 me-2"></i>Carrito de Venta</h5>
                <span class="badge bg-danger cursor-pointer" onclick="vaciarCarrito()">Vaciar</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Producto</th>
                                <th width="100">Precio</th>
                                <th width="120">Cant.</th>
                                <th width="100" class="text-end">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-carrito">
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 text-muted">Total a Pagar:</h4>
                    <h2 class="mb-0 fw-bold text-success" id="total-venta">$0.00</h2>
                </div>
                <div class="d-grid">
                    <button class="btn btn-success btn-lg py-3 shadow-sm" id="btnProcesarVenta">
                        <i class="bi bi-check-circle-fill me-2"></i> COBRAR E IMPRIMIR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-grid-fill me-2"></i>Catálogo Rápido</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0 small" id="tabla-productos-rapida">
                        <thead class="bg-light">
                            <tr>
                                <th>Producto</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $p): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-truncate" style="max-width: 150px;" title="<?= esc($p['nombre']) ?>">
                                        <?= esc($p['nombre']) ?>
                                    </div>
                                    <small class="text-muted"><?= esc($p['codigo_barras']) ?></small>
                                </td>
                                <td>
                                    <?php if($p['stock_actual'] > 5): ?>
                                        <span class="badge bg-success rounded-pill"><?= $p['stock_actual'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill"><?= $p['stock_actual'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>$<?= number_format($p['precio_venta'], 2) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary btn-add-rapido"
                                        data-id="<?= $p['id'] ?>" 
                                        data-nombre="<?= esc($p['nombre']) ?>" 
                                        data-precio="<?= $p['precio_venta'] ?>" 
                                        data-stock="<?= $p['stock_actual'] ?>">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Registrar Nuevo Cliente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoCliente">
            <div class="mb-3">
                <label class="form-label">DNI / Documento *</label>
                <input type="text" class="form-control" id="modal_dni" name="modal_dni" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre Completo *</label>
                <input type="text" class="form-control" id="modal_nombre" name="modal_nombre" required>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="modal_telefono">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="modal_email">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="modal_direccion">
            </div>
        </form>
        <div id="error-modal-cliente" class="alert alert-danger d-none small"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarClienteModal">Guardar y Seleccionar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let carrito = [];
    const baseUrl = '<?= base_url() ?>';

    // 1. FUNCIÓN AÑADIR AL CARRITO
    function agregarAlCarrito(id, nombre, precio, stock, cantidad = 1) {
        id = parseInt(id);
        stock = parseInt(stock);
        precio = parseFloat(precio);
        
        // Verificar si ya existe
        let item = carrito.find(x => x.id === id);
        
        if (item) {
            if (item.cantidad + cantidad > stock) {
                alert("¡Stock insuficiente! Solo quedan " + stock + " unidades.");
                return;
            }
            item.cantidad += cantidad;
        } else {
            if (cantidad > stock) {
                alert("Stock insuficiente.");
                return;
            }
            carrito.push({ id, nombre, precio, stock, cantidad });
        }
        renderizarCarrito();
    }

    // 2. RENDERIZAR CARRITO
    function renderizarCarrito() {
        let html = '';
        let total = 0;
        
        carrito.forEach((item, index) => {
            let subtotal = item.precio * item.cantidad;
            total += subtotal;
            
            html += `
                <tr>
                    <td>${item.nombre}</td>
                    <td>$${item.precio.toFixed(2)}</td>
                    <td>
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <button class="btn btn-outline-secondary btn-restar" data-index="${index}">-</button>
                            <input type="text" class="form-control text-center" value="${item.cantidad}" readonly>
                            <button class="btn btn-outline-secondary btn-sumar" data-index="${index}">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-bold">$${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-sm text-danger btn-borrar" data-index="${index}"><i class="bi bi-trash"></i></button></td>
                </tr>
            `;
        });
        
        $('#cuerpo-carrito').html(html);
        $('#total-venta').text('$' + total.toFixed(2));
    }

    // 3. LISTENERS BOTONES CARRITO
    $(document).on('click', '.btn-sumar', function() {
        let index = $(this).data('index');
        let item = carrito[index];
        if(item.cantidad < item.stock) {
            item.cantidad++;
            renderizarCarrito();
        } else {
            alert("Tope de stock alcanzado");
        }
    });

    $(document).on('click', '.btn-restar', function() {
        let index = $(this).data('index');
        if(carrito[index].cantidad > 1) {
            carrito[index].cantidad--;
            renderizarCarrito();
        }
    });

    $(document).on('click', '.btn-borrar', function() {
        let index = $(this).data('index');
        carrito.splice(index, 1);
        renderizarCarrito();
    });

    function vaciarCarrito() {
        if(confirm('¿Borrar todo el carrito?')) {
            carrito = [];
            renderizarCarrito();
        }
    }

    // 4. BUSCAR PRODUCTO AJAX (Input principal)
    $('#btnBuscarProducto').click(function() {
        buscarProducto();
    });
    
    $('#sku_producto').keypress(function(e) {
        if(e.which == 13) buscarProducto(); // Enter
    });

    function buscarProducto() {
        let sku = $('#sku_producto').val().trim();
        if(!sku) return;

        $.post(baseUrl + 'tienda/api/productos/buscar', {sku: sku}, function(data) {
            if(data.status === 'success') {
                let p = data.producto;
                agregarAlCarrito(p.id, p.nombre, p.precio_venta, p.stock_actual);
                $('#sku_producto').val(''); // Limpiar input para siguiente escaneo
                $('#error-busqueda-producto').hide();
            } else {
                $('#error-busqueda-producto').text('Producto no encontrado').show();
                // Play error sound opcional
            }
        }, 'json');
    }

    // 5. AÑADIR DESDE LISTA RÁPIDA
    $('.btn-add-rapido').click(function() {
        let btn = $(this);
        agregarAlCarrito(btn.data('id'), btn.data('nombre'), btn.data('precio'), btn.data('stock'));
    });

    // 6. BUSCAR CLIENTE
    $('#btnBuscarCliente').click(function() {
        let dni = $('#dni_cliente').val().trim();
        if(!dni) return;

        $('#btnBuscarCliente').prop('disabled', true);
        $('#msg_cliente').html('Buscando...');

        $.ajax({
            url: baseUrl + 'tienda/ventas/buscar_cliente_api',
            method: 'POST',
            data: {dni: dni},
            dataType: 'json',
            success: function(data) {
                $('#btnBuscarCliente').prop('disabled', false);
                
                if(data.status === 'success') {
                    // ENCONTRADO
                    seleccionarCliente(data.cliente.id, data.cliente.nombre);
                    $('#msg_cliente').html('<span class="text-success fw-bold">Cliente encontrado.</span>');
                } else {
                    // NO ENCONTRADO -> OFRECER CREAR
                    $('#nombre_cliente_display').val('Público General');
                    $('#cliente_id_hidden').val('');
                    $('#cliente_nombre_hidden').val('Público General');
                    
                    // Mostramos botón para abrir modal
                    let btnCrear = `<button class="btn btn-sm btn-warning ms-2" onclick="abrirModalCliente('${dni}')">
                                        <i class="bi bi-person-plus-fill"></i> Crear Nuevo
                                    </button>`;
                    $('#msg_cliente').html('<span class="text-danger">No encontrado.</span> ' + btnCrear);
                }
            },
            error: function() {
                 $('#btnBuscarCliente').prop('disabled', false);
                 $('#msg_cliente').text("Error de conexión");
            }
        });
    });

    // B. FUNCIONES AUXILIARES NUEVAS
    function seleccionarCliente(id, nombre) {
        $('#nombre_cliente_display').val(nombre);
        $('#cliente_id_hidden').val(id);
        $('#cliente_nombre_hidden').val(nombre);
        // Limpiamos mensajes
        $('#msg_cliente').html('<span class="text-success">Cliente seleccionado correctamente.</span>');
    }

    // Función global para que el botón HTML la encuentre
    window.abrirModalCliente = function(dniSugerido) {
        $('#modal_dni').val(dniSugerido);
        $('#modal_nombre').val('');
        $('#error-modal-cliente').addClass('d-none');
        var myModal = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
        myModal.show();
    };

    // C. GUARDAR DESDE EL MODAL
    $('#btnGuardarClienteModal').click(function() {
        let formData = $('#formNuevoCliente').serialize();
        let btn = $(this);
        btn.prop('disabled', true).text('Guardando...');

        $.post(baseUrl + 'tienda/ventas/crear_cliente_api', formData, function(data) {
            btn.prop('disabled', false).text('Guardar y Seleccionar');
            
            if(data.status === 'success') {
                // Cerrar modal
                let modalEl = document.getElementById('modalNuevoCliente');
                let modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                // Auto-seleccionar al nuevo cliente
                seleccionarCliente(data.cliente.id, data.cliente.nombre);
                $('#dni_cliente').val(data.cliente.documento); // Poner el DNI en el buscador visualmente
            } else {
                $('#error-modal-cliente').text(data.message).removeClass('d-none');
            }
        }, 'json').fail(function() {
            btn.prop('disabled', false).text('Guardar y Seleccionar');
            alert("Error de servidor al guardar cliente");
        });
    });

    // 7. PROCESAR VENTA FINAL
    $('#btnProcesarVenta').click(function() {
        if(carrito.length === 0) {
            alert("El carrito está vacío");
            return;
        }

        let total = parseFloat($('#total-venta').text().replace('$',''));
        let clienteId = $('#cliente_id_hidden').val();
        let clienteNombre = $('#cliente_nombre_hidden').val();

        let datosVenta = {
            items: carrito,
            total: total,
            cliente_id: clienteId,
            cliente_nombre: clienteNombre
        };

        let btn = $(this);
        btn.prop('disabled', true).text('Procesando...');

        $.ajax({
            url: baseUrl + 'tienda/ventas/guardar',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosVenta),
            success: function(data) {
                if(data.status === 'success') {
                    // Abrir boleta
                    window.open(baseUrl + 'tienda/ventas/boleta/' + data.venta_id, '_blank', 'width=400,height=600');
                    // Reset
                    carrito = [];
                    renderizarCarrito();
                    $('#nombre_cliente_display').val('Público General');
                    $('#dni_cliente').val('');
                    btn.prop('disabled', false).html('<i class="bi bi-check-circle-fill me-2"></i> COBRAR E IMPRIMIR');
                } else {
                    alert("Error: " + data.message);
                    btn.prop('disabled', false).text('Reintentar');
                }
            },
            error: function(err) {
                alert("Error de servidor");
                btn.prop('disabled', false).text('Reintentar');
            }
        });
    });

</script>
<?= $this->endSection() ?>