<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->timestamp('cert_ready_email_sent_at')->nullable()->after('rejected_at');
            $table->timestamp('cert_ready_notif_dismissed_at')->nullable()->after('cert_ready_email_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('calibration_requests', function (Blueprint $table) {
            $table->dropColumn(['cert_ready_email_sent_at', 'cert_ready_notif_dismissed_at']);
        });
    }
};