<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Culqi\Culqi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CulqiService
{
    protected PaymentGateway $gateway;
    protected string $publicKey;
    protected string $secretKey;
    protected string $rsaPublicKey;
    protected string $rsaId;
    protected string $mode;
    protected ?Culqi $culqiClient = null;

    public function __construct()
    {
        $this->gateway = PaymentGateway::getCulqi();
        $this->mode = $this->gateway->mode ?? 'sandbox';
        
        $this->publicKey = $this->gateway->getCredential('public_key') ?: env('CULQI_PUBLIC_KEY', '');
        $this->secretKey = $this->gateway->getCredential('secret_key') ?: env('CULQI_SECRET_KEY', '');
        $this->rsaPublicKey = $this->gateway->getCredential('rsa_public_key') ?: env('CULQI_RSA_PUBLIC_KEY', '');
        $this->rsaId = $this->gateway->getCredential('rsa_id') ?: env('CULQI_RSA_ID', '');

        if (!empty($this->secretKey) && class_exists(Culqi::class)) {
            try {
                $this->culqiClient = new Culqi(['api_key' => $this->secretKey]);
            } catch (\Throwable $e) {
                Log::warning('No se pudo inicializar Culqi SDK: ' . $e->getMessage());
            }
        }
    }

    /**
     * Obtiene headers de autenticación para llamadas directas a la API REST de Culqi
     */
    protected function getHeaders(?string $customSecretKey = null): array
    {
        $key = $customSecretKey ?: $this->secretKey;

        return [
            'Authorization' => "Bearer {$key}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Valida las credenciales de Culqi realizando una prueba en vivo con la API oficial
     */
    public function testConnection(?string $secretKey = null, ?string $publicKey = null): array
    {
        $secKey = trim($secretKey ?: $this->secretKey);
        $pubKey = trim($publicKey ?: $this->publicKey);

        if (empty($secKey)) {
            return [
                'success' => false,
                'message' => 'La Llave Secreta (Secret Key) es obligatoria para probar la conexión con Culqi.',
            ];
        }

        // Determinar entorno según prefijo de la llave
        $detectedMode = str_starts_with($secKey, 'sk_live_') ? 'Producción (Live)' : 'Pruebas (Sandbox / Test)';

        try {
            // Intentar listar o consultar tokens/cargos de prueba a Culqi API v2
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$secKey}",
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->timeout(12)->get('https://api.culqi.com/v2/charges', [
                'limit' => 1,
            ]);

            $statusCode = $response->status();
            $data = $response->json();

            if ($statusCode === 200) {
                return [
                    'success' => true,
                    'message' => "¡Conexión exitosa con los servidores de Culqi! Credenciales válidas en entorno {$detectedMode}.",
                    'mode' => $detectedMode,
                ];
            }

            if ($statusCode === 401 || $statusCode === 403) {
                return [
                    'success' => false,
                    'message' => 'Error de autenticación (401/403): La Llave Secreta es incorrecta o no tiene permisos en Culqi.',
                ];
            }

            $errMsg = $data['user_message'] ?? $data['merchant_message'] ?? $data['message'] ?? 'Error desconocido de Culqi.';
            return [
                'success' => false,
                'message' => "Respuesta de Culqi: {$errMsg} (HTTP {$statusCode})",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'No se pudo contactar con los servidores de Culqi: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Crea una Orden de Pago en Culqi (indispensable para pagos con QR, Billeteras Móviles y PagoEfectivo)
     */
    public function createOrder(array $payload): array
    {
        try {
            // Petición HTTP directa a Culqi API v2 (con fallback seguro SSL)
            $response = Http::withHeaders($this->getHeaders())
                ->withoutVerifying()
                ->timeout(15)
                ->post('https://api.culqi.com/v2/orders', $payload);

            $data = $response->json();
            if ($response->successful() && is_array($data)) {
                return $data;
            }

            if ($this->culqiClient && isset($this->culqiClient->Orders)) {
                $order = $this->culqiClient->Orders->create($payload);
                if (is_object($order)) {
                    return (array) $order;
                }
                if (is_string($order)) {
                    $decoded = json_decode($order, true);
                    return is_array($decoded) ? $decoded : ['id' => $order];
                }
                return (array) $order;
            }

            return $data ?? ['object' => 'error', 'user_message' => 'Error al comunicarse con Culqi (' . $response->status() . ')'];
        } catch (\Throwable $e) {
            Log::error('Error al crear orden en Culqi: ' . $e->getMessage());
            return [
                'object' => 'error',
                'user_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Crea un Cargo (Charge) en Culqi a partir de un Token de tarjeta o fuente de pago
     */
    public function createCharge(array $payload): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->withoutVerifying()
                ->timeout(15)
                ->post('https://api.culqi.com/v2/charges', $payload);

            $data = $response->json();
            if ($response->successful() && is_array($data)) {
                return $data;
            }

            if ($this->culqiClient && isset($this->culqiClient->Charges)) {
                $charge = $this->culqiClient->Charges->create($payload);
                if (is_object($charge)) {
                    return (array) $charge;
                }
                if (is_string($charge)) {
                    $decoded = json_decode($charge, true);
                    return is_array($decoded) ? $decoded : ['id' => $charge];
                }
                return (array) $charge;
            }

            return $data ?? ['object' => 'error', 'user_message' => 'Error al procesar cargo con Culqi (' . $response->status() . ')'];
        } catch (\Throwable $e) {
            Log::error('Error al crear cargo en Culqi: ' . $e->getMessage());
            return [
                'object' => 'error',
                'user_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Consulta el estado de una Orden en Culqi (para pagos QR / PagoEfectivo)
     */
    public function getOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->withoutVerifying()
                ->timeout(12)
                ->get("https://api.culqi.com/v2/orders/{$orderId}");

            $data = $response->json();
            if ($response->successful() && is_array($data)) {
                return $data;
            }

            if ($this->culqiClient && isset($this->culqiClient->Orders)) {
                $order = $this->culqiClient->Orders->get($orderId);
                if (is_object($order)) {
                    return (array) $order;
                }
                if (is_string($order)) {
                    $decoded = json_decode($order, true);
                    return is_array($decoded) ? $decoded : ['id' => $order];
                }
                return (array) $order;
            }

            return $data ?? [];
        } catch (\Throwable $e) {
            Log::error('Error al consultar orden en Culqi: ' . $e->getMessage());
            return [
                'object' => 'error',
                'user_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Consulta el detalle de un Cargo en Culqi
     */
    public function getCharge(string $chargeId): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->withoutVerifying()
                ->timeout(12)
                ->get("https://api.culqi.com/v2/charges/{$chargeId}");

            $data = $response->json();
            if ($response->successful() && is_array($data)) {
                return $data;
            }

            if ($this->culqiClient && isset($this->culqiClient->Charges)) {
                $charge = $this->culqiClient->Charges->get($chargeId);
                return is_object($charge) ? (array) $charge : (is_array($charge) ? $charge : json_decode($charge, true) ?? []);
            }

            return $data ?? [];
        } catch (\Throwable $e) {
            Log::error('Error al consultar cargo en Culqi: ' . $e->getMessage());
            return [
                'object' => 'error',
                'user_message' => $e->getMessage(),
            ];
        }
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getRsaPublicKey(): string
    {
        return $this->rsaPublicKey;
    }

    public function getRsaId(): string
    {
        return $this->rsaId;
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->gateway->is_active ?? false);
    }

    public function isSandbox(): bool
    {
        return $this->mode === 'sandbox';
    }

    public function getGateway(): PaymentGateway
    {
        return $this->gateway;
    }
}
