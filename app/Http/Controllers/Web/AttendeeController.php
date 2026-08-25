<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Setting;
use App\Models\Company;
use App\Models\TicketSale;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendeeController extends Controller
{
    /**
     * Muestra el catálogo de eventos para Control de Acceso & Asistentes.
     */
    public function index(): View
    {
        $settings = Setting::first();
        $organizer = Company::first();

        $dbEvents = Event::orderBy('event_date', 'desc')->get();
        $events = [];

        foreach ($dbEvents as $ev) {
            $zones = is_array($ev->zones) ? $ev->zones : [];
            $totalCapacity = (int) array_sum(array_column($zones, 'capacity'));
            $ticketsSold = $ev->sales ? (int) $ev->sales->sum('quantity') : (int) TicketSale::where('event_id', $ev->id)->sum('quantity');

            // Total boletos registrados en el sistema (PDFs + Ventas)
            $ticketsIssued = EventTicket::where('event_id', $ev->id)->count();
            if ($ticketsIssued === 0) {
                $ticketsIssued = max($ticketsSold, $totalCapacity);
            }

            // Total validados / ingresados
            $checkedInCount = EventTicket::where('event_id', $ev->id)->where('is_used', true)->count();
            $pendingCount = max(0, $ticketsIssued - $checkedInCount);
            $attendanceRate = $ticketsIssued > 0 ? min(100, round(($checkedInCount / $ticketsIssued) * 100, 1)) : 0;

            // Formatear fecha
            $dateFormatted = '10/04/2025';
            if (!empty($ev->event_date)) {
                try {
                    $dateFormatted = $ev->event_date instanceof \DateTimeInterface 
                        ? $ev->event_date->format('d/m/Y') 
                        : Carbon::parse($ev->event_date)->format('d/m/Y');
                } catch (\Exception $e) {
                    $dateFormatted = (string) $ev->event_date;
                }
            }

            $events[] = [
                'id' => $ev->id,
                'title' => $ev->title,
                'slug' => $ev->slug,
                'category' => $ev->category_name ?? 'Concierto',
                'sales_type' => $ev->sales_type ?? 'fisica',
                'image' => $ev->banner_image ?: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                'date_formatted' => $dateFormatted,
                'venue' => $ev->venue_name ?? 'Local Principal',
                'tickets_issued' => $ticketsIssued,
                'checked_in_count' => $checkedInCount,
                'pending_count' => $pendingCount,
                'attendance_rate' => $attendanceRate,
                'status' => $ev->status ?? 'Publicado',
                'status_class' => $ev->status === 'Publicado' ? 'badge-green' : ($ev->status === 'Agotado' ? 'badge-red' : 'badge-orange'),
            ];
        }

        return view('web.attendees', compact('events', 'settings', 'organizer'));
    }

    /**
     * Muestra la interfaz de Scanner QR y Control de Acceso en Vivo para un evento específico.
     */
    public function scanner(Event $event): View
    {
        $settings = Setting::first();
        $organizer = Company::first();

        // Asegurar que si hay ventas previas en ticket_sales pero no en event_tickets, se sincronicen
        $this->syncLegacySalesTickets($event);

        $ticketsIssued = EventTicket::where('event_id', $event->id)->count();
        $checkedInCount = EventTicket::where('event_id', $event->id)->where('is_used', true)->count();
        $pendingCount = max(0, $ticketsIssued - $checkedInCount);
        $attendanceRate = $ticketsIssued > 0 ? min(100, round(($checkedInCount / $ticketsIssued) * 100, 1)) : 0;

        // Historial reciente de ingresos (últimos 25)
        $recentCheckins = EventTicket::where('event_id', $event->id)
            ->where('is_used', true)
            ->orderBy('checked_in_at', 'desc')
            ->take(25)
            ->get();

        // Métricas por zona
        $zones = is_array($event->zones) ? $event->zones : [];
        $zonesAttendance = [];
        foreach ($zones as $z) {
            $zName = $z['name'] ?? 'General';
            $zIssued = EventTicket::where('event_id', $event->id)->where('zone_name', $zName)->count();
            $zChecked = EventTicket::where('event_id', $event->id)->where('zone_name', $zName)->where('is_used', true)->count();
            $zRate = $zIssued > 0 ? round(($zChecked / $zIssued) * 100, 1) : 0;

            $zonesAttendance[] = [
                'name' => $zName,
                'price' => $z['price'] ?? 0,
                'issued' => $zIssued,
                'checked_in' => $zChecked,
                'pending' => max(0, $zIssued - $zChecked),
                'rate' => $zRate,
            ];
        }

        $metrics = [
            'tickets_issued' => $ticketsIssued,
            'checked_in_count' => $checkedInCount,
            'pending_count' => $pendingCount,
            'attendance_rate' => $attendanceRate,
        ];

        return view('web.attendees_scanner', compact('event', 'metrics', 'recentCheckins', 'zonesAttendance', 'settings', 'organizer'));
    }

    /**
     * Valida un código QR o código de boleto escaneado en tiempo real.
     */
    public function verifyQr(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'qr_payload' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        $rawInput = trim($validated['qr_payload']);
        $deviceName = $validated['device_name'] ?? 'Control Puerta Principal';

        // 1. Buscar coincidencia exacta por qr_payload o ticket_code
        $ticket = EventTicket::where('event_id', $event->id)
            ->where(function ($q) use ($rawInput) {
                $q->where('qr_payload', $rawInput)
                  ->orWhere('ticket_code', $rawInput)
                  ->orWhere('validation_hash', $rawInput);
            })
            ->first();

        // 2. Si no coincide exactamente, buscar si contiene el código o token
        if (!$ticket) {
            $ticket = EventTicket::where('event_id', $event->id)
                ->where('qr_payload', 'LIKE', '%' . $rawInput . '%')
                ->first();
        }

        // 3. Fallback: Buscar en ticket_sales si el boleto fue emitido en POS o Web
        if (!$ticket) {
            $sales = TicketSale::where('event_id', $event->id)->get();
            foreach ($sales as $sale) {
                $tData = is_array($sale->tickets_data) ? $sale->tickets_data : (json_decode($sale->tickets_data ?? '[]', true) ?? []);
                $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                if (!is_array($items)) continue;

                $numIndex = 0;
                foreach ($items as $t) {
                    if (!is_array($t)) continue;
                    $numIndex++;
                    if (($t['qr_payload'] ?? '') === $rawInput || ($t['ticket_code'] ?? '') === $rawInput || ($t['validation_hash'] ?? '') === $rawInput) {
                        // Crear el registro en event_tickets
                        $ticket = EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_sale_id' => $sale->id,
                            'ticket_code' => $t['ticket_code'] ?? "TK-{$sale->receipt_number}-{$numIndex}",
                            'ticket_number' => $numIndex,
                            'zone_name' => $t['zone'] ?? ($t['name'] ?? $sale->zone_name),
                            'unit_price' => $t['price'] ?? $sale->unit_price,
                            'qr_payload' => $t['qr_payload'] ?? $rawInput,
                            'validation_hash' => $t['validation_hash'] ?? ('VG' . strtoupper(substr(md5($sale->receipt_number . $numIndex), 0, 8))),
                            'buyer_name' => $t['buyer_name'] ?? $sale->buyer_name,
                            'buyer_dni' => $t['buyer_dni'] ?? $sale->buyer_dni,
                            'source' => $sale->seller_name ?: 'pos_sale',
                            'is_used' => false,
                            'status' => 'valid',
                        ]);
                        break 2;
                    }
                }
            }
        }

        // 4. Si después de todo no se encuentra: BOLETO INVÁLIDO
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'status' => 'invalid',
                'title' => '❌ ¡BOLETO NO ENCONTRADO O FALSOS!',
                'message' => 'El código QR o número de boleto escaneado no corresponde a ningún boleto emitido para este evento.',
                'raw_input' => $rawInput,
            ], 404);
        }

        // 5. Si ya fue utilizado: BOLETO YA USADO / ACCESO DENEGADO
        if ($ticket->is_used) {
            $usedTime = $ticket->checked_in_at ? $ticket->checked_in_at->format('d/m/Y h:i:s A') : 'Hora desconocida';
            $usedDoor = $ticket->scanned_by ?: 'Puerta Principal';
            $usedHash = $ticket->validation_hash ?: ('VG' . strtoupper(substr(md5($ticket->id), 0, 8)));

            return response()->json([
                'success' => false,
                'status' => 'already_used',
                'title' => '🚫 ¡ACCESO DENEGADO! BOLETO YA UTILIZADO',
                'message' => "Este boleto ya fue validado e ingresó el {$usedTime} en {$usedDoor}.",
                'ticket' => [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'validation_hash' => $usedHash,
                    'zone_name' => $ticket->zone_name,
                    'unit_price' => 'S/ ' . number_format($ticket->unit_price, 2),
                    'buyer_name' => $ticket->buyer_name,
                    'buyer_dni' => $ticket->buyer_dni,
                    'checked_in_at' => $usedTime,
                    'scanned_by' => $usedDoor,
                ],
            ], 409);
        }

        // 6. BOLETO VÁLIDO: Marcar como utilizado
        $ticket->is_used = true;
        $ticket->checked_in_at = now();
        $ticket->scanned_by = $deviceName;
        $ticket->save();

        $hashVal = $ticket->validation_hash ?: ('VG' . strtoupper(substr(md5($ticket->id), 0, 8)));

        // Recalcular métricas en vivo
        $ticketsIssued = EventTicket::where('event_id', $event->id)->count();
        $checkedInCount = EventTicket::where('event_id', $event->id)->where('is_used', true)->count();
        $pendingCount = max(0, $ticketsIssued - $checkedInCount);
        $attendanceRate = $ticketsIssued > 0 ? min(100, round(($checkedInCount / $ticketsIssued) * 100, 1)) : 0;

        return response()->json([
            'success' => true,
            'status' => 'granted',
            'title' => '✅ ¡ACCESO PERMITIDO! BIENVENIDO',
            'message' => "Boleto válido verificado con éxito para la zona {$ticket->zone_name}.",
            'ticket' => [
                'id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'validation_hash' => $hashVal,
                'zone_name' => $ticket->zone_name,
                'unit_price' => 'S/ ' . number_format($ticket->unit_price, 2),
                'buyer_name' => $ticket->buyer_name,
                'buyer_dni' => $ticket->buyer_dni,
                'checked_in_at' => $ticket->checked_in_at->format('h:i:s A'),
                'checked_in_date' => $ticket->checked_in_at->format('d/m/Y'),
                'scanned_by' => $ticket->scanned_by,
            ],
            'metrics' => [
                'tickets_issued' => $ticketsIssued,
                'checked_in_count' => $checkedInCount,
                'pending_count' => $pendingCount,
                'attendance_rate' => $attendanceRate,
            ],
        ]);
    }

    /**
     * Muestra la interfaz exclusiva para escaneo móvil optimizada para celulares.
     */
    public function mobileScanner(Event $event): View
    {
        $settings = Setting::first();
        $organizer = Company::first();

        $this->syncLegacySalesTickets($event);

        $ticketsIssued = EventTicket::where('event_id', $event->id)->count();
        $checkedInCount = EventTicket::where('event_id', $event->id)->where('is_used', true)->count();
        $pendingCount = max(0, $ticketsIssued - $checkedInCount);
        $attendanceRate = $ticketsIssued > 0 ? min(100, round(($checkedInCount / $ticketsIssued) * 100, 1)) : 0;

        $recentCheckins = EventTicket::where('event_id', $event->id)
            ->where('is_used', true)
            ->orderBy('checked_in_at', 'desc')
            ->take(15)
            ->get();

        $metrics = [
            'tickets_issued' => $ticketsIssued,
            'checked_in_count' => $checkedInCount,
            'pending_count' => $pendingCount,
            'attendance_rate' => $attendanceRate,
        ];

        return view('web.attendees_mobile_scanner', compact('event', 'metrics', 'recentCheckins', 'settings', 'organizer'));
    }

    /**
     * Endpoint de sincronización en vivo para el panel de control de acceso.
     */
    public function checkinsFeed(Request $request, Event $event): JsonResponse
    {
        $sinceId = (int) $request->query('since_id', 0);

        $newCheckinsQuery = EventTicket::where('event_id', $event->id)
            ->where('is_used', true);

        if ($sinceId > 0) {
            $newCheckinsQuery->where('id', '>', $sinceId);
        }

        $newCheckins = $newCheckinsQuery->orderBy('checked_in_at', 'desc')
            ->take(25)
            ->get()
            ->map(function ($ticket) {
                $hash = $ticket->validation_hash ?: ('VG' . strtoupper(substr(md5($ticket->id), 0, 8)));
                return [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'validation_hash' => $hash,
                    'zone_name' => $ticket->zone_name,
                    'buyer_name' => $ticket->buyer_name,
                    'buyer_dni' => $ticket->buyer_dni,
                    'checked_in_at' => $ticket->checked_in_at ? $ticket->checked_in_at->format('h:i:s A') : '',
                    'checked_in_date' => $ticket->checked_in_at ? $ticket->checked_in_at->format('d/m/Y') : '',
                    'scanned_by' => $ticket->scanned_by ?: 'Móvil Scanner',
                ];
            });

        // Consulta agregada única de 1 solo paso para máximo rendimiento en tiempo real
        $statsByZone = EventTicket::where('event_id', $event->id)
            ->selectRaw('zone_name, count(*) as total, sum(case when is_used = 1 then 1 else 0 end) as checked_in')
            ->groupBy('zone_name')
            ->get()
            ->keyBy('zone_name');

        $ticketsIssued = 0;
        $checkedInCount = 0;
        foreach ($statsByZone as $st) {
            $ticketsIssued += (int) $st->total;
            $checkedInCount += (int) $st->checked_in;
        }

        $pendingCount = max(0, $ticketsIssued - $checkedInCount);
        $attendanceRate = $ticketsIssued > 0 ? min(100, round(($checkedInCount / $ticketsIssued) * 100, 1)) : 0;

        $zones = is_array($event->zones) ? $event->zones : [];
        $zonesAttendance = [];
        foreach ($zones as $z) {
            $zName = $z['name'] ?? 'General';
            $st = $statsByZone->get($zName);
            $zIssued = $st ? (int) $st->total : 0;
            $zChecked = $st ? (int) $st->checked_in : 0;
            $zRate = $zIssued > 0 ? round(($zChecked / $zIssued) * 100, 1) : 0;

            $zonesAttendance[] = [
                'name' => $zName,
                'issued' => $zIssued,
                'checked_in' => $zChecked,
                'pending' => max(0, $zIssued - $zChecked),
                'rate' => $zRate,
            ];
        }

        return response()->json([
            'success' => true,
            'new_checkins' => $newCheckins,
            'metrics' => [
                'tickets_issued' => $ticketsIssued,
                'checked_in_count' => $checkedInCount,
                'pending_count' => $pendingCount,
                'attendance_rate' => $attendanceRate,
            ],
            'zones' => $zonesAttendance,
        ]);
    }

    /**
     * Sincroniza ventas de Taquilla y Web existentes con la tabla event_tickets.
     */
    private function syncLegacySalesTickets(Event $event): void
    {
        $sales = TicketSale::where('event_id', $event->id)->get();
        foreach ($sales as $sale) {
            $tData = is_array($sale->tickets_data) ? $sale->tickets_data : (json_decode($sale->tickets_data ?? '[]', true) ?? []);
            $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
            if (!is_array($items) || empty($items)) {
                // Si no hay items desglose, crear por la cantidad de la venta
                $qty = max(1, (int)$sale->quantity);
                for ($idx = 1; $idx <= $qty; $idx++) {
                    $payload = "VIVEGO|EVT-{$event->id}|REC-{$sale->receipt_number}|TICK-{$idx}";
                    EventTicket::firstOrCreate(
                        [
                            'event_id' => $event->id,
                            'qr_payload' => $payload,
                        ],
                        [
                            'ticket_sale_id' => $sale->id,
                            'ticket_code' => "TK-{$sale->receipt_number}-{$idx}",
                            'ticket_number' => $idx,
                            'zone_name' => $sale->zone_name,
                            'unit_price' => $sale->unit_price,
                            'validation_hash' => 'VG' . strtoupper(substr(md5($sale->receipt_number . $idx), 0, 8)),
                            'buyer_name' => $sale->buyer_name,
                            'buyer_dni' => $sale->buyer_dni,
                            'source' => $sale->seller_name ?: 'pos_sale',
                            'is_used' => false,
                            'status' => 'valid',
                        ]
                    );
                }
                continue;
            }

            $numIndex = 0;
            foreach ($items as $t) {
                if (!is_array($t)) continue;
                $numIndex++;
                $payload = $t['qr_payload'] ?? "VIVEGO|EVT-{$event->id}|REC-{$sale->receipt_number}|TICK-{$numIndex}";
                EventTicket::firstOrCreate(
                    [
                        'event_id' => $event->id,
                        'qr_payload' => $payload,
                    ],
                    [
                        'ticket_sale_id' => $sale->id,
                        'ticket_code' => $t['ticket_code'] ?? "TK-{$sale->receipt_number}-{$numIndex}",
                        'ticket_number' => $numIndex,
                        'zone_name' => $t['zone'] ?? ($t['name'] ?? $sale->zone_name),
                        'unit_price' => $t['price'] ?? $sale->unit_price,
                        'validation_hash' => $t['validation_hash'] ?? ('VG' . strtoupper(substr(md5($sale->receipt_number . $numIndex), 0, 8))),
                        'buyer_name' => $t['buyer_name'] ?? $sale->buyer_name,
                        'buyer_dni' => $t['buyer_dni'] ?? $sale->buyer_dni,
                        'source' => $sale->seller_name ?: 'pos_sale',
                        'is_used' => false,
                        'status' => 'valid',
                    ]
                );
            }
        }
    }
}
