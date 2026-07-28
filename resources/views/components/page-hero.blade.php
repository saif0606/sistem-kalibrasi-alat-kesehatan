{{-- ==========================================================
     REUSABLE COMPONENT — Page Hero (header halaman dalam)
     Dipakai oleh setiap halaman selain Beranda: /profil, /layanan,
     /proses, /berita, /kontak, /chatbot — begitu masing-masing
     mulai didesain penuh, tinggal pakai komponen ini.

     Props:
       title    (string) — judul halaman, tampil besar
       current  (string, optional) — label breadcrumb aktif (default: $title)
       subtitle (string, optional) — deskripsi singkat di bawah judul
========================================================== --}}
@props([
    'title' => 'Halaman',
    'current' => null,
    'subtitle' => null,
    'tapis' => 'tr-bl',
])

@php
    $current = $current ?? $title;
@endphp

<section class="page-hero">
    <x-tapis-decoration :corners="$tapis" />
    <div class="page-hero-ornaments" aria-hidden="true">
        <span class="hero-blur-blob hero-blur-blob-1"></span>
        <span class="hero-blur-blob hero-blur-blob-2"></span>
    </div>
    <div class="container position-relative text-center">
        <nav aria-label="breadcrumb" data-aos="fade-up">
            <ol class="page-hero-breadcrumb">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li aria-hidden="true">/</li>
                <li class="active" aria-current="page">{{ $current }}</li>
            </ol>
        </nav>
        <h1 class="page-hero-title" data-aos="fade-up" data-aos-delay="80">{{ $title }}</h1>
        <x-tapis-divider />
        @if ($subtitle)
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="140">{{ $subtitle }}</p>
        @endif
    </div>
</section>
