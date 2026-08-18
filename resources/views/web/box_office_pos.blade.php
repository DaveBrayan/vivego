@extends('layouts.app')

@section('title', 'Punto de Venta POS - ' . $event->title . ' | Vive Go')

@push('styles')
    <style>
        .pos-header-card {
            background: linear-gradient(135deg, rgba(20, 20, 30, 0.95), rgba(30, 30, 45, 0.95));
            border: 1.5px solid rgba(255, 85, 0, 0.3);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.75rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .pos-zone-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.15rem;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .pos-zone-card:hover {
            border-color: var(--color-primary-orange);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 85, 0, 0.15);
        }
        .pos-quick-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #FFFFFF;
            padding: 0.45rem 0.85rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .pos-quick-btn:hover {
            background: var(--color-primary-orange);
            border-color: var(--color-primary-orange);
            color: #FFFFFF;
        }
        .pos-quick-btn.active {
            background: var(--color-primary-orange);
            border-color: var(--color-primary-orange);
            color: #FFFFFF;
        }
        .payment-method-pill {
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.03);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 700;
            font-size: 0.9rem;
            color: #FFFFFF;
        }
        .payment-method-pill:hover {
            border-color: rgba(255, 85, 0, 0.5);
            background: rgba(255, 85, 0, 0.05);
        }
        .payment-method-pill.active {
            border-color: var(--color-primary-orange);
            background: rgba(255, 85, 0, 0.15);
            box-shadow: 0 0 15px rgba(255, 85, 0, 0.25);
        }

        /* CARDS SELECCIONABLES DE ZONAS / SECTORES */
        .zone-card-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 0.85rem 1.15rem;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            user-select: none;
        }
        .zone-card-item:hover:not(.disabled) {
            background: rgba(255, 85, 0, 0.06);
            border-color: rgba(255, 85, 0, 0.4);
            transform: translateY(-2px);
        }
        .zone-card-item.active {
            background: rgba(255, 85, 0, 0.12) !important;
            border-color: var(--color-primary-orange) !important;
            box-shadow: 0 0 18px rgba(255, 85, 0, 0.3);
        }
        .zone-card-item.disabled {
            opacity: 0.45;
            cursor: not-allowed;
            border-color: rgba(255, 255, 255, 0.05);
        }
        .zone-radio-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .zone-card-item.active .zone-radio-indicator {
            border-color: var(--color-primary-orange);
            background: var(--color-primary-orange);
            box-shadow: 0 0 8px rgba(255, 85, 0, 0.6);
        }
        .zone-card-item.active .zone-radio-indicator::after {
            content: '';
            width: 6px;
            height: 6px;
            background: #FFFFFF;
            border-radius: 50%;
        }
        .zone-card-name {
            font-size: 0.925rem;
            font-weight: 800;
            color: #FFFFFF;
            display: block;
        }
        .zone-card-price {
            font-size: 1.2rem;
            font-weight: 900;
            color: var(--color-primary-orange);
            display: block;
        }
        .zone-stock-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
        }
        .zone-stock-badge.available {
            background: rgba(16, 185, 129, 0.15);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .zone-stock-badge.sold-out {
            background: rgba(239, 68, 68, 0.15);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* BOTONES DE CANTIDAD NARANJA Y STEPPER */
        .pos-stepper-btn.orange-btn {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: var(--color-primary-orange, #FF5500);
            color: #FFFFFF;
            border: none;
            font-size: 1.6rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(255, 85, 0, 0.4);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pos-stepper-btn.orange-btn:hover {
            background: #FF6611;
            transform: scale(1.06);
            box-shadow: 0 6px 18px rgba(255, 85, 0, 0.55);
        }
        .pos-stepper-btn.orange-btn:active {
            transform: scale(0.94);
        }
        .pos-stepper-input {
            width: 90px;
            height: 48px;
            text-align: center;
            font-size: 1.45rem;
            font-weight: 900;
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 85, 0, 0.3);
            border-radius: 14px;
        }

        /* CARD DE TOTAL RESUMEN */
        .pos-total-summary-card {
            background: rgba(255, 85, 0, 0.08);
            border: 2px solid var(--color-primary-orange);
            border-radius: 18px;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 18px rgba(255, 85, 0, 0.15);
        }

        @media (max-width: 860px) {
            .pos-modal-two-columns {
                grid-template-columns: 1fr !important;
                gap: 1.25rem !important;
            }
        }

        /* ESTILOS DEL RECIBO TÉRMICO PARA IMPRESIÓN 80MM / 58MM */
        @media print {
            body * {
                visibility: hidden;
            }
            #thermalReceiptContainer, #thermalReceiptContainer * {
                visibility: visible;
            }
            #thermalReceiptContainer {
                position: absolute;
                left: 0;
                top: 0;
                width: 76mm !important;
                margin: 0 !important;
                padding: 4mm !important;
                background: #FFFFFF !important;
                color: #000000 !important;
                font-family: 'Courier New', Courier, monospace !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN PRO MAX HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" id="salesTableSearch" class="dash-search-input" placeholder="Buscar venta por cliente, DNI, recibo o zona...">
                    <span class="dash-kbd-shortcut">⌘K</span>
                </div>

                <div class="dash-top-actions">
                    <!-- Botón Selector de Tema Claro / Oscuro -->
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema (Claro / Oscuro)">
                        <span id="themeToggleIcon">☀️</span>
                    </button>

                    <!-- Notificaciones -->
                    <button class="dash-icon-btn" id="btnNotifications" title="Notificaciones">
                        <span>🔔</span>
                        <span class="dash-unread-dot"></span>
                    </button>
                </div>
            </header>

            <div class="dash-container">
                
                <!-- HEADER PRINCIPAL DE LA TAQUILLA DEL EVENTO -->
                <div class="pos-header-card">
                    <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1; min-width: 300px;">
                        <div style="width: 72px; height: 72px; border-radius: 16px; overflow: hidden; flex-shrink: 0; border: 1.5px solid rgba(255, 85, 0, 0.4); background: #000;">
                            <img src="{{ $event->banner_image ?? 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
                                <a href="{{ route('web.box_office') }}" class="dash-badge-custom badge-blue" style="text-decoration: none; font-size: 0.75rem;">← Volver a Taquillas</a>
                                @if(($event->sales_type ?? 'fisica') === 'fisica')
                                    <span class="dash-badge-custom badge-orange" style="font-size: 0.75rem;">🎫 Venta Física (Taquilla)</span>
                                @else
                                    <span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; color: #00F0FF; border: 1px solid rgba(0,240,255,0.4); background: rgba(0,240,255,0.1);">🌐 Venta Virtual (Online)</span>
                                @endif
                                <span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">✓ Caja Abierta</span>
                            </div>
                            <h1 style="font-size: 1.45rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.25rem 0;">{{ $event->title }}</h1>
                            <div style="display: flex; align-items: center; gap: 1rem; color: #94A3B8; font-size: 0.85rem; font-weight: 600;">
                                <span>🗓️ {{ !empty($event->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('d/m/Y')) : '10/04/2025' }}</span>
                                <span>⏰ {{ $event->event_time ?? '18:00' }}</span>
                                <span>📍 {{ $event->venue_name ?? 'Complejo San Luis' }} ({{ $event->address ?? 'Ayacucho' }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- BOTÓN PRINCIPAL NUEVA VENTA POS -->
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <button type="button" class="btn btn-primary" onclick="openPosSaleModal()" style="font-size: 1.05rem; font-weight: 800; padding: 0.9rem 1.8rem; border-radius: 14px; box-shadow: 0 6px 22px rgba(255, 85, 0, 0.45); display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                            <span style="font-size: 1.3rem;">🛒</span>
                            <span>+ REGISTRAR VENTA (F1)</span>
                        </button>
                    </div>
                </div>

                <!-- KPIS EN TIEMPO REAL DEL EVENTO -->
                <div class="dash-stats-grid" style="margin-bottom: 1.75rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                    <div class="dash-stat-card" style="border: 1px solid rgba(16, 185, 129, 0.4); background: rgba(16, 185, 129, 0.06); padding: 1.25rem; border-radius: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                            <span style="font-size: 0.775rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Recaudación Total</span>
                            <span style="font-size: 1.3rem;">💰</span>
                        </div>
                        <div style="font-size: 1.85rem; font-weight: 900; color: #10B981;" id="kpiTotalRevenue">{{ $metrics['total_revenue'] }}</div>
                        <div style="font-size: 0.75rem; color: #94A3B8; margin-top: 0.2rem;" id="kpiSalesCount">{{ $metrics['sales_count'] }} ventas registradas</div>
                    </div>

                    <div class="dash-stat-card" style="border: 1px solid rgba(255, 85, 0, 0.4); background: rgba(255, 85, 0, 0.06); padding: 1.25rem; border-radius: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                            <span style="font-size: 0.775rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Boletos Vendidos</span>
                            <span style="font-size: 1.3rem;">🎟️</span>
                        </div>
                        <div style="font-size: 1.85rem; font-weight: 900; color: #FFFFFF;" id="kpiTicketsSold">{{ number_format($metrics['tickets_sold']) }} <span style="font-size: 0.9rem; color: #94A3B8;">/ {{ number_format($metrics['total_capacity']) }}</span></div>
                        <div style="font-size: 0.75rem; color: var(--color-primary-orange);">Stock restante: <strong id="kpiRemainingStock">{{ number_format($metrics['remaining_stock']) }}</strong></div>
                    </div>

                    <div class="dash-stat-card" style="border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.03); padding: 1.25rem; border-radius: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                            <span style="font-size: 0.775rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Efectivo en Caja</span>
                            <span style="font-size: 1.3rem;">💵</span>
                        </div>
                        <div style="font-size: 1.85rem; font-weight: 900; color: #F59E0B;" id="kpiCashRevenue">{{ $metrics['cash_revenue'] }}</div>
                        <div style="font-size: 0.75rem; color: #94A3B8;">Cobrado en billetes / monedas</div>
                    </div>

                    <div class="dash-stat-card" style="border: 1px solid rgba(0, 240, 255, 0.3); background: rgba(0, 240, 255, 0.05); padding: 1.25rem; border-radius: 16px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                            <span style="font-size: 0.775rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Digital (Yape / Plin / POS)</span>
                            <span style="font-size: 1.3rem;">📱</span>
                        </div>
                        <div style="font-size: 1.85rem; font-weight: 900; color: #00F0FF;" id="kpiDigitalRevenue">{{ $metrics['digital_revenue'] }}</div>
                        <div style="font-size: 0.75rem; color: #94A3B8;">Transferencias y pagos con tarjeta</div>
                    </div>
                </div>

                <!-- STOCK EN TIEMPO REAL POR ZONAS -->
                <div class="settings-card-box" style="margin-bottom: 1.75rem;">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">📊</div>
                        <div>
                            <h3 class="card-header-title">Disponibilidad de Stock por Zona / Sector</h3>
                            <p class="card-header-subtitle">Monitorea el aforo en tiempo real. Al registrar una venta, el stock se descuenta automáticamente.</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;" id="zonesStockContainer">
                        @foreach($zonesWithStats as $z)
                            <div class="pos-zone-card" data-zone-name="{{ $z['name'] }}" style="{{ $z['available'] <= 0 ? 'border-color: rgba(239, 68, 68, 0.4);' : '' }}">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <div>
                                        <h4 style="font-size: 1rem; font-weight: 800; color: #FFFFFF; margin: 0 0 0.2rem 0;">{{ $z['name'] }}</h4>
                                        <span style="font-size: 1.15rem; font-weight: 900; color: #10B981;">S/ {{ number_format($z['price'], 2) }}</span>
                                    </div>
                                    <span class="dash-badge-custom {{ $z['available'] > 0 ? 'badge-green' : 'badge-red' }}" style="font-size: 0.75rem; font-weight: 800;">
                                        {{ $z['available'] > 0 ? 'Disponible' : 'Agotado' }}
                                    </span>
                                </div>

                                <div style="margin-top: 0.75rem;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.775rem; font-weight: 700; margin-bottom: 0.35rem;">
                                        <span style="color: #94A3B8;">Vendidos: <strong style="color: #FFFFFF;" class="zone-sold-count">{{ $z['sold'] }}</strong> / <span class="zone-total-cap">{{ $z['capacity'] }}</span></span>
                                        <span style="color: {{ $z['available'] > 0 ? '#10B981' : '#EF4444' }};">Quedan: <strong class="zone-available-count">{{ $z['available'] }}</strong></span>
                                    </div>
                                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden;">
                                        <div class="zone-progress-bar" style="height: 100%; width: {{ $z['percentage'] }}%; background: linear-gradient(90deg, #FF5500, #10B981); border-radius: 10px; transition: width 0.4s ease;"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- TABLA DE VENTAS REGISTRADAS EN TIEMPO REAL -->
                <div class="settings-card-box">
                    <div class="settings-card-header" style="flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">🧾</div>
                            <div>
                                <h3 class="card-header-title">Registro de Ventas en Vivo</h3>
                                <p class="card-header-subtitle">Historial de transacciones de taquilla con cálculo de cambio y opción de reimpresión de recibo térmico.</p>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-sm" onclick="openPosSaleModal()" style="font-weight: 800; padding: 0.6rem 1.25rem; border-radius: 12px;">
                            + Nueva Venta
                        </button>
                    </div>

                    <!-- TABLA OFICIAL DE HISTORIAL DE VENTAS -->
                    <div class="dash-table-container">
                        <table class="dash-table" id="salesHistoryTable">
                            <thead>
                                <tr>
                                    <th>N° Recibo</th>
                                    <th>Fecha / Hora</th>
                                    <th>Cliente</th>
                                    <th>Zona / Sector</th>
                                    <th style="text-align: center;">Cant.</th>
                                    <th>Total Cobrado</th>
                                    <th>Método de Pago</th>
                                    <th>Pagó / Vuelto</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody">
                                @forelse($sales as $sale)
                                    <tr class="sale-row-item" data-sale-id="{{ $sale->id }}">
                                        <td>
                                            <span style="font-weight: 800; color: var(--color-primary-orange); font-family: monospace; font-size: 0.95rem;">
                                                {{ $sale->receipt_number }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="color: #94A3B8; font-size: 0.825rem; font-weight: 600;">
                                                {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : 'Hoy' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong style="color: #FFFFFF; font-size: 0.925rem; display: block;">{{ $sale->buyer_name }}</strong>
                                                <small style="color: #94A3B8; font-size: 0.775rem;">DNI: {{ $sale->buyer_dni }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="dash-badge-custom badge-blue" style="font-size: 0.75rem;">
                                                {{ $sale->zone_name }}
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span style="font-weight: 900; font-size: 1rem; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 8px;">
                                                {{ $sale->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: #10B981; font-size: 1rem; font-weight: 900;">
                                                S/ {{ number_format($sale->total_amount, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if($sale->payment_method === 'Efectivo')
                                                <span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">💵 Efectivo</span>
                                            @elseif($sale->payment_method === 'Yape')
                                                <span class="dash-badge-custom badge-purple" style="font-size: 0.75rem; background: rgba(168, 85, 247, 0.15); color: #A855F7; border: 1px solid rgba(168, 85, 247, 0.3);">📱 Yape</span>
                                            @elseif($sale->payment_method === 'Plin')
                                                <span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; color: #00F0FF; background: rgba(0, 240, 255, 0.15); border: 1px solid rgba(0, 240, 255, 0.3);">🟣 Plin</span>
                                            @else
                                                <span class="dash-badge-custom badge-blue" style="font-size: 0.75rem;">💳 {{ $sale->payment_method }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-size: 0.8rem; color: #94A3B8;">
                                                <span>Pagó: S/ {{ number_format($sale->amount_paid, 2) }}</span><br>
                                                <strong style="color: #10B981;">Vuelto: S/ {{ number_format($sale->change_amount, 2) }}</strong>
                                            </div>
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="reprintReceipt({{ json_encode($sale) }})" title="Reimprimir Recibo Térmico" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: 1px solid rgba(255,85,0,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3); cursor: pointer;">
                                                    <span>🧾</span>
                                                    <span>Recibo</span>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="downloadPosSalePdf({{ json_encode($sale) }})" title="Descargar Entrada PDF" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: 1px solid rgba(6,182,212,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); cursor: pointer;">
                                                    <span>🎟️</span>
                                                    <span>Entrada PDF</span>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deletePosSale({{ $sale->id }})" title="Borrar Entrada" style="background: linear-gradient(135deg, #EF4444, #DC2626); border: 1px solid rgba(239,68,68,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); cursor: pointer;">
                                                    <span>🗑️</span>
                                                    <span>Borrar Entrada</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptySalesRow">
                                        <td colspan="9" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
                                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🛒</div>
                                            <strong>Aún no se han registrado ventas para este evento.</strong><br>
                                            <span>Haz clic en <strong>"+ REGISTRAR VENTA"</strong> para emitir tu primer boleto y recibo térmico.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL PUNTO DE VENTA (REGISTRAR VENTA POS) -->
    <div class="admin-modal-overlay" id="posSaleModal">
        <div class="admin-modal-card" style="max-width: 1060px; width: 95%; max-height: 92vh; overflow-y: auto; padding: 2rem; border-radius: 28px; box-sizing: border-box;">
            
            <div class="admin-modal-header" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 44px; height: 44px; background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange); display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.4rem;">🛒</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.25rem; margin: 0; color: #FFFFFF; font-weight: 900;">Nueva Venta de Taquilla (POS)</h3>
                        <p class="card-header-subtitle" style="margin: 0; font-size: 0.825rem; color: #94A3B8;">{{ $event->title }}</p>
                    </div>
                </div>
                <button type="button" class="admin-modal-close" onclick="closePosSaleModal()" style="font-size: 1.3rem; color: #94A3B8; background: transparent; border: none; cursor: pointer;">✕</button>
            </div>

            <form id="posSaleForm" onsubmit="handlePosSaleSubmit(event)">
                <!-- GRID DE 2 COLUMNAS PRINCIPALES -->
                <div class="pos-modal-two-columns" style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 1.75rem; align-items: start; margin-bottom: 1.5rem;">
                    
                    <!-- COLUMNA 1: SELECCIÓN DE SECTORES / ZONAS EN CARDS + CANTIDAD + TOTAL A COBRAR -->
                    <div>
                        <!-- SECTORES / ZONAS EN CARDS INTERACTIVAS -->
                        <div class="form-group-custom" style="margin-bottom: 1.35rem;">
                            <label class="form-label-custom" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                                <span>🎟️ Sector / Zona de Entrada <span class="required-star">*</span></span>
                                <small style="color: #94A3B8; font-weight: 600;">Haz clic para seleccionar</small>
                            </label>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.65rem;" id="zoneCardsContainer">
                                @foreach($zonesWithStats as $index => $z)
                                    <div class="zone-card-item {{ $index === 0 && $z['available'] > 0 ? 'active' : '' }} {{ $z['available'] <= 0 ? 'disabled' : '' }}"
                                         data-name="{{ $z['name'] }}"
                                         data-price="{{ $z['price'] }}"
                                         data-available="{{ $z['available'] }}"
                                         onclick="selectZoneCard('{{ addslashes($z['name']) }}', {{ $z['price'] }}, {{ $z['available'] }}, this)">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div class="zone-radio-indicator"></div>
                                                <div>
                                                    <strong class="zone-card-name">{{ $z['name'] }}</strong>
                                                    <div style="margin-top: 0.2rem;">
                                                        @if($z['available'] > 0)
                                                            <span class="zone-stock-badge available">📦 Stock: {{ number_format($z['available']) }} libres</span>
                                                        @else
                                                            <span class="zone-stock-badge sold-out">🚫 AGOTADO</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="text-align: right;">
                                                <span class="zone-card-price">S/ {{ number_format($z['price'], 2) }}</span>
                                                <small style="display: block; font-size: 0.7rem; color: #94A3B8;">por entrada</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Input oculto para guardar el sector seleccionado -->
                            <input type="hidden" id="pos_zone_select" value="{{ $zonesWithStats[0]['name'] ?? '' }}" required>
                        </div>

                        <!-- CANTIDAD DE ENTRADAS CON BOTONES NARANJAS Y QUICK PILLS -->
                        <div class="form-group-custom" style="margin-bottom: 1.35rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 1.15rem; border-radius: 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                                <label class="form-label-custom" style="margin: 0;">🎫 Cantidad de Entradas <span class="required-star">*</span></label>
                                <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                    <button type="button" class="pos-quick-btn active" id="btnQuickQty1" onclick="setPosQuantity(1)">1</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty2" onclick="setPosQuantity(2)">2</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty3" onclick="setPosQuantity(3)">3</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty4" onclick="setPosQuantity(4)">4</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty5" onclick="setPosQuantity(5)">5</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty10" onclick="setPosQuantity(10)">10</button>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.85rem; justify-content: center; margin-top: 0.6rem;">
                                <button type="button" class="pos-stepper-btn orange-btn" onclick="stepPosQuantity(-1)" title="Restar una entrada">-</button>
                                <input type="number" id="pos_quantity" class="form-input-custom pos-stepper-input" value="1" min="1" max="50" required oninput="calculatePosTotal()">
                                <button type="button" class="pos-stepper-btn orange-btn" onclick="stepPosQuantity(1)" title="Sumar una entrada">+</button>
                            </div>
                        </div>

                        <!-- TOTAL A PAGAR RESALTADO PRO MAX -->
                        <div class="pos-total-summary-card">
                            <div>
                                <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Total a Cobrar</span>
                                <small style="color: #FFFFFF; font-weight: 600;" id="posUnitPriceDesc">1 entrada x S/ 0.00</small>
                            </div>
                            <div style="font-size: 2.2rem; font-weight: 900; color: #10B981; text-shadow: 0 2px 12px rgba(16, 185, 129, 0.3);" id="posTotalAmountDisplay">
                                S/ 0.00
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 2: DATOS DEL CLIENTE + MÉTODO DE PAGO + CALCULADORA DE VUELTO -->
                    <div>
                        <!-- DATOS DEL CLIENTE / COMPRADOR CON CHECKBOX SIN DATOS -->
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 1.25rem; border-radius: 18px; margin-bottom: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
                                <h4 style="font-size: 0.9rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <span>👤</span> <span>Datos del Comprador</span>
                                </h4>
                                <label style="display: flex; align-items: center; gap: 0.45rem; cursor: pointer; font-size: 0.775rem; font-weight: 800; color: var(--color-primary-orange); background: rgba(255,85,0,0.12); padding: 0.35rem 0.75rem; border-radius: 10px; border: 1px solid rgba(255,85,0,0.35); user-select: none;">
                                    <input type="checkbox" id="chkAnonymousBuyer" onchange="toggleAnonymousBuyer(this)" style="cursor: pointer; width: 16px; height: 16px; accent-color: #FF5500;">
                                    <span>Sin Datos (Venta Rápida)</span>
                                </label>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.9rem;">
                                <label for="pos_buyer_dni" class="form-label-custom">DNI / Documento <span class="required-star" id="star_buyer_dni">*</span></label>
                                <input type="text" id="pos_buyer_dni" class="form-input-custom" placeholder="Ej: 72819203" required style="font-weight: 700; letter-spacing: 0.5px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.9rem;">
                                <label for="pos_buyer_name" class="form-label-custom">Nombre Completo <span class="required-star" id="star_buyer_name">*</span></label>
                                <input type="text" id="pos_buyer_name" class="form-input-custom" placeholder="Ej: Juan Pérez Morales" required style="font-weight: 600;">
                            </div>

                            <div class="form-group-custom">
                                <label for="pos_buyer_phone" class="form-label-custom">Teléfono / WhatsApp (Opcional)</label>
                                <input type="text" id="pos_buyer_phone" class="form-input-custom" placeholder="Ej: +51 987654321">
                            </div>
                        </div>

                        <!-- MÉTODO DE PAGO CON PILLS -->
                        <div class="form-group-custom" style="margin-bottom: 1.25rem;">
                            <label class="form-label-custom">Método de Pago <span class="required-star">*</span></label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(85px, 1fr)); gap: 0.4rem;" id="paymentMethodsGroup">
                                <div class="payment-method-pill active" onclick="selectPaymentMethod('Efectivo', this)">
                                    <span>💵</span> <span>Efectivo</span>
                                </div>
                                <div class="payment-method-pill" onclick="selectPaymentMethod('Yape', this)">
                                    <span>📱</span> <span>Yape</span>
                                </div>
                                <div class="payment-method-pill" onclick="selectPaymentMethod('Plin', this)">
                                    <span>🟣</span> <span>Plin</span>
                                </div>
                                <div class="payment-method-pill" onclick="selectPaymentMethod('Tarjeta', this)">
                                    <span>💳</span> <span>Tarjeta</span>
                                </div>
                                <div class="payment-method-pill" onclick="selectPaymentMethod('Transferencia', this)">
                                    <span>🏦</span> <span>Transf.</span>
                                </div>
                            </div>
                            <input type="hidden" id="pos_payment_method" value="Efectivo">
                        </div>

                        <!-- CALCULADORA DE VUELTO / CAMBIO (SOLO EN EFECTIVO) -->
                        <div id="cashCalculatorBox" style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 18px; padding: 1.15rem; margin-bottom: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label class="form-label-custom" style="margin: 0;">💵 Monto Recibido del Cliente <span class="required-star">*</span></label>
                                <div style="display: flex; gap: 0.3rem; flex-wrap: wrap;">
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(10)">S/ 10</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(20)">S/ 20</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(50)">S/ 50</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(100)">S/ 100</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(200)">S/ 200</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid('exact')">Exacto</button>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; align-items: center;">
                                <div>
                                    <input type="number" id="pos_amount_paid" class="form-input-custom" step="0.50" min="0" placeholder="0.00" style="font-size: 1.2rem; font-weight: 800;" oninput="calculateChange()">
                                </div>
                                <div style="background: rgba(0,0,0,0.5); padding: 0.65rem 0.85rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); text-align: right;">
                                    <span style="font-size: 0.7rem; color: #94A3B8; font-weight: 700; display: block; text-transform: uppercase;">Cambio / Vuelto:</span>
                                    <strong style="font-size: 1.3rem; font-weight: 900; color: #10B981;" id="posChangeAmountDisplay">S/ 0.00</strong>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN DEL FOOTER -->
                <div style="display: flex; gap: 0.85rem; justify-content: flex-end; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.25rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePosSaleModal()" style="padding: 0.85rem 1.6rem; font-weight: 700;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitPosSale" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2rem; font-size: 1rem; font-weight: 900; box-shadow: 0 6px 20px rgba(255, 85, 0, 0.45);">
                        🧾 Confirmar Venta & Imprimir Recibo
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- CONTENEDOR OCULTO PARA IMPRESIÓN DEL RECIBO TÉRMICO (80MM POS) -->
    <div id="thermalReceiptContainer" style="display: none;"></div>

@endsection

@push('scripts')
    <!-- SweetAlert2, html2canvas, jsPDF, html2pdf y QRCode Generator Oficial -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    <script>
        const eventId = {{ $event->id }};
        const csrfToken = "{{ csrf_token() }}";

        // Función para anular / borrar una venta de entrada y restaurar aforo
        async function deletePosSale(saleId) {
            if (!saleId) return;

            const result = await Swal.fire({
                title: '🗑️ ¿Borrar Entrada / Venta?',
                text: 'Esta acción anulará el boleto emitido y devolverá el aforo correspondiente a la zona.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Sí, borrar entrada',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Eliminando entrada...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            try {
                const response = await fetch(`/admin/taquilla/venta/${saleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const row = document.querySelector(`tr[data-sale-id="${saleId}"]`);
                    if (row) {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        setTimeout(() => row.remove(), 300);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: '¡Entrada Borrada!',
                        text: data.message || 'El boleto fue eliminado y el aforo fue restaurado.',
                        background: '#14141E',
                        color: '#FFFFFF',
                        timer: 1800,
                        showConfirmButton: false
                    });

                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'No se pudo eliminar la entrada.');
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al borrar',
                    text: err.message || 'Ocurrió un problema al intentar eliminar la entrada.',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }
        }
        let currentTotalToPay = 0;
        let selectedZoneName = "{{ $zonesWithStats[0]['name'] ?? '' }}";
        let selectedZonePrice = {{ $zonesWithStats[0]['price'] ?? 0 }};
        let selectedZoneAvailable = {{ $zonesWithStats[0]['available'] ?? 100 }};

        function selectZoneCard(name, price, available, el) {
            if (available <= 0) return;
            
            document.querySelectorAll('.zone-card-item').forEach(c => c.classList.remove('active'));
            if (el) el.classList.add('active');

            selectedZoneName = name;
            selectedZonePrice = parseFloat(price) || 0;
            selectedZoneAvailable = parseInt(available, 10) || 0;

            const zoneInput = document.getElementById('pos_zone_select');
            if (zoneInput) zoneInput.value = name;

            // Restringir el límite de cantidad si el stock es menor
            const qtyInput = document.getElementById('pos_quantity');
            if (qtyInput) {
                qtyInput.max = selectedZoneAvailable;
                if (parseInt(qtyInput.value, 10) > selectedZoneAvailable) {
                    qtyInput.value = Math.max(1, selectedZoneAvailable);
                }
            }

            calculatePosTotal();
        }

        function openPosSaleModal() {
            const modal = document.getElementById('posSaleModal');
            if (modal) {
                modal.classList.add('active');
                
                // Auto-seleccionar primer sector disponible si ninguno está activo
                const activeCard = document.querySelector('.zone-card-item.active') || document.querySelector('.zone-card-item:not(.disabled)');
                if (activeCard) {
                    const name = activeCard.getAttribute('data-name');
                    const price = activeCard.getAttribute('data-price');
                    const available = activeCard.getAttribute('data-available');
                    selectZoneCard(name, price, available, activeCard);
                } else {
                    calculatePosTotal();
                }

                setTimeout(() => {
                    document.getElementById('pos_buyer_dni')?.focus();
                }, 150);
            }
        }

        function closePosSaleModal() {
            const modal = document.getElementById('posSaleModal');
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function setPosQuantity(qty) {
            const input = document.getElementById('pos_quantity');
            if (input) {
                const maxStock = selectedZoneAvailable > 0 ? selectedZoneAvailable : 50;
                input.value = Math.min(qty, maxStock);
                
                // Actualizar estado activo en los botones de cantidad rápida
                document.querySelectorAll('.pos-quick-btn[id^="btnQuickQty"]').forEach(b => b.classList.remove('active'));
                const quickBtn = document.getElementById(`btnQuickQty${qty}`);
                if (quickBtn) quickBtn.classList.add('active');
                
                calculatePosTotal();
            }
        }

        function stepPosQuantity(step) {
            const input = document.getElementById('pos_quantity');
            if (input) {
                let current = parseInt(input.value, 10) || 1;
                const maxStock = selectedZoneAvailable > 0 ? selectedZoneAvailable : 50;
                current = Math.max(1, Math.min(maxStock, current + step));
                input.value = current;

                document.querySelectorAll('.pos-quick-btn[id^="btnQuickQty"]').forEach(b => b.classList.remove('active'));
                const quickBtn = document.getElementById(`btnQuickQty${current}`);
                if (quickBtn) quickBtn.classList.add('active');

                calculatePosTotal();
            }
        }

        function calculatePosTotal() {
            const qtyInput = document.getElementById('pos_quantity');
            const totalDisplay = document.getElementById('posTotalAmountDisplay');
            const unitPriceDesc = document.getElementById('posUnitPriceDesc');

            const quantity = parseInt(qtyInput?.value, 10) || 1;
            currentTotalToPay = Math.round(selectedZonePrice * quantity * 100) / 100;

            if (totalDisplay) {
                totalDisplay.textContent = `S/ ${currentTotalToPay.toFixed(2)}`;
            }
            if (unitPriceDesc) {
                unitPriceDesc.textContent = `${quantity} entrada(s) x S/ ${selectedZonePrice.toFixed(2)}`;
            }

            // Actualizar cálculo de vuelto si el método es efectivo
            calculateChange();
        }

        function selectPaymentMethod(method, el) {
            document.querySelectorAll('.payment-method-pill').forEach(p => p.classList.remove('active'));
            if (el) el.classList.add('active');

            const methodInput = document.getElementById('pos_payment_method');
            if (methodInput) methodInput.value = method;

            const cashBox = document.getElementById('cashCalculatorBox');
            if (cashBox) {
                cashBox.style.display = (method === 'Efectivo') ? 'block' : 'none';
            }

            if (method !== 'Efectivo') {
                const paidInput = document.getElementById('pos_amount_paid');
                if (paidInput) paidInput.value = currentTotalToPay.toFixed(2);
            }
            calculateChange();
        }

        function setCashPaid(val) {
            const paidInput = document.getElementById('pos_amount_paid');
            if (!paidInput) return;

            if (val === 'exact') {
                paidInput.value = currentTotalToPay.toFixed(2);
            } else {
                paidInput.value = parseFloat(val).toFixed(2);
            }
            calculateChange();
        }

        function calculateChange() {
            const method = document.getElementById('pos_payment_method')?.value || 'Efectivo';
            const paidInput = document.getElementById('pos_amount_paid');
            const changeDisplay = document.getElementById('posChangeAmountDisplay');

            if (method !== 'Efectivo') {
                if (changeDisplay) changeDisplay.textContent = 'S/ 0.00';
                return;
            }

            const paid = parseFloat(paidInput?.value) || 0;
            const change = Math.max(0, paid - currentTotalToPay);

            if (changeDisplay) {
                changeDisplay.textContent = `S/ ${change.toFixed(2)}`;
                if (paid < currentTotalToPay && paid > 0) {
                    changeDisplay.style.color = '#EF4444';
                    changeDisplay.textContent = `Falta S/ ${(currentTotalToPay - paid).toFixed(2)}`;
                } else {
                    changeDisplay.style.color = '#10B981';
                }
            }
        }

        // Manejar checkbox de Cliente Anónimo / Sin Datos
        function toggleAnonymousBuyer(chk) {
            const isAnon = chk.checked;
            const dniInput = document.getElementById('pos_buyer_dni');
            const nameInput = document.getElementById('pos_buyer_name');
            const phoneInput = document.getElementById('pos_buyer_phone');
            const starDni = document.getElementById('star_buyer_dni');
            const starName = document.getElementById('star_buyer_name');

            if (isAnon) {
                if (dniInput) {
                    dniInput.value = '00000000';
                    dniInput.readOnly = true;
                    dniInput.style.opacity = '0.55';
                    dniInput.required = false;
                }
                if (nameInput) {
                    nameInput.value = 'CLIENTE VARIOS';
                    nameInput.readOnly = true;
                    nameInput.style.opacity = '0.55';
                    nameInput.required = false;
                }
                if (phoneInput) {
                    phoneInput.value = '-';
                    phoneInput.readOnly = true;
                    phoneInput.style.opacity = '0.55';
                }
                if (starDni) starDni.style.display = 'none';
                if (starName) starName.style.display = 'none';
            } else {
                if (dniInput) {
                    dniInput.value = '';
                    dniInput.readOnly = false;
                    dniInput.style.opacity = '1';
                    dniInput.required = true;
                    dniInput.focus();
                }
                if (nameInput) {
                    nameInput.value = '';
                    nameInput.readOnly = false;
                    nameInput.style.opacity = '1';
                    nameInput.required = true;
                }
                if (phoneInput) {
                    phoneInput.value = '';
                    phoneInput.readOnly = false;
                    phoneInput.style.opacity = '1';
                }
                if (starDni) starDni.style.display = '';
                if (starName) starName.style.display = '';
            }
        }

        // Generar QR sincrónico en Base64 Data URL
        function generateQrBase64(payload) {
            try {
                const qr = qrcode(0, 'M');
                qr.addData(payload);
                qr.make();
                return qr.createDataURL(5, 0);
            } catch (e) {
                console.error('Error generando QR:', e);
                return '';
            }
        }

        // Imprimir Recibo Térmico Oficial para Impresoras POS de 80mm / 58mm vía iframe aislado
        function printThermalReceipt(receiptData, eventData) {
            let iframe = document.getElementById('thermalPrintIframe');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'thermalPrintIframe';
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                document.body.appendChild(iframe);
            }

            const receiptHtml = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Recibo ${receiptData.receipt_number}</title>
                    <style>
                        @page {
                            size: 80mm auto;
                            margin: 0;
                        }
                        body {
                            margin: 0;
                            padding: 4mm;
                            font-family: 'Courier New', Courier, monospace;
                            font-size: 11px;
                            line-height: 1.35;
                            color: #000000;
                            background: #FFFFFF;
                            width: 72mm;
                            box-sizing: border-box;
                        }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .bold { font-weight: bold; }
                        .dashed-line { border-bottom: 1px dashed #000; margin: 6px 0; }
                        table { width: 100%; font-size: 10px; border-collapse: collapse; margin-bottom: 6px; }
                        th, td { padding: 3px 0; }
                    </style>
                </head>
                <body>
                    <div class="text-center">
                        <div style="font-size: 18px; font-weight: 900; letter-spacing: 1px;">VIVE GO</div>
                        <div style="font-size: 9px; font-weight: bold;">VIVE CADA MOMENTO - TICKETS OFICIALES</div>
                        <div style="font-size: 9px;">RUC: 20601234567</div>
                    </div>
                    
                    <div class="dashed-line"></div>

                    <div class="text-center">
                        <div style="font-size: 12px; font-weight: 900; text-transform: uppercase;">${eventData.title}</div>
                        <div style="font-size: 10px; margin-top: 2px;">📍 ${eventData.venue_name || ''}</div>
                        <div style="font-size: 9px;">${eventData.address || ''}</div>
                        <div style="font-size: 10px; font-weight: bold; margin-top: 2px;">🗓️ ${eventData.event_date || ''} - ${eventData.event_time || ''}</div>
                    </div>

                    <div class="dashed-line"></div>

                    <div>
                        <div class="bold" style="font-size: 11px;">COMPROBANTE DE VENTA: ${receiptData.receipt_number}</div>
                        <div style="font-size: 9px;">FECHA/HORA: ${receiptData.created_at_formatted}</div>
                        <div class="bold" style="font-size: 10px; margin-top: 3px;">CLIENTE: ${receiptData.buyer_name}</div>
                        <div style="font-size: 9px;">DNI/DOC: ${receiptData.buyer_dni}</div>
                    </div>

                    <div class="dashed-line"></div>

                    <table>
                        <thead>
                            <tr style="border-bottom: 1px solid #000;">
                                <th style="text-align: left;">DESCRIPCIÓN</th>
                                <th class="text-center">CANT</th>
                                <th class="text-right">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${receiptData.zone_name}</td>
                                <td class="text-center">${receiptData.quantity}</td>
                                <td class="text-right bold">${receiptData.total_amount_formatted}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="dashed-line"></div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 13px;">
                            <span>TOTAL A PAGAR:</span>
                            <span>${receiptData.total_amount_formatted}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 2px; font-size: 10px;">
                            <span>MÉTODO DE PAGO:</span>
                            <span>${receiptData.payment_method}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 10px;">
                            <span>MONTO RECIBIDO:</span>
                            <span>${receiptData.amount_paid_formatted}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 11px; margin-top: 2px;">
                            <span>CAMBIO / VUELTO:</span>
                            <span>${receiptData.change_amount_formatted}</span>
                        </div>
                    </div>

                    <div class="dashed-line"></div>

                    <div class="text-center" style="font-size: 9px; padding: 4px 0;">
                        <div class="bold" style="font-size: 11px; margin-bottom: 2px;">¡GRACIAS POR SU COMPRA!</div>
                        <div>CONSERVE ESTE COMPROBANTE DE PAGO</div>
                        <div style="margin-top: 4px; font-size: 8px; color: #444;">ViveGo Platform v2.0 - Impresión POS 80mm</div>
                    </div>
                </body>
                </html>
            `;

            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(receiptHtml);
            doc.close();

            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 300);
        }

        async function preloadPosImageAsDataUrl(url, type = 'banner') {
            if (!url || typeof url !== 'string' || url.trim() === '') return '';
            if (url.startsWith('data:')) return url;
            try {
                const response = await fetch(url, { mode: 'cors', cache: 'force-cache' });
                if (response.ok) {
                    const blob = await response.blob();
                    const dataUrl = await new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.onerror = reject;
                        reader.readAsDataURL(blob);
                    });
                    if (dataUrl && dataUrl.startsWith('data:image')) return dataUrl;
                }
            } catch (e) {}
            try {
                const dataUrl = await new Promise((resolve, reject) => {
                    const img = new Image();
                    img.crossOrigin = 'Anonymous';
                    const timeout = setTimeout(() => reject(new Error('Timeout')), 3000);
                    img.onload = () => {
                        clearTimeout(timeout);
                        try {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.naturalWidth || 600;
                            canvas.height = img.naturalHeight || 300;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0);
                            resolve(canvas.toDataURL('image/jpeg', 0.92));
                        } catch (err) { reject(err); }
                    };
                    img.onerror = () => { clearTimeout(timeout); reject(new Error('Image load error')); };
                    img.src = url;
                });
                return dataUrl;
            } catch (e) {
                return '';
            }
        }

        // Descargar Entrada PDF individual manteniendo el diseño de Canva Studio
        async function downloadPosSalePdf(sale) {
            if (!sale) return;

            console.log('[CanvaStudio POS PDF] Starting PDF generation for sale:', sale);

            Swal.fire({
                title: '🎨 Generando Entrada PDF...',
                html: 'Compilando diseño oficial del boleto con Canva Studio...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            try {
                const eventTitle = "{{ addslashes($event->title) }}";
                const eventVenue = "{{ addslashes($event->venue_name ?? '') }}";
                const eventAddress = "{{ addslashes($event->address ?? '') }}";
                const eventDate = "{{ !empty($event->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('d/m/Y')) : '' }}";
                const eventTime = "{{ addslashes($event->event_time ?? '') }}";
                const logoWhite = "{{ asset($settings->logo_white ?? 'images/logo-white.png') }}";

                const template = @json($event->template ?? null) || { id: 1, name: 'Plantilla 1: Taquilla Clásica Oficial', bg_color: '#FFFFFF', strip_color: '#000000', positions: {} };
                
                let tplPositions = {};
                if (template.positions) {
                    let raw = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
                    if (Array.isArray(raw)) {
                        raw.forEach(p => { if (p && p.id) tplPositions[p.id] = p; });
                    } else if (typeof raw === 'object') {
                        tplPositions = raw;
                    }
                }

                console.log('[CanvaStudio POS PDF] Positions map:', tplPositions);

                const bgColor = template.bg_color || '#FFFFFF';
                const stripColor = template.strip_color || '#000000';

                const isPlantilla2 = (template.id == 2 || (template.name && template.name.includes('Plantilla 2')) || (template.category && template.category.includes('Logo Derecho')));
                const isPlantilla3 = (template.id == 3 || (template.name && template.name.includes('Plantilla 3')) || (template.category && template.category.includes('Panorámico')));
                const isPlantilla1 = (!isPlantilla2 && !isPlantilla3);

                function isColorDark(hexColor) {
                    if (!hexColor || hexColor.charAt(0) !== '#') return false;
                    const hex = hexColor.substring(1);
                    if (hex.length < 6) return false;
                    const r = parseInt(hex.substr(0, 2), 16) || 0;
                    const g = parseInt(hex.substr(2, 2), 16) || 0;
                    const b = parseInt(hex.substr(4, 2), 16) || 0;
const lum = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                    return lum < 0.55;
                }

                const isDarkBg = isColorDark(bgColor);
                const primaryTextColor = isDarkBg ? '#FFFFFF' : '#000000';
                const secondaryTextColor = isDarkBg ? '#E2E8F0' : '#1E293B';
                const mutedTextColor = isDarkBg ? '#94A3B8' : '#475569';

                function getFullAssetUrl(urlStr) {
                    if (!urlStr) return null;
                    if (urlStr.startsWith('data:')) return urlStr;
                    if (urlStr.startsWith('http://') || urlStr.startsWith('https://')) return urlStr;

                    let clean = urlStr.replace(/^\//, '');
                    if (clean.includes('storage/')) {
                        clean = 'storage/' + clean.split('storage/').pop();
                    } else if (clean.includes('images/')) {
                        clean = 'images/' + clean.split('images/').pop();
                    } else if (clean.startsWith('events/') || clean.startsWith('templates/')) {
                        clean = 'storage/' + clean;
                    }

                    return window.location.origin + '/' + clean;
                }

                // Precargar imágenes a Data URLs
                const logoSrc = (tplPositions.canvaElLogo && tplPositions.canvaElLogo.src) ? getFullAssetUrl(tplPositions.canvaElLogo.src) : logoWhite;
                const bannerSrc = (tplPositions.canvaElBanner && tplPositions.canvaElBanner.src) ? getFullAssetUrl(tplPositions.canvaElBanner.src) : null;
                const bgImgSrc = template.bg_image ? getFullAssetUrl(template.bg_image) : (tplPositions.canvaBgImage ? getFullAssetUrl(tplPositions.canvaBgImage) : null);
                const boletoSrc = getFullAssetUrl('/images/Boleto.jpg');

                const [logoDataUrl, bannerDataUrl, bgDataUrl, boletoDataUrl] = await Promise.all([
                    preloadPosImageAsDataUrl(logoSrc, 'logo'),
                    preloadPosImageAsDataUrl(bannerSrc, 'banner'),
                    bgImgSrc ? preloadPosImageAsDataUrl(bgImgSrc, 'bg') : Promise.resolve(''),
                    preloadPosImageAsDataUrl(boletoSrc, 'boleto')
                ]);

                const pTitle = tplPositions.canvaElTitle || {};
                const pZone = tplPositions.canvaElZone || {};
                const pPrice = tplPositions.canvaElPrice || {};
                const pBanner = tplPositions.canvaElBanner || {};
                const pBuyerName = tplPositions.canvaElBuyerName || tplPositions.canvaElBuyer || {};
                const pBuyerDni = tplPositions.canvaElBuyerDni || {};
                const pVenue = tplPositions.canvaElVenue || {};
                const pCity = tplPositions.canvaElCity || {};
                const pDate = tplPositions.canvaElDate || {};
                const pTime = tplPositions.canvaElTime || {};
                const pNum = tplPositions.canvaElTicketNumber || {};
                const pQR = tplPositions.canvaElQR || {};
                const pHash = tplPositions.canvaElHash || {};
                const pDisc = tplPositions.canvaElDisclaimer || {};
                const pLogo = tplPositions.canvaElLogo || {};

                function replaceDynamicHtmlValue(html, labelPattern, newValue) {
                    if (!html || typeof html !== 'string') return html;

                    // Patrón 1: <span ...>LABEL:</span><div><span ...>VALOR</span></div>
                    const divSpanRegex = new RegExp(`(${labelPattern}:?\\s*<\\/span>\\s*<div>\\s*<span[^>]*>)[^<]+(<\\/span>\\s*<\\/div>)`, 'i');
                    if (divSpanRegex.test(html)) {
                        return html.replace(divSpanRegex, `$1${newValue}$2`);
                    }

                    // Patrón 2: <span ...>LABEL:</span><div>VALOR</div>
                    const divRegex = new RegExp(`(${labelPattern}:?\\s*<\\/span>\\s*<div>)[^<]+(<\\/div>)`, 'i');
                    if (divRegex.test(html)) {
                        return html.replace(divRegex, `$1${newValue}$2`);
                    }

                    // Patrón 3: <span ...>LABEL:</span> VALOR
                    const inlineRegex = new RegExp(`(${labelPattern}:?\\s*<\\/span>\\s*)([^<\\s]+[^<]*)`, 'i');
                    if (inlineRegex.test(html)) {
                        return html.replace(inlineRegex, `$1${newValue}`);
                    }

                    // Patrón 4: Etiqueta directa en HTML (ej. <div>COMPRADOR: NOMBRE</div> o <div>DNI: 12345678</div>)
                    const directLabelRegex = new RegExp(`(${labelPattern}:?\\s*)([^<\\n\\r]+)`, 'i');
                    if (directLabelRegex.test(html)) {
                        return html.replace(directLabelRegex, `$1${newValue}`);
                    }

                    return html;
                }

                function renderCanvaStudioElement(id, posObj, defaultTop, defaultLeft, defaultWidth, defaultHeight, fallbackHtml, dynamicData = null) {
                    posObj = posObj || {};
                    if (posObj.hidden === true || posObj.display === 'none' || posObj.visible === false) {
                        return '';
                    }

                    const top = posObj.top !== undefined ? (typeof posObj.top === 'number' ? posObj.top + 'px' : posObj.top) : defaultTop;
                    const left = posObj.left !== undefined ? (typeof posObj.left === 'number' ? posObj.left + 'px' : posObj.left) : defaultLeft;
                    const width = posObj.width !== undefined ? (typeof posObj.width === 'number' ? posObj.width + 'px' : posObj.width) : (defaultWidth || '');
                    const height = posObj.height !== undefined ? (typeof posObj.height === 'number' ? posObj.height + 'px' : posObj.height) : (defaultHeight || '');
                    const rotate = (posObj.rotate && posObj.rotate !== '0') ? `transform: rotate(${posObj.rotate}deg);` : '';

                    const widthStyle = width ? `width: ${width};` : '';
                    const heightStyle = height ? `height: ${height};` : '';

                    const textAlignVal = posObj.textAlign || 'left';
                    let flexAlign = 'flex-start';
                    if (textAlignVal === 'center') flexAlign = 'center';
                    else if (textAlignVal === 'right') flexAlign = 'flex-end';

                    const font = posObj.fontFamily && posObj.fontFamily !== 'inherit' ? `font-family: ${posObj.fontFamily};` : '';
                    const fontSize = posObj.fontSize ? `font-size: ${posObj.fontSize};` : '';
                    const color = posObj.color ? `color: ${posObj.color};` : '';
                    const weight = posObj.fontWeight ? `font-weight: ${posObj.fontWeight};` : '';
                    const style = posObj.fontStyle ? `font-style: ${posObj.fontStyle};` : '';
                    const transform = posObj.textTransform && posObj.textTransform !== 'none' ? `text-transform: ${posObj.textTransform};` : '';

                    const containerStyle = `
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: ${flexAlign};
                        text-align: ${textAlignVal};
                        width: 100%;
                        height: 100%;
                        box-sizing: border-box;
                        padding: 3px 6px;
                        border-radius: 4px;
                        ${font}
                        ${fontSize}
                        ${color}
                        ${weight}
                        ${style}
                        ${transform}
                    `.replace(/\s+/g, ' ').trim();

                    const hasBadge = posObj.hasBadgeBg || false;
                    let badgeStyle = hasBadge ? 'background: rgba(255, 85, 0, 0.25); border: 1.5px solid #FF5500; border-radius: 8px;' : '';
                    if (posObj.backgroundColor && posObj.backgroundColor !== 'transparent') {
                        badgeStyle += ` background-color: ${posObj.backgroundColor}; border-radius: 8px;`;
                    }

                    let finalHtml = fallbackHtml;
                    if (posObj.html && typeof posObj.html === 'string' && posObj.html.trim().length > 0) {
                        finalHtml = posObj.html;
                        if (dynamicData) {
                            if (id === 'canvaElZone' && dynamicData.zoneName) {
                                finalHtml = replaceDynamicHtmlValue(finalHtml, 'ZONA', dynamicData.zoneName);
                            } else if (id === 'canvaElPrice' && dynamicData.price) {
                                finalHtml = replaceDynamicHtmlValue(finalHtml, 'PRECIO', 'S/ ' + dynamicData.price);
                            } else if ((id === 'canvaElBuyerName' || id === 'canvaElBuyer') && dynamicData.buyerName) {
                                finalHtml = replaceDynamicHtmlValue(finalHtml, 'Comprador', dynamicData.buyerName);
                            } else if (id === 'canvaElBuyerDni' && dynamicData.buyerDni) {
                                finalHtml = replaceDynamicHtmlValue(finalHtml, 'DNI', dynamicData.buyerDni);
                            } else if (id === 'canvaElDate' && dynamicData.date) {
                                finalHtml = replaceDynamicHtmlValue(finalHtml, 'FECHA', dynamicData.date);
                            } else if (id === 'canvaElTime' && dynamicData.time) {
                                finalHtml = replaceDynamicHtmlValue(finalHtml, 'HORA', dynamicData.time);
                            } else if (id === 'canvaElTicketNumber' && dynamicData.ticketNum) {
                                finalHtml = finalHtml.replace(/N[°º]\s*\d+/gi, dynamicData.ticketNum);
                            } else if (id === 'canvaElHash' && dynamicData.hash) {
                                finalHtml = finalHtml.replace(/[A-Z0-9]{8,12}/gi, dynamicData.hash);
                            }
                        }
                        if (posObj.textAlign) {
                            finalHtml = finalHtml.replace(/text-align:\s*(center|right|left|justify);?/gi, `text-align: ${textAlignVal};`);
                        }
                    }

                    return `
                        <div class="canva-drag-element" id="${id}" style="position: absolute; top: ${top}; left: ${left}; ${widthStyle} ${heightStyle} z-index: 5; ${rotate} box-sizing: border-box;">
                            <div class="canva-drag-box-container ${hasBadge ? 'has-badge-bg' : ''}" style="${badgeStyle} ${containerStyle}">
                                ${finalHtml}
                            </div>
                        </div>
                    `;
                }

                // Renderizar etiquetas personalizadas adicionales (canvaCustomTag_...)
                let customTagsHtml = '';
                Object.keys(tplPositions).forEach(key => {
                    if ((key.startsWith('canvaCustomTag_') || tplPositions[key].isCustomTag) && !tplPositions[key].hidden && tplPositions[key].display !== 'none' && tplPositions[key].visible !== false) {
                        const p = tplPositions[key];
                        const tagText = p.text || (p.html ? p.html.replace(/<[^>]*>/g, '').trim() : 'Etiqueta');
                        const tagContent = p.html || tagText;
                        customTagsHtml += renderCanvaStudioElement(key, p, '40px', '120px', p.width, p.height, tagContent);
                    }
                });

                const logoHtml = renderCanvaStudioElement('canvaElLogo', pLogo, '15px', '25px', '', '36px', (logoDataUrl || logoSrc) ? `<img src="${logoDataUrl || logoSrc}" style="height: 100%; width: auto; object-fit: contain; filter: drop-shadow(0 0 8px rgba(255,85,0,0.6)); display: block;">` : '');
                const bannerHtml = renderCanvaStudioElement('canvaElBanner', pBanner, '15px', '340px', '250px', '110px', (bannerDataUrl || bannerSrc) ? `<img src="${bannerDataUrl || bannerSrc}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px; display: block;">` : '');
                const titleHtml = renderCanvaStudioElement('canvaElTitle', pTitle, '55px', '25px', '380px', '', `<h2 style="font-size: ${pTitle.fontSize || '1.15rem'}; font-weight: ${pTitle.fontWeight || '900'}; color: ${pTitle.color || primaryTextColor}; margin: 0; line-height: 1.15; text-align: ${pTitle.textAlign || 'left'};">${eventTitle}</h2>`);
                const zoneHtml = renderCanvaStudioElement('canvaElZone', pZone, '95px', '25px', '', '', `<span style="font-size: ${pZone.fontSize || '0.925rem'}; font-weight: ${pZone.fontWeight || '800'}; color: ${pZone.color || '#1E293B'}; text-transform: uppercase;">ZONA: ${sale.zone_name}</span>`, { zoneName: sale.zone_name });
                
                const unitPriceVal = parseFloat(sale.unit_price || sale.total_amount).toFixed(2);
                const priceHtml = renderCanvaStudioElement('canvaElPrice', pPrice, '95px', '240px', '', '', `<div style="text-align: ${pPrice.textAlign || 'left'}; line-height: 1.15;"><span style="font-size: 0.75rem; font-weight: 900; color: ${pPrice.color || primaryTextColor}; display: block;">PRECIO:</span><span style="font-size: ${pPrice.fontSize || '1.3rem'}; font-weight: 900; color: ${pPrice.color || primaryTextColor}; display: block; margin-top: 2px;">S/ ${unitPriceVal}</span></div>`, { price: unitPriceVal });
                
                const venueHtml = renderCanvaStudioElement('canvaElVenue', pVenue, '200px', '25px', '', '', `<div style="display: flex; flex-direction: column; font-size: 0.8rem; color: ${pVenue.color || primaryTextColor}; text-align: ${pVenue.textAlign || 'left'};"><span style="font-weight: 900; font-size: 0.95rem; display: block;">${eventVenue}</span>${eventAddress ? `<span style="font-size: 0.8rem; font-weight: 700; color: ${mutedTextColor}; display: block; margin-top: 2px;">${eventAddress}</span>` : ''}<span style="font-weight: 900; font-size: 1rem; color: #FF5500; display: block; margin-top: 2px;">${eventDate} / ${eventTime}</span></div>`);
                const cityHtml = renderCanvaStudioElement('canvaElCity', pCity, '225px', '25px', '', '', eventAddress ? `<span style="font-size: 0.8rem; font-weight: 700; color: ${mutedTextColor};">${eventAddress}</span>` : '');
                const dateHtml = renderCanvaStudioElement('canvaElDate', pDate, '260px', '25px', '', '', `<span style="font-weight: 900; font-size: 0.9rem; color: ${pDate.color || primaryTextColor};">FECHA: ${eventDate}</span>`, { date: eventDate });
                const timeHtml = renderCanvaStudioElement('canvaElTime', pTime, '260px', '220px', '', '', `<span style="font-weight: 900; font-size: 0.9rem; color: ${pTime.color || primaryTextColor};">HORA: ${eventTime}</span>`, { time: eventTime });
                
                const buyerNameHtml = renderCanvaStudioElement('canvaElBuyerName', pBuyerName, '140px', '25px', '', '', `<div style="display: flex; flex-direction: column; font-size: ${pBuyerName.fontSize || '0.8rem'}; color: ${pBuyerName.color || primaryTextColor};"><span style="font-size: 0.725rem; color: ${mutedTextColor};">Comprador:</span><span style="font-weight: 900; font-size: 0.95rem; text-transform: uppercase;">${sale.buyer_name ? sale.buyer_name : 'CLIENTE VARIOS'}</span></div>`, { buyerName: sale.buyer_name || 'CLIENTE VARIOS' });
                const buyerDniHtml = renderCanvaStudioElement('canvaElBuyerDni', pBuyerDni, '165px', '25px', '', '', `<span style="font-weight: 800; font-size: ${pBuyerDni.fontSize || '0.825rem'}; color: ${pBuyerDni.color || primaryTextColor};">DNI: ${sale.buyer_dni ? sale.buyer_dni : '00000000'}</span>`, { buyerDni: sale.buyer_dni || '00000000' });

                const ticketNumStr = 'N° ' + String(sale.id || 1).padStart(5, '0');

                // Hash único aleatorio de 10 dígitos / caracteres guardado
                let hashVal = sale.validation_hash 
                    || (sale.tickets_data && sale.tickets_data[0] ? sale.tickets_data[0].validation_hash : null);

                if (!hashVal || hashVal.length !== 10) {
                    hashVal = 'VG' + Math.random().toString(36).substring(2, 10).toUpperCase();
                    if (hashVal.length > 10) hashVal = hashVal.substring(0, 10);
                    while (hashVal.length < 10) hashVal += 'X';
                }

                // QR como token encriptado exclusivo para el scanner móvil del evento (no legible por apps externas)
                const qrPayload = (sale.tickets_data && sale.tickets_data[0] && sale.tickets_data[0].qr_payload)
                    ? sale.tickets_data[0].qr_payload
                    : (sale.qr_payload || `VGENC:${hashVal}`);
                const qrDataUrl = generateQrBase64(qrPayload);

                const ticketNumberHtml = renderCanvaStudioElement('canvaElTicketNumber', pNum, '15px', '660px', '', '', `<span style="font-size: ${pNum.fontSize || '1.2rem'}; font-weight: 900; color: ${pNum.color || primaryTextColor}; font-family: var(--font-heading, sans-serif); letter-spacing: 0.5px; display: inline-block;">${ticketNumStr}</span>`, { ticketNum: ticketNumStr });

                const qrBoxHtml = renderCanvaStudioElement('canvaElQR', pQR, '55px', '635px', '95px', '95px', `<div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1.5px solid #E2E8F0; width: 100%; height: 100%; box-sizing: border-box; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><img src="${qrDataUrl}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px;" alt="QR Code" /></div>`);

                const hashHtml = renderCanvaStudioElement('canvaElHash', pHash, '175px', '645px', '', '', `<span style="font-family: monospace; font-size: ${pHash.fontSize || '0.85rem'}; font-weight: 800; color: ${pHash.color || secondaryTextColor}; letter-spacing: 1.5px; display: inline-block;">${hashVal}</span>`, { hash: hashVal });

                const disclaimerHtml = renderCanvaStudioElement('canvaElDisclaimer', pDisc, '245px', '570px', '190px', '', `<div style="border-top: 1.5px solid #CBD5E1; padding-top: 0.25rem;"><p style="font-size: ${pDisc.fontSize || '0.625rem'}; font-weight: 700; color: ${pDisc.color || mutedTextColor}; line-height: 1.2; margin: 0; text-align: center;">La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.</p></div>`);

                let bgImgHtml = '';
                if (bgDataUrl || bgImgSrc) {
                    bgImgHtml = `<div style="position: absolute; inset: 0; background-image: url('${bgDataUrl || bgImgSrc}'); background-size: cover; background-position: center; z-index: 0; pointer-events: none;"></div>`;
                }

                const ticketsList = (sale.tickets_data && Array.isArray(sale.tickets_data) && sale.tickets_data.length > 0)
                    ? sale.tickets_data
                    : Array.from({ length: parseInt(sale.quantity || 1, 10) }, (_, i) => ({
                        ticket_code: `TK-${sale.receipt_number}-${i + 1}`,
                        ticket_number: i + 1,
                        zone: sale.zone_name,
                        price: sale.unit_price,
                        validation_hash: null,
                        qr_payload: null
                    }));

                const jsPdfObj = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF || null);
                let pdf = null;
                if (jsPdfObj) {
                    pdf = new jsPdfObj({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });
                }

                for (let i = 0; i < ticketsList.length; i++) {
                    const tItem = ticketsList[i];
                    let numSeq = tItem.ticket_number || (sale.id ? (sale.id + i) : (i + 1));
                    if (typeof numSeq === 'string') {
                        numSeq = parseInt(numSeq.replace(/[^0-9]/g, ''), 10) || (i + 1);
                    }
                    const ticketNumStr = 'N° ' + String(numSeq).padStart(5, '0');

                    let hashVal = tItem.validation_hash || sale.validation_hash;
                    if (!hashVal || hashVal.length !== 10) {
                        hashVal = 'VG' + Math.random().toString(36).substring(2, 10).toUpperCase();
                        if (hashVal.length > 10) hashVal = hashVal.substring(0, 10);
                        while (hashVal.length < 10) hashVal += 'X';
                    }

                    const qrPayload = tItem.qr_payload || sale.qr_payload || `VGENC:${hashVal}`;
                    const qrDataUrl = generateQrBase64(qrPayload);

                    const ticketNumberHtml = renderCanvaStudioElement('canvaElTicketNumber', pNum, '15px', '660px', '', '', `<span style="font-size: ${pNum.fontSize || '1.2rem'}; font-weight: 900; color: ${pNum.color || primaryTextColor}; font-family: var(--font-heading, sans-serif); letter-spacing: 0.5px; display: inline-block;">${ticketNumStr}</span>`, { ticketNum: ticketNumStr });
                    const qrBoxHtml = renderCanvaStudioElement('canvaElQR', pQR, '55px', '635px', '95px', '95px', `<div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1.5px solid #E2E8F0; width: 100%; height: 100%; box-sizing: border-box; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><img src="${qrDataUrl}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px;" alt="QR Code" /></div>`);
                    const hashHtml = renderCanvaStudioElement('canvaElHash', pHash, '175px', '645px', '', '', `<span style="font-family: monospace; font-size: ${pHash.fontSize || '0.85rem'}; font-weight: 800; color: ${pHash.color || secondaryTextColor}; letter-spacing: 1.5px; display: inline-block;">${hashVal}</span>`, { hash: hashVal });

                    const pdfContainer = document.createElement('div');
                    pdfContainer.className = 'posPdfSingleCanvas';
                    pdfContainer.style.position = 'fixed';
                    pdfContainer.style.left = '-9999px';
                    pdfContainer.style.top = '0';
                    pdfContainer.style.width = '794px';
                    pdfContainer.style.height = '1123px';
                    pdfContainer.style.zIndex = '999999';
                    pdfContainer.style.backgroundImage = `url('${boletoDataUrl || boletoSrc}')`;
                    pdfContainer.style.backgroundSize = '100% 100%';
                    pdfContainer.style.backgroundPosition = 'center';
                    pdfContainer.style.fontFamily = "'Plus Jakarta Sans', sans-serif";
                    pdfContainer.style.boxSizing = 'border-box';
                    pdfContainer.style.overflow = 'hidden';

                    pdfContainer.innerHTML = `
                        <div class="ticket-canvas-inner" style="width: 771px; height: 370px; position: absolute; top: 12px; left: 11.5px; background: ${bgColor}; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; border-radius: 18px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); box-sizing: border-box;">
                            ${bgImgHtml}
                            
                            <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;" class="canva-main-area">
                                ${logoHtml}
                                ${bannerHtml}
                                ${titleHtml}
                                ${zoneHtml}
                                ${priceHtml}
                                ${venueHtml}
                                ${cityHtml}
                                ${dateHtml}
                                ${timeHtml}
                                ${buyerNameHtml}
                                ${buyerDniHtml}
                                ${ticketNumberHtml}
                                ${qrBoxHtml}
                                ${hashHtml}
                                ${disclaimerHtml}
                                ${customTagsHtml}
                            </div>
                        </div>
                    `;

                    document.body.appendChild(pdfContainer);

                    if (document.fonts && document.fonts.ready) {
                        await document.fonts.ready;
                    }
                    await new Promise(r => setTimeout(r, 200));

                    console.log(`[CanvaStudio POS PDF] Rendering A4 page ${i + 1}/${ticketsList.length}...`);
                    const canvas = await html2canvas(pdfContainer, {
                        scale: 2.5,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#FFFFFF',
                        logging: false
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.95);
                    pdfContainer.remove();

                    if (pdf) {
                        if (i > 0) {
                            pdf.addPage('a4', 'portrait');
                        }
                        pdf.addImage(imgData, 'JPEG', 0, 0, 210, 297);
                    }
                }

                if (pdf) {
                    pdf.save(`Entradas_VIRTUAL_${sale.receipt_number}.pdf`);
                }

                Swal.fire({
                    title: '📥 PDF de Entradas Generado Exitosamente',
                    text: `Se ha generado el PDF con ${ticketsList.length} hoja(s) A4 para la venta N° ${sale.receipt_number}.`,
                    icon: 'success',
                    confirmButtonColor: '#06B6D4',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            } catch (err) {
                console.error('Error generando PDF de entrada:', err);
                const el = document.getElementById('posPdfSingleCanvas');
                if (el) el.remove();

                Swal.fire({
                    title: 'Inconveniente con el PDF',
                    text: 'No se pudo procesar la entrada: ' + (err.message || 'error de renderizado'),
                    icon: 'error',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }
        }

        // Reimprimir recibo desde la tabla
        function reprintReceipt(sale) {
            const eventData = {
                title: "{{ $event->title }}",
                venue_name: "{{ $event->venue_name ?? '' }}",
                address: "{{ $event->address ?? '' }}",
                event_date: "{{ !empty($event->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('d/m/Y')) : '' }}",
                event_time: "{{ $event->event_time ?? '' }}"
            };

            const receiptData = {
                receipt_number: sale.receipt_number,
                created_at_formatted: sale.created_at ? sale.created_at : 'Hoy',
                buyer_name: sale.buyer_name,
                buyer_dni: sale.buyer_dni,
                zone_name: sale.zone_name,
                quantity: sale.quantity,
                unit_price_formatted: `S/ ${parseFloat(sale.unit_price).toFixed(2)}`,
                total_amount_formatted: `S/ ${parseFloat(sale.total_amount).toFixed(2)}`,
                payment_method: sale.payment_method,
                amount_paid_formatted: `S/ ${parseFloat(sale.amount_paid).toFixed(2)}`,
                change_amount_formatted: `S/ ${parseFloat(sale.change_amount).toFixed(2)}`,
                tickets: sale.tickets_data || []
            };

            printThermalReceipt(receiptData, eventData);
        }

        // Enviar nueva venta (REACTIVO EN VIVO SIN RECARGAR LA PÁGINA)
        function handlePosSaleSubmit(e) {
            e.preventDefault();

            const zoneName = document.getElementById('pos_zone_select')?.value || selectedZoneName;
            const quantity = parseInt(document.getElementById('pos_quantity').value, 10);
            const buyerName = document.getElementById('pos_buyer_name').value.trim();
            const buyerDni = document.getElementById('pos_buyer_dni').value.trim();
            const buyerPhone = document.getElementById('pos_buyer_phone').value.trim();
            const paymentMethod = document.getElementById('pos_payment_method').value;
            const amountPaid = parseFloat(document.getElementById('pos_amount_paid').value) || currentTotalToPay;

            if (paymentMethod === 'Efectivo' && amountPaid < currentTotalToPay) {
                Swal.fire({
                    title: 'Monto Insuficiente',
                    text: `El cliente debe entregar al menos S/ ${currentTotalToPay.toFixed(2)}. Falta S/ ${(currentTotalToPay - amountPaid).toFixed(2)}.`,
                    icon: 'warning',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            const btnSubmit = document.getElementById('btnSubmitPosSale');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.textContent = '⏳ Procesando venta y emitiendo boletos...';
            }

            const payload = {
                zone_name: zoneName,
                quantity: quantity,
                buyer_name: buyerName,
                buyer_dni: buyerDni,
                buyer_phone: buyerPhone,
                payment_method: paymentMethod,
                amount_paid: amountPaid
            };

            fetch("{{ route('web.box_office.store_sale', $event->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = '🧾 Confirmar Venta & Imprimir Recibo';
                }

                if (data.success) {
                    // 1. Cerrar Modal POS y resetear formulario
                    closePosSaleModal();
                    document.getElementById('posSaleForm')?.reset();
                    const chkAnon = document.getElementById('chkAnonymousBuyer');
                    if (chkAnon) {
                        chkAnon.checked = false;
                        toggleAnonymousBuyer(chkAnon);
                    }

                    // 2. Disparar impresión térmica inmediata
                    printThermalReceipt(data.receipt, data.event);

                    // 3. Insertar nueva venta en la tabla reactiva en vivo
                    const emptyRow = document.getElementById('emptySalesRow');
                    if (emptyRow) emptyRow.remove();

                    const tableBody = document.getElementById('salesTableBody');
                    if (tableBody) {
                        const newRow = document.createElement('tr');
                        newRow.className = 'sale-row-item';
                        newRow.setAttribute('data-sale-id', data.sale.id);
                        
                        let paymentBadge = `<span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">💵 Efectivo</span>`;
                        if (data.sale.payment_method === 'Yape') {
                            paymentBadge = `<span class="dash-badge-custom badge-purple" style="font-size: 0.75rem; background: rgba(168, 85, 247, 0.15); color: #A855F7; border: 1px solid rgba(168, 85, 247, 0.3);">📱 Yape</span>`;
                        } else if (data.sale.payment_method === 'Plin') {
                            paymentBadge = `<span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; color: #00F0FF; background: rgba(0, 240, 255, 0.15); border: 1px solid rgba(0, 240, 255, 0.3);">🟣 Plin</span>`;
                        } else if (data.sale.payment_method !== 'Efectivo') {
                            paymentBadge = `<span class="dash-badge-custom badge-blue" style="font-size: 0.75rem;">💳 ${data.sale.payment_method}</span>`;
                        }

                        const saleDataEscaped = JSON.stringify(data.sale).replace(/"/g, '&quot;');

                        newRow.innerHTML = `
                            <td>
                                <span style="font-weight: 800; color: var(--color-primary-orange); font-family: monospace; font-size: 0.95rem;">
                                    ${data.receipt.receipt_number}
                                </span>
                            </td>
                            <td>
                                <span style="color: #94A3B8; font-size: 0.825rem; font-weight: 600;">
                                    ${data.receipt.created_at_formatted}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong style="color: #FFFFFF; font-size: 0.925rem; display: block;">${data.sale.buyer_name}</strong>
                                    <small style="color: #94A3B8; font-size: 0.775rem;">DNI: ${data.sale.buyer_dni}</small>
                                </div>
                            </td>
                            <td>
                                <span class="dash-badge-custom badge-blue" style="font-size: 0.75rem;">
                                    ${data.sale.zone_name}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-weight: 900; font-size: 1rem; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 8px;">
                                    ${data.sale.quantity}
                                </span>
                            </td>
                            <td>
                                <strong style="color: #10B981; font-size: 1rem; font-weight: 900;">
                                    ${data.receipt.total_amount_formatted}
                                </strong>
                            </td>
                            <td>
                                ${paymentBadge}
                            </td>
                            <td>
                                <div style="font-size: 0.8rem; color: #94A3B8;">
                                    <span>Pagó: ${data.receipt.amount_paid_formatted}</span><br>
                                    <strong style="color: #10B981;">Vuelto: ${data.receipt.change_amount_formatted}</strong>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick='reprintReceipt(${saleDataEscaped})' title="Reimprimir Recibo Térmico" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: 1px solid rgba(255,85,0,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3); cursor: pointer;">
                                        <span>🧾</span>
                                        <span>Recibo</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick='downloadPosSalePdf(${saleDataEscaped})' title="Descargar Entrada PDF" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: 1px solid rgba(6,182,212,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); cursor: pointer;">
                                        <span>🎟️</span>
                                        <span>Entrada PDF</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        tableBody.prepend(newRow);
                    }

                    // 4. Actualizar KPIs en vivo
                    if (data.metrics) {
                        const totalRevEl = document.getElementById('kpiTotalRevenue');
                        const salesCountEl = document.getElementById('kpiSalesCount');
                        const ticketsSoldEl = document.getElementById('kpiTicketsSold');
                        const remStockEl = document.getElementById('kpiRemainingStock');
                        const cashRevEl = document.getElementById('kpiCashRevenue');
                        const digRevEl = document.getElementById('kpiDigitalRevenue');

                        if (totalRevEl) totalRevEl.textContent = data.metrics.total_revenue;
                        if (salesCountEl) salesCountEl.textContent = `${data.metrics.tickets_sold} ventas registradas`;
                        if (ticketsSoldEl) ticketsSoldEl.innerHTML = `${data.metrics.tickets_sold} <span style="font-size: 0.9rem; color: #94A3B8;">/ ${data.metrics.remaining_stock + data.metrics.tickets_sold}</span>`;
                        if (remStockEl) remStockEl.textContent = data.metrics.remaining_stock;
                        if (cashRevEl) cashRevEl.textContent = data.metrics.cash_revenue;
                        if (digRevEl) digRevEl.textContent = data.metrics.digital_revenue;

                        // Actualizar cards de stock en la página y en el modal en tiempo real
                        if (data.metrics.zones) {
                            data.metrics.zones.forEach(z => {
                                // 1. Actualizar tarjeta de sector en la página principal
                                const pageCard = document.querySelector(`.pos-zone-card[data-zone-name="${z.name}"]`);
                                if (pageCard) {
                                    const badge = pageCard.querySelector('.dash-badge-custom');
                                    if (badge) {
                                        if (z.available > 0) {
                                            badge.className = 'dash-badge-custom badge-green';
                                            badge.textContent = `📦 Stock: ${z.available} libres`;
                                            pageCard.style.borderColor = '';
                                        } else {
                                            badge.className = 'dash-badge-custom badge-red';
                                            badge.textContent = '🚫 AGOTADO';
                                            pageCard.style.borderColor = 'rgba(239, 68, 68, 0.4)';
                                        }
                                    }

                                    const soldEl = pageCard.querySelector('.zone-sold-count');
                                    if (soldEl) soldEl.textContent = z.sold;

                                    const totalCapEl = pageCard.querySelector('.zone-total-cap');
                                    if (totalCapEl) totalCapEl.textContent = z.capacity;

                                    const availEl = pageCard.querySelector('.zone-available-count');
                                    if (availEl) {
                                        availEl.textContent = z.available;
                                        if (availEl.parentElement) {
                                            availEl.parentElement.style.color = z.available > 0 ? '#10B981' : '#EF4444';
                                        }
                                    }

                                    const progressEl = pageCard.querySelector('.zone-progress-bar');
                                    if (progressEl) {
                                        progressEl.style.width = `${z.percentage}%`;
                                    }
                                }

                                // 2. Actualizar tarjeta de sector en el modal POS
                                const zoneModalCard = document.querySelector(`.zone-card-item[data-name="${z.name}"]`);
                                if (zoneModalCard) {
                                    zoneModalCard.setAttribute('data-available', z.available);
                                    const modalBadge = zoneModalCard.querySelector('.zone-stock-badge');
                                    if (modalBadge) {
                                        if (z.available > 0) {
                                            modalBadge.className = 'zone-stock-badge available';
                                            modalBadge.textContent = `📦 Stock: ${z.available} libres`;
                                            zoneModalCard.classList.remove('disabled');
                                        } else {
                                            modalBadge.className = 'zone-stock-badge sold-out';
                                            modalBadge.textContent = '🚫 AGOTADO';
                                            zoneModalCard.classList.add('disabled');
                                            zoneModalCard.classList.remove('active');
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // 5. Modal Interactivo de Confirmación (Recibo + Entrada PDF)
                    Swal.fire({
                        title: `🎉 ¡Venta Registrada Exitosamente!`,
                        html: `
                            <div style="font-size: 0.95rem; color: #CBD5E1; margin-bottom: 1.25rem;">
                                N° Recibo: <b style="color: #FF5500; font-family: monospace;">${data.receipt.receipt_number}</b> | Monto: <b style="color: #10B981;">${data.receipt.total_amount_formatted}</b>
                            </div>
                            <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                                <button type="button" id="btnAlertPrintReceipt" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(255,85,0,0.3);">
                                    <span>🧾</span> Imprimir Recibo
                                </button>
                                <button type="button" id="btnAlertDownloadPdf" class="btn btn-secondary btn-sm" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(6,182,212,0.3);">
                                    <span>🎟️</span> Descargar Entrada PDF
                                </button>
                            </div>
                        `,
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Cerrar',
                        background: '#14141E',
                        color: '#FFFFFF',
                        didOpen: () => {
                            const btnR = document.getElementById('btnAlertPrintReceipt');
                            if (btnR) btnR.addEventListener('click', () => {
                                printThermalReceipt(data.receipt, data.event);
                            });
                            const btnP = document.getElementById('btnAlertDownloadPdf');
                            if (btnP) btnP.addEventListener('click', () => {
                                downloadPosSalePdf(data.sale);
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error al procesar venta',
                        text: data.message || 'No se pudo completar la transacción.',
                        icon: 'error',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            })
            .catch(err => {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = '🧾 Confirmar Venta & Imprimir Recibo';
                }
                Swal.fire({
                    title: 'Error de Red',
                    text: err.message,
                    icon: 'error',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            });
        }

        // Atajos de teclado: F1 para abrir POS rápido
        window.addEventListener('keydown', function (e) {
            if (e.key === 'F1') {
                e.preventDefault();
                openPosSaleModal();
            } else if (e.key === 'Escape') {
                closePosSaleModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Cerrar modal al hacer clic en el backdrop overlay
            const posModalOverlay = document.getElementById('posSaleModal');
            if (posModalOverlay) {
                posModalOverlay.addEventListener('click', function (e) {
                    if (e.target === this) closePosSaleModal();
                });
            }

            // Buscador en la tabla de ventas
            const searchInput = document.getElementById('salesTableSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.sale-row-item');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }

            // Theme Toggle
            const themeBtn = document.getElementById('btnThemeToggle');
            const themeIcon = document.getElementById('themeToggleIcon');
            const dashRoot = document.querySelector('.dashboard-root-wrapper');

            const savedTheme = localStorage.getItem('vivego_dashboard_theme');
            if (savedTheme === 'light' && dashRoot) {
                dashRoot.classList.add('theme-light');
                if (themeIcon) themeIcon.textContent = '🌙';
            }

            if (themeBtn && dashRoot) {
                themeBtn.addEventListener('click', function () {
                    dashRoot.classList.toggle('theme-light');
                    const isLight = dashRoot.classList.contains('theme-light');
                    if (themeIcon) themeIcon.textContent = isLight ? '🌙' : '☀️';
                    localStorage.setItem('vivego_dashboard_theme', isLight ? 'light' : 'dark');
                });
            }
        });
    </script>
@endpush
