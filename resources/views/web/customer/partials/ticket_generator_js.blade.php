<!-- SweetAlert2, html2canvas, jsPDF y QRCode Generator Oficial -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    function generateQrBase64(payload) {
        try {
            const qr = qrcode(0, 'M');
            qr.addData(payload);
            qr.make();
            return qr.createDataURL(5, 0);
        } catch (e) {
            console.error('Error generando QR:', e);
            return '';
        }
    }

    async function preloadPosImageAsDataUrl(url, type = 'banner') {
        if (!url || typeof url !== 'string' || url.trim() === '') return '';
        if (url.startsWith('data:')) return url;
        try {
            if (url.startsWith('http://') || url.startsWith('https://')) {
                const parsed = new URL(url);
                if (parsed.origin !== window.location.origin) {
                    return url;
                }
            }
        } catch(e) {}
        try {
            const response = await fetch(url, { mode: 'cors', cache: 'force-cache' });
            if (response.ok) {
                const blob = await response.blob();
                const dataUrl = await new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                });
                if (dataUrl && dataUrl.startsWith('data:image')) return dataUrl;
            }
        } catch (e) {}
        try {
            const dataUrl = await new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                const timeout = setTimeout(() => reject(new Error('Timeout')), 3000);
                img.onload = () => {
                    clearTimeout(timeout);
                    try {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.naturalWidth || 600;
                        canvas.height = img.naturalHeight || 300;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        resolve(canvas.toDataURL('image/jpeg', 0.92));
                    } catch (err) { reject(err); }
                };
                img.onerror = () => { clearTimeout(timeout); resolve(url); };
                img.src = url;
            });
            return dataUrl || url;
        } catch (e) {
            return url;
        }
    }

    function convertPositionsToElements(positions) {
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
            const isDisclaimer = id === 'canvaElDisclaimer' || mapped.field === 'disclaimer' || (id && String(id).toLowerCase().includes('disclaimer'));
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
                resolvedTextAlign = defaultAlignMap[id] || (isDisclaimer ? 'center' : 'left');
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
    }

    function getRealFontFamily(fontName) {
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
    }

    function replaceDynamicValueInHtml(html, labelKeyword, newValue) {
        if (!html || typeof html !== 'string') return html;
        const cleanLabel = labelKeyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        
        const multiPRegex = new RegExp(`(<p[^>]*>\\s*${cleanLabel}:?\\s*<\\/p>\\s*<p[^>]*>)(.*?)(<\\/p>)`, 'gi');
        if (multiPRegex.test(html)) {
            return html.replace(multiPRegex, `$1${newValue}$3`);
        }

        const singleRegex = new RegExp(`(${cleanLabel}:?\\s*)((?:<[^>]+>\\s*)*)([^<\\s]+[^<]*)`, 'gi');
        if (singleRegex.test(html)) {
            return html.replace(singleRegex, `$1$2${newValue}`);
        }

        return html;
    }

    function renderTicketCanvasContent(template, dynamicData, assetMap = {}) {
        let elements = [];
        if (template && Array.isArray(template.elements) && template.elements.length > 0) {
            elements = template.elements;
        } else if (template && template.positions) {
            let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
            elements = convertPositionsToElements(rawPos);
        }

        const seenFields = new Set();
        const uniqueElements = [];
        for (let i = elements.length - 1; i >= 0; i--) {
            const el = elements[i];
            if (!el || el.hidden === true || el.display === 'none' || el.visible === false) continue;
            const key = el.field || el.id;
            if (key && (key.startsWith('canvaEl') || key === 'title' || key === 'zone' || key === 'price' || key === 'buyer_name' || key === 'buyer_dni' || key === 'ticket_number' || key === 'hash' || key === 'venue' || key === 'date' || key === 'time' || key === 'disclaimer')) {
                if (seenFields.has(key)) continue;
                seenFields.add(key);
            }
            uniqueElements.unshift(el);
        }
        elements = uniqueElements;

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
                    const bName = (dynamicData.buyer_name || 'CLIENTE VARIOS').toUpperCase();
                    if (/Comprador/i.test(rawTxt)) {
                        rawTxt = replaceDynamicValueInHtml(rawTxt, 'Comprador', bName);
                    } else if (rawTxt && rawTxt.trim().length > 0) {
                        rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bName) : bName;
                    } else {
                        rawTxt = `<div style="display: flex; flex-direction: column; text-align: inherit; width: 100%;"><span style="font-size: 0.75em; opacity: 0.85;">Comprador:</span><span style="font-weight: 900; text-transform: uppercase;">${bName}</span></div>`;
                    }
                } else if (field === 'buyer_dni' || el.id === 'canvaElBuyerDni' || /DNI/i.test(rawTxt)) {
                    const bDni = dynamicData.buyer_dni || '00000000';
                    if (/DNI/i.test(rawTxt)) {
                        rawTxt = replaceDynamicValueInHtml(rawTxt, 'DNI', bDni);
                    } else if (rawTxt && rawTxt.trim().length > 0) {
                        rawTxt = rawTxt.includes('<') ? rawTxt.replace(/([^>]+)(?=<|$)/, bDni) : bDni;
                    } else {
                        rawTxt = `<span style="font-weight: 800;">DNI: ${bDni}</span>`;
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
                    rawTxt = rawTxt || `<div style="border-top: 1.5px solid #CBD5E1; padding-top: 0.25rem; width: 100%; text-align: inherit;"><p style="font-size: 0.65em; font-weight: 700; opacity: 0.8; line-height: 1.2; margin: 0; text-align: inherit;">La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.</p></div>`;
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
    }

    function getFullAssetUrl(urlStr) {
        if (!urlStr) return null;
        if (urlStr.startsWith('data:')) return urlStr;
        if (urlStr.startsWith('http://') || urlStr.startsWith('https://')) {
            return urlStr;
        }
        let clean = urlStr.replace(/^\//, '');
        if (clean.includes('storage/')) {
            clean = 'storage/' + clean.split('storage/').pop();
        } else if (clean.includes('images/')) {
            clean = 'images/' + clean.split('images/').pop();
        } else if (clean.startsWith('events/') || clean.startsWith('templates/') || clean.startsWith('media/') || clean.startsWith('uploads/')) {
            clean = 'storage/' + clean;
        }
        return window.location.origin + '/' + clean;
    }

    async function generateTicketPdfDoc(sale) {
        const event = sale.event || {};
        const template = event.template || { id: 1, name: 'Plantilla Oficial', bg_color: '#FFFFFF', positions: {}, elements: [] };

        const eventTitle = event.title || 'CONCIERTO EN VIVO';
        const eventVenue = event.venue_name || '';
        const eventAddress = event.address || '';
        const eventDate = event.event_date ? (typeof event.event_date === 'string' ? event.event_date.substring(0, 10) : '') : '';
        const eventTime = event.event_time || '';
        const logoWhite = "{{ asset('images/logo-white.png') }}";

        const bgColor = template.bg_color || '#FFFFFF';

        const bgImgSrc = template.background ? getFullAssetUrl(template.background) : (template.bg_image ? getFullAssetUrl(template.bg_image) : null);
        const bannerImgSrc = event.banner_image ? getFullAssetUrl(event.banner_image) : '';
        const boletoSrc = getFullAssetUrl('/images/Boleto.jpg');

        const [bgDataUrl, bannerDataUrl, logoDataUrl, boletoDataUrl] = await Promise.all([
            bgImgSrc ? preloadPosImageAsDataUrl(bgImgSrc, 'bg') : Promise.resolve(''),
            bannerImgSrc ? preloadPosImageAsDataUrl(bannerImgSrc, 'banner') : Promise.resolve(''),
            preloadPosImageAsDataUrl(logoWhite, 'logo'),
            preloadPosImageAsDataUrl(boletoSrc, 'boleto')
        ]);

        const assetMap = {
            bgDataUrl: bgDataUrl,
            bannerDataUrl: bannerDataUrl,
            logoDataUrl: logoDataUrl
        };

        let tplElements = template.elements || [];
        if ((!Array.isArray(tplElements) || tplElements.length === 0) && template.positions) {
            let rawPos = typeof template.positions === 'string' ? JSON.parse(template.positions) : template.positions;
            tplElements = convertPositionsToElements(rawPos);
        }

        for (let el of tplElements) {
            if (el.src) {
                const fullUrl = getFullAssetUrl(el.src);
                el.src = await preloadPosImageAsDataUrl(fullUrl, 'el_' + el.id);
            }
        }

        let ticketsDataParsed = sale.tickets_data;
        if (typeof ticketsDataParsed === 'string') {
            try { ticketsDataParsed = JSON.parse(ticketsDataParsed); } catch(e) {}
        }
        const rawItems = (ticketsDataParsed && ticketsDataParsed.items) ? ticketsDataParsed.items : (Array.isArray(ticketsDataParsed) ? ticketsDataParsed : []);

        let ticketsList = [];
        if (rawItems.length > 0) {
            rawItems.forEach((it, idx) => {
                const qty = parseInt(it.quantity || 1, 10);
                for (let q = 0; q < qty; q++) {
                    ticketsList.push({
                        ticket_code: `TK-${sale.receipt_number || '00000'}-${ticketsList.length + 1}`,
                        ticket_number: ticketsList.length + 1,
                        zone: it.name || sale.zone_name,
                        price: it.price || sale.unit_price,
                        validation_hash: null,
                        qr_payload: null
                    });
                }
            });
        } else {
            const qty = parseInt(sale.quantity || 1, 10);
            for (let q = 0; q < qty; q++) {
                ticketsList.push({
                    ticket_code: `TK-${sale.receipt_number || '00000'}-${q + 1}`,
                    ticket_number: q + 1,
                    zone: sale.zone_name,
                    price: sale.unit_price,
                    validation_hash: null,
                    qr_payload: null
                });
            }
        }

        const jsPdfObj = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : (window.jsPDF || null);
        let pdf = null;
        if (jsPdfObj) {
            pdf = new jsPdfObj({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });
        }

        for (let i = 0; i < ticketsList.length; i++) {
            const tItem = ticketsList[i];
            let numSeq = tItem.ticket_number || (sale.id ? (sale.id + i) : (i + 1));
            if (typeof numSeq === 'string') {
                numSeq = parseInt(numSeq.replace(/[^0-9]/g, ''), 10) || (i + 1);
            }
            const ticketNumStr = 'N° ' + String(numSeq).padStart(5, '0');

            let hashVal = tItem.validation_hash || sale.validation_hash;
            if (!hashVal) {
                hashVal = 'VG' + String(Math.abs(((sale.receipt_number || 'REC') + '_' + (i + 1)).split('').reduce((a, b) => { a = ((a << 5) - a) + b.charCodeAt(0); return a & a; }, 0))).padStart(8, '0').substring(0, 8).toUpperCase();
            }

            const qrPayload = tItem.qr_payload || sale.qr_payload || `VIVEGO|${sale.receipt_number || 'REC'}|EVT-${sale.event_id || (event.id || 1)}|DNI-${sale.buyer_dni || '00000000'}|TICK-${numSeq}|${hashVal}`;
            const qrDataUrl = generateQrBase64(qrPayload);

            const unitPriceVal = parseFloat(tItem.price || sale.unit_price || sale.total_amount || 0).toFixed(2);

            const dynamicData = {
                title: eventTitle,
                venue: eventVenue,
                city: eventAddress,
                date: eventDate,
                time: eventTime,
                zone: tItem.zone || sale.zone_name || 'GENERAL',
                price: 'S/ ' + unitPriceVal,
                buyer_name: sale.buyer_name || 'CLIENTE VARIOS',
                buyer_dni: sale.buyer_dni || '00000000',
                ticket_number: ticketNumStr,
                hash: hashVal,
                qr_data_url: qrDataUrl
            };

            const canvasHtml = renderTicketCanvasContent({ ...template, elements: tplElements }, dynamicData, assetMap);

            const pdfContainer = document.createElement('div');
            pdfContainer.className = 'posPdfSingleCanvas';
            pdfContainer.style.position = 'fixed';
            pdfContainer.style.left = '-9999px';
            pdfContainer.style.top = '0';
            pdfContainer.style.width = '794px';
            pdfContainer.style.height = '1123px';
            pdfContainer.style.zIndex = '999999';
            pdfContainer.style.backgroundImage = `url('${boletoDataUrl || boletoSrc}')`;
            pdfContainer.style.backgroundSize = '100% 100%';
            pdfContainer.style.backgroundPosition = 'center';
            pdfContainer.style.fontFamily = "'Plus Jakarta Sans', sans-serif";
            pdfContainer.style.boxSizing = 'border-box';
            pdfContainer.style.overflow = 'hidden';

            pdfContainer.innerHTML = `
                <div class="ticket-canvas-inner" style="width: 771px; height: 370px; position: absolute; top: 12px; left: 11.5px; background: ${bgColor}; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; border-radius: 18px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); box-sizing: border-box;">
                    ${canvasHtml}
                </div>
            `;

            document.body.appendChild(pdfContainer);

            if (document.fonts && document.fonts.ready) {
                await document.fonts.ready;
            }
            await new Promise(r => setTimeout(r, 200));

            const canvas = await html2canvas(pdfContainer, {
                scale: 2.5,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#FFFFFF',
                logging: false
            });

            const imgData = canvas.toDataURL('image/jpeg', 0.95);
            pdfContainer.remove();

            if (pdf) {
                if (i > 0) {
                    pdf.addPage('a4', 'portrait');
                }
                pdf.addImage(imgData, 'JPEG', 0, 0, 210, 297, undefined, 'FAST');
            }
        }

        return { pdf, sale };
    }

    // Helper global para compilar base64 directamente
    window.compileTicketPdfBase64 = async function(sale) {
        const { pdf } = await generateTicketPdfDoc(sale);
        return pdf ? pdf.output('datauristring') : '';
    };
    window.generateTicketPdfDoc = generateTicketPdfDoc;

    async function downloadClientTicketPdf(btn) {
        if (!btn) return;
        const payloadEncoded = btn.getAttribute('data-sale-payload');
        if (!payloadEncoded) return;

        let sale;
        try {
            sale = JSON.parse(decodeURIComponent(escape(atob(payloadEncoded))));
        } catch (e) {
            console.error('Error decodificando payload de venta:', e);
            return;
        }

        Swal.fire({
            title: '🎟️ Generando Boleto Oficial...',
            html: 'Compilando diseño de entrada en formato A4 con alta definición...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); },
            background: '#14141E',
            color: '#FFFFFF'
        });

        try {
            const { pdf } = await generateTicketPdfDoc(sale);

            if (pdf) {
                pdf.save(`Boleto_Oficial_${sale.receipt_number || 'ViveGo'}.pdf`);
                Swal.fire({
                    icon: 'success',
                    title: '🎉 ¡Boleto Descargado!',
                    text: `Tu boleto oficial ha sido generado y descargado exitosamente.`,
                    background: '#14141E',
                    color: '#FFFFFF',
                    confirmButtonColor: '#FF5500',
                    timer: 3000
                });
            }
        } catch (err) {
            console.error('Error generando PDF de cliente:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error al generar boleto',
                text: 'Ocurrió un inconveniente al compilar el PDF. Por favor intenta nuevamente.',
                background: '#14141E',
                color: '#FFFFFF',
                confirmButtonColor: '#FF5500'
            });
        }
    }

    async function emailClientTicketPdf(btn) {
        if (!btn) return;
        const payloadEncoded = btn.getAttribute('data-sale-payload');
        if (!payloadEncoded) return;

        let sale;
        try {
            sale = JSON.parse(decodeURIComponent(escape(atob(payloadEncoded))));
        } catch (e) {
            console.error('Error decodificando payload de venta:', e);
            return;
        }

        Swal.fire({
            title: '📧 Enviando a tu Correo...',
            html: 'Compilando boleto con fondo oficial y enviándolo a tu bandeja de entrada...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); },
            background: '#14141E',
            color: '#FFFFFF'
        });

        try {
            const { pdf } = await generateTicketPdfDoc(sale);
            const pdfBase64 = pdf ? pdf.output('datauristring') : '';

            const res = await fetch(`/mi-cuenta/boleto/${sale.id}/enviar-correo`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ticket_pdf_base64: pdfBase64
                })
            });

            const data = await res.json();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '✉️ ¡Correo Enviado!',
                    text: data.message || 'El boleto ha sido enviado a tu correo exitosamente.',
                    background: '#14141E',
                    color: '#FFFFFF',
                    confirmButtonColor: '#FF5500'
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Aviso',
                    text: data.message || 'No se pudo enviar el correo.',
                    background: '#14141E',
                    color: '#FFFFFF',
                    confirmButtonColor: '#FF5500'
                });
            }
        } catch (err) {
            console.error('Error enviando boleto por correo:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error al enviar',
                text: 'Ocurrió un error al enviar el boleto a tu correo.',
                background: '#14141E',
                color: '#FFFFFF',
                confirmButtonColor: '#FF5500'
            });
        }
    }
</script>
