<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayrollSlipMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nama,
        public string $periode,
        public string $pdfPath,
        public string $pdfPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Slip Gaji Periode {$this->periode}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payroll-slip',
            with: [
                'nama' => $this->nama,
                'periode' => $this->periode,
                'pdfPassword' => $this->pdfPassword,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath),
        ];
    }
}
