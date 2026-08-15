<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    use HasFactory;

    protected $table = 'administrators';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'country_code',
        'country_iso',
        'phone',
        'role',
        'status',
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Obtein full name of administrator.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get flag emoji based on ISO code.
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
