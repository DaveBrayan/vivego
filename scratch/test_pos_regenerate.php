<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;
use App\Services\TicketGenerationService;
use Illuminate\Support\Facades\DB;

$event = Event::find(25);
DB::beginTransaction();

try {
    $result = TicketGenerationService::regeneratePosSalesQrs($event, 100, 'pos_only');
    echo "Correlative Range with start=100: " . $result['correlative_range'] . "\n";
    foreach ($result['details'] as $d) {
        echo "  {$d['buyer_name']} | New Code: {$d['new_ticket_code']} | New Hash: {$d['new_hash']} | QR: {$d['new_qr']}\n";
    }
} finally {
    DB::rollBack();
}
