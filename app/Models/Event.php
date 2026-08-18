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
        'status',
        'sales_type',
    ];

    protected $casts = [
        'tags' => 'array',
        'zones' => 'array',
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
            return 'storage/' . ltrim($matches[1], '/');
        }

        if (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            return 'images/' . ltrim($matches[1], '/');
        }

        if (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            return 'storage/' . $clean;
        }

        return $clean;
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
