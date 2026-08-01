@extends('admin.layouts.app')

@section('title', 'Tambah Layanan')
@section('page_title', 'Tambah Layanan')
@section('page_subtitle', 'Buat layanan kalibrasi baru')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Layanan</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar (opsional)</label>
                <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" name="is_kan" value="1" class="form-check-input" id="isKan" {{ old('is_kan') ? 'checked' : '' }}>
                <label class="form-check-label" for="isKan">Bersertifikat KAN</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1.5rem;">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary" style="border-radius:10px; padding:0.5rem 1.5rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection