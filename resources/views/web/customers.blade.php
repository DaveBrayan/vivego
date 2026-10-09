@extends('layouts.app')

@section('title', 'Gestión de Clientes & Compradores | Vive Go')

@push('styles')
    <style>
        /* Estilos de Paginación ViveGo */
        .dt-page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.5rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #E2E8F0;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dt-page-btn:hover:not(.disabled):not(.active) {
            background: rgba(255, 85, 0, 0.15);
            border-color: rgba(255, 85, 0, 0.4);
            color: #FF5500;
            transform: translateY(-1px);
        }

        .dt-page-btn.active {
            background: linear-gradient(135deg, #FF5500, #FF7700);
            border-color: #FF5500;
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(255, 85, 0, 0.35);
        }

        .dt-page-btn.disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
        }

        .dt-page-dots {
            color: #64748B;
            padding: 0 0.25rem;
            font-weight: 800;
            line-height: 36px;
        }

        .cust-action-btn:hover {
            transform: scale(1.08);
            filter: brightness(1.15);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <form action="{{ route('web.customers') }}" method="GET" class="dash-search-container" style="flex: 1; max-width: 480px;">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" name="q" value="{{ $search }}" class="dash-search-input" placeholder="Buscar cliente por nombre, DNI, correo o teléfono...">
                    @if(!empty($perPage) && $perPage != 15)
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                    @endif
                </form>

                <div class="dash-top-actions">
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema">
                        <span id="themeToggleIcon">☀️</span>
                    </button>
                    <a href="{{ route('web.customers') }}" class="dash-icon-btn" title="Refrescar Lista">
                        <span>🔄</span>
                    </a>
                </div>
            </header>

            <div class="dash-container">
                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner" style="margin-bottom: 1.5rem;">
                    <div>
                        <span class="settings-tag">👥 GESTIÓN DE USUARIOS & COMPRADORES</span>
                        <h1 class="settings-page-title">Directorio de Clientes</h1>
                        <p class="settings-page-subtitle">Visualiza el historial completo de clientes, consulta sus eventos y boletos adquiridos, edita sus datos y gestiona sus accesos.</p>
                    </div>
                </div>

                <!-- CARDS DE MÉTRICAS RÁPIDAS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; backdrop-filter: blur(12px);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #3B82F6; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            👥
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Total Clientes Registrados</span>
                            <h3 id="statTotalCustomers" style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; margin: 0;">{{ number_format($stats['total_customers']) }}</h3>
                        </div>
                    </div>

                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; backdrop-filter: blur(12px);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(255, 85, 0, 0.15); border: 1px solid rgba(255, 85, 0, 0.3); color: #FF5500; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            🎟️
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Boletos Comprados (Global)</span>
                            <h3 id="statTotalTickets" style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; margin: 0;">{{ number_format($stats['total_tickets_bought']) }}</h3>
                        </div>
                    </div>

                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; backdrop-filter: blur(12px);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10B981; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            💰
                        </div>
                        <div>
                            <span style="font-size: 0.775rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Total Facturado (Global)</span>
                            <h3 id="statTotalRevenue" style="font-size: 1.6rem; font-weight: 900; color: #10B981; margin: 0;" data-value="{{ $stats['total_revenue'] }}">S/ {{ number_format($stats['total_revenue'], 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- BARRA DE BÚSQUEDA & FILTROS -->
                <div class="settings-card-box" style="padding: 1rem 1.25rem; margin-bottom: 1.5rem;">
                    <form action="{{ route('web.customers') }}" method="GET" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                        <div style="display: flex; flex: 1; min-width: 280px; max-width: 550px; position: relative;">
                            <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 1rem;">🔍</span>
                            <input type="text" name="q" value="{{ $search }}" id="customerSearchInput" placeholder="Buscar por nombre, DNI, correo o teléfono..." style="width: 100%; background: #0B0B12; border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; padding: 0.65rem 1rem 0.65rem 2.75rem; border-radius: 10px; font-size: 0.9rem;">
                            @if(!empty($search))
                                <a href="{{ route('web.customers', ['per_page' => $perPage]) }}" title="Limpiar búsqueda" style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); color: #94A3B8; text-decoration: none; font-size: 1.1rem; font-weight: 800;">✕</a>
                            @endif
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="font-size: 0.8rem; color: #94A3B8; font-weight: 600;">Mostrar:</span>
                                <select name="per_page" onchange="this.form.submit()" style="background: #0B0B12; border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; padding: 0.55rem 0.85rem; border-radius: 8px; font-size: 0.85rem; cursor: pointer;">
                                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 clientes</option>
                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30 clientes</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 clientes</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 clientes</option>
                                </select>
                            </div>

                            <button type="submit" style="background: linear-gradient(135deg, #FF5500, #FF7700); border: none; color: #FFF; font-weight: 800; padding: 0.6rem 1.25rem; border-radius: 9px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem;">
                                <span>Buscar</span>
                            </button>

                            @if(!empty($search))
                                <a href="{{ route('web.customers') }}" style="color: #94A3B8; font-size: 0.825rem; text-decoration: none; padding: 0.55rem 0.85rem; background: rgba(255,255,255,0.05); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                    ✕ Ver Todos
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- TABLA DE CLIENTES -->
                <div class="settings-card-box" style="padding: 0; overflow: hidden;">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="card-header-icon" style="background: rgba(0, 242, 254, 0.15); border: 1px solid rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan); width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">👥</div>
                            <div>
                                <h3 class="card-header-title" style="margin: 0; font-size: 1.15rem; font-weight: 900; color: #FFFFFF;">Directorio de Clientes Registrados</h3>
                                <p class="card-header-subtitle" style="margin: 0.2rem 0 0 0; font-size: 0.825rem; color: #94A3B8;">
                                    @if(!empty($search))
                                        Resultados para la búsqueda: <strong style="color: #FF5500;">"{{ $search }}"</strong>
                                    @else
                                        Listado general ordenado por registro más reciente
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            <span style="font-size: 0.8rem; color: #94A3B8; background: rgba(255,255,255,0.04); padding: 0.4rem 0.85rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                                Mostrando {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} de {{ $customers->total() }} clientes
                            </span>
                        </div>
                    </div>

                    <div class="dash-table-container" style="margin: 0;">
                        <table class="dash-table" id="customersTable" style="width: 100%;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.02); font-size: 0.775rem; color: #94A3B8; text-transform: uppercase;">
                                    <th style="padding: 0.9rem 1rem; width: 65px;">#</th>
                                    <th style="padding: 0.9rem 1rem;">Cliente & DNI</th>
                                    <th style="padding: 0.9rem 1rem;">Contacto (Email / Celular)</th>
                                    <th style="padding: 0.9rem 1rem; width: 170px;">Fecha de Creación</th>
                                    <th style="padding: 0.9rem 1rem; text-align: right; width: 190px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $index => $customer)
                                    <tr class="customer-row" id="customer-row-{{ $customer->id }}" data-name="{{ $customer->name }}" data-dni="{{ $customer->dni }}" data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}" data-status="{{ $customer->status ?? 'active' }}" data-tickets="{{ $customer->total_tickets ?? 0 }}" data-spent="{{ $customer->total_spent ?? 0 }}" style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;">
                                        <td style="padding: 0.9rem 1rem; color: #64748B; font-weight: 700; font-family: monospace;">
                                            {{ ($customers->firstItem() ?? 1) + $index }}
                                        </td>
                                        <td style="padding: 0.9rem 1rem;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div class="cust-avatar-circle" style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: #FFF; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong class="cust-name-label" style="color: #FFFFFF; font-size: 0.95rem; display: block;">{{ $customer->name }}</strong>
                                                    <span class="cust-dni-badge" style="background: rgba(255, 255, 255, 0.08); color: #94A3B8; font-size: 0.75rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 6px; display: inline-block; margin-top: 0.2rem;">
                                                        DNI: {{ $customer->dni ?: 'No especificado' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 0.9rem 1rem;">
                                            <div style="font-size: 0.85rem;">
                                                <span class="cust-email-label" style="color: #E2E8F0; display: block; font-weight: 500;">📧 {{ $customer->email }}</span>
                                                <span class="cust-phone-label" style="color: #94A3B8; font-size: 0.775rem; display: block; margin-top: 0.15rem;">📱 {{ $customer->phone ?: 'Sin teléfono' }}</span>
                                            </div>
                                        </td>
                                        <td style="padding: 0.9rem 1rem;">
                                            <span style="color: #94A3B8; font-size: 0.85rem; font-weight: 600; font-family: monospace;">
                                                {{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : 'Reciente' }}
                                            </span>
                                        </td>
                                        <td style="padding: 0.9rem 1rem; text-align: right;">
                                            <div style="display: inline-flex; align-items: center; gap: 0.45rem; justify-content: flex-end;">
                                                <!-- Botón Ver Detalles Completos del Cliente (Icono) -->
                                                <button type="button" class="btn btn-sm cust-action-btn" onclick="openCustomerDetails({{ $customer->id }})" title="Ver Detalles del Cliente, Eventos y Boletos" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); color: #60A5FA; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 1.05rem; padding: 0; transition: all 0.2s;">
                                                    👁️
                                                </button>

                                                <!-- Botón Editar Perfil (Icono) -->
                                                <button type="button" class="btn btn-sm cust-action-btn" onclick="openEditCustomerModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ addslashes($customer->dni ?? '') }}', '{{ addslashes($customer->email) }}', '{{ addslashes($customer->phone ?? '') }}', '{{ $customer->status ?? 'active' }}')" title="Editar Perfil del Cliente" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34D399; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; padding: 0; transition: all 0.2s;">
                                                    ✏️
                                                </button>

                                                <!-- Botón Resetear Contraseña (Icono) -->
                                                <button type="button" class="btn btn-sm cust-action-btn" onclick="openResetPasswordModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ $customer->email }}')" title="Resetear Contraseña" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #FBBF24; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; padding: 0; transition: all 0.2s;">
                                                    🔑
                                                </button>

                                                <!-- Botón Eliminar Cliente (Icono) -->
                                                <button type="button" class="btn btn-sm cust-action-btn" onclick="openDeleteCustomerModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ $customer->total_tickets ?? 0 }}, {{ $customer->total_spent ?? 0 }})" title="Eliminar Cliente" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; padding: 0; transition: all 0.2s;">
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyCustomersRow">
                                        <td colspan="5" style="text-align: center; padding: 3.5rem 1rem; color: #94A3B8;">
                                            <div style="width: 60px; height: 60px; border-radius: 20px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem auto; color: #64748B;">
                                                👥
                                            </div>
                                            <h4 style="color: #FFFFFF; font-size: 1.1rem; margin: 0 0 0.35rem 0; font-weight: 800;">No se encontraron clientes</h4>
                                            <p style="color: #94A3B8; font-size: 0.85rem; max-width: 460px; margin: 0 auto;">
                                                @if(!empty($search))
                                                    No hay clientes que coincidan con "{{ $search }}". Intenta con otro término o limpia el buscador.
                                                @else
                                                    Aún no se han registrado cuentas de clientes ni compras online en la plataforma.
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- FOOTER CON PAGINACIÓN VIVEGO -->
                    @if($customers->hasPages())
                        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                            <span style="font-size: 0.825rem; color: #94A3B8;">
                                Mostrando página <strong style="color: #FFFFFF;">{{ $customers->currentPage() }}</strong> de <strong style="color: #FFFFFF;">{{ $customers->lastPage() }}</strong> (Total: {{ $customers->total() }} clientes)
                            </span>

                            <div style="display: flex; gap: 0.35rem; align-items: center;">
                                {{-- Botón Anterior --}}
                                @if($customers->onFirstPage())
                                    <span class="dt-page-btn disabled">‹</span>
                                @else
                                    <a href="{{ $customers->previousPageUrl() }}" class="dt-page-btn" title="Página Anterior">‹</a>
                                @endif

                                {{-- Números de Página con Ventana Deslizante --}}
                                @php
                                    $cur = $customers->currentPage();
                                    $last = $customers->lastPage();
                                    $start = max(1, $cur - 2);
                                    $end = min($last, $cur + 2);
                                @endphp

                                @if($start > 1)
                                    <a href="{{ $customers->url(1) }}" class="dt-page-btn">1</a>
                                    @if($start > 2)
                                        <span class="dt-page-dots">...</span>
                                    @endif
                                @endif

                                @for($p = $start; $p <= $end; $p++)
                                    @if($p == $cur)
                                        <span class="dt-page-btn active">{{ $p }}</span>
                                    @else
                                        <a href="{{ $customers->url($p) }}" class="dt-page-btn">{{ $p }}</a>
                                    @endif
                                @endfor

                                @if($end < $last)
                                    @if($end < $last - 1)
                                        <span class="dt-page-dots">...</span>
                                    @endif
                                    <a href="{{ $customers->url($last) }}" class="dt-page-btn">{{ $last }}</a>
                                @endif

                                {{-- Botón Siguiente --}}
                                @if($customers->hasMorePages())
                                    <a href="{{ $customers->nextPageUrl() }}" class="dt-page-btn" title="Página Siguiente">›</a>
                                @else
                                    <span class="dt-page-btn disabled">›</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL DETALLES DEL CLIENTE (EVENTOS, CANTIDAD DE BOLETOS Y COMPRAS) -->
    <div class="modal-backdrop-custom" id="customerDetailsModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(10px); padding: 1rem;">
        <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 22px; width: 100%; max-width: 860px; max-height: 92vh; display: flex; flex-direction: column; box-shadow: 0 25px 60px -12px rgba(0,0,0,0.7); overflow: hidden;">
            <!-- HEADER MODAL -->
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: flex-start; background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div id="modalCustAvatar" style="width: 52px; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); color: #FFF; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                        C
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
                            <span style="background: rgba(59, 130, 246, 0.15); color: #60A5FA; font-size: 0.75rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.3);">
                                👤 DETALLE DEL CLIENTE
                            </span>
                            <span id="modalCustDniBadge" style="background: rgba(255, 255, 255, 0.08); color: #94A3B8; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 8px;">
                                DNI: ...
                            </span>
                        </div>
                        <h2 id="modalCustName" style="font-size: 1.45rem; font-weight: 900; color: #FFF; margin: 0;">Cargando...</h2>
                        <p id="modalCustContact" style="color: #94A3B8; font-size: 0.85rem; margin: 0.25rem 0 0 0;">...</p>
                    </div>
                </div>
                <button type="button" onclick="closeCustomerDetailsModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); color: #FFF; width: 36px; height: 36px; border-radius: 10px; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">✕</button>
            </div>

            <!-- CONTENEDOR SCROLLABLE DEL MODAL -->
            <div style="padding: 1.75rem 2rem; overflow-y: auto; flex: 1;">
                <!-- CARDS DE RESUMEN DEL CLIENTE -->
                <div id="modalCustSummaryCards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">
                    <div style="background: #1E293B; border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1rem;">
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; display: block;">🎟️ Total Boletos</span>
                        <strong id="modalSummaryTickets" style="font-size: 1.4rem; color: #FF5500; font-weight: 900;">0 boletos</strong>
                    </div>
                    <div style="background: #1E293B; border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1rem;">
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; display: block;">💰 Total Invertido</span>
                        <strong id="modalSummarySpent" style="font-size: 1.4rem; color: #10B981; font-weight: 900;">S/ 0.00</strong>
                    </div>
                    <div style="background: #1E293B; border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1rem;">
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; display: block;">🛒 Total Compras</span>
                        <strong id="modalSummaryOrders" style="font-size: 1.4rem; color: #60A5FA; font-weight: 900;">0 compras</strong>
                    </div>
                    <div style="background: #1E293B; border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1rem;">
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; display: block;">🎪 Eventos</span>
                        <strong id="modalSummaryEvents" style="font-size: 1.4rem; color: #FBBF24; font-weight: 900;">0 eventos</strong>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #FFFFFF; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <span>📋</span> Historial de Eventos & Compras Realizadas
                    </h3>
                </div>

                <!-- CONTENEDOR DE EVENTOS / COMPRAS -->
                <div id="modalCustSalesContainer">
                    <p style="color: #94A3B8; text-align: center; padding: 2.5rem 1rem;">Cargando información y boletos del cliente...</p>
                </div>
            </div>

            <!-- FOOTER MODAL -->
            <div style="padding: 1rem 2rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: flex-end; background: rgba(255,255,255,0.01);">
                <button type="button" onclick="closeCustomerDetailsModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: #E2E8F0; padding: 0.6rem 1.4rem; font-weight: 700; border-radius: 10px; cursor: pointer;">
                    Cerrar Detalle
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR PERFIL DE CLIENTE -->
    <div class="modal-backdrop-custom" id="editCustomerModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px); padding: 1rem;">
        <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; width: 100%; max-width: 520px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.85rem;">
                <div>
                    <span style="background: rgba(16, 185, 129, 0.15); color: #34D399; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);">
                        ACTUALIZAR PERFIL
                    </span>
                    <h3 style="font-size: 1.35rem; font-weight: 900; color: #FFF; margin: 0.4rem 0 0.1rem 0;">✏️ Editar Datos del Cliente</h3>
                </div>
                <button type="button" onclick="closeEditCustomerModal()" style="background: rgba(255,255,255,0.08); border: none; color: #FFF; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">✕</button>
            </div>

            <form id="formEditCustomer" onsubmit="submitEditCustomer(event)">
                <input type="hidden" id="editCustomerId">

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; color: #94A3B8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">
                            Nombre Completo: <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" id="editCustomerName" required style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; color: #94A3B8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">
                                DNI / Documento:
                            </label>
                            <input type="text" id="editCustomerDni" placeholder="8 dígitos" style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.95rem; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; color: #94A3B8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">
                                Teléfono / Celular:
                            </label>
                            <input type="text" id="editCustomerPhone" placeholder="999999999" style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.95rem; box-sizing: border-box;">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; color: #94A3B8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">
                            Correo Electrónico: <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="email" id="editCustomerEmail" required style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; color: #94A3B8; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">
                            Nueva Contraseña (dejar en blanco para conservar actual):
                        </label>
                        <input type="password" id="editCustomerPassword" placeholder="Opcional (mínimo 6 caracteres)" style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.95rem; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="closeEditCustomerModal()" style="background: rgba(255,255,255,0.08); border: none; color: #E2E8F0; padding: 0.7rem 1.25rem; font-weight: 700; border-radius: 10px; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitEditCustomer" style="background: linear-gradient(135deg, #10B981, #059669); border: none; color: #FFF; padding: 0.7rem 1.5rem; font-weight: 800; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                        <span>💾</span>
                        <span>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESETEAR CONTRASEÑA -->
    <div class="modal-backdrop-custom" id="resetPasswordModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px); padding: 1rem;">
        <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; width: 100%; max-width: 500px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6);">
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
                    <input type="text" id="customPasswordInput" placeholder="Dejar en blanco para autogenerar" style="width: 100%; background: #1E293B; border: 1px solid rgba(255,255,255,0.15); color: #FFF; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.9rem; box-sizing: border-box;">
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

    <!-- MODAL CONFIRMAR ELIMINACIÓN DE CLIENTE -->
    <div class="modal-backdrop-custom" id="deleteCustomerModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px); padding: 1rem;">
        <div style="background: #0F172A; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 20px; width: 100%; max-width: 500px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6); position: relative;">
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

    <!-- SCRIPTS JS PARA GESTIÓN DE CLIENTES Y MODALES -->
    <script>
        // Abrir modal de detalles completos del cliente
        function openCustomerDetails(id) {
            const modal = document.getElementById('customerDetailsModal');
            modal.style.display = 'flex';
            document.getElementById('modalCustSalesContainer').innerHTML = '<p style="color: #94A3B8; text-align: center; padding: 2.5rem 1rem;">Cargando información y boletos del cliente...</p>';

            fetch(`/admin/clientes/${id}/detalle`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.customer) {
                        const cust = data.customer;
                        const summary = data.summary || {};
                        const sales = data.sales || [];

                        // Header info
                        document.getElementById('modalCustName').textContent = cust.name;
                        document.getElementById('modalCustAvatar').textContent = cust.name ? cust.name.substring(0, 1).toUpperCase() : 'C';
                        document.getElementById('modalCustDniBadge').textContent = 'DNI: ' + (cust.dni || 'No especificado');
                        document.getElementById('modalCustContact').textContent = `📧 ${cust.email} | 📱 ${cust.phone || 'Sin teléfono'} | Registrado: ${cust.created_at ? new Date(cust.created_at).toLocaleDateString() : 'Reciente'}`;

                        // Summary metrics
                        document.getElementById('modalSummaryTickets').textContent = `${summary.total_tickets || 0} boletos`;
                        document.getElementById('modalSummarySpent').textContent = `S/ ${parseFloat(summary.total_spent || 0).toFixed(2)}`;
                        document.getElementById('modalSummaryOrders').textContent = `${summary.total_orders || 0} compras`;
                        document.getElementById('modalSummaryEvents').textContent = `${summary.total_events || 0} eventos`;

                        // List of Purchases & Events
                        if (sales.length === 0) {
                            document.getElementById('modalCustSalesContainer').innerHTML = `
                                <div style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.12); border-radius: 16px; padding: 3rem 1.5rem; text-align: center;">
                                    <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">🎟️</span>
                                    <h4 style="color: #FFFFFF; font-size: 1.1rem; margin: 0 0 0.35rem 0;">Sin Boletos ni Compras</h4>
                                    <p style="color: #94A3B8; font-size: 0.85rem; margin: 0;">Este cliente no ha registrado compras de entradas todavía.</p>
                                </div>
                            `;
                        } else {
                            let html = '<div style="display: flex; flex-direction: column; gap: 1.25rem;">';
                            sales.forEach((s, sIdx) => {
                                const eventTitle = s.event ? s.event.title : 'Evento ViveGo';
                                const eventDate = s.event && s.event.event_date ? new Date(s.event.event_date).toLocaleDateString() : 'Fecha por confirmar';
                                const eventLoc = s.event && s.event.location ? s.event.location : 'Ubicación asignada';
                                const purchaseDate = s.created_at ? new Date(s.created_at).toLocaleString() : 'Fecha N/D';
                                const unitPrice = parseFloat(s.unit_price || 0).toFixed(2);
                                const totalAmt = parseFloat(s.total_amount || 0).toFixed(2);

                                html += `
                                    <div style="background: #1E293B; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.35rem; transition: all 0.2s;">
                                        <!-- Header de la Compra -->
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.85rem; margin-bottom: 1rem;">
                                            <div>
                                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                                    <span style="background: rgba(255,85,0,0.15); color: #FF5500; font-weight: 900; font-family: monospace; font-size: 0.85rem; padding: 0.2rem 0.6rem; border-radius: 6px; border: 1px solid rgba(255,85,0,0.3);">
                                                        ${s.receipt_number || 'COMPROBANTE'}
                                                    </span>
                                                    <span style="color: #94A3B8; font-size: 0.785rem;">📅 ${purchaseDate}</span>
                                                </div>
                                                <h4 style="color: #FFF; font-size: 1.15rem; font-weight: 800; margin: 0.25rem 0 0.15rem 0;">🎪 ${eventTitle}</h4>
                                                <p style="color: #94A3B8; font-size: 0.8rem; margin: 0;">📍 ${eventLoc} | 🗓️ ${eventDate}</p>
                                            </div>

                                            <div style="text-align: right;">
                                                <span style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-weight: 900; font-size: 1.05rem; padding: 0.3rem 0.75rem; border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.3); display: inline-block;">
                                                    S/ ${totalAmt}
                                                </span>
                                                <span style="display: block; font-size: 0.75rem; color: #94A3B8; margin-top: 0.25rem; text-transform: uppercase;">
                                                    Método: <strong>${s.payment_method || 'Online'}</strong>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Resumen de Zona y Cantidad -->
                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; background: rgba(15, 23, 42, 0.6); padding: 0.85rem 1rem; border-radius: 12px; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.04);">
                                            <div>
                                                <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600;">ZONA COMPRADA:</span>
                                                <strong style="color: #60A5FA; font-size: 0.95rem;">${s.zone_name || 'General'}</strong>
                                            </div>
                                            <div>
                                                <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600;">CANTIDAD DE BOLETOS:</span>
                                                <span style="background: rgba(255,85,0,0.2); color: #FF7700; font-weight: 900; padding: 0.15rem 0.55rem; border-radius: 6px; font-size: 0.85rem;">
                                                    🎟️ ${s.quantity} entrada(s)
                                                </span>
                                            </div>
                                            <div>
                                                <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600;">PRECIO UNITARIO:</span>
                                                <strong style="color: #E2E8F0; font-size: 0.95rem;">S/ ${unitPrice} c/u</strong>
                                            </div>
                                        </div>
                                `;

                                // Desglose de boletos individuales si existen
                                if (s.event_tickets && s.event_tickets.length > 0) {
                                    html += `
                                        <div style="margin-bottom: 1rem;">
                                            <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                                Boletos Emitidos (${s.event_tickets.length}):
                                            </span>
                                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.5rem;">
                                    `;
                                    s.event_tickets.forEach(tk => {
                                        const isUsed = tk.is_used || tk.status === 'used';
                                        html += `
                                            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); padding: 0.55rem 0.75rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <span style="font-family: monospace; font-weight: 800; color: #FF5500; font-size: 0.85rem; display: block;">${tk.ticket_code || 'TK-0000'}</span>
                                                    <small style="color: #94A3B8; font-size: 0.75rem;">Boleto #${tk.ticket_number || ''}</small>
                                                </div>
                                                <span style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px; ${isUsed ? 'background: rgba(59,130,246,0.15); color: #60A5FA;' : 'background: rgba(16,185,129,0.15); color: #34D399;'}">
                                                    ${isUsed ? '✓ Usado' : '● Válido'}
                                                </span>
                                            </div>
                                        `;
                                    });
                                    html += `</div></div>`;
                                }

                                // Botón para ver comprobante
                                html += `
                                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 0.75rem;">
                                            <a href="/checkout/confirmacion/${s.id}" target="_blank" style="background: rgba(255,85,0,0.15); color: #FF5500; border: 1px solid rgba(255,85,0,0.3); padding: 0.45rem 0.95rem; border-radius: 8px; text-decoration: none; font-size: 0.8rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;">
                                                <span>🔍</span> Ver Voucher / Comprobante
                                            </a>
                                        </div>
                                    </div>
                                `;
                            });
                            html += '</div>';
                            document.getElementById('modalCustSalesContainer').innerHTML = html;
                        }
                    }
                })
                .catch(err => {
                    document.getElementById('modalCustSalesContainer').innerHTML = '<p style="color: #EF4444; text-align: center; padding: 2.5rem 1rem;">Error al cargar detalles del cliente.</p>';
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

        function copyNewPass() {
            const input = document.getElementById('txtGeneratedPass');
            input.select();
            document.execCommand('copy');
            showCustomerToast('Contraseña copiada al portapapeles.', 'success');
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
                    showCustomerToast('Contraseña restablecida con éxito.', 'success');
                } else {
                    alert(data.message || 'Error al resetear la contraseña.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Generar Nueva Contraseña';
                alert('Error al resetear la contraseña.');
            });
        }

        // Modal de Edición de Perfil de Cliente
        function openEditCustomerModal(id, name, dni, email, phone, status) {
            document.getElementById('editCustomerId').value = id;
            document.getElementById('editCustomerName').value = name || '';
            document.getElementById('editCustomerDni').value = dni && dni !== 'No especificado' ? dni : '';
            document.getElementById('editCustomerEmail').value = email || '';
            document.getElementById('editCustomerPhone').value = phone && phone !== 'Sin teléfono' ? phone : '';
            document.getElementById('editCustomerPassword').value = '';
            document.getElementById('editCustomerModal').style.display = 'flex';
        }

        function closeEditCustomerModal() {
            document.getElementById('editCustomerModal').style.display = 'none';
        }

        function submitEditCustomer(e) {
            e.preventDefault();
            const id = document.getElementById('editCustomerId').value;
            const name = document.getElementById('editCustomerName').value.trim();
            const dni = document.getElementById('editCustomerDni').value.trim();
            const email = document.getElementById('editCustomerEmail').value.trim();
            const phone = document.getElementById('editCustomerPhone').value.trim();
            const password = document.getElementById('editCustomerPassword').value;

            const btn = document.getElementById('btnSubmitEditCustomer');
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span><span>Guardando...</span>';

            fetch(`/admin/clientes/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    dni: dni,
                    email: email,
                    phone: phone,
                    password: password || null
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<span>💾</span><span>Guardar Cambios</span>';

                if (data.success) {
                    closeEditCustomerModal();

                    // Actualizar fila en la tabla en vivo
                    const row = document.getElementById(`customer-row-${id}`);
                    if (row) {
                        const nameLabel = row.querySelector('.cust-name-label');
                        if (nameLabel) nameLabel.textContent = data.customer.name;

                        const avatarCircle = row.querySelector('.cust-avatar-circle');
                        if (avatarCircle && data.customer.name) {
                            avatarCircle.textContent = data.customer.name.substring(0, 1).toUpperCase();
                        }

                        const dniBadge = row.querySelector('.cust-dni-badge');
                        if (dniBadge) {
                            dniBadge.textContent = 'DNI: ' + (data.customer.dni || 'No especificado');
                        }

                        const emailLabel = row.querySelector('.cust-email-label');
                        if (emailLabel) emailLabel.textContent = '📧 ' + data.customer.email;

                        const phoneLabel = row.querySelector('.cust-phone-label');
                        if (phoneLabel) phoneLabel.textContent = '📱 ' + (data.customer.phone || 'Sin teléfono');

                        // Actualizar data-attributes
                        row.dataset.name = data.customer.name;
                        row.dataset.dni = data.customer.dni || '';
                        row.dataset.email = data.customer.email;
                        row.dataset.phone = data.customer.phone || '';
                    }

                    showCustomerToast(data.message || 'Datos del cliente actualizados exitosamente.', 'success');
                } else {
                    const err = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Error al actualizar el cliente.');
                    alert(err);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<span>💾</span><span>Guardar Cambios</span>';
                alert('Ocurrió un error al intentar actualizar los datos del cliente.');
            });
        }

        // Modal de Eliminación de Cliente
        function openDeleteCustomerModal(id, name, tickets, spent) {
            const idInput = document.getElementById('deleteCustomerId');
            const ticketsInput = document.getElementById('deleteCustomerTickets');
            const spentInput = document.getElementById('deleteCustomerSpent');
            const nameEl = document.getElementById('deleteCustName');
            const modal = document.getElementById('deleteCustomerModal');

            if (idInput) idInput.value = id;
            if (ticketsInput) ticketsInput.value = tickets || 0;
            if (spentInput) spentInput.value = spent || 0;
            if (nameEl) nameEl.textContent = `"${name}"`;
            if (modal) modal.style.display = 'flex';
        }

        function closeDeleteCustomerModal() {
            const modal = document.getElementById('deleteCustomerModal');
            if (modal) modal.style.display = 'none';
        }

        function executeDeleteCustomer() {
            const id = document.getElementById('deleteCustomerId')?.value;
            if (!id) return;
            const btn = document.getElementById('btnConfirmDelete');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>⏳</span><span>Eliminando...</span>';
            }

            fetch(`/admin/clientes/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span>🗑️</span><span>Sí, Eliminar Permanentemente</span>';
                }

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
                        }, 400);
                    }

                    // Actualizar contadores de las tarjetas superiores
                    const statCust = document.getElementById('statTotalCustomers');
                    if (statCust) {
                        const current = parseInt(statCust.textContent.replace(/,/g, '')) || 0;
                        statCust.textContent = Math.max(0, current - 1).toLocaleString();
                    }

                    showCustomerToast(data.message || 'Cliente eliminado exitosamente.', 'success');
                } else {
                    alert(data.message || 'Error al eliminar el cliente.');
                }
            })
            .catch(err => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span>🗑️</span><span>Sí, Eliminar Permanentemente</span>';
                }
                alert('Ocurrió un error al intentar eliminar el cliente.');
            });
        }

        // Toast de notificación flotante
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

        // Cerrar modales con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCustomerDetailsModal();
                closeEditCustomerModal();
                closeResetPasswordModal();
                closeDeleteCustomerModal();
            }
        });
    </script>
@endsection
