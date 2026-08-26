<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión unificado para Administradores y Clientes.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // 1. Si ya tiene sesión activa como Administrador
        if (session('admin_logged_in') && session('admin_id')) {
            $admin = Administrator::find(session('admin_id'));
            if ($admin && $admin->status === 'Activo') {
                return redirect()->route('web.dashboard');
            }
            session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role', 'admin_avatar']);
        }

        // 2. Si ya tiene sesión activa como Cliente
        if (session('customer_logged_in') && session('customer_id')) {
            $user = User::find(session('customer_id'));
            if ($user && ($user->status === 'Activo' || is_null($user->status))) {
                return redirect()->route('web.customer.tickets');
            }
            session()->forget(['customer_logged_in', 'customer_id', 'customer_name', 'customer_email', 'customer_dni', 'customer_phone']);
        }

        $settings = Setting::current();
        return view('web.login', compact('settings'));
    }

    /**
     * Procesa el inicio de sesión unificado con detección automática de rol (Administrador o Cliente).
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($credentials['login']);
        $loginLower = strtolower($loginInput);
        $password = $credentials['password'];

        // =========================================================================
        // 1. INTENTAR AUTENTICAR COMO ADMINISTRADOR
        // =========================================================================
        $admin = Administrator::where(function ($query) use ($loginInput, $loginLower) {
            $query->where('email', $loginLower)
                  ->orWhere('username', $loginInput);
        })->first();

        if ($admin && Hash::check($password, $admin->password)) {
            if ($admin->status !== 'Activo') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tu cuenta de administrador se encuentra inactiva o suspendida.',
                    ], 422);
                }
                return redirect()->back()
                    ->withInput($request->only('login'))
                    ->withErrors(['login' => 'Tu cuenta de administrador se encuentra inactiva o suspendida.']);
            }

            $request->session()->regenerate();

            // Limpiar residuos de sesión de cliente
            session()->forget([
                'customer_logged_in',
                'customer_id',
                'customer_name',
                'customer_email',
                'customer_dni',
                'customer_phone',
            ]);

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

            // Asegurarse de que el targetUrl sea una ruta interna segura de administración
            if (!str_contains($targetUrl, '/dashboard') && !str_contains($targetUrl, '/admin')) {
                $targetUrl = route('web.dashboard');
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'role' => 'admin',
                    'message' => "¡Bienvenido de nuevo, {$admin->full_name}!",
                    'redirect_url' => $targetUrl,
                ]);
            }

            return redirect($targetUrl)
                ->with('success', "¡Bienvenido de nuevo, {$admin->full_name}! Has ingresado al Panel de Control.");
        }

        // =========================================================================
        // 2. INTENTAR AUTENTICAR COMO CLIENTE / USUARIO
        // =========================================================================
        $user = User::where(function ($query) use ($loginInput, $loginLower) {
            $query->where('email', $loginLower)
                  ->orWhere('dni', $loginInput);
        })->first();

        if ($user && Hash::check($password, $user->password)) {
            if ($user->status && $user->status !== 'Activo') {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tu cuenta de cliente se encuentra inactiva o bloqueada.',
                    ], 422);
                }
                return redirect()->back()
                    ->withInput($request->only('login'))
                    ->withErrors(['login' => 'Tu cuenta de cliente se encuentra inactiva o bloqueada.']);
            }

            $request->session()->regenerate();

            // Limpiar residuos de sesión administrativa
            session()->forget([
                'admin_logged_in',
                'admin_id',
                'admin_name',
                'admin_email',
                'admin_role',
                'admin_avatar',
                'must_change_password',
            ]);

            session([
                'customer_logged_in' => true,
                'customer_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_dni' => $user->dni,
                'customer_phone' => $user->phone,
            ]);

            $targetUrl = session()->pull('url.intended', route('web.customer.tickets'));

            // Si la URL intentada era de administración, redirigir al portal de boletos de cliente
            if (str_contains($targetUrl, '/dashboard') || str_contains($targetUrl, '/admin')) {
                $targetUrl = route('web.customer.tickets');
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'role' => 'customer',
                    'message' => "¡Bienvenido, {$user->name}!",
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'dni' => $user->dni,
                        'phone' => $user->phone,
                    ],
                    'redirect_url' => $targetUrl,
                ]);
            }

            return redirect($targetUrl)
                ->with('success', "¡Bienvenido, {$user->name}! Has ingresado a tu portal de boletos.");
        }

        // =========================================================================
        // 3. CREDENCIALES INCORRECTAS
        // =========================================================================
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas. Verifica tu usuario/correo/DNI y contraseña.',
            ], 422);
        }

        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'Credenciales incorrectas. Verifica tu usuario/correo/DNI y contraseña.']);
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
     * Cierra la sesión activa de forma segura.
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
            'customer_logged_in',
            'customer_id',
            'customer_name',
            'customer_email',
            'customer_dni',
            'customer_phone',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}


