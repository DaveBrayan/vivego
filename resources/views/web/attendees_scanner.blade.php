@extends('layouts.app')

@php
    $adminId = session('admin_id');
    $loggedAdmin = $adminId ? \App\Models\Administrator::find($adminId) : null;
    $canDeleteScans = $loggedAdmin ? $loggedAdmin->canDelete() : true;
@endphp

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
    @keyframes modalPop {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
                        <button type="button" class="btn" onclick="openScannerDevicesModal()" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; padding: 0.75rem 1.4rem; font-size: 0.9rem; border: none; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.35); cursor: pointer; transition: all 0.2s ease;">
                            <span>📱</span>
                            <span>+ Vincular Scanner Web</span>
                        </button>
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
                            <button type="button" class="btn btn-sm" onclick="openDeviceLogsModal()" style="background: linear-gradient(135deg, #8B5CF6, #6366F1); color: #FFFFFF; font-weight: 900; font-size: 0.85rem; padding: 0.6rem 1.3rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; border: none; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4); cursor: pointer; transition: all 0.2s ease;">
                                <span>📋</span>
                                <span>Historial de Dispositivos</span>
                                <span id="devLogsBtnBadge" style="background: rgba(255,255,255,0.25); color: #FFFFFF; font-size: 0.7rem; font-weight: 900; padding: 0.1rem 0.45rem; border-radius: 20px;">LIVE</span>
                            </button>
                            <button type="button" class="btn btn-sm" onclick="openScannerDevicesModal()" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.85rem; padding: 0.6rem 1.3rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; border: none; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.35); cursor: pointer; transition: all 0.2s ease;">
                                <span>📱</span>
                                <span>+ Vincular Scanner</span>
                            </button>
                            <button type="button" id="btnManualRefresh" onclick="manualRefreshFeed()" style="background: rgba(255,255,255,0.08); color: #FFFFFF; font-weight: 800; font-size: 0.85rem; padding: 0.6rem 1.15rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.45rem; border: 1px solid rgba(255,255,255,0.15); cursor: pointer; transition: all 0.2s ease;">
                                <span id="refreshIcon" style="font-size: 1rem;">🔄</span>
                                <span>Actualizar</span>
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
                                            @if($canDeleteScans)
                                            <button type="button" 
                                                    onclick="resetCheckin({{ $chk->id }}, '{{ $chk->ticket_code }}')" 
                                                    class="btn btn-sm"
                                                    title="Eliminar escaneo y permitir escanear de nuevo"
                                                    style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.35); padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 800; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.35rem;"
                                                    onmouseenter="this.style.background='#EF4444'; this.style.color='#FFFFFF';"
                                                    onmouseleave="this.style.background='rgba(239, 68, 68, 0.15)'; this.style.color='#EF4444';">
                                                <span>🗑️</span> <span>Anular Escaneo</span>
                                            </button>
                                            @else
                                            <span style="color: #64748B; font-size: 0.75rem; font-weight: 700;">🔒 Protegido</span>
                                            @endif
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

    <!-- MODAL DE VINCULACIÓN DE SCANNERS & DISPOSITIVOS MÓVILES (2 COLUMNAS) -->
    <div id="scannerDevicesModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(5, 5, 12, 0.88); backdrop-filter: blur(10px); justify-content: center; align-items: center; padding: 1.25rem;">
        <div style="background: #0F0F1A; border: 1.5px solid rgba(0, 240, 255, 0.35); border-radius: 24px; width: 100%; max-width: 980px; max-height: 92vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 70px rgba(0,0,0,0.9), 0 0 45px rgba(0,240,255,0.15); animation: modalPop 0.25s ease;">
            
            <!-- Modal Header -->
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.35); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                        📱
                    </div>
                    <div>
                        <h3 style="font-size: 1.2rem; font-weight: 900; color: #FFFFFF; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                            <span>Vincular Terminales & Scanners Móviles</span>
                            <span style="font-size: 0.72rem; padding: 0.15rem 0.6rem; border-radius: 20px; background: rgba(16,185,129,0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.3); font-weight: 800;">LIVE</span>
                        </h3>
                        <p style="font-size: 0.82rem; color: #94A3B8; margin: 0.2rem 0 0 0;">Genera enlaces y códigos QR individuales y hasheados para cada celular o puerta de acceso.</p>
                    </div>
                </div>
                <button type="button" onclick="closeScannerDevicesModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #94A3B8; font-size: 1.1rem; width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease;">
                    ✕
                </button>
            </div>

            <!-- Modal Body (2 Columns) -->
            <div style="padding: 1.5rem 1.75rem; overflow-y: auto; display: grid; grid-template-columns: 1.1fr 1.15fr; gap: 1.5rem; align-items: stretch;">
                
                <!-- COLUMNA 1: DISPOSITIVOS & AGREGAR NUEVO -->
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                    
                    <!-- Formulario Agregar Dispositivo -->
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.4px; display: block; margin-bottom: 0.45rem;">
                            ➕ Agregar Nuevo Dispositivo / Puerta
                        </label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" id="newDeviceInputName" placeholder="Ej: Puerta VIP, Móvil 2, Control Norte..." style="flex: 1; background: #14141E; border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 0.7rem 0.9rem; color: #FFFFFF; font-size: 0.88rem; font-weight: 700; outline: none;" onkeydown="if(event.key==='Enter') addNewScannerDevice();">
                            <button type="button" onclick="addNewScannerDevice()" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; border: none; font-weight: 900; font-size: 0.85rem; padding: 0.7rem 1.1rem; border-radius: 12px; cursor: pointer; white-space: nowrap; box-shadow: 0 4px 14px rgba(0,240,255,0.35); transition: all 0.2s ease;">
                                + Agregar
                            </button>
                        </div>
                        <small style="color: #64748B; font-size: 0.73rem; margin-top: 0.35rem; display: block;">
                            Cada dispositivo tendrá un código QR único y registrará el nombre exacto de la puerta en las asistencias.
                        </small>
                    </div>

                    <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.08); margin: 0;">

                    <!-- Listado de Dispositivos Registrados -->
                    <div style="flex: 1; display: flex; flex-direction: column; min-height: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                            <span style="font-size: 0.78rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.4px;">
                                Terminales Activos (<span id="devicesCountBadge">0</span>)
                            </span>
                            <span style="font-size: 0.72rem; color: #00F0FF; font-weight: 700;">● Click para seleccionar</span>
                        </div>

                        <div id="devicesListContainer" style="display: flex; flex-direction: column; gap: 0.6rem; max-height: 280px; overflow-y: auto; padding-right: 0.3rem;">
                            <!-- Rendered via JS -->
                        </div>
                    </div>
                </div>

                <!-- COLUMNA 2: VISUALIZADOR DE QR & ENLACE HASHED -->
                <div style="background: rgba(0, 240, 255, 0.03); border: 1.5px solid rgba(0, 240, 255, 0.25); border-radius: 20px; padding: 1.35rem; display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: space-between;">
                    
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem; gap: 0.6rem;">
                        <div style="text-align: left; flex: 1;">
                            <span style="font-size: 0.7rem; font-weight: 800; color: #00F0FF; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.25rem;">
                                Nombre del Terminal (Modificable en Vivo)
                            </span>
                            <div style="display: flex; align-items: center; gap: 0.4rem; background: #14141E; border: 1.5px solid rgba(0, 240, 255, 0.4); border-radius: 10px; padding: 0.35rem 0.65rem;">
                                <span style="font-size: 0.85rem;">📱</span>
                                <input type="text" id="activeDeviceNameInput" oninput="handleActiveDeviceRename(this.value)" placeholder="Nombre del terminal..." style="background: transparent; border: none; color: #FFFFFF; font-size: 0.92rem; font-weight: 800; width: 100%; outline: none;">
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.3rem;">
                            <span id="activeDeviceHashBadge" style="background: rgba(0,240,255,0.12); border: 1px solid rgba(0,240,255,0.3); color: #00F0FF; font-family: monospace; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 8px;">
                                HASH: -
                            </span>
                            <span id="activeDeviceClaimBadge" style="font-size: 0.68rem; font-weight: 800; color: #94A3B8; padding: 0.15rem 0.5rem; border-radius: 6px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                ⚪ Sin conectar
                            </span>
                        </div>
                    </div>

                    <!-- Tarjeta Blanca con QR de Alta Definición -->
                    <div style="background: #FFFFFF; padding: 0.85rem; border-radius: 20px; display: inline-block; box-shadow: 0 12px 35px rgba(0,0,0,0.6); margin-bottom: 1rem; position: relative;">
                        <img id="activeDeviceQrImg" src="" alt="QR Scanner" style="width: 180px; height: 180px; display: block; border-radius: 8px;">
                    </div>

                    <!-- Caja con URL Hashed del Scanner -->
                    <div style="width: 100%; margin-bottom: 1rem;">
                        <input type="text" id="activeDeviceUrlInput" readonly onclick="this.select(); copyActiveDeviceUrl(null);" title="Click para copiar" style="width: 100%; background: #14141E; border: 1px solid rgba(0,240,255,0.3); border-radius: 12px; padding: 0.7rem 0.9rem; color: #00F0FF; font-family: monospace; font-size: 0.76rem; text-align: center; outline: none; cursor: pointer;">
                    </div>

                    <!-- Botones de Acción para Compartir -->
                    <div style="width: 100%; display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                        <button type="button" onclick="copyActiveDeviceUrl(this)" style="flex: 1; min-width: 120px; background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; border: none; font-weight: 900; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(0,240,255,0.35);">
                            <span>📋</span>
                            <span class="btn-copy-txt">Copiar Enlace</span>
                        </button>
                        <button type="button" onclick="shareActiveDeviceWa()" style="flex: 1; min-width: 120px; background: rgba(37, 211, 102, 0.15); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 800; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;">
                            <span>💬</span>
                            <span>WhatsApp</span>
                        </button>
                        <button type="button" onclick="releaseActiveDevice()" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.35); font-weight: 800; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;" title="Liberar vinculación para abrir en otro celular">
                            <span>🔓</span>
                            <span>Liberar</span>
                        </button>
                        <a id="activeDeviceDirectLink" href="#" target="_blank" style="background: rgba(255,255,255,0.06); color: #FFFFFF; border: 1px solid rgba(255,255,255,0.18); font-weight: 800; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;">
                            <span>🚀</span>
                            <span>Probar</span>
                        </a>
                    </div>

                    <div style="margin-top: 0.85rem; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.02); border-radius: 10px; border: 1px dashed rgba(255,255,255,0.12); width: 100%;">
                        <p style="margin: 0; font-size: 0.72rem; color: #94A3B8; line-height: 1.35;">
                            📸 <strong>Instrucciones:</strong> Apunta la cámara del celular a este código QR. Cada terminal es <strong>exclusivo 1-a-1</strong>; para conectar otro celular genera un nuevo dispositivo o pulsa <em>Liberar</em>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 1rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.01);">
                <span style="font-size: 0.75rem; color: #64748B;">
                    🔒 Los enlaces cuentan con token de seguridad para operar sin inicio de sesión en puertas de acceso.
                </span>
                <button type="button" onclick="closeScannerDevicesModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 800; font-size: 0.85rem; padding: 0.6rem 1.4rem; border-radius: 10px; cursor: pointer;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL DE HISTORIAL DE ESCANEOS POR DISPOSITIVOS (CONSOLIDADO EN VIVO) -->
    <div id="deviceLogsModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(5, 5, 10, 0.88); backdrop-filter: blur(14px); align-items: center; justify-content: center; padding: 1.25rem;">
        <div style="background: #0E0E17; border: 1.5px solid rgba(139, 92, 246, 0.4); border-radius: 26px; width: 100%; max-width: 1120px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 70px rgba(0,0,0,0.8), 0 0 40px rgba(139, 92, 246, 0.15);">
            
            <!-- Modal Header -->
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); flex-wrap: wrap; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.35); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                        📋
                    </div>
                    <div>
                        <h3 style="font-size: 1.2rem; font-weight: 900; color: #FFFFFF; margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                            <span>Historial de Escaneos por Dispositivos</span>
                            <span style="font-size: 0.7rem; padding: 0.15rem 0.6rem; border-radius: 20px; background: rgba(16,185,129,0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.3); font-weight: 800;">
                                ● EN VIVO
                            </span>
                        </h3>
                        <p style="font-size: 0.8rem; color: #94A3B8; margin: 0.2rem 0 0 0;">
                            Auditoría de todos los escaneos realizados por terminales móviles y web (Válidos, Duplicados, Anulados e Inválidos).
                        </p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <button type="button" onclick="loadDeviceLogsData(true)" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; font-size: 0.82rem; font-weight: 800; padding: 0.55rem 0.95rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;" title="Refrescar datos">
                        <span id="devLogsRefreshIcon">🔄</span> <span>Refrescar</span>
                    </button>
                    <button type="button" onclick="clearDeviceLogsData()" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #EF4444; font-size: 0.82rem; font-weight: 800; padding: 0.55rem 0.95rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;" title="Limpiar historial">
                        <span>🗑️</span> <span>Limpiar</span>
                    </button>
                    <button type="button" onclick="closeDeviceLogsModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #94A3B8; font-size: 1.1rem; width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease;">
                        ✕
                    </button>
                </div>
            </div>

            <!-- Metric KPI Cards inside modal -->
            <div style="padding: 1rem 1.75rem; background: rgba(0,0,0,0.25); border-bottom: 1px solid rgba(255,255,255,0.06); display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.85rem;">
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem 1rem;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Total Intentos</span>
                    <div style="font-size: 1.35rem; font-weight: 900; color: #FFFFFF;" id="devLogStatTotal">0</div>
                </div>
                <div style="background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 14px; padding: 0.75rem 1rem;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #10B981; text-transform: uppercase;">✅ Válidos (Permitidos)</span>
                    <div style="font-size: 1.35rem; font-weight: 900; color: #10B981;" id="devLogStatGranted">0</div>
                </div>
                <div style="background: rgba(245, 158, 11, 0.06); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: 14px; padding: 0.75rem 1rem;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #F59E0B; text-transform: uppercase;">🚫 Duplicados (Ya Usados)</span>
                    <div style="font-size: 1.35rem; font-weight: 900; color: #F59E0B;" id="devLogStatAlreadyUsed">0</div>
                </div>
                <div style="background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 14px; padding: 0.75rem 1rem;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: #EF4444; text-transform: uppercase;">❌ Erróneos / Inválidos</span>
                    <div style="font-size: 1.35rem; font-weight: 900; color: #EF4444;" id="devLogStatErrors">0</div>
                </div>
            </div>

            <!-- Filter & Search toolbar -->
            <div style="padding: 0.85rem 1.75rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.01);">
                <!-- Status Filter Buttons -->
                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                    <button type="button" class="dev-log-filter-btn" id="filterBtn_all" onclick="setDevLogStatusFilter('all')" style="background: rgba(139, 92, 246, 0.2); border: 1px solid #8B5CF6; color: #FFFFFF; font-weight: 800; font-size: 0.78rem; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                        Todos (<span id="devLogFilterAllCount">0</span>)
                    </button>
                    <button type="button" class="dev-log-filter-btn" id="filterBtn_granted" onclick="setDevLogStatusFilter('granted')" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; font-weight: 800; font-size: 0.78rem; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                        ✅ Válidos (<span id="devLogFilterGrantedCount">0</span>)
                    </button>
                    <button type="button" class="dev-log-filter-btn" id="filterBtn_already_used" onclick="setDevLogStatusFilter('already_used')" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; font-weight: 800; font-size: 0.78rem; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                        🚫 Duplicados (<span id="devLogFilterUsedCount">0</span>)
                    </button>
                    <button type="button" class="dev-log-filter-btn" id="filterBtn_errors" onclick="setDevLogStatusFilter('errors')" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; font-weight: 800; font-size: 0.78rem; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                        ❌ Erróneos (<span id="devLogFilterErrorCount">0</span>)
                    </button>
                </div>

                <!-- Dispositivo dropdown & search box -->
                <div style="display: flex; gap: 0.6rem; align-items: center; flex: 1; min-width: 280px; justify-content: flex-end; flex-wrap: wrap;">
                    <select id="devLogDeviceSelect" onchange="filterAndRenderDevLogs()" style="background: #14141E; border: 1px solid rgba(0, 240, 255, 0.35); border-radius: 8px; color: #00F0FF; font-weight: 800; font-size: 0.78rem; padding: 0.45rem 0.75rem; outline: none; cursor: pointer; min-width: 170px;">
                        <option value="all">📱 Todos los Terminales</option>
                    </select>

                    <div style="position: relative; min-width: 180px; max-width: 240px; flex: 1;">
                        <input type="text" id="devLogSearchInput" oninput="filterAndRenderDevLogs()" placeholder="Buscar código, nombre, DNI..." style="width: 100%; background: #14141E; border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 0.45rem 0.75rem; color: #FFFFFF; font-size: 0.78rem; outline: none;">
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div style="padding: 1rem 1.75rem; overflow-y: auto; flex: 1; min-height: 280px;">
                <table class="dash-table" style="width: 100%; font-size: 0.82rem;">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Hora / Fecha</th>
                            <th style="width: 170px;">Dispositivo / Terminal</th>
                            <th>Boleto / Hash</th>
                            <th>Asistente / DNI</th>
                            <th>Zona</th>
                            <th>Resultado</th>
                            <th>Detalle / Mensaje</th>
                        </tr>
                    </thead>
                    <tbody id="devLogsTableBody">
                        <!-- Dynamic rendering -->
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 0.85rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.01);">
                <span style="font-size: 0.74rem; color: #94A3B8;">
                    🔄 Sincronización en vivo cada 3 segundos mientras este modal esté abierto.
                </span>
                <button type="button" onclick="closeDeviceLogsModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 800; font-size: 0.85rem; padding: 0.55rem 1.3rem; border-radius: 10px; cursor: pointer;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const eventId = {{ $event->id }};
        const eventTitle = "{{ addslashes($event->title) }}";
        const verifyUrl = "{{ route('web.attendees.verify_qr', $event->id) }}";
        const csrfToken = "{{ csrf_token() }}";
        const canDeleteScans = {{ $canDeleteScans ? 'true' : 'false' }};

        let isProcessingScan = false;

        /* ========================================================
           SISTEMA DE GESTIÓN Y VINCULACIÓN DE SCANNERS / DISPOSITIVOS
           ======================================================== */
        const STORAGE_KEY = `vivego_scanner_devices_evt_${eventId}`;
        let scannerDevices = [];
        let activeDeviceId = null;

        function loadScannerDevices() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved) {
                    scannerDevices = JSON.parse(saved);
                }
            } catch (e) {
                scannerDevices = [];
            }

            if (!Array.isArray(scannerDevices) || scannerDevices.length === 0) {
                const defHash = generateDeviceHash();
                scannerDevices = [
                    {
                        id: 'dev_' + Date.now(),
                        name: 'Móvil 1 - Puerta Principal',
                        hash: defHash,
                        token: 'VGTOK_' + Math.random().toString(36).substring(2, 10).toUpperCase(),
                        createdAt: new Date().toLocaleDateString()
                    }
                ];
                saveScannerDevices();
            }

            if (!activeDeviceId || !scannerDevices.find(d => d.id === activeDeviceId)) {
                activeDeviceId = scannerDevices[0].id;
            }
        }

        function saveScannerDevices() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(scannerDevices));
        }

        function generateDeviceHash() {
            const chars = '0123456789ABCDEF';
            let res = 'VGDEV-';
            for (let i = 0; i < 6; i++) {
                res += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return res;
        }

        function getDeviceScannerUrl(device) {
            const origin = window.location.origin;
            const devName = encodeURIComponent(device.name);
            const devToken = encodeURIComponent(device.token || device.hash);
            return `${origin}/scanner/${eventId}?dev=${devName}&token=${devToken}`;
        }

        function openScannerDevicesModal() {
            loadScannerDevices();
            renderScannerDevicesList();
            selectScannerDevice(activeDeviceId);

            const modal = document.getElementById('scannerDevicesModal');
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    const input = document.getElementById('newDeviceInputName');
                    if (input) input.focus();
                }, 100);
            }
        }

        function closeScannerDevicesModal() {
            const modal = document.getElementById('scannerDevicesModal');
            if (modal) modal.style.display = 'none';
        }

        function renderScannerDevicesList() {
            const container = document.getElementById('devicesListContainer');
            const countBadge = document.getElementById('devicesCountBadge');
            if (!container) return;

            if (countBadge) countBadge.textContent = scannerDevices.length;

            container.innerHTML = '';
            scannerDevices.forEach((dev) => {
                const isActive = dev.id === activeDeviceId;
                const card = document.createElement('div');
                card.id = `deviceCard_${dev.id}`;
                card.style.cssText = `
                    background: ${isActive ? 'rgba(0, 240, 255, 0.12)' : 'rgba(255, 255, 255, 0.03)'};
                    border: 1.5px solid ${isActive ? '#00F0FF' : 'rgba(255, 255, 255, 0.09)'};
                    border-radius: 14px;
                    padding: 0.75rem 0.95rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    box-shadow: ${isActive ? '0 0 18px rgba(0, 240, 255, 0.25)' : 'none'};
                `;

                card.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.65rem; min-width: 0;">
                        <span style="font-size: 1.25rem;">📱</span>
                        <div style="min-width: 0;">
                            <strong class="device-card-name" style="font-size: 0.88rem; color: #FFFFFF; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${dev.name}
                            </strong>
                            <div style="display: flex; align-items: center; gap: 0.4rem; margin-top: 0.15rem;">
                                <span style="font-family: monospace; font-size: 0.72rem; color: ${isActive ? '#00F0FF' : '#94A3B8'}; font-weight: 700;">
                                    ${dev.hash || 'VGDEV'}
                                </span>
                                <span class="device-status-pill" style="font-size: 0.68rem; font-weight: 700; color: #94A3B8;">⚪ Disponible</span>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.4rem;">
                        ${isActive ? '<span style="font-size: 0.7rem; font-weight: 900; background: #00F0FF; color: #050B14; padding: 0.15rem 0.45rem; border-radius: 6px;">ACTIVO</span>' : ''}
                        ${scannerDevices.length > 1 ? `
                            <button type="button" onclick="event.stopPropagation(); deleteScannerDevice('${dev.id}');" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444; border-radius: 8px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.8rem;" title="Eliminar dispositivo">
                                🗑️
                            </button>
                        ` : ''}
                    </div>
                `;

                card.onclick = () => selectScannerDevice(dev.id);
                container.appendChild(card);
            });
        }

        function addNewScannerDevice() {
            const input = document.getElementById('newDeviceInputName');
            let name = input ? input.value.trim() : '';

            if (!name) {
                name = `Móvil ${scannerDevices.length + 1} - Puerta ${scannerDevices.length + 1}`;
            }

            const newDev = {
                id: 'dev_' + Date.now(),
                name: name,
                hash: generateDeviceHash(),
                token: 'VGTOK_' + Math.random().toString(36).substring(2, 10).toUpperCase(),
                createdAt: new Date().toLocaleDateString()
            };

            scannerDevices.push(newDev);
            saveScannerDevices();

            if (input) input.value = '';

            activeDeviceId = newDev.id;
            renderScannerDevicesList();
            selectScannerDevice(newDev.id);
            if (typeof loadDeviceLogsData === 'function') {
                loadDeviceLogsData(false);
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `✓ Dispositivo "${name}" creado`,
                showConfirmButton: false,
                timer: 2000,
                background: '#14141E',
                color: '#FFFFFF'
            });
        }

        function selectScannerDevice(id) {
            activeDeviceId = id;
            const dev = scannerDevices.find(d => d.id === id) || scannerDevices[0];
            if (!dev) return;

            renderScannerDevicesList();

            const url = getDeviceScannerUrl(dev);
            const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=8&data=${encodeURIComponent(url)}`;

            const nameInput = document.getElementById('activeDeviceNameInput');
            const hashBadge = document.getElementById('activeDeviceHashBadge');
            const qrImg = document.getElementById('activeDeviceQrImg');
            const urlInput = document.getElementById('activeDeviceUrlInput');
            const directLink = document.getElementById('activeDeviceDirectLink');

            if (nameInput) nameInput.value = dev.name;
            if (hashBadge) hashBadge.textContent = `HASH: ${dev.hash || 'VGDEV'}`;
            if (qrImg) qrImg.src = qrApiUrl;
            if (urlInput) urlInput.value = url;
            if (directLink) directLink.href = url;

            checkDevicesClaimStatus();
        }

        function handleActiveDeviceRename(newName) {
            const dev = scannerDevices.find(d => d.id === activeDeviceId);
            if (!dev) return;

            dev.name = newName.trim() || 'Móvil';
            saveScannerDevices();

            const url = getDeviceScannerUrl(dev);
            const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=8&data=${encodeURIComponent(url)}`;

            const qrImg = document.getElementById('activeDeviceQrImg');
            const urlInput = document.getElementById('activeDeviceUrlInput');
            const directLink = document.getElementById('activeDeviceDirectLink');

            if (qrImg) qrImg.src = qrApiUrl;
            if (urlInput) urlInput.value = url;
            if (directLink) directLink.href = url;

            // Actualizar el nombre en la tarjeta de la lista izquierda
            const cardName = document.querySelector(`#deviceCard_${dev.id} .device-card-name`);
            if (cardName) cardName.textContent = dev.name;

            if (typeof loadDeviceLogsData === 'function') {
                loadDeviceLogsData(false);
            }
        }

        function releaseActiveDevice() {
            const dev = scannerDevices.find(d => d.id === activeDeviceId);
            if (!dev) return;

            const token = dev.token || dev.hash;
            const releaseUrl = "{{ route('web.attendees.release_device', $event->id) }}";

            fetch(releaseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    device_token: token
                })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `✓ Vinculación de "${dev.name}" liberada`,
                    text: 'Ya puedes escanear este QR desde otro celular',
                    showConfirmButton: false,
                    timer: 2500,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                checkDevicesClaimStatus();
            })
            .catch(err => console.error(err));
        }

        function checkDevicesClaimStatus() {
            const statusUrl = "{{ route('web.attendees.devices_status', $event->id) }}";
            const tokens = scannerDevices.map(d => d.token || d.hash);

            fetch(statusUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    tokens: tokens
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.statuses) {
                    const activeDev = scannerDevices.find(d => d.id === activeDeviceId);
                    const activeToken = activeDev ? (activeDev.token || activeDev.hash) : null;
                    const claimBadge = document.getElementById('activeDeviceClaimBadge');

                    if (activeToken && data.statuses[activeToken]) {
                        const st = data.statuses[activeToken];
                        if (claimBadge) {
                            if (st.claimed) {
                                claimBadge.innerHTML = '🟢 Conectado (1 celular)';
                                claimBadge.style.background = 'rgba(16, 185, 129, 0.15)';
                                claimBadge.style.color = '#10B981';
                                claimBadge.style.borderColor = 'rgba(16, 185, 129, 0.35)';
                            } else {
                                claimBadge.innerHTML = '⚪ Disponible';
                                claimBadge.style.background = 'rgba(255, 255, 255, 0.06)';
                                claimBadge.style.color = '#94A3B8';
                                claimBadge.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                            }
                        }
                    }

                    // Actualizar indicadores en la lista
                    scannerDevices.forEach(d => {
                        const tok = d.token || d.hash;
                        const st = data.statuses[tok];
                        const pill = document.querySelector(`#deviceCard_${d.id} .device-status-pill`);
                        if (pill) {
                            if (st && st.claimed) {
                                pill.textContent = '🟢 Activo';
                                pill.style.color = '#10B981';
                            } else {
                                pill.textContent = '⚪ Disponible';
                                pill.style.color = '#94A3B8';
                            }
                        }
                    });
                }
            })
            .catch(err => {});
        }

        function deleteScannerDevice(id) {
            if (scannerDevices.length <= 1) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Debe haber al menos 1 dispositivo vinculado',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            scannerDevices = scannerDevices.filter(d => d.id !== id);
            saveScannerDevices();

            if (activeDeviceId === id) {
                activeDeviceId = scannerDevices[0].id;
            }

            renderScannerDevicesList();
            selectScannerDevice(activeDeviceId);
            if (typeof loadDeviceLogsData === 'function') {
                loadDeviceLogsData(false);
            }
        }

        function copyActiveDeviceUrl(btn) {
            const dev = scannerDevices.find(d => d.id === activeDeviceId) || scannerDevices[0];
            if (!dev) return;

            const url = getDeviceScannerUrl(dev);
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    if (btn) {
                        const txt = btn.querySelector('.btn-copy-txt');
                        if (txt) {
                            txt.textContent = '✓ ¡Copiado!';
                            setTimeout(() => { txt.textContent = 'Copiar Enlace'; }, 2000);
                        }
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `📋 Enlace de "${dev.name}" copiado`,
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }).catch(() => fallbackCopy(url));
            } else {
                fallbackCopy(url);
            }
        }

        function shareActiveDeviceWa() {
            const dev = scannerDevices.find(d => d.id === activeDeviceId) || scannerDevices[0];
            if (!dev) return;

            const url = getDeviceScannerUrl(dev);
            const msg = `🎟️ *VIVE GO - TERMINAL DE CONTROL DE ACCESO*\n\nHola, aquí tienes el enlace del *Scanner Móvil* para el evento:\n👉 *${eventTitle}*\n📍 *Punto de Control:* ${dev.name}\n🔑 *Hash Dispositivo:* ${dev.hash}\n\n🔗 ${url}\n\n_Abre este enlace desde la cámara o navegador de tu celular para validar entradas._`;
            window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`, '_blank');
        }

        function fallbackCopy(text) {
            const temp = document.createElement('input');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '📋 ¡Enlace Copiado!',
                showConfirmButton: false,
                timer: 2000,
                background: '#14141E',
                color: '#FFFFFF'
            });
        }

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
                    ${canDeleteScans ? `
                    <button type="button" 
                            onclick="resetCheckin(${ticket.id}, '${ticket.ticket_code}')" 
                            class="btn btn-sm"
                            title="Eliminar escaneo y permitir escanear de nuevo"
                            style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.35); padding: 0.35rem 0.75rem; font-size: 0.75rem; font-weight: 800; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.35rem;"
                            onmouseenter="this.style.background='#EF4444'; this.style.color='#FFFFFF';"
                            onmouseleave="this.style.background='rgba(239, 68, 68, 0.15)'; this.style.color='#EF4444';">
                        <span>🗑️</span> <span>Anular Escaneo</span>
                    </button>
                    ` : '<span style="color: #64748B; font-size: 0.75rem; font-weight: 700;">🔒 Protegido</span>'}
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

        document.addEventListener('DOMContentLoaded', function () {
            loadScannerDevices();

            // Buscador en tabla de ingresos
            const tableFilter = document.getElementById('tableFilterInput');
            if (tableFilter) {
                tableFilter.addEventListener('input', function() {
                    const q = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('#checkinsTableBody .checkin-row-item');
                    rows.forEach(r => {
                        const text = r.textContent.toLowerCase();
                        r.style.display = text.includes(q) ? '' : 'none';
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

            // Iniciar sincronización automática en vivo de inmediato
            setTimeout(scheduleAutoSync, 1000);

            // Cargar datos iniciales del historial de dispositivos en background
            setTimeout(() => {
                fetch(deviceLogsUrl, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success && d.stats) {
                            const btnBadge = document.getElementById('devLogsBtnBadge');
                            if (btnBadge) btnBadge.textContent = `${d.stats.total || 0}`;
                        }
                    }).catch(e => {});
            }, 1200);
        });

        /* ========================================================
           SISTEMA DE HISTORIAL CONSOLIDADO DE ESCANEOS POR DISPOSITIVO
           ======================================================== */
        const deviceLogsUrl = "{{ route('web.attendees.device_logs', $event->id) }}";
        const clearDeviceLogsUrl = "{{ route('web.attendees.clear_device_logs', $event->id) }}";
        let rawDeviceLogs = [];
        let currentDevLogStatusFilter = 'all';
        let deviceLogsPollingInterval = null;

        function openDeviceLogsModal() {
            const modal = document.getElementById('deviceLogsModal');
            if (modal) {
                modal.style.display = 'flex';
                loadDeviceLogsData(true);
                if (deviceLogsPollingInterval) clearInterval(deviceLogsPollingInterval);
                deviceLogsPollingInterval = setInterval(() => {
                    if (modal.style.display !== 'none' && !document.hidden) {
                        loadDeviceLogsData(false);
                    }
                }, 3000);
            }
        }

        function closeDeviceLogsModal() {
            const modal = document.getElementById('deviceLogsModal');
            if (modal) {
                modal.style.display = 'none';
            }
            if (deviceLogsPollingInterval) {
                clearInterval(deviceLogsPollingInterval);
                deviceLogsPollingInterval = null;
            }
        }

        function setDevLogStatusFilter(filter) {
            currentDevLogStatusFilter = filter;

            const filterBtns = document.querySelectorAll('.dev-log-filter-btn');
            filterBtns.forEach(btn => {
                btn.style.background = 'rgba(255,255,255,0.04)';
                btn.style.borderColor = 'rgba(255,255,255,0.1)';
                btn.style.color = '#94A3B8';
            });

            const activeBtn = document.getElementById(`filterBtn_${filter}`);
            if (activeBtn) {
                activeBtn.style.background = 'rgba(139, 92, 246, 0.25)';
                activeBtn.style.borderColor = '#8B5CF6';
                activeBtn.style.color = '#FFFFFF';
            }

            filterAndRenderDevLogs();
        }

        function loadDeviceLogsData(isManual = false) {
            const icon = document.getElementById('devLogsRefreshIcon');
            if (isManual && icon) {
                icon.style.display = 'inline-block';
                icon.style.animation = 'spinRefresh 0.8s linear infinite';
            }

            fetch(deviceLogsUrl, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    rawDeviceLogs = data.logs || [];
                    const stats = data.stats || {};
                    const devices = data.devices || [];

                    // Actualizar contadores KPI
                    const elTotal = document.getElementById('devLogStatTotal');
                    const elGranted = document.getElementById('devLogStatGranted');
                    const elUsed = document.getElementById('devLogStatAlreadyUsed');
                    const elErrors = document.getElementById('devLogStatErrors');

                    if (elTotal) elTotal.textContent = stats.total || 0;
                    if (elGranted) elGranted.textContent = stats.granted_count || 0;
                    if (elUsed) elUsed.textContent = stats.already_used_count || 0;
                    if (elErrors) elErrors.textContent = stats.error_count || 0;

                    // Actualizar badges de filtros
                    const fAll = document.getElementById('devLogFilterAllCount');
                    const fGranted = document.getElementById('devLogFilterGrantedCount');
                    const fUsed = document.getElementById('devLogFilterUsedCount');
                    const fErrors = document.getElementById('devLogFilterErrorCount');

                    if (fAll) fAll.textContent = stats.total || 0;
                    if (fGranted) fGranted.textContent = stats.granted_count || 0;
                    if (fUsed) fUsed.textContent = stats.already_used_count || 0;
                    if (fErrors) fErrors.textContent = stats.error_count || 0;

                    // Badge del botón principal en la tabla
                    const btnBadge = document.getElementById('devLogsBtnBadge');
                    if (btnBadge) {
                        btnBadge.textContent = `${stats.total || 0}`;
                    }

                    // Actualizar Selector de Terminales (Combinando todos los dispositivos generados + logs)
                    loadScannerDevices(); // Sincroniza scannerDevices desde localStorage
                    const devSelect = document.getElementById('devLogDeviceSelect');
                    if (devSelect) {
                        const currentVal = devSelect.value;
                        const deviceMap = new Map();

                        // 1. Añadir todos los dispositivos configurados por el usuario
                        if (Array.isArray(scannerDevices)) {
                            scannerDevices.forEach(d => {
                                const trimmedName = (d.name || '').trim();
                                if (trimmedName) {
                                    deviceMap.set(trimmedName.toLowerCase(), {
                                        name: trimmedName,
                                        total: 0
                                    });
                                }
                            });
                        }

                        // 2. Añadir dispositivos registrados en logs del servidor
                        if (Array.isArray(devices)) {
                            devices.forEach(d => {
                                const trimmedName = (d.name || '').trim();
                                if (trimmedName) {
                                    const key = trimmedName.toLowerCase();
                                    if (!deviceMap.has(key)) {
                                        deviceMap.set(key, {
                                            name: trimmedName,
                                            total: d.total || 0
                                        });
                                    }
                                }
                            });
                        }

                        // 3. Añadir cualquier otro terminal que aparezca en rawDeviceLogs
                        if (Array.isArray(rawDeviceLogs)) {
                            rawDeviceLogs.forEach(log => {
                                const devName = (log.device_name || '').trim();
                                if (devName) {
                                    const key = devName.toLowerCase();
                                    if (!deviceMap.has(key)) {
                                        deviceMap.set(key, {
                                            name: devName,
                                            total: 0
                                        });
                                    }
                                }
                            });
                        }

                        // 4. Calcular conteo exacto por dispositivo según rawDeviceLogs
                        deviceMap.forEach((val, key) => {
                            val.total = rawDeviceLogs.filter(log => (log.device_name || '').trim().toLowerCase() === key).length;
                        });

                        let selectHtml = `<option value="all">📱 Todos los Terminales (${rawDeviceLogs.length})</option>`;
                        deviceMap.forEach((val) => {
                            selectHtml += `<option value="${val.name}">📱 ${val.name} (${val.total})</option>`;
                        });
                        devSelect.innerHTML = selectHtml;

                        // Restaurar selección previa si aún existe
                        if (currentVal && (currentVal === 'all' || Array.from(deviceMap.values()).some(v => v.name.toLowerCase() === currentVal.toLowerCase()))) {
                            const matched = Array.from(deviceMap.values()).find(v => v.name.toLowerCase() === currentVal.toLowerCase());
                            devSelect.value = matched ? matched.name : 'all';
                        }
                    }

                    filterAndRenderDevLogs();
                }
            })
            .catch(err => console.error("Error loading device logs:", err))
            .finally(() => {
                if (isManual && icon) {
                    setTimeout(() => { icon.style.animation = 'none'; }, 400);
                }
            });
        }

        function filterAndRenderDevLogs() {
            const tableBody = document.getElementById('devLogsTableBody');
            if (!tableBody) return;

            const devSelect = document.getElementById('devLogDeviceSelect');
            const selectedDev = devSelect ? devSelect.value : 'all';

            const searchInput = document.getElementById('devLogSearchInput');
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            let filtered = rawDeviceLogs.filter(item => {
                // Filtro por Dispositivo (comparación flexible)
                if (selectedDev !== 'all') {
                    const itemDev = (item.device_name || '').trim().toLowerCase();
                    const filterDev = selectedDev.trim().toLowerCase();
                    if (itemDev !== filterDev) {
                        return false;
                    }
                }

                // Filtro por Estado
                if (currentDevLogStatusFilter === 'granted' && item.status !== 'granted') {
                    return false;
                }
                if (currentDevLogStatusFilter === 'already_used' && item.status !== 'already_used') {
                    return false;
                }
                if (currentDevLogStatusFilter === 'errors' && ['granted', 'already_used'].includes(item.status)) {
                    return false;
                }

                // Filtro por Buscador
                if (query) {
                    const text = [
                        item.ticket_code,
                        item.validation_hash,
                        item.buyer_name,
                        item.buyer_dni,
                        item.zone_name,
                        item.device_name,
                        item.status_label,
                        item.message
                    ].filter(Boolean).join(' ').toLowerCase();

                    if (!text.includes(query)) return false;
                }

                return true;
            });

            if (filtered.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: #64748B;">
                            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">📋</div>
                            <strong style="color: #94A3B8; font-size: 0.95rem; display: block;">No se encontraron registros de escaneo</strong>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem;">Los escaneos que realicen los celulares aparecerán aquí automáticamente en tiempo real.</p>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            filtered.forEach(log => {
                let badgeStyle = '';
                let statusIcon = '✅';
                let statusLabel = log.status_label || 'Válido';

                if (log.status === 'granted') {
                    badgeStyle = 'background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.35);';
                    statusIcon = '✅';
                } else if (log.status === 'already_used') {
                    badgeStyle = 'background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.35);';
                    statusIcon = '🚫';
                } else if (log.status === 'upgraded_void') {
                    badgeStyle = 'background: rgba(139, 92, 246, 0.15); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.35);';
                    statusIcon = '🟣';
                } else if (log.status === 'wrong_event') {
                    badgeStyle = 'background: rgba(59, 130, 246, 0.15); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.35);';
                    statusIcon = '⚠️';
                } else {
                    badgeStyle = 'background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.35);';
                    statusIcon = '❌';
                }

                html += `
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.15s ease;">
                        <td>
                            <strong style="color: #FFFFFF; font-size: 0.82rem; display: block;">${log.time_formatted || '-'}</strong>
                            <small style="color: #64748B; font-size: 0.72rem;">${log.date_formatted || ''}</small>
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(0, 240, 255, 0.08); border: 1px solid rgba(0, 240, 255, 0.3); color: #00F0FF; padding: 0.2rem 0.55rem; border-radius: 8px; font-weight: 800; font-size: 0.75rem;">
                                <span>📱</span>
                                <span style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${log.device_name || 'Móvil'}</span>
                            </span>
                        </td>
                        <td>
                            <strong style="font-family: monospace; font-size: 0.85rem; color: #FFFFFF; display: block;">${log.ticket_code || '-'}</strong>
                            <small style="font-family: monospace; color: #FF7733; font-size: 0.72rem; font-weight: 700;">🔑 ${log.validation_hash || '-'}</small>
                        </td>
                        <td>
                            <strong style="color: #F1F5F9; font-size: 0.84rem; display: block;">${log.buyer_name || '-'}</strong>
                            <small style="color: #94A3B8; font-size: 0.72rem;">DNI: ${log.buyer_dni || '-'}</small>
                        </td>
                        <td>
                            <span style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; padding: 0.15rem 0.5rem; border-radius: 6px; font-weight: 800; font-size: 0.72rem;">
                                ${log.zone_name || '-'}
                            </span>
                        </td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.6rem; border-radius: 8px; font-weight: 800; font-size: 0.74rem; ${badgeStyle}">
                                <span>${statusIcon}</span>
                                <span>${statusLabel}</span>
                            </span>
                        </td>
                        <td>
                            <small style="color: #94A3B8; font-size: 0.74rem; line-height: 1.3; display: block;">
                                ${log.message || '-'}
                            </small>
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = html;
        }

        function clearDeviceLogsData() {
            Swal.fire({
                title: '¿Vaciar Historial de Dispositivos?',
                text: 'Esta acción limpiará el registro consolidado de escaneos de este evento. Las entradas válidas ya guardadas en la base de datos no se borrarán.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Sí, vaciar historial',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(clearDeviceLogsUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            rawDeviceLogs = [];
                            filterAndRenderDevLogs();
                            loadDeviceLogsData(true);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: '✓ Historial vaciado con éxito',
                                showConfirmButton: false,
                                timer: 2000,
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'No se pudo vaciar el historial', 'error');
                    });
                }
            });
        }
    </script>
@endpush
