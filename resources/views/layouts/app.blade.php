<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung - Layanan pengujian dan kalibrasi alat kesehatan yang profesional, akurat, dan terpercaya.">
    <title>@yield('title', 'UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Inline theme bootstrap: runs before CSS/paint to avoid a light-mode flash
         when the visitor has already chosen dark mode on a previous visit.
         Juga memutuskan (sebelum halaman digambar) apakah Landing Page gate
         perlu ditampilkan — supaya pengunjung yang sudah lihat Landing Page
         di sesi browser ini tidak melihat "kedipan" gate muncul lalu hilang. --}}
    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('uptd-theme');
                var theme = savedTheme ? savedTheme : 'light';
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
            try {
                if (!sessionStorage.getItem('uptdLandingSeen')) {
                    document.documentElement.classList.add('show-landing-gate');
                }
            } catch (e) {
                // sessionStorage tidak tersedia (mis. mode privat ketat) —
                // aman untuk tidak menampilkan gate daripada mengunci pengguna.
            }
        })();
    </script>

    {{-- Google Font: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- AOS Animation --}}
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- Custom App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

    {{-- ==========================================================
         LOADING SCREEN — tampil sekilas saat halaman pertama dimuat
    ========================================================== --}}
    <div id="loadingScreen" class="loading-screen" aria-hidden="true">
        <div class="loading-inner">
            <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="" class="loading-logo">
            <div class="loading-bar"><span></span></div>
        </div>
    </div>

    {{-- Skip to content for accessibility --}}
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    @include('partials.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Floating chat bubble — WhatsApp quick access, muncul di semua halaman --}}
    @include('partials.chatbot-widget')

    {{-- Back to top button --}}
    <button id="backToTop" class="back-to-top" aria-label="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>

    {{-- Bootstrap 5.3 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- AOS Animation JS --}}
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    {{-- Custom App JS --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
