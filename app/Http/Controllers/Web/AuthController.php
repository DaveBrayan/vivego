<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm(): View
    {
        $settings = Setting::current();
        return view('web.login', compact('settings'));
    }

    /**
     * Procesa el intento de inicio de sesión de administradores.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim(strtolower($credentials['login']));
        $password = $credentials['password'];

        // Buscar administrador por correo o por nombre de usuario
        $admin = Administrator::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        // Si existe en BD y la contraseña es válida (o fallback de desarrollo)
        if ($admin && (Hash::check($password, $admin->password) || $password === 'admin123' || $password === '12345678')) {
            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->id,
                'admin_name' => $admin->full_name,
                'admin_email' => $admin->email,
                'admin_role' => $admin->role,
                'admin_avatar' => $admin->avatar,
            ]);

            return redirect()->route('web.dashboard')
                ->with('success', "¡Bienvenido de nuevo, {$admin->full_name}! Has iniciado sesión correctamente.");
        }

        // Si no hay administradores en BD o credencial por defecto
        if (!$admin && ($loginInput === 'admin@vivego.pe' || $loginInput === 'admin') && ($password === 'admin123' || $password === '12345678')) {
            session([
                'admin_logged_in' => true,
                'admin_id' => 1,
                'admin_name' => 'Christian Gómez',
                'admin_email' => 'admin@vivego.pe',
                'admin_role' => 'Administrador Principal',
            ]);

            return redirect()->route('web.dashboard')
                ->with('success', '¡Bienvenido de nuevo al Panel de Administración Vive Go!');
        }

        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'Las credenciales ingresadas son incorrectas. Verifica tu usuario y contraseña.']);
    }

    /**
     * Cierra la sesión activa del administrador.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role', 'admin_avatar']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.login')
            ->with('success', 'Has cerrado sesión correctamente. ¡Hasta pronto!');
    }
}
