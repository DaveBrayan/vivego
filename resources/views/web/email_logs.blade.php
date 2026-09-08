@extends('layouts.app')

@section('title', 'Registro de Correos Electrónicos | Vive Go')

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN HEREDADO -->
        @include('layouts.sidebar')

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <form action="{{ route('web.email_logs') }}" method="GET" class="dash-search-container" style="flex: 1; max-width: 450px;">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" name="q" value="{{ $search }}" class="dash-search-input" placeholder="Buscar por destinatario, correo o N° recibo...">
                    @if($status !== 'all')
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    @if(!empty($eventId))
                        <input type="hidden" name="event_id" value="{{ $eventId }}">
                    @endif
                </form>

                <div class="dash-top-actions">
                    <button class="dash-icon-btn" id="btnThemeToggle" title="Cambiar Tema">
                        <span id="themeToggleIcon">☀️</span>
                    </button>
                    <a href="{{ route('web.email_logs') }}" class="dash-icon-btn" title="Refrescar Registros">
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

                <!-- ENCABEZADO PRINCIPAL -->
                <div class="settings-header-banner" style="margin-bottom: 1.5rem;">
                    <div>
                        <span class="settings-tag" style="background: rgba(37, 99, 235, 0.15); color: #60A5FA; border: 1px solid rgba(37, 99, 235, 0.3);">
                            📬 AUDITORÍA & MENSAJERÍA
                        </span>
                        <h1 class="settings-page-title" style="margin-top: 0.4rem;">Registro de Correos Electrónicos</h1>
                        <p class="settings-page-subtitle">Monitoreo en tiempo real de correos emitidos, entregados y fallidos de boletos oficiales y confirmaciones.</p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <a href="{{ route('web.email_logs') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; border-radius: 10px; padding: 0.75rem 1.25rem; font-weight: 700; text-decoration: none;">
                            <span>🔄</span> Actualizar
                        </a>
                    </div>
                </div>

                <!-- STATS CARDS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">
                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #60A5FA;">
                            📧
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Total Registros</span>
                            <strong style="font-size: 1.6rem; color: #FFFFFF; font-weight: 800;">{{ number_format($totalEmails) }}</strong>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #34D399;">
                            ✅
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Enviados con Éxito</span>
                            <strong style="font-size: 1.6rem; color: #10B981; font-weight: 800;">{{ number_format($sentEmails) }}</strong>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #F87171;">
                            ❌
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Fallidos / Con Error</span>
                            <strong style="font-size: 1.6rem; color: #EF4444; font-weight: 800;">{{ number_format($failedEmails) }}</strong>
                        </div>
                    </div>

                    <div class="settings-card-box" style="margin: 0; padding: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #FBBF24;">
                            📊
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: #94A3B8; display: block; font-weight: 600; text-transform: uppercase;">Tasa de Entrega</span>
                            <strong style="font-size: 1.6rem; color: #F59E0B; font-weight: 800;">{{ $successRate }}%</strong>
                        </div>
                    </div>
                </div>

                <!-- FILTROS & BÚSQUEDA AVANZADA -->
                <div class="settings-card-box" style="margin-bottom: 1.5rem; padding: 1.25rem;">
                    <form action="{{ route('web.email_logs') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; flex: 1; min-width: 300px;">
                            <!-- Filtro Estado -->
                            <div style="display: inline-flex; background: rgba(0,0,0,0.3); padding: 0.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                                <a href="{{ route('web.email_logs', array_merge(request()->query(), ['status' => 'all'])) }}" 
                                   style="padding: 0.45rem 0.95rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $status === 'all' ? 'background: #FF5500; color: #FFFFFF; box-shadow: 0 2px 8px rgba(255,85,0,0.4);' : 'color: #94A3B8;' }}">
                                    Todos ({{ $totalEmails }})
                                </a>
                                <a href="{{ route('web.email_logs', array_merge(request()->query(), ['status' => 'sent'])) }}" 
                                   style="padding: 0.45rem 0.95rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $status === 'sent' ? 'background: #10B981; color: #FFFFFF; box-shadow: 0 2px 8px rgba(16,185,129,0.4);' : 'color: #94A3B8;' }}">
                                    ✓ Enviados ({{ $sentEmails }})
                                </a>
                                <a href="{{ route('web.email_logs', array_merge(request()->query(), ['status' => 'failed'])) }}" 
                                   style="padding: 0.45rem 0.95rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: all 0.2s; {{ $status === 'failed' ? 'background: #EF4444; color: #FFFFFF; box-shadow: 0 2px 8px rgba(239,68,68,0.4);' : 'color: #94A3B8;' }}">
                                    ✕ Fallidos ({{ $failedEmails }})
                                </a>
                            </div>

                            <!-- Filtro Canal / Origen -->
                            <div style="display: inline-flex; background: rgba(0,0,0,0.3); padding: 0.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
                                <a href="{{ route('web.email_logs', array_merge(request()->query(), ['type' => 'all'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $type === 'all' ? 'background: #3B82F6; color: #FFFFFF;' : 'color: #94A3B8;' }}">
                                    Todos los Canales
                                </a>
                                <a href="{{ route('web.email_logs', array_merge(request()->query(), ['type' => 'pos'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $type === 'pos' ? 'background: #06B6D4; color: #FFFFFF; box-shadow: 0 2px 8px rgba(6,182,212,0.4);' : 'color: #94A3B8;' }}">
                                    🎟️ Ventas POS ({{ $posEmails }})
                                </a>
                                <a href="{{ route('web.email_logs', array_merge(request()->query(), ['type' => 'web'])) }}" 
                                   style="padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; {{ $type === 'web' ? 'background: #FF5500; color: #FFFFFF; box-shadow: 0 2px 8px rgba(255,85,0,0.4);' : 'color: #94A3B8;' }}">
                                    🌐 Web ({{ $webEmails }})
                                </a>
                            </div>

                            <!-- Selector de Evento -->
                            <div style="min-width: 200px;">
                                <select name="event_id" onchange="this.form.submit()" style="width: 100%; background: #0B0B12; border: 1px solid rgba(255,255,255,0.12); color: #FFFFFF; padding: 0.55rem 0.85rem; border-radius: 10px; font-size: 0.825rem;">
                                    <option value="">-- Todos los Eventos --</option>
                                    @foreach($events as $evt)
                                        <option value="{{ $evt->id }}" {{ $eventId == $evt->id ? 'selected' : '' }}>
                                            {{ Str::limit($evt->title, 35) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if(!empty($search))
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                        </div>

                        <!-- Reset Filtros -->
                        @if(!empty($search) || $status !== 'all' || $type !== 'all' || !empty($eventId))
                            <div>
                                <a href="{{ route('web.email_logs') }}" style="color: #94A3B8; font-size: 0.825rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 0.85rem; background: rgba(255,255,255,0.04); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                                    ✕ Limpiar Filtros
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                <!-- TABLA DE REGISTROS -->
                <div class="settings-card-box" style="padding: 0; overflow: hidden;">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,85,0,0.12); border: 1px solid rgba(255,85,0,0.3); color: #FF5500; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                📋
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #FFFFFF;">Historial Detallado de Correos</h3>
                                <p style="margin: 0.2rem 0 0 0; font-size: 0.8rem; color: #94A3B8;">Mostrando registros ordenados cronológicamente (Ventas POS y Web)</p>
                            </div>
                        </div>
                        <span style="font-size: 0.8rem; color: #94A3B8; background: rgba(255,255,255,0.04); padding: 0.35rem 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                            Página {{ $logs->currentPage() }} de {{ $logs->lastPage() }}
                        </span>
                    </div>

                    <div class="dash-table-container" style="margin: 0;">
                        <table class="dash-table" style="width: 100%;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.02); font-size: 0.75rem; color: #94A3B8; text-transform: uppercase;">
                                    <th style="padding: 0.85rem 1rem;">ID / Fecha</th>
                                    <th style="padding: 0.85rem 1rem;">Persona Destinataria</th>
                                    <th style="padding: 0.85rem 1rem;">Correo Electrónico</th>
                                    <th style="padding: 0.85rem 1rem;">Evento & Canal</th>
                                    <th style="padding: 0.85rem 1rem;">Estado</th>
                                    <th style="padding: 0.85rem 1rem;">Detalle de Error / Diagnóstico</th>
                                    <th style="padding: 0.85rem 1rem; text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    @php
                                        $isSuccess = $log->status === 'sent';
                                        $eventName = $log->event?->title ?? ($log->details['event_title'] ?? 'Evento ViveGo');
                                        $receiptNum = $log->ticketSale?->receipt_number ?? ($log->details['receipt_number'] ?? null);
                                    @endphp
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                                        <!-- ID y Fecha -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <span style="display: block; font-weight: 800; color: #60A5FA; font-size: 0.85rem; font-family: monospace;">#{{ $log->id }}</span>
                                            <span style="display: block; font-size: 0.75rem; color: #94A3B8; margin-top: 0.2rem;">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                        </td>

                                        <!-- Persona Destinataria -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <div style="display: flex; align-items: center; gap: 0.6rem;">
                                                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0;">
                                                    👤
                                                </div>
                                                <div>
                                                    <strong style="color: #FFFFFF; font-size: 0.88rem; display: block;">{{ $log->recipient_name ?: 'Sin Nombre' }}</strong>
                                                    @if($log->ticketSale?->buyer_dni)
                                                        <span style="font-size: 0.75rem; color: #94A3B8;">DNI: {{ $log->ticketSale->buyer_dni }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Correo Electrónico -->
                                        <td style="padding: 0.85rem 1rem;">
                                            <div style="display: flex; align-items: center; gap: 0.45rem;">
                                                <span style="color: #F59E0B; font-size: 0.85rem;">✉️</span>
                                                <span style="font-family: monospace; font-size: 0.85rem; color: #CBD5E1; font-weight: 600;">{{ $log->recipient_email }}</span>
                                            </div>
                                            @if($log->attempts > 1)
                                                <span style="display: inline-block; font-size: 0.7rem; background: rgba(245, 158, 11, 0.15); color: #FBBF24; padding: 0.1rem 0.4rem; border-radius: 4px; margin-top: 0.25rem;">
                                                    {{ $log->attempts }} intentos
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Evento & Venta -->
                                        <td style="padding: 0.85rem 1rem; max-width: 240px;">
                                            <strong style="display: block; color: #FFFFFF; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $eventName }}">
                                                🎪 {{ $eventName }}
                                            </strong>
                                            <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap; margin-top: 0.25rem;">
                                                @if($receiptNum)
                                                    <span style="font-size: 0.72rem; color: #60A5FA; font-family: monospace; background: rgba(37,99,235,0.12); padding: 0.1rem 0.4rem; border-radius: 4px; border: 1px solid rgba(37,99,235,0.25);">
                                                        #{{ $receiptNum }}
                                                    </span>
                                                @endif

                                                @if(in_array($log->mail_type, ['pos_sale', 'pos_resend']))
                                                    <span style="font-size: 0.7rem; font-weight: 800; color: #06B6D4; background: rgba(6,182,212,0.15); border: 1px solid rgba(6,182,212,0.3); padding: 0.1rem 0.45rem; border-radius: 4px;">
                                                        🎟️ VENTA POS
                                                    </span>
                                                @elseif($log->mail_type === 'courtesy')
                                                    <span style="font-size: 0.7rem; font-weight: 800; color: #A855F7; background: rgba(168,85,247,0.15); border: 1px solid rgba(168,85,247,0.3); padding: 0.1rem 0.45rem; border-radius: 4px;">
                                                        🎁 CORTESÍA
                                                    </span>
                                                @else
                                                    <span style="font-size: 0.7rem; font-weight: 800; color: #FF5500; background: rgba(255,85,0,0.15); border: 1px solid rgba(255,85,0,0.3); padding: 0.1rem 0.45rem; border-radius: 4px;">
                                                        🌐 TIENDA WEB
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Estado -->
                                        <td style="padding: 0.85rem 1rem;">
                                            @if($isSuccess)
                                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.35rem 0.75rem; border-radius: 9999px; font-weight: 800; font-size: 0.75rem;">
                                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
                                                    Enviado
                                                </span>
                                            @else
                                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.35rem 0.75rem; border-radius: 9999px; font-weight: 800; font-size: 0.75rem;">
                                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #EF4444; display: inline-block;"></span>
                                                    Fallido
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Detalle de Error / Motivo -->
                                        <td style="padding: 0.85rem 1rem; max-width: 280px;">
                                            @if(!$isSuccess && !empty($log->error_message))
                                                <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 0.4rem 0.65rem;">
                                                    <span style="display: block; color: #FCA5A5; font-size: 0.78rem; font-weight: 600; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->error_message }}">
                                                        ⚠️ {{ $log->error_message }}
                                                    </span>
                                                    <button type="button" onclick="showErrorModal({{ $log->id }})" style="background: none; border: none; color: #60A5FA; font-size: 0.725rem; font-weight: 700; cursor: pointer; padding: 0; margin-top: 0.25rem; text-decoration: underline;">
                                                        Ver Detalle Completo
                                                    </button>
                                                </div>
                                            @elseif($isSuccess)
                                                <span style="color: #94A3B8; font-size: 0.8rem; display: flex; align-items: center; gap: 0.3rem;">
                                                    <span>✓</span> Entregado por SMTP
                                                </span>
                                            @else
                                                <span style="color: #64748B; font-size: 0.8rem;">Sin detalles</span>
                                            @endif
                                        </td>

                                        <!-- Acciones -->
                                        <td style="padding: 0.85rem 1rem; text-align: right;">
                                            <div style="display: flex; gap: 0.45rem; justify-content: flex-end; align-items: center;">
                                                @if($log->ticket_sale_id)
                                                    <button type="button" class="btn-resend-email" onclick="resendEmail({{ $log->id }}, '{{ $log->recipient_email }}')" title="Reenviar este correo ahora" style="background: linear-gradient(135deg, #FF5500, #E04B00); border: none; color: #FFFFFF; font-size: 0.78rem; font-weight: 800; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 2px 8px rgba(255,85,0,0.3);">
                                                        <span>📨</span> Reenviar
                                                    </button>
                                                @endif

                                                <button type="button" onclick="showErrorModal({{ $log->id }})" title="Ver Información Técnica" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; padding: 0.45rem 0.65rem; border-radius: 8px; cursor: pointer; font-size: 0.8rem;">
                                                    👁️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 3.5rem 1.5rem;">
                                            <div style="font-size: 3rem; margin-bottom: 0.75rem;">📭</div>
                                            <h3 style="color: #FFFFFF; font-size: 1.15rem; font-weight: 800; margin: 0 0 0.35rem 0;">No se encontraron registros de correos</h3>
                                            <p style="color: #94A3B8; font-size: 0.875rem; margin: 0;">
                                                @if(!empty($search) || $status !== 'all' || !empty($eventId))
                                                    Intenta ajustar o limpiar los filtros de búsqueda aplicados.
                                                @else
                                                    Los correos de confirmación y compra que se envíen aparecerán aquí con su estado y diagnóstico.
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINACIÓN -->
                    @if($logs->hasPages())
                        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 0.825rem; color: #94A3B8;">
                                Mostrando {{ $logs->firstItem() }} a {{ $logs->lastItem() }} de {{ $logs->total() }} registros
                            </span>
                            <div>
                                {{ $logs->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL DE DETALLE DE ERROR & DIAGNÓSTICO TÉCNICO -->
    <div id="emailDetailModal" class="admin-modal-backdrop" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
        <div class="admin-modal-content" style="background: #14141E; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 20px; max-width: 650px; width: 100%; padding: 2rem; box-shadow: 0 25px 60px rgba(0,0,0,0.7); color: #FFFFFF; font-family: 'Plus Jakarta Sans', sans-serif;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                    <span>🔍</span> Diagnóstico de Envío de Correo
                </h3>
                <button type="button" onclick="closeErrorModal()" style="background: none; border: none; color: #94A3B8; font-size: 1.4rem; cursor: pointer;">✕</button>
            </div>

            <div id="modalLoading" style="text-align: center; padding: 2rem 1rem; color: #94A3B8;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem; animation: spin 1s linear infinite;">⏳</div>
                <p>Cargando información del registro...</p>
            </div>

            <div id="modalBody" style="display: none;">
                <!-- Header de estado -->
                <div id="modalStatusBadge" style="margin-bottom: 1.25rem;"></div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-bottom: 1.25rem; background: rgba(0,0,0,0.25); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Destinatario</span>
                        <strong id="modalRecipient" style="color: #FFFFFF; font-size: 0.9rem;"></strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Correo Electrónico</span>
                        <strong id="modalEmail" style="color: #F59E0B; font-size: 0.9rem; font-family: monospace;"></strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Evento Asociado</span>
                        <strong id="modalEvent" style="color: #FFFFFF; font-size: 0.9rem;"></strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Venta / Recibo</span>
                        <strong id="modalReceipt" style="color: #60A5FA; font-size: 0.9rem; font-family: monospace;"></strong>
                    </div>
                </div>

                <!-- Detalle de error -->
                <div id="modalErrorContainer" style="display: none; margin-bottom: 1.25rem;">
                    <span style="font-size: 0.75rem; color: #EF4444; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                        ⚠️ Motivo Exacto de la Falla:
                    </span>
                    <div id="modalErrorMessage" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; padding: 0.85rem 1rem; color: #FCA5A5; font-size: 0.85rem; line-height: 1.5; font-family: monospace; word-break: break-all;"></div>
                </div>

                <!-- Detalles técnicos JSON -->
                <div style="margin-bottom: 1.5rem;">
                    <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                        ⚙️ Metadatos Técnicos del Envío:
                    </span>
                    <pre id="modalJsonDetails" style="background: #0A0A10; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem; color: #94A3B8; font-size: 0.75rem; max-height: 150px; overflow-y: auto; font-family: monospace; margin: 0;"></pre>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" onclick="closeErrorModal()" class="btn btn-secondary" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; padding: 0.7rem 1.4rem; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Cerrar
                    </button>
                    <button type="button" id="btnModalResend" class="btn btn-primary" style="background: linear-gradient(135deg, #FF5500, #E04B00); border: none; color: #FFFFFF; padding: 0.7rem 1.6rem; border-radius: 10px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                        <span>📨</span> Reenviar Correo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE INTERACCIÓN -->
    <script>
        let currentModalLogId = null;

        async function showErrorModal(logId) {
            currentModalLogId = logId;
            const modal = document.getElementById('emailDetailModal');
            const loading = document.getElementById('modalLoading');
            const body = document.getElementById('modalBody');

            modal.style.display = 'flex';
            loading.style.display = 'block';
            body.style.display = 'none';

            try {
                const res = await fetch(`/admin/registro-correos/${logId}`);
                const data = await res.json();

                if (data.success && data.log) {
                    const log = data.log;
                    document.getElementById('modalRecipient').textContent = log.recipient_name || 'Sin Nombre';
                    document.getElementById('modalEmail').textContent = log.recipient_email;
                    document.getElementById('modalEvent').textContent = log.event_title || 'N/A';
                    document.getElementById('modalReceipt').textContent = log.receipt_number ? `Recibo #${log.receipt_number}` : 'N/A';

                    const badge = document.getElementById('modalStatusBadge');
                    if (log.status === 'sent') {
                        badge.innerHTML = `
                            <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34D399; padding: 0.75rem 1rem; border-radius: 10px; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                <span>✅</span> Correo entregado exitosamente el ${log.sent_at || log.created_at} (${log.attempts} intento/s)
                            </div>
                        `;
                        document.getElementById('modalErrorContainer').style.display = 'none';
                    } else {
                        badge.innerHTML = `
                            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #F87171; padding: 0.75rem 1rem; border-radius: 10px; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                                <span>❌</span> Falló el envío del correo (${log.attempts} intento/s registrado/s)
                            </div>
                        `;
                        document.getElementById('modalErrorContainer').style.display = 'block';
                        document.getElementById('modalErrorMessage').textContent = log.error_message || 'Error desconocido durante la conexión con el servidor SMTP.';
                    }

                    document.getElementById('modalJsonDetails').textContent = JSON.stringify(log.details || {}, null, 2);

                    const btnResend = document.getElementById('btnModalResend');
                    btnResend.onclick = () => {
                        closeErrorModal();
                        resendEmail(log.id, log.recipient_email);
                    };

                    loading.style.display = 'none';
                    body.style.display = 'block';
                } else {
                    alert('No se pudo cargar la información del registro.');
                    closeErrorModal();
                }
            } catch (err) {
                console.error(err);
                alert('Error al consultar el servidor.');
                closeErrorModal();
            }
        }

        function closeErrorModal() {
            document.getElementById('emailDetailModal').style.display = 'none';
        }

        // Reenvío de correo con SweetAlert2
        async function resendEmail(logId, email) {
            const confirmed = await Swal.fire({
                title: '¿Reenviar este correo?',
                html: `Se compilará nuevamente el PDF oficial con QR y se enviará a:<br><strong style="color: #60A5FA; font-family: monospace;">${email}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF5500',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Sí, reenviar ahora',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            });

            if (!confirmed.isConfirmed) return;

            Swal.fire({
                title: '📨 Enviando Correo...',
                html: 'Compilando boletos con código QR y conectando con el servidor SMTP...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            try {
                const res = await fetch(`/admin/registro-correos/${logId}/reenviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();

                if (data.success) {
                    await Swal.fire({
                        title: '¡Correo Entregado!',
                        text: data.message || 'El correo ha sido enviado con éxito.',
                        icon: 'success',
                        confirmButtonColor: '#10B981',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        title: 'Fallo al Enviar',
                        text: data.message || 'No se pudo entregar el correo.',
                        icon: 'error',
                        confirmButtonColor: '#EF4444',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    title: 'Error de Red',
                    text: 'Ocurrió un problema de comunicación con el servidor.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }
        }
    </script>
@endsection
