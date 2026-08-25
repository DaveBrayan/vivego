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
     * Muestra el formulario de inicio de sesión de administradores.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (session('admin_logged_in') && session('admin_id')) {
            $admin = Administrator::find(session('admin_id'));
            if ($admin && $admin->status === 'Activo') {
                return redirect()->route('web.dashboard');
            }
            session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role', 'admin_avatar']);
        }

        $settings = Setting::current();
        return view('web.login', compact('settings'));
    }

    /**
     * Procesa el intento de inicio de sesión de administradores con validación criptográfica estricta.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim(strtolower($credentials['login']));
        $password = $credentials['password'];

        // Buscar administrador en la base de datos por correo electrónico o nombre de usuario
        $admin = Administrator::where(function ($query) use ($loginInput) {
            $query->where('email', $loginInput)
                  ->orWhere('username', $loginInput);
        })->first();

        // Validar que el administrador exista, esté Activo y la contraseña coincida con el hash
        if ($admin && $admin->status === 'Activo' && Hash::check($password, $admin->password)) {
            $request->session()->regenerate();

            $isTempPassword = str_starts_with($password, 'VG');

            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->id,
                'admin_name' => $admin->full_name,
                'admin_email' => $admin->email,
                'admin_role' => $admin->role,
                'admin_avatar' => $admin->avatar,
                'must_change_password' => $isTempPassword,
            ]);

            $targetUrl = session()->pull('url.intended', route('web.dashboard'));

            // Asegurarse de que el targetUrl sea una ruta interna segura
            if (!str_contains($targetUrl, '/dashboard') && !str_contains($targetUrl, '/admin')) {
                $targetUrl = route('web.dashboard');
            }

            return redirect($targetUrl)
                ->with('success', "¡Bienvenido de nuevo, {$admin->full_name}! Has ingresado al Panel de Control.");
        }

        if ($admin && $admin->status !== 'Activo') {
            return redirect()->back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Tu cuenta de administrador se encuentra inactiva o suspendida.']);
        }

        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'Credenciales incorrectas. Verifica tu usuario/correo y contraseña.']);
    }

    /**
     * Permite a un administrador cambiar su contraseña activa.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $adminId = session('admin_id');
        $admin = $adminId ? Administrator::find($adminId) : null;

        if (!$admin) {
            return redirect()->route('web.login')
                ->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual ingresada es incorrecta.']);
        }

        $admin->password = Hash::make($validated['new_password']);
        $admin->save();

        session()->forget('must_change_password');

        return redirect()->back()->with('success', '🔑 ¡Tu contraseña ha sido actualizada con éxito!');
    }

    /**
     * Cierra la sesión activa del administrador de forma segura.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'admin_logged_in',
            'admin_id',
            'admin_name',
            'admin_email',
            'admin_role',
            'admin_avatar',
            'must_change_password',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.login')
            ->with('success', 'Has cerrado sesión del Panel de Administración correctamente.');
    }
}

