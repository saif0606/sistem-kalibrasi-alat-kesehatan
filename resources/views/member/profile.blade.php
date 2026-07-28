@extends('layouts.app')

@section('title', 'Profil Akun — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // $memberUser dikirim dari route (routes/web.php) — nama & email
        // asli dari akun yang login, nomor HP null/"-" sampai user pernah
        // mengajukan kalibrasi (baru terisi lewat form Ajukan Kalibrasi).

        // ==================================================================
        // DATA DUMMY — Informasi Instansi belum tersambung ke form
        // pengajuan sungguhan, menyusul begitu fitur itu dibangun.
        // ==================================================================
        $instansiTersimpan = [
            'nama' => 'RSUD Abdul Moeloek',
            'pic' => $memberUser['name'],
            'alamat' => 'Jl. Dr. Rivai No.6, Pahoman, Bandar Lampung, Lampung 35213',
        ];

        $memberSince = $memberUser['member_since'] ? $memberUser['member_since']->translatedFormat('d F Y') : '-';

        $statsProfil = [
            ['label' => 'Total Pengajuan', 'value' => 12, 'icon' => 'bi-folder2-open', 'tone' => 'green'],
            ['label' => 'Diproses', 'value' => 3, 'icon' => 'bi-gear-wide-connected', 'tone' => 'blue'],
            ['label' => 'Selesai', 'value' => 8, 'icon' => 'bi-check-circle', 'tone' => 'green'],
            ['label' => 'Menunggu', 'value' => 1, 'icon' => 'bi-hourglass-split', 'tone' => 'amber'],
        ];
    @endphp

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <section class="dashboard-hero">
        <x-tapis-decoration corners="tl-br" />
        <div class="container-xxl position-relative">
            <div data-aos="fade-up">
                <p class="dashboard-hero-eyebrow"><i class="bi bi-person-circle me-1"></i>Area Member</p>
                <h1 class="dashboard-hero-title">Profil Akun</h1>
                <p class="dashboard-hero-subtitle">Kelola informasi akun dan instansi Anda.</p>
            </div>
        </div>
    </section>

    <section class="member-section pt-0 pb-5">
        <div class="container-xxl">
            <div class="row g-4">

                {{-- ========================================================
                     KOLOM KIRI
                ======================================================== --}}
                <div class="col-lg-8">

                    {{-- 1. Card Data Akun --}}
                    <form class="kal-card acc-card" id="accDataForm" data-aos="fade-up">
                        <div class="kal-card-head">
                            <span class="kal-card-icon"><i class="bi bi-person-vcard"></i></span>
                            <div>
                                <h2>Data Akun</h2>
                                <p>Informasi dasar akun Anda.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4 kal-field">
                                <label for="accNama">Nama</label>
                                <input type="text" id="accNama" class="kal-input" value="{{ $memberUser['name'] }}" disabled>
                            </div>
                            <div class="col-md-4 kal-field">
                                <label for="accEmail">Email</label>
                                <input type="email" id="accEmail" class="kal-input" value="{{ $memberUser['email'] }}" disabled>
                            </div>
                            <div class="col-md-4 kal-field">
                                <label for="accHp">Nomor HP</label>
                                <input type="tel" id="accHp" class="kal-input" value="{{ $memberUser['phone'] ?? '-' }}" placeholder="Belum ada data" disabled>
                            </div>
                        </div>

                        <div class="acc-card-actions">
                            <span class="acc-save-note" id="accSaveNote"><i class="bi bi-check-circle-fill"></i> Perubahan disimpan</span>
                            <button type="button" class="btn btn-hero-outline d-none" id="accCancelBtn">Batal</button>
                            <button type="button" class="btn btn-hero-primary d-none" id="accSaveBtn">Simpan Perubahan</button>
                            <button type="button" class="btn btn-hero-outline" id="accEditBtn">
                                <i class="bi bi-pencil me-1"></i> Edit Profil
                            </button>
                        </div>
                    </form>

                    {{-- 2. Card Informasi Instansi --}}
                    <div class="kal-card" data-aos="fade-up">
                        <div class="kal-card-head">
                            <span class="kal-card-icon"><i class="bi bi-hospital"></i></span>
                            <div>
                                <h2>Informasi Instansi</h2>
                                <p>Digunakan otomatis saat Anda mengajukan kalibrasi.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 kal-field">
                                <label>Nama Instansi</label>
                                <div class="kal-input kal-input-readonly"><i class="bi bi-hospital"></i> {{ $instansiTersimpan['nama'] }}</div>
                            </div>
                            <div class="col-md-6 kal-field">
                                <label>Nama PIC</label>
                                <div class="kal-input kal-input-readonly"><i class="bi bi-person"></i> {{ $instansiTersimpan['pic'] }}</div>
                            </div>
                            <div class="col-12 kal-field">
                                <label>Alamat Instansi</label>
                                <div class="kal-input kal-input-readonly acc-readonly-multiline"><i class="bi bi-geo-alt"></i> {{ $instansiTersimpan['alamat'] }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Card Keamanan --}}
                    <div class="kal-card" data-aos="fade-up">
                        <div class="kal-card-head">
                            <span class="kal-card-icon"><i class="bi bi-shield-lock"></i></span>
                            <div>
                                <h2>Keamanan</h2>
                                <p>Kelola kata sandi akun Anda.</p>
                            </div>
                        </div>

                        <div class="row g-3 align-items-end">
                            <div class="col-md-8 kal-field mb-0">
                                <label>Password</label>
                                <div class="kal-input kal-input-readonly"><i class="bi bi-lock-fill"></i> &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</div>
                            </div>
                            <div class="col-md-4 kal-field mb-0">
                                <button type="button" class="btn btn-hero-outline w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#accPasswordModal">
                                    <i class="bi bi-key me-1"></i> Ubah Password
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ========================================================
                     KOLOM KANAN
                ======================================================== --}}
                <div class="col-lg-4">

                    {{-- Card Profil --}}
                    <div class="acc-profile-card" data-aos="fade-up" data-aos-delay="80">
                        <span class="acc-avatar">{{ $memberUser['initial'] }}</span>
                        <h2 class="acc-profile-name">{{ $memberUser['name'] }}</h2>
                        <p class="acc-profile-instansi">{{ $instansiTersimpan['nama'] }}</p>
                        <span class="acc-status-badge"><i class="bi bi-check-circle-fill"></i> Akun Aktif</span>
                        <p class="acc-member-since">Member sejak<br><strong>{{ $memberSince }}</strong></p>
                    </div>

                    {{-- Card Statistik --}}
                    <div class="kal-card acc-stats-card" data-aos="fade-up" data-aos-delay="140">
                        <div class="kal-card-head">
                            <span class="kal-card-icon"><i class="bi bi-bar-chart"></i></span>
                            <div>
                                <h2>Statistik</h2>
                                <p>Ringkasan pengajuan Anda.</p>
                            </div>
                        </div>
                        <div class="acc-stats-grid">
                            @foreach ($statsProfil as $stat)
                                <div class="acc-stat-mini">
                                    <span class="dash-stat-icon dash-stat-icon-{{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
                                    <span class="acc-stat-number">{{ $stat['value'] }}</span>
                                    <span class="acc-stat-label">{{ $stat['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         MODAL UBAH PASSWORD
    ============================================================ --}}
    <div class="modal fade acc-password-modal" id="accPasswordModal" tabindex="-1" aria-labelledby="accPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <span class="kal-card-icon acc-modal-icon"><i class="bi bi-shield-lock"></i></span>
                    <h5 id="accPasswordModalLabel">Ubah Password</h5>
                    <p class="acc-modal-desc">Gunakan kombinasi huruf, angka, dan simbol untuk password yang lebih aman.</p>

                    <div class="kal-field acc-password-field">
                        <label for="accPassOld">Password Lama</label>
                        <input type="password" id="accPassOld" class="kal-input">
                        <button type="button" class="auth-toggle-password" data-target="accPassOld" aria-label="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="kal-field acc-password-field">
                        <label for="accPassNew">Password Baru</label>
                        <input type="password" id="accPassNew" class="kal-input">
                        <button type="button" class="auth-toggle-password" data-target="accPassNew" aria-label="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="kal-field acc-password-field mb-0">
                        <label for="accPassConfirm">Konfirmasi Password Baru</label>
                        <input type="password" id="accPassConfirm" class="kal-input">
                        <button type="button" class="auth-toggle-password" data-target="accPassConfirm" aria-label="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-hero-primary" id="accPasswordSaveBtn">
                        <i class="bi bi-check2 me-1"></i> Simpan Password
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
