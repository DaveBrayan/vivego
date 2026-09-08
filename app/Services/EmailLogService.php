<?php

namespace App\Services;

use App\Mail\TicketPurchaseMail;
use App\Models\EmailLog;
use App\Models\TicketSale;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailLogService
{
    /**
     * Envía el correo de compra de entradas y registra automáticamente el resultado en email_logs
     */
    public static function sendTicketPurchaseMail(
        TicketSale $sale,
        ?string $tempPassword = null,
        bool $isNewUser = false,
        ?string $customPdfBase64 = null
    ): array {
        $sale->loadMissing(['event', 'eventTickets']);

        $recipientEmail = trim($sale->buyer_email ?: '');
        if (empty($recipientEmail)) {
            $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data ?? '[]', true);
            $recipientEmail = $tData['customer_email'] ?? ($tData['buyer_email'] ?? '');
        }

        $recipientName = $sale->customer_name ?: ($sale->buyer_name ?: 'Cliente');
        $eventName = $sale->event?->title ?? 'Evento ViveGo';
        $subject = "🎟️ Tus Entradas Oficiales - {$eventName} (#{$sale->receipt_number})";

        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            $log = EmailLog::create([
                'ticket_sale_id' => $sale->id,
                'event_id' => $sale->event_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail ?: 'sin-correo@vivego.pe',
                'subject' => $subject,
                'mail_type' => 'ticket_purchase',
                'status' => 'failed',
                'error_message' => 'Dirección de correo electrónico vacía o inválida.',
                'details' => [
                    'event_title' => $eventName,
                    'receipt_number' => $sale->receipt_number,
                    'sale_id' => $sale->id,
                    'error_type' => 'ValidationError'
                ],
                'attempts' => 1,
            ]);

            return [
                'success' => false,
                'message' => 'El correo del comprador es inválido o no existe.',
                'log' => $log,
            ];
        }

        try {
            Mail::to($recipientEmail)->send(new TicketPurchaseMail($sale, $tempPassword, $isNewUser, $customPdfBase64));

            $log = EmailLog::create([
                'ticket_sale_id' => $sale->id,
                'event_id' => $sale->event_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'mail_type' => 'ticket_purchase',
                'status' => 'sent',
                'error_message' => null,
                'details' => [
                    'event_title' => $eventName,
                    'receipt_number' => $sale->receipt_number,
                    'quantity' => $sale->quantity,
                    'sale_id' => $sale->id,
                    'sent_via' => config('mail.default', 'smtp'),
                ],
                'attempts' => 1,
                'sent_at' => now(),
            ]);

            Log::info("Email enviado exitosamente a {$recipientEmail} para la venta #{$sale->id}");

            return [
                'success' => true,
                'message' => 'Correo enviado exitosamente.',
                'log' => $log,
            ];
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Log::error("Error enviando email de compra a {$recipientEmail}: {$errorMsg}");

            $log = EmailLog::create([
                'ticket_sale_id' => $sale->id,
                'event_id' => $sale->event_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'mail_type' => 'ticket_purchase',
                'status' => 'failed',
                'error_message' => $errorMsg,
                'details' => [
                    'event_title' => $eventName,
                    'receipt_number' => $sale->receipt_number,
                    'sale_id' => $sale->id,
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                'attempts' => 1,
            ]);

            return [
                'success' => false,
                'message' => 'Error al enviar correo: ' . $errorMsg,
                'log' => $log,
            ];
        }
    }

    /**
     * Reintenta el envío de un correo fallido o enviado previamente
     */
    public static function resend(EmailLog $log): array
    {
        $sale = $log->ticketSale;
        if (!$sale) {
            return [
                'success' => false,
                'message' => 'No se encontró la venta vinculada a este registro de correo.',
            ];
        }

        $sale->loadMissing(['event', 'eventTickets']);
        $recipientEmail = $log->recipient_email;

        try {
            Mail::to($recipientEmail)->send(new TicketPurchaseMail($sale, null, false, null));

            $log->update([
                'status' => 'sent',
                'error_message' => null,
                'attempts' => $log->attempts + 1,
                'sent_at' => now(),
            ]);

            Log::info("Reenvío de email exitoso para Log #{$log->id} a {$recipientEmail}");

            return [
                'success' => true,
                'message' => '¡Correo reenviado exitosamente a ' . $recipientEmail . '!',
                'log' => $log,
            ];
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Log::error("Fallo al reenviar correo para Log #{$log->id}: {$errorMsg}");

            $log->update([
                'status' => 'failed',
                'error_message' => $errorMsg,
                'attempts' => $log->attempts + 1,
            ]);

            return [
                'success' => false,
                'message' => 'No se pudo reenviar el correo: ' . $errorMsg,
                'log' => $log,
            ];
        }
    }
}
