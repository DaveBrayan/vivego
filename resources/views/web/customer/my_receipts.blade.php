@extends('layouts.app')

@section('title', 'Mis Recibos y Comprobantes | ViveGo Perú')

@section('content')
<div class="customer-portal-wrapper" style="background: #F8FAFC; min-height: 88vh; padding: 2rem 1.25rem 5rem 1.25rem;">
    <div class="container" style="max-width: 1140px; margin: 0 auto;">
        
        <!-- HERO GREETING & METRICS -->
        <div style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #0F172A 100%); border-radius: 24px; padding: 2rem 2.25rem; margin-bottom: 2.25rem; box-shadow: 0 15px 35px -10px rgba(15, 23, 42, 0.35); position: relative; overflow: hidden; color: #FFFFFF;">
            <!-- Glow Accent -->
            <div style="position: absolute; top: -50px; right: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(0,210,196,0.25) 0%, rgba(0,210,196,0) 70%); border-radius: 50%; pointer-events: none;"></div>

            <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); padding: 0.3rem 0.85rem; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #00D2C4; margin-bottom: 0.75rem;">
                        <span>🧾</span> COMPROBANTES DE PAGO
                    </div>
                    <h1 style="font-size: 2.1rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.35rem 0; letter-spacing: -0.5px;">
                        Historial de Comprobantes Oficiales
                    </h1>
                    <p style="color: #94A3B8; font-size: 0.95rem; margin: 0;">
                        Descarga o imprime en cualquier momento todos tus comprobantes oficiales de pago.
                    </p>
                </div>

                @php
                    $totalSpent = $sales->sum('total_amount');
                    $totalPurchases = $sales->count();
                @endphp

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 120px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #00D2C4; display: block;">{{ $totalPurchases }}</span>
                        <span style="font-size: 0.75rem; color: #CBD5E1; font-weight: 700; text-transform: uppercase;">Compras</span>
                    </div>
                    <div style="background: rgba(16,185,129,0.15); border: 1.5px solid rgba(16,185,129,0.35); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 140px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #10B981; display: block;">S/ {{ number_format($totalSpent, 2) }}</span>
                        <span style="font-size: 0.75rem; color: #A7F3D0; font-weight: 700; text-transform: uppercase;">Total Pagado</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div style="background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);">
                <span style="font-size: 1.4rem;">⛔</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; color: #92400E; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.4rem;">⚠️</span>
                <div>{{ session('warning') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #ECFDF5; border: 1.5px solid #A7F3D0; color: #065F46; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.4rem;">✨</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if($sales->isEmpty())
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 4rem 2rem; text-align: center; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #EFF6FF; border: 2px solid #DBEAFE; color: #2563EB; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto;">
                    🧾
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 900; color: #0F172A; margin: 0 0 0.5rem 0;">No tienes recibos registrados</h3>
                <p style="color: #64748B; font-size: 0.95rem; max-width: 480px; margin: 0 auto 1.75rem auto; line-height: 1.5;">
                    Cuando compres entradas para tus eventos favoritos, tus comprobantes oficiales aparecerán aquí para descarga inmediata.
                </p>
                <a href="{{ route('web.home') }}" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFF; text-decoration: none; padding: 0.95rem 2.25rem; font-weight: 900; font-size: 1rem; border-radius: 14px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 6px 20px rgba(255,85,0,0.35);">
                    Ir a la Cartelera de Eventos ➔
                </a>
            </div>
        @else
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.04);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                        <thead>
                            <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0; color: #64748B; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 1.2rem 1.25rem;">N° Recibo / Fecha</th>
                                <th style="padding: 1.2rem 1.25rem;">Evento & Sectores</th>
                                <th style="padding: 1.2rem 1.25rem; text-align: center;">Cant.</th>
                                <th style="padding: 1.2rem 1.25rem;">Método de Pago</th>
                                <th style="padding: 1.2rem 1.25rem; text-align: right;">Total Pagado</th>
                                <th style="padding: 1.2rem 1.25rem; text-align: right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                @php
                                    $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                                    $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                                    $totalTickets = count($items) > 0 ? array_sum(array_column($items, 'quantity')) : max(1, $sale->quantity);

                                    $eventDateStr = !empty($sale->event?->event_date) ? (is_string($sale->event->event_date) ? substr($sale->event->event_date, 0, 10) : $sale->event->event_date->format('Y-m-d')) : null;
                                    $isPastEvent = false;
                                    if ($eventDateStr) {
                                        $eventTimeStr = $sale->event?->event_time ?: '23:59:59';
                                        $eventDateTime = \Carbon\Carbon::parse($eventDateStr . ' ' . $eventTimeStr);
                                        $isPastEvent = $eventDateTime->isPast();
                                    }

                                    $pm = strtolower($sale->payment_method ?? '');
                                    $isCourtesy = ($pm === 'cortesía' || $pm === 'cortesia' || !empty($tData['is_courtesy']) || (float)$sale->total_amount == 0 || str_contains(strtolower($sale->zone_name ?? ''), 'cortesía') || str_contains(strtolower($sale->zone_name ?? ''), 'cortesia'));

                                    $cleanZoneName = function($name, $zoneFallback = '') {
                                        $str = !empty($name) ? $name : $zoneFallback;
                                        if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $str, $m)) {
                                            return trim($m[1]);
                                        }
                                        return $str;
                                    };
                                @endphp
                                <tr style="border-bottom: 1px solid #F1F5F9; color: #334155; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 1.2rem 1.25rem;">
                                        <strong style="color: #EA580C; font-family: monospace; font-size: 0.95rem; display: block; letter-spacing: 0.5px;">
                                            {{ $sale->receipt_number }}
                                        </strong>
                                        <small style="color: #94A3B8; font-size: 0.8rem;">
                                            {{ $sale->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td style="padding: 1.2rem 1.25rem;">
                                        <strong style="color: #0F172A; display: block; font-size: 0.95rem; margin-bottom: 0.2rem;">
                                            {{ $sale->event?->title ?? 'Evento ViveGo' }}
                                        </strong>
                                        <div style="font-size: 0.8rem; color: #64748B;">
                                            @if(count($items) > 0)
                                                @foreach($items as $idx => $it)
                                                    <span>{{ $it['quantity'] ?? 1 }}x {{ $cleanZoneName($it['zone_name'] ?? ($it['name'] ?? ''), $sale->zone_name) }}
                                                        @if(function_exists('isSalePresale') && (isSalePresale($it) || isSalePresale($sale)))
                                                            <span style="background: rgba(255,85,0,0.12); color: #FF5500; font-size: 0.65rem; font-weight: 800; padding: 1px 5px; border-radius: 4px; margin-left: 0.2rem;">🔥 Preventa</span>
                                                        @endif
                                                    {{ $idx < count($items) - 1 ? ' • ' : '' }}</span>
                                                @endforeach
                                            @else
                                                <span>{{ $sale->quantity }}x {{ $cleanZoneName($sale->zone_name) }}
                                                    @if(function_exists('isSalePresale') && isSalePresale($sale))
                                                        <span style="background: rgba(255,85,0,0.12); color: #FF5500; font-size: 0.65rem; font-weight: 800; padding: 1px 5px; border-radius: 4px; margin-left: 0.2rem;">🔥 Preventa</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 1.2rem 1.25rem; text-align: center;">
                                        <span style="background: #F1F5F9; color: #0F172A; font-weight: 900; padding: 0.25rem 0.65rem; border-radius: 8px; font-size: 0.85rem;">
                                            {{ $totalTickets }}
                                        </span>
                                    </td>
                                    <td style="padding: 1.2rem 1.25rem;">
                                        @if($isCourtesy)
                                            <span style="background: #ECFDF5; color: #059669; font-weight: 800; font-size: 0.775rem; padding: 0.3rem 0.75rem; border-radius: 20px; border: 1px solid #A7F3D0; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                🎁 Entrada de Cortesía
                                            </span>
                                        @elseif($sale->is_upgrade)
                                            <span style="background: #EEF2FF; color: #4F46E5; font-weight: 800; font-size: 0.775rem; padding: 0.3rem 0.75rem; border-radius: 20px; border: 1px solid #C7D2FE; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                ⭐ Mejora (Upgrade)
                                            </span>
                                        @else
                                            <span style="background: #ECFDF5; color: #059669; font-weight: 800; font-size: 0.775rem; padding: 0.3rem 0.75rem; border-radius: 20px; border: 1px solid #A7F3D0; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                💳 {{ $sale->payment_method ?: 'Izipay Online' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 1.2rem 1.25rem; text-align: right;">
                                        @if($isCourtesy)
                                            <strong style="color: #059669; font-size: 1.05rem; font-weight: 900; display: block;">
                                                GRATIS
                                            </strong>
                                        @elseif($sale->is_upgrade)
                                            <strong style="color: #4F46E5; font-size: 1.1rem; font-weight: 900; display: block;">
                                                +S/ {{ number_format($sale->total_amount, 2) }}
                                            </strong>
                                            <small style="display: block; font-size: 0.7rem; color: #6366F1; font-weight: 800;">
                                                Diferencia Pagada
                                            </small>
                                        @else
                                            <strong style="color: #059669; font-size: 1.1rem; font-weight: 900; display: block;">
                                                S/ {{ number_format($sale->total_amount, 2) }}
                                            </strong>
                                        @endif
                                        @if((float)($sale->discount_amount ?? 0) > 0)
                                            <small style="display: inline-block; font-size: 0.725rem; color: #EA580C; font-weight: 800; background: #FFF7ED; padding: 2px 6px; border-radius: 6px; border: 1px solid #FFEDD5; margin-top: 3px;">
                                                Ahorro: S/ {{ number_format($sale->discount_amount, 2) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td style="padding: 1.2rem 1.25rem; text-align: right;">
                                        <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end;">
                                            @if($isPastEvent)
                                                <button type="button" disabled style="background: #F1F5F9; color: #94A3B8; border: 1px solid #E2E8F0; padding: 0.5rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; cursor: not-allowed;">
                                                    🔒 Caducado
                                                </button>
                                            @else
                                                <button type="button" onclick="downloadPosSalePdf({{ $sale->id }})" data-sale-id="{{ $sale->id }}" data-sale-payload="{{ base64_encode(json_encode($sale)) }}" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; border: none; cursor: pointer; padding: 0.5rem 0.95rem; font-size: 0.825rem; font-weight: 900; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 3px 10px rgba(255,85,0,0.3); transition: transform 0.15s;">
                                                    <span>🎟️ Boleto PDF</span>
                                                </button>
                                            @endif
                                            <a href="{{ route('web.checkout.confirmation', $sale->id) }}" style="background: #FFFFFF; color: #334155; border: 1.5px solid #CBD5E1; text-decoration: none; padding: 0.5rem 0.95rem; font-size: 0.825rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                                🧾 Recibo
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.posSalesMap = window.posSalesMap || {};
        Object.assign(window.posSalesMap, @json($sales->keyBy('id')));
    </script>
    @include('web.customer.partials.ticket_generator_js')
@endpush
