<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;

$e = Event::find(23) ?? Event::latest()->first();
echo "EVENT #{$e->id}: {$e->title}\n";
echo "sales_type: {$e->sales_type}\n";
echo "zones: " . json_encode($e->zones, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo "quota_split_settings: " . json_encode($e->quota_split_settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

$tickets = EventTicket::where('event_id', $e->id)->get();
echo "Total tickets: " . $tickets->count() . "\n";
foreach ($tickets->groupBy('zone_name') as $zoneName => $ztickets) {
    echo "Zone: '$zoneName' => count: " . $ztickets->count() . "\n";
    foreach ($ztickets->groupBy('ticket_type') as $type => $ttickets) {
        echo "   - type: '$type' => count: " . $ttickets->count() . " (statuses: " . json_encode($ttickets->groupBy('status')->map->count()) . ")\n";
        echo "     sample correlatives: min=" . $ttickets->min('correlative_number') . ", max=" . $ttickets->max('correlative_number') . "\n";
        echo "     first 5 correlatives: " . $ttickets->take(5)->pluck('correlative_number')->implode(', ') . "\n";
    }
}

$sales = TicketSale::where('event_id', $e->id)->get();
echo "Total sales: " . $sales->count() . "\n";
foreach ($sales as $s) {
    echo "Sale #{$s->id}: receipt={$s->receipt_number}, zone={$s->zone_name}, qty={$s->quantity}, corr_start={$s->correlative_start}, corr_end={$s->correlative_end}, type={$s->sale_type}, payment_method={$s->payment_method}, status={$s->status}\n";
    // Check ticket relations
    $relatedTickets = EventTicket::where('sale_id', $s->id)->get();
    echo "   Related tickets by sale_id: " . $relatedTickets->count() . "\n";
    foreach ($relatedTickets as $rt) {
        echo "      Ticket #{$rt->id}: code={$rt->ticket_code}, corr={$rt->correlative_number}, type={$rt->ticket_type}, status={$rt->status}\n";
    }
}
