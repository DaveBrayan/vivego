<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\TicketSale;
use App\Models\EventTicket;

$events = Event::all();
$totalSalesWithExcess = 0;
$totalExcessTickets = 0;

foreach ($events as $event) {
    $sales = TicketSale::where('event_id', $event->id)->get();
    $eventMismatches = 0;
    
    foreach ($sales as $sale) {
        $tickets = EventTicket::where('ticket_sale_id', $sale->id)->orderBy('id', 'asc')->get();
        $qty = (int)$sale->quantity;
        
        if ($tickets->count() > $qty) {
            $eventMismatches++;
            $totalSalesWithExcess++;
            $totalExcessTickets += ($tickets->count() - $qty);
        }
    }
    
    if ($eventMismatches > 0) {
        echo "Event [{$event->id}] {$event->title}: {$eventMismatches} sales with duplicate attached tickets.\n";
    }
}

echo "Total across whole DB: {$totalSalesWithExcess} sales with excess, {$totalExcessTickets} excess tickets.\n";
