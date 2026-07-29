<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\CalibrationRequest;
use Illuminate\Support\Facades\Mail;

#[Signature('app:debug-cert')]
#[Description('Debug certificate email sending')]
class DebugCertCommand extends Command
{
    public function handle()
    {
        $calibrations = CalibrationRequest::with(['certificate', 'user'])->where('status', 'Sertifikat')->latest()->get();

        if ($calibrations->isEmpty()) {
            $this->warn('Tidak ada pengajuan dengan status Sertifikat.');
            return;
        }

        foreach ($calibrations as $c) {
            $this->info('=== Pengajuan: ' . $c->registration_number . ' ===');
            $this->info('Email di form (calibration->email): ' . ($c->email ?? 'NULL'));
            $this->info('Status: ' . $c->status);
            $this->info('Has certificate record: ' . ($c->certificate ? 'YES' : 'NO'));
            if ($c->certificate) {
                $this->info('Certificate file_path: ' . $c->certificate->file_path);
                $this->info('email_sent_at: ' . ($c->certificate->email_sent_at ?? 'NULL - belum pernah dikirim'));
            }
            $this->line('');
        }

        // Try sending manually to latest Sertifikat
        $latest = $calibrations->first();
        $this->info('Mencoba kirim email ke: ' . $latest->email);
        try {
            Mail::to($latest->email)->send(new \App\Mail\CertificateReadyMail($latest));
            $this->info('✅ Email berhasil dikirim ke: ' . $latest->email);
            // Update email_sent_at
            if ($latest->certificate) {
                $latest->certificate->update(['email_sent_at' => now()]);
                $this->info('✅ email_sent_at di-update');
            }
        } catch (\Throwable $e) {
            $this->error('❌ Gagal kirim: ' . $e->getMessage());
        }
    }
}
