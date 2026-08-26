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
                        <button type="button" class="btn btn-sm" onclick="openScannerDevicesModal()" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; padding: 0.75rem 1.35rem; font-size: 0.9rem; border: none; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.35); cursor: pointer; transition: all 0.2s ease;">
                            <span>📱</span>
                            <span>+ Agregar Scanner</span>
                        </button>
                        <a href="{{ route('web.attendees') }}" class="btn" style="background: rgba(255, 85, 0, 0.18); border: 1.5px solid #FF5500; color: #FFFFFF; font-weight: 800; padding: 0.75rem 1.4rem; font-size: 0.9rem; text-decoration: none; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.25); transition: all 0.2s ease;">
                            <span>⬅️</span>
                            <span>Volver a Asistentes</span>
                        </a>
                    </div>
                </div>

                <!-- STOCK Y ASISTENCIA POR ZONA / SECTOR -->
                <div class="settings-card-box" style="margin-bottom: 1.5rem;">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">📊</div>
                        <div>
                            <h3 class="card-header-title">Ocupación y Asistencia por Sectores</h3>
                            <p class="card-header-subtitle">Monitorea el flujo de personas en cada zona del recinto en tiempo real.</p>
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
                        <span style="font-size: 0.7rem; color: #10B981;">Asistentes verificados</span>
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
                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                            <button type="button" class="btn btn-secondary btn-sm" id="btnManualRefresh" onclick="manualRefreshFeed()" style="font-weight: 800; font-size: 0.85rem; padding: 0.55rem 1.1rem; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.45rem; cursor: pointer;">
                                <span id="refreshIcon">🔄</span>
                                <span>Actualizar Asistencias</span>
                            </button>
                            <button type="button" class="btn btn-sm" onclick="openScannerDevicesModal()" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.85rem; padding: 0.55rem 1.25rem; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.45rem; border: none; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.35); cursor: pointer; transition: all 0.2s ease;">
                                <span>📱</span>
                                <span>+ Agregar Scanner</span>
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
                                            <span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">
                                                {{ $chk->zone_name }}
                                            </span>
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
                    
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem;">
                        <div style="text-align: left;">
                            <span style="font-size: 0.7rem; font-weight: 800; color: #00F0FF; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Terminal Seleccionado</span>
                            <strong id="activeDeviceTitle" style="font-size: 1.05rem; color: #FFFFFF; font-weight: 900;">Móvil 1 - Puerta Principal</strong>
                        </div>
                        <span id="activeDeviceHashBadge" style="background: rgba(0,240,255,0.12); border: 1px solid rgba(0,240,255,0.3); color: #00F0FF; font-family: monospace; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 8px;">
                            HASH: -
                        </span>
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
                        <button type="button" onclick="copyActiveDeviceUrl(this)" style="flex: 1; min-width: 130px; background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; border: none; font-weight: 900; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; box-shadow: 0 4px 12px rgba(0,240,255,0.35);">
                            <span>📋</span>
                            <span class="btn-copy-txt">Copiar Enlace</span>
                        </button>
                        <button type="button" onclick="shareActiveDeviceWa()" style="flex: 1; min-width: 130px; background: rgba(37, 211, 102, 0.15); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 800; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;">
                            <span>💬</span>
                            <span>WhatsApp</span>
                        </button>
                        <a id="activeDeviceDirectLink" href="#" target="_blank" style="background: rgba(255,255,255,0.06); color: #FFFFFF; border: 1px solid rgba(255,255,255,0.18); font-weight: 800; font-size: 0.82rem; padding: 0.65rem 0.9rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;">
                            <span>🚀</span>
                            <span>Probar</span>
                        </a>
                    </div>

                    <div style="margin-top: 0.85rem; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.02); border-radius: 10px; border: 1px dashed rgba(255,255,255,0.12); width: 100%;">
                        <p style="margin: 0; font-size: 0.72rem; color: #94A3B8; line-height: 1.35;">
                            📸 <strong>Instrucciones:</strong> Apunta la cámara del celular a este código QR para abrir el scanner. El dispositivo se conectará identificado automáticamente como <strong style="color: #FFFFFF;" id="activeDeviceInstructionName">-</strong>.
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const eventId = {{ $event->id }};
        const eventTitle = "{{ addslashes($event->title) }}";
        const verifyUrl = "{{ route('web.attendees.verify_qr', $event->id) }}";
        const csrfToken = "{{ csrf_token() }}";

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
                // Dispositivo por defecto
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
            scannerDevices.forEach((dev, index) => {
                const isActive = dev.id === activeDeviceId;
                const card = document.createElement('div');
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
                            <strong style="font-size: 0.88rem; color: #FFFFFF; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${dev.name}
                            </strong>
                            <span style="font-family: monospace; font-size: 0.72rem; color: ${isActive ? '#00F0FF' : '#94A3B8'}; font-weight: 700;">
                                ${dev.hash || 'VGDEV'}
                            </span>
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

            const titleEl = document.getElementById('activeDeviceTitle');
            const hashBadge = document.getElementById('activeDeviceHashBadge');
            const qrImg = document.getElementById('activeDeviceQrImg');
            const urlInput = document.getElementById('activeDeviceUrlInput');
            const directLink = document.getElementById('activeDeviceDirectLink');
            const instName = document.getElementById('activeDeviceInstructionName');

            if (titleEl) titleEl.textContent = dev.name;
            if (hashBadge) hashBadge.textContent = `HASH: ${dev.hash || 'VGDEV'}`;
            if (qrImg) qrImg.src = qrApiUrl;
            if (urlInput) urlInput.value = url;
            if (directLink) directLink.href = url;
            if (instName) instName.textContent = dev.name;
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
                    Swal.fire({
                        icon: 'success',
                        title: '✓ ¡Acceso Permitido!',
                        html: `
                            <div style="font-size: 0.95rem; margin-top: 0.5rem;">
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

            const tr = document.createElement('tr');
            tr.className = 'checkin-row-item row-highlight-new';
            tr.id = `checkinRow_${ticket.id}`;
            tr.innerHTML = `
                <td><span style="font-weight: 800; color: #10B981;">NUEVO</span></td>
                <td>
                    <span style="font-family: monospace; font-weight: 800; color: #FFFFFF; font-size: 0.9rem;">${ticket.ticket_code}</span>
                    <small style="display: block; font-family: monospace; color: #FF7733; font-size: 0.75rem; font-weight: 800;">🔑 ${ticket.validation_hash || ''}</small>
                </td>
                <td><span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">${ticket.zone_name}</span></td>
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

            if (issuedEl) issuedEl.textContent = m.tickets_issued;
            if (checkedEl) checkedEl.textContent = m.checked_in_count;
            if (pendingEl) pendingEl.textContent = m.pending_count;
            if (rateEl) rateEl.textContent = `${m.attendance_rate}%`;
        }

        // Sincronización automática y a demanda en tiempo real
        let latestCheckinId = {{ $recentCheckins->first() ? $recentCheckins->first()->id : 0 }};
        let isSyncing = false;

        function syncFeedData(isManual = false) {
            if (isSyncing) return;
            isSyncing = true;

            const icon = document.getElementById('refreshIcon');
            if (isManual && icon) {
                icon.style.display = 'inline-block';
                icon.style.animation = 'spin 0.8s linear infinite';
            }

            const sinceParam = isManual ? 0 : latestCheckinId;

            fetch(`/admin/asistentes/${eventId}/checkins-feed?since_id=${sinceParam}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.new_checkins && data.new_checkins.length > 0) {
                        if (isManual) {
                            const tbody = document.getElementById('checkinsTableBody');
                            if (tbody) tbody.innerHTML = '';
                        }

                        data.new_checkins.forEach(t => {
                            if (t.id > latestCheckinId) {
                                latestCheckinId = t.id;
                                appendCheckinRow(t);
                            } else if (isManual) {
                                appendCheckinRow(t);
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
                    }

                    if (data.metrics) {
                        updateKpis(data.metrics);
                    }

                    if (data.zones) {
                        data.zones.forEach(z => {
                            const zCard = document.querySelector(`.pos-zone-card[data-zone-name="${z.name}"]`);
                            if (zCard) {
                                const checkedEl = zCard.querySelector('.zone-checked-count');
                                const pendingEl = zCard.querySelector('.zone-pending-count');
                                const progressEl = zCard.querySelector('.zone-progress-bar');
                                const badge = zCard.querySelector('.dash-badge-custom');

                                if (checkedEl) checkedEl.textContent = z.checked_in;
                                if (pendingEl) pendingEl.textContent = z.pending;
                                if (progressEl) progressEl.style.width = `${z.rate}%`;
                                if (badge) badge.textContent = `${z.rate}% ingresaron`;
                            }
                        });
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
                // Programar la siguiente sincronización automática en 3 segundos solo si la pestaña está activa
                if (!isManual) {
                    setTimeout(scheduleAutoSync, 3000);
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
                setTimeout(scheduleAutoSync, 3000);
            }
        }

        // Iniciar sincronización automática en vivo
        setTimeout(scheduleAutoSync, 3000);

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
