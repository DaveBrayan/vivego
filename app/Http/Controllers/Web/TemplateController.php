<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TicketTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

class TemplateController extends Controller
{
    /**
     * Muestra la lista de plantillas de boletos desde la Base de Datos.
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

        // Sembrar estructuras iniciales solo si la tabla está vacía
        if (TicketTemplate::count() === 0) {
            // Plantilla 1: Taquilla Clásica Oficial (Franja Logo a la Izquierda, Stub Desprendible a la Derecha)
            TicketTemplate::create([
                'name' => 'Plantilla 1: Taquilla Clásica Oficial 2026',
                'category' => 'Estructura 1: Logo Izquierda',
                'bg_color' => '#FFFFFF',
                'strip_color' => '#000000',
                'positions' => [
                    'canvaElTitle' => ['top' => '15px', 'left' => '20px'],
                    'canvaElZone' => ['top' => '45px', 'left' => '20px'],
                    'canvaElPrice' => ['top' => '15px', 'left' => '430px'],
                    'canvaElBanner' => ['top' => '75px', 'left' => '20px'],
                    'canvaElBuyer' => ['top' => '220px', 'left' => '20px'],
                    'canvaElVenue' => ['top' => '220px', 'left' => '340px'],
                    'canvaElTicketNumber' => ['top' => '12px', 'left' => '65px'],
                    'canvaElQR' => ['top' => '45px', 'left' => '55px'],
                    'canvaElHash' => ['top' => '195px', 'left' => '65px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '15px'],
                ],
                'elements' => [],
                'is_default' => true,
                'status' => 'active',
            ]);

            // Plantilla 2: Taquilla con Franja del Logo al Lado DERECHO (Stub a la Izquierda)
            TicketTemplate::create([
                'name' => 'Plantilla 2: Franja Logo Derecho & Stub Izquierdo',
                'category' => 'Estructura 2: Logo Derecho',
                'bg_color' => '#FFFFFF',
                'strip_color' => '#06B6D4',
                'positions' => [
                    'canvaElTicketNumber' => ['top' => '12px', 'left' => '65px'],
                    'canvaElQR' => ['top' => '45px', 'left' => '55px'],
                    'canvaElHash' => ['top' => '195px', 'left' => '65px'],
                    'canvaElDisclaimer' => ['top' => '235px', 'left' => '15px'],
                    'canvaElTitle' => ['top' => '15px', 'left' => '20px'],
                    'canvaElZone' => ['top' => '45px', 'left' => '20px'],
                    'canvaElPrice' => ['top' => '15px', 'left' => '380px'],
                    'canvaElBanner' => ['top' => '75px', 'left' => '20px'],
                    'canvaElBuyer' => ['top' => '220px', 'left' => '20px'],
                    'canvaElVenue' => ['top' => '220px', 'left' => '320px'],
                ],
                'elements' => [],
                'is_default' => false,
                'status' => 'active',
            ]);

            // Plantilla 3: Hero Banner Panorámico & QR Central
            TicketTemplate::create([
                'name' => 'Plantilla 3: Hero Banner Panorámico & QR Central',
                'category' => 'Estructura 3: Banner Panorámico',
                'bg_color' => '#1E1B4B',
                'strip_color' => '#EAB308',
                'positions' => [
                    'canvaElBanner' => ['top' => '12px', 'left' => '15px'],
                    'canvaElTitle' => ['top' => '132px', 'left' => '15px'],
                    'canvaElZone' => ['top' => '175px', 'left' => '15px'],
                    'canvaElPrice' => ['top' => '175px', 'left' => '200px'],
                    'canvaElVenue' => ['top' => '215px', 'left' => '15px'],
                    'canvaElBuyer' => ['top' => '265px', 'left' => '15px'],
                    'canvaElQR' => ['top' => '135px', 'left' => '380px'],
                    'canvaElTicketNumber' => ['top' => '25px', 'left' => '65px'],
                    'canvaElHash' => ['top' => '90px', 'left' => '65px'],
                    'canvaElDisclaimer' => ['top' => '170px', 'left' => '15px'],
                ],
                'elements' => [],
                'is_default' => false,
                'status' => 'active',
            ]);
        }

        // Obtener plantillas guardadas de la base de datos
        $templates = TicketTemplate::where('status', 'active')
            ->orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        return view('web.templates', compact('templates', 'settings', 'organizer'));
    }

    /**
     * Guarda una nueva plantilla en la Base de Datos.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'bg_color' => 'nullable|string|max:20',
            'strip_color' => 'nullable|string|max:20',
            'positions' => 'nullable|array',
            'elements' => 'nullable|array',
        ]);

        $template = TicketTemplate::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'General',
            'bg_color' => $validated['bg_color'] ?? '#FFFFFF',
            'strip_color' => $validated['strip_color'] ?? '#000000',
            'positions' => $validated['positions'] ?? [],
            'elements' => $validated['elements'] ?? [],
            'is_default' => false,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plantilla creada con éxito en la Base de Datos',
            'template' => $template,
        ]);
    }

    /**
     * Actualiza una plantilla existente en la Base de Datos.
     */
    public function update(Request $request, TicketTemplate $template): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'bg_color' => 'nullable|string|max:20',
            'strip_color' => 'nullable|string|max:20',
            'positions' => 'nullable|array',
            'elements' => 'nullable|array',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plantilla actualizada correctamente en la Base de Datos',
            'template' => $template,
        ]);
    }

    /**
     * Elimina una plantilla de la Base de Datos.
     */
    public function destroy(TicketTemplate $template): JsonResponse
    {
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Plantilla eliminada de la Base de Datos',
        ]);
    }
}
