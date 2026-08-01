<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom `tanggal_kalibrasi`: tanggal kunjungan/pengerjaan
     * kalibrasi yang DITENTUKAN OLEH ADMIN (berbeda dari tanggal_mulai yang
     * merupakan tanggal permintaan awal dari pelanggan). Saat tanggal ini
     * tiba, status pengajuan otomatis berpindah dari "Penjadwalan" menjadi
     * "Kalibrasi" dan langsung terlihat oleh pelanggan.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('calibration_requests', 'tanggal_kalibrasi')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                $table->date('tanggal_kalibrasi')->nullable()->after('admin_note');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn('tanggal_kalibrasi');
        });
    }
};
