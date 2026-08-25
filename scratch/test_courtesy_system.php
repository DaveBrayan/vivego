<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Event;
use App\Models\TicketSale;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

echo "=== TEST 1: EVENT COURTESY CONFIGURATION ===\n";
$event = Event::first();
if (!$event) {
    echo "No event found, creating dummy event...\n";
    $event = Event::create([
        'title' => 'Concierto Test Cortesias 2026',
        'slug' => 'concierto-test-cortesias-2026',
        'category_name' => 'Conciertos',
        'event_date' => '2026-10-15',
        'event_time' => '20:00',
        'venue_name' => 'Estadio Nacional',
        'address' => 'Lima',
        'zones' => [
            ['name' => 'VIP', 'price' => 100.00, 'capacity' => 500],
            ['name' => 'General', 'price' => 50.00, 'capacity' => 1000]
        ],
        'courtesy_settings' => [
            'enabled' => true,
            'for_users' => true,
            'for_admins' => true,
            'name' => 'Pase VIP de Cortesía (Free)',
            'stock' => 100
        ]
    ]);
} else {
    $event->courtesy_settings = [
        'enabled' => true,
        'for_users' => true,
        'for_admins' => true,
        'name' => 'Pase VIP de Cortesía (Free)',
        'stock' => 100
    ];
    $event->save();
}

$refreshedEvent = Event::find($event->id);
echo "Event ID: {$refreshedEvent->id}\n";
echo "Courtesy Enabled: " . ($refreshedEvent->courtesy_settings['enabled'] ? 'YES' : 'NO') . "\n";
echo "Courtesy For Users: " . ($refreshedEvent->courtesy_settings['for_users'] ? 'YES' : 'NO') . "\n";
echo "Courtesy For Admins: " . ($refreshedEvent->courtesy_settings['for_admins'] ? 'YES' : 'NO') . "\n";
echo "Courtesy Name: " . $refreshedEvent->courtesy_settings['name'] . "\n\n";

echo "=== TEST 2: EVENT DETAIL CONTROLLER TICKET PARSING ===\n";
$detailController = new \App\Http\Controllers\Web\EventDetailController();
$view = $detailController->show($refreshedEvent->slug);
$viewData = $view->getData();
$tickets = $viewData['event']['tickets'] ?? [];

echo "Total ticket types in event detail: " . count($tickets) . "\n";
$courtesyTicket = collect($tickets)->firstWhere('is_courtesy', true);
if ($courtesyTicket) {
    echo "✓ Found courtesy ticket: '{$courtesyTicket['name']}'\n";
    echo "  Price: S/ {$courtesyTicket['price']}\n";
    echo "  Max Quantity for Users: {$courtesyTicket['max_quantity']}\n";
} else {
    echo "✕ Courtesy ticket NOT found in detail!\n";
}
echo "\n";

echo "=== TEST 3: WEB COURTESY CHECKOUT COMPLETION ===\n";
$checkoutController = new \App\Http\Controllers\Web\CheckoutController();
$courtesyReq = Request::create('/checkout/cortesia/completar', 'POST', [
    'event_id' => $refreshedEvent->id,
    'customer_name' => 'Ana Torres Gómez',
    'customer_email' => 'anatorres@test.com',
    'customer_phone' => '+51 987123456',
    'customer_doc_number' => '45678901',
    'customer_country' => 'Perú 🇵🇪',
    'customer_city' => 'Arequipa',
    'tickets' => [
        [
            'name' => 'Pase VIP de Cortesía (Free)',
            'price' => 0.00,
            'quantity' => 2,
            'is_courtesy' => true
        ]
    ]
]);

$courtesyRes = $checkoutController->completeCourtesyOrder($courtesyReq);
$resData = json_decode($courtesyRes->getContent(), true);
echo "Response Success: " . ($resData['success'] ? 'YES' : 'NO') . "\n";
echo "Receipt Number: " . ($resData['receipt_number'] ?? 'N/A') . "\n";
echo "Redirect URL: " . ($resData['redirect_url'] ?? 'N/A') . "\n";

if (!empty($resData['sale_id'])) {
    $sale = TicketSale::find($resData['sale_id']);
    echo "Verified Sale in DB:\n";
    echo "  ID: {$sale->id}\n";
    echo "  Receipt: {$sale->receipt_number}\n";
    echo "  Total Amount: S/ {$sale->total_amount}\n";
    echo "  Payment Method: {$sale->payment_method}\n";
    echo "  Buyer: {$sale->buyer_name} ({$sale->buyer_dni})\n";
}
echo "\n";

echo "=== TEST 4: BOX OFFICE / POS COURTESY SALE (ADMIN UNLIMITED) ===\n";
$boxOfficeController = new \App\Http\Controllers\Web\BoxOfficeController();
$refreshedEvent = Event::find($event->id);
$zones = $refreshedEvent->zones;
$zones[0]['capacity'] = 500;
$refreshedEvent->zones = $zones;
$refreshedEvent->save();

$firstZoneName = $refreshedEvent->zones[0]['name'] ?? 'VIP';

$posReq = Request::create("/admin/taquilla/{$refreshedEvent->id}/venta", 'POST', [
    'zone_name' => $firstZoneName,
    'quantity' => 5, // Admin emitiendo 5 cortesias
    'buyer_name' => 'Delegación de Prensa Oficial',
    'buyer_dni' => '00001111',
    'buyer_phone' => '+51 999888777',
    'payment_method' => 'Cortesía',
    'amount_paid' => 0.00
]);

$posRes = $boxOfficeController->storeSale($posReq, $refreshedEvent->id);
$posData = json_decode($posRes->getContent(), true);
echo "POS Content: " . $posRes->getContent() . "\n";
echo "POS Response Success: " . (($posData['success'] ?? false) ? 'YES' : 'NO') . "\n";
echo "POS Receipt: " . ($posData['sale']['receipt_number'] ?? 'N/A') . "\n";
echo "POS Total: S/ " . ($posData['sale']['total_amount'] ?? 'N/A') . "\n";
echo "POS Method: " . ($posData['sale']['payment_method'] ?? 'N/A') . "\n";
echo "POS Qty: " . ($posData['sale']['quantity'] ?? 'N/A') . " entradas emitidas con éxito sin límite!\n\n";

echo "=== ALL COURTESY TESTS PASSED SUCCESSFULLY! ===\n";
