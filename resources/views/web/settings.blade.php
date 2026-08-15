@extends('layouts.app')

@section('title', 'Configuración General del Sistema | Vive Go')

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
                    <li class="dash-nav-item {{ request()->routeIs('web.events*') ? 'active' : '' }}">
                        <a href="{{ route('web.events') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎟️</span>
                            <span class="dash-nav-text">Mis Eventos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
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
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">👥</span>
                            <span class="dash-nav-text">Asistentes</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">GESTIÓN & HERRAMIENTAS</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item {{ request()->routeIs('web.categories*') ? 'active' : '' }}">
                        <a href="{{ route('web.categories') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">📂</span>
                            <span class="dash-nav-text">Categorías</span>
                        </a>
                    </li>
                    <li class="dash-nav-item {{ request()->routeIs('web.templates*') ? 'active' : '' }}">
                        <a href="{{ route('web.templates') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎨</span>
                            <span class="dash-nav-text">Plantillas de Boletos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">🏷️</span>
                            <span class="dash-nav-text">Descuentos & Promos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">💳</span>
                            <span class="dash-nav-text">Liquidaciones</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">INFORMACIÓN EMPRESARIAL</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item {{ request()->routeIs('web.companies*') ? 'active' : '' }}">
                        <a href="{{ route('web.companies') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🏢</span>
                            <span class="dash-nav-text">Compañía</span>
                        </a>
                    </li>
                    <li class="dash-nav-item {{ request()->routeIs('web.managers*') ? 'active' : '' }}">
                        <a href="{{ route('web.managers') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">👤</span>
                            <span class="dash-nav-text">Responsable</span>
                        </a>
                    </li>
                    <li class="dash-nav-item {{ request()->routeIs('web.capacity_types*') ? 'active' : '' }}">
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
                    <li class="dash-nav-item active">
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

        <!-- ÁREA PRINCIPAL DE CONFIGURACIÓN -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" class="dash-search-input" placeholder="Buscar parámetros de configuración...">
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
                <!-- SUCCESS ALERT -->
                @if(session('success'))
                    <div class="alert-custom alert-success" style="margin-bottom: 1.5rem;">
                        <div class="alert-icon-box">✓</div>
                        <div class="alert-content">
                            <h4>¡Configuración Guardada!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                <!-- HEADER PAGE -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">⚙️ PANEL DE ADMINISTRACIÓN VIVE GO</span>
                        <h1 class="settings-page-title">Configuración General del Sistema</h1>
                        <p class="settings-page-subtitle">Gestiona la identidad visual, logos, colores del sistema, zona horaria y moneda global.</p>
                    </div>
                </div>

                <!-- FORMULARIO DE CONFIGURACIÓN DE BASE DE DATOS -->
                <form action="{{ route('web.settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-main-form">
                    @csrf

                    <div class="settings-grid-container">
                        <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                        <div class="settings-card-box">
                            <div class="settings-card-header">
                                <div class="card-header-icon">🌐</div>
                                <div>
                                    <h3 class="card-header-title">Identidad del Sistema & SEO</h3>
                                    <p class="card-header-subtitle">Parámetros globales visibles para usuarios y buscadores</p>
                                </div>
                            </div>

                            <div class="settings-card-body">
                                <div class="form-group-custom">
                                    <label for="site_name" class="form-label-custom">Nombre de la Página / Sistema <span class="required-star">*</span></label>
                                    <input type="text" id="site_name" name="site_name" class="form-input-custom" value="{{ old('site_name', $settings->site_name) }}" required placeholder="Ej: Vive Go">
                                    <span class="form-help-text">Este nombre aparecerá en la barra de títulos, headers e emails del sistema.</span>
                                </div>

                                <div class="form-group-custom" style="margin-top: 1.25rem;">
                                    <label for="site_description" class="form-label-custom">Descripción Meta / Eslogan SEO</label>
                                    <textarea id="site_description" name="site_description" class="form-textarea-custom" rows="3" placeholder="Descripción general de la plataforma...">{{ old('site_description', $settings->site_description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: LOGOTIPOS E ÍCONO -->
                        <div class="settings-card-box">
                            <div class="settings-card-header">
                                <div class="card-header-icon">🖼️</div>
                                <div>
                                    <h3 class="card-header-title">Logotipos & Favicon del Sistema</h3>
                                    <p class="card-header-subtitle">Sube los assets gráficos oficiales para los dos tipos de fondo</p>
                                </div>
                            </div>

                            <div class="settings-card-body logos-upload-grid">
                                <!-- Logo Versión Oscura (Fondo Claro) -->
                                <div class="logo-preview-card">
                                    <label class="form-label-custom">Logo Versión Oscura (para Fondo Claro)</label>
                                    <div class="logo-box-bg light-bg">
                                        <img src="{{ asset($settings->logo_dark ?? 'images/logo.png') }}" alt="Logo Oscuro" id="previewLogoDark">
                                    </div>
                                    <label class="btn-file-upload">
                                        <span>📁 Cambiar Logo Oscuro</span>
                                        <input type="file" name="logo_dark_file" accept="image/*" onchange="previewImage(this, 'previewLogoDark')">
                                    </label>
                                    <small class="file-path-info">Actual: <code>{{ $settings->logo_dark }}</code></small>
                                </div>

                                <!-- Logo Versión Blanca (Fondo Oscuro) -->
                                <div class="logo-preview-card">
                                    <label class="form-label-custom">Logo Versión Blanca (para Fondo Oscuro)</label>
                                    <div class="logo-box-bg dark-bg">
                                        <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" alt="Logo Blanco" id="previewLogoWhite">
                                    </div>
                                    <label class="btn-file-upload">
                                        <span>📁 Cambiar Logo Blanco</span>
                                        <input type="file" name="logo_white_file" accept="image/*" onchange="previewImage(this, 'previewLogoWhite')">
                                    </label>
                                    <small class="file-path-info">Actual: <code>{{ $settings->logo_white }}</code></small>
                                </div>

                                <!-- Ícono / Favicon -->
                                <div class="logo-preview-card">
                                    <label class="form-label-custom">Ícono / Favicon del Sistema</label>
                                    <div class="logo-box-bg favicon-bg">
                                        <img src="{{ asset($settings->favicon ?? 'images/loading.png') }}" alt="Favicon" id="previewFavicon" style="height: 48px; width: auto;">
                                    </div>
                                    <label class="btn-file-upload">
                                        <span>📁 Cambiar Favicon</span>
                                        <input type="file" name="favicon_file" accept="image/*" onchange="previewImage(this, 'previewFavicon')">
                                    </label>
                                    <small class="file-path-info">Actual: <code>{{ $settings->favicon }}</code></small>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: PALETA DE COLORES DEL SISTEMA -->
                        <div class="settings-card-box">
                            <div class="settings-card-header">
                                <div class="card-header-icon">🎨</div>
                                <div>
                                    <h3 class="card-header-title">Paleta de Colores Principales</h3>
                                    <p class="card-header-subtitle">Personaliza la tonalidad de botones, degradados y destaques</p>
                                </div>
                            </div>

                            <div class="settings-card-body colors-grid">
                                <!-- Color Primario -->
                                <div class="color-picker-card">
                                    <label for="primary_color" class="form-label-custom">Color Primario (Electric Orange)</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" id="primary_color_picker" value="{{ old('primary_color', $settings->primary_color) }}" class="color-picker-box" oninput="document.getElementById('primary_color').value = this.value">
                                        <input type="text" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings->primary_color) }}" class="form-input-custom" oninput="document.getElementById('primary_color_picker').value = this.value">
                                    </div>
                                    <div class="color-live-preview" style="background: {{ $settings->primary_color }};">
                                        <span>VISTA PREVIA BOTÓN PRIMARIO</span>
                                    </div>
                                </div>

                                <!-- Color Secundario -->
                                <div class="color-picker-card">
                                    <label for="secondary_color" class="form-label-custom">Color Secundario (Neon Red)</label>
                                    <div class="color-input-wrapper">
                                        <input type="color" id="secondary_color_picker" value="{{ old('secondary_color', $settings->secondary_color) }}" class="color-picker-box" oninput="document.getElementById('secondary_color').value = this.value">
                                        <input type="text" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color) }}" class="form-input-custom" oninput="document.getElementById('secondary_color_picker').value = this.value">
                                    </div>
                                    <div class="color-live-preview" style="background: {{ $settings->secondary_color }};">
                                        <span>VISTA PREVIA DEGRADADO</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: ZONA HORARIA Y MONEDA -->
                        <div class="settings-card-box">
                            <div class="settings-card-header">
                                <div class="card-header-icon">🌍</div>
                                <div>
                                    <h3 class="card-header-title">Zona Horaria & Moneda Global</h3>
                                    <p class="card-header-subtitle">Configuración regional para cobros, reportes e impuestos</p>
                                </div>
                            </div>

                            <div class="settings-card-body region-grid">
                                <!-- Zona Horaria -->
                                <div class="form-group-custom">
                                    <label for="timezone" class="form-label-custom">Zona Horaria del Servidor <span class="required-star">*</span></label>
                                    <select id="timezone" name="timezone" class="form-select-custom" required>
                                        @foreach($timezones as $tzKey => $tzName)
                                            <option value="{{ $tzKey }}" {{ old('timezone', $settings->timezone) === $tzKey ? 'selected' : '' }}>
                                                {{ $tzName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="form-help-text">Rige el cálculo de fechas de eventos y cierres de taquilla.</span>
                                </div>

                                <!-- Moneda y Símbolo -->
                                <div class="currency-row-group">
                                    <div class="form-group-custom" style="flex: 2;">
                                        <label for="currency" class="form-label-custom">Moneda Principal <span class="required-star">*</span></label>
                                        <select id="currency" name="currency" class="form-select-custom" required onchange="updateSymbol(this)">
                                            @foreach($currencies as $currKey => $currData)
                                                <option value="{{ $currKey }}" data-symbol="{{ $currData['symbol'] }}" {{ old('currency', $settings->currency) === $currKey ? 'selected' : '' }}>
                                                    {{ $currData['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group-custom" style="flex: 1;">
                                        <label for="currency_symbol" class="form-label-custom">Símbolo <span class="required-star">*</span></label>
                                        <input type="text" id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $settings->currency_symbol) }}" class="form-input-custom text-center-input" required placeholder="S/">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BARRA FLOTANTE / FOOTER DE ACCIONES -->
                    <div class="settings-sticky-actions" style="justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary btn-save-settings">
                            <span>💾 Guardar Configuración del Sistema</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Vista previa inmediata de imágenes al seleccionar archivos
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.getElementById(previewId);
                    if (img) img.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-actualizar símbolo según la moneda seleccionada
        function updateSymbol(select) {
            const selectedOption = select.options[select.selectedIndex];
            const symbol = selectedOption.getAttribute('data-symbol');
            if (symbol) {
                document.getElementById('currency_symbol').value = symbol;
            }
        }

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
