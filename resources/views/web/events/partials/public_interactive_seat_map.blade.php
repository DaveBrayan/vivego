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
        min-height: 480px;
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
            min-height: 380px;
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
        height: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF !important;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .public-seatmap-canvas-layer {
            height: 350px;
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
        cursor: default;
    }

    .svg-public-zone.stage-zone.clickable {
        cursor: pointer;
    }

    .svg-zone-center-text {
        pointer-events: none;
        user-select: none;
    }

    /* Estilos de Butacas Cuadradas con Número Integrado */
    .public-seat-group {
        cursor: pointer;
        transition: transform 0.12s ease;
    }

    .public-seat-group:hover {
        transform: scale(1.12);
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.35));
    }

    .public-seat-group:hover .public-seat-rect {
        stroke: #FF5500 !important;
        stroke-width: 2px !important;
    }

    .public-seat-group.selected .public-seat-rect {
        fill: #FF5500 !important;
        stroke: #FFFFFF !important;
        stroke-width: 2px !important;
        filter: drop-shadow(0 0 10px rgba(255, 85, 0, 0.8)) !important;
    }

    .public-seat-group.occupied {
        cursor: not-allowed;
    }

    .public-seat-group.occupied:hover {
        transform: none;
        filter: none;
    }

    .public-seat-group.occupied .public-seat-rect {
        fill: #DC2626 !important;
        stroke: #B91C1C !important;
        opacity: 0.95;
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

            <!-- Área del SVG Auto-Centrado en Fondo Blanco -->
            <div id="publicSeatMapCanvasLayer" class="public-seatmap-canvas-layer">
                
                @if(!empty($event['reference_image']))
                    <img id="publicSeatMapBgImg" src="{{ $event['reference_image'] }}" alt="Plano de Fondo" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; pointer-events: none; opacity: 0.85;">
                @endif

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
                <div id="tooltipZoneCap" style="font-size: 0.725rem; color: #94A3B8; margin-top: 0.1rem;"></div>
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
            });

            if (totalPointsCount > 0) {
                const boxWidth = Math.max(100, maxX - minX);
                const boxHeight = Math.max(100, maxY - minY);
                const padX = Math.max(25, Math.round(boxWidth * 0.05));
                const padY = Math.max(25, Math.round(boxHeight * 0.05));
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

                const isClickable = !hasSeats && (!isStage || priceVal > 0);
                const isSelected = this.isZoneMatch(this.selectedZoneName, z.name);

                const xs = z.points.map(p => p.x);
                const ys = z.points.map(p => p.y);
                const zMinX = Math.min(...xs);
                const zMaxX = Math.max(...xs);
                const zMinY = Math.min(...ys);
                const zMaxY = Math.max(...ys);
                const zW = zMaxX - zMinX;
                const zH = zMaxY - zMinY;
                const centerX = xs.reduce((a, b) => a + b, 0) / xs.length;
                const centerY = ys.reduce((a, b) => a + b, 0) / ys.length;

                // 2.1 Superficie Poligonal Fiel (Polygon)
                const pointsStr = z.points.map(p => `${p.x},${p.y}`).join(' ');
                const polygon = document.createElementNS(NS, 'polygon');
                polygon.setAttribute('points', pointsStr);
                polygon.setAttribute('stroke-linejoin', 'round');
                polygon.setAttribute('stroke-linecap', 'round');
                polygon.setAttribute('data-zone-name', z.name);

                if (isStage) {
                    polygon.setAttribute('fill', color || '#334155');
                    polygon.setAttribute('fill-opacity', isSelected ? '0.95' : '0.82');
                    polygon.setAttribute('stroke', isSelected ? '#FF5500' : (color || '#1E293B'));
                    polygon.setAttribute('stroke-width', isSelected ? '3.0' : '1.8');
                    polygon.setAttribute('class', `svg-public-zone stage-zone ${isClickable ? 'clickable' : ''} ${isSelected ? 'selected' : ''}`);
                } else if (hasSeats) {
                    // Zona con butacas: fondo de sector contenedor suave (gris/tinte)
                    polygon.setAttribute('fill', color);
                    polygon.setAttribute('fill-opacity', isSelected ? '0.35' : '0.22');
                    polygon.setAttribute('stroke', isSelected ? '#FF5500' : color);
                    polygon.setAttribute('stroke-width', isSelected ? '2.8' : '1.8');
                    polygon.setAttribute('class', `svg-public-zone has-seats ${isSelected ? 'selected' : ''}`);
                } else {
                    // Zona General sin butacas: color sólido vibrante con alto impacto
                    polygon.setAttribute('fill', color);
                    polygon.setAttribute('fill-opacity', isSelected ? '0.98' : '0.90');
                    polygon.setAttribute('stroke', isSelected ? '#FF5500' : 'rgba(0,0,0,0.15)');
                    polygon.setAttribute('stroke-width', isSelected ? '3.5' : '1.5');
                    polygon.setAttribute('class', `svg-public-zone no-seats ${isSelected ? 'selected' : ''}`);
                }

                // Eventos de Mouse sobre la Superficie
                polygon.addEventListener('mouseenter', (e) => this.onZoneMouseEnter(e, z, isStage));
                polygon.addEventListener('mousemove', (e) => this.onZoneMouseMove(e));
                polygon.addEventListener('mouseleave', () => this.onZoneMouseLeave());

                if (hasSeats) {
                    polygon.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const hintEl = document.getElementById('tooltipZoneActionHint');
                        if (hintEl) {
                            hintEl.textContent = '👇 Haz clic en una butaca para elegirla';
                            hintEl.style.background = 'rgba(255,85,0,0.25)';
                            hintEl.style.color = '#FF5500';
                        }
                    });
                } else if (isClickable) {
                    polygon.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.selectGeneralZone(z.name);
                    });
                }

                zonesGroup.appendChild(polygon);

                // 2.2 Si tiene Butacas: Renderizar Cuadrados con su Número Adentro (1, 2, 3...)
                if (hasSeats) {
                    // Calcular tamaño dinámico de la butaca según separación
                    let minStep = 24;
                    if (z.seats.length >= 2) {
                        let dMin = Infinity;
                        for (let i = 0; i < Math.min(z.seats.length, 15); i++) {
                            for (let j = i + 1; j < Math.min(z.seats.length, 15); j++) {
                                const dist = Math.hypot(z.seats[i].x - z.seats[j].x, z.seats[i].y - z.seats[j].y);
                                if (dist > 4 && dist < dMin) dMin = dist;
                            }
                        }
                        if (dMin < Infinity) minStep = dMin;
                    }

                    const seatSide = Math.max(14, Math.min(26, Math.round(minStep * 0.82)));
                    const halfSide = seatSide / 2;
                    const numFontSize = Math.max(7.5, Math.min(12, Math.round(seatSide * 0.50)));

                    z.seats.forEach((seat, sIdx) => {
                        const isOccupied = (seat.status === 'occupied' || seat.status === 'ocupado' || seat.status === 'vendido' || seat.is_occupied);
                        
                        // Extraer el número visible (1, 2, 3... o A-1)
                        let displayNum = '';
                        if (seat.col !== undefined && seat.col !== null && seat.col !== '') {
                            displayNum = String(seat.col);
                        } else if (seat.number) {
                            const p = String(seat.number).split('-');
                            displayNum = p.length > 1 ? p[p.length - 1] : String(seat.number);
                        } else {
                            displayNum = String(sIdx + 1);
                        }

                        const seatG = document.createElementNS(NS, 'g');
                        seatG.setAttribute('class', 'public-seat-group' + (isOccupied ? ' occupied' : ''));
                        seatG.setAttribute('data-seat-id', seat.label || seat.display_name || seat.number || seat.id || '');
                        seatG.setAttribute('data-zone-name', z.name);

                        // Cuadro de Butaca
                        const rect = document.createElementNS(NS, 'rect');
                        rect.setAttribute('x', seat.x - halfSide);
                        rect.setAttribute('y', seat.y - halfSide);
                        rect.setAttribute('width', seatSide);
                        rect.setAttribute('height', seatSide);
                        rect.setAttribute('rx', 3);
                        rect.setAttribute('ry', 3);
                        rect.setAttribute('class', 'public-seat-rect');
                        rect.setAttribute('fill', isOccupied ? '#DC2626' : color);
                        rect.setAttribute('stroke', isOccupied ? '#B91C1C' : 'rgba(255, 255, 255, 0.45)');
                        rect.setAttribute('stroke-width', '1.2');
                        rect.setAttribute('filter', 'url(#publicSeatShadow)');
                        seatG.appendChild(rect);

                        // Número Centrado Adentro de la Butaca
                        const txt = document.createElementNS(NS, 'text');
                        txt.setAttribute('x', seat.x);
                        txt.setAttribute('y', seat.y);
                        txt.setAttribute('text-anchor', 'middle');
                        txt.setAttribute('dominant-baseline', 'central');
                        txt.setAttribute('fill', '#FFFFFF');
                        txt.setAttribute('font-size', numFontSize);
                        txt.setAttribute('font-weight', '900');
                        txt.setAttribute('font-family', 'sans-serif');
                        txt.setAttribute('pointer-events', 'none');
                        txt.textContent = displayNum;
                        seatG.appendChild(txt);

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
                            subTxt.textContent = `${priceFormatted}${z.capacity ? ` • ${z.capacity} cap.` : ''}`;
                            textG.appendChild(subTxt);
                        }
                    }

                    labelsGroup.appendChild(textG);
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

            if (titleEl) {
                titleEl.textContent = isStage ? (z.name.startsWith('🎪') ? z.name : `🎪 ${z.name}`) : (z.name || 'Zona');
            }

            if (priceEl) {
                if (isStage && priceVal === 0) {
                    priceEl.textContent = 'ESCENARIO DEL EVENTO';
                    priceEl.style.color = '#38BDF8';
                } else {
                    priceEl.textContent = 'S/ ' + priceVal.toFixed(2);
                    priceEl.style.color = '#10B981';
                }
            }

            if (capEl) {
                if (isStage && priceVal === 0) {
                    capEl.textContent = 'Zona de Presentación';
                } else {
                    capEl.textContent = `Aforo: ${z.capacity || 'General'}`;
                }
            }

            if (hintEl) {
                if (isStage && priceVal === 0) {
                    hintEl.textContent = '🎪 Ubicación de Tarima';
                    hintEl.style.background = 'rgba(56, 189, 248, 0.2)';
                    hintEl.style.color = '#38BDF8';
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

            const seatLabel = seat.label || seat.display_name || (seat.number ? `Butaca ${seat.number}` : (seat.id ? `Butaca ${seat.id}` : 'Asiento'));
            if (titleEl) titleEl.textContent = `${z.name} (${seatLabel})`;
            
            if (priceEl) {
                if (isOccupied) {
                    priceEl.textContent = '🚫 OCUPADA / VENDIDA';
                    priceEl.style.color = '#EF4444';
                } else {
                    priceEl.textContent = 'S/ ' + (parseFloat(z.price) || 0).toFixed(2);
                    priceEl.style.color = '#10B981';
                }
            }
            
            const isCurrentlySelected = (e.currentTarget && e.currentTarget.classList && e.currentTarget.classList.contains('selected'));

            if (capEl) {
                if (isOccupied) {
                    capEl.textContent = 'No disponible para venta';
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
            const actualName = z ? z.name : zoneName;

            const availableGroup = document.querySelector(`.public-seat-group[data-zone-name="${actualName}"]:not(.occupied):not(.selected)`);
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
