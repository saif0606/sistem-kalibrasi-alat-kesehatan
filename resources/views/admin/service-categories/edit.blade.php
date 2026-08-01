@extends('admin.layouts.app')

@section('title', 'Edit Kategori Layanan')
@section('page_title', 'Edit Kategori Layanan')
@section('page_subtitle', 'Perbarui nama kategori layanan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.service-categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1.5rem;">
                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline-secondary" style="border-radius:10px; padding:0.5rem 1.5rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
