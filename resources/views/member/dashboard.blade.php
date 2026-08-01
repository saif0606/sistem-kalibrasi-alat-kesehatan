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
                <a href="{{ route('user.calibrations.create') }}" class="btn btn-hero-primary dash-quick-action-btn">
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
            ['route' => 'user.calibrations.create', 'icon' => 'bi-file-earmark-plus', 'label' => 'Ajukan Kalibrasi'],
            ['route' => 'dashboard.riwayat', 'icon' => 'bi-clock-history', 'label' => 'Riwayat Pengajuan'],
            ['route' => 'user.calibrations.index', 'icon' => 'bi-diagram-3', 'label' => 'Proses'],
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
                                            <a href="{{ route('user.calibrations.show', $item['kode']) }}#top" class="dash-table-action">
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
                            <a href="{{ route('user.calibrations.show', $statusTerakhir['kode']) }}#top" class="btn btn-hero-outline dash-status-btn">
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

@push('scripts')
@if(isset($rejectedDocCalibrations) && $rejectedDocCalibrations->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('rejectedDocModal'), { backdrop: 'static' });
    modal.show();
});
</script>
@endif
@endpush

@push('styles')
<style>
.rejected-doc-modal-header {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    border-radius: 20px 20px 0 0;
    padding: 24px 28px 20px;
    color: #fff;
}
.rejected-doc-order-card {
    background: rgba(239,68,68,0.07);
    border: 1.5px solid rgba(239,68,68,0.22);
    border-radius: 14px;
    padding: 16px 18px;
    margin-bottom: 12px;
}
.rejected-doc-order-card:last-child { margin-bottom: 0; }
.rejected-doc-number { font-family: monospace; font-size: 1rem; font-weight: 800; color: #0c2438; }
.rejected-doc-date   { font-size: 0.82rem; color: #64748b; margin-top: 2px; }
.rejected-doc-deadline {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.35);
    border-radius: 999px; padding: 4px 12px; font-size: 0.78rem;
    font-weight: 700; color: #b45309; margin-top: 8px;
}
[data-theme="dark"] .rejected-doc-order-card { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.3); }
[data-theme="dark"] .rejected-doc-number { color: #f1f5f9; }
</style>
@endpush

{{-- ============================================================
     MODAL: Penolakan Dokumen (muncul otomatis saat ada penolakan dokumen aktif)
============================================================ --}}
@if(isset($rejectedDocCalibrations) && $rejectedDocCalibrations->isNotEmpty())
<div class="modal fade" id="rejectedDocModal" tabindex="-1" aria-labelledby="rejectedDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:520px;">
        <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden; box-shadow:0 20px 50px rgba(0,0,0,0.18);">

            {{-- Header --}}
            <div class="rejected-doc-modal-header">
                <div style="display:flex; align-items:center; gap:14px;">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold" id="rejectedDocModalLabel" style="color:#fff;">Pengajuan Ditolak — Dokumen</h5>
                        <div style="font-size:0.82rem;opacity:0.85;margin-top:2px;">Tindakan diperlukan sebelum batas waktu habis</div>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:24px 28px;">
                <p style="font-size:0.88rem;color:#475569;margin-bottom:18px;line-height:1.6;">
                    Pengajuan kalibrasi berikut <strong style="color:#dc2626;">ditolak karena dokumen tidak lengkap</strong>.
                    Anda diberikan kesempatan untuk mengunggah ulang dokumen dalam waktu <strong style="color:#b45309;">1×24 jam</strong>.
                    Jika tidak, pesanan akan hangus dan Anda harus <strong style="color:#dc2626;">membuat pengajuan ulang dari awal</strong>.
                </p>

                @foreach($rejectedDocCalibrations as $cal)
                <div class="rejected-doc-order-card">
                    <div class="rejected-doc-number">
                        <i class="bi bi-hash me-1"></i>{{ $cal->registration_number }}
                    </div>
                    <div class="rejected-doc-date">
                        <i class="bi bi-calendar3 me-1"></i>Tanggal pengajuan: {{ $cal->request_date->format('d F Y') }}
                    </div>
                    <div class="rejected-doc-deadline">
                        <i class="bi bi-clock-fill"></i>
                        Batas waktu: <span style="color:#dc2626;">{{ $cal->resubmit_deadline->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB</span>
                        — {{ $cal->resubmit_deadline->locale('id')->diffForHumans() }}
                    </div>
                    <div style="margin-top:12px;">
                        <a href="{{ route('user.calibrations.show', $cal) }}"
                           class="btn btn-danger btn-sm fw-bold w-100" style="border-radius:10px; padding:9px;">
                            <i class="bi bi-arrow-repeat me-1"></i> Upload Ulang Dokumen Sekarang
                        </a>
                    </div>
                </div>
                @endforeach

                <div style="background:rgba(239,68,68,0.07);border:1px dashed rgba(239,68,68,0.3);border-radius:12px;padding:12px 16px;margin-top:16px;font-size:0.8rem;color:#7f1d1d;">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Nomor pesanan yang sama tetap berlaku selama Anda mengunggah dokumen sebelum batas waktu di atas.
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="padding:16px 28px;border-top:1px solid rgba(239,68,68,0.12);">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Tutup (Ingatkan Nanti)</button>
                <a href="{{ route('user.calibrations.index') }}" class="btn btn-outline-danger btn-sm fw-bold" style="border-radius:8px;">
                    <i class="bi bi-list-check me-1"></i> Lihat Semua Pesanan
                </a>
            </div>

        </div>
    </div>
</div>
@endif
