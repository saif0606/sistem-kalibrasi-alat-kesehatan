@extends('layouts.auth')

@section('title', 'Login — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

{{-- ============================================================
     Versi desktop — overlay di atas gambar gedung terkunci
============================================================ --}}
@section('lockedContent')
    <img src="{{ asset('images/logo-lampung-transparent.png') }}" alt="Logo Provinsi Lampung" class="auth-locked-panel-logo">
    <h1 class="auth-locked-title">Selamat Datang Kembali</h1>
    <p class="auth-locked-subtitle">Masuk untuk mengajukan dan memantau proses kalibrasi Anda.</p>

    @if (session('status'))
        <div class="auth-locked-notice">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
    @endif

    @if (session('notice'))
        <div class="auth-locked-notice">
            <i class="bi bi-info-circle-fill"></i> {{ session('notice') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Frontend siap diintegrasikan dengan Laravel Auth (belum ada backend) --}}
    <form class="auth-locked-form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="auth-locked-field">
            <label for="email">Email</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autocomplete="username">
            </div>
        </div>
        <div class="auth-locked-field">
            <label for="password">Password</label>
            <div class="auth-locked-input-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                <button type="button" class="auth-locked-toggle-password" data-target="password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="auth-locked-field-row">
            <label class="auth-locked-checkbox">
                <input type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
            <a href="{{ route('lupa-password') }}" class="auth-locked-link" data-page-transition>Lupa Password?</a>
        </div>
        <button type="submit" class="auth-locked-submit">
            Login <i class="bi bi-box-arrow-in-right"></i>
        </button>
    </form>

    <p class="auth-locked-switch">Belum punya akun? <a href="{{ route('register') }}" data-page-transition>Daftar di sini</a></p>
@endsection

{{-- ============================================================
     Versi mobile (<768px) — fallback card sederhana
============================================================ --}}
@section('mobileContent')
    <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi" class="auth-card-logo">
    <h1 class="auth-card-title">Selamat Datang Kembali</h1>
    <p class="auth-card-subtitle">Masuk untuk mengajukan dan memantau proses kalibrasi Anda.</p>

    @if (session('status'))
        <div class="auth-locked-notice">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
    @endif

    @if (session('notice'))
        <div class="auth-locked-notice">
            <i class="bi bi-info-circle-fill"></i> {{ session('notice') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-locked-notice auth-locked-notice-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="auth-field">
            <label for="m-email">Email</label>
            <div class="auth-input-group">
                <i class="bi bi-envelope"></i>
                <input type="email" id="m-email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autocomplete="username">
            </div>
        </div>
        <div class="auth-field">
            <label for="m-password">Password</label>
            <div class="auth-input-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="m-password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                <button type="button" class="auth-toggle-password" data-target="m-password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="auth-field-row">
            <label class="auth-checkbox">
                <input type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
            <a href="{{ route('lupa-password') }}" class="auth-link-muted" data-page-transition>Lupa Password?</a>
        </div>
        <button type="submit" class="btn btn-hero-primary w-100 justify-content-center auth-submit-btn">
            Login <i class="bi bi-box-arrow-in-right ms-1"></i>
        </button>
    </form>

    <p class="auth-switch-text">Belum punya akun? <a href="{{ route('register') }}" data-page-transition>Daftar di sini</a></p>
@endsection
