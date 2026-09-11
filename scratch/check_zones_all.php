<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Event::all() as $e) {
    $zones = $e->zones ?? [];
    $hasPts = 0;
    if (is_array($zones)) {
        foreach ($zones as $z) {
            if (!empty($z['points']) && is_array($z['points']) && count($z['points']) >= 3) {
                $hasPts++;
            }
        }
    }
    echo "Event {$e->id}: '{$e->title}' (sales_type: '{$e->sales_type}') - Zones: " . count($zones) . ", with points: {$hasPts}\n";
    if (is_array($zones)) {
        foreach ($zones as $zi => $z) {
            echo "   [{$zi}] " . ($z['name'] ?? 'no-name') . " (cap: " . ($z['capacity'] ?? '?') . ")\n";
        }
    }
}
