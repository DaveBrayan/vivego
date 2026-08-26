@extends('layouts.app')

@section('title', 'Gestión de Cupones de Descuento | Vive Go')

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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar cupón por código o descripción...">
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
                        <span class="settings-tag">🎟️ CÓDIGOS PROMOCIONALES</span>
                        <h1 class="settings-page-title">Cupones de Descuento</h1>
                        <p class="settings-page-subtitle">Genera códigos promocionales con límite de usos, montos mínimos y vigencia horaria para el checkout de entradas.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" onclick="openCreateCouponModal()" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; box-shadow: 0 4px 20px rgba(0, 240, 255, 0.4); border: none; cursor: pointer;">
                            🎟️ + Crear Nuevo Cupón
                        </button>
                    </div>
                </div>

                <!-- CARDS DE RESUMEN KPI -->
                <div class="dash-kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #00F0FF;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            🎟️
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Cupones Activos</span>
                            <h3 style="font-size: 1.65rem; font-weight: 900; color: #FFFFFF; margin: 0.1rem 0 0 0;">{{ $activeCoupons }}</h3>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #10B981;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            📈
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Usos Canjeados</span>
                            <h3 style="font-size: 1.65rem; font-weight: 900; color: #FFFFFF; margin: 0.1rem 0 0 0;">{{ $totalUses }}</h3>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #FF5500;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255, 85, 0, 0.15); border: 1px solid rgba(255, 85, 0, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            🎁
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 800; color: #94A3B8; text-transform: uppercase;">Total Cupones</span>
                            <h3 style="font-size: 1.65rem; font-weight: 900; color: #FFFFFF; margin: 0.1rem 0 0 0;">{{ $coupons->count() }}</h3>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE CUPONES -->
                <div class="settings-card-box">
                    <div class="settings-card-header">
                        <div class="card-header-icon" style="background: rgba(0, 240, 255, 0.12); border-color: rgba(0, 240, 255, 0.3); color: #00F0FF;">🎟️</div>
                        <div>
                            <h3 class="card-header-title">Listado de Códigos Promocionales</h3>
                            <p class="card-header-subtitle">Administra los cupones activos, límites de canje y eventos autorizados</p>
                        </div>
                    </div>

                    <div class="dash-table-container">
                        <table class="dash-table" id="couponsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código de Cupón</th>
                                    <th>Descuento</th>
                                    <th>Canjes / Límite</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th>Alcance / Eventos</th>
                                    <th>Activo</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $index => $cup)
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $isExceeded = $cup->usage_limit !== null && $cup->used_count >= $cup->usage_limit;
                                        $isExpired = $cup->end_at && $now->gt($cup->end_at);
                                        $isUpcoming = $cup->start_at && $now->lt($cup->start_at);
                                        $isValidNow = $cup->is_active && !$isExceeded && !$isExpired && !$isUpcoming;
                                    @endphp
                                    <tr id="couponRow_{{ $cup->id }}">
                                        <td>
                                            <span style="font-weight: 800; color: #94A3B8;">#{{ sprintf('%02d', $cup->id) }}</span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(0, 240, 255, 0.1); border: 1.5px dashed rgba(0, 240, 255, 0.5); padding: 0.35rem 0.75rem; border-radius: 8px;">
                                                    <span style="font-family: monospace; font-weight: 900; font-size: 0.95rem; color: #00F0FF; letter-spacing: 1px;">{{ $cup->code }}</span>
                                                    <button type="button" onclick="copyCouponCode('{{ $cup->code }}')" title="Copiar Código" style="background: none; border: none; color: #94A3B8; cursor: pointer; padding: 0; font-size: 0.85rem;">
                                                        📋
                                                    </button>
                                                </div>
                                            </div>
                                            @if($cup->description)
                                                <small style="display: block; color: #94A3B8; margin-top: 0.25rem; font-size: 0.75rem;">{{ $cup->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cup->discount_type === 'percentage')
                                                <span class="dash-badge-custom badge-orange" style="font-size: 0.85rem; font-weight: 900;">
                                                    -{{ (float)$cup->discount_value }}%
                                                </span>
                                            @else
                                                <span class="dash-badge-custom badge-green" style="font-size: 0.85rem; font-weight: 900;">
                                                    -S/ {{ number_format($cup->discount_value, 2) }}
                                                </span>
                                            @endif
                                            @if($cup->min_purchase_amount > 0)
                                                <small style="display: block; color: #64748B; font-size: 0.7rem; margin-top: 0.2rem;">Mín. S/ {{ number_format($cup->min_purchase_amount, 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong style="color: #FFFFFF; font-size: 0.9rem;">{{ $cup->used_count }}</strong>
                                                <span style="color: #94A3B8; font-size: 0.8rem;">/ {{ $cup->usage_limit !== null ? $cup->usage_limit : '∞ Ilimitado' }}</span>
                                            </div>
                                            @if($cup->usage_limit)
                                                @php $pct = min(100, round(($cup->used_count / $cup->usage_limit) * 100)); @endphp
                                                <div style="width: 80px; height: 5px; background: rgba(255,255,255,0.1); border-radius: 4px; margin-top: 0.3rem; overflow: hidden;">
                                                    <div style="width: {{ $pct }}%; height: 100%; background: {{ $pct >= 100 ? '#EF4444' : '#10B981' }}; border-radius: 4px;"></div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-size: 0.825rem; line-height: 1.4;">
                                                <span style="color: #10B981; font-weight: 700;">🟢 {{ $cup->start_at->format('d/m/Y h:i A') }}</span><br>
                                                <span style="color: #EF4444; font-weight: 700;">🔴 {{ $cup->end_at->format('d/m/Y h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($isValidNow)
                                                <span class="dash-badge-custom badge-green" style="font-weight: 900; font-size: 0.75rem;">
                                                    🟢 VIGENTE
                                                </span>
                                            @elseif($isExceeded)
                                                <span class="dash-badge-custom" style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 800; font-size: 0.75rem;">
                                                    🚫 AGOTADO
                                                </span>
                                            @elseif($isUpcoming)
                                                <span class="dash-badge-custom" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3); font-weight: 800; font-size: 0.75rem;">
                                                    ⏳ PRÓXIMO
                                                </span>
                                            @elseif($isExpired)
                                                <span class="dash-badge-custom" style="background: rgba(148, 163, 184, 0.1); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.2); font-weight: 700; font-size: 0.75rem;">
                                                    ⚫ EXPIRADO
                                                </span>
                                            @else
                                                <span class="dash-badge-custom" style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: 700; font-size: 0.75rem;">
                                                    ⏸️ PAUSADO
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cup->scope === 'all_events')
                                                @php $excCount = is_array($cup->excluded_event_ids) ? count($cup->excluded_event_ids) : 0; @endphp
                                                <div>
                                                    <span style="color: #00F0FF; font-weight: 800; font-size: 0.85rem;">🌐 Todos los Eventos</span>
                                                    @if($excCount > 0)
                                                        <small style="display: block; color: #F59E0B; font-weight: 700;">({{ $excCount }} excluidos)</small>
                                                    @endif
                                                </div>
                                            @else
                                                @php $incCount = is_array($cup->event_ids) ? count($cup->event_ids) : 0; @endphp
                                                <div>
                                                    <span style="color: #E2E8F0; font-weight: 800; font-size: 0.85rem;">🎯 {{ $incCount }} Evento(s)</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; cursor: pointer;">
                                                <input type="checkbox" {{ $cup->is_active ? 'checked' : '' }} onchange="toggleCouponActive({{ $cup->id }}, this)" style="opacity: 0; width: 0; height: 0;">
                                                <span style="position: absolute; cursor: pointer; inset: 0; background-color: {{ $cup->is_active ? '#10B981' : '#334155' }}; transition: .3s; border-radius: 24px; display: block;" class="toggle-slider-{{ $cup->id }}">
                                                    <span style="position: absolute; content: ''; height: 18px; width: 18px; left: {{ $cup->is_active ? '22px' : '3px' }}; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; display: block;" class="toggle-knob-{{ $cup->id }}"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem;">
                                                <button type="button" class="btn-action-view" onclick="editCoupon({{ json_encode($cup) }})" title="Editar Cupón" style="background: rgba(0, 240, 255, 0.1); border: 1px solid rgba(0, 240, 255, 0.3); color: #00F0FF; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer;">
                                                    ✏️
                                                </button>
                                                <form action="{{ route('web.coupons.destroy', $cup->id) }}" method="POST" class="delete-coupon-form" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-action-view btn-delete-coupon" title="Eliminar Cupón" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer;">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 3rem 1.5rem; color: #94A3B8;">
                                            <div style="font-size: 2.8rem; margin-bottom: 0.75rem;">🎟️</div>
                                            <strong style="color: #FFFFFF; font-size: 1.1rem; display: block; margin-bottom: 0.35rem;">No hay cupones de descuento creados</strong>
                                            <p style="margin: 0; font-size: 0.85rem; color: #64748B;">Crea tu primer código promocional como <b>VIVEGOVIP</b> para ofrecer descuentos en el checkout.</p>
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

    <!-- MODAL DE CREACIÓN / EDICIÓN DE CUPÓN -->
    <div id="couponModal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(5, 5, 12, 0.88); backdrop-filter: blur(8px); justify-content: center; align-items: center; padding: 1.25rem;">
        <div style="background: #10101C; border: 1.5px solid rgba(0, 240, 255, 0.35); border-radius: 24px; width: 100%; max-width: 780px; max-height: 92vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 70px rgba(0,0,0,0.9), 0 0 45px rgba(0,240,255,0.15); animation: modalPop 0.25s ease;">
            
            <!-- Modal Header -->
            <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.35); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                        🎟️
                    </div>
                    <div>
                        <h3 id="couponModalTitle" style="font-size: 1.2rem; font-weight: 900; color: #FFFFFF; margin: 0;">
                            Crear Nuevo Cupón de Descuento
                        </h3>
                        <p style="font-size: 0.82rem; color: #94A3B8; margin: 0.15rem 0 0 0;">Define el código de canje, descuento, vigencia y límites de uso.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCouponModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #94A3B8; font-size: 1.1rem; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease;">
                    ✕
                </button>
            </div>

            <!-- Formulario Cupón -->
            <form id="couponForm" action="{{ route('web.coupons.store') }}" method="POST" style="display: flex; flex-direction: column; overflow-y: auto; flex: 1;">
                @csrf
                <div id="couponMethodField"></div>

                <div style="padding: 1.5rem 1.75rem; display: flex; flex-direction: column; gap: 1.25rem;">
                    
                    <!-- Fila 1: Código & Generador Rápido -->
                    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1rem; align-items: flex-end;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🔑 Código del Cupón <span style="color: #00F0FF;">*</span>
                            </label>
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="text" name="code" id="couponInputCode" required placeholder="Ej: VIVEGOVIP, PROMO20" oninput="this.value = this.value.toUpperCase().replace(/\s+/g, '')" style="flex: 1; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #00F0FF; font-family: monospace; font-weight: 900; font-size: 1.05rem; outline: none; box-sizing: border-box; text-transform: uppercase; letter-spacing: 1px;">
                                <button type="button" onclick="generateRandomCouponCode()" style="background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.35); color: #00F0FF; font-weight: 800; font-size: 0.8rem; padding: 0 0.85rem; border-radius: 10px; cursor: pointer; white-space: nowrap;">
                                    ⚡ Generar
                                </button>
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                📝 Descripción / Notas Internas
                            </label>
                            <input type="text" name="description" id="couponInputDescription" placeholder="Ej: Promo Embajadores, Alianza Radio" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Fila 2: Tipo de Descuento & Monto -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                💰 Tipo de Descuento <span style="color: #00F0FF;">*</span>
                            </label>
                            <select name="discount_type" id="couponInputDiscountType" required style="width: 100%; padding: 0.75rem 1rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                                <option value="percentage">Porcentaje de Descuento (%)</option>
                                <option value="fixed">Monto Fijo de Descuento (S/)</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🎯 Valor del Descuento <span style="color: #00F0FF;">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.01" name="discount_value" id="couponInputDiscountValue" required placeholder="Ej: 15 para 15% o 30.00 para S/ 30" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Fila 3: Límites de Uso & Monto Mínimo -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🔢 Límite de Usos Totales (Opcional)
                            </label>
                            <input type="number" min="1" name="usage_limit" id="couponInputUsageLimit" placeholder="Ej: 50 (dejar vacío para ilimitado)" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                            <small style="color: #94A3B8; font-size: 0.725rem; display: block; margin-top: 0.2rem;">Cantidad máxima de compras que pueden canjear este cupón.</small>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                💵 Monto Mínimo de Compra (S/)
                            </label>
                            <input type="number" step="0.01" min="0" name="min_purchase_amount" id="couponInputMinPurchase" placeholder="Ej: 100.00 (0 para sin mínimo)" style="width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                            <small style="color: #94A3B8; font-size: 0.725rem; display: block; margin-top: 0.2rem;">Subtotal mínimo del carrito para que el cupón sea aceptado.</small>
                        </div>
                    </div>

                    <!-- Fila 4: Fecha & Hora de Inicio / Cierre -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🟢 Inicio de Vigencia <span style="color: #00F0FF;">*</span>
                            </label>
                            <input type="datetime-local" name="start_at" id="couponInputStartAt" required style="width: 100%; padding: 0.75rem 1rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                                🔴 Expiración (Fecha y Hora) <span style="color: #00F0FF;">*</span>
                            </label>
                            <input type="datetime-local" name="end_at" id="couponInputEndAt" required style="width: 100%; padding: 0.75rem 1rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Fila 5: Alcance del Cupón (Todos vs Seleccionados) -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.25rem;">
                        <label style="font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; display: block; margin-bottom: 0.75rem;">
                            🎯 Eventos Autorizados para el Cupón
                        </label>
                        
                        <div style="display: flex; gap: 1.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #FFFFFF; font-size: 0.9rem; font-weight: 700;">
                                <input type="radio" name="scope" value="all_events" id="couponScopeAllEvents" checked onchange="toggleCouponScopeSections()" style="accent-color: #00F0FF; width: 18px; height: 18px;">
                                <span>🌐 Válido para Todos los Eventos</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #FFFFFF; font-size: 0.9rem; font-weight: 700;">
                                <input type="radio" name="scope" value="selected_events" id="couponScopeSelectedEvents" onchange="toggleCouponScopeSections()" style="accent-color: #00F0FF; width: 18px; height: 18px;">
                                <span>🎯 Válido solo en Eventos Específicos</span>
                            </label>
                        </div>

                        <!-- Sección Exclusiones -->
                        <div id="couponSectionExclusions" style="margin-top: 0.75rem;">
                            <label style="font-size: 0.75rem; font-weight: 800; color: #F59E0B; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">
                                🚫 Exclusiones Opcionales (Eventos donde NO se aceptará este cupón):
                            </label>
                            <select name="excluded_event_ids[]" id="couponExcludedEvents" multiple style="width: 100%; height: 110px; padding: 0.5rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                                @foreach($events as $ev)
                                    <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->event_date ?: 'Sin fecha' }})</option>
                                @endforeach
                            </select>
                            <small style="color: #94A3B8; font-size: 0.75rem; display: block; margin-top: 0.3rem;">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar múltiples eventos excluidos.</small>
                        </div>

                        <!-- Sección Eventos Seleccionados -->
                        <div id="couponSectionSelectedEvents" style="display: none; margin-top: 0.75rem;">
                            <label style="font-size: 0.75rem; font-weight: 800; color: #10B981; text-transform: uppercase; display: block; margin-bottom: 0.35rem;">
                                ✅ Seleccionar Eventos Permitidos:
                            </label>
                            <select name="event_ids[]" id="couponSelectedEvents" multiple style="width: 100%; height: 110px; padding: 0.5rem; background: #181828; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; color: #FFFFFF; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                                @foreach($events as $ev)
                                    <option value="{{ $ev->id }}">{{ $ev->title }} ({{ $ev->event_date ?: 'Sin fecha' }})</option>
                                @endforeach
                            </select>
                            <small style="color: #94A3B8; font-size: 0.75rem; display: block; margin-top: 0.3rem;">Mantén presionado Ctrl (o Cmd en Mac) para seleccionar los eventos específicos.</small>
                        </div>
                    </div>

                    <!-- Switch Activo -->
                    <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.85rem 1.25rem;">
                        <div>
                            <strong style="color: #FFFFFF; font-size: 0.9rem; display: block;">Habilitar Cupón</strong>
                            <small style="color: #94A3B8; font-size: 0.75rem;">Si está inactivo, el checkout rechazará el código aunque esté vigente.</small>
                        </div>
                        <label style="position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; cursor: pointer;">
                            <input type="checkbox" name="is_active" id="couponInputIsActive" value="1" checked style="opacity: 0; width: 0; height: 0;">
                            <span style="position: absolute; cursor: pointer; inset: 0; background-color: #10B981; transition: .3s; border-radius: 24px; display: block;">
                                <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 22px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; display: block;"></span>
                            </span>
                        </label>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div style="padding: 1.25rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: flex-end; gap: 0.75rem; background: rgba(255,255,255,0.02);">
                    <button type="button" onclick="closeCouponModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; padding: 0.65rem 1.25rem; border-radius: 12px; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="couponSubmitBtn" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; border: none; font-weight: 900; font-size: 0.9rem; padding: 0.65rem 1.5rem; border-radius: 12px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,240,255,0.4);">
                        🎟️ Guardar Cupón
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const storeCouponUrl = "{{ route('web.coupons.store') }}";
        const csrfToken = "{{ csrf_token() }}";

        function openCreateCouponModal() {
            document.getElementById('couponModalTitle').textContent = '🎟️ Crear Nuevo Cupón de Descuento';
            document.getElementById('couponForm').action = storeCouponUrl;
            document.getElementById('couponMethodField').innerHTML = '';
            document.getElementById('couponSubmitBtn').innerHTML = '🎟️ Crear Cupón';

            // Reset campos
            document.getElementById('couponInputCode').value = '';
            document.getElementById('couponInputDescription').value = '';
            document.getElementById('couponInputDiscountType').value = 'percentage';
            document.getElementById('couponInputDiscountValue').value = '';
            document.getElementById('couponInputUsageLimit').value = '';
            document.getElementById('couponInputMinPurchase').value = '';

            // Fechas por defecto: Hoy hasta +30 días
            const now = new Date();
            const startStr = now.toISOString().slice(0, 16);
            const endDate = new Date(now.getTime() + (30 * 24 * 60 * 60 * 1000));
            const endStr = endDate.toISOString().slice(0, 16);

            document.getElementById('couponInputStartAt').value = startStr;
            document.getElementById('couponInputEndAt').value = endStr;

            document.getElementById('couponScopeAllEvents').checked = true;
            document.getElementById('couponInputIsActive').checked = true;

            const exclSelect = document.getElementById('couponExcludedEvents');
            for (let opt of exclSelect.options) opt.selected = false;
            const selSelect = document.getElementById('couponSelectedEvents');
            for (let opt of selSelect.options) opt.selected = false;

            toggleCouponScopeSections();

            document.getElementById('couponModal').style.display = 'flex';
        }

        function editCoupon(cup) {
            document.getElementById('couponModalTitle').textContent = '✏️ Editar Cupón: ' + cup.code;
            document.getElementById('couponForm').action = `/admin/cupones/${cup.id}`;
            document.getElementById('couponMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('couponSubmitBtn').innerHTML = '💾 Actualizar Cupón';

            document.getElementById('couponInputCode').value = cup.code || '';
            document.getElementById('couponInputDescription').value = cup.description || '';
            document.getElementById('couponInputDiscountType').value = cup.discount_type || 'percentage';
            document.getElementById('couponInputDiscountValue').value = cup.discount_value || '';
            document.getElementById('couponInputUsageLimit').value = cup.usage_limit || '';
            document.getElementById('couponInputMinPurchase').value = (cup.min_purchase_amount > 0) ? cup.min_purchase_amount : '';

            if (cup.start_at) {
                const s = new Date(cup.start_at);
                s.setMinutes(s.getMinutes() - s.getTimezoneOffset());
                document.getElementById('couponInputStartAt').value = s.toISOString().slice(0, 16);
            }

            if (cup.end_at) {
                const e = new Date(cup.end_at);
                e.setMinutes(e.getMinutes() - e.getTimezoneOffset());
                document.getElementById('couponInputEndAt').value = e.toISOString().slice(0, 16);
            }

            document.getElementById('couponInputIsActive').checked = !!cup.is_active;

            if (cup.scope === 'selected_events') {
                document.getElementById('couponScopeSelectedEvents').checked = true;
            } else {
                document.getElementById('couponScopeAllEvents').checked = true;
            }

            // Exclusiones
            const exclSelect = document.getElementById('couponExcludedEvents');
            const excludedIds = Array.isArray(cup.excluded_event_ids) ? cup.excluded_event_ids.map(Number) : [];
            for (let opt of exclSelect.options) {
                opt.selected = excludedIds.includes(Number(opt.value));
            }

            // Seleccionados
            const selSelect = document.getElementById('couponSelectedEvents');
            const selectedIds = Array.isArray(cup.event_ids) ? cup.event_ids.map(Number) : [];
            for (let opt of selSelect.options) {
                opt.selected = selectedIds.includes(Number(opt.value));
            }

            toggleCouponScopeSections();

            document.getElementById('couponModal').style.display = 'flex';
        }

        function closeCouponModal() {
            document.getElementById('couponModal').style.display = 'none';
        }

        function toggleCouponScopeSections() {
            const isAll = document.getElementById('couponScopeAllEvents').checked;
            document.getElementById('couponSectionExclusions').style.display = isAll ? 'block' : 'none';
            document.getElementById('couponSectionSelectedEvents').style.display = isAll ? 'none' : 'block';
        }

        function generateRandomCouponCode() {
            const prefixes = ['VIVEGO', 'PROMO', 'VIP', 'FEST', 'BLACK', 'DESCUENTO', 'SUMMER'];
            const randomPrefix = prefixes[Math.floor(Math.random() * prefixes.length)];
            const randomNum = Math.floor(10 + Math.random() * 90);
            const randomLetters = Math.random().toString(36).substring(2, 5).toUpperCase();
            document.getElementById('couponInputCode').value = `${randomPrefix}${randomNum}${randomLetters}`;
        }

        function copyCouponCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `¡Código ${code} copiado!`,
                    showConfirmButton: false,
                    timer: 1500,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            });
        }

        function toggleCouponActive(id, checkbox) {
            fetch(`/admin/cupones/${id}/toggle`, {
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

        // SweetAlert2 para eliminar cupón
        document.querySelectorAll('.btn-delete-coupon').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: '¿Eliminar este Cupón?',
                    text: 'Este código dejará de ser aceptado en el checkout.',
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
            const rows = document.querySelectorAll('#couponsTable tbody tr');
            rows.forEach(r => {
                const text = r.textContent.toLowerCase();
                r.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
@endpush
