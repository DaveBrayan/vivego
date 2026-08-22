<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // 'izipay', 'culqi', 'niubiz', 'mercadopago'
            $table->string('name'); // 'Izipay Perú'
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('mode')->default('sandbox'); // 'sandbox' o 'production'
            $table->text('credentials')->nullable(); // JSON con username, password, public_key, hmac_sha256
            $table->text('settings')->nullable(); // JSON con opciones: cards, qr_yape, pagoefectivo, recurring
            $table->timestamps();
        });

        // Insertar pasarelas por defecto
        DB::table('payment_gateways')->insert([
            [
                'code' => 'izipay',
                'name' => 'Izipay Perú',
                'description' => 'Pasarela líder en Perú con soporte para Tarjetas de Crédito/Débito, Yape, Plin, PagoEfectivo y Tokenización.',
                'logo' => 'https://developers.izipay.pe/favicon.ico',
                'is_active' => true,
                'mode' => 'sandbox',
                'credentials' => json_encode([
                    'username' => env('IZIPAY_USERNAME', ''),
                    'password' => env('IZIPAY_PASSWORD', ''),
                    'public_key' => env('IZIPAY_PUBLIC_KEY', ''),
                    'hmac_sha256' => env('IZIPAY_SHA256_KEY', ''),
                    'client_endpoint' => 'https://api.micuentaweb.pe',
                ]),
                'settings' => json_encode([
                    'enable_cards' => true,
                    'enable_qr_yape' => true,
                    'enable_pagoefectivo' => true,
                    'enable_recurring' => true,
                    'currency' => 'PEN',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'culqi',
                'name' => 'Culqi',
                'description' => 'Acepta pagos con tarjetas y banca móvil en Perú.',
                'logo' => null,
                'is_active' => false,
                'mode' => 'sandbox',
                'credentials' => json_encode([]),
                'settings' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'mercadopago',
                'name' => 'Mercado Pago',
                'description' => 'Cobros online y checkout transparente en toda Latinoamérica.',
                'logo' => null,
                'is_active' => false,
                'mode' => 'sandbox',
                'credentials' => json_encode([]),
                'settings' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
