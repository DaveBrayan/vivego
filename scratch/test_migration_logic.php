<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sales = App\Models\TicketSale::all();
echo "Analyzing " . $sales->count() . " sales:\n";

foreach ($sales as $s) {
    $raw = $s->tickets_data;
    $tData = is_array($raw) ? $raw : (json_decode($raw ?? '[]', true) ?: []);
    
    // Check if it's items array or direct array
    $ticketsList = [];
    if (isset($tData['items']) && is_array($tData['items'])) {
        $ticketsList = $tData['items'];
    } elseif (is_array($tData)) {
        // Filter out associative metadata keys
        $numericItems = array_filter($tData, function($k) { return is_numeric($k); }, ARRAY_FILTER_USE_KEY);
        if (!empty($numericItems)) {
            $ticketsList = array_values($numericItems);
        }
    }
    
    if (count($ticketsList) > 0) {
        echo "Sale ID {$s->id} (Event {$s->event_id}, {$s->buyer_name}): " . count($ticketsList) . " tickets extracted.\n";
        foreach ($ticketsList as $t) {
            $num = $t['ticket_number'] ?? $t['number'] ?? 0;
            $code = $t['ticket_code'] ?? $t['code'] ?? '';
            $hash = $t['validation_hash'] ?? $t['hash'] ?? '';
            $qr = !empty($t['qr_payload']) ? 'YES' : (!empty($t['qr']) ? 'YES' : 'NO');
            $zone = $t['zone'] ?? $t['zone_name'] ?? $s->zone_name;
            echo "   Ticket: num={$num}, code={$code}, hash={$hash}, qr={$qr}, zone={$zone}\n";
        }
    } else {
        echo "Sale ID {$s->id} (Event {$s->event_id}, {$s->buyer_name}): Qty={$s->quantity}, NO tickets in tickets_data!\n";
    }
}
