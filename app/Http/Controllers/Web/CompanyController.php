<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Muestra la lista de compañías registradas.
     */
    public function index(): View
    {
        $companies = Company::orderBy('id', 'asc')->get();
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

        return view('web.companies', compact('companies', 'settings', 'organizer', 'countries'));
    }

    /**
     * Registra una nueva compañía en el sistema.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'tax_id' => 'required|string|max:50|unique:companies,tax_id',
            'email' => 'nullable|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'country_iso' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        Company::create([
            'name' => $validated['name'],
            'tax_id' => $validated['tax_id'],
            'email' => isset($validated['email']) ? strtolower($validated['email']) : null,
            'country_code' => $validated['country_code'] ?? '+51',
            'country_iso' => strtolower($validated['country_iso'] ?? 'pe'),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'] ?? 'Activo',
        ]);

        return redirect()->back()->with('success', '¡Compañía registrada exitosamente en el sistema!');
    }

    /**
     * Actualiza la información de una compañía existente.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'tax_id' => 'required|string|max:50|unique:companies,tax_id,' . $company->id,
            'email' => 'nullable|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'country_iso' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'status' => 'required|string|in:Activo,Inactivo',
        ]);

        $company->update([
            'name' => $validated['name'],
            'tax_id' => $validated['tax_id'],
            'email' => isset($validated['email']) ? strtolower($validated['email']) : null,
            'country_code' => $validated['country_code'] ?? '+51',
            'country_iso' => strtolower($validated['country_iso'] ?? 'pe'),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'] ?? 'Activo',
        ]);

        return redirect()->back()->with('success', '¡Información de la compañía actualizada correctamente!');
    }

    /**
     * Elimina una compañía del sistema.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->back()->with('success', '¡La compañía ha sido eliminada del sistema!');
    }
}
