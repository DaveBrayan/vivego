<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;

$event = Event::find(23);
echo "=== ALL TICKETS FOR EVENT #23 ===\n";
$tickets = EventTicket::where('event_id', 23)->orderBy('id', 'asc')->get();
echo "Total count: " . $tickets->count() . "\n";

foreach ($tickets as $t) {
    if ($t->unit_price == 60 || $t->ticket_type == 'digital' || $t->ticket_sale_id || $t->status != 'valid' || $t->ticket_number <= 10 || $t->ticket_number >= 495) {
        echo "Ticket ID: {$t->id} | num: {$t->ticket_number} | code: {$t->ticket_code} | zone: {$t->zone_name} | price: {$t->unit_price} | type: {$t->ticket_type} | sale_id: {$t->ticket_sale_id} | buyer: {$t->buyer_name} | hash: {$t->validation_hash} | created_at: {$t->created_at}\n";
    }
}

echo "\n=== DUPLICATE TICKET NUMBERS BY ZONE ===\n";
$grouped = $tickets->groupBy('zone_name');
foreach ($grouped as $zone => $ztickets) {
    $numCounts = $ztickets->groupBy('ticket_number')->filter(fn($g) => $g->count() > 1);
    if ($numCounts->count() > 0) {
        echo "Zone '{$zone}' has duplicate ticket numbers:\n";
        foreach ($numCounts as $num => $dupes) {
            echo "  Number {$num} appears {$dupes->count()} times:\n";
            foreach ($dupes as $d) {
                echo "    - ID: {$d->id}, price: {$d->unit_price}, type: {$d->ticket_type}, sale_id: {$d->ticket_sale_id}, buyer: {$d->buyer_name}, created: {$d->created_at}\n";
            }
        }
    } else {
        echo "Zone '{$zone}' has NO duplicate ticket numbers.\n";
    }
}

echo "\n=== ALL SALES FOR EVENT #23 ===\n";
$sales = TicketSale::where('event_id', 23)->get();
foreach ($sales as $s) {
    echo "Sale #{$s->id} | receipt: {$s->receipt_number} | customer: {$s->customer_name} | zone: {$s->zone_name} | qty: {$s->quantity} | total: {$s->total_amount} | corr_start: {$s->correlative_start} | corr_end: {$s->correlative_end} | type: {$s->sale_type} | tickets_data: " . json_encode($s->tickets_data) . "\n";
}
