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
