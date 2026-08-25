@extends('layouts.app')

@section('title', 'Finalizar Compra | Checkout Seguro ViveGo')

@section('content')
<div class="checkout-light-page-wrapper">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem 4rem 1.25rem;">
        
        <!-- BARRA SUPERIOR DE NAVEGACIÓN Y TÍTULO -->
        <div class="checkout-header-bar" style="margin-bottom: 2rem;">
            <a href="{{ route('web.home') }}" class="btn-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Volver a la Cartelera</span>
            </a>

            <div class="checkout-title-row">
                <div>
                    <span class="secure-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        CHECKOUT 100% SEGURO & ENCRIPTADO
                    </span>
                    <h1 class="checkout-main-title">Finalizar Compra de Entradas</h1>
                    <p class="checkout-main-subtitle">Completa tus datos y selecciona tu método de pago preferido para emitir tus boletos oficiales.</p>
                </div>

                <!-- Badges de Confianza -->
                <div class="trust-badges-group">
                    @if($izipay->is_active)
                        <div class="izipay-verified-pill">
                            <span class="izipay-logo-txt">izi<span>pay</span></span>
                            <span class="verified-dot">● Oficial</span>
                        </div>
                    @endif

                    @if($culqi->is_active)
                        <div class="culqi-verified-pill">
                            <span class="culqi-logo-txt">culqi</span>
                            <span class="verified-dot">● QR & Yape</span>
                        </div>
                    @endif

                    <div class="ssl-pill">
                        🛡️ PCI-DSS Nivel 1
                    </div>
                </div>
            </div>
        </div>

        <!-- GRID PRINCIPAL DE 2 COLUMNAS -->
        <div class="checkout-grid-layout">
            
            <!-- COLUMNA IZQUIERDA: CARRITO, DATOS Y PASARELA DE PAGO -->
            <div class="checkout-steps-column">
                
                <!-- RESUMEN COMPACTO DEL EVENTO (EXCLUSIVO PARA MÓVIL) -->
                <div class="mobile-event-mini-header" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.85rem 1rem; margin-bottom: 1rem; align-items: center; gap: 0.85rem; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
                    <img src="{{ $eventData['banner_image'] }}" alt="{{ $eventData['title'] }}" style="width: 64px; height: 64px; border-radius: 12px; object-fit: cover; flex-shrink: 0; border: 1px solid #E2E8F0;">
                    <div style="flex: 1; min-width: 0;">
                        <span style="font-size: 0.7rem; font-weight: 900; color: var(--color-primary-orange); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">
                            {{ $eventData['category'] }}
                        </span>
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin: 0 0 3px 0; line-height: 1.25; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $eventData['title'] }}
                        </h3>
                        <span style="font-size: 0.775rem; color: #64748B; font-weight: 700; display: flex; align-items: center; gap: 0.3rem;">
                            <span>📅</span> {{ $eventData['date_selected'] }}
                        </span>
                    </div>
                </div>

                <!-- PASO 1: CARRITO DE ENTRADAS -->
                <div class="checkout-white-card">
                    <div class="card-step-header">
                        <div class="step-num-badge">1</div>
                        <div>
                            <h2 class="card-step-title">Tus Entradas Seleccionadas</h2>
                            <p class="card-step-subtitle">Revisa o ajusta la cantidad de entradas para este evento.</p>
                        </div>
                    </div>

                    <div class="cart-items-list">
                        @foreach($cartItems as $index => $item)
                            @php
                                $isPresale = !empty($item['is_presale']) || (!empty($item['presale_discount']) && (float)$item['presale_discount'] > 0);
                                $regPrice = !empty($item['regular_price']) ? (float)$item['regular_price'] : (float)$item['price'];
                                $curPrice = (float)$item['price'];
                                $discountVal = $item['presale_discount'] ?? 0;
                            @endphp
                            <div class="cart-row-item" data-price="{{ $item['price'] }}" style="{{ $isPresale ? 'border: 1.5px solid rgba(255, 85, 0, 0.35); background: #FFFBF8;' : '' }}">
                                <div class="cart-row-main">
                                    <span class="cart-item-emoji">🎟️</span>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                            <h4 class="cart-item-title" style="margin: 0;">{{ $item['name'] }}</h4>
                                            @if($isPresale)
                                                <span style="background: linear-gradient(135deg, #FF5500, #FF1E3C); color: #FFFFFF; font-size: 0.675rem; font-weight: 900; padding: 2px 7px; border-radius: 6px; box-shadow: 0 2px 5px rgba(255,85,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                                                    🔥 PREVENTA {{ $discountVal > 0 ? '-' . $discountVal . '%' : '' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                            <span class="cart-item-unit-cost" style="font-size: 0.85rem; color: #475569;">
                                                Precio: <strong style="color: var(--color-primary-orange); font-size: 0.95rem;">S/ {{ number_format($curPrice, 2) }}</strong> c/u
                                            </span>
                                            @if($isPresale && $regPrice > $curPrice)
                                                <span style="font-size: 0.825rem; color: #94A3B8; text-decoration: line-through; font-weight: 600;">
                                                    S/ {{ number_format($regPrice, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-row-controls">
                                    <div class="light-qty-counter">
                                        <button type="button" class="btn-lgt-qty" onclick="updateItemQuantity({{ $index }}, -1)">−</button>
                                        <span class="lgt-qty-num" id="qty-val-{{ $index }}">{{ $item['quantity'] }}</span>
                                        <button type="button" class="btn-lgt-qty" onclick="updateItemQuantity({{ $index }}, 1)">+</button>
                                    </div>
                                    <span class="cart-row-subtotal-price" id="subtotal-val-{{ $index }}">
                                        S/ {{ number_format(($curPrice * $item['quantity']), 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total en Paso 1 -->
                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1.5px dashed #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1rem; font-weight: 800; color: #475569;">Total a Pagar:</span>
                        <strong id="cartSummaryTotalStep1" style="font-size: 1.55rem; font-weight: 900; color: #059669;">S/ {{ number_format($grandTotal, 2) }}</strong>
                    </div>
                </div>

                <!-- PASO 2: DATOS DEL COMPRADOR / ASISTENTE -->
                <div class="checkout-white-card" style="margin-top: 1.5rem;">
                    <div class="card-step-header" style="justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 0.85rem;">
                            <div class="step-num-badge">2</div>
                            <div>
                                <h2 class="card-step-title">Datos del Comprador</h2>
                                <p class="card-step-subtitle">Tus entradas con código QR oficial serán enviadas a este correo.</p>
                            </div>
                        </div>
                        @if(!session('customer_logged_in'))
                            <button type="button" onclick="openFastLoginModal()" style="background: #F1F5F9; border: 1.5px solid #CBD5E1; color: #0F172A; font-weight: 800; font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <span>🔑</span>
                                <span>¿Ya tienes cuenta? Iniciar Sesión</span>
                            </button>
                        @endif
                    </div>

                    @if(session('customer_logged_in'))
                        <div style="background: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: 12px; padding: 0.75rem 1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 0.875rem; color: #065F46; font-weight: 700;">
                                👤 Comprando como: <strong>{{ session('customer_name') }}</strong> ({{ session('customer_email') }})
                            </div>
                            <a href="{{ route('web.customer.tickets') }}" target="_blank" style="color: #059669; font-size: 0.8rem; font-weight: 800; text-decoration: underline;">
                                Mis Boletos ➔
                            </a>
                        </div>
                    @endif

                    <form id="checkoutCustomerForm" class="customer-fields-grid" onsubmit="event.preventDefault(); proceedSelectedPayment();">
                        <div class="form-fields-2col">
                            <div class="form-field-box">
                                <label class="field-dark-label">Nombres y Apellidos Completos <span class="req">*</span></label>
                                <input type="text" id="buyerFullName" class="input-dark-text" placeholder="Ej: Carlos Ramírez Morales" value="{{ session('customer_name') ?? '' }}" required>
                            </div>
                            <div class="form-field-box">
                                <label class="field-dark-label">DNI / Pasaporte / CE <span class="req">*</span></label>
                                <input type="text" id="buyerDoc" class="input-dark-text" placeholder="Ej: 72345678" value="{{ session('customer_dni') ?? '' }}" required>
                            </div>
                        </div>

                        <div class="form-fields-2col" style="margin-top: 1rem;">
                            <div class="form-field-box">
                                <label class="field-dark-label">Correo Electrónico (Para recibir QR) <span class="req">*</span></label>
                                <input type="email" id="buyerEmail" class="input-dark-text" placeholder="tu.correo@ejemplo.com" value="{{ session('customer_email') ?? '' }}" required>
                            </div>
                            <div class="form-field-box">
                                <label class="field-dark-label">Teléfono Celular / WhatsApp <span class="req">*</span></label>
                                <input type="tel" id="buyerPhone" class="input-dark-text" placeholder="Ej: 987654321" value="{{ session('customer_phone') ?? '' }}" required>
                            </div>
                        </div>

                        <div class="form-fields-2col" style="margin-top: 1rem;">
                            <div class="form-field-box">
                                <label class="field-dark-label">País de Residencia <span class="req">*</span></label>
                                <select id="buyerCountry" class="input-dark-text" style="background-color: #FFFFFF; font-weight: 700; cursor: pointer;" required>
                                    <!-- Países cargados dinámicamente -->
                                </select>
                            </div>
                            <div class="form-field-box">
                                <label class="field-dark-label">Ciudad / Departamento <span class="req">*</span></label>
                                <select id="buyerCity" class="input-dark-text" style="background-color: #FFFFFF; font-weight: 700; cursor: pointer;" required>
                                    <!-- Ciudades cargadas dinámicamente -->
                                </select>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem; background: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: 12px; padding: 0.9rem 1.15rem; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.25rem;">🎟️</span>
                            <span style="font-size: 0.85rem; color: #065F46; font-weight: 700; line-height: 1.4;">
                                Tu <strong>Entrada Oficial en PDF</strong> y las <strong>credenciales temporales</strong> de acceso para consultar tus boletos en línea llegarán automáticamente a este correo al completar el pago.
                            </span>
                        </div>
                    </form>
                </div>

                <!-- PASO 3: PASARELAS DE PAGO DISPONIBLES (IZIPAY / CULQI) O CONFIRMACIÓN DE CORTESÍA -->
                @php
                    $izipayActive = (bool) ($izipay->is_active ?? false);
                    $culqiActive = (bool) ($culqi->is_active ?? false);
                    $defaultGateway = ($culqiActive && !$izipayActive) ? 'culqi' : 'izipay';
                    $isCourtesyCart = ($grandTotal <= 0.00) || (!empty($cartItems) && collect($cartItems)->every(fn($item) => !empty($item['is_courtesy']) || (float)$item['price'] == 0));
                @endphp

                <!-- TARJETA DE CONFIRMACIÓN PARA CORTESÍAS (GRATIS / FREE) -->
                <div class="checkout-white-card" id="courtesyStepCard" style="margin-top: 1.5rem; display: {{ $isCourtesyCart ? 'block' : 'none' }}; border: 2px solid #10B981;">
                    <div class="card-step-header">
                        <div class="step-num-badge" style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF;">🎁</div>
                        <div>
                            <h2 class="card-step-title" style="color: #065F46;">Entradas de Cortesía Gratuitas</h2>
                            <p class="card-step-subtitle" style="color: #047857;">Tus entradas seleccionadas son 100% libres de costo. No se requiere ningún método de pago.</p>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(5, 150, 105, 0.04)); border: 2px dashed #10B981; border-radius: 16px; padding: 1.75rem 1.25rem; text-align: center; margin-top: 1rem;">
                        <div style="font-size: 2.75rem; margin-bottom: 0.5rem;">🎉</div>
                        <h3 style="font-size: 1.25rem; font-weight: 900; color: #065F46; margin: 0 0 0.4rem 0;">¡Pase de Cortesía Confirmado!</h3>
                        <p style="font-size: 0.875rem; color: #047857; margin: 0 auto 1.5rem auto; max-width: 480px; line-height: 1.45;">
                            Tus entradas oficiales con código QR se emitirán automáticamente sin costo alguno. Por favor verifica que tus datos del <strong>Paso 2</strong> sean correctos.
                        </p>

                        <!-- Error Alert Box Cortesía -->
                        <div id="courtesyFormError" style="display: none; background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 0.85rem 1.15rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.25rem; text-align: left;"></div>

                        <button type="button" class="btn-pay-orange" id="btnConfirmCourtesy" onclick="completeCourtesyCheckout()" style="background: linear-gradient(135deg, #10B981, #059669); box-shadow: 0 6px 20px rgba(16,185,129,0.35); font-size: 1.05rem; padding: 1rem 2rem; width: 100%; max-width: 460px; margin: 0 auto; justify-content: center;">
                            <span>🎁 Confirmar Entradas de Cortesía Gratis</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- TARJETA REGULAR DE PASARELAS (IZIPAY / CULQI) -->
                <div class="checkout-white-card" id="regularPaymentStepCard" style="margin-top: 1.5rem; display: {{ $isCourtesyCart ? 'none' : 'block' }};">
                    <div class="card-step-header">
                        <div class="step-num-badge">3</div>
                        <div>
                            <h2 class="card-step-title">Método de Pago Seguro</h2>
                            <p class="card-step-subtitle">Selecciona tu pasarela preferida para pagar con Tarjetas, QR Yape / Plin o PagoEfectivo.</p>
                        </div>
                    </div>

                    <!-- SELECTOR DE PESTAÑAS DE PASARELA EN EL CHECKOUT -->
                    <div class="gateway-checkout-tabs">
                        @if($izipayActive || (!$izipayActive && !$culqiActive))
                            <button type="button" class="btn-checkout-gateway-tab {{ $defaultGateway === 'izipay' ? 'active' : '' }}" onclick="selectCheckoutGateway('izipay', this)">
                                <span class="gtw-tab-ico">💳</span>
                                <div>
                                    <strong class="gtw-tab-title">Izipay Perú</strong>
                                    <span class="gtw-tab-sub">Tarjetas, QR & Efectivo</span>
                                </div>
                            </button>
                        @endif

                        @if($culqiActive || (!$izipayActive && !$culqiActive))
                            <button type="button" class="btn-checkout-gateway-tab {{ $defaultGateway === 'culqi' ? 'active' : '' }}" onclick="selectCheckoutGateway('culqi', this)">
                                <span class="gtw-tab-ico">🟧</span>
                                <div>
                                    <strong class="gtw-tab-title">Culqi Perú</strong>
                                    <span class="gtw-tab-sub">Pago con QR, Yape & Tarjetas</span>
                                </div>
                                <span class="gtw-tab-tag">📱 QR Yape/Plin</span>
                            </button>
                        @endif
                    </div>

                    <!-- Error Alert Box -->
                    <div id="checkoutFormError" style="display: none; background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 0.85rem 1.15rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.25rem;"></div>

                    <!-- CONTENIDO DE PASARELA: IZIPAY -->
                    <div id="checkout-gateway-izipay" class="checkout-gateway-panel {{ $defaultGateway === 'izipay' ? 'active' : '' }}">
                        <div class="payment-tabs-preview">
                            <div class="pay-method-pill active">
                                <span class="method-icon">💳</span> Tarjetas Crédito/Débito
                            </div>
                            <div class="pay-method-pill">
                                <span class="method-icon">📱</span> Yape QR & Plin
                            </div>
                            <div class="pay-method-pill">
                                <span class="method-icon">💵</span> PagoEfectivo
                            </div>
                        </div>

                        <!-- Botón Inicial de Carga de Izipay -->
                        <div id="initPaymentSection" style="text-align: center; padding: 1.5rem 0 0.5rem 0;">
                            <button type="button" class="btn-pay-orange" id="btnInitIzipay" onclick="loadIzipayGateway()">
                                <span>💳 Pagar <span id="btnPayAmountDisplay">S/ {{ number_format($grandTotal, 2) }}</span> con Izipay</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                            <p style="font-size: 0.825rem; color: #64748B; margin-top: 0.85rem;">
                                🔒 Tus datos viajan cifrados bajo los estándares oficiales de seguridad de Izipay Perú.
                            </p>
                        </div>

                        <!-- Loader Izipay -->
                        <div id="izipayLoaderBox" style="display: none; text-align: center; padding: 2.5rem 1rem;">
                            <div class="preloader-ring" style="width: 44px; height: 44px; margin: 0 auto 0.85rem auto; border-width: 3.5px; border-color: #FF5500; border-top-color: transparent;"></div>
                            <p style="font-size: 0.95rem; color: #1E293B; font-weight: 700; margin: 0;">Conectando con la pasarela segura de Izipay...</p>
                            <span style="font-size: 0.8rem; color: #64748B;">Generando sesión cifrada de pago</span>
                        </div>

                        <!-- Contenedor Oficial Krypton Form -->
                        <div id="izipayEmbeddedContainer" style="display: none; margin-top: 1.5rem; min-height: 280px;">
                            <div class="kr-embedded"></div>
                        </div>
                    </div>

                    <!-- CONTENIDO DE PASARELA: CULQI -->
                    <div id="checkout-gateway-culqi" class="checkout-gateway-panel {{ $defaultGateway === 'culqi' ? 'active' : '' }}">
                        <div class="payment-tabs-preview">
                            <div class="pay-method-pill active" style="background: #FFF7ED; border-color: #FF5500; color: #EA580C;">
                                <span class="method-icon">📱</span> Pago con QR (Yape / Plin)
                            </div>
                            <div class="pay-method-pill">
                                <span class="method-icon">💳</span> Tarjetas Débito / Crédito
                            </div>
                            <div class="pay-method-pill">
                                <span class="method-icon">⚡</span> Yape Directo
                            </div>
                            <div class="pay-method-pill">
                                <span class="method-icon">💵</span> PagoEfectivo
                            </div>
                        </div>

                        <!-- Botón Inicial de Carga de Culqi -->
                        <div id="initCulqiPaymentSection" style="text-align: center; padding: 1.5rem 0 0.5rem 0;">
                            <button type="button" class="btn-pay-orange" id="btnInitCulqi" onclick="loadCulqiGateway()" style="background: linear-gradient(135deg, #FF5500, #E64A00);">
                                <span>🟧 Pagar <span id="btnPayAmountDisplayCulqi">S/ {{ number_format($grandTotal, 2) }}</span> con Culqi</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                            <p style="font-size: 0.825rem; color: #64748B; margin-top: 0.85rem;">
                                🔒 Pagos instantáneos con <strong>Código QR, Yape, Plin y Tarjetas</strong> respaldados por Culqi Perú.
                            </p>
                        </div>

                        <!-- Loader Culqi -->
                        <div id="culqiLoaderBox" style="display: none; text-align: center; padding: 2.5rem 1rem;">
                            <div class="preloader-ring" style="width: 44px; height: 44px; margin: 0 auto 0.85rem auto; border-width: 3.5px; border-color: #FF5500; border-top-color: transparent;"></div>
                            <p style="font-size: 0.95rem; color: #1E293B; font-weight: 700; margin: 0;">Iniciando pasarela de pagos Culqi...</p>
                            <span style="font-size: 0.8rem; color: #64748B;">Abriendo formulario seguro y código QR</span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- COLUMNA DERECHA: RESUMEN DEL PEDIDO (STICKY LIGHT CARD) -->
            <div class="checkout-summary-column">
                <div class="order-summary-light-card">
                    
                    <!-- Imagen del Evento -->
                    <div class="summary-img-header">
                        <img src="{{ $eventData['banner_image'] }}" alt="{{ $eventData['title'] }}" class="summary-event-img">
                        <div class="summary-category-pill">🔥 {{ $eventData['category'] }}</div>
                    </div>

                    <div class="summary-content-body">
                        <h3 class="summary-event-headline">{{ $eventData['title'] }}</h3>
                        
                        <div class="summary-event-info-list">
                            <div class="info-row">
                                <span class="info-icon">📅</span>
                                <div>
                                    <strong class="info-label">Fecha y Hora:</strong>
                                    <span class="info-val">{{ $eventData['date_selected'] }}</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <span class="info-icon">📍</span>
                                <div>
                                    <strong class="info-label">Lugar:</strong>
                                    <span class="info-val">{{ $eventData['venue']['name'] }} ({{ $eventData['venue']['address'] }})</span>
                                </div>
                            </div>
                        </div>

                        <div class="light-divider"></div>

                        <!-- Desglose Financiero -->
                        <div class="pricing-summary-box">
                            <div class="price-line">
                                <span class="line-label">Subtotal Entradas:</span>
                                <strong class="line-amount" id="summarySubtotalDisplay">S/ {{ number_format($grandTotal, 2) }}</strong>
                            </div>
                            <div class="price-line">
                                <span class="line-label">Comisión por servicio:</span>
                                <span class="free-badge">Gratis (S/ 0.00)</span>
                            </div>
                            
                            <div class="total-big-line">
                                <span class="total-big-label">TOTAL A PAGAR:</span>
                                <span class="total-big-val" id="summaryTotalDisplay">S/ {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>

                        <!-- Garantías de Confianza -->
                        <div class="trust-guarantees-light">
                            <div class="trust-point">
                                <span class="trust-ico">⚡</span>
                                <span><strong>Emisión Digital Instantánea:</strong> Códigos QR listos al instante.</span>
                            </div>
                            <div class="trust-point">
                                <span class="trust-ico">🛡️</span>
                                <span><strong>Entrada 100% Oficial:</strong> Garantía y respaldo ViveGo.</span>
                            </div>
                            <div class="trust-point">
                                <span class="trust-ico">📱</span>
                                <span><strong>Soporte WhatsApp:</strong> Asistencia directa en tu evento.</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ==========================================================================
       CHECKOUT PROFESIONAL EN TEMA CLARO / BLANCO (ESTRUCTURA PREMIUM)
       ========================================================================== */
    
    .checkout-light-page-wrapper {
        background-color: #F8FAFC !important;
        min-height: 100vh;
        color: #1E293B !important;
        font-family: inherit;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #475569 !important;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 1.25rem;
        transition: color 0.2s;
    }

    .btn-back-link:hover {
        color: #FF5500 !important;
    }

    .checkout-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 1.25rem;
        border-bottom: 2px solid #E2E8F0;
        padding-bottom: 1.5rem;
    }

    .secure-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #FEF3C7;
        color: #92400E;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0.25rem 0.65rem;
        border-radius: 8px;
        letter-spacing: 0.5px;
    }

    .checkout-main-title {
        font-size: 2.1rem !important;
        font-weight: 900 !important;
        color: #0F172A !important;
        margin: 0.5rem 0 0.25rem 0 !important;
        letter-spacing: -0.5px;
    }

    .checkout-main-subtitle {
        font-size: 0.95rem !important;
        color: #64748B !important;
        margin: 0 !important;
    }

    .trust-badges-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .izipay-verified-pill {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        padding: 0.4rem 0.9rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .izipay-logo-txt {
        font-size: 1.3rem;
        font-weight: 900;
        color: #FF0055;
        letter-spacing: -1px;
    }

    .izipay-logo-txt span {
        color: #00D2C4;
    }

    .culqi-verified-pill {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        padding: 0.4rem 0.9rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .culqi-logo-txt {
        font-size: 1.2rem;
        font-weight: 900;
        color: #FF5500;
        letter-spacing: -0.5px;
    }

    .verified-dot {
        font-size: 0.75rem;
        font-weight: 800;
        color: #059669;
    }

    .ssl-pill {
        background: #ECFDF5;
        border: 1.5px solid #A7F3D0;
        color: #065F46;
        font-size: 0.775rem;
        font-weight: 800;
        padding: 0.45rem 0.85rem;
        border-radius: 12px;
    }

    /* TABS SELECTORAS DE PASARELA EN CHECKOUT */
    .gateway-checkout-tabs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .btn-checkout-gateway-tab {
        background: #F8FAFC;
        border: 2px solid #E2E8F0;
        border-radius: 14px;
        padding: 0.85rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        position: relative;
    }

    .btn-checkout-gateway-tab:hover {
        border-color: #FF5500;
        background: #FFF7ED;
    }

    .btn-checkout-gateway-tab.active {
        background: #FFFFFF;
        border-color: #FF5500;
        box-shadow: 0 4px 15px rgba(255, 85, 0, 0.15);
    }

    .gtw-tab-ico {
        font-size: 1.6rem;
    }

    .gtw-tab-title {
        font-size: 0.95rem;
        color: #0F172A;
        display: block;
    }

    .gtw-tab-sub {
        font-size: 0.75rem;
        color: #64748B;
        display: block;
    }

    .gtw-tab-tag {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #ECFDF5;
        color: #059669;
        font-size: 0.675rem;
        font-weight: 800;
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
        border: 1px solid #A7F3D0;
    }

    .checkout-gateway-panel {
        display: none;
    }

    .checkout-gateway-panel.active {
        display: block;
        animation: fadeInGtw 0.2s ease-in-out;
    }

    @keyframes fadeInGtw {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* GRID LAYOUT */
    .checkout-grid-layout {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 2rem;
        align-items: start;
        margin-top: 2rem;
    }

    /* TARJETAS BLANCAS */
    .checkout-white-card {
        background: #FFFFFF !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 20px !important;
        padding: 2rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
    }

    .card-step-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1.5px solid #F1F5F9;
    }

    .step-num-badge {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #FF5500, #FF0055);
        color: #FFFFFF;
        font-weight: 900;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3);
    }

    .card-step-title {
        font-size: 1.3rem !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        margin: 0 !important;
    }

    .card-step-subtitle {
        font-size: 0.85rem !important;
        color: #64748B !important;
        margin: 0.2rem 0 0 0 !important;
    }

    /* CARRITO DE ENTRADAS */
    .cart-items-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .cart-row-item {
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.15rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cart-row-main {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .cart-item-emoji {
        font-size: 1.6rem;
    }

    .cart-item-title {
        font-size: 1.05rem !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        margin: 0 0 0.15rem 0 !important;
    }

    .cart-item-unit-cost {
        font-size: 0.85rem;
        color: #64748B;
    }

    .cart-row-controls {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .light-qty-counter {
        display: inline-flex;
        align-items: center;
        background: #FFFFFF;
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        padding: 0.25rem 0.5rem;
        gap: 0.75rem;
    }

    .btn-lgt-qty {
        background: transparent;
        border: none;
        color: #FF5500;
        font-weight: 900;
        font-size: 1.2rem;
        cursor: pointer;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .btn-lgt-qty:hover {
        background: #FFF7ED;
    }

    .lgt-qty-num {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0F172A;
        min-width: 18px;
        text-align: center;
    }

    .cart-row-subtotal-price {
        font-size: 1.2rem;
        font-weight: 900;
        color: #059669;
        min-width: 95px;
        text-align: right;
    }

    /* CAMPOS DEL COMPRADOR */
    .form-fields-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .field-dark-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 0.4rem;
    }

    .field-dark-label .req {
        color: #FF5500;
    }

    .input-dark-text {
        width: 100%;
        background: #FFFFFF !important;
        border: 1.5px solid #CBD5E1 !important;
        border-radius: 12px !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.95rem !important;
        color: #0F172A !important;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-dark-text:focus {
        border-color: #FF5500 !important;
        box-shadow: 0 0 0 3px rgba(255, 85, 0, 0.15) !important;
    }

    /* TABS DE MÉTODOS DE PAGO */
    .payment-tabs-preview {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .pay-method-pill {
        background: #F1F5F9;
        border: 1.5px solid #E2E8F0;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pay-method-pill.active {
        background: #FFF7ED;
        border-color: #FF5500;
        color: #EA580C;
    }

    .btn-pay-orange {
        background: linear-gradient(135deg, #FF5500, #E64A00) !important;
        color: #FFFFFF !important;
        border: none !important;
        padding: 1.05rem 2.5rem !important;
        font-size: 1.15rem !important;
        font-weight: 900 !important;
        border-radius: 14px !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 8px 24px rgba(255, 85, 0, 0.35) !important;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .btn-pay-orange:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(255, 85, 0, 0.45) !important;
    }

    /* COLUMNA DERECHA: RESUMEN DEL PEDIDO */
    .order-summary-light-card {
        background: #FFFFFF !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 20px !important;
        overflow: hidden;
        position: sticky;
        top: 100px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
    }

    .summary-img-header {
        position: relative;
        height: 180px;
        width: 100%;
        overflow: hidden;
    }

    .summary-event-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .summary-category-pill {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.85);
        color: #FFFFFF;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }

    .summary-content-body {
        padding: 1.75rem;
    }

    .summary-event-headline {
        font-size: 1.35rem !important;
        font-weight: 900 !important;
        color: #0F172A !important;
        margin: 0 0 1rem 0 !important;
        line-height: 1.3;
    }

    .summary-event-info-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.875rem;
    }

    .info-icon {
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .info-label {
        color: #475569;
        display: block;
    }

    .info-val {
        color: #0F172A;
        font-weight: 700;
    }

    .light-divider {
        height: 1.5px;
        background: #F1F5F9;
        margin: 1.25rem 0;
    }

    .pricing-summary-box {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .price-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
        color: #475569;
    }

    .line-amount {
        color: #0F172A;
        font-size: 1rem;
    }

    .free-badge {
        color: #059669;
        font-weight: 800;
        background: #ECFDF5;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .total-big-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.75rem;
        padding-top: 0.85rem;
        border-top: 2px dashed #E2E8F0;
    }

    .total-big-label {
        font-size: 1.05rem;
        font-weight: 900;
        color: #0F172A;
    }

    .total-big-val {
        font-size: 1.8rem;
        font-weight: 900;
        color: #059669;
    }

    .trust-guarantees-light {
        margin-top: 1.5rem;
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        font-size: 0.825rem;
        color: #475569;
    }

    .trust-point {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        line-height: 1.4;
    }

    .trust-point strong {
        color: #0F172A;
    }

    .mobile-event-mini-header {
        display: none;
    }

    @media (max-width: 950px) {
        .mobile-event-mini-header {
            display: flex !important;
        }
        .checkout-grid-layout {
            grid-template-columns: 1fr;
        }
        .checkout-summary-column {
            display: none !important;
        }
        .order-summary-light-card {
            display: none !important;
        }
        .form-fields-2col {
            grid-template-columns: 1fr;
        }
        .gateway-checkout-tabs {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
    @php
        $izipayPublicKey = $izipay->getCredential('public_key') ?: env('IZIPAY_PUBLIC_KEY', '');
        $izipayClientEndpoint = $izipay->getCredential('client_endpoint', 'https://api.micuentaweb.pe');
        $culqiPublicKey = $culqi->getCredential('public_key') ?: env('CULQI_PUBLIC_KEY', '');
    @endphp

    <!-- Librería Oficial de Izipay (Krypton Form) -->
    <script 
        src="{{ rtrim($izipayClientEndpoint, '/') }}/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
        kr-public-key="{{ $izipayPublicKey }}"
        kr-language="es">
    </script>
    <link rel="stylesheet" href="{{ rtrim($izipayClientEndpoint, '/') }}/static/js/krypton-client/V4.0/ext/classic-reset.css">
    <script src="{{ rtrim($izipayClientEndpoint, '/') }}/static/js/krypton-client/V4.0/ext/classic.js"></script>

    <!-- Librería Oficial de Culqi Checkout v4 -->
    <script src="https://checkout.culqi.com/js/v4"></script>

    <script>
        let cartItems = @json($cartItems);
        let eventData = @json($eventData);
        let currentGrandTotal = {{ $grandTotal }};
        let currentSelectedGateway = '{{ $defaultGateway }}';
        let isIzipayInitialized = false;
        let isCulqiInitialized = false;
        let currentCulqiOrderId = null;
        let culqiPollingInterval = null;

        // Cambiar Pasarela Seleccionada en Checkout
        function selectCheckoutGateway(gatewayKey, btn) {
            currentSelectedGateway = gatewayKey;
            document.querySelectorAll('.btn-checkout-gateway-tab').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.checkout-gateway-panel').forEach(p => p.classList.remove('active'));

            if (btn) btn.classList.add('active');
            const panel = document.getElementById('checkout-gateway-' + gatewayKey);
            if (panel) panel.classList.add('active');
        }

        function proceedSelectedPayment() {
            if (currentSelectedGateway === 'culqi') {
                loadCulqiGateway();
            } else {
                loadIzipayGateway();
            }
        }

        // Actualizar cantidades en el carrito
        function updateItemQuantity(index, delta) {
            if (isIzipayInitialized || isCulqiInitialized) {
                alert('⚠️ Ya generaste una sesión de pago. Si deseas modificar tus entradas, por favor recarga la página.');
                return;
            }

            if (!cartItems[index]) return;

            let newQty = (cartItems[index].quantity || 1) + delta;
            if (newQty < 1) newQty = 1;

            // Restricción de máximo 2 para entradas de cortesía en web
            if (cartItems[index].is_courtesy && newQty > 2) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Límite de Cortesías',
                        text: 'Solo se permite un máximo de 2 entradas de cortesía por usuario.',
                        icon: 'info',
                        confirmButtonColor: '#10B981'
                    });
                }
                return;
            }

            cartItems[index].quantity = newQty;
            cartItems[index].subtotal = cartItems[index].price * newQty;

            // Actualizar DOM
            document.getElementById('qty-val-' + index).textContent = newQty;
            document.getElementById('subtotal-val-' + index).textContent = 'S/ ' + cartItems[index].subtotal.toFixed(2);

            // Recalcular Total General
            currentGrandTotal = 0;
            cartItems.forEach(item => {
                currentGrandTotal += item.subtotal;
            });

            if (document.getElementById('cartSummaryTotalStep1')) {
                document.getElementById('cartSummaryTotalStep1').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('btnPayAmountDisplay')) {
                document.getElementById('btnPayAmountDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('btnPayAmountDisplayCulqi')) {
                document.getElementById('btnPayAmountDisplayCulqi').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('summarySubtotalDisplay')) {
                document.getElementById('summarySubtotalDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('summaryTotalDisplay')) {
                document.getElementById('summaryTotalDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }

            // Alternar vista de cortesía o pasarela regular
            const isCourtesy = currentGrandTotal <= 0 || cartItems.every(i => i.is_courtesy || i.price === 0);
            const courtesyCard = document.getElementById('courtesyStepCard');
            const regularCard = document.getElementById('regularPaymentStepCard');
            if (courtesyCard && regularCard) {
                courtesyCard.style.display = isCourtesy ? 'block' : 'none';
                regularCard.style.display = isCourtesy ? 'none' : 'block';
            }
        }

        // =========================================================================
        // 0. CONFIRMACIÓN Y EMISIÓN DE ENTRADAS DE CORTESÍA GRATIS
        // =========================================================================
        async function completeCourtesyCheckout() {
            const errBox = document.getElementById('courtesyFormError');
            const btn = document.getElementById('btnConfirmCourtesy');

            if (errBox) errBox.style.display = 'none';

            const name = document.getElementById('buyerFullName').value.trim();
            const doc = document.getElementById('buyerDoc').value.trim();
            const email = document.getElementById('buyerEmail').value.trim();
            const phone = document.getElementById('buyerPhone').value.trim();
            const country = document.getElementById('buyerCountry')?.options[document.getElementById('buyerCountry').selectedIndex]?.text || 'Perú 🇵🇪';
            const city = document.getElementById('buyerCity')?.value || 'Lima';

            if (!name || !doc || !email || !phone || !city) {
                if (errBox) {
                    errBox.textContent = '⚠️ Por favor completa todos los campos del Paso 2 (Datos del Comprador: Nombre, Documento, Correo, Teléfono, País y Ciudad).';
                    errBox.style.display = 'block';
                }
                document.getElementById('buyerFullName').focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<span>⏳ Generando y compilando tus boletos...</span>`;

            let ticketPdfBase64 = '';
            try {
                if (typeof window.generateTicketPdfDoc === 'function') {
                    const simulatedSale = {
                        receipt_number: 'REC-CORTESIA',
                        buyer_name: name,
                        buyer_dni: doc,
                        buyer_email: email,
                        event: {
                            id: eventData.id,
                            title: eventData.title,
                            venue_name: eventData.venue?.name || '',
                            address: eventData.venue?.address || eventData.city || '',
                            event_date: eventData.date_selected || '',
                            event_time: '20:00',
                            banner_image: eventData.banner_image || '',
                            template: eventData.template || null
                        },
                        tickets_data: { items: cartItems }
                    };
                    const { pdf } = await window.generateTicketPdfDoc(simulatedSale);
                    if (pdf) ticketPdfBase64 = pdf.output('datauristring');
                }
            } catch (pdfErr) {
                console.warn('Compilación de PDF de cortesía omitida:', pdfErr);
            }

            try {
                const response = await fetch("{{ route('web.checkout.courtesy_complete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        event_id: eventData.id || 1,
                        customer_name: name,
                        customer_email: email,
                        customer_phone: phone,
                        customer_doc_number: doc,
                        customer_country: country,
                        customer_city: city,
                        tickets: cartItems,
                        ticket_pdf_base64: ticketPdfBase64
                    })
                });

                const data = await response.json();

                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    btn.disabled = false;
                    btn.innerHTML = `<span>🎁 Confirmar Entradas de Cortesía Gratis</span>`;
                    if (errBox) {
                        errBox.textContent = '⚠️ ' + (data.message || 'No se pudieron emitir las entradas de cortesía.');
                        errBox.style.display = 'block';
                    }
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = `<span>🎁 Confirmar Entradas de Cortesía Gratis</span>`;
                if (errBox) {
                    errBox.textContent = '⚠️ Ocurrió un error al procesar tu solicitud. Intenta nuevamente.';
                    errBox.style.display = 'block';
                }
            }
        }

        // =========================================================================
        // 1. INTEGRACIÓN IZIPAY PERÚ
        // =========================================================================
        function loadIzipayGateway() {
            const name = document.getElementById('buyerFullName').value.trim();
            const doc = document.getElementById('buyerDoc').value.trim();
            const email = document.getElementById('buyerEmail').value.trim();
            const phone = document.getElementById('buyerPhone').value.trim();
            const country = document.getElementById('buyerCountry')?.value || 'PE';
            const city = document.getElementById('buyerCity')?.value || 'Lima';
            const errorBox = document.getElementById('checkoutFormError');

            if (!name || !doc || !email || !phone || !country || !city) {
                errorBox.textContent = '⚠️ Por favor completa todos los campos del Paso 2 (Datos del Comprador: Nombre, Documento, Correo, Teléfono, País y Ciudad) antes de proceder al pago.';
                errorBox.style.display = 'block';
                document.getElementById('buyerFullName').focus();
                return;
            }

            errorBox.style.display = 'none';
            document.getElementById('initPaymentSection').style.display = 'none';
            document.getElementById('izipayLoaderBox').style.display = 'block';

            // Llamada al backend para generar formToken
            fetch("{{ route('web.checkout.izipay_initiate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: currentGrandTotal,
                    event_id: eventData.id,
                    event_title: eventData.title,
                    date_selected: eventData.date_selected,
                    tickets: cartItems,
                    customer_name: name,
                    customer_email: email,
                    customer_phone: phone,
                    customer_doc: doc,
                    customer_country: country,
                    customer_city: city
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.formToken) {
                    isIzipayInitialized = true;

                    if (typeof KR !== 'undefined') {
                        KR.setFormToken(data.formToken).then(function() {
                            document.getElementById('izipayLoaderBox').style.display = 'none';
                            document.getElementById('izipayEmbeddedContainer').style.display = 'block';
                            KR.onSubmit(handleIzipaySubmit);
                        }).catch(function(err) {
                            console.error('Error al renderizar Izipay:', err);
                            document.getElementById('izipayLoaderBox').innerHTML = '<p style="color:#DC2626; font-weight:700;">Error al inicializar la pasarela de Izipay. Intenta de nuevo.</p>';
                        });
                    }
                } else {
                    document.getElementById('initPaymentSection').style.display = 'block';
                    document.getElementById('izipayLoaderBox').style.display = 'none';
                    errorBox.textContent = '⚠️ ' + (data.message || 'No se pudo iniciar la pasarela de pago.');
                    errorBox.style.display = 'block';
                }
            })
            .catch(err => {
                document.getElementById('initPaymentSection').style.display = 'block';
                document.getElementById('izipayLoaderBox').style.display = 'none';
                errorBox.textContent = '⚠️ Error de comunicación con el servidor: ' + err.message;
                errorBox.style.display = 'block';
            });
        }

        async function handleIzipaySubmit(response) {
            console.log('[Izipay] Callback de pago recibido:', response);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '🎉 ¡Pago Confirmado!',
                    html: 'Izipay ha procesado tu transacción con éxito.<br>Compilando tus entradas oficiales con código QR y creando tu perfil...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); },
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }

            let rawAnswer = '';
            if (typeof response.rawClientAnswer === 'string') {
                rawAnswer = response.rawClientAnswer;
            } else if (typeof response['kr-answer'] === 'string') {
                rawAnswer = response['kr-answer'];
            } else if (typeof response.rawAnswer === 'string') {
                rawAnswer = response.rawAnswer;
            } else if (typeof response.clientAnswer === 'object') {
                rawAnswer = JSON.stringify(response.clientAnswer);
            }

            let hash = response.hash || response['kr-hash'] || response.hashKey || '';

            let ticketPdfBase64 = '';
            try {
                if (typeof window.generateTicketPdfDoc === 'function') {
                    const simulatedSale = {
                        receipt_number: 'REC-PENDING',
                        buyer_name: document.getElementById('buyerFullName')?.value || 'Cliente ViveGo',
                        buyer_dni: document.getElementById('buyerDoc')?.value || '00000000',
                        buyer_email: document.getElementById('buyerEmail')?.value || '',
                        event: {
                            id: eventData.id,
                            title: eventData.title,
                            venue_name: eventData.venue?.name || '',
                            address: eventData.venue?.address || eventData.city || '',
                            event_date: eventData.date_selected || '',
                            event_time: '20:00',
                            banner_image: eventData.banner_image || '',
                            template: eventData.template || null
                        },
                        tickets_data: { items: cartItems }
                    };
                    const { pdf } = await window.generateTicketPdfDoc(simulatedSale);
                    if (pdf) ticketPdfBase64 = pdf.output('datauristring');
                }
            } catch (pdfErr) {
                console.warn('Compilación de PDF omitida:', pdfErr);
            }

            fetch("{{ route('web.checkout.izipay_complete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    'kr-answer': rawAnswer,
                    'kr-hash': hash,
                    'clientAnswer': response.clientAnswer,
                    'event_id': eventData.id,
                    'tickets': cartItems,
                    'customer_email': document.getElementById('buyerEmail')?.value || '',
                    'customer_name': document.getElementById('buyerFullName')?.value || '',
                    'customer_doc': document.getElementById('buyerDoc')?.value || '',
                    'customer_phone': document.getElementById('buyerPhone')?.value || '',
                    'ticket_pdf_base64': ticketPdfBase64
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Aviso de Pago',
                            text: data.message || 'Error al validar el pago.',
                            icon: 'warning',
                            confirmButtonColor: '#FF5500'
                        });
                    } else {
                        alert('⚠️ ' + (data.message || 'Error al validar el pago.'));
                    }
                }
            })
            .catch(err => {
                console.error('Error completando pago:', err);
                window.location.href = "{{ route('customer.my_tickets') }}";
            });

            return false;
        }

        // =========================================================================
        // 2. INTEGRACIÓN CULQI PERÚ (QR, YAPE, TARJETAS & PAGOEFECTIVO)
        // =========================================================================
        function loadCulqiGateway() {
            const name = document.getElementById('buyerFullName').value.trim();
            const doc = document.getElementById('buyerDoc').value.trim();
            const email = document.getElementById('buyerEmail').value.trim();
            const phone = document.getElementById('buyerPhone').value.trim();
            const country = document.getElementById('buyerCountry')?.value || 'PE';
            const city = document.getElementById('buyerCity')?.value || 'Lima';
            const errorBox = document.getElementById('checkoutFormError');

            if (!name || !doc || !email || !phone || !country || !city) {
                errorBox.textContent = '⚠️ Por favor completa todos los campos del Paso 2 (Datos del Comprador: Nombre, Documento, Correo, Teléfono, País y Ciudad) antes de proceder al pago con Culqi.';
                errorBox.style.display = 'block';
                document.getElementById('buyerFullName').focus();
                return;
            }

            errorBox.style.display = 'none';
            document.getElementById('initCulqiPaymentSection').style.display = 'none';
            document.getElementById('culqiLoaderBox').style.display = 'block';

            // Llamada al backend para generar Orden en Culqi
            fetch("{{ route('web.checkout.culqi_initiate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: currentGrandTotal,
                    event_id: eventData.id,
                    event_title: eventData.title,
                    date_selected: eventData.date_selected,
                    tickets: cartItems,
                    customer_name: name,
                    customer_email: email,
                    customer_phone: phone,
                    customer_doc: doc,
                    customer_country: country,
                    customer_city: city
                })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('initCulqiPaymentSection').style.display = 'block';
                document.getElementById('culqiLoaderBox').style.display = 'none';

                if (data.success && typeof Culqi !== 'undefined') {
                    isCulqiInitialized = true;
                    currentCulqiOrderId = data.orderId || null;

                    Culqi.publicKey = data.publicKey || '{{ $culqiPublicKey }}';

                    const culqiSettings = {
                        title: 'ViveGo - ' + (eventData.title || 'Entradas'),
                        currency: 'PEN',
                        amount: data.amountCents || Math.round(currentGrandTotal * 100),
                    };

                    if (data.orderId) {
                        culqiSettings.order = data.orderId;
                    }

                    Culqi.settings(culqiSettings);

                    Culqi.options({
                        lang: 'es',
                        installments: false,
                        paymentMethods: {
                            tarjeta: true,
                            yape: true,
                            billetera: true, // QR Billeteras (Yape, Plin)
                            pagoefectivo: true,
                            cuotealo: false
                        },
                        style: {
                            logo: '{{ asset("images/logo-icon.png") }}',
                            maincolor: '#FF5500',
                            buttontext: '#ffffff',
                            maintext: '#0F172A',
                            desctext: '#64748B'
                        }
                    });

                    // Abrir Checkout v4 de Culqi
                    Culqi.open();
                } else {
                    errorBox.textContent = '⚠️ ' + (data.warning || data.message || 'No se pudo abrir el checkout de Culqi.');
                    errorBox.style.display = 'block';
                }
            })
            .catch(err => {
                document.getElementById('initCulqiPaymentSection').style.display = 'block';
                document.getElementById('culqiLoaderBox').style.display = 'none';
                errorBox.textContent = '⚠️ Error de comunicación: ' + err.message;
                errorBox.style.display = 'block';
            });
        }

        // Manejador Global de Callback de Culqi Checkout v4
        window.culqi = async function () {
            console.log('[Culqi] Evento recibido:', { token: Culqi.token, order: Culqi.order, close: Culqi.close });

            if (Culqi.token) {
                // CASO A: Pago con Tarjeta completado con Token
                let tokenId = Culqi.token.id;
                Culqi.close();
                await processCulqiComplete({ token_id: tokenId });
            } else if (Culqi.order) {
                // CASO B: Pago con QR / Billeteras Móviles / PagoEfectivo generado
                let orderId = Culqi.order.id || currentCulqiOrderId;
                let orderState = Culqi.order.state || 'pending';

                if (orderState === 'paid') {
                    Culqi.close();
                    await processCulqiComplete({ order_id: orderId });
                } else {
                    // Iniciar polling en vivo para verificar cuando el cliente escanee y pague el QR
                    startCulqiOrderPolling(orderId);
                }
            } else if (Culqi.close) {
                console.log('[Culqi] Modal de pago cerrado por el usuario.');
            } else if (Culqi.error) {
                console.error('[Culqi] Error:', Culqi.error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Aviso Culqi',
                        text: Culqi.error.user_message || Culqi.error.merchant_message || 'Hubo un inconveniente al procesar tu solicitud.',
                        icon: 'warning',
                        confirmButtonColor: '#FF5500'
                    });
                } else {
                    alert('⚠️ ' + (Culqi.error.user_message || 'Error en pasarela Culqi'));
                }
            }
        };

        // Polling en tiempo real para verificar pagos QR
        function startCulqiOrderPolling(orderId) {
            if (!orderId) return;

            if (culqiPollingInterval) clearInterval(culqiPollingInterval);

            culqiPollingInterval = setInterval(() => {
                fetch("{{ route('web.checkout.culqi_order_status') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_id: orderId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.is_paid) {
                        clearInterval(culqiPollingInterval);
                        if (typeof Culqi !== 'undefined' && Culqi.close) Culqi.close();
                        processCulqiComplete({ order_id: orderId });
                    }
                })
                .catch(err => console.warn('[Culqi] Polling status:', err));
            }, 3000);
        }

        // Finalizar y registrar la compra con Culqi
        async function processCulqiComplete(payload) {
            if (culqiPollingInterval) clearInterval(culqiPollingInterval);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '🎉 ¡Pago Aprobado con Culqi!',
                    html: 'Tu transacción ha sido validada con éxito.<br>Generando tus entradas oficiales con código QR y creando tu cuenta...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); },
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }

            let ticketPdfBase64 = '';
            try {
                if (typeof window.generateTicketPdfDoc === 'function') {
                    const simulatedSale = {
                        receipt_number: 'REC-PENDING',
                        buyer_name: document.getElementById('buyerFullName')?.value || 'Cliente ViveGo',
                        buyer_dni: document.getElementById('buyerDoc')?.value || '00000000',
                        buyer_email: document.getElementById('buyerEmail')?.value || '',
                        event: {
                            id: eventData.id,
                            title: eventData.title,
                            venue_name: eventData.venue?.name || '',
                            address: eventData.venue?.address || eventData.city || '',
                            event_date: eventData.date_selected || '',
                            event_time: '20:00',
                            banner_image: eventData.banner_image || '',
                            template: eventData.template || null
                        },
                        tickets_data: { items: cartItems }
                    };
                    const { pdf } = await window.generateTicketPdfDoc(simulatedSale);
                    if (pdf) ticketPdfBase64 = pdf.output('datauristring');
                }
            } catch (pdfErr) {
                console.warn('Compilación client-side de PDF omitida:', pdfErr);
            }

            fetch("{{ route('web.checkout.culqi_complete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token_id: payload.token_id || null,
                    order_id: payload.order_id || currentCulqiOrderId,
                    amount: currentGrandTotal,
                    event_id: eventData.id,
                    tickets: cartItems,
                    customer_email: document.getElementById('buyerEmail')?.value || '',
                    customer_name: document.getElementById('buyerFullName')?.value || '',
                    customer_doc: document.getElementById('buyerDoc')?.value || '',
                    customer_phone: document.getElementById('buyerPhone')?.value || '',
                    ticket_pdf_base64: ticketPdfBase64
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Aviso de Pago',
                            text: data.message || 'Error al validar el pago en Culqi.',
                            icon: 'warning',
                            confirmButtonColor: '#FF5500'
                        });
                    } else {
                        alert('⚠️ ' + (data.message || 'Error al validar el pago en Culqi.'));
                    }
                }
            })
            .catch(err => {
                console.error('Error completando pago Culqi:', err);
                window.location.href = "{{ route('customer.my_tickets') }}";
            });
        }

        // =========================================================================
        // 3. SELECTOR DINÁMICO DE PAÍSES Y CIUDADES (25 REGIONES DE PERÚ + MUNDO)
        // =========================================================================
        const worldCountriesData = [
            {
                code: 'PE',
                name: 'Perú 🇵🇪',
                cities: [
                    'Lima (Lima Metropolitana)',
                    'Amazonas (Chachapoyas / Bagua)',
                    'Áncash (Huaraz / Chimbote)',
                    'Apurímac (Abancay / Andahuaylas)',
                    'Arequipa',
                    'Ayacucho (Huamanga / Huanta)',
                    'Cajamarca (Jaén / Chota)',
                    'Callao',
                    'Cusco (Urubamba / Quillabamba)',
                    'Huancavelica',
                    'Huánuco (Tingo María)',
                    'Ica (Chincha / Pisco / Nazca)',
                    'Junín (Huancayo / Tarma / Jauja / Satipo)',
                    'La Libertad (Trujillo / Chepén)',
                    'Lambayeque (Chiclayo / Ferreñafe)',
                    'Loreto (Iquitos / Yurimaguas)',
                    'Madre de Dios (Puerto Maldonado)',
                    'Moquegua (Ilo)',
                    'Pasco (Cerro de Pasco / Oxapampa)',
                    'Piura (Sullana / Talara / Paita)',
                    'Puno (Juliaca / Puno)',
                    'San Martín (Tarapoto / Moyobamba)',
                    'Tacna',
                    'Tumbes (Zorritos)',
                    'Ucayali (Pucallpa)'
                ]
            },
            {
                code: 'AR',
                name: 'Argentina 🇦🇷',
                cities: ['Buenos Aires', 'Córdoba', 'Rosario', 'Mendoza', 'La Plata', 'San Miguel de Tucumán', 'Salta', 'Santa Fe', 'San Juan', 'Mar del Plata', 'Neuquén', 'Bariloche']
            },
            {
                code: 'BO',
                name: 'Bolivia 🇧🇴',
                cities: ['La Paz', 'Santa Cruz de la Sierra', 'Cochabamba', 'Sucre', 'Oruro', 'Tarija', 'Potosí', 'Trinidad', 'Cobija', 'El Alto']
            },
            {
                code: 'BR',
                name: 'Brasil 🇧🇷',
                cities: ['São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Fortaleza', 'Belo Horizonte', 'Manaus', 'Curitiba', 'Recife', 'Porto Alegre']
            },
            {
                code: 'CL',
                name: 'Chile 🇨🇱',
                cities: ['Santiago', 'Valparaíso', 'Concepción', 'Viña del Mar', 'Antofagasta', 'Temuco', 'La Serena', 'Iquique', 'Puerto Montt', 'Rancagua', 'Arica', 'Talca']
            },
            {
                code: 'CO',
                name: 'Colombia 🇨🇴',
                cities: ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Cúcuta', 'Bucaramanga', 'Pereira', 'Santa Marta', 'Ibagué', 'Manizales', 'Pasto', 'Villavicencio']
            },
            {
                code: 'CR',
                name: 'Costa Rica 🇨🇷',
                cities: ['San José', 'Alajuela', 'Cartago', 'Heredia', 'Puntarenas', 'Liberia', 'Limón', 'Pérez Zeledón']
            },
            {
                code: 'EC',
                name: 'Ecuador 🇪🇨',
                cities: ['Quito', 'Guayaquil', 'Cuenca', 'Santo Domingo', 'Machala', 'Durán', 'Manta', 'Portoviejo', 'Loja', 'Ambato', 'Esmeraldas', 'Riobamba']
            },
            {
                code: 'SV',
                name: 'El Salvador 🇸🇻',
                cities: ['San Salvador', 'Santa Ana', 'San Miguel', 'Soyapango', 'Santa Tecla', 'Apopa', 'Delgado']
            },
            {
                code: 'ES',
                name: 'España 🇪🇸',
                cities: ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Palma de Mallorca', 'Las Palmas', 'Bilbao', 'Alicante', 'Córdoba', 'Valladolid']
            },
            {
                code: 'US',
                name: 'Estados Unidos 🇺🇸',
                cities: ['Miami', 'Nueva York', 'Los Ángeles', 'Chicago', 'Houston', 'Dallas', 'San Francisco', 'Orlando', 'Atlanta', 'Washington D.C.', 'Boston', 'Seattle', 'Las Vegas']
            },
            {
                code: 'GT',
                name: 'Guatemala 🇬🇹',
                cities: ['Ciudad de Guatemala', 'Mixco', 'Villa Nueva', 'Quetzaltenango', 'Escuintla', 'San Juan Sacatepéquez']
            },
            {
                code: 'HN',
                name: 'Honduras 🇭🇳',
                cities: ['Tegucigalpa', 'San Pedro Sula', 'Choloma', 'La Ceiba', 'El Progreso', 'Comayagua']
            },
            {
                code: 'MX',
                name: 'México 🇲🇽',
                cities: ['Ciudad de México', 'Guadalajara', 'Monterrey', 'Puebla', 'Tijuana', 'León', 'Ciudad Juárez', 'Cancún', 'Querétaro', 'Mérida', 'Zapopan', 'Toluca', 'Acapulco']
            },
            {
                code: 'NI',
                name: 'Nicaragua 🇳🇮',
                cities: ['Managua', 'León', 'Masaya', 'Matagalpa', 'Chinandega', 'Granada', 'Estelí']
            },
            {
                code: 'PA',
                name: 'Panamá 🇵🇦',
                cities: ['Ciudad de Panamá', 'San Miguelito', 'Tocumen', 'David', 'Colón', 'La Chorrera', 'Santiago']
            },
            {
                code: 'PY',
                name: 'Paraguay 🇵🇾',
                cities: ['Asunción', 'Ciudad del Este', 'San Lorenzo', 'Luque', 'Capiatá', 'Lambaré', 'Encarnación']
            },
            {
                code: 'DO',
                name: 'República Dominicana 🇩🇴',
                cities: ['Santo Domingo', 'Santiago de los Caballeros', 'Santo Domingo Este', 'Santo Domingo Norte', 'Puerto Plata', 'La Romana', 'San Pedro de Macorís', 'Punta Cana']
            },
            {
                code: 'UY',
                name: 'Uruguay 🇺🇾',
                cities: ['Montevideo', 'Salto', 'Ciudad de la Costa', 'Paysandú', 'Las Piedras', 'Maldonado', 'Rivera', 'Punta del Este']
            },
            {
                code: 'VE',
                name: 'Venezuela 🇻🇪',
                cities: ['Caracas', 'Maracaibo', 'Valencia', 'Barquisimeto', 'Maracay', 'Ciudad Guayana', 'San Cristóbal', 'Barcelona', 'Maturín', 'Puerto La Cruz']
            },
            {
                code: 'DE',
                name: 'Alemania 🇩🇪',
                cities: ['Berlín', 'Múnich', 'Hamburgo', 'Fráncfort', 'Colonia', 'Stuttgart', 'Düsseldorf', 'Dortmund']
            },
            {
                code: 'CA',
                name: 'Canadá 🇨🇦',
                cities: ['Toronto', 'Montreal', 'Vancouver', 'Calgary', 'Edmonton', 'Ottawa', 'Quebec', 'Winnipeg']
            },
            {
                code: 'FR',
                name: 'Francia 🇫🇷',
                cities: ['París', 'Marsella', 'Lyon', 'Toulouse', 'Niza', 'Nantes', 'Estrasburgo', 'Burdeos', 'Lille']
            },
            {
                code: 'IT',
                name: 'Italia 🇮🇹',
                cities: ['Roma', 'Milán', 'Nápoles', 'Turín', 'Palermo', 'Génova', 'Bolonia', 'Florencia', 'Venecia']
            },
            {
                code: 'GB',
                name: 'Reino Unido 🇬🇧',
                cities: ['Londres', 'Mánchester', 'Birmingham', 'Edimburgo', 'Glasgow', 'Liverpool', 'Bristol', 'Leeds']
            },
            {
                code: 'JP',
                name: 'Japón 🇯🇵',
                cities: ['Tokio', 'Yokohama', 'Osaka', 'Nagoya', 'Sapporo', 'Fukuoka', 'Kioto', 'Kobe']
            },
            {
                code: 'AU',
                name: 'Australia 🇦🇺',
                cities: ['Sídney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaida', 'Canberra', 'Gold Coast']
            },
            {
                code: 'OTHER',
                name: 'Otro País 🌍',
                cities: ['Ciudad Principal', 'Capital', 'Otra Ciudad']
            }
        ];

        function initCountryAndCitySelectors() {
            const countrySelect = document.getElementById('buyerCountry');
            const citySelect = document.getElementById('buyerCity');
            if (!countrySelect || !citySelect) return;

            // Limpiar y poblar selector de países
            countrySelect.innerHTML = '';
            worldCountriesData.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.code;
                opt.textContent = c.name;
                if (c.code === 'PE') opt.selected = true;
                countrySelect.appendChild(opt);
            });

            function populateCities(countryCode, defaultCity = null) {
                const country = worldCountriesData.find(c => c.code === countryCode) || worldCountriesData[0];
                citySelect.innerHTML = '';
                country.cities.forEach(cityName => {
                    const opt = document.createElement('option');
                    opt.value = cityName;
                    opt.textContent = cityName;
                    if (defaultCity && cityName.toLowerCase().includes(defaultCity.toLowerCase())) {
                        opt.selected = true;
                    }
                    citySelect.appendChild(opt);
                });
            }

            // Inicializar por defecto con las 25 ciudades/regiones de Perú
            populateCities('PE');

            countrySelect.addEventListener('change', function () {
                populateCities(this.value);
            });
        }

        // Listener de seguridad si KR ya está listo e inicialización general
        document.addEventListener('DOMContentLoaded', function () {
            initCountryAndCitySelectors();

            if (typeof KR !== 'undefined' && typeof KR.onFormReady === 'function') {
                KR.onFormReady(function () {
                    KR.onSubmit(handleIzipaySubmit);
                });
            }
        });

        // Modal de Login Rápido de Cliente
        function openFastLoginModal() {
            document.getElementById('fastLoginModal').style.display = 'flex';
        }

        function closeFastLoginModal() {
            document.getElementById('fastLoginModal').style.display = 'none';
        }

        function submitFastLogin(e) {
            e.preventDefault();
            const email = document.getElementById('fastLoginEmail').value.trim();
            const password = document.getElementById('fastLoginPassword').value;
            const errBox = document.getElementById('fastLoginError');
            const btn = document.getElementById('btnSubmitFastLogin');

            btn.disabled = true;
            btn.textContent = 'Iniciando sesión...';
            errBox.style.display = 'none';

            fetch("{{ route('web.customer.login') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email, password: password })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Ingresar a mi Cuenta';
                if (data.success) {
                    if (data.user) {
                        document.getElementById('buyerFullName').value = data.user.name || '';
                        document.getElementById('buyerEmail').value = data.user.email || '';
                        if (data.user.dni) document.getElementById('buyerDoc').value = data.user.dni;
                        if (data.user.phone) document.getElementById('buyerPhone').value = data.user.phone;
                    }
                    closeFastLoginModal();
                    alert('¡Sesión iniciada con éxito! Tus datos se han autocompletado.');
                    location.reload();
                } else {
                    errBox.textContent = data.message || 'Credenciales inválidas.';
                    errBox.style.display = 'block';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Ingresar a mi Cuenta';
                errBox.textContent = 'Error al iniciar sesión.';
                errBox.style.display = 'block';
            });
        }
    </script>

    <!-- MODAL INICIO DE SESIÓN RÁPIDO PARA CLIENTES -->
    <div id="fastLoginModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99999; align-items: center; justify-content: center; backdrop-filter: blur(6px);">
        <div style="background: #FFFFFF; border-radius: 20px; width: 95%; max-width: 440px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.3rem; font-weight: 900; color: #0F172A; margin: 0;">🔑 Iniciar Sesión</h3>
                <button type="button" onclick="closeFastLoginModal()" style="background: #F1F5F9; border: none; color: #64748B; width: 32px; height: 32px; border-radius: 8px; font-weight: 800; cursor: pointer;">✕</button>
            </div>
            <p style="font-size: 0.875rem; color: #64748B; margin: 0 0 1.25rem 0;">Ingresa tu correo y contraseña para cargar tus datos y asociar tus boletos.</p>

            <div id="fastLoginError" style="display: none; background: #FEF2F2; border: 1px solid #FCA5A5; color: #DC2626; padding: 0.75rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1rem;"></div>

            <form onsubmit="submitFastLogin(event)">
                <div style="margin-bottom: 1rem;">
                    <label class="field-dark-label">Correo Electrónico:</label>
                    <input type="email" id="fastLoginEmail" class="input-dark-text" placeholder="tu.correo@ejemplo.com" required style="width: 100%;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label class="field-dark-label">Contraseña:</label>
                    <input type="password" id="fastLoginPassword" class="input-dark-text" placeholder="••••••••" required style="width: 100%;">
                </div>
                <button type="submit" id="btnSubmitFastLogin" style="width: 100%; background: #FF5500; color: #FFF; border: none; padding: 0.85rem; border-radius: 12px; font-size: 0.95rem; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(255,85,0,0.3);">
                    Ingresar a mi Cuenta
                </button>
            </form>
        </div>
    </div>

    @include('web.customer.partials.ticket_generator_js')
@endpush
