<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;
use App\Services\TicketGenerationService;

$events = Event::all();
foreach ($events as $e) {
    TicketGenerationService::syncEventTickets($e);
    echo "=== EVENT #{$e->id}: {$e->title} (sales_type: {$e->sales_type}) ===\n";
    $tickets = EventTicket::where('event_id', $e->id)->get();
    echo "Total tickets in BD: " . $tickets->count() . "\n";
    foreach ($tickets->groupBy('zone_name') as $zone => $zt) {
        foreach ($zt->groupBy('ticket_type') as $type => $g) {
            $min = $g->min('ticket_number');
            $max = $g->max('ticket_number');
            $prices = $g->pluck('unit_price')->unique()->implode(', ');
            $dupeCount = $g->groupBy('ticket_number')->filter(fn($items) => $items->count() > 1)->count();
            echo " - Zone: '{$zone}' | Type: {$type} | Count: {$g->count()} | Range: {$min}..{$max} | Prices: {$prices} | Dupes: {$dupeCount}\n";
        }
    }
}
