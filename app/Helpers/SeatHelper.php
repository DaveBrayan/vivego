<?php

if (!function_exists('formatShortSeatCode')) {
    /**
     * Extrae y formatea el código abreviado de la butaca (ej: "Fila A - Asiento 1" -> "A1")
     */
    function formatShortSeatCode($seat): string
    {
        if (is_array($seat)) {
            if (!empty($seat['row']) && (!empty($seat['number']) || !empty($seat['col']))) {
                return strtoupper(trim((string)$seat['row'])) . trim((string)($seat['number'] ?? $seat['col']));
            }
            $seat = $seat['label'] ?? $seat['number'] ?? $seat['code'] ?? '';
        }
        $seat = trim((string)$seat);
        if (empty($seat)) {
            return '';
        }

        // Caso 1: "Fila A - Asiento 1" o "Fila A - Columna 1"
        if (preg_match('/Fila\s*([A-Za-z0-9]+)\s*-\s*(?:Asiento|Columna)\s*([0-9]+)/iu', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 2: "Fila A Asiento 1"
        if (preg_match('/Fila\s*([A-Za-z0-9]+)\s*(?:Asiento|Columna)\s*([0-9]+)/iu', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 3: "A-1" o "A 1"
        if (preg_match('/^([A-Za-z]+)[-\s]+([0-9]+)$/', $seat, $m)) {
            return strtoupper($m[1]) . $m[2];
        }

        // Caso 4: "A1" o código compacto
        if (preg_match('/([A-Za-z]+[0-9]+)$/', $seat, $m)) {
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
