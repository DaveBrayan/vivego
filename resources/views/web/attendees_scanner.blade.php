@extends('layouts.app')

@section('title', 'Scanner QR & Control de Acceso: ' . $event->title . ' | Vive Go')

@push('styles')
<style>
    .scanner-terminal-card {
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 1.75rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        position: relative;
        overflow: hidden;
    }
    .scanner-viewfinder-box {
        width: 100%;
        max-width: 480px;
        min-height: 280px;
        margin: 0 auto;
        background: #0A0A10;
        border: 2px dashed rgba(255, 85, 0, 0.5);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 25px rgba(255, 85, 0, 0.1);
    }
    .scanner-laser-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #FF5500, #00F0FF, #FF5500, transparent);
        box-shadow: 0 0 15px #FF5500;
        animation: scannerLaser 2.2s infinite ease-in-out;
        z-index: 10;
        pointer-events: none;
    }
    @keyframes scannerLaser {
        0% { top: 5%; opacity: 0.2; }
        50% { top: 90%; opacity: 1; }
        100% { top: 5%; opacity: 0.2; }
    }
    .scan-result-card {
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scan-result-idle {
        background: rgba(255, 255, 255, 0.03);
        border: 1.5px solid rgba(255, 255, 255, 0.1);
    }
    .scan-result-granted {
        background: rgba(16, 185, 129, 0.12);
        border: 2px solid #10B981;
        box-shadow: 0 0 30px rgba(16, 185, 129, 0.25);
    }
    .scan-result-denied {
        background: rgba(239, 68, 68, 0.12);
        border: 2px solid #EF4444;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.25);
    }
    .scan-result-warning {
        background: rgba(245, 158, 11, 0.12);
        border: 2px solid #F59E0B;
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.25);
    }
    .pos-zone-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.25s ease;
    }
    .pos-zone-card:hover {
        border-color: rgba(255, 85, 0, 0.3);
        background: rgba(255, 255, 255, 0.05);
    }
    .row-highlight-new {
        animation: rowFlash 2.5s ease;
    }
    @keyframes rowFlash {
        0% { background: rgba(16, 185, 129, 0.35); }
        100% { background: transparent; }
    }
</style>
<!-- html5-qrcode library for real camera QR scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar en historial de ingresos...">
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
                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">📲 TERMINAL DE VALIDACIÓN QR EN VIVO</span>
                        <h1 class="settings-page-title">{{ $event->title }}</h1>
                        <p class="settings-page-subtitle">
                            📍 {{ $event->venue_name ?? 'Local Principal' }} &nbsp;|&nbsp; 🗓️ {{ $event->event_date }} {{ $event->event_time }}
                        </p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                        <a href="{{ route('web.attendees') }}" class="btn" style="background: rgba(255, 85, 0, 0.18); border: 1.5px solid #FF5500; color: #FFFFFF; font-weight: 800; padding: 0.75rem 1.4rem; font-size: 0.9rem; text-decoration: none; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.25); transition: all 0.2s ease;">
                            <span>⬅️</span>
                            <span>Volver a Asistentes</span>
                        </a>
                    </div>
                </div>

                <!-- STOCK Y ASISTENCIA POR ZONA / SECTOR -->
                <div class="settings-card-box" style="margin-bottom: 1.5rem;">
                    <div class="settings-card-header" style="flex-wrap: wrap; gap: 1rem; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">📊</div>
                            <div>
                                <h3 class="card-header-title">Ocupación y Asistencia por Sectores</h3>
                                <p class="card-header-subtitle">Monitorea el flujo de personas en cada zona del recinto en tiempo real.</p>
                            </div>
                        </div>
                    </div>



                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;" id="zonesAttendanceContainer">
                        @foreach($zonesAttendance as $za)
                            <div class="pos-zone-card" data-zone-name="{{ $za['name'] }}">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <div>
                                        <h4 style="font-size: 1rem; font-weight: 800; color: #FFFFFF; margin: 0 0 0.2rem 0;">{{ $za['name'] }}</h4>
                                        <span style="font-size: 0.85rem; font-weight: 700; color: #94A3B8;">S/ {{ number_format($za['price'], 2) }}</span>
                                    </div>
                                    <span class="dash-badge-custom badge-cyan" style="font-size: 0.75rem; font-weight: 800;">
                                        {{ $za['rate'] }}% ingresaron
                                    </span>
                                </div>

                                <div style="margin-top: 0.75rem;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.775rem; font-weight: 700; margin-bottom: 0.35rem;">
                                        <span style="color: #94A3B8;">Ingresaron: <strong style="color: #10B981;" class="zone-checked-count">{{ $za['checked_in'] }}</strong> / {{ $za['issued'] }}</span>
                                        <span style="color: #F59E0B;">Faltan: <strong class="zone-pending-count">{{ $za['pending'] }}</strong></span>
                                    </div>
                                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden;">
                                        <div class="zone-progress-bar" style="height: 100%; width: {{ $za['rate'] }}%; background: linear-gradient(90deg, #10B981, #00F0FF); border-radius: 10px; transition: width 0.4s ease;"></div>
                                    </div>

                                    <!-- Campos Virtual vs Físico en tiempo real por zona -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; margin-top: 0.85rem; padding-top: 0.75rem; border-top: 1px dashed rgba(255,255,255,0.12);">
                                        <!-- Campo Virtual -->
                                        <div style="background: rgba(0, 240, 255, 0.05); border: 1px solid rgba(0, 240, 255, 0.25); border-radius: 10px; padding: 0.5rem 0.65rem;">
                                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                                                <span style="font-size: 0.72rem; font-weight: 900; color: #00F0FF; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    <span>🌐</span> <span>VIRTUAL</span>
                                                </span>
                                                <span style="font-size: 0.68rem; color: #94A3B8; font-weight: 700;">
                                                    Hay: <strong style="color: #FFFFFF;" class="zone-digital-issued">{{ $za['digital_issued'] ?? 0 }}</strong>
                                                </span>
                                            </div>
                                            <div style="font-size: 0.72rem; color: #94A3B8; font-weight: 700; display: flex; align-items: baseline; justify-content: space-between;">
                                                <span>Van entrando:</span>
                                                <strong style="color: #10B981; font-size: 0.92rem;" class="zone-digital-count">{{ $za['digital_checked'] ?? 0 }}</strong>
                                            </div>
                                        </div>

                                        <!-- Campo Físico -->
                                        <div style="background: rgba(255, 85, 0, 0.05); border: 1px solid rgba(255, 85, 0, 0.25); border-radius: 10px; padding: 0.5rem 0.65rem;">
                                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                                                <span style="font-size: 0.72rem; font-weight: 900; color: #FF7733; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    <span>🎟️</span> <span>FÍSICO</span>
                                                </span>
                                                <span style="font-size: 0.68rem; color: #94A3B8; font-weight: 700;">
                                                    Hay: <strong style="color: #FFFFFF;" class="zone-physical-issued">{{ $za['physical_issued'] ?? 0 }}</strong>
                                                </span>
                                            </div>
                                            <div style="font-size: 0.72rem; color: #94A3B8; font-weight: 700; display: flex; align-items: baseline; justify-content: space-between;">
                                                <span>Van entrando:</span>
                                                <strong style="color: #10B981; font-size: 0.92rem;" class="zone-physical-count">{{ $za['physical_checked'] ?? 0 }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- KPI CARDS COMPACTOS DE CONTROL DE ACCESO EN VIVO -->
                <div class="dash-stats-grid" style="margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.9rem;">
                    <div class="dash-stat-card" style="border: 1px solid rgba(0, 240, 255, 0.25); background: rgba(0, 240, 255, 0.04); padding: 0.85rem 1.15rem; border-radius: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Boletos en Sistema</span>
                            <span style="font-size: 1.1rem;">🎟️</span>
                        </div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #FFFFFF;" id="kpiTicketsIssued">{{ number_format($metrics['tickets_issued']) }}</div>
                        <span style="font-size: 0.7rem; color: #00F0FF;">Emitidos (PDF + Taquilla)</span>
                    </div>

                    <div class="dash-stat-card" style="border: 1px solid rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.05); padding: 0.85rem 1.15rem; border-radius: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Ingresados / Validados</span>
                            <span style="font-size: 1.1rem;">✅</span>
                        </div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #10B981;" id="kpiCheckedIn">{{ number_format($metrics['checked_in_count']) }}</div>
                        <div style="display: flex; gap: 0.6rem; margin-top: 0.25rem; font-size: 0.72rem; font-weight: 800;">
                            <span style="color: #00F0FF;">📱 Dig: <strong id="kpiDigitalChecked">{{ number_format($metrics['digital_checked'] ?? 0) }}</strong></span>
                            <span style="color: #FF7733;">🎟️ Fís: <strong id="kpiPhysicalChecked">{{ number_format($metrics['physical_checked'] ?? 0) }}</strong></span>
                        </div>
                    </div>

                    <div class="dash-stat-card" style="border: 1px solid rgba(245, 158, 11, 0.25); background: rgba(245, 158, 11, 0.04); padding: 0.85rem 1.15rem; border-radius: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Pendientes de Ingreso</span>
                            <span style="font-size: 1.1rem;">⏳</span>
                        </div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #F59E0B;" id="kpiPending">{{ number_format($metrics['pending_count']) }}</div>
                        <span style="font-size: 0.7rem; color: #F59E0B;">Por ingresar al local</span>
                    </div>

                    <div class="dash-stat-card" style="border: 1px solid rgba(255, 85, 0, 0.25); background: rgba(255, 85, 0, 0.04); padding: 0.85rem 1.15rem; border-radius: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                            <span style="font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">% Asistencia en Vivo</span>
                            <span style="font-size: 1.1rem;">📊</span>
                        </div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: var(--color-primary-orange);" id="kpiAttendanceRate">{{ $metrics['attendance_rate'] }}%</div>
                        <span style="font-size: 0.7rem; color: #94A3B8;">Ocupación del aforo</span>
                    </div>
                </div>

                <!-- HISTORIAL DE INGRESOS EN VIVO -->
                <div class="settings-card-box">
                    <div class="settings-card-header" style="flex-wrap: wrap; gap: 1rem; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">📜</div>
                            <div>
                                <h3 class="card-header-title">Registro de Accesos</h3>
                                <p class="card-header-subtitle">Feed de entradas validadas con hora exacta y punto de control.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                            <button type="button" id="btnManualRefresh" onclick="manualRefreshFeed()" style="background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-weight: 900; font-size: 0.85rem; padding: 0.6rem 1.3rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); cursor: pointer; transition: all 0.2s ease;">
                                <span id="refreshIcon" style="font-size: 1.05rem;">🔄</span>
                                <span>Actualizar Asistencias</span>
                            </button>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="checkinsTable">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Código de Boleto</th>
                                    <th>Sector / Zona</th>
                                    <th>Asistente / Titular</th>
                                    <th>DNI</th>
                                    <th>Hora de Ingreso</th>
                                    <th>Punto de Control</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="checkinsTableBody">
                                @forelse($recentCheckins as $idx => $chk)
                                    <tr class="checkin-row-item" id="checkinRow_{{ $chk->id }}">
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $idx + 1) }}</span>
                                        </td>
                                        <td>
                                            <span style="font-family: monospace; font-weight: 800; color: #FFFFFF; font-size: 0.9rem;">
                                                {{ $chk->ticket_code }}
                                            </span>
                                            <small style="display: block; font-family: monospace; color: #FF7733; font-size: 0.75rem; font-weight: 800;">
                                                🔑 {{ $chk->validation_hash ?: ('VG' . strtoupper(substr(md5($chk->id), 0, 8))) }}
                                            </small>
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                                                <span class="dash-badge-custom badge-green" style="font-size: 0.78rem; font-weight: 800;">
                                                    {{ $chk->zone_name }}
                                                </span>
                                                @php
                                                    $isDig = in_array($chk->ticket_type, ['digital', 'cortesia_digital']);
                                                @endphp
                                                @if($isDig)
                                                    <span class="dash-badge-custom badge-cyan" style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.55rem; letter-spacing: 0.3px;">
                                                        🌐 Virtual
                                                    </span>
                                                @else
                                                    <span class="dash-badge-custom badge-orange" style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.55rem; letter-spacing: 0.3px;">
                                                        🎟️ Físico
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong style="color: #FFFFFF;">{{ $chk->buyer_name }}</strong>
                                        </td>
                                        <td>
                                            <span style="color: #94A3B8; font-family: monospace;">{{ $chk->buyer_dni }}</span>
                                        </td>
                                        <td>
                                            <span style="color: #00F0FF; font-weight: 700;">
                                                {{ $chk->checked_in_at ? $chk->checked_in_at->format('h:i:s A') : '-' }}
                                            </span>
                                            <small style="display: block; color: #64748B; font-size: 0.7rem;">{{ $chk->checked_in_at ? $chk->checked_in_at->format('d/m/Y') : '' }}</small>
                                        </td>
                                        <td>
                                            <span style="color: #E2E8F0; font-size: 0.85rem;">{{ $chk->scanned_by ?: 'Puerta Principal' }}</span>
                                        </td>
                                        <td>
                                            <span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">
                                                ✓ Ingresado
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <button type="button" 
                                                    onclick="resetCheckin({{ $chk->id }}, '{{ $chk->ticket_code }}')" 
                                                    class="btn btn-sm"
                                                    title="Eliminar escaneo y permitir escanear de nuevo"
                                                    style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.35); padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 800; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.35rem;"
                                                    onmouseenter="this.style.background='#EF4444'; this.style.color='#FFFFFF';"
                                                    onmouseleave="this.style.background='rgba(239, 68, 68, 0.15)'; this.style.color='#EF4444';">
                                                <span>🗑️</span> <span>Anular Escaneo</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyCheckinsRow">
                                        <td colspan="9" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
                                            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎫</div>
                                            <strong>Aún no se han registrado ingresos para este evento.</strong>
                                            <p style="margin: 0.25rem 0 0 0; font-size: 0.85rem;">Escanea el primer código QR o ingresa un número de boleto para comenzar.</p>
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const eventId = {{ $event->id }};
        const eventTitle = "{{ addslashes($event->title) }}";
        const verifyUrl = "{{ route('web.attendees.verify_qr', $event->id) }}";
        const csrfToken = "{{ csrf_token() }}";

        let isProcessingScan = false;

        // Manejador del Formulario de Búsqueda / Entrada Manual
        function handleManualScan(e) {
            e.preventDefault();
            const input = document.getElementById('manualQrInput');
            if (!input) return;
            const val = input.value.trim();
            if (!val) return;

            processTicketScan(val);
            input.value = '';
            input.focus();
        }

        // Enviar código o número de boleto al servidor para validación
        function processTicketScan(qrPayload) {
            if (isProcessingScan) return;
            isProcessingScan = true;

            const deviceName = document.getElementById('deviceControlName')?.value || 'Puerta Principal';

            fetch(verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    qr_payload: qrPayload,
                    device_name: deviceName
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (body.status === 'granted') {
                    const isDig = body.ticket?.is_digital || ['digital', 'cortesia_digital'].includes(body.ticket?.ticket_type);
                    const typeLabel = isDig ? '🌐 BOLETO VIRTUAL' : '🎟️ BOLETO FÍSICO';
                    const typeBadgeBg = isDig ? 'rgba(0, 240, 255, 0.15)' : 'rgba(255, 85, 0, 0.15)';
                    const typeBadgeBorder = isDig ? '#00F0FF' : '#FF7733';
                    const typeBadgeColor = isDig ? '#00F0FF' : '#FF7733';

                    Swal.fire({
                        icon: 'success',
                        title: '✓ ¡Acceso Permitido!',
                        html: `
                            <div style="font-size: 0.95rem; margin-top: 0.5rem;">
                                <div style="display: inline-block; margin-bottom: 0.45rem; padding: 0.25rem 0.75rem; border-radius: 8px; background: ${typeBadgeBg}; border: 1px solid ${typeBadgeBorder}; color: ${typeBadgeColor}; font-weight: 900; font-size: 0.8rem; letter-spacing: 0.4px;">
                                    ${typeLabel}
                                </div>
                                <p style="color: #10B981; font-weight: 800; margin-bottom: 0.25rem;">${body.ticket?.zone_name || 'Zona General'}</p>
                                <p style="color: #FFFFFF; font-weight: 700; margin-bottom: 0.25rem;">Boleto: ${body.ticket?.ticket_code || qrPayload}</p>
                                <p style="color: #94A3B8; font-size: 0.85rem;">Titular: ${body.ticket?.buyer_name || 'Asistente'}</p>
                            </div>
                        `,
                        timer: 3500,
                        timerProgressBar: true,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });

                    if (body.ticket) appendCheckinRow(body.ticket);
                    if (body.metrics) updateKpis(body.metrics);
                    if (body.zones) updateZones(body.zones);
                } else if (body.status === 'upgraded_void') {
                    Swal.fire({
                        icon: 'warning',
                        title: '🚫 Boleto Anulado por Mejora',
                        html: `
                            <div style="font-size: 0.95rem; margin-top: 0.5rem; text-align: left; background: rgba(239, 68, 68, 0.1); padding: 0.85rem; border-radius: 10px; border: 1px solid rgba(239, 68, 68, 0.3);">
                                <p style="color: #EF4444; font-weight: 800; margin-bottom: 0.3rem;">⚠️ Este boleto fue ANULADO porque el usuario realizó un Upgrade.</p>
                                <p style="color: #FFFFFF; font-size: 0.85rem; margin-bottom: 0.2rem;">Zona anterior: <strong>${body.ticket?.zone_name || '-'}</strong></p>
                                <p style="color: #34D399; font-size: 0.85rem; font-weight: 800;">Nueva zona asignada: <strong>${body.ticket?.new_zone || 'Zona Superior'}</strong></p>
                                <p style="color: #94A3B8; font-size: 0.8rem; margin-top: 0.4rem;">Titular: ${body.ticket?.buyer_name || 'Asistente'}</p>
                            </div>
                        `,
                        timer: 6000,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                } else if (body.status === 'already_used') {
                    Swal.fire({
                        icon: 'warning',
                        title: '🚫 Boleto Ya Utilizado',
                        html: `<p style="color: #F59E0B; font-weight: 700;">${body.message || 'Este boleto ya fue escaneado anteriormente.'}</p>`,
                        timer: 4000,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                } else if (body.status === 'wrong_event') {
                    Swal.fire({
                        icon: 'warning',
                        title: body.title || '⚠️ Boleto de Otro Evento',
                        html: `<p style="color: #F59E0B; font-weight: 700; font-size: 0.95rem;">${body.message || 'Este boleto pertenece a otro evento.'}</p>`,
                        timer: 5000,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: body.title || '❌ Boleto Inválido',
                        html: `<p style="color: #EF4444; font-weight: 700;">${body.message || 'El código no corresponde a ningún boleto emitido.'}</p>`,
                        timer: 4500,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            })
            .catch(err => {
                console.error('Error verificando boleto:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo verificar el boleto. Comprueba tu conexión a internet.',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            })
            .finally(() => {
                setTimeout(() => { isProcessingScan = false; }, 600);
            });
        }

        // Agregar fila dinámica a la tabla de accesos en tiempo real
        function appendCheckinRow(ticket) {
            const tbody = document.getElementById('checkinsTableBody');
            const emptyRow = document.getElementById('emptyCheckinsRow');
            if (emptyRow) emptyRow.remove();

            const existingRow = document.getElementById(`checkinRow_${ticket.id}`);
            if (existingRow) return;

            const isDig = ticket.is_digital || ['digital', 'cortesia_digital'].includes(ticket.ticket_type);
            const typeBadgeHtml = isDig
                ? '<span class="dash-badge-custom badge-cyan" style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.55rem; letter-spacing: 0.3px;">🌐 Virtual</span>'
                : '<span class="dash-badge-custom badge-orange" style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.55rem; letter-spacing: 0.3px;">🎟️ Físico</span>';

            const tr = document.createElement('tr');
            tr.className = 'checkin-row-item row-highlight-new';
            tr.id = `checkinRow_${ticket.id}`;
            tr.innerHTML = `
                <td><span style="font-weight: 800; color: #10B981;">NUEVO</span></td>
                <td>
                    <span style="font-family: monospace; font-weight: 800; color: #FFFFFF; font-size: 0.9rem;">${ticket.ticket_code}</span>
                    <small style="display: block; font-family: monospace; color: #FF7733; font-size: 0.75rem; font-weight: 800;">🔑 ${ticket.validation_hash || ''}</small>
                </td>
                <td>
                    <div style="display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                        <span class="dash-badge-custom badge-green" style="font-size: 0.78rem; font-weight: 800;">${ticket.zone_name}</span>
                        ${typeBadgeHtml}
                    </div>
                </td>
                <td><strong style="color: #FFFFFF;">${ticket.buyer_name}</strong></td>
                <td><span style="color: #94A3B8; font-family: monospace;">${ticket.buyer_dni || '-'}</span></td>
                <td>
                    <span style="color: #00F0FF; font-weight: 700;">${ticket.checked_in_at}</span>
                    <small style="display: block; color: #64748B; font-size: 0.7rem;">${ticket.checked_in_date || 'Hoy'}</small>
                </td>
                <td><span style="color: #E2E8F0; font-size: 0.85rem;">${ticket.scanned_by || 'Puerta Principal'}</span></td>
                <td><span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">✓ Ingresado</span></td>
                <td style="text-align: right;">
                    <button type="button" 
                            onclick="resetCheckin(${ticket.id}, '${ticket.ticket_code}')" 
                            class="btn btn-sm"
                            title="Eliminar escaneo y permitir escanear de nuevo"
                            style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.35); padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 800; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.35rem;"
                            onmouseenter="this.style.background='#EF4444'; this.style.color='#FFFFFF';"
                            onmouseleave="this.style.background='rgba(239, 68, 68, 0.15)'; this.style.color='#EF4444';">
                        <span>🗑️</span> <span>Anular Escaneo</span>
                    </button>
                </td>
            `;

            if (tbody.firstChild) {
                tbody.insertBefore(tr, tbody.firstChild);
            } else {
                tbody.appendChild(tr);
            }
        }

        // Anular / Eliminar el escaneo de un boleto para permitir re-escanearlo
        function resetCheckin(ticketId, ticketCode) {
            Swal.fire({
                title: '¿Anular este Escaneo?',
                html: `
                    <p style="color: #94A3B8; font-size: 0.95rem; margin-bottom: 0.5rem;">
                        Se cancelará el registro de ingreso para el boleto <strong style="color: #FFFFFF; font-family: monospace;">${ticketCode}</strong>.
                    </p>
                    <p style="color: #10B981; font-weight: 700; font-size: 0.85rem;">
                        ✨ El boleto volverá a quedar como disponible y podrá ser escaneado nuevamente.
                    </p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '🗑️ Sí, Anular Escaneo',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#475569',
                background: '#14141E',
                color: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Anulando ingreso...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        background: '#14141E',
                        color: '#FFFFFF'
                    });

                    fetch(`/admin/asistentes/${eventId}/anular-escaneo/${ticketId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.getElementById(`checkinRow_${ticketId}`);
                            if (row) {
                                row.style.transition = 'all 0.4s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(40px)';
                                setTimeout(() => {
                                    row.remove();
                                    const tbody = document.getElementById('checkinsTableBody');
                                    if (tbody && tbody.children.length === 0) {
                                        tbody.innerHTML = `
                                            <tr id="emptyCheckinsRow">
                                                <td colspan="9" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
                                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎫</div>
                                                    <strong>Aún no se han registrado ingresos para este evento.</strong>
                                                    <p style="margin: 0.25rem 0 0 0; font-size: 0.85rem;">Escanea el primer código QR o ingresa un número de boleto para comenzar.</p>
                                                </td>
                                            </tr>
                                        `;
                                    }
                                }, 400);
                            }

                            if (data.metrics) {
                                updateKpis(data.metrics);
                            }
                            if (data.zones) {
                                updateZones(data.zones);
                            }

                            Swal.fire({
                                icon: 'success',
                                title: '¡Escaneo Anulado!',
                                text: data.message || 'El boleto ya puede volver a ser escaneado.',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#10B981',
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se pudo anular',
                                text: data.message || 'Ocurrió un error inesperado.',
                                confirmButtonColor: '#FF5500',
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Conexión',
                            text: 'No se pudo comunicar con el servidor.',
                            confirmButtonColor: '#FF5500',
                            background: '#14141E',
                            color: '#FFFFFF'
                        });
                    });
                }
            });
        }

        // Actualizar métricas KPI en pantalla
        function updateKpis(m) {
            const issuedEl = document.getElementById('kpiTicketsIssued');
            const checkedEl = document.getElementById('kpiCheckedIn');
            const pendingEl = document.getElementById('kpiPending');
            const rateEl = document.getElementById('kpiAttendanceRate');

            if (issuedEl && m.tickets_issued !== undefined) issuedEl.textContent = Number(m.tickets_issued).toLocaleString();
            if (checkedEl && m.checked_in_count !== undefined) checkedEl.textContent = Number(m.checked_in_count).toLocaleString();
            if (pendingEl && m.pending_count !== undefined) pendingEl.textContent = Number(m.pending_count).toLocaleString();
            if (rateEl && m.attendance_rate !== undefined) rateEl.textContent = `${m.attendance_rate}%`;

            // Mini KPIs
            const kpiDig = document.getElementById('kpiDigitalChecked');
            const kpiPhys = document.getElementById('kpiPhysicalChecked');
            if (kpiDig && m.digital_checked !== undefined) kpiDig.textContent = Number(m.digital_checked).toLocaleString();
            if (kpiPhys && m.physical_checked !== undefined) kpiPhys.textContent = Number(m.physical_checked).toLocaleString();

            // Tarjetas dedicadas de Ocupación y Asistencia (Digital vs Físico)
            const digCheckedEl = document.getElementById('statDigitalChecked');
            const digIssuedEl = document.getElementById('statDigitalIssued');
            const digPendingEl = document.getElementById('statDigitalPending');
            const digRateEl = document.getElementById('statDigitalRate');
            const digBarEl = document.getElementById('barDigitalRate');

            if (digCheckedEl && m.digital_checked !== undefined) digCheckedEl.textContent = Number(m.digital_checked).toLocaleString();
            if (digIssuedEl && m.digital_issued !== undefined) digIssuedEl.textContent = Number(m.digital_issued).toLocaleString();
            if (digPendingEl && m.digital_pending !== undefined) digPendingEl.textContent = Number(m.digital_pending).toLocaleString();
            if (digRateEl && m.digital_rate !== undefined) digRateEl.textContent = `${m.digital_rate}% ingresaron`;
            if (digBarEl && m.digital_rate !== undefined) digBarEl.style.width = `${m.digital_rate}%`;

            const physCheckedEl = document.getElementById('statPhysicalChecked');
            const physIssuedEl = document.getElementById('statPhysicalIssued');
            const physPendingEl = document.getElementById('statPhysicalPending');
            const physRateEl = document.getElementById('statPhysicalRate');
            const physBarEl = document.getElementById('barPhysicalRate');

            if (physCheckedEl && m.physical_checked !== undefined) physCheckedEl.textContent = Number(m.physical_checked).toLocaleString();
            if (physIssuedEl && m.physical_issued !== undefined) physIssuedEl.textContent = Number(m.physical_issued).toLocaleString();
            if (physPendingEl && m.physical_pending !== undefined) physPendingEl.textContent = Number(m.physical_pending).toLocaleString();
            if (physRateEl && m.physical_rate !== undefined) physRateEl.textContent = `${m.physical_rate}% ingresaron`;
            if (physBarEl && m.physical_rate !== undefined) physBarEl.style.width = `${m.physical_rate}%`;
        }

        // Actualizar métricas por zona / sector en tiempo real
        function updateZones(zones) {
            if (!Array.isArray(zones)) return;
            zones.forEach(z => {
                const zCard = document.querySelector(`.pos-zone-card[data-zone-name="${z.name}"]`);
                if (zCard) {
                    const checkedEl = zCard.querySelector('.zone-checked-count');
                    const pendingEl = zCard.querySelector('.zone-pending-count');
                    const progressEl = zCard.querySelector('.zone-progress-bar');
                    const badge = zCard.querySelector('.dash-badge-custom');
                    const digEl = zCard.querySelector('.zone-digital-count');
                    const digIssEl = zCard.querySelector('.zone-digital-issued');
                    const physEl = zCard.querySelector('.zone-physical-count');
                    const physIssEl = zCard.querySelector('.zone-physical-issued');

                    if (checkedEl && z.checked_in !== undefined) checkedEl.textContent = z.checked_in;
                    if (pendingEl && z.pending !== undefined) pendingEl.textContent = z.pending;
                    if (progressEl && z.rate !== undefined) progressEl.style.width = `${z.rate}%`;
                    if (badge && z.rate !== undefined) badge.textContent = `${z.rate}% ingresaron`;
                    if (digEl && z.digital_checked !== undefined) digEl.textContent = z.digital_checked;
                    if (digIssEl && z.digital_issued !== undefined) digIssEl.textContent = z.digital_issued;
                    if (physEl && z.physical_checked !== undefined) physEl.textContent = z.physical_checked;
                    if (physIssEl && z.physical_issued !== undefined) physIssEl.textContent = z.physical_issued;
                }
            });
        }

        // Sincronización automática y a demanda en tiempo real
        let isSyncing = false;

        function syncFeedData(isManual = false) {
            if (isSyncing) return;
            isSyncing = true;

            const icon = document.getElementById('refreshIcon');
            if (isManual && icon) {
                icon.style.display = 'inline-block';
                icon.style.animation = 'spin 0.8s linear infinite';
            }

            fetch(`/admin/asistentes/${eventId}/checkins-feed`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('checkinsTableBody');
                    const emptyRow = document.getElementById('emptyCheckinsRow');

                    if (data.new_checkins && data.new_checkins.length > 0) {
                        if (emptyRow) emptyRow.remove();

                        if (isManual && tbody) {
                            tbody.innerHTML = '';
                        }

                        // Iterar en reversa (del más antiguo al más reciente del lote) para que al insertar al tope queden en orden cronológico descendente
                        const checkinsToProcess = isManual ? data.new_checkins : [...data.new_checkins].reverse();

                        checkinsToProcess.forEach(t => {
                            const existingRow = document.getElementById(`checkinRow_${t.id}`);
                            if (!existingRow) {
                                appendCheckinRow(t);
                            } else {
                                const scannedCell = existingRow.querySelector('td:nth-child(7)');
                                if (scannedCell && t.scanned_by) {
                                    scannedCell.innerHTML = `<span style="color: #E2E8F0; font-size: 0.85rem;">${t.scanned_by}</span>`;
                                }
                            }
                        });
                    }

                    // Sincronizar filas removidas / anuladas
                    if (data.active_checkin_ids && Array.isArray(data.active_checkin_ids)) {
                        const activeSet = new Set(data.active_checkin_ids.map(Number));
                        const rows = document.querySelectorAll('#checkinsTableBody .checkin-row-item');
                        rows.forEach(r => {
                            const rawId = r.id.replace('checkinRow_', '');
                            if (rawId && !activeSet.has(Number(rawId))) {
                                r.remove();
                            }
                        });

                        if (data.active_checkin_ids.length === 0 && tbody && !document.getElementById('emptyCheckinsRow')) {
                            tbody.innerHTML = `
                                <tr id="emptyCheckinsRow">
                                    <td colspan="9" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
                                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎫</div>
                                        <strong>Aún no se han registrado ingresos para este evento.</strong>
                                        <p style="margin: 0.25rem 0 0 0; font-size: 0.85rem;">Escanea el primer código QR o ingresa un número de boleto para comenzar.</p>
                                    </td>
                                </tr>
                            `;
                        }
                    }

                    if (data.metrics) {
                        updateKpis(data.metrics);
                    }

                    if (data.zones) {
                        updateZones(data.zones);
                    }

                    if (isManual) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: '✓ Asistencias actualizadas',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#14141E',
                            color: '#FFFFFF'
                        });
                    }
                }
            })
            .catch(err => {})
            .finally(() => {
                isSyncing = false;
                if (isManual && icon) {
                    setTimeout(() => { icon.style.animation = 'none'; }, 400);
                }
                // Programar la siguiente sincronización automática cada 1.5 segundos
                if (!isManual) {
                    setTimeout(scheduleAutoSync, 1500);
                }
            });
        }

        function manualRefreshFeed() {
            syncFeedData(true);
        }

        function scheduleAutoSync() {
            if (!document.hidden) {
                syncFeedData(false);
            } else {
                setTimeout(scheduleAutoSync, 2000);
            }
        }

        // Iniciar sincronización automática en vivo de inmediato
        setTimeout(scheduleAutoSync, 1000);

        document.addEventListener('DOMContentLoaded', function () {
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
