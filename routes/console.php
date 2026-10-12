<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('culqi:process-order {orderId} {--email=} {--name=} {--doc=}', function ($orderId, \App\Services\CulqiService $culqiService) {
    $this->info("Procesando orden Culqi: {$orderId}...");
    $overrides = array_filter([
        'customer_email' => $this->option('email'),
        'customer_name' => $this->option('name'),
        'customer_doc' => $this->option('doc'),
    ]);
    $controller = app(\App\Http\Controllers\Web\CheckoutController::class);
    $sale = $controller->processCulqiPaidOrder($orderId, $culqiService, $overrides);
    if ($sale) {
        $this->info("¡Éxito! Venta #{$sale->id} (Recibo: {$sale->receipt_number}) registrada correctamente.");
        $this->info("Comprador: {$sale->buyer_name} | DNI: {$sale->buyer_dni}");
        $this->info("Total: S/ {$sale->total_amount} | Entradas: {$sale->quantity} ({$sale->zone_name})");
    } else {
        $this->error("No se pudo procesar la orden {$orderId}. Revisa logs en storage/logs.");
    }
})->purpose('Procesar manualmente una orden de Culqi pagada o depositada');

