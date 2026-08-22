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
                    <div class="izipay-verified-pill">
                        <span class="izipay-logo-txt">izi<span>pay</span></span>
                        <span class="verified-dot">● Pasarela Oficial</span>
                    </div>
                    <div class="ssl-pill">
                        🛡️ PCI-DSS Nivel 1
                    </div>
                </div>
            </div>
        </div>

        <!-- GRID PRINCIPAL DE 2 COLUMNAS -->
        <div class="checkout-grid-layout">
            
            <!-- COLUMNA IZQUIERDA: CARRITO, DATOS Y PASARELA IZIPAY -->
            <div class="checkout-steps-column">
                
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
                            <div class="cart-row-item" data-price="{{ $item['price'] }}">
                                <div class="cart-row-main">
                                    <span class="cart-item-emoji">🎟️</span>
                                    <div>
                                        <h4 class="cart-item-title">{{ $item['name'] }}</h4>
                                        <span class="cart-item-unit-cost">Precio: <strong>S/ {{ number_format($item['price'], 2) }}</strong> c/u</span>
                                    </div>
                                </div>

                                <div class="cart-row-controls">
                                    <div class="light-qty-counter">
                                        <button type="button" class="btn-lgt-qty" onclick="updateItemQuantity({{ $index }}, -1)">−</button>
                                        <span class="lgt-qty-num" id="qty-val-{{ $index }}">{{ $item['quantity'] }}</span>
                                        <button type="button" class="btn-lgt-qty" onclick="updateItemQuantity({{ $index }}, 1)">+</button>
                                    </div>
                                    <span class="cart-row-subtotal-price" id="subtotal-val-{{ $index }}">
                                        S/ {{ number_format(($item['price'] * $item['quantity']), 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
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

                    <form id="checkoutCustomerForm" class="customer-fields-grid" onsubmit="event.preventDefault(); loadIzipayGateway();">
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

                        <div style="margin-top: 1.25rem; background: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: 12px; padding: 0.9rem 1.15rem; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.25rem;">🎟️</span>
                            <span style="font-size: 0.85rem; color: #065F46; font-weight: 700; line-height: 1.4;">
                                Tu <strong>Entrada Oficial en PDF</strong> y las <strong>credenciales temporales</strong> de acceso para consultar tus boletos en línea llegarán automáticamente a este correo al completar el pago.
                            </span>
                        </div>
                    </form>
                </div>

                <!-- PASO 3: PASARELA OFICIAL IZIPAY -->
                <div class="checkout-white-card" style="margin-top: 1.5rem;">
                    <div class="card-step-header">
                        <div class="step-num-badge">3</div>
                        <div>
                            <h2 class="card-step-title">Método de Pago Seguro (Izipay)</h2>
                            <p class="card-step-subtitle">Paga con Tarjetas de Crédito/Débito, QR Yape, Plin o PagoEfectivo.</p>
                        </div>
                    </div>

                    <!-- Métodos soportados Pills -->
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

                    <!-- Error Alert Box -->
                    <div id="checkoutFormError" style="display: none; background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 0.85rem 1.15rem; border-radius: 12px; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.25rem;"></div>

                    <!-- Botón Inicial de Carga de Pasarela -->
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

                    <!-- Loader mientras se genera el token -->
                    <div id="izipayLoaderBox" style="display: none; text-align: center; padding: 2.5rem 1rem;">
                        <div class="preloader-ring" style="width: 44px; height: 44px; margin: 0 auto 0.85rem auto; border-width: 3.5px; border-color: #FF5500; border-top-color: transparent;"></div>
                        <p style="font-size: 0.95rem; color: #1E293B; font-weight: 700; margin: 0;">Conectando con la pasarela segura de Izipay...</p>
                        <span style="font-size: 0.8rem; color: #64748B;">Generando sesión cifrada de pago</span>
                    </div>

                    <!-- CONTENEDOR OFICIAL DE IZIPAY KRYPTON EMBEDDED -->
                    <div id="izipayEmbeddedContainer" style="display: none; margin-top: 1.5rem; min-height: 280px;">
                        <div class="kr-embedded"></div>
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

    @media (max-width: 950px) {
        .checkout-grid-layout {
            grid-template-columns: 1fr;
        }
        .order-summary-light-card {
            position: relative;
            top: 0;
        }
        .form-fields-2col {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
    @php
        $publicKey = $izipay->getCredential('public_key') ?: env('IZIPAY_PUBLIC_KEY', '');
        $clientEndpoint = $izipay->getCredential('client_endpoint', 'https://api.micuentaweb.pe');
    @endphp

    <!-- Librería Oficial de Izipay (Krypton Form) -->
    <script 
        src="{{ rtrim($clientEndpoint, '/') }}/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
        kr-public-key="{{ $publicKey }}"
        kr-language="es">
    </script>
    <link rel="stylesheet" href="{{ rtrim($clientEndpoint, '/') }}/static/js/krypton-client/V4.0/ext/classic-reset.css">
    <script src="{{ rtrim($clientEndpoint, '/') }}/static/js/krypton-client/V4.0/ext/classic.js"></script>

    <script>
        let cartItems = @json($cartItems);
        let eventData = @json($eventData);
        let currentGrandTotal = {{ $grandTotal }};
        let isIzipayInitialized = false;

        // Actualizar cantidades en el carrito
        function updateItemQuantity(index, delta) {
            if (isIzipayInitialized) {
                alert('⚠️ Ya generaste la sesión de pago. Si deseas modificar tus entradas, por favor recarga la página.');
                return;
            }

            if (!cartItems[index]) return;

            let newQty = (cartItems[index].quantity || 1) + delta;
            if (newQty < 1) newQty = 1;

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

            document.getElementById('btnPayAmountDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            document.getElementById('summarySubtotalDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            document.getElementById('summaryTotalDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
        }

        // Cargar Pasarela Oficial de Izipay
        function loadIzipayGateway() {
            const name = document.getElementById('buyerFullName').value.trim();
            const doc = document.getElementById('buyerDoc').value.trim();
            const email = document.getElementById('buyerEmail').value.trim();
            const phone = document.getElementById('buyerPhone').value.trim();
            const errorBox = document.getElementById('checkoutFormError');
            const btnInit = document.getElementById('btnInitIzipay');

            if (!name || !doc || !email || !phone) {
                errorBox.textContent = '⚠️ Por favor completa todos los campos del Paso 2 (Datos del Comprador) antes de proceder al pago.';
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
                    customer_doc: doc
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.formToken) {
                    isIzipayInitialized = true;

                    // Inicializar Krypton Form de Izipay
                    if (typeof KR !== 'undefined') {
                        KR.setFormToken(data.formToken).then(function() {
                            document.getElementById('izipayLoaderBox').style.display = 'none';
                            document.getElementById('izipayEmbeddedContainer').style.display = 'block';

                            // Vincular el manejador de éxito directamente
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

        // Manejador centralizado de finalización de pago en Izipay
        function handleIzipaySubmit(response) {
            console.log('[Izipay] Callback de pago recibido:', response);

            // Mostrar modal de procesamiento de compra
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '🎉 ¡Pago Confirmado!',
                    html: 'Izipay ha procesado tu transacción con éxito.<br>Estamos generando tus entradas oficiales con código QR y creando tu perfil...',
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

            // Enviar respuesta al backend para verificar firma y registrar boletos
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
                    'customer_phone': document.getElementById('buyerPhone')?.value || ''
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    // Redirigir a la página de confirmación con los boletos generados
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

            return false; // Previene redirección por defecto para que nosotros manejemos la navegación
        }

        // Listener de seguridad si KR ya está listo
        document.addEventListener('DOMContentLoaded', function () {
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
                    // Autocompletar datos del comprador
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
@endpush
