<!-- CONTENEDOR INTEGRADO: SELECTOR DE MODO PASO 2 (ESTÁNDAR vs MAPA INTERACTIVO VECTORIAL) -->
<div class="step2-mode-selector-wrapper" style="margin-bottom: 1.5rem; background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 18px; padding: 0.85rem 1.15rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <div class="card-header-icon" style="background: rgba(255, 85, 0, 0.15); border-color: rgba(255, 85, 0, 0.3); color: #FF5500; font-size: 1.1rem; width: 38px; height: 38px;">⚙️</div>
        <div>
            <strong style="color: #FFFFFF; font-size: 0.95rem; display: block;">Modo de Configuración de Zonas</strong>
            <span style="color: #94A3B8; font-size: 0.775rem;">Elige entre el mapa estándar con imagen referencial o el diseñador interactivo de zonas & butacas</span>
        </div>
    </div>

    <!-- Pestañas Conmutadoras de Modo -->
    <div class="step2-mode-tabs" style="display: inline-flex; gap: 0.35rem; background: rgba(15, 23, 42, 0.7); padding: 4px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.12);">
        <button type="button" class="btn-step2-tab active" id="btnStep2ModeStandard" onclick="switchStep2Mode('standard')">
            🖼️ Modo Estándar (Imagen)
        </button>
        <button type="button" class="btn-step2-tab" id="btnStep2ModeInteractive" onclick="switchStep2Mode('interactive')">
            🗺️ Modo Interactivo (Zonas & Butacas)
        </button>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODO 2: CONSTRUCTOR INTERACTIVO EN 2 COLUMNAS (ESTILO ELEMENTOR + CANVA) -->
<!-- ========================================================================= -->
<div id="step2InteractiveContainer" style="display: none; margin-bottom: 2rem;">
    <div style="display: grid; grid-template-columns: 390px 1fr; gap: 1.25rem; align-items: start; min-height: 660px;">
        
        <!-- COLUMNA IZQUIERDA: PANEL INSPECTOR & HERRAMIENTAS (ESTILO ELEMENTOR) -->
        <div style="background: #14141E; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1.15rem; max-height: 760px; overflow-y: auto;" class="custom-scrollbar">
            
            <!-- 1. SECCIÓN: MAPA / PLANO DE FONDO -->
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 14px; padding: 0.95rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                    <strong style="color: #FFFFFF; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>📐</span> <span>Plano del Recinto / Fondo</span>
                    </strong>
                    <span id="mapImageStatusBadge" class="dash-badge-custom badge-orange" style="font-size: 0.675rem; font-weight: 800;">
                        Sin Plano
                    </span>
                </div>

                <!-- Botones de Carga de Imagen de Plano -->
                <div id="builderImageUploadBox" style="display: flex; gap: 0.5rem; margin-bottom: 0.65rem;">
                    <button type="button" class="btn btn-primary btn-save-settings" style="flex: 1; font-size: 0.775rem; padding: 0.45rem 0.65rem;" onclick="openMediaManager('reference_image');">
                        🖼️ De Galería
                    </button>
                    <button type="button" class="btn btn-cancel-custom" style="flex: 1; font-size: 0.775rem; padding: 0.45rem 0.65rem;" onclick="document.getElementById('referenceFileInput').click();">
                        📁 Subir de PC
                    </button>
                    <button type="button" class="btn btn-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171; font-size: 0.775rem; padding: 0.45rem 0.65rem; border-radius: 8px;" onclick="removeReferenceImage()" title="Quitar Plano">
                        🗑️
                    </button>
                </div>

                <!-- Botón Inteligente: Auto-Vectorizar Zonas -->
                <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                    <button type="button" id="btnAutoVectorizeZones" class="btn-auto-vectorize" onclick="SeatMapEditor.autoVectorize()" style="width: 100%; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); color: #FFFFFF; border: none; border-radius: 10px; padding: 0.6rem 0.85rem; font-size: 0.825rem; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35); transition: all 0.2s ease;">
                        <span>⚡ Auto-Detectar y Vectorizar Zonas</span>
                    </button>
                </div>
            </div>

            <!-- 2. SECCIÓN: BARRA DE HERRAMIENTAS DE DIBUJO & FORMAS -->
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.45rem;">
                    <span style="font-size: 0.725rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">
                        🛠️ Herramientas de Trazado
                    </span>
                    <span style="font-size: 0.675rem; color: #10B981; font-weight: 700;">Tip: Arrastra del centro para mover</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.45rem;">
                    <button type="button" class="tool-btn active" id="toolBtnSelect" onclick="SeatMapEditor.setTool('select')" title="Seleccionar, mover o redimensionar zonas">
                        <span>🖐️</span> <strong>Seleccionar / Mover</strong>
                    </button>
                    <button type="button" class="tool-btn" id="toolBtnPolygon" onclick="SeatMapEditor.setTool('polygon')" title="Clic para fijar puntos, doble clic para cerrar">
                        <span>📐</span> <strong>Polígono Libre</strong>
                    </button>
                    <button type="button" class="tool-btn" id="toolBtnArc" onclick="SeatMapEditor.createCurvedArcZone()" title="Crear Media Luna / Arco Curvo para tribuna o popular">
                        <span>🌙</span> <strong>Media Luna / Arco</strong>
                    </button>
                    <button type="button" class="tool-btn" id="toolBtnLateral" onclick="SeatMapEditor.createLateralStandZone()" title="Crear Tribuna Lateral Oriente / Occidente">
                        <span>🏛️</span> <strong>Tribuna Lateral</strong>
                    </button>
                    <button type="button" class="tool-btn" id="toolBtnRect" onclick="SeatMapEditor.setTool('rect')" title="Arrastra en el lienzo para crear un sector rectangular">
                        <span>⏹️</span> <strong>Rectángulo</strong>
                    </button>
                    <button type="button" class="tool-btn" id="toolBtnStage" onclick="SeatMapEditor.createStageZone()" title="Crear Escenario / Tarima principal en la parte superior">
                        <span>🎪</span> <strong>Escenario / Tarima</strong>
                    </button>
                    <button type="button" class="tool-btn" id="toolBtnAddManual" onclick="SeatMapEditor.createManualZone()" title="Crear sector cuadrado en el centro">
                        <span>➕</span> <strong>Zona Básica</strong>
                    </button>
                </div>
            </div>

            <!-- 3. SECCIÓN: LISTA DE SECTORES CREADOS -->
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.725rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">
                        🎟️ Sectores Creados (<span id="zonesCountBadge">0</span>)
                    </span>
                    <button type="button" class="btn btn-link" style="font-size: 0.7rem; color: #EF4444; padding: 0; text-decoration: none;" onclick="SeatMapEditor.clearAllZones()">
                        Limpiar Todo
                    </button>
                </div>
                <div id="zonesListContainer" style="display: flex; flex-direction: column; gap: 0.55rem;">
                    <!-- Se llena dinámicamente con los cards de sectores -->
                </div>
            </div>

            <!-- Mensaje cuando NO hay zona seleccionada -->
            <div id="zoneInspectorEmpty" style="background: rgba(255, 255, 255, 0.02); border: 1.5px dashed rgba(255, 255, 255, 0.12); border-radius: 16px; padding: 1.5rem 1rem; text-align: center;">
                <span style="font-size: 1.8rem; display: block; margin-bottom: 0.35rem;">👆</span>
                <strong style="color: #E2E8F0; font-size: 0.85rem; display: block;">Ningún Sector Seleccionado</strong>
                <p style="color: #94A3B8; font-size: 0.75rem; margin: 0.25rem 0 0 0;">Haz clic en un sector de la lista o en el plano para ver y editar sus propiedades.</p>
            </div>

            <!-- 4. SECCIÓN: INSPECTOR DE PROPIEDADES (Se inserta dinámicamente debajo del card correspondiente en Sectores Creados) -->
            <div id="zoneInspectorCard" style="background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 85, 0, 0.4); border-radius: 14px; padding: 1.15rem; display: none; width: 100%; box-sizing: border-box;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
                    <strong style="color: #FF5500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 0.35rem;">
                        <span>✏️</span> <span>Propiedades de Zona</span>
                    </strong>
                    <div style="display: flex; gap: 0.35rem;">
                        <button type="button" class="dash-btn-icon-action" onclick="SeatMapEditor.duplicateSelectedZone()" title="Duplicar Zona" style="padding: 4px 6px; font-size: 0.8rem; background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.4); color: #60A5FA; border-radius: 6px; cursor: pointer;">
                            📑
                        </button>
                        <button type="button" class="dash-btn-icon-action btn-delete-action" onclick="SeatMapEditor.deleteSelectedZone()" title="Eliminar Zona Seleccionada" style="padding: 4px 6px;">
                            🗑️
                        </button>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <!-- Nombre de Zona con Sugerencias Rápidas -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700;">NOMBRE DE LA ZONA</label>
                            <span style="font-size: 0.65rem; color: #94A3B8;">Sugerencias abajo</span>
                        </div>
                        <input type="text" id="inspectorZoneName" class="form-input-custom" placeholder="Ej: ZONA VIP" style="font-size: 0.85rem; padding: 0.5rem;" oninput="SeatMapEditor.updateSelectedZoneProps()">
                        
                        <!-- Píldoras de nombres rápidos -->
                        <div style="display: flex; gap: 0.25rem; flex-wrap: wrap; margin-top: 0.35rem;">
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('ZONA VIP')">VIP</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('PLATINUM')">PLATINUM</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('PREFERENCIAL')">PREFERENCIAL</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('APDAYC')">APDAYC</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('POPULAR')">POPULAR</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('ORIENTE')">ORIENTE</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('OCCIDENTE')">OCCIDENTE</span>
                            <span class="name-preset-pill" onclick="SeatMapEditor.setZoneNamePreset('PALCOS')">PALCOS</span>
                        </div>
                    </div>

                    <!-- Color de Zona -->
                    <input type="hidden" id="inspectorZoneCapacityType" value="General">
                    <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 0.5rem 0.75rem; width: 100%; box-sizing: border-box;">
                        <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; margin: 0;">COLOR DE LA ZONA</label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="color" id="inspectorZoneColor" value="#FF5500" style="width: 34px; height: 30px; border: none; border-radius: 6px; cursor: pointer; background: transparent; padding: 0;" onchange="SeatMapEditor.updateSelectedZoneProps()">
                            <span id="inspectorZoneColorHex" style="font-size: 0.75rem; font-family: monospace; color: #94A3B8; font-weight: 700;">#FF5500</span>
                        </div>
                    </div>

                    <!-- Paleta de Colores Rápidos -->
                    <div>
                        <span style="font-size: 0.675rem; color: #94A3B8; display: block; margin-bottom: 0.3rem;">Paleta Rápida:</span>
                        <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                            <span class="color-preset" style="background: #FF5500;" onclick="SeatMapEditor.setZonePresetColor('#FF5500')" title="Naranja"></span>
                            <span class="color-preset" style="background: #10B981;" onclick="SeatMapEditor.setZonePresetColor('#10B981')" title="Verde Esmeralda"></span>
                            <span class="color-preset" style="background: #3B82F6;" onclick="SeatMapEditor.setZonePresetColor('#3B82F6')" title="Azul"></span>
                            <span class="color-preset" style="background: #8B5CF6;" onclick="SeatMapEditor.setZonePresetColor('#8B5CF6')" title="Morado"></span>
                            <span class="color-preset" style="background: #EC4899;" onclick="SeatMapEditor.setZonePresetColor('#EC4899')" title="Rosa"></span>
                            <span class="color-preset" style="background: #F59E0B;" onclick="SeatMapEditor.setZonePresetColor('#F59E0B')" title="Dorado / Mostaza"></span>
                            <span class="color-preset" style="background: #06B6D4;" onclick="SeatMapEditor.setZonePresetColor('#06B6D4')" title="Cyan"></span>
                            <span class="color-preset" style="background: #E11D48;" onclick="SeatMapEditor.setZonePresetColor('#E11D48')" title="Rojo Carmesí"></span>
                            <span class="color-preset" style="background: #854D0E;" onclick="SeatMapEditor.setZonePresetColor('#854D0E')" title="Marrón APDAYC"></span>
                        </div>
                    </div>

                    <!-- Precio Regular y Aforo -->
                    <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 0.65rem; width: 100%; box-sizing: border-box;">
                        <div style="min-width: 0;">
                            <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">PRECIO REGULAR (S/)</label>
                            <input type="number" step="0.50" id="inspectorZonePrice" class="form-input-custom" value="50.00" min="0" style="width: 100%; box-sizing: border-box; font-size: 0.85rem; padding: 0.45rem; color: #10B981; font-weight: 800;" oninput="SeatMapEditor.updateSelectedZoneProps()">
                        </div>
                        <div style="min-width: 0;">
                            <label style="font-size: 0.725rem; color: #CBD5E1; font-weight: 700; display: block; margin-bottom: 0.25rem;">AFORO (STOCK)</label>
                            <input type="number" id="inspectorZoneCapacity" class="form-input-custom" value="100" min="1" style="width: 100%; box-sizing: border-box; font-size: 0.85rem; padding: 0.45rem;" oninput="SeatMapEditor.updateSelectedZoneProps()">
                        </div>
                    </div>

                    <!-- Preventa Rápida -->
                    <div style="background: rgba(255,85,0,0.05); border: 1px dashed rgba(255,85,0,0.3); border-radius: 10px; padding: 0.65rem; width: 100%; box-sizing: border-box;">
                        <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; margin: 0 0 0.4rem 0;">
                            <input type="checkbox" id="inspectorZonePresaleEnabled" class="orange-checkbox" onchange="SeatMapEditor.updateSelectedZoneProps()">
                            <span style="font-size: 0.75rem; font-weight: 800; color: #FF5500;">🔥 Activar Preventa</span>
                        </label>
                        <div id="inspectorPresaleGrid" style="display: flex; flex-direction: column; gap: 0.45rem; opacity: 0.5; pointer-events: none; width: 100%; box-sizing: border-box;">
                            <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 0.5rem; width: 100%; box-sizing: border-box;">
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.65rem; color: #94A3B8;">% DCTO.</label>
                                    <input type="number" id="inspectorZonePresaleDiscount" class="form-input-custom" value="20" min="1" max="99" style="width: 100%; box-sizing: border-box; font-size: 0.775rem; padding: 0.35rem;" oninput="SeatMapEditor.updateSelectedZoneProps()">
                                </div>
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.65rem; color: #94A3B8;">PRECIO PREVENTA</label>
                                    <input type="text" id="inspectorZonePresalePriceDisplay" class="form-input-custom" value="S/ 40.00" readonly style="width: 100%; box-sizing: border-box; font-size: 0.775rem; padding: 0.35rem; color: #38BDF8; font-weight: 800;">
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 0.5rem; width: 100%; box-sizing: border-box;">
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.65rem; color: #94A3B8;">FECHA INICIO</label>
                                    <input type="date" id="inspectorZonePresaleStartDate" class="form-input-custom" style="width: 100%; box-sizing: border-box; font-size: 0.75rem; padding: 0.35rem; color: #E2E8F0;" onchange="SeatMapEditor.updateSelectedZoneProps()">
                                </div>
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.65rem; color: #94A3B8;">FECHA FIN</label>
                                    <input type="date" id="inspectorZonePresaleEndDate" class="form-input-custom" style="width: 100%; box-sizing: border-box; font-size: 0.75rem; padding: 0.35rem; color: #E2E8F0;" onchange="SeatMapEditor.updateSelectedZoneProps()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Generador de Butacas Numeradas (Acordeón) -->
                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 0.75rem; width: 100%; box-sizing: border-box;">
                        <div style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="document.getElementById('seatGenAccordionBody').classList.toggle('open');">
                            <strong style="font-size: 0.775rem; color: #E2E8F0; display: flex; align-items: center; gap: 0.35rem;">
                                <span>🪑</span> <span>Generar Butacas Numeradas</span>
                            </strong>
                            <span style="font-size: 0.75rem; color: #94A3B8;">▼</span>
                        </div>
                        <div id="seatGenAccordionBody" class="seat-gen-accordion" style="display: none; padding-top: 0.65rem; margin-top: 0.5rem; border-top: 1px dashed rgba(255,255,255,0.1);">
                            <!-- Dimensiones de cuadrícula -->
                            <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 0.5rem; margin-bottom: 0.55rem; width: 100%; box-sizing: border-box;">
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.675rem; color: #94A3B8; display: block; margin-bottom: 0.2rem;">CANTIDAD DE FILAS</label>
                                    <input type="number" id="seatGenRows" class="form-input-custom" value="5" min="1" max="26" style="width: 100%; box-sizing: border-box; font-size: 0.775rem; padding: 0.35rem;" oninput="SeatMapEditor.updateSeatNomenclaturePreview()">
                                </div>
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.675rem; color: #94A3B8; display: block; margin-bottom: 0.2rem;">ASIENTOS X FILA</label>
                                    <input type="number" id="seatGenCols" class="form-input-custom" value="10" min="1" max="50" style="width: 100%; box-sizing: border-box; font-size: 0.775rem; padding: 0.35rem;" oninput="SeatMapEditor.updateSeatNomenclaturePreview()">
                                </div>
                            </div>

                            <!-- Nomenclatura configurable: Filas y Columnas -->
                            <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 0.5rem; margin-bottom: 0.55rem; width: 100%; box-sizing: border-box;">
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.675rem; color: #94A3B8; display: block; margin-bottom: 0.2rem;">NOMBRAR FILAS</label>
                                    <select id="seatGenRowType" class="form-select-custom" style="width: 100%; box-sizing: border-box; font-size: 0.75rem; padding: 0.35rem;" onchange="SeatMapEditor.updateSeatNomenclaturePreview()">
                                        <option value="letters">Letras (A, B, C...)</option>
                                        <option value="numbers">Números (1, 2, 3...)</option>
                                    </select>
                                </div>
                                <div style="min-width: 0;">
                                    <label style="font-size: 0.675rem; color: #94A3B8; display: block; margin-bottom: 0.2rem;">NOMBRAR ASIENTOS</label>
                                    <select id="seatGenColType" class="form-select-custom" style="width: 100%; box-sizing: border-box; font-size: 0.75rem; padding: 0.35rem;" onchange="SeatMapEditor.updateSeatNomenclaturePreview()">
                                        <option value="numbers">Números (1, 2, 3...)</option>
                                        <option value="letters">Letras (A, B, C...)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Previsualización de Nomenclatura -->
                            <div style="background: rgba(0, 240, 255, 0.05); border: 1px dashed rgba(0, 240, 255, 0.3); border-radius: 8px; padding: 0.45rem 0.6rem; margin-bottom: 0.65rem;">
                                <div style="font-size: 0.65rem; color: #94A3B8; margin-bottom: 0.15rem;">CÓDIGO DE BUTACA:</div>
                                <div id="seatNomenclaturePreview" style="font-size: 0.75rem; font-weight: 800; color: #00F0FF; font-family: monospace;">
                                    Fila A - Asiento 1 (A-1)
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm" onclick="SeatMapEditor.generateSeatsForSelectedZone()" style="width: 100%; background: rgba(16, 185, 129, 0.15); border: 1.5px solid #10B981; color: #10B981; font-weight: 800; font-size: 0.75rem; padding: 0.45rem; border-radius: 8px; cursor: pointer;">
                                🪑 Poblar Zona con Butacas
                            </button>
                            <button type="button" id="btnRemoveSeats" class="btn btn-sm" onclick="SeatMapEditor.clearSeatsForSelectedZone()" style="width: 100%; margin-top: 0.45rem; background: rgba(239, 68, 68, 0.15); border: 1.5px solid #EF4444; color: #F87171; font-weight: 800; font-size: 0.75rem; padding: 0.45rem; border-radius: 8px; cursor: pointer; display: none;">
                                🗑️ Quitar Butacas
                            </button>
                            <span id="inspectorSeatsBadge" style="display: block; font-size: 0.7rem; color: #10B981; margin-top: 0.4rem; text-align: center; font-weight: 700;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: LIENZO VECTORIAL INTERACTIVO (ESTILO CANVA) -->
        <div style="background: #0B0F19; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; height: 760px; position: relative;">
            
            <!-- BARRA SUPERIOR DE CONTROLES DEL LIENZO -->
            <div style="background: #14141E; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 0.65rem 1.15rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.65rem; z-index: 10;">
                
                <!-- Indicador de Herramienta / Estado y Acciones Rápidas -->
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <span id="canvasModeIndicator" class="dash-badge-custom badge-blue" style="font-size: 0.75rem; font-weight: 800;">
                        🖐️ Modo Selección
                    </span>
                    <div id="polygonActionPill" style="display: none; align-items: center; gap: 0.35rem;">
                        <button type="button" class="btn btn-sm" onclick="SeatMapEditor.finishPolygonDrawing()" style="background: #10B981; color: #FFFFFF; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 6px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(16,185,129,0.4);">
                            ✅ Cerrar Polígono (Enter)
                        </button>
                        <button type="button" class="btn btn-sm" onclick="SeatMapEditor.cancelPolygonDrawing()" style="background: rgba(239,68,68,0.2); color: #F87171; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.55rem; border-radius: 6px; border: 1px solid rgba(239,68,68,0.4); cursor: pointer;">
                            ✕ Cancelar (Esc)
                        </button>
                    </div>
                </div>

                <!-- Controles de Zoom & Opacidad -->
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <!-- Opacidad de Plano -->
                    <div style="display: flex; align-items: center; gap: 0.4rem;">
                        <span style="font-size: 0.75rem; color: #94A3B8;">Plano:</span>
                        <input type="range" id="mapOpacitySlider" min="0" max="100" value="85" style="width: 80px; accent-color: #FF5500; cursor: pointer;" oninput="SeatMapEditor.setBgOpacity(this.value)">
                        <span id="mapOpacityLabel" style="font-size: 0.7rem; color: #CBD5E1; font-family: monospace; width: 30px;">85%</span>
                    </div>

                    <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.1);"></div>

                    <!-- Botones de Zoom -->
                    <div style="display: flex; align-items: center; gap: 0.25rem; background: rgba(255,255,255,0.05); padding: 2px; border-radius: 8px;">
                        <button type="button" class="btn-canvas-ctrl" onclick="SeatMapEditor.zoomOut()" title="Alejar (Zoom -)">➖</button>
                        <span id="zoomLevelDisplay" style="font-size: 0.75rem; font-family: monospace; color: #FFFFFF; padding: 0 0.4rem; font-weight: 700;">100%</span>
                        <button type="button" class="btn-canvas-ctrl" onclick="SeatMapEditor.zoomIn()" title="Acercar (Zoom +)">➕</button>
                        <button type="button" class="btn-canvas-ctrl" onclick="SeatMapEditor.resetView()" title="Centrar y Ajustar">🔄</button>
                    </div>

                    <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.1);"></div>

                    <!-- Botón Vista Previa Comprador -->
                    <button type="button" class="btn btn-sm" onclick="SeatMapEditor.toggleBuyerPreview()" id="btnBuyerPreviewToggle" style="background: rgba(6, 182, 212, 0.15); border: 1.5px solid #06B6D4; color: #06B6D4; font-size: 0.75rem; font-weight: 800; padding: 0.35rem 0.65rem; border-radius: 8px; cursor: pointer;">
                        👁️ Vista Comprador
                    </button>
                </div>
            </div>

            <!-- VIEWPORT DEL LIENZO VECTORIAL (SOPORTA PAN & ZOOM) -->
            <div id="seatMapViewport" style="flex: 1; position: relative; overflow: hidden; background-color: #080B11; background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px); background-size: 20px 20px;" onmousedown="SeatMapEditor.onViewportMouseDown(event)" onwheel="SeatMapEditor.onViewportWheel(event)">
                
                <!-- Contenedor Transformable (Aplica Pan y Zoom) -->
                <div id="seatMapTransformLayer" style="position: absolute; transform-origin: 0 0; width: 1000px; height: 650px; transition: transform 0.04s ease-out;">
                    
                    <!-- Imagen de Fondo (Plano) -->
                    <img id="seatMapBgImage" src="{{ $eventData['reference_image'] ?? '' }}" alt="Plano de Fondo" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; pointer-events: none; opacity: 0.85; display: {{ !empty($eventData['reference_image']) ? 'block' : 'none' }};">

                    <!-- Lienzo de Dibujo SVG Interactivo -->
                    <svg id="seatMapSvg" width="1000" height="650" viewBox="0 0 1000 650" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: visible;" onclick="SeatMapEditor.onSvgClick(event)" onmousemove="SeatMapEditor.onSvgMouseMove(event)" ondblclick="SeatMapEditor.onSvgDoubleClick(event)">
                        <defs>
                            <!-- Filtro de sombra suave para etiquetas de zona -->
                            <filter id="labelShadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#000000" flood-opacity="0.8" />
                            </filter>
                        </defs>

                        <!-- Capa 1: Zonas Vectoriales (Polígonos y Rectángulos) -->
                        <g id="svgZonesGroup"></g>

                        <!-- Capa 2: Asientos / Butacas numeradas -->
                        <g id="svgSeatsGroup"></g>

                        <!-- Capa 3: Etiquetas de Texto con Nombres y Precios -->
                        <g id="svgLabelsGroup"></g>

                        <!-- Capa 4: Puntos de control / Vértices arrastrables (Auto-escalables) -->
                        <g id="svgHandlesGroup"></g>

                        <!-- Capa 5: Caja de Transformación Externa (Agrandar, Achicar y Rotar) -->
                        <g id="svgTransformGizmoGroup"></g>

                        <!-- Capa 6: Vista previa de trazado en vivo -->
                        <g id="svgDrawPreviewGroup"></g>
                    </svg>
                </div>

                <!-- Placeholder cuando NO hay plano cargado -->
                <div id="noBgPlaceholderBadge" style="position: absolute; bottom: 20px; left: 20px; background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 0.75rem 1rem; pointer-events: none; max-width: 340px; backdrop-filter: blur(8px); display: {{ !empty($eventData['reference_image']) ? 'none' : 'block' }};">
                    <strong style="color: #FFFFFF; font-size: 0.8rem; display: flex; align-items: center; gap: 0.35rem;">
                        <span>💡</span> <span>Lienzo Listo en Blanco</span>
                    </strong>
                    <p style="color: #94A3B8; font-size: 0.725rem; margin: 0.2rem 0 0 0;">
                        Sube una imagen de tu plano o pulsa <strong>"Plantilla Estadio / Teatro"</strong> para comenzar.
                    </p>
                </div>

                <!-- MENÚ CONTEXTUAL FLOTANTE (CLIC DERECHO EN ZONA) -->
                <div id="zoneContextMenu" style="display: none; position: fixed; z-index: 99999; background: #14141E; border: 1.5px solid rgba(255, 85, 0, 0.4); border-radius: 12px; padding: 0.4rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.05); min-width: 220px; backdrop-filter: blur(12px);">
                    <div style="padding: 0.35rem 0.65rem; border-bottom: 1px solid rgba(255, 255, 255, 0.08); margin-bottom: 0.3rem;">
                        <strong id="ctxMenuZoneName" style="color: #FFFFFF; font-size: 0.775rem; display: block; text-transform: uppercase; letter-spacing: 0.5px;">ZONA</strong>
                        <span id="ctxMenuZoneSub" style="color: #94A3B8; font-size: 0.675rem;">Opciones avanzadas de vectorización</span>
                    </div>

                    <button type="button" class="ctx-menu-item" onclick="SeatMapEditor.autoVectorizeSelectedSingleZone()">
                        <span style="color: #6366F1;">⚡</span> <strong>Auto-Ajustar / Calcar esta Zona</strong>
                    </button>
                    <button type="button" class="ctx-menu-item" onclick="SeatMapEditor.convertSelectedZoneToRect()">
                        <span style="color: #FF5500;">⏹️</span> <strong>Convertir a Rectángulo (4 Vértices)</strong>
                    </button>
                    <button type="button" class="ctx-menu-item" onclick="SeatMapEditor.convertSelectedZoneToArc()">
                        <span style="color: #F59E0B;">🌙</span> <strong>Convertir a Media Luna / Arco</strong>
                    </button>
                    <button type="button" class="ctx-menu-item" onclick="SeatMapEditor.convertSelectedZoneToLateral()">
                        <span style="color: #EC4899;">🏛️</span> <strong>Convertir a Tribuna Lateral</strong>
                    </button>

                    <div style="height: 1px; background: rgba(255, 255, 255, 0.08); margin: 0.3rem 0;"></div>

                    <button type="button" class="ctx-menu-item" onclick="SeatMapEditor.duplicateSelectedZone()">
                        <span style="color: #38BDF8;">📑</span> <strong>Duplicar Zona</strong>
                    </button>
                    <button type="button" class="ctx-menu-item item-danger" onclick="SeatMapEditor.deleteSelectedZone()">
                        <span style="color: #EF4444;">🗑️</span> <strong>Eliminar Zona (Supr)</strong>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- BARRA DE NAVEGACIÓN Y CONTINUACIÓN A PASO 3 EN MODO INTERACTIVO -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; background: rgba(255, 255, 255, 0.03); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 18px; padding: 1rem 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <button type="button" class="btn btn-cancel-custom" onclick="goToStep(1)" style="padding: 0.75rem 1.25rem; font-size: 0.9rem;">
            ← Anterior: Información General
        </button>

        <div style="display: flex; align-items: center; gap: 0.85rem;">
            <span style="color: #94A3B8; font-size: 0.825rem;">
                🗺️ Zonas configuradas: <strong id="navInteractiveZoneCountBadge" style="color: #10B981; font-weight: 800;">0</strong>
            </span>
            <button type="button" class="btn btn-primary btn-save-settings" style="padding: 0.85rem 2.2rem; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.4);" onclick="SeatMapEditor.syncToStandardTable(); goToStep(3);">
                <span>Continuar a Plantilla Canva (Paso 3)</span> ➔
            </button>
        </div>
    </div>
</div>

<style>
    .btn-step2-tab {
        background: transparent;
        color: #94A3B8;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 9px;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-step2-tab:hover {
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.05);
    }
    .btn-step2-tab.active {
        background: linear-gradient(135deg, #FF5500 0%, #FF2E00 100%);
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(255, 85, 0, 0.3);
    }

    .tool-btn {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 0.55rem 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        color: #E2E8F0;
        font-size: 0.775rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .tool-btn:hover {
        background: rgba(255, 85, 0, 0.1);
        border-color: #FF5500;
        color: #FFFFFF;
    }
    .tool-btn.active {
        background: rgba(255, 85, 0, 0.2);
        border-color: #FF5500;
        color: #FF5500;
        font-weight: 800;
        box-shadow: 0 0 0 1px #FF5500;
    }

    .name-preset-pill {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #CBD5E1;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .name-preset-pill:hover {
        background: #FF5500;
        color: #FFFFFF;
        border-color: #FF5500;
    }

    .color-preset {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        cursor: pointer;
        border: 1.5px solid rgba(255,255,255,0.4);
        transition: transform 0.15s ease;
    }
    .color-preset:hover {
        transform: scale(1.15);
        border-color: #FFFFFF;
    }

    .btn-canvas-ctrl {
        background: transparent;
        border: none;
        color: #CBD5E1;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.75rem;
        transition: background 0.15s;
    }
    .btn-canvas-ctrl:hover {
        background: rgba(255,255,255,0.15);
        color: #FFFFFF;
    }

    .zone-list-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 0.55rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .zone-list-card:hover {
        background: rgba(255, 255, 255, 0.07);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .zone-list-card.active {
        background: rgba(255, 85, 0, 0.12);
        border-color: #FF5500;
    }

    /* SVG Hover & Drag Effects */
    .svg-zone-polygon {
        cursor: move !important;
        transition: fill-opacity 0.2s ease, stroke-width 0.2s ease;
    }
    .svg-zone-polygon:hover {
        fill-opacity: 0.75 !important;
        stroke-width: 3.5px !important;
        filter: drop-shadow(0 0 10px rgba(255, 85, 0, 0.6));
    }
    .svg-zone-polygon.selected {
        stroke-width: 4px !important;
        stroke-dasharray: 6 3;
        animation: svgDashAnim 1s linear infinite;
    }
    @keyframes svgDashAnim {
        to { stroke-dashoffset: -18; }
    }

    .svg-handle-circle {
        cursor: crosshair;
        transition: r 0.15s ease;
    }
    .svg-handle-circle:hover {
        r: 8px !important;
    }

    .seat-gen-accordion.open {
        display: block !important;
    }

    /* Context Menu Styles */
    .ctx-menu-item {
        width: 100%;
        background: transparent;
        border: none;
        border-radius: 8px;
        padding: 0.45rem 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #E2E8F0;
        font-size: 0.775rem;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: left;
    }
    .ctx-menu-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #FFFFFF;
    }
    .ctx-menu-item.item-danger:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #F87171;
    }

    #zoneInspectorCard {
        box-sizing: border-box !important;
        max-width: 100% !important;
    }
    #zoneInspectorCard input,
    #zoneInspectorCard select,
    #zoneInspectorCard .form-input-custom,
    #zoneInspectorCard .form-select-custom {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
</style>
