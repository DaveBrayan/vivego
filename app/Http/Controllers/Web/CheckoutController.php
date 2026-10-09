<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\PaymentGateway;
use App\Models\TicketSale;
use App\Models\User;
use App\Services\CulqiService;
use App\Services\IzipayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Registra un evento en el canal dedicado de logs de checkout (storage/logs/checkout.log)
     */
    protected function logCheckout(string $level, string $message, array $context = []): void
    {
        try {
            Log::channel('checkout')->$level($message, $context);
        } catch (\Throwable $e) {
            try {
                Log::$level("[Checkout] " . $message, $context);
            } catch (\Throwable $ex) {
                // Fallback silencioso
            }
        }
    }

    /**
     * Valida un código de cupón en tiempo real vía AJAX
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $code = strtoupper(trim((string) $request->input('code', '')));
        $eventId = (int) $request->input('event_id', 0);
        $subtotal = (float) $request->input('subtotal', 0.0);

        if (empty($code)) {
            return response()->json([
                'valid' => false,
                'message' => 'Por favor ingresa un código de cupón.'
            ], 422);
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'El cupón "' . $code . '" no existe o fue ingresado incorrectamente.'
            ], 404);
        }

        $check = $coupon->isValidForEvent($eventId, $subtotal);
        if (!$check['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $check['message']
            ], 422);
        }

        $discountAmount = $coupon->calculateDiscount($subtotal);
        $newTotal = max(0, round($subtotal - $discountAmount, 2));

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'description' => $coupon->description,
            'discount_type' => $coupon->discount_type,
            'discount_value' => (float) $coupon->discount_value,
            'discount_amount' => $discountAmount,
            'discount_formatted' => 'S/ ' . number_format($discountAmount, 2),
            'new_total' => $newTotal,
            'new_total_formatted' => 'S/ ' . number_format($newTotal, 2),
            'message' => '¡Cupón ' . $coupon->code . ' aplicado con éxito!'
        ]);
    }

    /**
     * Muestra la página completa de Carrito & Checkout
     */
    public function show(Request $request): View
    {
        $izipay = PaymentGateway::getIzipay();
        $culqi = PaymentGateway::getCulqi();

        // 0. Detectar si es una solicitud de Mejora de Entrada (Ticket Upgrade)
        $upgradeSaleId = $request->input('upgrade_sale_id');
        $upgradeZoneName = $request->input('upgrade_zone');
        $isUpgrade = false;
        $upgradeData = null;
        $event = null;
        $cartItems = [];
        $grandTotal = 0;
        $originalSubtotal = 0;
        $campaignDiscountTotal = 0;

        if ($upgradeSaleId) {
            $upgradeSale = TicketSale::with('event')->find($upgradeSaleId);
            if ($upgradeSale && !$upgradeSale->isUpgraded()) {
                $event = $upgradeSale->event;
                $zones = is_array($event?->zones) ? $event->zones : (is_string($event?->zones) ? json_decode($event->zones, true) : []);
                
                $targetZone = null;
                foreach ($zones as $z) {
                    $zName = $z['name'] ?? $z['capacity_type'] ?? '';
                    if (strtolower(trim($zName)) === strtolower(trim((string) $upgradeZoneName))) {
                        $targetZone = $z;
                        break;
                    }
                }
                if (!$targetZone && !empty($zones)) {
                    $targetZone = $zones[0];
                }

                if ($targetZone) {
                    $isUpgrade = true;
                    $targetZoneName = $targetZone['name'] ?? $targetZone['capacity_type'] ?? 'Zona Superior';
                    $targetPrice = (float) ($targetZone['price'] ?? 0);
                    $qty = max(1, (int) $upgradeSale->quantity);
                    $currentUnitPrice = (float) $upgradeSale->unit_price;
                    if ($currentUnitPrice <= 0 && (float) $upgradeSale->total_amount > 0) {
                        $currentUnitPrice = round((float) $upgradeSale->total_amount / $qty, 2);
                    }

                    $unitDifference = max(0, round($targetPrice - $currentUnitPrice, 2));
                    $totalDifference = round($unitDifference * $qty, 2);

                    $cartItems = [
                        [
                            'name' => $targetZoneName,
                            'zone_name' => $targetZoneName,
                            'original_zone' => $upgradeSale->zone_name,
                            'price' => $unitDifference,
                            'base_price' => $unitDifference,
                            'regular_price' => $targetPrice,
                            'is_upgrade' => true,
                            'upgrade_sale_id' => $upgradeSale->id,
                            'quantity' => $qty,
                            'subtotal' => $totalDifference,
                        ]
                    ];

                    $grandTotal = $totalDifference;
                    $originalSubtotal = $totalDifference;

                    $upgradeData = [
                        'sale_id' => $upgradeSale->id,
                        'receipt_number' => $upgradeSale->receipt_number,
                        'original_zone' => $upgradeSale->zone_name,
                        'original_unit_price' => $currentUnitPrice,
                        'original_total' => (float) $upgradeSale->total_amount,
                        'target_zone' => $targetZoneName,
                        'target_unit_price' => $targetPrice,
                        'unit_difference' => $unitDifference,
                        'quantity' => $qty,
                        'total_difference' => $totalDifference,
                    ];
                }
            }
        }

        // 1. Obtener Evento si no se definió por el upgrade
        if (!$event) {
            $eventId = $request->input('event_id') ?: session('checkout_event_id');
            $eventSlug = $request->input('event_slug') ?: session('checkout_event_slug');

            if ($eventId) {
                $event = Event::with('template')->find($eventId);
            } elseif ($eventSlug) {
                $event = Event::with('template')->where('slug', $eventSlug)->first();
            }

            if (!$event) {
                $event = Event::with('template')->latest()->first();
            }
        }

        if ($event) {
            session([
                'checkout_event_id' => $event->id,
                'checkout_event_slug' => $event->slug,
            ]);

            if ($event->isPast()) {
                return redirect()->route('web.event_detail', $event->slug ?: $event->id)
                    ->with('error', 'Este evento ya finalizó. No es posible realizar compras de entradas.');
            }
        }

        if ($event && ($event->status === 'Borrador' || $event->status === 'draft') && !auth()->check()) {
            return redirect()->route('web.home')->with('error', 'El evento seleccionado se encuentra en modo borrador y no está disponible para la venta.');
        }

        // Comprobar si existe Campaña Promocional Activa (ej: Black Friday) para este evento
        $activeCampaign = ($event && !$isUpgrade) ? Campaign::getActiveForEvent($event->id) : null;

        // 2. Extraer o armar Carrito de Entradas si no es Upgrade
        if (!$isUpgrade) {
            $ticketsRaw = $request->input('tickets');
            $cartItems = [];
            $grandTotal = 0;
            $originalSubtotal = 0;
            $campaignDiscountTotal = 0;

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
                        $hasPresale = !empty($z['has_presale']) && ($z['has_presale'] === true || $z['has_presale'] === 1 || $z['has_presale'] === '1' || $z['has_presale'] === 'true');
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

                        $finalPrice = $effectivePrice;
                        $itemCampaignDisc = 0.0;
                        if ($activeCampaign) {
                            $itemCampaignDisc = $activeCampaign->calculateDiscount($effectivePrice);
                            $finalPrice = max(0, $effectivePrice - $itemCampaignDisc);
                        }

                        $cartItems[] = [
                            'name' => $z['name'] ?? $z['capacity_type'] ?? 'Entrada',
                            'price' => $finalPrice,
                            'base_price' => $effectivePrice,
                            'regular_price' => $regularPrice,
                            'is_presale' => $isPresale,
                            'presale_discount' => $discountPercent,
                            'has_campaign' => ($itemCampaignDisc > 0),
                            'campaign_discount' => $itemCampaignDisc,
                            'quantity' => 1,
                            'subtotal' => $finalPrice
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
                        'base_price' => 100.00,
                        'regular_price' => 100.00,
                        'is_presale' => false,
                        'presale_discount' => 0,
                        'has_campaign' => false,
                        'campaign_discount' => 0,
                        'quantity' => 1,
                        'subtotal' => 100.00
                    ]
                ];
            }
        }

        if (!$isUpgrade) {
            $originalSubtotal = 0;
            $grandTotal = 0;
            $campaignDiscountTotal = 0;

            foreach ($cartItems as &$item) {
                $qty = (int)($item['quantity'] ?? 1);
                $basePrice = (float)($item['base_price'] ?? $item['price'] ?? 0);
                $itemPrice = (float)($item['price'] ?? 0);

                // Si hay campaña activa y no se calculó aún en el item
                if ($activeCampaign && empty($item['has_campaign'])) {
                    $itemCampDisc = $activeCampaign->calculateDiscount($basePrice);
                    if ($itemCampDisc > 0) {
                        $item['has_campaign'] = true;
                        $item['campaign_discount'] = $itemCampDisc;
                        $itemPrice = max(0, $basePrice - $itemCampDisc);
                        $item['price'] = $itemPrice;
                        $item['subtotal'] = round($itemPrice * $qty, 2);
                    }
                }

                $originalSubtotal += ($basePrice * $qty);
                $grandTotal += (float) ($item['subtotal'] ?? ($itemPrice * $qty));
                if (!empty($item['has_campaign'])) {
                    $campaignDiscountTotal += ((float)($item['campaign_discount'] ?? 0) * $qty);
                }
            }
            unset($item);
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
            'active_campaign' => $activeCampaign ? [
                'id' => $activeCampaign->id,
                'name' => $activeCampaign->name,
                'badge_text' => $activeCampaign->badge_text ?: ('🔥 ' . strtoupper($activeCampaign->name)),
                'banner_color' => $activeCampaign->banner_color ?: '#FF5500',
                'discount_type' => $activeCampaign->discount_type,
                'discount_value' => $activeCampaign->discount_value,
                'end_at' => $activeCampaign->end_at ? ($activeCampaign->end_at instanceof \DateTimeInterface ? $activeCampaign->end_at->format('Y-m-d H:i:s') : (string)$activeCampaign->end_at) : null,
                'end_at_display' => $activeCampaign->end_at ? ($activeCampaign->end_at instanceof \DateTimeInterface ? $activeCampaign->end_at->format('d/m/Y h:i A') : (string)$activeCampaign->end_at) : '',
            ] : null,
        ];

        // Registrar en log de auditoría el ingreso al checkout
        $firstItem = $cartItems[0] ?? [];
        $zoneLabel = $firstItem['name'] ?? ($firstItem['zone_name'] ?? 'Entrada General');
        $totalQuantity = array_sum(array_column($cartItems, 'quantity'));
        $this->logCheckout('info', "👤 [INGRESO_CHECKOUT] Cliente ingresó al checkout para '{$eventData['title']}' (Zona: {$zoneLabel}, Cantidad: " . max(1, $totalQuantity) . " entrada(s), Total: S/ " . number_format($grandTotal, 2) . ")", [
            'ip' => $request->ip(),
            'event_id' => $eventData['id'],
            'event_title' => $eventData['title'],
            'zone' => $zoneLabel,
            'quantity' => max(1, $totalQuantity),
            'total_amount' => $grandTotal,
            'is_upgrade' => $isUpgrade,
            'is_authenticated' => session('customer_logged_in') ? true : false,
            'customer_email' => session('customer_email') ?: null,
            'customer_dni' => session('customer_dni') ?: null,
        ]);

        return view('web.checkout', compact(
            'eventData', 
            'cartItems', 
            'grandTotal', 
            'originalSubtotal', 
            'campaignDiscountTotal', 
            'activeCampaign', 
            'izipay', 
            'culqi',
            'isUpgrade',
            'upgradeData'
        ));
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

        $eventId = $validated['event_id'] ?? null;
        $event = $eventId ? Event::find($eventId) : null;
        if ($event && $event->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Este evento ya finalizó. No es posible generar compras de entradas.',
            ], 422);
        }

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
            $this->logCheckout('info', "Izipay [Sesión Token Creada]: {$orderId} para {$validated['customer_email']} - Total: S/ " . number_format($validated['amount'], 2), [
                'order_id' => $orderId,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'amount' => $validated['amount'],
                'tickets_count' => count($validated['tickets']),
            ]);

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
        $this->logCheckout('error', "Izipay [Error Crear Sesión]: {$errorMessage}", ['response' => $response]);

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
        $this->logCheckout('info', 'Izipay [Callback Recibido]: Petición de pago recibida', $request->except(['ticket_pdf_base64']));

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
            $this->logCheckout('warning', 'Izipay [Error Callback]: Respuesta de pago incompleta. No se recibieron datos.');
            return response()->json([
                'success' => false,
                'message' => 'Respuesta de pago incompleta. No se recibieron datos de la pasarela.',
            ], 400);
        }

        $answer = json_decode($krAnswerRaw, true);
        if (!is_array($answer)) {
            $this->logCheckout('warning', 'Izipay [Error Callback]: Formato JSON de respuesta no válido.');
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
                $this->logCheckout('warning', "Izipay [Firma HMAC Inválida]: Hash {$krHash} no coincide con la respuesta.");
                return response()->json([
                    'success' => false,
                    'message' => 'Firma digital de Izipay no verificada.',
                ], 403);
            }
        }

        if ($orderStatus !== 'PAID') {
            $this->logCheckout('warning', "Izipay [Pago No Aprobado]: Estado bancario '{$orderStatus}'", ['answer' => $answer]);
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

        $buyerEmail = strtolower(trim((string)($customer['email'] ?? $request->input('customer_email') ?? '')));
        $buyerName = trim(($billing['firstName'] ?? '') . ' ' . ($billing['lastName'] ?? ''));
        if (empty($buyerName)) {
            $buyerName = trim($request->input('customer_name') ?: 'Cliente ViveGo');
        }
        $buyerPhone = !empty($billing['phoneNumber']) ? $billing['phoneNumber'] : ($request->input('customer_phone') ?: '999999999');
        $buyerDni = !empty($billing['identityCode']) ? $billing['identityCode'] : ($request->input('customer_doc') ?: '00000000');

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
            $subMethod = 'QR Billeteras (Yape / Plin)';
        } elseif (in_array($methodType, ['PAGOS_DIGITALES', 'PAGOEFECTIVO', 'CIP'])) {
            $subMethod = 'PagoEfectivo (CIP)';
        } elseif (!empty($brand)) {
            $subMethod = 'Tarjeta ' . $brand;
        }

        // Buscar evento si existe
        $eventId = $request->input('event_id');
        $event = $eventId ? Event::find($eventId) : Event::first();

        if ($event && $event->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Este evento ya finalizó. No es posible registrar nuevas compras.',
            ], 422);
        }

        $ticketsData = $request->input('tickets') ?: [
            ['name' => 'Entrada General', 'quantity' => 1, 'price' => $orderTotal]
        ];

        $totalQty = 0;
        foreach ($ticketsData as $t) {
            $totalQty += (int)($t['quantity'] ?? 1);
        }

        // Descuentos de Campaña y Cupones
        $couponCodeInput = strtoupper(trim((string)$request->input('coupon_code', '')));
        $couponDiscount = (float) $request->input('coupon_discount', 0);
        $campaignNameInput = $request->input('campaign_name');
        $campaignDiscountInput = (float) $request->input('campaign_discount', 0);
        $originalSubtotalInput = (float) $request->input('original_subtotal', 0);

        if ($couponCodeInput) {
            $appliedCoupon = Coupon::where('code', $couponCodeInput)->first();
            if ($appliedCoupon) {
                $appliedCoupon->incrementUsage();
            }
        }

        $totalDiscountAmount = $couponDiscount + $campaignDiscountInput;
        $discountDescParts = [];
        if ($campaignNameInput && $campaignDiscountInput > 0) {
            $discountDescParts[] = "Campaña {$campaignNameInput}: -S/ " . number_format($campaignDiscountInput, 2);
        }
        if ($couponCodeInput && $couponDiscount > 0) {
            $discountDescParts[] = "Cupón {$couponCodeInput}: -S/ " . number_format($couponDiscount, 2);
        }
        $discountDescription = implode(' | ', $discountDescParts);

        // Detectar si es Mejora de Entrada (Upgrade)
        $upgradeSaleId = $request->input('upgrade_sale_id') ?: ($ticketsData[0]['upgrade_sale_id'] ?? null);
        $effectiveZoneName = $ticketsData[0]['zone_name'] ?? $ticketsData[0]['name'] ?? 'General';
        if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $effectiveZoneName, $matches)) {
            $effectiveZoneName = trim($matches[1]);
        }
        foreach ($ticketsData as &$t) {
            $rawZ = $t['zone_name'] ?? $t['name'] ?? $effectiveZoneName;
            if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $rawZ, $m)) {
                $rawZ = trim($m[1]);
            }
            $t['name'] = $rawZ;
            $t['zone_name'] = $rawZ;
            $t['is_presale'] = function_exists('isSalePresale') ? isSalePresale($t) : (!empty($t['is_presale_active']) || !empty($t['is_presale']));
        }
        unset($t);

        $sale = TicketSale::create([
            'event_id' => $event?->id ?? 1,
            'receipt_number' => $receiptNumber,
            'buyer_name' => $buyerName,
            'buyer_dni' => $buyerDni ?: '00000000',
            'buyer_phone' => $buyerPhone ?: '999999999',
            'zone_name' => $effectiveZoneName,
            'unit_price' => $orderTotal / max(1, $totalQty),
            'quantity' => max(1, $totalQty),
            'original_subtotal' => $originalSubtotalInput > 0 ? $originalSubtotalInput : ($orderTotal + $totalDiscountAmount),
            'discount_amount' => $totalDiscountAmount,
            'discount_description' => $discountDescription ?: null,
            'campaign_name' => $campaignNameInput ?: null,
            'coupon_code' => $couponCodeInput ?: null,
            'total_amount' => $orderTotal,
            'payment_method' => 'Izipay',
            'amount_paid' => $orderTotal,
            'change_amount' => 0.00,
            'status' => 'completed',
            'is_upgrade' => !empty($upgradeSaleId),
            'upgraded_from_sale_id' => $upgradeSaleId,
            'upgrade_difference' => !empty($upgradeSaleId) ? $orderTotal : 0.00,
            'tickets_data' => [
                'items' => $ticketsData,
                'is_presale' => function_exists('isSalePresale') ? isSalePresale(['items' => $ticketsData]) : false,
                'sub_method' => $subMethod,
                'customer_email' => $buyerEmail,
                'izipay_order_id' => $orderId,
                'izipay_uuid' => $firstTx['uuid'] ?? null,
                'brand' => $brand,
                'coupon_code' => $couponCodeInput ?: null,
                'coupon_discount' => $couponDiscount,
                'campaign_name' => $campaignNameInput ?: null,
                'campaign_discount' => $campaignDiscountInput,
                'is_upgrade' => !empty($upgradeSaleId),
                'upgrade_sale_id' => $upgradeSaleId,
            ],
            'seller_name' => 'Pasarela Web Izipay',
        ]);

        // Invalidar boleto previo si fue upgrade y registrar boletos oficiales en event_tickets
        $this->processSaleCompletionAndTickets($sale, $event, $totalQty, $upgradeSaleId, $ticketsData);

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

        // 2. Enviar Correo Electrónico Automático con Recibo, Boletos y Credenciales y registrar en EmailLog
        if (!empty($buyerEmail) && filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
            \App\Services\EmailLogService::sendTicketPurchaseMail($sale, $tempPassword, $isNewUser, $request->input('ticket_pdf_base64'));
        }

        $this->logCheckout('info', "Izipay [Venta Completada]: Venta #{$sale->id} registrada exitosamente", [
            'receipt' => $receiptNumber,
            'buyer' => $buyerName,
            'email' => $buyerEmail,
            'total' => $orderTotal,
            'tickets_count' => $totalQty,
            'order_id' => $orderId,
        ]);

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

        $eventId = $validated['event_id'] ?? null;
        $event = $eventId ? Event::find($eventId) : null;
        if ($event && $event->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Este evento ya finalizó. No es posible generar transacciones de pago.',
            ], 422);
        }

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

        // Guardar contexto completo de la orden en Cache para permitir emisión desatendida vía Webhook
        $orderContext = [
            'event_id' => $validated['event_id'] ?? null,
            'event_title' => $validated['event_title'] ?? '',
            'date_selected' => $validated['date_selected'] ?? '',
            'tickets' => $request->input('tickets') ?: [],
            'customer_name' => $validated['customer_name'] ?? '',
            'customer_email' => $validated['customer_email'] ?? '',
            'customer_phone' => $validated['customer_phone'] ?? '',
            'customer_doc' => $validated['customer_doc'] ?? '',
            'customer_country' => $request->input('customer_country', 'PE'),
            'customer_city' => $request->input('customer_city', 'Lima'),
            'coupon_code' => $request->input('coupon_code'),
            'coupon_discount' => (float) $request->input('coupon_discount', 0),
            'campaign_name' => $request->input('campaign_name'),
            'campaign_discount' => (float) $request->input('campaign_discount', 0),
            'original_subtotal' => (float) $request->input('original_subtotal', 0),
            'upgrade_sale_id' => $request->input('upgrade_sale_id'),
            'is_upgrade' => (bool) $request->input('is_upgrade', false),
            'amount' => (float) $validated['amount'],
            'order_number' => $orderNumber,
        ];

        if (isset($response['id']) && str_starts_with($response['id'], 'ord_')) {
            Cache::put('culqi_order_ctx_' . $response['id'], $orderContext, now()->addHours(48));
            Cache::put('culqi_order_ctx_' . $orderNumber, $orderContext, now()->addHours(48));

            $this->logCheckout('info', "Culqi [Orden QR Generada]: {$response['id']} (#{$orderNumber}) para {$validated['customer_email']} - Total: S/ " . number_format($validated['amount'], 2), [
                'order_id' => $response['id'],
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_doc' => $validated['customer_doc'] ?? '',
                'tickets_count' => count($validated['tickets']),
                'has_qr' => !empty($response['qr']),
                'has_cip' => !empty($response['payment_code']),
            ]);

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
        $this->logCheckout('warning', "Culqi [Orden Warning]: No se generó orden oficial en Culqi API - {$errorMessage}", ['response' => $response]);

        return response()->json([
            'success' => true,
            'orderId' => null,
            'orderNumber' => $orderNumber,
            'publicKey' => $culqiService->getPublicKey(),
            'amountCents' => $amountCents,
            'amountFormatted' => 'S/ ' . number_format($validated['amount'], 2),
            'warning' => $errorMessage,
        ]);
    }

    /**
     * Procesa y materializa una Orden de Culqi pagada (creando TicketSale, EventTickets, Usuario y Correo)
     * Método idempotente: si la orden ya fue procesada, retorna la venta existente sin duplicar boletos.
     */
    public function processCulqiPaidOrder(string $orderId, CulqiService $culqiService, array $clientOverrideData = []): ?TicketSale
    {
        // 1. Verificación de Idempotencia: ¿Ya existe una venta para esta orden de Culqi?
        $existingSale = TicketSale::where('tickets_data', 'LIKE', '%' . $orderId . '%')->first();
        if ($existingSale) {
            $this->logCheckout('info', "Culqi [Idempotencia]: Orden {$orderId} ya procesada previamente con Venta #{$existingSale->id} (Recibo: {$existingSale->receipt_number})");
            return $existingSale;
        }

        // 2. Consultar el estado real de la Orden directamente en Culqi API
        $orderResponse = $culqiService->getOrder($orderId);
        $orderState = strtolower($orderResponse['state'] ?? 'pending');

        $this->logCheckout('info', "Culqi [Verificación API]: Orden {$orderId} consultada en Culqi -> Estado: {$orderState}", [
            'order_id' => $orderId,
            'state' => $orderState,
            'amount' => $orderResponse['amount'] ?? null,
        ]);

        if (!in_array($orderState, ['paid', 'pagado'])) {
            $this->logCheckout('warning', "Culqi [Orden no pagada]: Intento de procesar Orden {$orderId} con estado no pagado: '{$orderState}'");
            return null;
        }

        // 3. Recuperar contexto guardado en Cache o Metadata
        $cachedContext = Cache::get('culqi_order_ctx_' . $orderId, []);
        $orderNumber = $orderResponse['order_number'] ?? ($cachedContext['order_number'] ?? ('VG-' . strtoupper(Str::random(4)) . '-' . time()));
        
        if (!empty($orderNumber)) {
            $existingByOrderNum = TicketSale::where('tickets_data', 'LIKE', '%' . $orderNumber . '%')->first();
            if ($existingByOrderNum) {
                $this->logCheckout('info', "Culqi [Idempotencia]: Orden con número {$orderNumber} ya procesada previamente con Venta #{$existingByOrderNum->id}");
                return $existingByOrderNum;
            }
        }

        // 4. Extraer datos del comprador con fallbacks en cascada
        $clientDetails = $orderResponse['client_details'] ?? [];
        $firstName = $clientOverrideData['customer_name'] 
            ?? ($cachedContext['customer_name'] 
            ?? trim(($clientDetails['first_name'] ?? '') . ' ' . ($clientDetails['last_name'] ?? '')));
        $buyerName = !empty($firstName) ? $firstName : 'Cliente ViveGo';

        $buyerEmail = $clientOverrideData['customer_email'] 
            ?? ($cachedContext['customer_email'] 
            ?? ($clientDetails['email'] ?? ''));
        $buyerEmail = strtolower(trim($buyerEmail));

        $buyerPhone = $clientOverrideData['customer_phone'] 
            ?? ($cachedContext['customer_phone'] 
            ?? ($clientDetails['phone_number'] ?? '999999999'));

        $metadata = $orderResponse['metadata'] ?? [];
        $buyerDni = $clientOverrideData['customer_doc'] 
            ?? ($cachedContext['customer_doc'] 
            ?? ($metadata['buyer_doc'] ?? '00000000'));

        $orderTotal = isset($orderResponse['amount']) ? ((float)$orderResponse['amount'] / 100) : (float)($clientOverrideData['amount'] ?? ($cachedContext['amount'] ?? 0));

        $paymentType = strtoupper(!empty($orderResponse['payment_code']) ? 'PAGOEFECTIVO' : 'QR_BILLETERAS');
        $subMethod = $paymentType === 'PAGOEFECTIVO' ? 'PagoEfectivo (CIP)' : 'QR Billeteras (Yape / Plin)';

        // 5. Correlativo continuo REC-XXXXXX
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

        // 6. Evento
        $eventId = $clientOverrideData['event_id'] 
            ?? ($cachedContext['event_id'] 
            ?? ($metadata['event_id'] ?? null));
        $event = $eventId ? Event::find($eventId) : Event::first();

        // 7. Datos de boletos / items
        $ticketsData = $clientOverrideData['tickets'] 
            ?? ($cachedContext['tickets'] 
            ?? [
                ['name' => 'Entrada General', 'quantity' => 1, 'price' => $orderTotal]
            ]);

        $totalQty = 0;
        foreach ($ticketsData as $t) {
            $totalQty += (int)($t['quantity'] ?? 1);
        }

        // 8. Descuentos y cupones
        $couponCodeInput = strtoupper(trim((string)($clientOverrideData['coupon_code'] ?? ($cachedContext['coupon_code'] ?? ''))));
        $couponDiscount = (float)($clientOverrideData['coupon_discount'] ?? ($cachedContext['coupon_discount'] ?? 0));
        $campaignNameInput = $clientOverrideData['campaign_name'] ?? ($cachedContext['campaign_name'] ?? null);
        $campaignDiscountInput = (float)($clientOverrideData['campaign_discount'] ?? ($cachedContext['campaign_discount'] ?? 0));
        $originalSubtotalInput = (float)($clientOverrideData['original_subtotal'] ?? ($cachedContext['original_subtotal'] ?? 0));

        if ($couponCodeInput) {
            $appliedCoupon = Coupon::where('code', $couponCodeInput)->first();
            if ($appliedCoupon) {
                $appliedCoupon->incrementUsage();
            }
        }

        $totalDiscountAmount = $couponDiscount + $campaignDiscountInput;
        $discountDescParts = [];
        if ($campaignNameInput && $campaignDiscountInput > 0) {
            $discountDescParts[] = "Campaña {$campaignNameInput}: -S/ " . number_format($campaignDiscountInput, 2);
        }
        if ($couponCodeInput && $couponDiscount > 0) {
            $discountDescParts[] = "Cupón {$couponCodeInput}: -S/ " . number_format($couponDiscount, 2);
        }
        $discountDescription = implode(' | ', $discountDescParts);

        // 9. Upgrades
        $upgradeSaleId = $clientOverrideData['upgrade_sale_id'] ?? ($cachedContext['upgrade_sale_id'] ?? null);
        if ($upgradeSaleId) {
            $origSale = TicketSale::find($upgradeSaleId);
            if ($origSale) {
                if (($buyerDni === '00000000' || empty($buyerDni)) && !empty($origSale->buyer_dni)) {
                    $buyerDni = $origSale->buyer_dni;
                }
                if (empty($buyerEmail)) {
                    $origTData = is_array($origSale->tickets_data) ? $origSale->tickets_data : json_decode($origSale->tickets_data, true);
                    $buyerEmail = strtolower(trim($origTData['customer_email'] ?? ''));
                }
                if ($buyerName === 'Cliente ViveGo' && !empty($origSale->buyer_name)) {
                    $buyerName = $origSale->buyer_name;
                }
            }
        }

        $effectiveZoneName = $ticketsData[0]['zone_name'] ?? $ticketsData[0]['name'] ?? 'General';
        if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $effectiveZoneName, $matches)) {
            $effectiveZoneName = trim($matches[1]);
        }
        foreach ($ticketsData as &$t) {
            $rawZ = $t['zone_name'] ?? $t['name'] ?? $effectiveZoneName;
            if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $rawZ, $m)) {
                $rawZ = trim($m[1]);
            }
            $t['name'] = $rawZ;
            $t['zone_name'] = $rawZ;
            $t['is_presale'] = function_exists('isSalePresale') ? isSalePresale($t) : (!empty($t['is_presale_active']) || !empty($t['is_presale']));
        }
        unset($t);

        // 10. Crear TicketSale
        $sale = TicketSale::create([
            'event_id' => $event?->id ?? 1,
            'receipt_number' => $receiptNumber,
            'buyer_name' => $buyerName,
            'buyer_dni' => $buyerDni ?: '00000000',
            'buyer_phone' => $buyerPhone ?: '999999999',
            'zone_name' => $effectiveZoneName,
            'unit_price' => $orderTotal / max(1, $totalQty),
            'quantity' => max(1, $totalQty),
            'original_subtotal' => $originalSubtotalInput > 0 ? $originalSubtotalInput : ($orderTotal + $totalDiscountAmount),
            'discount_amount' => $totalDiscountAmount,
            'discount_description' => $discountDescription ?: null,
            'campaign_name' => $campaignNameInput ?: null,
            'coupon_code' => $couponCodeInput ?: null,
            'total_amount' => $orderTotal,
            'payment_method' => 'Culqi',
            'amount_paid' => $orderTotal,
            'change_amount' => 0.00,
            'status' => 'completed',
            'is_upgrade' => !empty($upgradeSaleId),
            'upgraded_from_sale_id' => $upgradeSaleId,
            'upgrade_difference' => !empty($upgradeSaleId) ? $orderTotal : 0.00,
            'tickets_data' => [
                'items' => $ticketsData,
                'is_presale' => function_exists('isSalePresale') ? isSalePresale(['items' => $ticketsData]) : false,
                'sub_method' => $subMethod,
                'customer_email' => $buyerEmail,
                'culqi_transaction_id' => $orderResponse['id'] ?? $orderId,
                'culqi_order_id' => $orderId,
                'culqi_order_number' => $orderNumber,
                'coupon_code' => $couponCodeInput ?: null,
                'coupon_discount' => $couponDiscount,
                'campaign_name' => $campaignNameInput ?: null,
                'campaign_discount' => $campaignDiscountInput,
                'is_upgrade' => !empty($upgradeSaleId),
                'upgrade_sale_id' => $upgradeSaleId,
            ],
            'seller_name' => 'Pasarela Web Culqi (QR/Auto)',
        ]);

        // 11. Generar EventTickets oficiales
        $this->processSaleCompletionAndTickets($sale, $event, $totalQty, $upgradeSaleId, $ticketsData);

        // 12. Sincronizar cuenta de usuario
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

            // Si hay sesión HTTP activa, loguearlo
            if (session()) {
                session()->forget([
                    'admin_logged_in',
                    'admin_id',
                    'admin_name',
                    'admin_email',
                    'admin_role',
                    'admin_avatar',
                ]);
                session([
                    'customer_logged_in' => true,
                    'customer_id' => $customerUser->id,
                    'customer_name' => $customerUser->name,
                    'customer_email' => $customerUser->email,
                    'customer_dni' => $customerUser->dni,
                    'customer_phone' => $customerUser->phone,
                ]);
            }
        }

        // 13. Enviar Correo Electrónico Automático
        if (!empty($buyerEmail) && filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
            \App\Services\EmailLogService::sendTicketPurchaseMail($sale, $tempPassword, $isNewUser, $clientOverrideData['ticket_pdf_base64'] ?? null);
        }

        $this->logCheckout('info', "Culqi [Venta QR Completada]: Venta #{$sale->id} registrada exitosamente para orden {$orderId}", [
            'sale_id' => $sale->id,
            'receipt' => $receiptNumber,
            'order_id' => $orderId,
            'buyer' => $buyerName,
            'email' => $buyerEmail,
            'total' => $orderTotal,
            'tickets_count' => $totalQty,
            'is_new_user' => $isNewUser,
        ]);

        return $sale;
    }

    /**
     * Procesa y valida el pago completado con Culqi (Cargos con Tarjeta vía Token o Pagos con QR / Orden)
     */
    public function completeCulqiPayment(Request $request, CulqiService $culqiService): JsonResponse
    {
        $this->logCheckout('info', 'Culqi [Callback Completar]: Petición recibida en frontend', $request->except(['ticket_pdf_base64']));

        $tokenId = $request->input('token_id') ?: $request->input('tokenId');
        $orderId = $request->input('order_id') ?: $request->input('orderId');

        // CASO 2: Pago con QR / Billeteras Móviles / PagoEfectivo mediante Orden de Culqi
        if (!empty($orderId)) {
            $sale = $this->processCulqiPaidOrder($orderId, $culqiService, $request->all());
            if (!$sale) {
                return response()->json([
                    'success' => false,
                    'message' => 'La orden en Culqi aún no registra confirmación de pago.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => '¡Pago procesado exitosamente con Culqi!',
                'orderId' => $orderId,
                'receiptNumber' => $sale->receipt_number,
                'saleId' => $sale->id,
                'redirect_url' => route('web.checkout.confirmation', $sale->id),
            ]);
        }

        // CASO 1: Pago con Tarjeta mediante Token generado por Culqi
        if (!empty($tokenId)) {
            // Verificar idempotencia si este token ya fue procesado
            $existingSaleToken = TicketSale::where('tickets_data', 'LIKE', '%' . $tokenId . '%')->first();
            if ($existingSaleToken) {
                $this->logCheckout('info', "Culqi [Idempotencia Tarjeta]: Token {$tokenId} ya procesado con Venta #{$existingSaleToken->id}");
                return response()->json([
                    'success' => true,
                    'message' => '¡Pago ya registrado previamente!',
                    'orderId' => $existingSaleToken->receipt_number,
                    'receiptNumber' => $existingSaleToken->receipt_number,
                    'saleId' => $existingSaleToken->id,
                    'redirect_url' => route('web.checkout.confirmation', $existingSaleToken->id),
                ]);
            }

            $amount = (float) $request->input('amount', 0);
            $amountCents = (int) round($amount * 100);

            $buyerName = trim($request->input('customer_name') ?: 'Cliente ViveGo');
            $buyerEmail = trim($request->input('customer_email') ?: '');
            $buyerDni = trim($request->input('customer_doc') ?: '00000000');
            $buyerPhone = trim($request->input('customer_phone') ?: '999999999');

            $nameParts = explode(' ', $buyerName, 2);
            $firstName = $nameParts[0] ?? 'Cliente';
            $lastName = $nameParts[1] ?? 'ViveGo';

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
            $this->logCheckout('info', "Culqi [Cargo Tarjeta Respuesta]: Token {$tokenId}", ['response' => $chargeResponse]);

            $chargeObj = $chargeResponse['object'] ?? '';
            $isPaid = ($chargeObj === 'charge') && (($chargeResponse['capture'] ?? false) || ($chargeResponse['outcome']['type'] ?? '') === 'venta_exitosa');

            if (!$isPaid && !isset($chargeResponse['id'])) {
                $errorMsg = $chargeResponse['user_message'] ?? $chargeResponse['merchant_message'] ?? 'El pago con tarjeta no pudo ser procesado por Culqi.';
                $this->logCheckout('warning', "Culqi [Cargo Tarjeta Rechazado]: {$errorMsg}", ['response' => $chargeResponse]);

                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }

            $transactionId = $chargeResponse['id'] ?? ('chr_' . time());
            $brand = strtoupper($chargeResponse['source']['iin']['card_brand'] ?? $chargeResponse['source']['brand'] ?? 'TARJETA');
            $subMethod = 'Tarjeta ' . ($brand ?: 'Crédito/Débito');
            $orderTotal = ($chargeResponse['amount'] ?? $amountCents) / 100;

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

            if ($event && $event->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este evento ya finalizó. No es posible registrar nuevas compras.',
                ], 422);
            }

            $ticketsData = $request->input('tickets') ?: [
                ['name' => 'Entrada General', 'quantity' => 1, 'price' => $orderTotal]
            ];

            $totalQty = 0;
            foreach ($ticketsData as $t) {
                $totalQty += (int)($t['quantity'] ?? 1);
            }

            // Descuentos de Campaña y Cupones
            $couponCodeInput = strtoupper(trim((string)$request->input('coupon_code', '')));
            $couponDiscount = (float) $request->input('coupon_discount', 0);
            $campaignNameInput = $request->input('campaign_name');
            $campaignDiscountInput = (float) $request->input('campaign_discount', 0);
            $originalSubtotalInput = (float) $request->input('original_subtotal', 0);

            if ($couponCodeInput) {
                $appliedCoupon = Coupon::where('code', $couponCodeInput)->first();
                if ($appliedCoupon) {
                    $appliedCoupon->incrementUsage();
                }
            }

            $totalDiscountAmount = $couponDiscount + $campaignDiscountInput;
            $discountDescParts = [];
            if ($campaignNameInput && $campaignDiscountInput > 0) {
                $discountDescParts[] = "Campaña {$campaignNameInput}: -S/ " . number_format($campaignDiscountInput, 2);
            }
            if ($couponCodeInput && $couponDiscount > 0) {
                $discountDescParts[] = "Cupón {$couponCodeInput}: -S/ " . number_format($couponDiscount, 2);
            }
            $discountDescription = implode(' | ', $discountDescParts);

            // Detectar si es Mejora de Entrada (Upgrade)
            $upgradeSaleId = $request->input('upgrade_sale_id') ?: ($ticketsData[0]['upgrade_sale_id'] ?? null);
            if ($upgradeSaleId) {
                $origSale = TicketSale::find($upgradeSaleId);
                if ($origSale) {
                    if (($buyerDni === '00000000' || empty($buyerDni)) && !empty($origSale->buyer_dni)) {
                        $buyerDni = $origSale->buyer_dni;
                    }
                    if (empty($buyerEmail)) {
                        $origTData = is_array($origSale->tickets_data) ? $origSale->tickets_data : json_decode($origSale->tickets_data, true);
                        $buyerEmail = strtolower(trim($origTData['customer_email'] ?? ''));
                    }
                    if ($buyerName === 'Cliente ViveGo' && !empty($origSale->buyer_name)) {
                        $buyerName = $origSale->buyer_name;
                    }
                }
            }
            $effectiveZoneName = $ticketsData[0]['zone_name'] ?? $ticketsData[0]['name'] ?? 'General';
            if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $effectiveZoneName, $matches)) {
                $effectiveZoneName = trim($matches[1]);
            }
            foreach ($ticketsData as &$t) {
                $rawZ = $t['zone_name'] ?? $t['name'] ?? $effectiveZoneName;
                if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $rawZ, $m)) {
                    $rawZ = trim($m[1]);
                }
                $t['name'] = $rawZ;
                $t['zone_name'] = $rawZ;
                $t['is_presale'] = function_exists('isSalePresale') ? isSalePresale($t) : (!empty($t['is_presale_active']) || !empty($t['is_presale']));
            }
            unset($t);

            // Registrar la venta en la base de datos
            $sale = TicketSale::create([
                'event_id' => $event?->id ?? 1,
                'receipt_number' => $receiptNumber,
                'buyer_name' => $buyerName,
                'buyer_dni' => $buyerDni ?: '00000000',
                'buyer_phone' => $buyerPhone ?: '999999999',
                'zone_name' => $effectiveZoneName,
                'unit_price' => $orderTotal / max(1, $totalQty),
                'quantity' => max(1, $totalQty),
                'original_subtotal' => $originalSubtotalInput > 0 ? $originalSubtotalInput : ($orderTotal + $totalDiscountAmount),
                'discount_amount' => $totalDiscountAmount,
                'discount_description' => $discountDescription ?: null,
                'campaign_name' => $campaignNameInput ?: null,
                'coupon_code' => $couponCodeInput ?: null,
                'total_amount' => $orderTotal,
                'payment_method' => 'Culqi',
                'amount_paid' => $orderTotal,
                'change_amount' => 0.00,
                'status' => 'completed',
                'is_upgrade' => !empty($upgradeSaleId),
                'upgraded_from_sale_id' => $upgradeSaleId,
                'upgrade_difference' => !empty($upgradeSaleId) ? $orderTotal : 0.00,
                'tickets_data' => [
                    'items' => $ticketsData,
                    'is_presale' => function_exists('isSalePresale') ? isSalePresale(['items' => $ticketsData]) : false,
                    'sub_method' => $subMethod,
                    'customer_email' => $buyerEmail,
                    'culqi_transaction_id' => $transactionId,
                    'culqi_token_id' => $tokenId,
                    'brand' => $brand,
                    'coupon_code' => $couponCodeInput ?: null,
                    'coupon_discount' => $couponDiscount,
                    'campaign_name' => $campaignNameInput ?: null,
                    'campaign_discount' => $campaignDiscountInput,
                    'is_upgrade' => !empty($upgradeSaleId),
                    'upgrade_sale_id' => $upgradeSaleId,
                ],
                'seller_name' => 'Pasarela Web Culqi (Tarjeta)',
            ]);

            // Invalidar boleto previo si fue upgrade y registrar boletos oficiales en event_tickets
            $this->processSaleCompletionAndTickets($sale, $event, $totalQty, $upgradeSaleId, $ticketsData);

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

            // 2. Enviar Correo Electrónico Automático con Recibo, Boletos y Credenciales y registrar en EmailLog
            if (!empty($buyerEmail) && filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
                \App\Services\EmailLogService::sendTicketPurchaseMail($sale, $tempPassword, $isNewUser, $request->input('ticket_pdf_base64'));
            }

            $this->logCheckout('info', "Culqi [Venta Tarjeta Completada]: Venta #{$sale->id} registrada exitosamente", [
                'receipt' => $receiptNumber,
                'buyer' => $buyerName,
                'email' => $buyerEmail,
                'total' => $orderTotal,
                'transaction_id' => $transactionId,
                'brand' => $brand,
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Pago procesado exitosamente con Culqi!',
                'orderId' => $transactionId,
                'receiptNumber' => $receiptNumber,
                'saleId' => $sale->id,
                'redirect_url' => route('web.checkout.confirmation', $sale->id),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se recibió un identificador de Token ni de Orden de Culqi.',
        ], 400);
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

        // 1. Verificación rápida: si ya fue procesada y registrada en BD (ej. por Webhook), retornar éxito directo
        $existingSale = TicketSale::where('tickets_data', 'LIKE', '%' . $orderId . '%')->first();
        if ($existingSale) {
            $this->logCheckout('debug', "Culqi [Polling]: Orden {$orderId} ya existe en BD con Sale #{$existingSale->id}");

            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'state' => 'paid',
                'is_paid' => true,
                'saleId' => $existingSale->id,
                'redirect_url' => route('web.checkout.confirmation', $existingSale->id),
            ]);
        }

        // 2. Consultar directamente a la API de Culqi
        $order = $culqiService->getOrder($orderId);
        $state = strtolower($order['state'] ?? 'pending');
        $isPaid = in_array($state, ['paid', 'pagado']);

        $saleId = null;
        $redirectUrl = null;

        $this->logCheckout('debug', "Culqi [Polling]: Consultando estado orden {$orderId} -> '{$state}' (Pagado: " . ($isPaid ? 'SÍ' : 'NO') . ")");

        // 3. Si Culqi confirma que está pagada, materializar de inmediato la venta y los boletos
        if ($isPaid) {
            $sale = $this->processCulqiPaidOrder($orderId, $culqiService, $request->all());
            if ($sale) {
                $saleId = $sale->id;
                $redirectUrl = route('web.checkout.confirmation', $sale->id);
            }
        }

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'state' => $state,
            'is_paid' => $isPaid,
            'order' => $order,
            'saleId' => $saleId,
            'redirect_url' => $redirectUrl,
        ]);
    }

    /**
     * Webhook oficial para recibir notificaciones asíncronas de Culqi (IPN)
     */
    public function culqiWebhook(Request $request, CulqiService $culqiService): JsonResponse
    {
        $payload = $request->all();
        if (empty($payload)) {
            $payload = json_decode($request->getContent(), true) ?: [];
        }

        $eventType = $payload['type'] ?? ($payload['event'] ?? 'unknown');
        $data = $payload['data'] ?? [];
        if (is_string($data)) {
            $data = json_decode($data, true) ?: [];
        }

        $this->logCheckout('info', "Culqi [Webhook Recibido]: Evento '{$eventType}'", ['payload' => $payload]);

        // Extraer identificadores posibles (Order ID o Charge ID)
        $orderId = $data['id'] ?? ($data['order_id'] ?? ($payload['order_id'] ?? null));
        $chargeId = null;

        if (is_string($orderId) && (str_starts_with($orderId, 'chr_') || str_starts_with($orderId, 'chg_'))) {
            $chargeId = $orderId;
            $orderId = $data['order_id'] ?? null;
        }

        // Si tenemos un Charge ID y no hay orderId, intentar obtener la orden desde el cargo
        if (empty($orderId) && !empty($chargeId)) {
            $chargeInfo = $culqiService->getCharge($chargeId);
            $orderId = $chargeInfo['order_id'] ?? null;
        }

        // Procesar si tenemos un Order ID
        if (!empty($orderId) && str_starts_with($orderId, 'ord_')) {
            $this->logCheckout('info', "Culqi [Webhook Procesando Orden]: {$orderId}");
            $sale = $this->processCulqiPaidOrder($orderId, $culqiService);
            if ($sale) {
                $this->logCheckout('info', "Culqi [Webhook Éxito]: Venta #{$sale->id} (Recibo: {$sale->receipt_number}) completada desatendida vía Webhook para orden {$orderId}");
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order processed and tickets generated successfully',
                    'sale_id' => $sale->id,
                    'receipt_number' => $sale->receipt_number,
                ], 200);
            }
        }

        return response()->json([
            'status' => 'received',
            'event' => $eventType,
        ], 200);
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

        if ($event->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Este evento ya finalizó. No es posible emitir entradas de cortesía.',
            ], 422);
        }

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

        // Registrar boletos individuales en la tabla event_tickets y vincular butacas
        $this->processSaleCompletionAndTickets($sale, $event, $totalQty, null, $ticketsData);

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

        // Enviar Correo Electrónico Automático con Boletos y Recibo y registrar en EmailLog
        if (!empty($buyerEmail) && filter_var($buyerEmail, FILTER_VALIDATE_EMAIL)) {
            \App\Services\EmailLogService::sendTicketPurchaseMail($sale, $tempPassword, $isNewUser, $request->input('ticket_pdf_base64'));
        }

        $this->logCheckout('info', "Cortesía [Venta Emitida]: Venta #{$sale->id} (Recibo: {$sale->receipt_number}) emitida", [
            'buyer' => $buyerName,
            'email' => $buyerEmail,
            'event_id' => $event->id,
            'tickets_count' => $totalQty,
        ]);

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

    /**
     * Procesa la invalidación de boletos previos por upgrade y genera los nuevos EventTicket oficiales
     */
    protected function processSaleCompletionAndTickets(TicketSale $sale, $event, int $totalQty, $upgradeSaleId = null, array $ticketsData = []): void
    {
        $targetEvent = $event ?: ($sale->event ?: Event::find($sale->event_id));

        // 1. Si es una Mejora de Boleto, anular e invalidar la venta y boletos anteriores
        if ($upgradeSaleId) {
            $origSale = TicketSale::find($upgradeSaleId);
            if ($origSale) {
                $origSale->status = 'upgraded';
                $origSale->upgraded_to_sale_id = $sale->id;

                $origTData = is_array($origSale->tickets_data) ? $origSale->tickets_data : (json_decode($origSale->tickets_data, true) ?: []);
                $origTData['is_upgraded'] = true;
                $origTData['upgraded_to_sale_id'] = $sale->id;
                $origTData['upgraded_to_zone'] = $sale->zone_name;
                $origTData['upgraded_at'] = now()->toDateTimeString();
                $origSale->tickets_data = $origTData;
                $origSale->save();

                // Marcar boletos anteriores como anulados por upgrade para que el Scanner Móvil los rechace
                \App\Models\EventTicket::where('ticket_sale_id', $origSale->id)->update([
                    'status' => 'upgraded',
                    'is_used' => true,
                    'upgraded_at' => now(),
                ]);

                // Actualizar la venta nueva con la zona previa
                $sale->upgrade_original_zone = $origSale->zone_name;
                $sale->save();
            }
        }

        // 2. Generar boletos individuales en la tabla event_tickets para la nueva venta
        if ($targetEvent) {
            $isCourtesySale = (in_array(strtolower($sale->payment_method ?? ''), ['cortesía', 'cortesia']) || (float)$sale->total_amount == 0);
            $maxDigitalSeq = (int) \App\Models\EventTicket::where('event_id', $targetEvent->id)->where('ticket_type', 'digital')->max('ticket_number') ?: 0;
            $maxCourtesySeq = (int) \App\Models\EventTicket::where('event_id', $targetEvent->id)->where(function ($q) {
                $q->where('ticket_type', 'cortesia_digital')
                  ->orWhere(function ($sq) {
                      $sq->where('ticket_type', 'cortesia')->where('source', 'web_checkout');
                  });
            })->max('ticket_number') ?: 0;
            $startSeq = $isCourtesySale ? ($maxCourtesySeq + 1) : ($maxDigitalSeq + 1);

            $itemsToProcess = [];
            if (!empty($ticketsData) && is_array($ticketsData)) {
                $rawItems = isset($ticketsData['items']) && is_array($ticketsData['items']) ? $ticketsData['items'] : $ticketsData;
                foreach ($rawItems as $tItem) {
                    $itemQty = (int)($tItem['quantity'] ?? 1);
                    $rawItemZone = $tItem['zone_name'] ?? ($tItem['name'] ?? $sale->zone_name);
                    $cleanItemZone = $rawItemZone;
                    if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $cleanItemZone, $m)) {
                        $cleanItemZone = trim($m[1]);
                    }
                    $itemPrice = (float)($tItem['price'] ?? $sale->unit_price);
                    $rawSeats = $tItem['seats'] ?? [];
                    if (is_string($rawSeats)) {
                        $rawSeats = json_decode($rawSeats, true) ?: [];
                    }
                    $itemSeats = is_array($rawSeats) ? array_values($rawSeats) : [];

                    for ($q = 0; $q < $itemQty; $q++) {
                        $seatVal = $itemSeats[$q] ?? null;
                        $itemsToProcess[] = [
                            'zone' => $cleanItemZone,
                            'price' => $itemPrice,
                            'seat' => $seatVal ? formatShortSeatCode($seatVal) : null,
                        ];
                    }
                }
            }

            // Fallback si no había items detallados en ticketsData
            if (empty($itemsToProcess)) {
                for ($i = 1; $i <= $totalQty; $i++) {
                    $itemsToProcess[] = [
                        'zone' => $sale->zone_name,
                        'price' => $sale->unit_price,
                        'seat' => null,
                    ];
                }
            }

            $splitSettings = is_array($targetEvent->quota_split_settings) 
                ? $targetEvent->quota_split_settings 
                : (json_decode($targetEvent->quota_split_settings ?? '[]', true) ?: []);
            $isSplitActive = !empty($splitSettings['enabled']);
            $isCourtesySale = (in_array(strtolower($sale->payment_method ?? ''), ['cortesía', 'cortesia']) || (float)$sale->total_amount == 0);
            $requiredTicketType = $isSplitActive ? ($isCourtesySale ? 'cortesia_digital' : 'digital') : null;

            $currentIdx = 0;
            $matchedPhysicalTickets = [];
            $matchedTicketNumbers = [];

            foreach ($itemsToProcess as $entry) {
                $currentIdx++;
                $cleanBaseZone = preg_replace('/\s*\([^)]*\)$/', '', trim($entry['zone']));
                $seatCode = !empty($entry['seat']) ? formatShortSeatCode($entry['seat']) : null;
                $zoneWithSeat = $seatCode ? formatZoneWithSeat($entry['zone'], $seatCode) : $entry['zone'];

                $physicalTicket = null;

                $applyTicketTypeFilter = function ($q) use ($requiredTicketType) {
                    if ($requiredTicketType === 'cortesia_digital') {
                        $q->where(function ($sq) {
                            $sq->where('ticket_type', 'cortesia_digital')
                               ->orWhere(function ($ssq) {
                                   $ssq->where('ticket_type', 'cortesia')->where('source', 'web_checkout');
                               });
                        });
                    } elseif ($requiredTicketType) {
                        $q->where('ticket_type', $requiredTicketType);
                    }
                };

                if ($seatCode) {
                    // Caso Butacas Numeradas: buscar boleto pre-generado para esta butaca específica
                    $seatDigits = preg_replace('/[^0-9]/', '', $seatCode);
                    $seatLetter = preg_replace('/[^A-Za-z]/', '', $seatCode);

                    $physicalTicket = \App\Models\EventTicket::where('event_id', $targetEvent->id)
                        ->where(function ($q) {
                            $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                        })
                        ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                        ->whereNotIn('ticket_number', $matchedTicketNumbers)
                        ->tap($applyTicketTypeFilter)
                        ->where(function ($q) use ($entry, $cleanBaseZone, $seatCode, $seatDigits, $seatLetter) {
                            $q->where('zone_name', $entry['zone'])
                              ->orWhere('zone_name', 'LIKE', "%{$seatCode}%")
                              ->orWhere('zone_name', 'LIKE', "%({$seatLetter}-{$seatDigits})%")
                              ->orWhere('zone_name', 'LIKE', "%({$seatLetter} {$seatDigits})%");
                        })
                        ->orderBy('id', 'asc')
                        ->first();
                } else {
                    // Caso Zona General: buscar el siguiente boleto disponible del pool garantizando correlativo único
                    $physicalTicket = \App\Models\EventTicket::where('event_id', $targetEvent->id)
                        ->where(function ($q) {
                            $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                        })
                        ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                        ->whereNotIn('ticket_number', $matchedTicketNumbers)
                        ->tap($applyTicketTypeFilter)
                        ->where(function ($q) use ($entry, $cleanBaseZone) {
                            $q->where('zone_name', $entry['zone'])
                              ->orWhere('zone_name', 'LIKE', $cleanBaseZone . '%');
                        })
                        ->where('zone_name', 'NOT LIKE', '%(%')
                        ->orderBy('ticket_number', 'asc')
                        ->orderBy('id', 'asc')
                        ->first();

                    if (!$physicalTicket) {
                        $physicalTicket = \App\Models\EventTicket::where('event_id', $targetEvent->id)
                            ->where(function ($q) {
                                $q->whereNull('ticket_sale_id')->orWhere('ticket_sale_id', 0);
                            })
                            ->whereNotIn('id', array_keys($matchedPhysicalTickets))
                            ->whereNotIn('ticket_number', $matchedTicketNumbers)
                            ->tap($applyTicketTypeFilter)
                            ->where(function ($q) use ($entry, $cleanBaseZone) {
                                $q->where('zone_name', $entry['zone'])
                                  ->orWhere('zone_name', 'LIKE', $cleanBaseZone . '%');
                            })
                            ->orderBy('ticket_number', 'asc')
                            ->orderBy('id', 'asc')
                            ->first();
                    }
                }

                if ($physicalTicket) {
                    // USAR EL MISMO BOLETO FÍSICO QUE SE IMPRIMIÓ EN LA PLANCHA (Mismo QR, Hash y Código)
                    $matchedPhysicalTickets[$physicalTicket->id] = $physicalTicket;
                    $matchedTicketNumbers[] = (int)$physicalTicket->ticket_number;
                    $physicalTicket->update([
                        'ticket_sale_id' => $sale->id,
                        'buyer_name' => $sale->buyer_name,
                        'buyer_dni' => $sale->buyer_dni,
                        'unit_price' => $entry['price'],
                        'status' => 'valid',
                        'is_used' => false,
                    ]);
                } else {
                    // Fallback: Generar nuevo código único virtual garantizando correlativo secuencial sin colisiones
                    $targetTicketType = $requiredTicketType ?: 'digital';
                    $latestDigitalSeq = (int) \App\Models\EventTicket::where('event_id', $targetEvent->id)
                        ->where('ticket_type', $targetTicketType)
                        ->max('ticket_number') ?: 0;
                    $currentSeq = max($startSeq, $latestDigitalSeq + 1);
                    while (in_array($currentSeq, $matchedTicketNumbers)) {
                        $currentSeq++;
                    }
                    $startSeq = $currentSeq + 1;
                    $matchedTicketNumbers[] = $currentSeq;

                    $ticketCode = 'TK-' . strtoupper(substr(\Illuminate\Support\Str::slug($targetEvent->title), 0, 3)) . '-' . str_pad($currentSeq, 5, '0', STR_PAD_LEFT);
                    $validationHash = 'VG' . strtoupper(substr(md5($sale->receipt_number . $currentIdx . $sale->id . uniqid()), 0, 8));
                    $qrPayload = "VIVEGO|{$sale->receipt_number}|EVT-{$sale->event_id}|DNI-{$sale->buyer_dni}|TICK-{$currentSeq}|{$validationHash}";

                    \App\Models\EventTicket::create([
                        'event_id' => $targetEvent->id,
                        'ticket_sale_id' => $sale->id,
                        'ticket_code' => $ticketCode,
                        'ticket_number' => $currentSeq,
                        'zone_name' => $zoneWithSeat,
                        'unit_price' => $entry['price'],
                        'qr_payload' => $qrPayload,
                        'validation_hash' => $validationHash,
                        'buyer_name' => $sale->buyer_name,
                        'buyer_dni' => $sale->buyer_dni,
                        'source' => $sale->seller_name ?: 'web_checkout',
                        'ticket_type' => $targetTicketType,
                        'is_used' => false,
                        'status' => 'valid',
                    ]);
                }
            }

            // 3. Sincronizar capacidad de zonas y estado de butacas ocupadas en el evento
            $zones = is_array($targetEvent->zones) ? $targetEvent->zones : (is_string($targetEvent->zones) ? json_decode($targetEvent->zones, true) : []);
            if (!empty($zones) && is_array($zones)) {
                $zonesUpdated = false;

                // Si fue upgrade, reponer cupo en la zona anterior
                if ($upgradeSaleId && !empty($origSale)) {
                    $origZKey = strtoupper(trim(preg_replace('/\s*\([^)]*\)$/', '', (string) $origSale->zone_name)));
                    foreach ($zones as &$oz) {
                        $ozName = strtoupper(trim($oz['name'] ?? $oz['capacity_type'] ?? ''));
                        $cleanOz = preg_replace('/\s*\([^)]*\)$/', '', $ozName);
                        if ($cleanOz === $origZKey || $ozName === $origZKey) {
                            $oz['capacity'] = (int) ($oz['capacity'] ?? 0) + (int) ($origSale->quantity ?? 1);
                            $zonesUpdated = true;
                            break;
                        }
                    }
                    unset($oz);
                }

                // Agrupar cantidades y butacas seleccionadas por zona
                $qtyPerZone = [];
                $seatsPerZone = [];
                foreach ($itemsToProcess as $entry) {
                    $zKey = strtoupper(trim(preg_replace('/\s*\([^)]*\)$/', '', (string) $entry['zone'])));
                    $qtyPerZone[$zKey] = ($qtyPerZone[$zKey] ?? 0) + 1;
                    if (!empty($entry['seat'])) {
                        $seatsPerZone[$zKey][] = formatShortSeatCode($entry['seat']);
                    }
                }

                foreach ($zones as &$z) {
                    $zName = strtoupper(trim($z['name'] ?? $z['capacity_type'] ?? ''));
                    $cleanZName = preg_replace('/\s*\([^)]*\)$/', '', $zName);

                    // Marcar butacas seleccionadas como ocupadas si corresponde (sin modificar el aforo base)
                    $targetSeats = $seatsPerZone[$cleanZName] ?? ($seatsPerZone[$zName] ?? []);
                    if (!empty($targetSeats) && !empty($z['seats']) && is_array($z['seats'])) {
                        foreach ($z['seats'] as &$seatItem) {
                            $sCode = formatShortSeatCode($seatItem);
                            if (in_array($sCode, $targetSeats)) {
                                $seatItem['status'] = 'occupied';
                                $zonesUpdated = true;
                            }
                        }
                        unset($seatItem);
                    }
                }
                unset($z);

                if ($zonesUpdated) {
                    $targetEvent->zones = $zones;
                    $targetEvent->save();
                }
            }
        }

        // Registrar en log de auditoría la entrega y verificación estricta de boletos emitidos
        $assignedTickets = \App\Models\EventTicket::where('ticket_sale_id', $sale->id)->get();
        $assignedCount = $assignedTickets->count();
        $ticketCodes = $assignedTickets->pluck('ticket_code')->toArray();
        $isExact = ($assignedCount === (int)$totalQty);

        $this->logCheckout('info', "🎟️ [ENTREGA_BOLETOS] Venta #{$sale->id} ({$sale->receipt_number}): Solicitados: {$totalQty} | Emitidos: {$assignedCount} " . ($isExact ? '✓ [CANTIDAD EXACTA ENTREGADA]' : '⚠️ [DESAJUSTE DE CANTIDAD]'), [
            'sale_id' => $sale->id,
            'receipt' => $sale->receipt_number,
            'event_id' => $event?->id,
            'requested_quantity' => (int)$totalQty,
            'assigned_quantity' => $assignedCount,
            'is_exact_match' => $isExact,
            'ticket_codes' => $ticketCodes,
            'buyer_name' => $sale->buyer_name,
            'buyer_dni' => $sale->buyer_dni,
        ]);
    }
}
