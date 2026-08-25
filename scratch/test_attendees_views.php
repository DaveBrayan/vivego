<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Event;
use App\Http\Controllers\Web\AttendeeController;

$event = Event::first();
echo "Testing with Event ID: {$event->id} ({$event->title})\n";

$controller = new AttendeeController();

try {
    echo "1. Testing index()...\n";
    $viewIndex = $controller->index();
    $htmlIndex = $viewIndex->render();
    echo "✓ index() rendered OK (" . strlen($htmlIndex) . " bytes)\n";
} catch (\Throwable $e) {
    echo "✕ Error in index(): " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}

try {
    echo "2. Testing scanner()...\n";
    $viewScanner = $controller->scanner($event);
    $htmlScanner = $viewScanner->render();
    echo "✓ scanner() rendered OK (" . strlen($htmlScanner) . " bytes)\n";
} catch (\Throwable $e) {
    echo "✕ Error in scanner(): " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}

try {
    echo "3. Testing mobileScanner()...\n";
    $viewMobile = $controller->mobileScanner($event);
    $htmlMobile = $viewMobile->render();
    echo "✓ mobileScanner() rendered OK (" . strlen($htmlMobile) . " bytes)\n";
} catch (\Throwable $e) {
    echo "✕ Error in mobileScanner(): " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
