{{-- ==========================================================
     FOOTER — Global, versi ringkas (Opsi B).
     Info lengkap (peta besar, form kontak) hanya ada di halaman Kontak.
     Alamat di sini singkat & langsung bisa diklik ke Google Maps.
========================================================== --}}
@php
    $mapsQuery = urlencode('Instalasi Farmasi dan Kalibrasi Alkes, Jalan Dokter Susilo No.44, Pahoman, Engal, Kota Bandar Lampung, Lampung 35212');
    $mapsEmbedUrl = 'https://www.google.com/maps?q=' . $mapsQuery . '&output=embed';
    $mapsShortUrl = 'https://maps.app.goo.gl/Yy8C78nKrXjsADng8';
@endphp
<footer class="site-footer">
    <div class="container">
        <div class="row gy-5 py-5">

            {{-- Logo & Deskripsi Singkat --}}
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-3 mb-3 footer-brand">
                    <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD" class="footer-logo">
                    <span class="fw-semibold text-white">UPTD Balai Pengujian &amp; Kalibrasi<br>Alat Kesehatan Provinsi Lampung</span>
                </a>
                <p class="footer-text footer-text-desc">
                    Unit Pelaksana Teknis Daerah di bawah Dinas Kesehatan Provinsi Lampung yang
                    melayani pengujian dan kalibrasi alat kesehatan secara profesional, akurat,
                    dan terpercaya sesuai standar ISO/IEC 17025.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="https://www.instagram.com/uptdifka" target="_blank" rel="noopener" class="social-icon" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send/?phone=6281292923438&text&type=phone_number&app_absent=0" target="_blank" rel="noopener" class="social-icon" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="mailto:uptdifka@gmail.com" class="social-icon" aria-label="Email">
                        <i class="bi bi-envelope-fill"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="bi bi-house-door"></i> Beranda</a></li>
                    <li><a href="{{ route('profil') }}"><i class="bi bi-building"></i> Profil</a></li>
                    <li><a href="{{ route('layanan') }}"><i class="bi bi-tools"></i> Layanan</a></li>
                    <li><a href="{{ route('user.calibrations.index') }}"><i class="bi bi-arrow-repeat"></i> Proses</a></li>
                    <li><a href="{{ route('berita') }}"><i class="bi bi-newspaper"></i> Berita</a></li>
                    <li><a href="{{ route('user.chat.index') }}"><i class="bi bi-chat-dots"></i> Chatbot</a></li>
                    <li><a href="{{ route('kontak') }}"><i class="bi bi-envelope-paper"></i> Kontak</a></li>
                </ul>
            </div>

            {{-- Kontak Singkat --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Kontak</h6>
                <ul class="footer-contact">
                    <li>
                        <i class="bi bi-geo-alt-fill"></i>
                        <a href="{{ $mapsShortUrl }}" target="_blank" rel="noopener" class="footer-address-link">
                            Jl. Dokter Susilo No. 44, Pahoman, Bandar Lampung
                            <i class="bi bi-box-arrow-up-right footer-address-icon"></i>
                        </a>
                    </li>
                    <li>
                        <i class="bi bi-envelope-fill"></i>
                        <a href="mailto:uptdifka@gmail.com">uptdifka@gmail.com</a>
                    </li>
                    <li>
                        <i class="bi bi-whatsapp"></i>
                        <a href="https://api.whatsapp.com/send/?phone=6281292923438&text&type=phone_number&app_absent=0" target="_blank" rel="noopener">0812-9292-3438</a>
                    </li>
                    <li>
                        <i class="bi bi-instagram"></i>
                        <a href="https://www.instagram.com/uptdifka" target="_blank" rel="noopener">@uptdifka</a>
                    </li>
                </ul>
            </div>

            {{-- Jam Operasional --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Jam Operasional</h6>
                <ul class="footer-hours">
                    <li><span>Senin&ndash;Kamis</span><span>08.00&ndash;16.00</span></li>
                    <li><span>Jumat</span><span>08.00&ndash;16.30</span></li>
                    <li><span>Sabtu&ndash;Minggu</span><span>Tutup</span></li>
                </ul>

                <a href="{{ $mapsShortUrl }}" target="_blank" rel="noopener" class="footer-map-preview" aria-label="Buka lokasi UPTD Balai Pengujian dan Kalibrasi di Google Maps">
                    <iframe
                        src="{{ $mapsEmbedUrl }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        tabindex="-1"
                        title="Lokasi UPTD Balai Pengujian dan Kalibrasi">
                    </iframe>
                    <span class="footer-map-preview-overlay">
                        <i class="bi bi-arrows-fullscreen"></i> Lihat Lokasi
                    </span>
                </a>
                <a href="{{ $mapsShortUrl }}" target="_blank" rel="noopener" class="footer-map-cta mt-3">
                    <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                </a>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3 gap-2">
            <p class="footer-copyright mb-0">
                &copy; {{ date('Y') }} UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung. Hak cipta dilindungi.
            </p>
            <p class="footer-copyright mb-0">
                Dinas Kesehatan Provinsi Lampung
            </p>
        </div>
    </div>
</footer>
