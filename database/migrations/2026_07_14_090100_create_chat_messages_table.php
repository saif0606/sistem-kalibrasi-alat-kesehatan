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
        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();

                // Pemilik percakapan (selalu pelanggan/user, walau pesan datang dari admin)
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

                // Admin yang mengirim pesan (null jika pesan berasal dari pelanggan)
                $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();

                $table->enum('sender_role', ['user', 'admin']);
                $table->text('message');

                // Menandai apakah pesan dari pelanggan sudah dibaca admin
                $table->boolean('is_read')->default(false);

                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
