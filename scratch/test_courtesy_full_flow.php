<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Event;
use App\Models\TicketSale;
use App\Models\EventTicket;
use Illuminate\Http\Request;

echo "=== TEST: COURTESY CHECKOUT FULL FLOW ===\n";

$event = Event::first();
echo "Testing on Event: {$event->title} (ID: {$event->id})\n";

$checkoutController = new \App\Http\Controllers\Web\CheckoutController();
$req = Request::create('/checkout/cortesia/completar', 'POST', [
    'event_id' => $event->id,
    'customer_name' => 'Deivid Chipana Test',
    'customer_email' => 'deividtest@vivego.pe',
    'customer_phone' => '921026462',
    'customer_doc_number' => '71234567',
    'customer_country' => 'Perú',
    'customer_city' => 'Ayacucho',
    'tickets' => [
        [
            'name' => 'Entrada de Cortesía (Free)',
            'price' => 0.00,
            'quantity' => 2,
            'subtotal' => 0.00,
            'is_courtesy' => true
        ]
    ]
]);

$res = $checkoutController->completeCourtesyOrder($req);
$resData = json_decode($res->getContent(), true);

echo "Response Success: " . ($resData['success'] ? 'YES' : 'NO') . "\n";
echo "Message: " . ($resData['message'] ?? 'N/A') . "\n";
echo "Receipt Number: " . ($resData['receipt_number'] ?? 'N/A') . "\n";
echo "Redirect URL: " . ($resData['redirect_url'] ?? 'N/A') . "\n";

$sale = TicketSale::find($resData['sale_id'] ?? 0);
if ($sale) {
    echo "Sale DB Payment Method: {$sale->payment_method}\n";
    echo "Sale DB Total Amount: S/ {$sale->total_amount}\n";

    // Test voucher rendering
    $confirmationHtml = view('web.checkout_confirmation', ['sale' => $sale])->render();
    if (str_contains($confirmationHtml, '🎁 Entrada de Cortesía (Gratis / Free)')) {
        echo "✓ Voucher Payment Method: '🎁 Entrada de Cortesía (Gratis / Free)' rendered correctly!\n";
    } else {
        echo "✕ Voucher Payment Method mismatch!\n";
    }

    if (str_contains($confirmationHtml, 'Pasarela Izipay Online')) {
        echo "✕ Error: Izipay still found in courtesy voucher!\n";
    } else {
        echo "✓ Izipay is NOT shown in courtesy voucher.\n";
    }

    $ticketsCount = EventTicket::where('ticket_sale_id', $sale->id)->count();
    echo "Event Tickets generated in DB: {$ticketsCount}\n";
}

echo "=== TEST COMPLETE ===\n";
