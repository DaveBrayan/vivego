<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner Móvil | {{ $event->title }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- SweetAlert2 & HTML5-QRCode -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        :root {
            --color-primary: #FF5500;
            --color-success: #10B981;
            --color-danger: #EF4444;
            --color-warning: #F59E0B;
            --color-info: #00F0FF;
            --color-purple: #8B5CF6;
            --color-dark-bg: #0A0A10;
            --color-card-bg: #14141E;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--color-dark-bg);
            color: #FFFFFF;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* HEADER MÓVIL FIJO */
        .mobile-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(20, 20, 30, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-pill {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--color-success);
            box-shadow: 0 0 10px var(--color-success);
            animation: pulseDot 2s infinite ease-in-out;
        }

        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.6; }
        }

        /* BARRA DE PESTAÑAS (TABS) */
        .scanner-nav-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 4px;
            gap: 4px;
            margin-bottom: 0.75rem;
        }

        .scanner-nav-tab {
            background: transparent;
            border: none;
            color: #94A3B8;
            font-size: 0.88rem;
            font-weight: 800;
            padding: 0.65rem 0.75rem;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.2s ease;
        }

        .scanner-nav-tab.active {
            background: linear-gradient(135deg, #FF5500, #FF7733);
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(255, 85, 0, 0.35);
        }

        .tab-badge-pill {
            background: rgba(255, 255, 255, 0.18);
            color: #FFFFFF;
            font-size: 0.7rem;
            font-weight: 900;
            padding: 0.1rem 0.45rem;
            border-radius: 20px;
            line-height: 1.2;
        }

        .scanner-nav-tab.active .tab-badge-pill {
            background: #FFFFFF;
            color: #FF5500;
        }

        /* CONTENEDOR PRINCIPAL */
        .scanner-container {
            flex: 1;
            padding: 0.85rem 1rem 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        /* TARJETA DE RESUMEN DEL EVENTO */
        .event-info-card {
            background: var(--color-card-bg);
            border: 1px solid rgba(255, 85, 0, 0.25);
            border-radius: 16px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .event-avatar {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            object-fit: cover;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            flex-shrink: 0;
        }

        /* CONTADORES KPI MÓVILES */
        .mobile-kpi-bar {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.5rem;
        }

        .kpi-mini-box {
            background: var(--color-card-bg);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.6rem 0.5rem;
            text-align: center;
        }

        .kpi-mini-box .val {
            font-size: 1.2rem;
            font-weight: 900;
            display: block;
        }

        .kpi-mini-box .lbl {
            font-size: 0.68rem;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
        }

        /* VISOR DE CÁMARA MÓVIL */
        .camera-viewport-card {
            background: #000000;
            border: 2px solid rgba(255, 85, 0, 0.4);
            border-radius: 20px;
            min-height: 270px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
        }

        #qrReaderVideoMobile {
            width: 100%;
            height: 100%;
        }

        #qrReaderVideoMobile video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 18px !important;
        }

        .laser-scan-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #FF5500, #00F0FF, #FF5500, transparent);
            box-shadow: 0 0 15px #FF5500;
            animation: scanLaser 2s infinite ease-in-out;
            z-index: 10;
            pointer-events: none;
        }

        @keyframes scanLaser {
            0% { top: 8%; opacity: 0.3; }
            50% { top: 92%; opacity: 1; }
            100% { top: 8%; opacity: 0.3; }
        }

        /* TOAST FLOTANTE SUPERIOR */
        .result-top-toast {
            position: fixed;
            top: 10px;
            left: 10px;
            right: 10px;
            max-width: 460px;
            margin: 0 auto;
            z-index: 99999;
            border-radius: 14px;
            padding: 0.75rem 0.95rem;
            display: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            animation: toastPopIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transition: opacity 0.25s ease;
        }

        @keyframes toastPopIn {
            from { transform: translateY(-20px) scale(0.96); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .result-granted {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.97), rgba(4, 120, 87, 0.97));
            border: 1.5px solid #10B981;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
        }

        .result-already-used {
            background: linear-gradient(135deg, rgba(120, 53, 15, 0.97), rgba(180, 83, 9, 0.97));
            border: 1.5px solid #F59E0B;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.45);
        }

        .result-invalid {
            background: linear-gradient(135deg, rgba(127, 29, 29, 0.97), rgba(185, 28, 28, 0.97));
            border: 1.5px solid #EF4444;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.45);
        }

        .result-upgraded-void {
            background: linear-gradient(135deg, rgba(88, 28, 135, 0.97), rgba(109, 40, 217, 0.97));
            border: 1.5px solid #8B5CF6;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.45);
        }

        /* ENTRADA MANUAL */
        .manual-input-box {
            display: flex;
            gap: 0.5rem;
            background: var(--color-card-bg);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 0.35rem 0.35rem 0.35rem 0.85rem;
        }

        .manual-input-box input {
            flex: 1;
            min-width: 0;
            background: transparent;
            border: none;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.9rem;
            outline: none;
            font-family: monospace;
            text-transform: uppercase;
        }

        .manual-input-box button {
            background: linear-gradient(135deg, #00F0FF, #00A3FF);
            color: #050B14;
            font-weight: 900;
            font-size: 0.85rem;
            border: none;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 240, 255, 0.3);
            white-space: nowrap;
        }

        /* FILTROS DE HISTORIAL */
        .history-filter-chip {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #94A3B8;
            font-size: 0.76rem;
            font-weight: 800;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .history-filter-chip.active {
            background: rgba(0, 240, 255, 0.15);
            border-color: #00F0FF;
            color: #00F0FF;
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.25);
        }

        /* TARJETA DE ITEM EN HISTORIAL */
        .history-item-card {
            background: #14141E;
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            padding: 0.75rem 0.95rem;
            margin-bottom: 0.55rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .history-card-granted {
            border-left: 4px solid #10B981;
            background: rgba(16, 185, 129, 0.04);
        }

        .history-card-already_used {
            border-left: 4px solid #F59E0B;
            background: rgba(245, 158, 11, 0.05);
        }

        .history-card-invalid {
            border-left: 4px solid #EF4444;
            background: rgba(239, 68, 68, 0.05);
        }

        .history-card-upgraded_void {
            border-left: 4px solid #8B5CF6;
            background: rgba(139, 92, 246, 0.05);
        }

        .history-card-wrong_event {
            border-left: 4px solid #3B82F6;
            background: rgba(59, 130, 246, 0.05);
        }
    </style>
</head>
<body>

    <!-- HEADER MÓVIL FIJO -->
    <header class="mobile-header">
        <div class="brand-pill">
            <span class="brand-dot"></span>
            <div>
                <strong style="font-size: 0.95rem; display: block; line-height: 1.1;">Vive Go Scanner</strong>
                <small style="color: #94A3B8; font-size: 0.7rem;">Control de Acceso en Vivo</small>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div style="display: flex; align-items: center; background: rgba(255,255,255,0.08); border: 1.5px solid rgba(0, 240, 255, 0.45); border-radius: 10px; padding: 0.2rem 0.55rem; gap: 0.35rem;" title="Nombre de este dispositivo / puerta (editable)">
                <span style="font-size: 0.85rem;">📱</span>
                <input type="text" id="mobileDeviceName" placeholder="Puerta / Móvil" style="background: transparent; border: none; color: #FFFFFF; font-size: 0.82rem; font-weight: 800; width: 110px; outline: none;">
            </div>
            <a href="{{ route('web.attendees') }}" style="color: #94A3B8; text-decoration: none; font-size: 1.2rem; padding: 0.3rem;" title="Salir de Scanner">✕</a>
        </div>
    </header>

    <div class="scanner-container">
        <!-- TOAST FLOTANTE SUPERIOR -->
        <div class="result-top-toast" id="mobileResultToast">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <span id="mResultIcon" style="font-size: 1.6rem; line-height: 1; flex-shrink: 0;">✅</span>
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.4rem; margin-bottom: 0.15rem;">
                        <strong id="mResultTitle" style="font-size: 0.92rem; font-weight: 900; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1.1;">¡ACCESO PERMITIDO!</strong>
                        <span id="mResultZone" style="font-weight: 900; font-size: 0.7rem; text-transform: uppercase; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.25); color: #FFFFFF; padding: 0.15rem 0.45rem; border-radius: 6px; white-space: nowrap;">VIP</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.4rem; font-size: 0.76rem; color: #F1F5F9; line-height: 1.2;">
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">👤 <strong style="color: #FFFFFF;" id="mResultBuyer">-</strong></span>
                        <span style="color: #00F0FF; font-weight: 800; font-size: 0.72rem; flex-shrink: 0;" id="mResultTime">-</span>
                    </div>
                    <div style="margin-top: 0.35rem; padding-top: 0.25rem; border-top: 1px dashed rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: space-between; font-size: 0.72rem;">
                        <span style="font-family: monospace; font-weight: 800; color: #FF7733; letter-spacing: 0.8px;" id="mResultHash">🔑 HASH: -</span>
                        <small style="color: #94A3B8; font-weight: 700;" id="mResultDevice">📱 Móvil 1</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- SELECTOR DE PESTAÑAS: SCANNER EN VIVO vs HISTORIAL -->
        <div class="scanner-nav-tabs">
            <button type="button" id="tabBtnScanner" onclick="switchScannerTab('scanner')" class="scanner-nav-tab active">
                <span>📷</span> <span>Scanner en Vivo</span>
            </button>
            <button type="button" id="tabBtnHistory" onclick="switchScannerTab('history')" class="scanner-nav-tab">
                <span>📜</span> <span>Historial</span>
                <span id="historyTabCountBadge" class="tab-badge-pill">0</span>
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- PESTAÑA 1: SCANNER EN VIVO (CÁMARA + ENTRADA MANUAL) -->
        <!-- ========================================================================= -->
        <div id="tabContentScanner" style="display: flex; flex-direction: column; gap: 0.85rem;">
            <!-- TARJETA DEL EVENTO -->
            <div class="event-info-card">
                <img src="{{ $event->banner_image ?: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $event->title }}" class="event-avatar">
                <div style="overflow: hidden;">
                    <h2 style="font-size: 0.95rem; font-weight: 900; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.15rem;">{{ $event->title }}</h2>
                    <small style="color: #94A3B8; font-size: 0.75rem; display: block;">📍 {{ $event->venue_name ?? 'Local Principal' }}</small>
                </div>
            </div>

            <!-- KPIS EN VIVO -->
            <div class="mobile-kpi-bar">
                <div class="kpi-mini-box">
                    <span class="val" id="mKpiIssued" style="color: #FFFFFF;">{{ $metrics['tickets_issued'] }}</span>
                    <span class="lbl">Emitidos</span>
                </div>
                <div class="kpi-mini-box" style="border-color: rgba(16, 185, 129, 0.3);">
                    <span class="val" id="mKpiChecked" style="color: #10B981;">{{ $metrics['checked_in_count'] }}</span>
                    <span class="lbl">Ingresados</span>
                </div>
                <div class="kpi-mini-box" style="border-color: rgba(255, 85, 0, 0.3);">
                    <span class="val" id="mKpiRate" style="color: var(--color-primary);">{{ $metrics['attendance_rate'] }}%</span>
                    <span class="lbl">Asistencia</span>
                </div>
            </div>

            <!-- VISOR DE CÁMARA MÓVIL AUTOMÁTICO -->
            <div class="camera-viewport-card" id="mobileCameraViewport" style="position: relative;">
                <div class="laser-scan-line" id="mobileLaser" style="display: none;"></div>
                <div id="qrReaderVideoMobile" style="width: 100%; height: 100%;"></div>
                
                <div id="mobilePlaceholder" style="text-align: center; padding: 2.5rem 1rem;">
                    <div style="font-size: 3.2rem; margin-bottom: 0.5rem; animation: pulse 1.5s infinite;">📷</div>
                    <h3 style="font-size: 1.1rem; font-weight: 900; margin-bottom: 0.35rem; color: #FFFFFF;">Iniciando Cámara...</h3>
                    <p style="color: #94A3B8; font-size: 0.8rem; margin: 0;">Apunta el código QR del boleto dentro de este cuadro.</p>
                </div>

                <!-- Botones flotantes de cámara: Cambiar Lente y Subir Foto -->
                <div style="position: absolute; top: 10px; right: 10px; z-index: 20; display: flex; gap: 0.4rem;">
                    <label for="qrFileInputMobile" style="background: rgba(0,0,0,0.65); border: 1.5px solid rgba(255,255,255,0.3); border-radius: 50%; width: 38px; height: 38px; color: #FFFFFF; font-size: 1.05rem; display: flex; align-items: center; justify-content: center; cursor: pointer;" title="Escanear desde Foto / Galería">
                        🖼️
                    </label>
                    <input type="file" id="qrFileInputMobile" accept="image/*" style="display: none;" onchange="handleQrFileSelected(this)">

                    <button type="button" id="btnMobileSwitchCam" onclick="switchMobileCamera()" style="background: rgba(0,0,0,0.65); border: 1.5px solid rgba(255,255,255,0.3); border-radius: 50%; width: 38px; height: 38px; color: #FFFFFF; font-size: 1rem; display: none; align-items: center; justify-content: center; cursor: pointer;" title="Cambiar Cámara">
                        🔄
                    </button>
                </div>
            </div>

            <!-- ENTRADA MANUAL DE BOLETO O CÓDIGO -->
            <div>
                <form onsubmit="handleMobileManualSubmit(event)" class="manual-input-box">
                    <input type="text" id="mobileManualInput" placeholder="N° BOLETO, QR O HASH..." oninput="this.value = this.value.toUpperCase()">
                    <button type="submit">
                        ⚡ Validar
                    </button>
                </form>
            </div>

            <!-- BANNER COMPACTO DE ÚLTIMO REGISTRO Y ACCESO A HISTORIAL -->
            <div id="lastScanSummaryBanner" style="display: none; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 0.65rem 0.85rem; align-items: center; justify-content: space-between; font-size: 0.8rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
                    <span id="lastScanBannerIcon" style="font-size: 1.1rem;">✅</span>
                    <div style="min-width: 0;">
                        <span id="lastScanBannerText" style="color: #FFFFFF; font-weight: 700; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Último escaneo registrado</span>
                        <small id="lastScanBannerSub" style="color: #94A3B8; font-size: 0.7rem;">Hace un momento</small>
                    </div>
                </div>
                <button type="button" onclick="switchScannerTab('history')" style="background: none; border: none; color: #00F0FF; font-weight: 800; font-size: 0.78rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.2rem; white-space: nowrap;">
                    <span>Ver Historial</span> <span>➔</span>
                </button>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- PESTAÑA 2: HISTORIAL DE ESCANEOS (DE ESTE DISPOSITIVO) -->
        <!-- ========================================================================= -->
        <div id="tabContentHistory" style="display: none; flex-direction: column; gap: 0.75rem;">
            <!-- SUB-ENCABEZADO DE DISPOSITIVO ACTIVO -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.2rem 0.2rem 0.5rem 0.2rem; border-bottom: 1px dashed rgba(255,255,255,0.12);">
                <div style="display: flex; align-items: center; gap: 0.4rem;">
                    <span style="font-size: 0.95rem;">📱</span>
                    <span id="historyDeviceTitleText" style="font-size: 0.85rem; font-weight: 800; color: #00F0FF;">Terminal Activo: Móvil</span>
                </div>
                <small style="color: #94A3B8; font-size: 0.72rem; background: rgba(255,255,255,0.05); padding: 0.15rem 0.5rem; border-radius: 6px;">Historial de este dispositivo</small>
            </div>

            <!-- BUSCADOR RÁPIDO EN HISTORIAL -->
            <div style="display: flex; gap: 0.45rem;">
                <div style="flex: 1; position: relative; display: flex; align-items: center;">
                    <span style="position: absolute; left: 12px; font-size: 0.9rem; color: #94A3B8;">🔍</span>
                    <input type="text" id="historySearchInput" placeholder="Buscar por código, titular, DNI..." oninput="filterScanHistory()" style="width: 100%; background: #14141E; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 12px; padding: 0.65rem 0.75rem 0.65rem 2.2rem; color: #FFFFFF; font-size: 0.85rem; font-weight: 700; outline: none;">
                </div>
                <button type="button" onclick="clearLocalScanHistory()" title="Limpiar historial de este dispositivo" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444; border-radius: 12px; padding: 0 0.85rem; font-weight: 800; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                    <span>🗑️</span>
                </button>
            </div>

            <!-- CHIPS DE FILTRO POR ESTADO -->
            <div style="display: flex; gap: 0.35rem; overflow-x: auto; padding-bottom: 0.25rem;">
                <button type="button" class="history-filter-chip active" id="chipFilterAll" onclick="setHistoryFilter('all')">
                    <span>Todos</span> <strong id="filterCountAll">0</strong>
                </button>
                <button type="button" class="history-filter-chip" id="chipFilterGranted" onclick="setHistoryFilter('granted')">
                    <span>✅ Válidos</span> <strong id="filterCountGranted">0</strong>
                </button>
                <button type="button" class="history-filter-chip" id="chipFilterUsed" onclick="setHistoryFilter('already_used')">
                    <span>🚫 Ya Usados</span> <strong id="filterCountUsed">0</strong>
                </button>
                <button type="button" class="history-filter-chip" id="chipFilterInvalid" onclick="setHistoryFilter('invalid')">
                    <span>❌ Errores</span> <strong id="filterCountInvalid">0</strong>
                </button>
            </div>

            <!-- LISTADO DE TARJETAS DE HISTORIAL -->
            <div id="scanHistoryContainer" style="display: flex; flex-direction: column; gap: 0.45rem;">
                <!-- Renderizado dinámico vía JavaScript -->
            </div>
        </div>
    </div>

    <script>
        const eventId = {{ $event->id }};
        const verifyUrl = "{{ route('web.scanner.verify_qr', $event->id) }}";
        const csrfToken = "{{ csrf_token() }}";

        let html5QrScannerMobile = null;
        let isMobileScanning = false;
        let isProcessingMobileScan = false;
        let currentFacingMode = "environment";
        let audioCtx = null;
        let toastHideTimer = null;

        // Historial exclusivo de escaneos realizados en este dispositivo (Válidos, Duplicados, Errores e Inválidos)
        let scanHistory = [];
        let currentHistoryFilter = 'all';

        function getActiveDeviceName() {
            const devInput = document.getElementById('mobileDeviceName');
            return (devInput && devInput.value.trim()) ? devInput.value.trim() : 'Móvil';
        }

        function updateHistoryDeviceLabel(name) {
            const lbl = document.getElementById('historyDeviceTitleText');
            if (lbl) {
                lbl.textContent = `Terminal Activo: ${name || 'Móvil'}`;
            }
        }

        function initDeviceName() {
            const urlParams = new URLSearchParams(window.location.search);
            const paramDev = urlParams.get('dev') || urlParams.get('device') || urlParams.get('name');
            
            let devName = '';
            if (paramDev && paramDev.trim()) {
                devName = decodeURIComponent(paramDev.trim());
                localStorage.setItem(`vivego_dev_name_evt_${eventId}`, devName);
                localStorage.setItem('vivego_scanner_device_name', devName);
            } else {
                devName = localStorage.getItem(`vivego_dev_name_evt_${eventId}`) 
                          || localStorage.getItem('vivego_scanner_device_name') 
                          || 'Puerta 1';
            }

            const devInput = document.getElementById('mobileDeviceName');
            if (devInput) {
                devInput.value = devName;
                
                const onNameChange = function() {
                    const val = devInput.value.trim() || 'Móvil';
                    localStorage.setItem(`vivego_dev_name_evt_${eventId}`, val);
                    localStorage.setItem('vivego_scanner_device_name', val);
                    updateHistoryDeviceLabel(val);
                };

                devInput.addEventListener('input', onNameChange);
                devInput.addEventListener('change', onNameChange);
            }

            updateHistoryDeviceLabel(devName);
        }

        // =========================================================================
        // CAMBIAR PESTAÑAS (SCANNER vs HISTORIAL)
        // =========================================================================
        function switchScannerTab(tab) {
            const btnScanner = document.getElementById('tabBtnScanner');
            const btnHistory = document.getElementById('tabBtnHistory');
            const contScanner = document.getElementById('tabContentScanner');
            const contHistory = document.getElementById('tabContentHistory');

            if (tab === 'history') {
                if (btnScanner) btnScanner.classList.remove('active');
                if (btnHistory) btnHistory.classList.add('active');
                if (contScanner) contScanner.style.display = 'none';
                if (contHistory) contHistory.style.display = 'flex';
                renderScanHistoryList();
            } else {
                if (btnHistory) btnHistory.classList.remove('active');
                if (btnScanner) btnScanner.classList.add('active');
                if (contHistory) contHistory.style.display = 'none';
                if (contScanner) contScanner.style.display = 'flex';
            }
        }

        // =========================================================================
        // SISTEMA DE HISTORIAL DE ESCANEOS (LOCAL A ESTE DISPOSITIVO)
        // =========================================================================
        const STORAGE_HISTORY_KEY = `vivego_scan_history_evt_${eventId}`;

        function loadLocalScanHistory() {
            let stored = [];
            try {
                const raw = localStorage.getItem(STORAGE_HISTORY_KEY);
                if (raw) stored = JSON.parse(raw);
            } catch (e) {
                stored = [];
            }

            scanHistory = Array.isArray(stored) ? stored : [];
            updateHistoryBadges();
        }

        function saveLocalScanHistory() {
            try {
                // Guardar máximo los últimos 150 registros para optimizar almacenamiento
                const capped = scanHistory.slice(0, 150);
                localStorage.setItem(STORAGE_HISTORY_KEY, JSON.stringify(capped));
            } catch (e) {
                console.warn('No se pudo guardar historial en localStorage:', e);
            }
            updateHistoryBadges();
        }

        function recordScanHistoryItem(item) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateStr = now.toLocaleDateString();

            const fullItem = {
                key: 'scan_' + Date.now() + '_' + Math.random().toString(36).substring(2, 6),
                id: item.id || null,
                status: item.status || 'invalid',
                ticket_code: item.ticket_code || 'Desconocido',
                validation_hash: item.validation_hash || '-',
                zone_name: item.zone_name || '-',
                buyer_name: item.buyer_name || '',
                buyer_dni: item.buyer_dni || '',
                checked_in_at: item.checked_in_at || timeStr,
                checked_in_date: dateStr,
                scanned_by: item.scanned_by || (document.getElementById('mobileDeviceName')?.value || 'Móvil'),
                message: item.message || '',
                timestamp: Date.now()
            };

            // Prepend al historial
            scanHistory.unshift(fullItem);
            saveLocalScanHistory();

            // Actualizar banner rápido de último escaneo
            updateLastScanBanner(fullItem);

            // Si está activa la pestaña de historial, re-renderizar
            const contHistory = document.getElementById('tabContentHistory');
            if (contHistory && contHistory.style.display !== 'none') {
                renderScanHistoryList();
            }
        }

        function updateLastScanBanner(item) {
            const banner = document.getElementById('lastScanSummaryBanner');
            const icon = document.getElementById('lastScanBannerIcon');
            const text = document.getElementById('lastScanBannerText');
            const sub = document.getElementById('lastScanBannerSub');
            if (!banner) return;

            let iconEmoji = '✅';
            let titleText = `Boleto ${item.ticket_code} validado`;
            if (item.status === 'already_used') {
                iconEmoji = '🚫';
                titleText = `Boleto ${item.ticket_code} YA USADO`;
            } else if (item.status === 'upgraded_void') {
                iconEmoji = '🔄';
                titleText = `Boleto ${item.ticket_code} ANULADO (Upgrade)`;
            } else if (item.status === 'wrong_event') {
                iconEmoji = '⚠️';
                titleText = `Boleto ${item.ticket_code} DE OTRO EVENTO`;
            } else if (item.status === 'invalid') {
                iconEmoji = '❌';
                titleText = `Código ${item.ticket_code} INVÁLIDO`;
            }

            if (icon) icon.textContent = iconEmoji;
            if (text) text.textContent = titleText;
            if (sub) sub.textContent = `${item.checked_in_at} • ${item.buyer_name || item.message || 'Escaneado'}`;
            banner.style.display = 'flex';
        }

        function updateHistoryBadges() {
            const total = scanHistory.length;
            const granted = scanHistory.filter(s => s.status === 'granted').length;
            const used = scanHistory.filter(s => s.status === 'already_used' || s.status === 'upgraded_void').length;
            const invalid = scanHistory.filter(s => s.status === 'invalid' || s.status === 'wrong_event').length;

            const badgeTab = document.getElementById('historyTabCountBadge');
            if (badgeTab) badgeTab.textContent = total;

            const cAll = document.getElementById('filterCountAll');
            if (cAll) cAll.textContent = total;

            const cGranted = document.getElementById('filterCountGranted');
            if (cGranted) cGranted.textContent = granted;

            const cUsed = document.getElementById('filterCountUsed');
            if (cUsed) cUsed.textContent = used;

            const cInvalid = document.getElementById('filterCountInvalid');
            if (cInvalid) cInvalid.textContent = invalid;
        }

        function setHistoryFilter(filter) {
            currentHistoryFilter = filter;
            document.querySelectorAll('.history-filter-chip').forEach(c => c.classList.remove('active'));

            if (filter === 'granted') document.getElementById('chipFilterGranted')?.classList.add('active');
            else if (filter === 'already_used') document.getElementById('chipFilterUsed')?.classList.add('active');
            else if (filter === 'invalid') document.getElementById('chipFilterInvalid')?.classList.add('active');
            else document.getElementById('chipFilterAll')?.classList.add('active');

            renderScanHistoryList();
        }

        function filterScanHistory() {
            renderScanHistoryList();
        }

        function renderScanHistoryList() {
            const container = document.getElementById('scanHistoryContainer');
            if (!container) return;

            const query = (document.getElementById('historySearchInput')?.value || '').trim().toLowerCase();

            let filtered = scanHistory.filter(item => {
                // Filtro por estado
                if (currentHistoryFilter === 'granted' && item.status !== 'granted') return false;
                if (currentHistoryFilter === 'already_used' && item.status !== 'already_used' && item.status !== 'upgraded_void') return false;
                if (currentHistoryFilter === 'invalid' && item.status !== 'invalid' && item.status !== 'wrong_event') return false;

                // Filtro por texto de búsqueda
                if (query) {
                    const str = `${item.ticket_code} ${item.validation_hash} ${item.zone_name} ${item.buyer_name} ${item.buyer_dni} ${item.message} ${item.scanned_by}`.toLowerCase();
                    return str.includes(query);
                }

                return true;
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2.5rem 1rem; color: #64748B; background: rgba(255,255,255,0.02); border: 1.5px dashed rgba(255,255,255,0.08); border-radius: 16px;">
                        <div style="font-size: 2.2rem; margin-bottom: 0.4rem;">🎫</div>
                        <strong style="color: #94A3B8; font-size: 0.9rem; display: block;">No hay escaneos que coincidan</strong>
                        <p style="font-size: 0.78rem; margin-top: 0.25rem;">Prueba cambiando el filtro o realiza un nuevo escaneo.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(item => {
                let badgeHtml = '';
                let cardClass = 'history-card-granted';

                if (item.status === 'granted') {
                    badgeHtml = '<span style="background: rgba(16, 185, 129, 0.18); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 0.68rem; font-weight: 900; padding: 0.15rem 0.45rem; border-radius: 6px;">✓ VÁLIDO</span>';
                    cardClass = 'history-card-granted';
                } else if (item.status === 'already_used') {
                    badgeHtml = '<span style="background: rgba(245, 158, 11, 0.18); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 0.68rem; font-weight: 900; padding: 0.15rem 0.45rem; border-radius: 6px;">🚫 YA USADO</span>';
                    cardClass = 'history-card-already_used';
                } else if (item.status === 'upgraded_void') {
                    badgeHtml = '<span style="background: rgba(139, 92, 246, 0.18); color: #A78BFA; border: 1px solid rgba(139, 92, 246, 0.4); font-size: 0.68rem; font-weight: 900; padding: 0.15rem 0.45rem; border-radius: 6px;">🔄 UPGRADE</span>';
                    cardClass = 'history-card-upgraded_void';
                } else if (item.status === 'wrong_event') {
                    badgeHtml = '<span style="background: rgba(59, 130, 246, 0.18); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.4); font-size: 0.68rem; font-weight: 900; padding: 0.15rem 0.45rem; border-radius: 6px;">⚠️ OTRO EVENTO</span>';
                    cardClass = 'history-card-wrong_event';
                } else {
                    badgeHtml = '<span style="background: rgba(239, 68, 68, 0.18); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.4); font-size: 0.68rem; font-weight: 900; padding: 0.15rem 0.45rem; border-radius: 6px;">❌ INVÁLIDO</span>';
                    cardClass = 'history-card-invalid';
                }

                return `
                    <div class="history-item-card ${cardClass}">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.45rem; min-width: 0;">
                                <strong style="color: #FFFFFF; font-size: 0.88rem; font-family: monospace;">${escapeHtml(item.ticket_code)}</strong>
                                ${item.validation_hash && item.validation_hash !== '-' ? `<span style="font-family: monospace; color: #FF7733; font-size: 0.72rem; font-weight: 800;">(${escapeHtml(item.validation_hash)})</span>` : ''}
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0;">
                                ${badgeHtml}
                                <span style="color: #00F0FF; font-weight: 800; font-size: 0.75rem;">${escapeHtml(item.checked_in_at)}</span>
                            </div>
                        </div>

                        ${item.buyer_name ? `
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; font-size: 0.78rem; color: #CBD5E1;">
                                <span>👤 <strong style="color: #FFFFFF;">${escapeHtml(item.buyer_name)}</strong> ${item.buyer_dni ? `(DNI: ${escapeHtml(item.buyer_dni)})` : ''}</span>
                                ${item.zone_name && item.zone_name !== '-' ? `<span style="color: #10B981; font-weight: 800; font-size: 0.72rem; text-transform: uppercase;">${escapeHtml(item.zone_name)}</span>` : ''}
                            </div>
                        ` : ''}

                        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.72rem; color: #94A3B8; padding-top: 0.2rem; border-top: 1px dashed rgba(255,255,255,0.07);">
                            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">💬 ${escapeHtml(item.message || 'Registro procesado')}</span>
                            <small style="color: #64748B; font-weight: 700; flex-shrink: 0;">📱 ${escapeHtml(item.scanned_by)}</small>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function clearLocalScanHistory() {
            Swal.fire({
                title: '¿Limpiar historial local?',
                text: 'Se vaciará la lista de escaneos de este dispositivo. No altera las estadísticas del servidor.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar',
                background: '#14141E',
                color: '#FFFFFF'
            }).then((res) => {
                if (res.isConfirmed) {
                    scanHistory = [];
                    localStorage.removeItem(STORAGE_HISTORY_KEY);
                    updateHistoryBadges();
                    renderScanHistoryList();
                    const banner = document.getElementById('lastScanSummaryBanner');
                    if (banner) banner.style.display = 'none';

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Historial local limpiado',
                        showConfirmButton: false,
                        timer: 1800,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // =========================================================================
        // PROCESAR Y RENDERIZAR RESULTADOS DE ESCANEO
        // =========================================================================
        function renderMobileScanResult(data, rawInput = '') {
            const toast = document.getElementById('mobileResultToast');
            const icon = document.getElementById('mResultIcon');
            const title = document.getElementById('mResultTitle');
            const zone = document.getElementById('mResultZone');
            const buyer = document.getElementById('mResultBuyer');
            const time = document.getElementById('mResultTime');
            const hashEl = document.getElementById('mResultHash');
            const devEl = document.getElementById('mResultDevice');

            if (!toast) return;
            if (toastHideTimer) clearTimeout(toastHideTimer);

            toast.style.display = 'block';
            toast.style.opacity = '1';
            toast.className = 'result-top-toast';

            const currentDevName = getActiveDeviceName();

            if (data.status === 'granted') {
                toast.classList.add('result-granted');
                if (icon) icon.textContent = '✅';
                if (title) title.textContent = '¡ACCESO PERMITIDO!';
                if (zone) {
                    zone.textContent = data.ticket?.zone_name || 'General';
                    zone.style.display = 'inline-block';
                }
                if (buyer) buyer.textContent = data.ticket?.buyer_name || 'Asistente';
                if (time) time.textContent = data.ticket?.checked_in_at || 'Ahora';
                if (hashEl) hashEl.textContent = `🔑 HASH: ${data.ticket?.validation_hash || '-'}`;
                if (devEl) devEl.textContent = `📱 ${data.ticket?.scanned_by || currentDevName}`;

                playMobileTone('granted');

                // Guardar en el Historial
                recordScanHistoryItem({
                    id: data.ticket?.id,
                    status: 'granted',
                    ticket_code: data.ticket?.ticket_code,
                    validation_hash: data.ticket?.validation_hash,
                    zone_name: data.ticket?.zone_name,
                    buyer_name: data.ticket?.buyer_name,
                    buyer_dni: data.ticket?.buyer_dni,
                    checked_in_at: data.ticket?.checked_in_at,
                    scanned_by: data.ticket?.scanned_by || currentDevName,
                    message: data.message || 'Acceso permitido con éxito.'
                });

                if (data.metrics) {
                    const issuedEl = document.getElementById('mKpiIssued');
                    const checkedEl = document.getElementById('mKpiChecked');
                    const rateEl = document.getElementById('mKpiRate');

                    if (issuedEl) issuedEl.textContent = data.metrics.tickets_issued;
                    if (checkedEl) checkedEl.textContent = data.metrics.checked_in_count;
                    if (rateEl) rateEl.textContent = `${data.metrics.attendance_rate}%`;
                }
            } else if (data.status === 'upgraded_void') {
                toast.classList.add('result-upgraded-void');
                if (icon) icon.textContent = '🔄';
                if (title) title.textContent = 'BOLETO ANULADO (UPGRADE)';
                if (zone) {
                    zone.textContent = 'UPGRADE A ' + (data.ticket?.new_zone || 'SUPERIOR');
                    zone.style.display = 'inline-block';
                }
                if (buyer) buyer.textContent = `${data.ticket?.buyer_name || 'Asistente'} (Ex: ${data.ticket?.zone_name || '-'})`;
                if (time) time.textContent = 'Solicitar Nuevo Boleto';
                if (hashEl) hashEl.textContent = `🔑 COD: ${data.ticket?.ticket_code || '-'}`;
                if (devEl) devEl.textContent = `📱 NUEVA ZONA: ${data.ticket?.new_zone || 'SUPERIOR'}`;

                playMobileTone('denied');

                // Guardar en el Historial
                recordScanHistoryItem({
                    id: data.ticket?.id,
                    status: 'upgraded_void',
                    ticket_code: data.ticket?.ticket_code,
                    validation_hash: data.ticket?.validation_hash,
                    zone_name: data.ticket?.zone_name,
                    buyer_name: data.ticket?.buyer_name,
                    buyer_dni: data.ticket?.buyer_dni,
                    scanned_by: currentDevName,
                    message: data.message || `Anulado por upgrade a zona ${data.ticket?.new_zone || 'superior'}.`
                });
            } else if (data.status === 'already_used') {
                toast.classList.add('result-already-used');
                if (icon) icon.textContent = '🚫';
                if (title) title.textContent = 'BOLETO YA USADO / DUPLICADO';
                if (zone) {
                    zone.textContent = data.ticket?.zone_name || 'General';
                    zone.style.display = 'inline-block';
                }
                if (buyer) buyer.textContent = data.ticket?.buyer_name || 'Asistente';
                if (time) time.textContent = `Validado: ${data.ticket?.checked_in_at || '-'}`;
                if (hashEl) hashEl.textContent = `🔑 HASH: ${data.ticket?.validation_hash || '-'}`;
                if (devEl) devEl.textContent = `📱 ${data.ticket?.scanned_by || currentDevName}`;

                playMobileTone('denied');

                // Guardar en el Historial
                recordScanHistoryItem({
                    id: data.ticket?.id,
                    status: 'already_used',
                    ticket_code: data.ticket?.ticket_code,
                    validation_hash: data.ticket?.validation_hash,
                    zone_name: data.ticket?.zone_name,
                    buyer_name: data.ticket?.buyer_name,
                    buyer_dni: data.ticket?.buyer_dni,
                    scanned_by: currentDevName,
                    message: data.message || `Ya ingresó previamente (${data.ticket?.checked_in_at || 'hora previa'} en ${data.ticket?.scanned_by || 'otra puerta'}).`
                });
            } else if (data.status === 'wrong_event') {
                toast.classList.add('result-already-used');
                if (icon) icon.textContent = '⚠️';
                if (title) title.textContent = 'OTRO EVENTO';
                if (zone) {
                    zone.textContent = 'EVENTO DISTINTO';
                    zone.style.display = 'inline-block';
                }
                if (buyer) buyer.textContent = data.ticket?.event_name || 'Pertenece a otro evento';
                if (time) time.textContent = 'Boleto No Válido Aquí';
                if (hashEl) hashEl.textContent = `🔑 COD: ${data.ticket?.ticket_code || '-'}`;
                if (devEl) devEl.textContent = `📱 ${data.ticket?.buyer_name || currentDevName}`;

                playMobileTone('denied');

                // Guardar en el Historial
                recordScanHistoryItem({
                    id: data.ticket?.id,
                    status: 'wrong_event',
                    ticket_code: data.ticket?.ticket_code,
                    validation_hash: data.ticket?.validation_hash,
                    zone_name: data.ticket?.zone_name,
                    buyer_name: data.ticket?.buyer_name || data.ticket?.event_name,
                    buyer_dni: data.ticket?.buyer_dni,
                    scanned_by: currentDevName,
                    message: data.message || `Boleto pertenece a otro evento (${data.ticket?.event_name || 'distinto'}).`
                });
            } else {
                toast.classList.add('result-invalid');
                if (icon) icon.textContent = '❌';
                if (title) title.textContent = 'BOLETO INVÁLIDO';
                if (zone) zone.style.display = 'none';
                if (buyer) buyer.textContent = data.message || 'Código no encontrado en el sistema.';
                if (time) time.textContent = '-';
                if (hashEl) hashEl.textContent = `🔑 COD: ${rawInput || 'NO ENCONTRADO'}`;
                if (devEl) devEl.textContent = `📱 ${currentDevName}`;

                playMobileTone('denied');

                // Guardar intento inválido en el Historial
                recordScanHistoryItem({
                    id: null,
                    status: 'invalid',
                    ticket_code: rawInput || 'Código desconocido',
                    validation_hash: '-',
                    zone_name: '-',
                    buyer_name: 'Intento Fallido',
                    buyer_dni: '',
                    scanned_by: currentDevName,
                    message: data.message || 'Código QR o boleto no encontrado en la base de datos.'
                });
            }

            toastHideTimer = setTimeout(() => {
                toast.style.transition = 'opacity 0.4s ease';
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (toast.style.opacity === '0') {
                        toast.style.display = 'none';
                    }
                }, 400);
            }, 4500);
        }

        function syncMobileRealtimeFeed() {
            const feedUrl = `{{ route('web.scanner.checkins_feed', $event->id) }}`;
            fetch(feedUrl, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.metrics) {
                    const issuedEl = document.getElementById('mKpiIssued');
                    const checkedEl = document.getElementById('mKpiChecked');
                    const rateEl = document.getElementById('mKpiRate');

                    if (issuedEl && issuedEl.textContent != data.metrics.tickets_issued) issuedEl.textContent = data.metrics.tickets_issued;
                    if (checkedEl && checkedEl.textContent != data.metrics.checked_in_count) checkedEl.textContent = data.metrics.checked_in_count;
                    if (rateEl && rateEl.textContent != `${data.metrics.attendance_rate}%`) rateEl.textContent = `${data.metrics.attendance_rate}%`;
                }
            })
            .catch(err => console.log(err));
        }

        function playMobileTone(type) {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                if (type === 'granted') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                    osc.frequency.setValueAtTime(1760, audioCtx.currentTime + 0.1);
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.3);
                    if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                    osc.frequency.setValueAtTime(160, audioCtx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.4, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.45);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.45);
                    if (navigator.vibrate) navigator.vibrate([400]);
                }
            } catch (e) {
                console.log(e);
            }
        }

        function handleMobileManualSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('mobileManualInput');
            if (!input) return;
            const val = input.value.trim();
            if (!val) return;
            processMobileScan(val);
            input.value = '';
        }

        // Decodificar QR a partir de foto tomada con la cámara nativa del celular
        function handleQrFileSelected(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];

            Swal.fire({
                title: 'Escaneando Foto...',
                text: 'Procesando código QR del boleto',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: '#14141E',
                color: '#FFFFFF'
            });

            const scanner = new Html5Qrcode("qrReaderVideoMobile");
            scanner.scanFile(file, false)
                .then(decodedText => {
                    Swal.close();
                    processMobileScan(decodedText);
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'QR No Detectado',
                        text: 'Asegúrate de tomar la foto de cerca, nítida y bien iluminada.',
                        confirmButtonText: 'Intentar de Nuevo',
                        confirmButtonColor: '#FF5500',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                })
                .finally(() => {
                    input.value = '';
                });
        }

        function processMobileScan(payload) {
            if (isProcessingMobileScan) return;
            isProcessingMobileScan = true;

            const dev = document.getElementById('mobileDeviceName')?.value || 'Móvil';

            fetch(verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    qr_payload: payload,
                    device_name: dev
                })
            })
            .then(async (res) => {
                const text = await res.text();
                try {
                    const data = JSON.parse(text);
                    renderMobileScanResult(data, payload);
                } catch(e) {
                    console.error('[Scanner Response JSON Parse Error]', text);
                    renderMobileScanResult({
                        success: false,
                        status: 'invalid',
                        title: '❌ ERROR DEL SERVIDOR',
                        message: 'Error en la respuesta del servidor.'
                    }, payload);
                }
            })
            .catch(err => {
                console.error('[Scanner Fetch Error]', err);
                renderMobileScanResult({
                    success: false,
                    status: 'invalid',
                    title: '❌ ERROR DE CONEXIÓN',
                    message: 'No se pudo conectar con el servidor.'
                }, payload);
            })
            .finally(() => {
                setTimeout(() => {
                    isProcessingMobileScan = false;
                }, 1500);
            });
        }

        function toggleMobileCamera() {
            if (isMobileScanning) {
                stopMobileCamera();
            } else {
                startMobileCamera();
            }
        }

        function startMobileCamera() {
            if (typeof Html5Qrcode === 'undefined') {
                alert('Librería de escaneo no disponible.');
                return;
            }

            // Verificar si el navegador bloquea la cámara por protocolo no seguro (HTTP con IP)
            if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    showHttpCameraHelper();
                    return;
                }
            }

            html5QrScannerMobile = new Html5Qrcode("qrReaderVideoMobile");
            const config = { 
                fps: 15, 
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                    return { width: Math.floor(minEdge * 0.82), height: Math.floor(minEdge * 0.82) };
                }
            };

            html5QrScannerMobile.start(
                { facingMode: currentFacingMode },
                config,
                (decodedText) => {
                    processMobileScan(decodedText);
                },
                () => {}
            ).then(() => {
                isMobileScanning = true;
                const laser = document.getElementById('mobileLaser');
                const placeholder = document.getElementById('mobilePlaceholder');
                const switchBtn = document.getElementById('btnMobileSwitchCam');

                if (laser) laser.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
                if (switchBtn) switchBtn.style.display = 'flex';
            }).catch(err => {
                console.error('Camera error:', err);
                showHttpCameraHelper();
            });
        }

        function showHttpCameraHelper() {
            Swal.fire({
                title: '🔒 Permiso de Cámara en Celular',
                html: `
                    <div style="text-align: left; font-size: 0.85rem; color: #E2E8F0; line-height: 1.5;">
                        <p style="margin-bottom: 0.75rem;">
                            Chrome en el celular requiere habilitar la cámara para direcciones IP locales:
                        </p>
                        <ol style="margin-left: 1.25rem; font-size: 0.8rem; color: #94A3B8; margin-bottom: 0.75rem;">
                            <li>Abre una pestaña en Chrome y ve a: <br><strong style="color: #00F0FF;">chrome://flags/#unsafely-treat-insecure-origin-as-secure</strong></li>
                            <li>Escribe: <strong style="color: #FF5500;">${location.origin}</strong></li>
                            <li>Cambia a <strong>Enabled</strong> y pulsa <strong>Relaunch</strong>.</li>
                        </ol>
                    </div>
                `,
                confirmButtonText: '🔄 Reintentar Activar Cámara',
                confirmButtonColor: '#FF5500',
                background: '#14141E',
                color: '#FFFFFF'
            }).then(() => {
                startMobileCamera();
            });
        }

        function stopMobileCamera() {
            if (html5QrScannerMobile && isMobileScanning) {
                html5QrScannerMobile.stop().then(() => {
                    html5QrScannerMobile.clear();
                    isMobileScanning = false;
                    const laser = document.getElementById('mobileLaser');
                    const placeholder = document.getElementById('mobilePlaceholder');
                    const switchBtn = document.getElementById('btnMobileSwitchCam');

                    if (laser) laser.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'block';
                    if (switchBtn) switchBtn.style.display = 'none';
                }).catch(e => console.error(e));
            }
        }

        function switchMobileCamera() {
            if (!isMobileScanning) return;
            currentFacingMode = (currentFacingMode === "environment") ? "user" : "environment";
            stopMobileCamera();
            setTimeout(() => {
                startMobileCamera();
            }, 300);
        }

        // Auto-activación e inicialización en vivo
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar nombre del dispositivo (URL o LocalStorage)
            initDeviceName();

            // Cargar Historial Local exclusivo de este dispositivo
            loadLocalScanHistory();

            // Iniciar sincronización continua de métricas globales del evento cada 3.5 segundos
            setInterval(syncMobileRealtimeFeed, 3500);

            // Iniciar cámara
            startMobileCamera();
        });
    </script>
</body>
</html>
