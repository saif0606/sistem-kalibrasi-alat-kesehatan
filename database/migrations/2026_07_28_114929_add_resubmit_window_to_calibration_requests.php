<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            // Alasan penolakan yang dipilih admin (mis. "Dokumen", "Lainnya")
            $table->string('rejection_reason')->nullable()->after('rejected_at_status');
            // Apakah admin mengizinkan user upload ulang dokumen tanpa bikin pengajuan baru
            $table->boolean('allow_resubmit')->default(false)->after('rejection_reason');
            // Batas waktu (1x24 jam sejak ditolak) user boleh upload ulang, sebelum nomor pesanan hangus
            $table->timestamp('resubmit_deadline')->nullable()->after('allow_resubmit');
            // Kapan tepatnya pengajuan ini ditolak (dipakai buat hitung deadline)
            $table->timestamp('rejected_at')->nullable()->after('resubmit_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'allow_resubmit', 'resubmit_deadline', 'rejected_at']);
        });
    }
};