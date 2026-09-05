<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketGenerationService
{
    /**
     * Sincroniza y pre-genera automáticamente todos los boletos oficiales (QR, correlativo y hash)
     * para el aforo configurado en las zonas del evento.
     *
     * Si el aforo aumentó o se agregaron nuevas butacas, genera solo los faltantes respetando
     * el orden correlativo continuo y protegiendo las entradas ya vendidas o impresas.
     */
    public static function syncEventTickets(Event $event): array
    {
        @set_time_limit(180);

        return DB::transaction(function () use ($event) {
            $zones = is_array($event->zones) ? $event->zones : (is_string($event->zones) ? json_decode($event->zones, true) : []);
            if (empty($zones)) {
                return ['created' => 0, 'total' => 0];
            }

            // Obtener el correlativo máximo actual de este evento
            $maxCorrelative = (int) EventTicket::where('event_id', $event->id)->max('ticket_number') ?: 0;
            $nextCorrelative = $maxCorrelative + 1;
            $createdCount = 0;

            // Obtener todos los boletos ya existentes del evento en memoria
            $existingTickets = EventTicket::where('event_id', $event->id)->get();

            // Mapa de butacas existentes indexadas por código normalizado: ej "A1", "F10"
            $existingNumberedSeats = [];
            // Conteo de boletos existentes por zona base no numerada
            $existingGeneralCounts = [];

            foreach ($existingTickets as $et) {
                $rawZone = $et->zone_name ?: '';
                if (preg_match('/\(([^)]+)\)/', $rawZone, $matches)) {
                    $seatCode = function_exists('formatShortSeatCode') ? strtoupper(trim(formatShortSeatCode($matches[1]))) : strtoupper(trim($matches[1]));
                    $cleanZone = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $rawZone)));
                    $existingNumberedSeats["{$cleanZone}___{$seatCode}"] = $et;
                } else {
                    $cleanZone = strtoupper(trim($rawZone));
                    $existingGeneralCounts[$cleanZone] = ($existingGeneralCounts[$cleanZone] ?? 0) + 1;
                }
            }

            foreach ($zones as $idx => $zone) {
                $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                $cleanZoneUpper = strtoupper($zoneName);

                // Omitir elementos decorativos o no comercializables (Escenario / Tarima)
                if (in_array($cleanZoneUpper, ['ESCENARIO', 'TARIMA']) || ($zone['capacity_type'] ?? '') === 'Escenario' || ($zone['type'] ?? '') === 'stage') {
                    continue;
                }

                $zonePrice = isset($zone['price']) ? (float)$zone['price'] : 0.00;
                $targetCapacity = isset($zone['capacity']) ? (int)$zone['capacity'] : 0;
                $seats = isset($zone['seats']) && is_array($zone['seats']) ? $zone['seats'] : [];

                if (!empty($seats)) {
                    // --- CASO 1: ZONA CON BUTACAS NUMERADAS ---
                    foreach ($seats as $seat) {
                        $seatCode = function_exists('formatShortSeatCode') ? strtoupper(trim(formatShortSeatCode($seat))) : strtoupper(trim($seat['number'] ?? $seat['label'] ?? (($seat['row'] ?? '') . ($seat['col'] ?? ''))));
                        if (empty($seatCode)) {
                            continue;
                        }

                        $seatKey = "{$cleanZoneUpper}___{$seatCode}";
                        // Si ya existe boleto para esta butaca, no hacer nada (conserva su QR y venta intactos)
                        if (isset($existingNumberedSeats[$seatKey])) {
                            continue;
                        }

                        // Crear boleto oficial para esta butaca
                        $ticketNumber = $nextCorrelative++;
                        $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                        $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_', true) . $event->id . $ticketNumber), 0, 8));
                        $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$ticketNumber}|HASH-{$validationHash}";
                        $fullZoneName = function_exists('formatZoneWithSeat') ? formatZoneWithSeat($zoneName, $seat) : "{$zoneName} ({$seatCode})";

                        $newTicket = EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_sale_id' => null,
                            'ticket_code' => $ticketCode,
                            'ticket_number' => $ticketNumber,
                            'zone_name' => $fullZoneName,
                            'unit_price' => $zonePrice,
                            'qr_payload' => $qrPayload,
                            'validation_hash' => $validationHash,
                            'buyer_name' => 'Talonario Físico / Taquilla',
                            'buyer_dni' => '00000000',
                            'source' => 'pdf_batch',
                            'is_used' => false,
                            'status' => 'valid',
                        ]);

                        $existingNumberedSeats[$seatKey] = $newTicket;
                        $createdCount++;
                    }
                } else {
                    // --- CASO 2: ZONA GENERAL (SIN NUMERAR) ---
                    $currentInDb = $existingGeneralCounts[$cleanZoneUpper] ?? 0;
                    $missingCount = max(0, $targetCapacity - $currentInDb);

                    for ($k = 0; $k < $missingCount; $k++) {
                        $ticketNumber = $nextCorrelative++;
                        $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                        $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_', true) . $event->id . $ticketNumber), 0, 8));
                        $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$ticketNumber}|HASH-{$validationHash}";

                        EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_sale_id' => null,
                            'ticket_code' => $ticketCode,
                            'ticket_number' => $ticketNumber,
                            'zone_name' => $zoneName,
                            'unit_price' => $zonePrice,
                            'qr_payload' => $qrPayload,
                            'validation_hash' => $validationHash,
                            'buyer_name' => 'Talonario Físico / Taquilla',
                            'buyer_dni' => '00000000',
                            'source' => 'pdf_batch',
                            'is_used' => false,
                            'status' => 'valid',
                        ]);

                        $createdCount++;
                        $existingGeneralCounts[$cleanZoneUpper] = ($existingGeneralCounts[$cleanZoneUpper] ?? 0) + 1;
                    }
                }
            }

            // --- CASO 3: ENTRADAS DE CORTESÍA (SI ESTÁN ACTIVADAS EN EL EVENTO) ---
            $cSettings = is_array($event->courtesy_settings) 
                ? $event->courtesy_settings 
                : (json_decode($event->courtesy_settings ?? '[]', true) ?: []);
            $isCourtesyActive = !empty($cSettings['enabled']);

            if ($isCourtesyActive) {
                $courtesyConfigMap = [];
                if (!empty($cSettings['zones']) && is_array($cSettings['zones'])) {
                    foreach ($cSettings['zones'] as $cz) {
                        if (!empty($cz['name'])) {
                            $clean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $cz['name'])));
                            $courtesyConfigMap[$clean] = $cz;
                        }
                    }
                }
                $hasCustomCourtesyZones = count($courtesyConfigMap) > 0;

                foreach ($zones as $idx => $zone) {
                    $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                    $cleanZoneUpper = strtoupper($zoneName);

                    if (in_array($cleanZoneUpper, ['ESCENARIO', 'TARIMA']) || ($zone['capacity_type'] ?? '') === 'Escenario' || ($zone['type'] ?? '') === 'stage') {
                        continue;
                    }

                    $czConfig = $courtesyConfigMap[$cleanZoneUpper] ?? null;
                    $czCap = 0;
                    if (!empty($czConfig) && isset($czConfig['stock']) && $czConfig['stock'] !== null && $czConfig['stock'] !== '' && is_numeric($czConfig['stock'])) {
                        $czCap = (int)$czConfig['stock'];
                    }

                    // Si no se asignó cupo de cortesía o es 0, no se generan boletos de cortesía para este sector
                    if ($czCap <= 0) {
                        continue;
                    }

                    $courtesyZoneName = "CORTESÍA - {$zoneName}";
                    $cleanCourtesyUpper = strtoupper($courtesyZoneName);

                    $currentInDb = $existingGeneralCounts[$cleanCourtesyUpper] ?? 0;
                    $missingCount = max(0, $czCap - $currentInDb);

                    for ($k = 0; $k < $missingCount; $k++) {
                        $ticketNumber = $nextCorrelative++;
                        $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                        $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_', true) . $event->id . $ticketNumber), 0, 8));
                        $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$ticketNumber}|HASH-{$validationHash}";

                        EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_sale_id' => null,
                            'ticket_code' => $ticketCode,
                            'ticket_number' => $ticketNumber,
                            'zone_name' => $courtesyZoneName,
                            'unit_price' => 0.00,
                            'qr_payload' => $qrPayload,
                            'validation_hash' => $validationHash,
                            'buyer_name' => 'Pase de Cortesía / Taquilla',
                            'buyer_dni' => '00000000',
                            'source' => 'pdf_batch',
                            'is_used' => false,
                            'status' => 'valid',
                        ]);

                        $createdCount++;
                        $existingGeneralCounts[$cleanCourtesyUpper] = ($existingGeneralCounts[$cleanCourtesyUpper] ?? 0) + 1;
                    }
                }
            }

            $totalNow = EventTicket::where('event_id', $event->id)->count();

            return [
                'created' => $createdCount,
                'total' => $totalNow,
            ];
        });
    }
}
