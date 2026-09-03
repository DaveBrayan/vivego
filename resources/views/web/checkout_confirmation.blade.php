@extends('layouts.app')

@section('title', '¡Compra Confirmada! | Vive Go')

@section('content')
<div class="checkout-light-page-wrapper" style="padding: 3rem 1.25rem 5rem 1.25rem;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 2.75rem; box-shadow: 0 15px 35px -5px rgba(0,0,0,0.06); position: relative; overflow: hidden;">
            
            <!-- Top Accent Gradient -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #FF5500, #FF0055, #00D2C4);"></div>

            <div style="text-align: center; margin-bottom: 2.25rem;">
                <div style="width: 76px; height: 76px; border-radius: 50%; background: #ECFDF5; border: 2.5px solid #10B981; color: #059669; font-size: 2.75rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto; box-shadow: 0 6px 20px rgba(16,185,129,0.2);">
                    ✓
                </div>
                <span style="background: #ECFDF5; color: #065F46; font-weight: 800; font-size: 0.8rem; padding: 0.35rem 0.95rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #A7F3D0;">
                    PAGO 100% CONFIRMADO & VALIDADO
                </span>
                <h1 style="font-size: 2.2rem; font-weight: 900; color: #0F172A; margin: 0.75rem 0 0.4rem 0;">¡Gracias por tu compra, {{ $sale->buyer_name }}!</h1>
                <p style="color: #64748B; font-size: 1rem; margin: 0;">Tus entradas oficiales con código QR han sido emitidas y registradas exitosamente.</p>
            </div>

            <!-- Voucher Box -->
            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 1.75rem; margin-bottom: 2rem;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid #E2E8F0; padding-bottom: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span style="font-size: 0.8rem; color: #64748B; font-weight: 700; display: block;">NÚMERO DE OPERACIÓN:</span>
                        <strong style="font-size: 1.2rem; color: #EA580C; font-family: monospace; font-weight: 900;">{{ $sale->receipt_number }}</strong>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.8rem; color: #64748B; font-weight: 700; display: block;">FECHA & HORA:</span>
                        <strong style="font-size: 0.95rem; color: #0F172A;">{{ $sale->created_at->format('d/m/Y - h:i A') }}</strong>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <span style="font-size: 0.8rem; color: #64748B; font-weight: 700; display: block;">EVENTO:</span>
                        <strong style="font-size: 1.05rem; color: #0F172A;">{{ $sale->event?->title ?? 'Evento ViveGo' }}</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #64748B; font-weight: 700; display: block;">LUGAR / RECINTO:</span>
                        <span style="font-size: 0.95rem; color: #334155; font-weight: 600;">{{ $sale->event?->venue_name ?? 'Recinto Oficial' }}</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #64748B; font-weight: 700; display: block;">TITULAR & DNI:</span>
                        <span style="font-size: 0.95rem; color: #334155; font-weight: 600;">{{ $sale->buyer_name }} ({{ $sale->buyer_dni }})</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #64748B; font-weight: 700; display: block;">MÉTODO DE PAGO:</span>
                        @php
                            $pm = strtolower($sale->payment_method ?? '');
                        @endphp
                        @if($pm === 'cortesía' || $pm === 'cortesia' || (float)$sale->total_amount == 0)
                            <span style="font-size: 0.95rem; color: #10B981; font-weight: 800;">🎁 Entrada de Cortesía (Gratis / Free)</span>
                        @elseif(str_contains($pm, 'culqi'))
                            <span style="font-size: 0.95rem; color: #9333EA; font-weight: 800;">🟣 Pasarela Culqi (Tarjeta / Yape / Plin)</span>
                        @elseif(str_contains($pm, 'izipay'))
                            <span style="font-size: 0.95rem; color: #00D2C4; font-weight: 800;">💳 Pasarela Izipay Online</span>
                        @else
                            <span style="font-size: 0.95rem; color: #059669; font-weight: 800;">💳 {{ $sale->payment_method }}</span>
                        @endif
                    </div>
                </div>

                <!-- Boletos Desglose -->
                <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; margin-top: 1rem;">
                    <span style="font-size: 0.8rem; font-weight: 800; color: #64748B; text-transform: uppercase; display: block; margin-bottom: 0.75rem;">
                        Boletos Oficiales Adquiridos:
                    </span>
                    @php
                        $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                        $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                        $hasDiscount = (float)($sale->discount_amount ?? 0) > 0;
                        $origSubtotal = (float)($sale->original_subtotal ?? ($sale->total_amount + ($sale->discount_amount ?? 0)));

                        $cleanZoneName = function($name, $zoneFallback = '') {
                            $str = !empty($name) ? $name : $zoneFallback;
                            if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $str, $m)) {
                                return trim($m[1]);
                            }
                            return $str;
                        };
                    @endphp

                    @if($sale->is_upgrade)
                        <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 10px; padding: 0.6rem 0.85rem; margin-bottom: 0.85rem; font-size: 0.82rem; color: #4338CA; font-weight: 700;">
                            ⭐ <strong>Mejora de Entrada Confirmada:</strong> Tu entrada anterior fue actualizada con éxito a la nueva zona vigente.
                        </div>
                    @endif

                    @foreach($items as $t)
                        @php
                            $tSeats = !empty($t['seats']) ? (is_array($t['seats']) ? $t['seats'] : json_decode($t['seats'], true)) : [];
                            if (empty($tSeats) && $sale->eventTickets && $sale->eventTickets->count() > 0) {
                                $zoneBase = $cleanZoneName($t['zone_name'] ?? ($t['name'] ?? ''), $sale->zone_name);
                                foreach ($sale->eventTickets as $et) {
                                    if (str_contains($et->zone_name, $zoneBase) && preg_match('/\(([^)]+)\)/', $et->zone_name, $sm)) {
                                        $tSeats[] = $sm[1];
                                    }
                                }
                            }
                            $formattedSeats = array_filter(array_map('formatShortSeatCode', (array)$tSeats));
                        @endphp
                        <div style="margin-bottom: 0.65rem; padding-bottom: 0.55rem; border-bottom: 1px dashed #E2E8F0;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.95rem; color: #1E293B; margin-bottom: 0.25rem;">
                                <span>
                                    🎟️ <strong>{{ $t['quantity'] ?? 1 }}x</strong> {{ $cleanZoneName($t['zone_name'] ?? ($t['name'] ?? ''), $sale->zone_name) }}
                                    @if(function_exists('isSalePresale') && (isSalePresale($t) || isSalePresale($sale)))
                                        <span style="background: rgba(255, 85, 0, 0.12); color: #FF5500; border: 1.5px solid rgba(255, 85, 0, 0.35); font-size: 0.72rem; font-weight: 900; padding: 2px 7px; border-radius: 6px; text-transform: uppercase; margin-left: 0.4rem;">🔥 Tarifa Preventa</span>
                                    @endif
                                </span>
                                <strong style="color: #0F172A;">
                                    @if($sale->is_upgrade)
                                        <span style="font-size: 0.85rem; color: #6366F1;">+S/ {{ number_format(($t['subtotal'] ?? (($t['price'] ?? 0) * ($t['quantity'] ?? 1))), 2) }}</span>
                                    @else
                                        S/ {{ number_format(($t['subtotal'] ?? (($t['price'] ?? 0) * ($t['quantity'] ?? 1))), 2) }}
                                    @endif
                                </strong>
                            </div>
                            @if(!empty($formattedSeats))
                                <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; align-items: center; padding-left: 1.5rem; margin-top: 0.2rem;">
                                    <span style="font-size: 0.75rem; font-weight: 800; color: #059669; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        🪑 Butacas Asignadas:
                                    </span>
                                    @foreach($formattedSeats as $fs)
                                        <span style="background: rgba(16, 185, 129, 0.12); color: #047857; border: 1.5px solid rgba(16, 185, 129, 0.35); font-size: 0.75rem; font-weight: 900; padding: 2px 8px; border-radius: 6px; box-shadow: 0 1px 3px rgba(16,185,129,0.08);">
                                            {{ $fs }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @if($hasDiscount)
                        <div style="border-top: 1px dashed #CBD5E1; margin-top: 0.6rem; padding-top: 0.6rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: #64748B; margin-bottom: 0.3rem;">
                                <span>Subtotal Base:</span>
                                <strong>S/ {{ number_format($origSubtotal, 2) }}</strong>
                            </div>
                            @if(!empty($sale->campaign_name))
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #FF5500; font-weight: 800; margin-bottom: 0.3rem;">
                                    <span>🔥 Campaña Promocional ({{ $sale->campaign_name }}):</span>
                                    <span>-S/ {{ number_format($tData['campaign_discount'] ?? $sale->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            @if(!empty($sale->coupon_code))
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #059669; font-weight: 800; margin-bottom: 0.3rem;">
                                    <span>🎟️ Cupón de Descuento ({{ $sale->coupon_code }}):</span>
                                    <span>-S/ {{ number_format($tData['coupon_discount'] ?? $sale->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            @if(empty($sale->campaign_name) && empty($sale->coupon_code))
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #FF5500; font-weight: 800; margin-bottom: 0.3rem;">
                                    <span>🏷️ Descuento Comercial:</span>
                                    <span>-S/ {{ number_format($sale->discount_amount, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; border-top: 2px dashed #E2E8F0; margin-top: 0.85rem; padding-top: 0.85rem; align-items: center;">
                        <strong style="font-size: 1.1rem; color: #0F172A;">
                            {{ $sale->is_upgrade ? 'Diferencia Pagada:' : 'Total Pagado:' }}
                        </strong>
                        <strong style="font-size: 1.5rem; color: {{ $sale->is_upgrade ? '#4F46E5' : '#059669' }}; font-weight: 900;">
                            {{ $sale->is_upgrade ? '+ ' : '' }}S/ {{ number_format($sale->total_amount, 2) }}
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('web.customer.tickets') }}" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFF; text-decoration: none; padding: 0.9rem 2rem; font-weight: 800; border-radius: 12px; font-size: 1rem; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 14px rgba(255,85,0,0.3);">
                    🎟️ Ver Mis Boletos en Línea
                </a>
                <a href="{{ route('web.home') }}" style="background: #FFFFFF; color: #334155; border: 1.5px solid #CBD5E1; text-decoration: none; padding: 0.9rem 1.85rem; font-weight: 700; border-radius: 12px; font-size: 1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    🏠 Volver a la Cartelera
                </a>
                <button type="button" onclick="window.print();" style="background: #F1F5F9; color: #334155; border: 1.5px solid #CBD5E1; padding: 0.9rem 1.5rem; font-weight: 700; border-radius: 12px; font-size: 1rem; cursor: pointer;">
                    🖨️ Imprimir
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
