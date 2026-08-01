{{-- ==========================================================
     LANDING PAGE GATE — tampil SEKALI per sesi browser sebelum
     Beranda. Background (landing-locked-bg.png) adalah ASET FINAL
     yang sudah disetujui client — gedung, ornamen tapis, dan
     komposisi TIDAK diedit sama sekali. Semua teks/logo/tombol di
     bawah ini adalah overlay yang diposisikan sebagai persentase
     dari frame gambar (lihat "LOCKED BACKGROUND OVERLAY SYSTEM"
     di app.css) supaya presisinya terjaga di semua ukuran layar.

     Teknis (tidak berubah dari versi sebelumnya): overlay fixed
     full-viewport, tampil sekali per sesi lewat sessionStorage +
     script blocking di <head> (lihat layouts/app.blade.php).
========================================================== --}}
<div id="landingGate" aria-hidden="true">

    {{-- ===== Versi desktop/tablet — overlay presisi di atas gambar ===== --}}
    <div class="locked-bg-frame landing-locked-frame">
        <img src="{{ asset('images/landing-locked-bg.png') }}" class="locked-bg-image" alt="Gedung UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung">

        <div class="locked-overlay locked-topright">
            <img src="{{ asset('images/logo-lampung-transparent.png') }}" alt="Logo Provinsi Lampung">
            <a href="{{ route('login') }}" id="landingLoginBtn" class="locked-login-btn" data-page-transition>
                Login <i class="bi bi-box-arrow-in-right"></i>
            </a>
        </div>

        <div class="locked-overlay landing-overlay-center">
            <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung" class="landing-logo">

            <h1 class="landing-title">UPTD Balai Pengujian &amp; Kalibrasi Alat Kesehatan</h1>
            <p class="landing-subtitle">Provinsi Lampung</p>

            <p class="landing-tagline">
                Melayani pengujian dan kalibrasi alat kesehatan secara profesional, akurat,
                terpercaya, dan terakreditasi untuk fasilitas kesehatan di seluruh Provinsi Lampung.
            </p>

            <span class="landing-badge">
                <i class="bi bi-patch-check-fill"></i> Terakreditasi ISO/IEC 17025
            </span>

            <button type="button" id="landingEnterBtn" class="btn btn-hero-primary landing-enter-btn">
                Masuk Website <i class="bi bi-arrow-right ms-2"></i>
            </button>

            <p class="landing-hint">Tekan Enter atau klik tombol di atas untuk melanjutkan</p>
        </div>
    </div>

    {{-- ===== Fallback mobile (<768px) ===== --}}
    <div class="landing-mobile-fallback">
        <div class="landing-mobile-inner">
            <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung" class="landing-logo">
            <h1 class="landing-title">UPTD Balai Pengujian &amp; Kalibrasi Alat Kesehatan</h1>
            <p class="landing-subtitle">Provinsi Lampung</p>
            <p class="landing-tagline">
                Melayani pengujian dan kalibrasi alat kesehatan secara profesional, akurat,
                terpercaya, dan terakreditasi untuk fasilitas kesehatan di seluruh Provinsi Lampung.
            </p>
            <span class="landing-badge">
                <i class="bi bi-patch-check-fill"></i> Terakreditasi ISO/IEC 17025
            </span>
            <button type="button" id="landingEnterBtnMobile" class="btn btn-hero-primary landing-enter-btn">
                Masuk Website <i class="bi bi-arrow-right ms-2"></i>
            </button>
            <p class="landing-hint">Tekan Enter atau klik tombol di atas untuk melanjutkan</p>
            <a href="{{ route('login') }}" id="landingLoginBtnMobile" class="locked-login-btn mt-3" data-page-transition>
                Login <i class="bi bi-box-arrow-in-right"></i>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        var gate = document.getElementById('landingGate');
        if (!gate) return;

        if (!document.documentElement.classList.contains('show-landing-gate')) {
            return;
        }

        document.body.classList.add('landing-gate-active');

        function enterSite() {
            try { sessionStorage.setItem('uptdLandingSeen', '1'); } catch (e) {}
            document.documentElement.classList.remove('show-landing-gate');
            gate.classList.add('landing-gate-leaving');
            document.body.classList.remove('landing-gate-active');
            window.setTimeout(function () {
                gate.style.display = 'none';
            }, 500);
        }

        {{-- Navigasi HANYA lewat tombol "Masuk Website" atau tombol Enter —
             klik di area kosong dan scroll TIDAK memicu apa pun (sesuai
             ketentuan: landing page tidak boleh berpindah halaman saat
             area kosong diklik). --}}

        ['landingLoginBtn', 'landingLoginBtnMobile'].forEach(function (id) {
            var loginBtn = document.getElementById(id);
            if (loginBtn) {
                loginBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    try { sessionStorage.setItem('uptdLandingSeen', '1'); } catch (err) {}
                });
            }
        });

        ['landingEnterBtn', 'landingEnterBtnMobile'].forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    enterSite();
                });
            }
        });

        document.addEventListener('keydown', function (e) {
            if (!document.documentElement.classList.contains('show-landing-gate')) return;
            if (e.key === 'Enter') {
                e.preventDefault();
                enterSite();
            }
        });
    })();
</script>
@endpush
