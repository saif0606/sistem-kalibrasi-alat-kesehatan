<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Catatan: DummyDataSeeder yang tadinya dipanggil di sini tidak ada
     * file-nya di project (kemungkinan belum sempat ditambahkan ke repo),
     * jadi dihapus dari daftar supaya `php artisan db:seed` tidak gagal.
     * Tidak ada data pengajuan/chat contoh yang dihasilkan otomatis —
     * silakan coba alur pengajuan & chat langsung lewat akun di bawah.
     */
    public function run(): void
    {
        $this->call([
            FaqAnswerSeeder::class,
        ]);

        // Akun Admin — untuk login di /admin/login
        User::updateOrCreate(
            ['email' => 'admin@uptdifka.test'],
            [
                'name' => 'Admin UPTD IFKA',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Akun User biasa — untuk login di /login (uji coba Proses & Chat)
        User::updateOrCreate(
            ['email' => 'user@uptdifka.test'],
            [
                'name' => 'Pengguna Uji Coba',
                'password' => bcrypt('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
