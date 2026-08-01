<?php

namespace App\Mail;

use App\Models\CalibrationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CalibrationRequest $calibration)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sertifikat Kalibrasi Anda Sudah Terbit — ' . $this->calibration->registration_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-ready',
        );
    }
}