<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Setting;
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
        $otherGateways = PaymentGateway::where('code', '!=', 'izipay')->get();

        // Webhook URL calculada dinámicamente
        $ipnUrl = url('/api/izipay/ipn');

        return view('web.payment_methods', compact('settings', 'izipay', 'otherGateways', 'ipnUrl'));
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

        return redirect()->route('web.payment_methods')
            ->with('success', '¡La configuración de Izipay se ha guardado exitosamente en la base de datos!');
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
}
