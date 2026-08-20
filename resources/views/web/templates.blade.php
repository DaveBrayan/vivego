@extends('layouts.app')

@push('styles')
    <!-- FUENTES GOOGLE FONTS PARA EL EDITOR DE PLANTILLAS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600;800;900&family=Montserrat:wght@400;700;900&family=Oswald:wght@500;700&family=Outfit:wght@400;700;900&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Poppins:wght@400;700;900&family=Roboto:wght@400;700;900&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        .swal2-container {
            z-index: 999999 !important;
        }

        /* PESTAÑAS MODERNAS PRO MAX */
        .templates-nav-tabs {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.04);
            padding: 0.4rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 1.75rem;
            width: fit-content;
        }

        .template-tab-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1.4rem;
            border-radius: 12px;
            border: none;
            background: transparent;
            color: #94A3B8;
            font-size: 0.925rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .template-tab-btn:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.06);
        }

        .template-tab-btn.active {
            background: linear-gradient(135deg, #FF5500 0%, #FF7733 100%);
            color: #FFFFFF;
            box-shadow: 0 6px 20px rgba(255, 85, 0, 0.35);
        }

        .template-tab-badge {
            background: rgba(255, 255, 255, 0.2);
            color: #FFFFFF;
            font-size: 0.75rem;
            font-weight: 900;
            padding: 0.15rem 0.55rem;
            border-radius: 20px;
        }

        .template-tab-btn:not(.active) .template-tab-badge {
            background: rgba(255, 255, 255, 0.08);
            color: #94A3B8;
        }

        /* BARRA SUPERIOR DE FORMATO ESTILO CANVA STUDIO PRO (FIJA ARRIBA DEL LIENZO) */
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

        /* ETIQUETAS Y ELEMENTOS DRAGGABLE 100% LIBRES CON CAJA CONTENEDORA */
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
        /* BORDE ÚNICO ELEGANTE Y ULTRA-LIMPIO ESTILO CANVA / FIGMA */
        .canva-drag-element.selected-element {
            outline: 1.5px solid #FF5500 !important;
            outline-offset: 0px !important;
            border-radius: 3px;
        }

        /* 4 PUNTOS DE CONTROL EN LAS ESQUINAS ESTILO CANVA / FIGMA */
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

        /* LIENZO OFICIAL CANVA 20.40CM X 9.80CM (ESCALA EXACTA: 771PX X 370PX) */
        .canva-official-canvas {
            position: relative;
            width: 771px;
            height: 370px;
            border-radius: 18px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.85);
            overflow: hidden;
            background: #FFFFFF;
            transition: background 0.3s ease;
            margin: 0 auto;
        }

        /* DIMENSIÓN BADGE */
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

        /* TIPO SELECTOR EN MODAL */
        .modal-type-selector {
            display: flex;
            background: rgba(255, 255, 255, 0.06);
            padding: 3px;
            border-radius: 10px;
            gap: 3px;
        }
        .modal-type-btn {
            border: none;
            background: transparent;
            color: #94A3B8;
            font-size: 0.785rem;
            font-weight: 800;
            padding: 0.35rem 0.85rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .modal-type-btn.active {
            background: #FF5500;
            color: #FFFFFF;
        }
    </style>
@endpush

@section('title', 'Plantillas de Boletos Físicos y Virtuales | Vive Go')

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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar plantilla de boleto física o virtual...">
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
                        <span class="settings-tag">🎨 ESTUDIO DE DISEÑO CANVA • 20.40cm × 9.80cm</span>
                        <h1 class="settings-page-title">Plantillas de Boletos</h1>
                        <p class="settings-page-subtitle">Diseña boletos físicos para taquilla y entradas virtuales con imágenes de fondo limpias y etiquetas con transform libre.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" id="btnOpenCanvaStudioModal" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem;">
                            ➕ Crear Nueva Plantilla Canva
                        </button>
                    </div>
                </div>

                <!-- PESTAÑAS: FÍSICAS vs VIRTUALES -->
                <div class="templates-nav-tabs">
                    <button type="button" class="template-tab-btn active" id="tabBtnPhysical" onclick="switchTemplateTab('physical')">
                        <span>🎟️ Boletos Físicos (Impresos)</span>
                        <span class="template-tab-badge" id="badgeCountPhysical">{{ $physicalTemplates->count() }}</span>
                    </button>
                    <button type="button" class="template-tab-btn" id="tabBtnVirtual" onclick="switchTemplateTab('virtual')">
                        <span>📱 Boletos Virtuales (E-Tickets)</span>
                        <span class="template-tab-badge" id="badgeCountVirtual">{{ $virtualTemplates->count() }}</span>
                    </button>
                </div>

                <!-- GRID 1: PLANTILLAS FÍSICAS -->
                <div id="tabContentPhysical">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;" id="physicalTemplatesGrid">
                        @forelse($physicalTemplates as $tpl)
                            <div class="settings-card-box template-item-card" data-type="fisica" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.25rem;" id="templateCard_{{ $tpl->id }}">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                                        <span class="dash-badge-custom badge-orange" style="font-size: 0.775rem;">
                                            🎟️ {{ $tpl->category }}
                                        </span>
                                    </div>

                                    <!-- THUMBNAIL REALISTA CON PROPORCIÓN 20.40CM X 9.80CM (FONDO LIMPIO) -->
                                    <div style="position: relative; height: 160px; border-radius: 14px; overflow: hidden; background-color: {{ $tpl->bg_color }}; {{ $tpl->bg_image ? "background-image: url('".(str_starts_with($tpl->bg_image, 'http') || str_starts_with($tpl->bg_image, 'storage/') ? asset($tpl->bg_image) : asset($tpl->bg_image))."'); background-size: cover; background-position: center;" : "" }} border: 1.5px solid rgba(255,255,255,0.15); display: flex; margin-bottom: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.25);">
                                        @if($tpl->id == 1 || str_contains($tpl->category, 'Logo Izquierda'))
                                            <!-- ESTRUCTURA 1: TAQUILLA CLÁSICA OFICIAL CON FRANJA DE LOGO A LA IZQUIERDA -->
                                            <div style="width: 36px; background: {{ $tpl->strip_color }}; display: flex; align-items: center; justify-content: center; position: relative; z-index: 2;">
                                                <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="height: 22px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 6px rgba(255,85,0,0.6));">
                                            </div>
                                            <div style="flex: 1; padding: 0.85rem; display: flex; flex-direction: column; justify-content: space-between; color: #000000; position: relative; z-index: 2;">
                                                <div style="display: flex; justify-content: space-between;">
                                                    <span style="font-weight: 900; font-size: 0.75rem;">SON DEL DUKE</span>
                                                    <span style="font-weight: 900; font-size: 0.75rem;">S/ 55.50</span>
                                                </div>
                                                <div style="height: 45px; background: rgba(0,0,0,0.06); border-radius: 8px;"></div>
                                                <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 700;">
                                                    <span>AYACUCHO</span>
                                                    <span>10.04.2025</span>
                                                </div>
                                            </div>
                                            <div style="width: 75px; background: #FAFAFA; border-left: 1px dashed #CBD5E1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem; position: relative; z-index: 2;">
                                                <span style="font-size: 0.6rem; font-weight: 900; color: #000000;">N° 00396</span>
                                                <div style="width: 35px; height: 35px; background: #000000; border-radius: 4px;"></div>
                                            </div>
                                        @elseif($tpl->id == 2 || str_contains($tpl->category, 'Logo Derecho'))
                                            <!-- ESTRUCTURA 2: TAQUILLA CON FRANJA DEL LOGO A LA DERECHA (STUB A LA IZQUIERDA) -->
                                            <div style="width: 75px; background: #FAFAFA; border-right: 1px dashed #CBD5E1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem; position: relative; z-index: 2;">
                                                <span style="font-size: 0.6rem; font-weight: 900; color: #000000;">N° 00396</span>
                                                <div style="width: 35px; height: 35px; background: #000000; border-radius: 4px;"></div>
                                            </div>
                                            <div style="flex: 1; padding: 0.85rem; display: flex; flex-direction: column; justify-content: space-between; color: #000000; position: relative; z-index: 2;">
                                                <div style="display: flex; justify-content: space-between;">
                                                    <span style="font-weight: 900; font-size: 0.75rem;">SON DEL DUKE</span>
                                                    <span style="font-weight: 900; font-size: 0.75rem;">S/ 55.50</span>
                                                </div>
                                                <div style="height: 45px; background: rgba(0,0,0,0.06); border-radius: 8px;"></div>
                                                <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 700;">
                                                    <span>AYACUCHO</span>
                                                    <span>10.04.2025</span>
                                                </div>
                                            </div>
                                            <div style="width: 36px; background: {{ $tpl->strip_color }}; display: flex; align-items: center; justify-content: center; position: relative; z-index: 2;">
                                                <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="height: 22px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 6px rgba(255,85,0,0.6));">
                                            </div>
                                        @else
                                            <!-- ESTRUCTURA 3: HERO BANNER PANORÁMICO SUPERIOR -->
                                            <div style="flex: 1; padding: 0.75rem; display: flex; flex-direction: column; justify-content: space-between; color: #FFFFFF; position: relative; z-index: 2;">
                                                <div style="height: 45px; background: rgba(255,255,255,0.18); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 900; color: #EAB308;">🖼️ BANNER PANORÁMICO 620PX</div>
                                                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                                    <div>
                                                        <span style="font-weight: 900; font-size: 0.75rem; display: block;">SON DEL DUKE</span>
                                                        <span style="font-weight: 800; font-size: 0.65rem; color: #EAB308;">S/ 55.50 • ZONA VIP</span>
                                                    </div>
                                                    <div style="width: 45px; height: 45px; background: #000000; border-radius: 6px; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 0.5rem; font-weight: 900; color: #EAB308;">📲 QR</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <h3 style="font-size: 1.1rem; font-weight: 900; color: var(--text-color); margin: 0 0 0.25rem 0;">{{ $tpl->name }}</h3>
                                    <p style="font-size: 0.825rem; color: #94A3B8; margin: 0 0 1rem 0;">ID: #{{ $tpl->id }} • Dimensión: 20.40 × 9.80 cm • {{ $tpl->created_at->format('d/m/Y') }}</p>
                                </div>

                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.65rem; font-size: 0.825rem; text-align: center; justify-content: center;" onclick="openCanvaStudioForEdit({{ $tpl->id }}, '{{ addslashes($tpl->name) }}', 'fisica', '{{ $tpl->bg_color }}', '{{ $tpl->strip_color }}', {{ json_encode($tpl->positions ?? []) }}, '{{ addslashes($tpl->bg_image ?? '') }}')">
                                        🎨 Editar en Canva
                                    </button>
                                    <button type="button" class="dash-btn-icon-action" style="padding: 0.65rem;" title="Duplicar Plantilla" onclick="duplicateTemplateInDb({{ $tpl->id }}, '{{ addslashes($tpl->name) }}', 'fisica', '{{ addslashes($tpl->bg_image ?? '') }}')">
                                        📋
                                    </button>
                                    <button type="button" class="dash-btn-icon-action btn-delete-action" style="padding: 0.65rem; color: #EF4444;" title="Eliminar Plantilla" onclick="deleteTemplateFromDb({{ $tpl->id }}, '{{ addslashes($tpl->name) }}')">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; background: rgba(255,255,255,0.02); border-radius: 18px; border: 1px dashed rgba(255,255,255,0.1);">
                                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎟️</div>
                                <h3 style="font-size: 1.1rem; color: #FFF; font-weight: 800;">No hay plantillas físicas registradas</h3>
                                <p style="font-size: 0.85rem; color: #94A3B8;">Crea una nueva plantilla para boletos impresos y taquilla física.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- GRID 2: PLANTILLAS VIRTUALES (E-TICKETS DIGITALES CON IMAGEN DE FONDO LIMPIA) -->
                <div id="tabContentVirtual" style="display: none;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;" id="virtualTemplatesGrid">
                        @forelse($virtualTemplates as $tpl)
                            <div class="settings-card-box template-item-card" data-type="virtual" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.25rem;" id="templateCard_{{ $tpl->id }}">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                                        <span class="dash-badge-custom" style="background: rgba(6, 182, 212, 0.15); color: #06B6D4; border: 1px solid rgba(6, 182, 212, 0.35); font-size: 0.775rem;">
                                            📱 {{ $tpl->category }}
                                        </span>
                                        @if($tpl->bg_image)
                                            <span class="dash-badge-custom" style="background: rgba(255, 85, 0, 0.15); color: #FF5500; border: 1px solid rgba(255, 85, 0, 0.3); font-size: 0.7rem;">
                                                🖼️ Fondo Personalizado
                                            </span>
                                        @endif
                                    </div>

                                    <!-- THUMBNAIL REALISTA DIGITAL VIRTUAL CON IMAGEN DE FONDO 100% LIMPIA (20.40CM X 9.80CM) -->
                                    <div style="position: relative; height: 160px; border-radius: 14px; overflow: hidden; background-color: {{ $tpl->bg_color }}; {{ $tpl->bg_image ? "background-image: url('".(str_starts_with($tpl->bg_image, 'http') || str_starts_with($tpl->bg_image, 'storage/') ? asset($tpl->bg_image) : asset($tpl->bg_image))."'); background-size: cover; background-position: center;" : "" }} border: 1.5px solid rgba(6, 182, 212, 0.35); display: flex; margin-bottom: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                                        
                                        <div style="flex: 1; padding: 0.85rem; display: flex; flex-direction: column; justify-content: space-between; color: #FFFFFF; position: relative; z-index: 2;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                                    <span style="font-size: 0.65rem; background: {{ $tpl->strip_color }}; color: #FFF; padding: 2px 6px; border-radius: 4px; font-weight: 900;">VIRTUAL PASS</span>
                                                    <span style="font-weight: 900; font-size: 0.75rem; text-shadow: 0 2px 4px rgba(0,0,0,0.9);">SON DEL DUKE</span>
                                                </div>
                                                <span style="font-weight: 900; font-size: 0.8rem; color: {{ $tpl->strip_color }}; text-shadow: 0 2px 4px rgba(0,0,0,0.9);">S/ 55.50</span>
                                            </div>
                                            <div style="height: 48px; background: rgba(0,0,0,0.35); border-radius: 8px; border: 1px dashed rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: #FFFFFF; text-shadow: 0 2px 4px rgba(0,0,0,0.9);">
                                                📲 Entrada Digital con Validación QR en Tiempo Real
                                            </div>
                                            <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 700; color: #FFFFFF; text-shadow: 0 2px 4px rgba(0,0,0,0.9);">
                                                <span>AYACUCHO • 10.04.2025</span>
                                                <span style="color: #06B6D4;">N° 00396</span>
                                            </div>
                                        </div>
                                        <div style="width: 80px; background: rgba(0,0,0,0.45); border-left: 1px dashed rgba(255,255,255,0.25); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.3rem; position: relative; z-index: 2;">
                                            <div style="width: 44px; height: 44px; background: #FFFFFF; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.55rem; font-weight: 900; color: #000;">QR</div>
                                            <span style="font-size: 0.55rem; color: #FFFFFF; font-family: monospace; text-shadow: 0 1px 2px #000;">E-TICKET</span>
                                        </div>
                                    </div>

                                    <h3 style="font-size: 1.1rem; font-weight: 900; color: var(--text-color); margin: 0 0 0.25rem 0;">{{ $tpl->name }}</h3>
                                    <p style="font-size: 0.825rem; color: #94A3B8; margin: 0 0 1rem 0;">ID: #{{ $tpl->id }} • Dimensión: 20.40 × 9.80 cm • {{ $tpl->created_at->format('d/m/Y') }}</p>
                                </div>

                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.65rem; font-size: 0.825rem; text-align: center; justify-content: center;" onclick="openCanvaStudioForEdit({{ $tpl->id }}, '{{ addslashes($tpl->name) }}', 'virtual', '{{ $tpl->bg_color }}', '{{ $tpl->strip_color }}', {{ json_encode($tpl->positions ?? []) }}, '{{ addslashes($tpl->bg_image ?? '') }}')">
                                        🎨 Editar en Canva
                                    </button>
                                    <button type="button" class="dash-btn-icon-action" style="padding: 0.65rem;" title="Duplicar Plantilla" onclick="duplicateTemplateInDb({{ $tpl->id }}, '{{ addslashes($tpl->name) }}', 'virtual', '{{ addslashes($tpl->bg_image ?? '') }}')">
                                        📋
                                    </button>
                                    <button type="button" class="dash-btn-icon-action btn-delete-action" style="padding: 0.65rem; color: #EF4444;" title="Eliminar Plantilla" onclick="deleteTemplateFromDb({{ $tpl->id }}, '{{ addslashes($tpl->name) }}')">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; background: rgba(255,255,255,0.02); border-radius: 18px; border: 1px dashed rgba(255,255,255,0.1);">
                                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📱</div>
                                <h3 style="font-size: 1.1rem; color: #FFF; font-weight: 800;">No hay plantillas virtuales registradas</h3>
                                <p style="font-size: 0.85rem; color: #94A3B8;">Crea una nueva plantilla para entradas virtuales y pases móviles.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL CANVA STUDIO (EDITOR INTERACTIVO DRAG & DROP 100% LIBRE • 20.40CM X 9.80CM) -->
    <div class="admin-modal-overlay" id="canvaStudioModal" style="align-items: center; padding: 1rem;">
        <div class="admin-modal-card" style="width: 96vw; max-width: 1440px; height: 94vh; display: flex; flex-direction: column; padding: 0; background: #0B0B12; border: 1.5px solid rgba(255,255,255,0.15); border-radius: 24px; overflow: hidden;">
            
            <!-- HEADER DEL CANVA STUDIO -->
            <div style="padding: 1rem 1.75rem; background: #14141E; border-bottom: 1.5px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="card-header-icon" style="width: 42px; height: 42px; background: linear-gradient(135deg, #06B6D4, #FF5500); color: #FFFFFF; font-size: 1.25rem;">🎨</div>
                    <div>
                        <input type="hidden" id="canvaTemplateId" value="">
                        <input type="hidden" id="canvaTemplateType" value="fisica">
                        
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <input type="text" id="canvaTemplateNameInput" style="background: transparent; border: none; font-weight: 900; font-size: 1.15rem; color: #FFFFFF; outline: none; border-bottom: 1px dashed rgba(255,255,255,0.3); min-width: 280px;" value="Nueva Plantilla Canva 2026">
                            
                            <!-- SELECTOR DE TIPO (FÍSICA / VIRTUAL) -->
                            <div class="modal-type-selector">
                                <button type="button" class="modal-type-btn active" id="modalTypeBtnPhysical" onclick="setModalTemplateType('fisica')">🎟️ Física</button>
                                <button type="button" class="modal-type-btn" id="modalTypeBtnVirtual" onclick="setModalTemplateType('virtual')">📱 Virtual</button>
                            </div>
                        </div>
                        <p style="font-size: 0.775rem; color: #94A3B8; margin: 2px 0 0 0;">Lienzo Oficial: 20.40 cm × 9.80 cm • Arrastre libre con etiquetas contenedoras y fondo limpio</p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span class="canvas-dimension-badge">
                        📐 20.40 cm × 9.80 cm (Proporción Oficial)
                    </span>
                    <button type="button" class="btn btn-cancel-custom" onclick="resetCanvaPositions(true)" style="padding: 0.6rem 1.2rem; font-size: 0.85rem;">
                        🔄 Restablecer
                    </button>
                    <button type="button" class="btn btn-primary btn-save-settings" onclick="saveCanvaTemplateToDb()" style="padding: 0.65rem 1.5rem; font-size: 0.9rem;">
                        💾 Guardar Cambios
                    </button>
                    <button class="admin-modal-close" id="btnCloseCanvaStudioModal" style="position: static;">✕</button>
                </div>
            </div>

            <!-- CUERPO PRINCIPAL DEL ESTUDIO CANVA (2 COLUMNAS) -->
            <div style="flex: 1; display: grid; grid-template-columns: 330px 1fr; overflow: hidden;">
                
                <!-- COLUMNA IZQUIERDA: HERRAMIENTAS, ETIQUETAS DINÁMICAS & CAPAS DE CANVA -->
                <div style="background: #14141E; border-right: 1.5px solid rgba(255,255,255,0.1); padding: 1.25rem; display: flex; flex-direction: column; gap: 1.25rem; overflow-y: auto;">
                    
                    <!-- SECCIÓN 1: AGREGAR ETIQUETA / TEXTO PERSONALIZADO -->
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 1rem; border-radius: 16px;">
                        <h4 style="font-size: 0.85rem; font-weight: 900; color: var(--color-primary-orange); letter-spacing: 0.5px; margin: 0 0 0.65rem 0;">
                            ➕ AGREGAR NUEVA ETIQUETA / TEXTO
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                            <input type="text" id="newCustomTagInput" class="form-input-custom" placeholder="Ej. #SponsorOficial, Ingreso +18..." style="font-size: 0.85rem; padding: 0.6rem 0.85rem;">
                            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.6rem 0.85rem; font-size: 0.825rem; text-align: center; justify-content: center;" onclick="createNewCustomTag()">
                                ➕ Añadir Etiqueta al Boleto
                            </button>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: CAMPOS DEL SISTEMA DISPONIBLES -->
                    <div>
                        <h4 style="font-size: 0.85rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.75rem;">📌 CAMPOS DEL SISTEMA DISPONIBLES</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.45rem;" id="systemElementsList">
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_logo" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('logo')">
                                <span>🖼️ Logo Marca Oficial</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_titulo" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('titulo')">
                                <span>📝 Título / Nombre Show</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_zona" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('zona')">
                                <span>🏷️ Zona / Sector</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_precio" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('precio')">
                                <span>💰 Precio de Entrada</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_banner" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('banner')">
                                <span>🖼️ Banner del Show</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_qr" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('qr')">
                                <span>📲 Código QR Gigante</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_comprador" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('comprador')">
                                <span>👤 Datos de Comprador</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_recinto" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('recinto')">
                                <span>📍 Recinto & Fecha</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_ticket_number" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('ticket_number')">
                                <span>🔢 N° Correlativo (00396)</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_hash" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('hash')">
                                <span>🔑 Hash Validación</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_disclaimer" style="justify-content: space-between; font-size: 0.85rem; padding: 0.55rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('disclaimer')">
                                <span>📜 Disclaimer / Nota Legal</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: SUBIR IMÁGENES DESDE PC (BANNER DEL SHOW & FONDO) -->
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; display: flex; flex-direction: column; gap: 1rem;">
                        
                        <!-- APARTADO: BANNER DEL SHOW -->
                        <div>
                            <h4 style="font-size: 0.85rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.65rem;">
                                🖼️ BANNER DEL SHOW
                            </h4>
                            
                            <input type="file" id="canvaShowBannerFileInput" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="handleShowBannerUpload(event)">

                            <div id="bannerUploadBoxFilled" style="display: flex; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 0.75rem; flex-direction: column; gap: 0.6rem;">
                                <div style="display: flex; align-items: center; gap: 0.65rem;">
                                    <div id="bannerThumbPreview" style="width: 52px; height: 36px; border-radius: 8px; background-size: cover; background-position: center; border: 1px solid rgba(255,255,255,0.2); background-image: url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80');"></div>
                                    <div style="flex: 1; overflow: hidden;">
                                        <div id="bannerFileNameText" style="font-size: 0.8rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Banner Oficial</div>
                                        <div style="font-size: 0.7rem; color: #10B981; font-weight: 700;">✓ Imagen de Cartel Activa</div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.4rem;">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.45rem; font-size: 0.75rem; text-align: center; justify-content: center;" onclick="document.getElementById('canvaShowBannerFileInput').click()">
                                        🔄 Cambiar Banner
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom" style="padding: 0.45rem 0.75rem; font-size: 0.75rem; color: #EF4444;" onclick="removeShowBannerImage()" title="Quitar Banner">
                                        🗑️ Quitar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- APARTADO: IMAGEN DE FONDO DESDE TU PC -->
                        <div>
                            <h4 style="font-size: 0.85rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.65rem;">
                                🌌 IMAGEN DE FONDO DEL BOLETO
                            </h4>
                            
                            <input type="file" id="canvaBgFileInput" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="handleBgFileUpload(event)">
                            <input type="hidden" id="canvaBgImageInput" value="">
                            <input type="hidden" id="canvaBgColor" value="#0F172A">
                            <input type="hidden" id="canvaBgColorText" value="#0F172A">
                            <input type="hidden" id="canvaStripColor" value="#FF5500">
                            <input type="hidden" id="canvaStripColorText" value="#FF5500">

                            <!-- CAJA PARA SUBIR ARCHIVO DESDE PC -->
                            <div id="bgUploadBoxEmpty" onclick="document.getElementById('canvaBgFileInput').click()" style="border: 2px dashed rgba(255, 85, 0, 0.4); border-radius: 14px; padding: 1.2rem 1rem; text-align: center; cursor: pointer; background: rgba(255, 85, 0, 0.05); transition: all 0.2s ease;">
                                <div style="font-size: 1.8rem; margin-bottom: 0.35rem;">📁</div>
                                <div style="font-size: 0.85rem; font-weight: 900; color: #FFFFFF; margin-bottom: 0.2rem;">
                                    Subir Fondo desde mi PC
                                </div>
                                <div style="font-size: 0.725rem; color: #94A3B8;">
                                    PNG, JPG o WEBP (Haz clic para seleccionar)
                                </div>
                            </div>

                            <!-- CAJA CUANDO YA TIENE IMAGEN CARGADA -->
                            <div id="bgUploadBoxFilled" style="display: none; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 0.75rem; flex-direction: column; gap: 0.6rem;">
                                <div style="display: flex; align-items: center; gap: 0.65rem;">
                                    <div id="bgThumbPreview" style="width: 52px; height: 36px; border-radius: 8px; background-size: cover; background-position: center; border: 1px solid rgba(255,255,255,0.2);"></div>
                                    <div style="flex: 1; overflow: hidden;">
                                        <div id="bgFileNameText" style="font-size: 0.8rem; font-weight: 800; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Fondo Seleccionado</div>
                                        <div style="font-size: 0.7rem; color: #10B981; font-weight: 700;">✓ Fondo 100% Limpio Activo</div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.4rem;">
                                    <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.45rem; font-size: 0.75rem; text-align: center; justify-content: center;" onclick="document.getElementById('canvaBgFileInput').click()">
                                        🔄 Cambiar Fondo
                                    </button>
                                    <button type="button" class="btn btn-cancel-custom" style="padding: 0.45rem 0.75rem; font-size: 0.75rem; color: #EF4444;" onclick="removeCanvaBgImage()" title="Quitar Fondo">
                                        🗑️ Quitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: LIENZO OFICIAL CANVA 20.40CM X 9.80CM (771PX X 370PX) CON ARRASTRE 100% LIBRE -->
                <div style="background: #08080E; padding: 1.5rem 2rem; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; overflow: auto; position: relative;">
                    
                    <!-- BARRA SUPERIOR DE FORMATO ESTILO CANVA STUDIO PRO (FIJA ARRIBA DEL LIENZO) -->
                    <div id="canvaStudioTopToolbar" class="canva-top-studio-toolbar">
                        <!-- SELECTOR DE FUENTES GOOGLE FONTS -->
                        <div class="toolbar-group" title="Tipo de Fuente">
                            <span style="font-size: 0.75rem; color: #94A3B8;">🔤</span>
                            <select id="floatingFontFamilySelect" class="toolbar-font-select" onchange="applyFloatingFormat('fontFamily', this.value)">
                                <option value="inherit">Fuente Actual</option>
                                <option value="'Inter', sans-serif">Inter (Moderna)</option>
                                <option value="'Montserrat', sans-serif">Montserrat (Geométrica)</option>
                                <option value="'Poppins', sans-serif">Poppins (Redondeada)</option>
                                <option value="'Bebas Neue', sans-serif">Bebas Neue (Impacto Show)</option>
                                <option value="'Oswald', sans-serif">Oswald (Condensada)</option>
                                <option value="'Outfit', sans-serif">Outfit (Digital Pro)</option>
                                <option value="'Playfair Display', serif">Playfair (Elegante VIP)</option>
                                <option value="'Space Grotesk', sans-serif">Space Grotesk (Tech)</option>
                                <option value="'Roboto', sans-serif">Roboto (Estándar)</option>
                                <option value="monospace">Monospace (Código / Hash)</option>
                            </select>
                        </div>

                        <!-- TAMAÑO DE FUENTE -->
                        <div class="toolbar-group" title="Tamaño de Fuente">
                            <button type="button" class="toolbar-btn" onmousedown="event.preventDefault(); applyFloatingFormat('fontSize', 'dec')" title="Disminuir Tamaño">➖</button>
                            <span id="floatingFontSizeText" class="font-size-indicator">14px</span>
                            <button type="button" class="toolbar-btn" onmousedown="event.preventDefault(); applyFloatingFormat('fontSize', 'inc')" title="Aumentar Tamaño">➕</button>
                        </div>

                        <!-- SELECTOR DE COLOR PERSONALIZADO -->
                        <div class="toolbar-group" title="Color de Texto">
                            <label for="floatingColorPicker" style="display: flex; align-items: center; gap: 0.35rem; cursor: pointer; margin: 0; padding: 0 2px;">
                                <span style="font-size: 0.75rem; font-weight: 800; color: #94A3B8;">🎨 Color</span>
                                <input type="color" id="floatingColorPicker" oninput="applyFloatingFormat('color', this.value)" class="color-picker-input" title="Elegir Color de Texto">
                            </label>
                        </div>

                        <!-- ESTILOS B / I / U -->
                        <div class="toolbar-group">
                            <button type="button" class="toolbar-btn" id="btnFloatingBold" onmousedown="event.preventDefault(); applyFloatingFormat('bold')" title="Negrita (Bold)" style="font-weight: 900; font-family: serif;">B</button>
                            <button type="button" class="toolbar-btn" id="btnFloatingItalic" onmousedown="event.preventDefault(); applyFloatingFormat('italic')" title="Cursiva (Italic)" style="font-style: italic; font-family: serif;">I</button>
                            <button type="button" class="toolbar-btn" id="btnFloatingUnderline" onmousedown="event.preventDefault(); applyFloatingFormat('underline')" title="Subrayado (Underline)" style="text-decoration: underline; font-weight: 700; font-family: serif;">U</button>
                        </div>

                        <!-- ALINEACIÓN -->
                        <div class="toolbar-group">
                            <button type="button" class="toolbar-btn" id="btnAlignLeft" onmousedown="event.preventDefault(); applyFloatingFormat('align', 'left')" title="Alinear Izquierda">⬅️</button>
                            <button type="button" class="toolbar-btn" id="btnAlignCenter" onmousedown="event.preventDefault(); applyFloatingFormat('align', 'center')" title="Centrar Texto">↔️</button>
                            <button type="button" class="toolbar-btn" id="btnAlignRight" onmousedown="event.preventDefault(); applyFloatingFormat('align', 'right')" title="Alinear Derecha">➡️</button>
                        </div>

                        <!-- TRANSFORMACIÓN, FONDO DE CAJA & ROTAR -->
                        <div class="toolbar-group">
                            <button type="button" class="toolbar-btn" id="btnFloatingTransform" onmousedown="event.preventDefault(); applyFloatingFormat('textTransform')" title="Transformar (MAYÚSCULAS / minúsculas)">
                                <strong style="font-size: 0.8rem;">TT</strong>
                            </button>
                            <button type="button" class="toolbar-btn" id="btnFloatingBadgeBg" onmousedown="event.preventDefault(); applyFloatingFormat('badgeBg')" title="Fondo Sombreado a la Etiqueta">
                                🔲
                            </button>
                            <button type="button" class="toolbar-btn" id="btnFloatingRotate" onmousedown="event.preventDefault(); applyFloatingFormat('rotate')" title="Rotar 90°">
                                🔄
                            </button>
                        </div>

                        <!-- ELIMINAR -->
                        <button type="button" class="toolbar-btn toolbar-btn-danger" onmousedown="event.preventDefault(); deleteSelectedCanvaElement()" title="Eliminar Etiqueta">🗑️</button>
                    </div>

                    <div style="margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; justify-content: center;">
                        <span class="canvas-dimension-badge" style="color: #10B981; border-color: rgba(16, 185, 129, 0.3);">
                            🖐️ Clic para Mover • Doble Clic para Editar Texto
                        </span>
                        <span class="canvas-dimension-badge">
                            📐 20.40 cm de Ancho × 9.80 cm de Alto (771px × 370px)
                        </span>
                    </div>

                    <!-- LIENZO PRINCIPAL CANVA 20.40CM X 9.80CM (771PX X 370PX) -->
                    <div class="canva-official-canvas" id="canvaTicketCanvas">
                        
                        <!-- CAPA 0: IMAGEN DE FONDO HD (100% LIMPIA, SIN OVERLAYS OSCUROS) -->
                        <div id="canvaBgImageLayer" style="position: absolute; inset: 0; background-size: cover; background-position: center; z-index: 0; pointer-events: none; transition: all 0.3s ease;"></div>

                        <!-- CAPA DECORATIVA 1: FRANJA LATERAL DE MARCA (NO BLOQUEA ARRASTRE) -->
                        <div id="canvaSideStrip" style="width: 65px; background: #000000; height: 100%; position: absolute; left: 0; top: 0; display: flex; align-items: center; justify-content: center; z-index: 2; pointer-events: none; transition: all 0.3s ease;">
                            <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="max-width: 220px; height: 42px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 8px rgba(255,85,0,0.6));">
                        </div>

                        <!-- CAPA DECORATIVA 2: LÍNEA GUÍA DE DESPRENDIBLE TROQUELADO (NO BLOQUEA ARRASTRE) -->
                        <div id="canvaStubGuideLine" style="position: absolute; right: 210px; top: 0; bottom: 0; width: 0; border-left: 2px dashed #CBD5E1; z-index: 2; pointer-events: none;"></div>

                        <!-- ========================================================================= -->
                        <!-- ÁREA UNIFICADA DE ETIQUETAS: TODAS LAS ETIQUETAS SON LIBRES DENTRO DEL LIENZO -->
                        <!-- ========================================================================= -->
                        <div id="canvaCanvasArea" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index: 5;">
                            
                            <!-- ELEMENTO ARRASTRABLE: LOGO OFICIAL VIVE GO -->
                            <div class="canva-drag-element" id="canvaElLogo" style="top: 20px; left: 80px; display: none;">
                                <div class="canva-drag-box-container">
                                    <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="height: 36px; width: auto; object-fit: contain; pointer-events: none; filter: drop-shadow(0 0 8px rgba(255,85,0,0.6));">
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: BANNER SHOW -->
                            <div class="canva-drag-element" id="canvaElBanner" style="top: 65px; left: 80px; width: 440px; height: 135px;">
                                <div class="canva-drag-box-container" style="padding: 0;">
                                    <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px; pointer-events: none;">
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: TÍTULO SHOW -->
                            <div class="canva-drag-element" id="canvaElTitle" style="top: 15px; left: 80px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false">
                                    <h2 style="font-size: 1.15rem; font-weight: 900; color: #000000; margin: 0;">Chúpate la Plata con Son del Duke en Ayacucho</h2>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: ZONA -->
                            <div class="canva-drag-element" id="canvaElZone" style="top: 42px; left: 80px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false">
                                    <span style="font-size: 0.925rem; font-weight: 800; color: #1E293B;">ZONA VIP</span>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: PRECIO -->
                            <div class="canva-drag-element" id="canvaElPrice" style="top: 15px; left: 420px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false" style="text-align: right;">
                                    <span style="font-size: 0.75rem; font-weight: 900; color: #000000; display: block;">PRECIO:</span>
                                    <span style="font-size: 1.3rem; font-weight: 900; color: #000000; display: block;">S/ 55.50</span>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: COMPRADOR -->
                            <div class="canva-drag-element" id="canvaElBuyer" style="top: 220px; left: 80px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false">
                                    <div style="display: flex; flex-direction: column; font-size: 0.8rem; color: #000000;">
                                        <span style="font-size: 0.725rem; color: #475569;">Comprador:</span>
                                        <span style="font-weight: 900; font-size: 0.95rem; text-transform: uppercase;">CHRISTIAN GOMEZ LUJAN</span>
                                        <span style="font-weight: 800; font-size: 0.825rem; color: #1E293B;">DNI: 70436491</span>
                                    </div>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: RECINTO & FECHA -->
                            <div class="canva-drag-element" id="canvaElVenue" style="top: 220px; left: 320px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false" style="text-align: right;">
                                    <div style="display: flex; flex-direction: column; font-size: 0.8rem; color: #000000;">
                                        <span style="font-weight: 900; font-size: 0.95rem;">Complejo San Luis</span>
                                        <span style="font-size: 0.8rem; font-weight: 700; color: #334155;">Av. Cusco 528 - AYACUCHO</span>
                                        <span style="font-weight: 900; font-size: 1rem; color: #FF5500;">10.04.2025 / 06:00PM</span>
                                    </div>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: N° CORRELATIVO -->
                            <div class="canva-drag-element" id="canvaElTicketNumber" style="top: 15px; left: 610px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false">
                                    <span style="font-size: 1.2rem; font-weight: 900; color: #000000; font-family: var(--font-heading);">N° 00396</span>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: CÓDIGO QR GIGANTE -->
                            <div class="canva-drag-element" id="canvaElQR" style="top: 50px; left: 590px;">
                                <div class="canva-drag-box-container" style="padding: 0;">
                                    <div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; pointer-events: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="135" height="135">
                                             <rect width="256" height="256" fill="#FFFFFF"/>
                                             <path d="M16,16H96V96H16Z M32,32V80H80V32Z M48,48H64V64H48Z" fill="#000000"/>
                                             <path d="M160,16H240V96H160Z M176,32V80H224V32Z M192,48H208V64H192Z" fill="#000000"/>
                                             <path d="M16,160H96V240H16Z M32,176V224H80V176Z M48,192H64V208H48Z" fill="#000000"/>
                                             <path d="M112,16H144V32H112Z M112,48H128V80H112Z M144,64H160V96H144Z M112,96H128V112H112Z M16,112H48V128H16Z M64,112H96V144H64Z M128,128H160V144H128Z M176,112H224V128H176Z M208,128H240V160H208Z M112,160H144V176H112Z M144,176H176V192H144Z M112,192H128V240H112Z M160,208H192V224H160Z M208,192H240V240H208Z M176,224H208V240H176Z M144,224H160V240H144Z" fill="#000000"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: CÓDIGO HASH DE VALIDACIÓN -->
                            <div class="canva-drag-element" id="canvaElHash" style="top: 205px; left: 605px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false">
                                    <span style="font-family: monospace; font-size: 0.9rem; font-weight: 800; color: #000000; letter-spacing: 1.5px;">JAJHSPWFWJ</span>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: DISCLAIMER / NOTA LEGAL -->
                            <div class="canva-drag-element" id="canvaElDisclaimer" style="top: 245px; left: 570px; width: 190px;">
                                <div class="canva-drag-box-container" contenteditable="false" spellcheck="false" style="border-top: 1.5px solid #000000; padding-top: 0.25rem;">
                                    <p style="font-size: 0.625rem; font-weight: 700; color: #334155; line-height: 1.2; margin: 0; text-align: center;">
                                        La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- LÍNEAS GUÍA DE ALINEACIÓN INTELIGENTE (EFECTO IMÁN / CANVA SNAP) -->
                        <div id="canvaSnapGuideX" style="position: absolute; top: 0; bottom: 0; width: 1.5px; border-left: 1.5px dashed #06B6D4; display: none; z-index: 9998; pointer-events: none; filter: drop-shadow(0 0 6px #06B6D4);"></div>
                        <div id="canvaSnapGuideY" style="position: absolute; left: 0; right: 0; height: 1.5px; border-top: 1.5px dashed #06B6D4; display: none; z-index: 9998; pointer-events: none; filter: drop-shadow(0 0 6px #06B6D4);"></div>

                    </div> <!-- Cierre canvaTicketCanvas -->
                </div> <!-- Cierre Columna Derecha -->
            </div> <!-- Cierre Cuerpo 2 Columnas -->
        </div> <!-- Cierre Container -->
    </div> <!-- Cierre Modal Overlay -->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        let customTagCounter = 1;
        let activeTab = 'physical';
        const defaultElementsHtml = {};

        document.addEventListener('DOMContentLoaded', function () {
            // Capturar HTML base por defecto de cada elemento para nunca perderlo
            document.querySelectorAll('.canva-drag-element').forEach(el => {
                const box = el.querySelector('.canva-drag-box-container');
                if (box) defaultElementsHtml[el.id] = box.innerHTML;
            });

            const modal = document.getElementById('canvaStudioModal');
            const openBtn = document.getElementById('btnOpenCanvaStudioModal');
            const closeBtn = document.getElementById('btnCloseCanvaStudioModal');

            if (openBtn && modal) {
                openBtn.addEventListener('click', function () {
                    document.getElementById('canvaTemplateId').value = '';
                    const defaultName = (activeTab === 'virtual') ? 'Nueva Plantilla Virtual Canva 2026' : 'Nueva Plantilla Física Canva 2026';
                    document.getElementById('canvaTemplateNameInput').value = defaultName;
                    
                    setModalTemplateType(activeTab === 'virtual' ? 'virtual' : 'fisica');
                    applyTemplateStructureMode(activeTab === 'virtual' ? 4 : 1, defaultName);
                    
                    changeCanvaBgImage('');

                    resetCanvaPositions(false);
                    modal.classList.add('active');
                });
            }

            if (closeBtn && modal) {
                closeBtn.addEventListener('click', function () {
                    modal.classList.remove('active');
                });
            }

            // Filtro de búsqueda en tiempo real
            const searchInput = document.getElementById('tableFilterInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.toLowerCase().trim();
                    const activeCards = document.querySelectorAll(activeTab === 'physical' ? '#physicalTemplatesGrid .template-item-card' : '#virtualTemplatesGrid .template-item-card');
                    activeCards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        card.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }

            makeCanvaElementsDraggable();
        });

        // Cambio de Pestañas (Físicas / Virtuales)
        function switchTemplateTab(tab) {
            activeTab = tab;
            const btnPhysical = document.getElementById('tabBtnPhysical');
            const btnVirtual = document.getElementById('tabBtnVirtual');
            const contentPhysical = document.getElementById('tabContentPhysical');
            const contentVirtual = document.getElementById('tabContentVirtual');

            if (tab === 'physical') {
                btnPhysical.classList.add('active');
                btnVirtual.classList.remove('active');
                contentPhysical.style.display = 'block';
                contentVirtual.style.display = 'none';
            } else {
                btnVirtual.classList.add('active');
                btnPhysical.classList.remove('active');
                contentVirtual.style.display = 'block';
                contentPhysical.style.display = 'none';
            }
        }

        // Selector de Tipo en el Modal (Física / Virtual)
        function setModalTemplateType(type) {
            document.getElementById('canvaTemplateType').value = type;
            const btnPhy = document.getElementById('modalTypeBtnPhysical');
            const btnVir = document.getElementById('modalTypeBtnVirtual');

            if (type === 'virtual') {
                btnVir.classList.add('active');
                btnPhy.classList.remove('active');
            } else {
                btnPhy.classList.add('active');
                btnVir.classList.remove('active');
            }
        }

        function applyTemplateStructureMode(id, name) {
            const sideStrip = document.getElementById('canvaSideStrip');
            const stubLine = document.getElementById('canvaStubGuideLine');
            const logoEl = document.getElementById('canvaElLogo');

            const isPlantilla1 = (id == 1 || (name && name.includes('Plantilla 1')));
            const isPlantilla2 = (id == 2 || (name && name.includes('Plantilla 2')));
            const isVirtual = (document.getElementById('canvaTemplateType').value === 'virtual' || id >= 4);

            if (isVirtual) {
                // Modo Virtual: Franja lateral oculta, línea de corte oculta, logo flotante activado
                if (sideStrip) sideStrip.style.display = 'none';
                if (stubLine) stubLine.style.display = 'none';
                if (logoEl) logoEl.style.display = 'block';
            } else if (isPlantilla1) {
                // Plantilla Física 1: Franja a la izquierda, línea de corte a la derecha
                if (sideStrip) {
                    sideStrip.style.display = 'flex';
                    sideStrip.style.left = '0';
                    sideStrip.style.right = 'auto';
                }
                if (stubLine) {
                    stubLine.style.display = 'block';
                    stubLine.style.right = '210px';
                    stubLine.style.left = 'auto';
                }
                if (logoEl) logoEl.style.display = 'none';
            } else if (isPlantilla2) {
                // Plantilla Física 2: Franja a la derecha, línea de corte a la izquierda
                if (sideStrip) {
                    sideStrip.style.display = 'flex';
                    sideStrip.style.right = '0';
                    sideStrip.style.left = 'auto';
                }
                if (stubLine) {
                    stubLine.style.display = 'block';
                    stubLine.style.left = '210px';
                    stubLine.style.right = 'auto';
                }
                if (logoEl) logoEl.style.display = 'none';
            } else {
                // Plantilla 3 o modo continuo
                if (sideStrip) sideStrip.style.display = 'none';
                if (stubLine) stubLine.style.display = 'none';
                if (logoEl) logoEl.style.display = 'block';
            }
        }

        function isColorDark(hexColor) {
            if (!hexColor || hexColor.charAt(0) !== '#') return false;
            const hex = hexColor.substring(1);
            if (hex.length < 6) return false;
            const r = parseInt(hex.substr(0, 2), 16) || 0;
            const g = parseInt(hex.substr(2, 2), 16) || 0;
            const b = parseInt(hex.substr(4, 2), 16) || 0;
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;
            return brightness < 140;
        }

        function adjustTextContrast(bgColor) {
            const hasBgImage = document.getElementById('canvaBgImageInput')?.value.trim() !== '';
            const isDark = hasBgImage || isColorDark(bgColor);
            const primaryColor = isDark ? '#FFFFFF' : '#000000';
            const secondaryColor = isDark ? '#E2E8F0' : '#334155';

            const titleBox = document.querySelector('#canvaElTitle .canva-drag-box-container');
            const title = document.querySelector('#canvaElTitle h2');
            if (title && !title.getAttribute('data-custom-color') && !titleBox?.innerHTML.includes('style=')) {
                title.style.color = primaryColor;
            }

            const ticketNumBox = document.querySelector('#canvaElTicketNumber .canva-drag-box-container');
            const ticketNum = document.querySelector('#canvaElTicketNumber span');
            if (ticketNum && !ticketNum.getAttribute('data-custom-color') && !ticketNumBox?.innerHTML.includes('style=')) {
                ticketNum.style.color = primaryColor;
            }

            const buyerBox = document.querySelector('#canvaElBuyer .canva-drag-box-container');
            const buyerSpans = document.querySelectorAll('#canvaElBuyer span');
            if (buyerSpans.length >= 3 && !buyerBox?.innerHTML.includes('style=')) {
                if (!buyerSpans[0].getAttribute('data-custom-color')) buyerSpans[0].style.color = secondaryColor;
                if (!buyerSpans[1].getAttribute('data-custom-color')) buyerSpans[1].style.color = primaryColor;
                if (!buyerSpans[2].getAttribute('data-custom-color')) buyerSpans[2].style.color = isDark ? '#E2E8F0' : '#1E293B';
            }

            const venueBox = document.querySelector('#canvaElVenue .canva-drag-box-container');
            const venueSpans = document.querySelectorAll('#canvaElVenue span');
            if (venueSpans.length >= 3 && !venueBox?.innerHTML.includes('style=')) {
                if (!venueSpans[0].getAttribute('data-custom-color')) venueSpans[0].style.color = primaryColor;
                if (!venueSpans[1].getAttribute('data-custom-color')) venueSpans[1].style.color = secondaryColor;
                if (!venueSpans[2].getAttribute('data-custom-color')) venueSpans[2].style.color = isDark ? '#FF9966' : '#FF5500';
            }

            const hash = document.querySelector('#canvaElHash span');
            if (hash && !hash.getAttribute('data-custom-color')) hash.style.color = secondaryColor;

            const disclaimer = document.querySelector('#canvaElDisclaimer p');
            if (disclaimer && !disclaimer.getAttribute('data-custom-color')) disclaimer.style.color = isDark ? '#CBD5E1' : '#475569';
        }

        let selectedCanvaElement = null;

        function rgbToHex(rgb) {
            if (!rgb) return '#000000';
            if (rgb.startsWith('#')) return rgb;
            const res = rgb.match(/\d+/g);
            if (!res || res.length < 3) return '#000000';
            return "#" + ((1 << 24) + (parseInt(res[0]) << 16) + (parseInt(res[1]) << 8) + parseInt(res[2])).toString(16).slice(1);
        }

        function syncSystemSidebarBadges() {
            const idMap = {
                'logo': 'canvaElLogo',
                'titulo': 'canvaElTitle',
                'zona': 'canvaElZone',
                'precio': 'canvaElPrice',
                'banner': 'canvaElBanner',
                'qr': 'canvaElQR',
                'comprador': 'canvaElBuyer',
                'recinto': 'canvaElVenue',
                'ticket_number': 'canvaElTicketNumber',
                'hash': 'canvaElHash',
                'disclaimer': 'canvaElDisclaimer'
            };

            Object.keys(idMap).forEach(type => {
                const btn = document.getElementById(`sysBtn_${type}`);
                const el = document.getElementById(idMap[type]);
                if (!btn || !el) return;

                const badge = btn.querySelector('.field-status-badge');
                const isVisible = (el.style.display !== 'none');
                const isSelected = (selectedCanvaElement && selectedCanvaElement.id === el.id);

                if (isSelected) {
                    btn.style.border = '1.5px solid #FF5500';
                    btn.style.background = 'rgba(255, 85, 0, 0.2)';
                    btn.style.boxShadow = '0 0 12px rgba(255, 85, 0, 0.4)';
                    if (badge) {
                        badge.textContent = '★ Seleccionado';
                        badge.style.color = '#FF5500';
                    }
                } else if (isVisible) {
                    btn.style.border = '1px solid rgba(16, 185, 129, 0.4)';
                    btn.style.background = 'rgba(16, 185, 129, 0.08)';
                    btn.style.boxShadow = 'none';
                    if (badge) {
                        badge.textContent = '✓ Activo';
                        badge.style.color = '#10B981';
                    }
                } else {
                    btn.style.border = '1px solid rgba(255, 255, 255, 0.1)';
                    btn.style.background = 'transparent';
                    btn.style.boxShadow = 'none';
                    if (badge) {
                        badge.textContent = '+ Añadir';
                        badge.style.color = '#06B6D4';
                    }
                }
            });
        }

        function toggleSystemElement(type) {
            const idMap = {
                'logo': 'canvaElLogo',
                'titulo': 'canvaElTitle',
                'zona': 'canvaElZone',
                'precio': 'canvaElPrice',
                'banner': 'canvaElBanner',
                'qr': 'canvaElQR',
                'comprador': 'canvaElBuyer',
                'recinto': 'canvaElVenue',
                'ticket_number': 'canvaElTicketNumber',
                'hash': 'canvaElHash',
                'disclaimer': 'canvaElDisclaimer'
            };

            const elementId = idMap[type];
            const el = document.getElementById(elementId);
            if (!el) return;

            if (el.style.display === 'none') {
                el.style.display = 'inline-flex';
                selectCanvaElement(el);
            } else {
                selectCanvaElement(el);
            }
        }

        function enableTextEditMode(el) {
            if (!el) return;
            const box = el.querySelector('.canva-drag-box-container');
            if (box) {
                box.setAttribute('contenteditable', 'true');
                box.focus();
            }
        }

        function disableTextEditMode(el) {
            if (!el) return;
            const box = el.querySelector('.canva-drag-box-container');
            if (box) {
                box.setAttribute('contenteditable', 'false');
            }
        }

        function selectCanvaElement(el) {
            document.querySelectorAll('.canva-drag-element').forEach(item => {
                item.classList.remove('selected-element');
                if (item !== el) {
                    disableTextEditMode(item);
                }
            });

            const topToolbar = document.getElementById('canvaStudioTopToolbar');

            if (!el) {
                selectedCanvaElement = null;
                if (topToolbar) {
                    topToolbar.style.opacity = '0.5';
                    topToolbar.style.pointerEvents = 'none';
                }
                syncSystemSidebarBadges();
                return;
            }

            selectedCanvaElement = el;
            el.classList.add('selected-element');

            if (topToolbar) {
                topToolbar.style.opacity = '1';
                topToolbar.style.pointerEvents = 'auto';
            }

            syncFloatingToolbarControls(el);
            syncSystemSidebarBadges();
        }

        function syncFloatingToolbarControls(el) {
            if (!el) return;
            const computed = window.getComputedStyle(el);
            const textAlign = el.style.textAlign || computed.textAlign || 'left';
            const fontSize = parseInt(el.style.fontSize || computed.fontSize, 10) || 14;
            const color = el.style.color || rgbToHex(computed.color) || '#000000';
            const isBold = (el.style.fontWeight === '900' || computed.fontWeight >= '700');
            const isItalic = (el.style.fontStyle === 'italic' || computed.fontStyle === 'italic');

            document.getElementById('btnAlignLeft')?.classList.toggle('active', textAlign === 'left');
            document.getElementById('btnAlignCenter')?.classList.toggle('active', textAlign === 'center');
            document.getElementById('btnAlignRight')?.classList.toggle('active', textAlign === 'right');

            const fontText = document.getElementById('floatingFontSizeText');
            if (fontText) fontText.textContent = fontSize + 'px';

            const colorPicker = document.getElementById('floatingColorPicker');
            if (colorPicker) colorPicker.value = color;

            document.getElementById('btnFloatingBold')?.classList.toggle('active', isBold);
            document.getElementById('btnFloatingItalic')?.classList.toggle('active', isItalic);

            const fontSelect = document.getElementById('floatingFontFamilySelect');
            if (fontSelect) {
                const rawFont = el.style.fontFamily || computed.fontFamily || '';
                let matched = false;
                for (let opt of fontSelect.options) {
                    if (opt.value !== 'inherit' && rawFont.toLowerCase().includes(opt.value.replace(/['"]/g, '').split(',')[0].trim().toLowerCase())) {
                        fontSelect.value = opt.value;
                        matched = true;
                        break;
                    }
                }
                if (!matched) fontSelect.value = 'inherit';
            }

            const box = el.querySelector('.canva-drag-box-container');
            const hasBg = box && box.classList.contains('has-badge-bg');
            document.getElementById('btnFloatingBadgeBg')?.classList.toggle('active', !!hasBg);
        }

        let savedSelectionRange = null;

        function saveCurrentSelection() {
            const sel = window.getSelection();
            if (sel && sel.rangeCount > 0 && selectedCanvaElement) {
                const range = sel.getRangeAt(0);
                if (selectedCanvaElement.contains(range.commonAncestorContainer) || selectedCanvaElement === range.commonAncestorContainer) {
                    savedSelectionRange = range.cloneRange();
                }
            }
        }

        function restoreSavedSelection() {
            if (savedSelectionRange) {
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(savedSelectionRange);
            }
        }

        document.addEventListener('selectionchange', function () {
            saveCurrentSelection();
        });

        function applyFloatingFormat(action, val) {
            if (!selectedCanvaElement) return;

            const box = selectedCanvaElement.querySelector('.canva-drag-box-container');
            const isEditing = box && box.getAttribute('contenteditable') === 'true';

            const sel = window.getSelection();
            let isRangeSelected = false;
            let targetRange = null;

            if (sel && !sel.isCollapsed && sel.rangeCount > 0 && selectedCanvaElement.contains(sel.anchorNode)) {
                isRangeSelected = true;
                targetRange = sel.getRangeAt(0);
                savedSelectionRange = targetRange.cloneRange();
            } else if (savedSelectionRange && !savedSelectionRange.collapsed) {
                isRangeSelected = true;
                targetRange = savedSelectionRange;
                restoreSavedSelection();
            }

            if (isRangeSelected && targetRange) {
                if (box) {
                    box.setAttribute('contenteditable', 'true');
                    box.focus();
                }

                const currentSel = window.getSelection();
                currentSel.removeAllRanges();
                currentSel.addRange(targetRange);

                // MODO SELECCIÓN PRECISA (ESTILO WORD / GOOGLE DOCS / CANVA)
                if (action === 'bold') {
                    document.execCommand('bold', false, null);
                } else if (action === 'italic') {
                    document.execCommand('italic', false, null);
                } else if (action === 'underline') {
                    document.execCommand('underline', false, null);
                } else if (action === 'fontFamily') {
                    if (val !== 'inherit') {
                        const span = document.createElement('span');
                        span.style.fontFamily = val;
                        span.appendChild(targetRange.extractContents());
                        targetRange.insertNode(span);
                    }
                } else if (action === 'color') {
                    document.execCommand('styleWithCSS', false, true);
                    const ok = document.execCommand('foreColor', false, val);
                    if (!ok) {
                        const span = document.createElement('span');
                        span.style.color = val;
                        span.appendChild(targetRange.extractContents());
                        targetRange.insertNode(span);
                    }
                } else if (action === 'fontSize') {
                    const span = document.createElement('span');
                    const computed = window.getComputedStyle(selectedCanvaElement);
                    let currSize = parseInt(selectedCanvaElement.style.fontSize || computed.fontSize, 10) || 14;
                    if (val === 'inc') currSize += 2;
                    if (val === 'dec') currSize = Math.max(8, currSize - 2);

                    span.style.fontSize = currSize + 'px';
                    span.appendChild(targetRange.extractContents());
                    targetRange.insertNode(span);
                }
            } else {
                // MODO CONTENEDOR COMPLETO
                if (action === 'fontFamily') {
                    if (val !== 'inherit') {
                        selectedCanvaElement.style.fontFamily = val;
                        selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                            child.style.fontFamily = val;
                        });
                    }
                } else if (action === 'align') {
                    const flexVal = val === 'center' ? 'center' : (val === 'right' ? 'flex-end' : 'flex-start');
                    selectedCanvaElement.style.textAlign = val;
                    // También aplicar al box-container para que se serialice en p.html y sea leído por el PDF
                    const box = selectedCanvaElement.querySelector('.canva-drag-box-container');
                    if (box) box.style.textAlign = val;
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.textAlign = val;
                        const disp = window.getComputedStyle(child).display;
                        if (disp === 'flex' || child.style.display === 'flex') {
                            child.style.alignItems = flexVal;
                        }
                    });
                } else if (action === 'color') {
                    selectedCanvaElement.style.color = val;
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.color = val;
                        child.setAttribute('data-custom-color', 'true');
                    });
                } else if (action === 'fontSize') {
                    const computed = window.getComputedStyle(selectedCanvaElement);
                    let currSize = parseInt(selectedCanvaElement.style.fontSize || computed.fontSize, 10) || 14;
                    if (val === 'inc') currSize += 2;
                    if (val === 'dec') currSize = Math.max(8, currSize - 2);

                    selectedCanvaElement.style.fontSize = currSize + 'px';
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.fontSize = currSize + 'px';
                    });
                } else if (action === 'bold') {
                    const computed = window.getComputedStyle(selectedCanvaElement);
                    const isBold = (selectedCanvaElement.style.fontWeight === '900' || computed.fontWeight >= '700');
                    const newWeight = isBold ? '400' : '900';
                    selectedCanvaElement.style.fontWeight = newWeight;
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.fontWeight = newWeight;
                    });
                } else if (action === 'italic') {
                    const computed = window.getComputedStyle(selectedCanvaElement);
                    const isItalic = (selectedCanvaElement.style.fontStyle === 'italic' || computed.fontStyle === 'italic');
                    const newStyle = isItalic ? 'normal' : 'italic';
                    selectedCanvaElement.style.fontStyle = newStyle;
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.fontStyle = newStyle;
                    });
                } else if (action === 'underline') {
                    const computed = window.getComputedStyle(selectedCanvaElement);
                    const isUnderline = (selectedCanvaElement.style.textDecoration === 'underline' || computed.textDecoration.includes('underline'));
                    const newDec = isUnderline ? 'none' : 'underline';
                    selectedCanvaElement.style.textDecoration = newDec;
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.textDecoration = newDec;
                    });
                } else if (action === 'textTransform') {
                    const computed = window.getComputedStyle(selectedCanvaElement);
                    const curr = selectedCanvaElement.style.textTransform || computed.textTransform || 'none';
                    let next = 'uppercase';
                    if (curr === 'uppercase') next = 'lowercase';
                    else if (curr === 'lowercase') next = 'capitalize';
                    else if (curr === 'capitalize') next = 'none';
                    else next = 'uppercase';

                    selectedCanvaElement.style.textTransform = next;
                    selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                        child.style.textTransform = next;
                    });
                } else if (action === 'badgeBg') {
                    const boxEl = selectedCanvaElement.querySelector('.canva-drag-box-container') || selectedCanvaElement;
                    boxEl.classList.toggle('has-badge-bg');
                    document.getElementById('btnFloatingBadgeBg')?.classList.toggle('active', boxEl.classList.contains('has-badge-bg'));
                } else if (action === 'rotate') {
                    let currDeg = parseInt(selectedCanvaElement.getAttribute('data-rotate') || '0', 10);
                    currDeg = (currDeg + 90) % 360;
                    selectedCanvaElement.setAttribute('data-rotate', currDeg);
                    selectedCanvaElement.style.transform = `rotate(${currDeg}deg)`;
                }
            }

            saveCurrentSelection();
            syncFloatingToolbarControls(selectedCanvaElement);
        }

        function deleteSelectedCanvaElement() {
            if (selectedCanvaElement) {
                removeCanvaElement(selectedCanvaElement.id);
                selectCanvaElement(null);
            }
        }

        // =========================================================================
        // REDIMENSIONAMIENTO INTERACTIVO (4 ESQUINAS LIMPIAS ESTILO CANVA / FIGMA)
        // =========================================================================
        function makeElementResizable(el) {
            if (!el) return;
            
            // Añadir los 4 puntos de redimensionamiento en las esquinas
            if (!el.querySelector('.canva-resize-handle')) {
                const handles = ['nw', 'ne', 'sw', 'se'];
                handles.forEach(dir => {
                    const h = document.createElement('div');
                    h.className = `canva-resize-handle handle-${dir}`;
                    h.setAttribute('data-direction', dir);
                    el.appendChild(h);
                });
            }

            el.querySelectorAll('.canva-resize-handle').forEach(handle => {
                handle.onmousedown = function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    const dir = handle.getAttribute('data-direction');
                    const startX = e.clientX;
                    const startY = e.clientY;

                    const computed = window.getComputedStyle(el);
                    const startW = el.offsetWidth;
                    const startH = el.offsetHeight;
                    const startL = parseInt(computed.left, 10) || el.offsetLeft;
                    const startT = parseInt(computed.top, 10) || el.offsetTop;

                    const toolbar = document.getElementById('canvaFloatingToolbar');
                    if (toolbar) toolbar.style.display = 'none';

                    function onMouseMove(ev) {
                        const dx = ev.clientX - startX;
                        const dy = ev.clientY - startY;

                        let newW = startW;
                        let newH = startH;
                        let newL = startL;
                        let newT = startT;

                        if (dir.includes('e')) {
                            newW = Math.max(30, startW + dx);
                        }
                        if (dir.includes('s')) {
                            newH = Math.max(20, startH + dy);
                        }
                        if (dir.includes('w')) {
                            newW = Math.max(30, startW - dx);
                            newL = startL + (startW - newW);
                        }
                        if (dir.includes('n')) {
                            newH = Math.max(20, startH - dy);
                            newT = startT + (startH - newH);
                        }

                        el.style.width = newW + 'px';
                        el.style.height = newH + 'px';
                        el.style.left = newL + 'px';
                        el.style.top = newT + 'px';

                        // Adaptar imágenes y SVGs al nuevo tamaño del contenedor
                        const img = el.querySelector('img');
                        if (img) {
                            img.style.width = '100%';
                            img.style.height = '100%';
                        }
                        const svg = el.querySelector('svg');
                        if (svg) {
                            svg.setAttribute('width', Math.max(24, newW - 12));
                            svg.setAttribute('height', Math.max(24, newH - 12));
                        }
                    }

                    function onMouseUp() {
                        document.removeEventListener('mousemove', onMouseMove);
                        document.removeEventListener('mouseup', onMouseUp);
                        positionFloatingToolbar(el);
                    }

                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                };
            });
        }

        // =========================================================================
        // ARRASTRE 100% LIBRE EN EL LIENZO 20.40CM X 9.80CM CON DOBLE CLIC PARA TEXTO
        // =========================================================================
        function makeCanvaElementsDraggable() {
            const dragElements = document.querySelectorAll('.canva-drag-element');
            const canvas = document.getElementById('canvaTicketCanvas');
            const guideX = document.getElementById('canvaSnapGuideX');
            const guideY = document.getElementById('canvaSnapGuideY');
            const SNAP_THRESHOLD = 7;

            if (canvas) {
                canvas.addEventListener('click', function(e) {
                    if (!e.target.closest('.canva-drag-element') && !e.target.closest('.canva-floating-toolbar')) {
                        selectCanvaElement(null);
                    }
                });
            }

            function hideSnapGuides() {
                if (guideX) guideX.style.display = 'none';
                if (guideY) guideY.style.display = 'none';
            }

            dragElements.forEach(el => {
                makeElementResizable(el);

                let isDragging = false;
                let hasMoved = false;
                let startX, startY, initialLeft, initialTop;

                el.addEventListener('mousedown', function (e) {
                    if (e.target.classList.contains('canva-delete-badge') || e.target.classList.contains('canva-resize-handle')) return;

                    const box = el.querySelector('.canva-drag-box-container');
                    const isEditing = box && box.getAttribute('contenteditable') === 'true';

                    // Si no está editando texto, activar arrastre libre de inmediato
                    if (!isEditing) {
                        isDragging = true;
                        hasMoved = false;
                        startX = e.clientX;
                        startY = e.clientY;

                        el.style.right = '';
                        el.style.bottom = '';

                        const computed = window.getComputedStyle(el);
                        initialLeft = parseInt(computed.left, 10) || el.offsetLeft;
                        initialTop = parseInt(computed.top, 10) || el.offsetTop;
                    }
                });

                document.addEventListener('mousemove', function (e) {
                    if (!isDragging) return;

                    const dx = e.clientX - startX;
                    const dy = e.clientY - startY;

                    if (Math.hypot(dx, dy) > 4) {
                        hasMoved = true;

                        const toolbar = document.getElementById('canvaFloatingToolbar');
                        if (toolbar) toolbar.style.display = 'none';

                        let rawLeft = initialLeft + dx;
                        let rawTop = initialTop + dy;

                        const elW = el.offsetWidth;
                        const elH = el.offsetHeight;

                        // Límites de seguridad dentro del lienzo 771px x 370px
                        const canvasW = canvas ? canvas.offsetWidth : 771;
                        const canvasH = canvas ? canvas.offsetHeight : 370;
                        rawLeft = Math.max(0, Math.min(rawLeft, canvasW - elW));
                        rawTop = Math.max(0, Math.min(rawTop, canvasH - elH));

                        let snappedLeft = rawLeft;
                        let snappedTop = rawTop;

                        let showGuideX = false;
                        let guideXPos = 0;

                        let showGuideY = false;
                        let guideYPos = 0;

                        const otherElements = Array.from(document.querySelectorAll('.canva-drag-element')).filter(other => {
                            return other !== el && other.style.display !== 'none';
                        });

                        const currentXPoints = [
                            { val: rawLeft, type: 'left' },
                            { val: rawLeft + elW / 2, type: 'center' },
                            { val: rawLeft + elW, type: 'right' }
                        ];

                        const currentYPoints = [
                            { val: rawTop, type: 'top' },
                            { val: rawTop + elH / 2, type: 'center' },
                            { val: rawTop + elH, type: 'bottom' }
                        ];

                        // 1. Atracción Magnética Eje X
                        for (let other of otherElements) {
                            const oL = other.offsetLeft;
                            const oW = other.offsetWidth;
                            const otherXPoints = [
                                { val: oL, type: 'left' },
                                { val: oL + oW / 2, type: 'center' },
                                { val: oL + oW, type: 'right' }
                            ];

                            for (let curP of currentXPoints) {
                                for (let othP of otherXPoints) {
                                    if (Math.abs(curP.val - othP.val) <= SNAP_THRESHOLD) {
                                        if (curP.type === 'left') snappedLeft = othP.val;
                                        else if (curP.type === 'center') snappedLeft = othP.val - elW / 2;
                                        else if (curP.type === 'right') snappedLeft = othP.val - elW;

                                        showGuideX = true;
                                        guideXPos = othP.val;
                                        break;
                                    }
                                }
                                if (showGuideX) break;
                            }
                            if (showGuideX) break;
                        }

                        // 2. Atracción Magnética Eje Y
                        for (let other of otherElements) {
                            const oT = other.offsetTop;
                            const oH = other.offsetHeight;
                            const otherYPoints = [
                                { val: oT, type: 'top' },
                                { val: oT + oH / 2, type: 'center' },
                                { val: oT + oH, type: 'bottom' }
                            ];

                            for (let curP of currentYPoints) {
                                for (let othP of otherYPoints) {
                                    if (Math.abs(curP.val - othP.val) <= SNAP_THRESHOLD) {
                                        if (curP.type === 'top') snappedTop = othP.val;
                                        else if (curP.type === 'center') snappedTop = othP.val - elH / 2;
                                        else if (curP.type === 'bottom') snappedTop = othP.val - elH;

                                        showGuideY = true;
                                        guideYPos = othP.val;
                                        break;
                                    }
                                }
                                if (showGuideY) break;
                            }
                            if (showGuideY) break;
                        }

                        el.style.left = snappedLeft + 'px';
                        el.style.top = snappedTop + 'px';

                        if (guideX) {
                            guideX.style.display = showGuideX ? 'block' : 'none';
                            if (showGuideX) guideX.style.left = guideXPos + 'px';
                        }

                        if (guideY) {
                            guideY.style.display = showGuideY ? 'block' : 'none';
                            if (showGuideY) guideY.style.top = guideYPos + 'px';
                        }
                    }
                });

                document.addEventListener('mouseup', function () {
                    if (isDragging) {
                        isDragging = false;
                        hideSnapGuides();

                        selectedCanvaElement = el;
                        document.querySelectorAll('.canva-drag-element').forEach(item => item.classList.remove('selected-element'));
                        el.classList.add('selected-element');
                        positionFloatingToolbar(el);
                        syncSystemSidebarBadges();
                    }
                });

                // DOBLE CLIC: Entrar en modo edición de texto enriquecido (estilo Canva / Figma)
                el.addEventListener('dblclick', function(e) {
                    e.stopPropagation();
                    selectCanvaElement(el);
                    enableTextEditMode(el);
                    saveCurrentSelection();
                });

                // CLIC SIMPLE: Seleccionar elemento
                el.addEventListener('click', function() {
                    if (!hasMoved) {
                        selectCanvaElement(el);
                    }
                });

                const box = el.querySelector('.canva-drag-box-container');
                if (box) {
                    box.addEventListener('blur', function() {
                        setTimeout(() => {
                            if (document.activeElement !== box) {
                                disableTextEditMode(el);
                            }
                        }, 250);
                    });
                    box.addEventListener('mouseup', function() {
                        if (box.getAttribute('contenteditable') === 'true') {
                            saveCurrentSelection();
                        }
                    });
                    box.addEventListener('keyup', function() {
                        if (box.getAttribute('contenteditable') === 'true') {
                            saveCurrentSelection();
                        }
                    });
                }
            });
        }

        // Navegación con Teclas de Dirección (← ↑ → ↓) para Mover Etiquetas
        document.addEventListener('keydown', function (e) {
            const modal = document.getElementById('canvaStudioModal');
            if (!modal || !modal.classList.contains('active')) return;
            if (!selectedCanvaElement) return;

            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;

            const arrowKeys = ['ArrowLeft', 'ArrowUp', 'ArrowRight', 'ArrowDown'];
            if (!arrowKeys.includes(e.key)) return;

            e.preventDefault();

            const step = e.shiftKey ? 5 : 1;
            const computed = window.getComputedStyle(selectedCanvaElement);
            let currLeft = parseInt(selectedCanvaElement.style.left || computed.left, 10) || 0;
            let currTop = parseInt(selectedCanvaElement.style.top || computed.top, 10) || 0;

            if (e.key === 'ArrowLeft') currLeft -= step;
            if (e.key === 'ArrowRight') currLeft += step;
            if (e.key === 'ArrowUp') currTop -= step;
            if (e.key === 'ArrowDown') currTop += step;

            selectedCanvaElement.style.left = currLeft + 'px';
            selectedCanvaElement.style.top = currTop + 'px';

            positionFloatingToolbar(selectedCanvaElement);
        });

        function createNewCustomTag() {
            const input = document.getElementById('newCustomTagInput');
            const tagText = input.value.trim();

            if (!tagText) {
                Swal.fire({
                    title: 'Escribe un texto',
                    text: 'Ingresa el nombre o etiqueta para añadir al boleto.',
                    icon: 'info',
                    background: '#14141E',
                    color: '#FFF'
                });
                return;
            }

            const elementId = 'canvaCustomTag_' + Date.now();
            const canvasArea = document.getElementById('canvaCanvasArea');

            const tagEl = document.createElement('div');
            tagEl.className = 'canva-drag-element';
            tagEl.id = elementId;
            tagEl.setAttribute('data-tag-text', tagText);
            tagEl.style.top = (40 + (customTagCounter * 25)) + 'px';
            tagEl.style.left = '120px';

            tagEl.innerHTML = `
                <div class="canva-drag-box-container has-badge-bg" style="background: rgba(255, 85, 0, 0.25); border: 1.5px solid var(--color-primary-orange); padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 800; font-size: 0.85rem; color: #FFFFFF; pointer-events: none; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">
                    🏷️ ${tagText}
                </div>
            `;

            canvasArea.appendChild(tagEl);
            makeCanvaElementsDraggable();
            selectCanvaElement(tagEl);
            input.value = '';
            customTagCounter++;

            Swal.fire({
                title: '¡Etiqueta Añadida!',
                text: `Se agregó "${tagText}" al lienzo. Arrástrala a cualquier posición libremente.`,
                icon: 'success',
                timer: 1200,
                showConfirmButton: false,
                background: '#14141E',
                color: '#FFFFFF'
            });
        }

        function removeCanvaElement(elementId) {
            const el = document.getElementById(elementId);
            if (el) {
                el.style.display = 'none';
                if (selectedCanvaElement && selectedCanvaElement.id === elementId) {
                    selectCanvaElement(null);
                } else {
                    syncSystemSidebarBadges();
                }
            }
        }

        function changeCanvaBg(color) {
            document.getElementById('canvaTicketCanvas').style.backgroundColor = color;
            document.getElementById('canvaBgColor').value = color;
            document.getElementById('canvaBgColorText').value = color;
            adjustTextContrast(color);
        }

        function handleBgFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!file.type.match('image.*')) {
                Swal.fire({
                    title: 'Formato no soportado',
                    text: 'Por favor selecciona un archivo de imagen (PNG, JPG o WEBP).',
                    icon: 'warning',
                    background: '#14141E',
                    color: '#FFF'
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const dataUrl = e.target.result;
                changeCanvaBgImage(dataUrl, file.name);
                
                Swal.fire({
                    title: '¡Imagen de Fondo Cargada!',
                    text: `Se cargó "${file.name}" limpia y directamente en el lienzo.`,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false,
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            };
            reader.readAsDataURL(file);
        }

        function removeCanvaBgImage() {
            const fileInput = document.getElementById('canvaBgFileInput');
            if (fileInput) fileInput.value = '';
            changeCanvaBgImage('');
        }

        function handleShowBannerUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const dataUrl = e.target.result;
                
                const bannerEl = document.getElementById('canvaElBanner');
                if (bannerEl) {
                    const img = bannerEl.querySelector('img');
                    if (img) img.src = dataUrl;
                }

                const bannerThumb = document.getElementById('bannerThumbPreview');
                if (bannerThumb) bannerThumb.style.backgroundImage = `url('${dataUrl}')`;

                const bannerNameText = document.getElementById('bannerFileNameText');
                if (bannerNameText) bannerNameText.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }

        function removeShowBannerImage() {
            const defaultBanner = 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80';
            const bannerEl = document.getElementById('canvaElBanner');
            if (bannerEl) {
                const img = bannerEl.querySelector('img');
                if (img) img.src = defaultBanner;
            }

            const bannerThumb = document.getElementById('bannerThumbPreview');
            if (bannerThumb) bannerThumb.style.backgroundImage = `url('${defaultBanner}')`;

            const bannerNameText = document.getElementById('bannerFileNameText');
            if (bannerNameText) bannerNameText.textContent = 'Banner Oficial';

            const fileInput = document.getElementById('canvaShowBannerFileInput');
            if (fileInput) fileInput.value = '';
        }

        function changeCanvaBgImage(url, fileName = '') {
            const imgLayer = document.getElementById('canvaBgImageLayer');
            const input = document.getElementById('canvaBgImageInput');
            const boxEmpty = document.getElementById('bgUploadBoxEmpty');
            const boxFilled = document.getElementById('bgUploadBoxFilled');
            const thumbPreview = document.getElementById('bgThumbPreview');
            const fileNameText = document.getElementById('bgFileNameText');

            if (input) input.value = url || '';

            if (url && url.trim() !== '') {
                const formattedUrl = url.startsWith('http') || url.startsWith('data:') ? url : (url.startsWith('/') ? url : '/' + url);
                if (imgLayer) imgLayer.style.backgroundImage = `url('${formattedUrl}')`;

                if (boxEmpty) boxEmpty.style.display = 'none';
                if (boxFilled) boxFilled.style.display = 'flex';
                if (thumbPreview) thumbPreview.style.backgroundImage = `url('${formattedUrl}')`;
                if (fileNameText) fileNameText.textContent = fileName || 'Fondo Cargado';
            } else {
                if (imgLayer) imgLayer.style.backgroundImage = 'none';

                if (boxEmpty) boxEmpty.style.display = 'block';
                if (boxFilled) boxFilled.style.display = 'none';
            }

            adjustTextContrast(document.getElementById('canvaBgColor').value);
        }

        function changeCanvaStrip(color) {
            const sideStrip = document.getElementById('canvaSideStrip');
            if (sideStrip) sideStrip.style.background = color;
            document.getElementById('canvaStripColor').value = color;
            document.getElementById('canvaStripColorText').value = color;
        }

        function resetCanvaPositions(showAlert = true) {
            const currentId = document.getElementById('canvaTemplateId').value;
            const currentType = document.getElementById('canvaTemplateType').value;
            
            let items;
            if (currentType === 'virtual' || currentId >= '4') {
                items = [
                    { id: 'canvaElLogo', top: '15px', left: '25px' },
                    { id: 'canvaElTitle', top: '55px', left: '25px' },
                    { id: 'canvaElZone', top: '95px', left: '25px' },
                    { id: 'canvaElPrice', top: '95px', left: '180px' },
                    { id: 'canvaElBanner', top: '15px', left: '330px' },
                    { id: 'canvaElBuyer', top: '150px', left: '25px' },
                    { id: 'canvaElVenue', top: '230px', left: '25px' },
                    { id: 'canvaElTicketNumber', top: '15px', left: '620px' },
                    { id: 'canvaElQR', top: '55px', left: '590px' },
                    { id: 'canvaElHash', top: '205px', left: '610px' },
                    { id: 'canvaElDisclaimer', top: '235px', left: '570px' }
                ];
            } else if (currentId == '2') {
                items = [
                    { id: 'canvaElTicketNumber', top: '15px', left: '35px' },
                    { id: 'canvaElQR', top: '50px', left: '25px' },
                    { id: 'canvaElHash', top: '205px', left: '35px' },
                    { id: 'canvaElDisclaimer', top: '245px', left: '15px' },
                    { id: 'canvaElTitle', top: '15px', left: '240px' },
                    { id: 'canvaElZone', top: '42px', left: '240px' },
                    { id: 'canvaElPrice', top: '15px', left: '560px' },
                    { id: 'canvaElBanner', top: '65px', left: '240px' },
                    { id: 'canvaElBuyer', top: '220px', left: '240px' },
                    { id: 'canvaElVenue', top: '220px', left: '460px' }
                ];
            } else {
                items = [
                    { id: 'canvaElTitle', top: '15px', left: '80px' },
                    { id: 'canvaElZone', top: '42px', left: '80px' },
                    { id: 'canvaElPrice', top: '15px', left: '420px' },
                    { id: 'canvaElBanner', top: '65px', left: '80px' },
                    { id: 'canvaElBuyer', top: '220px', left: '80px' },
                    { id: 'canvaElVenue', top: '220px', left: '320px' },
                    { id: 'canvaElTicketNumber', top: '15px', left: '610px' },
                    { id: 'canvaElQR', top: '50px', left: '590px' },
                    { id: 'canvaElHash', top: '205px', left: '605px' },
                    { id: 'canvaElDisclaimer', top: '245px', left: '570px' }
                ];
            }

            items.forEach(item => {
                const el = document.getElementById(item.id);
                if (el) {
                    el.style.top = item.top;
                    el.style.left = item.left;
                    el.style.width = '';
                    el.style.height = '';
                    el.style.right = '';
                    el.style.bottom = '';
                    el.style.transform = '';
                    el.removeAttribute('data-rotate');
                    const box = el.querySelector('.canva-drag-box-container');
                    if (box) {
                        box.classList.remove('has-badge-bg');
                        if (defaultElementsHtml && defaultElementsHtml[item.id]) {
                            box.innerHTML = defaultElementsHtml[item.id];
                        }
                    }
                }
            });

            if (showAlert) {
                Swal.fire({
                    title: 'Posiciones Restablecidas',
                    text: 'Todos los elementos volvieron a su alineación base en el lienzo 20.40cm × 9.80cm.',
                    icon: 'info',
                    background: '#14141E',
                    color: '#FFFFFF',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            syncSystemSidebarBadges();
        }

        function openCanvaStudioForEdit(id, name, type, bgColor, stripColor, positionsJson, bgImage) {
            document.getElementById('canvaTemplateId').value = id;
            document.getElementById('canvaTemplateNameInput').value = name;
            
            setModalTemplateType(type || (id >= 4 ? 'virtual' : 'fisica'));
            applyTemplateStructureMode(id, name);
            changeCanvaBg(bgColor || '#FFFFFF');
            changeCanvaStrip(stripColor || '#000000');
            changeCanvaBgImage(bgImage || '');

            // Limpiar etiquetas personalizadas anteriores del modal
            document.querySelectorAll('.canva-drag-element[id^="canvaCustomTag_"]').forEach(el => el.remove());

            resetCanvaPositions(false);

            // Reconstruir etiquetas personalizadas si vienen en positionsJson
            if (positionsJson && typeof positionsJson === 'object') {
                const canvasArea = document.getElementById('canvaCanvasArea');
                Object.keys(positionsJson).forEach(elementId => {
                    if (elementId.startsWith('canvaCustomTag_')) {
                        const p = positionsJson[elementId];
                        const tagText = p.text || 'Etiqueta';
                        const tagEl = document.createElement('div');
                        tagEl.className = 'canva-drag-element';
                        tagEl.id = elementId;
                        tagEl.setAttribute('data-tag-text', tagText);
                        tagEl.style.top = p.top || '40px';
                        tagEl.style.left = p.left || '120px';
                        if (p.width) tagEl.style.width = p.width;
                        if (p.height) tagEl.style.height = p.height;
                        const customHtml = p.html || `🏷️ ${tagText}`;
                        tagEl.innerHTML = `
                            <div class="canva-drag-box-container ${p.hasBadgeBg ? 'has-badge-bg' : ''}" contenteditable="true" spellcheck="false" style="background: rgba(255, 85, 0, 0.25); border: 1.5px solid var(--color-primary-orange); padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 800; font-size: 0.85rem; color: #FFFFFF; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">
                                ${customHtml}
                            </div>
                        `;
                        if (canvasArea) canvasArea.appendChild(tagEl);
                    }
                });
                makeCanvaElementsDraggable();
            }

            // Aplicar coordenadas, estilos y HTML guardados en MySQL
            if (positionsJson && typeof positionsJson === 'object') {
                Object.keys(positionsJson).forEach(elementId => {
                    const el = document.getElementById(elementId);
                    if (el && positionsJson[elementId]) {
                        const p = positionsJson[elementId];
                        if (p.top) el.style.top = p.top;
                        if (p.left) el.style.left = p.left;
                        if (p.width) el.style.width = p.width;
                        if (p.height) el.style.height = p.height;

                        const box = el.querySelector('.canva-drag-box-container');
                        if (box && p.html) {
                            box.innerHTML = p.html;
                        }

                        if (p.textAlign) el.style.textAlign = p.textAlign;
                        if (p.fontFamily) el.style.fontFamily = p.fontFamily;
                        if (p.fontSize) el.style.fontSize = p.fontSize;
                        if (p.color) el.style.color = p.color;
                        if (p.fontWeight) el.style.fontWeight = p.fontWeight;
                        if (p.fontStyle) el.style.fontStyle = p.fontStyle;
                        if (p.textTransform) el.style.textTransform = p.textTransform;
                        if (p.hasBadgeBg) {
                            el.querySelector('.canva-drag-box-container')?.classList.add('has-badge-bg');
                        }
                        if (p.rotate && p.rotate !== '0') {
                            el.setAttribute('data-rotate', p.rotate);
                            el.style.transform = `rotate(${p.rotate}deg)`;
                        }

                        el.style.right = '';
                        el.style.bottom = '';

                        if (p.hidden === true || p.display === 'none') {
                            el.style.display = 'none';
                        } else if (p.display) {
                            el.style.display = p.display;
                        }
                    }
                });
            }

            adjustTextContrast(bgColor || '#FFFFFF');
            syncSystemSidebarBadges();
            document.getElementById('canvaStudioModal').classList.add('active');
        }

        function saveCanvaTemplateToDb() {
            const templateId = document.getElementById('canvaTemplateId').value;
            const name = document.getElementById('canvaTemplateNameInput').value;
            const type = document.getElementById('canvaTemplateType').value || 'fisica';
            const bgColor = document.getElementById('canvaBgColor').value;
            const bgImage = document.getElementById('canvaBgImageInput')?.value.trim() || null;
            const stripColor = document.getElementById('canvaStripColor').value;

            const positions = {};
            const elements = [];
            document.querySelectorAll('.canva-drag-element').forEach(el => {
                const id = el.id;
                const computed = window.getComputedStyle(el);
                const top = el.style.top || computed.top;
                const left = el.style.left || computed.left;
                const width = el.style.width || '';
                const height = el.style.height || '';
                const isHidden = (el.style.display === 'none');
                let tagText = el.getAttribute('data-tag-text') || '';
                if (!tagText && id.startsWith('canvaCustomTag_')) {
                    tagText = el.innerText.replace(/^🏷️\s*/, '').trim();
                }

                const box = el.querySelector('.canva-drag-box-container');
                const hasBadgeBg = box ? box.classList.contains('has-badge-bg') : false;
                const rotateDeg = el.getAttribute('data-rotate') || '0';
                const htmlContent = box ? box.innerHTML : el.innerHTML;

                positions[id] = { 
                    top: top, 
                    left: left, 
                    width: width,
                    height: height,
                    html: htmlContent,
                    text: tagText,
                    display: isHidden ? 'none' : (el.style.display || 'inline-flex'),
                    hidden: isHidden,
                    textAlign: el.style.textAlign || (box ? box.style.textAlign : '') || computed.textAlign || 'left',
                    fontFamily: el.style.fontFamily || '',
                    fontSize: el.style.fontSize || computed.fontSize || '',
                    color: el.style.color || rgbToHex(computed.color) || '',
                    fontWeight: el.style.fontWeight || computed.fontWeight || '',
                    fontStyle: el.style.fontStyle || computed.fontStyle || '',
                    textTransform: el.style.textTransform || computed.textTransform || 'none',
                    hasBadgeBg: hasBadgeBg,
                    rotate: rotateDeg
                };
                elements.push({ id: id, text: el.innerText.trim(), hidden: isHidden });
            });

            const categoryName = (type === 'virtual') ? 'Virtual: E-Ticket' : 'Estructura Física';

            const payload = {
                name: name,
                category: categoryName,
                type: type,
                bg_color: bgColor,
                bg_image: bgImage,
                strip_color: stripColor,
                positions: positions,
                elements: elements
            };

            const url = templateId ? `/admin/plantillas/${templateId}` : '/admin/plantillas';
            const method = templateId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
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
                    if (data.template && data.template.id) {
                        document.getElementById('canvaTemplateId').value = data.template.id;
                    }
                    Swal.fire({
                        title: '🎉 ¡Cambios Guardados con Éxito!',
                        text: `Se guardó la plantilla ${type === 'virtual' ? 'Virtual' : 'Física'} "${name}" con fondo limpio y formato 20.40cm × 9.80cm en MySQL.`,
                        icon: 'success',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({ title: 'Error', text: 'No se pudieron guardar los cambios.', icon: 'error', background: '#14141E', color: '#FFF' });
                }
            })
            .catch(err => {
                Swal.fire({ title: 'Error de Red', text: err.message, icon: 'error', background: '#14141E', color: '#FFF' });
            });
        }

        function duplicateTemplateInDb(id, name, type, bgImage) {
            fetch('/admin/plantillas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: `${name} (Copia)`,
                    category: (type === 'virtual' ? 'Virtual: E-Ticket' : 'Estructura Física'),
                    type: type || 'fisica',
                    bg_color: '#FFFFFF',
                    bg_image: bgImage || null,
                    strip_color: '#000000'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '📋 Plantilla Duplicada',
                        text: `Se ha guardado en la base de datos la copia "${name} (Copia)".`,
                        icon: 'success',
                        background: '#14141E',
                        color: '#FFFFFF'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }

        function deleteTemplateFromDb(id, name) {
            Swal.fire({
                title: '¿Eliminar de la Base de Datos?',
                text: `¿Estás seguro de que deseas eliminar permanentemente la plantilla "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/plantillas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const card = document.getElementById(`templateCard_${id}`);
                            if (card) card.remove();

                            Swal.fire({
                                title: 'Eliminada',
                                text: `La plantilla "${name}" fue eliminada.`,
                                icon: 'success',
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
