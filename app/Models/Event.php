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
        return in_array(strtolower(trim((string) $this->status)), ['finalizado', 'concluido', 'terminado', 'cancelado']);
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
