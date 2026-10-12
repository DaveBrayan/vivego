<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSale extends Model
{
    use HasFactory;

    protected $table = 'ticket_sales';

    protected $fillable = [
        'event_id',
        'receipt_number',
        'buyer_name',
        'buyer_dni',
        'buyer_phone',
        'zone_name',
        'unit_price',
        'quantity',
        'total_amount',
        'payment_method',
        'sale_type',
        'amount_paid',
        'change_amount',
        'tickets_data',
        'seller_name',
        'original_subtotal',
        'discount_amount',
        'discount_description',
        'campaign_name',
        'coupon_code',
        'status',
        'is_upgrade',
        'upgraded_from_sale_id',
        'upgraded_to_sale_id',
        'upgrade_difference',
        'upgrade_original_zone',
    ];

    protected $casts = [
        'tickets_data' => 'array',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'original_subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'upgrade_difference' => 'decimal:2',
        'is_upgrade' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function eventTickets()
    {
        return $this->hasMany(EventTicket::class, 'ticket_sale_id');
    }

    public function upgradedFrom()
    {
        return $this->belongsTo(TicketSale::class, 'upgraded_from_sale_id');
    }

    public function upgradedTo()
    {
        return $this->belongsTo(TicketSale::class, 'upgraded_to_sale_id');
    }

    public function isUpgraded(): bool
    {
        return $this->status === 'upgraded' || !empty($this->upgraded_to_sale_id) || (!empty($this->tickets_data['is_upgraded']));
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'ticket_sale_id');
    }

    public function latestEmailLog()
    {
        return $this->hasOne(EmailLog::class, 'ticket_sale_id')->latestOfMany();
    }

    public function getCustomerEmailAttribute(): ?string
    {
        if (is_array($this->tickets_data)) {
            return $this->tickets_data['customer_email'] ?? ($this->tickets_data['buyer_email'] ?? ($this->tickets_data['email'] ?? null));
        }
        if (is_string($this->tickets_data)) {
            $td = json_decode($this->tickets_data, true);
            return $td['customer_email'] ?? ($td['buyer_email'] ?? ($td['email'] ?? null));
        }
        return null;
    }
}
