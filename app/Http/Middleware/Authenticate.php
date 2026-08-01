<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Ke mana tamu yang belum login diarahkan.
     */
    protected function redirectTo($request)
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Override supaya bisa menitipkan pesan notice yang sesuai halaman
     * yang coba diakses — memakai blok session('notice') yang sudah ada
     * di resources/views/auth/login.blade.php (tidak ada perubahan UI,
     * hanya isi pesannya yang disesuaikan per halaman).
     */
    protected function unauthenticated($request, array $guards)
    {
        if (! $request->expectsJson()) {
            $request->session()->flash('notice', $this->noticeFor($request));
        }

        parent::unauthenticated($request, $guards);
    }

    private function noticeFor($request): string
    {
        return match ($request->route()?->getName()) {
            'dashboard.pengajuan' => 'Silakan login terlebih dahulu untuk mengajukan kalibrasi.',
            'proses' => 'Silakan login untuk melihat proses pengajuan Anda.',
            'dashboard.riwayat' => 'Silakan login terlebih dahulu untuk melihat riwayat pengajuan.',
            'dashboard.profile' => 'Silakan login terlebih dahulu untuk mengakses halaman ini.',
            default => 'Silakan login terlebih dahulu untuk mengakses Dashboard.',
        };
    }
}
