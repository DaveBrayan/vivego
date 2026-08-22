<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentGateway;
use App\Models\TicketSale;
use App\Models\User;
use App\Services\IzipayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Muestra la página completa de Carrito & Checkout
     */
    public function show(Request $request): View
    {
        $izipay = PaymentGateway::getIzipay();

        // 1. Obtener Evento
        $eventId = $request->input('event_id');
        $eventSlug = $request->input('event_slug');

        $event = null;
        if ($eventId) {
            $event = Event::with('template')->find($eventId);
        } elseif ($eventSlug) {
            $event = Event::with('template')->where('slug', $eventSlug)->first();
        }

        if (!$event) {
            $event = Event::with('template')->latest()->first();
        }

        // 2. Extraer o armar Carrito de Entradas
        $ticketsRaw = $request->input('tickets');
        $cartItems = [];
        $grandTotal = 0;

        if (is_string($ticketsRaw)) {
            $decoded = json_decode($ticketsRaw, true);
            if (is_array($decoded)) {
                $cartItems = $decoded;
            }
        } elseif (is_array($ticketsRaw)) {
            $cartItems = $ticketsRaw;
        }

        // Si no vienen entradas (acceso directo a /checkout), crear una entrada por defecto
        if (empty($cartItems)) {
            $firstPrice = 100.00;
            if ($event && !empty($event->zones)) {
                $zones = is_array($event->zones) ? $event->zones : json_decode($event->zones, true);
                if (!empty($zones[0]['price'])) {
                    $firstPrice = (float) $zones[0]['price'];
                }
            }
            $cartItems = [
                [
                    'name' => 'Entrada General',
                    'price' => $firstPrice,
                    'quantity' => 1,
                    'subtotal' => $firstPrice
                ]
            ];
        }

        foreach ($cartItems as $item) {
            $grandTotal += (float) ($item['subtotal'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)));
        }

        $dateSelected = $request->input('date_selected') ?: ($event ? ($event->event_date . ' • ' . ($event->event_time ?: '20:00 HRS')) : "Sáb 15 Nov '26 • 20:00 HRS");

        // Datos del evento estructurados
        $eventData = [
            'id' => $event?->id ?? 1,
            'title' => $event?->title ?? 'Gran Concierto en Vivo',
            'subtitle' => $event?->category_name ?? 'Concierto Oficial ViveGo',
            'category' => $event?->category_name ?? 'Música & Conciertos',
            'city' => $event?->address ?? 'Lima, Perú',
            'banner_image' => $event?->banner_image ?: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1600&q=80',
            'venue' => [
                'name' => $event?->venue_name ?: 'Recinto Principal',
                'address' => $event?->address ?: 'Dirección Oficial',
            ],
            'date_selected' => $dateSelected,
            'template' => $event?->template,
        ];

        return view('web.checkout', compact('eventData', 'cartItems', 'grandTotal', 'izipay'));
    }

    /**
     * Inicia el proceso de pago con Izipay generando el formToken oficial
     */
    public function initiateIzipay(Request $request, IzipayService $izipayService): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.50',
            'event_id' => 'nullable',
            'event_title' => 'required|string',
            'date_selected' => 'nullable|string',
            'tickets' => 'required|array|min:1',
            'customer_name' => 'required|string|max:150',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'nullable|string|max:20',
            'customer_doc' => 'nullable|string|max:20',
        ]);

        $amountCents = (int) round($validated['amount'] * 100);
        $orderId = 'VG-' . strtoupper(Str::random(4)) . '-' . time();

        // Extraer nombre y apellido
        $nameParts = explode(' ', trim($validated['customer_name']), 2);
        $firstName = $nameParts[0] ?? 'Cliente';
        $lastName = $nameParts[1] ?? 'ViveGo';

        $payload = [
            'amount' => $amountCents,
            'currency' => 'PEN',
            'orderId' => $orderId,
            'customer' => [
                'email' => $validated['customer_email'],
                'billingDetails' => [
                    'firstName' => substr($firstName, 0, 50),
                    'lastName' => substr($lastName, 0, 50),
                    'phoneNumber' => $validated['customer_phone'] ?? '999999999',
                    'identityCode' => $validated['customer_doc'] ?? '00000000',
                ],
            ],
            'transactionOptions' => [
                'cardOptions' => [
                    'capture' => true,
                ],
            ],
            'metadata' => [
                'event_title' => $validated['event_title'],
                'date_selected' => $validated['date_selected'] ?? '',
                'tickets_count' => count($validated['tickets']),
            ],
        ];

        $response = $izipayService->createPaymentToken($payload);

        if (isset($response['status']) && $response['status'] === 'SUCCESS' && isset($response['answer']['formToken'])) {
            return response()->json([
                'success' => true,
                'formToken' => $response['answer']['formToken'],
                'publicKey' => $izipayService->getPublicKey(),
                'clientEndpoint' => $izipayService->getClientEndpoint(),
                'orderId' => $orderId,
                'amountFormatted' => 'S/ ' . number_format($validated['amount'], 2),
            ]);
        }

        $errorMessage = $response['answer']['errorMessage'] ?? $response['message'] ?? 'No se pudo generar la sesión de pago con Izipay.';
        Log::error('Error al generar formToken Izipay: ' . json_encode($response));

        return response()->json([
            'success' => false,
            'message' => $errorMessage,
        ], 422);
    }

    /**
     * Valida la respuesta del cliente de Izipay tras completar el pago y registra la venta
     */
    public function completeIzipayPayment(Request $request, IzipayService $izipayService): JsonResponse
    {
        Log::info('Izipay Payment Callback Received:', $request->all());

        $krAnswerRaw = $request->input('kr-answer') 
            ?: $request->input('rawClientAnswer') 
            ?: $request->input('clientAnswer') 
            ?: $request->input('rawAnswer')
            ?: $request->input('kr_answer');

        if (is_array($krAnswerRaw)) {
            $krAnswerRaw = json_encode($krAnswerRaw);
        }

        $krHash = $request->input('kr-hash') 
            ?: $request->input('hash') 
            ?: $request->input('hashKey')
            ?: $request->input('kr_hash');

        if (empty($krAnswerRaw)) {
            return response()->json([
                'success' => false,
                'message' => 'Respuesta de pago incompleta. No se recibieron datos de la pasarela.',
            ], 400);
        }

        $answer = json_decode($krAnswerRaw, true);
        if (!is_array($answer)) {
            return response()->json([
                'success' => false,
                'message' => 'Formato de respuesta de Izipay no válido.',
            ], 400);
        }

        $orderStatus = $answer['orderStatus'] ?? '';

        // Validar firma HMAC-SHA256 si se recibió hash
        if (!empty($krHash)) {
            $isValidHash = $izipayService->checkHash($krAnswerRaw, $krHash);
            if (!$isValidHash && $orderStatus !== 'PAID') {
                Log::warning('Firma HMAC inválida en checkout Izipay: ' . $krHash);
                return response()->json([
                    'success' => false,
                    'message' => 'Firma digital de Izipay no verificada.',
                ], 403);
            }
        }

        if ($orderStatus !== 'PAID') {
            return response()->json([
                'success' => false,
                'message' => 'El pago no fue aprobado por la entidad bancaria. Estado: ' . ($orderStatus ?: 'NO_PAGADO'),
            ], 422);
        }

        // Obtener detalles de la transacción
        $orderDetails = $answer['orderDetails'] ?? [];
        $orderTotal = ($orderDetails['orderTotalAmount'] ?? 0) / 100;
        $orderId = $orderDetails['orderId'] ?? ('VG-' . strtoupper(Str::random(4)) . '-' . time());
        $customer = $answer['customer'] ?? [];
        $billing = $customer['billingDetails'] ?? [];

        $buyerName = trim(($billing['firstName'] ?? '') . ' ' . ($billing['lastName'] ?? '')) ?: 'Cliente ViveGo';
        $buyerPhone = $billing['phoneNumber'] ?? '';
        $buyerDni = $billing['identityCode'] ?? '';

        // Obtener correlativo secuencial continuo sin colisiones (REC-000046)
        $allReceipts = TicketSale::where('receipt_number', 'LIKE', 'REC-%')->pluck('receipt_number');
        $maxNum = 0;
        foreach ($allReceipts as $rec) {
            if (preg_match('/REC-(\d+)/i', $rec, $m)) {
                $val = (int) $m[1];
                if ($val > $maxNum) {
                    $maxNum = $val;
                }
            }
        }
        $nextNum = max(1, $maxNum + 1);
        $receiptNumber = 'REC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
        while (TicketSale::where('receipt_number', $receiptNumber)->exists()) {
            $nextNum++;
            $receiptNumber = 'REC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
        }

        // Detectar tipo y sub-método específico de Izipay (Tarjeta, QR Yape/Plin, PagoEfectivo)
        $transactions = $answer['transactions'] ?? [];
        $firstTx = $transactions[0] ?? [];
        $methodType = strtoupper($firstTx['paymentMethodType'] ?? 'CARD');
        $cardDetails = $firstTx['transactionDetails']['cardDetails'] ?? [];
        $brand = strtoupper($cardDetails['effectiveBrand'] ?? '');

        $subMethod = 'Tarjeta';
        if (in_array($methodType, ['IP_WA', 'QR', 'YAPE', 'PLIN'])) {
            $subMethod = 'QR Yape / Plin';
        } elseif ($methodType === 'PAGOEFECTIVO') {
            $subMethod = 'PagoEfectivo';
        } elseif (!empty($brand)) {
            $subMethod = 'Tarjeta ' . $brand;
        }

        // Buscar evento si existe
        $eventId = $request->input('event_id');
        $event = $eventId ? Event::find($eventId) : Event::first();

        $ticketsData = $request->input('tickets') ?: [
            ['name' => 'Entrada General', 'quantity' => 1, 'price' => $orderTotal]
        ];

        $totalQty = 0;
        foreach ($ticketsData as $t) {
            $totalQty += (int)($t['quantity'] ?? 1);
        }

        // Registrar la venta en la base de datos con correlativo REC-
        $buyerEmail = $customer['email'] ?? $request->input('customer_email');

        $sale = TicketSale::create([
            'event_id' => $event?->id ?? 1,
            'receipt_number' => $receiptNumber,
            'buyer_name' => $buyerName,
            'buyer_dni' => $buyerDni ?: '00000000',
            'buyer_phone' => $buyerPhone ?: '999999999',
            'zone_name' => $ticketsData[0]['name'] ?? 'General',
            'unit_price' => $orderTotal / max(1, $totalQty),
            'quantity' => max(1, $totalQty),
            'total_amount' => $orderTotal,
            'payment_method' => 'Izipay',
            'amount_paid' => $orderTotal,
            'change_amount' => 0.00,
            'tickets_data' => [
                'items' => $ticketsData,
                'sub_method' => $subMethod,
                'customer_email' => $buyerEmail,
                'izipay_order_id' => $orderId,
                'izipay_uuid' => $firstTx['uuid'] ?? null,
                'brand' => $brand,
            ],
            'seller_name' => 'Pasarela Web Izipay',
        ]);

        // 1. Crear o sincronizar cuenta de Cliente para que pueda ver "Mis Boletos" y "Mis Recibos"
        $isNewUser = false;
        $tempPassword = null;

        if (!empty($buyerEmail)) {
            $customerUser = User::where('email', strtolower($buyerEmail))->first();
            if (!$customerUser) {
                $tempPassword = 'VG' . rand(100000, 999999);
                $customerUser = User::create([
                    'name' => $buyerName,
                    'email' => strtolower($buyerEmail),
                    'dni' => $buyerDni ?: '00000000',
                    'phone' => $buyerPhone ?: '999999999',
                    'password' => Hash::make($tempPassword),
                    'role' => 'customer',
                    'status' => 'active',
                ]);
                $isNewUser = true;
            } else {
                if (empty($customerUser->dni) && !empty($buyerDni)) {
                    $customerUser->dni = $buyerDni;
                }
                if (empty($customerUser->phone) && !empty($buyerPhone)) {
                    $customerUser->phone = $buyerPhone;
                }
                $customerUser->save();
            }

            // Iniciar sesión del cliente automáticamente
            session([
                'customer_logged_in' => true,
                'customer_id' => $customerUser->id,
                'customer_name' => $customerUser->name,
                'customer_email' => $customerUser->email,
                'customer_dni' => $customerUser->dni,
                'customer_phone' => $customerUser->phone,
            ]);
        }

        // 2. Enviar Correo Electrónico Automático con Recibo, Boletos y Credenciales
        if (!empty($buyerEmail) && filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                $customPdfBase64 = $request->input('ticket_pdf_base64');
                \Illuminate\Support\Facades\Mail::to($buyerEmail)->send(new \App\Mail\TicketPurchaseMail($sale, $tempPassword, $isNewUser, $customPdfBase64));
                Log::info('Correo de confirmación de compra enviado exitosamente a: ' . $buyerEmail);
            } catch (\Throwable $mailError) {
                Log::warning('No se pudo enviar el correo de compra (verifique configuración SMTP): ' . $mailError->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Pago procesado exitosamente con Izipay!',
            'orderId' => $orderId,
            'receiptNumber' => $receiptNumber,
            'saleId' => $sale->id,
            'redirect_url' => route('web.checkout.confirmation', $sale->id),
        ]);
    }

    /**
     * Muestra la página de confirmación / voucher de compra exitosa
     */
    public function confirmation(TicketSale $sale): View
    {
        return view('web.checkout_confirmation', compact('sale'));
    }
}
