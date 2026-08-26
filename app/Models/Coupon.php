<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'usage_limit',
        'used_count',
        'min_purchase_amount',
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
        'min_purchase_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Valida si el cupón está vigente y puede ser aplicado.
     */
    public function isValidForEvent(int|string $eventId, float $subtotal = 0.0): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Este cupón se encuentra inactivo actualmente.'];
        }

        $now = Carbon::now();
        if ($this->start_at && $now->lt($this->start_at)) {
            return ['valid' => false, 'message' => 'Este cupón aún no ha iniciado su vigencia (inicia el ' . $this->start_at->format('d/m/Y h:i A') . ').'];
        }

        if ($this->end_at && $now->gt($this->end_at)) {
            return ['valid' => false, 'message' => 'Este cupón ya expiró el ' . $this->end_at->format('d/m/Y h:i A') . '.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Este cupón ha alcanzado su límite máximo de usos disponibles.'];
        }

        if ($this->min_purchase_amount > 0 && $subtotal < (float)$this->min_purchase_amount) {
            return ['valid' => false, 'message' => 'El monto mínimo de compra para este cupón es de S/ ' . number_format($this->min_purchase_amount, 2) . '.'];
        }

        $eventId = (int) $eventId;

        if ($this->scope === 'selected_events') {
            $allowed = is_array($this->event_ids) ? array_map('intval', $this->event_ids) : [];
            if (!in_array($eventId, $allowed, true)) {
                return ['valid' => false, 'message' => 'Este cupón no es aplicable para este evento.'];
            }
        } else {
            $excluded = is_array($this->excluded_event_ids) ? array_map('intval', $this->excluded_event_ids) : [];
            if (in_array($eventId, $excluded, true)) {
                return ['valid' => false, 'message' => 'Este evento se encuentra excluido de esta promoción.'];
            }
        }

        return ['valid' => true, 'message' => '¡Cupón válido y aplicado exitosamente!'];
    }

    /**
     * Calcula el monto de descuento para un subtotal dado.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal <= 0 || (float)$this->discount_value <= 0) {
            return 0.00;
        }

        if ($this->discount_type === 'percentage') {
            $pct = min(100, max(0, (float)$this->discount_value));
            return round($subtotal * ($pct / 100), 2);
        }

        // Fixed
        return min($subtotal, round((float)$this->discount_value, 2));
    }

    /**
     * Incrementa de forma atómica el contador de usos.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
