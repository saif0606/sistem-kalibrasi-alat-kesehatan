<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('chat_messages', 'intent')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->string('intent')->nullable()->after('message');
            });
        }

        if (!Schema::hasColumn('chat_messages', 'confidence')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->float('confidence')->nullable()->after('intent');
            });
        }

        // Ubah sender_role dari enum ke string biar bisa nampung 'bot'
        if (Schema::hasColumn('chat_messages', 'sender_role')) {
            DB::statement("ALTER TABLE chat_messages MODIFY sender_role VARCHAR(20) NOT NULL DEFAULT 'user'");
        }
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['intent', 'confidence']);
        });
    }
};