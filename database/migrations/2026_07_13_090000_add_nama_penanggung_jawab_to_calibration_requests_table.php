<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('calibration_requests', 'nama_penanggung_jawab')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                // Nama penanggung jawab dari pihak instansi yang menangani pengajuan kalibrasi ini
                $table->string('nama_penanggung_jawab')->nullable()->after('nama_kontak');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn('nama_penanggung_jawab');
        });
    }
};
