<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Setting;
use App\Services\CulqiService;
use App\Services\IzipayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Muestra el panel de configuración de métodos de pago.
     */
    public function index(): View
    {
        $settings = Setting::current();
        $izipay = PaymentGateway::getIzipay();
        $culqi = PaymentGateway::getCulqi();
        $otherGateways = PaymentGateway::whereNotIn('code', ['izipay', 'culqi'])->get();

        // Webhook URLs calculadas dinámicamente
        $ipnUrl = url('/api/izipay/ipn');
        $culqiWebhookUrl = url('/api/culqi/webhook');

        return view('web.payment_methods', compact('settings', 'izipay', 'culqi', 'otherGateways', 'ipnUrl', 'culqiWebhookUrl'));
    }

    /**
     * Actualiza la configuración de Izipay en la base de datos.
     */
    public function updateIzipay(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => 'nullable|boolean',
            'mode' => 'required|in:sandbox,production',
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|max:255',
            'public_key' => 'nullable|string|max:255',
            'hmac_sha256' => 'nullable|string|max:255',
            'client_endpoint' => 'nullable|url|max:255',
            'enable_cards' => 'nullable|boolean',
            'enable_qr_yape' => 'nullable|boolean',
            'enable_pagoefectivo' => 'nullable|boolean',
            'enable_recurring' => 'nullable|boolean',
        ]);

        $izipay = PaymentGateway::getIzipay();

        // Si el password o hmac vienen vacíos y ya teníamos uno guardado, conservamos el anterior
        $currentCreds = $izipay->credentials ?? [];
        $password = !empty($validated['password']) ? $validated['password'] : ($currentCreds['password'] ?? '');
        $hmacSha256 = !empty($validated['hmac_sha256']) ? $validated['hmac_sha256'] : ($currentCreds['hmac_sha256'] ?? '');

        $izipay->is_active = $request->has('is_active');
        $izipay->mode = $validated['mode'];
        $izipay->credentials = [
            'username' => trim($validated['username'] ?? ''),
            'password' => trim($password),
            'public_key' => trim($validated['public_key'] ?? ''),
            'hmac_sha256' => trim($hmacSha256),
            'client_endpoint' => $validated['client_endpoint'] ?? 'https://api.micuentaweb.pe',
        ];

        $izipay->settings = [
            'enable_cards' => $request->has('enable_cards'),
            'enable_qr_yape' => $request->has('enable_qr_yape'),
            'enable_pagoefectivo' => $request->has('enable_pagoefectivo'),
            'enable_recurring' => $request->has('enable_recurring'),
            'currency' => 'PEN',
        ];

        $izipay->save();

        return redirect()->route('web.payment_methods', ['tab' => 'izipay'])
            ->with('success', '¡La configuración de Izipay se ha guardado exitosamente en la base de datos!');
    }

    /**
     * Actualiza la configuración de Culqi en la base de datos.
     */
    public function updateCulqi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => 'nullable|boolean',
            'mode' => 'required|in:sandbox,production',
            'public_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'rsa_public_key' => 'nullable|string|max:1000',
            'rsa_id' => 'nullable|string|max:255',
            'enable_cards' => 'nullable|boolean',
            'enable_qr_billeteras' => 'nullable|boolean',
            'enable_yape' => 'nullable|boolean',
            'enable_pagoefectivo' => 'nullable|boolean',
            'enable_cuotealo' => 'nullable|boolean',
        ]);

        $culqi = PaymentGateway::getCulqi();

        // Si la clave secreta viene vacía y ya teníamos una guardada, conservamos la anterior
        $currentCreds = $culqi->credentials ?? [];
        $secretKey = !empty($validated['secret_key']) ? $validated['secret_key'] : ($currentCreds['secret_key'] ?? '');

        $culqi->is_active = $request->has('is_active');
        $culqi->mode = $validated['mode'];
        $culqi->credentials = [
            'public_key' => trim($validated['public_key'] ?? ''),
            'secret_key' => trim($secretKey),
            'rsa_public_key' => trim($validated['rsa_public_key'] ?? ''),
            'rsa_id' => trim($validated['rsa_id'] ?? ''),
        ];

        $culqi->settings = [
            'enable_cards' => $request->has('enable_cards'),
            'enable_qr_billeteras' => $request->has('enable_qr_billeteras'),
            'enable_yape' => $request->has('enable_yape'),
            'enable_pagoefectivo' => $request->has('enable_pagoefectivo'),
            'enable_cuotealo' => $request->has('enable_cuotealo'),
            'currency' => 'PEN',
        ];

        $culqi->save();

        return redirect()->route('web.payment_methods', ['tab' => 'culqi'])
            ->with('success', '¡La configuración de Culqi (QR & Tarjetas) se ha guardado exitosamente!');
    }

    /**
     * Prueba de conexión con la API de Izipay vía AJAX.
     */
    public function testIzipayConnection(Request $request, IzipayService $izipayService): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $endpoint = $request->input('client_endpoint');

        $result = $izipayService->testConnection($username, $password, $endpoint);

        return response()->json($result);
    }

    /**
     * Prueba de conexión con la API de Culqi vía AJAX.
     */
    public function testCulqiConnection(Request $request, CulqiService $culqiService): JsonResponse
    {
        $secretKey = $request->input('secret_key');
        $publicKey = $request->input('public_key');

        $result = $culqiService->testConnection($secretKey, $publicKey);

        return response()->json($result);
    }
}
