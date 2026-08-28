<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== 1. TEST ROUTE RESOLUTION ===\n";
$routes = [
    'web.terms' => [],
    'web.privacy' => [],
    'web.cookies' => [],
    'web.claim_book' => [],
    'web.claim_book.store' => [],
    'web.claim_book.confirmation' => ['code' => 'REC-202608-0001'],
    'web.claims' => [],
    'web.claims.details' => ['id' => 1],
    'web.claims.respond' => ['id' => 1],
    'web.claims.update_status' => ['id' => 1],
    'web.claims.destroy' => ['id' => 1],
];

foreach ($routes as $name => $params) {
    try {
        $url = route($name, $params);
        echo "[OK] Route '{$name}' -> {$url}\n";
    } catch (\Throwable $e) {
        echo "[FAIL] Route '{$name}': " . $e->getMessage() . "\n";
    }
}

echo "\n=== 2. TEST VIEWS COMPILATION ===\n";
$views = [
    'web.legal_terms',
    'web.legal_privacy',
    'web.legal_cookies',
    'web.claim_book',
    'web.claims',
];

foreach ($views as $v) {
    try {
        $rendered = view($v, [
            'settings' => \App\Models\Setting::first(),
            'company' => \App\Models\Company::first(),
            'events' => \App\Models\Event::all(),
            'claims' => \App\Models\Claim::all(),
            'stats' => [
                'total' => 0,
                'reclamos' => 0,
                'quejas' => 0,
                'pendientes' => 0,
                'en_proceso' => 0,
                'atendidos' => 0,
                'anulados' => 0,
            ],
            'errors' => new \Illuminate\Support\ViewErrorBag(),
        ])->render();
        echo "[OK] View '{$v}' rendered successfully (" . strlen($rendered) . " bytes)\n";
    } catch (\Throwable $e) {
        echo "[FAIL] View '{$v}': " . $e->getMessage() . "\n";
    }
}

// Test claim confirmation view with dummy Claim model
try {
    $dummyClaim = new \App\Models\Claim([
        'claim_number' => 'REC-202608-0001',
        'person_type' => 'natural',
        'full_name' => 'Consumidor de Prueba',
        'document_type' => 'DNI',
        'document_number' => '78945612',
        'email' => 'test@vivego.pe',
        'phone' => '987654321',
        'address' => 'Av. Larco 123',
        'department' => 'LIMA',
        'district' => 'MIRAFLORES',
        'contracted_good_type' => 'SERVICIO',
        'claimed_amount' => 150.00,
        'good_description' => '2 Boletos VIP Concierto',
        'claim_type' => 'RECLAMO',
        'claim_detail' => 'Problema con la asignación de zona.',
        'consumer_request' => 'Reubicación o compensación.',
        'status' => 'Pendiente',
    ]);
    $dummyClaim->created_at = \Carbon\Carbon::now();

    $renderedConf = view('web.claim_confirmation', [
        'claim' => $dummyClaim,
        'company' => \App\Models\Company::first(),
        'settings' => \App\Models\Setting::first(),
    ])->render();
    echo "[OK] View 'web.claim_confirmation' rendered successfully (" . strlen($renderedConf) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] View 'web.claim_confirmation': " . $e->getMessage() . "\n";
}

echo "\n=== 3. TEST CLAIM CREATION & NUMBER GENERATION ===\n";
try {
    $nextCode = \App\Models\Claim::generateNextClaimNumber();
    echo "[OK] Next claim code: {$nextCode}\n";
} catch (\Throwable $e) {
    echo "[FAIL] Claim number generation: " . $e->getMessage() . "\n";
}
