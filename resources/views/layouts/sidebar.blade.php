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

    <!-- Footer Sidebar: Botón Salir -->
    <div class="dash-sidebar-footer">
        <a href="{{ route('web.home') }}" class="dash-btn-logout" title="Cerrar Sesión">
            <span class="dash-btn-logout-icon">🚪</span>
            <span class="dash-btn-logout-text">Cerrar Sesión</span>
        </a>
    </div>
</aside>
