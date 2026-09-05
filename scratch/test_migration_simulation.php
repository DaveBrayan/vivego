<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

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

$sales = App\Models\TicketSale::orderBy('id', 'asc')->get();
$migratedCount = 0;
$updatedCount = 0;
$createdCount = 0;

foreach ($sales as $sale) {
    $raw = $sale->tickets_data;
    $tData = is_array($raw) ? $raw : (json_decode($raw ?? '[]', true) ?: []);
    
    // Extraer lista de boletos
    $ticketsList = [];
    if (isset($tData['items']) && is_array($tData['items'])) {
        $ticketsList = $tData['items'];
    } elseif (is_array($tData)) {
        $numericItems = array_filter($tData, function($k) { return is_numeric($k); }, ARRAY_FILTER_USE_KEY);
        if (!empty($numericItems)) {
            $ticketsList = array_values($numericItems);
        }
    }
    
    // Si la venta no tenía tickets_data desglosado, creamos según quantity
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

    $updatedTicketsDataList = [];

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

        // Hash determinista compatible con ticket_generator_js.blade.php
        $valHash = !empty($t['validation_hash']) ? $t['validation_hash'] : (!empty($t['hash']) ? $t['hash'] : '');
        if (empty($valHash)) {
            $str = ($sale->receipt_number ?: 'REC') . '_' . ($i + 1);
            $h = abs(jsHashCode($str));
            $valHash = 'VG' . substr(str_pad($h, 8, '0', STR_PAD_LEFT), 0, 8);
        }

        // QR payload compatible con ticket_generator_js.blade.php
        $qrPayload = !empty($t['qr_payload']) ? $t['qr_payload'] : (!empty($t['qr']) ? $t['qr'] : '');
        if (empty($qrPayload)) {
            $qrPayload = "VIVEGO|{$sale->receipt_number}|EVT-{$sale->event_id}|DNI-{$buyerDni}|TICK-{$ticketNum}|{$valHash}";
        }

        echo "Simulation Sale {$sale->id} (Event {$sale->event_id}): Ticket {$ticketNum} ({$ticketCode}) Hash: {$valHash}\n";
    }
}
