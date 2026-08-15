<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'tax_id',
        'email',
        'country_code',
        'country_iso',
        'phone',
        'address',
        'status',
    ];

    /**
     * Mapeo de ISO de país a emoji de bandera.
     */
    public function getFlagEmojiAttribute(): string
    {
        $flags = [
            'pe' => '🇵🇪',
            'co' => '🇨🇴',
            'mx' => '🇲🇽',
            'cl' => '🇨🇱',
            'us' => '🇺🇸',
            'es' => '🇪🇸',
            'ar' => '🇦🇷',
            'ec' => '🇪🇨',
            'br' => '🇧🇷',
        ];

        return $flags[strtolower($this->country_iso)] ?? '🌐';
    }
}
