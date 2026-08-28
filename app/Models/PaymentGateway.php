<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $table = 'payment_gateways';

    protected $fillable = [
        'code',
        'name',
        'description',
        'logo',
        'is_active',
        'mode',
        'credentials',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credentials' => 'array',
        'settings' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($gateway) {
            Cache::forget("payment_gateway_{$gateway->code}");
            Cache::forget('active_payment_gateways');
        });
    }

    /**
     * Obtener pasarela Izipay
     */
    public static function getIzipay(): self
    {
        try {
            return Cache::remember('payment_gateway_izipay', 3600, function () {
                return static::firstOrCreate(
                    ['code' => 'izipay'],
                    [
                        'name' => 'Izipay Perú',
                        'description' => 'Pasarela líder en Perú con soporte para Tarjetas de Crédito/Débito, Yape, Plin, PagoEfectivo y Tokenización.',
                        'is_active' => true,
                        'mode' => 'sandbox',
                        'credentials' => [
                            'username' => env('IZIPAY_USERNAME', ''),
                            'password' => env('IZIPAY_PASSWORD', ''),
                            'public_key' => env('IZIPAY_PUBLIC_KEY', ''),
                            'hmac_sha256' => env('IZIPAY_SHA256_KEY', ''),
                            'client_endpoint' => 'https://api.micuentaweb.pe',
                        ],
                        'settings' => [
                            'enable_cards' => true,
                            'enable_qr_yape' => true,
                            'enable_pagoefectivo' => true,
                            'enable_recurring' => true,
                            'currency' => 'PEN',
                        ],
                    ]
                );
            });
        } catch (\Throwable $e) {
            $gw = new static();
            $gw->code = 'izipay';
            $gw->name = 'Izipay Perú';
            $gw->is_active = true;
            $gw->mode = 'sandbox';
            $gw->credentials = [];
            $gw->settings = [];
            return $gw;
        }
    }

    /**
     * Obtener pasarela Culqi
     */
    public static function getCulqi(): self
    {
        try {
            return Cache::remember('payment_gateway_culqi', 3600, function () {
                return static::firstOrCreate(
                    ['code' => 'culqi'],
                    [
                        'name' => 'Culqi Perú',
                        'description' => 'Pasarela oficial Culqi con soporte para Pagos con QR, Billeteras Móviles (Yape, Plin), Tarjetas de Crédito/Débito y PagoEfectivo.',
                        'is_active' => true,
                        'mode' => 'sandbox',
                        'credentials' => [
                            'public_key' => env('CULQI_PUBLIC_KEY', 'pk_test_FWqAYzGtYxLvF1yD'),
                            'secret_key' => env('CULQI_SECRET_KEY', 'sk_test_JqupNZ6U3CY8lr0V'),
                            'rsa_public_key' => env('CULQI_RSA_PUBLIC_KEY', ''),
                            'rsa_id' => env('CULQI_RSA_ID', ''),
                        ],
                        'settings' => [
                            'enable_cards' => true,
                            'enable_qr_billeteras' => true,
                            'enable_yape' => true,
                            'enable_pagoefectivo' => true,
                            'enable_cuotealo' => false,
                            'currency' => 'PEN',
                        ],
                    ]
                );
            });
        } catch (\Throwable $e) {
            $gw = new static();
            $gw->code = 'culqi';
            $gw->name = 'Culqi Perú';
            $gw->is_active = true;
            $gw->mode = 'sandbox';
            $gw->credentials = [
                'public_key' => env('CULQI_PUBLIC_KEY', 'pk_test_FWqAYzGtYxLvF1yD'),
                'secret_key' => env('CULQI_SECRET_KEY', 'sk_test_JqupNZ6U3CY8lr0V'),
            ];
            $gw->settings = [];
            return $gw;
        }
    }

    /**
     * Obtiene el valor de una credencial específica
     */
    public function getCredential(string $key, mixed $default = null): mixed
    {
        $creds = $this->credentials ?? [];
        return $creds[$key] ?? $default;
    }

    /**
     * Obtiene el valor de un ajuste específico
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        $settings = $this->settings ?? [];
        return $settings[$key] ?? $default;
    }

    /**
     * Retorna si está en modo Sandbox
     */
    public function isSandbox(): bool
    {
        return $this->mode === 'sandbox';
    }

    /**
     * Retorna el endpoint base según el modo o configuración
     */
    public function getEndpoint(): string
    {
        return $this->getCredential('client_endpoint', 'https://api.micuentaweb.pe');
    }
}
