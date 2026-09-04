@extends('layouts.app')

@section('title', 'Control de Acceso & Asistentes | Vive Go')

@push('styles')
<style>
    .filter-pill-btn {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #F1F5F9 !important;
        border: 1.5px solid rgba(255, 255, 255, 0.22) !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        padding: 0.55rem 1.25rem !important;
        border-radius: 12px !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.45rem !important;
        user-select: none !important;
    }
    .filter-pill-btn:hover {
        background: rgba(255, 255, 255, 0.16) !important;
        border-color: rgba(255, 255, 255, 0.45) !important;
        color: #FFFFFF !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    }
    .filter-pill-btn.active {
        background: linear-gradient(135deg, #FF5500, #FF7733) !important;
        color: #FFFFFF !important;
        border-color: #FF5500 !important;
        box-shadow: 0 4px 16px rgba(255, 85, 0, 0.5) !important;
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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar evento, recinto o modalidad de acceso...">
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
                        <span class="settings-tag">👥 CONTROL DE ACCESO &amp; ASISTENTES</span>
                        <h1 class="settings-page-title">Control de Acceso & Asistentes</h1>
                        <p class="settings-page-subtitle">Monitorea la asistencia en vivo de cada evento, valida entradas mediante el <strong>Scanner QR interactivo</strong> y evita duplicados o falsificaciones.</p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <a href="{{ route('web.events') }}" class="btn btn-secondary" style="padding: 0.85rem 1.4rem; font-size: 0.95rem; text-decoration: none;">
                            🎟️ Ver Mis Eventos
                        </a>
                        <a href="{{ route('web.box_office') }}" class="btn btn-primary btn-save-settings" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; text-decoration: none;">
                            💰 Ir a Taquilla POS
                        </a>
                    </div>
                </div>

                <!-- LISTADO DE EVENTOS PARA CONTROL DE ACCESO -->
                <div class="settings-card-box">
                    <div class="settings-card-header" style="flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">📲</div>
                            <div>
                                <h3 class="card-header-title">Puntos de Control de Acceso por Evento</h3>
                                <p class="card-header-subtitle">Selecciona un evento y haz clic en <strong>"Gestionar Asistentes / Scanner QR"</strong> para abrir la terminal de validación con cámara en tiempo real.</p>
                            </div>
                        </div>

                        <!-- Filtros Rápidos con Alta Visibilidad y Contraste -->
                        <div style="display: flex; gap: 0.6rem; flex-wrap: wrap; align-items: center;">
                            <button type="button" class="filter-pill-btn active" onclick="filterAttendees('all', this)">
                                <span>📋</span>
                                <span>Todos ({{ count($events) }})</span>
                            </button>
                            <button type="button" class="filter-pill-btn" onclick="filterAttendees('fisica', this)">
                                <span>🎫</span>
                                <span>Solo Física</span>
                            </button>
                            <button type="button" class="filter-pill-btn" onclick="filterAttendees('virtual', this)">
                                <span>🌐</span>
                                <span>Solo Virtual</span>
                            </button>
                        </div>
                    </div>

                    <!-- TABLA OFICIAL DE ASISTENTES Y CONTROL DE ACCESO -->
                    <div class="dash-table-container">
                        <table class="dash-table" id="attendeesTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Evento & Modalidad</th>
                                    <th>Fecha & Local</th>
                                    <th>Asistencia en Vivo</th>
                                    <th>Por Ingresar</th>
                                    <th style="text-align: right;">Punto de Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $index => $evt)
                                    <tr class="attendee-row-item" data-sales-type="{{ $evt['sales_type'] ?? 'fisica' }}">
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.9rem;">
                                                <div style="width: 54px; height: 54px; border-radius: 14px; overflow: hidden; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.15); background: #0A0A10;">
                                                    <img src="{{ $evt['image'] ?? ($evt['banner_image'] ?? '') }}" alt="{{ $evt['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <a href="{{ route('web.attendees.scanner', $evt['id']) }}" class="dash-event-name" style="display: block; font-size: 0.95rem; font-weight: 800;" title="{{ $evt['title'] }}">{{ $evt['title'] }}</a>
                                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem; flex-wrap: wrap;">
                                                        @if(($evt['sales_type'] ?? 'fisica') === 'ambos')
                                                            <span class="dash-badge-custom badge-purple" style="font-size: 0.7rem; padding: 0.15rem 0.6rem; color: #C084FC; border: 1px solid rgba(168, 85, 247, 0.4); background: rgba(168, 85, 247, 0.12);">🎫🌐 Físico + Virtual</span>
                                                        @elseif(($evt['sales_type'] ?? 'fisica') === 'fisica')
                                                            <span class="dash-badge-custom badge-orange" style="font-size: 0.7rem; padding: 0.15rem 0.6rem;">🎫 Venta Física</span>
                                                        @else
                                                            <span class="dash-badge-custom badge-cyan" style="font-size: 0.7rem; padding: 0.15rem 0.6rem; color: #00F0FF; border: 1px solid rgba(0,240,255,0.4); background: rgba(0,240,255,0.1);">🌐 Venta Virtual</span>
                                                        @endif
                                                        <span class="dash-badge-custom badge-blue" style="font-size: 0.7rem; padding: 0.15rem 0.6rem;">{{ $evt['category'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 0.15rem;">
                                                <span style="font-weight: 700; color: #FFFFFF;">🗓️ {{ $evt['date_formatted'] }}</span>
                                                <small style="color: #94A3B8; font-weight: 600;">📍 {{ $evt['venue'] ?? ($evt['venue_name'] ?? 'Recinto Principal') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width: 140px;">
                                                <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.85rem; font-weight: 800; margin-bottom: 0.35rem;">
                                                    <span style="color: #FFFFFF; font-weight: 900; letter-spacing: 0.3px;"><strong>{{ $evt['checked_in_count'] }}</strong> / {{ $evt['tickets_issued'] }}</span>
                                                    <span style="color: #10B981; font-weight: 900; font-size: 0.85rem;">{{ $evt['attendance_rate'] }}%</span>
                                                </div>
                                                <div style="width: 100%; height: 7px; background: rgba(255,255,255,0.12); border-radius: 10px; overflow: hidden;">
                                                    <div style="height: 100%; width: {{ $evt['attendance_rate'] }}%; background: linear-gradient(90deg, #10B981, #00F0FF); border-radius: 10px; transition: width 0.4s ease;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.95rem; font-weight: 800; color: {{ $evt['pending_count'] > 0 ? '#F59E0B' : '#10B981' }};">
                                                ⏳ {{ number_format($evt['pending_count']) }}
                                            </div>
                                            <small style="color: #94A3B8; font-size: 0.75rem;">por ingresar</small>
                                        </td>
                                         <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('web.attendees.scanner', $evt['id']) }}" class="btn btn-primary btn-sm" style="font-weight: 800; text-decoration: none; padding: 0.65rem 1.35rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 12px; background: linear-gradient(135deg, #10B981, #059669); border-color: #10B981; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);">
                                                <span>📊</span>
                                                <span>Control en Vivo</span>
                                            </a>
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function getEventScannerUrl(id) {
            return `${window.location.origin}/scanner/${id}`;
        }

        function copyEventScannerLink(id) {
            const url = getEventScannerUrl(id);
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '📋 ¡Enlace del Scanner Copiado!',
                        showConfirmButton: false,
                        timer: 2500,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }).catch(() => fallbackCopyLink(url));
            } else {
                fallbackCopyLink(url);
            }
        }

        function fallbackCopyLink(text) {
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

        function filterAttendees(type, btn) {
            document.querySelectorAll('.filter-pill-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            const rows = document.querySelectorAll('.attendee-row-item');
            rows.forEach(row => {
                const salesType = row.getAttribute('data-sales-type') || 'fisica';
                if (type === 'all' || salesType === type) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Buscador en tiempo real de la tabla
            const searchInput = document.getElementById('tableFilterInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.attendee-row-item');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(q) ? '' : 'none';
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
        });
    </script>
@endpush
