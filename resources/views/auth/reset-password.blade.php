@extends('layouts.auth')

@section('title', 'Reset Password — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('lockedContent')
    <div class="auth-locked-logo"><i class="bi bi-shield-lock-fill"></i></div>
    <h1 class="auth-locked-title">Buat Password Baru</h1>
    <p class="auth-locked-subtitle">Masukkan password baru untuk akun Anda.</p>

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-locked-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-locked-field">
            <label for="reset-email">Email</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="reset-email" name="email" value="{{ old('email', $request->email) }}" placeholder="nama@instansi.go.id" required autocomplete="username">
            </div>
        </div>
        <div class="auth-locked-field">
            <label for="reset-password">Password Baru</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="reset-password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password">
                <button type="button" class="auth-locked-toggle-password" data-target="reset-password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="auth-locked-field">
            <label for="reset-password-confirm">Konfirmasi Password</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" id="reset-password-confirm" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password">
                <button type="button" class="auth-locked-toggle-password" data-target="reset-password-confirm" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="auth-locked-submit">
            Simpan Password <i class="bi bi-check-lg"></i>
        </button>
    </form>

    <p class="auth-locked-switch"><a href="{{ route('login') }}" data-page-transition><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></p>
@endsection

@section('mobileContent')
    <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi" class="auth-card-logo">
    <h1 class="auth-card-title">Buat Password Baru</h1>
    <p class="auth-card-subtitle">Masukkan password baru untuk akun Anda.</p>

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="m-reset-email">Email</label>
            <div class="auth-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="m-reset-email" name="email" value="{{ old('email', $request->email) }}" placeholder="nama@instansi.go.id" required>
            </div>
        </div>
        <div class="auth-field">
            <label for="m-reset-password">Password Baru</label>
            <div class="auth-input-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="m-reset-password" name="password" placeholder="Min. 8 karakter" required>
            </div>
        </div>
        <div class="auth-field">
            <label for="m-reset-password-confirm">Konfirmasi Password</label>
            <div class="auth-input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" id="m-reset-password-confirm" name="password_confirmation" placeholder="Ulangi password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-hero-primary w-100 justify-content-center auth-submit-btn">
            Simpan Password <i class="bi bi-check-lg ms-1"></i>
        </button>
    </form>

    <p class="auth-switch-text"><a href="{{ route('login') }}" data-page-transition><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></p>
@endsection
