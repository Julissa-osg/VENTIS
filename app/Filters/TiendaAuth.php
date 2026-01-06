<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TiendaAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // 1. Verificar si está logueado
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(site_url('login'))->with('error', 'Debes iniciar sesión para acceder a tu tienda.');
        }

        $rol = $session->get('rol');

        // 2. Verificar el rol (Solo Admin Tienda y Vendedor)
        if ($rol !== 'Admin Tienda' && $rol !== 'Vendedor') {
            if ($rol === 'Super Admin') {
                return redirect()->to(site_url('admin'))->with('error', 'Acceso denegado a esta sección de tienda.');
            }
            
            $session->destroy();
            return redirect()->to(site_url('login'))->with('error', 'Rol de usuario no autorizado. Sesión finalizada.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita lógica aquí.
    }
}
