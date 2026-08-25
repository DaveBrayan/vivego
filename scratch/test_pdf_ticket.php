<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\TicketSale;
use App\Mail\TicketPurchaseMail;

$sale = TicketSale::latest()->first();
echo "Testing with Sale ID: {$sale->id}, Event ID: {$sale->event_id}\n";
echo "Event Title: " . ($sale->event?->title ?? 'N/A') . "\n";
echo "Event Banner: " . ($sale->event?->banner_image ?? 'N/A') . "\n";
echo "Payment Method: " . $sale->payment_method . "\n";

$mailable = new TicketPurchaseMail($sale);
$attachments = $mailable->attachments();
echo "Attachments count: " . count($attachments) . "\n";

foreach ($attachments as $att) {
    echo "Attachment class: " . get_class($att) . "\n";
}

$html = view('emails.ticket_purchase', ['sale' => $sale, 'tempPassword' => null, 'isNewUser' => false])->render();
echo "Email HTML length: " . strlen($html) . " bytes\n";

if (str_contains($html, 'Pasarela Izipay Online') && $sale->payment_method === 'Cortesía') {
    echo "WARNING: Hardcoded Izipay found in email for courtesy sale!\n";
} else {
    echo "Payment method in email rendered properly.\n";
}
