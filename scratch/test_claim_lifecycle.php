<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST CLAIM FULL LIFECYCLE ===\n";

// 1. Create a claim
$claim = new \App\Models\Claim();
$claim->claim_number = \App\Models\Claim::generateNextClaimNumber();
$claim->person_type = 'natural';
$claim->full_name = 'Carlos Rodriguez Mendoza';
$claim->document_type = 'DNI';
$claim->document_number = '45892134';
$claim->email = 'carlos.rodriguez@gmail.com';
$claim->phone = '998877665';
$claim->address = 'Av. Javier Prado Este 2450';
$claim->department = 'LIMA';
$claim->province = 'LIMA';
$claim->district = 'SAN BORJA';
$claim->contracted_good_type = 'SERVICIO';
$claim->claimed_amount = 220.00;
$claim->good_description = '2 Entradas Preferenciales para Festival Vive Rock 2026';
$claim->claim_type = 'RECLAMO';
$claim->claim_detail = 'Se generó un duplicado de cobro en mi tarjeta durante el checkout del evento.';
$claim->consumer_request = 'Solicito la anulación y devolución del cobro duplicado por S/. 220.00 a mi tarjeta.';
$claim->status = 'Pendiente';
$claim->ip_address = '127.0.0.1';
$claim->user_agent = 'Mozilla/5.0 (Test Environment)';
$claim->save();

echo "[OK] Created claim ID: {$claim->id} with Code: {$claim->claim_number}\n";

// 2. Query claim
$saved = \App\Models\Claim::find($claim->id);
echo "[OK] Found saved claim: {$saved->full_name}, Status: {$saved->status}, Legal Deadline: {$saved->legal_deadline->format('d/m/Y')}\n";

// 3. Update / Respond claim as Admin
$saved->admin_response = 'Estimado Carlos, hemos procedido con la verificación en la pasarela de pagos. Se anuló la transacción duplicada y el extorno fue procesado satisfactoriamente.';
$saved->status = 'Atendido';
$saved->admin_response_date = \Carbon\Carbon::now();
$saved->admin_notes = 'Verificado con pasarela Culqi. Operación #482910 extornada.';
$saved->save();

echo "[OK] Updated claim ID {$saved->id} to status: {$saved->status}\n";

// 4. Test generate next claim number after one exists
$nextCode = \App\Models\Claim::generateNextClaimNumber();
echo "[OK] Next claim code will be: {$nextCode}\n";
