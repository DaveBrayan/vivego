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
                if (!session('must_change_password')) {
                    return redirect()->route('web.dashboard');
                }
            } else {
                session()->forget(['admin_logged_in', 'admin_id', 'admin_name', 'admin_email', 'admin_role', 'admin_avatar', 'must_change_password']);
            }
        }

        // 2. Si ya tiene sesión activa como Cliente
        if (session('customer_logged_in') && session('customer_id')) {
            $user = User::find(session('customer_id'));
            $statusLower = strtolower(trim((string) ($user?->status ?? 'active')));
            $isInactive = in_array($statusLower, ['inactivo', 'inactive', 'bloqueado', 'blocked', 'suspendido', 'suspended', '0']);
            if ($user && !$isInactive) {
                if (!session('must_change_password')) {
                    return redirect()->route('web.customer.tickets');
                }
            } else {
                session()->forget(['customer_logged_in', 'customer_id', 'customer_name', 'customer_email', 'customer_dni', 'customer_phone', 'must_change_password']);
            }
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
        $password = trim($credentials['password']);
        $passwordUpper = strtoupper($password);

        // =========================================================================
        // 1. INTENTAR AUTENTICAR COMO ADMINISTRADOR
        // =========================================================================
        $admin = Administrator::where(function ($query) use ($loginInput, $loginLower) {
            $query->where('email', $loginLower)
                  ->orWhere('username', $loginInput);
        })->first();

        $adminPassMatches = $admin && (Hash::check($password, $admin->password) || (str_starts_with($passwordUpper, 'VG') && Hash::check($passwordUpper, $admin->password)));

        if ($adminPassMatches) {
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

            $isTempPassword = str_starts_with($passwordUpper, 'VG');

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
                    'must_change_password' => $isTempPassword,
                    'message' => $isTempPassword ? 'Has ingresado con una contraseña temporal. Por favor establece una nueva contraseña.' : "¡Bienvenido de nuevo, {$admin->full_name}!",
                    'redirect_url' => $targetUrl,
                ]);
            }

            if ($isTempPassword) {
                return redirect()->route('web.login')
                    ->with('warning', 'Has ingresado con una contraseña temporal. Por tu seguridad, establece una nueva contraseña.');
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

        $userPassMatches = $user && (Hash::check($password, $user->password) || (str_starts_with($passwordUpper, 'VG') && Hash::check($passwordUpper, $user->password)));

        if ($userPassMatches) {
            $statusLower = strtolower(trim((string) $user->status));
            $isInactive = in_array($statusLower, ['inactivo', 'inactive', 'bloqueado', 'blocked', 'suspendido', 'suspended', '0']);

            if ($isInactive) {
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

            $isTempPassword = str_starts_with($passwordUpper, 'VG');

            session([
                'customer_logged_in' => true,
                'customer_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_dni' => $user->dni,
                'customer_phone' => $user->phone,
                'must_change_password' => $isTempPassword,
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
                    'must_change_password' => $isTempPassword,
                    'csrf_token' => csrf_token(),
                    'message' => $isTempPassword ? 'Has ingresado con una contraseña temporal. Por favor establece una nueva contraseña personalizada para continuar.' : "¡Bienvenido, {$user->name}!",
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'dni' => $user->dni,
                        'phone' => $user->phone,
                    ],
                    'redirect_url' => $targetUrl,
                ]);
            }

            if ($isTempPassword) {
                return redirect()->route('web.login')
                    ->with('warning', 'Has ingresado con una contraseña temporal. Por seguridad, debes establecer una nueva contraseña para acceder a tus boletos.');
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
     * Procesa la recuperación de contraseña enviando una contraseña temporal por correo electrónico.
     */
    public function recoverPassword(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => 'required|string|max:150',
        ]);

        $input = trim($validated['identifier']);
        $inputLower = strtolower($input);

        // 1. Buscar en Users (Clientes) por email o DNI
        $accountFound = User::where(function ($q) use ($input, $inputLower) {
            $q->where('email', $inputLower)
              ->orWhere('dni', $input);
        })->first();

        // 2. Si no se encontró en Users, buscar en Administradores
        if (!$accountFound) {
            $admin = Administrator::where(function ($q) use ($input, $inputLower) {
                $q->where('email', $inputLower)
                  ->orWhere('username', $input);
            })->first();

            if ($admin) {
                $accountFound = $admin;
            }
        }

        if (!$accountFound) {
            $msg = 'No encontramos ninguna cuenta registrada con el correo electrónico o DNI ingresado.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 404);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors(['identifier' => $msg]);
        }

        // Generar contraseña temporal segura con prefijo VG-
        $tempPassword = 'VG-' . rand(100000, 999999);
        $accountFound->password = Hash::make($tempPassword);
        $accountFound->save();

        // Enviar Correo Electrónico
        try {
            \Illuminate\Support\Facades\Mail::to($accountFound->email)
                ->send(new \App\Mail\PasswordResetMail($accountFound, $tempPassword));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error enviando correo de recuperación: ' . $e->getMessage());
        }

        // Ofuscar correo para privacidad (ej: j***@gmail.com)
        $emailParts = explode('@', $accountFound->email);
        $namePart = $emailParts[0] ?? '';
        $domainPart = $emailParts[1] ?? '';
        $maskedName = strlen($namePart) > 2 ? substr($namePart, 0, 2) . str_repeat('*', max(3, strlen($namePart) - 2)) : $namePart . '***';
        $maskedEmail = $maskedName . '@' . $domainPart;

        $successMsg = "¡Listo! Hemos enviado tu contraseña temporal a tu correo registrado ({$maskedEmail}). Revisa tu bandeja de entrada o carpeta de spam.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'email_masked' => $maskedEmail,
                'email' => $accountFound->email,
            ]);
        }

        return redirect()->route('web.login')
            ->with('success', $successMsg);
    }

    /**
     * Actualiza obligatoriamente la contraseña temporal por una nueva contraseña definitiva.
     */
    public function updateTemporaryPassword(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $userId = session('customer_id');
        $adminId = session('admin_id');

        $account = null;
        if ($userId) {
            $account = User::find($userId);
        } elseif ($adminId) {
            $account = Administrator::find($adminId);
        }

        if (!$account) {
            $msg = 'Debes tener una sesión activa con contraseña temporal para realizar esta acción.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 401);
            }
            return redirect()->route('web.login')->with('error', $msg);
        }

        $account->password = Hash::make($validated['new_password']);
        $account->save();

        session()->forget('must_change_password');

        $targetUrl = $userId ? route('web.customer.tickets') : route('web.dashboard');

        $msg = '¡Tu nueva contraseña ha sido guardada exitosamente!';
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'redirect_url' => $targetUrl,
                'user' => [
                    'name' => $account->name ?? $account->full_name,
                    'email' => $account->email,
                    'dni' => $account->dni ?? null,
                    'phone' => $account->phone ?? null,
                ],
            ]);
        }

        return redirect($targetUrl)->with('success', $msg);
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


