<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;

    protected $table = 'event_tickets';

    protected $fillable = [
        'event_id',
        'ticket_sale_id',
        'ticket_code',
        'ticket_number',
        'zone_name',
        'unit_price',
        'qr_payload',
        'validation_hash',
        'buyer_name',
        'buyer_dni',
        'source',
        'is_used',
        'checked_in_at',
        'scanned_by',
        'status',
        'upgraded_to_ticket_id',
        'upgraded_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_used' => 'boolean',
        'checked_in_at' => 'datetime',
        'upgraded_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function ticketSale()
    {
        return $this->belongsTo(TicketSale::class, 'ticket_sale_id');
    }

    public function isUpgraded(): bool
    {
        return $this->status === 'upgraded' || !empty($this->upgraded_at) || !empty($this->upgraded_to_ticket_id);
    }
}
