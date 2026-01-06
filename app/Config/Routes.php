<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// RUTAS DE AUTENTICACIÓN (Front-Office)
$routes->get('registro', 'Auth::registro'); // Muestra el formulario
$routes->post('registro/guardar', 'Auth::guardarRegistro'); // Procesa el formulario (POST)

// RUTAS DE LOGIN Y LOGOUT
$routes->get('login', 'Auth::login'); // Muestra el formulario de login
$routes->post('login/auth', 'Auth::autenticar'); // Procesa el login (POST)
$routes->get('logout', 'Auth::logout'); // Cerrar sesión


// GRUPO DE RUTAS PROTEGIDAS PARA EL PANEL DE LA TIENDA
$routes->group('tienda', ['filter' => 'tiendaAuth'], function($routes) {
    // 1. Dashboard de la tienda
    $routes->get('/', 'Tienda::index');
    $routes->get('dashboard', 'Tienda::index');

    // 2. GESTIÓN DE PRODUCTOS
    $routes->get('productos', 'Producto::index');
    $routes->get('productos/crear', 'Producto::crear');
    $routes->post('productos/guardar', 'Producto::guardar');
    $routes->get('productos/editar/(:num)', 'Producto::editar/$1');
    $routes->post('productos/actualizar', 'Producto::actualizar');

    // Rutas del Módulo de Ventas (TPV)
    $routes->get('ventas', 'Venta::index'); // Interfaz principal del TPV

    // Ruta para AJAX: buscar producto por SKU
    $routes->post('api/productos/buscar', 'Venta::buscarProductoApi');

    // Guardar nuevo cliente vía AJAX
    $routes->post('ventas/crear_cliente_api', 'Venta::crearClienteApi');
    //Guardar la venta y descontar stock (POST)
    $routes->post('ventas/guardar', 'Venta::guardarVenta');
    
    //Ruta de la Boleta (GET con parámetro numérico)
    $routes->get('ventas/boleta/(:num)', 'Venta::generarBoleta/$1'); 

    $routes->post('ventas/buscar_cliente_api', 'Venta::buscarClienteApi');

    // REPORTES
    $routes->get('reportes', 'Reporte::index');

    // ZONA VIP
    $routes->get('vip/canjear', 'ZonaVip::index');
    $routes->post('vip/procesar', 'ZonaVip::canjear');

});




// En app/Config/Routes.php (fuera del grupo tienda, esto está bien para CodeIgniter 4)
$routes->get('tienda/ventas/boleta/(:num)', 'Venta::generarBoleta/$1');

// GRUPO DE RUTAS PROTEGIDAS PARA EL PANEL DE ADMINISTRADOR
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminAuth'], function($routes) {

$routes->get('/', 'Dashboard::index'); // Accede a /admin/
$routes->get('dashboard', 'Dashboard::index'); // Accede a /admin/dashboard


$routes->get('tiendas', 'Admin::listarTiendas');
$routes->get('tiendas/crear', 'Admin::crearTienda');
$routes->post('tiendas/guardar', 'Admin::guardarTienda');

// Muestra el formulario de edición con el ID de la tienda (GET)
// (:num) es un comodín que espera un número entero (el ID de la tienda)
$routes->get('tiendas/editar/(:num)', 'Admin::editarTienda/$1');

// Procesa el formulario de actualización (POST)
$routes->post('tiendas/actualizar', 'Admin::actualizarTienda');

// Procesa la solicitud de suspensión/eliminación (POST)
$routes->post('tiendas/eliminar/(:num)', 'Admin::eliminarTienda/$1');

   
// NOTA: Si usas 'Admin::' para tiendas, el controlador debe estar en App/Controllers/Admin/Admin.php
$routes->get('codigos', 'Admin::listarCodigos');
$routes->get('codigos/generar', 'Admin::generarCodigos');
$routes->post('codigos/crear', 'Admin::crearCodigos');

// NUEVO: MÓDULO DE GESTIÓN DE USUARIOS
    $routes->get('usuarios', 'Admin::listarUsuarios');
    $routes->get('usuarios/editar/(:num)', 'Admin::editarUsuario/$1');
    $routes->post('usuarios/actualizar', 'Admin::actualizarUsuario');

});

// RUTAS DE PERFIL (Accesible para cualquier usuario logueado)
$routes->group('perfil', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Usuario::miPerfil'); // Ver el perfil
    $routes->post('actualizar', 'Usuario::actualizarDatos'); // Guardar nombre/email
    $routes->post('password', 'Usuario::cambiarPassword'); // Cambio estricto de contraseña
    $routes->post('tienda', 'Usuario::actualizarTienda');
});
