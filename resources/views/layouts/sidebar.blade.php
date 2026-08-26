@php
    $sidebarSettings = $settings ?? \App\Models\Setting::first();
    
    // Obtener datos del usuario administrador que inició sesión
    $adminId = session('admin_id');
    $loggedAdmin = $adminId ? \App\Models\Administrator::find($adminId) : null;
    
    $loggedUserName = session('admin_name') ?? ($loggedAdmin ? $loggedAdmin->full_name : 'Administrador');
    $loggedUserRole = session('admin_role') ?? ($loggedAdmin ? $loggedAdmin->role : 'Administrador Principal');
    $loggedUserAvatar = session('admin_avatar') ?? ($loggedAdmin ? $loggedAdmin->avatar : null);

    if (empty($loggedUserAvatar)) {
        $loggedUserAvatar = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80';
    }
@endphp

<!-- ESTILOS DIRECTOS DEL SISTEMA RESPONSIVE MÓVIL (OFFCANVAS + TABLAS + BANNERS) -->
<style>
    /* Botón Hamburguesa Móvil */
    .dash-mobile-hamburger-btn {
        display: none;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
        font-size: 1.25rem;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dash-mobile-hamburger-btn:active {
        background: rgba(255, 85, 0, 0.25);
        border-color: #FF5500;
        color: #FF5500;
        transform: scale(0.95);
    }

    /* Fondo difuminado al abrir el menú móvil */
    .dash-sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.78);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99998;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .dash-sidebar-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* Botón Cerrar Menú en Móvil */
    .dash-sidebar-close-mobile-btn {
        display: none;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #CBD5E1;
        font-size: 1.15rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .dash-sidebar-close-mobile-btn:active {
        background: rgba(239, 68, 68, 0.25);
        border-color: #EF4444;
        color: #EF4444;
    }

    /* TABLAS RESPONSIVAS CON HORIZONTAL SCROLL TÁCTIL */
    .dash-table-container,
    .dash-table-responsive,
    .admin-table-container,
    .table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        display: block !important;
        margin-bottom: 1rem !important;
        padding-bottom: 0.5rem !important;
        scrollbar-width: thin;
        scrollbar-color: #FF5500 rgba(255, 255, 255, 0.08);
    }

    .dash-table-container::-webkit-scrollbar,
    .dash-table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .dash-table-container::-webkit-scrollbar-thumb,
    .dash-table-responsive::-webkit-scrollbar-thumb {
        background: #FF5500;
        border-radius: 4px;
    }

    .dash-table,
    table.admin-table {
        min-width: 780px !important;
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .dash-table th,
    .dash-table td,
    table.admin-table th,
    table.admin-table td {
        padding: 0.85rem 0.75rem !important;
        white-space: nowrap !important;
    }

    /* REGLAS EXCLUSIVAS PARA CELULARES Y TABLETS (<= 991px) */
    @media (max-width: 991px) {
        .dash-mobile-hamburger-btn {
            display: flex !important;
        }

        .dash-sidebar-close-mobile-btn {
            display: flex !important;
        }

        .dash-sidebar-toggle-btn {
            display: none !important;
        }

        .dashboard-root-wrapper {
            flex-direction: column !important;
            min-height: 100vh !important;
            width: 100% !important;
            overflow-x: hidden !important;
            position: relative !important;
        }

        /* SIDEBAR EN MÓVIL: OFFCANVAS TOTALMENTE OCULTO A LA IZQUIERDA */
        aside.dash-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            width: 290px !important;
            min-width: 290px !important;
            max-width: 85vw !important;
            height: 100vh !important;
            z-index: 99999 !important;
            transform: translateX(-100%) !important;
            transition: transform 0.32s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.32s ease !important;
            box-shadow: none !important;
            padding: 1.25rem 1rem !important;
            background: #12121B !important;
            border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
            display: flex !important;
            flex-direction: column !important;
            visibility: visible !important;
            overflow-y: auto !important;
        }

        /* CUANDO EL MENÚ ESTÁ ABIERTO */
        aside.dash-sidebar.mobile-open {
            transform: translateX(0) !important;
            box-shadow: 25px 0 70px rgba(0, 0, 0, 0.95) !important;
        }

        /* TEXTOS VISIBLES DENTRO DEL SIDEBAR MÓVIL */
        aside.dash-sidebar .dash-nav-text,
        aside.dash-sidebar .dash-organizer-info,
        aside.dash-sidebar .dash-nav-section-title,
        aside.dash-sidebar .dash-nav-pill-count,
        aside.dash-sidebar .dash-nav-badge-orange,
        aside.dash-sidebar .dash-btn-logout-text {
            display: block !important;
        }

        aside.dash-sidebar .dash-brand-logo {
            display: flex !important;
        }

        aside.dash-sidebar .dash-organizer-pill-card {
            display: flex !important;
            padding: 0.85rem !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            background: rgba(255, 255, 255, 0.03) !important;
        }

        aside.dash-sidebar .dash-nav-link {
            justify-content: flex-start !important;
            padding: 0.75rem 1rem !important;
        }

        aside.dash-sidebar .dash-btn-logout {
            justify-content: flex-start !important;
            padding: 0.75rem 1rem !important;
        }

        /* CONTENIDO PRINCIPAL FULL WIDTH */
        main.dash-main-content {
            width: 100% !important;
            min-width: 0 !important;
            flex: 1 !important;
            height: auto !important;
            min-height: 100vh !important;
            overflow-x: hidden !important;
        }

        /* TOP NAVBAR UNIFICADO CON BOTÓN HAMBURGUESA */
        .dash-top-navbar {
            display: flex !important;
            align-items: center !important;
            padding: 0.65rem 0.85rem !important;
            gap: 0.5rem !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 900 !important;
            background: #0A0A10 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .dash-search-container {
            padding: 0.45rem 0.75rem !important;
            flex: 1 !important;
            min-width: 0 !important;
        }

        .dash-search-input {
            font-size: 0.8rem !important;
        }

        .dash-kbd-shortcut,
        .dash-period-select-capsule {
            display: none !important;
        }

        .dash-top-actions {
            display: flex !important;
            align-items: center !important;
            gap: 0.4rem !important;
            flex-shrink: 0 !important;
        }

        .dash-icon-btn {
            width: 38px !important;
            height: 38px !important;
        }

        .dash-btn-create {
            display: none !important;
        }

        .dash-container {
            padding: 1rem 0.85rem !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* BANNERS Y BOTONES DE ACCIÓN FLUIDOS */
        .settings-header-banner,
        .table-header-card,
        .dash-hero-welcome-card {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 1.25rem 1rem !important;
            gap: 1rem !important;
        }

        .settings-header-banner > div:last-child,
        .settings-header-actions,
        .table-header-card > div:last-child {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            gap: 0.6rem !important;
            width: 100% !important;
        }

        .settings-header-banner .btn,
        .table-header-card .btn {
            flex: 1 1 calc(50% - 0.6rem) !important;
            min-width: 130px !important;
            justify-content: center !important;
            text-align: center !important;
            font-size: 0.85rem !important;
            padding: 0.75rem 0.85rem !important;
            white-space: nowrap !important;
        }

        .settings-page-title {
            font-size: 1.35rem !important;
        }

        .settings-page-subtitle {
            font-size: 0.825rem !important;
            line-height: 1.4 !important;
        }

        .settings-card-box,
        .dash-chart-card,
        .dash-feed-card,
        .dash-table-card {
            padding: 1.15rem 0.85rem !important;
            border-radius: 18px !important;
        }

        .dash-kpi-grid {
            grid-template-columns: 1fr !important;
            gap: 0.85rem !important;
        }

        .dash-charts-grid,
        .dash-bottom-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }
</style>

<!-- BACKDROP OVERLAY PARA SIDEBAR MÓVIL -->
<div class="dash-sidebar-overlay" id="dashMobileOverlay"></div>

<!-- SIDEBAR DE NAVEGACIÓN PRO MAX HEREDADO -->
<aside class="dash-sidebar" id="dashSidebar">
    <div class="dash-sidebar-header">
        <a href="{{ route('web.home') }}" class="dash-brand-logo">
            <img src="{{ asset($sidebarSettings->logo_white ?? 'images/logo-white.png') }}" alt="Vive Go" class="dash-logo-img logo-white-img">
            <img src="{{ asset($sidebarSettings->logo_dark ?? 'images/logo.png') }}" alt="Vive Go" class="dash-logo-img logo-dark-img">
        </a>
        <div style="display: flex; align-items: center; gap: 0.4rem;">
            <button class="dash-sidebar-toggle-btn" id="dashSidebarToggle" aria-label="Colapsar Menú" title="Plegar / Expandir Menú">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button class="dash-sidebar-close-mobile-btn" id="dashSidebarCloseMobile" type="button" aria-label="Cerrar Menú" title="Cerrar Menú">
                ✕
            </button>
        </div>
    </div>

    <!-- Perfil rápido del usuario autenticado -->
    <div class="dash-organizer-pill-card">
        <div class="dash-avatar-wrapper">
            <img src="{{ $loggedUserAvatar }}" alt="{{ $loggedUserName }}" class="dash-avatar-img">
            <span class="dash-online-status-dot"></span>
        </div>
        <div class="dash-organizer-info">
            <h4 class="dash-organizer-name" title="{{ $loggedUserName }}">{{ $loggedUserName }}</h4>
            <span class="dash-verified-badge">{{ $loggedUserRole }}</span>
        </div>
    </div>

    <!-- Menú de Navegación Principal -->
    <nav class="dash-nav-menu">
        <div class="dash-nav-section-title">MENÚ PRINCIPAL</div>
        <ul class="dash-nav-list">
            <li class="dash-nav-item {{ request()->routeIs('web.dashboard*') ? 'active' : '' }}">
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
            <li class="dash-nav-item {{ request()->routeIs('web.box_office*') ? 'active' : '' }}">
                <a href="{{ route('web.box_office') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">💰</span>
                    <span class="dash-nav-text">Taquilla & Ventas</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.attendees*') ? 'active' : '' }}">
                <a href="{{ route('web.attendees') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">👥</span>
                    <span class="dash-nav-text">Asistentes & Scanner</span>
                </a>
            </li>
        </ul>

        <div class="dash-nav-section-title" style="margin-top: 1.5rem;">GESTIÓN & HERRAMIENTAS</div>
        <ul class="dash-nav-list">
            <li class="dash-nav-item {{ request()->routeIs('web.campaigns*') ? 'active' : '' }}">
                <a href="{{ route('web.campaigns') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">🔥</span>
                    <span class="dash-nav-text">Campañas</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.coupons*') ? 'active' : '' }}">
                <a href="{{ route('web.coupons') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">🎟️</span>
                    <span class="dash-nav-text">Cupones</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.categories*') ? 'active' : '' }}">
                <a href="{{ route('web.categories') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">📂</span>
                    <span class="dash-nav-text">Categorías</span>
                </a>
            </li>
        </ul>

        <div class="dash-nav-section-title" style="margin-top: 1.5rem;">INFORMACIÓN EMPRESARIAL</div>
        <ul class="dash-nav-list">
            <li class="dash-nav-item {{ (request()->routeIs('web.customers*') || request()->is('admin/clientes*')) ? 'active' : '' }}">
                <a href="{{ \Illuminate\Support\Facades\Route::has('web.customers') ? route('web.customers') : url('/admin/clientes') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">👥</span>
                    <span class="dash-nav-text">Clientes</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.companies*') ? 'active' : '' }}">
                <a href="{{ route('web.companies') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">🏢</span>
                    <span class="dash-nav-text">Compañía</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.managers*') ? 'active' : '' }}">
                <a href="{{ route('web.managers') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">👤</span>
                    <span class="dash-nav-text">Responsables</span>
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
            <li class="dash-nav-item {{ request()->routeIs('web.payment_methods*') ? 'active' : '' }}">
                <a href="{{ route('web.payment_methods') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">💳</span>
                    <span class="dash-nav-text">Métodos de Pago</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.admins*') ? 'active' : '' }}">
                <a href="{{ route('web.admins') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">🛡️</span>
                    <span class="dash-nav-text">Administradores</span>
                </a>
            </li>
            <li class="dash-nav-item {{ request()->routeIs('web.settings*') ? 'active' : '' }}">
                <a href="{{ route('web.settings') }}" class="dash-nav-link">
                    <span class="dash-nav-icon">⚙️</span>
                    <span class="dash-nav-text">Configuración</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Footer Sidebar: Botón Cambiar Contraseña & Salir -->
    <div class="dash-sidebar-footer" style="display: flex; flex-direction: column; gap: 0.5rem; padding: 1rem;">
        <button type="button" class="dash-btn-logout" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #CBD5E1; width: 100%; cursor: pointer;" onclick="openChangePasswordModal()" title="Cambiar Contraseña">
            <span class="dash-btn-logout-icon">🔑</span>
            <span class="dash-btn-logout-text">Cambiar Clave</span>
        </button>

        <a href="#" class="dash-btn-logout" title="Cerrar Sesión" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
            <span class="dash-btn-logout-icon">🚪</span>
            <span class="dash-btn-logout-text">Cerrar Sesión</span>
        </a>

        <form id="sidebar-logout-form" action="{{ route('web.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

<!-- Modal de Cambio de Contraseña -->
<div id="changePasswordModal" class="admin-modal-backdrop" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1rem;">
    <div class="admin-modal-content" style="background: #14141E; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 20px; max-width: 440px; width: 100%; padding: 2rem; box-shadow: 0 25px 50px rgba(0,0,0,0.5); color: #FFFFFF; font-family: 'Plus Jakarta Sans', sans-serif;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                🔑 Actualizar Contraseña
            </h3>
            <button type="button" onclick="closeChangePasswordModal()" style="background: none; border: none; color: #94A3B8; font-size: 1.25rem; cursor: pointer;">✕</button>
        </div>

        @if(session('must_change_password'))
            <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #FDE68A; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.25rem; line-height: 1.4;">
                ⚠️ Se detectó una contraseña temporal. Por tu seguridad, actualízala antes de continuar.
            </div>
        @endif

        <form action="{{ route('web.change_password') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.15rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Contraseña Actual</label>
                <input type="password" name="current_password" required placeholder="••••••••" style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.15rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Nueva Contraseña</label>
                <input type="password" name="new_password" required placeholder="Mínimo 6 caracteres" style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Confirmar Nueva Contraseña</label>
                <input type="password" name="new_password_confirmation" required placeholder="Repite la nueva contraseña" style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none;">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeChangePasswordModal()" style="padding: 0.75rem 1.25rem; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; color: #FFFFFF; font-weight: 700; cursor: pointer;">Cancelar</button>
                <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #FF5500, #E04B00); border: none; border-radius: 12px; color: #FFFFFF; font-weight: 800; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);">Actualizar Clave</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openChangePasswordModal() {
        const m = document.getElementById('changePasswordModal');
        if (m) {
            m.style.display = 'flex';
        }
    }

    function closeChangePasswordModal() {
        const m = document.getElementById('changePasswordModal');
        if (m) {
            m.style.display = 'none';
        }
    }

    @if(session('must_change_password'))
        document.addEventListener('DOMContentLoaded', function() {
            openChangePasswordModal();
        });
    @endif

    // CONTROL GLOBAL DEL SIDEBAR (RESPONSIVE MÓVIL & ESCRITORIO)
    document.addEventListener('DOMContentLoaded', function() {
        const dashSidebar = document.getElementById('dashSidebar');
        const dashMobileOverlay = document.getElementById('dashMobileOverlay');
        const dashToggleBtn = document.getElementById('dashSidebarToggle');
        const dashCloseMobileBtn = document.getElementById('dashSidebarCloseMobile');

        // Auto-inyectar botón hamburguesa en la barra de navegación superior si no existe
        const topNavbars = document.querySelectorAll('.dash-top-navbar');
        topNavbars.forEach(navbar => {
            if (!navbar.querySelector('.dash-mobile-hamburger-btn')) {
                const hamburgerBtn = document.createElement('button');
                hamburgerBtn.type = 'button';
                hamburgerBtn.className = 'dash-mobile-hamburger-btn';
                hamburgerBtn.setAttribute('aria-label', 'Abrir Menú');
                hamburgerBtn.setAttribute('title', 'Abrir Menú de Navegación');
                hamburgerBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                `;
                hamburgerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openMobileSidebar();
                });
                navbar.insertBefore(hamburgerBtn, navbar.firstChild);
            }
        });

        function openMobileSidebar() {
            if (!dashSidebar) return;
            dashSidebar.classList.remove('collapsed');
            dashSidebar.classList.add('mobile-open');
            if (dashMobileOverlay) dashMobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            if (!dashSidebar) return;
            dashSidebar.classList.remove('mobile-open');
            if (dashMobileOverlay) dashMobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (dashCloseMobileBtn) {
            dashCloseMobileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeMobileSidebar();
            });
        }

        if (dashMobileOverlay) {
            dashMobileOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                closeMobileSidebar();
            });
        }

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dashSidebar && dashSidebar.classList.contains('mobile-open')) {
                closeMobileSidebar();
            }
        });

        // Desktop Toggle behavior
        if (dashToggleBtn && dashSidebar) {
            dashToggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (window.innerWidth <= 991) {
                    closeMobileSidebar();
                    return;
                }
                dashSidebar.classList.add('dash-animating');
                dashSidebar.classList.toggle('collapsed');

                const state = dashSidebar.classList.contains('collapsed');
                localStorage.setItem('vivego_sidebar_collapsed', state ? 'true' : 'false');

                setTimeout(function () {
                    dashSidebar.classList.remove('dash-animating');
                }, 450);
            });

            // Restaurar estado guardado sólo en pantallas de escritorio
            const isCollapsed = localStorage.getItem('vivego_sidebar_collapsed') === 'true';
            if (isCollapsed && window.innerWidth > 991) {
                dashSidebar.classList.add('collapsed');
            }
        }

        // Auto-cerrar sidebar en móvil al hacer clic en enlaces
        const sidebarLinks = document.querySelectorAll('.dash-sidebar .dash-nav-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 991) {
                    closeMobileSidebar();
                }
            });
        });
    });
</script>
