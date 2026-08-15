@extends('layouts.app')

@section('title', 'Crear Nuevo Evento | Vive Go')

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
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">📈</span>
                            <span class="dash-nav-text">Analíticas Pro</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">👥</span>
                            <span class="dash-nav-text">Asistentes</span>
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

                <div class="dash-nav-section-title" style="margin-top: 1.5rem;">ADMINISTRACIÓN</div>
                <ul class="dash-nav-list">
                    <li class="dash-nav-item">
                        <a href="{{ route('web.admins') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🛡️</span>
                            <span class="dash-nav-text">Administradores</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="{{ route('web.settings') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">⚙️</span>
                            <span class="dash-nav-text">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Footer Sidebar: Botón Salir -->
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
                    <input type="text" class="dash-search-input" placeholder="Buscar en la creación de evento...">
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
                <!-- BANNER DE ENCABEZADO PRO -->
                <div class="settings-header-banner">
                    <div>
                        <span class="settings-tag">🎟️ ASISTENTE DE CREACIÓN DE EVENTO (CONECTADO A MYSQL)</span>
                        <h1 class="settings-page-title">Crear Nuevo Evento</h1>
                        <p class="settings-page-subtitle">Poblado dinámicamente con categorías, compañías, tipos de aforo y plantillas de MySQL.</p>
                    </div>
                    <div>
                        <a href="{{ route('web.events') }}" class="btn btn-cancel-custom" style="white-space: nowrap; padding: 0.75rem 1.4rem; text-decoration: none;">
                            ← Volver a Mis Eventos
                        </a>
                    </div>
                </div>

                <!-- STEPPER NAVIGATION HEADER (BARRA DE PASOS ORDENADA) -->
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
                            <span class="step-title">Selección de Plantilla Canva</span>
                            <span class="step-desc">Previsualización del ticket</span>
                        </div>
                    </div>
                    <div class="stepper-divider"></div>
                    <div class="stepper-step" id="stepIndicator4" onclick="goToStep(4)">
                        <div class="step-badge">4</div>
                        <div class="step-info">
                            <span class="step-title">Confirmación</span>
                            <span class="step-desc">Revisión y publicación</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 1: INFORMACIÓN GENERAL -->
                <div class="step-content-panel active" id="stepPanel1">
                    <div class="settings-card-box">
                        <div class="settings-card-header">
                            <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: var(--color-primary-orange);">📝</div>
                            <div>
                                <h3 class="card-header-title">Paso 1: Información General del Evento</h3>
                                <p class="card-header-subtitle">Completa la portada, datos principales del espectáculo, mapa de ubicación y detalles</p>
                            </div>
                        </div>

                        <form class="admin-modal-form" onsubmit="event.preventDefault(); goToStep(2);">
                            
                            <!-- CONTENEDOR EN 2 COLUMNAS (IZQUIERDA: IMAGEN BANNER | DERECHA: DATOS DEL EVENTO) -->
                            <div style="display: grid; grid-template-columns: 480px 1fr; gap: 1.75rem; align-items: stretch; margin-bottom: 1.75rem;" class="step1-top-grid">
                                
                                <!-- COLUMNA IZQUIERDA: IMAGEN BANNER AMPLIADA CON VISTA PREVIA Y BOTÓN DE CARGA CLEAN -->
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

                                        <!-- CONTENEDOR VISTA PREVIA DE IMAGEN AMPLIADO -->
                                        <div style="position: relative; width: 100%; height: 290px; border-radius: 16px; overflow: hidden; border: 1.5px solid rgba(255,255,255,0.25); background: #000000; box-shadow: 0 12px 30px rgba(0,0,0,0.4); cursor: pointer;" onclick="document.getElementById('bannerFileInput').click();" title="Haz clic para cambiar o subir imagen">
                                            <img id="bannerPreviewImg" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80" alt="Vista Previa de Banner" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                            
                                            <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.25s ease; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; color: #FFFFFF; font-weight: 800; font-size: 0.95rem;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                                <span style="font-size: 1.8rem;">📷</span>
                                                <span>Clic para cambiar la portada</span>
                                            </div>

                                            <span style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.85); backdrop-filter: blur(6px); padding: 0.35rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 800; color: #10B981; border: 1px solid rgba(16, 185, 129, 0.4); pointer-events: none;">
                                                ✓ Vista Previa Oficial
                                            </span>
                                        </div>
                                    </div>

                                    <!-- INPUT OCULTO Y BOTÓN ÚNICO DE CARGA -->
                                    <div>
                                        <input type="hidden" id="event_banner" value="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80">
                                        <input type="file" id="bannerFileInput" accept="image/*" style="display: none;" onchange="handleBannerUpload(this)">
                                        
                                        <button type="button" class="btn btn-primary btn-save-settings" style="width: 100%; text-align: center; justify-content: center; padding: 0.85rem 1rem; font-size: 0.925rem;" onclick="document.getElementById('bannerFileInput').click();">
                                            📁 Subir / Cambiar Imagen del Evento
                                        </button>
                                        <small style="color: #94A3B8; margin-top: 0.5rem; text-align: center; display: block; font-size: 0.775rem;">
                                            💡 Proporción recomendada 16:9. Formatos aceptados: JPG, PNG o WebP (Máx 5MB).
                                        </small>
                                    </div>
                                </div>

                                <!-- COLUMNA DERECHA: DATOS PRINCIPALES HASTA DIRECCIÓN -->
                                <div style="display: flex; flex-direction: column; gap: 1.25rem; justify-content: space-between;">
                                    <!-- Nombre / Título del Evento -->
                                    <div class="form-group-custom">
                                        <label for="event_title" class="form-label-custom">Nombre / Título del Evento <span class="required-star">*</span></label>
                                        <input type="text" id="event_title" class="form-input-custom" required value="Chúpate la Plata con Son del Duke en Ayacucho" oninput="updateLiveTicketPreview()">
                                    </div>

                                    <!-- Categoría & Empresa Organizadora (DESDE MYSQL) -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="form-group-custom">
                                            <label for="event_category" class="form-label-custom">Categoría <span class="required-star">*</span></label>
                                            <select id="event_category" class="form-select-custom" required>
                                                @foreach($categories as $cat)
                                                    <option value="{{ is_array($cat) ? $cat['name'] : $cat->name }}">
                                                        {{ is_array($cat) ? ($cat['icon'] ?? '📂') : ($cat->icon ?? '📂') }} {{ is_array($cat) ? $cat['name'] : $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_company" class="form-label-custom">Compañía Organizadora <span class="required-star">*</span></label>
                                            <select id="event_company" class="form-select-custom" required>
                                                @foreach($companies as $comp)
                                                    <option value="{{ is_array($comp) ? $comp['name'] : $comp->name }}">
                                                        🏢 {{ is_array($comp) ? $comp['name'] : $comp->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Fecha del Evento & Hora de Inicio -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="form-group-custom">
                                            <label for="event_date_picker" class="form-label-custom">Fecha del Evento <span class="required-star">*</span></label>
                                            <input type="date" id="event_date_picker" class="form-input-custom" required value="2025-04-10" onchange="updateLiveTicketPreview()">
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_time_picker" class="form-label-custom">Hora de Inicio <span class="required-star">*</span></label>
                                            <input type="time" id="event_time_picker" class="form-input-custom" required value="18:00" onchange="updateLiveTicketPreview()">
                                        </div>
                                    </div>

                                    <!-- Recinto / Local del Show & Dirección -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="form-group-custom">
                                            <label for="event_venue" class="form-label-custom">Recinto / Local del Show <span class="required-star">*</span></label>
                                            <input type="text" id="event_venue" class="form-input-custom" required value="Complejo San Luis" oninput="updateLiveTicketPreview()">
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_address" class="form-label-custom">Dirección del Recinto & Ciudad <span class="required-star">*</span></label>
                                            <input type="text" id="event_address" class="form-input-custom" required value="Av. Cusco 528 - AYACUCHO" oninput="updateLiveTicketPreview()">
                                        </div>
                                    </div>

                                    <!-- Modalidad de Venta (Exclusivo: Física o Virtual) -->
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Modalidad de Venta de Entradas <span class="required-star">*</span></label>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                            <label style="border: 2px solid var(--color-primary-orange); background: rgba(255, 85, 0, 0.08); padding: 0.85rem 1.15rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: all 0.2s ease;" id="labelSalesFisica">
                                                <input type="radio" name="event_sales_type" id="salesTypeFisica" value="fisica" checked style="accent-color: #FF5500; width: 18px; height: 18px;" onchange="updateSalesTypeUI()">
                                                <div>
                                                    <strong style="display: block; font-size: 0.95rem; color: #FFFFFF;">🎫 Venta Física (Taquilla)</strong>
                                                    <span style="font-size: 0.78rem; color: #94A3B8;">Boletos físicos / Punto de venta POS</span>
                                                </div>
                                            </label>
                                            
                                            <label style="border: 2px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); padding: 0.85rem 1.15rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; cursor: pointer; transition: all 0.2s ease;" id="labelSalesVirtual">
                                                <input type="radio" name="event_sales_type" id="salesTypeVirtual" value="virtual" style="accent-color: #FF5500; width: 18px; height: 18px;" onchange="updateSalesTypeUI()">
                                                <div>
                                                    <strong style="display: block; font-size: 0.95rem; color: #FFFFFF;">🌐 Venta Virtual (Online)</strong>
                                                    <span style="font-size: 0.78rem; color: #94A3B8;">Venta exclusiva web con ticket digital</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MAPA INTERACTIVO CON CLIC DIRECTO PARA FIJAR PIN -->
                            <div class="form-group-custom" style="margin-bottom: 1.5rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <label class="form-label-custom" style="margin: 0;">
                                        Ubicación Geográfica en Mapa <span class="required-star">*</span>
                                    </label>
                                    <span class="dash-badge-custom badge-orange" style="font-size: 0.775rem; font-weight: 800;">
                                        👇 Haz clic directo en cualquier punto del mapa para fijar el pin 📍
                                    </span>
                                </div>
                                
                                <div class="map-picker-container" style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.12); padding: 1.25rem; border-radius: 16px;">
                                    <div id="interactiveLeafletMap" style="height: 290px; width: 100%; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); z-index: 1;"></div>
                                    
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.75rem; background: rgba(0,0,0,0.6); padding: 0.65rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                                        <span style="font-size: 0.85rem; font-weight: 700; color: #FFFFFF;">
                                            📍 Coordenadas de Ubicación: <strong id="mapCoordsText" style="color: var(--color-neon-cyan);">-13.1631, -74.2236</strong>
                                        </span>
                                        <span style="font-size: 0.775rem; color: #94A3B8;">(Pin movible al hacer clic en cualquier lugar)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- DETALLES Y DESCRIPCIÓN DEL EVENTO -->
                            <div class="form-group-custom" style="margin-bottom: 1.5rem;">
                                <label for="event_details" class="form-label-custom">Detalles del Evento & Descripción <span class="required-star">*</span></label>
                                <textarea id="event_details" class="form-textarea-custom" rows="4" required placeholder="Escribe la descripción general del show, artistas invitados, política de ingreso de menores, restricciones, etc.">¡Llega a Ayacucho la fiesta más grande del año! Disfruta en vivo de Chúpate la Plata con Son del Duke en el Complejo San Luis. Un espectáculo imperdible con sonido e iluminación de última generación. Ingreso para mayores de 18 años con DNI físico.</textarea>
                            </div>

                            <!-- ETIQUETAS & TAGS DEL EVENTO -->
                            <div class="form-group-custom">
                                <label for="event_tags_input" class="form-label-custom">Etiquetas & Palabras Clave (Tags) <small style="color: #94A3B8;">(Presiona Enter para agregar)</small></label>
                                <div class="tags-input-wrapper" id="tagsWrapper">
                                    <span class="tag-chip">#Concierto <button type="button" onclick="this.parentElement.remove()">✕</button></span>
                                    <span class="tag-chip">#SonDelDuke <button type="button" onclick="this.parentElement.remove()">✕</button></span>
                                    <span class="tag-chip">#Ayacucho <button type="button" onclick="this.parentElement.remove()">✕</button></span>
                                    <span class="tag-chip">#Cumbia <button type="button" onclick="this.parentElement.remove()">✕</button></span>
                                    <input type="text" id="event_tags_input" class="tag-inner-input" placeholder="Agregar tag (ej. #MusicaEnVivo)..." onkeydown="handleTagKeydown(event)">
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
                                <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2rem; font-size: 1rem;" onclick="goToStep(2)">
                                    Continuar a Zonas & Tarifas ➔
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- STEP 2: ZONAS & TARIFAS (GESTOR DINÁMICO CON TIPOS DE AFORO DESDE MYSQL, PRECIO Y CAPACIDAD) -->
                <div class="step-content-panel" id="stepPanel2">
                    <div class="settings-card-box">
                        <div class="settings-card-header" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div class="card-header-icon" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: #EAB308;">🏷️</div>
                                <div>
                                    <h3 class="card-header-title">Paso 2: Zonas & Configuración de Tarifas</h3>
                                    <p class="card-header-subtitle">Selecciona el Tipo de Aforo desde MySQL y establece el Precio y Capacidad (Stock)</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-save-settings" style="font-size: 0.85rem; padding: 0.65rem 1.25rem;" onclick="addDynamicZoneRow()">
                                ➕ Añadir Nueva Zona / Sector
                            </button>
                        </div>

                        <!-- TABLA DINÁMICA DE ZONAS, TIPOS DE AFORO, CAPACIDAD Y PRECIOS -->
                        <div class="dash-table-container" style="margin-bottom: 1.5rem;">
                            <table class="dash-table" id="zonesDynamicTable">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">Tipo de Aforo (MySQL)</th>
                                        <th style="width: 30%;">Nombre Zona / Sector</th>
                                        <th style="width: 20%;">Capacidad (Boletos)</th>
                                        <th style="width: 20%;">Precio Individual (S/)</th>
                                        <th style="width: 5%; text-align: center;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="zonesTableBody">
                                    <!-- FILA DE ZONA 1 -->
                                    <tr class="zone-row">
                                        <td>
                                            <select class="form-select-custom zone-capacity-type" onchange="onCapacityTypeChange(this)" style="font-size: 0.85rem; padding: 0.55rem;">
                                                @foreach($capacityTypes as $ct)
                                                    <option value="{{ is_array($ct) ? $ct['name'] : $ct->name }}" selected>
                                                        🏟️ {{ is_array($ct) ? $ct['name'] : $ct->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-custom zone-name-input" value="ZONA VIP PLATINUM" style="font-size: 0.85rem; padding: 0.55rem;" oninput="updateLiveTicketPreview()">
                                        </td>
                                        <td>
                                            <input type="number" class="form-input-custom zone-capacity-input" value="1000" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                                        </td>
                                        <td>
                                            <input type="number" step="0.50" class="form-input-custom zone-price-input" value="150.00" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;" oninput="updateLiveTicketPreview()">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                                        </td>
                                    </tr>

                                    <!-- FILA DE ZONA 2 -->
                                    <tr class="zone-row">
                                        <td>
                                            <select class="form-select-custom zone-capacity-type" onchange="onCapacityTypeChange(this)" style="font-size: 0.85rem; padding: 0.55rem;">
                                                @foreach($capacityTypes as $ct)
                                                    <option value="{{ is_array($ct) ? $ct['name'] : $ct->name }}" {{ (is_array($ct) ? $ct['name'] : $ct->name) == 'ZONA VIP STAND UP' ? 'selected' : '' }}>
                                                        🏟️ {{ is_array($ct) ? $ct['name'] : $ct->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-custom zone-name-input" value="ZONA VIP STAND UP" style="font-size: 0.85rem; padding: 0.55rem;" oninput="updateLiveTicketPreview()">
                                        </td>
                                        <td>
                                            <input type="number" class="form-input-custom zone-capacity-input" value="1500" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                                        </td>
                                        <td>
                                            <input type="number" step="0.50" class="form-input-custom zone-price-input" value="95.00" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;" oninput="updateLiveTicketPreview()">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                                        </td>
                                    </tr>

                                    <!-- FILA DE ZONA 3 -->
                                    <tr class="zone-row">
                                        <td>
                                            <select class="form-select-custom zone-capacity-type" onchange="onCapacityTypeChange(this)" style="font-size: 0.85rem; padding: 0.55rem;">
                                                @foreach($capacityTypes as $ct)
                                                    <option value="{{ is_array($ct) ? $ct['name'] : $ct->name }}" {{ (is_array($ct) ? $ct['name'] : $ct->name) == 'ZONA GENERAL' ? 'selected' : '' }}>
                                                        🏟️ {{ is_array($ct) ? $ct['name'] : $ct->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-custom zone-name-input" value="ZONA GENERAL" style="font-size: 0.85rem; padding: 0.55rem;" oninput="updateLiveTicketPreview()">
                                        </td>
                                        <td>
                                            <input type="number" class="form-input-custom zone-capacity-input" value="2500" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                                        </td>
                                        <td>
                                            <input type="number" step="0.50" class="form-input-custom zone-price-input" value="55.50" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;" oninput="updateLiveTicketPreview()">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- RESUMEN TOTAL DE AFORO -->
                        <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); padding: 1rem 1.25rem; border-radius: 14px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #94A3B8;">Aforo Total Configurado:</span>
                                <strong id="totalCapacitySummaryText" style="font-size: 1.1rem; color: #FFFFFF; margin-left: 0.5rem;">5,000 entradas</strong>
                            </div>
                            <div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #94A3B8;">Precio Desde:</span>
                                <strong id="minPriceSummaryText" style="font-size: 1.1rem; color: #10B981; margin-left: 0.5rem;">S/ 55.50</strong>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(1)">
                                ← Anterior: Información General
                            </button>
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2rem; font-size: 1rem;" onclick="goToStep(3)">
                                Continuar a Selección de Plantilla ➔
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: SELECCIÓN DE PLANTILLA CANVA & PREVISUALIZACIÓN EN VIVO (SIN CONTROL DE BOLETO) -->
                <div class="step-content-panel" id="stepPanel3">
                    <div style="display: grid; grid-template-columns: 380px 1fr; gap: 1.75rem; align-items: start;">
                        
                        <!-- COLUMNA IZQUIERDA: SELECCIÓN DE PLANTILLA DE BOLETO DESDE MYSQL -->
                        <div class="settings-card-box">
                            <div class="settings-card-header">
                                <div class="card-header-icon" style="background: rgba(0, 242, 254, 0.15); border-color: rgba(0, 242, 254, 0.4); color: var(--color-neon-cyan);">🎨</div>
                                <div>
                                    <h3 class="card-header-title" style="font-size: 1.1rem;">Plantillas Canva (MySQL)</h3>
                                    <p class="card-header-subtitle">Selecciona el diseño oficial para los boletos de este espectáculo</p>
                                </div>
                            </div>

                            <input type="hidden" id="selected_template_id" value="{{ $templates->first()->id ?? 1 }}">

                            <div style="display: flex; flex-direction: column; gap: 1rem;" id="templatesSelectList">
                                @foreach($templates as $tpl)
                                    <div class="template-select-card {{ $loop->first ? 'selected-template' : '' }}" id="templateSelectCard_{{ $tpl->id }}" onclick="selectTicketTemplate({{ $tpl->id }})">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                            <span class="dash-badge-custom badge-orange" style="font-size: 0.725rem;">
                                                {{ $tpl->category }}
                                            </span>
                                            <span style="font-size: 0.8rem; font-weight: 800; color: #10B981;" id="tplCheck_{{ $tpl->id }}">
                                                {{ $loop->first ? '✓ Seleccionada' : 'Seleccionar' }}
                                            </span>
                                        </div>

                                        <h4 style="font-size: 0.95rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.35rem 0;">{{ $tpl->name }}</h4>
                                        
                                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #94A3B8;">
                                            <span>Fondo: <strong style="color: {{ $tpl->bg_color }}">{{ $tpl->bg_color }}</strong></span>
                                            <span>•</span>
                                            <span>Franja: <strong style="color: {{ $tpl->strip_color }}">{{ $tpl->strip_color }}</strong></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: PREVISUALIZACIÓN EN VIVO DEL BOLETO CON LA PLANTILLA SELECCIONADA -->
                        <div class="settings-card-box">
                            <div class="settings-card-header" style="justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 0.85rem;">
                                    <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">🎟️</div>
                                    <div>
                                        <h3 class="card-header-title">Previsualización de Boleto en Vivo</h3>
                                        <p class="card-header-subtitle">Vista previa realista utilizando el diseño y colores de la plantilla seleccionada</p>
                                    </div>
                                </div>
                                <span class="dash-badge-custom badge-green" id="selectedTemplateBadge">✓ {{ $templates->first()->name ?? 'Plantilla 1' }}</span>
                            </div>

                            <div style="padding: 1.5rem 0; overflow-x: auto; display: flex; justify-content: center;">
                                
                                <!-- CONTENEDOR DEL BOLETO REALISTA (TICKET COMPLETO CON DISEÑO DINÁMICO SEGÚN PLANTILLA) -->
                                <div class="live-ticket-card" id="liveTicketCardPreview" style="position: relative; width: 900px; height: 330px; border-radius: 20px; box-shadow: 0 35px 90px rgba(0,0,0,0.85); overflow: hidden; background: #FFFFFF; transition: all 0.3s ease;">
                                    
                                    <!-- FRANJA LATERAL IZQUIERDA O DERECHA CON LOGO -->
                                    <div class="ticket-side-strip" style="width: 78px; background: #000000; height: 100%; position: absolute; left: 0; top: 0; display: flex; align-items: center; justify-content: center; z-index: 2;">
                                        <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="max-width: 240px; height: 48px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 10px rgba(255,85,0,0.6));">
                                    </div>

                                    <!-- CUERPO PRINCIPAL DEL EVENTO -->
                                    <div class="ticket-main-body" style="position: absolute; left: 78px; right: 250px; top: 0; bottom: 0; background: #FFFFFF; padding: 1rem 1.25rem; display: flex; flex-direction: column; justify-content: space-between; border-right: 2px dashed #CBD5E1;">
                                        <!-- ENCABEZADO DEL TICKET -->
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div>
                                                <h2 id="prevTitle" style="font-size: 1.15rem; font-weight: 900; color: #000000; margin: 0 0 0.2rem 0;">Chúpate la Plata con Son del Duke en Ayacucho</h2>
                                                <span id="prevZone" style="font-size: 0.95rem; font-weight: 800; color: #1E293B;">ZONA VIP PLATINUM</span>
                                            </div>
                                            <div style="text-align: right;">
                                                <span style="font-size: 0.8rem; font-weight: 900; color: #000000; display: block;">PRECIO:</span>
                                                <span id="prevPrice" style="font-size: 1.35rem; font-weight: 900; color: #000000; display: block;">S/ 150.00</span>
                                            </div>
                                        </div>

                                        <!-- IMAGEN BANNER CENTRAL DEL EVENTO -->
                                        <div style="width: 100%; height: 135px; border-radius: 12px; overflow: hidden; margin: 0.4rem 0;">
                                            <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80" alt="Show Banner" id="prevBannerImg" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>

                                        <!-- FOOTER DE DATOS Y LUGAR -->
                                        <div style="display: flex; justify-content: space-between; align-items: flex-end; font-size: 0.8rem;">
                                            <div style="display: flex; flex-direction: column; color: #000000;">
                                                <span style="font-size: 0.75rem; color: #475569;">Comprador:</span>
                                                <span id="prevBuyerName" style="font-weight: 900; font-size: 0.95rem; text-transform: uppercase;">CHRISTIAN GOMEZ LUJAN</span>
                                                <span id="prevBuyerDni" style="font-weight: 800; font-size: 0.85rem; color: #1E293B;">DNI: 70436491</span>
                                            </div>

                                            <div style="text-align: right; display: flex; flex-direction: column; color: #000000;">
                                                <span id="prevVenue" style="font-weight: 900; font-size: 1rem;">Complejo San Luis</span>
                                                <span id="prevAddress" style="font-size: 0.825rem; font-weight: 700; color: #334155;">Av. Cusco 528 - AYACUCHO</span>
                                                <span id="prevDateTime" style="font-weight: 900; font-size: 1.05rem;">10.04.2025 / 06:00PM</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STUB DERECHO O IZQUIERDO DESPRENDIBLE -->
                                    <div class="ticket-qr-stub" style="position: absolute; right: 0; top: 0; width: 250px; height: 100%; background: #FAFAFA; border-left: 2px dashed #CBD5E1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0.85rem; gap: 0.35rem;">
                                        <span id="prevTicketNumber" style="font-size: 1.25rem; font-weight: 900; color: #000000; font-family: var(--font-heading);">N° 00396</span>

                                        <div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="125" height="125">
                                                <rect width="256" height="256" fill="#FFFFFF"/>
                                                <path d="M16,16H96V96H16Z M32,32V80H80V32Z M48,48H64V64H48Z" fill="#000000"/>
                                                <path d="M160,16H240V96H160Z M176,32V80H224V32Z M192,48H208V64H192Z" fill="#000000"/>
                                                <path d="M16,160H96V240H16Z M32,176V224H80V176Z M48,192H64V208H48Z" fill="#000000"/>
                                                <path d="M112,16H144V32H112Z M112,48H128V80H112Z M144,64H160V96H144Z M112,96H128V112H112Z M16,112H48V128H16Z M64,112H96V144H64Z M128,128H160V144H128Z M176,112H224V128H176Z M208,128H240V160H208Z M112,160H144V176H112Z M144,176H176V192H144Z M112,192H128V240H112Z M160,208H192V224H160Z M208,192H240V240H208Z M176,224H208V240H176Z M144,224H160V240H144Z" fill="#000000"/>
                                            </svg>
                                        </div>

                                        <span id="prevHash" style="font-family: monospace; font-size: 0.9rem; font-weight: 800; color: #000000; letter-spacing: 1.5px;">JAJHSPWFWJ</span>

                                        <p id="prevDisclaimer" style="font-size: 0.65rem; font-weight: 700; color: #334155; line-height: 1.25; margin: 0; text-align: center;">
                                            La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
                                <button type="button" class="btn btn-cancel-custom" onclick="goToStep(2)">
                                    ← Anterior: Zonas & Tarifas
                                </button>
                                <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2rem; font-size: 1rem;" onclick="goToStep(4)">
                                    Continuar a Confirmación ➔
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: CONFIRMACIÓN & PUBLICACIÓN (GUARDADO EN MYSQL) -->
                <div class="step-content-panel" id="stepPanel4">
                    <div class="settings-card-box">
                        <div class="settings-card-header">
                            <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">🚀</div>
                            <div>
                                <h3 class="card-header-title">Paso 4: Confirmación & Publicación del Evento</h3>
                                <p class="card-header-subtitle">Revisa el resumen antes de guardar el espectáculo en la Base de Datos MySQL</p>
                            </div>
                        </div>

                        <div class="credentials-card" style="margin-bottom: 2rem;">
                            <div class="cred-row">
                                <span class="cred-label">🎟️ Evento:</span>
                                <span class="cred-val" id="summaryTitle">Chúpate la Plata con Son del Duke en Ayacucho</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">🏢 Compañía Organizadora:</span>
                                <span class="cred-val" id="summaryCompany">ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">📍 Recinto & Lugar:</span>
                                <span class="cred-val" id="summaryVenue">Complejo San Luis (Av. Cusco 528 - AYACUCHO)</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">🗓️ Fecha & Hora:</span>
                                <span class="cred-val" id="summaryDateTime">10.04.2025 a las 06:00PM</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">👥 Aforo Total Configurado:</span>
                                <span class="cred-val" id="summaryTotalCapacity">5,000 entradas</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">💰 Precio Desde:</span>
                                <span class="cred-val" style="color: #10B981; font-weight: 900;" id="summaryPrice">S/ 55.50</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">🎨 Plantilla de Boleto Seleccionada:</span>
                                <span class="cred-val" id="summaryTemplateName">Plantilla 1: Taquilla Clásica Oficial 2026</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(3)">
                                ← Anterior: Selección de Plantilla
                            </button>
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.95rem 2.5rem; font-size: 1.05rem;" onclick="finishPublishEvent()">
                                🚀 Publicar y Guardar Evento en BD
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
        const csrfToken = '{{ csrf_token() }}';
        const templatesData = @json($templates);
        const capacityTypesData = @json($capacityTypes);
        let mapInstance = null;
        let markerInstance = null;

        function initLeafletMap() {
            const mapContainer = document.getElementById('interactiveLeafletMap');
            if (mapContainer && !mapInstance) {
                const defaultLat = -13.1631;
                const defaultLng = -74.2236;

                mapInstance = L.map('interactiveLeafletMap').setView([defaultLat, defaultLng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(mapInstance);

                markerInstance = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapInstance);
                markerInstance.bindPopup("<b>📍 Complejo San Luis</b><br>Ayacucho, Perú").openPopup();

                function updateMarkerCoords(lat, lng) {
                    const coordsText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    const coordsEl = document.getElementById('mapCoordsText');
                    if (coordsEl) coordsEl.textContent = coordsText;
                }

                updateMarkerCoords(defaultLat, defaultLng);

                mapInstance.on('click', function (e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    markerInstance.setLatLng([lat, lng]);
                    markerInstance.bindPopup(`<b>📍 Nuevo Pin Fijado</b><br>Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`).openPopup();
                    updateMarkerCoords(lat, lng);
                });

                markerInstance.on('dragend', function (e) {
                    const pos = markerInstance.getLatLng();
                    updateMarkerCoords(pos.lat, pos.lng);
                });
            }
        }

        function goToStep(stepNumber) {
            for (let i = 1; i <= 4; i++) {
                const indicator = document.getElementById('stepIndicator' + i);
                const panel = document.getElementById('stepPanel' + i);

                if (indicator) {
                    if (i === stepNumber) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                }

                if (panel) {
                    if (i === stepNumber) {
                        panel.classList.add('active');
                    } else {
                        panel.classList.remove('active');
                    }
                }
            }

            if (stepNumber === 1 && mapInstance) {
                setTimeout(() => { mapInstance.invalidateSize(); }, 250);
            }

            updateLiveTicketPreview();
            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function handleBannerUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const bannerInput = document.getElementById('event_banner');
                    const previewImg = document.getElementById('bannerPreviewImg');
                    if (bannerInput) bannerInput.value = e.target.result;
                    if (previewImg) previewImg.src = e.target.result;
                    updateLiveTicketPreview();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function formatSpanishDate(rawDate) {
            if (!rawDate) return '10.04.2025';
            const parts = rawDate.split('-');
            if (parts.length === 3) {
                return `${parts[2]}.${parts[1]}.${parts[0]}`;
            }
            return rawDate;
        }

        function formatSpanishTime(rawTime) {
            if (!rawTime) return '06:00PM';
            const parts = rawTime.split(':');
            if (parts.length >= 2) {
                let hours = parseInt(parts[0], 10);
                const minutes = parts[1];
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                const hoursStr = hours < 10 ? '0' + hours : hours;
                return `${hoursStr}:${minutes}${ampm}`;
            }
            return rawTime;
        }

        function addDynamicZoneRow() {
            const tbody = document.getElementById('zonesTableBody');
            if (!tbody) return;

            let optionsHtml = '';
            capacityTypesData.forEach(ct => {
                const name = ct.name || ct;
                optionsHtml += `<option value="${name}">🏟️ ${name}</option>`;
            });

            const tr = document.createElement('tr');
            tr.className = 'zone-row';
            tr.innerHTML = `
                <td>
                    <select class="form-select-custom zone-capacity-type" onchange="onCapacityTypeChange(this)" style="font-size: 0.85rem; padding: 0.55rem;">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-input-custom zone-name-input" value="NUEVA ZONA" style="font-size: 0.85rem; padding: 0.55rem;" oninput="updateLiveTicketPreview()">
                </td>
                <td>
                    <input type="number" class="form-input-custom zone-capacity-input" value="500" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                </td>
                <td>
                    <input type="number" step="0.50" class="form-input-custom zone-price-input" value="75.00" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;" oninput="updateLiveTicketPreview()">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                </td>
            `;

            tbody.appendChild(tr);
            recalculateTotalCapacity();
            updateLiveTicketPreview();
        }

        function removeZoneRow(btn) {
            const row = btn.closest('.zone-row');
            if (row) {
                row.remove();
                recalculateTotalCapacity();
                updateLiveTicketPreview();
            }
        }

        function onCapacityTypeChange(select) {
            const row = select.closest('.zone-row');
            if (row) {
                const nameInput = row.querySelector('.zone-name-input');
                if (nameInput) nameInput.value = select.value;
                updateLiveTicketPreview();
            }
        }

        function recalculateTotalCapacity() {
            let total = 0;
            let prices = [];

            document.querySelectorAll('.zone-row').forEach(row => {
                const cap = parseInt(row.querySelector('.zone-capacity-input')?.value, 10) || 0;
                const price = parseFloat(row.querySelector('.zone-price-input')?.value) || 0;
                total += cap;
                if (price > 0) prices.push(price);
            });

            const minPrice = prices.length > 0 ? Math.min(...prices) : 0;

            const capEl = document.getElementById('totalCapacitySummaryText');
            if (capEl) capEl.textContent = total.toLocaleString() + ' entradas';

            const priceEl = document.getElementById('minPriceSummaryText');
            if (priceEl) priceEl.textContent = 'S/ ' + minPrice.toFixed(2);

            const summaryCap = document.getElementById('summaryTotalCapacity');
            if (summaryCap) summaryCap.textContent = total.toLocaleString() + ' entradas';

            const summaryPrice = document.getElementById('summaryPrice');
            if (summaryPrice) summaryPrice.textContent = 'S/ ' + minPrice.toFixed(2);
        }

        function selectTicketTemplate(templateId) {
            document.getElementById('selected_template_id').value = templateId;

            document.querySelectorAll('.template-select-card').forEach(card => {
                card.classList.remove('selected-template');
            });
            const selectedCard = document.getElementById('templateSelectCard_' + templateId);
            if (selectedCard) selectedCard.classList.add('selected-template');

            // Actualizar etiquetas de texto "Seleccionada"
            document.querySelectorAll('[id^="tplCheck_"]').forEach(span => {
                span.textContent = 'Seleccionar';
            });
            const activeCheck = document.getElementById('tplCheck_' + templateId);
            if (activeCheck) activeCheck.textContent = '✓ Seleccionada';

            const tpl = templatesData.find(t => t.id == templateId);
            if (tpl) {
                const badge = document.getElementById('selectedTemplateBadge');
                if (badge) badge.textContent = `✓ ${tpl.name}`;
                
                const summaryTpl = document.getElementById('summaryTemplateName');
                if (summaryTpl) summaryTpl.textContent = tpl.name;

                renderLiveTicketPreviewFromTemplate(tpl);
            }
        }

        function renderLiveTicketPreviewFromTemplate(tpl) {
            const previewContainer = document.getElementById('liveTicketCardPreview');
            if (!previewContainer) return;

            const bgColor = tpl.bg_color || '#FFFFFF';
            const stripColor = tpl.strip_color || '#000000';
            const isPlantilla1 = (tpl.id == 1 || (tpl.name && tpl.name.includes('Plantilla 1')));
            const isPlantilla2 = (tpl.id == 2 || (tpl.name && tpl.name.includes('Plantilla 2')));

            previewContainer.style.background = bgColor;

            const sideStrip = previewContainer.querySelector('.ticket-side-strip');
            const stubArea = previewContainer.querySelector('.ticket-qr-stub');
            const mainBody = previewContainer.querySelector('.ticket-main-body');

            if (sideStrip) sideStrip.style.background = stripColor;

            if (isPlantilla1) {
                if (sideStrip) {
                    sideStrip.style.left = '0';
                    sideStrip.style.right = 'auto';
                    sideStrip.style.display = 'flex';
                }
                if (stubArea) {
                    stubArea.style.right = '0';
                    stubArea.style.left = 'auto';
                    stubArea.style.borderLeft = '2px dashed #CBD5E1';
                    stubArea.style.borderRight = 'none';
                    stubArea.style.display = 'flex';
                }
                if (mainBody) {
                    mainBody.style.left = '78px';
                    mainBody.style.right = '250px';
                    mainBody.style.borderRight = '2px dashed #CBD5E1';
                    mainBody.style.borderLeft = 'none';
                    mainBody.style.background = bgColor;
                }
            } else if (isPlantilla2) {
                if (sideStrip) {
                    sideStrip.style.right = '0';
                    sideStrip.style.left = 'auto';
                    sideStrip.style.display = 'flex';
                }
                if (stubArea) {
                    stubArea.style.left = '0';
                    stubArea.style.right = 'auto';
                    stubArea.style.borderRight = '2px dashed #CBD5E1';
                    stubArea.style.borderLeft = 'none';
                    stubArea.style.display = 'flex';
                }
                if (mainBody) {
                    mainBody.style.left = '250px';
                    mainBody.style.right = '78px';
                    mainBody.style.borderLeft = '2px dashed #CBD5E1';
                    mainBody.style.borderRight = 'none';
                    mainBody.style.background = bgColor;
                }
            } else {
                if (sideStrip) sideStrip.style.display = 'none';
                if (stubArea) stubArea.style.display = 'none';
                if (mainBody) {
                    mainBody.style.left = '0';
                    mainBody.style.right = '0';
                    mainBody.style.borderLeft = 'none';
                    mainBody.style.borderRight = 'none';
                    mainBody.style.background = bgColor;
                }
            }
        }

        function updateLiveTicketPreview() {
            const title = document.getElementById('event_title')?.value || 'Chúpate la Plata con Son del Duke en Ayacucho';
            const company = document.getElementById('event_company')?.value || 'ASOCIACIÓN CULTURAL ARTES UNIDAS ACAU';
            const venue = document.getElementById('event_venue')?.value || 'Complejo San Luis';
            const address = document.getElementById('event_address')?.value || 'Av. Cusco 528 - AYACUCHO';
            
            const rawDate = document.getElementById('event_date_picker')?.value;
            const date = formatSpanishDate(rawDate);

            const rawTime = document.getElementById('event_time_picker')?.value;
            const time = formatSpanishTime(rawTime);

            const firstZoneRow = document.querySelector('.zone-row');
            const zoneName = firstZoneRow?.querySelector('.zone-name-input')?.value || 'ZONA VIP PLATINUM';
            const zonePrice = parseFloat(firstZoneRow?.querySelector('.zone-price-input')?.value) || 150.00;

            const banner = document.getElementById('event_banner')?.value || 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80';

            const previewImg = document.getElementById('bannerPreviewImg');
            if (previewImg && banner) previewImg.src = banner;

            if (document.getElementById('prevTitle')) document.getElementById('prevTitle').textContent = title;
            if (document.getElementById('prevVenue')) document.getElementById('prevVenue').textContent = venue;
            if (document.getElementById('prevAddress')) document.getElementById('prevAddress').textContent = address;
            if (document.getElementById('prevDateTime')) document.getElementById('prevDateTime').textContent = `${date} / ${time}`;
            if (document.getElementById('prevPrice')) document.getElementById('prevPrice').textContent = `S/ ${zonePrice.toFixed(2)}`;
            if (document.getElementById('prevBannerImg')) document.getElementById('prevBannerImg').src = banner;
            if (document.getElementById('prevZone')) document.getElementById('prevZone').textContent = zoneName;

            if (document.getElementById('summaryTitle')) document.getElementById('summaryTitle').textContent = title;
            if (document.getElementById('summaryCompany')) document.getElementById('summaryCompany').textContent = company;
            if (document.getElementById('summaryVenue')) document.getElementById('summaryVenue').textContent = `${venue} (${address})`;
            if (document.getElementById('summaryDateTime')) document.getElementById('summaryDateTime').textContent = `${date} a las ${time}`;

            recalculateTotalCapacity();
        }

        function handleTagKeydown(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const input = document.getElementById('event_tags_input');
                const val = input.value.trim().replace(/^#+/, '');
                
                if (val) {
                    const tagChip = document.createElement('span');
                    tagChip.className = 'tag-chip';
                    tagChip.innerHTML = `#${val} <button type="button" onclick="this.parentElement.remove()">✕</button>`;
                    
                    const wrapper = document.getElementById('tagsWrapper');
                    wrapper.insertBefore(tagChip, input);
                    input.value = '';
                }
            }
        }

        function finishPublishEvent() {
            const title = document.getElementById('event_title').value;
            const categoryName = document.getElementById('event_category').value;
            const companyName = document.getElementById('event_company').value;
            const bannerImage = document.getElementById('event_banner').value;
            const eventDate = document.getElementById('event_date_picker').value;
            const eventTime = document.getElementById('event_time_picker').value;
            const venueName = document.getElementById('event_venue').value;
            const address = document.getElementById('event_address').value;
            const details = document.getElementById('event_details').value;
            const templateId = document.getElementById('selected_template_id').value;

            // Recopilar tags
            const tags = [];
            document.querySelectorAll('#tagsWrapper .tag-chip').forEach(chip => {
                tags.push(chip.textContent.replace('✕', '').trim());
            });

            // Recopilar zonas
            const zones = [];
            document.querySelectorAll('.zone-row').forEach(row => {
                const capType = row.querySelector('.zone-capacity-type').value;
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

            fetch("{{ route('web.events.store') }}", {
                method: 'POST',
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
                        title: '🎉 ¡Evento Publicado y Guardado en MySQL!',
                        text: `El espectáculo "${title}" y sus zonas se han guardado exitosamente en la Base de Datos.`,
                        icon: 'success',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then(() => {
                        window.location.href = "{{ route('web.events') }}";
                    });
                } else {
                    Swal.fire({ title: 'Error', text: 'No se pudo guardar el evento.', icon: 'error', background: '#14141E', color: '#FFF' });
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
            recalculateTotalCapacity();
            
            // Seleccionar primera plantilla por defecto
            if (templatesData && templatesData.length > 0) {
                selectTicketTemplate(templatesData[0].id);
            } else {
                updateLiveTicketPreview();
            }

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
