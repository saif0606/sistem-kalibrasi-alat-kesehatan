@extends('layouts.auth')

@section('title', 'Lupa Password — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('lockedContent')
    <div class="auth-locked-logo"><i class="bi bi-key-fill"></i></div>
    <h1 class="auth-locked-title">Lupa Password?</h1>
    <p class="auth-locked-subtitle">Masukkan email akun Anda, kami akan mengirimkan tautan untuk membuat password baru.</p>

    @if (session('status'))
        <div class="auth-locked-notice">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-locked-form" method="POST" action="{{ route('lupa-password.submit') }}">
        @csrf
        <div class="auth-locked-field">
            <label for="forgot-email">Email</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="forgot-email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autocomplete="username">
            </div>
        </div>
        <button type="submit" class="auth-locked-submit">
            Kirim Tautan Reset <i class="bi bi-send"></i>
        </button>
    </form>

    <p class="auth-locked-switch"><a href="{{ route('login') }}" data-page-transition><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></p>
@endsection

@section('mobileContent')
    <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi" class="auth-card-logo">
    <h1 class="auth-card-title">Lupa Password?</h1>
    <p class="auth-card-subtitle">Masukkan email akun Anda, kami akan mengirimkan tautan untuk membuat password baru.</p>

    @if (session('status'))
        <div class="auth-locked-notice">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('lupa-password.submit') }}">
        @csrf
        <div class="auth-field">
            <label for="m-forgot-email">Email</label>
            <div class="auth-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="m-forgot-email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required>
            </div>
        </div>
        <button type="submit" class="btn btn-hero-primary w-100 justify-content-center auth-submit-btn">
            Kirim Tautan Reset <i class="bi bi-send ms-1"></i>
        </button>
    </form>

    <p class="auth-switch-text"><a href="{{ route('login') }}" data-page-transition><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a></p>
@endsection
