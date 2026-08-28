<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $table = 'claims';

    protected $fillable = [
        'claim_number',
        'person_type',
        'full_name',
        'document_type',
        'document_number',
        'email',
        'phone',
        'address',
        'department',
        'province',
        'district',
        'is_minor',
        'parent_name',
        'parent_document_type',
        'parent_document_number',
        'parent_email',
        'parent_phone',
        'contracted_good_type',
        'claimed_amount',
        'event_id',
        'order_code',
        'good_description',
        'claim_type',
        'claim_detail',
        'consumer_request',
        'status',
        'admin_response',
        'admin_response_date',
        'admin_responder_id',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_minor' => 'boolean',
        'claimed_amount' => 'decimal:2',
        'admin_response_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Evento asociado si aplica
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Administrador que atendió el reclamo
     */
    public function adminResponder(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'admin_responder_id');
    }

    /**
     * Genera un número correlativo único para la hoja de reclamación
     * Formato: REC-YYYYMM-0001
     */
    public static function generateNextClaimNumber(): string
    {
        $prefix = 'REC-' . date('Ym') . '-';
        $lastClaim = self::where('claim_number', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastClaim && preg_match('/-(\d+)$/', $lastClaim->claim_number, $matches)) {
            $nextSequence = (int)$matches[1] + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad((string)$nextSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Plazo legal de respuesta (15 días hábiles conforme al Código de Protección al Consumidor)
     */
    public function getLegalDeadlineAttribute(): Carbon
    {
        $date = $this->created_at ? $this->created_at->copy() : Carbon::now();
        $daysAdded = 0;
        while ($daysAdded < 15) {
            $date->addDay();
            if (!$date->isWeekend()) {
                $daysAdded++;
            }
        }
        return $date;
    }
}
