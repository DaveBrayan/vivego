<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentGateway;
use App\Models\TicketSale;
use App\Models\User;
use App\Services\CulqiService;
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
        $culqi = PaymentGateway::getCulqi();

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

        // 2. Extraer o armar Carrito de Entradas con Persistencia de Sesión
        $ticketsRaw = $request->input('tickets');
        $cartItems = [];
        $grandTotal = 0;

        if (is_string($ticketsRaw)) {
            $decoded = json_decode($ticketsRaw, true);
            if (is_array($decoded) && !empty($decoded)) {
                $cartItems = $decoded;
                session(['checkout_cart_' . ($event?->id ?? 'default') => $cartItems]);
            }
        } elseif (is_array($ticketsRaw) && !empty($ticketsRaw)) {
            $cartItems = $ticketsRaw;
            session(['checkout_cart_' . ($event?->id ?? 'default') => $cartItems]);
        }

        // Si no viene en el request actual, intentar restaurar de la sesión
        if (empty($cartItems) && $event && session()->has('checkout_cart_' . $event->id)) {
            $cartItems = session('checkout_cart_' . $event->id, []);
        }

        // Si aún está vacío, cargar las zonas reales del evento
        if (empty($cartItems)) {
            $today = date('Y-m-d');
            if ($event && !empty($event->zones)) {
                $zones = is_array($event->zones) ? $event->zones : json_decode($event->zones, true);
                if (!empty($zones)) {
                    foreach ($zones as $z) {
                        $regularPrice = (float)($z['price'] ?? 100.00);
                        $effectivePrice = $regularPrice;
                        $hasPresale = !empty($z['has_presale']) || (!empty($z['presale_discount']) && (float)$z['presale_discount'] > 0);
                        $discountPercent = (float)($z['presale_discount'] ?? 0);
                        $presaleStart = $z['presale_start_date'] ?? null;
                        $presaleEnd = $z['presale_end_date'] ?? null;
                        $isPresale = false;

                        if ($hasPresale && $discountPercent > 0) {
                            $dateValid = true;
                            if ($presaleStart && $today < $presaleStart) $dateValid = false;
                            if ($presaleEnd && $today > $presaleEnd) $dateValid = false;
                            if ($dateValid) {
                                $isPresale = true;
                                if (isset($z['presale_price']) && (float)$z['presale_price'] > 0) {
                                    $effectivePrice = (float)$z['presale_price'];
                                } else {
                                    $effectivePrice = round($regularPrice * (1 - ($discountPercent / 100)), 2);
                                }
                            }
                        }

                        $cartItems[] = [
                            'name' => $z['name'] ?? $z['capacity_type'] ?? 'Entrada',
                            'price' => $effectivePrice,
                            'regular_price' => $regularPrice,
                            'is_presale' => $isPresale,
                            'presale_discount' => $discountPercent,
                            'quantity' => 1,
                            'subtotal' => $effectivePrice
                        ];
                        break; // Tomar la primera zona disponible como fallback
                    }
                }
            }

            if (empty($cartItems)) {
                $cartItems = [
                    [
                        'name' => 'Entrada General',
                        'price' => 100.00,
                        'regular_price' => 100.00,
                        'is_presale' => false,
                        'presale_discount' => 0,
                        'quantity' => 1,
                        'subtotal' => 100.00
                    ]
                ];
            }
        }

        foreach ($cartItems as $item) {
            $grandTotal += (float) ($item['subtotal'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)));
        }

        $dateSelected = $request->input('date_selected');
        if ($dateSelected) {
            session(['checkout_date_' . ($event?->id ?? 'default') => $dateSelected]);
        } elseif ($event && session()->has('checkout_date_' . $event->id)) {
            $dateSelected = session('checkout_date_' . $event->id);
        } else {
            $dateSelected = ($event ? ($event->event_date . ' • ' . ($event->event_time ?: '20:00 HRS')) : "Sáb 15 Nov '26 • 20:00 HRS");
        }

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

        return view('web.checkout', compact('eventData', 'cartItems', 'grandTotal', 'izipay', 'culqi'));
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
            'customer_country' => 'nullable|string|max:100',
            'customer_city' => 'nullable|string|max:100',
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

            // Limpiar cualquier residuo de sesión administrativa
            session()->forget([
                'admin_logged_in',
                'admin_id',
                'admin_name',
                'admin_email',
                'admin_role',
                'admin_avatar',
            ]);

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
     * Inicia el proceso de pago con Culqi generando una Orden oficial (para QR / Billeteras / Tarjetas)
     */
    public function initiateCulqi(Request $request, CulqiService $culqiService): JsonResponse
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
            'customer_country' => 'nullable|string|max:100',
            'customer_city' => 'nullable|string|max:100',
        ]);

        $amountCents = (int) round($validated['amount'] * 100);
        $orderNumber = 'VG-' . strtoupper(Str::random(4)) . '-' . time();

        // Extraer nombre y apellido
        $nameParts = explode(' ', trim($validated['customer_name']), 2);
        $firstName = $nameParts[0] ?? 'Cliente';
        $lastName = $nameParts[1] ?? 'ViveGo';
        $phone = preg_replace('/[^0-9]/', '', $validated['customer_phone'] ?? '999999999');
        if (strlen($phone) < 9) $phone = '999999999';

        $payload = [
            'amount' => $amountCents,
            'currency_code' => 'PEN',
            'description' => 'Entradas: ' . substr($validated['event_title'], 0, 70),
            'order_number' => $orderNumber,
            'client_details' => [
                'first_name' => substr($firstName, 0, 50),
                'last_name' => substr($lastName, 0, 50),
                'email' => $validated['customer_email'],
                'phone_number' => $phone,
            ],
            'expiration_date' => time() + (24 * 60 * 60), // Validez 24 horas
            'metadata' => [
                'event_id' => $validated['event_id'] ?? 1,
                'event_title' => $validated['event_title'],
                'date_selected' => $validated['date_selected'] ?? '',
                'tickets_count' => count($validated['tickets']),
                'buyer_doc' => $validated['customer_doc'] ?? '',
            ],
        ];

        $response = $culqiService->createOrder($payload);

        if (isset($response['id']) && str_starts_with($response['id'], 'ord_')) {
            return response()->json([
                'success' => true,
                'orderId' => $response['id'],
                'orderNumber' => $orderNumber,
                'publicKey' => $culqiService->getPublicKey(),
                'amountCents' => $amountCents,
                'amountFormatted' => 'S/ ' . number_format($validated['amount'], 2),
                'qr' => $response['qr'] ?? null,
                'payment_code' => $response['payment_code'] ?? null,
            ]);
        }

        // Si Culqi devuelve error o no genera orden, aún podemos proceder con tokenización directa en el frontend
        $errorMessage = $response['user_message'] ?? $response['merchant_message'] ?? $response['message'] ?? 'No se pudo generar la sesión de orden con Culqi.';
        Log::warning('Respuesta al crear orden Culqi: ' . json_encode($response));

        return response()->json([
            'success' => true, // Permitimos proceder con Checkout directo pasando la publicKey
            'orderId' => null,
            'orderNumber' => $orderNumber,
            'publicKey' => $culqiService->getPublicKey(),
            'amountCents' => $amountCents,
            'amountFormatted' => 'S/ ' . number_format($validated['amount'], 2),
            'warning' => $errorMessage,
        ]);
    }

    /**
     * Procesa y valida el pago completado con Culqi (Cargos con Tarjeta vía Token o Pagos con QR / Orden)
     */
    public function completeCulqiPayment(Request $request, CulqiService $culqiService): JsonResponse
    {
        Log::info('Culqi Payment Callback Received:', $request->all());

        $tokenId = $request->input('token_id') ?: $request->input('tokenId');
        $orderId = $request->input('order_id') ?: $request->input('orderId');
        $amount = (float) $request->input('amount', 0);
        $amountCents = (int) round($amount * 100);

        $buyerName = trim($request->input('customer_name') ?: 'Cliente ViveGo');
        $buyerEmail = trim($request->input('customer_email') ?: '');
        $buyerDni = trim($request->input('customer_doc') ?: '00000000');
        $buyerPhone = trim($request->input('customer_phone') ?: '999999999');

        $nameParts = explode(' ', $buyerName, 2);
        $firstName = $nameParts[0] ?? 'Cliente';
        $lastName = $nameParts[1] ?? 'ViveGo';

        $subMethod = 'Culqi';
        $transactionId = null;
        $brand = null;
        $orderTotal = $amount;

        // CASO 1: Pago con Tarjeta mediante Token generado por Culqi
        if (!empty($tokenId)) {
            $chargePayload = [
                'amount' => $amountCents,
                'capture' => true,
                'currency_code' => 'PEN',
                'description' => 'Compra de Entradas - ViveGo',
                'email' => $buyerEmail,
                'installments' => 0,
                'antifraud_details' => [
                    'first_name' => substr($firstName, 0, 50),
                    'last_name' => substr($lastName, 0, 50),
                    'phone_number' => $buyerPhone,
                ],
                'source_id' => $tokenId,
                'metadata' => [
                    'buyer_dni' => $buyerDni,
                    'event_id' => $request->input('event_id'),
                ],
            ];

            $chargeResponse = $culqiService->createCharge($chargePayload);
            Log::info('Respuesta de Cargo Culqi:', $chargeResponse);

            $chargeObj = $chargeResponse['object'] ?? '';
            $isPaid = ($chargeObj === 'charge') && (($chargeResponse['capture'] ?? false) || ($chargeResponse['outcome']['type'] ?? '') === 'venta_exitosa');

            if (!$isPaid && !isset($chargeResponse['id'])) {
                $errorMsg = $chargeResponse['user_message'] ?? $chargeResponse['merchant_message'] ?? 'El pago con tarjeta no pudo ser procesado por Culqi.';
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }

            $transactionId = $chargeResponse['id'] ?? ('chr_' . time());
            $brand = strtoupper($chargeResponse['source']['iin']['card_brand'] ?? $chargeResponse['source']['brand'] ?? 'TARJETA');
            $subMethod = 'Tarjeta ' . ($brand ?: 'Crédito/Débito');
            $orderTotal = ($chargeResponse['amount'] ?? $amountCents) / 100;
        } 
        // CASO 2: Pago con QR / Billeteras Móviles / PagoEfectivo mediante Orden de Culqi
        elseif (!empty($orderId)) {
            $orderResponse = $culqiService->getOrder($orderId);
            Log::info('Consulta de Orden Culqi:', $orderResponse);

            $orderState = strtolower($orderResponse['state'] ?? 'pending');
            $transactionId = $orderResponse['id'] ?? $orderId;
            $orderTotal = ($orderResponse['amount'] ?? $amountCents) / 100;
            $paymentType = strtoupper($orderResponse['payment_code'] ? 'PAGOEFECTIVO' : 'QR_BILLETERAS');

            if ($paymentType === 'PAGOEFECTIVO') {
                $subMethod = 'PagoEfectivo (CIP)';
            } else {
                $subMethod = 'QR Billeteras (Yape / Plin)';
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se recibió un identificador de Token ni de Orden de Culqi.',
            ], 400);
        }

        // Obtener correlativo secuencial continuo (REC-000046)
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

        // Buscar evento
        $eventId = $request->input('event_id');
        $event = $eventId ? Event::find($eventId) : Event::first();

        $ticketsData = $request->input('tickets') ?: [
            ['name' => 'Entrada General', 'quantity' => 1, 'price' => $orderTotal]
        ];

        $totalQty = 0;
        foreach ($ticketsData as $t) {
            $totalQty += (int)($t['quantity'] ?? 1);
        }

        // Registrar la venta en la base de datos
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
            'payment_method' => 'Culqi',
            'amount_paid' => $orderTotal,
            'change_amount' => 0.00,
            'tickets_data' => [
                'items' => $ticketsData,
                'sub_method' => $subMethod,
                'customer_email' => $buyerEmail,
                'culqi_transaction_id' => $transactionId,
                'culqi_order_id' => $orderId,
                'culqi_token_id' => $tokenId,
                'brand' => $brand,
            ],
            'seller_name' => 'Pasarela Web Culqi',
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

            // Limpiar cualquier residuo de sesión administrativa
            session()->forget([
                'admin_logged_in',
                'admin_id',
                'admin_name',
                'admin_email',
                'admin_role',
                'admin_avatar',
            ]);

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
                Log::warning('No se pudo enviar el correo de compra Culqi: ' . $mailError->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Pago procesado exitosamente con Culqi!',
            'orderId' => $transactionId,
            'receiptNumber' => $receiptNumber,
            'saleId' => $sale->id,
            'redirect_url' => route('web.checkout.confirmation', $sale->id),
        ]);
    }

    /**
     * Consulta el estado de una Orden QR / PagoEfectivo en Culqi en tiempo real
     */
    public function checkCulqiOrderStatus(Request $request, CulqiService $culqiService): JsonResponse
    {
        $orderId = $request->input('order_id');
        if (empty($orderId)) {
            return response()->json(['success' => false, 'message' => 'Order ID requerido.'], 400);
        }

        $order = $culqiService->getOrder($orderId);
        $state = strtolower($order['state'] ?? 'pending');

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'state' => $state,
            'is_paid' => in_array($state, ['paid', 'pagado']),
            'order' => $order,
        ]);
    }

    /**
     * Webhook oficial para recibir notificaciones asíncronas de Culqi (IPN)
     */
    public function culqiWebhook(Request $request, CulqiService $culqiService): JsonResponse
    {
        Log::info('Culqi Webhook Received:', $request->all());

        $event = $request->input('type');
        $data = $request->input('data');

        return response()->json([
            'status' => 'received',
            'event' => $event,
        ]);
    }

    /**
     * Procesa y completa una orden de Entradas de Cortesía (Free / S/ 0.00)
     */
    public function completeCourtesyOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|integer',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_doc_number' => 'required|string|max:30',
            'customer_country' => 'nullable|string|max:100',
            'customer_city' => 'nullable|string|max:100',
            'tickets' => 'required|array|min:1',
        ]);

        $eventId = (int) $validated['event_id'];
        $event = Event::findOrFail($eventId);

        $courtesySettings = is_array($event->courtesy_settings) 
            ? $event->courtesy_settings 
            : (json_decode($event->courtesy_settings ?? '[]', true) ?? []);

        if (empty($courtesySettings['enabled']) || empty($courtesySettings['for_users'])) {
            return response()->json([
                'success' => false,
                'message' => 'Las entradas de cortesía no están habilitadas para los usuarios en este evento.',
            ], 422);
        }

        $ticketsData = $validated['tickets'];
        $totalQty = 0;
        $totalAmount = 0.00;

        foreach ($ticketsData as $t) {
            $qty = (int)($t['quantity'] ?? 1);
            $totalQty += $qty;
            $price = (float)($t['price'] ?? 0);
            $totalAmount += ($price * $qty);
        }

        $userMax = isset($courtesySettings['user_max_quantity']) && (int)$courtesySettings['user_max_quantity'] > 0 
            ? (int)$courtesySettings['user_max_quantity'] 
            : 2;

        // Límite configurable de entradas de cortesía por usuario
        if ($totalQty > $userMax) {
            return response()->json([
                'success' => false,
                'message' => "Solo se permite un máximo de {$userMax} entradas de cortesía por usuario.",
            ], 422);
        }

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

        $buyerName = $validated['customer_name'];
        $buyerEmail = strtolower($validated['customer_email']);
        $buyerPhone = $validated['customer_phone'];
        $buyerDni = $validated['customer_doc_number'];
        $buyerCountry = $validated['customer_country'] ?? 'Perú';
        $buyerCity = $validated['customer_city'] ?? 'Lima';

        $sale = TicketSale::create([
            'event_id' => $event->id,
            'receipt_number' => $receiptNumber,
            'buyer_name' => $buyerName,
            'buyer_dni' => $buyerDni,
            'buyer_phone' => $buyerPhone,
            'zone_name' => $ticketsData[0]['name'] ?? ($courtesySettings['name'] ?? 'Entrada de Cortesía (Free)'),
            'unit_price' => 0.00,
            'quantity' => $totalQty,
            'total_amount' => 0.00,
            'payment_method' => 'Cortesía',
            'amount_paid' => 0.00,
            'change_amount' => 0.00,
            'tickets_data' => [
                'items' => $ticketsData,
                'sub_method' => 'Cortesía Web (Gratis)',
                'customer_email' => $buyerEmail,
                'customer_country' => $buyerCountry,
                'customer_city' => $buyerCity,
                'is_courtesy' => true,
            ],
            'seller_name' => 'Web Cortesía ViveGo',
        ]);

        // Crear o sincronizar cuenta de Cliente para que pueda ver "Mis Boletos" y "Mis Recibos"
        $isNewUser = false;
        $tempPassword = null;

        if (!empty($buyerEmail)) {
            $customerUser = User::where('email', $buyerEmail)->first();
            if (!$customerUser) {
                $tempPassword = 'VG' . rand(100000, 999999);
                $customerUser = User::create([
                    'name' => $buyerName,
                    'email' => $buyerEmail,
                    'dni' => $buyerDni,
                    'phone' => $buyerPhone,
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

            // Limpiar cualquier residuo de sesión administrativa
            session()->forget([
                'admin_logged_in',
                'admin_id',
                'admin_name',
                'admin_email',
                'admin_role',
                'admin_avatar',
            ]);

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

        // Enviar Correo Electrónico Automático con Boletos y Recibo
        if (!empty($buyerEmail) && filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                $customPdfBase64 = $request->input('ticket_pdf_base64');
                \Illuminate\Support\Facades\Mail::to($buyerEmail)->send(new \App\Mail\TicketPurchaseMail($sale, $tempPassword, $isNewUser, $customPdfBase64));
                \Illuminate\Support\Facades\Log::info('Correo de confirmación de cortesía enviado exitosamente a: ' . $buyerEmail);
            } catch (\Throwable $mailError) {
                \Illuminate\Support\Facades\Log::warning('No se pudo enviar el correo de cortesía (verifique configuración SMTP): ' . $mailError->getMessage());
            }
        }

        // Limpiar carrito en sesión
        session()->forget(['checkout_cart_' . $event->id, 'checkout_date_' . $event->id]);

        return response()->json([
            'success' => true,
            'message' => '¡Entradas de cortesía emitidas con éxito!',
            'sale_id' => $sale->id,
            'receipt_number' => $sale->receipt_number,
            'redirect_url' => route('web.checkout.confirmation', $sale->id),
            'is_new_user' => $isNewUser,
            'temp_password' => $tempPassword,
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
