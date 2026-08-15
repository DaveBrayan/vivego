<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Muestra la vista de configuración general del sistema.
     */
    public function index(): View
    {
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        $timezones = [
            'America/Lima' => '(UTC-05:00) América / Lima (Perú)',
            'America/Bogota' => '(UTC-05:00) América / Bogotá (Colombia)',
            'America/Mexico_City' => '(UTC-06:00) América / Ciudad de México',
            'America/Santiago' => '(UTC-03:00) América / Santiago (Chile)',
            'America/Buenos_Aires' => '(UTC-03:00) América / Buenos Aires (Argentina)',
            'America/New_York' => '(UTC-05:00) América / Nueva York (EE.UU.)',
            'Europe/Madrid' => '(UTC+01:00) Europa / Madrid (España)',
        ];

        $currencies = [
            'PEN' => ['symbol' => 'S/', 'name' => 'PEN - Soles Peruanos'],
            'USD' => ['symbol' => '$', 'name' => 'USD - Dólares Estadounidenses'],
            'EUR' => ['symbol' => '€', 'name' => 'EUR - Euros'],
            'COP' => ['symbol' => '$', 'name' => 'COP - Pesos Colombianos'],
            'MXN' => ['symbol' => '$', 'name' => 'MXN - Pesos Mexicanos'],
            'CLP' => ['symbol' => '$', 'name' => 'CLP - Pesos Chilenos'],
        ];

        return view('web.settings', compact('settings', 'organizer', 'timezones', 'currencies'));
    }

    /**
     * Actualiza la configuración en la base de datos.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'timezone' => 'required|string|max:100',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'logo_dark_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'logo_white_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'favicon_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp,ico|max:1024',
        ]);

        $settings = Setting::current();
        $settings->site_name = $validated['site_name'];
        $settings->site_description = $validated['site_description'] ?? null;
        $settings->primary_color = $validated['primary_color'];
        $settings->secondary_color = $validated['secondary_color'];
        $settings->timezone = $validated['timezone'];
        $settings->currency = $validated['currency'];
        $settings->currency_symbol = $validated['currency_symbol'];

        // Manejo de carga de archivos de logo e ícono si son subidos
        if ($request->hasFile('logo_dark_file')) {
            $path = $request->file('logo_dark_file')->store('images', 'public');
            $settings->logo_dark = 'storage/' . $path;
        }

        if ($request->hasFile('logo_white_file')) {
            $path = $request->file('logo_white_file')->store('images', 'public');
            $settings->logo_white = 'storage/' . $path;
        }

        if ($request->hasFile('favicon_file')) {
            $path = $request->file('favicon_file')->store('images', 'public');
            $settings->favicon = 'storage/' . $path;
        }

        $settings->save();

        return redirect()->back()->with('success', '¡La configuración del sistema ha sido guardada exitosamente en la base de datos!');
    }
}
