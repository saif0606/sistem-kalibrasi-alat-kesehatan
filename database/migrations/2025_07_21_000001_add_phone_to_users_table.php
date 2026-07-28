<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Tambah kolom phone (nullable) ke tabel users
|--------------------------------------------------------------------------
| Nomor HP SENGAJA tidak diminta saat register (lihat RegisteredUserController)
| — baru terisi begitu user mengajukan kalibrasi pertama kali lewat form
| Ajukan Kalibrasi (nomor yang diinput di form itu akan disimpan ke sini).
| Selama belum pernah mengajukan, kolom ini tetap null dan ditampilkan
| sebagai "-" di halaman Profil Akun — bukan data dummy.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
