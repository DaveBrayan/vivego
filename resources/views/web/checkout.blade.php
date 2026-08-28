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
                    @if(!empty($isUpgrade) && !empty($upgradeData))
                        <!-- BANNER OFICIAL DE UPGRADE / MEJORA DE ENTRADA -->
                        <div style="background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%); border-radius: 20px; padding: 1.25rem 1.5rem; color: #FFFFFF; margin-bottom: 1.5rem; border: 1.5px solid #818CF8; box-shadow: 0 10px 25px rgba(67, 56, 202, 0.25); position: relative; overflow: hidden;">
                            <div style="position: absolute; right: -20px; top: -20px; width: 120px; height: 120px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.85rem; position: relative; z-index: 2;">
                                <div style="display: flex; align-items: center; gap: 0.85rem;">
                                    <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; border: 1px solid rgba(255,255,255,0.25);">
                                        ⭐
                                    </div>
                                    <div>
                                        <div style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(99,102,241,0.35); border: 1px solid rgba(165,180,252,0.4); padding: 0.15rem 0.6rem; border-radius: 20px; font-size: 0.68rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.8px; color: #E0E7FF; margin-bottom: 0.25rem;">
                                            VIVEGO UPGRADE OFICIAL
                                        </div>
                                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 900; color: #FFFFFF; letter-spacing: -0.3px;">
                                            Mejora de Entrada a {{ $upgradeData['target_zone'] }}
                                        </h3>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 0.72rem; color: #C7D2FE; font-weight: 800; text-transform: uppercase; display: block;">Solo pagas la diferencia:</span>
                                    <strong style="font-size: 1.45rem; color: #34D399; font-weight: 900;">S/ {{ number_format($upgradeData['total_difference'], 2) }}</strong>
                                </div>
                            </div>
                            
                            <div style="margin-top: 0.95rem; padding-top: 0.75rem; border-top: 1px dashed rgba(255,255,255,0.22); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; font-size: 0.82rem; color: #E0E7FF; position: relative; z-index: 2;">
                                <span>🎟️ Entrada anterior reconocida: <strong>{{ $upgradeData['quantity'] }}x {{ $upgradeData['original_zone'] }}</strong> (Abono: S/ {{ number_format($upgradeData['original_total'], 2) }})</span>
                                <span>✨ Nueva zona seleccionada: <strong>{{ $upgradeData['quantity'] }}x {{ $upgradeData['target_zone'] }}</strong> (S/ {{ number_format($upgradeData['target_unit_price'] * $upgradeData['quantity'], 2) }})</span>
                            </div>
                        </div>
                    @endif

                    <div class="card-step-header">
                        <div class="step-num-badge">1</div>
                        <div>
                            <h2 class="card-step-title">{{ !empty($isUpgrade) ? 'Detalle de la Mejora de Entrada' : 'Tus Entradas Seleccionadas' }}</h2>
                            <p class="card-step-subtitle">{{ !empty($isUpgrade) ? 'Revisa el cálculo de diferencia a pagar por tu upgrade de zona.' : 'Revisa o ajusta la cantidad de entradas para este evento.' }}</p>
                        </div>
                    </div>

                    <div class="cart-items-list">
                        @foreach($cartItems as $index => $item)
                            @php
                                $isItemUpgrade = !empty($item['is_upgrade']);
                                $isPresale = !empty($item['is_presale']) || (!empty($item['presale_discount']) && (float)$item['presale_discount'] > 0);
                                $hasCamp = !empty($item['has_campaign']);
                                $basePrice = !empty($item['base_price']) ? (float)$item['base_price'] : (!empty($item['regular_price']) ? (float)$item['regular_price'] : (float)$item['price']);
                                $regPrice = !empty($item['regular_price']) ? (float)$item['regular_price'] : (float)$item['price'];
                                $curPrice = (float)$item['price'];
                                $discountVal = $item['presale_discount'] ?? 0;
                            @endphp
                            <div class="cart-row-item" data-price="{{ $item['price'] }}" style="{{ $isItemUpgrade ? 'border: 2px solid #818CF8; background: #FAF5FF;' : ($hasCamp ? 'border: 1.5px solid rgba(255, 85, 0, 0.35); background: #FFFBF8;' : ($isPresale ? 'border: 1.5px solid rgba(255, 85, 0, 0.3); background: #FFFBF8;' : '')) }}">
                                <div class="cart-row-main">
                                    <span class="cart-item-emoji">{{ $isItemUpgrade ? '⭐' : '🎟️' }}</span>
                                    <div class="cart-item-text-box" style="flex: 1; min-width: 0;">
                                        <div class="cart-item-header-wrap" style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                            <h4 class="cart-item-title" style="margin: 0; font-size: 1rem; font-weight: 800; color: #0F172A;">{{ $item['name'] }}</h4>
                                            @if($isItemUpgrade)
                                                <span style="background: linear-gradient(135deg, #6366F1, #4F46E5); color: #FFFFFF; font-size: 0.65rem; font-weight: 900; padding: 2px 8px; border-radius: 6px; box-shadow: 0 2px 5px rgba(99,102,241,0.25); text-transform: uppercase; letter-spacing: 0.4px; white-space: nowrap;">
                                                    ⭐ UPGRADE ACTIVO
                                                </span>
                                            @elseif($hasCamp)
                                                <span style="background: linear-gradient(135deg, #FF5500, #FF1E3C); color: #FFFFFF; font-size: 0.65rem; font-weight: 900; padding: 2px 7px; border-radius: 6px; box-shadow: 0 2px 5px rgba(255,85,0,0.25); text-transform: uppercase; letter-spacing: 0.4px; white-space: nowrap;">
                                                    🔥 {{ $activeCampaign['badge_text'] ?? 'CAMPAÑA ACTIVA' }}
                                                </span>
                                            @elseif($isPresale)
                                                <span style="background: linear-gradient(135deg, #FF5500, #FF1E3C); color: #FFFFFF; font-size: 0.65rem; font-weight: 900; padding: 2px 7px; border-radius: 6px; box-shadow: 0 2px 5px rgba(255,85,0,0.25); text-transform: uppercase; letter-spacing: 0.4px; white-space: nowrap;">
                                                    🔥 PREVENTA {{ $discountVal > 0 ? '-' . $discountVal . '%' : '' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.45rem; margin-top: 0.2rem; flex-wrap: wrap;">
                                            @if($isItemUpgrade)
                                                <span class="cart-item-unit-cost" style="font-size: 0.825rem; color: #4338CA; font-weight: 700;">
                                                    Diferencia por entrada: <strong style="color: #059669; font-size: 0.95rem; font-weight: 900;">S/ {{ number_format($curPrice, 2) }}</strong>
                                                </span>
                                                <span style="font-size: 0.775rem; color: #64748B;">
                                                    (Valor nueva zona: S/ {{ number_format($regPrice, 2) }})
                                                </span>
                                            @else
                                                <span class="cart-item-unit-cost" style="font-size: 0.825rem; color: #475569;">
                                                    Precio: <strong style="color: var(--color-primary-orange); font-size: 0.9rem;">S/ {{ number_format($curPrice, 2) }}</strong> c/u
                                                </span>
                                                @if(($hasCamp || $isPresale) && $basePrice > $curPrice)
                                                    <span style="font-size: 0.8rem; color: #94A3B8; text-decoration: line-through; font-weight: 600;">
                                                        S/ {{ number_format($basePrice, 2) }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-row-controls">
                                    @if($isItemUpgrade)
                                        <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 10px; padding: 0.35rem 0.75rem; font-weight: 800; font-size: 0.85rem; color: #4F46E5;">
                                            {{ $item['quantity'] }}x entradas
                                        </div>
                                    @else
                                        <div class="light-qty-counter">
                                            <button type="button" class="btn-lgt-qty" onclick="updateItemQuantity({{ $index }}, -1)">−</button>
                                            <span class="lgt-qty-num" id="qty-val-{{ $index }}">{{ $item['quantity'] }}</span>
                                            <button type="button" class="btn-lgt-qty" onclick="updateItemQuantity({{ $index }}, 1)">+</button>
                                        </div>
                                    @endif
                                    <span class="cart-row-subtotal-price" id="subtotal-val-{{ $index }}" style="{{ $isItemUpgrade ? 'color: #059669; font-weight: 900;' : '' }}">
                                        S/ {{ number_format(($curPrice * $item['quantity']), 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección Cupón Promocional Exclusiva para Móvil (Arriba de Total a Pagar) -->
                    <div class="mobile-coupon-section-box" style="margin-top: 1.15rem; background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 12px; padding: 0.85rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.45rem;">
                            <span style="font-size: 0.825rem; font-weight: 800; color: #1E293B; display: flex; align-items: center; gap: 0.35rem;">
                                <span>🎟️</span> ¿Tienes un cupón de descuento?
                            </span>
                        </div>
                        
                        <div id="mobileCouponInputContainer" style="display: flex; gap: 0.45rem;">
                            <input type="text" id="mobileCheckoutCouponInput" placeholder="Ingresa código..." oninput="this.value = this.value.toUpperCase().replace(/\s+/g, '')" style="flex: 1; min-width: 0; padding: 0.5rem 0.75rem; border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 0.85rem; font-family: monospace; font-weight: 800; text-transform: uppercase; color: #0F172A; outline: none; background: #FFFFFF;">
                            <button type="button" id="btnApplyMobileCoupon" onclick="applyCouponCode('mobile')" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.85rem; border: none; padding: 0 0.95rem; border-radius: 10px; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,240,255,0.25);">
                                Aplicar
                            </button>
                        </div>

                        <!-- Badge de Cupón Aplicado en Móvil -->
                        <div id="mobileCouponAppliedBadge" style="display: none; align-items: center; justify-content: space-between; background: rgba(16, 185, 129, 0.12); border: 1.5px solid #10B981; border-radius: 10px; padding: 0.55rem 0.75rem; margin-top: 0.45rem;">
                            <div style="display: flex; align-items: center; gap: 0.45rem; min-width: 0;">
                                <span style="font-size: 1.1rem;">🎟️</span>
                                <div style="min-width: 0;">
                                    <strong id="mobileAppliedCouponCodeText" style="font-size: 0.85rem; color: #065F46; font-family: monospace; display: block;">CUPÓN</strong>
                                    <span id="mobileAppliedCouponDiscountText" style="font-size: 0.75rem; color: #047857; display: block; font-weight: 700;">-S/ 0.00</span>
                                </div>
                            </div>
                            <button type="button" onclick="removeAppliedCoupon()" title="Remover cupón" style="background: none; border: none; color: #EF4444; font-weight: 800; font-size: 0.8rem; cursor: pointer; padding: 0.2rem 0.35rem; flex-shrink: 0;">
                                ✕ Quitar
                            </button>
                        </div>
                        
                        <div id="mobileCouponMessageFeedback" style="display: none; font-size: 0.775rem; font-weight: 700; margin-top: 0.4rem;"></div>
                    </div>

                    <!-- Desglose de Descuentos en Móvil si aplica Campaña o Cupón -->
                    <div id="mobileDiscountBreakdownBox" style="{{ (!empty($activeCampaign) && $campaignDiscountTotal > 0) ? 'display: block;' : 'display: none;' }} margin-top: 0.85rem; padding-top: 0.75rem; border-top: 1px dashed #E2E8F0; font-size: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem; color: #64748B;">
                            <span>Subtotal Base:</span>
                            <strong id="mobileSummarySubtotalDisplay">S/ {{ number_format($originalSubtotal > 0 ? $originalSubtotal : $grandTotal, 2) }}</strong>
                        </div>
                        <div id="mobileCampaignDiscountRow" style="{{ (!empty($activeCampaign) && $campaignDiscountTotal > 0) ? 'display: flex;' : 'display: none;' }} justify-content: space-between; margin-bottom: 0.3rem; color: #FF5500; font-weight: 800;">
                            <span>🔥 <span id="mobileCampaignNameDisplay">{{ $activeCampaign['badge_text'] ?? 'Campaña' }}:</span></span>
                            <strong id="mobileCampaignDiscountDisplay">-S/ {{ number_format($campaignDiscountTotal, 2) }}</strong>
                        </div>
                        <div id="mobileCouponDiscountRow" style="display: none; justify-content: space-between; margin-bottom: 0.3rem; color: #059669; font-weight: 800;">
                            <span>🎟️ <span id="mobileCouponNameDisplay">Cupón:</span></span>
                            <strong id="mobileCouponDiscountDisplay">-S/ 0.00</strong>
                        </div>
                    </div>

                    <!-- Total en Paso 1 -->
                    <div style="margin-top: 0.85rem; padding-top: 0.85rem; border-top: 1.5px dashed #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
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
                                <span>🟧 Generar Código QR <span id="btnPayAmountDisplayCulqi">S/ {{ number_format($grandTotal, 2) }}</span> con Culqi</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                            <p style="font-size: 0.825rem; color: #64748B; margin-top: 0.85rem;">
                                🔒 Pagos instantáneos con <strong>Código QR (Yape, Plin), Tarjetas y PagoEfectivo</strong> respaldados por Culqi Perú.
                            </p>
                        </div>

                        <!-- Loader Culqi -->
                        <div id="culqiLoaderBox" style="display: none; text-align: center; padding: 2.5rem 1rem;">
                            <div class="preloader-ring" style="width: 44px; height: 44px; margin: 0 auto 0.85rem auto; border-width: 3.5px; border-color: #FF5500; border-top-color: transparent;"></div>
                            <p style="font-size: 0.95rem; color: #1E293B; font-weight: 700; margin: 0;">Generando código QR oficial en Culqi...</p>
                            <span style="font-size: 0.8rem; color: #64748B;">Conectando con servidores de Culqi / Niubiz</span>
                        </div>

                        <!-- Contenedor Oficial QR Directo en Pantalla -->
                        <div id="culqiQrDisplaySection" style="display: none; text-align: center; margin-top: 1rem; padding: 1.5rem 1.25rem; background: #FFFFFF; border: 2px solid #FED7AA; border-radius: 18px; box-shadow: 0 10px 25px -5px rgba(255,85,0,0.08);">
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: #FFF7ED; padding: 0.35rem 0.95rem; border-radius: 20px; color: #EA580C; font-weight: 800; font-size: 0.85rem; margin-bottom: 0.75rem;">
                                <span>📱</span> Código QR Oficial Culqi (Yape / Plin / Billeteras)
                            </div>
                            <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0 0 0.4rem 0;">Escanea el Código QR para Pagar</h3>
                            <p style="font-size: 0.85rem; color: #64748B; margin: 0 0 1.25rem 0;">
                                Abre tu app <strong>Yape</strong>, <strong>Plin</strong> o tu banca móvil y escanea el código a continuación:
                            </p>

                            <!-- QR Box -->
                            <div style="display: inline-block; padding: 1rem; background: #FFFFFF; border-radius: 16px; border: 2px solid #FED7AA; box-shadow: 0 4px 15px rgba(0,0,0,0.06); margin-bottom: 0.85rem;">
                                <img id="culqiQrImage" src="" alt="Código QR Culqi" style="width: 230px; height: 230px; display: block; border-radius: 8px; object-fit: contain; margin: 0 auto;">
                                <div style="margin-top: 0.65rem; font-size: 1.2rem; font-weight: 900; color: #EA580C;" id="culqiQrAmountText">
                                    S/ 0.00
                                </div>
                            </div>

                            <!-- Código CIP / PagoEfectivo si existe -->
                            <div id="culqiCipBox" style="display: none; background: #F8FAFC; border: 1px dashed #CBD5E1; padding: 0.75rem 1rem; border-radius: 12px; max-width: 360px; margin: 0 auto 1rem auto;">
                                <span style="font-size: 0.775rem; color: #64748B; display: block;">Código de Pago CIP (PagoEfectivo / Agentes):</span>
                                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: 0.25rem;">
                                    <strong style="font-size: 1.2rem; color: #0F172A; letter-spacing: 1px;" id="culqiCipCode">---</strong>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="copyCulqiCipCode()" style="padding: 0.25rem 0.65rem; font-size: 0.75rem; font-weight: 700;">📋 Copiar</button>
                                </div>
                            </div>

                            <!-- Estado en tiempo real con animación de pulso -->
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 0.75rem; background: #F0FDF4; border-radius: 12px; border: 1px solid #BBF7D0; max-width: 440px; margin: 0 auto 1.25rem auto;">
                                <span class="pulse-indicator" style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #16A34A; box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); animation: pulseGreen 1.5s infinite;"></span>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #166534;" id="culqiStatusMessage">Esperando tu pago... Se confirmará automáticamente</span>
                            </div>

                            <!-- Botones de Acción -->
                            <div style="display: flex; flex-direction: column; gap: 0.6rem; max-width: 380px; margin: 0 auto;">
                                <button type="button" class="btn btn-primary" onclick="verifyCulqiPaymentManual()" id="btnVerifyCulqiManual" style="padding: 0.75rem 1.25rem; font-weight: 800; background: linear-gradient(135deg, #FF5500, #E64A00); border-radius: 12px; width: 100%;">
                                    ⚡ Ya realicé el pago / Verificar ahora
                                </button>
                                <button type="button" class="btn btn-outline" onclick="openCulqiCardModalDirectly()" style="padding: 0.65rem 1.25rem; font-weight: 700; color: #475569; border-color: #CBD5E1; border-radius: 12px; width: 100%;">
                                    💳 Pagar con Tarjeta (Débito / Crédito)
                                </button>
                                <button type="button" onclick="cancelCulqiQrView()" style="font-size: 0.825rem; color: #94A3B8; text-decoration: underline; background: none; border: none; cursor: pointer; padding: 0.4rem;">
                                    ← Volver o elegir otro medio de pago
                                </button>
                            </div>
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

                        <!-- Sección Cupón Promocional Interactiva -->
                        <div class="coupon-section-box" style="margin-bottom: 1.25rem; background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 14px; padding: 0.95rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-size: 0.825rem; font-weight: 800; color: #1E293B; display: flex; align-items: center; gap: 0.35rem;">
                                    <span>🎟️</span> ¿Tienes un cupón de descuento?
                                </span>
                            </div>
                            
                            <div id="couponInputContainer" style="display: flex; gap: 0.5rem;">
                                <input type="text" id="checkoutCouponInput" placeholder="Ingresa código..." oninput="this.value = this.value.toUpperCase().replace(/\s+/g, '')" style="flex: 1; padding: 0.55rem 0.8rem; border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 0.85rem; font-family: monospace; font-weight: 800; text-transform: uppercase; color: #0F172A; outline: none; background: #FFFFFF;">
                                <button type="button" id="btnApplyCheckoutCoupon" onclick="applyCouponCode()" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.85rem; border: none; padding: 0 0.95rem; border-radius: 10px; cursor: pointer; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,240,255,0.3);">
                                    Aplicar
                                </button>
                            </div>

                            <!-- Badge de Cupón Aplicado -->
                            <div id="couponAppliedBadge" style="display: none; align-items: center; justify-content: space-between; background: rgba(16, 185, 129, 0.12); border: 1.5px solid #10B981; border-radius: 10px; padding: 0.55rem 0.8rem; margin-top: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.1rem;">🎟️</span>
                                    <div>
                                        <strong id="appliedCouponCodeText" style="font-size: 0.85rem; color: #065F46; font-family: monospace;">CUPÓN</strong>
                                        <span id="appliedCouponDiscountText" style="font-size: 0.75rem; color: #047857; display: block; font-weight: 700;">-S/ 0.00</span>
                                    </div>
                                </div>
                                <button type="button" onclick="removeAppliedCoupon()" title="Remover cupón" style="background: none; border: none; color: #EF4444; font-weight: 800; font-size: 0.8rem; cursor: pointer; padding: 0.2rem 0.4rem;">
                                    ✕ Quitar
                                </button>
                            </div>
                            
                            <div id="couponMessageFeedback" style="display: none; font-size: 0.775rem; font-weight: 700; margin-top: 0.4rem;"></div>
                        </div>

                        <!-- Desglose Financiero -->
                        <div class="pricing-summary-box">
                            @if(!empty($isUpgrade) && !empty($upgradeData))
                                <div class="price-line" style="color: #4F46E5;">
                                    <span class="line-label" style="font-weight: 700;">Nueva Zona ({{ $upgradeData['quantity'] }}x {{ $upgradeData['target_zone'] }}):</span>
                                    <strong class="line-amount" style="color: #4F46E5; font-weight: 800;">S/ {{ number_format($upgradeData['target_unit_price'] * $upgradeData['quantity'], 2) }}</strong>
                                </div>
                                <div class="price-line" style="color: #059669;">
                                    <span class="line-label" style="font-weight: 700;">Abono Entrada Previa:</span>
                                    <strong class="line-amount" style="color: #059669; font-weight: 800;">-S/ {{ number_format($upgradeData['original_total'], 2) }}</strong>
                                </div>
                                <div class="price-line" style="border-top: 1px dashed #E2E8F0; padding-top: 0.4rem; margin-top: 0.4rem;">
                                    <span class="line-label" style="font-weight: 800; color: #0F172A;">Diferencia a Pagar:</span>
                                    <strong class="line-amount" id="summarySubtotalDisplay" style="color: #059669; font-weight: 900;">S/ {{ number_format($grandTotal, 2) }}</strong>
                                </div>
                            @else
                                <div class="price-line">
                                    <span class="line-label">Subtotal Base:</span>
                                    <strong class="line-amount" id="summarySubtotalDisplay">S/ {{ number_format($originalSubtotal > 0 ? $originalSubtotal : $grandTotal, 2) }}</strong>
                                </div>
                            @endif

                            <!-- Descuento de Campaña si aplica -->
                            <div class="price-line" id="summaryCampaignRow" style="{{ !empty($activeCampaign) && $campaignDiscountTotal > 0 ? '' : 'display: none;' }}">
                                <span class="line-label" style="color: #FF5500; font-weight: 800; display: flex; align-items: center; gap: 0.3rem;">
                                    <span>🔥</span> <span id="summaryCampaignNameDisplay">{{ $activeCampaign['badge_text'] ?? 'Campaña Promocional' }}:</span>
                                </span>
                                <strong class="line-amount" id="summaryCampaignDiscountDisplay" style="color: #FF5500; font-weight: 900;">
                                    -S/ {{ number_format($campaignDiscountTotal, 2) }}
                                </strong>
                            </div>

                            <!-- Descuento de Cupón si aplica -->
                            <div class="price-line" id="summaryCouponRow" style="display: none;">
                                <span class="line-label" style="color: #059669; font-weight: 800; display: flex; align-items: center; gap: 0.3rem;">
                                    <span>🎟️</span> <span id="summaryCouponNameDisplay">Cupón de Descuento:</span>
                                </span>
                                <strong class="line-amount" id="summaryCouponDiscountDisplay" style="color: #059669; font-weight: 900;">
                                    -S/ 0.00
                                </strong>
                            </div>

                            <div class="price-line">
                                <span class="line-label">Comisión por servicio:</span>
                                <span class="free-badge">Gratis (S/ 0.00)</span>
                            </div>
                            
                            <div class="total-big-line">
                                <span class="total-big-label">{{ !empty($isUpgrade) ? 'TOTAL DIFERENCIA:' : 'TOTAL A PAGAR:' }}</span>
                                <span class="total-big-val" id="summaryTotalDisplay" style="{{ !empty($isUpgrade) ? 'color: #059669;' : '' }}">S/ {{ number_format($grandTotal, 2) }}</span>
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

    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
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
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        transition: border-color 0.2s;
    }

    .cart-row-main {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        flex: 1;
        min-width: 0;
    }

    .cart-item-emoji {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .cart-item-title {
        font-size: 1.05rem !important;
        font-weight: 800 !important;
        color: #0F172A !important;
        margin: 0 !important;
    }

    .cart-item-unit-cost {
        font-size: 0.85rem;
        color: #64748B;
    }

    .cart-row-controls {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-shrink: 0;
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

    .mobile-coupon-section-box {
        display: none;
    }

    @media (max-width: 950px) {
        .mobile-coupon-section-box {
            display: block !important;
        }
        .mobile-event-mini-header {
            display: flex !important;
        }
        .checkout-grid-layout {
            grid-template-columns: 1fr;
            gap: 1.5rem;
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

    @media (max-width: 640px) {
        .checkout-white-card {
            padding: 1.25rem 1rem !important;
            border-radius: 16px !important;
        }
        .card-step-header {
            gap: 0.75rem;
            margin-bottom: 1.15rem;
            padding-bottom: 0.85rem;
        }
        .step-num-badge {
            width: 34px;
            height: 34px;
            font-size: 1rem;
            border-radius: 10px;
        }
        .card-step-title {
            font-size: 1.15rem !important;
        }
        .card-step-subtitle {
            font-size: 0.775rem !important;
        }
        .cart-row-item {
            padding: 0.85rem 1rem;
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
        .cart-row-main {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .cart-item-emoji {
            font-size: 1.35rem;
        }
        .cart-item-title {
            font-size: 0.95rem !important;
        }
        .cart-row-controls {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.65rem;
            border-top: 1px dashed #E2E8F0;
        }
        .cart-row-subtotal-price {
            font-size: 1.25rem;
            font-weight: 900;
            color: #059669;
            min-width: auto;
            text-align: right;
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
        let activeCampaign = @json($activeCampaign);
        let appliedCoupon = null;
        let isUpgrade = {{ !empty($isUpgrade) ? 'true' : 'false' }};
        let upgradeData = @json($upgradeData ?? null);

        let baseSubtotal = {{ (float)($originalSubtotal > 0 ? $originalSubtotal : $grandTotal) }};
        let campaignDiscountTotal = {{ (float)($campaignDiscountTotal ?? 0) }};
        let couponDiscountTotal = 0;
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

        // =========================================================================
        // RECALCULAR TOTALES, DESCUENTOS DE CAMPAÑA Y CUPONES
        // =========================================================================
        function recalculateOrderTotals() {
            baseSubtotal = 0;
            campaignDiscountTotal = 0;

            cartItems.forEach(item => {
                const qty = item.quantity || 1;
                const baseP = parseFloat(item.base_price || item.regular_price || item.price || 0);
                const campDisc = parseFloat(item.campaign_discount || 0);
                
                baseSubtotal += (baseP * qty);
                campaignDiscountTotal += (campDisc * qty);
            });

            const subtotalAfterCampaign = Math.max(0, baseSubtotal - campaignDiscountTotal);

            if (appliedCoupon) {
                if (appliedCoupon.discount_type === 'percentage') {
                    const pct = Math.min(100, Math.max(0, parseFloat(appliedCoupon.discount_value || 0)));
                    couponDiscountTotal = Math.round(subtotalAfterCampaign * (pct / 100) * 100) / 100;
                } else {
                    couponDiscountTotal = Math.min(subtotalAfterCampaign, parseFloat(appliedCoupon.discount_value || 0));
                }
            } else {
                couponDiscountTotal = 0;
            }

            currentGrandTotal = Math.max(0, Math.round((subtotalAfterCampaign - couponDiscountTotal) * 100) / 100);

            // 1. Actualizar DOM en Desktop
            if (document.getElementById('summarySubtotalDisplay')) {
                document.getElementById('summarySubtotalDisplay').textContent = 'S/ ' + baseSubtotal.toFixed(2);
            }

            const campRow = document.getElementById('summaryCampaignRow');
            if (campRow) {
                if (activeCampaign && campaignDiscountTotal > 0) {
                    campRow.style.display = 'flex';
                    document.getElementById('summaryCampaignDiscountDisplay').textContent = '-S/ ' + campaignDiscountTotal.toFixed(2);
                } else {
                    campRow.style.display = 'none';
                }
            }

            const cupRow = document.getElementById('summaryCouponRow');
            if (cupRow) {
                if (appliedCoupon && couponDiscountTotal > 0) {
                    cupRow.style.display = 'flex';
                    document.getElementById('summaryCouponNameDisplay').textContent = `Cupón ${appliedCoupon.code}:`;
                    document.getElementById('summaryCouponDiscountDisplay').textContent = '-S/ ' + couponDiscountTotal.toFixed(2);
                } else {
                    cupRow.style.display = 'none';
                }
            }

            // 2. Actualizar DOM en Móvil (Paso 1)
            if (document.getElementById('mobileSummarySubtotalDisplay')) {
                document.getElementById('mobileSummarySubtotalDisplay').textContent = 'S/ ' + baseSubtotal.toFixed(2);
            }

            const mobCampRow = document.getElementById('mobileCampaignDiscountRow');
            if (mobCampRow) {
                if (activeCampaign && campaignDiscountTotal > 0) {
                    mobCampRow.style.display = 'flex';
                    document.getElementById('mobileCampaignDiscountDisplay').textContent = '-S/ ' + campaignDiscountTotal.toFixed(2);
                } else {
                    mobCampRow.style.display = 'none';
                }
            }

            const mobCupRow = document.getElementById('mobileCouponDiscountRow');
            if (mobCupRow) {
                if (appliedCoupon && couponDiscountTotal > 0) {
                    mobCupRow.style.display = 'flex';
                    document.getElementById('mobileCouponNameDisplay').textContent = `Cupón ${appliedCoupon.code}:`;
                    document.getElementById('mobileCouponDiscountDisplay').textContent = '-S/ ' + couponDiscountTotal.toFixed(2);
                } else {
                    mobCupRow.style.display = 'none';
                }
            }

            const mobBreakdown = document.getElementById('mobileDiscountBreakdownBox');
            if (mobBreakdown) {
                if ((activeCampaign && campaignDiscountTotal > 0) || (appliedCoupon && couponDiscountTotal > 0)) {
                    mobBreakdown.style.display = 'block';
                } else {
                    mobBreakdown.style.display = 'none';
                }
            }

            // Totales y Botones
            if (document.getElementById('summaryTotalDisplay')) {
                document.getElementById('summaryTotalDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('cartSummaryTotalStep1')) {
                document.getElementById('cartSummaryTotalStep1').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('btnPayAmountDisplay')) {
                document.getElementById('btnPayAmountDisplay').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
            }
            if (document.getElementById('btnPayAmountDisplayCulqi')) {
                document.getElementById('btnPayAmountDisplayCulqi').textContent = 'S/ ' + currentGrandTotal.toFixed(2);
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
        // APLICAR & REMOVER CUPÓN DE DESCUENTO (Sincronizado Desktop + Móvil)
        // =========================================================================
        function applyCouponCode(source = 'desktop') {
            let code = '';
            if (source === 'mobile') {
                const mobInput = document.getElementById('mobileCheckoutCouponInput');
                code = mobInput ? mobInput.value.trim().toUpperCase() : '';
            } else {
                const deskInput = document.getElementById('checkoutCouponInput');
                code = deskInput ? deskInput.value.trim().toUpperCase() : '';
                if (!code) {
                    const mobInput = document.getElementById('mobileCheckoutCouponInput');
                    code = mobInput ? mobInput.value.trim().toUpperCase() : '';
                }
            }

            const deskMsg = document.getElementById('couponMessageFeedback');
            const mobMsg = document.getElementById('mobileCouponMessageFeedback');
            const deskBtn = document.getElementById('btnApplyCheckoutCoupon');
            const mobBtn = document.getElementById('btnApplyMobileCoupon');

            if (!code) {
                const targetMsg = (source === 'mobile' ? mobMsg : deskMsg) || mobMsg || deskMsg;
                if (targetMsg) {
                    targetMsg.style.color = '#EF4444';
                    targetMsg.textContent = '⚠️ Por favor ingresa un código de cupón.';
                    targetMsg.style.display = 'block';
                }
                return;
            }

            if (deskBtn) { deskBtn.disabled = true; deskBtn.textContent = '...'; }
            if (mobBtn) { mobBtn.disabled = true; mobBtn.textContent = '...'; }

            const currentSubtotalForCoupon = Math.max(0, baseSubtotal - campaignDiscountTotal);

            fetch("{{ route('web.checkout.validate_coupon') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    event_id: eventData.id || 1,
                    subtotal: currentSubtotalForCoupon
                })
            })
            .then(r => r.json())
            .then(d => {
                if (deskBtn) { deskBtn.disabled = false; deskBtn.textContent = 'Aplicar'; }
                if (mobBtn) { mobBtn.disabled = false; mobBtn.textContent = 'Aplicar'; }

                if (d.valid) {
                    appliedCoupon = d;
                    
                    // Sincronizar Desktop
                    const deskContainer = document.getElementById('couponInputContainer');
                    if (deskContainer) deskContainer.style.display = 'none';
                    const deskBadge = document.getElementById('couponAppliedBadge');
                    if (deskBadge) {
                        document.getElementById('appliedCouponCodeText').textContent = d.code;
                        document.getElementById('appliedCouponDiscountText').textContent = d.discount_type === 'percentage' 
                            ? `-${d.discount_value}% de descuento aplicado` 
                            : `-S/ ${parseFloat(d.discount_value).toFixed(2)} de descuento aplicado`;
                        deskBadge.style.display = 'flex';
                    }
                    if (deskMsg) {
                        deskMsg.style.color = '#10B981';
                        deskMsg.textContent = '✓ ' + (d.message || 'Cupón aplicado con éxito.');
                        deskMsg.style.display = 'block';
                    }

                    // Sincronizar Móvil
                    const mobContainer = document.getElementById('mobileCouponInputContainer');
                    if (mobContainer) mobContainer.style.display = 'none';
                    const mobBadge = document.getElementById('mobileCouponAppliedBadge');
                    if (mobBadge) {
                        document.getElementById('mobileAppliedCouponCodeText').textContent = d.code;
                        document.getElementById('mobileAppliedCouponDiscountText').textContent = d.discount_type === 'percentage' 
                            ? `-${d.discount_value}% de descuento` 
                            : `-S/ ${parseFloat(d.discount_value).toFixed(2)} de descuento`;
                        mobBadge.style.display = 'flex';
                    }
                    if (mobMsg) {
                        mobMsg.style.color = '#10B981';
                        mobMsg.textContent = '✓ ' + (d.message || 'Cupón aplicado con éxito.');
                        mobMsg.style.display = 'block';
                    }

                    recalculateOrderTotals();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `🎉 ¡Cupón ${d.code} aplicado!`,
                            showConfirmButton: false,
                            timer: 2000,
                            background: '#14141E',
                            color: '#FFFFFF'
                        });
                    }
                } else {
                    appliedCoupon = null;
                    const errTxt = '⚠️ ' + (d.message || 'Cupón no válido.');
                    if (deskMsg) { deskMsg.style.color = '#EF4444'; deskMsg.textContent = errTxt; deskMsg.style.display = 'block'; }
                    if (mobMsg) { mobMsg.style.color = '#EF4444'; mobMsg.textContent = errTxt; mobMsg.style.display = 'block'; }
                }
            })
            .catch(err => {
                if (deskBtn) { deskBtn.disabled = false; deskBtn.textContent = 'Aplicar'; }
                if (mobBtn) { mobBtn.disabled = false; mobBtn.textContent = 'Aplicar'; }
                const errTxt = '⚠️ Error al validar cupón. Intenta nuevamente.';
                if (deskMsg) { deskMsg.style.color = '#EF4444'; deskMsg.textContent = errTxt; deskMsg.style.display = 'block'; }
                if (mobMsg) { mobMsg.style.color = '#EF4444'; mobMsg.textContent = errTxt; mobMsg.style.display = 'block'; }
            });
        }

        function removeAppliedCoupon() {
            appliedCoupon = null;

            // Reset Desktop
            const deskBadge = document.getElementById('couponAppliedBadge');
            if (deskBadge) deskBadge.style.display = 'none';
            const deskContainer = document.getElementById('couponInputContainer');
            if (deskContainer) deskContainer.style.display = 'flex';
            const deskMsg = document.getElementById('couponMessageFeedback');
            if (deskMsg) { deskMsg.style.display = 'none'; deskMsg.textContent = ''; }
            const deskInput = document.getElementById('checkoutCouponInput');
            if (deskInput) deskInput.value = '';

            // Reset Móvil
            const mobBadge = document.getElementById('mobileCouponAppliedBadge');
            if (mobBadge) mobBadge.style.display = 'none';
            const mobContainer = document.getElementById('mobileCouponInputContainer');
            if (mobContainer) mobContainer.style.display = 'flex';
            const mobMsg = document.getElementById('mobileCouponMessageFeedback');
            if (mobMsg) { mobMsg.style.display = 'none'; mobMsg.textContent = ''; }
            const mobInput = document.getElementById('mobileCheckoutCouponInput');
            if (mobInput) mobInput.value = '';

            recalculateOrderTotals();
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

            recalculateOrderTotals();
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
                        coupon_code: appliedCoupon ? appliedCoupon.code : null,
                        coupon_discount: couponDiscountTotal,
                        campaign_name: activeCampaign ? activeCampaign.name : null,
                        campaign_discount: campaignDiscountTotal,
                        original_subtotal: baseSubtotal,
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
                    customer_city: city,
                    coupon_code: appliedCoupon ? appliedCoupon.code : null,
                    coupon_discount: couponDiscountTotal,
                    campaign_name: activeCampaign ? activeCampaign.name : null,
                    campaign_discount: campaignDiscountTotal,
                    original_subtotal: baseSubtotal,
                    upgrade_sale_id: upgradeData ? upgradeData.sale_id : null,
                    is_upgrade: isUpgrade
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
                    'coupon_code': appliedCoupon ? appliedCoupon.code : null,
                    'coupon_discount': couponDiscountTotal,
                    'campaign_name': activeCampaign ? activeCampaign.name : null,
                    'campaign_discount': campaignDiscountTotal,
                    'original_subtotal': baseSubtotal,
                    'upgrade_sale_id': upgradeData ? upgradeData.sale_id : null,
                    'is_upgrade': isUpgrade,
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
                    customer_city: city,
                    coupon_code: appliedCoupon ? appliedCoupon.code : null,
                    coupon_discount: couponDiscountTotal,
                    campaign_name: activeCampaign ? activeCampaign.name : null,
                    campaign_discount: campaignDiscountTotal,
                    original_subtotal: baseSubtotal,
                    upgrade_sale_id: upgradeData ? upgradeData.sale_id : null,
                    is_upgrade: isUpgrade
                })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('culqiLoaderBox').style.display = 'none';

                if (data.success) {
                    isCulqiInitialized = true;
                    currentCulqiOrderId = data.orderId || null;

                    // Si Culqi generó el código QR oficial, lo mostramos directamente en pantalla
                    if (data.qr) {
                        const qrImg = document.getElementById('culqiQrImage');
                        if (qrImg) qrImg.src = data.qr;

                        const amountTxt = document.getElementById('culqiQrAmountText');
                        if (amountTxt) amountTxt.textContent = data.amountFormatted || ('S/ ' + currentGrandTotal.toFixed(2));

                        if (data.payment_code) {
                            const cipBox = document.getElementById('culqiCipBox');
                            const cipCode = document.getElementById('culqiCipCode');
                            if (cipBox && cipCode) {
                                cipCode.textContent = data.payment_code;
                                cipBox.style.display = 'block';
                            }
                        }

                        document.getElementById('initCulqiPaymentSection').style.display = 'none';
                        document.getElementById('culqiQrDisplaySection').style.display = 'block';

                        // Iniciar monitoreo en tiempo real del pago QR
                        startCulqiOrderPolling(data.orderId);
                    } else if (typeof Culqi !== 'undefined') {
                        // Fallback a modal Culqi Checkout si no viene imagen QR directa
                        document.getElementById('initCulqiPaymentSection').style.display = 'block';
                        Culqi.publicKey = data.publicKey || '{{ $culqiPublicKey }}';
                        Culqi.settings({
                            title: 'ViveGo - ' + (eventData.title || 'Entradas'),
                            currency: 'PEN',
                            amount: data.amountCents || Math.round(currentGrandTotal * 100),
                            order: data.orderId || undefined
                        });
                        Culqi.options({
                            lang: 'es',
                            installments: false,
                            paymentMethods: { tarjeta: true, yape: true, billetera: true, pagoefectivo: true, cuotealo: false }
                        });
                        Culqi.open();
                    }
                } else {
                    document.getElementById('initCulqiPaymentSection').style.display = 'block';
                    errorBox.textContent = '⚠️ ' + (data.warning || data.message || 'No se pudo generar la orden de Culqi.');
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

        // Abre el modal nativo de Culqi exclusivamente para pagar con Tarjeta
        function openCulqiCardModalDirectly() {
            if (typeof Culqi === 'undefined') return;
            Culqi.publicKey = '{{ $culqiPublicKey }}';
            Culqi.settings({
                title: 'ViveGo - ' + (eventData.title || 'Entradas'),
                currency: 'PEN',
                amount: Math.round(currentGrandTotal * 100),
                order: currentCulqiOrderId || undefined
            });
            Culqi.options({
                lang: 'es',
                installments: false,
                paymentMethods: {
                    tarjeta: true,
                    yape: true,
                    billetera: false,
                    pagoefectivo: false,
                    cuotealo: false
                }
            });
            Culqi.open();
        }

        // Cancela la vista de QR y vuelve al selector
        function cancelCulqiQrView() {
            if (culqiPollingInterval) clearInterval(culqiPollingInterval);
            document.getElementById('culqiQrDisplaySection').style.display = 'none';
            document.getElementById('initCulqiPaymentSection').style.display = 'block';
        }

        // Copia el código CIP al portapapeles
        function copyCulqiCipCode() {
            const cip = document.getElementById('culqiCipCode')?.textContent;
            if (cip && cip !== '---') {
                navigator.clipboard.writeText(cip).then(() => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Código Copiado!',
                            text: 'Código CIP: ' + cip,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('Código CIP copiado: ' + cip);
                    }
                });
            }
        }

        // Verificación manual del estado del pago QR
        function verifyCulqiPaymentManual() {
            if (!currentCulqiOrderId) {
                alert('⚠️ No se ha generado una orden de pago activa.');
                return;
            }

            const btn = document.getElementById('btnVerifyCulqiManual');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '🔄 Verificando con Culqi...';
            }

            fetch("{{ route('web.checkout.culqi_order_status') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: currentCulqiOrderId })
            })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '⚡ Ya realicé el pago / Verificar ahora';
                }

                if (data.success && data.is_paid) {
                    if (culqiPollingInterval) clearInterval(culqiPollingInterval);
                    processCulqiComplete({ order_id: currentCulqiOrderId });
                } else {
                    const statusMsg = document.getElementById('culqiStatusMessage');
                    if (statusMsg) {
                        statusMsg.textContent = '⏳ Pago aún no detectado. Si ya pagaste en Yape/Plin, espera unos segundos mientras Culqi lo confirma.';
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Pago en Proceso',
                            text: 'Aún no se ha detectado la confirmación de tu pago. Si ya escaneaste el QR en Yape o Plin, se actualizará en breve.',
                            confirmButtonColor: '#FF5500'
                        });
                    }
                }
            })
            .catch(err => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '⚡ Ya realicé el pago / Verificar ahora';
                }
                console.error('[Culqi] Error manual verify:', err);
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
                    coupon_code: appliedCoupon ? appliedCoupon.code : null,
                    coupon_discount: couponDiscountTotal,
                    campaign_name: activeCampaign ? activeCampaign.name : null,
                    campaign_discount: campaignDiscountTotal,
                    original_subtotal: baseSubtotal,
                    upgrade_sale_id: upgradeData ? upgradeData.sale_id : null,
                    is_upgrade: isUpgrade,
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
