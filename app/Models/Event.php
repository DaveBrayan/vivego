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
