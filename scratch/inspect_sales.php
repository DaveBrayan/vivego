<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sales = App\Models\TicketSale::where('event_id', 25)->get();
echo "Total sales for Event 25: " . $sales->count() . PHP_EOL;
foreach ($sales as $s) {
    echo "Sale ID: {$s->id} | Receipt: {$s->receipt_number} | Buyer: {$s->buyer_name} | Zone: {$s->zone_name} | Qty: {$s->quantity} | Method: {$s->payment_method} | Seller: '{$s->seller_name}'" . PHP_EOL;
    $tickets = App\Models\EventTicket::where('ticket_sale_id', $s->id)->get();
    echo "  Linked EventTickets count: " . $tickets->count() . PHP_EOL;
    foreach ($tickets as $t) {
        echo "    ET #{$t->id} | Code: {$t->ticket_code} | Num: {$t->ticket_number} | Hash: {$t->validation_hash} | Zone: {$t->zone_name} | QR: {$t->qr_payload}" . PHP_EOL;
    }
}
