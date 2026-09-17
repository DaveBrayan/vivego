<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\EventTicket;
use App\Models\TicketSale;

$sampleSales = [25, 28, 63, 64, 78];
foreach ($sampleSales as $saleId) {
    $sale = TicketSale::find($saleId);
    echo "=== SALE #{$saleId} (Event {$sale->event_id}, Qty {$sale->quantity}, Buyer: {$sale->buyer_name}) ===" . PHP_EOL;
    $tickets = EventTicket::where('ticket_sale_id', $saleId)->get();
    foreach ($tickets as $t) {
        echo "  ET #{$t->id}: Number={$t->ticket_number}, Code={$t->ticket_code}, Type={$t->ticket_type}, Zone={$t->zone_name}, Source={$t->source}, Used=" . ($t->is_used ? 'YES' : 'NO') . ", Created={$t->created_at}" . PHP_EOL;
    }
    echo "  tickets_data in sale: " . json_encode($sale->tickets_data) . PHP_EOL . PHP_EOL;
}
