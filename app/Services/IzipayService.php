<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IzipayService
{
    protected PaymentGateway $gateway;
    protected string $username;
    protected string $password;
    protected string $publicKey;
    protected string $hmacSha256;
    protected string $endpoint;
    protected string $mode;

    public function __construct()
    {
        $this->gateway = PaymentGateway::getIzipay();
        $this->mode = $this->gateway->mode ?? 'sandbox';
        
        $this->username = $this->gateway->getCredential('username') ?: env('IZIPAY_USERNAME', '');
        $this->password = $this->gateway->getCredential('password') ?: env('IZIPAY_PASSWORD', '');
        $this->publicKey = $this->gateway->getCredential('public_key') ?: env('IZIPAY_PUBLIC_KEY', '');
        $this->hmacSha256 = $this->gateway->getCredential('hmac_sha256') ?: env('IZIPAY_SHA256_KEY', '');
        $this->endpoint = rtrim($this->gateway->getCredential('client_endpoint', 'https://api.micuentaweb.pe'), '/');
    }

    /**
     * Obtener headers con autenticación básica para la API REST de Izipay
     */
    protected function getHeaders(): array
    {
        $auth = base64_encode("{$this->username}:{$this->password}");

        return [
            'Authorization' => "Basic {$auth}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Verifica la validez de las credenciales enviadas realizando una llamada de prueba
     */
    public function testConnection(?string $username = null, ?string $password = null, ?string $endpoint = null): array
    {
        $user = $username ?: $this->username;
        $pass = $password ?: $this->password;
        $url = rtrim($endpoint ?: $this->endpoint, '/');

        if (empty($user) || empty($pass)) {
            return [
                'success' => false,
                'message' => 'El Usuario y la Clave de API REST son obligatorios para probar la conexión.',
            ];
        }

        try {
            $auth = base64_encode("{$user}:{$pass}");
            
            // Realizamos una petición de prueba básica a la API REST de Izipay
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => "Basic {$auth}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(12)->post("{$url}/api-payment/V4/Charge/CreatePayment", [
                    'amount' => 100, // 1.00 PEN de prueba
                    'currency' => 'PEN',
                    'orderId' => 'TEST-' . time(),
                    'customer' => [
                        'email' => 'test@vivego.pe',
                    ],
                ]);

            $data = $response->json();

            if ($response->status() === 200 && isset($data['status']) && $data['status'] === 'SUCCESS') {
                return [
                    'success' => true,
                    'message' => '¡Conexión exitosa con la API de Izipay! Las credenciales son válidas.',
                    'mode' => $data['answer']['mode'] ?? $this->mode,
                ];
            }

            if ($response->status() === 401 || $response->status() === 403) {
                return [
                    'success' => false,
                    'message' => 'Error de autenticación (401/403): El Usuario o la Clave de API REST son incorrectos para este entorno.',
                ];
            }

            return [
                'success' => false,
                'message' => $data['answer']['errorMessage'] ?? $data['message'] ?? 'Respuesta inesperada de Izipay. Código HTTP: ' . $response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'No se pudo contactar con los servidores de Izipay: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Genera un formToken para renderizar el formulario incrustado de Izipay
     */
    public function createPaymentToken(array $payload): array
    {
        try {
            $url = "{$this->endpoint}/api-payment/V4/Charge/CreatePayment";

            $response = Http::withoutVerifying()
                ->withHeaders($this->getHeaders())
                ->timeout(15)
                ->post($url, $payload);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('Error al crear formToken en Izipay: ' . $e->getMessage());
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Valida la firma HMAC-SHA256 de las notificaciones o respuestas
     */
    public function checkHash(string $krAnswer, string $krHash): bool
    {
        $calculatedHash = hash_hmac('sha256', $krAnswer, $this->hmacSha256);
        return hash_equals($calculatedHash, $krHash);
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getClientEndpoint(): string
    {
        return $this->endpoint;
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->gateway->is_active ?? false);
    }
}
