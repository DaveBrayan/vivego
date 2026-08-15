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
        <!-- SIDEBAR DE NAVEGACIÓN PRO MAX -->
        <aside class="dash-sidebar" id="dashSidebar">
            <div class="dash-sidebar-header">
                <a href="{{ route('web.home') }}" class="dash-brand-logo">
                    <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" alt="Vive Go" class="dash-logo-img logo-white-img">
                    <img src="{{ asset($settings->logo_dark ?? 'images/logo.png') }}" alt="Vive Go" class="dash-logo-img logo-dark-img">
                </a>
                <button class="dash-sidebar-toggle-btn" id="dashSidebarToggle" aria-label="Colapsar Menú" title="Plegar / Expandir Menú">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
            </div>

            <!-- Perfil rápido de organizador -->
            <div class="dash-organizer-pill-card">
                <div class="dash-avatar-wrapper">
                    <img src="{{ $organizer['avatar'] }}" alt="{{ $organizer['name'] }}" class="dash-avatar-img">
                    <span class="dash-online-status-dot"></span>
                </div>
                <div class="dash-organizer-info">
                    <h4 class="dash-organizer-name" title="{{ $organizer['name'] }}">{{ $organizer['name'] }}</h4>
                    <span class="dash-verified-badge">✓ {{ $organizer['status'] }}</span>
                </div>
            </div>

            <!-- Menú de Navegación Principal -->
            <nav class="dash-nav-menu">
                <div class="dash-nav-section-title">MENÚ PRINCIPAL</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.dashboard') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">📊</span>
                            <span class="dash-nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.events') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎟️</span>
                            <span class="dash-nav-text">Mis Eventos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.box_office') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">💰</span>
                            <span class="dash-nav-text">Taquilla & Ventas</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">📈</span>
                            <span class="dash-nav-text">Analíticas Pro</span>
                        </a>
                    </li>
                    <li class="dash-nav-item active">
                        <a href="{{ route('web.attendees') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">👥</span>
                            <span class="dash-nav-text">Asistentes & Scanner</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">GESTIÓN & HERRAMIENTAS</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.categories') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">📂</span>
                            <span class="dash-nav-text">Categorías</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.templates') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎨</span>
                            <span class="dash-nav-text">Plantillas de Boletos</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">INFORMACIÓN EMPRESARIAL</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.companies') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🏢</span>
                            <span class="dash-nav-text">Compañía</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.managers') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">👔</span>
                            <span class="dash-nav-text">Responsables</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">SISTEMA & AJUSTES</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.admins') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🛡️</span>
                            <span class="dash-nav-text">Administradores</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.settings') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">⚙️</span>
                            <span class="dash-nav-text">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

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
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
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
                        <div>
                            <button type="button" class="btn btn-secondary btn-sm" id="btnManualRefresh" onclick="manualRefreshFeed()" style="font-weight: 800; font-size: 0.85rem; padding: 0.55rem 1.1rem; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.45rem; cursor: pointer;">
                                <span id="refreshIcon">🔄</span>
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
                                </tr>
                            </thead>
                            <tbody id="checkinsTableBody">
                                @forelse($recentCheckins as $idx => $chk)
                                    <tr class="checkin-row-item">
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $idx + 1) }}</span>
                                        </td>
                                        <td>
                                            <span style="font-family: monospace; font-weight: 800; color: #FFFFFF; font-size: 0.9rem;">
                                                {{ $chk->ticket_code }}
                                            </span>
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
                                    </tr>
                                @empty
                                    <tr id="emptyCheckinsRow">
                                        <td colspan="8" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
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
    <script>
        const eventId = {{ $event->id }};
        const eventTitle = "{{ addslashes($event->title) }}";
        const verifyUrl = "{{ route('web.attendees.verify_qr', $event->id) }}";
        const csrfToken = "{{ csrf_token() }}";

        let isProcessingScan = false;

        // Obtener la URL absoluta del scanner móvil según el dominio/IP actual
        function getMobileScannerUrl() {
            return `${window.location.origin}/scanner/${eventId}`;
        }

        // Copiar enlace directo del scanner móvil al portapapeles
        function copyMobileScannerLink() {
            const url = getMobileScannerUrl();
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '📋 ¡Enlace del Scanner Copiado!',
                        text: 'Pégalo o compártelo con el personal de puerta.',
                        showConfirmButton: false,
                        timer: 3000,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }).catch(() => fallbackCopy(url));
            } else {
                fallbackCopy(url);
            }
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
                timer: 2500,
                background: '#14141E',
                color: '#FFFFFF'
            });
        }

        // Compartir enlace del scanner por WhatsApp
        function shareViaWhatsapp() {
            const url = getMobileScannerUrl();
            const message = `🎟️ *VIVE GO - TERMINAL DE CONTROL DE ACCESO*\n\nHola, aquí tienes el enlace del *Scanner Móvil* para el evento:\n👉 *${eventTitle}*\n\n🔗 ${url}\n\n_Abre este enlace desde tu celular para escanear las entradas._`;
            const waUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        // Abrir Modal con QR y Opciones de Compartir para Celulares
        function openMobileScannerModal() {
            const url = getMobileScannerUrl();
            const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=280x280&margin=10&data=${encodeURIComponent(url)}`;

            // Copiar link automáticamente al portapapeles
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).catch(() => {});
            }

            Swal.fire({
                title: '📱 Terminal de Escaneo Móvil',
                html: `
                    <div style="text-align: center; padding: 0.5rem 0;">
                        <p style="color: #94A3B8; font-size: 0.88rem; margin-bottom: 1.25rem; line-height: 1.4;">
                            Apunta la cámara de tu smartphone hacia este código QR para abrir el <strong>Scanner Móvil</strong> sin necesidad de instalar apps:
                        </p>
                        <div style="background: #FFFFFF; padding: 0.85rem; border-radius: 18px; display: inline-block; box-shadow: 0 6px 25px rgba(0,0,0,0.6); margin-bottom: 1.25rem;">
                            <img src="${qrApiUrl}" alt="QR Móvil" style="width: 220px; height: 220px; display: block; border-radius: 8px;">
                        </div>
                        
                        <!-- Caja de enlace -->
                        <div style="background: rgba(255,255,255,0.05); padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(0,240,255,0.25); word-break: break-all; font-family: monospace; font-size: 0.82rem; color: #00F0FF; margin-bottom: 1.25rem;">
                            ${url}
                        </div>

                        <!-- Botones de Acción -->
                        <div style="display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap;">
                            <button type="button" onclick="navigator.clipboard.writeText('${url}'); this.textContent='✓ ¡Link Copiado!'; setTimeout(() => this.textContent='📋 Copiar Enlace', 2500);" style="background: linear-gradient(135deg, #FF5500, #FF7733); color: #FFFFFF; border: none; font-weight: 800; font-size: 0.85rem; padding: 0.65rem 1.15rem; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 12px rgba(255,85,0,0.4);">
                                📋 Copiar Enlace
                            </button>
                            <button type="button" onclick="shareViaWhatsapp()" style="background: rgba(37, 211, 102, 0.15); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 800; font-size: 0.85rem; padding: 0.65rem 1.15rem; border-radius: 10px; cursor: pointer;">
                                💬 WhatsApp
                            </button>
                            <a href="${url}" target="_blank" style="background: rgba(255,255,255,0.08); color: #FFFFFF; border: 1px solid rgba(255,255,255,0.2); font-weight: 800; font-size: 0.85rem; padding: 0.65rem 1.15rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                                🚀 Abrir Scanner
                            </a>
                        </div>
                    </div>
                `,
                background: '#14141E',
                color: '#FFFFFF',
                showConfirmButton: true,
                confirmButtonText: 'Entendido / Cerrar',
                confirmButtonColor: '#FF5500'
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
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Boleto Inválido',
                        html: `<p style="color: #EF4444; font-weight: 700;">${body.message || 'El código no corresponde a este evento.'}</p>`,
                        timer: 4000,
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

            const tr = document.createElement('tr');
            tr.className = 'checkin-row-item row-highlight-new';
            tr.innerHTML = `
                <td><span style="font-weight: 800; color: #10B981;">NUEVO</span></td>
                <td><span style="font-family: monospace; font-weight: 800; color: #FFFFFF; font-size: 0.9rem;">${ticket.ticket_code}</span></td>
                <td><span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">${ticket.zone_name}</span></td>
                <td><strong style="color: #FFFFFF;">${ticket.buyer_name}</strong></td>
                <td><span style="color: #94A3B8; font-family: monospace;">${ticket.buyer_dni || '-'}</span></td>
                <td>
                    <span style="color: #00F0FF; font-weight: 700;">${ticket.checked_in_at}</span>
                    <small style="display: block; color: #64748B; font-size: 0.7rem;">${ticket.checked_in_date || 'Hoy'}</small>
                </td>
                <td><span style="color: #E2E8F0; font-size: 0.85rem;">${ticket.scanned_by || 'Puerta Principal'}</span></td>
                <td><span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">✓ Ingresado</span></td>
            `;

            if (tbody.firstChild) {
                tbody.insertBefore(tr, tbody.firstChild);
            } else {
                tbody.appendChild(tr);
            }
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
            // Sidebar Toggle
            const sidebar = document.getElementById('dashSidebar');
            const toggleBtn = document.getElementById('dashSidebarToggle');
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.add('dash-animating');
                    sidebar.classList.toggle('collapsed');
                    setTimeout(function () { sidebar.classList.remove('dash-animating'); }, 450);
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
        });
    </script>
@endpush
