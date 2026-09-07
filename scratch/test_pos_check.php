<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tickets = \App\Models\EventTicket::where('event_id', 25)->orderBy('id', 'asc')->get();
echo "Event 25 total tickets: " . $tickets->count() . "\n";
$sold = $tickets->whereNotNull('ticket_sale_id');
$unsold = $tickets->whereNull('ticket_sale_id');
echo "Sold tickets: " . $sold->count() . " | Unsold tickets: " . $unsold->count() . "\n";

foreach ($sold as $t) {
    echo "Sold: ET #{$t->id} | Sale: {$t->ticket_sale_id} | Num: {$t->ticket_number} | Code: {$t->ticket_code} | Zone: {$t->zone_name} | Buyer: {$t->buyer_name} | QR: {$t->qr_payload}\n";
}
echo "-- First 5 unsold:\n";
foreach ($unsold->take(5) as $t) {
    echo "Unsold: ET #{$t->id} | Num: {$t->ticket_number} | Code: {$t->ticket_code} | Zone: {$t->zone_name} | Type: {$t->ticket_type}\n";
}
