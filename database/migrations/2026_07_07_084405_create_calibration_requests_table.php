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
        if (!Schema::hasTable('calibration_requests')) {
            Schema::create('calibration_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('registration_number')->unique();
                $table->string('device_name')->nullable();
                
                // Form fields from user mockup
                $table->string('nama_instansi');
                $table->string('nama_kontak');
                $table->string('nomor_telepon');
                $table->string('email');
                $table->text('alamat_lengkap');
                $table->string('metode_kalibrasi')->default('Kirim UPTD'); // Kirim UPTD / Kunjungan UPTD
                $table->boolean('konfirmasi_alamat')->default(false);
                
                $table->foreignId('service_category_id')->nullable()->constrained()->nullOnDelete();
                $table->text('daftar_alat'); // stores JSON array
                $table->text('catatan_tambahan')->nullable();
                
                $table->enum('status', ['Pengajuan', 'Penjadwalan', 'Kalibrasi', 'Sertifikat'])->default('Pengajuan');
                $table->text('admin_note')->nullable();
                
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai')->nullable();
                $table->boolean('selesai_menyesuaikan_kapasitas')->default(false);
                $table->date('request_date')->nullable(); // derived or compatibility field
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibration_requests');
    }
};
