<script>
    /**
     * =========================================================================
     * CONSTRUCTOR VISUAL DE ZONAS Y BUTACAS VECTORIALES (ESTILO ELEMENTOR + CANVA)
     * Vectorización Real de Píxeles Fiel a la Imagen
     * Eliminación de Vértices Individuales con Selección Verde y Tecla Supr
     * Puntos y Líneas Punteadas Ultra-Finas al Hacer Zoom + Herramienta Escenario
     * =========================================================================
     */
    const SeatMapEditor = {
        zones: [],
        selectedZoneId: null,
        selectedPointIdx: null,
        currentTool: 'select',
        zoom: 1.0,
        pan: { x: 0, y: 0 },
        isPanning: false,
        panStart: { x: 0, y: 0 },
        drawingPoints: [],
        rectStartPoint: null,
        draggingHandle: null,
        isDraggingZone: false,
        dragZoneInfo: null,
        isTransformingGizmo: false,
        gizmoTransformInfo: null,
        isBuyerPreview: false,
        bgOpacity: 0.85,
        viewportWidth: 1000,
        viewportHeight: 650,

        init: function() {
            this.loadInitialZonesFromDom();
            this.render();
            this.updateImageBadge();
            this.bindGlobalShortcuts();
            this.bindContextMenuDismiss();
        },

        bindContextMenuDismiss: function() {
            document.addEventListener('click', () => this.hideContextMenu());
            document.addEventListener('contextmenu', (e) => {
                if (!e.target.closest('.svg-zone-polygon')) {
                    this.hideContextMenu();
                }
            });
        },

        bindGlobalShortcuts: function() {
            window.addEventListener('keydown', (e) => {
                const activeTag = document.activeElement?.tagName;
                const isInput = ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeTag) || document.activeElement?.isContentEditable;
                
                if (!isInput && (e.key === 'Delete' || e.key === 'Backspace' || e.key === 'Del' || e.keyCode === 46)) {
                    if (this.selectedPointIdx !== null && this.selectedZoneId) {
                        e.preventDefault();
                        this.deleteSelectedPoint();
                        return;
                    }

                    if (this.selectedZoneId) {
                        e.preventDefault();
                        this.deleteSelectedZone();
                    }
                }

                if (this.currentTool === 'polygon' && this.drawingPoints.length > 0) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.finishPolygonDrawing();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        this.cancelPolygonDrawing();
                    }
                }
            });
        },

        deleteSelectedPoint: function() {
            const z = this.getSelectedZone();
            if (!z || !Array.isArray(z.points) || this.selectedPointIdx === null) return;

            if (z.points.length <= 3) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Mínimo 3 Vértices',
                        text: 'Un polígono debe tener al menos 3 puntos. Si deseas borrar todo el sector, pulsa Supr sin seleccionar un punto.',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
                return;
            }

            z.points.splice(this.selectedPointIdx, 1);
            this.selectedPointIdx = null;
            this.render();
            this.syncToStandardTable();
        },

        loadInitialZonesFromDom: function() {
            const phpInitialZones = @json($eventData['zones'] ?? ($zones ?? []));
            const defaultColors = ['#10B981', '#2563EB', '#38BDF8', '#78350F', '#F59E0B', '#EC4899', '#8B5CF6', '#FF5500'];
            
            let hasInteractiveZones = false;
            this.zones = [];

            if (Array.isArray(phpInitialZones) && phpInitialZones.length > 0) {
                phpInitialZones.forEach((pz, idx) => {
                    const hasPts = Array.isArray(pz.points) && pz.points.length >= 3;
                    if (hasPts) hasInteractiveZones = true;

                    const yOffset = 70 + (idx * 105);
                    const defaultPoints = [
                        { x: 348, y: yOffset },
                        { x: 652, y: yOffset },
                        { x: 652, y: yOffset + 80 },
                        { x: 348, y: yOffset + 80 }
                    ];

                    let sRows = parseInt(pz.seat_rows || pz.rows) || null;
                    let sCols = parseInt(pz.seat_cols || pz.cols) || null;
                    if ((!sRows || !sCols) && Array.isArray(pz.seats) && pz.seats.length > 0) {
                        const uniqueR = new Set(pz.seats.map(s => s.row).filter(Boolean));
                        const uniqueC = new Set(pz.seats.map(s => s.col).filter(Boolean));
                        if (uniqueR.size > 0) sRows = uniqueR.size;
                        if (uniqueC.size > 0) sCols = uniqueC.size;
                    }

                    this.zones.push({
                        id: pz.id || ('zone_' + Date.now() + '_' + idx),
                        name: pz.name || ('Zona ' + (idx + 1)),
                        capacity: parseInt(pz.capacity) || 100,
                        price: parseFloat(pz.price) || 50,
                        capacity_type: pz.capacity_type || 'Aforo General',
                        color: pz.color || defaultColors[idx % defaultColors.length],
                        presale_enabled: !!(pz.has_presale || pz.presale_enabled),
                        presale_discount: parseFloat(pz.presale_discount) || 20,
                        presale_price: parseFloat(pz.presale_price) || null,
                        presale_start_date: pz.presale_start_date || null,
                        presale_end_date: pz.presale_end_date || null,
                        presale_stock: parseInt(pz.presale_stock) || null,
                        points: hasPts ? pz.points : defaultPoints,
                        seats: Array.isArray(pz.seats) ? pz.seats : [],
                        seat_rows: sRows,
                        seat_cols: sCols,
                        rows: sRows,
                        cols: sCols,
                        seat_row_type: pz.seat_row_type || pz.row_type || 'letters',
                        seat_col_type: pz.seat_col_type || pz.col_type || 'numbers',
                        row_type: pz.seat_row_type || pz.row_type || 'letters',
                        col_type: pz.seat_col_type || pz.col_type || 'numbers'
                    });
                });
            } else {
                const rows = document.querySelectorAll('#zonesTableBody .zone-row');
                if (rows.length > 0) {
                    rows.forEach((row, idx) => {
                        const capType = row.querySelector('.zone-capacity-type')?.value || 'Aforo General';
                        const name = row.querySelector('.zone-name-input')?.value?.trim() || capType;
                        const cap = parseInt(row.querySelector('.zone-capacity-input')?.value) || 100;
                        const price = parseFloat(row.querySelector('.zone-price-input')?.value) || 50;
                        
                        const presaleRow = row.nextElementSibling;
                        const isPresale = presaleRow?.querySelector('.zone-presale-enabled')?.checked || false;
                        const presaleDisc = parseFloat(presaleRow?.querySelector('.zone-presale-discount')?.value) || 20;
                        const presaleStart = presaleRow?.querySelector('.zone-presale-start')?.value || null;
                        const presaleEnd = presaleRow?.querySelector('.zone-presale-end')?.value || null;
                        const presaleStock = parseInt(presaleRow?.querySelector('.zone-presale-stock')?.value) || null;

                        const yOffset = 70 + (idx * 105);
                        const defaultPoints = [
                            { x: 348, y: yOffset },
                            { x: 652, y: yOffset },
                            { x: 652, y: yOffset + 80 },
                            { x: 348, y: yOffset + 80 }
                        ];

                        this.zones.push({
                            id: 'zone_' + Date.now() + '_' + idx,
                            name: name,
                            capacity: cap,
                            price: price,
                            capacity_type: capType,
                            color: defaultColors[idx % defaultColors.length],
                            presale_enabled: isPresale,
                            presale_discount: presaleDisc,
                            presale_start_date: presaleStart,
                            presale_end_date: presaleEnd,
                            presale_stock: presaleStock,
                            points: defaultPoints,
                            seats: []
                        });
                    });
                }
            }

            this.selectedZoneId = this.zones.length > 0 ? this.zones[0].id : null;
            this.selectedPointIdx = null;

            // Determinar modo activo según zonas guardadas
            if (hasInteractiveZones) {
                window.currentStep2ZoneMode = 'interactive';
                switchStep2Mode('interactive');
            } else {
                window.currentStep2ZoneMode = 'standard';
                switchStep2Mode('standard');
            }
        },

        syncToStandardTable: function() {
            const tbody = document.getElementById('zonesTableBody');
            if (!tbody) return;

            if (this.zones && this.zones.length > 0) {
                tbody.innerHTML = '';
                const todayStr = new Date().toISOString().split('T')[0];
                const futureDate = new Date();
                futureDate.setDate(futureDate.getDate() + 15);
                const futureStr = futureDate.toISOString().split('T')[0];

                const escapeStr = (str) => {
                    const p = document.createElement('p');
                    p.textContent = str || '';
                    return p.innerHTML;
                };

                this.zones.forEach((z) => {
                    const row = document.createElement('tr');
                    row.className = 'zone-row';
                    row.innerHTML = `
                        <td>
                            <select class="form-select-custom zone-capacity-type" style="font-size: 0.85rem; padding: 0.55rem;">
                                <option value="Aforo VIP" ${z.capacity_type === 'Aforo VIP' ? 'selected' : ''}>🏟️ Aforo VIP</option>
                                <option value="Aforo Preferencial" ${z.capacity_type === 'Aforo Preferencial' ? 'selected' : ''}>🏟️ Aforo Preferencial</option>
                                <option value="Aforo General" ${(!z.capacity_type || z.capacity_type === 'Aforo General') ? 'selected' : ''}>🏟️ Aforo General</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-input-custom zone-name-input" value="${escapeStr(z.name || '')}" style="font-size: 0.85rem; padding: 0.55rem;" oninput="if(typeof syncCourtesyZonesTable==='function') syncCourtesyZonesTable()">
                        </td>
                        <td>
                            <input type="number" class="form-input-custom zone-capacity-input" value="${z.capacity || 100}" min="1" style="font-size: 0.85rem; padding: 0.55rem;" oninput="if(typeof recalculateTotalCapacity==='function') recalculateTotalCapacity(); if(typeof syncCourtesyZonesTable==='function') syncCourtesyZonesTable();">
                        </td>
                        <td>
                            <input type="number" step="0.50" class="form-input-custom zone-price-input" value="${(parseFloat(z.price) || 0).toFixed(2)}" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;" oninput="if(typeof updateZonePresaleCalc==='function') updateZonePresaleCalc(this); if(typeof recalculateTotalCapacity==='function') recalculateTotalCapacity(); if(typeof syncCourtesyZonesTable==='function') syncCourtesyZonesTable();">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-toggle-presale" style="background: rgba(255,85,0,0.15); border: 1.5px solid #FF5500; color: #FF5500; font-size: 0.775rem; font-weight: 800; padding: 0.45rem 0.65rem; border-radius: 8px; width: 100%; text-align: center;" onclick="if(typeof toggleZonePresaleBox==='function') toggleZonePresaleBox(this)">
                                🔥 Configurar
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="if(typeof removeZoneRow==='function') removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                        </td>
                    `;

                    const presaleRow = document.createElement('tr');
                    presaleRow.className = 'zone-presale-row';
                    presaleRow.style.display = z.presale_enabled ? 'table-row' : 'none';
                    presaleRow.style.background = 'rgba(255, 85, 0, 0.03)';
                    const discPrice = z.price ? (z.price * (1 - (z.presale_discount || 0) / 100)) : 0;

                    presaleRow.innerHTML = `
                        <td colspan="6" style="padding: 0.85rem 1.25rem; border-bottom: 1.5px solid rgba(255,85,0,0.25);">
                            <div style="background: rgba(15,23,42,0.8); border: 1.5px dashed rgba(255,85,0,0.4); border-radius: 12px; padding: 1rem 1.25rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0;">
                                        <input type="checkbox" class="zone-presale-enabled" ${z.presale_enabled ? 'checked' : ''} onchange="if(typeof togglePresaleInputs==='function') togglePresaleInputs(this)" style="accent-color: #FF5500; width: 18px; height: 18px;">
                                        <strong style="color: #FF5500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">🔥 Activar Preventa para esta Zona</strong>
                                    </label>
                                    <span class="presale-preview-badge" style="font-size: 0.75rem; font-weight: 800; color: ${z.presale_enabled ? '#38BDF8' : '#94A3B8'}; background: rgba(255,255,255,0.08); padding: 3px 10px; border-radius: 6px;">
                                        ${z.presale_enabled ? 'Preventa Activa' : 'Preventa Inactiva'}
                                    </span>
                                </div>
                                <div class="zone-presale-inputs-grid" style="display: grid; grid-template-columns: 1fr 1.2fr 1.5fr 1.5fr 1.2fr; gap: 0.75rem; ${z.presale_enabled ? '' : 'opacity: 0.4; pointer-events: none;'}">
                                    <div>
                                        <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">% DESCUENTO</label>
                                        <input type="number" class="form-input-custom zone-presale-discount" value="${z.presale_discount || 20}" min="0" max="99" style="font-size: 0.825rem; padding: 0.45rem;" oninput="if(typeof updateZonePresaleCalc==='function') updateZonePresaleCalc(this)">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">PRECIO PREVENTA (S/)</label>
                                        <input type="number" step="0.50" class="form-input-custom zone-presale-price" value="${discPrice.toFixed(2)}" min="0" style="font-size: 0.825rem; padding: 0.45rem; color: #38BDF8; font-weight: 800;" readonly>
                                    </div>
                                    <div>
                                        <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">FECHA INICIO</label>
                                        <input type="date" class="form-input-custom zone-presale-start" value="${z.presale_start_date || todayStr}" style="font-size: 0.825rem; padding: 0.45rem;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">FECHA FIN (LÍMITE)</label>
                                        <input type="date" class="form-input-custom zone-presale-end" value="${z.presale_end_date || futureStr}" style="font-size: 0.825rem; padding: 0.45rem;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">STOCK PREVENTA</label>
                                        <input type="number" class="form-input-custom zone-presale-stock" value="${z.presale_stock || ''}" min="0" style="font-size: 0.825rem; padding: 0.45rem;" placeholder="Hasta agotar">
                                    </div>
                                </div>
                            </div>
                        </td>
                    `;

                    tbody.appendChild(row);
                    tbody.appendChild(presaleRow);
                });
            }

            if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
            if (typeof syncCourtesyZonesTable === 'function') syncCourtesyZonesTable();
        },

        syncFromStandardTable: function() {
            const rows = document.querySelectorAll('#zonesTableBody .zone-row');
            if (rows.length === 0) return;

            const defaultColors = ['#10B981', '#2563EB', '#38BDF8', '#78350F', '#F59E0B', '#EC4899', '#8B5CF6', '#FF5500'];
            const updatedZones = [];

            rows.forEach((row, idx) => {
                const capType = row.querySelector('.zone-capacity-type')?.value || 'Aforo General';
                const name = row.querySelector('.zone-name-input')?.value?.trim() || ('Zona ' + (idx + 1));
                const cap = parseInt(row.querySelector('.zone-capacity-input')?.value) || 100;
                const price = parseFloat(row.querySelector('.zone-price-input')?.value) || 50;
                
                const presaleRow = row.nextElementSibling;
                const isPresale = presaleRow?.querySelector('.zone-presale-enabled')?.checked || false;
                const presaleDisc = parseFloat(presaleRow?.querySelector('.zone-presale-discount')?.value) || 20;
                const presaleStart = presaleRow?.querySelector('.zone-presale-start')?.value || null;
                const presaleEnd = presaleRow?.querySelector('.zone-presale-end')?.value || null;
                const presaleStock = parseInt(presaleRow?.querySelector('.zone-presale-stock')?.value) || null;

                const existing = this.zones ? this.zones.find(ez => ez.name === name || ez.id === ('zone_' + idx)) : null;

                const yOffset = 70 + (idx * 105);
                const defaultPoints = [
                    { x: 348, y: yOffset },
                    { x: 652, y: yOffset },
                    { x: 652, y: yOffset + 80 },
                    { x: 348, y: yOffset + 80 }
                ];

                updatedZones.push({
                    id: existing ? existing.id : ('zone_' + Date.now() + '_' + idx),
                    name: name,
                    capacity: cap,
                    price: price,
                    capacity_type: capType,
                    color: existing ? existing.color : defaultColors[idx % defaultColors.length],
                    presale_enabled: isPresale,
                    presale_discount: presaleDisc,
                    presale_start_date: presaleStart,
                    presale_end_date: presaleEnd,
                    presale_stock: presaleStock,
                    points: (existing && Array.isArray(existing.points) && existing.points.length >= 3) ? existing.points : defaultPoints,
                    seats: existing ? (existing.seats || []) : [],
                    seat_rows: existing ? (existing.seat_rows || existing.rows) : null,
                    seat_cols: existing ? (existing.seat_cols || existing.cols) : null,
                    rows: existing ? (existing.rows || existing.seat_rows) : null,
                    cols: existing ? (existing.cols || existing.seat_cols) : null,
                    seat_row_type: existing ? (existing.seat_row_type || existing.row_type) : null,
                    seat_col_type: existing ? (existing.seat_col_type || existing.col_type) : null,
                    row_type: existing ? (existing.row_type || existing.seat_row_type) : null,
                    col_type: existing ? (existing.col_type || existing.seat_col_type) : null
                });
            });

            this.zones = updatedZones;
            if (!this.selectedZoneId && this.zones.length > 0) {
                this.selectedZoneId = this.zones[0].id;
            }
        },

        getExportZones: function() {
            return (this.zones || []).map(z => ({
                id: z.id,
                name: z.name || 'Zona',
                capacity_type: z.capacity_type || 'Aforo General',
                capacity: parseInt(z.capacity) || 0,
                price: parseFloat(z.price) || 0,
                color: z.color || '#FF5500',
                points: Array.isArray(z.points) ? z.points : [],
                has_presale: !!z.presale_enabled,
                presale_discount: parseFloat(z.presale_discount) || 0,
                presale_price: z.price ? parseFloat((z.price * (1 - (parseFloat(z.presale_discount) || 0) / 100)).toFixed(2)) : 0,
                presale_start_date: z.presale_start_date || null,
                presale_end_date: z.presale_end_date || null,
                presale_stock: parseInt(z.presale_stock) || null,
                seats: Array.isArray(z.seats) ? z.seats : [],
                seat_rows: parseInt(z.seat_rows || z.rows) || null,
                seat_cols: parseInt(z.seat_cols || z.cols) || null,
                rows: parseInt(z.seat_rows || z.rows) || null,
                cols: parseInt(z.seat_cols || z.cols) || null,
                seat_row_type: z.seat_row_type || z.row_type || null,
                seat_col_type: z.seat_col_type || z.col_type || null,
                row_type: z.seat_row_type || z.row_type || null,
                col_type: z.seat_col_type || z.col_type || null
            }));
        },

        setTool: function(toolName) {
            this.currentTool = toolName;
            this.drawingPoints = [];
            this.rectStartPoint = null;
            this.selectedPointIdx = null;

            document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById('toolBtn' + toolName.charAt(0).toUpperCase() + toolName.slice(1));
            if (activeBtn) activeBtn.classList.add('active');

            const indicator = document.getElementById('canvasModeIndicator');
            const polygonActionPill = document.getElementById('polygonActionPill');
            const viewport = document.getElementById('seatMapViewport');

            if (toolName === 'select') {
                if (indicator) {
                    indicator.innerHTML = '🖐️ Modo Selección / Mover';
                    indicator.className = 'dash-badge-custom badge-blue';
                }
                if (polygonActionPill) polygonActionPill.style.display = 'none';
                if (viewport) viewport.style.cursor = 'default';
            } else if (toolName === 'polygon') {
                if (indicator) {
                    indicator.innerHTML = '📐 Trazando Polígono';
                    indicator.className = 'dash-badge-custom badge-orange';
                }
                if (polygonActionPill) polygonActionPill.style.display = 'inline-flex';
                if (viewport) viewport.style.cursor = 'crosshair';
            } else if (toolName === 'rect') {
                if (indicator) {
                    indicator.innerHTML = '⏹️ Trazando Rectángulo';
                    indicator.className = 'dash-badge-custom badge-orange';
                }
                if (polygonActionPill) polygonActionPill.style.display = 'none';
                if (viewport) viewport.style.cursor = 'crosshair';
            }

            this.render();
        },

        showContextMenu: function(e, zoneId) {
            this.selectedZoneId = zoneId;
            this.selectedPointIdx = null;
            this.render();

            const zone = this.getSelectedZone();
            if (!zone) return;

            const menu = document.getElementById('zoneContextMenu');
            const nameEl = document.getElementById('ctxMenuZoneName');
            const subEl = document.getElementById('ctxMenuZoneSub');

            if (nameEl) nameEl.textContent = zone.name;
            if (subEl) subEl.textContent = `${zone.capacity_type} • S/ ${zone.price.toFixed(2)}`;

            if (menu) {
                menu.style.display = 'block';
                let x = e.clientX;
                let y = e.clientY;

                const menuW = 230;
                const menuH = 260;
                if (x + menuW > window.innerWidth) x = window.innerWidth - menuW - 10;
                if (y + menuH > window.innerHeight) y = window.innerHeight - menuH - 10;

                menu.style.left = `${x}px`;
                menu.style.top = `${y}px`;
            }
        },

        hideContextMenu: function() {
            const menu = document.getElementById('zoneContextMenu');
            if (menu) menu.style.display = 'none';
        },

        autoVectorizeSelectedSingleZone: function() {
            this.hideContextMenu();
            const z = this.getSelectedZone();
            if (!z) return;

            const refImgSrc = document.getElementById('reference_image')?.value || document.getElementById('seatMapBgImage')?.src;
            if (!refImgSrc) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin Plano Cargado',
                        text: 'Sube una imagen de plano para poder calcar esta zona con visión artificial.',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
                return;
            }

            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => {
                try {
                    const containerW = 1000;
                    const containerH = 650;
                    const natW = img.naturalWidth || img.width || 500;
                    const natH = img.naturalHeight || img.height || 650;
                    const imgAspect = natW / natH;
                    const containerAspect = containerW / containerH;

                    let renderW, renderH, offsetX, offsetY;
                    if (imgAspect > containerAspect) {
                        renderW = containerW;
                        renderH = containerW / imgAspect;
                        offsetX = 0;
                        offsetY = (containerH - renderH) / 2;
                    } else {
                        renderH = containerH;
                        renderW = containerH * imgAspect;
                        offsetX = (containerW - renderW) / 2;
                        offsetY = 0;
                    }

                    const xs = z.points.map(p => p.x);
                    const ys = z.points.map(p => p.y);
                    const minX = Math.min(...xs);
                    const maxX = Math.max(...xs);
                    const minY = Math.min(...ys);
                    const maxY = Math.max(...ys);

                    const scanW = 320;
                    const scanH = Math.round(320 * (natH / natW));
                    const canvas = document.createElement('canvas');
                    canvas.width = scanW;
                    canvas.height = scanH;
                    const ctx = canvas.getContext('2d', { willReadFrequently: true });
                    ctx.drawImage(img, 0, 0, scanW, scanH);
                    const imgData = ctx.getImageData(0, 0, scanW, scanH);
                    const data = imgData.data;

                    const scanMinX = Math.max(0, Math.round(((minX - offsetX) / renderW) * scanW));
                    const scanMaxX = Math.min(scanW - 1, Math.round(((maxX - offsetX) / renderW) * scanW));
                    const scanMinY = Math.max(0, Math.round(((minY - offsetY) / renderH) * scanH));
                    const scanMaxY = Math.min(scanH - 1, Math.round(((maxY - offsetY) / renderH) * scanH));

                    const pixels = [];
                    let sumR = 0, sumG = 0, sumB = 0;

                    for (let y = scanMinY; y <= scanMaxY; y++) {
                        for (let x = scanMinX; x <= scanMaxX; x++) {
                            const idx = (y * scanW + x) * 4;
                            const r = data[idx], g = data[idx + 1], b = data[idx + 2], a = data[idx + 3];

                            if (a < 50 || (r > 230 && g > 230 && b > 230) || (r < 32 && g < 32 && b < 32)) continue;

                            const bucketId = this.classifyPixelColor(r, g, b);
                            if (bucketId > 0) {
                                pixels.push(y * scanW + x);
                                sumR += r;
                                sumG += g;
                                sumB += b;
                            }
                        }
                    }

                    if (pixels.length > 20) {
                        const blob = {
                            pixels: pixels,
                            avgColor: {
                                r: Math.round(sumR / pixels.length),
                                g: Math.round(sumG / pixels.length),
                                b: Math.round(sumB / pixels.length)
                            }
                        };
                        const fittedZone = this.fitGeometricShapeToBlob(blob, scanW, scanH, offsetX, offsetY, renderW, renderH, containerW, containerH, 0);
                        if (fittedZone && fittedZone.points && fittedZone.points.length >= 3) {
                            z.points = fittedZone.points;
                            z.color = fittedZone.color;
                            this.render();
                            this.syncToStandardTable();
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: '⚡ Zona Calcada de la Imagen',
                                    text: `Se extrajo el contorno real de ${z.name} directamente desde el plano.`,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    background: '#14141E',
                                    color: '#FFFFFF'
                                });
                            }
                        }
                    }
                } catch (err) {
                    console.warn('Error al auto-vectorizar zona individual:', err);
                }
            };
            img.src = refImgSrc;
        },

        convertSelectedZoneToRect: function() {
            this.hideContextMenu();
            const z = this.getSelectedZone();
            if (!z || !z.points || z.points.length < 3) return;

            const xs = z.points.map(p => p.x);
            const ys = z.points.map(p => p.y);
            const minX = Math.min(...xs);
            const maxX = Math.max(...xs);
            const minY = Math.min(...ys);
            const maxY = Math.max(...ys);

            z.points = [
                { x: minX, y: minY },
                { x: maxX, y: minY },
                { x: maxX, y: maxY },
                { x: minX, y: maxY }
            ];

            this.render();
            this.syncToStandardTable();
        },

        convertSelectedZoneToArc: function() {
            this.hideContextMenu();
            const z = this.getSelectedZone();
            if (!z || !z.points || z.points.length < 3) return;

            const xs = z.points.map(p => p.x);
            const ys = z.points.map(p => p.y);
            const minX = Math.min(...xs);
            const maxX = Math.max(...xs);
            const minY = Math.min(...ys);
            const cx = (minX + maxX) / 2;
            const w = maxX - minX;
            const innerR = Math.max(30, w * 0.38);
            const outerR = w * 0.52;

            z.points = this.createCurvedArcPoints(cx, minY - 25, innerR, outerR, 8, 172, 14);

            this.render();
            this.syncToStandardTable();
        },

        convertSelectedZoneToLateral: function() {
            this.hideContextMenu();
            const z = this.getSelectedZone();
            if (!z || !z.points || z.points.length < 3) return;

            const xs = z.points.map(p => p.x);
            const ys = z.points.map(p => p.y);
            const minX = Math.min(...xs);
            const maxX = Math.max(...xs);
            const minY = Math.min(...ys);
            const maxY = Math.max(...ys);
            const cy = (minY + maxY) / 2;
            const isLeft = (minX < 450);

            z.points = [
                { x: minX, y: minY },
                { x: maxX, y: minY },
                { x: isLeft ? maxX : Math.round(maxX + 12), y: cy },
                { x: maxX, y: maxY },
                { x: minX, y: maxY },
                { x: isLeft ? Math.round(minX - 12) : minX, y: cy }
            ];

            this.render();
            this.syncToStandardTable();
        },

        autoVectorize: function() {
            const refImgSrc = document.getElementById('reference_image')?.value || document.getElementById('seatMapBgImage')?.src;
            
            if (!refImgSrc) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin Plano Cargado',
                        text: 'Sube una imagen de tu plano para auto-detectar y vectorizar las zonas.',
                        background: '#14141E',
                        color: '#FFFFFF'
                    });
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '⚡ Analizando Plano...',
                    text: 'Extrayendo y calcando los contornos reales del plano...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); },
                    background: '#14141E',
                    color: '#FFFFFF'
                });
            }

            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => {
                try {
                    const detectedZones = this.processImageAndExtractZones(img);
                    if (detectedZones && detectedZones.length > 0) {
                        this.zones = detectedZones;
                        this.selectedZoneId = this.zones[0]?.id || null;
                        this.selectedPointIdx = null;
                        this.setTool('select');
                        this.render();
                        this.syncToStandardTable();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: `⚡ ¡${detectedZones.length} Zonas Calcadas de la Imagen!`,
                                text: 'Se extrajeron los contornos reales directamente de los sectores de tu imagen.',
                                timer: 2800,
                                showConfirmButton: false,
                                background: '#14141E',
                                color: '#FFFFFF'
                            });
                        }
                    }
                } catch (err) {
                    console.warn('Error en vectorización por computadora:', err);
                }
            };
            img.src = refImgSrc;
        },

        processImageAndExtractZones: function(img) {
            const containerW = 1000;
            const containerH = 650;
            const natW = img.naturalWidth || img.width || 500;
            const natH = img.naturalHeight || img.height || 650;
            const imgAspect = natW / natH;
            const containerAspect = containerW / containerH;

            let renderW, renderH, offsetX, offsetY;
            if (imgAspect > containerAspect) {
                renderW = containerW;
                renderH = containerW / imgAspect;
                offsetX = 0;
                offsetY = (containerH - renderH) / 2;
            } else {
                renderH = containerH;
                renderW = containerH * imgAspect;
                offsetX = (containerW - renderW) / 2;
                offsetY = 0;
            }

            const scanW = 320;
            const scanH = Math.round(320 * (natH / natW));
            const canvas = document.createElement('canvas');
            canvas.width = scanW;
            canvas.height = scanH;
            const ctx = canvas.getContext('2d', { willReadFrequently: true });
            ctx.drawImage(img, 0, 0, scanW, scanH);
            const imgData = ctx.getImageData(0, 0, scanW, scanH);
            const data = imgData.data;

            const colorMap = new Int16Array(scanW * scanH);
            const colorBuckets = {};

            for (let y = 0; y < scanH; y++) {
                for (let x = 0; x < scanW; x++) {
                    const idx = (y * scanW + x) * 4;
                    const r = data[idx], g = data[idx + 1], b = data[idx + 2], a = data[idx + 3];

                    if (a < 50 || (r > 230 && g > 230 && b > 230) || (r < 32 && g < 32 && b < 32)) {
                        colorMap[y * scanW + x] = 0;
                        continue;
                    }

                    const bucketId = this.classifyPixelColor(r, g, b);
                    if (bucketId > 0) {
                        colorMap[y * scanW + x] = bucketId;
                        if (!colorBuckets[bucketId]) colorBuckets[bucketId] = { count: 0, r: 0, g: 0, b: 0 };
                        colorBuckets[bucketId].count++;
                        colorBuckets[bucketId].r += r;
                        colorBuckets[bucketId].g += g;
                        colorBuckets[bucketId].b += b;
                    } else {
                        colorMap[y * scanW + x] = 0;
                    }
                }
            }

            const visited = new Uint8Array(scanW * scanH);
            const minBlobSize = Math.max(100, Math.round(scanW * scanH * 0.012));
            const rawBlobs = [];

            for (let y = 0; y < scanH; y++) {
                for (let x = 0; x < scanW; x++) {
                    const pIdx = y * scanW + x;
                    const colorId = colorMap[pIdx];

                    if (colorId > 0 && !visited[pIdx]) {
                        const queue = [pIdx];
                        visited[pIdx] = 1;
                        const blobPixels = [];
                        let sumR = 0, sumG = 0, sumB = 0;

                        let head = 0;
                        while(head < queue.length) {
                            const cur = queue[head++];
                            blobPixels.push(cur);
                            const cx = cur % scanW;
                            const cy = Math.floor(cur / scanW);
                            const dataIdx = cur * 4;
                            sumR += data[dataIdx];
                            sumG += data[dataIdx + 1];
                            sumB += data[dataIdx + 2];

                            const neighbors = [
                                cy > 0 ? cur - scanW : -1,
                                cy < scanH - 1 ? cur + scanW : -1,
                                cx > 0 ? cur - 1 : -1,
                                cx < scanW - 1 ? cur + 1 : -1
                            ];

                            for (const n of neighbors) {
                                if (n >= 0 && !visited[n] && colorMap[n] === colorId) {
                                    visited[n] = 1;
                                    queue.push(n);
                                }
                            }
                        }

                        if (blobPixels.length >= minBlobSize) {
                            rawBlobs.push({
                                colorId: colorId,
                                size: blobPixels.length,
                                pixels: blobPixels,
                                avgColor: {
                                    r: Math.round(sumR / blobPixels.length),
                                    g: Math.round(sumG / blobPixels.length),
                                    b: Math.round(sumB / blobPixels.length)
                                }
                            });
                        }
                    }
                }
            }

            if (rawBlobs.length === 0) return null;

            const newZones = [];
            rawBlobs.forEach((blob, bIdx) => {
                const zone = this.fitGeometricShapeToBlob(blob, scanW, scanH, offsetX, offsetY, renderW, renderH, containerW, containerH, bIdx);
                if (zone) newZones.push(zone);
            });

            return newZones.length > 0 ? newZones : null;
        },

        fitGeometricShapeToBlob: function(blob, scanW, scanH, offsetX, offsetY, renderW, renderH, containerW, containerH, idx) {
            const rawMask = new Uint8Array(scanW * scanH);
            blob.pixels.forEach(p => rawMask[p] = 1);
            const solidMask = this.fillHolesInMask(rawMask, scanW, scanH);

            const rawContour = this.traceContour(solidMask, scanW, scanH);
            if (rawContour.length < 4) return null;

            let simplified = this.douglasPeucker(rawContour, 1.8);
            if (simplified.length < 3) return null;

            if (simplified.length > 5) {
                simplified = this.chaikinSmooth(simplified, 1);
            }

            const points = simplified.map(pt => ({
                x: Math.round(offsetX + (pt.x / scanW) * renderW),
                y: Math.round(offsetY + (pt.y / scanH) * renderH)
            }));

            const xs = points.map(p => p.x);
            const ys = points.map(p => p.y);
            const cx = xs.reduce((a, b) => a + b, 0) / xs.length;
            const cy = ys.reduce((a, b) => a + b, 0) / ys.length;

            const relX = cx / containerW;
            const relY = cy / containerH;

            let capacityType = 'Campo';
            let price = 50.00;
            let capacity = 100;

            if (relX < 0.36 || relX > 0.64) {
                capacityType = 'Tribuna';
                price = 65.00;
                capacity = 300;
            } else if (relY >= 0.70) {
                capacityType = 'Graderías';
                price = 40.00;
                capacity = 400;
            } else if (relY < 0.35) {
                capacityType = 'Campo';
                price = 120.00;
                capacity = 150;
            } else if (relY < 0.52) {
                capacityType = 'Campo';
                price = 90.00;
                capacity = 200;
            } else {
                capacityType = 'Campo';
                price = 70.00;
                capacity = 250;
            }

            const zoneName = capacityType;
            const hexColor = this.rgbToHex(blob.avgColor.r, blob.avgColor.g, blob.avgColor.b);

            return {
                id: 'zone_auto_' + Date.now() + '_' + idx,
                name: zoneName,
                capacity: capacity,
                price: price,
                capacity_type: capacityType,
                color: hexColor,
                presale_enabled: false,
                presale_discount: 20,
                points: points,
                seats: []
            };
        },

        fillHolesInMask: function(mask, w, h) {
            const isOuterBg = new Uint8Array(w * h);
            const queue = [];

            for (let x = 0; x < w; x++) {
                if (!mask[x]) { isOuterBg[x] = 1; queue.push(x); }
                const bIdx = (h - 1) * w + x;
                if (!mask[bIdx]) { isOuterBg[bIdx] = 1; queue.push(bIdx); }
            }
            for (let y = 0; y < h; y++) {
                const lIdx = y * w;
                if (!mask[lIdx] && !isOuterBg[lIdx]) { isOuterBg[lIdx] = 1; queue.push(lIdx); }
                const rIdx = y * w + (w - 1);
                if (!mask[rIdx] && !isOuterBg[rIdx]) { isOuterBg[rIdx] = 1; queue.push(rIdx); }
            }

            let head = 0;
            while (head < queue.length) {
                const cur = queue[head++];
                const cx = cur % w;
                const cy = Math.floor(cur / w);

                const neighbors = [
                    cy > 0 ? cur - w : -1,
                    cy < h - 1 ? cur + w : -1,
                    cx > 0 ? cur - 1 : -1,
                    cx < w - 1 ? cur + 1 : -1
                ];

                for (const n of neighbors) {
                    if (n >= 0 && !mask[n] && !isOuterBg[n]) {
                        isOuterBg[n] = 1;
                        queue.push(n);
                    }
                }
            }

            const solidMask = new Uint8Array(w * h);
            for (let i = 0; i < w * h; i++) {
                if (!isOuterBg[i]) solidMask[i] = 1;
            }
            return solidMask;
        },

        chaikinSmooth: function(points, iterations = 1) {
            if (points.length < 3) return points;
            let current = points;

            for (let it = 0; it < iterations; it++) {
                const next = [];
                const n = current.length;

                for (let i = 0; i < n; i++) {
                    const p0 = current[i];
                    const p1 = current[(i + 1) % n];

                    next.push({
                        x: Math.round(0.75 * p0.x + 0.25 * p1.x),
                        y: Math.round(0.75 * p0.y + 0.25 * p1.y)
                    });
                    next.push({
                        x: Math.round(0.25 * p0.x + 0.75 * p1.x),
                        y: Math.round(0.25 * p0.y + 0.75 * p1.y)
                    });
                }
                current = next;
            }
            return current;
        },

        traceContour: function(mask, w, h) {
            let startX = -1, startY = -1;
            for (let y = 0; y < h; y++) {
                for (let x = 0; x < w; x++) {
                    if (mask[y * w + x]) {
                        startX = x;
                        startY = y;
                        break;
                    }
                }
                if (startX !== -1) break;
            }
            if (startX === -1) return [];

            const contour = [];
            let cx = startX, cy = startY;
            let dir = 0;
            const dx = [1, 1, 0, -1, -1, -1, 0, 1];
            const dy = [0, 1, 1, 1, 0, -1, -1, -1];

            let steps = 0;
            const maxSteps = w * h;

            do {
                contour.push({ x: cx, y: cy });
                let found = false;
                for (let i = 0; i < 8; i++) {
                    const checkDir = (dir + i) % 8;
                    const nx = cx + dx[checkDir];
                    const ny = cy + dy[checkDir];
                    if (nx >= 0 && nx < w && ny >= 0 && ny < h && mask[ny * w + nx]) {
                        cx = nx;
                        cy = ny;
                        dir = (checkDir + 5) % 8;
                        found = true;
                        break;
                    }
                }
                if (!found) break;
                steps++;
            } while ((cx !== startX || cy !== startY) && steps < maxSteps);

            return contour;
        },

        douglasPeucker: function(points, epsilon) {
            if (points.length <= 2) return points;

            let maxDist = 0;
            let index = 0;
            const p1 = points[0];
            const p2 = points[points.length - 1];

            for (let i = 1; i < points.length - 1; i++) {
                const p = points[i];
                const dist = this.perpendicularDistance(p, p1, p2);
                if (dist > maxDist) {
                    maxDist = dist;
                    index = i;
                }
            }

            if (maxDist > epsilon) {
                const left = this.douglasPeucker(points.slice(0, index + 1), epsilon);
                const right = this.douglasPeucker(points.slice(index), epsilon);
                return left.slice(0, left.length - 1).concat(right);
            } else {
                return [p1, p2];
            }
        },

        perpendicularDistance: function(p, p1, p2) {
            const dx = p2.x - p1.x;
            const dy = p2.y - p1.y;
            if (dx === 0 && dy === 0) return Math.hypot(p.x - p1.x, p.y - p1.y);
            const num = Math.abs(dy * p.x - dx * p.y + p2.x * p1.y - p2.y * p1.x);
            return num / Math.hypot(dx, dy);
        },

        classifyPixelColor: function(r, g, b) {
            const max = Math.max(r, g, b) / 255;
            const min = Math.min(r, g, b) / 255;
            const d = max - min;

            if (d < 0.12) return 0;

            let h = 0;
            if (max === r / 255) h = ((g / 255 - b / 255) / d) % 6;
            else if (max === g / 255) h = (b / 255 - r / 255) / d + 2;
            else h = (r / 255 - g / 255) / d + 4;
            h = Math.round(h * 60);
            if (h < 0) h += 360;

            if (h >= 345 || h < 18) return 1;
            if (h >= 18 && h < 46) return 2;
            if (h >= 46 && h < 75) return 3;
            if (h >= 75 && h < 160) return 4;
            if (h >= 160 && h < 200) return 5;
            if (h >= 200 && h < 260) return 6;
            if (h >= 260 && h < 345) return 7;

            return 0;
        },

        rgbToHex: function(r, g, b) {
            return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
        },

        createStageZone: function() {
            const newId = 'zone_stage_' + Date.now();
            const newZone = {
                id: newId,
                name: 'ESCENARIO',
                capacity: 0,
                price: 0,
                capacity_type: 'Escenario',
                color: '#334155',
                presale_enabled: false,
                presale_discount: 0,
                points: [
                    { x: 370, y: 25 },
                    { x: 630, y: 25 },
                    { x: 630, y: 95 },
                    { x: 370, y: 95 }
                ],
                seats: []
            };

            this.zones.push(newZone);
            this.selectedZoneId = newId;
            this.selectedPointIdx = null;
            this.setTool('select');
            this.render();
            this.syncToStandardTable();
        },

        createCurvedArcPoints: function(cx, cy, innerR, outerR, startAngleDeg, endAngleDeg, segments = 14) {
            const points = [];
            const startRad = (startAngleDeg * Math.PI) / 180;
            const endRad = (endAngleDeg * Math.PI) / 180;

            for (let i = 0; i <= segments; i++) {
                const angle = startRad + (i / segments) * (endRad - startRad);
                points.push({
                    x: Math.round(cx + Math.cos(angle) * outerR),
                    y: Math.round(cy + Math.sin(angle) * outerR)
                });
            }

            for (let i = segments; i >= 0; i--) {
                const angle = startRad + (i / segments) * (endRad - startRad);
                points.push({
                    x: Math.round(cx + Math.cos(angle) * innerR),
                    y: Math.round(cy + Math.sin(angle) * innerR)
                });
            }

            return points;
        },

        createCurvedArcZone: function() {
            const count = this.zones.length + 1;
            const colors = ['#F59E0B', '#78350F', '#38BDF8', '#EC4899', '#10B981', '#FF5500'];
            const newId = 'zone_arc_' + Date.now();

            const arcPoints = this.createCurvedArcPoints(500, 360, 160, 220, 0, 180, 14);

            const newZone = {
                id: newId,
                name: 'Graderías',
                capacity: 250,
                price: 45.00,
                capacity_type: 'Graderías',
                color: colors[(count - 1) % colors.length],
                presale_enabled: false,
                presale_discount: 20,
                points: arcPoints,
                seats: []
            };

            this.zones.push(newZone);
            this.selectedZoneId = newId;
            this.selectedPointIdx = null;
            this.setTool('select');
            this.render();
            this.syncToStandardTable();
        },

        createLateralStandZone: function() {
            const count = this.zones.length + 1;
            const newId = 'zone_lateral_' + Date.now();
            const isLeft = (count % 2 === 1);

            const x1 = isLeft ? 230 : 660;
            const x2 = isLeft ? 340 : 770;
            const points = [
                { x: x1, y: 110 },
                { x: x2, y: 110 },
                { x: isLeft ? Math.round(x2) : Math.round(x2 + 10), y: 275 },
                { x: x2, y: 440 },
                { x: x1, y: 440 },
                { x: isLeft ? Math.round(x1 - 10) : Math.round(x1), y: 275 }
            ];

            const newZone = {
                id: newId,
                name: 'Tribuna',
                capacity: 300,
                price: 60.00,
                capacity_type: 'Tribuna',
                color: isLeft ? '#EC4899' : '#8B5CF6',
                presale_enabled: false,
                presale_discount: 20,
                points: points,
                seats: []
            };

            this.zones.push(newZone);
            this.selectedZoneId = newId;
            this.selectedPointIdx = null;
            this.setTool('select');
            this.render();
            this.syncToStandardTable();
        },

        createManualZone: function() {
            const count = this.zones.length + 1;
            const colors = ['#10B981', '#2563EB', '#38BDF8', '#78350F', '#F59E0B', '#EC4899', '#8B5CF6'];
            const offset = (count * 20) % 100;
            const newId = 'zone_' + Date.now();

            const newZone = {
                id: newId,
                name: 'Campo',
                capacity: 100,
                price: 50.00,
                capacity_type: 'Campo',
                color: colors[(count - 1) % colors.length],
                presale_enabled: false,
                presale_discount: 20,
                points: [
                    { x: 360 + offset, y: 180 + offset },
                    { x: 640 + offset, y: 180 + offset },
                    { x: 640 + offset, y: 280 + offset },
                    { x: 360 + offset, y: 280 + offset }
                ],
                seats: []
            };

            this.zones.push(newZone);
            this.selectedZoneId = newId;
            this.selectedPointIdx = null;
            this.setTool('select');
            this.render();
            this.syncToStandardTable();
        },

        selectZone: function(zoneId) {
            this.selectedZoneId = zoneId;
            this.selectedPointIdx = null;
            this.render();
        },

        duplicateSelectedZone: function() {
            this.hideContextMenu();
            const z = this.getSelectedZone();
            if (!z) return;

            const newId = 'zone_dup_' + Date.now();
            const offset = 25;
            const newPoints = z.points.map(p => ({ x: p.x + offset, y: p.y + offset }));

            const dupZone = {
                id: newId,
                name: `${z.name} (COPIA)`,
                capacity: z.capacity,
                price: z.price,
                capacity_type: z.capacity_type,
                color: z.color,
                presale_enabled: z.presale_enabled,
                presale_discount: z.presale_discount,
                points: newPoints,
                seats: [],
                seat_rows: z.seat_rows || z.rows || null,
                seat_cols: z.seat_cols || z.cols || null,
                rows: z.rows || z.seat_rows || null,
                cols: z.cols || z.seat_cols || null,
                seat_row_type: z.seat_row_type || z.row_type || null,
                seat_col_type: z.seat_col_type || z.col_type || null,
                row_type: z.row_type || z.seat_row_type || null,
                col_type: z.col_type || z.seat_col_type || null
            };

            this.zones.push(dupZone);
            this.selectedZoneId = newId;
            this.selectedPointIdx = null;
            this.render();
            this.syncToStandardTable();
        },

        deleteSelectedZone: function() {
            this.hideContextMenu();
            if (!this.selectedZoneId) return;
            this.zones = this.zones.filter(item => item.id !== this.selectedZoneId);
            this.selectedZoneId = this.zones.length > 0 ? this.zones[0].id : null;
            this.selectedPointIdx = null;
            this.render();
            this.syncToStandardTable();
        },

        clearAllZones: function() {
            if (this.zones.length === 0) return;
            if (confirm('¿Estás seguro de que deseas eliminar todas las zonas del plano?')) {
                this.zones = [];
                this.selectedZoneId = null;
                this.selectedPointIdx = null;
                this.render();
                this.syncToStandardTable();
            }
        },

        setZoneNamePreset: function(name) {
            const z = this.getSelectedZone();
            if (!z) return;
            z.name = name;
            const el = document.getElementById('inspectorZoneName');
            if (el) el.value = name;
            this.render();
            this.syncToStandardTable();
        },

        setZonePresetColor: function(hex) {
            const z = this.getSelectedZone();
            if (!z) return;
            z.color = hex;
            const col = document.getElementById('inspectorZoneColor');
            if (col) col.value = hex;
            const hexLbl = document.getElementById('inspectorZoneColorHex');
            if (hexLbl) hexLbl.textContent = hex;
            this.render();
            this.syncToStandardTable();
        },

        getSelectedZone: function() {
            return this.zones.find(z => z.id === this.selectedZoneId);
        },

        onCapacityTypeChange: function() {
            const z = this.getSelectedZone();
            if (!z) return;
            const newType = document.getElementById('inspectorZoneCapacityType').value;
            z.capacity_type = newType;
            z.name = newType;
            const nameEl = document.getElementById('inspectorZoneName');
            if (nameEl) nameEl.value = newType;
            this.render();
            this.syncToStandardTable();
        },

        updateSelectedZoneProps: function() {
            const z = this.getSelectedZone();
            if (!z) return;

            const nameEl = document.getElementById('inspectorZoneName');
            z.name = nameEl ? (nameEl.value.trim() || z.capacity_type || 'Zona') : z.name;
            const capTypeEl = document.getElementById('inspectorZoneCapacityType');
            z.capacity_type = capTypeEl ? capTypeEl.value : z.capacity_type;
            const colorEl = document.getElementById('inspectorZoneColor');
            z.color = colorEl ? colorEl.value : z.color;
            const hexEl = document.getElementById('inspectorZoneColorHex');
            if (hexEl) hexEl.textContent = z.color;
            const priceEl = document.getElementById('inspectorZonePrice');
            z.price = priceEl ? (parseFloat(priceEl.value) || 0) : z.price;
            const capEl = document.getElementById('inspectorZoneCapacity');
            z.capacity = capEl ? (parseInt(capEl.value) || 0) : z.capacity;

            const presaleCheck = document.getElementById('inspectorZonePresaleEnabled');
            const isPresale = presaleCheck ? presaleCheck.checked : false;
            z.presale_enabled = isPresale;
            const discEl = document.getElementById('inspectorZonePresaleDiscount');
            z.presale_discount = discEl ? (parseFloat(discEl.value) || 0) : 20;

            const pStartEl = document.getElementById('inspectorZonePresaleStartDate');
            z.presale_start_date = pStartEl && pStartEl.value ? pStartEl.value : null;

            const pEndEl = document.getElementById('inspectorZonePresaleEndDate');
            z.presale_end_date = pEndEl && pEndEl.value ? pEndEl.value : null;

            const pPrice = Math.max(0, z.price * (1 - (z.presale_discount / 100)));
            z.presale_price = pPrice;
            const presaleDisp = document.getElementById('inspectorZonePresalePriceDisplay');
            if (presaleDisp) presaleDisp.value = `S/ ${pPrice.toFixed(2)}`;

            const presaleGrid = document.getElementById('inspectorPresaleGrid');
            if (presaleGrid) {
                presaleGrid.style.opacity = isPresale ? '1' : '0.5';
                presaleGrid.style.pointerEvents = isPresale ? 'auto' : 'none';
            }

            this.render();
            this.syncToStandardTable();
        },

        updateSeatNomenclaturePreview: function() {
            const previewEl = document.getElementById('seatNomenclaturePreview');
            if (!previewEl) return;

            const rowType = document.getElementById('seatGenRowType')?.value || 'letters';
            const colType = document.getElementById('seatGenColType')?.value || 'numbers';

            const rowSample = rowType === 'numbers' ? '1' : 'A';
            const colSample = colType === 'letters' ? 'A' : '1';

            previewEl.textContent = `Fila ${rowSample} - Asiento ${colSample} (${rowSample}-${colSample})`;
        },

        onSeatGenDimensionsChange: function() {
            const numRows = parseInt(document.getElementById('seatGenRows')?.value) || 0;
            const numCols = parseInt(document.getElementById('seatGenCols')?.value) || 0;
            const rowType = document.getElementById('seatGenRowType')?.value || 'letters';
            const colType = document.getElementById('seatGenColType')?.value || 'numbers';
            const autoCap = numRows * numCols;
            
            const z = this.getSelectedZone();
            if (z) {
                z.seat_rows = numRows;
                z.seat_cols = numCols;
                z.rows = numRows;
                z.cols = numCols;
                z.seat_row_type = rowType;
                z.seat_col_type = colType;
                z.row_type = rowType;
                z.col_type = colType;
                if (autoCap > 0) {
                    z.capacity = autoCap;
                }
            }

            const capEl = document.getElementById('inspectorZoneCapacity');
            if (capEl && autoCap > 0) {
                capEl.value = autoCap;
            }

            this.syncToStandardTable();
        },

        generateSeatsForSelectedZone: function() {
            const z = this.getSelectedZone();
            if (!z || !z.points || z.points.length < 3) {
                alert('Selecciona una zona válida en el plano.');
                return;
            }

            const numRows = parseInt(document.getElementById('seatGenRows')?.value) || 5;
            const numCols = parseInt(document.getElementById('seatGenCols')?.value) || 10;
            const rowType = document.getElementById('seatGenRowType')?.value || 'letters';
            const colType = document.getElementById('seatGenColType')?.value || 'numbers';

            const xs = z.points.map(p => p.x);
            const ys = z.points.map(p => p.y);
            const minX = Math.min(...xs) + 15;
            const maxX = Math.max(...xs) - 15;
            const minY = Math.min(...ys) + 25;
            const maxY = Math.max(...ys) - 15;

            const width = Math.max(20, maxX - minX);
            const height = Math.max(20, maxY - minY);

            const xStep = numCols > 1 ? width / (numCols - 1) : width / 2;
            const yStep = numRows > 1 ? height / (numRows - 1) : height / 2;

            const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const seats = [];

            for (let r = 0; r < numRows; r++) {
                let rowCode, rowTitle;
                if (rowType === 'numbers') {
                    rowCode = String(r + 1);
                    rowTitle = `Fila ${r + 1}`;
                } else {
                    rowCode = alphabet[r % alphabet.length];
                    rowTitle = `Fila ${rowCode}`;
                }

                const curY = minY + (r * yStep);

                for (let c = 0; c < numCols; c++) {
                    let colCode, seatTitle;
                    if (colType === 'letters') {
                        colCode = alphabet[c % alphabet.length];
                        seatTitle = `Asiento ${colCode}`;
                    } else {
                        colCode = String(c + 1);
                        seatTitle = `Asiento ${colCode}`;
                    }

                    const curX = minX + (c * xStep);
                    const shortCode = `${rowCode}-${colCode}`;
                    const fullLabel = `${rowTitle} - ${seatTitle}`;

                    seats.push({
                        id: `${z.id}_${rowCode}_${colCode}`,
                        row: rowCode,
                        col: colCode,
                        number: shortCode,
                        label: fullLabel,
                        display_name: fullLabel,
                        x: Math.round(curX),
                        y: Math.round(curY),
                        status: 'available'
                    });
                }
            }

            z.seats = seats;
            z.capacity = seats.length;
            z.capacity_type = 'Butacas Numeradas';
            z.seat_rows = numRows;
            z.seat_cols = numCols;
            z.rows = numRows;
            z.cols = numCols;
            z.seat_row_type = rowType;
            z.seat_col_type = colType;
            z.row_type = rowType;
            z.col_type = colType;

            if (!z.name || z.name === 'Zona' || z.name === 'Nueva Zona') {
                z.name = 'Butacas Numeradas';
            }
            
            const nameEl = document.getElementById('inspectorZoneName');
            if (nameEl && (!nameEl.value || nameEl.value === 'Zona' || nameEl.value === 'Nueva Zona')) {
                nameEl.value = z.name;
            }
            const capEl = document.getElementById('inspectorZoneCapacity');
            if (capEl) capEl.value = z.capacity;
            const capTypeEl = document.getElementById('inspectorZoneCapacityType');
            if (capTypeEl) capTypeEl.value = z.capacity_type;
            const badgeEl = document.getElementById('inspectorSeatsBadge');
            if (badgeEl) badgeEl.textContent = `✓ ${seats.length} butacas numeradas activas`;

            const btnRemove = document.getElementById('btnRemoveSeats');
            if (btnRemove) btnRemove.style.display = 'block';

            this.render();
            this.syncToStandardTable();
        },

        clearSeatsForSelectedZone: function() {
            const z = this.getSelectedZone();
            if (!z) return;

            if (!confirm(`¿Deseas quitar todas las butacas numeradas del sector "${z.name}" y dejarlo como aforo general?`)) {
                return;
            }

            z.seats = [];
            z.capacity_type = 'General';
            z.seat_rows = null;
            z.seat_cols = null;
            z.rows = null;
            z.cols = null;

            const badgeEl = document.getElementById('inspectorSeatsBadge');
            if (badgeEl) badgeEl.textContent = '';
            const btnRemove = document.getElementById('btnRemoveSeats');
            if (btnRemove) btnRemove.style.display = 'none';

            this.render();
            this.syncToStandardTable();
        },

        validateUnpopulatedSeats: function() {
            // 1. Validar la zona activa actualmente en el inspector si tiene butacas o si se editaron filas/asientos
            const z = this.getSelectedZone();
            const rowsInput = document.getElementById('seatGenRows');
            const colsInput = document.getElementById('seatGenCols');
            if (z && rowsInput && colsInput) {
                const numRows = parseInt(rowsInput.value) || 0;
                const numCols = parseInt(colsInput.value) || 0;
                const expected = numRows * numCols;
                const actualSeats = Array.isArray(z.seats) ? z.seats.length : 0;
                const isNumbered = (z.capacity_type === 'Butacas Numeradas') || (actualSeats > 0) || (z.name && /butaca/i.test(z.name));

                if (isNumbered && expected > 0 && expected !== actualSeats) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: '¡Butacas pendientes de poblar!',
                            html: `Has configurado <b>${numRows} filas × ${numCols} asientos (${expected} butacas)</b> en la zona <b>"${z.name}"</b>, pero actualmente hay <b>${actualSeats} butacas generadas</b>.<br><br><span style="color: #10B981; font-weight: 800;">Por favor presiona el botón "🪑 Poblar Zona con Butacas"</span> para generar los asientos antes de continuar.`,
                            confirmButtonColor: '#10B981',
                            confirmButtonText: 'Entendido',
                            background: '#14141E',
                            color: '#FFFFFF'
                        });
                    } else {
                        alert(`Has configurado ${numRows} filas × ${numCols} asientos (${expected} butacas) en la zona "${z.name}", pero hay ${actualSeats} butacas generadas. Por favor pulsa "Poblar Zona con Butacas" antes de continuar.`);
                    }
                    return false;
                }
            }

            // 2. Validar que ninguna zona de tipo 'Butacas Numeradas' se quede sin butacas o desfasada
            if (Array.isArray(this.zones)) {
                for (let i = 0; i < this.zones.length; i++) {
                    const zone = this.zones[i];
                    const actual = Array.isArray(zone.seats) ? zone.seats.length : 0;
                    const isNumbered = (zone.capacity_type === 'Butacas Numeradas') || (actual > 0);

                    if (isNumbered) {
                        if (actual === 0) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Zona sin butacas',
                                    html: `La zona <b>"${zone.name}"</b> está configurada como Butacas Numeradas pero no tiene asientos generados.<br><br>Selecciona la zona en el plano y pulsa <b style="color: #10B981;">"🪑 Poblar Zona con Butacas"</b>.`,
                                    confirmButtonColor: '#10B981',
                                    confirmButtonText: 'Entendido',
                                    background: '#14141E',
                                    color: '#FFFFFF'
                                });
                            } else {
                                alert(`La zona "${zone.name}" no tiene butacas generadas. Por favor pulsa "Poblar Zona con Butacas".`);
                            }
                            return false;
                        }

                        const rows = parseInt(zone.seat_rows || zone.rows) || 0;
                        const cols = parseInt(zone.seat_cols || zone.cols) || 0;
                        if (rows > 0 && cols > 0 && (rows * cols !== actual)) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: '¡Butacas pendientes de poblar!',
                                    html: `En la zona <b>"${zone.name}"</b> configuraste <b>${rows} filas × ${cols} asientos (${rows * cols} butacas)</b> pero actualmente hay <b>${actual} butacas generadas</b>.<br><br>Selecciona la zona en el plano y presiona <b style="color: #10B981;">"🪑 Poblar Zona con Butacas"</b> antes de continuar.`,
                                    confirmButtonColor: '#10B981',
                                    confirmButtonText: 'Entendido',
                                    background: '#14141E',
                                    color: '#FFFFFF'
                                });
                            } else {
                                alert(`En la zona "${zone.name}" hay butacas desfasadas. Por favor pulsa "Poblar Zona con Butacas".`);
                            }
                            return false;
                        }
                    }
                }
            }

            return true;
        },

        render: function() {
            this.renderSvgZones();
            this.renderSvgSeats();
            this.renderSvgLabels();
            this.renderSvgHandles();
            this.renderTransformGizmo();
            this.renderInspector();
            this.renderZonesList();
            this.renderPreviewLayer();
            this.updateZoomTransform();
        },

        renderSvgZones: function() {
            const group = document.getElementById('svgZonesGroup');
            if (!group) return;
            group.innerHTML = '';

            const strokeW = (2 / Math.sqrt(this.zoom)).toFixed(2);
            const selectedStrokeW = (2.6 / Math.sqrt(this.zoom)).toFixed(2);
            const dashArr = `${(4 / this.zoom).toFixed(1)} ${(2.5 / this.zoom).toFixed(1)}`;

            this.zones.forEach(z => {
                const isSelected = (z.id === this.selectedZoneId);
                const pointsStr = z.points.map(p => `${p.x},${p.y}`).join(' ');

                const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
                polygon.setAttribute('points', pointsStr);
                polygon.setAttribute('fill', z.color);
                polygon.setAttribute('fill-opacity', isSelected ? '0.68' : '0.45');
                polygon.setAttribute('stroke', isSelected ? '#FFFFFF' : z.color);
                polygon.setAttribute('stroke-width', isSelected ? selectedStrokeW : strokeW);
                if (isSelected) {
                    polygon.setAttribute('stroke-dasharray', dashArr);
                }
                polygon.setAttribute('class', `svg-zone-polygon ${isSelected ? 'selected' : ''}`);
                
                polygon.onmousedown = (e) => {
                    e.stopPropagation();
                    if (e.button === 0 && this.currentTool === 'select') {
                        this.selectZone(z.id);
                        this.startZoneDrag(e, z);
                    }
                };

                polygon.oncontextmenu = (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.showContextMenu(e, z.id);
                };

                group.appendChild(polygon);
            });
        },

        startZoneDrag: function(e, zone) {
            this.isDraggingZone = true;
            const startCoords = this.getSvgCoordinates(e);
            this.dragZoneInfo = {
                id: zone.id,
                startX: startCoords.x,
                startY: startCoords.y,
                origPoints: zone.points.map(p => ({ x: p.x, y: p.y })),
                origSeats: Array.isArray(zone.seats) ? zone.seats.map(s => ({ ...s })) : []
            };

            const onMouseMove = (ev) => {
                if (!this.isDraggingZone || !this.dragZoneInfo) return;
                const currentCoords = this.getSvgCoordinates(ev);
                const dx = Math.round(currentCoords.x - this.dragZoneInfo.startX);
                const dy = Math.round(currentCoords.y - this.dragZoneInfo.startY);

                const currentZ = this.zones.find(item => item.id === this.dragZoneInfo.id);
                if (currentZ) {
                    currentZ.points = this.dragZoneInfo.origPoints.map(p => ({
                        x: p.x + dx,
                        y: p.y + dy
                    }));

                    if (Array.isArray(currentZ.seats) && currentZ.seats.length > 0) {
                        currentZ.seats = this.dragZoneInfo.origSeats.map(s => ({
                            ...s,
                            x: s.x + dx,
                            y: s.y + dy
                        }));
                    }

                    this.renderSvgZones();
                    this.renderSvgSeats();
                    this.renderSvgLabels();
                    this.renderSvgHandles();
                    this.renderTransformGizmo();
                }
            };

            const onMouseUp = () => {
                this.isDraggingZone = false;
                this.dragZoneInfo = null;
                window.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('mouseup', onMouseUp);
                this.syncToStandardTable();
            };

            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', onMouseUp);
        },

        renderSvgSeats: function() {
            const group = document.getElementById('svgSeatsGroup');
            if (!group) return;
            group.innerHTML = '';

            const seatSize = Math.max(5, 9 / Math.sqrt(this.zoom));
            const halfSize = seatSize / 2;
            const strokeW = (1.2 / Math.sqrt(this.zoom)).toFixed(2);

            this.zones.forEach(z => {
                if (Array.isArray(z.seats) && z.seats.length > 0) {
                    z.seats.forEach(s => {
                        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                        rect.setAttribute('x', s.x - halfSize);
                        rect.setAttribute('y', s.y - halfSize);
                        rect.setAttribute('width', seatSize);
                        rect.setAttribute('height', seatSize);
                        rect.setAttribute('rx', '2');
                        rect.setAttribute('fill', '#FFFFFF');
                        rect.setAttribute('stroke', z.color);
                        rect.setAttribute('stroke-width', strokeW);
                        rect.style.cursor = 'pointer';

                        const title = document.createElementNS('http://www.w3.org/2000/svg', 'title');
                        title.textContent = `${z.name} - Butaca ${s.label} (S/ ${z.price.toFixed(2)})`;
                        rect.appendChild(title);

                        group.appendChild(rect);
                    });
                }
            });
        },

        renderSvgLabels: function() {
            const group = document.getElementById('svgLabelsGroup');
            if (!group) return;
            group.innerHTML = '';

            this.zones.forEach(z => {
                if (!z.points || z.points.length === 0) return;

                const xs = z.points.map(p => p.x);
                const ys = z.points.map(p => p.y);
                const centerX = xs.reduce((a, b) => a + b, 0) / xs.length;
                const centerY = ys.reduce((a, b) => a + b, 0) / ys.length;

                const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                g.setAttribute('transform', `translate(${centerX}, ${centerY})`);
                g.style.pointerEvents = 'none';

                const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                rect.setAttribute('x', '-70');
                rect.setAttribute('y', '-16');
                rect.setAttribute('width', '140');
                rect.setAttribute('height', '32');
                rect.setAttribute('rx', '8');
                rect.setAttribute('fill', 'rgba(15, 23, 42, 0.90)');
                rect.setAttribute('stroke', z.color);
                rect.setAttribute('stroke-width', (1.5 / Math.sqrt(this.zoom)).toFixed(2));
                rect.setAttribute('filter', 'url(#labelShadow)');
                g.appendChild(rect);

                const textName = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                textName.setAttribute('x', '0');
                textName.setAttribute('y', '-3');
                textName.setAttribute('text-anchor', 'middle');
                textName.setAttribute('fill', '#FFFFFF');
                textName.setAttribute('font-size', '10.5');
                textName.setAttribute('font-weight', '800');
                textName.setAttribute('font-family', 'sans-serif');
                textName.textContent = z.name.length > 16 ? (z.name.slice(0, 14) + '..') : z.name;
                g.appendChild(textName);

                const textPrice = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                textPrice.setAttribute('x', '0');
                textPrice.setAttribute('y', '10');
                textPrice.setAttribute('text-anchor', 'middle');
                textPrice.setAttribute('fill', '#10B981');
                textPrice.setAttribute('font-size', '9');
                textPrice.setAttribute('font-weight', '800');
                textPrice.setAttribute('font-family', 'sans-serif');
                textPrice.textContent = z.price > 0 ? `S/ ${z.price.toFixed(2)} (${z.capacity} cap)` : `${z.capacity_type}`;
                g.appendChild(textPrice);

                group.appendChild(g);
            });
        },

        renderSvgHandles: function() {
            const group = document.getElementById('svgHandlesGroup');
            if (!group) return;
            group.innerHTML = '';

            const selectedZone = this.getSelectedZone();
            if (!selectedZone || this.currentTool !== 'select' || this.isBuyerPreview) return;

            const handleRadius = Math.max(1.8, 3.8 / this.zoom);
            const strokeWidth = Math.max(0.6, 1.2 / this.zoom);

            selectedZone.points.forEach((p, idx) => {
                const isPointActive = (this.selectedPointIdx === idx);

                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('cx', p.x);
                circle.setAttribute('cy', p.y);
                circle.setAttribute('r', isPointActive ? (handleRadius * 1.35).toFixed(2) : handleRadius.toFixed(2));
                circle.setAttribute('fill', isPointActive ? '#10B981' : '#FF5500');
                circle.setAttribute('stroke', '#FFFFFF');
                circle.setAttribute('stroke-width', strokeWidth.toFixed(2));
                circle.setAttribute('class', `svg-handle-circle ${isPointActive ? 'active-point' : ''}`);
                circle.style.cursor = 'pointer';
                circle.title = isPointActive ? 'Punto seleccionado. Pulsa Supr / Delete para eliminarlo' : 'Haz clic para seleccionar este punto (en verde) y borrarlo con Supr';

                circle.onclick = (e) => {
                    e.stopPropagation();
                    this.selectedPointIdx = idx;
                    this.render();
                };

                circle.onmousedown = (e) => {
                    e.stopPropagation();
                    this.selectedPointIdx = idx;
                    this.draggingHandle = { zoneId: selectedZone.id, pointIdx: idx };
                    window.addEventListener('mousemove', this.onHandleMouseMove);
                    window.addEventListener('mouseup', this.onHandleMouseUp);
                };

                group.appendChild(circle);
            });
        },

        renderTransformGizmo: function() {
            const group = document.getElementById('svgTransformGizmoGroup');
            if (!group) return;
            group.innerHTML = '';

            const selectedZone = this.getSelectedZone();
            if (!selectedZone || this.currentTool !== 'select' || this.isBuyerPreview || !selectedZone.points || selectedZone.points.length < 3) return;

            const xs = selectedZone.points.map(p => p.x);
            const ys = selectedZone.points.map(p => p.y);
            const minX = Math.min(...xs);
            const maxX = Math.max(...xs);
            const minY = Math.min(...ys);
            const maxY = Math.max(...ys);

            const pad = 12 / this.zoom;
            const boxX = minX - pad;
            const boxY = minY - pad;
            const boxW = (maxX - minX) + (pad * 2);
            const boxH = (maxY - minY) + (pad * 2);
            const cx = (minX + maxX) / 2;
            const cy = (minY + maxY) / 2;

            const strokeW = Math.max(0.7, 1.1 / this.zoom);
            const sqSize = Math.max(5.5, 8.5 / this.zoom);
            const halfSq = sqSize / 2;
            const rotStemH = 20 / this.zoom;

            const bboxRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            bboxRect.setAttribute('x', boxX);
            bboxRect.setAttribute('y', boxY);
            bboxRect.setAttribute('width', boxW);
            bboxRect.setAttribute('height', boxH);
            bboxRect.setAttribute('fill', 'none');
            bboxRect.setAttribute('stroke', '#38BDF8');
            bboxRect.setAttribute('stroke-width', strokeW.toFixed(2));
            bboxRect.setAttribute('stroke-dasharray', `${(3 / this.zoom).toFixed(1)} ${(2 / this.zoom).toFixed(1)}`);
            bboxRect.style.pointerEvents = 'none';
            group.appendChild(bboxRect);

            const rotLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            rotLine.setAttribute('x1', cx);
            rotLine.setAttribute('y1', boxY);
            rotLine.setAttribute('x2', cx);
            rotLine.setAttribute('y2', boxY - rotStemH);
            rotLine.setAttribute('stroke', '#38BDF8');
            rotLine.setAttribute('stroke-width', strokeW.toFixed(2));
            group.appendChild(rotLine);

            const rotHandle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            rotHandle.setAttribute('cx', cx);
            rotHandle.setAttribute('cy', boxY - rotStemH);
            rotHandle.setAttribute('r', Math.max(2.5, 4.5 / this.zoom).toFixed(2));
            rotHandle.setAttribute('fill', '#38BDF8');
            rotHandle.setAttribute('stroke', '#FFFFFF');
            rotHandle.setAttribute('stroke-width', strokeW.toFixed(2));
            rotHandle.style.cursor = 'grab';
            rotHandle.title = 'Arrastra para Rotar / Girar la zona';

            rotHandle.onmousedown = (e) => {
                e.stopPropagation();
                this.startGizmoRotate(e, selectedZone, cx, cy);
            };
            group.appendChild(rotHandle);

            const corners = [
                { id: 'nw', x: boxX, y: boxY, cursor: 'nwse-resize', anchorX: maxX, anchorY: maxY },
                { id: 'ne', x: boxX + boxW, y: boxY, cursor: 'nesw-resize', anchorX: minX, anchorY: maxY },
                { id: 'se', x: boxX + boxW, y: boxY + boxH, cursor: 'nwse-resize', anchorX: minX, anchorY: minY },
                { id: 'sw', x: boxX, y: boxY + boxH, cursor: 'nesw-resize', anchorX: maxX, anchorY: minY }
            ];

            corners.forEach(c => {
                const cornerSq = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                cornerSq.setAttribute('x', c.x - halfSq);
                cornerSq.setAttribute('y', c.y - halfSq);
                cornerSq.setAttribute('width', sqSize);
                cornerSq.setAttribute('height', sqSize);
                cornerSq.setAttribute('fill', '#FFFFFF');
                cornerSq.setAttribute('stroke', '#0284C7');
                cornerSq.setAttribute('stroke-width', strokeW.toFixed(2));
                cornerSq.setAttribute('rx', '1');
                cornerSq.style.cursor = c.cursor;
                cornerSq.title = 'Arrastra para Agrandar o Achicar la zona';

                cornerSq.onmousedown = (e) => {
                    e.stopPropagation();
                    this.startGizmoScale(e, selectedZone, c);
                };
                group.appendChild(cornerSq);
            });
        },

        startGizmoScale: function(e, zone, corner) {
            const startCoords = this.getSvgCoordinates(e);
            const anchorX = corner.anchorX;
            const anchorY = corner.anchorY;
            const origSpanX = Math.abs(startCoords.x - anchorX) || 1;
            const origSpanY = Math.abs(startCoords.y - anchorY) || 1;

            const origPoints = zone.points.map(p => ({ x: p.x, y: p.y }));
            const origSeats = Array.isArray(zone.seats) ? zone.seats.map(s => ({ ...s })) : [];

            const onMouseMove = (ev) => {
                const curCoords = this.getSvgCoordinates(ev);
                const curSpanX = Math.abs(curCoords.x - anchorX);
                const curSpanY = Math.abs(curCoords.y - anchorY);

                const scaleX = Math.max(0.1, curSpanX / origSpanX);
                const scaleY = Math.max(0.1, curSpanY / origSpanY);

                zone.points = origPoints.map(p => ({
                    x: Math.round(anchorX + (p.x - anchorX) * scaleX),
                    y: Math.round(anchorY + (p.y - anchorY) * scaleY)
                }));

                if (origSeats.length > 0) {
                    zone.seats = origSeats.map(s => ({
                        ...s,
                        x: Math.round(anchorX + (s.x - anchorX) * scaleX),
                        y: Math.round(anchorY + (s.y - anchorY) * scaleY)
                    }));
                }

                this.renderSvgZones();
                this.renderSvgSeats();
                this.renderSvgLabels();
                this.renderSvgHandles();
                this.renderTransformGizmo();
            };

            const onMouseUp = () => {
                window.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('mouseup', onMouseUp);
                this.syncToStandardTable();
            };

            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', onMouseUp);
        },

        startGizmoRotate: function(e, zone, cx, cy) {
            const startCoords = this.getSvgCoordinates(e);
            const startAngle = Math.atan2(startCoords.y - cy, startCoords.x - cx);

            const origPoints = zone.points.map(p => ({ x: p.x, y: p.y }));
            const origSeats = Array.isArray(zone.seats) ? zone.seats.map(s => ({ ...s })) : [];

            const onMouseMove = (ev) => {
                const curCoords = this.getSvgCoordinates(ev);
                const curAngle = Math.atan2(curCoords.y - cy, curCoords.x - cx);
                const deltaAngle = curAngle - startAngle;

                const cos = Math.cos(deltaAngle);
                const sin = Math.sin(deltaAngle);

                zone.points = origPoints.map(p => {
                    const dx = p.x - cx;
                    const dy = p.y - cy;
                    return {
                        x: Math.round(cx + dx * cos - dy * sin),
                        y: Math.round(cy + dx * sin + dy * cos)
                    };
                });

                if (origSeats.length > 0) {
                    zone.seats = origSeats.map(s => {
                        const dx = s.x - cx;
                        const dy = s.y - cy;
                        return {
                            ...s,
                            x: Math.round(cx + dx * cos - dy * sin),
                            y: Math.round(cy + dx * sin + dy * cos)
                        };
                    });
                }

                this.renderSvgZones();
                this.renderSvgSeats();
                this.renderSvgLabels();
                this.renderSvgHandles();
                this.renderTransformGizmo();
            };

            const onMouseUp = () => {
                window.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('mouseup', onMouseUp);
                this.syncToStandardTable();
            };

            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', onMouseUp);
        },

        renderPreviewLayer: function() {
            const group = document.getElementById('svgDrawPreviewGroup');
            if (!group) return;
            group.innerHTML = '';

            if (this.drawingPoints.length > 0) {
                const handleR = Math.max(2, 3.8 / this.zoom);

                for (let i = 0; i < this.drawingPoints.length; i++) {
                    const p = this.drawingPoints[i];
                    
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('cx', p.x);
                    circle.setAttribute('cy', p.y);
                    circle.setAttribute('r', i === 0 ? (handleR * 1.35).toFixed(2) : handleR.toFixed(2));
                    circle.setAttribute('fill', i === 0 ? '#10B981' : '#FF5500');
                    circle.setAttribute('stroke', '#FFFFFF');
                    circle.setAttribute('stroke-width', (1.2 / this.zoom).toFixed(2));
                    
                    if (i === 0) {
                        circle.style.cursor = 'pointer';
                        circle.title = 'Haz clic aquí para cerrar el polígono';
                        circle.onclick = (e) => {
                            e.stopPropagation();
                            this.finishPolygonDrawing();
                        };
                    }

                    group.appendChild(circle);

                    if (i > 0) {
                        const prev = this.drawingPoints[i - 1];
                        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        line.setAttribute('x1', prev.x);
                        line.setAttribute('y1', prev.y);
                        line.setAttribute('x2', p.x);
                        line.setAttribute('y2', p.y);
                        line.setAttribute('stroke', '#FF5500');
                        line.setAttribute('stroke-width', (1.5 / this.zoom).toFixed(2));
                        line.setAttribute('stroke-dasharray', `${(3 / this.zoom).toFixed(1)} ${(2 / this.zoom).toFixed(1)}`);
                        group.appendChild(line);
                    }
                }
            }
        },

        renderInspector: function() {
            const card = document.getElementById('zoneInspectorCard');
            const empty = document.getElementById('zoneInspectorEmpty');
            const z = this.getSelectedZone();

            if (!z) {
                if (card) card.style.display = 'none';
                if (empty) empty.style.display = 'block';
                return;
            }

            if (card) card.style.display = 'block';
            if (empty) empty.style.display = 'none';

            const nameEl = document.getElementById('inspectorZoneName');
            if (nameEl) nameEl.value = z.name;
            const capTypeEl = document.getElementById('inspectorZoneCapacityType');
            if (capTypeEl) capTypeEl.value = z.capacity_type;
            const colorEl = document.getElementById('inspectorZoneColor');
            if (colorEl) colorEl.value = z.color;
            const hexEl = document.getElementById('inspectorZoneColorHex');
            if (hexEl) hexEl.textContent = z.color;
            const priceEl = document.getElementById('inspectorZonePrice');
            if (priceEl) priceEl.value = z.price.toFixed(2);
            const capEl = document.getElementById('inspectorZoneCapacity');
            if (capEl) capEl.value = z.capacity;

            const isPresale = !!z.presale_enabled;
            const presaleCheck = document.getElementById('inspectorZonePresaleEnabled');
            if (presaleCheck) presaleCheck.checked = isPresale;
            const discEl = document.getElementById('inspectorZonePresaleDiscount');
            if (discEl) discEl.value = z.presale_discount || 20;

            const pPrice = Math.max(0, z.price * (1 - ((z.presale_discount || 20) / 100)));
            const presaleDisp = document.getElementById('inspectorZonePresalePriceDisplay');
            if (presaleDisp) presaleDisp.value = `S/ ${pPrice.toFixed(2)}`;

            const presaleGrid = document.getElementById('inspectorPresaleGrid');
            if (presaleGrid) {
                presaleGrid.style.opacity = isPresale ? '1' : '0.5';
                presaleGrid.style.pointerEvents = isPresale ? 'auto' : 'none';
            }

            const pStartEl = document.getElementById('inspectorZonePresaleStartDate');
            if (pStartEl) pStartEl.value = z.presale_start_date ? z.presale_start_date.split('T')[0].split(' ')[0] : '';

            const pEndEl = document.getElementById('inspectorZonePresaleEndDate');
            if (pEndEl) pEndEl.value = z.presale_end_date ? z.presale_end_date.split('T')[0].split(' ')[0] : '';

            const seatsCount = Array.isArray(z.seats) ? z.seats.length : 0;
            const badgeSeats = document.getElementById('inspectorSeatsBadge');
            if (badgeSeats) badgeSeats.textContent = seatsCount > 0 ? `✓ ${seatsCount} butacas numeradas activas` : '';

            const btnRemove = document.getElementById('btnRemoveSeats');
            if (btnRemove) btnRemove.style.display = seatsCount > 0 ? 'block' : 'none';

            // Restaurar configuración de filas y asientos para la zona seleccionada
            const rowsInput = document.getElementById('seatGenRows');
            const colsInput = document.getElementById('seatGenCols');
            const rowTypeSelect = document.getElementById('seatGenRowType');
            const colTypeSelect = document.getElementById('seatGenColType');

            let savedRows = parseInt(z.seat_rows || z.rows) || null;
            let savedCols = parseInt(z.seat_cols || z.cols) || null;

            // Si no tiene rows/cols explícitos pero tiene asientos en z.seats, deducirlos automáticamente
            if ((!savedRows || !savedCols) && Array.isArray(z.seats) && z.seats.length > 0) {
                const uniqueRows = new Set(z.seats.map(s => s.row).filter(Boolean));
                const uniqueCols = new Set(z.seats.map(s => s.col).filter(Boolean));
                if (uniqueRows.size > 0) savedRows = uniqueRows.size;
                if (uniqueCols.size > 0) savedCols = uniqueCols.size;
                z.seat_rows = savedRows;
                z.seat_cols = savedCols;
                z.rows = savedRows;
                z.cols = savedCols;
            }

            if (rowsInput) {
                rowsInput.value = savedRows || 5;
            }
            if (colsInput) {
                colsInput.value = savedCols || 10;
            }

            if (rowTypeSelect) {
                rowTypeSelect.value = z.seat_row_type || z.row_type || 'letters';
            }
            if (colTypeSelect) {
                colTypeSelect.value = z.seat_col_type || z.col_type || 'numbers';
            }

            this.updateSeatNomenclaturePreview();
        },

        renderZonesList: function() {
            const list = document.getElementById('zonesListContainer');
            const badge = document.getElementById('zonesCountBadge');
            const navBadge = document.getElementById('navInteractiveZoneCountBadge');
            const inspectorCard = document.getElementById('zoneInspectorCard');
            const inspectorEmpty = document.getElementById('zoneInspectorEmpty');

            if (badge) badge.textContent = this.zones.length;
            if (navBadge) navBadge.textContent = this.zones.length;

            const totalCap = this.zones.reduce((sum, z) => sum + (parseInt(z.capacity) || 0), 0);
            if (typeof recalculateTotalCapacity === 'function') {
                recalculateTotalCapacity();
            } else {
                const capEl = document.getElementById('calculatedTotalCapacity');
                if (capEl) capEl.textContent = totalCap.toLocaleString();
                const capSummaryEl = document.getElementById('totalCapacitySummaryText');
                if (capSummaryEl) capSummaryEl.textContent = totalCap.toLocaleString() + ' entradas';
            }
            if (!list) return;

            list.innerHTML = '';

            if (this.zones.length === 0) {
                list.innerHTML = `<span style="font-size: 0.75rem; color: #94A3B8; text-align: center; display: block; padding: 0.85rem; background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 10px;">No hay sectores creados aún. Usa las herramientas arriba para crear uno.</span>`;
                if (inspectorCard) inspectorCard.style.display = 'none';
                if (inspectorEmpty) inspectorEmpty.style.display = 'none';
                return;
            }

            let foundSelected = false;

            this.zones.forEach((z) => {
                const isSelected = (z.id === this.selectedZoneId);
                if (isSelected) foundSelected = true;

                const item = document.createElement('div');
                item.className = `zone-list-card ${isSelected ? 'active' : ''}`;
                item.onclick = () => {
                    if (this.selectedZoneId === z.id) {
                        this.selectedZoneId = null;
                        this.render();
                    } else {
                        this.selectZone(z.id);
                    }
                };

                const hasSeats = Array.isArray(z.seats) && z.seats.length > 0;

                item.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.6rem; min-width: 0;">
                        <span style="width: 14px; height: 14px; border-radius: 4px; background: ${z.color}; flex-shrink: 0; box-shadow: 0 0 8px ${z.color}88;"></span>
                        <div style="min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                                <strong style="color: #FFFFFF; font-size: 0.825rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 155px;">${z.name}</strong>
                                ${hasSeats ? '<span style="font-size: 0.625rem; background: rgba(16,185,129,0.2); color: #10B981; padding: 1px 5px; border-radius: 4px; font-weight: 800;">🪑 ' + z.seats.length + ' butacas</span>' : ''}
                                ${z.presale_enabled ? '<span style="font-size: 0.625rem; background: rgba(255,85,0,0.2); color: #FF5500; padding: 1px 4px; border-radius: 4px; font-weight: 800;">🔥 Preventa</span>' : ''}
                            </div>
                            <span style="color: #94A3B8; font-size: 0.7rem; display: block; margin-top: 2px;">${z.capacity} entradas • S/ ${z.price.toFixed(2)}</span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0;">
                        <span style="font-size: 0.7rem; font-weight: 700; color: ${isSelected ? '#FF5500' : '#64748B'};">${isSelected ? '▲ Cerrar' : '▼ Editar'}</span>
                        <button type="button" class="dash-btn-icon-action btn-delete-action" style="padding: 3px 5px; font-size: 0.75rem;" onclick="event.stopPropagation(); SeatMapEditor.deleteZoneById('${z.id}')" title="Eliminar Sector">🗑️</button>
                    </div>
                `;
                list.appendChild(item);

                // Si esta zona está seleccionada, colocar el inspector de propiedades directamente debajo de este card
                if (isSelected && inspectorCard) {
                    list.appendChild(inspectorCard);
                    inspectorCard.style.display = 'block';
                }
            });

            if (inspectorEmpty) {
                inspectorEmpty.style.display = foundSelected ? 'none' : 'block';
            }
        },

        deleteZoneById: function(id) {
            this.zones = this.zones.filter(z => z.id !== id);
            if (this.selectedZoneId === id) {
                this.selectedZoneId = this.zones.length > 0 ? this.zones[0].id : null;
                this.selectedPointIdx = null;
            }
            this.render();
            this.syncToStandardTable();
        },

        syncToStandardTable: function() {
            const tbody = document.getElementById('zonesTableBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            this.zones.forEach((z) => {
                const tr = document.createElement('tr');
                tr.className = 'zone-row';
                tr.innerHTML = `
                    <td>
                        <select class="form-select-custom zone-capacity-type" style="font-size: 0.85rem; padding: 0.55rem;" onchange="SeatMapEditor.syncFromStandardTable()">
                            @foreach($capacityTypes as $ct)
                                <option value="{{ is_array($ct) ? $ct['name'] : $ct->name }}" ${z.capacity_type === '{{ is_array($ct) ? $ct['name'] : $ct->name }}' ? 'selected' : ''}>
                                    🏟️ {{ is_array($ct) ? $ct['name'] : $ct->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-input-custom zone-name-input" value="${z.name}" style="font-size: 0.85rem; padding: 0.55rem;" oninput="SeatMapEditor.syncFromStandardTable()">
                    </td>
                    <td>
                        <input type="number" class="form-input-custom zone-capacity-input" value="${z.capacity}" min="0" style="font-size: 0.85rem; padding: 0.55rem;" oninput="if(typeof recalculateTotalCapacity==='function')recalculateTotalCapacity(); SeatMapEditor.syncFromStandardTable()">
                    </td>
                    <td>
                        <input type="number" step="0.50" class="form-input-custom zone-price-input" value="${z.price.toFixed(2)}" min="0" style="font-size: 0.85rem; padding: 0.55rem; color: #10B981; font-weight: 800;" oninput="if(typeof updateZonePresaleCalc==='function')updateZonePresaleCalc(this); if(typeof recalculateTotalCapacity==='function')recalculateTotalCapacity(); SeatMapEditor.syncFromStandardTable()">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-toggle-presale" style="background: ${z.presale_enabled ? 'var(--color-primary-orange)' : 'rgba(255,85,0,0.15)'}; border: 1.5px solid #FF5500; color: ${z.presale_enabled ? '#FFFFFF' : '#FF5500'}; font-size: 0.775rem; font-weight: 800; padding: 0.45rem 0.65rem; border-radius: 8px; width: 100%; text-align: center;" onclick="if(typeof toggleZonePresaleBox==='function')toggleZonePresaleBox(this)">
                            🔥 ${z.presale_enabled ? 'Preventa Activa' : 'Configurar'}
                        </button>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="if(typeof removeZoneRow==='function')removeZoneRow(this)" title="Eliminar Zona">🗑️</button>
                    </td>
                `;
                tbody.appendChild(tr);

                const pDisc = z.presale_discount || 20;
                const pPrice = z.price * (1 - (pDisc / 100));
                const trPresale = document.createElement('tr');
                trPresale.className = 'zone-presale-row';
                trPresale.style.display = z.presale_enabled ? 'table-row' : 'none';
                trPresale.style.background = 'rgba(255, 85, 0, 0.03)';
                trPresale.innerHTML = `
                    <td colspan="6" style="padding: 0.85rem 1.25rem; border-bottom: 1.5px solid rgba(255,85,0,0.25);">
                        <div style="background: rgba(15,23,42,0.8); border: 1.5px dashed rgba(255,85,0,0.4); border-radius: 12px; padding: 1rem 1.25rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0;">
                                    <input type="checkbox" class="zone-presale-enabled" ${z.presale_enabled ? 'checked' : ''} onchange="if(typeof togglePresaleInputs==='function')togglePresaleInputs(this)" style="accent-color: #FF5500; width: 18px; height: 18px;">
                                    <strong style="color: #FF5500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px;">🔥 Activar Preventa para esta Zona</strong>
                                </label>
                                <span class="presale-preview-badge" style="font-size: 0.75rem; font-weight: 800; color: #FFFFFF; background: linear-gradient(135deg, #FF5500, #FF1E3C); padding: 3px 10px; border-radius: 6px;">
                                    🔥 Precio Preventa: S/ ${pPrice.toFixed(2)} (-${pDisc}%)
                                </span>
                            </div>
                            <div class="zone-presale-inputs-grid" style="display: grid; grid-template-columns: 1fr 1.2fr 1.5fr 1.5fr 1.2fr; gap: 0.75rem;">
                                <div>
                                    <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">% DESCUENTO</label>
                                    <input type="number" class="form-input-custom zone-presale-discount" value="${pDisc}" min="0" max="99" style="font-size: 0.825rem; padding: 0.45rem;" oninput="if(typeof updateZonePresaleCalc==='function')updateZonePresaleCalc(this)">
                                </div>
                                <div>
                                    <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">PRECIO PREVENTA (S/)</label>
                                    <input type="number" step="0.50" class="form-input-custom zone-presale-price" value="${pPrice.toFixed(2)}" min="0" style="font-size: 0.825rem; padding: 0.45rem; color: #38BDF8; font-weight: 800;" readonly>
                                </div>
                                <div>
                                    <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">FECHA INICIO</label>
                                    <input type="date" class="form-input-custom zone-presale-start" value="${z.presale_start_date ? z.presale_start_date.split('T')[0].split(' ')[0] : new Date().toISOString().slice(0,10)}" style="font-size: 0.825rem; padding: 0.45rem;">
                                </div>
                                <div>
                                    <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">FECHA FIN (LÍMITE)</label>
                                    <input type="date" class="form-input-custom zone-presale-end" value="${z.presale_end_date ? z.presale_end_date.split('T')[0].split(' ')[0] : new Date(Date.now() + 15*86400000).toISOString().slice(0,10)}" style="font-size: 0.825rem; padding: 0.45rem;">
                                </div>
                                <div>
                                    <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">STOCK PREVENTA</label>
                                    <input type="number" class="form-input-custom zone-presale-stock" value="" min="0" style="font-size: 0.825rem; padding: 0.45rem;" placeholder="Hasta agotar">
                                </div>
                            </div>
                        </div>
                    </td>
                `;
                tbody.appendChild(trPresale);
            });

            if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
            if (typeof syncCourtesyZonesTable === 'function') syncCourtesyZonesTable();
        },

        syncFromStandardTable: function() {
            const rows = document.querySelectorAll('#zonesTableBody .zone-row');
            if (rows.length === 0) return;

            rows.forEach((row, idx) => {
                const name = row.querySelector('.zone-name-input')?.value?.trim() || `Zona ${idx + 1}`;
                const cap = parseInt(row.querySelector('.zone-capacity-input')?.value) || 0;
                const price = parseFloat(row.querySelector('.zone-price-input')?.value) || 0;
                const capType = row.querySelector('.zone-capacity-type')?.value || 'Campo';

                if (this.zones[idx]) {
                    this.zones[idx].name = name;
                    this.zones[idx].capacity = cap;
                    this.zones[idx].price = price;
                    this.zones[idx].capacity_type = capType;
                }
            });

            this.render();
        },

        getSvgCoordinates: function(e) {
            const svg = document.getElementById('seatMapSvg');
            if (!svg) return { x: 0, y: 0 };
            const pt = svg.createSVGPoint();
            pt.x = e.clientX;
            pt.y = e.clientY;
            return pt.matrixTransform(svg.getScreenCTM().inverse());
        },

        onSvgClick: function(e) {
            this.hideContextMenu();

            const coords = this.getSvgCoordinates(e);
            const x = Math.round(coords.x);
            const y = Math.round(coords.y);

            if (this.currentTool === 'polygon') {
                if (this.drawingPoints.length >= 3) {
                    const first = this.drawingPoints[0];
                    const dist = Math.hypot(x - first.x, y - first.y);
                    if (dist < 20) {
                        this.finishPolygonDrawing();
                        return;
                    }
                }

                this.drawingPoints.push({ x, y });
                this.renderPreviewLayer();
            } else if (this.currentTool === 'rect') {
                if (!this.rectStartPoint) {
                    this.rectStartPoint = { x, y };
                    this.drawingPoints = [{ x, y }];
                } else {
                    const p1 = this.rectStartPoint;
                    const p2 = { x, y };
                    const minX = Math.min(p1.x, p2.x);
                    const maxX = Math.max(p1.x, p2.x);
                    const minY = Math.min(p1.y, p2.y);
                    const maxY = Math.max(p1.y, p2.y);

                    if (maxX - minX > 20 && maxY - minY > 20) {
                        const newZone = {
                            id: 'zone_rect_' + Date.now(),
                            name: 'Campo',
                            capacity: 100,
                            price: 50.00,
                            capacity_type: 'Campo',
                            color: '#FF5500',
                            presale_enabled: false,
                            presale_discount: 20,
                            points: [
                                { x: minX, y: minY },
                                { x: maxX, y: minY },
                                { x: maxX, y: maxY },
                                { x: minX, y: maxY }
                            ],
                            seats: []
                        };
                        this.zones.push(newZone);
                        this.selectedZoneId = newZone.id;
                        this.selectedPointIdx = null;
                        this.setTool('select');
                        this.render();
                        this.syncToStandardTable();
                    }
                    this.rectStartPoint = null;
                    this.drawingPoints = [];
                }
            }
        },

        onSvgMouseMove: function(e) {
            if (this.currentTool === 'polygon' && this.drawingPoints.length > 0) {
                const coords = this.getSvgCoordinates(e);
                const last = this.drawingPoints[this.drawingPoints.length - 1];
                let previewLine = document.getElementById('tempRubberBandLine');
                if (!previewLine) {
                    previewLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    previewLine.setAttribute('id', 'tempRubberBandLine');
                    previewLine.setAttribute('stroke', '#FF5500');
                    previewLine.setAttribute('stroke-width', (1.5 / this.zoom).toFixed(2));
                    previewLine.setAttribute('stroke-dasharray', `${(3 / this.zoom).toFixed(1)} ${(2 / this.zoom).toFixed(1)}`);
                    document.getElementById('svgDrawPreviewGroup')?.appendChild(previewLine);
                }
                previewLine.setAttribute('x1', last.x);
                previewLine.setAttribute('y1', last.y);
                previewLine.setAttribute('x2', Math.round(coords.x));
                previewLine.setAttribute('y2', Math.round(coords.y));
            }
        },

        onSvgDoubleClick: function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (this.currentTool === 'polygon' && this.drawingPoints.length >= 3) {
                this.finishPolygonDrawing();
            }
        },

        finishPolygonDrawing: function() {
            if (this.drawingPoints.length < 3) {
                alert('Un polígono necesita al menos 3 vértices.');
                return;
            }

            const newZone = {
                id: 'zone_poly_' + Date.now(),
                name: 'Campo',
                capacity: 100,
                price: 50.00,
                capacity_type: 'Campo',
                color: '#FF5500',
                presale_enabled: false,
                presale_discount: 20,
                points: [...this.drawingPoints],
                seats: []
            };

            this.zones.push(newZone);
            this.selectedZoneId = newZone.id;
            this.selectedPointIdx = null;
            this.drawingPoints = [];
            this.setTool('select');
            this.render();
            this.syncToStandardTable();
        },

        cancelPolygonDrawing: function() {
            this.drawingPoints = [];
            this.rectStartPoint = null;
            this.setTool('select');
        },

        onHandleMouseMove: function(e) {
            if (!SeatMapEditor.draggingHandle) return;
            const coords = SeatMapEditor.getSvgCoordinates(e);
            const z = SeatMapEditor.zones.find(item => item.id === SeatMapEditor.draggingHandle.zoneId);
            if (z && z.points[SeatMapEditor.draggingHandle.pointIdx]) {
                z.points[SeatMapEditor.draggingHandle.pointIdx].x = Math.round(coords.x);
                z.points[SeatMapEditor.draggingHandle.pointIdx].y = Math.round(coords.y);
                SeatMapEditor.renderSvgZones();
                SeatMapEditor.renderSvgHandles();
                SeatMapEditor.renderTransformGizmo();
            }
        },

        onHandleMouseUp: function() {
            SeatMapEditor.draggingHandle = null;
            window.removeEventListener('mousemove', SeatMapEditor.onHandleMouseMove);
            window.removeEventListener('mouseup', SeatMapEditor.onHandleMouseUp);
            SeatMapEditor.syncToStandardTable();
        },

        zoomIn: function() {
            this.zoom = Math.min(4.0, this.zoom + 0.2);
            this.render();
        },

        zoomOut: function() {
            this.zoom = Math.max(0.3, this.zoom - 0.2);
            this.render();
        },

        resetView: function() {
            this.zoom = 1.0;
            this.pan = { x: 0, y: 0 };
            this.render();
        },

        setBgOpacity: function(val) {
            this.bgOpacity = val / 100;
            const img = document.getElementById('seatMapBgImage');
            if (img) img.style.opacity = this.bgOpacity;
            const lbl = document.getElementById('mapOpacityLabel');
            if (lbl) lbl.textContent = `${val}%`;
        },

        updateZoomTransform: function() {
            const layer = document.getElementById('seatMapTransformLayer');
            const zoomDisplay = document.getElementById('zoomLevelDisplay');
            if (layer) {
                layer.style.transform = `translate(${this.pan.x}px, ${this.pan.y}px) scale(${this.zoom})`;
            }
            if (zoomDisplay) {
                zoomDisplay.textContent = `${Math.round(this.zoom * 100)}%`;
            }
        },

        onViewportMouseDown: function(e) {
            if (e.target.tagName === 'rect' || e.target.tagName === 'polygon' || e.target.tagName === 'circle') return;
            if (this.currentTool !== 'select') return;

            this.selectedPointIdx = null;
            this.renderSvgHandles();

            this.isPanning = true;
            this.panStart = { x: e.clientX - this.pan.x, y: e.clientY - this.pan.y };
            const viewport = document.getElementById('seatMapViewport');
            if (viewport) viewport.style.cursor = 'grabbing';

            const onMouseMove = (ev) => {
                if (!this.isPanning) return;
                this.pan.x = ev.clientX - this.panStart.x;
                this.pan.y = ev.clientY - this.panStart.y;
                this.updateZoomTransform();
            };

            const onMouseUp = () => {
                this.isPanning = false;
                if (viewport) viewport.style.cursor = 'default';
                window.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('mouseup', onMouseUp);
            };

            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', onMouseUp);
        },

        onViewportWheel: function(e) {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.12 : 0.12;
            this.zoom = Math.min(4.0, Math.max(0.3, this.zoom + delta));
            this.render();
        },

        toggleBuyerPreview: function() {
            this.isBuyerPreview = !this.isBuyerPreview;
            const btn = document.getElementById('btnBuyerPreviewToggle');
            if (btn) {
                btn.style.background = this.isBuyerPreview ? '#06B6D4' : 'rgba(6, 182, 212, 0.15)';
                btn.style.color = this.isBuyerPreview ? '#000000' : '#06B6D4';
            }
            this.render();
        },

        setReferenceImage: function(url) {
            const input = document.getElementById('reference_image');
            const preview = document.getElementById('referencePreviewImg');
            const placeholder = document.getElementById('referencePlaceholderBox');
            const container = document.getElementById('referencePreviewContainer');

            if (input) input.value = url;
            if (preview) preview.src = url;
            if (placeholder) placeholder.style.display = url ? 'none' : 'block';
            if (container) container.style.display = url ? 'block' : 'none';

            this.updateImageBadge();
        },

        updateImageBadge: function() {
            const refImg = document.getElementById('reference_image')?.value;
            const badge = document.getElementById('mapImageStatusBadge');
            const bgImg = document.getElementById('seatMapBgImage');
            const placeholder = document.getElementById('noBgPlaceholderBadge');

            if (refImg) {
                if (badge) {
                    badge.textContent = 'Plano Cargado';
                    badge.className = 'dash-badge-custom badge-green';
                }
                if (bgImg) {
                    bgImg.src = refImg;
                    bgImg.style.display = 'block';
                }
                if (placeholder) placeholder.style.display = 'none';
            } else {
                if (badge) {
                    badge.textContent = 'Sin Plano';
                    badge.className = 'dash-badge-custom badge-orange';
                }
                if (bgImg) {
                    bgImg.style.display = 'none';
                }
                if (placeholder) placeholder.style.display = 'block';
            }
        }
    };

    function switchStep2Mode(mode) {
        window.currentStep2ZoneMode = mode;
        const btnStandard = document.getElementById('btnStep2ModeStandard');
        const btnInteractive = document.getElementById('btnStep2ModeInteractive');
        const standardContainer = document.getElementById('step2StandardContainer');
        const interactiveContainer = document.getElementById('step2InteractiveContainer');

        if (mode === 'standard') {
            if (btnStandard) btnStandard.classList.add('active');
            if (btnInteractive) btnInteractive.classList.remove('active');
            if (standardContainer) standardContainer.style.display = 'block';
            if (interactiveContainer) interactiveContainer.style.display = 'none';
            SeatMapEditor.syncToStandardTable();
            if (typeof syncCourtesyZonesTable === 'function') {
                syncCourtesyZonesTable();
            }
            if (typeof recalculateTotalCapacity === 'function') {
                recalculateTotalCapacity();
            }
        } else {
            if (btnInteractive) btnInteractive.classList.add('active');
            if (btnStandard) btnStandard.classList.remove('active');
            if (standardContainer) standardContainer.style.display = 'none';
            if (interactiveContainer) interactiveContainer.style.display = 'block';
            SeatMapEditor.syncFromStandardTable();
            SeatMapEditor.updateImageBadge();
            SeatMapEditor.render();
            if (typeof syncCourtesyZonesTable === 'function') {
                syncCourtesyZonesTable();
            }
            if (typeof recalculateTotalCapacity === 'function') {
                recalculateTotalCapacity();
            }
        }
    }

    // Auto-inicializar cuando cargue el script
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        SeatMapEditor.init();
    } else {
        document.addEventListener('DOMContentLoaded', () => SeatMapEditor.init());
    }
</script>
