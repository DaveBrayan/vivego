<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Event;
use Illuminate\Http\Request;

echo "=== TEST: EVENT UPDATE NAME & ZONES ===\n";

$event = Event::first();
if (!$event) {
    echo "No event found!\n";
    exit;
}

echo "Updating Event ID: {$event->id} (Current title: {$event->title})\n";

$newName = "EVENTO DE PRUEBA PLANTILLA 2 - ACTUALIZADO " . rand(100, 999);

$eventController = new \App\Http\Controllers\Web\EventController();
$req = Request::create("/admin/eventos/{$event->id}", 'PUT', [
    'title' => $newName,
    'category_name' => $event->category_name ?? 'Conciertos',
    'company_name' => $event->company_name ?? 'Vive Go',
    'layout_template' => 'template_2',
    'event_date' => '2026-11-20',
    'event_time' => '21:00',
    'venue_name' => 'Estadio Nacional',
    'address' => 'Lima, Perú',
    'zones' => [
        [
            'capacity_type' => 'Aforo VIP',
            'name' => 'ZONA PLATINUM',
            'capacity' => 500,
            'price' => 150.00,
            'has_presale' => false,
            'presale_discount' => 0,
            'presale_price' => null,
            'presale_start_date' => null,
            'presale_end_date' => null,
            'presale_stock' => null,
        ]
    ],
    'courtesy_settings' => [
        'enabled' => true,
        'for_users' => true,
        'for_admins' => true,
        'name' => 'Entrada de Cortesía (Free)',
        'user_max_quantity' => 2,
        'stock' => 50,
    ]
]);

$res = $eventController->update($req, $event->id);
$resData = json_decode($res->getContent(), true);

echo "Update Response Success: " . ($resData['success'] ? 'YES' : 'NO') . "\n";
echo "Message: " . ($resData['message'] ?? 'N/A') . "\n";

$refreshed = Event::find($event->id);
echo "New DB Title: {$refreshed->title}\n";
echo "New Slug: {$refreshed->slug}\n";
echo "Template: {$refreshed->layout_template}\n";

if ($refreshed->title === $newName) {
    echo "✓ Event updated and saved in MySQL successfully without any error!\n";
} else {
    echo "✕ Title mismatch!\n";
}

echo "=== TEST COMPLETE ===\n";
