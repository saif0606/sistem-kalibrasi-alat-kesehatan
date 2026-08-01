@extends('layouts.app')

@section('title', 'Profil — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    {{-- ==========================================================
         1. HERO PROFIL
    ========================================================== --}}
    <x-page-hero
        title="Profil UPTD Balai Pengujian dan Kalibrasi"
        current="Profil"
        subtitle="Mengenal lebih dekat UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung — sejarah, visi misi, legalitas, hingga layanan kami."
    />

    {{-- ==========================================================
         2. TENTANG UPTD BALAI PENGUJIAN DAN KALIBRASI
    ========================================================== --}}
    <section id="tentang-uptd" class="about-section section-padding">
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
                    <span class="section-eyebrow">Gambaran Umum</span>
                    <h2 class="section-title">Tentang UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan</h2>
                    <p class="section-text">
                        UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung adalah
                        Unit Pelaksana Teknis Daerah di bawah Dinas Kesehatan Provinsi Lampung,
                        pertama kali dengan nama UPTD IFKA yang dibentuk berdasarkan Peraturan
                        Gubernur Lampung Nomor 3 Tahun 2017 tentang Pembentukan, Organisasi dan
                        Tata Kerja Unit Pelaksana Teknis Dinas pada Dinas Daerah Provinsi Lampung,
                        serta dipertegas melalui Peraturan Gubernur Lampung Nomor 59 Tahun 2021
                        tentang Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Perangkat
                        Daerah.
                    </p>
                    <p class="section-text">
                        Kami bertugas menyelenggarakan pelayanan pengujian dan kalibrasi alat
                        kesehatan bagi seluruh fasilitas pelayanan kesehatan di wilayah Provinsi
                        Lampung, sekaligus menjalankan fungsi pemantapan dan pengendalian mutu guna
                        menjamin keakuratan, keamanan, dan keandalan alat kesehatan yang digunakan
                        oleh rumah sakit, puskesmas, klinik, dan fasilitas kesehatan lainnya, sesuai
                        standar dan regulasi yang berlaku.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================================
         3. SEJARAH SINGKAT (timeline)
         Data dummy — struktur siap diganti model Sejarah/Milestone.
    ========================================================== --}}
    <section id="sejarah" class="history-section section-padding section-bg-blue">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow section-eyebrow-onblue">Perjalanan Kami</span>
                <h2 class="section-title">Sejarah Singkat</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Tonggak perkembangan UPTD Balai Pengujian dan Kalibrasi dalam melayani
                    pengujian dan kalibrasi alat kesehatan di Provinsi Lampung.
                </p>
            </div>

            <div class="history-timeline">
                @php
                    $milestones = [
                        ['year' => '2017', 'title' => 'Sejarah Berdirinya UPTD', 'desc' => 'Dibentuk pada 1 Februari 2017 berdasarkan Peraturan Gubernur Lampung Nomor 3 Tahun 2017 tentang Pembentukan, Organisasi dan Tata Kerja Unit Pelaksana Teknis Dinas pada Dinas Daerah Provinsi Lampung.'],
                        ['year' => '2021', 'title' => 'Penguatan Tugas & Fungsi', 'desc' => 'Susunan organisasi, tugas, dan fungsi dipertegas melalui Peraturan Gubernur Lampung Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Perangkat Daerah.'],
                        ['year' => '2026', 'title' => 'Digitalisasi Layanan', 'desc' => 'Peluncuran Sistem Informasi Monitoring Kalibrasi berbasis web untuk memudahkan pengajuan dan pemantauan status kalibrasi secara online.'],
                    ];
                @endphp

                @foreach ($milestones as $milestone)
                    <div class="history-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                        <div class="history-year-marker">{{ $milestone['year'] }}</div>
                        <div class="history-content">
                            <h3>{{ $milestone['title'] }}</h3>
                            <p>{{ $milestone['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         4. VISI & MISI
    ========================================================== --}}
    <section id="visi-misi" class="vision-mission-section section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow">Arah &amp; Tujuan</span>
                <h2 class="section-title">Visi &amp; Misi</h2>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="vision-box">
                        <span class="vm-icon"><i class="bi bi-eye-fill"></i></span>
                        <h3>Visi</h3>
                        <p>
                            Menjadi institusi pelayanan pengujian dan kalibrasi alat kesehatan yang
                            berkualitas dan terpercaya menuju masyarakat sehat dan mandiri di
                            bidang kesehatan.
                        </p>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left">
                    <div class="mission-box">
                        <span class="vm-icon"><i class="bi bi-flag-fill"></i></span>
                        <h3>Misi</h3>
                        @php
                            $missions = [
                                'Menyediakan layanan pengujian dan kalibrasi alat kesehatan yang bermutu, terpercaya, dan inovatif.',
                                'Menyelenggarakan pemantapan dan pengendalian mutu pelayanan pengujian dan kalibrasi alat kesehatan secara berkelanjutan.',
                                'Meningkatkan dan mengembangkan sumber daya manusia yang profesional dan berkualitas.',
                                'Menjalin jejaring kerja dan kemitraan dengan fasilitas pelayanan kesehatan di seluruh Provinsi Lampung.',
                            ];
                        @endphp
                        <ul class="mission-list">
                            @foreach ($missions as $mission)
                                <li><i class="bi bi-check-circle-fill"></i> {{ $mission }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4" data-aos="fade-up">
                <div class="col-lg-9">
                    <div class="vision-box text-center">
                        <span class="vm-icon"><i class="bi bi-stars"></i></span>
                        <h3>Moto &amp; Janji Pelayanan</h3>
                        <p class="mb-2">
                            <strong>SIKOP</strong> — Sigap, Inovatif, Kooperatif, Objektif, Profesional.
                        </p>
                        <p class="mb-0">
                            Berkomitmen memberikan pelayanan terbaik dengan mengutamakan jaminan
                            mutu serta kecepatan hasil pemeriksaan sesuai dengan waktu yang telah
                            ditetapkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================================
         5. TUPOKSI (accordion)
    ========================================================== --}}
    <section id="tupoksi" class="tupoksi-section section-padding section-bg-green">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow">Landasan Kerja</span>
                <h2 class="section-title">Tugas Pokok &amp; Fungsi</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Tugas pokok dan fungsi UPTD Balai Pengujian dan Kalibrasi sesuai peraturan yang berlaku.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up" data-aos-delay="80">
                    <div class="accordion faq-accordion" id="tupoksiAccordion">
                        @php
                            $tupoksi = [
                                [
                                    'title' => 'Tugas Pokok',
                                    'desc' => 'UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan mempunyai tugas melaksanakan pengujian dan kalibrasi alat kesehatan sesuai standar dan ketentuan yang berlaku.',
                                ],
                                [
                                    'title' => 'Fungsi',
                                    'desc' => 'Untuk menyelenggarakan tugas tersebut, UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan mempunyai fungsi:',
                                    'list' => [
                                        'pengkoordinasian pelaksanaan tugas dan fungsi unsur organisasi;',
                                        'penyelenggaraan tugas dan fungsi pengujian dan kalibrasi alat kesehatan;',
                                        'penyelenggaraan pemantapan mutu pelayanan pengujian dan kalibrasi alat kesehatan;',
                                        'pembinaan, pengawasan dan pengendalian pelaksanaan tugas dan fungsi unsur organisasi; dan',
                                        'pelaksanaan urusan ketatausahaan.',
                                    ],
                                ],
                            ];
                        @endphp

                        @foreach ($tupoksi as $item)
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="tupoksiHeading{{ $loop->index }}">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#tupoksiCollapse{{ $loop->index }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="tupoksiCollapse{{ $loop->index }}">
                                        {{ $item['title'] }}
                                    </button>
                                </h3>
                                <div id="tupoksiCollapse{{ $loop->index }}"
                                     class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                     aria-labelledby="tupoksiHeading{{ $loop->index }}"
                                     data-bs-parent="#tupoksiAccordion">
                                    <div class="accordion-body">
                                        {{ $item['desc'] }}
                                        @isset($item['list'])
                                            <ul class="tupoksi-list mb-0 mt-2">
                                                @foreach ($item['list'] as $point)
                                                    <li>{{ $point }}</li>
                                                @endforeach
                                            </ul>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================================
         6. LEGALITAS & AKREDITASI
    ========================================================== --}}
    <section id="legalitas" class="legal-section section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow">Kredibilitas Kami</span>
                <h2 class="section-title">Legalitas &amp; Akreditasi</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Setiap hasil pengujian dan kalibrasi kami didukung legalitas resmi yang diakui
                    secara nasional.
                </p>
            </div>

            <div class="row g-4">
                @php
                    $legalItems = [
                        ['icon' => 'bi-patch-check-fill', 'title' => 'Terakreditasi KAN', 'desc' => 'Diakreditasi oleh Komite Akreditasi Nasional sebagai laboratorium kalibrasi yang kompeten.', 'pdf' => 'akreditasi-kan-iso17025.pdf'],
                        ['icon' => 'bi-award-fill', 'title' => 'ISO/IEC 17025', 'desc' => 'Menerapkan standar internasional untuk kompetensi laboratorium pengujian dan kalibrasi.', 'pdf' => 'akreditasi-kan-iso17025.pdf'],
                        ['icon' => 'bi-shield-fill-check', 'title' => 'Izin Operasional Kemenkes', 'desc' => 'Mengantongi izin operasional resmi dari Kementerian Kesehatan Republik Indonesia.', 'pdf' => 'izin-kemenkes.pdf'],
                    ];
                @endphp

                @foreach ($legalItems as $item)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <a href="{{ asset('assets/pdf/' . $item['pdf']) }}" target="_blank" rel="noopener" class="legal-card">
                            <div class="legal-card-icon">
                                <i class="bi {{ $item['icon'] }}"></i>
                            </div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['desc'] }}</p>
                            <span class="legal-card-link"><i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen PDF</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         7. DAFTAR PELAYANAN
         Data dummy — struktur siap diganti model Layanan.
    ========================================================== --}}
    <section id="daftar-pelayanan" class="service-list-section section-padding section-bg-blue">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow section-eyebrow-onblue">Apa yang Kami Kerjakan</span>
                <h2 class="section-title">Daftar Pelayanan</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Cakupan layanan utama yang kami sediakan bagi fasilitas kesehatan di Provinsi
                    Lampung.
                </p>
            </div>

            <div class="row g-4">
                @php
                    $profileServices = [
                        ['icon' => 'bi-sliders2', 'title' => 'Kalibrasi Alat Kesehatan', 'desc' => 'Kalibrasi berbagai jenis alat kesehatan sesuai standar dan prosedur yang ketat.'],
                        ['icon' => 'bi-clipboard2-pulse', 'title' => 'Pengujian Alat Kesehatan', 'desc' => 'Pengujian keamanan dan performa alat kesehatan sesuai regulasi yang berlaku.'],
                        ['icon' => 'bi-file-earmark-check', 'title' => 'Sertifikasi Hasil Kalibrasi', 'desc' => 'Penerbitan sertifikat resmi yang sah dan dapat dipertanggungjawabkan.'],
                        ['icon' => 'bi-chat-square-dots', 'title' => 'Konsultasi', 'desc' => 'Konsultasi teknis mengenai pengujian dan kalibrasi alat kesehatan sesuai kebutuhan fasilitas pelayanan kesehatan.'],
                        ['icon' => 'bi-graph-up-arrow', 'title' => 'Monitoring Berkala', 'desc' => 'Pemantauan jadwal kalibrasi ulang agar alat kesehatan selalu dalam kondisi laik pakai.'],
                        ['icon' => 'bi-truck', 'title' => 'Layanan Kunjungan Lapangan', 'desc' => 'Tim teknisi datang langsung ke fasilitas kesehatan untuk kalibrasi di lokasi.'],
                    ];
                @endphp

                @foreach ($profileServices as $service)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="service-list-card">
                            <span class="service-list-icon"><i class="bi {{ $service['icon'] }}"></i></span>
                            <div>
                                <h3>{!! $service['title'] !!}</h3>
                                <p>{!! $service['desc'] !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         8. MITRA RUMAH SAKIT / FASKES (logo wall)
         Data asli — nama fasyankes mitra dari Rekap Sertifikat Kalibrasi 2025.
    ========================================================== --}}
    <section id="mitra" class="partner-section section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow">Dipercaya Oleh</span>
                <h2 class="section-title">Rumah Sakit &amp; Fasilitas Kesehatan Mitra</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Sebagian fasilitas kesehatan yang telah menggunakan layanan pengujian dan
                    kalibrasi kami.
                </p>
            </div>

            <div class="partner-wall" data-aos="fade-up" data-aos-delay="80">
                @php
                    // Data asli — nama fasyankes mitra, diringkas dari Rekap Sertifikat
                    // Kalibrasi Tahun 2025 (nama instansi unik, alamat tidak disertakan).
                    $partnerNames = [
                        'Klinik Darussyifa', 'Klinik Pribadi', 'Pengadilan Tinggi', 'Klinik Bverz',
                        'Klinik Skin Rachel', 'Klinik Skin Rachel Way Halim',
                        'Klinik Passai Kelas I Bandar Lampung', 'Klinik Dindah Jaya',
                        'Klinik Mutiara Hati', 'Klinik Kasih', 'Klinik Niramaya Medical Center',
                        'Klinik Choiriah', 'UPT Puskesmas Tanjung Agung', 'Klinik Citra Serdang',
                        'Klinik Anugrah Medika', 'Klinik Mutiara Ibu', 'Klinik Bunda Tika',
                        'Klinik Medistra Raya', 'UPTD Puskesmas Hajimena', 'Klinik Jasmine Medika',
                        'UPT Puskesmas Rawat Inap Banjar Agung', 'UPTD Puskesmas Rawat Jalan Way Urang',
                        'UPT Puskesmas Way Kandis', 'Puskesmas Natar', 'UPTD Puskesmas Karang Anyar',
                        'UPTD Puskesmas Rawat Inap Sukadamai', 'Klinik Pratama PT. Gula Putih Mataram',
                        'BP Lanal Lampung', 'UPTD Puskesmas Pagar Dewa', 'Klinik Pratama Jasa Medika',
                        'Klinik Pratama Bunda Dara Medika', 'UPTD Puskesmas Panggung Jaya',
                        'Klinik Menggala Medical Center', 'Klinik Nasheha',
                        'Klinik Rawat Inap Bunda Asih Medika', 'Klinik Pratama Raffasya Sentra Medika',
                        'Klinik Pratama Rawat Inap ArRaudah Medika', 'Klinik Panaragan Jaya Medika',
                        'Klinik Pratama Loka Husada', 'Klinik Pratama An-Nur Husada',
                        'Klinik Pratama Rawat Inap ArRahman', 'Klinik Rawat Inap Asa Ibu Medika',
                        'Klinik Advent', 'Klinik Pratama Ardhito Medika',
                        'Klinik Pratama Rawat Inap AsShoffa', 'Klinik Pratama Rawa Jitu Medika',
                        'Klinik Pratama Rawat Inap Selagai Medika', 'UPT Puskesmas Pinang Jaya',
                        'Rumah Sakit Ibu dan Anak Santa Ana', 'Klinik Imam Bonjol',
                        'Klinik Rawat Inap Aditya Putri', 'Klinik Utama Rawat Inap Munyai Medical Center',
                        'Klinik Sri Agung Medika', 'Klinik Cahaya Sehat', 'Rumah Sakit Mutiara Bunda',
                        'Klinik Ngudi Waluyo', 'Klinik Pratama Sejahtera Medical Center',
                        'Rumah Sakit Bintang Medika', 'UPT Puskesmas Rawat Inap Permata Sukarame',
                        'UPT Puskesmas Palapa', 'Klinik Restu', 'Klinik Pratama Wede ArRachman',
                        'UPT Puskesmas Kebon Jahe', 'Klinik Pratama Indai Bunda', 'Klinik Utama Inara',
                        'UPT Puskesmas Sukabumi', 'UPT Puskesmas Korpri', 'Rumah Sakit Siti Khodijah',
                        'Klinik Pratama Nugraha Medika', 'Klinik Utama Rawat Inap 22 Medika',
                        'Klinik Rawat Inap Cendana', 'UPT Puskesmas Kupang Kota',
                        'Rumah Sakit Ibu dan Anak Restu Bunda', 'Klinik Lanud Pangeran M. Bun Yamin',
                        'Klinik Pratama Ella Lampung', 'Klinik Pratama BNN Provinsi Lampung',
                        'UPT Puskesmas Rawat Inap Way Laga',
                    ];

                    // Urutkan agar mitra paling representatif (Rumah Sakit &
                    // instansi pemerintah) tampil di halaman pertama, sisanya
                    // tetap mengikuti urutan asli data rekap.
                    $tierRS = array_values(array_filter($partnerNames, fn ($n) => str_contains($n, 'Rumah Sakit')));
                    $tierGov = array_values(array_filter($partnerNames, fn ($n) => in_array($n, ['Pengadilan Tinggi', 'BP Lanal Lampung'], true)));
                    $tierPuskesmas = array_values(array_filter($partnerNames, fn ($n) => str_contains($n, 'Puskesmas')));
                    $tierKlinik = array_values(array_filter($partnerNames, fn ($n) => str_contains($n, 'Klinik')));
                    $partnerNames = array_merge($tierRS, $tierGov, $tierPuskesmas, $tierKlinik);

                    // Ikon ditentukan otomatis dari jenis instansi — pola & variasi ikon
                    // yang sama seperti sebelumnya (RS, Puskesmas, Klinik, lainnya).
                    $partners = array_map(function ($name) {
                        $icon = 'bi-building-check';
                        if (str_contains($name, 'Rumah Sakit')) {
                            $icon = 'bi-hospital';
                        } elseif (str_contains($name, 'Puskesmas')) {
                            $icon = 'bi-heart-pulse';
                        } elseif (str_contains($name, 'Klinik')) {
                            $icon = 'bi-clipboard2-pulse';
                        }
                        return ['icon' => $icon, 'name' => $name];
                    }, $partnerNames);

                    // Pagination Laravel asli (LengthAwarePaginator) — 8 mitra/halaman,
                    // membaca query string ?page= seperti pagination Eloquent biasa.
                    // Saat ini sumbernya array statis, tapi API-nya identik dengan
                    // Model::paginate(8): begitu data mitra dipindah ke tabel database
                    // lewat Dashboard Admin, baris di atas tinggal diganti
                    // `Partner::orderBy(...)->paginate(8)` — blade & pagination link
                    // di bawah TIDAK perlu diubah sama sekali.
                    $perPage = 8;
                    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('page');
                    $partnersPage = new \Illuminate\Pagination\LengthAwarePaginator(
                        array_slice($partners, ($currentPage - 1) * $perPage, $perPage),
                        count($partners),
                        $perPage,
                        $currentPage,
                        [
                            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                            'pageName' => 'page',
                            'fragment' => 'mitra',
                        ]
                    );
                @endphp

                @forelse ($partnersPage as $partner)
                    <div class="partner-card">
                        <span class="partner-card-icon"><i class="bi {{ $partner['icon'] }}"></i></span>
                        <span>{{ $partner['name'] }}</span>
                    </div>
                @empty
                    <p class="section-text text-center mb-0">Belum ada data mitra.</p>
                @endforelse
            </div>

            @if ($partnersPage->hasPages())
                <div class="riw-pagination-wrap">
                    {{ $partnersPage->onEachSide(1)->links('partials.pagination-bootstrap') }}
                </div>
            @endif
        </div>
    </section>

    {{-- ==========================================================
         9. STRUKTUR ORGANISASI
         Data terbaru (2026) — bukan lagi gambar mentah, dibuat
         dalam bentuk card modern dengan garis penghubung.
    ========================================================== --}}
    <section id="struktur-organisasi" class="org-section section-padding section-bg-green">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow section-eyebrow-onblue">Susunan Kelembagaan</span>
                <h2 class="section-title">Struktur Organisasi</h2>
                <p class="section-text org-subtitle mx-auto" style="max-width: 640px;">
                    UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan<br>
                    Dinas Kesehatan Provinsi Lampung
                </p>
            </div>

            @php
                $kepalaUptd = ['name' => 'Media Lisna, S.Gz., M.Kes.', 'role' => 'Kepala UPTD Balai Pengujian dan Kalibrasi'];

                $branches = [
                    [
                        'title' => 'Sub Bagian Tata Usaha',
                        'head' => 'Indra Susanti, SE',
                        'members' => [
                            'Sahirin, S.Kep.',
                            'Ria Kusmira, S.Si., Apt.',
                            'Yulia Hajar Fitri, S.Farm., Apt.',
                            'Dita Fionita, S.IP.',
                            'Reza Octaviani, SE.',
                            'Arjun Fauji',
                            'Duwi Sriastuti',
                        ],
                    ],
                    [
                        'title' => 'Seksi Obat, Perbekkes &amp; Makmin',
                        'head' => 'Kurota Aini, S.Si., Apt.',
                        'members' => [
                            'Martina Navratilova, S.ST',
                            'Wahyu Kusuma Madiarti, S.Farm',
                            'Imron Rosyadi, S.Si., Apt.',
                            'Roni',
                            'Mey Lisa, A.Md.Keb',
                            'Saipul Anuar',
                        ],
                    ],
                    [
                        'title' => 'Seksi Kalibrasi Alat Kesehatan',
                        'head' => 'Apriyan Yasir, S.Kep., MM.',
                        'members' => [
                            'Eko Purwanto, S.KM',
                            'Meli Maulina Sari, S.KM., M.Kes',
                            'Dody Kurnaldi, A.Md',
                            'Dwi Laraspeny',
                            'Isnina, Amd.F',
                            'Rizal Fachlevi, SE',
                            'Edlin Sarasmita, S.P',
                            'Isidorus Amandi Triangga, Amd.Rad',
                        ],
                    ],
                ];

                $fungsional = [
                    'Yetri Darnas, S.Si., Apt., M.M',
                    'Yulita Lisaveria, S.Farm., Apt.',
                    'Doni Wiwit Cahyaningrum, S.ST',
                    'Apt. Sandy Yoga Ramadhan, S.Farm',
                    'Biyan Yudha Wibisono, A.Md',
                    'Komang Pande Neobagus Wibawa, S.Tr.T',
                    'I Wayan Davin Rama Iswara, S.Tr.T',
                    'Firman Meriyanto, A.Md.T',
                    'Shabrina Rahmantia, A.Md.T',
                    'Alfina Ratna Zahira, A.Md.T',
                    'Nurul Dwi Setyaningrum, A.Md.T',
                    'Sandi Bagus Sadewo, A.Md.T',
                    'Roby Setiapama, A.Md.Farm',
                    'Risa Oktalia Sari, A.Md.Farm',
                ];
            @endphp

            <div class="org-chart" data-aos="zoom-in">

                {{-- Kepala UPTD --}}
                <div class="org-card org-card-head">
                    <span class="org-card-role">{{ $kepalaUptd['role'] }}</span>
                    <span class="org-card-name">{{ $kepalaUptd['name'] }}</span>
                </div>

                <div class="org-connector-v"></div>

                {{-- 3 Branches: Sub Bagian TU, Seksi Obat, Seksi Kalibrasi --}}
                <div class="org-branches">
                    @foreach ($branches as $branch)
                        <div class="org-branch" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="org-card org-card-branch">
                                <span class="org-card-role">{{ $branch['title'] }}</span>
                                <span class="org-card-name">{{ $branch['head'] }}</span>
                            </div>
                            <ul class="org-member-list">
                                @foreach ($branch['members'] as $member)
                                    <li>{{ $member }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                {{-- Kelompok Jabatan Fungsional --}}
                <div class="org-connector-v"></div>
                <div class="org-card org-card-fungsional-title">
                    <span class="org-card-role">Kelompok Jabatan Fungsional</span>
                </div>
                <div class="org-fungsional-grid" data-aos="fade-up" data-aos-delay="100">
                    @foreach ($fungsional as $person)
                        <div class="org-fungsional-item">{{ $person }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ==========================================================
         10. CTA PENUTUP
    ========================================================== --}}
    <section class="cta-section">
        <div class="container">
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

