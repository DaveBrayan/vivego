<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Entradas Oficiales - ViveGo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1E293B;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #F1F5F9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" width="600" style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 600px;">
                    
                    <!-- HEADER BRANDING -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1E1B4B, #0F172A); padding: 30px; text-align: center;">
                            @if(isset($message) && file_exists(public_path('images/logo-white.png')))
                                <img src="{{ $message->embed(public_path('images/logo-white.png')) }}" alt="ViveGo" style="max-height: 48px; width: auto; margin-bottom: 8px; display: inline-block;">
                            @else
                                <span style="font-size: 28px; font-weight: 900; color: #FFFFFF; letter-spacing: -0.5px;">
                                    Vive<span style="color: #FF5500;">Go</span>
                                </span>
                            @endif
                            <p style="color: #94A3B8; font-size: 11px; margin: 4px 0 0 0; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">
                                Plataforma Oficial de Entradas y Experiencias
                            </p>
                        </td>
                    </tr>

                    <!-- ACCENT STRIP -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #FF5500, #FF0055, #00D2C4);"></td>
                    </tr>

                    @php
                        $pm = strtolower($sale->payment_method ?? '');
                        $isCourtesy = ($pm === 'cortesía' || $pm === 'cortesia' || (float)$sale->total_amount == 0);
                        $isCulqi = str_contains($pm, 'culqi');
                        $isIzipay = str_contains($pm, 'izipay');
                    @endphp

                    <!-- EVENT BANNER IMAGE (SI EXISTE) -->
                    @if(!empty($sale->event?->banner_image))
                        <tr>
                            <td style="padding: 20px 30px 0 30px; text-align: center;">
                                <img src="{{ $sale->event->banner_image }}" alt="{{ $sale->event?->title ?? 'Evento' }}" style="width: 100%; max-width: 540px; max-height: 220px; object-fit: cover; border-radius: 12px; display: block; border: 1.5px solid #E2E8F0; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            </td>
                        </tr>
                    @endif

                    <!-- SUCCESS GREETING -->
                    <tr>
                        <td style="padding: 25px 30px 20px 30px; text-align: center;">
                            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: #ECFDF5; border: 2px solid #10B981; color: #059669; font-size: 28px; line-height: 56px; margin: 0 auto 15px auto;">
                                ✓
                            </div>
                            @if($isCourtesy)
                                <span style="background-color: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #A7F3D0;">
                                    🎁 ENTRADA DE CORTESÍA CONFIRMADA
                                </span>
                                <h1 style="font-size: 24px; font-weight: 900; color: #0F172A; margin: 15px 0 5px 0;">
                                    ¡Tus Entradas de Cortesía están Listas, {{ $sale->buyer_name }}!
                                </h1>
                                <p style="font-size: 14px; color: #64748B; margin: 0; line-height: 1.5;">
                                    Tus entradas de cortesía han sido emitidas exitosamente sin costo. A continuación encontrarás el recibo oficial, las credenciales de tu cuenta y tus boletos oficiales adjuntos.
                                </p>
                            @else
                                <span style="background-color: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #A7F3D0;">
                                    PAGO CONFIRMADO & VALIDADO
                                </span>
                                <h1 style="font-size: 24px; font-weight: 900; color: #0F172A; margin: 15px 0 5px 0;">
                                    ¡Gracias por tu compra, {{ $sale->buyer_name }}!
                                </h1>
                                <p style="font-size: 14px; color: #64748B; margin: 0; line-height: 1.5;">
                                    Tu orden ha sido procesada exitosamente. A continuación encontrarás el recibo oficial, las credenciales de tu cuenta y el acceso a tus boletos.
                                </p>
                            @endif
                        </td>
                    </tr>

                    <!-- VOUCHER / RECEIPT CARD -->
                    <tr>
                        <td style="padding: 10px 30px 20px 30px;">
                            <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                                <tr>
                                    <td style="padding-bottom: 12px; border-bottom: 1.5px solid #E2E8F0;">
                                        <table width="100%">
                                            <tr>
                                                <td align="left">
                                                    <span style="font-size: 11px; color: #64748B; font-weight: 700; text-transform: uppercase;">N° de Recibo Oficial</span><br>
                                                    <strong style="font-size: 16px; color: #FF5500; font-family: monospace;">{{ $sale->receipt_number }}</strong>
                                                </td>
                                                <td align="right">
                                                    <span style="font-size: 11px; color: #64748B; font-weight: 700; text-transform: uppercase;">Fecha de Emisión</span><br>
                                                    <strong style="font-size: 13px; color: #0F172A;">{{ $sale->created_at->format('d/m/Y - h:i A') }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-top: 15px;">
                                        <table width="100%" cellpadding="4">
                                            <tr>
                                                <td width="30%" style="font-size: 13px; color: #64748B; font-weight: 700;">Evento:</td>
                                                <td style="font-size: 14px; color: #0F172A; font-weight: 800;">{{ $sale->event?->title ?? 'Evento ViveGo' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px; color: #64748B; font-weight: 700;">Recinto:</td>
                                                <td style="font-size: 13px; color: #334155;">{{ $sale->event?->venue_name ?? 'Recinto Principal' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px; color: #64748B; font-weight: 700;">Titular & DNI:</td>
                                                <td style="font-size: 13px; color: #334155;">{{ $sale->buyer_name }} (DNI: {{ $sale->buyer_dni }})</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px; color: #64748B; font-weight: 700;">Método de Pago:</td>
                                                @if($isCourtesy)
                                                    <td style="font-size: 13px; color: #10B981; font-weight: 800;">🎁 Entrada de Cortesía (Gratis / Free)</td>
                                                @elseif($isCulqi)
                                                    <td style="font-size: 13px; color: #9333EA; font-weight: 800;">🟣 Pasarela Culqi (Tarjeta / Yape / Plin)</td>
                                                @elseif($isIzipay)
                                                    <td style="font-size: 13px; color: #00D2C4; font-weight: 800;">💳 Pasarela Izipay Online</td>
                                                @else
                                                    <td style="font-size: 13px; color: #059669; font-weight: 800;">💳 {{ $sale->payment_method }}</td>
                                                @endif
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- TICKETS BREAKDOWN -->
                                <tr>
                                    <td style="padding-top: 15px;">
                                        <table width="100%" cellpadding="6" style="background-color: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px;">
                                            <tr style="background-color: #F1F5F9; border-bottom: 1px solid #CBD5E1;">
                                                <th align="left" style="font-size: 12px; color: #475569;">Entrada</th>
                                                <th align="center" style="font-size: 12px; color: #475569;">Cant.</th>
                                                <th align="right" style="font-size: 12px; color: #475569;">Subtotal</th>
                                            </tr>
                                            @php
                                                $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                                                $rawItems = $tData['items'] ?? (is_array($tData) ? $tData : []);
                                                $hasDiscount = (float)($sale->discount_amount ?? 0) > 0;
                                                $origSubtotal = (float)($sale->original_subtotal ?? ($sale->total_amount + ($sale->discount_amount ?? 0)));

                                                $cleanZoneName = function($name, $zoneFallback = '') {
                                                    $str = !empty($name) ? $name : $zoneFallback;
                                                    if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $str, $m)) {
                                                        $str = trim($m[1]);
                                                    }
                                                    return trim(preg_replace('/\s*\([^)]*\)$/', '', $str));
                                                };

                                                $groupedTickets = [];
                                                foreach ($rawItems as $t) {
                                                    if (!is_array($t)) continue;
                                                    if (empty($t['zone']) && empty($t['zone_name']) && empty($t['name']) && empty($t['ticket_code']) && !isset($t['price'])) continue;

                                                    $zoneName = $cleanZoneName($t['zone'] ?? ($t['zone_name'] ?? ($t['name'] ?? '')), $sale->zone_name);
                                                    if (empty($zoneName)) {
                                                        $zoneName = !empty($sale->zone_name) ? $cleanZoneName($sale->zone_name) : 'Entrada';
                                                    }
                                                    $groupKey = mb_strtolower($zoneName);

                                                    $seats = [];
                                                    if (!empty($t['seat'])) {
                                                        $seats[] = $t['seat'];
                                                    } elseif (!empty($t['seat_label'])) {
                                                        $seats[] = $t['seat_label'];
                                                    } elseif (!empty($t['seats'])) {
                                                        $seats = is_array($t['seats']) ? $t['seats'] : (json_decode($t['seats'], true) ?: [$t['seats']]);
                                                    } elseif (preg_match('/\(([^)]+)\)/', $t['zone'] ?? ($t['zone_name'] ?? ''), $zm)) {
                                                        $seats[] = $zm[1];
                                                    }

                                                    $qty = (int)($t['quantity'] ?? 1);
                                                    if ($qty <= 0) $qty = 1;

                                                    $subtotal = isset($t['subtotal']) ? (float)$t['subtotal'] : ((float)($t['price'] ?? 0) * $qty);
                                                    $isPresale = function_exists('isSalePresale') && (isSalePresale($t) || isSalePresale($sale));

                                                    if (!isset($groupedTickets[$groupKey])) {
                                                        $groupedTickets[$groupKey] = [
                                                            'name' => $zoneName,
                                                            'quantity' => 0,
                                                            'subtotal' => 0.0,
                                                            'seats' => [],
                                                            'is_presale' => $isPresale,
                                                        ];
                                                    }

                                                    $groupedTickets[$groupKey]['quantity'] += $qty;
                                                    $groupedTickets[$groupKey]['subtotal'] += $subtotal;
                                                    if ($isPresale) {
                                                        $groupedTickets[$groupKey]['is_presale'] = true;
                                                    }
                                                    foreach ((array)$seats as $s) {
                                                        if (!empty($s)) {
                                                            $groupedTickets[$groupKey]['seats'][] = $s;
                                                        }
                                                    }
                                                }

                                                if (empty($groupedTickets)) {
                                                    $zoneName = $cleanZoneName($sale->zone_name);
                                                    $groupKey = mb_strtolower($zoneName);
                                                    $groupedTickets[$groupKey] = [
                                                        'name' => $zoneName,
                                                        'quantity' => (int)($sale->quantity ?? 1),
                                                        'subtotal' => (float)$sale->total_amount,
                                                        'seats' => [],
                                                        'is_presale' => function_exists('isSalePresale') && isSalePresale($sale),
                                                    ];
                                                }

                                                foreach ($groupedTickets as $gKey => &$group) {
                                                    if (empty($group['seats']) && $sale->eventTickets && $sale->eventTickets->count() > 0) {
                                                        foreach ($sale->eventTickets as $et) {
                                                            $etZoneClean = $cleanZoneName($et->zone_name);
                                                            if (mb_strtolower($etZoneClean) === $gKey || str_contains(mb_strtolower($et->zone_name), $gKey)) {
                                                                if (preg_match('/\(([^)]+)\)/', $et->zone_name, $sm)) {
                                                                    $group['seats'][] = $sm[1];
                                                                } elseif (!empty($et->seat)) {
                                                                    $group['seats'][] = $et->seat;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $group['formatted_seats'] = array_values(array_unique(array_filter(array_map('formatShortSeatCode', (array)$group['seats']))));
                                                }
                                                unset($group);
                                            @endphp
                                            @foreach($groupedTickets as $t)
                                                <tr>
                                                    <td style="font-size: 13px; color: #0F172A; font-weight: 700; padding: 7px 8px; border-bottom: 1px dashed #E2E8F0;">
                                                        🎟️ {{ $t['name'] }}
                                                        @if(!empty($t['is_presale']))
                                                            <span style="display: inline-block; background-color: #FFF7ED; border: 1px solid #FFEDD5; color: #EA580C; font-size: 11px; font-weight: 900; padding: 2px 7px; border-radius: 4px; margin-left: 4px; vertical-align: middle;">🔥 PREVENTA</span>
                                                        @endif
                                                        @if(!empty($t['formatted_seats']))
                                                            <div style="margin-top: 5px; font-size: 11px; color: #059669; font-weight: 800;">
                                                                <span style="color: #64748B;">🪑 Butacas:</span>
                                                                @foreach($t['formatted_seats'] as $fs)
                                                                    <span style="display: inline-block; background-color: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 2px 6px; border-radius: 4px; margin-right: 3px; font-weight: 900;">{{ $fs }}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td align="center" style="font-size: 13px; color: #475569; padding: 7px 8px; border-bottom: 1px dashed #E2E8F0;">{{ $t['quantity'] }}</td>
                                                    <td align="right" style="font-size: 13px; color: {{ $sale->is_upgrade ? '#4F46E5' : '#059669' }}; font-weight: 800; padding: 7px 8px; border-bottom: 1px dashed #E2E8F0;">
                                                        {{ $sale->is_upgrade ? '+ ' : '' }}S/ {{ number_format($t['subtotal'], 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if($hasDiscount)
                                                <tr style="border-top: 1.5px dashed #CBD5E1;">
                                                    <td colspan="2" style="font-size: 13px; color: #64748B; font-weight: 700; padding-top: 8px;">Subtotal Base:</td>
                                                    <td align="right" style="font-size: 13px; color: #64748B; font-weight: 700; padding-top: 8px;">S/ {{ number_format($origSubtotal, 2) }}</td>
                                                </tr>
                                                @if(!empty($sale->campaign_name))
                                                    <tr>
                                                        <td colspan="2" style="font-size: 12px; color: #FF5500; font-weight: 800;">🔥 Campaña ({{ $sale->campaign_name }}):</td>
                                                        <td align="right" style="font-size: 12px; color: #FF5500; font-weight: 800;">-S/ {{ number_format($tData['campaign_discount'] ?? $sale->discount_amount, 2) }}</td>
                                                    </tr>
                                                @endif
                                                @if(!empty($sale->coupon_code))
                                                    <tr>
                                                        <td colspan="2" style="font-size: 12px; color: #059669; font-weight: 800;">🎟️ Cupón Canjeado ({{ $sale->coupon_code }}):</td>
                                                        <td align="right" style="font-size: 12px; color: #059669; font-weight: 800;">-S/ {{ number_format($tData['coupon_discount'] ?? $sale->discount_amount, 2) }}</td>
                                                    </tr>
                                                @endif
                                                @if(empty($sale->campaign_name) && empty($sale->coupon_code))
                                                    <tr>
                                                        <td colspan="2" style="font-size: 12px; color: #FF5500; font-weight: 800;">🏷️ Descuento Comercial:</td>
                                                        <td align="right" style="font-size: 12px; color: #FF5500; font-weight: 800;">-S/ {{ number_format($sale->discount_amount, 2) }}</td>
                                                    </tr>
                                                @endif
                                            @endif

                                            <tr style="border-top: 1.5px dashed #CBD5E1;">
                                                <td colspan="2" style="font-size: 14px; font-weight: 900; color: #0F172A; padding-top: 10px;">
                                                    {{ $sale->is_upgrade ? 'DIFERENCIA PAGADA:' : 'TOTAL PAGADO:' }}
                                                </td>
                                                <td align="right" style="font-size: 18px; font-weight: 900; color: {{ $sale->is_upgrade ? '#4F46E5' : '#059669' }}; padding-top: 10px;">
                                                    {{ $sale->is_upgrade ? '+ ' : '' }}S/ {{ number_format($sale->total_amount, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- USER PORTAL CREDENTIALS BOX (LIGHT STYLE) -->
                    @php
                        $userEmail = $sale->tickets_data['customer_email'] ?? $sale->buyer_name;
                    @endphp
                    <tr>
                        <td style="padding: 0 30px 25px 30px;">
                            <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 22px; color: #1E293B;">
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 8px;">
                                            <tr>
                                                <td align="left">
                                                    <strong style="font-size: 16px; color: #0F172A; letter-spacing: -0.2px;">
                                                        🔑 Acceso a tu Cuenta de Cliente ViveGo
                                                    </strong>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <p style="font-size: 13px; color: #475569; margin: 0 0 14px 0; line-height: 1.45;">
                                            Hemos creado tu perfil para que puedas consultar tus entradas activas, descargar tus boletos y ver tus recibos en cualquier momento:
                                        </p>

                                        <table width="100%" cellpadding="9" style="background: #FFFFFF; border-radius: 10px; border: 1.5px solid #E2E8F0; margin-bottom: 14px;">
                                            <tr>
                                                <td width="38%" style="font-size: 12px; color: #64748B; font-weight: 700; border-bottom: 1px solid #F1F5F9;">🔗 Enlace de Acceso:</td>
                                                <td style="font-size: 13px; color: #FF5500; font-weight: 700; border-bottom: 1px solid #F1F5F9;">
                                                    <a href="{{ route('web.login') }}" style="color: #FF5500; text-decoration: underline; font-weight: 800;">{{ route('web.login') }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 700; {{ !empty($tempPassword) ? 'border-bottom: 1px solid #F1F5F9;' : '' }}">👤 Usuario (Correo):</td>
                                                <td style="font-size: 13px; color: #0F172A; font-weight: 800; font-family: monospace; {{ !empty($tempPassword) ? 'border-bottom: 1px solid #F1F5F9;' : '' }}">{{ $userEmail }}</td>
                                            </tr>
                                            @if(!empty($tempPassword))
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 700;">🔑 Contraseña Temporal:</td>
                                                <td>
                                                    <span style="background: #FF5500; color: #FFFFFF; font-family: monospace; font-size: 15px; font-weight: 900; padding: 4px 12px; border-radius: 6px; letter-spacing: 1px; display: inline-block;">{{ $tempPassword }}</span>
                                                </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 700;">🔑 Contraseña:</td>
                                                <td style="font-size: 12px; color: #334155; font-weight: 600;">Tu contraseña habitual registrada</td>
                                            </tr>
                                            @endif
                                        </table>

                                        @if(!empty($tempPassword))
                                        <p style="font-size: 11px; color: #64748B; margin: 0; line-height: 1.35;">
                                            💡 Te sugerimos iniciar sesión con esta contraseña temporal y actualizarla en tu panel de cliente cuando desees.
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA BUTTON -->
                    <tr>
                        <td align="center" style="padding: 5px 30px 35px 30px;">
                            <a href="{{ route('customer.my_tickets') }}" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; text-decoration: none; padding: 14px 32px; font-size: 15px; font-weight: 800; border-radius: 12px; display: inline-block; box-shadow: 0 4px 15px rgba(255,85,0,0.35);">
                                🎟️ Ingresar y Ver Mis Boletos en ViveGo
                            </a>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #F8FAFC; border-top: 1.5px solid #E2E8F0; padding: 25px 30px; text-align: center;">
                            <p style="font-size: 12px; color: #64748B; margin: 0 0 8px 0;">
                                Este es un correo automático emitido por <strong>ViveGo Perú</strong> en alianza con <strong>Izipay</strong>.
                            </p>
                            <p style="font-size: 11px; color: #94A3B8; margin: 0;">
                                © {{ date('Y') }} ViveGo. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
