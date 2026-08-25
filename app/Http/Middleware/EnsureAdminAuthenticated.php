<?php

namespace App\Http\Middleware;

use App\Models\Administrator;
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
        // 1. Si el usuario actual tiene sesión activa como Cliente y NO es Administrador
        if (session('customer_logged_in') && !session('admin_logged_in')) {
            return redirect()->route('web.customer.tickets')
                ->with('error', '⛔ Acceso denegado: Tu cuenta de cliente no tiene permisos para acceder al Panel de Administración.');
        }

        // 2. Si no hay sesión de Administrador válida
        if (!session()->has('admin_logged_in') || !session('admin_logged_in') || !session()->has('admin_id')) {
            session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role', 'admin_avatar']);
            session()->put('url.intended', $request->fullUrl());

            return redirect()->route('web.login')
                ->with('warning', 'Debes iniciar sesión con una cuenta de Administrador para acceder al Panel.');
        }

        // 3. Verificar en tiempo real contra la base de datos que el administrador exista y esté Activo
        $admin = Administrator::find(session('admin_id'));

        if (!$admin || $admin->status !== 'Activo') {
            session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role', 'admin_avatar']);

            return redirect()->route('web.login')
                ->with('error', 'Tu cuenta de administrador no existe o se encuentra inactiva. Contacta al soporte.');
        }

        return $next($request);
    }
}

