@extends('admin.layouts.app')

@section('title', 'Artikel & Berita')
@section('page_title', 'Manajemen Artikel & Berita')
@section('page_subtitle', 'Kelola konten berita, pengumuman, dan edukasi')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.articles.create') }}" class="btn btn-sm" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1rem;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Artikel
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Artikel</th>
                        <th>Kategori</th>
                        <th>Tanggal Terbit</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $index => $article)
                    <tr>
                        <td style="color:var(--text-muted); font-size:0.78rem;">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($article->image)
                                    <img src="{{ asset('storage/'.$article->image) }}" class="rounded" width="44" height="44" style="object-fit:cover;" alt="{{ $article->title }}">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width:44px; height:44px; background:rgba(30,148,71,0.1);">
                                        <i class="bi bi-newspaper" style="color:var(--green-600);"></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;">{{ $article->title }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">/{{ $article->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge badge-penjadwalan">{{ $article->category }}</span></td>
                        <td style="color:var(--text-secondary); font-size:0.82rem;">
                            {{ $article->published_at ? $article->published_at->format('d M Y') : 'Belum dijadwalkan' }}
                        </td>
                        <td class="text-end d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel {{ addslashes($article->title) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-newspaper" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                            Belum ada artikel
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
