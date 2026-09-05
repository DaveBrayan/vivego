@extends('layouts.app')

@section('title', 'Dispositivos & Scanners Móviles | Vive Go')

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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar dispositivo por nombre, modelo o UUID...">
                    <span class="dash-kbd-shortcut">⌘K</span>
                </div>

                <div class="dash-top-actions">
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema (Claro / Oscuro)">
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
                <div class="settings-header-banner" style="display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.9)); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 1.6rem 2rem; margin-bottom: 1.75rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <div>
                        <span class="settings-tag" style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.4); padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px;">📱 CONTROL DE ACCESO & APP FLUTTER</span>
                        <h1 class="settings-page-title" style="margin: 0.6rem 0 0.35rem 0; font-size: 1.85rem; font-weight: 900; color: #FFFFFF;">Dispositivos & Scanners Móviles</h1>
                        <p class="settings-page-subtitle" style="margin: 0; color: #94A3B8; font-size: 0.9rem;">Vincula teléfonos celulares mediante código QR para escanear entradas en puerta con la aplicación móvil ViveGo.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" id="btnOpenCreateDeviceModal" onclick="openCreateDeviceModal()" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; font-weight: 900; box-shadow: 0 4px 20px rgba(255, 85, 0, 0.45); border: none; border-radius: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.2s ease;">
                            <span>📱</span> + Vincular Nuevo Dispositivo
                        </button>
                    </div>
                </div>

                <!-- CARDS DE RESUMEN KPI -->
                <div class="dash-kpi-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #10B981; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📱</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Total Terminales</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #FFFFFF; margin-top: 2px;">{{ $totalDevices }}</div>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #3B82F6; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🟢</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Activos / En Línea</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #60A5FA; margin-top: 2px;">{{ $activeDevices }}</div>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #F59E0B; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">⏳</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Por Vincular</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #F59E0B; margin-top: 2px;">{{ $pendingDevices }}</div>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #8B5CF6; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(139, 92, 246, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">⚡</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Boletos Validados</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #C084FC; margin-top: 2px;">{{ number_format($totalScans) }}</div>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE DISPOSITIVOS REGISTRADOS -->
                <div class="settings-card-box" style="background: #14141E; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 1.5rem; box-shadow: 0 15px 35px rgba(0,0,0,0.4);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: gap; gap: 1rem;">
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 900; color: #FFFFFF; margin: 0;">Terminales Móviles Vinculadas</h3>
                            <p style="font-size: 0.8rem; color: #94A3B8; margin: 3px 0 0 0;">Cada dispositivo escanea con su nombre y reporta en tiempo real al panel central.</p>
                        </div>
                        <div style="font-size: 0.8rem; color: #64748B;">
                            IP del Servidor sugerida: <span style="font-family: monospace; color: #10B981; font-weight: 800;">{{ $detectedUrl }}</span>
                        </div>
                    </div>

                    @if($devices->isEmpty())
                        <div style="text-align: center; padding: 3.5rem 1rem; color: #94A3B8;">
                            <div style="font-size: 3.5rem; margin-bottom: 0.75rem;">📲</div>
                            <h4 style="font-size: 1.15rem; font-weight: 800; color: #FFFFFF; margin: 0 0 0.5rem 0;">Aún no tienes dispositivos registrados</h4>
                            <p style="font-size: 0.85rem; max-width: 480px; margin: 0 auto 1.5rem auto; line-height: 1.5;">Haz clic en <strong>+ Vincular Nuevo Dispositivo</strong> para crear una terminal (ej: "Puerta Principal", "Scanner VIP") y escanear su código QR con la app Flutter.</p>
                            <button type="button" class="btn btn-primary" onclick="openCreateDeviceModal()" style="padding: 0.85rem 1.6rem; border-radius: 12px; font-weight: 800; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);">
                                📱 Registrar mi Primer Dispositivo
                            </button>
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                                <thead>
                                    <tr style="border-bottom: 1.5px solid rgba(255,255,255,0.1); color: #94A3B8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <th style="padding: 0.85rem 1rem;">Dispositivo</th>
                                        <th style="padding: 0.85rem 1rem;">Estado</th>
                                        <th style="padding: 0.85rem 1rem;">Eventos Asignados</th>
                                        <th style="padding: 0.85rem 1rem;">Modelo de Dispositivo</th>
                                        <th style="padding: 0.85rem 1rem; text-align: center;">Escaneos</th>
                                        <th style="padding: 0.85rem 1rem;">Última Actividad</th>
                                        <th style="padding: 0.85rem 1rem; text-align: right;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devices as $dev)
                                        @php
                                            $isOnline = $dev->isOnline();
                                            $hasAssignedEvents = !empty($dev->assigned_events) && count($dev->assigned_events) > 0;
                                            $assignedEventsList = $dev->getAssignedEventsList();
                                        @endphp
                                        <tr class="device-row" data-name="{{ strtolower($dev->name) }}" data-uuid="{{ strtolower($dev->device_uuid) }}" style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s ease;">
                                            <td style="padding: 1rem;">
                                                <div style="font-weight: 900; color: #FFFFFF; font-size: 0.95rem;">{{ $dev->name }}</div>
                                                <div style="font-size: 0.72rem; color: #64748B; font-family: monospace; margin-top: 2px;">UUID: {{ $dev->device_uuid }}</div>
                                            </td>
                                            <td style="padding: 1rem;">
                                                @if($dev->status === 'active')
                                                    @if($isOnline)
                                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #10B981; box-shadow: 0 0 8px #10B981;"></span> EN LÍNEA
                                                        </span>
                                                    @else
                                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: rgba(59, 130, 246, 0.12); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3);">
                                                            ✓ VINCULADO
                                                        </span>
                                                    @endif
                                                @elseif($dev->status === 'pending')
                                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3);">
                                                        ⏳ ESPERANDO QR
                                                    </span>
                                                @else
                                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                                                        🚫 BLOQUEADO
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="padding: 1rem;">
                                                @if(!$hasAssignedEvents)
                                                    <span style="font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 8px; background: rgba(148, 163, 184, 0.15); color: #CBD5E1; border: 1px solid rgba(148, 163, 184, 0.25); font-weight: 700;">
                                                        🌐 Todos los Eventos
                                                    </span>
                                                @else
                                                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; max-width: 280px;">
                                                        @foreach($assignedEventsList->take(2) as $aEvt)
                                                            <span style="font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 6px; background: rgba(139, 92, 246, 0.15); color: #C084FC; border: 1px solid rgba(139, 92, 246, 0.3); font-weight: 700; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 130px;" title="{{ $aEvt->title }}">
                                                                🎟️ {{ $aEvt->title }}
                                                            </span>
                                                        @endforeach
                                                        @if($assignedEventsList->count() > 2)
                                                             <span style="font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 6px; background: rgba(255,255,255,0.06); color: #94A3B8; font-weight: 700;">
                                                                +{{ $assignedEventsList->count() - 2 }} más
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="padding: 1rem;">
                                                @if($dev->device_model)
                                                    <div style="color: #FFFFFF; font-weight: 800; font-size: 0.9rem;">📱 {{ $dev->device_model }}</div>
                                                @elseif($dev->status === 'active')
                                                    <div style="color: #FFFFFF; font-weight: 800; font-size: 0.9rem;">📱 {{ $dev->name }}</div>
                                                @else
                                                    <span style="color: #64748B; font-style: italic;">Sin vincular aún</span>
                                                @endif
                                            </td>
                                            <td style="padding: 1rem; text-align: center;">
                                                <span style="font-family: monospace; font-size: 1rem; font-weight: 900; color: #10B981;">{{ $dev->scans_count }}</span>
                                            </td>
                                            <td style="padding: 1rem;">
                                                @if($dev->last_activity_at)
                                                    <div style="color: #CBD5E1;">{{ $dev->last_activity_at->diffForHumans() }}</div>
                                                    <div style="font-size: 0.72rem; color: #64748B;">{{ $dev->last_activity_at->format('d/m H:i') }} (IP: {{ $dev->last_ip ?: 'Local' }})</div>
                                                @else
                                                    <span style="color: #64748B;">Nunca</span>
                                                @endif
                                            </td>
                                            <td style="padding: 1rem; text-align: right;">
                                                <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                                    <!-- Vincular con QR (Solo visible si el dispositivo aún no está activo ni en línea) -->
                                                    @if($dev->status !== 'active' && !$isOnline)
                                                        <button type="button" class="btn btn-sm" onclick="showPairingQrModal({{ $dev->id }}, '{{ addslashes($dev->name) }}', '{{ $dev->device_uuid }}', '{{ $dev->pairing_token }}')" title="Vincular con la App Móvil (Código QR)" style="background: rgba(255, 85, 0, 0.15); border: 1.5px solid #FF5500; color: #FF5500; padding: 0.45rem 0.85rem; border-radius: 10px; cursor: pointer; font-size: 0.8rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s ease;">
                                                            <span>🔗</span> Vincular QR
                                                        </button>
                                                    @endif

                                                    <!-- Editar Eventos -->
                                                    <button type="button" class="btn btn-sm" onclick="openEditDeviceModal({{ $dev->id }}, '{{ addslashes($dev->name) }}', {{ json_encode($dev->assigned_events ?: []) }})" title="Editar Configuración y Eventos" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); color: #60A5FA; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer; font-size: 0.78rem; font-weight: 800;">
                                                        ✏️
                                                    </button>

                                                    <!-- Bloquear / Reactivar -->
                                                    <button type="button" class="btn btn-sm" onclick="toggleDeviceStatus({{ $dev->id }}, '{{ $dev->status }}')" title="{{ $dev->status === 'revoked' ? 'Reactivar Dispositivo' : 'Bloquear / Revocar' }}" style="background: {{ $dev->status === 'revoked' ? 'rgba(16, 185, 129, 0.15)' : 'rgba(245, 158, 11, 0.15)' }}; border: 1px solid {{ $dev->status === 'revoked' ? 'rgba(16, 185, 129, 0.4)' : 'rgba(245, 158, 11, 0.4)' }}; color: {{ $dev->status === 'revoked' ? '#10B981' : '#F59E0B' }}; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer; font-size: 0.78rem; font-weight: 800;">
                                                        {{ $dev->status === 'revoked' ? '🔓' : '🔒' }}
                                                    </button>

                                                    <!-- Eliminar -->
                                                    <button type="button" class="btn btn-sm" onclick="deleteDevice({{ $dev->id }}, '{{ addslashes($dev->name) }}')" title="Eliminar Dispositivo" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #EF4444; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer; font-size: 0.78rem; font-weight: 800;">
                                                        🗑️
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- ========================================================================= -->
    <!-- ESTILOS EXCLUSIVOS PARA MODALES DE DISPOSITIVOS                           -->
    <!-- ========================================================================= -->
    <style>
        .custom-device-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(10, 10, 16, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            box-sizing: border-box;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .custom-device-modal-overlay.active {
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .custom-device-modal-card {
            background: #14141E;
            border: 1.5px solid rgba(255, 85, 0, 0.4);
            border-radius: 26px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.8);
            color: #FFFFFF;
            width: 100%;
            transform: scale(0.94) translateY(15px);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-sizing: border-box;
        }

        .custom-device-modal-overlay.active .custom-device-modal-card {
            transform: scale(1) translateY(0);
        }

        @keyframes devicePulseSpin {
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- ========================================================================= -->
    <!-- MODAL 1: REGISTRAR / VINCULAR NUEVO DISPOSITIVO MÓVIL (2 COLUMNAS PRO)     -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="createDeviceModal">
        <div class="custom-device-modal-card" style="max-width: 920px; width: 95%; max-height: 90vh; overflow-y: auto; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(255, 85, 0, 0.15); border: 1.5px solid rgba(255, 85, 0, 0.4); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📱</div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.3rem; font-weight: 900; color: #FFFFFF;">Vincular Nuevo Dispositivo</h3>
                        <p style="margin: 3px 0 0 0; font-size: 0.82rem; color: #94A3B8;">Configura la terminal de escaneo y asígnale los eventos autorizados con su aforo.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateDeviceModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 36px; height: 36px; font-size: 1.2rem; color: #CBD5E1; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <form id="createDeviceForm" onsubmit="submitCreateDevice(event)">
                @csrf
                <!-- GRID EN 2 COLUMNAS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.75rem; align-items: start; margin-bottom: 1.5rem;">
                    <!-- COLUMNA IZQUIERDA: DATOS DEL DISPOSITIVO -->
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin-bottom: 0.4rem;">
                                Nombre del Dispositivo / Puerta <span style="color: #EF4444;">*</span>
                            </label>
                            <input type="text" id="dev_name" name="name" required class="dash-search-input" placeholder="Ej: Puerta Principal 1, Scanner VIP, Móvil Operador" style="width: 100%; box-sizing: border-box; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 0.8rem 1rem; color: #FFFFFF; font-size: 0.92rem;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin-bottom: 0.4rem;">
                                URL del Servidor ViveGo (IP Local o Dominio Web)
                            </label>
                            <input type="text" id="dev_server_url" name="server_url" value="{{ $detectedUrl }}" class="dash-search-input" style="width: 100%; box-sizing: border-box; background: rgba(255,255,255,0.05); border: 1px solid rgba(255, 85, 0, 0.4); border-radius: 12px; padding: 0.8rem 1rem; color: #FF5500; font-family: monospace; font-size: 0.9rem; font-weight: 800;">
                            <span style="display: block; font-size: 0.73rem; color: #64748B; margin-top: 5px;">Asegúrate de que el teléfono esté en la misma red Wi-Fi si usas una IP local.</span>
                        </div>

                        <!-- BANNER TIP DE VINCULACIÓN -->
                        <div style="background: rgba(255, 85, 0, 0.08); border: 1px solid rgba(255, 85, 0, 0.25); border-radius: 16px; padding: 1rem; display: flex; gap: 0.85rem; align-items: flex-start;">
                            <span style="font-size: 1.5rem; line-height: 1;">⚡</span>
                            <div style="font-size: 0.78rem; color: #CBD5E1; line-height: 1.45;">
                                <strong style="color: #FF7722; display: block; margin-bottom: 3px;">Vinculación Instantánea por QR</strong>
                                Al hacer clic en <strong>Generar Código QR</strong>, la pantalla mostrará el código QR. Solo debes apuntar la cámara de la aplicación móvil ViveGo y quedará vinculada en segundos.
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: EVENTOS Y AFORO -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                            <label style="font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin: 0;">
                                Eventos Autorizados para Escaneo
                            </label>
                            <button type="button" onclick="toggleAllEventCheckboxes()" style="background: none; border: none; color: #FF5500; font-size: 0.78rem; font-weight: 800; cursor: pointer;">
                                Marcar / Desmarcar Todos
                            </button>
                        </div>

                        <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem;">
                            <label style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.8rem; border-radius: 10px; cursor: pointer; background: rgba(255, 85, 0, 0.12); border: 1px solid rgba(255, 85, 0, 0.3); margin-bottom: 0.6rem;">
                                <input type="checkbox" id="chk_all_events" onchange="onToggleAllEvents(this)" checked style="accent-color: #FF5500; transform: scale(1.2);">
                                <div>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: #FF7722;">🌐 Acceso Global</div>
                                    <div style="font-size: 0.72rem; color: #94A3B8;">Permitir escanear boletos de todos los eventos actuales y futuros</div>
                                </div>
                            </label>

                            <div id="individual_events_box" style="display: none; max-height: 280px; overflow-y: auto; padding-right: 4px; flex-direction: column; gap: 0.5rem;">
                                @foreach($allEvents as $ev)
                                    <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.65rem 0.75rem; border-radius: 10px; cursor: pointer; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); margin-bottom: 0.4rem; transition: all 0.2s ease;">
                                        <input type="checkbox" name="assigned_events[]" value="{{ $ev->id }}" class="event-chk" style="accent-color: #FF5500; margin-top: 3px; transform: scale(1.15);">
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $ev->title }}
                                            </div>
                                            <div style="font-size: 0.72rem; color: #94A3B8; margin-top: 2px;">
                                                📅 {{ $ev->event_date ? (is_string($ev->event_date) ? substr($ev->event_date, 0, 10) : $ev->event_date->format('d/m/Y')) : 'S/F' }} • 📍 {{ $ev->venue_name ?: 'Recinto' }}
                                            </div>
                                            <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 5px;">
                                                <span style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800;">
                                                    👥 Aforo: {{ number_format($ev->total_capacity) }}
                                                </span>
                                                <span style="background: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3); padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800;">
                                                    🎟️ {{ number_format($ev->tickets_count) }} Boletos
                                                </span>
                                                <span style="background: rgba(139, 92, 246, 0.15); color: #C084FC; border: 1px solid rgba(139, 92, 246, 0.3); padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800;">
                                                    ✅ {{ number_format($ev->tickets_used) }} Escaneados
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.25rem;">
                    <button type="button" onclick="closeCreateDeviceModal()" style="padding: 0.75rem 1.4rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #CBD5E1; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitDevice" style="padding: 0.75rem 1.8rem; background: linear-gradient(135deg, #FF5500, #E64A00); border: none; color: #FFFFFF; border-radius: 12px; font-weight: 900; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);">
                        <span>🔲</span> Generar Código QR
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: CÓDIGO QR DE VINCULACIÓN CON LA APP FLUTTER                      -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="pairingQrModal">
        <div class="custom-device-modal-card" style="max-width: 480px; padding: 2.2rem; text-align: center;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 0.75rem; font-weight: 900; background: rgba(255, 85, 0, 0.15); color: #FF5500; border: 1px solid rgba(255, 85, 0, 0.4); padding: 0.25rem 0.75rem; border-radius: 9999px;">
                    VINCULACIÓN POR QR VIVEGO
                </span>
                <button type="button" onclick="closePairingQrModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 34px; height: 34px; font-size: 1.1rem; color: #CBD5E1; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <h3 id="qrModalDeviceName" style="margin: 0 0 0.35rem 0; font-size: 1.45rem; font-weight: 900; color: #FFFFFF;">Puerta Principal</h3>
            <p style="font-size: 0.85rem; color: #94A3B8; margin: 0 0 1.25rem 0;">Apunta la cámara de la App ViveGo en tu celular a este código para vincularlo al instante.</p>

            <!-- CONTENEDOR DEL CÓDIGO QR -->
            <div style="background: #FFFFFF; padding: 1.2rem; border-radius: 22px; display: inline-block; box-shadow: 0 10px 35px rgba(255, 85, 0, 0.3); margin-bottom: 1.25rem;">
                <div id="pairingQrCanvas" style="width: 220px; height: 220px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #64748B; font-size: 0.8rem;">Generando código QR...</span>
                </div>
            </div>

            <!-- DETALLES DE CONEXIÓN -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; color: #94A3B8; margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span>Servidor:</span>
                    <span id="qrModalServerUrl" style="font-family: monospace; font-weight: 800; color: #FF5500;">http://...</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>UUID:</span>
                    <span id="qrModalUuid" style="font-family: monospace; font-weight: 700; color: #CBD5E1;">...</span>
                </div>
            </div>

            <div id="pairingWaitingStatus" style="font-size: 0.82rem; color: #F59E0B; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <span style="display: inline-block; width: 14px; height: 14px; border: 2px solid #F59E0B; border-right-color: transparent; border-radius: 50%; animation: devicePulseSpin 0.8s linear infinite;"></span>
                Esperando que el teléfono escanee el código...
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.75rem;">
                <button type="button" onclick="copyPairingJson()" style="padding: 0.7rem 1.3rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #CBD5E1; border-radius: 12px; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                    📋 Copiar Configuración
                </button>
                <button type="button" onclick="closePairingQrModal()" style="padding: 0.7rem 1.6rem; background: linear-gradient(135deg, #FF5500, #E64A00); border: none; color: #FFFFFF; border-radius: 12px; font-weight: 900; font-size: 0.82rem; cursor: pointer;">
                    Listo / Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: EDITAR EVENTOS ASIGNADOS AL DISPOSITIVO                           -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="editDeviceModal">
        <div class="custom-device-modal-card" style="max-width: 650px; width: 95%; max-height: 90vh; overflow-y: auto; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.85rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">✏️</div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: #FFFFFF;">Editar Dispositivo</h3>
                </div>
                <button type="button" onclick="closeEditDeviceModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 34px; height: 34px; font-size: 1.1rem; color: #CBD5E1; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <form id="editDeviceForm" onsubmit="submitEditDevice(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_dev_id">

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin-bottom: 0.4rem;">
                        Nombre del Dispositivo
                    </label>
                    <input type="text" id="edit_dev_name" name="name" required class="dash-search-input" style="width: 100%; box-sizing: border-box; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 0.75rem 1rem; color: #FFFFFF; font-size: 0.92rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin-bottom: 0.5rem;">
                        Eventos Autorizados para este Dispositivo
                    </label>
                    <div style="max-height: 280px; overflow-y: auto; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.45rem;">
                        @foreach($allEvents as $ev)
                            <label style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.6rem 0.75rem; border-radius: 8px; cursor: pointer; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); transition: all 0.2s ease;">
                                <input type="checkbox" name="assigned_events[]" value="{{ $ev->id }}" class="edit-event-chk" style="accent-color: #FF5500; margin-top: 3px; transform: scale(1.15);">
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $ev->title }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #94A3B8; margin-top: 2px;">
                                        📅 {{ $ev->event_date ? (is_string($ev->event_date) ? substr($ev->event_date, 0, 10) : $ev->event_date->format('d/m/Y')) : 'S/F' }} • 📍 {{ $ev->venue_name ?: 'Recinto' }}
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 5px;">
                                        <span style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800;">
                                            👥 Aforo: {{ number_format($ev->total_capacity) }}
                                        </span>
                                        <span style="background: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3); padding: 0.15rem 0.45rem; border-radius: 6px; font-size: 0.68rem; font-weight: 800;">
                                            🎟️ {{ number_format($ev->tickets_count) }} Boletos
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.25rem;">
                    <button type="button" onclick="closeEditDeviceModal()" style="padding: 0.75rem 1.4rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #CBD5E1; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="padding: 0.75rem 1.8rem; background: linear-gradient(135deg, #FF5500, #E64A00); border: none; color: #FFFFFF; border-radius: 12px; font-weight: 900; cursor: pointer;">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Oficial -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Librerías QRCode Generator para renderizado instantáneo en el navegador -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    <script>
        const csrfToken = "{{ csrf_token() }}";
        const defaultServerUrl = "{{ $detectedUrl }}";
        let activePollingInterval = null;
        let currentActivePayload = '';

        // Filtro buscador instantáneo en la tabla
        document.getElementById('tableFilterInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.device-row').forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const uuid = row.getAttribute('data-uuid') || '';
                row.style.display = (name.includes(query) || uuid.includes(query)) ? '' : 'none';
            });
        });

        // Alternador de Todos los Eventos vs Eventos Específicos
        function onToggleAllEvents(checkbox) {
            const indBox = document.getElementById('individual_events_box');
            if (checkbox.checked) {
                if (indBox) indBox.style.display = 'none';
                document.querySelectorAll('.event-chk').forEach(c => c.checked = false);
            } else {
                if (indBox) indBox.style.display = 'block';
                document.querySelectorAll('.event-chk').forEach(c => c.checked = true);
            }
        }

        function toggleAllEventCheckboxes() {
            const allChk = document.getElementById('chk_all_events');
            if (allChk) {
                allChk.checked = !allChk.checked;
                onToggleAllEvents(allChk);
            }
        }

        // ==========================================
        // FUNCIONES ROBUSTAS DE APERTURA DE MODALES
        // ==========================================
        function openCreateDeviceModal() {
            const modal = document.getElementById('createDeviceModal');
            if (!modal) return;
            modal.style.display = 'flex';
            // Reflow para activar animación
            void modal.offsetWidth;
            modal.classList.add('active');
            document.getElementById('dev_name')?.focus();
        }

        function closeCreateDeviceModal() {
            const modal = document.getElementById('createDeviceModal');
            if (!modal) return;
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 250);
        }

        // Envío para registrar dispositivo
        async function submitCreateDevice(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitDevice');
            if (btn) btn.disabled = true;

            const form = document.getElementById('createDeviceForm');
            const formData = new FormData(form);

            const isAllEvents = document.getElementById('chk_all_events')?.checked;
            if (isAllEvents) {
                formData.delete('assigned_events[]');
            }

            try {
                const res = await fetch("{{ route('web.devices.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    closeCreateDeviceModal();
                    // Mostrar inmediatamente el código QR de vinculación
                    showPairingQrModal(data.device.id, data.device.name, data.device.device_uuid, data.device.pairing_token);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo registrar el dispositivo.',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            } catch(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: err.message,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            } finally {
                if (btn) btn.disabled = false;
            }
        }

        // Mostrar Modal con el Código QR de Vinculación
        function showPairingQrModal(deviceId, deviceName, deviceUuid, pairingToken) {
            const serverUrl = document.getElementById('dev_server_url')?.value || defaultServerUrl;

            document.getElementById('qrModalDeviceName').textContent = deviceName;
            document.getElementById('qrModalServerUrl').textContent = serverUrl;
            document.getElementById('qrModalUuid').textContent = (deviceUuid || '').substring(0, 18) + '...';

            const payloadObj = {
                vivego_pair: true,
                server_url: serverUrl.replace(/\/$/, ''),
                device_uuid: deviceUuid,
                token: pairingToken,
                name: deviceName,
                timestamp: Math.floor(Date.now() / 1000)
            };

            currentActivePayload = JSON.stringify(payloadObj);

            // Generar QR con librería local o fallback API
            const qrBox = document.getElementById('pairingQrCanvas');
            if (qrBox) {
                let qrRendered = false;
                if (typeof qrcode !== 'undefined') {
                    try {
                        const qr = qrcode(0, 'M');
                        qr.addData(currentActivePayload);
                        qr.make();
                        qrBox.innerHTML = qr.createImgTag(5, 0);
                        const img = qrBox.querySelector('img');
                        if (img) {
                            img.style.width = '100%';
                            img.style.height = '100%';
                            img.style.display = 'block';
                            qrRendered = true;
                        }
                    } catch(e) {
                        console.warn('Error con qrcode-generator, intentando fallback:', e);
                    }
                }

                if (!qrRendered) {
                    // Fallback visual inmediato garantizado
                    const encodedPayload = encodeURIComponent(currentActivePayload);
                    qrBox.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodedPayload}" style="width: 100%; height: 100%; display: block;" alt="Código QR de Vinculación">`;
                }
            }

            const modal = document.getElementById('pairingQrModal');
            if (modal) {
                modal.style.display = 'flex';
                void modal.offsetWidth;
                modal.classList.add('active');
            }

            // Polling rápido para detectar cuando el teléfono escanee el código
            if (activePollingInterval) clearInterval(activePollingInterval);
            activePollingInterval = setInterval(async () => {
                try {
                    const res = await fetch(`/api/gate/pair-check/${deviceId}`);
                    if (res.ok) {
                        const checkData = await res.json();
                        if (checkData.paired === true || checkData.is_active === true) {
                            clearInterval(activePollingInterval);
                            const statusDiv = document.getElementById('pairingWaitingStatus');
                            if (statusDiv) {
                                statusDiv.innerHTML = '<span style="color: #10B981; font-weight: 900; font-size: 0.95rem;">🎉 ¡Dispositivo vinculado con éxito!</span>';
                            }
                            Swal.fire({
                                icon: 'success',
                                title: '¡Vinculación Exitosa!',
                                text: `La terminal "${deviceName}" se ha vinculado correctamente y ya está lista para escanear.`,
                                timer: 1800,
                                showConfirmButton: false,
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                            setTimeout(() => window.location.reload(), 1600);
                        }
                    }
                } catch(e) {}
            }, 1800);
        }

        function closePairingQrModal() {
            if (activePollingInterval) clearInterval(activePollingInterval);
            const modal = document.getElementById('pairingQrModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 250);
            }
            window.location.reload();
        }

        function copyPairingJson() {
            if (currentActivePayload) {
                navigator.clipboard.writeText(currentActivePayload);
                Swal.fire({
                    icon: 'success',
                    title: '¡Copiado!',
                    text: 'Configuración JSON copiada al portapapeles.',
                    timer: 1500,
                    showConfirmButton: false,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }
        }

        // Editar eventos del dispositivo
        function openEditDeviceModal(id, name, assignedEvents) {
            document.getElementById('edit_dev_id').value = id;
            document.getElementById('edit_dev_name').value = name;

            const eventsArray = Array.isArray(assignedEvents) ? assignedEvents.map(Number) : [];
            document.querySelectorAll('.edit-event-chk').forEach(c => {
                c.checked = eventsArray.includes(parseInt(c.value, 10));
            });

            const modal = document.getElementById('editDeviceModal');
            if (modal) {
                modal.style.display = 'flex';
                void modal.offsetWidth;
                modal.classList.add('active');
            }
        }

        function closeEditDeviceModal() {
            const modal = document.getElementById('editDeviceModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 250);
            }
        }

        async function submitEditDevice(e) {
            e.preventDefault();
            const id = document.getElementById('edit_dev_id').value;
            const form = document.getElementById('editDeviceForm');
            const formData = new FormData(form);

            try {
                const res = await fetch(`/admin/dispositivos/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    closeEditDeviceModal();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado con Éxito!',
                        text: data.message || 'El dispositivo ha sido actualizado correctamente.',
                        timer: 1600,
                        showConfirmButton: false,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                    setTimeout(() => window.location.reload(), 1400);
                }
            } catch(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }
        }

        // Bloquear / Desbloquear dispositivo
        async function toggleDeviceStatus(id, currentStatus) {
            const actionText = currentStatus === 'revoked' ? 'reactivar' : 'bloquear';
            const confirm = await Swal.fire({
                title: `¿Deseas ${actionText} este dispositivo?`,
                text: currentStatus === 'revoked' 
                    ? 'El dispositivo volverá a poder escanear boletos en puerta.' 
                    : 'El dispositivo no podrá escanear más boletos hasta que lo reactives.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: currentStatus === 'revoked' ? '#10B981' : '#F59E0B',
                confirmButtonText: `Sí, ${actionText}`,
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            });

            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch(`/admin/dispositivos/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    window.location.reload();
                }
            } catch(err) {
                Swal.fire({ icon: 'error', title: 'Error', text: err.message, background: '#14141E', color: '#FFFFFF' });
            }
        }

        // Eliminar dispositivo
        async function deleteDevice(id, name) {
            const confirm = await Swal.fire({
                title: `¿Eliminar terminal "${name}"?`,
                text: 'Esta acción removerá el dispositivo del sistema permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            });

            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch(`/admin/dispositivos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    window.location.reload();
                }
            } catch(err) {
                Swal.fire({ icon: 'error', title: 'Error', text: err.message, background: '#14141E', color: '#FFFFFF' });
            }
        }

        // Cerrar modales haciendo click en el fondo oscuro
        ['createDeviceModal', 'pairingQrModal', 'editDeviceModal'].forEach(modalId => {
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                modalEl.addEventListener('click', function(e) {
                    if (e.target === modalEl) {
                        if (modalId === 'createDeviceModal') closeCreateDeviceModal();
                        else if (modalId === 'pairingQrModal') closePairingQrModal();
                        else if (modalId === 'editDeviceModal') closeEditDeviceModal();
                    }
                });
            }
        });
    </script>
@endsection
