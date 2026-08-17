@extends('layouts.app')

@section('title', 'Mis Eventos | Vive Go')

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
                    <li class="dash-nav-item active">
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
                    <li class="dash-nav-item">
                        <a href="{{ route('web.attendees') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">👥</span>
                            <span class="dash-nav-text">Asistentes</span>
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
                            <span class="dash-nav-icon">👤</span>
                            <span class="dash-nav-text">Responsable</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.capacity_types') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🏟️</span>
                            <span class="dash-nav-text">Tipos de Aforo</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">ADMINISTRACIÓN</div>
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

            <!-- Footer Sidebar: Botón Salir -->
            <div class="dash-sidebar-footer">
                <a href="{{ route('web.home') }}" class="dash-btn-logout" title="Cerrar Sesión">
                    <span class="dash-btn-logout-icon">🚪</span>
                    <span class="dash-btn-logout-text">Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar evento, recinto o categoría...">
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
                <!-- NOTIFICACIONES DE ÉXITO -->
                @if(session('success'))
                    <div class="alert-custom alert-success">
                        <div class="alert-icon-box">✓</div>
                        <div class="alert-content">
                            <h4>¡Operación Exitosa!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()" title="Cerrar Notificación">✕</button>
                    </div>
                @endif

                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">🎟️ GESTIÓN DE EVENTOS & CATÁLOGO</span>
                        <h1 class="settings-page-title">Mis Eventos</h1>
                        <p class="settings-page-subtitle">Administra tu catálogo de conciertos, espectáculos, obras de teatro y festivales en tiempo real.</p>
                    </div>
                    <div>
                        <a href="{{ route('web.events.create') }}" class="btn btn-primary btn-save-settings" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; text-decoration: none;">
                            ➕ Crear Nuevo Evento
                        </a>
                    </div>
                </div>

                <!-- TABLA DE EVENTOS -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">🎟️</div>
                        <div>
                            <h3 class="card-header-title">Catálogo Oficial de Eventos</h3>
                            <p class="card-header-subtitle">Lista de eventos registrados para venta en el marketplace Vive Go</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="eventsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Evento & Categoría</th>
                                    <th>Fecha & Hora</th>
                                    <th>Ventas & Aforo</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $index => $evt)
                                    <tr>
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.9rem;">
                                                <div style="width: 54px; height: 54px; border-radius: 14px; overflow: hidden; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.15); background: #0A0A10;">
                                                    <img src="{{ $evt['image'] }}" alt="{{ $evt['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <a href="{{ route('web.event.detail', $evt['slug']) }}" class="dash-event-name" style="display: block; font-size: 0.95rem;" title="{{ $evt['title'] }}">{{ $evt['title'] }}</a>
                                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.2rem; flex-wrap: wrap;">
                                                        <span class="dash-badge-custom badge-blue" style="font-size: 0.7rem; padding: 0.15rem 0.6rem;">{{ $evt['category_icon'] }} {{ $evt['category'] }}</span>
                                                        @if(($evt['sales_type'] ?? 'fisica') === 'fisica')
                                                            <span class="dash-badge-custom badge-orange" style="font-size: 0.7rem; padding: 0.15rem 0.6rem;">🎫 Venta Física</span>
                                                        @else
                                                            <span class="dash-badge-custom badge-cyan" style="font-size: 0.7rem; padding: 0.15rem 0.6rem; color: #00F0FF; border: 1px solid rgba(0,240,255,0.4); background: rgba(0,240,255,0.1);">🌐 Venta Virtual</span>
                                                        @endif
                                                        <small style="color: #94A3B8; font-weight: 600; font-size: 0.775rem;">📍 {{ $evt['venue'] }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 0.15rem;">
                                                <span class="admin-email-text" style="font-weight: 700;">🗓️ {{ $evt['date_formatted'] }}</span>
                                                <small style="color: #94A3B8; font-weight: 600;">⏰ {{ $evt['time_formatted'] }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width: 140px;">
                                                <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.85rem; font-weight: 800; margin-bottom: 0.35rem;">
                                                    <span style="color: #FFFFFF; font-weight: 900; letter-spacing: 0.3px;" class="event-capacity-text"><strong>{{ $evt['tickets_sold'] }}</strong> / {{ $evt['total_capacity'] }}</span>
                                                    <span style="color: var(--color-primary-orange); font-weight: 900; font-size: 0.85rem;">{{ $evt['capacity_percentage'] }}%</span>
                                                </div>
                                                <div style="width: 100%; height: 7px; background: rgba(255,255,255,0.12); border-radius: 10px; overflow: hidden;" class="event-progress-bg">
                                                    <div style="height: 100%; width: {{ $evt['capacity_percentage'] }}%; background: linear-gradient(90deg, #FF5500, #FF1E3C); border-radius: 10px; transition: width 0.4s ease;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="dash-badge-custom {{ $evt['status_class'] }}">
                                                @if($evt['status'] === 'Publicado') ✓ @elseif($evt['status'] === 'Agotado') 🚫 @else ⏳ @endif {{ $evt['status'] }}
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="dash-actions-cell" style="justify-content: flex-end;">
                                                <a href="{{ route('web.event.detail', $evt['slug']) }}" class="dash-btn-icon-action" title="Previsualizar Evento" target="_blank" style="color: var(--color-neon-cyan);">👁️</a>
                                                <a href="{{ route('web.events.edit', $evt['id']) }}" class="dash-btn-icon-action" title="Editar Evento">✏️</a>
                                                <button type="button" class="dash-btn-icon-action btn-generate-pdf-tickets" data-event='@json($evt)' title="Generar Boletos PDF" style="color: #EAB308;">🖨️</button>
                                                <button type="button" class="dash-btn-icon-action btn-delete-event" data-id="{{ $evt['id'] }}" data-title="{{ $evt['title'] }}" title="Eliminar Evento" style="color: #FF1E3C;">🗑️</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL GENERAR BOLETOS PDF CON 2 PASOS (1. Generar Códigos -> 2. Descargar PDF) -->
    <div class="admin-modal-overlay" id="generateTicketsModal">
        <div class="admin-modal-card" style="max-width: 640px; width: 95%; max-height: 90vh; overflow-y: auto; padding: 2rem; border-radius: 28px; box-sizing: border-box;">
            <div class="admin-modal-header" style="margin-bottom: 1.5rem; padding-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 44px; height: 44px; background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">🖨️</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.2rem;">Generador de Boletos PDF & Control</h3>
                        <p class="card-header-subtitle">Genera primero los códigos QR para registrarlos en base de datos y luego descarga el PDF oficial.</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseGenerateModal" type="button">✕</button>
            </div>

            <form id="formGeneratePdfTickets" class="admin-modal-form" onsubmit="return false;">
                
                <div class="form-group-custom" style="margin-bottom: 1.15rem;">
                    <label class="form-label-custom">Espectáculo / Evento</label>
                    <input type="text" id="gen_event_title" class="form-input-custom" readonly style="background: rgba(255,255,255,0.05); color: #FFFFFF; font-weight: 800;">
                </div>

                <!-- Zona / Sector -->
                <div class="form-group-custom" style="margin-bottom: 1.15rem;">
                    <label for="gen_zone" class="form-label-custom">Zona / Sector <span class="required-star">*</span></label>
                    <select id="gen_zone" class="form-select-custom" required onchange="onZoneSelectChange(true)">
                        <option value="ZONA VIP|55.50|100">🎟️ ZONA VIP (S/ 55.50) — Aforo: 100 boletos</option>
                    </select>
                    <input type="hidden" id="gen_quantity" value="100">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.15rem; margin-bottom: 1.15rem;">
                    <!-- Boletos por Hoja -->
                    <div class="form-group-custom">
                        <label for="gen_per_page" class="form-label-custom">Boletos por Hoja <span class="required-star">*</span></label>
                        <select id="gen_per_page" class="form-select-custom" required onchange="updateEstimatedPages()">
                            <option value="1">1 por hoja</option>
                            <option value="2">2 por hoja</option>
                            <option value="3">3 por hoja</option>
                            <option value="4">4 por hoja</option>
                            <option value="5" selected>5 por hoja (Predeterminado)</option>
                        </select>
                    </div>

                    <!-- Tamaño de Hoja -->
                    <div class="form-group-custom">
                        <label for="gen_paper_size" class="form-label-custom">Tamaño de Hoja <span class="required-star">*</span></label>
                        <select id="gen_paper_size" class="form-select-custom" required onchange="updateEstimatedPages()">
                            <option value="A4" selected>📄 A4</option>
                            <option value="Letter">📄 Carta</option>
                            <option value="A5">📄 A5</option>
                            <option value="80mm">🧾 Térmico 80mm</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.15rem; margin-bottom: 1.25rem;">
                    <!-- Correlativo Inicial -->
                    <div class="form-group-custom">
                        <label for="gen_start_num" class="form-label-custom">Correlativo Inicial (N°) <span class="required-star">*</span></label>
                        <input type="number" id="gen_start_num" class="form-input-custom" value="1" min="1" required placeholder="Ej. 1" oninput="resetGeneratedCodesState()">
                    </div>

                    <!-- Orientación -->
                    <div class="form-group-custom">
                        <label for="gen_orientation" class="form-label-custom">Orientación <span class="required-star">*</span></label>
                        <select id="gen_orientation" class="form-select-custom" required>
                            <option value="portrait" selected>📱 Vertical</option>
                            <option value="landscape">🖥️ Horizontal</option>
                        </select>
                    </div>
                </div>

                <!-- RESUMEN RÁPIDO DE HOJAS TOTALES -->
                <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); padding: 0.9rem 1.15rem; border-radius: 16px; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem;">
                        <span style="color: #94A3B8; font-weight: 700;">📄 Hojas Estimadas a Imprimir:</span>
                        <strong id="estimatedPagesText" style="color: var(--color-neon-cyan); font-weight: 900; font-size: 1.05rem;">17 Hojas A4</strong>
                    </div>
                </div>

                <!-- CAJA DE ESTADO DE LOS CÓDIGOS -->
                <div id="codesStatusBox" style="background: rgba(255,85,0,0.08); border: 1.5px dashed rgba(255,85,0,0.3); border-radius: 16px; padding: 1rem 1.15rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                    <span id="codesStatusIcon" style="font-size: 1.5rem;">⚠️</span>
                    <div>
                        <strong id="codesStatusTitle" style="color: #FF7733; font-size: 0.9rem; display: block;">Paso 1 Obligatorio: Generar Códigos QR</strong>
                        <p id="codesStatusDesc" style="color: #94A3B8; font-size: 0.78rem; margin: 0;">Debes generar primero los códigos y guardarlos en la base de datos para habilitar la descarga de entradas.</p>
                    </div>
                </div>

                <!-- ACCIONES CON LOS BOTONES -->
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <div id="modalButtonsGrid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
                        <!-- BOTÓN 1: Generar Códigos (Solo visible para eventos nuevos sin boletos en BD) -->
                        <button type="button" class="btn btn-primary" id="btnGenerateCodes" onclick="handleGenerateCodesStep()" style="padding: 0.85rem 1rem; font-size: 0.92rem; font-weight: 900; background: linear-gradient(135deg, #00F0FF, #0070F3); border-color: #00F0FF; color: #0A0A10; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.35); cursor: pointer;">
                            <span>⚡ 1. Generar Códigos</span>
                        </button>

                        <!-- BOTÓN 2: Generar y Descargar PDF -->
                        <button type="button" class="btn btn-primary btn-save-settings" id="btnDownloadPdf" onclick="handleDownloadPdfStep()" disabled style="padding: 0.85rem 1rem; font-size: 0.92rem; font-weight: 900; opacity: 0.45; cursor: not-allowed; pointer-events: none;">
                            <span>🖨️ 2. Generar PDF</span>
                        </button>
                    </div>

                    <button type="button" class="btn btn-cancel-custom" id="btnCancelGenerateModal" style="width: 100%; text-align: center; padding: 0.7rem;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        let selectedEventForPdf = null;
        let generatedTicketsQueue = [];
        let isCodesLocked = false;

        function generateUniqueTicketHash() {
            let randomHex = '';
            if (window.crypto && window.crypto.getRandomValues) {
                const bytes = new Uint8Array(16);
                window.crypto.getRandomValues(bytes);
                randomHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('').toUpperCase();
            } else {
                randomHex = (Math.random().toString(36).substring(2) + Math.random().toString(36).substring(2) + Math.random().toString(36).substring(2)).substring(0, 32).toUpperCase();
            }
            const validationHash = 'VG' + randomHex.substring(0, 8);
            const qrPayload = 'VG-' + randomHex;
            return { validationHash, qrPayload };
        }

        function getFallbackLogoSvgDataUrl() {
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 80" width="320" height="80">
                <defs>
                    <linearGradient id="vvg" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FF5500" />
                        <stop offset="100%" stop-color="#FF1E3C" />
                    </linearGradient>
                </defs>
                <text x="10" y="56" font-family="'Plus Jakarta Sans', Arial, sans-serif" font-weight="900" font-size="44" fill="#FFFFFF" letter-spacing="1">VIVE</text>
                <text x="140" y="56" font-family="'Plus Jakarta Sans', Arial, sans-serif" font-weight="900" font-size="44" fill="url(#vvg)" letter-spacing="1">GO</text>
            </svg>`;
            return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
        }

        function getFallbackBannerSvgDataUrl(titleText) {
            const safeTitle = (titleText || 'VIVE GO EVENT').replace(/[<>&"]/g, '');
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 240" width="800" height="240">
                <defs>
                    <linearGradient id="bgG" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#0F0F1A" />
                        <stop offset="50%" stop-color="#1F132B" />
                        <stop offset="100%" stop-color="#FF5500" />
                    </linearGradient>
                </defs>
                <rect width="100%" height="100%" fill="url(#bgG)" rx="14"/>
                <circle cx="700" cy="40" r="120" fill="rgba(255,85,0,0.25)"/>
                <text x="40" y="95" font-family="'Plus Jakarta Sans', Arial, sans-serif" font-weight="900" font-size="30" fill="#FFFFFF">${safeTitle.substring(0, 38)}</text>
                <text x="40" y="145" font-family="'Plus Jakarta Sans', Arial, sans-serif" font-weight="800" font-size="16" fill="#FF5500">BOLETO OFICIAL DE ACCESO • VIVE GO</text>
            </svg>`;
            return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
        }

        async function preloadImageAsDataUrl(url, type = 'banner', fallbackText = '') {
            if (!url || typeof url !== 'string' || url.trim() === '') {
                return type === 'logo' ? getFallbackLogoSvgDataUrl() : getFallbackBannerSvgDataUrl(fallbackText);
            }
            if (url.startsWith('data:')) {
                return url;
            }

            // 1. Intentar Fetch con CORS
            try {
                const response = await fetch(url, { mode: 'cors', cache: 'force-cache' });
                if (response.ok) {
                    const blob = await response.blob();
                    const dataUrl = await new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.onerror = reject;
                        reader.readAsDataURL(blob);
                    });
                    if (dataUrl && dataUrl.startsWith('data:image')) {
                        return dataUrl;
                    }
                }
            } catch (e) {
                // Fetch omitido o bloqueado, continuar a método Image/Canvas
            }

            // 2. Intentar Image() con Canvas export
            try {
                const dataUrl = await new Promise((resolve, reject) => {
                    const img = new Image();
                    img.crossOrigin = 'Anonymous';
                    const timeout = setTimeout(() => {
                        reject(new Error('Timeout loading image'));
                    }, 3500);

                    img.onload = () => {
                        clearTimeout(timeout);
                        try {
                            const canvas = document.createElement('canvas');
                            canvas.width = img.naturalWidth || 600;
                            canvas.height = img.naturalHeight || 300;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0);
                            const res = canvas.toDataURL('image/jpeg', 0.92);
                            resolve(res);
                        } catch (err) {
                            reject(err);
                        }
                    };
                    img.onerror = () => {
                        clearTimeout(timeout);
                        reject(new Error('Image error'));
                    };
                    img.src = url;
                });
                return dataUrl;
            } catch (e) {
                return type === 'logo' ? getFallbackLogoSvgDataUrl() : getFallbackBannerSvgDataUrl(fallbackText);
            }
        }

        function setModalLockedState(count) {
            isCodesLocked = true;
            const statusBox = document.getElementById('codesStatusBox');
            const statusIcon = document.getElementById('codesStatusIcon');
            const statusTitle = document.getElementById('codesStatusTitle');
            const statusDesc = document.getElementById('codesStatusDesc');
            const btnGenCodes = document.getElementById('btnGenerateCodes');
            const btnDownPdf = document.getElementById('btnDownloadPdf');
            const btnGrid = document.getElementById('modalButtonsGrid');
            const zoneSelect = document.getElementById('gen_zone');
            const startNumInput = document.getElementById('gen_start_num');

            if (zoneSelect) zoneSelect.disabled = true;
            if (startNumInput) startNumInput.disabled = true;

            if (statusBox) {
                statusBox.style.background = 'rgba(16, 185, 129, 0.12)';
                statusBox.style.borderColor = '#10B981';
                if (statusIcon) statusIcon.textContent = '🔒';
                if (statusTitle) {
                    statusTitle.textContent = `¡Lote de Boletos Bloqueado & Protegido! (${count} Códigos Registrados)`;
                    statusTitle.style.color = '#10B981';
                }
                if (statusDesc) {
                    statusDesc.textContent = `Este evento ya cuenta con ${count} boletos emitidos con códigos QR hasheados en la Base de Datos. Puedes ajustar el diseño y volver a generar el PDF cuantas veces desees; los códigos no cambiarán.`;
                }
            }

            if (btnGrid) {
                btnGrid.style.gridTemplateColumns = '1fr';
            }

            if (btnGenCodes) {
                btnGenCodes.style.display = 'none';
            }

            if (btnDownPdf) {
                btnDownPdf.disabled = false;
                btnDownPdf.style.opacity = '1';
                btnDownPdf.style.cursor = 'pointer';
                btnDownPdf.style.pointerEvents = 'auto';
                btnDownPdf.style.background = 'linear-gradient(135deg, #FF5500, #FF7733)';
                btnDownPdf.style.boxShadow = '0 4px 18px rgba(255, 85, 0, 0.45)';
                btnDownPdf.innerHTML = `<span>🖨️ Generar y Descargar Boletos PDF (${count} Boletos)</span>`;
            }
        }

        function resetGeneratedCodesState() {
            if (isCodesLocked) return;

            generatedTicketsQueue = [];
            
            const statusBox = document.getElementById('codesStatusBox');
            const statusIcon = document.getElementById('codesStatusIcon');
            const statusTitle = document.getElementById('codesStatusTitle');
            const statusDesc = document.getElementById('codesStatusDesc');
            const btnGenCodes = document.getElementById('btnGenerateCodes');
            const btnDownPdf = document.getElementById('btnDownloadPdf');
            const btnGrid = document.getElementById('modalButtonsGrid');
            const zoneSelect = document.getElementById('gen_zone');
            const startNumInput = document.getElementById('gen_start_num');

            if (zoneSelect) zoneSelect.disabled = false;
            if (startNumInput) startNumInput.disabled = false;

            if (statusBox) {
                statusBox.style.background = 'rgba(255,85,0,0.08)';
                statusBox.style.borderColor = 'rgba(255,85,0,0.3)';
                if (statusIcon) statusIcon.textContent = '⚠️';
                if (statusTitle) {
                    statusTitle.textContent = 'Paso 1 Obligatorio: Generar Códigos QR Hasheados';
                    statusTitle.style.color = '#FF7733';
                }
                if (statusDesc) statusDesc.textContent = 'Debes generar primero los códigos seguros y guardarlos en la base de datos para habilitar la descarga de entradas.';
            }

            if (btnGrid) {
                btnGrid.style.gridTemplateColumns = '1fr 1fr';
            }

            if (btnGenCodes) {
                btnGenCodes.style.display = 'inline-flex';
                btnGenCodes.innerHTML = '<span>⚡ 1. Generar Códigos</span>';
                btnGenCodes.style.background = 'linear-gradient(135deg, #00F0FF, #0070F3)';
                btnGenCodes.style.borderColor = '#00F0FF';
                btnGenCodes.style.color = '#0A0A10';
            }

            if (btnDownPdf) {
                btnDownPdf.disabled = true;
                btnDownPdf.style.opacity = '0.45';
                btnDownPdf.style.cursor = 'not-allowed';
                btnDownPdf.style.pointerEvents = 'none';
                btnDownPdf.style.boxShadow = 'none';
                btnDownPdf.innerHTML = '<span>🖨️ 2. Generar PDF</span>';
            }
        }

        function onZoneSelectChange(isUserAction = false) {
            if (isCodesLocked) return;

            if (isUserAction) {
                resetGeneratedCodesState();
            }
            const zoneSelect = document.getElementById('gen_zone');
            if (!zoneSelect) return;
            const qtyInput = document.getElementById('gen_quantity');
            
            if (zoneSelect.value === 'ALL') {
                const defaultZones = [
                    { name: 'BOX PLATINUM INDIVIDUAL', price: 150.00, capacity: 10 },
                    { name: 'ZONA VIP STAND UP', price: 95.00, capacity: 20 },
                    { name: 'ZONA GENERAL', price: 55.50, capacity: 30 }
                ];
                const zones = (selectedEventForPdf && selectedEventForPdf.zones && selectedEventForPdf.zones.length > 0)
                    ? selectedEventForPdf.zones
                    : defaultZones;
                const totalCap = zones.reduce((sum, z) => sum + (parseInt(z.capacity || z.stock, 10) || 10), 0);
                if (qtyInput) qtyInput.value = totalCap;
            } else {
                const parts = zoneSelect.value.split('|');
                const capacity = parseInt(parts[2], 10) || 10;
                if (qtyInput) qtyInput.value = capacity;
            }
            updateEstimatedPages();
        }

        function updateEstimatedPages() {
            let totalQty = 60;
            if (generatedTicketsQueue && generatedTicketsQueue.length > 0) {
                totalQty = generatedTicketsQueue.length;
            } else {
                totalQty = parseInt(document.getElementById('gen_quantity')?.value, 10) || 60;
            }
            const perPage = parseInt(document.getElementById('gen_per_page')?.value, 10) || 5;
            const paperSize = document.getElementById('gen_paper_size')?.value || 'A4';
            
            const totalPages = Math.ceil(totalQty / perPage);
            const estPagesText = document.getElementById('estimatedPagesText');
            if (estPagesText) {
                estPagesText.textContent = `${totalPages} ${totalPages === 1 ? 'Hoja' : 'Hojas'} ${paperSize}`;
            }
        }

        function getQrCodeDataUrl(payload) {
            if (typeof qrcode !== 'undefined') {
                try {
                    const qr = qrcode(0, 'L');
                    qr.addData(payload);
                    qr.make();
                    return qr.createDataURL(6, 0);
                } catch(e) {
                    console.error('Error generando QR DataURL:', e);
                }
            }
            return '';
        }

        // ==========================================
        // PASO 1: GENERAR CÓDIGOS & GUARDAR EN BD
        // ==========================================
        function handleGenerateCodesStep() {
            if (!selectedEventForPdf) {
                Swal.fire({ title: 'Error', text: 'No se seleccionó ningún evento.', icon: 'error', background: '#14141E', color: '#FFF' });
                return;
            }

            if (isCodesLocked) {
                Swal.fire({
                    title: 'Lote Ya Registrado',
                    text: 'Los códigos de este evento ya están registrados y protegidos en la base de datos para garantizar la validez de los boletos impresos.',
                    icon: 'info',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            const zoneVal = document.getElementById('gen_zone').value;
            const quantity = parseInt(document.getElementById('gen_quantity').value, 10) || 60;
            const startNum = parseInt(document.getElementById('gen_start_num').value, 10) || 1;

            const defaultZones = [
                { name: 'BOX PLATINUM INDIVIDUAL', price: 150.00, capacity: 10 },
                { name: 'ZONA VIP STAND UP', price: 95.00, capacity: 20 },
                { name: 'ZONA GENERAL', price: 55.50, capacity: 30 }
            ];

            let ticketsQueue = [];
            let currentNum = startNum;

            if (zoneVal === 'ALL') {
                const eventZones = (selectedEventForPdf.zones && selectedEventForPdf.zones.length > 0)
                    ? selectedEventForPdf.zones
                    : defaultZones;

                eventZones.forEach(z => {
                    const cap = parseInt(z.capacity || z.stock || 100, 10);
                    const p = parseFloat(z.price || 0).toFixed(2);
                    for (let i = 0; i < cap; i++) {
                        const num = currentNum++;
                        const numPad = String(num).padStart(5, '0');
                        const { validationHash, qrPayload } = generateUniqueTicketHash();
                        
                        ticketsQueue.push({
                            ticketNumberVal: num,
                            ticketCode: `N° ${numPad}`,
                            zoneName: z.name,
                            zonePrice: p,
                            validationHash: validationHash,
                            qrPayload: qrPayload,
                            qrDataUrl: getQrCodeDataUrl(qrPayload),
                            buyerName: 'Público General',
                            buyerDni: '00000000',
                            source: 'pdf_batch'
                        });
                    }
                });
            } else {
                const parts = zoneVal.split('|');
                const zoneName = parts[0] || 'ZONA VIP';
                const zonePrice = parseFloat(parts[1] || 55.50).toFixed(2);
                for (let i = 0; i < quantity; i++) {
                    const num = currentNum++;
                    const numPad = String(num).padStart(5, '0');
                    const { validationHash, qrPayload } = generateUniqueTicketHash();

                    ticketsQueue.push({
                        ticketNumberVal: num,
                        ticketCode: `N° ${numPad}`,
                        zoneName: zoneName,
                        zonePrice: zonePrice,
                        validationHash: validationHash,
                        qrPayload: qrPayload,
                        qrDataUrl: getQrCodeDataUrl(qrPayload),
                        buyerName: 'Público General',
                        buyerDni: '00000000',
                        source: 'pdf_batch'
                    });
                }
            }

            // Mostrar estado de guardado
            Swal.fire({
                title: '⚡ Registrando Códigos Hasheados...',
                html: `Guardando <b>${ticketsQueue.length} códigos QR únicos y hasheados</b> en la Base de Datos para el Control de Acceso...`,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            // Enviar a la base de datos
            const payloadForDb = ticketsQueue.map(t => ({
                ticket_code: t.ticketCode,
                ticket_number: t.ticketNumberVal,
                zone_name: t.zoneName,
                unit_price: t.zonePrice,
                validation_hash: t.validationHash,
                qr_payload: t.qrPayload,
                buyer_name: t.buyerName,
                buyer_dni: t.buyerDni,
                source: 'pdf_batch'
            }));

            fetch(`/admin/eventos/${selectedEventForPdf.id}/registrar-boletos-pdf`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tickets: payloadForDb })
            })
            .then(res => res.json())
            .then(data => {
                generatedTicketsQueue = ticketsQueue;
                setModalLockedState(ticketsQueue.length);
                updateEstimatedPages();

                Swal.fire({
                    title: '🎉 ¡Códigos Hasheados y Registrados!',
                    html: `Se han creado y guardado <b>${ticketsQueue.length} boletos con códigos QR hasheados</b> en la Base de Datos.<br><br>🔒 <b>Los códigos ya no cambiarán</b>. Ahora puedes hacer clic en <b>"Generar y Descargar Boletos PDF"</b> cuantas veces desees.`,
                    icon: 'success',
                    confirmButtonColor: '#FF5500',
                    confirmButtonText: 'Entendido, Descargar PDF',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            })
            .catch(err => {
                console.error('Error registrando códigos:', err);
                Swal.fire({
                    title: 'Error al Registrar Códigos',
                    text: 'Ocurrió un inconveniente al guardar los códigos en el servidor. Por favor intenta nuevamente.',
                    icon: 'error',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            });
        }

        // ==========================================
        // PASO 2: GENERAR & DESCARGAR PDF (REUTILIZA CÓDIGOS EXISTENTES)
        // ==========================================
        function handleDownloadPdfStep() {
            if (!generatedTicketsQueue || generatedTicketsQueue.length === 0) {
                Swal.fire({
                    title: 'Paso 1 Requerido',
                    text: 'Debes hacer clic primero en "1. Generar Códigos" para registrar los boletos en la base de datos.',
                    icon: 'warning',
                    confirmButtonColor: '#FF5500',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            const perPage = parseInt(document.getElementById('gen_per_page').value, 10) || 5;
            const paperSize = document.getElementById('gen_paper_size').value || 'A4';
            const orientation = document.getElementById('gen_orientation').value || 'portrait';

            // Cerrar modal
            const modal = document.getElementById('generateTicketsModal');
            if (modal) modal.classList.remove('active');

            Swal.fire({
                title: '⚡ Iniciando Compilador PDF...',
                html: `Preparando <b>${generatedTicketsQueue.length} entradas</b> en alta resolución...`,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            generatePdfPrintWindow(selectedEventForPdf, generatedTicketsQueue, perPage, paperSize, orientation);
        }

        function showPdfSuccessModal(fileName, count, docContent) {
            Swal.fire({
                title: '🎉 ¡Boletos PDF Descargados!',
                html: `Se descargó el archivo <b>"${fileName}"</b> con <b>${count} boletos oficiales</b> listos para imprimir y escanear.<br><br>¿Deseas también abrir el cuadro de impresión directa del navegador?`,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#FF5500',
                cancelButtonColor: '#475569',
                confirmButtonText: '🖨️ Imprimir Directamente',
                cancelButtonText: 'Cerrar',
                background: '#14141E',
                color: '#FFFFFF'
            }).then((res) => {
                if (res.isConfirmed) {
                    openDirectNativePrintWindow(docContent);
                }
            });
        }

        function openDirectNativePrintWindow(docContent) {
            let printContainer = document.getElementById('vivegoNativePrintFrame');
            if (printContainer) printContainer.remove();

            printContainer = document.createElement('iframe');
            printContainer.id = 'vivegoNativePrintFrame';
            printContainer.style.position = 'fixed';
            printContainer.style.left = '-9999px';
            printContainer.style.top = '0';
            printContainer.style.width = '1000px';
            printContainer.style.height = '1400px';
            printContainer.style.border = 'none';
            printContainer.style.background = '#FFFFFF';
            printContainer.style.zIndex = '-999';
            document.body.appendChild(printContainer);

            const frameDoc = printContainer.contentDocument || printContainer.contentWindow.document;
            frameDoc.open();
            frameDoc.write(docContent);
            frameDoc.close();

            setTimeout(function() {
                try {
                    printContainer.contentWindow.focus();
                    printContainer.contentWindow.print();
                } catch(e) {
                    console.error('Error abriendo cuadro de impresión:', e);
                }
            }, 600);
        }

        async function generatePdfPrintWindow(evt, ticketsQueue, perPage, paperSize, orientation) {
            try {
                // 1. PRECARGA DE ASSETS A BASE64 DATA URLS (Garantiza 0 errores de CORS y 0 hojas en blanco en Hosting)
                Swal.update({
                    title: '⚡ 1/3 - Procesando Gráficos & QR Codes...',
                    html: 'Convirtiendo logotipos, imágenes de fondo y códigos QR a alta definición sin dependencias de red...'
                });

                const logoRawUrl = window.location.origin + '/images/logo-white.png';
                const [logoDataUrl, bannerDataUrl] = await Promise.all([
                    preloadImageAsDataUrl(logoRawUrl, 'logo'),
                    preloadImageAsDataUrl(evt.image, 'banner', evt.title)
                ]);

                const tpl = evt.template || { id: 1, name: 'Plantilla 1', bg_color: '#FFFFFF', strip_color: '#000000', positions: {} };
                const bgColor = tpl.bg_color || '#FFFFFF';
                const stripColor = tpl.strip_color || '#000000';
                
                const isPlantilla2 = (tpl.id == 2 || (tpl.name && tpl.name.includes('Plantilla 2')) || (tpl.category && tpl.category.includes('Logo Derecho')));
                const isPlantilla3 = (tpl.id == 3 || (tpl.name && tpl.name.includes('Plantilla 3')) || (tpl.category && tpl.category.includes('Panorámico')));
                const isPlantilla1 = (!isPlantilla2 && !isPlantilla3);

                function isColorDark(hexColor) {
                    if (!hexColor || hexColor.charAt(0) !== '#') return false;
                    const hex = hexColor.substring(1);
                    if (hex.length < 6) return false;
                    const r = parseInt(hex.substr(0, 2), 16) || 0;
                    const g = parseInt(hex.substr(2, 2), 16) || 0;
                    const b = parseInt(hex.substr(4, 2), 16) || 0;
                    const lum = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                    return lum < 0.55;
                }

                const isDarkBg = isColorDark(bgColor);
                const primaryTextColor = isDarkBg ? '#FFFFFF' : '#000000';
                const secondaryTextColor = isDarkBg ? '#E2E8F0' : '#1E293B';
                const mutedTextColor = isDarkBg ? '#94A3B8' : '#475569';

                function formatTime12h(timeStr) {
                    if (!timeStr) return '06:00PM';
                    if (timeStr.includes('AM') || timeStr.includes('PM') || timeStr.includes('am') || timeStr.includes('pm')) {
                        return timeStr.toUpperCase();
                    }
                    const parts = timeStr.split(':');
                    if (parts.length >= 2) {
                        let hours = parseInt(parts[0], 10);
                        const minutes = parts[1];
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12;
                        const strHours = hours < 10 ? '0' + hours : hours;
                        return `${strHours}:${minutes}${ampm}`;
                    }
                    return timeStr;
                }

                const rawDate = evt.date_formatted || '10.04.2025';
                const cleanDate = rawDate.replace(/\//g, '.');
                const cleanTime = formatTime12h(evt.time_formatted || '06:00PM');
                const venue = evt.venue || 'Complejo San Luis';
                const address = evt.city || 'Av. Cusco 528 - AYACUCHO';
                const title = evt.title || 'Chúpate la Plata con Son del Duke en Ayacucho';

                // Detección de tamaño de papel (A4 o Carta)
                const isCarta = (paperSize.toUpperCase() === 'CARTA' || paperSize.toUpperCase() === 'LETTER');
                const isA5 = (paperSize.toUpperCase() === 'A5');
                const pageWidthMm = isCarta ? 215.9 : (isA5 ? 148 : 210);
                const pageHeightMm = isCarta ? 279.4 : (isA5 ? 210 : 297);
                const jsPdfFormat = isCarta ? 'letter' : (isA5 ? 'a5' : 'a4');

                // Parámetros de escalado y proporción panorámica exacta (3.0:1)
                let printCardWidth, printCardHeight, scaleRatio, cardMarginBottom, sheetPaddingTopBottom;

                if (perPage === 5) {
                    if (isCarta) {
                        printCardWidth = '148mm';
                        printCardHeight = '49.33mm';
                        scaleRatio = ((148 * 3.77953) / 900).toFixed(6);
                        cardMarginBottom = '2mm';
                        sheetPaddingTopBottom = '5mm';
                    } else {
                        printCardWidth = '154mm';
                        printCardHeight = '51.33mm';
                        scaleRatio = ((154 * 3.77953) / 900).toFixed(6);
                        cardMarginBottom = '2.5mm';
                        sheetPaddingTopBottom = '6mm';
                    }
                } else if (perPage === 4) {
                    if (isCarta) {
                        printCardWidth = '168mm';
                        printCardHeight = '56mm';
                        scaleRatio = ((168 * 3.77953) / 900).toFixed(6);
                        cardMarginBottom = '4mm';
                        sheetPaddingTopBottom = '8mm';
                    } else {
                        printCardWidth = '177mm';
                        printCardHeight = '59mm';
                        scaleRatio = ((177 * 3.77953) / 900).toFixed(6);
                        cardMarginBottom = '4.5mm';
                        sheetPaddingTopBottom = '10mm';
                    }
                } else if (perPage === 3) {
                    printCardWidth = '190mm';
                    printCardHeight = '63.33mm';
                    scaleRatio = ((190 * 3.77953) / 900).toFixed(6);
                    cardMarginBottom = '10mm';
                    sheetPaddingTopBottom = '12mm';
                } else if (perPage === 2) {
                    printCardWidth = '192mm';
                    printCardHeight = '64mm';
                    scaleRatio = ((192 * 3.77953) / 900).toFixed(6);
                    cardMarginBottom = '30mm';
                    sheetPaddingTopBottom = '20mm';
                } else { // 1
                    printCardWidth = '196mm';
                    printCardHeight = '65.33mm';
                    scaleRatio = ((196 * 3.77953) / 900).toFixed(6);
                    cardMarginBottom = '0mm';
                    sheetPaddingTopBottom = '30mm';
                }

                const tplPositions = tpl.positions || {};

                const pTitle = tplPositions.canvaElTitle || {};
                const pZone = tplPositions.canvaElZone || {};
                const pPrice = tplPositions.canvaElPrice || {};
                const pBanner = tplPositions.canvaElBanner || {};
                const pBuyer = tplPositions.canvaElBuyer || {};
                const pVenue = tplPositions.canvaElVenue || {};
                const pNum = tplPositions.canvaElTicketNumber || {};
                const pQR = tplPositions.canvaElQR || {};
                const pHash = tplPositions.canvaElHash || {};
                const pDisc = tplPositions.canvaElDisclaimer || {};
                const pLogo = tplPositions.canvaElLogo || {};

                const isTitleHidden = pTitle.hidden === true || pTitle.display === 'none';
                const isZoneHidden = pZone.hidden === true || pZone.display === 'none';
                const isPriceHidden = pPrice.hidden === true || pPrice.display === 'none';
                const isBannerHidden = pBanner.hidden === true || pBanner.display === 'none';
                const isBuyerHidden = pBuyer.hidden === true || pBuyer.display === 'none';
                const isVenueHidden = pVenue.hidden === true || pVenue.display === 'none';
                const isNumHidden = pNum.hidden === true || pNum.display === 'none';
                const isQRHidden = pQR.hidden === true || pQR.display === 'none';
                const isHashHidden = pHash.hidden === true || pHash.display === 'none';
                const isDiscHidden = pDisc.hidden === true || pDisc.display === 'none';
                const isLogoHidden = pLogo.hidden === true || pLogo.display === 'none';

                function getCustomStyleString(posObj, defaultAlign = 'left', defaultColor = null) {
                    if (!posObj) return `text-align: ${defaultAlign}; color: ${defaultColor || primaryTextColor};`;
                    let res = '';
                    const align = posObj.textAlign || defaultAlign;
                    if (align) res += `text-align: ${align};`;
                    if (posObj.fontSize) res += `font-size: ${posObj.fontSize};`;
                    const color = posObj.color || defaultColor || primaryTextColor;
                    if (color) res += `color: ${color};`;
                    if (posObj.fontWeight) res += `font-weight: ${posObj.fontWeight};`;
                    if (posObj.fontStyle) res += `font-style: ${posObj.fontStyle};`;
                    return res;
                }

                let pagesHtml = '';
                const totalQty = ticketsQueue.length;

                for (let ticketIdx = 0; ticketIdx < totalQty; ticketIdx += perPage) {
                    let pageTicketsHtml = '';

                    for (let k = 0; k < perPage && (ticketIdx + k) < totalQty; k++) {
                        const ticketItem = ticketsQueue[ticketIdx + k];
                        const ticketNumberVal = ticketItem.ticketNumberVal || (ticketIdx + k + 1);
                        const zoneName = ticketItem.zoneName;
                        const priceFormatted = ticketItem.zonePrice;
                        const ticketNum = (ticketItem.ticketCode && ticketItem.ticketCode.startsWith('N°'))
                            ? ticketItem.ticketCode
                            : ('N° ' + String(ticketNumberVal).padStart(5, '0'));
                        const hash = ticketItem.validationHash || ('VG' + Math.random().toString(36).substring(2, 10).toUpperCase());
                        const qrPayload = ticketItem.qrPayload || `VG-${hash}`;
                        const qrDataUrl = ticketItem.qrDataUrl || getQrCodeDataUrl(qrPayload);

                        let customTagsHtml = '';
                        Object.keys(tplPositions).forEach(key => {
                            if (key.startsWith('canvaCustomTag_') && !tplPositions[key].hidden && tplPositions[key].display !== 'none') {
                                const p = tplPositions[key];
                                const tagText = p.text || 'Etiqueta';
                                customTagsHtml += `
                                    <div class="canva-drag-element" style="position: absolute; top: ${p.top || '40px'}; left: ${p.left || '120px'}; z-index: 10;">
                                        <div style="background: rgba(255, 85, 0, 0.15); border: 1.5px solid #FF5500; padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 800; font-size: ${p.fontSize || '0.85rem'}; color: ${p.color || primaryTextColor}; ${getCustomStyleString(p)}">
                                            🏷️ ${tagText}
                                        </div>
                                    </div>
                                `;
                            }
                        });

                        const ticketNumberHtml = isNumHidden ? '' : `
                            <div class="canva-drag-element" id="canvaElTicketNumber" style="position: absolute; top: ${pNum.top || '16px'}; ${isPlantilla3 ? 'left: 650px; width: 250px;' : 'left: 0; width: 100%;'} text-align: center; display: flex; justify-content: center; align-items: center; z-index: 5;">
                                <span style="font-size: ${pNum.fontSize || '1.3rem'}; font-weight: 900; color: ${pNum.color || primaryTextColor}; letter-spacing: 0.5px; display: inline-block;">${ticketNum}</span>
                            </div>
                        `;

                        const qrBoxHtml = isQRHidden ? '' : `
                            <div class="canva-drag-element" id="canvaElQR" style="position: absolute; top: ${pQR.top || '48px'}; left: ${isPlantilla3 ? '705px' : '55px'}; width: 140px; height: 140px; display: flex; align-items: center; justify-content: center; z-index: 5;">
                                <div class="stub-qr-box" style="padding: 0.4rem; background: #FFFFFF; border-radius: 16px; border: 1.5px solid #E2E8F0; width: 140px; height: 140px; display: flex; align-items: center; justify-content: center; box-sizing: border-box; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                    <img src="${qrDataUrl}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px;" alt="QR Code" />
                                </div>
                            </div>
                        `;

                        const hashHtml = isHashHidden ? '' : `
                            <div class="canva-drag-element" id="canvaElHash" style="position: absolute; top: ${pHash.top || '200px'}; ${isPlantilla3 ? 'left: 650px; width: 250px;' : 'left: 0; width: 100%;'} text-align: center; display: flex; justify-content: center; align-items: center; z-index: 5;">
                                <span style="font-family: monospace; font-size: ${pHash.fontSize || '0.95rem'}; font-weight: 800; color: ${pHash.color || secondaryTextColor}; letter-spacing: 3px; display: inline-block;">${hash}</span>
                            </div>
                        `;

                        const disclaimerHtml = isDiscHidden ? '' : `
                            <div class="canva-drag-element" id="canvaElDisclaimer" style="position: absolute; top: ${pDisc.top || '226px'}; left: ${isPlantilla3 ? '665px' : '15px'}; width: 220px; text-align: center; z-index: 5;">
                                <div style="border-top: 1px solid #E2E8F0; padding-top: 0.25rem;">
                                    <p style="font-size: ${pDisc.fontSize || '0.58rem'}; font-weight: 600; color: ${pDisc.color || mutedTextColor}; line-height: 1.25; margin: 0; text-align: center;">
                                        La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.
                                    </p>
                                </div>
                            </div>
                        `;

                        pageTicketsHtml += `
                            <div class="ticket-wrapper-card" style="width: ${printCardWidth}; height: ${printCardHeight}; position: relative; overflow: hidden; border-radius: 20px; border: 1.5px solid #000000; margin-bottom: ${cardMarginBottom}; flex-shrink: 0; box-sizing: border-box; background: ${bgColor};">
                                <div class="ticket-canvas-inner" style="width: 900px; height: 300px; transform: scale(${scaleRatio}); transform-origin: top left; position: absolute; top: 0; left: 0; background: ${bgColor}; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                                    
                                    <div class="ticket-side-strip" style="width: 78px; background: ${stripColor}; height: 100%; position: absolute; ${isPlantilla2 ? 'right: 0;' : 'left: 0;'} top: 0; display: ${isPlantilla3 ? 'none' : 'flex'}; align-items: center; justify-content: center; z-index: 2;">
                                        <img src="${logoDataUrl}" style="max-width: 240px; height: 48px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 10px rgba(255,85,0,0.6));">
                                    </div>

                                    <div style="position: absolute; left: ${isPlantilla2 ? '250px' : (isPlantilla3 ? '0' : '78px')}; width: ${isPlantilla3 ? '900px' : '572px'}; top: 0; bottom: 0; background: ${bgColor}; border-right: ${isPlantilla1 ? '2px dashed #CBD5E1' : 'none'}; border-left: ${isPlantilla2 ? '2px dashed #CBD5E1' : 'none'};" class="canva-main-area">
                                        ${customTagsHtml}

                                        ${(isPlantilla3 && !isLogoHidden) ? `
                                        <div class="canva-drag-element" id="canvaElLogo" style="position: absolute; top: ${pLogo.top || '18px'}; left: ${pLogo.left || '25px'}; z-index: 5;">
                                            <img src="${logoDataUrl}" style="height: ${pLogo.height || '38px'}; width: auto; object-fit: contain; filter: drop-shadow(0 0 10px rgba(255,85,0,0.6));">
                                        </div>` : ''}

                                        ${isBannerHidden ? '' : `
                                        <div class="canva-drag-element" id="canvaElBanner" style="position: absolute; top: ${pBanner.top || '54px'}; left: ${pBanner.left || '20px'}; width: ${pBanner.width || (isPlantilla3 ? '520px' : '532px')}; height: ${pBanner.height || '130px'};">
                                            <img src="${bannerDataUrl}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 14px;">
                                        </div>`}

                                        ${isTitleHidden ? '' : `
                                        <div class="canva-drag-element" id="canvaElTitle" style="position: absolute; top: ${pTitle.top || (isPlantilla3 ? '60px' : '18px')}; left: 20px; width: 532px; text-align: center; ${getCustomStyleString(pTitle, 'center', primaryTextColor)}">
                                            <h2 style="font-size: ${pTitle.fontSize || '1.18rem'}; font-weight: 900; color: ${pTitle.color || primaryTextColor}; margin: 0; line-height: 1.15; text-align: center;">${title}</h2>
                                        </div>`}

                                        ${isZoneHidden ? '' : `
                                        <div class="canva-drag-element" id="canvaElZone" style="position: absolute; top: ${pZone.top || (isPlantilla3 ? '105px' : '194px')}; left: 20px; ${getCustomStyleString(pZone, 'left', secondaryTextColor)}">
                                            <span style="font-size: ${pZone.fontSize || '0.98rem'}; font-weight: 900; color: ${pZone.color || '#DC2626'}; text-transform: uppercase; letter-spacing: 0.5px; display: block;">${zoneName}</span>
                                        </div>`}

                                        ${isPriceHidden ? '' : `
                                        <div class="canva-drag-element" id="canvaElPrice" style="position: absolute; top: ${pPrice.top || (isPlantilla3 ? '105px' : '216px')}; left: ${isPlantilla3 ? '150px' : (isPlantilla2 ? '20px' : '430px')}; ${getCustomStyleString(pPrice, isPlantilla2 ? 'left' : (isPlantilla3 ? 'left' : 'right'), primaryTextColor)}">
                                            <div style="text-align: inherit; line-height: 1.15;">
                                                <span style="font-size: 0.825rem; font-weight: 900; color: ${pPrice.color || primaryTextColor}; display: block; letter-spacing: 0.5px;">PRECIO:</span>
                                                <span style="font-size: ${pPrice.fontSize || '1.35rem'}; font-weight: 900; color: ${pPrice.color || primaryTextColor}; display: block; margin-top: 2px;">S/ ${priceFormatted}</span>
                                            </div>
                                        </div>`}

                                        ${isVenueHidden ? '' : `
                                        <div class="canva-drag-element" id="canvaElVenue" style="position: absolute; top: ${pVenue.top || (isPlantilla3 ? '225px' : '194px')}; right: 20px; ${getCustomStyleString(pVenue, 'right', primaryTextColor)}">
                                            <div style="display: flex; flex-direction: column; font-size: 0.8rem; text-align: right; line-height: 1.25;">
                                                <span style="font-weight: 900; font-size: 1.05rem; color: ${pVenue.color || primaryTextColor}; display: block; line-height: 1.2;">${venue}</span>
                                                <span style="font-size: 0.85rem; font-weight: 800; color: #2C3E50; display: block; line-height: 1.2; margin-top: 2px;">${address}</span>
                                                <span style="font-weight: 900; font-size: 1.1rem; color: #FF5500; display: block; line-height: 1.2; margin-top: 2px;">${cleanDate} / ${cleanTime}</span>
                                            </div>
                                        </div>`}

                                        ${isPlantilla3 ? `
                                            ${ticketNumberHtml}
                                            ${qrBoxHtml}
                                            ${hashHtml}
                                            ${disclaimerHtml}
                                        ` : ''}
                                    </div>

                                    ${isPlantilla3 ? '' : `
                                    <div style="position: absolute; ${isPlantilla2 ? 'left: 0;' : 'right: 0;'} top: 0; width: 250px; height: 100%; background: ${isDarkBg ? 'rgba(255,255,255,0.05)' : '#FAFAFA'}; ${isPlantilla2 ? 'border-right: 2px dashed #CBD5E1;' : 'border-left: 2px dashed #CBD5E1;'}; box-sizing: border-box;" class="canva-stub-area">
                                        ${ticketNumberHtml}
                                        ${qrBoxHtml}
                                        ${hashHtml}
                                        ${disclaimerHtml}
                                    </div>`}
                                </div>
                            </div>
                        `;
                    }

                    pagesHtml += `
                        <div class="print-page-sheet" style="width: ${pageWidthMm}mm; min-height: ${pageHeightMm}mm; max-height: ${pageHeightMm}mm; page-break-after: always; break-after: page; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: ${sheetPaddingTopBottom}; padding-bottom: ${sheetPaddingTopBottom}; overflow: hidden; background: #FFFFFF;">
                            ${pageTicketsHtml}
                        </div>
                    `;
                }

                const docContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <title>Boletos Oficiales - ${title}</title>
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
                        <style>
                            @page { size: ${pageWidthMm}mm ${pageHeightMm}mm; margin: 0; }
                            * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                            body { margin: 0; padding: 0; background: #FFFFFF; color: #000000; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
                            .print-page-sheet { width: ${pageWidthMm}mm; min-height: ${pageHeightMm}mm; max-height: ${pageHeightMm}mm; page-break-after: always; break-after: page; page-break-inside: avoid; break-inside: avoid; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: ${sheetPaddingTopBottom}; padding-bottom: ${sheetPaddingTopBottom}; overflow: hidden; }
                            .print-page-sheet:last-child { page-break-after: auto; break-after: auto; }
                            .ticket-wrapper-card { border: 1.5px solid #000000; border-radius: 20px; overflow: hidden; position: relative; box-sizing: border-box; box-shadow: none; flex-shrink: 0; page-break-inside: avoid; break-inside: avoid; }
                            .ticket-wrapper-card:last-child { margin-bottom: 0 !important; }
                            .ticket-canvas-inner { border-radius: 20px; overflow: hidden; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
                            .ticket-side-strip { display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
                            .stub-qr-box { padding: 0.4rem; background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; display: flex; align-items: center; justify-content: center; box-sizing: border-box; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
                            .stub-qr-box img { width: 100% !important; height: 100% !important; display: block !important; border-radius: 4px; }
                        </style>
                    </head>
                    <body>
                        ${pagesHtml}
                    </body>
                    </html>
                `;

                // 2. MONTAR EN CONTENEDOR IN-DOM CON CÁLCULO DE LAYOUT REAL (Evita el error de canvas en blanco por iframe desconectado)
                let renderContainer = document.getElementById('vivegoPdfRenderContainer');
                if (renderContainer) renderContainer.remove();

                renderContainer = document.createElement('div');
                renderContainer.id = 'vivegoPdfRenderContainer';
                renderContainer.style.position = 'fixed';
                renderContainer.style.left = '0';
                renderContainer.style.top = '0';
                renderContainer.style.width = `${pageWidthMm}mm`;
                renderContainer.style.zIndex = '999999';
                renderContainer.style.opacity = '0.005'; // Mantiene activos los cálculos de dimensiones en el motor de renderizado
                renderContainer.style.pointerEvents = 'none';
                renderContainer.style.background = '#FFFFFF';
                renderContainer.style.overflow = 'visible';
                renderContainer.innerHTML = pagesHtml;
                document.body.appendChild(renderContainer);

                // Esperar a que las fuentes web y estilos terminen de pintarse en el DOM
                if (document.fonts && document.fonts.ready) {
                    await document.fonts.ready;
                }
                await new Promise(r => setTimeout(r, 250));

                const cleanTitle = (evt.title || 'Boletos').replace(/[^a-zA-Z0-9_-]/g, '_');
                const fileName = `Boletos_${cleanTitle}.pdf`;

                const sheets = renderContainer.querySelectorAll('.print-page-sheet');
                const totalSheets = sheets.length;

                // 3. MOTOR PRINCIPAL: jsPDF + html2canvas MULTIPÁGINA DE ALTA DEFINICIÓN
                const jsPdfObj = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF || null);

                if (jsPdfObj && typeof html2canvas !== 'undefined') {
                    const pdf = new jsPdfObj({
                        orientation: orientation,
                        unit: 'mm',
                        format: jsPdfFormat,
                        compress: true
                    });

                    for (let i = 0; i < totalSheets; i++) {
                        const sheetEl = sheets[i];
                        Swal.update({
                            title: '🎨 2/3 - Renderizando Boletos en Alta Calidad...',
                            html: `Compilando hoja <b>${i + 1} de ${totalSheets}</b> con máxima nitidez...`
                        });

                        const canvas = await html2canvas(sheetEl, {
                            scale: 2.2,
                            useCORS: true,
                            allowTaint: true,
                            backgroundColor: '#FFFFFF',
                            logging: false,
                            windowWidth: sheetEl.scrollWidth,
                            windowHeight: sheetEl.scrollHeight
                        });

                        const imgData = canvas.toDataURL('image/jpeg', 0.95);
                        if (i > 0) {
                            pdf.addPage(jsPdfFormat, orientation);
                        }
                        pdf.addImage(imgData, 'JPEG', 0, 0, pageWidthMm, pageHeightMm, undefined, 'FAST');
                    }

                    Swal.update({
                        title: '📥 3/3 - Guardando Archivo PDF...',
                        html: `Empaquetando <b>"${fileName}"</b> para descarga directa...`
                    });

                    pdf.save(fileName);
                    renderContainer.remove();

                    showPdfSuccessModal(fileName, ticketsQueue.length, docContent);
                } else if (typeof html2pdf !== 'undefined') {
                    // Fallback 1: html2pdf
                    Swal.update({
                        title: '📥 Generando PDF...',
                        html: `Compilando boletos con motor alternativo...`
                    });

                    const opt = {
                        margin: 0,
                        filename: fileName,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true, allowTaint: true, logging: false },
                        jsPDF: { unit: 'mm', format: jsPdfFormat, orientation: orientation },
                        pagebreak: { mode: ['css', 'legacy'] }
                    };

                    await html2pdf().set(opt).from(renderContainer).save();
                    renderContainer.remove();

                    showPdfSuccessModal(fileName, ticketsQueue.length, docContent);
                } else {
                    // Fallback 2: Cuadro de Impresión Directa
                    renderContainer.remove();
                    openDirectNativePrintWindow(docContent);
                    Swal.close();
                }
            } catch (err) {
                console.error('Error generando PDF:', err);
                const renderContainer = document.getElementById('vivegoPdfRenderContainer');
                if (renderContainer) renderContainer.remove();

                Swal.fire({
                    title: 'Inconveniente al compilar PDF',
                    html: `Ocurrió un detalle técnico (${err.message || 'error desconocido'}).<br><br>¿Deseas abrir el <b>cuadro de impresión directa</b> para imprimir o guardar en PDF?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF5500',
                    cancelButtonColor: '#475569',
                    confirmButtonText: '🖨️ Abrir Impresión Directa',
                    cancelButtonText: 'Cerrar',
                    background: '#14141E',
                    color: '#FFFFFF'
                }).then((res) => {
                    if (res.isConfirmed) {
                        openDirectNativePrintWindow(docContent);
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Modal Editar Evento
            const editModal = document.getElementById('editEventModal');
            const closeEditBtn = document.getElementById('btnCloseEditEventModal');
            const cancelEditBtn = document.getElementById('btnCancelEditEvent');
            const editBtns = document.querySelectorAll('.btn-edit-event');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-event'));
                    
                    if (document.getElementById('edit_title')) document.getElementById('edit_title').value = data.title || '';
                    if (document.getElementById('edit_category')) document.getElementById('edit_category').value = data.category || 'Concierto';
                    if (document.getElementById('edit_venue')) document.getElementById('edit_venue').value = data.venue || '';
                    if (document.getElementById('edit_status')) document.getElementById('edit_status').value = data.status || 'Publicado';

                    if (editModal) editModal.classList.add('active');
                });
            });

            if (closeEditBtn && editModal) closeEditBtn.addEventListener('click', () => editModal.classList.remove('active'));
            if (cancelEditBtn && editModal) cancelEditBtn.addEventListener('click', () => editModal.classList.remove('active'));

            // Modal Generar Boletos PDF
            const genModal = document.getElementById('generateTicketsModal');
            const closeGenBtn = document.getElementById('btnCloseGenerateModal');
            const cancelGenBtn = document.getElementById('btnCancelGenerateModal');
            const genBtns = document.querySelectorAll('.btn-generate-pdf-tickets');

            genBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-event'));
                    selectedEventForPdf = data;

                    if (document.getElementById('gen_event_title')) {
                        document.getElementById('gen_event_title').value = data.title;
                    }

                    // Cargar dinámicamente las zonas del evento en el selector
                    const zoneSelect = document.getElementById('gen_zone');
                    if (zoneSelect) {
                        zoneSelect.innerHTML = '';
                        const defaultZones = [
                            { name: 'BOX PLATINUM INDIVIDUAL', price: 150.00, capacity: 10 },
                            { name: 'ZONA VIP STAND UP', price: 95.00, capacity: 20 },
                            { name: 'ZONA GENERAL', price: 55.50, capacity: 30 }
                        ];
                        const eventZones = (data.zones && data.zones.length > 0) ? data.zones : defaultZones;
                        const totalAllCap = eventZones.reduce((sum, z) => sum + (parseInt(z.capacity || z.stock, 10) || 10), 0);

                        // Opción 1: TODAS LAS ZONAS
                        const optAll = document.createElement('option');
                        optAll.value = 'ALL';
                        optAll.textContent = `🌟 TODAS LAS ZONAS DEL EVENTO (Total: ${totalAllCap} boletos)`;
                        optAll.selected = true;
                        zoneSelect.appendChild(optAll);

                        // Opciones individuales por zona
                        eventZones.forEach((z) => {
                            const opt = document.createElement('option');
                            const cap = z.capacity || z.stock || 10;
                            opt.value = `${z.name}|${z.price}|${cap}`;
                            opt.textContent = `🎟️ ${z.name} (S/ ${parseFloat(z.price).toFixed(2)}) — Aforo: ${cap} boletos`;
                            zoneSelect.appendChild(opt);
                        });
                    }

                    onZoneSelectChange(false);

                    // Consultar si ya existen códigos registrados en la BD para este evento
                    fetch(`/admin/eventos/${data.id}/boletos-registrados`)
                        .then(res => res.json())
                        .then(resData => {
                            if (resData.success && resData.count > 0) {
                                generatedTicketsQueue = resData.tickets.map((t, idx) => {
                                    const num = t.ticketNumberVal || (idx + 1);
                                    const code = (t.ticketCode && t.ticketCode.startsWith('N°')) ? t.ticketCode : `N° ${String(num).padStart(5, '0')}`;
                                    return {
                                        ticketNumberVal: num,
                                        ticketCode: code,
                                        zoneName: t.zoneName,
                                        zonePrice: t.zonePrice,
                                        validationHash: t.validationHash,
                                        qrPayload: t.qrPayload,
                                        qrDataUrl: getQrCodeDataUrl(t.qrPayload),
                                        buyerName: t.buyerName,
                                        buyerDni: t.buyerDni,
                                        source: t.source
                                    };
                                });

                                setModalLockedState(resData.count);
                                updateEstimatedPages();
                            } else {
                                isCodesLocked = false;
                                resetGeneratedCodesState();
                                updateEstimatedPages();
                            }
                        })
                        .catch(err => {
                            console.error('Error comprobando boletos existentes:', err);
                            isCodesLocked = false;
                            resetGeneratedCodesState();
                        });

                    if (genModal) {
                        genModal.classList.add('active');
                    }
                });
            });

            if (closeGenBtn && genModal) closeGenBtn.addEventListener('click', () => genModal.classList.remove('active'));
            if (cancelGenBtn && genModal) cancelGenBtn.addEventListener('click', () => genModal.classList.remove('active'));

            // Eliminar Evento de MySQL con SweetAlert2
            const deleteBtns = document.querySelectorAll('.btn-delete-event');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const title = this.getAttribute('data-title');
                    const id = this.getAttribute('data-id');

                    Swal.fire({
                        title: '¿Eliminar Evento?',
                        text: `¿Estás seguro de que deseas eliminar permanentemente "${title}" de la base de datos?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#FF1E3C',
                        cancelButtonColor: '#475569',
                        confirmButtonText: '🗑️ Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/eventos/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    title: '¡Evento Eliminado!',
                                    text: `El evento "${title}" ha sido eliminado de la Base de Datos.`,
                                    icon: 'success',
                                    confirmButtonColor: '#FF5500',
                                    background: '#14141E',
                                    color: '#FFFFFF'
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(err => {
                                window.location.reload();
                            });
                        }
                    });
                });
            });

            // Live Search Filter en Tabla
            const searchInput = document.getElementById('tableFilterInput');
            const tableRows = document.querySelectorAll('#eventsTable tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.toLowerCase().trim();
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }

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

            // Theme Toggle (Dark / Light)
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
