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

    public function build(): static
    {
        $logoPath = public_path('images/logo-white.png');
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc  = 'data:image/png;base64,' . $logoData;

        return $this->view('emails.certificate-ready', [
            'calibration' => $this->calibration,
            'logoSrc'     => $logoSrc,
        ])->subject('Sertifikat Kalibrasi Anda Sudah Terbit — ' . $this->calibration->registration_number);
    }
}