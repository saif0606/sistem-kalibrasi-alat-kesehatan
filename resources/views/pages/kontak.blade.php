@extends('layouts.app')

@section('title', 'Hubungi Kami — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // Data kontak resmi — sengaja SAMA PERSIS dengan yang dipakai di
        // partials/footer.blade.php dan Berita & Informasi (Media Center),
        // supaya tidak ada informasi yang berbeda antar halaman.
        $waNumber = '6281292923438';
        $waDisplay = '0812-9292-3438';
        $waLink = 'https://api.whatsapp.com/send/?phone=' . $waNumber . '&text=' . urlencode('Halo, saya ingin bertanya mengenai layanan kalibrasi.') . '&type=phone_number&app_absent=0';
        $email = 'uptdifka@gmail.com';
        $alamatSingkat = 'Jl. Dokter Susilo No. 44, Pahoman, Bandar Lampung';
        $alamatLengkap = 'UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan, Jl. Dokter Susilo No. 44, Pahoman, Kec. Engal, Kota Bandar Lampung, Lampung 35212';
        $mapsQuery = urlencode('Instalasi Farmasi dan Kalibrasi Alkes, Jalan Dokter Susilo No.44, Pahoman, Engal, Kota Bandar Lampung, Lampung 35212');
        $mapsEmbedUrl = 'https://www.google.com/maps?q=' . $mapsQuery . '&output=embed';
        $mapsShortUrl = 'https://maps.app.goo.gl/Yy8C78nKrXjsADng8';

        $infoCards = [
            ['icon' => 'bi-geo-alt-fill', 'label' => 'Alamat', 'value' => $alamatSingkat, 'href' => $mapsShortUrl, 'external' => true],
            ['icon' => 'bi-telephone-fill', 'label' => 'Telepon', 'value' => $waDisplay, 'href' => 'tel:+' . $waNumber, 'external' => false],
            ['icon' => 'bi-whatsapp', 'label' => 'WhatsApp', 'value' => $waDisplay, 'href' => $waLink, 'external' => true],
            ['icon' => 'bi-envelope-fill', 'label' => 'Email', 'value' => $email, 'href' => 'mailto:' . $email, 'external' => false],
            ['icon' => 'bi-clock-fill', 'label' => 'Jam Operasional', 'value' => 'Senin–Jumat, 08.00 WIB', 'href' => '#jam-operasional', 'external' => false],
        ];

        $faqList = [
            ['q' => 'Bagaimana cara mengajukan kalibrasi?', 'a' => 'Buat akun terlebih dahulu melalui menu Login/Register, lalu masuk ke Dashboard dan pilih menu Ajukan Kalibrasi. Lengkapi data instansi, data alat, dan dokumen pendukung — prosesnya bisa selesai kurang dari 3 menit.'],
            ['q' => 'Berapa lama proses kalibrasi?', 'a' => 'Verifikasi pengajuan dilakukan maksimal 1×24 jam kerja. Lama proses kalibrasi selanjutnya bervariasi tergantung jenis dan jumlah alat, umumnya beberapa hari kerja setelah jadwal disepakati.'],
            ['q' => 'Apakah UPTD melayani kalibrasi di lokasi pelanggan?', 'a' => 'Ya. UPTD Balai Pengujian dan Kalibrasi melayani pelaksanaan kalibrasi secara on-site di lokasi pelanggan sesuai kebutuhan.'],
            ['q' => 'Bagaimana cara memperoleh sertifikat hasil kalibrasi?', 'a' => 'Setelah proses kalibrasi selesai, sertifikat hasil kalibrasi dapat diunduh melalui akun pelanggan. Selain itu, pelanggan juga dapat mengambil sertifikat fisik secara langsung di kantor UPTD Balai Pengujian dan Kalibrasi.'],
        ];

        $socialAccounts = [
            ['icon' => 'bi-instagram', 'label' => 'Instagram', 'handle' => '@uptdifka', 'href' => 'https://www.instagram.com/uptdifka'],
            ['icon' => 'bi-whatsapp', 'label' => 'WhatsApp', 'handle' => $waDisplay, 'href' => $waLink],
            ['icon' => 'bi-envelope-fill', 'label' => 'Email', 'handle' => $email, 'href' => 'mailto:' . $email],
        ];

        $jamOperasional = [
            ['hari' => 'Senin – Kamis', 'jam' => '08.00 – 16.00 WIB', 'buka' => true],
            ['hari' => 'Jumat', 'jam' => '08.00 – 16.30 WIB', 'buka' => true],
            ['hari' => 'Sabtu', 'jam' => 'Tutup', 'buka' => false],
            ['hari' => 'Minggu', 'jam' => 'Tutup', 'buka' => false],
        ];

        $areaPelayanan = ['Rumah Sakit', 'Puskesmas', 'Klinik', 'Laboratorium', 'Fasilitas Pelayanan Kesehatan Lainnya'];
    @endphp

    <x-page-hero
        title="Hubungi Kami"
        subtitle="Tim UPTD siap membantu informasi mengenai layanan kalibrasi, jadwal pelayanan, proses pengajuan, serta informasi lainnya melalui berbagai kanal komunikasi resmi."
    />

    {{-- ============================================================
         INFORMASI KONTAK
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="row g-3 g-lg-4">
                @foreach ($infoCards as $i => $card)
                    <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                        <a href="{{ $card['href'] }}" @if ($card['external']) target="_blank" rel="noopener" @endif class="kontak-info-card">
                            <span class="kontak-info-icon"><i class="bi {{ $card['icon'] }}"></i></span>
                            <span class="kontak-info-label">{{ $card['label'] }}</span>
                            <span class="kontak-info-value">{{ $card['value'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         LOKASI KANTOR
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="kontak-map-card" data-aos="fade-up">
                <a href="{{ $mapsShortUrl }}" target="_blank" rel="noopener" class="kontak-map-embed" aria-label="Buka lokasi UPTD Balai Pengujian dan Kalibrasi di Google Maps">
                    <iframe
                        src="{{ $mapsEmbedUrl }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        tabindex="-1"
                        title="Lokasi UPTD Balai Pengujian dan Kalibrasi">
                    </iframe>
                </a>
                <div class="kontak-map-info">
                    <span class="section-eyebrow">Lokasi Kantor</span>
                    <h2>UPTD Balai Pengujian &amp; Kalibrasi Alat Kesehatan</h2>
                    <ul class="kontak-map-detail-list">
                        <li><i class="bi bi-geo-alt-fill"></i> {{ $alamatLengkap }}</li>
                        <li><i class="bi bi-signpost-split-fill"></i> Patokan: sebelah Kantor Dinas Kesehatan Provinsi Lampung, Jl. Dokter Susilo.</li>
                        <li><i class="bi bi-clock-fill"></i> Senin–Kamis 08.00–16.00 WIB &bull; Jumat 08.00–16.30 WIB</li>
                    </ul>
                    <a href="{{ $mapsShortUrl }}" target="_blank" rel="noopener" class="btn btn-hero-primary">
                        <i class="bi bi-map me-1"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         HUBUNGI VIA WHATSAPP
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="dash-quick-action" data-aos="fade-up">
                <div class="dash-quick-action-icon">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div class="dash-quick-action-body">
                    <h2>Butuh Bantuan Lebih Cepat?</h2>
                    <p>Tim kami siap membantu melalui WhatsApp pada jam operasional.</p>
                </div>
                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-hero-primary dash-quick-action-btn">
                    <i class="bi bi-whatsapp me-1"></i> Chat via WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         FAQ
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="text-center mb-4" data-aos="fade-up">
                <span class="section-eyebrow d-inline-flex">Pertanyaan Umum</span>
                <h2 class="section-title" style="font-size: clamp(1.5rem, 1.3rem + 0.8vw, 2rem);">Pertanyaan yang Sering Diajukan</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up" data-aos-delay="80">
                    <div class="accordion faq-accordion" id="kontakFaqAccordion">
                        @foreach ($faqList as $faq)
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="kontakFaqHeading{{ $loop->index }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#kontakFaqCollapse{{ $loop->index }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="kontakFaqCollapse{{ $loop->index }}">
                                        {{ $faq['q'] }}
                                    </button>
                                </h3>
                                <div id="kontakFaqCollapse{{ $loop->index }}"
                                     class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                     aria-labelledby="kontakFaqHeading{{ $loop->index }}"
                                     data-bs-parent="#kontakFaqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         MEDIA SOSIAL
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="text-center mb-4" data-aos="fade-up">
                <span class="section-eyebrow d-inline-flex">Tetap Terhubung</span>
                <h2 class="section-title" style="font-size: clamp(1.5rem, 1.3rem + 0.8vw, 2rem);">Ikuti Kami di Media Sosial</h2>
                <p class="section-text mb-0">Dapatkan update aktivitas, edukasi, dan pengumuman terbaru dari kanal resmi kami.</p>
            </div>
            <div class="row g-3 justify-content-center">
                @foreach ($socialAccounts as $i => $social)
                    <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                        <a href="{{ $social['href'] }}" target="_blank" rel="noopener" class="kontak-social-card">
                            <span class="kontak-social-icon"><i class="bi {{ $social['icon'] }}"></i></span>
                            <strong>{{ $social['label'] }}</strong>
                            <span>{{ $social['handle'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         JAM OPERASIONAL & AREA PELAYANAN
    ============================================================ --}}
    <section class="section-padding pb-0" id="jam-operasional">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7" data-aos="fade-up">
                    <div class="kal-card h-100">
                        <div class="kal-card-head">
                            <span class="kal-card-icon"><i class="bi bi-clock-fill"></i></span>
                            <div>
                                <h2>Jam Operasional</h2>
                                <p>Waktu pelayanan tatap muka maupun WhatsApp.</p>
                            </div>
                        </div>
                        <ul class="kontak-jam-list">
                            @foreach ($jamOperasional as $jam)
                                <li>
                                    <span>{{ $jam['hari'] }}</span>
                                    <strong class="{{ $jam['buka'] ? '' : 'kontak-jam-tutup' }}">{{ $jam['jam'] }}</strong>
                                </li>
                            @endforeach
                        </ul>
                        <p class="kontak-jam-note"><i class="bi bi-info-circle me-1"></i> Respon WhatsApp dilakukan pada jam operasional.</p>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="80">
                    <div class="kal-card h-100">
                        <div class="kal-card-head">
                            <span class="kal-card-icon"><i class="bi bi-hospital"></i></span>
                            <div>
                                <h2>Area Pelayanan</h2>
                                <p>Fasilitas kesehatan yang dapat kami layani.</p>
                            </div>
                        </div>
                        <ul class="kontak-area-list">
                            @foreach ($areaPelayanan as $area)
                                <li><i class="bi bi-check-circle-fill"></i> {{ $area }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         CALL TO ACTION
    ============================================================ --}}
    <section class="section-padding">
        <div class="container">
            <div class="cta-box" data-aos="zoom-in">
                <h2>Siap Mengajukan Kalibrasi?</h2>
                <p>Ajukan permohonan kalibrasi alat kesehatan secara online melalui website UPTD.</p>
                <a href="{{ route('dashboard.pengajuan') }}" class="btn btn-cta">
                    Ajukan Kalibrasi <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

@endsection
