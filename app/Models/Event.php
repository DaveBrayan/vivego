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
    ];

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
