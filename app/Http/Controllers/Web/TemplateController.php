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

        // Obtener plantillas guardadas de la base de datos divididas por tipo
        $templates = TicketTemplate::where('status', 'active')
            ->orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $physicalTemplates = $templates->filter(function ($t) {
            return ($t->type === 'fisica' || empty($t->type) || !in_array($t->type, ['virtual']));
        });

        $virtualTemplates = $templates->filter(function ($t) {
            return ($t->type === 'virtual');
        });

        return view('web.templates', compact('templates', 'physicalTemplates', 'virtualTemplates', 'settings', 'organizer'));
    }

    /**
     * Guarda una nueva plantilla en la Base de Datos.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|string|in:fisica,virtual',
            'bg_color' => 'nullable|string|max:20',
            'bg_image' => 'nullable|string',
            'strip_color' => 'nullable|string|max:20',
            'positions' => 'nullable|array',
            'elements' => 'nullable|array',
        ]);

        if (array_key_exists('bg_image', $validated)) {
            if ($validated['bg_image'] === '' || $validated['bg_image'] === 'null' || empty($validated['bg_image'])) {
                $validated['bg_image'] = null;
            } elseif (str_starts_with($validated['bg_image'], 'data:image')) {
                if (preg_match('/^data:image\/(\w+);base64,/', $validated['bg_image'], $typeMatch)) {
                    $data = substr($validated['bg_image'], strpos($validated['bg_image'], ',') + 1);
                    $ext = strtolower($typeMatch[1]);
                    if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                        $ext = 'png';
                    }
                    $decoded = base64_decode($data);
                    if ($decoded !== false) {
                        $fileName = 'bg_' . uniqid() . '_' . time() . '.' . $ext;
                        \Illuminate\Support\Facades\Storage::disk('public')->put('templates/' . $fileName, $decoded);
                        $validated['bg_image'] = 'storage/templates/' . $fileName;
                    }
                }
            }
        }

        $template = TicketTemplate::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? ($request->input('type') === 'virtual' ? 'Virtual: E-Ticket' : 'General'),
            'type' => $validated['type'] ?? 'fisica',
            'bg_color' => $validated['bg_color'] ?? '#FFFFFF',
            'bg_image' => $validated['bg_image'] ?? null,
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
            'type' => 'nullable|string|in:fisica,virtual',
            'bg_color' => 'nullable|string|max:20',
            'bg_image' => 'nullable|string',
            'strip_color' => 'nullable|string|max:20',
            'positions' => 'nullable|array',
            'elements' => 'nullable|array',
        ]);

        if (array_key_exists('bg_image', $validated)) {
            if ($validated['bg_image'] === '' || $validated['bg_image'] === 'null' || empty($validated['bg_image'])) {
                $validated['bg_image'] = null;
            } elseif (str_starts_with($validated['bg_image'], 'data:image')) {
                if (preg_match('/^data:image\/(\w+);base64,/', $validated['bg_image'], $typeMatch)) {
                    $data = substr($validated['bg_image'], strpos($validated['bg_image'], ',') + 1);
                    $ext = strtolower($typeMatch[1]);
                    if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                        $ext = 'png';
                    }
                    $decoded = base64_decode($data);
                    if ($decoded !== false) {
                        $fileName = 'bg_' . uniqid() . '_' . time() . '.' . $ext;
                        \Illuminate\Support\Facades\Storage::disk('public')->put('templates/' . $fileName, $decoded);
                        $validated['bg_image'] = 'storage/templates/' . $fileName;
                    }
                }
            }
        }

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
