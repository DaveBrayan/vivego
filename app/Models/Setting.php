<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'site_name',
        'site_description',
        'logo_dark',
        'logo_white',
        'favicon',
        'primary_color',
        'secondary_color',
        'timezone',
        'currency',
        'currency_symbol',
    ];

    /**
     * Get active system settings singleton
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'site_name' => 'Vive Go',
            'site_description' => 'Plataforma integral de ticketing, venta de entradas masivas, conciertos, teatro y festivales en Perú.',
            'logo_dark' => 'images/logo.png',
            'logo_white' => 'images/logo-white.png',
            'favicon' => 'images/loading.png',
            'primary_color' => '#FF5500',
            'secondary_color' => '#FF1E3C',
            'timezone' => 'America/Lima',
            'currency' => 'PEN',
            'currency_symbol' => 'S/',
        ]);
    }
}
