@extends('layouts.app')

@section('title', 'Pasarelas de Pago | Izipay & Culqi | Vive Go')

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN PRO MAX HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONFIGURACIÓN -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" class="dash-search-input" placeholder="Buscar opciones de pago...">
                    <span class="dash-kbd-shortcut">⌘K</span>
                </div>

                <div class="dash-top-actions">
                    <a href="{{ route('web.dashboard') }}" class="dash-icon-btn" title="Volver al Dashboard">
                        <span>📊</span>
                    </a>
                </div>
            </header>

            <div class="dash-container">
                <!-- SUCCESS ALERT -->
                @if(session('success'))
                    <div class="alert-custom alert-success" style="margin-bottom: 1rem;">
                        <div class="alert-icon-box">✓</div>
                        <div class="alert-content">
                            <h4>¡Configuración Guardada!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                <!-- CONTENEDOR DE ALERTAS AJAX DE TEST -->
                <div id="testResultAlert" style="display: none; margin-bottom: 1rem;"></div>

                <!-- NAVEGACIÓN POR PESTAÑAS PRINCIPALES (IZIPAY / CULQI) -->
                @php
                    $activeTab = request('tab', 'izipay');
                @endphp
                <div class="gateway-main-tabs-nav">
                    <button type="button" class="gateway-tab-btn {{ $activeTab === 'izipay' ? 'active' : '' }}" onclick="switchGatewayTab('izipay', this)">
                        <span class="tab-btn-icon">💳</span>
                        <div class="tab-btn-text">
                            <strong class="tab-title">Izipay Perú</strong>
                            <span class="tab-subtitle">
                                @if($izipay->is_active)
                                    <span class="tab-dot active">●</span> Activa
                                @else
                                    <span class="tab-dot inactive">○</span> Inactiva
                                @endif
                                • {{ $izipay->isSandbox() ? 'Sandbox' : 'Live' }}
                            </span>
                        </div>
                    </button>

                    <button type="button" class="gateway-tab-btn {{ $activeTab === 'culqi' ? 'active' : '' }}" onclick="switchGatewayTab('culqi', this)">
                        <span class="tab-btn-icon" style="display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('images/logo-yape.png') }}" alt="Yape" style="width: 24px; height: 24px; object-fit: contain; border-radius: 6px;">
                        </span>
                        <div class="tab-btn-text">
                            <strong class="tab-title">Culqi Perú</strong>
                            <span class="tab-subtitle">
                                @if($culqi->is_active)
                                    <span class="tab-dot active">●</span> Activa
                                @else
                                    <span class="tab-dot inactive">○</span> Inactiva
                                @endif
                                • QR, Yape & Tarjetas
                            </span>
                        </div>
                        <span class="badge-tab-highlight">Soporte QR 📱</span>
                    </button>
                </div>

                <!-- ========================================================================= -->
                <!-- PESTAÑA 1: IZIPAY PERÚ -->
                <!-- ========================================================================= -->
                <div id="tab-content-izipay" class="gateway-tab-content {{ $activeTab === 'izipay' ? 'active' : '' }}">
                    <!-- HEADER PAGE COMPACTO IZIPAY -->
                    <div class="settings-header-banner" style="margin-bottom: 1rem; padding: 1.25rem 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <span class="settings-tag" style="margin-bottom: 0.25rem;">💳 PASARELA OFICIAL #1</span>
                                <h1 class="settings-page-title" style="font-size: 1.4rem; margin: 0;">Configuración Oficial de Izipay Perú</h1>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if($izipay->is_active)
                                    <span class="badge-status-pill badge-active">● Activa</span>
                                @else
                                    <span class="badge-status-pill badge-inactive">○ Inactiva</span>
                                @endif

                                @if($izipay->isSandbox())
                                    <span class="badge-status-pill badge-sandbox">🧪 Sandbox (Pruebas)</span>
                                @else
                                    <span class="badge-status-pill badge-live">🚀 Producción (Live)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- FORMULARIO IZIPAY -->
                    <form action="{{ route('web.payment_methods.update_izipay') }}" method="POST" id="izipayConfigForm" class="settings-card-box payment-gateway-card">
                        @csrf

                        <!-- 1. BARRA DE ESTADO Y ENTORNO -->
                        <div class="compact-controls-bar">
                            <div class="switch-control-group">
                                <label class="switch-container">
                                    <input type="checkbox" name="is_active" value="1" {{ $izipay->is_active ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                                <span class="switch-label-text">Habilitar Izipay en la pasarela de pagos</span>
                            </div>

                            <div class="env-radios-group">
                                <label class="env-radio-btn {{ $izipay->mode === 'sandbox' ? 'selected' : '' }}">
                                    <input type="radio" name="mode" value="sandbox" {{ $izipay->mode === 'sandbox' ? 'checked' : '' }} onchange="updateModeUI(this)">
                                    <span>🧪 Modo Pruebas (Sandbox)</span>
                                </label>
                                <label class="env-radio-btn {{ $izipay->mode === 'production' ? 'selected' : '' }}">
                                    <input type="radio" name="mode" value="production" {{ $izipay->mode === 'production' ? 'checked' : '' }} onchange="updateModeUI(this)">
                                    <span>🚀 Modo Producción (Live)</span>
                                </label>
                            </div>
                        </div>

                        <!-- 2. GRID DE CREDENCIALES IZIPAY -->
                        <div class="compact-section-title">
                            <span>🔑</span> Credenciales de API REST (Back Office Izipay)
                        </div>

                        <div class="credentials-grid-4col">
                            <!-- Col 1: Usuario -->
                            <div class="form-group-custom">
                                <label for="izipay_username" class="form-label-compact">Usuario / Comercio <span class="required-star">*</span></label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">👤</span>
                                    <input type="text" id="izipay_username" name="username" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('username', $izipay->getCredential('username')) }}"
                                        placeholder="Ej: 87877354">
                                </div>
                            </div>

                            <!-- Col 2: Password -->
                            <div class="form-group-custom">
                                <label for="izipay_password" class="form-label-compact">Contraseña API REST <span class="required-star">*</span></label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🔒</span>
                                    <input type="password" id="izipay_password" name="password" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('password', $izipay->getCredential('password')) }}"
                                        placeholder="Contraseña REST">
                                    <button type="button" class="btn-toggle-secret" onclick="togglePasswordVisibility('izipay_password', this)" title="Mostrar/Ocultar">👁️</button>
                                </div>
                            </div>

                            <!-- Col 3: Public Key -->
                            <div class="form-group-custom">
                                <label for="izipay_public_key" class="form-label-compact">Clave Pública (JS) <span class="required-star">*</span></label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🌐</span>
                                    <input type="text" id="izipay_public_key" name="public_key" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('public_key', $izipay->getCredential('public_key')) }}"
                                        placeholder="87877354:publickey_...">
                                </div>
                            </div>

                            <!-- Col 4: HMAC-SHA256 -->
                            <div class="form-group-custom">
                                <label for="izipay_hmac_sha256" class="form-label-compact">Clave HMAC-SHA256 <span class="required-star">*</span></label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🛡️</span>
                                    <input type="password" id="izipay_hmac_sha256" name="hmac_sha256" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('hmac_sha256', $izipay->getCredential('hmac_sha256')) }}"
                                        placeholder="Clave HMAC-SHA256">
                                    <button type="button" class="btn-toggle-secret" onclick="togglePasswordVisibility('izipay_hmac_sha256', this)" title="Mostrar/Ocultar">👁️</button>
                                </div>
                            </div>
                        </div>

                        <!-- 3. ENDPOINT Y WEBHOOK IPN IZIPAY -->
                        <div class="endpoint-webhook-grid">
                            <div class="form-group-custom">
                                <label for="izipay_client_endpoint" class="form-label-compact">Endpoint del Servidor API</label>
                                <input type="url" id="izipay_client_endpoint" name="client_endpoint" class="form-input-custom input-compact"
                                    value="{{ old('client_endpoint', $izipay->getCredential('client_endpoint', 'https://api.micuentaweb.pe')) }}"
                                    placeholder="https://api.micuentaweb.pe">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-compact">URL de Notificación Instantánea (Webhook / IPN)</label>
                                <div class="webhook-copy-wrapper">
                                    <input type="text" id="ipnWebhookUrl" readonly value="{{ $ipnUrl }}" class="form-input-custom input-compact" style="color: #10B981; font-family: monospace;">
                                    <button type="button" class="btn btn-secondary btn-sm btn-copy-inline" onclick="copyWebhookUrl('ipnWebhookUrl', 'btnCopyWebhook')" id="btnCopyWebhook" title="Copiar URL">
                                        📋 Copiar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 4. MÉTODOS DE COBRO HABILITADOS IZIPAY -->
                        <div class="compact-section-title" style="margin-top: 1rem;">
                            <span>💳</span> Métodos de Cobro Habilitados en el Checkout
                        </div>

                        <div class="payment-methods-grid-4col">
                            <!-- Tarjetas -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">💳</span>
                                    <div>
                                        <strong class="toggle-name">Tarjetas</strong>
                                        <span class="toggle-desc">Crédito / Débito</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_cards" value="1" {{ $izipay->getSetting('enable_cards', true) ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <!-- QR & Yape / Plin -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">📱</span>
                                    <div>
                                        <strong class="toggle-name">QR Yape / Plin</strong>
                                        <span class="toggle-desc">Billeteras Digitales</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_qr_yape" value="1" {{ $izipay->getSetting('enable_qr_yape', true) ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <!-- PagoEfectivo -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">💵</span>
                                    <div>
                                        <strong class="toggle-name">PagoEfectivo</strong>
                                        <span class="toggle-desc">Banca & Agentes</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_pagoefectivo" value="1" {{ $izipay->getSetting('enable_pagoefectivo', true) ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <!-- Tokenización -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">🔄</span>
                                    <div>
                                        <strong class="toggle-name">Tokenización</strong>
                                        <span class="toggle-desc">Suscripciones Auto</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_recurring" value="1" {{ $izipay->getSetting('enable_recurring', true) ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- 5. BOTONES DE ACCIÓN IZIPAY -->
                        <div class="compact-action-buttons">
                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-weight: 800;">
                                💾 Guardar Configuración Izipay
                            </button>

                            <button type="button" class="btn btn-outline" id="btnTestConnection" onclick="testIzipayConnection()" style="padding: 0.75rem 1.5rem; font-weight: 700;">
                                ⚡ Probar Conexión con Izipay
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ========================================================================= -->
                <!-- PESTAÑA 2: CULQI PERÚ (QR, TARJETAS, YAPE, PAGOEFECTIVO) -->
                <!-- ========================================================================= -->
                <div id="tab-content-culqi" class="gateway-tab-content {{ $activeTab === 'culqi' ? 'active' : '' }}">
                    <!-- HEADER PAGE COMPACTO CULQI -->
                    <div class="settings-header-banner culqi-banner" style="margin-bottom: 1rem; padding: 1.25rem 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <span class="settings-tag culqi-tag" style="margin-bottom: 0.25rem;">🟧 PASARELA CULQI PERÚ (SDK culqi/culqi-php)</span>
                                <h1 class="settings-page-title" style="font-size: 1.4rem; margin: 0;">Configuración Oficial de Culqi Perú</h1>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if($culqi->is_active)
                                    <span class="badge-status-pill badge-active">● Activa</span>
                                @else
                                    <span class="badge-status-pill badge-inactive">○ Inactiva</span>
                                @endif

                                @if($culqi->isSandbox())
                                    <span class="badge-status-pill badge-sandbox">🧪 Sandbox (Pruebas)</span>
                                @else
                                    <span class="badge-status-pill badge-live">🚀 Producción (Live)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- FORMULARIO CULQI -->
                    <form action="{{ route('web.payment_methods.update_culqi') }}" method="POST" id="culqiConfigForm" class="settings-card-box payment-gateway-card culqi-card">
                        @csrf

                        <!-- 1. BARRA DE ESTADO Y ENTORNO -->
                        <div class="compact-controls-bar">
                            <div class="switch-control-group">
                                <label class="switch-container">
                                    <input type="checkbox" name="is_active" value="1" {{ $culqi->is_active ? 'checked' : '' }}>
                                    <span class="switch-slider switch-orange"></span>
                                </label>
                                <span class="switch-label-text">Habilitar Culqi en la pasarela de pagos</span>
                            </div>

                            <div class="env-radios-group">
                                <label class="env-radio-btn {{ $culqi->mode === 'sandbox' ? 'selected' : '' }}">
                                    <input type="radio" name="mode" value="sandbox" {{ $culqi->mode === 'sandbox' ? 'checked' : '' }} onchange="updateModeUI(this)">
                                    <span>🧪 Modo Pruebas (Integ-Panel)</span>
                                </label>
                                <label class="env-radio-btn {{ $culqi->mode === 'production' ? 'selected' : '' }}">
                                    <input type="radio" name="mode" value="production" {{ $culqi->mode === 'production' ? 'checked' : '' }} onchange="updateModeUI(this)">
                                    <span>🚀 Modo Producción (Live)</span>
                                </label>
                            </div>
                        </div>

                        <!-- 2. GRID DE CREDENCIALES CULQI -->
                        <div class="compact-section-title">
                            <span>🔑</span> Llaves de API & Encriptación (CulqiPanel > Desarrollo > API Keys)
                        </div>

                        <div class="credentials-grid-4col">
                            <!-- Col 1: Public Key -->
                            <div class="form-group-custom">
                                <label for="culqi_public_key" class="form-label-compact">Clave Pública (Public Key) <span class="required-star">*</span></label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🌐</span>
                                    <input type="text" id="culqi_public_key" name="public_key" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('public_key', $culqi->getCredential('public_key')) }}"
                                        placeholder="pk_test_... o pk_live_...">
                                </div>
                            </div>

                            <!-- Col 2: Secret Key -->
                            <div class="form-group-custom">
                                <label for="culqi_secret_key" class="form-label-compact">Clave Secreta (Secret Key) <span class="required-star">*</span></label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🔒</span>
                                    <input type="password" id="culqi_secret_key" name="secret_key" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('secret_key', $culqi->getCredential('secret_key')) }}"
                                        placeholder="sk_test_... o sk_live_...">
                                    <button type="button" class="btn-toggle-secret" onclick="togglePasswordVisibility('culqi_secret_key', this)" title="Mostrar/Ocultar">👁️</button>
                                </div>
                            </div>

                            <!-- Col 3: RSA Public Key -->
                            <div class="form-group-custom">
                                <label for="culqi_rsa_public_key" class="form-label-compact">Clave Pública RSA (Opcional)</label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🛡️</span>
                                    <input type="password" id="culqi_rsa_public_key" name="rsa_public_key" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('rsa_public_key', $culqi->getCredential('rsa_public_key')) }}"
                                        placeholder="-----BEGIN PUBLIC KEY-----...">
                                    <button type="button" class="btn-toggle-secret" onclick="togglePasswordVisibility('culqi_rsa_public_key', this)" title="Mostrar/Ocultar">👁️</button>
                                </div>
                            </div>

                            <!-- Col 4: RSA ID -->
                            <div class="form-group-custom">
                                <label for="culqi_rsa_id" class="form-label-compact">ID Llave RSA (Opcional)</label>
                                <div class="input-with-icon-wrapper">
                                    <span class="input-prefix-icon">🆔</span>
                                    <input type="text" id="culqi_rsa_id" name="rsa_id" class="form-input-custom input-compact input-has-icon"
                                        value="{{ old('rsa_id', $culqi->getCredential('rsa_id')) }}"
                                        placeholder="Ej: f4d89a...">
                                </div>
                            </div>
                        </div>

                        <!-- 3. ENDPOINT Y WEBHOOK IPN CULQI -->
                        <div class="endpoint-webhook-grid">
                            <div class="form-group-custom">
                                <label class="form-label-compact">Endpoint Base API Culqi</label>
                                <input type="text" readonly value="https://api.culqi.com/v2" class="form-input-custom input-compact" style="color: #94A3B8;">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-compact">URL de Webhook Culqi (Notificaciones IPN)</label>
                                <div class="webhook-copy-wrapper">
                                    <input type="text" id="culqiWebhookUrlInput" readonly value="{{ $culqiWebhookUrl }}" class="form-input-custom input-compact" style="color: #FF5500; font-family: monospace;">
                                    <button type="button" class="btn btn-secondary btn-sm btn-copy-inline" onclick="copyWebhookUrl('culqiWebhookUrlInput', 'btnCopyCulqiWebhook')" id="btnCopyCulqiWebhook" title="Copiar URL">
                                        📋 Copiar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 4. MÉTODOS DE COBRO HABILITADOS CULQI -->
                        <div class="compact-section-title" style="margin-top: 1rem;">
                            <span>📱</span> Métodos de Pago Habilitados en Culqi
                        </div>

                        <div class="payment-methods-grid-4col">
                            <!-- QR y Billeteras Digitales (Yape / Plin) -->
                            <div class="toggle-card-compact highlight-qr-card">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small" style="background: rgba(255, 85, 0, 0.2); color: #FF5500;">📱</span>
                                    <div>
                                        <strong class="toggle-name" style="color: #FF8844;">Pago con QR & Billeteras</strong>
                                        <span class="toggle-desc">Yape, Plin y Billeteras</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_qr_billeteras" value="1" {{ $culqi->getSetting('enable_qr_billeteras', true) ? 'checked' : '' }}>
                                    <span class="switch-slider switch-orange"></span>
                                </label>
                            </div>

                            <!-- Tarjetas Débito / Crédito -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">💳</span>
                                    <div>
                                        <strong class="toggle-name">Tarjetas Débito/Crédito</strong>
                                        <span class="toggle-desc">Visa, Mastercard, Amex</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_cards" value="1" {{ $culqi->getSetting('enable_cards', true) ? 'checked' : '' }}>
                                    <span class="switch-slider switch-orange"></span>
                                </label>
                            </div>

                            <!-- Yape Directo con OTP -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">⚡</span>
                                    <div>
                                        <strong class="toggle-name">Yape Directo</strong>
                                        <span class="toggle-desc">Código de Aprobación</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_yape" value="1" {{ $culqi->getSetting('enable_yape', true) ? 'checked' : '' }}>
                                    <span class="switch-slider switch-orange"></span>
                                </label>
                            </div>

                            <!-- PagoEfectivo (CIP) -->
                            <div class="toggle-card-compact">
                                <div class="toggle-card-info">
                                    <span class="toggle-icon-small">💵</span>
                                    <div>
                                        <strong class="toggle-name">PagoEfectivo (CIP)</strong>
                                        <span class="toggle-desc">Agentes y Banca Online</span>
                                    </div>
                                </div>
                                <label class="switch-container switch-sm">
                                    <input type="checkbox" name="enable_pagoefectivo" value="1" {{ $culqi->getSetting('enable_pagoefectivo', true) ? 'checked' : '' }}>
                                    <span class="switch-slider switch-orange"></span>
                                </label>
                            </div>
                        </div>

                        <!-- 5. BOTONES DE ACCIÓN CULQI -->
                        <div class="compact-action-buttons">
                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-weight: 800; background: linear-gradient(135deg, #FF5500, #E64A00);">
                                💾 Guardar Configuración Culqi
                            </button>

                            <button type="button" class="btn btn-outline" id="btnTestCulqiConnection" onclick="testCulqiConnection()" style="padding: 0.75rem 1.5rem; font-weight: 700; border-color: #FF5500; color: #FF5500;">
                                ⚡ Probar Conexión con Culqi
                            </button>
                        </div>
                    </form>

                    <!-- GUÍA PASO A PASO DEL SDK CULQI-PHP (OFICIAL GITHUB) -->
                    <div class="culqi-guide-box" style="margin-top: 1.5rem;">
                        <div class="culqi-guide-header">
                            <span style="font-size: 1.4rem;">📖</span>
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 900; color: #F8FAFC; margin: 0;">Guía Oficial de Integración Culqi PHP (GitHub: culqi/culqi-php)</h3>
                                <p style="font-size: 0.825rem; color: #94A3B8; margin: 0.15rem 0 0 0;">Información técnica y paso a paso para configurar pagos con QR, Tarjetas y Yape.</p>
                            </div>
                        </div>

                        <div class="culqi-guide-grid">
                            <div class="guide-step-card">
                                <div class="guide-step-badge">Paso 1</div>
                                <h4 class="guide-step-title">Obtener Llaves API</h4>
                                <p class="guide-step-desc">
                                    Ingresa a tu <a href="https://culqipanel.culqi.com/development/api-keys" target="_blank" style="color:#FF5500; text-decoration: underline; font-weight: bold;">CulqiPanel &gt; Desarrollo &gt; API Keys</a> y copia tu <code>pk_test_...</code> / <code>pk_live_...</code> y tu <code>sk_test_...</code> / <code>sk_live_...</code>.
                                </p>
                            </div>

                            <div class="guide-step-card">
                                <div class="guide-step-badge">Paso 2</div>
                                <h4 class="guide-step-title">¿Cómo Funciona el Pago con QR?</h4>
                                <p class="guide-step-desc">
                                    En Culqi, los pagos con QR y Billeteras Móviles utilizan el servicio de <strong>Órdenes (<code>$culqi-&gt;Orders-&gt;create()</code>)</strong>. ViveGo genera la orden automáticamente y despliega el código QR en pantalla para que el cliente lo escanee con <strong>Yape</strong> o <strong>Plin</strong>.
                                </p>
                            </div>

                            <div class="guide-step-card">
                                <div class="guide-step-badge">Paso 3</div>
                                <h4 class="guide-step-title">Tarjetas de Prueba</h4>
                                <p class="guide-step-desc">
                                    Para probar en entorno Sandbox usa cualquier tarjeta de prueba oficial de Culqi: Número <code>4111 1111 1111 1111</code>, CVV <code>123</code>, fecha de expiración futura (ej: <code>12/28</code>).
                                </p>
                            </div>

                            <div class="guide-step-card">
                                <div class="guide-step-badge">Paso 4</div>
                                <h4 class="guide-step-title">Webhook / Notificaciones</h4>
                                <p class="guide-step-desc">
                                    En tu <a href="https://culqipanel.culqi.com/development/webhooks" target="_blank" style="color:#FF5500; text-decoration: underline; font-weight: bold;">CulqiPanel &gt; Desarrollo &gt; Webhooks</a>, haz clic en <strong>+ Crear Webhook</strong> y pega la URL de Notificación: <code>{{ $culqiWebhookUrl }}</code>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
@endsection

@push('styles')
<style>
    /* NAVEGADOR DE PESTAÑAS PRINCIPAL */
    .gateway-main-tabs-nav {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .gateway-tab-btn {
        background: #14141E;
        border: 1.5px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 0.9rem 1.4rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        text-align: left;
        color: #94A3B8;
    }

    .gateway-tab-btn:hover {
        border-color: rgba(255, 85, 0, 0.4);
        background: #1A1A28;
    }

    .gateway-tab-btn.active {
        background: #1A1A2A;
        border-color: #FF5500;
        color: #FFFFFF;
        box-shadow: 0 4px 20px rgba(255, 85, 0, 0.2);
    }

    .tab-btn-icon {
        font-size: 1.6rem;
        line-height: 1;
    }

    .tab-title {
        font-size: 1rem;
        display: block;
        color: #F8FAFC;
        font-weight: 800;
    }

    .tab-subtitle {
        font-size: 0.75rem;
        color: #94A3B8;
        display: block;
        margin-top: 0.15rem;
    }

    .tab-dot.active {
        color: #10B981;
    }

    .tab-dot.inactive {
        color: #64748B;
    }

    .badge-tab-highlight {
        background: rgba(255, 85, 0, 0.15);
        color: #FF5500;
        border: 1px solid rgba(255, 85, 0, 0.3);
        font-size: 0.725rem;
        font-weight: 800;
        padding: 0.2rem 0.55rem;
        border-radius: 20px;
        margin-left: 0.5rem;
    }

    .gateway-tab-content {
        display: none;
    }

    .gateway-tab-content.active {
        display: block;
        animation: fadeInTab 0.25s ease-in-out;
    }

    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ESTILOS DE PASARELA */
    .payment-gateway-card {
        background: #14141E;
        border: 1px solid rgba(255, 85, 0, 0.25);
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        position: relative;
        overflow: hidden;
        padding: 1.5rem !important;
    }

    .payment-gateway-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #FF0055, #FF5500, #00D2C4);
    }

    .culqi-card::before {
        background: linear-gradient(90deg, #FF5500, #FF8800, #FFAA00) !important;
    }

    .badge-status-pill {
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.15);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-inactive {
        background: rgba(148, 163, 184, 0.15);
        color: #94A3B8;
        border: 1px solid rgba(148, 163, 184, 0.3);
    }

    .badge-sandbox {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .badge-live {
        background: rgba(255, 85, 0, 0.15);
        color: #FF5500;
        border: 1px solid rgba(255, 85, 0, 0.3);
    }

    .compact-controls-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.02);
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        margin-bottom: 1.25rem;
    }

    .switch-control-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .switch-label-text {
        font-weight: 700;
        font-size: 0.9rem;
        color: #F8FAFC;
    }

    .switch-container {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .switch-container.switch-sm {
        width: 38px;
        height: 20px;
    }

    .switch-container input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #334155;
        transition: .3s;
        border-radius: 34px;
    }

    .switch-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    .switch-container.switch-sm .switch-slider:before {
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
    }

    input:checked + .switch-slider {
        background-color: #10B981;
    }

    input:checked + .switch-slider.switch-orange {
        background-color: #FF5500;
    }

    input:checked + .switch-slider:before {
        transform: translateX(20px);
    }

    .switch-container.switch-sm input:checked + .switch-slider:before {
        transform: translateX(18px);
    }

    .env-radios-group {
        display: flex;
        gap: 0.5rem;
    }

    .env-radio-btn {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.4rem 0.85rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #CBD5E1;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .env-radio-btn input {
        display: none;
    }

    .env-radio-btn.selected,
    .env-radio-btn:has(input:checked) {
        background: rgba(255, 85, 0, 0.15);
        border-color: #FF5500;
        color: #FFFFFF;
    }

    .compact-section-title {
        font-size: 0.875rem;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.75rem;
    }

    /* GRID DE 4 COLUMNAS PARA CREDENCIALES */
    .credentials-grid-4col {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .form-label-compact {
        font-size: 0.8rem;
        font-weight: 700;
        color: #E2E8F0;
        margin-bottom: 0.35rem;
        display: block;
    }

    .input-compact {
        padding-top: 0.55rem !important;
        padding-bottom: 0.55rem !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
    }

    .input-with-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-prefix-icon {
        position: absolute;
        left: 0.75rem;
        font-size: 0.9rem;
        pointer-events: none;
        color: #94A3B8;
    }

    .form-input-custom.input-has-icon {
        padding-left: 2.35rem !important;
        padding-right: 2.2rem !important;
    }

    .btn-toggle-secret {
        position: absolute;
        right: 0.5rem;
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        padding: 0.2rem;
        border-radius: 4px;
    }

    /* ENDPOINT Y WEBHOOK IPN */
    .endpoint-webhook-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .webhook-copy-wrapper {
        display: flex;
        gap: 0.4rem;
    }

    .btn-copy-inline {
        padding: 0 0.85rem !important;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        white-space: nowrap;
        border-radius: 10px !important;
    }

    /* MÉTODOS DE COBRO EN 4 COLUMNAS */
    .payment-methods-grid-4col {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .toggle-card-compact {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 0.75rem 0.85rem;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        transition: border-color 0.2s;
    }

    .toggle-card-compact:hover {
        border-color: rgba(255, 85, 0, 0.3);
    }

    .highlight-qr-card {
        background: rgba(255, 85, 0, 0.05);
        border-color: rgba(255, 85, 0, 0.3);
    }

    .toggle-card-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .toggle-icon-small {
        font-size: 1.25rem;
        background: rgba(255, 255, 255, 0.06);
        padding: 0.35rem;
        border-radius: 8px;
        line-height: 1;
        flex-shrink: 0;
    }

    .toggle-name {
        font-size: 0.825rem;
        color: #F8FAFC;
        display: block;
        line-height: 1.2;
    }

    .toggle-desc {
        font-size: 0.725rem;
        color: #94A3B8;
        display: block;
        line-height: 1.1;
    }

    /* BOTONES DE ACCIÓN */
    .compact-action-buttons {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid rgba(255, 85, 0, 0.5);
        color: #FF5500;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-outline:hover {
        background: rgba(255, 85, 0, 0.15);
        border-color: #FF5500;
        color: #FFFFFF;
    }

    /* GUÍA STEP-BY-STEP CULQI */
    .culqi-guide-box {
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 1.5rem;
    }

    .culqi-guide-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .culqi-guide-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .guide-step-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 1rem;
    }

    .guide-step-badge {
        font-size: 0.7rem;
        font-weight: 900;
        background: rgba(255, 85, 0, 0.2);
        color: #FF5500;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .guide-step-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: #F8FAFC;
        margin: 0 0 0.4rem 0;
    }

    .guide-step-desc {
        font-size: 0.775rem;
        color: #94A3B8;
        line-height: 1.4;
        margin: 0;
    }

    .guide-step-desc code {
        background: rgba(0, 0, 0, 0.4);
        color: #10B981;
        padding: 0.1rem 0.3rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.75rem;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .credentials-grid-4col,
        .payment-methods-grid-4col,
        .culqi-guide-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .endpoint-webhook-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .credentials-grid-4col,
        .payment-methods-grid-4col,
        .culqi-guide-grid {
            grid-template-columns: 1fr;
        }
        .compact-controls-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        .gateway-tab-btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Cambiar entre Pestaña Izipay y Culqi
    function switchGatewayTab(tabKey, btn) {
        document.querySelectorAll('.gateway-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.gateway-tab-content').forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        const content = document.getElementById('tab-content-' + tabKey);
        if (content) {
            content.classList.add('active');
        }

        // Actualizar URL sin recargar
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabKey);
        window.history.replaceState({}, '', url);
    }

    // Mostrar / Ocultar contraseñas y llaves secretas
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
        }
    }

    // Actualizar estilo visual del radio selector de modo
    function updateModeUI(radio) {
        radio.closest('.compact-controls-bar').querySelectorAll('.env-radio-btn').forEach(btn => btn.classList.remove('selected'));
        if (radio.checked) {
            radio.closest('.env-radio-btn').classList.add('selected');
        }
    }

    // Copiar URL de Webhook al portapapeles
    function copyWebhookUrl(inputId, btnId) {
        const input = document.getElementById(inputId);
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);

        const btn = document.getElementById(btnId);
        const origText = btn.innerHTML;
        btn.innerHTML = '✅ Copiado';
        setTimeout(() => {
            btn.innerHTML = origText;
        }, 2000);
    }

    // Probar Conexión con Izipay vía AJAX
    function testIzipayConnection() {
        const btn = document.getElementById('btnTestConnection');
        const alertBox = document.getElementById('testResultAlert');
        const username = document.getElementById('izipay_username').value;
        const password = document.getElementById('izipay_password').value;
        const endpoint = document.getElementById('izipay_client_endpoint').value;

        btn.disabled = true;
        const origText = btn.innerHTML;
        btn.innerHTML = '⏳ Conectando...';

        fetch("{{ route('web.payment_methods.test_izipay') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                username: username,
                password: password,
                client_endpoint: endpoint
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origText;
            alertBox.style.display = 'block';

            if (data.success) {
                alertBox.className = 'alert-custom alert-success';
                alertBox.style.cssText = 'margin-bottom: 1rem; display: flex;';
                alertBox.innerHTML = `
                    <div class="alert-icon-box">✓</div>
                    <div class="alert-content">
                        <h4>¡Conexión Exitosa con Izipay!</h4>
                        <p>${data.message} Modo detectado: <strong>${data.mode || 'Sandbox'}</strong>.</p>
                    </div>
                    <button class="alert-close-btn" onclick="this.parentElement.style.display='none'">✕</button>
                `;
            } else {
                alertBox.className = 'alert-custom';
                alertBox.style.cssText = 'background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; padding: 0.85rem 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;';
                alertBox.innerHTML = `
                    <div class="alert-icon-box" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">✕</div>
                    <div class="alert-content" style="flex: 1;">
                        <h4 style="margin: 0; color: #EF4444; font-weight: 800; font-size: 0.95rem;">Error al conectar con Izipay</h4>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem;">${data.message}</p>
                    </div>
                    <button class="alert-close-btn" style="background: none; border: none; color: #FFF; font-size: 1.1rem; cursor: pointer;" onclick="this.parentElement.style.display='none'">✕</button>
                `;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = origText;
            alertBox.style.display = 'block';
            alertBox.className = 'alert-custom';
            alertBox.innerHTML = `
                <div class="alert-icon-box" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">✕</div>
                <div class="alert-content" style="flex: 1;">
                    <h4 style="margin: 0; color: #EF4444; font-weight: 800; font-size: 0.95rem;">Error de comunicación</h4>
                    <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem;">${err.message}</p>
                </div>
                <button class="alert-close-btn" style="background: none; border: none; color: #FFF; font-size: 1.1rem; cursor: pointer;" onclick="this.parentElement.style.display='none'">✕</button>
            `;
        });
    }

    // Probar Conexión con Culqi vía AJAX
    function testCulqiConnection() {
        const btn = document.getElementById('btnTestCulqiConnection');
        const alertBox = document.getElementById('testResultAlert');
        const pubKey = document.getElementById('culqi_public_key').value;
        const secKey = document.getElementById('culqi_secret_key').value;

        btn.disabled = true;
        const origText = btn.innerHTML;
        btn.innerHTML = '⏳ Conectando con Culqi...';

        fetch("{{ route('web.payment_methods.test_culqi') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                public_key: pubKey,
                secret_key: secKey
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origText;
            alertBox.style.display = 'block';

            if (data.success) {
                alertBox.className = 'alert-custom alert-success';
                alertBox.style.cssText = 'margin-bottom: 1rem; display: flex;';
                alertBox.innerHTML = `
                    <div class="alert-icon-box">✓</div>
                    <div class="alert-content">
                        <h4>¡Conexión Exitosa con Culqi!</h4>
                        <p>${data.message}</p>
                    </div>
                    <button class="alert-close-btn" onclick="this.parentElement.style.display='none'">✕</button>
                `;
            } else {
                alertBox.className = 'alert-custom';
                alertBox.style.cssText = 'background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; padding: 0.85rem 1rem; border-radius: 12px; display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;';
                alertBox.innerHTML = `
                    <div class="alert-icon-box" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">✕</div>
                    <div class="alert-content" style="flex: 1;">
                        <h4 style="margin: 0; color: #EF4444; font-weight: 800; font-size: 0.95rem;">Error al conectar con Culqi</h4>
                        <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem;">${data.message}</p>
                    </div>
                    <button class="alert-close-btn" style="background: none; border: none; color: #FFF; font-size: 1.1rem; cursor: pointer;" onclick="this.parentElement.style.display='none'">✕</button>
                `;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = origText;
            alertBox.style.display = 'block';
            alertBox.className = 'alert-custom';
            alertBox.innerHTML = `
                <div class="alert-icon-box" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">✕</div>
                <div class="alert-content" style="flex: 1;">
                    <h4 style="margin: 0; color: #EF4444; font-weight: 800; font-size: 0.95rem;">Error de comunicación</h4>
                    <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem;">${err.message}</p>
                </div>
                <button class="alert-close-btn" style="background: none; border: none; color: #FFF; font-size: 1.1rem; cursor: pointer;" onclick="this.parentElement.style.display='none'">✕</button>
            `;
        });
    }
</script>
@endpush
