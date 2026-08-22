@extends('layouts.app')

@section('title', $event['title'] . ' | Vive Go Eventos')

@section('content')
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
        });
    </script>
@endpush