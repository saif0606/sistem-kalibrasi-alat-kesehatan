@extends('admin.layouts.app')

@section('title', 'Pengguna')
@section('page_title', 'Manajemen Pengguna')
@section('page_subtitle', 'Monitoring dan kelola akun pengguna aplikasi')

@section('content')
<ul class="nav nav-pills mb-4" id="usersTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold px-4 rounded-pill me-2" id="pelanggan-tab" data-bs-toggle="tab" data-bs-target="#pelanggan-tab-pane" type="button" role="tab">
            <i class="bi bi-people me-1"></i> Pelanggan & User
        </button>
    </li>
    @if(auth()->user()?->role === 'admin' || auth()->user()?->role === 'super_admin')
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 rounded-pill" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-tab-pane" type="button" role="tab">
            <i class="bi bi-shield-lock me-1"></i> Administrator
        </button>
    </li>
    @endif
</ul>

<div class="tab-content" id="usersTabContent">
    <div class="tab-pane fade show active" id="pelanggan-tab-pane" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
        <!-- Filter Bar -->
        <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-5">
                    <label class="form-label">Cari Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:rgba(30,148,71,0.08); border-color:var(--card-border); border-right:none; border-radius:10px 0 0 10px;">
                            <i class="bi bi-search" style="color:var(--green-600);"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                               style="border-left:none; border-radius:0 10px 10px 0;"
                               placeholder="Cari nama atau email..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Filter Role</label>
                    @php
                        $roleOptions = [
                            ''      => ['label' => 'Semua Role',       'icon' => 'bi-people-fill'],
                            'admin' => ['label' => 'Admin',            'icon' => 'bi-shield-lock-fill'],
                            'user'  => ['label' => 'User / Pelanggan', 'icon' => 'bi-person-fill'],
                        ];
                        $selectedRole = request('role', '');
                    @endphp
                    <div class="modern-select dropdown">
                        <input type="hidden" name="role" id="role-filter-input" value="{{ $selectedRole }}">
                        <button type="button" class="modern-select-trigger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi {{ $roleOptions[$selectedRole]['icon'] }} modern-select-trigger-icon"></i>
                            <span class="modern-select-trigger-label">{{ $roleOptions[$selectedRole]['label'] }}</span>
                        </button>
                        <ul class="dropdown-menu modern-select-menu">
                            @foreach($roleOptions as $value => $opt)
                            <li>
                                <button type="button"
                                        class="modern-select-item {{ $selectedRole === $value ? 'active' : '' }}"
                                        onclick="setModernSelect(this, 'role-filter-input', '{{ $value }}', '{{ $opt['label'] }}', '{{ $opt['icon'] }}')">
                                    <i class="bi {{ $opt['icon'] }}"></i>
                                    <span>{{ $opt['label'] }}</span>
                                    <i class="bi bi-check-lg modern-select-check"></i>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Stats Row -->
        <div class="d-flex gap-4 px-4 py-3 border-bottom" style="border-color: var(--card-border) !important;">
            <div class="text-center">
                <div style="font-size:1.4rem; font-weight:800; color:var(--blue-600);">{{ $users->total() }}</div>
                <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em;">Total Ditemukan</div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Total Pengajuan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td style="color:var(--text-muted); font-size:0.78rem;">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $user->role=='admin' ? '1E9447' : '0f6ea8' }}&color=fff&size=80"
                                     class="rounded-circle" width="38" height="38" alt="{{ $user->name }}">
                                <div>
                                    <div style="font-weight:600;">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--text-secondary);">{{ $user->email }}</td>
                        <td>
                            <span class="status-badge {{ $user->role == 'admin' ? 'badge-sertifikat' : 'badge-penjadwalan' }}">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </td>
                        <td style="color:var(--text-secondary); font-size:0.82rem;">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <span style="font-weight:700; color:var(--text-primary);">
                                {{ $user->calibrationRequests()->count() ?? 0 }}
                            </span>
                            <span style="font-size:0.75rem; color:var(--text-muted);"> pesanan</span>
                        </td>
                        <td class="text-end d-flex gap-2 justify-content-end align-items-center">
                        <a href="{{ route('admin.chat.index', ['user' => $user->id]) }}" class="btn btn-sm modern-chat-btn">
                                <i class="bi bi-chat-dots-fill me-1"></i> Chat
                            </a>
                            @if($user->role !== 'super_admin' && $user->id !== auth()->id())
                            <button type="button" class="btn btn-sm btn-delete-user"
                                onclick="showDeleteUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                title="Hapus pengguna">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-people" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                            Tidak ada pengguna ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="p-4 d-flex justify-content-between align-items-center border-top" style="border-color:var(--card-border) !important;">
            <div style="font-size:0.82rem; color:var(--text-muted);">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
        @endif
            </div>
        </div>
    </div>

    @if(auth()->user()?->role === 'admin' || auth()->user()?->role === 'super_admin')
    <div class="tab-pane fade" id="admin-tab-pane" role="tabpanel">
        <!-- SUPER ADMIN: Manajemen Admin -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold" style="color:var(--text-primary);"><i class="bi bi-shield-lock me-2" style="color:var(--green-600);"></i>Manajemen Admin</h5>
                    <small style="color:var(--text-secondary);">Tambahkan atau hapus akun administrator.</small>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Admin
                </button>
            </div>
            <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td style="color:var(--text-muted); font-size:0.78rem;">{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $admin->name }}</td>
                        <td style="color:var(--text-secondary);">{{ $admin->email }}</td>
                        <td>
                            <span class="status-badge badge-sertifikat">{{ strtoupper($admin->role) }}</span>
                        </td>
                        <td class="text-end">
                            @if($admin->id !== auth()->id() && $admin->role !== 'super_admin')
                            <form id="del-admin-{{ $admin->id }}" action="{{ route('admin.users.destroyAdmin', $admin->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="confirmDelete('del-admin-{{ $admin->id }}', 'Akun admin ini akan dihapus permanen.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                                <span style="font-size:0.75rem; color:var(--text-muted);">Tidak dapat dihapus</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--green-600), var(--blue-600)); border-radius: 14px; color: white; font-size: 1.4rem;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size: 1.25rem;">Tambah Akun Admin</h5>
                        <small>Buat akses administrator baru</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.storeAdmin') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.9rem;">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control admin-input" required style="border-radius: 12px; padding: 12px 18px; font-weight: 500; transition: border-color 0.2s;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.9rem;">Email</label>
                        <input type="email" name="email" class="form-control admin-input" required style="border-radius: 12px; padding: 12px 18px; font-weight: 500; transition: border-color 0.2s;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.9rem;">Password</label>
                        <input type="password" name="password" class="form-control admin-input" required minlength="6" style="border-radius: 12px; padding: 12px 18px; font-weight: 500; transition: border-color 0.2s;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 0.9rem;">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control admin-input" required minlength="6" style="border-radius: 12px; padding: 12px 18px; font-weight: 500; transition: border-color 0.2s;">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn fw-bold px-4 btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn fw-bold px-4" style="background: linear-gradient(135deg, var(--green-600), var(--blue-600)); border: none; color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(30, 148, 71, 0.3);"><i class="bi bi-save-fill me-2"></i>Simpan Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

</div> <!-- End tab-content -->

{{-- Modal: Hapus User --}}
<div class="modal fade" id="modalHapusUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" id="modalHapusUserContent" style="border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.25);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 14px; color: white; font-size: 1.4rem;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size: 1.2rem;">Hapus Pengguna</h5>
                        <small class="del-user-subtitle">Tindakan ini tidak dapat dibatalkan</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-2">
                <div class="p-3 mb-3" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); border-radius: 12px;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444;"></i>
                        <strong style="font-size:0.9rem;">Perhatian</strong>
                    </div>
                    <p class="mb-0" style="font-size:0.82rem; color:var(--text-secondary); line-height:1.5;">
                        Akun <strong id="delUserName"></strong> (<span id="delUserEmail"></span>) akan <strong>dihapus permanen</strong>.
                        Data akun dan seluruh riwayatnya tidak bisa dipulihkan.
                        Namun, catatan ini akan tersimpan di spreadsheet Google Sheets pada tab <em>Blacklist</em>.
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:0.88rem;">Alasan Penghapusan <span style="color:#ef4444;">*</span></label>
                    <textarea id="delUserReason" class="form-control del-user-input" rows="3"
                        placeholder="Tuliskan alasan mengapa akun ini dihapus..." required
                        style="border-radius:12px; padding:12px 16px; font-size:0.88rem; resize:none;"></textarea>
                    <div id="delUserReasonError" style="font-size:0.78rem; color:#ef4444; margin-top:4px; display:none;">Alasan harus diisi.</div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex gap-2">
                <button type="button" class="btn fw-bold px-4 btn-cancel-del" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn fw-bold px-4" id="btnConfirmDelete"
                    style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none; color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(239,68,68,0.3);">
                    <i class="bi bi-trash3-fill me-2"></i>Hapus Permanen
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form for delete --}}
<form id="formHapusUser" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="reason" id="delReasonInput">
</form>

@push('styles')
<style>
.nav-pills .nav-link {
    color: var(--text-primary);
    background: rgba(100, 116, 139, 0.1);
    border: 1.5px solid rgba(100, 116, 139, 0.3);
    transition: all 0.2s;
    font-weight: 600;
}
.nav-pills .nav-link:hover {
    background: rgba(30, 148, 71, 0.08);
    border-color: rgba(30, 148, 71, 0.3);
    color: var(--green-600);
}
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
    color: #fff;
    background: var(--green-600);
    border-color: var(--green-600);
    box-shadow: 0 4px 12px rgba(30,148,71,0.25);
}

/* Modern Chat Button */
.modern-chat-btn {
    background: linear-gradient(135deg, #1253A5, #1E9447);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 6px 16px;
    font-weight: 600;
    font-size: 0.82rem;
    transition: all 0.2s;
    box-shadow: 0 3px 8px rgba(18,83,165,0.25);
}
.modern-chat-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(18,83,165,0.35);
    color: white;
}

/* Modal Tambah Admin - Dark Mode Support */
#modalTambahAdmin .modal-content {
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.08);
}
#modalTambahAdmin .admin-input {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    color: #1e293b;
}
#modalTambahAdmin label {
    color: #1e293b;
}
#modalTambahAdmin .btn-cancel {
    background: transparent;
    border: 1.5px solid #cbd5e1;
    color: #64748b;
    border-radius: 12px;
}

[data-theme="dark"] #modalTambahAdmin .modal-content {
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.08);
}
[data-theme="dark"] #modalTambahAdmin .admin-input {
    background: #0f172a;
    border: 1.5px solid #334155;
    color: #e2e8f0;
}
[data-theme="dark"] #modalTambahAdmin label {
    color: #e2e8f0;
}
[data-theme="dark"] #modalTambahAdmin small {
    color: #94a3b8;
}
[data-theme="dark"] #modalTambahAdmin .btn-cancel {
    border-color: #334155;
    color: #94a3b8;
}

/* Modal Hapus User Styles */
#modalHapusUserContent {
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.08);
}
.del-user-subtitle { color: var(--text-muted); }
.del-user-input {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    color: #1e293b;
    transition: all 0.2s;
}
.del-user-input:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    outline: none;
}
.btn-cancel-del {
    background: transparent;
    border: 1.5px solid #cbd5e1;
    color: #64748b;
    border-radius: 12px;
}
.btn-cancel-del:hover {
    background: #f1f5f9;
    color: #334155;
}
.btn-delete-user {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
    padding: 6px 12px;
    transition: all 0.2s;
}
.btn-delete-user:hover {
    background: #ef4444;
    color: white;
}

/* Dark Mode - Modal Hapus */
[data-theme="dark"] #modalHapusUserContent {
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.08);
}
[data-theme="dark"] #modalHapusUserContent .del-user-subtitle {
    color: #94a3b8;
}
[data-theme="dark"] #modalHapusUserContent label {
    color: #e2e8f0;
}
[data-theme="dark"] .del-user-input {
    background: #0f172a;
    border: 1.5px solid #334155;
    color: #e2e8f0;
}
[data-theme="dark"] .del-user-input:focus {
    border-color: #ef4444;
}
[data-theme="dark"] .btn-cancel-del {
    border-color: #334155;
    color: #94a3b8;
}
[data-theme="dark"] .btn-cancel-del:hover {
    background: #334155;
    color: #f1f5f9;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Pindahkan modal ke body agar tidak terhalang backdrop (masalah z-index parent)
    const modalTambahAdmin = document.getElementById('modalTambahAdmin');
    if(modalTambahAdmin) {
        document.body.appendChild(modalTambahAdmin);
    }
    
    const modalHapusUser = document.getElementById('modalHapusUser');
    if(modalHapusUser) {
        document.body.appendChild(modalHapusUser);
    }
    
    // Handle Delete User Confirmation
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    if(btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', function() {
            const reason = document.getElementById('delUserReason').value.trim();
            const error = document.getElementById('delUserReasonError');
            
            if (!reason) {
                error.style.display = 'block';
                document.getElementById('delUserReason').focus();
                return;
            }
            
            error.style.display = 'none';
            document.getElementById('delReasonInput').value = reason;
            
            // Disable button to prevent double submit
            btnConfirmDelete.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...';
            btnConfirmDelete.disabled = true;
            
            document.getElementById('formHapusUser').submit();
        });
    }
});

function showDeleteUserModal(id, name, email) {
    document.getElementById('delUserName').textContent = name;
    document.getElementById('delUserEmail').textContent = email;
    document.getElementById('delUserReason').value = '';
    document.getElementById('delUserReasonError').style.display = 'none';
    
    // Set form action
    document.getElementById('formHapusUser').action = `/admin/users/${id}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('modalHapusUser'));
    modal.show();
}
</script>
@endpush

@endsection
