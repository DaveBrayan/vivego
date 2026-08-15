@extends('layouts.app')

@section('title', 'Editar Evento: ' . $eventData['title'] . ' | Vive Go')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #interactiveLeafletMap .leaflet-popup-content-wrapper {
            background: #14141E;
            color: #FFFFFF;
            border-radius: 12px;
        }
        #interactiveLeafletMap .leaflet-popup-tip {
            background: #14141E;
        }
        .template-select-card {
            border: 2px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.25s ease;
            background: rgba(255,255,255,0.03);
        }
        .template-select-card:hover {
            border-color: var(--color-primary-orange);
            transform: translateY(-2px);
        }
        .template-select-card.selected-template {
            border-color: var(--color-primary-orange);
            background: rgba(255, 85, 0, 0.08);
            box-shadow: 0 0 20px rgba(255, 85, 0, 0.25);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-root-wrapper">
        <!-- SIDEBAR DE NAVEGACIÓN PRO MAX -->
        <aside class="dash-sidebar" id="dashSidebar">
            <div class="dash-sidebar-header">
                <a href="{{ route('web.home') }}" class="dash-brand-logo">
                    <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" alt="Vive Go" class="dash-logo-img logo-white-img">
                    <img src="{{ asset($settings->logo_dark ?? 'images/logo.png') }}" alt="Vive Go" class="dash-logo-img logo-dark-img">
                </a>
                <button class="dash-sidebar-toggle-btn" id="dashSidebarToggle" aria-label="Colapsar Menú" title="Plegar / Expandir Menú">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
            </div>

            <!-- Perfil rápido de organizador -->
            <div class="dash-organizer-pill-card">
                <div class="dash-avatar-wrapper">
                    <img src="{{ $organizer['avatar'] }}" alt="{{ $organizer['name'] }}" class="dash-avatar-img">
                    <span class="dash-online-status-dot"></span>
                </div>
                <div class="dash-organizer-info">
                    <h4 class="dash-organizer-name" title="{{ $organizer['name'] }}">{{ $organizer['name'] }}</h4>
                    <span class="dash-verified-badge">✓ {{ $organizer['status'] }}</span>
                </div>
            </div>

            <!-- Menú de Navegación Principal -->
            <nav class="dash-nav-menu">
                <div class="dash-nav-section-title">MENÚ PRINCIPAL</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.dashboard') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">📊</span>
                            <span class="dash-nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="dash-nav-item active">
                        <a href="{{ route('web.events') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎟️</span>
                            <span class="dash-nav-text">Mis Eventos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.box_office') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">💰</span>
                            <span class="dash-nav-text">Taquilla & Ventas</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">GESTIÓN & HERRAMIENTAS</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.categories') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">📂</span>
                            <span class="dash-nav-text">Categorías</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.templates') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎨</span>
                            <span class="dash-nav-text">Plantillas de Boletos</span>
                        </a>
                    </li>
                </ul>

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">INFORMACIÓN EMPRESARIAL</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.companies') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🏢</span>
                            <span class="dash-nav-text">Compañía</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.managers') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">👤</span>
                            <span class="dash-nav-text">Responsable</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.capacity_types') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🏟️</span>
                            <span class="dash-nav-text">Tipos de Aforo</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="dash-sidebar-footer">
                <a href="{{ route('web.home') }}" class="dash-btn-logout" title="Cerrar Sesión">
                    <span class="dash-btn-logout-icon">🚪</span>
                    <span class="dash-btn-logout-text">Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL DE CONTENIDO -->
        <main class="dash-main-content">
            <!-- TOP NAVBAR -->
            <header class="dash-top-navbar">
                <div class="dash-search-container">
                    <span class="dash-search-icon">🔍</span>
                    <input type="text" class="dash-search-input" placeholder="Buscar en la edición de evento...">
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
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">✏️ EDICIÓN DE EVENTO EXISTENTE (CONECTADO A MYSQL)</span>
                        <h1 class="settings-page-title">Editar Evento: {{ $eventData['title'] }}</h1>
                        <p class="settings-page-subtitle">Modifica la portada, recintos, fechas, zonas de aforo y plantilla Canva oficial.</p>
                    </div>
                    <div>
                        <a href="{{ route('web.events') }}" class="btn btn-cancel-custom" style="white-space: nowrap; padding: 0.75rem 1.4rem; text-decoration: none;">
                            ← Volver a Mis Eventos
                        </a>
                    </div>
                </div>

                <!-- STEPPER NAVIGATION HEADER -->
                <div class="event-stepper-bar">
                    <div class="stepper-step active" id="stepIndicator1" onclick="goToStep(1)">
                        <div class="step-badge">1</div>
                        <div class="step-info">
                            <span class="step-title">Información General</span>
                            <span class="step-desc">Datos del espectáculo</span>
                        </div>
                    </div>
                    <div class="stepper-divider"></div>
                    <div class="stepper-step" id="stepIndicator2" onclick="goToStep(2)">
                        <div class="step-badge">2</div>
                        <div class="step-info">
                            <span class="step-title">Zonas & Tarifas</span>
                            <span class="step-desc">Aforo, precios y stock</span>
                        </div>
                    </div>
                    <div class="stepper-divider"></div>
                    <div class="stepper-step" id="stepIndicator3" onclick="goToStep(3)">
                        <div class="step-badge">3</div>
                        <div class="step-info">
                            <span class="step-title">Plantilla Canva</span>
                            <span class="step-desc">Diseño del boleto</span>
                        </div>
                    </div>
                    <div class="stepper-divider"></div>
                    <div class="stepper-step" id="stepIndicator4" onclick="goToStep(4)">
                        <div class="step-badge">4</div>
                        <div class="step-info">
                            <span class="step-title">Confirmación</span>
                            <span class="step-desc">Guardar cambios</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 1: INFORMACIÓN GENERAL -->
                <div class="step-content-panel active" id="stepPanel1">
                    <div class="settings-card-box">
                        <div class="settings-card-header">
                            <div class="card-header-icon" style="background: rgba(37, 99, 235, 0.15); border-color: rgba(37, 99, 235, 0.3); color: #2563EB;">📝</div>
                            <div>
                                <h3 class="card-header-title">Paso 1: Información General del Evento</h3>
                                <p class="card-header-subtitle">Modifica la portada, datos del espectáculo, mapa de ubicación y detalles</p>
                            </div>
                        </div>

                        <form class="admin-modal-form" onsubmit="event.preventDefault(); goToStep(2);">
                            
                            <div style="display: grid; grid-template-columns: 480px 1fr; gap: 1.75rem; align-items: stretch; margin-bottom: 1.75rem;" class="step1-top-grid">
                                
                                <!-- COLUMNA IZQUIERDA: BANNER -->
                                <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.12); padding: 1.35rem; border-radius: 20px; display: flex; flex-direction: column; justify-content: space-between; gap: 1rem; height: 100%;">
                                    <div>
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                            <label class="form-label-custom" style="margin: 0; font-size: 1rem; font-weight: 800;">
                                                Imagen Banner del Evento <span class="required-star">*</span>
                                            </label>
                                            <span class="dash-badge-custom badge-blue" style="font-size: 0.775rem; font-weight: 800;">
                                                📐 1200 x 630 px (16:9)
                                            </span>
                                        </div>

                                        <div style="position: relative; width: 100%; height: 290px; border-radius: 16px; overflow: hidden; border: 1.5px solid rgba(255,255,255,0.25); background: #000000; box-shadow: 0 12px 30px rgba(0,0,0,0.4); cursor: pointer;" onclick="document.getElementById('bannerFileInput').click();" title="Haz clic para cambiar imagen">
                                            <img id="bannerPreviewImg" src="{{ $eventData['banner_image'] ?? 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80' }}" alt="Vista Previa de Banner" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                            
                                            <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.25s ease; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; color: #FFFFFF; font-weight: 800; font-size: 0.95rem;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                                <span style="font-size: 1.8rem;">📷</span>
                                                <span>Clic para cambiar la portada</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <input type="hidden" id="event_banner" value="{{ $eventData['banner_image'] ?? 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80' }}">
                                        <input type="file" id="bannerFileInput" accept="image/*" style="display: none;" onchange="handleBannerUpload(this)">
                                        
                                        <button type="button" class="btn btn-primary btn-save-settings" style="width: 100%; text-align: center; justify-content: center; padding: 0.85rem 1rem; font-size: 0.925rem;" onclick="document.getElementById('bannerFileInput').click();">
                                            📁 Cambiar Imagen del Evento
                                        </button>
                                    </div>
                                </div>

                                <!-- COLUMNA DERECHA: CAMPOS DE TEXTO -->
                                <div style="display: flex; flex-direction: column; gap: 1.25rem; justify-content: space-between;">
                                    <div class="form-group-custom">
                                        <label for="event_title" class="form-label-custom">Nombre / Título del Evento <span class="required-star">*</span></label>
                                        <input type="text" id="event_title" class="form-input-custom" required value="{{ $eventData['title'] }}" oninput="updateLiveTicketPreview()">
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.15rem;">
                                        <div class="form-group-custom">
                                            <label for="event_category" class="form-label-custom">Categoría del Evento <span class="required-star">*</span></label>
                                            <select id="event_category" class="form-select-custom" required>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat['name'] }}" {{ ($eventData['category_name'] ?? '') === $cat['name'] ? 'selected' : '' }}>
                                                        {{ $cat['icon'] ?? '🎤' }} {{ $cat['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_company" class="form-label-custom">Compañía / Organizador <span class="required-star">*</span></label>
                                            <select id="event_company" class="form-select-custom" required>
                                                @foreach($companies as $comp)
                                                    <option value="{{ $comp['name'] }}" {{ ($eventData['company_name'] ?? '') === $comp['name'] ? 'selected' : '' }}>
                                                        🏢 {{ $comp['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.15rem;">
                                        <div class="form-group-custom">
                                            <label for="event_date" class="form-label-custom">Fecha del Evento <span class="required-star">*</span></label>
                                            <input type="date" id="event_date" class="form-input-custom" required value="{{ $eventData['event_date'] }}" onchange="updateLiveTicketPreview()">
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_time" class="form-label-custom">Hora de Inicio <span class="required-star">*</span></label>
                                            <input type="time" id="event_time" class="form-input-custom" required value="{{ $eventData['event_time'] }}" onchange="updateLiveTicketPreview()">
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.15rem;">
                                        <div class="form-group-custom">
                                            <label for="event_venue" class="form-label-custom">Recinto / Local <span class="required-star">*</span></label>
                                            <input type="text" id="event_venue" class="form-input-custom" required value="{{ $eventData['venue_name'] }}" oninput="updateLiveTicketPreview()">
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_address" class="form-label-custom">Ciudad / Dirección <span class="required-star">*</span></label>
                                            <input type="text" id="event_address" class="form-input-custom" required value="{{ $eventData['address'] }}" oninput="updateLiveTicketPreview()">
                                        </div>
                                    </div>

                                    <!-- Modalidad de Venta (Exclusivo: Física o Virtual) -->
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Modalidad de Venta de Entradas <span class="required-star">*</span></label>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                            @php
                                                $isFisica = ($eventData['sales_type'] ?? 'fisica') === 'fisica';
                                            @endphp
                                            <label style="border: 2px solid {{ $isFisica ? 'var(--color-primary-orange)' : 'rgba(255,255,255,0.1)' }}; background: {{ $isFisica ? 'rgba(255, 85, 0, 0.08)' : 'rgba(255,255,255,0.02)' }}; padding: 0.85rem 1.15rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: all 0.2s ease;" id="labelSalesFisica">
                                                <input type="radio" name="event_sales_type" id="salesTypeFisica" value="fisica" {{ $isFisica ? 'checked' : '' }} style="accent-color: #FF5500; width: 18px; height: 18px;" onchange="updateSalesTypeUI()">
                                                <div>
                                                    <strong style="display: block; font-size: 0.95rem; color: #FFFFFF;">🎫 Venta Física (Taquilla)</strong>
                                                    <span style="font-size: 0.78rem; color: #94A3B8;">Boletos físicos / Punto de venta POS</span>
                                                </div>
                                            </label>
                                            
                                            <label style="border: 2px solid {{ !$isFisica ? 'var(--color-neon-cyan)' : 'rgba(255,255,255,0.1)' }}; background: {{ !$isFisica ? 'rgba(0, 240, 255, 0.08)' : 'rgba(255,255,255,0.02)' }}; padding: 0.85rem 1.15rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: all 0.2s ease;" id="labelSalesVirtual">
                                                <input type="radio" name="event_sales_type" id="salesTypeVirtual" value="virtual" {{ !$isFisica ? 'checked' : '' }} style="accent-color: #FF5500; width: 18px; height: 18px;" onchange="updateSalesTypeUI()">
                                                <div>
                                                    <strong style="display: block; font-size: 0.95rem; color: #FFFFFF;">🌐 Venta Virtual (Online)</strong>
                                                    <span style="font-size: 0.78rem; color: #94A3B8;">Venta exclusiva web con ticket digital</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MAPA DE UBICACIÓN LEAFLET -->
                            <div style="margin-bottom: 1.75rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                    <label class="form-label-custom" style="margin: 0; font-size: 1rem; font-weight: 800;">
                                        📍 Ubicación Geográfica en Mapa Interactivo (GPS)
                                    </label>
                                    <span style="color: #94A3B8; font-size: 0.8rem; font-weight: 600;">
                                        Haz clic en el mapa para marcar las coordenadas del recinto
                                    </span>
                                </div>

                                <div id="interactiveLeafletMap" style="width: 100%; height: 280px; border-radius: 16px; overflow: hidden; border: 1.5px solid rgba(255,255,255,0.15); background: #0A0A10;"></div>
                            </div>

                            <!-- DESCRIPCIÓN Y ETIQUETAS -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                                <div class="form-group-custom">
                                    <label for="event_details" class="form-label-custom">Descripción / Detalles del Evento</label>
                                    <textarea id="event_details" class="form-input-custom" rows="4" style="resize: vertical;">{{ $eventData['description'] }}</textarea>
                                </div>

                                <div class="form-group-custom">
                                    <label for="event_tags" class="form-label-custom">Etiquetas / Tags de Búsqueda</label>
                                    <input type="text" id="event_tags" class="form-input-custom" value="{{ implode(', ', $eventData['tags'] ?? []) }}">
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
                                <button type="submit" class="btn btn-primary btn-save-settings">
                                    Siguiente: Zonas & Tarifas →
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- STEP 2: ZONAS Y TARIFAS -->
                <div class="step-content-panel" id="stepPanel2">
                    <div class="settings-card-box">
                        <div class="settings-card-header" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">🎟️</div>
                                <div>
                                    <h3 class="card-header-title">Paso 2: Zonas, Precios y Aforo por Sector</h3>
                                    <p class="card-header-subtitle">Configura las zonas de tickets, precios y aforo total</p>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary btn-save-settings" onclick="addNewZoneRow()">
                                ➕ Agregar Nueva Zona
                            </button>
                        </div>

                        <form class="admin-modal-form" onsubmit="event.preventDefault(); goToStep(3);">
                            <div style="background: rgba(255,255,255,0.02); border: 1.5px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 1.25rem; margin-bottom: 1.75rem;">
                                <table class="admin-table" style="margin: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%;">Tipo de Aforo</th>
                                            <th style="width: 30%;">Nombre de la Zona</th>
                                            <th style="width: 20%;">Aforo (Stock)</th>
                                            <th style="width: 20%;">Precio Unitario (S/)</th>
                                            <th style="width: 5%; text-align: center;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="zonesTableBody">
                                        <!-- Se puebla dinámicamente con JS -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- TARJETA RESUMEN DE AFORO ACUMULADO -->
                            <div style="background: rgba(16, 185, 129, 0.08); border: 1.5px solid rgba(16, 185, 129, 0.25); padding: 1.25rem; border-radius: 18px; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span style="font-size: 2rem;">🏟️</span>
                                    <div>
                                        <h4 style="margin: 0; color: #FFFFFF; font-size: 1.05rem; font-weight: 800;">Aforo Total Estimado del Espectáculo</h4>
                                        <p style="margin: 0; color: #94A3B8; font-size: 0.85rem;">Suma total de localidades configuradas en las zonas superiores</p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span id="calculatedTotalCapacity" style="font-size: 1.8rem; font-weight: 900; color: #10B981;">0</span>
                                    <span style="display: block; font-size: 0.75rem; color: #A7F3D0; font-weight: 700;">Entradas Disponibles</span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
                                <button type="button" class="btn btn-cancel-custom" onclick="goToStep(1)">
                                    ← Volver al Paso 1
                                </button>
                                <button type="submit" class="btn btn-primary btn-save-settings">
                                    Siguiente: Plantilla Canva →
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- STEP 3: SELECCIÓN DE PLANTILLA CANVA -->
                <div class="step-content-panel" id="stepPanel3">
                    <div class="settings-card-box">
                        <div class="settings-card-header">
                            <div class="card-header-icon" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: #EAB308;">🎨</div>
                            <div>
                                <h3 class="card-header-title">Paso 3: Asignar Plantilla Oficial de Boletos Canva</h3>
                                <p class="card-header-subtitle">Selecciona la plantilla con la que se imprimirán los tickets oficiales de este evento</p>
                            </div>
                        </div>

                        <form class="admin-modal-form" onsubmit="event.preventDefault(); goToStep(4);">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
                                @foreach($templates as $tpl)
                                    <div class="template-select-card {{ ($eventData['template_id'] ?? 1) == $tpl->id ? 'selected-template' : '' }}" data-id="{{ $tpl->id }}" onclick="selectTicketTemplate({{ $tpl->id }})">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                            <strong style="color: #FFFFFF; font-size: 0.95rem;">{{ $tpl->name }}</strong>
                                            @if($tpl->is_default)
                                                <span class="dash-badge-custom badge-green" style="font-size: 0.65rem;">Por Defecto</span>
                                            @endif
                                        </div>
                                        <p style="color: #94A3B8; font-size: 0.8rem; margin-bottom: 0.75rem;">{{ $tpl->category }}</p>

                                        <!-- MOCKUP VISUAL DE LA PLANTILLA -->
                                        <div style="width: 100%; height: 100px; border-radius: 12px; border: 1.5px solid rgba(255,255,255,0.15); background: {{ $tpl->bg_color ?? '#FFFFFF' }}; display: flex; overflow: hidden; position: relative;">
                                            @if($tpl->id == 2)
                                                <div style="width: 30%; height: 100%; background: #F1F5F9; border-right: 2px dashed #94A3B8; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; padding: 4px;">
                                                    <div style="width: 24px; height: 24px; background: #000; border-radius: 4px;"></div>
                                                    <span style="font-size: 0.5rem; font-weight: 900; color: #000;">N° 00001</span>
                                                </div>
                                                <div style="flex: 1; padding: 6px; display: flex; flex-direction: column; justify-content: space-between; color: #000;">
                                                    <span style="font-size: 0.65rem; font-weight: 900; line-height: 1.1;">{{ $eventData['title'] }}</span>
                                                    <span style="font-size: 0.55rem; color: #64748B;">ZONA VIP PLATINUM</span>
                                                    <span style="font-size: 0.65rem; font-weight: 900; color: #FF5500;">S/ 150.00</span>
                                                </div>
                                                <div style="width: 18%; height: 100%; background: {{ $tpl->strip_color ?? '#000000' }}; display: flex; align-items: center; justify-content: center;">
                                                    <span style="color: #FFF; font-weight: 900; font-size: 0.55rem; transform: rotate(-90deg);">VIVE GO</span>
                                                </div>
                                            @elseif($tpl->id == 3)
                                                <div style="width: 100%; height: 100%; background: {{ $tpl->bg_color ?? '#1E1B4B' }}; color: #FFF; padding: 8px; display: flex; justify-content: space-between;">
                                                    <div style="display: flex; flex-direction: column; justify-content: space-between;">
                                                        <span style="background: {{ $tpl->strip_color ?? '#F59E0B' }}; color: #000; font-size: 0.5rem; font-weight: 900; padding: 1px 4px; border-radius: 3px; display: inline-block;">VIP PASS</span>
                                                        <span style="font-size: 0.65rem; font-weight: 900;">{{ $eventData['title'] }}</span>
                                                        <span style="font-size: 0.6rem; font-weight: 900; color: {{ $tpl->strip_color ?? '#F59E0B' }};">S/ 150.00</span>
                                                    </div>
                                                    <div style="width: 35px; height: 35px; background: #FFF; border-radius: 4px; display: flex; align-items: center; justify-content: center; align-self: center;">
                                                        <div style="width: 25px; height: 25px; background: #000;"></div>
                                                    </div>
                                                </div>
                                            @else
                                                <div style="width: 18%; height: 100%; background: {{ $tpl->strip_color ?? '#000000' }}; display: flex; align-items: center; justify-content: center;">
                                                    <span style="color: #FFF; font-weight: 900; font-size: 0.55rem; transform: rotate(-90deg);">VIVE GO</span>
                                                </div>
                                                <div style="flex: 1; padding: 6px; display: flex; flex-direction: column; justify-content: space-between; color: #000;">
                                                    <span style="font-size: 0.65rem; font-weight: 900; line-height: 1.1;">{{ $eventData['title'] }}</span>
                                                    <span style="font-size: 0.55rem; color: #64748B;">ZONA VIP PLATINUM</span>
                                                    <span style="font-size: 0.65rem; font-weight: 900; color: #FF5500;">S/ 150.00</span>
                                                </div>
                                                <div style="width: 30%; height: 100%; background: #F1F5F9; border-left: 2px dashed #94A3B8; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; padding: 4px;">
                                                    <div style="width: 24px; height: 24px; background: #000; border-radius: 4px;"></div>
                                                    <span style="font-size: 0.5rem; font-weight: 900; color: #000;">N° 00001</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <input type="hidden" id="selected_template_id" value="{{ $eventData['template_id'] ?? 1 }}">

                            <div style="display: flex; justify-content: space-between; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
                                <button type="button" class="btn btn-cancel-custom" onclick="goToStep(2)">
                                    ← Volver al Paso 2
                                </button>
                                <button type="submit" class="btn btn-primary btn-save-settings">
                                    Siguiente: Confirmar Cambios →
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- STEP 4: CONFIRMACIÓN Y REVISIÓN FINAL -->
                <div class="step-content-panel" id="stepPanel4">
                    <div class="settings-card-box">
                        <div class="settings-card-header">
                            <div class="card-header-icon" style="background: rgba(37, 99, 235, 0.15); border-color: rgba(37, 99, 235, 0.3); color: #2563EB;">🚀</div>
                            <div>
                                <h3 class="card-header-title">Paso 4: Confirmación & Guardado de Cambios</h3>
                                <p class="card-header-subtitle">Revisa el resumen final de la información antes de actualizar en la Base de Datos MySQL</p>
                            </div>
                        </div>

                        <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.12); padding: 1.5rem; border-radius: 20px; margin-bottom: 2rem;">
                            <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1.5rem; align-items: center; margin-bottom: 1.5rem;">
                                <img id="summaryBannerImg" src="{{ $eventData['banner_image'] ?? 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80' }}" alt="Banner" style="width: 100%; height: 110px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(255,255,255,0.2);">
                                
                                <div>
                                    <span id="summaryCategory" class="dash-badge-custom badge-blue" style="margin-bottom: 0.5rem; display: inline-block;">🎤 {{ $eventData['category_name'] }}</span>
                                    <h2 id="summaryTitle" style="color: #FFFFFF; margin: 0 0 0.35rem 0; font-size: 1.35rem; font-weight: 900;">{{ $eventData['title'] }}</h2>
                                    <p style="color: #94A3B8; margin: 0; font-size: 0.9rem;">
                                        📍 <strong id="summaryVenue" style="color: #FFFFFF;">{{ $eventData['venue_name'] }}</strong> ({{ $eventData['address'] }}) &nbsp;|&nbsp; 
                                        🗓️ <span id="summaryDate" style="color: #FF5500; font-weight: 800;">{{ $eventData['event_date'] }} / {{ $eventData['event_time'] }}</span>
                                    </p>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                                <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                                    <strong style="color: #94A3B8; display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Compañía / Empresa</strong>
                                    <span id="summaryCompany" style="color: #FFFFFF; font-weight: 800; font-size: 0.95rem;">🏢 {{ $eventData['company_name'] }}</span>
                                </div>

                                <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                                    <strong style="color: #94A3B8; display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Aforo Total Registrado</strong>
                                    <span id="summaryCapacity" style="color: #10B981; font-weight: 900; font-size: 1.1rem;">0 Boletos</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(3)">
                                ← Volver al Paso 3
                            </button>
                            
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.9rem 2rem; font-size: 1rem;" onclick="saveEditedEvent()">
                                💾 Guardar Cambios del Evento
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const csrfToken = "{{ csrf_token() }}";
        const initialEventData = @json($eventData);
        const capacityTypesData = @json($capacityTypes);
        const templatesData = @json($templates);

        let leafletMap = null;
        let leafletMarker = null;

        function goToStep(stepNumber) {
            document.querySelectorAll('.stepper-step').forEach((el, idx) => {
                if (idx + 1 === stepNumber) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });

            document.querySelectorAll('.step-content-panel').forEach((el, idx) => {
                if (idx + 1 === stepNumber) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });

            if (stepNumber === 1 && leafletMap) {
                setTimeout(() => leafletMap.invalidateSize(), 300);
            }

            if (stepNumber === 4) {
                updateSummaryData();
            }
        }

        function initLeafletMap() {
            const mapContainer = document.getElementById('interactiveLeafletMap');
            if (!mapContainer) return;

            const lat = initialEventData.latitude || -13.1631;
            const lng = initialEventData.longitude || -74.2236;

            leafletMap = L.map('interactiveLeafletMap').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(leafletMap);

            leafletMarker = L.marker([lat, lng], { draggable: true }).addTo(leafletMap)
                .bindPopup("<b>" + (initialEventData.venue_name || "Recinto del Evento") + "</b><br>" + (initialEventData.address || "Dirección"))
                .openPopup();

            leafletMarker.on('dragend', function (e) {
                const coord = e.target.getLatLng();
                leafletMarker.bindPopup(`<b>${coord.lat.toFixed(4)}, ${coord.lng.toFixed(4)}</b>`).openPopup();
            });

            leafletMap.on('click', function (e) {
                leafletMarker.setLatLng(e.latlng);
                leafletMarker.bindPopup(`<b>Recinto Seleccionado:</b><br>${e.latlng.lat.toFixed(4)}, ${e.latlng.lng.toFixed(4)}`).openPopup();
            });
        }

        function handleBannerUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('bannerPreviewImg').src = e.target.result;
                    document.getElementById('event_banner').value = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function populateZoneRows(zonesArray) {
            const tbody = document.getElementById('zonesTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            const listToRender = (zonesArray && zonesArray.length > 0) ? zonesArray : [
                { capacity_type: 'Aforo VIP', name: 'BOX PLATINUM INDIVIDUAL', capacity: 10, price: 150.00 },
                { capacity_type: 'Aforo Preferencial', name: 'ZONA VIP STAND UP', capacity: 20, price: 95.00 },
                { capacity_type: 'Aforo General', name: 'ZONA GENERAL', capacity: 30, price: 55.50 }
            ];

            listToRender.forEach(z => {
                addNewZoneRow(z.capacity_type, z.name, z.capacity, z.price);
            });
        }

        function addNewZoneRow(capType = '', zoneName = '', capacity = 100, price = 50.00) {
            const tbody = document.getElementById('zonesTableBody');
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.className = 'zone-row-item';

            let optionsHtml = '';
            if (capacityTypesData && capacityTypesData.length > 0) {
                capacityTypesData.forEach(ct => {
                    const sel = (capType === ct.name) ? 'selected' : '';
                    optionsHtml += `<option value="${ct.name}" ${sel}>${ct.name} (${ct.max_capacity} max)</option>`;
                });
            } else {
                optionsHtml = `
                    <option value="Aforo VIP">Aforo VIP</option>
                    <option value="Aforo Preferencial">Aforo Preferencial</option>
                    <option value="Aforo General" selected>Aforo General</option>
                `;
            }

            tr.innerHTML = `
                <td>
                    <select class="form-select-custom zone-type-select" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-input-custom zone-name-input" value="${zoneName || 'ZONA GENERAL'}" placeholder="Ej. PLATINUM" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">
                </td>
                <td>
                    <input type="number" class="form-input-custom zone-capacity-input" value="${capacity}" min="1" oninput="recalculateTotalCapacity()" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">
                </td>
                <td>
                    <input type="number" step="0.50" class="form-input-custom zone-price-input" value="${price}" min="0" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="dash-btn-icon-action" style="color: #FF1E3C;" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                </td>
            `;

            tbody.appendChild(tr);
            recalculateTotalCapacity();
        }

        function removeZoneRow(btn) {
            const tr = btn.closest('tr');
            if (tr) tr.remove();
            recalculateTotalCapacity();
        }

        function recalculateTotalCapacity() {
            let total = 0;
            document.querySelectorAll('.zone-capacity-input').forEach(input => {
                total += parseInt(input.value, 10) || 0;
            });
            const capText = document.getElementById('calculatedTotalCapacity');
            if (capText) capText.textContent = total.toLocaleString();
        }

        function selectTicketTemplate(templateId) {
            document.querySelectorAll('.template-select-card').forEach(card => {
                if (parseInt(card.getAttribute('data-id'), 10) === templateId) {
                    card.classList.add('selected-template');
                } else {
                    card.classList.remove('selected-template');
                }
            });
            const input = document.getElementById('selected_template_id');
            if (input) input.value = templateId;
        }

        function updateSummaryData() {
            const title = document.getElementById('event_title').value;
            const categoryName = document.getElementById('event_category').value;
            const companyName = document.getElementById('event_company').value;
            const venueName = document.getElementById('event_venue').value;
            const address = document.getElementById('event_address').value;
            const eventDate = document.getElementById('event_date').value;
            const eventTime = document.getElementById('event_time').value;
            const bannerImage = document.getElementById('event_banner').value;

            document.getElementById('summaryTitle').textContent = title || 'Sin Título';
            document.getElementById('summaryCategory').textContent = '🎤 ' + categoryName;
            document.getElementById('summaryCompany').textContent = '🏢 ' + companyName;
            document.getElementById('summaryVenue').textContent = venueName || 'Recinto';
            document.getElementById('summaryDate').textContent = `${eventDate} / ${eventTime}`;
            document.getElementById('summaryBannerImg').src = bannerImage;

            let total = 0;
            document.querySelectorAll('.zone-capacity-input').forEach(input => {
                total += parseInt(input.value, 10) || 0;
            });
            document.getElementById('summaryCapacity').textContent = total.toLocaleString() + ' Boletos Total';
        }

        function saveEditedEvent() {
            const title = document.getElementById('event_title').value;
            const categoryName = document.getElementById('event_category').value;
            const companyName = document.getElementById('event_company').value;
            const bannerImage = document.getElementById('event_banner').value;
            const eventDate = document.getElementById('event_date').value;
            const eventTime = document.getElementById('event_time').value;
            const venueName = document.getElementById('event_venue').value;
            const address = document.getElementById('event_address').value;
            const details = document.getElementById('event_details').value;
            const tags = document.getElementById('event_tags').value.split(',').map(t => t.trim()).filter(Boolean);
            const templateId = document.getElementById('selected_template_id').value;

            const zones = [];
            document.querySelectorAll('.zone-row-item').forEach(row => {
                const capType = row.querySelector('.zone-type-select').value;
                const name = row.querySelector('.zone-name-input').value;
                const capacity = parseInt(row.querySelector('.zone-capacity-input').value, 10) || 0;
                const price = parseFloat(row.querySelector('.zone-price-input').value) || 0;

                zones.push({
                    capacity_type: capType,
                    name: name,
                    capacity: capacity,
                    price: price
                });
            });

            const payload = {
                title: title,
                category_name: categoryName,
                company_name: companyName,
                banner_image: bannerImage,
                event_date: eventDate,
                event_time: eventTime,
                venue_name: venueName,
                address: address,
                latitude: -13.1631,
                longitude: -74.2236,
                description: details,
                tags: tags,
                template_id: parseInt(templateId, 10),
                zones: zones,
                sales_type: document.querySelector('input[name="event_sales_type"]:checked')?.value || 'fisica'
            };

            fetch("{{ route('web.events.update', $eventData['id']) }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '🎉 ¡Evento Actualizado con Éxito!',
                        text: `El espectáculo "${title}" y sus modificaciones se han guardado exitosamente en la Base de Datos.`,
                        icon: 'success',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then(() => {
                        window.location.href = "{{ route('web.events') }}";
                    });
                } else {
                    Swal.fire({ title: 'Error', text: 'No se pudieron guardar las modificaciones.', icon: 'error', background: '#14141E', color: '#FFF' });
                }
            })
            .catch(err => {
                Swal.fire({ title: 'Error de Red', text: err.message, icon: 'error', background: '#14141E', color: '#FFF' });
            });
        }

        function updateSalesTypeUI() {
            const isFisica = document.getElementById('salesTypeFisica')?.checked;
            const labelFisica = document.getElementById('labelSalesFisica');
            const labelVirtual = document.getElementById('labelSalesVirtual');

            if (isFisica) {
                if (labelFisica) {
                    labelFisica.style.borderColor = 'var(--color-primary-orange)';
                    labelFisica.style.background = 'rgba(255, 85, 0, 0.08)';
                }
                if (labelVirtual) {
                    labelVirtual.style.borderColor = 'rgba(255,255,255,0.1)';
                    labelVirtual.style.background = 'rgba(255,255,255,0.02)';
                }
            } else {
                if (labelVirtual) {
                    labelVirtual.style.borderColor = 'var(--color-neon-cyan)';
                    labelVirtual.style.background = 'rgba(0, 240, 255, 0.08)';
                }
                if (labelFisica) {
                    labelFisica.style.borderColor = 'rgba(255,255,255,0.1)';
                    labelFisica.style.background = 'rgba(255,255,255,0.02)';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            initLeafletMap();
            populateZoneRows(initialEventData.zones);
            selectTicketTemplate(initialEventData.template_id || 1);

            // Sidebar Toggle
            const sidebar = document.getElementById('dashSidebar');
            const toggleBtn = document.getElementById('dashSidebarToggle');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.add('dash-animating');
                    sidebar.classList.toggle('collapsed');
                    setTimeout(function () { sidebar.classList.remove('dash-animating'); }, 450);
                });
            }

            // Theme Toggle (Dark / Light)
            const themeBtn = document.getElementById('btnThemeToggle');
            const themeIcon = document.getElementById('themeToggleIcon');
            const dashRoot = document.querySelector('.dashboard-root-wrapper');

            const savedTheme = localStorage.getItem('vivego_dashboard_theme');
            if (savedTheme === 'light' && dashRoot) {
                dashRoot.classList.add('theme-light');
                if (themeIcon) themeIcon.textContent = '🌙';
            }

            if (themeBtn && dashRoot) {
                themeBtn.addEventListener('click', function () {
                    dashRoot.classList.toggle('theme-light');
                    const isLight = dashRoot.classList.contains('theme-light');
                    if (themeIcon) themeIcon.textContent = isLight ? '🌙' : '☀️';
                    localStorage.setItem('vivego_dashboard_theme', isLight ? 'light' : 'dark');
                });
            }
        });
    </script>
@endpush
