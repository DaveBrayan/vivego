<!-- =========================================================================
     COMPONENTE PÚBLICO: MAPA INTERACTIVO DE ZONAS & BUTACAS VECTORIALES
     ViveGo Pro Max - Fondo Blanco, Tipografía Grande al Centro & Butacas Numeradas
     ========================================================================= -->

<style>
    .public-seatmap-wrapper {
        width: 100%;
        margin-bottom: 2rem;
    }

    .public-seatmap-card {
        position: relative;
        width: 100%;
        min-height: 560px;
        background: #FFFFFF !important;
        border-radius: 20px;
        overflow: hidden;
        border: 1.5px solid #E2E8F0;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.07);
        user-select: none;
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 768px) {
        .public-seatmap-card {
            min-height: 400px;
            border-radius: 16px;
        }
    }

    .public-seatmap-header-bar {
        padding: 0.95rem 1.25rem;
        background: #FFFFFF;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        z-index: 5;
    }

    .public-seatmap-canvas-layer {
        position: relative;
        flex: 1;
        width: 100%;
        height: 560px;
        min-height: 540px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF !important;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .public-seatmap-canvas-layer {
            height: 400px;
            min-height: 380px;
        }
    }

    /* Polígonos de Zona */
    .svg-public-zone {
        transition: fill-opacity 0.2s ease, stroke-width 0.2s ease, filter 0.2s ease;
    }

    .svg-public-zone.no-seats {
        cursor: pointer;
    }

    .svg-public-zone.no-seats:hover {
        filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.25)) !important;
        stroke: #FF5500 !important;
        stroke-width: 3.5px !important;
    }

    .svg-public-zone.no-seats.selected {
        stroke-width: 3.8px !important;
        stroke: #FF5500 !important;
        filter: drop-shadow(0 8px 22px rgba(255, 85, 0, 0.45)) !important;
    }

    .svg-public-zone.has-seats {
        cursor: default;
    }

    .svg-public-zone.has-seats.selected {
        stroke-width: 3.2px !important;
        stroke: #FF5500 !important;
        filter: drop-shadow(0 6px 18px rgba(255, 85, 0, 0.35)) !important;
    }

    .svg-public-zone.stage-zone {
        cursor: default !important;
        pointer-events: none !important;
    }

    .svg-zone-center-text {
        pointer-events: none;
        user-select: none;
    }

    /* Estilos de Butacas Cuadradas con Número Integrado */
    .public-seat-group {
        cursor: pointer;
        transition: filter 0.15s ease;
    }

    .public-seat-group:hover .public-seat-rect {
        stroke: #FF5500 !important;
        stroke-width: 1.8px !important;
        filter: drop-shadow(0 0 5px rgba(255, 85, 0, 0.9)) drop-shadow(0 1px 3px rgba(0, 0, 0, 0.4));
    }

    .public-seat-group:hover text {
        fill: #FFFFFF !important;
    }

    .public-seat-group.selected .public-seat-rect {
        fill: #FF5500 !important;
        stroke: #FFFFFF !important;
        stroke-width: 1.8px !important;
        filter: drop-shadow(0 0 6px rgba(255, 85, 0, 0.95)) !important;
    }

    .public-seat-group.selected text {
        fill: #FFFFFF !important;
        font-weight: 900 !important;
    }

    .public-seat-group.occupied {
        cursor: not-allowed;
    }

    .public-seat-group.occupied:hover .public-seat-rect {
        stroke: #B91C1C !important;
        stroke-width: 1.2px !important;
        filter: none;
    }

    .public-seat-group.occupied .public-seat-rect {
        fill: #DC2626 !important;
        stroke: #B91C1C !important;
        opacity: 0.95;
    }

    .public-seat-group.digital-disabled {
        cursor: not-allowed !important;
        opacity: 0.70;
    }

    .public-seat-group.digital-disabled:hover .public-seat-rect {
        stroke: #64748B !important;
        stroke-width: 1.5px !important;
        filter: none !important;
    }

    .public-seat-group.digital-disabled .public-seat-rect {
        fill: #475569 !important;
        stroke: #94A3B8 !important;
        opacity: 0.65;
    }

    .ticket-type-row.map-highlighted {
        animation: pulseTicketRow 0.6s ease-in-out;
        border-color: #FF5500 !important;
        background: rgba(255, 85, 0, 0.08) !important;
    }

    @keyframes pulseTicketRow {
        0% { box-shadow: 0 0 0 0 rgba(255, 85, 0, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 85, 0, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 85, 0, 0); }
    }
</style>

@if(!empty($event['has_interactive_zones']) && !empty($event['interactive_zones']))
    <div class="public-seatmap-wrapper animate-fade-in detail-order-seatmap" id="interactiveSeatMapSection">
        
        <!-- Canvas Contenedor del Mapa Interactivo (Fondo Blanco Limpio con Zonas de Alto Impacto) -->
        <div id="publicSeatMapContainer" class="public-seatmap-card">
            
            <!-- Encabezado Integrado dentro de la Tarjeta Blanca -->
            <div class="public-seatmap-header-bar">
                <div class="info-block-icon" style="background: rgba(255, 85, 0, 0.1); color: #FF5500; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">🗺️</div>
                <div style="flex: 1; min-width: 0;">
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 900; color: #0F172A; line-height: 1.2;">Mapa Interactivo de Zonas</h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.775rem; color: #64748B; font-weight: 600;">Haz clic en cualquier sector o butaca del plano para seleccionarla directamente</p>
                </div>
            </div>

            <!-- Área del SVG Auto-Centrado en Fondo Blanco Limpio -->
            <div id="publicSeatMapCanvasLayer" class="public-seatmap-canvas-layer">
                <svg id="publicSeatMapSvg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: visible;" preserveAspectRatio="xMidYMid meet">
                    <defs>
                        <filter id="publicSeatShadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="1.5" stdDeviation="2" flood-color="#000000" flood-opacity="0.25" />
                        </filter>
                    </defs>
                    <!-- Zonas generadas fielmente por polígonos SVG dinámicos -->
                </svg>
            </div>

            <!-- Tooltip Flotante / Popover de Zona o Butaca -->
            <div id="publicZoneTooltip" style="position: absolute; display: none; z-index: 30; pointer-events: none; background: #0F172A; border: 1.5px solid rgba(255, 85, 0, 0.7); border-radius: 12px; padding: 0.65rem 0.95rem; color: #FFFFFF; box-shadow: 0 10px 28px rgba(0,0,0,0.45); transform: translate(-50%, -120%); transition: opacity 0.15s ease; min-width: 150px; backdrop-filter: blur(8px);">
                <div id="tooltipZoneTitle" style="font-weight: 900; font-size: 0.9rem; margin-bottom: 0.15rem; color: #FF8800;"></div>
                <div id="tooltipZonePrice" style="font-size: 1.05rem; font-weight: 900; color: #10B981;"></div>
                <div id="tooltipZoneCap" style="font-size: 0.725rem; color: #94A3B8; margin-top: 0.1rem; display: none;"></div>
                <div id="tooltipZoneActionHint" style="margin-top: 0.35rem; font-size: 0.68rem; font-weight: 800; background: rgba(255,85,0,0.2); color: #FF8800; padding: 2px 6px; border-radius: 4px; text-align: center;">
                    👉 Clic para seleccionar
                </div>
            </div>
        </div>

    </div>

    <hr class="event-section-divider detail-order-seatmap">

@elseif(!empty($event['reference_image']))
    <!-- Sección: Imagen de Referencia Estándar (Modo Estándar) -->
    <div class="public-seatmap-wrapper animate-fade-in detail-order-seatmap">
        <div class="info-block-header">
            <div class="info-block-icon">🗺️</div>
            <h2>Mapa de Zonas y Referencia</h2>
        </div>

        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; margin-top: 0.5rem;">
            <div style="position: relative; display: inline-block; max-width: 100%; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid rgba(0, 0, 0, 0.08);">
                <img src="{{ $event['reference_image'] }}" alt="Mapa de Zonas y Referencia - {{ $event['title'] }}" style="max-height: 340px; width: auto; max-width: 100%; object-fit: contain; display: block; margin: 0 auto; border-radius: 14px;">
                
                <a href="{{ $event['reference_image'] }}" target="_blank" style="position: absolute; bottom: 8px; right: 8px; background: rgba(15, 23, 42, 0.8); color: #FFFFFF; font-size: 0.725rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px); display: inline-flex; align-items: center; gap: 0.3rem;" title="Abrir imagen en tamaño completo">
                    <span>🔍</span> Ver completo
                </a>
            </div>
        </div>
    </div>

    <hr class="event-section-divider detail-order-seatmap">
@endif

<!-- Script del Visualizador Interactivo Vectorial en Fondo Blanco con Tipografía Grande y Butacas Numeradas -->
<script>
    const PublicSeatMap = {
        zones: @json($event['interactive_zones'] ?? []),
        selectedZoneName: null,

        init: function() {
            if (!this.zones || this.zones.length === 0) return;
            this.render();
        },

        render: function() {
            const svg = document.getElementById('publicSeatMapSvg');
            if (!svg) return;
            
            // Limpiar contenido previo manteniendo <defs>
            const defs = svg.querySelector('defs');
            svg.innerHTML = '';
            if (defs) {
                svg.appendChild(defs);
            } else {
                const newDefs = document.createElementNS("http://www.w3.org/2000/svg", 'defs');
                newDefs.innerHTML = `
                    <filter id="publicSeatShadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="1.5" stdDeviation="2" flood-color="#000000" flood-opacity="0.25" />
                    </filter>
                `;
                svg.appendChild(newDefs);
            }

            const NS = "http://www.w3.org/2000/svg";

            // 1. Calcular caja envolvente (Bounding Box)
            let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
            let totalPointsCount = 0;

            this.zones.forEach(z => {
                if (Array.isArray(z.points) && z.points.length >= 3) {
                    z.points.forEach(p => {
                        if (p.x < minX) minX = p.x;
                        if (p.x > maxX) maxX = p.x;
                        if (p.y < minY) minY = p.y;
                        if (p.y > maxY) maxY = p.y;
                        totalPointsCount++;
                    });
                }
                if (Array.isArray(z.seats)) {
                    z.seats.forEach(s => {
                        if (s.x < minX) minX = s.x;
                        if (s.x > maxX) maxX = s.x;
                        if (s.y < minY) minY = s.y;
                        if (s.y > maxY) maxY = s.y;
                        totalPointsCount++;
                    });
                }
                if (z.label_x !== undefined && z.label_x !== null && z.label_y !== undefined && z.label_y !== null) {
                    const lx = parseFloat(z.label_x);
                    const ly = parseFloat(z.label_y);
                    if (!isNaN(lx) && !isNaN(ly)) {
                        if (lx - 50 < minX) minX = lx - 50;
                        if (lx + 50 > maxX) maxX = lx + 50;
                        if (ly - 20 < minY) minY = ly - 20;
                        if (ly + 20 > maxY) maxY = ly + 20;
                        totalPointsCount++;
                    }
                }
            });

            if (totalPointsCount > 0) {
                const boxWidth = Math.max(100, maxX - minX);
                const boxHeight = Math.max(100, maxY - minY);
                const padX = Math.max(18, Math.round(boxWidth * 0.035));
                const padY = Math.max(18, Math.round(boxHeight * 0.035));
                const vbX = Math.round(minX - padX);
                const vbY = Math.round(minY - padY);
                const vbW = Math.round(boxWidth + padX * 2);
                const vbH = Math.round(boxHeight + padY * 2);
                svg.setAttribute('viewBox', `${vbX} ${vbY} ${vbW} ${vbH}`);
            } else {
                svg.setAttribute('viewBox', '0 0 1000 650');
            }

            // Capa 1: Superficies de Polígonos
            const zonesGroup = document.createElementNS(NS, 'g');
            zonesGroup.setAttribute('id', 'publicZonesGroup');

            // Capa 2: Asientos / Butacas Numeradas con su Número Adentro
            const seatsGroup = document.createElementNS(NS, 'g');
            seatsGroup.setAttribute('id', 'publicSeatsGroup');

            // Capa 3: Textos Grandes al Centro
            const labelsGroup = document.createElementNS(NS, 'g');
            labelsGroup.setAttribute('id', 'publicLabelsGroup');

            // 2. Renderizar cada zona conservando con 100% de fidelidad su geometría poligonal
            this.zones.forEach((z) => {
                if (!Array.isArray(z.points) || z.points.length < 3) return;

                const color = z.color || '#2563EB';
                const hasSeats = Array.isArray(z.seats) && z.seats.length > 0;
                const priceVal = parseFloat(z.price) || 0;
                const priceFormatted = 'S/ ' + priceVal.toFixed(2);
                const nameText = z.name || 'Zona';
                const nameUpper = nameText.toUpperCase().trim();
                const capUpper = (z.capacity_type || '').toUpperCase().trim();

                const isStage = nameUpper.includes('ESCENARIO') || 
                                nameUpper.includes('TARIMA') || 
                                capUpper.includes('ESCENARIO') || 
                                (z.type && z.type === 'stage') ||
                                (priceVal === 0 && (parseInt(z.capacity) || 0) === 0 && !hasSeats);

                const isDigitalDisabled = !!z.is_digital_disabled;
                const isZoneAvailable = (z.available !== false && z.is_available !== false) && !isDigitalDisabled;
                const isClickable = !hasSeats && (!isStage || priceVal > 0) && !isDigitalDisabled && isZoneAvailable;
                const isSelected = this.isZoneMatch(this.selectedZoneName, z.name);

                const xs = z.points.map(p => p.x);
                const ys = z.points.map(p => p.y);
                const zMinX = Math.min(...xs);
                const zMaxX = Math.max(...xs);
                const zMinY = Math.min(...ys);
                const zMaxY = Math.max(...ys);
                const zW = zMaxX - zMinX;
                const zH = zMaxY - zMinY;
                const autoCenterX = xs.reduce((a, b) => a + b, 0) / xs.length;
                const autoCenterY = ys.reduce((a, b) => a + b, 0) / ys.length;

                const hasCustomLabel = (z.label_x !== undefined && z.label_x !== null && !isNaN(parseFloat(z.label_x))) &&
                                       (z.label_y !== undefined && z.label_y !== null && !isNaN(parseFloat(z.label_y)));

                const centerX = hasCustomLabel ? parseFloat(z.label_x) : autoCenterX;
                const centerY = hasCustomLabel ? parseFloat(z.label_y) : autoCenterY;

                // 2.1 Superficie Poligonal Fiel (Polygon)
                const pointsStr = z.points.map(p => `${p.x},${p.y}`).join(' ');
                const polygon = document.createElementNS(NS, 'polygon');
                polygon.setAttribute('points', pointsStr);
                polygon.setAttribute('stroke-linejoin', 'round');
                polygon.setAttribute('stroke-linecap', 'round');
                polygon.setAttribute('data-zone-name', z.name);

                if (isStage) {
                    polygon.setAttribute('fill', color || '#334155');
                    polygon.setAttribute('fill-opacity', '0.88');
                    polygon.setAttribute('stroke', color || '#1E293B');
                    polygon.setAttribute('stroke-width', '1.5');
                    polygon.setAttribute('class', 'svg-public-zone stage-zone');
                } else if (hasSeats) {
                    // Zona con butacas: fondo contenedor
                    polygon.setAttribute('fill', isDigitalDisabled ? '#475569' : color);
                    polygon.setAttribute('fill-opacity', isDigitalDisabled ? '0.18' : (isSelected ? '0.35' : '0.22'));
                    polygon.setAttribute('stroke', isSelected ? '#FF5500' : (isDigitalDisabled ? '#64748B' : color));
                    polygon.setAttribute('stroke-width', isSelected ? '2.8' : '1.8');
                    polygon.setAttribute('class', `svg-public-zone has-seats ${isSelected ? 'selected' : ''} ${isDigitalDisabled ? 'digital-disabled' : ''}`);
                } else {
                    // Zona General sin butacas: color sólido vibrante con alto impacto
                    polygon.setAttribute('fill', isDigitalDisabled ? '#64748B' : color);
                    polygon.setAttribute('fill-opacity', isDigitalDisabled ? '0.45' : (isSelected ? '0.98' : '0.90'));
                    polygon.setAttribute('stroke', isSelected ? '#FF5500' : (isDigitalDisabled ? '#94A3B8' : 'rgba(0,0,0,0.15)'));
                    polygon.setAttribute('stroke-width', isSelected ? '3.5' : '1.5');
                    polygon.setAttribute('class', `svg-public-zone no-seats ${isSelected ? 'selected' : ''} ${isDigitalDisabled ? 'digital-disabled' : ''}`);
                }

                // Eventos de Mouse sobre la Superficie (SOLO para zonas comerciales, no para el escenario)
                if (!isStage) {
                    polygon.addEventListener('mouseenter', (e) => this.onZoneMouseEnter(e, z, false));
                    polygon.addEventListener('mousemove', (e) => this.onZoneMouseMove(e));
                    polygon.addEventListener('mouseleave', () => this.onZoneMouseLeave());

                    if (hasSeats) {
                        polygon.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (isDigitalDisabled || !isZoneAvailable) {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Venta Digital No Disponible',
                                        text: `El sector "${z.name}" no está habilitado para compra por internet (solo disponible en puntos de venta físicos o boletería).`,
                                        icon: 'info',
                                        confirmButtonColor: '#0F172A',
                                        confirmButtonText: 'Entendido'
                                    });
                                } else {
                                    alert(`El sector "${z.name}" no está disponible para venta digital.`);
                                }
                                return;
                            }
                            const hintEl = document.getElementById('tooltipZoneActionHint');
                            if (hintEl) {
                                hintEl.textContent = '👇 Haz clic en una butaca para elegirla';
                                hintEl.style.background = 'rgba(255,85,0,0.25)';
                                hintEl.style.color = '#FF5500';
                            }
                        });
                    } else if (isDigitalDisabled || !isZoneAvailable) {
                        polygon.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Venta Digital No Disponible',
                                    text: `La zona "${z.name}" no está habilitada para compra por internet (solo disponible en puntos de venta físicos o boletería).`,
                                    icon: 'info',
                                    confirmButtonColor: '#0F172A',
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                alert(`La zona "${z.name}" no está disponible para venta digital.`);
                            }
                        });
                    } else if (isClickable) {
                        polygon.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.selectGeneralZone(z.name);
                        });
                    }
                }

                zonesGroup.appendChild(polygon);

                // 2.2 Si tiene Butacas: Renderizar Cuadrados con su Número Adentro (1, 2, 3...)
                if (hasSeats) {
                    // Asegurar margen generoso de las butacas con respecto a los bordes de la zona
                    const seatXs = z.seats.map(s => s.x);
                    const seatYs = z.seats.map(s => s.y);
                    const sMinX = Math.min(...seatXs);
                    const sMaxX = Math.max(...seatXs);
                    const sMinY = Math.min(...seatYs);
                    const sMaxY = Math.max(...seatYs);

                    const padL = sMinX - zMinX;
                    const padR = zMaxX - sMaxX;
                    const padT = sMinY - zMinY;
                    const padB = zMaxY - sMaxY;

                    const sCols = parseInt(z.seat_cols || z.cols) || (Array.isArray(z.seats) ? new Set(z.seats.map(s => s.col)).size : 10);
                    const sRows = parseInt(z.seat_rows || z.rows) || (Array.isArray(z.seats) ? new Set(z.seats.map(s => s.row)).size : 5);

                    let desiredPadX = Math.max(20, Math.min(45, Math.round(zW * 0.08)));
                    if (sCols >= 16) {
                        desiredPadX = Math.max(16, Math.min(26, Math.round(zW * 0.04)));
                    } else if (sCols >= 10) {
                        desiredPadX = Math.max(20, Math.min(32, Math.round(zW * 0.06)));
                    }

                    let desiredPadY = Math.max(20, Math.min(42, Math.round(zH * 0.08)));
                    if (sRows >= 12) {
                        desiredPadY = Math.max(16, Math.min(24, Math.round(zH * 0.04)));
                    } else if (sRows >= 8) {
                        desiredPadY = Math.max(18, Math.min(30, Math.round(zH * 0.06)));
                    }

                    const minAllowedPad = (sCols >= 16 || sRows >= 12) ? 12 : 18;
                    if (padL < minAllowedPad || padR < minAllowedPad || padT < minAllowedPad || padB < minAllowedPad) {
                        const sW = Math.max(1, sMaxX - sMinX);
                        const sH = Math.max(1, sMaxY - sMinY);
                        const targetMinX = zMinX + desiredPadX;
                        const targetMaxX = zMaxX - desiredPadX;
                        const targetMinY = zMinY + desiredPadY;
                        const targetMaxY = zMaxY - desiredPadY;
                        const targetW = Math.max(10, targetMaxX - targetMinX);
                        const targetH = Math.max(10, targetMaxY - targetMinY);

                        z.seats.forEach(s => {
                            const normX = (s.x - sMinX) / sW;
                            const normY = (s.y - sMinY) / sH;
                            s.x = Math.round(targetMinX + normX * targetW);
                            s.y = Math.round(targetMinY + normY * targetH);
                        });
                    }

                    // Calcular tamaño dinámico de la butaca según separación (sin traslape horizontal o vertical)
                    let minStep = 32;
                    if (z.seats.length >= 2) {
                        let dMin = Infinity;
                        const sampleLimit = Math.min(z.seats.length, 80);
                        for (let i = 0; i < sampleLimit; i++) {
                            for (let j = i + 1; j < Math.min(sampleLimit, i + 25); j++) {
                                const dist = Math.hypot(z.seats[i].x - z.seats[j].x, z.seats[i].y - z.seats[j].y);
                                if (dist > 4 && dist < dMin) dMin = dist;
                            }
                        }
                        if (dMin < Infinity) minStep = dMin;
                    }

                    // Garantizar SIEMPRE una separación visible entre butacas adyacentes (al menos 3 a 5px libres)
                    const maxAllowedSide = Math.max(10, Math.floor(minStep - 4));
                    const idealSide = Math.round(minStep * 0.74);
                    const seatSide = Math.max(10, Math.min(46, Math.min(idealSide, maxAllowedSide)));
                    const halfSide = seatSide / 2;
                    const cornerRadius = Math.max(2, Math.min(6, Math.round(seatSide * 0.16)));
                    const strokeW = seatSide < 20 ? '0.9' : (seatSide < 28 ? '1.2' : '1.4');

                    z.seats.forEach((seat, sIdx) => {
                        const isOccupied = (seat.status === 'occupied' || seat.status === 'ocupado' || seat.status === 'vendido' || seat.is_occupied);
                        const isBlocked = isDigitalDisabled || !isZoneAvailable;
                        
                        // Extraer el código visible según la configuración (ej: A1, A2... o 1-1)
                        let displayNum = '';
                        if (seat.row !== undefined && seat.row !== null && seat.row !== '' &&
                            seat.col !== undefined && seat.col !== null && seat.col !== '') {
                            const rStr = String(seat.row).trim();
                            const cStr = String(seat.col).trim();
                            if (isNaN(rStr) || isNaN(cStr)) {
                                displayNum = `${rStr}${cStr}`;
                            } else {
                                displayNum = `${rStr}-${cStr}`;
                            }
                        } else if (seat.number && String(seat.number).includes('-')) {
                            const p = String(seat.number).split('-');
                            if (isNaN(p[0]) || isNaN(p[1])) {
                                displayNum = p.join('');
                            } else {
                                displayNum = String(seat.number);
                            }
                        } else if (seat.number) {
                            displayNum = String(seat.number);
                        } else if (seat.col !== undefined && seat.col !== null && seat.col !== '') {
                            displayNum = String(seat.col);
                        } else {
                            displayNum = String(sIdx + 1);
                        }

                        let dynamicFontSize = Math.max(6, Math.min(14, Math.round(seatSide * 0.44)));
                        let letterSpacing = '-0.2px';
                        if (displayNum.length >= 4) {
                            dynamicFontSize = Math.max(5.5, Math.min(9, Math.round(seatSide * 0.28)));
                            letterSpacing = '-0.6px';
                        } else if (displayNum.length === 3) {
                            dynamicFontSize = Math.max(5.8, Math.min(11, Math.round(seatSide * 0.34)));
                            letterSpacing = '-0.5px';
                        } else if (displayNum.length === 2) {
                            dynamicFontSize = Math.max(6.5, Math.min(13, Math.round(seatSide * 0.40)));
                            letterSpacing = '-0.3px';
                        } else {
                            dynamicFontSize = Math.max(7, Math.min(15, Math.round(seatSide * 0.48)));
                            letterSpacing = '0px';
                        }

                        const seatG = document.createElementNS(NS, 'g');
                        seatG.setAttribute('class', 'public-seat-group' + (isOccupied ? ' occupied' : '') + (isBlocked ? ' digital-disabled' : ''));
                        seatG.setAttribute('data-seat-id', seat.label || seat.display_name || seat.number || seat.id || '');
                        seatG.setAttribute('data-zone-name', z.name);
                        seatG.style.cursor = (isOccupied || isBlocked) ? 'not-allowed' : 'pointer';

                        // Cuadro de Butaca
                        const rect = document.createElementNS(NS, 'rect');
                        rect.setAttribute('x', seat.x - halfSide);
                        rect.setAttribute('y', seat.y - halfSide);
                        rect.setAttribute('width', seatSide);
                        rect.setAttribute('height', seatSide);
                        rect.setAttribute('rx', cornerRadius);
                        rect.setAttribute('ry', cornerRadius);
                        rect.setAttribute('class', 'public-seat-rect');
                        rect.setAttribute('fill', isOccupied ? '#DC2626' : (isBlocked ? '#475569' : color));
                        rect.setAttribute('stroke', isOccupied ? '#B91C1C' : (isBlocked ? '#94A3B8' : 'rgba(255, 255, 255, 0.5)'));
                        rect.setAttribute('stroke-width', strokeW);
                        rect.setAttribute('opacity', isBlocked ? '0.70' : '1');
                        rect.setAttribute('filter', 'url(#publicSeatShadow)');
                        seatG.appendChild(rect);

                        // Número Centrado Adentro de la Butaca
                        if (seatSide >= 9) {
                            const txt = document.createElementNS(NS, 'text');
                            txt.setAttribute('x', seat.x);
                            txt.setAttribute('y', seat.y);
                            txt.setAttribute('text-anchor', 'middle');
                            txt.setAttribute('dominant-baseline', 'central');
                            txt.setAttribute('fill', '#FFFFFF');
                            txt.setAttribute('font-size', dynamicFontSize);
                            txt.setAttribute('letter-spacing', letterSpacing);
                            txt.setAttribute('font-weight', '900');
                            txt.setAttribute('font-family', 'sans-serif');
                            txt.setAttribute('pointer-events', 'none');
                            txt.textContent = displayNum;
                            seatG.appendChild(txt);
                        }

                        // Eventos sobre butaca individual
                        seatG.addEventListener('mouseenter', (e) => this.onSeatMouseEnter(e, seat, z, isOccupied));
                        seatG.addEventListener('mousemove', (e) => this.onZoneMouseMove(e));
                        seatG.addEventListener('mouseleave', () => this.onZoneMouseLeave());
                        seatG.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.onSeatClick(seatG, seat, z, isOccupied);
                        });

                        seatsGroup.appendChild(seatG);
                    });
                }

                // 2.3 Tipografía Grande Centrada (Para Zonas Generales y Escenario)
                if (!hasSeats) {
                    const textG = document.createElementNS(NS, 'g');
                    textG.setAttribute('class', 'svg-zone-center-text');

                    if (isStage) {
                        // Título Grande de ESCENARIO
                        const stageTitle = nameText.toUpperCase();
                        const stageFontSize = Math.max(16, Math.min(30, Math.round(zW / Math.max(7, stageTitle.length * 0.75))));

                        const stageTxt = document.createElementNS(NS, 'text');
                        stageTxt.setAttribute('x', centerX);
                        stageTxt.setAttribute('y', centerY);
                        stageTxt.setAttribute('text-anchor', 'middle');
                        stageTxt.setAttribute('dominant-baseline', 'central');
                        stageTxt.setAttribute('fill', '#FFFFFF');
                        stageTxt.setAttribute('font-size', stageFontSize);
                        stageTxt.setAttribute('font-weight', '900');
                        stageTxt.setAttribute('font-family', "'Montserrat', 'Impact', 'Arial Black', sans-serif");
                        stageTxt.setAttribute('letter-spacing', '2px');
                        stageTxt.textContent = stageTitle;
                        textG.appendChild(stageTxt);
                    } else {
                        // Título Grande de Zona General (ej: CAMPO A, ZONA GENERAL, ZONA VIP)
                        const rawTitle = nameText.toUpperCase();
                        const titleFontSize = Math.max(16, Math.min(32, Math.round(zW / Math.max(6, rawTitle.length * 0.75))));

                        const mainTxt = document.createElementNS(NS, 'text');
                        mainTxt.setAttribute('x', centerX);
                        mainTxt.setAttribute('y', priceVal > 0 ? (centerY - 5) : centerY);
                        mainTxt.setAttribute('text-anchor', 'middle');
                        mainTxt.setAttribute('dominant-baseline', 'central');
                        mainTxt.setAttribute('fill', '#FFFFFF');
                        mainTxt.setAttribute('font-size', titleFontSize);
                        mainTxt.setAttribute('font-weight', '900');
                        mainTxt.setAttribute('font-family', "'Montserrat', 'Impact', 'Arial Black', sans-serif");
                        mainTxt.setAttribute('letter-spacing', '1.5px');
                        mainTxt.textContent = rawTitle;
                        textG.appendChild(mainTxt);

                        if (priceVal > 0) {
                            const subTxt = document.createElementNS(NS, 'text');
                            subTxt.setAttribute('x', centerX);
                            subTxt.setAttribute('y', centerY + Math.max(14, titleFontSize * 0.65));
                            subTxt.setAttribute('text-anchor', 'middle');
                            subTxt.setAttribute('dominant-baseline', 'central');
                            subTxt.setAttribute('fill', 'rgba(255, 255, 255, 0.95)');
                            subTxt.setAttribute('font-size', Math.max(9.5, Math.min(13, titleFontSize * 0.45)));
                            subTxt.setAttribute('font-weight', '800');
                            subTxt.setAttribute('font-family', 'sans-serif');
                            subTxt.textContent = priceFormatted;
                            textG.appendChild(subTxt);
                        }
                    }

                    labelsGroup.appendChild(textG);
                } else {
                    // 2.4 Si tiene Butacas: Nombre del Sector Arriba de la Zona (o en posición personalizada)
                    const headerG = document.createElementNS(NS, 'g');
                    headerG.setAttribute('class', 'svg-zone-header-badge');
                    headerG.style.pointerEvents = 'none';

                    const rawTitle = nameText.toUpperCase();
                    const defaultLabelY = (zMinY >= 34) ? (zMinY - 14) : Math.max(14, zMinY + 14);
                    const labelX = hasCustomLabel ? centerX : autoCenterX;
                    const labelY = hasCustomLabel ? centerY : defaultLabelY;

                    let titleTextContent = '';
                    if (isDigitalDisabled) {
                        titleTextContent = `${rawTitle} • 🔒 SOLO FÍSICA`;
                    } else if (!isZoneAvailable) {
                        titleTextContent = `${rawTitle} • 🚫 AGOTADO`;
                    } else {
                        titleTextContent = `${rawTitle}${priceVal > 0 ? ' • ' + priceFormatted : ''}`;
                    }
                    const badgeW = Math.max(90, Math.min(280, titleTextContent.length * 8.0 + 26));

                    const badgeRect = document.createElementNS(NS, 'rect');
                    badgeRect.setAttribute('x', labelX - badgeW / 2);
                    badgeRect.setAttribute('y', labelY - 12);
                    badgeRect.setAttribute('width', badgeW);
                    badgeRect.setAttribute('height', '24');
                    badgeRect.setAttribute('rx', '7');
                    badgeRect.setAttribute('fill', 'rgba(15, 23, 42, 0.92)');
                    badgeRect.setAttribute('stroke', isDigitalDisabled ? '#64748B' : color);
                    badgeRect.setAttribute('stroke-width', '1.3');
                    badgeRect.setAttribute('filter', 'url(#publicSeatShadow)');
                    headerG.appendChild(badgeRect);

                    const titleTxt = document.createElementNS(NS, 'text');
                    titleTxt.setAttribute('x', labelX);
                    titleTxt.setAttribute('y', labelY);
                    titleTxt.setAttribute('text-anchor', 'middle');
                    titleTxt.setAttribute('dominant-baseline', 'central');
                    titleTxt.setAttribute('fill', '#FFFFFF');
                    titleTxt.setAttribute('font-size', '11');
                    titleTxt.setAttribute('font-weight', '900');
                    titleTxt.setAttribute('font-family', 'sans-serif');
                    titleTxt.setAttribute('letter-spacing', '0.5px');
                    titleTxt.textContent = titleTextContent;
                    headerG.appendChild(titleTxt);

                    labelsGroup.appendChild(headerG);
                }
            });

            svg.appendChild(zonesGroup);
            svg.appendChild(seatsGroup);
            svg.appendChild(labelsGroup);
        },

        onZoneMouseEnter: function(e, z, isStage) {
            const tooltip = document.getElementById('publicZoneTooltip');
            const titleEl = document.getElementById('tooltipZoneTitle');
            const priceEl = document.getElementById('tooltipZonePrice');
            const capEl = document.getElementById('tooltipZoneCap');
            const hintEl = document.getElementById('tooltipZoneActionHint');
            if (!tooltip) return;

            const hasSeats = Array.isArray(z.seats) && z.seats.length > 0;
            const priceVal = parseFloat(z.price) || 0;
            const isDigitalDisabled = !!z.is_digital_disabled;
            const isAvailable = (z.available !== false && z.is_available !== false) && !isDigitalDisabled;

            if (titleEl) {
                titleEl.textContent = isStage ? (z.name.startsWith('🎪') ? z.name : `🎪 ${z.name}`) : (z.name || 'Zona');
            }

            if (priceEl) {
                if (isStage && priceVal === 0) {
                    priceEl.textContent = 'ESCENARIO DEL EVENTO';
                    priceEl.style.color = '#38BDF8';
                } else if (isDigitalDisabled) {
                    priceEl.textContent = '🔒 SOLO VENTA FÍSICA';
                    priceEl.style.color = '#94A3B8';
                } else if (!isAvailable) {
                    priceEl.textContent = '🚫 ENTRADAS AGOTADAS';
                    priceEl.style.color = '#EF4444';
                } else {
                    priceEl.textContent = 'S/ ' + priceVal.toFixed(2);
                    priceEl.style.color = '#10B981';
                }
            }

            if (capEl) {
                if (isStage && priceVal === 0) {
                    capEl.textContent = 'Zona de Presentación';
                    capEl.style.display = 'block';
                } else if (isDigitalDisabled) {
                    capEl.textContent = '🔒 No disponible para venta digital';
                    capEl.style.display = 'block';
                } else if (!isAvailable) {
                    capEl.textContent = '🚫 Entradas agotadas';
                    capEl.style.display = 'block';
                } else if (hasSeats) {
                    capEl.textContent = 'Zona con Selección de Butacas';
                    capEl.style.display = 'block';
                } else {
                    capEl.textContent = '';
                    capEl.style.display = 'none';
                }
            }

            if (hintEl) {
                if (isStage && priceVal === 0) {
                    hintEl.textContent = '🎪 Ubicación de Tarima';
                    hintEl.style.background = 'rgba(56, 189, 248, 0.2)';
                    hintEl.style.color = '#38BDF8';
                } else if (isDigitalDisabled) {
                    hintEl.textContent = '🔒 Solo venta física / boletería';
                    hintEl.style.background = 'rgba(100, 116, 139, 0.25)';
                    hintEl.style.color = '#94A3B8';
                } else if (!isAvailable) {
                    hintEl.textContent = '🚫 Sector agotado';
                    hintEl.style.background = 'rgba(239, 68, 68, 0.2)';
                    hintEl.style.color = '#EF4444';
                } else if (hasSeats) {
                    hintEl.textContent = '🪑 Selecciona tus butacas en el plano';
                    hintEl.style.background = 'rgba(16, 185, 129, 0.2)';
                    hintEl.style.color = '#10B981';
                } else {
                    hintEl.textContent = '👉 Clic para seleccionar zona';
                    hintEl.style.background = 'rgba(255,85,0,0.2)';
                    hintEl.style.color = '#FF8800';
                }
            }

            tooltip.style.display = 'block';
            this.onZoneMouseMove(e);
        },

        onSeatMouseEnter: function(e, seat, z, isOccupied) {
            const tooltip = document.getElementById('publicZoneTooltip');
            const titleEl = document.getElementById('tooltipZoneTitle');
            const priceEl = document.getElementById('tooltipZonePrice');
            const capEl = document.getElementById('tooltipZoneCap');
            const hintEl = document.getElementById('tooltipZoneActionHint');
            if (!tooltip) return;

            const isDigitalDisabled = !!z.is_digital_disabled;
            const isBlocked = isDigitalDisabled || (z.available === false || z.is_available === false);

            const seatLabel = seat.label || seat.display_name || (seat.number ? `Butaca ${seat.number}` : (seat.id ? `Butaca ${seat.id}` : 'Asiento'));
            if (titleEl) titleEl.textContent = `${z.name} (${seatLabel})`;
            
            if (priceEl) {
                if (isOccupied) {
                    priceEl.textContent = '🚫 OCUPADA / VENDIDA';
                    priceEl.style.color = '#EF4444';
                } else if (isDigitalDisabled) {
                    priceEl.textContent = '🔒 SOLO VENTA FÍSICA';
                    priceEl.style.color = '#94A3B8';
                } else if (z.available === false || z.is_available === false) {
                    priceEl.textContent = '🚫 AGOTADA';
                    priceEl.style.color = '#EF4444';
                } else {
                    priceEl.textContent = 'S/ ' + (parseFloat(z.price) || 0).toFixed(2);
                    priceEl.style.color = '#10B981';
                }
            }
            
            const isCurrentlySelected = (e.currentTarget && e.currentTarget.classList && e.currentTarget.classList.contains('selected'));

            if (capEl) {
                capEl.style.display = 'block';
                if (isOccupied) {
                    capEl.textContent = 'No disponible para venta';
                } else if (isDigitalDisabled) {
                    capEl.textContent = '🔒 No disponible para venta digital';
                } else if (z.available === false || z.is_available === false) {
                    capEl.textContent = 'Entradas agotadas';
                } else if (isCurrentlySelected) {
                    capEl.textContent = '✅ Butaca seleccionada';
                } else {
                    capEl.textContent = 'Asiento libre / disponible';
                }
            }

            if (hintEl) {
                if (isOccupied) {
                    hintEl.textContent = '❌ Butaca no disponible';
                    hintEl.style.background = 'rgba(239,68,68,0.2)';
                    hintEl.style.color = '#EF4444';
                } else if (isDigitalDisabled) {
                    hintEl.textContent = '🔒 Solo disponible en boletería física';
                    hintEl.style.background = 'rgba(100, 116, 139, 0.25)';
                    hintEl.style.color = '#94A3B8';
                } else if (z.available === false || z.is_available === false) {
                    hintEl.textContent = '🚫 Sector agotado';
                    hintEl.style.background = 'rgba(239,68,68,0.15)';
                    hintEl.style.color = '#EF4444';
                } else if (isCurrentlySelected) {
                    hintEl.textContent = '🔄 Clic para desmarcar butaca';
                    hintEl.style.background = 'rgba(239,68,68,0.15)';
                    hintEl.style.color = '#EF4444';
                } else {
                    hintEl.textContent = '👉 Clic para elegir esta butaca';
                    hintEl.style.background = 'rgba(255,85,0,0.2)';
                    hintEl.style.color = '#FF8800';
                }
            }

            tooltip.style.display = 'block';
            this.onZoneMouseMove(e);
        },

        onSeatClick: function(seatGroup, seat, z, isOccupied) {
            const isDigitalDisabled = !!z.is_digital_disabled;
            const isBlocked = isDigitalDisabled || (z.available === false || z.is_available === false);

            if (isOccupied) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Butaca Ocupada',
                        text: 'Esta butaca ya fue vendida y no se encuentra disponible.',
                        icon: 'error',
                        confirmButtonColor: '#EF4444',
                        confirmButtonText: 'Entendido'
                    });
                }
                return;
            }

            if (isBlocked) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: isDigitalDisabled ? 'Venta Digital No Disponible' : 'Sector Agotado',
                        text: isDigitalDisabled 
                            ? `Las butacas del sector "${z.name}" no están habilitadas para compra por internet (solo disponibles en puntos de venta físicos o boletería).`
                            : `Las entradas para el sector "${z.name}" se encuentran agotadas.`,
                        icon: isDigitalDisabled ? 'info' : 'warning',
                        confirmButtonColor: '#0F172A',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    alert(isDigitalDisabled 
                        ? `El sector "${z.name}" no está disponible para venta digital.` 
                        : `El sector "${z.name}" se encuentra agotado.`);
                }
                return;
            }

            seatGroup.classList.toggle('selected');
            this.syncZoneSeatCount(z.name);
        },

        syncZoneSeatCount: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            const actualName = z ? z.name : zoneName;

            // 1. Obtener todas las butacas seleccionadas actualmente en esta zona
            const selectedGroups = Array.from(document.querySelectorAll(`.public-seat-group.selected[data-zone-name="${actualName}"]`));
            const selectedCount = selectedGroups.length;
            const seatCodes = selectedGroups.map(g => g.getAttribute('data-seat-id') || 'Asiento').filter(Boolean);

            // 2. Resaltar la zona completa si tiene al menos 1 butaca seleccionada
            document.querySelectorAll(`.svg-public-zone[data-zone-name="${actualName}"]`).forEach(poly => {
                if (selectedCount > 0) {
                    poly.classList.add('selected');
                } else {
                    poly.classList.remove('selected');
                }
            });

            // 3. Sincronizar el contador exacto en la lista de entradas
            const matchedRows = this.getTicketRowsForZone(actualName);
            matchedRows.forEach(row => {
                const isRowAvail = row.getAttribute('data-available') !== 'false';
                const isRowDigDisabled = row.getAttribute('data-digital-disabled') === 'true';

                if (!isRowAvail || isRowDigDisabled) {
                    const countEl = row.querySelector('.ticket-count-val');
                    if (countEl) countEl.textContent = '0';
                    row.removeAttribute('data-selected-seats');
                    const seatContainer = row.querySelector('.selected-seat-tags-box');
                    if (seatContainer) seatContainer.innerHTML = '';
                    return;
                }

                const countEl = row.querySelector('.ticket-count-val');
                if (countEl) {
                    countEl.textContent = selectedCount;
                }
                row.setAttribute('data-selected-seats', JSON.stringify(seatCodes));

                // Mostrar etiquetas visuales de butacas seleccionadas bajo la entrada
                let seatContainer = row.querySelector('.selected-seat-tags-box');
                if (!seatContainer) {
                    seatContainer = document.createElement('div');
                    seatContainer.className = 'selected-seat-tags-box';
                    seatContainer.style.cssText = 'display: flex; flex-wrap: wrap; gap: 0.3rem; margin-top: 0.45rem;';
                    const targetParent = row.firstElementChild || row;
                    targetParent.appendChild(seatContainer);
                }

                if (seatCodes.length > 0) {
                    seatContainer.innerHTML = seatCodes.map(code => 
                        `<span style="background: rgba(255, 85, 0, 0.1); border: 1px solid rgba(255, 85, 0, 0.35); color: #FF5500; font-size: 0.685rem; font-weight: 800; padding: 2px 7px; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.25rem;">🪑 ${code}</span>`
                    ).join('');
                } else {
                    seatContainer.innerHTML = '';
                }

                if (selectedCount > 0) {
                    row.classList.add('map-highlighted');
                    setTimeout(() => row.classList.remove('map-highlighted'), 800);
                }
            });

            if (matchedRows.length > 0 && selectedCount > 0) {
                matchedRows[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            // 4. Recalcular precio total inmediatamente
            if (typeof window.recalculateTicketTotal === 'function') {
                window.recalculateTicketTotal();
            }
        },

        selectNextAvailableSeat: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            if (z && (z.is_digital_disabled || z.available === false || z.is_available === false)) {
                return false;
            }
            const actualName = z ? z.name : zoneName;

            const availableGroup = document.querySelector(`.public-seat-group[data-zone-name="${actualName}"]:not(.occupied):not(.digital-disabled):not(.selected)`);
            if (!availableGroup) return false;

            availableGroup.classList.add('selected');
            this.syncZoneSeatCount(actualName);
            return true;
        },

        unselectLastSeat: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            const actualName = z ? z.name : zoneName;

            const selectedGroups = Array.from(document.querySelectorAll(`.public-seat-group.selected[data-zone-name="${actualName}"]`));
            if (selectedGroups.length === 0) return false;

            const lastGroup = selectedGroups[selectedGroups.length - 1];
            lastGroup.classList.remove('selected');
            this.syncZoneSeatCount(actualName);
            return true;
        },

        hasSeatsInZone: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            return !!(z && Array.isArray(z.seats) && z.seats.length > 0);
        },

        selectGeneralZone: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            if (z && (z.is_digital_disabled || z.available === false || z.is_available === false)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: z.is_digital_disabled ? 'Venta Digital No Disponible' : 'Zona Agotada',
                        text: z.is_digital_disabled 
                            ? `La zona "${z.name}" no está habilitada para compra por internet (solo disponible en puntos de venta físicos o boletería).`
                            : `La zona "${z.name}" se encuentra agotada.`,
                        icon: z.is_digital_disabled ? 'info' : 'warning',
                        confirmButtonColor: '#0F172A',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    alert(`La zona "${z.name}" no está disponible para compra.`);
                }
                return;
            }

            this.selectedZoneName = zoneName;

            document.querySelectorAll('.svg-public-zone').forEach(poly => {
                if (this.isZoneMatch(poly.getAttribute('data-zone-name'), zoneName)) {
                    poly.classList.add('selected');
                } else {
                    poly.classList.remove('selected');
                }
            });

            const matchedRows = this.getTicketRowsForZone(zoneName);
            matchedRows.forEach(row => {
                const countEl = row.querySelector('.ticket-count-val');
                const btnPlus = row.querySelector('.btn-ticket-plus');
                if (countEl) {
                    let currentQty = parseInt(countEl.textContent) || 0;
                    if (currentQty === 0 && btnPlus) {
                        btnPlus.click();
                    }
                }
                row.classList.add('map-highlighted');
                setTimeout(() => row.classList.remove('map-highlighted'), 800);
            });

            if (matchedRows.length > 0) {
                matchedRows[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            if (typeof window.recalculateTicketTotal === 'function') {
                window.recalculateTicketTotal();
            }
        },

        getTicketRowsForZone: function(zoneName) {
            const rows = [];
            const rawTarget = (zoneName || '').trim();
            if (!rawTarget) return rows;

            const cleanTarget = rawTarget.toLowerCase().replace(/zona|sector/gi, '').trim();

            document.querySelectorAll('.ticket-type-row').forEach(row => {
                const rawRowZone = (row.getAttribute('data-zone-name') || '').trim();
                const nameEl = row.querySelector('.ticket-name');
                const rawRowName = nameEl ? nameEl.textContent.replace('🎟️', '').replace('🎁', '').trim() : '';

                // 1. Coincidencia exacta estricta
                if (rawRowZone && rawRowZone.toLowerCase() === rawTarget.toLowerCase()) {
                    rows.push(row);
                    return;
                }
                if (rawRowName && rawRowName.toLowerCase() === rawTarget.toLowerCase()) {
                    rows.push(row);
                    return;
                }

                // 2. Coincidencia limpia sin "zona" ni "sector"
                const cleanRowZone = rawRowZone.toLowerCase().replace(/zona|sector/gi, '').trim();
                const cleanRowName = rawRowName.toLowerCase().replace(/zona|sector/gi, '').trim();

                if (cleanTarget && cleanRowZone && cleanRowZone === cleanTarget) {
                    rows.push(row);
                    return;
                }
                if (cleanTarget && cleanRowName && cleanRowName === cleanTarget) {
                    rows.push(row);
                    return;
                }

                // 3. Coincidencia parcial sólo si ambas cadenas son significativas (mínimo 4 caracteres)
                if (cleanTarget.length >= 4 && cleanRowZone.length >= 4) {
                    if (cleanRowZone.includes(cleanTarget) || cleanTarget.includes(cleanRowZone)) {
                        rows.push(row);
                        return;
                    }
                }
                if (cleanTarget.length >= 4 && cleanRowName.length >= 4) {
                    if (cleanRowName.includes(cleanTarget) || cleanTarget.includes(cleanRowName)) {
                        rows.push(row);
                        return;
                    }
                }
            });
            return rows;
        },

        isZoneMatch: function(name1, name2) {
            if (!name1 || !name2) return false;
            const r1 = name1.trim().toLowerCase();
            const r2 = name2.trim().toLowerCase();
            if (r1 === r2) return true;

            const n1 = r1.replace(/zona|sector/gi, '').trim();
            const n2 = r2.replace(/zona|sector/gi, '').trim();
            if (!n1 || !n2) return false;
            if (n1 === n2) return true;

            if (n1.length >= 4 && n2.length >= 4) {
                return n1.includes(n2) || n2.includes(n1);
            }
            return false;
        },

        onZoneMouseMove: function(e) {
            const tooltip = document.getElementById('publicZoneTooltip');
            const container = document.getElementById('publicSeatMapContainer');
            if (!tooltip || !container) return;

            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            tooltip.style.left = `${x}px`;
            tooltip.style.top = `${y}px`;
        },

        onZoneMouseLeave: function() {
            const tooltip = document.getElementById('publicZoneTooltip');
            if (tooltip) tooltip.style.display = 'none';
        }
    };

    window.PublicSeatMap = PublicSeatMap;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => PublicSeatMap.init());
    } else {
        PublicSeatMap.init();
    }
</script>
