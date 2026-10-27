@extends('layouts.app')

@section('title', 'Dispositivos & Scanners de Acceso | Vive Go')

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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar terminal por nombre, evento o PIN...">
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
                <div class="settings-header-banner" style="display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(10, 15, 30, 0.95)); border: 1.5px solid rgba(0, 240, 255, 0.25); border-radius: 20px; padding: 1.6rem 2rem; margin-bottom: 1.5rem; box-shadow: 0 10px 35px rgba(0,0,0,0.4);">
                    <div>
                        <span class="settings-tag" style="background: rgba(0, 240, 255, 0.12); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.4); padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px;">
                            🔐 CONTROL DE ACCESO & SCANNERS
                        </span>
                        <h1 class="settings-page-title" style="margin: 0.6rem 0 0.35rem 0; font-size: 1.85rem; font-weight: 900; color: #FFFFFF;">
                            Dispositivos & Scanners
                        </h1>
                        <p class="settings-page-subtitle" style="margin: 0; color: #94A3B8; font-size: 0.9rem;">
                            Gestiona terminales de validación: <strong>Scanners Web</strong> con PIN de 4 dígitos para celulares y <strong>Dispositivos App</strong> con código QR.
                        </p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
                        <button type="button" class="btn" onclick="openCreateWebScannerModal()" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem; background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; box-shadow: 0 4px 20px rgba(0, 240, 255, 0.35); border: none; border-radius: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.2s ease;">
                            <span>🌐</span> + Crear Scanner Web
                        </button>
                        <button type="button" class="btn" onclick="openCreateDeviceModal()" style="white-space: nowrap; padding: 0.85rem 1.4rem; font-size: 0.92rem; background: rgba(255, 85, 0, 0.15); border: 1.5px solid #FF5500; color: #FF7733; font-weight: 800; border-radius: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
                            <span>📱</span> + Vincular App Móvil
                        </button>
                    </div>
                </div>

                <!-- CARDS DE RESUMEN KPI -->
                <div class="dash-kpi-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #00F0FF; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(0, 240, 255, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🌐</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Scanners Web (PIN)</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #00F0FF; margin-top: 2px;">{{ $totalWebScanners }}</div>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #FF5500; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 85, 0, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📱</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Terminales App (QR)</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #FF7733; margin-top: 2px;">{{ $totalAppDevices }}</div>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin-bottom: 0; padding: 1.25rem 1.4rem; display: flex; align-items: center; gap: 1rem; border-left: 4px solid #10B981; background: #14141E; border-radius: 16px; border: 1px solid rgba(255,255,255,0.08);">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🟢</div>
                        <div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Activos / Listos</span>
                            <div style="font-size: 1.6rem; font-weight: 900; color: #10B981; margin-top: 2px;">{{ $activeDevices }}</div>
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

                <!-- SELECTOR DE PESTAÑAS: 1. WEB (PRIMERA) vs 2. APP MÓVIL -->
                <div class="device-tabs-bar" style="display: flex; gap: 0.65rem; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 6px; margin-bottom: 1.5rem; max-width: 580px;">
                    <button type="button" id="tabBtnWeb" onclick="switchDeviceTab('web')" class="device-tab-btn active" style="flex: 1; border: none; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 800; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.55rem; transition: all 0.25s ease;">
                        <span>🌐</span>
                        <span>Scanner Web (Navegador Móvil)</span>
                        <span class="badge-tab-count" style="font-size: 0.75rem; padding: 0.15rem 0.55rem; border-radius: 20px; font-weight: 900;">{{ $totalWebScanners }}</span>
                    </button>
                    <button type="button" id="tabBtnApp" onclick="switchDeviceTab('app')" class="device-tab-btn" style="flex: 1; border: none; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 800; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.55rem; transition: all 0.25s ease;">
                        <span>📱</span>
                        <span>Dispositivos App (Flutter)</span>
                        <span class="badge-tab-count" style="font-size: 0.75rem; padding: 0.15rem 0.55rem; border-radius: 20px; font-weight: 900;">{{ $totalAppDevices }}</span>
                    </button>
                </div>

                <!-- ========================================================================= -->
                <!-- PESTAÑA 1: SCANNERS WEB (ACTIVA POR DEFECTO)                              -->
                <!-- ========================================================================= -->
                <div id="tabContentWeb" style="display: block;">
                    <div class="settings-card-box" style="background: #14141E; border: 1px solid rgba(0, 240, 255, 0.2); border-radius: 20px; padding: 1.5rem; box-shadow: 0 15px 35px rgba(0,0,0,0.4);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <h3 style="font-size: 1.18rem; font-weight: 900; color: #FFFFFF; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <span>Scanners Web para Puertas y Operadores</span>
                                    <span style="font-size: 0.72rem; padding: 0.2rem 0.6rem; border-radius: 20px; background: rgba(0,240,255,0.12); color: #00F0FF; border: 1px solid rgba(0,240,255,0.3); font-weight: 800;">PIN DE 4 DÍGITOS</span>
                                </h3>
                                <p style="font-size: 0.82rem; color: #94A3B8; margin: 3px 0 0 0;">
                                    Los operadores ingresan desde el navegador de su teléfono mediante el link limpio y digitan su PIN del sistema.
                                </p>
                            </div>
                            <button type="button" onclick="openCreateWebScannerModal()" class="btn" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.88rem; padding: 0.65rem 1.2rem; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;">
                                <span>➕</span> Crear Scanner Web
                            </button>
                        </div>

                        @if($webScanners->isEmpty())
                            <div style="text-align: center; padding: 3.5rem 1rem; color: #94A3B8;">
                                <div style="font-size: 3.5rem; margin-bottom: 0.75rem;">🌐</div>
                                <h4 style="font-size: 1.2rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.5rem 0;">No tienes Scanners Web creados aún</h4>
                                <p style="font-size: 0.86rem; max-width: 500px; margin: 0 auto 1.5rem auto; line-height: 1.5;">
                                    Crea perfiles para tus puertas o personal de acceso (ej: "Puerta Principal", "Control VIP"). El sistema generará automáticamente un <strong>PIN de 4 dígitos</strong> y su enlace de login directo.
                                </p>
                                <button type="button" class="btn" onclick="openCreateWebScannerModal()" style="padding: 0.85rem 1.6rem; border-radius: 12px; font-weight: 900; background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; border: none; cursor: pointer; box-shadow: 0 4px 18px rgba(0, 240, 255, 0.4);">
                                    ⚡ Crear mi Primer Scanner Web
                                </button>
                            </div>
                        @else
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                                    <thead>
                                        <tr style="border-bottom: 1.5px solid rgba(255,255,255,0.1); color: #94A3B8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th style="padding: 0.85rem 1rem;">Scanner / Puerta</th>
                                            <th style="padding: 0.85rem 1rem;">Evento Autorizado</th>
                                            <th style="padding: 0.85rem 1rem; text-align: center;">PIN de Acceso</th>
                                            <th style="padding: 0.85rem 1rem;">Enlace de Login</th>
                                            <th style="padding: 0.85rem 1rem; text-align: center;">Escaneos</th>
                                            <th style="padding: 0.85rem 1rem; text-align: center;">Estado</th>
                                            <th style="padding: 0.85rem 1rem; text-align: right;">Acciones & Credenciales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($webScanners as $ws)
                                            @php
                                                $assignedIds = !empty($ws->assigned_events) ? array_values(array_map('intval', (array)$ws->assigned_events)) : [];
                                                $assignedEventsList = !empty($assignedIds) ? $allEvents->whereIn('id', $assignedIds)->values() : collect();
                                                $firstEvent = $assignedEventsList->first();
                                                $firstEventId = $firstEvent ? $firstEvent->id : null;
                                                $accessUrl = $firstEventId ? rtrim($detectedUrl, '/') . '/scanner/' . $firstEventId : rtrim($detectedUrl, '/') . '/scanner/' . ($allEvents->first() ? $allEvents->first()->id : '');
                                                $allTitlesString = $assignedEventsList->pluck('title')->join(', ');
                                            @endphp
                                            <tr class="device-row" data-name="{{ strtolower($ws->name) }}" data-pin="{{ $ws->access_pin }}" style="border-bottom: 1px solid rgba(255,255,255,0.06); transition: background 0.2s ease;">
                                                <td style="padding: 1rem;">
                                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                                        <span style="font-size: 1.3rem;">🌐</span>
                                                        <div>
                                                            <div style="font-weight: 900; color: #FFFFFF; font-size: 0.95rem;">{{ $ws->name }}</div>
                                                            <div style="font-size: 0.72rem; color: #64748B;">Creado {{ $ws->created_at ? $ws->created_at->format('d/m/Y H:i') : 'Recientemente' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding: 1rem;">
                                                    @if($assignedEventsList->isEmpty())
                                                        <span style="font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 8px; background: rgba(148, 163, 184, 0.15); color: #CBD5E1; border: 1px solid rgba(148, 163, 184, 0.25); font-weight: 700;">
                                                            🌐 Todos los Eventos
                                                        </span>
                                                    @elseif($assignedEventsList->count() === 1)
                                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 8px; background: rgba(0, 240, 255, 0.1); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.3); font-weight: 800; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $firstEvent->title }}">
                                                            🎟️ {{ $firstEvent->title }}
                                                        </span>
                                                    @else
                                                        <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                                                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 8px; background: rgba(0, 240, 255, 0.1); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.3); font-weight: 800; max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $firstEvent->title }}">
                                                                🎟️ {{ $firstEvent->title }}
                                                            </span>
                                                            <span style="font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 8px; background: rgba(0, 240, 255, 0.18); color: #00F0FF; border: 1px solid rgba(0, 240, 255, 0.45); font-weight: 900; cursor: help;" title="{{ $allTitlesString }}">
                                                                +{{ $assignedEventsList->count() - 1 }} más
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td style="padding: 1rem; text-align: center;">
                                                    <div style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(0, 240, 255, 0.12); border: 1.5px solid #00F0FF; border-radius: 10px; padding: 0.3rem 0.65rem; box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);">
                                                        <span style="font-size: 1.15rem; font-weight: 900; font-family: monospace; color: #00F0FF; letter-spacing: 2px;">
                                                            {{ $ws->access_pin }}
                                                        </span>
                                                        <button type="button" onclick="copyTextDirect('{{ $ws->access_pin }}', 'PIN [{{ $ws->access_pin }}] copiado')" style="background: none; border: none; color: #00F0FF; cursor: pointer; font-size: 0.85rem; padding: 0;" title="Copiar PIN">
                                                            📋
                                                        </button>
                                                    </div>
                                                </td>
                                                <td style="padding: 1rem;">
                                                    <div style="display: flex; align-items: center; gap: 0.4rem; max-width: 220px;">
                                                        <input type="text" readonly value="{{ $accessUrl }}" onclick="this.select(); copyTextDirect('{{ $accessUrl }}', 'Enlace copiado')" style="width: 100%; background: #0A0A10; border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 0.35rem 0.6rem; color: #94A3B8; font-family: monospace; font-size: 0.75rem; outline: none; cursor: pointer;" title="Click para copiar enlace">
                                                        <button type="button" onclick="copyTextDirect('{{ $accessUrl }}', 'Enlace de acceso copiado')" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; border-radius: 8px; padding: 0.35rem 0.55rem; cursor: pointer; font-size: 0.78rem;" title="Copiar Enlace">
                                                            📋
                                                        </button>
                                                    </div>
                                                </td>
                                                <td style="padding: 1rem; text-align: center;">
                                                    <span style="font-family: monospace; font-size: 1.05rem; font-weight: 900; color: #10B981;">
                                                        {{ $ws->scans_count }}
                                                    </span>
                                                </td>
                                                <td style="padding: 1rem; text-align: center;">
                                                    @if($ws->status === 'active')
                                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981;"></span> ACTIVO
                                                        </span>
                                                    @else
                                                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                                                            🚫 BLOQUEADO
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="padding: 1rem; text-align: right;">
                                                    <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                                        <!-- Copiar Credenciales Completas (Link + PIN) -->
                                                        <button type="button" class="btn btn-sm" onclick="copyFullCredentials('{{ addslashes($ws->name) }}', '{{ addslashes($allTitlesString ?: 'Todos los Eventos') }}', '{{ $accessUrl }}', '{{ $ws->access_pin }}')" title="Copiar Credenciales Completas (Link y PIN)" style="background: rgba(0, 240, 255, 0.15); border: 1.5px solid #00F0FF; color: #00F0FF; padding: 0.45rem 0.75rem; border-radius: 9px; cursor: pointer; font-size: 0.78rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.35rem;">
                                                            <span>📋</span> <span>Copiar Credenciales</span>
                                                        </button>

                                                        <!-- Editar Scanner Web -->
                                                        <button type="button" class="btn btn-sm" onclick="openEditWebScannerModal({{ $ws->id }}, '{{ addslashes($ws->name) }}', '{{ $ws->access_pin }}', {{ json_encode($assignedIds) }}, '{{ $accessUrl }}')" title="Editar Scanner Web y Eventos" style="background: rgba(0, 240, 255, 0.15); border: 1px solid rgba(0, 240, 255, 0.35); color: #00F0FF; padding: 0.45rem 0.65rem; border-radius: 9px; cursor: pointer; font-size: 0.85rem;" onmouseenter="this.style.background='#00F0FF'; this.style.color='#050B14';" onmouseleave="this.style.background='rgba(0, 240, 255, 0.15)'; this.style.color='#00F0FF';">
                                                            ✏️
                                                        </button>

                                                        <!-- Bloquear / Reactivar -->
                                                        <button type="button" class="btn btn-sm" onclick="toggleDeviceStatus({{ $ws->id }}, '{{ $ws->status }}')" title="{{ $ws->status === 'revoked' ? 'Reactivar Scanner' : 'Bloquear Scanner' }}" style="background: {{ $ws->status === 'revoked' ? 'rgba(16, 185, 129, 0.15)' : 'rgba(245, 158, 11, 0.15)' }}; border: 1px solid {{ $ws->status === 'revoked' ? 'rgba(16, 185, 129, 0.4)' : 'rgba(245, 158, 11, 0.4)' }}; color: {{ $ws->status === 'revoked' ? '#10B981' : '#F59E0B' }}; padding: 0.45rem 0.65rem; border-radius: 9px; cursor: pointer; font-size: 0.85rem;">
                                                            {{ $ws->status === 'revoked' ? '🔓' : '🔒' }}
                                                        </button>

                                                        <!-- Eliminar -->
                                                        <button type="button" class="btn btn-sm" onclick="deleteDevice({{ $ws->id }}, '{{ addslashes($ws->name) }}')" title="Eliminar Scanner" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #EF4444; padding: 0.45rem 0.65rem; border-radius: 9px; cursor: pointer; font-size: 0.85rem;">
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

                <!-- ========================================================================= -->
                <!-- PESTAÑA 2: DISPOSITIVOS APP FLUTTER                                       -->
                <!-- ========================================================================= -->
                <div id="tabContentApp" style="display: none;">
                    <div class="settings-card-box" style="background: #14141E; border: 1px solid rgba(255, 85, 0, 0.25); border-radius: 20px; padding: 1.5rem; box-shadow: 0 15px 35px rgba(0,0,0,0.4);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <h3 style="font-size: 1.18rem; font-weight: 900; color: #FFFFFF; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <span>Terminales Móviles Vinculadas (App Flutter)</span>
                                    <span style="font-size: 0.72rem; padding: 0.2rem 0.6rem; border-radius: 20px; background: rgba(255,85,0,0.15); color: #FF7733; border: 1px solid rgba(255,85,0,0.35); font-weight: 800;">VINCULACIÓN QR</span>
                                </h3>
                                <p style="font-size: 0.82rem; color: #94A3B8; margin: 3px 0 0 0;">
                                    Terminales físicas y teléfonos celulares que escanean utilizando la app nativa instalada.
                                </p>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="openCreateDeviceModal()" style="padding: 0.65rem 1.2rem; font-size: 0.88rem; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; font-weight: 800; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;">
                                <span>📱</span> + Vincular Nueva Terminal
                            </button>
                        </div>

                        @if($appDevices->isEmpty())
                            <div style="text-align: center; padding: 3.5rem 1rem; color: #94A3B8;">
                                <div style="font-size: 3.5rem; margin-bottom: 0.75rem;">📲</div>
                                <h4 style="font-size: 1.2rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.5rem 0;">No tienes dispositivos App registrados</h4>
                                <p style="font-size: 0.86rem; max-width: 480px; margin: 0 auto 1.5rem auto; line-height: 1.5;">
                                    Haz clic en <strong>+ Vincular Nueva Terminal</strong> para crear un registro y escanear su código QR con la aplicación móvil ViveGo.
                                </p>
                                <button type="button" class="btn btn-primary" onclick="openCreateDeviceModal()" style="padding: 0.85rem 1.6rem; border-radius: 12px; font-weight: 900; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);">
                                    📱 Registrar Terminal App
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
                                            <th style="padding: 0.85rem 1rem;">Modelo</th>
                                            <th style="padding: 0.85rem 1rem; text-align: center;">Escaneos</th>
                                            <th style="padding: 0.85rem 1rem;">Última Actividad</th>
                                            <th style="padding: 0.85rem 1rem; text-align: right;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appDevices as $dev)
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
                                                        <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; max-width: 260px;">
                                                            @foreach($assignedEventsList->take(2) as $aEvt)
                                                                <span style="font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 6px; background: rgba(139, 92, 246, 0.15); color: #C084FC; border: 1px solid rgba(139, 92, 246, 0.3); font-weight: 700; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 120px;" title="{{ $aEvt->title }}">
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
                                                        <div style="font-size: 0.72rem; color: #64748B;">{{ $dev->last_activity_at->format('d/m H:i') }}</div>
                                                    @else
                                                        <span style="color: #64748B;">Nunca</span>
                                                    @endif
                                                </td>
                                                <td style="padding: 1rem; text-align: right;">
                                                    <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                                        @if($dev->status !== 'active' && !$isOnline)
                                                            <button type="button" class="btn btn-sm" onclick="showPairingQrModal({{ $dev->id }}, '{{ addslashes($dev->name) }}', '{{ $dev->device_uuid }}', '{{ $dev->pairing_token }}')" title="Vincular con la App Móvil (Código QR)" style="background: rgba(255, 85, 0, 0.15); border: 1.5px solid #FF5500; color: #FF5500; padding: 0.45rem 0.85rem; border-radius: 10px; cursor: pointer; font-size: 0.8rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.4rem;">
                                                                <span>🔗</span> Vincular QR
                                                            </button>
                                                        @endif

                                                        <button type="button" class="btn btn-sm" onclick="openEditDeviceModal({{ $dev->id }}, '{{ addslashes($dev->name) }}', {{ json_encode($dev->assigned_events ?: []) }})" title="Editar Configuración y Eventos" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); color: #60A5FA; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer; font-size: 0.78rem; font-weight: 800;">
                                                            ✏️
                                                        </button>

                                                        <button type="button" class="btn btn-sm" onclick="toggleDeviceStatus({{ $dev->id }}, '{{ $dev->status }}')" title="{{ $dev->status === 'revoked' ? 'Reactivar Dispositivo' : 'Bloquear / Revocar' }}" style="background: {{ $dev->status === 'revoked' ? 'rgba(16, 185, 129, 0.15)' : 'rgba(245, 158, 11, 0.15)' }}; border: 1px solid {{ $dev->status === 'revoked' ? 'rgba(16, 185, 129, 0.4)' : 'rgba(245, 158, 11, 0.4)' }}; color: {{ $dev->status === 'revoked' ? '#10B981' : '#F59E0B' }}; padding: 0.4rem 0.65rem; border-radius: 8px; cursor: pointer; font-size: 0.78rem; font-weight: 800;">
                                                            {{ $dev->status === 'revoked' ? '🔓' : '🔒' }}
                                                        </button>

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
            </div>
        </main>
    </div>

    <!-- ========================================================================= -->
    <!-- ESTILOS EXCLUSIVOS PARA MODALES & TABS DE DISPOSITIVOS                     -->
    <!-- ========================================================================= -->
    <style>
        .device-tab-btn {
            background: transparent;
            color: #94A3B8;
        }
        .device-tab-btn.active {
            background: linear-gradient(135deg, #00F0FF, #00A3FF);
            color: #050B14 !important;
            box-shadow: 0 4px 15px rgba(0, 240, 255, 0.35);
        }
        .device-tab-btn.active .badge-tab-count {
            background: #050B14;
            color: #00F0FF;
        }
        .device-tab-btn:not(.active) .badge-tab-count {
            background: rgba(255, 255, 255, 0.12);
            color: #CBD5E1;
        }

        .custom-device-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(10, 10, 18, 0.88);
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
            background: #0F0F1A;
            border-radius: 24px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.85);
            color: #FFFFFF;
            width: 100%;
            transform: scale(0.94) translateY(15px);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-sizing: border-box;
        }

        .custom-device-modal-overlay.active .custom-device-modal-card {
            transform: scale(1) translateY(0);
        }

        .custom-web-modal-card {
            border: 1.5px solid rgba(0, 240, 255, 0.4);
            box-shadow: 0 25px 70px rgba(0,0,0,0.9), 0 0 35px rgba(0,240,255,0.18);
        }

        .custom-app-modal-card {
            border: 1.5px solid rgba(255, 85, 0, 0.4);
            box-shadow: 0 25px 70px rgba(0,0,0,0.9), 0 0 35px rgba(255,85,0,0.18);
        }
    </style>

    <!-- ========================================================================= -->
    <!-- MODAL 1: CREAR SCANNER WEB (AZUL/CYAN PREMIUM - EXACTO COMO LA IMAGEN)     -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="createWebScannerModal">
        <div class="custom-device-modal-card custom-web-modal-card" style="max-width: 620px; width: 95%; max-height: 92vh; overflow-y: auto; padding: 0;">
            
            <!-- Modal Header -->
            <div style="padding: 1.35rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(0, 240, 255, 0.12); border: 1.5px solid rgba(0, 240, 255, 0.4); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 0 15px rgba(0,240,255,0.2);">
                        📱
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: #FFFFFF;">Crear Scanner Web</h3>
                            <span style="font-size: 0.7rem; font-weight: 900; padding: 0.15rem 0.55rem; border-radius: 20px; background: rgba(0,240,255,0.15); color: #00F0FF; border: 1px solid rgba(0,240,255,0.35);">
                                BASE DE DATOS
                            </span>
                        </div>
                        <p style="margin: 3px 0 0 0; font-size: 0.8rem; color: #94A3B8;">
                            Configura el nombre y el evento. El sistema generará un PIN de 4 dígitos inmodificable.
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateWebScannerModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 36px; height: 36px; font-size: 1.1rem; color: #CBD5E1; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <!-- Paso 1: Formulario de Creación (Solo Nombre y Evento) -->
            <div id="webScannerStepForm" style="padding: 1.6rem 1.75rem;">
                <form id="createWebScannerForm" onsubmit="submitCreateWebScanner(event)">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 1.35rem;">
                        
                        <!-- Campo 1: Nombre Solamente -->
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.45rem;">
                                Nombre del Scanner / Puerta <span style="color: #00F0FF;">*</span>
                            </label>
                            <input type="text" id="web_name" name="name" required placeholder="Ej: Puerta Principal, Scanner VIP, Carlos Norte..." style="width: 100%; box-sizing: border-box; background: #14141E; border: 1.5px solid rgba(0, 240, 255, 0.3); border-radius: 12px; padding: 0.85rem 1rem; color: #FFFFFF; font-size: 0.95rem; font-weight: 700; outline: none; transition: border 0.2s ease;">
                            <span style="display: block; font-size: 0.74rem; color: #64748B; margin-top: 5px;">
                                Este nombre identificará quién escaneó cada boleto en los reportes de auditoría.
                            </span>
                        </div>

                        <!-- Campo 2: Acceso a qué Eventos (Múltiple Selección) -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                                <label style="font-size: 0.82rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.4px; margin: 0;">
                                    ¿A qué eventos tendrá acceso? <span style="color: #00F0FF;">*</span>
                                </label>
                                <button type="button" onclick="toggleAllWebCreateEvents()" style="background: none; border: none; color: #00F0FF; font-size: 0.78rem; font-weight: 800; cursor: pointer; text-decoration: underline;">
                                    Marcar / Desmarcar Todos
                                </button>
                            </div>

                            <div style="background: #14141E; border: 1.5px solid rgba(0, 240, 255, 0.3); border-radius: 14px; padding: 0.75rem; max-height: 230px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.45rem;" id="webCreateEventsContainer">
                                @foreach($allEvents as $ev)
                                    <label class="web-event-item-label" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.65rem 0.85rem; border-radius: 10px; cursor: pointer; background: rgba(0, 240, 255, 0.04); border: 1px solid rgba(0, 240, 255, 0.15); transition: all 0.2s ease;">
                                        <input type="checkbox" name="web_event_ids[]" value="{{ $ev->id }}" class="web-create-event-chk" style="accent-color: #00F0FF; margin-top: 3px; transform: scale(1.2); cursor: pointer;" {{ $loop->first ? 'checked' : '' }}>
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="font-size: 0.88rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                🎟️ {{ $ev->title }}
                                            </div>
                                            <div style="font-size: 0.73rem; color: #94A3B8; margin-top: 2px;">
                                                🗓️ {{ $ev->event_date }} {{ $ev->event_time }} &nbsp;|&nbsp; 📍 {{ $ev->venue_name ?? 'Local Principal' }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <span style="display: block; font-size: 0.74rem; color: #64748B; margin-top: 5px;">
                                Puedes seleccionar uno o varios eventos. El operador podrá usar su PIN para ingresar a cualquiera de ellos.
                            </span>
                        </div>

                        <!-- Banner Informativo del PIN -->
                        <div style="background: rgba(0, 240, 255, 0.08); border: 1.5px dashed rgba(0, 240, 255, 0.35); border-radius: 14px; padding: 0.9rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.4rem;">🔒</span>
                            <div style="font-size: 0.78rem; color: #CBD5E1; line-height: 1.4;">
                                <strong style="color: #00F0FF; display: block; margin-bottom: 2px;">PIN Automático del Sistema</strong>
                                El sistema generará un <strong>PIN de 4 dígitos</strong> aleatorio e inmodificable para garantizar máxima seguridad y trazabilidad.
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                            <button type="button" onclick="closeCreateWebScannerModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; font-weight: 800; font-size: 0.88rem; padding: 0.85rem 1.4rem; border-radius: 12px; cursor: pointer;">
                                Cancelar
                            </button>
                            <button type="submit" id="btnSubmitCreateWebScanner" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.92rem; padding: 0.85rem 1.75rem; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 18px rgba(0, 240, 255, 0.4); display: inline-flex; align-items: center; gap: 0.45rem;">
                                <span>⚡</span> <span>Crear Scanner y Obtener PIN</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Paso 2: Credenciales Generadas (Aparece Recién Creado el Scanner) -->
            <div id="webScannerStepSuccess" style="display: none; padding: 1.6rem 1.75rem; flex-direction: column; align-items: center; text-align: center; gap: 1.25rem;">
                <div style="width: 64px; height: 64px; border-radius: 20px; background: rgba(0, 240, 255, 0.15); border: 2px solid #00F0FF; display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 0 25px rgba(0,240,255,0.35);">
                    🎉
                </div>

                <div>
                    <h3 style="margin: 0; font-size: 1.35rem; font-weight: 900; color: #FFFFFF;">¡Scanner Creado con Éxito!</h3>
                    <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #94A3B8;">
                        Perfil: <strong id="succWebScannerName" style="color: #FFFFFF;">-</strong> &nbsp;|&nbsp; Evento: <strong id="succWebScannerEvent" style="color: #00F0FF;">-</strong>
                    </p>
                </div>

                <!-- Tarjeta Principal de Credenciales -->
                <div style="background: rgba(0, 240, 255, 0.06); border: 2px dashed #00F0FF; border-radius: 18px; padding: 1.25rem 1.5rem; width: 100%; box-sizing: border-box; text-align: center;">
                    <span style="font-size: 0.74rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; display: block;">
                        PIN DE ACCESO DEL SISTEMA (4 DÍGITOS)
                    </span>
                    <div id="succWebScannerPin" style="font-size: 3rem; font-weight: 900; font-family: monospace; color: #00F0FF; letter-spacing: 8px; margin: 0.4rem 0;">
                        ----
                    </div>
                    <small style="color: #94A3B8; font-size: 0.74rem; display: block; margin-bottom: 0.85rem;">
                        🔒 El operador digita este PIN al entrar al enlace de login.
                    </small>

                    <!-- Input del Enlace de Login -->
                    <div style="text-align: left; margin-top: 0.5rem;">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #CBD5E1; display: block; margin-bottom: 0.35rem;">
                            🔗 Enlace de Acceso Directo:
                        </label>
                        <div style="display: flex; gap: 0.45rem;">
                            <input type="text" id="succWebScannerUrl" readonly onclick="this.select();" style="flex: 1; background: #0F0F1A; border: 1px solid rgba(0,240,255,0.3); border-radius: 10px; padding: 0.65rem 0.85rem; color: #00F0FF; font-family: monospace; font-size: 0.82rem; outline: none; cursor: pointer;">
                            <button type="button" onclick="copyTextDirect(document.getElementById('succWebScannerUrl').value, 'Enlace copiado')" style="background: rgba(0,240,255,0.15); border: 1px solid #00F0FF; color: #00F0FF; font-weight: 800; font-size: 0.8rem; padding: 0.65rem 0.95rem; border-radius: 10px; cursor: pointer; white-space: nowrap;">
                                📋 Copiar Link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN: COPIAR CREDENCIALES COMPLETAS (LINK + PIN) Y WHATSAPP -->
                <div style="width: 100%; display: flex; flex-direction: column; gap: 0.65rem;">
                    <!-- Botón Principal: Copiar Ambos -->
                    <button type="button" id="btnCopyBothCreds" onclick="copyCreatedCredentials()" style="width: 100%; background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.95rem; padding: 0.9rem; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 18px rgba(0, 240, 255, 0.4); display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.15s ease;">
                        <span>📋</span> <span id="btnCopyBothText">Copiar Credenciales (Link y PIN)</span>
                    </button>



                    <button type="button" onclick="closeCreateWebScannerModal(true)" style="background: none; border: none; color: #94A3B8; font-size: 0.82rem; font-weight: 700; cursor: pointer; padding: 0.4rem; margin-top: 0.2rem;">
                        Cerrar y Actualizar Lista
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1.5: EDITAR SCANNER WEB (AZUL/CYAN PREMIUM)                         -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="editWebScannerModal">
        <div class="custom-device-modal-card custom-web-modal-card" style="max-width: 620px; width: 95%; max-height: 92vh; overflow-y: auto; padding: 0;">
            
            <!-- Modal Header -->
            <div style="padding: 1.35rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02);">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(0, 240, 255, 0.12); border: 1.5px solid rgba(0, 240, 255, 0.4); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 0 15px rgba(0,240,255,0.2);">
                        ✏️
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: #FFFFFF;">Editar Scanner Web</h3>
                            <span style="font-size: 0.7rem; font-weight: 900; padding: 0.15rem 0.55rem; border-radius: 20px; background: rgba(0,240,255,0.15); color: #00F0FF; border: 1px solid rgba(0,240,255,0.35);">
                                ACCESO WEB
                            </span>
                        </div>
                        <p style="margin: 3px 0 0 0; font-size: 0.8rem; color: #94A3B8;">
                            Actualiza el nombre y los eventos autorizados para este operador.
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeEditWebScannerModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 36px; height: 36px; font-size: 1.1rem; color: #CBD5E1; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <!-- Formulario de Edición -->
            <div style="padding: 1.6rem 1.75rem;">
                <form id="editWebScannerForm" onsubmit="submitEditWebScanner(event)">
                    @csrf
                    <input type="hidden" id="edit_web_id">
                    
                    <div style="display: flex; flex-direction: column; gap: 1.35rem;">
                        
                        <!-- PIN del Sistema (Solo Lectura) -->
                        <div style="background: rgba(0, 240, 255, 0.08); border: 1.5px dashed rgba(0, 240, 255, 0.35); border-radius: 14px; padding: 0.85rem 1.1rem; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.4rem;">🔑</span>
                                <div>
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">PIN DE ACCESO (INMODIFICABLE)</span>
                                    <div id="edit_web_pin_display" style="font-size: 1.45rem; font-weight: 900; font-family: monospace; color: #00F0FF; letter-spacing: 3px;">
                                        ----
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="btnCopyEditPin" onclick="copyEditWebPin()" style="background: rgba(0, 240, 255, 0.15); border: 1px solid #00F0FF; color: #00F0FF; font-size: 0.78rem; font-weight: 800; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer;">
                                📋 Copiar PIN
                            </button>
                        </div>

                        <!-- Campo 1: Nombre -->
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.45rem;">
                                Nombre del Scanner / Puerta <span style="color: #00F0FF;">*</span>
                            </label>
                            <input type="text" id="edit_web_name" name="name" required placeholder="Ej: Puerta Principal, Scanner VIP..." style="width: 100%; box-sizing: border-box; background: #14141E; border: 1.5px solid rgba(0, 240, 255, 0.3); border-radius: 12px; padding: 0.85rem 1rem; color: #FFFFFF; font-size: 0.95rem; font-weight: 700; outline: none;">
                        </div>

                        <!-- Campo 2: Eventos Autorizados (Múltiples Eventos) -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                                <label style="font-size: 0.82rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.4px; margin: 0;">
                                    Eventos Autorizados (Múltiple Selección) <span style="color: #00F0FF;">*</span>
                                </label>
                                <button type="button" onclick="toggleAllWebEditEvents()" style="background: none; border: none; color: #00F0FF; font-size: 0.78rem; font-weight: 800; cursor: pointer; text-decoration: underline;">
                                    Marcar / Desmarcar Todos
                                </button>
                            </div>

                            <div style="background: #14141E; border: 1.5px solid rgba(0, 240, 255, 0.3); border-radius: 14px; padding: 0.75rem; max-height: 230px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.45rem;" id="webEditEventsContainer">
                                @foreach($allEvents as $ev)
                                    <label class="web-event-item-label" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.65rem 0.85rem; border-radius: 10px; cursor: pointer; background: rgba(0, 240, 255, 0.04); border: 1px solid rgba(0, 240, 255, 0.15); transition: all 0.2s ease;">
                                        <input type="checkbox" name="edit_web_event_ids[]" value="{{ $ev->id }}" class="web-edit-event-chk" style="accent-color: #00F0FF; margin-top: 3px; transform: scale(1.2); cursor: pointer;">
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="font-size: 0.88rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                🎟️ {{ $ev->title }}
                                            </div>
                                            <div style="font-size: 0.73rem; color: #94A3B8; margin-top: 2px;">
                                                🗓️ {{ $ev->event_date }} {{ $ev->event_time }} &nbsp;|&nbsp; 📍 {{ $ev->venue_name ?? 'Local Principal' }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <span style="display: block; font-size: 0.74rem; color: #64748B; margin-top: 5px;">
                                Selecciona todos los eventos que este operador podrá escanear con su PIN.
                            </span>
                        </div>

                        <!-- Botones de Envío -->
                        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.25rem;">
                            <button type="button" onclick="closeEditWebScannerModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; font-weight: 800; font-size: 0.88rem; padding: 0.85rem 1.4rem; border-radius: 12px; cursor: pointer;">
                                Cancelar
                            </button>
                            <button type="submit" id="btnSubmitEditWebScanner" style="background: linear-gradient(135deg, #00F0FF, #00A3FF); color: #050B14; font-weight: 900; font-size: 0.92rem; padding: 0.85rem 1.75rem; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 18px rgba(0, 240, 255, 0.4); display: inline-flex; align-items: center; gap: 0.45rem;">
                                <span>💾</span> <span>Guardar Cambios</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: VINCULAR DISPOSITIVO APP FLUTTER (CON CÓDIGO QR)                 -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="createDeviceModal">
        <div class="custom-device-modal-card custom-app-modal-card" style="max-width: 920px; width: 95%; max-height: 90vh; overflow-y: auto; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(255, 85, 0, 0.15); border: 1.5px solid rgba(255, 85, 0, 0.4); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">📱</div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.3rem; font-weight: 900; color: #FFFFFF;">Vincular Nuevo Dispositivo App</h3>
                        <p style="margin: 3px 0 0 0; font-size: 0.82rem; color: #94A3B8;">Configura la terminal de escaneo y asígnale los eventos autorizados con su aforo.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateDeviceModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 36px; height: 36px; font-size: 1.2rem; color: #CBD5E1; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
            </div>

            <form id="createDeviceForm" onsubmit="submitCreateDevice(event)">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.75rem; align-items: start; margin-bottom: 1.5rem;">
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

                        <div style="background: rgba(255, 85, 0, 0.08); border: 1px solid rgba(255, 85, 0, 0.25); border-radius: 16px; padding: 1rem; display: flex; gap: 0.85rem; align-items: flex-start;">
                            <span style="font-size: 1.5rem; line-height: 1;">⚡</span>
                            <div style="font-size: 0.78rem; color: #CBD5E1; line-height: 1.4;">
                                <strong style="color: #FF7722; display: block; margin-bottom: 3px;">Vinculación Instantánea por QR</strong>
                                Al hacer clic en <strong>Generar Código QR</strong>, la pantalla mostrará el código QR. Solo debes apuntar la cámara de la aplicación móvil ViveGo y quedará vinculada en segundos.
                            </div>
                        </div>
                    </div>

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
                                            <div style="font-size: 0.73rem; color: #94A3B8; margin-top: 2px;">
                                                Aforo Total: <strong style="color: #FF7722;">{{ number_format($ev->total_capacity) }}</strong> &nbsp;|&nbsp;
                                                Vendidos: <strong style="color: #10B981;">{{ number_format($ev->tickets_count) }}</strong>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.25rem;">
                    <button type="button" onclick="closeCreateDeviceModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; font-weight: 800; font-size: 0.88rem; padding: 0.85rem 1.4rem; border-radius: 12px; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitCreateDevice" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; font-weight: 900; font-size: 0.92rem; padding: 0.85rem 1.75rem; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 18px rgba(255, 85, 0, 0.4); display: inline-flex; align-items: center; gap: 0.45rem;">
                        <span>📷</span> <span>Generar Código QR de Vinculación</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: CÓDIGO QR DE VINCULACIÓN PARA APP MÓVIL                          -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="pairingQrModal">
        <div class="custom-device-modal-card custom-app-modal-card" style="max-width: 480px; width: 90%; text-align: center; padding: 2rem;">
            <div style="display: flex; justify-content: flex-end; margin-bottom: 0.5rem;">
                <button type="button" onclick="closePairingQrModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 34px; height: 34px; font-size: 1.1rem; color: #CBD5E1; cursor: pointer;">✕</button>
            </div>

            <div style="width: 58px; height: 58px; border-radius: 18px; background: rgba(255, 85, 0, 0.15); border: 2px solid #FF5500; display: inline-flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 0.85rem;">
                📱
            </div>

            <h3 id="modalPairingDevName" style="margin: 0; font-size: 1.35rem; font-weight: 900; color: #FFFFFF;">Dispositivo</h3>
            <p style="margin: 4px 0 1.25rem 0; font-size: 0.85rem; color: #94A3B8;">Apunta la cámara de la App ViveGo para emparejar este teléfono.</p>

            <div style="background: #FFFFFF; border: 4px solid #FF5500; border-radius: 20px; padding: 1.25rem; display: inline-block; box-shadow: 0 10px 30px rgba(255, 85, 0, 0.35); margin-bottom: 1.25rem;">
                <div id="pairingQrCanvas"></div>
            </div>

            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 0.75rem; text-align: left; margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #94A3B8; margin-bottom: 3px;">
                    <span>UUID del Dispositivo:</span>
                    <button type="button" onclick="copyPairingJson()" style="background: none; border: none; color: #FF7722; font-weight: 800; cursor: pointer; padding: 0;">Copiar Config</button>
                </div>
                <div id="modalPairingUuid" style="font-family: monospace; font-size: 0.78rem; color: #CBD5E1; word-break: break-all;">-</div>
            </div>

            <button type="button" onclick="closePairingQrModal()" style="width: 100%; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; font-weight: 900; font-size: 0.95rem; padding: 0.85rem; border-radius: 12px; border: none; cursor: pointer;">
                Entendido / Cerrar
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: EDITAR EVENTOS ASIGNADOS A UNA TERMINAL                          -->
    <!-- ========================================================================= -->
    <div class="custom-device-modal-overlay" id="editDeviceModal">
        <div class="custom-device-modal-card" style="max-width: 580px; width: 95%; max-height: 90vh; overflow-y: auto; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">✏️</div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 900; color: #FFFFFF;">Editar Dispositivo</h3>
                        <p style="margin: 3px 0 0 0; font-size: 0.82rem; color: #94A3B8;">Modifica el nombre o los eventos que esta terminal puede escanear.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditDeviceModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; width: 36px; height: 36px; font-size: 1.1rem; color: #CBD5E1; cursor: pointer;">✕</button>
            </div>

            <form id="editDeviceForm" onsubmit="submitEditDevice(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_dev_id">

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin-bottom: 0.4rem;">
                        Nombre del Dispositivo / Puerta
                    </label>
                    <input type="text" id="edit_dev_name" name="name" required style="width: 100%; box-sizing: border-box; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 0.8rem 1rem; color: #FFFFFF; font-size: 0.92rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 800; color: #CBD5E1; margin-bottom: 0.5rem;">
                        Eventos Autorizados
                    </label>
                    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem; max-height: 280px; overflow-y: auto;">
                        @foreach($allEvents as $ev)
                            <label style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.75rem; border-radius: 10px; cursor: pointer; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); margin-bottom: 0.4rem;">
                                <input type="checkbox" name="assigned_events[]" value="{{ $ev->id }}" class="edit-event-chk" style="accent-color: #3B82F6; transform: scale(1.15);">
                                <span style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; flex: 1;">{{ $ev->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.25rem;">
                    <button type="button" onclick="closeEditDeviceModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; font-weight: 800; font-size: 0.88rem; padding: 0.8rem 1.3rem; border-radius: 12px; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="background: linear-gradient(135deg, #3B82F6, #2563EB); color: #FFFFFF; font-weight: 900; font-size: 0.92rem; padding: 0.8rem 1.6rem; border-radius: 12px; border: none; cursor: pointer;">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Librería qrcode.min.js para renderizar los QRs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const csrfToken = "{{ csrf_token() }}";
        const storeWebScannerUrl = "{{ route('web.devices.store_web') }}";
        const storeAppDeviceUrl = "{{ route('web.devices.store') }}";

        let currentActivePayload = null;
        let lastCreatedScannerData = null;

        // =========================================================================
        // CAMBIAR PESTAÑAS (SCANNER WEB vs APP MÓVIL)
        // =========================================================================
        function switchDeviceTab(tab) {
            const btnWeb = document.getElementById('tabBtnWeb');
            const btnApp = document.getElementById('tabBtnApp');
            const contentWeb = document.getElementById('tabContentWeb');
            const contentApp = document.getElementById('tabContentApp');

            if (tab === 'app') {
                if (btnWeb) btnWeb.classList.remove('active');
                if (btnApp) btnApp.classList.add('active');
                if (contentWeb) contentWeb.style.display = 'none';
                if (contentApp) contentApp.style.display = 'block';
            } else {
                if (btnApp) btnApp.classList.remove('active');
                if (btnWeb) btnWeb.classList.add('active');
                if (contentApp) contentApp.style.display = 'none';
                if (contentWeb) contentWeb.style.display = 'block';
            }
        }

        // =========================================================================
        // MODAL CREAR SCANNER WEB (AZUL/CYAN)
        // =========================================================================
        function openCreateWebScannerModal() {
            // Reset modal al paso 1
            const stepForm = document.getElementById('webScannerStepForm');
            const stepSuccess = document.getElementById('webScannerStepSuccess');
            const form = document.getElementById('createWebScannerForm');
            if (form) form.reset();
            if (stepForm) stepForm.style.display = 'block';
            if (stepSuccess) stepSuccess.style.display = 'none';

            const modal = document.getElementById('createWebScannerModal');
            if (modal) {
                modal.style.display = 'flex';
                void modal.offsetWidth;
                modal.classList.add('active');
                setTimeout(() => {
                    const inp = document.getElementById('web_name');
                    if (inp) inp.focus();
                }, 100);
            }
        }

        function closeCreateWebScannerModal(shouldReload = false) {
            const modal = document.getElementById('createWebScannerModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 250);
            }
            if (shouldReload) {
                window.location.reload();
            }
        }

        function toggleAllWebCreateEvents() {
            const chks = document.querySelectorAll('.web-create-event-chk');
            const allChecked = Array.from(chks).every(c => c.checked);
            chks.forEach(c => c.checked = !allChecked);
        }

        async function submitCreateWebScanner(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitCreateWebScanner');
            const name = document.getElementById('web_name').value.trim();
            const checkedEvents = Array.from(document.querySelectorAll('.web-create-event-chk:checked')).map(c => parseInt(c.value, 10));

            if (!name) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Por favor ingresa un nombre para el scanner',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            if (checkedEvents.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Selecciona al menos un evento para autorizar este scanner',
                    showConfirmButton: false,
                    timer: 2500,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>⏳</span> <span>Creando...</span>';
            }

            try {
                const res = await fetch(storeWebScannerUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: name,
                        event_ids: checkedEvents
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    lastCreatedScannerData = data;

                    // Mostrar Paso 2: Credenciales Generadas con botón de copia
                    document.getElementById('succWebScannerName').textContent = data.device.name;
                    document.getElementById('succWebScannerEvent').textContent = data.event_title || 'Múltiples Eventos';
                    document.getElementById('succWebScannerPin').textContent = data.pin;
                    document.getElementById('succWebScannerUrl').value = data.access_url;
                    const directBtn = document.getElementById('succWebScannerDirectBtn');
                    if (directBtn) directBtn.href = data.access_url;

                    document.getElementById('webScannerStepForm').style.display = 'none';
                    document.getElementById('webScannerStepSuccess').style.display = 'flex';

                    // Toast de éxito
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `✓ Scanner creado. PIN: ${data.pin}`,
                        showConfirmButton: false,
                        timer: 2500,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo crear el scanner',
                        text: data.message || 'Error en el servidor.',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            } catch(err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: 'No se pudo conectar con el servidor.',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span>⚡</span> <span>Crear Scanner y Obtener PIN</span>';
                }
            }
        }

        // =========================================================================
        // MODAL EDITAR SCANNER WEB (AZUL/CYAN)
        // =========================================================================
        let currentEditWebScannerPin = '';

        function openEditWebScannerModal(id, name, pin, assignedEvents, accessUrl) {
            document.getElementById('edit_web_id').value = id;
            document.getElementById('edit_web_name').value = name;
            document.getElementById('edit_web_pin_display').textContent = pin;
            currentEditWebScannerPin = pin;

            const eventsArray = Array.isArray(assignedEvents) ? assignedEvents.map(Number) : [];
            document.querySelectorAll('.web-edit-event-chk').forEach(c => {
                c.checked = eventsArray.includes(parseInt(c.value, 10));
            });

            const modal = document.getElementById('editWebScannerModal');
            if (modal) {
                modal.style.display = 'flex';
                void modal.offsetWidth;
                modal.classList.add('active');
                setTimeout(() => {
                    const inp = document.getElementById('edit_web_name');
                    if (inp) inp.focus();
                }, 100);
            }
        }

        function closeEditWebScannerModal() {
            const modal = document.getElementById('editWebScannerModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 250);
            }
        }

        function copyEditWebPin() {
            if (currentEditWebScannerPin) {
                copyTextDirect(currentEditWebScannerPin, `PIN [${currentEditWebScannerPin}] copiado`);
            }
        }

        function toggleAllWebEditEvents() {
            const chks = document.querySelectorAll('.web-edit-event-chk');
            const allChecked = Array.from(chks).every(c => c.checked);
            chks.forEach(c => c.checked = !allChecked);
        }

        async function submitEditWebScanner(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitEditWebScanner');
            const id = document.getElementById('edit_web_id').value;
            const name = document.getElementById('edit_web_name').value.trim();
            const checkedEvents = Array.from(document.querySelectorAll('.web-edit-event-chk:checked')).map(c => parseInt(c.value, 10));

            if (!name) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Por favor ingresa un nombre para el scanner',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            if (checkedEvents.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Debes seleccionar al menos un evento autorizado',
                    showConfirmButton: false,
                    timer: 2500,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>⏳</span> <span>Guardando...</span>';
            }

            try {
                const res = await fetch(`/admin/dispositivos/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        name: name,
                        assigned_events: checkedEvents
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    closeEditWebScannerModal();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Scanner Actualizado!',
                        text: data.message || 'Los cambios se han guardado con éxito.',
                        timer: 1600,
                        showConfirmButton: false,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                    setTimeout(() => window.location.reload(), 1400);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: data.message || 'Ocurrió un error inesperado.',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            } catch(err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Red',
                    text: err.message,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span>💾</span> <span>Guardar Cambios</span>';
                }
            }
        }

        // Copiar credenciales recién creadas
        function copyCreatedCredentials() {
            if (!lastCreatedScannerData) return;
            const name = lastCreatedScannerData.device ? lastCreatedScannerData.device.name : 'Scanner';
            const eventTitle = lastCreatedScannerData.event_title || 'Evento';
            const url = lastCreatedScannerData.access_url;
            const pin = lastCreatedScannerData.pin;

            copyFullCredentials(name, eventTitle, url, pin);

            const txt = document.getElementById('btnCopyBothText');
            if (txt) {
                txt.textContent = '✓ ¡Credenciales Copiadas!';
                setTimeout(() => { txt.textContent = 'Copiar Credenciales (Link y PIN)'; }, 2500);
            }
        }

        function shareCreatedViaWhatsApp() {
            if (!lastCreatedScannerData) return;
            const name = lastCreatedScannerData.device ? lastCreatedScannerData.device.name : 'Scanner';
            const eventTitle = lastCreatedScannerData.event_title || 'Evento';
            const url = lastCreatedScannerData.access_url;
            const pin = lastCreatedScannerData.pin;

            shareWaCredentials(name, eventTitle, url, pin);
        }

        // =========================================================================
        // ACCIONES DE COPIA Y WHATSAPP COMPARTIDAS
        // =========================================================================
        function copyFullCredentials(name, eventTitle, url, pin) {
            const formatted = `🎟️ *VIVE GO - ACCESO AL SCANNER DE CONTROL*\n\n`
                + `📍 *Punto / Puerta:* ${name}\n`
                + `🎪 *Evento:* ${eventTitle}\n\n`
                + `🔗 *Enlace de Login:* ${url}\n`
                + `🔑 *PIN de Acceso:* *${pin}*\n\n`
                + `_Ingresa al enlace en tu celular y digita tu PIN de 4 dígitos para comenzar a escanear entradas._`;

            copyTextDirect(formatted, `Credenciales de [${name}] copiadas (Link y PIN)`);
        }

        function shareWaCredentials(name, eventTitle, url, pin) {
            const msg = `🎟️ *VIVE GO - ACCESO AL SCANNER DE CONTROL*\n\n`
                + `Hola! Aquí tienes el acceso para la puerta del evento:\n`
                + `🎪 *Evento:* ${eventTitle}\n`
                + `📍 *Punto / Puerta:* ${name}\n\n`
                + `🔗 *Enlace de Login:* ${url}\n`
                + `🔑 *PIN de Acceso:* *${pin}*\n\n`
                + `_Abre el enlace en tu celular y digita tu PIN de 4 dígitos para comenzar a validar boletos._`;

            window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`, '_blank');
        }

        function copyTextDirect(text, successTitle) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: successTitle || 'Copiado al portapapeles',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }).catch(() => fallbackCopy(text, successTitle));
            } else {
                fallbackCopy(text, successTitle);
            }
        }

        function fallbackCopy(text, successTitle) {
            const t = document.createElement('textarea');
            t.value = text;
            t.style.position = 'fixed';
            t.style.opacity = '0';
            document.body.appendChild(t);
            t.select();
            document.execCommand('copy');
            document.body.removeChild(t);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: successTitle || 'Copiado al portapapeles',
                showConfirmButton: false,
                timer: 2000,
                background: '#14141E',
                color: '#FFFFFF'
            });
        }

        // =========================================================================
        // MODALES DE DISPOSITIVOS APP (VINCULAR QR / EDITAR / ELIMINAR / BLOQUEAR)
        // =========================================================================
        function openCreateDeviceModal() {
            const modal = document.getElementById('createDeviceModal');
            if (modal) {
                modal.style.display = 'flex';
                void modal.offsetWidth;
                modal.classList.add('active');
            }
        }

        function closeCreateDeviceModal() {
            const modal = document.getElementById('createDeviceModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 250);
            }
        }

        function onToggleAllEvents(chk) {
            const box = document.getElementById('individual_events_box');
            if (!box) return;
            box.style.display = chk.checked ? 'none' : 'flex';
            if (chk.checked) {
                document.querySelectorAll('.event-chk').forEach(c => c.checked = false);
            }
        }

        function toggleAllEventCheckboxes() {
            const chkAll = document.getElementById('chk_all_events');
            if (chkAll) {
                chkAll.checked = !chkAll.checked;
                onToggleAllEvents(chkAll);
            }
        }

        async function submitCreateDevice(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitCreateDevice');
            const form = document.getElementById('createDeviceForm');
            const formData = new FormData(form);

            if (btn) btn.disabled = true;

            try {
                const res = await fetch(storeAppDeviceUrl, {
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
                    showPairingQrModal(
                        data.device.id,
                        data.device.name,
                        data.device.device_uuid,
                        data.device.pairing_token,
                        data.qr_payload
                    );
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al registrar terminal',
                        text: data.message || 'Ocurrió un error inesperado.',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            } catch(err) {
                console.error(err);
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

        function showPairingQrModal(id, name, uuid, token, rawPayload = null) {
            document.getElementById('modalPairingDevName').textContent = name;
            document.getElementById('modalPairingUuid').textContent = uuid;

            const qrContainer = document.getElementById('pairingQrCanvas');
            qrContainer.innerHTML = '';

            let payload = rawPayload;
            if (!payload) {
                const sUrl = document.getElementById('dev_server_url') ? document.getElementById('dev_server_url').value : window.location.origin;
                payload = JSON.stringify({
                    vivego_pair: true,
                    server_url: sUrl,
                    device_uuid: uuid,
                    token: token,
                    name: name
                });
            }

            currentActivePayload = payload;

            new QRCode(qrContainer, {
                text: payload,
                width: 220,
                height: 220,
                colorDark: '#000000',
                colorLight: '#FFFFFF',
                correctLevel: QRCode.CorrectLevel.M
            });

            const modal = document.getElementById('pairingQrModal');
            if (modal) {
                modal.style.display = 'flex';
                void modal.offsetWidth;
                modal.classList.add('active');
            }
        }

        function closePairingQrModal() {
            const modal = document.getElementById('pairingQrModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 250);
            }
            window.location.reload();
        }

        function copyPairingJson() {
            if (currentActivePayload) {
                copyTextDirect(currentActivePayload, 'Configuración JSON copiada');
            }
        }

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

        async function toggleDeviceStatus(id, currentStatus) {
            const actionText = currentStatus === 'revoked' ? 'reactivar' : 'bloquear';
            const confirm = await Swal.fire({
                title: `¿Deseas ${actionText} este dispositivo?`,
                text: currentStatus === 'revoked' 
                    ? 'El scanner volverá a poder validar boletos en puerta.' 
                    : 'El scanner no podrá validar más boletos hasta que lo reactives.',
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

        // Filtro buscador instantáneo de tablas
        const filterInput = document.getElementById('tableFilterInput');
        if (filterInput) {
            filterInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.device-row').forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const pin = row.getAttribute('data-pin') || '';
                    const uuid = row.getAttribute('data-uuid') || '';
                    if (!q || name.includes(q) || pin.includes(q) || uuid.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Cerrar modales haciendo click en el fondo oscuro
        ['createWebScannerModal', 'editWebScannerModal', 'createDeviceModal', 'pairingQrModal', 'editDeviceModal'].forEach(modalId => {
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                modalEl.addEventListener('click', function(e) {
                    if (e.target === modalEl) {
                        if (modalId === 'createWebScannerModal') closeCreateWebScannerModal();
                        else if (modalId === 'editWebScannerModal') closeEditWebScannerModal();
                        else if (modalId === 'createDeviceModal') closeCreateDeviceModal();
                        else if (modalId === 'pairingQrModal') closePairingQrModal();
                        else if (modalId === 'editDeviceModal') closeEditDeviceModal();
                    }
                });
            }
        });
    </script>
@endsection
