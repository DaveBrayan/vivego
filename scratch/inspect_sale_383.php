<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TicketSale;
use App\Models\EventTicket;

$sale = TicketSale::where('receipt_number', 'REC-000383')->first();
if ($sale) {
    echo "SALE REC-000383:\n";
    echo "ID: {$sale->id}\n";
    echo "Event ID: {$sale->event_id}\n";
    echo "Customer: {$sale->buyer_name} / {$sale->customer_name}\n";
    echo "Zone: {$sale->zone_name}\n";
    echo "Qty: {$sale->quantity}\n";
    echo "Total: {$sale->total_amount}\n";
    echo "Sale type: {$sale->sale_type}\n";
    echo "Tickets Data:\n" . json_encode($sale->tickets_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "Sale REC-000383 not found.\n";
}

$tickets = EventTicket::where('ticket_sale_id', $sale ? $sale->id : 0)->get();
echo "Associated EventTickets count: " . $tickets->count() . "\n";
foreach ($tickets as $t) {
    echo " - Ticket #{$t->id}: num={$t->ticket_number}, code={$t->ticket_code}, zone={$t->zone_name}, type={$t->ticket_type}, source={$t->source}\n";
}
