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

    /**
     * Sincroniza todas las ventas registradas en ticket_sales con la tabla oficial
     * de boletos de plancha (event_tickets).
     *
     * Traslada el número correlativo, código QR oficial, hash de validación y datos del comprador,
     * vinculando 'ticket_sale_id' para que la plancha imprima exactamente el mismo boleto y QR
     * que ya tiene el comprador, evitando correlativos duplicados.
     */
    public static function syncSalesToEventTickets(): array
    {
        @set_time_limit(300);

        return DB::transaction(function () {
            $sales = \App\Models\TicketSale::orderBy('id', 'asc')->get();
            $validEventIds = Event::pluck('id')->flip()->toArray();

            $syncedSales = 0;
            $skippedSales = 0;
            $updatedTickets = 0;
            $createdTickets = 0;
            $details = [];

            foreach ($sales as $sale) {
                // Omitir ventas asociadas a eventos que ya no existen en base de datos
                if (!isset($validEventIds[$sale->event_id])) {
                    $skippedSales++;
                    continue;
                }

                $raw = $sale->tickets_data;
                $tData = is_array($raw) ? $raw : (json_decode($raw ?? '[]', true) ?: []);

                $ticketsList = [];
                $isItemsFormat = false;
                if (isset($tData['items']) && is_array($tData['items'])) {
                    $ticketsList = $tData['items'];
                    $isItemsFormat = true;
                } elseif (is_array($tData)) {
                    $numericItems = array_filter($tData, function ($k) {
                        return is_numeric($k);
                    }, ARRAY_FILTER_USE_KEY);
                    if (!empty($numericItems)) {
                        $ticketsList = array_values($numericItems);
                    }
                }

                $qty = (int)$sale->quantity > 0 ? (int)$sale->quantity : 1;
                if (empty($ticketsList)) {
                    for ($k = 0; $k < $qty; $k++) {
                        $ticketsList[] = [
                            'ticket_number' => $k + 1,
                            'zone' => $sale->zone_name,
                            'price' => $sale->unit_price,
                        ];
                    }
                }

                $updatedTicketsList = [];

                foreach ($ticketsList as $i => $t) {
                    $ticketNum = 0;
                    if (isset($t['ticket_number']) && is_numeric($t['ticket_number']) && (int)$t['ticket_number'] > 0) {
                        $ticketNum = (int)$t['ticket_number'];
                    } elseif (isset($t['number']) && is_numeric($t['number']) && (int)$t['number'] > 0) {
                        $ticketNum = (int)$t['number'];
                    } else {
                        $ticketNum = $i + 1;
                    }

                    $ticketCode = 'N° ' . str_pad($ticketNum, 5, '0', STR_PAD_LEFT);
                    $zoneName = !empty($t['zone']) ? $t['zone'] : (!empty($t['zone_name']) ? $t['zone_name'] : $sale->zone_name);
                    $unitPrice = isset($t['price']) ? (float)$t['price'] : (float)$sale->unit_price;
                    $buyerName = !empty($t['buyer_name']) ? $t['buyer_name'] : $sale->buyer_name;
                    $buyerDni = !empty($t['buyer_dni']) ? $t['buyer_dni'] : ($sale->buyer_dni ?: '00000000');

                    // Hash determinista idéntico al motor gráfico de boletos virtuales
                    $valHash = !empty($t['validation_hash']) ? $t['validation_hash'] : (!empty($t['hash']) ? $t['hash'] : '');
                    if (empty($valHash)) {
                        $str = ($sale->receipt_number ?: 'REC') . '_' . ($i + 1);
                        $h = abs(self::jsHashCode($str));
                        $valHash = 'VG' . substr(str_pad($h, 8, '0', STR_PAD_LEFT), 0, 8);
                    }

                    // QR payload oficial idéntico al emitido en taquilla o web
                    $qrPayload = !empty($t['qr_payload']) ? $t['qr_payload'] : (!empty($t['qr']) ? $t['qr'] : '');
                    if (empty($qrPayload)) {
                        $qrPayload = "VIVEGO|{$sale->receipt_number}|EVT-{$sale->event_id}|DNI-{$buyerDni}|TICK-{$ticketNum}|{$valHash}";
                    }

                    // 1. Buscar si ya existe el boleto vinculado a esta venta
                    $et = EventTicket::where('ticket_sale_id', $sale->id)
                        ->where('ticket_number', $ticketNum)
                        ->first();

                    // 2. Si no, buscar si existe un boleto con ese mismo correlativo en ese evento (ej. generado previamente para plancha)
                    if (!$et) {
                        $et = EventTicket::where('event_id', $sale->event_id)
                            ->where('ticket_number', $ticketNum)
                            ->first();
                    }

                    // 3. Si existe, lo actualizamos con los datos oficiales de la venta (QR, hash, comprador y ticket_sale_id)
                    if ($et) {
                        $et->update([
                            'ticket_sale_id' => $sale->id,
                            'ticket_code' => $ticketCode,
                            'zone_name' => $zoneName,
                            'unit_price' => $unitPrice,
                            'qr_payload' => $qrPayload,
                            'validation_hash' => $valHash,
                            'buyer_name' => $buyerName,
                            'buyer_dni' => $buyerDni,
                            'source' => 'pos_sale',
                            'status' => 'valid',
                        ]);
                        $updatedTickets++;
                    } else {
                        // 4. Si no existía en event_tickets, lo creamos directamente
                        $et = EventTicket::create([
                            'event_id' => $sale->event_id,
                            'ticket_sale_id' => $sale->id,
                            'ticket_code' => $ticketCode,
                            'ticket_number' => $ticketNum,
                            'zone_name' => $zoneName,
                            'unit_price' => $unitPrice,
                            'qr_payload' => $qrPayload,
                            'validation_hash' => $valHash,
                            'buyer_name' => $buyerName,
                            'buyer_dni' => $buyerDni,
                            'source' => 'pos_sale',
                            'is_used' => false,
                            'status' => 'valid',
                        ]);
                        $createdTickets++;
                    }

                    $t['event_ticket_id'] = $et->id;
                    $t['ticket_number'] = $ticketNum;
                    $t['ticket_code'] = $ticketCode;
                    $t['validation_hash'] = $valHash;
                    $t['qr_payload'] = $qrPayload;
                    $updatedTicketsList[] = $t;

                    $details[] = [
                        'sale_id' => $sale->id,
                        'event_id' => $sale->event_id,
                        'ticket_number' => $ticketNum,
                        'ticket_code' => $ticketCode,
                        'validation_hash' => $valHash,
                        'buyer_name' => $buyerName,
                        'event_ticket_id' => $et->id,
                        'action' => $et->wasRecentlyCreated ? 'Creado' : 'Actualizado',
                    ];
                }

                // Guardar la referencia mutua en ticket_sales
                if ($isItemsFormat) {
                    $tData['items'] = $updatedTicketsList;
                    $sale->update(['tickets_data' => $tData]);
                } else {
                    $sale->update(['tickets_data' => $updatedTicketsList]);
                }
                $syncedSales++;
            }

            // Normalizar cualquier boleto remanente en event_tickets con formato antiguo TK-
            $legacyTickets = EventTicket::where('ticket_code', 'like', 'TK-%')->get();
            foreach ($legacyTickets as $lt) {
                $num = (int)$lt->ticket_number > 0 ? (int)$lt->ticket_number : 1;
                $lt->update([
                    'ticket_code' => 'N° ' . str_pad($num, 5, '0', STR_PAD_LEFT),
                ]);
            }

            return [
                'synced_sales' => $syncedSales,
                'skipped_sales' => $skippedSales,
                'updated_tickets' => $updatedTickets,
                'created_tickets' => $createdTickets,
                'details' => $details,
            ];
        });
    }

    /**
     * Calcula el hash entero de 32-bit de un string compatible con JavaScript String.hashCode()
     */
    protected static function jsHashCode(string $str): int
    {
        $hash = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $hash = (($hash << 5) - $hash) + ord($str[$i]);
            $hash = $hash & 0xFFFFFFFF;
            if ($hash > 0x7FFFFFFF) {
                $hash -= 0x100000000;
            }
        }
        return $hash;
    }
}
