<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'type',
        'bg_color',
        'bg_image',
        'strip_color',
        'positions',
        'elements',
        'is_default',
        'status',
    ];

    protected $casts = [
        'positions' => 'array',
        'elements' => 'array',
        'is_default' => 'boolean',
    ];
}
