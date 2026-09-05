<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sales = App\Models\TicketSale::all();
echo "Total sales: " . $sales->count() . "\n";
foreach ($sales as $s) {
    echo "Sale ID: {$s->id}, Event: {$s->event_id}, Buyer: {$s->buyer_name}, Zone: {$s->zone_name}, Qty: {$s->quantity}\n";
    $tData = is_array($s->tickets_data) ? $s->tickets_data : json_decode($s->tickets_data ?? '[]', true);
    echo "Tickets count in tickets_data: " . count($tData) . "\n";
    foreach ($tData as $idx => $t) {
        echo "  [{$idx}] num: " . ($t['ticket_number'] ?? $t['number'] ?? 'N/A') . ", code: " . ($t['ticket_code'] ?? 'N/A') . ", hash: " . ($t['validation_hash'] ?? $t['hash'] ?? 'N/A') . ", qr: " . substr($t['qr_payload'] ?? $t['qr'] ?? '', 0, 40) . "...\n";
    }
}

$eventTickets = App\Models\EventTicket::all();
echo "\nTotal event_tickets: " . $eventTickets->count() . "\n";
foreach ($eventTickets->take(10) as $et) {
    echo "ET ID: {$et->id}, Event: {$et->event_id}, Sale ID: {$et->ticket_sale_id}, num: {$et->ticket_number}, code: {$et->ticket_code}, hash: {$et->validation_hash}, buyer: {$et->buyer_name}\n";
}
