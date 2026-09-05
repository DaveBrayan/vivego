<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$e = App\Models\Event::with('template')->latest('id')->first();
if ($e) {
    echo "Event ID: {$e->id}, Title: {$e->title}\n";
    if ($e->template) {
        echo "Template ID: {$e->template->id}\n";
        echo "Template background: {$e->template->background}\n";
        echo "Template bg_image: {$e->template->bg_image}\n";
        echo "Template bg_color: {$e->template->bg_color}\n";
        echo "Template positions: " . substr(json_encode($e->template->positions ?? $e->template->elements), 0, 300) . "\n";
    } else {
        echo "No template relation. Template ID column: " . ($e->template_id ?? 'null') . "\n";
    }
}
