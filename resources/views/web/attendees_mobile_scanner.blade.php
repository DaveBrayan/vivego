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
            padding: 0.85rem 1rem;
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

        /* CONTENEDOR PRINCIPAL */
        .scanner-container {
            flex: 1;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        /* TARJETA DE RESUMEN DEL EVENTO */
        .event-info-card {
            background: var(--color-card-bg);
            border: 1px solid rgba(255, 85, 0, 0.25);
            border-radius: 18px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .event-avatar {
            width: 50px;
            height: 50px;
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
            padding: 0.65rem 0.5rem;
            text-align: center;
        }

        .kpi-mini-box .val {
            font-size: 1.25rem;
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
            border-radius: 22px;
            min-height: 280px;
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
            border-radius: 20px !important;
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

        /* BOTONES DE CONTROL DE CÁMARA */
        .camera-action-buttons {
            display: flex;
            gap: 0.6rem;
        }

        .btn-m-action {
            flex: 1;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .btn-m-primary {
            background: linear-gradient(135deg, #FF5500, #FF7733);
            color: #FFFFFF;
            box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);
        }

        .btn-m-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
        }

        /* TOAST FLOTANTE SUPERIOR COMPACTO (TIPO SWEETALERT SLIM) */
        .result-top-toast {
            position: fixed;
            top: 10px;
            left: 10px;
            right: 10px;
            max-width: 440px;
            margin: 0 auto;
            z-index: 99999;
            border-radius: 14px;
            padding: 0.65rem 0.9rem;
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

        /* ENTRADA MANUAL */
        .manual-input-box {
            display: flex;
            gap: 0.5rem;
        }

        .manual-input-box input {
            flex: 1;
            padding: 0.85rem 1rem;
            background: #14141E;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.95rem;
            outline: none;
        }

        .manual-input-box input:focus {
            border-color: #FF5500;
        }

        /* MINI FEED */
        .feed-mini-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 0.65rem 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }
    </style>
</head>
<body>

    <!-- HEADER MÓVIL -->
    <header class="mobile-header">
        <div class="brand-pill">
            <span class="brand-dot"></span>
            <div>
                <strong style="font-size: 0.95rem; display: block; line-height: 1.1;">Vive Go Scanner</strong>
                <small style="color: #94A3B8; font-size: 0.7rem;">Control de Puerta</small>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <input type="text" id="mobileDeviceName" value="Móvil 1" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; color: #FFFFFF; font-size: 0.75rem; padding: 0.3rem 0.6rem; width: 80px; font-weight: 700; text-align: center;">
            <a href="{{ route('web.attendees') }}" style="color: #94A3B8; text-decoration: none; font-size: 1.2rem; padding: 0.3rem;" title="Salir">✕</a>
        </div>
    </header>

    <div class="scanner-container">
        <!-- TOAST FLOTANTE SUPERIOR COMPACTO (TIPO SWEETALERT SLIM) -->
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
                <div style="font-size: 3.5rem; margin-bottom: 0.5rem; animation: pulse 1.5s infinite;">📷</div>
                <h3 style="font-size: 1.1rem; font-weight: 900; margin-bottom: 0.35rem; color: #FFFFFF;">Iniciando Cámara...</h3>
                <p style="color: #94A3B8; font-size: 0.8rem; margin: 0;">Apunta el código QR del boleto dentro de este cuadro.</p>
            </div>

            <!-- Botón flotante para cambiar lente (trasera / delantera) si hay más de una -->
            <button type="button" id="btnMobileSwitchCam" onclick="switchMobileCamera()" style="position: absolute; top: 12px; right: 12px; z-index: 20; background: rgba(0,0,0,0.6); border: 1.5px solid rgba(255,255,255,0.3); border-radius: 50%; width: 38px; height: 38px; color: #FFFFFF; font-size: 1rem; display: none; align-items: center; justify-content: center; cursor: pointer;">
                🔄
            </button>
        </div>

        <!-- FEED DE ÚLTIMOS ESCANEOS -->
        <div style="margin-top: 0.5rem;">
            <small style="color: #94A3B8; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; display: block; margin-bottom: 0.4rem;">
                📜 Últimos Ingresos Validados
            </small>
            <div id="mobileRecentFeed">
                @forelse($recentCheckins as $chk)
                    <div class="feed-mini-item" id="feedItem_{{ $chk->id }}">
                        <div style="min-width: 0; flex: 1;">
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <strong style="color: #FFFFFF; font-size: 0.85rem;">{{ $chk->ticket_code }}</strong>
                                <span style="font-family: monospace; color: #FF7733; font-weight: 800; font-size: 0.725rem;">({{ $chk->validation_hash ?: ('VG' . strtoupper(substr(md5($chk->id), 0, 8))) }})</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.15rem;">
                                <small style="color: #10B981; font-weight: 700; font-size: 0.72rem; text-transform: uppercase;">{{ $chk->zone_name }}</small>
                                <small style="color: #94A3B8; font-size: 0.7rem;">📱 {{ $chk->scanned_by ?: 'Móvil Scanner' }}</small>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.45rem; flex-shrink: 0;">
                            <span style="color: #00F0FF; font-weight: 800; font-size: 0.75rem;">{{ $chk->checked_in_at ? $chk->checked_in_at->format('h:i:s A') : '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div id="emptyMobileFeed" style="text-align: center; padding: 1.5rem; color: #64748B; font-size: 0.8rem;">
                        Sin ingresos recientes todavía.
                    </div>
                @endforelse
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
        let lastFeedId = {{ $recentCheckins->first() ? $recentCheckins->first()->id : 0 }};
        let toastHideTimer = null;

        // Leer parámetro dev / device desde URL (vinculación de scanner)
        const urlParams = new URLSearchParams(window.location.search);
        const paramDev = urlParams.get('dev') || urlParams.get('device');
        if (paramDev) {
            document.addEventListener('DOMContentLoaded', () => {
                const devInput = document.getElementById('mobileDeviceName');
                if (devInput) devInput.value = paramDev;
            });
        }

        function renderMobileScanResult(data) {
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
                if (devEl) devEl.textContent = `📱 ${data.ticket?.scanned_by || 'Móvil'}`;

                playMobileTone('granted');

                if (data.ticket) appendMobileFeed(data.ticket);
                if (data.metrics) {
                    const issuedEl = document.getElementById('mKpiIssued');
                    const checkedEl = document.getElementById('mKpiChecked');
                    const rateEl = document.getElementById('mKpiRate');

                    if (issuedEl) issuedEl.textContent = data.metrics.tickets_issued;
                    if (checkedEl) checkedEl.textContent = data.metrics.checked_in_count;
                    if (rateEl) rateEl.textContent = `${data.metrics.attendance_rate}%`;
                }
            } else if (data.status === 'already_used') {
                toast.classList.add('result-already-used');
                if (icon) icon.textContent = '🚫';
                if (title) title.textContent = 'BOLETO YA USADO';
                if (zone) {
                    zone.textContent = data.ticket?.zone_name || 'General';
                    zone.style.display = 'inline-block';
                }
                if (buyer) buyer.textContent = data.ticket?.buyer_name || 'Asistente';
                if (time) time.textContent = `Validado: ${data.ticket?.checked_in_at || '-'}`;
                if (hashEl) hashEl.textContent = `🔑 HASH: ${data.ticket?.validation_hash || '-'}`;
                if (devEl) devEl.textContent = `📱 ${data.ticket?.scanned_by || 'Móvil'}`;

                playMobileTone('denied');
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
                if (devEl) devEl.textContent = `📱 ${data.ticket?.buyer_name || 'Móvil'}`;

                playMobileTone('denied');
            } else {
                toast.classList.add('result-invalid');
                if (icon) icon.textContent = '❌';
                if (title) title.textContent = 'BOLETO INVÁLIDO';
                if (zone) zone.style.display = 'none';
                if (buyer) buyer.textContent = data.message || 'Código no encontrado en el sistema.';
                if (time) time.textContent = '-';
                if (hashEl) hashEl.textContent = '🔑 HASH: NO ENCONTRADO';
                if (devEl) devEl.textContent = '📱 Móvil';

                playMobileTone('denied');
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

        function appendMobileFeed(t) {
            if (!t || !t.id) return;
            const container = document.getElementById('mobileRecentFeed');
            const empty = document.getElementById('emptyMobileFeed');
            if (empty) empty.remove();

            if (document.getElementById(`feedItem_${t.id}`)) return;

            const hashVal = t.validation_hash || ('VG' + String(t.id).padStart(8, '0'));
            const scannedBy = t.scanned_by || 'Móvil Scanner';

            const item = document.createElement('div');
            item.id = `feedItem_${t.id}`;
            item.className = 'feed-mini-item';
            item.innerHTML = `
                <div style="min-width: 0; flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.4rem;">
                        <strong style="color: #FFFFFF; font-size: 0.85rem;">${t.ticket_code || 'Boleto'}</strong>
                        <span style="font-family: monospace; color: #FF7733; font-weight: 800; font-size: 0.725rem;">(${hashVal})</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.15rem;">
                        <small style="color: #10B981; font-weight: 700; font-size: 0.72rem; text-transform: uppercase;">${t.zone_name || 'General'}</small>
                        <small style="color: #94A3B8; font-size: 0.7rem;">📱 ${scannedBy}</small>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.45rem; flex-shrink: 0;">
                    <span style="color: #00F0FF; font-weight: 800; font-size: 0.75rem;">${t.checked_in_at || ''}</span>
                </div>
            `;

            if (container.firstChild) {
                container.insertBefore(item, container.firstChild);
            } else {
                container.appendChild(item);
            }
        }

        function syncMobileRealtimeFeed() {
            const feedUrl = `{{ route('web.scanner.checkins_feed', $event->id) }}`;
            fetch(feedUrl, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.metrics) {
                        const issuedEl = document.getElementById('mKpiIssued');
                        const checkedEl = document.getElementById('mKpiChecked');
                        const rateEl = document.getElementById('mKpiRate');

                        if (issuedEl) issuedEl.textContent = data.metrics.tickets_issued;
                        if (checkedEl) checkedEl.textContent = data.metrics.checked_in_count;
                        if (rateEl) rateEl.textContent = `${data.metrics.attendance_rate}%`;
                    }

                    if (data.new_checkins && data.new_checkins.length > 0) {
                        // Iterar en reversa para agregar al inicio respetando orden descendente
                        [...data.new_checkins].reverse().forEach(chk => {
                            appendMobileFeed(chk);
                        });
                    }

                    // Sincronizar anulaciones: Si un boleto fue anulado en admin, removerlo del feed móvil
                    if (data.active_checkin_ids && Array.isArray(data.active_checkin_ids)) {
                        const activeSet = new Set(data.active_checkin_ids.map(Number));
                        const feedItems = document.querySelectorAll('#mobileRecentFeed .feed-mini-item');
                        feedItems.forEach(el => {
                            const rawId = el.id.replace('feedItem_', '');
                            if (rawId && !activeSet.has(Number(rawId))) {
                                el.style.transition = 'all 0.35s ease';
                                el.style.opacity = '0';
                                el.style.transform = 'translateX(-30px)';
                                setTimeout(() => {
                                    el.remove();
                                    const remaining = document.querySelectorAll('#mobileRecentFeed .feed-mini-item');
                                    if (remaining.length === 0) {
                                        const feedCont = document.getElementById('mobileRecentFeed');
                                        if (feedCont && !document.getElementById('emptyMobileFeed')) {
                                            feedCont.innerHTML = '<div id="emptyMobileFeed" style="text-align: center; padding: 1.5rem; color: #64748B; font-size: 0.8rem;">Sin ingresos recientes todavía.</div>';
                                        }
                                    }
                                }, 350);
                            }
                        });
                    }
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
                    renderMobileScanResult(data);
                } catch(e) {
                    console.error('[Scanner Response JSON Parse Error]', text);
                    renderMobileScanResult({
                        success: false,
                        status: 'invalid',
                        title: '❌ ERROR DEL SERVIDOR',
                        message: 'Error en la respuesta del servidor.'
                    });
                }
            })
            .catch(err => {
                console.error('[Scanner Fetch Error]', err);
                renderMobileScanResult({
                    success: false,
                    status: 'invalid',
                    title: '❌ ERROR DE CONEXIÓN',
                    message: 'No se pudo conectar con el servidor.'
                });
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
            let savedDevName = localStorage.getItem('vivego_scanner_device_name');
            if (!savedDevName) {
                // Auto-asignar secuencial o aleatorio Móvil 1, Móvil 2, etc.
                const randomDevNum = Math.floor(Math.random() * 5) + 1;
                savedDevName = 'Móvil ' + randomDevNum;
                localStorage.setItem('vivego_scanner_device_name', savedDevName);
            }

            const devInput = document.getElementById('mobileDeviceName');
            if (devInput) {
                devInput.value = savedDevName;
                devInput.addEventListener('change', function() {
                    const val = this.value.trim();
                    if (val) {
                        localStorage.setItem('vivego_scanner_device_name', val);
                    }
                });
            }

            // Iniciar sincronización continua cada 2 segundos
            setInterval(syncMobileRealtimeFeed, 2000);

            // Iniciar cámara
            startMobileCamera();
        });
    </script>
</body>
</html>
