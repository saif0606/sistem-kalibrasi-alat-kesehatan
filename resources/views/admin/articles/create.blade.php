@extends('admin.layouts.app')

@section('title', 'Tambah Artikel')
@section('page_title', 'Tambah Artikel')
@section('page_subtitle', 'Buat konten berita, pengumuman, atau edukasi baru')

@section('content')
<style>
.art-form-card {
    background: var(--bg-card, #fff);
    border-radius: 16px;
    border: 1px solid var(--border-color, rgba(0,0,0,0.07));
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 20px;
}
.art-form-section {
    padding: 22px 24px;
    border-bottom: 1px solid var(--border-color, rgba(0,0,0,0.07));
}
.art-form-section:last-child { border-bottom: none; }
.art-section-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--green-600, #17a45c);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.art-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
    background: var(--bg-input, #fafafa);
    color: var(--text-primary, #0c2438);
    font-family: inherit;
    outline: none;
}
.art-input:focus {
    border-color: #17a45c;
    box-shadow: 0 0 0 3px rgba(23,164,92,0.11);
    background: var(--bg-card, #fff);
}
.art-input.is-invalid { border-color: #ef4444; }
.art-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2317a45c' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 38px;
    cursor: pointer;
}
.art-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary, #0c2438);
    margin-bottom: 6px;
}
.cat-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 2px; }
.cat-pill {
    padding: 6px 16px;
    border-radius: 20px;
    border: 1.5px solid var(--border-color, #e2e8f0);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.16s;
    background: transparent;
    color: var(--text-secondary, #64748b);
}
.cat-pill.active, .cat-pill:hover {
    border-color: #17a45c;
    background: rgba(23,164,92,0.09);
    color: #17a45c;
}
.drop-zone {
    border: 2px dashed var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 22px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.18s;
    position: relative;
    overflow: hidden;
}
.drop-zone:hover, .drop-zone.drag-over {
    border-color: #17a45c;
    background: rgba(23,164,92,0.04);
}
.drop-zone input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
    width: 100%; height: 100%;
}
.link-status {
    display: none;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    margin-top: 8px;
    width: fit-content;
}
.link-status.fetching { display:flex; background:rgba(23,164,92,0.1); color:#17a45c; }
.link-status.success  { display:flex; background:rgba(23,164,92,0.1); color:#17a45c; }
.link-status.failed   { display:flex; background:rgba(239,68,68,0.1); color:#ef4444; }
/* Preview panel */
.preview-panel {
    background: var(--bg-card, #fff);
    border-radius: 16px;
    border: 1px solid var(--border-color, rgba(0,0,0,0.07));
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    padding: 22px 24px;
    position: sticky;
    top: 20px;
}
.preview-box {
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #17a45c, #2b6ff0);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.preview-box img.cover {
    width: 100%; height: 100%; object-fit: cover;
    position: absolute; inset: 0;
}
.preview-box img.logo-default {
    width: auto; height: 58px;
    object-fit: contain; position: relative;
}
.source-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--border-color, #e2e8f0); flex-shrink: 0;
    transition: background 0.2s;
}
.source-dot.active { background: #17a45c; }
.btn-save {
    background: linear-gradient(135deg, #17a45c, #0d8f4e);
    color: #fff; border: none; border-radius: 12px;
    padding: 12px 28px; font-weight: 700; font-size: 14px;
    cursor: pointer; transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(23,164,92,0.28);
    display: inline-flex; align-items: center; gap: 7px;
}
.btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(23,164,92,0.38); color: #fff; }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 7px;
    background: transparent; border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 12px; padding: 11px 22px; font-weight: 600; font-size: 14px;
    color: var(--text-secondary, #64748b); text-decoration: none; transition: all 0.18s;
}
.btn-cancel:hover { border-color: #94a3b8; color: var(--text-primary); }
.link-input-wrap { position: relative; flex: 1; }
.link-input-wrap .link-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 15px; pointer-events: none;
}
.link-input-wrap .art-input { padding-left: 38px; }
.invalid-msg { font-size: 12px; color: #ef4444; margin-top: 4px; }
</style>

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="artForm">
    @csrf

    <div class="row g-4">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">
            {{-- Info Utama --}}
            <div class="art-form-card">
                <div class="art-form-section">
                    <div class="art-section-label"><i class="bi bi-pencil-square"></i> Informasi Utama</div>

                    <div class="mb-4">
                        <label class="art-label">Judul Artikel <span style="color:#ef4444">*</span></label>
                        <input type="text" name="title" id="artTitle"
                            class="art-input @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="Masukkan judul artikel..." required>
                        @error('title')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="art-label">Kategori <span style="color:#ef4444">*</span></label>
                        <div class="cat-pills">
                            @foreach(['Instagram', 'Youtube', 'Pengumuman', 'Edukasi', 'Dokumentasi'] as $cat)
                                <button type="button"
                                    class="cat-pill {{ (old('category', 'Instagram') === $cat) ? 'active' : '' }}"
                                    onclick="pickCat(this, '{{ $cat }}')">{{ $cat }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="category" id="catInput" value="{{ old('category', 'Instagram') }}" required>
                        @error('category')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="art-form-section">
                    <div class="art-section-label"><i class="bi bi-text-paragraph"></i> Konten</div>
                    <textarea name="content" rows="8"
                        class="art-input @error('content') is-invalid @enderror"
                        placeholder="Tulis isi artikel di sini..." required>{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Media --}}
            <div class="art-form-card">
                <div class="art-form-section">
                    <div class="art-section-label"><i class="bi bi-link-45deg"></i> Link Sumber Eksternal</div>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="link-input-wrap">
                            <i class="bi bi-globe link-icon"></i>
                            <input type="url" name="link_url" id="linkUrl"
                                class="art-input @error('link_url') is-invalid @enderror"
                                value="{{ old('link_url') }}"
                                placeholder="https://instagram.com/... (opsional)">
                        </div>
                        <button type="button" onclick="doFetchOg()"
                            style="background:rgba(23,164,92,0.1); color:#17a45c; border:none; border-radius:10px; padding:10px 16px; font-size:13px; font-weight:600; white-space:nowrap; cursor:pointer; transition:all 0.18s;"
                            onmouseover="this.style.background='rgba(23,164,92,0.18)'"
                            onmouseout="this.style.background='rgba(23,164,92,0.1)'">
                            <i class="bi bi-search me-1"></i>Ambil Gambar
                        </button>
                    </div>
                    <div id="linkStatus" class="link-status mt-2"></div>
                    @error('link_url')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>

                <div class="art-form-section">
                    <div class="art-section-label"><i class="bi bi-image"></i> Upload Gambar Manual</div>
                    <div class="drop-zone" id="dropZone">
                        <input type="file" name="image" id="imgInput" accept="image/*" onchange="handleUpload(this)">
                        <div id="dropContent">
                            <div style="font-size:30px; color:#17a45c; margin-bottom:8px;"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div style="font-size:14px; font-weight:600; color:var(--text-primary);">Seret & lepas gambar di sini</div>
                            <div style="font-size:12px; color:#94a3b8; margin-top:4px;">atau klik untuk browse â€” PNG, JPG, WEBP Â· maks 5MB</div>
                        </div>
                    </div>
                    @error('image')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
            </div>



            {{-- Actions --}}
            <div class="d-flex align-items-center gap-3 pb-2">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Simpan Artikel
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn-cancel">
                    Batal
                </a>
            </div>
        </div>

        {{-- RIGHT: Preview --}}
        <div class="col-lg-4">
            <div class="preview-panel">
                <div class="art-section-label mb-3"><i class="bi bi-eye"></i> Preview Tampilan</div>

                <div class="card-mockup" style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#fff; box-shadow:0 4px 6px rgba(0,0,0,0.05);">
                    <div class="preview-box" id="previewBox" style="height:180px; background:#f8fafc; display:flex; align-items:center; justify-content:center; position:relative;">
                        <img id="previewImg" class="logo-default" src="{{ asset('images/logo-white.png') }}" alt="preview" style="max-width:100%; max-height:100%; padding:20px;">
                    </div>
                    <div style="padding:16px;">
                        <span id="previewCat" style="display:inline-block; font-size:11px; font-weight:700; color:#17a45c; background:rgba(23,164,92,0.1); padding:4px 10px; border-radius:12px; margin-bottom:10px;">Berita</span>
                        <div id="previewTitle" style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:8px; line-height:1.4;">Judul Artikel</div>
                        <div id="previewSubtitle" style="font-size:13px; color:#64748b; line-height:1.5;">Ringkasan / deskripsi artikel akan tampil di sini...</div>
                        <hr style="border:0; border-top:1px dashed #cbd5e1; margin:16px 0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:12px;">
                            <span style="color:#94a3b8;"><i class="bi bi-clock"></i> Baru saja</span>
                            <span style="color:#17a45c; font-weight:700;">Baca Selengkapnya &rarr;</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:20px; text-align:center;">
                    <div style="font-size:12px;color:#94a3b8;font-weight:600;margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">
                        SUMBER THUMBNAIL
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:16px;font-size:13px;color:#64748b;">
                        <span style="display:flex;align-items:center;gap:4px;"><span class="source-dot" id="srcDot1"></span> Upload</span>
                        <span style="display:flex;align-items:center;gap:4px;"><span class="source-dot" id="srcDot2"></span> Link URL</span>
                        <span style="display:flex;align-items:center;gap:4px;"><span class="source-dot active" id="srcDot3"></span> Default</span>
                    </div>
                </div>

                <div style="margin-top:16px; padding-top:14px; border-top:1px solid var(--border-color,#e2e8f0);">
                    <button type="button" onclick="clearUpload()"
                        style="font-size:12px; color:#94a3b8; background:none; border:none; cursor:pointer; padding:0;"
                        id="clearBtn" class="d-none">
                        <i class="bi bi-x-circle me-1"></i>Hapus gambar upload
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
const LOGO_SRC = '{{ asset("images/logo-white.png") }}';
let uploadSrc = null;
let ogSrc = null;
const OG_URL = '{{ route("admin.articles.fetch-og") }}';

const titleInput = document.getElementById('artTitle');
const previewTitle = document.getElementById('previewTitle');
titleInput.addEventListener('input', () => {
    previewTitle.textContent = titleInput.value || 'Judul Artikel';
});

const previewCat = document.getElementById('previewCat');
const origPickCat = window.pickCat;
window.pickCat = function(btn, cat) {
    if(origPickCat) origPickCat(btn, cat);
    if(previewCat) previewCat.textContent = cat;
};

const contentInput = document.getElementById('artContent');
const previewSubtitle = document.getElementById('previewSubtitle');
if (contentInput) {
    contentInput.addEventListener('input', () => {
        let txt = contentInput.value.trim();
        if(txt.length > 60) txt = txt.substring(0, 60) + '...';
        previewSubtitle.textContent = txt || 'Ringkasan / deskripsi artikel akan tampil di sini...';
    });
}

function handleUpload(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        uploadSrc = e.target.result;
        ogSrc = null; document.getElementById('linkUrl').value = '';
        const name = input.files[0].name;
        document.getElementById('dropContent').innerHTML =
            <div style="font-size:22px;color:#17a45c"><i class="bi bi-check-circle-fill"></i></div>
             <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-top:6px"> + name + </div>
             <div style="font-size:11px;color:#94a3b8;margin-top:3px">Klik untuk ganti gambar</div>;
        refreshPreview();
    };
    reader.readAsDataURL(input.files[0]);
}

function clearUpload() {
    uploadSrc = null;
    ogSrc = null;
    document.getElementById('imgInput').value = '';
    document.getElementById('linkUrl').value = '';
    document.getElementById('dropContent').innerHTML =
        <div style="font-size:30px;color:#17a45c;margin-bottom:8px"><i class="bi bi-cloud-arrow-up"></i></div>
         <div style="font-size:14px;font-weight:600;color:var(--text-primary)">Seret & lepas gambar di sini</div>
         <div style="font-size:12px;color:#94a3b8;margin-top:4px">atau klik untuk browse — PNG, JPG, WEBP · maks 5MB</div>;
    refreshPreview();
}

function setStatus(type, msg) {
    const el = document.getElementById('linkStatus');
    el.innerHTML = '';
    el.className = 'link-status status-' + type;
    if (type === 'fetching') {
        el.innerHTML = <span class="spinner-border spinner-border-sm me-2" style="width:12px;height:12px"></span>  + msg;
    } else if (type === 'success') {
        el.innerHTML = <i class="bi bi-check-circle-fill me-1"></i>  + msg;
    } else if (type === 'failed') {
        el.innerHTML = <i class="bi bi-x-circle-fill me-1"></i>  + msg;
    }
}

async function doFetchOg() {
    const url = document.getElementById('linkUrl').value.trim();
    if (!url) return;
    setStatus('fetching', 'Mengambil gambar dari link...');
    try {
        const res  = await fetch(OG_URL + '?url=' + encodeURIComponent(url));
        const data = await res.json();
        if (data.image) { ogSrc = data.image; setStatus('success', 'Gambar berhasil diambil!'); }
        else            { ogSrc = null; setStatus('failed', data.message || 'Tidak ada gambar ditemukan'); }
    } catch (e) { ogSrc = null; setStatus('failed', 'Gagal mengambil gambar'); }
    
    if(ogSrc) uploadSrc = null;
    refreshPreview();
}

let timeout = null;
document.getElementById('linkUrl').addEventListener('input', function() {
    clearTimeout(timeout);
    ogSrc = null; document.getElementById('linkStatus').className = ''; document.getElementById('linkStatus').innerHTML = '';
    if (this.value.trim() === '') refreshPreview();
    else timeout = setTimeout(doFetchOg, 1200);
});
document.getElementById('btnFetch').addEventListener('click', () => {
    clearTimeout(timeout); doFetchOg();
});

function refreshPreview() {
    const img     = document.getElementById('previewImg');
    const d1 = document.getElementById('srcDot1');
    const d2 = document.getElementById('srcDot2');
    const d3 = document.getElementById('srcDot3');
    
    if(d1) d1.className = 'source-dot'; 
    if(d2) d2.className = 'source-dot'; 
    if(d3) d3.className = 'source-dot';
    
    if (uploadSrc) {
        img.className = 'cover'; img.src = uploadSrc;
        if(d1) d1.classList.add('active'); 
    } else if (ogSrc) {
        img.className = 'cover'; img.src = ogSrc;
        if(d2) d2.classList.add('active'); 
    } else {
        img.className = 'logo-default'; img.src = LOGO_SRC;
        if(d3) d3.classList.add('active'); 
    }
}

const dz = document.getElementById('dropZone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.classList.remove('drag-over');
    if (e.dataTransfer.files[0] && e.dataTransfer.files[0].type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]);
        const inp = document.getElementById('imgInput'); inp.files = dt.files;
        handleUpload(inp);
    }
});
refreshPreview();
</script>
@endpush
@endsection


