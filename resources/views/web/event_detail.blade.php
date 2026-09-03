@extends('layouts.app')

@section('title', $event['title'] . ' | Vive Go Eventos')

@section('content')
    @if(($event['layout_template'] ?? 'template_1') === 'template_2')
        <!-- =========================================================
             PLANTILLA 2: ESTILO INMERSIVO (FONDO DUAL: PC 16:9 Y MÓVIL 9:16 + ARTISTA MAX + ZONAS SIN SOMBRA + CARDS BLANCAS)
             ========================================================= -->
        <style>
            body {
                background-color: #0A0E1A !important;
            }
            .main-content {
                background: transparent !important;
                padding-top: 0 !important;
            }
            .template2-backdrop {
                position: fixed;
                inset: 0;
                background-image: url('{{ $event['background_image'] ?: $event['banner_image'] }}');
                background-size: 100% auto;
                background-position: center top;
                background-repeat: repeat-y;
                background-attachment: fixed;
                z-index: -2;
            }
            /* Dispositivos Móviles y Tablets: Activa el Fondo 9:16 (1080x1920 px) */
            @media (max-width: 768px) {
                .template2-backdrop {
                    background-image: url('{{ $event['background_mobile_image'] ?: ($event['background_image'] ?: $event['banner_image']) }}') !important;
                    background-size: cover !important;
                    background-position: center top !important;
                    background-repeat: no-repeat !important;
                }
                .template2-main-container {
                    padding-left: 0.75rem !important;
                    padding-right: 0.75rem !important;
                }
                .template2-booking-card {
                    padding: 1.25rem 1rem !important;
                }
            }
        </style>

        <!-- Fondo Fijo Dual: 16:9 en PC y 9:16 en Móviles -->
        <div class="template2-backdrop"></div>

        <div class="template2-main-container" style="position: relative; z-index: 1; max-width: 860px; margin: 0 auto; padding: 2rem 1rem 5rem 1rem; display: flex; flex-direction: column; align-items: center; gap: 2.5rem;">
            
            <!-- 1. IMAGEN DEL ARTISTA EXTRA GRANDE (SIN TEXTOS NI INTERFERENCIAS) -->
            @php
                $artistImgSrc = $event['artist_image'] ?: $event['banner_image'];
            @endphp
            <div id="artistHeroWrapper" style="width: 100%; text-align: center; display: flex; justify-content: center; margin-bottom: 0.5rem;">
                <img id="artistHeroImg" src="{{ $artistImgSrc }}" alt="{{ $event['title'] }}" style="width: 100%; max-width: 860px; height: auto; object-fit: contain; display: block; margin: 0 auto; filter: drop-shadow(0 15px 35px rgba(0,0,0,0.7));">
            </div>

            <!-- 2. MAPA INTERACTIVO DE ZONAS O IMAGEN DE REFERENCIA -->
            @include('web.events.partials.public_interactive_seat_map')

            <!-- 3. SELECCIÓN DE ENTRADAS Y COMPRA (TEMA BLANCO) -->
            <div class="template2-booking-card" style="width: 100%; max-width: 860px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 1.75rem 1.5rem; box-shadow: 0 15px 40px rgba(0,0,0,0.18);">
                
                @if(count($event['dates']) > 1)
                    <!-- Selector de Fecha si hay múltiples -->
                    <div class="dates-selector-group" style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.25rem;">
                        @foreach($event['dates'] as $dateItem)
                            <div class="date-select-card" data-date-id="{{ $dateItem['id'] }}" style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 0.85rem 1.15rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                                <div class="date-select-info">
                                    <span class="date-select-day" style="font-size: 0.95rem; font-weight: 800; color: #0F172A;">📅 {{ $dateItem['date'] }}</span>
                                    <span class="date-select-time" style="font-size: 0.825rem; color: #64748B;">🕒 {{ $dateItem['time'] }}</span>
                                </div>
                                <div class="date-select-checkmark-orange" style="color: var(--color-primary-orange); font-weight: 900;">✓</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="display: none;">
                        @foreach($event['dates'] as $dateItem)
                            <div class="date-select-card selected" data-date-id="{{ $dateItem['id'] }}">
                                <span class="date-select-day">{{ $dateItem['date'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!empty($event['active_campaign']))
                    <div style="background: {{ $event['active_campaign']['banner_color'] ?? '#FF5500' }}; color: #FFFFFF; padding: 0.75rem 1rem; border-radius: 12px; margin-bottom: 1.1rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <span style="font-size: 1.3rem;">🔥</span>
                            <div>
                                <strong style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; display: block;">{{ $event['active_campaign']['badge_text'] }}</strong>
                                <span style="font-size: 0.75rem; opacity: 0.9;">Descuento comercial automático aplicado en todas las entradas</span>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 800; background: rgba(0,0,0,0.25); padding: 0.25rem 0.6rem; border-radius: 6px; white-space: nowrap;">
                            Válido hasta {{ $event['active_campaign']['end_at_display'] }}
                        </span>
                    </div>
                @endif

                <!-- Lista de Zonas / Boletos en Blanco -->
                <div class="tickets-list-box" style="display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.25rem;">
                    @foreach($event['tickets'] as $ticket)
                        @php
                            $isAvail = !empty($ticket['available']);
                            $hasCamp = !empty($ticket['has_campaign']);
                        @endphp
                        <div class="ticket-type-row" 
                             data-zone-name="{{ $ticket['name'] }}"
                             data-price="{{ $ticket['price'] }}"
                             data-regular-price="{{ $ticket['regular_price'] }}"
                             data-is-presale="{{ $ticket['is_presale_active'] ? 'true' : 'false' }}"
                             data-presale-discount="{{ $ticket['presale_discount'] }}"
                             data-has-campaign="{{ $hasCamp ? 'true' : 'false' }}"
                             data-available="{{ $isAvail ? 'true' : 'false' }}"
                             style="background: {{ $isAvail ? '#FFFFFF' : '#F1F5F9' }}; border: 1.5px solid {{ $isAvail ? '#E2E8F0' : '#CBD5E1' }}; border-radius: 14px; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; position: relative; {{ !$isAvail ? 'opacity: 0.65;' : '' }}">
                            
                            <div class="ticket-type-info" style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <span class="ticket-name" style="font-size: 1.05rem; font-weight: 800; color: {{ $isAvail ? '#0F172A' : '#64748B' }};">{{ $ticket['name'] }}</span>
                                    
                                    @if(!$isAvail)
                                        <span style="background: #EF4444; color: #FFFFFF; font-size: 0.7rem; font-weight: 900; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">
                                            🚫 AGOTADO
                                        </span>
                                    @elseif($hasCamp)
                                        <span style="background: {{ $ticket['campaign_color'] ?? '#FF5500' }}; color: #FFFFFF; font-size: 0.725rem; font-weight: 900; padding: 2px 8px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); text-transform: uppercase; letter-spacing: 0.5px;">
                                            {{ $ticket['campaign_badge'] }}
                                        </span>
                                    @elseif($ticket['is_presale_active'])
                                        <span style="background: linear-gradient(135deg, #FF5500, #FF1E3C); color: #FFFFFF; font-size: 0.725rem; font-weight: 900; padding: 2px 8px; border-radius: 6px; box-shadow: 0 2px 6px rgba(255,85,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                                            🔥 PREVENTA -{{ $ticket['presale_discount'] }}%
                                        </span>
                                    @endif
                                </div>
                                
                                @if(!$isAvail)
                                    <span style="font-size: 0.8rem; color: #EF4444; font-weight: 700; display: block; margin-top: 0.2rem;">Entradas agotadas</span>
                                @elseif($hasCamp)
                                    <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-top: 0.35rem;">
                                        <span class="ticket-price" style="font-size: 1.25rem; font-weight: 900; color: var(--color-primary-orange);">S/ {{ number_format($ticket['price'], 2) }}</span>
                                        <span style="font-size: 0.9rem; color: #94A3B8; text-decoration: line-through; font-weight: 600;">S/ {{ number_format($ticket['effective_price'] ?? $ticket['regular_price'], 2) }}</span>
                                    </div>
                                @elseif($ticket['is_presale_active'])
                                    <div style="font-size: 0.775rem; color: #E11D48; font-weight: 700; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.35rem;">
                                        <span>⏳ Válido {{ !empty($ticket['presale_end_date']) ? 'hasta el ' . \Carbon\Carbon::parse($ticket['presale_end_date'])->format('d/m/Y') : '' }} o hasta agotar stock{{ !empty($ticket['presale_stock']) ? ' (' . $ticket['presale_stock'] . ' cupos)' : '' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-top: 0.25rem;">
                                        <span class="ticket-price" style="font-size: 1.25rem; font-weight: 900; color: var(--color-primary-orange);">S/ {{ number_format($ticket['price'], 2) }}</span>
                                        <span style="font-size: 0.9rem; color: #94A3B8; text-decoration: line-through; font-weight: 600;">S/ {{ number_format($ticket['regular_price'], 2) }}</span>
                                    </div>
                                @else
                                    <span class="ticket-price" style="font-size: 1.2rem; font-weight: 900; color: var(--color-primary-orange); margin-top: 0.2rem; display: block;">S/ {{ number_format($ticket['price'], 2) }}</span>
                                @endif
                            </div>

                            <div class="ticket-quantity-counter" style="display: flex; align-items: center; gap: 0.65rem; background: #EDF2F7; padding: 0.35rem 0.65rem; border-radius: 10px; border: 1px solid #CBD5E1; {{ !$isAvail ? 'opacity: 0.35; pointer-events: none;' : '' }}">
                                <button type="button" class="counter-btn minus btn-ticket-minus" {{ !$isAvail ? 'disabled' : '' }} style="width: 32px; height: 32px; border-radius: 6px; border: none; background: #E2E8F0; color: #0F172A; font-size: 1.15rem; font-weight: 800; cursor: pointer;">-</button>
                                <span class="counter-value ticket-count-val" style="font-size: 1.1rem; font-weight: 800; color: #0F172A; min-width: 24px; text-align: center;">0</span>
                                <button type="button" class="counter-btn plus btn-ticket-plus" {{ !$isAvail ? 'disabled' : '' }} style="width: 32px; height: 32px; border-radius: 6px; border: none; background: var(--color-primary-orange); color: #FFFFFF; font-size: 1.15rem; font-weight: 800; cursor: pointer;">+</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cupón de Descuento y Total en Blanco -->
                <div style="background: #F1F5F9; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.1rem; margin-bottom: 1.25rem;">
                    <div style="margin-bottom: 0.75rem;">
                        <a href="javascript:void(0)" class="promo-code-link-orange" id="btnTogglePromoCode" style="font-size: 0.85rem; font-weight: 800; color: var(--color-primary-orange); text-decoration: none;">¿Tienes un código de descuento?</a>
                        <div class="promo-code-input-box" id="promoCodeInputBox" style="display: none; margin-top: 0.5rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="text" id="inputPromoCode" placeholder="Ingresa tu código..." style="flex: 1; padding: 0.55rem 0.75rem; border-radius: 8px; border: 1.5px solid #CBD5E1; background: #FFFFFF; color: #0F172A; font-size: 0.85rem; font-weight: 700;">
                                <button type="button" id="btnApplyPromoCode" class="btn btn-primary btn-sm" style="padding: 0.55rem 0.95rem; border-radius: 8px; font-size: 0.85rem; font-weight: 800;">Aplicar</button>
                            </div>
                            <div id="promoCodeMsg" style="font-size: 0.775rem; margin-top: 0.4rem; font-weight: 700; display: none;"></div>
                        </div>
                    </div>

                    <div class="total-summary-row" style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="total-label" style="font-size: 1rem; color: #475569; font-weight: 700;">Total a Pagar:</span>
                        <span class="total-price-value" id="totalPriceDisplay" style="font-size: 1.65rem; font-weight: 900; color: var(--color-primary-orange);">S/ 0.00</span>
                    </div>
                </div>

                <!-- Botón de Compra -->
                @if(!empty($event['all_sold_out']))
                    <button class="btn btn-secondary btn-checkout-sticky" style="width: 100%; padding: 1rem; font-size: 1.1rem; font-weight: 800; border-radius: 12px; background: #64748B; color: #FFFFFF; cursor: not-allowed; opacity: 0.75; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: none;" disabled>
                        <span>🚫 ENTRADAS AGOTADAS</span>
                    </button>
                @else
                    <button class="btn btn-primary btn-checkout-sticky" id="btnOpenAuthModal" style="width: 100%; padding: 1rem; font-size: 1.1rem; font-weight: 800; border-radius: 12px; box-shadow: 0 8px 25px rgba(255, 85, 0, 0.4); text-transform: uppercase; letter-spacing: 0.5px;">
                        <span>COMPRAR ENTRADAS ➔</span>
                    </button>
                @endif

            </div>

            <!-- Información Adicional Desplegable (Ubicación / Detalles) (TEMA BLANCO) -->
            <div style="width: 100%; max-width: 760px; display: flex; flex-direction: column; gap: 0.85rem; margin-top: 0.25rem;">
                
                <!-- Recinto / Local Blanco -->
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.15rem 1.35rem; box-shadow: 0 10px 30px rgba(0,0,0,0.12);">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; color: #64748B; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">📍 Local / Recinto:</span>
                            <strong style="display: block; font-size: 1.05rem; color: #0F172A; margin-top: 0.2rem;">{{ $event['venue']['name'] }}</strong>
                            <span style="font-size: 0.85rem; color: #475569;">{{ $event['venue']['address'] }}</span>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm btn-map-modal-trigger" id="btnOpenMapModal" style="padding: 0.55rem 0.95rem; font-size: 0.8rem; font-weight: 700; border-radius: 8px; background: #F1F5F9; color: #0F172A; border: 1px solid #CBD5E1; white-space: nowrap;">
                            <span>Ver Mapa</span>
                        </button>
                    </div>
                </div>

                <!-- Detalles / Descripción Blanco -->
                @if(!empty($event['details']) && count($event['details']) > 0)
                    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.15rem 1.35rem; box-shadow: 0 10px 30px rgba(0,0,0,0.12);">
                        <details style="cursor: pointer;">
                            <summary style="font-size: 0.95rem; font-weight: 800; color: #0F172A; outline: none;">
                                📋 Ver detalles y descripción del evento
                            </summary>
                            <div class="details-content-box" style="color: #334155; font-size: 0.875rem; line-height: 1.65; margin-top: 0.85rem;">
                                @foreach($event['details'] as $paragraph)
                                    <p style="margin-bottom: 0.5rem;">{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        </details>
                    </div>
                @endif

            </div>

        </div>
    @else
        <!-- ==========================================
             PLANTILLA 1: ESTÁNDAR (CLÁSICA VIVE GO)
             ========================================== -->
        <div class="event-detail-wrapper container">
            <!-- Breadcrumbs Navigation -->
            <nav class="detail-breadcrumbs">
                <a href="{{ route('web.home') }}" style="color: var(--color-primary-orange);">Inicio</a> &nbsp; / &nbsp;
                <span>{{ $event['category'] }}</span> &nbsp; / &nbsp;
                <span style="color: var(--text-primary);">{{ $event['title'] }}</span>
            </nav>

            <!-- Layout Principal: 2 Columnas (Izquierda: Banner + Info Detallada | Derecha: Booking Sidebar Sticky) -->
            <div class="event-detail-grid">
                <!-- Columna Izquierda: Banner Principal e Información del Evento -->
                <div class="event-detail-main">
                    <!-- Poster Banner Principal del Evento -->
                    <div class="event-banner-stage detail-order-1">
                        <img src="{{ $event['banner_image'] }}" alt="{{ $event['title'] }}" class="event-banner-img">
                    </div>

                    <!-- Metadatos: Tags a la izquierda + Advisory Pill a la DERECHA -->
                    <div class="event-media-subrow detail-order-2">
                        <div class="event-meta-tags">
                            <span class="meta-tag-category">🔥 {{ $event['category'] }}</span>
                            <span class="meta-tag-location">📍 {{ $event['city'] }}</span>
                        </div>

                        <!-- Advisory Pill Orange -->
                        <div class="event-advisory-pill-orange">
                            <span class="advisory-badge-g-orange">G</span>
                            <span>{{ $event['advisory'] }}</span>
                        </div>
                    </div>

                    <h1 class="event-detail-title detail-order-3">{{ $event['title'] }}</h1>
                    <p class="event-detail-subtitle detail-order-4">{{ $event['subtitle'] }}</p>

                    <hr class="event-section-divider detail-order-5">

                    <!-- Sección: Ubicación -->
                    <section class="event-info-block animate-fade-in detail-order-7">
                        <div class="info-block-header">
                            <div class="info-block-icon">📍</div>
                            <h2>Ubicación y Recinto</h2>
                        </div>

                        <div class="location-card-box location-card-flex-row">
                            <div class="location-info-col">
                                <h3 class="location-venue-name">{{ $event['venue']['name'] }}</h3>
                                <p class="location-venue-address">{{ $event['venue']['address'] }}</p>
                            </div>

                            <button type="button" class="btn btn-secondary btn-sm btn-map-modal-trigger" id="btnOpenMapModal">
                                <span>📍 Ver ubicación en el mapa</span>
                            </button>
                        </div>
                    </section>

                    <hr class="event-section-divider detail-order-8">

                    <!-- Sección: Mapa Interactivo de Zonas o Imagen de Referencia -->
                    @include('web.events.partials.public_interactive_seat_map')

                    <!-- Sección: Detalles del Evento (Columna Izquierda Principal) -->
                    <section class="event-info-block animate-fade-in detail-order-9">
                        <div class="info-block-header">
                            <div class="info-block-icon">📋</div>
                            <h2>Detalles del evento</h2>
                        </div>

                        <div class="details-content-box">
                            @foreach($event['details'] as $index => $paragraph)
                                <p class="details-paragraph {{ $index >= 2 ? 'details-extra-paragraph' : '' }}" style="{{ $index >= 2 ? 'display: none;' : '' }}">
                                    {{ $paragraph }}
                                </p>
                            @endforeach

                            @if(count($event['details']) > 2)
                                <button type="button" id="btnToggleDetails" class="btn-toggle-details-orange">
                                    <span id="toggleDetailsText">Ver más</span>
                                    <span id="toggleDetailsIcon">➔</span>
                                </button>
                            @endif
                        </div>
                    </section>
                </div>

                <!-- Columna Derecha: Sticky Booking Box + Organiza & Etiquetas Pro Max -->
                <div class="event-detail-sidebar">
                    <div class="booking-sticky-card detail-order-6">

                        <!-- Sección: Fecha y Hora Selector Dinámico -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-section-title">Fecha y Hora</h3>

                            <div class="dates-selector-group">
                                @foreach($event['dates'] as $dateItem)
                                    <div class="date-select-card"
                                        data-date-id="{{ $dateItem['id'] }}">
                                        <div class="date-select-info">
                                            <span class="date-select-day">📅 {{ $dateItem['date'] }}</span>
                                            <span class="date-select-time">🕒 {{ $dateItem['time'] }}</span>
                                        </div>
                                        <div class="date-select-checkmark-orange">✓</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sección: Entradas Selector Dinámico -->
                        <div class="sidebar-section">
                            <div class="tickets-header-row">
                                <h3 class="sidebar-section-title" style="margin-bottom: 0.5rem;">Entradas</h3>
                            </div>

                            @if(!empty($event['active_campaign']))
                                <div style="background: {{ $event['active_campaign']['banner_color'] ?? '#FF5500' }}; color: #FFFFFF; padding: 0.65rem 0.85rem; border-radius: 10px; margin-bottom: 0.85rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                                    <div style="display: flex; align-items: center; gap: 0.45rem;">
                                        <span style="font-size: 1.1rem;">🔥</span>
                                        <strong style="font-size: 0.85rem; text-transform: uppercase;">{{ $event['active_campaign']['badge_text'] }}</strong>
                                    </div>
                                    <span style="font-size: 0.7rem; font-weight: 800; background: rgba(0,0,0,0.25); padding: 0.2rem 0.5rem; border-radius: 5px;">
                                        Hasta {{ $event['active_campaign']['end_at_display'] }}
                                    </span>
                                </div>
                            @endif

                            <div class="tickets-list-box">
                                @foreach($event['tickets'] as $ticket)
                                    @php
                                        $isAvail = !empty($ticket['available']);
                                        $isCourtesy = !empty($ticket['is_courtesy']);
                                        $hasCamp = !empty($ticket['has_campaign']);
                                        $maxQty = !empty($ticket['max_quantity']) ? $ticket['max_quantity'] : 99;
                                    @endphp
                                    <div class="ticket-type-row" 
                                         data-price="{{ $ticket['price'] }}"
                                         data-regular-price="{{ $ticket['regular_price'] }}"
                                         data-is-presale="{{ $ticket['is_presale_active'] ? 'true' : 'false' }}"
                                         data-presale-discount="{{ $ticket['presale_discount'] }}"
                                         data-has-campaign="{{ $hasCamp ? 'true' : 'false' }}"
                                         data-is-courtesy="{{ $isCourtesy ? 'true' : 'false' }}"
                                         data-max-quantity="{{ $maxQty }}"
                                         data-available="{{ $isAvail ? 'true' : 'false' }}"
                                         style="{{ $isCourtesy ? 'border: 1.5px solid #10B981; background: rgba(16,185,129,0.04);' : ($hasCamp ? 'border: 1.5px solid ' . ($ticket['campaign_color'] ?? '#FF5500') . ';' : ($ticket['is_presale_active'] && $isAvail ? 'border-color: rgba(255, 85, 0, 0.4);' : '')) }} {{ !$isAvail ? 'opacity: 0.6; background: rgba(255,255,255,0.02);' : '' }}">
                                        
                                        <div class="ticket-type-info" style="flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                                <span class="ticket-name" style="{{ $isCourtesy ? 'color: #065F46; font-weight: 800;' : '' }}">
                                                    {{ $isCourtesy ? '🎁' : '🎟️' }} {{ $ticket['name'] }}
                                                </span>
                                                @if(!$isAvail)
                                                    <span style="background: #EF4444; color: #FFFFFF; font-size: 0.65rem; font-weight: 900; padding: 1px 6px; border-radius: 5px; text-transform: uppercase;">
                                                        🚫 AGOTADO
                                                    </span>
                                                @elseif($isCourtesy)
                                                    <span style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-size: 0.675rem; font-weight: 900; padding: 1px 6px; border-radius: 5px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(16,185,129,0.3);">
                                                        ✨ GRATIS / FREE
                                                    </span>
                                                @elseif($hasCamp)
                                                    <span style="background: {{ $ticket['campaign_color'] ?? '#FF5500' }}; color: #FFFFFF; font-size: 0.675rem; font-weight: 900; padding: 1px 6px; border-radius: 5px; text-transform: uppercase;">
                                                        {{ $ticket['campaign_badge'] }}
                                                    </span>
                                                @elseif($ticket['is_presale_active'])
                                                    <span style="background: linear-gradient(135deg, #FF5500, #FF1E3C); color: #FFFFFF; font-size: 0.675rem; font-weight: 900; padding: 1px 6px; border-radius: 5px; box-shadow: 0 2px 4px rgba(255,85,0,0.3); text-transform: uppercase;">
                                                        🔥 PREVENTA -{{ $ticket['presale_discount'] }}%
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if(!$isAvail)
                                                <div style="font-size: 0.725rem; color: #EF4444; font-weight: 700; margin-top: 0.15rem;">
                                                    Entradas agotadas
                                                </div>
                                            @elseif($isCourtesy)
                                                <div style="font-size: 0.725rem; color: #047857; font-weight: 700; margin-top: 0.15rem;">
                                                    🎁 Entrada de cortesía (Máximo 2 por usuario)
                                                </div>
                                                <div style="margin-top: 0.2rem;">
                                                    <span class="ticket-price" style="color: #10B981; font-weight: 900; font-size: 1.15rem;">S/ 0.00 <span style="font-size: 0.75rem; color: #059669; font-weight: 700;">(GRATIS)</span></span>
                                                </div>
                                            @elseif($hasCamp)
                                                <div style="display: flex; align-items: baseline; gap: 0.45rem; margin-top: 0.25rem;">
                                                    <span class="ticket-price" style="font-size: 1.15rem; font-weight: 900; color: var(--color-primary-orange);">S/ {{ number_format($ticket['price'], 2) }}</span>
                                                    <span style="font-size: 0.85rem; color: #94A3B8; text-decoration: line-through;">S/ {{ number_format($ticket['effective_price'] ?? $ticket['regular_price'], 2) }}</span>
                                                </div>
                                            @elseif($ticket['is_presale_active'])
                                                <div style="font-size: 0.725rem; color: #FF5500; font-weight: 700; margin-top: 0.15rem;">
                                                    ⏳ Válido {{ !empty($ticket['presale_end_date']) ? 'hasta el ' . \Carbon\Carbon::parse($ticket['presale_end_date'])->format('d/m/Y') : '' }} o agotar stock
                                                </div>
                                                <div style="display: flex; align-items: baseline; gap: 0.45rem; margin-top: 0.2rem;">
                                                    <span class="ticket-price" style="font-size: 1.15rem; font-weight: 900; color: var(--color-primary-orange);">S/ {{ number_format($ticket['price'], 2) }}</span>
                                                    <span style="font-size: 0.85rem; color: #94A3B8; text-decoration: line-through;">S/ {{ number_format($ticket['regular_price'], 2) }}</span>
                                                </div>
                                            @else
                                                <span class="ticket-price">S/ {{ number_format($ticket['price'], 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="ticket-quantity-counter" style="{{ !$isAvail ? 'opacity: 0.35; pointer-events: none;' : '' }}">
                                            <button type="button" class="counter-btn minus btn-ticket-minus" {{ !$isAvail ? 'disabled' : '' }}>-</button>
                                            <span class="counter-value ticket-count-val">0</span>
                                            <button type="button" class="counter-btn plus btn-ticket-plus" {{ !$isAvail ? 'disabled' : '' }}>+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Botón CTA de Compra Sticky con Código de Descuento en la zona del Total -->
                        <div class="sidebar-checkout-cta">
                            <!-- Enlace y Campo para Código de Descuento arriba del Total -->
                            <div style="margin-bottom: 0.85rem; padding-bottom: 0.65rem; border-bottom: 1px dashed #CBD5E1;">
                                <a href="javascript:void(0)" class="promo-code-link-orange" id="btnTogglePromoCode" style="font-size: 0.85rem; font-weight: 700; display: inline-block;">¿Tienes un código?</a>
                                <div class="promo-code-input-box" id="promoCodeInputBox" style="display: none; margin-top: 0.5rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <input type="text" id="inputPromoCode" placeholder="Ingresa tu código..." style="flex: 1; padding: 0.5rem 0.75rem; border-radius: 12px; border: 1.5px solid #CBD5E1; font-size: 0.825rem; font-weight: 700;">
                                        <button type="button" id="btnApplyPromoCode" class="btn btn-primary btn-sm" style="padding: 0.5rem 0.85rem; border-radius: 12px; font-size: 0.8rem;">Aplicar</button>
                                    </div>
                                    <div id="promoCodeMsg" style="font-size: 0.775rem; margin-top: 0.35rem; font-weight: 700; display: none;"></div>
                                </div>
                            </div>

                            <div class="total-summary-row">
                                <span class="total-label">Total a Pagar</span>
                                <span class="total-price-value" id="totalPriceDisplay">S/ 0.00</span>
                            </div>

                            @if(!empty($event['all_sold_out']))
                                <button class="btn btn-secondary btn-checkout-sticky" style="background: #64748B; color: #FFFFFF; cursor: not-allowed; opacity: 0.75; width: 100%; justify-content: center;" disabled>
                                    <span>🚫 Entradas Agotadas</span>
                                </button>
                            @else
                                <button class="btn btn-primary btn-checkout-sticky" id="btnOpenAuthModal">
                                    <span>Comprar Entradas</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Organiza (Sidebar) -->
                    <div class="sidebar-info-card detail-order-11 animate-fade-in" style="margin-top: 0.85rem;">
                        <div class="info-block-header" style="margin-bottom: 0.75rem;">
                            <div class="info-block-icon">🏢</div>
                            <h2 style="font-size: 1.25rem;">Organiza</h2>
                        </div>

                        <div class="organizer-info-box" style="padding: 1.15rem 1.25rem; border-radius: 20px;">
                            <h3 class="organizer-company-name" style="font-size: 1.1rem; margin-bottom: 0.2rem;">{{ $event['organizer']['name'] }}</h3>
                            <p class="organizer-ruc" style="font-size: 0.825rem; color: var(--text-secondary);">RUC - {{ $event['organizer']['ruc'] }}</p>
                        </div>
                    </div>

                    <!-- Sección: Etiquetas (Hashtags) en Sidebar -->
                    <div class="sidebar-info-card detail-order-13 animate-fade-in" style="margin-top: 0.85rem;">
                        <div class="info-block-header" style="margin-bottom: 0.75rem;">
                            <div class="info-block-icon">🏷️</div>
                            <h2 style="font-size: 1.25rem;">Etiquetas del Evento</h2>
                        </div>

                        <div class="tags-cloud-container">
                            @foreach($event['tags'] as $tag)
                                <span class="tag-pill-item">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL PANORÁMICO DE 2 COLUMNAS (AUTH MODAL) -->
    <div class="auth-modal-overlay" id="authModal">
        <div class="auth-modal-split-container">
            <button class="auth-modal-close" id="btnCloseAuthModal" aria-label="Cerrar">&times;</button>

            <!-- Columna Izquierda: Formularios de Autenticación -->
            <div class="auth-modal-split-left">
                <div class="auth-modal-header-left">
                    <h2 class="auth-modal-title" id="authModalTitleText">Iniciar Sesión</h2>
                    <p class="auth-modal-subtitle" id="authModalSubtitleText">Ingresa con tu cuenta para continuar con tu compra.</p>
                </div>

                <!-- Pestañas de Alternancia -->
                <div class="auth-tabs-nav-capsule">
                    <button class="auth-tab-btn active" id="tabBtnLogin">Iniciar Sesión</button>
                    <button class="auth-tab-btn" id="tabBtnRegister">Crear Cuenta</button>
                </div>

                <!-- Formulario 1: Iniciar Sesión -->
                <form class="auth-form active" id="formLogin"
                    onsubmit="event.preventDefault(); window.location.href = '{{ route('web.dashboard') }}';">
                    <div class="form-group-item">
                        <label class="field-static-label">Correo electrónico</label>
                        <input type="email" class="form-control-input" placeholder="ejemplo@vivego.pe" required>
                    </div>
                    <div class="form-group-item">
                        <label class="field-static-label">Contraseña</label>
                        <div class="password-field-container">
                            <input type="password" id="inputLoginPass" class="form-control-input" placeholder="••••••••" required>
                            <span class="password-eye-toggle" id="toggleLoginPass">👁</span>
                        </div>
                    </div>
                    <div class="form-row-between-login">
                        <label class="checkbox-custom-item">
                            <input type="checkbox" class="orange-checkbox" checked>
                            <span>Recordarme</span>
                        </label>
                        <a href="#" class="forgot-pass-orange-link">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-submit-orange" style="margin-top: 1.25rem;">
                        Iniciar Sesión ➔
                    </button>
                </form>

                <!-- Formulario 2: Crear Cuenta -->
                <form class="auth-form" id="formRegister"
                    onsubmit="event.preventDefault(); alert('¡Cuenta registrada con éxito en Vive Go! Redirigiendo al pago...');">
                    <div class="form-row-2col">
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">Correo electrónico</label>
                            <input type="email" class="form-control-input" placeholder="Correo electrónico" required>
                        </div>
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">Contraseña</label>
                            <div class="password-field-container">
                                <input type="password" id="inputRegPass" class="form-control-input" placeholder="Contraseña" required>
                                <span class="password-eye-toggle" id="toggleRegPass">👁</span>
                            </div>
                        </div>
                    </div>

                    <!-- SEPARADOR REINCORPORADO ARRIBA DE NOMBRE Y APELLIDOS -->
                    <div class="auth-form-section-divider">
                        <span>Datos Personales</span>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">Nombre</label>
                            <input type="text" class="form-control-input" placeholder="Nombre" required>
                        </div>
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">Apellidos</label>
                            <input type="text" class="form-control-input" placeholder="Apellidos" required>
                        </div>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">País</label>
                            <select class="form-control-select" id="selectCountry" required>
                                <option value="PE" selected>Perú 🇵🇪</option>
                                <option value="CL">Chile 🇨🇱</option>
                                <option value="CO">Colombia 🇨🇴</option>
                                <option value="AR">Argentina 🇦🇷</option>
                                <option value="MX">México 🇲🇽</option>
                                <option value="EC">Ecuador 🇪🇨</option>
                                <option value="BO">Bolivia 🇧🇴</option>
                                <option value="ES">España 🇪🇸</option>
                                <option value="US">Estados Unidos 🇺🇸</option>
                            </select>
                        </div>
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">Selecciona una ciudad</label>
                            <select class="form-control-select" id="selectCity" required>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group-item">
                            <div class="joined-doc-group">
                                <select class="doc-prefix-select">
                                    <option value="DNI" selected>DNI</option>
                                    <option value="CE">CE</option>
                                    <option value="PAS">PAS</option>
                                    <option value="RUC">RUC</option>
                                </select>
                                <input type="text" class="joined-input-field" placeholder="Nro. Documento" required>
                            </div>
                        </div>
                        <div class="form-group-item field-floating-box">
                            <label class="floating-field-label">Género</label>
                            <select class="form-control-select" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                                <option value="prefiero_no_decir">Prefiero no decir</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-2col">
                        <div class="form-group-item">
                            <div class="joined-phone-group">
                                <select class="phone-code-select" id="selectPhoneCode">
                                    <option value="+51" selected>+51</option>
                                    <option value="+56">+56</option>
                                    <option value="+57">+57</option>
                                    <option value="+54">+54</option>
                                    <option value="+52">+52</option>
                                    <option value="+593">+593</option>
                                    <option value="+591">+591</option>
                                    <option value="+34">+34</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="tel" class="joined-input-field" placeholder="Teléfono" required>
                            </div>
                        </div>
                        <div class="form-group-item flex-center-vert">
                            <span class="required-asterisk-text">* Campos obligatorios</span>
                        </div>
                    </div>

                    <div class="terms-checkbox-list">
                        <label class="checkbox-custom-item">
                            <input type="checkbox" class="orange-checkbox" required>
                            <span>He leído y acepto los <a href="#" class="orange-terms-link">términos y condiciones de uso</a> de Vive Go, así como sus <a href="#" class="orange-terms-link">políticas de privacidad</a>.*</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit-orange">
                        Continuar ➔
                    </button>
                </form>
            </div>

            <!-- Columna Derecha: Tarjeta Visual -->
            <div class="auth-modal-split-right">
                <div class="auth-showcase-poster">
                    <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=800&q=80" alt="Vive Go Eventos" class="auth-showcase-img">
                    <div class="auth-showcase-overlay">
                        <div class="auth-showcase-brand">
                            <img src="{{ asset('images/logo-white.png') }}" alt="Vive Go" class="auth-showcase-logo-img">
                        </div>

                        <div class="auth-showcase-content">
                            <span class="auth-showcase-badge">✨ TU ENTRADA VIP</span>
                            <h3>¡Vive la experiencia de los mejores eventos!</h3>
                            <p>Accede a funciones exclusivas, teatro, conciertos y experiencias únicas en todo el Perú.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMULARIO OCULTO PARA NAVEGACIÓN DIRECTA A LA PÁGINA DE CHECKOUT -->
    <form id="formGoToCheckout" action="{{ route('web.checkout') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event['id'] ?? '' }}">
        <input type="hidden" name="event_slug" value="{{ $event['slug'] ?? '' }}">
        <input type="hidden" name="date_selected" id="hiddenCheckoutDate" value="">
        <input type="hidden" name="tickets" id="hiddenCheckoutTickets" value="">
    </form>

    <!-- MODAL DE MAPA (UBICACIÓN Y RECINTO) -->
    <div class="auth-modal-overlay" id="mapModal">
        <div class="auth-modal-split-container" style="grid-template-columns: 1fr; max-width: 720px; padding: 2rem;">
            <button class="auth-modal-close" id="btnCloseMapModal" aria-label="Cerrar">&times;</button>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="info-block-header" style="margin-bottom: 0;">
                    <div class="info-block-icon">📍</div>
                    <h2 style="font-size: 1.25rem;">{{ $event['venue']['name'] }}</h2>
                </div>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.25rem;">{{ $event['venue']['address'] }}</p>
                <div style="border-radius: 20px; overflow: hidden; height: 380px; box-shadow: var(--shadow-md);">
                    <iframe src="{{ $event['venue']['map_embed'] }}" width="100%" height="100%"
                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 Oficial -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Variables globales del carrito
        let currentGrandTotal = 0;
        let selectedTicketsArray = [];
        let currentSelectedDate = '';

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Selector Dinámico de Fecha y Hora
            const dateCards = document.querySelectorAll('.date-select-card');
            if (dateCards.length > 0) {
                dateCards[0].classList.add('selected');
                currentSelectedDate = dateCards[0].querySelector('.date-select-day')?.textContent || '';
            }

            dateCards.forEach(card => {
                card.addEventListener('click', function () {
                    dateCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    currentSelectedDate = this.querySelector('.date-select-day')?.textContent || '';
                });
            });

            // 2. Contador Dinámico de Entradas por Fila y Recálculo del Total (Inicio en 0)
            const ticketRows = document.querySelectorAll('.ticket-type-row');
            const totalPriceDisplay = document.getElementById('totalPriceDisplay');

            function recalculateTotal() {
                currentGrandTotal = 0;
                selectedTicketsArray = [];

                ticketRows.forEach(row => {
                    const isAvailable = row.getAttribute('data-available') !== 'false';
                    if (!isAvailable) return;

                    const price = parseFloat(row.getAttribute('data-price')) || 0;
                    const regularPrice = parseFloat(row.getAttribute('data-regular-price')) || price;
                    const isPresale = row.getAttribute('data-is-presale') === 'true';
                    const presaleDiscount = parseFloat(row.getAttribute('data-presale-discount')) || 0;
                    const name = row.querySelector('.ticket-name')?.textContent.replace('🎟️', '').replace('🎁', '').trim() || 'Entrada';
                    const isCourtesy = row.getAttribute('data-is-courtesy') === 'true';
                    const countEl = row.querySelector('.ticket-count-val');
                    const qty = parseInt(countEl ? countEl.textContent : 0) || 0;

                    if (qty > 0) {
                        currentGrandTotal += price * qty;
                        const seatCodes = row.getAttribute('data-selected-seats');
                        selectedTicketsArray.push({
                            name: name,
                            price: price,
                            regular_price: regularPrice,
                            is_presale: isPresale,
                            presale_discount: presaleDiscount,
                            is_courtesy: isCourtesy,
                            quantity: qty,
                            subtotal: price * qty,
                            seats: seatCodes ? JSON.parse(seatCodes) : []
                        });
                    }
                });

                if (totalPriceDisplay) {
                    totalPriceDisplay.textContent = 'S/ ' + currentGrandTotal.toFixed(2);
                }

                // Sincronizar zonas resaltadas en el mapa interactivo
                const selectedNames = selectedTicketsArray.map(item => item.name.toLowerCase());
                document.querySelectorAll('.svg-public-zone').forEach(poly => {
                    const zName = (poly.getAttribute('data-zone-name') || '').toLowerCase();
                    const isSelected = selectedNames.some(sn => sn.includes(zName) || zName.includes(sn));
                    if (isSelected) {
                        poly.classList.add('selected');
                    } else {
                        poly.classList.remove('selected');
                    }
                });
                document.querySelectorAll('.public-zone-legend-pill').forEach(pill => {
                    const zName = (pill.getAttribute('data-zone-name') || '').toLowerCase();
                    const isSelected = selectedNames.some(sn => sn.includes(zName) || zName.includes(sn));
                    if (isSelected) {
                        pill.classList.add('active');
                    } else {
                        pill.classList.remove('active');
                    }
                });
            }

            window.recalculateTicketTotal = recalculateTotal;

            ticketRows.forEach(row => {
                const isAvailable = row.getAttribute('data-available') !== 'false';
                if (!isAvailable) return;

                const isCourtesy = row.getAttribute('data-is-courtesy') === 'true';
                const maxQty = parseInt(row.getAttribute('data-max-quantity')) || 99;

                const btnMinus = row.querySelector('.btn-ticket-minus');
                const btnPlus = row.querySelector('.btn-ticket-plus');
                const countEl = row.querySelector('.ticket-count-val');

                const rowZoneName = row.getAttribute('data-zone-name') || (row.querySelector('.ticket-name')?.textContent.replace('🎟️', '').replace('🎁', '').trim() || '');

                if (btnMinus && btnPlus && countEl) {
                    btnMinus.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (window.PublicSeatMap && typeof window.PublicSeatMap.hasSeatsInZone === 'function' && window.PublicSeatMap.hasSeatsInZone(rowZoneName)) {
                            window.PublicSeatMap.unselectLastSeat(rowZoneName);
                            return;
                        }

                        let currentQuantity = parseInt(countEl.textContent) || 0;
                        if (currentQuantity > 0) {
                            currentQuantity--;
                            countEl.textContent = currentQuantity;
                            recalculateTotal();
                        }
                    });

                    btnPlus.addEventListener('click', function (e) {
                        e.preventDefault();
                        let currentQuantity = parseInt(countEl.textContent) || 0;
                        if (currentQuantity >= maxQty) {
                            if (isCourtesy && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Límite de Cortesías',
                                    text: 'Solo se permite un máximo de 2 entradas de cortesía por usuario.',
                                    icon: 'info',
                                    confirmButtonColor: '#10B981',
                                    confirmButtonText: 'Entendido'
                                });
                            }
                            return;
                        }

                        if (window.PublicSeatMap && typeof window.PublicSeatMap.hasSeatsInZone === 'function' && window.PublicSeatMap.hasSeatsInZone(rowZoneName)) {
                            const selected = window.PublicSeatMap.selectNextAvailableSeat(rowZoneName);
                            if (!selected && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Butacas Agotadas',
                                    text: 'No quedan más butacas libres en esta zona.',
                                    icon: 'warning',
                                    confirmButtonColor: '#FF5500',
                                    confirmButtonText: 'Entendido'
                                });
                            }
                            return;
                        }

                        currentQuantity++;
                        countEl.textContent = currentQuantity;
                        recalculateTotal();
                    });
                }
            });

            // 3. Toggle de Campo de Código de Descuento
            const btnTogglePromo = document.getElementById('btnTogglePromoCode');
            const promoBox = document.getElementById('promoCodeInputBox');
            const btnApplyPromo = document.getElementById('btnApplyPromoCode');
            const inputPromo = document.getElementById('inputPromoCode');
            const promoMsg = document.getElementById('promoCodeMsg');

            if (btnTogglePromo && promoBox) {
                btnTogglePromo.addEventListener('click', function(e) {
                    e.preventDefault();
                    promoBox.style.display = (promoBox.style.display === 'none' || !promoBox.style.display) ? 'block' : 'none';
                });
            }

            if (btnApplyPromo && inputPromo && promoMsg) {
                btnApplyPromo.addEventListener('click', function(e) {
                    e.preventDefault();
                    const code = inputPromo.value.trim().toUpperCase();
                    if (code === 'VIVEGO' || code === 'PROMO20' || code === 'DESC20') {
                        promoMsg.style.color = '#10B981';
                        promoMsg.textContent = '✓ ¡Código aplicado correctamente! Descuento activo.';
                        promoMsg.style.display = 'block';
                    } else if (code.length > 0) {
                        promoMsg.style.color = '#FF1E3C';
                        promoMsg.textContent = '✕ Código no válido o expirado.';
                        promoMsg.style.display = 'block';
                    }
                });
            }

            // 4. Redirección Directa a la Página de Checkout (Compatible con todos los botones de compra)
            const formGoToCheckout = document.getElementById('formGoToCheckout');
            const buyButtons = document.querySelectorAll('#btnOpenAuthModal, .btn-checkout-sticky');

            if (buyButtons.length > 0 && formGoToCheckout) {
                buyButtons.forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (this.disabled) return;

                        recalculateTotal();

                        let totalSelectedQty = 0;
                        selectedTicketsArray.forEach(item => {
                            totalSelectedQty += (item.quantity || 0);
                        });

                        if (totalSelectedQty === 0 || selectedTicketsArray.length === 0) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Selecciona tus Entradas',
                                    text: 'Por favor, selecciona al menos una (1) entrada o pase de cortesía para continuar con la compra.',
                                    icon: 'warning',
                                    confirmButtonColor: '#FF5500',
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                alert('⚠️ Por favor, selecciona al menos una (1) entrada para continuar con la compra.');
                            }
                            return;
                        }

                        document.getElementById('hiddenCheckoutDate').value = currentSelectedDate || "Fecha Oficial";
                        document.getElementById('hiddenCheckoutTickets').value = JSON.stringify(selectedTicketsArray);
                        formGoToCheckout.submit();
                    });
                });
            }

            // 5. Modal de Mapa (Ubicación y Recinto)
            const mapModal = document.getElementById('mapModal');
            const btnOpenMapModal = document.getElementById('btnOpenMapModal');
            const btnCloseMapModal = document.getElementById('btnCloseMapModal');

            if (btnOpenMapModal && mapModal) {
                btnOpenMapModal.addEventListener('click', function (e) {
                    e.preventDefault();
                    mapModal.classList.add('active');
                });
            }

            if (btnCloseMapModal && mapModal) {
                btnCloseMapModal.addEventListener('click', function () {
                    mapModal.classList.remove('active');
                });
            }

            if (mapModal) {
                mapModal.addEventListener('click', function (e) {
                    if (e.target === mapModal) {
                        mapModal.classList.remove('active');
                    }
                });
            }

            // 6. Toggle Ver Más / Ver Menos para Detalles del Evento
            const btnToggleDetails = document.getElementById('btnToggleDetails');
            const extraParagraphs = document.querySelectorAll('.details-extra-paragraph');
            const toggleDetailsText = document.getElementById('toggleDetailsText');
            const toggleDetailsIcon = document.getElementById('toggleDetailsIcon');

            if (btnToggleDetails) {
                let isExpanded = false;
                btnToggleDetails.addEventListener('click', function (e) {
                    e.preventDefault();
                    isExpanded = !isExpanded;
                    extraParagraphs.forEach(p => {
                        p.style.display = isExpanded ? 'block' : 'none';
                    });
                    if (toggleDetailsText) toggleDetailsText.textContent = isExpanded ? 'Ver menos' : 'Ver más';
                    if (toggleDetailsIcon) toggleDetailsIcon.textContent = isExpanded ? '↑' : '➔';
                });
            }
        });
    </script>
@endpush