@extends('admin.layouts.app')

@section('title', 'Update Status Kalibrasi')
@section('page_title', 'Update Status Pesanan: ' . $calibration->registration_number)
@section('page_subtitle', 'Perbarui status proses & jadwal kalibrasi. Perubahan langsung terlihat oleh pelanggan.')

@section('page_actions')
<a href="{{ route('admin.calibrations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection

@push('styles')
<style>
.info-row { display: flex; margin-bottom: 12px; font-size: 0.86rem; }
.info-row .info-label { width: 150px; flex-shrink: 0; color: var(--text-secondary); }
.info-row .info-val { font-weight: 600; color: var(--text-primary); }

/* ===== Status selector: kartu ikon, bukan dropdown polos ===== */
.status-picker { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
.status-option { position: relative; }
.status-option input { position: absolute; opacity: 0; inset: 0; width: 100%; height: 100%; margin: 0; cursor: pointer; z-index: 2; }
.status-option label {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    text-align: center; padding: 14px 6px; border-radius: 14px;
    border: 1.5px solid var(--card-border);
    background: rgba(120,140,160,0.06);
    color: var(--text-secondary);
    font-size: 0.72rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s;
}
.status-option label i { font-size: 1.3rem; }
.status-option input:checked + label {
    border-color: var(--green-600);
    background: linear-gradient(135deg, rgba(30,148,71,0.14), rgba(15,110,168,0.1));
    color: var(--green-700);
    box-shadow: 0 4px 14px rgba(30,148,71,0.18);
    transform: translateY(-2px);
}
[data-theme="dark"] .status-option input:checked + label { color: var(--green-400); }
.status-option label:hover { border-color: var(--green-600); }

.jadwal-box {
    border: 1.5px dashed var(--card-border);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
    background: rgba(15,110,168,0.05);
    display: none;
}
.jadwal-box .jadwal-hint { font-size: 0.74rem; color: var(--text-secondary); margin-top: 6px; display: flex; gap: 6px; align-items: flex-start; }

.cert-box {
    border: 1.5px dashed var(--card-border);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
    background: rgba(30,148,71,0.05);
    display: none;
}
.cert-box .cert-hint { font-size: 0.74rem; color: var(--text-secondary); margin-top: 6px; display: flex; gap: 6px; align-items: flex-start; }
.ditolak-box {
    border: 1.5px dashed rgba(239,68,68,0.35);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
    background: rgba(239,68,68,0.05);
    display: none;
}
.ditolak-box .ditolak-hint { font-size: 0.74rem; color: var(--text-secondary); margin-top: 6px; display: flex; gap: 6px; align-items: flex-start; }

.reason-picker { display: flex; gap: 10px; margin-bottom: 14px; }
.reason-option { flex: 1; position: relative; }
.reason-option input { position: absolute; opacity: 0; inset: 0; width: 100%; height: 100%; margin: 0; cursor: pointer; z-index: 2; }
.reason-option label {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px 8px; border-radius: 10px;
    border: 1.5px solid var(--card-border);
    background: var(--card-bg);
    color: var(--text-secondary);
    font-size: 0.8rem; font-weight: 700;
    cursor: pointer; transition: all 0.2s;
}
.reason-option input:checked + label {
    border-color: #dc2626;
    background: rgba(239,68,68,0.1);
    color: #dc2626;
}

.resubmit-toggle-box {
    display: none;
    background: rgba(255,255,255,0.5);
    border: 1px solid var(--card-border);
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 4px;
}
[data-theme="dark"] .resubmit-toggle-box { background: rgba(255,255,255,0.03); }

/* === File Dropzone === */
.file-dropzone {
    border: 2px dashed var(--green-600, #1E9447);
    border-radius: 14px;
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: rgba(30,148,71,0.04);
    position: relative;
    user-select: none;
}
.file-dropzone:hover, .file-dropzone.dragover {
    background: rgba(30,148,71,0.1);
    border-color: var(--green-600, #1E9447);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(30,148,71,0.15);
}
.file-dropzone.has-file {
    border-style: solid;
    background: rgba(30,148,71,0.08);
}
.file-dropzone-icon {
    font-size: 2.2rem;
    color: var(--green-600, #1E9447);
    margin-bottom: 8px;
    display: block;
}
.file-dropzone-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}
.file-dropzone-sub {
    font-size: 0.78rem;
    color: var(--text-secondary, #64748b);
}
.file-dropzone-chosen {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--green-600, #1E9447);
    margin-top: 8px;
}
[data-theme="dark"] .file-dropzone { background: rgba(30,148,71,0.07); border-color: rgba(30,148,71,0.5); }
[data-theme="dark"] .file-dropzone:hover, [data-theme="dark"] .file-dropzone.dragover { background: rgba(30,148,71,0.15); }
[data-theme="dark"] .file-dropzone-title { color: #e2e8f0; }
[data-theme="dark"] .file-dropzone-sub { color: #94a3b8; }

/* Tombol navigasi panah di samping kiri/kanan preview dokumen — halaman Edit */
.admin-alat-nav-btn-edit {
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
    cursor: pointer;
    transition: all 0.15s;
}
.admin-alat-nav-btn-edit:hover { background: var(--green-600); color: #fff; border-color: var(--green-600); }
.admin-alat-nav-btn-left  { left: 10px; }
.admin-alat-nav-btn-right { right: 10px; }
[data-theme="dark"] .admin-alat-nav-btn-edit { background: rgba(30,30,30,0.85); color: #e2e8f0; }

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
    <!-- Detail Pengajuan Instansi -->
    <div class="col-lg-6 d-flex">
        <div class="card p-4 w-100 h-100">
            <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-size: 1rem; color: var(--text-primary);">
                <i class="bi bi-building me-2" style="color:var(--blue-600);"></i>Detail Instansi & Pemohon
            </h5>

            <div class="info-row">
                <span class="info-label">No. Registrasi</span>
                <span class="info-val">{{ $calibration->registration_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Instansi</span>
                <span class="info-val">{{ $calibration->nama_instansi ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Kontak PIC</span>
                <span class="info-val">{{ $calibration->nama_kontak ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nomor Telepon</span>
                <span class="info-val">{{ $calibration->nomor_telepon ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-val">{{ $calibration->email ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat Lengkap</span>
                <span class="info-val">{{ $calibration->alamat_lengkap ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tgl Pengajuan</span>
                <span class="info-val">{{ $calibration->request_date?->format('d F Y') ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Akun Login</span>
                <span class="info-val">{{ $calibration->user->name ?? '-' }} ({{ $calibration->user->email ?? '-' }})</span>
            </div>
            @if($calibration->catatan_tambahan)
            <div class="info-row mb-0">
                <span class="info-label">Catatan Tambahan</span>
                <span class="info-val" style="color:#dc2626;">{{ $calibration->catatan_tambahan }}</span>
            </div>
            @endif
        </div>
    </div>


    <!-- Form Update Status -->
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-size: 1rem; color: var(--text-primary);">
                <i class="bi bi-arrow-repeat me-2" style="color:var(--blue-600);"></i>Perbarui Status Pesanan
            </h5>

            {{-- Bukti Pembayaran yang dikirim user --}}
            @if($calibration->bukti_pembayaran)
            <div class="p-3 mb-3 rounded" style="background:rgba(34,197,94,0.06); border:1px solid rgba(34,197,94,0.2);">
                <div class="fw-bold mb-2" style="font-size:0.85rem; color:var(--green-600);">
                    <i class="bi bi-receipt-cutoff me-1"></i> Bukti Pembayaran dari User
                </div>
                @php
                    $buktiExt = strtolower(pathinfo($calibration->bukti_pembayaran, PATHINFO_EXTENSION));
                    $buktiUrl = asset('storage/' . $calibration->bukti_pembayaran);
                @endphp
                @if(in_array($buktiExt, ['jpg','jpeg','png']))
                    <img src="{{ $buktiUrl }}" alt="Bukti Pembayaran" class="img-fluid rounded mb-2 w-100" style="max-height:160px; object-fit:cover; border:1px solid var(--card-border);">
                @elseif($buktiExt === 'pdf')
                    <div style="height:140px; border:1px solid var(--card-border); border-radius:6px; overflow:hidden; margin-bottom:8px;">
                        <embed src="{{ $buktiUrl }}" type="application/pdf" width="100%" height="100%"/>
                    </div>
                @endif
                <a href="{{ $buktiUrl }}" target="_blank" class="btn btn-sm btn-outline-success w-100 fw-bold" style="border-radius:8px; font-size:0.8rem;">
                    <i class="bi bi-eye me-1"></i> Lihat / Unduh Bukti Pembayaran
                </a>
            </div>
            @endif

            <form action="{{ route('admin.calibrations.update', $calibration->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label class="form-label">Status Pesanan</label>
                <div class="status-picker">
                    @php
                        $statusOptions = [
                            'Pengajuan'   => 'bi-file-earmark-text',
                            'Penjadwalan' => 'bi-calendar-event',
                            'Kalibrasi'   => 'bi-gear-wide-connected',
                            'Pembayaran'  => 'bi-cash-coin',
                            'Sertifikat'  => 'bi-patch-check-fill',
                            'Selesai'     => 'bi-check2-all',
                            'Ditolak'     => 'bi-x-octagon-fill',
                        ];
                    @endphp
                    @foreach($statusOptions as $value => $icon)
                    <div class="status-option">
                        <input type="radio" name="status" id="status-{{ $loop->index }}" value="{{ $value }}"
                               {{ old('status', $calibration->status) == $value ? 'checked' : '' }} required>
                        <label for="status-{{ $loop->index }}">
                            <i class="bi {{ $icon }}"></i>
                            {{ $value }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('status') <div class="text-danger small mb-3">{{ $message }}</div> @enderror

                <!-- Jadwal Tanggal Kalibrasi -->
                <div class="jadwal-box" id="jadwal-box">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label mb-1" for="tanggal_kalibrasi">
                                <i class="bi bi-calendar-check me-1" style="color:var(--blue-600);"></i>
                                Tanggal Kalibrasi / Kunjungan Teknisi
                            </label>
                            <input type="date" class="form-control @error('tanggal_kalibrasi') is-invalid @enderror"
                                   name="tanggal_kalibrasi" id="tanggal_kalibrasi"
                                   value="{{ old('tanggal_kalibrasi', optional($calibration->tanggal_kalibrasi)->format('Y-m-d')) }}">
                            @error('tanggal_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label mb-1" for="waktu_kalibrasi">
                                <i class="bi bi-clock me-1" style="color:var(--blue-600);"></i>
                                Perkiraan Waktu
                            </label>
                            <input type="time" class="form-control @error('waktu_kalibrasi') is-invalid @enderror"
                                   name="waktu_kalibrasi" id="waktu_kalibrasi"
                                   value="{{ old('waktu_kalibrasi', $calibration->waktu_kalibrasi ? \Carbon\Carbon::parse($calibration->waktu_kalibrasi)->format('H:i') : '') }}">
                            @error('waktu_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-1" for="lokasi_kalibrasi">
                            <i class="bi bi-geo-alt-fill me-1" style="color:var(--blue-600);"></i>
                            Lokasi Kalibrasi
                        </label>
                        @php
                            $lokasiOptions = [
                                ''                 => ['label' => '-- Pilih Lokasi --', 'icon' => 'bi-dash'],
                                'Klinik / Faskes'  => ['label' => 'Klinik / Faskes', 'icon' => 'bi-hospital'],
                                'Lab UPTD'         => ['label' => 'Lab UPTD', 'icon' => 'bi-building'],
                            ];
                            $selectedLokasi = old('lokasi_kalibrasi', $calibration->lokasi_kalibrasi) ?? '';
                        @endphp
                        <div class="modern-select dropdown">
                            <input type="hidden" name="lokasi_kalibrasi" id="lokasi-kalibrasi-input" value="{{ $selectedLokasi }}">
                            <button type="button" class="modern-select-trigger dropdown-toggle w-100" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi {{ $lokasiOptions[$selectedLokasi]['icon'] }} modern-select-trigger-icon"></i>
                                <span class="modern-select-trigger-label">{{ $lokasiOptions[$selectedLokasi]['label'] }}</span>
                            </button>
                            <ul class="dropdown-menu modern-select-menu w-100">
                                @foreach($lokasiOptions as $val => $opt)
                                <li>
                                    <button type="button" 
                                            class="modern-select-item {{ $selectedLokasi === $val ? 'active' : '' }}" 
                                            onclick="setModernSelect(this, 'lokasi-kalibrasi-input', '{{ $val }}', '{{ $opt['label'] }}', '{{ $opt['icon'] }}')">
                                        <i class="bi {{ $opt['icon'] }}"></i>
                                        <span>{{ $opt['label'] }}</span>
                                        <i class="bi bi-check-lg modern-select-check"></i>
                                    </button>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @error('lokasi_kalibrasi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-1" for="draft_harga">
                            <i class="bi bi-file-earmark-spreadsheet me-1" style="color:var(--blue-600);"></i>
                            Draft Harga (Opsional)
                        </label>
                        @if($calibration->draft_harga)
                        <div class="mb-2 d-flex align-items-center gap-2" id="existing-draft-box">
                            <a href="{{ Storage::url($calibration->draft_harga) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-text me-1"></i> Lihat Draft Saat Ini</a>
                            <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1" onclick="deleteExistingFile('draft_harga')" title="Hapus file ini"><i class="bi bi-x-lg"></i></button>
                        </div>
                        @endif
                        <input type="hidden" name="delete_draft_harga" id="delete_draft_harga" value="0">
                        <div class="file-dropzone" id="dropzone-draft" onclick="document.getElementById('draft_harga').click()" ondragover="dzDragOver(event,this)" ondragleave="dzDragLeave(event,this)" ondrop="dzDrop(event,this,'draft_harga')">
                            <input type="file" class="@error('draft_harga') is-invalid @enderror" id="draft_harga" name="draft_harga" accept=".pdf,.xlsx,.xls,.doc,.docx" style="display:none;" onchange="dzFileChosen(this,'dropzone-draft')">
                            <div class="file-dropzone-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                            <div class="file-dropzone-title">Klik atau seret file ke sini</div>
                            <div class="file-dropzone-sub">PDF, XLSX, DOC &bull; Maks. 10MB</div>
                            <div class="file-dropzone-chosen" id="draft_harga-chosen" style="display:none; position:relative; z-index:3;">
                                <i class="bi bi-file-earmark-check-fill me-1"></i><span></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2 py-0 px-1" onclick="clearSelectedFile(event, 'draft_harga', 'dropzone-draft')"><i class="bi bi-x-circle"></i></button>
                            </div>
                        </div>
                        @error('draft_harga') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <p class="jadwal-hint mb-0">
                        <i class="bi bi-info-circle"></i>
                        <span>Begitu tanggal ini tiba, status akan otomatis berubah menjadi <strong>"Kalibrasi"</strong> dan langsung terlihat oleh pelanggan &mdash; tidak perlu diperbarui manual lagi.</span>
                    </p>
                </div>


                <!-- Alasan Penolakan (khusus status Ditolak) -->
                <div class="ditolak-box" id="ditolak-box">
                    <label class="form-label mb-2">
                        <i class="bi bi-exclamation-octagon-fill me-1" style="color:#dc2626;"></i>
                        Alasan Penolakan
                    </label>
                    <div class="reason-picker">
                        <div class="reason-option">
                            <input type="radio" name="rejection_reason" id="reason-dokumen" value="Dokumen"
                            {{ old('rejection_reason', $calibration->rejection_reason) == 'Dokumen' ? 'checked' : '' }}>
                            <label for="reason-dokumen"><i class="bi bi-file-earmark-x"></i> Dokumen</label>
                        </div>
                        <div class="reason-option">
                            <input type="radio" name="rejection_reason" id="reason-lainnya" value="Lainnya"
       {{ old('rejection_reason', $calibration->rejection_reason ?? 'Lainnya') == 'Lainnya' ? 'checked' : '' }}>
                            <label for="reason-lainnya"><i class="bi bi-three-dots"></i> Lainnya</label>
                        </div>
                    </div>
                    @error('rejection_reason') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                    <div class="resubmit-toggle-box" id="resubmit-toggle-box">
                        <input type="hidden" name="allow_resubmit" id="allow_resubmit" value="{{ old('allow_resubmit', $calibration->allow_resubmit) ? '1' : '0' }}">
                        <p class="ditolak-hint mb-0 mt-2">
                            <i class="bi bi-clock-history"></i>
                            <span>Pelanggan diberi waktu <strong>1x24 jam</strong> untuk upload dokumen baru. Jika lewat batas waktu, nomor pesanan ini otomatis hangus dan pelanggan wajib mengajukan kalibrasi baru.</span>
                        </p>
                    </div>

                    <p class="ditolak-hint mb-0 mt-2" id="ditolak-no-resubmit-hint" style="display:none;">
                        <i class="bi bi-info-circle"></i>
                        <span>Pelanggan tidak akan bisa upload ulang dokumen. Nomor pesanan ini akan mati dan pelanggan harus mengajukan kalibrasi baru dari awal.</span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="form-label">Catatan Teknis / Admin <span style="font-weight:400; color:var(--text-secondary);">(terlihat oleh pelanggan)</span></label>
                    <textarea class="form-control @error('admin_note') is-invalid @enderror" name="admin_note" rows="5"
                              placeholder="Misal: Teknisi dijadwalkan datang tanggal 20 Juli 2026. Alat sedang dikalibrasi di laboratorium...">{{ old('admin_note', $calibration->admin_note) }}</textarea>
                    @error('admin_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Daftar Alat: full width, di bawah -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card p-4">
            <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-size: 1rem; color: var(--text-primary);">
                <i class="bi bi-tools me-2" style="color:var(--green-600);"></i>
                @php
                    $rawAlatEdit = $calibration->daftar_alat;
                    $decodedAlatEdit = null;
                    if (is_array($rawAlatEdit)) {
                        $decodedAlatEdit = $rawAlatEdit;
                    } elseif (is_string($rawAlatEdit)) {
                        $tmp = json_decode($rawAlatEdit, true);
                        $decodedAlatEdit = is_array($tmp) ? $tmp : $rawAlatEdit;
                    }
                    $alatFilesEdit  = [];
                    $alatLegacyEdit = [];
                    if ($decodedAlatEdit) {
                        if (is_array($decodedAlatEdit)) {
                            foreach ($decodedAlatEdit as $item) {
                                if (is_array($item)) {
                                    $alatLegacyEdit[] = $item;
                                } elseif (is_string($item) && $item !== '') {
                                    $alatFilesEdit[] = $item;
                                }
                            }
                        } elseif (is_string($decodedAlatEdit) && $decodedAlatEdit !== '[]' && $decodedAlatEdit !== '') {
                            $alatFilesEdit[] = $decodedAlatEdit;
                        }
                    }
                @endphp
                @if(count($alatFilesEdit) > 0)
                    Daftar Alat yang Diunggah ({{ count($alatFilesEdit) }} file)
                @else
                    Daftar Alat yang Dikalibrasi
                @endif
            </h5>

            @if(count($alatLegacyEdit) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Nama Alat</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @foreach($alatLegacyEdit as $alat)
                            <tr>
                                <td>{{ $alat['name'] ?? '-' }}</td>
                                <td>{{ $alat['qty'] ?? 1 }} unit</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if(count($alatFilesEdit) > 0)
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    @if(count($alatFilesEdit) > 1)
                    <div class="d-flex flex-wrap gap-2" id="adminAlatFileTabsEdit">
                        @foreach($alatFilesEdit as $idx => $path)
                        <button type="button"
                                class="btn btn-sm admin-alat-file-tab-edit {{ $idx === 0 ? 'btn-success' : 'btn-outline-secondary' }}"
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
                            @if(count($alatFilesEdit) > 1)
                            <label class="form-label small fw-semibold mb-2">Pilih dokumen yang dikirim</label>
                            <div class="d-flex flex-column gap-1 mb-3" style="max-height:140px; overflow-y:auto;">
                                @foreach($alatFilesEdit as $idx => $path)
                                <div class="form-check">
                                    <input class="form-check-input admin-alat-reply-checkbox-edit" type="checkbox" value="{{ $path }}" id="alatReplyCheckEdit{{ $idx }}" checked>
                                    <label class="form-check-label small" for="alatReplyCheckEdit{{ $idx }}">File {{ $idx + 1 }}</label>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <input type="hidden" class="admin-alat-reply-checkbox-edit" value="{{ $alatFilesEdit[0] }}" checked>
                            @endif
                            <label class="form-label small fw-semibold mb-1">Pesan ke pelanggan</label>
                            <textarea class="form-control form-control-sm admin-alat-reply-text-edit" rows="3" placeholder="Tulis pesan (opsional)..."></textarea>
                            <button type="button" class="btn btn-reply-gradient btn-sm w-100 mt-2 admin-alat-reply-send-edit" style="border-radius:10px;">
                                <i class="bi bi-send-fill me-1"></i> Kirim ke Chat
                            </button>
                        </div>
                    </div>
                </div>

                @foreach($alatFilesEdit as $idx => $path)
                @php
                    $alatExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $alatUrl = asset('storage/' . $path);
                @endphp
                <div class="admin-alat-file-preview-edit" data-idx="{{ $idx }}" style="{{ $idx === 0 ? '' : 'display:none;' }}">
                    <div class="preview-card border rounded p-2 mt-2" style="background: var(--card-bg); border-color: var(--card-border) !important; height: 620px; display: flex; flex-direction: column;">
                        <div class="d-flex align-items-center justify-content-between mb-2 ps-1 pe-1">
                            <h6 class="small fw-bold mb-0" style="color: var(--text-secondary);"><i class="bi bi-eye"></i> Pratinjau Dokumen {{ count($alatFilesEdit) > 1 ? '('.($idx+1).'/'.count($alatFilesEdit).')' : '' }}</h6>
                            
                        </div>

                        {{-- Area preview + panah navigasi di samping kiri/kanan --}}
                        <div class="flex-grow-1 rounded overflow-hidden position-relative" style="border: 1px solid var(--card-border); background: var(--input-bg);">
                            @if(count($alatFilesEdit) > 1)
                            <button type="button" class="admin-alat-nav-btn-edit admin-alat-nav-btn-left" data-dir="-1" title="Sebelumnya">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button type="button" class="admin-alat-nav-btn-edit admin-alat-nav-btn-right" data-dir="1" title="Selanjutnya">
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

                {{-- Form tersembunyi: dipicu oleh tombol "Balas" / "Balas Semua" di atas --}}
                <form action="{{ route('admin.calibrations.reply-chat', $calibration) }}" method="POST" id="adminAlatReplyFormEdit" class="d-none">
                    @csrf
                    <input type="hidden" name="message" id="adminAlatReplyMessageEdit">
                    <div id="adminAlatSelectedInputsEdit"></div>
                </form>
            @elseif(count($alatLegacyEdit) === 0)
                <p class="mb-0" style="color:var(--text-secondary);">Belum ada data alat.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="status"]');
    const jadwalBox = document.getElementById('jadwal-box');
    const ditolakBox = document.getElementById('ditolak-box');

    const reasonRadios = document.querySelectorAll('input[name="rejection_reason"]');
    const resubmitToggleBox = document.getElementById('resubmit-toggle-box');
    const noResubmitHint = document.getElementById('ditolak-no-resubmit-hint');
    const allowResubmitCheckbox = document.getElementById('allow_resubmit');

    function toggleFields(status) {
        jadwalBox.style.display  = status === 'Penjadwalan' ? 'block' : 'none';
        ditolakBox.style.display = status === 'Ditolak'      ? 'block' : 'none';

        if (status === 'Ditolak') {
            toggleReasonFields();
        }
    }

    function toggleReasonFields() {
        const checkedReason = document.querySelector('input[name="rejection_reason"]:checked');
        const reason = checkedReason ? checkedReason.value : null;

        if (reason === 'Dokumen') {
            resubmitToggleBox.style.display = 'block';
            noResubmitHint.style.display = 'none';
            if (allowResubmitCheckbox) allowResubmitCheckbox.value = '1';
        } else {
            resubmitToggleBox.style.display = 'none';
            noResubmitHint.style.display = 'flex';
            if (allowResubmitCheckbox) allowResubmitCheckbox.value = '0';
        }
    }

    // Initialize on load
    const checkedRadio = document.querySelector('input[name="status"]:checked');
    if (checkedRadio) {
        toggleFields(checkedRadio.value);
    }

    // Listen to changes
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleFields(this.value);
        });
    });

    reasonRadios.forEach(radio => {
        radio.addEventListener('change', toggleReasonFields);
    });
});
</script>

<script>
function dzDragOver(e, el) { e.preventDefault(); el.classList.add('dragover'); }
function dzDragLeave(e, el) { el.classList.remove('dragover'); }
function dzDrop(e, el, inputId) {
    e.preventDefault();
    el.classList.remove('dragover');
    const input = document.getElementById(inputId);
    if (e.dataTransfer.files.length) {
        // Transfer files to the real input
        const dt = new DataTransfer();
        dt.items.add(e.dataTransfer.files[0]);
        input.files = dt.files;
        dzFileChosen(input, el.id);
    }
}
function dzFileChosen(input, zoneId) {
    const zone = document.getElementById(zoneId);
    if (!zone) return;
    const chosenId = input.id + '-chosen';
    const chosenEl = document.getElementById(chosenId);
    if (input.files && input.files.length > 0) {
        zone.classList.add('has-file');
        zone.querySelector('.file-dropzone-icon').style.display = 'none';
        zone.querySelector('.file-dropzone-title').style.display = 'none';
        zone.querySelector('.file-dropzone-sub').style.display = 'none';
        if (chosenEl) {
            chosenEl.style.display = 'block';
            chosenEl.querySelector('span').textContent = input.files[0].name;
        }
    } else {
        zone.classList.remove('has-file');
        zone.querySelector('.file-dropzone-icon').style.display = '';
        zone.querySelector('.file-dropzone-title').style.display = '';
        zone.querySelector('.file-dropzone-sub').style.display = '';
        if (chosenEl) chosenEl.style.display = 'none';
    }
}

function clearSelectedFile(event, inputId, zoneId) {
    event.stopPropagation();
    const input = document.getElementById(inputId);
    input.value = '';
    dzFileChosen(input, zoneId);
}

function deleteExistingFile(type) {
    const labels = {
        'draft_harga': 'draft harga',
    };
    const label = labels[type] || 'file ini';
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus ' + label + '?',
            text: "File akan dihapus setelah form disimpan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal-delete-popup',
                title: 'swal-delete-title',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
                icon: 'swal-delete-icon'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                processDeleteFile(type);
            }
        });
    } else {
        if (!confirm('Yakin ingin menghapus ' + label + '? File akan dihapus setelah form disimpan.')) return;
        processDeleteFile(type);
    }
}

function processDeleteFile(type) {
    if (type === 'draft_harga') {
        document.getElementById('delete_draft_harga').value = '1';
        document.getElementById('existing-draft-box').style.display = 'none';
    }
}

/* ==========================================
   Navigasi pratinjau Daftar Alat (multi-file) — halaman Edit/Update
========================================== */
(function () {
    const totalAlatFiles = document.querySelectorAll('.admin-alat-file-preview-edit').length;
    if (totalAlatFiles === 0) return;

    function showAlatFile(idx) {
        if (idx < 0) idx = totalAlatFiles - 1;
        if (idx >= totalAlatFiles) idx = 0;
        const idxStr = String(idx);

        document.querySelectorAll('.admin-alat-file-tab-edit').forEach(t => {
            const isActive = t.dataset.idx === idxStr;
            t.classList.toggle('btn-success', isActive);
            t.classList.toggle('btn-outline-secondary', !isActive);
        });

        document.querySelectorAll('.admin-alat-file-preview-edit').forEach(p => {
            p.style.display = p.dataset.idx === idxStr ? 'block' : 'none';
        });
    }

    document.addEventListener('click', function (e) {
        const tab = e.target.closest('.admin-alat-file-tab-edit');
        if (tab) {
            showAlatFile(parseInt(tab.dataset.idx, 10));
            return;
        }

        const navBtn = e.target.closest('.admin-alat-nav-btn-edit');
        if (navBtn) {
            let currentIdx = 0;
            document.querySelectorAll('.admin-alat-file-preview-edit').forEach(p => {
                if (p.style.display !== 'none') currentIdx = parseInt(p.dataset.idx, 10);
            });
            const dir = parseInt(navBtn.dataset.dir, 10);
            showAlatFile(currentIdx + dir);
        }
    });
})();

/* ==========================================
   Form "Balas ke Chat" — kirim dokumen (satu/beberapa) sebagai lampiran — halaman Edit/Update
========================================== */
(function () {
    const form = document.getElementById('adminAlatReplyFormEdit');
    if (!form) return;

    const messageInput = document.getElementById('adminAlatReplyMessageEdit');
    const container = document.getElementById('adminAlatSelectedInputsEdit');

    document.addEventListener('click', function (e) {
        const sendBtn = e.target.closest('.admin-alat-reply-send-edit');
        if (!sendBtn) return;

        const dropdownMenu = sendBtn.closest('.admin-alat-reply-dropdown');
        if (!dropdownMenu) return;

        const checked = dropdownMenu.querySelectorAll('.admin-alat-reply-checkbox-edit:checked');
        const paths = Array.from(checked).map(cb => cb.value);

        if (paths.length === 0) {
            alert('Pilih minimal 1 dokumen untuk dikirim ke chat.');
            return;
        }

        const textareaEl = dropdownMenu.querySelector('.admin-alat-reply-text-edit');
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
