@extends('layouts.app')

@section('title', 'Dashboard de Organizador | Vive Go')

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN PRO MAX HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DEL DASHBOARD -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR DEL PANEL -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" class="dash-search-input"
                        placeholder="Buscar eventos, órdenes, asistentes o reportes (Ctrl + K)...">
                    <span class="dash-kbd-shortcut">⌘K</span>
                </div>

                <div class="dash-top-actions">
                    <!-- Selector de Período -->
                    <div class="dash-period-select-capsule">
                        <span class="dash-period-icon">📅</span>
                        <select class="dash-period-select">
                            <option value="7d">Últimos 7 días</option>
                            <option value="30d" selected>Últimos 30 días</option>
                            <option value="year">Año 2026</option>
                        </select>
                    </div>

                    <!-- Botón Selector de Tema Claro / Oscuro -->
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema (Claro / Oscuro)">
                        <span id="themeToggleIcon">☀️</span>
                    </button>

                    <!-- Notificaciones Bell -->
                    <button class="dash-icon-btn" id="btnNotifications" title="Notificaciones">
                        <span>🔔</span>
                        <span class="dash-unread-dot"></span>
                    </button>

                    <!-- Botón CTA Nuevo Evento -->
                    <a href="#" class="btn btn-primary dash-btn-create">
                        <span>+ Crear Nuevo Evento</span>
                    </a>
                </div>
            </header>

            <!-- DASHBOARD CONTAINER (BENTO GRID) -->
            <div class="dash-container">
                <!-- HERO WELCOME BANNER -->
                <div class="dash-hero-welcome-card">
                    <div class="dash-hero-welcome-content">
                        <span class="dash-hero-tag">🔥 CENTRAL DE CONTROL VIVE GO</span>
                        <h1 class="dash-hero-title">¡Hola, {{ $organizer['name'] }}! 👋</h1>
                        <p class="dash-hero-subtitle">Tus eventos tienen un excelente rendimiento esta semana. Has alcanzado
                            el <strong>{{ $metrics['tickets_percentage'] }}%</strong> de ocupación total.</p>

                        <div class="dash-hero-actions">
                            <a href="{{ route('web.event.detail', 'mezcla-2026') }}" class="btn btn-white btn-sm"
                                target="_blank">
                                <span>👁️ Ver Evento En Vivo</span>
                            </a>
                            <button class="btn btn-glass-white btn-sm" id="btnExportReport">
                                <span>📥 Descargar Reporte PDF</span>
                            </button>
                        </div>
                    </div>

                    <div class="dash-hero-badge-graphic">
                        <div class="dash-hero-live-circle">
                            <span class="dash-live-pulse-dot"></span>
                            <span>TAQUILLA EN VIVO</span>
                        </div>
                    </div>
                </div>

                <!-- KPI METRIC CARDS (GRID DE 4 COLUMNAS) -->
                <div class="dash-kpi-grid">
                    <!-- KPI 1: Ventas Totales -->
                    <div class="dash-kpi-card glow-orange">
                        <div class="dash-kpi-header">
                            <span class="dash-kpi-label">Ventas Totales</span>
                            <span class="dash-kpi-icon">💰</span>
                        </div>
                        <div class="dash-kpi-value">S/ {{ $metrics['total_sales'] }}</div>
                        <div class="dash-kpi-footer">
                            <span class="dash-badge-trend positive">↑ {{ $metrics['total_sales_growth'] }}</span>
                            <span class="dash-kpi-subtext">vs. mes anterior</span>
                        </div>
                    </div>

                    <!-- KPI 2: Entradas Vendidas -->
                    <div class="dash-kpi-card glow-red">
                        <div class="dash-kpi-header">
                            <span class="dash-kpi-label">Entradas Vendidas</span>
                            <span class="dash-kpi-icon">🎟️</span>
                        </div>
                        <div class="dash-kpi-value">{{ number_format($metrics['tickets_sold']) }} <span
                                class="dash-kpi-total">/ {{ number_format($metrics['tickets_total']) }}</span></div>
                        <div class="dash-kpi-progress-bar">
                            <div class="dash-kpi-progress-fill" style="width: {{ $metrics['tickets_percentage'] }}%;"></div>
                        </div>
                        <div class="dash-kpi-footer">
                            <span class="dash-kpi-subtext"><strong>{{ $metrics['tickets_percentage'] }}%</strong> ocupación
                                global</span>
                        </div>
                    </div>

                    <!-- KPI 3: Tasa de Asistencia -->
                    <div class="dash-kpi-card glow-cyan">
                        <div class="dash-kpi-header">
                            <span class="dash-kpi-label">Asistencia Confirmada</span>
                            <span class="dash-kpi-icon">🎯</span>
                        </div>
                        <div class="dash-kpi-value">{{ $metrics['attendance_rate'] }}</div>
                        <div class="dash-kpi-footer">
                            <span class="dash-badge-trend positive">↑ {{ $metrics['attendance_growth'] }}</span>
                            <span class="dash-kpi-subtext">promedio de ingresos</span>
                        </div>
                    </div>

                    <!-- KPI 4: Ingresos Netos -->
                    <div class="dash-kpi-card glow-purple">
                        <div class="dash-kpi-header">
                            <span class="dash-kpi-label">Ingresos Netos</span>
                            <span class="dash-kpi-icon">💳</span>
                        </div>
                        <div class="dash-kpi-value">S/ {{ $metrics['net_revenue'] }}</div>
                        <div class="dash-kpi-footer">
                            <span class="dash-badge-trend info">✓ Listo para liquidación</span>
                        </div>
                    </div>
                </div>

                <!-- FILA 2: GRÁFICO INTERACTIVO DE VENTAS + DISTRIBUCIÓN DE ENTRADAS -->
                <div class="dash-charts-grid">
                    <!-- GRÁFICO DE INGRESOS EN TIEMPO REAL -->
                    <div class="dash-chart-card">
                        <div class="dash-chart-header">
                            <div>
                                <h3 class="dash-card-title">Tendencia de Ingresos por Día</h3>
                                <p class="dash-card-subtitle">Recaudación acumulada en soles (S/) durante la última semana
                                </p>
                            </div>
                            <div class="dash-chart-actions">
                                <span class="dash-pill-btn active">Semanal</span>
                                <span class="dash-pill-btn">Mensual</span>
                            </div>
                        </div>

                        <div class="dash-sales-bar-chart">
                            <!-- Bar Item: Lun -->
                            <div class="chart-bar-col">
                                <div class="chart-bar-val">S/ 4.2k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 45%;"></div>
                                </div>
                                <span class="chart-bar-day">Lun</span>
                            </div>
                            <!-- Bar Item: Mar -->
                            <div class="chart-bar-col">
                                <div class="chart-bar-val">S/ 6.1k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 65%;"></div>
                                </div>
                                <span class="chart-bar-day">Mar</span>
                            </div>
                            <!-- Bar Item: Mié -->
                            <div class="chart-bar-col">
                                <div class="chart-bar-val">S/ 5.3k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 55%;"></div>
                                </div>
                                <span class="chart-bar-day">Mié</span>
                            </div>
                            <!-- Bar Item: Jue -->
                            <div class="chart-bar-col">
                                <div class="chart-bar-val">S/ 8.4k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 82%;"></div>
                                </div>
                                <span class="chart-bar-day">Jue</span>
                            </div>
                            <!-- Bar Item: Vie -->
                            <div class="chart-bar-col active-highlight">
                                <div class="chart-bar-val">S/ 12.8k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 95%;"></div>
                                </div>
                                <span class="chart-bar-day">Vie 🔥</span>
                            </div>
                            <!-- Bar Item: Sáb -->
                            <div class="chart-bar-col">
                                <div class="chart-bar-val">S/ 9.1k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 88%;"></div>
                                </div>
                                <span class="chart-bar-day">Sáb</span>
                            </div>
                            <!-- Bar Item: Dom -->
                            <div class="chart-bar-col">
                                <div class="chart-bar-val">S/ 3.0k</div>
                                <div class="chart-bar-track">
                                    <div class="chart-bar-fill" style="height: 35%;"></div>
                                </div>
                                <span class="chart-bar-day">Dom</span>
                            </div>
                        </div>
                    </div>

                    <!-- DISTRIBUCIÓN POR TIPO DE ENTRADA -->
                    <div class="dash-chart-card">
                        <div class="dash-chart-header">
                            <div>
                                <h3 class="dash-card-title">Distribución de Entradas</h3>
                                <p class="dash-card-subtitle">Ventas divididas por categoría de ticket</p>
                            </div>
                        </div>

                        <div class="dash-donut-distribution-box">
                            <div class="dash-donut-visual">
                                <div class="dash-donut-center">
                                    <span class="dash-donut-number">1,420</span>
                                    <span class="dash-donut-sub">Tickets</span>
                                </div>
                            </div>

                            <div class="dash-legend-list">
                                <div class="dash-legend-item">
                                    <span class="legend-color-dot orange"></span>
                                    <span class="legend-label">General (S/ 80)</span>
                                    <span class="legend-percent">55%</span>
                                </div>
                                <div class="dash-legend-item">
                                    <span class="legend-color-dot red"></span>
                                    <span class="legend-label">VIP Preferencial (S/ 120)</span>
                                    <span class="legend-percent">35%</span>
                                </div>
                                <div class="dash-legend-item">
                                    <span class="legend-color-dot cyan"></span>
                                    <span class="legend-label">Experiencia VIP (S/ 200)</span>
                                    <span class="legend-percent">10%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FILA 3: TABLA DE EVENTOS ACTIVOS + FEED DE ACTIVIDAD EN TIEMPO REAL -->
                <div class="dash-bottom-grid">
                    <!-- TABLA DE EVENTOS ACTIVOS -->
                    <div class="dash-table-card">
                        <div class="dash-table-header">
                            <div>
                                <h3 class="dash-card-title">Mis Eventos Activos</h3>
                                <p class="dash-card-subtitle">Administración de taquilla y estado de venta</p>
                            </div>
                            <a href="#" class="dash-link-orange">Ver Todos (3) ➔</a>
                        </div>

                        <div class="dash-table-responsive">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>EVENTO</th>
                                        <th>FECHA & LUGAR</th>
                                        <th>VENDIDOS</th>
                                        <th>RECAUDACIÓN</th>
                                        <th>ESTADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>
                                                <div class="dash-event-cell">
                                                    <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}"
                                                        class="dash-event-thumb">
                                                    <div>
                                                        <h4 class="dash-event-name">{{ $event['title'] }}</h4>
                                                        <span class="dash-event-category">{{ $event['category'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dash-venue-cell">
                                                    <span class="dash-venue-date">📅 {{ $event['date'] }}</span>
                                                    <span class="dash-venue-place">📍 {{ $event['venue'] }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dash-progress-cell">
                                                    <span class="dash-progress-num">{{ $event['tickets_sold'] }} /
                                                        {{ $event['tickets_total'] }}</span>
                                                    <div class="dash-progress-track">
                                                        <div class="dash-progress-fill"
                                                            style="width: {{ ($event['tickets_sold'] / $event['tickets_total']) * 100 }}%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="dash-revenue-text">S/ {{ $event['revenue'] }}</span>
                                            </td>
                                            <td>
                                                @if($event['status_color'] === 'success')
                                                    <span class="dash-badge-status status-success">🟢 {{ $event['status'] }}</span>
                                                @elseif($event['status_color'] === 'warning')
                                                    <span class="dash-badge-status status-warning">🔥 {{ $event['status'] }}</span>
                                                @else
                                                    <span class="dash-badge-status status-info">🔵 {{ $event['status'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dash-actions-cell">
                                                    <a href="{{ route('web.event.detail', 'mezcla-2026') }}"
                                                        class="dash-btn-icon-action" title="Ver Evento" target="_blank">👁️</a>
                                                    <button class="dash-btn-icon-action"
                                                        title="Administrar Taquilla">🎟️</button>
                                                    <button class="dash-btn-icon-action" title="Opciones">⚙️</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- FEED DE ACTIVIDAD EN TIEMPO REAL -->
                    <div class="dash-feed-card">
                        <div class="dash-feed-header">
                            <h3 class="dash-card-title">Ventas en Tiempo Real</h3>
                            <span class="dash-live-badge-green">● En directo</span>
                        </div>

                        <div class="dash-feed-list">
                            @foreach($activities as $act)
                                <div class="dash-feed-item">
                                    <div class="dash-feed-icon-box">
                                        @if($act['type'] === 'ticket')
                                            🎟️
                                        @elseif($act['type'] === 'promo')
                                            🏷️
                                        @else
                                            💰
                                        @endif
                                    </div>
                                    <div class="dash-feed-content">
                                        <div class="dash-feed-top-row">
                                            <strong class="dash-feed-user">{{ $act['user'] }}</strong>
                                            <span class="dash-feed-time">{{ $act['time'] }}</span>
                                        </div>
                                        <p class="dash-feed-action">{{ $act['action'] }}</p>
                                        <span class="dash-feed-event-name">{{ $act['event'] }}</span>
                                    </div>
                                    <div class="dash-feed-amount">{{ $act['amount'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Exportar Reporte Demo Alert
            const btnExport = document.getElementById('btnExportReport');
            if (btnExport) {
                btnExport.addEventListener('click', function () {
                    alert('🚀 Generando y descargando el reporte consolidado de taquilla en formato PDF...');
                });
            }

            // Selector de Tema Claro / Oscuro (Pro Max System)
            const themeBtn = document.getElementById('btnThemeToggle');
            const themeIcon = document.getElementById('themeToggleIcon');
            const dashRoot = document.querySelector('.dashboard-root-wrapper');

            // Restaurar tema preferido guardado
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