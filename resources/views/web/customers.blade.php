@extends('layouts.app')

@section('title', 'Gestión de Clientes & Compradores | Vive Go')

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" id="customerSearchInput" class="dash-search-input" placeholder="Buscar cliente por nombre, DNI, correo o teléfono...">
                    <span class="dash-kbd-shortcut">⌘K</span>
                </div>

                <div class="dash-top-actions">
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema">
                        <span id="themeToggleIcon">☀️</span>
                    </button>
                    <button class="dash-icon-btn" id="btnNotifications" title="Notificaciones">
                        <span>🔔</span>
                        <span class="dash-unread-dot"></span>
                    </button>
                </div>
            </header>

            <div class="dash-container">
                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">👥 GESTIÓN DE USUARIOS & COMPRADORES</span>
                        <h1 class="settings-page-title">Directorio de Clientes</h1>
                        <p class="settings-page-subtitle">Visualiza la fecha de registro de cada cliente, sus boletos comprados y gestiona o resetea sus credenciales de acceso.</p>
                    </div>
                </div>

                <!-- CARDS DE MÉTRICAS RÁPIDAS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; backdrop-filter: blur(12px);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #3B82F6; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            👥
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Total Clientes</span>
                            <h3 id="statTotalCustomers" style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; margin: 0;">{{ $stats['total_customers'] }}</h3>
                        </div>
                    </div>

                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; backdrop-filter: blur(12px);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(255, 85, 0, 0.15); border: 1px solid rgba(255, 85, 0, 0.3); color: #FF5500; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            🎟️
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Boletos Comprados</span>
                            <h3 id="statTotalTickets" style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; margin: 0;">{{ $stats['total_tickets_bought'] }}</h3>
                        </div>
                    </div>

                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; backdrop-filter: blur(12px);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10B981; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            💰
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Total Facturado</span>
                            <h3 id="statTotalRevenue" style="font-size: 1.6rem; font-weight: 900; color: #10B981; margin: 0;" data-value="{{ $stats['total_revenue'] }}">S/ {{ number_format($stats['total_revenue'], 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE CLIENTES -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(0, 242, 254, 0.15); border-color: rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan);">👥</div>
                        <div>
                            <h3 class="card-header-title">Listado de Clientes Registrados</h3>
                            <p class="card-header-subtitle">Historial de creación de cuenta, compras acumuladas y acciones de soporte</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="customersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente & DNI</th>
                                    <th>Contacto (Email / Celular)</th>
                                    <th>Fecha de Creación</th>
                                    <th style="text-align: center;">Boletos</th>
                                    <th>Total Invertido</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $index => $customer)
                                    <tr class="customer-row" id="customer-row-{{ $customer->id }}" data-tickets="{{ $customer->total_tickets }}" data-spent="{{ $customer->total_spent }}">
                                        <td style="color: #64748B; font-weight: 700;">{{ $index + 1 }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: #FFF; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong style="color: #FFFFFF; font-size: 0.95rem; display: block;">{{ $customer->name }}</strong>
                                                    <span style="background: rgba(255, 255, 255, 0.08); color: #94A3B8; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px;">
                                                        DNI: {{ $customer->dni ?: 'No especificado' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.85rem;">
                                                <span style="color: #E2E8F0; display: block;">📧 {{ $customer->email }}</span>
                                                <span style="color: #94A3B8; font-size: 0.775rem;">📱 {{ $customer->phone ?: 'Sin teléfono' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color: #94A3B8; font-size: 0.85rem; font-weight: 600;">
                                                {{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : 'Reciente' }}
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span style="background: rgba(255, 85, 0, 0.15); color: #FF5500; font-weight: 900; font-size: 0.95rem; padding: 0.25rem 0.75rem; border-radius: 10px; border: 1px solid rgba(255, 85, 0, 0.3);">
                                                {{ $customer->total_tickets }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: #10B981; font-size: 1rem; font-weight: 900;">
                                                S/ {{ number_format($customer->total_spent, 2) }}
                                            </strong>
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: inline-flex; align-items: center; gap: 0.45rem; justify-content: flex-end;">
                                                <!-- Botón Ver Detalle & Boletos -->
                                                <button type="button" class="btn btn-sm" onclick="openCustomerDetails({{ $customer->id }})" title="Ver Boletos y Detalle de Compras" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); color: #60A5FA; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                                                    <span>🎟️</span>
                                                    <span>Ver Boletos</span>
                                                </button>

                                                <!-- Botón Resetear Contraseña -->
                                                <button type="button" class="btn btn-sm" onclick="openResetPasswordModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ $customer->email }}')" title="Resetear Contraseña" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #FBBF24; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                                                    <span>🔑</span>
                                                    <span>Reset Password</span>
                                                </button>

                                                <!-- Botón Eliminar Cliente -->
                                                <button type="button" class="btn btn-sm" onclick="openDeleteCustomerModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $customer->total_tickets }}, {{ $customer->total_spent }})" title="Eliminar Cliente, Boletos y Cuenta" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171; padding: 0.45rem 0.85rem; font-size: 0.8rem; font-weight: 800; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                                                    <span>🗑️</span>
                                                    <span>Eliminar</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyCustomersRow">
                                        <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: #94A3B8;">
                                            <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">👥</span>
                                            No se han registrado clientes ni compras online aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL DETALLE DE COMPRAS & BOLETOS DEL CLIENTE -->
    <div class="modal-backdrop-custom" id="customerDetailsModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
        <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                <div>
                    <span style="background: rgba(59, 130, 246, 0.15); color: #60A5FA; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 12px; border: 1px solid rgba(59, 130, 246, 0.3);">
                        DETALLE DEL CLIENTE
                    </span>
                    <h2 id="modalCustName" style="font-size: 1.5rem; font-weight: 900; color: #FFF; margin: 0.35rem 0 0.1rem 0;">Cargando...</h2>
                    <p id="modalCustEmail" style="color: #94A3B8; font-size: 0.875rem; margin: 0;">...</p>
                </div>
                <button type="button" onclick="closeCustomerDetailsModal()" style="background: rgba(255,255,255,0.08); border: none; color: #FFF; width: 36px; height: 36px; border-radius: 10px; font-size: 1.1rem; cursor: pointer;">✕</button>
            </div>

            <div id="modalCustSalesContainer">
                <p style="color: #94A3B8; text-align: center; padding: 2rem;">Cargando compras y boletos...</p>
            </div>
        </div>
    </div>

    <!-- MODAL RESETEAR CONTRASEÑA -->
    <div class="modal-backdrop-custom" id="resetPasswordModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
        <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; width: 95%; max-width: 500px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                <div>
                    <h3 style="font-size: 1.3rem; font-weight: 900; color: #FFF; margin: 0;">🔑 Resetear Contraseña</h3>
                    <p id="resetCustInfo" style="color: #94A3B8; font-size: 0.85rem; margin: 0.35rem 0 0 0;">Genera una nueva contraseña para el cliente</p>
                </div>
                <button type="button" onclick="closeResetPasswordModal()" style="background: rgba(255,255,255,0.08); border: none; color: #FFF; width: 32px; height: 32px; border-radius: 8px; cursor: pointer;">✕</button>
            </div>

            <div id="resetSuccessAlert" style="display: none; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); border-radius: 12px; padding: 1rem; margin-bottom: 1.25rem;">
                <strong style="color: #34D399; display: block; font-size: 0.9rem;">✓ ¡Contraseña Actualizada con Éxito!</strong>
                <p style="color: #E2E8F0; font-size: 0.85rem; margin: 0.5rem 0;">Nueva contraseña asignada:</p>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="text" id="txtGeneratedPass" readonly style="background: #1E293B; border: 1px solid rgba(255,255,255,0.2); color: #FF5500; font-family: monospace; font-size: 1.1rem; font-weight: 900; padding: 0.5rem 0.75rem; border-radius: 8px; width: 100%;">
                    <button type="button" onclick="copyNewPass()" style="background: #FF5500; border: none; color: #FFF; font-weight: 800; padding: 0.55rem 1rem; border-radius: 8px; cursor: pointer; white-space: nowrap;">Copiar</button>
                </div>
            </div>

            <form id="formResetPass" onsubmit="submitResetPassword(event)">
                <input type="hidden" id="resetCustomerId">
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; color: #94A3B8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.4rem;">
                        Contraseña personalizada (opcional):
                    </label>
                    <input type="text" id="customPasswordInput" placeholder="Dejar en blanco para autogenerar" style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.9rem;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="closeResetPasswordModal()" style="background: rgba(255,255,255,0.08); border: none; color: #E2E8F0; padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; cursor: pointer;">
                        Cerrar
                    </button>
                    <button type="submit" id="btnSubmitReset" style="background: #EF4444; border: none; color: #FFF; padding: 0.65rem 1.5rem; font-weight: 800; border-radius: 10px; cursor: pointer;">
                        Generar Nueva Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT CLIENTES & MODALES -->
    <script>
        // Filtro de búsqueda en vivo
        document.getElementById('customerSearchInput')?.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#customersTable tbody tr.customer-row');
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                r.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Abrir modal de detalles y boletos
        function openCustomerDetails(id) {
            const modal = document.getElementById('customerDetailsModal');
            modal.style.display = 'flex';
            document.getElementById('modalCustSalesContainer').innerHTML = '<p style="color: #94A3B8; text-align: center; padding: 2rem;">Cargando información del cliente...</p>';

            fetch(`/admin/clientes/${id}/detalle`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('modalCustName').textContent = data.customer.name;
                        document.getElementById('modalCustEmail').textContent = `📧 ${data.customer.email} | 📱 ${data.customer.phone || 'Sin teléfono'} | DNI: ${data.customer.dni || 'S/D'}`;

                        let html = '';
                        if (!data.sales || data.sales.length === 0) {
                            html = '<p style="color: #94A3B8; text-align: center; padding: 2rem;">Este cliente no tiene boletos registrados todavía.</p>';
                        } else {
                            html = '<div style="display: flex; flex-direction: column; gap: 1rem;">';
                            data.sales.forEach(s => {
                                html += `
                                    <div style="background: #1E293B; border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 1.25rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                                            <div>
                                                <span style="color: #FF5500; font-family: monospace; font-weight: 800; font-size: 0.95rem;">${s.receipt_number}</span>
                                                <small style="color: #94A3B8; margin-left: 0.5rem;">${new Date(s.created_at).toLocaleString()}</small>
                                            </div>
                                            <span style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-weight: 800; font-size: 0.8rem; padding: 0.2rem 0.6rem; border-radius: 6px;">
                                                S/ ${parseFloat(s.total_amount).toFixed(2)}
                                            </span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <strong style="color: #FFF; font-size: 1rem;">${s.event ? s.event.title : 'Evento ViveGo'}</strong>
                                                <p style="color: #94A3B8; font-size: 0.85rem; margin: 0.2rem 0 0 0;">Zona: <strong style="color: #60A5FA;">${s.zone_name}</strong> | Cantidad: <strong>${s.quantity} boleto(s)</strong></p>
                                            </div>
                                            <a href="/checkout/confirmacion/${s.id}" target="_blank" style="background: rgba(255,85,0,0.15); color: #FF5500; border: 1px solid rgba(255,85,0,0.3); padding: 0.45rem 0.85rem; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: 800;">
                                                🔍 Ver Voucher
                                            </a>
                                        </div>
                                    </div>
                                `;
                            });
                            html += '</div>';
                        }
                        document.getElementById('modalCustSalesContainer').innerHTML = html;
                    }
                })
                .catch(err => {
                    document.getElementById('modalCustSalesContainer').innerHTML = '<p style="color: #EF4444; text-align: center; padding: 2rem;">Error al cargar detalles del cliente.</p>';
                });
        }

        function closeCustomerDetailsModal() {
            document.getElementById('customerDetailsModal').style.display = 'none';
        }

        // Modal de Reset de Contraseña
        function openResetPasswordModal(id, name, email) {
            document.getElementById('resetCustomerId').value = id;
            document.getElementById('resetCustInfo').textContent = `Cliente: ${name} (${email})`;
            document.getElementById('resetSuccessAlert').style.display = 'none';
            document.getElementById('customPasswordInput').value = '';
            document.getElementById('resetPasswordModal').style.display = 'flex';
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').style.display = 'none';
        }

        function submitResetPassword(e) {
            e.preventDefault();
            const id = document.getElementById('resetCustomerId').value;
            const customPass = document.getElementById('customPasswordInput').value;
            const btn = document.getElementById('btnSubmitReset');
            btn.disabled = true;
            btn.textContent = 'Actualizando...';

            fetch(`/admin/clientes/${id}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ custom_password: customPass })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Generar Nueva Contraseña';
                if (data.success) {
                    document.getElementById('resetSuccessAlert').style.display = 'block';
                    document.getElementById('txtGeneratedPass').value = data.new_password;
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Generar Nueva Contraseña';
                alert('Error al resetear la contraseña.');
            });
        }

        // Modal de Eliminación de Cliente
        function openDeleteCustomerModal(id, name, tickets, spent) {
            document.getElementById('deleteCustomerId').value = id;
            document.getElementById('deleteCustomerTickets').value = tickets;
            document.getElementById('deleteCustomerSpent').value = spent;
            document.getElementById('deleteCustName').textContent = `"${name}"`;
            document.getElementById('deleteCustTicketsCount').textContent = `${tickets} boleto(s) (S/ ${parseFloat(spent).toFixed(2)})`;
            document.getElementById('deleteCustomerModal').style.display = 'flex';
        }

        function closeDeleteCustomerModal() {
            document.getElementById('deleteCustomerModal').style.display = 'none';
        }

        function executeDeleteCustomer() {
            const id = document.getElementById('deleteCustomerId').value;
            const tickets = parseInt(document.getElementById('deleteCustomerTickets').value) || 0;
            const spent = parseFloat(document.getElementById('deleteCustomerSpent').value) || 0;
            const btn = document.getElementById('btnConfirmDelete');

            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span><span>Eliminando...</span>';

            fetch(`/admin/clientes/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<span>🗑️</span><span>Sí, Eliminar Permanentemente</span>';

                if (data.success) {
                    closeDeleteCustomerModal();

                    // Animar retiro de la fila de la tabla
                    const row = document.getElementById(`customer-row-${id}`);
                    if (row) {
                        row.style.transition = 'all 0.4s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            row.remove();
                            // Si ya no quedan clientes, mostrar estado vacío
                            const remainingRows = document.querySelectorAll('#customersTable tbody tr.customer-row');
                            if (remainingRows.length === 0) {
                                document.querySelector('#customersTable tbody').innerHTML = `
                                    <tr id="emptyCustomersRow">
                                        <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: #94A3B8;">
                                            <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">👥</span>
                                            No se han registrado clientes ni compras online aún.
                                        </td>
                                    </tr>
                                `;
                            }
                        }, 400);
                    }

                    // Actualizar contadores de las tarjetas superiores
                    const statCust = document.getElementById('statTotalCustomers');
                    if (statCust) {
                        const current = parseInt(statCust.textContent) || 0;
                        statCust.textContent = Math.max(0, current - 1);
                    }

                    const statTix = document.getElementById('statTotalTickets');
                    if (statTix) {
                        const current = parseInt(statTix.textContent) || 0;
                        statTix.textContent = Math.max(0, current - tickets);
                    }

                    const statRev = document.getElementById('statTotalRevenue');
                    if (statRev) {
                        const currentVal = parseFloat(statRev.getAttribute('data-value')) || 0;
                        const newVal = Math.max(0, currentVal - spent);
                        statRev.setAttribute('data-value', newVal);
                        statRev.textContent = `S/ ${newVal.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }

                    // Notificación tipo toast elegante
                    showCustomerToast(data.message || 'Cliente y boletos eliminados exitosamente.', 'success');
                } else {
                    alert(data.message || 'Error al eliminar el cliente.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<span>🗑️</span><span>Sí, Eliminar Permanentemente</span>';
                alert('Ocurrió un error al intentar eliminar el cliente.');
            });
        }

        // Toast de notificación
        function showCustomerToast(msg, type = 'success') {
            let toast = document.getElementById('customerActionToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'customerActionToast';
                toast.style.position = 'fixed';
                toast.style.bottom = '25px';
                toast.style.right = '25px';
                toast.style.zIndex = '99999';
                toast.style.padding = '14px 22px';
                toast.style.borderRadius = '12px';
                toast.style.fontWeight = '800';
                toast.style.fontSize = '0.9rem';
                toast.style.boxShadow = '0 10px 30px rgba(0,0,0,0.5)';
                toast.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                toast.style.display = 'flex';
                toast.style.alignItems = 'center';
                toast.style.gap = '0.75rem';
                document.body.appendChild(toast);
            }

            toast.style.background = type === 'success' ? '#10B981' : '#EF4444';
            toast.style.color = '#FFFFFF';
            toast.innerHTML = `<span>${type === 'success' ? '✓' : '⚠️'}</span><span>${msg}</span>`;
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(15px)';
            }, 3500);
        }
    </script>

    <!-- MODAL CONFIRMAR ELIMINACIÓN DE CLIENTE -->
    <div class="modal-backdrop-custom" id="deleteCustomerModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
        <div style="background: #0F172A; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 20px; width: 95%; max-width: 500px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6); position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #EF4444; font-size: 1.3rem; display: flex; align-items: center; justify-content: center;">
                        ⚠️
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 900; color: #FFF; margin: 0;">Eliminar Cliente</h3>
                        <p style="color: #94A3B8; font-size: 0.8rem; margin: 0.2rem 0 0 0;">Esta acción es destructiva e irreversible</p>
                    </div>
                </div>
                <button type="button" onclick="closeDeleteCustomerModal()" style="background: rgba(255,255,255,0.08); border: none; color: #FFF; width: 32px; height: 32px; border-radius: 8px; cursor: pointer;">✕</button>
            </div>

            <input type="hidden" id="deleteCustomerId">
            <input type="hidden" id="deleteCustomerTickets">
            <input type="hidden" id="deleteCustomerSpent">

            <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <p style="color: #E2E8F0; font-size: 0.925rem; margin: 0 0 0.75rem 0; line-height: 1.5;">
                    ¿Estás seguro de que deseas eliminar la cuenta del cliente <strong id="deleteCustName" style="color: #FF5500;"></strong>?
                </p>
                <ul style="color: #94A3B8; font-size: 0.825rem; margin: 0; padding-left: 1.25rem; line-height: 1.6;">
                    <li>Se eliminará su perfil y credenciales de acceso.</li>
                    <li style="color: #10B981; font-weight: 700;">✓ Las entradas y ventas asociadas se conservarán intactas en el sistema.</li>
                </ul>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeDeleteCustomerModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: #E2E8F0; padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; cursor: pointer;">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmDelete" onclick="executeDeleteCustomer()" style="background: linear-gradient(135deg, #EF4444, #DC2626); border: none; color: #FFF; padding: 0.65rem 1.5rem; font-weight: 800; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 15px rgba(239,68,68,0.4);">
                    <span>🗑️</span>
                    <span>Sí, Eliminar Permanentemente</span>
                </button>
            </div>
        </div>
    </div>
@endsection
