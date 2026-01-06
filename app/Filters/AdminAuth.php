<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si no está logueado, redirigir al login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'))->with('error', 'Debes iniciar sesión para acceder al Panel Administrativo.');
        }

        // Si está logueado, pero NO es Super Admin, redirigir al dashboard de su rol (tienda)
        if (session()->get('rol') !== 'Super Admin') {
            return redirect()->to(site_url('tienda'))->with('error', 'Acceso denegado. No tienes permisos de Super Administrador.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada después de la ejecución
    }
}
