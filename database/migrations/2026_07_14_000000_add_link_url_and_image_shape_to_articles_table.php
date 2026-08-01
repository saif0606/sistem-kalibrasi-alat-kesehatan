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
        Schema::table('articles', function (Blueprint $table) {
            // link_url sudah ada dari migration sebelumnya, cukup tambah image_shape
            if (!\Illuminate\Support\Facades\Schema::hasColumn('articles', 'image_shape')) {
                $table->string('image_shape')->default('square')->after('link_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['link_url', 'image_shape']);
        });
    }
};
