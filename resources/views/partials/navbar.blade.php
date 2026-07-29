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
@php
    $isLoggedIn = auth()->check();

<<<<<<< HEAD
    $memberUser = $isLoggedIn ? [
        'name' => auth()->user()->name,
        'email' => auth()->user()->email,
        'initial' => mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)),
    ] : null;

    $guestNavItems = [
        ['route' => 'home', 'label' => 'Beranda'],
        ['route' => 'profil', 'label' => 'Profil'],
        ['route' => 'layanan', 'label' => 'Layanan'],
        ['route' => 'proses', 'label' => 'Proses'],
        ['route' => 'berita', 'label' => 'Berita'],
        ['route' => 'chatbot', 'label' => 'Chatbot'],
        ['route' => 'kontak', 'label' => 'Kontak'],
    ];

    $memberNavItems = [
        ['route' => 'dashboard', 'label' => 'Dashboard'],
        ['route' => 'dashboard.pengajuan', 'label' => 'Ajukan Kalibrasi'],
        ['route' => 'layanan', 'label' => 'Layanan'],
        ['route' => 'proses', 'label' => 'Proses'],
        ['route' => 'berita', 'label' => 'Berita'],
        ['route' => 'chatbot', 'label' => 'Chatbot'],
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
            </span>
=======
    {{-- BENAR --}}
<div class="nav-right">
  @auth
    @php
    $notifCert = \App\Models\CalibrationRequest::where('user_id', auth()->id())
    ->where('status', 'Sertifikat')
    ->whereNull('cert_ready_notif_dismissed_at')
    ->count();
        $notifChat = \App\Models\ChatMessage::where('user_id', auth()->id())
            ->where('sender_role', 'admin')
            ->whereNull('read_by_user_at')
            ->count();
        $notifTotal = $notifCert + $notifChat;
    @endphp
    <div class="dropdown">
        <button class="notif-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
            <i class="bi bi-bell-fill"></i>
            @if($notifTotal > 0)
                <span class="notif-badge">{{ $notifTotal > 9 ? '9+' : $notifTotal }}</span>
            @endif
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="min-width:300px; border-radius:14px; overflow:hidden;">
            <li class="px-3 py-2 border-bottom" style="background: linear-gradient(120deg, #094a73, #17a45c);">
                <span style="font-weight:700; font-size:0.82rem; color:#fff;"><i class="bi bi-bell-fill me-1"></i> Notifikasi</span>
            </li>
            @if($notifCert > 0)
                <li>
                    <a href="{{ route('user.calibrations.index') }}" class="dropdown-item d-flex align-items-start gap-2 py-2">
                        <i class="bi bi-patch-check-fill" style="color:#17a45c;"></i>
                        <span style="font-size:0.82rem;">{{ $notifCert }} sertifikat siap diambil</span>
                    </a>
                </li>
            @endif
            @if($notifChat > 0)
                <li>
                    <a href="{{ route('user.chat.index') }}" class="dropdown-item d-flex align-items-start gap-2 py-2">
                        <i class="bi bi-chat-dots-fill" style="color:#2b6ff0;"></i>
                        <span style="font-size:0.82rem;">{{ $notifChat }} balasan admin belum dibaca</span>
                    </a>
                </li>
            @endif
            @if($notifTotal === 0)
                <li class="px-3 py-3 text-center" style="font-size:0.78rem; color:#8a9bab;">Tidak ada notifikasi baru</li>
            @endif
        </ul>
    </div>
  @endif
      @auth
        <a href="{{ route('user.calibrations.index') }}" class="login-link d-none d-lg-inline" style="display:flex;align-items:center;gap:6px;">
          <i class="bi bi-person-circle"></i> {{ Str::limit(Auth::user()->name, 12) }}
>>>>>>> b1e5967 (benerin bagian admin pesanan, user, notifikasi, edit  dokumen pengajuan, chat)
        </a>

        {{-- Mobile toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-bars">
                <span></span><span></span><span></span>
            </span>
        </button>

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
