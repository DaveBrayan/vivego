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

        try {
            // Si el cliente envió el PDF compilado en alta resolución directamente desde el navegador
            if (!empty($this->customPdfBase64)) {
                $raw = $this->customPdfBase64;
                if (str_contains($raw, ';base64,')) {
                    $raw = explode(';base64,', $raw)[1];
                }
                $pdfBinary = base64_decode($raw);
                if ($pdfBinary) {
                    $attachments[] = Attachment::fromData(fn () => $pdfBinary, "Entrada_Oficial_{$this->sale->receipt_number}.pdf")
                        ->withMime('application/pdf');
                    return $attachments;
                }
            }

            // Fallback con DomPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', [public_path(), base_path()]);

            $dompdf = new Dompdf($options);
            $html = view('pdf.ticket_voucher', ['sale' => $this->sale])->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper([0, 0, 794, 1123], 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            $attachments[] = Attachment::fromData(fn () => $pdfOutput, "Entrada_Oficial_{$this->sale->receipt_number}.pdf")
                ->withMime('application/pdf');
        } catch (\Throwable $e) {
            Log::warning('Error generando PDF adjunto de entrada: ' . $e->getMessage());
        }

        return $attachments;
    }
}
