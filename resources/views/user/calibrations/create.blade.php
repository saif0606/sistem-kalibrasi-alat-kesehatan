@extends('layouts.app')

@section('title', 'Ajukan Kalibrasi - UPTD')

@push('styles')
<style>
/* ===== WRAP ===== */
.portal-wrap {
    padding: 130px 0 80px;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
    color: #0c2438;
}

/* Floating ambient blobs */
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    opacity: 0.35;
    z-index: 0;
    pointer-events: none;
}
.blob-1 { width: 380px; height: 380px; top: 0px; left: -100px; background: radial-gradient(circle, #22c07a, transparent 70%); }
.blob-2 { width: 420px; height: 420px; top: 300px; right: -140px; background: radial-gradient(circle, #4d8bff, transparent 70%); animation: blobFloat 14s ease-in-out infinite; }
.blob-3 { width: 300px; height: 300px; bottom: 100px; left: 35%; background: radial-gradient(circle, #17a45c, transparent 70%); animation: blobFloat 18s ease-in-out infinite reverse; }
@keyframes blobFloat { 0%,100%{ transform:translateY(0px);} 50%{ transform:translateY(30px);} }
@media (prefers-reduced-motion: reduce){ .blob-2, .blob-3{ animation:none; } }

.back-link {
    color: var(--color-text-muted, #64748B); text-decoration: none;
    font-size: 0.82rem; display: inline-flex; align-items: center;
    gap: 5px; transition: color 0.2s;
}
.back-link:hover { color: var(--color-primary-green, #16A34A); }

/* ===== STEP FLOW CARD ===== */
.flow-card {
    background: #fff;
    border-radius: var(--radius-md, 12px);
    border: 2px solid transparent;
    background-image: linear-gradient(#fff, #fff), linear-gradient(135deg, #16A34A, #2563EB);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    box-shadow: 0 0 25px rgba(47,116,201,0.12), 0 4px 20px rgba(30, 41, 59, 0.06);
    overflow: hidden;
}

/* ===== FORM CARD ===== */
.card-form {
    background: rgba(255,255,255,0.65);
    backdrop-filter: blur(22px) saturate(150%);
    -webkit-backdrop-filter: blur(22px) saturate(150%);
    border: 1px solid rgba(255,255,255,0.85)!important;
    border-radius: 20px;
    color: var(--color-heading, #0F172A);
    box-shadow: 0 10px 40px rgba(15,60,50,0.08);
    transition: box-shadow 0.3s;
    position: relative;
    z-index: 1;
}
.card-form:hover { box-shadow: 0 0 35px rgba(22,163,74,0.08), 0 10px 30px -12px rgba(30, 41, 59, 0.15); }
.section-title {
    font-size: 0.95rem; font-weight: 700; color: var(--color-heading, #0F172A);
    margin-bottom: 16px; padding-bottom: 10px;
    border-bottom: 2px solid var(--color-border, #E2E8F0);
}
.form-label { font-weight: 600; font-size: 0.85rem; color: var(--color-heading, #0F172A); margin-bottom: 6px; }
.form-label .req { color: #ef4444; }
.form-label .opt { color: var(--color-text-muted, #64748B); font-weight: 400; font-size: 0.78rem; }
.form-control, .form-select {
    border-radius: 10px;
    border: 1.5px solid rgba(15,76,150,0.15);
    padding: 10px 14px;
    font-size: 0.9rem;
    background-color: #fff;
    color: var(--color-heading, #0F172A);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control::placeholder { color: var(--color-text-muted, #64748B); opacity: 0.7; }
.form-control:focus, .form-select:focus {
    border-color: var(--color-primary-green, #16A34A);
    box-shadow: 0 0 0 3px rgba(22,163,74,0.15);
    background-color: #fff; color: var(--color-heading, #0F172A);
}
.form-text-hint { font-size: 0.75rem; color: var(--color-text-muted, #64748B); margin-top: 5px; }

/* ===== ADDRESS AUTOCOMPLETE ===== */
.addr-wrapper { position: relative; }
.addr-suggestions {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff;
    border: 1.5px solid rgba(15,76,150,0.15);
    border-radius: 10px;
    z-index: 999;
    max-height: 220px; overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    display: none;
}
.addr-suggestion-item {
    padding: 10px 14px; cursor: pointer;
    font-size: 0.85rem; color: var(--color-heading, #0F172A);
    border-bottom: 1px solid var(--color-border, #E2E8F0);
    transition: background 0.15s;
    display: flex; align-items: flex-start; gap: 8px;
}
.addr-suggestion-item:last-child { border-bottom: none; }
.addr-suggestion-item:hover { background: var(--color-bg-alt, #F8FAFC); }
.addr-suggestion-item i { color: var(--color-primary-green, #16A34A); flex-shrink: 0; margin-top: 2px; }
.addr-loading { padding: 12px 14px; font-size: 0.82rem; color: var(--color-text-muted, #64748B); text-align: center; }

/* ===== UPLOAD DROPZONE ===== */
.upload-dropzone {
    position: relative;
    border: 2px dashed rgba(22,163,74,0.35);
    border-radius: 14px;
    background: var(--color-bg-alt, #F8FAFC);
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.upload-dropzone:hover { border-color: var(--color-primary-green, #16A34A); background: rgba(22,163,74,0.06); }
.upload-dropzone.dragover { border-color: var(--color-primary-green, #16A34A); background: rgba(22,163,74,0.1); box-shadow: 0 0 0 4px rgba(22,163,74,0.12); }
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
    background: linear-gradient(135deg, var(--color-primary-green, #16A34A), var(--color-primary-green-dark, #12793A));
    color: #fff; font-size: 1.6rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
    box-shadow: 0 6px 16px rgba(22,163,74,0.3);
    transition: transform 0.2s;
}
.upload-dropzone:hover .upload-icon-circle { transform: translateY(-2px); }
.upload-dropzone-text { font-size: 0.9rem; color: var(--color-heading, #0F172A); }
.upload-dropzone-text strong { color: var(--color-primary-green-dark, #12793A); }
.upload-dropzone-hint { font-size: 0.75rem; color: var(--color-text-muted, #64748B); margin-top: 6px; }

.upload-file-card {
    position: relative; z-index: 2;
    display: flex; align-items: center; gap: 12px;
    background: #fff;
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
.upload-file-icon.excel { background: rgba(22,163,74,0.12); color: var(--color-primary-green-dark, #12793A); }
.upload-file-icon.image { background: rgba(245,158,11,0.14); color: #b45309; }
.upload-file-info { flex: 1; min-width: 0; }
.upload-file-name {
    font-weight: 600; font-size: 0.86rem; color: var(--color-heading, #0F172A);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.upload-file-size { font-size: 0.76rem; color: var(--color-text-muted, #64748B); margin-top: 2px; }
.upload-remove-btn {
    position: relative; z-index: 3;
    background: none; border: none; color: var(--color-text-muted, #64748B);
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s; flex-shrink: 0;
}
.upload-remove-btn:hover { background: #fef2f2; color: #dc2626; }

/* ===== BUTTONS ===== */
.btn-submit {
    background: linear-gradient(135deg, var(--color-primary-green, #16A34A), var(--color-primary-green-dark, #12793A));
    color: #fff; border: none; border-radius: 10px;
    padding: 11px 28px; font-weight: 600; font-size: 0.9rem;
    box-shadow: 0 4px 14px rgba(22,163,74,0.25);
    transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
}
.btn-submit:hover { opacity: 0.9; color: #fff; transform: translateY(-1px); }
.btn-cancel {
    background: var(--color-bg-alt, #F8FAFC); color: #334155;
    border: 1.5px solid var(--color-border, #E2E8F0); border-radius: 10px;
    padding: 11px 20px; font-weight: 500; font-size: 0.9rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-cancel:hover { background: var(--color-border, #E2E8F0); color: #0F172A; }

.help-box {
    display: flex; align-items: center; justify-content: space-between;
    background: #fff; border-radius: 12px; padding: 14px 20px;
    border: 1px solid var(--color-border, #E2E8F0);
    box-shadow: 0 0 20px rgba(22,163,74,0.1), 0 4px 20px rgba(30, 41, 59, 0.06);
}
.help-box:hover { box-shadow: 0 0 28px rgba(22,163,74,0.15), 0 10px 30px -12px rgba(30, 41, 59, 0.15); }
</style>
@endpush

@section('content')
<div class="portal-wrap">
    {{-- Ambient Background Blobs --}}
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    {{-- Header --}}
    <div class="container mb-4 position-relative z-1">
        <a href="{{ route('user.calibrations.index') }}" class="back-link mb-2 d-inline-block">
            <i class="bi bi-arrow-left"></i> Kembali ke Portal
        </a>
        <h1 class="fw-bold mb-1" style="font-size:1.8rem; color:var(--text-900);">Form Pengajuan Kalibrasi</h1>
        <p class="text-muted mb-0" style="font-size:0.92rem;">Lengkapi data di bawah untuk mengajukan kalibrasi alat kesehatan Anda</p>
    </div>

    <div class="container position-relative z-1">



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
                                <label class="form-label">Nama PIC / Jabatan <span class="req">*</span></label>
                                <input type="text" name="nama_kontak"
                                    class="form-control @error('nama_kontak') is-invalid @enderror"
                                    placeholder="Nama penanggung jawab dan jabatan"
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

                            {{-- MANUAL ADDRESS --}}
                            <div class="col-12">
                                <label class="form-label">
                                    Alamat Lengkap <span class="req">*</span>
                                </label>
                                <textarea name="alamat_lengkap" id="alamatValue"
                                    class="form-control @error('alamat_lengkap') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap..."
                                    required>{{ old('alamat_lengkap') }}</textarea>
                                @error('alamat_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                      {{-- ===== DAFTAR ALAT & DOKUMEN ===== --}}
        <div class="section-title">Dokumen Pengajuan dan Daftar alat yang dikalibrasi</div>
        <div class="mb-4">
            <label class="form-label">
                Unggah Dokumen <span class="text-danger fw-normal" style="font-size:0.8rem;">(Wajib, minimal 1 file PDF)</span>
            </label>

            <div class="upload-dropzone @error('daftar_alat.*') is-invalid @enderror" id="uploadDropzone">
                <input type="file" name="daftar_alat[]" id="daftarAlatFile" multiple accept="application/pdf"
                    class="upload-input-hidden" required>

                {{-- Empty state --}}
                <div class="upload-empty-state" id="uploadEmptyState">
                    <div class="upload-icon-circle">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div class="upload-dropzone-text">
                        <strong>Klik untuk unggah</strong> atau seret file ke sini (bisa lebih dari satu)<br>
                        <span style="font-size: 0.85rem; font-weight: bold; color: var(--text-500); display: block; margin-top: 6px;">Wajib menyertakan dokumen pengajuan resmi dan daftar alat yang akan dikalibrasi beserta jumlahnya</span>
                    </div>
                    <div class="upload-dropzone-hint mt-2">
                        Hanya file PDF &middot; maksimal 10 MB per file
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
