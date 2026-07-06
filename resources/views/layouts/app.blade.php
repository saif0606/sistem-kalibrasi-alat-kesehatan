<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'UPTD Balai Pengujian & Kalibrasi Alat Kesehatan Provinsi Lampung')</title>
<meta name="description" content="@yield('description', 'UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung - Layanan pengujian dan kalibrasi alat kesehatan profesional, akurat, dan terpercaya.')">
<link rel="icon" type="image/png" href="{{ asset('images/logo-uptd-mark.png') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- AOS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- Custom -->
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">

@stack('styles')
</head>
<body>

<!-- ================= LOADING SCREEN ================= -->
<div id="loadingScreen">
  <div class="loader-logo">
    <img src="{{ asset('images/logo-uptd-mark.png') }}" alt="Logo UPTD" style="width:70%;height:70%;object-fit:contain;">
  </div>
  <div class="loader-text">UPTD BPKALKES Lampung</div>
  <div class="loader-bar"></div>
</div>

@include('partials.navbar')

<main>
@yield('content')
</main>

@include('partials.footer')

<button class="back-to-top" id="backToTop" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></button>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="{{ asset('js/landing.js') }}"></script>

@stack('scripts')
</body>
</html>
