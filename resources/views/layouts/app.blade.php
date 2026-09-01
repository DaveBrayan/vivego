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

    <!-- Assets Compilados de Producción con Caché HTTP del Navegador -->
    @php
        $manifestPath = public_path('build/manifest.json');
        $cssFile = null;
        $jsAsset = null;
        if (file_exists($manifestPath)) {
            $manifestData = json_decode(file_get_contents($manifestPath), true);
            $cssFile = $manifestData['resources/css/app.css']['file'] ?? null;
            $jsAsset = $manifestData['resources/js/app.js']['file'] ?? null;
        }
    @endphp

    @if($cssFile && file_exists(public_path('build/' . $cssFile)))
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @if($jsAsset && file_exists(public_path('build/' . $jsAsset)))
        <script defer src="{{ asset('build/' . $jsAsset) }}"></script>
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

        .footer-claim-badge-pro {
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            background: linear-gradient(135deg, #0B1E48 0%, #16387C 100%);
            border: 1.5px solid #FFFFFF;
            border-radius: 14px;
            padding: 0.65rem 1.15rem;
            text-decoration: none;
            color: #FFFFFF;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45), 0 0 15px rgba(22, 56, 124, 0.35);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1.15rem;
        }

        .footer-claim-badge-pro:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6), 0 0 25px rgba(37, 99, 235, 0.5);
            border-color: #FFFFFF;
            background: linear-gradient(135deg, #0E275E 0%, #1D4ED8 100%);
        }

        .footer-claim-img {
            width: 48px;
            height: auto;
            max-height: 38px;
            object-fit: contain;
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.4));
        }

        .footer-claim-text-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .footer-claim-title {
            font-family: var(--font-heading, 'Outfit', sans-serif);
            font-size: 0.925rem;
            font-weight: 900;
            color: #FFFFFF;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .footer-claim-sub {
            font-size: 0.7rem;
            color: #93C5FD;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
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
                    @if(session('customer_logged_in'))
                        <a href="{{ route('web.customer.tickets') }}" class="btn btn-secondary btn-sm desktop-only" style="background: rgba(255,85,0,0.15); color: #FF5500; border: 1px solid rgba(255,85,0,0.4); font-weight: 800;">
                            🎟️ Mis Boletos
                        </a>
                        <a href="{{ route('web.customer.receipts') }}" class="btn btn-secondary btn-sm desktop-only" style="font-weight: 700;">
                            🧾 Mis Recibos
                        </a>
                        <form action="{{ route('web.customer.logout') }}" method="POST" style="display: inline;" class="desktop-only">
                            @csrf
                            <button type="submit" title="Cerrar Sesión" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 0.45rem 0.85rem; border-radius: 10px; font-weight: 800; font-size: 0.825rem; cursor: pointer;">
                                🚪 Salir
                            </button>
                        </form>
                    @elseif(session('admin_logged_in'))
                        <a href="{{ route('web.dashboard') }}" class="btn btn-secondary btn-sm desktop-only">📊 Panel Admin</a>
                        <form action="{{ route('web.logout') }}" method="POST" style="display: inline;" class="desktop-only">
                            @csrf
                            <button type="submit" title="Cerrar Sesión Admin" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 0.45rem 0.85rem; border-radius: 10px; font-weight: 800; font-size: 0.825rem; cursor: pointer;">
                                🚪 Salir Admin
                            </button>
                        </form>
                    @else
                        <a href="{{ route('web.login') }}" class="btn btn-primary btn-sm desktop-only" style="font-weight: 800;">
                            🎟️ Mis Boletos / Iniciar Sesión
                        </a>
                    @endif

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

            <!-- Enlaces Legales en Drawer Móvil -->
            <div class="drawer-section" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08);">
                <h4 class="drawer-section-title">Legal & Soporte</h4>
                <ul class="drawer-category-list">
                    <li><a href="{{ route('web.terms') }}" class="drawer-cat-link">📜 Términos y Condiciones</a></li>
                    <li><a href="{{ route('web.privacy') }}" class="drawer-cat-link">🔒 Políticas de Privacidad</a></li>
                    <li><a href="{{ route('web.cookies') }}" class="drawer-cat-link">🍪 Política de Cookies</a></li>
                    <li><a href="{{ route('web.claim_book') }}" class="drawer-cat-link" style="color: #FF5500; font-weight: 800;">📖 Libro de Reclamaciones</a></li>
                </ul>
            </div>

            <!-- Botones de Acción Móvil -->
            <div class="drawer-actions">
                @if(session('customer_logged_in'))
                    <a href="{{ route('web.customer.tickets') }}" class="btn btn-primary btn-sm" style="width: 100%; text-align: center;">
                        🎟️ Mis Boletos Oficiales
                    </a>
                    <a href="{{ route('web.customer.receipts') }}" class="btn btn-secondary btn-sm" style="width: 100%; text-align: center; margin-top: 0.65rem;">
                        🧾 Mis Recibos
                    </a>
                    <form action="{{ route('web.customer.logout') }}" method="POST" style="margin-top: 0.65rem;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; color: #DC2626; border-color: #FCA5A5; background: #FEF2F2;">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                @elseif(session('admin_logged_in'))
                    <a href="{{ route('web.dashboard') }}" class="btn btn-primary btn-sm" style="width: 100%; text-align: center;">
                        📊 Panel Admin
                    </a>
                    <form action="{{ route('web.logout') }}" method="POST" style="margin-top: 0.65rem;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; color: #DC2626; border-color: #FCA5A5; background: #FEF2F2;">
                            🚪 Cerrar Sesión Admin
                        </button>
                    </form>
                @else
                    <a href="{{ route('web.login') }}" class="btn btn-primary btn-sm" style="width: 100%; text-align: center;">
                        🎟️ Mis Boletos / Iniciar Sesión
                    </a>
                @endif
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
                    <a href="{{ route('web.claim_book') }}" class="footer-claim-badge-pro" title="Libro de Reclamaciones Virtual - Conforme a Ley N.° 29571">
                        <img src="{{ asset('images/libro_de_reclamaciones.png') }}" 
                             alt="Libro de Reclamaciones" 
                             class="footer-claim-img"
                             onerror="this.src='{{ asset('images/libro_de_reclamaciones.jpeg') }}'">
                        <div class="footer-claim-text-group">
                            <span class="footer-claim-title">Libro de Reclamaciones</span>
                            <span class="footer-claim-sub">Conforme a Ley N.° 29571</span>
                        </div>
                    </a>
                </div>

                <div>
                    <h3 class="footer-col-title">Marketplace</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('web.home') }}">Conciertos y Festivales</a></li>
                        <li><a href="{{ route('web.home') }}">Fiestas y Clubes</a></li>
                        <li><a href="{{ route('web.home') }}">Teatro y Comedia</a></li>
                        <li><a href="{{ route('web.home') }}">Eventos Deportivos</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-col-title">Plataforma</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('web.home') }}">Vende tus Entradas</a></li>
                        <li><a href="{{ route('web.login') }}">Mis Boletos</a></li>
                        <li><a href="{{ route('web.login') }}">Portal de Clientes</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-col-title">Legal & Transparencia</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('web.terms') }}">📜 Términos y Condiciones</a></li>
                        <li><a href="{{ route('web.privacy') }}">🔒 Políticas de Privacidad</a></li>
                        <li><a href="{{ route('web.cookies') }}">🍪 Política de Cookies</a></li>
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
        (function() {
            function hidePreloader() {
                var preloader = document.getElementById('pagePreloader');
                if (preloader && !preloader.classList.contains('fade-out')) {
                    preloader.classList.add('fade-out');
                    setTimeout(function() {
                        if (preloader) preloader.style.display = 'none';
                    }, 300);
                }
            }

            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                hidePreloader();
            } else {
                document.addEventListener('DOMContentLoaded', hidePreloader);
                window.addEventListener('load', hidePreloader);
            }
            // Timeout de seguridad para que la página sea interactiva al instante
            setTimeout(hidePreloader, 600);
        })();

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
        });
    </script>
    @if(session('customer_logged_in') && session('must_change_password'))
    <!-- MODAL GLOBAL DE CAMBIO OBLIGATORIO DE CONTRASEÑA PARA CLIENTE -->
    <div id="globalMandatoryChangePassModal" style="display: flex; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.88); z-index: 999999; align-items: center; justify-content: center; backdrop-filter: blur(10px); padding: 1.25rem;">
        <div style="background: #FFFFFF; border-radius: 24px; width: 100%; max-width: 460px; padding: 2.25rem; box-shadow: 0 25px 60px -15px rgba(0,0,0,0.5); border: 1.5px solid #E2E8F0; text-align: left;">
            <div style="text-align: center; margin-bottom: 1.25rem;">
                <div style="width: 58px; height: 58px; border-radius: 50%; background-color: #FFF7ED; border: 2px solid #FFEDD5; color: #EA580C; font-size: 26px; line-height: 56px; margin: 0 auto 12px auto; display: flex; align-items: center; justify-content: center;">
                    🔒
                </div>
                <h3 style="font-size: 1.4rem; font-weight: 900; color: #0F172A; margin: 0 0 0.4rem 0;">
                    Establecer Nueva Contraseña
                </h3>
                <p style="font-size: 0.875rem; color: #64748B; margin: 0; line-height: 1.45;">
                    Has ingresado con una <strong>contraseña temporal</strong>. Por seguridad de tu cuenta, debes establecer tu nueva contraseña personalizada para continuar.
                </p>
            </div>

            <div id="globalMandatoryPassError" style="display: none; background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #DC2626; padding: 0.85rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.25rem; line-height: 1.4;"></div>
            <div id="globalMandatoryPassSuccess" style="display: none; background: #F0FDF4; border: 1.5px solid #BBF7D0; color: #16A34A; padding: 0.85rem; border-radius: 12px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.25rem; line-height: 1.4;"></div>

            <form onsubmit="submitGlobalMandatoryChangePassword(event)">
                <div style="margin-bottom: 1.15rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #334155; margin-bottom: 0.4rem;">Nueva Contraseña (mínimo 6 caracteres):</label>
                    <input type="password" id="globalMandatoryNewPass" required minlength="6" placeholder="••••••••••••" style="width: 100%; padding: 0.85rem 1rem; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 0.95rem; outline: none; box-sizing: border-box; background: #F8FAFC;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #334155; margin-bottom: 0.4rem;">Confirmar Nueva Contraseña:</label>
                    <input type="password" id="globalMandatoryConfirmPass" required minlength="6" placeholder="••••••••••••" style="width: 100%; padding: 0.85rem 1rem; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 0.95rem; outline: none; box-sizing: border-box; background: #F8FAFC;">
                </div>

                <button type="submit" id="btnSubmitGlobalMandatoryPass" style="width: 100%; padding: 0.95rem; background: linear-gradient(135deg, #FF5500 0%, #EA580C 100%); color: #FFFFFF; border: none; border-radius: 14px; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 10px 24px rgba(255, 85, 0, 0.35); transition: transform 0.2s;">
                    💾 Guardar y Continuar
                </button>
            </form>
        </div>
    </div>
    <script>
        function submitGlobalMandatoryChangePassword(e) {
            e.preventDefault();
            const newPass = document.getElementById('globalMandatoryNewPass').value;
            const confirmPass = document.getElementById('globalMandatoryConfirmPass').value;
            const errBox = document.getElementById('globalMandatoryPassError');
            const succBox = document.getElementById('globalMandatoryPassSuccess');
            const btn = document.getElementById('btnSubmitGlobalMandatoryPass');

            errBox.style.display = 'none';
            succBox.style.display = 'none';

            if (newPass.length < 6) {
                errBox.textContent = 'La nueva contraseña debe tener al menos 6 caracteres.';
                errBox.style.display = 'block';
                return;
            }

            if (newPass !== confirmPass) {
                errBox.textContent = 'Las contraseñas no coinciden. Intenta de nuevo.';
                errBox.style.display = 'block';
                return;
            }

            btn.disabled = true;
            btn.textContent = '💾 Guardando contraseña...';

            fetch("{{ route('web.password.update_temp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    new_password: newPass,
                    new_password_confirmation: confirmPass
                })
            })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                btn.disabled = false;
                btn.textContent = '💾 Guardar y Continuar';

                if (res.ok && data.success) {
                    succBox.innerHTML = '✨ <strong>¡Contraseña Actualizada!</strong> Recargando...';
                    succBox.style.display = 'block';
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    errBox.textContent = data.message || 'No se pudo actualizar la contraseña.';
                    errBox.style.display = 'block';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = '💾 Guardar y Continuar';
                errBox.textContent = 'Ocurrió un error al actualizar la contraseña.';
                errBox.style.display = 'block';
            });
        }
    </script>
    @endif
    @stack('scripts')
</body>

</html>