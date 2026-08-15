@extends('layouts.app')

@section('title', 'Gestión de Categorías | Vive Go')

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
                    <li class="dash-nav-item">
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
                    <li class="dash-nav-item">
                        <a href="{{ route('web.companies') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🏢</span>
                            <span class="dash-nav-text">Compañía</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.managers') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">👤</span>
                            <span class="dash-nav-text">Responsable</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar categoría o slug...">
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
                    <div class="alert-custom alert-success">
                        <div class="alert-icon-box">✓</div>
                        <div class="alert-content">
                            <h4>¡Operación Exitosa!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()" title="Cerrar Notificación">✕</button>
                    </div>
                @endif

                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">📂 CLASIFICACIÓN & CATÁLOGO</span>
                        <h1 class="settings-page-title">Gestión de Categorías</h1>
                        <p class="settings-page-subtitle">Administra las categorías de espectáculos para organizar y filtrar los eventos en la plataforma.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" id="btnOpenCreateCategoryModal" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem;">
                            ➕ Crear Nueva Categoría
                        </button>
                    </div>
                </div>

                <!-- TABLA DE CATEGORÍAS -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">📂</div>
                        <div>
                            <h3 class="card-header-title">Listado de Categorías de Eventos</h3>
                            <p class="card-header-subtitle">Clasificación oficial de espectáculos para filtros y recomendaciones</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Icono & Categoría</th>
                                    <th>Slug / URL Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $cat)
                                    <tr>
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                                <div class="card-header-icon" style="width: 38px; height: 38px; font-size: 1.15rem; border-radius: 12px; flex-shrink: 0; background: rgba(0, 242, 254, 0.12); border-color: rgba(0, 242, 254, 0.3); color: var(--color-neon-cyan);">
                                                    {{ $cat->icon }}
                                                </div>
                                                <span class="dash-event-name" style="font-size: 0.975rem;">{{ $cat->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <code>/categoria/{{ $cat->slug }}</code>
                                        </td>
                                        <td>
                                            @if($cat->description)
                                                <span class="admin-email-text">{{ $cat->description }}</span>
                                            @else
                                                <span style="color: #94A3B8; font-style: italic; font-size: 0.85rem;">Sin descripción</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cat->status === 'Activo')
                                                <span class="dash-badge-custom badge-green">✓ {{ $cat->status }}</span>
                                            @else
                                                <span class="dash-badge-custom badge-red">🚫 {{ $cat->status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="dash-actions-cell" style="justify-content: flex-end;">
                                                <button type="button" class="dash-btn-icon-action btn-edit-category" data-category='@json($cat)' title="Editar Categoría">✏️</button>
                                                <button type="button" class="dash-btn-icon-action btn-delete-category" data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" title="Eliminar Categoría" style="color: #FF1E3C;">🗑️</button>
                                                
                                                <form id="delete-category-form-{{ $cat->id }}" action="{{ route('web.categories.destroy', $cat->id) }}" method="POST" style="display: none;">
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

    <!-- MODAL CREAR CATEGORÍA -->
    <div class="admin-modal-overlay" id="createCategoryModal">
        <div class="admin-modal-card">
            <div class="admin-modal-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px;">📂</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.15rem;">Registrar Nueva Categoría</h3>
                        <p class="card-header-subtitle">Ingresa el nombre, icono y descripción de la categoría</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseCreateCategoryModal">✕</button>
            </div>

            <form action="{{ route('web.categories.store') }}" method="POST" class="admin-modal-form">
                @csrf
                <div class="admin-modal-grid">
                    <!-- Nombre de la Categoría -->
                    <div class="form-group-custom">
                        <label for="name" class="form-label-custom">Nombre de la Categoría <span class="required-star">*</span></label>
                        <input type="text" id="name" name="name" class="form-input-custom" required placeholder="Ej: Conciertos, Teatro, Festivales">
                    </div>

                    <!-- Icono Emoji -->
                    <div class="form-group-custom">
                        <label for="icon" class="form-label-custom">Icono / Emoji <span class="required-star">*</span></label>
                        <input type="text" id="icon" name="icon" class="form-input-custom" required placeholder="Ej: 🎤, 🎪, 🎭, 💻, ⚽">
                    </div>

                    <!-- Estado -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="status" class="form-label-custom">Estado <span class="required-star">*</span></label>
                        <select id="status" name="status" class="form-select-custom" required>
                            <option value="Activo">✓ Activo</option>
                            <option value="Inactivo">🚫 Inactivo</option>
                        </select>
                    </div>

                    <!-- Descripción (Opcional) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="description" class="form-label-custom">Descripción <small style="color: #94A3B8;">(Opcional)</small></label>
                        <input type="text" id="description" name="description" class="form-input-custom" placeholder="Breve resumen de la categoría...">
                    </div>
                </div>

                <div class="admin-modal-footer">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelCreateCategory">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">💾 Registrar Categoría</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR CATEGORÍA -->
    <div class="admin-modal-overlay" id="editCategoryModal">
        <div class="admin-modal-card">
            <div class="admin-modal-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px; background: rgba(0, 242, 254, 0.15); border-color: rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan);">✏️</div>
                    <div>
                        <h3 class="card-header-title" style="font-size: 1.15rem;">Editar Categoría</h3>
                        <p class="card-header-subtitle">Modifica la información, icono y estado de la categoría</p>
                    </div>
                </div>
                <button class="admin-modal-close" id="btnCloseEditCategoryModal">✕</button>
            </div>

            <form id="editCategoryForm" action="" method="POST" class="admin-modal-form">
                @csrf
                @method('PUT')
                <div class="admin-modal-grid">
                    <!-- Nombre de la Categoría -->
                    <div class="form-group-custom">
                        <label for="edit_name" class="form-label-custom">Nombre de la Categoría <span class="required-star">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-input-custom" required>
                    </div>

                    <!-- Icono Emoji -->
                    <div class="form-group-custom">
                        <label for="edit_icon" class="form-label-custom">Icono / Emoji <span class="required-star">*</span></label>
                        <input type="text" id="edit_icon" name="icon" class="form-input-custom" required>
                    </div>

                    <!-- Estado -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_status" class="form-label-custom">Estado <span class="required-star">*</span></label>
                        <select id="edit_status" name="status" class="form-select-custom" required>
                            <option value="Activo">✓ Activo</option>
                            <option value="Inactivo">🚫 Inactivo</option>
                        </select>
                    </div>

                    <!-- Descripción (Opcional) -->
                    <div class="form-group-custom" style="grid-column: span 2;">
                        <label for="edit_description" class="form-label-custom">Descripción <small style="color: #94A3B8;">(Opcional)</small></label>
                        <input type="text" id="edit_description" name="description" class="form-input-custom">
                    </div>
                </div>

                <div class="admin-modal-footer">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelEditCategory">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">💾 Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Crear Categoría
            const createModal = document.getElementById('createCategoryModal');
            const openCreateBtn = document.getElementById('btnOpenCreateCategoryModal');
            const closeCreateBtn = document.getElementById('btnCloseCreateCategoryModal');
            const cancelCreateBtn = document.getElementById('btnCancelCreateCategory');

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

            // Modal Editar Categoría
            const editModal = document.getElementById('editCategoryModal');
            const closeEditBtn = document.getElementById('btnCloseEditCategoryModal');
            const cancelEditBtn = document.getElementById('btnCancelEditCategory');
            const editForm = document.getElementById('editCategoryForm');
            const editBtns = document.querySelectorAll('.btn-edit-category');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-category'));
                    
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_icon').value = data.icon || '🎤';
                    document.getElementById('edit_description').value = data.description || '';
                    document.getElementById('edit_status').value = data.status || 'Activo';

                    if (editForm) {
                        editForm.action = `/admin/categorias/${data.id}`;
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

            // Eliminar Categoría con SweetAlert2
            const deleteBtns = document.querySelectorAll('.btn-delete-category');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    Swal.fire({
                        title: '¿Eliminar Categoría?',
                        text: `Vas a eliminar a "${name}". Esta acción desvincularía la categoría del catálogo.`,
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
                            const deleteForm = document.getElementById('delete-category-form-' + id);
                            if (deleteForm) deleteForm.submit();
                        }
                    });
                });
            });

            // Live Search Filter en Tabla
            const searchInput = document.getElementById('tableFilterInput');
            const tableRows = document.querySelectorAll('#categoriesTable tbody tr');

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
