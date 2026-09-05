<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TicketSale;
use App\Models\EventTicket;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

function jsHashCode($str) {
    $hash = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $hash = (($hash << 5) - $hash) + ord($str[$i]);
        $hash = $hash & 0xFFFFFFFF;
        if ($hash > 0x7FFFFFFF) {
            $hash -= 0x100000000;
        }
    }
    return $hash;
}

$results = DB::transaction(function() {
    $sales = TicketSale::orderBy('id', 'asc')->get();
    $updatedTickets = 0;
    $createdTickets = 0;
    $syncedSales = 0;
    $skippedSales = 0;
    $details = [];

    // Cache existing events
    $validEventIds = Event::pluck('id')->flip()->toArray();

    foreach ($sales as $sale) {
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
            $numericItems = array_filter($tData, function($k) { return is_numeric($k); }, ARRAY_FILTER_USE_KEY);
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

            $ticketCode = !empty($t['ticket_code']) ? $t['ticket_code'] : ('N° ' . str_pad($ticketNum, 5, '0', STR_PAD_LEFT));
            $zoneName = !empty($t['zone']) ? $t['zone'] : (!empty($t['zone_name']) ? $t['zone_name'] : $sale->zone_name);
            $unitPrice = isset($t['price']) ? (float)$t['price'] : (float)$sale->unit_price;
            $buyerName = !empty($t['buyer_name']) ? $t['buyer_name'] : $sale->buyer_name;
            $buyerDni = !empty($t['buyer_dni']) ? $t['buyer_dni'] : ($sale->buyer_dni ?: '00000000');

            $valHash = !empty($t['validation_hash']) ? $t['validation_hash'] : (!empty($t['hash']) ? $t['hash'] : '');
            if (empty($valHash)) {
                $str = ($sale->receipt_number ?: 'REC') . '_' . ($i + 1);
                $h = abs(jsHashCode($str));
                $valHash = 'VG' . substr(str_pad($h, 8, '0', STR_PAD_LEFT), 0, 8);
            }

            $qrPayload = !empty($t['qr_payload']) ? $t['qr_payload'] : (!empty($t['qr']) ? $t['qr'] : '');
            if (empty($qrPayload)) {
                $qrPayload = "VIVEGO|{$sale->receipt_number}|EVT-{$sale->event_id}|DNI-{$buyerDni}|TICK-{$ticketNum}|{$valHash}";
            }

            // 1. Buscar si ya existe el boleto vinculado a esta venta
            $et = EventTicket::where('ticket_sale_id', $sale->id)
                ->where('ticket_number', $ticketNum)
                ->first();

            // 2. Si no, buscar si existe un boleto con ese mismo correlativo en ese evento (por ejemplo, generado por la plancha/aforo)
            if (!$et) {
                $et = EventTicket::where('event_id', $sale->event_id)
                    ->where('ticket_number', $ticketNum)
                    ->first();
            }

            // 3. Si existe, lo actualizamos con los datos oficiales de la venta (QR, hash y venta)
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
                // 4. Si no existía en event_tickets, lo creamos
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

            $details[] = "Sale #{$sale->id} (Event #{$sale->event_id}) -> Ticket #{$ticketNum} [{$valHash}] -> EventTicket #{$et->id}";
        }

        // Actualizar tickets_data en ticket_sales
        if ($isItemsFormat) {
            $tData['items'] = $updatedTicketsList;
            $sale->update(['tickets_data' => $tData]);
        } else {
            $sale->update(['tickets_data' => $updatedTicketsList]);
        }
        $syncedSales++;
    }

    return [
        'synced_sales' => $syncedSales,
        'skipped_sales' => $skippedSales,
        'updated_tickets' => $updatedTickets,
        'created_tickets' => $createdTickets,
        'details' => $details
    ];
});

echo "SYNC SUCCESS:\n";
echo "Synced sales: {$results['synced_sales']}\n";
echo "Skipped orphaned sales: {$results['skipped_sales']}\n";
echo "Updated event_tickets: {$results['updated_tickets']}\n";
echo "Created event_tickets: {$results['created_tickets']}\n";
echo "Details sample (first 10):\n";
foreach (array_slice($results['details'], 0, 10) as $d) {
    echo "  $d\n";
}
