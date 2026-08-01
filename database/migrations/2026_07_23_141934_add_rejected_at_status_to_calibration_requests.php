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
        if (!Schema::hasColumn('calibration_requests', 'rejected_at_status')) {
            Schema::table('calibration_requests', function (Blueprint $table) {
                $table->string('rejected_at_status')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn('rejected_at_status');
        });
    }
};
