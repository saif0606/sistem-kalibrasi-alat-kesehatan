@extends('layouts.app')

@section('title', 'UPTD Balai Pengujian & Kalibrasi Alat Kesehatan Provinsi Lampung')
@section('description', 'UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung - Layanan pengujian dan kalibrasi alat kesehatan profesional, akurat, dan terpercaya.')

@section('content')

<!-- ================= HERO ================= -->
<section class="hero" id="beranda">
  <span class="hero-ornament"><span></span><span></span><span></span></span>
  <span class="hero-dots">
    <span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span><span></span>
  </span>

  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-right">
        <span class="badge-accred"><i class="bi bi-patch-check-fill"></i> Terakreditasi ISO/IEC 17025</span>
        <h1 class="hero-title">
          UPTD Balai Pengujian dan Kalibrasi
          <span class="grad-text">Alat Kesehatan</span>
          Provinsi Lampung
        </h1>
        <p class="hero-lead">
          Melayani pengujian dan kalibrasi alat kesehatan secara profesional, akurat, dan terpercaya
          sesuai standar bagi fasilitas kesehatan di seluruh Provinsi Lampung.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#kontak" class="btn-brand-primary">Ajukan Kalibrasi <i class="bi bi-arrow-right"></i></a>
          <a href="#layanan" class="btn-brand-outline">Lihat Layanan <i class="bi bi-grid-3x3-gap"></i></a>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left">
        <div class="hero-media">
          <div class="float-card card-top">
            <span class="icon-circle"><i class="bi bi-check-lg"></i></span>
            <span>
              <strong data-target="1500" data-suffix="+">1.500+</strong>
              <span>Alat Terkalibrasi</span>
            </span>
          </div>

          <div class="img-wrap">
            <img src="https://images.unsplash.com/photo-1581093458791-9d0fb3b1f4c9?auto=format&fit=crop&w=900&q=80" alt="Kalibrasi alat kesehatan">
          </div>

          <div class="float-card card-bottom">
            <span class="icon-circle"><i class="bi bi-shield-check"></i></span>
            <span>
              <strong>99%</strong>
              <span>Tingkat Kepuasan</span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= FEATURE STRIP ================= -->
<div class="container feature-strip">
  <div class="row g-3 g-lg-4">
    <div class="col-md-4" data-aos="fade-up">
      <div class="feature-card">
        <span class="icon-box"><i class="bi bi-award"></i></span>
        <div>
          <h6>Terakreditasi ISO/IEC 17025</h6>
          <p>Sesuai standar internasional untuk hasil pengujian yang akurat dan dapat dipertanggungjawabkan.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
      <div class="feature-card">
        <span class="icon-box"><i class="bi bi-sliders"></i></span>
        <div>
          <h6>Kalibrasi Alat Kesehatan</h6>
          <p>Menjamin akurasi dan keandalan alat kesehatan sesuai standar acuan nasional dan internasional.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
      <div class="feature-card">
        <span class="icon-box"><i class="bi bi-clipboard2-pulse"></i></span>
        <div>
          <h6>Pengujian Alat Kesehatan</h6>
          <p>Pemeriksaan performa dan keamanan alat kesehatan sebelum digunakan pada pasien.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ================= TENTANG (soft blue) ================= -->
<section class="section section-soft-blue" id="tentang">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-right">
        <span class="eyebrow"><i class="bi bi-building"></i> Tentang Kami</span>
        <h2 class="section-title">Unit Pelaksana Teknis Daerah Terpercaya</h2>
        <p class="section-sub mb-4">
          UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung berkomitmen menjaga
          keamanan dan keandalan alat kesehatan di seluruh fasilitas kesehatan melalui layanan
          pengujian dan kalibrasi yang profesional, akurat, dan sesuai standar ISO/IEC 17025.
        </p>
        <div class="row g-3">
          <div class="col-6">
            <div class="d-flex gap-2 align-items-start">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span class="small">Tenaga teknis bersertifikat</span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex gap-2 align-items-start">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span class="small">Peralatan standar nasional</span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex gap-2 align-items-start">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span class="small">Laporan hasil terpercaya</span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex gap-2 align-items-start">
              <i class="bi bi-check-circle-fill text-success mt-1"></i>
              <span class="small">Jadwal layanan fleksibel</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="zoom-in">
        <div class="hero-media">
          <div class="img-wrap" style="aspect-ratio:4/3;">
            <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=900&q=80" alt="Tim UPTD Balai Kalibrasi">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= LAYANAN (white) ================= -->
<section class="section section-white" id="layanan">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3" data-aos="fade-up">
      <div>
        <span class="eyebrow"><i class="bi bi-stars"></i> Layanan Kami</span>
        <h2 class="section-title mb-2">Layanan Unggulan Kami</h2>
        <p class="section-sub mb-0">Kami menyediakan berbagai layanan profesional untuk mendukung keandalan dan keselamatan alat kesehatan Anda.</p>
      </div>
      <a href="#kontak" class="btn-link-brand">Lihat Semua Layanan <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4" data-aos="fade-up">
        <div class="service-card">
          <div class="thumb">
            <img src="https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?auto=format&fit=crop&w=700&q=80" alt="Kalibrasi Alat Kesehatan">
            <span class="badge-icon"><i class="bi bi-sliders"></i></span>
          </div>
          <div class="body">
            <h5>Kalibrasi Alat Kesehatan</h5>
            <p>Layanan kalibrasi berbagai jenis alat kesehatan dengan standar dan prosedur yang ketat.</p>
            <a href="#kontak" class="more-link">Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="service-card">
          <div class="thumb">
            <img src="https://images.unsplash.com/photo-1580281657702-257584239a55?auto=format&fit=crop&w=700&q=80" alt="Pengujian Alat Kesehatan">
            <span class="badge-icon"><i class="bi bi-clipboard2-pulse"></i></span>
          </div>
          <div class="body">
            <h5>Pengujian Alat Kesehatan</h5>
            <p>Pengujian keamanan dan performa alat kesehatan sesuai regulasi yang berlaku.</p>
            <a href="#kontak" class="more-link">Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mx-auto" data-aos="fade-up" data-aos-delay="200">
        <div class="service-card">
          <div class="thumb">
            <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=700&q=80" alt="Konsultasi dan Pelatihan">
            <span class="badge-icon"><i class="bi bi-people"></i></span>
          </div>
          <div class="body">
            <h5>Konsultasi &amp; Pelatihan</h5>
            <p>Konsultasi teknis dan pelatihan untuk meningkatkan kompetensi SDM kesehatan.</p>
            <a href="#kontak" class="more-link">Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= STATS (gradient brand) ================= -->
<section class="section-soft-green py-5" id="kalibrasi">
  <div class="container">
    <div class="stats-band" data-aos="zoom-in">
      <div class="row g-4">
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <span class="num" data-target="1500" data-suffix="+">0</span>
            <span class="label">Alat Terkalibrasi</span>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <span class="num" data-target="200" data-suffix="+">0</span>
            <span class="label">Fasilitas Kesehatan Mitra Kami</span>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <span class="num" data-target="99" data-suffix="%">0</span>
            <span class="label">Tingkat Kepuasan Pelanggan</span>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-item">
            <span class="num">ISO/IEC 17025</span>
            <span class="label">Laboratorium Terakreditasi</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= PROSES KALIBRASI (white) ================= -->
<section class="section section-white">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="eyebrow justify-content-center"><i class="bi bi-diagram-3"></i> Alur Layanan</span>
      <h2 class="section-title">Proses Kalibrasi</h2>
      <p class="section-sub mx-auto">Tahapan layanan kalibrasi kami dirancang mudah, transparan, dan efisien.</p>
    </div>
    <div class="row g-4 text-center">
      <div class="col-md-3" data-aos="fade-up">
        <div class="feature-card flex-column text-center align-items-center">
          <span class="icon-box mb-2"><i class="bi bi-file-earmark-text"></i></span>
          <h6>1. Pengajuan</h6>
          <p>Ajukan permohonan kalibrasi secara online atau langsung.</p>
        </div>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-card flex-column text-center align-items-center">
          <span class="icon-box mb-2"><i class="bi bi-truck"></i></span>
          <h6>2. Penjadwalan</h6>
          <p>Tim teknis menjadwalkan kunjungan atau penerimaan alat.</p>
        </div>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-card flex-column text-center align-items-center">
          <span class="icon-box mb-2"><i class="bi bi-gear"></i></span>
          <h6>3. Kalibrasi</h6>
          <p>Alat diuji dan dikalibrasi sesuai standar ISO/IEC 17025.</p>
        </div>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-card flex-column text-center align-items-center">
          <span class="icon-box mb-2"><i class="bi bi-file-earmark-check"></i></span>
          <h6>4. Sertifikat</h6>
          <p>Hasil dan sertifikat kalibrasi diterbitkan dan diserahkan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= ARTIKEL (soft green) ================= -->
<section class="section section-soft-green" id="artikel">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gap-3" data-aos="fade-up">
      <div>
        <span class="eyebrow"><i class="bi bi-newspaper"></i> Artikel</span>
        <h2 class="section-title mb-2">Berita &amp; Informasi Terbaru</h2>
        <p class="section-sub mb-0">Dapatkan informasi terbaru seputar kegiatan, layanan, dan edukasi kesehatan.</p>
      </div>
      <a href="#" class="btn-link-brand">Lihat Semua Artikel <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4" data-aos="fade-up">
        <div class="article-card">
          <div class="thumb">
            <img src="https://images.unsplash.com/photo-1584982751601-97dcc096659c?auto=format&fit=crop&w=700&q=80" alt="Artikel kalibrasi">
          </div>
          <div class="body">
            <span class="article-date"><i class="bi bi-calendar3"></i> 2 Mei 2026</span>
            <h6>Peran Kalibrasi dalam Menjaga Keamanan Pasien</h6>
            <a href="#" class="more-link">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="article-card">
          <div class="thumb">
            <img src="https://images.unsplash.com/photo-1631549916768-4119b2e5f926?auto=format&fit=crop&w=700&q=80" alt="Artikel pengujian rutin">
          </div>
          <div class="body">
            <span class="article-date"><i class="bi bi-calendar3"></i> 28 April 2026</span>
            <h6>Pentingnya Pengujian Rutin Alat Kesehatan di Fasilitas Kesehatan</h6>
            <a href="#" class="more-link">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mx-auto" data-aos="fade-up" data-aos-delay="200">
        <div class="article-card">
          <div class="thumb">
            <img src="https://images.unsplash.com/photo-1550831107-1553da8c8464?auto=format&fit=crop&w=700&q=80" alt="Artikel tips perawatan">
          </div>
          <div class="body">
            <span class="article-date"><i class="bi bi-calendar3"></i> 20 April 2026</span>
            <h6>Tips Merawat Alat Kesehatan agar Tetap Akurat dan Awet</h6>
            <a href="#" class="more-link">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= CTA (gradient brand) ================= -->
<section class="section-brand-gradient py-5" id="kontak-cta">
  <div class="container text-center py-4" data-aos="zoom-in">
    <h2 class="text-white mb-3" style="font-size:1.9rem;">Siap Mengajukan Kalibrasi Alat Kesehatan Anda?</h2>
    <p class="text-white-50 mb-4 mx-auto" style="max-width:560px;">Hubungi tim kami sekarang dan dapatkan layanan pengujian &amp; kalibrasi yang profesional, akurat, dan terpercaya.</p>
    <a href="https://api.whatsapp.com/send/?phone=6281292923438&text&type=phone_number&app_absent=0" target="_blank" rel="noopener" class="btn-brand-primary" style="background:#fff;color:var(--green-700);box-shadow:0 10px 24px rgba(0,0,0,0.2);">
      <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
    </a>
  </div>
</section>

@endsection
