@extends('admin.layouts.app')

@section('title', 'Pesanan Kalibrasi')
@section('page_title', 'Pesanan Kalibrasi')
@section('page_subtitle', 'Kelola semua pengajuan kalibrasi alat kesehatan')

@section('content')
<div class="card border-0">
    <div class="card-body p-0">

        <!-- Filter Bar -->
        <div class="p-4 border-bottom" style="border-color:var(--card-border)!important;">
            <form method="GET" action="{{ route('admin.calibrations.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-5">
                    <label class="form-label">Cari Pesanan</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:rgba(30,148,71,0.08);border-color:var(--card-border);border-right:none;border-radius:10px 0 0 10px;">
                            <i class="bi bi-search" style="color:var(--green-600);"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                               style="border-left:none;border-radius:0 10px 10px 0;"
                               placeholder="No. pendaftaran, instansi, atau nama..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Filter Status</label>
                    @php
                        // Icon disamakan dengan status-picker di halaman edit biar konsisten
                        $statusOptions = [
                            ''            => ['label' => 'Semua Status',      'icon' => 'bi-list-ul'],
                            'Pengajuan'   => ['label' => 'Pengajuan',         'icon' => 'bi-file-earmark-text'],
                            'Penjadwalan' => ['label' => 'Penjadwalan',       'icon' => 'bi-calendar-event'],
                            'Kalibrasi'   => ['label' => 'Kalibrasi',         'icon' => 'bi-gear-wide-connected'],
                            'Pembayaran'  => ['label' => 'Pembayaran',        'icon' => 'bi-cash-coin'],
                            'Sertifikat'  => ['label' => 'Sertifikat Terbit', 'icon' => 'bi-patch-check-fill'],
                            'Selesai'     => ['label' => 'Selesai',          'icon' => 'bi-check2-all'],
                            'Ditolak'     => ['label' => 'Ditolak',          'icon' => 'bi-x-octagon-fill'],
                        ];
                        $selectedStatus = request('status', '');
                    @endphp
                    <div class="modern-select dropdown">
                        <input type="hidden" name="status" id="status-filter-input" value="{{ $selectedStatus }}">
                        <button type="button" class="modern-select-trigger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi {{ $statusOptions[$selectedStatus]['icon'] }} modern-select-trigger-icon"></i>
                            <span class="modern-select-trigger-label">{{ $statusOptions[$selectedStatus]['label'] }}</span>
                        </button>
                        <ul class="dropdown-menu modern-select-menu">
                            @foreach($statusOptions as $value => $opt)
                            <li>
                                <button type="button"
                                        class="modern-select-item {{ $selectedStatus === $value ? 'active' : '' }}"
                                        onclick="setModernSelect(this, 'status-filter-input', '{{ $value }}', '{{ $opt['label'] }}', '{{ $opt['icon'] }}')">
                                    <i class="bi {{ $opt['icon'] }}"></i>
                                    <span>{{ $opt['label'] }}</span>
                                    <i class="bi bi-check-lg modern-select-check"></i>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.calibrations.index') }}" class="btn btn-secondary d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Instansi / Pemohon</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tgl Kalibrasi</th>
                        <th>Keterangan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calibrations as $c)
                    <tr>
                        <td>
                            <span style="font-family:monospace; font-size:0.8rem; font-weight:700; color:var(--blue-600);">
                                {{ $c->registration_number ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $c->nama_instansi }}</div>
                            <div style="font-size:0.78rem; color:var(--text-muted);">{{ $c->nama_kontak }}</div>
                        </td>
                        <td style="font-size:0.82rem; color:var(--text-secondary);">
                            {{ $c->metode_kalibrasi ?? '-' }}
                        </td>
                        <td>
                            @php
                                $badgeClass = match($c->status) {
                                    'Pengajuan'   => 'badge-pengajuan',
                                    'Penjadwalan' => 'badge-penjadwalan',
                                    'Pembayaran'  => 'badge-pembayaran',
                                    'Kalibrasi'   => 'badge-kalibrasi',
                                    'Sertifikat'  => 'badge-sertifikat',
                                    'Selesai'     => 'badge-sertifikat',
                                    'Ditolak'     => 'badge-ditolak',
                                    default       => 'badge-pengajuan',
                                };
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">{{ $c->status }}</span>
                        </td>
                        <td style="font-size:0.82rem;">
                            @if($c->tanggal_kalibrasi)
                                <span style="color:var(--text-primary); font-weight:600;">
                                    <i class="bi bi-calendar-check me-1" style="color:var(--blue-600);"></i>
                                    {{ $c->tanggal_kalibrasi->format('d M Y') }}
                                </span>
                            @else
                                <span style="color:var(--text-muted);">Belum dijadwalkan</span>
                            @endif
                        </td>
                        <td style="font-size:0.78rem;">
                            @if($c->admin_note)
                                <span style="color:var(--text-primary);">{{ $c->admin_note }}</span>
                            @else
                                <span style="color:var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.chat.index', ['user' => $c->user_id, 'ref' => $c->registration_number]) }}"
                                   class="btn btn-sm btn-outline-success py-1 px-2" style="font-size:0.78rem;">
                                    <i class="bi bi-chat-dots me-1"></i> Chat
                                </a>
                                <a href="{{ route('admin.calibrations.show', $c) }}"
                                   class="btn btn-sm btn-secondary py-1 px-2" style="font-size:0.78rem;">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                                <a href="{{ route('admin.calibrations.edit', $c) }}"
                                   class="btn btn-sm btn-primary py-1 px-2" style="font-size:0.78rem;">
                                    <i class="bi bi-pencil me-1"></i> Update
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-clipboard2-x" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                            Belum ada pesanan kalibrasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($calibrations->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center border-top" style="border-color:var(--card-border)!important;">
            <div style="font-size:0.82rem; color:var(--text-muted);">
                {{ $calibrations->firstItem() }}–{{ $calibrations->lastItem() }} dari {{ $calibrations->total() }} pesanan
            </div>
            {{ $calibrations->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
