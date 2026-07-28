@extends('layouts.app')

@section('title', 'Riwayat Pengajuan — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // $riwayatList dikirim dari route (routes/web.php), bersumber dari
        // uptdPengajuanData() — sumber yang sama dipakai Dashboard & Status
        // Terakhir, jadi nomor pengajuan konsisten di semua halaman.

        $statusMeta = [
            'menunggu' => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'status-menunggu',
                'desc'  => 'Pengajuan sedang diperiksa oleh petugas.',
            ],
            'jadwal' => [
                'label' => 'Menunggu Jadwal',
                'class' => 'status-jadwal',
                'desc'  => 'Pengajuan terverifikasi, menunggu jadwal pelaksanaan.',
            ],
            'diproses' => [
                'label' => 'Sedang Diproses',
                'class' => 'status-diproses',
                'desc'  => 'Kalibrasi sedang dijadwalkan.',
            ],
            'selesai' => [
                'label' => 'Selesai',
                'class' => 'status-selesai',
                'desc'  => 'Pengajuan telah selesai dan sertifikat tersedia.',
            ],
            'ditolak' => [
                'label' => 'Ditolak',
                'class' => 'status-ditolak',
                'desc'  => 'Silakan lihat detail untuk informasi lebih lanjut.',
            ],
        ];

        // Ringkasan statistik dihitung dari data di atas — begitu diganti
        // query database, cukup ganti count() ini dengan agregasi Eloquent.
        $stats = [
            ['label' => 'Total Pengajuan', 'value' => count($riwayatList), 'icon' => 'bi-folder2-open', 'tone' => 'green'],
            ['label' => 'Sedang Diproses', 'value' => count(array_filter($riwayatList, fn($i) => $i['status'] === 'diproses')), 'icon' => 'bi-gear-wide-connected', 'tone' => 'blue'],
            ['label' => 'Selesai', 'value' => count(array_filter($riwayatList, fn($i) => $i['status'] === 'selesai')), 'icon' => 'bi-check-circle', 'tone' => 'green'],
            ['label' => 'Menunggu Verifikasi', 'value' => count(array_filter($riwayatList, fn($i) => $i['status'] === 'menunggu')), 'icon' => 'bi-hourglass-split', 'tone' => 'amber'],
        ];
    @endphp

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <section class="dashboard-hero">
        <x-tapis-decoration corners="tl-br" />
        <div class="container-xxl position-relative">
            <div data-aos="fade-up">
                <p class="dashboard-hero-eyebrow"><i class="bi bi-clock-history me-1"></i>Area Member</p>
                <h1 class="dashboard-hero-title">Riwayat Pengajuan</h1>
                <p class="dashboard-hero-subtitle">Lihat seluruh riwayat pengajuan kalibrasi beserta status prosesnya.</p>
            </div>
        </div>
    </section>

    {{-- ============================================================
         RINGKASAN STATISTIK
    ============================================================ --}}
    <section class="member-section pt-0">
        <div class="container-xxl">
            <div class="row g-3 g-lg-4">
                @foreach ($stats as $i => $stat)
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                        <div class="dash-stat-card">
                            <span class="dash-stat-icon dash-stat-icon-{{ $stat['tone'] }}">
                                <i class="bi {{ $stat['icon'] }}"></i>
                            </span>
                            <span class="dash-stat-number">{{ $stat['value'] }}</span>
                            <span class="dash-stat-label">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         SEARCH & FILTER
    ============================================================ --}}
    <section class="member-section pt-0 pb-0">
        <div class="container-xxl">
            <div class="riw-toolbar" data-aos="fade-up">
                <div class="tarif-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="riwSearch" placeholder="Cari Nomor Pengajuan...">
                </div>
                <select id="riwFilter" class="tarif-filter-select">
                    <option value="">Semua Status</option>
                    @foreach ($statusMeta as $key => $meta)
                        <option value="{{ $key }}">{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    {{-- ============================================================
         DAFTAR PENGAJUAN
    ============================================================ --}}
    <section class="member-section pt-0 pb-5">
        <div class="container-xxl">

            @if (count($riwayatList))
                <div class="riw-list" id="riwList">
                    @foreach ($riwayatList as $i => $item)
                        @php $meta = $statusMeta[$item['status']]; @endphp
                        <div class="riw-card" data-aos="fade-up" data-aos-delay="{{ min($i * 40, 200) }}"
                             data-kode="{{ strtolower($item['kode']) }}" data-status="{{ $item['status'] }}"
                             data-href="{{ route('proses', ['id' => $item['kode']]) }}#top" role="link" tabindex="0">

                            <div class="riw-card-top">
                                <div class="riw-card-kode">
                                    <span>{{ $item['kode'] }}</span>
                                    <button type="button" class="riw-copy-btn" data-copy="{{ $item['kode'] }}" aria-label="Salin nomor pengajuan">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <div class="riw-card-status">
                                    <span class="status-badge {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                                    <p class="riw-status-desc">{{ $meta['desc'] }}</p>
                                </div>
                            </div>

                            <div class="riw-card-divider"></div>

                            <div class="riw-card-meta">
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-hospital"></i> Nama Instansi</span>
                                    <strong>{{ $item['instansi'] }}</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-calendar3"></i> Tanggal Pengajuan</span>
                                    <strong>{{ $item['tanggal']->translatedFormat('d F Y') }}</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-arrow-repeat"></i> Update Terakhir</span>
                                    <strong>{{ $item['tanggal']->translatedFormat('d F Y') }} • {{ $item['tanggal']->format('H.i') }} WIB</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-clipboard2-check"></i> Jenis Layanan</span>
                                    <strong>Kalibrasi</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-tools"></i> Jumlah Alat</span>
                                    <strong>{{ $item['jumlah_alat'] }} Alat</strong>
                                </div>
                            </div>

                            <div class="riw-card-footer">
                                <a href="{{ route('proses', ['id' => $item['kode']]) }}#top" class="btn btn-hero-outline riw-card-btn">
                                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                @if ($item['status'] === 'selesai')
                                    <button type="button" class="btn btn-hero-primary riw-card-btn riw-download-btn" data-kode="{{ $item['kode'] }}">
                                        <i class="bi bi-download me-1"></i> Download Sertifikat
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Empty state hasil pencarian/filter (disembunyikan secara default) --}}
                <div class="tarif-empty d-none" id="riwFilterEmpty">
                    <i class="bi bi-search d-block mb-2" style="font-size:1.75rem;"></i>
                    Tidak ada pengajuan yang cocok dengan pencarian/filter Anda.
                </div>

                {{-- ========================================================
                     PAGINATION (dummy)
                ======================================================== --}}
                <nav class="riw-pagination-wrap" aria-label="Navigasi halaman riwayat pengajuan">
                    <ul class="pagination riw-pagination">
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                        </li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            @else
                {{-- ========================================================
                     EMPTY STATE — belum pernah mengajukan sama sekali
                ======================================================== --}}
                <div class="riw-empty-global" data-aos="fade-up">
                    <span class="riw-empty-icon"><i class="bi bi-inbox"></i></span>
                    <h2>Belum Ada Pengajuan</h2>
                    <p>Anda belum pernah mengajukan layanan kalibrasi.</p>
                    <a href="{{ route('dashboard.pengajuan') }}" class="btn btn-hero-primary">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Kalibrasi
                    </a>
                </div>
            @endif

        </div>
    </section>

@endsection
