@extends('layouts.app')

@section('title', 'Gestión de Compañías | Vive Go')

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
                    <li class="dash-nav-item active">
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

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar compañía o RUC / NIT...">
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
                    <div class="alert-custom alert-success" style="margin-bottom: 1.5rem;">
                        <div class="alert-icon-box">✓</div>
                        <div class="alert-content">
                            <h4>¡Operación Exitosa!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">🏢 INFORMACIÓN EMPRESARIAL & COMPAÑÍAS</span>
                        <h1 class="settings-page-title">Gestión de Compañías</h1>
                        <p class="settings-page-subtitle">Administra la información legal, RUC/NIT, correo de contacto y líneas telefónicas de las empresas registradas.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" id="btnOpenCreateCompanyModal" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem;">
                            ➕ Crear Nueva Compañía
                        </button>
                    </div>
                </div>

                <!-- TABLA DE COMPAÑÍAS -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(0, 242, 254, 0.15); border-color: rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan);">🏢</div>
                        <div>
                            <h3 class="card-header-title">Listado de Empresas Registradas</h3>
                            <p class="card-header-subtitle">Compañías dadas de alta para la emisión de comprobantes y gestión de eventos</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="companiesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Compañía & RUC / NIT</th>
                                    <th>Correo Electrónico</th>
                                    <th>Teléfono / Celular</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $index => $company)
                                    <tr>
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                                <div class="card-header-icon" style="width: 38px; height: 38px; font-size: 1.1rem; border-radius: 12px; flex-shrink: 0;">🏢</div>
                                                <div>
                                                    <span class="dash-event-name" style="display: block;">{{ $company->name }}</span>
                                                    <small style="color: var(--color-primary-orange); font-weight: 800; letter-spacing: 0.5px;">RUC / NIT: {{ $company->tax_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($company->email)
                                                <span class="admin-email-text">{{ $company->email }}</span>
                                            @else
                                                <span style="color: #94A3B8; font-style: italic; font-size: 0.85rem;">Sin correo registrado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($company->phone)
                                                <div class="admin-phone-text">
                                                    <span style="font-size: 1.15rem;">{{ $company->flag_emoji }}</span>
                                                    <span><code>{{ $company->country_code }}</code> {{ $company->phone }}</span>
                                                </div>
                                            @else
                                                <span style="color: #94A3B8; font-style: italic; font-size: 0.85rem;">Sin teléfono registrado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($company->status === 'Activo')
                                                <span class="dash-badge-custom badge-green">✓ {{ $company->status }}</span>
                                            @else
                                                <span class="dash-badge-custom badge-red">🚫 {{ $company->status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="dash-actions-cell" style="justify-content: flex-end;">
                                                <button type="button" class="dash-btn-icon-action btn-edit-company" data-company='@json($company)' title="Editar Compañía">✏️</button>
                                                <button type="button" class="dash-btn-icon-action btn-delete-company" data-id="{{ $company->id }}" data-name="{{ $company->name }}" title="Eliminar Compañía" style="color: #FF1E3C;">🗑️</button>
                                                <form id="delete-company-form-{{ $company->id }}" action="{{ route('web.companies.destroy', $company->id) }}" method="POST" style="display: none;">
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

    <!-- MODAL CREAR NUEVA COMPAÑÍA -->
    <div class="admin-modal-overlay" id="createCompanyModal">
        <div class="admin-modal-card">
            <div class="admin-modal-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px;">🏢</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.15rem;">Registrar Nueva Compañía</h3>
                        <p class="card-header-subtitle">Ingresa la información legal y datos de contacto de la empresa</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseCreateCompanyModal">✕</button>
            </div>

            <form action="{{ route('web.companies.store') }}" method="POST" class="admin-modal-form">
                @csrf
                <div class="admin-modal-grid">
                    <!-- Nombre de la Compañía -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="name" class="form-label-custom">Nombre de la Compañía <span class="required-star">*</span></label>
                        <input type="text" id="name" name="name" class="form-input-custom" required placeholder="Ej: PRODUCCIONES VIVE GO S.A.C.">
                    </div>

                    <!-- RUC / NIT -->
                    <div class="form-group-custom">
                        <label for="tax_id" class="form-label-custom">RUC / NIT <span class="required-star">*</span></label>
                        <input type="text" id="tax_id" name="tax_id" class="form-input-custom" required placeholder="Ej: 20601234567">
                    </div>

                    <!-- Estado -->
                    <div class="form-group-custom">
                        <label for="status" class="form-label-custom">Estado de la Compañía <span class="required-star">*</span></label>
                        <select id="status" name="status" class="form-select-custom" required>
                            <option value="Activo">✓ Activo</option>
                            <option value="Inactivo">🚫 Inactivo</option>
                        </select>
                    </div>

                    <!-- Correo Electrónico (OPCIONAL) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="email" class="form-label-custom">Correo Electrónico <small style="color: #94A3B8; font-weight: normal;">(Opcional)</small></label>
                        <input type="email" id="email" name="email" class="form-input-custom" placeholder="contacto@empresa.com">
                    </div>

                    <!-- Teléfono Móvil con Selector de País (OPCIONAL) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="phone" class="form-label-custom">Celular & País <small style="color: #94A3B8; font-weight: normal;">(Opcional)</small></label>
                        <div class="country-phone-picker">
                            <select id="create_company_country_selector" class="form-select-custom country-select-flag-only" onchange="syncCompanyCountry(this)">
                                @foreach($countries as $c)
                                    <option value="{{ $c['code'] }}" data-iso="{{ $c['iso'] }}">
                                        {{ $c['display'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="country_code" name="country_code" value="+51">
                            <input type="hidden" id="country_iso" name="country_iso" value="pe">
                            <input type="text" id="phone" name="phone" class="form-input-custom" style="flex: 1;" placeholder="912345678">
                        </div>
                    </div>

                    <!-- Dirección Fiscal (OPCIONAL) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="address" class="form-label-custom">Dirección Fiscal <small style="color: #94A3B8; font-weight: normal;">(Opcional)</small></label>
                        <input type="text" id="address" name="address" class="form-input-custom" placeholder="Ej: Av. Javier Prado Este 123, San Isidro">
                    </div>
                </div>

                <div class="admin-modal-footer">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelCreateCompany">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">💾 Registrar Compañía</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR COMPAÑÍA -->
    <div class="admin-modal-overlay" id="editCompanyModal">
        <div class="admin-modal-card">
            <div class="admin-modal-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px; background: rgba(0, 242, 254, 0.15); border-color: rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan);">✏️</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.15rem;">Editar Compañía</h3>
                        <p class="card-header-subtitle">Modifica la información legal y datos de contacto de la empresa</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseEditCompanyModal">✕</button>
            </div>

            <form id="editCompanyForm" action="" method="POST" class="admin-modal-form">
                @csrf
                @method('PUT')
                <div class="admin-modal-grid">
                    <!-- Nombre de la Compañía -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_name" class="form-label-custom">Nombre de la Compañía <span class="required-star">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-input-custom" required>
                    </div>

                    <!-- RUC / NIT -->
                    <div class="form-group-custom">
                        <label for="edit_tax_id" class="form-label-custom">RUC / NIT <span class="required-star">*</span></label>
                        <input type="text" id="edit_tax_id" name="tax_id" class="form-input-custom" required>
                    </div>

                    <!-- Estado -->
                    <div class="form-group-custom">
                        <label for="edit_status" class="form-label-custom">Estado de la Compañía <span class="required-star">*</span></label>
                        <select id="edit_status" name="status" class="form-select-custom" required>
                            <option value="Activo">✓ Activo</option>
                            <option value="Inactivo">🚫 Inactivo</option>
                        </select>
                    </div>

                    <!-- Correo Electrónico (OPCIONAL) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_email" class="form-label-custom">Correo Electrónico <small style="color: #94A3B8; font-weight: normal;">(Opcional)</small></label>
                        <input type="email" id="edit_email" name="email" class="form-input-custom">
                    </div>

                    <!-- Teléfono Móvil con Selector de País (OPCIONAL) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_phone" class="form-label-custom">Celular & País <small style="color: #94A3B8; font-weight: normal;">(Opcional)</small></label>
                        <div class="country-phone-picker">
                            <select id="edit_company_country_selector" class="form-select-custom country-select-flag-only" onchange="syncEditCompanyCountry(this)">
                                @foreach($countries as $c)
                                    <option value="{{ $c['code'] }}" data-iso="{{ $c['iso'] }}">
                                        {{ $c['display'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="edit_country_code" name="country_code" value="+51">
                            <input type="hidden" id="edit_country_iso" name="country_iso" value="pe">
                            <input type="text" id="edit_phone" name="phone" class="form-input-custom" style="flex: 1;">
                        </div>
                    </div>

                    <!-- Dirección Fiscal (OPCIONAL) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_address" class="form-label-custom">Dirección Fiscal <small style="color: #94A3B8; font-weight: normal;">(Opcional)</small></label>
                        <input type="text" id="edit_address" name="address" class="form-input-custom">
                    </div>
                </div>

                <div class="admin-modal-footer">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelEditCompany">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">💾 Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function syncCompanyCountry(select) {
            const opt = select.options[select.selectedIndex];
            document.getElementById('country_code').value = select.value;
            document.getElementById('country_iso').value = opt.getAttribute('data-iso');
        }

        function syncEditCompanyCountry(select) {
            const opt = select.options[select.selectedIndex];
            document.getElementById('edit_country_code').value = select.value;
            document.getElementById('edit_country_iso').value = opt.getAttribute('data-iso');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Modal Crear Compañía logic
            const createModal = document.getElementById('createCompanyModal');
            const openCreateBtn = document.getElementById('btnOpenCreateCompanyModal');
            const closeCreateBtn = document.getElementById('btnCloseCreateCompanyModal');
            const cancelCreateBtn = document.getElementById('btnCancelCreateCompany');

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

            // Modal Editar Compañía logic
            const editModal = document.getElementById('editCompanyModal');
            const closeEditBtn = document.getElementById('btnCloseEditCompanyModal');
            const cancelEditBtn = document.getElementById('btnCancelEditCompany');
            const editForm = document.getElementById('editCompanyForm');
            const editBtns = document.querySelectorAll('.btn-edit-company');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-company'));
                    
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_tax_id').value = data.tax_id || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_phone').value = data.phone || '';
                    document.getElementById('edit_address').value = data.address || '';
                    document.getElementById('edit_status').value = data.status || 'Activo';

                    const countrySelect = document.getElementById('edit_company_country_selector');
                    if (countrySelect) {
                        for (let i = 0; i < countrySelect.options.length; i++) {
                            if (countrySelect.options[i].value === data.country_code) {
                                countrySelect.selectedIndex = i;
                                break;
                            }
                        }
                        syncEditCompanyCountry(countrySelect);
                    }

                    if (editForm) {
                        editForm.action = `/admin/compania/${data.id}`;
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

            // Eliminar Compañía con SweetAlert2
            const deleteBtns = document.querySelectorAll('.btn-delete-company');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    Swal.fire({
                        title: '¿Eliminar Compañía?',
                        text: `Vas a eliminar a "${name}". Esta acción dará de baja la empresa del sistema.`,
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
                            const deleteForm = document.getElementById('delete-company-form-' + id);
                            if (deleteForm) deleteForm.submit();
                        }
                    });
                });
            });

            // Live Search Filter en Tabla
            const searchInput = document.getElementById('tableFilterInput');
            const tableRows = document.querySelectorAll('#companiesTable tbody tr');

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
