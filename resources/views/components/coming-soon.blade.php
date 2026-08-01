{{-- ==========================================================
     REUSABLE COMPONENT — Placeholder "Sedang Dikembangkan"
     Dipakai oleh semua halaman yang belum didesain penuh:
     /profil, /layanan, /proses, /berita, /kontak, /chatbot, /login

     Props:
       title       (string)  — judul halaman
       description (string)  — deskripsi singkat isi halaman nanti
       icon        (string)  — kelas Bootstrap Icons, mis. "bi-person-badge"
       eta         (string, optional) — catatan tahap pengembangan
========================================================== --}}
@props([
    'title' => 'Halaman',
    'description' => 'Halaman ini sedang dalam tahap pengembangan.',
    'icon' => 'bi-hourglass-split',
    'eta' => null,
    'backRoute' => 'home',
    'backLabel' => 'Kembali ke Beranda',
])

<section class="coming-soon-section">
    <x-tapis-decoration corners="tl-br" />
    <div class="container position-relative">
        <div class="coming-soon-card" data-aos="zoom-in">
            <div class="coming-soon-icon">
                <i class="bi {{ $icon }}"></i>
            </div>
            <span class="coming-soon-badge">
                <i class="bi bi-tools"></i> Sedang Dikembangkan
            </span>
            <h1 class="coming-soon-title">{{ $title }}</h1>
            <p class="coming-soon-desc">{{ $description }}</p>

            @if ($eta)
                <p class="coming-soon-eta"><i class="bi bi-info-circle me-1"></i>{{ $eta }}</p>
            @endif

            {{ $slot ?? '' }}

            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="{{ route($backRoute) }}" class="btn btn-outline-primary-custom">
                    <i class="bi bi-arrow-left me-1"></i> {{ $backLabel }}
                </a>
                <a href="https://api.whatsapp.com/send/?phone=6281292923438&text&type=phone_number&app_absent=0"
                   target="_blank" rel="noopener" class="btn btn-hero-primary">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
