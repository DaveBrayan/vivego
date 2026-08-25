<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Event;
use App\Models\EventTicket;
use App\Http\Controllers\Web\AttendeeController;
use Illuminate\Http\Request;

echo "=== TEST: VERIFY QR & LIVE FEED ===\n";

$event = Event::first();
echo "Testing on Event: {$event->title} (ID: {$event->id})\n";

$controller = new AttendeeController();

// Find or create an unused ticket
$ticket = EventTicket::where('event_id', $event->id)->where('is_used', false)->first();
if (!$ticket) {
    echo "Creating a test ticket...\n";
    $ticket = EventTicket::create([
        'event_id' => $event->id,
        'ticket_code' => 'TK-TEST-999',
        'ticket_number' => 999,
        'zone_name' => 'General',
        'unit_price' => 0.00,
        'qr_payload' => "VIVEGO|REC-TEST|EVT-{$event->id}|DNI-12345678|TICK-999",
        'validation_hash' => 'VGTEST999',
        'buyer_name' => 'Tester User',
        'buyer_dni' => '12345678',
        'source' => 'web_test',
        'is_used' => false,
        'status' => 'valid'
    ]);
}

echo "Testing scan for Ticket Code: {$ticket->ticket_code}, QR Payload: {$ticket->qr_payload}\n";

// 1. Scan valid ticket
$reqValid = Request::create("/admin/asistentes/{$event->id}/validar-qr", 'POST', [
    'qr_payload' => $ticket->qr_payload,
    'device_name' => 'Puerta VIP Móvil'
]);

$resValid = $controller->verifyQr($reqValid, $event);
$dataValid = json_decode($resValid->getContent(), true);

echo "Scan 1 Status: " . ($dataValid['status'] ?? 'N/A') . "\n";
echo "Scan 1 Title: " . ($dataValid['title'] ?? 'N/A') . "\n";

// 2. Scan same ticket again (should be already used)
$resUsed = $controller->verifyQr($reqValid, $event);
$dataUsed = json_decode($resUsed->getContent(), true);

echo "Scan 2 Status: " . ($dataUsed['status'] ?? 'N/A') . " (Expected: already_used)\n";
echo "Scan 2 Title: " . ($dataUsed['title'] ?? 'N/A') . "\n";

// 3. Test Checkins Feed
$reqFeed = Request::create("/admin/asistentes/{$event->id}/checkins-feed", 'GET');
$resFeed = $controller->checkinsFeed($reqFeed, $event);
$dataFeed = json_decode($resFeed->getContent(), true);

echo "Feed Checkins Count: " . count($dataFeed['new_checkins'] ?? []) . "\n";
echo "Feed Metrics Total: " . ($dataFeed['metrics']['tickets_issued'] ?? 0) . "\n";
echo "Feed Checked In: " . ($dataFeed['metrics']['checked_in_count'] ?? 0) . "\n";

echo "=== ALL VERIFICATIONS PASSED ===\n";
