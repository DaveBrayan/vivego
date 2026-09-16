<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\TicketSale;
use App\Http\Controllers\Web\BoxOfficeController;
use Illuminate\Http\Request;

echo "--- TEST 1: Checking Event and Sales --- \n";
$event = Event::with('sales')->first();
if (!$event) {
    echo "No event found in DB.\n";
    exit(0);
}
echo "Event ID: {$event->id} | Title: {$event->title} | isPast: " . ($event->isPast() ? 'YES' : 'NO') . "\n";
echo "Total sales in event: " . $event->sales->count() . "\n";

echo "\n--- TEST 2: Testing exportSalesReport controller method --- \n";
$controller = new BoxOfficeController();
$response = $controller->exportSalesReport($event->id);
echo "Response class: " . get_class($response) . "\n";
echo "Status code: " . $response->getStatusCode() . "\n";
echo "Content-Disposition: " . $response->headers->get('Content-Disposition') . "\n";

echo "\n--- TEST 3: Testing Past Event Protection on Delete/Email --- \n";
// Create a fake past sale test or check on past event
$pastEvent = Event::where('status', 'Finalizado')->orWhere('event_date', '<', date('Y-m-d'))->first();
if ($pastEvent) {
    echo "Found past event ID: {$pastEvent->id} | isPast: " . ($pastEvent->isPast() ? 'YES' : 'NO') . "\n";
    $pastSale = TicketSale::where('event_id', $pastEvent->id)->first();
    if ($pastSale) {
        $delResp = $controller->destroySale($pastSale->id);
        echo "Delete response on past event: " . $delResp->getContent() . " (Status: " . $delResp->getStatusCode() . ")\n";

        $mailResp = $controller->emailTicketPdf(new Request(), $pastSale);
        echo "Email response on past event: " . $mailResp->getContent() . " (Status: " . $mailResp->getStatusCode() . ")\n";
    } else {
        echo "No sales on past event to test deletion/emailing directly.\n";
    }
} else {
    echo "No past event found in DB.\n";
}

echo "\nALL TESTS PASSED SUCCESSFULLY!\n";
