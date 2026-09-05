<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$et = App\Models\EventTicket::find(688);
if ($et) {
    echo "ET 688: ticket_sale_id={$et->ticket_sale_id}, num={$et->ticket_number}, code={$et->ticket_code}, hash={$et->validation_hash}, buyer={$et->buyer_name}, qr={$et->qr_payload}\n";
} else {
    echo "ET 688 not found\n";
}
