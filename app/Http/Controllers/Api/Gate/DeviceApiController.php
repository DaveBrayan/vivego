<?php

namespace App\Http\Controllers\Api\Gate;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Event;
use App\Models\EventTicket;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceApiController extends Controller
{
    /**
     * Paso 1: Vincula la aplicación móvil mediante el código QR escaneado en pantalla.
     */
    public function pair(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => 'required|string',
            'token' => 'required|string',
            'device_model' => 'nullable|string',
            'platform' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        $device = Device::where('device_uuid', $validated['device_uuid'])
            ->where('pairing_token', $validated['token'])
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'status' => 'invalid_token',
                'message' => 'Código de vinculación inválido o dispositivo no encontrado en el sistema.',
            ], 404);
        }

        if ($device->status === 'revoked') {
            return response()->json([
                'success' => false,
                'status' => 'revoked',
                'message' => 'Este dispositivo ha sido revocado permanentemente por el administrador.',
            ], 403);
        }

        // Generar token API seguro para las solicitudes posteriores de este dispositivo
        $apiToken = Str::random(60);

        $model = !empty($validated['device_model']) ? trim($validated['device_model']) : ($device->device_model ?: 'Smartphone Android');
        $platform = !empty($validated['platform']) ? trim($validated['platform']) : ($device->platform ?: 'Android');
        $version = !empty($validated['app_version']) ? trim($validated['app_version']) : ($device->app_version ?: '1.0.0');

        $device->update([
            'api_token' => $apiToken,
            'status' => 'active',
            'device_model' => $model,
            'platform' => $platform,
            'app_version' => $version,
            'last_ip' => $request->ip(),
            'paired_at' => now(),
            'last_activity_at' => now(),
        ]);

        $assignedEvents = $this->formatEventsForDevice($device);

        return response()->json([
            'success' => true,
            'message' => "¡Dispositivo '{$device->name}' vinculado exitosamente con ViveGo!",
            'api_token' => $apiToken,
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'device_uuid' => $device->device_uuid,
                'status' => $device->status,
                'paired_at' => $device->paired_at->format('d/m/Y H:i:s'),
            ],
            'events' => $assignedEvents,
        ]);
    }

    /**
     * Paso 2: Latido (Heartbeat) para verificar si el dispositivo sigue activo y con qué eventos.
     */
    public function status(Request $request): JsonResponse
    {
        /** @var Device $device */
        $device = $request->attributes->get('device');

        $isBlocked = ($device->status === 'revoked');
        $events = $isBlocked ? [] : $this->formatEventsForDevice($device);

        return response()->json([
            'success' => true,
            'status' => $device->status,
            'is_blocked' => $isBlocked,
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'device_uuid' => $device->device_uuid,
                'status' => $device->status,
                'scans_count' => $device->scans_count,
                'last_scanned_at' => $device->last_scanned_at ? $device->last_scanned_at->format('d/m/Y H:i:s') : null,
            ],
            'events_count' => count($events),
            'events' => $events,
        ]);
    }

    /**
     * Paso 3: Obtiene el catálogo de eventos autorizados para este dispositivo móvil.
     */
    public function events(Request $request): JsonResponse
    {
        /** @var Device $device */
        $device = $request->attributes->get('device');

        return response()->json([
            'success' => true,
            'events' => $this->formatEventsForDevice($device),
        ]);
    }

    /**
     * Paso 4: Escaneo y Validación de Boleto en Vivo por la App Móvil.
     */
    public function scan(Request $request): JsonResponse
    {
        /** @var Device $device */
        $device = $request->attributes->get('device');

        $validated = $request->validate([
            'event_id' => 'required|integer',
            'qr_payload' => 'required|string',
        ]);

        $eventId = (int) $validated['event_id'];
        $rawInput = trim($validated['qr_payload']);

        // 1. Validar si el dispositivo tiene permiso para este evento
        if (!$device->hasAccessToEvent($eventId)) {
            return response()->json([
                'success' => false,
                'status' => 'forbidden_event',
                'title' => '🚫 DISPOSITIVO NO AUTORIZADO',
                'message' => "Este dispositivo no cuenta con permisos para escanear en este evento.",
            ], 403);
        }

        $event = Event::find($eventId);
        if (!$event) {
            return response()->json([
                'success' => false,
                'status' => 'not_found',
                'title' => '❌ EVENTO NO ENCONTRADO',
                'message' => 'El evento seleccionado no existe en el sistema.',
            ], 404);
        }

        // 2. Resolver boleto contra la base de datos
        $ticket = $this->resolveTicketFromInput($rawInput, $event);

        // 3. Si no pertenece a este evento, verificar si es de otro evento
        if (!$ticket) {
            $otherTicket = $this->resolveTicketFromInput($rawInput, null);
            if ($otherTicket && $otherTicket->event_id !== $event->id) {
                $otherEvent = Event::find($otherTicket->event_id);
                $otherDate = '';
                if ($otherEvent && !empty($otherEvent->event_date)) {
                    $otherDate = $otherEvent->event_date instanceof \DateTimeInterface
                        ? $otherEvent->event_date->format('d/m/Y')
                        : (is_string($otherEvent->event_date) ? substr($otherEvent->event_date, 0, 10) : '');
                }

                return response()->json([
                    'success' => false,
                    'status' => 'wrong_event',
                    'title' => '⚠️ BOLETO DE OTRO EVENTO',
                    'message' => "El boleto es auténtico pero pertenece a: \"{$otherEvent?->title}\"" . ($otherDate ? " ({$otherDate})" : "") . ". No es para este evento.",
                    'ticket' => [
                        'ticket_code' => $otherTicket->ticket_code,
                        'buyer_name' => $otherTicket->buyer_name,
                        'zone_name' => $otherTicket->zone_name,
                        'event_name' => $otherEvent?->title,
                    ],
                ], 422);
            }

            return response()->json([
                'success' => false,
                'status' => 'invalid',
                'title' => '❌ BOLETO INVÁLIDO',
                'message' => 'Código QR no reconocido o no registrado en el sistema ViveGo.',
                'raw_input' => $rawInput,
            ], 404);
        }

        // 4. Verificar si el boleto fue anulado por Upgrade
        $isUpgraded = ($ticket->status === 'upgraded')
            || ($ticket->ticketSale && $ticket->ticketSale->status === 'upgraded');

        if ($isUpgraded) {
            $upgradedTo = $ticket->ticketSale?->upgradedTo;
            $newZone = $upgradedTo ? $upgradedTo->zone_name : 'una zona superior';

            return response()->json([
                'success' => false,
                'status' => 'upgraded_void',
                'title' => '🚫 BOLETO ANULADO POR MEJORA',
                'message' => "Este boleto fue reemplazado por un pase a {$newZone}. Solicite la nueva entrada al usuario.",
                'ticket' => [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'zone_name' => $ticket->zone_name,
                    'new_zone' => $newZone,
                    'buyer_name' => $ticket->buyer_name,
                ],
            ], 422);
        }

        // 5. Verificar si ya fue utilizado (ACCESO DENEGADO)
        if ($ticket->is_used) {
            $usedTime = $ticket->checked_in_at ? $ticket->checked_in_at->format('d/m/Y h:i:s A') : 'Hora desconocida';
            $usedDoor = $ticket->scanned_by ?: 'Control de Puerta';

            return response()->json([
                'success' => false,
                'status' => 'already_used',
                'title' => '🚫 BOLETO YA UTILIZADO',
                'message' => "Este boleto ya fue ingresado el {$usedTime} en {$usedDoor}.",
                'ticket' => [
                    'id' => $ticket->id,
                    'ticket_code' => $ticket->ticket_code,
                    'buyer_name' => $ticket->buyer_name,
                    'zone_name' => $ticket->zone_name,
                    'checked_in_at' => $usedTime,
                    'scanned_by' => $usedDoor,
                ],
            ], 422);
        }

        // 6. ¡ACCESO CONCEDIDO! Marcar boleto como usado y registrar terminal que lo escaneó
        $scanTime = now();
        $ticket->update([
            'is_used' => true,
            'checked_in_at' => $scanTime,
            'scanned_by' => $device->name,
            'checked_in_device' => $device->name,
        ]);

        // Registrar métricas en el dispositivo móvil
        $device->increment('scans_count');
        $device->updateQuietly([
            'last_scanned_at' => $scanTime,
            'last_activity_at' => $scanTime,
        ]);

        // Extraer butaca si existe
        $seatCode = '';
        if (preg_match('/\(([^)]+)\)/', $ticket->zone_name, $m)) {
            $seatCode = trim($m[1]);
        }

        // Métricas actualizadas del evento para feedback en la pantalla
        $ticketsIssued = EventTicket::where('event_id', $event->id)->where('status', '!=', 'upgraded')->count();
        $checkedInCount = EventTicket::where('event_id', $event->id)->where('is_used', true)->where('status', '!=', 'upgraded')->count();
        $attendanceRate = $ticketsIssued > 0 ? round(($checkedInCount / $ticketsIssued) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'status' => 'success',
            'title' => '✅ ACCESO PERMITIDO',
            'message' => "¡Boleto validado correctamente! Bienvenido(a).",
            'ticket' => [
                'id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'ticket_number' => $ticket->ticket_number,
                'correlative' => 'N° ' . str_pad($ticket->ticket_number, 5, '0', STR_PAD_LEFT),
                'buyer_name' => $ticket->buyer_name ?: 'ASISTENTE',
                'buyer_dni' => $ticket->buyer_dni ?: '00000000',
                'zone_name' => $ticket->zone_name,
                'seat' => $seatCode,
                'unit_price' => number_format((float) $ticket->unit_price, 2, '.', ''),
                'checked_in_at' => $scanTime->format('d/m/Y h:i:s A'),
                'scanned_by' => $device->name,
            ],
            'metrics' => [
                'tickets_issued' => $ticketsIssued,
                'checked_in_count' => $checkedInCount,
                'pending_count' => max(0, $ticketsIssued - $checkedInCount),
                'attendance_rate' => $attendanceRate,
            ],
        ]);
    }

    /**
     * Paso 5: Desvincula el dispositivo voluntariamente desde la app.
     */
    public function unpair(Request $request): JsonResponse
    {
        /** @var Device $device */
        $device = $request->attributes->get('device');

        $device->update([
            'api_token' => null,
            'status' => 'pending',
            'pairing_token' => Str::random(32),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo desvinculado correctamente.',
        ]);
    }

    /**
     * Formatea los eventos asignados a un dispositivo para el consumo de la app móvil.
     */
    private function formatEventsForDevice(Device $device): array
    {
        $events = $device->getAssignedEventsList();
        $formatted = [];

        foreach ($events as $ev) {
            $ticketsIssued = EventTicket::where('event_id', $ev->id)->where('status', '!=', 'upgraded')->count();
            $checkedInCount = EventTicket::where('event_id', $ev->id)->where('is_used', true)->where('status', '!=', 'upgraded')->count();
            $attendanceRate = $ticketsIssued > 0 ? round(($checkedInCount / $ticketsIssued) * 100, 1) : 0;

            $dateFormatted = 'Fecha por confirmar';
            if (!empty($ev->event_date)) {
                $dateFormatted = $ev->event_date instanceof \DateTimeInterface
                    ? $ev->event_date->format('d M, Y')
                    : (is_string($ev->event_date) ? date('d M, Y', strtotime($ev->event_date)) : 'Fecha por confirmar');
            }

            $eventName = $ev->title ?: ($ev->name ?: 'Evento #' . $ev->id);

            // Aforo total calculado a partir de las zonas o boletos emitidos
            $zones = is_string($ev->zones) ? json_decode($ev->zones, true) : (is_array($ev->zones) ? $ev->zones : []);
            $totalCapacity = 0;
            if (!empty($zones)) {
                foreach ($zones as $z) {
                    $totalCapacity += (int) ($z['capacity'] ?? $z['stock'] ?? 0);
                }
            }
            if ($totalCapacity == 0) {
                $totalCapacity = max($ticketsIssued, 100);
            }

            $formatted[] = [
                'id' => $ev->id,
                'name' => $eventName,
                'title' => $eventName,
                'event_date' => $dateFormatted,
                'date' => $dateFormatted,
                'doors_open' => $ev->event_time ?: '20:00 HRS',
                'time' => $ev->event_time ?: '20:00 HRS',
                'venue' => $ev->venue_name ?: ($ev->address ?: 'Recinto Principal'),
                'address' => $ev->address ?: '',
                'banner_url' => $ev->banner_image ? asset($ev->banner_image) : null,
                'status' => $ev->status ?: 'active',
                'capacity' => $totalCapacity,
                'total_tickets' => $ticketsIssued,
                'tickets_issued' => $ticketsIssued,
                'used_tickets' => $checkedInCount,
                'checked_in_count' => $checkedInCount,
                'remaining_tickets' => max(0, $ticketsIssued - $checkedInCount),
                'pending_count' => max(0, $ticketsIssued - $checkedInCount),
                'attendance_percentage' => $attendanceRate,
                'attendance_rate' => $attendanceRate,
            ];
        }

        return $formatted;
    }

    /**
     * Resuelve un boleto a partir de cualquier código QR, hash, token o texto alfanumérico.
     */
    private function resolveTicketFromInput(string $rawInput, ?Event $event = null): ?EventTicket
    {
        $raw = trim($rawInput);
        if (empty($raw)) return null;

        $targetEventId = $event ? $event->id : null;

        $foundHash = null;
        $ticketCode = null;

        if (str_contains($raw, '|')) {
            $parts = explode('|', $raw);
            foreach ($parts as $p) {
                $pTrim = trim($p);
                if (str_starts_with($pTrim, 'TK-') || str_starts_with($pTrim, 'N°') || str_starts_with($pTrim, 'Nº')) {
                    $ticketCode = $pTrim;
                } elseif (str_starts_with($pTrim, 'HASH-')) {
                    $foundHash = substr($pTrim, 5);
                } elseif (str_starts_with($pTrim, 'VG') && strlen($pTrim) >= 6) {
                    $foundHash = $pTrim;
                }
            }
        }

        $ticketQuery = EventTicket::query();
        if ($targetEventId) {
            $ticketQuery->where('event_id', $targetEventId);
        }

        $ticket = (clone $ticketQuery)->where(function ($q) use ($raw, $foundHash, $ticketCode) {
            $q->where('qr_payload', $raw)
              ->orWhere('ticket_code', $raw)
              ->orWhere('validation_hash', $raw);

            if ($foundHash) {
                $q->orWhere('validation_hash', $foundHash)
                  ->orWhere('validation_hash', 'LIKE', "%{$foundHash}%");
            }
            if ($ticketCode) {
                $q->orWhere('ticket_code', $ticketCode);
            }
        })->first();

        if ($ticket) return $ticket;

        // Caso VGENC:
        if (str_starts_with($raw, 'VGENC:')) {
            $matched = EventTicket::where('qr_payload', $raw);
            if ($targetEventId) {
                $matched->where('event_id', $targetEventId);
            }
            return $matched->first();
        }

        return null;
    }
}
