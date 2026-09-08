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
            $totalCapacity = (int) array_sum(array_column($zones, 'capacity'));
            $minPrice = count($zones) > 0 ? min(array_column($zones, 'price')) : 50;

            // Calcular ventas reales desde la tabla ticket_sales
            $salesCount = $ev->sales ? (int) $ev->sales->sum('quantity') : (int) TicketSale::where('event_id', $ev->id)->sum('quantity');
            $salesRevenue = $ev->sales ? (float) $ev->sales->sum('total_amount') : (float) TicketSale::where('event_id', $ev->id)->sum('total_amount');

            // El aforo total es la capacidad configurada, y el stock restante se calcula restando las ventas
            $remainingStock = max(0, $totalCapacity - $salesCount);

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
            'physical_count' => count(array_filter($events, fn($e) => in_array($e['sales_type'] ?? 'fisica', ['fisica', 'ambos']))),
            'virtual_count' => count(array_filter($events, fn($e) => in_array($e['sales_type'] ?? 'fisica', ['virtual', 'ambos']))),
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

        $sales = $event->sales()->with('eventTickets')->latest()->get();
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

        $zonesWithStats = $this->buildZonesWithStats($event, $sales);

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
            'selected_seats' => 'nullable|array',
            'selected_seats.*' => 'string|max:50',
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

        $splitSettings = is_array($event->quota_split_settings) 
            ? $event->quota_split_settings 
            : (json_decode($event->quota_split_settings ?? '[]', true) ?: []);
        $isSplitActive = !empty($splitSettings['enabled']);

        if ($isCourtesy) {
            $courtesyZonesConfig = $courtesySettings['zones'] ?? [];
            $targetCz = null;
            if (!empty($courtesyZonesConfig) && is_array($courtesyZonesConfig)) {
                foreach ($courtesyZonesConfig as $cz) {
                    if (($cz['name'] ?? '') === $validated['zone_name']) {
                        $targetCz = $cz;
                        break;
                    }
                }
            }

            $cleanZName = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $validated['zone_name'])));
            $targetSplitZone = null;
            if (!empty($splitSettings['zones']) && is_array($splitSettings['zones'])) {
                foreach ($splitSettings['zones'] as $sz) {
                    if (!empty($sz['name']) && strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $sz['name']))) === $cleanZName) {
                        $targetSplitZone = $sz;
                        break;
                    }
                }
            }

            if ($targetCz && isset($targetCz['enabled']) && !$targetCz['enabled']) {
                return response()->json([
                    'success' => false,
                    'message' => "Las entradas de cortesía están deshabilitadas para el sector {$validated['zone_name']}.",
                ], 422);
            }

            // En Taquilla POS, las cortesías emitidas son DIGITALES (las físicas se imprimen en plancha)
            $digitalCourtesyStock = null;
            if ($targetSplitZone && isset($targetSplitZone['courtesy_virtual']) && is_numeric($targetSplitZone['courtesy_virtual'])) {
                $digitalCourtesyStock = (int) $targetSplitZone['courtesy_virtual'];
            } elseif ($targetCz && isset($targetCz['virtual_stock']) && is_numeric($targetCz['virtual_stock'])) {
                $digitalCourtesyStock = (int) $targetCz['virtual_stock'];
            } elseif ($targetCz && isset($targetCz['stock']) && is_numeric($targetCz['stock']) && !$isSplitActive) {
                $digitalCourtesyStock = (int) $targetCz['stock'];
            }

            if ($digitalCourtesyStock !== null) {
                $soldDigitalCourtesy = TicketSale::where('event_id', $event->id)
                    ->whereIn('payment_method', ['Cortesía', 'cortesia'])
                    ->where(function ($q) use ($validated, $cleanZName) {
                        $q->where('zone_name', $validated['zone_name'])
                          ->orWhere('zone_name', 'LIKE', $cleanZName . '%');
                    })
                    ->sum('quantity');

                if (($soldDigitalCourtesy + $validated['quantity']) > $digitalCourtesyStock) {
                    $remCourtesy = max(0, $digitalCourtesyStock - $soldDigitalCourtesy);
                    return response()->json([
                        'success' => false,
                        'message' => "Cupo de cortesías digitales agotado para {$validated['zone_name']}. Cupo asignado digital: {$digitalCourtesyStock}, disponibles: {$remCourtesy}.",
                    ], 422);
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

        // Marcar butacas seleccionadas como ocupadas si corresponde
        $selectedSeats = is_array($validated['selected_seats'] ?? null) ? array_values($validated['selected_seats']) : [];
        if (!empty($selectedSeats) && !empty($zones[$targetZoneIndex]['seats']) && is_array($zones[$targetZoneIndex]['seats'])) {
            foreach ($zones[$targetZoneIndex]['seats'] as &$seatItem) {
                $sCode = formatShortSeatCode($seatItem);
                if (in_array($sCode, $selectedSeats)) {
                    $seatItem['status'] = 'occupied';
                }
            }
            unset($seatItem);
        }

        $event->zones = $zones;
        $event->save();

        // Generar correlativo de recibo único global (REC-000001)
        $lastSale = TicketSale::orderBy('id', 'desc')->first();
        $nextNum = $lastSale ? ($lastSale->id + 1) : 1;
        $receiptNumber = 'REC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);

        // Secuencias de fallback separadas por tipo de boleto
        $startSeqPhysical = ((int) \App\Models\EventTicket::where('event_id', $event->id)->where('ticket_type', 'fisica')->max('ticket_number') ?: 0) + 1;
        $startSeqDigital = ((int) \App\Models\EventTicket::where('event_id', $event->id)->where('ticket_type', 'digital')->max('ticket_number') ?: 0) + 1;
        $startSeqCourtesy = ((int) \App\Models\EventTicket::where('event_id', $event->id)->where(function($q) {
            $q->where('ticket_type', 'cortesia_digital')
              ->orWhere(function($sq) {
                  $sq->where('ticket_type', 'cortesia')->where('source', 'web_checkout');
              });
        })->max('ticket_number') ?: 0) + 1;

        // Generar códigos e información para cada boleto, reutilizando boletos físicos ya pre-impresos
        $ticketsData = [];
        $matchedPhysicalTickets = [];
        $cleanBaseZone = preg_replace('/\s*\([^)]*\)$/', '', trim($validated['zone_name']));

        $saleMode = $request->input('sale_mode', 'digital'); // 'digital' o 'fisica'
        $isPhysicalSale = ($saleMode === 'fisica');

        $splitSettings = is_array($event->quota_split_settings) 
            ? $event->quota_split_settings 
            : (json_decode($event->quota_split_settings ?? '[]', true) ?: []);
        $isSplitActive = !empty($splitSettings['enabled']);

        if ($isCourtesy) {
            $requiredTicketType = 'cortesia_digital';
        } elseif ($isPhysicalSale) {
            $requiredTicketType = 'fisica';
        } else {
            $requiredTicketType = 'digital';
        }

        for ($i = 1; $i <= $validated['quantity']; $i++) {
            $effectiveTicketPrice = $isCourtesy ? 0.00 : $unitPrice;
            $seatCode = !empty($selectedSeats[$i - 1]) ? formatShortSeatCode($selectedSeats[$i - 1]) : null;
            $zoneWithSeat = $seatCode ? formatZoneWithSeat($validated['zone_name'], $seatCode) : $validated['zone_name'];

            $physicalTicket = null;
            $courtesyTicket = null;
            $digitalTicket = null;

            if ($isPhysicalSale) {
                // VENTA FÍSICA: Usar boletos físicos pre-generados para la plancha
                if ($seatCode) {
                    $seatDigits = preg_replace('/[^0-9]/', '', $seatCode);
                    $seatLetter = preg_replace('/[^A-Za-z]/', '', $seatCode);

                    $physicalTicket = \App\Models\EventTicket::where('event_id', $event->id)
                        ->where(function($q) {
                            $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                        })
                        ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                        ->where('ticket_type', 'fisica')
                        ->where(function ($q) use ($zoneWithSeat, $seatCode, $seatLetter, $seatDigits) {
                            $q->where('zone_name', $zoneWithSeat)
                              ->orWhere('zone_name', 'LIKE', "%({$seatCode})%")
                              ->orWhere('zone_name', 'LIKE', "%({$seatLetter}-{$seatDigits})%")
                              ->orWhere('zone_name', 'LIKE', "%({$seatLetter} {$seatDigits})%");
                        })
                        ->orderBy('id', 'asc')
                        ->first();
                } else {
                    $physicalTicket = \App\Models\EventTicket::where('event_id', $event->id)
                        ->where(function($q) {
                            $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                        })
                        ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                        ->where('ticket_type', 'fisica')
                        ->where(function ($q) use ($validated, $cleanBaseZone) {
                            $q->where('zone_name', $validated['zone_name'])
                              ->orWhere('zone_name', 'LIKE', $cleanBaseZone . '%');
                        })
                        ->orderBy('ticket_number', 'asc')
                        ->orderBy('id', 'asc')
                        ->first();
                }

                if ($physicalTicket) {
                    $ticketCode = $physicalTicket->ticket_code;
                    $currentSeq = $physicalTicket->ticket_number;
                    $validationHash = $physicalTicket->validation_hash;
                    $qrPayload = $physicalTicket->qr_payload;
                    if (!empty($physicalTicket->zone_name)) {
                        $zoneWithSeat = $physicalTicket->zone_name;
                    }
                    $matchedPhysicalTickets[$physicalTicket->id] = $physicalTicket;
                } else {
                    $currentSeq = $startSeqPhysical + ($i - 1);
                    $ticketCode = 'N° ' . str_pad($currentSeq, 5, '0', STR_PAD_LEFT);
                    $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_phys_', true) . $event->id . $receiptNumber . $currentSeq), 0, 8));
                    $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$currentSeq}|HASH-{$validationHash}";
                }
            } elseif ($isCourtesy) {
                // CORTESÍA DIGITAL: Consumir boletos pre-generados de tipo 'cortesia_digital' con su propio correlativo
                $courtesyTicket = \App\Models\EventTicket::where('event_id', $event->id)
                    ->where(function($q) {
                        $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                    })
                    ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                    ->where(function($q) {
                        $q->where('ticket_type', 'cortesia_digital')
                          ->orWhere(function($sq) {
                              $sq->where('ticket_type', 'cortesia')->where('source', 'web_checkout');
                          });
                    })
                    ->where(function ($q) use ($validated, $cleanBaseZone) {
                        $q->where('zone_name', $validated['zone_name'])
                          ->orWhere('zone_name', 'LIKE', $cleanBaseZone . '%')
                          ->orWhere('zone_name', 'LIKE', '%CORTESÍA - ' . $cleanBaseZone . '%')
                          ->orWhere('zone_name', 'LIKE', '%CORTESIA - ' . $cleanBaseZone . '%');
                    })
                    ->orderBy('ticket_number', 'asc')
                    ->orderBy('id', 'asc')
                    ->first();

                if ($courtesyTicket) {
                    $ticketCode = $courtesyTicket->ticket_code;
                    $currentSeq = $courtesyTicket->ticket_number;
                    $validationHash = $courtesyTicket->validation_hash;
                    $qrPayload = $courtesyTicket->qr_payload;
                    if (!empty($courtesyTicket->zone_name)) {
                        $zoneWithSeat = $courtesyTicket->zone_name;
                    }
                    $matchedPhysicalTickets[$courtesyTicket->id] = $courtesyTicket;
                } else {
                    $currentSeq = $startSeqCourtesy + ($i - 1);
                    $ticketCode = 'N° ' . str_pad($currentSeq, 5, '0', STR_PAD_LEFT);
                    $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_pos_cort_', true) . $event->id . $receiptNumber . $currentSeq), 0, 8));
                    $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$currentSeq}|HASH-{$validationHash}";
                }
            } else {
                // VENTA DIGITAL POS REGULAR: Generar códigos digitales o consumir boletos digitales
                $digitalTicket = null;
                if ($seatCode) {
                    $seatDigits = preg_replace('/[^0-9]/', '', $seatCode);
                    $seatLetter = preg_replace('/[^A-Za-z]/', '', $seatCode);

                    $digitalTicket = \App\Models\EventTicket::where('event_id', $event->id)
                        ->where(function($q) {
                            $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                        })
                        ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                        ->where('ticket_type', 'digital')
                        ->where(function ($q) use ($zoneWithSeat, $seatCode, $seatLetter, $seatDigits) {
                            $q->where('zone_name', $zoneWithSeat)
                              ->orWhere('zone_name', 'LIKE', "%({$seatCode})%")
                              ->orWhere('zone_name', 'LIKE', "%({$seatLetter}-{$seatDigits})%")
                              ->orWhere('zone_name', 'LIKE', "%({$seatLetter} {$seatDigits})%");
                        })
                        ->orderBy('id', 'asc')
                        ->first();
                } else {
                    // Zona digital general: consumir boleto digital no numerado si existe pre-generado
                    $digitalTicket = \App\Models\EventTicket::where('event_id', $event->id)
                        ->where(function($q) {
                            $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                        })
                        ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                        ->where('ticket_type', 'digital')
                        ->where(function ($q) use ($validated, $cleanBaseZone) {
                            $q->where('zone_name', $validated['zone_name'])
                              ->orWhere('zone_name', 'LIKE', $cleanBaseZone . '%');
                        })
                        ->orderBy('ticket_number', 'asc')
                        ->orderBy('id', 'asc')
                        ->first();
                }

                if ($digitalTicket) {
                    $ticketCode = $digitalTicket->ticket_code;
                    $currentSeq = $digitalTicket->ticket_number;
                    $validationHash = $digitalTicket->validation_hash;
                    $qrPayload = $digitalTicket->qr_payload;
                    if (!empty($digitalTicket->zone_name)) {
                        $zoneWithSeat = $digitalTicket->zone_name;
                    }
                    $matchedPhysicalTickets[$digitalTicket->id] = $digitalTicket;
                } else {
                    $currentSeq = $startSeqDigital + ($i - 1);
                    $ticketCode = 'N° ' . str_pad($currentSeq, 5, '0', STR_PAD_LEFT);
                    $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_pos_dig_', true) . $event->id . $receiptNumber . $currentSeq), 0, 8));
                    $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$currentSeq}|HASH-{$validationHash}";
                }
            }

            $ticketsData[] = [
                'ticket_code' => $ticketCode,
                'ticket_number' => $currentSeq,
                'ticket_index' => $i,
                'validation_hash' => $validationHash,
                'qr_payload' => $qrPayload,
                'zone' => $zoneWithSeat,
                'seat' => $seatCode,
                'seat_label' => $seatCode,
                'price' => $effectiveTicketPrice,
                'buyer_name' => $buyerName,
                'buyer_dni' => $buyerDni,
                'buyer_phone' => $buyerPhone,
                'buyer_email' => $buyerEmail,
                'is_courtesy' => $isCourtesy,
                'event_ticket_id' => $physicalTicket ? $physicalTicket->id : ($courtesyTicket ?? null ? $courtesyTicket->id : ($digitalTicket ?? null ? $digitalTicket->id : null)),
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
            'sale_type' => $isPhysicalSale ? 'fisica' : 'digital',
            'amount_paid' => $amountPaid,
            'change_amount' => $changeAmount,
            'tickets_data' => $ticketsData,
            'seller_name' => 'Taquilla Principal',
        ]);

        // Vincular los boletos físicos existentes a esta venta o registrar los nuevos
        foreach ($ticketsData as $tData) {
            if (!empty($tData['event_ticket_id']) && isset($matchedPhysicalTickets[$tData['event_ticket_id']])) {
                $pTicket = $matchedPhysicalTickets[$tData['event_ticket_id']];
                $pTicket->update([
                    'ticket_sale_id' => $sale->id,
                    'buyer_name' => $tData['buyer_name'],
                    'buyer_dni' => $tData['buyer_dni'],
                    'unit_price' => $tData['price'],
                    'ticket_type' => $requiredTicketType ?: ($isCourtesy ? 'cortesia' : ($isPhysicalSale ? 'fisica' : 'digital')),
                    'source' => $isPhysicalSale ? 'pos_physical' : ($isCourtesy ? 'pos_courtesy' : 'pos_sale'),
                    'status' => 'valid',
                    'is_used' => false,
                ]);
            } else {
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
                    'source' => $isPhysicalSale ? 'pos_physical' : ($isCourtesy ? 'pos_courtesy' : 'pos_sale'),
                    'ticket_type' => $requiredTicketType ?: ($isCourtesy ? 'cortesia' : ($isPhysicalSale ? 'fisica' : 'digital')),
                    'is_used' => false,
                    'status' => 'valid',
                ]);
            }
        }

        // Recalcular métricas en vivo para actualización dinámica
        $allSales = TicketSale::where('event_id', $event->id)->get();
        $totalRevenue = $allSales->sum('total_amount');
        $cashRevenue = $allSales->where('payment_method', 'Efectivo')->sum('total_amount');
        $digitalRevenue = $totalRevenue - $cashRevenue;
        $ticketsSold = $allSales->sum('quantity');

        $zonesWithStats = $this->buildZonesWithStats($event, $allSales);

        $totalCapacity = (int) array_sum(array_column($zonesWithStats, 'capacity'));
        $remainingStock = (int) array_sum(array_column($zonesWithStats, 'available'));

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
        if (!empty($effectiveEmail) && filter_var($effectiveEmail, FILTER_VALIDATE_EMAIL) && !$isPhysicalSale) {
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

            // Registrar y enviar a través de EmailLogService (auditoría en Registro de Correos)
            $mailResult = \App\Services\EmailLogService::sendTicketPurchaseMail(
                $sale,
                $tempPassword,
                $isNewUser,
                $pdfBase64,
                $effectiveEmail,
                $isCourtesy ? 'courtesy' : 'pos_sale'
            );
            $emailSent = $mailResult['success'];
        }

        // Cargar boletos físicos y relación de evento para el frontend
        $sale->loadMissing(['eventTickets', 'event']);

        return response()->json([
            'success' => true,
            'message' => $isPhysicalSale ? '¡Venta física registrada con éxito!' : '¡Venta registrada con éxito en Taquilla!',
            'sale' => $sale,
            'sale_mode' => $saleMode,
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
            $tDataList = is_array($sale->tickets_data) ? $sale->tickets_data : (json_decode($sale->tickets_data ?? '[]', true) ?: []);
            $releasedSeats = [];
            foreach ($tDataList as $td) {
                if (!empty($td['seat'])) {
                    $releasedSeats[] = formatShortSeatCode($td['seat']);
                }
            }

            // Si hay butacas numeradas liberadas, restaurar su estado a disponible sin alterar el aforo base
            if (!empty($releasedSeats)) {
                $hasSeatChanges = false;
                foreach ($zones as $idx => $z) {
                    if (!empty($zones[$idx]['seats']) && is_array($zones[$idx]['seats'])) {
                        foreach ($zones[$idx]['seats'] as &$sItem) {
                            $sCode = formatShortSeatCode($sItem);
                            if (in_array($sCode, $releasedSeats)) {
                                $sItem['status'] = 'available';
                                $hasSeatChanges = true;
                            }
                        }
                        unset($sItem);
                    }
                }
                if ($hasSeatChanges) {
                    $event->zones = $zones;
                    $event->save();
                }
            }
        }

        // Liberar el boleto en event_tickets: NUNCA se borra el registro de la entrada,
        // solo se desvincula de la venta (ticket_sale_id = null) y se resetean los datos del comprador
        // para que la entrada quede completamente libre y lista para registrarse en otra venta.
        \App\Models\EventTicket::where('ticket_sale_id', $sale->id)
            ->update([
                'ticket_sale_id' => null,
                'buyer_name' => 'Talonario Físico / Taquilla',
                'buyer_dni' => '00000000',
                'status' => 'valid',
                'is_used' => false,
            ]);

        // Eliminar el registro de la venta en ticket_sales
        $sale->delete();

        return response()->json([
            'success' => true,
            'message' => '¡Venta eliminada correctamente! La entrada quedó libre para poder registrarse en otra venta.'
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
        $sale->loadMissing(['eventTickets', 'event']);

        $mailResult = \App\Services\EmailLogService::sendTicketPurchaseMail(
            $sale,
            null,
            false,
            $pdfBase64,
            $recipient,
            'pos_resend'
        );

        if ($mailResult['success']) {
            return response()->json([
                'success' => true,
                'message' => "¡Boleto oficial enviado exitosamente a {$recipient}!"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $mailResult['message']
            ], 500);
        }
    }

    /**
     * Construye las estadísticas detalladas de stock por zona (desglose físico, digital y cortesía).
     */
    protected function buildZonesWithStats(Event $event, $sales = null): array
    {
        if ($sales === null) {
            $sales = $event->sales()->with('eventTickets')->latest()->get();
        }

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

        // Configuración de división de aforo físico vs digital
        $splitSettings = is_array($event->quota_split_settings)
            ? $event->quota_split_settings
            : (json_decode($event->quota_split_settings ?? '[]', true) ?: []);
        $isSplitActive = !empty($splitSettings['enabled']);

        $zoneSplitMap = [];
        if (!empty($splitSettings['zones']) && is_array($splitSettings['zones'])) {
            foreach ($splitSettings['zones'] as $sz) {
                if (!empty($sz['name'])) {
                    $clean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $sz['name'])));
                    $zoneSplitMap[$clean] = $sz;
                }
            }
        }

        // Configuración de cortesías
        $courtesySettings = is_array($event->courtesy_settings)
            ? $event->courtesy_settings
            : (json_decode($event->courtesy_settings ?? '[]', true) ?? []);

        $courtesyEnabledGlobally = !empty($courtesySettings['enabled']) || !empty($splitSettings['courtesy_global_enabled']);
        $courtesyZonesConfig = $courtesySettings['zones'] ?? [];
        $courtesyZoneConfigMap = [];
        if (is_array($courtesyZonesConfig)) {
            foreach ($courtesyZonesConfig as $cz) {
                if (!empty($cz['name'])) {
                    $cleanCz = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $cz['name'])));
                    $courtesyZoneConfigMap[$cleanCz] = $cz;
                    $courtesyZoneConfigMap[$cz['name']] = $cz;
                }
            }
        }

        $courtesySales = $sales->filter(fn($s) => in_array($s->payment_method, ['Cortesía', 'cortesia']));

        // Obtener tickets vendidos en DB para desglose exacto
        $soldTickets = \App\Models\EventTicket::where('event_id', $event->id)
            ->where(function ($q) {
                $q->whereNotNull('ticket_sale_id')->where('ticket_sale_id', '>', 0);
            })
            ->get();

        $zonesWithStats = [];
        foreach ($zones as $z) {
            $zName = $z['name'] ?? 'General';
            $cleanZone = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $zName)));
            $zTotalCap = (int) ($z['capacity'] ?? 0);
            $zPrice = (float) ($z['price'] ?? 0);

            $szConfig = $zoneSplitMap[$cleanZone] ?? null;

            // Capacidades configuradas: Física y Digital
            if ($isSplitActive && $szConfig) {
                $physCap = isset($szConfig['physical']) && is_numeric($szConfig['physical']) 
                    ? (int) $szConfig['physical'] 
                    : (int) ($z['physical_capacity'] ?? $zTotalCap);
                $virtCap = isset($szConfig['virtual']) && is_numeric($szConfig['virtual']) 
                    ? (int) $szConfig['virtual'] 
                    : (int) ($z['virtual_capacity'] ?? max(0, $zTotalCap - $physCap));
            } elseif (isset($z['physical_capacity']) || isset($z['virtual_capacity'])) {
                $physCap = (int) ($z['physical_capacity'] ?? $zTotalCap);
                $virtCap = (int) ($z['virtual_capacity'] ?? max(0, $zTotalCap - $physCap));
            } elseif ($event->sales_type === 'virtual') {
                $physCap = 0;
                $virtCap = $zTotalCap;
            } elseif ($event->sales_type === 'fisica') {
                $physCap = $zTotalCap;
                $virtCap = 0;
            } else {
                $physCap = $zTotalCap;
                $virtCap = 0;
            }

            // Ventas registradas de la zona
            $zoneSales = $sales->filter(function ($s) use ($zName, $cleanZone) {
                $sClean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $s->zone_name ?? '')));
                return $s->zone_name === $zName || $sClean === $cleanZone;
            });
            $rawZoneSold = (int) $zoneSales->sum('quantity');

            // Conteo exacto por tickets en BD
            $zoneSoldTickets = $soldTickets->filter(function ($t) use ($cleanZone) {
                $tClean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $t->zone_name ?? '')));
                return $tClean === $cleanZone;
            });

            // Conteo exacto por tickets en BD (Físicas vs Digitales)
            $physSold = 0;
            $virtSold = 0;
            foreach ($zoneSoldTickets as $t) {
                if (in_array($t->ticket_type, ['cortesia', 'cortesia_digital'])) {
                    continue;
                }
                $isPhys = ($t->ticket_type === 'fisica' && ($t->ticketSale?->sale_type === 'fisica' || $t->source === 'pos_physical'));
                if ($isPhys) {
                    $physSold++;
                } else {
                    $virtSold++;
                }
            }

            // Si hay ventas históricas sin boletos explícitos asignados, complementar por canal
            // Solo sale_type === 'fisica' o pos_physical cuenta como FÍSICA. Todo lo demás es DIGITAL.
            if (($physSold + $virtSold) < $rawZoneSold) {
                $posPhysQty = (int) $zoneSales->filter(fn($s) => ($s->sale_type ?? '') === 'fisica' || ($s->source ?? '') === 'pos_physical')->sum('quantity');
                $digitQty = (int) $zoneSales->filter(fn($s) => ($s->sale_type ?? 'digital') !== 'fisica' && ($s->source ?? '') !== 'pos_physical')->sum('quantity');
                $physSold = max($physSold, $posPhysQty);
                $virtSold = max($virtSold, $digitQty);
            }

            $zSold = max($rawZoneSold, ($physSold + $virtSold));
            $zAvail = max(0, $zTotalCap - $zSold);

            $physAvail = max(0, $physCap - $physSold);
            $virtAvail = max(0, $virtCap - $virtSold);

            // Cortesías para esta zona
            $zCourtesyConfig = $courtesyZoneConfigMap[$cleanZone] ?? ($courtesyZoneConfigMap[$zName] ?? null);
            $hasCustomCourtesyZones = count($courtesyZoneConfigMap) > 0;

            $zCourtesyEnabled = $hasCustomCourtesyZones
                ? (!empty($zCourtesyConfig['enabled']))
                : $courtesyEnabledGlobally;

            $courtesyPhysCap = 0;
            $courtesyVirtCap = 0;
            if ($szConfig) {
                if (isset($szConfig['courtesy_physical']) && is_numeric($szConfig['courtesy_physical'])) {
                    $courtesyPhysCap = (int) $szConfig['courtesy_physical'];
                }
                if (isset($szConfig['courtesy_virtual']) && is_numeric($szConfig['courtesy_virtual'])) {
                    $courtesyVirtCap = (int) $szConfig['courtesy_virtual'];
                }
            }
            if ($courtesyPhysCap === 0 && $courtesyVirtCap === 0 && $zCourtesyConfig) {
                if (isset($zCourtesyConfig['physical_stock']) && is_numeric($zCourtesyConfig['physical_stock'])) {
                    $courtesyPhysCap = (int) $zCourtesyConfig['physical_stock'];
                }
                if (isset($zCourtesyConfig['virtual_stock']) && is_numeric($zCourtesyConfig['virtual_stock'])) {
                    $courtesyVirtCap = (int) $zCourtesyConfig['virtual_stock'];
                }
                if ($courtesyPhysCap === 0 && $courtesyVirtCap === 0 && isset($zCourtesyConfig['stock']) && is_numeric($zCourtesyConfig['stock'])) {
                    $courtesyPhysCap = (int) $zCourtesyConfig['stock'];
                }
            }

            $zCourtesyMaxStock = ($courtesyPhysCap + $courtesyVirtCap) > 0
                ? ($courtesyPhysCap + $courtesyVirtCap)
                : (($zCourtesyConfig && isset($zCourtesyConfig['stock']) && is_numeric($zCourtesyConfig['stock'])) ? (int) $zCourtesyConfig['stock'] : null);

            $courtesySoldTickets = $soldTickets->filter(function ($t) use ($cleanZone) {
                $tClean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $t->zone_name ?? '')));
                return (str_contains($tClean, 'CORTESIA') || str_contains($tClean, 'CORTESÍA')) && str_contains($tClean, $cleanZone);
            });
            $courtesyVirtSold = $courtesySoldTickets->where(fn($t) => $t->ticket_type === 'cortesia_digital' || ($t->ticket_type === 'cortesia' && in_array($t->source, ['web_checkout', 'pos_courtesy', 'pos_sale'])))->count();
            $courtesyPhysSold = $courtesySoldTickets->where('ticket_type', 'cortesia')->whereNotIn('source', ['web_checkout', 'pos_courtesy', 'pos_sale'])->count();

            $courtesySalesQty = (int) $courtesySales->filter(function ($s) use ($zName, $cleanZone) {
                $sClean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $s->zone_name ?? '')));
                return $s->zone_name === $zName || $sClean === $cleanZone;
            })->sum('quantity');

            // Las ventas de cortesía en taquilla son digitales
            if (($courtesyVirtSold + $courtesyPhysSold) < $courtesySalesQty) {
                $courtesyVirtSold = max($courtesyVirtSold, $courtesySalesQty);
            }

            $zCourtesySold = max($courtesySalesQty, ($courtesyPhysSold + $courtesyVirtSold));
            $zCourtesyAvailable = $zCourtesyMaxStock !== null
                ? max(0, $zCourtesyMaxStock - $zCourtesySold)
                : $zAvail;

            $hasCourtesySplit = ($courtesyPhysCap + $courtesyVirtCap) > 0;
            $courtesyVirtAvail = $courtesyVirtCap > 0 
                ? max(0, $courtesyVirtCap - $courtesyVirtSold) 
                : ($hasCourtesySplit ? 0 : $zCourtesyAvailable);
            $courtesyPhysAvail = $courtesyPhysCap > 0 
                ? max(0, $courtesyPhysCap - $courtesyPhysSold) 
                : ($hasCourtesySplit ? 0 : $zCourtesyAvailable);

            $zonesWithStats[] = [
                'name' => $zName,
                'price' => $zPrice,
                'capacity' => $zTotalCap,
                'sold' => $zSold,
                'available' => $zAvail,
                'percentage' => $zTotalCap > 0 ? min(100, round(($zSold / $zTotalCap) * 100)) : 0,

                // Desglose físico vs virtual
                'has_split' => $isSplitActive,
                'physical_capacity' => $physCap,
                'digital_capacity' => $virtCap,
                'physical_sold' => $physSold,
                'digital_sold' => $virtSold,
                'physical_available' => $physAvail,
                'digital_available' => $virtAvail,

                // Cortesía
                'courtesy_enabled' => $zCourtesyEnabled,
                'courtesy_max_stock' => $zCourtesyMaxStock,
                'courtesy_sold' => $zCourtesySold,
                'courtesy_available' => $zCourtesyAvailable,
                'courtesy_digital_available' => $courtesyVirtAvail,
                'courtesy_physical_available' => $courtesyPhysAvail,
                'courtesy_physical_capacity' => $courtesyPhysCap,
                'courtesy_digital_capacity' => $courtesyVirtCap,
                'courtesy_physical_sold' => $courtesyPhysSold,
                'courtesy_digital_sold' => $courtesyVirtSold,

                'capacity_type' => $z['capacity_type'] ?? null,
                'seats' => $z['seats'] ?? [],
            ];
        }

        return $zonesWithStats;
    }
}

