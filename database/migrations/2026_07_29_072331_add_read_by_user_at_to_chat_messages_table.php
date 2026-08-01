<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('chat_messages', 'read_by_user_at')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->timestamp('read_by_user_at')->nullable()->after('is_read');
            });
        }
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('read_by_user_at');
        });
    }
};