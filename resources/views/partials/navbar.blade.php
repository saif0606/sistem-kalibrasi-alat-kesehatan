{{-- ==========================================================
     NAVBAR — Global component, dipakai di semua halaman
     (publik maupun area member setelah login).

     Satu navbar, dua kondisi. Dashboard BUKAN aplikasi terpisah,
     jadi tidak ada navbar kedua — hanya isi menu & aksi kanan yang
     berubah tergantung status login.

     $isLoggedIn dibaca dari Auth::check() (session Laravel
    sungguhan) — sehingga navbar tetap konsisten versi login di
    halaman mana pun (Berita, Layanan, Kontak, dst.) selama sesi
    masih aktif, dan tidak pernah kembali ke versi guest sendirian
    tanpa logout eksplisit.
========================================================== --}}
<style>
/* Notification bell (Modern Glassmorphism) */
.notif-btn {
    width: 42px; height: 42px;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem;
    color: var(--color-heading, #0F172A);
    cursor: pointer;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
}
.notif-btn:hover { 
    background: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.1);
    color: var(--color-primary-green, #16A34A); 
}
.notif-badge {
    position: absolute; top: -2px; right: -2px;
    background: var(--color-primary-green, #16A34A); 
    color: #fff;
    font-size: 0.65rem; font-weight: 700;
    min-width: 18px; height: 18px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 4px;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}
[data-theme="dark"] .notif-btn {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(255, 255, 255, 0.1);
    color: #E2E8F0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
[data-theme="dark"] .notif-btn:hover {
    background: rgba(30, 41, 59, 0.8);
    color: #16A34A;
}
[data-theme="dark"] .notif-badge {
    border-color: #0F172A;
}
</style>
@php
    $isLoggedIn = auth()->check();

    $memberUser = $isLoggedIn ? [
        'name' => auth()->user()->name,
        'email' => auth()->user()->email,
        'initial' => mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)),
    ] : null;

    $guestNavItems = [
        ['route' => 'home', 'label' => 'Beranda'],
        ['route' => 'profil', 'label' => 'Profil'],
        ['route' => 'layanan', 'label' => 'Layanan'],
        ['route' => 'user.calibrations.index', 'label' => 'Proses'],
        ['route' => 'berita', 'label' => 'Berita'],
        ['route' => 'user.chat.index', 'label' => 'Chatbot'],
        ['route' => 'kontak', 'label' => 'Kontak'],
    ];

    $memberNavItems = [
        ['route' => 'dashboard', 'label' => 'Dashboard'],
        ['route' => 'user.calibrations.create', 'label' => 'Ajukan Kalibrasi'],
        ['route' => 'layanan', 'label' => 'Layanan'],
        ['route' => 'user.calibrations.index', 'label' => 'Proses'],
        ['route' => 'berita', 'label' => 'Berita'],
        ['route' => 'user.chat.index', 'label' => 'Chatbot'],
        ['route' => 'kontak', 'label' => 'Kontak'],
    ];

    $navItems = $isLoggedIn ? $memberNavItems : $guestNavItems;
    $brandHref = $isLoggedIn ? route('dashboard') : route('home');
@endphp
<nav class="navbar navbar-expand-xl navbar-main fixed-top" id="mainNavbar">
    <div class="container-xxl">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $brandHref }}">
            <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Logo UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung" class="navbar-logo">
            <span class="navbar-brand-text">
                <span class="brand-line-1">UPTD Balai Pengujian &amp; Kalibrasi</span>
                <span class="brand-line-2">Alat Kesehatan &bull; Provinsi Lampung</span>
        </a>

        @php
            if (auth()->check()) {
                $notifCert = \App\Models\CalibrationRequest::where('user_id', auth()->id())
                    ->where('status', 'Sertifikat')
                    ->whereNull('cert_ready_notif_dismissed_at')
                    ->count();
                $notifChat = \App\Models\ChatMessage::where('user_id', auth()->id())
                    ->where('sender_role', 'admin')
                    ->whereNull('read_by_user_at')
                    ->count();
                $notifTotal = $notifCert + $notifChat;
            } else {
                $notifCert = 0; $notifChat = 0; $notifTotal = 0;
            }
        @endphp

        {{-- Mobile: bell + toggler side-by-side --}}
        <div class="d-flex d-xl-none align-items-center gap-2 ms-auto">
            @auth
            <div class="dropdown">
                <button class="notif-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    @if($notifTotal > 0)
                        <span class="notif-badge">{{ $notifTotal > 9 ? '9+' : $notifTotal }}</span>
                    @endif
                </button>
                @include('partials._notif_dropdown', ['notifCert'=>$notifCert,'notifChat'=>$notifChat,'notifTotal'=>$notifTotal])
            </div>
            @endauth
            {{-- Mobile toggler --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-bars">
                    <span></span><span></span><span></span>
                </span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- Menu — satu route per halaman, isi berubah sesuai status login --}}
            <ul class="navbar-nav mx-xl-auto">
                @foreach ($navItems as $item)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                           href="{{ route($item['route']) }}">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Right actions: Dark/Light toggle + Login CTA / Dropdown akun --}}
            <div class="d-flex align-items-center gap-3 mt-3 mt-xl-0">

                @auth
                {{-- Desktop: bell di dalam collapse, setelah menu, sebelum akun --}}
                <div class="dropdown d-none d-xl-flex align-items-center">
                    <button class="notif-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                        <i class="bi bi-bell-fill"></i>
                        @if($notifTotal > 0)
                            <span class="notif-badge">{{ $notifTotal > 9 ? '9+' : $notifTotal }}</span>
                        @endif
                    </button>
                    @include('partials._notif_dropdown', ['notifCert'=>$notifCert,'notifChat'=>$notifChat,'notifTotal'=>$notifTotal])
                </div>
                @endauth

                @if ($isLoggedIn)
                    {{-- Dropdown akun user --}}
                    <div class="dropdown member-account-dropdown">
                        <button class="member-account-trigger" type="button" id="memberAccountMenu"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="member-avatar">{{ $memberUser['initial'] }}</span>
                            <span class="member-account-name d-none d-sm-inline">{{ $memberUser['name'] }}</span>
                            <i class="bi bi-chevron-down member-account-caret"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end member-account-menu" aria-labelledby="memberAccountMenu">
                            <li class="member-account-menu-header">
                                <span class="member-avatar member-avatar-lg">{{ $memberUser['initial'] }}</span>
                                <span>
                                    <strong>{{ $memberUser['name'] }}</strong>
                                    <small>{{ $memberUser['email'] }}</small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                                    <i class="bi bi-person-circle"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard.riwayat') }}">
                                    <i class="bi bi-clock-history"></i> Riwayat Pengajuan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profil') }}">
                                    <i class="bi bi-bank"></i> Profil UPTD
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item dropdown-item-danger">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                @endif

                <div class="theme-toggle" role="group" aria-label="Ganti tema tampilan">
                    <button type="button" id="themeLightBtn" class="theme-toggle-btn" aria-pressed="true" aria-label="Mode terang">
                        <i class="bi bi-sun-fill"></i>
                    </button>
                    <button type="button" id="themeDarkBtn" class="theme-toggle-btn" aria-pressed="false" aria-label="Mode gelap">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
