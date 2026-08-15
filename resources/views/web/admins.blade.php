@extends('layouts.app')

@section('title', 'Gestión de Administradores | Vive Go')

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
                    <li class="dash-nav-item active">
                        <a href="{{ route('web.admins') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🛡️</span>
                            <span class="dash-nav-text">Administradores</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
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

        <!-- ÁREA PRINCIPAL DE ADMINISTRADORES -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" class="dash-search-input" id="tableFilterInput" placeholder="Buscar por nombre, usuario, correo...">
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
                    <div class="settings-success-alert">
                        <div class="alert-icon-box">✓</div>
                        <div class="alert-content">
                            <h4>¡Operación Exitosa!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                <!-- HEADER PAGE -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">🛡️ GESTIÓN DE ACCESOS Y USUARIOS</span>
                        <h1 class="settings-page-title">Administradores del Sistema</h1>
                        <p class="settings-page-subtitle">Gestiona las cuentas de administradores, asigna roles de acceso y registra nuevos usuarios.</p>
                    </div>

                    <button class="btn btn-primary btn-save-settings" id="btnOpenCreateAdminModal">
                        <span>➕ Crear Nuevo Administrador</span>
                    </button>
                </div>

                <!-- TABLA DE ADMINISTRADORES -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <div>
                            <h3 class="dash-card-title">Listado de Administradores</h3>
                            <p class="dash-card-subtitle">Cuentas activas con privilegio de acceso al panel de control</p>
                        </div>
                    </div>

                    <div class="dash-table-wrapper" style="margin-top: 1rem;">
                        <table class="dash-table" id="adminsTable">
                            <thead>
                                <tr>
                                    <th>ADMINISTRADOR</th>
                                    <th>CORREO ELECTRÓNICO</th>
                                    <th>TELÉFONO / CELULAR</th>
                                    <th>ROL</th>
                                    <th>ESTADO</th>
                                    <th style="text-align: right;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($administrators as $admin)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                                <img src="{{ $admin->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=300&q=80' }}" alt="{{ $admin->full_name }}" class="dash-avatar-img" style="width: 42px; height: 42px; border-radius: 50%; border: 2px solid var(--color-primary-orange);">
                                                <div>
                                                    <span class="dash-event-name" style="display: block;">{{ $admin->full_name }}</span>
                                                    <small style="color: var(--text-muted); font-weight: 600;">{{ '@' . $admin->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="admin-email-text">{{ $admin->email }}</span>
                                        </td>
                                        <td>
                                            <div class="admin-phone-text">
                                                <span style="font-size: 1.15rem;">{{ $admin->flag_emoji }}</span>
                                                <span><code>{{ $admin->country_code }}</code> {{ $admin->phone }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($admin->role === 'Administrador Principal')
                                                <span class="dash-badge-custom badge-orange">👑 {{ $admin->role }}</span>
                                            @else
                                                <span class="dash-badge-custom badge-blue">🛡️ {{ $admin->role }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($admin->status === 'Activo')
                                                <span class="dash-badge-custom badge-green">✓ {{ $admin->status }}</span>
                                            @else
                                                <span class="dash-badge-custom badge-red">🚫 {{ $admin->status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="dash-actions-cell" style="justify-content: flex-end;">
                                                <button type="button" class="dash-btn-icon-action btn-edit-admin" data-admin='@json($admin)' title="Editar Administrador">✏️</button>
                                                <button type="button" class="dash-btn-icon-action btn-reset-admin" data-id="{{ $admin->id }}" data-name="{{ $admin->full_name }}" title="Restablecer Contraseña" style="color: var(--color-primary-orange);">🔑</button>
                                                <button type="button" class="dash-btn-icon-action btn-delete-admin" data-id="{{ $admin->id }}" data-name="{{ $admin->full_name }}" title="Eliminar Administrador" style="color: #FF1E3C;">🗑️</button>
                                                
                                                <form id="reset-form-{{ $admin->id }}" action="{{ route('web.admins.reset-password', $admin->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                <form id="delete-form-{{ $admin->id }}" action="{{ route('web.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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

    <!-- MODAL CREAR NUEVO ADMINISTRADOR -->
    <div class="admin-modal-overlay" id="createAdminModal">
        <div class="admin-modal-card">
            <div class="admin-modal-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px;">👤</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.15rem;">Crear Nuevo Administrador</h3>
                        <p class="card-header-subtitle">Ingresa los datos personales y credenciales de acceso</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseCreateAdminModal">✕</button>
            </div>

            <form action="{{ route('web.admins.store') }}" method="POST" class="admin-modal-form">
                @csrf
                <div class="admin-modal-grid">
                    <!-- Nombre -->
                    <div class="form-group-custom">
                        <label for="first_name" class="form-label-custom">Nombre <span class="required-star">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-input-custom" required placeholder="Ej: Deivid">
                    </div>

                    <!-- Apellido -->
                    <div class="form-group-custom">
                        <label for="last_name" class="form-label-custom">Apellido <span class="required-star">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-input-custom" required placeholder="Ej: Chipana">
                    </div>

                    <!-- Usuario -->
                    <div class="form-group-custom">
                        <label for="username" class="form-label-custom">Nombre de Usuario <span class="required-star">*</span></label>
                        <input type="text" id="username" name="username" class="form-input-custom" required placeholder="Ej: deivid.chipana">
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="form-group-custom">
                        <label for="email" class="form-label-custom">Correo Electrónico <span class="required-star">*</span></label>
                        <input type="email" id="email" name="email" class="form-input-custom" required placeholder="ejemplo@vivego.pe">
                    </div>

                    <!-- Rol del Administrador -->
                    <div class="form-group-custom">
                        <label for="role" class="form-label-custom">Rol de Administrador <span class="required-star">*</span></label>
                        <select id="role" name="role" class="form-select-custom" required>
                            <option value="Administrador" selected>🛡️ Administrador</option>
                            <option value="Administrador Principal">👑 Administrador Principal</option>
                        </select>
                    </div>

                    <!-- Teléfono Móvil con Selector de País (Plugin Bandera) -->
                    <div class="form-group-custom">
                        <label for="phone" class="form-label-custom">Celular & País <span class="required-star">*</span></label>
                        <div class="country-phone-picker">
                            <select id="country_selector" class="form-select-custom country-select-flag-only" onchange="syncCountry(this)">
                                @foreach($countries as $c)
                                    <option value="{{ $c['code'] }}" data-iso="{{ $c['iso'] }}" {{ $c['iso'] === 'pe' ? 'selected' : '' }}>
                                        {{ $c['display'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="country_code" name="country_code" value="+51">
                            <input type="hidden" id="country_iso" name="country_iso" value="pe">
                            <input type="text" id="phone" name="phone" class="form-input-custom" style="flex: 1;" required placeholder="987654321">
                        </div>
                    </div>

                    <!-- Aviso de Contraseña Generada -->
                    <div class="auto-password-notice" style="grid-column: span 2;">
                        <div class="notice-icon">🔐</div>
                        <div class="notice-text">
                            <strong>Contraseña generada automáticamente:</strong> El sistema asignará una clave segura y la mostrará en pantalla al registrar con un botón para copiarla.
                        </div>
                    </div>
                </div>

                <div class="admin-modal-footer">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelCreateAdmin">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">💾 Registrar Administrador</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR ADMINISTRADOR -->
    <div class="admin-modal-overlay" id="editAdminModal">
        <div class="admin-modal-card">
            <div class="admin-modal-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px; background: rgba(0, 242, 254, 0.15); border-color: rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan);">✏️</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.15rem;">Editar Administrador</h3>
                        <p class="card-header-subtitle">Modifica los datos personales, rol y estado de la cuenta</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseEditAdminModal">✕</button>
            </div>

            <form id="editAdminForm" action="" method="POST" class="admin-modal-form">
                @csrf
                @method('PUT')
                <div class="admin-modal-grid">
                    <!-- Nombre -->
                    <div class="form-group-custom">
                        <label for="edit_first_name" class="form-label-custom">Nombre <span class="required-star">*</span></label>
                        <input type="text" id="edit_first_name" name="first_name" class="form-input-custom" required>
                    </div>

                    <!-- Apellido -->
                    <div class="form-group-custom">
                        <label for="edit_last_name" class="form-label-custom">Apellido <span class="required-star">*</span></label>
                        <input type="text" id="edit_last_name" name="last_name" class="form-input-custom" required>
                    </div>

                    <!-- Usuario -->
                    <div class="form-group-custom">
                        <label for="edit_username" class="form-label-custom">Nombre de Usuario <span class="required-star">*</span></label>
                        <input type="text" id="edit_username" name="username" class="form-input-custom" required>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="form-group-custom">
                        <label for="edit_email" class="form-label-custom">Correo Electrónico <span class="required-star">*</span></label>
                        <input type="email" id="edit_email" name="email" class="form-input-custom" required>
                    </div>

                    <!-- Rol del Administrador -->
                    <div class="form-group-custom">
                        <label for="edit_role" class="form-label-custom">Rol <span class="required-star">*</span></label>
                        <select id="edit_role" name="role" class="form-select-custom" required>
                            <option value="Administrador">🛡️ Administrador</option>
                            <option value="Administrador Principal">👑 Administrador Principal</option>
                        </select>
                    </div>

                    <!-- Estado del Administrador -->
                    <div class="form-group-custom">
                        <label for="edit_status" class="form-label-custom">Estado de la Cuenta <span class="required-star">*</span></label>
                        <select id="edit_status" name="status" class="form-select-custom" required>
                            <option value="Activo">✓ Activo</option>
                            <option value="Inactivo">🚫 Inactivo</option>
                        </select>
                    </div>

                    <!-- Teléfono Móvil con Selector de País -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_phone" class="form-label-custom">Celular & País <span class="required-star">*</span></label>
                        <div class="country-phone-picker">
                            <select id="edit_country_selector" class="form-select-custom country-select-flag-only" onchange="syncEditCountry(this)">
                                @foreach($countries as $c)
                                    <option value="{{ $c['code'] }}" data-iso="{{ $c['iso'] }}">
                                        {{ $c['display'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="edit_country_code" name="country_code" value="+51">
                            <input type="hidden" id="edit_country_iso" name="country_iso" value="pe">
                            <input type="text" id="edit_phone" name="phone" class="form-input-custom" style="flex: 1;" required>
                        </div>
                    </div>
                </div>

                <div class="admin-modal-footer">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelEditAdmin">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">💾 Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESULTADO: CREDENCIALES GENERADAS -->
    @if(session('created_admin'))
        <div class="admin-modal-overlay active" id="createdCredentialsModal">
            <div class="admin-modal-card credentials-modal-card">
                <div class="admin-modal-header">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div class="card-header-icon" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.4); color: #10B981;">🎉</div>
                        <div>
                            <h3 class="card-header-title" style="font-size: 1.2rem; color: #10B981;">¡Administrador Creado con Éxito!</h3>
                            <p class="card-header-subtitle">Credenciales de acceso generadas automáticamente</p>
                        </div>
                    </div>
                    <button class="admin-modal-close" onclick="document.getElementById('createdCredentialsModal').remove()">✕</button>
                </div>

                <div class="credentials-modal-body">
                    <div class="credentials-warning-box">
                        <span>⚠️ <strong>¡Guarda esta información!</strong> Copia esta clave generada antes de cerrar la ventana.</span>
                    </div>

                    <div class="credentials-card">
                        <div class="cred-row">
                            <span class="cred-label">👤 Nombre:</span>
                            <span class="cred-val">{{ session('created_admin.name') }}</span>
                        </div>
                        <div class="cred-row">
                            <span class="cred-label">📧 Email / Usuario:</span>
                            <span class="cred-val">{{ session('created_admin.email') }} <code>({{ '@' . session('created_admin.username') }})</code></span>
                        </div>
                        <div class="cred-row">
                            <span class="cred-label">📱 Celular:</span>
                            <span class="cred-val">{{ session('created_admin.flag') }} {{ session('created_admin.phone') }}</span>
                        </div>
                        <div class="cred-row">
                            <span class="cred-label">👑 Rol:</span>
                            <span class="cred-val">{{ session('created_admin.role') }}</span>
                        </div>

                        <!-- Cajón de Contraseña -->
                        <div class="password-highlight-box">
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                <span style="font-size: 0.725rem; font-weight: 800; color: #94A3B8; letter-spacing: 1px;">CONTRASEÑA GENERADA</span>
                                <span class="pass-code-text" id="rawPasswordValue">{{ session('created_admin.password') }}</span>
                            </div>
                            <button type="button" 
                                    class="btn-copy-pass" 
                                    id="btnCopyPass" 
                                    data-copy-text="👋 ¡Hola, {{ session('created_admin.name') }}!&#10;&#10;Se han generado exitosamente tus credenciales para el sistema Vive Go.&#10;&#10;🌐 Enlace de Ingreso:&#10;{{ route('web.admins') }}&#10;&#10;🔑 Tus Credenciales de Acceso:&#10;• Email: {{ session('created_admin.email') }}&#10;• Contraseña Temporal: {{ session('created_admin.password') }}&#10;• Rol Asignado: {{ session('created_admin.role') }}&#10;&#10;⚠️ NOTA DE SEGURIDAD:&#10;Esta es una contraseña temporal. Por tu seguridad, por favor cámbiala inmediatamente al realizar tu primer inicio de sesión."
                                    onclick="copyCredentialsFromBtn(this)">
                                <span class="copy-icon-span">📋</span> <span class="copy-text-span">Copiar Credenciales</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="admin-modal-footer" style="margin-top: 1.5rem;">
                    <button type="button" class="btn btn-primary btn-save-settings" style="width: 100%; justify-content: center;" onclick="document.getElementById('createdCredentialsModal').remove()">
                        ✓ Entendido, Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL RESULTADO: RESTABLECER CONTRASEÑA -->
    @if(session('reset_password_credentials'))
        <div class="admin-modal-overlay active" id="resetPasswordCredentialsModal">
            <div class="admin-modal-card credentials-modal-card">
                <div class="admin-modal-header">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <div class="card-header-icon" style="width: 48px; height: 48px; background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.4); color: var(--color-primary-orange);">🔑</div>
                        <div>
                            <h3 class="card-header-title" style="font-size: 1.2rem; color: var(--color-primary-orange);">¡Contraseña Restablecida!</h3>
                            <p class="card-header-subtitle">Nueva clave temporal de acceso generada</p>
                        </div>
                    </div>
                    <button class="admin-modal-close" onclick="document.getElementById('resetPasswordCredentialsModal').remove()">✕</button>
                </div>

                <div class="credentials-modal-body">
                    <div class="credentials-warning-box">
                        <span>⚠️ <strong>¡Contraseña Anterior Invalidada!</strong> Copia esta nueva clave temporal para enviarla al administrador.</span>
                    </div>

                    <div class="credentials-card">
                        <div class="cred-row">
                            <span class="cred-label">👤 Nombre:</span>
                            <span class="cred-val">{{ session('reset_password_credentials.name') }}</span>
                        </div>
                        <div class="cred-row">
                            <span class="cred-label">📧 Email / Usuario:</span>
                            <span class="cred-val">{{ session('reset_password_credentials.email') }} <code>({{ '@' . session('reset_password_credentials.username') }})</code></span>
                        </div>

                        <!-- Cajón de Contraseña -->
                        <div class="password-highlight-box">
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                <span style="font-size: 0.725rem; font-weight: 800; color: #94A3B8; letter-spacing: 1px;">NUEVA CONTRASEÑA TEMPORAL</span>
                                <span class="pass-code-text" id="rawResetPasswordValue">{{ session('reset_password_credentials.password') }}</span>
                            </div>
                            <button type="button" 
                                    class="btn-copy-pass" 
                                    id="btnCopyResetPass" 
                                    data-copy-text="👋 ¡Hola, {{ session('reset_password_credentials.name') }}!&#10;&#10;Se ha restablecido exitosamente tu contraseña para el sistema Vive Go.&#10;&#10;🌐 Enlace de Ingreso:&#10;{{ route('web.admins') }}&#10;&#10;🔑 Tus Nuevas Credenciales de Acceso:&#10;• Email: {{ session('reset_password_credentials.email') }}&#10;• Nueva Contraseña Temporal: {{ session('reset_password_credentials.password') }}&#10;• Rol Asignado: {{ session('reset_password_credentials.role') }}&#10;&#10;⚠️ NOTA DE SEGURIDAD:&#10;Tu contraseña anterior ha sido invalidada. Por favor, ingresa con tu nueva clave temporal y cámbiala inmediatamente al realizar tu primer inicio de sesión."
                                    onclick="copyCredentialsFromBtn(this)">
                                <span class="copy-icon-span">📋</span> <span class="copy-text-span">Copiar Nuevas Credenciales</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="admin-modal-footer" style="margin-top: 1.5rem;">
                    <button type="button" class="btn btn-primary btn-save-settings" style="width: 100%; justify-content: center;" onclick="document.getElementById('resetPasswordCredentialsModal').remove()">
                        ✓ Entendido, Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function copyCredentialsFromBtn(btn) {
            const textToCopy = btn.getAttribute('data-copy-text');
            if (!textToCopy) return;

            function showSuccess() {
                const btnText = btn.querySelector('.copy-text-span') || btn;
                const btnIcon = btn.querySelector('.copy-icon-span');
                const oldText = btnText.textContent;
                const oldIcon = btnIcon ? btnIcon.textContent : '';

                if (btnIcon) btnIcon.textContent = '✅';
                btnText.textContent = '¡Copiado al Portapapeles!';

                setTimeout(() => {
                    if (btnIcon) btnIcon.textContent = oldIcon || '📋';
                    btnText.textContent = oldText;
                }, 3000);
            }

            function fallbackCopy() {
                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    document.execCommand('copy');
                    showSuccess();
                } catch (err) {
                    alert('No se pudo copiar automáticamente. Por favor copia manualmente.');
                }
                document.body.removeChild(textarea);
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(showSuccess).catch(fallbackCopy);
            } else {
                fallbackCopy();
            }
        }

        function syncCountry(select) {
            const opt = select.options[select.selectedIndex];
            document.getElementById('country_code').value = select.value;
            document.getElementById('country_iso').value = opt.getAttribute('data-iso');
        }

        function syncEditCountry(select) {
            const opt = select.options[select.selectedIndex];
            document.getElementById('edit_country_code').value = select.value;
            document.getElementById('edit_country_iso').value = opt.getAttribute('data-iso');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Modal Crear Administrador logic
            const createModal = document.getElementById('createAdminModal');
            const openCreateBtn = document.getElementById('btnOpenCreateAdminModal');
            const closeCreateBtn = document.getElementById('btnCloseCreateAdminModal');
            const cancelCreateBtn = document.getElementById('btnCancelCreateAdmin');

            if (openCreateBtn && createModal) {
                openCreateBtn.addEventListener('click', function () {
                    createModal.classList.add('active');
                });
            }

            if (closeCreateBtn && createModal) {
                closeCreateBtn.addEventListener('click', function () {
                    createModal.classList.remove('active');
                });
            }

            if (cancelCreateBtn && createModal) {
                cancelCreateBtn.addEventListener('click', function () {
                    createModal.classList.remove('active');
                });
            }

            if (createModal) {
                createModal.addEventListener('click', function (e) {
                    if (e.target === createModal) createModal.classList.remove('active');
                });
            }

            // Modal Editar Administrador logic
            const editModal = document.getElementById('editAdminModal');
            const closeEditBtn = document.getElementById('btnCloseEditAdminModal');
            const cancelEditBtn = document.getElementById('btnCancelEditAdmin');
            const editForm = document.getElementById('editAdminForm');
            const editBtns = document.querySelectorAll('.btn-edit-admin');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-admin'));
                    
                    document.getElementById('edit_first_name').value = data.first_name || '';
                    document.getElementById('edit_last_name').value = data.last_name || '';
                    document.getElementById('edit_username').value = data.username || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_phone').value = data.phone || '';
                    document.getElementById('edit_role').value = data.role || 'Administrador';
                    document.getElementById('edit_status').value = data.status || 'Activo';

                    const countrySelect = document.getElementById('edit_country_selector');
                    if (countrySelect) {
                        for (let i = 0; i < countrySelect.options.length; i++) {
                            if (countrySelect.options[i].value === data.country_code) {
                                countrySelect.selectedIndex = i;
                                break;
                            }
                        }
                        syncEditCountry(countrySelect);
                    }

                    if (editForm) {
                        editForm.action = `/admin/administradores/${data.id}`;
                    }

                    if (editModal) {
                        editModal.classList.add('active');
                    }
                });
            });

            if (closeEditBtn && editModal) {
                closeEditBtn.addEventListener('click', function () {
                    editModal.classList.remove('active');
                });
            }

            if (cancelEditBtn && editModal) {
                cancelEditBtn.addEventListener('click', function () {
                    editModal.classList.remove('active');
                });
            }

            if (editModal) {
                editModal.addEventListener('click', function (e) {
                    if (e.target === editModal) editModal.classList.remove('active');
                });
            }

            // Botones de Restablecer Contraseña con SweetAlert2
            const resetBtns = document.querySelectorAll('.btn-reset-admin');
            resetBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    Swal.fire({
                        title: '¿Restablecer Contraseña?',
                        text: `Se generará una nueva clave temporal para "${name}" e invalidará la contraseña actual.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#FF5500',
                        cancelButtonColor: '#475569',
                        confirmButtonText: '🔑 Sí, restablecer',
                        cancelButtonText: 'Cancelar',
                        background: '#14141E',
                        color: '#FFFFFF',
                        customClass: {
                            popup: 'swal-dark-popup'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const resetForm = document.getElementById('reset-form-' + id);
                            if (resetForm) resetForm.submit();
                        }
                    });
                });
            });

            // Botones de Eliminar con SweetAlert2
            const deleteBtns = document.querySelectorAll('.btn-delete-admin');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: `Vas a eliminar al administrador "${name}". Esta acción no se puede deshacer y revocará inmediatamente su acceso.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#FF1E3C',
                        cancelButtonColor: '#475569',
                        confirmButtonText: '🗑️ Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        background: '#14141E',
                        color: '#FFFFFF',
                        customClass: {
                            popup: 'swal-dark-popup'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteForm = document.getElementById('delete-form-' + id);
                            if (deleteForm) deleteForm.submit();
                        }
                    });
                });
            });

            // Filter Table Live Search
            const searchInput = document.getElementById('tableFilterInput');
            const tableRows = document.querySelectorAll('#adminsTable tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.toLowerCase().trim();
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }

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
