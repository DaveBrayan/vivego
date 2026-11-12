<script>
    /**
     * Helpers compartidos para el Paso 2 (Estándar e Interactivo):
     * - Sincronización de "Distribución de Aforo y Cortesías por Zona"
     * - Restricción automática: Zonas con Butacas Numeradas no permiten Venta Física
     */
    function isStageZoneHelper(name, capType, obj) {
        const zName = String(name || '').trim().toUpperCase();
        const zCapType = String(capType || '').trim().toUpperCase();
        return zName === 'ESCENARIO' || zName === 'TARIMA' || zName.includes('ESCENARIO') || zName.includes('TARIMA')
            || zCapType.includes('ESCENARIO') || zCapType.includes('TARIMA')
            || !!(obj && (obj.type === 'stage' || obj.is_stage || obj.is_area));
    }

    function isZoneNumberedSeats(zoneOrMeta) {
        if (!zoneOrMeta) return false;
        if (isStageZoneHelper(zoneOrMeta.name, zoneOrMeta.capacity_type, zoneOrMeta)) return false;

        const capType = String(zoneOrMeta.capacity_type || zoneOrMeta.capacityType || '').toLowerCase();
        const hasSeatsArray = Array.isArray(zoneOrMeta.seats) && zoneOrMeta.seats.length > 0;
        const rowsCount = parseInt(zoneOrMeta.seat_rows || zoneOrMeta.rows || 0, 10) || 0;
        const colsCount = parseInt(zoneOrMeta.seat_cols || zoneOrMeta.cols || 0, 10) || 0;
        const hasSeatGrid = rowsCount > 0 && colsCount > 0;

        return !!zoneOrMeta.isNumbered
            || !!zoneOrMeta.is_numbered
            || hasSeatsArray
            || hasSeatGrid
            || capType.includes('butaca')
            || capType.includes('numerad');
    }

    function quickSwitchSalesTypeFromQuota(targetType) {
        const map = {
            fisica: 'salesTypeFisica',
            virtual: 'salesTypeVirtual',
            ambos: 'salesTypeAmbos'
        };
        const radio = document.getElementById(map[targetType]);
        if (radio) {
            radio.checked = true;
            if (typeof updateSalesTypeStyles === 'function') {
                updateSalesTypeStyles();
            } else if (typeof applySalesTypeToQuotaInputs === 'function') {
                applySalesTypeToQuotaInputs();
            }
        }
    }

    function resolveStep2ZoneListForQuota() {
        const isInteractiveMode = (window.currentStep2ZoneMode === 'interactive');
        const hasEditorZones = (typeof SeatMapEditor !== 'undefined' && Array.isArray(SeatMapEditor.zones));
        const zoneList = [];

        if (isInteractiveMode && hasEditorZones) {
            SeatMapEditor.zones.forEach((z, idx) => {
                if (typeof isStageZone === 'function' && isStageZone(z)) return;
                if (isStageZoneHelper(z.name, z.capacity_type, z)) return;

                zoneList.push({
                    name: (z.name || '').trim() || `Zona ${idx + 1}`,
                    capacity: parseInt(z.capacity, 10) || 0,
                    price: parseFloat(z.price) || 0,
                    capacity_type: z.capacity_type || '',
                    seatsCount: Array.isArray(z.seats) ? z.seats.length : 0,
                    isNumbered: isZoneNumberedSeats(z)
                });
            });
            return zoneList;
        }

        const zoneRows = document.querySelectorAll('#zonesTableBody .zone-row');
        if (zoneRows.length > 0) {
            const editorCommercialZones = hasEditorZones
                ? SeatMapEditor.zones.filter(z => !isStageZoneHelper(z.name, z.capacity_type, z))
                : [];

            zoneRows.forEach((row, idx) => {
                const name = row.querySelector('.zone-name-input')?.value?.trim() || `Zona ${idx + 1}`;
                const capType = row.querySelector('.zone-capacity-type')?.value?.trim() || '';
                if (isStageZoneHelper(name, capType)) return;

                const matchingEditorZone = editorCommercialZones.find(ez =>
                    String(ez.name || '').trim().toUpperCase() === name.toUpperCase()
                ) || editorCommercialZones[idx] || null;

                const isNumbered = isZoneNumberedSeats({
                    name: name,
                    capacity_type: capType,
                    seats: matchingEditorZone?.seats,
                    seat_rows: matchingEditorZone?.seat_rows || matchingEditorZone?.rows,
                    seat_cols: matchingEditorZone?.seat_cols || matchingEditorZone?.cols
                });

                zoneList.push({
                    name: name,
                    capacity: parseInt(row.querySelector('.zone-capacity-input')?.value, 10) || 0,
                    price: parseFloat(row.querySelector('.zone-price-input')?.value) || 0,
                    capacity_type: capType,
                    seatsCount: Array.isArray(matchingEditorZone?.seats) ? matchingEditorZone.seats.length : 0,
                    isNumbered: isNumbered
                });
            });
        } else if (hasEditorZones && SeatMapEditor.zones.length > 0) {
            SeatMapEditor.zones.forEach((z, idx) => {
                if (typeof isStageZone === 'function' && isStageZone(z)) return;
                if (isStageZoneHelper(z.name, z.capacity_type, z)) return;

                zoneList.push({
                    name: (z.name || '').trim() || `Zona ${idx + 1}`,
                    capacity: parseInt(z.capacity, 10) || 0,
                    price: parseFloat(z.price) || 0,
                    capacity_type: z.capacity_type || '',
                    seatsCount: Array.isArray(z.seats) ? z.seats.length : 0,
                    isNumbered: isZoneNumberedSeats(z)
                });
            });
        }

        return zoneList;
    }

    function applySalesTypeToQuotaInputs() {
        const salesType = typeof getEventSalesType === 'function' ? getEventSalesType() : 'fisica';
        const badgeEl = document.getElementById('salesModalityBadge');
        const noticeEl = document.getElementById('salesTypeNoticeBox');

        if (badgeEl) {
            if (salesType === 'ambos') {
                badgeEl.style.background = 'rgba(168, 85, 247, 0.18)';
                badgeEl.style.border = '1px solid rgba(168, 85, 247, 0.4)';
                badgeEl.style.color = '#C084FC';
                badgeEl.innerHTML = '🎫🌐 Venta Híbrida (Físico y Digital Activos)';
            } else if (salesType === 'fisica') {
                badgeEl.style.background = 'rgba(255, 85, 0, 0.18)';
                badgeEl.style.border = '1px solid rgba(255, 85, 0, 0.4)';
                badgeEl.style.color = '#FFAA80';
                badgeEl.innerHTML = '🎫 Solo Venta Física (Digital Bloqueado)';
            } else {
                badgeEl.style.background = 'rgba(56, 189, 248, 0.18)';
                badgeEl.style.border = '1px solid rgba(56, 189, 248, 0.4)';
                badgeEl.style.color = '#38BDF8';
                badgeEl.innerHTML = '🌐 Solo Venta Digital (Físico Bloqueado)';
            }
        }

        const courtesyForUsersWrapper = document.getElementById('courtesyForUsersWrapper');
        const courtesyUserMaxWrapper = document.getElementById('courtesyUserMaxWrapper');
        if (courtesyForUsersWrapper) {
            courtesyForUsersWrapper.style.display = (salesType === 'fisica') ? 'none' : 'inline-flex';
        }
        if (courtesyUserMaxWrapper) {
            courtesyUserMaxWrapper.style.display = (salesType === 'fisica') ? 'none' : 'flex';
        }

        let hasNumberedZone = false;
        document.querySelectorAll('#quotaSplitZonesBody tr[data-zone-name]').forEach(row => {
            const totalCap = parseInt(row.getAttribute('data-zone-total'), 10) || 0;
            const isNumbered = row.getAttribute('data-numbered-seats') === '1';
            if (isNumbered) hasNumberedZone = true;

            const physInput = row.querySelector('.quota-zone-phys-input');
            const virtInput = row.querySelector('.quota-zone-virt-input');
            const cortPhysInput = row.querySelector('.quota-zone-cort-phys-input');
            const cortVirtInput = row.querySelector('.quota-zone-cort-virt-input');

            if (isNumbered) {
                // Butacas numeradas NUNCA permiten Venta Física
                if (physInput) {
                    physInput.value = 0;
                    physInput.disabled = true;
                    physInput.style.opacity = '0.35';
                    physInput.style.cursor = 'not-allowed';
                    physInput.title = 'Bloqueado: las zonas con Butacas Numeradas no permiten Venta Física';
                }

                if (salesType === 'fisica') {
                    if (virtInput) {
                        virtInput.value = 0;
                        virtInput.disabled = true;
                        virtInput.style.opacity = '0.35';
                        virtInput.style.cursor = 'not-allowed';
                        virtInput.title = 'Bloqueado por Modalidad Solo Venta Física';
                    }
                    if (cortVirtInput) {
                        cortVirtInput.value = 0;
                        cortVirtInput.disabled = true;
                        cortVirtInput.style.opacity = '0.35';
                        cortVirtInput.style.cursor = 'not-allowed';
                        cortVirtInput.title = 'Bloqueado por Modalidad Solo Venta Física';
                    }
                    if (cortPhysInput) {
                        cortPhysInput.disabled = false;
                        cortPhysInput.style.opacity = '1';
                        cortPhysInput.style.cursor = 'auto';
                        cortPhysInput.title = 'Cortesías físicas para taquilla o planchas';
                    }
                } else {
                    // virtual o ambos -> 100% de butacas numeradas pasan a Venta Digital
                    if (virtInput) {
                        virtInput.value = totalCap;
                        virtInput.disabled = true;
                        virtInput.style.opacity = '1';
                        virtInput.style.cursor = 'default';
                        virtInput.title = '100% asignado a Venta Digital (Butacas Numeradas no permite Venta Física)';
                    }
                    if (cortVirtInput) {
                        cortVirtInput.disabled = false;
                        cortVirtInput.style.opacity = '1';
                        cortVirtInput.style.cursor = 'auto';
                        cortVirtInput.title = 'Cortesías digitales para web';
                    }
                    if (cortPhysInput) {
                        if (salesType === 'virtual') {
                            cortPhysInput.value = 0;
                            cortPhysInput.disabled = true;
                            cortPhysInput.style.opacity = '0.35';
                            cortPhysInput.style.cursor = 'not-allowed';
                            cortPhysInput.title = 'Bloqueado por Modalidad Solo Venta Digital';
                        } else {
                            cortPhysInput.disabled = false;
                            cortPhysInput.style.opacity = '1';
                            cortPhysInput.style.cursor = 'auto';
                            cortPhysInput.title = '';
                        }
                    }
                }
            } else if (salesType === 'fisica') {
                if (virtInput) {
                    virtInput.value = 0;
                    virtInput.disabled = true;
                    virtInput.style.opacity = '0.35';
                    virtInput.style.cursor = 'not-allowed';
                    virtInput.title = 'Bloqueado por Modalidad Solo Venta Física';
                }
                if (cortVirtInput) {
                    cortVirtInput.value = 0;
                    cortVirtInput.disabled = true;
                    cortVirtInput.style.opacity = '0.35';
                    cortVirtInput.style.cursor = 'not-allowed';
                    cortVirtInput.title = 'Bloqueado por Modalidad Solo Venta Física';
                }
                if (physInput) {
                    physInput.value = totalCap;
                    physInput.disabled = true;
                    physInput.style.opacity = '1';
                    physInput.style.cursor = 'default';
                    physInput.title = '100% asignado a venta física';
                }
                if (cortPhysInput) {
                    cortPhysInput.disabled = false;
                    cortPhysInput.style.opacity = '1';
                    cortPhysInput.style.cursor = 'auto';
                    cortPhysInput.title = 'Cortesías físicas para taquilla o planchas';
                }
            } else if (salesType === 'virtual') {
                if (physInput) {
                    physInput.value = 0;
                    physInput.disabled = true;
                    physInput.style.opacity = '0.35';
                    physInput.style.cursor = 'not-allowed';
                    physInput.title = 'Bloqueado por Modalidad Solo Venta Digital';
                }
                if (cortPhysInput) {
                    cortPhysInput.value = 0;
                    cortPhysInput.disabled = true;
                    cortPhysInput.style.opacity = '0.35';
                    cortPhysInput.style.cursor = 'not-allowed';
                    cortPhysInput.title = 'Bloqueado por Modalidad Solo Venta Digital';
                }
                if (virtInput) {
                    virtInput.value = totalCap;
                    virtInput.disabled = true;
                    virtInput.style.opacity = '1';
                    virtInput.style.cursor = 'default';
                    virtInput.title = '100% asignado a venta digital';
                }
                if (cortVirtInput) {
                    cortVirtInput.disabled = false;
                    cortVirtInput.style.opacity = '1';
                    cortVirtInput.style.cursor = 'auto';
                    cortVirtInput.title = 'Cortesías digitales para web';
                }
            } else {
                // Ambos (híbrido) en zona normal
                if (physInput) {
                    physInput.disabled = false;
                    physInput.style.opacity = '1';
                    physInput.style.cursor = 'auto';
                    physInput.title = '';
                }
                if (virtInput) {
                    virtInput.disabled = false;
                    virtInput.style.opacity = '1';
                    virtInput.style.cursor = 'auto';
                    virtInput.title = '';
                }
                if (cortPhysInput) {
                    cortPhysInput.disabled = false;
                    cortPhysInput.style.opacity = '1';
                    cortPhysInput.style.cursor = 'auto';
                    cortPhysInput.title = '';
                }
                if (cortVirtInput) {
                    cortVirtInput.disabled = false;
                    cortVirtInput.style.opacity = '1';
                    cortVirtInput.style.cursor = 'auto';
                    cortVirtInput.title = '';
                }
            }

            updateQuotaRowState(row, totalCap);
        });

        if (noticeEl) {
            if (hasNumberedZone && salesType === 'fisica') {
                noticeEl.style.background = 'rgba(239, 68, 68, 0.12)';
                noticeEl.style.borderColor = 'rgba(239, 68, 68, 0.45)';
                noticeEl.innerHTML = `
                    <span style="font-size: 1rem;">⚠️</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <span><strong>Butacas Numeradas</strong> no permite Venta Física.</span>
                        <button type="button" onclick="quickSwitchSalesTypeFromQuota('ambos')" style="background: rgba(168,85,247,0.22); border: 1px solid rgba(168,85,247,0.5); color: #E9D5FF; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 6px; cursor: pointer;">⚖️ Cambiar a Híbrida</button>
                        <button type="button" onclick="quickSwitchSalesTypeFromQuota('virtual')" style="background: rgba(56,189,248,0.22); border: 1px solid rgba(56,189,248,0.5); color: #BAE6FD; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.55rem; border-radius: 6px; cursor: pointer;">🌐 Solo Digital</button>
                    </div>
                `;
            } else {
                noticeEl.style.background = 'rgba(255,255,255,0.04)';
                noticeEl.style.borderColor = 'rgba(255,255,255,0.1)';
                if (salesType === 'fisica') {
                    noticeEl.innerHTML = '<span style="font-size: 1rem;">🔒</span><span>Modalidad <strong>Solo Venta Física</strong>: Venta y cortesía digital bloqueadas (0).</span>';
                } else if (salesType === 'virtual') {
                    noticeEl.innerHTML = '<span style="font-size: 1rem;">🔒</span><span>Modalidad <strong>Solo Venta Digital</strong>: Venta y cortesía física bloqueadas (0).</span>';
                } else if (hasNumberedZone) {
                    noticeEl.innerHTML = '<span style="font-size: 1rem;">🪑</span><span><strong>Venta Híbrida</strong>: Las zonas con Butacas Numeradas quedan bloqueadas en Venta Física (solo Digital).</span>';
                } else {
                    noticeEl.innerHTML = '<span style="font-size: 1rem;">⚖️</span><span>Modalidad <strong>Venta Híbrida</strong>: Canales físicos y digitales activos para reparto libre.</span>';
                }
            }
        }

        if (typeof recalculateTotalCapacity === 'function') {
            recalculateTotalCapacity();
        }
    }

    function syncQuotaSplitTable() {
        const tbody = document.getElementById('quotaSplitZonesBody');
        if (!tbody) return;

        const salesType = typeof getEventSalesType === 'function' ? getEventSalesType() : 'fisica';
        const savedQ = (typeof savedQuotaZones !== 'undefined' && Array.isArray(savedQuotaZones)) ? savedQuotaZones : [];
        const savedC = (typeof savedCourtesyZones !== 'undefined' && Array.isArray(savedCourtesyZones)) ? savedCourtesyZones : [];

        // Preservar valores actualmente ingresados por el usuario
        const currentInputs = {};
        tbody.querySelectorAll('tr[data-zone-name]').forEach(row => {
            const zName = row.getAttribute('data-zone-name');
            if (zName) {
                currentInputs[zName] = {
                    phys: row.querySelector('.quota-zone-phys-input')?.value ?? '',
                    virt: row.querySelector('.quota-zone-virt-input')?.value ?? '',
                    cortPhys: row.querySelector('.quota-zone-cort-phys-input')?.value ?? '',
                    cortVirt: row.querySelector('.quota-zone-cort-virt-input')?.value ?? '',
                    prevTotal: parseInt(row.getAttribute('data-zone-total'), 10) || 0,
                    wasNumbered: row.getAttribute('data-numbered-seats') === '1'
                };
            }
        });

        const zoneList = resolveStep2ZoneListForQuota();
        tbody.innerHTML = '';

        if (zoneList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #94A3B8; padding: 1.75rem;">Configura sectores en la tabla de zonas superior para distribuir sus aforos y cortesías.</td></tr>`;
            if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
            return;
        }

        zoneList.forEach((z) => {
            const zName = z.name;
            const zCap = z.capacity;
            const isNumbered = !!z.isNumbered;
            const cleanUpper = zName.trim().toUpperCase();

            let physVal = '';
            let virtVal = '';
            let cortPhysVal = '';
            let cortVirtVal = '';

            if (currentInputs[zName]) {
                const prev = currentInputs[zName];
                cortPhysVal = prev.cortPhys;
                cortVirtVal = prev.cortVirt;
                if (isNumbered) {
                    physVal = 0;
                    virtVal = (salesType === 'fisica') ? 0 : zCap;
                } else if (prev.prevTotal !== zCap && salesType === 'ambos') {
                    // Si cambió el aforo total de la zona en modo híbrido, reajustar manteniendo proporción simple
                    const oldP = Math.min(zCap, Math.max(0, parseInt(prev.phys, 10) || 0));
                    physVal = prev.wasNumbered ? zCap : oldP;
                    virtVal = zCap - physVal;
                } else if (prev.wasNumbered && !isNumbered && salesType === 'ambos') {
                    physVal = zCap;
                    virtVal = 0;
                } else {
                    physVal = prev.phys;
                    virtVal = prev.virt;
                }
            } else {
                if (savedQ.length > 0) {
                    const savedMatch = savedQ.find(s => (s.name || s.zone_name || '').trim().toUpperCase() === cleanUpper);
                    if (savedMatch) {
                        if (savedMatch.physical !== null && savedMatch.physical !== undefined) physVal = savedMatch.physical;
                        if (savedMatch.virtual !== null && savedMatch.virtual !== undefined) virtVal = savedMatch.virtual;
                        if (savedMatch.courtesy_physical !== null && savedMatch.courtesy_physical !== undefined) cortPhysVal = savedMatch.courtesy_physical;
                        if (savedMatch.courtesy_virtual !== null && savedMatch.courtesy_virtual !== undefined) cortVirtVal = savedMatch.courtesy_virtual;
                    }
                }

                if ((cortPhysVal === '' || cortVirtVal === '') && savedC.length > 0) {
                    const cMatch = savedC.find(s => (s.name || s.zone_name || '').trim().toUpperCase() === cleanUpper);
                    if (cMatch) {
                        if (cortPhysVal === '' && cMatch.physical_stock !== undefined) cortPhysVal = cMatch.physical_stock;
                        if (cortVirtVal === '' && cMatch.virtual_stock !== undefined) cortVirtVal = cMatch.virtual_stock;
                        if (cortPhysVal === '' && cMatch.stock !== undefined && salesType === 'fisica') cortPhysVal = cMatch.stock;
                        if (cortVirtVal === '' && cMatch.stock !== undefined && salesType === 'virtual') cortVirtVal = cMatch.stock;
                    }
                }

                if (isNumbered) {
                    physVal = 0;
                    virtVal = (salesType === 'fisica') ? 0 : zCap;
                } else if (physVal === '' && virtVal === '') {
                    if (salesType === 'virtual') {
                        physVal = 0;
                        virtVal = zCap;
                    } else {
                        physVal = zCap;
                        virtVal = 0;
                    }
                }

                if (cortPhysVal === '') cortPhysVal = 0;
                if (cortVirtVal === '') cortVirtVal = 0;
            }

            const pNum = parseInt(physVal, 10) || 0;
            const vNum = parseInt(virtVal, 10) || 0;
            const cpNum = parseInt(cortPhysVal, 10) || 0;
            const cvNum = parseInt(cortVirtVal, 10) || 0;
            const totalSector = (pNum + vNum) + (cpNum + cvNum);

            const numberedTag = isNumbered
                ? `<span style="display: inline-block; margin-top: 0.2rem; font-size: 0.68rem; font-weight: 800; color: #F59E0B; background: rgba(245, 158, 11, 0.14); border: 1px solid rgba(245, 158, 11, 0.35); padding: 1px 6px; border-radius: 6px;">🪑 Butacas Numeradas · Sin Venta Física</span>`
                : '';

            const tr = document.createElement('tr');
            tr.setAttribute('data-zone-name', zName);
            tr.setAttribute('data-zone-total', zCap);
            tr.setAttribute('data-numbered-seats', isNumbered ? '1' : '0');
            tr.innerHTML = `
                <td>
                    <strong style="color: #FFFFFF; font-size: 0.9rem; display: block;">${zName}</strong>
                    <span style="font-size: 0.72rem; color: #94A3B8;">Ref: S/ ${z.price ? z.price.toFixed(2) : '0.00'}</span>
                    ${numberedTag ? `<div style="margin-top: 2px;">${numberedTag}</div>` : ''}
                </td>
                <td style="text-align: center;">
                    <span class="dash-badge-custom badge-blue" style="font-size: 0.8rem; font-weight: 800;">${zCap.toLocaleString()}</span>
                </td>
                <td style="text-align: center;">
                    <input type="number" 
                           class="form-input-custom quota-zone-phys-input" 
                           value="${physVal}" 
                           min="0" 
                           max="${zCap}" 
                           style="font-size: 0.92rem; padding: 0.45rem 0.6rem; border: 1.5px solid rgba(255, 85, 0, 0.5); background: rgba(255, 85, 0, 0.08); font-weight: 800; color: #FF5500; text-align: center; width: 110px; border-radius: 8px; margin: 0 auto;" 
                           oninput="handleQuotaPhysChange(this, ${zCap})">
                </td>
                <td style="text-align: center;">
                    <input type="number" 
                           class="form-input-custom quota-zone-virt-input" 
                           value="${virtVal}" 
                           min="0" 
                           max="${zCap}" 
                           style="font-size: 0.92rem; padding: 0.45rem 0.6rem; border: 1.5px solid rgba(56, 189, 248, 0.5); background: rgba(56, 189, 248, 0.08); font-weight: 800; color: #38BDF8; text-align: center; width: 110px; border-radius: 8px; margin: 0 auto;" 
                           oninput="handleQuotaVirtChange(this, ${zCap})">
                </td>
                <td style="text-align: center;" class="quota-balance-cell"></td>
                <td style="text-align: center;">
                    <input type="number" 
                           class="form-input-custom quota-zone-cort-phys-input" 
                           value="${cortPhysVal}" 
                           min="0" 
                           max="99999" 
                           placeholder="0" 
                           style="font-size: 0.92rem; padding: 0.45rem 0.6rem; border: 1.5px solid rgba(16, 185, 129, 0.5); background: rgba(16, 185, 129, 0.08); font-weight: 800; color: #10B981; text-align: center; width: 100px; border-radius: 8px; margin: 0 auto;" 
                           oninput="handleCourtesyChange(this)">
                </td>
                <td style="text-align: center;">
                    <input type="number" 
                           class="form-input-custom quota-zone-cort-virt-input" 
                           value="${cortVirtVal}" 
                           min="0" 
                           max="99999" 
                           placeholder="0" 
                           style="font-size: 0.92rem; padding: 0.45rem 0.6rem; border: 1.5px solid rgba(168, 85, 247, 0.5); background: rgba(168, 85, 247, 0.08); font-weight: 800; color: #C084FC; text-align: center; width: 100px; border-radius: 8px; margin: 0 auto;" 
                           oninput="handleCourtesyChange(this)">
                </td>
                <td style="text-align: center;" class="quota-row-total-cell">
                    <strong style="color: #FFFFFF; font-size: 0.95rem; font-weight: 900;">${totalSector.toLocaleString()}</strong>
                    <span style="display: block; font-size: 0.7rem; color: #94A3B8;">${pNum + vNum} vta + ${cpNum + cvNum} cort</span>
                </td>
            `;
            tbody.appendChild(tr);
        });

        applySalesTypeToQuotaInputs();
    }

    function syncCourtesyZonesTable() {
        syncQuotaSplitTable();
    }

    function handleQuotaPhysChange(input, totalCap) {
        const row = input.closest('tr');
        if (!row) return;
        const virtInput = row.querySelector('.quota-zone-virt-input');
        const salesType = typeof getEventSalesType === 'function' ? getEventSalesType() : 'fisica';

        if (row.getAttribute('data-numbered-seats') === '1') {
            input.value = 0;
            if (virtInput) virtInput.value = (salesType === 'fisica') ? 0 : totalCap;
            updateQuotaRowState(row, totalCap);
            if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
            return;
        }

        const val = parseInt(input.value, 10);
        if (salesType === 'ambos' && !isNaN(val) && val >= 0) {
            const remaining = Math.max(0, totalCap - val);
            if (virtInput) virtInput.value = remaining;
        }
        updateQuotaRowState(row, totalCap);
        if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
    }

    function handleQuotaVirtChange(input, totalCap) {
        const row = input.closest('tr');
        if (!row) return;
        const physInput = row.querySelector('.quota-zone-phys-input');
        const salesType = typeof getEventSalesType === 'function' ? getEventSalesType() : 'fisica';

        if (row.getAttribute('data-numbered-seats') === '1') {
            if (physInput) physInput.value = 0;
            input.value = (salesType === 'fisica') ? 0 : totalCap;
            updateQuotaRowState(row, totalCap);
            if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
            return;
        }

        const val = parseInt(input.value, 10);
        if (salesType === 'ambos' && !isNaN(val) && val >= 0) {
            const remaining = Math.max(0, totalCap - val);
            if (physInput) physInput.value = remaining;
        }
        updateQuotaRowState(row, totalCap);
        if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
    }

    function handleCourtesyChange(input) {
        const row = input.closest('tr');
        if (!row) return;
        const totalCap = parseInt(row.getAttribute('data-zone-total'), 10) || 0;
        updateQuotaRowState(row, totalCap);
        if (typeof recalculateTotalCapacity === 'function') recalculateTotalCapacity();
    }

    function updateQuotaRowState(row, totalCap) {
        const isNumbered = row.getAttribute('data-numbered-seats') === '1';
        const salesType = typeof getEventSalesType === 'function' ? getEventSalesType() : 'fisica';
        const p = parseInt(row.querySelector('.quota-zone-phys-input')?.value, 10) || 0;
        const v = parseInt(row.querySelector('.quota-zone-virt-input')?.value, 10) || 0;
        const cp = parseInt(row.querySelector('.quota-zone-cort-phys-input')?.value, 10) || 0;
        const cv = parseInt(row.querySelector('.quota-zone-cort-virt-input')?.value, 10) || 0;

        const cell = row.querySelector('.quota-balance-cell');
        if (cell) {
            if (isNumbered && salesType === 'fisica') {
                cell.innerHTML = `<span class="quota-balance-badge" style="background: rgba(239, 68, 68, 0.16); border: 1px solid rgba(239, 68, 68, 0.45); color: #FCA5A5; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 6px; display: inline-block;">🚫 Solo Digital / Híbrida</span>`;
            } else {
                const isBalanced = (p + v) === totalCap;
                if (isBalanced && isNumbered) {
                    cell.innerHTML = `<span class="quota-balance-badge" style="background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.4); color: #7DD3FC; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 6px;">🪑 Solo Digital (${p}+${v}=${totalCap})</span>`;
                } else {
                    cell.innerHTML = isBalanced
                        ? `<span class="quota-balance-badge" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34D399; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 6px;">✓ Cuadrado (${p}+${v}=${totalCap})</span>`
                        : `<span class="quota-balance-badge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #FCA5A5; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 6px;">⚠️ Descuadrado (${p+v}≠${totalCap})</span>`;
                }
            }
        }

        const totalCell = row.querySelector('.quota-row-total-cell');
        if (totalCell) {
            const totalSector = (p + v) + (cp + cv);
            totalCell.innerHTML = `
                <strong style="color: #FFFFFF; font-size: 0.95rem; font-weight: 900;">${totalSector.toLocaleString()}</strong>
                <span style="display: block; font-size: 0.7rem; color: #94A3B8;">${p + v} vta + ${cp + cv} cort</span>
            `;
        }
    }

    function collectAndValidateQuotaSplitForSubmit(salesType, zones) {
        if (typeof syncQuotaSplitTable === 'function') {
            syncQuotaSplitTable();
        }

        const quotaSplitZones = [];
        let quotaSplitError = null;
        let quotaErrorTitle = 'Aforo Descuadrado';

        document.querySelectorAll('#quotaSplitZonesBody tr[data-zone-name]').forEach(row => {
            const zName = row.getAttribute('data-zone-name');
            const zTotal = parseInt(row.getAttribute('data-zone-total'), 10) || 0;
            const isNumbered = row.getAttribute('data-numbered-seats') === '1';
            let phys = parseInt(row.querySelector('.quota-zone-phys-input')?.value, 10) || 0;
            let virt = parseInt(row.querySelector('.quota-zone-virt-input')?.value, 10) || 0;
            const cortPhys = parseInt(row.querySelector('.quota-zone-cort-phys-input')?.value, 10) || 0;
            const cortVirt = parseInt(row.querySelector('.quota-zone-cort-virt-input')?.value, 10) || 0;

            if (isNumbered) {
                if (salesType === 'fisica') {
                    quotaErrorTitle = 'Restricción de Butacas Numeradas';
                    quotaSplitError = `La zona "${zName}" cuenta con Butacas Numeradas y no permite Venta Física. Cambia la modalidad de venta en el Paso 1 a "Solo Venta Digital" o "Venta Híbrida (Ambos)", o quita las butacas numeradas.`;
                } else {
                    phys = 0;
                    virt = zTotal;
                }
            } else if (salesType === 'ambos' && (phys + virt) !== zTotal) {
                quotaErrorTitle = 'Aforo Descuadrado';
                quotaSplitError = `La zona "${zName}" no coincide: Física (${phys}) + Digital (${virt}) = ${phys + virt}, debe sumar exactamente el aforo de venta (${zTotal}).`;
            }

            quotaSplitZones.push({
                name: zName,
                zone_name: zName,
                capacity: zTotal,
                physical: phys,
                virtual: virt,
                courtesy_physical: cortPhys,
                courtesy_virtual: cortVirt
            });
        });

        if (!quotaSplitError && Array.isArray(zones)) {
            for (const z of zones) {
                if (isZoneNumberedSeats(z) && salesType === 'fisica') {
                    quotaErrorTitle = 'Restricción de Butacas Numeradas';
                    quotaSplitError = `La zona "${z.name || 'Zona'}" cuenta con Butacas Numeradas y no permite Venta Física. Cambia la modalidad de venta en el Paso 1 a "Solo Venta Digital" o "Venta Híbrida (Ambos)".`;
                    break;
                }
            }
        }

        return {
            quotaSplitZones,
            quotaSplitError,
            quotaErrorTitle
        };
    }
</script>

