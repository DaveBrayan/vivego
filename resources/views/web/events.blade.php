@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Anton&family=Bebas+Neue&family=Caveat:wght@600;700&family=Cinzel:wght@600;800&family=Comfortaa:wght@600;700&family=Dancing+Script:wght@600;700&family=Fira+Sans:ital,wght@0,400;0,700;1,400&family=Great+Vibes&family=Inter:wght@400;600;800;900&family=Lato:wght@400;700;900&family=Lobster&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Monoton&family=Montserrat:wght@400;700;900&family=Nunito:wght@400;700;900&family=Open+Sans:wght@400;700&family=Oswald:wght@500;700&family=Outfit:wght@400;700;900&family=Pacifico&family=Permanent+Marker&family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Poppins:wght@400;700;900&family=Raleway:wght@400;700;900&family=Righteous&family=Roboto:wght@400;700;900&family=Rubik:wght@400;700;900&family=Satisfy&family=Space+Grotesk:wght@500;700&family=Syne:wght@700;800&family=Work+Sans:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        .font-lato { font-family: 'Lato', sans-serif !important; }
        .font-montserrat { font-family: 'Montserrat', sans-serif !important; }
        .font-opensans { font-family: 'Open Sans', sans-serif !important; }
        .font-roboto { font-family: 'Roboto', sans-serif !important; }
        .font-inter { font-family: 'Inter', sans-serif !important; }
        .font-poppins { font-family: 'Poppins', sans-serif !important; }
        .font-outfit { font-family: 'Outfit', sans-serif !important; }
        .font-raleway { font-family: 'Raleway', sans-serif !important; }
        .font-nunito { font-family: 'Nunito', sans-serif !important; }
        .font-rubik { font-family: 'Rubik', sans-serif !important; }
        .font-work-sans { font-family: 'Work Sans', sans-serif !important; }
        .font-oswald { font-family: 'Oswald', sans-serif !important; }
        .font-bebas { font-family: 'Bebas Neue', sans-serif !important; }
        .font-anton { font-family: 'Anton', sans-serif !important; }
        .font-syne { font-family: 'Syne', sans-serif !important; }
        .font-space-grotesk { font-family: 'Space Grotesk', sans-serif !important; }
        .font-righteous { font-family: 'Righteous', sans-serif !important; }
        .font-monoton { font-family: 'Monoton', sans-serif !important; }
        .font-merriweather { font-family: 'Merriweather', serif !important; }
        .font-playfair { font-family: 'Playfair Display', serif !important; }
        .font-cinzel { font-family: 'Cinzel', serif !important; }
        .font-abril { font-family: 'Abril Fatface', serif !important; }
        .font-dancing { font-family: 'Dancing Script', cursive !important; }
        .font-greatvibes { font-family: 'Great Vibes', cursive !important; }
        .font-pacifico { font-family: 'Pacifico', cursive !important; }
        .font-satisfy { font-family: 'Satisfy', cursive !important; }
        .font-caveat { font-family: 'Caveat', cursive !important; }
        .font-lobster { font-family: 'Lobster', cursive !important; }
        .font-comfortaa { font-family: 'Comfortaa', cursive !important; }

        .ticket-element-node p, 
        .ticket-element-node div, 
        .ticket-element-node span, 
        .ticket-element-node h1, 
        .ticket-element-node h2, 
        .ticket-element-node h3, 
        .ticket-element-node h4 {
            margin: 0 !important;
            padding: 0 !important;
            text-align: inherit !important;
            box-sizing: border-box !important;
        }
    </style>
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
                                                @if($evt['status'] === 'Publicado')
                                                    🌐 Público
                                                @elseif($evt['status'] === 'Oculto' || $evt['status'] === 'No Marketplace' || $evt['status'] === 'unlisted')
                                                    🔗 Oculto en Marketplace
                                                @elseif($evt['status'] === 'Borrador' || $evt['status'] === 'draft')
                                                    📝 Borrador
                                                @elseif($evt['status'] === 'Agotado')
                                                    🚫 Agotado
                                                @else
                                                    {{ $evt['status'] }}
                                                @endif
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="dash-actions-cell" style="justify-content: flex-end;">
                                                <a href="{{ route('web.event.detail', $evt['slug']) }}" class="dash-btn-icon-action" title="Previsualizar Evento" target="_blank" style="color: var(--color-neon-cyan);">👁️</a>
                                                <a href="{{ route('web.events.edit', $evt['id']) }}" class="dash-btn-icon-action" title="Editar Evento">✏️</a>
                                                <button type="button" class="dash-btn-icon-action btn-duplicate-event" data-id="{{ $evt['id'] }}" data-title="{{ $evt['title'] }}" title="Duplicar Evento Completo" style="color: #A855F7;">📋</button>
                                                @if(($evt['sales_type'] ?? 'virtual') !== 'virtual')
                                                    <button type="button" class="dash-btn-icon-action btn-generate-pdf-tickets" data-event='@json($evt)' title="Generar Boletos PDF" style="color: #EAB308;">🖨️</button>
                                                @endif
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

                    <button type="button" class="btn btn-cancel-custom" id="btnCancelGenerateModal" style="text-align: center; padding: 0.75rem; width: 100%;">Cancelar</button>
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

        function convertPositionsToElements(positions) {
            if (!positions || typeof positions !== 'object') return [];
            const elements = [];
            const fieldMap = {
                canvaElLogo: { field: 'logo', type: 'image' },
                canvaElBanner: { field: 'banner', type: 'image' },
                canvaElTitle: { field: 'title', type: 'text' },
                canvaElZone: { field: 'zone', type: 'text' },
                canvaElPrice: { field: 'price', type: 'text' },
                canvaElVenue: { field: 'venue', type: 'text' },
                canvaElCity: { field: 'city', type: 'text' },
                canvaElDate: { field: 'date', type: 'text' },
                canvaElTime: { field: 'time', type: 'text' },
                canvaElBuyerName: { field: 'buyer_name', type: 'text' },
                canvaElBuyer: { field: 'buyer_name', type: 'text' },
                canvaElBuyerDni: { field: 'buyer_dni', type: 'text' },
                canvaElTicketNumber: { field: 'ticket_number', type: 'text' },
                canvaElQR: { field: 'qr', type: 'qr' },
                canvaElHash: { field: 'hash', type: 'text' },
                canvaElDisclaimer: { field: 'disclaimer', type: 'disclaimer' },
            };

            Object.keys(positions).forEach((id) => {
                const p = positions[id];
                if (!p || p.hidden === true || p.display === 'none' || p.visible === false) return;

                const mapped = fieldMap[id] || { field: 'custom', type: 'text' };
                const isDisclaimer = id === 'canvaElDisclaimer' || mapped.field === 'disclaimer' || (id && String(id).toLowerCase().includes('disclaimer'));
                const topVal = parseFloat(p.top) || 0;
                const leftVal = parseFloat(p.left) || 0;
                const widthVal = parseFloat(p.width) || 120;
                const heightVal = parseFloat(p.height) || 40;

                // Leer textAlign desde múltiples fuentes en orden de prioridad:
                // 1. p.textAlign guardado (del wrapper canva-drag-element)
                // 2. Del HTML guardado (text-align puede estar en los hijos del box-container)
                // 3. Mapa de alineaciones por defecto por ID/campo (refleja el diseño original de los templates)
                const defaultAlignMap = {
                    canvaElPrice: 'right',
                    canvaElVenue: 'right',
                    canvaElTicketNumber: 'left',
                    canvaElHash: 'left',
                    canvaElDisclaimer: 'center',
                };
                let resolvedTextAlign = p.textAlign || '';
                if (!resolvedTextAlign && p.html) {
                    const taMatch = p.html.match(/text-align\s*:\s*(left|center|right|justify)/i);
                    if (taMatch) resolvedTextAlign = taMatch[1];
                }
                if (!resolvedTextAlign) {
                    resolvedTextAlign = defaultAlignMap[id] || (isDisclaimer ? 'center' : 'left');
                }

                elements.push({
                    id: id,
                    field: mapped.field,
                    type: mapped.type,
                    content: p.html || p.text || '',
                    src: p.src || '',
                    x: leftVal,
                    y: topVal,
                    width: widthVal,
                    height: heightVal,
                    rotation: parseFloat(p.rotate) || 0,
                    fit: 'cover',
                    style: {
                        fontFamily: p.fontFamily || 'Plus Jakarta Sans',
                        fontSize: parseFloat(p.fontSize) || 14,
                        color: p.color || '#FFFFFF',
                        fontWeight: p.fontWeight || 'bold',
                        fontStyle: p.fontStyle || 'normal',
                        textAlign: resolvedTextAlign,
                        letterSpacing: 0,
                        lineHeight: 1.2,
                        background: p.backgroundColor || 'transparent'
                    }
                });
            });

            return elements;
        }

        function getRealFontFamily(fontName) {
            if (!fontName) return 'Plus Jakarta Sans';
            const fontMap = {
                'font-lato': 'Lato',
                'font-montserrat': 'Montserrat',
                'font-opensans': 'Open Sans',
                'font-roboto': 'Roboto',
                'font-inter': 'Inter',
                'font-poppins': 'Poppins',
                'font-outfit': 'Outfit',
                'font-raleway': 'Raleway',
                'font-nunito': 'Nunito',
                'font-rubik': 'Rubik',
                'font-work-sans': 'Work Sans',
                'font-oswald': 'Oswald',
                'font-bebas': 'Bebas Neue',
                'font-anton': 'Anton',
                'font-syne': 'Syne',
                'font-space-grotesk': 'Space Grotesk',
                'font-righteous': 'Righteous',
                'font-monoton': 'Monoton',
                'font-merriweather': 'Merriweather',
                'font-playfair': 'Playfair Display',
                'font-cinzel': 'Cinzel',
                'font-abril': 'Abril Fatface',
                'font-dancing': 'Dancing Script',
                'font-greatvibes': 'Great Vibes',
                'font-pacifico': 'Pacifico',
                'font-satisfy': 'Satisfy',
                'font-caveat': 'Caveat',
                'font-lobster': 'Lobster',
                'font-comfortaa': 'Comfortaa'
            };

            if (fontMap[fontName]) return fontMap[fontName];
            if (typeof fontName === 'string' && fontName.startsWith('font-')) {
                const clean = fontName.replace('font-', '');
                return clean.charAt(0).toUpperCase() + clean.slice(1);
            }
            return fontName;
        }

        function replaceDynamicValueInHtml(html, labelKeyword, newValue) {
            if (!html || typeof html !== 'string') return html;
            const cleanLabel = labelKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            
            // Caso 1: Estructura multilínea de párrafos <p>Label:</p><p>Valor</p>
            const multiPRegex = new RegExp(`(<p[^>]*>\\s*${cleanLabel}:?\\s*<\\/p>\\s*<p[^>]*>)(.*?)(<\\/p>)`, 'gi');
            if (multiPRegex.test(html)) {
                return html.replace(multiPRegex, `$1${newValue}$3`);
            }

            // Caso 2: Estructura en línea Label: Valor
            const singleRegex = new RegExp(`(${cleanLabel}:?\\s*)((?:<[^>]+>\\s*)*)([^<\\s]+[^<]*)`, 'gi');
            if (singleRegex.test(html)) {
                return html.replace(singleRegex, `$1$2${newValue}`);
            }

            return html;
        }

        function renderTicketCanvasContent(template, dynamicData, assetMap = {}) {
            let elements = [];
            if (template && Array.isArray(template.elements) && template.elements.length > 0) {
                elements = template.elements;
            } else if (template && template.positions) {
                let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
                elements = convertPositionsToElements(rawPos);
            }

            // Deduplicar elementos del sistema para evitar renderizado doble
            const seenFields = new Set();
            const uniqueElements = [];
            for (let i = elements.length - 1; i >= 0; i--) {
                const el = elements[i];
                if (!el || el.hidden === true || el.display === 'none' || el.visible === false) continue;
                const key = el.field || el.id;
                if (key && (key.startsWith('canvaEl') || key === 'title' || key === 'zone' || key === 'price' || key === 'buyer_name' || key === 'buyer_dni' || key === 'ticket_number' || key === 'hash' || key === 'venue' || key === 'date' || key === 'time' || key === 'disclaimer')) {
                    if (seenFields.has(key)) continue;
                    seenFields.add(key);
                }
                uniqueElements.unshift(el);
            }
            elements = uniqueElements;

            const bgUrl = assetMap.bgDataUrl || (template ? (template.background || template.bg_image) : null);
            let bgHtml = '';
            if (bgUrl) {
                bgHtml = `<div style="position: absolute; inset: 0; background-image: url('${bgUrl}'); background-size: cover; background-position: center; z-index: 0; pointer-events: none;"></div>`;
            }

            let elementsHtml = '';

            elements.forEach((el, idx) => {
                if (!el || el.hidden === true || el.display === 'none' || el.visible === false) return;

                const type = el.type || 'text';
                const field = el.field || 'custom';

                const x = parseFloat(el.x) || 0;
                const y = parseFloat(el.y) || 0;

                const w = el.style?.width ? (typeof el.style.width === 'number' ? el.style.width + 'px' : el.style.width) : (el.width ? (typeof el.width === 'number' ? el.width + 'px' : el.width) : 'auto');
                const h = el.style?.height ? (typeof el.style.height === 'number' ? el.style.height + 'px' : el.style.height) : (el.height ? (typeof el.height === 'number' ? el.height + 'px' : el.height) : 'auto');
                const rotation = parseFloat(el.style?.rotation || el.rotation || el.rotate) || 0;
                const transform = rotation ? `transform: rotate(${rotation}deg); transform-origin: center center;` : '';

                const style = el.style || {};
                const rawFontName = style.fontFamily || el.fontFamily || 'Plus Jakarta Sans';
                const realFontName = getRealFontFamily(rawFontName);
                const font = realFontName.includes(',') ? `font-family: ${realFontName};` : `font-family: '${realFontName}', sans-serif;`;
                const fontSize = style.fontSize ? (typeof style.fontSize === 'number' ? `font-size: ${style.fontSize}px;` : `font-size: ${style.fontSize};`) : 'font-size: 14px;';
                const color = style.color ? `color: ${style.color};` : 'color: #FFFFFF;';
                const weight = style.fontWeight ? `font-weight: ${style.fontWeight};` : 'font-weight: bold;';
                const fontStyle = style.fontStyle ? `font-style: ${style.fontStyle};` : 'font-style: normal;';
                let textAlign = style.textAlign || el.textAlign || el.align || 'left';
                const letterSpacing = style.letterSpacing ? `letter-spacing: ${style.letterSpacing}px;` : '';
                const lineHeight = style.lineHeight ? `line-height: ${style.lineHeight};` : 'line-height: 1.2;';
                const bgStyle = style.background && style.background !== 'transparent' ? `background-color: ${style.background}; border-radius: ${style.borderRadius || '8px'}; padding: ${style.padding || '2px 6px'};` : '';

                let innerContent = '';

                if (type === 'qr' || field === 'qr' || el.id === 'canvaElQR') {
                    const qrSrc = dynamicData.qr_data_url || el.src;
                    innerContent = `<div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1.5px solid #E2E8F0; width: 100%; height: 100%; box-sizing: border-box; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><img src="${qrSrc}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px;" alt="QR Code" /></div>`;
                } else if (type === 'image' || type === 'logo' || type === 'banner' || field === 'logo' || field === 'banner' || field === 'image' || el.id === 'canvaElLogo' || el.id === 'canvaElBanner') {
                    let imgSrc = el.src;
                    if ((field === 'banner' || type === 'banner' || el.id === 'canvaElBanner') && (!imgSrc || imgSrc === '')) {
                        imgSrc = assetMap.bannerDataUrl;
                    }
                    if ((field === 'logo' || type === 'logo' || el.id === 'canvaElLogo') && (!imgSrc || imgSrc === '')) {
                        imgSrc = assetMap.logoDataUrl;
                    }
                    const fitMode = style.objectFit || el.fit || (type === 'banner' || field === 'banner' ? 'cover' : 'contain');
                    if (imgSrc) {
                        innerContent = `<img src="${imgSrc}" style="width: 100%; height: 100%; display: block; object-fit: ${fitMode}; ${field === 'logo' || type === 'logo' ? 'filter: drop-shadow(0 0 8px rgba(255,85,0,0.6));' : ''}" />`;
                    }
                } else {
                    let rawTxt = el.content || el.html || el.text || '';

                    // Limpieza de artefactos de Quill
                    if (typeof rawTxt === 'string') {
                        rawTxt = rawTxt.replace(/<span class="ql-cursor">.*?<\/span>/gi, '').replace(/\uFEFF/g, '');
                    }

                    if (field === 'title' || el.id === 'canvaElTitle') {
                        if (dynamicData.title) {
                            rawTxt = (rawTxt && (rawTxt.includes('<') || rawTxt.includes('>')))
                                ? rawTxt.replace(/(<h[1-6][^>]*>|<p[^>]*>|<span[^>]*>)(.*?)(<\/h[1-6]>|<\/p>|<\/span>|$)/gi, (m, p1, p2, p3) => p1 + dynamicData.title + p3)
                                : dynamicData.title;
                        }
                    } else if (field === 'zone' || el.id === 'canvaElZone' || /ZONA/i.test(rawTxt)) {
                        const zVal = (dynamicData.zone || 'GENERAL').toUpperCase();
                        if (/ZONA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'ZONA', zVal);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, zVal) : zVal;
                        } else {
                            rawTxt = `<span style="font-size: inherit; font-weight: inherit; color: inherit; text-transform: uppercase;">ZONA: ${zVal}</span>`;
                        }
                    } else if (field === 'price' || el.id === 'canvaElPrice' || /PRECIO/i.test(rawTxt)) {
                        const pVal = dynamicData.price ? (String(dynamicData.price).startsWith('S/') ? dynamicData.price : 'S/ ' + dynamicData.price) : 'S/ 0.00';
                        if (/PRECIO/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'PRECIO', pVal);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, pVal) : pVal;
                        } else {
                            rawTxt = `<div style="line-height: 1.15; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; font-weight: 900; display: block;">PRECIO:</span><span style="font-size: 1.2em; font-weight: 900; display: block; margin-top: 2px;">${pVal}</span></div>`;
                        }
                    } else if (field === 'buyer_name' || el.id === 'canvaElBuyerName' || el.id === 'canvaElBuyer' || /Comprador/i.test(rawTxt)) {
                        const bName = (dynamicData.buyer_name || 'CLIENTE VARIOS').toUpperCase();
                        if (/Comprador/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'Comprador', bName);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bName) : bName;
                        } else {
                            rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; opacity: 0.85;">Comprador:</span><span style="font-weight: 900; text-transform: uppercase;">${bName}</span></div>`;
                        }
                    } else if (field === 'buyer_dni' || el.id === 'canvaElBuyerDni' || /DNI/i.test(rawTxt)) {
                        const bDni = dynamicData.buyer_dni || '00000000';
                        if (/DNI/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'DNI', bDni);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bDni) : bDni;
                        } else {
                            rawTxt = `<span style="font-weight: 800;">DNI: ${bDni}</span>`;
                        }
                    } else if (field === 'ticket_number' || el.id === 'canvaElTicketNumber' || /N[°º]/i.test(rawTxt)) {
                        const numStr = dynamicData.ticket_number || 'N° 00001';
                        if (/N[°º]/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/N[°º]\s*[\d]+/gi, numStr);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, numStr) : numStr;
                        } else {
                            rawTxt = `<span style="font-weight: 900; letter-spacing: 0.5px;">${numStr}</span>`;
                        }
                    } else if (field === 'hash' || el.id === 'canvaElHash' || (el.id && String(el.id).toLowerCase().includes('hash')) || /VG-?[A-Z0-9]{6,12}/i.test(rawTxt)) {
                        const hStr = dynamicData.hash || 'VG00000000';
                        if (/VG-?[A-Z0-9]{6,12}/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/VG-?[A-Z0-9]{6,12}/gi, hStr);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, hStr) : hStr;
                        } else {
                            rawTxt = `<span style="font-family: monospace; font-weight: 800; letter-spacing: 1.5px;">${hStr}</span>`;
                        }
                    } else if (field === 'venue' || el.id === 'canvaElVenue') {
                        const vName = dynamicData.venue || '';
                        const vAddr = dynamicData.city || '';
                        const vDate = dynamicData.date || '';
                        const vTime = dynamicData.time || '';
                        if (!rawTxt || rawTxt.trim().length === 0) {
                            rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-weight: 900; display: block;">${vName}</span>${vAddr ? `<span style="font-size: 0.85em; opacity: 0.8; display: block; margin-top: 2px;">${vAddr}</span>` : ''}<span style="font-weight: 900; color: #FF5500; display: block; margin-top: 2px;">${vDate} / ${vTime}</span></div>`;
                        }
                    } else if (field === 'city' || el.id === 'canvaElCity') {
                        rawTxt = dynamicData.city || rawTxt;
                    } else if (field === 'date' || el.id === 'canvaElDate' || /FECHA/i.test(rawTxt)) {
                        if (/FECHA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'FECHA', dynamicData.date || '');
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = dynamicData.date ? (rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, dynamicData.date) : dynamicData.date) : rawTxt;
                        } else {
                            rawTxt = `<span style="font-weight: 900;">FECHA: ${dynamicData.date || ''}</span>`;
                        }
                    } else if (field === 'time' || el.id === 'canvaElTime' || /HORA/i.test(rawTxt)) {
                        if (/HORA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'HORA', dynamicData.time || '');
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = dynamicData.time ? (rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, dynamicData.time) : dynamicData.time) : rawTxt;
                        } else {
                            rawTxt = `<span style="font-weight: 900;">HORA: ${dynamicData.time || ''}</span>`;
                        }
                    } else if (field === 'disclaimer' || el.id === 'canvaElDisclaimer' || (el.id && String(el.id).toLowerCase().includes('disclaimer'))) {
                        rawTxt = rawTxt || `<div style="border-top: 1.5px solid #CBD5E1; padding-top: 0.25rem; width: 100%; text-align: inherit;"><p style="font-size: 0.65em; font-weight: 700; opacity: 0.8; line-height: 1.2; margin: 0; text-align: inherit;">La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.</p></div>`;
                    }

                    const flexAlign = textAlign === 'center' ? 'center' : (textAlign === 'right' ? 'flex-end' : 'flex-start');

                    if (typeof rawTxt === 'string' && rawTxt.includes('<')) {
                        // Forzar text-align en cualquier estilo existente
                        rawTxt = rawTxt
                            .replace(/text-align\s*:\s*(left|center|right|justify)/gi, `text-align: ${textAlign}`)
                            .replace(/align-items\s*:\s*(flex-start|center|flex-end|stretch)/gi, `align-items: ${flexAlign}`);
                        
                        // Inyectar text-align y width: 100% en todas las etiquetas de bloque internas
                        rawTxt = rawTxt.replace(/<(p|div|h[1-6])\b([^>]*)>/gi, (match, tag, attrs) => {
                            if (/style\s*=/i.test(attrs)) {
                                return `<${tag} ${attrs.replace(/style\s*=\s*(['"])/i, `style=$1text-align: ${textAlign} !important; width: 100% !important; `)}>`;
                            } else {
                                return `<${tag} style="text-align: ${textAlign} !important; width: 100% !important; margin: 0; padding: 0;" ${attrs}>`;
                            }
                        });
                    }

                    innerContent = `
                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: ${flexAlign}; text-align: ${textAlign} !important; box-sizing: border-box; ${font} ${fontSize} ${color} ${weight} ${fontStyle} ${letterSpacing} ${lineHeight} ${bgStyle}">
                            ${rawTxt}
                        </div>
                    `;
                }

                elementsHtml += `
                    <div class="ticket-element-node" style="position: absolute; top: ${y}px; left: ${x}px; width: ${w}; height: ${h}; z-index: ${idx + 5}; ${transform} box-sizing: border-box; text-align: ${textAlign} !important;">
                        ${innerContent}
                    </div>
                `;
            });

            return `
                ${bgHtml}
                <div style="position: absolute; inset: 0; width: 100%; height: 100%;" class="ticket-elements-layer">
                    ${elementsHtml}
                </div>
            `;
        }

        async function generatePdfPrintWindow(evt, ticketsQueue, perPage, paperSize, orientation) {
            try {
                // 1. PRECARGA DE ASSETS A BASE64 DATA URLS
                Swal.update({
                    title: '⚡ 1/3 - Procesando Gráficos & QR Codes...',
                    html: 'Convirtiendo logotipos, imágenes de fondo y códigos QR a alta definición sin dependencias de red...'
                });

                function getFullAssetUrl(urlStr) {
                    if (!urlStr) return null;
                    if (urlStr.startsWith('data:')) return urlStr;
                    if (urlStr.startsWith('http://') || urlStr.startsWith('https://')) {
                        try {
                            const parsed = new URL(urlStr);
                            return window.location.origin + parsed.pathname + (parsed.search || '');
                        } catch(e) {
                            return urlStr;
                        }
                    }

                    let clean = urlStr.replace(/^\//, '');
                    if (clean.includes('storage/')) {
                        clean = 'storage/' + clean.split('storage/').pop();
                    } else if (clean.includes('images/')) {
                        clean = 'images/' + clean.split('images/').pop();
                    } else if (clean.startsWith('events/') || clean.startsWith('templates/') || clean.startsWith('media/') || clean.startsWith('uploads/')) {
                        clean = 'storage/' + clean;
                    }

                    return window.location.origin + '/' + clean;
                }

                const tpl = evt.template || { id: 1, name: 'Plantilla 1', bg_color: '#FFFFFF', positions: {}, elements: [] };
                const bgColor = tpl.bg_color || '#FFFFFF';

                const bgImgSrc = tpl.background ? getFullAssetUrl(tpl.background) : (tpl.bg_image ? getFullAssetUrl(tpl.bg_image) : null);
                const bannerImgSrc = evt.image ? getFullAssetUrl(evt.image) : null;
                const logoRawUrl = window.location.origin + '/images/logo-white.png';
                const boletoSrc = getFullAssetUrl('/images/Boleto.jpg');

                const [bgDataUrl, bannerDataUrl, logoDataUrl, boletoDataUrl] = await Promise.all([
                    bgImgSrc ? preloadImageAsDataUrl(bgImgSrc, 'bg') : Promise.resolve(''),
                    bannerImgSrc ? preloadImageAsDataUrl(bannerImgSrc, 'banner') : Promise.resolve(''),
                    logoRawUrl ? preloadImageAsDataUrl(logoRawUrl, 'logo') : Promise.resolve(''),
                    preloadImageAsDataUrl(boletoSrc, 'boleto')
                ]);

                const assetMap = {
                    bgDataUrl: bgDataUrl,
                    bannerDataUrl: bannerDataUrl,
                    logoDataUrl: logoDataUrl
                };

                let tplElements = tpl.elements || [];
                if ((!Array.isArray(tplElements) || tplElements.length === 0) && tpl.positions) {
                    let rawPos = typeof tpl.positions === 'string' ? JSON.parse(tpl.positions) : tpl.positions;
                    tplElements = convertPositionsToElements(rawPos);
                }

                for (let el of tplElements) {
                    if (el.src) {
                        const fullUrl = getFullAssetUrl(el.src);
                        el.src = await preloadImageAsDataUrl(fullUrl, 'el_' + el.id);
                    }
                }

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
                const title = evt.title || 'Evento Especial';

                // Detección de tamaño de papel (A4 o Carta)
                const isCarta = (paperSize.toUpperCase() === 'CARTA' || paperSize.toUpperCase() === 'LETTER');
                const isA5 = (paperSize.toUpperCase() === 'A5');
                const pageWidthMm = isCarta ? 215.9 : (isA5 ? 148 : 210);
                const pageHeightMm = isCarta ? 279.4 : (isA5 ? 210 : 297);
                const jsPdfFormat = isCarta ? 'letter' : (isA5 ? 'a5' : 'a4');

                // Parámetros de escalado
                let printCardWidth, printCardHeight, scaleRatio, cardMarginBottom, sheetPaddingTopBottom;

                if (perPage === 5) {
                    if (isCarta) {
                        printCardWidth = '110mm';
                        printCardHeight = '52.79mm';
                        scaleRatio = ((110 * 3.779527559) / 771).toFixed(6);
                        cardMarginBottom = '1.5mm';
                        sheetPaddingTopBottom = '4mm';
                    } else {
                        printCardWidth = '114mm';
                        printCardHeight = '54.71mm';
                        scaleRatio = ((114 * 3.779527559) / 771).toFixed(6);
                        cardMarginBottom = '2mm';
                        sheetPaddingTopBottom = '5mm';
                    }
                } else if (perPage === 4) {
                    if (isCarta) {
                        printCardWidth = '126mm';
                        printCardHeight = '60.47mm';
                        scaleRatio = ((126 * 3.779527559) / 771).toFixed(6);
                        cardMarginBottom = '3mm';
                        sheetPaddingTopBottom = '6mm';
                    } else {
                        printCardWidth = '130mm';
                        printCardHeight = '62.39mm';
                        scaleRatio = ((130 * 3.779527559) / 771).toFixed(6);
                        cardMarginBottom = '3.5mm';
                        sheetPaddingTopBottom = '8mm';
                    }
                } else if (perPage === 3) {
                    printCardWidth = '150mm';
                    printCardHeight = '72.00mm';
                    scaleRatio = ((150 * 3.779527559) / 771).toFixed(6);
                    cardMarginBottom = '6mm';
                    sheetPaddingTopBottom = '10mm';
                } else if (perPage === 2) {
                    printCardWidth = '170mm';
                    printCardHeight = '81.58mm';
                    scaleRatio = ((170 * 3.779527559) / 771).toFixed(6);
                    cardMarginBottom = '15mm';
                    sheetPaddingTopBottom = '15mm';
                } else { // 1
                    printCardWidth = '190mm';
                    printCardHeight = '91.18mm';
                    scaleRatio = ((190 * 3.779527559) / 771).toFixed(6);
                    cardMarginBottom = '0mm';
                    sheetPaddingTopBottom = '20mm';
                }

                let pagesHtml = '';
                const totalQty = ticketsQueue.length;

                for (let ticketIdx = 0; ticketIdx < totalQty; ticketIdx += perPage) {
                    let pageTicketsHtml = '';

                    for (let k = 0; k < perPage && (ticketIdx + k) < totalQty; k++) {
                        const ticketItem = ticketsQueue[ticketIdx + k];
                        let ticketNumberVal = ticketItem.ticketNumberVal || (ticketIdx + k + 1);
                        if (typeof ticketNumberVal === 'string') {
                            ticketNumberVal = parseInt(ticketNumberVal.replace(/[^0-9]/g, ''), 10) || (ticketIdx + k + 1);
                        }
                        const zoneName = ticketItem.zoneName;
                        const priceFormatted = ticketItem.zonePrice;
                        const ticketNum = 'N° ' + String(ticketNumberVal).padStart(5, '0');

                        let hash = ticketItem.validationHash;
                        if (!hash || hash.length !== 10) {
                            hash = 'VG' + Math.random().toString(36).substring(2, 10).toUpperCase();
                            if (hash.length > 10) hash = hash.substring(0, 10);
                            while (hash.length < 10) hash += 'X';
                        }
                        const qrPayload = ticketItem.qrPayload || `VGENC:${hash}`;
                        const qrDataUrl = ticketItem.qrDataUrl || getQrCodeDataUrl(qrPayload);

                        const dynamicData = {
                            title: title,
                            venue: venue,
                            city: address,
                            date: cleanDate,
                            time: cleanTime,
                            zone: zoneName,
                            price: 'S/ ' + priceFormatted,
                            buyer_name: ticketItem.buyerName || 'PÚBLICO GENERAL',
                            buyer_dni: ticketItem.buyerDni || '00000000',
                            ticket_number: ticketNum,
                            hash: hash,
                            qr_data_url: qrDataUrl
                        };

                        const canvasHtml = renderTicketCanvasContent({ ...tpl, elements: tplElements }, dynamicData, assetMap);

                        pageTicketsHtml += `
                            <div class="ticket-wrapper-card" style="width: ${printCardWidth}; height: ${printCardHeight}; position: relative; overflow: hidden; border-radius: 18px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); margin-bottom: ${cardMarginBottom}; flex-shrink: 0; box-sizing: border-box; background: ${bgColor};">
                                <div class="ticket-canvas-inner" style="width: 771px; height: 370px; transform: scale(${scaleRatio}); transform-origin: top left; position: absolute; top: 0; left: 0; background: ${bgColor}; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; overflow: hidden; border-radius: 18px; box-sizing: border-box;">
                                    ${canvasHtml}
                                </div>
                            </div>
                        `;
                    }

                    const sheetBgStyle = (perPage === 1) ? `background-image: url('${boletoDataUrl || boletoSrc}'); background-size: 100% 100%; background-position: center;` : 'background: #FFFFFF;';

                    pagesHtml += `
                        <div class="print-page-sheet" style="width: ${pageWidthMm}mm; min-height: ${pageHeightMm}mm; max-height: ${pageHeightMm}mm; page-break-after: always; break-after: page; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: ${perPage === 1 ? '3.5mm' : sheetPaddingTopBottom}; padding-bottom: ${sheetPaddingTopBottom}; overflow: hidden; ${sheetBgStyle}">
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
                        <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Anton&family=Bebas+Neue&family=Caveat:wght@600;700&family=Cinzel:wght@600;800&family=Comfortaa:wght@600;700&family=Dancing+Script:wght@600;700&family=Fira+Sans:ital,wght@0,400;0,700;1,400&family=Great+Vibes&family=Inter:wght@400;600;800;900&family=Lato:wght@400;700;900&family=Lobster&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Monoton&family=Montserrat:wght@400;700;900&family=Nunito:wght@400;700;900&family=Open+Sans:wght@400;700&family=Oswald:wght@500;700&family=Outfit:wght@400;700;900&family=Pacifico&family=Permanent+Marker&family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Poppins:wght@400;700;900&family=Raleway:wght@400;700;900&family=Righteous&family=Roboto:wght@400;700;900&family=Rubik:wght@400;700;900&family=Satisfy&family=Space+Grotesk:wght@500;700&family=Syne:wght@700;800&family=Work+Sans:wght@400;700;900&display=swap" rel="stylesheet">
                        <style>
                            .font-lato { font-family: 'Lato', sans-serif !important; }
                            .font-montserrat { font-family: 'Montserrat', sans-serif !important; }
                            .font-opensans { font-family: 'Open Sans', sans-serif !important; }
                            .font-roboto { font-family: 'Roboto', sans-serif !important; }
                            .font-inter { font-family: 'Inter', sans-serif !important; }
                            .font-poppins { font-family: 'Poppins', sans-serif !important; }
                            .font-outfit { font-family: 'Outfit', sans-serif !important; }
                            .font-raleway { font-family: 'Raleway', sans-serif !important; }
                            .font-nunito { font-family: 'Nunito', sans-serif !important; }
                            .font-rubik { font-family: 'Rubik', sans-serif !important; }
                            .font-work-sans { font-family: 'Work Sans', sans-serif !important; }
                            .font-oswald { font-family: 'Oswald', sans-serif !important; }
                            .font-bebas { font-family: 'Bebas Neue', sans-serif !important; }
                            .font-anton { font-family: 'Anton', sans-serif !important; }
                            .font-syne { font-family: 'Syne', sans-serif !important; }
                            .font-space-grotesk { font-family: 'Space Grotesk', sans-serif !important; }
                            .font-righteous { font-family: 'Righteous', sans-serif !important; }
                            .font-monoton { font-family: 'Monoton', sans-serif !important; }
                            .font-merriweather { font-family: 'Merriweather', serif !important; }
                            .font-playfair { font-family: 'Playfair Display', serif !important; }
                            .font-cinzel { font-family: 'Cinzel', serif !important; }
                            .font-abril { font-family: 'Abril Fatface', serif !important; }
                            .font-dancing { font-family: 'Dancing Script', cursive !important; }
                            .font-greatvibes { font-family: 'Great Vibes', cursive !important; }
                            .font-pacifico { font-family: 'Pacifico', cursive !important; }
                            .font-satisfy { font-family: 'Satisfy', cursive !important; }
                            .font-caveat { font-family: 'Caveat', cursive !important; }
                            .font-lobster { font-family: 'Lobster', cursive !important; }
                            .font-comfortaa { font-family: 'Comfortaa', cursive !important; }

                            .ticket-element-node p, 
                            .ticket-element-node div, 
                            .ticket-element-node span, 
                            .ticket-element-node h1, 
                            .ticket-element-node h2, 
                            .ticket-element-node h3, 
                            .ticket-element-node h4 {
                                margin: 0 !important;
                                padding: 0 !important;
                                text-align: inherit !important;
                                box-sizing: border-box !important;
                            }

                            @page { size: ${pageWidthMm}mm ${pageHeightMm}mm; margin: 0; }
                            * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                            body { margin: 0; padding: 0; background: #FFFFFF; color: #000000; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
                            .print-page-sheet { width: ${pageWidthMm}mm; min-height: ${pageHeightMm}mm; max-height: ${pageHeightMm}mm; page-break-after: always; break-after: page; page-break-inside: avoid; break-inside: avoid; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: ${sheetPaddingTopBottom}; padding-bottom: ${sheetPaddingTopBottom}; overflow: hidden; }
                            .print-page-sheet:last-child { page-break-after: auto; break-after: auto; }
                            .ticket-wrapper-card { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 20px; overflow: hidden; position: relative; box-sizing: border-box; flex-shrink: 0; page-break-inside: avoid; break-inside: avoid; }
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

            // Duplicar Evento Completo en MySQL con SweetAlert2
            const duplicateBtns = document.querySelectorAll('.btn-duplicate-event');
            duplicateBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const title = this.getAttribute('data-title');
                    const id = this.getAttribute('data-id');

                    Swal.fire({
                        title: '¿Duplicar Evento?',
                        html: `Se creará una copia completa de <b>"${title}"</b> incluyendo su información, todas las zonas de aforo y el diseño del boleto.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#A855F7',
                        cancelButtonColor: '#475569',
                        confirmButtonText: '📋 Sí, Duplicar Todo',
                        cancelButtonText: 'Cancelar',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: '⚡ Duplicando Evento...',
                                html: 'Clonando información, zonas de aforo y diseño del boleto...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); },
                                background: '#14141E',
                                color: '#FFFFFF'
                            });

                            fetch(`/admin/eventos/${id}/duplicar`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: '🎉 ¡Evento Duplicado!',
                                        text: data.message || `El evento "${title}" ha sido duplicado con éxito.`,
                                        icon: 'success',
                                        confirmButtonColor: '#FF5500',
                                        confirmButtonText: 'Entendido',
                                        background: '#14141E',
                                        color: '#FFFFFF'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error al Duplicar',
                                        text: data.message || 'Ocurrió un error al intentar duplicar el evento.',
                                        icon: 'error',
                                        confirmButtonColor: '#FF5500',
                                        background: '#14141E',
                                        color: '#FFFFFF'
                                    });
                                }
                            })
                            .catch(err => {
                                console.error('Error al duplicar evento:', err);
                                window.location.reload();
                            });
                        }
                    });
                });
            });

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
