<?php

namespace App\Services;

use App\Mail\TicketPurchaseMail;
use App\Models\EmailLog;
use App\Models\TicketSale;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailLogService
{
    /**
     * Verifica oficialmente en el mensaje MIME enviado (y en el Mailable) que el PDF de entradas fue adjuntado.
     */
    private static function verifySentPdfAttachment(?SentMessage $sentMessage, TicketPurchaseMail $mailable): array
    {
        try {
            $original = ($sentMessage && method_exists($sentMessage, 'getOriginalMessage'))
                ? $sentMessage->getOriginalMessage()
                : null;

            if ($original && method_exists($original, 'getAttachments')) {
                foreach ($original->getAttachments() as $part) {
                    $filename = method_exists($part, 'getFilename') ? $part->getFilename() : null;
                    $contentType = method_exists($part, 'getContentType') ? strtolower((string) $part->getContentType()) : '';
                    $body = method_exists($part, 'getBody') ? $part->getBody() : '';

                    $isPdfPart = ($contentType === 'application/pdf') || ($filename && str_ends_with(strtolower($filename), '.pdf'));
                    if ($isPdfPart && is_string($body) && strlen($body) > 100 && str_starts_with(ltrim($body), '%PDF')) {
                        return [
                            'attachment_sent' => true,
                            'attachment_name' => $filename ?: ($mailable->attachmentFilename ?: "Entrada_Oficial_{$mailable->sale->receipt_number}.pdf"),
                            'attachment_size' => strlen($body),
                            'attachment_source' => $mailable->attachmentSource ?: 'mime_verified',
                            'attachment_error' => null,
                        ];
                    }
                }

                return [
                    'attachment_sent' => false,
                    'attachment_name' => $mailable->attachmentFilename,
                    'attachment_size' => 0,
                    'attachment_source' => $mailable->attachmentSource ?: 'mime_missing',
                    'attachment_error' => $mailable->attachmentError ?: 'El mensaje SMTP no incluyó ningún archivo PDF adjunto válido.',
                ];
            }
        } catch (\Throwable $ex) {
            Log::warning('Error inspeccionando adjunto MIME enviado: ' . $ex->getMessage());
        }

        if ($mailable->attachmentGenerated && ($mailable->attachmentSizeBytes ?? 0) > 100) {
            return [
                'attachment_sent' => true,
                'attachment_name' => $mailable->attachmentFilename,
                'attachment_size' => $mailable->attachmentSizeBytes,
                'attachment_source' => $mailable->attachmentSource ?: 'mailable',
                'attachment_error' => null,
            ];
        }

        return [
            'attachment_sent' => false,
            'attachment_name' => $mailable->attachmentFilename,
            'attachment_size' => 0,
            'attachment_source' => $mailable->attachmentSource ?: 'none',
            'attachment_error' => $mailable->attachmentError ?: 'No se pudo adjuntar el PDF de entradas.',
        ];
    }
    /**
     * Envía el correo de compra de entradas y registra automáticamente el resultado en email_logs
     */
    public static function sendTicketPurchaseMail(
        TicketSale $sale,
        ?string $tempPassword = null,
        bool $isNewUser = false,
        ?string $customPdfBase64 = null,
        ?string $forcedRecipient = null,
        string $mailType = 'ticket_purchase'
    ): array {
        $sale->loadMissing(['event.template', 'eventTickets']);

        $recipientEmail = trim($forcedRecipient ?: ($sale->buyer_email ?: ''));
        if (empty($recipientEmail)) {
            $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data ?? '[]', true);
            $recipientEmail = $tData['customer_email'] ?? ($tData['buyer_email'] ?? '');
        }

        // Si aún está vacío, buscar por DNI o Nombre en la tabla users
        if (empty($recipientEmail) && !empty($sale->buyer_dni) && $sale->buyer_dni !== '00000000') {
            $userFound = \App\Models\User::where('dni', $sale->buyer_dni)->first();
            if ($userFound && !empty($userFound->email)) {
                $recipientEmail = $userFound->email;
            }
        }

        $recipientName = $sale->customer_name ?: ($sale->buyer_name ?: 'Cliente');
        $eventName = $sale->event?->title ?? 'Evento ViveGo';
        $subject = "🎟️ Tus Entradas Oficiales - {$eventName} (#{$sale->receipt_number})";

        $sourceLabel = match($mailType) {
            'pos_sale' => 'Venta POS / Taquilla',
            'pos_resend' => 'Reenvío desde Taquilla',
            'ticket_resend' => 'Reenvío Portal Cliente',
            'courtesy' => 'Entrada de Cortesía',
            default => 'Venta Tienda Web'
        };

        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            $log = EmailLog::create([
                'ticket_sale_id' => $sale->id,
                'event_id' => $sale->event_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail ?: 'sin-correo@vivego.pe',
                'subject' => $subject,
                'mail_type' => $mailType,
                'status' => 'failed',
                'error_message' => 'Dirección de correo electrónico vacía o inválida.',
                'attachment_sent' => false,
                'attachment_name' => null,
                'attachment_size' => 0,
                'attachment_error' => 'Correo no enviado por dirección vacía o inválida.',
                'details' => [
                    'event_title' => $eventName,
                    'receipt_number' => $sale->receipt_number,
                    'sale_id' => $sale->id,
                    'source_label' => $sourceLabel,
                    'seller_name' => $sale->seller_name ?: 'Sistema',
                    'error_type' => 'ValidationError',
                    'attachment_sent' => false,
                    'attachment_error' => 'Correo no enviado por dirección vacía o inválida.',
                ],
                'attempts' => 1,
            ]);

            return [
                'success' => false,
                'message' => 'El correo del comprador es inválido o no existe.',
                'log' => $log,
                'attachment_sent' => false,
                'attachment_name' => null,
                'attachment_size' => 0,
                'attachment_error' => $log->attachment_error,
            ];
        }

        $mailable = new TicketPurchaseMail($sale, $tempPassword, $isNewUser, $customPdfBase64);

        try {
            $sentMessage = Mail::to($recipientEmail)->send($mailable);
            $attMeta = self::verifySentPdfAttachment($sentMessage, $mailable);

            $log = EmailLog::create([
                'ticket_sale_id' => $sale->id,
                'event_id' => $sale->event_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'mail_type' => $mailType,
                'status' => 'sent',
                'error_message' => null,
                'attachment_sent' => $attMeta['attachment_sent'],
                'attachment_name' => $attMeta['attachment_name'],
                'attachment_size' => $attMeta['attachment_size'],
                'attachment_error' => $attMeta['attachment_error'],
                'details' => [
                    'event_title' => $eventName,
                    'receipt_number' => $sale->receipt_number,
                    'quantity' => $sale->quantity,
                    'sale_id' => $sale->id,
                    'source_label' => $sourceLabel,
                    'seller_name' => $sale->seller_name ?: 'Sistema',
                    'sent_via' => config('mail.default', 'smtp'),
                    'attachment_sent' => $attMeta['attachment_sent'],
                    'attachment_name' => $attMeta['attachment_name'],
                    'attachment_size' => $attMeta['attachment_size'],
                    'attachment_source' => $attMeta['attachment_source'],
                    'attachment_error' => $attMeta['attachment_error'],
                ],
                'attempts' => 1,
                'sent_at' => now(),
            ]);

            Log::info("Email enviado exitosamente a {$recipientEmail} para la venta #{$sale->id} [Origen: {$sourceLabel} | PDF Adjunto: " . ($attMeta['attachment_sent'] ? 'SI (' . $attMeta['attachment_size'] . ' bytes)' : 'NO - ' . $attMeta['attachment_error']) . ']');

            return [
                'success' => true,
                'message' => $attMeta['attachment_sent']
                    ? 'Correo y PDF adjunto entregados exitosamente.'
                    : 'Correo entregado por SMTP, pero sin PDF adjunto verificable.',
                'log' => $log,
                'attachment_sent' => $attMeta['attachment_sent'],
                'attachment_name' => $attMeta['attachment_name'],
                'attachment_size' => $attMeta['attachment_size'],
                'attachment_error' => $attMeta['attachment_error'],
            ];
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Log::error("Error enviando email de compra a {$recipientEmail}: {$errorMsg}");
            $attError = $mailable->attachmentError ?: 'El envío SMTP falló antes o durante la transmisión del correo.';

            $log = EmailLog::create([
                'ticket_sale_id' => $sale->id,
                'event_id' => $sale->event_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail,
                'subject' => $subject,
                'mail_type' => $mailType,
                'status' => 'failed',
                'error_message' => $errorMsg,
                'attachment_sent' => false,
                'attachment_name' => $mailable->attachmentFilename,
                'attachment_size' => 0,
                'attachment_error' => $attError,
                'details' => [
                    'event_title' => $eventName,
                    'receipt_number' => $sale->receipt_number,
                    'sale_id' => $sale->id,
                    'source_label' => $sourceLabel,
                    'seller_name' => $sale->seller_name ?: 'Sistema',
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'attachment_sent' => false,
                    'attachment_error' => $attError,
                ],
                'attempts' => 1,
            ]);

            return [
                'success' => false,
                'message' => 'Error al enviar correo: ' . $errorMsg,
                'log' => $log,
                'attachment_sent' => false,
                'attachment_name' => $mailable->attachmentFilename,
                'attachment_size' => 0,
                'attachment_error' => $attError,
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

        $sale->loadMissing(['event.template', 'eventTickets']);
        $recipientEmail = $log->recipient_email;
        $mailable = new TicketPurchaseMail($sale, null, false, null);

        try {
            $sentMessage = Mail::to($recipientEmail)->send($mailable);
            $attMeta = self::verifySentPdfAttachment($sentMessage, $mailable);
            $details = is_array($log->details) ? $log->details : [];
            $details['sent_via'] = config('mail.default', 'smtp');
            $details['attachment_sent'] = $attMeta['attachment_sent'];
            $details['attachment_name'] = $attMeta['attachment_name'];
            $details['attachment_size'] = $attMeta['attachment_size'];
            $details['attachment_source'] = $attMeta['attachment_source'];
            $details['attachment_error'] = $attMeta['attachment_error'];
            $details['last_resent_at'] = now()->format('Y-m-d H:i:s');

            $log->update([
                'status' => 'sent',
                'error_message' => null,
                'attachment_sent' => $attMeta['attachment_sent'],
                'attachment_name' => $attMeta['attachment_name'],
                'attachment_size' => $attMeta['attachment_size'],
                'attachment_error' => $attMeta['attachment_error'],
                'details' => $details,
                'attempts' => $log->attempts + 1,
                'sent_at' => now(),
            ]);

            Log::info("Reenvío de email exitoso para Log #{$log->id} a {$recipientEmail} [PDF Adjunto: " . ($attMeta['attachment_sent'] ? 'SI (' . $attMeta['attachment_size'] . ' bytes)' : 'NO') . ']');

            return [
                'success' => true,
                'message' => $attMeta['attachment_sent']
                    ? "¡Correo y PDF adjunto reenviados exitosamente a {$recipientEmail}!"
                    : "Correo reenviado por SMTP a {$recipientEmail}, pero no se pudo verificar el PDF adjunto.",
                'log' => $log,
                'attachment_sent' => $attMeta['attachment_sent'],
                'attachment_name' => $attMeta['attachment_name'],
                'attachment_size' => $attMeta['attachment_size'],
                'attachment_error' => $attMeta['attachment_error'],
            ];
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Log::error("Fallo al reenviar correo para Log #{$log->id}: {$errorMsg}");
            $attError = $mailable->attachmentError ?: 'El reenvío SMTP falló antes o durante la transmisión del correo.';
            $details = is_array($log->details) ? $log->details : [];
            $details['attachment_sent'] = false;
            $details['attachment_error'] = $attError;

            $log->update([
                'status' => 'failed',
                'error_message' => $errorMsg,
                'attachment_sent' => false,
                'attachment_name' => $mailable->attachmentFilename,
                'attachment_size' => 0,
                'attachment_error' => $attError,
                'details' => $details,
                'attempts' => $log->attempts + 1,
            ]);

            return [
                'success' => false,
                'message' => 'No se pudo reenviar el correo: ' . $errorMsg,
                'log' => $log,
                'attachment_sent' => false,
                'attachment_name' => $mailable->attachmentFilename,
                'attachment_size' => 0,
                'attachment_error' => $attError,
            ];
        }
    }
}
