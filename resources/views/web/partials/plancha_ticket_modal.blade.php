<!-- ========================================================================= -->
<!-- MODAL DE GENERACIÓN DE BOLETOS EN PLANCHA DE IMPRENTA (3 COLUMNAS MODERNO) -->
<!-- ========================================================================= -->

<style>
@media (max-width: 1080px) {
    .plancha-modal-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}
@media (max-width: 768px) {
    .plancha-modal-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<div class="admin-modal-overlay" id="planchaTicketModal" style="display: none; position: fixed; inset: 0; z-index: 100000; background: rgba(10, 10, 16, 0.85); backdrop-filter: blur(12px); align-items: center; justify-content: center; padding: 1rem;">
    <div class="admin-modal-card" style="max-width: 1240px; width: 96%; background: #14141E; border: 1.5px solid rgba(255,255,255,0.12); border-radius: 22px; padding: 1.35rem 1.6rem; box-shadow: 0 25px 60px rgba(0,0,0,0.7); max-height: 92vh; overflow-y: auto; box-sizing: border-box; color: #FFFFFF;">
        
        <!-- Header Compacto -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.15rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(37, 99, 235, 0.15); border: 1.5px solid rgba(37, 99, 235, 0.35); color: #3B82F6; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">🖨️</div>
                <div>
                    <h2 style="font-size: 1.2rem; font-weight: 900; margin: 0; color: #FFFFFF;">Generador de Planchas & Hojas de Boletos</h2>
                    <p style="font-size: 0.78rem; color: #94A3B8; margin: 0.15rem 0 0 0;">Emisión de códigos QR oficiales y distribución en pliegos de imprenta (24 boletos) u hojas A4 (6 boletos).</p>
                </div>
            </div>
            <button type="button" onclick="closePlanchaModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; width: 32px; height: 32px; border-radius: 10px; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">✕</button>
        </div>

        <!-- Layout en 3 Columnas -->
        <div class="plancha-modal-grid" style="display: grid; grid-template-columns: 290px 1.25fr 330px; gap: 1.1rem; align-items: stretch;">
            
            <!-- COLUMNA 1 (IZQUIERDA): Formato de Hoja, Medidas y Evento -->
            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                
                <!-- Evento Fijo Predefinido -->
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; padding: 0.75rem 0.9rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Evento Oficial</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div style="font-weight: 900; font-size: 0.88rem; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" id="plancha_event_title_text">Cargando evento...</div>
                        <span style="background: rgba(37, 99, 235, 0.2); color: #60A5FA; border: 1px solid rgba(37, 99, 235, 0.4); font-size: 0.63rem; font-weight: 800; padding: 0.12rem 0.45rem; border-radius: 6px; flex-shrink: 0;">VENTA FÍSICA</span>
                    </div>
                    <div style="font-size: 0.72rem; color: #94A3B8; margin-top: 0.25rem;" id="plancha_event_meta_text">📅 Fecha y recinto...</div>
                </div>

                <!-- 1. SELECTOR INTERACTIVO: FORMATO / MEDIDA DE IMPRESIÓN -->
                <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #60A5FA; text-transform: uppercase; letter-spacing: 0.5px;">
                        1. Formato de Hoja <span style="color: #EF4444;">*</span>
                    </label>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                        <!-- Opción 65x85 cm -->
                        <div class="plancha-size-card" id="card_plancha_65x85" onclick="selectPlanchaSize('65x85')" style="border: 2px solid #2563EB; background: rgba(37, 99, 235, 0.14); border-radius: 12px; padding: 0.65rem 0.75rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.2rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 900; font-size: 0.86rem; color: #FFFFFF;">📐 65×85 cm</span>
                                <span id="badge_plancha_65x85" style="background: #2563EB; color: #FFFFFF; font-size: 0.58rem; font-weight: 900; padding: 0.1rem 0.35rem; border-radius: 4px;">ACTIVO</span>
                            </div>
                            <span style="font-size: 0.7rem; color: #93C5FD; font-weight: 700;">850×650 mm (Offset Estándar)</span>
                            <span style="font-size: 0.64rem; color: #94A3B8;">4 col × 6 filas = 24 boletos</span>
                        </div>

                        <!-- Opción 60x80 cm -->
                        <div class="plancha-size-card" id="card_plancha_60x80" onclick="selectPlanchaSize('60x80')" style="border: 1.5px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.03); border-radius: 12px; padding: 0.65rem 0.75rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.2rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 900; font-size: 0.86rem; color: #FFFFFF;">📐 60×80 cm</span>
                                <span id="badge_plancha_60x80" style="display: none; background: #2563EB; color: #FFFFFF; font-size: 0.58rem; font-weight: 900; padding: 0.1rem 0.35rem; border-radius: 4px;">ACTIVO</span>
                            </div>
                            <span style="font-size: 0.7rem; color: #93C5FD; font-weight: 700;">800×600 mm (Pliego Mediano)</span>
                            <span style="font-size: 0.64rem; color: #94A3B8;">4 col × 6 filas = 24 boletos</span>
                        </div>

                        <!-- Opción Tamaño A4 -->
                        <div class="plancha-size-card" id="card_plancha_a4" onclick="selectPlanchaSize('a4')" style="border: 1.5px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.03); border-radius: 12px; padding: 0.65rem 0.75rem; cursor: pointer; display: flex; flex-direction: column; gap: 0.2rem; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 900; font-size: 0.86rem; color: #FFFFFF;">📄 Hoja A4</span>
                                <span id="badge_plancha_a4" style="display: none; background: #2563EB; color: #FFFFFF; font-size: 0.58rem; font-weight: 900; padding: 0.1rem 0.35rem; border-radius: 4px;">ACTIVO</span>
                            </div>
                            <span style="font-size: 0.7rem; color: #93C5FD; font-weight: 700;">297×210 mm (Impresora Estándar)</span>
                            <span style="font-size: 0.64rem; color: #94A3B8;">2 col × 3 filas = 6 boletos</span>
                        </div>
                    </div>
                </div>

                <!-- ESPECIFICACIONES TÉCNICAS DEL FORMATO -->
                <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.7rem 0.85rem; font-size: 0.72rem; color: #94A3B8; display: flex; flex-direction: column; gap: 0.3rem; margin-top: auto;">
                    <div style="font-weight: 800; color: #CBD5E1; text-transform: uppercase; font-size: 0.68rem; margin-bottom: 0.1rem;">📐 Medidas de Corte</div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Medida boleto:</span>
                        <strong id="plancha_spec_ticket_size" style="color: #60A5FA;">196 × 94 mm</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Por pliego / hoja:</span>
                        <strong id="plancha_spec_per_sheet" style="color: #FFFFFF;">24 unidades</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Guías perimetrales:</span>
                        <strong style="color: #10B981;">✓ 3 mm de corte</strong>
                    </div>
                </div>

            </div>

            <!-- COLUMNA 2 (CENTRO): Selección de Zonas y Cantidades -->
            <div style="display: flex; flex-direction: column; gap: 0.55rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #60A5FA; text-transform: uppercase; letter-spacing: 0.5px;">2. Zonas y Cantidad de Boletos</span>
                    <span id="plancha_zones_badge_count" style="font-size: 0.7rem; color: #10B981; font-weight: 800; white-space: nowrap;">Calculando...</span>
                </div>


                
                <!-- Contenedor lista de zonas con scroll vertical -->
                <div id="plancha_zones_list_container" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 0.5rem; max-height: 440px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.45rem; flex: 1;">
                    <!-- Poblado dinámicamente -->
                    <div style="padding: 1.5rem; text-align: center; color: #94A3B8; font-size: 0.78rem;">Cargando zonas y aforos...</div>
                </div>
            </div>

            <!-- COLUMNA 3 (DERECHA): Estado, Resumen Técnico y Botones de Acción -->
            <div style="display: flex; flex-direction: column; gap: 0.7rem;">
                
                <!-- ALERTA DINÁMICA DE DETECCIÓN DE AFORO / BOLETOS PENDIENTES -->
                <div id="plancha_status_alert_box" style="background: rgba(37, 99, 235, 0.1); border: 1.5px solid rgba(37, 99, 235, 0.3); border-radius: 14px; padding: 0.75rem 0.85rem;">
                    <div style="display: flex; align-items: flex-start; gap: 0.55rem;">
                        <span style="font-size: 1.25rem;" id="plancha_alert_icon">⚡</span>
                        <div style="font-size: 0.78rem; line-height: 1.35;" id="plancha_alert_content">
                            <strong style="color: #60A5FA; display: block; font-size: 0.83rem; margin-bottom: 0.2rem;">Analizando estado de aforos...</strong>
                            <span style="color: #CBD5E1;">Sincronizando boletos registrados y códigos QR con el sistema.</span>
                        </div>
                    </div>
                </div>

                <!-- RESUMEN TÉCNICO DE PRODUCCIÓN (COMPACTO) -->
                <div style="background: rgba(15, 23, 42, 0.75); border: 1.5px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 0.75rem 0.85rem;">
                    <div style="font-size: 0.68rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.45rem; display: flex; justify-content: space-between;">
                        <span>Resumen de Entradas & PDF</span>
                        <span id="plancha_summary_mode_label" style="color: #10B981;">Estado BD</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.78rem;">
                        <div style="background: rgba(255,255,255,0.03); padding: 0.45rem 0.55rem; border-radius: 8px;">
                            <span style="font-size: 0.66rem; color: #94A3B8; display: block;">Entradas en BD (QR)</span>
                            <strong id="plancha_summary_db_tickets" style="font-size: 1.05rem; color: #10B981; font-weight: 900;">0</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); padding: 0.45rem 0.55rem; border-radius: 8px;">
                            <span style="font-size: 0.66rem; color: #94A3B8; display: block;">A Imprimir en PDF</span>
                            <strong id="plancha_summary_total_tickets" style="font-size: 1.05rem; color: #FFFFFF; font-weight: 900;">0</strong>
                        </div>
                    </div>

                    <div style="margin-top: 0.5rem; padding-top: 0.45rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.73rem; color: #94A3B8; display: flex; flex-direction: column; gap: 0.22rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Hojas / Pliegos:</span>
                            <strong id="plancha_summary_sheets_count" style="color: #60A5FA; font-weight: 800;">0 hoja(s)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Rango correlativo:</span>
                            <strong id="plancha_summary_correlative_range" style="color: #F59E0B; font-family: monospace; font-size: 0.76rem;">N° 00001 → N° 00000</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Medida del PDF:</span>
                            <strong id="plancha_summary_dimensions" style="color: #FFFFFF;">Hoja A4 (297 × 210 mm)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Códigos QR & Scanner:</span>
                            <strong style="color: #10B981;">✓ 100% Sincronizados y Válidos</strong>
                        </div>
                    </div>
                </div>

                <!-- BARRA DE PROGRESO DE RENDERIZADO -->
                <div id="plancha_render_progress_box" style="display: none; background: rgba(37, 99, 235, 0.15); border: 1px solid rgba(37, 99, 235, 0.4); border-radius: 12px; padding: 0.65rem 0.8rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.73rem; font-weight: 800; color: #93C5FD; margin-bottom: 0.3rem;">
                        <span id="plancha_progress_step_text">Compilando boletos...</span>
                        <span id="plancha_progress_percent">0%</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                        <div id="plancha_progress_bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #2563EB, #60A5FA); transition: width 0.15s ease;"></div>
                    </div>
                </div>



                <!-- BOTONES DE ACCIÓN: GENERAR PLANCHA PDF Y CERRAR -->
                <div style="display: flex; flex-direction: column; gap: 0.6rem; margin-top: auto;">
                    <!-- BOTÓN PRINCIPAL: GENERAR PLANCHA PDF -->
                    <button type="button" id="btnExecutePlanchaGeneration" onclick="startPlanchaPdfGeneration()" style="background: linear-gradient(135deg, #2563EB, #1D4ED8); color: #FFFFFF; border: none; padding: 0.85rem 1.1rem; border-radius: 14px; font-size: 0.92rem; font-weight: 900; box-shadow: 0 4px 18px rgba(37, 99, 235, 0.45); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease;">
                        <span style="font-size: 1.2rem;">🖨️</span>
                        <span id="btnPlanchaText">GENERAR PLANCHA PDF</span>
                    </button>

                    <!-- BOTÓN CERRAR -->
                    <button type="button" onclick="closePlanchaModal()" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94A3B8; padding: 0.65rem 0.9rem; border-radius: 12px; font-weight: 800; font-size: 0.82rem; cursor: pointer; text-align: center; transition: all 0.2s ease;">
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
    let newlyEmittedTicketsCache = [];
    let planchaIsGenerating = false;

    /**
     * Abre el modal de planchas y consulta el estado de boletos y aforos del evento
     */
    async function openPlanchaModal(eventData) {
        if (!eventData) return;
        activePlanchaEvent = eventData;
        planchaPrintMode = 'ONLY_NEW';
        newlyEmittedTicketsCache = [];

        // 1. Resetear barra de progreso y estado previo
        const progressBox = document.getElementById('plancha_render_progress_box');
        const progressBar = document.getElementById('plancha_progress_bar');
        const progressPercent = document.getElementById('plancha_progress_percent');
        const progressStepText = document.getElementById('plancha_progress_step_text');
        if (progressBox) progressBox.style.display = 'none';
        if (progressBar) progressBar.style.width = '0%';
        if (progressPercent) progressPercent.textContent = '0%';
        if (progressStepText) progressStepText.textContent = 'Compilando boletos...';

        // 2. Título y metadatos del evento
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

        // 3. Resetear selectores visuales
        selectPlanchaSize('65x85');

        // 4. Mostrar modal de inmediato con animación suave
        const modal = document.getElementById('planchaTicketModal');
        if (modal) {
            modal.style.display = 'flex';
            requestAnimationFrame(() => {
                modal.classList.add('active');
            });
        }

        // 5. Mostrar estado de carga en zonas
        const zonesContainer = document.getElementById('plancha_zones_list_container');
        if (zonesContainer) {
            zonesContainer.innerHTML = '<div style="padding: 1rem; text-align: center; color: #94A3B8; font-size: 0.8rem;">🔄 Sincronizando aforos y boletos de la base de datos...</div>';
        }

        // 6. Consultar boletos ya registrados en BD para este evento
        try {
            planchaExistingTickets = [];
            if (activePlanchaEvent.id) {
                const res = await fetch(`/admin/eventos/${activePlanchaEvent.id}/boletos-registrados`);
                const data = await res.json();
                if (data && data.success) {
                    if (Array.isArray(data.tickets)) {
                        planchaExistingTickets = data.tickets;
                    }
                    if (data.courtesy_settings && (!activePlanchaEvent.courtesy_settings || Object.keys(activePlanchaEvent.courtesy_settings).length === 0)) {
                        activePlanchaEvent.courtesy_settings = data.courtesy_settings;
                    }
                }
            }
        } catch (e) {
            console.warn('[Plancha] Error cargando boletos registrados:', e);
            planchaExistingTickets = [];
        }

        // 7. Procesar y calcular aforos de zonas vs boletos ya existentes
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

        // Resetear barra de progreso al cerrar el modal
        const progressBox = document.getElementById('plancha_render_progress_box');
        const progressBar = document.getElementById('plancha_progress_bar');
        const progressPercent = document.getElementById('plancha_progress_percent');
        const progressStepText = document.getElementById('plancha_progress_step_text');
        if (progressBox) progressBox.style.display = 'none';
        if (progressBar) progressBar.style.width = '0%';
        if (progressPercent) progressPercent.textContent = '0%';
        if (progressStepText) progressStepText.textContent = 'Compilando boletos...';
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
     * Selección de medida de plancha (65x85, 60x80 o A4)
     */
    function selectPlanchaSize(sizeKey) {
        selectedPlanchaSizeKey = sizeKey;
        const card65 = document.getElementById('card_plancha_65x85');
        const card60 = document.getElementById('card_plancha_60x80');
        const cardA4 = document.getElementById('card_plancha_a4');
        const badge65 = document.getElementById('badge_plancha_65x85');
        const badge60 = document.getElementById('badge_plancha_60x80');
        const badgeA4 = document.getElementById('badge_plancha_a4');

        const activeStyle = { border: '2px solid #2563EB', background: 'rgba(37, 99, 235, 0.14)' };
        const inactiveStyle = { border: '1.5px solid rgba(255,255,255,0.12)', background: 'rgba(255,255,255,0.03)' };

        if (card65) Object.assign(card65.style, sizeKey === '65x85' ? activeStyle : inactiveStyle);
        if (card60) Object.assign(card60.style, sizeKey === '60x80' ? activeStyle : inactiveStyle);
        if (cardA4) Object.assign(cardA4.style, sizeKey === 'a4' ? activeStyle : inactiveStyle);

        if (badge65) badge65.style.display = sizeKey === '65x85' ? 'inline-block' : 'none';
        if (badge60) badge60.style.display = sizeKey === '60x80' ? 'inline-block' : 'none';
        if (badgeA4) badgeA4.style.display = sizeKey === 'a4' ? 'inline-block' : 'none';

        // Actualizar textos de botones de preajuste por pliego/hoja
        const sheetLabel = sizeKey === 'a4' ? '1 Hoja (6)' : '1 Pliego (24)';
        if (Array.isArray(planchaZoneBreakdown)) {
            planchaZoneBreakdown.forEach((_, idx) => {
                const btnSheet = document.getElementById(`btn_preset_sheet_${idx}`);
                if (btnSheet) btnSheet.textContent = sheetLabel;
            });
        }

        // Actualizar especificaciones en Columna 1
        const specSize = document.getElementById('plancha_spec_ticket_size');
        const specPerSheet = document.getElementById('plancha_spec_per_sheet');
        if (sizeKey === 'a4') {
            if (specSize) specSize.textContent = '130 × 60 mm';
            if (specPerSheet) specPerSheet.textContent = '6 boletos (2×3)';
        } else if (sizeKey === '60x80') {
            if (specSize) specSize.textContent = '184 × 88.3 mm';
            if (specPerSheet) specPerSheet.textContent = '24 boletos (4×6)';
        } else {
            if (specSize) specSize.textContent = '196 × 94 mm';
            if (specPerSheet) specPerSheet.textContent = '24 boletos (4×6)';
        }

        updatePlanchaSummary();
    }

    function cleanZoneBase(name) {
        if (!name) return 'GENERAL';
        let str = String(name).replace(/\s*\([^)]*\)$/, '').trim().toUpperCase();
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function formatShortSeatCodeJs(seat) {
        if (!seat) return '';
        if (typeof seat === 'object') {
            const r = seat.row ? String(seat.row).toUpperCase().trim() : '';
            const c = (seat.col !== undefined && seat.col !== null) ? String(seat.col).trim() : '';
            const num = (seat.number !== undefined && seat.number !== null) ? String(seat.number).trim() : '';

            // Si col es numérico (ej: row: "A", col: "3")
            if (r && c && /^\d+$/.test(c)) {
                return r + c;
            }

            // Si number es como "A-3", "A3", "A 3" o solo dígitos
            if (r && num) {
                const numMatch = num.match(new RegExp('^' + r + '[\\s\\-_]*(\\d+)$', 'i'));
                if (numMatch) {
                    return r + numMatch[1];
                }
                if (/^\d+$/.test(num)) {
                    return r + num;
                }
            }

            seat = seat.label || seat.display_name || seat.number || (r && c ? r + c : '') || seat.code || '';
        }
        seat = String(seat).trim();
        if (!seat) return '';

        // Si viene con prefijo duplicado tipo "AA-3", "AA3", "AA_3"
        let m = seat.match(/^([A-Za-z])\1[\s\-_]*([0-9]+)$/);
        if (m) return m[1].toUpperCase() + m[2];

        // Caso: "Fila A - Asiento 1" o "Fila A - Columna 1"
        m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*-\s*(?:Asiento|Columna)\s*([0-9]+)/i);
        if (m) return m[1].toUpperCase() + m[2];

        // Caso: "Fila A Asiento 1"
        m = seat.match(/Fila\s*([A-Za-z0-9]+)\s*(?:Asiento|Columna)\s*([0-9]+)/i);
        if (m) return m[1].toUpperCase() + m[2];

        // Caso: "A-1" o "A 1" o "A_1"
        m = seat.match(/^([A-Za-z]+)[-\s_]+([0-9]+)$/);
        if (m) return m[1].toUpperCase() + m[2];

        // Caso: "A1" o "B10"
        m = seat.match(/^([A-Za-z]+[0-9]+)$/);
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
                baseZoneName: zName,
                isCourtesy: false,
                price: zPrice,
                capacity: zCap,
                seats: zSeats,
                isNumberedZone: isNumbered,
                alreadyGenerated: alreadyGenCount,
                pendingNew: pendingCount,
                existingTickets: existingList
            });
        });

        // Detectar si las entradas de cortesía están activadas en el evento
        let cSettings = evt.courtesy_settings || {};
        if (typeof cSettings === 'string') {
            try { cSettings = JSON.parse(cSettings); } catch(e) { cSettings = {}; }
        }
        const isCourtesyActive = cSettings && (cSettings.enabled === true || cSettings.enabled === '1' || cSettings.enabled === 1 || cSettings.enabled === 'true');

        if (isCourtesyActive) {
            const courtesyConfigMap = {};
            if (Array.isArray(cSettings.zones)) {
                cSettings.zones.forEach(cz => {
                    if (cz && cz.name) {
                        courtesyConfigMap[cleanZoneBase(cz.name)] = cz;
                    }
                });
            }
            const hasCustomCourtesyZones = Object.keys(courtesyConfigMap).length > 0;

            zones.forEach(z => {
                const zName = z.name || 'General';
                const zKey = cleanZoneBase(zName);
                const czConfig = courtesyConfigMap[zKey];
                // Determinar stock de cortesía asignado para este sector (solo si se configuró un cupo mayor a 0)
                let czCap = 0;
                if (czConfig && czConfig.stock !== null && czConfig.stock !== undefined && czConfig.stock !== '' && !isNaN(parseInt(czConfig.stock))) {
                    czCap = parseInt(czConfig.stock, 10);
                }
                if (czCap <= 0) return;

                const courtesyZoneName = `CORTESÍA - ${zName}`;
                const courtesyZKey = cleanZoneBase(courtesyZoneName);

                // Butacas si la zona base es numerada
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

                const existingCourtesyList = existingByZone[courtesyZKey] || [];
                const alreadyGenCount = existingCourtesyList.length;
                const pendingCount = Math.max(0, czCap - alreadyGenCount);

                totalConfigCapacity += czCap;
                totalAlreadyGenerated += alreadyGenCount;
                totalPendingNew += pendingCount;

                planchaZoneBreakdown.push({
                    name: courtesyZoneName,
                    baseZoneName: zName,
                    isCourtesy: true,
                    price: 0.00,
                    capacity: czCap,
                    seats: zSeats,
                    isNumberedZone: isNumbered,
                    alreadyGenerated: alreadyGenCount,
                    pendingNew: pendingCount,
                    existingTickets: existingCourtesyList
                });
            });
        }

        // 1. Renderizar lista visual de zonas en Columna 1
        const zonesContainer = document.getElementById('plancha_zones_list_container');
        const badgeCount = document.getElementById('plancha_zones_badge_count');
        if (badgeCount) {
            const regularCount = planchaZoneBreakdown.filter(zb => !zb.isCourtesy).length;
            const courtesyCount = planchaZoneBreakdown.filter(zb => zb.isCourtesy).length;
            if (courtesyCount > 0) {
                badgeCount.textContent = `${regularCount} reg. + ${courtesyCount} cortesías (${totalConfigCapacity} aforo total)`;
            } else {
                badgeCount.textContent = `${planchaZoneBreakdown.length} zonas (${totalConfigCapacity} aforo total)`;
            }
        }

        // Poblar selector rápido de zonas
        const quickFilter = document.getElementById('plancha_quick_zone_filter');
        if (quickFilter) {
            let filterHtml = '<option value="ALL">🌟 Todas las Zonas</option>';
            if (isCourtesyActive) {
                filterHtml += '<option value="REGULAR_ONLY">🎟️ Solo Zonas Regulares</option>';
                filterHtml += '<option value="COURTESY_ONLY">🎁 Solo Zonas de Cortesía</option>';
            }
            planchaZoneBreakdown.forEach((zb, idx) => {
                const icon = zb.isCourtesy ? '🎁' : '🎟️';
                filterHtml += `<option value="${idx}">${icon} ${zb.name}</option>`;
            });
            quickFilter.innerHTML = filterHtml;
            quickFilter.value = 'ALL';
        }

        if (zonesContainer) {
            let html = '';
            const sheetLabel = selectedPlanchaSizeKey === 'a4' ? '1 Hoja (6)' : '1 Pliego (24)';
            let courtesyHeaderRendered = false;

            planchaZoneBreakdown.forEach((zb, idx) => {
                let statusBadge = '';
                if (zb.alreadyGenerated === 0) {
                    statusBadge = `<span style="background: ${zb.isCourtesy ? 'rgba(16,185,129,0.12)' : 'rgba(37,99,235,0.15)'}; color: ${zb.isCourtesy ? '#6EE7B7' : '#93C5FD'}; border: 1px solid ${zb.isCourtesy ? 'rgba(16,185,129,0.3)' : 'rgba(37,99,235,0.3)'}; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 6px;">0 emitidas</span>`;
                } else {
                    statusBadge = `<span style="background: rgba(16,185,129,0.15); color: #34D399; border: 1px solid rgba(16,185,129,0.3); font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 6px;">✓ ${zb.alreadyGenerated} en BD</span>`;
                }

                let seatChip = zb.isNumberedZone
                    ? `<span style="background: rgba(245,158,11,0.15); color: #F59E0B; border: 1px solid rgba(245,158,11,0.3); font-size: 0.63rem; font-weight: 800; padding: 0.12rem 0.4rem; border-radius: 6px; margin-left: 0.35rem;">🪑 ${zb.seats.length > 0 ? zb.seats.length : zb.capacity} Butacas</span>`
                    : '';

                // Cantidad y selección por defecto inteligente según aforo pendiente
                let defaultQty = 24;
                let defaultChecked = true;
                if (totalPendingNew > 0) {
                    if (zb.pendingNew > 0) {
                        defaultQty = zb.pendingNew;
                        defaultChecked = true;
                    } else {
                        defaultQty = 0;
                        defaultChecked = false;
                    }
                } else {
                    defaultQty = zb.alreadyGenerated > 0 ? zb.alreadyGenerated : (zb.capacity > 0 ? zb.capacity : 24);
                    defaultChecked = true;
                }

                const cardOpacity = defaultChecked ? '1' : '0.45';
                const cardBorder = zb.isCourtesy
                    ? (defaultChecked ? 'rgba(16, 185, 129, 0.4)' : 'rgba(16, 185, 129, 0.15)')
                    : (defaultChecked ? 'rgba(255,255,255,0.08)' : 'rgba(255,255,255,0.04)');
                const inputDisabled = defaultChecked ? '' : 'disabled';

                if (zb.isCourtesy && !courtesyHeaderRendered) {
                    courtesyHeaderRendered = true;
                    html += `
                        <div id="plancha_courtesy_zones_header" style="margin: 0.35rem 0 0.2rem 0; padding: 0.25rem 0.6rem; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; gap: 0.4rem;">
                            <div style="display: flex; align-items: center; gap: 0.35rem;">
                                <span style="font-size: 0.8rem;">🎁</span>
                                <span style="color: #10B981; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.2px;">Entradas de Cortesía (Pases Free)</span>
                                <span style="font-size: 0.62rem; color: #94A3B8;">— Emisión S/ 0.00</span>
                            </div>
                            <span style="background: rgba(16,185,129,0.12); color: #34D399; border: 1px solid rgba(16,185,129,0.25); font-size: 0.58rem; font-weight: 700; padding: 0.08rem 0.35rem; border-radius: 4px; white-space: nowrap;">Cortesías Activas</span>
                        </div>
                    `;
                }

                const icon = zb.isCourtesy ? '🎁' : '🎟️';
                const courtesyTag = zb.isCourtesy
                    ? `<span style="background: rgba(16,185,129,0.18); color: #10B981; border: 1px solid rgba(16,185,129,0.4); font-size: 0.63rem; font-weight: 900; padding: 0.12rem 0.4rem; border-radius: 6px; margin-left: 0.35rem;">FREE</span>`
                    : '';
                const priceLabel = zb.isCourtesy
                    ? `<span style="color: #10B981; font-weight: 800;">S/ 0.00 (CORTESÍA)</span>`
                    : `S/ ${zb.price.toFixed(2)}`;
                const refLabel = zb.isCourtesy ? 'Cupo Ref:' : 'Aforo Ref:';
                const inputBorder = zb.isCourtesy ? 'rgba(16,185,129,0.6)' : 'rgba(37,99,235,0.6)';
                const chkAccent = zb.isCourtesy ? '#10B981' : '#2563EB';

                html += `
                    <div id="plancha_zone_card_${idx}" class="${zb.isCourtesy ? 'card-is-courtesy' : 'card-is-regular'}" style="background: ${zb.isCourtesy ? 'rgba(16, 185, 129, 0.04)' : 'rgba(255,255,255,0.02)'}; border: 1.5px solid ${cardBorder}; opacity: ${cardOpacity}; border-radius: 12px; padding: 0.6rem 0.75rem; display: flex; flex-direction: column; gap: 0.4rem; transition: all 0.2s ease;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; flex: 1; min-width: 0; margin: 0;">
                                <input type="checkbox" id="plancha_zone_chk_${idx}" ${defaultChecked ? 'checked' : ''} onchange="onPlanchaZoneCheckChange(${idx})" style="width: 16px; height: 16px; accent-color: ${chkAccent}; cursor: pointer; border-radius: 4px; flex-shrink: 0;" />
                                <span style="font-weight: 800; font-size: 0.83rem; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${icon} ${zb.name}
                                </span>
                                ${courtesyTag}
                                ${seatChip}
                            </label>
                            <div style="flex-shrink: 0;">
                                ${statusBadge}
                            </div>
                        </div>

                        <div style="font-size: 0.7rem; color: #94A3B8; display: flex; align-items: center; justify-content: space-between; padding-left: 1.5rem;">
                            <span>Precio: <strong style="color: #10B981;">${priceLabel}</strong></span>
                            <span>${refLabel} <strong style="color: #FFFFFF;">${zb.capacity}</strong> • En BD: <strong style="color: #34D399;">${zb.alreadyGenerated}</strong></span>
                        </div>

                        <div id="plancha_zone_qty_row_${idx}" style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; background: rgba(15, 23, 42, 0.7); padding: 0.35rem 0.55rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.07); margin-left: 1.5rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <span style="font-size: 0.7rem; color: ${zb.isCourtesy ? '#6EE7B7' : '#93C5FD'}; font-weight: 800; white-space: nowrap;">Cantidad:</span>
                                <input type="number" 
                                       id="plancha_zone_qty_${idx}" 
                                       min="1" 
                                       max="99999" 
                                       value="${defaultQty}" 
                                       ${inputDisabled}
                                       oninput="onPlanchaZoneQtyInput(${idx})" 
                                       style="width: 70px; background: rgba(255,255,255,0.08); border: 1.5px solid ${inputBorder}; color: #FFFFFF; border-radius: 6px; padding: 0.2rem 0.35rem; font-size: 0.82rem; font-weight: 900; text-align: center; outline: none;" />
                            </div>
                            <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                ${zb.pendingNew > 0 ? `<button type="button" onclick="setZoneQtyPreset(${idx}, 'pending')" style="background: rgba(16,185,129,0.22); border: 1.5px solid rgba(16,185,129,0.5); color: #34D399; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.45rem; border-radius: 5px; cursor: pointer;">⚡ Restantes (${zb.pendingNew})</button>` : ''}
                                <button type="button" onclick="adjustZoneQty(${idx}, 10)" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 5px; cursor: pointer;">+10</button>
                                <button type="button" onclick="setZoneQtyPreset(${idx}, 'sheet')" id="btn_preset_sheet_${idx}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 5px; cursor: pointer;">${sheetLabel}</button>
                                <button type="button" onclick="setZoneQtyPreset(${idx}, 'capacity')" style="background: ${zb.isCourtesy ? 'rgba(16,185,129,0.25)' : 'rgba(37,99,235,0.25)'}; border: 1.5px solid ${zb.isCourtesy ? 'rgba(16,185,129,0.5)' : 'rgba(37,99,235,0.5)'}; color: ${zb.isCourtesy ? '#6EE7B7' : '#93C5FD'}; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 5px; cursor: pointer;">${zb.isCourtesy ? 'Cupo' : 'Aforo'} (${zb.capacity})</button>
                                ${zb.alreadyGenerated > 0 ? `<button type="button" onclick="setZoneQtyPreset(${idx}, 'existing')" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4); color: #34D399; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 5px; cursor: pointer;">En BD (${zb.alreadyGenerated})</button>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            zonesContainer.innerHTML = html;
        }

        // 2. Configurar Alerta Dinámica en Columna 3
        const alertBox = document.getElementById('plancha_status_alert_box');
        const alertIcon = document.getElementById('plancha_alert_icon');
        const alertContent = document.getElementById('plancha_alert_content');

        if (alertBox) {
            alertBox.style.background = 'rgba(16, 185, 129, 0.12)';
            alertBox.style.border = '1.5px solid rgba(16, 185, 129, 0.4)';
        }
        if (alertIcon) alertIcon.textContent = '✓';
        if (alertContent) {
            alertContent.innerHTML = `
                <strong style="color: #34D399; display: block; font-size: 0.88rem; margin-bottom: 0.2rem;">✓ Boletos Oficiales Listos (${totalAlreadyGenerated} en BD)</strong>
                <span style="color: #CBD5E1;">Todas las entradas y códigos QR del aforo configurado están registrados en la base de datos. Selecciona las zonas deseadas y haz clic en <b>"GENERAR PLANCHA PDF"</b>.</span>
            `;
        }

        updatePlanchaSummary();
    }

    /**
     * Manejador al marcar / desmarcar una zona
     */
    function onPlanchaZoneCheckChange(idx) {
        const chk = document.getElementById(`plancha_zone_chk_${idx}`);
        const card = document.getElementById(`plancha_zone_card_${idx}`);
        const qtyInput = document.getElementById(`plancha_zone_qty_${idx}`);
        const btns = card ? card.querySelectorAll('button') : [];

        if (chk && card) {
            const isCourtesy = planchaZoneBreakdown && planchaZoneBreakdown[idx] && planchaZoneBreakdown[idx].isCourtesy;
            if (chk.checked) {
                card.style.opacity = '1';
                card.style.borderColor = isCourtesy ? 'rgba(16, 185, 129, 0.4)' : 'rgba(255,255,255,0.08)';
                if (qtyInput) qtyInput.disabled = false;
                btns.forEach(b => b.disabled = false);
            } else {
                card.style.opacity = '0.45';
                card.style.borderColor = isCourtesy ? 'rgba(16, 185, 129, 0.15)' : 'rgba(255,255,255,0.04)';
                if (qtyInput) qtyInput.disabled = true;
                btns.forEach(b => b.disabled = true);
            }
        }

        // Sincronizar el selector desplegable rápido
        const quickFilter = document.getElementById('plancha_quick_zone_filter');
        if (quickFilter && Array.isArray(planchaZoneBreakdown)) {
            const allChecked = planchaZoneBreakdown.every((_, i) => document.getElementById(`plancha_zone_chk_${i}`)?.checked);
            const regularZones = planchaZoneBreakdown.map((zb, i) => (!zb.isCourtesy ? i : null)).filter(v => v !== null);
            const courtesyZones = planchaZoneBreakdown.map((zb, i) => (zb.isCourtesy ? i : null)).filter(v => v !== null);

            const regularAllChecked = regularZones.length > 0 && regularZones.every(i => document.getElementById(`plancha_zone_chk_${i}`)?.checked) && (courtesyZones.length === 0 || courtesyZones.every(i => !document.getElementById(`plancha_zone_chk_${i}`)?.checked));
            const courtesyAllChecked = courtesyZones.length > 0 && courtesyZones.every(i => document.getElementById(`plancha_zone_chk_${i}`)?.checked) && regularZones.every(i => !document.getElementById(`plancha_zone_chk_${i}`)?.checked);

            if (allChecked) {
                quickFilter.value = 'ALL';
            } else if (regularAllChecked && courtesyZones.length > 0) {
                quickFilter.value = 'REGULAR_ONLY';
            } else if (courtesyAllChecked) {
                quickFilter.value = 'COURTESY_ONLY';
            } else {
                const checkedIndices = planchaZoneBreakdown
                    .map((_, i) => document.getElementById(`plancha_zone_chk_${i}`)?.checked ? i : null)
                    .filter(v => v !== null);
                if (checkedIndices.length === 1) {
                    quickFilter.value = String(checkedIndices[0]);
                }
            }
        }

        updatePlanchaSummary();
    }

    /**
     * Filtro desplegable de zonas (Todas, Solo Regulares, Solo Cortesías, o una zona específica)
     */
    function onPlanchaZoneFilterChange(val) {
        if (!Array.isArray(planchaZoneBreakdown)) return;

        if (val === 'ALL') {
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                const card = document.getElementById(`plancha_zone_card_${idx}`);
                if (card) card.style.display = 'flex';
                if (chk) {
                    chk.checked = true;
                    onPlanchaZoneCheckChange(idx);
                }
            });
            const cHeader = document.getElementById('plancha_courtesy_zones_header');
            if (cHeader) cHeader.style.display = 'flex';
        } else if (val === 'REGULAR_ONLY') {
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                const card = document.getElementById(`plancha_zone_card_${idx}`);
                if (zb.isCourtesy) {
                    if (card) card.style.display = 'none';
                    if (chk) { chk.checked = false; onPlanchaZoneCheckChange(idx); }
                } else {
                    if (card) card.style.display = 'flex';
                    if (chk) { chk.checked = true; onPlanchaZoneCheckChange(idx); }
                }
            });
            const cHeader = document.getElementById('plancha_courtesy_zones_header');
            if (cHeader) cHeader.style.display = 'none';
        } else if (val === 'COURTESY_ONLY') {
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                const card = document.getElementById(`plancha_zone_card_${idx}`);
                if (zb.isCourtesy) {
                    if (card) card.style.display = 'flex';
                    if (chk) { chk.checked = true; onPlanchaZoneCheckChange(idx); }
                } else {
                    if (card) card.style.display = 'none';
                    if (chk) { chk.checked = false; onPlanchaZoneCheckChange(idx); }
                }
            });
            const cHeader = document.getElementById('plancha_courtesy_zones_header');
            if (cHeader) cHeader.style.display = 'flex';
        } else {
            const targetIdx = parseInt(val, 10);
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                const card = document.getElementById(`plancha_zone_card_${idx}`);
                if (card) card.style.display = 'flex';
                if (chk) {
                    chk.checked = (idx === targetIdx);
                    onPlanchaZoneCheckChange(idx);
                }
            });
            const cHeader = document.getElementById('plancha_courtesy_zones_header');
            if (cHeader) {
                const targetIsCourtesy = planchaZoneBreakdown[targetIdx]?.isCourtesy;
                cHeader.style.display = targetIsCourtesy ? 'flex' : 'none';
            }
        }

        updatePlanchaSummary();
    }

    /**
     * Validación en tiempo real del campo cantidad por zona
     */
    function onPlanchaZoneQtyInput(idx) {
        updatePlanchaSummary();
    }

    /**
     * Sumar o restar cantidad dinámicamente (+10, etc.)
     */
    function adjustZoneQty(idx, amount) {
        const input = document.getElementById(`plancha_zone_qty_${idx}`);
        const chk = document.getElementById(`plancha_zone_chk_${idx}`);
        if (!input) return;
        if (chk && !chk.checked) {
            chk.checked = true;
            onPlanchaZoneCheckChange(idx);
        }
        let current = parseInt(input.value, 10) || 0;
        input.value = Math.max(1, current + amount);
        updatePlanchaSummary();
    }

    /**
     * Botones de atajo rápido de cantidad (Aforo / 1 Hoja o Pliego / En BD)
     */
    function setZoneQtyPreset(idx, type) {
        const input = document.getElementById(`plancha_zone_qty_${idx}`);
        const chk = document.getElementById(`plancha_zone_chk_${idx}`);
        if (!input) return;

        if (chk && !chk.checked) {
            chk.checked = true;
            onPlanchaZoneCheckChange(idx);
        }

        const zb = (Array.isArray(planchaZoneBreakdown) && planchaZoneBreakdown[idx]) ? planchaZoneBreakdown[idx] : null;

        if (type === 'pending') {
            input.value = Math.max(1, zb ? zb.pendingNew : 1);
        } else if (type === 'capacity') {
            input.value = Math.max(1, zb ? zb.capacity : 24);
        } else if (type === 'existing') {
            input.value = Math.max(1, zb ? zb.alreadyGenerated : 24);
        } else if (type === 'sheet') {
            const perSheet = selectedPlanchaSizeKey === 'a4' ? 6 : 24;
            input.value = perSheet;
        }
        updatePlanchaSummary();
    }

    /**
     * Actualiza el resumen técnico de boletos y pliegos en tiempo real
     */
    function updatePlanchaSummary() {
        const isA4 = selectedPlanchaSizeKey === 'a4';
        const isPlancha65 = selectedPlanchaSizeKey === '65x85';
        const isPlancha60 = selectedPlanchaSizeKey === '60x80';

        const perSheet = isA4 ? 6 : 24;
        let planchaSizeLabel = '65 × 85 cm (850 × 650 mm)';
        let sheetTypeWord = 'pliego(s) (4×6)';

        if (isA4) {
            planchaSizeLabel = 'Hoja A4 (297 × 210 mm)';
            sheetTypeWord = 'hoja(s) A4 (2×3)';
        } else if (isPlancha60) {
            planchaSizeLabel = '60 × 80 cm (800 × 600 mm)';
            sheetTypeWord = 'pliego(s) (4×6)';
        }

        const dimEl = document.getElementById('plancha_summary_dimensions');
        if (dimEl) dimEl.textContent = planchaSizeLabel;

        // Calcular boletos que se imprimirán según las zonas seleccionadas y sus cantidades
        let ticketsToPrintCount = 0;
        let selectedZonesCount = 0;

        if (Array.isArray(planchaZoneBreakdown)) {
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                if (chk && chk.checked) {
                    selectedZonesCount++;
                    const input = document.getElementById(`plancha_zone_qty_${idx}`);
                    const qty = input ? (parseInt(input.value, 10) || 0) : 0;
                    ticketsToPrintCount += Math.max(0, qty);
                }
            });
        }

        const dbCount = Array.isArray(planchaExistingTickets) ? planchaExistingTickets.length : 0;
        const dbTotalEl = document.getElementById('plancha_summary_db_tickets');
        if (dbTotalEl) dbTotalEl.textContent = `${dbCount} entradas`;

        let maxExistingNum = 0;
        if (Array.isArray(planchaExistingTickets)) {
            planchaExistingTickets.forEach(t => {
                const n = parseInt(t.ticketNumberVal || t.ticket_number, 10);
                if (n > maxExistingNum) maxExistingNum = n;
            });
        }

        let totalPendingRemaining = 0;
        if (Array.isArray(planchaZoneBreakdown)) {
            planchaZoneBreakdown.forEach(zb => {
                totalPendingRemaining += (zb.pendingNew || 0);
            });
        }

        const sheetsCount = Math.ceil(ticketsToPrintCount / perSheet) || 0;

        const totalEl = document.getElementById('plancha_summary_total_tickets');
        const sheetsEl = document.getElementById('plancha_summary_sheets_count');
        const rangeEl = document.getElementById('plancha_summary_correlative_range');
        const btnPlancha = document.getElementById('btnExecutePlanchaGeneration');
        const btnText = document.getElementById('btnPlanchaText');

        if (totalEl) totalEl.textContent = `${ticketsToPrintCount} boletos (${selectedZonesCount} zona${selectedZonesCount === 1 ? '' : 's'})`;
        if (sheetsEl) sheetsEl.textContent = `${sheetsCount} ${sheetTypeWord}`;

        // Calcular rango correlativo exacto de los boletos seleccionados para imprimir
        let selectedPrintTickets = [];
        if (Array.isArray(planchaZoneBreakdown)) {
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                if (chk && chk.checked) {
                    const zKey = cleanZoneBase(zb.name);
                    const zoneExisting = planchaExistingTickets.filter(t => cleanZoneBase(t.zoneName || t.zone_name) === zKey);
                    const input = document.getElementById(`plancha_zone_qty_${idx}`);
                    const qtyWanted = input ? (parseInt(input.value, 10) || 0) : 0;
                    if (qtyWanted > 0 && zoneExisting.length > 0) {
                        const slice = zoneExisting.slice(0, qtyWanted);
                        selectedPrintTickets = selectedPrintTickets.concat(slice);
                    }
                }
            });
        }
        
        if (rangeEl) {
            if (selectedPrintTickets.length > 0) {
                const nums = selectedPrintTickets.map(t => parseInt(t.ticketNumberVal || t.ticket_number, 10)).filter(n => !isNaN(n) && n > 0);
                if (nums.length > 0) {
                    const minN = Math.min(...nums);
                    const maxN = Math.max(...nums);
                    rangeEl.textContent = `N° ${String(minN).padStart(5, '0')} → N° ${String(maxN).padStart(5, '0')}`;
                } else {
                    rangeEl.textContent = `N° 00001 → N° ${String(selectedPrintTickets.length).padStart(5, '0')}`;
                }
            } else if (ticketsToPrintCount > 0) {
                let startNum = maxExistingNum + 1;
                let endNum = maxExistingNum + ticketsToPrintCount;
                rangeEl.textContent = `N° ${String(startNum).padStart(5, '0')} → N° ${String(endNum).padStart(5, '0')} (Por emitir)`;
            } else {
                rangeEl.textContent = 'Sin boletos seleccionados';
            }
        }

        // Botón GENERAR PDF: Habilitado cuando se hayan seleccionado boletos
        let generatedInDbForSelectedZones = 0;
        if (Array.isArray(planchaZoneBreakdown)) {
            planchaZoneBreakdown.forEach((zb, idx) => {
                const chk = document.getElementById(`plancha_zone_chk_${idx}`);
                if (chk && chk.checked) {
                    const zKey = cleanZoneBase(zb.name);
                    const zoneExisting = planchaExistingTickets.filter(t => cleanZoneBase(t.zoneName || t.zone_name) === zKey);
                    const input = document.getElementById(`plancha_zone_qty_${idx}`);
                    const qtyWanted = input ? (parseInt(input.value, 10) || 0) : 0;
                    generatedInDbForSelectedZones += Math.min(qtyWanted, zoneExisting.length);
                }
            });
        }

        if (btnText && btnPlancha) {
            if (generatedInDbForSelectedZones > 0) {
                btnText.textContent = isA4 
                    ? `GENERAR PDF A4 (${generatedInDbForSelectedZones} BOLETOS)` 
                    : `GENERAR PLANCHA PDF (${generatedInDbForSelectedZones} BOLETOS)`;
                btnPlancha.disabled = false;
                btnPlancha.style.opacity = '1';
                btnPlancha.style.cursor = 'pointer';
                btnPlancha.style.background = 'linear-gradient(135deg, #2563EB, #1D4ED8)';
                btnPlancha.style.boxShadow = '0 4px 16px rgba(37, 99, 235, 0.4)';
            } else {
                btnPlancha.disabled = true;
                btnPlancha.style.opacity = '0.35';
                btnPlancha.style.cursor = 'not-allowed';
                btnPlancha.style.background = 'rgba(255,255,255,0.06)';
                btnPlancha.style.boxShadow = 'none';
                if (dbCount === 0) {
                    btnText.textContent = 'GENERAR PLANCHA PDF (SIN BOLETOS EN BD)';
                } else {
                    btnText.textContent = 'GENERAR PLANCHA PDF (SIN BOLETOS SELECCIONADOS)';
                }
            }
        }
    }

    /**
     * GENERACIÓN DE PDF (Distribuir / mapear entradas ya registradas en la hoja A4 o Plancha)
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

        if (!Array.isArray(planchaExistingTickets) || planchaExistingTickets.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'No hay entradas disponibles',
                text: 'No se encontraron boletos registrados en el sistema para este evento.',
                confirmButtonColor: '#2563EB',
                background: '#14141E',
                color: '#FFFFFF'
            });
            return;
        }

        const isA4 = selectedPlanchaSizeKey === 'a4';
        const isPlancha65 = selectedPlanchaSizeKey === '65x85';
        const isPlancha60 = selectedPlanchaSizeKey === '60x80';

        let sheetWidthMm = 850;
        let sheetHeightMm = 650;
        let planchaSizeLabel = '65x85cm';
        let perSheet = 24;
        let cols = 4;
        let rows = 6;
        let ticketWidthMm = 196;
        let ticketHeightMm = 94;
        let gapX = 8;
        let gapY = 6;
        let marginX = (850 - (4 * 196 + 3 * 8)) / 2;
        let marginY = (650 - (6 * 94 + 5 * 6)) / 2;

        if (isA4) {
            sheetWidthMm = 297;
            sheetHeightMm = 210;
            planchaSizeLabel = 'A4';
            perSheet = 6;
            cols = 2;
            rows = 3;
            ticketWidthMm = 130;
            ticketHeightMm = 60;
            gapX = 8;
            gapY = 4;
            marginX = (297 - (2 * 130 + 8)) / 2; // 14.5 mm
            marginY = 12; // 12 mm margen superior para el encabezado
        } else if (isPlancha60) {
            sheetWidthMm = 800;
            sheetHeightMm = 600;
            planchaSizeLabel = '60x80cm';
            perSheet = 24;
            cols = 4;
            rows = 6;
            ticketWidthMm = 184;
            ticketHeightMm = 88.3;
            gapX = 8;
            gapY = 6;
            marginX = (800 - (4 * 184 + 3 * 8)) / 2;
            marginY = (600 - (6 * 88.3 + 5 * 6)) / 2;
        }

        // Recolectar las entradas correspondientes a las zonas marcadas
        let candidateTickets = [];

        if (Array.isArray(planchaZoneBreakdown)) {
            planchaZoneBreakdown.forEach((zb, zIdx) => {
                const chk = document.getElementById(`plancha_zone_chk_${zIdx}`);
                if (!chk || !chk.checked) return;

                const qtyInput = document.getElementById(`plancha_zone_qty_${zIdx}`);
                const qtyWanted = qtyInput ? (parseInt(qtyInput.value, 10) || 0) : 0;
                if (qtyWanted <= 0) return;

                // Filtrar las entradas registradas de esta zona
                const zoneTickets = planchaExistingTickets.filter(t => cleanZoneBase(t.zoneName || t.zone_name) === cleanZoneBase(zb.name));
                if (zoneTickets.length === 0) return;
                
                const sliceTickets = zoneTickets.slice(0, qtyWanted);

                sliceTickets.forEach(t => {
                    const zName = t.zoneName || t.zone_name || zb.name;
                    let sCode = t.seatCode || t.seat || '';
                    if (!sCode) {
                        const m = zName.match(/\(([^)]+)\)/);
                        if (m) sCode = m[1].trim();
                    }

                    const isTicketCourtesy = zb.isCourtesy || /cortes[ií]a/i.test(zName) || (t.buyerName && /cortes/i.test(t.buyerName));

                    candidateTickets.push({
                        ticketNumberVal: parseInt(t.ticketNumberVal || t.ticket_number, 10),
                        ticketCode: t.ticketCode || t.ticket_code,
                        zoneName: zName,
                        seatCode: sCode,
                        zonePrice: isTicketCourtesy ? '0.00' : (t.zonePrice || (parseFloat(t.unit_price) || 0).toFixed(2)),
                        validationHash: t.validationHash || t.validation_hash,
                        qrPayload: t.qrPayload || t.qr_payload,
                        buyerName: t.buyerName || t.buyer_name || (isTicketCourtesy ? 'PASE DE CORTESÍA / TAQUILLA' : 'TALONARIO FÍSICO'),
                        buyerDni: t.buyerDni || t.buyer_dni || '00000000',
                        isCourtesy: isTicketCourtesy,
                        isNew: !!t.isNew
                    });
                });
            });
        }

        if (candidateTickets.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin boletos a generar en PDF',
                text: 'No se encontraron boletos registrados para las zonas y cantidades seleccionadas. Recuerda que los boletos se crean automáticamente al configurar el aforo en el evento.',
                confirmButtonColor: '#2563EB',
                background: '#14141E',
                color: '#FFFFFF'
            });
            return;
        }

        // Ordenar correlativamente
        candidateTickets.sort((a, b) => a.ticketNumberVal - b.ticketNumberVal);

        const formatTitle = isA4 ? 'Hoja A4' : `Plancha ${planchaSizeLabel}`;
        const sheetUnitName = isA4 ? 'hoja(s) A4' : 'plancha(s)';

        const minNum = candidateTickets[0].ticketNumberVal;
        const maxNum = candidateTickets[candidateTickets.length - 1].ticketNumberVal;
        const minTicketStr = String(minNum).padStart(5, '0');
        const maxTicketStr = String(maxNum).padStart(5, '0');

        // Confirmar con el usuario permitiendo elegir o ajustar rango de números correlativos
        const confirmRes = await Swal.fire({
            title: `Generar PDF en ${formatTitle}`,
            html: `
                <div style="text-align: left; font-size: 0.82rem; color: #CBD5E1;">
                    <p style="margin: 0 0 0.85rem 0; color: #94A3B8; line-height: 1.4;">
                        Configura el <b>rango de números correlativos</b> que deseas imprimir en esta plancha. Puedes imprimir todo el rango seleccionado o indicar un tramo específico:
                    </p>
                    
                    <div style="background: rgba(255,255,255,0.03); border: 1.5px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0.85rem; margin-bottom: 0.85rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
                            <div>
                                <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #60A5FA; margin-bottom: 0.35rem; text-transform: uppercase;">
                                    🔢 Desde Correlativo
                                </label>
                                <input id="swal_range_start" type="number" class="swal2-input" value="${minNum}" min="${minNum}" max="${maxNum}" 
                                       style="width: 100%; margin: 0; padding: 0.5rem 0.65rem; height: 42px; font-size: 1rem; font-weight: 900; color: #F59E0B; text-align: center; background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(245, 158, 11, 0.4); border-radius: 8px; box-sizing: border-box;" 
                                       oninput="updateSwalRangeSummary()">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #60A5FA; margin-bottom: 0.35rem; text-transform: uppercase;">
                                    🔢 Hasta Correlativo
                                </label>
                                <input id="swal_range_end" type="number" class="swal2-input" value="${maxNum}" min="${minNum}" max="${maxNum}" 
                                       style="width: 100%; margin: 0; padding: 0.5rem 0.65rem; height: 42px; font-size: 1rem; font-weight: 900; color: #F59E0B; text-align: center; background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(245, 158, 11, 0.4); border-radius: 8px; box-sizing: border-box;" 
                                       oninput="updateSwalRangeSummary()">
                            </div>
                        </div>

                        <div style="margin-top: 0.65rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.73rem; color: #94A3B8; border-top: 1px dashed rgba(255,255,255,0.08); padding-top: 0.55rem;">
                            <span>Rango total en selección:</span>
                            <span style="font-family: monospace; font-weight: 800; color: #FFFFFF;">N° ${minTicketStr} → N° ${maxTicketStr} (${candidateTickets.length} disp.)</span>
                        </div>
                    </div>

                    <div id="swal_range_preview_box" style="background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 8px; padding: 0.55rem 0.75rem; font-size: 0.76rem; color: #93C5FD; display: flex; justify-content: space-between; align-items: center;">
                        <span id="swal_range_preview_text">Se imprimirán <b>${candidateTickets.length} boletos</b></span>
                        <span id="swal_range_preview_sheets" style="color: #F59E0B; font-weight: 800;">(${Math.ceil(candidateTickets.length / perSheet)} ${sheetUnitName})</span>
                    </div>

                    <div style="margin-top: 0.6rem; font-size: 0.72rem; color: #10B981; font-weight: 700; text-align: center;">
                        ✓ Códigos QR oficiales y correlativos continuos sincronizados para impresión
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563EB',
            cancelButtonColor: '#475569',
            confirmButtonText: '🖨️ Sí, Compilar y Descargar PDF',
            cancelButtonText: 'Cancelar',
            background: '#14141E',
            color: '#FFFFFF',
            didOpen: () => {
                window.updateSwalRangeSummary = function() {
                    const s = parseInt(document.getElementById('swal_range_start')?.value, 10);
                    const e = parseInt(document.getElementById('swal_range_end')?.value, 10);
                    const previewText = document.getElementById('swal_range_preview_text');
                    const previewSheets = document.getElementById('swal_range_preview_sheets');
                    if (isNaN(s) || isNaN(e) || s > e) {
                        if (previewText) previewText.innerHTML = '<span style="color: #EF4444;">Rango correlativo inválido</span>';
                        if (previewSheets) previewSheets.textContent = '';
                        return;
                    }
                    const count = candidateTickets.filter(t => t.ticketNumberVal >= s && t.ticketNumberVal <= e).length;
                    const sheets = Math.ceil(count / perSheet);
                    if (previewText) previewText.innerHTML = `Se imprimirán <b>${count} boletos</b>`;
                    if (previewSheets) previewSheets.textContent = `(${sheets} ${sheetUnitName})`;
                };
            },
            preConfirm: () => {
                const s = parseInt(document.getElementById('swal_range_start')?.value, 10);
                const e = parseInt(document.getElementById('swal_range_end')?.value, 10);
                if (isNaN(s) || isNaN(e)) {
                    Swal.showValidationMessage('Por favor ingresa números correlativos válidos.');
                    return false;
                }
                if (s > e) {
                    Swal.showValidationMessage('El correlativo inicial no puede ser mayor que el correlativo final.');
                    return false;
                }
                const filtered = candidateTickets.filter(t => t.ticketNumberVal >= s && t.ticketNumberVal <= e);
                if (filtered.length === 0) {
                    Swal.showValidationMessage(`No existen boletos con correlativos entre N° ${s} y N° ${e} en las zonas seleccionadas.`);
                    return false;
                }
                return { start: s, end: e, tickets: filtered };
            }
        });

        if (!confirmRes.isConfirmed || !confirmRes.value || !confirmRes.value.tickets) {
            return;
        }

        const ticketsToPrint = confirmRes.value.tickets;
        const totalSheets = Math.ceil(ticketsToPrint.length / perSheet);

        planchaIsGenerating = true;
        const progressBox = document.getElementById('plancha_render_progress_box');
        const progressStepText = document.getElementById('plancha_progress_step_text');
        const progressBar = document.getElementById('plancha_progress_bar');
        const progressPercent = document.getElementById('plancha_progress_percent');

        if (progressBox) progressBox.style.display = 'block';

        Swal.fire({
            title: `🖨️ Compilando ${formatTitle}...`,
            html: `<div style="margin-top: 0.5rem;"><div style="font-weight: 800; font-size: 1.1rem; color: #60A5FA;" id="plancha_swal_progress">Iniciando motor gráfico...</div><div style="font-size: 0.82rem; color: #94A3B8; margin-top: 0.35rem;">Acomodando boletos en cuadrícula de ${cols}×${rows} (${perSheet} por hoja)...</div></div>`,
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

            const pdf = new jsPdfObj({
                orientation: 'landscape',
                unit: 'mm',
                format: isA4 ? 'a4' : [sheetHeightMm, sheetWidthMm],
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
            const bgColor = (template && template.bg_color) ? template.bg_color : '#FFFFFF';

            const renderWrapper = document.createElement('div');
            renderWrapper.id = 'planchaSingleTicketRender';
            renderWrapper.style.position = 'fixed';
            renderWrapper.style.left = '-9999px';
            renderWrapper.style.top = '0';
            renderWrapper.style.width = '771px';
            renderWrapper.style.height = '370px';
            renderWrapper.style.zIndex = '9999999';
            renderWrapper.style.overflow = 'hidden';
            renderWrapper.style.borderRadius = '18px';
            renderWrapper.style.background = bgColor;
            renderWrapper.style.fontFamily = "'Plus Jakarta Sans', sans-serif";
            renderWrapper.style.boxSizing = 'border-box';
            document.body.appendChild(renderWrapper);

            for (let i = 0; i < ticketsToPrint.length; i++) {
                const tItem = ticketsToPrint[i];
                const currentSheetIdx = Math.floor(i / perSheet);
                const currentSlotIdx = i % perSheet;

                const col = currentSlotIdx % cols;
                const row = Math.floor(currentSlotIdx / cols);

                const posX = marginX + col * (ticketWidthMm + gapX);
                const posY = marginY + row * (ticketHeightMm + gapY);

                if (i > 0 && currentSlotIdx === 0) {
                    pdf.addPage(isA4 ? 'a4' : [sheetHeightMm, sheetWidthMm], 'landscape');
                }

                if (currentSlotIdx === 0) {
                    const firstNum = String(ticketsToPrint[currentSheetIdx * perSheet].ticketNumberVal).padStart(5, '0');
                    const lastNum = String(ticketsToPrint[Math.min((currentSheetIdx + 1) * perSheet - 1, ticketsToPrint.length - 1)].ticketNumberVal).padStart(5, '0');

                    if (isA4) {
                        pdf.setFontSize(7);
                        pdf.setTextColor(148, 163, 184);
                        pdf.text(`VIVEGO • IMPRESIÓN A4 • EVENTO: ${eventTitle.toUpperCase()} • HOJA ${currentSheetIdx + 1} DE ${totalSheets} • BOLETOS N° ${firstNum} AL N° ${lastNum}`, marginX, 7.5);
                    } else {
                        pdf.setFontSize(8.5);
                        pdf.setTextColor(148, 163, 184);
                        pdf.text(`PLANCHA DE IMPRENTA OFICIAL VIVEGO • MEDIDA: ${planchaSizeLabel.toUpperCase()} (${sheetWidthMm}x${sheetHeightMm}mm) • EVENTO: ${eventTitle.toUpperCase()} • PLIEGO ${currentSheetIdx + 1} DE ${totalSheets} • BOLETOS N° ${firstNum} AL N° ${lastNum}`, marginX, 15);
                    }
                }

                const pct = Math.round(((i + 1) / ticketsToPrint.length) * 100);
                const swalText = document.getElementById('plancha_swal_progress');
                if (swalText) {
                    swalText.textContent = `Acomodando boleto ${i + 1} de ${ticketsToPrint.length} (${pct}%)...`;
                }
                if (progressStepText) progressStepText.textContent = `Boleto ${i + 1} de ${ticketsToPrint.length} (${isA4 ? 'Hoja' : 'Pliego'} ${currentSheetIdx + 1}/${totalSheets})`;
                if (progressBar) progressBar.style.width = `${pct}%`;
                if (progressPercent) progressPercent.textContent = `${pct}%`;

                let qrDataUrl = '';
                if (typeof generateQrBase64 === 'function') {
                    qrDataUrl = generateQrBase64(tItem.qrPayload);
                } else if (typeof qrcode !== 'undefined') {
                    const qr = qrcode(0, 'M');
                    qr.addData(tItem.qrPayload);
                    qr.make();
                    qrDataUrl = qr.createDataURL(4, 0);
                }

                const isTicketCourtesy = tItem.isCourtesy || /cortes[ií]a/i.test(tItem.zoneName) || (tItem.buyerName && /cortes/i.test(tItem.buyerName));
                const dynamicData = {
                    title: eventTitle,
                    venue: eventVenue,
                    city: eventAddress,
                    date: eventDate,
                    time: eventTime,
                    zone: tItem.zoneName,
                    seat: tItem.seatCode || '',
                    price: isTicketCourtesy ? 'S/ 0.00 (CORTESÍA)' : ('S/ ' + tItem.zonePrice),
                    buyer_name: isTicketCourtesy ? 'PASE DE CORTESÍA' : '',
                    buyer_dni: '',
                    is_plancha_print: true,
                    ticket_number: tItem.ticketCode,
                    hash: tItem.validationHash,
                    qr_data_url: qrDataUrl
                };

                let canvasHtml = '';
                if (typeof renderPlanchaTicketCanvasContent === 'function') {
                    canvasHtml = renderPlanchaTicketCanvasContent(template, dynamicData, assetMap);
                } else if (typeof renderTicketCanvasContent === 'function') {
                    canvasHtml = renderTicketCanvasContent(template, dynamicData, assetMap);
                } else {
                    canvasHtml = `
                        <div style="position: relative; width: 100%; height: 100%; padding: 1.25rem; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; background: #FFFFFF; color: #1E293B; border-radius: 18px; border: 1.5px solid #CBD5E1;">
                            <div>
                                <h3 style="font-size: 18px; font-weight: 900; margin: 0 0 4px 0;">${eventTitle}</h3>
                                <p style="font-size: 13px; color: ${isTicketCourtesy ? '#10B981' : '#FF5500'}; font-weight: 800; margin: 0 0 6px 0;">ZONA: ${tItem.zoneName} • PRECIO: ${isTicketCourtesy ? 'S/ 0.00 (CORTESÍA)' : 'S/ ' + tItem.zonePrice}</p>
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

                const canvas = await html2canvas(renderWrapper, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#FFFFFF',
                    logging: false
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);
                pdf.addImage(imgData, 'JPEG', posX, posY, ticketWidthMm, ticketHeightMm, undefined, 'FAST');

                const cutRadius = isA4 ? 2.5 : 3.5;
                pdf.setDrawColor(180, 190, 205);
                pdf.setLineWidth(0.2);
                pdf.roundedRect(posX, posY, ticketWidthMm, ticketHeightMm, cutRadius, cutRadius, 'S');
            }

            if (renderWrapper && renderWrapper.parentNode) {
                renderWrapper.parentNode.removeChild(renderWrapper);
            }

            const safeEventName = (eventTitle || 'Evento').replace(/[^a-zA-Z0-9_\-]/g, '_');
            const fileName = `Plancha_${planchaSizeLabel}_${safeEventName}_Total_${ticketsToPrint.length}.pdf`;
            pdf.save(fileName);

            planchaIsGenerating = false;
            if (progressBox) progressBox.style.display = 'none';
            if (progressBar) progressBar.style.width = '0%';
            if (progressPercent) progressPercent.textContent = '0%';
            if (progressStepText) progressStepText.textContent = 'Compilando boletos...';

            closePlanchaModal();

            // Limpiar flags temporales para que el modal quede 100% limpio como al refrescar la página
            if (Array.isArray(planchaExistingTickets)) {
                planchaExistingTickets.forEach(t => { t.isNew = false; });
            }
            newlyEmittedTicketsCache = [];

            Swal.fire({
                icon: 'success',
                title: `🎉 ¡${isA4 ? 'PDF A4' : 'Plancha'} Generada con Éxito!`,
                html: `Se descargó el archivo en formato <b>${isA4 ? 'Hoja A4 (297×210 mm)' : planchaSizeLabel}</b> con <b>${ticketsToPrint.length} boletos</b> en ${totalSheets} ${sheetUnitName}.<br><br><span style="color: #10B981; font-weight: 700;">✓ Boletos impresos sincronizados con la base de datos.</span>`,
                confirmButtonColor: '#2563EB',
                confirmButtonText: 'Excelente',
                background: '#14141E',
                color: '#FFFFFF'
            });

        } catch (err) {
            console.error('Error generando PDF:', err);
            planchaIsGenerating = false;
            if (progressBox) progressBox.style.display = 'none';
            if (progressBar) progressBar.style.width = '0%';
            if (progressPercent) progressPercent.textContent = '0%';
            if (progressStepText) progressStepText.textContent = 'Compilando boletos...';

            Swal.fire({
                icon: 'error',
                title: 'Error al Generar PDF',
                text: err.message || 'Ocurrió un error en la compilación gráfica.',
                confirmButtonColor: '#EF4444',
                background: '#14141E',
                color: '#FFFFFF'
            });
        } finally {
            planchaIsGenerating = false;
            const pb = document.getElementById('plancha_render_progress_box');
            if (pb) pb.style.display = 'none';
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
