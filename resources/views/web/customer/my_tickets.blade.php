@extends('layouts.app')

@section('title', 'Mis Boletos Oficiales | ViveGo Perú')

@section('content')
<div class="customer-portal-wrapper" style="background: #F8FAFC; min-height: 88vh; padding: 2rem 1.25rem 5rem 1.25rem;">
    <div class="container" style="max-width: 1140px; margin: 0 auto;">
        
        <!-- HERO GREETING & METRICS -->
        <div style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 60%, #0F172A 100%); border-radius: 24px; padding: 2rem 2.25rem; margin-bottom: 2.25rem; box-shadow: 0 15px 35px -10px rgba(15, 23, 42, 0.35); position: relative; overflow: hidden; color: #FFFFFF;">
            <!-- Glow Accent -->
            <div style="position: absolute; top: -50px; right: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(255,85,0,0.3) 0%, rgba(255,85,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

            <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); padding: 0.3rem 0.85rem; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #00D2C4; margin-bottom: 0.75rem;">
                        <span>👤</span> PORTAL EXCLUSIVO DE CLIENTES
                    </div>
                    <h1 style="font-size: 2.1rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.35rem 0; letter-spacing: -0.5px;">
                        ¡Hola, {{ session('customer_name') ?: 'Cliente ViveGo' }}! 👋
                    </h1>
                    <p style="color: #94A3B8; font-size: 0.95rem; margin: 0;">
                        Aquí tienes el control de todas tus entradas oficiales y boletos digitales adquiridos.
                    </p>
                </div>

                <!-- KPI Quick Stats -->
                @php
                    $totalTicketsCount = 0;
                    $activeCount = 0;
                    $pastCount = 0;

                    foreach($sales as $s) {
                        $tData = is_array($s->tickets_data) ? $s->tickets_data : json_decode($s->tickets_data, true);
                        $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                        $qty = count($items) > 0 ? array_sum(array_column($items, 'quantity')) : max(1, $s->quantity);
                        $totalTicketsCount += $qty;

                        $eDateStr = !empty($s->event?->event_date) ? (is_string($s->event->event_date) ? substr($s->event->event_date, 0, 10) : $s->event->event_date->format('Y-m-d')) : null;
                        $isPast = false;
                        if ($eDateStr) {
                            $eTime = $s->event?->event_time ?: '23:59:59';
                            $dt = \Carbon\Carbon::parse($eDateStr . ' ' . $eTime);
                            $isPast = $dt->isPast();
                        }
                        if ($isPast) {
                            $pastCount += $qty;
                        } else {
                            $activeCount += $qty;
                        }
                    }
                @endphp

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 110px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #10B981; display: block;">{{ $activeCount }}</span>
                        <span style="font-size: 0.75rem; color: #CBD5E1; font-weight: 700; text-transform: uppercase;">Activas</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 110px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #94A3B8; display: block;">{{ $pastCount }}</span>
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Finalizadas</span>
                    </div>
                    <div style="background: rgba(255,85,0,0.15); border: 1.5px solid rgba(255,85,0,0.35); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 110px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #FF5500; display: block;">{{ $totalTicketsCount }}</span>
                        <span style="font-size: 0.75rem; color: #FFD2BD; font-weight: 700; text-transform: uppercase;">Total Boletos</span>
                    </div>
                </div>
            </div>
        </div>

        @if($sales->isEmpty())
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 4rem 2rem; text-align: center; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05); animation: fadeIn 0.4s ease-out;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #FFF7ED; border: 2px solid #FFEDD5; color: #FF5500; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto;">
                    🎟️
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 900; color: #0F172A; margin: 0 0 0.5rem 0;">Aún no tienes boletos registrados</h3>
                <p style="color: #64748B; font-size: 0.95rem; max-width: 480px; margin: 0 auto 1.75rem auto; line-height: 1.5;">
                    Explora nuestra cartelera de eventos en vivo y adquiere tus entradas oficiales con código QR y validación instantánea.
                </p>
                <a href="{{ route('web.home') }}" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFF; text-decoration: none; padding: 0.95rem 2.25rem; font-weight: 900; font-size: 1rem; border-radius: 14px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 6px 20px rgba(255,85,0,0.35); transition: transform 0.2s;">
                    Explorar Cartelera ➔
                </a>
            </div>
        @else
            <!-- TICKETS GRID -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 2rem;">
                @foreach($sales as $sale)
                    @php
                        $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                        $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                        $totalTickets = count($items) > 0 ? array_sum(array_column($items, 'quantity')) : max(1, $sale->quantity);

                        // Evaluar si el evento ya pasó
                        $eventDateStr = !empty($sale->event?->event_date) ? (is_string($sale->event->event_date) ? substr($sale->event->event_date, 0, 10) : $sale->event->event_date->format('Y-m-d')) : null;
                        $isPastEvent = false;
                        if ($eventDateStr) {
                            $eventTimeStr = $sale->event?->event_time ?: '23:59:59';
                            $eventDateTime = \Carbon\Carbon::parse($eventDateStr . ' ' . $eventTimeStr);
                            $isPastEvent = $eventDateTime->isPast();
                        }
                    @endphp

                    <div class="ticket-customer-card" style="background: #FFFFFF; border: 1.5px solid {{ $isPastEvent ? '#E2E8F0' : '#CBD5E1' }}; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); display: flex; flex-direction: column; position: relative; transition: transform 0.2s, box-shadow 0.2s; opacity: {{ $isPastEvent ? '0.78' : '1' }};">
                        
                        <!-- Top Image Banner -->
                        <div style="position: relative; height: 160px; background: #0F172A; overflow: hidden;">
                            @if($sale->event && $sale->event->banner_image)
                                <img src="{{ $sale->event->banner_image }}" alt="{{ $sale->event->title }}" style="width: 100%; height: 100%; object-fit: cover; {{ $isPastEvent ? 'filter: grayscale(100%) contrast(85%); opacity: 0.65;' : 'transition: transform 0.3s;' }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1E1B4B, #0F172A); display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 2.5rem; {{ $isPastEvent ? 'filter: grayscale(100%);' : '' }}">🎟️</div>
                            @endif

                            <!-- Gradient Protection Overlay -->
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.8) 0%, transparent 60%);"></div>
                            
                            <!-- Status Badge -->
                            @if($isPastEvent)
                                <span style="position: absolute; top: 12px; right: 12px; background: #64748B; color: #FFFFFF; font-size: 0.75rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                                    🕒 EVENTO FINALIZADO
                                </span>
                            @else
                                <span style="position: absolute; top: 12px; right: 12px; background: #10B981; color: #FFFFFF; font-size: 0.75rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(16,185,129,0.35); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #34D399;">
                                    ✓ ENTRADA VÁLIDA
                                </span>
                            @endif

                            <!-- Event Date Tag -->
                            <span style="position: absolute; bottom: 12px; left: 14px; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); color: #FFFFFF; font-size: 0.775rem; font-weight: 800; padding: 0.35rem 0.75rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); display: inline-flex; align-items: center; gap: 0.4rem;">
                                📅 {{ !empty($sale->event?->event_date) ? (is_string($sale->event->event_date) ? substr($sale->event->event_date, 0, 10) : $sale->event->event_date->format('d/m/Y')) : $sale->created_at->format('d/m/Y') }}
                                {{ $sale->event?->event_time ? ' • ' . $sale->event->event_time : '' }}
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <span style="font-size: 0.75rem; font-weight: 900; color: #EA580C; font-family: monospace; letter-spacing: 0.5px;">
                                    OPERACIÓN #{{ $sale->receipt_number }}
                                </span>
                                <span style="font-size: 0.85rem; color: #059669; font-weight: 900;">
                                    S/ {{ number_format($sale->total_amount, 2) }}
                                </span>
                            </div>

                            <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0 0 0.9rem 0; line-height: 1.25;">
                                {{ $sale->event?->title ?? 'Evento ViveGo' }}
                            </h3>

                            <!-- Desglose por Sectores / Zonas Adquiridas -->
                            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.95rem; margin-bottom: 1.25rem;">
                                <span style="font-size: 0.725rem; font-weight: 800; color: #64748B; text-transform: uppercase; display: block; margin-bottom: 0.5rem; letter-spacing: 0.5px;">
                                    Sectores Adquiridos ({{ $totalTickets }} entrada{{ $totalTickets > 1 ? 's' : '' }}):
                                </span>
                                
                                <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                                    @if(count($items) > 0)
                                        @foreach($items as $t)
                                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0.45rem 0.75rem;">
                                                <span style="font-weight: 800; color: #0F172A;">
                                                    <span style="background: rgba(255,85,0,0.12); color: #FF5500; padding: 2px 6px; border-radius: 6px; font-weight: 900;">{{ $t['quantity'] ?? 1 }}x</span>
                                                    {{ $t['name'] ?? 'Entrada' }}
                                                </span>
                                                <span style="font-weight: 800; color: #059669; font-size: 0.85rem;">
                                                    S/ {{ number_format(($t['subtotal'] ?? (($t['price'] ?? 0) * ($t['quantity'] ?? 1))), 2) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0.45rem 0.75rem;">
                                            <span style="font-weight: 800; color: #0F172A;">
                                                <span style="background: rgba(255,85,0,0.12); color: #FF5500; padding: 2px 6px; border-radius: 6px; font-weight: 900;">{{ $sale->quantity }}x</span>
                                                {{ $sale->zone_name }}
                                            </span>
                                            <span style="font-weight: 800; color: #059669; font-size: 0.85rem;">
                                                S/ {{ number_format($sale->total_amount, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div style="margin-top: 0.75rem; padding-top: 0.65rem; border-top: 1px dashed #CBD5E1; font-size: 0.8rem; color: #64748B; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.3rem;">
                                    <span>📍 {{ $sale->event?->venue_name ?? 'Recinto Oficial' }}</span>
                                    <span>👤 {{ $sale->buyer_name }}</span>
                                </div>
                            </div>

                            <!-- Botón Generar Boleto PDF -->
                            <div style="margin-top: auto;">
                                @if($isPastEvent)
                                    <button type="button" disabled style="width: 100%; background: #F1F5F9; color: #94A3B8; border: 1.5px solid #E2E8F0; padding: 0.9rem 1rem; font-size: 0.9rem; font-weight: 800; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: not-allowed;">
                                        <span>🔒 Entrada Caducada</span>
                                    </button>
                                @else
                                    <a href="{{ route('web.customer.ticket_pdf', $sale->id) }}" class="btn-generar-boleto" style="width: 100%; box-sizing: border-box; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; text-align: center; text-decoration: none; padding: 0.95rem 1rem; font-size: 0.975rem; font-weight: 900; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 6px 18px rgba(255, 85, 0, 0.35); transition: transform 0.15s, box-shadow 0.15s;">
                                        <span>🎟️ Generar Boleto</span>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

<style>
    .ticket-customer-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1) !important;
    }
    .ticket-customer-card:hover img {
        transform: scale(1.03);
    }
    .btn-generar-boleto:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 85, 0, 0.45) !important;
    }
</style>
@endsection
