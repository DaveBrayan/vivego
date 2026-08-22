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

    /**
     * Create a new message instance.
     */
    public function __construct(TicketSale $sale, ?string $tempPassword = null, bool $isNewUser = false)
    {
        $this->sale = $sale;
        $this->tempPassword = $tempPassword;
        $this->isNewUser = $isNewUser;
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
