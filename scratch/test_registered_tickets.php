<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;
use App\Http\Controllers\Web\EventController;
use Illuminate\Http\Request;

$event = Event::find(23);
$controller = new EventController();
$request = Request::create('/admin/eventos/23/boletos-registrados', 'GET');
app()->instance('request', $request);

$response = $controller->getRegisteredTickets($event);
$data = json_decode($response->getContent(), true);

echo "TOTAL RETURNED TICKETS FROM API: " . count($data['tickets']) . "\n";
$grouped = [];
foreach ($data['tickets'] as $t) {
    $z = $t['zoneName'];
    $grouped[$z] = ($grouped[$z] ?? 0) + 1;
}
echo "TICKETS BY ZONE:\n" . json_encode($grouped, JSON_PRETTY_PRINT) . "\n";

echo "SAMPLE OF FIRST 10 TICKETS:\n";
foreach (array_slice($data['tickets'], 0, 10) as $t) {
    echo " - ID: {$t['id']}, Num: {$t['ticketNumberVal']}, Code: {$t['ticketCode']}, Zone: {$t['zoneName']}, Price: {$t['zonePrice']}, Type: {$t['ticketType']}\n";
}

echo "SAMPLE OF LAST 10 TICKETS:\n";
foreach (array_slice($data['tickets'], -10) as $t) {
    echo " - ID: {$t['id']}, Num: {$t['ticketNumberVal']}, Code: {$t['ticketCode']}, Zone: {$t['zoneName']}, Price: {$t['zonePrice']}, Type: {$t['ticketType']}\n";
}
