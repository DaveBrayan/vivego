<?php

if (!function_exists('formatShortSeatCode')) {
    /**
     * Extrae y formatea el código abreviado de la butaca (ej: "Fila A - Asiento 1" -> "A1")
     */
    function formatShortSeatCode($seat): string
    {
        if (is_array($seat)) {
            $row = !empty($seat['row']) ? strtoupper(trim((string)$seat['row'])) : '';
            $col = !empty($seat['col']) ? trim((string)$seat['col']) : '';
            $number = !empty($seat['number']) ? trim((string)$seat['number']) : '';
            $label = !empty($seat['label']) ? trim((string)$seat['label']) : (!empty($seat['display_name']) ? trim((string)$seat['display_name']) : '');

            // Si tenemos row y col limpia numérica (ej: row: "A", col: "3")
            if ($row !== '' && $col !== '' && preg_match('/^\d+$/', $col)) {
                return $row . $col;
            }

            // Si number es como "A-3", "A3", "A 3" o solo dígitos
            if ($row !== '' && $number !== '') {
                if (preg_match('/^' . preg_quote($row, '/') . '[\s\-_]*(\d+)$/i', $number, $m)) {
                    return $row . $m[1];
                }
                if (preg_match('/^\d+$/', $number)) {
                    return $row . $number;
                }
            }

            if ($label !== '') {
                $seat = $label;
            } elseif ($number !== '') {
                $seat = $number;
            } elseif ($row !== '' && $col !== '') {
                $seat = $row . $col;
            } else {
                $seat = $seat['code'] ?? '';
            }
        }

        $seat = trim((string)$seat);
        if (empty($seat)) {
            return '';
        }

        // Si viene con prefijo de letra duplicado como "AA-3", "AA3", "AA_3"
        if (preg_match('/^([A-Za-z])\1[\s\-_]*([0-9]+)$/', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 1: "Fila A - Asiento 1" o "Fila A - Columna 1"
        if (preg_match('/Fila\s*([A-Za-z0-9]+)\s*-\s*(?:Asiento|Columna)\s*([0-9]+)/iu', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 2: "Fila A Asiento 1"
        if (preg_match('/Fila\s*([A-Za-z0-9]+)\s*(?:Asiento|Columna)\s*([0-9]+)/iu', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 3: "A-1" o "A 1" o "A_1"
        if (preg_match('/^([A-Za-z]+)[-\s_]+([0-9]+)$/', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 4: "A1" o código compacto
        if (preg_match('/^([A-Za-z]+[0-9]+)$/', $seat, $m)) {
            return strtoupper($m[1]);
        }

        return $seat;
    }
}

if (!function_exists('formatZoneWithSeat')) {
    /**
     * Formatea el nombre de la zona con su código de butaca (ej: "Butacas Numeradas (A1)")
     */
    function formatZoneWithSeat(string $zoneName, $seat): string
    {
        $cleanZone = trim($zoneName);
        if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $cleanZone, $m)) {
            $cleanZone = trim($m[1]);
        }

        $short = formatShortSeatCode($seat);
        if (empty($short)) {
            return $cleanZone;
        }

        // Evitar duplicar si ya contiene (A1) o similar
        if (preg_match('/\(' . preg_quote($short, '/') . '\)/i', $cleanZone)) {
            return $cleanZone;
        }

        // Remover cualquier sufijo de butaca anterior si ya existía entre paréntesis
        $baseZone = preg_replace('/\s*\([^)]*\)$/', '', $cleanZone);
        return trim($baseZone) . " ({$short})";
    }
}

if (!function_exists('isSalePresale')) {
    /**
     * Determina si una venta o item se adquirió con tarifa de preventa
     */
    function isSalePresale($saleOrItem): bool
    {
        if (is_array($saleOrItem)) {
            if (!empty($saleOrItem['is_presale_active']) || !empty($saleOrItem['is_presale'])) {
                return true;
            }
            if (!empty($saleOrItem['presale_discount']) && (float)$saleOrItem['presale_discount'] > 0) {
                return true;
            }
            if (!empty($saleOrItem['regular_price']) && !empty($saleOrItem['price']) && (float)$saleOrItem['regular_price'] > (float)$saleOrItem['price'] && empty($saleOrItem['is_upgrade'])) {
                return true;
            }
            if (isset($saleOrItem['items']) && is_array($saleOrItem['items'])) {
                foreach ($saleOrItem['items'] as $subItem) {
                    if (isSalePresale($subItem)) return true;
                }
            }
            return false;
        }

        if (is_object($saleOrItem)) {
            $tData = is_array($saleOrItem->tickets_data) ? $saleOrItem->tickets_data : (json_decode($saleOrItem->tickets_data ?? '[]', true) ?: []);
            if (!empty($tData['is_presale'])) {
                return true;
            }
            $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
            foreach ($items as $item) {
                if (isSalePresale($item)) return true;
            }
            if (str_contains(strtolower($saleOrItem->zone_name ?? ''), 'preventa') || str_contains(strtolower($saleOrItem->campaign_name ?? ''), 'preventa')) {
                return true;
            }
        }

        return false;
    }
}
