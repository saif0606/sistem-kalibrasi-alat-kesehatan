@extends('layouts.app')

@section('title', 'Dashboard — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // $memberUser, $riwayatTerbaru & $statusTerakhir dikirim dari route
        // (routes/web.php) — bersumber dari user yang sedang login +
        // uptdPengajuanData(), bukan lagi data dummy lokal di file ini.

        // ==================================================================
        // DATA DUMMY — angka ringkasan di bawah ini nantinya diganti
        // query ke Model (Pengajuan::where('user_id', ...), dst).
        // ==================================================================

        $stats = [
            ['label' => 'Pengajuan Aktif', 'value' => 4, 'icon' => 'bi-folder2-open', 'tone' => 'green'],
            ['label' => 'Menunggu Verifikasi', 'value' => 2, 'icon' => 'bi-hourglass-split', 'tone' => 'amber'],
            ['label' => 'Sedang Diproses', 'value' => 3, 'icon' => 'bi-gear-wide-connected', 'tone' => 'blue'],
            ['label' => 'Selesai', 'value' => 8, 'icon' => 'bi-check-circle', 'tone' => 'green'],
        ];

        $statusMeta = [
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'class' => 'status-menunggu'],
            'jadwal'   => ['label' => 'Menunggu Jadwal', 'class' => 'status-jadwal'],
            'diproses' => ['label' => 'Diproses', 'class' => 'status-diproses'],
            'selesai'  => ['label' => 'Selesai', 'class' => 'status-selesai'],
            'ditolak'  => ['label' => 'Ditolak', 'class' => 'status-ditolak'],
        ];

        $statusProgress = [
            'menunggu' => 15,
            'jadwal' => 40,
            'diproses' => 65,
            'selesai' => 100,
            'ditolak' => 100,
        ];

        $informasiTerbaru = [
            [
                'icon' => 'bi-calendar-check',
                'title' => 'Jadwal Pelayanan',
                'desc' => 'Layanan buka Senin–Jumat, 08.00–16.00 WIB. Libur nasional tetap tutup.',
            ],
            [
                'icon' => 'bi-megaphone',
                'title' => 'Pengumuman',
                'desc' => 'Mulai Agustus 2026, pengajuan kalibrasi wajib melampirkan foto alat.',
            ],
            [
                'icon' => 'bi-info-circle',
                'title' => 'Informasi Kalibrasi',
                'desc' => 'Estimasi proses kalibrasi 3–7 hari kerja tergantung jenis alat.',
            ],
        ];
    @endphp

    {{-- ============================================================
         1. GREETING
    ============================================================ --}}
    <section class="dashboard-hero">
        <x-tapis-decoration corners="tl-br" />
        <div class="container-xxl position-relative">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div data-aos="fade-up">
                    <p class="dashboard-hero-eyebrow"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}</p>
                    <h1 class="dashboard-hero-title">Halo, {{ explode(' ', $memberUser['name'])[0] }} 👋</h1>
                    <p class="dashboard-hero-subtitle">Selamat datang kembali di Sistem Informasi Monitoring Kalibrasi UPTD Balai Pengujian dan Kalibrasi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. RINGKASAN STATISTIK
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
         3. QUICK ACTION — Ajukan Kalibrasi Baru
    ============================================================ --}}
    <section class="member-section pt-0 pb-0">
        <div class="container-xxl">
            <div class="dash-quick-action" data-aos="fade-up">
                <div class="dash-quick-action-icon">
                    <i class="bi bi-file-earmark-plus"></i>
                </div>
                <div class="dash-quick-action-body">
                    <h2>Ajukan Kalibrasi Baru</h2>
                    <p>Anda dapat mengajukan kalibrasi alat kesehatan secara online — cukup isi data alat dan instansi, tim kami akan memverifikasi dalam 1x24 jam kerja.</p>
                </div>
                <a href="{{ route('dashboard.pengajuan') }}" class="btn btn-hero-primary dash-quick-action-btn">
                    <i class="bi bi-plus-circle me-1"></i> Ajukan Kalibrasi
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         AKSES CEPAT — shortcut ke fungsi utama Member Area
    ============================================================ --}}
    @php
        $shortcuts = [
            ['route' => 'dashboard.pengajuan', 'icon' => 'bi-file-earmark-plus', 'label' => 'Ajukan Kalibrasi'],
            ['route' => 'dashboard.riwayat', 'icon' => 'bi-clock-history', 'label' => 'Riwayat Pengajuan'],
            ['route' => 'proses', 'icon' => 'bi-diagram-3', 'label' => 'Proses'],
            ['route' => 'profil', 'icon' => 'bi-bank', 'label' => 'Profil UPTD'],
        ];
    @endphp
    <section class="member-section">
        <div class="container-xxl">
            <div class="row g-3">
                @foreach ($shortcuts as $i => $shortcut)
                    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                        <a href="{{ route($shortcut['route']) }}" class="dash-shortcut-card">
                            <span class="dash-shortcut-icon"><i class="bi {{ $shortcut['icon'] }}"></i></span>
                            <span class="dash-shortcut-label">{{ $shortcut['label'] }}</span>
                            <i class="bi bi-arrow-right dash-shortcut-arrow"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         4. RIWAYAT PENGAJUAN TERBARU
    ============================================================ --}}
    <section class="member-section pt-0">
        <div class="container-xxl">
            <div class="dash-panel" data-aos="fade-up">
                <div class="dash-panel-head">
                    <h2>Riwayat Pengajuan Terbaru</h2>
                    <a href="{{ route('dashboard.riwayat') }}" class="dash-panel-link">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @if (count($riwayatTerbaru))
                    <div class="tarif-table-wrapper dash-table-wrapper">
                        <table class="tarif-table">
                            <thead>
                                <tr>
                                    <th>Nomor Pengajuan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayatTerbaru as $item)
                                    @php $meta = $statusMeta[$item['status']]; @endphp
                                    <tr>
                                        <td class="dash-table-code">{{ $item['kode'] }}</td>
                                        <td>{{ $item['tanggal']->translatedFormat('d M Y') }}</td>
                                        <td><span class="status-badge {{ $meta['class'] }}">{{ $meta['label'] }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('proses', ['id' => $item['kode']]) }}#top" class="dash-table-action">
                                                Lihat Detail <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="tarif-empty">
                        <i class="bi bi-inbox d-block mb-2" style="font-size:1.75rem;"></i>
                        Belum ada pengajuan kalibrasi. Yuk ajukan yang pertama!
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================
         5. STATUS TERAKHIR   &   6. INFORMASI TERBARU
    ============================================================ --}}
    <section class="member-section pt-0 pb-5">
        <div class="container-xxl">
            <div class="row g-3 g-lg-4">

                {{-- 5. Status Terakhir --}}
                @if ($statusTerakhir)
                    @php
                        $statusTerakhirMeta = $statusMeta[$statusTerakhir['status']];
                        $statusTerakhirProgress = $statusProgress[$statusTerakhir['status']];
                    @endphp
                    <div class="col-lg-5" data-aos="fade-up">
                        <div class="dash-status-card">
                            <span class="dash-status-badge-label">Status Terakhir</span>
                            <h3 class="dash-status-code">{{ $statusTerakhir['kode'] }}</h3>
                            <p class="dash-status-text">
                                Status: <strong>{{ $statusTerakhirMeta['label'] }}</strong>
                            </p>
                            <div class="dash-progress">
                                <div class="dash-progress-bar" style="width: {{ $statusTerakhirProgress }}%"></div>
                            </div>
                            <span class="dash-progress-percent">{{ $statusTerakhirProgress }}% selesai</span>
                            <a href="{{ route('proses', ['id' => $statusTerakhir['kode']]) }}#top" class="btn btn-hero-outline dash-status-btn">
                                Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- 6. Informasi Terbaru --}}
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="80">
                    <div class="dash-panel dash-info-panel">
                        <div class="dash-panel-head">
                            <h2>Informasi Terbaru</h2>
                        </div>
                        <ul class="dash-info-list">
                            @foreach ($informasiTerbaru as $info)
                                <li>
                                    <span class="dash-info-icon"><i class="bi {{ $info['icon'] }}"></i></span>
                                    <span class="dash-info-body">
                                        <strong>{{ $info['title'] }}</strong>
                                        <span>{{ $info['desc'] }}</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
