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
        if (!Schema::hasColumn('calibration_requests', 'lokasi_kalibrasi')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                $table->string('lokasi_kalibrasi')->nullable()->after('metode_kalibrasi'); // Klinik / Faskes or Lab UPTD
            });
        }
    }

    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn('lokasi_kalibrasi');
        });
    }
};
