@extends('layouts.app')

@section('title', 'Tipos de Aforo | Vive Go')

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN PRO MAX HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar tipo de aforo...">
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
                        <span class="settings-tag">🏟️ CONFIGURACIÓN DE AFORO & ZONAS</span>
                        <h1 class="settings-page-title">Tipos de Aforo</h1>
                        <p class="settings-page-subtitle">Administra los tipos de aforo, etiquetas y colores de identificación registrados en la Base de Datos.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" id="btnOpenCreateCapacityModal" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem;">
                            ➕ Crear Tipo de Aforo
                        </button>
                    </div>
                </div>

                <!-- TABLA DE TIPOS DE AFORO -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">🏟️</div>
                        <div>
                            <h3 class="card-header-title">Listado de Zonas & Aforos</h3>
                            <p class="card-header-subtitle">Tipos de aforo y colores de identificación almacenados en MySQL</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="capacityTypesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo de Aforo</th>
                                    <th>Color de Identificación</th>
                                    <th>Estado</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($capacityTypes as $index => $cap)
                                    <tr>
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $index + 1) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.95rem;">
                                                <!-- Círculo de Color -->
                                                <div style="width: 28px; height: 28px; border-radius: 50%; background-color: {{ $cap->color_hex }}; flex-shrink: 0; box-shadow: 0 0 12px {{ $cap->color_hex }}88; border: 2px solid rgba(255,255,255,0.3);"></div>
                                                <span class="dash-event-name" style="font-size: 0.975rem; font-weight: 900; letter-spacing: 0.4px;">{{ $cap->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="dash-badge-custom badge-orange" style="font-size: 0.775rem; font-weight: 800; background: rgba(255,255,255,0.05); color: {{ $cap->color_hex }}; border: 1px solid {{ $cap->color_hex }};">
                                                🎨 {{ $cap->color_hex }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($cap->status === 'Activo')
                                                <span class="dash-badge-custom badge-green">✓ {{ $cap->status }}</span>
                                            @else
                                                <span class="dash-badge-custom badge-red">🚫 {{ $cap->status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="dash-actions-cell" style="justify-content: flex-end;">
                                                <button type="button" class="dash-btn-icon-action btn-edit-capacity" data-capacity='@json($cap)' title="Editar Aforo">✏️</button>
                                                <button type="button" class="dash-btn-icon-action btn-delete-capacity" data-id="{{ $cap->id }}" data-name="{{ $cap->name }}" title="Eliminar Aforo" style="color: #FF1E3C;">🗑️</button>
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

    <!-- MODAL CREAR TIPO DE AFORO -->
    <div class="admin-modal-overlay" id="createCapacityModal">
        <div class="admin-modal-card" style="max-width: 520px;">
            <div class="admin-modal-header">
                <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">➕</div>
                <div>
                    <h3 class="admin-modal-title">Nuevo Tipo de Aforo</h3>
                    <p class="admin-modal-subtitle">Registra una nueva categoría de zona en la base de datos</p>
                </div>
                <button class="admin-modal-close" id="btnCloseCreateCapacityModal">✕</button>
            </div>

            <form action="{{ route('web.capacity_types.store') }}" method="POST" class="admin-modal-form">
                @csrf
                <div class="form-group-custom">
                    <label for="create_name" class="form-label-custom">Nombre / Tipo de Aforo <span class="required-star">*</span></label>
                    <input type="text" id="create_name" name="name" class="form-input-custom" required placeholder="Ej. ZONA SUPER VIP, PLATINUM, BOX D"` uppercase style="text-transform: uppercase;">
                </div>

                <div class="form-group-custom">
                    <label for="create_color_hex" class="form-label-custom">Color de Identificación <span class="required-star">*</span></label>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <input type="color" id="create_color_picker" value="#FF5500" style="width: 45px; height: 42px; border-radius: 8px; border: none; cursor: pointer;" onchange="document.getElementById('create_color_hex').value = this.value">
                        <input type="text" id="create_color_hex" name="color_hex" class="form-input-custom" value="#FF5500" required placeholder="#FF5500" style="flex: 1;">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="create_status" class="form-label-custom">Estado <span class="required-star">*</span></label>
                    <select id="create_status" name="status" class="form-select-custom" required>
                        <option value="Activo" selected>✓ Activo</option>
                        <option value="Inactivo">🚫 Inactivo</option>
                    </select>
                </div>

                <div class="admin-modal-actions">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelCreateCapacityModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">Guardar Tipo de Aforo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR TIPO DE AFORO -->
    <div class="admin-modal-overlay" id="editCapacityModal">
        <div class="admin-modal-card" style="max-width: 520px;">
            <div class="admin-modal-header">
                <div class="card-header-icon" style="background: rgba(37, 99, 235, 0.15); border-color: rgba(37, 99, 235, 0.3); color: #2563EB;">✏️</div>
                <div>
                    <h3 class="admin-modal-title">Editar Tipo de Aforo</h3>
                    <p class="admin-modal-subtitle">Modifica el nombre o color del aforo en la base de datos</p>
                </div>
                <button class="admin-modal-close" id="btnCloseEditCapacityModal">✕</button>
            </div>

            <form id="formEditCapacity" method="POST" class="admin-modal-form">
                @csrf
                @method('PUT')
                <div class="form-group-custom">
                    <label for="edit_name" class="form-label-custom">Nombre / Tipo de Aforo <span class="required-star">*</span></label>
                    <input type="text" id="edit_name" name="name" class="form-input-custom" required style="text-transform: uppercase;">
                </div>

                <div class="form-group-custom">
                    <label for="edit_color_hex" class="form-label-custom">Color de Identificación <span class="required-star">*</span></label>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <input type="color" id="edit_color_picker" style="width: 45px; height: 42px; border-radius: 8px; border: none; cursor: pointer;" onchange="document.getElementById('edit_color_hex').value = this.value">
                        <input type="text" id="edit_color_hex" name="color_hex" class="form-input-custom" required style="flex: 1;">
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="edit_status" class="form-label-custom">Estado <span class="required-star">*</span></label>
                    <select id="edit_status" name="status" class="form-select-custom" required>
                        <option value="Activo">✓ Activo</option>
                        <option value="Inactivo">🚫 Inactivo</option>
                    </select>
                </div>

                <div class="admin-modal-actions">
                    <button type="button" class="btn btn-cancel-custom" id="btnCancelEditCapacityModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save-settings">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FORMULARIO OCULTO PARA ELIMINAR -->
    <form id="deleteCapacityForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Crear
            const createModal = document.getElementById('createCapacityModal');
            const openCreateBtn = document.getElementById('btnOpenCreateCapacityModal');
            const closeCreateBtn = document.getElementById('btnCloseCreateCapacityModal');
            const cancelCreateBtn = document.getElementById('btnCancelCreateCapacityModal');

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

            // Modal Editar
            const editModal = document.getElementById('editCapacityModal');
            const closeEditBtn = document.getElementById('btnCloseEditCapacityModal');
            const cancelEditBtn = document.getElementById('btnCancelEditCapacityModal');
            const editForm = document.getElementById('formEditCapacity');

            document.querySelectorAll('.btn-edit-capacity').forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-capacity'));
                    if (editForm && data) {
                        editForm.action = `/admin/aforo/${data.id}`;
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_color_hex').value = data.color_hex;
                        document.getElementById('edit_color_picker').value = data.color_hex;
                        document.getElementById('edit_status').value = data.status;
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

            // Eliminar con SweetAlert2
            document.querySelectorAll('.btn-delete-capacity').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const deleteForm = document.getElementById('deleteCapacityForm');

                    Swal.fire({
                        title: '¿Eliminar Tipo de Aforo?',
                        text: `¿Estás seguro de que deseas eliminar permanentemente "${name}" de la base de datos?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#475569',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then((result) => {
                        if (result.isConfirmed && deleteForm) {
                            deleteForm.action = `/admin/aforo/${id}`;
                            deleteForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
