<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\TicketSale;
use App\Models\EventTicket;

$dryRun = false; // Set to false to apply changes

echo "=== REAL CLEANUP EXECUTION ===\n";

$sales = TicketSale::all();
$fixedSales = 0;
$unlinkedTickets = 0;
$deletedClones = 0;

foreach ($sales as $sale) {
    $tickets = EventTicket::where('ticket_sale_id', $sale->id)->orderBy('id', 'asc')->get();
    $qty = (int)$sale->quantity;
    
    if ($tickets->count() <= $qty) {
        continue;
    }
    
    $fixedSales++;
    echo "Sale #{$sale->id} (Event {$sale->event_id}, Receipt: {$sale->receipt_number}) - Qty: {$qty}, Attached: {$tickets->count()}\n";
    
    // Group by ticket_code or ticket_number
    $grouped = $tickets->groupBy(function($t) {
        $code = trim($t->ticket_code ?: '');
        $num = (int)$t->ticket_number;
        return $code ?: "NUM_{$num}";
    });
    
    $toKeep = collect();
    $toUnlink = collect();
    
    foreach ($grouped as $key => $gTickets) {
        if ($gTickets->count() === 1) {
            $toKeep->push($gTickets->first());
        } else {
            // Sort to prioritize used tickets, then digital/original tickets
            $sorted = $gTickets->sort(function($a, $b) {
                // Prioritize is_used = true
                if ($a->is_used && !$b->is_used) return -1;
                if (!$a->is_used && $b->is_used) return 1;
                // Then prioritize smaller ID (earlier created)
                return $a->id <=> $b->id;
            })->values();
            
            // Keep the top 1
            $toKeep->push($sorted->first());
            
            // The rest are excess
            for ($i = 1; $i < $sorted->count(); $i++) {
                $toUnlink->push($sorted->get($i));
            }
        }
    }
    
    // If toKeep is still > qty, reduce toKeep to exact qty (prioritizing is_used)
    if ($toKeep->count() > $qty) {
        $sortedKeep = $toKeep->sort(function($a, $b) {
            if ($a->is_used && !$b->is_used) return -1;
            if (!$a->is_used && $b->is_used) return 1;
            return $a->id <=> $b->id;
        })->values();
        
        $toKeep = $sortedKeep->take($qty);
        $excessKeep = $sortedKeep->slice($qty);
        foreach ($excessKeep as $ek) {
            $toUnlink->push($ek);
        }
    }
    
    foreach ($toUnlink as $t) {
        $unlinkedTickets++;
        echo "   -> Unlink Ticket ID {$t->id} (Code: {$t->ticket_code}, Type: {$t->ticket_type}, Used: " . ($t->is_used ? 'YES' : 'NO') . ")\n";
        
        if (!$dryRun) {
            // Unlink ticket from sale
            $t->update([
                'ticket_sale_id' => null,
                'status' => 'available',
                'buyer_name' => '',
                'buyer_dni' => '',
            ]);
        }
    }
}

echo "\n============================================\n";
echo "Total sales processed: {$fixedSales}\n";
echo "Total excess tickets unlinked: {$unlinkedTickets}\n";
