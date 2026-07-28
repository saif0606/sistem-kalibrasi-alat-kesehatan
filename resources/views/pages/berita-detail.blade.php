@extends('layouts.app')

@section('title', ($item['judul'] ?? 'Berita Tidak Ditemukan') . ' — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan Provinsi Lampung')

@section('content')

    @php
        $categoryClass = [
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
        // Sumber media sosial menampilkan tombol "Lihat Postingan Asli";
        // sumber "Website" cukup jadi keterangan tanpa tombol keluar.
        $socialSources = ['Instagram', 'Facebook', 'TikTok'];
    @endphp

    <x-page-hero
        :title="$item['judul'] ?? 'Berita Tidak Ditemukan'"
        current="Berita & Informasi"
        :subtitle="$item ? null : 'Informasi yang Anda cari mungkin sudah dipindahkan atau tidak tersedia.'"
    />

    <section class="section-padding">
        <div class="container">
            @if ($item)
                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        <a href="{{ route('berita') }}" class="berita-back-link" data-aos="fade-up">
                            <i class="bi bi-arrow-left"></i> Kembali ke Berita & Informasi
                        </a>

                        <div class="berita-detail-visual" data-aos="fade-up">
                            @if ($item['gambar'])
                                <img src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}" loading="lazy">
                            @else
                                <i class="bi {{ $item['icon'] }}"></i>
                            @endif
                        </div>

                        <div class="berita-detail-meta" data-aos="fade-up">
                            <span class="berita-cat-badge {{ $categoryClass[$item['kategori']] }}">{{ $item['kategori'] }}</span>
                            <span class="berita-source-badge"><i class="bi {{ $sourceIcon[$item['sumber']] }}"></i> {{ $item['sumber'] }}</span>
                            <span class="berita-date"><i class="bi bi-calendar3"></i> {{ $item['tanggal']->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>

                        <div class="berita-detail-body" data-aos="fade-up">
                            @foreach ($item['isi'] as $paragraf)
                                <p>{{ $paragraf }}</p>
                            @endforeach
                        </div>

                        @if (in_array($item['sumber'], $socialSources) && !empty($item['sumber_url']))
                            <a href="{{ $item['sumber_url'] }}" target="_blank" rel="noopener" class="btn btn-hero-outline berita-original-btn" data-aos="fade-up">
                                <i class="bi {{ $sourceIcon[$item['sumber']] }} me-1"></i> Lihat Postingan Asli
                            </a>
                        @elseif ($item['sumber'] === 'Website' && !empty($item['sumber_url']))
                            <a href="{{ $item['sumber_url'] }}" target="_blank" rel="noopener" class="btn btn-hero-outline berita-original-btn" data-aos="fade-up">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Sumber Berita
                            </a>
                        @endif

                    </div>
                </div>
            @else
                <div class="riw-empty-global" data-aos="fade-up">
                    <span class="riw-empty-icon"><i class="bi bi-file-earmark-x"></i></span>
                    <h2>Informasi Tidak Ditemukan</h2>
                    <p>Berita atau informasi yang Anda cari mungkin sudah dipindahkan atau tidak tersedia.</p>
                    <a href="{{ route('berita') }}" class="btn btn-hero-primary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita & Informasi
                    </a>
                </div>
            @endif
        </div>
    </section>

@endsection
