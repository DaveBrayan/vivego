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
        'attachment_sent',
        'attachment_name',
        'attachment_size',
        'attachment_error',
        'details',
        'attempts',
        'sent_at',
    ];

    protected $casts = [
        'details' => 'array',
        'sent_at' => 'datetime',
        'attempts' => 'integer',
        'attachment_sent' => 'boolean',
        'attachment_size' => 'integer',
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

    public function hasVerifiedAttachment(): bool
    {
        $val = $this->attachment_sent ?? ($this->details['attachment_sent'] ?? null);
        return $val === true || $val === 1 || $val === '1';
    }

    public function hasMissingAttachment(): bool
    {
        $val = $this->attachment_sent ?? ($this->details['attachment_sent'] ?? null);
        return $val === false || $val === 0 || $val === '0';
    }
}
