<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Event;
use Illuminate\Http\Request;

echo "=== TEST: POST COURTESY TICKETS TO CHECKOUT ===\n";
$event = Event::first();

$courtesyItems = [
    [
        'name' => 'Pase VIP de Cortesía (Free)',
        'price' => 0.00,
        'regular_price' => 0.00,
        'is_presale' => false,
        'presale_discount' => 0,
        'is_courtesy' => true,
        'quantity' => 2,
        'subtotal' => 0.00
    ]
];

$checkoutController = new \App\Http\Controllers\Web\CheckoutController();
$req = Request::create('/checkout', 'POST', [
    'event_id' => $event->id,
    'selected_date' => '15/10/2026',
    'tickets' => json_encode($courtesyItems)
]);

$view = $checkoutController->show($req);
$rendered = $view->render();

echo "Rendered length: " . strlen($rendered) . " bytes\n";
if (str_contains($rendered, 'courtesyStepCard') && str_contains($rendered, 'Confirmar Entradas de Cortesía')) {
    echo "✓ Courtesy Confirmation Card rendered successfully in Checkout!\n";
} else {
    echo "✕ Courtesy Confirmation Card NOT rendered in Checkout!\n";
}

if (str_contains($rendered, 'Pase VIP de Cortesía (Free)')) {
    echo "✓ Cart item name found in Checkout view!\n";
}

if (str_contains($rendered, 'completeCourtesyCheckout()')) {
    echo "✓ completeCourtesyCheckout() JS handler present!\n";
}

echo "=== CHECKOUT VERIFICATION COMPLETE ===\n";
