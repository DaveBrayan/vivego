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
        'amount_paid',
        'change_amount',
        'tickets_data',
        'seller_name',
    ];

    protected $casts = [
        'tickets_data' => 'array',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
