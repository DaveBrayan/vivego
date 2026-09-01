<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\TicketPurchaseMail;
use App\Models\Event;
use App\Models\Setting;
use App\Models\TicketSale;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BoxOfficeController extends Controller
{
    /**
     * Muestra la lista de eventos disponibles para Taquilla y Ventas.
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

        // Obtener eventos con sus ventas acumuladas
        $dbEvents = Event::with(['template', 'sales'])->orderBy('id', 'desc')->get();

        $events = [];
        $globalTotalRevenue = 0;
        $globalTicketsSold = 0;
        $globalTotalCapacity = 0;

        foreach ($dbEvents as $ev) {
            $zones = $ev->zones ?? [];
            $currentAvailableCapacity = (int) array_sum(array_column($zones, 'capacity'));
            $minPrice = count($zones) > 0 ? min(array_column($zones, 'price')) : 50;

            // Calcular ventas reales desde la tabla ticket_sales
            $salesCount = $ev->sales ? (int) $ev->sales->sum('quantity') : (int) TicketSale::where('event_id', $ev->id)->sum('quantity');
            $salesRevenue = $ev->sales ? (float) $ev->sales->sum('total_amount') : (float) TicketSale::where('event_id', $ev->id)->sum('total_amount');

            // El aforo total inicial es el stock disponible actual más las entradas ya vendidas
            $totalCapacity = $currentAvailableCapacity + $salesCount;
            $remainingStock = $currentAvailableCapacity;

            $globalTotalRevenue += $salesRevenue;
            $globalTicketsSold += $salesCount;
            $globalTotalCapacity += $totalCapacity;

            $capacityPercentage = $totalCapacity > 0 ? min(100, round(($salesCount / $totalCapacity) * 100, 1)) : 0;

            // Formatear fecha
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
                'tickets_sold' => $salesCount,
                'total_capacity' => $totalCapacity > 0 ? $totalCapacity : 60,
                'remaining_stock' => $remainingStock,
                'capacity_percentage' => $capacityPercentage,
                'min_price' => 'S/ ' . number_format($minPrice, 2),
                'revenue_formatted' => 'S/ ' . number_format($salesRevenue, 2),
                'revenue_raw' => $salesRevenue,
                'status' => $ev->status ?? 'Publicado',
                'status_class' => $ev->status === 'Agotado' ? 'badge-red' : 'badge-green',
                'sales_type' => $ev->sales_type ?? 'fisica',
                'zones' => $zones,
                'sales_count' => $ev->sales ? $ev->sales->count() : TicketSale::where('event_id', $ev->id)->count(),
            ];
        }

        // Si aún no hay eventos en BD, agregar inicial de muestra
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
                    'remaining_stock' => 800,
                    'capacity_percentage' => 0,
                    'min_price' => 'S/ 55.50',
                    'revenue_formatted' => 'S/ 0.00',
                    'revenue_raw' => 0,
                    'status' => 'Publicado',
                    'status_class' => 'badge-green',
                    'sales_type' => 'fisica',
                    'zones' => [
                        ['name' => 'BOX PLATINUM INDIVIDUAL', 'price' => 150.00, 'capacity' => 10],
                        ['name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 20],
                        ['name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 30]
                    ],
                    'sales_count' => 0,
                ]
            ];
            $globalTotalCapacity = 60;
        }

        $kpis = [
            'total_revenue' => 'S/ ' . number_format($globalTotalRevenue, 2),
            'tickets_sold' => $globalTicketsSold,
            'total_capacity' => $globalTotalCapacity,
            'active_events' => count($events),
            'physical_count' => count(array_filter($events, fn($e) => ($e['sales_type'] ?? 'fisica') === 'fisica')),
            'virtual_count' => count(array_filter($events, fn($e) => ($e['sales_type'] ?? 'fisica') === 'virtual')),
        ];

        return view('web.box_office', compact('events', 'kpis', 'settings', 'organizer'));
    }

    /**
     * Muestra la pantalla POS / Gestión de Ventas de un evento específico.
     */
    public function manage($id): View
    {
        $settings = Setting::current();

        $organizer = [
            'name' => 'Christian Gómez',
            'company' => 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            'role' => 'Organizador Principal',
            'status' => 'Verificado Pro',
        ];

        $event = Event::with(['template', 'sales' => function($q) {
            $q->orderBy('id', 'desc');
        }])->find($id);

        if (!$event) {
            // Fallback para mock si no existe en BD
            $event = (object) [
                'id' => $id,
                'title' => 'GRUPO 5 - NOCHE DE ORO 50 ANIVERSARIO',
                'slug' => 'grupo-5-noche-de-oro',
                'category_name' => 'Conciertos',
                'company_name' => 'PRODUCCIONES VIVE GO S.A.C.',
                'banner_image' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                'event_date' => '2026-11-15',
                'event_time' => '20:00',
                'venue_name' => 'Estadio Nacional de Lima',
                'address' => 'Lima',
                'sales_type' => 'fisica',
                'status' => 'Publicado',
                'zones' => [
                    ['name' => 'BOX PLATINUM INDIVIDUAL', 'price' => 150.00, 'capacity' => 10],
                    ['name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 20],
                    ['name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 30]
                ],
                'sales' => collect([]),
            ];
        }

        $sales = $event->sales ?? collect([]);
        $totalRevenue = $sales->sum('total_amount');
        $cashRevenue = $sales->where('payment_method', 'Efectivo')->sum('total_amount');
        $digitalRevenue = $totalRevenue - $cashRevenue;
        $ticketsSold = $sales->sum('quantity');

        $zones = is_array($event->zones)
            ? $event->zones
            : (is_string($event->zones) ? json_decode($event->zones, true) : []);

        if (empty($zones)) {
            $zones = [
                ['name' => 'BOX PLATINUM INDIVIDUAL', 'price' => 150.00, 'capacity' => 10],
                ['name' => 'ZONA VIP STAND UP', 'price' => 95.00, 'capacity' => 20],
                ['name' => 'ZONA GENERAL', 'price' => 55.50, 'capacity' => 30]
            ];
        }

        $courtesySettings = is_array($event->courtesy_settings)
            ? $event->courtesy_settings
            : (json_decode($event->courtesy_settings ?? '[]', true) ?? []);

        $courtesyEnabledGlobally = !empty($courtesySettings['enabled']);
        $courtesyZonesConfig = $courtesySettings['zones'] ?? [];
        $courtesyZoneConfigMap = [];
        if (is_array($courtesyZonesConfig)) {
            foreach ($courtesyZonesConfig as $cz) {
                if (!empty($cz['name'])) {
                    $courtesyZoneConfigMap[$cz['name']] = $cz;
                }
            }
        }

        $courtesySales = $sales->filter(fn($s) => in_array($s->payment_method, ['Cortesía', 'cortesia']));

        // Calcular stock por cada zona en base a ventas realizadas
        $zonesWithStats = [];
        foreach ($zones as $z) {
            $zName = $z['name'] ?? 'General';
            $zAvail = (int) ($z['capacity'] ?? 0);
            $zPrice = (float) ($z['price'] ?? 0);
            $zSold = (int) $sales->where('zone_name', $zName)->sum('quantity');
            $zTotalCap = $zAvail + $zSold;

            $zCourtesySold = (int) $courtesySales->where('zone_name', $zName)->sum('quantity');
            $hasCustomCourtesyZones = count($courtesyZoneConfigMap) > 0;
            $zCourtesyConfig = $courtesyZoneConfigMap[$zName] ?? null;

            $zCourtesyEnabled = $hasCustomCourtesyZones
                ? (!empty($zCourtesyConfig['enabled']))
                : $courtesyEnabledGlobally;

            $zCourtesyMaxStock = ($zCourtesyConfig && isset($zCourtesyConfig['stock']) && $zCourtesyConfig['stock'] !== '' && $zCourtesyConfig['stock'] !== null)
                ? (int) $zCourtesyConfig['stock']
                : null;

            $zCourtesyAvailable = $zCourtesyMaxStock !== null
                ? min($zAvail, max(0, $zCourtesyMaxStock - $zCourtesySold))
                : $zAvail;

            $zonesWithStats[] = [
                'name' => $zName,
                'price' => $zPrice,
                'capacity' => $zTotalCap,
                'sold' => $zSold,
                'available' => $zAvail,
                'percentage' => $zTotalCap > 0 ? min(100, round(($zSold / $zTotalCap) * 100)) : 0,
                'courtesy_enabled' => $zCourtesyEnabled,
                'courtesy_max_stock' => $zCourtesyMaxStock,
                'courtesy_sold' => $zCourtesySold,
                'courtesy_available' => $zCourtesyAvailable,
            ];
        }

        $totalCapacity = array_sum(array_column($zonesWithStats, 'capacity'));
        $remainingStock = array_sum(array_column($zonesWithStats, 'available'));

        $metrics = [
            'total_revenue' => 'S/ ' . number_format($totalRevenue, 2),
            'cash_revenue' => 'S/ ' . number_format($cashRevenue, 2),
            'digital_revenue' => 'S/ ' . number_format($digitalRevenue, 2),
            'tickets_sold' => $ticketsSold,
            'total_capacity' => $totalCapacity,
            'remaining_stock' => $remainingStock,
            'sales_count' => $sales->count(),
        ];

        // Obtener lista de clientes registrados para autocompletado inteligente en Taquilla / Cortesías
        try {
            $existingUsers = \App\Models\User::select('id', 'name', 'dni', 'email', 'phone')
                ->where(function ($q) {
                    $q->whereNull('role')->orWhere('role', '!=', 'admin');
                })
                ->orderBy('name', 'asc')
                ->limit(500)
                ->get();
        } catch (\Throwable $e) {
            $existingUsers = collect([]);
        }

        $allClients = $existingUsers->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'dni' => $u->dni ?? '',
            'email' => $u->email ?? '',
            'phone' => $u->phone ?? '',
        ]);

        // Agregar también compradores previos de ventas si no están en $existingUsers
        if ($sales && $sales->count() > 0) {
            $previousBuyers = $sales->map(function ($s) {
                $tData = is_array($s->tickets_data) ? $s->tickets_data : json_decode($s->tickets_data ?? '[]', true);
                return [
                    'id' => null,
                    'name' => $s->buyer_name,
                    'dni' => $s->buyer_dni ?? '',
                    'email' => $s->buyer_email ?? ($tData['buyer_email'] ?? ''),
                    'phone' => $s->buyer_phone ?? '',
                ];
            })->filter(function ($b) {
                return !empty($b['name'])
                    && !in_array(strtoupper(trim($b['name'])), ['PÚBLICO GENERAL', 'PUBLICO GENERAL', 'INVITADO DE CORTESÍA', 'INVITADO DE CORTESIA', 'INVITADO', 'INVITADO DE CORTESIA']);
            });

            $allClients = $allClients->concat($previousBuyers);
        }

        // Deduplicar clientes por DNI o por nombre
        $allClients = $allClients->unique(function ($item) {
            return !empty($item['dni']) && $item['dni'] !== '00000000' && $item['dni'] !== '11111111'
                ? $item['dni']
                : strtolower(trim($item['name']));
        })->values();

        return view('web.box_office_pos', compact('event', 'zonesWithStats', 'sales', 'metrics', 'settings', 'organizer', 'allClients'));
    }

    /**
     * Registra una nueva venta de entradas en Taquilla (POS), descuenta stock y emite el recibo térmico.
     */
    public function storeSale(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'buyer_name' => 'nullable|string|max:255',
            'buyer_dni' => 'nullable|string|max:20',
            'buyer_phone' => 'nullable|string|max:100',
            'buyer_email' => 'nullable|string|email|max:255',
            'zone_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:Efectivo,Culqi,culqi,Yape,Plin,Tarjeta,Transferencia,Cortesía,cortesia',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $buyerName = !empty(trim($validated['buyer_name'] ?? '')) ? trim($validated['buyer_name']) : 'CLIENTE VARIOS';
        $buyerDni = !empty(trim($validated['buyer_dni'] ?? '')) ? trim($validated['buyer_dni']) : '00000000';
        $buyerPhone = !empty(trim($validated['buyer_phone'] ?? '')) ? trim($validated['buyer_phone']) : '-';
        $buyerEmail = !empty(trim($validated['buyer_email'] ?? '')) ? trim($validated['buyer_email']) : null;

        $event = Event::findOrFail($id);
        $zones = is_array($event->zones) ? $event->zones : [];

        // Buscar la zona seleccionada
        $targetZoneIndex = null;
        $unitPrice = 0;
        $currentCapacity = 0;

        foreach ($zones as $idx => $z) {
            if (($z['name'] ?? '') === $validated['zone_name']) {
                $targetZoneIndex = $idx;
                $unitPrice = (float) ($z['price'] ?? 0);
                $currentCapacity = (int) ($z['capacity'] ?? 0);
                break;
            }
        }

        if ($targetZoneIndex === null) {
            return response()->json([
                'success' => false,
                'message' => 'La zona seleccionada no existe para este evento.',
            ], 422);
        }

        // Verificar si hay stock suficiente en la zona
        if ($currentCapacity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente en {$validated['zone_name']}. Disponibles: {$currentCapacity} entradas.",
            ], 422);
        }

        $isCourtesy = ($validated['payment_method'] === 'Cortesía' || $validated['payment_method'] === 'cortesia');

        $courtesySettings = is_array($event->courtesy_settings)
            ? $event->courtesy_settings
            : (json_decode($event->courtesy_settings ?? '[]', true) ?? []);

        if ($isCourtesy) {
            $courtesyZonesConfig = $courtesySettings['zones'] ?? [];
            if (!empty($courtesyZonesConfig) && is_array($courtesyZonesConfig)) {
                foreach ($courtesyZonesConfig as $cz) {
                    if (($cz['name'] ?? '') === $validated['zone_name']) {
                        if (isset($cz['enabled']) && !$cz['enabled']) {
                            return response()->json([
                                'success' => false,
                                'message' => "Las entradas de cortesía están deshabilitadas para el sector {$validated['zone_name']}.",
                            ], 422);
                        }
                        if (isset($cz['stock']) && $cz['stock'] !== null && $cz['stock'] !== '') {
                            $maxCourtesyStock = (int) $cz['stock'];
                            $alreadySoldCourtesy = TicketSale::where('event_id', $event->id)
                                ->whereIn('payment_method', ['Cortesía', 'cortesia'])
                                ->where('zone_name', $validated['zone_name'])
                                ->sum('quantity');
                            if (($alreadySoldCourtesy + $validated['quantity']) > $maxCourtesyStock) {
                                $remCourtesy = max(0, $maxCourtesyStock - $alreadySoldCourtesy);
                                return response()->json([
                                    'success' => false,
                                    'message' => "Cupo de cortesías agotado para {$validated['zone_name']}. Cupo asignado: {$maxCourtesyStock}, disponibles: {$remCourtesy}.",
                                ], 422);
                            }
                        }
                        break;
                    }
                }
            }

            $totalAmount = 0.00;
            $amountPaid = 0.00;
            $changeAmount = 0.00;
        } else {
            $totalAmount = round($unitPrice * $validated['quantity'], 2);
            $amountPaid = round((float) $validated['amount_paid'], 2);

            // Si el método es Efectivo, validar que el monto pagado sea mayor o igual al total
            if ($validated['payment_method'] === 'Efectivo' && $amountPaid < $totalAmount) {
                return response()->json([
                    'success' => false,
                    'message' => "El monto entregado (S/ " . number_format($amountPaid, 2) . ") es menor al total a pagar (S/ " . number_format($totalAmount, 2) . ").",
                ], 422);
            }

            // Si no es efectivo, el monto recibido se asume igual al total
            if ($validated['payment_method'] !== 'Efectivo') {
                $amountPaid = $totalAmount;
            }

            $changeAmount = max(0, round($amountPaid - $totalAmount, 2));
        }

        // Descontar stock de la zona y actualizar evento
        $zones[$targetZoneIndex]['capacity'] = max(0, $currentCapacity - $validated['quantity']);
        $event->zones = $zones;
        $event->save();

        // Generar correlativo de recibo único global (REC-000001)
        $lastSale = TicketSale::orderBy('id', 'desc')->first();
        $nextNum = $lastSale ? ($lastSale->id + 1) : 1;
        $receiptNumber = 'REC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);

        // Obtener la secuencia inicial de boletos para el evento
        $lastTicket = \App\Models\EventTicket::where('event_id', $event->id)->orderBy('id', 'desc')->first();
        $startSeq = $lastTicket ? ((int) preg_replace('/[^0-9]/', '', $lastTicket->ticket_number) + 1) : 1;

        // Generar códigos únicos e información para cada boleto individual
        $ticketsData = [];
        for ($i = 1; $i <= $validated['quantity']; $i++) {
            $currentSeq = $startSeq + ($i - 1);
            $ticketCode = 'TK-' . strtoupper(substr(Str::slug($event->title), 0, 3)) . '-' . str_pad($currentSeq, 5, '0', STR_PAD_LEFT);
            $validationHash = strtoupper(Str::random(10));

            // Hash encriptado de alta seguridad exclusivo para el scanner móvil del evento
            $encryptedToken = strtoupper(substr(hash_hmac('sha256', "VIVEGO_ENC_{$event->id}_{$receiptNumber}_{$validationHash}_{$i}", config('app.key', 'ViveGoSecretKey2026')), 0, 24));
            $qrPayload = "VGENC:{$encryptedToken}";

            $effectiveTicketPrice = $isCourtesy ? 0.00 : $unitPrice;

            $ticketsData[] = [
                'ticket_code' => $ticketCode,
                'ticket_number' => $currentSeq,
                'ticket_index' => $i,
                'validation_hash' => $validationHash,
                'qr_payload' => $qrPayload,
                'zone' => $validated['zone_name'],
                'price' => $effectiveTicketPrice,
                'buyer_name' => $buyerName,
                'buyer_dni' => $buyerDni,
                'buyer_phone' => $buyerPhone,
                'buyer_email' => $buyerEmail,
                'is_courtesy' => $isCourtesy,
            ];
        }

        $phoneFieldVal = $buyerPhone;
        if ($buyerEmail && $buyerPhone !== '-') {
            $phoneFieldVal = "{$buyerPhone} | {$buyerEmail}";
        } elseif ($buyerEmail && $buyerPhone === '-') {
            $phoneFieldVal = $buyerEmail;
        }

        // Crear registro en la tabla ticket_sales
        $sale = TicketSale::create([
            'event_id' => $event->id,
            'receipt_number' => $receiptNumber,
            'buyer_name' => $buyerName,
            'buyer_dni' => $buyerDni,
            'buyer_phone' => $phoneFieldVal,
            'zone_name' => $validated['zone_name'],
            'unit_price' => $isCourtesy ? 0.00 : $unitPrice,
            'quantity' => $validated['quantity'],
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'amount_paid' => $amountPaid,
            'change_amount' => $changeAmount,
            'tickets_data' => $ticketsData,
            'seller_name' => 'Taquilla Principal',
        ]);

        // Registrar cada boleto emitido individualmente en la tabla event_tickets
        foreach ($ticketsData as $tData) {
            \App\Models\EventTicket::create([
                'event_id' => $event->id,
                'ticket_sale_id' => $sale->id,
                'ticket_code' => $tData['ticket_code'],
                'ticket_number' => $tData['ticket_number'],
                'zone_name' => $tData['zone'],
                'unit_price' => $tData['price'],
                'qr_payload' => $tData['qr_payload'],
                'validation_hash' => $tData['validation_hash'],
                'buyer_name' => $tData['buyer_name'],
                'buyer_dni' => $tData['buyer_dni'],
                'source' => 'pos_sale',
                'is_used' => false,
            ]);
        }

        // Recalcular métricas en vivo para actualización dinámica
        $allSales = TicketSale::where('event_id', $event->id)->get();
        $totalRevenue = $allSales->sum('total_amount');
        $cashRevenue = $allSales->where('payment_method', 'Efectivo')->sum('total_amount');
        $digitalRevenue = $totalRevenue - $cashRevenue;
        $ticketsSold = $allSales->sum('quantity');

        $courtesySales = $allSales->filter(fn($s) => in_array($s->payment_method, ['Cortesía', 'cortesia']));
        $courtesyZonesConfig = $courtesySettings['zones'] ?? [];
        $courtesyZoneConfigMap = [];
        if (is_array($courtesyZonesConfig)) {
            foreach ($courtesyZonesConfig as $cz) {
                if (!empty($cz['name'])) {
                    $courtesyZoneConfigMap[$cz['name']] = $cz;
                }
            }
        }
        $courtesyEnabledGlobally = !empty($courtesySettings['enabled']);

        $zonesWithStats = [];
        foreach ($event->zones as $z) {
            $zName = $z['name'] ?? 'General';
            $zAvail = (int) ($z['capacity'] ?? 0);
            $zPrice = (float) ($z['price'] ?? 0);
            $zSold = (int) $allSales->where('zone_name', $zName)->sum('quantity');
            $zTotalCap = $zAvail + $zSold;

            $zCourtesySold = (int) $courtesySales->where('zone_name', $zName)->sum('quantity');
            $hasCustomCourtesyZones = count($courtesyZoneConfigMap) > 0;
            $zCourtesyConfig = $courtesyZoneConfigMap[$zName] ?? null;

            $zCourtesyEnabled = $hasCustomCourtesyZones
                ? (!empty($zCourtesyConfig['enabled']))
                : $courtesyEnabledGlobally;

            $zCourtesyMaxStock = ($zCourtesyConfig && isset($zCourtesyConfig['stock']) && $zCourtesyConfig['stock'] !== '' && $zCourtesyConfig['stock'] !== null)
                ? (int) $zCourtesyConfig['stock']
                : null;

            $zCourtesyAvailable = $zCourtesyMaxStock !== null
                ? min($zAvail, max(0, $zCourtesyMaxStock - $zCourtesySold))
                : $zAvail;

            $zonesWithStats[] = [
                'name' => $zName,
                'price' => $zPrice,
                'capacity' => $zTotalCap,
                'sold' => $zSold,
                'available' => $zAvail,
                'percentage' => $zTotalCap > 0 ? min(100, round(($zSold / $zTotalCap) * 100)) : 0,
                'courtesy_enabled' => $zCourtesyEnabled,
                'courtesy_max_stock' => $zCourtesyMaxStock,
                'courtesy_sold' => $zCourtesySold,
                'courtesy_available' => $zCourtesyAvailable,
            ];
        }

        // Envío automático de correo con el PDF si se proporcionó o detectó un email válido
        $pdfBase64 = $request->input('ticket_pdf_base64');
        $effectiveEmail = $buyerEmail;

        if (empty($effectiveEmail) && !empty($buyerDni) && $buyerDni !== '00000000') {
            $userFound = \App\Models\User::where('dni', $buyerDni)->first();
            if ($userFound && !empty($userFound->email)) {
                $effectiveEmail = $userFound->email;
            }
        }

        if (empty($effectiveEmail) && !empty($buyerName) && !in_array(strtoupper($buyerName), ['CLIENTE VARIOS', 'INVITADO DE CORTESÍA', 'INVITADO DE CORTESIA', 'INVITADO'])) {
            $userFound = \App\Models\User::where('name', $buyerName)->whereNotNull('email')->first();
            if ($userFound && !empty($userFound->email)) {
                $effectiveEmail = $userFound->email;
            }
        }

        $emailSent = false;
        if (!empty($effectiveEmail) && filter_var($effectiveEmail, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $effectiveEmail)->first();
            $tempPassword = null;
            $isNewUser = false;

            if (!$user) {
                try {
                    $tempPassword = Str::random(8);
                    $user = \App\Models\User::create([
                        'name' => ($buyerName !== 'CLIENTE VARIOS' && $buyerName !== 'INVITADO DE CORTESÍA' && $buyerName !== 'INVITADO DE CORTESIA') ? $buyerName : explode('@', $effectiveEmail)[0],
                        'email' => $effectiveEmail,
                        'dni' => $buyerDni !== '00000000' ? $buyerDni : null,
                        'phone' => $buyerPhone !== '-' ? $buyerPhone : null,
                        'password' => bcrypt($tempPassword),
                        'role' => 'client',
                    ]);
                    $isNewUser = true;
                } catch (\Throwable $ex) {
                    Log::warning("No se pudo crear usuario automático en Taquilla: " . $ex->getMessage());
                }
            }

            try {
                Mail::to($effectiveEmail)->send(new TicketPurchaseMail($sale, $tempPassword, $isNewUser, $pdfBase64));
                $emailSent = true;
                Log::info("Boleto oficial enviado automáticamente a {$effectiveEmail} tras emisión en Taquilla POS");
            } catch (\Throwable $e) {
                Log::warning("No se pudo enviar el correo tras registrar venta/cortesía en Taquilla: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Venta registrada con éxito en Taquilla!',
            'sale' => $sale,
            'email_sent' => $emailSent,
            'recipient' => $effectiveEmail,
            'metrics' => [
                'total_revenue' => 'S/ ' . number_format($totalRevenue, 2),
                'cash_revenue' => 'S/ ' . number_format($cashRevenue, 2),
                'digital_revenue' => 'S/ ' . number_format($digitalRevenue, 2),
                'tickets_sold' => $ticketsSold,
                'total_capacity' => $totalCapacity,
                'remaining_stock' => $remainingStock,
                'zones' => $zonesWithStats,
            ],
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'venue_name' => $event->venue_name,
                'address' => $event->address,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'zones' => $event->zones,
            ],
            'receipt' => [
                'receipt_number' => $receiptNumber,
                'created_at_formatted' => Carbon::now()->format('d/m/Y H:i:s'),
                'buyer_name' => $sale->buyer_name,
                'buyer_dni' => $sale->buyer_dni,
                'zone_name' => $sale->zone_name,
                'quantity' => $sale->quantity,
                'unit_price_formatted' => 'S/ ' . number_format($unitPrice, 2),
                'total_amount_formatted' => 'S/ ' . number_format($totalAmount, 2),
                'payment_method' => $sale->payment_method,
                'amount_paid_formatted' => 'S/ ' . number_format($amountPaid, 2),
                'change_amount_formatted' => 'S/ ' . number_format($changeAmount, 2),
                'tickets' => $ticketsData,
            ]
        ]);
    }

    /**
     * Elimina / Anula una venta de taquilla, revierte el stock del evento y elimina boletos asociados.
     */
    public function destroySale($id): JsonResponse
    {
        $sale = TicketSale::find($id);
        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'La venta especificada no existe.'
            ], 404);
        }

        $event = Event::find($sale->event_id);
        if ($event && is_array($event->zones)) {
            $zones = $event->zones;
            foreach ($zones as $idx => $z) {
                if (($z['name'] ?? '') === $sale->zone_name) {
                    $zones[$idx]['capacity'] = (int) ($z['capacity'] ?? 0) + (int) $sale->quantity;
                    break;
                }
            }
            $event->zones = $zones;
            $event->save();
        }

        // Eliminar boletos individuales asociados
        \App\Models\EventTicket::where('ticket_sale_id', $sale->id)->delete();

        // Eliminar la venta
        $sale->delete();

        return response()->json([
            'success' => true,
            'message' => '¡Entrada / venta eliminada correctamente y aforo restaurado!'
        ]);
    }

    /**
     * Enviar Entrada PDF oficial generada en Canva Studio por correo desde Taquilla POS.
     */
    public function emailTicketPdf(Request $request, TicketSale $sale): JsonResponse
    {
        $recipient = $request->input('email');

        if (empty($recipient) && !empty($sale->tickets_data)) {
            $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
            $recipient = $tData['customer_email'] ?? ($tData['buyer_email'] ?? ($tData['email'] ?? null));
        }

        if (empty($recipient) && !empty($sale->buyer_dni) && $sale->buyer_dni !== '00000000') {
            $user = \App\Models\User::where('dni', $sale->buyer_dni)->first();
            if ($user && !empty($user->email)) {
                $recipient = $user->email;
            }
        }

        if (empty($recipient) && !empty($sale->buyer_name)) {
            $user = \App\Models\User::where('name', $sale->buyer_name)->whereNotNull('email')->first();
            if ($user && !empty($user->email)) {
                $recipient = $user->email;
            }
        }

        if (empty($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un correo electrónico válido registrado para este boleto.'
            ], 422);
        }

        // Guardar en tickets_data si no estaba registrado
        $tData = is_array($sale->tickets_data) ? $sale->tickets_data : (json_decode($sale->tickets_data ?? '[]', true) ?: []);
        if (empty($tData['customer_email']) || $tData['customer_email'] !== $recipient) {
            $tData['customer_email'] = $recipient;
            $sale->tickets_data = $tData;
            $sale->save();
        }

        $pdfBase64 = $request->input('ticket_pdf_base64');

        try {
            Mail::to($recipient)->send(new TicketPurchaseMail($sale, null, false, $pdfBase64));

            return response()->json([
                'success' => true,
                'message' => "¡Boleto oficial enviado exitosamente a {$recipient}!"
            ]);
        } catch (\Throwable $e) {
            Log::error('Error enviando boleto por correo desde Taquilla POS: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }
}

