@extends('layouts.app')

@section('title', 'Riwayat Pengajuan — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // $riwayatList dikirim dari route (routes/web.php), bersumber langsung
        // dari tabel calibration_requests milik user yang sedang login.

        $statusMeta = [
            'Pengajuan'   => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'status-menunggu',
                'desc'  => 'Pengajuan sedang diperiksa oleh petugas.',
            ],
            'Penjadwalan' => [
                'label' => 'Menunggu Jadwal',
                'class' => 'status-jadwal',
                'desc'  => 'Pengajuan terverifikasi, menunggu jadwal pelaksanaan.',
            ],
            'Kalibrasi'   => [
                'label' => 'Sedang Diproses',
                'class' => 'status-diproses',
                'desc'  => 'Kalibrasi sedang dijadwalkan.',
            ],
            'Selesai'     => [
                'label' => 'Selesai',
                'class' => 'status-selesai',
                'desc'  => 'Pengajuan telah selesai dan sertifikat tersedia.',
            ],
            'Ditolak'     => [
                'label' => 'Ditolak',
                'class' => 'status-ditolak',
                'desc'  => 'Silakan lihat detail untuk informasi lebih lanjut.',
            ],
        ];

        $stats = [
            ['label' => 'Total Pengajuan', 'value' => $totalCount, 'icon' => 'bi-folder2-open', 'tone' => 'green'],
            ['label' => 'Sedang Diproses', 'value' => $statusCounts['Kalibrasi'] ?? 0, 'icon' => 'bi-gear-wide-connected', 'tone' => 'blue'],
            ['label' => 'Selesai', 'value' => $statusCounts['Selesai'] ?? 0, 'icon' => 'bi-check-circle', 'tone' => 'green'],
            ['label' => 'Menunggu Verifikasi', 'value' => $statusCounts['Pengajuan'] ?? 0, 'icon' => 'bi-hourglass-split', 'tone' => 'amber'],
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
                        @php $meta = $statusMeta[$item->status] ?? ['label' => ucwords($item->status), 'class' => 'status-menunggu', 'desc' => 'Sedang diproses.']; @endphp
                        <div class="riw-card" data-aos="fade-up" data-aos-delay="{{ min($i * 40, 200) }}"
                             data-kode="{{ strtolower($item->registration_number) }}" data-status="{{ $item->status }}"
                             data-href="{{ route('user.calibrations.show', $item->id) }}#top" role="link" tabindex="0">

                            <div class="riw-card-top">
                                <div class="riw-card-kode">
                                    <span>{{ $item->registration_number }}</span>
                                    <button type="button" class="riw-copy-btn" data-copy="{{ $item->registration_number }}" aria-label="Salin nomor pengajuan">
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
                                    <strong>{{ $item->nama_instansi }}</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-calendar3"></i> Tanggal Pengajuan</span>
                                    <strong>{{ ($item->request_date ?? $item->created_at)->translatedFormat('d F Y') }}</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-arrow-repeat"></i> Update Terakhir</span>
                                    <strong>{{ $item->updated_at->translatedFormat('d F Y') }} • {{ $item->updated_at->format('H.i') }} WIB</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-clipboard2-check"></i> Jenis Layanan</span>
                                    <strong>Kalibrasi</strong>
                                </div>
                                @php
                                    $daftarAlat = $item->daftar_alat;
                                    if (is_string($daftarAlat)) {
                                        $daftarAlat = json_decode($daftarAlat, true);
                                    }
                                @endphp
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-tools"></i> Jumlah Alat</span>
                                    <strong>{{ count(is_countable($daftarAlat) ? $daftarAlat : []) }} Alat</strong>
                                </div>
                            </div>

                            <div class="riw-card-footer">
                                <a href="{{ route('user.calibrations.show', $item->id) }}#top" class="btn btn-hero-outline riw-card-btn">
                                    Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                @if ($item->status === 'Selesai')
                                    <button type="button" class="btn btn-hero-primary riw-card-btn riw-download-btn" data-kode="{{ $item->registration_number }}">
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

                <div class="mt-4">{{ $riwayatList->links('pagination::bootstrap-5') }}</div>
            @else
                {{-- ========================================================
                     EMPTY STATE — belum pernah mengajukan sama sekali
                ======================================================== --}}
                <div class="riw-empty-global" data-aos="fade-up">
                    <span class="riw-empty-icon"><i class="bi bi-inbox"></i></span>
                    <h2>Belum Ada Pengajuan</h2>
                    <p>Anda belum pernah mengajukan layanan kalibrasi.</p>
                    <a href="{{ route('user.calibrations.create') }}" class="btn btn-hero-primary">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Kalibrasi
                    </a>
                </div>
            @endif

        </div>
    </section>

@endsection
