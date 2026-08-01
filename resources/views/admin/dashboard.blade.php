@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan statistik dan aktivitas terkini sistem kalibrasi')

@section('content')
<div class="row g-4 mb-4">
    {{-- Stat Cards --}}
    @php
        $stats = [
            ['label' => 'Total Pengajuan Baru',  'value' => $pengajuanBaru, 'icon' => 'bi-file-earmark-text', 'color' => 'var(--blue-500)',  'bg' => 'rgba(26,143,197,0.12)'],
            ['label' => 'Total Pesanan Aktif',   'value' => $totalPesanan,  'icon' => 'bi-arrow-repeat',      'color' => 'var(--green-500)', 'bg' => 'rgba(38,184,87,0.12)'],
            ['label' => 'Pesanan Selesai',       'value' => $selesai,       'icon' => 'bi-patch-check',       'color' => '#8b5cf6',          'bg' => 'rgba(139,92,246,0.12)'],
            ['label' => 'Ditolak/Hangus',        'value' => $ditolak,       'icon' => 'bi-x-circle',          'color' => '#ef4444',          'bg' => 'rgba(239,68,68,0.12)'],
            ['label' => 'Total Pengguna',        'value' => $totalUser,     'icon' => 'bi-people',            'color' => '#f59e0b',          'bg' => 'rgba(245,158,11,0.12)'],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="col-6 col-md-4 col-lg-2 flex-grow-1">
        <div class="card border-0 h-100" style="background: var(--card-bg); backdrop-filter: blur(var(--card-blur)); border: 1px solid var(--card-border)!important; border-radius: 16px;">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div style="width:40px; height:40px; border-radius:12px; background:{{ $stat['bg'] }}; display:flex; align-items:center; justify-content:center;">
                        <i class="bi {{ $stat['icon'] }}" style="font-size:1.1rem; color:{{ $stat['color'] }};"></i>
                    </div>
                </div>
                <div>
                    <div style="font-size:1.6rem; font-weight:800; color:var(--text-primary); line-height:1;">{{ $stat['value'] }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:3px;">{{ $stat['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- GRAFIK PESANAN --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0" style="background: var(--card-bg); backdrop-filter: blur(var(--card-blur)); border: 1px solid var(--card-border)!important; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <div>
                        <h6 class="fw-700 mb-0" style="color:var(--text-primary);">Grafik Pertumbuhan Pesanan</h6>
                        <small style="color:var(--text-muted);">Tren pesanan berdasarkan periode yang dipilih</small>
                    </div>
                    <div class="d-flex gap-2">
                        <select id="chartFilter" class="form-select form-select-sm" style="border-radius:10px; border-color:var(--card-border); background-color:var(--card-bg); color:var(--text-primary); width:auto;" onchange="updateChart(this.value)">
                            <option value="6months">6 Bulan Terakhir</option>
                            <option value="1year">1 Tahun Terakhir</option>
                            <option value="all">Semua Waktu</option>
                        </select>
                    </div>
                </div>
                <div style="height: 300px; width: 100%;">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Calibrations Table --}}
    <div class="col-lg-8">
        <div class="card border-0 h-100" style="background: var(--card-bg); backdrop-filter: blur(var(--card-blur)); border: 1px solid var(--card-border)!important; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h6 class="fw-700 mb-0" style="color:var(--text-primary);">Pengajuan Terkini</h6>
                        <small style="color:var(--text-muted);">10 pengajuan terbaru yang masuk ke sistem</small>
                    </div>
                    <a href="{{ route('admin.calibrations.index') }}" class="btn btn-sm" style="background:rgba(26,143,197,0.1); color:var(--blue-500); border-radius:10px; font-size:0.8rem; font-weight:600; border:none;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($recentCalibrations->isEmpty())
                    <div class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.4;"></i>
                        <p class="mb-0">Belum ada pengajuan masuk</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size:0.85rem;">
                            <thead>
                                <tr style="border-color:var(--card-border);">
                                    <th style="color:var(--text-muted); font-weight:600; border-color:var(--card-border); padding-bottom:12px;">No. Pengajuan</th>
                                    <th style="color:var(--text-muted); font-weight:600; border-color:var(--card-border); padding-bottom:12px;">Instansi</th>
                                    <th style="color:var(--text-muted); font-weight:600; border-color:var(--card-border); padding-bottom:12px;">Status</th>
                                    <th style="color:var(--text-muted); font-weight:600; border-color:var(--card-border); padding-bottom:12px;">Tanggal</th>
                                    <th style="color:var(--text-muted); font-weight:600; border-color:var(--card-border); padding-bottom:12px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $statusColor = [
                                    'Menunggu Verifikasi' => ['bg' => 'rgba(245,158,11,0.12)', 'color' => '#f59e0b'],
                                    'Disetujui'           => ['bg' => 'rgba(56,182,232,0.12)', 'color' => 'var(--blue-400)'],
                                    'Ditolak'             => ['bg' => 'rgba(239,68,68,0.12)',  'color' => '#ef4444'],
                                    'Penjadwalan'         => ['bg' => 'rgba(139,92,246,0.12)','color' => '#8b5cf6'],
                                    'Kalibrasi'           => ['bg' => 'rgba(26,143,197,0.12)','color' => 'var(--blue-500)'],
                                    'Pembayaran'          => ['bg' => 'rgba(245,158,11,0.12)','color' => '#f59e0b'],
                                    'Selesai'             => ['bg' => 'rgba(38,184,87,0.12)', 'color' => 'var(--green-500)'],
                                ];
                                @endphp
                                @foreach($recentCalibrations as $cal)
                                @php
                                    $sc = $statusColor[$cal->status] ?? ['bg' => 'rgba(100,116,139,0.12)', 'color' => '#64748b'];
                                @endphp
                                <tr style="border-color:var(--card-border);">
                                    <td style="color:var(--text-primary); font-weight:600; vertical-align:middle;">{{ $cal->registration_number }}</td>
                                    <td style="color:var(--text-secondary); vertical-align:middle; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $cal->nama_instansi }}</td>
                                    <td style="vertical-align:middle;">
                                        <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:0.75rem; font-weight:600; background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                                            {{ $cal->status }}
                                        </span>
                                    </td>
                                    <td style="color:var(--text-muted); vertical-align:middle;">{{ $cal->created_at->format('d M Y') }}</td>
                                    <td style="vertical-align:middle;">
                                        <a href="{{ route('admin.calibrations.show', $cal) }}" class="btn btn-sm" style="background:rgba(26,143,197,0.1); color:var(--blue-500); border-radius:8px; font-size:0.75rem; border:none; padding:4px 10px;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Side --}}
    <div class="col-lg-4">
        <div class="row g-4 h-100">

            {{-- Status Distribution --}}
            <div class="col-12">
                <div class="card border-0" style="background: var(--card-bg); backdrop-filter: blur(var(--card-blur)); border: 1px solid var(--card-border)!important; border-radius: 16px;">
                    <div class="card-body p-4">
                        <h6 class="fw-700 mb-1" style="color:var(--text-primary);">Distribusi Status</h6>
                        <small class="d-block mb-3" style="color:var(--text-muted);">Semua pengajuan berdasarkan status</small>

                        @if(($pengajuanBaru + $totalPesanan + $selesai + $ditolak) === 0)
                            <div class="text-center py-3" style="color:var(--text-muted); font-size:0.85rem;">
                                <i class="bi bi-pie-chart" style="font-size:1.5rem; display:block; margin-bottom:8px; opacity:0.4;"></i>
                                Belum ada data
                            </div>
                        @else
                            @php
                            $allStatuses = [
                                'Pengajuan'           => ['icon' => 'bi-file-earmark-text', 'color' => 'var(--blue-500)'],
                                'Ditolak'             => ['icon' => 'bi-x-circle',          'color' => '#ef4444'],
                                'Penjadwalan'         => ['icon' => 'bi-calendar3',         'color' => '#8b5cf6'],
                                'Kalibrasi'           => ['icon' => 'bi-activity',          'color' => 'var(--blue-500)'],
                                'Pembayaran'          => ['icon' => 'bi-credit-card',       'color' => '#f59e0b'],
                                'Sertifikat'          => ['icon' => 'bi-award',             'color' => 'var(--green-600)'],
                                'Selesai'             => ['icon' => 'bi-patch-check',       'color' => 'var(--green-500)'],
                            ];
                            @endphp
                            <div class="d-flex flex-column gap-2">
                                @foreach($allStatuses as $statusName => $statusInfo)
                                    @php $count = $statusStats[$statusName] ?? 0; @endphp
                                    @if($count > 0)
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="d-flex align-items-center gap-2" style="flex:1; min-width:0;">
                                            <i class="bi {{ $statusInfo['icon'] }}" style="color:{{ $statusInfo['color'] }}; font-size:0.9rem; flex-shrink:0;"></i>
                                            <span style="font-size:0.8rem; color:var(--text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $statusName }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:80px; height:6px; border-radius:99px; background:rgba(100,116,139,0.15); overflow:hidden;">
                                                <div style="height:100%; width:{{ ($pengajuanBaru+$totalPesanan+$selesai+$ditolak) > 0 ? round(($count/($pengajuanBaru+$totalPesanan+$selesai+$ditolak))*100) : 0 }}%; border-radius:99px; background:{{ $statusInfo['color'] }};"></div>
                                            </div>
                                            <span style="font-size:0.8rem; font-weight:700; color:var(--text-primary); min-width:20px; text-align:right;">{{ $count }}</span>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-12">
                <div class="card border-0" style="background: var(--card-bg); backdrop-filter: blur(var(--card-blur)); border: 1px solid var(--card-border)!important; border-radius: 16px;">
                    <div class="card-body p-4">
                        <h6 class="fw-700 mb-3" style="color:var(--text-primary);">Aksi Cepat</h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('admin.calibrations.index', ['status' => 'Pengajuan']) }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="border-radius:12px; background:rgba(26,143,197,0.08); border:1px solid rgba(26,143,197,0.2);">
                                <i class="bi bi-file-earmark-plus" style="color:var(--blue-500); font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Tinjau Pengajuan Baru</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $pengajuanBaru }} pengajuan butuh tindak lanjut</div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted); font-size:0.85rem;"></i>
                            </a>

                            <a href="{{ route('admin.calibrations.index', ['status' => 'Kalibrasi']) }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="border-radius:12px; background:rgba(38,184,87,0.08); border:1px solid rgba(38,184,87,0.2);">
                                <i class="bi bi-tools" style="color:var(--green-500); font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Kelola Pesanan Aktif</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $totalPesanan }} pesanan sedang berjalan</div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted); font-size:0.85rem;"></i>
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="border-radius:12px; background:rgba(139,92,246,0.08); border:1px solid rgba(139,92,246,0.2);">
                                <i class="bi bi-people" style="color:#8b5cf6; font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Manajemen Pengguna</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $totalUser }} pengguna terdaftar</div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted); font-size:0.85rem;"></i>
                            </a>

                            <a href="{{ route('admin.dashboard.export.download') }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="border-radius:12px; background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2);">
                                <i class="bi bi-download" style="color:#f59e0b; font-size:1.1rem;"></i>
                                <div>
                                    <div style="font-size:0.82rem; font-weight:600; color:var(--text-primary);">Export Data CSV</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">Unduh semua riwayat data</div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted); font-size:0.85rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Dokumen Penting & Spreadsheet --}}
<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card border-0" style="background: var(--card-bg); backdrop-filter: blur(var(--card-blur)); border: 1px solid var(--card-border)!important; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h6 class="fw-700 mb-0" style="color:var(--text-primary);">Dokumen & Spreadsheet</h6>
                        <small style="color:var(--text-muted);">Kelola sertifikat, surat izin, dan akses data spreadsheet pesanan</small>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Sertifikat KAN --}}
                    <div class="col-md-4">
                        <div class="p-4 h-100 d-flex flex-column align-items-center text-center gap-3" style="border-radius:16px; background:rgba(38,184,87,0.06); border:1.5px solid rgba(38,184,87,0.2);">
                            <div style="width:56px; height:56px; border-radius:16px; background:rgba(38,184,87,0.15); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-patch-check-fill" style="font-size:1.5rem; color:var(--green-600);"></i>
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:0.9rem; color:var(--text-primary); margin-bottom:4px;">Sertifikat KAN</div>
                                @if($setting->sertifikat_kan)
                                    <a href="{{ asset('storage/'.$setting->sertifikat_kan) }}" target="_blank"
                                       style="font-size:0.75rem; color:var(--green-600); text-decoration:none;">
                                        <i class="bi bi-eye me-1"></i>Lihat dokumen aktif
                                    </a>
                                @else
                                    <div style="font-size:0.75rem; color:var(--text-muted);">Belum ada dokumen</div>
                                @endif
                            </div>
                            <button type="button" onclick="openUploadModal('sertifikat_kan','Sertifikat KAN','var(--green-600)','rgba(38,184,87,0.1)')"
                                class="btn w-100 mt-auto" style="background:var(--green-600); color:#fff; border-radius:12px; font-size:0.82rem; font-weight:600; border:none; padding:10px 0;">
                                <i class="bi bi-cloud-upload me-1"></i>
                                {{ $setting->sertifikat_kan ? 'Ganti Dokumen' : 'Unggah Dokumen' }}
                            </button>
                        </div>
                    </div>

                    {{-- Surat Izin Operasional --}}
                    <div class="col-md-4">
                        <div class="p-4 h-100 d-flex flex-column align-items-center text-center gap-3" style="border-radius:16px; background:rgba(26,143,197,0.06); border:1.5px solid rgba(26,143,197,0.2);">
                            <div style="width:56px; height:56px; border-radius:16px; background:rgba(26,143,197,0.15); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-file-earmark-medical-fill" style="font-size:1.5rem; color:var(--blue-500);"></i>
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:0.9rem; color:var(--text-primary); margin-bottom:4px;">Surat Izin Operasional</div>
                                @if($setting->surat_operasional)
                                    <a href="{{ asset('storage/'.$setting->surat_operasional) }}" target="_blank"
                                       style="font-size:0.75rem; color:var(--blue-500); text-decoration:none;">
                                        <i class="bi bi-eye me-1"></i>Lihat dokumen aktif
                                    </a>
                                @else
                                    <div style="font-size:0.75rem; color:var(--text-muted);">Belum ada dokumen</div>
                                @endif
                            </div>
                            <button type="button" onclick="openUploadModal('surat_operasional','Surat Izin Operasional','var(--blue-500)','rgba(26,143,197,0.1)')"
                                class="btn w-100 mt-auto" style="background:var(--blue-600); color:#fff; border-radius:12px; font-size:0.82rem; font-weight:600; border:none; padding:10px 0;">
                                <i class="bi bi-cloud-upload me-1"></i>
                                {{ $setting->surat_operasional ? 'Ganti Dokumen' : 'Unggah Dokumen' }}
                            </button>
                        </div>
                    </div>

                    {{-- Spreadsheet Database --}}
                    <div class="col-md-4">
                        <div class="p-4 h-100 d-flex flex-column align-items-center text-center gap-3" style="border-radius:16px; background:rgba(139,92,246,0.06); border:1.5px solid rgba(139,92,246,0.2);">
                            <div style="width:56px; height:56px; border-radius:16px; background:rgba(139,92,246,0.15); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-file-earmark-spreadsheet-fill" style="font-size:1.5rem; color:#8b5cf6;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:0.9rem; color:var(--text-primary); margin-bottom:4px;">Spreadsheet Pesanan</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">Data pesanan masuk tersimpan di sini</div>
                            </div>
                            <a href="{{ $setting->spreadsheet_url ?? 'https://docs.google.com/spreadsheets/d/1DhHL_YELkImqnR3DgC0hYnIvqe9tB-Z-Tyebs_8o8CM/edit?usp=sharing' }}"
                               target="_blank" rel="noopener"
                               class="btn w-100 mt-auto" style="background:#8b5cf6; color:#fff; border-radius:12px; font-size:0.82rem; font-weight:600; border:none; padding:10px 0;">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Google Sheets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL UPLOAD DOKUMEN — Modern Drag & Drop
============================================================ --}}
<div id="uploadModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); backdrop-filter:blur(6px); align-items:center; justify-content:center;">
    <div style="background:var(--card-bg); border:1px solid var(--card-border); border-radius:24px; width:100%; max-width:460px; margin:16px; box-shadow:0 32px 80px rgba(0,0,0,0.25); overflow:hidden;">

        {{-- Header --}}
        <div id="modalHeader" class="d-flex align-items-center justify-content-between p-4 pb-3">
            <div class="d-flex align-items-center gap-3">
                <div id="modalIconBox" style="width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-cloud-upload-fill" style="font-size:1.2rem; color:#fff;"></i>
                </div>
                <div>
                    <div id="modalTitle" style="font-weight:700; font-size:0.95rem; color:var(--text-primary);"></div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">PDF, JPG, PNG · Maks. 5 MB</div>
                </div>
            </div>
            <button onclick="closeUploadModal()" style="background:rgba(100,116,139,0.1); border:none; border-radius:10px; width:34px; height:34px; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-x-lg" style="font-size:0.9rem; color:var(--text-muted);"></i>
            </button>
        </div>

        {{-- Form --}}
        <form id="uploadForm" action="{{ route('admin.dashboard.document.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="uploadFieldName" name="_doc_type" value="">

            <div class="px-4 pb-2">
                {{-- Drop Zone --}}
                <div id="dropZone"
                     onclick="document.getElementById('fileInput').click()"
                     ondragover="event.preventDefault(); this.style.background='rgba(99,102,241,0.08)'; this.style.borderColor='#6366f1';"
                     ondragleave="this.style.background=''; this.style.borderColor='rgba(100,116,139,0.25)';"
                     ondrop="handleDrop(event)"
                     style="border:2px dashed rgba(100,116,139,0.25); border-radius:18px; padding:36px 24px; text-align:center; cursor:pointer; transition:all 0.2s; position:relative;">

                    <div id="dropZoneIcon" style="width:72px; height:72px; border-radius:50%; background:rgba(99,102,241,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <i class="bi bi-cloud-arrow-up" style="font-size:1.8rem; color:#6366f1;"></i>
                    </div>
                    <div style="font-weight:700; font-size:0.95rem; color:var(--text-primary); margin-bottom:6px;">Seret file ke sini</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);">atau klik untuk memilih file dari komputer</div>

                    <input id="fileInput" type="file" accept=".pdf,.jpg,.jpeg,.png" style="display:none;"
                           onchange="handleFileSelect(this.files[0])">
                </div>

                {{-- File Preview --}}
                <div id="filePreview" style="display:none; margin-top:12px; padding:12px 14px; border-radius:14px; background:rgba(99,102,241,0.06); border:1px solid rgba(99,102,241,0.2);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px; height:40px; border-radius:10px; background:rgba(99,102,241,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i id="fileIcon" class="bi bi-file-earmark-pdf" style="font-size:1rem; color:#6366f1;"></i>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div id="fileName" style="font-size:0.82rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></div>
                            <div id="fileSize" style="font-size:0.72rem; color:var(--text-muted);"></div>
                        </div>
                        <button type="button" onclick="clearFile()" style="background:rgba(239,68,68,0.08); border:none; border-radius:8px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0;">
                            <i class="bi bi-x" style="font-size:0.85rem; color:#ef4444;"></i>
                        </button>
                    </div>
                    {{-- Progress bar (simulasi visual) --}}
                    <div style="margin-top:10px; height:4px; border-radius:99px; background:rgba(100,116,139,0.15); overflow:hidden;">
                        <div id="fileProgressBar" style="height:100%; width:0%; border-radius:99px; background:#6366f1; transition:width 0.4s ease;"></div>
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="d-flex gap-2 p-4 pt-3">
                <button type="button" onclick="closeUploadModal()"
                    class="btn flex-fill" style="background:rgba(100,116,139,0.1); color:var(--text-secondary); border-radius:12px; font-weight:600; border:none; padding:12px;">
                    Batal
                </button>
                <button type="submit" id="submitBtn"
                    class="btn flex-fill" style="background:#6366f1; color:#fff; border-radius:12px; font-weight:600; border:none; padding:12px;">
                    <i class="bi bi-cloud-upload me-1"></i> Upload
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ============================================
// LOGIC FOR CHART.JS
// ============================================
const rawData = @json($chartDataRaw);
const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

let orderChartInstance = null;

// Function to get last N months
function generateLastNMonths(n) {
    let result = [];
    let d = new Date();
    d.setDate(1); // Set to 1st to avoid edge cases
    for (let i = n - 1; i >= 0; i--) {
        let tempDate = new Date(d.getFullYear(), d.getMonth() - i, 1);
        let monthStr = (tempDate.getMonth() + 1).toString().padStart(2, '0');
        result.push({
            label: monthNames[tempDate.getMonth()] + ' ' + tempDate.getFullYear(),
            key: tempDate.getFullYear() + '-' + monthStr
        });
    }
    return result;
}

// Function to generate all months in a specific range or just all unique months in data
function generateAllTime() {
    if (rawData.length === 0) return [];
    
    // Sort raw data
    rawData.sort((a, b) => a.month_year.localeCompare(b.month_year));
    
    let startParts = rawData[0].month_year.split('-');
    let startYear = parseInt(startParts[0]);
    let startMonth = parseInt(startParts[1]) - 1;
    
    let endParts = rawData[rawData.length - 1].month_year.split('-');
    let endYear = parseInt(endParts[0]);
    let endMonth = parseInt(endParts[1]) - 1;
    
    let result = [];
    let d = new Date(startYear, startMonth, 1);
    let endD = new Date(endYear, endMonth, 1);
    
    while(d <= endD) {
        let monthStr = (d.getMonth() + 1).toString().padStart(2, '0');
        result.push({
            label: monthNames[d.getMonth()] + ' ' + d.getFullYear(),
            key: d.getFullYear() + '-' + monthStr
        });
        d.setMonth(d.getMonth() + 1);
    }
    return result;
}

function updateChart(filter) {
    let labels = [];
    let dataPoints = [];
    
    let timeline = [];
    if (filter === '6months') {
        timeline = generateLastNMonths(6);
    } else if (filter === '1year') {
        timeline = generateLastNMonths(12);
    } else {
        timeline = generateAllTime();
    }
    
    // Fallback if no data
    if(timeline.length === 0) {
        timeline = generateLastNMonths(6);
    }
    
    // Populate data
    timeline.forEach(t => {
        labels.push(t.label);
        let match = rawData.find(r => r.month_year === t.key);
        dataPoints.push(match ? match.total : 0);
    });

    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDarkMode ? '#e7edf5' : '#475569';
    const gridColor = isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';

    if (orderChartInstance) {
        orderChartInstance.destroy();
    }

    const ctx = document.getElementById('orderChart').getContext('2d');
    
    // Create gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(38, 184, 87, 0.4)');
    gradient.addColorStop(1, 'rgba(38, 184, 87, 0.0)');

    orderChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Pesanan / Pengajuan',
                data: dataPoints,
                backgroundColor: gradient,
                borderColor: '#1E9447',
                borderWidth: 3,
                pointBackgroundColor: '#1E9447',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDarkMode ? 'rgba(15,31,56,0.9)' : 'rgba(255,255,255,0.9)',
                    titleColor: textColor,
                    bodyColor: textColor,
                    borderColor: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 4,
                    usePointStyle: true,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: textColor, font: {family: "'Inter', sans-serif"} }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { color: textColor, font: {family: "'Inter', sans-serif"}, stepSize: 1 }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
}

// Initial Load
document.addEventListener('DOMContentLoaded', () => {
    updateChart('6months');
});

// Watch theme changes to update chart colors
const observer = new MutationObserver(() => {
    updateChart(document.getElementById('chartFilter').value);
});
observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
</script>
@endpush

<script>
function openUploadModal(field, title, color, bgColor) {
    document.getElementById('uploadFieldName').name = field;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalIconBox').style.background = color.includes('var') ? 'var(--blue-600)' : color;
    document.getElementById('submitBtn').style.background = color.includes('var') ? color : color;
    clearFile();
    const modal = document.getElementById('uploadModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.body.style.overflow = '';
    clearFile();
}

function handleDrop(e) {
    e.preventDefault();
    const dt = e.dataTransfer;
    const file = dt.files[0];
    if (file) handleFileSelect(file);
    e.currentTarget.style.background = '';
    e.currentTarget.style.borderColor = 'rgba(100,116,139,0.25)';
}

function handleFileSelect(file) {
    if (!file) return;
    const allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    if (!allowed.includes(file.type)) {
        alert('Format file tidak didukung. Gunakan PDF, JPG, atau PNG.');
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5 MB.');
        return;
    }

    // Update hidden input
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('fileInput').files = dt.files;

    // Update preview
    const icons = {'application/pdf': 'bi-file-earmark-pdf', 'image/jpeg': 'bi-file-earmark-image', 'image/jpg': 'bi-file-earmark-image', 'image/png': 'bi-file-earmark-image'};
    document.getElementById('fileIcon').className = 'bi ' + (icons[file.type] || 'bi-file-earmark');
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('filePreview').style.display = 'block';

    // Animate progress bar
    let w = 0;
    const bar = document.getElementById('fileProgressBar');
    bar.style.width = '0%';
    const iv = setInterval(() => {
        w = Math.min(w + 8, 100);
        bar.style.width = w + '%';
        if (w >= 100) clearInterval(iv);
    }, 30);
}

function clearFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('fileProgressBar').style.width = '0%';
}

// Close on backdrop click
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) closeUploadModal();
});
</script>
@endsection
