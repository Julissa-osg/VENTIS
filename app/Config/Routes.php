<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----------------------------------------------------
// RUTA PRINCIPAL (PORTADA)
// ----------------------------------------------------
$routes->get('/', 'Home::index');


// ----------------------------------------------------
// AUTENTICACIÓN (PÚBLICO)
// ----------------------------------------------------
$routes->get('registro', 'Auth::registro');
$routes->post('registro/guardar', 'Auth::guardarRegistro');

$routes->get('login', 'Auth::login');
$routes->post('login/auth', 'Auth::autenticar');

$routes->get('logout', 'Auth::logout');


// ----------------------------------------------------
// PANEL DE LA TIENDA (PROTEGIDO)
// ----------------------------------------------------
$routes->group('tienda', ['filter' => 'tiendaAuth'], function ($routes) {

    // DASHBOARD
    $routes->get('/', 'Tienda::index');
    $routes->get('dashboard', 'Tienda::index');

    // PRODUCTOS
    $routes->get('productos', 'Producto::index');
    $routes->get('productos/crear', 'Producto::crear');
    $routes->post('productos/guardar', 'Producto::guardar');
    $routes->get('productos/editar/(:num)', 'Producto::editar/$1');
    $routes->post('productos/actualizar', 'Producto::actualizar');

    // VENTAS / TPV
    $routes->get('ventas', 'Venta::index');
    $routes->post('ventas/guardar', 'Venta::guardarVenta');
    $routes->get('ventas/boleta/(:num)', 'Venta::generarBoleta/$1');

    // API / AJAX
    $routes->post('api/productos/buscar', 'Venta::buscarProductoApi');
    $routes->post('ventas/crear_cliente_api', 'Venta::crearClienteApi');
    $routes->post('ventas/buscar_cliente_api', 'Venta::buscarClienteApi');

    // REPORTES
    $routes->get('reportes', 'Reporte::index');

    // ZONA VIP
    $routes->get('vip/canjear', 'ZonaVip::index');
    $routes->post('vip/procesar', 'ZonaVip::canjear');
});


// ----------------------------------------------------
// PANEL ADMINISTRADOR (PROTEGIDO)
// ----------------------------------------------------
$routes->group('admin', [
    'namespace' => 'App\Controllers\Admin',
    'filter'    => 'adminAuth'
], function ($routes) {

    // DASHBOARD
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    // TIENDAS
    $routes->get('tiendas', 'Admin::listarTiendas');
    $routes->get('tiendas/crear', 'Admin::crearTienda');
    $routes->post('tiendas/guardar', 'Admin::guardarTienda');
    $routes->get('tiendas/editar/(:num)', 'Admin::editarTienda/$1');
    $routes->post('tiendas/actualizar', 'Admin::actualizarTienda');
    $routes->post('tiendas/eliminar/(:num)', 'Admin::eliminarTienda/$1');

    // CÓDIGOS
    $routes->get('codigos', 'Admin::listarCodigos');
    $routes->get('codigos/generar', 'Admin::generarCodigos');
    $routes->post('codigos/crear', 'Admin::crearCodigos');

    // USUARIOS
    $routes->get('usuarios', 'Admin::listarUsuarios');
    $routes->get('usuarios/editar/(:num)', 'Admin::editarUsuario/$1');
    $routes->post('usuarios/actualizar', 'Admin::actualizarUsuario');
});


// ----------------------------------------------------
// PERFIL DE USUARIO (LOGUEADO)
// ----------------------------------------------------
$routes->group('perfil', function ($routes) {

    $routes->get('/', 'Usuario::miPerfil');
    $routes->post('actualizar', 'Usuario::actualizarDatos');
    $routes->post('password', 'Usuario::cambiarPassword');
    $routes->post('tienda', 'Usuario::actualizarTienda');
});

