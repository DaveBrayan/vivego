<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'slug',
        'category_name',
        'company_name',
        'banner_image',
        'reference_image',
        'event_date',
        'event_time',
        'venue_name',
        'address',
        'latitude',
        'longitude',
        'description',
        'tags',
        'template_id',
        'zones',
        'courtesy_settings',
        'quota_split_settings',
        'status',
        'sales_type',
        'layout_template',
        'background_image',
        'background_mobile_image',
        'artist_image',
    ];

    protected $casts = [
        'tags' => 'array',
        'zones' => 'array',
        'courtesy_settings' => 'array',
        'quota_split_settings' => 'array',
    ];

    /**
     * Determina si el evento ya concluyó o fue finalizado manualmente por el organizador/administrador.
     */
    public function isPast(): bool
    {
        return in_array(strtolower(trim((string) $this->status)), ['finalizado', 'concluido', 'terminado', 'cancelado', 'cerrado']);
    }

    /**
     * Determina si una zona corresponde a un escenario o tarima no comercializable.
     */
    public static function isStageZone($zone): bool
    {
        if (!is_array($zone)) {
            return false;
        }

        $name = strtoupper(trim((string) ($zone['name'] ?? '')));
        $capType = (string) ($zone['capacity_type'] ?? '');
        $type = (string) ($zone['type'] ?? '');

        if (in_array($name, ['ESCENARIO', 'TARIMA'])
            || str_contains($name, 'ESCENARIO')
            || str_contains($name, 'TARIMA')
            || strcasecmp($capType, 'Escenario') === 0
            || strcasecmp($type, 'stage') === 0
            || !empty($zone['is_stage'])
            || !empty($zone['is_area'])) {
            return true;
        }

        $priceVal = (float)($zone['price'] ?? 0);
        $capVal = (int)($zone['capacity'] ?? 0);
        $hasSeats = !empty($zone['seats']) && is_array($zone['seats']) && count($zone['seats']) > 0;
        if ($priceVal === 0.0 && $capVal === 0 && !$hasSeats) {
            return true;
        }

        return false;
    }

    /**
     * Retorna las zonas comerciales del evento (excluyendo escenarios y áreas estructurales).
     */
    public function getCommercialZonesAttribute(): array
    {
        $rawZones = $this->zones ?? [];
        if (is_string($rawZones)) {
            $rawZones = json_decode($rawZones, true) ?: [];
        }
        if (!is_array($rawZones)) {
            return [];
        }

        return array_values(array_filter($rawZones, fn($z) => !self::isStageZone($z)));
    }

    /**
     * Retorna el precio base mínimo comercial del evento (considerando preventas activas si aplican).
     */
    public function getMinPriceAttribute(): float
    {
        $commercialZones = $this->commercial_zones;
        if (empty($commercialZones)) {
            return 50.00;
        }

        $prices = [];
        $today = date('Y-m-d');

        foreach ($commercialZones as $cz) {
            $regPrice = isset($cz['price']) ? (float)$cz['price'] : 0.0;
            $effPrice = $regPrice;

            // Verificar si tiene preventa activa
            $hasPresale = !empty($cz['has_presale']) && ($cz['has_presale'] === true || $cz['has_presale'] === 1 || $cz['has_presale'] === '1' || $cz['has_presale'] === 'true');
            $discPercent = isset($cz['presale_discount']) ? (float)$cz['presale_discount'] : 0;
            $pStart = !empty($cz['presale_start_date']) ? $cz['presale_start_date'] : null;
            $pEnd = !empty($cz['presale_end_date']) ? $cz['presale_end_date'] : null;
            $pStock = isset($cz['presale_stock']) && $cz['presale_stock'] !== '' ? (int)$cz['presale_stock'] : null;

            if ($hasPresale && $discPercent > 0) {
                $valid = true;
                if ($pStart && $today < $pStart) $valid = false;
                if ($pEnd && $today > $pEnd) $valid = false;
                if ($pStock !== null && $pStock <= 0) $valid = false;

                if ($valid) {
                    if (isset($cz['presale_price']) && (float)$cz['presale_price'] > 0) {
                        $effPrice = (float)$cz['presale_price'];
                    } else {
                        $effPrice = round($regPrice * (1 - ($discPercent / 100)), 2);
                    }
                }
            }

            $prices[] = $effPrice;
        }

        $positivePrices = array_values(array_filter($prices, fn($p) => $p > 0));
        if (count($positivePrices) > 0) {
            return (float) min($positivePrices);
        }

        return count($prices) > 0 ? (float) min($prices) : 50.00;
    }

    /**
     * Determina si la fecha/hora del calendario ya pasaron (independientemente del estado).
     */
    public function isDatePassed(): bool
    {
        if (empty($this->event_date)) {
            return false;
        }

        try {
            if ($this->event_date instanceof \DateTimeInterface) {
                $dateCarbon = \Carbon\Carbon::instance($this->event_date);
            } else {
                $raw = trim((string) $this->event_date);
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}/', $raw)) {
                    $dateCarbon = \Carbon\Carbon::createFromFormat('d/m/Y', substr($raw, 0, 10));
                } else {
                    $dateCarbon = \Carbon\Carbon::parse($raw);
                }
            }

            $timeStr = !empty($this->event_time) ? trim(preg_replace('/[^0-9:]/', '', (string) $this->event_time)) : '23:59:59';
            if (empty($timeStr) || strlen($timeStr) < 4) {
                $timeStr = '23:59:59';
            } elseif (strlen($timeStr) === 5) {
                $timeStr .= ':00';
            }

            $parts = explode(':', $timeStr);
            $hour = isset($parts[0]) ? (int)$parts[0] : 23;
            $min = isset($parts[1]) ? (int)$parts[1] : 59;
            $sec = isset($parts[2]) ? (int)$parts[2] : 59;

            $eventDateTime = $dateCarbon->setTime($hour, $min, $sec);
            return \Carbon\Carbon::now()->greaterThan($eventDateTime);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getBannerImageAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'data:image') || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            $clean = 'storage/' . ltrim($matches[1], '/');
        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            $clean = 'images/' . ltrim($matches[1], '/');
        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            $clean = 'storage/' . $clean;
        }

        return asset($clean);
    }

    public function getReferenceImageAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'data:image') || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            $clean = 'storage/' . ltrim($matches[1], '/');
        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            $clean = 'images/' . ltrim($matches[1], '/');
        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            $clean = 'storage/' . $clean;
        }

        return asset($clean);
    }

    public function getBackgroundImageAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'data:image') || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            $clean = 'storage/' . ltrim($matches[1], '/');
        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            $clean = 'images/' . ltrim($matches[1], '/');
        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            $clean = 'storage/' . $clean;
        }

        return asset($clean);
    }

    public function getBackgroundMobileImageAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'data:image') || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            $clean = 'storage/' . ltrim($matches[1], '/');
        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            $clean = 'images/' . ltrim($matches[1], '/');
        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            $clean = 'storage/' . $clean;
        }

        return asset($clean);
    }

    public function getArtistImageAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'data:image') || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            $clean = 'storage/' . ltrim($matches[1], '/');
        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            $clean = 'images/' . ltrim($matches[1], '/');
        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            $clean = 'storage/' . $clean;
        }

        return asset($clean);
    }

    protected static function booted(): void
    {
        static::deleting(function (Event $event) {
            // Eliminar en cascada todos los boletos y códigos QR registrados del evento (físicos, plancha y ventas)
            $event->tickets()->delete();
            // Eliminar las ventas asociadas al evento
            $event->sales()->delete();
        });
    }

    public function template()
    {
        return $this->belongsTo(TicketTemplate::class, 'template_id');
    }

    public function sales()
    {
        return $this->hasMany(TicketSale::class, 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }
}
