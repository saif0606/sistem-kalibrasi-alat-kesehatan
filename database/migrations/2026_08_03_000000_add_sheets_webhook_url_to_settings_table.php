<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'sheets_webhook_url')) {
                $table->string('sheets_webhook_url')->nullable()->after('spreadsheet_url');
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'sheets_webhook_url')) {
                $table->dropColumn('sheets_webhook_url');
            }
        });
    }
};
