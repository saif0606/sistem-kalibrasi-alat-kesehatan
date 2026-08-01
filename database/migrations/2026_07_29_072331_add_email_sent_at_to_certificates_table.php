<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('certificates', 'email_sent_at')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->timestamp('email_sent_at')->nullable()->after('issued_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('email_sent_at');
        });
    }
};