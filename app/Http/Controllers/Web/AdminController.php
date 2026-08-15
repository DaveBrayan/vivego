<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Muestra la lista de administradores del sistema.
     */
    public function index(): View
    {
        $administrators = Administrator::orderBy('id', 'asc')->get();
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        $countries = [
            ['code' => '+51',  'iso' => 'pe', 'flag' => '🇵🇪', 'display' => '🇵🇪 +51'],
            ['code' => '+57',  'iso' => 'co', 'flag' => '🇨🇴', 'display' => '🇨🇴 +57'],
            ['code' => '+52',  'iso' => 'mx', 'flag' => '🇲🇽', 'display' => '🇲🇽 +52'],
            ['code' => '+56',  'iso' => 'cl', 'flag' => '🇨🇱', 'display' => '🇨🇱 +56'],
            ['code' => '+1',   'iso' => 'us', 'flag' => '🇺🇸', 'display' => '🇺🇸 +1'],
            ['code' => '+34',  'iso' => 'es', 'flag' => '🇪🇸', 'display' => '🇪🇸 +34'],
            ['code' => '+54',  'iso' => 'ar', 'flag' => '🇦🇷', 'display' => '🇦🇷 +54'],
            ['code' => '+593', 'iso' => 'ec', 'flag' => '🇪🇨', 'display' => '🇪🇨 +593'],
            ['code' => '+55',  'iso' => 'br', 'flag' => '🇧🇷', 'display' => '🇧🇷 +55'],
        ];

        return view('web.admins', compact('administrators', 'settings', 'organizer', 'countries'));
    }

    /**
     * Almacena un nuevo administrador en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|unique:administrators,username|max:50',
            'email' => 'required|email|unique:administrators,email|max:255',
            'country_code' => 'required|string|max:10',
            'country_iso' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|in:Administrador Principal,Administrador',
        ]);

        // Generar contraseña segura automáticamente por el sistema
        $generatedPassword = 'VG' . rand(100, 999) . '!' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));

        $admin = Administrator::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($generatedPassword),
            'country_code' => $validated['country_code'],
            'country_iso' => strtolower($validated['country_iso']),
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'status' => 'Activo',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=300&q=80',
        ]);

        return redirect()->back()
            ->with('success', '¡El administrador ha sido registrado correctamente!')
            ->with('created_admin', [
                'name' => $admin->full_name,
                'username' => $admin->username,
                'email' => $admin->email,
                'password' => $generatedPassword,
                'phone' => $admin->country_code . ' ' . $admin->phone,
                'role' => $admin->role,
                'flag' => $admin->flag_emoji,
            ]);
    }

    /**
     * Actualiza los datos de un administrador existente.
     */
    public function update(Request $request, Administrator $administrator): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:administrators,username,' . $administrator->id,
            'email' => 'required|email|max:255|unique:administrators,email,' . $administrator->id,
            'country_code' => 'required|string|max:10',
            'country_iso' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|in:Administrador Principal,Administrador',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        $administrator->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'country_code' => $validated['country_code'],
            'country_iso' => strtolower($validated['country_iso']),
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', '¡El administrador ha sido actualizado con éxito!');
    }

    /**
     * Elimina un administrador del sistema.
     */
    public function destroy(Administrator $administrator): RedirectResponse
    {
        $administrator->delete();

        return redirect()->back()->with('success', '¡El administrador ha sido eliminado del sistema!');
    }

    /**
     * Restablece la contraseña de un administrador y genera una nueva contraseña temporal.
     */
    public function resetPassword(Administrator $administrator): RedirectResponse
    {
        $newPassword = 'VG' . rand(100, 999) . '!' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));

        $administrator->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()
            ->with('success', '¡La contraseña ha sido restablecida correctamente!')
            ->with('reset_password_credentials', [
                'name' => $administrator->full_name,
                'username' => $administrator->username,
                'email' => $administrator->email,
                'password' => $newPassword,
                'phone' => $administrator->country_code . ' ' . $administrator->phone,
                'role' => $administrator->role,
                'flag' => $administrator->flag_emoji,
            ]);
    }
}
