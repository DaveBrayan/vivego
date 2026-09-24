@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .filter-pill-btn {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #F1F5F9 !important;
            border: 1.5px solid rgba(255, 255, 255, 0.22) !important;
            font-weight: 800 !important;
            font-size: 0.825rem !important;
            padding: 0.45rem 1rem !important;
            border-radius: 12px !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.45rem !important;
            user-select: none !important;
            box-sizing: border-box !important;
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
                    @if(!isset($canDelete) || $canDelete)
                    <div>
                        <a href="{{ route('web.events.create') }}" class="btn btn-primary btn-save-settings" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; text-decoration: none;">
                            ➕ Crear Nuevo Evento
                        </a>
                    </div>
                    @endif
                </div>

                <!-- TABLA DE EVENTOS -->
                <div class="settings-card-box">
                    <div class="settings-card-header" style="flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">🎟️</div>
                            <div>
                                <h3 class="card-header-title">Catálogo Oficial de Eventos</h3>
                                <p class="card-header-subtitle">Lista de eventos registrados para venta en el marketplace Vive Go</p>
                            </div>
                        </div>

                        <!-- Filtros Rápidos, Paginación & Buscador -->
                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
                            <!-- Selector DataTable de Filas a Mostrar -->
                            <div style="display: flex; align-items: center; gap: 0.45rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); padding: 0.35rem 0.75rem; border-radius: 12px;">
                                <label for="eventsPerPageSelect" style="color: #94A3B8; font-size: 0.8rem; font-weight: 700; margin: 0; white-space: nowrap;">Mostrar:</label>
                                <select id="eventsPerPageSelect" style="background: #1E1E2D; color: #FFFFFF; border: 1px solid rgba(255, 255, 255, 0.18); padding: 0.3rem 0.6rem; border-radius: 8px; font-size: 0.825rem; font-weight: 800; cursor: pointer; outline: none;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="-1">Todos</option>
                                </select>
                            </div>

                            <!-- Filtros de Estado de Evento -->
                            <div style="display: flex; gap: 0.45rem; flex-wrap: wrap; align-items: center;">
                                <button type="button" class="filter-pill-btn active" onclick="if(window.eventsPagination){ window.eventsPagination.setFilter('all', this); }">
                                    <span>📋</span>
                                    <span>Todos ({{ count($events) }})</span>
                                </button>
                                <button type="button" class="filter-pill-btn" onclick="if(window.eventsPagination){ window.eventsPagination.setFilter('publicado', this); }">
                                    <span>🌐</span>
                                    <span>Publicados</span>
                                </button>
                                <button type="button" class="filter-pill-btn" onclick="if(window.eventsPagination){ window.eventsPagination.setFilter('finalizado', this); }">
                                    <span>⌛</span>
                                    <span>Finalizados</span>
                                </button>
                            </div>

                            <!-- Buscador en tiempo real de la tabla -->
                            <div style="position: relative; min-width: 220px;">
                                <input type="text" id="eventsTableSearch" placeholder="Buscar evento, recinto o categoría..." oninput="if(window.eventsPagination){ window.eventsPagination.filterTable(this.value); }" style="width: 100%; background: #1E1E2D; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px; padding: 0.5rem 0.85rem 0.5rem 2.2rem; color: #FFFFFF; font-size: 0.825rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--color-primary-orange)'" onblur="this.style.borderColor='rgba(255, 255, 255, 0.12)'">
                                <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-size: 0.85rem; color: #64748B; pointer-events: none;">🔍</span>
                            </div>
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
                            <tbody id="eventsTableBody">
                                @forelse($events as $index => $evt)
                                    <tr class="event-row-item" data-status="{{ strtolower($evt['status'] ?? 'publicado') }}" data-past="{{ !empty($evt['is_past']) || $evt['status'] === 'Finalizado' ? '1' : '0' }}">
                                        <td>
                                            <span class="row-index-num" style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
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
                                            <span class="dash-badge-custom {{ $evt['status_class'] }}" @if(!empty($evt['is_past']) || $evt['status'] === 'Finalizado') style="background: rgba(148, 163, 184, 0.15); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.35);" @endif>
                                                @if(!empty($evt['is_past']) || $evt['status'] === 'Finalizado')
                                                    ⌛ Finalizado
                                                @elseif($evt['status'] === 'Publicado')
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
                                                @if(!empty($evt['is_past']) || $evt['status'] === 'Finalizado')
                                                    <span class="dash-btn-icon-action" title="Evento Finalizado (Edición bloqueada)" style="opacity: 0.35; cursor: not-allowed; filter: grayscale(1); pointer-events: auto; display: inline-flex; align-items: center; justify-content: center;" onclick="Swal.fire({title: 'Evento Finalizado', text: 'Este evento ya ha concluido o fue finalizado. La edición se encuentra bloqueada.', icon: 'info', background: '#14141E', color: '#FFFFFF', confirmButtonColor: '#FF5500'});">✏️</span>
                                                    @if(!isset($canDelete) || $canDelete)
                                                    <button type="button" class="dash-btn-icon-action btn-duplicate-event" data-id="{{ $evt['id'] }}" data-title="{{ $evt['title'] }}" title="Duplicar Evento Completo" style="color: #A855F7;">📋</button>
                                                    <span class="dash-btn-icon-action" title="Evento Finalizado (Eliminación bloqueada)" style="opacity: 0.35; cursor: not-allowed; filter: grayscale(1); pointer-events: auto; display: inline-flex; align-items: center; justify-content: center;" onclick="Swal.fire({title: 'Evento Finalizado', text: 'No es posible eliminar un evento finalizado para preservar el histórico de ventas y boletos.', icon: 'info', background: '#14141E', color: '#FFFFFF', confirmButtonColor: '#FF5500'});">🗑️</span>
                                                    @endif
                                                @else
                                                    <a href="{{ route('web.events.edit', $evt['id']) }}" class="dash-btn-icon-action" title="Editar Evento">✏️</a>
                                                    @if(!isset($canDelete) || $canDelete)
                                                    <button type="button" class="dash-btn-icon-action btn-duplicate-event" data-id="{{ $evt['id'] }}" data-title="{{ $evt['title'] }}" title="Duplicar Evento Completo" style="color: #A855F7;">📋</button>
                                                    <button type="button" class="dash-btn-icon-action btn-delete-event" data-id="{{ $evt['id'] }}" data-title="{{ $evt['title'] }}" title="Eliminar Evento" style="color: #FF1E3C;">🗑️</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyEventsRow">
                                        <td colspan="6" style="text-align: center; padding: 3rem; color: #94A3B8;">
                                            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🎟️</div>
                                            <strong>No se encontraron eventos registrados en tu catálogo.</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- FOOTER DE PAGINACIÓN DATATABLE PRO MAX -->
                    <div id="eventsPaginationWrapper" class="dt-pagination-wrapper">
                        <div id="eventsPaginationInfo" class="dt-pagination-info">
                            Mostrando <strong>1</strong> a <strong>{{ min(10, count($events)) }}</strong> de <strong>{{ count($events) }}</strong> eventos
                        </div>
                        <div id="eventsPaginationControls" class="dt-pagination-controls">
                            <!-- Botones generados dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // MOTOR DE PAGINACIÓN Y FILTRADO TIPO DATATABLE PARA MIS EVENTOS
        // ==============================================================
        window.eventsPagination = {
            currentPage: 1,
            pageSize: 10,
            searchQuery: '',
            filterStatus: 'all',

            init: function () {
                const perPageSelect = document.getElementById('eventsPerPageSelect');
                if (perPageSelect) {
                    perPageSelect.addEventListener('change', (e) => {
                        const val = parseInt(e.target.value, 10);
                        this.pageSize = isNaN(val) ? 10 : val;
                        this.currentPage = 1;
                        this.render();
                    });
                }

                const searchInput = document.getElementById('eventsTableSearch');
                if (searchInput) {
                    const handleSearch = (e) => {
                        this.filterTable(e.target.value);
                    };
                    searchInput.addEventListener('input', handleSearch);
                    searchInput.addEventListener('keyup', handleSearch);
                    searchInput.addEventListener('change', handleSearch);
                }

                const globalNavSearch = document.getElementById('tableFilterInput');
                if (globalNavSearch) {
                    globalNavSearch.addEventListener('input', (e) => {
                        this.filterTable(e.target.value);
                    });
                }

                this.render();
            },

            setFilter: function (status, btn) {
                this.filterStatus = (status || 'all').toLowerCase();
                document.querySelectorAll('.filter-pill-btn').forEach(b => b.classList.remove('active'));
                if (btn) btn.classList.add('active');
                this.currentPage = 1;
                this.render();
            },

            filterTable: function (query) {
                const navInput = document.getElementById('tableFilterInput');
                const boxInput = document.getElementById('eventsTableSearch');
                const val = (query !== undefined) ? query : (boxInput ? boxInput.value : (navInput ? navInput.value : ''));
                this.searchQuery = (val || '').toLowerCase().trim();
                this.currentPage = 1;
                this.render();
            },

            getMatchingRows: function () {
                const rows = Array.from(document.querySelectorAll('#eventsTableBody tr.event-row-item'));
                
                const normalize = (str) => {
                    return (str || '')
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .trim();
                };

                const cleanQuery = normalize(this.searchQuery);

                return rows.filter(row => {
                    // Filtro por estado
                    const st = (row.getAttribute('data-status') || '').toLowerCase();
                    const isPast = row.getAttribute('data-past') === '1';

                    if (this.filterStatus === 'publicado') {
                        if (st !== 'publicado' || isPast) return false;
                    } else if (this.filterStatus === 'finalizado') {
                        if (st !== 'finalizado' && !isPast) return false;
                    }

                    // Filtro por texto de búsqueda
                    if (cleanQuery) {
                        const text = normalize(row.textContent || row.innerText || '');
                        if (!text.includes(cleanQuery)) {
                            return false;
                        }
                    }

                    return true;
                });
            },

            render: function () {
                const allRows = Array.from(document.querySelectorAll('#eventsTableBody tr.event-row-item'));
                const emptyRow = document.getElementById('emptyEventsRow');
                const matchingRows = this.getMatchingRows();
                const totalMatching = matchingRows.length;
                const totalAll = allRows.length;

                if (totalAll === 0) {
                    if (emptyRow) emptyRow.style.display = '';
                    this.updateControls(0, 0, 0, 0, 1);
                    return;
                }

                if (emptyRow) emptyRow.style.display = 'none';

                // Ocultar todas las filas
                allRows.forEach(row => { row.style.display = 'none'; });

                // Manejo de búsqueda sin resultados
                let noResultsRow = document.getElementById('noEventsResultsRow');
                if (totalMatching === 0) {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.id = 'noEventsResultsRow';
                        noResultsRow.innerHTML = `
                            <td colspan="6" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔍</div>
                                <strong>No se encontraron eventos que coincidan con los filtros aplicados.</strong>
                            </td>
                        `;
                        const tbody = document.getElementById('eventsTableBody');
                        if (tbody) tbody.appendChild(noResultsRow);
                    }
                    noResultsRow.style.display = '';
                    this.updateControls(0, 0, 0, totalAll, 1);
                    return;
                } else if (noResultsRow) {
                    noResultsRow.style.display = 'none';
                }

                // Calcular paginación
                const isAll = (this.pageSize === -1);
                const effectivePageSize = isAll ? totalMatching : this.pageSize;
                const totalPages = Math.ceil(totalMatching / (effectivePageSize || 10)) || 1;

                if (this.currentPage > totalPages) this.currentPage = totalPages;
                if (this.currentPage < 1) this.currentPage = 1;

                const startIndex = (this.currentPage - 1) * effectivePageSize;
                const endIndex = isAll ? totalMatching : Math.min(startIndex + effectivePageSize, totalMatching);

                // Mostrar únicamente las filas de la página actual
                for (let i = startIndex; i < endIndex; i++) {
                    if (matchingRows[i]) {
                        matchingRows[i].style.display = '';
                    }
                }

                this.updateControls(startIndex + 1, endIndex, totalMatching, totalAll, totalPages);
            },

            goToPage: function (page) {
                this.currentPage = page;
                this.render();
            },

            updateControls: function (start, end, totalMatching, totalAll, totalPages) {
                const infoEl = document.getElementById('eventsPaginationInfo');
                const controlsEl = document.getElementById('eventsPaginationControls');
                const wrapperEl = document.getElementById('eventsPaginationWrapper');

                if (wrapperEl) {
                    wrapperEl.style.display = totalAll === 0 ? 'none' : 'flex';
                }

                if (infoEl) {
                    if (totalAll === 0) {
                        infoEl.innerHTML = `Mostrando <strong>0</strong> eventos registrados`;
                    } else if (totalMatching === 0) {
                        infoEl.innerHTML = `Mostrando <strong>0</strong> de <strong>${totalAll}</strong> eventos (0 coincidencias)`;
                    } else if (this.searchQuery || this.filterStatus !== 'all') {
                        infoEl.innerHTML = `Mostrando <strong>${start}</strong> a <strong>${end}</strong> de <strong>${totalMatching}</strong> eventos filtrados <em>(de ${totalAll} totales)</em>`;
                    } else {
                        infoEl.innerHTML = `Mostrando <strong>${start}</strong> a <strong>${end}</strong> de <strong>${totalAll}</strong> eventos registrados`;
                    }
                }

                if (!controlsEl) return;
                controlsEl.innerHTML = '';

                if (totalMatching <= 0 || (this.pageSize === -1 && totalMatching > 0) || totalPages <= 1) {
                    return;
                }

                // Botón Primera Página
                const firstBtn = document.createElement('button');
                firstBtn.type = 'button';
                firstBtn.className = 'dt-page-btn';
                firstBtn.innerHTML = '«';
                firstBtn.title = 'Primera página';
                firstBtn.disabled = this.currentPage <= 1;
                firstBtn.onclick = () => this.goToPage(1);
                controlsEl.appendChild(firstBtn);

                // Botón Anterior
                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = 'dt-page-btn';
                prevBtn.innerHTML = '‹ Ant';
                prevBtn.title = 'Página anterior';
                prevBtn.disabled = this.currentPage <= 1;
                prevBtn.onclick = () => this.goToPage(this.currentPage - 1);
                controlsEl.appendChild(prevBtn);

                // Páginas numéricas
                const maxButtons = 5;
                let startPage = Math.max(1, this.currentPage - Math.floor(maxButtons / 2));
                let endPage = Math.min(totalPages, startPage + maxButtons - 1);

                if (endPage - startPage + 1 < maxButtons) {
                    startPage = Math.max(1, endPage - maxButtons + 1);
                }

                if (startPage > 1) {
                    const p1Btn = document.createElement('button');
                    p1Btn.type = 'button';
                    p1Btn.className = 'dt-page-btn';
                    p1Btn.textContent = '1';
                    p1Btn.onclick = () => this.goToPage(1);
                    controlsEl.appendChild(p1Btn);

                    if (startPage > 2) {
                        const dots = document.createElement('span');
                        dots.className = 'dt-page-dots';
                        dots.textContent = '...';
                        controlsEl.appendChild(dots);
                    }
                }

                for (let p = startPage; p <= endPage; p++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.type = 'button';
                    pageBtn.className = 'dt-page-btn' + (p === this.currentPage ? ' active' : '');
                    pageBtn.textContent = p;
                    pageBtn.onclick = () => this.goToPage(p);
                    controlsEl.appendChild(pageBtn);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.className = 'dt-page-dots';
                        dots.textContent = '...';
                        controlsEl.appendChild(dots);
                    }

                    const lastPBtn = document.createElement('button');
                    lastPBtn.type = 'button';
                    lastPBtn.className = 'dt-page-btn';
                    lastPBtn.textContent = totalPages;
                    lastPBtn.onclick = () => this.goToPage(totalPages);
                    controlsEl.appendChild(lastPBtn);
                }

                // Botón Siguiente
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = 'dt-page-btn';
                nextBtn.innerHTML = 'Sig ›';
                nextBtn.title = 'Página siguiente';
                nextBtn.disabled = this.currentPage >= totalPages;
                nextBtn.onclick = () => this.goToPage(this.currentPage + 1);
                controlsEl.appendChild(nextBtn);

                // Botón Última Página
                const lastBtn = document.createElement('button');
                lastBtn.type = 'button';
                lastBtn.className = 'dt-page-btn';
                lastBtn.innerHTML = '»';
                lastBtn.title = 'Última página';
                lastBtn.disabled = this.currentPage >= totalPages;
                lastBtn.onclick = () => this.goToPage(totalPages);
                controlsEl.appendChild(lastBtn);
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar paginación de Mis Eventos
            if (window.eventsPagination) {
                window.eventsPagination.init();
            }

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
