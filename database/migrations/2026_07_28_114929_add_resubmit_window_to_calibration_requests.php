<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('calibration_requests', 'rejection_reason')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                // Alasan penolakan yang dipilih admin (mis. "Dokumen", "Lainnya")
                $table->string('rejection_reason')->nullable()->after('rejected_at_status');
            });
        }

        if (!Schema::hasColumn('calibration_requests', 'allow_resubmit')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                // Apakah admin mengizinkan user upload ulang dokumen tanpa bikin pengajuan baru
                $table->boolean('allow_resubmit')->default(false)->after('rejection_reason');
            });
        }

        if (!Schema::hasColumn('calibration_requests', 'resubmit_deadline')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                // Batas waktu (1x24 jam sejak ditolak) user boleh upload ulang, sebelum nomor pesanan hangus
                $table->timestamp('resubmit_deadline')->nullable()->after('allow_resubmit');
            });
        }

        if (!Schema::hasColumn('calibration_requests', 'rejected_at')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                // Kapan tepatnya pengajuan ini ditolak (dipakai buat hitung deadline)
                $table->timestamp('rejected_at')->nullable()->after('resubmit_deadline');
            });
        }
    }

    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'allow_resubmit', 'resubmit_deadline', 'rejected_at']);
        });
    }
};