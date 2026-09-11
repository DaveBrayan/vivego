<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$passwords = ['admin123', 'password', '123456', '12345678', 'Vivego2026', 'vivego', 'admin', 'deivid', 'chipana', '1234', 'secret', 'admin2026', 'Vivego2025'];
foreach (App\Models\Administrator::all() as $a) {
    echo "Admin {$a->id} ({$a->email}):\n";
    foreach ($passwords as $p) {
        if (Illuminate\Support\Facades\Hash::check($p, $a->password)) {
            echo "  MATCH: '{$p}'\n";
        }
    }
}
