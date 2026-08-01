@extends('admin.layouts.app')

@section('title', 'Tambah Artikel')
@section('page_title', 'Tambah Artikel')
@section('page_subtitle', 'Buat konten berita, pengumuman, atau edukasi baru')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach(['Berita', 'Pengumuman', 'Edukasi', 'Dokumentasi'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bentuk Gambar</label>
                    <select name="image_shape" class="form-select">
                        <option value="square" {{ old('image_shape') === 'square' ? 'selected' : '' }}>Persegi</option>
                        <option value="landscape" {{ old('image_shape') === 'landscape' ? 'selected' : '' }}>Landscape</option>
                        <option value="portrait" {{ old('image_shape') === 'portrait' ? 'selected' : '' }}>Portrait</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Terbit</label>
                    <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at') }}">
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Link Sumber Eksternal (opsional, mis. Instagram/Facebook)</label>
                <input type="url" name="link_url" class="form-control @error('link_url') is-invalid @enderror" value="{{ old('link_url') }}" placeholder="https://instagram.com/...">
                @error('link_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Gambar (opsional)</label>
                <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1.5rem;">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary" style="border-radius:10px; padding:0.5rem 1.5rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
