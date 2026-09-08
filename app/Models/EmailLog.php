<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_logs';

    protected $fillable = [
        'ticket_sale_id',
        'event_id',
        'recipient_name',
        'recipient_email',
        'subject',
        'mail_type',
        'status',
        'error_message',
        'details',
        'attempts',
        'sent_at',
    ];

    protected $casts = [
        'details' => 'array',
        'sent_at' => 'datetime',
        'attempts' => 'integer',
    ];

    public function ticketSale()
    {
        return $this->belongsTo(TicketSale::class, 'ticket_sale_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function isSuccess(): bool
    {
        return $this->status === 'sent';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
