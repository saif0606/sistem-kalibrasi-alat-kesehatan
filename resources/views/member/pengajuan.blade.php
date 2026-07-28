@extends('layouts.app')

@section('title', 'Ajukan Kalibrasi — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // ==================================================================
        // $memberUser dikirim dari route (data akun asli yang login).
        // DATA DUMMY di bawah ini masih menunggu fitur InstansiProfile &
        // penyimpanan pengajuan sungguhan:
        //   - $instansiTersimpan  → InstansiProfile::where('user_id', ...)->first()
        //     (null jika user baru & belum pernah mengajukan; disini diisi
        //     contoh supaya perilaku auto-fill terlihat)
        // ==================================================================
        $instansiTersimpan = [
            'nama' => 'RSUD Abdul Moeloek',
            'alamat' => 'Jl. Dr. Rivai No.6, Pahoman, Bandar Lampung, Lampung 35213',
        ];
    @endphp

    {{-- ============================================================
         1. HERO
    ============================================================ --}}
    <section class="dashboard-hero kal-hero">
        <x-tapis-decoration corners="tl-br" />
        <div class="container-xxl position-relative">
            <div data-aos="fade-up">
                <p class="dashboard-hero-eyebrow"><i class="bi bi-file-earmark-plus me-1"></i>Layanan Kalibrasi</p>
                <h1 class="dashboard-hero-title">Ajukan Kalibrasi</h1>
                <p class="dashboard-hero-subtitle">Silakan lengkapi data berikut untuk mengajukan layanan kalibrasi alat kesehatan.</p>

                <ul class="kal-hero-checklist">
                    <li><i class="bi bi-check-circle-fill"></i> Verifikasi maksimal 1×24 jam</li>
                    <li><i class="bi bi-check-circle-fill"></i> Status pengajuan dapat dipantau melalui Dashboard</li>
                    <li><i class="bi bi-check-circle-fill"></i> Kolom bertanda (<span class="kal-required">*</span>) wajib diisi</li>
                </ul>
            </div>
        </div>
    </section>

    <form id="kalForm" class="kal-form" method="POST" enctype="multipart/form-data" novalidate>
        <section class="member-section pt-0 pb-5">
            <div class="container-xxl">
                <div class="row g-4">

                    <div class="col-lg-8">

                        {{-- ============================================================
                             2. CARD DATA INSTANSI
                        ============================================================ --}}
                        <div class="kal-card" data-aos="fade-up">
                            <div class="kal-card-head">
                                <span class="kal-card-icon"><i class="bi bi-hospital"></i></span>
                                <div>
                                    <h2>Data Instansi</h2>
                                    <p>Data ini otomatis tersimpan untuk pengajuan Anda berikutnya.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 kal-field">
                                    <label for="namaInstansi">Nama Instansi <span class="kal-required">*</span></label>
                                    <input type="text" id="namaInstansi" name="instansi_nama" class="kal-input"
                                           placeholder="Contoh: RSUD Abdul Moeloek" value="{{ $instansiTersimpan['nama'] ?? '' }}" required>
                                </div>

                                <div class="col-md-6 kal-field">
                                    <label for="namaPic">Nama PIC / Jabatan <span class="kal-required">*</span></label>
                                    <input type="text" id="namaPic" name="pic_nama" class="kal-input"
                                           placeholder="Contoh: Andi Saputra / Kepala IPSRS" value="{{ $memberUser['name'] }}" required>
                                </div>

                                <div class="col-md-6 kal-field">
                                    <label for="nomorHp">Nomor HP <span class="kal-required">*</span></label>
                                    <input type="tel" id="nomorHp" name="pic_hp" class="kal-input"
                                           placeholder="08xx-xxxx-xxxx" value="{{ $memberUser['phone'] ?? '' }}" required>
                                </div>

                                <div class="col-md-6 kal-field">
                                    <label for="emailPic">Email <span class="kal-required">*</span></label>
                                    <input type="email" id="emailPic" name="pic_email" class="kal-input"
                                           placeholder="nama@instansi.go.id" value="{{ $memberUser['email'] }}" required>
                                </div>

                                <div class="col-12 kal-field">
                                    <label for="alamatInstansi">Alamat Lengkap <span class="kal-required">*</span></label>
                                    <textarea id="alamatInstansi" name="alamat" class="kal-input kal-textarea" rows="2"
                                              placeholder="Jalan, kelurahan, kecamatan, kabupaten/kota" required>{{ $instansiTersimpan['alamat'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================================
                             3. CARD DATA ALAT
                        ============================================================ --}}
                        <div class="kal-card" data-aos="fade-up">
                            <div class="kal-card-head">
                                <span class="kal-card-icon"><i class="bi bi-tools"></i></span>
                                <div>
                                    <h2>Data Alat</h2>
                                    <p>Tambahkan seluruh alat kesehatan yang ingin dikalibrasi.</p>
                                </div>
                            </div>

                            <a href="{{ route('layanan') }}" class="kal-info-link">
                                <i class="bi bi-info-circle"></i>
                                Belum mengetahui daftar alat yang dapat dikalibrasi?
                                <span>Lihat Daftar Alat <i class="bi bi-arrow-right"></i></span>
                            </a>

                            {{-- Empty state --}}
                            <div class="kal-alat-empty" id="kalAlatEmpty">
                                <span class="kal-alat-empty-icon"><i class="bi bi-inbox"></i></span>
                                <p>Belum ada alat yang ditambahkan.</p>
                                <button type="button" class="btn btn-hero-primary" id="kalAddAlatEmptyBtn">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Alat
                                </button>
                            </div>

                            {{-- Dynamic table --}}
                            <div class="kal-alat-table-wrap d-none" id="kalAlatTableWrap">
                                <div class="table-responsive">
                                    <table class="kal-alat-table" id="kalAlatTable">
                                        <thead>
                                            <tr>
                                                <th>Nama Alat</th>
                                                <th>Merk</th>
                                                <th>Model</th>
                                                <th class="text-center" style="width:90px;">Jumlah</th>
                                                <th class="text-center" style="width:56px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="kalAlatTableBody"></tbody>
                                    </table>
                                </div>

                                <div class="kal-alat-footer">
                                    <button type="button" class="kal-add-row-btn" id="kalAddAlatRowBtn">
                                        <i class="bi bi-plus-lg"></i> Tambah Alat
                                    </button>
                                    <span class="kal-alat-total">Total Alat: <strong id="kalAlatTotal">0</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================================
                             5. UPLOAD DOKUMEN
                        ============================================================ --}}
                        <div class="kal-card" data-aos="fade-up">
                            <div class="kal-card-head">
                                <span class="kal-card-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                                <div>
                                    <h2>Upload Dokumen</h2>
                                    <p>Format PDF, maksimal 10 MB per file.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="kal-upload-zone" for="uploadSurat" id="uploadSuratZone">
                                        <input type="file" id="uploadSurat" name="upload_surat[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,application/pdf,image/*" class="kal-upload-input" multiple required>
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                        <strong>Upload Surat Permohonan dan Lampiran Alat <span class="kal-required">*</span></strong>
                                        <span class="kal-upload-hint">PDF &bull; Maksimal 10 MB per file</span>
                                    </label>
                                    <ul class="kal-upload-filelist" id="uploadSuratList"></ul>
                                </div>
                                <div class="col-md-6">
                                    <label class="kal-upload-zone" for="uploadPendukung" id="uploadPendukungZone">
                                        <input type="file" id="uploadPendukung" name="upload_pendukung[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,application/pdf,image/*" class="kal-upload-input" multiple>
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                        <strong>Upload Dokumen Pendukung <span class="kal-optional">(opsional)</span></strong>
                                        <span class="kal-upload-hint">PDF &bull; Maksimal 10 MB per file</span>
                                    </label>
                                    <ul class="kal-upload-filelist" id="uploadPendukungList"></ul>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================================
                             6. CATATAN TAMBAHAN
                        ============================================================ --}}
                        <div class="kal-card" data-aos="fade-up">
                            <div class="kal-card-head">
                                <span class="kal-card-icon"><i class="bi bi-chat-left-text"></i></span>
                                <div>
                                    <h2>Catatan Tambahan</h2>
                                    <p>Opsional — sampaikan hal lain yang perlu kami ketahui.</p>
                                </div>
                            </div>
                            <div class="kal-field mb-0">
                                <textarea name="catatan" class="kal-input kal-textarea" rows="3"
                                          placeholder="Contoh: Alat Berjumlah Banyak."></textarea>
                            </div>
                        </div>

                    </div>

                    {{-- ============================================================
                         7. RINGKASAN PENGAJUAN (sticky sidebar)
                    ============================================================ --}}
                    <div class="col-lg-4">
                        <div class="kal-summary-card" data-aos="fade-up" data-aos-delay="80">
                            <h2><i class="bi bi-receipt me-2"></i>Ringkasan Pengajuan</h2>

                            <div class="kal-summary-row">
                                <span>Instansi</span>
                                <strong id="ringkasanInstansi">—</strong>
                            </div>
                            <div class="kal-summary-row">
                                <span>Nama PIC</span>
                                <strong id="ringkasanPic">—</strong>
                            </div>
                            <div class="kal-summary-row">
                                <span>Jumlah Alat</span>
                                <strong id="ringkasanJumlahAlat">0</strong>
                            </div>

                            {{-- 8. TOMBOL --}}
                            <div class="kal-actions">
                                <a href="{{ route('dashboard') }}" class="btn btn-hero-outline w-100 justify-content-center">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                                </a>
                                <button type="submit" class="btn btn-hero-primary w-100 justify-content-center">
                                    Kirim Pengajuan <i class="bi bi-send ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </form>

    {{-- Template baris alat — di-clone via JS, tidak dirender langsung --}}
    <template id="kalAlatRowTemplate">
        <tr>
            <td><input type="text" name="alat[__i__][nama_alat]" class="kal-table-input" placeholder="Nama alat" required></td>
            <td><input type="text" name="alat[__i__][merk]" class="kal-table-input" placeholder="Merk"></td>
            <td><input type="text" name="alat[__i__][model]" class="kal-table-input" placeholder="Model"></td>
            <td><input type="number" name="alat[__i__][jumlah]" class="kal-table-input text-center" min="1" value="1" required></td>
            <td class="text-center">
                <button type="button" class="kal-table-remove" aria-label="Hapus alat">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        </tr>
    </template>

    {{-- ============================================================
         9. MODAL KONFIRMASI
    ============================================================ --}}
    <div class="modal fade kal-confirm-modal" id="kalConfirmModal" tabindex="-1" aria-labelledby="kalConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <span class="kal-confirm-icon"><i class="bi bi-question-circle"></i></span>
                    <h5 id="kalConfirmModalLabel">Apakah seluruh data yang Anda masukkan sudah benar?</h5>
                    <p>Data yang telah dikirim akan diproses oleh petugas UPTD.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-hero-primary" id="kalConfirmSubmitBtn">
                        Kirim Pengajuan <i class="bi bi-send ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         10. SUCCESS STATE
    ============================================================ --}}
    <div class="modal fade kal-success-modal" id="kalSuccessModal" tabindex="-1" aria-labelledby="kalSuccessModalLabel"
         aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <span class="kal-success-icon"><i class="bi bi-check-lg"></i></span>
                    <h5 id="kalSuccessModalLabel">Pengajuan Berhasil</h5>
                    <p class="kal-success-desc">Terima kasih, pengajuan kalibrasi Anda telah kami terima.</p>
                    <div class="kal-success-info">
                        <div>
                            <span>Nomor Pengajuan</span>
                            <strong id="kalSuccessNomor">PK-2026-00001</strong>
                        </div>
                        <div>
                            <span>Status</span>
                            <strong class="status-badge status-menunggu">Menunggu Verifikasi</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('dashboard') }}" class="btn btn-hero-primary w-100 justify-content-center">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
