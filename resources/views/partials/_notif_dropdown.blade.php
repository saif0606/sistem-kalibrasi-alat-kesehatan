{{--
    Notification dropdown content — shared between mobile & desktop bell instances.
    Variables: $notifCert, $notifChat, $notifTotal
--}}
<ul class="dropdown-menu dropdown-menu-end border-0 mt-2 notif-dropdown-panel p-0"
    style="min-width:300px; border-radius:16px; overflow:hidden; box-shadow:0 12px 40px rgba(0,0,0,0.14);">

    {{-- Header --}}
    <li style="background:linear-gradient(135deg,#0c4a6e,#17a45c); padding:14px 18px;">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:8px; color:#fff;">
                <i class="bi bi-bell-fill" style="font-size:0.9rem;"></i>
                <span style="font-weight:700; font-size:0.85rem;">Notifikasi</span>
            </div>
            @if($notifTotal > 0)
                <span style="background:rgba(255,255,255,0.25); color:#fff; font-size:0.7rem; font-weight:700; padding:2px 9px; border-radius:999px;">
                    {{ $notifTotal }} baru
                </span>
            @endif
        </div>
    </li>

    {{-- Sertifikat Siap --}}
    @if($notifCert > 0)
    <li>
        <a href="{{ route('user.calibrations.index') }}" class="dropdown-item py-0 px-0">
            <div style="display:flex; align-items:center; gap:12px; padding:12px 18px; transition:background 0.15s;">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(23,164,92,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-patch-check-fill" style="color:#17a45c; font-size:1rem;"></i>
                </div>
                <div>
                    <div style="font-size:0.82rem; font-weight:700; color:var(--text-primary, #0c2438);">Sertifikat Siap Diambil</div>
                    <div style="font-size:0.72rem; color:#64748b;">{{ $notifCert }} sertifikat kalibrasi telah terbit</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="font-size:0.72rem; color:#94a3b8;"></i>
            </div>
        </a>
    </li>
    @endif

    {{-- Pesan Admin --}}
    @if($notifChat > 0)
    <li>
        <a href="{{ route('user.chat.index') }}" class="dropdown-item py-0 px-0">
            <div style="display:flex; align-items:center; gap:12px; padding:12px 18px; transition:background 0.15s;">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(43,111,240,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-chat-dots-fill" style="color:#2b6ff0; font-size:1rem;"></i>
                </div>
                <div>
                    <div style="font-size:0.82rem; font-weight:700; color:var(--text-primary, #0c2438);">Balasan Admin</div>
                    <div style="font-size:0.72rem; color:#64748b;">{{ $notifChat }} pesan belum dibaca</div>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="font-size:0.72rem; color:#94a3b8;"></i>
            </div>
        </a>
    </li>
    @endif

    {{-- Empty state --}}
    @if($notifTotal === 0)
    <li>
        <div style="padding:24px 18px; text-align:center;">
            <i class="bi bi-bell-slash" style="font-size:1.6rem; color:#cbd5e1; display:block; margin-bottom:8px;"></i>
            <div style="font-size:0.78rem; color:#94a3b8; font-weight:600;">Tidak ada notifikasi baru</div>
            <div style="font-size:0.7rem; color:#b5c0cc; margin-top:2px;">Semua sudah up to date!</div>
        </div>
    </li>
    @endif

    {{-- Footer --}}
    <li style="border-top:1px solid rgba(15,60,50,0.07); padding:10px 14px; background:rgba(248,250,252,0.8);">
        <a href="{{ route('user.calibrations.index') }}"
           style="display:block; text-align:center; font-size:0.76rem; font-weight:700; color:#17a45c; text-decoration:none;">
            Lihat Semua Pesanan <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </li>
</ul>
