@extends('layouts.app')

@section('title', $event['title'] . ' | Vive Go Eventos')

@section('content')
    <style>
        @media (max-width: 991px) {
            .template2-hero-grid {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }
            .template2-hero-card {
                padding: 1.75rem 1.5rem !important;
            }
            .template2-hero-grid h1 {
                font-size: 2rem !important;
            }
            .template2-booking-grid {
                grid-template-columns: 1fr !important;
            }
            .template2-bottom-grid {
                grid-template-columns: 1fr !important;
            }
            .artist-hero-container {
                min-height: 280px !important;
            }
            #artistHeroImg {
                max-height: 320px !important;
            }
        }
        @media (max-width: 576px) {
            .template2-booking-card {
                padding: 1.5rem 1rem !important;
            }
        }
    </style>

    @if(($event['layout_template'] ?? 'template_1') === 'template_2')
        <!-- =========================================================
             PLANTILLA 2: INMERSIVA (ARTISTA & FONDO FIJO CON PARALLAX)
             ========================================================= -->
        <!-- Fondo Fijo de Pantalla Completa (Estático en scroll) -->
        <div class="template2-backdrop" style="position: fixed; inset: 0; background-image: url('{{ $event['background_image'] ?: $event['banner_image'] }}'); background-size: cover; background-position: center top; background-attachment: fixed; z-index: -2;"></div>
        <div class="template2-overlay" style="position: fixed; inset: 0; background: linear-gradient(180deg, rgba(8, 12, 22, 0.82) 0%, rgba(10, 14, 26, 0.94) 55%, rgba(10, 14, 26, 0.98) 100%); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: -1;"></div>

        <div class="event-detail-wrapper container" style="position: relative; z-index: 1; padding-top: 1.5rem; max-width: 1200px;">
            <!-- Breadcrumbs Navigation -->
            <nav class="detail-breadcrumbs" style="margin-bottom: 1.25rem;">
                <a href="{{ route('web.home') }}" style="color: var(--color-primary-orange);">Inicio</a> &nbsp; / &nbsp;
                <span style="color: #94A3B8;">{{ $event['category'] }}</span> &nbsp; / &nbsp;
                <span style="color: #FFFFFF; font-weight: 700;">{{ $event['title'] }}</span>
            </nav>

            <!-- 1. HERO INMERSIVO CON IMAGEN DEL ARTISTA (SCROLL DINÁMICO/PARALLAX) -->
            <section class="template2-hero-card" style="position: relative; border-radius: 28px; background: rgba(255, 255, 255, 0.04); border: 1.5px solid rgba(255, 255, 255, 0.12); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); overflow: hidden; margin-bottom: 2.5rem; padding: 2.75rem 3rem;">
                <div style="display: grid; grid-template-columns: 1fr 440px; gap: 2.5rem; align-items: center;" class="template2-hero-grid">
                    
                    <!-- Lado Izquierdo: Títulos, Fechas, Ubicación -->
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.65rem; align-items: center;">
                            <span class="dash-badge-custom badge-orange" style="font-size: 0.85rem; font-weight: 800; padding: 0.4rem 0.95rem; border-radius: 999px; letter-spacing: 0.5px;">
                                🔥 {{ $event['category'] }}
                            </span>
                            <span class="dash-badge-custom badge-blue" style="font-size: 0.85rem; font-weight: 800; padding: 0.4rem 0.95rem; border-radius: 999px;">
                                📍 {{ $event['city'] }}
                            </span>
                            <span style="background: rgba(255,255,255,0.1); color: #E2E8F0; font-size: 0.8rem; font-weight: 700; padding: 0.4rem 0.85rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.15);">
                                🛡️ {{ $event['advisory'] }}
                            </span>
                        </div>

                        <h1 style="font-size: 2.75rem; font-weight: 900; line-height: 1.15; color: #FFFFFF; text-shadow: 0 4px 25px rgba(0,0,0,0.7); margin: 0; letter-spacing: -0.5px;">
                            {{ $event['title'] }}
                        </h1>

                        <p style="font-size: 1.1rem; color: #CBD5E1; line-height: 1.5; margin: 0;">
                            {{ $event['subtitle'] }}
                        </p>

                        <!-- Píldoras de Fecha y Lugar -->
                        <div style="display: flex; flex-wrap: wrap; gap: 0.85rem; margin-top: 0.5rem;">
                            <div style="background: rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.15); padding: 0.75rem 1.15rem; border-radius: 16px; display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.4rem;">📅</span>
                                <div>
                                    <span style="display: block; font-size: 0.725rem; color: #94A3B8; text-transform: uppercase; font-weight: 800;">Fecha</span>
                                    <strong style="color: #FFFFFF; font-size: 0.925rem;">{{ $event['dates'][0]['date'] ?? 'Próximamente' }}</strong>
                                </div>
                            </div>

                            <div style="background: rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.15); padding: 0.75rem 1.15rem; border-radius: 16px; display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.4rem;">🕒</span>
                                <div>
                                    <span style="display: block; font-size: 0.725rem; color: #94A3B8; text-transform: uppercase; font-weight: 800;">Hora</span>
                                    <strong style="color: #FFFFFF; font-size: 0.925rem;">{{ $event['dates'][0]['time'] ?? '18:00' }}</strong>
                                </div>
                            </div>

                            <div style="background: rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.15); padding: 0.75rem 1.15rem; border-radius: 16px; display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.4rem;">📍</span>
                                <div>
                                    <span style="display: block; font-size: 0.725rem; color: #94A3B8; text-transform: uppercase; font-weight: 800;">Recinto</span>
                                    <strong style="color: #FFFFFF; font-size: 0.925rem;">{{ $event['venue']['name'] }}</strong>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 0.5rem;">
                            <a href="#sectionTicketsTemplate2" class="btn btn-primary btn-save-settings" style="display: inline-flex; align-items: center; gap: 0.65rem; padding: 0.95rem 2.2rem; font-size: 1.05rem; font-weight: 800; border-radius: 16px; box-shadow: 0 10px 25px rgba(255, 85, 0, 0.45); text-decoration: none;">
                                <span>🎟️ Comprar Entradas</span>
                                <span>➔</span>
                            </a>
                        </div>
                    </div>

                    <!-- Lado Derecho: Imagen del Artista con Efecto Scroll Parallax -->
                    <div style="position: relative; display: flex; align-items: center; justify-content: center; min-height: 380px;" class="artist-hero-container">
                        @php
                            $artistImgSrc = $event['artist_image'] ?: $event['banner_image'];
                        @endphp
                        <div id="artistHeroWrapper" style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transition: transform 0.12s ease-out; will-change: transform;">
                            <img id="artistHeroImg" src="{{ $artistImgSrc }}" alt="{{ $event['title'] }} - Artista" style="max-height: 430px; width: auto; max-width: 100%; object-fit: contain; filter: drop-shadow(0 20px 35px rgba(0,0,0,0.85)); border-radius: 20px;">
                        </div>
                    </div>

                </div>
            </section>

            <!-- 2. SECCIÓN DEBAJO DEL ARTISTA: IMAGEN REFERENCIAL DEL RECINTO / MAPA DE ZONAS -->
            @if(!empty($event['reference_image']))
                <section class="event-info-block animate-fade-in" style="margin-bottom: 2.5rem; background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.12); border-radius: 24px; padding: 2rem;">
                    <div class="info-block-header" style="margin-bottom: 1.25rem;">
                        <div class="info-block-icon" style="background: rgba(0, 240, 255, 0.15); color: var(--color-neon-cyan);">🗺️</div>
                        <h2 style="font-size: 1.4rem; color: #FFFFFF;">Mapa de Zonas y Distribución del Recinto</h2>
                    </div>

                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
                        <div style="position: relative; display: inline-block; max-width: 100%; border-radius: 18px; overflow: hidden; box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5); border: 1.5px solid rgba(255, 255, 255, 0.15); background: #000000;">
                            <img src="{{ $event['reference_image'] }}" alt="Plano de Zonas - {{ $event['title'] }}" style="max-height: 440px; width: auto; max-width: 100%; object-fit: contain; display: block; margin: 0 auto; border-radius: 18px;">
                            
                            <a href="{{ $event['reference_image'] }}" target="_blank" style="position: absolute; bottom: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); color: #FFFFFF; font-size: 0.8rem; font-weight: 800; padding: 6px 14px; border-radius: 10px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(8px); display: inline-flex; align-items: center; gap: 0.4rem;" title="Abrir imagen en alta definición">
                                <span>🔍</span> Ver plano en pantalla completa
                            </a>
                        </div>
                    </div>
                </section>
            @endif

            <!-- 3. SECCIÓN DEBAJO DEL RECINTO: PRECIOS, ZONAS Y SELECCIÓN DE ENTRADAS -->
            <section id="sectionTicketsTemplate2" class="template2-booking-card" style="background: rgba(15, 23, 42, 0.88); border: 1.5px solid rgba(255, 85, 0, 0.4); border-radius: 28px; padding: 2.25rem 2.5rem; margin-bottom: 2.5rem; box-shadow: 0 20px 50px rgba(0,0,0,0.6); backdrop-filter: blur(16px);">
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="background: rgba(255, 85, 0, 0.15); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                            🎟️
                        </div>
                        <div>
                            <h2 style="font-size: 1.5rem; font-weight: 800; color: #FFFFFF; margin: 0;">Selección de Entradas & Tarifas</h2>
                            <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #94A3B8;">Elige tu fecha y la cantidad de boletos que deseas adquirir</p>
                        </div>
                    </div>

                    <span class="dash-badge-custom badge-green" style="font-size: 0.8rem; font-weight: 800;">
                        🔒 Compra 100% Segura
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: 340px 1fr; gap: 2.25rem;" class="template2-booking-grid">
                    
                    <!-- Columna Izquierda: Fecha, Cupón y Total -->
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <h3 class="sidebar-section-title" style="color: #FFFFFF; font-size: 1.1rem; margin: 0;">1. Selecciona Fecha y Hora</h3>
                        
                        <div class="dates-selector-group" style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($event['dates'] as $dateItem)
                                <div class="date-select-card" data-date-id="{{ $dateItem['id'] }}" style="background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 1rem 1.25rem;">
                                    <div class="date-select-info">
                                        <span class="date-select-day" style="font-size: 0.95rem; font-weight: 800; color: #FFFFFF;">📅 {{ $dateItem['date'] }}</span>
                                        <span class="date-select-time" style="font-size: 0.85rem; color: #94A3B8;">🕒 {{ $dateItem['time'] }}</span>
                                    </div>
                                    <div class="date-select-checkmark-orange">✓</div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Resumen y Cupón Promocional -->
                        <div style="background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.1); border-radius: 18px; padding: 1.25rem; margin-top: auto;">
                            <div style="margin-bottom: 1rem;">
                                <a href="javascript:void(0)" class="promo-code-link-orange" id="btnTogglePromoCode" style="font-size: 0.85rem; font-weight: 800;">¿Tienes un código de descuento?</a>
                                <div class="promo-code-input-box" id="promoCodeInputBox" style="display: none; margin-top: 0.65rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <input type="text" id="inputPromoCode" placeholder="Código..." style="flex: 1; padding: 0.5rem 0.75rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.5); color: #FFFFFF; font-size: 0.825rem; font-weight: 700;">
                                        <button type="button" id="btnApplyPromoCode" class="btn btn-primary btn-sm" style="padding: 0.5rem 0.85rem; border-radius: 10px; font-size: 0.8rem;">Aplicar</button>
                                    </div>
                                    <div id="promoCodeMsg" style="font-size: 0.775rem; margin-top: 0.35rem; font-weight: 700; display: none;"></div>
                                </div>
                            </div>

                            <div class="total-summary-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <span class="total-label" style="font-size: 1rem; color: #CBD5E1; font-weight: 700;">Total a Pagar:</span>
                                <span class="total-price-value" id="totalPriceDisplay" style="font-size: 1.6rem; font-weight: 900; color: var(--color-primary-orange);">S/ 0.00</span>
                            </div>

                            <button class="btn btn-primary btn-checkout-sticky" id="btnOpenAuthModal" style="width: 100%; padding: 1rem; font-size: 1.05rem; font-weight: 800; border-radius: 14px; box-shadow: 0 8px 25px rgba(255, 85, 0, 0.4);">
                                <span>Comprar Entradas ➔</span>
                            </button>
                        </div>
                    </div>

                    <!-- Columna Derecha: Lista de Entradas y Zonas con Contador -->
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <h3 class="sidebar-section-title" style="color: #FFFFFF; font-size: 1.1rem; margin: 0;">2. Selecciona las Zonas</h3>
                        
                        <div class="tickets-list-box" style="display: flex; flex-direction: column; gap: 0.85rem;">
                            @foreach($event['tickets'] as $ticket)
                                <div class="ticket-type-row" data-price="{{ $ticket['price'] }}" style="background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 18px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s ease;">
                                    <div class="ticket-type-info" style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                                            <span class="ticket-name" style="font-size: 1.1rem; font-weight: 800; color: #FFFFFF;">🎟️ {{ $ticket['name'] }}</span>
                                            <span class="dash-badge-custom badge-green" style="font-size: 0.725rem;">Disponible</span>
                                        </div>
                                        <span class="ticket-price" style="font-size: 1.25rem; font-weight: 900; color: var(--color-primary-orange);">S/ {{ number_format($ticket['price'], 2) }}</span>
                                    </div>
                                    <div class="ticket-quantity-counter" style="display: flex; align-items: center; gap: 0.85rem; background: rgba(0,0,0,0.5); padding: 0.35rem 0.65rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.15);">
                                        <button type="button" class="counter-btn minus btn-ticket-minus" style="width: 32px; height: 32px; border-radius: 8px; border: none; background: rgba(255,255,255,0.1); color: #FFFFFF; font-size: 1.1rem; font-weight: 800; cursor: pointer;">-</button>
                                        <span class="counter-value ticket-count-val" style="font-size: 1.1rem; font-weight: 800; color: #FFFFFF; min-width: 24px; text-align: center;">0</span>
                                        <button type="button" class="counter-btn plus btn-ticket-plus" style="width: 32px; height: 32px; border-radius: 8px; border: none; background: var(--color-primary-orange); color: #FFFFFF; font-size: 1.1rem; font-weight: 800; cursor: pointer;">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </section>

            <!-- 4. SECCIÓN DE DETALLES, UBICACIÓN, ORGANIZADOR Y TAGS -->
            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; margin-bottom: 3rem;" class="template2-bottom-grid">
                
                <!-- Columna Izquierda: Descripción y Detalles -->
                <div style="background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 2rem;">
                    <div class="info-block-header" style="margin-bottom: 1.25rem;">
                        <div class="info-block-icon" style="background: rgba(255, 85, 0, 0.15); color: var(--color-primary-orange);">📋</div>
                        <h2 style="font-size: 1.35rem; color: #FFFFFF;">Detalles del Espectáculo</h2>
                    </div>

                    <div class="details-content-box" style="color: #CBD5E1; line-height: 1.7; font-size: 0.95rem;">
                        @foreach($event['details'] as $index => $paragraph)
                            <p class="details-paragraph {{ $index >= 2 ? 'details-extra-paragraph' : '' }}" style="{{ $index >= 2 ? 'display: none;' : '' }}; margin-bottom: 1rem;">
                                {{ $paragraph }}
                            </p>
                        @endforeach

                        @if(count($event['details']) > 2)
                            <button type="button" id="btnToggleDetails" class="btn-toggle-details-orange" style="margin-top: 0.5rem;">
                                <span id="toggleDetailsText">Ver más información</span>
                                <span id="toggleDetailsIcon">➔</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Columna Derecha: Recinto GPS, Organiza y Tags -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    
                    <!-- Ubicación Recinto -->
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 1.75rem;">
                        <div class="info-block-header" style="margin-bottom: 0.85rem;">
                            <div class="info-block-icon">📍</div>
                            <h2 style="font-size: 1.2rem; color: #FFFFFF;">Ubicación del Recinto</h2>
                        </div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #FFFFFF; margin: 0 0 0.25rem 0;">{{ $event['venue']['name'] }}</h3>
                        <p style="font-size: 0.85rem; color: #94A3B8; margin: 0 0 1rem 0;">{{ $event['venue']['address'] }}</p>
                        
                        <button type="button" class="btn btn-secondary btn-sm btn-map-modal-trigger" id="btnOpenMapModal" style="width: 100%; justify-content: center; padding: 0.65rem 1rem; border-radius: 12px;">
                            <span>📍 Ver ubicación en Google Maps</span>
                        </button>
                    </div>

                    <!-- Organiza -->
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 1.75rem;">
                        <div class="info-block-header" style="margin-bottom: 0.85rem;">
                            <div class="info-block-icon">🏢</div>
                            <h2 style="font-size: 1.2rem; color: #FFFFFF;">Organizador</h2>
                        </div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #FFFFFF; margin: 0 0 0.25rem 0;">{{ $event['organizer']['name'] }}</h3>
                        <p style="font-size: 0.85rem; color: #94A3B8; margin: 0;">RUC: {{ $event['organizer']['ruc'] }}</p>
                    </div>

                    <!-- Tags -->
                    @if(!empty($event['tags']))
                        <div style="background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 1.75rem;">
                            <div class="info-block-header" style="margin-bottom: 0.85rem;">
                                <div class="info-block-icon">🏷️</div>
                                <h2 style="font-size: 1.2rem; color: #FFFFFF;">Etiquetas</h2>
                            </div>
                            <div class="tags-cloud-container">
                                @foreach($event['tags'] as $tag)
                                    <span class="tag-pill-item">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

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

                    @if(!empty($event['reference_image']))
                        <!-- Sección: Imagen de Referencia / Plano de Zonas y Distribución -->
                        <section class="event-info-block animate-fade-in detail-order-8" style="margin-bottom: 2rem;">
                            <div class="info-block-header">
                                <div class="info-block-icon">🗺️</div>
                                <h2>Mapa de Zonas y Referencia</h2>
                            </div>

                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; margin-top: 0.5rem;">
                                <div style="position: relative; display: inline-block; max-width: 100%; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <img src="{{ $event['reference_image'] }}" alt="Mapa de Zonas y Referencia - {{ $event['title'] }}" style="max-height: 340px; width: auto; max-width: 100%; object-fit: contain; display: block; margin: 0 auto; border-radius: 14px;">
                                    
                                    <a href="{{ $event['reference_image'] }}" target="_blank" style="position: absolute; bottom: 8px; right: 8px; background: rgba(15, 23, 42, 0.8); color: #FFFFFF; font-size: 0.725rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px); display: inline-flex; align-items: center; gap: 0.3rem;" title="Abrir imagen en tamaño completo">
                                        <span>🔍</span> Ver completo
                                    </a>
                                </div>
                            </div>
                        </section>

                        <hr class="event-section-divider detail-order-8">
                    @endif

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

                            <div class="tickets-list-box">
                                @foreach($event['tickets'] as $ticket)
                                    <div class="ticket-type-row" data-price="{{ $ticket['price'] }}">
                                        <div class="ticket-type-info">
                                            <span class="ticket-name">🎟️ {{ $ticket['name'] }}</span>
                                            <span class="ticket-price">S/ {{ $ticket['price'] }}</span>
                                        </div>
                                        <div class="ticket-quantity-counter">
                                            <button type="button" class="counter-btn minus btn-ticket-minus">-</button>
                                            <span class="counter-value ticket-count-val">0</span>
                                            <button type="button" class="counter-btn plus btn-ticket-plus">+</button>
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

                            <button class="btn btn-primary btn-checkout-sticky" id="btnOpenAuthModal">
                                <span>Comprar Entradas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
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
                    const price = parseFloat(row.getAttribute('data-price')) || 0;
                    const name = row.querySelector('.ticket-name')?.textContent.replace('🎟️', '').trim() || 'Entrada';
                    const countEl = row.querySelector('.ticket-count-val');
                    const qty = parseInt(countEl ? countEl.textContent : 0) || 0;

                    if (qty > 0) {
                        currentGrandTotal += price * qty;
                        selectedTicketsArray.push({
                            name: name,
                            price: price,
                            quantity: qty,
                            subtotal: price * qty
                        });
                    }
                });

                if (totalPriceDisplay) {
                    totalPriceDisplay.textContent = 'S/ ' + currentGrandTotal.toFixed(2);
                }
            }

            ticketRows.forEach(row => {
                const btnMinus = row.querySelector('.btn-ticket-minus');
                const btnPlus = row.querySelector('.btn-ticket-plus');
                const countEl = row.querySelector('.ticket-count-val');

                if (btnMinus && btnPlus && countEl) {
                    btnMinus.addEventListener('click', function (e) {
                        e.preventDefault();
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

            // 4. Redirección Directa a la Página de Checkout
            const btnOpenAuthModal = document.getElementById('btnOpenAuthModal');
            const formGoToCheckout = document.getElementById('formGoToCheckout');

            if (btnOpenAuthModal && formGoToCheckout) {
                btnOpenAuthModal.addEventListener('click', function (e) {
                    e.preventDefault();

                    if (currentGrandTotal <= 0 || selectedTicketsArray.length === 0) {
                        alert('⚠️ Por favor, selecciona al menos una (1) entrada para continuar con la compra.');
                        return;
                    }

                    document.getElementById('hiddenCheckoutDate').value = currentSelectedDate || "Fecha Oficial";
                    document.getElementById('hiddenCheckoutTickets').value = JSON.stringify(selectedTicketsArray);
                    formGoToCheckout.submit();
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

            // 7. Efecto Parallax / Scroll Dinámico para Imagen de Artista en Plantilla 2
            const artistHero = document.getElementById('artistHeroWrapper');
            if (artistHero) {
                let ticking = false;
                window.addEventListener('scroll', function () {
                    if (!ticking) {
                        window.requestAnimationFrame(function () {
                            const scrollY = window.scrollY;
                            // Desplaza suavemente hacia abajo la imagen del artista con el scroll
                            const translateVal = Math.min(scrollY * 0.18, 140);
                            artistHero.style.transform = `translateY(${translateVal}px)`;
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            }
        });
    </script>
@endpush