<!-- ================= NAVBAR ================= -->
<nav class="navbar-uptd" id="mainNavbar">
  <div class="container">
    <a href="{{ url('/') }}" class="brand-link">
      <span class="brand-logo-wrap">
        <img src="{{ asset('images/logo-uptd-mark.png') }}" alt="Logo UPTD Balai Pengujian & Kalibrasi Alat Kesehatan">
      </span>
      <span class="brand-text">
        <span class="brand-title">UPTD Balai Pengujian &amp; Kalibrasi</span>
        <span class="brand-sub">Alat Kesehatan &bull; Provinsi Lampung</span>
      </span>
    </a>

    <div class="nav-overlay" id="navOverlay"></div>

    <ul class="nav-menu" id="navMenu">
      <li><a href="#beranda" class="nav-link active">Beranda</a></li>
      <li><a href="#tentang" class="nav-link">Tentang</a></li>
      <li><a href="#layanan" class="nav-link">Layanan</a></li>
      <li><a href="#kalibrasi" class="nav-link">Kalibrasi</a></li>
      <li><a href="#artikel" class="nav-link">Artikel</a></li>
      <li><a href="#kontak" class="nav-link">Kontak</a></li>
      <li class="d-lg-none login-mobile">
        <a href="{{ url('/login') }}" class="nav-link">Login</a>
      </li>
    </ul>

    <div class="nav-right">
      <a href="{{ url('/login') }}" class="login-link d-none d-lg-inline">Login</a>
      <button class="theme-toggle" id="themeToggle" aria-label="Ganti tema terang/gelap">
        <span class="knob"><i class="bi bi-sun-fill"></i></span>
      </button>
      <button class="navbar-toggler-uptd" id="navToggler" aria-label="Buka menu">
        <i class="bi bi-list"></i>
      </button>
    </div>
  </div>
</nav>
