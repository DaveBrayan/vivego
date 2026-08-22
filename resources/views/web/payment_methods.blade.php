@extends('layouts.app')

@section('title', 'Métodos de Pago & Izipay | Vive Go')

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

                <!-- HEADER PAGE COMPACTO -->
                <div class="settings-header-banner" style="margin-bottom: 1rem; padding: 1.25rem 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 0.75rem;">
                        <div>
                            <span class="settings-tag" style="margin-bottom: 0.25rem;">💳 PASARELAS DE PAGO & CHECKOUT</span>
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

                <!-- FORMULARIO COMPACTO EN COLUMNAS -->
                <form action="{{ route('web.payment_methods.update_izipay') }}" method="POST" id="izipayConfigForm" class="settings-card-box payment-gateway-card">
                    @csrf

                    <!-- 1. BARRA DE ESTADO Y ENTORNO (HORIZONTAL COMPACTA) -->
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

                    <!-- 2. GRID DE CREDENCIALES (4 COLUMNAS COMPACTAS) -->
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

                    <!-- 3. ENDPOINT Y WEBHOOK IPN (2 COLUMNAS COMPACTAS) -->
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
                                <button type="button" class="btn btn-secondary btn-sm btn-copy-inline" onclick="copyWebhookUrl()" id="btnCopyWebhook" title="Copiar URL">
                                    📋 Copiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 4. MÉTODOS DE COBRO HABILITADOS (4 COLUMNAS EN 1 FILA) -->
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

                        <!-- Tokenización / Suscripciones -->
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

                    <!-- 5. BOTONES DE ACCIÓN (COMPACTOS Y ALINEADOS) -->
                    <div class="compact-action-buttons">
                        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-weight: 800;">
                            💾 Guardar Configuración
                        </button>

                        <button type="button" class="btn btn-outline" id="btnTestConnection" onclick="testIzipayConnection()" style="padding: 0.75rem 1.5rem; font-weight: 700;">
                            ⚡ Probar Conexión con Izipay
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
@endsection

@push('styles')
<style>
    /* ESTILOS ULTRA COMPACTOS Y EN COLUMNAS PARA MÉTODOS DE PAGO */
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

    /* ENDPOINT Y WEBHOOK IPN (2 COLUMNAS) */
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

    /* MÉTODOS DE COBRO EN 4 COLUMNAS (1 FILA) */
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

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .credentials-grid-4col,
        .payment-methods-grid-4col {
            grid-template-columns: repeat(2, 1fr);
        }
        .endpoint-webhook-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .credentials-grid-4col,
        .payment-methods-grid-4col {
            grid-template-columns: 1fr;
        }
        .compact-controls-bar {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@push('scripts')
<script>
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
        document.querySelectorAll('.env-radio-btn').forEach(btn => btn.classList.remove('selected'));
        if (radio.checked) {
            radio.closest('.env-radio-btn').classList.add('selected');
        }
    }

    // Copiar URL de Webhook al portapapeles
    function copyWebhookUrl() {
        const input = document.getElementById('ipnWebhookUrl');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);

        const btn = document.getElementById('btnCopyWebhook');
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
</script>
@endpush
