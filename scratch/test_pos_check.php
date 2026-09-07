<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$indexes = Illuminate\Support\Facades\DB::select("SHOW INDEX FROM event_tickets");
foreach ($indexes as $idx) {
    echo "{$idx->Key_name} | {$idx->Column_name} | Non_unique: {$idx->Non_unique}\n";
}
