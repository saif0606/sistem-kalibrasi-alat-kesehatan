@extends('layouts.app')

@section('title', 'Beranda — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    <x-landing-gate />

    {{-- ==========================================================
         1. HERO
         CTA "Ajukan Kalibrasi" mengarah ke /login (gerbang menuju
         Sistem Informasi Monitoring Kalibrasi).
    ========================================================== --}}
    <section id="beranda" class="hero-section">
        <x-tapis-decoration corners="tl-br" />
        <div class="hero-ornaments" aria-hidden="true">
            <span class="hero-blur-blob hero-blur-blob-1"></span>
            <span class="hero-blur-blob hero-blur-blob-2"></span>
        </div>

        <div class="container position-relative">
            <div class="row align-items-center gy-5">

                <div class="col-lg-6 hero-copy" data-aos="fade-right">
                    <span class="hero-eyebrow">
                        <i class="bi bi-patch-check-fill"></i> Terakreditasi ISO/IEC 17025
                    </span>
                    <h1 class="hero-title">
                        UPTD Balai<br>
                        Pengujian &amp; Kalibrasi<br>
                        <span class="hero-title-accent">Alat Kesehatan</span><br>
                        Provinsi Lampung
                    </h1>
                    <p class="hero-subtitle">
                        Melayani pengujian dan kalibrasi alat kesehatan secara profesional,
                        akurat, dan terpercaya — kini dilengkapi sistem monitoring kalibrasi
                        online bagi seluruh fasilitas kesehatan di Provinsi Lampung.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('user.calibrations.create') }}" class="btn btn-hero-primary">
                            Ajukan Kalibrasi <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <a href="#layanan-preview" class="btn btn-hero-outline">
                            <i class="bi bi-grid me-1"></i> Lihat Layanan
                        </a>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="hero-visual">
                        <div class="hero-image-frame">
                            <img src="{{ asset('images/hero-asn-photo.jpg') }}"
                                 width="1400" height="787"
                                 alt="Tim ASN UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung"
                                 class="hero-image">
                        </div>
                        <div class="hero-float-badge hero-float-badge-1 float-anim">
                            <span class="hero-float-icon hero-float-icon-green">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>
                            <div>
                                <strong>1.500+</strong>
                                <span>Alat Terkalibrasi</span>
                            </div>
                        </div>
                        <div class="hero-float-badge hero-float-badge-2 float-anim-delay">
                            <span class="hero-float-icon hero-float-icon-blue">
                                <i class="bi bi-shield-check"></i>
                            </span>
                            <div>
                                <strong>99%</strong>
                                <span>Tingkat Kepuasan</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================================
         2. STATISTIK
         Data dummy — struktur siap diganti query model Statistik.
    ========================================================== --}}
    <section class="stats-section">
        <div class="stats-ornaments" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row g-4 text-center">
                @php
                    $stats = [
                        ['icon' => 'bi-calendar3', 'target' => 20, 'suffix' => '+', 'label' => 'Tahun Berdiri'],
                        ['icon' => 'bi-clipboard2-pulse', 'target' => 1500, 'suffix' => '+', 'label' => 'Alat Dikalibrasi'],
                        ['icon' => 'bi-building', 'target' => 200, 'suffix' => '+', 'label' => 'Instansi Mitra'],
                        ['icon' => 'bi-emoji-smile', 'target' => 99, 'suffix' => '%', 'label' => 'Tingkat Kepuasan'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 75 }}">
                        <div class="stat-card">
                            <span class="stat-icon"><i class="bi {{ $stat['icon'] }}"></i></span>
                            <h3 class="stat-number"><span data-count-to="{{ $stat['target'] }}">0</span>{{ $stat['suffix'] }}</h3>
                            <p class="stat-label">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         3. TENTANG UPTD BALAI PENGUJIAN DAN KALIBRASI (ringkas) — pintu masuk ke /profil
    ========================================================== --}}
    <section id="tentang" class="about-section section-padding">
        <div class="container">
            <div class="row align-items-center gy-5">

                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-image-frame">
                        <img src="{{ asset('images/gedung-hero-band.jpg') }}"
                             width="1280" height="614"
                             alt="Gedung UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung"
                             class="about-image">
                        <div class="about-image-tag">
                            <i class="bi bi-building"></i> Gedung Utama Balai
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <span class="section-eyebrow">Tentang Kami</span>
                    <h2 class="section-title">
                        Mitra Terpercaya untuk<br>Keandalan Alat Kesehatan Anda
                    </h2>
                    <p class="section-text">
                        UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi
                        Lampung adalah unit pelaksana teknis daerah di bawah Dinas Kesehatan
                        Provinsi Lampung yang berkomitmen menjamin keakuratan dan keselamatan
                        alat kesehatan di seluruh fasilitas pelayanan kesehatan Lampung.
                    </p>
                    <div class="row g-3 my-4">
                        @php
                            $setting = $setting ?? null;
                            $kanPdf = $setting && $setting->sertifikat_kan
                                ? asset('storage/' . $setting->sertifikat_kan)
                                : asset('assets/pdf/akreditasi-kan-iso17025.pdf');
                            $izinPdf = $setting && $setting->surat_operasional
                                ? asset('storage/' . $setting->surat_operasional)
                                : asset('assets/pdf/izin-kemenkes.pdf');
                            $legalBadges = [
                                ['icon' => 'bi-patch-check', 'label' => 'Akreditasi KAN', 'pdf' => $kanPdf],
                                ['icon' => 'bi-award', 'label' => 'ISO/IEC 17025', 'pdf' => $kanPdf],
                                ['icon' => 'bi-shield-check', 'label' => 'Izin Kemenkes', 'pdf' => $izinPdf],
                            ];
                        @endphp
                        @foreach ($legalBadges as $badge)
                            <div class="col-4">
                                <a href="{{ $badge['pdf'] }}" target="_blank" rel="noopener" class="about-mini-stat">
                                    <i class="bi {{ $badge['icon'] }}"></i>
                                    <span>{{ $badge['label'] }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('profil') }}" class="btn btn-outline-primary-custom">
                        Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================================
         4. KENAPA MEMILIH KAMI — fokus manfaat pengguna
         (Beda konteks dari "Keunggulan Institusi" di /profil)
    ========================================================== --}}
    <section class="why-us-section section-padding section-bg-blue">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow section-eyebrow-onblue">Manfaat Bagi Anda</span>
                <h2 class="section-title">Kenapa Memilih Kami</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Pengalaman menggunakan layanan kalibrasi yang kami jaga di setiap tahap.
                </p>
            </div>

            <div class="row g-4">
                @php
                    $whyUs = [
                        ['icon' => 'bi-person-badge', 'title' => 'Pelayanan Profesional', 'desc' => 'Ditangani tim yang ramah, komunikatif, dan mengutamakan kepuasan pelanggan.'],
                        ['icon' => 'bi-mortarboard', 'title' => 'Teknisi Berpengalaman', 'desc' => 'Teknisi kalibrasi bersertifikat dengan jam terbang tinggi di bidangnya.'],
                        ['icon' => 'bi-lightning-charge', 'title' => 'Proses Cepat & Tepat', 'desc' => 'Alur kerja efisien tanpa mengorbankan ketelitian hasil pengukuran.'],
                        ['icon' => 'bi-cash-coin', 'title' => 'Harga Transparan', 'desc' => 'Estimasi biaya jelas di awal, tanpa biaya tersembunyi.'],
                        ['icon' => 'bi-graph-up-arrow', 'title' => 'Monitoring Proses Online', 'desc' => 'Pantau status pengajuan kalibrasi Anda kapan saja lewat dashboard akun.'],
                        ['icon' => 'bi-chat-dots', 'title' => 'Konsultasi Mudah & Responsif', 'desc' => 'Tim kami siap membantu lewat WhatsApp maupun AI Assistant kami.'],
                    ];
                @endphp

                @foreach ($whyUs as $item)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="why-us-card">
                            <div class="why-us-icon">
                                <i class="bi {{ $item['icon'] }}"></i>
                            </div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{!! $item['desc'] !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         5. LAYANAN (preview) — pintu masuk ke /layanan
    ========================================================== --}}
    <section id="layanan-preview" class="services-section section-padding">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3" data-aos="fade-up">
                <div>
                    <span class="section-eyebrow"><i class="bi bi-grid-fill"></i> Layanan Kami</span>
                    <h2 class="section-title mb-0">Layanan Unggulan Kami</h2>
                    <p class="section-text mb-0 mt-2" style="max-width: 560px;">
                        Solusi lengkap untuk memastikan alat kesehatan Anda bekerja secara akurat dan aman.
                    </p>
                </div>
                <a href="{{ route('layanan') }}" class="btn btn-outline-primary-custom flex-shrink-0">
                    Lihat Semua Layanan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4 justify-content-center">
                @php
                    $services = [
                        [
                            'icon' => 'bi-sliders2',
                            'image' => 'layanan-kalibrasi-pengujian.jpg',
                            'title' => 'Kalibrasi & Pengujian Alat Kesehatan',
                            'desc' => 'Kalibrasi dan pengujian keamanan-performa alat kesehatan sesuai standar dan regulasi yang berlaku.',
                        ],
                        [
                            'icon' => 'bi-chat-square-dots',
                            'image' => 'layanan-konsultasi.jpg',
                            'title' => 'Konsultasi',
                            'desc' => 'Konsultasi teknis untuk meningkatkan kompetensi SDM kesehatan dalam pengelolaan alat.',
                        ],
                    ];
                @endphp

                @foreach ($services as $service)
                    <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="service-card">
                            <div class="service-card-image">
                                <img src="{{ asset('images/' . $service['image']) }}" width="700" height="480" alt="{{ $service['title'] }}">
                                <span class="service-card-icon"><i class="bi {{ $service['icon'] }}"></i></span>
                            </div>
                            <div class="service-card-body">
                                <h3>{!! $service['title'] !!}</h3>
                                <p>{{ $service['desc'] }}</p>
                                <a href="{{ route('layanan') }}" class="service-card-link">
                                    Selengkapnya <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         6. PROSES (preview) — 5 langkah ringkas, detail di /proses
    ========================================================== --}}
    <section id="proses-preview" class="process-section section-padding section-bg-green">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3" data-aos="fade-up">
                <div>
                    <span class="section-eyebrow">Alur Layanan</span>
                    <h2 class="section-title mb-0">Proses Kalibrasi</h2>
                    <p class="section-text mb-0 mt-2" style="max-width: 560px;">
                        Alur pelayanan kalibrasi kami, dari pengajuan hingga selesai.
                    </p>
                </div>
                <a href="{{ route('user.calibrations.index') }}" class="btn btn-outline-primary-custom flex-shrink-0">
                    Lihat Alur Lengkap <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="process-timeline process-timeline-branched" data-aos="fade-up" data-aos-delay="100">
                @php
                    $steps = [
                        ['icon' => 'bi-pencil-square', 'title' => 'Pengajuan', 'desc' => 'User mengirim formulir dan dokumen persyaratan.'],
                        ['icon' => 'bi-calendar-check', 'title' => 'Penjadwalan', 'desc' => 'Petugas mengonfirmasi jadwal pelayanan.'],
                        ['icon' => 'bi-tools', 'title' => 'Kalibrasi', 'desc' => 'Teknisi melakukan proses pengujian dan kalibrasi alat.'],
                        ['icon' => 'bi-patch-check', 'title' => 'Sertifikat', 'desc' => 'Sertifikat hasil kalibrasi diterbitkan dan siap diambil/diunduh.'],
                        ['icon' => 'bi-check-circle', 'title' => 'Selesai', 'desc' => 'Pengguna telah menerima sertifikat, seluruh layanan selesai.'],
                    ];
                @endphp

                <div class="process-track process-track-5">
                    @foreach ($steps as $step)
                        <div class="process-step" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="process-step-number">{{ sprintf('%02d', $loop->iteration) }}</div>
                            <div class="process-step-icon">
                                <i class="bi {{ $step['icon'] }}"></i>
                            </div>
                            <h3>{{ $step['title'] }}</h3>
                            <p>{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Cabang pengecualian — dari tahap Pengajuan, kalau dokumen
                     tidak lengkap / persyaratan tidak sesuai / dibatalkan. --}}
                <div class="process-branch">
                    <div class="process-branch-connector" aria-hidden="true"></div>
                    <div class="process-branch-node">
                        <div class="process-step-icon process-step-icon-cancelled">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="process-branch-text">
                            <h4>Cancelled</h4>
                            <p>Dari tahap Pengajuan — jika dokumen tidak lengkap, persyaratan tidak sesuai, atau pengajuan dibatalkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================================
         7. BERITA TERBARU (preview) — pintu masuk ke /berita
    ========================================================== --}}
    <section id="berita-preview" class="articles-section section-padding">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3" data-aos="fade-up">
                <div>
                    <span class="section-eyebrow">Wawasan</span>
                    <h2 class="section-title mb-0">Berita &amp; Informasi Terbaru</h2>
                    <p class="section-text mb-0 mt-2" style="max-width: 560px;">
                        Informasi seputar kegiatan, layanan, dan edukasi kesehatan.
                    </p>
                </div>
                <a href="{{ route('berita') }}" class="btn btn-outline-primary-custom flex-shrink-0">
                    Lihat Semua Berita <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @php
                    // Sumber data sama persis dengan halaman /berita (uptdBeritaData()
                    // di routes/web.php) — bukan data duplikat, supaya judul & gambar
                    // di preview ini selalu konsisten dengan halaman Berita sesungguhnya.
                    $previewArticles = array_slice(uptdBeritaData(), 0, 3, true);
                @endphp

                @foreach ($previewArticles as $slug => $article)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('berita.show', $slug) }}" class="article-card">
                            <div class="article-card-image">
                                @if ($article['gambar'])
                                    <img src="{{ $article['gambar'] }}" width="700" height="440" alt="{{ $article['judul'] }}" loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.add('article-card-fallback-show');">
                                @endif
                                <div class="article-card-fallback {{ $article['gambar'] ? '' : 'article-card-fallback-show' }}">
                                    <i class="bi {{ $article['icon'] }}"></i>
                                </div>
                                <span class="article-category">{{ $article['kategori'] }}</span>
                            </div>
                            <div class="article-card-body">
                                <span class="article-date">
                                    <i class="bi bi-clock-history"></i> {{ $article['tanggal']->diffForHumans() }}
                                </span>
                                <h3>{{ $article['judul'] }}</h3>
                                <span class="article-card-link">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         8. CHATBOT TEASER — perkenalan fitur AI Assistant
    ========================================================== --}}
    <section class="chatbot-teaser-section section-padding section-bg-blue">
        <div class="container">
            <div class="chatbot-teaser-card" data-aos="fade-up">
                <div class="row align-items-center g-4">
                    <div class="col-lg-2 text-center">
                        <div class="chatbot-teaser-icon">
                            <i class="bi bi-robot"></i>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <span class="section-eyebrow section-eyebrow-onblue">Fitur Unggulan</span>
                        <h2 class="section-title mb-2">AI Assistant UPTD Balai Pengujian dan Kalibrasi</h2>
                        <p class="section-text mb-3">
                            Punya pertanyaan seputar layanan kalibrasi? AI Assistant kami siap
                            membantu — mulai dari menjawab FAQ, memberi rekomendasi alat, hingga
                            memandu proses pengajuan kalibrasi Anda.
                        </p>
                        <ul class="chatbot-teaser-list">
                            <li><i class="bi bi-check-circle-fill"></i> Jawab pertanyaan seputar layanan & harga</li>
                            <li><i class="bi bi-check-circle-fill"></i> Rekomendasi jenis kalibrasi yang tepat</li>
                            <li><i class="bi bi-check-circle-fill"></i> Panduan langkah pengajuan kalibrasi</li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex flex-column gap-3">
                            <a href="https://api.whatsapp.com/send/?phone=6281292923438&text&type=phone_number&app_absent=0"
                               target="_blank" rel="noopener" class="btn btn-hero-primary w-100 justify-content-center">
                                <i class="bi bi-whatsapp me-1"></i> Mulai Chat
                            </a>
                            <a href="{{ route('chatbot') }}" class="btn btn-hero-outline w-100 justify-content-center">
                                Lihat Halaman Chatbot <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================================
         9. CTA PENUTUP
    ========================================================== --}}
    <section id="kontak" class="cta-section">
        <x-tapis-decoration corners="tr-bl" />
        <div class="container position-relative">
            <div class="cta-box" data-aos="zoom-in">
                <h2>Siap Mengajukan Kalibrasi?</h2>
                <p>Daftar atau login ke akun Anda untuk mengajukan permohonan kalibrasi alat kesehatan dan pantau prosesnya secara online.</p>
                <a href="{{ route('user.calibrations.create') }}" class="btn btn-cta">
                    Ajukan Kalibrasi Sekarang <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

@endsection
