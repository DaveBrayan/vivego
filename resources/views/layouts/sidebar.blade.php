@php
    $sidebarSettings = $settings ?? \App\Models\Setting::first();
    $sidebarOrganizer = $organizer ?? null;
    
    // Normalizar datos de la compañía u organizador
    $orgName = 'Vive Go Producciones';
    $orgAvatar = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80';
    $orgStatus = 'Organizador Oficial';

    if ($sidebarOrganizer) {
        if (is_array($sidebarOrganizer)) {
            $orgName = $sidebarOrganizer['name'] ?? $orgName;
            $orgAvatar = $sidebarOrganizer['avatar'] ?? $orgAvatar;
            $orgStatus = $sidebarOrganizer['status'] ?? $orgStatus;
        } elseif (is_object($sidebarOrganizer)) {
            $orgName = $sidebarOrganizer->name ?? $sidebarOrganizer->company_name ?? $orgName;
            if (!empty($sidebarOrganizer->logo)) {
                $orgAvatar = asset($sidebarOrganizer->logo);
            } elseif (!empty($sidebarOrganizer->avatar)) {
                $orgAvatar = asset($sidebarOrganizer->avatar);
            }
            $orgStatus = $sidebarOrganizer->status ?? 'Verificado';
        }
    } else {
        $firstCompany = \App\Models\Company::first();
        if ($firstCompany) {
            $orgName = $firstCompany->name ?: $orgName;
            if (!empty($firstCompany->logo)) {
                $orgAvatar = asset($firstCompany->logo);
            }
        }
    }
@endphp

<!-- SIDEBAR DE NAVEGACIÓN PRO MAX HEREDADO -->
<aside class="dash-sidebar" id="dashSidebar">
    <div class="dash-sidebar-header">
        <a href="{{ route('web.home') }}" class="dash-brand-logo">
            <img src="{{ asset($sidebarSettings->logo_white ?? 'images/logo-white.png') }}" alt="Vive Go" class="dash-logo-img logo-white-img">
            <img src="{{ asset($sidebarSettings->logo_dark ?? 'images/logo.png') }}" alt="Vive Go" class="dash-logo-img logo-dark-img">
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
            <img src="{{ $orgAvatar }}" alt="{{ $orgName }}" class="dash-avatar-img">
            <span class="dash-online-status-dot"></span>
        </div>
        <div class="dash-organizer-info">
            <h4 class="dash-organizer-name" title="{{ $orgName }}">{{ $orgName }}</h4>
            <span class="dash-verified-badge">✓ {{ $orgStatus }}</span>
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
</script>
