<!-- ========================================================================= -->
<!-- MODAL DE GENERACIÓN DE BOLETOS EN PLANCHA DE IMPRENTA (2 COLUMNAS COMPACTO) -->
<!-- ========================================================================= -->

<div class="admin-modal-overlay" id="planchaTicketModal" style="display: none; position: fixed; inset: 0; z-index: 100000; background: rgba(10, 10, 16, 0.85); backdrop-filter: blur(12px); align-items: center; justify-content: center; padding: 1rem;">
    <div class="admin-modal-card" style="max-width: 960px; width: 95%; background: #14141E; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 22px; padding: 1.5rem 1.75rem; box-shadow: 0 25px 60px rgba(0,0,0,0.7); max-height: 92vh; overflow-y: auto; box-sizing: border-box; color: #FFFFFF;">
        
        <!-- Header Compacto -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; padding-bottom: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(37, 99, 235, 0.15); border: 1.5px solid rgba(37, 99, 235, 0.35); color: #3B82F6; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">🖨️</div>
                <div>
                    <h2 style="font-size: 1.2rem; font-weight: 900; margin: 0; color: #FFFFFF;">Generador de Planchas de Boletos</h2>
                    <p style="font-size: 0.78rem; color: #94A3B8; margin: 0.15rem 0 0 0;">Acomodo automático en pliegos de imprenta (24 boletos / pliego) con control de aforo y QR oficial.</p>
                </div>
            </div>
            <button type="button" onclick="closePlanchaModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; width: 32px; height: 32px; border-radius: 10px; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">✕</button>
        </div>

        <!-- Layout en 2 Columnas -->
        <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 1.25rem; align-items: start;">
            
            <!-- COLUMNA 1 (IZQUIERDA): Configuración y Aforo del Evento -->
            <div>
                <!-- Evento Fijo Predefinido -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; padding: 0.75rem 0.95rem; margin-bottom: 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Evento Seleccionado</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div style="font-weight: 900; font-size: 0.92rem; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" id="plancha_event_title_text">Cargando evento...</div>
                        <span style="background: rgba(37, 99, 235, 0.2); color: #60A5FA; border: 1px solid rgba(37, 99, 235, 0.4); font-size: 0.68rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 6px; flex-shrink: 0;">VENTA FÍSICA</span>
                    </div>
                    <div style="font-size: 0.74rem; color: #94A3B8; margin-top: 0.25rem;" id="plancha_event_meta_text">📅 Fecha y recinto...</div>
                </div>

                <!-- 1. ÚNICO SELECTOR INTERACTIVO: TAMAÑO DE PLANCHA -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #60A5FA; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.45rem;">
                        1. Tamaño de Plancha (Medida de Imprenta) <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                        <!-- Opción 65x85 cm -->
                        <div class="plancha-size-card" id="card_plancha_65x85" onclick="selectPlanchaSize('65x85')" style="border: 2px solid #2563EB; background: rgba(37, 99, 235, 0.14); border-radius: 12px; padding: 0.75rem 0.85rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.2rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 900; font-size: 0.95rem; color: #FFFFFF;">📐 65 x 85 cm</span>
                                <span id="badge_plancha_65x85" style="background: #2563EB; color: #FFFFFF; font-size: 0.62rem; font-weight: 900; padding: 0.1rem 0.4rem; border-radius: 4px;">ACTIVO</span>
                            </div>
                            <span style="font-size: 0.74rem; color: #93C5FD; font-weight: 700;">850 × 650 mm (Estándar Offset)</span>
                            <span style="font-size: 0.68rem; color: #94A3B8;">4 col × 6 filas = 24 boletos / pliego</span>
                        </div>

                        <!-- Opción 60x80 cm -->
                        <div class="plancha-size-card" id="card_plancha_60x80" onclick="selectPlanchaSize('60x80')" style="border: 1.5px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.03); border-radius: 12px; padding: 0.75rem 0.85rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.2rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 900; font-size: 0.95rem; color: #FFFFFF;">📐 60 x 80 cm</span>
                                <span id="badge_plancha_60x80" style="display: none; background: #2563EB; color: #FFFFFF; font-size: 0.62rem; font-weight: 900; padding: 0.1rem 0.4rem; border-radius: 4px;">ACTIVO</span>
                            </div>
                            <span style="font-size: 0.74rem; color: #93C5FD; font-weight: 700;">800 × 600 mm (Pliego Mediano)</span>
                            <span style="font-size: 0.68rem; color: #94A3B8;">4 col × 6 filas = 24 boletos / pliego</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Zonas y Aforos del Evento (Preseleccionadas en automático) -->
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">2. Aforos por Zona (Todas Preseleccionadas)</span>
                        <span id="plancha_zones_badge_count" style="font-size: 0.7rem; color: #10B981; font-weight: 800;">Calculando...</span>
                    </div>
                    
                    <!-- Contenedor lista de zonas con scroll compacto -->
                    <div id="plancha_zones_list_container" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 0.5rem; max-height: 180px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.4rem;">
                        <!-- Poblado dinámicamente -->
                        <div style="padding: 0.85rem; text-align: center; color: #94A3B8; font-size: 0.78rem;">Cargando zonas y aforos...</div>
                    </div>
                </div>
            </div>

            <!-- COLUMNA 2 (DERECHA): Detección de Aumento de Aforo, Resumen y Botón -->
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                
                <!-- ALERTA DINÁMICA DE DETECCIÓN DE AFORO / BOLETOS PENDIENTES -->
                <div id="plancha_status_alert_box" style="background: rgba(37, 99, 235, 0.1); border: 1.5px solid rgba(37, 99, 235, 0.3); border-radius: 14px; padding: 0.85rem 1rem;">
                    <div style="display: flex; align-items: flex-start; gap: 0.6rem;">
                        <span style="font-size: 1.3rem;" id="plancha_alert_icon">⚡</span>
                        <div style="font-size: 0.8rem; line-height: 1.35;" id="plancha_alert_content">
                            <strong style="color: #60A5FA; display: block; font-size: 0.85rem; margin-bottom: 0.2rem;">Analizando estado de aforos...</strong>
                            <span style="color: #CBD5E1;">Sincronizando boletos registrados y códigos QR con el sistema.</span>
                        </div>
                    </div>
                </div>

                <!-- SELECTOR DE LOTE (SI HAY BOLETOS PREVIOS Y NUEVOS) -->
                <div id="plancha_batch_mode_wrapper" style="display: none; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 0.65rem 0.85rem;">
                    <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.4rem;">¿Qué deseas generar?</label>
                    <div style="display: flex; gap: 0.45rem;">
                        <button type="button" id="btnModeOnlyNew" onclick="setPlanchaPrintMode('ONLY_NEW')" style="flex: 1; background: #2563EB; color: #FFFFFF; border: none; font-size: 0.74rem; font-weight: 800; padding: 0.45rem 0.6rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                            ⚡ Solo Nuevos Restantes
                        </button>
                        <button type="button" id="btnModeReprintAll" onclick="setPlanchaPrintMode('ALL')" style="flex: 1; background: rgba(255,255,255,0.06); color: #94A3B8; border: 1px solid rgba(255,255,255,0.1); font-size: 0.74rem; font-weight: 800; padding: 0.45rem 0.6rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                            🔄 Reimprimir Todo
                        </button>
                    </div>
                </div>

                <!-- RESUMEN TÉCNICO DE PRODUCCIÓN (COMPACTO) -->
                <div style="background: rgba(15, 23, 42, 0.75); border: 1.5px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.85rem 1rem;">
                    <div style="font-size: 0.7rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; display: flex; justify-content: space-between;">
                        <span>Resumen de Impresión</span>
                        <span id="plancha_summary_mode_label" style="color: #60A5FA;">Lote oficial</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; font-size: 0.8rem;">
                        <div style="background: rgba(255,255,255,0.03); padding: 0.5rem 0.65rem; border-radius: 8px;">
                            <span style="font-size: 0.68rem; color: #94A3B8; display: block;">Total Boletos a Generar</span>
                            <strong id="plancha_summary_total_tickets" style="font-size: 1.15rem; color: #FFFFFF; font-weight: 900;">0</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); padding: 0.5rem 0.65rem; border-radius: 8px;">
                            <span style="font-size: 0.68rem; color: #94A3B8; display: block;">Planchas Estimadas</span>
                            <strong id="plancha_summary_sheets_count" style="font-size: 1.15rem; color: #60A5FA; font-weight: 900;">0 pliego(s)</strong>
                        </div>
                    </div>

                    <div style="margin-top: 0.6rem; padding-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.75rem; color: #94A3B8; display: flex; flex-direction: column; gap: 0.25rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Rango correlativo:</span>
                            <strong id="plancha_summary_correlative_range" style="color: #F59E0B; font-family: monospace; font-size: 0.8rem;">N° 00001 → N° 00000</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Medida del PDF:</span>
                            <strong id="plancha_summary_dimensions" style="color: #FFFFFF;">65 × 85 cm (850 × 650 mm)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Códigos QR & Scanner:</span>
                            <strong style="color: #10B981;">✓ 100% Sincronizados y Válidos</strong>
                        </div>
                    </div>
                </div>

                <!-- BARRA DE PROGRESO DE RENDERIZADO -->
                <div id="plancha_render_progress_box" style="display: none; background: rgba(37, 99, 235, 0.15); border: 1px solid rgba(37, 99, 235, 0.4); border-radius: 12px; padding: 0.75rem 0.85rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 800; color: #93C5FD; margin-bottom: 0.35rem;">
                        <span id="plancha_progress_step_text">Compilando boletos...</span>
                        <span id="plancha_progress_percent">0%</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                        <div id="plancha_progress_bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #2563EB, #60A5FA); transition: width 0.15s ease;"></div>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN (COMPACTOS Y PROPORCIONADOS) -->
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 0.65rem; margin-top: 0.2rem;">
                    <button type="button" id="btnExecutePlanchaGeneration" onclick="startPlanchaPdfGeneration()" style="background: linear-gradient(135deg, #2563EB, #1D4ED8); color: #FFFFFF; border: none; padding: 0.8rem 1.25rem; border-radius: 12px; font-size: 0.92rem; font-weight: 900; box-shadow: 0 4px 18px rgba(37, 99, 235, 0.4); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease;">
                        <span style="font-size: 1.1rem;">🖨️</span>
                        <span id="btnPlanchaText">GENERAR PLANCHA PDF</span>
                    </button>
                    <button type="button" onclick="closePlanchaModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; padding: 0.8rem 1.1rem; border-radius: 12px; font-weight: 800; font-size: 0.85rem; cursor: pointer;">
                        Cerrar
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    let activePlanchaEvent = null;
    let selectedPlanchaSizeKey = '65x85'; // '65x85' o '60x80'
    let planchaExistingTickets = [];
    let planchaZoneBreakdown = [];
    let planchaPrintMode = 'ONLY_NEW'; // 'ONLY_NEW', 'ALL', 'REPRINT'
    let planchaIsGenerating = false;

    /**
     * Abre el modal de planchas y consulta el estado de boletos y aforos del evento
     */
    async function openPlanchaModal(eventData) {
        if (!eventData) return;
        activePlanchaEvent = eventData;
        planchaPrintMode = 'ONLY_NEW';

        // 1. Título y metadatos del evento
        const titleEl = document.getElementById('plancha_event_title_text');
        const metaEl = document.getElementById('plancha_event_meta_text');
        if (titleEl) {
            titleEl.textContent = activePlanchaEvent.title || 'Evento Oficial';
        }
        if (metaEl) {
            const dateStr = activePlanchaEvent.event_date ? (typeof activePlanchaEvent.event_date === 'string' ? activePlanchaEvent.event_date.substring(0, 10) : 'Fecha oficial') : 'Fecha por confirmar';
            const venueStr = activePlanchaEvent.venue_name || activePlanchaEvent.address || 'Recinto Oficial';
            const timeStr = activePlanchaEvent.event_time ? (' • ' + activePlanchaEvent.event_time) : '';
            metaEl.textContent = `📅 ${dateStr}${timeStr} • 📍 ${venueStr}`;
        }

        // 2. Resetear selectores visuales
        selectPlanchaSize('65x85');

        // 3. Mostrar modal de inmediato con animación suave
        const modal = document.getElementById('planchaTicketModal');
        if (modal) {
            modal.style.display = 'flex';
            requestAnimationFrame(() => {
                modal.classList.add('active');
            });
        }

        // 4. Mostrar estado de carga en zonas
        const zonesContainer = document.getElementById('plancha_zones_list_container');
        if (zonesContainer) {
            zonesContainer.innerHTML = '<div style="padding: 1rem; text-align: center; color: #94A3B8; font-size: 0.8rem;">🔄 Sincronizando aforos y boletos de la base de datos...</div>';
        }

        // 5. Consultar boletos ya registrados en BD para este evento
        try {
            planchaExistingTickets = [];
            if (activePlanchaEvent.id) {
                const res = await fetch(`/admin/eventos/${activePlanchaEvent.id}/boletos-registrados`);
                const data = await res.json();
                if (data && data.success && Array.isArray(data.tickets)) {
                    planchaExistingTickets = data.tickets;
                }
            }
        } catch (e) {
            console.warn('[Plancha] Error cargando boletos registrados:', e);
            planchaExistingTickets = [];
        }

        // 6. Procesar y calcular aforos de zonas vs boletos ya existentes
        computePlanchaAforoAndZones();
    }

    function closePlanchaModal() {
        if (planchaIsGenerating) return;
        const modal = document.getElementById('planchaTicketModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 200);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('planchaTicketModal');
        if (modalEl) {
            modalEl.addEventListener('click', function(e) {
                if (e.target === this && !planchaIsGenerating) {
                    closePlanchaModal();
                }
            });
        }
    });

    /**
     * Selección de medida de plancha (65x85 o 60x80)
     */
    function selectPlanchaSize(sizeKey) {
        selectedPlanchaSizeKey = sizeKey;
        const card65 = document.getElementById('card_plancha_65x85');
        const card60 = document.getElementById('card_plancha_60x80');
        const badge65 = document.getElementById('badge_plancha_65x85');
        const badge60 = document.getElementById('badge_plancha_60x80');

        if (sizeKey === '65x85') {
            if (card65) { card65.style.border = '2px solid #2563EB'; card65.style.background = 'rgba(37, 99, 235, 0.14)'; }
            if (card60) { card60.style.border = '1.5px solid rgba(255,255,255,0.12)'; card60.style.background = 'rgba(255,255,255,0.03)'; }
            if (badge65) badge65.style.display = 'inline-block';
            if (badge60) badge60.style.display = 'none';
        } else {
            if (card60) { card60.style.border = '2px solid #2563EB'; card60.style.background = 'rgba(37, 99, 235, 0.14)'; }
            if (card65) { card65.style.border = '1.5px solid rgba(255,255,255,0.12)'; card65.style.background = 'rgba(255,255,255,0.03)'; }
            if (badge60) badge60.style.display = 'inline-block';
            if (badge65) badge65.style.display = 'none';
        }

        updatePlanchaSummary();
    }

    function cleanZoneBase(name) {
        if (!name) return 'GENERAL';
        return String(name).replace(/\s*\([^)]*\)$/, '').trim().toUpperCase();
    }

    function formatShortSeatCodeJs(seat) {
        if (!seat) return '';
        if (typeof seat === 'object') {
            if (seat.row && (seat.number !== undefined || seat.col !== undefined)) {
                return String(seat.row).toUpperCase().trim() + String(seat.number !== undefined ? seat.number : seat.col).trim();
            }
            seat = seat.label || seat.number || seat.code || '';
        }
        seat = String(seat).trim();
        if (!seat) return '';

        let m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*-\s*(?:Asiento|Columna)\s*([0-9]+)/i);
        if (m) return m[1].toUpperCase() + m[2];

        m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*(?:Asiento|Columna)\s*([0-9]+)/i);
        if (m) return m[1].toUpperCase() + m[2];

        m = seat.match(/^([A-Za-z]+)[-\s]+([0-9]+)$/);
        if (m) return m[1].toUpperCase() + m[2];

        m = seat.match(/([A-Za-z]+[0-9]+)$/);
        if (m) return m[1].toUpperCase();

        return seat;
    }

    function formatZoneWithSeatJs(zoneName, seat) {
        let clean = (zoneName || '').trim();
        let shortSeat = formatShortSeatCodeJs(seat);
        if (!shortSeat) return clean;
        if (clean.includes(`(${shortSeat})`)) return clean;
        let base = clean.replace(/\s*\([^)]*\)$/, '').trim();
        return `${base} (${shortSeat})`;
    }

    function getSeatCodeForIndex(zSeats, index, zb = null) {
        if (Array.isArray(zSeats) && zSeats[index]) {
            const s = zSeats[index];
            if (typeof s === 'string') return formatShortSeatCodeJs(s);
            if (s.label) return formatShortSeatCodeJs(s.label);
            if (s.row && (s.number !== undefined || s.col !== undefined)) {
                return `${s.row}${s.number !== undefined ? s.number : s.col}`;
            }
            if (s.number) return `${s.number}`;
        }

        // Determinar la cantidad real de asientos por fila (ej: 10)
        let colsPerRow = 10;
        if (zb && (zb.seat_cols || zb.cols)) {
            colsPerRow = parseInt(zb.seat_cols || zb.cols, 10);
        } else if (Array.isArray(zSeats) && zSeats.length > 0) {
            const colsSet = new Set(zSeats.map(s => s.col || (typeof s === 'object' ? s.number : null)).filter(Boolean));
            if (colsSet.size > 0) {
                colsPerRow = colsSet.size;
            } else {
                let maxNum = 0;
                zSeats.forEach(s => {
                    const txt = typeof s === 'string' ? s : (s.label || s.number || '');
                    const m = String(txt).match(/(\d+)$/);
                    if (m) maxNum = Math.max(maxNum, parseInt(m[1], 10));
                });
                if (maxNum > 0) colsPerRow = maxNum;
            }
        }

        colsPerRow = Math.max(1, colsPerRow);

        // Generador sintético de numeración de butacas usando la cantidad real de asientos por fila (A1..A10, B1..B10, C1..C10, D1..D10, etc.)
        const rowLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const rowIdx = Math.floor(index / colsPerRow);
        const colNum = (index % colsPerRow) + 1;
        const rowLetter = rowLetters[rowIdx % rowLetters.length];
        return `${rowLetter}${colNum}`;
    }

    /**
     * Calcula la relación entre aforo configurado en el evento vs boletos ya existentes en BD
     */
    function computePlanchaAforoAndZones() {
        if (!activePlanchaEvent) return;

        const evt = activePlanchaEvent;
        let rawZones = (evt.raw_zones && Array.isArray(evt.raw_zones) && evt.raw_zones.length > 0)
            ? evt.raw_zones
            : (evt.zones || []);

        if (typeof rawZones === 'string') {
            try { rawZones = JSON.parse(rawZones); } catch(e) { rawZones = []; }
        }
        const zones = (rawZones && Array.isArray(rawZones) && rawZones.length > 0)
            ? rawZones
            : [
                { name: 'ZONA GENERAL', price: 50.00, capacity: 100 },
                { name: 'ZONA VIP', price: 90.00, capacity: 50 }
            ];

        // Mapear boletos ya existentes por zona usando base limpia
        const existingByZone = {};
        planchaExistingTickets.forEach(t => {
            const zKey = cleanZoneBase(t.zoneName || t.zone_name);
            if (!existingByZone[zKey]) existingByZone[zKey] = [];
            existingByZone[zKey].push(t);
        });

        planchaZoneBreakdown = [];
        let totalConfigCapacity = 0;
        let totalAlreadyGenerated = 0;
        let totalPendingNew = 0;

        zones.forEach(z => {
            const zName = z.name || 'General';
            const zKey = cleanZoneBase(zName);
            const zPrice = parseFloat(z.price || 0);

            // Buscar butacas en z.seats o en raw_zones
            let zSeats = [];
            if (Array.isArray(z.seats) && z.seats.length > 0) {
                zSeats = z.seats;
            } else if (evt.raw_zones && Array.isArray(evt.raw_zones)) {
                const rz = evt.raw_zones.find(item => cleanZoneBase(item.name) === zKey);
                if (rz && Array.isArray(rz.seats)) zSeats = rz.seats;
            }

            const isNumbered = (zSeats && zSeats.length > 0) ||
                (z.capacity_type && /butaca|asiento|numerad/i.test(z.capacity_type)) ||
                /butaca|asiento|numerad/i.test(zName);

            const zCap = parseInt(z.capacity || z.stock || z.available || (zSeats.length > 0 ? zSeats.length : 24), 10) || (zSeats.length > 0 ? zSeats.length : 24);
            
            const existingList = existingByZone[zKey] || [];
            const alreadyGenCount = existingList.length;
            const pendingCount = Math.max(0, zCap - alreadyGenCount);

            totalConfigCapacity += zCap;
            totalAlreadyGenerated += alreadyGenCount;
            totalPendingNew += pendingCount;

            planchaZoneBreakdown.push({
                name: zName,
                price: zPrice,
                capacity: zCap,
                seats: zSeats,
                isNumberedZone: isNumbered,
                alreadyGenerated: alreadyGenCount,
                pendingNew: pendingCount,
                existingTickets: existingList
            });
        });

        // 1. Renderizar lista visual de zonas en Columna 1
        const zonesContainer = document.getElementById('plancha_zones_list_container');
        const badgeCount = document.getElementById('plancha_zones_badge_count');
        if (badgeCount) {
            badgeCount.textContent = `${planchaZoneBreakdown.length} zonas (${totalConfigCapacity} aforo total)`;
        }

        if (zonesContainer) {
            let html = '';
            planchaZoneBreakdown.forEach(zb => {
                let statusBadge = '';
                if (zb.alreadyGenerated === 0) {
                    statusBadge = `<span style="background: rgba(37,99,235,0.15); color: #93C5FD; border: 1px solid rgba(37,99,235,0.3); font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 6px;">${zb.capacity} a generar</span>`;
                } else if (zb.pendingNew > 0) {
                    statusBadge = `<span style="background: rgba(16,185,129,0.15); color: #34D399; border: 1px solid rgba(16,185,129,0.3); font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 6px;">+${zb.pendingNew} NUEVOS (${zb.alreadyGenerated} impresos)</span>`;
                } else {
                    statusBadge = `<span style="background: rgba(255,255,255,0.06); color: #94A3B8; border: 1px solid rgba(255,255,255,0.1); font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 6px;">✓ ${zb.alreadyGenerated} impresos</span>`;
                }

                let seatChip = zb.isNumberedZone
                    ? `<span style="background: rgba(245,158,11,0.15); color: #F59E0B; border: 1px solid rgba(245,158,11,0.3); font-size: 0.63rem; font-weight: 800; padding: 0.12rem 0.4rem; border-radius: 6px; margin-left: 0.35rem;">🪑 ${zb.seats.length > 0 ? zb.seats.length : zb.capacity} Butacas</span>`
                    : '';

                html += `
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; padding: 0.55rem 0.75rem; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 800; font-size: 0.83rem; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: flex; align-items: center;">
                                <span>🎟️ ${zb.name}</span>
                                ${seatChip}
                            </div>
                            <div style="font-size: 0.7rem; color: #94A3B8; margin-top: 0.1rem;">
                                Precio: <strong style="color: #10B981;">S/ ${zb.price.toFixed(2)}</strong> • Aforo: <strong style="color: #FFFFFF;">${zb.capacity}</strong>
                            </div>
                        </div>
                        <div style="flex-shrink: 0;">
                            ${statusBadge}
                        </div>
                    </div>
                `;
            });
            zonesContainer.innerHTML = html;
        }

        // 2. Configurar Alerta y Modo de Impresión en Columna 2
        const alertBox = document.getElementById('plancha_status_alert_box');
        const alertIcon = document.getElementById('plancha_alert_icon');
        const alertContent = document.getElementById('plancha_alert_content');
        const batchModeWrapper = document.getElementById('plancha_batch_mode_wrapper');

        if (totalAlreadyGenerated > 0 && totalPendingNew > 0) {
            // CASO A: Aumento de aforo detectado (hay anteriores y hay nuevos)
            planchaPrintMode = 'ONLY_NEW';
            if (batchModeWrapper) batchModeWrapper.style.display = 'block';
            if (alertBox) {
                alertBox.style.background = 'rgba(16, 185, 129, 0.12)';
                alertBox.style.border = '1.5px solid rgba(16, 185, 129, 0.4)';
            }
            if (alertIcon) alertIcon.textContent = '⚡';
            if (alertContent) {
                alertContent.innerHTML = `
                    <strong style="color: #34D399; display: block; font-size: 0.88rem; margin-bottom: 0.2rem;">¡Aumento de aforo detectado!</strong>
                    <span style="color: #CBD5E1;">Se detectaron <b>+${totalPendingNew} boletos nuevos</b> pendientes por imprimir. Se generarán únicamente estos restantes con nuevos códigos QR.</span>
                    <div style="margin-top: 0.35rem; font-size: 0.73rem; color: #94A3B8;">✓ Los ${totalAlreadyGenerated} boletos anteriores conservan sus códigos QR y numeración original intacta.</div>
                `;
            }
        } else if (totalAlreadyGenerated > 0 && totalPendingNew === 0) {
            // CASO B: Todos los boletos ya fueron generados previamente
            planchaPrintMode = 'ALL';
            if (batchModeWrapper) batchModeWrapper.style.display = 'none';
            if (alertBox) {
                alertBox.style.background = 'rgba(255, 255, 255, 0.04)';
                alertBox.style.border = '1.5px solid rgba(255, 255, 255, 0.12)';
            }
            if (alertIcon) alertIcon.textContent = '✓';
            if (alertContent) {
                alertContent.innerHTML = `
                    <strong style="color: #10B981; display: block; font-size: 0.88rem; margin-bottom: 0.2rem;">Aforo Completo Registrado</strong>
                    <span style="color: #CBD5E1;">Todos los <b>${totalAlreadyGenerated} boletos</b> ya fueron registrados previamente con sus códigos QR.</span>
                    <div style="margin-top: 0.35rem; font-size: 0.73rem; color: #60A5FA;">Puedes descargar una reimpresión idéntica manteniendo exactamente los mismos códigos QR y correlativos.</div>
                `;
            }
        } else {
            // CASO C: Primera emisión oficial
            planchaPrintMode = 'ONLY_NEW';
            if (batchModeWrapper) batchModeWrapper.style.display = 'none';
            if (alertBox) {
                alertBox.style.background = 'rgba(37, 99, 235, 0.1)';
                alertBox.style.border = '1.5px solid rgba(37, 99, 235, 0.3)';
            }
            if (alertIcon) alertIcon.textContent = '✨';
            if (alertContent) {
                alertContent.innerHTML = `
                    <strong style="color: #60A5FA; display: block; font-size: 0.88rem; margin-bottom: 0.2rem;">Primera Emisión Oficial</strong>
                    <span style="color: #CBD5E1;">Se generarán correlativamente los <b>${totalConfigCapacity} boletos</b> del aforo total con sus códigos QR oficiales listos para scanner.</span>
                `;
            }
        }

        updatePlanchaSummary();
    }

    /**
     * Alterna entre modo "Solo Nuevos Restantes" o "Reimprimir Todo"
     */
    function setPlanchaPrintMode(mode) {
        planchaPrintMode = mode;
        const btnNew = document.getElementById('btnModeOnlyNew');
        const btnAll = document.getElementById('btnModeReprintAll');

        if (mode === 'ONLY_NEW') {
            if (btnNew) {
                btnNew.style.background = '#2563EB';
                btnNew.style.color = '#FFFFFF';
                btnNew.style.border = 'none';
            }
            if (btnAll) {
                btnAll.style.background = 'rgba(255,255,255,0.06)';
                btnAll.style.color = '#94A3B8';
                btnAll.style.border = '1px solid rgba(255,255,255,0.1)';
            }
        } else {
            if (btnAll) {
                btnAll.style.background = '#2563EB';
                btnAll.style.color = '#FFFFFF';
                btnAll.style.border = 'none';
            }
            if (btnNew) {
                btnNew.style.background = 'rgba(255,255,255,0.06)';
                btnNew.style.color = '#94A3B8';
                btnNew.style.border = '1px solid rgba(255,255,255,0.1)';
            }
        }

        updatePlanchaSummary();
    }

    /**
     * Actualiza el resumen técnico de boletos y pliegos en tiempo real
     */
    function updatePlanchaSummary() {
        const isPlancha65 = selectedPlanchaSizeKey === '65x85';
        const sheetWidthMm = isPlancha65 ? 850 : 800;
        const sheetHeightMm = isPlancha65 ? 650 : 600;
        const planchaSizeLabel = isPlancha65 ? '65 × 85 cm (850 × 650 mm)' : '60 × 80 cm (800 × 600 mm)';

        const dimEl = document.getElementById('plancha_summary_dimensions');
        if (dimEl) dimEl.textContent = planchaSizeLabel;

        // Calcular boletos que se imprimirán según planchaPrintMode
        let ticketsToPrintCount = 0;
        let startCorrelative = 1;
        let endCorrelative = 1;

        let maxExistingNum = 0;
        planchaExistingTickets.forEach(t => {
            const n = parseInt(t.ticketNumberVal || t.ticket_number, 10);
            if (n > maxExistingNum) maxExistingNum = n;
        });

        if (planchaPrintMode === 'ONLY_NEW') {
            const pendingTotal = planchaZoneBreakdown.reduce((sum, zb) => sum + zb.pendingNew, 0);
            ticketsToPrintCount = pendingTotal > 0 ? pendingTotal : planchaZoneBreakdown.reduce((sum, zb) => sum + zb.capacity, 0);
            startCorrelative = maxExistingNum + 1;
            endCorrelative = maxExistingNum + ticketsToPrintCount;
        } else {
            ticketsToPrintCount = planchaZoneBreakdown.reduce((sum, zb) => sum + Math.max(zb.capacity, zb.alreadyGenerated), 0);
            startCorrelative = 1;
            endCorrelative = ticketsToPrintCount;
        }

        const sheetsCount = Math.ceil(ticketsToPrintCount / 24) || 1;

        const totalEl = document.getElementById('plancha_summary_total_tickets');
        const sheetsEl = document.getElementById('plancha_summary_sheets_count');
        const rangeEl = document.getElementById('plancha_summary_correlative_range');
        const modeLabelEl = document.getElementById('plancha_summary_mode_label');
        const btnText = document.getElementById('btnPlanchaText');

        if (totalEl) totalEl.textContent = `${ticketsToPrintCount} boletos`;
        if (sheetsEl) sheetsEl.textContent = `${sheetsCount} pliego(s) (4×6)`;
        if (rangeEl) rangeEl.textContent = `N° ${String(startCorrelative).padStart(5, '0')} → N° ${String(endCorrelative).padStart(5, '0')}`;
        
        if (modeLabelEl) {
            modeLabelEl.textContent = (planchaPrintMode === 'ONLY_NEW' && maxExistingNum > 0)
                ? '⚡ Lote Restante (+Nuevos)'
                : 'Lote Completo Oficial';
        }

        if (btnText) {
            btnText.textContent = `GENERAR PLANCHA PDF (${ticketsToPrintCount} BOLETOS)`;
        }
    }

    /**
     * MOTOR PRINCIPAL DE GENERACIÓN DE PLANCHAS PDF (65x85cm y 60x80cm)
     */
    async function startPlanchaPdfGeneration() {
        if (!activePlanchaEvent) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se ha detectado información del evento.',
                background: '#14141E',
                color: '#FFFFFF'
            });
            return;
        }

        const isPlancha65 = selectedPlanchaSizeKey === '65x85';
        const sheetWidthMm = isPlancha65 ? 850 : 800;
        const sheetHeightMm = isPlancha65 ? 650 : 600;
        const planchaSizeLabel = isPlancha65 ? '65x85cm' : '60x80cm';

        // 1. Construir la lista exacta de boletos a incluir en la plancha
        // Respetando los QR ya existentes para que NUNCA muten
        let ticketsToPrint = [];
        let ticketsToRegisterDb = [];

        let maxExistingNum = 0;
        planchaExistingTickets.forEach(t => {
            const n = parseInt(t.ticketNumberVal || t.ticket_number, 10);
            if (n > maxExistingNum) maxExistingNum = n;
        });

        let nextCorrelative = maxExistingNum + 1;

        if (planchaPrintMode === 'ONLY_NEW') {
            // Caso: Solo generar boletos restantes / nuevos
            planchaZoneBreakdown.forEach(zb => {
                const countToGenerate = zb.alreadyGenerated > 0 ? zb.pendingNew : zb.capacity;

                // Detectar qué butacas ya fueron impresas si la zona tiene butacas
                const usedSeats = new Set();
                if (zb.isNumberedZone) {
                    zb.existingTickets.forEach(et => {
                        const etZone = et.zoneName || et.zone_name || '';
                        const m = etZone.match(/\(([^)]+)\)/);
                        if (m) usedSeats.add(m[1].trim().toUpperCase());
                        if (et.seat) usedSeats.add(String(et.seat).trim().toUpperCase());
                    });
                }

                let seatIndexCursor = 0;

                for (let k = 0; k < countToGenerate; k++) {
                    const currentNum = nextCorrelative++;
                    const ticketNumStr = 'N° ' + String(currentNum).padStart(5, '0');
                    const valHash = 'VG' + Math.random().toString(36).substring(2, 10).toUpperCase();
                    const qrPayload = `VIVEGO|EVT-${activePlanchaEvent.id}|TICK-${currentNum}|HASH-${valHash}`;

                    let seatCode = '';
                    if (zb.isNumberedZone) {
                        while (seatIndexCursor < 9999) {
                            const candidate = getSeatCodeForIndex(zb.seats, seatIndexCursor++);
                            if (!usedSeats.has(candidate.toUpperCase())) {
                                seatCode = candidate;
                                usedSeats.add(candidate.toUpperCase());
                                break;
                            }
                        }
                    }

                    const fullZoneName = seatCode ? `${zb.name} (${seatCode})` : zb.name;

                    const ticketObj = {
                        ticketNumberVal: currentNum,
                        ticketCode: ticketNumStr,
                        zoneName: fullZoneName,
                        seatCode: seatCode,
                        zonePrice: zb.price.toFixed(2),
                        validationHash: valHash,
                        qrPayload: qrPayload,
                        buyerName: 'TALONARIO FÍSICO TAQUILLA',
                        buyerDni: '00000000',
                        isNew: true
                    };
                    ticketsToPrint.push(ticketObj);
                    ticketsToRegisterDb.push(ticketObj);
                }
            });
        } else {
            // Caso: Reimprimir todo o lote completo
            planchaZoneBreakdown.forEach(zb => {
                const usedSeats = new Set();

                // 1. Primero agregar los existentes con sus QR originales y butacas intactos
                zb.existingTickets.forEach(et => {
                    const etZone = et.zoneName || et.zone_name || zb.name;
                    let seatCode = '';
                    const m = etZone.match(/\(([^)]+)\)/);
                    if (m) {
                        seatCode = m[1].trim();
                        usedSeats.add(seatCode.toUpperCase());
                    } else if (et.seat) {
                        seatCode = String(et.seat).trim();
                        usedSeats.add(seatCode.toUpperCase());
                    }

                    ticketsToPrint.push({
                        ticketNumberVal: et.ticketNumberVal || et.ticket_number,
                        ticketCode: et.ticketCode || et.ticket_code,
                        zoneName: etZone,
                        seatCode: seatCode,
                        zonePrice: zb.price.toFixed(2),
                        validationHash: et.validationHash || et.validation_hash,
                        qrPayload: et.qrPayload || et.qr_payload,
                        buyerName: et.buyerName || et.buyer_name || 'TALONARIO FÍSICO',
                        buyerDni: et.buyerDni || et.buyer_dni || '00000000',
                        isNew: false
                    });
                });

                // 2. Si hay nuevos pendientes además de los existentes, asignarles las butacas restantes
                let seatIndexCursor = 0;
                for (let k = 0; k < zb.pendingNew; k++) {
                    const currentNum = nextCorrelative++;
                    const ticketNumStr = 'N° ' + String(currentNum).padStart(5, '0');
                    const valHash = 'VG' + Math.random().toString(36).substring(2, 10).toUpperCase();
                    const qrPayload = `VIVEGO|EVT-${activePlanchaEvent.id}|TICK-${currentNum}|HASH-${valHash}`;

                    let seatCode = '';
                    if (zb.isNumberedZone) {
                        while (seatIndexCursor < 9999) {
                            const candidate = getSeatCodeForIndex(zb.seats, seatIndexCursor++, zb);
                            if (!usedSeats.has(candidate.toUpperCase())) {
                                seatCode = candidate;
                                usedSeats.add(candidate.toUpperCase());
                                break;
                            }
                        }
                    }

                    const fullZoneName = seatCode ? `${zb.name} (${seatCode})` : zb.name;

                    const ticketObj = {
                        ticketNumberVal: currentNum,
                        ticketCode: ticketNumStr,
                        zoneName: fullZoneName,
                        seatCode: seatCode,
                        zonePrice: zb.price.toFixed(2),
                        validationHash: valHash,
                        qrPayload: qrPayload,
                        buyerName: 'TALONARIO FÍSICO TAQUILLA',
                        buyerDni: '00000000',
                        isNew: true
                    };
                    ticketsToPrint.push(ticketObj);
                    ticketsToRegisterDb.push(ticketObj);
                }
            });
        }

        if (ticketsToPrint.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'No hay boletos pendientes',
                text: 'Todos los boletos de este aforo ya fueron generados. Puedes seleccionar "Reimprimir Todo" si deseas descargar nuevamente las planchas.',
                confirmButtonColor: '#2563EB',
                background: '#14141E',
                color: '#FFFFFF'
            });
            return;
        }

        const totalSheets = Math.ceil(ticketsToPrint.length / 24);

        // Confirmar con el usuario
        const confirmRes = await Swal.fire({
            title: `¿Generar Plancha ${planchaSizeLabel}?`,
            html: `Se crearán <b>${ticketsToPrint.length} boletos físicos</b> organizados en <b>${totalSheets} plancha(s) de ${planchaSizeLabel}</b> (24 boletos por hoja en cuadrícula de 4×6).<br><br><span style="color: #10B981; font-weight: 700;">✓ Los códigos QR se sincronizarán directamente con la app scanner para el control de acceso.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563EB',
            cancelButtonColor: '#475569',
            confirmButtonText: '🖨️ Sí, Generar y Descargar',
            cancelButtonText: 'Cancelar',
            background: '#14141E',
            color: '#FFFFFF'
        });

        if (!confirmRes.isConfirmed) return;

        planchaIsGenerating = true;
        const progressBox = document.getElementById('plancha_render_progress_box');
        const progressStepText = document.getElementById('plancha_progress_step_text');
        const progressBar = document.getElementById('plancha_progress_bar');
        const progressPercent = document.getElementById('plancha_progress_percent');

        if (progressBox) progressBox.style.display = 'block';

        Swal.fire({
            title: `🖨️ Compilando Plancha ${planchaSizeLabel}...`,
            html: `<div style="margin-top: 0.5rem;"><div style="font-weight: 800; font-size: 1.1rem; color: #60A5FA;" id="plancha_swal_progress">Iniciando motor gráfico...</div><div style="font-size: 0.82rem; color: #94A3B8; margin-top: 0.35rem;">Acomodando boletos en cuadrícula de imprenta de 4×6...</div></div>`,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); },
            background: '#14141E',
            color: '#FFFFFF'
        });

        try {
            const jsPdfObj = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF || null);
            if (!jsPdfObj) {
                throw new Error('La librería jsPDF no está cargada en la página.');
            }

            // Dimensiones de cada boleto en la plancha (proporción 771:370 ≈ 2.084)
            // 4 columnas x 6 filas = 24 boletos
            const ticketWidthMm = isPlancha65 ? 196 : 184;
            const ticketHeightMm = isPlancha65 ? 94 : 88.3;
            const gapX = 8;
            const gapY = 6;

            const totalGridW = 4 * ticketWidthMm + 3 * gapX;
            const totalGridH = 6 * ticketHeightMm + 5 * gapY;

            const marginX = (sheetWidthMm - totalGridW) / 2;
            const marginY = (sheetHeightMm - totalGridH) / 2;

            // Inicializar jsPDF con el tamaño exacto de la plancha (Landscape: [alto, ancho])
            const pdf = new jsPdfObj({
                orientation: 'landscape',
                unit: 'mm',
                format: [sheetHeightMm, sheetWidthMm],
                compress: true
            });

            // Pre-cargar imágenes y activos una sola vez
            const assetMap = {};
            const evt = activePlanchaEvent;
            const template = evt.template || {
                id: 1,
                name: 'Plantilla Oficial',
                bg_color: '#FFFFFF',
                elements: []
            };

            if (typeof preloadImageAsDataUrl === 'function') {
                if (evt.banner_image) {
                    assetMap.bannerDataUrl = await preloadImageAsDataUrl("{{ asset('') }}" + evt.banner_image.replace(/^\//, ''), 'banner', evt.title);
                }
                const bgSource = template.background || template.bg_image;
                if (bgSource) {
                    assetMap.bgDataUrl = await preloadImageAsDataUrl("{{ asset('') }}" + bgSource.replace(/^\//, ''), 'bg');
                }
            }

            const eventTitle = evt.title || 'Evento Oficial';
            const eventVenue = evt.venue_name || evt.address || 'Recinto Oficial';
            const eventAddress = evt.address || '';
            const eventDate = evt.event_date ? (typeof evt.event_date === 'string' ? evt.event_date.substring(0, 10) : 'Fecha oficial') : 'Próximamente';
            const eventTime = evt.event_time || '19:00';
            const bgColor = template.bg_color || '#14141E';

            // Contenedor temporal para renderizar el boleto individual
            const renderWrapper = document.createElement('div');
            renderWrapper.id = 'planchaSingleTicketRender';
            renderWrapper.style.position = 'fixed';
            renderWrapper.style.left = '-9999px';
            renderWrapper.style.top = '0';
            renderWrapper.style.width = '771px';
            renderWrapper.style.height = '370px';
            renderWrapper.style.zIndex = '9999999';
            renderWrapper.style.overflow = 'hidden';
            renderWrapper.style.borderRadius = '14px';
            renderWrapper.style.background = bgColor;
            renderWrapper.style.fontFamily = "'Plus Jakarta Sans', sans-serif";
            renderWrapper.style.boxSizing = 'border-box';
            document.body.appendChild(renderWrapper);

            // Renderizar y agregar cada boleto a la plancha
            for (let i = 0; i < ticketsToPrint.length; i++) {
                const tItem = ticketsToPrint[i];
                const currentSheetIdx = Math.floor(i / 24);
                const currentSlotIdx = i % 24;

                const col = currentSlotIdx % 4; // 0, 1, 2, 3
                const row = Math.floor(currentSlotIdx / 4); // 0, 1, 2, 3, 4, 5

                const posX = marginX + col * (ticketWidthMm + gapX);
                const posY = marginY + row * (ticketHeightMm + gapY);

                // Nueva hoja de plancha cuando se llena la cuadrícula de 24
                if (i > 0 && currentSlotIdx === 0) {
                    pdf.addPage([sheetHeightMm, sheetWidthMm], 'landscape');
                }

                // Si es el primer boleto de la hoja, dibujar encabezado técnico de la plancha
                if (currentSlotIdx === 0) {
                    pdf.setFontSize(8.5);
                    pdf.setTextColor(148, 163, 184);
                    pdf.text(`PLANCHA DE IMPRENTA OFICIAL VIVEGO • MEDIDA: ${planchaSizeLabel.toUpperCase()} (${sheetWidthMm}x${sheetHeightMm}mm) • EVENTO: ${eventTitle.toUpperCase()} • PLIEGO ${currentSheetIdx + 1} DE ${totalSheets} • BOLETOS N° ${String(ticketsToPrint[currentSheetIdx * 24].ticketNumberVal).padStart(5, '0')} AL N° ${String(ticketsToPrint[Math.min((currentSheetIdx + 1) * 24 - 1, ticketsToPrint.length - 1)].ticketNumberVal).padStart(5, '0')}`, marginX, 15);
                }

                // Actualizar barra de progreso
                const pct = Math.round(((i + 1) / ticketsToPrint.length) * 100);
                const swalText = document.getElementById('plancha_swal_progress');
                if (swalText) {
                    swalText.textContent = `Acomodando boleto ${i + 1} de ${ticketsToPrint.length} (${pct}%)...`;
                }
                if (progressStepText) progressStepText.textContent = `Boleto ${i + 1} de ${ticketsToPrint.length} (Pliego ${currentSheetIdx + 1}/${totalSheets})`;
                if (progressBar) progressBar.style.width = `${pct}%`;
                if (progressPercent) progressPercent.textContent = `${pct}%`;

                // Generar QR en base64
                let qrDataUrl = '';
                if (typeof generateQrBase64 === 'function') {
                    qrDataUrl = generateQrBase64(tItem.qrPayload);
                } else if (typeof qrcode !== 'undefined') {
                    const qr = qrcode(0, 'M');
                    qr.addData(tItem.qrPayload);
                    qr.make();
                    qrDataUrl = qr.createDataURL(4, 0);
                }

                const dynamicData = {
                    title: eventTitle,
                    venue: eventVenue,
                    city: eventAddress,
                    date: eventDate,
                    time: eventTime,
                    zone: tItem.zoneName,
                    seat: tItem.seatCode || '',
                    price: 'S/ ' + tItem.zonePrice,
                    buyer_name: '',
                    buyer_dni: '',
                    is_plancha_print: true,
                    ticket_number: tItem.ticketCode,
                    hash: tItem.validationHash,
                    qr_data_url: qrDataUrl
                };

                // Renderizar contenido del boleto idéntico al boleto virtual (sin comprador ni dni)
                let canvasHtml = '';
                if (typeof renderPlanchaTicketCanvasContent === 'function') {
                    canvasHtml = renderPlanchaTicketCanvasContent(template, dynamicData, assetMap);
                } else if (typeof renderTicketCanvasContent === 'function') {
                    canvasHtml = renderTicketCanvasContent(template, dynamicData, assetMap);
                } else {
                    canvasHtml = `
                        <div style="position: relative; width: 100%; height: 100%; padding: 1.25rem; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; background: #14141E; color: #FFFFFF; border-radius: 14px;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 900; margin: 0 0 4px 0;">${eventTitle}</h3>
                                <p style="font-size: 13px; color: #FF5500; font-weight: 800; margin: 0 0 6px 0;">ZONA: ${tItem.zoneName} • PRECIO: S/ ${tItem.zonePrice}</p>
                                <p style="font-size: 11px; opacity: 0.8; margin: 0;">${eventVenue} • ${eventDate} ${eventTime}</p>
                                <div style="margin-top: 10px; font-size: 13px; font-weight: 900; color: #F59E0B;">${tItem.ticketCode}</div>
                            </div>
                            <div style="width: 100px; height: 100px; background: #FFFFFF; padding: 4px; border-radius: 8px;">
                                <img src="${qrDataUrl}" style="width: 100%; height: 100%; object-fit: contain;" />
                            </div>
                        </div>
                    `;
                }

                renderWrapper.innerHTML = canvasHtml;

                // Capturar con html2canvas
                const canvas = await html2canvas(renderWrapper, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: bgColor,
                    logging: false
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);

                // Dibujar el boleto en la hoja PDF
                pdf.addImage(imgData, 'JPEG', posX, posY, ticketWidthMm, ticketHeightMm, undefined, 'FAST');

                // Marca de recorte con borde redondeado (radio 3.5mm)
                pdf.setDrawColor(180, 190, 205);
                pdf.setLineWidth(0.25);
                pdf.roundedRect(posX, posY, ticketWidthMm, ticketHeightMm, 3.5, 3.5, 'S');
            }

            // Remover el contenedor temporal
            if (renderWrapper && renderWrapper.parentNode) {
                renderWrapper.parentNode.removeChild(renderWrapper);
            }

            // Registrar boletos NUEVOS en la base de datos para habilitar el escaneo en puertas
            if (activePlanchaEvent.id && ticketsToRegisterDb.length > 0) {
                const swalText = document.getElementById('plancha_swal_progress');
                if (swalText) {
                    swalText.textContent = `Registrando ${ticketsToRegisterDb.length} boletos en la base de datos para el escáner...`;
                }

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || (typeof csrfToken !== 'undefined' ? csrfToken : '');
                    await fetch(`/admin/eventos/${activePlanchaEvent.id}/registrar-boletos-pdf`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            tickets: ticketsToRegisterDb.map(t => ({
                                ticket_code: t.ticketCode,
                                ticket_number: t.ticketNumberVal,
                                zone_name: t.zoneName,
                                unit_price: parseFloat(t.zonePrice) || 0.00,
                                validation_hash: t.validationHash,
                                qr_payload: t.qrPayload,
                                buyer_name: t.buyerName,
                                buyer_dni: t.buyerDni
                            }))
                        })
                    });
                } catch (dbErr) {
                    console.warn('[Plancha] Advertencia registrando en BD:', dbErr);
                }
            }

            // Descargar el archivo PDF con la medida exacta de la plancha
            const safeEventName = (eventTitle || 'Evento').replace(/[^a-zA-Z0-9_\-]/g, '_');
            const fileName = `Plancha_${planchaSizeLabel}_${safeEventName}_Total_${ticketsToPrint.length}.pdf`;
            pdf.save(fileName);

            planchaIsGenerating = false;
            closePlanchaModal();

            Swal.fire({
                icon: 'success',
                title: '🎉 ¡Plancha Generada con Éxito!',
                html: `Se descargó el archivo en formato <b>${planchaSizeLabel}</b> con <b>${ticketsToPrint.length} boletos</b> en ${totalSheets} pliego(s).<br><br><span style="color: #10B981; font-weight: 700;">✓ Boletos listos para imprenta y sincronizados para escaneo en puerta.</span>`,
                confirmButtonColor: '#2563EB',
                confirmButtonText: 'Excelente',
                background: '#14141E',
                color: '#FFFFFF'
            });

        } catch (err) {
            console.error('Error generando plancha:', err);
            planchaIsGenerating = false;
            if (progressBox) progressBox.style.display = 'none';

            Swal.fire({
                icon: 'error',
                title: 'Error al Generar Plancha',
                text: err.message || 'Ocurrió un error en la compilación gráfica de la plancha.',
                confirmButtonColor: '#EF4444',
                background: '#14141E',
                color: '#FFFFFF'
            });
        }
    }

    // =========================================================================
    // FUNCIONES AUXILIARES DE RENDERIZADO (AUTOCONTENIDAS)
    // =========================================================================
    if (typeof window.generateQrBase64 !== 'function') {
        window.generateQrBase64 = function(payload) {
            if (typeof qrcode !== 'undefined') {
                const qr = qrcode(0, 'M');
                qr.addData(payload);
                qr.make();
                return qr.createDataURL(4, 0);
            }
            return '';
        };
    }

    if (typeof window.getRealFontFamily !== 'function') {
        window.getRealFontFamily = function(fontName) {
            if (!fontName) return 'Plus Jakarta Sans';
            const fontMap = {
                'font-lato': 'Lato',
                'font-montserrat': 'Montserrat',
                'font-opensans': 'Open Sans',
                'font-roboto': 'Roboto',
                'font-inter': 'Inter',
                'font-poppins': 'Poppins',
                'font-outfit': 'Outfit',
                'font-raleway': 'Raleway',
                'font-nunito': 'Nunito',
                'font-rubik': 'Rubik',
                'font-work-sans': 'Work Sans',
                'font-oswald': 'Oswald',
                'font-bebas': 'Bebas Neue',
                'font-anton': 'Anton',
                'font-syne': 'Syne',
                'font-space-grotesk': 'Space Grotesk',
                'font-righteous': 'Righteous',
                'font-monoton': 'Monoton',
                'font-merriweather': 'Merriweather',
                'font-playfair': 'Playfair Display',
                'font-cinzel': 'Cinzel',
                'font-abril': 'Abril Fatface',
                'font-dancing': 'Dancing Script',
                'font-greatvibes': 'Great Vibes',
                'font-pacifico': 'Pacifico',
                'font-satisfy': 'Satisfy',
                'font-caveat': 'Caveat',
                'font-lobster': 'Lobster',
                'font-comfortaa': 'Comfortaa'
            };
            if (fontMap[fontName]) return fontMap[fontName];
            if (typeof fontName === 'string' && fontName.startsWith('font-')) {
                const clean = fontName.replace('font-', '');
                return clean.charAt(0).toUpperCase() + clean.slice(1);
            }
            return fontName;
        };
    }

    if (typeof window.replaceDynamicValueInHtml !== 'function') {
        window.replaceDynamicValueInHtml = function(html, labelKeyword, newValue) {
            if (!html || typeof html !== 'string') return html;
            const cleanLabel = labelKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const multiPRegex = new RegExp(`(<p[^>]*>\\s*${cleanLabel}:?\\s*<\\/p>\\s*<p[^>]*>)(.*?)(<\\/p>)`, 'gi');
            if (multiPRegex.test(html)) return html.replace(multiPRegex, `$1${newValue}$3`);
            const singleRegex = new RegExp(`(${cleanLabel}:?\\s*)((?:<[^>]+>\\s*)*)([^<\\s]+[^<]*)`, 'gi');
            if (singleRegex.test(html)) return html.replace(singleRegex, `$1$2${newValue}`);
            return html;
        };
    }

    if (typeof window.convertPositionsToElements !== 'function') {
        window.convertPositionsToElements = function(positions) {
            if (!positions || typeof positions !== 'object') return [];
            const elements = [];
            const fieldMap = {
                canvaElLogo: { field: 'logo', type: 'image' },
                canvaElBanner: { field: 'banner', type: 'image' },
                canvaElTitle: { field: 'title', type: 'text' },
                canvaElZone: { field: 'zone', type: 'text' },
                canvaElPrice: { field: 'price', type: 'text' },
                canvaElVenue: { field: 'venue', type: 'text' },
                canvaElCity: { field: 'city', type: 'text' },
                canvaElDate: { field: 'date', type: 'text' },
                canvaElTime: { field: 'time', type: 'text' },
                canvaElBuyerName: { field: 'buyer_name', type: 'text' },
                canvaElBuyer: { field: 'buyer_name', type: 'text' },
                canvaElBuyerDni: { field: 'buyer_dni', type: 'text' },
                canvaElTicketNumber: { field: 'ticket_number', type: 'text' },
                canvaElQR: { field: 'qr', type: 'qr' },
                canvaElHash: { field: 'hash', type: 'text' },
                canvaElDisclaimer: { field: 'disclaimer', type: 'disclaimer' },
            };

            Object.keys(positions).forEach((id) => {
                const p = positions[id];
                if (!p || p.hidden === true || p.display === 'none' || p.visible === false) return;
                const mapped = fieldMap[id] || { field: 'custom', type: 'text' };
                const topVal = parseFloat(p.top) || 0;
                const leftVal = parseFloat(p.left) || 0;
                const widthVal = parseFloat(p.width) || 120;
                const heightVal = parseFloat(p.height) || 40;
                const defaultAlignMap = {
                    canvaElPrice: 'right',
                    canvaElVenue: 'right',
                    canvaElTicketNumber: 'left',
                    canvaElHash: 'left',
                    canvaElDisclaimer: 'center',
                };
                let resolvedTextAlign = p.textAlign || '';
                if (!resolvedTextAlign && p.html) {
                    const taMatch = p.html.match(/text-align\s*:\s*(left|center|right|justify)/i);
                    if (taMatch) resolvedTextAlign = taMatch[1];
                }
                if (!resolvedTextAlign) {
                    resolvedTextAlign = defaultAlignMap[id] || 'left';
                }

                elements.push({
                    id: id,
                    field: mapped.field,
                    type: mapped.type,
                    content: p.html || p.text || '',
                    src: p.src || '',
                    x: leftVal,
                    y: topVal,
                    width: widthVal,
                    height: heightVal,
                    rotation: parseFloat(p.rotate) || 0,
                    fit: 'cover',
                    style: {
                        fontFamily: p.fontFamily || 'Plus Jakarta Sans',
                        fontSize: parseFloat(p.fontSize) || 14,
                        color: p.color || '#FFFFFF',
                        fontWeight: p.fontWeight || 'bold',
                        fontStyle: p.fontStyle || 'normal',
                        textAlign: resolvedTextAlign,
                        letterSpacing: 0,
                        lineHeight: 1.2,
                        background: p.backgroundColor || 'transparent'
                    }
                });
            });
            return elements;
        };
    }

    window.renderPlanchaTicketCanvasContent = function(template, dynamicData, assetMap = {}) {
            let elements = [];
            if (template && Array.isArray(template.elements) && template.elements.length > 0) {
                elements = template.elements;
            } else if (template && template.positions) {
                let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
                elements = convertPositionsToElements(rawPos);
            }

            const bgUrl = assetMap.bgDataUrl || (template ? (template.background || template.bg_image) : null);
            let bgHtml = '';
            if (bgUrl) {
                bgHtml = `<div style="position: absolute; inset: 0; background-image: url('${bgUrl}'); background-size: cover; background-position: center; z-index: 0; pointer-events: none;"></div>`;
            }

            let elementsHtml = '';

            elements.forEach((el, idx) => {
                if (!el || el.hidden === true || el.display === 'none' || el.visible === false) return;

                const type = el.type || 'text';
                const field = el.field || 'custom';

                // En la plancha física se obvian y no se colocan los campos de comprador y DNI
                if (dynamicData.is_plancha_print) {
                    if (type === 'buyer_name' || type === 'buyer_dni' || type === 'buyer' ||
                        field === 'buyer_name' || field === 'buyer_dni' || field === 'buyer' ||
                        el.id === 'canvaElBuyer' || el.id === 'canvaElBuyerName' || el.id === 'canvaElBuyerDni' ||
                        (el.id && String(el.id).toLowerCase().includes('buyer')) ||
                        /Comprador/i.test(el.content || el.html || el.text || '') ||
                        /DNI/i.test(el.content || el.html || el.text || '')) {
                        return; // Omitir completamente en la plancha física
                    }
                }

                const x = parseFloat(el.x) || 0;
                const y = parseFloat(el.y) || 0;
                const w = el.style?.width ? (typeof el.style.width === 'number' ? el.style.width + 'px' : el.style.width) : (el.width ? (typeof el.width === 'number' ? el.width + 'px' : el.width) : 'auto');
                const h = el.style?.height ? (typeof el.style.height === 'number' ? el.style.height + 'px' : el.style.height) : (el.height ? (typeof el.height === 'number' ? el.height + 'px' : el.height) : 'auto');
                const rotation = parseFloat(el.style?.rotation || el.rotation || el.rotate) || 0;
                const transform = rotation ? `transform: rotate(${rotation}deg); transform-origin: center center;` : '';

                const style = el.style || {};
                const rawFontName = style.fontFamily || el.fontFamily || 'Plus Jakarta Sans';
                const realFontName = getRealFontFamily(rawFontName);
                const font = realFontName.includes(',') ? `font-family: ${realFontName};` : `font-family: '${realFontName}', sans-serif;`;
                const fontSize = style.fontSize ? (typeof style.fontSize === 'number' ? `font-size: ${style.fontSize}px;` : `font-size: ${style.fontSize};`) : 'font-size: 14px;';
                const color = style.color ? `color: ${style.color};` : 'color: #FFFFFF;';
                const weight = style.fontWeight ? `font-weight: ${style.fontWeight};` : 'font-weight: bold;';
                const fontStyle = style.fontStyle ? `font-style: ${style.fontStyle};` : 'font-style: normal;';
                let textAlign = style.textAlign || el.textAlign || el.align || 'left';
                const letterSpacing = style.letterSpacing ? `letter-spacing: ${style.letterSpacing}px;` : '';
                const lineHeight = style.lineHeight ? `line-height: ${style.lineHeight};` : 'line-height: 1.2;';
                const bgStyle = style.background && style.background !== 'transparent' ? `background-color: ${style.background}; border-radius: ${style.borderRadius || '8px'}; padding: ${style.padding || '2px 6px'};` : '';

                let innerContent = '';

                if (type === 'qr' || field === 'qr' || el.id === 'canvaElQR') {
                    const qrSrc = dynamicData.qr_data_url || el.src;
                    innerContent = `<div style="padding: 0.35rem; background: #FFFFFF; border-radius: 12px; border: 1.5px solid #E2E8F0; width: 100%; height: 100%; box-sizing: border-box; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><img src="${qrSrc}" style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px;" alt="QR Code" /></div>`;
                } else if (type === 'image' || type === 'logo' || type === 'banner' || field === 'logo' || field === 'banner' || field === 'image' || el.id === 'canvaElLogo' || el.id === 'canvaElBanner') {
                    let imgSrc = el.src;
                    if ((field === 'banner' || type === 'banner' || el.id === 'canvaElBanner') && (!imgSrc || imgSrc === '')) {
                        imgSrc = assetMap.bannerDataUrl;
                    }
                    if ((field === 'logo' || type === 'logo' || el.id === 'canvaElLogo') && (!imgSrc || imgSrc === '')) {
                        imgSrc = assetMap.logoDataUrl;
                    }
                    const fitMode = style.objectFit || el.fit || (type === 'banner' || field === 'banner' ? 'cover' : 'contain');
                    if (imgSrc) {
                        innerContent = `<img src="${imgSrc}" style="width: 100%; height: 100%; display: block; object-fit: ${fitMode}; ${field === 'logo' || type === 'logo' ? 'filter: drop-shadow(0 0 8px rgba(255,85,0,0.6));' : ''}" />`;
                    }
                } else {
                    let rawTxt = el.content || el.html || el.text || '';
                    if (typeof rawTxt === 'string') {
                        rawTxt = rawTxt.replace(/<span class="ql-cursor">.*?<\/span>/gi, '').replace(/\uFEFF/g, '');
                    }

                    // En la plancha física, evitar cualquier texto residual de comprador o DNI
                    if (dynamicData.is_plancha_print && (/Comprador/i.test(rawTxt) || /DNI/i.test(rawTxt))) {
                        return;
                    }

                    if (field === 'title' || el.id === 'canvaElTitle') {
                        if (dynamicData.title) {
                            rawTxt = (rawTxt && (rawTxt.includes('<') || rawTxt.includes('>')))
                                ? rawTxt.replace(/(<h[1-6][^>]*>|<p[^>]*>|<span[^>]*>)(.*?)(<\/h[1-6]>|<\/p>|<\/span>|$)/gi, (m, p1, p2, p3) => p1 + dynamicData.title + p3)
                                : dynamicData.title;
                        }
                    } else if (field === 'zone' || el.id === 'canvaElZone' || /ZONA/i.test(rawTxt)) {
                        const zVal = (dynamicData.zone || 'GENERAL').toUpperCase();
                        if (/ZONA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'ZONA', zVal);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, zVal) : zVal;
                        } else {
                            rawTxt = `<span style="font-size: inherit; font-weight: inherit; color: inherit; text-transform: uppercase;">ZONA: ${zVal}</span>`;
                        }
                    } else if (field === 'seat' || field === 'butaca' || el.id === 'canvaElSeat' || el.id === 'canvaElButaca' || /BUTACA/i.test(rawTxt) || /ASIENTO/i.test(rawTxt)) {
                        const sVal = dynamicData.seat || '';
                        if (sVal) {
                            if (/BUTACA/i.test(rawTxt)) {
                                rawTxt = replaceDynamicValueInHtml(rawTxt, 'BUTACA', sVal);
                            } else if (/ASIENTO/i.test(rawTxt)) {
                                rawTxt = replaceDynamicValueInHtml(rawTxt, 'ASIENTO', sVal);
                            } else if (rawTxt && rawTxt.trim().length > 0) {
                                rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, sVal) : sVal;
                            } else {
                                rawTxt = `<div style="text-align: inherit; width: 100%;"><span style="font-size: 0.75em; font-weight: 900; display: block;">BUTACA:</span><span style="font-size: 1.1em; font-weight: 900; display: block; color: #F59E0B;">${sVal}</span></div>`;
                            }
                        } else {
                            rawTxt = '';
                        }
                    } else if (field === 'price' || el.id === 'canvaElPrice' || /PRECIO/i.test(rawTxt)) {
                        const pVal = dynamicData.price ? (String(dynamicData.price).startsWith('S/') ? dynamicData.price : 'S/ ' + dynamicData.price) : 'S/ 0.00';
                        if (/PRECIO/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'PRECIO', pVal);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, pVal) : pVal;
                        } else {
                            rawTxt = `<div style="line-height: 1.15; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; font-weight: 900; display: block;">PRECIO:</span><span style="font-size: 1.2em; font-weight: 900; display: block; margin-top: 2px;">${pVal}</span></div>`;
                        }
                    } else if (field === 'buyer_name' || el.id === 'canvaElBuyerName' || el.id === 'canvaElBuyer' || /Comprador/i.test(rawTxt)) {
                        if (dynamicData.is_plancha_print) {
                            return; // En la plancha física no se coloca comprador
                        }
                        const bName = (dynamicData.buyer_name || 'TALONARIO FÍSICO').toUpperCase();
                        if (/Comprador/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'Comprador', bName);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bName) : bName;
                        } else {
                            rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; opacity: 0.85;">Comprador:</span><span style="font-weight: 900; text-transform: uppercase;">${bName}</span></div>`;
                        }
                    } else if (field === 'ticket_number' || el.id === 'canvaElTicketNumber' || /N[°º]/i.test(rawTxt)) {
                        const numStr = dynamicData.ticket_number || 'N° 00001';
                        if (/N[°º]/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/N[°º]\s*[\d]+/gi, numStr);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, numStr) : numStr;
                        } else {
                            rawTxt = `<span style="font-weight: 900; letter-spacing: 0.5px;">${numStr}</span>`;
                        }
                    } else if (field === 'hash' || el.id === 'canvaElHash' || (el.id && String(el.id).toLowerCase().includes('hash')) || /VG-?[A-Z0-9]{6,12}/i.test(rawTxt)) {
                        const hStr = dynamicData.hash || 'VG00000000';
                        if (/VG-?[A-Z0-9]{6,12}/i.test(rawTxt)) {
                            rawTxt = rawTxt.replace(/VG-?[A-Z0-9]{6,12}/gi, hStr);
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, hStr) : hStr;
                        } else {
                            rawTxt = `<span style="font-family: monospace; font-weight: 800; letter-spacing: 1.5px;">${hStr}</span>`;
                        }
                    } else if (field === 'venue' || el.id === 'canvaElVenue') {
                        const vName = dynamicData.venue || '';
                        const vAddr = dynamicData.city || '';
                        const vDate = dynamicData.date || '';
                        const vTime = dynamicData.time || '';
                        if (!rawTxt || rawTxt.trim().length === 0) {
                            rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-weight: 900; display: block;">${vName}</span>${vAddr ? `<span style="font-size: 0.85em; opacity: 0.8; display: block; margin-top: 2px;">${vAddr}</span>` : ''}<span style="font-weight: 900; color: #FF5500; display: block; margin-top: 2px;">${vDate} / ${vTime}</span></div>`;
                        }
                    } else if (field === 'city' || el.id === 'canvaElCity') {
                        rawTxt = dynamicData.city || rawTxt;
                    } else if (field === 'date' || el.id === 'canvaElDate' || /FECHA/i.test(rawTxt)) {
                        if (/FECHA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'FECHA', dynamicData.date || '');
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = dynamicData.date ? (rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, dynamicData.date) : dynamicData.date) : rawTxt;
                        } else {
                            rawTxt = `<span style="font-weight: 900;">FECHA: ${dynamicData.date || ''}</span>`;
                        }
                    } else if (field === 'time' || el.id === 'canvaElTime' || /HORA/i.test(rawTxt)) {
                        if (/HORA/i.test(rawTxt)) {
                            rawTxt = replaceDynamicValueInHtml(rawTxt, 'HORA', dynamicData.time || '');
                        } else if (rawTxt && rawTxt.trim().length > 0) {
                            rawTxt = dynamicData.time ? (rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, dynamicData.time) : dynamicData.time) : rawTxt;
                        } else {
                            rawTxt = `<span style="font-weight: 900;">HORA: ${dynamicData.time || ''}</span>`;
                        }
                    } else if (field === 'disclaimer' || el.id === 'canvaElDisclaimer' || (el.id && String(el.id).toLowerCase().includes('disclaimer'))) {
                        rawTxt = rawTxt || `<div style="border-top: 1.5px solid #CBD5E1; padding-top: 0.25rem; width: 100%; text-align: inherit;"><p style="font-size: 0.65em; font-weight: 700; opacity: 0.8; line-height: 1.2; margin: 0; text-align: inherit;">Boleto oficial de venta física. No compartir ni fotocopiar.</p></div>`;
                    }

                    const flexAlign = textAlign === 'center' ? 'center' : (textAlign === 'right' ? 'flex-end' : 'flex-start');

                    if (typeof rawTxt === 'string' && rawTxt.includes('<')) {
                        rawTxt = rawTxt
                            .replace(/text-align\s*:\s*(left|center|right|justify)/gi, `text-align: ${textAlign}`)
                            .replace(/align-items\s*:\s*(flex-start|center|flex-end|stretch)/gi, `align-items: ${flexAlign}`);
                        rawTxt = rawTxt.replace(/<(p|div|h[1-6])\b([^>]*)>/gi, (match, tag, attrs) => {
                            if (/style\s*=/i.test(attrs)) {
                                return `<${tag} ${attrs.replace(/style\s*=\s*(['"])/i, `style=$1text-align: ${textAlign} !important; width: 100% !important; `)}>`;
                            } else {
                                return `<${tag} style="text-align: ${textAlign} !important; width: 100% !important; margin: 0; padding: 0;" ${attrs}>`;
                            }
                        });
                    }

                    innerContent = `
                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: ${flexAlign}; text-align: ${textAlign} !important; box-sizing: border-box; ${font} ${fontSize} ${color} ${weight} ${fontStyle} ${letterSpacing} ${lineHeight} ${bgStyle}">
                            ${rawTxt}
                        </div>
                    `;
                }

                elementsHtml += `
                    <div class="ticket-element-node" style="position: absolute; top: ${y}px; left: ${x}px; width: ${w}; height: ${h}; z-index: ${idx + 5}; ${transform} box-sizing: border-box; text-align: ${textAlign} !important;">
                        ${innerContent}
                    </div>
                `;
            });

            return `
                ${bgHtml}
                <div style="position: absolute; inset: 0; width: 100%; height: 100%;" class="ticket-elements-layer">
                    ${elementsHtml}
                </div>
            `;
        };
</script>
