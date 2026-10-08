@extends('layouts.app')

@section('title', 'Logs de Pagos & Auditoría de Checkout | Vive Go')

@push('styles')
    <style>
        /* Estilos de Paginación */
        .dt-page-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #CBD5E1;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            text-decoration: none !important;
            user-select: none;
            line-height: 1.2;
        }

        .dt-page-btn:hover:not(.disabled):not(.active) {
            background: rgba(255, 85, 0, 0.15);
            border-color: rgba(255, 85, 0, 0.4);
            color: #FF5500;
            transform: translateY(-1px);
        }

        .dt-page-btn.active {
            background: linear-gradient(135deg, #FF5500, #FF7700) !important;
            border-color: #FF5500 !important;
            color: #FFFFFF !important;
            font-weight: 900;
            box-shadow: 0 2px 8px rgba(255, 85, 0, 0.4);
            cursor: default;
        }

        .dt-page-btn.disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
            color: #64748B;
        }

        .dt-page-dots {
            color: #64748B;
            padding: 0 0.35rem;
            font-weight: 700;
            user-select: none;
            display: inline-flex;
            align-items: center;
        }

        /* Log Level Badges */
        .badge-level-info {
            background: rgba(16, 185, 129, 0.15);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.35);
        }
        .badge-level-warning {
            background: rgba(245, 158, 11, 0.15);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.35);
        }
        .badge-level-error {
            background: rgba(239, 68, 68, 0.18);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.4);
        }
        .badge-level-debug {
            background: rgba(139, 92, 246, 0.15);
            color: #A78BFA;
            border: 1px solid rgba(139, 92, 246, 0.35);
        }

        /* Gateway Badges */
        .badge-gw-culqi {
            background: rgba(255, 136, 0, 0.15);
            color: #FFA500;
            border: 1px solid rgba(255, 136, 0, 0.35);
        }
        .badge-gw-izipay {
            background: rgba(99, 102, 241, 0.15);
            color: #818CF8;
            border: 1px solid rgba(99, 102, 241, 0.35);
        }
        .badge-gw-cortesia {
            background: rgba(20, 184, 166, 0.15);
            color: #2DD4BF;
            border: 1px solid rgba(20, 184, 166, 0.35);
        }
        .badge-gw-sistema {
            background: rgba(148, 163, 184, 0.15);
            color: #CBD5E1;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        /* Live Indicator */
        .live-pulse {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.6);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .json-pre-viewer {
            background: #08080E;
            color: #E2E8F0;
            padding: 1rem;
            border-radius: 10px;
            font-family: 'Fira Code', monospace, Consolas, monospace;
            font-size: 0.8rem;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.08);
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .theme-light .json-pre-viewer {
            background: #F8FAFC;
            color: #0F172A;
            border-color: #E2E8F0;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <form action="{{ route('web.checkout_logs') }}" method="GET" class="dash-search-container" style="flex: 1; max-width: 450px;">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" name="q" value="{{ $search }}" class="dash-search-input" placeholder="Buscar por ID de orden, recibo, correo, token o error...">
                    @if($levelFilter !== 'all')
                        <input type="hidden" name="level" value="{{ $levelFilter }}">
                    @endif
                    @if($gatewayFilter !== 'all')
                        <input type="hidden" name="gateway" value="{{ $gatewayFilter }}">
                    @endif
                </form>

                <div class="dash-top-actions">
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema">
                        <span id="themeToggleIcon">☀️</span>
                    </button>
                    <a href="{{ route('web.checkout_logs') }}" class="dash-icon-btn" title="Refrescar Registros">
                        <span>🔄</span>
                    </a>
                </div>
            </header>

            <div class="dash-container">
                <!-- NOTIFICACIONES FLASH -->
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

                @if(session('error'))
                    <div class="alert-custom alert-danger" style="margin-bottom: 1.5rem; background: rgba(239, 68, 68, 0.15); border-left: 4px solid #EF4444; color: #FCA5A5; display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 12px;">
                        <div style="font-size: 1.5rem;">⚠️</div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 0.25rem 0; color: #FFFFFF; font-size: 0.95rem;">Error en la Operación</h4>
                            <p style="margin: 0; font-size: 0.85rem;">{{ session('error') }}</p>
                        </div>
                        <button style="background: none; border: none; color: #CBD5E1; cursor: pointer;" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                <!-- ENCABEZADO PRINCIPAL & ACCIONES GLOBALES -->
                <div class="settings-header-banner" style="margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.25rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <span class="settings-tag" style="background: rgba(255, 85, 0, 0.15); color: #FF7700; border: 1px solid rgba(255, 85, 0, 0.35);">
                                🧾 AUDITORÍA DE PAGOS
                            </span>
                            <div style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(16, 185, 129, 0.12); padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <span class="live-pulse"></span>
                                <span style="font-size: 0.75rem; color: #34D399; font-weight: 700;">Archivo Único: checkout.log</span>
                            </div>
                        </div>
                        <h1 class="settings-page-title" style="margin-top: 0.4rem;">Logs de Pagos & Pasarelas</h1>
                        <p class="settings-page-subtitle">Monitoreo en tiempo real de transacciones, tokens de tarjeta, webhooks asíncronos y pagos QR de Culqi, Izipay y Cortesías.</p>
                    </div>

                    <!-- BOTONERA DE ACCIONES -->
                    <div style="display: flex; flex-wrap: wrap; gap: 0.65rem; align-items: center;">
                        <!-- Toggle Live Auto-Refresh -->
                        <button type="button" id="btnToggleLive" class="btn" onclick="toggleLiveFeed()" style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.35); color: #34D399; border-radius: 10px; padding: 0.65rem 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                            <span id="liveIcon">🟢</span> <span id="liveText">Modo en Vivo (ON)</span>
                        </button>

                        <!-- Descargar Archivo -->
                        <a href="{{ route('web.checkout_logs.download') }}" class="btn" style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.35); color: #60A5FA; border-radius: 10px; padding: 0.65rem 1.1rem; font-weight: 700; text-decoration: none; transition: all 0.2s;" title="Descargar checkout.log">
                            <span>📥</span> Descargar Log
                        </a>

                        <!-- Vaciar Log -->
                        <button type="button" class="btn" onclick="openClearLogModal()" style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); color: #F87171; border-radius: 10px; padding: 0.65rem 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;" title="Limpiar y vaciar checkout.log">
                            <span>🗑️</span> Vaciar Log
                        </button>
                    </div>
                </div>

                <!-- STATS CARDS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">
                    <!-- Total Eventos -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #60A5FA;">
                            🧾
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Total Registros</span>
                            <strong style="font-size: 1.5rem; color: #FFFFFF; font-weight: 800;" id="statTotal">{{ number_format($totalCount) }}</strong>
                        </div>
                    </div>

                    <!-- Culqi Perú -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,136,0,0.15); border: 1px solid rgba(255,136,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #FFA500;">
                            💳
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Culqi (QR & Tarjeta)</span>
                            <strong style="font-size: 1.5rem; color: #FFA500; font-weight: 800;">{{ number_format($culqiCount) }}</strong>
                        </div>
                    </div>

                    <!-- Izipay -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #818CF8;">
                            🟣
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Izipay Pasarela</span>
                            <strong style="font-size: 1.5rem; color: #818CF8; font-weight: 800;">{{ number_format($izipayCount) }}</strong>
                        </div>
                    </div>

                    <!-- Advertencias / Errores -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #F87171;">
                            ⚠️
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Avisos & Errores</span>
                            <strong style="font-size: 1.5rem; color: #EF4444; font-weight: 800;">{{ number_format($warningErrorCount) }}</strong>
                        </div>
                    </div>

                    <!-- Tamaño del Archivo -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #34D399;">
                            💾
                        </div>
                        <div>
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Peso storage/logs</span>
                            <strong style="font-size: 1.5rem; color: #10B981; font-weight: 800;">{{ $fileSizeFormatted }}</strong>
                        </div>
                    </div>
                </div>

                <!-- BARRA DE FILTROS AVANZADOS -->
                <div class="settings-card-box" style="margin-bottom: 1.5rem; padding: 1.25rem;">
                    <form action="{{ route('web.checkout_logs') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; flex: 1; min-width: 300px;">
                            <!-- Filtro de Nivel -->
                            <div style="display: inline-flex; background: rgba(0,0,0,0.3); padding: 0.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['level' => 'all'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $levelFilter === 'all' ? 'background: #FF5500; color: #FFFFFF; box-shadow: 0 2px 8px rgba(255,85,0,0.4);' : 'color: #94A3B8;' }}">
                                    Todos ({{ $totalCount }})
                                </a>
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['level' => 'info'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $levelFilter === 'info' ? 'background: #10B981; color: #FFFFFF; box-shadow: 0 2px 8px rgba(16,185,129,0.4);' : 'color: #94A3B8;' }}">
                                    ✓ INFO
                                </a>
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['level' => 'warning'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $levelFilter === 'warning' ? 'background: #F59E0B; color: #FFFFFF; box-shadow: 0 2px 8px rgba(245,158,11,0.4);' : 'color: #94A3B8;' }}">
                                    ⚠️ WARNING
                                </a>
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['level' => 'error'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $levelFilter === 'error' ? 'background: #EF4444; color: #FFFFFF; box-shadow: 0 2px 8px rgba(239,68,68,0.4);' : 'color: #94A3B8;' }}">
                                    ✕ ERROR
                                </a>
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['level' => 'debug'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $levelFilter === 'debug' ? 'background: #8B5CF6; color: #FFFFFF; box-shadow: 0 2px 8px rgba(139,92,246,0.4);' : 'color: #94A3B8;' }}">
                                    🔍 DEBUG
                                </a>
                            </div>

                            <!-- Filtro por Pasarela -->
                            <div style="display: inline-flex; background: rgba(0,0,0,0.3); padding: 0.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['gateway' => 'all'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $gatewayFilter === 'all' ? 'background: #3B82F6; color: #FFFFFF;' : 'color: #94A3B8;' }}">
                                    Todas
                                </a>
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['gateway' => 'culqi'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $gatewayFilter === 'culqi' ? 'background: #FF8800; color: #FFFFFF; box-shadow: 0 2px 8px rgba(255,136,0,0.4);' : 'color: #94A3B8;' }}">
                                    Culqi ({{ $culqiCount }})
                                </a>
                                <a href="{{ route('web.checkout_logs', array_merge(request()->query(), ['gateway' => 'izipay'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $gatewayFilter === 'izipay' ? 'background: #6366F1; color: #FFFFFF; box-shadow: 0 2px 8px rgba(99,102,241,0.4);' : 'color: #94A3B8;' }}">
                                    Izipay ({{ $izipayCount }})
                                </a>
                            </div>

                            <!-- Selector Registros por Página -->
                            <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                <span style="font-size: 0.75rem; color: #94A3B8;">Ver:</span>
                                <select name="per_page" onchange="this.form.submit()" style="background: #0B0B12; border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; padding: 0.45rem 0.75rem; border-radius: 8px; font-size: 0.8rem;">
                                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 / pág</option>
                                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30 / pág</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / pág</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 / pág</option>
                                </select>
                            </div>

                            @if(!empty($search))
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                        </div>

                        <!-- Botón Limpiar Filtros -->
                        @if(!empty($search) || $levelFilter !== 'all' || $gatewayFilter !== 'all' || $perPage != 30)
                            <div>
                                <a href="{{ route('web.checkout_logs') }}" style="color: #94A3B8; font-size: 0.825rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 0.85rem; background: rgba(255,255,255,0.04); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                                    ✕ Limpiar Filtros
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                <!-- TABLA PRINCIPAL DE LOGS -->
                <div class="settings-card-box" style="padding: 0; overflow: hidden;">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,85,0,0.12); border: 1px solid rgba(255,85,0,0.3); color: #FF5500; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                📜
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #FFFFFF;">Flujo de Checkout & Pasarelas de Pago</h3>
                                <p style="margin: 0.2rem 0 0 0; font-size: 0.8rem; color: #94A3B8;">Eventos registrados en <code style="color: #60A5FA; background: rgba(37,99,235,0.1); padding: 0.15rem 0.4rem; border-radius: 4px;">storage/logs/checkout.log</code></p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.8rem; color: #94A3B8; background: rgba(255,255,255,0.04); padding: 0.35rem 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                                Registros: {{ $paginatedLogs->firstItem() ?? 0 }} - {{ $paginatedLogs->lastItem() ?? 0 }} de {{ $paginatedLogs->total() }}
                            </span>
                        </div>
                    </div>

                    <div class="dash-table-container" style="margin: 0;">
                        <table class="dash-table" style="width: 100%;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.02); font-size: 0.75rem; color: #94A3B8; text-transform: uppercase;">
                                    <th style="padding: 0.85rem 1rem; width: 60px;">#</th>
                                    <th style="padding: 0.85rem 1rem; width: 155px;">Fecha & Hora</th>
                                    <th style="padding: 0.85rem 1rem; width: 95px;">Nivel</th>
                                    <th style="padding: 0.85rem 1rem; width: 120px;">Pasarela</th>
                                    <th style="padding: 0.85rem 1rem; width: 160px;">Categoría</th>
                                    <th style="padding: 0.85rem 1rem;">Mensaje / Operación</th>
                                    <th style="padding: 0.85rem 1rem; width: 120px; text-align: right;">Detalle</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                @forelse($paginatedLogs as $log)
                                    @php
                                        $levelUpper = strtoupper($log['level'] ?? 'INFO');
                                        $levelBadgeClass = match($levelUpper) {
                                            'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY' => 'badge-level-error',
                                            'WARNING' => 'badge-level-warning',
                                            'DEBUG' => 'badge-level-debug',
                                            default => 'badge-level-info',
                                        };

                                        $gwUpper = strtoupper($log['gateway'] ?? '');
                                        $gwBadgeClass = match(true) {
                                            str_contains($gwUpper, 'CULQI') => 'badge-gw-culqi',
                                            str_contains($gwUpper, 'IZIPAY') => 'badge-gw-izipay',
                                            str_contains($gwUpper, 'CORTES') => 'badge-gw-cortesia',
                                            default => 'badge-gw-sistema',
                                        };

                                        $hasContext = !empty($log['context']) && is_array($log['context']);
                                    @endphp
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                                        <!-- ID / Seq -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <span style="font-weight: 800; color: #64748B; font-size: 0.8rem; font-family: monospace;">{{ $log['id'] }}</span>
                                        </td>

                                        <!-- Timestamp -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <span style="display: block; font-weight: 700; color: #FFFFFF; font-size: 0.825rem; font-family: monospace;">{{ date('d/m/Y', strtotime($log['timestamp'])) }}</span>
                                            <span style="display: block; font-size: 0.75rem; color: #94A3B8; font-family: monospace;">{{ date('H:i:s', strtotime($log['timestamp'])) }}</span>
                                        </td>

                                        <!-- Level Badge -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <span class="{{ $levelBadgeClass }}" style="display: inline-block; padding: 0.2rem 0.55rem; border-radius: 6px; font-size: 0.725rem; font-weight: 900; letter-spacing: 0.5px;">
                                                {{ $levelUpper }}
                                            </span>
                                        </td>

                                        <!-- Gateway Badge -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <span class="{{ $gwBadgeClass }}" style="display: inline-block; padding: 0.2rem 0.55rem; border-radius: 6px; font-size: 0.725rem; font-weight: 700;">
                                                {{ $log['gateway'] }}
                                            </span>
                                        </td>

                                        <!-- Categoría / Tag -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <span style="display: inline-block; background: rgba(255,255,255,0.05); color: #E2E8F0; padding: 0.2rem 0.55rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.08);">
                                                {{ $log['category'] }}
                                            </span>
                                        </td>

                                        <!-- Mensaje / Detalle -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <div style="font-size: 0.85rem; color: #FFFFFF; font-weight: 500; line-height: 1.4;">
                                                {{ $log['message'] }}
                                            </div>
                                            @if($hasContext && isset($log['context']['order_id']))
                                                <span style="display: inline-block; margin-top: 0.25rem; font-size: 0.725rem; color: #60A5FA; background: rgba(37,99,235,0.1); padding: 0.1rem 0.4rem; border-radius: 4px; font-family: monospace;">
                                                    Orden: {{ $log['context']['order_id'] }}
                                                </span>
                                            @endif
                                            @if($hasContext && isset($log['context']['receipt']))
                                                <span style="display: inline-block; margin-top: 0.25rem; font-size: 0.725rem; color: #34D399; background: rgba(16,185,129,0.1); padding: 0.1rem 0.4rem; border-radius: 4px; font-family: monospace;">
                                                    Recibo: {{ $log['context']['receipt'] }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Botón Ver Contexto / JSON -->
                                        <td style="padding: 0.85rem 1rem; text-align: right;">
                                            @if($hasContext)
                                                <button type="button" class="btn btn-sm" onclick="showLogDetail({{ json_encode($log) }})" style="background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.35); color: #60A5FA; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;" title="Ver Contexto Completo JSON">
                                                    <span>🔍</span> JSON
                                                </button>
                                            @else
                                                <span style="font-size: 0.75rem; color: #64748B;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="padding: 3.5rem 1rem; text-align: center;">
                                            <div style="width: 60px; height: 60px; border-radius: 20px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1rem auto; color: #64748B;">
                                                🧾
                                            </div>
                                            <h4 style="color: #FFFFFF; font-size: 1.1rem; margin: 0 0 0.4rem 0; font-weight: 700;">No se encontraron registros de logs</h4>
                                            <p style="color: #94A3B8; font-size: 0.85rem; max-width: 480px; margin: 0 auto;">
                                                @if(!empty($search) || $levelFilter !== 'all' || $gatewayFilter !== 'all')
                                                    No hay eventos que coincidan con los filtros de búsqueda aplicados. Intenta restablecer los filtros.
                                                @else
                                                    Aún no se han ejecutado operaciones de checkout o el archivo <code style="color:#FF7700">storage/logs/checkout.log</code> está vacío.
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- FOOTER & PAGINACIÓN PERSONALIZADA -->
                    @if($paginatedLogs->hasPages())
                        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                            <span style="font-size: 0.825rem; color: #94A3B8;">
                                Mostrando página <strong style="color: #FFFFFF;">{{ $paginatedLogs->currentPage() }}</strong> de <strong style="color: #FFFFFF;">{{ $paginatedLogs->lastPage() }}</strong>
                            </span>

                            <div style="display: flex; gap: 0.35rem; align-items: center;">
                                {{-- Botón Anterior --}}
                                @if($paginatedLogs->onFirstPage())
                                    <span class="dt-page-btn disabled">‹</span>
                                @else
                                    <a href="{{ $paginatedLogs->previousPageUrl() }}" class="dt-page-btn">‹</a>
                                @endif

                                {{-- Números de Página con Ventana Deslizante --}}
                                @php
                                    $cur = $paginatedLogs->currentPage();
                                    $last = $paginatedLogs->lastPage();
                                    $start = max(1, $cur - 2);
                                    $end = min($last, $cur + 2);
                                @endphp

                                @if($start > 1)
                                    <a href="{{ $paginatedLogs->url(1) }}" class="dt-page-btn">1</a>
                                    @if($start > 2)
                                        <span class="dt-page-dots">...</span>
                                    @endif
                                @endif

                                @for($p = $start; $p <= $end; $p++)
                                    @if($p == $cur)
                                        <span class="dt-page-btn active">{{ $p }}</span>
                                    @else
                                        <a href="{{ $paginatedLogs->url($p) }}" class="dt-page-btn">{{ $p }}</a>
                                    @endif
                                @endfor

                                @if($end < $last)
                                    @if($end < $last - 1)
                                        <span class="dt-page-dots">...</span>
                                    @endif
                                    <a href="{{ $paginatedLogs->url($last) }}" class="dt-page-btn">{{ $last }}</a>
                                @endif

                                {{-- Botón Siguiente --}}
                                @if($paginatedLogs->hasMorePages())
                                    <a href="{{ $paginatedLogs->nextPageUrl() }}" class="dt-page-btn">›</a>
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

    <!-- MODAL PARA VER CONTEXTO / JSON DETALLADO -->
    <div id="modalLogDetail" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
        <div style="background: #0F0F18; border: 1px solid rgba(255,255,255,0.15); border-radius: 16px; width: 100%; max-width: 650px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7); animation: modalIn 0.2s ease;">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <div style="width: 34px; height: 34px; border-radius: 8px; background: rgba(37,99,235,0.15); color: #60A5FA; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        🔍
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1rem; color: #FFFFFF; font-weight: 800;" id="modalLogTitle">Detalle del Registro de Log</h3>
                        <p style="margin: 0.15rem 0 0 0; font-size: 0.75rem; color: #94A3B8;" id="modalLogSubtitle">Información estructurada y contexto</p>
                    </div>
                </div>
                <button type="button" onclick="closeLogDetailModal()" style="background: none; border: none; color: #94A3B8; font-size: 1.25rem; cursor: pointer; padding: 0.25rem 0.5rem; border-radius: 6px;">✕</button>
            </div>

            <div style="padding: 1.5rem; max-height: 70vh; overflow-y: auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="background: rgba(255,255,255,0.03); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <span style="font-size: 0.7rem; color: #94A3B8; display: block; text-transform: uppercase;">Fecha & Hora</span>
                        <strong style="font-size: 0.85rem; color: #FFFFFF; font-family: monospace;" id="modalLogTime">-</strong>
                    </div>
                    <div style="background: rgba(255,255,255,0.03); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <span style="font-size: 0.7rem; color: #94A3B8; display: block; text-transform: uppercase;">Pasarela / Nivel</span>
                        <div style="display: flex; gap: 0.35rem; align-items: center; margin-top: 0.2rem;">
                            <span id="modalLogGateway" class="badge-gw-culqi" style="padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.725rem; font-weight: 700;">-</span>
                            <span id="modalLogLevel" class="badge-level-info" style="padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.725rem; font-weight: 700;">-</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <span style="font-size: 0.75rem; color: #94A3B8; display: block; margin-bottom: 0.35rem; font-weight: 600;">Mensaje:</span>
                    <div id="modalLogMsg" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); padding: 0.75rem; border-radius: 8px; color: #FFFFFF; font-size: 0.85rem; line-height: 1.4;">-</div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;">Contexto / Payload JSON:</span>
                        <button type="button" onclick="copyModalJson()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #CBD5E1; font-size: 0.725rem; padding: 0.2rem 0.55rem; border-radius: 6px; cursor: pointer;">
                            📋 Copiar JSON
                        </button>
                    </div>
                    <pre id="modalLogJson" class="json-pre-viewer">{}</pre>
                </div>
            </div>

            <div style="padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: flex-end;">
                <button type="button" class="btn" onclick="closeLogDetailModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; border-radius: 8px; padding: 0.5rem 1.25rem; font-weight: 700; cursor: pointer;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA VACIAR LOG -->
    <div id="modalClearLog" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
        <div style="background: #0F0F18; border: 1px solid rgba(239,68,68,0.3); border-radius: 16px; width: 100%; max-width: 480px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(239,68,68,0.25);">
            <div style="padding: 1.5rem; text-align: center;">
                <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.35); color: #F87171; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 1.25rem auto;">
                    🗑️
                </div>
                <h3 style="margin: 0 0 0.5rem 0; color: #FFFFFF; font-size: 1.2rem; font-weight: 800;">¿Vaciar Archivo de Logs?</h3>
                <p style="margin: 0 0 1.5rem 0; color: #94A3B8; font-size: 0.875rem; line-height: 1.5;">
                    Esta acción truncará por completo el archivo <code style="color: #F87171; background: rgba(239,68,68,0.1); padding: 0.15rem 0.35rem; border-radius: 4px;">checkout.log</code> y eliminará todos los registros históricos actuales de pasarelas de pago. Esta acción no se puede deshacer.
                </p>

                <form action="{{ route('web.checkout_logs.clear') }}" method="POST" style="display: flex; gap: 0.75rem; justify-content: center;">
                    @csrf
                    <button type="button" onclick="closeClearLogModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; border-radius: 8px; padding: 0.65rem 1.25rem; font-weight: 700; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="background: linear-gradient(135deg, #EF4444, #DC2626); border: none; color: #FFFFFF; border-radius: 8px; padding: 0.65rem 1.5rem; font-weight: 800; cursor: pointer; box-shadow: 0 2px 10px rgba(239,68,68,0.4);">
                        Sí, Vaciar Logs
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Modal de Visualización Detallada JSON
        let currentModalJsonString = '';

        function showLogDetail(logData) {
            document.getElementById('modalLogTitle').innerText = 'Log #' + logData.id + ' — ' + logData.category;
            document.getElementById('modalLogSubtitle').innerText = logData.gateway + ' (' + logData.level + ')';
            document.getElementById('modalLogTime').innerText = logData.timestamp;
            
            const gwEl = document.getElementById('modalLogGateway');
            gwEl.innerText = logData.gateway;
            gwEl.className = logData.gateway.includes('Culqi') ? 'badge-gw-culqi' : (logData.gateway.includes('Izipay') ? 'badge-gw-izipay' : 'badge-gw-sistema');

            const lvlEl = document.getElementById('modalLogLevel');
            lvlEl.innerText = logData.level;
            lvlEl.className = logData.level === 'ERROR' ? 'badge-level-error' : (logData.level === 'WARNING' ? 'badge-level-warning' : 'badge-level-info');

            document.getElementById('modalLogMsg').innerText = logData.message;

            const contextData = logData.context || { raw: logData.raw };
            currentModalJsonString = JSON.stringify(contextData, null, 2);
            document.getElementById('modalLogJson').innerText = currentModalJsonString;

            const modal = document.getElementById('modalLogDetail');
            modal.style.display = 'flex';
        }

        function closeLogDetailModal() {
            document.getElementById('modalLogDetail').style.display = 'none';
        }

        function copyModalJson() {
            if (!currentModalJsonString) return;
            navigator.clipboard.writeText(currentModalJsonString).then(() => {
                alert('¡Contexto JSON copiado al portapapeles!');
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = currentModalJsonString;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                alert('¡Contexto JSON copiado al portapapeles!');
            });
        }

        // Modal Vaciar Log
        function openClearLogModal() {
            document.getElementById('modalClearLog').style.display = 'flex';
        }

        function closeClearLogModal() {
            document.getElementById('modalClearLog').style.display = 'none';
        }

        // Auto Refresh en Vivo vía Feed API
        let liveTimer = null;
        let isLiveActive = false;

        function toggleLiveFeed() {
            isLiveActive = !isLiveActive;
            const btn = document.getElementById('btnToggleLive');
            const icon = document.getElementById('liveIcon');
            const text = document.getElementById('liveText');

            if (isLiveActive) {
                btn.style.background = 'rgba(16, 185, 129, 0.2)';
                btn.style.borderColor = 'rgba(16, 185, 129, 0.5)';
                icon.innerText = '🟢';
                text.innerText = 'Modo en Vivo (Activo: 5s)';
                fetchLiveFeed();
                liveTimer = setInterval(fetchLiveFeed, 5000);
            } else {
                btn.style.background = 'rgba(255, 255, 255, 0.06)';
                btn.style.borderColor = 'rgba(255, 255, 255, 0.12)';
                btn.style.color = '#94A3B8';
                icon.innerText = '⏸️';
                text.innerText = 'Modo en Vivo (Pausado)';
                if (liveTimer) clearInterval(liveTimer);
            }
        }

        function fetchLiveFeed() {
            fetch('{{ route("web.checkout_logs.feed") }}')
                .then(res => res.json())
                .then(data => {
                    if (data.success && Array.isArray(data.logs)) {
                        // Actualizar contador rápido
                        const statTotalEl = document.getElementById('statTotal');
                        if (statTotalEl && data.count) {
                            statTotalEl.innerText = data.count.toLocaleString();
                        }
                    }
                })
                .catch(err => console.debug('Live feed sync error:', err));
        }

        // Cerrar modales con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogDetailModal();
                closeClearLogModal();
            }
        });
    </script>
@endpush
