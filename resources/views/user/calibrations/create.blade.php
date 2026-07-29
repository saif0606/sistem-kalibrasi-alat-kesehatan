@extends('layouts.app')

@section('title', 'Ajukan Kalibrasi - UPTD')

@push('styles')
<style>
/* ===== WRAP ===== */
.portal-wrap {
    padding: 100px 0 80px;
    min-height: 100vh;
    background: var(--bg) url('{{ asset("images/bg.png") }}') no-repeat center top;
    background-size: cover;
    background-attachment: fixed;
    color: var(--text-900);
}

.back-link {
    color: var(--text-500); text-decoration: none;
    font-size: 0.82rem; display: inline-flex; align-items: center;
    gap: 5px; transition: color 0.2s;
}
.back-link:hover { color: var(--green-600); }

/* ===== STEP FLOW CARD ===== */
.flow-card {
    background: var(--card-bg);
    border-radius: var(--radius-md);
    border: 2px solid transparent;
    background-image: linear-gradient(var(--card-bg), var(--card-bg)), var(--bg-gradient-brand);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    box-shadow: 0 0 25px rgba(47,116,201,0.12), var(--shadow-soft);
    overflow: hidden;
}

/* ===== FORM CARD ===== */
.card-form {
    background: var(--card-bg);
    border-radius: var(--radius-md);
    border: 2px solid transparent;
    background-image: linear-gradient(var(--card-bg), var(--card-bg)), var(--bg-gradient-brand);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    box-shadow: 0 0 25px rgba(31,155,74,0.12), var(--shadow-soft);
    color: var(--text-900);
    transition: box-shadow 0.3s;
}
.card-form:hover { box-shadow: 0 0 35px rgba(31,155,74,0.18), var(--shadow-hover); }
.section-title {
    font-size: 0.95rem; font-weight: 700; color: var(--text-900);
    margin-bottom: 16px; padding-bottom: 10px;
    border-bottom: 2px solid var(--card-border);
}
.form-label { font-weight: 600; font-size: 0.85rem; color: var(--text-900); margin-bottom: 6px; }
.form-label .req { color: #ef4444; }
.form-label .opt { color: var(--text-500); font-weight: 400; font-size: 0.78rem; }
.form-control, .form-select {
    border-radius: 10px;
    border: 1.5px solid rgba(15,76,150,0.15);
    padding: 10px 14px;
    font-size: 0.9rem;
    background-color: var(--card-bg);
    color: var(--text-900);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control::placeholder { color: var(--text-500); opacity: 0.7; }
.form-control:focus, .form-select:focus {
    border-color: var(--green-600);
    box-shadow: 0 0 0 3px rgba(31,155,74,0.15);
    background-color: var(--card-bg); color: var(--text-900);
}
.form-text-hint { font-size: 0.75rem; color: var(--text-500); margin-top: 5px; }

/* ===== ADDRESS AUTOCOMPLETE ===== */
.addr-wrapper { position: relative; }
.addr-suggestions {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: var(--card-bg);
    border: 1.5px solid rgba(15,76,150,0.15);
    border-radius: 10px;
    z-index: 999;
    max-height: 220px; overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    display: none;
}
.addr-suggestion-item {
    padding: 10px 14px; cursor: pointer;
    font-size: 0.85rem; color: var(--text-900);
    border-bottom: 1px solid var(--card-border);
    transition: background 0.15s;
    display: flex; align-items: flex-start; gap: 8px;
}
.addr-suggestion-item:last-child { border-bottom: none; }
.addr-suggestion-item:hover { background: var(--bg-soft); }
.addr-suggestion-item i { color: var(--green-600); flex-shrink: 0; margin-top: 2px; }
.addr-loading { padding: 12px 14px; font-size: 0.82rem; color: var(--text-500); text-align: center; }

/* ===== UPLOAD DROPZONE ===== */
.upload-dropzone {
    position: relative;
    border: 2px dashed rgba(31,155,74,0.35);
    border-radius: 14px;
    background: var(--bg-soft);
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.upload-dropzone:hover { border-color: var(--green-600); background: rgba(31,155,74,0.06); }
.upload-dropzone.dragover { border-color: var(--green-600); background: rgba(31,155,74,0.1); box-shadow: 0 0 0 4px rgba(31,155,74,0.12); }
.upload-dropzone.is-invalid { border-color: #ef4444; }
.upload-dropzone.has-file { cursor: default; padding: 14px; }

.upload-input-hidden {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    opacity: 0; cursor: pointer; z-index: 1;
}
.upload-dropzone.has-file .upload-input-hidden { pointer-events: none; }

.upload-icon-circle {
    width: 58px; height: 58px; border-radius: 50%;
    background: linear-gradient(135deg, var(--green-600), var(--green-700));
    color: #fff; font-size: 1.6rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
    box-shadow: 0 6px 16px rgba(31,155,74,0.3);
    transition: transform 0.2s;
}
.upload-dropzone:hover .upload-icon-circle { transform: translateY(-2px); }
.upload-dropzone-text { font-size: 0.9rem; color: var(--text-900); }
.upload-dropzone-text strong { color: var(--green-700); }
.upload-dropzone-hint { font-size: 0.75rem; color: var(--text-500); margin-top: 6px; }

.upload-file-card {
    position: relative; z-index: 2;
    display: flex; align-items: center; gap: 12px;
    background: var(--card-bg);
    border: 1.5px solid rgba(15,76,150,0.15);
    border-radius: 12px; padding: 12px 14px;
    text-align: left;
}
.upload-file-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(47,116,201,0.12); color: #2f74c9;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.35rem; flex-shrink: 0;
}
.upload-file-icon.pdf { background: rgba(239,68,68,0.12); color: #dc2626; }
.upload-file-icon.excel { background: rgba(31,155,74,0.12); color: var(--green-700); }
.upload-file-icon.image { background: rgba(245,158,11,0.14); color: #b45309; }
.upload-file-info { flex: 1; min-width: 0; }
.upload-file-name {
    font-weight: 600; font-size: 0.86rem; color: var(--text-900);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.upload-file-size { font-size: 0.76rem; color: var(--text-500); margin-top: 2px; }
.upload-remove-btn {
    position: relative; z-index: 3;
    background: none; border: none; color: var(--text-500);
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s; flex-shrink: 0;
}
.upload-remove-btn:hover { background: #fef2f2; color: #dc2626; }

/* ===== BUTTONS ===== */
.btn-submit {
    background: linear-gradient(135deg, var(--green-600), var(--green-700));
    color: #fff; border: none; border-radius: 10px;
    padding: 11px 28px; font-weight: 600; font-size: 0.9rem;
    box-shadow: 0 4px 14px rgba(31,155,74,0.25);
    transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
}
.btn-submit:hover { opacity: 0.9; color: #fff; transform: translateY(-1px); }
.btn-cancel {
    background: var(--bg-soft); color: var(--text-700);
    border: 1.5px solid var(--card-border); border-radius: 10px;
    padding: 11px 20px; font-weight: 500; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-cancel:hover { background: var(--card-border); color: var(--text-900); }

.help-box {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--card-bg); border-radius: 12px; padding: 14px 20px;
    border: 2px solid transparent;
    background-image: linear-gradient(var(--card-bg), var(--card-bg)), var(--bg-gradient-brand);
    background-origin: border-box; background-clip: padding-box, border-box;
    box-shadow: 0 0 20px rgba(31,155,74,0.1), var(--shadow-soft);
}
.help-box:hover { box-shadow: 0 0 28px rgba(31,155,74,0.15), var(--shadow-hover); }
</style>
@endpush

@section('content')
<div class="portal-wrap">

    {{-- Header --}}
    <div class="container mb-4">
        <a href="{{ route('user.calibrations.index') }}" class="back-link mb-2 d-inline-block">
            <i class="bi bi-arrow-left"></i> Kembali ke Portal
        </a>
        <h1 class="fw-bold mb-1" style="font-size:1.8rem; color:var(--text-900);">Form Pengajuan Kalibrasi</h1>
        <p class="text-muted mb-0" style="font-size:0.92rem;">Lengkapi data di bawah untuk mengajukan kalibrasi alat kesehatan Anda</p>
    </div>

    <div class="container">



        {{-- ===== FORM ===== --}}
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card-form p-4 mb-4">

                    @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4" style="font-size:0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <form action="{{ route('user.calibrations.store') }}" method="POST" id="calibrationRequestForm" enctype="multipart/form-data">
                        @csrf

                        {{-- ===== DATA INSTANSI ===== --}}
                        <div class="section-title"><i class="bi bi-building me-2 text-primary"></i>Data Instansi</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Instansi <span class="req">*</span></label>
                                <input type="text" name="nama_instansi"
                                    class="form-control @error('nama_instansi') is-invalid @enderror"
                                    placeholder="RSUD / Klinik / Laboratorium ..."
                                    value="{{ old('nama_instansi') }}" required>
                                @error('nama_instansi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Kontak PIC <span class="req">*</span></label>
                                <input type="text" name="nama_kontak"
                                    class="form-control @error('nama_kontak') is-invalid @enderror"
                                    placeholder="Nama penanggung jawab"
                                    value="{{ old('nama_kontak') }}" required>
                                @error('nama_kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon <span class="req">*</span></label>
                                <input type="text" name="nomor_telepon"
                                    class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    placeholder="+62 812 xxxx xxxx"
                                    value="{{ old('nomor_telepon') }}" required>
                                @error('nomor_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="req">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="email@instansi.com"
                                    value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- ADDRESS WITH MAPS AUTOCOMPLETE --}}
                            <div class="col-12">
                                <label class="form-label">
                                    Alamat Lengkap <span class="req">*</span>
                                    <span class="opt ms-1"><i class="bi bi-map"></i> Cari via peta</span>
                                </label>
                                <div class="addr-wrapper">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:var(--bg-soft);border:1.5px solid rgba(15,76,150,0.15);border-right:none;border-radius:10px 0 0 10px;">
                                            <i class="bi bi-geo-alt-fill text-danger"></i>
                                        </span>
                                        <input type="text" id="alamatSearch"
                                            class="form-control @error('alamat_lengkap') is-invalid @enderror"
                                            placeholder="Ketik nama tempat atau jalan untuk mencari..."
                                            autocomplete="off"
                                            style="border-left:none;border-radius:0 10px 10px 0;"
                                            value="{{ old('alamat_lengkap') }}">
                                    </div>
                                    <input type="hidden" name="alamat_lengkap" id="alamatValue" value="{{ old('alamat_lengkap') }}" required>
                                    <div class="addr-suggestions" id="addrSuggestions"></div>
                                </div>
                                <div class="form-text-hint">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Ketik minimal 3 karakter untuk mencari lokasi. Pilih dari daftar yang muncul.
                                </div>
                                @error('alamat_lengkap')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- ===== METODE KALIBRASI ===== --}}
                        <div class="section-title"><i class="bi bi-geo-alt me-2 text-warning"></i>Metode & Lokasi Kalibrasi</div>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label">Metode Layanan Kalibrasi <span class="req">*</span></label>
                                <div class="d-flex gap-4 mt-1 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_kalibrasi"
                                            id="metodeKirim" value="Kirim UPTD"
                                            {{ old('metode_kalibrasi','Kirim UPTD') == 'Kirim UPTD' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="metodeKirim" style="font-size:0.9rem;cursor:pointer;">
                                            <i class="bi bi-box-seam text-primary me-1"></i> Kirim Alat ke Kantor UPTD
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_kalibrasi"
                                            id="metodeKunjungan" value="Kunjungan UPTD"
                                            {{ old('metode_kalibrasi') == 'Kunjungan UPTD' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="metodeKunjungan" style="font-size:0.9rem;cursor:pointer;">
                                            <i class="bi bi-people text-success me-1"></i> Teknisi UPTD Datang ke Lokasi
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Conditional confirm address --}}
                            <div class="col-12" id="konfirmasiAlamatWrapper" style="display:none;">
                                <div class="p-3 rounded-3" style="background:rgba(239,68,68,0.05);border:1.5px solid rgba(239,68,68,0.25);">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="konfirmasi_alamat" id="konfirmasiAlamat" value="1">
                                        <label class="form-check-label fw-semibold" for="konfirmasiAlamat"
                                            style="font-size:0.85rem;cursor:pointer;color:#dc2626;">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Saya mengonfirmasi bahwa Alamat Lengkap di atas adalah <u>lokasi fisik alat kesehatan</u> yang akan dikalibrasi
                                            <span class="req">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                      {{-- ===== DAFTAR ALAT ===== --}}
        <div class="section-title">Daftar Alat yang Dikalibrasi</div>
        <div class="mb-4">
            <label class="form-label">
                Unggah Daftar Alat <span class="text-muted fw-normal" style="font-size:0.8rem;">(Opsional)</span>
            </label>

            <div class="upload-dropzone @error('daftar_alat.*') is-invalid @enderror" id="uploadDropzone">
                <input type="file" name="daftar_alat[]" id="daftarAlatFile" multiple
                    class="upload-input-hidden">

                {{-- Empty state --}}
                <div class="upload-empty-state" id="uploadEmptyState">
                    <div class="upload-icon-circle">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div class="upload-dropzone-text">
                        <strong>Klik untuk unggah</strong> atau seret file ke sini (bisa lebih dari satu)
                    </div>
                    <div class="upload-dropzone-hint">
                        PDF, Word, Excel, JPG, PNG, atau format lainnya &middot; maksimal 10 MB per file
                    </div>
                </div>
            </div>

            {{-- Daftar file yang sudah dipilih --}}
            <div id="uploadFileList" class="mt-2"></div>

            @error('daftar_alat.*')
            <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
             
        </div>


                        {{-- ===== CATATAN ===== --}}
                        <div class="section-title"><i class="bi bi-chat-square-text me-2 text-info"></i>Catatan Tambahan</div>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label">Catatan Tambahan <span class="opt">(Opsional)</span></label>
                                <textarea name="catatan_tambahan" class="form-control"
                                    placeholder="Informasi tambahan atau permintaan khusus..."
                                    rows="2">{{ old('catatan_tambahan') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-send"></i> Kirim Pengajuan
                            </button>
                            <a href="{{ route('user.calibrations.index') }}" class="btn-cancel">Batal</a>
                        </div>
                    </form>
                </div>

                {{-- Help Box --}}
                <div class="help-box">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-telephone-fill text-success fs-4"></i>
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:0.88rem;">Butuh Bantuan atau Panduan Tambahan?</h6>
                            <p class="text-muted mb-0 small" style="font-size:0.75rem;">Tim support kami siap membantu melalui WhatsApp.</p>
                        </div>
                    </div>
                    <a href="https://api.whatsapp.com/send/?phone=6281292923438" target="_blank"
                        class="btn btn-sm btn-success rounded-pill px-3" style="font-size:0.8rem;font-weight:600;">
                        <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================
       1. METODE KALIBRASI — Conditional checklist
    ========================================== */
    const metodeKunjungan = document.getElementById('metodeKunjungan');
    const metodeKirim     = document.getElementById('metodeKirim');
    const wrapperConfirm  = document.getElementById('konfirmasiAlamatWrapper');
    const checkConfirm    = document.getElementById('konfirmasiAlamat');

    function toggleAlamatConfirm() {
        if (metodeKunjungan.checked) {
            wrapperConfirm.style.display = 'block';
            checkConfirm.required = true;
        } else {
            wrapperConfirm.style.display = 'none';
            checkConfirm.required = false;
            checkConfirm.checked  = false;
        }
    }
    metodeKunjungan.addEventListener('change', toggleAlamatConfirm);
    metodeKirim.addEventListener('change', toggleAlamatConfirm);
    toggleAlamatConfirm();

    /* ==========================================
       2. ADDRESS AUTOCOMPLETE (Photon geocoder — lebih pintar
          untuk nama tempat/POI seperti "UPTD ...", dengan
          fallback ke Nominatim untuk pencarian alamat jalan)
    ========================================== */
    const addrSearch  = document.getElementById('alamatSearch');
    const addrHidden  = document.getElementById('alamatValue');
    const addrBox     = document.getElementById('addrSuggestions');
    let addrTimer     = null;
    let addrSelected  = !!addrHidden.value; // true if pre-filled (old input)

    // Titik bias pencarian (sekitar Bandar Lampung) supaya hasil lokal lebih relevan,
    // tapi pencarian tetap tidak dibatasi hanya area ini.
    const ADDR_BIAS_LAT = -5.3971;
    const ADDR_BIAS_LON = 105.2668;

    addrSearch.addEventListener('input', function () {
        const q = this.value.trim();
        addrSelected = false;
        addrHidden.value = '';
        clearTimeout(addrTimer);
        if (q.length < 3) { addrBox.style.display = 'none'; return; }
        addrBox.innerHTML = '<div class="addr-loading"><i class="bi bi-arrow-repeat spin me-1"></i>Mencari lokasi...</div>';
        addrBox.style.display = 'block';
        addrTimer = setTimeout(() => fetchPhoton(q), 350);
    });

    function renderSuggestions(items) {
        if (!items || items.length === 0) {
            addrBox.innerHTML = '<div class="addr-loading">Lokasi tidak ditemukan.<br><small class="text-muted mt-1 d-block">Anda tetap bisa menggunakan alamat yang diketik secara manual.</small></div>';
            return;
        }
        addrBox.innerHTML = '';
        items.forEach(label => {
            const div = document.createElement('div');
            div.className = 'addr-suggestion-item';
            div.innerHTML = `<i class="bi bi-geo-alt"></i><span>${escHtml(label)}</span>`;
            div.addEventListener('mousedown', function (e) {
                e.preventDefault();
                addrSearch.value   = label;
                addrHidden.value   = label;
                addrSelected       = true;
                addrBox.style.display = 'none';
            });
            addrBox.appendChild(div);
        });
    }

    // Provider utama: Photon (bagus untuk nama tempat/POI, mis. "UPTD IFKA")
    function fetchPhoton(query) {
        const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&lat=${ADDR_BIAS_LAT}&lon=${ADDR_BIAS_LON}&location_bias_scale=0.9&limit=6&lang=id`;
        fetch(url)
            .then(r => r.json())
            .then(data => {
                const feats = (data && data.features) || [];
                if (feats.length === 0) {
                    // fallback ke Nominatim untuk pencarian alamat jalan yang lebih presisi
                    fetchNominatim(query);
                    return;
                }
                renderSuggestions(feats.map(f => formatPhotonAddress(f.properties || {})));
            })
            .catch(() => fetchNominatim(query));
    }

    function formatPhotonAddress(p) {
        const parts = [];
        if (p.name) parts.push(p.name);
        const jalan = [p.street, p.housenumber].filter(Boolean).join(' ');
        if (jalan) parts.push(jalan);
        if (p.district) parts.push(p.district);
        if (p.city) parts.push(p.city);
        else if (p.county) parts.push(p.county);
        if (p.state) parts.push(p.state);
        if (p.postcode) parts.push(p.postcode);
        if (!parts.includes('Indonesia')) parts.push('Indonesia');
        return parts.filter(Boolean).join(', ');
    }

    // Fallback: Nominatim (OpenStreetMap) — kuat untuk alamat jalan/nomor rumah
    function fetchNominatim(query) {
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=6&addressdetails=1&countrycodes=id`;
        fetch(url, { headers: { 'Accept-Language': 'id' } })
            .then(r => r.json())
            .then(data => {
                if (!data || data.length === 0) {
                    addrBox.innerHTML = '<div class="addr-loading">Lokasi tidak ditemukan.<br><small class="text-muted mt-1 d-block">Anda tetap bisa menggunakan alamat yang diketik secara manual.</small></div>';
                    return;
                }
                renderSuggestions(data.map(item => item.display_name));
            })
            .catch(() => {
                addrBox.innerHTML = '<div class="addr-loading">Gagal terhubung ke layanan peta. Anda tetap bisa mengetik alamat secara manual.</div>';
            });
    }

    // Allow manual entry: sync hidden value on blur if user typed but didn't pick
    addrSearch.addEventListener('blur', function () {
        setTimeout(() => { addrBox.style.display = 'none'; }, 150);
        if (!addrSelected && this.value.trim()) {
            addrHidden.value = this.value.trim();
        }
    });

    /* ==========================================
       3. DAFTAR ALAT — dropzone upload modern (multi-file)
    ========================================== */
    const dropzone      = document.getElementById('uploadDropzone');
    const fileInput     = document.getElementById('daftarAlatFile');
    const emptyState    = document.getElementById('uploadEmptyState');
    const fileListEl    = document.getElementById('uploadFileList');

    let selectedFiles = []; // array of File objects yang sedang dipilih

    function fileIconFor(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        if (ext === 'pdf') return { icon: 'bi-file-earmark-pdf-fill', cls: 'pdf' };
        if (['xls', 'xlsx'].includes(ext)) return { icon: 'bi-file-earmark-excel-fill', cls: 'excel' };
        if (['doc', 'docx'].includes(ext)) return { icon: 'bi-file-earmark-word-fill', cls: '' };
        if (['jpg', 'jpeg', 'png'].includes(ext)) return { icon: 'bi-file-earmark-image-fill', cls: 'image' };
        return { icon: 'bi-file-earmark-fill', cls: '' };
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function escHtml(str) {
        if (typeof str !== 'string') return String(str);
        return str.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function syncFileInput() {
        // Susun ulang FileList dari array selectedFiles (pakai DataTransfer)
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function renderFileList() {
        fileListEl.innerHTML = '';

        if (selectedFiles.length === 0) {
            emptyState.style.display = 'block';
            dropzone.classList.remove('has-file');
            return;
        }

        emptyState.style.display = 'none';
        dropzone.classList.add('has-file');
        dropzone.classList.remove('is-invalid');

        selectedFiles.forEach((file, idx) => {
            const meta = fileIconFor(file.name);
            const row = document.createElement('div');
            row.className = 'upload-file-card';
            row.style.marginBottom = '8px';
            row.innerHTML = `
                <div class="upload-file-icon ${meta.cls}"><i class="bi ${meta.icon}"></i></div>
                <div class="upload-file-info">
                    <div class="upload-file-name">${escHtml(file.name)}</div>
                    <div class="upload-file-size">${formatSize(file.size)}</div>
                </div>
                <button type="button" class="upload-remove-btn" data-idx="${idx}" title="Hapus file">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
            fileListEl.appendChild(row);
        });

        fileListEl.querySelectorAll('.upload-remove-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const idx = parseInt(this.dataset.idx, 10);
                selectedFiles.splice(idx, 1);
                syncFileInput();
                renderFileList();
            });
        });
    }

    function addFiles(fileListLike) {
        const incoming = Array.from(fileListLike);
        const rejected = [];

        for (const file of incoming) {
            if (file.size > 10 * 1024 * 1024) {
                rejected.push(file.name);
                continue;
            }
            selectedFiles.push(file);
        }

        if (rejected.length > 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Tidak bisa mengirim file karena terlalu besar',
                    html: rejected.length === 1
                        ? `File <strong>${escHtml(rejected[0])}</strong> melebihi batas maksimal 10 MB.`
                        : `${rejected.length} file melebihi batas maksimal 10 MB:<br><strong>${rejected.map(escHtml).join(', ')}</strong>`,
                    icon: 'error',
                    confirmButtonText: 'Mengerti',
                    customClass: {
                        popup: 'swal-delete-popup',
                        title: 'swal-delete-title',
                        confirmButton: 'swal-confirm-btn'
                    },
                    buttonsStyling: false
                });
            } else {
                alert('Tidak bisa mengirim file karena terlalu besar (maksimal 10 MB per file): ' + rejected.join(', '));
            }
        }

        syncFileInput();
        renderFileList();
    }

    fileInput.addEventListener('change', function () {
        if (this.files && this.files.length > 0) {
            addFiles(this.files);
        }
    });

    // Drag & drop
    ['dragenter', 'dragover'].forEach(evt => {
        dropzone.addEventListener(evt, function (e) {
            e.preventDefault(); e.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropzone.addEventListener(evt, function (e) {
            e.preventDefault(); e.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });
    dropzone.addEventListener('drop', function (e) {
        const dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length > 0) {
            addFiles(dt.files);
        }
    });
});
</script>
<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { display: inline-block; animation: spin 1s linear infinite; }
</style>
@endpush
