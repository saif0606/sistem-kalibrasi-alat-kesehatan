<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('calibration_requests', 'bukti_pembayaran')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                $table->string('bukti_pembayaran')->nullable()->after('draft_harga');
            });
        }
    }

    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }
};
