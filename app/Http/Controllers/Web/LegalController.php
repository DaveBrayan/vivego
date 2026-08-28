<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    /**
     * Términos y Condiciones de Uso
     */
    public function terms(): View
    {
        $settings = Setting::first();
        $company = Company::first();
        return view('web.legal_terms', compact('settings', 'company'));
    }

    /**
     * Políticas de Privacidad y Tratamiento de Datos Personales
     */
    public function privacy(): View
    {
        $settings = Setting::first();
        $company = Company::first();
        return view('web.legal_privacy', compact('settings', 'company'));
    }

    /**
     * Política de Cookies y Tecnologías Similares
     */
    public function cookies(): View
    {
        $settings = Setting::first();
        $company = Company::first();
        return view('web.legal_cookies', compact('settings', 'company'));
    }
}
