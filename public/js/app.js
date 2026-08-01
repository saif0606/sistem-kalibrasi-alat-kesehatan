/* ==========================================================================
   UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung
   Vanilla JavaScript — theme toggle, navbar scroll, counters, AOS, loading
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    /* ----------------------------------------------------------------
       AOS — Animate On Scroll init
    ---------------------------------------------------------------- */
    if (window.AOS) {
        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 60,
        });
    }

    /* ----------------------------------------------------------------
       Loading screen: hide once the page has fully loaded
    ---------------------------------------------------------------- */
    const loadingScreen = document.getElementById('loadingScreen');

    function hideLoadingScreen() {
        if (loadingScreen) {
            loadingScreen.classList.add('loaded');
        }
    }

    if (document.readyState === 'complete') {
        setTimeout(hideLoadingScreen, 300);
    } else {
        window.addEventListener('load', function () {
            setTimeout(hideLoadingScreen, 300);
        });
    }
    // Safety fallback in case 'load' is delayed by slow external assets
    setTimeout(hideLoadingScreen, 2500);

    /* ----------------------------------------------------------------
       Dark / Light mode toggle — segmented sun/moon buttons
    ---------------------------------------------------------------- */
    const themeLightBtn = document.getElementById('themeLightBtn');
    const themeDarkBtn = document.getElementById('themeDarkBtn');
    const htmlEl = document.documentElement;
    const THEME_KEY = 'uptd-theme';

    function applyThemeState(theme) {
        htmlEl.setAttribute('data-theme', theme);
        if (themeLightBtn) {
            themeLightBtn.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
        }
        if (themeDarkBtn) {
            themeDarkBtn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        }
    }

    function setTheme(theme) {
        applyThemeState(theme);
        try {
            localStorage.setItem(THEME_KEY, theme);
        } catch (e) {
            /* localStorage unavailable — theme just won't persist */
        }
    }

    // Sync toggle UI with whatever the inline bootstrap script already set
    applyThemeState(htmlEl.getAttribute('data-theme') || 'light');

    if (themeLightBtn) {
        themeLightBtn.addEventListener('click', function () {
            setTheme('light');
        });
    }
    if (themeDarkBtn) {
        themeDarkBtn.addEventListener('click', function () {
            setTheme('dark');
        });
    }

    /* ----------------------------------------------------------------
       Navbar: transparent -> blurred white on scroll
    ---------------------------------------------------------------- */
    const navbar = document.getElementById('mainNavbar');
    const backToTop = document.getElementById('backToTop');
    const scrollThreshold = 60;

    function handleScrollState() {
        const scrolled = window.scrollY > scrollThreshold;

        if (navbar) {
            navbar.classList.toggle('scrolled', scrolled);
        }
        if (backToTop) {
            backToTop.classList.toggle('show', window.scrollY > 400);
        }
    }

    handleScrollState();
    window.addEventListener('scroll', handleScrollState, { passive: true });

    /* ----------------------------------------------------------------
       Back to top button
    ---------------------------------------------------------------- */
    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ----------------------------------------------------------------
       Statistic counters — animate when scrolled into view
    ---------------------------------------------------------------- */
    const counters = document.querySelectorAll('[data-count-to]');

    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-count-to'), 10) || 0;
        const duration = 1600;
        const startTime = performance.now();

        function tick(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
            el.textContent = Math.round(eased * target).toLocaleString('id-ID');

            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = target.toLocaleString('id-ID');
            }
        }

        requestAnimationFrame(tick);
    }

    if (counters.length && 'IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach((counter) => counterObserver.observe(counter));
    } else {
        // Fallback: animate immediately if IntersectionObserver unsupported
        counters.forEach((counter) => animateCounter(counter));
    }

    /* ----------------------------------------------------------------
       Close mobile navbar collapse after clicking a nav link
    ---------------------------------------------------------------- */
    const navbarCollapseEl = document.getElementById('navbarMain');
    const allNavLinks = document.querySelectorAll('.navbar-nav .nav-link');
    if (navbarCollapseEl && window.bootstrap) {
        const bsCollapse = window.bootstrap.Collapse.getOrCreateInstance(navbarCollapseEl, { toggle: false });
        allNavLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (navbarCollapseEl.classList.contains('show')) {
                    bsCollapse.hide();
                }
            });
        });
    }

    /* ----------------------------------------------------------------
       LAYANAN — Filter tabel Daftar Tarif (search)
    ---------------------------------------------------------------- */
    const tarifSearch = document.getElementById('tarifSearch');
    const tarifTable = document.getElementById('tarifTable');
    const tarifEmptyState = document.getElementById('tarifEmptyState');

    function filterTarifTable() {
        if (!tarifTable) return;
        const query = (tarifSearch?.value || '').trim().toLowerCase();
        const rows = tarifTable.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach((row) => {
            const name = row.getAttribute('data-name') || '';
            const show = name.includes(query);
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (tarifEmptyState) {
            tarifEmptyState.classList.toggle('d-none', visibleCount !== 0);
        }
    }

    if (tarifSearch) tarifSearch.addEventListener('input', filterTarifTable);

    /* ----------------------------------------------------------------
       LAYANAN — Modal Detail Katalog Alat (diisi ulang via data-*)
    ---------------------------------------------------------------- */
    const katalogModal = document.getElementById('katalogDetailModal');
    if (katalogModal) {
        katalogModal.addEventListener('show.bs.modal', (event) => {
            const btn = event.relatedTarget;
            if (!btn) return;

            const setText = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value || '-';
            };

            setText('katalogModalName', btn.getAttribute('data-name'));
            setText('katalogModalDesc', btn.getAttribute('data-desc'));
            setText('katalogModalPrice', btn.getAttribute('data-price'));
            setText('katalogModalSyarat', 'Alat dapat beroperasi dengan baik serta aksesoris lengkap.');

            const categoryEl = document.getElementById('katalogModalCategory');
            const color = btn.getAttribute('data-color') || 'green';
            if (categoryEl) {
                categoryEl.textContent = btn.getAttribute('data-category') || '-';
                categoryEl.className = 'tarif-category-badge' + (color === 'blue' ? ' tarif-badge-blue' : '');
            }

            const visualEl = document.getElementById('katalogModalVisual');
            if (visualEl) {
                const gambar = btn.getAttribute('data-gambar');
                if (gambar) {
                    visualEl.innerHTML = '<img src="' + gambar + '" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">';
                } else {
                    visualEl.innerHTML = '<i class="bi ' + (btn.getAttribute('data-icon') || 'bi-clipboard2-pulse') + '"></i>';
                    visualEl.style.color = color === 'blue' ? 'var(--color-primary-blue)' : 'var(--color-primary-green)';
                }
            }
        });
    }

    /* ----------------------------------------------------------------
       PAGE TRANSITION — fade + slide halus saat berpindah antara
       Landing -> Login, dan Login <-> Register (durasi ~350ms).
       Tautan yang berpindah antar halaman auth di-intercept supaya
       fade-out dulu sebelum benar-benar navigasi.
    ---------------------------------------------------------------- */
    document.querySelectorAll('a[data-page-transition]').forEach((link) => {
        link.addEventListener('click', function (e) {
            const href = link.getAttribute('href');
            if (!href || href.charAt(0) === '#' || link.target === '_blank') return;
            e.preventDefault();
            document.body.classList.add('page-transition-out');
            window.setTimeout(function () {
                window.location.href = href;
            }, 320);
        });
    });

    /* ----------------------------------------------------------------
       AUTH — Toggle show/hide password (Login, Register, dsb.)
    ---------------------------------------------------------------- */
    document.querySelectorAll('.auth-toggle-password, .auth-locked-toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;
            const icon = btn.querySelector('i');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            if (icon) {
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            }
            btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });

    /* ----------------------------------------------------------------
       AJUKAN KALIBRASI — Radio Lokasi Pelaksanaan (fallback :has())
    ---------------------------------------------------------------- */
    document.querySelectorAll('.kal-radio-option').forEach((option) => {
        const input = option.querySelector('input[type="radio"]');
        if (!input) return;
        const syncChecked = () => {
            document.querySelectorAll('input[name="' + input.name + '"]').forEach((radio) => {
                radio.closest('.kal-radio-option')?.classList.toggle('is-checked', radio.checked);
            });
        };
        input.addEventListener('change', syncChecked);
        syncChecked();
    });

    /* ----------------------------------------------------------------
       AJUKAN KALIBRASI — Tabel dinamis Data Alat
    ---------------------------------------------------------------- */
    const kalAlatEmpty = document.getElementById('kalAlatEmpty');
    const kalAlatTableWrap = document.getElementById('kalAlatTableWrap');
    const kalAlatTableBody = document.getElementById('kalAlatTableBody');
    const kalAlatRowTemplate = document.getElementById('kalAlatRowTemplate');
    const kalAlatTotal = document.getElementById('kalAlatTotal');
    const kalAddAlatEmptyBtn = document.getElementById('kalAddAlatEmptyBtn');
    const kalAddAlatRowBtn = document.getElementById('kalAddAlatRowBtn');

    let kalRowIndex = 0;

    function updateKalAlatState() {
        const rowCount = kalAlatTableBody ? kalAlatTableBody.querySelectorAll('tr').length : 0;
        if (kalAlatTotal) kalAlatTotal.textContent = rowCount;

        const jumlahAlatEl = document.getElementById('ringkasanJumlahAlat');
        if (jumlahAlatEl) jumlahAlatEl.textContent = rowCount;

        if (rowCount === 0) {
            kalAlatEmpty?.classList.remove('d-none');
            kalAlatTableWrap?.classList.add('d-none');
        } else {
            kalAlatEmpty?.classList.add('d-none');
            kalAlatTableWrap?.classList.remove('d-none');
        }
    }

    function addKalAlatRow() {
        if (!kalAlatRowTemplate || !kalAlatTableBody) return;
        const rowHtml = kalAlatRowTemplate.innerHTML.replace(/__i__/g, kalRowIndex++);
        const temp = document.createElement('tbody');
        temp.innerHTML = rowHtml.trim();
        const row = temp.firstElementChild;
        kalAlatTableBody.appendChild(row);
        updateKalAlatState();
        row.querySelector('.kal-table-input')?.focus();
    }

    if (kalAlatTableBody) {
        kalAlatTableBody.addEventListener('click', (event) => {
            const btn = event.target.closest('.kal-table-remove');
            if (!btn) return;
            btn.closest('tr')?.remove();
            updateKalAlatState();
        });
    }

    if (kalAddAlatEmptyBtn) kalAddAlatEmptyBtn.addEventListener('click', addKalAlatRow);
    if (kalAddAlatRowBtn) kalAddAlatRowBtn.addEventListener('click', addKalAlatRow);

    updateKalAlatState();

    /* ----------------------------------------------------------------
       AJUKAN KALIBRASI — Upload dokumen multi-file:
       - bisa pilih banyak file sekaligus (attribute multiple)
       - drag & drop mendukung banyak file
       - bisa menambah file lagi setelah upload pertama (diakumulasi,
         bukan menimpa pilihan sebelumnya)
       - tampil daftar file terpilih, masing-masing bisa dihapus satu per satu
       - validasi ukuran (maks. 5 MB/file) tetap berjalan
       - hasil akhir disinkronkan balik ke <input> via DataTransfer, jadi
         saat form di-submit (multipart/form-data) SEMUA file terkirim
         dalam satu request sebagai array — siap dipakai backend Laravel
         (request()->file('upload_surat')) tanpa perubahan lebih lanjut.
    ---------------------------------------------------------------- */
    const MAX_UPLOAD_SIZE = 10 * 1024 * 1024; // 10 MB

    function formatFileSize(bytes) {
        if (bytes >= 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        return Math.ceil(bytes / 1024) + ' KB';
    }

    function initMultiUpload(input) {
        if (!input) return;
        const zone = document.getElementById(input.id + 'Zone') || input.closest('.kal-upload-zone');
        const list = document.getElementById(input.id + 'List');
        let store = []; // File[] terakumulasi

        function syncInputFiles() {
            const dt = new DataTransfer();
            store.forEach((file) => dt.items.add(file));
            input.files = dt.files;
        }

        function render() {
            if (!list) return;
            list.innerHTML = '';
            store.forEach((file, idx) => {
                const li = document.createElement('li');
                li.className = 'kal-upload-file-item';
                li.innerHTML =
                    '<i class="bi bi-file-earmark-text"></i>' +
                    '<span class="kal-upload-file-name">' + file.name + '</span>' +
                    '<span class="kal-upload-file-size">' + formatFileSize(file.size) + '</span>' +
                    '<button type="button" class="kal-upload-file-remove" aria-label="Hapus file ini"><i class="bi bi-x-lg"></i></button>';
                li.querySelector('.kal-upload-file-remove').addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    store.splice(idx, 1);
                    syncInputFiles();
                    render();
                });
                list.appendChild(li);
            });
            if (zone) zone.classList.toggle('has-files', store.length > 0);
        }

        function addFiles(fileList) {
            let rejected = 0;
            Array.from(fileList).forEach((file) => {
                if (file.size > MAX_UPLOAD_SIZE) {
                    rejected++;
                    return;
                }
                // Hindari duplikat persis (nama + ukuran sama)
                const dup = store.some((f) => f.name === file.name && f.size === file.size);
                if (!dup) store.push(file);
            });
            syncInputFiles();
            render();
            if (rejected > 0 && zone) {
                let warn = zone.parentElement.querySelector('.kal-upload-warning');
                if (!warn) {
                    warn = document.createElement('p');
                    warn.className = 'kal-upload-warning';
                    zone.insertAdjacentElement('afterend', warn);
                }
                warn.textContent = rejected + ' file dilewati karena melebihi 10 MB.';
                setTimeout(() => warn.remove(), 4000);
            }
        }

        // Pilih file lewat dialog (klik zone) — diakumulasi, bukan menimpa
        input.addEventListener('change', () => {
            addFiles(input.files);
        });

        // Drag & drop
        if (zone) {
            ['dragenter', 'dragover'].forEach((evt) => {
                zone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.add('dragover');
                });
            });
            ['dragleave', 'dragend'].forEach((evt) => {
                zone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.remove('dragover');
                });
            });
            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                zone.classList.remove('dragover');
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                    addFiles(e.dataTransfer.files);
                }
            });
        }
    }

    document.querySelectorAll('.kal-upload-input').forEach(initMultiUpload);

    /* ----------------------------------------------------------------
       AJUKAN KALIBRASI — Ringkasan Pengajuan otomatis mengikuti input,
       serta alur submit: Konfirmasi → Success (belum ada backend,
       jadi disimulasikan penuh di sisi klien)
    ---------------------------------------------------------------- */
    const kalForm = document.getElementById('kalForm');
    if (kalForm) {
        const ringkasanInstansi = document.getElementById('ringkasanInstansi');
        const ringkasanPic = document.getElementById('ringkasanPic');
        const namaInstansiInput = document.getElementById('namaInstansi');
        const namaPicInput = document.getElementById('namaPic');

        function updateRingkasan() {
            if (ringkasanInstansi) {
                ringkasanInstansi.textContent = namaInstansiInput?.value.trim() || '—';
            }
            if (ringkasanPic) {
                ringkasanPic.textContent = namaPicInput?.value.trim() || '—';
            }
        }

        kalForm.addEventListener('input', updateRingkasan);
        kalForm.addEventListener('change', updateRingkasan);
        updateRingkasan();

        const kalConfirmModalEl = document.getElementById('kalConfirmModal');
        const kalSuccessModalEl = document.getElementById('kalSuccessModal');
        const kalConfirmSubmitBtn = document.getElementById('kalConfirmSubmitBtn');
        const kalConfirmModal = kalConfirmModalEl ? new bootstrap.Modal(kalConfirmModalEl) : null;
        const kalSuccessModal = kalSuccessModalEl ? new bootstrap.Modal(kalSuccessModalEl) : null;

        kalForm.addEventListener('submit', (event) => {
            event.preventDefault();
            if (!kalForm.checkValidity()) {
                kalForm.reportValidity();
                return;
            }
            kalConfirmModal?.show();
        });

        if (kalConfirmSubmitBtn) {
            kalConfirmSubmitBtn.addEventListener('click', () => {
                kalConfirmModal?.hide();

                // Nomor pengajuan dummy — nanti diganti hasil insert database
                const nomorEl = document.getElementById('kalSuccessNomor');
                if (nomorEl) {
                    const random = Math.floor(Math.random() * 9000 + 1000);
                    nomorEl.textContent = 'PK-2026-' + String(random).padStart(5, '0');
                }

                setTimeout(() => kalSuccessModal?.show(), 300);
            });
        }
    }

    /* ----------------------------------------------------------------
       TOMBOL "COPY ID" — dipakai di Riwayat Pengajuan & Proses,
       delegasi ke document supaya bekerja di halaman manapun tanpa
       perlu di-scope ulang per halaman.
    ---------------------------------------------------------------- */
    document.addEventListener('click', (event) => {
        const btn = event.target.closest('.riw-copy-btn');
        if (!btn) return;
        event.stopPropagation();
        const kode = btn.getAttribute('data-copy') || '';
        const icon = btn.querySelector('i');
        const markCopied = () => {
            btn.classList.add('copied');
            if (icon) icon.className = 'bi bi-check2';
            setTimeout(() => {
                btn.classList.remove('copied');
                if (icon) icon.className = 'bi bi-clipboard';
            }, 1500);
        };
        if (navigator.clipboard) {
            navigator.clipboard.writeText(kode).then(markCopied).catch(markCopied);
        } else {
            markCopied();
        }
    });

    /* ----------------------------------------------------------------
       RIWAYAT PENGAJUAN — Search & filter status (card list)
    ---------------------------------------------------------------- */
    const riwSearch = document.getElementById('riwSearch');
    const riwFilter = document.getElementById('riwFilter');
    const riwList = document.getElementById('riwList');
    const riwFilterEmpty = document.getElementById('riwFilterEmpty');

    function filterRiwayatList() {
        if (!riwList) return;
        const query = (riwSearch?.value || '').trim().toLowerCase();
        const status = riwFilter?.value || '';
        const cards = riwList.querySelectorAll('.riw-card');
        let visibleCount = 0;

        cards.forEach((card) => {
            const kode = card.getAttribute('data-kode') || '';
            const cardStatus = card.getAttribute('data-status') || '';
            const matchesQuery = kode.includes(query);
            const matchesStatus = !status || cardStatus === status;
            const show = matchesQuery && matchesStatus;
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (riwFilterEmpty) {
            riwFilterEmpty.classList.toggle('d-none', visibleCount !== 0);
        }
    }

    if (riwSearch) riwSearch.addEventListener('input', filterRiwayatList);
    if (riwFilter) riwFilter.addEventListener('change', filterRiwayatList);

    /* ----------------------------------------------------------------
       RIWAYAT PENGAJUAN — Card dapat diklik, tombol Copy ID,
       dan tombol Download Sertifikat (dummy)
    ---------------------------------------------------------------- */
    if (riwList) {
        riwList.querySelectorAll('.riw-card').forEach((card) => {
            const goToDetail = () => {
                const href = card.getAttribute('data-href');
                if (href) window.location.href = href;
            };
            card.addEventListener('click', (event) => {
                if (event.target.closest('button, a')) return;
                goToDetail();
            });
            card.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    goToDetail();
                }
            });
        });

        riwList.querySelectorAll('.riw-download-btn').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                event.stopPropagation();
                // Dummy — nanti diganti link unduh sertifikat sungguhan
                const kode = btn.getAttribute('data-kode') || '';
                alert('Sertifikat untuk ' + kode + ' akan segera diunduh (fitur ini masih dummy).');
            });
        });
    }

    /* ----------------------------------------------------------------
       PROFIL AKUN — Toggle Edit / Simpan / Batal pada Card Data Akun
    ---------------------------------------------------------------- */
    const accDataForm = document.getElementById('accDataForm');
    if (accDataForm) {
        const accFields = ['accNama', 'accEmail', 'accHp'].map((id) => document.getElementById(id));
        const accEditBtn = document.getElementById('accEditBtn');
        const accCancelBtn = document.getElementById('accCancelBtn');
        const accSaveBtn = document.getElementById('accSaveBtn');
        const accSaveNote = document.getElementById('accSaveNote');
        let accOriginalValues = [];

        function setAccEditing(isEditing) {
            accFields.forEach((field) => field && (field.disabled = !isEditing));
            accEditBtn?.classList.toggle('d-none', isEditing);
            accCancelBtn?.classList.toggle('d-none', !isEditing);
            accSaveBtn?.classList.toggle('d-none', !isEditing);
        }

        accEditBtn?.addEventListener('click', () => {
            accOriginalValues = accFields.map((field) => field?.value ?? '');
            setAccEditing(true);
            accFields[0]?.focus();
        });

        accCancelBtn?.addEventListener('click', () => {
            accFields.forEach((field, i) => field && (field.value = accOriginalValues[i]));
            setAccEditing(false);
        });

        accSaveBtn?.addEventListener('click', () => {
            // Dummy — nanti diganti request update profil ke backend
            setAccEditing(false);
            if (accSaveNote) {
                accSaveNote.classList.remove('show');
                void accSaveNote.offsetWidth; // restart animasi
                accSaveNote.classList.add('show');
            }
        });
    }

    /* ----------------------------------------------------------------
       PROFIL AKUN — Modal Ubah Password (dummy)
    ---------------------------------------------------------------- */
    const accPasswordModalEl = document.getElementById('accPasswordModal');
    const accPasswordSaveBtn = document.getElementById('accPasswordSaveBtn');
    if (accPasswordModalEl && accPasswordSaveBtn) {
        accPasswordSaveBtn.addEventListener('click', () => {
            const oldPass = document.getElementById('accPassOld');
            const newPass = document.getElementById('accPassNew');
            const confirmPass = document.getElementById('accPassConfirm');

            if (!oldPass.value || !newPass.value || !confirmPass.value) {
                alert('Mohon lengkapi seluruh kolom password.');
                return;
            }
            if (newPass.value !== confirmPass.value) {
                alert('Konfirmasi password baru tidak cocok.');
                return;
            }

            // Dummy — nanti diganti request ubah password ke backend
            const modal = bootstrap.Modal.getInstance(accPasswordModalEl) || new bootstrap.Modal(accPasswordModalEl);
            modal.hide();
            [oldPass, newPass, confirmPass].forEach((input) => (input.value = ''));
            setTimeout(() => alert('Password berhasil diperbarui.'), 300);
        });
    }

    /* ----------------------------------------------------------------
       BERITA & INFORMASI — Search + filter kategori (grid card)
    ---------------------------------------------------------------- */
    const beritaGrid = document.getElementById('beritaGrid');
    if (beritaGrid) {
        const beritaSearch = document.getElementById('beritaSearch');
        const beritaEmpty = document.getElementById('beritaEmpty');
        const filterPills = document.querySelectorAll('.berita-filter-pill');
        let activeCategory = '';

        function filterBeritaGrid() {
            const query = (beritaSearch?.value || '').trim().toLowerCase();
            const items = beritaGrid.querySelectorAll('.berita-grid-item');
            let visibleCount = 0;

            items.forEach((el) => {
                const title = el.getAttribute('data-title') || '';
                const category = el.getAttribute('data-category') || '';
                const isSocial = el.getAttribute('data-social') === '1';
                const matchesQuery = title.includes(query);
                let matchesCategory = true;
                if (activeCategory === '__social__') {
                    matchesCategory = isSocial;
                } else if (activeCategory) {
                    matchesCategory = category === activeCategory;
                }
                const show = matchesQuery && matchesCategory;
                el.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            if (beritaEmpty) beritaEmpty.classList.toggle('d-none', visibleCount !== 0);
        }

        beritaSearch?.addEventListener('input', filterBeritaGrid);

        filterPills.forEach((pill) => {
            pill.addEventListener('click', () => {
                filterPills.forEach((p) => p.classList.remove('active'));
                pill.classList.add('active');
                activeCategory = pill.getAttribute('data-filter') || '';
                filterBeritaGrid();
            });
        });
    }

});
