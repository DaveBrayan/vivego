<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;

$events = Event::latest()->take(5)->get();
foreach ($events as $e) {
    echo "========================================\n";
    echo "EVENT #{$e->id}: {$e->title} | sales_type: {$e->sales_type}\n";
    echo "========================================\n";
    echo "ZONES JSON:\n";
    echo json_encode($e->zones, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "QUOTA SPLIT SETTINGS:\n";
    echo json_encode($e->quota_split_settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "COURTESY SETTINGS:\n";
    echo json_encode($e->courtesy_settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
    $tickets = EventTicket::where('event_id', $e->id)->get();
    echo "TICKETS COUNT: " . $tickets->count() . "\n";
    $groupedTickets = $tickets->groupBy('zone_name')->map(function($group) {
        return [
            'total' => $group->count(),
            'by_status' => $group->groupBy('status')->map->count(),
            'by_ticket_type' => $group->groupBy('ticket_type')->map->count(),
            'min_corr' => $group->min('correlative_number'),
            'max_corr' => $group->max('correlative_number'),
        ];
    });
    echo "TICKETS BY ZONE:\n" . json_encode($groupedTickets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

    $sales = TicketSale::where('event_id', $e->id)->get();
    echo "SALES COUNT: " . $sales->count() . "\n";
    foreach ($sales as $s) {
        echo " - Sale #{$s->id} | receipt: {$s->receipt_number} | zone: {$s->zone_name} | qty: {$s->quantity} | total: {$s->total_amount} | corr_start: {$s->correlative_start} | corr_end: {$s->correlative_end} | type: {$s->sale_type}\n";
    }
}
