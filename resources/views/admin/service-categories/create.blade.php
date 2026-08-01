@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Layanan')
@section('page_title', 'Tambah Kategori Layanan')
@section('page_subtitle', 'Buat kategori baru untuk pengelompokan layanan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.service-categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Monitoring, Diagnostik, Terapi" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1.5rem;">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline-secondary" style="border-radius:10px; padding:0.5rem 1.5rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
