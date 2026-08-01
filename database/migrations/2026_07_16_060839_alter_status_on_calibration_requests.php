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
        Schema::table('calibration_requests', function (Blueprint $table) {
            // Because modifying ENUMs in SQLite can be problematic, 
            // string columns behave essentially the same in SQLite without throwing errors.
            // In Laravel 11 / SQLite, dropping and recreating might be required, or we just change it to string.
            $table->string('status')->default('Pengajuan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->enum('status', ['Pengajuan', 'Penjadwalan', 'Kalibrasi', 'Sertifikat'])->default('Pengajuan')->change();
        });
    }
};
