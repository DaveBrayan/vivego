<?php

namespace App\Mail;

use App\Models\TicketSale;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public TicketSale $sale;
    public ?string $tempPassword;
    public bool $isNewUser;
    public ?string $customPdfBase64;
    public bool $attachmentGenerated = false;
    public ?string $attachmentFilename = null;
    public ?int $attachmentSizeBytes = null;
    public ?string $attachmentSource = null;
    public ?string $attachmentError = null;

    /**
     * Create a new message instance.
     */
    public function __construct(TicketSale $sale, ?string $tempPassword = null, bool $isNewUser = false, ?string $customPdfBase64 = null)
    {
        $this->sale = $sale;
        $this->tempPassword = $tempPassword;
        $this->isNewUser = $isNewUser;
        $this->customPdfBase64 = $customPdfBase64;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ Tus Entradas Oficiales y Recibo de Compra #' . $this->sale->receipt_number . ' - ViveGo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_purchase',
            with: [
                'sale' => $this->sale,
                'tempPassword' => $this->tempPassword,
                'isNewUser' => $this->isNewUser,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        $filename = "Entrada_Oficial_{$this->sale->receipt_number}.pdf";

        $this->attachmentGenerated = false;
        $this->attachmentFilename = $filename;
        $this->attachmentSizeBytes = null;
        $this->attachmentSource = null;
        $this->attachmentError = null;

        try {
            // Si el cliente envió el PDF compilado en alta resolución directamente desde el navegador
            if (!empty($this->customPdfBase64)) {
                $raw = trim($this->customPdfBase64);
                if (str_contains($raw, ';base64,')) {
                    $raw = explode(';base64,', $raw, 2)[1];
                }
                $pdfBinary = base64_decode(preg_replace('/\s+/', '', $raw), true);
                if ($pdfBinary === false) {
                    $pdfBinary = base64_decode($raw);
                }
                if (is_string($pdfBinary) && strlen($pdfBinary) > 100 && str_starts_with(ltrim($pdfBinary), '%PDF')) {
                    $this->attachmentGenerated = true;
                    $this->attachmentSizeBytes = strlen($pdfBinary);
                    $this->attachmentSource = 'custom_base64';

                    $attachments[] = Attachment::fromData(fn () => $pdfBinary, $filename)
                        ->withMime('application/pdf');
                    return $attachments;
                }

                Log::warning('PDF personalizado recibido vacío o con formato inválido, usando respaldo DomPDF.', [
                    'sale_id' => $this->sale->id ?? null,
                    'receipt_number' => $this->sale->receipt_number ?? null,
                ]);
            }

            // Fallback con DomPDF
            $this->sale->loadMissing(['eventTickets', 'event.template']);

            $dompdfDir = storage_path('app/dompdf');
            $dompdfFontCache = $dompdfDir . '/fonts';
            if (!@is_dir($dompdfFontCache)) {
                @mkdir($dompdfFontCache, 0775, true);
            }

            $options = new Options();
            $options->set('tempDir', $dompdfDir);
            $options->set('fontCache', $dompdfFontCache);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', [public_path(), base_path(), storage_path()]);

            $dompdf = new Dompdf($options);
            $html = view('pdf.ticket_voucher', ['sale' => $this->sale])->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper([0, 0, 794, 1123], 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            if (!is_string($pdfOutput) || strlen($pdfOutput) <= 100 || !str_starts_with($pdfOutput, '%PDF')) {
                $this->attachmentSource = 'dompdf';
                $this->attachmentError = 'DomPDF generó una salida PDF vacía o inválida.';
                Log::error('DomPDF generó una salida inválida para la venta.', [
                    'sale_id' => $this->sale->id ?? null,
                    'output_bytes' => is_string($pdfOutput) ? strlen($pdfOutput) : 0,
                ]);
                return $attachments;
            }

            $this->attachmentGenerated = true;
            $this->attachmentSizeBytes = strlen($pdfOutput);
            $this->attachmentSource = 'dompdf';

            $attachments[] = Attachment::fromData(fn () => $pdfOutput, $filename)
                ->withMime('application/pdf');
        } catch (\Throwable $e) {
            $this->attachmentGenerated = false;
            $this->attachmentSource = 'dompdf_error';
            $this->attachmentError = $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')';

            Log::error('Error generando PDF adjunto de entrada: ' . $this->attachmentError, [
                'sale_id' => $this->sale->id ?? null,
                'receipt_number' => $this->sale->receipt_number ?? null,
            ]);
        }

        return $attachments;
    }
}
