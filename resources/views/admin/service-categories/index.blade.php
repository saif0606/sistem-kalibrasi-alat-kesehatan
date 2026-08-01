@extends('admin.layouts.app')

@section('title', 'Kategori Layanan')
@section('page_title', 'Kategori Layanan')
@section('page_subtitle', 'Kelola kategori untuk pengelompokan layanan kalibrasi')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.service-categories.create') }}" class="btn btn-sm" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1rem;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Layanan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td style="color:var(--text-muted); font-size:0.78rem;">{{ $index + 1 }}</td>
                        <td style="font-weight:600;">{{ $category->name }}</td>
                        <td>
                            <span style="font-weight:700; color:var(--text-primary);">{{ $category->services_count }}</span>
                            <span style="font-size:0.75rem; color:var(--text-muted);"> layanan</span>
                        </td>
                        <td class="text-end d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.service-categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('admin.service-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?');">
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
                        <td colspan="4" class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-tags" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                            Belum ada kategori layanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
