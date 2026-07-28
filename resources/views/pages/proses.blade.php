@extends('layouts.app')

@section('title', 'Proses Kalibrasi — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // ==================================================================
        // DATA DUMMY — nanti diganti Auth::user()->calibrations()->latest()->get()
        // Halaman ini menggabungkan dua fungsi: ringkasan tahapan proses
        // (dulu terpisah sebagai "Tracking") dan daftar seluruh pengajuan
        // milik user, sesuai arahan penggabungan fitur.
        // ==================================================================
        $calibrations = [
            [
                'kode' => 'KAL-2025-00021',
                'alat' => 'Sphygmomanometer / Tensimeter',
                'tanggal' => '18 Juli 2025',
                'updated' => '18 Juli 2025 • 10.15 WIB',
                'status' => 'Pengajuan',
            ],
            [
                'kode' => 'KAL-2025-00019',
                'alat' => 'Nebulizer',
                'tanggal' => '15 Juli 2025',
                'updated' => '17 Juli 2025 • 14.00 WIB',
                'status' => 'Penjadwalan',
            ],
            [
                'kode' => 'KAL-2025-00018',
                'alat' => 'ECG (Electrocardiograph) Monitor',
                'tanggal' => '12 Juli 2025',
                'updated' => '20 Juli 2025 • 09.30 WIB',
                'status' => 'Kalibrasi',
            ],
            [
                'kode' => 'KAL-2025-00015',
                'alat' => 'Infusion Pump',
                'tanggal' => '02 Juli 2025',
                'updated' => '10 Juli 2025 • 13.45 WIB',
                'status' => 'Sertifikat',
            ],
        ];

        $stageOrder = ['Pengajuan', 'Penjadwalan', 'Kalibrasi', 'Sertifikat'];

        $stageMeta = [
            'Pengajuan'   => ['badge' => 'stage-pengajuan',   'desc' => 'Pengajuan Anda telah diterima dan sedang diperiksa oleh petugas UPTD.'],
            'Penjadwalan' => ['badge' => 'stage-penjadwalan', 'desc' => 'Dokumen terverifikasi — sedang disepakati jadwal pelaksanaan kalibrasi.'],
            'Kalibrasi'   => ['badge' => 'stage-kalibrasi',   'desc' => 'Tim teknis kami sedang melaksanakan proses kalibrasi alat Anda.'],
            'Sertifikat'  => ['badge' => 'stage-sertifikat',  'desc' => 'Kalibrasi telah selesai — sertifikat sedang/telah diterbitkan.'],
        ];

        // Pengajuan yang sedang "difokuskan" di bagian Tahapan Proses.
        // Dipilih lewat ?id=KODE (dari tombol "Lacak"); default ke yang terbaru.
        $requestedId = strtoupper(trim((string) request()->query('id', '')));
        $focused = null;
        foreach ($calibrations as $item) {
            if ($item['kode'] === $requestedId) {
                $focused = $item;
                break;
            }
        }
        if (!$focused) {
            $focused = $calibrations[0] ?? null;
        }

        $currentIndex = $focused ? array_search($focused['status'], $stageOrder) : -1;

        // Warna orb tiap step: abu (belum), biru (sedang), hijau (telah dilaksanakan)
        $stepTone = function ($stepIndex) use ($currentIndex) {
            if ($currentIndex === -1 || $stepIndex > $currentIndex) return 'grey';
            if ($stepIndex === $currentIndex) return 'blue';
            return 'green';
        };

        // Warna garis penghubung antar step $i dan $i+1
        $connectorTone = function ($i) use ($currentIndex) {
            if ($currentIndex === -1) return 'grey';
            if ($i + 1 < $currentIndex) return 'green';
            if ($i + 1 === $currentIndex) return 'blue';
            return 'grey';
        };

        $stepDescriptions = [
            'Mengisi form data',
            'Kesepakatan jadwal',
            'Proses teknis',
            'Dokumen terbit',
        ];
    @endphp

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <section class="dashboard-hero" id="top">
        <x-tapis-decoration corners="tl-br" />
        <div class="container-xxl position-relative">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div data-aos="fade-up">
                    <p class="dashboard-hero-eyebrow"><i class="bi bi-diagram-3 me-1"></i>Area Member</p>
                    <h1 class="dashboard-hero-title">Portal Proses Kalibrasi</h1>
                    <p class="dashboard-hero-subtitle">Pantau dan ajukan kalibrasi alat kesehatan Anda secara online.</p>
                </div>
                <a href="{{ route('dashboard.pengajuan') }}" class="btn btn-hero-primary flex-shrink-0" data-aos="fade-up" data-aos-delay="80">
                    <i class="bi bi-plus-circle me-1"></i> Ajukan Kalibrasi
                </a>
            </div>
        </div>
    </section>

    @if ($focused)
        {{-- ============================================================
             TAHAPAN PROSES KALIBRASI (ringkasan pengajuan terfokus)
        ============================================================ --}}
        <section class="member-section pt-0">
            <div class="container-xxl">
                <div class="prs-flow-card" data-aos="fade-up">

                    <div class="prs-flow-head">
                        <div>
                            <span class="prs-flow-label">Menampilkan tahapan untuk</span>
                            <h2 class="prs-flow-kode">{{ $focused['kode'] }}</h2>
                        </div>
                        <div class="prs-flow-meta">
                            <span class="stage-badge {{ $stageMeta[$focused['status']]['badge'] }}">{{ $focused['status'] }}</span>
                            <span class="prs-flow-updated"><i class="bi bi-clock-history"></i> {{ $focused['updated'] }}</span>
                        </div>
                    </div>
                    <p class="prs-flow-desc">{{ $stageMeta[$focused['status']]['desc'] }}</p>

                    <div class="prs-steps">
                        <div class="prs-step-track">
                            @for ($i = 0; $i < 3; $i++)
                                <svg class="prs-track-{{ $connectorTone($i) }}" viewBox="0 0 100 24" preserveAspectRatio="none">
                                    <path class="prs-connector-line" d="M0 12 L35 12 L40 4 L46 20 L50 8 L54 15 L60 12 L100 12" />
                                </svg>
                            @endfor
                        </div>

                        @foreach ($stageOrder as $i => $stageName)
                            <div class="prs-step">
                                <span class="prs-step-num prs-tone-{{ $stepTone($i) }}">{{ $i + 1 }}</span>
                                <span class="prs-step-halo prs-tone-{{ $stepTone($i) }}"></span>
                                <span class="prs-step-title">{{ $stageName }}</span>
                                <span class="prs-step-desc">{{ $stepDescriptions[$i] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         DAFTAR PENGAJUAN SAYA
    ============================================================ --}}
    <section class="member-section pt-0 pb-5">
        <div class="container-xxl">
            <div class="d-flex align-items-center justify-content-between mb-3" data-aos="fade-up">
                <h2 class="prs-list-title mb-0">Daftar Pengajuan Saya</h2>
                <span class="prs-list-total">Total: {{ count($calibrations) }} pengajuan</span>
            </div>

            @if (count($calibrations))
                <div class="riw-list">
                    @foreach ($calibrations as $i => $item)
                        <div class="riw-card {{ $focused && $focused['kode'] === $item['kode'] ? 'riw-card-focused' : '' }}"
                             data-aos="fade-up" data-aos-delay="{{ min($i * 40, 200) }}">

                            <div class="riw-card-top">
                                <div class="riw-card-kode">
                                    <span>{{ $item['kode'] }}</span>
                                    <button type="button" class="riw-copy-btn" data-copy="{{ $item['kode'] }}" aria-label="Salin nomor pengajuan">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <span class="stage-badge {{ $stageMeta[$item['status']]['badge'] }}">{{ $item['status'] }}</span>
                            </div>

                            <div class="riw-card-divider"></div>

                            <div class="riw-card-meta">
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-tools"></i> Alat Kesehatan</span>
                                    <strong>{{ $item['alat'] }}</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-calendar3"></i> Tanggal Pengajuan</span>
                                    <strong>{{ $item['tanggal'] }}</strong>
                                </div>
                                <div class="riw-meta-item">
                                    <span><i class="bi bi-arrow-repeat"></i> Update Terakhir</span>
                                    <strong>{{ $item['updated'] }}</strong>
                                </div>
                            </div>

                            <div class="riw-card-footer">
                                <a href="{{ route('proses', ['id' => $item['kode']]) }}#top" class="btn btn-hero-outline riw-card-btn">
                                    <i class="bi bi-eye me-1"></i> Lacak
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="riw-empty-global" data-aos="fade-up">
                    <span class="riw-empty-icon"><i class="bi bi-clipboard2-x"></i></span>
                    <h2>Belum Ada Pengajuan</h2>
                    <p>Anda belum pernah mengajukan kalibrasi. Klik tombol di bawah untuk memulai.</p>
                    <a href="{{ route('dashboard.pengajuan') }}" class="btn btn-hero-primary">
                        <i class="bi bi-plus-circle me-1"></i> Buat Pengajuan Pertama
                    </a>
                </div>
            @endif
        </div>
    </section>

@endsection
