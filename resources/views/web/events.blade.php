@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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
                                                        @if(($evt['sales_type'] ?? 'fisica') === 'ambos')
                                                            <span class="dash-badge-custom badge-purple" style="font-size: 0.7rem; padding: 0.15rem 0.6rem; color: #C084FC; border: 1px solid rgba(168, 85, 247, 0.4); background: rgba(168, 85, 247, 0.12);">🎫🌐 Físico + Virtual</span>
                                                        @elseif(($evt['sales_type'] ?? 'fisica') === 'fisica')
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
                                            <div style="min-width: 145px; max-width: 175px;">
                                                <div style="display: flex; justify-content: space-between; align-items: baseline; font-size: 0.85rem; font-weight: 800; margin-bottom: 0.35rem;">
                                                    <span style="color: #FFFFFF; font-weight: 900; letter-spacing: 0.3px;" class="event-capacity-text" title="Ventas regulares / Aforo regular">
                                                        <strong>{{ $evt['regular_sold'] ?? $evt['tickets_sold'] }}</strong> / {{ $evt['regular_capacity'] ?? $evt['total_capacity'] }}
                                                    </span>
                                                    <span style="color: var(--color-primary-orange); font-weight: 900; font-size: 0.85rem;">{{ $evt['capacity_percentage'] }}%</span>
                                                </div>
                                                <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.12); border-radius: 10px; overflow: hidden;" class="event-progress-bg">
                                                    <div style="height: 100%; width: {{ $evt['capacity_percentage'] }}%; background: linear-gradient(90deg, #FF5500, #FF1E3C); border-radius: 10px; transition: width 0.4s ease;"></div>
                                                </div>

                                                @if(!empty($evt['courtesy_enabled']) || ($evt['courtesy_capacity'] ?? 0) > 0 || ($evt['courtesy_sold'] ?? 0) > 0)
                                                    <div style="margin-top: 0.45rem; display: flex; align-items: center; justify-content: space-between; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.28); border-radius: 7px; padding: 0.22rem 0.5rem; font-size: 0.725rem;" title="Pases de cortesía emitidos / Cupo total de cortesías">
                                                        <span style="color: #6EE7B7; font-weight: 800; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                            <span>🎁</span> <span>Cortesías:</span>
                                                        </span>
                                                        <span style="color: #10B981; font-weight: 900; letter-spacing: 0.2px;">
                                                            <strong>{{ $evt['courtesy_sold'] ?? 0 }}</strong> / {{ $evt['courtesy_capacity'] ?? 0 }}
                                                        </span>
                                                    </div>
                                                @endif
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
