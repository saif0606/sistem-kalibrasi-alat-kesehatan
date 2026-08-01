@extends('layouts.auth')

@section('title', 'Daftar Akun — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

{{-- ============================================================
     Versi desktop — overlay di atas gambar gedung terkunci

     Form disederhanakan: hanya Nama Lengkap, Email, Password, dan
     Konfirmasi Password. Data instansi/NIK/HP/Username diisi belakangan
     saat pengguna membuat Pengajuan Kalibrasi, bukan saat registrasi.
============================================================ --}}
@section('lockedContent')
    <img src="{{ asset('images/logo-lampung-transparent.png') }}" alt="Logo Provinsi Lampung" class="auth-locked-panel-logo">
    <h1 class="auth-locked-title">Buat Akun Baru</h1>
    <p class="auth-locked-subtitle">Lengkapi data berikut untuk membuat akun.</p>

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Frontend siap diintegrasikan dengan Laravel Auth (belum ada backend) --}}
    <form class="auth-locked-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="auth-locked-field">
            <label for="name">Nama Lengkap</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-person"></i>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required autocomplete="name">
            </div>
        </div>
        <div class="auth-locked-field">
            <label for="reg-email">Email</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="reg-email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autocomplete="username">
            </div>
        </div>
        <div class="auth-locked-field">
            <label for="reg-password">Password</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="reg-password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password">
                <button type="button" class="auth-locked-toggle-password" data-target="reg-password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="auth-locked-field">
            <label for="reg-password-confirm">Konfirmasi Password</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" id="reg-password-confirm" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
                <button type="button" class="auth-locked-toggle-password" data-target="reg-password-confirm" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <label class="auth-locked-checkbox mb-3">
            <input type="checkbox" name="terms" required>
            <span>Saya menyetujui syarat dan ketentuan.</span>
        </label>

        <button type="submit" class="auth-locked-submit">
            Daftar <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <p class="auth-locked-switch">Sudah memiliki akun? <a href="{{ route('login') }}" data-page-transition>Login</a></p>
@endsection

{{-- ============================================================
     Versi mobile (<768px) — fallback card sederhana
============================================================ --}}
@section('mobileContent')
    <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi" class="auth-card-logo">
    <h1 class="auth-card-title">Buat Akun Baru</h1>
    <p class="auth-card-subtitle">Lengkapi data berikut untuk membuat akun.</p>

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="auth-field">
            <label for="m-name">Nama Lengkap</label>
            <div class="auth-input-group"><i class="bi bi-person"></i>
                <input type="text" id="m-name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required></div>
        </div>
        <div class="auth-field">
            <label for="m-reg-email">Email</label>
            <div class="auth-input-group"><i class="bi bi-envelope"></i>
                <input type="email" id="m-reg-email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required></div>
        </div>
        <div class="auth-field">
            <label for="m-reg-password">Password</label>
            <div class="auth-input-group"><i class="bi bi-lock"></i>
                <input type="password" id="m-reg-password" name="password" placeholder="Min. 8 karakter" required>
                <button type="button" class="auth-toggle-password" data-target="m-reg-password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
        </div>
        <div class="auth-field">
            <label for="m-reg-password-confirm">Konfirmasi Password</label>
            <div class="auth-input-group"><i class="bi bi-lock-fill"></i>
                <input type="password" id="m-reg-password-confirm" name="password_confirmation" placeholder="Ulangi password" required>
                <button type="button" class="auth-toggle-password" data-target="m-reg-password-confirm" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
            </div>
        </div>

        <label class="auth-checkbox auth-checkbox-terms mt-3">
            <input type="checkbox" name="terms" required>
            <span>Saya menyetujui syarat dan ketentuan.</span>
        </label>

        <button type="submit" class="btn btn-hero-primary w-100 justify-content-center auth-submit-btn mt-3">
            Daftar <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </form>

    <p class="auth-switch-text">Sudah memiliki akun? <a href="{{ route('login') }}" data-page-transition>Login</a></p>
@endsection
