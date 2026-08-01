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
        if (!Schema::hasColumn('calibration_requests', 'waktu_kalibrasi')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                $table->time('waktu_kalibrasi')->nullable()->after('tanggal_kalibrasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn('waktu_kalibrasi');
        });
    }
};
