<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'badge_text',
        'banner_color',
        'discount_type',
        'discount_value',
        'start_at',
        'end_at',
        'is_active',
        'scope',
        'event_ids',
        'excluded_event_ids',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'event_ids' => 'array',
        'excluded_event_ids' => 'array',
        'discount_value' => 'decimal:2',
    ];

    /**
     * Determina si la campaña está actualmente vigente y activa en tiempo real.
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        if ($this->start_at && $now->lt($this->start_at)) {
            return false;
        }

        if ($this->end_at && $now->gt($this->end_at)) {
            return false;
        }

        return true;
    }

    /**
     * Verifica si la campaña aplica a un evento específico.
     */
    public function appliesToEvent(int|string $eventId): bool
    {
        if (!$this->isCurrentlyActive()) {
            return false;
        }

        $eventId = (int) $eventId;

        if ($this->scope === 'selected_events') {
            $allowed = is_array($this->event_ids) ? array_map('intval', $this->event_ids) : [];
            return in_array($eventId, $allowed, true);
        }

        // Scope: all_events (comprobar exclusiones)
        $excluded = is_array($this->excluded_event_ids) ? array_map('intval', $this->excluded_event_ids) : [];
        return !in_array($eventId, $excluded, true);
    }

    /**
     * Calcula el monto de descuento para un precio dado.
     */
    public function calculateDiscount(float $price): float
    {
        if ($price <= 0 || (float)$this->discount_value <= 0) {
            return 0.00;
        }

        if ($this->discount_type === 'percentage') {
            $pct = min(100, max(0, (float)$this->discount_value));
            return round($price * ($pct / 100), 2);
        }

        // Fixed
        return min($price, round((float)$this->discount_value, 2));
    }

    /**
     * Retorna la primera campaña activa aplicable a un evento dado.
     */
    public static function getActiveForEvent(int|string $eventId): ?self
    {
        try {
            $now = Carbon::now();
            $campaigns = self::where('is_active', true)
                ->where('start_at', '<=', $now)
                ->where('end_at', '>=', $now)
                ->orderBy('id', 'desc')
                ->get();

            foreach ($campaigns as $campaign) {
                if ($campaign->appliesToEvent($eventId)) {
                    return $campaign;
                }
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}
