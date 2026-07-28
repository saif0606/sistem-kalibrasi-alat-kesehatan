<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung">
    <title>@yield('title', 'Masuk — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('uptd-theme');
                var theme = savedTheme ? savedTheme : 'light';
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
{{--
    Background gedung (auth-locked-bg.png) adalah ASET FINAL yang sudah
    disetujui client — tidak diedit/crop/regenerate sama sekali. Semua
    elemen UI (logo, judul, form) adalah overlay di atasnya, diposisikan
    sebagai persentase dari frame supaya presisinya terjaga di semua
    ukuran layar (lihat komentar CSS "LOCKED BACKGROUND OVERLAY SYSTEM").

    Frame dikunci penuh 100vh/100dvh dengan gambar object-fit:cover
    (sama seperti landing page) supaya halaman SELALU tampil dalam satu
    layar tanpa scrollbar, di aspek rasio layar mana pun.

    Card form (auth-locked-panel) TETAP SATU card tunggal — logo
    Lampung, judul, deskripsi, dan form semuanya adalah elemen di
    dalam card yang sama, bukan card/panel/container baru.

    Di layar <768px gambar disembunyikan (proporsional jadi terlalu
    pendek untuk ditempati form) dan digantikan fallback card sederhana
    yang tetap satu identitas visual (auth-mobile-fallback).
--}}
<body class="auth-body">

    <div class="locked-bg-frame auth-locked-frame">
        <img src="{{ asset('images/auth-locked-bg.png') }}" class="locked-bg-image" alt="Gedung UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung">

        <div class="locked-overlay auth-locked-brand">
            <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi">
            <span>
                <b>UPTD Balai Pengujian &amp; Kalibrasi Alat Kesehatan</b>
                <em>Provinsi Lampung</em>
            </span>
        </div>

        <div class="locked-overlay auth-locked-tagline">
            <h2>Profesional, Akurat,<br>Terpercaya</h2>
            <p>Untuk fasilitas kesehatan yang lebih baik di Provinsi Lampung.</p>
        </div>

        <div class="locked-overlay auth-locked-panel">
            <div class="auth-locked-panel-inner">
                @yield('lockedContent')
            </div>
        </div>
    </div>

    {{-- Fallback mobile (<768px) — background locked disembunyikan --}}
    <div class="auth-mobile-fallback">
        <div class="auth-card-wrap">
            <div class="auth-card">
                @yield('mobileContent')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
