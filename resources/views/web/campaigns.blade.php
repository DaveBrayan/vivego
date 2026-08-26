@extends('layouts.app')

@section('title', 'Gestión de Campañas Promocionales | Vive Go')

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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar campaña por nombre o badge...">
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

                @if($errors->any())
                    <div class="alert-custom" style="background: rgba(239, 68, 68, 0.15); border: 1.5px solid #EF4444; color: #FCA5A5; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.4rem;">⚠️</span>
                            <div>
                                <strong style="color: #FFFFFF; display: block; margin-bottom: 0.2rem;">Por favor corrige los siguientes errores:</strong>
                                <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.85rem;">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button class="alert-close-btn" onclick="this.parentElement.remove()" style="background: none; border: none; color: #FCA5A5; font-size: 1.2rem; cursor: pointer;">✕</button>
                    </div>
                @endif

                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">🔥 MARKETING & PROMOCIONES</span>
                        <h1 class="settings-page-title">Campañas Comerciales</h1>
                        <p class="settings-page-subtitle">Crea y programa promociones masivas como <b>Black Friday</b> o <b>Cyber Days</b> aplicables a toda la cartelera o eventos seleccionados.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" onclick="openCreateCampaignModal()" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; background: linear-gradient(135deg, #FF5500, #E04B00); box-shadow: 0 4px 20px rgba(255, 85, 0, 0.4); border: none; cursor: pointer;">
                            🔥 + Crear Nueva Campaña
                        </button>
                    </div>
                </div>

                <!-- CARDS DE RESUMEN KPI -->
                <div class="dash-kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #10B981;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            🚀
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Campañas en Vivo</span>
                            <h3 style="font-size: 1.65rem; font-weight: 900; color: #FFFFFF; margin: 0.1rem 0 0 0;">{{ $activeCount }}</h3>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #FF5500;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255, 85, 0, 0.15); border: 1px solid rgba(255, 85, 0, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            🏷️
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Total Registradas</span>
                            <h3 style="font-size: 1.65rem; font-weight: 900; color: #FFFFFF; margin: 0.1rem 0 0 0;">{{ $totalCount }}</h3>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #00F0FF;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            🎟️
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Eventos en Catálogo</span>
                            <h3 style="font-size: 1.65rem; font-weight: 900; color: #FFFFFF; margin: 0.1rem 0 0 0;">{{ $events->count() }}</h3>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE CAMPAÑAS -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">🔥</div>
                        <div>
                            <h3 class="card-header-title">Listado de Campañas Comerciales</h3>
                            <p class="card-header-subtitle">Control de promociones, vigencia, porcentajes de descuento y eventos vinculados</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="campaignsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Campaña & Badge Visual</th>
                                    <th>Descuento</th>
                                    <th>Vigencia (Inicio - Fin)</th>
                                    <th>Estado de Campaña</th>
                                    <th>Alcance / Eventos</th>
                                    <th>Activo</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $index => $camp)
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $isLive = $camp->isCurrentlyActive();
                                        $isUpcoming = $camp->is_active && $camp->start_at && $now->lt($camp->start_at);
                                        $isExpired = $camp->end_at && $now->gt($camp->end_at);
                                    @endphp
                                    <tr id="campaignRow_{{ $camp->id }}">
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $camp->id) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                                                <strong style="color: #FFFFFF; font-size: 0.95rem;">{{ $camp->name }}</strong>
                                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; background: {{ $camp->banner_color ?: '#FF5500' }}; color: #FFFFFF; width: fit-content; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                                    {{ $camp->badge_text ?: ('🔥 ' . strtoupper($camp->name)) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($camp->discount_type === 'percentage')
                                                <span class="dash-badge-custom badge-orange" style="font-size: 0.85rem; font-weight: 900;">
                                                    -{{ (float)$camp->discount_value }}% OFF
                                                </span>
                                            @else
                                                <span class="dash-badge-custom badge-green" style="font-size: 0.85rem; font-weight: 900;">
                                                    -S/ {{ number_format($camp->discount_value, 2) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-size: 0.825rem; line-height: 1.4;">
                                                <span style="color: #10B981; font-weight: 700;">🟢 {{ $camp->start_at->format('d/m/Y h:i A') }}</span><br>
                                                <span style="color: #EF4444; font-weight: 700;">🔴 {{ $camp->end_at->format('d/m/Y h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($isLive)
                                                <span class="dash-badge-custom badge-green" style="font-weight: 900; font-size: 0.75rem; animation: pulseGlow 1.5s infinite alternate;">
                                                    🟢 EN VIVO AHORA
                                                </span>
                                            @elseif($isUpcoming)
                                                <span class="dash-badge-custom" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 800; font-size: 0.75rem;">
                                                    ⏳ PROGRAMADA
                                                </span>
                                            @elseif($isExpired)
                                                <span class="dash-badge-custom" style="background: rgba(148, 163, 184, 0.1); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.2); font-weight: 700; font-size: 0.75rem;">
                                                    ⚫ FINALIZADA
                                                </span>
                                            @else
                                                <span class="dash-badge-custom" style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 700; font-size: 0.75rem;">
                                                    ⏸️ PAUSADA
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($camp->scope === 'all_events')
                                                @php $excCount = is_array($camp->excluded_event_ids) ? count($camp->excluded_event_ids) : 0; @endphp
                                                <div>
                                                    <span style="color: #00F0FF; font-weight: 800; font-size: 0.85rem;">🌐 Todos los Eventos</span>
                                                    @if($excCount > 0)
                                                        <small style="display: block; color: #F59E0B; font-weight: 700;">({{ $excCount }} excluidos)</small>
                                                    @endif
                                                </div>
                                            @else
                                                @php $incCount = is_array($camp->event_ids) ? count($camp->event_ids) : 0; @endphp
                                                <div>
                                                    <span style="color: #E2E8F0; font-weight: 800; font-size: 0.85rem;">🎯 {{ $incCount }} Evento(s) Seleccionado(s)</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; cursor: pointer;">
                                                <input type="checkbox" {{ $camp->is_active ? 'checked' : '' }} onchange="toggleCampaignActive({{ $camp->id }}, this)" style="opacity: 0; width: 0; height: 0;">
                                                <span style="position: absolute; cursor: pointer; inset: 0; background-color: {{ $camp->is_active ? '#10B981' : '#334155' }}; transition: .3s; border-radius: 24px; display: block;" class="toggle-slider-{{ $camp->id }}">
                                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: {{ $camp->is_active ? '22px' : '3px' }}; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; display: block;" class="toggle-knob-{{ $camp->id }}"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                                                <button type="button" class="btn-action-view" onclick="editCampaign({{ json_encode($camp) }})" title="Editar Campaña" style="background: rgba(0, 240, 255, 0.1); border: 1px solid rgba(0, 240, 255, 0.3); color: #00F0FF; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer;">
                                                    ✏️
                                                </button>
                                                <form action="{{ route('web.campaigns.destroy', $camp->id) }}" method="POST" class="delete-campaign-form" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-action-view btn-delete-campaign" title="Eliminar Campaña" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer;">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 3rem 1.5rem; color: #94A3B8;">
                                            <div style="font-size: 2.8rem; margin-bottom: 0.75rem;">🔥</div>
                                            <strong style="color: #FFFFFF; font-size: 1.1rem; display: block; margin-bottom: 0.35rem;">No hay campañas comerciales registradas</strong>
                                            <p style="margin: 0; font-size: 0.85rem; color: #64748B;">Crea tu primera campaña como <b>Black Friday</b> o <b>Cyber Days</b> para impulsar las ventas en tu plataforma.</p>
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

    <!-- MODAL DE CREACIÓN / EDICIÓN DE CAMPAÑA -->
    <div id="campaignModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(5, 5, 12, 0.88); backdrop-filter: blur(8px); justify-content: center; align-items: center; padding: 1.25rem;">
        <div style="background: #10101C; border: 1.5px solid rgba(255, 85, 0, 0.35); border-radius: 24px; width: 100%; max-width: 780px; max-height: 92vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 70px rgba(0,0,0,0.9), 0 0 45px rgba(255,85,0,0.15); animation: modalPop 0.25s ease;">
            
            <!-- Modal Header -->
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 85, 0, 0.15); border: 1px solid rgba(255, 85, 0, 0.35); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                        🔥
                    </div>
                    <div>
                        <h3 id="campaignModalTitle" style="font-size: 1.2rem; font-weight: 900; color: #FFFFFF; margin: 0;">
                            Crear Nueva Campaña Comercial
                        </h3>
                        <p style="font-size: 0.82rem; color: #94A3B8; margin: 0.15rem 0 0 0;">Configura los descuentos masivos, fechas y eventos participantes.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCampaignModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #94A3B8; font-size: 1.1rem; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease;">
                    ✕
                </button>
            </div>

            <!-- Formulario Campaña -->
            <form id="campaignForm" action="{{ route('web.campaigns.store') }}" method="POST" style="display: flex; flex-direction: column; overflow-y: auto; flex: 1;">
                @csrf
                <div id="campaignMethodField"></div>

                <div style="padding: 1.5rem 1.75rem; display: flex; flex-direction: column; gap: 1.25rem;">
                    
                    <!-- Fila 1: Nombre & Badge Text -->
                    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🏷️ Nombre de la Campaña <span style="color: #FF5500;">*</span>
                            </label>
                            <input type="text" name="name" id="campInputName" required placeholder="Ej: Black Friday 2026, Cyber Days" oninput="updateBadgePreview()" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🏷️ Texto del Badge Visual
                            </label>
                            <input type="text" name="badge_text" id="campInputBadge" placeholder="Ej: 🔥 BLACK FRIDAY 30% OFF" oninput="updateBadgePreview()" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Vista Previa & Color del Badge -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">Vista Previa en Cards y Checkout:</span>
                            <span id="campBadgePreview" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.85rem; border-radius: 8px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; background: #FF5500; color: #FFFFFF; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                                🔥 BLACK FRIDAY 2026
                            </span>
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">Color del Cintillo:</span>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <button type="button" onclick="setCampColor('#FF5500')" style="width: 28px; height: 28px; border-radius: 50%; background: #FF5500; border: 2px solid #FFFFFF; cursor: pointer;"></button>
                                <button type="button" onclick="setCampColor('#EF4444')" style="width: 28px; height: 28px; border-radius: 50%; background: #EF4444; border: 2px solid transparent; cursor: pointer;"></button>
                                <button type="button" onclick="setCampColor('#00F0FF')" style="width: 28px; height: 28px; border-radius: 50%; background: #00F0FF; border: 2px solid transparent; cursor: pointer;"></button>
                                <button type="button" onclick="setCampColor('#A855F7')" style="width: 28px; height: 28px; border-radius: 50%; background: #A855F7; border: 2px solid transparent; cursor: pointer;"></button>
                                <button type="button" onclick="setCampColor('#10B981')" style="width: 28px; height: 28px; border-radius: 50%; background: #10B981; border: 2px solid transparent; cursor: pointer;"></button>
                                <input type="hidden" name="banner_color" id="campInputColor" value="#FF5500">
                            </div>
                        </div>
                    </div>

                    <!-- Fila 2: Tipo de Descuento & Monto -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                💰 Tipo de Descuento <span style="color: #FF5500;">*</span>
                            </label>
                            <select name="discount_type" id="campInputDiscountType" required onchange="updateBadgePreview()" style="width: 100%; padding: 0.75rem 1rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                                <option value="percentage">Porcentaje de Descuento (%)</option>
                                <option value="fixed">Monto Fijo de Descuento (S/)</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🎯 Valor del Descuento <span style="color: #FF5500;">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" name="discount_value" id="campInputDiscountValue" required placeholder="Ej: 20 para 20% o 15.00 para S/ 15" oninput="updateBadgePreview()" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Fila 3: Fecha & Hora de Inicio / Cierre -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🟢 Apertura (Fecha y Hora) <span style="color: #FF5500;">*</span>
                            </label>
                            <input type="datetime-local" name="start_at" id="campInputStartAt" required style="width: 100%; padding: 0.75rem 1rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🔴 Cierre / Vencimiento (Fecha y Hora) <span style="color: #FF5500;">*</span>
                            </label>
                            <input type="datetime-local" name="end_at" id="campInputEndAt" required style="width: 100%; padding: 0.75rem 1rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Fila 4: Alcance de la Campaña (Todos vs Seleccionados) -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.25rem;">
                        <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.75rem;">
                            🎯 Alcance y Aplicación de la Campaña
                        </label>
                        
                        <div style="display: flex; gap: 1.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #FFFFFF; font-size: 0.9rem; font-weight: 700;">
                                <input type="radio" name="scope" value="all_events" id="scopeAllEvents" checked onchange="toggleScopeSections()" style="accent-color: #FF5500; width: 18px; height: 18px;">
                                <span>🌐 Aplicar a Todos los Eventos</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #FFFFFF; font-size: 0.9rem; font-weight: 700;">
                                <input type="radio" name="scope" value="selected_events" id="scopeSelectedEvents" onchange="toggleScopeSections()" style="accent-color: #FF5500; width: 18px; height: 18px;">
                                <span>🎯 Seleccionar Eventos Específicos</span>
                            </label>
                        </div>

                        <!-- Sección Exclusiones (Cuando scope == all_events) -->
                        <div id="sectionExclusions" style="margin-top: 0.75rem;">
                            <label style="font-size: 0.75rem; font-weight: 800; color: #F59E0B; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">
                                🚫 Exclusiones Opcionales (Eventos que NO tendrán este descuento):
                            </label>
                            <select name="excluded_event_ids[]" id="campExcludedEvents" multiple style="width: 100%; height: 110px; padding: 0.5rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                                @foreach($events as $ev)
                                    <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->event_date ?: 'Sin fecha' }})</option>
                                @endforeach
                            </select>
                            <small style="color: #94A3B8; font-size: 0.75rem; display: block; margin-top: 0.3rem;">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples eventos excluidos.</small>
                        </div>

                        <!-- Sección Eventos Seleccionados (Cuando scope == selected_events) -->
                        <div id="sectionSelectedEvents" style="display: none; margin-top: 0.75rem;">
                            <label style="font-size: 0.75rem; font-weight: 800; color: #10B981; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">
                                ✅ Seleccionar Eventos Participantes:
                            </label>
                            <select name="event_ids[]" id="campSelectedEvents" multiple style="width: 100%; height: 110px; padding: 0.5rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                                @foreach($events as $ev)
                                    <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->event_date ?: 'Sin fecha' }})</option>
                                @endforeach
                            </select>
                            <small style="color: #94A3B8; font-size: 0.75rem; display: block; margin-top: 0.3rem;">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar los eventos donde aplicará la campaña.</small>
                        </div>
                    </div>

                    <!-- Switch Activo -->
                    <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.85rem 1.25rem;">
                        <div>
                            <strong style="color: #FFFFFF; font-size: 0.9rem; display: block;">Habilitar Campaña</strong>
                            <small style="color: #94A3B8; font-size: 0.75rem;">Si está desactivada, los descuentos no se aplicarán aunque esté en fecha.</small>
                        </div>
                        <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; cursor: pointer;">
                            <input type="checkbox" name="is_active" id="campInputIsActive" value="1" checked style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; inset: 0; background-color: #10B981; transition: .3s; border-radius: 24px; display: block;" id="campModalToggleBg">
                                <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 22px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; display: block;" id="campModalToggleKnob"></span>
                            </span>
                        </label>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: flex-end; gap: 0.75rem; background: rgba(255,255,255,0.02);">
                    <button type="button" onclick="closeCampaignModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; padding: 0.65rem 1.25rem; border-radius: 12px; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="campSubmitBtn" style="background: linear-gradient(135deg, #FF5500, #E04B00); border: none; color: #FFFFFF; font-weight: 900; font-size: 0.9rem; padding: 0.65rem 1.5rem; border-radius: 12px; cursor: pointer; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                        🔥 Guardar Campaña
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const storeCampaignUrl = "{{ route('web.campaigns.store') }}";
        const csrfToken = "{{ csrf_token() }}";

        function openCreateCampaignModal() {
            document.getElementById('campaignModalTitle').textContent = '🔥 Crear Nueva Campaña Comercial';
            document.getElementById('campaignForm').action = storeCampaignUrl;
            document.getElementById('campaignMethodField').innerHTML = '';
            document.getElementById('campSubmitBtn').innerHTML = '🔥 Crear Campaña';

            // Reset campos
            document.getElementById('campInputName').value = '';
            document.getElementById('campInputBadge').value = '';
            document.getElementById('campInputColor').value = '#FF5500';
            document.getElementById('campInputDiscountType').value = 'percentage';
            document.getElementById('campInputDiscountValue').value = '';

            // Fechas por defecto: Hoy hasta +7 días
            const now = new Date();
            const startStr = now.toISOString().slice(0, 16);
            const endDate = new Date(now.getTime() + (7 * 24 * 60 * 60 * 1000));
            const endStr = endDate.toISOString().slice(0, 16);

            document.getElementById('campInputStartAt').value = startStr;
            document.getElementById('campInputEndAt').value = endStr;

            document.getElementById('scopeAllEvents').checked = true;
            document.getElementById('campInputIsActive').checked = true;

            // Reset selects
            const exclSelect = document.getElementById('campExcludedEvents');
            for (let opt of exclSelect.options) opt.selected = false;
            const selSelect = document.getElementById('campSelectedEvents');
            for (let opt of selSelect.options) opt.selected = false;

            setCampColor('#FF5500');
            toggleScopeSections();
            updateBadgePreview();

            document.getElementById('campaignModal').style.display = 'flex';
        }

        function editCampaign(camp) {
            document.getElementById('campaignModalTitle').textContent = '✏️ Editar Campaña: ' + camp.name;
            document.getElementById('campaignForm').action = `/admin/campanas/${camp.id}`;
            document.getElementById('campaignMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('campSubmitBtn').innerHTML = '💾 Actualizar Campaña';

            document.getElementById('campInputName').value = camp.name || '';
            document.getElementById('campInputBadge').value = camp.badge_text || '';
            document.getElementById('campInputColor').value = camp.banner_color || '#FF5500';
            document.getElementById('campInputDiscountType').value = camp.discount_type || 'percentage';
            document.getElementById('campInputDiscountValue').value = camp.discount_value || '';

            if (camp.start_at) {
                const s = new Date(camp.start_at);
                s.setMinutes(s.getMinutes() - s.getTimezoneOffset());
                document.getElementById('campInputStartAt').value = s.toISOString().slice(0, 16);
            }

            if (camp.end_at) {
                const e = new Date(camp.end_at);
                e.setMinutes(e.getMinutes() - e.getTimezoneOffset());
                document.getElementById('campInputEndAt').value = e.toISOString().slice(0, 16);
            }

            document.getElementById('campInputIsActive').checked = !!camp.is_active;

            if (camp.scope === 'selected_events') {
                document.getElementById('scopeSelectedEvents').checked = true;
            } else {
                document.getElementById('scopeAllEvents').checked = true;
            }

            // Exclusiones
            const exclSelect = document.getElementById('campExcludedEvents');
            const excludedIds = Array.isArray(camp.excluded_event_ids) ? camp.excluded_event_ids.map(Number) : [];
            for (let opt of exclSelect.options) {
                opt.selected = excludedIds.includes(Number(opt.value));
            }

            // Seleccionados
            const selSelect = document.getElementById('campSelectedEvents');
            const selectedIds = Array.isArray(camp.event_ids) ? camp.event_ids.map(Number) : [];
            for (let opt of selSelect.options) {
                opt.selected = selectedIds.includes(Number(opt.value));
            }

            setCampColor(camp.banner_color || '#FF5500');
            toggleScopeSections();
            updateBadgePreview();

            document.getElementById('campaignModal').style.display = 'flex';
        }

        function closeCampaignModal() {
            document.getElementById('campaignModal').style.display = 'none';
        }

        function setCampColor(color) {
            document.getElementById('campInputColor').value = color;
            const preview = document.getElementById('campBadgePreview');
            if (preview) {
                preview.style.background = color;
                preview.style.boxShadow = `0 4px 15px ${color}66`;
            }
        }

        function updateBadgePreview() {
            const nameVal = document.getElementById('campInputName').value.trim();
            const customBadge = document.getElementById('campInputBadge').value.trim();
            const preview = document.getElementById('campBadgePreview');
            const val = document.getElementById('campInputDiscountValue').value;
            const type = document.getElementById('campInputDiscountType').value;

            if (customBadge) {
                preview.textContent = customBadge;
            } else if (nameVal) {
                const discText = val ? (type === 'percentage' ? ` -${val}%` : ` -S/ ${val}`) : '';
                preview.textContent = `🔥 ${nameVal.toUpperCase()}${discText}`;
            } else {
                preview.textContent = '🔥 BLACK FRIDAY 2026';
            }
        }

        function toggleScopeSections() {
            const isAll = document.getElementById('scopeAllEvents').checked;
            document.getElementById('sectionExclusions').style.display = isAll ? 'block' : 'none';
            document.getElementById('sectionSelectedEvents').style.display = isAll ? 'none' : 'block';
        }

        function toggleCampaignActive(id, checkbox) {
            fetch(`/admin/campanas/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const slider = document.querySelector(`.toggle-slider-${id}`);
                    const knob = document.querySelector(`.toggle-knob-${id}`);
                    if (slider) slider.style.backgroundColor = d.is_active ? '#10B981' : '#334155';
                    if (knob) knob.style.left = d.is_active ? '22px' : '3px';

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: d.message || 'Estado actualizado',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            })
            .catch(err => {
                checkbox.checked = !checkbox.checked;
            });
        }

        // SweetAlert2 para eliminar campaña
        document.querySelectorAll('.btn-delete-campaign').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: '¿Eliminar esta Campaña?',
                    text: 'Los descuentos dejarán de aplicarse inmediatamente a los eventos.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '🗑️ Sí, Eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#475569',
                    background: '#14141E',
                    color: '#FFFFFF'
                }).then((r) => {
                    if (r.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Buscador en tabla
        document.getElementById('tableFilterInput')?.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#campaignsTable tbody tr');
            rows.forEach(r => {
                const text = r.textContent.toLowerCase();
                r.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
@endpush
