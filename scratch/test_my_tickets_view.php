<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\TicketSale;
use Illuminate\Http\Request;

echo "=== TEST: CUSTOMER PORTAL MY_TICKETS VIEW ===\n";

$lastSale = TicketSale::latest()->first();
if ($lastSale) {
    session([
        'customer_logged_in' => true,
        'customer_dni' => $lastSale->buyer_dni,
        'customer_email' => $lastSale->tickets_data['customer_email'] ?? 'test@test.com',
        'customer_name' => $lastSale->buyer_name,
    ]);
}

$portalController = new \App\Http\Controllers\Web\CustomerPortalController();
$view = $portalController->myTickets();
$rendered = $view->render();

echo "Rendered length: " . strlen($rendered) . " bytes\n";
if (str_contains($rendered, 'Mejorar mi Entrada') && str_contains($rendered, 'Bloqueado')) {
    echo "✓ '⭐ Mejorar mi Entrada (Bloqueado)' rendered successfully!\n";
} else {
    echo "✕ Locked upgrade button NOT found!\n";
}

if (!str_contains($rendered, 'Enviar a mi Correo')) {
    echo "✓ Old 'Enviar a mi Correo' button removed successfully from tickets!\n";
} else {
    echo "✕ Old 'Enviar a mi Correo' still present!\n";
}

echo "=== MY_TICKETS TEST COMPLETE ===\n";
