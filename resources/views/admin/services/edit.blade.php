@extends('admin.layouts.app')

@section('title', 'Edit Layanan')
@section('page_title', 'Edit Layanan')
@section('page_subtitle', 'Perbarui data layanan kalibrasi')

@section('content')
<style>
.svc-form-card {
    background:var(--bg-card,#fff); border-radius:16px;
    border:1px solid var(--border-color,rgba(0,0,0,0.07));
    box-shadow:0 2px 12px rgba(0,0,0,0.05); overflow:hidden; margin-bottom:20px;
}
.svc-form-section { padding:22px 24px; border-bottom:1px solid var(--border-color,rgba(0,0,0,0.07)); }
.svc-form-section:last-child { border-bottom:none; }
.svc-section-label {
    font-size:10.5px; font-weight:700; letter-spacing:0.09em; text-transform:uppercase;
    color:var(--green-600,#17a45c); margin-bottom:16px; display:flex; align-items:center; gap:7px;
}
.svc-input {
    width:100%; padding:11px 14px; border:1.5px solid var(--border-color,#e2e8f0);
    border-radius:10px; font-size:14px; transition:border-color 0.18s, box-shadow 0.18s, background 0.18s;
    background:var(--bg-input,#fafafa); color:var(--text-primary,#0c2438); font-family:inherit; outline:none;
}
.svc-input:focus { border-color:#17a45c; box-shadow:0 0 0 3px rgba(23,164,92,0.11); background:var(--bg-card,#fff); }
.svc-input.is-invalid { border-color:#ef4444; }
.svc-label { display:block; font-size:13px; font-weight:600; color:var(--text-primary,#0c2438); margin-bottom:6px; }
.price-wrap { position:relative; }
.price-prefix { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:14px; font-weight:600; color:#94a3b8; pointer-events:none; }
.price-wrap .svc-input { padding-left:40px; }
.drop-zone {
    border:2px dashed var(--border-color,#e2e8f0); border-radius:12px;
    padding:22px 16px; text-align:center; cursor:pointer; transition:all 0.18s; position:relative; overflow:hidden;
}
.drop-zone:hover, .drop-zone.drag-over { border-color:#17a45c; background:rgba(23,164,92,0.04); }
.drop-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.kan-toggle-wrap {
    display:flex; align-items:center; gap:14px; padding:14px 18px;
    background:rgba(23,164,92,0.05); border-radius:12px; cursor:pointer;
    border:1.5px solid transparent; transition:all 0.18s;
}
.kan-toggle-wrap:hover { border-color:rgba(23,164,92,0.3); }
.kan-toggle-wrap.active { background:rgba(23,164,92,0.1); border-color:rgba(23,164,92,0.4); }
.toggle-switch {
    width:44px; height:24px; background:#e2e8f0; border-radius:12px;
    position:relative; transition:background 0.2s; flex-shrink:0;
}
.toggle-switch::after {
    content:''; width:20px; height:20px; background:#fff; border-radius:50%;
    position:absolute; top:2px; left:2px; transition:transform 0.2s; box-shadow:0 1px 4px rgba(0,0,0,0.15);
}
.kan-toggle-wrap.active .toggle-switch { background:#17a45c; }
.kan-toggle-wrap.active .toggle-switch::after { transform:translateX(20px); }
.preview-panel {
    background:var(--bg-card,#fff); border-radius:16px;
    border:1px solid var(--border-color,rgba(0,0,0,0.07));
    box-shadow:0 2px 12px rgba(0,0,0,0.05); padding:22px 24px; position:sticky; top:20px;
}
.preview-box {
    width:100%; aspect-ratio:1/1; border-radius:12px; overflow:hidden;
    background:linear-gradient(135deg,#17a45c,#2b6ff0);
    display:flex; align-items:center; justify-content:center; position:relative;
}
.preview-box img.cover { width:100%;height:100%;object-fit:cover;position:absolute;inset:0; }
.preview-box img.logo-default { width:auto;height:58px;object-fit:contain;position:relative; }
.btn-save {
    background:linear-gradient(135deg,#17a45c,#0d8f4e); color:#fff; border:none;
    border-radius:12px; padding:12px 28px; font-weight:700; font-size:14px;
    cursor:pointer; transition:all 0.2s; box-shadow:0 4px 12px rgba(23,164,92,0.28);
    display:inline-flex; align-items:center; gap:7px;
}
.btn-save:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(23,164,92,0.38); color:#fff; }
.btn-cancel {
    display:inline-flex; align-items:center; gap:7px;
    background:transparent; border:1.5px solid var(--border-color,#e2e8f0);
    border-radius:12px; padding:11px 22px; font-weight:600; font-size:14px;
    color:var(--text-secondary,#64748b); text-decoration:none; transition:all 0.18s;
}
.btn-cancel:hover { border-color:#94a3b8; color:var(--text-primary); }
.invalid-msg { font-size:12px; color:#ef4444; margin-top:4px; }
.existing-thumb-wrap {
    display:flex; align-items:center; gap:12px; padding:12px;
    background:rgba(23,164,92,0.06); border-radius:10px; margin-bottom:12px;
}
</style>

<form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">
        {{-- LEFT --}}
        <div class="col-lg-8">
            <div class="svc-form-card">
                <div class="svc-form-section">
                    <div class="svc-section-label"><i class="bi bi-tools"></i> Informasi Layanan</div>

                    <div class="mb-4">
                        <label class="svc-label">Nama Layanan <span style="color:#ef4444">*</span></label>
                        <input type="text" name="name" id="svcName"
                            class="svc-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $service->name) }}" placeholder="Nama layanan..." required>
                        @error('name')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="svc-label">Harga (Rp) <span style="color:#ef4444">*</span></label>
                        <div class="price-wrap">
                            <span class="price-prefix">Rp</span>
                            <input type="number" step="1" min="0" name="price" id="svcPrice"
                                class="svc-input @error('price') is-invalid @enderror"
                                value="{{ old('price', $service->price) }}" placeholder="0" required>
                        </div>
                        @error('price')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="svc-form-section">
                    <div class="svc-section-label"><i class="bi bi-text-paragraph"></i> Deskripsi</div>
                    <textarea name="description" rows="5"
                        class="svc-input @error('description') is-invalid @enderror"
                        placeholder="Jelaskan layanan ini secara singkat...">{{ old('description', $service->description) }}</textarea>
                    @error('description')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="svc-form-card">
                <div class="svc-form-section">
                    <div class="svc-section-label"><i class="bi bi-image"></i> Gambar Layanan</div>

                    @if($service->image)
                    <div class="existing-thumb-wrap">
                        <img src="{{ asset('storage/'.$service->image) }}" width="60" height="60"
                            style="object-fit:cover;border-radius:8px;flex-shrink:0;" alt="current">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Gambar saat ini</div>
                            <div style="font-size:11.5px;color:#94a3b8;margin-top:2px;">Upload baru untuk mengganti</div>
                        </div>
                    </div>
                    @endif

                    <div class="drop-zone" id="dropZone">
                        <input type="file" name="image" id="imgInput" accept="image/*" onchange="handleUpload(this)">
                        <div id="dropContent">
                            <div style="font-size:30px;color:#17a45c;margin-bottom:8px;"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">
                                {{ $service->image ? 'Seret & lepas untuk ganti gambar' : 'Seret & lepas gambar di sini' }}
                            </div>
                            <div style="font-size:12px;color:#94a3b8;margin-top:4px;">atau klik untuk browse — PNG, JPG, WEBP · maks 2MB</div>
                        </div>
                    </div>
                    @error('image')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>

                <div class="svc-form-section">
                    <div class="svc-section-label"><i class="bi bi-patch-check"></i> Sertifikasi</div>
                    <label for="kanToggleLabel">
                        <div class="kan-toggle-wrap {{ old('is_kan', $service->is_kan) ? 'active' : '' }}" id="kanWrap">
                            <input type="hidden" name="is_kan" id="isKanHidden" value="{{ old('is_kan', $service->is_kan) ? '1' : '0' }}">
                            <div class="toggle-switch" id="kanSwitch"></div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:var(--text-primary);">Bersertifikat KAN</div>
                                <div style="font-size:12px;color:#94a3b8;margin-top:2px;">Layanan ini telah memperoleh akreditasi KAN</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 pb-2">
                <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.services.index') }}" class="btn-cancel">Batal</a>
            </div>
        </div>

        {{-- RIGHT: Preview --}}
        <div class="col-lg-4">
            <div class="preview-panel">
                <div class="svc-section-label mb-3"><i class="bi bi-eye"></i> Preview Gambar</div>
                <div class="preview-box" id="previewBox">
                    @if($service->image)
                        <img id="previewImg" class="cover" src="{{ asset('storage/'.$service->image) }}" alt="preview">
                    @else
                        <img id="previewImg" class="logo-default" src="{{ asset('images/logo-white.png') }}" alt="preview">
                    @endif
                </div>
                <div id="previewCaption" style="font-size:11.5px;color:#94a3b8;text-align:center;margin-top:8px;">
                    {{ $service->image ? 'Gambar saat ini' : 'Menggunakan logo UPTD (default)' }}
                </div>

                <div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border-color,#e2e8f0);">
                    <button type="button" onclick="clearUpload()" id="clearBtn"
                        style="font-size:12px;color:#94a3b8;background:none;border:none;cursor:pointer;padding:0;"
                        class="d-none">
                        <i class="bi bi-x-circle me-1"></i>Hapus gambar baru
                    </button>
                </div>

                <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border-color,#e2e8f0);">
                    <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;margin-bottom:10px;">Info Layanan</div>
                    <div id="previewName" style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $service->name }}</div>
                    <div id="previewPrice" style="font-size:13px;color:#17a45c;font-weight:700;margin-top:4px;">
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
const LOGO_SRC  = '{{ asset("images/logo-white.png") }}';
const EXIST_SRC = '{{ $service->image ? asset("storage/".$service->image) : "" }}';
let uploadSrc = null;

// KAN toggle
const kanWrap = document.getElementById('kanWrap');
const kanHid  = document.getElementById('isKanHidden');
let kanOn = kanHid.value === '1';
function syncKan() { kanWrap.classList.toggle('active', kanOn); kanHid.value = kanOn ? '1' : '0'; }
kanWrap.addEventListener('click', () => { kanOn = !kanOn; syncKan(); });
syncKan();

// Live preview
document.getElementById('svcName').addEventListener('input', function() {
    document.getElementById('previewName').textContent = this.value || '{{ $service->name }}';
});
document.getElementById('svcPrice').addEventListener('input', function() {
    const n = parseInt(this.value);
    document.getElementById('previewPrice').textContent = n ? 'Rp ' + n.toLocaleString('id-ID') : '';
});

function handleUpload(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        uploadSrc = e.target.result;
        const name = input.files[0].name;
        document.getElementById('dropContent').innerHTML =
            `<div style="font-size:22px;color:#17a45c"><i class="bi bi-check-circle-fill"></i></div>
             <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-top:6px">${name}</div>
             <div style="font-size:11px;color:#94a3b8;margin-top:3px">Klik untuk ganti gambar</div>`;
        refreshPreview();
    };
    reader.readAsDataURL(input.files[0]);
}

function refreshPreview() {
    const img     = document.getElementById('previewImg');
    const caption = document.getElementById('previewCaption');
    const clearBtn= document.getElementById('clearBtn');
    if (uploadSrc) {
        img.className = 'cover'; img.src = uploadSrc;
        caption.textContent = 'Gambar baru (upload)'; clearBtn.classList.remove('d-none');
    } else if (EXIST_SRC) {
        img.className = 'cover'; img.src = EXIST_SRC;
        caption.textContent = 'Gambar saat ini'; clearBtn.classList.add('d-none');
    } else {
        img.className = 'logo-default'; img.src = LOGO_SRC;
        caption.textContent = 'Menggunakan logo UPTD (default)'; clearBtn.classList.add('d-none');
    }
}

function clearUpload() {
    uploadSrc = null;
    document.getElementById('imgInput').value = '';
    document.getElementById('dropContent').innerHTML =
        `<div style="font-size:30px;color:#17a45c;margin-bottom:8px"><i class="bi bi-cloud-arrow-up"></i></div>
         <div style="font-size:14px;font-weight:600;color:var(--text-primary)">Seret & lepas gambar di sini</div>
         <div style="font-size:12px;color:#94a3b8;margin-top:4px">atau klik untuk browse — PNG, JPG, WEBP · maks 2MB</div>`;
    refreshPreview();
}

const dz = document.getElementById('dropZone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.classList.remove('drag-over');
    const f = e.dataTransfer.files[0];
    if (f && f.type.startsWith('image/')) {
        const inp = document.getElementById('imgInput');
        const dt = new DataTransfer(); dt.items.add(f); inp.files = dt.files;
        handleUpload(inp);
    }
});
</script>
@endpush
@endsection