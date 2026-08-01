@extends('layouts.app')

@section('title', 'Layanan — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    {{-- ==========================================================
         DATA DUMMY — siap diganti Model::all() dari controller.
         Kategori & tarif berdasarkan brosur resmi Tarif Layanan Jasa
         Kalibrasi UPTD Balai Pengujian dan Kalibrasi Tahun 2025.
    ========================================================== --}}
    @php
        $services = $services->sortByDesc('created_at')->values();

        $oldEquipments = [
            ['name' => 'Sphygmomanometer / Tensimeter', 'category' => 'Diagnostik', 'price' => 84000, 'desc' => 'Alat pengukur tekanan darah non-invasif untuk pemeriksaan rutin pasien.', 'gambar' => 'katalog/tensimeter.jpg', 'icon' => 'bi-clipboard2-pulse', 'color' => 'green'],
            ['name' => 'Pulse Oximetri (SPO2 Monitor)', 'category' => 'Monitoring', 'price' => 180000, 'desc' => 'Alat pemantau saturasi oksigen dan denyut nadi pasien.', 'gambar' => 'katalog/spo2.jpg', 'icon' => 'bi-activity', 'color' => 'blue'],
            ['name' => 'Nebulizer', 'category' => 'Respiratory', 'price' => 228000, 'desc' => 'Alat terapi uap untuk pengobatan saluran pernapasan.', 'gambar' => 'katalog/nebulizer.jpg', 'icon' => 'bi-lungs', 'color' => 'blue'],
            ['name' => 'Blood Pressure Monitor (BPM) / Non Invasive', 'category' => 'Monitoring', 'price' => 162000, 'desc' => 'Monitor tekanan darah non-invasif untuk pemantauan berkelanjutan.', 'gambar' => 'katalog/bpm.jpg', 'icon' => 'bi-activity', 'color' => 'blue'],
            ['name' => 'Regulator Oksigen (Flow Meter)', 'category' => 'Respiratory', 'price' => 192000, 'desc' => 'Alat pengatur aliran oksigen medis ke pasien.', 'gambar' => 'katalog/regulator-oksigen.jpg', 'icon' => 'bi-lungs', 'color' => 'blue'],
            ['name' => 'Timbangan Bayi', 'category' => 'Neonatal', 'price' => 180000, 'desc' => 'Timbangan digital/analog untuk pemantauan berat badan bayi.', 'gambar' => 'katalog/timbangan-bayi.jpg', 'icon' => 'bi-emoji-smile', 'color' => 'green'],
            ['name' => 'Fetal Detector / Doppler', 'category' => 'Diagnostik', 'price' => 156000, 'desc' => 'Alat pendeteksi detak jantung janin.', 'gambar' => 'katalog/fetal-doppler.jpg', 'icon' => 'bi-clipboard2-pulse', 'color' => 'green'],
            ['name' => 'Suction Pump / Alat Hisap Medik', 'category' => 'Terapi', 'price' => 264000, 'desc' => 'Alat penghisap cairan/lendir untuk tindakan medis.', 'gambar' => 'katalog/suction-pump.jpg', 'icon' => 'bi-heart-pulse', 'color' => 'blue'],
            ['name' => 'ECG (Electrocardiograph) Monitor', 'category' => 'Diagnostik', 'price' => 168000, 'desc' => 'Alat perekam aktivitas listrik jantung.', 'gambar' => 'katalog/ecg.jpg', 'icon' => 'bi-clipboard2-pulse', 'color' => 'green'],
            ['name' => 'Centrifuge', 'category' => 'Laboratorium', 'price' => 240000, 'desc' => 'Alat pemutar sampel laboratorium untuk pemisahan cairan.', 'gambar' => 'katalog/centrifuge.jpg', 'icon' => 'bi-droplet-half', 'color' => 'green'],
            ['name' => 'Ultrasonography (USG)', 'category' => 'Diagnostik', 'price' => 300000, 'desc' => 'Alat pencitraan diagnostik menggunakan gelombang ultrasonik.', 'gambar' => 'katalog/usg.jpg', 'icon' => 'bi-clipboard2-pulse', 'color' => 'green'],
            ['name' => 'Autoclave', 'category' => 'Sterilisasi', 'price' => 312000, 'desc' => 'Alat sterilisasi peralatan medis menggunakan uap bertekanan tinggi.', 'gambar' => 'katalog/autoclave.jpg', 'icon' => 'bi-shield-check', 'color' => 'blue'],
            ['name' => 'Infant Warmer', 'category' => 'Neonatal', 'price' => 240000, 'desc' => 'Alat penghangat bayi untuk menjaga suhu tubuh optimal.', 'gambar' => 'katalog/infant-warmer.jpg', 'icon' => 'bi-emoji-smile', 'color' => 'green'],
            ['name' => 'Inkubator Perawatan', 'category' => 'Neonatal', 'price' => 324000, 'desc' => 'Alat perawatan bayi dengan kontrol suhu dan kelembapan.', 'gambar' => 'katalog/inkubator.jpg', 'icon' => 'bi-emoji-smile', 'color' => 'green'],
            ['name' => 'Monitor Pasien (Bed Side Monitor)', 'category' => 'Monitoring', 'price' => 588000, 'desc' => 'Monitor multiparameter untuk memantau kondisi vital pasien.', 'gambar' => 'katalog/bedside-monitor.jpg', 'icon' => 'bi-activity', 'color' => 'blue'],
            ['name' => 'Sterilisator Kering', 'category' => 'Sterilisasi', 'price' => 204000, 'desc' => 'Alat sterilisasi menggunakan panas kering.', 'gambar' => 'katalog/sterilisator-kering.jpg', 'icon' => 'bi-shield-check', 'color' => 'blue'],
            ['name' => 'Phototherapy Unit / Blue Light', 'category' => 'Neonatal', 'price' => 204000, 'desc' => 'Alat terapi sinar untuk penanganan bayi kuning (ikterus).', 'gambar' => 'katalog/phototherapy.jpg', 'icon' => 'bi-emoji-smile', 'color' => 'green'],
            ['name' => 'Lampu Operasi', 'category' => 'Terapi', 'price' => 192000, 'desc' => 'Lampu penerangan khusus untuk ruang tindakan/operasi.', 'gambar' => 'katalog/lampu-operasi.jpg', 'icon' => 'bi-heart-pulse', 'color' => 'blue'],
            ['name' => 'Cardiotocograph (CTG)', 'category' => 'Diagnostik', 'price' => 168000, 'desc' => 'Alat pemantau detak jantung janin dan kontraksi rahim.', 'gambar' => 'katalog/ctg.jpg', 'icon' => 'bi-clipboard2-pulse', 'color' => 'green'],
            ['name' => 'Defibrillator Monitor', 'category' => 'Terapi', 'price' => 300000, 'desc' => 'Alat kejut jantung untuk penanganan kondisi gawat darurat.', 'gambar' => 'katalog/defibrillator.jpg', 'icon' => 'bi-heart-pulse', 'color' => 'blue'],
            ['name' => 'Infusion Pump', 'category' => 'Terapi', 'price' => 288000, 'desc' => 'Alat pengatur laju cairan infus secara presisi.', 'gambar' => 'katalog/infusion-pump.jpg', 'icon' => 'bi-heart-pulse', 'color' => 'blue'],
            ['name' => 'Syringe Pump', 'category' => 'Terapi', 'price' => 288000, 'desc' => 'Alat pengatur pemberian obat/cairan dosis kecil secara presisi.', 'gambar' => 'katalog/syringe-pump.jpg', 'icon' => 'bi-heart-pulse', 'color' => 'blue'],
            ['name' => 'Spirometer', 'category' => 'Respiratory', 'price' => 156000, 'desc' => 'Alat pengukur kapasitas dan fungsi paru-paru.', 'gambar' => 'katalog/spirometer.jpg', 'icon' => 'bi-lungs', 'color' => 'blue'],
            ['name' => 'Continuous Positive Airway Pressure (CPAP)', 'category' => 'Respiratory', 'price' => 396000, 'desc' => 'Alat bantu napas tekanan positif berkelanjutan.', 'gambar' => 'katalog/cpap.jpg', 'icon' => 'bi-lungs', 'color' => 'blue'],
        ];

        foreach ($oldEquipments as $i => $eq) {
            $oldEquipments[$i]['gambar'] = isset($eq['gambar']) ? asset('images/' . $eq['gambar']) : null;
        }

        $newServices = $services->map(function ($service) {
            return [
                'name' => $service->name,
                'category' => 'Layanan',
                'price' => $service->price,
                'desc' => $service->description ?: '-',
                'gambar' => $service->image ? asset('storage/' . $service->image) : null,
                'icon' => 'bi bi-tools',
                'color' => 'blue',
            ];
        })->all();

        $equipments = array_merge($oldEquipments, $newServices);
    @endphp

    {{-- ==========================================================
         1. HERO
    ========================================================== --}}
    <x-page-hero
        title="Layanan Pengujian & Kalibrasi Alat Kesehatan"
        current="Layanan"
        subtitle="UPTD Balai Pengujian dan Kalibrasi menyediakan layanan pengujian dan kalibrasi alat kesehatan yang profesional, akurat, terpercaya, dan sesuai standar nasional maupun internasional."
        tapis="tl-br"
    />
    <div class="layanan-hero-icons container" data-aos="fade-up" data-aos-delay="120">
        <span><i class="bi bi-heart-pulse"></i></span>
        <span><i class="bi bi-clipboard2-pulse"></i></span>
        <span><i class="bi bi-shield-check"></i></span>
        <span><i class="bi bi-activity"></i></span>
    </div>

    {{-- ==========================================================
         2. JENIS LAYANAN — 3 card besar
    ========================================================== --}}
    <section id="jenis-layanan" class="services-section section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow"><i class="bi bi-grid-fill"></i> Layanan Kami</span>
                <h2 class="section-title">Jenis Layanan</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Dua layanan utama yang kami sediakan bagi fasilitas kesehatan di Provinsi Lampung.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                @php
                    $jenisLayanan = [
                        ['icon' => 'bi-sliders2', 'image' => 'layanan-kalibrasi-pengujian.jpg', 'title' => 'Kalibrasi & Pengujian Alat Kesehatan', 'desc' => 'Kalibrasi dan pengujian keamanan-performa alat kesehatan sesuai standar dan regulasi yang berlaku.'],
                        ['icon' => 'bi-chat-square-dots', 'image' => 'layanan-konsultasi.jpg', 'title' => 'Konsultasi', 'desc' => 'Konsultasi teknis untuk meningkatkan kompetensi SDM kesehatan dalam pengelolaan alat.'],
                    ];
                @endphp

                @foreach ($jenisLayanan as $item)
                    <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="service-card">
                            <div class="service-card-image">
                                <img src="{{ asset('images/' . $item['image']) }}" width="700" height="480" alt="{{ $item['title'] }}">
                                <span class="service-card-icon"><i class="bi {{ $item['icon'] }}"></i></span>
                            </div>
                            <div class="service-card-body">
                                <h3>{!! $item['title'] !!}</h3>
                                <p>{!! $item['desc'] !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================================
         3. ALUR PELAYANAN
    ========================================================== --}}
    <section id="alur-pelayanan" class="process-section section-padding section-bg-green">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow">Alur Layanan</span>
                <h2 class="section-title">Alur Pelayanan</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Alur pelayanan kalibrasi kami, dari pengajuan hingga selesai.
                </p>
            </div>

            <div class="process-timeline process-timeline-branched" data-aos="fade-up" data-aos-delay="100">
                @php
                    $steps = [
                        ['icon' => 'bi-pencil-square', 'title' => 'Pengajuan', 'desc' => 'Ajukan permohonan kalibrasi lewat akun Anda.'],
                        ['icon' => 'bi-calendar-check', 'title' => 'Penjadwalan', 'desc' => 'Jadwal pelaksanaan kalibrasi dikonfirmasi ke Anda.'],
                        ['icon' => 'bi-tools', 'title' => 'Kalibrasi/Pengujian', 'desc' => 'Pengukuran dilakukan teknisi dengan alat standar.'],
                        ['icon' => 'bi-patch-check', 'title' => 'Sertifikat', 'desc' => 'Sertifikat resmi diterbitkan dan dapat diunduh.'],
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
         4. DAFTAR TARIF — searchable & filterable table
         Berdasarkan Perda Provinsi Lampung No. 4 Tahun 2024
    ========================================================== --}}
    <section id="daftar-tarif" class="tarif-section section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow"><i class="bi bi-receipt"></i> Tarif Resmi</span>
                <h2 class="section-title">Daftar Tarif Layanan Kalibrasi</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Tarif berdasarkan Peraturan Daerah Provinsi Lampung Nomor 4 Tahun 2024, belum termasuk biaya akomodasi tenaga teknis.
                </p>
            </div>

            <div class="tarif-toolbar" data-aos="fade-up">
                <div class="tarif-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="tarifSearch" placeholder="Cari nama alat...">
                </div>
            </div>

            <div class="tarif-table-wrapper" data-aos="fade-up" data-aos-delay="80">
                <table class="tarif-table tarif-table-simple" id="tarifTable">
                    <thead>
                        <tr>
                            <th>Nama Alat</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipments as $item)
                            <tr data-name="{{ strtolower($item['name']) }}" data-category="{{ $item['category'] }}">
                                <td>{{ $item['name'] }}</td>
                                <td class="tarif-price">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Belum ada layanan yang ditampilkan. Silakan tambahkan layanan melalui admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <p id="tarifEmptyState" class="tarif-empty d-none">
                    <i class="bi bi-inbox"></i> Tidak ada alat yang cocok dengan pencarian.
                </p>
            </div>
        </div>
    </section>

    {{-- ==========================================================
         5. KATALOG ALAT — grid card + modal detail
    ========================================================== --}}
    <section id="katalog-alat" class="katalog-section section-padding section-bg-blue">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-eyebrow section-eyebrow-onblue"><i class="bi bi-grid-3x3-gap-fill"></i> Katalog</span>
                <h2 class="section-title">Katalog Alat Kesehatan</h2>
                <p class="section-text mx-auto" style="max-width: 640px;">
                    Seluruh jenis alat kesehatan yang dapat diajukan untuk pengujian dan kalibrasi di UPTD Balai Pengujian dan Kalibrasi.
                </p>
            </div>

            <div class="row g-4">
                @forelse ($equipments as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 60 }}">
                        <div class="katalog-card">
                            <div class="katalog-card-visual katalog-visual-{{ $item['color'] ?? 'blue' }}">
                                @if (!empty($item['gambar']))
                                    <img src="{{ $item['gambar'] }}" alt="{{ $item['name'] }}" loading="lazy">
                                @else
                                    <i class="bi {{ $item['icon'] ?? 'bi-tools' }}"></i>
                                @endif
                            </div>
                            <div class="katalog-card-body">
                                <span class="tarif-category-badge tarif-badge-{{ $item['color'] ?? 'blue' }}">{{ $item['category'] }}</span>
                                <h3>{{ $item['name'] }}</h3>
                                <p class="section-text">{{ $item['desc'] ?? '-' }}</p>
                                <div class="katalog-card-footer">
                                    <span class="katalog-price">Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                                    <span class="tarif-status-badge">Tersedia</span>
                                </div>
                                <button type="button" class="btn btn-outline-primary-custom w-100 mt-3 katalog-detail-btn"
                                        data-bs-toggle="modal" data-bs-target="#katalogDetailModal"
                                        data-name="{{ $item['name'] }}"
                                        data-category="{{ $item['category'] }}"
                                        data-icon="{{ $item['icon'] ?? 'bi bi-tools' }}"
                                        data-color="{{ $item['color'] ?? 'blue' }}"
                                        data-gambar="{{ $item['gambar'] ?? '' }}"
                                        data-price="Rp{{ number_format($item['price'], 0, ',', '.') }}"
                                        data-desc="{{ $item['desc'] ?? '-' }}">
                                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="riw-empty-global" data-aos="fade-up">
                            <span class="riw-empty-icon"><i class="bi bi-inbox"></i></span>
                            <h2>Belum ada layanan</h2>
                            <p>Silakan tambahkan layanan melalui panel admin agar ditampilkan di halaman ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Modal Detail (tunggal, diisi ulang via JS setiap kali tombol diklik) --}}
    <div class="modal fade katalog-modal" id="katalogDetailModal" tabindex="-1" aria-labelledby="katalogDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="katalogDetailModalLabel">Detail Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="katalog-modal-visual" id="katalogModalVisual">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <span class="tarif-category-badge" id="katalogModalCategory">Kategori</span>
                    <h4 id="katalogModalName" class="mt-2 mb-3">Nama Alat</h4>
                    <p class="section-text mb-3" id="katalogModalDesc">Deskripsi alat.</p>
                    <div class="katalog-modal-info">
                        <div>
                            <span>Tarif</span>
                            <strong id="katalogModalPrice">Rp0</strong>
                        </div>
                    </div>
                    <div class="katalog-modal-syarat">
                        <h6><i class="bi bi-list-check me-1"></i> Syarat</h6>
                        <p id="katalogModalSyarat">-</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('user.calibrations.create') }}" class="btn btn-hero-primary w-100 justify-content-center">
                        Ajukan Kalibrasi <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================================
         6. CTA PENUTUP
         Catatan: karena sistem login/auth belum diimplementasikan,
         tombol untuk sementara selalu mengarah ke /login. Nanti tinggal
         tambah pengecekan auth()->check() untuk arahkan ke Form Pengajuan.
    ========================================================== --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-box" data-aos="zoom-in">
                <h2>Siap Melakukan Kalibrasi?</h2>
                <p>Ajukan permohonan kalibrasi alat kesehatan Anda sekarang dan pantau prosesnya secara online.</p>
                <a href="{{ route('user.calibrations.create') }}" class="btn btn-cta">
                    Ajukan Kalibrasi <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

@endsection
