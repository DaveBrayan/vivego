@extends('layouts.app')

@section('title', 'Taquilla & Ventas POS | Vive Go')

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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar evento, recinto o modalidad de taquilla...">
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
                        <span class="settings-tag">💰 GESTIÓN DE TAQUILLA & PUNTOS DE VENTA (POS)</span>
                        <h1 class="settings-page-title">Taquilla & Ventas</h1>
                        <p class="settings-page-subtitle">Abre el punto de venta presencial, registra compras con cálculo de vuelto, descuenta stock e imprime recibos térmicos oficiales.</p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <a href="{{ route('web.events') }}" class="btn btn-secondary" style="padding: 0.85rem 1.4rem; font-size: 0.95rem; text-decoration: none;">
                            🎟️ Ver Mis Eventos
                        </a>
                        <a href="{{ route('web.events.create') }}" class="btn btn-primary btn-save-settings" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; text-decoration: none;">
                            ➕ Nuevo Evento
                        </a>
                    </div>
                </div>

                <!-- LISTADO DE EVENTOS PARA GESTIÓN DE TAQUILLA -->
                <div class="settings-card-box">
                    <div class="settings-card-header" style="flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">🛒</div>
                            <div>
                                <h3 class="card-header-title">Puntos de Venta (Taquillas de Eventos)</h3>
                                <p class="card-header-subtitle">Selecciona un evento y haz clic en <strong>"Gestionar Taquilla"</strong> para abrir la caja registradora, registrar ventas y emitir boletos térmicos.</p>
                            </div>
                        </div>

                        <!-- Filtros Rápidos, Paginación & Buscador -->
                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
                            <!-- Selector DataTable de Filas a Mostrar -->
                            <div style="display: flex; align-items: center; gap: 0.45rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); padding: 0.35rem 0.75rem; border-radius: 12px;">
                                <label for="boxOfficePerPageSelect" style="color: #94A3B8; font-size: 0.8rem; font-weight: 700; margin: 0; white-space: nowrap;">Mostrar:</label>
                                <select id="boxOfficePerPageSelect" style="background: #1E1E2D; color: #FFFFFF; border: 1px solid rgba(255, 255, 255, 0.18); padding: 0.3rem 0.6rem; border-radius: 8px; font-size: 0.825rem; font-weight: 800; cursor: pointer; outline: none;">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="-1">Todos</option>
                                </select>
                            </div>

                            <!-- Filtros Rápidos con Alta Visibilidad -->
                            <div style="display: flex; gap: 0.45rem; flex-wrap: wrap; align-items: center;">
                                <button type="button" class="filter-pill-btn active" onclick="if(window.boxOfficePagination){ window.boxOfficePagination.setFilter('all', this); }">
                                    <span>📋</span>
                                    <span>Todos ({{ count($events) }})</span>
                                </button>
                                <button type="button" class="filter-pill-btn" onclick="if(window.boxOfficePagination){ window.boxOfficePagination.setFilter('fisica', this); }">
                                    <span>🎫</span>
                                    <span>Solo Física ({{ $kpis['physical_count'] }})</span>
                                </button>
                                <button type="button" class="filter-pill-btn" onclick="if(window.boxOfficePagination){ window.boxOfficePagination.setFilter('virtual', this); }">
                                    <span>🌐</span>
                                    <span>Solo Virtual ({{ $kpis['virtual_count'] }})</span>
                                </button>
                            </div>

                            <!-- Buscador en tiempo real de la tabla -->
                            <div style="position: relative; min-width: 220px;">
                                <input type="text" id="boxOfficeTableSearch" placeholder="Buscar por evento o recinto..." oninput="if(window.boxOfficePagination){ window.boxOfficePagination.filterTable(this.value); }" style="width: 100%; background: #1E1E2D; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px; padding: 0.5rem 0.85rem 0.5rem 2.2rem; color: #FFFFFF; font-size: 0.825rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--color-primary-orange)'" onblur="this.style.borderColor='rgba(255, 255, 255, 0.12)'">
                                <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-size: 0.85rem; color: #64748B; pointer-events: none;">🔍</span>
                            </div>
                        </div>
                    </div>

                    <!-- TABLA OFICIAL DE TAQUILLA IDÉNTICA A MIS EVENTOS -->
                    <div class="dash-table-container">
                        <table class="dash-table" id="boxOfficeTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Evento & Modalidad</th>
                                    <th>Ventas & Aforo</th>
                                    <th>Stock Libre</th>
                                    <th>Recaudación</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acción de Taquilla</th>
                                </tr>
                            </thead>
                            <tbody id="boxOfficeTableBody">
                                @forelse($events as $index => $evt)
                                    <tr class="boxoffice-row-item" data-sales-type="{{ $evt['sales_type'] ?? 'fisica' }}">
                                        <td>
                                            <span class="row-index-num" style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.9rem;">
                                                <div style="width: 54px; height: 54px; border-radius: 14px; overflow: hidden; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.15); background: #0A0A10;">
                                                    <img src="{{ $evt['image'] }}" alt="{{ $evt['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <a href="{{ route('web.box_office.manage', $evt['id']) }}" class="dash-event-name" style="display: block; font-size: 0.95rem; font-weight: 800;" title="{{ $evt['title'] }}">{{ $evt['title'] }}</a>
                                                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-top: 0.2rem; flex-wrap: wrap; font-size: 0.775rem; color: #94A3B8; font-weight: 600;">
                                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; color: #CBD5E1;">🗓️ {{ $evt['date_formatted'] }}</span>
                                                        <span style="color: #64748B;">•</span>
                                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; color: #94A3B8;">📍 {{ $evt['venue'] }}</span>
                                                    </div>
                                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.35rem; flex-wrap: wrap;">
                                                        @if(($evt['sales_type'] ?? 'fisica') === 'ambos')
                                                             <span class="dash-badge-custom badge-purple" style="font-size: 0.7rem; padding: 0.15rem 0.6rem; color: #C084FC; border: 1px solid rgba(168, 85, 247, 0.4); background: rgba(168, 85, 247, 0.12);">🎫🌐 Venta Mixta (Taquilla + Online)</span>
                                                        @elseif(($evt['sales_type'] ?? 'fisica') === 'fisica')
                                                            <span class="dash-badge-custom badge-orange" style="font-size: 0.7rem; padding: 0.15rem 0.6rem;">🎫 Venta Física (Taquilla)</span>
                                                        @else
                                                            <span class="dash-badge-custom badge-cyan" style="font-size: 0.7rem; padding: 0.15rem 0.6rem; color: #00F0FF; border: 1px solid rgba(0,240,255,0.4); background: rgba(0,240,255,0.1);">🌐 Venta Virtual (Online)</span>
                                                        @endif
                                                        <span class="dash-badge-custom badge-blue" style="font-size: 0.7rem; padding: 0.15rem 0.6rem;">{{ $evt['category_icon'] }} {{ $evt['category'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width: 140px;">
                                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 800; margin-bottom: 0.35rem;">
                                                    <span style="color: #E2E8F0;">{{ number_format($evt['tickets_sold']) }} / {{ number_format($evt['total_capacity']) }}</span>
                                                    <span style="color: var(--color-primary-orange);">{{ $evt['capacity_percentage'] }}%</span>
                                                </div>
                                                <div style="width: 100%; height: 7px; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden;">
                                                    <div style="height: 100%; width: {{ $evt['capacity_percentage'] }}%; background: linear-gradient(90deg, #FF5500, #FF1E3C); border-radius: 10px;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.95rem; font-weight: 800; color: {{ $evt['remaining_stock'] > 0 ? '#10B981' : '#EF4444' }};">
                                                📦 {{ number_format($evt['remaining_stock']) }}
                                            </div>
                                            <small style="color: #94A3B8; font-size: 0.75rem;">entradas libres</small>
                                        </td>
                                        <td>
                                            <div style="font-weight: 900; font-size: 1rem; color: #FFFFFF;">
                                                {{ $evt['revenue_formatted'] }}
                                            </div>
                                            <small style="color: #94A3B8; font-size: 0.75rem;">{{ $evt['sales_count'] ?? 0 }} transacciones</small>
                                        </td>
                                        <td>
                                            @if(!empty($evt['is_past']) || $evt['status'] === 'Finalizado')
                                                <div style="display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                                                    <span class="dash-badge-custom badge-gray" style="background: rgba(148, 163, 184, 0.15); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.35); font-weight: 800;">
                                                        ⌛ Finalizado
                                                    </span>
                                                    <small style="color: #64748B; font-size: 0.7rem;">Caja y ventas cerradas</small>
                                                </div>
                                            @else
                                                <div style="display: flex; flex-direction: column; gap: 0.45rem; align-items: flex-start;">
                                                    <span class="dash-badge-custom {{ $evt['status_class'] }}">
                                                        @if($evt['status'] === 'Publicado') ✓ Publicado @elseif($evt['status'] === 'Agotado') 🚫 Agotado @else ⏳ {{ $evt['status'] }} @endif
                                                    </span>
                                                    @if(!empty($evt['is_date_passed']))
                                                        <button type="button" class="btn btn-sm btn-finalize-boxoffice" data-id="{{ $evt['id'] }}" data-title="{{ $evt['title'] }}" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171; font-size: 0.725rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.25)'" onmouseout="this.style.background='rgba(239,68,68,0.12)'" title="El día del evento ya pasó. Finalizar y cerrar caja.">
                                                                <span>⏹️</span>
                                                                <span>Finalizar Evento</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            @if(!empty($evt['is_past']) || $evt['status'] === 'Finalizado')
                                                <a href="{{ route('web.box_office.manage', $evt['id']) }}" class="btn btn-sm" style="font-weight: 800; text-decoration: none; padding: 0.6rem 1.15rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 12px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(59, 130, 246, 0.25)); border: 1.5px solid rgba(99, 102, 241, 0.6); color: #C7D2FE; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25); transition: all 0.2s ease;">
                                                    <span>📊</span>
                                                    <span>Ver Historial y Reportes</span>
                                                </a>
                                            @else
                                                <a href="{{ route('web.box_office.manage', $evt['id']) }}" class="btn btn-primary btn-sm" style="font-weight: 800; text-decoration: none; padding: 0.6rem 1.15rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 12px; box-shadow: 0 4px 14px rgba(255, 85, 0, 0.35);">
                                                    <span>💼</span>
                                                    <span>Gestionar Taquilla</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyBoxOfficeRow">
                                        <td colspan="7" style="text-align: center; padding: 3rem; color: #94A3B8;">
                                            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🎟️</div>
                                            <strong>No se encontraron eventos registrados para gestión de taquilla.</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- FOOTER DE PAGINACIÓN DATATABLE PRO MAX -->
                    <div id="boxOfficePaginationWrapper" class="dt-pagination-wrapper">
                        <div id="boxOfficePaginationInfo" class="dt-pagination-info">
                            Mostrando <strong>1</strong> a <strong>{{ min(10, count($events)) }}</strong> de <strong>{{ count($events) }}</strong> eventos
                        </div>
                        <div id="boxOfficePaginationControls" class="dt-pagination-controls">
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
        // MOTOR DE PAGINACIÓN Y FILTRADO TIPO DATATABLE PARA TAQUILLA
        // ==============================================================
        window.boxOfficePagination = {
            currentPage: 1,
            pageSize: 10,
            searchQuery: '',
            filterType: 'all',

            init: function () {
                const perPageSelect = document.getElementById('boxOfficePerPageSelect');
                if (perPageSelect) {
                    perPageSelect.addEventListener('change', (e) => {
                        const val = parseInt(e.target.value, 10);
                        this.pageSize = isNaN(val) ? 10 : val;
                        this.currentPage = 1;
                        this.render();
                    });
                }

                const searchInput = document.getElementById('boxOfficeTableSearch');
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

            setFilter: function (type, btn) {
                this.filterType = type || 'all';
                document.querySelectorAll('.filter-pill-btn').forEach(b => b.classList.remove('active'));
                if (btn) btn.classList.add('active');
                this.currentPage = 1;
                this.render();
            },

            filterTable: function (query) {
                const navInput = document.getElementById('tableFilterInput');
                const boxInput = document.getElementById('boxOfficeTableSearch');
                const val = (query !== undefined) ? query : (boxInput ? boxInput.value : (navInput ? navInput.value : ''));
                this.searchQuery = (val || '').toLowerCase().trim();
                this.currentPage = 1;
                this.render();
            },

            getMatchingRows: function () {
                const rows = Array.from(document.querySelectorAll('#boxOfficeTableBody tr.boxoffice-row-item'));
                
                const normalize = (str) => {
                    return (str || '')
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .trim();
                };

                const cleanQuery = normalize(this.searchQuery);

                return rows.filter(row => {
                    // Filtro por tipo de venta
                    const salesType = row.getAttribute('data-sales-type') || 'fisica';
                    if (this.filterType !== 'all') {
                        if (this.filterType === 'fisica' && salesType !== 'fisica' && salesType !== 'ambos') {
                            return false;
                        }
                        if (this.filterType === 'virtual' && salesType !== 'virtual' && salesType !== 'ambos') {
                            return false;
                        }
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
                const allRows = Array.from(document.querySelectorAll('#boxOfficeTableBody tr.boxoffice-row-item'));
                const emptyRow = document.getElementById('emptyBoxOfficeRow');
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
                let noResultsRow = document.getElementById('noBoxOfficeResultsRow');
                if (totalMatching === 0) {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.id = 'noBoxOfficeResultsRow';
                        noResultsRow.innerHTML = `
                            <td colspan="7" style="text-align: center; padding: 2.5rem; color: #94A3B8;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔍</div>
                                <strong>No se encontraron eventos que coincidan con los filtros aplicados.</strong>
                            </td>
                        `;
                        const tbody = document.getElementById('boxOfficeTableBody');
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
                const infoEl = document.getElementById('boxOfficePaginationInfo');
                const controlsEl = document.getElementById('boxOfficePaginationControls');
                const wrapperEl = document.getElementById('boxOfficePaginationWrapper');

                if (wrapperEl) {
                    wrapperEl.style.display = totalAll === 0 ? 'none' : 'flex';
                }

                if (infoEl) {
                    if (totalAll === 0) {
                        infoEl.innerHTML = `Mostrando <strong>0</strong> eventos registrados`;
                    } else if (totalMatching === 0) {
                        infoEl.innerHTML = `Mostrando <strong>0</strong> de <strong>${totalAll}</strong> eventos (0 coincidencias)`;
                    } else if (this.searchQuery || this.filterType !== 'all') {
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
            // Inicializar paginación de Taquilla
            if (window.boxOfficePagination) {
                window.boxOfficePagination.init();
            }

            // Finalizar evento desde la tabla de Taquilla con confirmación SweetAlert2
            document.querySelectorAll('.btn-finalize-boxoffice').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');

                    Swal.fire({
                        title: '¿Finalizar Evento?',
                        html: `<div style="text-align: left; font-size: 0.9rem; line-height: 1.5; color: #CBD5E1;">
                                <p style="margin-bottom: 0.75rem;">¿Estás seguro de finalizar el evento <strong>"${title}"</strong>?</p>
                                <ul style="margin: 0; padding-left: 1.25rem; color: #FCA5A5; font-size: 0.85rem;">
                                    <li>Se <strong>cerrará la caja de taquilla POS</strong> y la venta física.</li>
                                    <li>Se <strong>desactivará la compra y emisión de entradas</strong> (físicas, virtuales y cortesías).</li>
                                    <li>En <strong>Mis Eventos</strong> quedará bloqueada la edición y eliminación.</li>
                                </ul>
                               </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#475569',
                        confirmButtonText: '⏹️ Sí, Finalizar Evento',
                        cancelButtonText: 'Cancelar',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Cerrando Taquilla y Evento...',
                                html: 'Actualizando estado y bloqueando canales de venta...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); },
                                background: '#14141E',
                                color: '#FFFFFF'
                            });

                            fetch(`/admin/taquilla/${id}/finalizar`, {
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
                                        title: '¡Evento Finalizado!',
                                        text: data.message || 'El evento ha sido finalizado con éxito.',
                                        icon: 'success',
                                        confirmButtonColor: '#FF5500',
                                        confirmButtonText: 'Aceptar',
                                        background: '#14141E',
                                        color: '#FFFFFF'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: data.message || 'No se pudo finalizar el evento.',
                                        icon: 'error',
                                        confirmButtonColor: '#FF5500',
                                        background: '#14141E',
                                        color: '#FFFFFF'
                                    });
                                }
                            })
                            .catch(err => {
                                console.error('Error al finalizar evento:', err);
                                Swal.fire({
                                    title: 'Error de Red',
                                    text: 'Ocurrió un error al comunicarse con el servidor.',
                                    icon: 'error',
                                    confirmButtonColor: '#FF5500',
                                    background: '#14141E',
                                    color: '#FFFFFF'
                                });
                            });
                        }
                    });
                });
            });

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
