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
            height: 42px !important;
            min-height: 42px !important;
            padding: 0 0.85rem !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            line-height: 40px !important;
            border-radius: 10px !important;
            border: 1.5px solid rgba(255, 255, 255, 0.15) !important;
            box-sizing: border-box !important;
            display: block !important;
            width: 100% !important;
            outline: none !important;
            cursor: pointer !important;
        }
        #pos_courtesy_note {
            border-color: rgba(16, 185, 129, 0.45) !important;
            background-color: #14141E !important;
            color: #FFFFFF !important;
            font-weight: 700 !important;
        }
        #pos_courtesy_note:focus {
            border-color: #10B981 !important;
            box-shadow: 0 0 14px rgba(16, 185, 129, 0.4) !important;
        }
        select.form-input-custom option,
        select.form-select-custom option,
        #pos_courtesy_note option,
        #pos_zone_select option {
            background-color: #14141E !important;
            color: #FFFFFF !important;
            padding: 10px 14px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
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

        /* GRID DE 3 COLUMNAS DEL MODAL POS */
        .pos-modal-three-columns {
            display: grid;
            grid-template-columns: 1fr 1.08fr 1fr;
            gap: 1.15rem;
            align-items: start;
            margin-bottom: 1.1rem;
        }

        @keyframes pulseGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 85, 0, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(255, 85, 0, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 85, 0, 0);
            }
        }

        @keyframes pulseGlowGreen {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        @media (max-width: 980px) {
            .pos-modal-three-columns {
                grid-template-columns: 1fr !important;
                gap: 1.15rem !important;
            }
        }

        @media (max-width: 860px) {
            #posSaleModal .admin-modal-card,
            #posCourtesyModal .admin-modal-card {
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

        /* ESTILOS DE BUTACAS INTERACTIVAS EN MODAL POS */
        .pos-seat-rect {
            cursor: pointer;
            transition: fill 0.15s ease, stroke 0.15s ease, filter 0.15s ease;
        }

        .pos-seat-rect:hover {
            filter: drop-shadow(0 0 6px rgba(255, 85, 0, 0.9));
            stroke: #FF5500 !important;
            stroke-width: 2px !important;
        }

        .pos-seat-rect.selected {
            fill: #FF5500 !important;
            stroke: #FFFFFF !important;
            stroke-width: 2px !important;
            filter: drop-shadow(0 0 8px #FF5500) !important;
        }

        .pos-seat-rect.occupied {
            fill: #EF4444 !important;
            stroke: #DC2626 !important;
            cursor: not-allowed !important;
            opacity: 0.85;
        }

        .pos-seat-rect.occupied:hover {
            filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.9));
            stroke: #991B1B !important;
        }

        .pos-seat-chip {
            background: rgba(255, 85, 0, 0.12);
            border: 1px solid rgba(255, 85, 0, 0.4);
            color: #FF5500;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            animation: popInChip 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popInChip {
            0% { transform: scale(0.7); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
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
                                @if(($event->sales_type ?? 'fisica') === 'ambos')
                                    <span class="dash-badge-custom badge-purple" style="font-size: 0.75rem; background: rgba(168, 85, 247, 0.15); color: #C084FC; border: 1px solid rgba(168, 85, 247, 0.4);">🎫🌐 Venta Híbrida (Taquilla + Online)</span>
                                @elseif(($event->sales_type ?? 'fisica') === 'fisica')
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

                    <!-- BOTONES PRINCIPALES DE TAQUILLA (VENTA + CORTESÍA O PLANCHA) -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                        <button type="button" class="btn btn-primary" onclick="openPosSaleModal()" style="font-size: 1rem; font-weight: 800; padding: 0.85rem 1.6rem; border-radius: 14px; box-shadow: 0 6px 22px rgba(255, 85, 0, 0.45); display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer;">
                            <span style="font-size: 1.25rem;">🛒</span>
                            <span>+ REGISTRAR VENTA (F1)</span>
                        </button>
                        @if(in_array(($event->sales_type ?? 'fisica'), ['fisica', 'ambos']))
                            <button type="button" class="btn" onclick="openPlanchaModalCurrentEvent()" style="background: linear-gradient(135deg, #2563EB, #1D4ED8); color: #FFFFFF; font-size: 1rem; font-weight: 800; padding: 0.85rem 1.6rem; border-radius: 14px; box-shadow: 0 6px 22px rgba(37, 99, 235, 0.4); display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer; border: none; transition: all 0.2s ease;">
                                <span style="font-size: 1.25rem;">🖨️</span>
                                <span>+ GENERAR PLANCHA PDF</span>
                            </button>
                        @endif
                        @php
                            $cSettings = is_array($event->courtesy_settings) 
                                ? $event->courtesy_settings 
                                : (json_decode($event->courtesy_settings ?? '[]', true) ?? []);
                            $isCourtesyActive = !empty($cSettings['enabled']);
                        @endphp
                        @if($isCourtesyActive)
                            <button type="button" class="btn" onclick="openPosCourtesyModal()" style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-size: 1rem; font-weight: 800; padding: 0.85rem 1.6rem; border-radius: 14px; box-shadow: 0 6px 22px rgba(16, 185, 129, 0.4); display: inline-flex; align-items: center; gap: 0.55rem; cursor: pointer; border: none; transition: all 0.2s ease;">
                                <span style="font-size: 1.25rem;">🎁</span>
                                <span>+ NUEVA CORTESÍA (F2)</span>
                            </button>
                        @endif
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
                                                <button type="button" class="btn btn-info btn-sm" onclick="emailPosSalePdf({{ $sale->id }})" title="Enviar Entrada al Correo" style="background: linear-gradient(135deg, #6366F1, #4F46E5); border: 1px solid rgba(99,102,241,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); cursor: pointer;">
                                                    <span>✉️</span>
                                                    <span>Enviar Correo</span>
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

    <!-- MODAL PUNTO DE VENTA (REGISTRAR VENTA POS - 3 COLUMNAS COMPACTO) -->
    <div class="admin-modal-overlay" id="posSaleModal">
        <div class="admin-modal-card" style="max-width: 1160px; width: 96%; max-height: 92vh; overflow-y: auto; padding: 1.5rem 1.65rem; border-radius: 24px; box-sizing: border-box; border: 1.5px solid rgba(255, 85, 0, 0.35); box-shadow: 0 20px 60px rgba(0,0,0,0.7), 0 0 30px rgba(255,85,0,0.1);">
            
            <div class="admin-modal-header" style="margin-bottom: 1rem; padding-bottom: 0.65rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
                    <div class="card-header-icon" style="width: 36px; height: 36px; background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange); display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.15rem; flex-shrink: 0;">🛒</div>
                    <div style="min-width: 0; flex: 1;">
                        <h3 class="card-header-title" style="font-size: 1.05rem; margin: 0; color: #FFFFFF; font-weight: 900; line-height: 1.2;">Nueva Venta de Taquilla (POS)</h3>
                        <p class="card-header-subtitle" style="margin: 0; font-size: 0.775rem; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $event->title }} · Emisión inmediata de boletos</p>
                    </div>
                </div>
                <button type="button" class="admin-modal-close" onclick="closePosSaleModal()" style="font-size: 1.2rem; color: #94A3B8; background: transparent; border: none; cursor: pointer; flex-shrink: 0; padding: 0.2rem 0.4rem;" aria-label="Cerrar">✕</button>
            </div>

            <form id="posSaleForm" onsubmit="handlePosSaleSubmit(event)">
                <!-- GRID DE 3 COLUMNAS PRINCIPALES -->
                <div class="pos-modal-three-columns">
                    
                    <!-- COLUMNA 1: SECTOR / ZONA + CANTIDAD + TOTAL A COBRAR -->
                    <div>
                        <div style="font-size: 0.78rem; font-weight: 800; color: var(--color-primary-orange); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.55rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span>🎟️</span> <span>1. Sector & Cantidad</span>
                        </div>

                        <!-- SECTORES / ZONAS EN CARDS INTERACTIVAS COMPACTAS -->
                        <div class="form-group-custom" style="margin-bottom: 0.75rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.45rem; max-height: 210px; overflow-y: auto; padding-right: 0.2rem;" id="zoneCardsContainer">
                                @foreach($zonesWithStats as $index => $z)
                                    <div class="zone-card-item {{ $index === 0 && $z['available'] > 0 ? 'active' : '' }} {{ $z['available'] <= 0 ? 'disabled' : '' }}"
                                         data-name="{{ $z['name'] }}"
                                         data-price="{{ $z['price'] }}"
                                         data-available="{{ $z['available'] }}"
                                         onclick="selectZoneCard('{{ addslashes($z['name']) }}', {{ $z['price'] }}, {{ $z['available'] }}, this)"
                                         style="padding: 0.55rem 0.75rem; border-radius: 12px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem;">
                                            <div style="display: flex; align-items: center; gap: 0.55rem; min-width: 0; flex: 1;">
                                                <div class="zone-radio-indicator" style="width: 14px; height: 14px;"></div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <strong class="zone-card-name" style="font-size: 0.85rem; word-break: break-word;">{{ $z['name'] }}</strong>
                                                    <div style="margin-top: 0.1rem;">
                                                        @if($z['available'] > 0)
                                                            <span class="zone-stock-badge available" style="font-size: 0.65rem; padding: 0.1rem 0.35rem;">📦 {{ number_format($z['available']) }} libres</span>
                                                        @else
                                                            <span class="zone-stock-badge sold-out" style="font-size: 0.65rem; padding: 0.1rem 0.35rem;">🚫 AGOTADO</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="text-align: right; flex-shrink: 0;">
                                                <span class="zone-card-price" style="font-size: 0.95rem; white-space: nowrap;">S/ {{ number_format($z['price'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Input oculto para guardar el sector seleccionado -->
                            <input type="hidden" id="pos_zone_select" value="{{ $zonesWithStats[0]['name'] ?? '' }}" required>
                        </div>

                        <!-- SELECTOR INTERACTIVO DE BUTACAS NUMERADAS EN MODAL POS -->
                        <div id="posSeatSelectorContainer" style="display: none; margin-bottom: 0.75rem; background: rgba(255,255,255,0.02); border: 1.5px solid rgba(255, 85, 0, 0.35); border-radius: 14px; padding: 0.65rem 0.75rem; box-shadow: inset 0 2px 8px rgba(0,0,0,0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                                <div style="font-size: 0.75rem; font-weight: 800; color: var(--color-primary-orange); display: flex; align-items: center; gap: 0.35rem;">
                                    <span>🪑</span> <span id="posSeatSelectorTitle">Selección de Butacas</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.68rem; font-weight: 700; color: #94A3B8;">
                                    <span style="display: inline-flex; align-items: center; gap: 0.2rem;"><span style="display: inline-block; width: 8px; height: 8px; border-radius: 2px; background: #10B981;"></span> Libre</span>
                                    <span style="display: inline-flex; align-items: center; gap: 0.2rem;"><span style="display: inline-block; width: 8px; height: 8px; border-radius: 2px; background: #EF4444;"></span> Ocupada</span>
                                    <span style="display: inline-flex; align-items: center; gap: 0.2rem;"><span style="display: inline-block; width: 8px; height: 8px; border-radius: 2px; background: #FF5500;"></span> Elegida</span>
                                </div>
                            </div>

                            <!-- Lienzo SVG del Plano de Butacas Auto-Ajustable -->
                            <div id="posSeatMapCanvasLayer" style="position: relative; width: 100%; height: 210px; background: #FFFFFF; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center;">
                                <svg id="posSeatMapSvg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: visible;" preserveAspectRatio="xMidYMid meet"></svg>
                            </div>

                            <!-- Tooltip Flotante del Mapa POS -->
                            <div id="posZoneTooltip" style="position: absolute; display: none; z-index: 50; pointer-events: none; background: #0F172A; border: 1.5px solid rgba(255, 85, 0, 0.6); border-radius: 8px; padding: 0.35rem 0.6rem; color: #FFFFFF; box-shadow: 0 10px 25px rgba(0,0,0,0.4); transform: translate(-50%, -120%); font-size: 0.72rem; white-space: nowrap;">
                                <div id="posTooltipTitle" style="font-weight: 800; color: #FF8800;"></div>
                                <div id="posTooltipStatus" style="font-size: 0.68rem; color: #10B981; font-weight: 700;"></div>
                            </div>

                            <!-- Chips de Butacas Seleccionadas -->
                            <div style="margin-top: 0.45rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                    <span style="font-size: 0.68rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Butacas Elegidas:</span>
                                    <button type="button" onclick="clearPosSelectedSeats()" style="background: none; border: none; color: #EF4444; font-size: 0.68rem; font-weight: 800; cursor: pointer; padding: 0; display: none;" id="btnPosClearSeats">
                                        ✕ Desmarcar todas
                                    </button>
                                </div>
                                <div id="posSelectedSeatsTagsBox" style="display: flex; flex-wrap: wrap; gap: 0.3rem; align-items: center; min-height: 22px;">
                                    <span style="font-size: 0.7rem; color: #94A3B8;">👈 Haz clic en una o más butacas en el plano</span>
                                </div>
                            </div>
                        </div>

                        <!-- CANTIDAD DE ENTRADAS CON BOTONES NARANJAS Y QUICK PILLS -->
                        <div class="form-group-custom" style="margin-bottom: 0.75rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 0.65rem 0.75rem; border-radius: 14px;">
                            <label class="form-label-custom" style="margin: 0 0 0.35rem 0; font-size: 0.775rem;">🎫 Cantidad de Boletos <span class="required-star">*</span></label>
                            <div class="pos-quick-qty-pills" style="display: flex; gap: 0.25rem; width: 100%; margin-bottom: 0.45rem;">
                                <button type="button" class="pos-quick-btn active" id="btnQuickQty1" onclick="setPosQuantity(1)" style="height: 30px; font-size: 0.75rem;">1</button>
                                <button type="button" class="pos-quick-btn" id="btnQuickQty2" onclick="setPosQuantity(2)" style="height: 30px; font-size: 0.75rem;">2</button>
                                <button type="button" class="pos-quick-btn" id="btnQuickQty3" onclick="setPosQuantity(3)" style="height: 30px; font-size: 0.75rem;">3</button>
                                <button type="button" class="pos-quick-btn" id="btnQuickQty4" onclick="setPosQuantity(4)" style="height: 30px; font-size: 0.75rem;">4</button>
                                <button type="button" class="pos-quick-btn" id="btnQuickQty5" onclick="setPosQuantity(5)" style="height: 30px; font-size: 0.75rem;">5</button>
                                <button type="button" class="pos-quick-btn" id="btnQuickQty10" onclick="setPosQuantity(10)" style="height: 30px; font-size: 0.75rem;">10</button>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                                <button type="button" class="pos-stepper-btn orange-btn" onclick="stepPosQuantity(-1)" title="Restar" style="width: 32px; height: 32px; font-size: 1.1rem;">-</button>
                                <input type="number" id="pos_quantity" class="form-input-custom pos-stepper-input" value="1" min="1" max="50" required oninput="calculatePosTotal()" style="width: 65px; height: 34px; font-size: 1.1rem;">
                                <button type="button" class="pos-stepper-btn orange-btn" onclick="stepPosQuantity(1)" title="Sumar" style="width: 32px; height: 32px; font-size: 1.1rem;">+</button>
                            </div>
                        </div>

                        <!-- TOTAL A PAGAR RESALTADO PRO COMPACTO -->
                        <div class="pos-total-summary-card" style="padding: 0.7rem 0.9rem; border-radius: 14px;">
                            <div>
                                <span style="font-size: 0.68rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Total a Cobrar</span>
                                <small style="color: #FFFFFF; font-weight: 600; font-size: 0.775rem;" id="posUnitPriceDesc">1 entrada x S/ 0.00</small>
                            </div>
                            <div style="font-size: 1.45rem; font-weight: 900; color: #10B981; text-shadow: 0 2px 12px rgba(16, 185, 129, 0.3); text-align: right;" id="posTotalAmountDisplay">
                                S/ 0.00
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 2: DATOS DEL CLIENTE / COMPRADOR + AUTOCOMPLETADO DNI -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.55rem;">
                            <div style="font-size: 0.78rem; font-weight: 800; color: var(--color-primary-orange); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.35rem;">
                                <span>👤</span> <span>2. Datos del Cliente</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <button type="button" 
                                        onclick="clearPosClientFields()" 
                                        title="Limpiar todos los datos del cliente" 
                                        style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #EF4444; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; transition: all 0.2s ease;">
                                    <span>🗑️</span> <span>Limpiar</span>
                                </button>
                                <label style="display: flex; align-items: center; gap: 0.35rem; cursor: pointer; font-size: 0.7rem; font-weight: 800; color: var(--color-primary-orange); background: rgba(255,85,0,0.1); padding: 0.2rem 0.5rem; border-radius: 8px; border: 1px solid rgba(255,85,0,0.3); user-select: none;">
                                    <input type="checkbox" id="chkAnonymousBuyer" onchange="toggleAnonymousBuyer(this)" style="cursor: pointer; width: 13px; height: 13px; accent-color: #FF5500;">
                                    <span>Sin Datos</span>
                                </label>
                            </div>
                        </div>

                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 0.85rem; border-radius: 14px;">
                            <!-- DNI CON BOTÓN "TRAER DATOS" CUANDO SE DETECTA CLIENTE -->
                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                    <label for="pos_buyer_dni" class="form-label-custom" style="margin: 0; font-size: 0.775rem;">
                                        DNI / Documento <span class="required-star" id="star_buyer_dni">*</span>
                                    </label>
                                    <!-- Botón Desbloqueado "Traer Datos" cuando coincide con cliente registrado -->
                                    <button type="button" 
                                            id="btnFetchClientDni" 
                                            onclick="fetchClientDataFromDni()" 
                                            style="display: none; background: linear-gradient(135deg, #FF5500, #FF7733); border: none; color: #FFFFFF; font-size: 0.68rem; font-weight: 800; padding: 0.18rem 0.55rem; border-radius: 6px; cursor: pointer; align-items: center; gap: 0.3rem; box-shadow: 0 2px 8px rgba(255,85,0,0.4); animation: pulseGlow 1.5s infinite;">
                                        <span>⚡</span> <span>Traer Datos</span>
                                    </button>
                                </div>
                                <input type="text" id="pos_buyer_dni" class="form-input-custom" placeholder="Ej: 72819203" required style="font-weight: 700; font-size: 0.825rem; height: 36px; letter-spacing: 0.5px;" oninput="onDniInputPosSale(this.value)">
                                <!-- Aviso visual cuando se detecta el cliente -->
                                <div id="dniClientFoundBadge" style="display: none; font-size: 0.68rem; color: #10B981; font-weight: 700; margin-top: 0.25rem;">
                                    ✓ Cliente encontrado: <span id="dniClientFoundName" style="color: #FFFFFF;"></span>
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <label for="pos_buyer_name" class="form-label-custom" style="margin-bottom: 0.25rem; font-size: 0.775rem;">Nombre Completo <span class="required-star" id="star_buyer_name">*</span></label>
                                <input type="text" id="pos_buyer_name" class="form-input-custom" placeholder="Ej: Juan Pérez Morales" required style="font-weight: 600; font-size: 0.825rem; height: 36px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <label for="pos_buyer_email" class="form-label-custom" style="margin-bottom: 0.25rem; font-size: 0.775rem;">Correo Electrónico (Opcional - Enviar Entrada)</label>
                                <input type="email" id="pos_buyer_email" class="form-input-custom" placeholder="Ej: cliente@correo.com" style="font-size: 0.8rem; height: 36px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0;">
                                <label for="pos_buyer_phone" class="form-label-custom" style="margin-bottom: 0.25rem; font-size: 0.775rem;">Teléfono / WhatsApp (Opcional)</label>
                                <input type="text" id="pos_buyer_phone" class="form-input-custom" placeholder="Ej: +51 987654321" style="font-size: 0.8rem; height: 36px;">
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 3: MÉTODO DE PAGO (SOLO EFECTIVO Y CULQI) + CALCULADORA DE VUELTO -->
                    <div>
                        <div style="font-size: 0.78rem; font-weight: 800; color: var(--color-primary-orange); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.55rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span>💳</span> <span>3. Método de Pago</span>
                        </div>

                        <!-- MÉTODO DE PAGO CON PILLS: SOLO EFECTIVO Y CULQI -->
                        <div class="form-group-custom" style="margin-bottom: 0.75rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.45rem;" id="paymentMethodsGroup">
                                <div class="payment-method-pill active" onclick="selectPaymentMethod('Efectivo', this)" style="padding: 0.55rem 0.5rem; font-size: 0.825rem; border-radius: 10px;">
                                    <span>💵</span> <span>Efectivo</span>
                                </div>
                                <div class="payment-method-pill" onclick="selectPaymentMethod('Culqi', this)" style="padding: 0.55rem 0.5rem; font-size: 0.825rem; border-radius: 10px;">
                                    <span>💳</span> <span>Culqi</span>
                                </div>
                            </div>
                            <input type="hidden" id="pos_payment_method" value="Efectivo">
                        </div>

                        <!-- CALCULADORA DE VUELTO / CAMBIO (SOLO EN EFECTIVO) -->
                        <div id="cashCalculatorBox" style="background: rgba(255,255,255,0.02); border: 1.5px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem; margin-bottom: 0.75rem;">
                            <label class="form-label-custom" style="margin: 0 0 0.3rem 0; font-size: 0.775rem;">💵 Monto Recibido <span class="required-star">*</span></label>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.25rem; margin-bottom: 0.45rem;">
                                <button type="button" class="pos-quick-btn" onclick="setCashPaid(10)" style="height: 28px; font-size: 0.725rem; min-width: 0;">S/ 10</button>
                                <button type="button" class="pos-quick-btn" onclick="setCashPaid(20)" style="height: 28px; font-size: 0.725rem; min-width: 0;">S/ 20</button>
                                <button type="button" class="pos-quick-btn" onclick="setCashPaid(50)" style="height: 28px; font-size: 0.725rem; min-width: 0;">S/ 50</button>
                                <button type="button" class="pos-quick-btn" onclick="setCashPaid(100)" style="height: 28px; font-size: 0.725rem; min-width: 0;">S/ 100</button>
                                <button type="button" class="pos-quick-btn" onclick="setCashPaid(200)" style="height: 28px; font-size: 0.725rem; min-width: 0;">S/ 200</button>
                                <button type="button" class="pos-quick-btn" onclick="setCashPaid('exact')" style="height: 28px; font-size: 0.725rem; min-width: 0;">Exacto</button>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                <div>
                                    <input type="number" id="pos_amount_paid" class="form-input-custom" step="0.50" min="0" placeholder="0.00" style="font-size: 1.05rem; font-weight: 800; height: 35px;" oninput="calculateChange()">
                                </div>
                                <div style="background: rgba(0,0,0,0.45); padding: 0.45rem 0.65rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 0.68rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Vuelto:</span>
                                    <strong style="font-size: 1.05rem; font-weight: 900; color: #10B981;" id="posChangeAmountDisplay">S/ 0.00</strong>
                                </div>
                            </div>
                        </div>

                        <!-- TARJETA INFORMATIVA COMPACTA -->
                        <div style="background: rgba(255,85,0,0.04); border: 1px solid rgba(255,85,0,0.18); border-radius: 12px; padding: 0.55rem 0.7rem; font-size: 0.725rem; color: #94A3B8;">
                            <div style="display: flex; align-items: center; gap: 0.35rem; color: var(--color-primary-orange); font-weight: 800; margin-bottom: 0.15rem;">
                                <span>⚡</span> <span>Emisión Inmediata</span>
                            </div>
                            <div>Generación en vivo de recibo térmico y código QR de seguridad.</div>
                        </div>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN DEL FOOTER -->
                <div class="pos-modal-footer-actions" style="display: flex; gap: 0.6rem; justify-content: flex-end; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePosSaleModal()" style="padding: 0.55rem 1.15rem; font-size: 0.85rem; font-weight: 700; border-radius: 10px;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitPosSale" class="btn btn-primary btn-save-settings" style="padding: 0.55rem 1.45rem; font-size: 0.875rem; font-weight: 900; border-radius: 10px; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.4);">
                        🧾 Confirmar Venta & Imprimir
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- MODAL PUNTO DE VENTA (NUEVA CORTESÍA POS EN 3 COLUMNAS COMPACTAS) -->
    <div class="admin-modal-overlay" id="posCourtesyModal">
        <div class="admin-modal-card" style="max-width: 1160px; width: 96%; max-height: 92vh; overflow-y: auto; padding: 1.4rem 1.6rem; border-radius: 24px; box-sizing: border-box; border: 1.5px solid rgba(16, 185, 129, 0.4); box-shadow: 0 20px 60px rgba(0,0,0,0.7), 0 0 30px rgba(16,185,129,0.12);">
            
            <div class="admin-modal-header" style="margin-bottom: 1rem; padding-bottom: 0.65rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0; flex: 1;">
                    <div class="card-header-icon" style="width: 36px; height: 36px; background: rgba(16, 185, 129, 0.15); border: 1.5px solid rgba(16, 185, 129, 0.35); color: #10B981; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.15rem; flex-shrink: 0;">🎁</div>
                    <div style="min-width: 0; flex: 1;">
                        <h3 class="card-header-title" style="font-size: 1.05rem; margin: 0; color: #FFFFFF; font-weight: 900; line-height: 1.2;">Nueva Entrada de Cortesía (Pase Free / Invitados)</h3>
                        <p class="card-header-subtitle" style="margin: 0; font-size: 0.75rem; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $event->title }} · Emisión oficial autorizada a costo S/ 0.00</p>
                    </div>
                </div>
                <button type="button" class="admin-modal-close" onclick="closePosCourtesyModal()" style="font-size: 1.2rem; color: #94A3B8; background: transparent; border: none; cursor: pointer; flex-shrink: 0; padding: 0.2rem 0.4rem;" aria-label="Cerrar">✕</button>
            </div>

            <form id="posCourtesyForm" onsubmit="handlePosCourtesySubmit(event)">
                <!-- GRID DE 3 COLUMNAS PRINCIPALES -->
                <div class="pos-modal-three-columns">
                    
                    <!-- COLUMNA 1: SELECCIÓN DE SECTORES / ZONAS + CANTIDAD + TOTAL S/ 0.00 -->
                    <div>
                        <div style="font-size: 0.78rem; font-weight: 800; color: #10B981; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.55rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span>🎟️</span> <span>1. Sector de Cortesía</span>
                        </div>

                        <!-- SECTORES / ZONAS EN CARDS INTERACTIVAS DE CORTESÍA -->
                        <div class="form-group-custom" style="margin-bottom: 0.75rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.45rem; max-height: 205px; overflow-y: auto; padding-right: 0.2rem;" id="courtesyZoneCardsContainer">
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
                                         onclick="selectCourtesyZoneCard('{{ addslashes($z['name']) }}', {{ $cAvail }}, this)"
                                         style="padding: 0.55rem 0.75rem; border-radius: 12px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem;">
                                            <div style="display: flex; align-items: center; gap: 0.55rem; min-width: 0; flex: 1;">
                                                <div class="zone-radio-indicator" style="width: 14px; height: 14px; border-color: #10B981;"></div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <strong class="zone-card-name" style="font-size: 0.85rem; word-break: break-word;">{{ $z['name'] }}</strong>
                                                    <div style="margin-top: 0.1rem;">
                                                        @if(!$cEnabled)
                                                            <span class="zone-stock-badge sold-out" style="font-size: 0.65rem; padding: 0.1rem 0.35rem;">🚫 DESHABILITADA</span>
                                                        @elseif($cAvail > 0)
                                                            <span class="zone-stock-badge available" style="font-size: 0.65rem; padding: 0.1rem 0.35rem;">🎁 {{ number_format($cAvail) }} libres</span>
                                                        @else
                                                            <span class="zone-stock-badge sold-out" style="font-size: 0.65rem; padding: 0.1rem 0.35rem;">🚫 AGOTADO</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="text-align: right; flex-shrink: 0;">
                                                <span style="display: inline-block; background: rgba(16,185,129,0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.35); font-weight: 800; font-size: 0.72rem; padding: 0.15rem 0.45rem; border-radius: 6px;">FREE</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="pos_courtesy_zone_select" value="{{ $zonesWithStats[$firstActiveIndex ?? 0]['name'] ?? ($zonesWithStats[0]['name'] ?? '') }}" required>
                        </div>

                        <!-- CANTIDAD DE ENTRADAS DE CORTESÍA -->
                        <div class="form-group-custom" style="margin-bottom: 0.75rem; background: rgba(16, 185, 129, 0.02); border: 1px solid rgba(16, 185, 129, 0.15); padding: 0.65rem 0.75rem; border-radius: 14px;">
                            <label class="form-label-custom" style="margin: 0 0 0.35rem 0; font-size: 0.775rem; color: #10B981;">🎁 Cantidad de Pases <span class="required-star">*</span></label>
                            <div class="pos-quick-qty-pills" style="display: flex; gap: 0.25rem; width: 100%; margin-bottom: 0.45rem;">
                                <button type="button" class="pos-quick-btn active" id="btnCourtesyQuickQty1" onclick="setCourtesyQuantity(1)" style="height: 30px; font-size: 0.75rem;">1</button>
                                <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty2" onclick="setCourtesyQuantity(2)" style="height: 30px; font-size: 0.75rem;">2</button>
                                <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty3" onclick="setCourtesyQuantity(3)" style="height: 30px; font-size: 0.75rem;">3</button>
                                <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty4" onclick="setCourtesyQuantity(4)" style="height: 30px; font-size: 0.75rem;">4</button>
                                <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty5" onclick="setCourtesyQuantity(5)" style="height: 30px; font-size: 0.75rem;">5</button>
                                <button type="button" class="pos-quick-btn" id="btnCourtesyQuickQty10" onclick="setCourtesyQuantity(10)" style="height: 30px; font-size: 0.75rem;">10</button>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                                <button type="button" class="pos-stepper-btn" onclick="stepCourtesyQuantity(-1)" title="Restar" style="width: 32px; height: 32px; font-size: 1.1rem; background: #10B981; color: #FFFFFF; border: none; border-radius: 10px; font-weight: 900; cursor: pointer;">-</button>
                                <input type="number" id="pos_courtesy_quantity" class="form-input-custom pos-stepper-input" value="1" min="1" max="100" required style="border-color: rgba(16, 185, 129, 0.4); color: #10B981; width: 65px; height: 34px; font-size: 1.1rem;" oninput="updateCourtesySummary()">
                                <button type="button" class="pos-stepper-btn" onclick="stepCourtesyQuantity(1)" title="Sumar" style="width: 32px; height: 32px; font-size: 1.1rem; background: #10B981; color: #FFFFFF; border: none; border-radius: 10px; font-weight: 900; cursor: pointer;">+</button>
                            </div>
                        </div>

                        <!-- TOTAL CORTESÍA RESALTADO -->
                        <div class="pos-total-summary-card" style="background: rgba(16, 185, 129, 0.08); border: 2px solid #10B981; padding: 0.7rem 0.9rem; border-radius: 14px; box-shadow: 0 4px 18px rgba(16, 185, 129, 0.2);">
                            <div>
                                <span style="font-size: 0.68rem; font-weight: 800; color: #10B981; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Costo de Emisión</span>
                                <small style="color: #FFFFFF; font-weight: 600; font-size: 0.775rem;" id="courtesyQuantityDesc">1 entrada(s) de Cortesía (Free)</small>
                            </div>
                            <div style="font-size: 1.45rem; font-weight: 900; color: #10B981; text-shadow: 0 2px 12px rgba(16, 185, 129, 0.4); text-align: right;">
                                S/ 0.00
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 2: DATOS DEL BENEFICIARIO / INVITADO -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.55rem;">
                            <div style="font-size: 0.78rem; font-weight: 800; color: #10B981; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.35rem;">
                                <span>👤</span> <span>2. Datos del Invitado</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <button type="button" 
                                        onclick="clearPosCourtesyFields()" 
                                        title="Limpiar datos del invitado" 
                                        style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #EF4444; font-size: 0.68rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; transition: all 0.2s ease;">
                                    <span>🗑️</span> <span>Limpiar</span>
                                </button>
                                <label style="display: flex; align-items: center; gap: 0.35rem; cursor: pointer; font-size: 0.7rem; font-weight: 800; color: #10B981; background: rgba(16,185,129,0.1); padding: 0.2rem 0.5rem; border-radius: 8px; border: 1px solid rgba(16,185,129,0.3); user-select: none;">
                                    <input type="checkbox" id="chkAnonymousCourtesy" onchange="toggleAnonymousCourtesy(this)" style="cursor: pointer; width: 13px; height: 13px; accent-color: #10B981;">
                                    <span>Sin Datos</span>
                                </label>
                            </div>
                        </div>

                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 0.85rem; border-radius: 14px;">
                            <!-- DNI CON BOTÓN "TRAER DATOS" -->
                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                    <label for="pos_courtesy_dni" class="form-label-custom" style="margin: 0; font-size: 0.775rem;">
                                        DNI / Documento <span class="required-star" id="star_courtesy_dni">*</span>
                                    </label>
                                    <!-- Botón Desbloqueado "Traer Datos" cuando coincide con cliente registrado -->
                                    <button type="button" 
                                            id="btnFetchCourtesyClientDni" 
                                            onclick="fetchCourtesyClientDataFromDni()" 
                                            style="display: none; background: linear-gradient(135deg, #10B981, #059669); border: none; color: #FFFFFF; font-size: 0.68rem; font-weight: 800; padding: 0.18rem 0.55rem; border-radius: 6px; cursor: pointer; align-items: center; gap: 0.3rem; box-shadow: 0 2px 8px rgba(16,185,129,0.4); animation: pulseGlowGreen 1.5s infinite;">
                                        <span>⚡</span> <span>Traer Datos</span>
                                    </button>
                                </div>
                                <input type="text" id="pos_courtesy_dni" class="form-input-custom" placeholder="Ej: 72819203" required style="font-weight: 700; font-size: 0.825rem; height: 36px; letter-spacing: 0.5px;" oninput="onDniInputPosCourtesy(this.value)">
                                <!-- Aviso visual cuando se detecta el cliente -->
                                <div id="dniCourtesyFoundBadge" style="display: none; font-size: 0.68rem; color: #10B981; font-weight: 700; margin-top: 0.25rem;">
                                    ✓ Cliente encontrado: <span id="dniCourtesyFoundName" style="color: #FFFFFF;"></span>
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <label for="pos_courtesy_name" class="form-label-custom" style="margin-bottom: 0.25rem; font-size: 0.775rem;">Nombre Completo del Invitado <span class="required-star" id="star_courtesy_name">*</span></label>
                                <input type="text" id="pos_courtesy_name" class="form-input-custom" placeholder="Ej: Juan Pérez Morales" required style="font-weight: 600; font-size: 0.825rem; height: 36px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <label for="pos_courtesy_email" class="form-label-custom" style="margin-bottom: 0.25rem; font-size: 0.775rem;">Correo Electrónico (Opcional - Enviar Pase)</label>
                                <input type="email" id="pos_courtesy_email" class="form-input-custom" placeholder="Ej: invitado@correo.com" style="font-size: 0.8rem; height: 36px;">
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 0;">
                                <label for="pos_courtesy_phone" class="form-label-custom" style="margin-bottom: 0.25rem; font-size: 0.775rem;">Teléfono / WhatsApp (Opcional)</label>
                                <input type="text" id="pos_courtesy_phone" class="form-input-custom" placeholder="Ej: +51 987654321" style="font-size: 0.8rem; height: 36px;">
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA 3: MOTIVO / AUTORIZACIÓN DE CORTESÍA -->
                    <div>
                        <div style="font-size: 0.78rem; font-weight: 800; color: #10B981; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.55rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span>🎁</span> <span>3. Motivo & Emisión</span>
                        </div>

                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); padding: 0.85rem; border-radius: 14px; margin-bottom: 0.75rem;">
                            <div class="form-group-custom" style="margin-bottom: 0.65rem;">
                                <label for="pos_courtesy_note" class="form-label-custom" style="margin-bottom: 0.35rem; font-size: 0.775rem;">Motivo / Tipo de Cortesía <span class="required-star">*</span></label>
                                <select id="pos_courtesy_note" class="form-input-custom" style="font-size: 0.85rem !important; font-weight: 700 !important; height: 42px !important; min-height: 42px !important; padding: 0 0.85rem !important; line-height: 40px !important; background-color: #14141E !important; color: #FFFFFF !important; border: 1.5px solid rgba(16, 185, 129, 0.45) !important; border-radius: 10px !important; width: 100% !important; box-sizing: border-box !important;">
                                    <option value="Cortesía Directa" selected style="background-color: #14141E; color: #FFFFFF;">🎁 Cortesía Administrador</option>
                                    <option value="Invitado Especial" style="background-color: #14141E; color: #FFFFFF;">⭐ Invitado Especial / VIP</option>
                                    <option value="Prensa / Medios" style="background-color: #14141E; color: #FFFFFF;">📰 Prensa / Medios</option>
                                    <option value="Auspiciador / Sponsor" style="background-color: #14141E; color: #FFFFFF;">🤝 Auspiciador / Patrocinador</option>
                                    <option value="Staff / Producción" style="background-color: #14141E; color: #FFFFFF;">🛠️ Staff / Producción</option>
                                </select>
                            </div>

                            <div style="background: rgba(16, 185, 129, 0.08); border: 1px dashed rgba(16, 185, 129, 0.35); border-radius: 12px; padding: 0.65rem 0.75rem; font-size: 0.725rem; color: #94A3B8;">
                                <div style="display: flex; align-items: center; gap: 0.35rem; color: #10B981; font-weight: 800; margin-bottom: 0.15rem;">
                                    <span>🛡️</span> <span>Emisión 100% Gratuita</span>
                                </div>
                                <div>Pase oficial con QR de seguridad, sin costo de cobro ni caja.</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- BOTONES DE ACCIÓN DEL FOOTER CORTESÍA -->
                <div class="pos-modal-footer-actions" style="display: flex; gap: 0.6rem; justify-content: flex-end; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closePosCourtesyModal()" style="padding: 0.55rem 1.15rem; font-size: 0.85rem; font-weight: 700; border-radius: 10px;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitPosCourtesy" class="btn" style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; border: none; padding: 0.55rem 1.45rem; font-size: 0.875rem; font-weight: 900; border-radius: 10px; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.45); cursor: pointer;">
                        🎁 Emitir Cortesía & Imprimir
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- CONTENEDOR OCULTO PARA IMPRESIÓN DEL RECIBO TÉRMICO (80MM POS) -->
    <div id="thermalReceiptContainer" style="display: none;"></div>

    <!-- MODAL DE GENERACIÓN DE PLANCHA DE IMPRENTA -->
    @include('web.partials.plancha_ticket_modal')

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

        function openPlanchaModalCurrentEvent() {
            const evtData = {
                id: {{ $event->id }},
                title: {!! json_encode($event->title) !!},
                slug: {!! json_encode($event->slug) !!},
                venue_name: {!! json_encode($event->venue_name ?? $event->address ?? 'Recinto Oficial') !!},
                address: {!! json_encode($event->address ?? '') !!},
                event_date: {!! json_encode(!empty($event->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('Y-m-d')) : '') !!},
                event_time: {!! json_encode($event->event_time ?? '19:00') !!},
                banner_image: {!! json_encode($event->banner_image ?? '') !!},
                sales_type: {!! json_encode($event->sales_type ?? 'fisica') !!},
                template: {!! json_encode($event->template ?? null) !!},
                zones: {!! json_encode($zonesWithStats ?? ($zones ?? [])) !!},
                raw_zones: {!! json_encode(is_array($event->zones) ? $event->zones : (json_decode($event->zones ?? '[]', true) ?: [])) !!},
                courtesy_settings: {!! json_encode($cSettings ?? (is_array($event->courtesy_settings) ? $event->courtesy_settings : (json_decode($event->courtesy_settings ?? '[]', true) ?: []))) !!}
            };
            if (typeof openPlanchaModal === 'function') {
                openPlanchaModal(evtData);
            } else if (typeof window.openPlanchaModal === 'function') {
                window.openPlanchaModal(evtData);
            } else {
                console.error('[Plancha] openPlanchaModal no está definido');
            }
        }

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
            const emailInput = document.getElementById('pos_buyer_email');
            const phoneInput = document.getElementById('pos_buyer_phone');
            const textDisplay = document.getElementById('saleClientSelectedText');
            const badge = document.getElementById('saleClientSelectedBadge');
            const btnClear = document.getElementById('btnClearSaleClientSelection');

            if (dniInput) dniInput.value = client.dni || '';
            if (nameInput) nameInput.value = client.name || '';
            if (emailInput) emailInput.value = client.email || '';
            if (phoneInput) phoneInput.value = client.phone || '';

            if (textDisplay) {
                textDisplay.innerHTML = `<span style="color: var(--color-primary-orange); font-weight: 800;">👤 ${escapePosHtml(client.name)}${client.dni ? ' (DNI: ' + escapePosHtml(client.dni) + ')' : ''}</span>`;
            }

            if (badge) badge.style.display = 'inline-block';
            if (btnClear) btnClear.style.display = 'inline-flex';

            closeSaleClientDropdown();

            [dniInput, nameInput, emailInput, phoneInput].forEach(inp => {
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
            const btnFetch = document.getElementById('btnFetchClientDni');
            const badgeFound = document.getElementById('dniClientFoundBadge');

            if (searchInput) searchInput.value = '';
            if (textDisplay) {
                textDisplay.innerHTML = `<span>🔍</span> <span>Buscar cliente registrado...</span>`;
            }
            if (badge) badge.style.display = 'none';
            if (btnClear) btnClear.style.display = 'none';
            if (btnFetch) btnFetch.style.display = 'none';
            if (badgeFound) badgeFound.style.display = 'none';
            posMatchedClientByDni = null;

            closeSaleClientDropdown();
        }

        // CONTROL DE DETECCIÓN Y BOTÓN "TRAER DATOS" POR DNI
        let posMatchedClientByDni = null;

        function onDniInputPosSale(val) {
            const cleanDni = (val || '').trim().toLowerCase();
            const btnFetch = document.getElementById('btnFetchClientDni');
            const badgeFound = document.getElementById('dniClientFoundBadge');
            const nameFound = document.getElementById('dniClientFoundName');

            posMatchedClientByDni = null;

            if (cleanDni && cleanDni.length >= 3 && cleanDni !== '00000000' && window.posExistingClients && Array.isArray(window.posExistingClients)) {
                const match = window.posExistingClients.find(c => c.dni && c.dni.toLowerCase().trim() === cleanDni);
                if (match) {
                    posMatchedClientByDni = match;
                    if (btnFetch) {
                        btnFetch.style.display = 'inline-flex';
                        const shortName = (match.name || '').split(' ')[0];
                        btnFetch.innerHTML = `<span>⚡</span> <span>Traer Datos (${escapePosHtml(shortName)})</span>`;
                        btnFetch.style.background = 'linear-gradient(135deg, #FF5500, #FF7733)';
                    }
                    if (badgeFound && nameFound) {
                        nameFound.textContent = match.name;
                        badgeFound.style.display = 'block';
                    }
                    return;
                }
            }

            if (btnFetch) btnFetch.style.display = 'none';
            if (badgeFound) badgeFound.style.display = 'none';
        }

        function fetchClientDataFromDni() {
            if (!posMatchedClientByDni) return;
            const client = posMatchedClientByDni;

            const nameInput = document.getElementById('pos_buyer_name');
            const emailInput = document.getElementById('pos_buyer_email');
            const phoneInput = document.getElementById('pos_buyer_phone');
            const textDisplay = document.getElementById('saleClientSelectedText');
            const btnFetch = document.getElementById('btnFetchClientDni');

            if (nameInput) nameInput.value = client.name || '';
            if (emailInput) emailInput.value = client.email || '';
            if (phoneInput) phoneInput.value = client.phone || '';

            if (textDisplay) {
                textDisplay.innerHTML = `<span style="color: var(--color-primary-orange); font-weight: 800;">👤 ${escapePosHtml(client.name)}</span>`;
            }

            if (btnFetch) {
                btnFetch.innerHTML = `<span>✓</span> <span>Datos Cargados</span>`;
                btnFetch.style.background = 'linear-gradient(135deg, #10B981, #059669)';
                setTimeout(() => {
                    if (btnFetch) btnFetch.style.display = 'none';
                }, 2000);
            }

            [nameInput, emailInput, phoneInput].forEach(inp => {
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

        function clearPosClientFields() {
            const dniInput = document.getElementById('pos_buyer_dni');
            const nameInput = document.getElementById('pos_buyer_name');
            const emailInput = document.getElementById('pos_buyer_email');
            const phoneInput = document.getElementById('pos_buyer_phone');
            const btnFetch = document.getElementById('btnFetchClientDni');
            const badgeFound = document.getElementById('dniClientFoundBadge');
            const chkAnon = document.getElementById('chkAnonymousBuyer');

            if (chkAnon && chkAnon.checked) {
                chkAnon.checked = false;
                toggleAnonymousBuyer(chkAnon);
            }

            if (dniInput) {
                dniInput.value = '';
                dniInput.readOnly = false;
                dniInput.style.opacity = '1';
                dniInput.focus();
            }
            if (nameInput) {
                nameInput.value = '';
                nameInput.readOnly = false;
                nameInput.style.opacity = '1';
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

            if (btnFetch) btnFetch.style.display = 'none';
            if (badgeFound) badgeFound.style.display = 'none';
            posMatchedClientByDni = null;
        }

        // CONTROL DE DETECCIÓN Y BOTÓN "TRAER DATOS" POR DNI PARA CORTESÍA
        let posMatchedCourtesyClientByDni = null;

        function onDniInputPosCourtesy(val) {
            const cleanDni = (val || '').trim().toLowerCase();
            const btnFetch = document.getElementById('btnFetchCourtesyClientDni');
            const badgeFound = document.getElementById('dniCourtesyFoundBadge');
            const nameFound = document.getElementById('dniCourtesyFoundName');

            posMatchedCourtesyClientByDni = null;

            if (cleanDni && cleanDni.length >= 3 && cleanDni !== '00000000' && window.posExistingClients && Array.isArray(window.posExistingClients)) {
                const match = window.posExistingClients.find(c => c.dni && c.dni.toLowerCase().trim() === cleanDni);
                if (match) {
                    posMatchedCourtesyClientByDni = match;
                    if (btnFetch) {
                        btnFetch.style.display = 'inline-flex';
                        const shortName = (match.name || '').split(' ')[0];
                        btnFetch.innerHTML = `<span>⚡</span> <span>Traer Datos (${escapePosHtml(shortName)})</span>`;
                    }
                    if (badgeFound && nameFound) {
                        nameFound.textContent = match.name;
                        badgeFound.style.display = 'block';
                    }
                    return;
                }
            }

            if (btnFetch) btnFetch.style.display = 'none';
            if (badgeFound) badgeFound.style.display = 'none';
        }

        function fetchCourtesyClientDataFromDni() {
            if (!posMatchedCourtesyClientByDni) return;
            const client = posMatchedCourtesyClientByDni;

            const nameInput = document.getElementById('pos_courtesy_name');
            const emailInput = document.getElementById('pos_courtesy_email');
            const phoneInput = document.getElementById('pos_courtesy_phone');
            const btnFetch = document.getElementById('btnFetchCourtesyClientDni');

            if (nameInput) nameInput.value = client.name || '';
            if (emailInput) emailInput.value = client.email || '';
            if (phoneInput) phoneInput.value = client.phone || '';

            if (btnFetch) {
                btnFetch.innerHTML = `<span>✓</span> <span>Datos Cargados</span>`;
                setTimeout(() => {
                    if (btnFetch) btnFetch.style.display = 'none';
                }, 2000);
            }

            [nameInput, emailInput, phoneInput].forEach(inp => {
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

        function clearPosCourtesyFields() {
            const dniInput = document.getElementById('pos_courtesy_dni');
            const nameInput = document.getElementById('pos_courtesy_name');
            const emailInput = document.getElementById('pos_courtesy_email');
            const phoneInput = document.getElementById('pos_courtesy_phone');
            const btnFetch = document.getElementById('btnFetchCourtesyClientDni');
            const badgeFound = document.getElementById('dniCourtesyFoundBadge');
            const chkAnon = document.getElementById('chkAnonymousCourtesy');

            if (chkAnon && chkAnon.checked) {
                chkAnon.checked = false;
                toggleAnonymousCourtesy(chkAnon);
            }

            if (dniInput) {
                dniInput.value = '';
                dniInput.readOnly = false;
                dniInput.style.opacity = '1';
                dniInput.focus();
            }
            if (nameInput) {
                nameInput.value = '';
                nameInput.readOnly = false;
                nameInput.style.opacity = '1';
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

            if (btnFetch) btnFetch.style.display = 'none';
            if (badgeFound) badgeFound.style.display = 'none';
            posMatchedCourtesyClientByDni = null;
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

        // Auto-detección inteligente al escribir DNI o Correo en los formularios POS
        function setupPosClientAutoDetect() {
            const bindAutoDetect = (dniId, nameId, emailId, phoneId) => {
                const dniEl = document.getElementById(dniId);
                const nameEl = document.getElementById(nameId);
                const emailEl = document.getElementById(emailId);
                const phoneEl = document.getElementById(phoneId);

                const checkMatch = (val, type) => {
                    if (!val || val.length < 3 || !window.posExistingClients || !Array.isArray(window.posExistingClients)) return;
                    const cleanVal = val.toLowerCase().trim();
                    let match = null;

                    if (type === 'dni' && cleanVal !== '00000000') {
                        match = window.posExistingClients.find(c => c.dni && c.dni.toLowerCase().trim() === cleanVal);
                    } else if (type === 'email' && cleanVal.includes('@')) {
                        match = window.posExistingClients.find(c => c.email && c.email.toLowerCase().trim() === cleanVal);
                    }

                    if (match) {
                        if (nameEl && (!nameEl.value || nameEl.value === 'CLIENTE VARIOS')) nameEl.value = match.name || '';
                        if (dniEl && (!dniEl.value || dniEl.value === '00000000') && match.dni) dniEl.value = match.dni;
                        if (emailEl && !emailEl.value && match.email) emailEl.value = match.email;
                        if (phoneEl && (!phoneEl.value || phoneEl.value === '-') && match.phone) phoneEl.value = match.phone;
                    }
                };

                if (dniEl) {
                    dniEl.addEventListener('blur', () => checkMatch(dniEl.value, 'dni'));
                    dniEl.addEventListener('change', () => checkMatch(dniEl.value, 'dni'));
                }
                if (emailEl) {
                    emailEl.addEventListener('blur', () => checkMatch(emailEl.value, 'email'));
                    emailEl.addEventListener('change', () => checkMatch(emailEl.value, 'email'));
                }
            };

            bindAutoDetect('pos_buyer_dni', 'pos_buyer_name', 'pos_buyer_email', 'pos_buyer_phone');
            bindAutoDetect('pos_courtesy_dni', 'pos_courtesy_name', 'pos_courtesy_email', 'pos_courtesy_phone');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupPosClientAutoDetect);
        } else {
            setupPosClientAutoDetect();
        }

        let currentTotalToPay = 0;
        let selectedZoneName = "{{ $zonesWithStats[0]['name'] ?? '' }}";
        let selectedZonePrice = {{ $zonesWithStats[0]['price'] ?? 0 }};
        let selectedZoneAvailable = {{ $zonesWithStats[0]['available'] ?? 100 }};
        let posSelectedSeats = [];
        window.posRawZones = @json(is_array($event->zones) ? $event->zones : (json_decode($event->zones ?? '[]', true) ?: []));

        function cleanPosZoneBase(name) {
            if (!name) return 'GENERAL';
            return String(name).replace(/\s*\([^)]*\)$/, '').trim().toUpperCase();
        }

        function getActivePosZone(name = selectedZoneName) {
            const cleanTarget = cleanPosZoneBase(name);
            return (window.posRawZones || []).find(z => {
                const cleanZ = cleanPosZoneBase(z.name || '');
                return cleanZ === cleanTarget || (z.name && z.name.trim().toLowerCase() === name.trim().toLowerCase());
            });
        }

        function formatShortSeatCodeJs(seat) {
            if (!seat) return '';
            if (typeof seat === 'object') {
                const r = seat.row ? String(seat.row).toUpperCase().trim() : '';
                const c = (seat.col !== undefined && seat.col !== null) ? String(seat.col).trim() : '';
                const num = (seat.number !== undefined && seat.number !== null) ? String(seat.number).trim() : '';

                // Si col es numérico (ej: row: "A", col: "3")
                if (r && c && /^\d+$/.test(c)) {
                    return r + c;
                }

                // Si number es como "A-3", "A3", "A 3" o solo dígitos
                if (r && num) {
                    const numMatch = num.match(new RegExp('^' + r + '[\\s\\-_]*(\\d+)$', 'i'));
                    if (numMatch) {
                        return r + numMatch[1];
                    }
                    if (/^\d+$/.test(num)) {
                        return r + num;
                    }
                }

                seat = seat.label || seat.display_name || seat.number || (r && c ? r + c : '') || seat.code || '';
            }
            seat = String(seat).trim();
            if (!seat) return '';

            // Si viene duplicado como "AA-3", "AA3", "AA_3"
            let m = seat.match(/^([A-Za-z])\1[\s\-_]*([0-9]+)$/);
            if (m) return m[1].toUpperCase() + m[2];

            // "Fila A - Asiento 1" o "Fila A - Columna 1"
            m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*-\s*(?:Asiento|Columna)\s*([0-9]+)/i);
            if (m) return `${m[1].toUpperCase()}${m[2]}`;

            // "Fila A Asiento 1"
            m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*(?:Asiento|Columna)\s*([0-9]+)/i);
            if (m) return `${m[1].toUpperCase()}${m[2]}`;

            // "A-1" o "A 1" o "A_1"
            m = seat.match(/^([A-Za-z]+)[-\s_]+([0-9]+)$/);
            if (m) return `${m[1].toUpperCase()}${m[2]}`;

            // Código directo tipo "A1" o "B10"
            m = seat.match(/^([A-Za-z]+[0-9]+)$/);
            if (m) return m[1].toUpperCase();

            return seat;
        }

        function isPosSeatOccupied(zoneName, seatCode) {
            if (!seatCode) return false;
            const targetZone = getActivePosZone(zoneName);
            if (targetZone && Array.isArray(targetZone.seats)) {
                const sObj = targetZone.seats.find(s => {
                    const c = formatShortSeatCodeJs(s);
                    return c === seatCode || (s.label && s.label === seatCode);
                });
                if (sObj && (sObj.status === 'occupied' || sObj.status === 'ocupado' || sObj.status === 'vendido' || sObj.is_occupied)) {
                    return true;
                }
            }

            // También verificar en ventas ya existentes en vivo
            if (window.posSalesMap && typeof window.posSalesMap === 'object') {
                const cleanZ = cleanPosZoneBase(zoneName);
                for (const saleId in window.posSalesMap) {
                    const sale = window.posSalesMap[saleId];
                    if (!sale || sale.status === 'cancelled') continue;
                    const saleZone = cleanPosZoneBase(sale.zone_name || '');
                    if (saleZone === cleanZ) {
                        const tData = Array.isArray(sale.tickets_data) ? sale.tickets_data : (typeof sale.tickets_data === 'string' ? JSON.parse(sale.tickets_data || '[]') : []);
                        for (const t of tData) {
                            const tSeat = formatShortSeatCodeJs(t.seat || t.seat_label || t.seat_number || t.zone || '');
                            if (tSeat === seatCode) return true;
                        }
                    }
                }
            }
            return false;
        }

        function renderPosSeatMap(zoneName) {
            const container = document.getElementById('posSeatSelectorContainer');
            const svg = document.getElementById('posSeatMapSvg');
            const titleEl = document.getElementById('posSeatSelectorTitle');
            if (!container || !svg) return false;

            const targetZone = getActivePosZone(zoneName);
            if (!targetZone || !Array.isArray(targetZone.seats) || targetZone.seats.length === 0) {
                container.style.display = 'none';
                posSelectedSeats = [];
                updatePosSelectedSeatsTags();
                return false;
            }

            container.style.display = 'block';
            if (titleEl) titleEl.textContent = `Selección de Butacas (${targetZone.name})`;

            svg.innerHTML = '';
            const NS = "http://www.w3.org/2000/svg";

            // 1. Calcular caja envolvente (Bounding Box)
            let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
            let totalPointsCount = 0;

            if (Array.isArray(targetZone.points) && targetZone.points.length >= 3) {
                targetZone.points.forEach(p => {
                    if (p.x < minX) minX = p.x;
                    if (p.x > maxX) maxX = p.x;
                    if (p.y < minY) minY = p.y;
                    if (p.y > maxY) maxY = p.y;
                    totalPointsCount++;
                });
            }

            if (Array.isArray(targetZone.seats)) {
                targetZone.seats.forEach(s => {
                    if (s.x < minX) minX = s.x;
                    if (s.x > maxX) maxX = s.x;
                    if (s.y < minY) minY = s.y;
                    if (s.y > maxY) maxY = s.y;
                    totalPointsCount++;
                });
            }

            if (totalPointsCount > 0) {
                const boxWidth = Math.max(120, maxX - minX);
                const boxHeight = Math.max(80, maxY - minY);
                const padX = Math.max(14, Math.round(boxWidth * 0.04));
                const padY = Math.max(14, Math.round(boxHeight * 0.05));
                const vbX = Math.round(minX - padX);
                const vbY = Math.round(minY - padY);
                const vbW = Math.round(boxWidth + padX * 2);
                const vbH = Math.round(boxHeight + padY * 2);
                svg.setAttribute('viewBox', `${vbX} ${vbY} ${vbW} ${vbH}`);
            } else {
                svg.setAttribute('viewBox', '0 0 600 350');
            }

            // 2. Dibujar fondo de la zona si tiene polígono o rectángulo
            const zoneColor = targetZone.color || '#FF5500';
            const xs = (targetZone.points || []).map(p => p.x);
            const ys = (targetZone.points || []).map(p => p.y);
            const zMinX = xs.length > 0 ? Math.min(...xs) : minX;
            const zMaxX = xs.length > 0 ? Math.max(...xs) : maxX;
            const zMinY = ys.length > 0 ? Math.min(...ys) : minY;
            const zMaxY = ys.length > 0 ? Math.max(...ys) : maxY;

            const bgGroup = document.createElementNS(NS, 'g');
            let zoneSurface;
            if (Array.isArray(targetZone.points) && targetZone.points.length === 4) {
                zoneSurface = document.createElementNS(NS, 'rect');
                zoneSurface.setAttribute('x', zMinX);
                zoneSurface.setAttribute('y', zMinY);
                zoneSurface.setAttribute('width', zMaxX - zMinX);
                zoneSurface.setAttribute('height', zMaxY - zMinY);
                zoneSurface.setAttribute('rx', 10);
                zoneSurface.setAttribute('ry', 10);
            } else if (Array.isArray(targetZone.points) && targetZone.points.length >= 3) {
                zoneSurface = document.createElementNS(NS, 'polygon');
                zoneSurface.setAttribute('points', targetZone.points.map(p => `${p.x},${p.y}`).join(' '));
                zoneSurface.setAttribute('stroke-linejoin', 'round');
            }

            if (zoneSurface) {
                zoneSurface.setAttribute('fill', '#FAFAFA');
                zoneSurface.setAttribute('stroke', zoneColor);
                zoneSurface.setAttribute('stroke-width', '1.4');
                zoneSurface.setAttribute('opacity', '0.9');
                bgGroup.appendChild(zoneSurface);
            }
            svg.appendChild(bgGroup);

            // 3. Dibujar cada butaca
            const seatsGroup = document.createElementNS(NS, 'g');
            const seatSide = 12;
            const halfSide = seatSide / 2;

            targetZone.seats.forEach(seat => {
                const seatCode = formatShortSeatCodeJs(seat);
                const isOccupied = isPosSeatOccupied(targetZone.name, seatCode);
                const isSelected = posSelectedSeats.includes(seatCode);

                const rect = document.createElementNS(NS, 'rect');
                rect.setAttribute('x', seat.x - halfSide);
                rect.setAttribute('y', seat.y - halfSide);
                rect.setAttribute('width', seatSide);
                rect.setAttribute('height', seatSide);
                rect.setAttribute('rx', 2.8);
                rect.setAttribute('ry', 2.8);
                rect.setAttribute('class', `pos-seat-rect ${isOccupied ? 'occupied' : ''} ${isSelected ? 'selected' : ''}`);
                rect.setAttribute('fill', isOccupied ? '#EF4444' : (isSelected ? '#FF5500' : (zoneColor || '#10B981')));
                rect.setAttribute('stroke', isOccupied ? '#DC2626' : (isSelected ? '#FFFFFF' : '#FFFFFF'));
                rect.setAttribute('stroke-width', '1.2');
                rect.setAttribute('data-seat-id', seatCode);
                rect.setAttribute('data-zone-name', targetZone.name);

                rect.addEventListener('mouseenter', (e) => onPosSeatMouseEnter(e, seat, targetZone, isOccupied));
                rect.addEventListener('mousemove', (e) => onPosSeatMouseMove(e));
                rect.addEventListener('mouseleave', () => onPosSeatMouseLeave());
                rect.addEventListener('click', (e) => {
                    e.stopPropagation();
                    togglePosSeat(seatCode, targetZone.name, isOccupied);
                });

                seatsGroup.appendChild(rect);
            });

            svg.appendChild(seatsGroup);
            updatePosSelectedSeatsTags();
            return true;
        }

        function onPosSeatMouseEnter(e, seat, zone, isOccupied) {
            const tooltip = document.getElementById('posZoneTooltip');
            const titleEl = document.getElementById('posTooltipTitle');
            const statusEl = document.getElementById('posTooltipStatus');
            if (!tooltip) return;

            const seatCode = formatShortSeatCodeJs(seat);
            const isSelected = posSelectedSeats.includes(seatCode);

            if (titleEl) {
                const rowStr = seat.row ? `Fila ${seat.row}` : '';
                const numStr = (seat.number || seat.col) ? `Asiento ${seat.number || seat.col}` : '';
                const fullLabel = [rowStr, numStr].filter(Boolean).join(' - ') || seatCode;
                titleEl.textContent = `🪑 ${zone.name} (${fullLabel} · ${seatCode})`;
            }

            if (statusEl) {
                if (isOccupied) {
                    statusEl.textContent = '🚫 OCUPADA / VENDIDA';
                    statusEl.style.color = '#EF4444';
                } else if (isSelected) {
                    statusEl.textContent = `🟠 SELECCIONADA (S/ ${selectedZonePrice.toFixed(2)}) · Clic para desmarcar`;
                    statusEl.style.color = '#FF5500';
                } else {
                    statusEl.textContent = `🟢 DISPONIBLE (S/ ${selectedZonePrice.toFixed(2)}) · Clic para elegir`;
                    statusEl.style.color = '#10B981';
                }
            }

            tooltip.style.display = 'block';
            onPosSeatMouseMove(e);
        }

        function onPosSeatMouseMove(e) {
            const tooltip = document.getElementById('posZoneTooltip');
            const container = document.getElementById('posSeatMapCanvasLayer');
            if (!tooltip || !container) return;

            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            tooltip.style.left = `${x}px`;
            tooltip.style.top = `${y}px`;
        }

        function onPosSeatMouseLeave() {
            const tooltip = document.getElementById('posZoneTooltip');
            if (tooltip) tooltip.style.display = 'none';
        }

        function togglePosSeat(seatCode, zoneName, isOccupied) {
            if (isOccupied) {
                Swal.fire({
                    title: 'Butaca Ocupada',
                    text: `La butaca ${seatCode} ya se encuentra ocupada o vendida.`,
                    icon: 'warning',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            const rect = document.querySelector(`.pos-seat-rect[data-seat-id="${seatCode}"]`);
            const targetZone = getActivePosZone(zoneName);
            const zoneColor = targetZone ? (targetZone.color || '#10B981') : '#10B981';

            if (posSelectedSeats.includes(seatCode)) {
                posSelectedSeats = posSelectedSeats.filter(c => c !== seatCode);
                if (rect) {
                    rect.classList.remove('selected');
                    rect.setAttribute('fill', zoneColor);
                    rect.setAttribute('stroke', '#FFFFFF');
                }
            } else {
                posSelectedSeats.push(seatCode);
                if (rect) {
                    rect.classList.add('selected');
                    rect.setAttribute('fill', '#FF5500');
                    rect.setAttribute('stroke', '#FFFFFF');
                }
            }

            const newQty = Math.max(1, posSelectedSeats.length);
            const qtyInput = document.getElementById('pos_quantity');
            if (qtyInput) {
                qtyInput.value = newQty;
            }

            document.querySelectorAll('.pos-quick-btn[id^="btnQuickQty"]').forEach(b => b.classList.remove('active'));
            const quickBtn = document.getElementById(`btnQuickQty${newQty}`);
            if (quickBtn) quickBtn.classList.add('active');

            updatePosSelectedSeatsTags();
            calculatePosTotal();
        }

        function removePosSeat(seatCode) {
            togglePosSeat(seatCode, selectedZoneName, false);
        }

        function clearPosSelectedSeats() {
            posSelectedSeats = [];
            const targetZone = getActivePosZone();
            const zoneColor = targetZone ? (targetZone.color || '#10B981') : '#10B981';

            document.querySelectorAll('.pos-seat-rect.selected').forEach(rect => {
                rect.classList.remove('selected');
                rect.setAttribute('fill', zoneColor);
                rect.setAttribute('stroke', '#FFFFFF');
            });

            const qtyInput = document.getElementById('pos_quantity');
            if (qtyInput) qtyInput.value = 1;

            document.querySelectorAll('.pos-quick-btn[id^="btnQuickQty"]').forEach(b => b.classList.remove('active'));
            const btn1 = document.getElementById('btnQuickQty1');
            if (btn1) btn1.classList.add('active');

            updatePosSelectedSeatsTags();
            calculatePosTotal();
        }

        function updatePosSelectedSeatsTags() {
            const tagsBox = document.getElementById('posSelectedSeatsTagsBox');
            const clearBtn = document.getElementById('btnPosClearSeats');
            if (!tagsBox) return;

            if (posSelectedSeats.length === 0) {
                tagsBox.innerHTML = '<span style="font-size: 0.7rem; color: #94A3B8;">👈 Haz clic en una o más butacas en el plano</span>';
                if (clearBtn) clearBtn.style.display = 'none';
            } else {
                tagsBox.innerHTML = posSelectedSeats.map(code => 
                    `<span class="pos-seat-chip">🪑 ${code} <button type="button" onclick="removePosSeat('${code}')" style="background:none; border:none; color:#FF5500; font-weight:900; cursor:pointer; padding:0 2px; line-height:1;" title="Quitar butaca">✕</button></span>`
                ).join('');
                if (clearBtn) clearBtn.style.display = 'inline-block';
            }
        }

        function selectZoneCard(name, price, available, el) {
            if (available <= 0) return;
            
            document.querySelectorAll('.zone-card-item').forEach(c => c.classList.remove('active'));
            if (el) el.classList.add('active');

            selectedZoneName = name;
            selectedZonePrice = parseFloat(price) || 0;
            selectedZoneAvailable = parseInt(available, 10) || 0;

            const zoneInput = document.getElementById('pos_zone_select');
            if (zoneInput) zoneInput.value = name;

            posSelectedSeats = [];
            const hasSeats = renderPosSeatMap(name);

            if (hasSeats) {
                // Auto-seleccionar primer asiento libre por defecto
                const firstAvailRect = document.querySelector('.pos-seat-rect:not(.occupied)');
                if (firstAvailRect) {
                    const firstCode = firstAvailRect.getAttribute('data-seat-id');
                    if (firstCode) {
                        togglePosSeat(firstCode, name, false);
                    }
                }
            } else {
                const qtyInput = document.getElementById('pos_quantity');
                if (qtyInput) {
                    qtyInput.max = selectedZoneAvailable;
                    if (parseInt(qtyInput.value, 10) > selectedZoneAvailable) {
                        qtyInput.value = Math.max(1, selectedZoneAvailable);
                    }
                }
            }

            calculatePosTotal();
        }

        function openPosSaleModal() {
            const modal = document.getElementById('posSaleModal');
            if (modal) {
                modal.classList.add('active');
                closeSaleClientDropdown();
                
                const btnFetch = document.getElementById('btnFetchClientDni');
                const badgeFound = document.getElementById('dniClientFoundBadge');
                if (btnFetch) btnFetch.style.display = 'none';
                if (badgeFound) badgeFound.style.display = 'none';
                posMatchedClientByDni = null;

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

                // Por defecto seleccionar Efectivo
                const cashPill = document.querySelector('#paymentMethodsGroup .payment-method-pill');
                if (cashPill) selectPaymentMethod('Efectivo', cashPill);

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
            @if(!$isCourtesyActive)
                return;
            @endif
            const modal = document.getElementById('posCourtesyModal');
            if (modal) {
                modal.classList.add('active');
                closeCourtesyClientDropdown();

                const btnFetch = document.getElementById('btnFetchCourtesyClientDni');
                const badgeFound = document.getElementById('dniCourtesyFoundBadge');
                if (btnFetch) btnFetch.style.display = 'none';
                if (badgeFound) badgeFound.style.display = 'none';
                posMatchedCourtesyClientByDni = null;

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
            const targetZone = getActivePosZone();
            const hasSeats = targetZone && Array.isArray(targetZone.seats) && targetZone.seats.length > 0;

            if (hasSeats) {
                const targetQty = Math.min(qty, selectedZoneAvailable > 0 ? selectedZoneAvailable : 50);
                if (targetQty > posSelectedSeats.length) {
                    while (posSelectedSeats.length < targetQty) {
                        const nextRect = document.querySelector('.pos-seat-rect:not(.occupied):not(.selected)');
                        if (!nextRect) break;
                        const c = nextRect.getAttribute('data-seat-id');
                        if (c) togglePosSeat(c, selectedZoneName, false);
                        else break;
                    }
                } else if (targetQty < posSelectedSeats.length) {
                    while (posSelectedSeats.length > targetQty && posSelectedSeats.length > 1) {
                        const lastCode = posSelectedSeats[posSelectedSeats.length - 1];
                        togglePosSeat(lastCode, selectedZoneName, false);
                    }
                }
                return;
            }

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
            const targetZone = getActivePosZone();
            const hasSeats = targetZone && Array.isArray(targetZone.seats) && targetZone.seats.length > 0;

            if (hasSeats) {
                if (step > 0) {
                    const nextRect = document.querySelector('.pos-seat-rect:not(.occupied):not(.selected)');
                    if (nextRect) {
                        const c = nextRect.getAttribute('data-seat-id');
                        if (c) togglePosSeat(c, selectedZoneName, false);
                    }
                } else if (step < 0 && posSelectedSeats.length > 1) {
                    const lastCode = posSelectedSeats[posSelectedSeats.length - 1];
                    togglePosSeat(lastCode, selectedZoneName, false);
                }
                return;
            }

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
            const emailInput = document.getElementById('pos_buyer_email');
            const phoneInput = document.getElementById('pos_buyer_phone');
            const starDni = document.getElementById('star_buyer_dni');
            const starName = document.getElementById('star_buyer_name');
            const btnFetch = document.getElementById('btnFetchClientDni');
            const badgeFound = document.getElementById('dniClientFoundBadge');

            if (isAnon) {
                posMatchedClientByDni = null;
                if (btnFetch) btnFetch.style.display = 'none';
                if (badgeFound) badgeFound.style.display = 'none';

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

        // Manejar checkbox de Invitado Anónimo / Sin Datos en Cortesía
        function toggleAnonymousCourtesy(chk) {
            const isAnon = chk.checked;
            const dniInput = document.getElementById('pos_courtesy_dni');
            const nameInput = document.getElementById('pos_courtesy_name');
            const emailInput = document.getElementById('pos_courtesy_email');
            const phoneInput = document.getElementById('pos_courtesy_phone');
            const starDni = document.getElementById('star_courtesy_dni');
            const starName = document.getElementById('star_courtesy_name');
            const btnFetch = document.getElementById('btnFetchCourtesyClientDni');
            const badgeFound = document.getElementById('dniCourtesyFoundBadge');

            if (isAnon) {
                posMatchedCourtesyClientByDni = null;
                if (btnFetch) btnFetch.style.display = 'none';
                if (badgeFound) badgeFound.style.display = 'none';

                if (dniInput) {
                    dniInput.value = '00000000';
                    dniInput.readOnly = true;
                    dniInput.style.opacity = '0.55';
                    dniInput.required = false;
                }
                if (nameInput) {
                    nameInput.value = 'INVITADO DE CORTESIA';
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

        function getFullAssetUrl(urlStr) {
            if (!urlStr || typeof urlStr !== 'string') return '';
            if (urlStr.startsWith('data:')) return urlStr;

            let clean = urlStr;
            try {
                if (urlStr.startsWith('http://') || urlStr.startsWith('https://')) {
                    const parsed = new URL(urlStr);
                    const isLocal = parsed.hostname === window.location.hostname || parsed.hostname === 'localhost' || parsed.hostname === '127.0.0.1';
                    if (!isLocal && !urlStr.includes('/storage/') && !urlStr.includes('/images/') && !urlStr.includes('/media/')) {
                        return urlStr;
                    }
                    clean = parsed.pathname;
                }
            } catch(e) {}

            clean = clean.replace(/^\//, '');

            if (clean.includes('storage/')) {
                clean = 'storage/' + clean.split('storage/').pop();
            } else if (clean.includes('images/')) {
                clean = 'images/' + clean.split('images/').pop();
            } else if (clean.startsWith('events/') || clean.startsWith('templates/') || clean.startsWith('media/') || clean.startsWith('uploads/')) {
                clean = 'storage/' + clean;
            } else if (clean.includes('media/')) {
                clean = 'storage/media/' + clean.split('media/').pop();
            }

            return window.location.origin + '/' + clean;
        }

        async function preloadPosImageAsDataUrl(url, type = 'banner') {
            if (!url || typeof url !== 'string' || url.trim() === '') return '';
            if (url.startsWith('data:')) return url;
            
            const targetUrl = getFullAssetUrl(url) || url;

            try {
                const response = await fetch(targetUrl, { cache: 'force-cache' });
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
                    img.onerror = () => { clearTimeout(timeout); resolve(targetUrl); };
                    img.src = targetUrl;
                });
                return dataUrl || targetUrl;
            } catch (e) {
                return targetUrl;
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

                // Si es impresión de plancha física, omitir completamente campos de comprador y DNI
                if (dynamicData.is_plancha_print) {
                    if (type === 'buyer_name' || type === 'buyer_dni' || type === 'buyer' ||
                        field === 'buyer_name' || field === 'buyer_dni' || field === 'buyer' ||
                        el.id === 'canvaElBuyer' || el.id === 'canvaElBuyerName' || el.id === 'canvaElBuyerDni' ||
                        (el.id && /buyer|comprador|dni/i.test(String(el.id))) ||
                        /Comprador/i.test(el.content || el.html || el.text || '') ||
                        /DNI/i.test(el.content || el.html || el.text || '')) {
                        return;
                    }
                }

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

                    // En plancha física ocultar cualquier etiqueta residual de Comprador o DNI
                    if (dynamicData.is_plancha_print && (/Comprador/i.test(rawTxt) || /DNI/i.test(rawTxt))) {
                        return;
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
                    } else if (field === 'seat' || field === 'butaca' || el.id === 'canvaElSeat' || el.id === 'canvaElButaca' || /BUTACA/i.test(rawTxt) || /ASIENTO/i.test(rawTxt)) {
                        const sVal = dynamicData.seat || '';
                        if (sVal) {
                            if (/BUTACA/i.test(rawTxt)) {
                                rawTxt = replaceDynamicValueInHtml(rawTxt, 'BUTACA', sVal);
                            } else if (/ASIENTO/i.test(rawTxt)) {
                                rawTxt = replaceDynamicValueInHtml(rawTxt, 'ASIENTO', sVal);
                            } else if (rawTxt && rawTxt.trim().length > 0) {
                                rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, sVal) : sVal;
                            } else {
                                rawTxt = `<div style="text-align: inherit; width: 100%;"><span style="font-size: 0.75em; font-weight: 900; display: block;">BUTACA:</span><span style="font-size: 1.1em; font-weight: 900; display: block; color: #F59E0B;">${sVal}</span></div>`;
                            }
                        } else {
                            rawTxt = '';
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
                    } else if (field === 'ticket_number' || field === 'ticket_code' || field === 'code' ||
                               el.id === 'canvaElTicketNumber' || el.id === 'canvaElTicketCode' ||
                               /N[°º]/i.test(rawTxt) || /TK-[A-Z0-9_\-]+/i.test(rawTxt)) {
                        const numVal = parseInt(String(dynamicData.ticket_number || dynamicData.ticket_code || '').replace(/[^0-9]/g, ''), 10) || 1;
                        const numStr = 'N° ' + String(numVal).padStart(5, '0');
                        if (/N[°º]/i.test(rawTxt) || /TK-[A-Z0-9_\-]+/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/(N[°º]\s*[\d]+|TK-[A-Z0-9_\-]+)/gi, numStr);
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

        function cleanZoneNameJs(name) {
            if (!name || typeof name !== 'string') return '';
            let m = name.match(/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/i);
            return m ? m[1].trim() : name.trim();
        }

        function formatShortSeatCodeJs(seat) {
            if (!seat) return '';
            if (typeof seat === 'object') {
                const r = seat.row ? String(seat.row).toUpperCase().trim() : '';
                const c = (seat.col !== undefined && seat.col !== null) ? String(seat.col).trim() : '';
                const num = (seat.number !== undefined && seat.number !== null) ? String(seat.number).trim() : '';

                // Si col es numérico (ej: row: "A", col: "3")
                if (r && c && /^\d+$/.test(c)) {
                    return r + c;
                }

                // Si number es como "A-3", "A3", "A 3" o solo dígitos
                if (r && num) {
                    const numMatch = num.match(new RegExp('^' + r + '[\\s\\-_]*(\\d+)$', 'i'));
                    if (numMatch) {
                        return r + numMatch[1];
                    }
                    if (/^\d+$/.test(num)) {
                        return r + num;
                    }
                }

                seat = seat.label || seat.display_name || seat.number || (r && c ? r + c : '') || seat.code || '';
            }
            seat = String(seat).trim();
            if (!seat) return '';

            // Si viene duplicado como "AA-3", "AA3", "AA_3"
            let m = seat.match(/^([A-Za-z])\1[\s\-_]*([0-9]+)$/);
            if (m) return m[1].toUpperCase() + m[2];

            // "Fila A - Asiento 1" o "Fila A - Columna 1"
            m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*-\s*(?:Asiento|Columna)\s*([0-9]+)/i);
            if (m) return `${m[1].toUpperCase()}${m[2]}`;

            // "Fila A Asiento 1"
            m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*(?:Asiento|Columna)\s*([0-9]+)/i);
            if (m) return `${m[1].toUpperCase()}${m[2]}`;

            // "A-1" o "A 1" o "A_1"
            m = seat.match(/^([A-Za-z]+)[-\s_]+([0-9]+)$/);
            if (m) return `${m[1].toUpperCase()}${m[2]}`;

            // Código directo tipo "A1" o "B10"
            m = seat.match(/^([A-Za-z]+[0-9]+)$/);
            if (m) return m[1].toUpperCase();

            return seat;
        }

        function formatZoneWithSeatJs(zoneName, seat) {
            let clean = cleanZoneNameJs(zoneName || '');
            let shortSeat = formatShortSeatCodeJs(seat);
            if (!shortSeat) return clean;
            if (clean.includes(`(${shortSeat})`)) return clean;
            let base = clean.replace(/\s*\([^)]*\)$/, '').trim();
            return `${base} (${shortSeat})`;
        }

        // Generador base de documento PDF con Canva Studio (compartido)
        async function generatePosTicketPdfDoc(sale) {
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

            let tplElements = template.elements || [];
            if ((!Array.isArray(tplElements) || tplElements.length === 0) && template.positions) {
                let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
                tplElements = convertPositionsToElements(rawPos);
            }

            for (let el of tplElements) {
                if (el.src) {
                    const fullUrl = getFullAssetUrl(el.src);
                    el.src = await preloadPosImageAsDataUrl(fullUrl, 'el_' + el.id);
                }
            }

            let ticketsDataParsed = sale.tickets_data;
            if (typeof ticketsDataParsed === 'string') {
                try { ticketsDataParsed = JSON.parse(ticketsDataParsed); } catch(e) {}
            }

            const isCourtesy = (sale.payment_method === 'Cortesía' || sale.payment_method === 'cortesia');

            let ticketsList = [];

            // 1. Si la venta ya tiene event_tickets cargados con su zone_name (ej: "Butacas Numeradas (A1)")
            if (sale.event_tickets && Array.isArray(sale.event_tickets) && sale.event_tickets.length > 0) {
                sale.event_tickets.forEach((et, i) => {
                    ticketsList.push({
                        ticket_code: et.ticket_code || `TK-${sale.receipt_number}-${i + 1}`,
                        ticket_number: et.ticket_number || (i + 1),
                        zone: et.zone_name || sale.zone_name,
                        price: et.unit_price || sale.unit_price,
                        is_courtesy: isCourtesy,
                        validation_hash: et.validation_hash || null,
                        qr_payload: et.qr_payload || null
                    });
                });
            } else if (sale.eventTickets && Array.isArray(sale.eventTickets) && sale.eventTickets.length > 0) {
                sale.eventTickets.forEach((et, i) => {
                    ticketsList.push({
                        ticket_code: et.ticket_code || `TK-${sale.receipt_number}-${i + 1}`,
                        ticket_number: et.ticket_number || (i + 1),
                        zone: et.zone_name || sale.zone_name,
                        price: et.unit_price || sale.unit_price,
                        is_courtesy: isCourtesy,
                        validation_hash: et.validation_hash || null,
                        qr_payload: et.qr_payload || null
                    });
                });
            } else if (ticketsDataParsed && ticketsDataParsed.items && Array.isArray(ticketsDataParsed.items) && ticketsDataParsed.items.length > 0) {
                ticketsDataParsed.items.forEach((it, idx) => {
                    const qty = parseInt(it.quantity || 1, 10);
                    const rawZoneName = it.zone_name || it.name || sale.zone_name;
                    const price = it.price || it.regular_price || sale.unit_price;
                    let seats = it.seats || [];
                    if (typeof seats === 'string') {
                        try { seats = JSON.parse(seats); } catch(e) { seats = []; }
                    }
                    if (!Array.isArray(seats)) seats = [];

                    for (let q = 0; q < qty; q++) {
                        const st = seats[q] || null;
                        const effectiveZone = formatZoneWithSeatJs(rawZoneName, st);
                        ticketsList.push({
                            ticket_code: it.ticket_code || `TK-${sale.receipt_number}-${ticketsList.length + 1}`,
                            ticket_number: ticketsList.length + 1,
                            zone: effectiveZone,
                            seat: formatShortSeatCodeJs(st),
                            price: price,
                            is_courtesy: isCourtesy || it.is_courtesy,
                            validation_hash: it.validation_hash || null,
                            qr_payload: it.qr_payload || null
                        });
                    }
                });
            } else if (Array.isArray(ticketsDataParsed) && ticketsDataParsed.length > 0) {
                ticketsDataParsed.forEach((tItem, i) => {
                    const st = tItem.seat || tItem.seat_number || (tItem.seats && tItem.seats[0]) || null;
                    const rawZoneName = tItem.zone || tItem.zone_name || sale.zone_name;
                    ticketsList.push({
                        ticket_code: tItem.ticket_code || `TK-${sale.receipt_number}-${i + 1}`,
                        ticket_number: tItem.ticket_number || (i + 1),
                        zone: formatZoneWithSeatJs(rawZoneName, st),
                        seat: formatShortSeatCodeJs(st),
                        price: tItem.price || sale.unit_price,
                        is_courtesy: isCourtesy || tItem.is_courtesy,
                        validation_hash: tItem.validation_hash || null,
                        qr_payload: tItem.qr_payload || null
                    });
                });
            } else {
                const qty = parseInt(sale.quantity || 1, 10);
                for (let q = 0; q < qty; q++) {
                    ticketsList.push({
                        ticket_code: `TK-${sale.receipt_number}-${q + 1}`,
                        ticket_number: q + 1,
                        zone: sale.zone_name,
                        price: sale.unit_price,
                        is_courtesy: isCourtesy,
                        validation_hash: null,
                        qr_payload: null
                    });
                }
            }

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
                const ticketNumStr = (tItem.ticket_code && tItem.ticket_code.startsWith('N°')) ? tItem.ticket_code : ('N° ' + String(numSeq).padStart(5, '0'));

                let hashVal = tItem.validation_hash || sale.validation_hash;
                if (!hashVal) {
                    hashVal = 'VG' + String(Math.abs(((sale.receipt_number || 'REC') + '_' + (i + 1)).split('').reduce((a, b) => { a = ((a << 5) - a) + b.charCodeAt(0); return a & a; }, 0))).padStart(8, '0').substring(0, 8).toUpperCase();
                }

                const qrPayload = tItem.qr_payload || sale.qr_payload || `VIVEGO|${sale.receipt_number || 'REC'}|EVT-${sale.event_id || eventId}|DNI-${sale.buyer_dni || '00000000'}|TICK-${numSeq}|${hashVal}`;
                const qrDataUrl = generateQrBase64(qrPayload);

                const isCourtesyTicket = isCourtesy || tItem.is_courtesy;
                const unitPriceVal = isCourtesyTicket ? '0.00' : parseFloat(tItem.price || sale.unit_price || sale.total_amount).toFixed(2);
                const priceDisplay = isCourtesyTicket ? 'CORTESÍA' : ('S/ ' + unitPriceVal);

                const dynamicData = {
                    title: eventTitle,
                    venue: eventVenue,
                    city: eventAddress,
                    date: eventDate,
                    time: eventTime,
                    zone: cleanZoneNameJs(tItem.zone || sale.zone_name),
                    price: priceDisplay,
                    buyer_name: sale.buyer_name || (isCourtesyTicket ? 'INVITADO DE CORTESÍA' : 'CLIENTE VARIOS'),
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

            return { pdf, ticketsList };
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
                const { pdf, ticketsList } = await generatePosTicketPdfDoc(sale);

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
                const el = document.querySelector('.posPdfSingleCanvas');
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

        // Enviar Entrada PDF oficial por correo desde Taquilla (POS)
        async function emailPosSalePdf(saleOrId) {
            const sale = getSaleObject(saleOrId);
            if (!sale) {
                console.error('[CanvaStudio POS Email] Venta no encontrada:', saleOrId);
                return;
            }

            // 1. Buscar si el comprador ya tiene un correo registrado
            let buyerEmail = '';
            if (sale.buyer_email && sale.buyer_email.includes('@')) {
                buyerEmail = sale.buyer_email.trim();
            }

            if (!buyerEmail && sale.tickets_data) {
                const td = (typeof sale.tickets_data === 'string') ? JSON.parse(sale.tickets_data) : sale.tickets_data;
                if (td) {
                    const candidate = td.customer_email || td.buyer_email || td.email;
                    if (candidate && candidate.includes('@')) {
                        buyerEmail = candidate.trim();
                    }
                }
            }

            // Buscar en lista de clientes existentes por DNI o Nombre
            if (!buyerEmail && window.posExistingClients && Array.isArray(window.posExistingClients)) {
                const found = window.posExistingClients.find(c => (c.dni && sale.buyer_dni && c.dni === sale.buyer_dni && c.email && c.email.includes('@')) || (c.name && sale.buyer_name && c.name.toLowerCase().trim() === sale.buyer_name.toLowerCase().trim() && c.email && c.email.includes('@')));
                if (found && found.email) {
                    buyerEmail = found.email.trim();
                }
            }

            // 2. Si NO tiene correo registrado, preguntar por SweetAlert (solo en caso de no existir)
            if (!buyerEmail) {
                const { value: emailInput } = await Swal.fire({
                    title: '✉️ Enviar Entrada por Correo',
                    text: `El cliente "${sale.buyer_name || 'Comprador'}" no tiene correo registrado. Ingresa el correo para enviar la entrada:`,
                    input: 'email',
                    inputPlaceholder: 'ejemplo@correo.com',
                    showCancelButton: true,
                    confirmButtonText: '📧 Enviar Boleto PDF',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#6366F1',
                    background: '#14141E',
                    color: '#FFFFFF',
                    inputValidator: (value) => {
                        if (!value || !value.includes('@') || !value.includes('.')) {
                            return 'Por favor ingresa un correo electrónico válido';
                        }
                    }
                });

                if (!emailInput) return;
                buyerEmail = emailInput.trim();
            }

            // 3. Compilar y enviar directamente sin preguntar si ya tenía correo
            Swal.fire({
                title: '📧 Enviando Entrada al Correo...',
                html: `Compilando diseño oficial del boleto y enviándolo a <strong>${buyerEmail}</strong>...`,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            try {
                const { pdf } = await generatePosTicketPdfDoc(sale);
                const pdfBase64 = pdf ? pdf.output('datauristring') : '';

                const res = await fetch(`/admin/taquilla/venta/${sale.id}/enviar-correo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: buyerEmail,
                        ticket_pdf_base64: pdfBase64
                    })
                });

                const data = await res.json();
                if (data.success) {
                    sale.buyer_email = buyerEmail;

                    Swal.fire({
                        icon: 'success',
                        title: '✉️ ¡Entrada Enviada con Éxito!',
                        html: `Se ha enviado el boleto oficial en PDF adjunto a <strong>${buyerEmail}</strong>.`,
                        confirmButtonColor: '#10B981',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo enviar',
                        text: data.message || 'Ocurrió un error al intentar enviar el correo.',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            } catch (err) {
                console.error('Error enviando PDF de entrada por correo:', err);
                const el = document.querySelector('.posPdfSingleCanvas');
                if (el) el.remove();

                Swal.fire({
                    icon: 'error',
                    title: 'Error de Envío',
                    text: 'Inconveniente al procesar o enviar el PDF: ' + (err.message || 'Error de red'),
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
        async function handlePosSaleSubmit(e) {
            e.preventDefault();

            const zoneName = document.getElementById('pos_zone_select')?.value || selectedZoneName;
            const quantity = parseInt(document.getElementById('pos_quantity').value, 10);
            const buyerName = document.getElementById('pos_buyer_name').value.trim();
            const buyerDni = document.getElementById('pos_buyer_dni').value.trim();
            const buyerPhone = document.getElementById('pos_buyer_phone').value.trim();
            let buyerEmail = document.getElementById('pos_buyer_email')?.value.trim() || '';
            const paymentMethod = document.getElementById('pos_payment_method').value;
            const amountPaid = parseFloat(document.getElementById('pos_amount_paid').value) || currentTotalToPay;

            const targetZone = getActivePosZone(zoneName);
            const hasSeats = targetZone && Array.isArray(targetZone.seats) && targetZone.seats.length > 0;
            if (hasSeats && posSelectedSeats.length === 0) {
                Swal.fire({
                    title: 'Selecciona tus Butacas',
                    text: 'Por favor elige al menos una butaca numerada en el plano para continuar.',
                    icon: 'warning',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            const finalQuantity = hasSeats ? posSelectedSeats.length : quantity;

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

            // Auto-detectar correo si no se escribió pero el cliente existe en el sistema
            if (!buyerEmail && window.posExistingClients && Array.isArray(window.posExistingClients)) {
                const found = window.posExistingClients.find(c => (c.dni && buyerDni && c.dni === buyerDni && c.email && c.email.includes('@')) || (c.name && buyerName && c.name.toLowerCase().trim() === buyerName.toLowerCase().trim() && c.email && c.email.includes('@')));
                if (found && found.email) {
                    buyerEmail = found.email.trim();
                }
            }

            const btnSubmit = document.getElementById('btnSubmitPosSale');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.textContent = '⏳ Procesando venta y emitiendo boletos...';
            }

            const payload = {
                zone_name: zoneName,
                quantity: finalQuantity,
                selected_seats: (hasSeats && posSelectedSeats.length > 0) ? posSelectedSeats : null,
                buyer_name: buyerName,
                buyer_dni: buyerDni,
                buyer_phone: buyerPhone,
                buyer_email: buyerEmail || null,
                payment_method: paymentMethod,
                amount_paid: amountPaid,
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
                    if (data.event && data.event.zones) {
                        window.posRawZones = data.event.zones;
                    }

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
                                    <button type="button" class="btn btn-info btn-sm" onclick="emailPosSalePdf(${data.sale.id})" title="Enviar Entrada al Correo" style="background: linear-gradient(135deg, #6366F1, #4F46E5); border: 1px solid rgba(99,102,241,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); cursor: pointer;">
                                        <span>✉️</span>
                                        <span>Enviar Correo</span>
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

                    // 3. Actualizar KPIs y disponibilidad en vivo
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

                    // 6. Enviar automáticamente entrada oficial en PDF idéntica a la plancha
                    const targetEmail = (buyerEmail && buyerEmail.includes('@')) ? buyerEmail : ((data.recipient && data.recipient.includes('@')) ? data.recipient : null);
                    if (targetEmail) {
                        (async () => {
                            try {
                                const { pdf } = await generatePosTicketPdfDoc(data.sale);
                                const realPdfBase64 = pdf ? pdf.output('datauristring') : '';
                                if (realPdfBase64) {
                                    await fetch(`/admin/taquilla/venta/${data.sale.id}/enviar-correo`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        body: JSON.stringify({
                                            email: targetEmail,
                                            ticket_pdf_base64: realPdfBase64
                                        })
                                    });
                                    console.log('[POS] Boleto oficial idéntico a plancha enviado a:', targetEmail);
                                }
                            } catch (autoEmailErr) {
                                console.warn('[POS Auto-Email Error]', autoEmailErr);
                            }
                        })();
                    }

                    // 7. Modal Interactivo de Confirmación (Recibo + Entrada PDF)
                    const emailSentMsg = targetEmail 
                        ? `<div style="margin-top: 0.6rem; font-size: 0.85rem; color: #10B981; font-weight: 700; background: rgba(16,185,129,0.12); padding: 0.4rem 0.75rem; border-radius: 10px; border: 1px solid rgba(16,185,129,0.3);">✉️ Boleto oficial idéntico enviado a <strong>${escapePosHtml(targetEmail)}</strong></div>` 
                        : '';

                    Swal.fire({
                        title: `🎉 ¡Venta Registrada Exitosamente!`,
                        html: `
                            <div style="font-size: 0.95rem; color: #CBD5E1; margin-bottom: 1.25rem;">
                                N° Recibo: <b style="color: #FF5500; font-family: monospace;">${data.receipt.receipt_number}</b> | Monto: <b style="color: #10B981;">${data.receipt.total_amount_formatted}</b>
                                ${emailSentMsg}
                            </div>
                            <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                                <button type="button" id="btnAlertPrintReceipt" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #FF5500, #FF7733); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(255,85,0,0.3);">
                                    <span>🧾</span> Imprimir Recibo
                                </button>
                                <button type="button" id="btnAlertDownloadPdf" class="btn btn-secondary btn-sm" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(6,182,212,0.3);">
                                    <span>🎟️</span> Descargar Entrada PDF
                                </button>
                                <button type="button" id="btnAlertEmailPdf" class="btn btn-info btn-sm" style="background: linear-gradient(135deg, #6366F1, #4F46E5); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                                    <span>✉️</span> Enviar al Correo
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
                            const btnE = document.getElementById('btnAlertEmailPdf');
                            if (btnE) btnE.addEventListener('click', () => {
                                emailPosSalePdf(data.sale.id);
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
        async function handlePosCourtesySubmit(e) {
            e.preventDefault();

            const zoneName = document.getElementById('pos_courtesy_zone_select')?.value || selectedCourtesyZoneName;
            const quantity = parseInt(document.getElementById('pos_courtesy_quantity').value, 10) || 1;
            const buyerName = document.getElementById('pos_courtesy_name').value.trim() || 'INVITADO DE CORTESÍA';
            const buyerDni = document.getElementById('pos_courtesy_dni').value.trim() || '00000000';
            let buyerEmail = document.getElementById('pos_courtesy_email')?.value.trim() || '';
            const buyerPhone = document.getElementById('pos_courtesy_phone').value.trim() || '-';
            const courtesyNote = document.getElementById('pos_courtesy_note')?.value || 'Cortesía Directa';

            // Auto-detectar correo si no se escribió pero el invitado/beneficiario existe en el sistema
            if (!buyerEmail && window.posExistingClients && Array.isArray(window.posExistingClients)) {
                const found = window.posExistingClients.find(c => (c.dni && buyerDni && c.dni === buyerDni && c.email && c.email.includes('@')) || (c.name && buyerName && c.name.toLowerCase().trim() === buyerName.toLowerCase().trim() && c.email && c.email.includes('@')));
                if (found && found.email) {
                    buyerEmail = found.email.trim();
                }
            }

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
                buyer_email: buyerEmail || null,
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
                        
                        const isWeb = (data.sale.seller_name && data.sale.seller_name.toLowerCase().includes('web'));
                        const courtesyBadge = isWeb
                            ? `<span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; background: rgba(0, 240, 255, 0.15); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;"><span>🌐</span> <span>Cortesía Web</span></span>`
                            : `<span class="dash-badge-custom badge-green" style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.35); font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;"><span>🎁</span> <span>Cortesía Adm</span></span>`;

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
                                ${courtesyBadge}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="reprintReceipt(${data.sale.id})" title="Reimprimir Recibo Térmico" style="background: linear-gradient(135deg, #10B981, #059669); border: 1px solid rgba(16,185,129,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); cursor: pointer;">
                                        <span>🧾</span>
                                        <span>Recibo</span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="downloadPosSalePdf(${data.sale.id})" title="Descargar Entrada PDF" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: 1px solid rgba(6,182,212,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); cursor: pointer;">
                                        <span>🎟️</span>
                                        <span>Entrada PDF</span>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" onclick="emailPosSalePdf(${data.sale.id})" title="Enviar Entrada al Correo" style="background: linear-gradient(135deg, #6366F1, #4F46E5); border: 1px solid rgba(99,102,241,0.6); color: #FFFFFF; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); cursor: pointer;">
                                        <span>✉️</span>
                                        <span>Enviar Correo</span>
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

                    // 6. Auto-enviar entrada oficial idéntica a la plancha por correo
                    const targetCourtesyEmail = (buyerEmail && buyerEmail.includes('@')) ? buyerEmail : ((data.recipient && data.recipient.includes('@')) ? data.recipient : null);
                    if (targetCourtesyEmail) {
                        (async () => {
                            try {
                                const { pdf } = await generatePosTicketPdfDoc(data.sale);
                                const realPdfBase64 = pdf ? pdf.output('datauristring') : '';
                                if (realPdfBase64) {
                                    await fetch(`/admin/taquilla/venta/${data.sale.id}/enviar-correo`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        body: JSON.stringify({
                                            email: targetCourtesyEmail,
                                            ticket_pdf_base64: realPdfBase64
                                        })
                                    });
                                    console.log('[POS] Pase oficial de cortesía idéntico a plancha enviado a:', targetCourtesyEmail);
                                }
                            } catch (autoCourtesyEmailErr) {
                                console.warn('[POS Courtesy Auto-Email Error]', autoCourtesyEmailErr);
                            }
                        })();
                    }

                    // 7. Modal flotante de confirmación y opciones rápidas
                    const courtesyEmailSentMsg = targetCourtesyEmail 
                        ? `<div style="margin-top: 0.6rem; font-size: 0.85rem; color: #10B981; font-weight: 700; background: rgba(16,185,129,0.12); padding: 0.4rem 0.75rem; border-radius: 10px; border: 1px solid rgba(16,185,129,0.3);">✉️ Pase oficial idéntico enviado a <strong>${escapePosHtml(targetCourtesyEmail)}</strong></div>` 
                        : '';

                    Swal.fire({
                        icon: 'success',
                        title: '¡Cortesía Emitida con Éxito!',
                        html: `
                            <div style="color: #94A3B8; font-size: 0.95rem; margin-bottom: 1.25rem;">
                                Se generó el pase oficial <strong>${data.receipt.receipt_number}</strong> para <strong>${escapePosHtml(buyerName)}</strong> (${quantity} entrada(s) de cortesía).
                                ${courtesyEmailSentMsg}
                            </div>
                            <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                                <button type="button" id="btnAlertPrintReceiptCourtesy" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #10B981, #059669); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                                    <span>🧾</span> Imprimir Recibo
                                </button>
                                <button type="button" id="btnAlertDownloadPdfCourtesy" class="btn btn-secondary btn-sm" style="background: linear-gradient(135deg, #06B6D4, #0284C7); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(6,182,212,0.3);">
                                    <span>🎟️</span> Descargar Entrada PDF
                                </button>
                                <button type="button" id="btnAlertEmailPdfCourtesy" class="btn btn-info btn-sm" style="background: linear-gradient(135deg, #6366F1, #4F46E5); border: none; color: #FFFFFF; padding: 0.65rem 1.25rem; font-weight: 800; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                                    <span>✉️</span> Enviar al Correo
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
                            const btnE = document.getElementById('btnAlertEmailPdfCourtesy');
                            if (btnE) btnE.addEventListener('click', () => {
                                emailPosSalePdf(data.sale.id);
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
                @if($isCourtesyActive)
                    openPosCourtesyModal();
                @endif
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
