<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Vive Go - VIVE CADA MOMENTO | Plataforma Oficial de Eventos')</title>
    <meta name="description"
        content="Vive Go es la plataforma integral de ticketing, venta de entradas masivas, conciertos, teatro y festivales en Perú. Vive cada momento.">
    <!-- Icono del sistema oficial: loading.png -->
    <link rel="icon" type="image/png" href="{{ asset('images/loading.png') }}">

    <!-- Vite Assets Processing -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Production Asset & Direct Inline CSS (Garantiza 100% la carga de estilos) -->
    @php
        $manifestPath = public_path('build/manifest.json');
        $cssContent = null;
        $jsAsset = null;
        if (file_exists($manifestPath)) {
            $manifestData = json_decode(file_get_contents($manifestPath), true);
            $cssFile = $manifestData['resources/css/app.css']['file'] ?? null;
            $jsAsset = $manifestData['resources/js/app.js']['file'] ?? null;
            if ($cssFile && file_exists(public_path('build/' . $cssFile))) {
                $cssContent = file_get_contents(public_path('build/' . $cssFile));
            }
        }
    @endphp

    @if($cssContent)
        <style>
            {!! $cssContent !!}
        </style>
    @endif

    @if($jsAsset)
        <script defer src="{{ asset('build/' . $jsAsset) }}?v={{ time() }}"></script>
    @endif

    <style>
        #pagePreloader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background-color: #1C1C24;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        #pagePreloader.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Page Preloader (Logo de Carga Oficial: loading.png) -->
    <div id="pagePreloader">
        <div class="preloader-spinner-wrapper">
            <div class="preloader-ring"></div>
            <img src="{{ asset('images/loading.png') }}" alt="Vive Go Loading" class="preloader-logo-img">
        </div>
        <p class="preloader-text">VIVE CADA MOMENTO</p>
    </div>

    @unless(request()->is('dashboard*') || request()->is('admin*') || request()->segment(1) === 'dashboard' || request()->segment(1) === 'admin' || request()->routeIs('web.dashboard*', 'web.settings*', 'web.admins*'))
    <!-- Top Announcement Banner -->
    <div class="top-banner">
        ⚡ ¡ENTRADAS OFICIALES Y NOMINACIÓN DIGITAL EN TIEMPO REAL! Compra con Yape, Plin y Tarjetas.
    </div>

    <!-- Header / Navbar Global -->
    <header class="navbar-header">
        <div class="container">
            <div class="navbar-main">
                <!-- Logotipo Oficial Vive Go (logo.png) -->
                <a href="{{ route('web.home') }}" class="brand-logo" title="Vive Go - VIVE CADA MOMENTO">
                    <img src="{{ asset('images/logo.png') }}" alt="Vive Go - VIVE CADA MOMENTO" class="brand-logo-img">
                </a>

                <!-- Buscador predictivo centrado (Escritorio) -->
                <div class="search-location-group desktop-search-only">
                    <div class="search-input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Buscar concierto, artista o recinto..."
                            autocomplete="off">
                    </div>
                </div>

                <!-- Botones de Acción + Hamburguesa Móvil -->
                <div class="nav-actions">
                    <a href="{{ route('web.dashboard') }}" class="btn btn-secondary btn-sm desktop-only">📊 Mi Panel</a>
                    <a href="{{ route('web.dashboard') }}" class="btn btn-primary btn-sm desktop-only">Iniciar Sesión</a>

                    <!-- Botón Hamburguesa Móvil -->
                    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Abrir Menú">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Categorías Rápidas (Escritorio) -->
        <nav class="nav-categories desktop-only">
            <div class="container">
                <ul class="category-list">
                    <li class="category-item active"><a href="#">🔥 Destacados</a></li>
                    <li class="category-item"><a href="#">🎤 Conciertos</a></li>
                    <li class="category-item"><a href="#">🎉 Fiestas</a></li>
                    <li class="category-item"><a href="#">🎭 Teatro & Shows</a></li>
                    <li class="category-item"><a href="#">⚽ Deportes</a></li>
                    <li class="category-item"><a href="#">🍔 Gastronomía</a></li>
                </ul>
            </div>
        </nav>
    </header>
    @endunless

    <!-- Overlay Fondo Obscuro para Menú Móvil -->
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>

    <!-- Menú Desplegable Lateral Móvil (Drawer Slide-Out) -->
    <aside class="mobile-nav-drawer" id="mobileNavDrawer">
        <div class="drawer-header">
            <img src="{{ asset('images/logo.png') }}" alt="Vive Go" class="drawer-logo">
            <button class="drawer-close-btn" id="drawerCloseBtn" aria-label="Cerrar Menú">✕</button>
        </div>

        <div class="drawer-body">
            <!-- Buscador Móvil -->
            <div class="search-location-group drawer-search-group">
                <div class="search-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Buscar concierto, artista..." autocomplete="off">
                </div>
            </div>

            <!-- Categorías de Eventos Móvil -->
            <div class="drawer-section">
                <h4 class="drawer-section-title">Categorías</h4>
                <ul class="drawer-category-list">
                    <li><a href="#" class="drawer-cat-link active">🔥 Destacados</a></li>
                    <li><a href="#" class="drawer-cat-link">🎤 Conciertos</a></li>
                    <li><a href="#" class="drawer-cat-link">🎉 Fiestas & Clubes</a></li>
                    <li><a href="#" class="drawer-cat-link">🎭 Teatro & Shows</a></li>
                    <li><a href="#" class="drawer-cat-link">⚽ Deportes</a></li>
                    <li><a href="#" class="drawer-cat-link">🍔 Gastronomía</a></li>
                </ul>
            </div>

            <!-- Botones de Acción Móvil -->
            <div class="drawer-actions">
                <a href="{{ route('web.dashboard') }}" class="btn btn-primary btn-sm" style="width: 100%; text-align: center;">
                    Iniciar Sesión / Mi Cuenta
                </a>
                <a href="{{ route('web.dashboard') }}" class="btn btn-secondary btn-sm" style="width: 100%; text-align: center; margin-top: 0.65rem;">
                    📊 Mi Panel
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @unless(request()->is('dashboard*') || request()->is('admin*') || request()->segment(1) === 'dashboard' || request()->segment(1) === 'admin' || request()->routeIs('web.dashboard*', 'web.settings*', 'web.admins*'))
    <!-- Footer Institucional -->
    <footer class="footer-institutional">
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand">
                    <!-- Logotipo Oficial Blanco para Footer (logo-white.png) -->
                    <img src="{{ asset('images/logo-white.png') }}" alt="Vive Go - VIVE CADA MOMENTO"
                        class="footer-logo-img">

                    <p class="footer-slogan">
                        Vive Go es la plataforma líder en venta de entradas, boletaje inteligente y gestión de eventos
                        masivos. VIVE CADA MOMENTO.
                    </p>
                    <a href="#" class="claim-book-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        Libro de Reclamaciones
                    </a>
                </div>

                <div>
                    <h3 class="footer-col-title">Marketplace</h3>
                    <ul class="footer-links">
                        <li><a href="#">Conciertos y Festivales</a></li>
                        <li><a href="#">Fiestas y Clubes</a></li>
                        <li><a href="#">Teatro y Comedia</a></li>
                        <li><a href="#">Eventos Deportivos</a></li>
                        <li><a href="#">Experiencias VIP</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-col-title">Organizadores</h3>
                    <ul class="footer-links">
                        <li><a href="#">Vende tus Entradas</a></li>
                        <li><a href="#">Panel de Control SaaS</a></li>
                        <li><a href="#">Gestión de RRPP</a></li>
                        <li><a href="#">Sistema de Escaneo Puerta</a></li>
                        <li><a href="#">Punto de Venta POS</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-col-title">Legal & Soporte</h3>
                    <ul class="footer-links">
                        <li><a href="#">Términos y Condiciones</a></li>
                        <li><a href="#">Políticas de Privacidad</a></li>
                        <li><a href="#">Preguntas Frecuentes</a></li>
                        <li><a href="#">Centro de Ayuda</a></li>
                        <li><a href="#">Contacto / Soporte</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Vive Go. Todos los derechos reservados. VIVE CADA MOMENTO.</p>
                <p>Desarrollado por Deivid Chipana</p>
            </div>
        </div>
    </footer>
    @endunless

    <script>
        window.addEventListener('load', function() {
            var preloader = document.getElementById('pagePreloader');
            if (preloader) {
                preloader.classList.add('fade-out');
                setTimeout(function() { preloader.style.display = 'none'; }, 600);
            }
        });

        // Mobile Nav Drawer Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobileMenuToggle');
            const drawer = document.getElementById('mobileNavDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const closeBtn = document.getElementById('drawerCloseBtn');

            function openDrawer() {
                if (drawer) drawer.classList.add('active');
                if (overlay) overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                if (drawer) drawer.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (overlay) overlay.addEventListener('click', closeDrawer);

            // Global Dashboard Sidebar Toggle
            const dashSidebar = document.getElementById('dashSidebar');
            const dashToggleBtn = document.getElementById('dashSidebarToggle');

            if (dashSidebar && dashToggleBtn) {
                // Restaurar preferencia colapsada si existe en localStorage
                const isCollapsed = localStorage.getItem('vivego_sidebar_collapsed') === 'true';
                if (isCollapsed && window.innerWidth > 992) {
                    dashSidebar.classList.add('collapsed');
                }

                dashToggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    dashSidebar.classList.add('dash-animating');
                    dashSidebar.classList.toggle('collapsed');

                    const state = dashSidebar.classList.contains('collapsed');
                    localStorage.setItem('vivego_sidebar_collapsed', state ? 'true' : 'false');

                    setTimeout(function () {
                        dashSidebar.classList.remove('dash-animating');
                    }, 450);
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>