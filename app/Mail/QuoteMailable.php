<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quote $quote,
        public string $pdfContent,
        public ?string $note = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quotation '.$this->quote->number.' - '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quote',
            with: [
                'quote' => $this->quote,
                'note' => $this->note,
            ],
        );
    }

    public function attachments(): array
    {
        $filename = 'quotation-'.str_replace('/', '-', $this->quote->number).'.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfContent, $filename)->withMime('application/pdf'),
        ];
    }
}
