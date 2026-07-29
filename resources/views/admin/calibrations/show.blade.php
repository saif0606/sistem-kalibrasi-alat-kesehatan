@extends('admin.layouts.app')

@section('title', 'Detail Pesanan Kalibrasi')
@section('page_title', 'Detail Pesanan: ' . $calibration->registration_number)
@section('page_subtitle', 'Informasi lengkap yang diisi pelanggan saat pengajuan')

@section('page_actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.calibrations.edit', $calibration) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Update Status
    </a>
    <a href="{{ route('admin.calibrations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
@endsection

@push('styles')

<style>
.badge-ditolak-bisa {
    background: rgba(245,158,11,0.15) !important;
    color: #b45309 !important;
    border: 1px solid rgba(245,158,11,0.35) !important;
}
[data-theme="dark"] .badge-ditolak-bisa {
    background: rgba(245,158,11,0.18) !important;
    color: #fbbf24 !important;
    border-color: rgba(245,158,11,0.3) !important;
}

.info-row { display: flex; margin-bottom: 12px; font-size: 0.86rem; flex-wrap: wrap; }
.info-row .info-label { width: 180px; flex-shrink: 0; color: var(--text-secondary); }
.info-row .info-val { font-weight: 600; color: var(--text-primary); }
.timeline-mini { display:flex; align-items:center; gap:0; margin-bottom: 6px; }
.timeline-mini .tl-dot {
    width: 34px; height: 34px; border-radius: 50%; display:flex; align-items:center; justify-content:center;
    font-size: 0.85rem; font-weight:700; flex-shrink:0;
    background: rgba(120,140,160,0.12); color: var(--text-secondary); border: 2px solid var(--card-border);
}
.timeline-mini .tl-dot.done   { background: var(--green-600); color:#fff; border-color: var(--green-600); }
.timeline-mini .tl-dot.active { background: var(--blue-600); color:#fff; border-color: var(--blue-600); }
.timeline-mini .tl-line { flex:1; height:3px; background: var(--card-border); }
.timeline-mini .tl-line.done { background: var(--green-600); }
.timeline-mini-label { font-size:0.68rem; font-weight:600; color: var(--text-secondary); text-align:center; }

/* Tombol navigasi panah di samping kiri/kanan preview dokumen */
.admin-alat-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    width: 34px; height: 34px;
    border-radius: 50%;
    border: 1.5px solid var(--card-border);
    background: rgba(255,255,255,0.85);
    color: var(--text-primary);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.15s;
}
.admin-alat-nav-btn:hover { background: var(--green-600); color: #fff; border-color: var(--green-600); }
.admin-alat-nav-btn-left  { left: 10px; }
.admin-alat-nav-btn-right { right: 10px; }
[data-theme="dark"] .admin-alat-nav-btn { background: rgba(30,30,30,0.85); color: #e2e8f0; }

/* === Tombol Balas Dokumen: gradient blue-green glassmorphism === */
.btn-reply-gradient {
    background: linear-gradient(135deg, rgba(15,110,168,0.92), rgba(30,148,71,0.92));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(15,110,168,0.25);
    transition: all 0.2s;
}
.btn-reply-gradient:hover, .btn-reply-gradient:focus {
    background: linear-gradient(135deg, rgba(15,110,168,1), rgba(30,148,71,1));
    color: #fff;
    box-shadow: 0 6px 20px rgba(15,110,168,0.35);
    transform: translateY(-1px);
}
.btn-reply-gradient.show,
.btn-reply-gradient:active {
    background: linear-gradient(135deg, rgba(15,110,168,1), rgba(30,148,71,1)) !important;
    color: #fff !important;
}

.admin-alat-reply-dropdown {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(15,110,168,0.18);
}
[data-theme="dark"] .admin-alat-reply-dropdown {
    background: rgba(30,41,59,0.85);
    border-color: rgba(255,255,255,0.08);
}
.admin-alat-reply-dropdown .admin-alat-reply-text {
    border-radius: 10px;
    border-color: rgba(15,110,168,0.25);
}
.admin-alat-reply-dropdown .admin-alat-reply-text:focus {
    border-color: var(--green-600);
    box-shadow: 0 0 0 3px rgba(30,148,71,0.15);
}
</style>
@endpush



@section('content')
<div class="row g-4">
    <!-- Kolom kiri: Data Instansi -->
    <div class="col-lg-6 d-flex">
        <div class="card p-4 w-100 h-100">
            <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-size: 1rem; color: var(--text-primary);">
                <i class="bi bi-building me-2" style="color:var(--blue-600);"></i>Data Instansi & Pemohon
            </h5>
            <div class="info-row"><span class="info-label">No. Registrasi</span><span class="info-val">{{ $calibration->registration_number }}</span></div>
            <div class="info-row"><span class="info-label">Nama Instansi</span><span class="info-val">{{ $calibration->nama_instansi ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Nama Kontak PIC</span><span class="info-val">{{ $calibration->nama_kontak ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Nomor Telepon</span><span class="info-val">{{ $calibration->nomor_telepon ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Email</span><span class="info-val">{{ $calibration->email ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Alamat Lengkap</span><span class="info-val">{{ $calibration->alamat_lengkap ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Metode Kalibrasi</span><span class="info-val">{{ $calibration->metode_kalibrasi ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Akun Login</span><span class="info-val">{{ $calibration->user->name ?? '-' }} ({{ $calibration->user->email ?? '-' }})</span></div>
            @if($calibration->catatan_tambahan)
            <div class="info-row mb-0"><span class="info-label">Catatan Pelanggan</span><span class="info-val" style="color:#dc2626;">{{ $calibration->catatan_tambahan }}</span></div>
            @endif
        </div>
    </div>

    <!-- Kolom kanan: Status Saat Ini -->
    <div class="col-lg-6 d-flex">
        <div class="card p-4 w-100 h-100">
            <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-size: 1rem; color: var(--text-primary);">
                <i class="bi bi-signpost-2 me-2" style="color:var(--blue-600);"></i>Status Saat Ini
            </h5>
            @php
                $steps = ['Pengajuan', 'Penjadwalan', 'Pembayaran', 'Kalibrasi', 'Sertifikat', 'Selesai'];
                if ($calibration->status == 'Ditolak') {
                    $steps = ['Pengajuan', 'Ditolak'];
                }
                $currentIdx = array_search($calibration->status, $steps);
                $badgeClass = match($calibration->status) {
                    'Pengajuan'   => 'badge-pengajuan',
                    'Penjadwalan' => 'badge-penjadwalan',
                    'Pembayaran'  => 'badge-pembayaran',
                    'Kalibrasi'   => 'badge-kalibrasi',
                    'Sertifikat'  => 'badge-sertifikat',
                    'Selesai'     => 'badge-sertifikat',
                    'Ditolak'     => $calibration->canResubmitDocuments() ? 'badge-ditolak-bisa' : 'badge-ditolak',
                    default       => 'badge-pengajuan',
                };
            @endphp
            <div class="mb-3">
                <span class="status-badge {{ $badgeClass }}">
                    {{ $calibration->status }}{{ $calibration->canResubmitDocuments() ? ' (Bisa Diperbaiki)' : '' }}
                </span>
            </div>

            <div class="timeline-mini mb-1">
                @foreach($steps as $idx => $step)
                    <div class="tl-dot {{ $idx < $currentIdx ? 'done' : ($idx === $currentIdx ? 'active' : '') }}">
                        @if($idx < $currentIdx) <i class="bi bi-check-lg"></i> @else {{ $idx + 1 }} @endif
                    </div>
                    @if(!$loop->last)
                        <div class="tl-line {{ $idx < $currentIdx ? 'done' : '' }}"></div>
                    @endif
                @endforeach
            </div>
            <div class="d-flex justify-content-between mb-4">
                @foreach($steps as $step)
                <span class="timeline-mini-label" style="width: {{ 100 / count($steps) }}%;">{{ $step }}</span>
                @endforeach
            </div>

            <div class="info-row"><span class="info-label">Tgl Pengajuan</span><span class="info-val">{{ $calibration->request_date?->format('d F Y') ?? '-' }}</span></div>
            <div class="info-row mb-0">
                <span class="info-label">Tgl Kalibrasi</span>
                <span class="info-val">
                    @if($calibration->tanggal_kalibrasi)
                        <i class="bi bi-calendar-check me-1" style="color:var(--blue-600);"></i>{{ $calibration->tanggal_kalibrasi->format('d F Y') }}
                    @else
                        <span style="font-weight:400; color:var(--text-secondary);">Belum dijadwalkan</span>
                    @endif
                </span>
            </div>
            @if($calibration->lokasi_kalibrasi)
            <div class="info-row mt-2 mb-0">
                <span class="info-label">Lokasi Kalibrasi</span>
                <span class="info-val">
                    <i class="bi bi-geo-alt-fill me-1" style="color:var(--blue-600);"></i>{{ $calibration->lokasi_kalibrasi }}
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Info tambahan: catatan admin, bukti pembayaran, sertifikat -->
<div class="row g-4 mt-4">
    @if($calibration->admin_note)
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-2" style="font-size: 0.92rem; color: var(--text-primary);">
                <i class="bi bi-chat-square-text me-2" style="color:var(--blue-600);"></i>Catatan Admin
            </h5>
            <p class="mb-0" style="font-size:0.86rem; color:var(--text-primary);">{{ $calibration->admin_note }}</p>
        </div>
    </div>
    @endif

<!-- Daftar Alat: full width, di bawah -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card p-4">
            @php
                // Normalisasi daftar_alat ke array of file paths (format baru, multi-file).
                // Model bisa saja BELUM cast 'daftar_alat' => 'array', jadi nilainya masih
                // string JSON mentah (mis. '["daftar_alat/x.png"]') -> perlu di-json_decode
                // manual dulu di sini, supaya tidak dianggap "satu path file utuh".
                $rawAlat     = $calibration->daftar_alat;
                $decodedAlat = null;

                if (is_array($rawAlat)) {
                    $decodedAlat = $rawAlat; // sudah di-cast array oleh model
                } elseif (is_string($rawAlat)) {
                    $tmp = json_decode($rawAlat, true);
                    $decodedAlat = is_array($tmp) ? $tmp : $rawAlat; // fallback: bukan JSON valid
                }

                // Tetap dukung 2 format lama: array asosiatif {name, qty} & string path tunggal.
                $alatFiles  = [];
                $alatLegacy = []; // format lama {name, qty}

                if ($decodedAlat) {
                    if (is_array($decodedAlat)) {
                        foreach ($decodedAlat as $item) {
                            if (is_array($item)) {
                                $alatLegacy[] = $item; // {name, qty}
                            } elseif (is_string($item) && $item !== '') {
                                $alatFiles[] = $item; // path file
                            }
                        }
                    } elseif (is_string($decodedAlat) && $decodedAlat !== '[]' && $decodedAlat !== '') {
                        $alatFiles[] = $decodedAlat; // path file tunggal (data lama, bukan JSON)
                    }
                }
            @endphp

            <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-size: 1rem; color: var(--text-primary);">
                <i class="bi bi-file-earmark-text me-2" style="color:var(--green-600);"></i>
                @if(count($alatFiles) > 0)
                    Daftar Alat yang Diunggah ({{ count($alatFiles) }} file)
                @else
                    Daftar Alat Kesehatan
                @endif
            </h5>

            @if(count($alatLegacy) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Nama Alat</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @foreach($alatLegacy as $alat)
                            <tr>
                                <td>{{ $alat['name'] ?? '-' }}</td>
                                <td>{{ $alat['qty'] ?? 1 }} unit</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if(count($alatFiles) > 0)
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    @if(count($alatFiles) > 1)
                    <div class="d-flex flex-wrap gap-2" id="adminAlatFileTabs">
                        @foreach($alatFiles as $idx => $path)
                        <button type="button"
                                class="btn btn-sm admin-alat-file-tab {{ $idx === 0 ? 'btn-success' : 'btn-outline-secondary' }}"
                                style="border-radius:8px; font-size:0.78rem;"
                                data-idx="{{ $idx }}">
                            <i class="bi bi-file-earmark me-1"></i> File {{ $idx + 1 }}
                        </button>
                        @endforeach
                    </div>
                    @endif

                    <div class="dropdown ms-auto">
                        <button type="button" class="btn btn-sm btn-reply-gradient dropdown-toggle" style="border-radius:10px; font-size:0.78rem;" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="bi bi-reply-fill me-1"></i> Balas ke Chat
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3 admin-alat-reply-dropdown" style="width:300px;">
                            @if(count($alatFiles) > 1)
                            <label class="form-label small fw-semibold mb-2">Pilih dokumen yang dikirim</label>
                            <div class="d-flex flex-column gap-1 mb-3" style="max-height:140px; overflow-y:auto;">
                                @foreach($alatFiles as $idx => $path)
                                <div class="form-check">
                                    <input class="form-check-input admin-alat-reply-checkbox" type="checkbox" value="{{ $path }}" id="alatReplyCheck{{ $idx }}" checked>
                                    <label class="form-check-label small" for="alatReplyCheck{{ $idx }}">File {{ $idx + 1 }}</label>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <input type="hidden" class="admin-alat-reply-checkbox" value="{{ $alatFiles[0] }}" checked>
                            @endif
                            <label class="form-label small fw-semibold mb-1">Pesan ke pelanggan</label>
                            <textarea class="form-control form-control-sm admin-alat-reply-text" rows="3" placeholder="Tulis pesan (opsional)..."></textarea>
                            <button type="button" class="btn btn-reply-gradient btn-sm w-100 mt-2 admin-alat-reply-send" style="border-radius:10px;">
                                <i class="bi bi-send-fill me-1"></i> Kirim ke Chat
                            </button>
                        </div>
                    </div>
                </div>

                @foreach($alatFiles as $idx => $path)
                @php
                    $alatExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $alatUrl = asset('storage/' . $path);
                @endphp
                <div class="admin-alat-file-preview" data-idx="{{ $idx }}" style="{{ $idx === 0 ? '' : 'display:none;' }}">
                    <div class="preview-card border rounded p-2 mt-2" style="background: var(--card-bg); border-color: var(--card-border) !important; height: 620px; display: flex; flex-direction: column;">
                        <div class="d-flex align-items-center justify-content-between mb-2 ps-1 pe-1">
                            <h6 class="small fw-bold mb-0" style="color: var(--text-secondary);"><i class="bi bi-eye"></i> Pratinjau Dokumen {{ count($alatFiles) > 1 ? '('.($idx+1).'/'.count($alatFiles).')' : '' }}</h6>
                        </div>

                        {{-- Area preview + panah navigasi di samping kiri/kanan --}}
                        <div class="flex-grow-1 rounded overflow-hidden position-relative" style="border: 1px solid var(--card-border); background: var(--input-bg);">
                            @if(count($alatFiles) > 1)
                            <button type="button" class="admin-alat-nav-btn admin-alat-nav-btn-left" data-dir="-1" title="Sebelumnya">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button type="button" class="admin-alat-nav-btn admin-alat-nav-btn-right" data-dir="1" title="Selanjutnya">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                            @endif

                            @if(in_array($alatExt, ['png', 'jpg', 'jpeg']))
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <img src="{{ $alatUrl }}" alt="Daftar Alat {{ $idx + 1 }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                            @elseif($alatExt === 'pdf')
                                <embed src="{{ $alatUrl }}" type="application/pdf" width="100%" height="100%" />
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                                    <i class="bi bi-file-earmark-x fs-1 text-warning mb-2"></i>
                                    <span style="font-size: 0.85rem; color: var(--text-muted);">Pratinjau otomatis tidak tersedia untuk format <strong>.{{ $alatExt }}</strong>.<br>Silakan klik tombol unduh di bawah.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ $alatUrl }}" target="_blank" class="btn w-100 mt-2 fw-bold" style="border: 1.5px solid var(--green-600); color: var(--green-600); background: var(--card-bg); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 14px; transition: all 0.2s;">
                        <i class="bi bi-download"></i> Lihat / Unduh File {{ $idx + 1 }}
                    </a>
                </div>
                @endforeach

                {{-- Form tersembunyi: dipicu oleh tombol "Balas ke Chat" di atas --}}
                <form action="{{ route('admin.calibrations.reply-chat', $calibration) }}" method="POST" id="adminAlatReplyForm" class="d-none">
                    @csrf
                    <input type="hidden" name="message" id="adminAlatReplyMessage">
                    <div id="adminAlatSelectedInputs"></div>
                </form>
            @elseif(count($alatLegacy) === 0)
                <p class="mb-0" style="color:var(--text-secondary);">Belum ada data alat.</p>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
/* ==========================================
   Navigasi pratinjau Daftar Alat (multi-file) — Admin
========================================== */
(function () {
    const totalAlatFiles = document.querySelectorAll('.admin-alat-file-preview').length;
    if (totalAlatFiles === 0) return;

    function showAlatFile(idx) {
        if (idx < 0) idx = totalAlatFiles - 1;
        if (idx >= totalAlatFiles) idx = 0;
        const idxStr = String(idx);

        document.querySelectorAll('.admin-alat-file-tab').forEach(t => {
            const isActive = t.dataset.idx === idxStr;
            t.classList.toggle('btn-success', isActive);
            t.classList.toggle('btn-outline-secondary', !isActive);
        });

        document.querySelectorAll('.admin-alat-file-preview').forEach(p => {
            p.style.display = p.dataset.idx === idxStr ? 'block' : 'none';
        });
    }

    document.addEventListener('click', function (e) {
        const tab = e.target.closest('.admin-alat-file-tab');
        if (tab) {
            showAlatFile(parseInt(tab.dataset.idx, 10));
            return;
        }

        const navBtn = e.target.closest('.admin-alat-nav-btn');
        if (navBtn) {
            let currentIdx = 0;
            document.querySelectorAll('.admin-alat-file-preview').forEach(p => {
                if (p.style.display !== 'none') currentIdx = parseInt(p.dataset.idx, 10);
            });
            const dir = parseInt(navBtn.dataset.dir, 10);
            showAlatFile(currentIdx + dir);
        }
    });
})();

/* ==========================================
   Form "Balas ke Chat" — kirim dokumen (satu/beberapa) sebagai lampiran
========================================== */
(function () {
    const form = document.getElementById('adminAlatReplyForm');
    if (!form) return;

    const messageInput = document.getElementById('adminAlatReplyMessage');
    const container = document.getElementById('adminAlatSelectedInputs');

    document.addEventListener('click', function (e) {
        const sendBtn = e.target.closest('.admin-alat-reply-send');
        if (!sendBtn) return;

        const dropdownMenu = sendBtn.closest('.admin-alat-reply-dropdown');
        if (!dropdownMenu) return;

        const checked = dropdownMenu.querySelectorAll('.admin-alat-reply-checkbox:checked');
        const paths = Array.from(checked).map(cb => cb.value);

        if (paths.length === 0) {
            alert('Pilih minimal 1 dokumen untuk dikirim ke chat.');
            return;
        }

        const textareaEl = dropdownMenu.querySelector('.admin-alat-reply-text');
        messageInput.value = textareaEl ? textareaEl.value : '';

        container.innerHTML = '';
        paths.forEach(path => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'attachments[]';
            inp.value = path;
            container.appendChild(inp);
        });

        form.submit();
    });
})();
</script>
@endpush
@endsection