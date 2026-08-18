<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * Maneja las peticiones entrantes verificando que el usuario haya iniciado sesión como Administrador.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('admin_logged_in') || !session('admin_logged_in')) {
            // Almacenar la URL original a la que el usuario intentó acceder
            session()->put('url.intended', $request->fullUrl());

            return redirect()->route('web.login')
                ->with('warning', 'Debes iniciar sesión para acceder al Panel de Administración.');
        }

        return $next($request);
    }
}
