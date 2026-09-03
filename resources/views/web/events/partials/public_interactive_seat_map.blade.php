<!-- =========================================================================
     COMPONENTE PÚBLICO: MAPA INTERACTIVO DE ZONAS & BUTACAS VECTORIALES
     ViveGo Pro Max - Zonas Grandes, Trazo Fino, Butacas con Separación
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
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
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
        gap: 0.65rem;
        position: relative;
        z-index: 5;
    }

    .public-seatmap-canvas-layer {
        position: relative;
        flex: 1;
        width: 100%;
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
    }

    @media (max-width: 768px) {
        .public-seatmap-canvas-layer {
            height: 320px;
        }
    }

    .svg-public-zone {
        transition: all 0.2s ease;
        filter: drop-shadow(0 3px 12px rgba(0, 0, 0, 0.05));
    }

    .svg-public-zone.no-seats {
        cursor: pointer;
    }

    .svg-public-zone.no-seats:hover {
        filter: drop-shadow(0 6px 18px rgba(255, 85, 0, 0.35));
        stroke: #FF5500 !important;
        stroke-width: 2.2 !important;
    }

    .svg-public-zone.no-seats.selected {
        filter: drop-shadow(0 6px 22px rgba(255, 85, 0, 0.55));
        stroke: #FF5500 !important;
        stroke-width: 2.5 !important;
    }

    .svg-public-zone.has-seats {
        cursor: default;
    }

    .svg-public-zone.has-seats:hover {
        filter: drop-shadow(0 4px 14px rgba(0, 0, 0, 0.08));
    }

    .svg-public-zone.has-seats.selected {
        filter: drop-shadow(0 6px 22px rgba(255, 85, 0, 0.45));
        stroke: #FF5500 !important;
        stroke-width: 2.2 !important;
    }

    .svg-public-zone-badge {
        pointer-events: none;
        user-select: none;
    }

    /* Estilos de Butacas Cuadradas con Separación Nítida */
    .public-seat-rect {
        cursor: pointer;
        transition: fill 0.15s ease, stroke 0.15s ease, filter 0.15s ease;
    }

    .public-seat-rect:hover {
        filter: drop-shadow(0 0 6px rgba(255, 85, 0, 0.8));
        stroke: #FF5500 !important;
        stroke-width: 2px !important;
    }

    .public-seat-rect.selected {
        fill: #FF5500 !important;
        stroke: #FFFFFF !important;
        stroke-width: 2px !important;
        filter: drop-shadow(0 0 8px #FF5500) !important;
    }

    /* Asientos Ocupados / Vendidos en Rojo */
    .public-seat-rect.occupied {
        fill: #EF4444 !important;
        stroke: #DC2626 !important;
        cursor: not-allowed;
        opacity: 0.9;
    }

    .public-seat-rect.occupied:hover {
        filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.8));
        stroke: #991B1B !important;
    }

    .ticket-type-row.map-highlighted {
        animation: pulseTicketRow 0.6s ease-in-out;
        border-color: #FF5500 !important;
        background: rgba(255, 85, 0, 0.06) !important;
    }

    @keyframes pulseTicketRow {
        0% { box-shadow: 0 0 0 0 rgba(255, 85, 0, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 85, 0, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 85, 0, 0); }
    }
</style>

@if(!empty($event['has_interactive_zones']) && !empty($event['interactive_zones']))
    <div class="public-seatmap-wrapper animate-fade-in detail-order-8" id="interactiveSeatMapSection">
        
        <!-- Canvas Contenedor del Mapa Interactivo (Fondo Blanco, Encabezado Integrado) -->
        <div id="publicSeatMapContainer" class="public-seatmap-card">
            
            <!-- Encabezado Integrado dentro del Cuadro Blanco -->
            <div class="public-seatmap-header-bar">
                <div class="info-block-icon" style="background: rgba(255, 85, 0, 0.1); color: #FF5500; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">🗺️</div>
                <div style="flex: 1; min-width: 0;">
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 900; color: #0F172A; line-height: 1.2;">Mapa Interactivo de Zonas</h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.775rem; color: #64748B; font-weight: 600;">Haz clic en cualquier sector o butaca del plano para seleccionarla directamente</p>
                </div>
            </div>

            <!-- Área del SVG Auto-Centrado -->
            <div id="publicSeatMapCanvasLayer" class="public-seatmap-canvas-layer">
                
                @if(!empty($event['reference_image']))
                    <img id="publicSeatMapBgImg" src="{{ $event['reference_image'] }}" alt="Plano de Fondo" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; pointer-events: none; opacity: 0.9;">
                @endif

                <svg id="publicSeatMapSvg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: visible;" preserveAspectRatio="xMidYMid meet">
                    <!-- Zonas generadas y auto-centradas dinámicamente por JS -->
                </svg>
            </div>

            <!-- Tooltip Flotante / Popover de Zona o Butaca -->
            <div id="publicZoneTooltip" style="position: absolute; display: none; z-index: 30; pointer-events: none; background: #0F172A; border: 1.5px solid rgba(255, 85, 0, 0.6); border-radius: 12px; padding: 0.65rem 0.95rem; color: #FFFFFF; box-shadow: 0 10px 25px rgba(0,0,0,0.25); transform: translate(-50%, -120%); transition: opacity 0.15s ease; min-width: 150px;">
                <div id="tooltipZoneTitle" style="font-weight: 900; font-size: 0.9rem; margin-bottom: 0.15rem; color: #FF8800;"></div>
                <div id="tooltipZonePrice" style="font-size: 1.05rem; font-weight: 900; color: #10B981;"></div>
                <div id="tooltipZoneCap" style="font-size: 0.725rem; color: #94A3B8; margin-top: 0.1rem;"></div>
                <div id="tooltipZoneActionHint" style="margin-top: 0.35rem; font-size: 0.68rem; font-weight: 800; background: rgba(255,85,0,0.2); color: #FF8800; padding: 2px 6px; border-radius: 4px; text-align: center;">
                    👉 Clic para seleccionar
                </div>
            </div>
        </div>

    </div>

    <hr class="event-section-divider detail-order-8">

@elseif(!empty($event['reference_image']))
    <!-- Sección: Imagen de Referencia Estándar (Modo Estándar) -->
    <div class="public-seatmap-wrapper animate-fade-in detail-order-8">
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

    <hr class="event-section-divider detail-order-8">
@endif

<!-- Script del Visualizador Interactivo Auto-Centrado -->
<script>
    const PublicSeatMap = {
        zones: (@json($event['interactive_zones'] ?? [])).filter(z => {
            const n = (z.name || '').toUpperCase().trim();
            const cap = (z.capacity_type || '').toUpperCase().trim();
            return n !== 'ESCENARIO' && n !== 'TARIMA' && cap !== 'ESCENARIO';
        }),
        selectedZoneName: null,

        init: function() {
            if (!this.zones || this.zones.length === 0) return;
            this.render();
        },

        render: function() {
            const svg = document.getElementById('publicSeatMapSvg');
            if (!svg) return;
            svg.innerHTML = '';

            const NS = "http://www.w3.org/2000/svg";

            // 1. Calcular caja envolvente (Bounding Box) más ajustada para que las zonas se vean más grandes
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
                const padX = Math.max(15, Math.round(boxWidth * 0.035));
                const padY = Math.max(18, Math.round(boxHeight * 0.04));
                const vbX = Math.round(minX - padX);
                const vbY = Math.round(minY - padY - 8);
                const vbW = Math.round(boxWidth + padX * 2);
                const vbH = Math.round(boxHeight + padY * 2 + 12);
                svg.setAttribute('viewBox', `${vbX} ${vbY} ${vbW} ${vbH}`);
            } else {
                svg.setAttribute('viewBox', '0 0 1000 650');
            }

            // 2. Renderizar cada zona como un contenedor blanco con su nombre y butacas
            this.zones.forEach((z) => {
                if (!Array.isArray(z.points) || z.points.length < 3) return;

                const color = z.color || '#FF5500';
                const hasSeats = Array.isArray(z.seats) && z.seats.length > 0;
                const priceVal = parseFloat(z.price) || 0;
                const priceFormatted = 'S/ ' + priceVal.toFixed(2);
                const nameText = z.name || 'Zona';

                const xs = z.points.map(p => p.x);
                const ys = z.points.map(p => p.y);
                const zMinX = Math.min(...xs);
                const zMaxX = Math.max(...xs);
                const zMinY = Math.min(...ys);
                const zMaxY = Math.max(...ys);
                const zW = zMaxX - zMinX;
                const zH = zMaxY - zMinY;

                // Grupo Contenedor de la Zona
                const zoneGroup = document.createElementNS(NS, 'g');
                zoneGroup.setAttribute('class', 'svg-zone-group');

                // 2.1 El Cuadro/Superficie de la Zona (Blanco Puro con Trazo Fino y Delicado)
                const isRectShape = z.points.length === 4;
                let zoneSurface;

                if (isRectShape) {
                    zoneSurface = document.createElementNS(NS, 'rect');
                    zoneSurface.setAttribute('x', zMinX);
                    zoneSurface.setAttribute('y', zMinY);
                    zoneSurface.setAttribute('width', zW);
                    zoneSurface.setAttribute('height', zH);
                    zoneSurface.setAttribute('rx', 14);
                    zoneSurface.setAttribute('ry', 14);
                } else {
                    zoneSurface = document.createElementNS(NS, 'polygon');
                    zoneSurface.setAttribute('points', z.points.map(p => `${p.x},${p.y}`).join(' '));
                    zoneSurface.setAttribute('stroke-linejoin', 'round');
                }

                const zoneClass = 'svg-public-zone ' + (hasSeats ? 'has-seats' : 'no-seats') + (this.selectedZoneName === z.name ? ' selected' : '');
                zoneSurface.setAttribute('class', zoneClass);
                zoneSurface.setAttribute('fill', '#FFFFFF');
                zoneSurface.setAttribute('stroke', color);
                zoneSurface.setAttribute('stroke-width', '1.6');
                zoneSurface.setAttribute('data-zone-name', z.name);

                // Eventos Mouse sobre la Zona
                zoneSurface.addEventListener('mouseenter', (e) => this.onZoneMouseEnter(e, z));
                zoneSurface.addEventListener('mousemove', (e) => this.onZoneMouseMove(e));
                zoneSurface.addEventListener('mouseleave', () => this.onZoneMouseLeave());

                if (hasSeats) {
                    zoneSurface.style.cursor = 'default';
                    zoneSurface.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const hintEl = document.getElementById('tooltipZoneActionHint');
                        if (hintEl) {
                            hintEl.textContent = '👇 Haz clic en una butaca para elegirla';
                            hintEl.style.background = 'rgba(255,85,0,0.25)';
                            hintEl.style.color = '#FF5500';
                        }
                    });
                } else {
                    zoneSurface.style.cursor = 'pointer';
                    zoneSurface.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.selectGeneralZone(z.name);
                    });
                }

                zoneGroup.appendChild(zoneSurface);

                // 2.2 Badge Superior con el Nombre de la Zona
                const pillW = Math.max(100, nameText.length * 8 + 26);
                const pillH = 22;
                const pillX = zMinX + 14;
                const pillY = zMinY - 11;

                const namePill = document.createElementNS(NS, 'rect');
                namePill.setAttribute('x', pillX);
                namePill.setAttribute('y', pillY);
                namePill.setAttribute('width', pillW);
                namePill.setAttribute('height', pillH);
                namePill.setAttribute('rx', 11);
                namePill.setAttribute('ry', 11);
                namePill.setAttribute('fill', color);
                namePill.setAttribute('filter', 'drop-shadow(0 2px 4px rgba(0,0,0,0.12))');
                namePill.setAttribute('class', 'svg-public-zone-badge');
                zoneGroup.appendChild(namePill);

                const nameTxt = document.createElementNS(NS, 'text');
                nameTxt.setAttribute('x', pillX + pillW / 2);
                nameTxt.setAttribute('y', pillY + 15);
                nameTxt.setAttribute('text-anchor', 'middle');
                nameTxt.setAttribute('fill', '#FFFFFF');
                nameTxt.setAttribute('font-size', '10');
                nameTxt.setAttribute('font-weight', '900');
                nameTxt.setAttribute('font-family', 'sans-serif');
                nameTxt.setAttribute('class', 'svg-public-zone-badge');
                nameTxt.textContent = nameText;
                zoneGroup.appendChild(nameTxt);

                // 2.3 Badge Superior Derecho con el Precio
                const pricePillW = 82;
                const pricePillH = 22;
                const pricePillX = zMaxX - pricePillW - 14;
                const pricePillY = zMinY - 11;

                const pricePill = document.createElementNS(NS, 'rect');
                pricePill.setAttribute('x', pricePillX);
                pricePill.setAttribute('y', pricePillY);
                pricePill.setAttribute('width', pricePillW);
                pricePill.setAttribute('height', pricePillH);
                pricePill.setAttribute('rx', 11);
                pricePill.setAttribute('ry', 11);
                pricePill.setAttribute('fill', '#FFF7ED');
                pricePill.setAttribute('stroke', '#FF5500');
                pricePill.setAttribute('stroke-width', '1.2');
                pricePill.setAttribute('filter', 'drop-shadow(0 2px 4px rgba(255,85,0,0.12))');
                pricePill.setAttribute('class', 'svg-public-zone-badge');
                zoneGroup.appendChild(pricePill);

                const priceTxt = document.createElementNS(NS, 'text');
                priceTxt.setAttribute('x', pricePillX + pricePillW / 2);
                priceTxt.setAttribute('y', pricePillY + 15);
                priceTxt.setAttribute('text-anchor', 'middle');
                priceTxt.setAttribute('fill', '#FF5500');
                priceTxt.setAttribute('font-size', '10');
                priceTxt.setAttribute('font-weight', '900');
                priceTxt.setAttribute('font-family', 'sans-serif');
                priceTxt.setAttribute('class', 'svg-public-zone-badge');
                priceTxt.textContent = priceFormatted;
                zoneGroup.appendChild(priceTxt);

                // 2.4 Contenido Interior: Butacas Cuadradas con Separación Óptima O Texto de Zona General
                if (hasSeats) {
                    const seatSide = 11;
                    const halfSide = seatSide / 2;

                    z.seats.forEach(seat => {
                        const isOccupied = (seat.status === 'occupied' || seat.status === 'ocupado' || seat.status === 'vendido' || seat.is_occupied);
                        const rect = document.createElementNS(NS, 'rect');
                        rect.setAttribute('x', seat.x - halfSide);
                        rect.setAttribute('y', seat.y - halfSide);
                        rect.setAttribute('width', seatSide);
                        rect.setAttribute('height', seatSide);
                        rect.setAttribute('rx', 2.8);
                        rect.setAttribute('ry', 2.8);
                        rect.setAttribute('class', 'public-seat-rect' + (isOccupied ? ' occupied' : ''));
                        rect.setAttribute('fill', isOccupied ? '#EF4444' : (color || '#10B981'));
                        rect.setAttribute('stroke', isOccupied ? '#DC2626' : '#FFFFFF');
                        rect.setAttribute('stroke-width', '1.2');
                        rect.setAttribute('data-seat-id', seat.label || seat.display_name || seat.number || seat.id || '');
                        rect.setAttribute('data-zone-name', z.name);

                        // Eventos sobre butaca individual
                        rect.addEventListener('mouseenter', (e) => this.onSeatMouseEnter(e, seat, z, isOccupied));
                        rect.addEventListener('mousemove', (e) => this.onZoneMouseMove(e));
                        rect.addEventListener('mouseleave', () => this.onZoneMouseLeave());
                        rect.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.onSeatClick(rect, seat, z, isOccupied);
                        });

                        zoneGroup.appendChild(rect);
                    });
                } else {
                    // Zona General sin butacas: Precio y Aforo Centrados Limpiamente
                    const centerX = (zMinX + zMaxX) / 2;
                    const centerY = (zMinY + zMaxY) / 2;

                    const bigPriceTxt = document.createElementNS(NS, 'text');
                    bigPriceTxt.setAttribute('x', centerX);
                    bigPriceTxt.setAttribute('y', centerY - 2);
                    bigPriceTxt.setAttribute('text-anchor', 'middle');
                    bigPriceTxt.setAttribute('fill', '#FF5500');
                    bigPriceTxt.setAttribute('font-size', '16');
                    bigPriceTxt.setAttribute('font-weight', '900');
                    bigPriceTxt.setAttribute('font-family', 'sans-serif');
                    bigPriceTxt.setAttribute('class', 'svg-public-zone-badge');
                    bigPriceTxt.textContent = priceFormatted;
                    zoneGroup.appendChild(bigPriceTxt);

                    const subTxt = document.createElementNS(NS, 'text');
                    subTxt.setAttribute('x', centerX);
                    subTxt.setAttribute('y', centerY + 16);
                    subTxt.setAttribute('text-anchor', 'middle');
                    subTxt.setAttribute('fill', '#64748B');
                    subTxt.setAttribute('font-size', '9.5');
                    subTxt.setAttribute('font-weight', '700');
                    subTxt.setAttribute('font-family', 'sans-serif');
                    subTxt.setAttribute('class', 'svg-public-zone-badge');
                    subTxt.textContent = `Aforo: ${z.capacity || 'General'} • Clic para seleccionar`;
                    zoneGroup.appendChild(subTxt);
                }

                svg.appendChild(zoneGroup);
            });
        },

        onZoneMouseEnter: function(e, z) {
            const tooltip = document.getElementById('publicZoneTooltip');
            const titleEl = document.getElementById('tooltipZoneTitle');
            const priceEl = document.getElementById('tooltipZonePrice');
            const capEl = document.getElementById('tooltipZoneCap');
            const hintEl = document.getElementById('tooltipZoneActionHint');
            if (!tooltip) return;

            const hasSeats = Array.isArray(z.seats) && z.seats.length > 0;

            if (titleEl) titleEl.textContent = z.name || 'Zona';
            if (priceEl) {
                priceEl.textContent = 'S/ ' + (parseFloat(z.price) || 0).toFixed(2);
                priceEl.style.color = '#10B981';
            }
            if (capEl) capEl.textContent = `Aforo: ${z.capacity || 'General'}`;
            if (hintEl) {
                if (hasSeats) {
                    hintEl.textContent = '🪑 Selecciona tus butacas en el mapa';
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
            
            const isCurrentlySelected = (e.target && e.target.classList && e.target.classList.contains('selected'));

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

        onSeatClick: function(rect, seat, z, isOccupied) {
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

            rect.classList.toggle('selected');
            this.syncZoneSeatCount(z.name);
        },

        syncZoneSeatCount: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            const actualName = z ? z.name : zoneName;

            // 1. Obtener todas las butacas seleccionadas actualmente en esta zona
            const selectedRects = Array.from(document.querySelectorAll(`.public-seat-rect.selected[data-zone-name="${actualName}"]`));
            const selectedCount = selectedRects.length;
            const seatCodes = selectedRects.map(r => r.getAttribute('data-seat-id') || 'Asiento').filter(Boolean);

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

            const availableRect = document.querySelector(`.public-seat-rect[data-zone-name="${actualName}"]:not(.occupied):not(.selected)`);
            if (!availableRect) return false;

            availableRect.classList.add('selected');
            this.syncZoneSeatCount(actualName);
            return true;
        },

        unselectLastSeat: function(zoneName) {
            const z = this.zones.find(item => this.isZoneMatch(item.name, zoneName));
            const actualName = z ? z.name : zoneName;

            const selectedRects = Array.from(document.querySelectorAll(`.public-seat-rect.selected[data-zone-name="${actualName}"]`));
            if (selectedRects.length === 0) return false;

            const lastRect = selectedRects[selectedRects.length - 1];
            lastRect.classList.remove('selected');
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
            const cleanTarget = (zoneName || '').toLowerCase().replace(/zona|sector/gi, '').trim();

            document.querySelectorAll('.ticket-type-row').forEach(row => {
                const rowZone = (row.getAttribute('data-zone-name') || '').toLowerCase().replace(/zona|sector/gi, '').trim();
                const nameEl = row.querySelector('.ticket-name');
                const rowName = nameEl ? nameEl.textContent.replace('🎟️', '').replace('🎁', '').toLowerCase().replace(/zona|sector/gi, '').trim() : '';

                if (rowZone === cleanTarget || rowName === cleanTarget ||
                    (cleanTarget && (rowZone.includes(cleanTarget) || cleanTarget.includes(rowZone) || rowName.includes(cleanTarget) || cleanTarget.includes(rowName)))) {
                    rows.push(row);
                }
            });
            return rows;
        },

        isZoneMatch: function(name1, name2) {
            if (!name1 || !name2) return false;
            const n1 = name1.toLowerCase().replace(/zona|sector/gi, '').trim();
            const n2 = name2.toLowerCase().replace(/zona|sector/gi, '').trim();
            return n1 === n2 || n1.includes(n2) || n2.includes(n1);
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
