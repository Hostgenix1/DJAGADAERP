<?php

namespace App\Mail;

use App\Models\PayrollEntry;
use App\Services\PayrollService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayslipMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PayrollEntry $payrollEntry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payslip — '.$this->payrollEntry->period,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payslip',
            with: ['entry' => $this->payrollEntry],
        );
    }

    public function attachments(): array
    {
        $pdf = PayrollService::renderPayslip($this->payrollEntry)->output();
        $name = 'payslip-'.$this->payrollEntry->period.'.pdf';

        return [Attachment::fromData(fn () => $pdf, $name)->withMime('application/pdf')];
    }
}
