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
        'allowed_scope',
        'allowed_events',
        'status',
        'avatar',
    ];

    protected $casts = [
        'allowed_events' => 'array',
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

    /**
     * Comprueba si el usuario es Administrador Principal (SuperAdmin).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'Administrador Principal';
    }

    /**
     * Comprueba si el usuario tiene rol de Ventas.
     */
    public function isVentas(): bool
    {
        return $this->role === 'Ventas';
    }

    /**
     * Comprueba si el usuario tiene rol de Validador de Entradas.
     */
    public function isValidador(): bool
    {
        return $this->role === 'Validador de Entradas';
    }

    /**
     * Determina si el usuario tiene permiso para eliminar registros en el sistema.
     * Los roles de Ventas y Validador de Entradas NO pueden eliminar.
     */
    public function canDelete(): bool
    {
        return in_array($this->role, ['Administrador Principal', 'Administrador']);
    }

    /**
     * Comprueba si el usuario puede acceder a un evento determinado.
     */
    public function canAccessEvent($eventId): bool
    {
        if ($this->allowed_scope !== 'specific') {
            return true;
        }

        $events = is_array($this->allowed_events) ? $this->allowed_events : [];
        return in_array((int)$eventId, array_map('intval', $events));
    }

    /**
     * Obtiene los IDs de eventos permitidos para este usuario.
     * Retorna null si tiene acceso a todos los eventos.
     */
    public function getAllowedEventIds(): ?array
    {
        if ($this->allowed_scope !== 'specific' || empty($this->allowed_events)) {
            return null; // Null significa sin restricción (todos)
        }

        return array_map('intval', (array)$this->allowed_events);
    }
}
