@extends('layouts.app')

@section('title', 'Crear Nuevo Evento | Vive Go')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Anton&family=Bebas+Neue&family=Caveat:wght@600;700&family=Cinzel:wght@600;800&family=Comfortaa:wght@600;700&family=Dancing+Script:wght@600;700&family=Fira+Sans:ital,wght@0,400;0,700;1,400&family=Great+Vibes&family=Inter:wght@400;600;800;900&family=Lato:wght@400;700;900&family=Lobster&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Monoton&family=Montserrat:wght@400;700;900&family=Nunito:wght@400;700;900&family=Open+Sans:wght@400;700&family=Oswald:wght@500;700&family=Outfit:wght@400;700;900&family=Pacifico&family=Permanent+Marker&family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Poppins:wght@400;700;900&family=Raleway:wght@400;700;900&family=Righteous&family=Roboto:wght@400;700;900&family=Rubik:wght@400;700;900&family=Satisfy&family=Space+Grotesk:wght@500;700&family=Syne:wght@700;800&family=Work+Sans:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <style>
        #interactiveLeafletMap .leaflet-popup-content-wrapper {
            background: #14141E;
            color: #FFFFFF;
            border-radius: 12px;
        }
        #interactiveLeafletMap .leaflet-popup-tip {
            background: #14141E;
        }

        /* ESTILOS DEL DISEÑADOR ESTILO ELEMENTOR / CERTIFICADOS */
        #cert-canvas {
            width: 771px;
            height: 370px;
            background-color: #FFFFFF;
            position: relative;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            margin: 0 auto;
            flex-shrink: 0;
            line-height: 1.15 !important;
            text-align: left;
            color: #000000;
            font-size: 12px; /* Tamaño base predeterminado de 12px */
            transform-origin: top center;
            transition: transform 0.2s ease-out, box-shadow 0.2s;
            border-radius: 22px; /* Borde redondeado más elegante del boleto */
        }

        #cert-canvas * {
            box-sizing: border-box !important;
        }

        .cert-element {
            position: absolute;
            user-select: none;
            cursor: grab;
            z-index: 10;
            border: 1px dashed rgba(255, 85, 0, 0.3) !important;
            min-width: 30px; 
            min-height: 20px;
            padding: 2px 4px;
            box-sizing: border-box !important;
        }

        .cert-element * {
            pointer-events: none !important;
        }

        .cert-element .resize-handle {
            pointer-events: auto !important;
        }

        .cert-element p, .cert-element div:not(.resize-handle), .cert-element span {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            text-align: inherit !important;
        }

        .cert-element strong, .cert-element b {
            font-weight: 800 !important;
        }
        .cert-element em, .cert-element i {
            font-style: italic !important;
        }

        .cert-element .ql-align-center { text-align: center !important; }
        .cert-element .ql-align-right { text-align: right !important; }
        .cert-element .ql-align-left { text-align: left !important; }

        .cert-element:hover { 
            border-color: #FF5500 !important; 
            background-color: rgba(255, 85, 0, 0.05); 
        }

        .cert-element.selected { 
            border: 2px solid #FF5500 !important; 
            background-color: rgba(255, 85, 0, 0.08);
            z-index: 100; 
            cursor: grabbing;
            box-shadow: 0 0 0 4px rgba(255, 85, 0, 0.2);
        }

        .resize-handle {
            position: absolute !important;
            width: 10px !important;
            height: 10px !important;
            background-color: #FF5500 !important;
            border: 2px solid #FFFFFF !important;
            border-radius: 50% !important;
            z-index: 110 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;
            display: none;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .cert-element.selected .resize-handle { display: block !important; }
        .handle-nw { top: -5px; left: -5px; cursor: nw-resize; }
        .handle-ne { top: -5px; right: -5px; cursor: ne-resize; }
        .handle-sw { bottom: -5px; left: -5px; cursor: sw-resize; }
        .handle-se { bottom: -5px; right: -5px; cursor: se-resize; }

        .cert-element img {
            display: block;
            pointer-events: none;
            width: 100%;
            height: 100%;
        }

        /* TARJETAS DE ELEMENTOS TIPO ELEMENTOR WIDGETS (2 COLUMNAS) */
        .elementor-widget-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.75rem 0.6rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }
        .elementor-widget-card:hover {
            background: rgba(255, 85, 0, 0.12);
            border-color: #FF5500;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 85, 0, 0.2);
        }
        .elementor-widget-icon {
            font-size: 1.4rem;
            line-height: 1;
        }
        .elementor-widget-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.2;
        }

        /* TARJETAS DE VARIANTES DE LOGO */
        .logo-variant-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 0.85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .logo-variant-card:hover, .logo-variant-card.selected {
            border-color: #FF5500;
            background: rgba(255, 85, 0, 0.08);
            box-shadow: 0 0 0 2px rgba(255, 85, 0, 0.3);
        }

        .ql-toolbar.ql-snow { border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px 8px 0 0; background: #1E1E2D; color: #FFF; }
        .ql-container.ql-snow { border: 1px solid rgba(255, 255, 255, 0.15); border-top: none; border-radius: 0 0 8px 8px; background: #14141E; color: #FFF; font-family: inherit; font-size: 13px; }
        .ql-editor { min-height: 80px; color: #FFFFFF; }
        .ql-snow .ql-stroke { stroke: #CBD5E1 !important; }
        .ql-snow .ql-fill { fill: #CBD5E1 !important; }
        .ql-snow .ql-picker { color: #CBD5E1 !important; }

        .font-lato { font-family: 'Lato', sans-serif; }
        .font-montserrat { font-family: 'Montserrat', sans-serif; }
        .font-opensans { font-family: 'Open Sans', sans-serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .font-raleway { font-family: 'Raleway', sans-serif; }
        .font-nunito { font-family: 'Nunito', sans-serif; }
        .font-rubik { font-family: 'Rubik', sans-serif; }
        .font-work-sans { font-family: 'Work Sans', sans-serif; }
        .font-space-grotesk { font-family: 'Space Grotesk', sans-serif; }
        .font-bebas { font-family: 'Bebas Neue', cursive; }
        .font-oswald { font-family: 'Oswald', sans-serif; }
        .font-anton { font-family: 'Anton', sans-serif; }
        .font-righteous { font-family: 'Righteous', cursive; }
        .font-syne { font-family: 'Syne', sans-serif; }
        .font-merriweather { font-family: 'Merriweather', serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-abril { font-family: 'Abril Fatface', serif; }
        .font-dancing { font-family: 'Dancing Script', cursive; }
        .font-greatvibes { font-family: 'Great Vibes', cursive; }
        .font-pacifico { font-family: 'Pacifico', cursive; }
        .font-satisfy { font-family: 'Satisfy', cursive; }
        .font-caveat { font-family: 'Caveat', cursive; }
        .font-lobster { font-family: 'Lobster', cursive; }
        .font-permanent { font-family: 'Permanent Marker', cursive; }
        .font-monoton { font-family: 'Monoton', cursive; }
        .font-comfortaa { font-family: 'Comfortaa', cursive; }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
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
                        <span class="settings-tag">📅 CREACIÓN DE EVENTO PRINCIPAL (DISEÑADOR ELEMENTOR PRO)</span>
                        <h1 class="settings-page-title">Crear Nuevo Evento Principal</h1>
                        <p class="settings-page-subtitle">Completa la información general, zonas y personaliza el boleto en el diseñador visual (20.40 cm × 9.80 cm).</p>
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
                            <span class="step-desc">Diseñador 20.40 × 9.80 cm</span>
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
                            
                            <div style="display: grid; grid-template-columns: 480px 1fr; gap: 1.75rem; align-items: stretch; margin-bottom: 1.75rem;" class="step1-top-grid">
                                
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

                                        <div style="position: relative; width: 100%; height: 290px; border-radius: 16px; overflow: hidden; border: 1.5px solid rgba(255,255,255,0.25); background: #000000; box-shadow: 0 12px 30px rgba(0,0,0,0.4); cursor: pointer;" onclick="openMediaManager('event_banner');" title="Haz clic para seleccionar desde la Galería de Medios">
                                            <img id="bannerPreviewImg" src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80" alt="Vista Previa de Banner" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                            
                                            <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.25s ease; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; color: #FFFFFF; font-weight: 800; font-size: 0.95rem;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                                <span style="font-size: 1.8rem;">🖼️</span>
                                                <span>Abrir Galería de Medios</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <input type="hidden" id="event_banner" value="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80">
                                        <input type="file" id="bannerFileInput" accept="image/*" style="display: none;" onchange="handleBannerUpload(this)">
                                        
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; text-align: center; justify-content: center; padding: 0.75rem 0.5rem; font-size: 0.85rem;" onclick="openMediaManager('event_banner');">
                                                🖼️ Seleccionar de la Galería
                                            </button>
                                            <button type="button" class="btn btn-cancel-custom" style="padding: 0.75rem 0.85rem; font-size: 0.85rem;" onclick="document.getElementById('bannerFileInput').click();" title="Subir archivo desde mi PC">
                                                📁 Subir PC
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 1.25rem; justify-content: space-between;">
                                    <div class="form-group-custom">
                                        <label for="event_title" class="form-label-custom">Nombre / Título del Evento <span class="required-star">*</span></label>
                                        <input type="text" id="event_title" class="form-input-custom" required value="Gran Concierto Principal 2026">
                                    </div>

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

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="form-group-custom">
                                            <label for="event_date_picker" class="form-label-custom">Fecha del Evento <span class="required-star">*</span></label>
                                            <input type="date" id="event_date_picker" class="form-input-custom" required value="2026-11-15">
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_time_picker" class="form-label-custom">Hora de Inicio <span class="required-star">*</span></label>
                                            <input type="time" id="event_time_picker" class="form-input-custom" required value="20:00">
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="form-group-custom">
                                            <label for="event_venue" class="form-label-custom">Recinto / Local del Show <span class="required-star">*</span></label>
                                            <input type="text" id="event_venue" class="form-input-custom" required value="Estadio Nacional Ayacucho">
                                        </div>

                                        <div class="form-group-custom">
                                            <label for="event_address" class="form-label-custom">Dirección del Recinto & Ciudad <span class="required-star">*</span></label>
                                            <input type="text" id="event_address" class="form-input-custom" required value="Av. Independencia s/n - AYACUCHO">
                                        </div>
                                    </div>

                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Modalidad de Venta de Entradas <span class="required-star">*</span></label>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                            <label style="border: 2px solid var(--color-primary-orange); background: rgba(255, 85, 0, 0.08); padding: 0.85rem 1.15rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; cursor: pointer;" id="labelSalesFisica">
                                                <input type="radio" name="event_sales_type" id="salesTypeFisica" value="fisica" checked style="accent-color: #FF5500; width: 18px; height: 18px;">
                                                <div>
                                                    <strong style="display: block; font-size: 0.95rem; color: #FFFFFF;">🎫 Venta Física (Taquilla)</strong>
                                                    <span style="font-size: 0.78rem; color: #94A3B8;">Boletos físicos / Punto de venta POS</span>
                                                </div>
                                            </label>
                                            
                                            <label style="border: 2px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02); padding: 0.85rem 1.15rem; border-radius: 14px; display: flex; align-items: center; gap: 0.75rem; cursor: pointer;" id="labelSalesVirtual">
                                                <input type="radio" name="event_sales_type" id="salesTypeVirtual" value="virtual" style="accent-color: #FF5500; width: 18px; height: 18px;">
                                                <div>
                                                    <strong style="display: block; font-size: 0.95rem; color: #FFFFFF;">🌐 Venta Virtual (Online)</strong>
                                                    <span style="font-size: 0.78rem; color: #94A3B8;">Venta exclusiva web con ticket digital</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 1.5rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <label class="form-label-custom" style="margin: 0;">
                                        Ubicación Geográfica en Mapa <span class="required-star">*</span>
                                    </label>
                                </div>
                                
                                <div class="map-picker-container" style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.12); padding: 1.25rem; border-radius: 16px;">
                                    <div id="interactiveLeafletMap" style="height: 290px; width: 100%; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); z-index: 1;"></div>
                                    
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.75rem; background: rgba(0,0,0,0.6); padding: 0.65rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                                        <span style="font-size: 0.85rem; font-weight: 700; color: #FFFFFF;">
                                            📍 Coordenadas de Ubicación: <strong id="mapCoordsText" style="color: var(--color-neon-cyan);">-13.1631, -74.2236</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-custom" style="margin-bottom: 1.5rem;">
                                <label for="event_details" class="form-label-custom">Detalles del Evento & Descripción <span class="required-star">*</span></label>
                                <textarea id="event_details" class="form-textarea-custom" rows="4" required placeholder="Escribe la descripción del evento...">Gran concierto estelar con los mejores grupos en vivo.</textarea>
                            </div>

                            <div class="form-group-custom">
                                <label for="event_tags_input" class="form-label-custom">Etiquetas & Palabras Clave (Tags)</label>
                                <div class="tags-input-wrapper" id="tagsWrapper">
                                    <span class="tag-chip">#Concierto2026 <button type="button" onclick="this.parentElement.remove()">✕</button></span>
                                    <input type="text" id="event_tags_input" class="tag-inner-input" placeholder="Agregar tag..." onkeydown="handleTagKeydown(event)">
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

                <!-- STEP 2: ZONAS & TARIFAS -->
                <div class="step-content-panel" id="stepPanel2">
                    <div class="settings-card-box">
                        <div class="settings-card-header" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div class="card-header-icon" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: #EAB308;">🏷️</div>
                                <div>
                                    <h3 class="card-header-title">Paso 2: Zonas & Configuración de Tarifas</h3>
                                    <p class="card-header-subtitle">Establece la capacidad y precio de cada zona para este evento</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-save-settings" style="font-size: 0.85rem; padding: 0.65rem 1.25rem;" onclick="addDynamicZoneRow()">
                                ➕ Añadir Nueva Zona / Sector
                            </button>
                        </div>

                        <div class="dash-table-container" style="margin-bottom: 1.5rem;">
                            <table class="dash-table" id="zonesDynamicTable">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">Tipo de Aforo</th>
                                        <th style="width: 30%;">Nombre Zona / Sector</th>
                                        <th style="width: 20%;">Capacidad (Boletos)</th>
                                        <th style="width: 20%;">Precio Individual (S/)</th>
                                        <th style="width: 5%; text-align: center;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="zonesTableBody">
                                    <tr class="zone-row">
                                        <td>
                                            <select class="form-select-custom zone-capacity-type" style="font-size: 0.85rem; padding: 0.55rem;">
                                                @foreach($capacityTypes as $ct)
                                                    <option value="{{ is_array($ct) ? $ct['name'] : $ct->name }}" selected>
                                                        🏟️ {{ is_array($ct) ? $ct['name'] : $ct->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-custom zone-name-input" value="ZONA PLATINUM" style="font-size: 0.85rem; padding: 0.55rem;">
                                        </td>
                                        <td>
                                            <input type="number" class="form-input-custom zone-capacity-input" value="800" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                                        </td>
                                        <td>
                                            <input type="number" step="0.50" class="form-input-custom zone-price-input" value="150.00" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                                        </td>
                                    </tr>
                                    <tr class="zone-row">
                                        <td>
                                            <select class="form-select-custom zone-capacity-type" style="font-size: 0.85rem; padding: 0.55rem;">
                                                @foreach($capacityTypes as $ct)
                                                    <option value="{{ is_array($ct) ? $ct['name'] : $ct->name }}" {{ (is_array($ct) ? $ct['name'] : $ct->name) == 'ZONA GENERAL' ? 'selected' : '' }}>
                                                        🏟️ {{ is_array($ct) ? $ct['name'] : $ct->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-custom zone-name-input" value="ZONA GENERAL" style="font-size: 0.85rem; padding: 0.55rem;">
                                        </td>
                                        <td>
                                            <input type="number" class="form-input-custom zone-capacity-input" value="2000" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                                        </td>
                                        <td>
                                            <input type="number" step="0.50" class="form-input-custom zone-price-input" value="60.00" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); padding: 1rem 1.25rem; border-radius: 14px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #94A3B8;">Aforo Total Configurado:</span>
                                <strong id="totalCapacitySummaryText" style="font-size: 1.1rem; color: #FFFFFF; margin-left: 0.5rem;">2,800 entradas</strong>
                            </div>
                            <div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #94A3B8;">Precio Desde:</span>
                                <strong id="minPriceSummaryText" style="font-size: 1.1rem; color: #10B981; margin-left: 0.5rem;">S/ 60.00</strong>
                            </div>
                        </div>

                        <!-- SECCIÓN: SUBIR IMAGEN DE REFERENCIA / PLANO DE ZONAS -->
                        <div style="margin-top: 1.75rem; background: rgba(255, 255, 255, 0.02); border: 1.5px dashed rgba(255, 255, 255, 0.18); border-radius: 18px; padding: 1.5rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.65rem;">
                                    <span style="font-size: 1.3rem;">🗺️</span>
                                    <div>
                                        <h4 style="margin: 0; font-size: 1rem; font-weight: 800; color: #FFFFFF;">Imagen de Referencia del Recinto / Mapa de Zonas</h4>
                                        <p style="margin: 0.15rem 0 0 0; font-size: 0.8rem; color: #94A3B8;">Sube un croquis, plano o imagen de referencia de las zonas para los compradores (se mostrará arriba de los detalles del evento).</p>
                                    </div>
                                </div>
                                <span class="dash-badge-custom badge-blue" style="font-size: 0.75rem; font-weight: 800;">Opcional</span>
                            </div>

                            <input type="hidden" id="reference_image" value="">
                            <input type="file" id="referenceFileInput" accept="image/*" style="display: none;" onchange="handleReferenceImageUpload(this)">

                            <!-- Contenedor cuando no hay imagen seleccionada -->
                            <div id="referencePlaceholderBox" style="border: 1.5px dashed rgba(255, 255, 255, 0.15); background: rgba(15, 23, 42, 0.5); border-radius: 14px; padding: 2rem 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s;" onclick="openMediaManager('reference_image');">
                                <span style="font-size: 2.2rem; display: block; margin-bottom: 0.5rem;">📐</span>
                                <strong style="color: #E2E8F0; font-size: 0.95rem; display: block;">Subir Imagen de Referencia o Plano de Zonas</strong>
                                <p style="color: #94A3B8; font-size: 0.8rem; margin: 0.35rem 0 1rem 0;">Formatos permitidos: PNG, JPG, WEBP o SVG (máx. 10MB)</p>
                                <div style="display: inline-flex; gap: 0.65rem;" onclick="event.stopPropagation();">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="font-size: 0.825rem; padding: 0.55rem 1rem;" onclick="openMediaManager('reference_image');">
                                        🖼️ Seleccionar de la Galería
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom" style="font-size: 0.825rem; padding: 0.55rem 1rem;" onclick="document.getElementById('referenceFileInput').click();">
                                        📁 Subir desde PC
                                    </button>
                                </div>
                            </div>

                            <!-- Contenedor con vista previa cuando hay imagen -->
                            <div id="referencePreviewContainer" style="display: none; position: relative; border-radius: 14px; overflow: hidden; border: 1.5px solid rgba(255, 255, 255, 0.2); background: #0B0F19; text-align: center; padding: 1rem;">
                                <img id="referencePreviewImg" src="" alt="Vista Previa de Referencia" style="max-height: 280px; width: auto; max-width: 100%; object-fit: contain; margin: 0 auto; display: block; border-radius: 8px;">
                                <div style="display: flex; justify-content: center; gap: 0.65rem; margin-top: 1rem;">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="font-size: 0.8rem; padding: 0.45rem 0.85rem;" onclick="openMediaManager('reference_image');">
                                        🔄 Cambiar Imagen
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom" style="font-size: 0.8rem; padding: 0.45rem 0.85rem;" onclick="document.getElementById('referenceFileInput').click();">
                                        📁 Subir otra de PC
                                    </button>
                                    <button type="button" class="btn btn-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171; font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 8px; cursor: pointer;" onclick="removeReferenceImage()">
                                        🗑️ Quitar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(1)">
                                ← Anterior: Información General
                            </button>
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2rem; font-size: 1rem;" onclick="goToStep(3)">
                                Continuar al Diseñador ➔
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: DISEÑADOR INTERACTIVO ESTILO ELEMENTOR / CERTIFICADOS (20.40 CM × 9.80 CM) -->
                <div class="step-content-panel" id="stepPanel3">
                    <input type="hidden" name="custom_ticket" id="custom_ticket_input" value="">

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <!-- ENCABEZADO DEL DISEÑADOR -->
                        <div style="background: #14141E; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: #FF5500; font-size: 1.2rem;">🎨</div>
                                <div>
                                    <h3 style="margin: 0; color: #FFFFFF; font-size: 1.1rem; font-weight: 800;">Diseñador Visual de Boletos Estilo Elementor</h3>
                                    <p style="margin: 0; color: #94A3B8; font-size: 0.825rem;">Dimensiones oficiales de boleto: <strong style="color: #06B6D4;">20.40 cm × 9.80 cm (771px × 370px)</strong></p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.75rem;">
                                <button type="button" class="btn btn-primary btn-save-settings" onclick="openMediaManager('background')" style="padding: 0.6rem 1.1rem; font-size: 0.85rem;">
                                    🖼️ Seleccionar Imagen de Fondo
                                </button>
                            </div>
                        </div>

                        <!-- CONTENEDOR PRINCIPAL DEL EDITOR (PANEL IZQUIERDO ELEMENTOR + LIENZO DE TRABAJO DERECHO) -->
                        <div style="display: grid; grid-template-columns: 360px 1fr; gap: 1.25rem; align-items: start; min-height: 520px;">
                            
                            <!-- PANEL IZQUIERDO: HERRAMIENTAS & PROPIEDADES ESTILO ELEMENTOR -->
                            <div style="background: #14141E; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; max-height: 650px; overflow-y: auto;" class="custom-scrollbar">
                                
                                <!-- VISTA 1: LISTADO DE ETIQUETAS Y WIDGETS (CARDS EN 2 COLUMNAS) -->
                                <div id="sidebarElementsView" style="display: flex; flex-direction: column; gap: 1rem;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.6rem;">
                                        <h4 style="margin: 0; font-size: 0.825rem; font-weight: 900; color: #FF5500; text-transform: uppercase; letter-spacing: 0.5px;">📌 ETIQUETAS & WIDGETS</h4>
                                        <span style="font-size: 0.725rem; color: #94A3B8;">Haz clic para insertar</span>
                                    </div>

                                    <!-- GRILLA DE 2 COLUMNAS FORMATO CARDS ELEMENTOR SIN ICONOS EN TEXTO DE ETIQUETAS -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                                        <div class="elementor-widget-card" onclick="addElement('logo', '/images/logo.png', 'Logo Marca')">
                                            <span class="elementor-widget-icon">🖼️</span>
                                            <span class="elementor-widget-title">Logo Marca</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Evento: Gran Concierto Principal 2026')">
                                            <span class="elementor-widget-icon">📝</span>
                                            <span class="elementor-widget-title">Título Show</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Zona: VIP')">
                                            <span class="elementor-widget-icon">🏷️</span>
                                            <span class="elementor-widget-title">Zona / Sector</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Precio: S/ 150.00')">
                                            <span class="elementor-widget-icon">💰</span>
                                            <span class="elementor-widget-title">Precio Entrada</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('banner', null, 'Banner Ticket')">
                                            <span class="elementor-widget-icon">🖼️</span>
                                            <span class="elementor-widget-title">Banner Ticket</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Comprador: Juan Pérez')">
                                            <span class="elementor-widget-icon">👤</span>
                                            <span class="elementor-widget-title">Comprador</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'DNI: 70654321')">
                                            <span class="elementor-widget-icon">🆔</span>
                                            <span class="elementor-widget-title">DNI Comprador</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Recinto: Estadio Nacional')">
                                            <span class="elementor-widget-icon">📍</span>
                                            <span class="elementor-widget-title">Recinto Show</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Ciudad: Ayacucho')">
                                            <span class="elementor-widget-icon">🏙️</span>
                                            <span class="elementor-widget-title">Ciudad</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Fecha: 15/11/2026')">
                                            <span class="elementor-widget-icon">📅</span>
                                            <span class="elementor-widget-title">Fecha Evento</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'Hora: 20:00 hrs')">
                                            <span class="elementor-widget-icon">⏰</span>
                                            <span class="elementor-widget-title">Hora Evento</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'N° 00001')">
                                            <span class="elementor-widget-icon">🔢</span>
                                            <span class="elementor-widget-title">N° Correlativo</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('qr')">
                                            <span class="elementor-widget-icon">📲</span>
                                            <span class="elementor-widget-title">Código QR</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'VG-9A8B7C6D')">
                                            <span class="elementor-widget-icon">🔑</span>
                                            <span class="elementor-widget-title">Hash Validación</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('system_tag', null, 'La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.', '9px')" style="grid-column: span 2;">
                                            <span class="elementor-widget-icon">📜</span>
                                            <span class="elementor-widget-title">Disclaimer Legal</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="addElement('text', null, 'Texto Libre')">
                                            <span class="elementor-widget-icon">✍️</span>
                                            <span class="elementor-widget-title">Texto Libre</span>
                                        </div>
                                        <div class="elementor-widget-card" onclick="openMediaManager('image')">
                                            <span class="elementor-widget-icon">📷</span>
                                            <span class="elementor-widget-title">Imagen Externa</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- VISTA 2: INSPECTOR DE PROPIEDADES (SOLO VISIBLE CUANDO UN ELEMENTO ESTÁ SELECCIONADO) -->
                                <div id="sidebarInspectorView" style="display: none; flex-direction: column; gap: 0.85rem;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.6rem;">
                                        <button type="button" class="btn btn-cancel-custom" style="padding: 0.35rem 0.75rem; font-size: 0.775rem; font-weight: 800;" onclick="deselectAll()">
                                            ← Volver a Elementos
                                        </button>
                                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--color-neon-cyan);" id="selectedElementTypeTitle">OPCIONES WIDGET</span>
                                    </div>
                                    
                                    <!-- CONTROLES COMUNES DE TRANSFORMACIÓN Y TAMAÑO PARA TODOS LOS ELEMENTOS -->
                                    <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <label style="font-size: 0.775rem; font-weight: 800; color: #CBD5E1; margin: 0;">🔄 TRANSFORMACIÓN & ROTACIÓN</label>
                                        </div>

                                        <div>
                                            <label style="font-size: 0.725rem; font-weight: 700; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Ángulo de Rotación (°)</label>
                                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                <input type="number" id="prop-rotation" class="form-input-custom" value="0" min="-360" max="360" step="15" style="font-size: 0.775rem; padding: 0.4rem; width: 75px;" oninput="updateSelectedProp('rotation', this.value)">
                                                <div style="display: flex; gap: 4px; flex: 1;">
                                                    <button type="button" class="btn btn-cancel-custom" style="padding: 0.35rem 0.4rem; font-size: 0.75rem; flex: 1; text-align: center;" onclick="rotateSelectedElement(-90)" title="Rotar a la izquierda -90°">↺ -90°</button>
                                                    <button type="button" class="btn btn-cancel-custom" style="padding: 0.35rem 0.4rem; font-size: 0.75rem; flex: 1; text-align: center;" onclick="rotateSelectedElement(0)" title="Restablecer rotación a 0°">0°</button>
                                                    <button type="button" class="btn btn-cancel-custom" style="padding: 0.35rem 0.4rem; font-size: 0.75rem; flex: 1; text-align: center;" onclick="rotateSelectedElement(90)" title="Rotar a la derecha +90°">↻ +90°</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                            <div>
                                                <label style="font-size: 0.725rem; font-weight: 700; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Ancho (px)</label>
                                                <input type="number" id="prop-width" class="form-input-custom" placeholder="auto" min="20" max="771" style="font-size: 0.775rem; padding: 0.4rem;" oninput="updateSelectedProp('width', this.value)">
                                            </div>
                                            <div>
                                                <label style="font-size: 0.725rem; font-weight: 700; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Alto (px)</label>
                                                <input type="number" id="prop-height" class="form-input-custom" placeholder="auto" min="10" max="370" style="font-size: 0.775rem; padding: 0.4rem;" oninput="updateSelectedProp('height', this.value)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- INSPECTOR ESPECIALIZADO DE LOGO MARCA (SELECCIÓN ENTRE LOGO COLOR Y LOGO BLANCO) -->
                                    <div id="logoInspectorControls" style="display: none; flex-direction: column; gap: 1rem;">
                                        <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 1rem;">
                                            <label style="font-size: 0.775rem; font-weight: 800; color: #CBD5E1; display: block; margin-bottom: 0.75rem;">
                                                Selecciona la versión oficial del logo:
                                            </label>
                                            
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                                <div class="logo-variant-card" id="logoCardColor" onclick="setLogoVariant('/images/logo.png')">
                                                    <div style="background: #1E1E2D; width: 100%; border-radius: 10px; padding: 0.75rem; text-align: center; border: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; height: 50px;">
                                                        <img src="/images/logo.png" alt="Logo Color" style="max-height: 38px; max-width: 100%; object-fit: contain;">
                                                    </div>
                                                    <span style="font-size: 0.75rem; font-weight: 800; color: #FFFFFF;">🎨 Logo Color</span>
                                                </div>

                                                <div class="logo-variant-card" id="logoCardWhite" onclick="setLogoVariant('/images/logo-white.png')">
                                                    <div style="background: #000000; width: 100%; border-radius: 10px; padding: 0.75rem; text-align: center; border: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; height: 50px;">
                                                        <img src="/images/logo-white.png" alt="Logo Blanco" style="max-height: 38px; max-width: 100%; object-fit: contain;">
                                                    </div>
                                                    <span style="font-size: 0.75rem; font-weight: 800; color: #FFFFFF;">⚪ Logo Blanco</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- INSPECTOR DE IMAGEN GENERAL (PARA BANNER TICKET E IMÁGENES EXTERNAS) -->
                                    <div id="imageInspectorControls" style="display: none; flex-direction: column; gap: 1rem;">
                                        <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 1rem; text-align: center;">
                                            <div style="width: 100%; height: 140px; background: #000; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 0.85rem; border: 1px solid rgba(255,255,255,0.15);">
                                                <img id="inspectorImgPreview" src="" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                            </div>
                                            <button type="button" class="btn btn-primary btn-save-settings" style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.85rem;" onclick="openMediaManager('selected_element_image')">
                                                🖼️ Seleccionar Imagen de la Galería
                                            </button>
                                        </div>

                                        <!-- MODO DE AJUSTE DE IMAGEN EN EL INSPECTOR -->
                                        <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 1rem;">
                                            <label style="font-size: 0.775rem; font-weight: 800; color: #CBD5E1; display: block; margin-bottom: 0.5rem;">
                                                📐 MODO DE AJUSTE DE IMAGEN
                                            </label>
                                            <select id="prop-object-fit" class="form-select-custom" style="width: 100%; max-width: 100%; font-size: 0.775rem; padding: 0.45rem; text-overflow: ellipsis;" onchange="updateSelectedProp('objectFit', this.value)">
                                                <option value="cover">🖼️ Rellenar</option>
                                                <option value="fill">📐 Estirar</option>
                                                <option value="contain">🔍 Contener</option>
                                            </select>
                                            <small style="display: block; font-size: 0.725rem; color: #94A3B8; margin-top: 0.4rem; line-height: 1.3;">
                                                • <strong>Rellenar:</strong> Expande la imagen a los laterales sin bordes vacíos.<br>
                                                • <strong>Estirar:</strong> Fuerza la imagen a ocupar el 100% exacto del marco.<br>
                                                • <strong>Contener:</strong> Mantiene la imagen completa sin recortar.
                                            </small>
                                        </div>
                                    </div>

                                    <!-- INSPECTOR DE TEXTO (AMPLIA SELECCIÓN DE 30 FUENTES GOOGLE FONTS) -->
                                    <div id="textInspectorControls" style="display: flex; flex-direction: column; gap: 0.85rem;">
                                        <div>
                                            <label style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Contenido de Texto</label>
                                            <div id="cert-text-editor"></div>
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                            <div>
                                                <label style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Familia de Tipografía</label>
                                                <select id="prop-font-family" class="form-select-custom" style="font-size: 0.775rem; padding: 0.4rem;" onchange="updateSelectedProp('fontFamily', this.value)">
                                                    <optgroup label="SANS-SERIF MODERNA">
                                                        <option value="font-montserrat">Montserrat</option>
                                                        <option value="font-inter">Inter</option>
                                                        <option value="font-poppins">Poppins</option>
                                                        <option value="font-roboto">Roboto</option>
                                                        <option value="font-lato">Lato</option>
                                                        <option value="font-opensans">Open Sans</option>
                                                        <option value="font-outfit">Outfit</option>
                                                        <option value="font-raleway">Raleway</option>
                                                        <option value="font-nunito">Nunito</option>
                                                        <option value="font-rubik">Rubik</option>
                                                        <option value="font-work-sans">Work Sans</option>
                                                        <option value="font-space-grotesk">Space Grotesk</option>
                                                    </optgroup>
                                                    <optgroup label="TITULARES & IMPACTO">
                                                        <option value="font-bebas">Bebas Neue</option>
                                                        <option value="font-oswald">Oswald</option>
                                                        <option value="font-anton">Anton</option>
                                                        <option value="font-righteous">Righteous</option>
                                                        <option value="font-syne">Syne</option>
                                                        <option value="font-monoton">Monoton</option>
                                                        <option value="font-permanent">Permanent Marker</option>
                                                    </optgroup>
                                                    <optgroup label="SERIF & ELEGANTES">
                                                        <option value="font-merriweather">Merriweather</option>
                                                        <option value="font-playfair">Playfair Display</option>
                                                        <option value="font-cinzel">Cinzel</option>
                                                        <option value="font-abril">Abril Fatface</option>
                                                    </optgroup>
                                                    <optgroup label="CURSIVA & MANUSCRITA">
                                                        <option value="font-dancing">Dancing Script</option>
                                                        <option value="font-greatvibes">Great Vibes</option>
                                                        <option value="font-pacifico">Pacifico</option>
                                                        <option value="font-satisfy">Satisfy</option>
                                                        <option value="font-caveat">Caveat</option>
                                                        <option value="font-lobster">Lobster</option>
                                                        <option value="font-comfortaa">Comfortaa</option>
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div>
                                                <label style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Tamaño (px)</label>
                                                <input type="number" id="prop-font-size" class="form-input-custom" value="12" min="6" max="120" style="font-size: 0.775rem; padding: 0.4rem;" oninput="updateSelectedProp('fontSize', this.value)">
                                            </div>
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                            <div>
                                                <label style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Estilos de Texto</label>
                                                <div style="display: flex; gap: 4px;">
                                                    <button type="button" class="btn btn-cancel-custom" id="btnToggleBold" onclick="toggleBold()" style="padding: 0.45rem; flex: 1; text-align: center; font-weight: 900;" title="Negrita (Bold)">B</button>
                                                    <button type="button" class="btn btn-cancel-custom" id="btnToggleItalic" onclick="toggleItalic()" style="padding: 0.45rem; flex: 1; text-align: center; font-style: italic; font-weight: 700;" title="Cursiva (Italic)">I</button>
                                                </div>
                                            </div>

                                            <div>
                                                <label style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Color Texto</label>
                                                <input type="color" id="prop-text-color" value="#000000" style="width: 100%; height: 32px; border: none; border-radius: 8px; cursor: pointer; background: transparent;" onchange="updateSelectedProp('color', this.value)">
                                            </div>
                                        </div>

                                        <div>
                                            <label style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Alineación</label>
                                            <!-- BOTONES DE ALINEACIÓN CON ICONOS SVG PROFESIONALES -->
                                            <div style="display: flex; gap: 4px;">
                                                <button type="button" class="btn btn-cancel-custom align-btn" id="alignBtnLeft" onclick="updateSelectedProp('textAlign', 'left')" style="padding: 0.45rem; flex: 1; text-align: center; display: flex; align-items: center; justify-content: center;" title="Alinear a la Izquierda">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="21" y1="6" x2="3" y2="6"/><line x1="15" y1="12" x2="3" y2="12"/><line x1="18" y1="18" x2="3" y2="18"/></svg>
                                                </button>
                                                <button type="button" class="btn btn-cancel-custom align-btn" id="alignBtnCenter" onclick="updateSelectedProp('textAlign', 'center')" style="padding: 0.45rem; flex: 1; text-align: center; display: flex; align-items: center; justify-content: center;" title="Alinear al Centro">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="21" y1="6" x2="3" y2="6"/><line x1="18" y1="12" x2="6" y2="12"/><line x1="21" y1="18" x2="3" y2="18"/></svg>
                                                </button>
                                                <button type="button" class="btn btn-cancel-custom align-btn" id="alignBtnRight" onclick="updateSelectedProp('textAlign', 'right')" style="padding: 0.45rem; flex: 1; text-align: center; display: flex; align-items: center; justify-content: center;" title="Alinear a la Derecha">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="12" x2="9" y2="12"/><line x1="21" y1="18" x2="3" y2="18"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BOTÓN DE ELIMINAR ELEMENTO -->
                                    <button type="button" onclick="removeSelectedElement()" style="width: 100%; padding: 0.55rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #EF4444; border-radius: 10px; font-size: 0.8rem; font-weight: 800; cursor: pointer; text-align: center; margin-top: 0.5rem;">
                                        🗑️ Eliminar Elemento Seleccionado
                                    </button>
                                </div>

                            </div>

                            <!-- COLUMNA DERECHA: MESA DE TRABAJO Y LIENZO (771PX × 370PX = 20.40CM × 9.80CM) -->
                            <div style="background: #0F172A; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; min-height: 520px;" id="workspace-container">
                                
                                <div style="background: rgba(0,0,0,0.4); border-b: 1px solid rgba(255,255,255,0.1); padding: 0.65rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 0.775rem; font-weight: 800; color: #38BDF8; text-transform: uppercase; letter-spacing: 0.5px;">📐 MESA DE TRABAJO — BOLETO 20.40 CM × 9.80 CM</span>
                                    
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <button type="button" class="btn btn-cancel-custom" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" onclick="zoomCanvas(-0.1)">-</button>
                                        <span style="font-size: 0.775rem; font-weight: 800; color: #FFFFFF; min-width: 40px; text-align: center;" id="zoom-level-indicator">100%</span>
                                        <button type="button" class="btn btn-cancel-custom" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" onclick="zoomCanvas(0.1)">+</button>
                                        <button type="button" class="btn btn-cancel-custom" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" onclick="fitCanvas()">Ajustar</button>
                                    </div>
                                </div>

                                <div style="flex: 1; overflow: auto; padding: 2.5rem 1rem; display: flex; flex-direction: column; align-items: center; justify-content: center;" id="scroll-area" class="custom-scrollbar">
                                    <div id="cert-canvas-wrapper" style="position: relative;">
                                        <div id="cert-canvas" onclick="if(event.target === this) deselectAll()">
                                            <img id="cert-background" src="" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: fill; pointer-events: none; z-index: 0; display: none;">
                                            
                                            <div id="cert-background-placeholder" style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94A3B8; border: 2px dashed rgba(0,0,0,0.15); margin: 15px; border-radius: 12px; pointer-events: none; background: #FFFFFF;">
                                                <span style="font-size: 2.5rem; margin-bottom: 0.25rem; opacity: 0.5;">🖼️</span>
                                                <p style="font-size: 0.825rem; font-weight: 700; color: #64748B; margin: 0;">Fondo del Boleto (20.40 cm × 9.80 cm)</p>
                                                <small style="font-size: 0.725rem; color: #94A3B8;">Subes una imagen de fondo o usa el botón superior</small>
                                            </div>

                                            <div id="elements-layer" style="position: absolute; inset: 0; z-index: 10; width: 100%; height: 100%;"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(2)">
                                ← Anterior: Zonas & Tarifas
                            </button>
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2rem; font-size: 1rem;" onclick="goToStep(4)">
                                Continuar a Confirmación ➔
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: CONFIRMACIÓN Y PUBLICACIÓN -->
                <div class="step-content-panel" id="stepPanel4">
                    <div class="settings-card-box">
                        <div class="settings-card-header">
                            <div class="card-header-icon" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #10B981;">🚀</div>
                            <div>
                                <h3 class="card-header-title">Paso 4: Confirmación & Registro del Evento Principal</h3>
                                <p class="card-header-subtitle">Revisa el resumen final de la información antes de guardar en MySQL</p>
                            </div>
                        </div>

                        <div style="background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 1.5rem; margin-bottom: 2rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                                <div>
                                    <h4 style="color: #94A3B8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">Título del Evento</h4>
                                    <p id="reviewTitle" style="color: #FFFFFF; font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem;">Gran Concierto Principal 2026</p>

                                    <h4 style="color: #94A3B8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">Categoría & Organizador</h4>
                                    <p id="reviewCategoryCompany" style="color: #FFFFFF; font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem;">Conciertos — PRODUCCIONES VIVE GO S.A.C.</p>
                                </div>

                                <div>
                                    <h4 style="color: #94A3B8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">Fecha, Hora & Recinto</h4>
                                    <p id="reviewDateTimeVenue" style="color: #FFFFFF; font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem;">15/11/2026 - 20:00 hrs | Estadio Nacional Ayacucho</p>

                                    <h4 style="color: #94A3B8; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">Aforo Total Configurado</h4>
                                    <p id="reviewCapacity" style="color: #10B981; font-size: 1.1rem; font-weight: 900; margin: 0;">2,800 entradas</p>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between;">
                            <button type="button" class="btn btn-cancel-custom" onclick="goToStep(3)">
                                ← Volver al Paso 3
                            </button>
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.9rem 2.5rem; font-size: 1.05rem;" onclick="submitMainEventForm()">
                                🚀 Publicar Evento Principal
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL DE BIBLIOTECA DE MEDIOS INTERACTIVA -->
    <div id="media-modal" style="position: fixed; inset: 0; z-index: 99999; display: none;">
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px);" onclick="closeMediaManager()"></div>
        <div style="position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem;">
            <div style="background: #14141E; border: 1px solid rgba(255,255,255,0.15); border-radius: 24px; width: 100%; max-width: 680px; height: 520px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.5); z-index: 10;">
                <div style="padding: 1rem 1.25rem; background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: space-between;">
                    <h3 style="margin: 0; color: #FFFFFF; font-size: 1.1rem; font-weight: 800;">🖼️ Biblioteca de Medios</h3>
                    <button type="button" onclick="closeMediaManager()" style="background: none; border: none; color: #94A3B8; font-size: 1.2rem; cursor: pointer;">✕</button>
                </div>
                <div style="padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: center;">
                    <label class="btn btn-primary btn-save-settings" style="cursor: pointer; padding: 0.6rem 1.2rem; font-size: 0.85rem;">
                        📁 Subir Nueva Imagen (PC)
                        <input type="file" style="display: none;" accept="image/*" id="mediaUploadFileInput" onchange="uploadMediaFile(this)">
                    </label>
                    <span style="font-size: 0.78rem; color: #94A3B8;">Haz clic en una imagen para elegirla</span>
                </div>
                <div id="media-grid" style="flex: 1; overflow-y: auto; padding: 1rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;" class="custom-scrollbar">
                    <!-- Renderizado dinámico desde JS -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        let currentStep = 1;
        let leafletMap = null;
        let leafletMarker = null;

        // DEFINICIÓN DE FUNCIONES AUXILIARES GLOBALES
        window.updateCertificateInput = function() {
            const input = document.getElementById('custom_ticket_input');
            if (input) {
                input.value = JSON.stringify(certState);
            }
        };

        function handleBannerUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('bannerPreviewImg');
                    const hidden = document.getElementById('event_banner');
                    if (preview) preview.src = e.target.result;
                    if (hidden) hidden.value = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleReferenceImageUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('referencePreviewImg');
                    const hidden = document.getElementById('reference_image');
                    const placeholder = document.getElementById('referencePlaceholderBox');
                    const container = document.getElementById('referencePreviewContainer');

                    if (preview) preview.src = e.target.result;
                    if (hidden) hidden.value = e.target.result;
                    if (placeholder) placeholder.style.display = 'none';
                    if (container) container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeReferenceImage() {
            const preview = document.getElementById('referencePreviewImg');
            const hidden = document.getElementById('reference_image');
            const placeholder = document.getElementById('referencePlaceholderBox');
            const container = document.getElementById('referencePreviewContainer');
            const fileInput = document.getElementById('referenceFileInput');

            if (preview) preview.src = '';
            if (hidden) hidden.value = '';
            if (fileInput) fileInput.value = '';
            if (placeholder) placeholder.style.display = 'block';
            if (container) container.style.display = 'none';
        }

        function initLeafletMap() {
            const mapEl = document.getElementById('interactiveLeafletMap');
            if (!mapEl) return;

            if (leafletMap) {
                leafletMap.invalidateSize();
                return;
            }

            const defaultLat = -13.1631;
            const defaultLng = -74.2236;

            try {
                leafletMap = L.map('interactiveLeafletMap').setView([defaultLat, defaultLng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(leafletMap);

                leafletMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(leafletMap);

                leafletMarker.on('dragend', function(e) {
                    const position = leafletMarker.getLatLng();
                    const coordsText = document.getElementById('mapCoordsText');
                    if (coordsText) {
                        coordsText.innerText = `${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}`;
                    }
                });

                leafletMap.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    leafletMarker.setLatLng([lat, lng]);
                    const coordsText = document.getElementById('mapCoordsText');
                    if (coordsText) {
                        coordsText.innerText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    }
                });
            } catch(err) {
                console.warn('Leaflet warning:', err);
            }
        }

        // ESTADO DE LA GALERÍA DE MEDIOS
        let mediaGallery = [
            'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=600&q=80'
        ];

        // MOTOR DEL DISEÑADOR INTERACTIVO (20.40 CM × 9.80 CM)
        let certState = { background: null, elements: [] };
        let selectedElementId = null;
        let currentZoom = 1.0;
        let isDragging = false;
        let dragOffset = { mouseX: 0, mouseY: 0, elX: 0, elY: 0 };
        let mediaContext = null;
        let quillEditor = null;

        function goToStep(step) {
            document.querySelectorAll('.step-content-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.stepper-step').forEach(s => s.classList.remove('active'));

            const targetPanel = document.getElementById('stepPanel' + step);
            const targetIndicator = document.getElementById('stepIndicator' + step);

            if (targetPanel) targetPanel.classList.add('active');
            if (targetIndicator) targetIndicator.classList.add('active');

            currentStep = step;

            if (step === 1 && !leafletMap) {
                setTimeout(initLeafletMap, 200);
            }
            if (step === 3) {
                setTimeout(() => {
                    if (!quillEditor && document.getElementById('cert-text-editor')) {
                        initQuillEditor();
                    }
                    renderCanvas();
                }, 150);
            }
            if (step === 4) {
                updateReviewSummary();
            }
        }

        function initQuillEditor() {
            quillEditor = new Quill('#cert-text-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'color': [] }],
                        ['clean']
                    ]
                }
            });

            quillEditor.on('text-change', function() {
                updateSelectedProp('content', quillEditor.root.innerHTML);
            });
        }

        function getFontFamily(k) {
            const f = {
                'font-lato': "'Lato', sans-serif",
                'font-montserrat': "'Montserrat', sans-serif",
                'font-opensans': "'Open Sans', sans-serif",
                'font-roboto': "'Roboto', sans-serif",
                'font-inter': "'Inter', sans-serif",
                'font-poppins': "'Poppins', sans-serif",
                'font-outfit': "'Outfit', sans-serif",
                'font-raleway': "'Raleway', sans-serif",
                'font-nunito': "'Nunito', sans-serif",
                'font-rubik': "'Rubik', sans-serif",
                'font-work-sans': "'Work Sans', sans-serif",
                'font-space-grotesk': "'Space Grotesk', sans-serif",
                'font-bebas': "'Bebas Neue', cursive",
                'font-oswald': "'Oswald', sans-serif",
                'font-anton': "'Anton', sans-serif",
                'font-righteous': "'Righteous', cursive",
                'font-syne': "'Syne', sans-serif",
                'font-merriweather': "'Merriweather', serif",
                'font-playfair': "'Playfair Display', serif",
                'font-cinzel': "'Cinzel', serif",
                'font-abril': "'Abril Fatface', serif",
                'font-dancing': "'Dancing Script', cursive",
                'font-greatvibes': "'Great Vibes', cursive",
                'font-pacifico': "'Pacifico', cursive",
                'font-satisfy': "'Satisfy', cursive",
                'font-caveat': "'Caveat', cursive",
                'font-lobster': "'Lobster', cursive",
                'font-permanent': "'Permanent Marker', cursive",
                'font-monoton': "'Monoton', cursive",
                'font-comfortaa': "'Comfortaa', cursive"
            };
            return f[k] || "'Inter', sans-serif";
        }

        function addElement(type, src = null, defaultText = null, defaultFontSize = '12px') {
            const id = 'el_' + Date.now();
            let contentText = defaultText || 'Texto';
            let initialSrc = src;

            if (type === 'logo' && !initialSrc) {
                initialSrc = '/images/logo.png';
            }
            if (type === 'banner' && !initialSrc) {
                initialSrc = 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=500&q=80';
            }

            let newEl = {
                id,
                type,
                src: initialSrc,
                x: 40 + (certState.elements.length * 15),
                y: 40 + (certState.elements.length * 15),
                content: contentText,
                style: {
                    fontFamily: 'font-montserrat',
                    fontSize: defaultFontSize || '12px',
                    color: '#000000',
                    fontWeight: 'normal',
                    fontStyle: 'normal',
                    textAlign: 'left',
                    width: type === 'banner' ? '240px' : (type === 'logo' ? '120px' : 'auto'),
                    height: type === 'banner' ? '80px' : 'auto',
                    rotation: 0,
                    objectFit: type === 'banner' ? 'cover' : 'contain'
                }
            };

            certState.elements.push(newEl);
            renderCanvas();
            selectElement(id);
        }

        function selectElement(id) {
            selectedElementId = id;
            const el = certState.elements.find(x => x.id === id);
            const elementsView = document.getElementById('sidebarElementsView');
            const inspectorView = document.getElementById('sidebarInspectorView');

            if (el && inspectorView && elementsView) {
                elementsView.style.display = 'none';
                inspectorView.style.display = 'flex';

                if (!el.style) el.style = {};

                // Cargar valores comunes en los inputs
                const rotInput = document.getElementById('prop-rotation');
                if (rotInput) rotInput.value = el.style.rotation !== undefined ? el.style.rotation : 0;

                const wInput = document.getElementById('prop-width');
                if (wInput) wInput.value = el.style.width && el.style.width !== 'auto' ? parseInt(el.style.width) : '';

                const hInput = document.getElementById('prop-height');
                if (hInput) hInput.value = el.style.height && el.style.height !== 'auto' ? parseInt(el.style.height) : '';

                // Estado de botones Bold e Italic
                const btnBold = document.getElementById('btnToggleBold');
                const btnItalic = document.getElementById('btnToggleItalic');

                if (btnBold) {
                    if (el.style.fontWeight === 'bold') {
                        btnBold.style.borderColor = '#FF5500';
                        btnBold.style.background = 'rgba(255, 85, 0, 0.25)';
                        btnBold.style.color = '#FFFFFF';
                    } else {
                        btnBold.style.borderColor = 'rgba(255,255,255,0.15)';
                        btnBold.style.background = 'rgba(255,255,255,0.04)';
                        btnBold.style.color = '#CBD5E1';
                    }
                }

                if (btnItalic) {
                    if (el.style.fontStyle === 'italic') {
                        btnItalic.style.borderColor = '#FF5500';
                        btnItalic.style.background = 'rgba(255, 85, 0, 0.25)';
                        btnItalic.style.color = '#FFFFFF';
                    } else {
                        btnItalic.style.borderColor = 'rgba(255,255,255,0.15)';
                        btnItalic.style.background = 'rgba(255,255,255,0.04)';
                        btnItalic.style.color = '#CBD5E1';
                    }
                }

                // Resaltar botón de alineación activo
                const currentAlign = el.style.textAlign || 'left';
                const alignMap = { 'left': 'alignBtnLeft', 'center': 'alignBtnCenter', 'right': 'alignBtnRight' };
                ['alignBtnLeft', 'alignBtnCenter', 'alignBtnRight'].forEach(btnId => {
                    const btn = document.getElementById(btnId);
                    if (btn) {
                        if (btnId === alignMap[currentAlign]) {
                            btn.style.borderColor = '#FF5500';
                            btn.style.background = 'rgba(255, 85, 0, 0.25)';
                            btn.style.color = '#FFFFFF';
                        } else {
                            btn.style.borderColor = 'rgba(255,255,255,0.15)';
                            btn.style.background = 'rgba(255,255,255,0.04)';
                            btn.style.color = '#CBD5E1';
                        }
                    }
                });

                const textCtrls = document.getElementById('textInspectorControls');
                const imgCtrls = document.getElementById('imageInspectorControls');
                const logoCtrls = document.getElementById('logoInspectorControls');
                const titleBadge = document.getElementById('selectedElementTypeTitle');

                if (el.type === 'logo') {
                    if (textCtrls) textCtrls.style.display = 'none';
                    if (imgCtrls) imgCtrls.style.display = 'none';
                    if (logoCtrls) logoCtrls.style.display = 'flex';

                    if (titleBadge) titleBadge.innerText = 'OPCIONES LOGO MARCA';

                    const cColor = document.getElementById('logoCardColor');
                    const cWhite = document.getElementById('logoCardWhite');
                    if (cColor && cWhite) {
                        if (el.src === '/images/logo-white.png') {
                            cColor.classList.remove('selected');
                            cWhite.classList.add('selected');
                        } else {
                            cWhite.classList.remove('selected');
                            cColor.classList.add('selected');
                        }
                    }
                } else if (el.type === 'banner' || el.type === 'image') {
                    if (textCtrls) textCtrls.style.display = 'none';
                    if (logoCtrls) logoCtrls.style.display = 'none';
                    if (imgCtrls) imgCtrls.style.display = 'flex';

                    if (titleBadge) {
                        titleBadge.innerText = el.type === 'banner' ? 'OPCIONES BANNER TICKET' : 'OPCIONES IMAGEN';
                    }

                    const prev = document.getElementById('inspectorImgPreview');
                    if (prev) {
                        prev.src = el.src || 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=400&q=80';
                    }

                    const fitSelect = document.getElementById('prop-object-fit');
                    if (fitSelect) {
                        fitSelect.value = el.style.objectFit || (el.type === 'banner' ? 'cover' : 'contain');
                    }
                } else {
                    if (imgCtrls) imgCtrls.style.display = 'none';
                    if (logoCtrls) logoCtrls.style.display = 'none';
                    if (textCtrls) textCtrls.style.display = 'flex';

                    if (titleBadge) titleBadge.innerText = 'OPCIONES DE TEXTO';

                    if (quillEditor && el.content) {
                        quillEditor.root.innerHTML = el.content;
                    }
                    const ff = document.getElementById('prop-font-family');
                    if (ff && el.style.fontFamily) ff.value = el.style.fontFamily;
                    const fs = document.getElementById('prop-font-size');
                    if (fs && el.style.fontSize) fs.value = parseInt(el.style.fontSize) || 12;
                    const tc = document.getElementById('prop-text-color');
                    if (tc && el.style.color) tc.value = el.style.color;
                }
            }

            renderCanvas();
        }

        function toggleBold() {
            if (!selectedElementId) return;
            const el = certState.elements.find(x => x.id === selectedElementId);
            if (!el) return;
            if (!el.style) el.style = {};
            el.style.fontWeight = (el.style.fontWeight === 'bold') ? 'normal' : 'bold';
            selectElement(selectedElementId);
        }

        function toggleItalic() {
            if (!selectedElementId) return;
            const el = certState.elements.find(x => x.id === selectedElementId);
            if (!el) return;
            if (!el.style) el.style = {};
            el.style.fontStyle = (el.style.fontStyle === 'italic') ? 'normal' : 'italic';
            selectElement(selectedElementId);
        }

        function setLogoVariant(logoPath) {
            if (!selectedElementId) return;
            const el = certState.elements.find(x => x.id === selectedElementId);
            if (el && el.type === 'logo') {
                el.src = logoPath;
                selectElement(selectedElementId);
            }
        }

        function deselectAll() {
            selectedElementId = null;
            const elementsView = document.getElementById('sidebarElementsView');
            const inspectorView = document.getElementById('sidebarInspectorView');
            if (elementsView) elementsView.style.display = 'flex';
            if (inspectorView) inspectorView.style.display = 'none';
            renderCanvas();
        }

        function updateSelectedProp(prop, val) {
            if (!selectedElementId) return;
            const el = certState.elements.find(x => x.id === selectedElementId);
            if (!el) return;
            if (!el.style) el.style = {};

            if (prop === 'fontSize') {
                el.style.fontSize = val + 'px';
            } else if (prop === 'content') {
                el.content = val;
            } else if (prop === 'width') {
                el.style.width = val ? val + 'px' : 'auto';
            } else if (prop === 'height') {
                el.style.height = val ? val + 'px' : 'auto';
            } else if (prop === 'rotation') {
                el.style.rotation = parseInt(val) || 0;
            } else if (prop === 'fontFamily' || prop === 'color' || prop === 'textAlign' || prop === 'objectFit') {
                el.style[prop] = val;
                if (prop === 'textAlign') selectElement(selectedElementId);
            }
            renderCanvas();
        }

        function rotateSelectedElement(deg) {
            if (!selectedElementId) return;
            const el = certState.elements.find(x => x.id === selectedElementId);
            if (!el) return;
            if (!el.style) el.style = {};
            el.style.rotation = deg;
            const rotInput = document.getElementById('prop-rotation');
            if (rotInput) rotInput.value = deg;
            renderCanvas();
        }

        function removeSelectedElement() {
            if (!selectedElementId) return;
            certState.elements = certState.elements.filter(e => e.id !== selectedElementId);
            deselectAll();
        }

        function renderCanvas() {
            const bgImg = document.getElementById('cert-background');
            const placeholder = document.getElementById('cert-background-placeholder');
            const layer = document.getElementById('elements-layer');

            if (certState.background) {
                bgImg.src = certState.background;
                bgImg.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            } else {
                bgImg.style.display = 'none';
                if (placeholder) placeholder.style.display = certState.elements.length > 0 ? 'none' : 'flex';
            }

            if (layer) {
                layer.innerHTML = '';
                certState.elements.forEach(el => {
                    const div = document.createElement('div');
                    div.id = el.id;
                    div.className = `cert-element ${selectedElementId === el.id ? 'selected' : ''}`;
                    div.style.left = el.x + 'px';
                    div.style.top = el.y + 'px';

                    if (el.style) {
                        div.style.fontFamily = getFontFamily(el.style.fontFamily);
                        div.style.fontSize = el.style.fontSize || '12px';
                        div.style.color = el.style.color || '#000000';
                        div.style.fontWeight = (el.style && el.style.fontWeight) ? el.style.fontWeight : 'normal';
                        div.style.fontStyle = (el.style && el.style.fontStyle) ? el.style.fontStyle : 'normal';
                        div.style.width = el.style.width || 'auto';
                        div.style.height = el.style.height || 'auto';

                        // CORRECCIÓN DE ALINEACIÓN DE TEXTO
                        if (el.type === 'qr' || el.type === 'logo' || el.type === 'banner' || el.type === 'image') {
                            div.style.display = 'flex';
                            div.style.alignItems = 'center';
                            div.style.justifyContent = 'center';
                        } else {
                            div.style.display = 'block';
                            const alignVal = (el.style && el.style.textAlign) ? el.style.textAlign : 'left';
                            div.style.textAlign = alignVal;
                        }

                        // APLICACIÓN DE TRANSFORMACIÓN DE ROTACIÓN
                        const rot = el.style.rotation || 0;
                        div.style.transform = `rotate(${rot}deg)`;
                        div.style.transformOrigin = 'center center';
                    }

                    if (el.type === 'qr') {
                        div.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=VIVEGO-SAMPLE-QR" style="width: 100%; height: 100%; object-fit: contain;">';
                    } else if (el.type === 'logo' || el.type === 'banner' || el.type === 'image') {
                        const defaultFallback = el.type === 'logo' ? '/images/logo.png' : 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=500&q=80';
                        const imgSrc = el.src || defaultFallback;
                        const fitMode = (el.style && el.style.objectFit) ? el.style.objectFit : (el.type === 'banner' ? 'cover' : 'contain');
                        div.innerHTML = `<img src="${imgSrc}" style="width: 100%; height: 100%; display: block; object-fit: ${fitMode};">`;
                    } else {
                        div.innerHTML = el.content || 'Etiqueta';
                    }

                    const hNW = document.createElement('div'); hNW.className = 'resize-handle handle-nw';
                    const hNE = document.createElement('div'); hNE.className = 'resize-handle handle-ne';
                    const hSW = document.createElement('div'); hSW.className = 'resize-handle handle-sw';
                    const hSE = document.createElement('div'); hSE.className = 'resize-handle handle-se';

                    hNW.onmousedown = (e) => startResize(e, el.id, 'nw');
                    hNE.onmousedown = (e) => startResize(e, el.id, 'ne');
                    hSW.onmousedown = (e) => startResize(e, el.id, 'sw');
                    hSE.onmousedown = (e) => startResize(e, el.id, 'se');

                    div.appendChild(hNW); div.appendChild(hNE); div.appendChild(hSW); div.appendChild(hSE);

                    div.onmousedown = (e) => {
                        e.stopPropagation();
                        selectElement(el.id);
                        startDrag(e, el.id);
                    };

                    layer.appendChild(div);
                });
            }

            if (typeof window.updateCertificateInput === 'function') {
                window.updateCertificateInput();
            }
        }

        function startResize(e, id, handleType) {
            e.stopPropagation();
            e.preventDefault();
            const el = certState.elements.find(x => x.id === id);
            if (!el) return;

            const startX = e.clientX;
            const startY = e.clientY;
            const targetDiv = document.getElementById(id);
            if (!targetDiv) return;

            const startWidth = targetDiv.offsetWidth;
            const startHeight = targetDiv.offsetHeight;

            document.onmousemove = (me) => {
                const dx = (me.clientX - startX) / currentZoom;
                const dy = (me.clientY - startY) / currentZoom;

                let newWidth = startWidth;
                let newHeight = startHeight;

                if (handleType.includes('e')) newWidth = Math.max(20, Math.round(startWidth + dx));
                if (handleType.includes('w')) newWidth = Math.max(20, Math.round(startWidth - dx));
                if (handleType.includes('s')) newHeight = Math.max(15, Math.round(startHeight + dy));
                if (handleType.includes('n')) newHeight = Math.max(15, Math.round(startHeight - dy));

                if (!el.style) el.style = {};
                el.style.width = newWidth + 'px';
                targetDiv.style.width = newWidth + 'px';

                if (el.type === 'logo' || el.type === 'banner' || el.type === 'image' || el.type === 'qr') {
                    el.style.height = newHeight + 'px';
                    targetDiv.style.height = newHeight + 'px';
                }

                // Actualizar inputs del inspector si está seleccionado
                const wInput = document.getElementById('prop-width');
                if (wInput) wInput.value = newWidth;
                const hInput = document.getElementById('prop-height');
                if (hInput) hInput.value = newHeight;
            };

            document.onmouseup = () => {
                document.onmousemove = null;
                document.onmouseup = null;
                renderCanvas();
            };
        }

        function startDrag(e, id) {
            e.stopPropagation();
            isDragging = true;
            const el = certState.elements.find(x => x.id === id);
            if (!el) return;

            const targetDiv = document.getElementById(id);
            if (!targetDiv) return;

            dragOffset.mouseX = e.clientX;
            dragOffset.mouseY = e.clientY;
            dragOffset.elX = el.x;
            dragOffset.elY = el.y;

            document.onmousemove = (me) => {
                if (!isDragging) return;
                const dx = (me.clientX - dragOffset.mouseX) / currentZoom;
                const dy = (me.clientY - dragOffset.mouseY) / currentZoom;

                const newX = Math.max(0, Math.min(771 - targetDiv.offsetWidth, Math.round(dragOffset.elX + dx)));
                const newY = Math.max(0, Math.min(370 - targetDiv.offsetHeight, Math.round(dragOffset.elY + dy)));

                el.x = newX;
                el.y = newY;

                targetDiv.style.left = newX + 'px';
                targetDiv.style.top = newY + 'px';
            };

            document.onmouseup = () => {
                if (isDragging) {
                    isDragging = false;
                    document.onmousemove = null;
                    document.onmouseup = null;
                    if (typeof window.updateCertificateInput === 'function') {
                        window.updateCertificateInput();
                    }
                }
            };
        }

        function zoomCanvas(delta) {
            currentZoom = Math.max(0.5, Math.min(1.5, currentZoom + delta));
            const canvas = document.getElementById('cert-canvas');
            if (canvas) {
                canvas.style.transform = `scale(${currentZoom})`;
            }
            const ind = document.getElementById('zoom-level-indicator');
            if (ind) ind.innerText = Math.round(currentZoom * 100) + '%';
        }

        function fitCanvas() {
            currentZoom = 1.0;
            const canvas = document.getElementById('cert-canvas');
            if (canvas) canvas.style.transform = 'scale(1)';
            const ind = document.getElementById('zoom-level-indicator');
            if (ind) ind.innerText = '100%';
        }

        /* GESTIÓN DE GALERÍA DE MEDIOS DIVERSIFICADA Y PERSISTENTE */
        function fetchMediaGallery(callback) {
            fetch("{{ route('web.media.index') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.success && Array.isArray(data.media)) {
                        mediaGallery = data.media;
                        renderMediaGrid();
                    }
                    if (typeof callback === 'function') callback();
                })
                .catch(err => {
                    console.warn('Error al cargar medios:', err);
                    renderMediaGrid();
                });
        }

        function openMediaManager(ctx) {
            mediaContext = ctx;
            fetchMediaGallery(() => {
                const modal = document.getElementById('media-modal');
                if (modal) modal.style.display = 'block';
            });
        }

        function closeMediaManager() {
            const modal = document.getElementById('media-modal');
            if (modal) modal.style.display = 'none';
        }

        function getMediaFileName(u) {
            if (!u) return '';
            try {
                const parts = u.split('/');
                return parts[parts.length - 1].split('?')[0].split('#')[0];
            } catch(e) {
                return u;
            }
        }

        function renderMediaGrid() {
            const grid = document.getElementById('media-grid');
            if (!grid) return;
            grid.innerHTML = '';

            let currentActiveUrl = null;
            if (mediaContext === 'background') {
                currentActiveUrl = certState.background;
            } else if (mediaContext === 'event_banner') {
                currentActiveUrl = document.getElementById('event_banner')?.value || document.getElementById('bannerPreviewImg')?.src;
            } else if (mediaContext === 'reference_image') {
                currentActiveUrl = document.getElementById('reference_image')?.value || document.getElementById('referencePreviewImg')?.src;
            } else if (mediaContext === 'selected_element_image') {
                if (selectedElementId) {
                    const el = certState.elements.find(x => x.id === selectedElementId);
                    if (el) currentActiveUrl = el.src;
                }
            }

            const activeFilename = getMediaFileName(currentActiveUrl);

            mediaGallery.forEach((url, index) => {
                const itemFilename = getMediaFileName(url);
                const isSelected = activeFilename && itemFilename && (activeFilename === itemFilename || url === currentActiveUrl);

                const card = document.createElement('div');
                card.style.cssText = `position: relative; aspect-ratio: 16/9; background: #000; border-radius: 12px; overflow: hidden; border: ${isSelected ? '3px solid #FF5500' : '1.5px solid rgba(255,255,255,0.15)'}; cursor: pointer; transition: transform 0.2s ease, border-color 0.2s ease; ${isSelected ? 'box-shadow: 0 0 15px rgba(255, 85, 0, 0.6);' : ''}`;
                card.onmouseover = () => { if (!isSelected) card.style.borderColor = '#FF5500'; };
                card.onmouseout = () => { if (!isSelected) card.style.borderColor = 'rgba(255,255,255,0.15)'; };

                const selectedBadge = isSelected ? '<div style="position: absolute; top: 6px; left: 6px; background: #FF5500; color: #FFFFFF; font-size: 10px; font-weight: 900; padding: 2px 7px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.6); z-index: 15; letter-spacing: 0.5px;">✓ SELECCIONADO</div>' : '';

                card.innerHTML = `
                    ${selectedBadge}
                    <img src="${url}" style="width: 100%; height: 100%; object-fit: cover;" onclick="selectMediaItem('${url}')">
                    <button type="button" onclick="confirmDeleteMediaItem(event, ${index})" style="position: absolute; top: 6px; right: 6px; background: rgba(239, 68, 68, 0.9); color: #FFFFFF; border: none; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 900; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.5); z-index: 20; transition: transform 0.15s ease;" title="Eliminar Imagen de la Galería">✕</button>
                `;
                grid.appendChild(card);
            });
        }

        function selectMediaItem(url) {
            if (mediaContext === 'background') {
                certState.background = url;
            } else if (mediaContext === 'event_banner') {
                const preview = document.getElementById('bannerPreviewImg');
                const input = document.getElementById('event_banner');
                if (preview) preview.src = url;
                if (input) input.value = url;
            } else if (mediaContext === 'reference_image') {
                const preview = document.getElementById('referencePreviewImg');
                const input = document.getElementById('reference_image');
                const placeholder = document.getElementById('referencePlaceholderBox');
                const container = document.getElementById('referencePreviewContainer');

                if (preview) preview.src = url;
                if (input) input.value = url;
                if (placeholder) placeholder.style.display = 'none';
                if (container) container.style.display = 'block';
            } else if (mediaContext === 'selected_element_image') {
                if (selectedElementId) {
                    const el = certState.elements.find(x => x.id === selectedElementId);
                    if (el) {
                        el.src = url;
                        const prev = document.getElementById('inspectorImgPreview');
                        if (prev) prev.src = url;
                    }
                }
            } else {
                addElement('image', url);
            }
            renderCanvas();
            closeMediaManager();
        }

        function uploadMediaFile(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const formData = new FormData();
                formData.append('file', file);

                Swal.fire({
                    title: 'Subiendo Imagen...',
                    text: 'Guardando en la carpeta del proyecto...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); },
                    background: '#14141E',
                    color: '#FFFFFF'
                });

                fetch("{{ route('web.media.upload') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.url) {
                        fetchMediaGallery(() => {
                            Swal.fire({
                                title: '¡Imagen Guardada!',
                                text: 'La imagen se subió exitosamente a la carpeta del proyecto.',
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                        });
                    } else {
                        Swal.fire({
                            title: 'Error de Subida',
                            text: data.message || 'No se pudo subir la imagen.',
                            icon: 'error',
                            background: '#14141E',
                            color: '#FFFFFF'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Error de Red',
                        text: 'Ocurrió un error al enviar el archivo.',
                        icon: 'error',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                })
                .finally(() => {
                    input.value = '';
                });
            }
        }

        function confirmDeleteMediaItem(e, index) {
            e.stopPropagation();
            const targetUrl = mediaGallery[index];

            Swal.fire({
                title: '¿Eliminar imagen?',
                text: 'Esta imagen se eliminará permanentemente de tu biblioteca de medios.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('web.media.delete') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ url: targetUrl })
                    })
                    .then(() => {
                        mediaGallery.splice(index, 1);
                        renderMediaGrid();
                        Swal.fire({
                            title: 'Eliminada',
                            text: 'La imagen fue removida de la galería.',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#14141E',
                            color: '#FFFFFF'
                        });
                    });
                }
            });
        }

        function handleTagKeydown(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = e.target.value.trim();
                if (val) {
                    const tagText = val.startsWith('#') ? val : '#' + val;
                    const chip = document.createElement('span');
                    chip.className = 'tag-chip';
                    chip.innerHTML = `${tagText} <button type="button" onclick="this.parentElement.remove()">✕</button>`;
                    document.getElementById('tagsWrapper').insertBefore(chip, e.target);
                    e.target.value = '';
                }
            }
        }

        function addDynamicZoneRow() {
            const tbody = document.getElementById('zonesTableBody');
            const row = document.createElement('tr');
            row.className = 'zone-row';
            row.innerHTML = `
                <td>
                    <select class="form-select-custom zone-capacity-type" style="font-size: 0.85rem; padding: 0.55rem;">
                        <option value="Aforo VIP">🏟️ Aforo VIP</option>
                        <option value="Aforo Preferencial">🏟️ Aforo Preferencial</option>
                        <option value="Aforo General" selected>🏟️ Aforo General</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-input-custom zone-name-input" value="NUEVA ZONA" style="font-size: 0.85rem; padding: 0.55rem;">
                </td>
                <td>
                    <input type="number" class="form-input-custom zone-capacity-input" value="100" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="recalculateTotalCapacity()">
                </td>
                <td>
                    <input type="number" step="0.50" class="form-input-custom zone-price-input" value="50.00" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                </td>
            `;
            tbody.appendChild(row);
            recalculateTotalCapacity();
        }

        function removeZoneRow(btn) {
            const row = btn.closest('tr');
            if (document.querySelectorAll('#zonesTableBody .zone-row').length > 1) {
                row.remove();
                recalculateTotalCapacity();
            } else {
                Swal.fire({
                    title: 'Atención',
                    text: 'Debes mantener al menos una zona configurada.',
                    icon: 'warning',
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }
        }

        function recalculateTotalCapacity() {
            let total = 0;
            let minPrice = Infinity;
            document.querySelectorAll('.zone-row').forEach(row => {
                const cap = parseInt(row.querySelector('.zone-capacity-input').value) || 0;
                const price = parseFloat(row.querySelector('.zone-price-input').value) || 0;
                total += cap;
                if (price < minPrice) minPrice = price;
            });
            if (minPrice === Infinity) minPrice = 0;

            const capEl = document.getElementById('totalCapacitySummaryText');
            if (capEl) capEl.innerText = total.toLocaleString() + ' entradas';
            const priceEl = document.getElementById('minPriceSummaryText');
            if (priceEl) priceEl.innerText = 'S/ ' + minPrice.toFixed(2);
        }

        function updateReviewSummary() {
            const title = document.getElementById('event_title').value || 'Sin Título';
            const cat = document.getElementById('event_category').value;
            const comp = document.getElementById('event_company').value;
            const date = document.getElementById('event_date_picker')?.value || document.getElementById('event_date')?.value;
            const time = document.getElementById('event_time_picker')?.value || document.getElementById('event_time')?.value;
            const venue = document.getElementById('event_venue').value;

            const rTitle = document.getElementById('reviewTitle'); if (rTitle) rTitle.innerText = title;
            const rCatComp = document.getElementById('reviewCategoryCompany'); if (rCatComp) rCatComp.innerText = `${cat} — ${comp}`;
            const rDT = document.getElementById('reviewDateTimeVenue'); if (rDT) rDT.innerText = `${date} - ${time} hrs | ${venue}`;
            const rCap = document.getElementById('reviewCapacity'); if (rCap && document.getElementById('totalCapacitySummaryText')) rCap.innerText = document.getElementById('totalCapacitySummaryText').innerText;
        }

        function submitMainEventForm() {
            const title = document.getElementById('event_title').value;
            if (!title) {
                Swal.fire('Atención', 'Ingresa el nombre del evento.', 'warning');
                goToStep(1);
                return;
            }

            const zones = [];
            document.querySelectorAll('.zone-row').forEach(row => {
                zones.push({
                    capacity_type: row.querySelector('.zone-capacity-type').value,
                    name: row.querySelector('.zone-name-input').value,
                    capacity: parseInt(row.querySelector('.zone-capacity-input').value) || 0,
                    price: parseFloat(row.querySelector('.zone-price-input').value) || 0
                });
            });

            const tags = [];
            document.querySelectorAll('#tagsWrapper .tag-chip').forEach(c => {
                tags.push(c.innerText.replace('✕', '').trim());
            });

            const coordsText = document.getElementById('mapCoordsText').innerText;
            const coords = coordsText.split(',');
            const lat = coords[0] ? parseFloat(coords[0].trim()) : -13.1631;
            const lng = coords[1] ? parseFloat(coords[1].trim()) : -74.2236;

            const salesType = document.querySelector('input[name="event_sales_type"]:checked')?.value || 'fisica';

            const payload = {
                title: title,
                category_name: document.getElementById('event_category').value,
                company_name: document.getElementById('event_company').value,
                banner_image: document.getElementById('event_banner').value,
                reference_image: document.getElementById('reference_image')?.value || null,
                event_date: document.getElementById('event_date_picker')?.value || document.getElementById('event_date').value,
                event_time: document.getElementById('event_time_picker')?.value || document.getElementById('event_time').value,
                venue_name: document.getElementById('event_venue').value,
                address: document.getElementById('event_address').value,
                latitude: lat,
                longitude: lng,
                description: document.getElementById('event_details').value,
                tags: tags,
                zones: zones,
                sales_type: salesType,
                custom_ticket: certState
            };

            Swal.showLoading();

            fetch("{{ route('web.events.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Evento Principal Publicado!',
                        text: data.message || 'El espectáculo y su boleto se guardaron exitosamente.',
                        icon: 'success',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then(() => {
                        window.location.href = "{{ route('web.events') }}";
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar el evento.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Ocurrió un error al comunicar con el servidor.', 'error');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initLeafletMap();
        });
    </script>
@endpush
