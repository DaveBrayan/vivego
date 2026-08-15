@extends('layouts.app')

@push('styles')
    <style>
        .swal2-container {
            z-index: 999999 !important;
        }
        .canva-floating-toolbar {
            position: absolute;
            z-index: 9999;
            background: #14141E;
            border: 1.5px solid rgba(255, 85, 0, 0.7);
            border-radius: 14px;
            padding: 0.35rem 0.65rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            backdrop-filter: blur(10px);
        }
        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 2px;
            background: rgba(255, 255, 255, 0.06);
            padding: 2px 4px;
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
        .color-picker-input {
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: transparent;
            padding: 0;
        }
        .canva-drag-element {
            cursor: move;
            transition: box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .canva-drag-element.selected-element {
            outline: 2px dashed #FF5500 !important;
            outline-offset: 3px;
            box-shadow: 0 0 15px rgba(255, 85, 0, 0.4) !important;
        }
    </style>
@endpush

@section('title', 'Plantillas de Boletos Canva | Vive Go')

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
                    <li class="dash-nav-item">
                        <a href="{{ route('web.events') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎟️</span>
                            <span class="dash-nav-text">Mis Eventos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
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
                    <li class="dash-nav-item active">
                        <a href="{{ route('web.templates') }}" class="dash-nav-link">
                            <span class="dash-nav-icon">🎨</span>
                            <span class="dash-nav-text">Plantillas de Boletos</span>
                        </a>
                    </li>
                    <li class="dash-nav-item">
                        <a href="#" class="dash-nav-link">
                            <span class="dash-nav-icon">🏷️</span>
                            <span class="dash-nav-text">Descuentos & Promos</span>
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
                    <input type="text" id="tableFilterInput" class="dash-search-input" placeholder="Buscar plantilla de boleto...">
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
                        <span class="settings-tag">🎨 ESTUDIO DE DISEÑO CANVA</span>
                        <h1 class="settings-page-title">Plantillas de Boletos</h1>
                        <p class="settings-page-subtitle">Plantilla 1 oficial (Logo Izquierda), Plantilla 2 (Logo Derecho) y Plantilla 3 (Banner Panorámico).</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-save-settings" id="btnOpenCanvaStudioModal" style="white-space: nowrap; padding: 0.85rem 1.6rem; font-size: 0.95rem;">
                            ➕ Crear Nueva Plantilla Canva
                        </button>
                    </div>
                </div>

                <!-- GRID DE PLANTILLAS DE BOLETOS DESDE MYSQL -->
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem;" id="templatesGrid">
                    @foreach($templates as $tpl)
                        <div class="settings-card-box" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.25rem;" id="templateCard_{{ $tpl->id }}">
                            <div>
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                                    <span class="dash-badge-custom badge-orange" style="font-size: 0.775rem;">
                                        {{ $tpl->category }}
                                    </span>
                                    @if($tpl->is_default)
                                        <span class="dash-badge-custom badge-green" style="font-size: 0.725rem;">
                                            ★ Predeterminada
                                        </span>
                                    @endif
                                </div>

                                <!-- THUMBNAIL REALISTA CON ESTRUCTURA DIVERSIFICADA DINÁMICA -->
                                <div style="position: relative; height: 160px; border-radius: 14px; overflow: hidden; background: {{ $tpl->bg_color }}; border: 1.5px solid rgba(255,255,255,0.15); display: flex; margin-bottom: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.25);">
                                    @if($tpl->id == 1 || $tpl->is_default || str_contains($tpl->category, 'Logo Izquierda'))
                                        <!-- ESTRUCTURA 1: TAQUILLA CLÁSICA OFICIAL CON FRANJA DE LOGO A LA IZQUIERDA -->
                                        <div style="width: 36px; background: {{ $tpl->strip_color }}; display: flex; align-items: center; justify-content: center;">
                                            <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="height: 22px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 6px rgba(255,85,0,0.6));">
                                        </div>
                                        <div style="flex: 1; padding: 0.85rem; display: flex; flex-direction: column; justify-content: space-between; color: #000000;">
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
                                        <div style="width: 75px; background: #FAFAFA; border-left: 1px dashed #CBD5E1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem;">
                                            <span style="font-size: 0.6rem; font-weight: 900; color: #000000;">N° 00396</span>
                                            <div style="width: 35px; height: 35px; background: #000000; border-radius: 4px;"></div>
                                        </div>
                                    @elseif($tpl->id == 2 || str_contains($tpl->category, 'Logo Derecho'))
                                        <!-- ESTRUCTURA 2: TAQUILLA CON FRANJA DEL LOGO A LA DERECHA (STUB A LA IZQUIERDA) -->
                                        <div style="width: 75px; background: #FAFAFA; border-right: 1px dashed #CBD5E1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem;">
                                            <span style="font-size: 0.6rem; font-weight: 900; color: #000000;">N° 00396</span>
                                            <div style="width: 35px; height: 35px; background: #000000; border-radius: 4px;"></div>
                                        </div>
                                        <div style="flex: 1; padding: 0.85rem; display: flex; flex-direction: column; justify-content: space-between; color: #000000;">
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
                                        <div style="width: 36px; background: {{ $tpl->strip_color }}; display: flex; align-items: center; justify-content: center;">
                                            <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="height: 22px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 6px rgba(255,85,0,0.6));">
                                        </div>
                                    @else
                                        <!-- ESTRUCTURA 3: HERO BANNER PANORÁMICO SUPERIOR -->
                                        <div style="flex: 1; padding: 0.75rem; display: flex; flex-direction: column; justify-content: space-between; color: #FFFFFF;">
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
                                <p style="font-size: 0.825rem; color: #94A3B8; margin: 0 0 1rem 0;">ID BD: #{{ $tpl->id }} • {{ $tpl->created_at->format('d/m/Y') }}</p>
                            </div>

                            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; padding: 0.65rem; font-size: 0.825rem; text-align: center; justify-content: center;" onclick="openCanvaStudioForEdit({{ $tpl->id }}, '{{ addslashes($tpl->name) }}', '{{ $tpl->bg_color }}', '{{ $tpl->strip_color }}', {{ json_encode($tpl->positions ?? []) }})">
                                    🎨 Editar en Canva
                                </button>
                                <button type="button" class="dash-btn-icon-action" style="padding: 0.65rem;" title="Duplicar Plantilla" onclick="duplicateTemplateInDb({{ $tpl->id }}, '{{ addslashes($tpl->name) }}')">
                                    📋
                                </button>
                                <button type="button" class="dash-btn-icon-action btn-delete-action" style="padding: 0.65rem; color: #EF4444;" title="Eliminar Plantilla" onclick="deleteTemplateFromDb({{ $tpl->id }}, '{{ addslashes($tpl->name) }}')">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL CANVA STUDIO (EDITOR INTERACTIVO DRAG & DROP CON CONEXIÓN A BASE DE DATOS) -->
    <div class="admin-modal-overlay" id="canvaStudioModal" style="align-items: center; padding: 1rem;">
        <div class="admin-modal-card" style="width: 95vw; max-width: 1400px; height: 92vh; display: flex; flex-direction: column; padding: 0; background: #0F0F17; border: 1.5px solid rgba(255,255,255,0.15); border-radius: 24px; overflow: hidden;">
            
            <!-- HEADER DEL CANVA STUDIO -->
            <div style="padding: 1rem 1.75rem; background: #14141E; border-bottom: 1.5px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="card-header-icon" style="width: 40px; height: 40px; background: linear-gradient(135deg, #06B6D4, #FF5500); color: #FFFFFF; font-size: 1.2rem;">🎨</div>
                    <div>
                        <input type="hidden" id="canvaTemplateId" value="">
                        <input type="text" id="canvaTemplateNameInput" style="background: transparent; border: none; font-weight: 900; font-size: 1.15rem; color: #FFFFFF; outline: none; border-bottom: 1px dashed rgba(255,255,255,0.3);" value="Nueva Plantilla Canva 2026">
                        <p style="font-size: 0.775rem; color: #94A3B8; margin: 0;">Guardado en MySQL • Arrastra, agrega o elimina cualquier etiqueta libremente</p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <button type="button" class="btn btn-cancel-custom" onclick="resetCanvaPositions(true)" style="padding: 0.6rem 1.2rem; font-size: 0.85rem;">
                        🔄 Restablecer Posiciones
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
                    
                    <!-- SECCIÓN: AGREGAR ETIQUETA / TEXTO PERSONALIZADO -->
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

                    <div>
                        <h4 style="font-size: 0.85rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.75rem;">📌 CAMPOS DEL SISTEMA DISPONIBLES</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;" id="systemElementsList">
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_logo" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('logo')">
                                <span>🖼️ Logo Marca Oficial</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_titulo" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('titulo')">
                                <span>📝 Título / Nombre Show</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_zona" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('zona')">
                                <span>🏷️ Zona / Sector</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_precio" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('precio')">
                                <span>💰 Precio de Entrada</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_banner" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('banner')">
                                <span>🖼️ Banner del Show</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_qr" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('qr')">
                                <span>📲 Código QR Gigante</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_comprador" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('comprador')">
                                <span>👤 Datos de Comprador</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_recinto" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('recinto')">
                                <span>📍 Recinto & Fecha</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>

                            <!-- BOTONES PARA CAMPOS DEL DESPRENDIBLE -->
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_ticket_number" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('ticket_number')">
                                <span>🔢 N° Correlativo (00396)</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_hash" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('hash')">
                                <span>🔑 Hash Validación</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                            <button type="button" class="btn btn-cancel-custom system-field-btn" id="sysBtn_disclaimer" style="justify-content: space-between; font-size: 0.85rem; padding: 0.6rem 0.85rem; transition: all 0.2s ease;" onclick="toggleSystemElement('disclaimer')">
                                <span>📜 Disclaimer / Nota Legal</span>
                                <span class="field-status-badge" style="font-size: 0.75rem; color: #06B6D4;">+ Añadir</span>
                            </button>
                        </div>
                    </div>

                    <div style="border-top: 1px solid rgba(255,255,255,0.1); paddingTop: 1rem;">
                        <h4 style="font-size: 0.85rem; font-weight: 900; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 0.75rem;">🎨 PERSONALIZACIÓN DE COLORES</h4>
                        
                        <div class="form-group-custom" style="margin-bottom: 0.85rem;">
                            <label class="form-label-custom">Color de Fondo del Boleto</label>
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="color" id="canvaBgColor" value="#FFFFFF" onchange="changeCanvaBg(this.value)" style="width: 40px; height: 38px; border-radius: 8px; border: none; cursor: pointer;">
                                <input type="text" id="canvaBgColorText" class="form-input-custom" value="#FFFFFF" readonly style="flex: 1;">
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom">Color de Franja Accento</label>
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="color" id="canvaStripColor" value="#000000" onchange="changeCanvaStrip(this.value)" style="width: 40px; height: 38px; border-radius: 8px; border: none; cursor: pointer;">
                                <input type="text" id="canvaStripColorText" class="form-input-custom" value="#000000" readonly style="flex: 1;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: LIENZO INTERACTIVO CANVA ADAPTABLE (900PX X 330PX) -->
                <div style="background: #09090E; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: auto; position: relative;">
                    
                    <div style="margin-bottom: 1rem; background: rgba(255,255,255,0.05); padding: 0.5rem 1.25rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3);">
                        🖐️ 100% Transformación Libre: Arrastra Correlativo N°, QR, Hash, Disclaimer, Zona, Precio y Recinto libremente
                    </div>

                    <!-- LIENZO PRINCIPAL CANVA ADAPTABLE (900PX X 300PX) -->
                    <div class="live-ticket-card" id="canvaTicketCanvas" style="position: relative; width: 900px; height: 300px; border-radius: 20px; box-shadow: 0 35px 90px rgba(0,0,0,0.85); overflow: hidden; background: #FFFFFF; transition: background 0.3s ease;">
                        
                        <!-- FRANJA LATERAL (POSICIÓN ADAPTABLE: IZQUIERDA EN PLANTILLA 1, DERECHA EN PLANTILLA 2) -->
                        <div class="ticket-side-strip" id="canvaSideStrip" style="width: 78px; background: #000000; height: 100%; position: absolute; left: 0; top: 0; display: flex; align-items: center; justify-content: center; z-index: 2;">
                            <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="max-width: 240px; height: 48px; width: auto; object-fit: contain; transform: rotate(-90deg); filter: drop-shadow(0 0 10px rgba(255,85,0,0.6));">
                        </div>

                        <!-- CUERPO PRINCIPAL DEL LIENZO DENTRO DEL TICKET -->
                        <div style="position: absolute; left: 78px; right: 250px; top: 0; bottom: 0; background: #FFFFFF; border-right: 2px dashed #CBD5E1;" id="canvaMainArea">
                            
                            <!-- ELEMENTO ARRASTRABLE: LOGO MARCA VIVE GO (PARA LIENZO CONTINUO MODO 3) -->
                            <div class="canva-drag-element" id="canvaElLogo" style="top: 20px; left: 25px; display: none;">
                                <img src="{{ asset($settings->logo_white ?? 'images/logo-white.png') }}" style="height: 38px; width: auto; object-fit: contain; pointer-events: none; filter: drop-shadow(0 0 10px rgba(255,85,0,0.6));">
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: BANNER SHOW -->
                            <div class="canva-drag-element" id="canvaElBanner" style="top: 75px; left: 20px; width: 500px; height: 135px;">
                                <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1000&q=80" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; pointer-events: none;">
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: TÍTULO SHOW -->
                            <div class="canva-drag-element" id="canvaElTitle" style="top: 15px; left: 20px;">
                                <h2 style="font-size: 1.15rem; font-weight: 900; color: #000000; margin: 0; pointer-events: none;">Chúpate la Plata con Son del Duke en Ayacucho</h2>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: ZONA -->
                            <div class="canva-drag-element" id="canvaElZone" style="top: 45px; left: 20px;">
                                <span style="font-size: 0.95rem; font-weight: 800; color: #1E293B; pointer-events: none;">ZONA VIP</span>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: PRECIO -->
                            <div class="canva-drag-element" id="canvaElPrice" style="top: 15px; left: 430px;">
                                <div style="text-align: right; pointer-events: none;">
                                    <span style="font-size: 0.8rem; font-weight: 900; color: #000000; display: block;">PRECIO:</span>
                                    <span style="font-size: 1.35rem; font-weight: 900; color: #000000; display: block;">S/ 55.50</span>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: COMPRADOR -->
                            <div class="canva-drag-element" id="canvaElBuyer" style="top: 220px; left: 20px;">
                                <div style="display: flex; flex-direction: column; font-size: 0.8rem; color: #000000; pointer-events: none;">
                                    <span style="font-size: 0.75rem; color: #475569;">Comprador:</span>
                                    <span style="font-weight: 900; font-size: 0.95rem; text-transform: uppercase;">CHRISTIAN GOMEZ LUJAN</span>
                                    <span style="font-weight: 800; font-size: 0.85rem; color: #1E293B;">DNI: 70436491</span>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: RECINTO & FECHA -->
                            <div class="canva-drag-element" id="canvaElVenue" style="top: 340px; left: 340px;">
                                <div style="text-align: right; display: flex; flex-direction: column; font-size: 0.8rem; color: #000000; pointer-events: none;">
                                    <span style="font-weight: 900; font-size: 1rem;">Complejo San Luis</span>
                                    <span style="font-size: 0.825rem; font-weight: 700; color: #334155;">Av. Cusco 528 - AYACUCHO</span>
                                    <span style="font-weight: 900; font-size: 1.05rem;">10.04.2025 / 06:00PM</span>
                                </div>
                            </div>
                        </div>

                        <!-- STUB DERECHO O IZQUIERDO DESPRENDIBLE (CANVA STUB - CON QR, N° CORRELATIVO, HASH Y DISCLAIMER) -->
                        <div style="position: absolute; right: 0; top: 0; width: 250px; height: 100%; background: #FAFAFA; border-left: 2px dashed #CBD5E1;" id="canvaStubArea">
                            
                            <!-- ELEMENTO ARRASTRABLE: N° CORRELATIVO -->
                            <div class="canva-drag-element" id="canvaElTicketNumber" style="top: 12px; left: 65px;">
                                <span style="font-size: 1.25rem; font-weight: 900; color: #000000; font-family: var(--font-heading); pointer-events: none;">N° 00396</span>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: CÓDIGO QR GIGANTE -->
                            <div class="canva-drag-element" id="canvaElQR" style="top: 40px; left: 50px;">
                                <div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; pointer-events: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="150" height="150">
                                        <rect width="256" height="256" fill="#FFFFFF"/>
                                        <path d="M16,16H96V96H16Z M32,32V80H80V32Z M48,48H64V64H48Z" fill="#000000"/>
                                        <path d="M160,16H240V96H160Z M176,32V80H224V32Z M192,48H208V64H192Z" fill="#000000"/>
                                        <path d="M16,160H96V240H16Z M32,176V224H80V176Z M48,192H64V208H48Z" fill="#000000"/>
                                        <path d="M112,16H144V32H112Z M112,48H128V80H112Z M144,64H160V96H144Z M112,96H128V112H112Z M16,112H48V128H16Z M64,112H96V144H64Z M128,128H160V144H128Z M176,112H224V128H176Z M208,128H240V160H208Z M112,160H144V176H112Z M144,176H176V192H144Z M112,192H128V240H112Z M160,208H192V224H160Z M208,192H240V240H208Z M176,224H208V240H176Z M144,224H160V240H144Z" fill="#000000"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: CÓDIGO HASH DE VALIDACIÓN -->
                            <div class="canva-drag-element" id="canvaElHash" style="top: 195px; left: 65px;">
                                <span style="font-family: monospace; font-size: 0.95rem; font-weight: 800; color: #000000; letter-spacing: 1.5px; pointer-events: none;">JAJHSPWFWJ</span>
                            </div>

                            <!-- ELEMENTO ARRASTRABLE: DISCLAIMER / NOTA LEGAL -->
                            <div class="canva-drag-element" id="canvaElDisclaimer" style="top: 235px; left: 15px; width: 220px;">
                                <div style="border-top: 2px stroke #000000; padding-top: 0.25rem; pointer-events: none;">
                                    <p style="font-size: 0.65rem; font-weight: 700; color: #334155; line-height: 1.25; margin: 0; text-align: center;">
                                        La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- LÍNEAS GUÍA DE ALINEACIÓN INTELIGENTE (EFECTO IMÁN / CANVA SNAP) -->
                        <div id="canvaSnapGuideX" style="position: absolute; top: 0; bottom: 0; width: 1.5px; border-left: 1.5px dashed #06B6D4; display: none; z-index: 9998; pointer-events: none; filter: drop-shadow(0 0 6px #06B6D4);"></div>
                        <div id="canvaSnapGuideY" style="position: absolute; left: 0; right: 0; height: 1.5px; border-top: 1.5px dashed #06B6D4; display: none; z-index: 9998; pointer-events: none; filter: drop-shadow(0 0 6px #06B6D4);"></div>

                        <!-- TOOLBAR FLOTANTE DE FORMATO ESTILO WORD / CANVA -->
                        <div id="canvaFloatingToolbar" class="canva-floating-toolbar" style="display: none;">
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" id="btnAlignLeft" onclick="applyFloatingFormat('align', 'left')" title="Alinear Izquierda">⬅️</button>
                                <button type="button" class="toolbar-btn" id="btnAlignCenter" onclick="applyFloatingFormat('align', 'center')" title="Centrar Texto">↔️</button>
                                <button type="button" class="toolbar-btn" id="btnAlignRight" onclick="applyFloatingFormat('align', 'right')" title="Alinear Derecha">➡️</button>
                            </div>

                            <div class="toolbar-divider"></div>

                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" onclick="applyFloatingFormat('fontSize', 'dec')" title="Disminuir Tamaño">➖</button>
                                <span id="floatingFontSizeText" class="font-size-indicator">14px</span>
                                <button type="button" class="toolbar-btn" onclick="applyFloatingFormat('fontSize', 'inc')" title="Aumentar Tamaño">➕</button>
                            </div>

                            <div class="toolbar-divider"></div>

                            <div class="toolbar-group" title="Color de Texto">
                                <input type="color" id="floatingColorPicker" onchange="applyFloatingFormat('color', this.value)" class="color-picker-input">
                            </div>

                            <div class="toolbar-divider"></div>

                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" id="btnFloatingBold" onclick="applyFloatingFormat('bold')" title="Negrita (Bold)" style="font-weight: 900; font-family: serif;">B</button>
                                <button type="button" class="toolbar-btn" id="btnFloatingItalic" onclick="applyFloatingFormat('italic')" title="Cursiva (Italic)" style="font-style: italic; font-family: serif;">I</button>
                            </div>

                            <div class="toolbar-divider"></div>

                            <button type="button" class="toolbar-btn toolbar-btn-danger" onclick="deleteSelectedCanvaElement()" title="Eliminar Etiqueta">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        let customTagCounter = 1;

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('canvaStudioModal');
            const openBtn = document.getElementById('btnOpenCanvaStudioModal');
            const closeBtn = document.getElementById('btnCloseCanvaStudioModal');

            if (openBtn && modal) {
                openBtn.addEventListener('click', function () {
                    document.getElementById('canvaTemplateId').value = '';
                    document.getElementById('canvaTemplateNameInput').value = 'Nueva Plantilla Canva 2026';
                    applyTemplateStructureMode(1, 'Plantilla 1');
                    resetCanvaPositions(false);
                    modal.classList.add('active');
                });
            }

            if (closeBtn && modal) {
                closeBtn.addEventListener('click', function () {
                    modal.classList.remove('active');
                });
            }

            makeCanvaElementsDraggable();
        });

        function applyTemplateStructureMode(id, name) {
            const sideStrip = document.getElementById('canvaSideStrip');
            const stubArea = document.getElementById('canvaStubArea');
            const mainArea = document.getElementById('canvaMainArea');
            const logoEl = document.getElementById('canvaElLogo');

            const isPlantilla1 = (id == 1 || (name && name.includes('Plantilla 1')));
            const isPlantilla2 = (id == 2 || (name && name.includes('Plantilla 2')));

            if (isPlantilla1) {
                // Plantilla 1: Franja del Logo a la IZQUIERDA, Desprendible a la DERECHA
                if (sideStrip) {
                    sideStrip.style.display = 'flex';
                    sideStrip.style.left = '0';
                    sideStrip.style.right = 'auto';
                }
                if (stubArea) {
                    stubArea.style.display = 'block';
                    stubArea.style.right = '0';
                    stubArea.style.left = 'auto';
                    stubArea.style.borderLeft = '2px dashed #CBD5E1';
                    stubArea.style.borderRight = 'none';
                }
                if (mainArea) {
                    mainArea.style.left = '78px';
                    mainArea.style.right = '250px';
                    mainArea.style.borderRight = '2px dashed #CBD5E1';
                    mainArea.style.borderLeft = 'none';
                }
                if (logoEl) logoEl.style.display = 'none';
            } else if (isPlantilla2) {
                // Plantilla 2: Franja del Logo al lado DERECHO, Desprendible a la IZQUIERDA
                if (sideStrip) {
                    sideStrip.style.display = 'flex';
                    sideStrip.style.right = '0';
                    sideStrip.style.left = 'auto';
                }
                if (stubArea) {
                    stubArea.style.display = 'block';
                    stubArea.style.left = '0';
                    stubArea.style.right = 'auto';
                    stubArea.style.borderRight = '2px dashed #CBD5E1';
                    stubArea.style.borderLeft = 'none';
                }
                if (mainArea) {
                    mainArea.style.left = '250px';
                    mainArea.style.right = '78px';
                    mainArea.style.borderLeft = '2px dashed #CBD5E1';
                    mainArea.style.borderRight = 'none';
                }
                if (logoEl) logoEl.style.display = 'none';
            } else {
                // Plantilla 3: Lienzo Continuo Unificado
                if (sideStrip) sideStrip.style.display = 'none';
                if (stubArea) stubArea.style.display = 'none';
                if (mainArea) {
                    mainArea.style.left = '0';
                    mainArea.style.right = '0';
                    mainArea.style.borderLeft = 'none';
                    mainArea.style.borderRight = 'none';
                }
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
            const isDark = isColorDark(bgColor);
            const primaryColor = isDark ? '#FFFFFF' : '#000000';
            const secondaryColor = isDark ? '#CBD5E1' : '#334155';

            const title = document.querySelector('#canvaElTitle h2');
            if (title) title.style.color = primaryColor;

            const ticketNum = document.querySelector('#canvaElTicketNumber span');
            if (ticketNum) ticketNum.style.color = primaryColor;

            const buyerSpans = document.querySelectorAll('#canvaElBuyer span');
            if (buyerSpans.length >= 3) {
                buyerSpans[0].style.color = secondaryColor;
                buyerSpans[1].style.color = primaryColor;
                buyerSpans[2].style.color = isDark ? '#CBD5E1' : '#1E293B';
            }

            const venueSpans = document.querySelectorAll('#canvaElVenue span');
            if (venueSpans.length >= 3) {
                venueSpans[0].style.color = primaryColor;
                venueSpans[1].style.color = secondaryColor;
                venueSpans[2].style.color = isDark ? '#FF9966' : '#FF5500';
            }

            const hash = document.querySelector('#canvaElHash span');
            if (hash) hash.style.color = secondaryColor;

            const disclaimer = document.querySelector('#canvaElDisclaimer p');
            if (disclaimer) disclaimer.style.color = isDark ? '#94A3B8' : '#475569';
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
                el.style.display = 'block';
                selectCanvaElement(el);
            } else {
                selectCanvaElement(el);
            }
        }

        function selectCanvaElement(el) {
            document.querySelectorAll('.canva-drag-element').forEach(item => {
                item.classList.remove('selected-element');
            });

            if (!el) {
                selectedCanvaElement = null;
                const toolbar = document.getElementById('canvaFloatingToolbar');
                if (toolbar) toolbar.style.display = 'none';
                syncSystemSidebarBadges();
                return;
            }

            selectedCanvaElement = el;
            el.classList.add('selected-element');

            positionFloatingToolbar(el);
            syncFloatingToolbarControls(el);
            syncSystemSidebarBadges();
        }

        function positionFloatingToolbar(el) {
            const toolbar = document.getElementById('canvaFloatingToolbar');
            const canvas = document.getElementById('canvaTicketCanvas');
            if (!toolbar || !canvas || !el) return;

            toolbar.style.display = 'flex';

            let topPos = el.offsetTop + el.offsetHeight + 10;
            if (topPos + 45 > canvas.offsetHeight) {
                topPos = Math.max(5, el.offsetTop - 45);
            }

            let leftPos = Math.max(10, Math.min(el.offsetLeft, canvas.offsetWidth - 340));

            toolbar.style.top = topPos + 'px';
            toolbar.style.left = leftPos + 'px';
        }

        function syncFloatingToolbarControls(el) {
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
        }

        function applyFloatingFormat(action, val) {
            if (!selectedCanvaElement) return;

            if (action === 'align') {
                selectedCanvaElement.style.textAlign = val;
                if (val === 'center' || val === 'right') {
                    if (selectedCanvaElement.parentElement && selectedCanvaElement.parentElement.id === 'canvaMainArea') {
                        selectedCanvaElement.style.width = '100%';
                        selectedCanvaElement.style.left = '0px';
                    } else if (selectedCanvaElement.parentElement && selectedCanvaElement.parentElement.id === 'canvaStubArea') {
                        selectedCanvaElement.style.width = '100%';
                        selectedCanvaElement.style.left = '0px';
                    }
                }
                selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                    child.style.textAlign = val;
                });
            } else if (action === 'color') {
                selectedCanvaElement.style.color = val;
                selectedCanvaElement.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => {
                    child.style.color = val;
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
            }

            syncFloatingToolbarControls(selectedCanvaElement);
        }

        function deleteSelectedCanvaElement() {
            if (selectedCanvaElement) {
                removeCanvaElement(selectedCanvaElement.id);
                selectCanvaElement(null);
            }
        }

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
                let isDragging = false;
                let hasMoved = false;
                let startX, startY, initialLeft, initialTop;

                el.addEventListener('mousedown', function (e) {
                    if (e.target.classList.contains('canva-delete-badge')) return;

                    const toolbar = document.getElementById('canvaFloatingToolbar');
                    if (toolbar) toolbar.style.display = 'none';

                    isDragging = true;
                    hasMoved = false;
                    el.classList.add('selected');
                    startX = e.clientX;
                    startY = e.clientY;

                    el.style.right = '';
                    el.style.bottom = '';

                    const computed = window.getComputedStyle(el);
                    initialLeft = parseInt(computed.left, 10) || 0;
                    initialTop = parseInt(computed.top, 10) || 0;

                    e.stopPropagation();
                });

                document.addEventListener('mousemove', function (e) {
                    if (!isDragging) return;

                    const dx = e.clientX - startX;
                    const dy = e.clientY - startY;

                    if (Math.hypot(dx, dy) > 3) {
                        hasMoved = true;
                    }

                    // Seleccionar virtualmente la etiqueta para resaltar el campo en el menú izquierdo durante el arrastre
                    if (selectedCanvaElement !== el) {
                        selectedCanvaElement = el;
                        document.querySelectorAll('.canva-drag-element').forEach(item => item.classList.remove('selected-element'));
                        el.classList.add('selected-element');
                        syncSystemSidebarBadges();
                    }

                    let rawLeft = initialLeft + dx;
                    let rawTop = initialTop + dy;

                    const elW = el.offsetWidth;
                    const elH = el.offsetHeight;

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

                    // 1. Atracción Magnética Eje X (Alineación Vertical)
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

                    // 2. Atracción Magnética Eje Y (Alineación Horizontal)
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
                        if (showGuideX) {
                            guideX.style.display = 'block';
                            guideX.style.left = guideXPos + 'px';
                        } else {
                            guideX.style.display = 'none';
                        }
                    }

                    if (guideY) {
                        if (showGuideY) {
                            guideY.style.display = 'block';
                            guideY.style.top = guideYPos + 'px';
                        } else {
                            guideY.style.display = 'none';
                        }
                    }

                    const toolbar = document.getElementById('canvaFloatingToolbar');
                    if (toolbar) toolbar.style.display = 'none';
                });

                document.addEventListener('mouseup', function () {
                    if (isDragging) {
                        isDragging = false;
                        hideSnapGuides();
                        setTimeout(() => { el.classList.remove('selected'); }, 400);

                        if (hasMoved) {
                            // Al soltar tras arrastrar: mantener resaltada la etiqueta y el panel izquierdo, pero SIN abrir el menú flotante de alineación
                            selectedCanvaElement = el;
                            document.querySelectorAll('.canva-drag-element').forEach(item => item.classList.remove('selected-element'));
                            el.classList.add('selected-element');
                            const toolbar = document.getElementById('canvaFloatingToolbar');
                            if (toolbar) toolbar.style.display = 'none';
                            syncSystemSidebarBadges();
                        } else {
                            // Al hacer clic limpio y soltar: abrir el menú flotante de formato y alineación
                            selectCanvaElement(el);
                        }
                    }
                });
            });
        }

        // Navegación con Teclas de Dirección (← ↑ → ↓) para Mover Etiquetas Seleccionadas
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
            const mainArea = document.getElementById('canvaMainArea');

            const tagEl = document.createElement('div');
            tagEl.className = 'canva-drag-element';
            tagEl.id = elementId;
            tagEl.setAttribute('data-tag-text', tagText);
            tagEl.style.top = (40 + (customTagCounter * 25)) + 'px';
            tagEl.style.left = '120px';

            tagEl.innerHTML = `
                <div style="background: rgba(255, 85, 0, 0.15); border: 1.5px solid var(--color-primary-orange); padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 800; font-size: 0.85rem; color: #000000; pointer-events: none;">
                    🏷️ ${tagText}
                </div>
            `;

            mainArea.appendChild(tagEl);
            makeCanvaElementsDraggable();
            selectCanvaElement(tagEl);
            input.value = '';
            customTagCounter++;

            Swal.fire({
                title: '¡Etiqueta Añadida!',
                text: `Se agregó "${tagText}" al lienzo del boleto. Arrástrala a donde desees.`,
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

        function addSystemElement(type) {
            const map = {
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
            const elementId = map[type];
            if (elementId) {
                const el = document.getElementById(elementId);
                if (el) {
                    el.style.display = 'block';
                    selectCanvaElement(el);
                }
            }
        }

        function changeCanvaBg(color) {
            document.getElementById('canvaMainArea').style.background = color;
            document.getElementById('canvaTicketCanvas').style.background = color;
            document.getElementById('canvaBgColor').value = color;
            document.getElementById('canvaBgColorText').value = color;
            adjustTextContrast(color);
        }

        function changeCanvaStrip(color) {
            document.getElementById('canvaSideStrip').style.background = color;
            document.getElementById('canvaStripColor').value = color;
            document.getElementById('canvaStripColorText').value = color;
        }

        function resetCanvaPositions(showAlert = true) {
            const currentId = document.getElementById('canvaTemplateId').value;
            const isPlantilla1 = (currentId == '1' || !currentId);
            const isPlantilla2 = (currentId == '2');
            
            const plantilla1Items = [
                { id: 'canvaElTitle', top: '15px', left: '20px' },
                { id: 'canvaElZone', top: '45px', left: '20px' },
                { id: 'canvaElPrice', top: '15px', left: '430px' },
                { id: 'canvaElBanner', top: '75px', left: '20px' },
                { id: 'canvaElBuyer', top: '220px', left: '20px' },
                { id: 'canvaElVenue', top: '220px', left: '340px' },
                { id: 'canvaElTicketNumber', top: '12px', left: '65px' },
                { id: 'canvaElQR', top: '45px', left: '55px' },
                { id: 'canvaElHash', top: '195px', left: '65px' },
                { id: 'canvaElDisclaimer', top: '235px', left: '15px' }
            ];

            const plantilla2Items = [
                { id: 'canvaElTicketNumber', top: '12px', left: '65px' },
                { id: 'canvaElQR', top: '45px', left: '55px' },
                { id: 'canvaElHash', top: '195px', left: '65px' },
                { id: 'canvaElDisclaimer', top: '235px', left: '15px' },
                { id: 'canvaElTitle', top: '15px', left: '20px' },
                { id: 'canvaElZone', top: '45px', left: '20px' },
                { id: 'canvaElPrice', top: '15px', left: '380px' },
                { id: 'canvaElBanner', top: '75px', left: '20px' },
                { id: 'canvaElBuyer', top: '220px', left: '20px' },
                { id: 'canvaElVenue', top: '220px', left: '320px' }
            ];

            const plantilla3Items = [
                { id: 'canvaElLogo', top: '20px', left: '25px' },
                { id: 'canvaElTitle', top: '70px', left: '25px' },
                { id: 'canvaElZone', top: '115px', left: '25px' },
                { id: 'canvaElPrice', top: '115px', left: '150px' },
                { id: 'canvaElBanner', top: '20px', left: '320px' },
                { id: 'canvaElBuyer', top: '165px', left: '25px' },
                { id: 'canvaElVenue', top: '245px', left: '25px' },
                { id: 'canvaElTicketNumber', top: '20px', left: '710px' },
                { id: 'canvaElQR', top: '60px', left: '680px' },
                { id: 'canvaElHash', top: '205px', left: '700px' },
                { id: 'canvaElDisclaimer', top: '235px', left: '675px' }
            ];

            let items = plantilla1Items;
            if (isPlantilla2) items = plantilla2Items;
            else if (!isPlantilla1) items = plantilla3Items;

            items.forEach(item => {
                const el = document.getElementById(item.id);
                if (el) {
                    el.style.top = item.top;
                    el.style.left = item.left;
                    el.style.right = '';
                    el.style.bottom = '';
                }
            });

            if (showAlert) {
                Swal.fire({
                    title: 'Posiciones Restablecidas',
                    text: 'Todos los elementos volvieron a su alineación limpia predeterminada.',
                    icon: 'info',
                    background: '#14141E',
                    color: '#FFFFFF',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            syncSystemSidebarBadges();
        }

        function openCanvaStudioForEdit(id, name, bgColor, stripColor, positionsJson) {
            document.getElementById('canvaTemplateId').value = id;
            document.getElementById('canvaTemplateNameInput').value = name;
            
            applyTemplateStructureMode(id, name);
            changeCanvaBg(bgColor || '#FFFFFF');
            changeCanvaStrip(stripColor || '#000000');

            // Limpiar etiquetas personalizadas anteriores dinámicas del modal
            document.querySelectorAll('.canva-drag-element[id^="canvaCustomTag_"]').forEach(el => el.remove());

            resetCanvaPositions(false);

            // Reconstruir etiquetas personalizadas si vienen en positionsJson
            if (positionsJson && typeof positionsJson === 'object') {
                const mainArea = document.getElementById('canvaMainArea');
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
                        tagEl.innerHTML = `
                            <div style="background: rgba(255, 85, 0, 0.15); border: 1.5px solid var(--color-primary-orange); padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 800; font-size: 0.85rem; color: #000000; pointer-events: none;">
                                🏷️ ${tagText}
                            </div>
                        `;
                        if (mainArea) mainArea.appendChild(tagEl);
                    }
                });
                makeCanvaElementsDraggable();
            }

            // Aplicar coordenadas, estilos y estado de visibilidad guardados en MySQL
            if (positionsJson && typeof positionsJson === 'object') {
                Object.keys(positionsJson).forEach(elementId => {
                    const el = document.getElementById(elementId);
                    if (el && positionsJson[elementId]) {
                        const p = positionsJson[elementId];
                        if (p.top) el.style.top = p.top;
                        if (p.left) el.style.left = p.left;

                        if (p.textAlign) {
                            el.style.textAlign = p.textAlign;
                            el.querySelectorAll('h1, h2, h3, p, span, div').forEach(child => child.style.textAlign = p.textAlign);
                        }
                        if (p.fontSize) {
                            el.style.fontSize = p.fontSize;
                            el.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => child.style.fontSize = p.fontSize);
                        }
                        if (p.color) {
                            el.style.color = p.color;
                            el.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => child.style.color = p.color);
                        }
                        if (p.fontWeight) {
                            el.style.fontWeight = p.fontWeight;
                            el.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => child.style.fontWeight = p.fontWeight);
                        }
                        if (p.fontStyle) {
                            el.style.fontStyle = p.fontStyle;
                            el.querySelectorAll('h1, h2, h3, p, span, div, strong').forEach(child => child.style.fontStyle = p.fontStyle);
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
            const bgColor = document.getElementById('canvaBgColor').value;
            const stripColor = document.getElementById('canvaStripColor').value;

            const positions = {};
            const elements = [];
            document.querySelectorAll('.canva-drag-element').forEach(el => {
                const id = el.id;
                const computed = window.getComputedStyle(el);
                const top = el.style.top || computed.top;
                const left = el.style.left || computed.left;
                const isHidden = (el.style.display === 'none');
                let tagText = el.getAttribute('data-tag-text') || '';
                if (!tagText && id.startsWith('canvaCustomTag_')) {
                    tagText = el.innerText.replace(/^🏷️\s*/, '').trim();
                }

                positions[id] = { 
                    top: top, 
                    left: left, 
                    text: tagText,
                    display: isHidden ? 'none' : (el.style.display || 'block'),
                    hidden: isHidden,
                    textAlign: el.style.textAlign || computed.textAlign || 'left',
                    fontSize: el.style.fontSize || computed.fontSize || '',
                    color: el.style.color || rgbToHex(computed.color) || '',
                    fontWeight: el.style.fontWeight || computed.fontWeight || '',
                    fontStyle: el.style.fontStyle || computed.fontStyle || ''
                };
                elements.push({ id: id, text: el.innerText.trim(), hidden: isHidden });
            });

            const payload = {
                name: name,
                category: 'General',
                bg_color: bgColor,
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
                        text: `Se guardaron los cambios, los colores y las etiquetas visibles de la plantilla "${name}" exitosamente en MySQL.`,
                        icon: 'success',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                } else {
                    Swal.fire({ title: 'Error', text: 'No se pudieron guardar los cambios.', icon: 'error', background: '#14141E', color: '#FFF' });
                }
            })
            .catch(err => {
                Swal.fire({ title: 'Error de Red', text: err.message, icon: 'error', background: '#14141E', color: '#FFF' });
            });
        }

        function duplicateTemplateInDb(id, name) {
            fetch('/admin/plantillas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: `${name} (Copia)`,
                    category: 'General',
                    bg_color: '#FFFFFF',
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
                text: `¿Estás seguro de que deseas eliminar permanentemente la plantilla "${name}" de la base de datos?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Sí, eliminar de MySQL',
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
                                text: `La plantilla "${name}" fue eliminada de la base de datos.`,
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
