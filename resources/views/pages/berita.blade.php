@extends('layouts.app')

@section('title', 'Berita & Informasi — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        // $beritaList disuntik dari route (uptdBeritaData()), sudah terurut
        // dari yang terbaru — nanti diganti Berita::latest()->get().
        $categoryClass = [
            'Instagram' => 'berita-cat-dokumentasi',
            'Youtube' => 'berita-cat-dokumentasi',
            'Berita' => 'berita-cat-berita',
            'Pengumuman' => 'berita-cat-pengumuman',
            'Edukasi' => 'berita-cat-edukasi',
            'Dokumentasi' => 'berita-cat-dokumentasi',
        ];
        $sourceIcon = [
            'Instagram' => 'bi-instagram',
            'Facebook' => 'bi-facebook',
            'TikTok' => 'bi-tiktok',
            'Website' => 'bi-globe2',
        ];
        // Kategori filter mengikuti kategori yang benar-benar ada di data
        // (bukan daftar tetap) supaya tidak ada tombol filter yang hasilnya
        // selalu kosong.
        $categories = array_merge(['Semua'], collect($beritaList)->pluck('kategori')->unique()->values()->all(), ['Media Sosial']);

        // Dihitung langsung dari $beritaList (bukan angka tetap) supaya selalu
        // sesuai jumlah item yang benar-benar ada — tidak ada statistik dummy.
        $totalSocial = collect($beritaList)->where('is_social', true)->count();
        $totalWebsite = collect($beritaList)->where('is_social', false)->count();
        $stats = [
            ['label' => 'Total Informasi', 'value' => count($beritaList), 'icon' => 'bi-grid-3x3-gap', 'tone' => 'green'],
            ['label' => 'Dari Instagram', 'value' => $totalSocial, 'icon' => 'bi-instagram', 'tone' => 'amber'],
            ['label' => 'Dari Website Resmi', 'value' => $totalWebsite, 'icon' => 'bi-globe2', 'tone' => 'blue'],
        ];

        // ---- Spotlight: Update Terbaru Media Sosial ----
        // 1 featured besar ('featured_social') + 3 post kecil terbaru berikutnya.
        $socialFeaturedSlug = null;
        $socialFeatured = null;
        $socialSmall = [];
        foreach ($beritaList as $slug => $item) {
            if (!$item['is_social']) continue;
            if (!empty($item['featured_social']) && !$socialFeatured) {
                $socialFeaturedSlug = $slug;
                $socialFeatured = $item;
            }
        }
        foreach ($beritaList as $slug => $item) {
            if (!$item['is_social'] || $slug === $socialFeaturedSlug) continue;
            $socialSmall[$slug] = $item;
            if (count($socialSmall) >= 3) break;
        }

        // ---- Spotlight: Berita Website (maksimal 3) ----
        $websiteFeatured = [];
        foreach ($beritaList as $slug => $item) {
            if (!empty($item['featured_website'])) {
                $websiteFeatured[$slug] = $item;
            }
            if (count($websiteFeatured) >= 3) break;
        }
    @endphp

    <x-page-hero
        title="Berita & Informasi"
        subtitle="Ikuti perkembangan terbaru layanan, kegiatan, edukasi, pengumuman, dokumentasi, dan aktivitas media sosial resmi UPTD Balai Pengujian & Kalibrasi."
    />

    {{-- ============================================================
         SEARCH BAR
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="berita-search-wrap" data-aos="fade-up">
                <div class="tarif-search-box berita-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="beritaSearch" placeholder="Cari informasi...">
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         UPDATE TERBARU MEDIA SOSIAL (prioritas paling atas)
    ============================================================ --}}
    @if ($socialFeatured)
        <section class="section-padding pb-0">
            <div class="container">
                <div class="mb-4" data-aos="fade-up">
                    <h2 class="prs-list-title mb-1"><i class="bi bi-instagram me-2"></i>Update Terbaru Media Sosial</h2>
                    <p class="berita-section-subtitle">Informasi terbaru langsung dari media sosial resmi UPTD.</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6" data-aos="fade-up">
                        <a href="{{ $socialFeatured['sumber_url'] }}" target="_blank" rel="noopener" class="berita-social-featured">
                            <div class="berita-social-featured-visual">
                                @if ($socialFeatured['gambar'])
                                    <img src="{{ $socialFeatured['gambar'] }}" alt="{{ $socialFeatured['judul'] }}" loading="lazy">
                                @else
                                    <i class="bi {{ $socialFeatured['icon'] }}"></i>
                                @endif
                                <span class="berita-source-badge berita-source-badge-onvisual"><i class="bi {{ $sourceIcon[$socialFeatured['sumber']] ?? 'bi-globe2' }}"></i> {{ $socialFeatured['sumber'] }}</span>
                            </div>
                            <div class="berita-social-featured-body">
                                <h3>{{ $socialFeatured['judul'] }}</h3>
                                <p>{{ $socialFeatured['ringkasan'] }}</p>
                                <div class="berita-social-featured-footer">
                                    <span class="berita-date"><i class="bi bi-clock-history"></i> {{ $socialFeatured['tanggal']->diffForHumans() }}</span>
                                    <span class="berita-card-link">Lihat Postingan <i class="bi bi-box-arrow-up-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-6">
                        <div class="berita-social-small-stack">
                            @foreach ($socialSmall as $item)
                                <a href="{{ $item['sumber_url'] }}" target="_blank" rel="noopener" class="berita-social-small-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                                    <span class="berita-social-small-visual">
                                        @if ($item['gambar'])
                                            <img src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}" loading="lazy">
                                        @else
                                            <i class="bi {{ $item['icon'] }}"></i>
                                        @endif
                                    </span>
                                    <span class="berita-social-small-body">
                                        <span class="berita-source-badge"><i class="bi {{ $sourceIcon[$item['sumber']] ?? 'bi-globe2' }}"></i> {{ $item['sumber'] }}</span>
                                        <strong>{{ $item['judul'] }}</strong>
                                        <span class="berita-date"><i class="bi bi-clock-history"></i> {{ $item['tanggal']->diffForHumans() }}</span>
                                    </span>
                                    <i class="bi bi-box-arrow-up-right berita-social-small-arrow"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         BERITA WEBSITE (maksimal 3)
    ============================================================ --}}
    @if (count($websiteFeatured))
        <section class="section-padding pb-0">
            <div class="container">
                <div class="mb-4" data-aos="fade-up">
                    <h2 class="prs-list-title mb-1"><i class="bi bi-globe2 me-2"></i>Berita Website</h2>
                    <p class="berita-section-subtitle">Artikel resmi seputar kegiatan dan pelayanan UPTD.</p>
                </div>

                <div class="row g-4">
                    @foreach ($websiteFeatured as $slug => $item)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                            <a href="{{ route('berita.show', $slug) }}" class="berita-card">
                                <div class="berita-card-visual">
                                    @if ($item['gambar'])
                                        <img src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}" loading="lazy">
                                    @else
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    @endif
                                    <span class="berita-source-badge berita-source-badge-onvisual"><i class="bi {{ $sourceIcon[$item['sumber']] ?? 'bi-globe2' }}"></i> {{ $item['sumber'] }}</span>
                                </div>
                                <div class="berita-card-body">
                                    <span class="berita-cat-badge {{ $categoryClass[$item['kategori']] ?? 'berita-cat-dokumentasi' }}">{{ $item['kategori'] }}</span>
                                    <h3>{{ $item['judul'] }}</h3>
                                    <p>{{ $item['ringkasan'] }}</p>
                                    <div class="berita-card-footer">
                                        <span class="berita-date"><i class="bi bi-clock-history"></i> {{ $item['tanggal']->diffForHumans() }}</span>
                                        <span class="berita-card-link">Baca Selengkapnya <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         STATISTIK RINGKAS
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="row g-3">
                @foreach ($stats as $i => $stat)
                    <div class="col-4" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                        <div class="berita-stat-card">
                            <span class="dash-stat-icon dash-stat-icon-{{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
                            <span class="berita-stat-number">{{ $stat['value'] }}</span>
                            <span class="berita-stat-label">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         FILTER KATEGORI
    ============================================================ --}}
    <section class="section-padding pb-0">
        <div class="container">
            <div class="berita-filter-row" data-aos="fade-up">
                @foreach ($categories as $cat)
                    @php
                        $filterValue = match (true) {
                            $cat === 'Semua' => '',
                            $cat === 'Media Sosial' => '__social__',
                            default => $cat,
                        };
                    @endphp
                    <button type="button" class="berita-filter-pill {{ $cat === 'Semua' ? 'active' : '' }}" data-filter="{{ $filterValue }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         MEDIA CENTER — seluruh informasi, urut waktu terbaru
    ============================================================ --}}
    <section class="section-padding">
        <div class="container">
            <div class="mb-4" data-aos="fade-up">
                <h2 class="prs-list-title mb-1"><i class="bi bi-grid-3x3-gap me-2"></i>Media Center</h2>
                <p class="berita-section-subtitle">Seluruh update UPTD dari website dan media sosial, dalam satu tempat.</p>
            </div>

            <div class="row g-4" id="beritaGrid">
                @foreach ($beritaList as $slug => $item)
                    <div class="col-md-6 col-lg-4 berita-grid-item" data-title="{{ strtolower($item['judul']) }}" data-category="{{ $item['kategori'] }}"
                         data-social="{{ $item['is_social'] ? '1' : '0' }}" data-aos="fade-up" data-aos-delay="{{ min($loop->index * 40, 200) }}">
                        <a href="{{ route('berita.show', $slug) }}" class="berita-card">
                            <div class="berita-card-visual">
                                @if ($item['gambar'])
                                    <img src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}" loading="lazy">
                                @else
                                    <i class="bi {{ $item['icon'] }}"></i>
                                @endif
                                <span class="berita-source-badge berita-source-badge-onvisual"><i class="bi {{ $sourceIcon[$item['sumber']] ?? 'bi-globe2' }}"></i> {{ $item['sumber'] }}</span>
                            </div>
                            <div class="berita-card-body">
                                <span class="berita-cat-badge {{ $categoryClass[$item['kategori']] ?? 'berita-cat-dokumentasi' }}">{{ $item['kategori'] }}</span>
                                <h3>{{ $item['judul'] }}</h3>
                                <p>{{ $item['ringkasan'] }}</p>
                                <div class="berita-card-footer">
                                    <span class="berita-date"><i class="bi bi-clock-history"></i> {{ $item['tanggal']->diffForHumans() }}</span>
                                    <span class="berita-card-link">Baca Selengkapnya <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="tarif-empty d-none" id="beritaEmpty">
                <i class="bi bi-search d-block mb-2" style="font-size:1.75rem;"></i>
                Tidak ada informasi yang cocok dengan pencarian/filter Anda.
            </div>
        </div>
    </section>

@endsection
