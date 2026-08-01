@extends('admin.layouts.app')

@section('title', 'Layanan')
@section('page_title', 'Manajemen Layanan')
@section('page_subtitle', 'Kelola daftar layanan kalibrasi beserta harga')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.services.create') }}" class="btn btn-sm" style="background:var(--green-600); color:#fff; border-radius:10px; padding:0.5rem 1rem;">
        <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Bersertifikat KAN</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $index => $service)
                    <tr>
                        <td style="color:var(--text-muted); font-size:0.78rem;">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($service->image)
                                    <img src="{{ asset('storage/'.$service->image) }}" class="rounded" width="40" height="40" style="object-fit:cover;" alt="{{ $service->name }}">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width:40px; height:40px; background:rgba(30,148,71,0.1);">
                                        <i class="bi bi-clipboard2-pulse" style="color:var(--green-600);"></i>
                                    </div>
                                @endif
                                <div style="font-weight:600;">{{ $service->name }}</div>
                            </div>
                        </td>
                        <td style="font-weight:600;">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td>
                            @if($service->is_kan)
                                <span class="status-badge badge-sertifikat">KAN</span>
                            @else
                                <span style="color:var(--text-muted); font-size:0.78rem;">Tidak</span>
                            @endif
                        </td>
                        <td class="text-end d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Hapus layanan {{ addslashes($service->name) }}?');">
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
                            <i class="bi bi-clipboard2-pulse" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                            Belum ada layanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection