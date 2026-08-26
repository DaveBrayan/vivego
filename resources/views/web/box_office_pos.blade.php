@extends('layouts.app')

@section('title', 'Punto de Venta POS - ' . $event->title . ' | Vive Go')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Anton&family=Bebas+Neue&family=Caveat:wght@600;700&family=Cinzel:wght@600;800&family=Comfortaa:wght@600;700&family=Dancing+Script:wght@600;700&family=Fira+Sans:ital,wght@0,400;0,700;1,400&family=Great+Vibes&family=Inter:wght@400;600;800;900&family=Lato:wght@400;700;900&family=Lobster&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Monoton&family=Montserrat:wght@400;700;900&family=Nunito:wght@400;700;900&family=Open+Sans:wght@400;700&family=Oswald:wght@500;700&family=Outfit:wght@400;700;900&family=Pacifico&family=Permanent+Marker&family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Poppins:wght@400;700;900&family=Raleway:wght@400;700;900&family=Righteous&family=Roboto:wght@400;700;900&family=Rubik:wght@400;700;900&family=Satisfy&family=Space+Grotesk:wght@500;700&family=Syne:wght@700;800&family=Work+Sans:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        .font-lato { font-family: 'Lato', sans-serif !important; }
        .font-montserrat { font-family: 'Montserrat', sans-serif !important; }
        .font-opensans { font-family: 'Open Sans', sans-serif !important; }
        .font-roboto { font-family: 'Roboto', sans-serif !important; }
        .font-inter { font-family: 'Inter', sans-serif !important; }
        .font-poppins { font-family: 'Poppins', sans-serif !important; }
        .font-outfit { font-family: 'Outfit', sans-serif !important; }
        .font-raleway { font-family: 'Raleway', sans-serif !important; }
        .font-nunito { font-family: 'Nunito', sans-serif !important; }
        .font-rubik { font-family: 'Rubik', sans-serif !important; }
        .font-work-sans { font-family: 'Work Sans', sans-serif !important; }
        .font-oswald { font-family: 'Oswald', sans-serif !important; }
        .font-bebas { font-family: 'Bebas Neue', sans-serif !important; }
        .font-anton { font-family: 'Anton', sans-serif !important; }
        .font-syne { font-family: 'Syne', sans-serif !important; }
        .font-space-grotesk { font-family: 'Space Grotesk', sans-serif !important; }
        .font-righteous { font-family: 'Righteous', sans-serif !important; }
        .font-monoton { font-family: 'Monoton', sans-serif !important; }
        .font-merriweather { font-family: 'Merriweather', serif !important; }
        .font-playfair { font-family: 'Playfair Display', serif !important; }
        .font-cinzel { font-family: 'Cinzel', serif !important; }
        .font-abril { font-family: 'Abril Fatface', serif !important; }
        .font-dancing { font-family: 'Dancing Script', cursive !important; }
        .font-greatvibes { font-family: 'Great Vibes', cursive !important; }
        .font-pacifico { font-family: 'Pacifico', cursive !important; }
        .font-satisfy { font-family: 'Satisfy', cursive !important; }
        .font-caveat { font-family: 'Caveat', cursive !important; }
        .font-lobster { font-family: 'Lobster', cursive !important; }
        .font-comfortaa { font-family: 'Comfortaa', cursive !important; }

        .ticket-element-node p, 
        .ticket-element-node div, 
        .ticket-element-node span, 
        .ticket-element-node h1, 
        .ticket-element-node h2, 
        .ticket-element-node h3, 
        .ticket-element-node h4 {
            margin: 0 !important;
            padding: 0 !important;
            text-align: inherit !important;
            box-sizing: border-box !important;
        }

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
        .courtesy-zone-card:hover:not(.disabled) {
            background: rgba(16, 185, 129, 0.06) !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
        }
        .courtesy-zone-card.active {
            background: rgba(16, 185, 129, 0.12) !important;
            border-color: #10B981 !important;
            box-shadow: 0 0 18px rgba(16, 185, 129, 0.3) !important;
        }
        .courtesy-zone-card.active .zone-radio-indicator {
            border-color: #10B981 !important;
            background: #10B981 !important;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.6) !important;
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

        /* SELECTS Y DROPDOWNS EN MODO OSCURO */
        select.form-input-custom,
        select.form-select-custom,
        #pos_courtesy_note,
        #pos_zone_select {
            background-color: #14141E !important;
            color: #FFFFFF !important;
        }
        select.form-input-custom option,
        select.form-select-custom option,
        #pos_courtesy_note option,
        #pos_zone_select option {
            background-color: #14141E !important;
            color: #FFFFFF !important;
            padding: 10px 14px !important;
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

        /* BOTONES QUICK QUANTITY Y QUICK CASH */
        .pos-quick-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #CBD5E1;
            font-weight: 800;
            font-size: 0.85rem;
            border-radius: 10px;
            padding: 0.4rem 0.65rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 36px;
            box-sizing: border-box;
        }

        .pos-quick-btn:hover {
            background: rgba(255, 85, 0, 0.15);
            border-color: #FF5500;
            color: #FF5500;
        }

        .pos-quick-btn.active {
            background: var(--color-primary-orange, #FF5500) !important;
            border-color: var(--color-primary-orange, #FF5500) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(255, 85, 0, 0.4);
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
            #posSaleModal .admin-modal-card {
                padding: 1.25rem 0.9rem !important;
                width: 96% !important;
                max-width: 96% !important;
                border-radius: 20px !important;
                max-height: 94vh !important;
                margin: auto !important;
                box-sizing: border-box !important;
            }

            .pos-modal-two-columns {
                grid-template-columns: 1fr !important;
                gap: 1.15rem !important;
            }

            .zone-card-item {
                padding: 0.75rem 0.85rem !important;
                border-radius: 14px !important;
            }

            .zone-card-name {
                font-size: 0.875rem !important;
            }

            .zone-card-price {
                font-size: 1.05rem !important;
            }

            .pos-quick-qty-pills {
                display: grid !important;
                grid-template-columns: repeat(6, 1fr) !important;
                gap: 0.3rem !important;
                width: 100% !important;
            }

            .pos-quick-btn {
                width: 100% !important;
                min-width: 0 !important;
                height: 36px !important;
                padding: 0 !important;
                font-size: 0.8rem !important;
            }

            .pos-total-summary-card {
                padding: 0.85rem 1rem !important;
                border-radius: 14px !important;
            }

            #posTotalAmountDisplay {
                font-size: 1.6rem !important;
            }

            .pos-modal-footer-actions {
                flex-direction: column-reverse !important;
                gap: 0.6rem !important;
            }

            .pos-modal-footer-actions .btn {
                width: 100% !important;
                padding: 0.85rem 1rem !important;
                justify-content: center !important;
                text-align: center !important;
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

                    <!-- BOTONES PRINCIPALES DE TAQUILLA (VENTA + CORTESÍA) -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                        <button type="button" class="btn btn-primary" onclick="openPosSaleModal()" style="font-size: 1rem; font-weight: 800; padding: 0.85rem 1.6rem; border-radius: 14px; box-shadow: 0 6px 22px rgba(255, 85, 0, 0.45); display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer;">
                            <span style="font-size: 1.25rem;">🛒</span>
                            <span>+ REGISTRAR VENTA (F1)</span>
                        </button>
                        <button type="button" class="btn" onclick="openPosCourtesyModal()" style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-size: 1rem; font-weight: 800; padding: 0.85rem 1.6rem; border-radius: 14px; box-shadow: 0 6px 22px rgba(16, 185, 129, 0.4); display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer; border: none; transition: all 0.2s ease;">
                            <span style="font-size: 1.25rem;">🎁</span>
                            <span>+ NUEVA CORTESÍA (F2)</span>
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
                    </div>

                    <!-- TABLA OFICIAL DE HISTORIAL DE VENTAS -->
                    <div class="dash-table-container">
                        <table class="dash-table" id="salesHistoryTable">
                            <thead>
                                <tr>
                                    <th>N° Recibo / Fecha</th>
                                    <th>Cliente</th>
                                    <th>Zona / Sector</th>
                                    <th style="text-align: center;">Cant.</th>
                                    <th>Total Cobrado</th>
                                    <th>Método de Pago</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody">
                                @forelse($sales as $sale)
                                    <tr class="sale-row-item" data-sale-id="{{ $sale->id }}">
                                        <td>
                                            <div>
                                                <span style="font-weight: 800; color: var(--color-primary-orange); font-family: monospace; font-size: 0.95rem; display: block;">
                                                    {{ $sale->receipt_number }}
                                                </span>
                                                <small style="color: #94A3B8; font-size: 0.775rem; font-weight: 600;">
                                                    {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : 'Hoy' }}
                                                </small>
                                            </div>
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
                                            @elseif($sale->payment_method === 'Cortesía' || $sale->payment_method === 'cortesia')
                                                @php
                                                    $isWebCourtesy = false;
                                                    $sellerNameLower = strtolower($sale->seller_name ?? '');
                                                    $tDataLower = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data ?? '[]', true);
                                                    $subMLower = strtolower($tDataLower['sub_method'] ?? '');
                                                    if (str_contains($sellerNameLower, 'web') || str_contains($subMLower, 'web')) {
                                                        $isWebCourtesy = true;
                                                    }
                                                @endphp
                                                @if($isWebCourtesy)
                                                    <span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; background: rgba(0, 240, 255, 0.15); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                        <span>🌐</span> <span>Cortesía Web</span>
                                                    </span>
                                                @else
                                                    <span class="dash-badge-custom badge-green" style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                        <span>🎁</span> <span>Cortesía Adm</span>
                                                    </span>
                                                @endif
                                            @elseif($sale->payment_method === 'Yape')
                                                <span class="dash-badge-custom badge-purple" style="font-size: 0.75rem; background: rgba(168, 85, 247, 0.15); color: #A855F7; border: 1px solid rgba(168, 85, 247, 0.3);">📱 Yape</span>
                                            @elseif($sale->payment_method === 'Plin')
                                                <span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; color: #00F0FF; background: rgba(0, 240, 255, 0.15); border: 1px solid rgba(0, 240, 255, 0.3);">🟣 Plin</span>
                                            @elseif(str_starts_with(strtolower($sale->payment_method), 'izipay') || $sale->payment_method === 'izipay_online' || $sale->payment_method === 'Izipay')
                                                <div style="display: inline-flex; flex-direction: column; align-items: flex-start; gap: 0.15rem;">
                                                    <span class="dash-badge-custom badge-blue" style="font-size: 0.75rem; font-weight: 800; background: rgba(0, 210, 196, 0.15); color: #00D2C4; border: 1px solid rgba(0, 210, 196, 0.35); padding: 0.2rem 0.55rem; border-radius: 6px;">
                                                        💳 Izipay
                                                    </span>
                                                    <small style="font-size: 0.7rem; color: #94A3B8; font-weight: 600;">
                                                        @php
                                                            $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                                                            $subM = $tData['sub_method'] ?? (str_contains(strtolower($sale->payment_method), 'qr') ? 'QR Yape / Plin' : 'Tarjeta');
                                                        @endphp
                                                        {{ $subM }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="dash-badge-custom badge-blue" style="font-size: 0.75rem;">💳 {{ $sale->payment_method }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="reprintReceipt({{ $sale->id }})" title="Reimprimir Recibo Térmico" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: 1px solid rgba(255,85,0,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3); cursor: pointer;">
                                                    <span>🧾</span>
                                                    <span>Recibo</span>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="downloadPosSalePdf({{ $sale->id }})" title="Descargar Entrada PDF" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: 1px solid rgba(6,182,212,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); cursor: pointer;">
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
            
            <div class="admin-modal-header" style="margin-bottom: 1.25rem; padding-bottom: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0; flex: 1;">
                    <div class="card-header-icon" style="width: 42px; height: 42px; background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange); display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.3rem; flex-shrink: 0;">🛒</div>
                    <div style="min-width: 0; flex: 1;">
                        <h3 class="card-header-title" style="font-size: 1.15rem; margin: 0; color: #FFFFFF; font-weight: 900; line-height: 1.25;">Nueva Venta de Taquilla (POS)</h3>
                        <p class="card-header-subtitle" style="margin: 0; font-size: 0.8rem; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $event->title }}</p>
                    </div>
                </div>
                <button type="button" class="admin-modal-close" onclick="closePosSaleModal()" style="font-size: 1.3rem; color: #94A3B8; background: transparent; border: none; cursor: pointer; flex-shrink: 0; padding: 0.25rem 0.5rem;" aria-label="Cerrar">✕</button>
            </div>

            <form id="posSaleForm" onsubmit="handlePosSaleSubmit(event)">
                <!-- GRID DE 2 COLUMNAS PRINCIPALES -->
                <div class="pos-modal-two-columns" style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 1.5rem; align-items: start; margin-bottom: 1.25rem;">
                    
                    <!-- COLUMNA 1: SELECCIÓN DE SECTORES / ZONAS EN CARDS + CANTIDAD + TOTAL A COBRAR -->
                    <div>
                        <!-- SECTORES / ZONAS EN CARDS INTERACTIVAS -->
                        <div class="form-group-custom" style="margin-bottom: 1.25rem;">
                            <label class="form-label-custom" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.85rem;">
                                <span>🎟️ Sector / Zona de Entrada <span class="required-star">*</span></span>
                                <small style="color: #94A3B8; font-weight: 600;">Haz clic para seleccionar</small>
                            </label>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.6rem;" id="zoneCardsContainer">
                                @foreach($zonesWithStats as $index => $z)
                                    <div class="zone-card-item {{ $index === 0 && $z['available'] > 0 ? 'active' : '' }} {{ $z['available'] <= 0 ? 'disabled' : '' }}"
                                         data-name="{{ $z['name'] }}"
                                         data-price="{{ $z['price'] }}"
                                         data-available="{{ $z['available'] }}"
                                         onclick="selectZoneCard('{{ addslashes($z['name']) }}', {{ $z['price'] }}, {{ $z['available'] }}, this)">
                                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.6rem;">
                                            <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
                                                <div class="zone-radio-indicator"></div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <strong class="zone-card-name" style="word-break: break-word;">{{ $z['name'] }}</strong>
                                                    <div style="margin-top: 0.15rem;">
                                                        @if($z['available'] > 0)
                                                            <span class="zone-stock-badge available">📦 Stock: {{ number_format($z['available']) }} libres</span>
                                                        @else
                                                            <span class="zone-stock-badge sold-out">🚫 AGOTADO</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="text-align: right; flex-shrink: 0;">
                                                <span class="zone-card-price" style="white-space: nowrap;">S/ {{ number_format($z['price'], 2) }}</span>
                                                <small style="display: block; font-size: 0.68rem; color: #94A3B8; white-space: nowrap;">por entrada</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Input oculto para guardar el sector seleccionado -->
                            <input type="hidden" id="pos_zone_select" value="{{ $zonesWithStats[0]['name'] ?? '' }}" required>
                        </div>

                        <!-- CANTIDAD DE ENTRADAS CON BOTONES NARANJAS Y QUICK PILLS -->
                        <div class="form-group-custom" style="margin-bottom: 1.25rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 1rem; border-radius: 18px;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <label class="form-label-custom" style="margin: 0; font-size: 0.85rem;">🎫 Cantidad de Entradas <span class="required-star">*</span></label>
                                <div class="pos-quick-qty-pills" style="display: flex; gap: 0.35rem; width: 100%;">
                                    <button type="button" class="pos-quick-btn active" id="btnQuickQty1" onclick="setPosQuantity(1)">1</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty2" onclick="setPosQuantity(2)">2</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty3" onclick="setPosQuantity(3)">3</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty4" onclick="setPosQuantity(4)">4</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty5" onclick="setPosQuantity(5)">5</button>
                                    <button type="button" class="pos-quick-btn" id="btnQuickQty10" onclick="setPosQuantity(10)">10</button>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.85rem; justify-content: center; margin-top: 0.5rem;">
                                <button type="button" class="pos-stepper-btn orange-btn" onclick="stepPosQuantity(-1)" title="Restar una entrada">-</button>
                                <input type="number" id="pos_quantity" class="form-input-custom pos-stepper-input" value="1" min="1" max="50" required oninput="calculatePosTotal()">
                                <button type="button" class="pos-stepper-btn orange-btn" onclick="stepPosQuantity(1)" title="Sumar una entrada">+</button>
                            </div>
                        </div>

                        <!-- TOTAL A PAGAR RESALTADO PRO MAX -->
                        <div class="pos-total-summary-card">
                            <div>
                                <span style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Total a Cobrar</span>
                                <small style="color: #FFFFFF; font-weight: 600; font-size: 0.85rem;" id="posUnitPriceDesc">1 entrada x S/ 0.00</small>
                            </div>
                            <div style="font-size: 1.85rem; font-weight: 900; color: #10B981; text-shadow: 0 2px 12px rgba(16, 185, 129, 0.3); text-align: right;" id="posTotalAmountDisplay">
                                S/ 0.00
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 2: DATOS DEL CLIENTE + MÉTODO DE PAGO + CALCULADORA DE VUELTO -->
                    <div>
                        <!-- DATOS DEL CLIENTE / COMPRADOR CON CHECKBOX SIN DATOS -->
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 1.15rem; border-radius: 18px; margin-bottom: 1.15rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                                <h4 style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>👤</span> <span>Datos del Comprador</span>
                                </h4>
                                <label style="display: flex; align-items: center; gap: 0.45rem; cursor: pointer; font-size: 0.75rem; font-weight: 800; color: var(--color-primary-orange); background: rgba(255,85,0,0.12); padding: 0.3rem 0.65rem; border-radius: 10px; border: 1px solid rgba(255,85,0,0.35); user-select: none;">
                                    <input type="checkbox" id="chkAnonymousBuyer" onchange="toggleAnonymousBuyer(this)" style="cursor: pointer; width: 15px; height: 15px; accent-color: #FF5500;">
                                    <span>Sin Datos (Venta Rápida)</span>
                                </label>
                            </div>

                            <!-- SELECTOR DESPLEGABLE CON BUSCADOR (PLEGADO POR DEFECTO) -->
                            <div class="pos-client-picker" id="pos_sale_client_picker_container" style="position: relative; margin-bottom: 0.85rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                    <label class="form-label-custom" style="margin: 0; font-size: 0.775rem; color: var(--color-primary-orange); display: flex; align-items: center; gap: 0.35rem; font-weight: 700;">
                                        <span>👥</span> <span>Seleccionar Cliente Registrado (Opcional)</span>
                                    </label>
                                    <span id="saleClientSelectedBadge" style="display: none; font-size: 0.7rem; font-weight: 800; color: var(--color-primary-orange); background: rgba(255,85,0,0.15); border: 1px solid rgba(255,85,0,0.3); padding: 0.15rem 0.5rem; border-radius: 8px;">
                                        ✓ Autocompletado
                                    </span>
                                </div>

                                <!-- Botón Desplegable (Cerrado por defecto) -->
                                <div style="display: flex; gap: 0.4rem; align-items: center;">
                                    <button type="button" 
                                            id="btnSaleClientDropdown" 
                                            onclick="toggleSaleClientDropdown(event)" 
                                            class="form-input-custom" 
                                            style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; text-align: left; cursor: pointer; background: rgba(255, 85, 0, 0.05); border: 1.5px solid rgba(255, 85, 0, 0.35); padding: 0.6rem 0.85rem; border-radius: 12px; color: #94A3B8; font-weight: 600; font-size: 0.825rem; width: 100%;">
                                        <span id="saleClientSelectedText" style="display: flex; align-items: center; gap: 0.45rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1;">
                                            <span>🔍</span> <span>-- Seleccionar de clientes registrados --</span>
                                        </span>
                                        <span id="saleClientArrow" style="font-size: 0.75rem; color: var(--color-primary-orange); transition: transform 0.2s ease;">▼</span>
                                    </button>
                                    <button type="button" 
                                            id="btnClearSaleClientSelection" 
                                            onclick="clearSaleClientSelection(event)" 
                                            title="Quitar cliente seleccionado" 
                                            style="display: none; height: 38px; padding: 0 0.65rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 10px; color: #EF4444; font-size: 0.8rem; font-weight: 800; cursor: pointer; align-items: center; gap: 0.25rem; flex-shrink: 0;">
                                        ✕
                                    </button>
                                </div>

                                <!-- Panel Flotante con BUSCADOR ADENTRO -->
                                <div id="saleClientDropdownPanel" 
                                     style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #14141E; border: 1.5px solid rgba(255, 85, 0, 0.45); border-radius: 14px; box-shadow: 0 16px 40px rgba(0,0,0,0.95); z-index: 1000; padding: 0.65rem;">
                                    
                                    <!-- Buscador dentro del desplegable -->
                                    <div style="position: relative; margin-bottom: 0.5rem;">
                                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-size: 0.85rem; color: var(--color-primary-orange); pointer-events: none;">🔎</span>
                                        <input type="text" 
                                               id="pos_sale_client_search" 
                                               class="form-input-custom" 
                                               placeholder="Buscar por Nombre, DNI, Correo..." 
                                               autocomplete="off"
                                               style="padding-left: 2.2rem; font-size: 0.825rem; background: rgba(255,255,255,0.06); border-color: rgba(255, 85, 0, 0.35); height: 36px;"
                                               oninput="filterSaleClients(this.value)">
                                    </div>

                                    <!-- Lista de Resultados -->
                                    <div id="saleClientDropdownList" style="max-height: 200px; overflow-y: auto;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.8rem;">
                                <label for="pos_buyer_dni" class="form-label-custom">DNI / Documento <span class="required-star" id="star_buyer_dni">*</span></label>
                                <input type="text" id="pos_buyer_dni" class="form-input-custom" placeholder="Ej: 72819203" required style="font-weight: 700; letter-spacing: 0.5px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.8rem;">
                                <label for="pos_buyer_name" class="form-label-custom">Nombre Completo <span class="required-star" id="star_buyer_name">*</span></label>
                                <input type="text" id="pos_buyer_name" class="form-input-custom" placeholder="Ej: Juan Pérez Morales" required style="font-weight: 600;">
                            </div>

                            <div class="form-group-custom">
                                <label for="pos_buyer_phone" class="form-label-custom">Teléfono / WhatsApp (Opcional)</label>
                                <input type="text" id="pos_buyer_phone" class="form-input-custom" placeholder="Ej: +51 987654321">
                            </div>
                        </div>

                        <!-- MÉTODO DE PAGO CON PILLS -->
                        <div class="form-group-custom" style="margin-bottom: 1.15rem;">
                            <label class="form-label-custom">Método de Pago <span class="required-star">*</span></label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 0.4rem;" id="paymentMethodsGroup">
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
                        <div id="cashCalculatorBox" style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 18px; padding: 1rem 0.85rem; margin-bottom: 1.15rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.45rem; margin-bottom: 0.65rem;">
                                <label class="form-label-custom" style="margin: 0; font-size: 0.85rem;">💵 Monto Recibido del Cliente <span class="required-star">*</span></label>
                                <div style="display: flex; gap: 0.3rem; flex-wrap: wrap; width: 100%;">
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(10)">S/ 10</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(20)">S/ 20</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(50)">S/ 50</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(100)">S/ 100</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid(200)">S/ 200</button>
                                    <button type="button" class="pos-quick-btn" onclick="setCashPaid('exact')">Exacto</button>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; align-items: center;">
                                <div>
                                    <input type="number" id="pos_amount_paid" class="form-input-custom" step="0.50" min="0" placeholder="0.00" style="font-size: 1.15rem; font-weight: 800;" oninput="calculateChange()">
                                </div>
                                <div style="background: rgba(0,0,0,0.5); padding: 0.55rem 0.75rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); text-align: right;">
                                    <span style="font-size: 0.65rem; color: #94A3B8; font-weight: 700; display: block; text-transform: uppercase;">Cambio / Vuelto:</span>
                                    <strong style="font-size: 1.15rem; font-weight: 900; color: #10B981;" id="posChangeAmountDisplay">S/ 0.00</strong>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN DEL FOOTER -->
                <div class="pos-modal-footer-actions" style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.15rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePosSaleModal()" style="padding: 0.75rem 1.4rem; font-weight: 700;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitPosSale" class="btn btn-primary btn-save-settings" style="padding: 0.75rem 1.8rem; font-size: 0.95rem; font-weight: 900; box-shadow: 0 6px 20px rgba(255, 85, 0, 0.45);">
                        🧾 Confirmar Venta & Imprimir
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- MODAL PUNTO DE VENTA (NUEVA CORTESÍA POS) -->
    <div class="admin-modal-overlay" id="posCourtesyModal">
        <div class="admin-modal-card" style="max-width: 1060px; width: 95%; max-height: 92vh; overflow-y: auto; padding: 2rem; border-radius: 28px; box-sizing: border-box; border: 1.5px solid rgba(16, 185, 129, 0.4); box-shadow: 0 20px 60px rgba(0,0,0,0.6), 0 0 30px rgba(16,185,129,0.12);">
            
            <div class="admin-modal-header" style="margin-bottom: 1.25rem; padding-bottom: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0; flex: 1;">
                    <div class="card-header-icon" style="width: 42px; height: 42px; background: rgba(16, 185, 129, 0.15); border: 1.5px solid rgba(16, 185, 129, 0.35); color: #10B981; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.3rem; flex-shrink: 0;">🎁</div>
                    <div style="min-width: 0; flex: 1;">
                        <h3 class="card-header-title" style="font-size: 1.15rem; margin: 0; color: #FFFFFF; font-weight: 900; line-height: 1.25;">Nueva Entrada de Cortesía (Pase Free / Invitados)</h3>
                        <p class="card-header-subtitle" style="margin: 0; font-size: 0.8rem; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $event->title }} · Emisión oficial autorizada a costo S/ 0.00</p>
                    </div>
                </div>
                <button type="button" class="admin-modal-close" onclick="closePosCourtesyModal()" style="font-size: 1.3rem; color: #94A3B8; background: transparent; border: none; cursor: pointer; flex-shrink: 0; padding: 0.25rem 0.5rem;" aria-label="Cerrar">✕</button>
            </div>

            <form id="posCourtesyForm" onsubmit="handlePosCourtesySubmit(event)">
                <!-- GRID DE 2 COLUMNAS PRINCIPALES -->
                <div class="pos-modal-two-columns" style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 1.5rem; align-items: start; margin-bottom: 1.25rem;">
                    
                    <!-- COLUMNA 1: SELECCIÓN DE SECTORES / ZONAS + CANTIDAD + TOTAL S/ 0.00 -->
                    <div>
                        <!-- SECTORES / ZONAS EN CARDS INTERACTIVAS DE CORTESÍA -->
                        <div class="form-group-custom" style="margin-bottom: 1.25rem;">
                            <label class="form-label-custom" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.85rem;">
                                <span>🎟️ Sector / Zona para la Cortesía <span class="required-star">*</span></span>
                                <small style="color: #10B981; font-weight: 700;">Haz clic para asignar zona</small>
                            </label>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.6rem;" id="courtesyZoneCardsContainer">
                                @php
                                    $firstActiveIndex = null;
                                @endphp
                                @foreach($zonesWithStats as $index => $z)
                                    @php
                                        $cEnabled = $z['courtesy_enabled'] ?? true;
                                        $cAvail = $z['courtesy_available'] ?? $z['available'];
                                        $cMaxStock = $z['courtesy_max_stock'] ?? null;
                                        $isAvailable = $cEnabled && ($cAvail > 0);
                                        if ($isAvailable && $firstActiveIndex === null) {
                                            $firstActiveIndex = $index;
                                        }
                                    @endphp
                                    <div class="zone-card-item courtesy-zone-card {{ $firstActiveIndex === $index ? 'active' : '' }} {{ !$isAvailable ? 'disabled' : '' }}"
                                         data-name="{{ $z['name'] }}"
                                         data-available="{{ $cAvail }}"
                                         data-regular-available="{{ $z['available'] }}"
                                         data-courtesy-enabled="{{ $cEnabled ? '1' : '0' }}"
                                         onclick="selectCourtesyZoneCard('{{ addslashes($z['name']) }}', {{ $cAvail }}, this)">
                                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.6rem;">
                                            <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
                                                <div class="zone-radio-indicator" style="border-color: #10B981;"></div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <strong class="zone-card-name" style="word-break: break-word;">{{ $z['name'] }}</strong>
                                                    <div style="margin-top: 0.15rem; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                                        @if(!$cEnabled)
                                                            <span class="zone-stock-badge sold-out">🚫 NO HABILITADA PARA CORTESÍA</span>
                                                        @elseif($cAvail > 0)
                                                            <span class="zone-stock-badge available">
                                                                🎁 Cupo: {{ number_format($cAvail) }} libres {{ $cMaxStock !== null ? "(de {$cMaxStock} asignados)" : "(aforo libre)" }}
                                                            </span>
                                                        @else
                                                            <span class="zone-stock-badge sold-out">🚫 CUPO CORTESÍA AGOTADO</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="text-align: right; flex-shrink: 0;">
                                                <span style="display: inline-block; background: rgba(16,185,129,0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.35); font-weight: 800; font-size: 0.8rem; padding: 0.25rem 0.6rem; border-radius: 8px;">CORTESÍA</span>
                                                <small style="display: block; font-size: 0.68rem; color: #94A3B8; margin-top: 2px;">Precio reg. S/ {{ number_format($z['price'], 2) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Input oculto para guardar el sector de cortesía seleccionado -->
                            <input type="hidden" id="pos_courtesy_zone_select" value="{{ $zonesWithStats[$firstActiveIndex ?? 0]['name'] ?? ($zonesWithStats[0]['name'] ?? '') }}" required>
                        </div>

                        <!-- CANTIDAD DE ENTRADAS DE CORTESÍA CON BOTONES VERDES Y QUICK PILLS -->
                        <div class="form-group-custom" style="margin-bottom: 1.25rem; background: rgba(16, 185, 129, 0.03); border: 1px solid rgba(16, 185, 129, 0.18); padding: 1rem; border-radius: 18px;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <label class="form-label-custom" style="margin: 0; font-size: 0.85rem; color: #10B981;">🎁 Cantidad de Entradas de Cortesía <span class="required-star">*</span></label>
                                <div class="pos-quick-qty-pills" style="display: flex; gap: 0.35rem; width: 100%;">
                                    <button type="button" class="pos-quick-btn active" id="btnCourtesyQuickQty1" onclick="setCourtesyQuantity(1)">1</button>
                                    <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty2" onclick="setCourtesyQuantity(2)">2</button>
                                    <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty3" onclick="setCourtesyQuantity(3)">3</button>
                                    <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty4" onclick="setCourtesyQuantity(4)">4</button>
                                    <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty5" onclick="setCourtesyQuantity(5)">5</button>
                                    <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty10" onclick="setCourtesyQuantity(10)">10</button>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.85rem; justify-content: center; margin-top: 0.5rem;">
                                <button type="button" class="pos-stepper-btn" onclick="stepCourtesyQuantity(-1)" title="Restar una cortesía" style="width: 48px; height: 48px; border-radius: 14px; background: #10B981; color: #FFFFFF; border: none; font-size: 1.6rem; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">-</button>
                                <input type="number" id="pos_courtesy_quantity" class="form-input-custom pos-stepper-input" value="1" min="1" max="100" required style="border-color: rgba(16, 185, 129, 0.4); color: #10B981;" oninput="updateCourtesySummary()">
                                <button type="button" class="pos-stepper-btn" onclick="stepCourtesyQuantity(1)" title="Sumar una cortesía" style="width: 48px; height: 48px; border-radius: 14px; background: #10B981; color: #FFFFFF; border: none; font-size: 1.6rem; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);">+</button>
                            </div>
                        </div>

                        <!-- TOTAL A COBRAR RESALTADO: S/ 0.00 CORTESÍA -->
                        <div class="pos-total-summary-card" style="background: rgba(16, 185, 129, 0.08); border-color: #10B981; box-shadow: 0 4px 18px rgba(16, 185, 129, 0.2);">
                            <div>
                                <span style="font-size: 0.75rem; font-weight: 800; color: #10B981; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Monto Total de Emisión</span>
                                <small style="color: #FFFFFF; font-weight: 600; font-size: 0.85rem;" id="courtesyQuantityDesc">1 entrada(s) de Cortesía (Free)</small>
                            </div>
                            <div style="font-size: 1.85rem; font-weight: 900; color: #10B981; text-shadow: 0 2px 12px rgba(16, 185, 129, 0.4); text-align: right;">
                                S/ 0.00
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 2: DATOS DEL BENEFICIARIO / INVITADO (SIN MÉTODOS DE PAGO) -->
                    <div>
                        <!-- DATOS DEL BENEFICIARIO -->
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 1.15rem; border-radius: 18px; margin-bottom: 1.15rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                                <h4 style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                                    <span>🎁</span> <span>Beneficiario / Invitado</span>
                                </h4>
                                <label style="display: flex; align-items: center; gap: 0.45rem; cursor: pointer; font-size: 0.75rem; font-weight: 800; color: #10B981; background: rgba(16,185,129,0.12); padding: 0.3rem 0.65rem; border-radius: 10px; border: 1px solid rgba(16,185,129,0.35); user-select: none;">
                                    <input type="checkbox" id="chkAnonymousCourtesy" onchange="toggleAnonymousCourtesy(this)" style="cursor: pointer; width: 15px; height: 15px; accent-color: #10B981;">
                                    <span>Sin Datos (Cortesía Rápida)</span>
                                </label>
                            </div>

                            <!-- SELECTOR DESPLEGABLE CON BUSCADOR (PLEGADO POR DEFECTO) -->
                            <div class="pos-client-picker" id="pos_courtesy_client_picker_container" style="position: relative; margin-bottom: 0.85rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                    <label class="form-label-custom" style="margin: 0; font-size: 0.775rem; color: #10B981; display: flex; align-items: center; gap: 0.35rem; font-weight: 700;">
                                        <span>👥</span> <span>Seleccionar Cliente / Beneficiario Existente (Opcional)</span>
                                    </label>
                                    <span id="courtesyClientSelectedBadge" style="display: none; font-size: 0.7rem; font-weight: 800; color: #10B981; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); padding: 0.15rem 0.5rem; border-radius: 8px;">
                                        ✓ Autocompletado
                                    </span>
                                </div>

                                <!-- Botón Desplegable (Cerrado por defecto) -->
                                <div style="display: flex; gap: 0.4rem; align-items: center;">
                                    <button type="button" 
                                            id="btnCourtesyClientDropdown" 
                                            onclick="toggleCourtesyClientDropdown(event)" 
                                            class="form-input-custom" 
                                            style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; text-align: left; cursor: pointer; background: rgba(16, 185, 129, 0.05); border: 1.5px solid rgba(16, 185, 129, 0.3); padding: 0.6rem 0.85rem; border-radius: 12px; color: #94A3B8; font-weight: 600; font-size: 0.825rem; width: 100%;">
                                        <span id="courtesyClientSelectedText" style="display: flex; align-items: center; gap: 0.45rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1;">
                                            <span>🔍</span> <span>-- Seleccionar cliente / invitado existente --</span>
                                        </span>
                                        <span id="courtesyClientArrow" style="font-size: 0.75rem; color: #10B981; transition: transform 0.2s ease;">▼</span>
                                    </button>
                                    <button type="button" 
                                            id="btnClearCourtesyClientSelection" 
                                            onclick="clearCourtesyClientSelection(event)" 
                                            title="Quitar cliente seleccionado" 
                                            style="display: none; height: 38px; padding: 0 0.65rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 10px; color: #EF4444; font-size: 0.8rem; font-weight: 800; cursor: pointer; align-items: center; gap: 0.25rem; flex-shrink: 0;">
                                        ✕
                                    </button>
                                </div>

                                <!-- Panel Flotante con BUSCADOR ADENTRO -->
                                <div id="courtesyClientDropdownPanel" 
                                     style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #14141E; border: 1.5px solid rgba(16, 185, 129, 0.45); border-radius: 14px; box-shadow: 0 16px 40px rgba(0,0,0,0.95); z-index: 1000; padding: 0.65rem;">
                                    
                                    <!-- Buscador dentro del desplegable -->
                                    <div style="position: relative; margin-bottom: 0.5rem;">
                                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-size: 0.85rem; color: #10B981; pointer-events: none;">🔎</span>
                                        <input type="text" 
                                               id="pos_courtesy_client_search" 
                                               class="form-input-custom" 
                                               placeholder="Buscar por Nombre, DNI, Correo..." 
                                               autocomplete="off"
                                               style="padding-left: 2.2rem; font-size: 0.825rem; background: rgba(255,255,255,0.06); border-color: rgba(16, 185, 129, 0.35); height: 36px;"
                                               oninput="filterCourtesyClients(this.value)">
                                    </div>

                                    <!-- Lista de Resultados -->
                                    <div id="courtesyClientDropdownList" style="max-height: 200px; overflow-y: auto;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.8rem;">
                                <label for="pos_courtesy_dni" class="form-label-custom">DNI / Documento <span class="required-star" id="star_courtesy_dni">*</span></label>
                                <input type="text" id="pos_courtesy_dni" class="form-input-custom" placeholder="Ej: 72819203" required style="font-weight: 700; letter-spacing: 0.5px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.8rem;">
                                <label for="pos_courtesy_name" class="form-label-custom">Nombre Completo del Invitado <span class="required-star" id="star_courtesy_name">*</span></label>
                                <input type="text" id="pos_courtesy_name" class="form-input-custom" placeholder="Ej: Juan Pérez Morales" required style="font-weight: 600;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.8rem;">
                                <label for="pos_courtesy_email" class="form-label-custom">Correo Electrónico (Opcional)</label>
                                <input type="email" id="pos_courtesy_email" class="form-input-custom" placeholder="Ej: invitado@correo.com" style="font-size: 0.85rem;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.8rem;">
                                <label for="pos_courtesy_phone" class="form-label-custom">Teléfono / WhatsApp (Opcional)</label>
                                <input type="text" id="pos_courtesy_phone" class="form-input-custom" placeholder="Ej: +51 987654321">
                            </div>

                            <div class="form-group-custom">
                                <label for="pos_courtesy_note" class="form-label-custom">Motivo / Tipo de Cortesía (Opcional)</label>
                                <select id="pos_courtesy_note" class="form-input-custom" style="font-size: 0.85rem; font-weight: 600; background-color: #14141E; color: #FFFFFF;">
                                    <option value="Invitado Especial" style="background-color: #14141E; color: #FFFFFF;">⭐ Invitado Especial / VIP</option>
                                    <option value="Prensa / Medios" style="background-color: #14141E; color: #FFFFFF;">📰 Prensa / Medios de Comunicación</option>
                                    <option value="Auspiciador / Sponsor" style="background-color: #14141E; color: #FFFFFF;">🤝 Auspiciador / Patrocinador</option>
                                    <option value="Staff / Producción" style="background-color: #14141E; color: #FFFFFF;">🛠️ Staff / Producción</option>
                                    <option value="Cortesía Directa" selected style="background-color: #14141E; color: #FFFFFF;">🎁 Cortesía Administrador</option>
                                </select>
                            </div>
                        </div>

                        <!-- PANEL INFORMATIVO DE CORTESÍA -->
                        <div style="background: rgba(16, 185, 129, 0.08); border: 1.5px dashed rgba(16, 185, 129, 0.35); border-radius: 16px; padding: 1rem 1.15rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="font-size: 1.4rem; flex-shrink: 0;">🛡️</span>
                            <div>
                                <strong style="color: #10B981; font-size: 0.85rem; display: block;">Emisión de Pases 100% Gratuitos</strong>
                                <p style="margin: 0.2rem 0 0 0; font-size: 0.775rem; color: #94A3B8; line-height: 1.45;">
                                    Las entradas se registrarán con método <strong>Cortesía</strong> a costo <strong>S/ 0.00</strong>, generarán su QR oficial y descontarán aforo en tiempo real.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN DEL FOOTER CORTESÍA -->
                <div class="pos-modal-footer-actions" style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.15rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePosCourtesyModal()" style="padding: 0.75rem 1.4rem; font-weight: 700;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitPosCourtesy" class="btn" style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; border: none; padding: 0.75rem 1.8rem; font-size: 0.95rem; font-weight: 900; border-radius: 14px; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45); cursor: pointer;">
                        🎁 Emitir Cortesía & Imprimir
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

        // Mapa reactivo de ventas para búsquedas rápidas de PDFs y Recibos
        window.posSalesMap = @json($sales->keyBy('id'));

        function getSaleObject(saleOrId) {
            if (typeof saleOrId === 'object' && saleOrId !== null) return saleOrId;
            if (window.posSalesMap && window.posSalesMap[saleOrId]) return window.posSalesMap[saleOrId];
            return null;
        }

        // ==========================================
        // AUTOCOMPLETADO Y BUSCADOR INTELIGENTE DE CLIENTES
        // ==========================================
        window.posExistingClients = @json($allClients ?? []);

        function highlightClientMatch(text, query) {
            if (!text) return '';
            if (!query || !query.trim()) return escapePosHtml(text);
            const q = query.trim().replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
            const regex = new RegExp(`(${q})`, 'gi');
            return escapePosHtml(text).replace(regex, '<mark style="background: rgba(16, 185, 129, 0.4); color: #FFFFFF; padding: 0 3px; border-radius: 4px; font-weight: 800;">$1</mark>');
        }

        function highlightSaleClientMatch(text, query) {
            if (!text) return '';
            if (!query || !query.trim()) return escapePosHtml(text);
            const q = query.trim().replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
            const regex = new RegExp(`(${q})`, 'gi');
            return escapePosHtml(text).replace(regex, '<mark style="background: rgba(255, 85, 0, 0.45); color: #FFFFFF; padding: 0 3px; border-radius: 4px; font-weight: 800;">$1</mark>');
        }

        function escapePosHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // --- CORTESÍA: CONTROL DEL DESPLEGABLE PLEGABLE ---
        function toggleCourtesyClientDropdown(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const panel = document.getElementById('courtesyClientDropdownPanel');
            if (panel && panel.style.display === 'block') {
                closeCourtesyClientDropdown();
            } else {
                openCourtesyClientDropdown();
            }
        }

        function openCourtesyClientDropdown() {
            const panel = document.getElementById('courtesyClientDropdownPanel');
            const arrow = document.getElementById('courtesyClientArrow');
            if (panel) {
                panel.style.display = 'block';
                if (arrow) arrow.textContent = '▲';
                const input = document.getElementById('pos_courtesy_client_search');
                if (input) {
                    input.focus();
                    filterCourtesyClients(input.value || '');
                }
            }
        }

        function closeCourtesyClientDropdown() {
            const panel = document.getElementById('courtesyClientDropdownPanel');
            const arrow = document.getElementById('courtesyClientArrow');
            if (panel) panel.style.display = 'none';
            if (arrow) arrow.textContent = '▼';
        }

        function filterCourtesyClients(query) {
            const list = document.getElementById('courtesyClientDropdownList');
            if (!list) return;

            const q = (query || '').toLowerCase().trim();
            const clients = window.posExistingClients || [];
            let matches = [];

            if (!q) {
                matches = clients.slice(0, 15);
            } else {
                matches = clients.filter(c => {
                    const name = (c.name || '').toLowerCase();
                    const dni = (c.dni || '').toLowerCase();
                    const email = (c.email || '').toLowerCase();
                    const phone = (c.phone || '').toLowerCase();
                    return name.includes(q) || dni.includes(q) || email.includes(q) || phone.includes(q);
                }).slice(0, 20);
            }

            if (matches.length === 0) {
                list.innerHTML = `
                    <div style="padding: 1.15rem; text-align: center; color: #94A3B8; font-size: 0.825rem;">
                        <div style="font-size: 1.6rem; margin-bottom: 0.35rem;">👤🔍</div>
                        <div>No se encontró ningún cliente registrado con "<b>${escapePosHtml(query)}</b>".</div>
                        <div style="color: #10B981; font-weight: 700; margin-top: 0.35rem; font-size: 0.775rem;">
                            ✨ Puedes ingresar los datos manualmente en el formulario.
                        </div>
                    </div>
                `;
                return;
            }

            let html = `
                <div style="padding: 0.35rem 0.6rem; font-size: 0.7rem; font-weight: 800; color: #10B981; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 0.3rem; display: flex; justify-content: space-between;">
                    <span>Clientes Registrados (${matches.length})</span>
                    <span>Click para seleccionar</span>
                </div>
            `;

            matches.forEach((c) => {
                const initial = (c.name || 'U').charAt(0).toUpperCase();
                const clientJson = JSON.stringify(c).replace(/"/g, '&quot;');
                html += `
                    <div class="pos-client-item" 
                         onclick='selectCourtesyClientObject(${clientJson})' 
                         style="padding: 0.55rem 0.75rem; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 0.65rem; transition: all 0.15s ease; border-bottom: 1px solid rgba(255,255,255,0.04);"
                         onmouseenter="this.style.background='rgba(16, 185, 129, 0.18)'"
                         onmouseleave="this.style.background='transparent'">
                        <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-weight: 900; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
                                ${initial}
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-weight: 700; color: #FFFFFF; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${highlightClientMatch(c.name, query)}
                                </div>
                                <div style="font-size: 0.725rem; color: #94A3B8; display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: 0.1rem;">
                                    ${c.email ? `<span>✉️ ${highlightClientMatch(c.email, query)}</span>` : ''}
                                    ${c.phone ? `<span>📱 ${highlightClientMatch(c.phone, query)}</span>` : ''}
                                </div>
                            </div>
                        </div>
                        ${c.dni ? `<span class="dash-badge-custom badge-green" style="font-size: 0.72rem; font-family: monospace; font-weight: 800; padding: 0.2rem 0.5rem; flex-shrink: 0;">DNI: ${highlightClientMatch(c.dni, query)}</span>` : ''}
                    </div>
                `;
            });

            list.innerHTML = html;
        }

        function selectCourtesyClientObject(client) {
            if (!client) return;

            // Desmarcar "Sin Datos" si estaba activo
            const chkAnon = document.getElementById('chkAnonymousCourtesy');
            if (chkAnon && chkAnon.checked) {
                chkAnon.checked = false;
                toggleAnonymousCourtesy(chkAnon);
            }

            const dniInput = document.getElementById('pos_courtesy_dni');
            const nameInput = document.getElementById('pos_courtesy_name');
            const emailInput = document.getElementById('pos_courtesy_email');
            const phoneInput = document.getElementById('pos_courtesy_phone');
            const textDisplay = document.getElementById('courtesyClientSelectedText');
            const badge = document.getElementById('courtesyClientSelectedBadge');
            const btnClear = document.getElementById('btnClearCourtesyClientSelection');

            if (dniInput) dniInput.value = client.dni || '';
            if (nameInput) nameInput.value = client.name || '';
            if (emailInput) emailInput.value = client.email || '';
            if (phoneInput) phoneInput.value = client.phone || '';

            if (textDisplay) {
                textDisplay.innerHTML = `<span style="color: #10B981; font-weight: 800;">👤 ${escapePosHtml(client.name)}${client.dni ? ' (DNI: ' + escapePosHtml(client.dni) + ')' : ''}</span>`;
            }

            if (badge) badge.style.display = 'inline-block';
            if (btnClear) btnClear.style.display = 'inline-flex';

            closeCourtesyClientDropdown();

            // Animación suave de confirmación en los campos autocompletados
            [dniInput, nameInput, emailInput, phoneInput].forEach(inp => {
                if (inp) {
                    inp.style.transition = 'box-shadow 0.3s ease, border-color 0.3s ease';
                    inp.style.borderColor = '#10B981';
                    inp.style.boxShadow = '0 0 12px rgba(16, 185, 129, 0.4)';
                    setTimeout(() => {
                        inp.style.borderColor = '';
                        inp.style.boxShadow = '';
                    }, 1200);
                }
            });
        }

        function clearCourtesyClientSelection(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const searchInput = document.getElementById('pos_courtesy_client_search');
            const textDisplay = document.getElementById('courtesyClientSelectedText');
            const badge = document.getElementById('courtesyClientSelectedBadge');
            const btnClear = document.getElementById('btnClearCourtesyClientSelection');

            if (searchInput) searchInput.value = '';
            if (textDisplay) {
                textDisplay.innerHTML = `<span>🔍</span> <span>-- Seleccionar cliente / invitado existente --</span>`;
            }
            if (badge) badge.style.display = 'none';
            if (btnClear) btnClear.style.display = 'none';

            closeCourtesyClientDropdown();
        }

        // --- VENTAS: CONTROL DEL DESPLEGABLE PLEGABLE ---
        function toggleSaleClientDropdown(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const panel = document.getElementById('saleClientDropdownPanel');
            if (panel && panel.style.display === 'block') {
                closeSaleClientDropdown();
            } else {
                openSaleClientDropdown();
            }
        }

        function openSaleClientDropdown() {
            const panel = document.getElementById('saleClientDropdownPanel');
            const arrow = document.getElementById('saleClientArrow');
            if (panel) {
                panel.style.display = 'block';
                if (arrow) arrow.textContent = '▲';
                const input = document.getElementById('pos_sale_client_search');
                if (input) {
                    input.focus();
                    filterSaleClients(input.value || '');
                }
            }
        }

        function closeSaleClientDropdown() {
            const panel = document.getElementById('saleClientDropdownPanel');
            const arrow = document.getElementById('saleClientArrow');
            if (panel) panel.style.display = 'none';
            if (arrow) arrow.textContent = '▼';
        }

        function filterSaleClients(query) {
            const list = document.getElementById('saleClientDropdownList');
            if (!list) return;

            const q = (query || '').toLowerCase().trim();
            const clients = window.posExistingClients || [];
            let matches = [];

            if (!q) {
                matches = clients.slice(0, 15);
            } else {
                matches = clients.filter(c => {
                    const name = (c.name || '').toLowerCase();
                    const dni = (c.dni || '').toLowerCase();
                    const email = (c.email || '').toLowerCase();
                    const phone = (c.phone || '').toLowerCase();
                    return name.includes(q) || dni.includes(q) || email.includes(q) || phone.includes(q);
                }).slice(0, 20);
            }

            if (matches.length === 0) {
                list.innerHTML = `
                    <div style="padding: 1.15rem; text-align: center; color: #94A3B8; font-size: 0.825rem;">
                        <div style="font-size: 1.6rem; margin-bottom: 0.35rem;">👤🔍</div>
                        <div>No se encontró ningún cliente registrado con "<b>${escapePosHtml(query)}</b>".</div>
                        <div style="color: var(--color-primary-orange); font-weight: 700; margin-top: 0.35rem; font-size: 0.775rem;">
                            ✨ Puedes ingresar los datos manualmente en el formulario.
                        </div>
                    </div>
                `;
                return;
            }

            let html = `
                <div style="padding: 0.35rem 0.6rem; font-size: 0.7rem; font-weight: 800; color: var(--color-primary-orange); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 0.3rem; display: flex; justify-content: space-between;">
                    <span>Clientes Registrados (${matches.length})</span>
                    <span>Click para seleccionar</span>
                </div>
            `;

            matches.forEach((c) => {
                const initial = (c.name || 'U').charAt(0).toUpperCase();
                const clientJson = JSON.stringify(c).replace(/"/g, '&quot;');
                html += `
                    <div class="pos-client-item" 
                         onclick='selectSaleClientObject(${clientJson})' 
                         style="padding: 0.55rem 0.75rem; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 0.65rem; transition: all 0.15s ease; border-bottom: 1px solid rgba(255,255,255,0.04);"
                         onmouseenter="this.style.background='rgba(255, 85, 0, 0.18)'"
                         onmouseleave="this.style.background='transparent'">
                        <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #FF5500, #FF7733); color: #FFFFFF; font-weight: 900; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(255,85,0,0.3);">
                                ${initial}
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-weight: 700; color: #FFFFFF; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${highlightSaleClientMatch(c.name, query)}
                                </div>
                                <div style="font-size: 0.725rem; color: #94A3B8; display: flex; gap: 0.6rem; flex-wrap: wrap; margin-top: 0.1rem;">
                                    ${c.email ? `<span>✉️ ${highlightSaleClientMatch(c.email, query)}</span>` : ''}
                                    ${c.phone ? `<span>📱 ${highlightSaleClientMatch(c.phone, query)}</span>` : ''}
                                </div>
                            </div>
                        </div>
                        ${c.dni ? `<span class="dash-badge-custom badge-orange" style="font-size: 0.72rem; font-family: monospace; font-weight: 800; padding: 0.2rem 0.5rem; flex-shrink: 0; background: rgba(255,85,0,0.15); color: #FF5500; border: 1px solid rgba(255,85,0,0.3);">DNI: ${highlightSaleClientMatch(c.dni, query)}</span>` : ''}
                    </div>
                `;
            });

            list.innerHTML = html;
        }

        function selectSaleClientObject(client) {
            if (!client) return;

            const chkAnon = document.getElementById('chkAnonymousBuyer');
            if (chkAnon && chkAnon.checked) {
                chkAnon.checked = false;
                toggleAnonymousBuyer(chkAnon);
            }

            const dniInput = document.getElementById('pos_buyer_dni');
            const nameInput = document.getElementById('pos_buyer_name');
            const phoneInput = document.getElementById('pos_buyer_phone');
            const textDisplay = document.getElementById('saleClientSelectedText');
            const badge = document.getElementById('saleClientSelectedBadge');
            const btnClear = document.getElementById('btnClearSaleClientSelection');

            if (dniInput) dniInput.value = client.dni || '';
            if (nameInput) nameInput.value = client.name || '';
            if (phoneInput) phoneInput.value = client.phone || '';

            if (textDisplay) {
                textDisplay.innerHTML = `<span style="color: var(--color-primary-orange); font-weight: 800;">👤 ${escapePosHtml(client.name)}${client.dni ? ' (DNI: ' + escapePosHtml(client.dni) + ')' : ''}</span>`;
            }

            if (badge) badge.style.display = 'inline-block';
            if (btnClear) btnClear.style.display = 'inline-flex';

            closeSaleClientDropdown();

            [dniInput, nameInput, phoneInput].forEach(inp => {
                if (inp) {
                    inp.style.transition = 'box-shadow 0.3s ease, border-color 0.3s ease';
                    inp.style.borderColor = 'var(--color-primary-orange)';
                    inp.style.boxShadow = '0 0 12px rgba(255, 85, 0, 0.4)';
                    setTimeout(() => {
                        inp.style.borderColor = '';
                        inp.style.boxShadow = '';
                    }, 1200);
                }
            });
        }

        function clearSaleClientSelection(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const searchInput = document.getElementById('pos_sale_client_search');
            const textDisplay = document.getElementById('saleClientSelectedText');
            const badge = document.getElementById('saleClientSelectedBadge');
            const btnClear = document.getElementById('btnClearSaleClientSelection');

            if (searchInput) searchInput.value = '';
            if (textDisplay) {
                textDisplay.innerHTML = `<span>🔍</span> <span>-- Seleccionar de clientes registrados --</span>`;
            }
            if (badge) badge.style.display = 'none';
            if (btnClear) btnClear.style.display = 'none';

            closeSaleClientDropdown();
        }

        // Cerrar dropdowns de autocompletado al hacer click fuera
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#pos_courtesy_client_picker_container')) {
                closeCourtesyClientDropdown();
            }
            if (!e.target.closest('#pos_sale_client_picker_container')) {
                closeSaleClientDropdown();
            }
        });

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
                closeSaleClientDropdown();
                
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
            closeSaleClientDropdown();
        }

        // CONTROL DEL MODAL DE CORTESÍA
        let selectedCourtesyZoneName = "{{ $zonesWithStats[$firstActiveIndex ?? 0]['name'] ?? ($zonesWithStats[0]['name'] ?? '') }}";
        let selectedCourtesyZoneAvailable = {{ $zonesWithStats[$firstActiveIndex ?? 0]['courtesy_available'] ?? ($zonesWithStats[0]['available'] ?? 100) }};

        function openPosCourtesyModal() {
            const modal = document.getElementById('posCourtesyModal');
            if (modal) {
                modal.classList.add('active');
                closeCourtesyClientDropdown();

                // Auto-seleccionar primer sector de cortesía disponible si ninguno está activo
                const activeCard = document.querySelector('.courtesy-zone-card.active:not(.disabled)') || document.querySelector('.courtesy-zone-card:not(.disabled)');
                if (activeCard) {
                    const name = activeCard.getAttribute('data-name');
                    const available = activeCard.getAttribute('data-available');
                    selectCourtesyZoneCard(name, available, activeCard);
                } else {
                    updateCourtesySummary();
                }

                setTimeout(() => {
                    document.getElementById('pos_courtesy_dni')?.focus();
                }, 150);
            }
        }

        function closePosCourtesyModal() {
            const modal = document.getElementById('posCourtesyModal');
            if (modal) {
                modal.classList.remove('active');
            }
            closeCourtesyClientDropdown();
        }

        function selectCourtesyZoneCard(name, available, el) {
            const availInt = parseInt(available, 10) || 0;
            if (availInt <= 0) return;

            document.querySelectorAll('.courtesy-zone-card').forEach(c => c.classList.remove('active'));
            if (el) el.classList.add('active');

            selectedCourtesyZoneName = name;
            selectedCourtesyZoneAvailable = availInt;

            const zoneInput = document.getElementById('pos_courtesy_zone_select');
            if (zoneInput) zoneInput.value = name;

            const qtyInput = document.getElementById('pos_courtesy_quantity');
            if (qtyInput) {
                qtyInput.max = selectedCourtesyZoneAvailable;
                if (parseInt(qtyInput.value, 10) > selectedCourtesyZoneAvailable) {
                    qtyInput.value = Math.max(1, selectedCourtesyZoneAvailable);
                }
            }

            updateCourtesySummary();
        }

        function setCourtesyQuantity(qty) {
            const input = document.getElementById('pos_courtesy_quantity');
            if (input) {
                const maxStock = selectedCourtesyZoneAvailable > 0 ? selectedCourtesyZoneAvailable : 100;
                input.value = Math.min(qty, maxStock);

                document.querySelectorAll('.pos-quick-btn[id^="btnCourtesyQuickQty"]').forEach(b => b.classList.remove('active'));
                const quickBtn = document.getElementById(`btnCourtesyQuickQty${qty}`);
                if (quickBtn) quickBtn.classList.add('active');

                updateCourtesySummary();
            }
        }

        function stepCourtesyQuantity(step) {
            const input = document.getElementById('pos_courtesy_quantity');
            if (input) {
                let current = parseInt(input.value, 10) || 1;
                const maxStock = selectedCourtesyZoneAvailable > 0 ? selectedCourtesyZoneAvailable : 100;
                current = Math.max(1, Math.min(maxStock, current + step));
                input.value = current;

                document.querySelectorAll('.pos-quick-btn[id^="btnCourtesyQuickQty"]').forEach(b => b.classList.remove('active'));
                const quickBtn = document.getElementById(`btnCourtesyQuickQty${current}`);
                if (quickBtn) quickBtn.classList.add('active');

                updateCourtesySummary();
            }
        }

        function updateCourtesySummary() {
            const qtyInput = document.getElementById('pos_courtesy_quantity');
            const descEl = document.getElementById('courtesyQuantityDesc');
            const quantity = parseInt(qtyInput?.value, 10) || 1;

            if (descEl) {
                descEl.textContent = `${quantity} entrada(s) de Cortesía (Free - S/ 0.00)`;
            }
        }

        function toggleAnonymousCourtesy(checkbox) {
            const dniInput = document.getElementById('pos_courtesy_dni');
            const nameInput = document.getElementById('pos_courtesy_name');
            const phoneInput = document.getElementById('pos_courtesy_phone');
            const emailInput = document.getElementById('pos_courtesy_email');
            const starDni = document.getElementById('star_courtesy_dni');
            const starName = document.getElementById('star_courtesy_name');

            if (checkbox.checked) {
                if (dniInput) {
                    dniInput.value = '00000000';
                    dniInput.readOnly = true;
                    dniInput.style.opacity = '0.55';
                    dniInput.required = false;
                }
                if (nameInput) {
                    nameInput.value = 'INVITADO DE CORTESÍA';
                    nameInput.readOnly = true;
                    nameInput.style.opacity = '0.55';
                    nameInput.required = false;
                }
                if (emailInput) {
                    emailInput.value = '';
                    emailInput.readOnly = true;
                    emailInput.style.opacity = '0.55';
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
                if (emailInput) {
                    emailInput.value = '';
                    emailInput.readOnly = false;
                    emailInput.style.opacity = '1';
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

            const paidInput = document.getElementById('pos_amount_paid');
            const totalDisplay = document.getElementById('posTotalToPayDisplay');

            if (method === 'Cortesía') {
                if (paidInput) paidInput.value = '0.00';
                if (totalDisplay) {
                    totalDisplay.textContent = 'S/ 0.00 (Cortesía)';
                    totalDisplay.style.color = '#10B981';
                }
            } else {
                if (totalDisplay) {
                    totalDisplay.textContent = 'S/ ' + currentTotalToPay.toFixed(2);
                    totalDisplay.style.color = '#FF5500';
                }
                if (method !== 'Efectivo' && paidInput) {
                    paidInput.value = currentTotalToPay.toFixed(2);
                }
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

            const isCourtesyReceipt = (receiptData.payment_method === 'Cortesía' || receiptData.payment_method === 'cortesia');

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
                        <div class="bold" style="font-size: 11px;">${isCourtesyReceipt ? 'PASE DE CORTESÍA / FREE' : 'COMPROBANTE DE VENTA'}: ${receiptData.receipt_number}</div>
                        <div style="font-size: 9px;">FECHA/HORA: ${receiptData.created_at_formatted}</div>
                        <div class="bold" style="font-size: 10px; margin-top: 3px;">CLIENTE / INVITADO: ${receiptData.buyer_name}</div>
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
                                <td class="text-right bold">${isCourtesyReceipt ? 'S/ 0.00' : receiptData.total_amount_formatted}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="dashed-line"></div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 13px;">
                            <span>TOTAL:</span>
                            <span>${isCourtesyReceipt ? 'S/ 0.00 (CORTESÍA)' : receiptData.total_amount_formatted}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 2px; font-size: 10px;">
                            <span>MODALIDAD:</span>
                            <span>${isCourtesyReceipt ? 'CORTESÍA / PASE LIBRE' : receiptData.payment_method}</span>
                        </div>
                        ${!isCourtesyReceipt ? `
                        <div style="display: flex; justify-content: space-between; font-size: 10px;">
                            <span>MONTO RECIBIDO:</span>
                            <span>${receiptData.amount_paid_formatted}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 11px; margin-top: 2px;">
                            <span>CAMBIO / VUELTO:</span>
                            <span>${receiptData.change_amount_formatted}</span>
                        </div>` : `
                        <div style="text-align: center; margin-top: 4px; font-size: 9px; font-weight: bold;">
                            *** PASE DE CORTESÍA - SIN COSTO ***
                        </div>`}
                    </div>

                    <div class="dashed-line"></div>

                    <div class="text-center" style="font-size: 9px; padding: 4px 0;">
                        <div class="bold" style="font-size: 11px; margin-bottom: 2px;">${isCourtesyReceipt ? '¡DISFRUTA EL EVENTO!' : '¡GRACIAS POR SU COMPRA!'}</div>
                        <div>${isCourtesyReceipt ? 'CONSERVE ESTE PASE DE INVITADO' : 'CONSERVE ESTE COMPROBANTE DE PAGO'}</div>
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
                if (url.startsWith('http://') || url.startsWith('https://')) {
                    const parsed = new URL(url);
                    if (parsed.origin !== window.location.origin) {
                        return url;
                    }
                }
            } catch(e) {}
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
                    img.onerror = () => { clearTimeout(timeout); resolve(url); };
                    img.src = url;
                });
                return dataUrl || url;
            } catch (e) {
                return url;
            }
        }

        function convertPositionsToElements(positions) {
            if (!positions || typeof positions !== 'object') return [];
            const elements = [];
            const fieldMap = {
                canvaElLogo: { field: 'logo', type: 'image' },
                canvaElBanner: { field: 'banner', type: 'image' },
                canvaElTitle: { field: 'title', type: 'text' },
                canvaElZone: { field: 'zone', type: 'text' },
                canvaElPrice: { field: 'price', type: 'text' },
                canvaElVenue: { field: 'venue', type: 'text' },
                canvaElCity: { field: 'city', type: 'text' },
                canvaElDate: { field: 'date', type: 'text' },
                canvaElTime: { field: 'time', type: 'text' },
                canvaElBuyerName: { field: 'buyer_name', type: 'text' },
                canvaElBuyer: { field: 'buyer_name', type: 'text' },
                canvaElBuyerDni: { field: 'buyer_dni', type: 'text' },
                canvaElTicketNumber: { field: 'ticket_number', type: 'text' },
                canvaElQR: { field: 'qr', type: 'qr' },
                canvaElHash: { field: 'hash', type: 'text' },
                canvaElDisclaimer: { field: 'disclaimer', type: 'disclaimer' },
            };

            Object.keys(positions).forEach((id) => {
                const p = positions[id];
                if (!p || p.hidden === true || p.display === 'none' || p.visible === false) return;

                const mapped = fieldMap[id] || { field: 'custom', type: 'text' };
                const isDisclaimer = id === 'canvaElDisclaimer' || mapped.field === 'disclaimer' || (id && String(id).toLowerCase().includes('disclaimer'));
                const topVal = parseFloat(p.top) || 0;
                const leftVal = parseFloat(p.left) || 0;
                const widthVal = parseFloat(p.width) || 120;
                const heightVal = parseFloat(p.height) || 40;

                // Leer textAlign desde múltiples fuentes en orden de prioridad:
                // 1. p.textAlign guardado (del wrapper canva-drag-element)
                // 2. Del HTML guardado (text-align puede estar en los hijos del box-container)
                // 3. Mapa de alineaciones por defecto por ID/campo (refleja el diseño original de los templates)
                const defaultAlignMap = {
                    canvaElPrice: 'right',
                    canvaElVenue: 'right',
                    canvaElTicketNumber: 'left',
                    canvaElHash: 'left',
                    canvaElDisclaimer: 'center',
                };
                let resolvedTextAlign = p.textAlign || '';
                if (!resolvedTextAlign && p.html) {
                    const taMatch = p.html.match(/text-align\s*:\s*(left|center|right|justify)/i);
                    if (taMatch) resolvedTextAlign = taMatch[1];
                }
                if (!resolvedTextAlign) {
                    resolvedTextAlign = defaultAlignMap[id] || (isDisclaimer ? 'center' : 'left');
                }

                elements.push({
                    id: id,
                    field: mapped.field,
                    type: mapped.type,
                    content: p.html || p.text || '',
                    src: p.src || '',
                    x: leftVal,
                    y: topVal,
                    width: widthVal,
                    height: heightVal,
                    rotation: parseFloat(p.rotate) || 0,
                    fit: 'cover',
                    style: {
                        fontFamily: p.fontFamily || 'Plus Jakarta Sans',
                        fontSize: parseFloat(p.fontSize) || 14,
                        color: p.color || '#FFFFFF',
                        fontWeight: p.fontWeight || 'bold',
                        fontStyle: p.fontStyle || 'normal',
                        textAlign: resolvedTextAlign,
                        letterSpacing: 0,
                        lineHeight: 1.2,
                        background: p.backgroundColor || 'transparent'
                    }
                });
            });

            return elements;
        }

        function getRealFontFamily(fontName) {
            if (!fontName) return 'Plus Jakarta Sans';
            const fontMap = {
                'font-lato': 'Lato',
                'font-montserrat': 'Montserrat',
                'font-opensans': 'Open Sans',
                'font-roboto': 'Roboto',
                'font-inter': 'Inter',
                'font-poppins': 'Poppins',
                'font-outfit': 'Outfit',
                'font-raleway': 'Raleway',
                'font-nunito': 'Nunito',
                'font-rubik': 'Rubik',
                'font-work-sans': 'Work Sans',
                'font-oswald': 'Oswald',
                'font-bebas': 'Bebas Neue',
                'font-anton': 'Anton',
                'font-syne': 'Syne',
                'font-space-grotesk': 'Space Grotesk',
                'font-righteous': 'Righteous',
                'font-monoton': 'Monoton',
                'font-merriweather': 'Merriweather',
                'font-playfair': 'Playfair Display',
                'font-cinzel': 'Cinzel',
                'font-abril': 'Abril Fatface',
                'font-dancing': 'Dancing Script',
                'font-greatvibes': 'Great Vibes',
                'font-pacifico': 'Pacifico',
                'font-satisfy': 'Satisfy',
                'font-caveat': 'Caveat',
                'font-lobster': 'Lobster',
                'font-comfortaa': 'Comfortaa'
            };

            if (fontMap[fontName]) return fontMap[fontName];
            if (typeof fontName === 'string' && fontName.startsWith('font-')) {
                const clean = fontName.replace('font-', '');
                return clean.charAt(0).toUpperCase() + clean.slice(1);
            }
            return fontName;
        }

        function replaceDynamicValueInHtml(html, labelKeyword, newValue) {
            if (!html || typeof html !== 'string') return html;
            const cleanLabel = labelKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            
            // Caso 1: Estructura multilínea de párrafos <p>Label:</p><p>Valor</p>
            const multiPRegex = new RegExp(`(<p[^>]*>\\s*${cleanLabel}:?\\s*<\\/p>\\s*<p[^>]*>)(.*?)(<\\/p>)`, 'gi');
            if (multiPRegex.test(html)) {
                return html.replace(multiPRegex, `$1${newValue}$3`);
            }

            // Caso 2: Estructura en línea Label: Valor
            const singleRegex = new RegExp(`(${cleanLabel}:?\\s*)((?:<[^>]+>\\s*)*)([^<\\s]+[^<]*)`, 'gi');
            if (singleRegex.test(html)) {
                return html.replace(singleRegex, `$1$2${newValue}`);
            }

            return html;
        }

        function renderTicketCanvasContent(template, dynamicData, assetMap = {}) {
            let elements = [];
            if (template && Array.isArray(template.elements) && template.elements.length > 0) {
                elements = template.elements;
            } else if (template && template.positions) {
                let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
                elements = convertPositionsToElements(rawPos);
            }

            // Deduplicar elementos del sistema para evitar renderizado doble
            const seenFields = new Set();
            const uniqueElements = [];
            for (let i = elements.length - 1; i >= 0; i--) {
                const el = elements[i];
                if (!el || el.hidden === true || el.display === 'none' || el.visible === false) continue;
                const key = el.field || el.id;
                if (key && (key.startsWith('canvaEl') || key === 'title' || key === 'zone' || key === 'price' || key === 'buyer_name' || key === 'buyer_dni' || key === 'ticket_number' || key === 'hash' || key === 'venue' || key === 'date' || key === 'time' || key === 'disclaimer')) {
                    if (seenFields.has(key)) continue;
                    seenFields.add(key);
                }
                uniqueElements.unshift(el);
            }
            elements = uniqueElements;

            const bgUrl = assetMap.bgDataUrl || (template ? (template.background || template.bg_image) : null);
            let bgHtml = '';
            if (bgUrl) {
                bgHtml = `<div style="position: absolute; inset: 0; background-image: url('${bgUrl}'); background-size: cover; background-position: center; z-index: 0; pointer-events: none;"></div>`;
            }

            let elementsHtml = '';

            elements.forEach((el, idx) => {
                if (!el || el.hidden === true || el.display === 'none' || el.visible === false) return;

                const type = el.type || 'text';
                const field = el.field || 'custom';

                const x = parseFloat(el.x) || 0;
                const y = parseFloat(el.y) || 0;

                const w = el.style?.width ? (typeof el.style.width === 'number' ? el.style.width + 'px' : el.style.width) : (el.width ? (typeof el.width === 'number' ? el.width + 'px' : el.width) : 'auto');
                const h = el.style?.height ? (typeof el.style.height === 'number' ? el.style.height + 'px' : el.style.height) : (el.height ? (typeof el.height === 'number' ? el.height + 'px' : el.height) : 'auto');
                const rotation = parseFloat(el.style?.rotation || el.rotation || el.rotate) || 0;
                const transform = rotation ? `transform: rotate(${rotation}deg); transform-origin: center center;` : '';

                const style = el.style || {};
                const rawFontName = style.fontFamily || el.fontFamily || 'Plus Jakarta Sans';
                const realFontName = getRealFontFamily(rawFontName);
                const font = realFontName.includes(',') ? `font-family: ${realFontName};` : `font-family: '${realFontName}', sans-serif;`;
                const fontSize = style.fontSize ? (typeof style.fontSize === 'number' ? `font-size: ${style.fontSize}px;` : `font-size: ${style.fontSize};`) : 'font-size: 14px;';
                const color = style.color ? `color: ${style.color};` : 'color: #FFFFFF;';
                const weight = style.fontWeight ? `font-weight: ${style.fontWeight};` : 'font-weight: bold;';
                const fontStyle = style.fontStyle ? `font-style: ${style.fontStyle};` : 'font-style: normal;';
                let textAlign = style.textAlign || el.textAlign || el.align || 'left';
                const letterSpacing = style.letterSpacing ? `letter-spacing: ${style.letterSpacing}px;` : '';
                const lineHeight = style.lineHeight ? `line-height: ${style.lineHeight};` : 'line-height: 1.2;';
                const bgStyle = style.background && style.background !== 'transparent' ? `background-color: ${style.background}; border-radius: ${style.borderRadius || '8px'}; padding: ${style.padding || '2px 6px'};` : '';

                let innerContent = '';

                if (type === 'qr' || field === 'qr' || el.id === 'canvaElQR') {
                    const qrSrc = dynamicData.qr_data_url || el.src;
                    innerContent = `<div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1.5px solid #E2E8F0; width: 100%; height: 100%; box-sizing: border-box; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><img src="${qrSrc}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px;" alt="QR Code" /></div>`;
                } else if (type === 'image' || type === 'logo' || type === 'banner' || field === 'logo' || field === 'banner' || field === 'image' || el.id === 'canvaElLogo' || el.id === 'canvaElBanner') {
                    let imgSrc = el.src;
                    if ((field === 'banner' || type === 'banner' || el.id === 'canvaElBanner') && (!imgSrc || imgSrc === '')) {
                        imgSrc = assetMap.bannerDataUrl;
                    }
                    if ((field === 'logo' || type === 'logo' || el.id === 'canvaElLogo') && (!imgSrc || imgSrc === '')) {
                        imgSrc = assetMap.logoDataUrl;
                    }
                    const fitMode = style.objectFit || el.fit || (type === 'banner' || field === 'banner' ? 'cover' : 'contain');
                    if (imgSrc) {
                        innerContent = `<img src="${imgSrc}" style="width: 100%; height: 100%; display: block; object-fit: ${fitMode}; ${field === 'logo' || type === 'logo' ? 'filter: drop-shadow(0 0 8px rgba(255,85,0,0.6));' : ''}" />`;
                    }
                } else {
                    let rawTxt = el.content || el.html || el.text || '';

                    // Limpieza de artefactos de Quill
                    if (typeof rawTxt === 'string') {
                        rawTxt = rawTxt.replace(/<span class="ql-cursor">.*?<\/span>/gi, '').replace(/\uFEFF/g, '');
                    }

                    if (field === 'title' || el.id === 'canvaElTitle') {
                        if (dynamicData.title) {
                            rawTxt = (rawTxt && (rawTxt.includes('<') || rawTxt.includes('>')))
                                ? rawTxt.replace(/(<h[1-6][^>]*>|<p[^>]*>|<span[^>]*>)(.*?)(<\/h[1-6]>|<\/p>|<\/span>|$)/gi, (m, p1, p2, p3) => p1 + dynamicData.title + p3)
                                : dynamicData.title;
                        }
                    } else if (field === 'zone' || el.id === 'canvaElZone' || /ZONA/i.test(rawTxt)) {
                        const zVal = (dynamicData.zone || 'GENERAL').toUpperCase();
                        if (/ZONA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'ZONA', zVal);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, zVal) : zVal;
                        } else {
                            rawTxt = `<span style="font-size: inherit; font-weight: inherit; color: inherit; text-transform: uppercase;">ZONA: ${zVal}</span>`;
                        }
                    } else if (field === 'price' || el.id === 'canvaElPrice' || /PRECIO/i.test(rawTxt)) {
                        const pVal = dynamicData.price ? (String(dynamicData.price).startsWith('S/') ? dynamicData.price : 'S/ ' + dynamicData.price) : 'S/ 0.00';
                        if (/PRECIO/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'PRECIO', pVal);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, pVal) : pVal;
                        } else {
                            rawTxt = `<div style="line-height: 1.15; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; font-weight: 900; display: block;">PRECIO:</span><span style="font-size: 1.2em; font-weight: 900; display: block; margin-top: 2px;">${pVal}</span></div>`;
                        }
                    } else if (field === 'buyer_name' || el.id === 'canvaElBuyerName' || el.id === 'canvaElBuyer' || /Comprador/i.test(rawTxt)) {
                        const bName = (dynamicData.buyer_name || 'CLIENTE VARIOS').toUpperCase();
                        if (/Comprador/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'Comprador', bName);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bName) : bName;
                        } else {
                            rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; opacity: 0.85;">Comprador:</span><span style="font-weight: 900; text-transform: uppercase;">${bName}</span></div>`;
                        }
                    } else if (field === 'buyer_dni' || el.id === 'canvaElBuyerDni' || /DNI/i.test(rawTxt)) {
                        const bDni = dynamicData.buyer_dni || '00000000';
                        if (/DNI/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'DNI', bDni);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bDni) : bDni;
                        } else {
                            rawTxt = `<span style="font-weight: 800;">DNI: ${bDni}</span>`;
                        }
                    } else if (field === 'ticket_number' || el.id === 'canvaElTicketNumber' || /N[°º]/i.test(rawTxt)) {
                        const numStr = dynamicData.ticket_number || 'N° 00001';
                        if (/N[°º]/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/N[°º]\s*[\d]+/gi, numStr);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, numStr) : numStr;
                        } else {
                            rawTxt = `<span style="font-weight: 900; letter-spacing: 0.5px;">${numStr}</span>`;
                        }
                    } else if (field === 'hash' || el.id === 'canvaElHash' || (el.id && String(el.id).toLowerCase().includes('hash')) || /VG-?[A-Z0-9]{6,12}/i.test(rawTxt)) {
                        const hStr = dynamicData.hash || 'VG00000000';
                        if (/VG-?[A-Z0-9]{6,12}/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/VG-?[A-Z0-9]{6,12}/gi, hStr);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, hStr) : hStr;
                        } else {
                            rawTxt = `<span style="font-family: monospace; font-weight: 800; letter-spacing: 1.5px;">${hStr}</span>`;
                        }
                    } else if (field === 'venue' || el.id === 'canvaElVenue') {
                        const vName = dynamicData.venue || '';
                        const vAddr = dynamicData.city || '';
                        const vDate = dynamicData.date || '';
                        const vTime = dynamicData.time || '';
                        if (!rawTxt || rawTxt.trim().length === 0) {
                            rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-weight: 900; display: block;">${vName}</span>${vAddr ? `<span style="font-size: 0.85em; opacity: 0.8; display: block; margin-top: 2px;">${vAddr}</span>` : ''}<span style="font-weight: 900; color: #FF5500; display: block; margin-top: 2px;">${vDate} / ${vTime}</span></div>`;
                        }
                    } else if (field === 'city' || el.id === 'canvaElCity') {
                        rawTxt = dynamicData.city || rawTxt;
                    } else if (field === 'date' || el.id === 'canvaElDate' || /FECHA/i.test(rawTxt)) {
                        if (/FECHA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'FECHA', dynamicData.date || '');
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = dynamicData.date ? (rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, dynamicData.date) : dynamicData.date) : rawTxt;
                        } else {
                            rawTxt = `<span style="font-weight: 900;">FECHA: ${dynamicData.date || ''}</span>`;
                        }
                    } else if (field === 'time' || el.id === 'canvaElTime' || /HORA/i.test(rawTxt)) {
                        if (/HORA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'HORA', dynamicData.time || '');
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = dynamicData.time ? (rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, dynamicData.time) : dynamicData.time) : rawTxt;
                        } else {
                            rawTxt = `<span style="font-weight: 900;">HORA: ${dynamicData.time || ''}</span>`;
                        }
                    } else if (field === 'disclaimer' || el.id === 'canvaElDisclaimer' || (el.id && String(el.id).toLowerCase().includes('disclaimer'))) {
                        rawTxt = rawTxt || `<div style="border-top: 1.5px solid #CBD5E1; padding-top: 0.25rem; width: 100%; text-align: inherit;"><p style="font-size: 0.65em; font-weight: 700; opacity: 0.8; line-height: 1.2; margin: 0; text-align: inherit;">La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.</p></div>`;
                    }

                    const flexAlign = textAlign === 'center' ? 'center' : (textAlign === 'right' ? 'flex-end' : 'flex-start');

                    if (typeof rawTxt === 'string' && rawTxt.includes('<')) {
                        // Forzar text-align en cualquier estilo existente
                        rawTxt = rawTxt
                            .replace(/text-align\s*:\s*(left|center|right|justify)/gi, `text-align: ${textAlign}`)
                            .replace(/align-items\s*:\s*(flex-start|center|flex-end|stretch)/gi, `align-items: ${flexAlign}`);
                        
                        // Inyectar text-align y width: 100% en todas las etiquetas de bloque internas
                        rawTxt = rawTxt.replace(/<(p|div|h[1-6])\b([^>]*)>/gi, (match, tag, attrs) => {
                            if (/style\s*=/i.test(attrs)) {
                                return `<${tag} ${attrs.replace(/style\s*=\s*(['"])/i, `style=$1text-align: ${textAlign} !important; width: 100% !important; `)}>`;
                            } else {
                                return `<${tag} style="text-align: ${textAlign} !important; width: 100% !important; margin: 0; padding: 0;" ${attrs}>`;
                            }
                        });
                    }

                    innerContent = `
                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: ${flexAlign}; text-align: ${textAlign} !important; box-sizing: border-box; ${font} ${fontSize} ${color} ${weight} ${fontStyle} ${letterSpacing} ${lineHeight} ${bgStyle}">
                            ${rawTxt}
                        </div>
                    `;
                }

                elementsHtml += `
                    <div class="ticket-element-node" style="position: absolute; top: ${y}px; left: ${x}px; width: ${w}; height: ${h}; z-index: ${idx + 5}; ${transform} box-sizing: border-box; text-align: ${textAlign} !important;">
                        ${innerContent}
                    </div>
                `;
            });

            return `
                ${bgHtml}
                <div style="position: absolute; inset: 0; width: 100%; height: 100%;" class="ticket-elements-layer">
                    ${elementsHtml}
                </div>
            `;
        }

        // Descargar PDF individual / multipágina para la venta en Taquilla (POS)
        async function downloadPosSalePdf(saleOrId) {
            const sale = getSaleObject(saleOrId);
            if (!sale) {
                console.error('[CanvaStudio POS PDF] Venta no encontrada:', saleOrId);
                return;
            }

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

                const template = @json($event->template ?? null) || { id: 1, name: 'Plantilla 1', bg_color: '#FFFFFF', positions: {}, elements: [] };
                const bgColor = template.bg_color || '#FFFFFF';

                function getFullAssetUrl(urlStr) {
                    if (!urlStr) return null;
                    if (urlStr.startsWith('data:')) return urlStr;
                    if (urlStr.startsWith('http://') || urlStr.startsWith('https://')) {
                        return urlStr;
                    }

                    let clean = urlStr.replace(/^\//, '');
                    if (clean.includes('storage/')) {
                        clean = 'storage/' + clean.split('storage/').pop();
                    } else if (clean.includes('images/')) {
                        clean = 'images/' + clean.split('images/').pop();
                    } else if (clean.startsWith('events/') || clean.startsWith('templates/') || clean.startsWith('media/') || clean.startsWith('uploads/')) {
                        clean = 'storage/' + clean;
                    }

                    return window.location.origin + '/' + clean;
                }

                // Precargar imágenes base
                const bgImgSrc = template.background ? getFullAssetUrl(template.background) : (template.bg_image ? getFullAssetUrl(template.bg_image) : null);
                const bannerImgSrc = "{{ !empty($event->banner_image) ? asset($event->banner_image) : '' }}";
                const boletoSrc = getFullAssetUrl('/images/Boleto.jpg');

                const [bgDataUrl, bannerDataUrl, logoDataUrl, boletoDataUrl] = await Promise.all([
                    bgImgSrc ? preloadPosImageAsDataUrl(bgImgSrc, 'bg') : Promise.resolve(''),
                    bannerImgSrc ? preloadPosImageAsDataUrl(bannerImgSrc, 'banner') : Promise.resolve(''),
                    logoWhite ? preloadPosImageAsDataUrl(logoWhite, 'logo') : Promise.resolve(''),
                    preloadPosImageAsDataUrl(boletoSrc, 'boleto')
                ]);

                const assetMap = {
                    bgDataUrl: bgDataUrl,
                    bannerDataUrl: bannerDataUrl,
                    logoDataUrl: logoDataUrl
                };

                // Pre-procesar URLs en los elementos de la plantilla
                let tplElements = template.elements || [];
                if ((!Array.isArray(tplElements) || tplElements.length === 0) && template.positions) {
                    let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
                    tplElements = convertPositionsToElements(rawPos);
                }

                // Precargar todas las imágenes individuales de los elementos (incluyendo banner ticket subidos)
                for (let el of tplElements) {
                    if (el.src) {
                        const fullUrl = getFullAssetUrl(el.src);
                        el.src = await preloadPosImageAsDataUrl(fullUrl, 'el_' + el.id);
                    }
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
                    if (!hashVal) {
                        hashVal = 'VG' + String(Math.abs(((sale.receipt_number || 'REC') + '_' + (i + 1)).split('').reduce((a, b) => { a = ((a << 5) - a) + b.charCodeAt(0); return a & a; }, 0))).padStart(8, '0').substring(0, 8).toUpperCase();
                    }

                    const qrPayload = tItem.qr_payload || sale.qr_payload || `VIVEGO|${sale.receipt_number || 'REC'}|EVT-${sale.event_id || eventId}|DNI-${sale.buyer_dni || '00000000'}|TICK-${numSeq}|${hashVal}`;
                    const qrDataUrl = generateQrBase64(qrPayload);

                    const isCourtesy = (sale.payment_method === 'Cortesía' || sale.payment_method === 'cortesia' || tItem.is_courtesy);
                    const unitPriceVal = isCourtesy ? '0.00' : parseFloat(tItem.price || sale.unit_price || sale.total_amount).toFixed(2);
                    const priceDisplay = isCourtesy ? 'CORTESÍA' : ('S/ ' + unitPriceVal);

                    const dynamicData = {
                        title: eventTitle,
                        venue: eventVenue,
                        city: eventAddress,
                        date: eventDate,
                        time: eventTime,
                        zone: tItem.zone || sale.zone_name,
                        price: priceDisplay,
                        buyer_name: sale.buyer_name || (isCourtesy ? 'INVITADO DE CORTESÍA' : 'CLIENTE VARIOS'),
                        buyer_dni: sale.buyer_dni || '00000000',
                        ticket_number: ticketNumStr,
                        hash: hashVal,
                        qr_data_url: qrDataUrl
                    };

                    const canvasHtml = renderTicketCanvasContent({ ...template, elements: tplElements }, dynamicData, assetMap);

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
                            ${canvasHtml}
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
        function reprintReceipt(saleOrId) {
            const sale = getSaleObject(saleOrId);
            if (!sale) {
                console.error('[POS Receipt] Venta no encontrada:', saleOrId);
                return;
            }

            const eventData = {
                title: "{{ addslashes($event->title) }}",
                venue_name: "{{ addslashes($event->venue_name ?? '') }}",
                address: "{{ addslashes($event->address ?? '') }}",
                event_date: "{{ !empty($event->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('d/m/Y')) : '' }}",
                event_time: "{{ addslashes($event->event_time ?? '') }}"
            };

            const receiptData = {
                receipt_number: sale.receipt_number,
                created_at_formatted: sale.created_at ? (typeof sale.created_at === 'string' ? sale.created_at.substring(0, 19).replace('T', ' ') : 'Hoy') : 'Hoy',
                buyer_name: sale.buyer_name,
                buyer_dni: sale.buyer_dni,
                zone_name: sale.zone_name,
                quantity: sale.quantity,
                unit_price_formatted: `S/ ${parseFloat(sale.unit_price || 0).toFixed(2)}`,
                total_amount_formatted: `S/ ${parseFloat(sale.total_amount || 0).toFixed(2)}`,
                payment_method: sale.payment_method,
                amount_paid_formatted: `S/ ${parseFloat(sale.amount_paid || sale.total_amount || 0).toFixed(2)}`,
                change_amount_formatted: `S/ ${parseFloat(sale.change_amount || 0).toFixed(2)}`,
                tickets: (typeof sale.tickets_data === 'string' ? JSON.parse(sale.tickets_data) : sale.tickets_data) || []
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
                    // 1. Guardar en mapa reactivo de ventas
                    window.posSalesMap = window.posSalesMap || {};
                    window.posSalesMap[data.sale.id] = data.sale;

                    // 2. Insertar nueva venta en la tabla reactiva en vivo DE INMEDIATO
                    const emptyRow = document.getElementById('emptySalesRow');
                    if (emptyRow) emptyRow.remove();

                    const tableBody = document.getElementById('salesTableBody');
                    if (tableBody) {
                        const newRow = document.createElement('tr');
                        newRow.className = 'sale-row-item';
                        newRow.setAttribute('data-sale-id', data.sale.id);
                        
                        let paymentBadge = `<span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">💵 Efectivo</span>`;
                        if (data.sale.payment_method === 'Cortesía' || data.sale.payment_method === 'cortesia') {
                            const isWeb = (data.sale.seller_name && data.sale.seller_name.toLowerCase().includes('web'));
                            paymentBadge = isWeb
                                ? `<span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; background: rgba(0, 240, 255, 0.15); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;"><span>🌐</span> <span>Cortesía Web</span></span>`
                                : `<span class="dash-badge-custom badge-green" style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;"><span>🎁</span> <span>Cortesía Adm</span></span>`;
                        } else if (data.sale.payment_method === 'Yape') {
                            paymentBadge = `<span class="dash-badge-custom badge-purple" style="font-size: 0.75rem; background: rgba(168, 85, 247, 0.15); color: #A855F7; border: 1px solid rgba(168, 85, 247, 0.3);">📱 Yape</span>`;
                        } else if (data.sale.payment_method === 'Plin') {
                            paymentBadge = `<span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; color: #00F0FF; background: rgba(0, 240, 255, 0.15); border: 1px solid rgba(0, 240, 255, 0.3);">🟣 Plin</span>`;
                        } else if (data.sale.payment_method !== 'Efectivo') {
                            paymentBadge = `<span class="dash-badge-custom badge-blue" style="font-size: 0.75rem;">💳 ${data.sale.payment_method}</span>`;
                        }

                        newRow.innerHTML = `
                            <td>
                                <div>
                                    <span style="font-weight: 800; color: var(--color-primary-orange); font-family: monospace; font-size: 0.95rem; display: block;">
                                        ${data.receipt.receipt_number}
                                    </span>
                                    <small style="color: #94A3B8; font-size: 0.775rem; font-weight: 600;">
                                        ${data.receipt.created_at_formatted}
                                    </small>
                                </div>
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
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="reprintReceipt(${data.sale.id})" title="Reimprimir Recibo Térmico" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: 1px solid rgba(255,85,0,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3); cursor: pointer;">
                                        <span>🧾</span>
                                        <span>Recibo</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="downloadPosSalePdf(${data.sale.id})" title="Descargar Entrada PDF" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: 1px solid rgba(6,182,212,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); cursor: pointer;">
                                        <span>🎟️</span>
                                        <span>Entrada PDF</span>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePosSale(${data.sale.id})" title="Borrar Entrada" style="background: linear-gradient(135deg, #EF4444, #DC2626); border: 1px solid rgba(239,68,68,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); cursor: pointer;">
                                        <span>🗑️</span>
                                        <span>Borrar Entrada</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        tableBody.prepend(newRow);
                    }

                    // 3. Actualizar KPIs en vivo
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

                                const zoneModalCard = document.querySelector(`.zone-card-item[data-name="${z.name}"]:not(.courtesy-zone-card)`);
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

                                const courtesyZoneCard = document.querySelector(`.courtesy-zone-card[data-name="${z.name}"]`);
                                if (courtesyZoneCard) {
                                    const cAvail = (z.courtesy_available !== undefined) ? z.courtesy_available : z.available;
                                    const cEnabled = (z.courtesy_enabled !== undefined) ? z.courtesy_enabled : true;
                                    const cMaxStock = z.courtesy_max_stock || null;
                                    courtesyZoneCard.setAttribute('data-available', cAvail);
                                    const cModalBadge = courtesyZoneCard.querySelector('.zone-stock-badge');
                                    if (cModalBadge) {
                                        if (!cEnabled) {
                                            cModalBadge.className = 'zone-stock-badge sold-out';
                                            cModalBadge.textContent = '🚫 NO HABILITADA PARA CORTESÍA';
                                            courtesyZoneCard.classList.add('disabled');
                                            courtesyZoneCard.classList.remove('active');
                                        } else if (cAvail > 0) {
                                            cModalBadge.className = 'zone-stock-badge available';
                                            cModalBadge.textContent = `🎁 Cupo: ${cAvail} libres ${cMaxStock ? '(de ' + cMaxStock + ' asignados)' : '(aforo libre)'}`;
                                            courtesyZoneCard.classList.remove('disabled');
                                        } else {
                                            cModalBadge.className = 'zone-stock-badge sold-out';
                                            cModalBadge.textContent = '🚫 CUPO CORTESÍA AGOTADO';
                                            courtesyZoneCard.classList.add('disabled');
                                            courtesyZoneCard.classList.remove('active');
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // 4. Cerrar Modal POS y resetear formulario
                    closePosSaleModal();
                    clearSaleClientSelection();
                    document.getElementById('posSaleForm')?.reset();
                    const chkAnon = document.getElementById('chkAnonymousBuyer');
                    if (chkAnon) {
                        chkAnon.checked = false;
                        toggleAnonymousBuyer(chkAnon);
                    }

                    // 5. Disparar impresión térmica segura
                    try {
                        printThermalReceipt(data.receipt, data.event);
                    } catch(errPrint) {
                        console.warn('[POS Thermal Print Error]', errPrint);
                    }

                    // 6. Modal Interactivo de Confirmación (Recibo + Entrada PDF)
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
                                reprintReceipt(data.sale.id);
                            });
                            const btnP = document.getElementById('btnAlertDownloadPdf');
                            if (btnP) btnP.addEventListener('click', () => {
                                downloadPosSalePdf(data.sale.id);
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

        // Enviar nueva cortesía (REACTIVO EN VIVO)
        function handlePosCourtesySubmit(e) {
            e.preventDefault();

            const zoneName = document.getElementById('pos_courtesy_zone_select')?.value || selectedCourtesyZoneName;
            const quantity = parseInt(document.getElementById('pos_courtesy_quantity').value, 10) || 1;
            const buyerName = document.getElementById('pos_courtesy_name').value.trim() || 'INVITADO DE CORTESÍA';
            const buyerDni = document.getElementById('pos_courtesy_dni').value.trim() || '00000000';
            const buyerEmail = document.getElementById('pos_courtesy_email')?.value.trim() || null;
            const buyerPhone = document.getElementById('pos_courtesy_phone').value.trim() || '-';
            const courtesyNote = document.getElementById('pos_courtesy_note')?.value || 'Cortesía Directa';

            const btnSubmit = document.getElementById('btnSubmitPosCourtesy');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.textContent = '⏳ Emitiendo cortesías y generando QR...';
            }

            const payload = {
                zone_name: zoneName,
                quantity: quantity,
                buyer_name: buyerName,
                buyer_dni: buyerDni,
                buyer_email: buyerEmail,
                buyer_phone: buyerPhone !== '-' ? `${buyerPhone} (${courtesyNote})` : courtesyNote,
                payment_method: 'Cortesía',
                amount_paid: 0.00
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
                    btnSubmit.textContent = '🎁 Emitir Cortesía & Imprimir';
                }

                if (data.success) {
                    // 1. Guardar en mapa reactivo de ventas
                    window.posSalesMap = window.posSalesMap || {};
                    window.posSalesMap[data.sale.id] = data.sale;

                    // 2. Insertar nueva fila en la tabla reactiva de inmediato
                    const emptyRow = document.getElementById('emptySalesRow');
                    if (emptyRow) emptyRow.remove();

                    const tableBody = document.getElementById('salesTableBody');
                    if (tableBody) {
                        const newRow = document.createElement('tr');
                        newRow.className = 'sale-row-item';
                        newRow.setAttribute('data-sale-id', data.sale.id);

                        const paymentBadge = `<span class="dash-badge-custom badge-green" style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;"><span>🎁</span> <span>Cortesía Adm</span></span>`;

                        newRow.innerHTML = `
                            <td>
                                <div>
                                    <span style="font-weight: 800; color: #10B981; font-family: monospace; font-size: 0.95rem; display: block;">
                                        ${data.receipt.receipt_number}
                                    </span>
                                    <small style="color: #94A3B8; font-size: 0.775rem; font-weight: 600;">
                                        ${data.receipt.created_at_formatted}
                                    </small>
                                </div>
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
                                    S/ 0.00
                                </strong>
                            </td>
                            <td>
                                ${paymentBadge}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="reprintReceipt(${data.sale.id})" title="Reimprimir Recibo Térmico" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: 1px solid rgba(255,85,0,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3); cursor: pointer;">
                                        <span>🧾</span>
                                        <span>Recibo</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="downloadPosSalePdf(${data.sale.id})" title="Descargar Entrada PDF" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: 1px solid rgba(6,182,212,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); cursor: pointer;">
                                        <span>🎟️</span>
                                        <span>Entrada PDF</span>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePosSale(${data.sale.id})" title="Borrar Entrada" style="background: linear-gradient(135deg, #EF4444, #DC2626); border: 1px solid rgba(239,68,68,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); cursor: pointer;">
                                        <span>🗑️</span>
                                        <span>Borrar Entrada</span>
                                    </button>
                                </div>
                            </td>
                        `;
                        tableBody.prepend(newRow);
                    }

                    // 3. Actualizar KPIs en vivo
                    if (data.metrics) {
                        const ticketsSoldEl = document.getElementById('kpiTicketsSold');
                        const remStockEl = document.getElementById('kpiRemainingStock');
                        const salesCountEl = document.getElementById('kpiSalesCount');

                        if (salesCountEl) salesCountEl.textContent = `${data.metrics.tickets_sold} ventas registradas`;
                        if (ticketsSoldEl) ticketsSoldEl.innerHTML = `${data.metrics.tickets_sold} <span style="font-size: 0.9rem; color: #94A3B8;">/ ${data.metrics.remaining_stock + data.metrics.tickets_sold}</span>`;
                        if (remStockEl) remStockEl.textContent = data.metrics.remaining_stock;

                        // Actualizar cards de stock en página y modal
                        if (data.metrics.zones) {
                            data.metrics.zones.forEach(z => {
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
                                    const availEl = pageCard.querySelector('.zone-available-count');
                                    if (availEl) {
                                        availEl.textContent = z.available;
                                        if (availEl.parentElement) {
                                            availEl.parentElement.style.color = z.available > 0 ? '#10B981' : '#EF4444';
                                        }
                                    }
                                }

                                const zoneModalCard = document.querySelector(`.zone-card-item[data-name="${z.name}"]:not(.courtesy-zone-card)`);
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
                                        }
                                    }
                                }

                                const courtesyZoneCard = document.querySelector(`.courtesy-zone-card[data-name="${z.name}"]`);
                                if (courtesyZoneCard) {
                                    const cAvail = (z.courtesy_available !== undefined) ? z.courtesy_available : z.available;
                                    const cEnabled = (z.courtesy_enabled !== undefined) ? z.courtesy_enabled : true;
                                    const cMaxStock = z.courtesy_max_stock || null;
                                    courtesyZoneCard.setAttribute('data-available', cAvail);
                                    const cModalBadge = courtesyZoneCard.querySelector('.zone-stock-badge');
                                    if (cModalBadge) {
                                        if (!cEnabled) {
                                            cModalBadge.className = 'zone-stock-badge sold-out';
                                            cModalBadge.textContent = '🚫 NO HABILITADA PARA CORTESÍA';
                                            courtesyZoneCard.classList.add('disabled');
                                        } else if (cAvail > 0) {
                                            cModalBadge.className = 'zone-stock-badge available';
                                            cModalBadge.textContent = `🎁 Cupo: ${cAvail} libres ${cMaxStock ? '(de ' + cMaxStock + ' asignados)' : '(aforo libre)'}`;
                                            courtesyZoneCard.classList.remove('disabled');
                                        } else {
                                            cModalBadge.className = 'zone-stock-badge sold-out';
                                            cModalBadge.textContent = '🚫 CUPO CORTESÍA AGOTADO';
                                            courtesyZoneCard.classList.add('disabled');
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // 4. Cerrar Modal Cortesía y resetear formulario
                    closePosCourtesyModal();
                    clearCourtesyClientSelection();
                    document.getElementById('posCourtesyForm')?.reset();
                    const chkAnon = document.getElementById('chkAnonymousCourtesy');
                    if (chkAnon) {
                        chkAnon.checked = false;
                        toggleAnonymousCourtesy(chkAnon);
                    }

                    // 5. Disparar impresión térmica segura
                    try {
                        printThermalReceipt(data.receipt, data.event);
                    } catch(errPrint) {
                        console.warn('[POS Thermal Print Error]', errPrint);
                    }

                    // 6. Modal flotante de confirmación y opciones rápidas
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cortesía Emitida con Éxito!',
                        html: `
                            <p style="color: #94A3B8; font-size: 0.95rem; margin-bottom: 1.25rem;">
                                Se generó el pase oficial <strong>${data.receipt.receipt_number}</strong> para <strong>${buyerName}</strong> (${quantity} entrada(s) de cortesía).
                            </p>
                            <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                                <button type="button" id="btnAlertPrintReceiptCourtesy" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #10B981, #059669); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                                    <span>🧾</span> Imprimir Recibo
                                </button>
                                <button type="button" id="btnAlertDownloadPdfCourtesy" class="btn btn-secondary btn-sm" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(6,182,212,0.3);">
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
                            const btnR = document.getElementById('btnAlertPrintReceiptCourtesy');
                            if (btnR) btnR.addEventListener('click', () => {
                                reprintReceipt(data.sale.id);
                            });
                            const btnP = document.getElementById('btnAlertDownloadPdfCourtesy');
                            if (btnP) btnP.addEventListener('click', () => {
                                downloadPosSalePdf(data.sale.id);
                            });
                        }
                    });

                } else {
                    Swal.fire({
                        title: 'Error al emitir cortesía',
                        text: data.message || 'No se pudo completar la emisión de cortesía.',
                        icon: 'error',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            })
            .catch(err => {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = '🎁 Emitir Cortesía & Imprimir';
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

        // Atajos de teclado: F1 para Venta POS, F2 para Cortesía, Escape para cerrar
        window.addEventListener('keydown', function (e) {
            if (e.key === 'F1') {
                e.preventDefault();
                openPosSaleModal();
            } else if (e.key === 'F2') {
                e.preventDefault();
                openPosCourtesyModal();
            } else if (e.key === 'Escape') {
                closePosSaleModal();
                closePosCourtesyModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Cerrar modales al hacer clic en el backdrop overlay
            const posModalOverlay = document.getElementById('posSaleModal');
            if (posModalOverlay) {
                posModalOverlay.addEventListener('click', function (e) {
                    if (e.target === this) closePosSaleModal();
                });
            }

            const posCourtesyOverlay = document.getElementById('posCourtesyModal');
            if (posCourtesyOverlay) {
                posCourtesyOverlay.addEventListener('click', function (e) {
                    if (e.target === this) closePosCourtesyModal();
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
