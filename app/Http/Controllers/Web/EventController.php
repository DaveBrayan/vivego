<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CapacityType;
use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use App\Models\Setting;
use App\Models\TicketTemplate;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Muestra la lista de eventos desde la Base de Datos.
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

        $companies = Company::all();

        // Obtener eventos guardados en MySQL con sus ventas asociadas
        $dbEvents = Event::with(['template', 'sales'])->orderBy('id', 'desc')->get();

        $events = [];

        foreach ($dbEvents as $ev) {
            $zones = $ev->zones ?? [];
            $currentAvailable = (int) array_sum(array_column($zones, 'capacity'));
            $minPrice = count($zones) > 0 ? min(array_column($zones, 'price')) : 50;

            // Calcular ventas reales y aforo total inicial (disponible + vendido)
            $ticketsSold = $ev->sales ? (int) $ev->sales->sum('quantity') : (int) TicketSale::where('event_id', $ev->id)->sum('quantity');
            $totalCapacity = $currentAvailable + $ticketsSold;
            $capacityPercentage = $totalCapacity > 0 ? min(100, round(($ticketsSold / $totalCapacity) * 100, 1)) : 0;

            // Formatear fecha de manera 100% segura contra cadenas o Carbon
            $dateFormatted = '10/04/2025';
            if (!empty($ev->event_date)) {
                try {
                    if ($ev->event_date instanceof \DateTimeInterface) {
                        $dateFormatted = $ev->event_date->format('d/m/Y');
                    } else {
                        $dateFormatted = Carbon::parse($ev->event_date)->format('d/m/Y');
                    }
                } catch (\Throwable $e) {
                    $dateFormatted = (string) $ev->event_date;
                }
            }

            // Obtener datos exactos de la plantilla guardada o por defecto
            $templateModel = $ev->template;
            if (!$templateModel && $ev->template_id) {
                $templateModel = TicketTemplate::find($ev->template_id);
            }
            if (!$templateModel) {
                $templateModel = TicketTemplate::where('is_default', 1)->first() ?? TicketTemplate::first();
            }

            $templateData = [
                'id' => $templateModel ? $templateModel->id : 1,
                'name' => $templateModel ? $templateModel->name : 'Plantilla 1: Taquilla Clásica Oficial 2026',
                'category' => $templateModel ? $templateModel->category : 'Estructura 1: Logo Izquierda',
                'type' => $templateModel ? $templateModel->type : 'fisica',
                'bg_color' => ($templateModel && $templateModel->bg_color) ? $templateModel->bg_color : '#FFFFFF',
                'bg_image' => $templateModel ? $templateModel->bg_image : null,
                'strip_color' => ($templateModel && $templateModel->strip_color) ? $templateModel->strip_color : '#000000',
                'positions' => $templateModel ? ($templateModel->positions ?? []) : [],
                'elements' => $templateModel ? ($templateModel->elements ?? []) : [],
            ];

            $evStatus = $ev->status ?? 'Publicado';
            $evStatusClass = 'badge-green';
            if ($evStatus === 'Borrador' || $evStatus === 'draft') {
                $evStatusClass = 'badge-gray';
            } elseif ($evStatus === 'Oculto' || $evStatus === 'No Marketplace' || $evStatus === 'unlisted') {
                $evStatusClass = 'badge-purple';
            } elseif ($evStatus === 'Agotado') {
                $evStatusClass = 'badge-red';
            }

            $events[] = [
                'id' => $ev->id,
                'title' => $ev->title,
                'slug' => $ev->slug,
                'category' => $ev->category_name ?? 'Concierto',
                'category_icon' => '🎤',
                'image' => $ev->banner_image ?? 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                'company_name' => $ev->company_name ?? 'PRODUCCIONES VIVE GO S.A.C.',
                'venue' => $ev->venue_name ?? 'Complejo San Luis',
                'city' => $ev->address ?? 'Ayacucho',
                'date_formatted' => $dateFormatted,
                'time_formatted' => $ev->event_time ?? '18:00 hrs',
                'tickets_sold' => $ticketsSold,
                'total_capacity' => $totalCapacity > 0 ? $totalCapacity : 60,
                'capacity_percentage' => $capacityPercentage,
                'min_price' => 'S/ ' . number_format($minPrice, 2),
                'revenue_formatted' => 'S/ 0.00',
                'status' => $evStatus,
                'status_class' => $evStatusClass,
                'sales_type' => $ev->sales_type ?? 'fisica',
                'template' => $templateData,
                'zones' => $zones,
                'template_id' => $ev->template_id,
            ];
        }

        // Si la base de datos no tiene eventos aún, mostrar lista inicial de demostración
        if (count($events) === 0) {
            $events = [
                [
                    'id' => 1,
                    'title' => 'GRUPO 5 - NOCHE DE ORO 50 ANIVERSARIO',
                    'slug' => 'grupo-5-noche-de-oro',
                    'category' => 'Conciertos',
                    'category_icon' => '🎤',
                    'image' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                    'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
                    'venue' => 'Estadio Nacional de Lima',
                    'city' => 'Lima',
                    'date_formatted' => '15/11/2026',
                    'time_formatted' => '20:00 hrs',
                    'tickets_sold' => 0,
                    'total_capacity' => 800,
                    'capacity_percentage' => 0,
                    'min_price' => 'S/ 55.50',
                    'revenue_formatted' => 'S/ 0.00',
                    'status' => 'Publicado',
                    'status_class' => 'badge-green',
                    'sales_type' => 'fisica',
                    'template' => [
                        'id' => 1,
                        'name' => 'Plantilla 1: Taquilla Clásica Oficial 2026',
                        'category' => 'Estructura 1: Logo Izquierda',
                        'bg_color' => '#FFFFFF',
                        'strip_color' => '#000000',
                        'positions' => [],
                        'elements' => [],
                    ],
                    'zones' => [
                        ['name' => 'ZONA VIP PLATINUM', 'price' => 150.00, 'capacity' => 1000],
                        ['name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 2000],
                        ['name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 2000],
                    ],
                    'template_id' => 1,
                ],
            ];
        }

        return view('web.events', compact('events', 'companies', 'settings', 'organizer'));
    }

    /**
     * Muestra la página de creación de eventos por pasos (Multi-step Wizard).
     */
    public function create(): View
    {
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        // Cargar Categorías desde MySQL
        $categories = Category::all();
        if ($categories->count() === 0) {
            $categories = collect([
                ['id' => 1, 'name' => 'Conciertos', 'icon' => '🎤'],
                ['id' => 2, 'name' => 'Festivales', 'icon' => '🎪'],
                ['id' => 3, 'name' => 'Teatro & Cultura', 'icon' => '🎭'],
                ['id' => 4, 'name' => 'Conferencias & Tech', 'icon' => '💻'],
                ['id' => 5, 'name' => 'Deportes & Fitness', 'icon' => '⚽'],
            ]);
        }

        // Cargar Compañías desde MySQL
        $companies = Company::all();
        if ($companies->count() === 0) {
            $companies = collect([
                ['id' => 1, 'name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU', 'tax_id' => '20601234567'],
                ['id' => 2, 'name' => 'PRODUCCIONES VIVE GO S.A.C.', 'tax_id' => '20559876543'],
                ['id' => 3, 'name' => 'ENTRETENIMIENTO GLOBAL LATAM S.A.', 'tax_id' => '900123456-1'],
            ]);
        }

        // Cargar TODOS los Tipos de Aforo creados en MySQL
        $capacityTypes = CapacityType::orderBy('id', 'asc')->get();

        // Cargar Plantillas de Boletos desde MySQL
        $templates = TicketTemplate::orderBy('is_default', 'desc')->get();

        return view('web.events_create', compact('companies', 'categories', 'capacityTypes', 'templates', 'settings', 'organizer'));
    }

    /**
     * Guarda un nuevo evento en la Base de Datos.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_name' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'banner_image' => 'nullable|string',
            'reference_image' => 'nullable|string',
            'layout_template' => 'nullable|string|in:template_1,template_2',
            'background_image' => 'nullable|string',
            'background_mobile_image' => 'nullable|string',
            'artist_image' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:20',
            'venue_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'template_id' => 'nullable|integer',
            'zones' => 'nullable|array',
            'courtesy_settings' => 'nullable|array',
            'sales_type' => 'nullable|string|in:fisica,virtual',
        ]);

        $bannerImage = $this->saveBase64Image($validated['banner_image'] ?? null, 'events', 'event_banner');
        $referenceImage = $this->saveBase64Image($validated['reference_image'] ?? null, 'events', 'reference_image');
        $backgroundImage = $this->saveBase64Image($validated['background_image'] ?? null, 'events', 'bg_immersive');
        $backgroundMobileImage = $this->saveBase64Image($validated['background_mobile_image'] ?? null, 'events', 'bg_mobile');
        $artistImage = $this->saveBase64Image($validated['artist_image'] ?? null, 'events', 'artist_cutout');

        $slug = Str::slug($validated['title']) . '-' . rand(100, 999);

        $event = Event::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category_name' => $validated['category_name'] ?? 'Conciertos',
            'company_name' => $validated['company_name'] ?? 'Vive Go',
            'banner_image' => $bannerImage,
            'reference_image' => $referenceImage,
            'layout_template' => $validated['layout_template'] ?? 'template_1',
            'background_image' => $backgroundImage,
            'background_mobile_image' => $backgroundMobileImage,
            'artist_image' => $artistImage,
            'event_date' => $validated['event_date'] ?? null,
            'event_time' => $validated['event_time'] ?? '18:00',
            'venue_name' => $validated['venue_name'] ?? 'Complejo San Luis',
            'address' => $validated['address'] ?? 'Ayacucho',
            'latitude' => $validated['latitude'] ?? -13.1631,
            'longitude' => $validated['longitude'] ?? -74.2236,
            'description' => $validated['description'] ?? null,
            'tags' => $validated['tags'] ?? [],
            'template_id' => $validated['template_id'] ?? null,
            'zones' => $validated['zones'] ?? [],
            'courtesy_settings' => $request->input('courtesy_settings') ?? [],
            'status' => 'Publicado',
            'sales_type' => $validated['sales_type'] ?? 'fisica',
        ]);

        $customTicket = $request->input('custom_ticket');
        if (!empty($customTicket) && is_array($customTicket)) {
            $rawBg = $customTicket['background'] ?? $customTicket['bg_image'] ?? null;
            $bgImage = !empty($rawBg) ? $this->saveBase64Image($rawBg, 'templates', 'bg') : null;
            $ticketBanner = $this->saveBase64Image($customTicket['ticket_banner'] ?? null, 'templates', 'ticket_banner');

            $elements = $customTicket['elements'] ?? [];
            if (is_array($elements)) {
                foreach ($elements as &$el) {
                    if (is_array($el) && !empty($el['src']) && str_starts_with($el['src'], 'data:image')) {
                        $el['src'] = $this->saveBase64Image($el['src'], 'templates', 'tpl_elem_' . ($el['id'] ?? rand(100, 999)));
                    }
                }
            }

            $positions = $customTicket['positions'] ?? [];
            if (is_array($positions)) {
                foreach ($positions as $k => &$pos) {
                    if (is_array($pos) && !empty($pos['src']) && str_starts_with($pos['src'], 'data:image')) {
                        $pos['src'] = $this->saveBase64Image($pos['src'], 'templates', 'tpl_elem_' . $k);
                    }
                }
            }

            if ($ticketBanner && isset($positions['canvaElBanner'])) {
                $positions['canvaElBanner']['src'] = $ticketBanner;
            }

            $tpl = TicketTemplate::create([
                'name' => 'Boleto: ' . $validated['title'],
                'category' => (($validated['sales_type'] ?? 'virtual') === 'virtual' ? 'Virtual: E-Ticket Evento' : 'Física: Taquilla Evento'),
                'type' => $validated['sales_type'] ?? 'virtual',
                'bg_color' => $customTicket['bg_color'] ?? '#FFFFFF',
                'bg_image' => $bgImage,
                'strip_color' => $customTicket['strip_color'] ?? '#FF5500',
                'positions' => $positions,
                'elements' => $elements,
                'is_default' => false,
                'status' => 'active',
            ]);
            $event->template_id = $tpl->id;
            $event->save();
        }

        return response()->json([
            'success' => true,
            'message' => '¡Evento publicado y guardado en MySQL con éxito!',
            'event' => $event,
            'redirect' => route('events.index')
        ]);
    }

    /**
     * Muestra la página de edición de un evento (Multi-step Wizard prellenado).
     */
    public function edit($id): View
    {
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        // Buscar evento en BD o usar mock si es un ID numérico predeterminado
        $eventModel = Event::with('template')->find($id);

        if (!$eventModel) {
            $dbEvent = Event::with('template')->where('id', $id)->first();
            if ($dbEvent) {
                $eventModel = $dbEvent;
            }
        }

        if (!$eventModel) {
            $eventData = [
                'id' => $id,
                'title' => 'GRUPO 5 - NOCHE DE ORO 50 ANIVERSARIO',
                'category_name' => 'Conciertos',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
                'banner_image' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                'reference_image' => null,
                'layout_template' => 'template_1',
                'background_image' => null,
                'background_mobile_image' => null,
                'artist_image' => null,
                'event_date' => '2026-11-15',
                'event_time' => '20:00',
                'venue_name' => 'Estadio Nacional de Lima',
                'address' => 'Lima',
                'latitude' => -12.0671,
                'longitude' => -77.0336,
                'description' => 'Gran concierto de aniversario del Grupo 5 en vivo con el mejor espectáculo.',
                'tags' => ['cumbia', 'envivo', 'grupo5'],
                'template_id' => 1,
                'zones' => [
                    ['capacity_type' => 'Aforo VIP', 'name' => 'BOX PLATINUM INDIVIDUAL', 'price' => 150.00, 'capacity' => 10],
                    ['capacity_type' => 'Aforo Preferencial', 'name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 20],
                    ['capacity_type' => 'Aforo General', 'name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 30]
                ],
                'status' => 'Publicado',
                'sales_type' => 'fisica',
            ];
        } else {
            $dateVal = '';
            if (!empty($eventModel->event_date)) {
                if ($eventModel->event_date instanceof \DateTimeInterface) {
                    $dateVal = $eventModel->event_date->format('Y-m-d');
                } else {
                    $dateVal = substr((string)$eventModel->event_date, 0, 10);
                }
            }

            $templateModel = $eventModel->template;
            if (!$templateModel && $eventModel->template_id) {
                $templateModel = TicketTemplate::find($eventModel->template_id);
            }
            if (!$templateModel) {
                $templateModel = TicketTemplate::where('is_default', 1)->first() ?? TicketTemplate::first();
            }

            $templateData = $templateModel ? [
                'id' => $templateModel->id,
                'name' => $templateModel->name,
                'category' => $templateModel->category,
                'type' => $templateModel->type,
                'bg_color' => ($templateModel->bg_color) ? $templateModel->bg_color : '#FFFFFF',
                'bg_image' => $templateModel->bg_image,
                'strip_color' => ($templateModel->strip_color) ? $templateModel->strip_color : '#000000',
                'positions' => $templateModel->positions ?? [],
                'elements' => $templateModel->elements ?? [],
            ] : null;

            $eventData = [
                'id' => $eventModel->id,
                'title' => $eventModel->title,
                'category_name' => $eventModel->category_name ?? 'Conciertos',
                'company_name' => $eventModel->company_name ?? 'Vive Go',
                'banner_image' => $eventModel->banner_image,
                'reference_image' => $eventModel->reference_image,
                'layout_template' => $eventModel->layout_template ?? 'template_1',
                'background_image' => $eventModel->background_image,
                'background_mobile_image' => $eventModel->background_mobile_image,
                'artist_image' => $eventModel->artist_image,
                'event_date' => $dateVal,
                'event_time' => $eventModel->event_time ?? '18:00',
                'venue_name' => $eventModel->venue_name ?? '',
                'address' => $eventModel->address ?? '',
                'latitude' => $eventModel->latitude ?? -13.1631,
                'longitude' => $eventModel->longitude ?? -74.2236,
                'description' => $eventModel->description ?? '',
                'tags' => is_array($eventModel->tags) ? $eventModel->tags : [],
                'template_id' => $eventModel->template_id ?? ($templateModel ? $templateModel->id : 1),
                'template' => $templateData,
                'zones' => is_array($eventModel->zones) ? $eventModel->zones : [],
                'courtesy_settings' => is_array($eventModel->courtesy_settings) ? $eventModel->courtesy_settings : (json_decode($eventModel->courtesy_settings, true) ?? []),
                'status' => $eventModel->status ?? 'Publicado',
                'sales_type' => $eventModel->sales_type ?? 'fisica',
            ];
        }

        $categories = Category::all();
        if ($categories->count() === 0) {
            $categories = collect([
                ['id' => 1, 'name' => 'Conciertos', 'icon' => '🎤'],
                ['id' => 2, 'name' => 'Festivales', 'icon' => '🎪'],
                ['id' => 3, 'name' => 'Teatro & Cultura', 'icon' => '🎭'],
                ['id' => 4, 'name' => 'Conferencias & Tech', 'icon' => '💻'],
                ['id' => 5, 'name' => 'Deportes & Fitness', 'icon' => '⚽'],
            ]);
        }

        $companies = Company::all();
        if ($companies->count() === 0) {
            $companies = collect([
                ['id' => 1, 'name' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU', 'tax_id' => '20601234567'],
                ['id' => 2, 'name' => 'PRODUCCIONES VIVE GO S.A.C.', 'tax_id' => '20559876543'],
                ['id' => 3, 'name' => 'ENTRETENIMIENTO GLOBAL LATAM S.A.', 'tax_id' => '900123456-1'],
            ]);
        }

        $capacityTypes = CapacityType::orderBy('id', 'asc')->get();
        $templates = TicketTemplate::orderBy('is_default', 'desc')->get();

        return view('web.events_edit', compact('eventData', 'companies', 'categories', 'capacityTypes', 'templates', 'settings', 'organizer'));
    }

    /**
     * Actualiza los datos de un evento en la Base de Datos.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_name' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'banner_image' => 'nullable|string',
            'reference_image' => 'nullable|string',
            'layout_template' => 'nullable|string|in:template_1,template_2',
            'background_image' => 'nullable|string',
            'background_mobile_image' => 'nullable|string',
            'artist_image' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:20',
            'venue_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'template_id' => 'nullable|integer',
            'zones' => 'nullable|array',
            'courtesy_settings' => 'nullable|array',
            'status' => 'nullable|string',
            'sales_type' => 'nullable|string|in:fisica,virtual',
        ]);

        $event = Event::find($id);

        if (!$event) {
            $bannerImage = $this->saveBase64Image($validated['banner_image'] ?? null, 'events', 'event_banner');
            $referenceImage = $this->saveBase64Image($validated['reference_image'] ?? null, 'events', 'reference_image');
            $backgroundImage = $this->saveBase64Image($validated['background_image'] ?? null, 'events', 'bg_immersive');
            $backgroundMobileImage = $this->saveBase64Image($validated['background_mobile_image'] ?? null, 'events', 'bg_mobile');
            $artistImage = $this->saveBase64Image($validated['artist_image'] ?? null, 'events', 'artist_cutout');
            $slug = Str::slug($validated['title']) . '-' . rand(100, 999);
            $event = Event::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'category_name' => $validated['category_name'] ?? 'Conciertos',
                'company_name' => $validated['company_name'] ?? 'Vive Go',
                'banner_image' => $bannerImage,
                'reference_image' => $referenceImage,
                'layout_template' => $validated['layout_template'] ?? 'template_1',
                'background_image' => $backgroundImage,
                'background_mobile_image' => $backgroundMobileImage,
                'artist_image' => $artistImage,
                'event_date' => $validated['event_date'] ?? null,
                'event_time' => $validated['event_time'] ?? '18:00',
                'venue_name' => $validated['venue_name'] ?? 'Complejo San Luis',
                'address' => $validated['address'] ?? 'Ayacucho',
                'latitude' => $validated['latitude'] ?? -13.1631,
                'longitude' => $validated['longitude'] ?? -74.2236,
                'description' => $validated['description'] ?? null,
                'tags' => $validated['tags'] ?? [],
                'template_id' => $validated['template_id'] ?? null,
                'zones' => $validated['zones'] ?? [],
                'courtesy_settings' => $request->input('courtesy_settings') ?? [],
                'status' => $validated['status'] ?? 'Publicado',
                'sales_type' => $validated['sales_type'] ?? 'fisica',
            ]);
        } else {
            $bannerImage = $this->saveBase64Image($validated['banner_image'] ?? $event->banner_image, 'events', 'event_banner');
            $referenceImage = array_key_exists('reference_image', $validated) ? $this->saveBase64Image($validated['reference_image'], 'events', 'reference_image') : $event->reference_image;
            $backgroundImage = array_key_exists('background_image', $validated) ? $this->saveBase64Image($validated['background_image'], 'events', 'bg_immersive') : $event->background_image;
            $backgroundMobileImage = array_key_exists('background_mobile_image', $validated) ? $this->saveBase64Image($validated['background_mobile_image'], 'events', 'bg_mobile') : $event->background_mobile_image;
            $artistImage = array_key_exists('artist_image', $validated) ? $this->saveBase64Image($validated['artist_image'], 'events', 'artist_cutout') : $event->artist_image;

            $event->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . $event->id,
                'category_name' => $validated['category_name'] ?? $event->category_name,
                'company_name' => $validated['company_name'] ?? $event->company_name,
                'banner_image' => $bannerImage,
                'reference_image' => $referenceImage,
                'layout_template' => $validated['layout_template'] ?? $event->layout_template ?? 'template_1',
                'background_image' => $backgroundImage,
                'background_mobile_image' => $backgroundMobileImage,
                'artist_image' => $artistImage,
                'event_date' => $validated['event_date'] ?? $event->event_date,
                'event_time' => $validated['event_time'] ?? $event->event_time,
                'venue_name' => $validated['venue_name'] ?? $event->venue_name,
                'address' => $validated['address'] ?? $event->address,
                'latitude' => $validated['latitude'] ?? $event->latitude,
                'longitude' => $validated['longitude'] ?? $event->longitude,
                'description' => $validated['description'] ?? $event->description,
                'tags' => $validated['tags'] ?? $event->tags,
                'template_id' => $validated['template_id'] ?? $event->template_id,
                'zones' => $validated['zones'] ?? $event->zones,
                'courtesy_settings' => $request->input('courtesy_settings') ?? [],
                'status' => $validated['status'] ?? $event->status ?? 'Publicado',
                'sales_type' => $validated['sales_type'] ?? $event->sales_type ?? 'fisica',
            ]);
        }

        $customTicket = $request->input('custom_ticket');
        if (!empty($customTicket) && is_array($customTicket)) {
            $rawBg = $customTicket['background'] ?? $customTicket['bg_image'] ?? null;
            $bgImage = !empty($rawBg) ? $this->saveBase64Image($rawBg, 'templates', 'bg') : null;
            $ticketBanner = array_key_exists('ticket_banner', $customTicket) ? $this->saveBase64Image($customTicket['ticket_banner'], 'templates', 'ticket_banner') : null;

            $elements = $customTicket['elements'] ?? [];
            if (is_array($elements)) {
                foreach ($elements as &$el) {
                    if (is_array($el) && !empty($el['src']) && str_starts_with($el['src'], 'data:image')) {
                        $el['src'] = $this->saveBase64Image($el['src'], 'templates', 'tpl_elem_' . ($el['id'] ?? rand(100, 999)));
                    }
                }
            }

            $positions = $customTicket['positions'] ?? [];
            if (is_array($positions)) {
                foreach ($positions as $k => &$pos) {
                    if (is_array($pos) && !empty($pos['src'])) {
                        if (str_starts_with($pos['src'], 'data:image')) {
                            $pos['src'] = $this->saveBase64Image($pos['src'], 'templates', 'tpl_elem_' . $k);
                        } else {
                            $pos['src'] = $this->sanitizeAssetUrl($pos['src']);
                        }
                    }
                }
            }

            if ($ticketBanner && isset($positions['canvaElBanner'])) {
                $positions['canvaElBanner']['src'] = $ticketBanner;
            }

            $existingTemplate = $event->template_id ? TicketTemplate::find($event->template_id) : null;
            if ($existingTemplate && str_starts_with($existingTemplate->name, 'Boleto:')) {
                $existingTemplate->update([
                    'name' => 'Boleto: ' . $validated['title'],
                    'category' => (($validated['sales_type'] ?? 'virtual') === 'virtual' ? 'Virtual: E-Ticket Evento' : 'Física: Taquilla Evento'),
                    'type' => $validated['sales_type'] ?? $event->sales_type ?? 'virtual',
                    'bg_color' => $customTicket['bg_color'] ?? $existingTemplate->bg_color,
                    'bg_image' => $bgImage ?? $existingTemplate->bg_image,
                    'strip_color' => $customTicket['strip_color'] ?? $existingTemplate->strip_color,
                    'positions' => $positions,
                    'elements' => count($elements) > 0 ? $elements : $existingTemplate->elements,
                ]);
            } else {
                $tpl = TicketTemplate::create([
                    'name' => 'Boleto: ' . $validated['title'],
                    'category' => (($validated['sales_type'] ?? 'virtual') === 'virtual' ? 'Virtual: E-Ticket Evento' : 'Física: Taquilla Evento'),
                    'type' => $validated['sales_type'] ?? $event->sales_type ?? 'virtual',
                    'bg_color' => $customTicket['bg_color'] ?? '#FFFFFF',
                    'bg_image' => $bgImage,
                    'strip_color' => $customTicket['strip_color'] ?? '#FF5500',
                    'positions' => $positions,
                    'elements' => $elements,
                    'is_default' => false,
                    'status' => 'active',
                ]);
                $event->template_id = $tpl->id;
                $event->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Evento actualizado con éxito en MySQL!',
        ]);
    }

    /**
     * Elimina un evento de la Base de Datos junto con todos sus boletos y códigos QR asociados.
     */
    public function destroy(Event $event): JsonResponse|RedirectResponse
    {
        // Eliminar en cascada todos los boletos, QR generados y ventas del evento
        $event->tickets()->delete();
        $event->sales()->delete();
        $event->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Evento y todos sus boletos/QR eliminados exitosamente de la base de datos!',
            ]);
        }

        return redirect()->back()->with('success', '¡Evento y sus boletos eliminados de la Base de Datos!');
    }

    /**
     * Duplica un evento existente clonando toda su información, zonas de aforo y el diseño del boleto.
     */
    public function duplicate(Request $request, Event $event): JsonResponse
    {
        $baseTitle = $event->title;
        $newTitle = '[Copia] ' . $baseTitle;
        $slug = Str::slug($newTitle) . '-' . rand(100, 999);

        $rawBanner = $event->getRawOriginal('banner_image') ?? $event->banner_image;
        $rawRefImage = $event->getRawOriginal('reference_image') ?? $event->reference_image;

        $newEvent = Event::create([
            'title' => $newTitle,
            'slug' => $slug,
            'category_name' => $event->category_name,
            'company_name' => $event->company_name,
            'banner_image' => $rawBanner,
            'reference_image' => $rawRefImage,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
            'venue_name' => $event->venue_name,
            'address' => $event->address,
            'latitude' => $event->latitude,
            'longitude' => $event->longitude,
            'description' => $event->description,
            'tags' => $event->tags,
            'template_id' => null,
            'zones' => $event->zones,
            'status' => $event->status ?? 'Publicado',
            'sales_type' => $event->sales_type ?? 'fisica',
        ]);

        if ($event->template_id) {
            $originalTemplate = TicketTemplate::find($event->template_id);
            if ($originalTemplate) {
                $rawBgImage = $originalTemplate->getRawOriginal('bg_image') ?? $originalTemplate->bg_image;
                $rawPositions = $originalTemplate->getRawOriginal('positions');
                $positionsArray = is_string($rawPositions) ? json_decode($rawPositions, true) : ($originalTemplate->positions ?? []);

                $newTemplate = TicketTemplate::create([
                    'name' => 'Boleto: ' . $newTitle,
                    'category' => $originalTemplate->category,
                    'type' => $originalTemplate->type,
                    'bg_color' => $originalTemplate->bg_color,
                    'bg_image' => $rawBgImage,
                    'strip_color' => $originalTemplate->strip_color,
                    'positions' => $positionsArray,
                    'elements' => $originalTemplate->elements,
                    'is_default' => false,
                    'status' => 'active',
                ]);

                $newEvent->template_id = $newTemplate->id;
                $newEvent->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Evento y diseño del boleto duplicados con éxito!',
            'event' => $newEvent,
        ]);
    }

    /**
     * Registra en la base de datos el lote de boletos generados para impresión PDF.
     */
    public function storeBatchTickets(Request $request, Event $event): JsonResponse
    {
        @set_time_limit(120);

        $validated = $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_code' => 'required|string',
            'tickets.*.ticket_number' => 'nullable|integer',
            'tickets.*.zone_name' => 'required|string',
            'tickets.*.unit_price' => 'nullable|numeric',
            'tickets.*.qr_payload' => 'required|string',
            'tickets.*.validation_hash' => 'nullable|string',
            'tickets.*.buyer_name' => 'nullable|string',
            'tickets.*.buyer_dni' => 'nullable|string',
        ]);

        $insertedCount = 0;
        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $event, &$insertedCount) {
            foreach ($validated['tickets'] as $t) {
                // Buscar si este boleto ya fue registrado para este evento (por código de boleto o por payload)
                $ticketCode = $t['ticket_code'] ?? null;
                $qrPayload = $t['qr_payload'] ?? null;

                $existing = \App\Models\EventTicket::where('event_id', $event->id)
                    ->where(function ($q) use ($ticketCode, $qrPayload) {
                        if ($ticketCode) {
                            $q->where('ticket_code', $ticketCode);
                        }
                        if ($qrPayload) {
                            $q->orWhere('qr_payload', $qrPayload);
                        }
                    })
                    ->first();

                if ($existing) {
                    // Si ya existe, NO mutamos ni su qr_payload ni su validation_hash para no invalidar impresiones previas
                    $existing->update([
                        'ticket_code' => $ticketCode ?: $existing->ticket_code,
                        'ticket_number' => $t['ticket_number'] ?? $existing->ticket_number,
                        'zone_name' => $t['zone_name'] ?? $existing->zone_name,
                        'unit_price' => $t['unit_price'] ?? $existing->unit_price,
                        'buyer_name' => $t['buyer_name'] ?? $existing->buyer_name,
                        'buyer_dni' => $t['buyer_dni'] ?? $existing->buyer_dni,
                        'source' => 'pdf_batch',
                    ]);
                } else {
                    // Si es nuevo (aumento de aforo o primera tanda), registrar con sus claves únicas
                    \App\Models\EventTicket::create([
                        'event_id' => $event->id,
                        'ticket_code' => $ticketCode,
                        'ticket_number' => $t['ticket_number'] ?? 1,
                        'zone_name' => $t['zone_name'] ?? 'General',
                        'unit_price' => $t['unit_price'] ?? 0.00,
                        'validation_hash' => $t['validation_hash'] ?? null,
                        'qr_payload' => $qrPayload,
                        'buyer_name' => $t['buyer_name'] ?? 'Impresión de Evento',
                        'buyer_dni' => $t['buyer_dni'] ?? '00000000',
                        'source' => 'pdf_batch',
                        'is_used' => false,
                        'status' => 'valid',
                    ]);
                }
                $insertedCount++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "¡{$insertedCount} boletos registrados con éxito en la base de datos para control de acceso!",
            'count' => $insertedCount,
        ]);
    }

    /**
     * Obtiene los boletos ya registrados en la base de datos para impresión PDF de este evento.
     */
    public function getRegisteredTickets(Event $event): JsonResponse
    {
        $tickets = \App\Models\EventTicket::where('event_id', $event->id)
            ->where('source', 'pdf_batch')
            ->orderBy('ticket_number', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->values()
            ->map(function ($t, $index) use ($event) {
                $num = (int) $t->ticket_number > 0 ? (int) $t->ticket_number : ($index + 1);
                $formattedCode = $t->ticket_code ?: ('N° ' . str_pad($num, 5, '0', STR_PAD_LEFT));
                $valHash = $t->validation_hash ?: ('VG' . strtoupper(substr(md5($event->id . '_' . $num . '_' . $t->id), 0, 8)));
                $qrPayload = $t->qr_payload ?: "VIVEGO|EVT-{$event->id}|TICK-{$num}|HASH-{$valHash}";

                return [
                    'id' => $t->id,
                    'ticketNumberVal' => $num,
                    'ticketCode' => $formattedCode,
                    'zoneName' => $t->zone_name,
                    'zonePrice' => number_format((float) $t->unit_price, 2, '.', ''),
                    'validationHash' => $valHash,
                    'qrPayload' => $qrPayload,
                    'buyerName' => $t->buyer_name ?: 'Impresión de Evento',
                    'buyerDni' => $t->buyer_dni ?: '00000000',
                    'source' => $t->source,
                ];
            });

        return response()->json([
            'success' => true,
            'count' => $tickets->count(),
            'tickets' => $tickets,
        ]);
    }

    /**
     * Elimina todos los boletos generados para impresión PDF de este evento.
     */
    public function destroyBatchTickets(Request $request, Event $event): JsonResponse
    {
        $query = \App\Models\EventTicket::where('event_id', $event->id)
            ->where(function ($q) {
                $q->where('source', 'pdf_batch')
                  ->orWhereNull('ticket_sale_id');
            });

        // Si se envía una zona específica a eliminar
        $zone = $request->input('zone');
        if (!empty($zone) && $zone !== 'ALL') {
            $query->where('zone_name', 'LIKE', $zone . '%');
        }

        $deletedCount = $query->delete();

        return response()->json([
            'success' => true,
            'message' => "Se han eliminado {$deletedCount} entradas generadas de este evento.",
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * Limpia y normaliza URLs de imágenes eliminando dominios o IPs locales previas.
     */
    protected function sanitizeAssetUrl(?string $imageString): ?string
    {
        if (empty($imageString)) {
            return null;
        }

        if (str_starts_with($imageString, 'data:image')) {
            return $imageString;
        }

        if (str_starts_with($imageString, 'http://') || str_starts_with($imageString, 'https://')) {
            return $imageString;
        }

        $clean = ltrim($imageString, '/');

        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            return 'storage/' . ltrim($matches[1], '/');
        }

        if (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            return 'images/' . ltrim($matches[1], '/');
        }

        if (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            return 'storage/' . $clean;
        }

        return $clean;
    }

    /**
     * Escribe datos binarios de imagen directamente en disco sin requerir la extensión PHP finfo.
     */
    protected function writeBinaryFile(string $folder, string $fileName, string $binaryData): string
    {
        $storageDir = storage_path('app/public/' . $folder);
        if (!file_exists($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }
        @file_put_contents($storageDir . '/' . $fileName, $binaryData);

        $publicDir = public_path('storage/' . $folder);
        if (!file_exists($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        @file_put_contents($publicDir . '/' . $fileName, $binaryData);

        return 'storage/' . $folder . '/' . $fileName;
    }

    /**
     * Decodifica una imagen base64 y la guarda en el disco público de Laravel.
     */
    protected function saveBase64Image(?string $imageString, string $folder = 'events', string $prefix = 'img'): ?string
    {
        if (empty($imageString)) {
            return null;
        }

        $imageString = $this->sanitizeAssetUrl($imageString);

        if (!str_starts_with($imageString, 'data:image')) {
            return $imageString;
        }

        if (preg_match('/^data:image\/([a-zA-Z0-9+\.-]+);(?:charset=[^;]+;)?base64,(.+)$/s', $imageString, $matches)) {
            $rawExt = strtolower($matches[1]);
            $ext = 'png';
            if (str_contains($rawExt, 'jpg') || str_contains($rawExt, 'jpeg')) $ext = 'jpg';
            elseif (str_contains($rawExt, 'webp')) $ext = 'webp';
            elseif (str_contains($rawExt, 'gif')) $ext = 'gif';
            elseif (str_contains($rawExt, 'svg')) $ext = 'svg';

            $data = base64_decode(str_replace(' ', '+', $matches[2]));
            if ($data !== false && !empty($data)) {
                $fileName = $prefix . '_' . uniqid() . '_' . time() . '.' . $ext;
                return $this->writeBinaryFile($folder, $fileName, $data);
            }
        }

        return $imageString;
    }

    /**
     * Sube un archivo de imagen desde el cliente directamente a la carpeta del proyecto.
     */
    public function uploadMedia(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $fileName = 'media_' . time() . '_' . Str::random(8) . '.' . $extension;

            $binaryData = file_get_contents($file->getRealPath());
            $relativePath = $this->writeBinaryFile('media', $fileName, $binaryData);
            $fullUrl = asset($relativePath);

            return response()->json([
                'success' => true,
                'url' => $fullUrl,
                'path' => $relativePath,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Archivo no válido o corrupto'], 400);
    }

    /**
     * Obtiene la lista de imágenes guardadas en la biblioteca de medios del proyecto.
     */
    public function getMedia(): JsonResponse
    {
        $defaultMedia = [
            'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=600&q=80'
        ];

        $uploadedFiles = [];
        $mediaDirs = [
            public_path('storage/media'),
            storage_path('app/public/media')
        ];

        foreach ($mediaDirs as $dir) {
            if (file_exists($dir)) {
                $files = glob($dir . '/*.{jpg,jpeg,png,gif,webp,svg,JPG,PNG,JPEG}', GLOB_BRACE);
                if (is_array($files)) {
                    usort($files, function ($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });

                    foreach ($files as $file) {
                        $basename = basename($file);
                        $url = asset('storage/media/' . $basename);
                        if (!in_array($url, $uploadedFiles)) {
                            $uploadedFiles[] = $url;
                        }
                    }
                }
            }
        }

        $allMedia = array_values(array_unique(array_merge($uploadedFiles, $defaultMedia)));

        return response()->json([
            'success' => true,
            'media' => $allMedia,
        ]);
    }

    /**
     * Elimina una imagen de la biblioteca de medios.
     */
    public function deleteMedia(Request $request): JsonResponse
    {
        $url = $request->input('url');
        if (!empty($url)) {
            $filename = basename($url);
            $storageFile = storage_path('app/public/media/' . $filename);
            $publicFile = public_path('storage/media/' . $filename);

            if (file_exists($storageFile)) {
                @unlink($storageFile);
            }
            if (file_exists($publicFile)) {
                @unlink($publicFile);
            }
        }

        return response()->json(['success' => true]);
    }
}
