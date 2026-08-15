<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Manager;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    /**
     * Muestra la lista de responsables con sus compañías asignadas.
     */
    public function index(): View
    {
        $managers = Manager::with('company')->orderBy('id', 'asc')->get();
        $companies = Company::where('status', 'Activo')->orderBy('name', 'asc')->get();
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

        return view('web.managers', compact('managers', 'companies', 'settings', 'organizer', 'countries'));
    }

    /**
     * Registra un nuevo responsable y genera su contraseña en automático.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:managers,username',
            'email' => 'required|email|max:255|unique:managers,email',
            'country_code' => 'required|string|max:10',
            'country_iso' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
        ]);

        // Generar contraseña segura automáticamente por el sistema
        $generatedPassword = 'VG' . rand(100, 999) . '!' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));

        $manager = Manager::create([
            'company_id' => $validated['company_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($generatedPassword),
            'country_code' => $validated['country_code'],
            'country_iso' => strtolower($validated['country_iso']),
            'phone' => $validated['phone'],
            'status' => 'Activo',
        ]);

        $manager->load('company');

        return redirect()->back()
            ->with('success', '¡El responsable ha sido registrado correctamente!')
            ->with('created_manager', [
                'name' => $manager->full_name,
                'username' => $manager->username,
                'email' => $manager->email,
                'password' => $generatedPassword,
                'phone' => $manager->country_code . ' ' . $manager->phone,
                'company' => $manager->company->name ?? 'Compañía Asignada',
                'flag' => $manager->flag_emoji,
            ]);
    }

    /**
     * Actualiza los datos de un responsable existente.
     */
    public function update(Request $request, Manager $manager): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:managers,username,' . $manager->id,
            'email' => 'required|email|max:255|unique:managers,email,' . $manager->id,
            'country_code' => 'required|string|max:10',
            'country_iso' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        $manager->update([
            'company_id' => $validated['company_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'country_code' => $validated['country_code'],
            'country_iso' => strtolower($validated['country_iso']),
            'phone' => $validated['phone'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', '¡Información del responsable actualizada correctamente!');
    }

    /**
     * Elimina un responsable del sistema.
     */
    public function destroy(Manager $manager): RedirectResponse
    {
        $manager->delete();

        return redirect()->back()->with('success', '¡El responsable ha sido eliminado del sistema!');
    }

    /**
     * Restablece la contraseña de un responsable y genera una clave temporal.
     */
    public function resetPassword(Manager $manager): RedirectResponse
    {
        $newPassword = 'VG' . rand(100, 999) . '!' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4));

        $manager->update([
            'password' => Hash::make($newPassword),
        ]);

        $manager->load('company');

        return redirect()->back()
            ->with('success', '¡La contraseña del responsable ha sido restablecida correctamente!')
            ->with('reset_password_credentials', [
                'name' => $manager->full_name,
                'username' => $manager->username,
                'email' => $manager->email,
                'password' => $newPassword,
                'phone' => $manager->country_code . ' ' . $manager->phone,
                'company' => $manager->company->name ?? 'Compañía Asignada',
                'flag' => $manager->flag_emoji,
            ]);
    }
}
