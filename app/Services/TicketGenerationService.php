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

            $splitSettings = is_array($event->quota_split_settings) 
                ? $event->quota_split_settings 
                : (json_decode($event->quota_split_settings ?? '[]', true) ?: []);
            $isSplitActive = !empty($splitSettings['enabled']);

            $createdCount = 0;
            $existingTickets = EventTicket::where('event_id', $event->id)->get();

            // Mapa de butacas existentes indexadas por código normalizado: ej "A1", "F10"
            $existingNumberedSeats = [];
            // Conteo de boletos existentes por zona base no numerada
            $existingGeneralCounts = [];
            $existingGeneralPhysCounts = [];
            $existingGeneralVirtCounts = [];
            $existingGeneralCortCounts = [];

            foreach ($existingTickets as $et) {
                $rawZone = $et->zone_name ?: '';
                $cleanZone = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $rawZone)));
                $tType = $et->ticket_type ?: 'fisica';

                if (preg_match('/\(([^)]+)\)/', $rawZone, $matches)) {
                    $seatCode = function_exists('formatShortSeatCode') ? strtoupper(trim(formatShortSeatCode($matches[1]))) : strtoupper(trim($matches[1]));
                    $existingNumberedSeats["{$cleanZone}___{$seatCode}"] = $et;
                } else {
                    $existingGeneralCounts[$cleanZone] = ($existingGeneralCounts[$cleanZone] ?? 0) + 1;
                }

                if ($tType === 'digital') {
                    $existingGeneralVirtCounts[$cleanZone] = ($existingGeneralVirtCounts[$cleanZone] ?? 0) + 1;
                } elseif ($tType === 'cortesia' || str_contains($cleanZone, 'CORTESÍA') || str_contains($cleanZone, 'CORTESIA')) {
                    $existingGeneralCortCounts[$cleanZone] = ($existingGeneralCortCounts[$cleanZone] ?? 0) + 1;
                } else {
                    $existingGeneralPhysCounts[$cleanZone] = ($existingGeneralPhysCounts[$cleanZone] ?? 0) + 1;
                }
            }

            if ($isSplitActive) {
                // =========================================================================
                // MODO 1: DIVISIÓN ACTIVA DE CUPO FÍSICO VS DIGITAL (CORRELATIVOS INDEPENDIENTES)
                // =========================================================================
                $maxPhys = (int) EventTicket::where('event_id', $event->id)->where('ticket_type', 'fisica')->max('ticket_number') ?: 0;
                $maxVirt = (int) EventTicket::where('event_id', $event->id)->where('ticket_type', 'digital')->max('ticket_number') ?: 0;
                $maxPhysCort = (int) EventTicket::where('event_id', $event->id)->where('ticket_type', 'cortesia')->where('source', '!=', 'web_checkout')->max('ticket_number') ?: 0;
                $maxVirtCort = (int) EventTicket::where('event_id', $event->id)->where(function($q) {
                    $q->where('ticket_type', 'cortesia_digital')
                      ->orWhere(function($sq) {
                          $sq->where('ticket_type', 'cortesia')->where('source', 'web_checkout');
                      });
                })->max('ticket_number') ?: 0;

                $nextPhysCorrelative = $maxPhys + 1;
                $nextVirtCorrelative = $maxVirt + 1;
                $nextPhysCortCorrelative = $maxPhysCort + 1;
                $nextVirtCortCorrelative = $maxVirtCort + 1;

                $zoneSplitMap = [];
                if (!empty($splitSettings['zones']) && is_array($splitSettings['zones'])) {
                    foreach ($splitSettings['zones'] as $sz) {
                        if (!empty($sz['name'])) {
                            $clean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $sz['name'])));
                            $zoneSplitMap[$clean] = $sz;
                        }
                    }
                }

                foreach ($zones as $idx => $zone) {
                    $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                    $cleanZoneUpper = strtoupper($zoneName);

                    if (in_array($cleanZoneUpper, ['ESCENARIO', 'TARIMA']) || ($zone['capacity_type'] ?? '') === 'Escenario' || ($zone['type'] ?? '') === 'stage') {
                        continue;
                    }

                    $zonePrice = isset($zone['price']) ? (float)$zone['price'] : 0.00;
                    $targetTotalCapacity = isset($zone['capacity']) ? (int)$zone['capacity'] : 0;

                    $szConfig = $zoneSplitMap[$cleanZoneUpper] ?? null;
                    $targetPhysCap = (isset($szConfig['physical']) && is_numeric($szConfig['physical'])) 
                        ? (int)$szConfig['physical'] 
                        : (isset($zone['physical_capacity']) && is_numeric($zone['physical_capacity']) ? (int)$zone['physical_capacity'] : $targetTotalCapacity);

                    $targetVirtCap = (isset($szConfig['virtual']) && is_numeric($szConfig['virtual'])) 
                        ? (int)$szConfig['virtual'] 
                        : (isset($zone['virtual_capacity']) && is_numeric($zone['virtual_capacity']) ? (int)$zone['virtual_capacity'] : max(0, $targetTotalCapacity - $targetPhysCap));

                    $seats = isset($zone['seats']) && is_array($zone['seats']) ? $zone['seats'] : [];

                    if (!empty($seats)) {
                        // ZONA CON BUTACAS NUMERADAS: RECONCILIAR CUPO FÍSICO VS DIGITAL
                        $assignedPhys = 0;
                        foreach ($seats as $seat) {
                            $seatCode = function_exists('formatShortSeatCode') ? strtoupper(trim(formatShortSeatCode($seat))) : strtoupper(trim($seat['number'] ?? $seat['label'] ?? (($seat['row'] ?? '') . ($seat['col'] ?? ''))));
                            if (empty($seatCode)) continue;

                            $seatKey = "{$cleanZoneUpper}___{$seatCode}";
                            $existingTicket = $existingNumberedSeats[$seatKey] ?? null;
                            $shouldBePhys = ($assignedPhys < $targetPhysCap);

                            if ($existingTicket) {
                                // Butaca ya existe en BD: respetar si ya está vendida
                                if (!empty($existingTicket->ticket_sale_id) || $existingTicket->status === 'sold') {
                                    $isSoldPhys = ($existingTicket->ticket_type === 'fisica' || $existingTicket->source !== 'web_checkout');
                                    if ($isSoldPhys) {
                                        $assignedPhys++;
                                    }
                                } else {
                                    // Sincronizar butaca no vendida según la división física vs digital
                                    if ($shouldBePhys) {
                                        if ($existingTicket->ticket_type !== 'fisica' || $existingTicket->source === 'web_checkout') {
                                            $existingTicket->update([
                                                'ticket_type' => 'fisica',
                                                'source' => 'pdf_batch',
                                                'buyer_name' => 'Talonario Físico / Taquilla',
                                            ]);
                                        }
                                        $assignedPhys++;
                                    } else {
                                        if ($existingTicket->ticket_type !== 'digital' || $existingTicket->source !== 'web_checkout') {
                                            $existingTicket->update([
                                                'ticket_type' => 'digital',
                                                'source' => 'web_checkout',
                                                'buyer_name' => 'Boleto Digital Web',
                                            ]);
                                        }
                                    }
                                }
                                continue;
                            }

                            // Si no existía en BD, crear la nueva butaca
                            if ($shouldBePhys) {
                                $ticketNumber = $nextPhysCorrelative++;
                                $ticketType = 'fisica';
                                $source = 'pdf_batch';
                                $buyerDesc = 'Talonario Físico / Taquilla';
                                $assignedPhys++;
                            } else {
                                $ticketNumber = $nextVirtCorrelative++;
                                $ticketType = 'digital';
                                $source = 'web_checkout';
                                $buyerDesc = 'Boleto Digital Web';
                            }

                            $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                            $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_' . substr($ticketType, 0, 1) . '_', true) . $event->id . $ticketNumber), 0, 8));
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
                                'buyer_name' => $buyerDesc,
                                'buyer_dni' => '00000000',
                                'source' => $source,
                                'ticket_type' => $ticketType,
                                'is_used' => false,
                                'status' => 'valid',
                            ]);

                            $existingNumberedSeats[$seatKey] = $newTicket;
                            $createdCount++;
                        }
                    } else {
                        // ZONA GENERAL NO NUMERADA: RECONCILIAR CUPO FÍSICO Y DIGITAL
                        $soldPhysCount = EventTicket::where('event_id', $event->id)
                            ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                                $q->where('zone_name', $zoneName)
                                  ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper]);
                            })
                            ->whereNotNull('ticket_sale_id')
                            ->where(function($q) {
                                $q->where('ticket_type', 'fisica')->orWhere('source', '!=', 'web_checkout');
                            })
                            ->count();

                        $soldVirtCount = EventTicket::where('event_id', $event->id)
                            ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                                $q->where('zone_name', $zoneName)
                                  ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper]);
                            })
                            ->whereNotNull('ticket_sale_id')
                            ->where(function($q) {
                                $q->where('ticket_type', 'digital')->orWhere('source', 'web_checkout');
                            })
                            ->count();

                        $neededUnsoldPhys = max(0, $targetPhysCap - $soldPhysCount);
                        $neededUnsoldVirt = max(0, $targetVirtCap - $soldVirtCount);

                        $unsoldGeneralTickets = EventTicket::where('event_id', $event->id)
                            ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                                $q->where('zone_name', $zoneName)
                                  ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper]);
                            })
                            ->whereNull('ticket_sale_id')
                            ->orderBy('id', 'asc')
                            ->get();

                        $assignedUnsoldPhys = 0;
                        $assignedUnsoldVirt = 0;

                        foreach ($unsoldGeneralTickets as $t) {
                            if ($assignedUnsoldPhys < $neededUnsoldPhys) {
                                if ($t->ticket_type !== 'fisica' || $t->source === 'web_checkout') {
                                    $t->update([
                                        'ticket_type' => 'fisica',
                                        'source' => 'pdf_batch',
                                        'buyer_name' => 'Talonario Físico / Taquilla',
                                    ]);
                                }
                                $assignedUnsoldPhys++;
                            } elseif ($assignedUnsoldVirt < $neededUnsoldVirt) {
                                if ($t->ticket_type !== 'digital' || $t->source !== 'web_checkout') {
                                    $t->update([
                                        'ticket_type' => 'digital',
                                        'source' => 'web_checkout',
                                        'buyer_name' => 'Boleto Digital Web',
                                    ]);
                                }
                                $assignedUnsoldVirt++;
                            } else {
                                // Boletos sobrantes que exceden el aforo actual configurado
                                $t->delete();
                            }
                        }

                        // Si aún faltan entradas físicas por crear
                        $missingPhys = max(0, $neededUnsoldPhys - $assignedUnsoldPhys);
                        for ($k = 0; $k < $missingPhys; $k++) {
                            $ticketNumber = $nextPhysCorrelative++;
                            $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                            $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_f_', true) . $event->id . $ticketNumber), 0, 8));
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
                                'ticket_type' => 'fisica',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);
                            $createdCount++;
                        }

                        // Si aún faltan entradas virtuales por crear
                        $missingVirt = max(0, $neededUnsoldVirt - $assignedUnsoldVirt);
                        for ($k = 0; $k < $missingVirt; $k++) {
                            $ticketNumber = $nextVirtCorrelative++;
                            $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                            $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_d_', true) . $event->id . $ticketNumber), 0, 8));
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
                                'buyer_name' => 'Boleto Digital Web',
                                'buyer_dni' => '00000000',
                                'source' => 'web_checkout',
                                'ticket_type' => 'digital',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);
                            $createdCount++;
                        }
                    }
                }

                // CORTESÍAS CON DIVISIÓN ACTIVA (CORRELATIVOS PROPIOS: FÍSICO Y DIGITAL)
                $cSettings = is_array($event->courtesy_settings) 
                    ? $event->courtesy_settings 
                    : (json_decode($event->courtesy_settings ?? '[]', true) ?: []);
                $isCourtesyActive = !empty($cSettings['enabled']) || !empty($splitSettings['courtesy_global_enabled']) || !empty($splitSettings['enabled']);

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

                    foreach ($zones as $idx => $zone) {
                        $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                        $cleanZoneUpper = strtoupper($zoneName);

                        if (in_array($cleanZoneUpper, ['ESCENARIO', 'TARIMA']) || ($zone['capacity_type'] ?? '') === 'Escenario' || ($zone['type'] ?? '') === 'stage') {
                            continue;
                        }

                        $szConfig = $zoneSplitMap[$cleanZoneUpper] ?? null;
                        $czConfig = $courtesyConfigMap[$cleanZoneUpper] ?? null;

                        // Determinar cupos de cortesía física y digital
                        $physCortCap = 0;
                        $virtCortCap = 0;

                        if (!empty($szConfig)) {
                            if (isset($szConfig['courtesy_physical']) && is_numeric($szConfig['courtesy_physical'])) {
                                $physCortCap = (int)$szConfig['courtesy_physical'];
                            }
                            if (isset($szConfig['courtesy_virtual']) && is_numeric($szConfig['courtesy_virtual'])) {
                                $virtCortCap = (int)$szConfig['courtesy_virtual'];
                            }
                        }

                        // Fallback a courtesy_settings si no vino en splitSettings
                        if ($physCortCap === 0 && $virtCortCap === 0 && !empty($czConfig)) {
                            if (isset($czConfig['physical_stock']) && is_numeric($czConfig['physical_stock'])) {
                                $physCortCap = (int)$czConfig['physical_stock'];
                            }
                            if (isset($czConfig['virtual_stock']) && is_numeric($czConfig['virtual_stock'])) {
                                $virtCortCap = (int)$czConfig['virtual_stock'];
                            }
                            if ($physCortCap === 0 && $virtCortCap === 0 && !empty($czConfig['stock'])) {
                                $physCortCap = (int)$czConfig['stock'];
                            }
                        }

                        $courtesyZoneName = "CORTESÍA - {$zoneName}";

                        // Reconciliar boletos de cortesía no vendidos existentes
                        $unsoldCourtesyTickets = EventTicket::where('event_id', $event->id)
                            ->where(function($q) use ($courtesyZoneName) {
                                $q->where('zone_name', $courtesyZoneName)
                                  ->orWhere('zone_name', 'LIKE', '%' . $courtesyZoneName . '%');
                            })
                            ->whereNull('ticket_sale_id')
                            ->orderBy('id', 'asc')
                            ->get();

                        $assignedUnsoldPhysCort = 0;
                        $assignedUnsoldVirtCort = 0;

                        foreach ($unsoldCourtesyTickets as $t) {
                            if ($assignedUnsoldPhysCort < $physCortCap) {
                                if ($t->ticket_type !== 'cortesia' || $t->source === 'web_checkout') {
                                    $t->update([
                                        'ticket_type' => 'cortesia',
                                        'source' => 'pdf_batch',
                                        'buyer_name' => 'Pase de Cortesía Físico',
                                        'unit_price' => 0.00,
                                    ]);
                                }
                                $assignedUnsoldPhysCort++;
                            } elseif ($assignedUnsoldVirtCort < $virtCortCap) {
                                if ($t->ticket_type !== 'cortesia_digital' || $t->source !== 'web_checkout') {
                                    $t->update([
                                        'ticket_type' => 'cortesia_digital',
                                        'source' => 'web_checkout',
                                        'buyer_name' => 'Pase de Cortesía Web',
                                        'unit_price' => 0.00,
                                    ]);
                                }
                                $assignedUnsoldVirtCort++;
                            } else {
                                // Boletos de cortesía sobrantes que ya no aplican
                                $t->delete();
                            }
                        }

                        // 1. Generar Cortesías Físicas faltantes (para planchas y taquilla)
                        $missingPhysCort = max(0, $physCortCap - $assignedUnsoldPhysCort);
                        for ($k = 0; $k < $missingPhysCort; $k++) {
                            $ticketNumber = $nextPhysCortCorrelative++;
                            $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                            $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_cp_', true) . $event->id . $ticketNumber), 0, 8));
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
                                'buyer_name' => 'Pase de Cortesía Físico',
                                'buyer_dni' => '00000000',
                                'source' => 'pdf_batch',
                                'ticket_type' => 'cortesia',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);
                            $createdCount++;
                        }

                        // 2. Generar Cortesías Digitales faltantes (para emisión / compras web)
                        $missingVirtCort = max(0, $virtCortCap - $assignedUnsoldVirtCort);
                        for ($k = 0; $k < $missingVirtCort; $k++) {
                            $ticketNumber = $nextVirtCortCorrelative++;
                            $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                            $validationHash = 'VG' . strtoupper(substr(md5(uniqid('vg_cd_', true) . $event->id . $ticketNumber), 0, 8));
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
                                'buyer_name' => 'Pase de Cortesía Web',
                                'buyer_dni' => '00000000',
                                'source' => 'web_checkout',
                                'ticket_type' => 'cortesia_digital',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);
                            $createdCount++;
                        }
                    }
                } else {
                    // Si cortesías están desactivadas, podar cortesías no vendidas
                    EventTicket::where('event_id', $event->id)
                        ->whereNull('ticket_sale_id')
                        ->where(function($q) {
                            $q->whereIn('ticket_type', ['cortesia', 'cortesia_digital'])
                              ->orWhere('zone_name', 'LIKE', 'CORTESÍA%')
                              ->orWhere('zone_name', 'LIKE', 'CORTESIA%');
                        })
                        ->delete();
                }

            } else {
                // =========================================================================
                // MODO 2: COMPORTAMIENTO ORIGINAL UNIFICADO (100% RETROCOMPATIBLE)
                // =========================================================================
                $maxCorrelative = (int) EventTicket::where('event_id', $event->id)->max('ticket_number') ?: 0;
                $nextCorrelative = $maxCorrelative + 1;

                foreach ($zones as $idx => $zone) {
                    $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                    $cleanZoneUpper = strtoupper($zoneName);

                    if (in_array($cleanZoneUpper, ['ESCENARIO', 'TARIMA']) || ($zone['capacity_type'] ?? '') === 'Escenario' || ($zone['type'] ?? '') === 'stage') {
                        continue;
                    }

                    $zonePrice = isset($zone['price']) ? (float)$zone['price'] : 0.00;
                    $targetCapacity = isset($zone['capacity']) ? (int)$zone['capacity'] : 0;
                    $seats = isset($zone['seats']) && is_array($zone['seats']) ? $zone['seats'] : [];

                    if (!empty($seats)) {
                        foreach ($seats as $seat) {
                            $seatCode = function_exists('formatShortSeatCode') ? strtoupper(trim(formatShortSeatCode($seat))) : strtoupper(trim($seat['number'] ?? $seat['label'] ?? (($seat['row'] ?? '') . ($seat['col'] ?? ''))));
                            if (empty($seatCode)) continue;

                            $seatKey = "{$cleanZoneUpper}___{$seatCode}";
                            if (isset($existingNumberedSeats[$seatKey])) continue;

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
                                'ticket_type' => 'fisica',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);

                            $existingNumberedSeats[$seatKey] = $newTicket;
                            $createdCount++;
                        }
                    } else {
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
                                'ticket_type' => 'fisica',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);

                            $createdCount++;
                            $existingGeneralCounts[$cleanZoneUpper] = ($existingGeneralCounts[$cleanZoneUpper] ?? 0) + 1;
                        }
                    }
                }

                // CORTESÍAS EN MODO UNIFICADO (CONTINÚAN EL CORRELATIVO GENERAL)
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

                        if ($czCap <= 0) continue;

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
                                'ticket_type' => 'cortesia',
                                'is_used' => false,
                                'status' => 'valid',
                            ]);

                            $createdCount++;
                            $existingGeneralCounts[$cleanCourtesyUpper] = ($existingGeneralCounts[$cleanCourtesyUpper] ?? 0) + 1;
                        }
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
     * Regenera nuevos códigos QR, hashes de validación y números correlativos secuenciales
     * para las ventas de POS / Taquilla de un evento específico.
     *
     * Actualiza simultáneamente la tabla `ticket_sales` (campo tickets_data) y `event_tickets`,
     * garantizando que las reimpresiones en POS y las validaciones de escaneo en puerta
     * funcionen coordinadamente con los nuevos códigos generados.
     */
    public static function regeneratePosSalesQrs(Event $event, int $startCorrelative = 1, string $scope = 'pos_only'): array
    {
        @set_time_limit(300);

        return DB::transaction(function () use ($event, $startCorrelative, $scope) {
            $query = \App\Models\TicketSale::where('event_id', $event->id)
                ->where('status', '!=', 'cancelled');

            if ($scope === 'pos_only') {
                $query->where(function ($q) {
                    $q->where('seller_name', 'LIKE', '%Taquilla%')
                      ->orWhere('seller_name', 'LIKE', '%POS%')
                      ->orWhere('seller_name', 'LIKE', '%Admin%')
                      ->orWhere('payment_method', 'Efectivo')
                      ->orWhere('payment_method', 'POS')
                      ->orWhere('payment_method', 'Cortesía');
                });
            }

            $sales = $query->orderBy('id', 'asc')->get();

            if ($sales->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No se encontraron ventas ' . ($scope === 'pos_only' ? 'de POS / Taquilla' : '') . ' para el evento seleccionado.',
                    'sales_count' => 0,
                    'tickets_count' => 0,
                    'correlative_range' => 'N/A',
                    'details' => [],
                ];
            }

            $currentCorrelative = max(1, (int)$startCorrelative);
            $minCorrelative = $currentCorrelative;
            $maxCorrelative = $currentCorrelative;

            $processedSales = 0;
            $regeneratedTickets = 0;
            $details = [];

            foreach ($sales as $sale) {
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

                $linkedEventTickets = EventTicket::where('ticket_sale_id', $sale->id)
                    ->orderBy('id', 'asc')
                    ->get()
                    ->keyBy('id');
                $linkedList = $linkedEventTickets->values();

                $updatedTicketsList = [];

                foreach ($ticketsList as $i => $t) {
                    $oldNum = isset($t['ticket_number']) ? $t['ticket_number'] : (isset($t['number']) ? $t['number'] : ($i + 1));
                    $oldCode = $t['ticket_code'] ?? ('N° ' . str_pad($oldNum, 5, '0', STR_PAD_LEFT));
                    $oldHash = $t['validation_hash'] ?? ($t['hash'] ?? '');
                    $oldQr = $t['qr_payload'] ?? ($t['qr'] ?? '');

                    $newNum = $currentCorrelative++;
                    $maxCorrelative = $newNum;
                    $newTicketCode = 'N° ' . str_pad($newNum, 5, '0', STR_PAD_LEFT);

                    // Hash único fresco y payload QR oficial
                    $randomHex = strtoupper(substr(md5(uniqid('vg_pos_qr_', true) . $event->id . $sale->id . $newNum . microtime(true)), 0, 8));
                    $newValHash = 'VG' . $randomHex;
                    $newQrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$newNum}|HASH-{$newValHash}";

                    $buyerName = !empty($t['buyer_name']) ? $t['buyer_name'] : $sale->buyer_name;
                    $buyerDni = !empty($t['buyer_dni']) ? $t['buyer_dni'] : ($sale->buyer_dni ?: '00000000');
                    $zoneName = !empty($t['zone']) ? $t['zone'] : (!empty($t['zone_name']) ? $t['zone_name'] : $sale->zone_name);
                    $unitPrice = isset($t['price']) ? (float)$t['price'] : (float)$sale->unit_price;

                    // Localizar el boleto físico correspondiente en event_tickets
                    $et = null;
                    if (!empty($t['event_ticket_id']) && isset($linkedEventTickets[$t['event_ticket_id']])) {
                        $et = $linkedEventTickets[$t['event_ticket_id']];
                    } elseif (isset($linkedList[$i])) {
                        $et = $linkedList[$i];
                    } else {
                        $et = EventTicket::where('ticket_sale_id', $sale->id)
                            ->where('ticket_number', $oldNum)
                            ->first();
                    }

                    if (!$et) {
                        // Buscar si existe un boleto físico no vendido con ese correlativo en el evento
                        $et = EventTicket::where('event_id', $event->id)
                            ->where('ticket_number', $oldNum)
                            ->whereNull('ticket_sale_id')
                            ->first();
                    }

                    if ($et) {
                        $et->update([
                            'ticket_sale_id' => $sale->id,
                            'ticket_number' => $newNum,
                            'ticket_code' => $newTicketCode,
                            'validation_hash' => $newValHash,
                            'qr_payload' => $newQrPayload,
                            'buyer_name' => $buyerName,
                            'buyer_dni' => $buyerDni,
                            'unit_price' => $unitPrice,
                            'zone_name' => $zoneName,
                            'source' => 'pos_sale',
                            'status' => 'valid',
                        ]);
                    } else {
                        $et = EventTicket::create([
                            'event_id' => $event->id,
                            'ticket_sale_id' => $sale->id,
                            'ticket_number' => $newNum,
                            'ticket_code' => $newTicketCode,
                            'validation_hash' => $newValHash,
                            'qr_payload' => $newQrPayload,
                            'buyer_name' => $buyerName,
                            'buyer_dni' => $buyerDni,
                            'unit_price' => $unitPrice,
                            'zone_name' => $zoneName,
                            'source' => 'pos_sale',
                            'ticket_type' => 'fisica',
                            'is_used' => false,
                            'status' => 'valid',
                        ]);
                    }

                    $t['event_ticket_id'] = $et->id;
                    $t['ticket_number'] = $newNum;
                    $t['ticket_code'] = $newTicketCode;
                    $t['validation_hash'] = $newValHash;
                    $t['qr_payload'] = $newQrPayload;
                    $updatedTicketsList[] = $t;
                    $regeneratedTickets++;

                    $details[] = [
                        'sale_id' => $sale->id,
                        'receipt_number' => $sale->receipt_number,
                        'buyer_name' => $buyerName,
                        'zone_name' => $zoneName,
                        'old_ticket_code' => $oldCode,
                        'new_ticket_code' => $newTicketCode,
                        'old_hash' => $oldHash,
                        'new_hash' => $newValHash,
                        'new_qr' => $newQrPayload,
                        'event_ticket_id' => $et->id,
                    ];
                }

                // Guardar la estructura actualizada en tickets_data
                if ($isItemsFormat) {
                    $tData['items'] = $updatedTicketsList;
                    $sale->update(['tickets_data' => $tData]);
                } else {
                    $sale->update(['tickets_data' => $updatedTicketsList]);
                }
                $processedSales++;
            }

            // Reconciliar boletos no vendidos para que no colisionen con los correlativos asignados a ventas
            $unsoldColliding = EventTicket::where('event_id', $event->id)
                ->whereNull('ticket_sale_id')
                ->where('ticket_number', '<=', $maxCorrelative)
                ->orderBy('id', 'asc')
                ->get();

            if ($unsoldColliding->isNotEmpty()) {
                $maxExistingNum = EventTicket::where('event_id', $event->id)->max('ticket_number') ?? $maxCorrelative;
                $startShift = max($maxCorrelative + 1, $maxExistingNum + 1);

                foreach ($unsoldColliding as $ut) {
                    $shiftNum = $startShift++;
                    $shiftCode = 'N° ' . str_pad($shiftNum, 5, '0', STR_PAD_LEFT);
                    $shiftHash = 'VG' . strtoupper(substr(md5(uniqid('vg_shift_', true) . $event->id . $shiftNum), 0, 8));
                    $shiftQr = "VIVEGO|EVT-{$event->id}|TICK-{$shiftNum}|HASH-{$shiftHash}";
                    $ut->update([
                        'ticket_number' => $shiftNum,
                        'ticket_code' => $shiftCode,
                        'validation_hash' => $shiftHash,
                        'qr_payload' => $shiftQr,
                    ]);
                }
            }

            $correlativeRange = 'N° ' . str_pad($minCorrelative, 5, '0', STR_PAD_LEFT) . ' → N° ' . str_pad($maxCorrelative, 5, '0', STR_PAD_LEFT);

            return [
                'success' => true,
                'message' => 'Se regeneraron con éxito los códigos QR y correlativos de las ventas seleccionadas.',
                'sales_count' => $processedSales,
                'tickets_count' => $regeneratedTickets,
                'correlative_range' => $correlativeRange,
                'min_correlative' => $minCorrelative,
                'max_correlative' => $maxCorrelative,
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

