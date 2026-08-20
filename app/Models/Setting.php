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

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('system_settings_singleton');
        });
    }

    /**
     * Get active system settings singleton with automatic high-speed caching
     */
    public static function current(): self
    {
        return \Illuminate\Support\Facades\Cache::remember('system_settings_singleton', 3600, function () {
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
        });
    }
}
