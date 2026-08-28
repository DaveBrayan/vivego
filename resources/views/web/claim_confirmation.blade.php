@extends('layouts.app')

@section('title', 'Constancia de Reclamación | Vive Go')

@push('styles')
<style>
    .confirm-page-root {
        background: #0A0A10;
        color: #F8FAFC;
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 5rem;
    }

    .confirm-wrapper {
        max-width: 850px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    .confirm-success-banner {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.05));
        border: 1px solid rgba(16, 185, 129, 0.35);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .confirm-icon-circle {
        width: 65px;
        height: 65px;
        background: #10B981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #FFFFFF;
        margin: 0 auto 1rem auto;
        box-shadow: 0 0 25px rgba(16, 185, 129, 0.5);
    }

    .confirm-title {
        font-family: var(--font-heading, 'Outfit', sans-serif);
        font-size: 1.85rem;
        font-weight: 900;
        color: #FFFFFF;
        margin-bottom: 0.5rem;
    }

    .confirm-code-badge {
        display: inline-block;
        background: rgba(255, 85, 0, 0.15);
        border: 1.5px solid #FF5500;
        color: #FF5500;
        font-size: 1.25rem;
        font-weight: 900;
        padding: 0.4rem 1.25rem;
        border-radius: 12px;
        margin: 0.75rem 0;
        letter-spacing: 0.05em;
        font-family: monospace;
    }

    /* Hoja Oficial de Reclamación */
    .sheet-card {
        background: #14141E;
        border: 1.5px solid rgba(255, 255, 255, 0.12);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        position: relative;
    }

    .sheet-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
        gap: 1.5rem;
    }

    .sheet-company-info h3 {
        margin: 0 0 0.25rem 0;
        font-size: 1.35rem;
        font-weight: 800;
        color: #FFFFFF;
    }

    .sheet-company-info p {
        margin: 0;
        color: #94A3B8;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .sheet-meta-box {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 0.85rem 1.25rem;
        text-align: right;
        min-width: 220px;
    }

    .sheet-meta-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #FF5500;
        margin-bottom: 0.2rem;
    }

    .sheet-meta-code {
        font-size: 1.15rem;
        font-weight: 900;
        color: #FFFFFF;
        font-family: monospace;
    }

    .sheet-meta-date {
        font-size: 0.8rem;
        color: #94A3B8;
    }

    /* Secciones de la Hoja */
    .sheet-section {
        margin-bottom: 1.75rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        padding: 1.25rem;
    }

    .sheet-section-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: #FF5500;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin: 0 0 0.85rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        padding-bottom: 0.4rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sheet-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .sheet-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }

    .sheet-item {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .sheet-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
    }

    .sheet-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #FFFFFF;
        line-height: 1.4;
    }

    .sheet-type-pill {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .sheet-type-reclamo {
        background: rgba(255, 85, 0, 0.2);
        color: #FF5500;
        border: 1px solid #FF5500;
    }

    .sheet-type-queja {
        background: rgba(0, 242, 254, 0.2);
        color: #00F2FE;
        border: 1px solid #00F2FE;
    }

    .sheet-status-pill {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 800;
        background: rgba(245, 158, 11, 0.2);
        color: #FBBF24;
        border: 1px solid #F59E0B;
    }

    /* Botones de Acción */
    .sheet-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.95rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-print {
        background: linear-gradient(135deg, #FF5500, #FF1E3C);
        color: #FFFFFF;
        border: none;
        box-shadow: 0 4px 18px rgba(255, 85, 0, 0.4);
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.5);
    }

    .btn-home {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #FFFFFF;
    }

    .btn-home:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #FFFFFF;
    }

    /* Print Styles */
    @media print {
        body {
            background: #FFFFFF !important;
            color: #000000 !important;
        }
        .top-banner, .navbar-header, .footer-institutional, .sheet-actions, .confirm-success-banner {
            display: none !important;
        }
        .confirm-page-root {
            background: #FFFFFF !important;
            padding: 0 !important;
        }
        .sheet-card {
            background: #FFFFFF !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
            box-shadow: none !important;
            padding: 1.5rem !important;
        }
        .sheet-company-info h3, .sheet-meta-code, .sheet-value {
            color: #000000 !important;
        }
        .sheet-company-info p, .sheet-label, .sheet-meta-date {
            color: #555555 !important;
        }
        .sheet-meta-box, .sheet-section {
            background: #FAFAFA !important;
            border: 1px solid #CCCCCC !important;
        }
        .sheet-section-title {
            color: #000000 !important;
            border-bottom: 1px solid #CCCCCC !important;
        }
    }

    @media (max-width: 768px) {
        .sheet-card {
            padding: 1.35rem;
        }
        .sheet-header {
            flex-direction: column;
            align-items: stretch;
        }
        .sheet-meta-box {
            text-align: left;
        }
        .sheet-grid-2, .sheet-grid-3 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="confirm-page-root">
    <div class="confirm-wrapper">
        
        <!-- Banner de Éxito -->
        <div class="confirm-success-banner">
            <div class="confirm-icon-circle">✓</div>
            <h1 class="confirm-title">¡Hoja de Reclamación Registrada!</h1>
            <p style="color: #CBD5E1; font-size: 0.95rem; margin: 0;">
                Tu requerimiento ha sido registrado en nuestro <strong>Libro de Reclamaciones Virtual</strong> con el siguiente código de seguimiento:
            </p>
            <div class="confirm-code-badge">{{ $claim->claim_number }}</div>
            <p style="color: #94A3B8; font-size: 0.85rem; margin: 0;">
                Hemos registrado tu solicitud con fecha <strong>{{ $claim->created_at->format('d/m/Y H:i') }}</strong>. Conforme a la Ley N.° 29571, recibirás una respuesta formal en un plazo máximo de <strong>15 días hábiles</strong> (fecha límite: {{ $claim->legal_deadline->format('d/m/Y') }}).
            </p>
        </div>

        <!-- Hoja de Reclamación Imprimible -->
        <div class="sheet-card" id="printableClaimSheet">
            <!-- Header Oficial -->
            <div class="sheet-header">
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <img src="{{ asset('images/libro_de_reclamaciones.png') }}" 
                         alt="Libro de Reclamaciones" 
                         style="width: 100px; height: auto; object-fit: contain; filter: drop-shadow(0 6px 14px rgba(0,0,0,0.5));"
                         onerror="this.src='{{ asset('images/libro_de_reclamaciones.jpeg') }}'">
                    <div class="sheet-company-info">
                        <h3>IPEXA S.A.C</h3>
                        <p><strong>RUC:</strong> 20606476231</p>
                        <p><strong>Dirección Fiscal:</strong> JR APRINACOCHAZ Nº 11</p>
                        <p><strong>Plataforma Digital:</strong> VIVEGO.PE</p>
                    </div>
                </div>

                <div class="sheet-meta-box">
                    <div class="sheet-meta-title">HOJA DE RECLAMACIÓN VIRTUAL</div>
                    <div class="sheet-meta-code">{{ $claim->claim_number }}</div>
                    <div class="sheet-meta-date">Fecha: {{ $claim->created_at->format('d/m/Y H:i:s') }}</div>
                    <div style="margin-top: 0.4rem;">
                        <span class="sheet-status-pill">{{ $claim->status }}</span>
                    </div>
                </div>
            </div>

            <!-- 1. Consumidor Reclamante -->
            <div class="sheet-section">
                <div class="sheet-section-title">
                    <span>1. Identificación del Consumidor Reclamante</span>
                    <span style="font-size: 0.75rem; color: #94A3B8; text-transform: uppercase;">Persona {{ ucfirst($claim->person_type) }}</span>
                </div>
                <div class="sheet-grid-3">
                    <div class="sheet-item">
                        <span class="sheet-label">Nombre / Razón Social</span>
                        <span class="sheet-value">{{ $claim->full_name }}</span>
                    </div>
                    <div class="sheet-item">
                        <span class="sheet-label">Documento de Identidad</span>
                        <span class="sheet-value">{{ $claim->document_type }}: {{ $claim->document_number }}</span>
                    </div>
                    <div class="sheet-item">
                        <span class="sheet-label">Teléfono / Celular</span>
                        <span class="sheet-value">{{ $claim->phone }}</span>
                    </div>
                </div>
                <div class="sheet-grid-2" style="margin-top: 0.75rem;">
                    <div class="sheet-item">
                        <span class="sheet-label">Correo Electrónico</span>
                        <span class="sheet-value">{{ $claim->email }}</span>
                    </div>
                    <div class="sheet-item">
                        <span class="sheet-label">Domicilio</span>
                        <span class="sheet-value">{{ $claim->address }} {{ $claim->district ? '- ' . $claim->district : '' }} {{ $claim->department ? '(' . $claim->department . ')' : '' }}</span>
                    </div>
                </div>

                @if($claim->is_minor)
                <div style="margin-top: 0.85rem; padding-top: 0.65rem; border-top: 1px dashed rgba(255,255,255,0.1);">
                    <span class="sheet-label" style="color: #FF5500;">Padre / Madre / Apoderado Legal:</span>
                    <div class="sheet-value" style="font-size: 0.85rem;">
                        {{ $claim->parent_name }} ({{ $claim->parent_document_type ?? 'DNI' }}: {{ $claim->parent_document_number }})
                    </div>
                </div>
                @endif
            </div>

            <!-- 2. Bien Contratado -->
            <div class="sheet-section">
                <div class="sheet-section-title">
                    <span>2. Identificación del Bien Contratado</span>
                    <span class="sheet-type-pill {{ $claim->claim_type === 'RECLAMO' ? 'sheet-type-reclamo' : 'sheet-type-queja' }}">
                        {{ $claim->contracted_good_type }}
                    </span>
                </div>
                <div class="sheet-grid-3">
                    <div class="sheet-item">
                        <span class="sheet-label">Tipo de Bien</span>
                        <span class="sheet-value">{{ $claim->contracted_good_type }}</span>
                    </div>
                    <div class="sheet-item">
                        <span class="sheet-label">Monto Reclamado</span>
                        <span class="sheet-value">S/. {{ number_format((float)$claim->claimed_amount, 2) }}</span>
                    </div>
                    <div class="sheet-item">
                        <span class="sheet-label">N.° Compra / Boleto</span>
                        <span class="sheet-value">{{ $claim->order_code ?: 'No especificado' }}</span>
                    </div>
                </div>
                <div class="sheet-item" style="margin-top: 0.75rem;">
                    <span class="sheet-label">Descripción del Producto o Servicio</span>
                    <span class="sheet-value">{{ $claim->good_description }}</span>
                </div>
                @if($claim->event)
                <div class="sheet-item" style="margin-top: 0.5rem;">
                    <span class="sheet-label">Evento Asociado</span>
                    <span class="sheet-value" style="color: #FF5500;">{{ $claim->event->title }}</span>
                </div>
                @endif
            </div>

            <!-- 3. Detalle de la Reclamación -->
            <div class="sheet-section">
                <div class="sheet-section-title">
                    <span>3. Detalle de la Reclamación</span>
                    <span class="sheet-type-pill {{ $claim->claim_type === 'RECLAMO' ? 'sheet-type-reclamo' : 'sheet-type-queja' }}">
                        {{ $claim->claim_type }}
                    </span>
                </div>
                <div class="sheet-item" style="margin-bottom: 1rem;">
                    <span class="sheet-label">Detalle de los Hechos</span>
                    <div class="sheet-value" style="white-space: pre-wrap; background: rgba(0,0,0,0.25); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">{{ $claim->claim_detail }}</div>
                </div>
                <div class="sheet-item">
                    <span class="sheet-label">Pedido Concreto del Consumidor</span>
                    <div class="sheet-value" style="white-space: pre-wrap; background: rgba(0,0,0,0.25); padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">{{ $claim->consumer_request }}</div>
                </div>
            </div>

            @if($claim->admin_response)
            <!-- 4. Respuesta de la Empresa -->
            <div class="sheet-section" style="border-color: rgba(16, 185, 129, 0.4); background: rgba(16, 185, 129, 0.05);">
                <div class="sheet-section-title" style="color: #10B981;">
                    <span>4. Respuesta Oficial de la Empresa</span>
                    <span style="font-size: 0.75rem; color: #10B981;">Fecha: {{ $claim->admin_response_date ? $claim->admin_response_date->format('d/m/Y') : '' }}</span>
                </div>
                <div class="sheet-value" style="white-space: pre-wrap;">{{ $claim->admin_response }}</div>
            </div>
            @endif

            <div style="font-size: 0.75rem; color: #94A3B8; line-height: 1.4; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1rem; margin-top: 1rem;">
                * <strong>RECLAMO:</strong> Disconformidad relacionada a los productos o servicios.<br>
                * <strong>QUEJA:</strong> Disconformidad no relacionada a los productos o servicios; malestar o descontento respecto a la atención al público.<br>
                * <em>Conforme al D.S. N.° 011-2011-PCM y modificatorias, el proveedor deberá dar respuesta al reclamo o queja en un plazo no mayor a quince (15) días hábiles improrrogables.</em>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="sheet-actions">
            <button type="button" onclick="window.print()" class="btn-action btn-print">
                🖨️ Imprimir / Guardar en PDF
            </button>
            <a href="{{ route('web.home') }}" class="btn-action btn-home">
                🏠 Volver al Inicio
            </a>
            <a href="{{ route('web.terms') }}" class="btn-action btn-home">
                📜 Términos y Condiciones
            </a>
        </div>

    </div>
</div>
@endsection
