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
                        <span style="font-size: 0.95rem; color: #059669; font-weight: 800;">💳 Pasarela Izipay Online</span>
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
                    @endphp
                    @foreach($items as $t)
                        <div style="display: flex; justify-content: space-between; font-size: 0.95rem; color: #1E293B; margin-bottom: 0.4rem;">
                            <span>🎟️ <strong>{{ $t['quantity'] ?? 1 }}x</strong> {{ $t['name'] ?? 'Entrada' }}</span>
                            <strong style="color: #0F172A;">S/ {{ number_format(($t['subtotal'] ?? (($t['price'] ?? 0) * ($t['quantity'] ?? 1))), 2) }}</strong>
                        </div>
                    @endforeach
                    <div style="display: flex; justify-content: space-between; border-top: 2px dashed #E2E8F0; margin-top: 0.85rem; padding-top: 0.85rem; align-items: center;">
                        <strong style="font-size: 1.1rem; color: #0F172A;">Total Pagado:</strong>
                        <strong style="font-size: 1.5rem; color: #059669; font-weight: 900;">S/ {{ number_format($sale->total_amount, 2) }}</strong>
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
