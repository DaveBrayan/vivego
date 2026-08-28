@extends('layouts.app')

@section('title', 'Libro de Reclamaciones | Panel Administrativo Vive Go')

@push('styles')
<style>
    .claim-badge-type {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .badge-type-reclamo {
        background: rgba(255, 85, 0, 0.15);
        color: #FF5500;
        border: 1px solid rgba(255, 85, 0, 0.35);
    }
    .badge-type-queja {
        background: rgba(0, 242, 254, 0.15);
        color: #00F2FE;
        border: 1px solid rgba(0, 242, 254, 0.35);
    }

    .claim-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 800;
    }
    .status-pendiente {
        background: rgba(245, 158, 11, 0.15);
        color: #FBBF24;
        border: 1px solid rgba(245, 158, 11, 0.35);
    }
    .status-en_proceso {
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
        border: 1px solid rgba(59, 130, 246, 0.35);
    }
    .status-atendido {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
        border: 1px solid rgba(16, 185, 129, 0.35);
    }
    .status-anulado {
        background: rgba(239, 68, 68, 0.15);
        color: #F87171;
        border: 1px solid rgba(239, 68, 68, 0.35);
    }

    .filter-pill-btn {
        padding: 0.45rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #CBD5E1;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .filter-pill-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #FFFFFF;
    }
    .filter-pill-btn.active {
        background: #FF5500;
        border-color: #FF5500;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(255, 85, 0, 0.35);
    }

    /* Modal Backdrop */
    .claims-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .claims-modal-backdrop.show {
        display: flex;
    }
    .claims-modal-box {
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        max-width: 850px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2rem;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        color: #FFFFFF;
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
            <div class="dash-search-container">
                <span class="dash-search-icon">🔍</span>
                <input type="text" id="claimsSearchInput" class="dash-search-input" placeholder="Buscar por código, reclamante, DNI, correo o evento...">
                <span class="dash-kbd-shortcut">⌘K</span>
            </div>

            <div class="dash-top-actions">
                <a href="{{ route('web.claim_book') }}" target="_blank" class="dash-icon-btn" title="Ver Formulario Público" style="text-decoration: none; width: auto; padding: 0 1rem; gap: 0.4rem; font-size: 0.85rem; font-weight: 700; color: #CBD5E1;">
                    <span>🌐</span> Ver Formulario Web
                </a>
            </div>
        </header>

        <div class="dash-container">
            <!-- BANNER DE ENCABEZADO PRO -->
            <div class="settings-header-banner">
                <div>
                    <span class="settings-tag">⚖️ LIBRO DE RECLAMACIONES & ATENCIÓN AL CLIENTE</span>
                    <h1 class="settings-page-title">Gestión de Reclamaciones</h1>
                    <p class="settings-page-subtitle">
                        Revisa, gestiona y responde a los reclamos y quejas virtuales registrados en <strong>VIVEGO.PE</strong> conforme al plazo legal de 15 días hábiles (Ley N.° 29571).
                    </p>
                </div>
            </div>

            <!-- CARDS DE MÉTRICAS RÁPIDAS -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.15rem; margin-bottom: 1.75rem;">
                <!-- Total -->
                <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 255, 255, 0.08); color: #FFFFFF; font-size: 1.35rem; display: flex; align-items: center; justify-content: center;">
                        📖
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Total Registrados</span>
                        <h3 id="statTotal" style="font-size: 1.5rem; font-weight: 900; color: #FFFFFF; margin: 0;">{{ $stats['total'] }}</h3>
                    </div>
                </div>

                <!-- Reclamos -->
                <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 85, 0, 0.15); border: 1px solid rgba(255, 85, 0, 0.3); color: #FF5500; font-size: 1.35rem; display: flex; align-items: center; justify-content: center;">
                        ⚠️
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Reclamos</span>
                        <h3 id="statReclamos" style="font-size: 1.5rem; font-weight: 900; color: #FF5500; margin: 0;">{{ $stats['reclamos'] }}</h3>
                    </div>
                </div>

                <!-- Quejas -->
                <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(0, 242, 254, 0.15); border: 1px solid rgba(0, 242, 254, 0.3); color: #00F2FE; font-size: 1.35rem; display: flex; align-items: center; justify-content: center;">
                        📢
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Quejas</span>
                        <h3 id="statQuejas" style="font-size: 1.5rem; font-weight: 900; color: #00F2FE; margin: 0;">{{ $stats['quejas'] }}</h3>
                    </div>
                </div>

                <!-- Pendientes -->
                <div style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 16px; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.2); color: #FBBF24; font-size: 1.35rem; display: flex; align-items: center; justify-content: center;">
                        ⏳
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #FDE68A; font-weight: 700; text-transform: uppercase;">Pendientes</span>
                        <h3 id="statPendientes" style="font-size: 1.5rem; font-weight: 900; color: #FBBF24; margin: 0;">{{ $stats['pendientes'] }}</h3>
                    </div>
                </div>

                <!-- Atendidos -->
                <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 1.15rem; display: flex; align-items: center; gap: 0.85rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.2); color: #34D399; font-size: 1.35rem; display: flex; align-items: center; justify-content: center;">
                        ✅
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #A7F3D0; font-weight: 700; text-transform: uppercase;">Atendidos</span>
                        <h3 id="statAtendidos" style="font-size: 1.5rem; font-weight: 900; color: #34D399; margin: 0;">{{ $stats['atendidos'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- FILTROS Y BÚSQUEDA -->
            <div style="background: #14141E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;" id="statusFilterPills">
                    <button type="button" class="filter-pill-btn active" data-filter-status="all">Todos ({{ $stats['total'] }})</button>
                    <button type="button" class="filter-pill-btn" data-filter-status="Pendiente">⏳ Pendientes ({{ $stats['pendientes'] }})</button>
                    <button type="button" class="filter-pill-btn" data-filter-status="En Proceso">🔄 En Proceso ({{ $stats['en_proceso'] }})</button>
                    <button type="button" class="filter-pill-btn" data-filter-status="Atendido">✅ Atendidos ({{ $stats['atendidos'] }})</button>
                    <button type="button" class="filter-pill-btn" data-filter-status="Anulado">❌ Anulados ({{ $stats['anulados'] }})</button>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <select id="typeFilterSelect" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; padding: 0.45rem 0.85rem; color: #FFFFFF; font-size: 0.825rem; outline: none;">
                        <option value="all">Tipo: Todos</option>
                        <option value="RECLAMO">⚠️ Sólo Reclamos</option>
                        <option value="QUEJA">📢 Sólo Quejas</option>
                    </select>
                </div>
            </div>

            <!-- TABLA DE RECLAMACIONES -->
            <div class="dash-table-card" style="background: #14141E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 18px; overflow: hidden;">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="dash-table" id="claimsTable" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.775rem; text-transform: uppercase; color: #94A3B8; letter-spacing: 0.05em;">
                                <th style="padding: 1rem 1.25rem;">N.° Hoja</th>
                                <th style="padding: 1rem;">Tipo</th>
                                <th style="padding: 1rem;">Consumidor / Reclamante</th>
                                <th style="padding: 1rem;">Bien Contratado</th>
                                <th style="padding: 1rem;">Monto</th>
                                <th style="padding: 1rem;">Fecha & Límite</th>
                                <th style="padding: 1rem;">Estado</th>
                                <th style="padding: 1rem 1.25rem; text-align: right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="claimsTableBody">
                            @forelse($claims as $c)
                                <tr class="claim-row" 
                                    data-id="{{ $c->id }}"
                                    data-code="{{ $c->claim_number }}"
                                    data-status="{{ $c->status }}" 
                                    data-type="{{ $c->claim_type }}"
                                    data-search="{{ strtolower($c->claim_number . ' ' . $c->full_name . ' ' . $c->document_number . ' ' . $c->email . ' ' . $c->phone . ' ' . $c->order_code . ' ' . ($c->event ? $c->event->title : '')) }}"
                                    style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.875rem; transition: background 0.15s ease;">
                                    
                                    <!-- N.° Hoja -->
                                    <td style="padding: 1rem 1.25rem; font-family: monospace; font-weight: 800; color: #FF5500;">
                                        {{ $c->claim_number }}
                                    </td>

                                    <!-- Tipo -->
                                    <td style="padding: 1rem;">
                                        <span class="claim-badge-type {{ $c->claim_type === 'RECLAMO' ? 'badge-type-reclamo' : 'badge-type-queja' }}">
                                            {{ $c->claim_type === 'RECLAMO' ? '⚠️ Reclamo' : '📢 Queja' }}
                                        </span>
                                    </td>

                                    <!-- Consumidor -->
                                    <td style="padding: 1rem;">
                                        <div style="font-weight: 700; color: #FFFFFF;">{{ $c->full_name }}</div>
                                        <div style="font-size: 0.775rem; color: #94A3B8;">
                                            {{ $c->document_type }}: {{ $c->document_number }} • 📞 {{ $c->phone }}
                                        </div>
                                        <div style="font-size: 0.775rem; color: #64748B;">{{ $c->email }}</div>
                                    </td>

                                    <!-- Bien Contratado -->
                                    <td style="padding: 1rem;">
                                        <div style="color: #CBD5E1; font-weight: 600;">{{ $c->contracted_good_type }}</div>
                                        <div style="font-size: 0.775rem; color: #94A3B8; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $c->good_description }}">
                                            {{ $c->good_description }}
                                        </div>
                                        @if($c->event)
                                            <div style="font-size: 0.75rem; color: #FF5500;">🎟️ {{ $c->event->title }}</div>
                                        @endif
                                    </td>

                                    <!-- Monto -->
                                    <td style="padding: 1rem; font-weight: 700; color: #FFFFFF;">
                                        {{ $c->claimed_amount > 0 ? 'S/. ' . number_format((float)$c->claimed_amount, 2) : '-' }}
                                    </td>

                                    <!-- Fecha & Límite -->
                                    <td style="padding: 1rem;">
                                        <div style="color: #CBD5E1;">{{ $c->created_at ? $c->created_at->format('d/m/Y') : '-' }}</div>
                                        <div style="font-size: 0.75rem; color: #94A3B8;">
                                            Plazo: {{ $c->legal_deadline ? $c->legal_deadline->format('d/m/Y') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Estado -->
                                    <td style="padding: 1rem;">
                                        @php
                                            $statusClass = match($c->status) {
                                                'Pendiente' => 'status-pendiente',
                                                'En Proceso' => 'status-en_proceso',
                                                'Atendido' => 'status-atendido',
                                                'Anulado' => 'status-anulado',
                                                default => 'status-pendiente'
                                            };
                                        @endphp
                                        <span class="claim-badge-status {{ $statusClass }}">
                                            {{ $c->status }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td style="padding: 1rem 1.25rem; text-align: right;">
                                        <div style="display: inline-flex; gap: 0.4rem;">
                                            <button type="button" onclick="openClaimModal({{ $c->id }})" title="Revisar & Responder" style="padding: 0.45rem 0.85rem; background: linear-gradient(135deg, #FF5500, #FF1E3C); border: none; border-radius: 8px; color: #FFFFFF; font-weight: 800; font-size: 0.8rem; cursor: pointer;">
                                                🔍 Revisar
                                            </button>
                                            <a href="{{ route('web.claim_book.confirmation', ['code' => $c->claim_number]) }}" target="_blank" title="Ver Constancia Oficial" style="padding: 0.45rem 0.65rem; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; color: #CBD5E1; text-decoration: none; font-size: 0.8rem; display: flex; align-items: center;">
                                                📄
                                            </a>
                                            <button type="button" onclick="deleteClaim({{ $c->id }}, '{{ $c->claim_number }}')" title="Eliminar Registro" style="padding: 0.45rem 0.65rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; color: #EF4444; font-size: 0.8rem; cursor: pointer;">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noClaimsRow">
                                    <td colspan="8" style="padding: 3rem; text-align: center; color: #94A3B8;">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📖</div>
                                        <h4 style="color: #FFFFFF; margin: 0 0 0.25rem 0;">No hay hojas de reclamación registradas</h4>
                                        <p style="margin: 0; font-size: 0.85rem;">Los reclamos y quejas virtuales registrados desde el footer aparecerán aquí.</p>
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

<!-- MODAL DE DETALLE Y RESPUESTA DE RECLAMO -->
<div id="claimModal" class="claims-modal-backdrop">
    <div class="claims-modal-box">
        <!-- Header Modal -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <div>
                <span style="font-size: 0.8rem; font-weight: 800; color: #FF5500; text-transform: uppercase;">HOJA DE RECLAMACIÓN VIRTUAL</span>
                <h2 id="modalClaimCode" style="font-size: 1.5rem; font-weight: 900; margin: 0.2rem 0; font-family: monospace;">-</h2>
                <div style="font-size: 0.85rem; color: #94A3B8;" id="modalClaimMeta">-</div>
            </div>
            <button type="button" onclick="closeClaimModal()" style="background: none; border: none; color: #94A3B8; font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>

        <!-- Contenido Dividido en Tarjetas -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <!-- 1. Consumidor -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 1rem;">
                <h4 style="font-size: 0.85rem; font-weight: 800; color: #FF5500; text-transform: uppercase; margin: 0 0 0.6rem 0;">1. Consumidor</h4>
                <div style="font-size: 0.9rem; line-height: 1.6;">
                    <div><strong>Nombre:</strong> <span id="modalFullName">-</span></div>
                    <div><strong>Documento:</strong> <span id="modalDocument">-</span></div>
                    <div><strong>Teléfono:</strong> <span id="modalPhone">-</span></div>
                    <div><strong>Correo:</strong> <span id="modalEmail">-</span></div>
                    <div><strong>Domicilio:</strong> <span id="modalAddress">-</span></div>
                    <div id="modalMinorBox" style="display: none; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px dashed rgba(255,255,255,0.1); color: #FBBF24;">
                        <strong>Apoderado:</strong> <span id="modalParentInfo">-</span>
                    </div>
                </div>
            </div>

            <!-- 2. Bien Contratado -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 1rem;">
                <h4 style="font-size: 0.85rem; font-weight: 800; color: #FF5500; text-transform: uppercase; margin: 0 0 0.6rem 0;">2. Bien Contratado</h4>
                <div style="font-size: 0.9rem; line-height: 1.6;">
                    <div><strong>Tipo:</strong> <span id="modalGoodType">-</span></div>
                    <div><strong>Monto:</strong> <span id="modalAmount">-</span></div>
                    <div><strong>N.° Compra:</strong> <span id="modalOrderCode">-</span></div>
                    <div><strong>Descripción:</strong> <span id="modalGoodDesc">-</span></div>
                    <div id="modalEventBox" style="display: none; color: #FF5500;">
                        <strong>Evento:</strong> <span id="modalEventTitle">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Detalle del Reclamo y Pedido -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 1.15rem; margin-bottom: 1.5rem;">
            <h4 style="font-size: 0.85rem; font-weight: 800; color: #FF5500; text-transform: uppercase; margin: 0 0 0.6rem 0;">3. Detalle de los Hechos & Pedido</h4>
            
            <div style="margin-bottom: 0.85rem;">
                <span style="font-size: 0.75rem; color: #94A3B8; text-transform: uppercase; font-weight: 700;">Detalle de lo Ocurrido:</span>
                <div id="modalClaimDetail" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 0.75rem; font-size: 0.9rem; white-space: pre-wrap; margin-top: 0.25rem;">-</div>
            </div>

            <div>
                <span style="font-size: 0.75rem; color: #94A3B8; text-transform: uppercase; font-weight: 700;">Pedido Concreto del Reclamante:</span>
                <div id="modalConsumerRequest" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 0.75rem; font-size: 0.9rem; white-space: pre-wrap; margin-top: 0.25rem;">-</div>
            </div>
        </div>

        <!-- 4. Formulario de Respuesta Oficial -->
        <form id="respondClaimForm" onsubmit="submitClaimResponse(event)">
            @csrf
            <input type="hidden" id="modalClaimId" name="claim_id">

            <div style="background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 14px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.9rem; font-weight: 800; color: #10B981; text-transform: uppercase; margin: 0 0 1rem 0;">4. Respuesta Oficial de la Empresa & Estado</h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Estado del Reclamo *</label>
                        <select name="status" id="modalStatusSelect" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(255,255,255,0.15); border-radius: 10px; color: #FFFFFF; font-size: 0.9rem; outline: none;">
                            <option value="Pendiente">⏳ Pendiente</option>
                            <option value="En Proceso">🔄 En Proceso</option>
                            <option value="Atendido">✅ Atendido / Resuelto</option>
                            <option value="Anulado">❌ Anulado</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Fecha Límite Legal (15 días)</label>
                        <input type="text" id="modalDeadlineInput" readonly style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; color: #CBD5E1; font-size: 0.9rem; outline: none;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Respuesta Oficial al Consumidor *</label>
                    <textarea name="admin_response" id="modalAdminResponse" rows="4" required placeholder="Redacta la respuesta formal que será consignada en la Hoja de Reclamación..." style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(255,255,255,0.15); border-radius: 10px; color: #FFFFFF; font-size: 0.9rem; outline: none; resize: vertical; box-sizing: border-box;"></textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #CBD5E1; text-transform: uppercase; margin-bottom: 0.4rem;">Notas Internas del Equipo (Privadas)</label>
                    <input type="text" name="admin_notes" id="modalAdminNotes" placeholder="Comentarios internos que no se muestran al cliente..." style="width: 100%; padding: 0.65rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #CBD5E1; font-size: 0.85rem; outline: none; box-sizing: border-box;">
                </div>
            </div>

            <!-- Botones del Modal -->
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center;">
                <a id="modalPrintLink" href="#" target="_blank" style="padding: 0.75rem 1.25rem; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; color: #FFFFFF; font-weight: 700; text-decoration: none; font-size: 0.9rem;">
                    📄 Ver Constancia
                </a>
                <button type="button" onclick="closeClaimModal()" style="padding: 0.75rem 1.25rem; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: #CBD5E1; font-weight: 700; cursor: pointer;">
                    Cerrar
                </button>
                <button type="submit" id="btnSaveResponse" style="padding: 0.75rem 1.75rem; background: linear-gradient(135deg, #10B981, #059669); border: none; border-radius: 12px; color: #FFFFFF; font-weight: 800; cursor: pointer; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                    💾 Guardar Respuesta
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Filtros de tabla
    let currentStatusFilter = 'all';
    let currentTypeFilter = 'all';
    let currentSearchTerm = '';

    const rows = document.querySelectorAll('.claim-row');
    const searchInput = document.getElementById('claimsSearchInput');
    const typeSelect = document.getElementById('typeFilterSelect');
    const statusPills = document.querySelectorAll('#statusFilterPills button');

    function applyFilters() {
        let visibleCount = 0;
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const type = row.getAttribute('data-type');
            const search = row.getAttribute('data-search') || '';

            const matchStatus = (currentStatusFilter === 'all' || status === currentStatusFilter);
            const matchType = (currentTypeFilter === 'all' || type === currentTypeFilter);
            const matchSearch = (currentSearchTerm === '' || search.includes(currentSearchTerm));

            if (matchStatus && matchType && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noRow = document.getElementById('noClaimsRow');
        if (noRow) {
            noRow.style.display = (visibleCount === 0) ? '' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            currentSearchTerm = this.value.toLowerCase().trim();
            applyFilters();
        });
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            currentTypeFilter = this.value;
            applyFilters();
        });
    }

    statusPills.forEach(pill => {
        pill.addEventListener('click', function() {
            statusPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentStatusFilter = this.getAttribute('data-filter-status');
            applyFilters();
        });
    });

    // Abrir Modal de Detalle
    function openClaimModal(claimId) {
        fetch(`/admin/reclamaciones/${claimId}/detalle`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const c = data.claim;
                    document.getElementById('modalClaimId').value = c.id;
                    document.getElementById('modalClaimCode').innerText = c.claim_number;
                    document.getElementById('modalClaimMeta').innerText = `Fecha: ${data.created_formatted} • Tipo: ${c.claim_type} (${c.contracted_good_type})`;

                    document.getElementById('modalFullName').innerText = c.full_name;
                    document.getElementById('modalDocument').innerText = `${c.document_type}: ${c.document_number}`;
                    document.getElementById('modalPhone').innerText = c.phone;
                    document.getElementById('modalEmail').innerText = c.email;
                    document.getElementById('modalAddress').innerText = `${c.address} ${c.district ? '- ' + c.district : ''} ${c.department ? '(' + c.department + ')' : ''}`;

                    const minorBox = document.getElementById('modalMinorBox');
                    if (c.is_minor) {
                        minorBox.style.display = 'block';
                        document.getElementById('modalParentInfo').innerText = `${c.parent_name} (${c.parent_document_type || 'DNI'}: ${c.parent_document_number})`;
                    } else {
                        minorBox.style.display = 'none';
                    }

                    document.getElementById('modalGoodType').innerText = c.contracted_good_type;
                    document.getElementById('modalAmount').innerText = c.claimed_amount > 0 ? `S/. ${parseFloat(c.claimed_amount).toFixed(2)}` : 'No especificado';
                    document.getElementById('modalOrderCode').innerText = c.order_code || 'No especificado';
                    document.getElementById('modalGoodDesc').innerText = c.good_description;

                    const eventBox = document.getElementById('modalEventBox');
                    if (data.event_title) {
                        eventBox.style.display = 'block';
                        document.getElementById('modalEventTitle').innerText = data.event_title;
                    } else {
                        eventBox.style.display = 'none';
                    }

                    document.getElementById('modalClaimDetail').innerText = c.claim_detail;
                    document.getElementById('modalConsumerRequest').innerText = c.consumer_request;

                    document.getElementById('modalStatusSelect').value = c.status;
                    document.getElementById('modalDeadlineInput').value = `${data.deadline_formatted} ${data.is_overdue ? '⚠️ (Plazo Vencido)' : '✓'}`;
                    document.getElementById('modalAdminResponse').value = c.admin_response || '';
                    document.getElementById('modalAdminNotes').value = c.admin_notes || '';

                    document.getElementById('modalPrintLink').href = `/libro-de-reclamaciones/constancia/${c.claim_number}`;

                    document.getElementById('claimModal').classList.add('show');
                }
            })
            .catch(err => {
                alert('Error al cargar los datos del reclamo.');
                console.error(err);
            });
    }

    function closeClaimModal() {
        document.getElementById('claimModal').classList.remove('show');
    }

    // Enviar Respuesta
    function submitClaimResponse(e) {
        e.preventDefault();
        const form = document.getElementById('respondClaimForm');
        const claimId = document.getElementById('modalClaimId').value;
        const btn = document.getElementById('btnSaveResponse');

        btn.disabled = true;
        btn.innerText = 'Guardando...';

        const formData = new FormData(form);

        fetch(`/admin/reclamaciones/${claimId}/responder`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = '💾 Guardar Respuesta';

            if (data.success) {
                alert(data.message);
                closeClaimModal();
                window.location.reload();
            } else {
                alert('Error al guardar la respuesta.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = '💾 Guardar Respuesta';
            alert('Error en la comunicación con el servidor.');
            console.error(err);
        });
    }

    // Eliminar Reclamo
    function deleteClaim(claimId, code) {
        if (!confirm(`¿Estás seguro de eliminar el registro de la Hoja de Reclamación "${code}"? Esta acción no se puede deshacer.`)) {
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        fetch(`/admin/reclamaciones/${claimId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                const row = document.querySelector(`.claim-row[data-id="${claimId}"]`);
                if (row) row.remove();
            }
        })
        .catch(err => {
            alert('Error al eliminar la reclamación.');
            console.error(err);
        });
    }
</script>
@endpush
@endsection
