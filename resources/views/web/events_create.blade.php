@extends('layouts.app')

@section('title', 'Crear Nuevo Evento | Vive Go')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600;800;900&family=Montserrat:wght@400;700;900&family=Oswald:wght@500;700&family=Outfit:wght@400;700;900&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Poppins:wght@400;700;900&family=Roboto:wght@400;700;900&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
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

        /* BARRA SUPERIOR DE FORMATO ESTILO CANVA STUDIO PRO (771PX) */
        .canva-top-studio-toolbar {
            width: 771px;
            max-width: 100%;
            background: #14141E;
            border: 1.5px solid rgba(255, 85, 0, 0.4);
            border-radius: 14px;
            padding: 0.4rem 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.45rem;
            margin-bottom: 0.85rem;
            flex-wrap: wrap;
            z-index: 20;
            backdrop-filter: blur(10px);
        }
        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 4px;
            background: rgba(255, 255, 255, 0.06);
            padding: 3px 6px;
            border-radius: 8px;
        }
        .toolbar-btn {
            background: transparent;
            border: none;
            color: #FFFFFF;
            padding: 0.25rem 0.45rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.825rem;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toolbar-btn:hover {
            background: rgba(255, 85, 0, 0.25);
            color: #FF5500;
        }
        .toolbar-btn.active {
            background: #FF5500;
            color: #FFFFFF;
        }
        .toolbar-btn-danger:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #EF4444;
        }
        .toolbar-divider {
            width: 1px;
            height: 18px;
            background: rgba(255, 255, 255, 0.15);
        }
        .font-size-indicator {
            font-size: 0.75rem;
            font-weight: 800;
            color: #FFFFFF;
            min-width: 32px;
            text-align: center;
        }
        .toolbar-font-select {
            background: #1E1E2D;
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            padding: 0.25rem 0.45rem;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            outline: none;
            max-width: 140px;
        }
        .toolbar-font-select:focus {
            border-color: #FF5500;
        }
        .color-picker-input {
            width: 26px;
            height: 26px;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            cursor: pointer;
            background: transparent;
            padding: 0;
            vertical-align: middle;
            transition: border-color 0.15s ease;
        }
        .color-picker-input:hover {
            border-color: #FF5500;
        }

        /* ETIQUETAS Y ELEMENTOS DRAGGABLE 100% LIBRES */
        .canva-drag-element {
            position: absolute !important;
            cursor: move;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            transform-origin: center center;
            box-sizing: border-box;
            user-select: none;
            -webkit-user-select: none;
        }
        .canva-drag-box-container {
            display: inline-flex;
            flex-direction: column;
            padding: 3px 6px;
            border-radius: 4px;
            background: transparent;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            justify-content: center;
            cursor: move;
            pointer-events: auto !important;
            user-select: none;
            -webkit-user-select: none;
            outline: none;
        }
        .canva-drag-box-container[contenteditable="true"] {
            cursor: text !important;
            user-select: text !important;
            -webkit-user-select: text !important;
            background: rgba(255, 85, 0, 0.06);
            border-radius: 4px;
        }
        .canva-drag-box-container.has-badge-bg {
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        .canva-drag-element.selected-element {
            outline: 1.5px solid #FF5500 !important;
            outline-offset: 0px !important;
            border-radius: 3px;
        }
        .canva-resize-handle {
            position: absolute;
            width: 7px;
            height: 7px;
            background: #FFFFFF;
            border: 1.5px solid #FF5500;
            border-radius: 1px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
            z-index: 999;
            display: none;
            pointer-events: auto;
        }
        .canva-drag-element.selected-element .canva-resize-handle {
            display: block;
        }
        .handle-nw { top: -4px; left: -4px; cursor: nw-resize; }
        .handle-ne { top: -4px; right: -4px; cursor: ne-resize; }
        .handle-sw { bottom: -4px; left: -4px; cursor: sw-resize; }
        .handle-se { bottom: -4px; right: -4px; cursor: se-resize; }

        /* LIENZO OFICIAL CANVA 20.40CM X 9.80CM (771PX X 370PX) */
        .canva-official-canvas {
            position: relative;
            width: 771px;
            height: 370px;
            border-radius: 18px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.15);
            overflow: hidden;
            background: #FFFFFF;
            transition: background 0.3s ease;
            margin: 0 auto;
        }
        .canva-tag-icon {
            display: inline-block;
            vertical-align: -2px;
            margin-right: 5px;
            flex-shrink: 0;
        }
        .canvas-dimension-badge {
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            color: #38BDF8;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
    </style>
@endpush

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
                                Continuar a Personalización de Boleto ➔
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: PERSONALIZACIÓN DE BOLETO EN CANVA STUDIO (20.40CM X 9.80CM) -->
                <div class="step-content-panel" id="stepPanel3">
                    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; align-items: start;">
                        
                        <!-- COLUMNA IZQUIERDA: HERRAMIENTAS, PRESETS & CAPAS DE CANVA -->
                        <div class="settings-card-box" style="padding: 1.25rem; display: flex; flex-direction: column; gap: 1.25rem;">
                            
                            <!-- SECCIÓN: SELECTOR DE PLANTILLA BASE PRECONFIGURADA -->
                            <div>
                                <div class="settings-card-header" style="margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                    <div class="card-header-icon" style="background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06B6D4; width: 34px; height: 34px; font-size: 1rem;">🎨</div>
                                    <div>
                                        <h4 style="font-size: 0.925rem; font-weight: 900; color: #FFFFFF; margin: 0;">Plantilla Base</h4>
                                        <p style="font-size: 0.75rem; color: #94A3B8; margin: 0;">Cargar diseño predefinido</p>
                                    </div>
                                </div>
                                <input type="hidden" id="selected_template_id" value="4">
                                <select id="baseTemplateSelector" class="form-select-custom" style="font-size: 0.85rem; padding: 0.6rem;" onchange="loadPresetTemplate(this.value)">
                                    <optgroup label="📱 Plantillas Virtuales (E-Tickets)">
                                        <option value="4" selected>📱 Plantilla Virtual 1: E-Ticket Dark Neon Pro (Recomendada)</option>
                                        <option value="5">📱 Plantilla Virtual 2: Mobile Pass Cyber Glow</option>
                                        <option value="6">📱 Plantilla Virtual 3: Entrada Digital Minimal Gold</option>
                                    </optgroup>
                                    <optgroup label="🎟️ Plantillas Físicas (Taquilla Impresa)">
                                        <option value="1">🎟️ Plantilla 1: Taquilla Clásica Oficial 2026</option>
                                        <option value="2">🎟️ Plantilla 2: Franja Logo Derecho & Stub Izquierdo</option>
                                        <option value="3">🎟️ Plantilla 3: Hero Banner Panorámico & QR Central</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- SECCIÓN 1: AGREGAR ETIQUETA / TEXTO PERSONALIZADO -->
                            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 0.85rem; border-radius: 14px;">
                                <h5 style="font-size: 0.8rem; font-weight: 900; color: var(--color-primary-orange); letter-spacing: 0.5px; margin: 0 0 0.5rem 0;">
                                    ➕ AGREGAR NUEVA ETIQUETA
                                </h5>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <input type="text" id="newCustomTagInput" class="form-input-custom" placeholder="Ej. #SponsorOficial, Ingreso +18..." style="font-size: 0.825rem; padding: 0.55rem 0.75rem;">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.55rem 0.75rem; font-size: 0.8rem; text-align: center; justify-content: center;" onclick="createNewCustomTag()">
                                        ➕ Añadir Etiqueta al Boleto
                                    </button>
                                </div>
                            </div>

                            <!-- SECCIÓN 2: CAMPOS DEL SISTEMA DISPONIBLES -->
                            <div>
                                <h5 style="font-size: 0.8rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.5rem;">📌 CAMPOS DEL SISTEMA</h5>
                                <div style="display: flex; flex-direction: column; gap: 0.35rem;" id="systemElementsList">
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_logo" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('logo')">
                                        <span>🖼️ Logo Marca Oficial</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_titulo" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('titulo')">
                                        <span>📝 Título / Nombre Show</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_zona" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('zona')">
                                        <span>🏷️ Zona / Sector</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_precio" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('precio')">
                                        <span>💰 Precio de Entrada</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_banner" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('banner')">
                                        <span>🖼️ Banner para Ticket</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_comprador_nombre" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('buyer_name')">
                                        <span>👤 Nombre Comprador</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_comprador_dni" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('buyer_dni')">
                                        <span>🆔 DNI Comprador</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_recinto" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('recinto')">
                                        <span>📍 Recinto del Show</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_ciudad" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('ciudad')">
                                        <span>🏙️ Ciudad / Dirección</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_fecha" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('fecha')">
                                        <span>📅 Fecha del Evento</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_hora" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('hora')">
                                        <span>⏰ Hora del Evento</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_ticket_number" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('ticket_number')">
                                        <span>🔢 N° Correlativo</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_qr" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('qr')">
                                        <span>📲 Código QR Gigante</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_hash" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('hash')">
                                        <span>🔑 Hash Validación</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_disclaimer" style="justify-content: space-between; font-size: 0.8rem; padding: 0.45rem 0.75rem;" onclick="toggleSystemElement('disclaimer')">
                                        <span>📜 Disclaimer / Nota Legal</span>
                                        <span class="field-status-badge" style="font-size: 0.725rem; color: #06B6D4;">+ Añadir</span>
                                    </button>
                                </div>
                            </div>

                            <!-- SECCIÓN 3: SUBIR IMÁGENES DESDE PC (BANNER PARA TICKET & FONDO) -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.85rem; display: flex; flex-direction: column; gap: 1rem;">
                                
                                <!-- APARTADO: BANNER PARA TICKET -->
                                <div>
                                    <h5 style="font-size: 0.8rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                                        🖼️ BANNER PARA TICKET
                                    </h5>
                                    
                                    <input type="file" id="canvaTicketBannerFileInput" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="handleTicketBannerUpload(event)">
                                    <input type="hidden" id="canvaTicketBannerInput" value="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80">

                                    <div id="ticketBannerUploadBoxFilled" style="display: flex; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; padding: 0.65rem; flex-direction: column; gap: 0.5rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div id="ticketBannerThumbPreview" style="width: 48px; height: 32px; border-radius: 6px; background-size: cover; background-position: center; border: 1px solid rgba(255,255,255,0.2); background-image: url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80');"></div>
                                            <div style="flex: 1; overflow: hidden;">
                                                <div id="ticketBannerFileNameText" style="font-size: 0.775rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Banner de Boleto</div>
                                                <div style="font-size: 0.68rem; color: #10B981; font-weight: 700;">✓ Imagen para Ticket Activa</div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 0.35rem;">
                                            <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.4rem; font-size: 0.725rem; text-align: center; justify-content: center;" onclick="document.getElementById('canvaTicketBannerFileInput').click()">
                                                🔄 Cambiar Banner
                                            </button>
                                            <button type="button" class="btn btn-cancel-custom" style="padding: 0.4rem 0.65rem; font-size: 0.725rem; color: #EF4444;" onclick="removeTicketBannerImage()" title="Quitar Banner">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- APARTADO: COLOR DE FONDO DEL BOLETO -->
                                <div>
                                    <h5 style="font-size: 0.8rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                                        🎨 COLOR DE FONDO DEL BOLETO
                                    </h5>
                                    <div style="display: flex; align-items: center; gap: 0.65rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; padding: 0.6rem 0.85rem;">
                                        <input type="color" id="canvaBgColorPicker" value="#FFFFFF" oninput="onCanvaBgColorChange(this.value)" onchange="onCanvaBgColorChange(this.value)" style="width: 38px; height: 32px; border: none; border-radius: 6px; cursor: pointer; background: transparent;">
                                        <span id="canvaBgColorHexText" style="font-size: 0.8rem; font-weight: 800; color: #FFFFFF; font-family: monospace;">#FFFFFF</span>
                                    </div>
                                </div>

                                <!-- APARTADO: IMAGEN DE FONDO DESDE TU PC -->
                                <div>
                                    <h5 style="font-size: 0.8rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.5rem;">
                                        🌌 IMAGEN DE FONDO DEL BOLETO
                                    </h5>
                                    
                                    <input type="file" id="canvaBgFileInput" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="handleBgFileUpload(event)">
                                    <input type="hidden" id="canvaBgImageInput" value="">
                                    <input type="hidden" id="canvaBgColor" value="#FFFFFF">
                                    <input type="hidden" id="canvaStripColor" value="#FF5500">

                                    <div id="bgUploadBoxEmpty" onclick="document.getElementById('canvaBgFileInput').click()" style="display: block; border: 2px dashed rgba(255, 85, 0, 0.4); border-radius: 12px; padding: 0.85rem; text-align: center; cursor: pointer; background: rgba(255, 85, 0, 0.05);">
                                        <div style="font-size: 1.4rem; margin-bottom: 0.2rem;">📁</div>
                                        <div style="font-size: 0.8rem; font-weight: 800; color: #FFFFFF;">Subir Fondo desde mi PC</div>
                                        <div style="font-size: 0.7rem; color: #94A3B8;">PNG, JPG o WEBP (Opcional)</div>
                                    </div>

                                    <div id="bgUploadBoxFilled" style="display: none; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; padding: 0.65rem; flex-direction: column; gap: 0.5rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div id="bgThumbPreview" style="width: 48px; height: 32px; border-radius: 6px; background-size: cover; background-position: center; border: 1px solid rgba(255,255,255,0.2);"></div>
                                            <div style="flex: 1; overflow: hidden;">
                                                <div id="bgFileNameText" style="font-size: 0.775rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Fondo Personalizado</div>
                                                <div style="font-size: 0.68rem; color: #10B981; font-weight: 700;">✓ Fondo Activo</div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 0.35rem;">
                                            <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.4rem; font-size: 0.725rem; text-align: center; justify-content: center;" onclick="document.getElementById('canvaBgFileInput').click()">
                                                🔄 Cambiar Fondo
                                            </button>
                                            <button type="button" class="btn btn-cancel-custom" style="padding: 0.4rem 0.65rem; font-size: 0.725rem; color: #EF4444;" onclick="removeCanvaBgImage()" title="Quitar Fondo">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: BARRA SUPERIOR + LIENZO OFICIAL CANVA STUDIO PRO (771PX X 370PX) -->
                        <div class="settings-card-box" style="padding: 1.25rem; display: flex; flex-direction: column; align-items: center;">
                            
                            <div style="width: 771px; max-width: 100%; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                                <div style="display: flex; align-items: center; gap: 0.65rem;">
                                    <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981; width: 34px; height: 34px; font-size: 1rem;">🎟️</div>
                                    <h3 class="card-header-title" style="font-size: 1rem;">Diseño Oficial del Boleto</h3>
                                </div>
                                <span class="canvas-dimension-badge">
                                    📐 20.40 cm × 9.80 cm (Proporción Oficial)
                                </span>
                            </div>

                            <!-- BARRA SUPERIOR DE FORMATO ESTILO CANVA STUDIO PRO -->
                            <div class="canva-top-studio-toolbar" id="canvaStudioTopToolbar">
                                <!-- TIPOGRAFÍA / GOOGLE FONTS -->
                                <div class="toolbar-group">
                                    <select id="floatingFontFamilySelect" class="toolbar-font-select" onmousedown="event.stopPropagation();" onchange="applyFloatingFormat('fontFamily', this.value)" title="Cambiar Fuente Tipográfica">
                                        <option value="inherit">🔤 Fuente Estándar</option>
                                        <option value="'Inter', sans-serif">Inter (Moderna)</option>
                                        <option value="'Montserrat', sans-serif">Montserrat</option>
                                        <option value="'Poppins', sans-serif">Poppins</option>
                                        <option value="'Bebas Neue', sans-serif">Bebas Neue</option>
                                        <option value="'Oswald', sans-serif">Oswald</option>
                                        <option value="'Outfit', sans-serif">Outfit</option>
                                        <option value="'Playfair Display', serif">Playfair Display (VIP)</option>
                                        <option value="'Space Grotesk', sans-serif">Space Grotesk</option>
                                        <option value="'Roboto', sans-serif">Roboto</option>
                                        <option value="monospace">Monospace (Código)</option>
                                    </select>
                                </div>

                                <!-- TAMAÑO DE FUENTE -->
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('fontSize', 'dec')" title="Disminuir tamaño (A-)">A-</button>
                                    <span class="font-size-indicator" id="floatingFontSizeText">14px</span>
                                    <button type="button" class="toolbar-btn" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('fontSize', 'inc')" title="Aumentar tamaño (A+)">A+</button>
                                </div>

                                <!-- SELECTOR DE COLOR PERSONALIZADO -->
                                <div class="toolbar-group">
                                    <input type="color" id="floatingColorPicker" class="color-picker-input" value="#000000" oninput="applyFloatingFormat('color', this.value)" onchange="applyFloatingFormat('color', this.value)" title="Elegir Color de Texto / Elemento">
                                </div>

                                <!-- ESTILOS DE TEXTO B / I / U -->
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" id="btnFloatingBold" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('bold')" title="Negrita (B)"><strong>B</strong></button>
                                    <button type="button" class="toolbar-btn" id="btnFloatingItalic" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('italic')" title="Cursiva (I)"><em>I</em></button>
                                    <button type="button" class="toolbar-btn" id="btnFloatingUnderline" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('underline')" title="Subrayado (U)"><u>U</u></button>
                                </div>

                                <!-- ALINEACIÓN -->
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" id="btnAlignLeft" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('align', 'left')" title="Alinear Izquierda">⬅</button>
                                    <button type="button" class="toolbar-btn" id="btnAlignCenter" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('align', 'center')" title="Centrar">⬌</button>
                                    <button type="button" class="toolbar-btn" id="btnAlignRight" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('align', 'right')" title="Alinear Derecha">➡</button>
                                </div>

                                <!-- ACCIONES ADICIONALES -->
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('textTransform')" title="Mayúsculas / Minúsculas (Aa)">Aa</button>
                                    <button type="button" class="toolbar-btn" id="btnFloatingBadgeBg" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('badgeBg')" title="Fondo Destacado">🔲</button>
                                    <button type="button" class="toolbar-btn" onmousedown="event.preventDefault();" onclick="applyFloatingFormat('rotate')" title="Girar 90°">🔄</button>
                                    <button type="button" class="toolbar-btn toolbar-btn-danger" onmousedown="event.preventDefault();" onclick="deleteSelectedCanvaElement()" title="Eliminar Etiqueta">🗑️</button>
                                </div>
                            </div>

                            <!-- CONTENEDOR DEL LIENZO OFICIAL CANVA (771PX X 370PX) - FONDO BLANCO POR DEFECTO -->
                            <div class="canva-official-canvas" id="canvaTicketCanvas" style="background-color: #FFFFFF; background-image: none; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.12);">
                                
                                <div id="canvaCanvasArea" style="position: absolute; inset: 0; width: 100%; height: 100%;">
                                    
                                    <!-- LOGO DE MARCA -->
                                    <div class="canva-drag-element" id="canvaElLogo" style="top: 15px; left: 25px;">
                                        <div class="canva-drag-box-container">
                                            <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" alt="Logo" style="height: 32px; width: auto; object-fit: contain; pointer-events: none;">
                                        </div>
                                    </div>

                                    <!-- TÍTULO DEL EVENTO (12PX) -->
                                    <div class="canva-drag-element" id="canvaElTitle" style="top: 55px; left: 25px; width: 380px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" style="font-size: 12px; font-weight: 900; color: #000000; margin: 0; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">Chúpate la Plata con Son del Duke en Ayacucho</div>
                                        </div>
                                    </div>

                                    <!-- ZONA / SECTOR (12PX) -->
                                    <div class="canva-drag-element" id="canvaElZone" style="top: 95px; left: 25px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" style="font-size: 12px; font-weight: 800; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">ZONA: BOX PLATINUM INDIVIDUAL</div>
                                        </div>
                                    </div>

                                    <!-- PRECIO (12PX) -->
                                    <div class="canva-drag-element" id="canvaElPrice" style="top: 95px; left: 240px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" style="font-size: 12px; font-weight: 900; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">PRECIO: S/ 150.00</div>
                                        </div>
                                    </div>

                                    <!-- BANNER PARA TICKET -->
                                    <div class="canva-drag-element" id="canvaElBanner" style="top: 15px; left: 340px; width: 250px; height: 110px;">
                                        <div class="canva-drag-box-container" style="padding: 0; width: 100%; height: 100%; overflow: hidden; border-radius: 12px;">
                                            <img id="canvaPrevBannerImg" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80" alt="Banner" style="width: 100%; height: 100%; object-fit: cover; border: none; outline: none; box-shadow: none; pointer-events: none; display: block;">
                                        </div>
                                    </div>

                                    <!-- NOMBRE COMPRADOR (12PX) -->
                                    <div class="canva-drag-element" id="canvaElBuyerName" style="top: 140px; left: 25px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" id="canvaPrevBuyerNameText" style="font-weight: 900; font-size: 12px; text-transform: uppercase; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">COMPRADOR: CHRISTIAN GOMEZ LUJAN</div>
                                        </div>
                                    </div>

                                    <!-- DNI COMPRADOR (SIN ICONO, 12PX) -->
                                    <div class="canva-drag-element" id="canvaElBuyerDni" style="top: 165px; left: 25px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" id="canvaPrevBuyerDniText" style="font-weight: 800; font-size: 12px; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">DNI: 70436491</div>
                                        </div>
                                    </div>

                                    <!-- RECINTO / LUGAR (12PX) -->
                                    <div class="canva-drag-element" id="canvaElVenue" style="top: 200px; left: 25px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" id="canvaPrevVenueText" style="font-weight: 900; font-size: 12px; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">Complejo San Luis</div>
                                        </div>
                                    </div>

                                    <!-- CIUDAD / DIRECCIÓN (12PX) -->
                                    <div class="canva-drag-element" id="canvaElCity" style="top: 225px; left: 25px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" id="canvaPrevAddressText" style="font-size: 12px; font-weight: 700; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">Av. Cusco 528 - AYACUCHO</div>
                                        </div>
                                    </div>

                                    <!-- FECHA DEL EVENTO (12PX) -->
                                    <div class="canva-drag-element" id="canvaElDate" style="top: 260px; left: 25px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" id="canvaPrevDateText" style="font-weight: 900; font-size: 12px; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">FECHA: 28/02/2026</div>
                                        </div>
                                    </div>

                                    <!-- HORA DEL EVENTO (12PX) -->
                                    <div class="canva-drag-element" id="canvaElTime" style="top: 260px; left: 220px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" id="canvaPrevTimeText" style="font-weight: 900; font-size: 12px; color: #000000; line-height: 1.25; outline: none; white-space: pre-wrap; word-break: break-word;">HORA: 08:00 PM</div>
                                        </div>
                                    </div>

                                    <!-- NÚMERO CORRELATIVO (SIN ICONO, 12PX) -->
                                    <div class="canva-drag-element" id="canvaElTicketNumber" style="top: 15px; left: 660px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" style="font-size: 12px; font-weight: 900; color: #000000; font-family: var(--font-heading); outline: none; white-space: pre-wrap; word-break: break-word;">N° 00396</div>
                                        </div>
                                    </div>

                                    <!-- CÓDIGO QR GIGANTE (PROPORCIÓN CUADRADA 1:1) -->
                                    <div class="canva-drag-element" id="canvaElQR" style="top: 55px; left: 635px; width: 95px; height: 95px;">
                                        <div class="canva-drag-box-container" style="background: #FFFFFF; padding: 5px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.15); width: 100%; height: 100%; box-sizing: border-box; display: flex; align-items: center; justify-content: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" style="width: 100%; height: 100%; pointer-events: none; display: block;">
                                                <rect width="256" height="256" fill="#FFFFFF"/>
                                                <path d="M16,16H96V96H16Z M32,32V80H80V32Z M48,48H64V64H48Z" fill="#000000"/>
                                                <path d="M160,16H240V96H160Z M176,32V80H224V32Z M192,48H208V64H192Z" fill="#000000"/>
                                                <path d="M16,160H96V240H16Z M32,176V224H80V176Z M48,192H64V208H48Z" fill="#000000"/>
                                                <path d="M112,16H144V32H112Z M112,48H128V80H112Z M144,64H160V96H144Z M112,96H128V112H112Z M16,112H48V128H16Z M64,112H96V144H64Z M128,128H160V144H128Z M176,112H224V128H176Z M208,128H240V160H208Z M112,160H144V176H112Z M144,176H176V192H144Z M112,192H128V240H112Z M160,208H192V224H160Z M208,192H240V240H208Z M176,224H208V240H176Z M144,224H160V240H144Z" fill="#000000"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- HASH DE VALIDACIÓN (SIN ICONO, 12PX) -->
                                    <div class="canva-drag-element" id="canvaElHash" style="top: 175px; left: 645px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" style="font-family: monospace; font-size: 12px; font-weight: 800; color: #000000; letter-spacing: 1.5px; outline: none; white-space: pre-wrap; word-break: break-word;">JAJHSPWFWJ</div>
                                        </div>
                                    </div>

                                    <!-- DISCLAIMER / BOLETO DIGITAL (SIN ICONO, 12PX) -->
                                    <div class="canva-drag-element" id="canvaElDisclaimer" style="top: 205px; left: 610px; width: 145px;">
                                        <div class="canva-drag-box-container" style="font-size: 12px;">
                                            <div class="canva-text-content" style="font-size: 11px; font-weight: 700; color: #000000; line-height: 1.25; margin: 0; text-align: center; outline: none; white-space: pre-wrap; word-break: break-word;">La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.</div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div style="width: 771px; max-width: 100%; display: flex; justify-content: space-between; margin-top: 1.5rem;">
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
                                <p class="card-header-subtitle">Revisa el resumen y el boleto configurado antes de publicar el espectáculo</p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                            <!-- RESUMEN DE DATOS -->
                            <div class="credentials-card" style="margin: 0;">
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
                                    <span class="cred-label">🌐 Modalidad:</span>
                                    <span class="cred-val" style="color: #06B6D4; font-weight: 800;" id="summarySalesType">🌐 Venta Virtual (E-Ticket)</span>
                                </div>
                            </div>

                            <!-- VISTA PREVIA RESUMIDA DEL BOLETO DISEÑADO -->
                            <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.12); padding: 1.25rem; border-radius: 16px; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                        <h4 style="font-size: 0.85rem; font-weight: 900; color: #FFFFFF; margin: 0;">🎨 Boleto Oficial Configurado</h4>
                                        <span class="dash-badge-custom badge-green" style="font-size: 0.75rem;">✓ Listo para emitir</span>
                                    </div>
                                    <p style="font-size: 0.8rem; color: #94A3B8; margin-bottom: 0.85rem;">
                                        Este diseño personalizado se aplicará a todas las entradas vendidas para este evento.
                                    </p>
                                </div>
                                <div style="background: rgba(0,0,0,0.5); padding: 1rem; border-radius: 12px; border: 1px dashed rgba(255,255,255,0.2); text-align: center;">
                                    <span style="font-size: 1.75rem; display: block; margin-bottom: 0.25rem;">🎟️</span>
                                    <strong style="color: #FFFFFF; font-size: 0.9rem;" id="summaryTicketLabel">Plantilla Virtual 1 Personalizada</strong>
                                    <small style="display: block; color: #94A3B8; margin-top: 0.25rem;">Dimensiones: 20.40 cm × 9.80 cm</small>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(3)">
                                ← Anterior: Personalización de Boleto
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

        function compressImageFile(file, maxWidth = 1200, maxHeight = 800, quality = 0.85) {
            return new Promise((resolve) => {
                if (!file || !file.type.startsWith('image/')) {
                    resolve(null);
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth || height > maxHeight) {
                            if (width / height > maxWidth / maxHeight) {
                                height = Math.round((height * maxWidth) / width);
                                width = maxWidth;
                            } else {
                                width = Math.round((width * maxHeight) / height);
                                height = maxHeight;
                            }
                        }

                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        const mimeType = (file.type === 'image/png') ? 'image/png' : 'image/jpeg';
                        const compressedDataUrl = canvas.toDataURL(mimeType, quality);
                        resolve(compressedDataUrl);
                    };
                    img.onerror = () => resolve(e.target.result);
                    img.src = e.target.result;
                };
                reader.onerror = () => resolve(null);
                reader.readAsDataURL(file);
            });
        }

        async function handleBannerUpload(input) {
            if (input.files && input.files[0]) {
                const compressedDataUrl = await compressImageFile(input.files[0], 1200, 700, 0.85);
                if (compressedDataUrl) {
                    const bannerInput = document.getElementById('event_banner');
                    const previewImg = document.getElementById('bannerPreviewImg');
                    if (bannerInput) bannerInput.value = compressedDataUrl;
                    if (previewImg) previewImg.src = compressedDataUrl;
                    updateLiveTicketPreview();
                }
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

        let currentCanvaElement = null;
        let isDraggingCanva = false;
        let isResizingCanva = false;
        let currentResizeHandle = null;
        let startX = 0, startY = 0, startLeft = 0, startTop = 0, startWidth = 0, startHeight = 0;
        let tagCounter = 1;

        // PLANTILLAS PREDEFINIDAS
        const PRESET_TEMPLATES = {
            '4': {
                name: 'Plantilla Virtual 1: E-Ticket Oficial White Clean',
                type: 'virtual',
                bg_color: '#FFFFFF',
                strip_color: '#000000',
                bg_image: null,
                ticket_banner: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                positions: {
                    canvaElLogo: { top: '15px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTitle: { top: '55px', left: '25px', width: '380px', height: 'auto', rotate: 0, visible: true },
                    canvaElZone: { top: '95px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElPrice: { top: '95px', left: '240px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBanner: { top: '15px', left: '340px', width: '250px', height: '110px', rotate: 0, visible: true },
                    canvaElBuyerName: { top: '140px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBuyerDni: { top: '165px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElVenue: { top: '200px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElCity: { top: '225px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDate: { top: '260px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTime: { top: '260px', left: '220px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTicketNumber: { top: '15px', left: '660px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElQR: { top: '55px', left: '635px', width: '95px', height: '95px', rotate: 0, visible: true },
                    canvaElHash: { top: '175px', left: '645px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDisclaimer: { top: '205px', left: '610px', width: '145px', height: 'auto', rotate: 0, visible: true }
                }
            },
            '5': {
                name: 'Plantilla Virtual 2: Mobile Pass Cyber Glow',
                type: 'virtual',
                bg_color: '#090D16',
                strip_color: '#00F0FF',
                bg_image: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
                ticket_banner: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                positions: {
                    canvaElLogo: { top: '20px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTitle: { top: '65px', left: '30px', width: '360px', height: 'auto', rotate: 0, visible: true },
                    canvaElZone: { top: '110px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElPrice: { top: '110px', left: '230px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBanner: { top: '20px', left: '320px', width: '260px', height: '120px', rotate: 0, visible: true },
                    canvaElBuyerName: { top: '155px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBuyerDni: { top: '180px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElVenue: { top: '215px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElCity: { top: '240px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDate: { top: '275px', left: '30px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTime: { top: '275px', left: '220px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTicketNumber: { top: '20px', left: '650px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElQR: { top: '60px', left: '625px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElHash: { top: '185px', left: '635px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDisclaimer: { top: '220px', left: '600px', width: '155px', height: 'auto', rotate: 0, visible: true }
                }
            },
            '6': {
                name: 'Plantilla Virtual 3: Entrada Digital Minimal Gold',
                type: 'virtual',
                bg_color: '#18120C',
                strip_color: '#F59E0B',
                bg_image: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1200&q=80',
                ticket_banner: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                positions: {
                    canvaElLogo: { top: '15px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTitle: { top: '60px', left: '25px', width: '380px', height: 'auto', rotate: 0, visible: true },
                    canvaElZone: { top: '105px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElPrice: { top: '105px', left: '240px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBanner: { top: '15px', left: '330px', width: '260px', height: '115px', rotate: 0, visible: true },
                    canvaElBuyerName: { top: '150px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBuyerDni: { top: '175px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElVenue: { top: '210px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElCity: { top: '235px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDate: { top: '270px', left: '25px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTime: { top: '270px', left: '220px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTicketNumber: { top: '15px', left: '650px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElQR: { top: '55px', left: '630px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElHash: { top: '180px', left: '640px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDisclaimer: { top: '215px', left: '605px', width: '150px', height: 'auto', rotate: 0, visible: true }
                }
            },
            '1': {
                name: 'Plantilla 1: Taquilla Clásica Oficial 2026',
                type: 'fisica',
                bg_color: '#FFFFFF',
                strip_color: '#000000',
                bg_image: null,
                ticket_banner: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
                positions: {
                    canvaElLogo: { top: '20px', left: '20px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTitle: { top: '15px', left: '95px', width: '340px', height: 'auto', rotate: 0, visible: true },
                    canvaElZone: { top: '50px', left: '95px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElPrice: { top: '15px', left: '420px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBanner: { top: '80px', left: '95px', width: '390px', height: '125px', rotate: 0, visible: true },
                    canvaElBuyerName: { top: '215px', left: '95px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElBuyerDni: { top: '240px', left: '95px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElVenue: { top: '215px', left: '300px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElCity: { top: '240px', left: '300px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDate: { top: '270px', left: '95px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTime: { top: '270px', left: '220px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElTicketNumber: { top: '15px', left: '550px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElQR: { top: '50px', left: '540px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElHash: { top: '190px', left: '545px', width: 'auto', height: 'auto', rotate: 0, visible: true },
                    canvaElDisclaimer: { top: '220px', left: '515px', width: '170px', height: 'auto', rotate: 0, visible: true }
                }
            }
        };

        function loadPresetTemplate(templateId) {
            const tpl = PRESET_TEMPLATES[templateId] || PRESET_TEMPLATES['4'];
            document.getElementById('selected_template_id').value = templateId;

            // Actualizar colores y fondo
            document.getElementById('canvaBgColor').value = tpl.bg_color;
            document.getElementById('canvaStripColor').value = tpl.strip_color;
            updateCanvaBgColor(tpl.bg_color);
            updateCanvaStripColor(tpl.strip_color);

            const canvas = document.getElementById('canvaTicketCanvas');
            if (tpl.bg_image) {
                document.getElementById('canvaBgImageInput').value = tpl.bg_image;
                canvas.style.backgroundImage = `url('${tpl.bg_image}')`;
                canvas.style.backgroundSize = 'cover';
                canvas.style.backgroundPosition = 'center';

                const filledBox = document.getElementById('bgUploadBoxFilled');
                const emptyBox = document.getElementById('bgUploadBoxEmpty');
                const thumb = document.getElementById('bgThumbPreview');
                const nameText = document.getElementById('bgFileNameText');

                if (filledBox) filledBox.style.display = 'flex';
                if (emptyBox) emptyBox.style.display = 'none';
                if (thumb) thumb.style.backgroundImage = `url('${tpl.bg_image}')`;
                if (nameText) nameText.textContent = tpl.name;
            } else {
                removeCanvaBgImage();
            }

            // Aplicar banner para ticket
            if (tpl.ticket_banner) {
                const bannerInput = document.getElementById('canvaTicketBannerInput');
                if (bannerInput) bannerInput.value = tpl.ticket_banner;

                const canvaBanner = document.getElementById('canvaPrevBannerImg');
                if (canvaBanner) canvaBanner.src = tpl.ticket_banner;

                const thumb = document.getElementById('ticketBannerThumbPreview');
                if (thumb) thumb.style.backgroundImage = `url('${tpl.ticket_banner}')`;
            }

            // Aplicar posiciones a elementos
            if (tpl.positions) {
                Object.keys(tpl.positions).forEach(key => {
                    const pos = tpl.positions[key];
                    const el = document.getElementById(key);

                    if (el && pos) {
                        el.style.top = pos.top || el.style.top;
                        el.style.left = pos.left || el.style.left;
                        if (pos.width && pos.width !== 'auto') el.style.width = pos.width;
                        if (pos.height && pos.height !== 'auto') el.style.height = pos.height;
                        if (pos.rotate !== undefined) el.dataset.rotate = pos.rotate;
                        const deg = parseInt(el.dataset.rotate || '0', 10);
                        el.style.transform = `rotate(${deg}deg)`;
                        el.style.display = (pos.visible === false) ? 'none' : '';
                    }
                });
            }

            // Actualizar resumen en Step 4
            const summaryTicketLabel = document.getElementById('summaryTicketLabel');
            if (summaryTicketLabel) summaryTicketLabel.textContent = tpl.name;

            updateLiveTicketPreview();
        }

        function onCanvaBgColorChange(color) {
            document.getElementById('canvaBgColor').value = color;
            const hexText = document.getElementById('canvaBgColorHexText');
            if (hexText) hexText.textContent = color.toUpperCase();
            const canvas = document.getElementById('canvaTicketCanvas');
            if (canvas) canvas.style.backgroundColor = color;
        }

        function updateCanvaBgColor(color) {
            const hex = color ? (color.startsWith('#') ? color : rgbToHex(color)) : '#FFFFFF';
            const input = document.getElementById('canvaBgColor');
            if (input) input.value = hex;
            const picker = document.getElementById('canvaBgColorPicker');
            if (picker && hex.startsWith('#') && hex.length === 7) picker.value = hex;
            const hexText = document.getElementById('canvaBgColorHexText');
            if (hexText) hexText.textContent = hex.toUpperCase();
            const canvas = document.getElementById('canvaTicketCanvas');
            if (canvas) canvas.style.backgroundColor = hex;
        }

        function updateCanvaStripColor(color) {
            // Actualizar franja si existe
        }

        function removeCanvaBgImage() {
            document.getElementById('canvaBgImageInput').value = '';
            document.getElementById('canvaBgFileInput').value = '';
            const canvas = document.getElementById('canvaTicketCanvas');
            if (canvas) {
                canvas.style.backgroundImage = 'none';
                canvas.style.backgroundColor = document.getElementById('canvaBgColor').value || '#FFFFFF';
            }
            const filledBox = document.getElementById('bgUploadBoxFilled');
            const emptyBox = document.getElementById('bgUploadBoxEmpty');
            if (filledBox) filledBox.style.display = 'none';
            if (emptyBox) emptyBox.style.display = 'block';
        }

        async function handleBgFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const dataUrl = await compressImageFile(file, 1200, 600, 0.85);
            if (!dataUrl) return;

            document.getElementById('canvaBgImageInput').value = dataUrl;

            const canvas = document.getElementById('canvaTicketCanvas');
            if (canvas) {
                canvas.style.backgroundImage = `url('${dataUrl}')`;
                canvas.style.backgroundSize = 'cover';
                canvas.style.backgroundPosition = 'center';
            }

            const filledBox = document.getElementById('bgUploadBoxFilled');
            const emptyBox = document.getElementById('bgUploadBoxEmpty');
            const thumb = document.getElementById('bgThumbPreview');
            const nameText = document.getElementById('bgFileNameText');

            if (filledBox) filledBox.style.display = 'flex';
            if (emptyBox) emptyBox.style.display = 'none';
            if (thumb) thumb.style.backgroundImage = `url('${dataUrl}')`;
            if (nameText) nameText.textContent = file.name;
        }

        async function handleTicketBannerUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const dataUrl = await compressImageFile(file, 1200, 600, 0.85);
            if (!dataUrl) return;
            
            const bannerInput = document.getElementById('canvaTicketBannerInput');
            if (bannerInput) bannerInput.value = dataUrl;

            const canvaBanner = document.getElementById('canvaPrevBannerImg');
            if (canvaBanner) canvaBanner.src = dataUrl;

            const bannerThumb = document.getElementById('ticketBannerThumbPreview');
            if (bannerThumb) bannerThumb.style.backgroundImage = `url('${dataUrl}')`;

            const bannerNameText = document.getElementById('ticketBannerFileNameText');
            if (bannerNameText) bannerNameText.textContent = file.name;
        }

        function removeTicketBannerImage() {
            const defaultBanner = 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80';
            const bannerInput = document.getElementById('canvaTicketBannerInput');
            if (bannerInput) bannerInput.value = defaultBanner;

            const canvaBanner = document.getElementById('canvaPrevBannerImg');
            if (canvaBanner) canvaBanner.src = defaultBanner;

            const bannerThumb = document.getElementById('ticketBannerThumbPreview');
            if (bannerThumb) bannerThumb.style.backgroundImage = `url('${defaultBanner}')`;

            const bannerNameText = document.getElementById('ticketBannerFileNameText');
            if (bannerNameText) bannerNameText.textContent = 'Banner de Boleto';

            const fileInput = document.getElementById('canvaTicketBannerFileInput');
            if (fileInput) fileInput.value = '';
        }

        // AGREGAR 4 MANEJADORES DE ESQUINA A CADA ELEMENTO
        function attachTransformHandles(el) {
            if (el.querySelector('.handle-nw')) return;

            const nw = document.createElement('div'); nw.className = 'canva-resize-handle handle-nw'; nw.dataset.handle = 'nw';
            const ne = document.createElement('div'); ne.className = 'canva-resize-handle handle-ne'; ne.dataset.handle = 'ne';
            const sw = document.createElement('div'); sw.className = 'canva-resize-handle handle-sw'; sw.dataset.handle = 'sw';
            const se = document.createElement('div'); se.className = 'canva-resize-handle handle-se'; se.dataset.handle = 'se';

            el.appendChild(nw);
            el.appendChild(ne);
            el.appendChild(sw);
            el.appendChild(se);
        }

        let savedCanvaRange = null;
        let savedSelectedSpan = null;

        document.addEventListener('selectionchange', function () {
            const sel = window.getSelection();
            if (sel && !sel.isCollapsed && currentCanvaElement) {
                const box = currentCanvaElement.querySelector('.canva-drag-box-container');
                let selNode = sel.anchorNode ? ((sel.anchorNode.nodeType === 3) ? sel.anchorNode.parentElement : sel.anchorNode) : null;
                if (box && selNode && box.contains(selNode)) {
                    savedCanvaRange = sel.getRangeAt(0).cloneRange();
                    let span = selNode ? selNode.closest('span, font') : null;
                    if (span && box.contains(span) && !span.classList.contains('canva-text-content') && !span.classList.contains('canva-drag-box-container')) {
                        savedSelectedSpan = span;
                    } else {
                        savedSelectedSpan = null;
                    }
                }
            }
        });

        document.addEventListener('input', function (e) {
            const box = (e.target && e.target.closest) ? e.target.closest('.canva-drag-box-container') : null;
            if (box) {
                box.dataset.userEdited = 'true';
                const el = box.closest('.canva-drag-element');
                if (el) el.dataset.userEdited = 'true';
            }
        });

        function selectCanvaElement(el) {
            document.querySelectorAll('.canva-drag-element').forEach(item => {
                if (item !== el) {
                    item.classList.remove('selected-element');
                    const box = item.querySelector('.canva-drag-box-container');
                    const textEl = item.querySelector('.canva-text-content');
                    if (box) box.removeAttribute('contenteditable');
                    if (textEl) textEl.removeAttribute('contenteditable');
                }
            });

            if (currentCanvaElement !== el) {
                savedSelectedSpan = null;
                savedCanvaRange = null;
            }

            currentCanvaElement = el;
            if (el) {
                el.classList.add('selected-element');
                attachTransformHandles(el);
                syncFloatingToolbarControls(el);
            }
        }

        function rgbToHex(color) {
            if (!color) return '#000000';
            if (color.startsWith('#')) {
                return (color.length === 4) ? '#' + color[1] + color[1] + color[2] + color[2] + color[3] + color[3] : color;
            }
            const rgb = color.match(/\d+/g);
            if (rgb && rgb.length >= 3) {
                return "#" + ((1 << 24) + (parseInt(rgb[0]) << 16) + (parseInt(rgb[1]) << 8) + parseInt(rgb[2])).toString(16).slice(1);
            }
            return '#000000';
        }

        function syncFloatingToolbarControls(el) {
            if (!el) return;
            const box = el.querySelector('.canva-drag-box-container');
            const textEl = el.querySelector('.canva-text-content');
            const target = textEl || box || el;

            const selection = window.getSelection();
            let activeEl = target;
            if (selection && !selection.isCollapsed && box && box.contains(selection.anchorNode)) {
                let node = (selection.anchorNode.nodeType === 3) ? selection.anchorNode.parentElement : selection.anchorNode;
                activeEl = node.closest('span, font') || node;
            } else if (savedSelectedSpan && box && box.contains(savedSelectedSpan)) {
                activeEl = savedSelectedSpan;
            } else if (box) {
                const inner = box.querySelector('span[style*="font-size"], font[style*="font-size"]');
                if (inner) activeEl = inner;
            }

            const size = window.getComputedStyle(activeEl).fontSize || '12px';
            const sizeInt = parseInt(size, 10) || 12;
            const sizeEl = document.getElementById('floatingFontSizeText');
            if (sizeEl) sizeEl.textContent = sizeInt + 'px';

            const family = target.style.fontFamily || window.getComputedStyle(target).fontFamily || 'inherit';
            const fontSelect = document.getElementById('floatingFontFamilySelect');
            if (fontSelect) {
                let found = false;
                const cleanFamily = family.replace(/['"]/g, '').toLowerCase();
                for (let i = 0; i < fontSelect.options.length; i++) {
                    const optVal = fontSelect.options[i].value.replace(/['"]/g, '').toLowerCase();
                    const optName = fontSelect.options[i].text.toLowerCase().split(' ')[0];
                    if (cleanFamily.includes(optName) || cleanFamily.includes(optVal.split(',')[0].trim())) {
                        fontSelect.selectedIndex = i;
                        found = true;
                        break;
                    }
                }
                if (!found) fontSelect.value = 'inherit';
            }

            const rawColor = target.style.color || window.getComputedStyle(target).color || '#000000';
            const colorPicker = document.getElementById('floatingColorPicker');
            if (colorPicker) {
                colorPicker.value = rgbToHex(rawColor);
            }

            const btnBold = document.getElementById('btnFloatingBold');
            const isBold = window.getComputedStyle(target).fontWeight >= 700 || window.getComputedStyle(target).fontWeight === 'bold';
            if (btnBold) btnBold.classList.toggle('active', isBold);

            const btnItalic = document.getElementById('btnFloatingItalic');
            const isItalic = window.getComputedStyle(target).fontStyle === 'italic';
            if (btnItalic) btnItalic.classList.toggle('active', isItalic);

            const btnUnderline = document.getElementById('btnFloatingUnderline');
            const isUnderline = window.getComputedStyle(target).textDecorationLine.includes('underline');
            if (btnUnderline) btnUnderline.classList.toggle('active', isUnderline);

            const btnBadge = document.getElementById('btnFloatingBadgeBg');
            if (btnBadge && box) {
                btnBadge.classList.toggle('active', box.classList.contains('has-badge-bg'));
            }
        }

        function applyFloatingFormat(cmd, value = null) {
            if (!currentCanvaElement) return;

            const box = currentCanvaElement.querySelector('.canva-drag-box-container');
            const textEl = currentCanvaElement.querySelector('.canva-text-content');
            const target = textEl || box || currentCanvaElement;
            const selection = window.getSelection();

            let anchorNodeEl = (selection && selection.anchorNode) ? ((selection.anchorNode.nodeType === 3) ? selection.anchorNode.parentElement : selection.anchorNode) : null;
            const isDomSelectionActive = selection && !selection.isCollapsed && box && anchorNodeEl && box.contains(anchorNodeEl);
            if (isDomSelectionActive) {
                let activeSpan = anchorNodeEl ? anchorNodeEl.closest('span, font') : null;
                if (activeSpan && (activeSpan.classList.contains('canva-text-content') || activeSpan.classList.contains('canva-drag-box-container'))) {
                    activeSpan = null;
                }
                savedSelectedSpan = activeSpan;
                if (selection.rangeCount > 0) {
                    savedCanvaRange = selection.getRangeAt(0).cloneRange();
                }
            }

            let rangeEl = (savedCanvaRange && savedCanvaRange.commonAncestorContainer) ? ((savedCanvaRange.commonAncestorContainer.nodeType === 3) ? savedCanvaRange.commonAncestorContainer.parentElement : savedCanvaRange.commonAncestorContainer) : null;
            const isSavedRangeValid = rangeEl && box && box.contains(rangeEl);

            const hasTextSelection = isDomSelectionActive || (savedSelectedSpan && box && box.contains(savedSelectedSpan)) || (savedCanvaRange && !savedCanvaRange.collapsed && isSavedRangeValid);

            if (box) {
                box.dataset.userEdited = 'true';
                currentCanvaElement.dataset.userEdited = 'true';
            }

            if (hasTextSelection) {
                if (cmd === 'fontSize') {
                    if (savedSelectedSpan && box && box.contains(savedSelectedSpan)) {
                        const curSize = parseInt(window.getComputedStyle(savedSelectedSpan).fontSize || '12', 10);
                        const newSize = (value === 'inc') ? Math.min(curSize + 1, 96) : Math.max(curSize - 1, 8);
                        savedSelectedSpan.style.fontSize = newSize + 'px';
                        if (savedSelectedSpan.tagName === 'FONT') savedSelectedSpan.removeAttribute('size');
                        
                        console.log('[CanvaStudio] Scaled active text span to:', newSize + 'px');
                        const sizeEl = document.getElementById('floatingFontSizeText');
                        if (sizeEl) sizeEl.textContent = newSize + 'px';
                        syncFloatingToolbarControls(currentCanvaElement);
                        return;
                    }

                    const curSize = parseInt(window.getComputedStyle(target).fontSize || '12', 10);
                    const newSize = (value === 'inc') ? Math.min(curSize + 1, 96) : Math.max(curSize - 1, 8);
                    
                    try {
                        const range = (selection && !selection.isCollapsed) ? selection.getRangeAt(0) : savedCanvaRange;
                        if (range) {
                            const span = document.createElement('span');
                            span.style.fontSize = newSize + 'px';
                            range.surroundContents(span);
                            savedSelectedSpan = span;
                            console.log('[CanvaStudio] Wrapped new text selection in span:', newSize + 'px');
                        }
                    } catch (e) {
                        if (selection && selection.toString()) {
                            document.execCommand('insertHTML', false, `<span style="font-size: ${newSize}px;">${selection.toString()}</span>`);
                            const newSpans = box.querySelectorAll('span[style*="font-size"]');
                            if (newSpans.length > 0) savedSelectedSpan = newSpans[newSpans.length - 1];
                        }
                    }

                    const sizeEl = document.getElementById('floatingFontSizeText');
                    if (sizeEl) sizeEl.textContent = newSize + 'px';
                    syncFloatingToolbarControls(currentCanvaElement);
                    return;
                }

                document.execCommand('styleWithCSS', false, true);

                if (cmd === 'bold') {
                    document.execCommand('bold', false, null);
                } else if (cmd === 'italic') {
                    document.execCommand('italic', false, null);
                } else if (cmd === 'underline') {
                    document.execCommand('underline', false, null);
                } else if (cmd === 'color') {
                    if (savedSelectedSpan && box && box.contains(savedSelectedSpan)) {
                        savedSelectedSpan.style.color = value;
                    } else if (savedCanvaRange && !savedCanvaRange.collapsed && box && box.contains(savedCanvaRange.commonAncestorContainer)) {
                        try {
                            const span = document.createElement('span');
                            span.style.color = value;
                            savedCanvaRange.surroundContents(span);
                            savedSelectedSpan = span;
                            console.log('[CanvaStudio] Wrapped range in color span:', value);
                        } catch (e) {
                            document.execCommand('foreColor', false, value);
                        }
                    } else {
                        document.execCommand('foreColor', false, value);
                    }
                } else if (cmd === 'fontFamily') {
                    if (savedSelectedSpan && box && box.contains(savedSelectedSpan)) {
                        savedSelectedSpan.style.fontFamily = value;
                    } else {
                        document.execCommand('fontName', false, value.replace(/['"]/g, ''));
                    }
                } else if (cmd === 'align') {
                    document.execCommand('justify' + (value.charAt(0).toUpperCase() + value.slice(1)), false, null);
                }

                syncFloatingToolbarControls(currentCanvaElement);
                return;
            }

            // Aplicar a todo el elemento contenedor (cuando NO hay texto individual seleccionado)
            if (cmd === 'fontSize') {
                console.log('[CanvaStudio] Full Container FontSize Scaling:', {
                    action: value,
                    elementId: currentCanvaElement.id
                });
                const childStyled = box ? box.querySelectorAll('span, font, div, p, h1, h2, h3, h4, b, strong, i, u') : [];
                if (childStyled && childStyled.length > 0) {
                    let lastSize = 12;
                    childStyled.forEach(c => {
                        const cur = parseInt(window.getComputedStyle(c).fontSize || '12', 10);
                        const nxt = (value === 'inc') ? Math.min(cur + 1, 96) : Math.max(cur - 1, 8);
                        c.style.fontSize = nxt + 'px';
                        if (c.tagName === 'FONT') c.removeAttribute('size');
                        console.log('[CanvaStudio] Scaled child node:', c.textContent, 'from', cur + 'px', 'to', nxt + 'px');
                        lastSize = nxt;
                    });
                    target.style.fontSize = lastSize + 'px';
                    if (box) box.style.fontSize = lastSize + 'px';
                    const sizeEl = document.getElementById('floatingFontSizeText');
                    if (sizeEl) sizeEl.textContent = lastSize + 'px';
                } else {
                    const curSize = parseInt(window.getComputedStyle(target).fontSize || '12', 10);
                    const newSize = (value === 'inc') ? Math.min(curSize + 1, 96) : Math.max(curSize - 1, 8);
                    target.style.fontSize = newSize + 'px';
                    if (box) box.style.fontSize = newSize + 'px';
                    console.log('[CanvaStudio] Scaled single target:', target.textContent, 'from', curSize + 'px', 'to', newSize + 'px');
                    const sizeEl = document.getElementById('floatingFontSizeText');
                    if (sizeEl) sizeEl.textContent = newSize + 'px';
                }
            } else if (cmd === 'fontFamily') {
                target.style.fontFamily = value;
                if (box) box.style.fontFamily = value;
                currentCanvaElement.style.fontFamily = value;
                currentCanvaElement.querySelectorAll('*').forEach(c => {
                    if (!c.classList.contains('canva-resize-handle')) {
                        c.style.fontFamily = value;
                        if (c.tagName === 'FONT') c.removeAttribute('face');
                    }
                });
            } else if (cmd === 'color') {
                target.style.color = value;
                if (box) box.style.color = value;
                currentCanvaElement.style.color = value;
                currentCanvaElement.querySelectorAll('*').forEach(c => {
                    if (!c.classList.contains('canva-resize-handle')) {
                        c.style.color = value;
                        if (c.tagName === 'FONT') c.removeAttribute('color');
                    }
                });
            } else if (cmd === 'bold') {
                const cur = window.getComputedStyle(target).fontWeight;
                const next = (cur >= 700 || cur === 'bold') ? '400' : '900';
                target.style.fontWeight = next;
                if (box) box.style.fontWeight = next;
                currentCanvaElement.querySelectorAll('*').forEach(c => {
                    if (!c.classList.contains('canva-resize-handle')) c.style.fontWeight = next;
                });
            } else if (cmd === 'italic') {
                const cur = window.getComputedStyle(target).fontStyle;
                const next = (cur === 'italic') ? 'normal' : 'italic';
                target.style.fontStyle = next;
                if (box) box.style.fontStyle = next;
                currentCanvaElement.querySelectorAll('*').forEach(c => {
                    if (!c.classList.contains('canva-resize-handle')) c.style.fontStyle = next;
                });
            } else if (cmd === 'underline') {
                const cur = window.getComputedStyle(target).textDecorationLine;
                const next = cur.includes('underline') ? 'none' : 'underline';
                target.style.textDecoration = next;
                if (box) box.style.textDecoration = next;
                currentCanvaElement.querySelectorAll('*').forEach(c => {
                    if (!c.classList.contains('canva-resize-handle')) c.style.textDecoration = next;
                });
            } else if (cmd === 'align') {
                target.style.textAlign = value;
                if (box) box.style.textAlign = value;
                currentCanvaElement.style.textAlign = value;
            } else if (cmd === 'textTransform') {
                const cur = window.getComputedStyle(target).textTransform;
                const next = (cur === 'uppercase') ? 'none' : 'uppercase';
                target.style.textTransform = next;
                if (box) box.style.textTransform = next;
                currentCanvaElement.querySelectorAll('*').forEach(c => {
                    if (!c.classList.contains('canva-resize-handle')) c.style.textTransform = next;
                });
            } else if (cmd === 'badgeBg') {
                if (box) box.classList.toggle('has-badge-bg');
            } else if (cmd === 'rotate') {
                const cur = parseInt(currentCanvaElement.dataset.rotate || '0', 10);
                const next = (cur + 90) % 360;
                currentCanvaElement.dataset.rotate = next;
                currentCanvaElement.style.transform = `rotate(${next}deg)`;
            }

            syncFloatingToolbarControls(currentCanvaElement);
        }

        function deleteSelectedCanvaElement() {
            if (!currentCanvaElement) return;
            const elId = currentCanvaElement.id;
            const standardIds = ['canvaElLogo', 'canvaElTitle', 'canvaElZone', 'canvaElPrice', 'canvaElBanner', 'canvaElBuyerName', 'canvaElBuyerDni', 'canvaElVenue', 'canvaElCity', 'canvaElDate', 'canvaElTime', 'canvaElTicketNumber', 'canvaElQR', 'canvaElHash', 'canvaElDisclaimer'];

            currentCanvaElement.style.display = 'none';
            const unselectEl = currentCanvaElement;
            currentCanvaElement = null;
            unselectEl.classList.remove('selected-element');

            // Actualizar botones de sistema si aplica
            if (elId && standardIds.includes(elId)) {
                const sysKey = elId.replace('canvaEl', '').toLowerCase();
                const btn = document.getElementById('sysBtn_' + sysKey);
                if (btn) {
                    const badge = btn.querySelector('.field-status-badge');
                    if (badge) {
                        badge.textContent = '+ Añadir';
                        badge.style.color = '#06B6D4';
                    }
                }
            }
        }

        function createNewCustomTag() {
            const input = document.getElementById('newCustomTagInput');
            const text = input.value.trim();
            if (!text) return;

            const canvas = document.getElementById('canvaCanvasArea');
            if (!canvas) return;

            const tagId = 'canvaCustomTag_' + Date.now();
            const tagEl = document.createElement('div');
            tagEl.className = 'canva-drag-element';
            tagEl.id = tagId;
            tagEl.style.top = '120px';
            tagEl.style.left = '100px';

            const box = document.createElement('div');
            box.className = 'canva-drag-box-container';
            box.style.fontSize = '12px';
            box.style.color = '#000000';
            box.innerHTML = `<div class="canva-text-content" style="font-size: 12px; font-weight: 800; color: #000000; outline: none; white-space: pre-wrap; line-height: 1.25; word-break: break-word;">${text}</div>`;

            tagEl.appendChild(box);
            canvas.appendChild(tagEl);

            attachTransformHandles(tagEl);
            selectCanvaElement(tagEl);
            input.value = '';
        }

        function toggleSystemElement(key) {
            const elMap = {
                'logo': 'canvaElLogo',
                'titulo': 'canvaElTitle',
                'zona': 'canvaElZone',
                'precio': 'canvaElPrice',
                'banner': 'canvaElBanner',
                'comprador_nombre': 'canvaElBuyerName',
                'buyer_name': 'canvaElBuyerName',
                'comprador_dni': 'canvaElBuyerDni',
                'buyer_dni': 'canvaElBuyerDni',
                'recinto': 'canvaElVenue',
                'ciudad': 'canvaElCity',
                'fecha': 'canvaElDate',
                'hora': 'canvaElTime',
                'ticket_number': 'canvaElTicketNumber',
                'qr': 'canvaElQR',
                'hash': 'canvaElHash',
                'disclaimer': 'canvaElDisclaimer'
            };

            const elId = elMap[key];
            const el = document.getElementById(elId);
            const btn = document.getElementById('sysBtn_' + key);
            const badge = btn?.querySelector('.field-status-badge');

            if (el) {
                if (el.style.display === 'none') {
                    el.style.display = '';
                    if (badge) {
                        badge.textContent = '✓ Visible';
                        badge.style.color = '#10B981';
                    }
                    selectCanvaElement(el);
                } else {
                    el.style.display = 'none';
                    if (badge) {
                        badge.textContent = '+ Añadir';
                        badge.style.color = '#06B6D4';
                    }
                    if (currentCanvaElement === el) {
                        currentCanvaElement = null;
                        el.classList.remove('selected-element');
                    }
                }
            }
        }

        // DRAG & RESIZE WORKFLOW
        document.addEventListener('mousedown', function (e) {
            const handle = e.target.closest('.canva-resize-handle');
            if (handle) {
                isResizingCanva = true;
                currentResizeHandle = handle.dataset.handle;
                const parentEl = handle.closest('.canva-drag-element');
                selectCanvaElement(parentEl);

                startX = e.clientX;
                startY = e.clientY;
                startLeft = parentEl.offsetLeft;
                startTop = parentEl.offsetTop;
                startWidth = parentEl.offsetWidth;
                startHeight = parentEl.offsetHeight;

                e.preventDefault();
                e.stopPropagation();
                return;
            }

            const dragEl = e.target.closest('.canva-drag-element');
            if (dragEl) {
                const box = dragEl.querySelector('.canva-drag-box-container');
                const textEl = dragEl.querySelector('.canva-text-content');
                if ((box && box.getAttribute('contenteditable') === 'true') || (textEl && textEl.getAttribute('contenteditable') === 'true')) {
                    return; // Permite mover el cursor de texto libremente
                }

                selectCanvaElement(dragEl);
                isDraggingCanva = true;

                startX = e.clientX;
                startY = e.clientY;
                startLeft = dragEl.offsetLeft;
                startTop = dragEl.offsetTop;

                e.preventDefault();
            } else if (!e.target.closest('#canvaStudioTopToolbar') && !e.target.closest('.settings-card-box')) {
                selectCanvaElement(null);
            }
        });

        // DOBLE CLICK PARA EDITAR TEXTO INTERNO CON SOPORTE MULTILÍNEA Y TABULACIÓN
        document.addEventListener('dblclick', function (e) {
            const dragEl = e.target.closest('.canva-drag-element');
            if (dragEl) {
                if (dragEl.id === 'canvaElLogo' || dragEl.id === 'canvaElBanner' || dragEl.id === 'canvaElQR') {
                    return;
                }
                const textEl = dragEl.querySelector('.canva-text-content') || dragEl.querySelector('.canva-drag-box-container');
                if (textEl) {
                    textEl.setAttribute('contenteditable', 'true');
                    textEl.focus();
                    
                    const range = document.createRange();
                    range.selectNodeContents(textEl);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }
        });

        // SOPORTE PARA TABULACIÓN Y SALTO DE LÍNEA EN MODO EDICIÓN DE TEXTO
        document.addEventListener('keydown', function (e) {
            const activeEditable = document.querySelector('[contenteditable="true"]');
            if (activeEditable && (activeEditable === e.target || activeEditable.contains(e.target))) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    document.execCommand('insertText', false, '    ');
                } else if (e.key === 'Escape') {
                    activeEditable.removeAttribute('contenteditable');
                    activeEditable.blur();
                }
            }
        });

        document.addEventListener('focusout', function (e) {
            if (e.target && e.target.hasAttribute && e.target.hasAttribute('contenteditable')) {
                if (e.relatedTarget && (e.relatedTarget.closest('#canvaStudioTopToolbar') || e.relatedTarget.closest('.canva-drag-element'))) {
                    return;
                }
                const active = document.activeElement;
                if (active && (active.closest('#canvaStudioTopToolbar') || active.closest('.canva-drag-element'))) {
                    return;
                }
                if (!e.relatedTarget || (!e.relatedTarget.closest('#canvaTicketCanvas') && !e.relatedTarget.closest('#canvaStudioTopToolbar'))) {
                    e.target.removeAttribute('contenteditable');
                }
            }
        });

        document.addEventListener('mousemove', function (e) {
            if (isResizingCanva && currentCanvaElement) {
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;
                const isQR = (currentCanvaElement.id === 'canvaElQR');

                if (isQR) {
                    // Mantener estricta proporción cuadrada 1:1 para el QR
                    if (currentResizeHandle === 'se') {
                        const side = Math.max(startWidth + Math.max(dx, dy), 40);
                        currentCanvaElement.style.width = side + 'px';
                        currentCanvaElement.style.height = side + 'px';
                    } else if (currentResizeHandle === 'sw') {
                        const side = Math.max(startWidth + Math.max(-dx, dy), 40);
                        const newLeft = startLeft + (startWidth - side);
                        currentCanvaElement.style.width = side + 'px';
                        currentCanvaElement.style.height = side + 'px';
                        currentCanvaElement.style.left = newLeft + 'px';
                    } else if (currentResizeHandle === 'ne') {
                        const side = Math.max(startWidth + Math.max(dx, -dy), 40);
                        const newTop = startTop + (startHeight - side);
                        currentCanvaElement.style.width = side + 'px';
                        currentCanvaElement.style.height = side + 'px';
                        currentCanvaElement.style.top = newTop + 'px';
                    } else if (currentResizeHandle === 'nw') {
                        const side = Math.max(startWidth + Math.max(-dx, -dy), 40);
                        const newLeft = startLeft + (startWidth - side);
                        const newTop = startTop + (startHeight - side);
                        currentCanvaElement.style.width = side + 'px';
                        currentCanvaElement.style.height = side + 'px';
                        currentCanvaElement.style.left = newLeft + 'px';
                        currentCanvaElement.style.top = newTop + 'px';
                    }
                } else {
                    // Redimensionamiento 2D libre en cualquier dirección (ancho y alto) para banner y demás
                    if (currentResizeHandle === 'se') {
                        currentCanvaElement.style.width = Math.max(startWidth + dx, 30) + 'px';
                        currentCanvaElement.style.height = Math.max(startHeight + dy, 20) + 'px';
                    } else if (currentResizeHandle === 'sw') {
                        const newW = Math.max(startWidth - dx, 30);
                        currentCanvaElement.style.width = newW + 'px';
                        currentCanvaElement.style.left = (startLeft + (startWidth - newW)) + 'px';
                        currentCanvaElement.style.height = Math.max(startHeight + dy, 20) + 'px';
                    } else if (currentResizeHandle === 'ne') {
                        const newW = Math.max(startWidth + dx, 30);
                        const newH = Math.max(startHeight - dy, 20);
                        currentCanvaElement.style.width = newW + 'px';
                        currentCanvaElement.style.height = newH + 'px';
                        currentCanvaElement.style.top = (startTop + (startHeight - newH)) + 'px';
                    } else if (currentResizeHandle === 'nw') {
                        const newW = Math.max(startWidth - dx, 30);
                        const newH = Math.max(startHeight - dy, 20);
                        currentCanvaElement.style.width = newW + 'px';
                        currentCanvaElement.style.height = newH + 'px';
                        currentCanvaElement.style.left = (startLeft + (startWidth - newW)) + 'px';
                        currentCanvaElement.style.top = (startTop + (startHeight - newH)) + 'px';
                    }
                }
            } else if (isDraggingCanva && currentCanvaElement) {
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;

                const newLeft = Math.max(0, Math.min(startLeft + dx, 771 - currentCanvaElement.offsetWidth));
                const newTop = Math.max(0, Math.min(startTop + dy, 370 - currentCanvaElement.offsetHeight));

                currentCanvaElement.style.left = newLeft + 'px';
                currentCanvaElement.style.top = newTop + 'px';
            }
        });

        document.addEventListener('mouseup', function () {
            isDraggingCanva = false;
            isResizingCanva = false;
            currentResizeHandle = null;
        });

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
            const zoneName = firstZoneRow?.querySelector('.zone-name-input')?.value || 'BOX PLATINUM INDIVIDUAL';
            const zonePrice = parseFloat(firstZoneRow?.querySelector('.zone-price-input')?.value) || 150.00;

            const banner = document.getElementById('event_banner')?.value || 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80';

            const previewImg = document.getElementById('bannerPreviewImg');
            if (previewImg && banner) previewImg.src = banner;

            const titleEl = document.querySelector('#canvaElTitle .canva-text-content') || document.querySelector('#canvaElTitle h2');
            if (titleEl && !titleEl.closest('.canva-drag-element')?.dataset.userEdited && titleEl.querySelectorAll('span, font').length === 0) {
                titleEl.textContent = title;
            }

            const zoneEl = document.querySelector('#canvaElZone .canva-text-content') || document.querySelector('#canvaElZone span');
            if (zoneEl && !zoneEl.closest('.canva-drag-element')?.dataset.userEdited && zoneEl.querySelectorAll('span, font').length === 0) {
                zoneEl.textContent = `ZONA: ${zoneName}`;
            }

            const priceEl = document.querySelector('#canvaElPrice .canva-text-content') || document.querySelector('#canvaElPrice span');
            if (priceEl && !priceEl.closest('.canva-drag-element')?.dataset.userEdited && priceEl.querySelectorAll('span, font').length === 0) {
                priceEl.textContent = `PRECIO: S/ ${zonePrice.toFixed(2)}`;
            }

            const venueText = document.getElementById('canvaPrevVenueText');
            if (venueText && !venueText.closest('.canva-drag-element')?.dataset.userEdited && venueText.querySelectorAll('span, font').length === 0) {
                venueText.textContent = venue;
            }

            const addressText = document.getElementById('canvaPrevAddressText');
            if (addressText && !addressText.closest('.canva-drag-element')?.dataset.userEdited && addressText.querySelectorAll('span, font').length === 0) {
                addressText.textContent = address;
            }

            const dateText = document.getElementById('canvaPrevDateText');
            if (dateText && !dateText.closest('.canva-drag-element')?.dataset.userEdited && dateText.querySelectorAll('span, font').length === 0) {
                dateText.textContent = `FECHA: ${date}`;
            }

            const timeText = document.getElementById('canvaPrevTimeText');
            if (timeText && !timeText.closest('.canva-drag-element')?.dataset.userEdited && timeText.querySelectorAll('span, font').length === 0) {
                timeText.textContent = `HORA: ${time}`;
            }

            if (document.getElementById('summaryTitle')) document.getElementById('summaryTitle').textContent = title;
            if (document.getElementById('summaryCompany')) document.getElementById('summaryCompany').textContent = company;
            if (document.getElementById('summaryVenue')) document.getElementById('summaryVenue').textContent = `${venue} (${address})`;
            if (document.getElementById('summaryDateTime')) document.getElementById('summaryDateTime').textContent = `${date} a las ${time}`;

            const salesTypeVal = document.querySelector('input[name="event_sales_type"]:checked')?.value || 'virtual';
            const summarySalesType = document.getElementById('summarySalesType');
            if (summarySalesType) {
                summarySalesType.textContent = (salesTypeVal === 'virtual') ? '🌐 Venta Virtual (E-Ticket)' : '🎟️ Venta Física (Taquilla Impresa)';
                summarySalesType.style.color = (salesTypeVal === 'virtual') ? '#06B6D4' : '#FF5500';
            }

            recalculateTotalCapacity();
        }

        function getCanvaPositionsPayload() {
            const positions = {};
            const standardIds = [
                'canvaElLogo', 'canvaElTitle', 'canvaElZone', 'canvaElPrice',
                'canvaElBanner', 'canvaElBuyerName', 'canvaElBuyerDni', 'canvaElVenue',
                'canvaElCity', 'canvaElDate', 'canvaElTime', 'canvaElTicketNumber',
                'canvaElQR', 'canvaElHash', 'canvaElDisclaimer'
            ];

            document.querySelectorAll('#canvaCanvasArea .canva-drag-element').forEach(el => {
                const id = el.id || ('canvaCustomTag_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5));
                const box = el.querySelector('.canva-drag-box-container');
                const textEl = el.querySelector('.canva-text-content');
                const target = textEl || box || el;
                
                const topVal = el.style.top ? el.style.top : (el.offsetTop + 'px');
                const leftVal = el.style.left ? el.style.left : (el.offsetLeft + 'px');
                const widthVal = (el.style.width && el.style.width !== 'auto') ? el.style.width : (el.offsetWidth ? (el.offsetWidth + 'px') : 'auto');
                const heightVal = (el.style.height && el.style.height !== 'auto') ? el.style.height : (el.offsetHeight ? (el.offsetHeight + 'px') : 'auto');
                
                const img = el.querySelector('img');

                // Evitar duplicar base64 pesados en el campo HTML para prevenir sobrecarga en POST
                let cleanHtml = box ? box.innerHTML : el.innerHTML;
                if (id === 'canvaElBanner' || id === 'canvaElLogo' || id === 'canvaElQR') {
                    cleanHtml = null;
                } else if (cleanHtml) {
                    // Limpiar iconos SVG de etiquetas para asegurar diseño sin iconos
                    cleanHtml = cleanHtml.replace(/<svg\b[^>]*class="[^"]*canva-tag-icon[^"]*"[\s\S]*?<\/svg>/gi, '').trim();
                }

                const computed = window.getComputedStyle(target);
                const isCustom = id.startsWith('canvaCustomTag_') || !standardIds.includes(id);

                positions[id] = {
                    top: topVal,
                    left: leftVal,
                    width: widthVal,
                    height: heightVal,
                    rotate: parseInt(el.dataset.rotate || '0', 10),
                    html: cleanHtml,
                    text: textEl ? textEl.innerText.trim() : (box ? box.innerText.trim() : ''),
                    fontSize: target.style.fontSize || box?.style.fontSize || computed.fontSize || '12px',
                    fontFamily: target.style.fontFamily || box?.style.fontFamily || computed.fontFamily || 'inherit',
                    color: target.style.color || box?.style.color || computed.color || '#000000',
                    fontWeight: target.style.fontWeight || box?.style.fontWeight || computed.fontWeight || '700',
                    fontStyle: target.style.fontStyle || box?.style.fontStyle || computed.fontStyle || 'normal',
                    textDecoration: target.style.textDecoration || box?.style.textDecoration || computed.textDecorationLine || 'none',
                    textAlign: target.style.textAlign || box?.style.textAlign || el.style.textAlign || computed.textAlign || 'left',
                    textTransform: target.style.textTransform || box?.style.textTransform || computed.textTransform || 'none',
                    hasBadgeBg: box ? box.classList.contains('has-badge-bg') : false,
                    backgroundColor: box ? box.style.backgroundColor : '',
                    visible: (el.style.display !== 'none') && (window.getComputedStyle(el).display !== 'none'),
                    isCustomTag: isCustom,
                    src: (id === 'canvaElBanner') ? (document.getElementById('canvaTicketBannerInput')?.value || (img ? img.src : null)) : (img ? img.src : null)
                };
            });
            return positions;
        }

        function cleanImageField(str) {
            if (!str || typeof str !== 'string') return str;
            if (str.startsWith('data:')) return str;
            if (str.startsWith('http://') || str.startsWith('https://')) return str;

            let clean = str.replace(/^\//, '');
            if (clean.includes('storage/')) {
                clean = 'storage/' + clean.split('storage/').pop();
            } else if (clean.includes('images/')) {
                clean = 'images/' + clean.split('images/').pop();
            } else if (clean.startsWith('events/') || clean.startsWith('templates/')) {
                clean = 'storage/' + clean;
            }

            return window.location.origin + '/' + clean;
        }

        function finishPublishEvent() {
            const title = document.getElementById('event_title').value;
            const categoryName = document.getElementById('event_category').value;
            const companyName = document.getElementById('event_company').value;
            const bannerImage = cleanImageField(document.getElementById('event_banner').value);
            const eventDate = document.getElementById('event_date_picker').value;
            const eventTime = document.getElementById('event_time_picker').value;
            const venueName = document.getElementById('event_venue').value;
            const address = document.getElementById('event_address').value;
            const details = document.getElementById('event_details').value;
            const templateId = document.getElementById('selected_template_id').value;
            const salesType = document.querySelector('input[name="event_sales_type"]:checked')?.value || 'virtual';

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

            const positionsPayload = getCanvaPositionsPayload();
            if (positionsPayload) {
                Object.keys(positionsPayload).forEach(k => {
                    if (positionsPayload[k] && positionsPayload[k].src) {
                        positionsPayload[k].src = cleanImageField(positionsPayload[k].src);
                    }
                });
            }

            const customTicketPayload = {
                positions: positionsPayload,
                bg_color: document.getElementById('canvaBgColor').value || '#0F172A',
                bg_image: cleanImageField(document.getElementById('canvaBgImageInput').value || null),
                ticket_banner: cleanImageField(document.getElementById('canvaTicketBannerInput')?.value || null),
                strip_color: document.getElementById('canvaStripColor').value || '#FF5500',
                type: salesType
            };

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
                sales_type: salesType,
                custom_ticket: customTicketPayload
            };

            Swal.fire({
                title: 'Guardando Evento...',
                text: 'Publicando espectáculo y guardando diseño de boleto en MySQL',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

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
                        text: `El espectáculo "${title}" y su boleto personalizado se han guardado exitosamente.`,
                        icon: 'success',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then(() => {
                        window.location.href = "{{ route('web.events') }}";
                    });
                } else {
                    Swal.fire({ title: 'Error', text: data.message || 'No se pudo guardar el evento.', icon: 'error', background: '#14141E', color: '#FFF' });
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

            updateLiveTicketPreview();
        }

        document.addEventListener('DOMContentLoaded', function () {
            initLeafletMap();
            recalculateTotalCapacity();
            
            // Adjuntar manejadores de transformación
            document.querySelectorAll('#canvaCanvasArea .canva-drag-element').forEach(el => {
                attachTransformHandles(el);
            });

            // Cargar Plantilla Virtual 1 por defecto
            loadPresetTemplate('4');

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
