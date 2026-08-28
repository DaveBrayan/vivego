<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Administrator;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientUser;
    public string $tempPassword;
    public string $userName;
    public ?string $userDni;
    public string $userEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($user, string $tempPassword)
    {
        $this->recipientUser = $user;
        $this->tempPassword = $tempPassword;
        $this->userName = $user->name ?? $user->full_name ?? 'Usuario';
        $this->userDni = $user->dni ?? null;
        $this->userEmail = $user->email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔑 Tu Contraseña Temporal de Acceso - ViveGo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
            with: [
                'name' => $this->userName,
                'dni' => $this->userDni,
                'email' => $this->userEmail,
                'tempPassword' => $this->tempPassword,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
