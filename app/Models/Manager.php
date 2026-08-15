<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Manager extends Model
{
    use HasFactory;

    protected $table = 'managers';

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'country_code',
        'country_iso',
        'phone',
        'status',
    ];

    /**
     * Relación con la compañía asignada.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Nombre completo del responsable.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

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
