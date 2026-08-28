@extends('layouts.app')

@section('title', 'Libro de Reclamaciones Virtual | Vive Go')

@push('styles')
<style>
    .claim-page-root {
        background: #0A0A10;
        color: #F8FAFC;
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 5rem;
    }

    .claim-hero {
        background: radial-gradient(circle at 50% 0%, rgba(255, 85, 0, 0.2) 0%, rgba(15, 15, 20, 0) 70%),
                    linear-gradient(180deg, #14141E 0%, #0A0A10 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 3rem 1rem 2.25rem 1rem;
        margin-bottom: 2rem;
    }

    .claim-hero-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2.25rem;
        max-width: 950px;
        margin: 0 auto;
        text-align: left;
    }

    .claim-book-visual-wrapper {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .claim-book-hero-img {
        width: 190px;
        max-width: 100%;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 14px 28px rgba(0, 0, 0, 0.7)) drop-shadow(0 0 18px rgba(255, 85, 0, 0.3));
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        background: transparent !important;
    }

    .claim-book-hero-img:hover {
        transform: scale(1.06) rotate(-2deg);
    }

    .claim-hero-text {
        flex: 1;
    }

    .claim-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        background: rgba(255, 85, 0, 0.12);
        border: 1px solid rgba(255, 85, 0, 0.3);
        color: #FF5500;
        font-size: 0.825rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.85rem;
    }

    .claim-title {
        font-family: var(--font-heading, 'Outfit', sans-serif);
        font-size: 2.5rem;
        font-weight: 900;
        color: #FFFFFF;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
        line-height: 1.15;
    }

    .claim-subtitle {
        color: #94A3B8;
        font-size: 0.975rem;
        margin: 0;
        line-height: 1.55;
    }

    @media (max-width: 768px) {
        .claim-hero-content {
            flex-direction: column;
            text-align: center;
            gap: 1.25rem;
        }
        .claim-book-hero-img {
            width: 140px;
        }
    }

    .claim-form-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }

    /* Tarjeta Oficial de la Empresa */
    .company-official-card {
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.75rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .company-data-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .company-data-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #94A3B8;
        letter-spacing: 0.05em;
    }

    .company-data-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #FFFFFF;
    }

    /* Formulario Principal */
    .claim-main-form {
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 2.25rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }

    .form-step-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .form-step-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .step-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #FF5500, #FF1E3C);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(255, 85, 0, 0.35);
    }

    .step-title {
        font-family: var(--font-heading, 'Outfit', sans-serif);
        font-size: 1.25rem;
        font-weight: 800;
        color: #FFFFFF;
        margin: 0;
    }

    /* Selector de Tipo (Reclamo vs Queja) */
    .claim-type-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .claim-type-card {
        border: 2px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.03);
        border-radius: 14px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .claim-type-card:hover {
        border-color: rgba(255, 85, 0, 0.4);
        background: rgba(255, 85, 0, 0.04);
        transform: translateY(-2px);
    }

    .claim-type-card.selected {
        border-color: #FF5500;
        background: rgba(255, 85, 0, 0.08);
        box-shadow: 0 6px 20px rgba(255, 85, 0, 0.2);
    }

    .claim-type-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .claim-type-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .claim-type-icon {
        font-size: 1.4rem;
    }

    .claim-type-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: #FFFFFF;
    }

    .claim-type-desc {
        font-size: 0.825rem;
        color: #94A3B8;
        line-height: 1.4;
        margin: 0;
    }

    /* Form Fields */
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.15rem;
        margin-bottom: 1.15rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 800;
        color: #CBD5E1;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .form-label .required {
        color: #FF1E3C;
    }

    .form-control-dark {
        width: 100%;
        padding: 0.85rem 1rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1.5px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        color: #FFFFFF;
        font-size: 0.925rem;
        font-family: inherit;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .form-control-dark:focus {
        border-color: #FF5500;
        background: rgba(15, 23, 42, 0.9);
        box-shadow: 0 0 0 3px rgba(255, 85, 0, 0.18);
    }

    textarea.form-control-dark {
        resize: vertical;
        min-height: 100px;
        line-height: 1.5;
    }

    /* Radio pills */
    .pill-radio-group {
        display: flex;
        gap: 0.6rem;
    }

    .pill-radio-label {
        flex: 1;
        text-align: center;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #CBD5E1;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pill-radio-label:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #FFFFFF;
    }

    .pill-radio-label input {
        display: none;
    }

    .pill-radio-label.active {
        background: #FF5500;
        border-color: #FF5500;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3);
    }

    /* Menor de Edad Container */
    .minor-toggle-box {
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    /* Checkboxes de Declaración */
    .checkbox-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
        cursor: pointer;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #FF5500;
        margin-top: 0.15rem;
        cursor: pointer;
    }

    .checkbox-text {
        font-size: 0.85rem;
        color: #CBD5E1;
        line-height: 1.45;
    }

    .checkbox-text a {
        color: #FF5500;
        text-decoration: underline;
        font-weight: 700;
    }

    /* Submit Button */
    .btn-submit-claim {
        width: 100%;
        padding: 1.15rem;
        background: linear-gradient(135deg, #FF5500, #FF1E3C);
        border: none;
        border-radius: 14px;
        color: #FFFFFF;
        font-family: var(--font-heading, 'Outfit', sans-serif);
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(255, 85, 0, 0.4);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }

    .btn-submit-claim:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(255, 85, 0, 0.55);
    }

    .btn-submit-claim:active {
        transform: scale(0.98);
    }

    .btn-submit-claim:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Alerts */
    .claim-alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        color: #FCA5A5;
        font-size: 0.9rem;
    }

    .claim-alert-error ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.25rem;
    }

    @media (max-width: 768px) {
        .claim-main-form {
            padding: 1.35rem;
        }
        .claim-title {
            font-size: 1.9rem;
        }
        .claim-type-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="claim-page-root">
    <!-- Hero Header -->
    <div class="claim-hero">
        <div class="container">
            <div class="claim-hero-content">
                <div class="claim-book-visual-wrapper">
                    <img src="{{ asset('images/libro_de_reclamaciones.png') }}" 
                         alt="Libro de Reclamaciones ViveGo" 
                         class="claim-book-hero-img"
                         onerror="this.src='{{ asset('images/libro_de_reclamaciones.jpeg') }}'">
                </div>
                <div class="claim-hero-text">
                    <span class="claim-badge">⚖️ Conforme a Ley N.° 29571</span>
                    <h1 class="claim-title">Libro de Reclamaciones Virtual</h1>
                    <p class="claim-subtitle">
                        Plataforma oficial de atención de Reclamos y Quejas de <strong>VIVEGO.PE</strong> administrada por <strong>IPEXA S.A.C</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="claim-form-wrapper">

        <!-- Errores de Validación -->
        @if (isset($errors) && $errors->any())
            <div class="claim-alert-error">
                <strong>⚠️ Por favor corrige los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tarjeta Oficial de Identificación de la Empresa -->
        <div class="company-official-card">
            <div class="company-data-item">
                <span class="company-data-label">Razón Social</span>
                <span class="company-data-value">IPEXA S.A.C</span>
            </div>
            <div class="company-data-item">
                <span class="company-data-label">RUC</span>
                <span class="company-data-value">20606476231</span>
            </div>
            <div class="company-data-item">
                <span class="company-data-label">Dirección Fiscal</span>
                <span class="company-data-value">JR APRINACOCHAZ Nº 11</span>
            </div>
            <div class="company-data-item">
                <span class="company-data-label">Fecha y Hora</span>
                <span class="company-data-value" id="currentDateFormatted">{{ date('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('web.claim_book.store') }}" method="POST" class="claim-main-form" id="claimBookForm">
            @csrf

            <!-- 1. IDENTIFICACIÓN DEL CONSUMIDOR RECLAMANTE -->
            <div class="form-step-section">
                <div class="step-header">
                    <span class="step-number">1</span>
                    <h3 class="step-title">Identificación del Consumidor Reclamante</h3>
                </div>

                <!-- Tipo de Persona -->
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label">Tipo de Persona <span class="required">*</span></label>
                    <div class="pill-radio-group">
                        <label class="pill-radio-label active" id="labelPersonNatural">
                            <input type="radio" name="person_type" value="natural" checked onchange="handlePersonTypeChange('natural')">
                            👤 Persona Natural
                        </label>
                        <label class="pill-radio-label" id="labelPersonJuridica">
                            <input type="radio" name="person_type" value="juridica" onchange="handlePersonTypeChange('juridica')">
                            🏢 Persona Jurídica (Empresa)
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label class="form-label" id="labelFullName">Nombres y Apellidos Completos <span class="required">*</span></label>
                        <input type="text" name="full_name" class="form-control-dark" required value="{{ old('full_name') }}" placeholder="Ej. Juan Carlos Pérez Gómez">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Tipo de Documento <span class="required">*</span></label>
                        <select name="document_type" class="form-control-dark" required id="selectDocumentType">
                            <option value="DNI" {{ old('document_type') == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="CE" {{ old('document_type') == 'CE' ? 'selected' : '' }}>Carné de Extranjería (CE)</option>
                            <option value="PASAPORTE" {{ old('document_type') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="RUC" {{ old('document_type') == 'RUC' ? 'selected' : '' }}>RUC</option>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1.2;">
                        <label class="form-label">N.° de Documento <span class="required">*</span></label>
                        <input type="text" name="document_number" class="form-control-dark" required value="{{ old('document_number') }}" placeholder="Ej. 74859612">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control-dark" required value="{{ old('email') }}" placeholder="correo@ejemplo.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Teléfono / Celular <span class="required">*</span></label>
                        <input type="tel" name="phone" class="form-control-dark" required value="{{ old('phone') }}" placeholder="Ej. 987654321">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label class="form-label">Domicilio / Dirección <span class="required">*</span></label>
                        <input type="text" name="address" class="form-control-dark" required value="{{ old('address') }}" placeholder="Av. Principal 123, Dpto 401">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Departamento</label>
                        <input type="text" name="department" class="form-control-dark" value="{{ old('department', 'LIMA') }}" placeholder="LIMA">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Provincia / Distrito</label>
                        <input type="text" name="district" class="form-control-dark" value="{{ old('district') }}" placeholder="Ej. Miraflores">
                    </div>
                </div>

                <!-- Toggle Menor de Edad -->
                <div class="minor-toggle-box" id="minorToggleBox">
                    <label class="checkbox-item" style="margin-bottom: 0;">
                        <input type="checkbox" name="is_minor" value="1" id="checkIsMinor" onchange="toggleMinorFields(this.checked)" {{ old('is_minor') ? 'checked' : '' }}>
                        <span class="checkbox-text">
                            <strong>¿El reclamante es menor de edad?</strong> (Marcar esta casilla para consignar los datos del padre, madre o apoderado legal).
                        </span>
                    </label>

                    <div id="minorFieldsContainer" style="display: {{ old('is_minor') ? 'block' : 'none' }}; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08);">
                        <div class="form-row">
                            <div class="form-group" style="flex: 2;">
                                <label class="form-label">Nombres y Apellidos del Padre / Madre / Apoderado <span class="required">*</span></label>
                                <input type="text" name="parent_name" class="form-control-dark" value="{{ old('parent_name') }}" placeholder="Nombres del representante legal">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">Doc. del Apoderado</label>
                                <select name="parent_document_type" class="form-control-dark">
                                    <option value="DNI">DNI</option>
                                    <option value="CE">CE</option>
                                    <option value="PASAPORTE">Pasaporte</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1.2;">
                                <label class="form-label">N.° Documento Apoderado</label>
                                <input type="text" name="parent_document_number" class="form-control-dark" value="{{ old('parent_document_number') }}" placeholder="N.° Documento">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. IDENTIFICACIÓN DEL BIEN CONTRATADO -->
            <div class="form-step-section">
                <div class="step-header">
                    <span class="step-number">2</span>
                    <h3 class="step-title">Identificación del Bien Contratado</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tipo de Bien <span class="required">*</span></label>
                        <div class="pill-radio-group">
                            <label class="pill-radio-label active" id="labelBienServicio">
                                <input type="radio" name="contracted_good_type" value="SERVICIO" checked onchange="handleGoodTypeChange('SERVICIO')">
                                🎟️ Servicio (Entradas / Eventos)
                            </label>
                            <label class="pill-radio-label" id="labelBienProducto">
                                <input type="radio" name="contracted_good_type" value="PRODUCTO" onchange="handleGoodTypeChange('PRODUCTO')">
                                📦 Producto (Merchandising / Bien)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Monto Reclamado (S/.)</label>
                        <input type="number" step="0.01" name="claimed_amount" class="form-control-dark" value="{{ old('claimed_amount') }}" placeholder="0.00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1.5;">
                        <label class="form-label">Evento Relacionado (Opcional)</label>
                        <select name="event_id" class="form-control-dark">
                            <option value="">-- Seleccionar Evento (Si aplica) --</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>
                                    {{ $ev->title }} ({{ $ev->event_date ? date('d/m/Y', strtotime($ev->event_date)) : '' }})
                                </option>
                            @endforeach
                            <option value="">Otro / Servicio general de Plataforma ViveGo</option>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">N.° de Compra / Código de Entrada</label>
                        <input type="text" name="order_code" class="form-control-dark" value="{{ old('order_code') }}" placeholder="Ej. TK-123456 o VG-ORD-001">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción del Bien o Servicio Contratado <span class="required">*</span></label>
                    <input type="text" name="good_description" class="form-control-dark" required value="{{ old('good_description') }}" placeholder="Ej. Compra de 2 entradas VIP para el Concierto o Servicio de boletaje digital">
                </div>
            </div>

            <!-- 3. DETALLE DE LA RECLAMACIÓN -->
            <div class="form-step-section">
                <div class="step-header">
                    <span class="step-number">3</span>
                    <h3 class="step-title">Detalle de la Reclamación y Pedido</h3>
                </div>

                <label class="form-label">Tipo de Reclamación <span class="required">*</span></label>
                <div class="claim-type-grid">
                    <!-- Opción Reclamo -->
                    <div class="claim-type-card selected" id="cardReclamo" onclick="selectClaimType('RECLAMO')">
                        <input type="radio" name="claim_type" value="RECLAMO" id="radioReclamo" checked>
                        <div class="claim-type-header">
                            <span class="claim-type-icon">⚠️</span>
                            <span class="claim-type-name">RECLAMO</span>
                        </div>
                        <p class="claim-type-desc">
                            Disconformidad relacionada a los <strong>productos o servicios</strong> adquiridos (ej. cobro indebido, problema con entrada, evento cancelado).
                        </p>
                    </div>

                    <!-- Opción Queja -->
                    <div class="claim-type-card" id="cardQueja" onclick="selectClaimType('QUEJA')">
                        <input type="radio" name="claim_type" value="QUEJA" id="radioQueja">
                        <div class="claim-type-header">
                            <span class="claim-type-icon">📢</span>
                            <span class="claim-type-name">QUEJA</span>
                        </div>
                        <p class="claim-type-desc">
                            Disconformidad no relacionada a los productos/servicios; <strong>malestar o descontento respecto a la atención</strong> al público o soporte.
                        </p>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label">Detalle de los Hechos (Explicación Clara) <span class="required">*</span></label>
                    <textarea name="claim_detail" class="form-control-dark" rows="4" required placeholder="Describe cronológicamente lo sucedido con el mayor detalle posible...">{{ old('claim_detail') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Pedido Concreto del Consumidor <span class="required">*</span></label>
                    <textarea name="consumer_request" class="form-control-dark" rows="3" required placeholder="Indica la solución esperada (Ej. Reembolso del importe pagado, emisión de nuevo boleto, aclaración formal, etc.)...">{{ old('consumer_request') }}</textarea>
                </div>
            </div>

            <!-- 4. DECLARACIÓN Y ENVÍO -->
            <div class="form-step-section">
                <div class="step-header">
                    <span class="step-number">4</span>
                    <h3 class="step-title">Declaración Jurada y Aceptación</h3>
                </div>

                <div style="background: rgba(255, 85, 0, 0.05); border: 1px solid rgba(255, 85, 0, 0.2); border-radius: 12px; padding: 1.15rem; margin-bottom: 1.5rem;">
                    <label class="checkbox-item">
                        <input type="checkbox" name="terms_accepted" value="1" required {{ old('terms_accepted') ? 'checked' : '' }}>
                        <span class="checkbox-text">
                            <strong>Declaración de Veracidad:</strong> Declaro bajo juramento ser el titular de la presente reclamación y que la información aquí consignada es veraz y fidedigna, en cumplimiento del Código de Protección y Defensa del Consumidor (Ley N.° 29571).
                        </span>
                    </label>

                    <label class="checkbox-item" style="margin-bottom: 0;">
                        <input type="checkbox" name="privacy_accepted" value="1" required checked>
                        <span class="checkbox-text">
                            He leído y acepto los <a href="{{ route('web.terms') }}" target="_blank">Términos y Condiciones</a> y la <a href="{{ route('web.privacy') }}" target="_blank">Política de Privacidad</a> de ViveGo para el tratamiento de mis datos personales en la atención de esta reclamación.
                        </span>
                    </label>
                </div>

                <div style="font-size: 0.8rem; color: #94A3B8; line-height: 1.4; margin-bottom: 1.5rem; text-align: center;">
                    ℹ️ <em>La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI. El plazo legal máximo de respuesta es de 15 días hábiles.</em>
                </div>

                <button type="submit" class="btn-submit-claim" id="btnSubmitClaim">
                    <span>📨 Enviar Hoja de Reclamación</span>
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    function selectClaimType(type) {
        const cardReclamo = document.getElementById('cardReclamo');
        const cardQueja = document.getElementById('cardQueja');
        const radioReclamo = document.getElementById('radioReclamo');
        const radioQueja = document.getElementById('radioQueja');

        if (type === 'RECLAMO') {
            cardReclamo.classList.add('selected');
            cardQueja.classList.remove('selected');
            radioReclamo.checked = true;
        } else {
            cardQueja.classList.add('selected');
            cardReclamo.classList.remove('selected');
            radioQueja.checked = true;
        }
    }

    function handlePersonTypeChange(type) {
        const labelNat = document.getElementById('labelPersonNatural');
        const labelJur = document.getElementById('labelPersonJuridica');
        const labelFullName = document.getElementById('labelFullName');
        const selectDoc = document.getElementById('selectDocumentType');
        const minorBox = document.getElementById('minorToggleBox');

        if (type === 'natural') {
            labelNat.classList.add('active');
            labelJur.classList.remove('active');
            labelFullName.innerHTML = 'Nombres y Apellidos Completos <span class="required">*</span>';
            if (minorBox) minorBox.style.display = 'block';
            if (selectDoc.value === 'RUC') selectDoc.value = 'DNI';
        } else {
            labelJur.classList.add('active');
            labelNat.classList.remove('active');
            labelFullName.innerHTML = 'Razón Social de la Empresa <span class="required">*</span>';
            if (minorBox) minorBox.style.display = 'none';
            selectDoc.value = 'RUC';
        }
    }

    function handleGoodTypeChange(type) {
        const labelServ = document.getElementById('labelBienServicio');
        const labelProd = document.getElementById('labelBienProducto');

        if (type === 'SERVICIO') {
            labelServ.classList.add('active');
            labelProd.classList.remove('active');
        } else {
            labelProd.classList.add('active');
            labelServ.classList.remove('active');
        }
    }

    function toggleMinorFields(isMinor) {
        const container = document.getElementById('minorFieldsContainer');
        if (container) {
            container.style.display = isMinor ? 'block' : 'none';
        }
    }
</script>
@endpush
@endsection
