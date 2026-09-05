<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

function jsHashCode($str) {
    $hash = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $hash = (($hash << 5) - $hash) + ord($str[$i]);
        $hash = $hash & 0xFFFFFFFF;
        if ($hash > 0x7FFFFFFF) {
            $hash -= 0x100000000;
        }
    }
    return $hash;
}

$sales = App\Models\TicketSale::where('buyer_name', 'LIKE', '%DEIVID%')->get();
foreach ($sales as $sale) {
    echo "Found sale: ID {$sale->id}, Receipt: {$sale->receipt_number}, Buyer: {$sale->buyer_name}, Zone: {$sale->zone_name}\n";
    $str = ($sale->receipt_number ?: 'REC') . '_1';
    $h = abs(jsHashCode($str));
    $hashVal = 'VG' . substr(str_pad($h, 8, '0', STR_PAD_LEFT), 0, 8);
    echo "Calculated Hash: {$hashVal}\n";
}
