<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;
use Illuminate\Support\Facades\DB;

$event = Event::find(23);

// Let's run a test simulation of the new sync logic
function testSync($event) {
    return DB::transaction(function() use ($event) {
        $zones = is_array($event->zones) ? $event->zones : (json_decode($event->zones ?? '[]', true) ?: []);
        $splitSettings = is_array($event->quota_split_settings) ? $event->quota_split_settings : (json_decode($event->quota_split_settings ?? '[]', true) ?: []);
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

        $cSettings = is_array($event->courtesy_settings) ? $event->courtesy_settings : (json_decode($event->courtesy_settings ?? '[]', true) ?: []);
        $isCourtesyActive = !empty($cSettings['enabled']) || !empty($splitSettings['courtesy_global_enabled']);
        $courtesyConfigMap = [];
        if (!empty($cSettings['zones']) && is_array($cSettings['zones'])) {
            foreach ($cSettings['zones'] as $cz) {
                if (!empty($cz['name'])) {
                    $clean = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $cz['name'])));
                    $courtesyConfigMap[$clean] = $cz;
                }
            }
        }

        // Purgar boletos digitales vacíos sin venta
        EventTicket::where('event_id', $event->id)
            ->whereNull('ticket_sale_id')
            ->whereIn('ticket_type', ['digital', 'cortesia_digital'])
            ->delete();

        $processedCleanZones = [];

        foreach ($zones as $idx => $zone) {
            $name = strtoupper(trim((string) ($zone['name'] ?? '')));
            if (in_array($name, ['ESCENARIO', 'TARIMA']) || str_contains($name, 'ESCENARIO') || str_contains($name, 'TARIMA')) {
                continue;
            }

            $zoneName = trim($zone['name'] ?? ('Zona ' . ($idx + 1)));
            $cleanZoneUpper = strtoupper(trim(preg_replace('/\s*\([^)]+\)/', '', $zoneName)));
            $processedCleanZones[$cleanZoneUpper] = true;

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

            // 1. REGULAR PHYSICAL TICKETS
            // Obtener boletos vendidos de esta zona
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

            // Obtener boletos no vendidos existentes
            $unsoldPhysTickets = EventTicket::where('event_id', $event->id)
                ->where('ticket_type', 'fisica')
                ->whereNull('ticket_sale_id')
                ->where(function($q) use ($zoneName, $cleanZoneUpper) {
                    $q->where('zone_name', $zoneName)
                      ->orWhereRaw("UPPER(TRIM(zone_name)) = ?", [$cleanZoneUpper])
                      ->orWhere('zone_name', 'LIKE', "{$cleanZoneUpper} (%");
                })
                ->orderBy('id', 'asc')
                ->get();

            if ($targetPhysCap <= 0) {
                // Eliminar todos los boletos físicos no vendidos de esta zona
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
                    }
                }

                // Eliminar cualquier sobrante no utilizado
                foreach ($unsoldQueue as $extraT) {
                    $extraT->delete();
                }
            }
        }
    });
}

testSync($event);

echo "SYNC COMPLETED!\n";

$tickets = EventTicket::where('event_id', 23)->orderBy('id', 'asc')->get();
echo "TOTAL TICKETS NOW: " . $tickets->count() . "\n";
foreach ($tickets->groupBy('zone_name') as $zone => $zt) {
    echo "Zone '{$zone}': total = {$zt->count()}\n";
    foreach ($zt->groupBy('ticket_type') as $type => $g) {
        $min = $g->min('ticket_number');
        $max = $g->max('ticket_number');
        $prices = $g->pluck('unit_price')->unique()->implode(', ');
        echo "   - type: {$type}, count: {$g->count()}, numbers: {$min}..{$max}, prices: {$prices}\n";
    }
}
