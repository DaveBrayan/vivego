@extends('layouts.app')

@section('title', 'Logs de Pagos & Auditoría de Checkout | Vive Go')

@push('styles')
    <style>
        /* Estilos de la Terminal en Vivo */
        .terminal-window {
            background: #07090E;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.75);
            display: flex;
            flex-direction: column;
        }

        .terminal-header {
            background: #0D1117;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .terminal-dots {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .terminal-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .dot-red { background: #EF4444; box-shadow: 0 0 6px rgba(239, 68, 68, 0.6); }
        .dot-yellow { background: #F59E0B; box-shadow: 0 0 6px rgba(245, 158, 11, 0.6); }
        .dot-green { background: #10B981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.6); }

        .terminal-body {
            background: #05070A;
            padding: 1.25rem;
            min-height: 520px;
            max-height: 720px;
            overflow-y: auto;
            font-family: 'Fira Code', 'JetBrains Mono', Consolas, Monaco, monospace;
            font-size: 0.825rem;
            line-height: 1.65;
            color: #E2E8F0;
            scroll-behavior: smooth;
        }

        .terminal-body::-webkit-scrollbar {
            width: 8px;
        }
        .terminal-body::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.4);
        }
        .terminal-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        .terminal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 85, 0, 0.5);
        }

        .terminal-line {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            transition: background 0.15s ease;
            word-break: break-word;
        }

        .terminal-line:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .terminal-line.log-new-flash {
            animation: terminalFlash 1.5s ease-out;
        }

        @keyframes terminalFlash {
            0% { background: rgba(255, 85, 0, 0.25); }
            100% { background: transparent; }
        }

        .t-time {
            color: #67E8F9;
            font-weight: 700;
            font-size: 0.775rem;
            white-space: nowrap;
            user-select: none;
            flex-shrink: 0;
        }

        .t-tag {
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.1rem 0.45rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            flex-shrink: 0;
            user-select: none;
        }

        /* Colores de Tags de Pasarela */
        .tag-culqi { background: rgba(255, 136, 0, 0.2); color: #FFA500; border: 1px solid rgba(255, 136, 0, 0.4); }
        .tag-izipay { background: rgba(99, 102, 241, 0.2); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.4); }
        .tag-cortesia { background: rgba(20, 184, 166, 0.2); color: #2DD4BF; border: 1px solid rgba(20, 184, 166, 0.4); }
        .tag-system { background: rgba(148, 163, 184, 0.15); color: #CBD5E1; border: 1px solid rgba(148, 163, 184, 0.3); }

        /* Colores de Niveles */
        .level-info { color: #38BDF8; font-weight: 700; }
        .level-success { color: #34D399; font-weight: 800; }
        .level-warn { color: #FBBF24; font-weight: 800; }
        .level-error { color: #F87171; font-weight: 900; }

        .terminal-msg {
            flex: 1;
            color: #F1F5F9;
        }

        .terminal-json-toggle {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #94A3B8;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            user-select: none;
        }

        .terminal-json-toggle:hover {
            background: rgba(255, 85, 0, 0.2);
            border-color: rgba(255, 85, 0, 0.4);
            color: #FF7700;
        }

        .terminal-json-block {
            background: #030508;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-left: 3px solid var(--color-primary-orange);
            border-radius: 6px;
            padding: 0.65rem 0.85rem;
            margin-top: 0.4rem;
            font-size: 0.75rem;
            color: #CBD5E1;
            white-space: pre-wrap;
            word-break: break-word;
            display: none;
        }

        /* Pulsador Live */
        .live-pulse-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.6);
            animation: pulse-green-glow 1.8s infinite;
            display: inline-block;
        }

        @keyframes pulse-green-glow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Botones de Barra de Navegación */
        .term-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #E2E8F0;
            font-size: 0.785rem;
            font-weight: 700;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }

        .term-btn:hover {
            background: rgba(255, 85, 0, 0.15);
            border-color: rgba(255, 85, 0, 0.4);
            color: #FF5500;
            transform: translateY(-1px);
        }

        .term-btn.active {
            background: linear-gradient(135deg, #FF5500, #FF7700);
            border-color: #FF5500;
            color: #FFFFFF;
            box-shadow: 0 2px 10px rgba(255, 85, 0, 0.35);
        }

        /* Paginador Tabla */
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
            line-height: 1.2;
        }

        .dt-page-btn:hover:not(.disabled):not(.active) {
            background: rgba(255, 85, 0, 0.15);
            border-color: rgba(255, 85, 0, 0.4);
            color: #FF5500;
        }

        .dt-page-btn.active {
            background: linear-gradient(135deg, #FF5500, #FF7700) !important;
            border-color: #FF5500 !important;
            color: #FFFFFF !important;
            font-weight: 900;
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
                    <input type="text" name="q" value="{{ $search }}" class="dash-search-input" placeholder="Buscar por DNI, correo, recibo, token o error...">
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
                    <div class="alert-custom alert-success" style="margin-bottom: 1.5rem; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34D399; padding: 1rem 1.25rem; border-radius: 12px; font-weight: 700;">
                        <span>✓</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert-custom alert-danger" style="margin-bottom: 1.5rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171; padding: 1rem 1.25rem; border-radius: 12px; font-weight: 700;">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif

                <!-- ENCABEZADO PRINCIPAL & ACCIONES GLOBALES -->
                <div class="settings-header-banner" style="margin-bottom: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.25rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <span class="settings-tag" style="background: rgba(255, 85, 0, 0.15); color: #FF7700; border: 1px solid rgba(255, 85, 0, 0.35);">
                                🧾 AUDITORÍA EN TIEMPO REAL
                            </span>
                            <div style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(16, 185, 129, 0.12); padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <span class="live-pulse-dot" id="headerLiveDot"></span>
                                <span style="font-size: 0.75rem; color: #34D399; font-weight: 700;" id="headerLiveStatus">Transmisión en Vivo</span>
                            </div>
                        </div>
                        <h1 class="settings-page-title" style="margin-top: 0.4rem;">Logs de Pagos & Checkout Live Terminal</h1>
                        <p class="settings-page-subtitle">Monitoreo paso a paso del ciclo de compra: ingreso al checkout, datos del comprador, pasarela seleccionada (Culqi QR/Tarjeta, Izipay), aprobación de pago, entrega de boletos, despacho de correo y trazabilidad de errores.</p>
                    </div>

                    <!-- SELECTOR DE VISTA (TERMINAL VS TABLA) & BOTONERA -->
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                        <div style="background: rgba(0,0,0,0.4); padding: 0.25rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); display: flex; gap: 0.25rem;">
                            <button type="button" class="term-btn active" id="btnSwitchTerminal" onclick="switchViewMode('terminal')">
                                <span>🖥️</span> <span>Modo Terminal</span>
                            </button>
                            <button type="button" class="term-btn" id="btnSwitchTable" onclick="switchViewMode('table')">
                                <span>📊</span> <span>Modo Tabla</span>
                            </button>
                        </div>

                        <!-- Descargar Archivo -->
                        <a href="{{ route('web.checkout_logs.download') }}" class="term-btn" title="Descargar checkout.log">
                            <span>📥</span> <span>Descargar .log</span>
                        </a>

                        <!-- Vaciar Log -->
                        <button type="button" class="term-btn" onclick="openClearLogModal()" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.35); color: #F87171;" title="Limpiar y vaciar checkout.log">
                            <span>🗑️</span> <span>Vaciar</span>
                        </button>
                    </div>
                </div>

                <!-- STATS CARDS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <!-- Total Registros -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #60A5FA;">
                            🧾
                        </div>
                        <div>
                            <span style="font-size: 0.725rem; color: #94A3B8; display: block; font-weight: 700; text-transform: uppercase;">Total Registros</span>
                            <strong style="font-size: 1.4rem; color: #FFFFFF; font-weight: 900;" id="statTotal">{{ number_format($totalCount) }}</strong>
                        </div>
                    </div>

                    <!-- Culqi Perú -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255,136,0,0.15); border: 1px solid rgba(255,136,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #FFA500;">
                            💳
                        </div>
                        <div>
                            <span style="font-size: 0.725rem; color: #94A3B8; display: block; font-weight: 700; text-transform: uppercase;">Culqi (QR / Tarjeta)</span>
                            <strong style="font-size: 1.4rem; color: #FFA500; font-weight: 900;" id="statCulqi">{{ number_format($culqiCount) }}</strong>
                        </div>
                    </div>

                    <!-- Izipay -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #818CF8;">
                            🟣
                        </div>
                        <div>
                            <span style="font-size: 0.725rem; color: #94A3B8; display: block; font-weight: 700; text-transform: uppercase;">Izipay Pasarela</span>
                            <strong style="font-size: 1.4rem; color: #818CF8; font-weight: 900;" id="statIzipay">{{ number_format($izipayCount) }}</strong>
                        </div>
                    </div>

                    <!-- Boletos Emitidos -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #34D399;">
                            🎟️
                        </div>
                        <div>
                            <span style="font-size: 0.725rem; color: #94A3B8; display: block; font-weight: 700; text-transform: uppercase;">Entrega Boletos</span>
                            <strong style="font-size: 1.4rem; color: #34D399; font-weight: 900;">{{ number_format($ticketEventsCount) }}</strong>
                        </div>
                    </div>

                    <!-- Avisos / Errores -->
                    <div class="settings-card-box" style="margin: 0; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #F87171;">
                            ⚠️
                        </div>
                        <div>
                            <span style="font-size: 0.725rem; color: #94A3B8; display: block; font-weight: 700; text-transform: uppercase;">Avisos & Errores</span>
                            <strong style="font-size: 1.4rem; color: #EF4444; font-weight: 900;" id="statErrors">{{ number_format($warningErrorCount) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- CONTENEDOR VISTA TERMINAL (MODO TERMINAL) -->
                <div id="terminalViewContainer" class="terminal-window">
                    <!-- BARRA SUPERIOR DE LA TERMINAL -->
                    <div class="terminal-header">
                        <div style="display: flex; align-items: center; gap: 0.85rem;">
                            <div class="terminal-dots">
                                <span class="terminal-dot dot-red"></span>
                                <span class="terminal-dot dot-yellow"></span>
                                <span class="terminal-dot dot-green"></span>
                            </div>
                            <span style="font-family: monospace; font-size: 0.85rem; font-weight: 800; color: #94A3B8;">
                                <strong style="color: #FF5500;">vivego@checkout</strong>:<span style="color: #60A5FA;">/storage/logs</span>$ <span style="color: #34D399;">tail -f checkout.log</span>
                            </span>
                        </div>

                        <!-- HERRAMIENTAS DE LA TERMINAL -->
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <!-- Botón Pausar / Reanudar Stream -->
                            <button type="button" id="btnToggleStream" class="term-btn" onclick="toggleStreamFeed()" style="font-size: 0.75rem; padding: 0.35rem 0.7rem;">
                                <span id="streamIcon">⏸️</span> <span id="streamText">Pausar Stream</span>
                            </button>

                            <!-- Botón Auto-Scroll -->
                            <button type="button" id="btnToggleAutoScroll" class="term-btn active" onclick="toggleAutoScroll()" style="font-size: 0.75rem; padding: 0.35rem 0.7rem;">
                                <span>⬇️</span> <span id="autoScrollText">Auto-Scroll: ON</span>
                            </button>

                            <!-- Botón Copiar Todo -->
                            <button type="button" class="term-btn" onclick="copyTerminalLogs()" style="font-size: 0.75rem; padding: 0.35rem 0.7rem;" title="Copiar salida de texto de la terminal">
                                <span>📋</span> <span>Copiar</span>
                            </button>

                            <!-- Botón Limpiar Pantalla -->
                            <button type="button" class="term-btn" onclick="clearTerminalScreen()" style="font-size: 0.75rem; padding: 0.35rem 0.7rem;" title="Limpiar la vista actual de la pantalla">
                                <span>🧹</span> <span>Limpiar Pantalla</span>
                            </button>
                        </div>
                    </div>

                    <!-- BARRA DE FILTRADO RÁPIDO EN TERMINAL -->
                    <div style="background: #080C14; border-bottom: 1px solid rgba(255,255,255,0.06); padding: 0.65rem 1.25rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex: 1; max-width: 480px;">
                            <span style="color: #64748B; font-size: 0.85rem;">🔎</span>
                            <input type="text" id="terminalFilterInput" oninput="filterTerminalLines(this.value)" placeholder="Filtrar consola (ej: DNI, correo, 'culqi', 'izipay', 'REC-', 'ERROR', 'boletos')..." style="width: 100%; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); color: #FFF; font-family: monospace; font-size: 0.8rem; padding: 0.4rem 0.75rem; border-radius: 6px;">
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                            <button type="button" onclick="setTerminalCategoryFilter('ALL')" class="term-btn active term-filter-tag" data-cat="ALL" style="font-size: 0.7rem; padding: 0.25rem 0.55rem;">Todos</button>
                            <button type="button" onclick="setTerminalCategoryFilter('ERROR')" class="term-btn term-filter-tag" data-cat="ERROR" style="font-size: 0.7rem; padding: 0.25rem 0.55rem; color: #F87171;">🚨 Errores</button>
                            <button type="button" onclick="setTerminalCategoryFilter('PAGO')" class="term-btn term-filter-tag" data-cat="PAGO" style="font-size: 0.7rem; padding: 0.25rem 0.55rem; color: #34D399;">💳 Pagos</button>
                            <button type="button" onclick="setTerminalCategoryFilter('BOLETOS')" class="term-btn term-filter-tag" data-cat="BOLETOS" style="font-size: 0.7rem; padding: 0.25rem 0.55rem; color: #60A5FA;">🎟️ Boletos</button>
                            <button type="button" onclick="setTerminalCategoryFilter('CORREO')" class="term-btn term-filter-tag" data-cat="CORREO" style="font-size: 0.7rem; padding: 0.25rem 0.55rem; color: #A78BFA;">📧 Correos</button>
                        </div>
                    </div>

                    <!-- CUERPO DE LA TERMINAL -->
                    <div id="terminalBody" class="terminal-body">
                        @php
                            $chronologicalLogs = array_reverse($terminalLogs);
                        @endphp

                        @forelse($chronologicalLogs as $log)
                            @php
                                $levelClass = match(strtoupper($log['level'])) {
                                    'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY' => 'level-error',
                                    'WARNING' => 'level-warn',
                                    'DEBUG' => 'level-info',
                                    default => 'level-success',
                                };

                                $gwClass = match(true) {
                                    str_contains(strtoupper($log['gateway']), 'CULQI') => 'tag-culqi',
                                    str_contains(strtoupper($log['gateway']), 'IZIPAY') => 'tag-izipay',
                                    str_contains(strtoupper($log['gateway']), 'CORTES') => 'tag-cortesia',
                                    default => 'tag-system',
                                };

                                $hasCtx = !empty($log['context']) && is_array($log['context']);
                            @endphp
                            <div class="terminal-line" data-search="{{ strtolower($log['timestamp'] . ' ' . $log['level'] . ' ' . $log['gateway'] . ' ' . $log['category'] . ' ' . $log['message'] . ' ' . json_encode($log['context'])) }}">
                                <span class="t-time">[{{ $log['timestamp'] }}]</span>
                                <span class="t-tag {{ $gwClass }}">{{ $log['gateway'] }}</span>
                                <span class="{{ $levelClass }}">[{{ $log['level'] }}]</span>
                                <div class="terminal-msg">
                                    <span>{{ $log['message'] }}</span>
                                    @if($hasCtx)
                                        <button type="button" class="terminal-json-toggle" onclick="toggleInlineJson(this)">
                                            <span>▶</span> JSON
                                        </button>
                                        <div class="terminal-json-block">{{ json_encode($log['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="color: #64748B; text-align: center; padding: 4rem 1rem;">
                                <span>⚡ No se registran eventos de checkout en el archivo de log aún.</span>
                            </div>
                        @endforelse
                    </div>

                    <!-- FOOTER DE LA TERMINAL -->
                    <div style="background: #0D1117; border-top: 1px solid rgba(255,255,255,0.08); padding: 0.65rem 1.25rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.775rem; color: #94A3B8;">
                        <span id="termLineCountLabel">Mostrando {{ count($terminalLogs) }} eventos recientes en buffer</span>
                        <span>Tamaño de Archivo: <strong style="color: #FFFFFF;">{{ $fileSizeFormatted }}</strong></span>
                    </div>
                </div>

                <!-- CONTENEDOR VISTA TABLA CLÁSICA (MODO TABLA) -->
                <div id="tableViewContainer" class="settings-card-box" style="display: none; padding: 0; overflow: hidden; margin-top: 1.5rem;">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,85,0,0.12); border: 1px solid rgba(255,85,0,0.3); color: #FF5500; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                📜
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #FFFFFF;">Tabla de Auditoría Paginada</h3>
                                <p style="margin: 0.2rem 0 0 0; font-size: 0.8rem; color: #94A3B8;">Registros extraídos de <code style="color: #60A5FA;">storage/logs/checkout.log</code></p>
                            </div>
                        </div>

                        <div>
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
                                    <th style="padding: 0.85rem 1rem; width: 175px;">Fecha & Hora (Precisa)</th>
                                    <th style="padding: 0.85rem 1rem; width: 95px;">Nivel</th>
                                    <th style="padding: 0.85rem 1rem; width: 125px;">Pasarela</th>
                                    <th style="padding: 0.85rem 1rem;">Mensaje / Operación</th>
                                    <th style="padding: 0.85rem 1rem; width: 100px; text-align: right;">Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paginatedLogs as $log)
                                    @php
                                        $levelUpper = strtoupper($log['level'] ?? 'INFO');
                                        $levelBadgeClass = match($levelUpper) {
                                            'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY' => 'badge-level-error',
                                            'WARNING' => 'badge-level-warning',
                                            default => 'badge-level-info',
                                        };
                                        $hasContext = !empty($log['context']) && is_array($log['context']);
                                    @endphp
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                        <td style="padding: 0.85rem 1rem; color: #64748B; font-weight: 800; font-family: monospace;">{{ $log['id'] }}</td>
                                        <td style="padding: 0.85rem 1rem;">
                                            <span style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: #67E8F9;">{{ $log['timestamp'] }}</span>
                                        </td>
                                        <td style="padding: 0.85rem 1rem;">
                                            <span class="t-tag" style="background: rgba(255,255,255,0.08); color: #FFF;">{{ $log['level'] }}</span>
                                        </td>
                                        <td style="padding: 0.85rem 1rem;">
                                            <strong style="color: #FFA500; font-size: 0.85rem;">{{ $log['gateway'] }}</strong>
                                        </td>
                                        <td style="padding: 0.85rem 1rem;">
                                            <span style="color: #E2E8F0; font-size: 0.85rem;">{{ $log['message'] }}</span>
                                        </td>
                                        <td style="padding: 0.85rem 1rem; text-align: right;">
                                            @if($hasContext)
                                                <button type="button" class="btn btn-sm" onclick="showLogDetailModal({{ json_encode($log) }})" style="background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.35); color: #60A5FA; border-radius: 6px; padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                                    🔍 JSON
                                                </button>
                                            @else
                                                <span style="color: #64748B;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="padding: 3.5rem 1rem; text-align: center; color: #94A3B8;">
                                            No se encontraron registros de logs en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINADOR VISTA TABLA -->
                    @if($paginatedLogs->hasPages())
                        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                            <span style="font-size: 0.825rem; color: #94A3B8;">
                                Mostrando página <strong style="color: #FFFFFF;">{{ $paginatedLogs->currentPage() }}</strong> de <strong style="color: #FFFFFF;">{{ $paginatedLogs->lastPage() }}</strong>
                            </span>

                            <div style="display: flex; gap: 0.35rem; align-items: center;">
                                @if($paginatedLogs->onFirstPage())
                                    <span class="dt-page-btn disabled">‹</span>
                                @else
                                    <a href="{{ $paginatedLogs->previousPageUrl() }}" class="dt-page-btn">‹</a>
                                @endif

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

    <!-- MODAL VACIAR / REINICIAR LOGS -->
    <div class="modal-backdrop-custom" id="clearLogModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px); padding: 1rem;">
        <div style="background: #0F172A; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 20px; width: 100%; max-width: 480px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #EF4444; font-size: 1.3rem; display: flex; align-items: center; justify-content: center;">
                        🗑️
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 900; color: #FFF; margin: 0;">Vaciar Archivo de Logs</h3>
                        <p style="color: #94A3B8; font-size: 0.8rem; margin: 0.2rem 0 0 0;">Esta acción borrará el historial de checkout.log</p>
                    </div>
                </div>
                <button type="button" onclick="closeClearLogModal()" style="background: rgba(255,255,255,0.08); border: none; color: #FFF; width: 32px; height: 32px; border-radius: 8px; cursor: pointer;">✕</button>
            </div>

            <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <p style="color: #E2E8F0; font-size: 0.9rem; margin: 0 0 0.5rem 0; line-height: 1.5;">
                    ¿Confirmas que deseas reiniciar y truncar el archivo <code style="color:#FF7700">storage/logs/checkout.log</code>?
                </p>
                <small style="color: #94A3B8;">Los eventos nuevos que ocurran a partir de este momento se seguirán registrando normalmente.</small>
            </div>

            <form action="{{ route('web.checkout_logs.clear') }}" method="POST" style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                @csrf
                <button type="button" onclick="closeClearLogModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: #E2E8F0; padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; cursor: pointer;">
                    Cancelar
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #EF4444, #DC2626); border: none; color: #FFF; padding: 0.65rem 1.5rem; font-weight: 800; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <span>🗑️</span> <span>Sí, Vaciar Logs</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL DETALLE JSON CONTEXT -->
    <div class="modal-backdrop-custom" id="logDetailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(8px); padding: 1rem;">
        <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; width: 100%; max-width: 680px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6); overflow: hidden;">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #FFF; margin: 0;" id="logDetailModalTitle">Detalle de Transacción</h3>
                    <p style="color: #94A3B8; font-size: 0.8rem; margin: 0.2rem 0 0 0;" id="logDetailModalTime">...</p>
                </div>
                <button type="button" onclick="closeLogDetailModal()" style="background: rgba(255,255,255,0.08); border: none; color: #FFF; width: 32px; height: 32px; border-radius: 8px; cursor: pointer;">✕</button>
            </div>

            <div style="padding: 1.25rem 1.5rem; overflow-y: auto; flex: 1;">
                <pre id="logDetailModalJson" style="background: #05070A; border: 1px solid rgba(255,255,255,0.08); padding: 1rem; border-radius: 12px; color: #E2E8F0; font-family: monospace; font-size: 0.825rem; white-space: pre-wrap; word-break: break-word;"></pre>
            </div>

            <div style="padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: flex-end;">
                <button type="button" onclick="closeLogDetailModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: #E2E8F0; padding: 0.55rem 1.25rem; font-weight: 700; border-radius: 8px; cursor: pointer;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPTS JS PARA TERMINAL EN VIVO Y STREAMING AJAX -->
    <script>
        let isStreamActive = true;
        let isAutoScrollEnabled = true;
        let streamInterval = null;
        let currentTerminalCategory = 'ALL';
        let currentTerminalSearch = '';
        let seenLogTimestamps = new Set();

        // Inicializar registro de marcas de tiempo existentes en la terminal
        document.querySelectorAll('#terminalBody .terminal-line').forEach(line => {
            const timeEl = line.querySelector('.t-time');
            if (timeEl) seenLogTimestamps.add(timeEl.textContent.trim());
        });

        // Auto-scroll inicial al final de la terminal
        window.addEventListener('DOMContentLoaded', () => {
            scrollTerminalToBottom();
            startLiveStreamFeed();
        });

        function scrollTerminalToBottom() {
            if (!isAutoScrollEnabled) return;
            const term = document.getElementById('terminalBody');
            if (term) {
                term.scrollTop = term.scrollHeight;
            }
        }

        function toggleAutoScroll() {
            isAutoScrollEnabled = !isAutoScrollEnabled;
            const btn = document.getElementById('btnToggleAutoScroll');
            const txt = document.getElementById('autoScrollText');
            if (isAutoScrollEnabled) {
                btn.classList.add('active');
                txt.textContent = 'Auto-Scroll: ON';
                scrollTerminalToBottom();
            } else {
                btn.classList.remove('active');
                txt.textContent = 'Auto-Scroll: OFF';
            }
        }

        function toggleStreamFeed() {
            isStreamActive = !isStreamActive;
            const icon = document.getElementById('streamIcon');
            const txt = document.getElementById('streamText');
            const dot = document.getElementById('headerLiveDot');
            const statusLabel = document.getElementById('headerLiveStatus');

            if (isStreamActive) {
                icon.textContent = '⏸️';
                txt.textContent = 'Pausar Stream';
                if (dot) dot.style.background = '#10B981';
                if (statusLabel) statusLabel.textContent = 'Transmisión en Vivo';
                startLiveStreamFeed();
            } else {
                icon.textContent = '▶️';
                txt.textContent = 'Reanudar Stream';
                if (dot) dot.style.background = '#EF4444';
                if (statusLabel) statusLabel.textContent = 'Stream Pausado';
                if (streamInterval) clearInterval(streamInterval);
            }
        }

        function startLiveStreamFeed() {
            if (streamInterval) clearInterval(streamInterval);
            streamInterval = setInterval(() => {
                if (!isStreamActive) return;
                fetchLiveFeedLogs();
            }, 2500);
        }

        function fetchLiveFeedLogs() {
            fetch("{{ route('web.checkout_logs.feed') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.logs && data.logs.length > 0) {
                        const term = document.getElementById('terminalBody');
                        if (!term) return;

                        // Actualizar contadores
                        if (data.stats) {
                            if (document.getElementById('statTotal')) document.getElementById('statTotal').textContent = data.stats.total.toLocaleString();
                            if (document.getElementById('statCulqi')) document.getElementById('statCulqi').textContent = data.stats.culqi.toLocaleString();
                            if (document.getElementById('statIzipay')) document.getElementById('statIzipay').textContent = data.stats.izipay.toLocaleString();
                            if (document.getElementById('statErrors')) document.getElementById('statErrors').textContent = data.stats.errors.toLocaleString();
                        }

                        // Las líneas llegan ordenadas por lo más reciente primero, revertir para imprimir en orden
                        const newLogs = [...data.logs].reverse();
                        let appended = false;

                        newLogs.forEach(log => {
                            const timeKey = `[${log.timestamp}]`;
                            if (!seenLogTimestamps.has(timeKey)) {
                                seenLogTimestamps.add(timeKey);
                                const lineEl = createTerminalLineElement(log, true);
                                term.appendChild(lineEl);
                                appended = true;
                            }
                        });

                        if (appended) {
                            scrollTerminalToBottom();
                        }
                    }
                })
                .catch(err => {
                    // Fallback silencioso en caso de micro-corte de red
                });
        }

        function createTerminalLineElement(log, isNew = false) {
            const div = document.createElement('div');
            div.className = 'terminal-line' + (isNew ? ' log-new-flash' : '');
            
            const searchable = (log.timestamp + ' ' + log.level + ' ' + log.gateway + ' ' + log.category + ' ' + log.message + ' ' + JSON.stringify(log.context || '')).toLowerCase();
            div.dataset.search = searchable;

            let levelClass = 'level-success';
            const lvl = (log.level || 'INFO').toUpperCase();
            if (['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'].includes(lvl)) levelClass = 'level-error';
            else if (lvl === 'WARNING') levelClass = 'level-warn';
            else if (lvl === 'DEBUG') levelClass = 'level-info';

            let gwClass = 'tag-system';
            const gw = (log.gateway || '').toUpperCase();
            if (gw.includes('CULQI')) gwClass = 'tag-culqi';
            else if (gw.includes('IZIPAY')) gwClass = 'tag-izipay';
            else if (gw.includes('CORTES')) gwClass = 'tag-cortesia';

            const hasCtx = log.context && typeof log.context === 'object' && Object.keys(log.context).length > 0;
            const jsonBtn = hasCtx ? `<button type="button" class="terminal-json-toggle" onclick="toggleInlineJson(this)"><span>▶</span> JSON</button><div class="terminal-json-block">${escapeHtml(JSON.stringify(log.context, null, 2))}</div>` : '';

            div.innerHTML = `
                <span class="t-time">[${escapeHtml(log.timestamp)}]</span>
                <span class="t-tag ${gwClass}">${escapeHtml(log.gateway)}</span>
                <span class="${levelClass}">[${escapeHtml(log.level)}]</span>
                <div class="terminal-msg">
                    <span>${escapeHtml(log.message)}</span>
                    ${jsonBtn}
                </div>
            `;

            return div;
        }

        function toggleInlineJson(btn) {
            const block = btn.nextElementSibling;
            if (!block) return;
            if (block.style.display === 'block') {
                block.style.display = 'none';
                btn.querySelector('span').textContent = '▶';
            } else {
                block.style.display = 'block';
                btn.querySelector('span').textContent = '▼';
            }
        }

        function filterTerminalLines(query) {
            currentTerminalSearch = (query || '').toLowerCase().trim();
            applyTerminalFilters();
        }

        function setTerminalCategoryFilter(cat) {
            currentTerminalCategory = cat;
            document.querySelectorAll('.term-filter-tag').forEach(b => {
                b.classList.toggle('active', b.dataset.cat === cat);
            });
            applyTerminalFilters();
        }

        function applyTerminalFilters() {
            const lines = document.querySelectorAll('#terminalBody .terminal-line');
            lines.forEach(line => {
                const searchData = line.dataset.search || '';
                let matchesSearch = true;
                let matchesCat = true;

                if (currentTerminalSearch && !searchData.includes(currentTerminalSearch)) {
                    matchesSearch = false;
                }

                if (currentTerminalCategory !== 'ALL') {
                    if (currentTerminalCategory === 'ERROR' && !searchData.includes('error') && !searchData.includes('critical') && !searchData.includes('warn')) {
                        matchesCat = false;
                    } else if (currentTerminalCategory === 'PAGO' && !searchData.includes('pago') && !searchData.includes('culqi') && !searchData.includes('izipay') && !searchData.includes('cargo')) {
                        matchesCat = false;
                    } else if (currentTerminalCategory === 'BOLETOS' && !searchData.includes('boleto') && !searchData.includes('ticket') && !searchData.includes('entrega')) {
                        matchesCat = false;
                    } else if (currentTerminalCategory === 'CORREO' && !searchData.includes('correo') && !searchData.includes('email') && !searchData.includes('mail')) {
                        matchesCat = false;
                    }
                }

                line.style.display = (matchesSearch && matchesCat) ? 'flex' : 'none';
            });
        }

        function copyTerminalLogs() {
            const term = document.getElementById('terminalBody');
            if (!term) return;
            let text = '';
            term.querySelectorAll('.terminal-line').forEach(l => {
                if (l.style.display !== 'none') {
                    text += l.innerText + '\n';
                }
            });
            navigator.clipboard.writeText(text).then(() => {
                alert('✓ Salida de la terminal copiada al portapapeles.');
            });
        }

        function clearTerminalScreen() {
            const term = document.getElementById('terminalBody');
            if (term) {
                term.innerHTML = '<div style="color: #64748B; text-align: center; padding: 4rem 1rem;"><span>⚡ Pantalla de terminal limpiada. Esperando nuevos eventos...</span></div>';
            }
        }

        function switchViewMode(mode) {
            const termBox = document.getElementById('terminalViewContainer');
            const tableBox = document.getElementById('tableViewContainer');
            const btnTerm = document.getElementById('btnSwitchTerminal');
            const btnTable = document.getElementById('btnSwitchTable');

            if (mode === 'terminal') {
                if (termBox) termBox.style.display = 'flex';
                if (tableBox) tableBox.style.display = 'none';
                if (btnTerm) btnTerm.classList.add('active');
                if (btnTable) btnTable.classList.remove('active');
                scrollTerminalToBottom();
            } else {
                if (termBox) termBox.style.display = 'none';
                if (tableBox) tableBox.style.display = 'block';
                if (btnTerm) btnTerm.classList.remove('active');
                if (btnTable) btnTable.classList.add('active');
            }
        }

        function openClearLogModal() {
            document.getElementById('clearLogModal').style.display = 'flex';
        }
        function closeClearLogModal() {
            document.getElementById('clearLogModal').style.display = 'none';
        }

        function showLogDetailModal(log) {
            document.getElementById('logDetailModalTitle').textContent = `[${log.level}] ${log.gateway} - ${log.category}`;
            document.getElementById('logDetailModalTime').textContent = `Timestamp: ${log.timestamp}`;
            document.getElementById('logDetailModalJson').textContent = JSON.stringify(log.context, null, 2);
            document.getElementById('logDetailModal').style.display = 'flex';
        }
        function closeLogDetailModal() {
            document.getElementById('logDetailModal').style.display = 'none';
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
@endsection
