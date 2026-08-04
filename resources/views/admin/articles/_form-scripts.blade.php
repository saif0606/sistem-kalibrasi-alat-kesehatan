<script>
const LOGO_SRC = '{{ asset("images/logo-white.png") }}';
const OG_URL   = '{{ route("admin.articles.fetch-og") }}';
let uploadSrc  = null;
let ogSrc      = null;
@if(!empty($existingImage))
const EXIST_SRC = '{{ $existingImage }}';
@else
const EXIST_SRC = '';
@endif

function pickCat(btn, cat) {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('catInput').value = cat;
    const previewCat = document.getElementById('previewCat');
    if (previewCat) previewCat.textContent = cat;
}

const titleInput = document.getElementById('artTitle');
const previewTitle = document.getElementById('previewTitle');
if (titleInput && previewTitle) {
    titleInput.addEventListener('input', () => {
        previewTitle.textContent = titleInput.value || 'Judul Artikel';
    });
}

const contentInput = document.querySelector('textarea[name="content"]');
const previewSubtitle = document.getElementById('previewSubtitle');
if (contentInput && previewSubtitle) {
    contentInput.addEventListener('input', () => {
        let txt = contentInput.value.trim();
        if (txt.length > 60) txt = txt.substring(0, 60) + '...';
        previewSubtitle.textContent = txt || 'Ringkasan / deskripsi artikel akan tampil di sini...';
    });
}

function handleUpload(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        uploadSrc = e.target.result;
        ogSrc = null;
        const linkInput = document.getElementById('linkUrl');
        if (linkInput) linkInput.value = '';
        const name = input.files[0].name;
        document.getElementById('dropContent').innerHTML =
            `<div style="font-size:22px;color:#17a45c"><i class="bi bi-check-circle-fill"></i></div>
             <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-top:6px">${name}</div>
             <div style="font-size:11px;color:#94a3b8;margin-top:3px">Klik untuk ganti gambar</div>`;
        refreshPreview();
    };
    reader.readAsDataURL(input.files[0]);
}

function clearUpload() {
    uploadSrc = null;
    ogSrc = null;
    document.getElementById('imgInput').value = '';
    const linkInput = document.getElementById('linkUrl');
    if (linkInput) linkInput.value = '';
    document.getElementById('dropContent').innerHTML =
        `<div style="font-size:30px;color:#17a45c;margin-bottom:8px"><i class="bi bi-cloud-arrow-up"></i></div>
         <div style="font-size:14px;font-weight:600;color:var(--text-primary)">Seret & lepas gambar di sini</div>
         <div style="font-size:12px;color:#94a3b8;margin-top:4px">atau klik untuk browse — PNG, JPG, WEBP · maks 5MB</div>`;
    refreshPreview();
}

function setStatus(type, msg) {
    const el = document.getElementById('linkStatus');
    if (!el) return;
    el.className = 'link-status ' + type;
    if (type === 'fetching') {
        el.innerHTML = `<span class="spinner-border spinner-border-sm me-2" style="width:12px;height:12px"></span>${msg}`;
    } else if (type === 'success') {
        el.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i>${msg}`;
    } else if (type === 'failed') {
        el.innerHTML = `<i class="bi bi-x-circle-fill me-1"></i>${msg}`;
    }
}

async function doFetchOg() {
    const linkInput = document.getElementById('linkUrl');
    if (!linkInput) return;
    const url = linkInput.value.trim();
    if (!url) return;

    setStatus('fetching', 'Mengambil gambar dari link...');
    try {
        const res  = await fetch(OG_URL + '?url=' + encodeURIComponent(url));
        const data = await res.json();
        if (data.image) {
            ogSrc = data.image;
            setStatus('success', 'Thumbnail berhasil diambil — akan tersimpan otomatis saat submit.');
        } else {
            ogSrc = null;
            setStatus('failed', data.message || 'Tidak ada gambar ditemukan');
        }
    } catch (e) {
        ogSrc = null;
        setStatus('failed', 'Gagal mengambil gambar');
    }

    if (ogSrc) uploadSrc = null;
    refreshPreview();
}

let fetchTimeout = null;
const linkUrlInput = document.getElementById('linkUrl');
if (linkUrlInput) {
    linkUrlInput.addEventListener('input', function () {
        clearTimeout(fetchTimeout);
        ogSrc = null;
        const statusEl = document.getElementById('linkStatus');
        if (statusEl) { statusEl.className = 'link-status'; statusEl.innerHTML = ''; }
        if (this.value.trim() === '') {
            refreshPreview();
        } else {
            fetchTimeout = setTimeout(doFetchOg, 1200);
        }
    });

    if (linkUrlInput.value.trim()) {
        doFetchOg();
    }
}

function refreshPreview() {
    const img = document.getElementById('previewImg');
    const d1  = document.getElementById('srcDot1');
    const d2  = document.getElementById('srcDot2');
    const d3  = document.getElementById('srcDot3');
    if (!img) return;

    [d1, d2, d3].forEach(d => { if (d) d.className = 'source-dot'; });

    if (uploadSrc) {
        img.className = 'cover';
        img.src = uploadSrc;
        if (d1) d1.classList.add('active');
    } else if (ogSrc) {
        img.className = 'cover';
        img.src = ogSrc;
        if (d2) d2.classList.add('active');
    } else if (EXIST_SRC) {
        img.className = 'cover';
        img.src = EXIST_SRC;
        if (d3) d3.classList.add('active');
    } else {
        img.className = 'logo-default';
        img.src = LOGO_SRC;
        if (d3) d3.classList.add('active');
    }
}

const dz = document.getElementById('dropZone');
if (dz) {
    dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
    dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
    dz.addEventListener('drop', e => {
        e.preventDefault();
        dz.classList.remove('drag-over');
        if (e.dataTransfer.files[0] && e.dataTransfer.files[0].type.startsWith('image/')) {
            const dt  = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            const inp = document.getElementById('imgInput');
            inp.files = dt.files;
            handleUpload(inp);
        }
    });
}

refreshPreview();
</script>
