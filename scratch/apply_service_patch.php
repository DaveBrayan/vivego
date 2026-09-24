<?php
$servicePath = __DIR__ . '/../app/Services/TicketGenerationService.php';
$content = file_get_contents($servicePath);

$startMarker = '    public static function syncEventTickets(Event $event): array';
$endMarker = '    public static function syncSalesToEventTickets(): array';

$startPos = strpos($content, $startMarker);
$endPos = strpos($content, $endMarker);

if ($startPos === false || $endPos === false) {
    die("Markers not found!\n");
}

$newMethod = <<<'PHP'
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

            // Purgar boletos digitales o cortesías digitales vacíos sin venta
            EventTicket::where('event_id', $event->id)
                ->whereNull('ticket_sale_id')
                ->whereIn('ticket_type', ['digital', 'cortesia_digital'])
                ->delete();

            $createdCount = 0;

            $zoneSplitMap = [];
            if (!empty($splitSettings['zones']) && is_array($splitSettings['zones'])) {
                foreach ($splitSettings['zones'] as $sz) {
                    if (!empty($sz['name'])) {
                        $clean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $sz['name'])));
                        $zoneSplitMap[$clean] = $sz;
                    }
                }
            }

            $cSettings = is_array($event->courtesy_settings) 
                ? $event->courtesy_settings 
                : (json_decode($event->courtesy_settings ?? '[]', true) ?: []);
            $isCourtesyActive = !empty($cSettings['enabled']) || !empty($splitSettings['courtesy_global_enabled']) || !empty($splitSettings['enabled']);
            $courtesyConfigMap = [];
            if (!empty($cSettings['zones']) && is_array($cSettings['zones'])) {
                foreach ($cSettings['zones'] as $cz) {
                    if (!empty($cz['name'])) {
                        $clean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $cz['name'])));
                        $courtesyConfigMap[$clean] = $cz;
                    }
                }
            }

            // 1. RECONCILIAR ENTRADAS FÍSICAS REGULARES POR ZONA
            foreach ($zones as $idx => $zone) {
                if (self::isStageZone($zone)) {
                    continue;
                }

                $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                $cleanZoneUpper = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $zoneName)));

                $zonePrice = isset($zone['price']) ? (float)$zone['price'] : 0.00;
                $targetTotalCap = isset($zone['capacity']) ? (int)$zone['capacity'] : 0;

                $szConfig = $zoneSplitMap[$cleanZoneUpper] ?? null;
                if ($isSplitActive) {
                    $targetPhysCap = (isset($szConfig['physical']) && is_numeric($szConfig['physical']))
                        ? (int)$szConfig['physical']
                        : (isset($zone['physical_capacity']) && is_numeric($zone['physical_capacity']) ? (int)$zone['physical_capacity'] : $targetTotalCap);
                } elseif ($event->sales_type === 'virtual') {
                    $targetPhysCap = 0;
                } else {
                    $targetPhysCap = $targetTotalCap;
                }

                $seats = isset($zone['seats']) && is_array($zone['seats']) ? $zone['seats'] : [];

                if (!empty($seats)) {
                    // ZONA CON BUTACAS NUMERADAS
                    $assignedPhys = 0;
                    $existingSeatsInDb = EventTicket::where('event_id', $event->id)
                        ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                            $q->where('zone_name', $zoneName)
                              ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper])
                              ->orWhere('zone_name', 'LIKE', "{$cleanZoneUpper} (%");
                        })
                        ->get()
                        ->keyBy(function($item) {
                            if (preg_match('/\(([^)]+)\)/', $item->zone_name, $m)) {
                                return strtoupper(trim(function_exists('formatShortSeatCode') ? formatShortSeatCode($m[1]) : $m[1]));
                            }
                            return $item->id;
                        });

                    foreach ($seats as $sIdx => $seat) {
                        $seatCode = function_exists('formatShortSeatCode') ? strtoupper(trim(formatShortSeatCode($seat))) : strtoupper(trim($seat['number'] ?? $seat['label'] ?? (($seat['row'] ?? '') . ($seat['col'] ?? ''))));
                        if (empty($seatCode)) continue;

                        $shouldBePhys = ($assignedPhys < $targetPhysCap);
                        $existingT = $existingSeatsInDb->get($seatCode);

                        if ($shouldBePhys) {
                            $assignedPhys++;
                            $ticketNumber = $assignedPhys;
                            $ticketCode = 'N° ' . str_pad($ticketNumber, 5, '0', STR_PAD_LEFT);
                            $valHash = 'VG' . strtoupper(substr(md5('vg_seat_' . $event->id . '_' . $cleanZoneUpper . '_' . $seatCode), 0, 8));
                            $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$ticketNumber}|HASH-{$valHash}";
                            $fullZoneName = function_exists('formatZoneWithSeat') ? formatZoneWithSeat($zoneName, $seat) : "{$zoneName} ({$seatCode})";

                            if ($existingT) {
                                if (empty($existingT->ticket_sale_id)) {
                                    $existingT->update([
                                        'ticket_code' => $ticketCode,
                                        'ticket_number' => $ticketNumber,
                                        'zone_name' => $fullZoneName,
                                        'unit_price' => $zonePrice,
                                        'qr_payload' => $qrPayload,
                                        'validation_hash' => $valHash,
                                        'buyer_name' => 'Talonario Físico / Taquilla',
                                        'buyer_dni' => '00000000',
                                        'source' => 'pdf_batch',
                                        'ticket_type' => 'fisica',
                                        'status' => 'valid',
                                        'is_used' => false,
                                    ]);
                                }
                            } else {
                                EventTicket::create([
                                    'event_id' => $event->id,
                                    'ticket_sale_id' => null,
                                    'ticket_code' => $ticketCode,
                                    'ticket_number' => $ticketNumber,
                                    'zone_name' => $fullZoneName,
                                    'unit_price' => $zonePrice,
                                    'qr_payload' => $qrPayload,
                                    'validation_hash' => $valHash,
                                    'buyer_name' => 'Talonario Físico / Taquilla',
                                    'buyer_dni' => '00000000',
                                    'source' => 'pdf_batch',
                                    'ticket_type' => 'fisica',
                                    'status' => 'valid',
                                    'is_used' => false,
                                ]);
                                $createdCount++;
                            }
                        } else {
                            if ($existingT && empty($existingT->ticket_sale_id)) {
                                $existingT->delete();
                            }
                        }
                    }
                } else {
                    // ZONA GENERAL NO NUMERADA: RECONCILIACIÓN EXACTA 1..N
                    $soldPhysTickets = EventTicket::where('event_id', $event->id)
                        ->where('ticket_type', 'fisica')
                        ->whereNotNull('ticket_sale_id')
                        ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                            $q->where('zone_name', $zoneName)
                              ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper])
                              ->orWhere('zone_name', 'LIKE', "{$cleanZoneUpper} (%");
                        })
                        ->get();
                    $soldNumbers = $soldPhysTickets->pluck('ticket_number')->filter()->map(fn($n) => (int)$n)->toArray();

                    $unsoldPhysTickets = EventTicket::where('event_id', $event->id)
                        ->where(function($q) {
                            $q->where('ticket_type', 'fisica')
                              ->orWhereNull('ticket_type');
                        })
                        ->whereNull('ticket_sale_id')
                        ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                            $q->where('zone_name', $zoneName)
                              ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper]);
                        })
                        ->orderBy('id', 'asc')
                        ->get();

                    if ($targetPhysCap <= 0) {
                        foreach ($unsoldPhysTickets as $upt) {
                            $upt->delete();
                        }
                    } else {
                        $unsoldQueue = $unsoldPhysTickets->all();
                        $neededNumbers = [];
                        for ($num = 1; $num <= $targetPhysCap; $num++) {
                            if (!in_array($num, $soldNumbers)) {
                                $neededNumbers[] = $num;
                            }
                        }

                        foreach ($neededNumbers as $ticketNum) {
                            $ticketCode = 'N° ' . str_pad($ticketNum, 5, '0', STR_PAD_LEFT);
                            $valHash = 'VG' . strtoupper(substr(md5('vg_f_' . $event->id . '_' . $cleanZoneUpper . '_' . $ticketNum), 0, 8));
                            $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$ticketNum}|HASH-{$valHash}";

                            if (!empty($unsoldQueue)) {
                                $t = array_shift($unsoldQueue);
                                $t->update([
                                    'ticket_code' => $ticketCode,
                                    'ticket_number' => $ticketNum,
                                    'zone_name' => $zoneName,
                                    'unit_price' => $zonePrice,
                                    'qr_payload' => $qrPayload,
                                    'validation_hash' => $valHash,
                                    'buyer_name' => 'Talonario Físico / Taquilla',
                                    'buyer_dni' => '00000000',
                                    'source' => 'pdf_batch',
                                    'ticket_type' => 'fisica',
                                    'status' => 'valid',
                                    'is_used' => false,
                                ]);
                            } else {
                                EventTicket::create([
                                    'event_id' => $event->id,
                                    'ticket_sale_id' => null,
                                    'ticket_code' => $ticketCode,
                                    'ticket_number' => $ticketNum,
                                    'zone_name' => $zoneName,
                                    'unit_price' => $zonePrice,
                                    'qr_payload' => $qrPayload,
                                    'validation_hash' => $valHash,
                                    'buyer_name' => 'Talonario Físico / Taquilla',
                                    'buyer_dni' => '00000000',
                                    'source' => 'pdf_batch',
                                    'ticket_type' => 'fisica',
                                    'status' => 'valid',
                                    'is_used' => false,
                                ]);
                                $createdCount++;
                            }
                        }

                        // Eliminar boletos sobrantes no utilizados
                        foreach ($unsoldQueue as $extraT) {
                            $extraT->delete();
                        }
                    }
                }
            }

            // 2. RECONCILIAR ENTRADAS FÍSICAS DE CORTESÍA POR ZONA
            if ($isCourtesyActive) {
                foreach ($zones as $idx => $zone) {
                    if (self::isStageZone($zone)) {
                        continue;
                    }

                    $zoneName = trim($zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1)));
                    $cleanZoneUpper = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $zoneName)));

                    $szConfig = $zoneSplitMap[$cleanZoneUpper] ?? null;
                    $czConfig = $courtesyConfigMap[$cleanZoneUpper] ?? null;

                    $physCortCap = 0;
                    if (!empty($szConfig)) {
                        if (isset($szConfig['courtesy_physical']) && is_numeric($szConfig['courtesy_physical'])) {
                            $physCortCap = (int)$szConfig['courtesy_physical'];
                        }
                    }
                    if ($physCortCap === 0 && !empty($czConfig)) {
                        if (isset($czConfig['physical_stock']) && is_numeric($czConfig['physical_stock'])) {
                            $physCortCap = (int)$czConfig['physical_stock'];
                        } elseif (isset($czConfig['stock']) && is_numeric($czConfig['stock'])) {
                            $physCortCap = (int)$czConfig['stock'];
                        }
                    }

                    $courtesyZoneName = "CORTESÍA - {$zoneName}";
                    $cleanCourtesyUpper = strtoupper($courtesyZoneName);

                    $soldCortTickets = EventTicket::where('event_id', $event->id)
                        ->where('ticket_type', 'cortesia')
                        ->whereNotNull('ticket_sale_id')
                        ->where(function($q) use ($courtesyZoneName, $cleanCourtesyUpper) {
                            $q->where('zone_name', $courtesyZoneName)
                              ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanCourtesyUpper]);
                        })
                        ->get();
                    $soldCortNumbers = $soldCortTickets->pluck('ticket_number')->filter()->map(fn($n) => (int)$n)->toArray();

                    $unsoldCortTickets = EventTicket::where('event_id', $event->id)
                        ->where('ticket_type', 'cortesia')
                        ->whereNull('ticket_sale_id')
                        ->where(function($q) use ($courtesyZoneName, $cleanCourtesyUpper) {
                            $q->where('zone_name', $courtesyZoneName)
                              ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanCourtesyUpper]);
                        })
                        ->orderBy('id', 'asc')
                        ->get();

                    if ($physCortCap <= 0) {
                        foreach ($unsoldCortTickets as $uct) {
                            $uct->delete();
                        }
                    } else {
                        $unsoldCortQueue = $unsoldCortTickets->all();
                        $neededCortNumbers = [];
                        for ($num = 1; $num <= $physCortCap; $num++) {
                            if (!in_array($num, $soldCortNumbers)) {
                                $neededCortNumbers[] = $num;
                            }
                        }

                        foreach ($neededCortNumbers as $ticketNum) {
                            $ticketCode = 'N° ' . str_pad($ticketNum, 5, '0', STR_PAD_LEFT);
                            $valHash = 'VG' . strtoupper(substr(md5('vg_cp_' . $event->id . '_' . $cleanCourtesyUpper . '_' . $ticketNum), 0, 8));
                            $qrPayload = "VIVEGO|EVT-{$event->id}|TICK-{$ticketNum}|HASH-{$valHash}";

                            if (!empty($unsoldCortQueue)) {
                                $t = array_shift($unsoldCortQueue);
                                $t->update([
                                    'ticket_code' => $ticketCode,
                                    'ticket_number' => $ticketNum,
                                    'zone_name' => $courtesyZoneName,
                                    'unit_price' => 0.00,
                                    'qr_payload' => $qrPayload,
                                    'validation_hash' => $valHash,
                                    'buyer_name' => 'Pase de Cortesía Físico',
                                    'buyer_dni' => '00000000',
                                    'source' => 'pdf_batch',
                                    'ticket_type' => 'cortesia',
                                    'status' => 'valid',
                                    'is_used' => false,
                                ]);
                            } else {
                                EventTicket::create([
                                    'event_id' => $event->id,
                                    'ticket_sale_id' => null,
                                    'ticket_code' => $ticketCode,
                                    'ticket_number' => $ticketNum,
                                    'zone_name' => $courtesyZoneName,
                                    'unit_price' => 0.00,
                                    'qr_payload' => $qrPayload,
                                    'validation_hash' => $valHash,
                                    'buyer_name' => 'Pase de Cortesía Físico',
                                    'buyer_dni' => '00000000',
                                    'source' => 'pdf_batch',
                                    'ticket_type' => 'cortesia',
                                    'status' => 'valid',
                                    'is_used' => false,
                                ]);
                                $createdCount++;
                            }
                        }

                        foreach ($unsoldCortQueue as $extraCortT) {
                            $extraCortT->delete();
                        }
                    }
                }
            } else {
                EventTicket::where('event_id', $event->id)
                    ->whereNull('ticket_sale_id')
                    ->where(function($q) {
                        $q->whereIn('ticket_type', ['cortesia', 'cortesia_digital'])
                          ->orWhere('zone_name', 'LIKE', 'CORTESÍA%')
                          ->orWhere('zone_name', 'LIKE', 'CORTESIA%');
                    })
                    ->delete();
            }

            $totalNow = EventTicket::where('event_id', $event->id)->count();

            return [
                'created' => $createdCount,
                'total' => $totalNow,
            ];
        });
    }

PHP;

$updatedContent = substr($content, 0, $startPos) . $newMethod . "\n" . substr($content, $endPos);
file_put_contents($servicePath, $updatedContent);
echo "TicketGenerationService updated successfully!\n";
