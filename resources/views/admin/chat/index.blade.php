@extends('admin.layouts.app')

@section('title', 'Chat')
@section('page_title', 'Pesan')
@section('page_subtitle', 'Balas pesan pelanggan dan pantau percakapan')

@push('styles')
<style>
/* === VARIABLES === */
:root {
    --chat-bg: #f0f2f5;
    --card-bg: rgba(255, 255, 255, 0.65);
    --card-border: rgba(255, 255, 255, 0.8);
    --input-bg: rgba(255, 255, 255, 0.8);
    --glass-blur: blur(20px);
    --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
    --batik-opacity-panel: 0.25;
    --batik-opacity-chat: 0.07;
    --batik-filter: none;
    
    /* Admin = vibrant green (kanan), User = Deep Space Sparkle (kiri) */
    --bubble-sent-bg: #089145;
    --bubble-sent-color: #ffffff;
    --bubble-recv-bg: #406768;
    --bubble-recv-color: #ffffff;
    --bubble-border: rgba(0,0,0,0.08);
    --text-primary: #1e293b;
}

/* === DARK MODE WA STYLE === */
[data-theme="dark"] {
    --chat-bg: #111b21; /* WA Dark BG */
    --card-bg: rgba(17, 27, 33, 0.85);
    --card-border: rgba(255, 255, 255, 0.05);
    --input-bg: #2a3942;
    --glass-blur: blur(15px);
    --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    
    /* Batik WA Dark Pattern - Abu-abu aja */
    --batik-opacity-panel: 0.25;
    --batik-opacity-chat: 0.07;
    --batik-filter: invert(1) grayscale(1) opacity(0.5);
    
    /* Dark Mode Palette */
    --bubble-sent-bg: #089145;
    --bubble-sent-color: #ffffff;
    --bubble-recv-bg: #406768;
    --bubble-recv-color: #ffffff;
    --bubble-border: transparent;
    --text-primary: #e7edf5;
}

/* Override padding bawaan layout agar chat bisa full height */
body { overflow: hidden; }
.main-content { height: 100dvh; overflow: hidden; display: flex; flex-direction: column; }
.content-area { padding: 0 !important; display: flex; flex-direction: column; flex: 1; min-height: 0; }
.page-banner { display: none; } /* Sembunyikan banner bawaan untuk halaman chat */

/* === CHAT CONTAINER === */
.chat-container {
    flex: 1;
    display: flex;
    overflow: hidden;
    position: relative;
    background: var(--chat-bg);
    min-height: 0;
}

/* === DOC SIDE PANEL === */
.doc-side-panel {
    width: 0;
    display: flex;
    flex-direction: column;
    background: var(--card-bg);
    border-left: 1px solid var(--card-border);
    overflow: hidden;
    transition: width 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;
    opacity: 0;
    pointer-events: none;
    flex-shrink: 0;
}
.chat-container.preview-open .doc-side-panel {
    width: 400px;
    opacity: 1;
    pointer-events: all;
}
[data-theme="dark"] .doc-side-panel {
    background: var(--card-bg);
}
.doc-side-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--card-border);
    background: linear-gradient(135deg, rgba(9,74,115,0.08), rgba(30,148,71,0.06));
    flex-shrink: 0;
}
.doc-side-panel-header h6 {
    margin: 0;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}
.doc-side-panel-header h6 i { color: #1E9447; font-size: 1rem; }
.doc-side-panel-close {
    width: 28px; height: 28px; border-radius: 8px; border: none;
    background: rgba(0,0,0,0.06); color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 0.9rem; transition: all 0.2s;
}
.doc-side-panel-close:hover { background: rgba(239,68,68,0.12); color: #ef4444; }
.doc-side-panel-body {
    flex: 1; overflow-y: auto; overflow-x: hidden;
    padding: 16px; display: flex; flex-direction: column; gap: 12px;
}
.doc-side-panel-download {
    display: flex; align-items: center; justify-content: center;
    padding: 12px 16px; border-top: 1px solid var(--card-border); flex-shrink: 0;
}
.doc-side-panel-download a {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 0.82rem; font-weight: 700; padding: 8px 20px;
    border-radius: 10px; border: 1.5px solid #1E9447; color: #1E9447;
    text-decoration: none; background: transparent; transition: all 0.2s;
}
.doc-side-panel-download a:hover { background: #1E9447; color: white; }

/* === CHAT LIST PANEL === */
.chat-list-panel {
    width: 280px;
    border-right: 1px solid var(--card-border);
    background: var(--card-bg);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    display: flex; flex-direction: column;
    position: relative;
    transition: background 0.4s, border-color 0.4s;
    z-index: 10;
}

.chat-list-panel::before {
    content: ''; position: absolute; inset: 0;
    background-image: url('{{ asset("images/batik.png") }}');
    background-size: 472px 472px;
    opacity: var(--batik-opacity-panel);
    filter: var(--batik-filter);
    pointer-events: none; z-index: -1;
}

.chat-search {
    padding: 16px;
    border-bottom: 1px solid var(--card-border);
    background: transparent;
}
.chat-modern-select {
    width: 100%; padding: 12px 16px;
    border: 1px solid var(--card-border);
    border-radius: 12px; font-size: 0.9rem;
    background: var(--input-bg); color: var(--text-primary);
    outline: none; transition: all 0.3s; cursor: pointer;
    display: flex; justify-content: space-between; align-items: center;
}
.chat-modern-select:hover, .chat-modern-select[aria-expanded="true"] { border-color: var(--green-600); }
.chat-modern-dropdown {
    width: 100%; max-height: 350px; overflow-y: auto;
    background: var(--card-bg); backdrop-filter: var(--glass-blur);
    border: 1px solid var(--card-border); border-radius: 12px;
    padding: 8px; box-shadow: var(--glass-shadow);
}
.chat-modern-dropdown .dropdown-item {
    border-radius: 8px; padding: 10px 14px;
    color: var(--text-primary); transition: all 0.2s;
    display: flex; align-items: center; gap: 10px;
}
.chat-modern-dropdown .dropdown-item:hover, .chat-modern-dropdown .dropdown-item:focus {
    background: rgba(30,148,71,0.1); color: var(--text-primary);
}
.chat-modern-dropdown .dropdown-item.active {
    background: var(--green-600); color: #fff;
}
[data-theme="dark"] .chat-modern-dropdown .dropdown-item.active { background: #005c4b; }
.chat-modern-dropdown::-webkit-scrollbar { width: 6px; }
.chat-modern-dropdown::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 3px; }

.chat-conversations { flex: 1; overflow-y: auto; }
.chat-conversations::-webkit-scrollbar { width: 6px; }
.chat-conversations::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 3px; }

.chat-item {
    display: flex; gap: 12px; padding: 14px 16px;
    cursor: pointer; text-decoration: none; border-bottom: 1px solid var(--card-border);
    transition: all 0.2s; color: inherit;
}
.chat-item:hover { background: rgba(150,150,150,0.1); }
.chat-item.active { background: rgba(30,148,71,0.1); border-left: 4px solid var(--green-600); }
[data-theme="dark"] .chat-item.active { background: #2a3942; border-left-color: #00a884; }

.chat-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
.chat-info { flex: 1; min-width: 0; }
.chat-header-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
.chat-name { font-weight: 600; font-size: 0.95rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-time { font-size: 0.75rem; color: var(--text-muted); }
.chat-message-snippet { font-size: 0.85rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-badge { background: var(--green-600); color: white; font-size: 0.7rem; padding: 2px 7px; border-radius: 10px; font-weight: bold; }

/* === CHAT AREA === */
.chat-area {
    flex: 1; display: flex; flex-direction: column;
    background-color: transparent;
    position: relative;
    min-width: 0;
    min-height: 0;
}

.chat-area::before {
    content: ''; position: absolute; inset: 0;
    background-image: url('{{ asset("images/batik.png") }}');
    background-size: 472px 472px;
    opacity: var(--batik-opacity-chat);
    filter: var(--batik-filter);
    pointer-events: none; z-index: 0;
}

.chat-area-header {
    padding: 12px 20px;
    background: var(--card-bg);
    border-bottom: 1px solid var(--card-border);
    display: flex; align-items: center; gap: 12px;
    z-index: 1; backdrop-filter: var(--glass-blur);
}
.chat-area-header img { width: 42px; height: 42px; border-radius: 50%; }
.chat-area-header h6 { margin: 0; font-size: 1rem; font-weight: 600; color: var(--text-primary); }
.chat-area-header small { color: var(--text-muted); font-size: 0.8rem; }

.chat-messages {
    flex: 1; padding: 20px; overflow-y: auto;
    display: flex; flex-direction: column; gap: 8px;
    position: relative; z-index: 1;
}
.chat-messages::-webkit-scrollbar { width: 6px; }
.chat-messages::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 3px; }

.msg-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    max-width: 72%;
}
.msg-row--left  { align-self: flex-start; flex-direction: row; }
.msg-row--right { align-self: flex-end;   flex-direction: row-reverse; }

.msg-avatar {
    width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
    flex-shrink: 0; margin-top: 2px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.msg-body { display: flex; flex-direction: column; gap: 4px; min-width: 0; }

.msg-header {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.74rem;
}
.msg-row--right .msg-header { flex-direction: row-reverse; }

.msg-sender {
    font-weight: 700;
    color: var(--text-primary);
}
.msg-time-top {
    color: var(--text-muted);
    font-size: 0.68rem;
}

.message {
    padding: 9px 14px; border-radius: 14px;
    font-size: 0.9rem; line-height: 1.55;
    box-shadow: var(--bubble-shadow, 0 1px 3px rgba(0,0,0,0.12));
    word-break: break-word;
    border: 1px solid var(--bubble-border);
    position: relative;
}
.message.received {
    background: var(--bubble-recv-bg);
    color: var(--bubble-recv-color);
    border-top-left-radius: 4px;
}
.message.sent {
    background: var(--bubble-sent-bg);
    color: var(--bubble-sent-color);
    border-top-right-radius: 4px;
}
.msg-chevron {
    position: absolute;
    top: 4px;
    right: 8px;
    cursor: pointer;
    font-size: 1rem;
    color: rgba(255,255,255,0.7);
    background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, transparent 70%);
    border-radius: 50%;
    width: 22px; height: 22px;
    display: flex; align-items: center; justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 2;
}
.message:hover .msg-chevron { opacity: 1; }
.message.received .msg-chevron { color: rgba(0,0,0,0.4); background: radial-gradient(circle, rgba(255,255,255,0.5) 0%, transparent 70%); }
[data-theme="dark"] .message.received .msg-chevron { color: rgba(255,255,255,0.5); background: radial-gradient(circle, rgba(0,0,0,0.3) 0%, transparent 70%); }

.message-time { display: none; } /* waktu sudah dipindah ke header */

/* ===== CONTEXT MENU ===== */
.bubble-context-menu {
    position: fixed; z-index: 9999;
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.25);
    min-width: 140px; overflow: hidden;
    animation: menuIn 0.15s ease-out forwards;
}
[data-theme="dark"] .bubble-context-menu {
    background: #1e293b;
    border-color: rgba(255,255,255,0.1);
    box-shadow: 0 8px 28px rgba(0,0,0,0.5);
}
@keyframes menuIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
.bubble-context-menu button {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 11px 16px;
    background: none; border: none;
    font-size: 0.88rem; font-weight: 600;
    color: #1e293b;
    cursor: pointer; text-align: left;
    transition: background 0.15s;
}
[data-theme="dark"] .bubble-context-menu button { color: #f8fafc; }
.bubble-context-menu button:hover { background: rgba(30,148,71,0.1); }
[data-theme="dark"] .bubble-context-menu button:hover { background: rgba(255,255,255,0.05); }
.bubble-context-menu button i { font-size: 1rem; color: #1E9447; width: 18px; }
.bubble-context-menu hr { margin: 2px 0; border-color: rgba(0,0,0,0.06); }

/* Reply preview bar */
.reply-preview-bar {
    display: none; margin: 0; padding: 8px 16px;
    background: var(--card-bg);
    border-top: 1px solid var(--card-border);
    border-left: 3px solid #1E9447;
    align-items: center; gap: 10px;
    position: relative; z-index: 1;
}
.reply-preview-bar.active { display: flex; }
.reply-preview-content { flex: 1; min-width: 0; }
.reply-preview-name { font-size: 0.72rem; font-weight: 700; color: #1E9447; margin-bottom: 2px; }
.reply-preview-text { font-size: 0.8rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.reply-preview-close { background: none; border: none; color: var(--text-muted); font-size: 1.1rem; cursor: pointer; flex-shrink: 0; padding: 0; }
.reply-preview-close:hover { color: #dc2626; }

/* Reply quote inside bubble */
.reply-quote {
    border-left: 3px solid rgba(255,255,255,0.7) !important;
    border-radius: 6px;
    background: rgba(0,0,0,0.2) !important;
    padding: 5px 10px;
    margin-bottom: 6px;
    font-size: 0.78rem;
}
.message .reply-quote-name,
.msg-bubble .reply-quote-name {
    font-weight: 700 !important;
    color: rgba(255,255,255,0.95) !important;
    font-size: 0.72rem;
    margin-bottom: 2px;
}
.message .reply-quote-text,
.msg-bubble .reply-quote-text {
    color: rgba(255,255,255,0.72) !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 240px;
}

.chat-input-area {
    padding: 12px 20px; 
    background: var(--card-bg);
    border-top: 1px solid var(--card-border);
    display: flex; gap: 12px; align-items: center;
    position: relative; z-index: 1; 
    backdrop-filter: var(--glass-blur);
}
.chat-input-area form { display: flex; width: 100%; gap: 10px; align-items: center; }
.chat-input {
    flex: 1; padding: 12px 20px; border: 1px solid var(--card-border);
    border-radius: 24px; font-size: 0.95rem; outline: none;
    background: var(--input-bg); color: var(--text-primary); transition: all 0.2s;
}
.chat-input:focus { border-color: var(--green-600); }
.btn-send {
    width: 46px; height: 46px; border-radius: 50%; border: none;
    background: #00a884; color: white; display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s; font-size: 1.2rem;
}
.btn-send:hover { background: #008f6f; transform: scale(1.05); }

/* === Order Reference Card (Marketplace-style) === */
.chat-ref-card {
    margin: 0;
    padding: 12px 20px;
    background: var(--card-bg);
    backdrop-filter: var(--glass-blur);
    border-top: 1px solid var(--card-border);
    position: relative; z-index: 2;
}
.chat-ref-card-inner {
    display: flex;
    align-items: center;
    gap: 14px;
    background: linear-gradient(135deg, rgba(30,148,71,0.07), rgba(15,110,168,0.07));
    border: 1.5px solid rgba(30,148,71,0.2);
    border-radius: 14px;
    padding: 12px 16px;
    position: relative;
    transition: all 0.2s;
}
.chat-ref-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--green-600), var(--blue-600));
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.3rem;
    box-shadow: 0 4px 12px rgba(30,148,71,0.3);
}
.chat-ref-body { flex: 1; min-width: 0; }
.chat-ref-label { font-size: 0.7rem; font-weight: 700; color: var(--green-600); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
.chat-ref-number { font-size: 0.82rem; font-weight: 700; color: var(--text-primary); font-family: monospace; }
.chat-ref-meta { font-size: 0.74rem; color: var(--text-muted); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-ref-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
.btn-ref-detail {
    font-size: 0.75rem; font-weight: 700; padding: 6px 14px;
    border-radius: 10px; border: 1.5px solid var(--green-600);
    color: var(--green-600); background: transparent;
    text-decoration: none; transition: all 0.2s; white-space: nowrap;
}
.btn-ref-detail:hover { background: var(--green-600); color: white; }
.btn-ref-close {
    width: 26px; height: 26px; border-radius: 50%; border: none;
    background: rgba(100,116,139,0.15); color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 0.85rem; transition: all 0.2s; flex-shrink: 0;
}
.btn-ref-close:hover { background: rgba(239,68,68,0.15); color: #dc2626; }
[data-theme="dark"] .chat-ref-card-inner {
    background: linear-gradient(135deg, rgba(30,148,71,0.12), rgba(15,110,168,0.12));
    border-color: rgba(30,148,71,0.3);
}

/* Empty state */
.chat-empty { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); z-index: 1; }
.chat-empty i { font-size: 4rem; opacity: 0.5; margin-bottom: 15px; }

@media (max-width: 860px) {
    .chat-container { position: relative; flex-direction: column; }
    .chat-list-panel { width: 100%; position: absolute; height: 100%; z-index: 20; transition: transform 0.3s; }
    .chat-list-panel.hide-mobile { transform: translateX(-100%); }
    .chat-area { width: 100%; flex: 1; }
    
    .chat-container.preview-open .chat-area { height: 50vh; flex: none; }
    .chat-container.preview-open .doc-side-panel { 
        width: 100% !important; 
        height: 50vh; 
        border-left: none; 
        border-top: 1px solid var(--card-border); 
    }
}

/* Twemoji Custom Styles */
img.emoji {
    height: 1.25em;
    width: 1.25em;
    margin: 0 0.05em 0 0.1em;
    vertical-align: -0.1em;
    display: inline-block;
}
</style>
@endpush

@section('content')
<div class="chat-container">
    
    <!-- CHAT LIST PANEL -->
    <div class="chat-list-panel {{ $selectedUser ? 'hide-mobile' : '' }}">
        <div class="chat-search d-flex gap-2 align-items-center">
            <div class="dropdown flex-grow-1">
                <button class="chat-modern-select dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    <span class="text-truncate" style="max-width: 90%;">
                        @if($selectedUser)
                            <i class="bi bi-person-fill text-success me-2"></i>{{ $selectedUser->name }} ({{ $selectedUser->email }})
                        @else
                            <i class="bi bi-search me-2 text-muted"></i>Cari pelanggan...
                        @endif
                    </span>
                </button>
                <ul class="dropdown-menu chat-modern-dropdown p-2 shadow-lg">
                    <li>
                        <input type="text" class="form-control form-control-sm mb-2" id="chatSearchInput" placeholder="Ketik nama pelanggan..." autofocus>
                    </li>
                    <li><hr class="dropdown-divider opacity-25"></li>
                    <div id="chatSearchList">
                        @foreach($allCustomers as $c)
                            <li class="chat-search-item">
                                <a class="dropdown-item {{ $selectedUser && $selectedUser->id == $c->id ? 'active' : '' }}" href="{{ route('admin.chat.index') }}?user={{ $c->id }}">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($c->name) }}&background=1E9447&color=fff" class="rounded-circle" style="width:32px; height:32px; object-fit:cover;">
                                    <div style="min-width:0;">
                                        <div class="fw-bold text-truncate chat-search-name" style="font-size:0.9rem;">{{ $c->name }}</div>
                                        <small class="text-truncate d-block chat-search-email" style="font-size:0.75rem; opacity:0.8;">{{ $c->email }}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </div>
                </ul>
            </div>
            <!-- Tombol Broadcast -->
            <button type="button" class="btn btn-primary p-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;" data-bs-toggle="modal" data-bs-target="#broadcastModal" title="Broadcast Pesan">
                <i class="bi bi-megaphone-fill"></i>
            </button>
        </div>
        
        <div class="chat-conversations">
            @forelse($conversations as $conv)
                @php $last = $conv->chatMessages->first(); @endphp
                <a href="{{ route('admin.chat.index', ['user' => $conv->id]) }}" class="chat-item {{ $selectedUser && $selectedUser->id == $conv->id ? 'active' : '' }}">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($conv->name) }}&background=1E9447&color=fff" class="chat-avatar">
                    <div class="chat-info">
                        <div class="chat-header-info">
                            <span class="chat-name">{{ $conv->name }}</span>
                            <span class="chat-time">{{ $last ? $last->created_at->format('H:i') : '' }}</span>
                        </div>
                        <div class="chat-header-info m-0">
                            <span class="chat-message-snippet">
                                @if($last && in_array($last->sender_role, ['admin', 'bot']))
                                    <i class="bi bi-check2-all text-primary"></i> 
                                @endif
                                {{ Str::limit($last->message ?? 'Mulai percakapan', 30) }}
                            </span>
                            @if($conv->unread_count > 0)
                                <span class="chat-badge">{{ $conv->unread_count }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center p-4 text-muted mt-5">Belum ada percakapan.</div>
            @endforelse
        </div>
    </div>

    <!-- CHAT AREA -->
    @if($selectedUser)
        <div class="chat-area">
            <div class="chat-area-header">
                <a href="{{ route('admin.chat.index') }}" class="btn btn-sm btn-light d-md-none me-2"><i class="bi bi-arrow-left"></i></a>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedUser->name) }}&background=1E9447&color=fff">
                <div>
                    <h6>{{ $selectedUser->name }}</h6>
                    <small>{{ $selectedUser->email }}</small>
                </div>
            </div>

            <div class="chat-messages" id="chatThreadBody">
                @forelse($messages as $msg)
                    @php
                        $isBot = $msg->sender_role === 'bot';
                        $isAdmin = $msg->sender_role === 'admin';
                        $isOutgoing = $isAdmin || $isBot;
                        $avatarName  = $isAdmin ? 'Admin' : ($isBot ? 'Bot' : $selectedUser->name);
                        $avatarBg    = $isAdmin ? '089145' : ($isBot ? '0b5ed7' : '406768');
                        $adminName   = optional($msg->admin)->name ?? 'UPTD';
                        $senderLabel = $isAdmin ? 'Admin (' . $adminName . ')' : ($isBot ? 'Asisten Bot' : $selectedUser->name);
                    @endphp
                    @php
                        $msgText = $msg->message;
                        $hasRef = false;
                        $refDoc = null;
                        $refNum = null;
                        
                        if (preg_match('/^\[Dokumen (.*?) \(Pesanan (.*?)\)\]\s*(.*)/', $msgText, $m)) {
                            $hasRef = true;
                            $refDoc = $m[1];
                            $refNum = $m[2];
                            $msgText = $m[3];
                        } elseif (preg_match('/^\[Pesanan (.*?)\]\s*(.*)/', $msgText, $m)) {
                            $hasRef = true;
                            $refNum = $m[1];
                            $msgText = $m[2];
                        }
                    @endphp

                    @if($hasRef)
                    <div class="chat-ref-card-msg" style="display:flex; justify-content:{{ $isOutgoing ? 'flex-end' : 'flex-start' }}; padding:4px 0;">
                        <div style="max-width: 360px; min-width: 200px; background: var(--card-bg); border: 1.5px solid rgba(30,148,71,0.25); border-radius: 16px 16px {{ $isOutgoing ? '4px 16px' : '16px 4px' }}; padding: 12px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.10); position: relative;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:38px; height:38px; border-radius:10px; flex-shrink:0; background: linear-gradient(135deg, #1E9447, #0F6EA8); display:flex; align-items:center; justify-content:center; color:white; font-size:1.15rem; box-shadow: 0 3px 8px rgba(30,148,71,0.3);">
                                    <i class="bi {{ $refDoc ? 'bi-file-earmark-text' : 'bi-clipboard2-pulse' }}"></i>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-size:0.65rem; font-weight:800; color:#1E9447; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px;">
                                        {{ $refDoc ? 'DOKUMEN ' . $refDoc : 'PESANAN KALIBRASI' }}
                                    </div>
                                    <div style="font-size:0.8rem; font-weight:700; font-family:monospace; color:var(--text-primary);">{{ $refNum }}</div>
                                    <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">Pertanyaan terkait hal ini</div>
                                </div>
                            </div>
                            <div style="margin-top:10px; border-top:1px solid var(--card-border); padding-top:8px; display:flex; justify-content:flex-end;">
                                @if($refDoc)
                                    <button type="button" onclick="previewDraftHarga('{{ $refNum }}')" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';">
                                        <i class="bi bi-eye"></i> Pratinjau
                                    </button>
                                @else
                                    <a href="{{ route('admin.calibrations.index', ['search' => $refNum]) }}" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @php
                        $isSticker = false;
                        $stickerUrl = '';
                        if (preg_match('/^\[STICKER:(.+?)\]$/', trim($msgText), $stM)) {
                            $isSticker = true;
                            $stickerUrl = asset('stiker/' . $stM[1]);
                            $msgText = ''; // Clear text so bubble isn't rendered twice
                        }
                    @endphp

                    <div class="msg-row {{ $isOutgoing ? 'msg-row--right' : 'msg-row--left' }}" data-msg-id="{{ $msg->id }}">
                        <img class="msg-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($avatarName) }}&background={{ $avatarBg }}&color=fff">
                        <div class="msg-body">
                            <div class="msg-header">
                                <span class="msg-sender">{{ $senderLabel }}</span>
                                <span class="msg-time-top">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                            
                            @php
                                $renderedReplyQuote = false;
                            @endphp

                            @if($msg->attachment)
                            <div class="message {{ $isOutgoing ? 'sent' : 'received' }} p-2" style="margin-bottom: {{ trim($msgText) !== '' ? '5px' : '0' }}; min-width: 250px;">
                                <div class="msg-chevron" onclick="showAdminContextMenu(event, {{ $msg->id }}, '{{ addslashes($msgText) }}', '{{ $senderLabel }}', '{{ addslashes($msg->attachment) }}')"><i class="bi bi-chevron-down"></i></div>
                                
                                @if($msg->parent)
                                    @php
                                        $parentSender = $msg->parent->sender_role === 'admin' ? 'Admin (Anda)' : $selectedUser->name;
                                        if ($msg->parent->attachment) {
                                            $parentAttachExtA = strtolower(pathinfo($msg->parent->attachment, PATHINFO_EXTENSION));
                                            $parentAttachNameA = basename($msg->parent->attachment);
                                            $parentIsImageA = in_array($parentAttachExtA, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                            $parentPreview = null;
                                        } else {
                                            $parentAttachExtA = null;
                                            $parentAttachNameA = null;
                                            $parentIsImageA = false;
                                            $parentPreview = Str::limit($msg->parent->message, 60);
                                        }
                                        $renderedReplyQuote = true;
                                    @endphp
                                    <div class="reply-quote">
                                        <div class="reply-quote-name">{{ $parentSender }}</div>
                                        @if($parentAttachExtA)
                                            <div class="reply-quote-text" style="display:flex; align-items:center; gap:5px;">
                                                <i class="bi {{ $parentIsImageA ? 'bi-image' : 'bi-file-earmark-text' }}" style="font-size:0.9rem;"></i>
                                                <span>{{ strlen($parentAttachNameA) > 30 ? substr($parentAttachNameA, 0, 30) . '...' : $parentAttachNameA }}</span>
                                            </div>
                                        @else
                                            <div class="reply-quote-text">{{ $parentPreview }}</div>
                                        @endif
                                    </div>
                                @endif

                                @php
                                    $ext = strtolower(pathinfo($msg->attachment, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                    $fileUrl = asset('storage/' . $msg->attachment);
                                    $fileName = basename($msg->attachment);
                                    $displayName = strlen($fileName) > 30 ? substr($fileName, 0, 30) . '...' : $fileName;
                                @endphp
                                @if($isImage)
                                    <img src="{{ $fileUrl }}" alt="Attachment" style="max-width: 250px; border-radius: 8px; display: block; cursor: pointer;" onclick="previewAttachment('{{ $fileUrl }}', '{{ $ext }}')">
                                @else
                                    <div style="display:flex; align-items:center; gap:10px; background:rgba(255,255,255,0.15); padding:10px; border-radius:12px; border:1px solid rgba(255,255,255,0.2);">
                                        <div style="width:40px; height:40px; border-radius:8px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.2rem; flex-shrink:0;">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div style="flex:1; min-width:0; line-height:1.2;">
                                            <div style="font-size:0.8rem; font-weight:700; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $fileName }}">
                                                {{ $displayName }}
                                            </div>
                                            <div style="font-size:0.7rem; color:rgba(255,255,255,0.7); text-transform:uppercase; margin-top:2px;">{{ $ext }} File</div>
                                        </div>
                                    </div>
                                    <div style="margin-top:8px; display:flex; gap:8px;">
                                        <button type="button" onclick="previewAttachment('{{ $fileUrl }}', '{{ $ext }}')" style="flex:1; font-size:0.75rem; font-weight:700; padding:6px; border-radius:8px; border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.9); background:rgba(255,255,255,0.12); display:inline-flex; align-items:center; justify-content:center; gap:5px; transition:all 0.2s;">
                                            <i class="bi bi-eye"></i> Pratinjau
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @endif

                            @if($isSticker)
                                <div style="margin-top:5px; margin-bottom:5px; text-align:{{ $isOutgoing ? 'right' : 'left' }};">
                                    <img src="{{ $stickerUrl }}" alt="Stiker" style="width: 120px; height: auto; display: inline-block;">
                                </div>
                            @endif

                            @if(trim($msgText) !== '')
                            <div class="message {{ $isOutgoing ? 'sent' : 'received' }}">
                                <div class="msg-chevron" onclick="showAdminContextMenu(event, {{ $msg->id }}, '{{ addslashes($msgText) }}', '{{ $senderLabel }}', '{{ addslashes($msg->attachment) }}')"><i class="bi bi-chevron-down"></i></div>
                                
                                @if($msg->parent && !$renderedReplyQuote)
                                    @php
                                        $parentSender = $msg->parent->sender_role === 'admin' ? 'Admin (Anda)' : $selectedUser->name;
                                        if ($msg->parent->attachment) {
                                            $parentAttachExtB = strtolower(pathinfo($msg->parent->attachment, PATHINFO_EXTENSION));
                                            $parentAttachNameB = basename($msg->parent->attachment);
                                            $parentIsImageB = in_array($parentAttachExtB, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                        } else {
                                            $parentAttachExtB = null;
                                            $parentAttachNameB = null;
                                            $parentIsImageB = false;
                                            $parentPreviewB = Str::limit($msg->parent->message, 60);
                                        }
                                    @endphp
                                    <div class="reply-quote">
                                        <div class="reply-quote-name">{{ $parentSender }}</div>
                                        @if($parentAttachExtB)
                                            <div class="reply-quote-text" style="display:flex; align-items:center; gap:5px;">
                                                <i class="bi {{ $parentIsImageB ? 'bi-image' : 'bi-file-earmark-text' }}" style="font-size:0.9rem;"></i>
                                                <span>{{ strlen($parentAttachNameB) > 30 ? substr($parentAttachNameB, 0, 30) . '...' : $parentAttachNameB }}</span>
                                            </div>
                                        @else
                                            <div class="reply-quote-text">{{ $parentPreviewB }}</div>
                                        @endif
                                    </div>
                                @endif

                                {{ $msgText }}
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted mt-5">Kirim pesan pertama untuk memulai.</div>
                @endforelse
            </div>

            @if(isset($refCalibration) && $refCalibration)
            <div class="chat-ref-card" id="refCard">
                <div class="chat-ref-card-inner">
                    <div class="chat-ref-icon">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <div class="chat-ref-body">
                        <div class="chat-ref-label">Pesanan Kalibrasi</div>
                        <div class="chat-ref-number">{{ $refCalibration->registration_number }}</div>
                        <div class="chat-ref-meta">
                            {{ $refCalibration->nama_instansi }}
                            &middot; {{ $refCalibration->status }}
                            @if($refCalibration->device_name) &middot; {{ Str::limit($refCalibration->device_name, 28) }} @endif
                        </div>
                    </div>
                    <div class="chat-ref-actions">
                        <a href="{{ route('admin.calibrations.show', $refCalibration) }}" class="btn-ref-detail">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                        <button type="button" class="btn-ref-close" onclick="document.getElementById('refCard').remove(); document.getElementById('adminChatInput').value=''" title="Tutup">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Reply Preview Bar -->
            <div id="adminReplyBar" class="reply-preview-bar">
                <div class="reply-preview-content">
                    <div class="reply-preview-name" id="adminReplyName">Balas</div>
                    <div class="reply-preview-text" id="adminReplyText"></div>
                </div>
                <button type="button" class="reply-preview-close" onclick="cancelAdminReply()" title="Batal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- File Preview Area -->
            <div id="adminFilePreview" style="display:none; padding:10px 18px; background:var(--chat-bg); border-top:1px solid var(--panel-border);">
                <div style="display:flex; align-items:center; gap:10px; background:var(--card-bg); padding:8px 12px; border-radius:12px; border:1px solid rgba(0,0,0,0.05); max-width:340px;">
                    <div id="adminFileThumb" style="flex-shrink:0; width:44px; height:44px; border-radius:8px; overflow:hidden; background:rgba(30,148,71,0.1); display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div id="adminFileName" style="font-size:0.8rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">filename.pdf</div>
                        <div id="adminFileSize" style="font-size:0.7rem; color:var(--text-muted);">2.5 MB</div>
                    </div>
                    <button type="button" class="btn-close" style="font-size:0.7rem;" onclick="removeAdminFile()"></button>
                </div>
            </div>

            <!-- Sticker Container -->
            <div id="adminStickerContainer" style="display:none; position:absolute; bottom:70px; left:20px; z-index:100; background:var(--card-bg); border:1px solid var(--panel-border); border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.15); width:320px; max-height:300px; overflow-y:auto; padding:12px;">
                <div style="font-size:0.85rem; font-weight:700; color:var(--text-muted); margin-bottom:10px; padding-left:4px;">Stiker Spesial</div>
                <div id="adminStickerGrid" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px;">
                    <!-- populated via JS -->
                </div>
            </div>

            <!-- Floating Scroll Buttons -->
            <div id="chatScrollButtons" style="display:none; position:absolute; bottom:160px; left:18px; z-index:100; flex-direction:column; gap:7px;">
                <button type="button" class="admin-scroll-btn" id="scrollUpBtn" onclick="scrollToTop()">
                    <i class="bi bi-chevron-up" style="font-size:0.85rem;"></i>
                </button>
                <button type="button" class="admin-scroll-btn position-relative" id="scrollDownBtn" onclick="scrollToBottom()">
                    <i class="bi bi-chevron-down" style="font-size:0.85rem;"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" id="newMsgBadge" style="display:none; font-size:0.6rem; padding:0.3em 0.5em; border:1.5px solid var(--card-bg); box-shadow:0 2px 4px rgba(0,0,0,0.15);">0</span>
                </button>
            </div>

            <div class="chat-input-area" style="display:flex; align-items:center; gap:10px; position:relative;">
                <button type="button" id="adminEmojiBtn" class="btn" style="background:transparent; border:none; color:var(--text-muted); font-size:1.3rem; display:flex; align-items:center; justify-content:center; padding:0;" aria-label="Sticker" onclick="toggleAdminEmoji()">
                    <i class="bi bi-stickies"></i>
                </button>
                <label for="adminAttachment" class="btn mb-0" style="background:transparent; border:none; color:var(--text-muted); font-size:1.3rem; display:flex; align-items:center; justify-content:center; padding:0; cursor:pointer;" aria-label="Attach File" title="File chat biasa akan dihapus otomatis setelah 24 jam">
                    <i class="bi bi-paperclip"></i>
                </label>
                <input type="file" id="adminAttachment" hidden accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" onchange="previewAdminFile()">
                <input type="text" id="adminChatInput" class="chat-input" placeholder="Ketik pesan..." autocomplete="off" autofocus style="flex:1;">
                <button type="button" id="adminSendBtn" class="btn-send" style="flex-shrink:0;"><i class="bi bi-send-fill"></i></button>
            </div>
        </div>
    @else
        <div class="chat-area">
            <div class="chat-empty">
                <i class="bi bi-chat-square-text"></i>
                <h5>UPTD Kalibrasi Chat</h5>
                <p>Pilih percakapan untuk mulai membaca dan membalas pesan.</p>
            </div>
        </div>
    @endif

    {{-- Doc Side Preview Panel (Admin) --}}
    <div class="doc-side-panel" id="docSidePanel">
        <div class="doc-side-panel-header">
            <h6 id="sidePanelTitle"><i class="bi bi-file-earmark-text"></i> Pratinjau Dokumen</h6>
            <button class="doc-side-panel-close" onclick="closeSidePanel()" title="Tutup"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="doc-side-panel-body" id="sidePanelBody">
            <!-- injected via JS -->
        </div>
        <div class="doc-side-panel-download">
            <a href="#" id="sidePanelDownloadBtn" download><i class="bi bi-download"></i> Unduh File</a>
        </div>
    </div>

</div>

<!-- Context Menu -->
<div id="bubbleContextMenu" class="bubble-context-menu" style="display:none;">
    <button onclick="doAdminReply()"><i class="bi bi-reply"></i> Balas</button>
    <hr>
    <button onclick="doAdminCopy()"><i class="bi bi-clipboard"></i> Salin</button>
    <hr>
    <button onclick="doAdminDelete()" style="color:#ef4444;"><i class="bi bi-trash3" style="color:#ef4444;"></i> Hapus</button>
</div>

<!-- MODAL BROADCAST -->
<div class="modal fade" id="broadcastModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="background: var(--card-bg); backdrop-filter: var(--glass-blur); -webkit-backdrop-filter: var(--glass-blur); border-radius: 20px; box-shadow: var(--glass-shadow); border: 1px solid var(--card-border);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--green-600), var(--blue-600)); border-radius: 14px; color: white; font-size: 1.4rem;">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="color: var(--text-primary); font-size: 1.25rem;">Broadcast Pesan</h5>
                        <small style="color: var(--text-muted); font-size: 0.85rem;">Kirim pengumuman massal</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--bs-btn-close-white-filter, none);"></button>
            </div>
            <form action="{{ route('admin.chat.broadcast') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: var(--text-primary); font-size: 0.9rem;">Target Penerima</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="target" id="targetAll" value="all" autocomplete="off" checked>
                            <label class="btn btn-outline-success rounded-pill px-3 py-2 flex-grow-1" for="targetAll" style="font-weight: 500; font-size: 0.9rem;">
                                <i class="bi bi-globe me-2"></i>Semua Pelanggan
                            </label>

                            <input type="radio" class="btn-check" name="target" id="targetSelected" value="selected" autocomplete="off">
                            <label class="btn btn-outline-primary rounded-pill px-3 py-2 flex-grow-1" for="targetSelected" style="font-weight: 500; font-size: 0.9rem;">
                                <i class="bi bi-person-lines-fill me-2"></i>Pilih Tertentu...
                            </label>
                        </div>
                    </div>

                    <div class="mb-4" id="broadcastUserSelect" style="display: none;">
                        <label class="form-label fw-bold" style="color: var(--text-primary); font-size: 0.9rem;">Daftar Pelanggan <small class="text-muted fw-normal ms-1">(Pilih yang dituju)</small></label>
                        <div class="border rounded-4 p-2 custom-scroll" style="height: 160px; overflow-y: auto; background: var(--input-bg); border-color: var(--card-border) !important;">
                            <div class="d-flex flex-column gap-1">
                                @foreach($allCustomers as $c)
                                    <label class="d-flex align-items-center gap-3 p-2 rounded-3 cursor-pointer user-select-none" style="transition: all 0.2s; cursor: pointer;">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input broadcast-user-checkbox" type="checkbox" name="user_ids[]" value="{{ $c->id }}" style="cursor: pointer;">
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-grow-1">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($c->name) }}&background=1E9447&color=fff" class="rounded-circle" style="width:28px; height:28px;">
                                            <div>
                                                <div class="fw-bold" style="font-size:0.85rem; color: var(--text-primary); lh-1">{{ $c->name }}</div>
                                                <div style="font-size:0.75rem; color: var(--text-muted);">{{ $c->email }}</div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <style>
                            .custom-scroll::-webkit-scrollbar { width: 6px; }
                            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 3px; }
                            .cursor-pointer:hover { background: rgba(150,150,150,0.1); }
                        </style>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold" style="color: var(--text-primary); font-size: 0.9rem;">Isi Pesan</label>
                        <textarea name="message" class="form-control chat-input" rows="4" placeholder="Ketik isi pesan pengumuman..." required style="border-radius: 14px; padding: 16px; font-weight: 500; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn fw-bold px-4" data-bs-dismiss="modal" style="background: transparent; border: 1.5px solid var(--card-border); color: var(--text-secondary); border-radius: 12px; transition: all 0.2s;">Batal</button>
                    <button type="submit" class="btn fw-bold px-4" style="background: linear-gradient(135deg, var(--green-600), var(--blue-600)); border: none; color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(30, 148, 71, 0.3); transition: all 0.2s;"><i class="bi bi-send-fill me-2"></i>Kirim Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Draft Harga -->
<div class="modal fade" id="draftHargaChatModal" tabindex="-1" aria-labelledby="draftHargaChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; background: var(--card-bg); border: 1px solid var(--panel-border); box-shadow: 0 20px 50px rgba(0,0,0,0.25);">
            <div class="modal-header border-bottom-0 pb-0 position-relative">
                <h5 class="modal-title fw-bold ms-2 mt-2" id="draftHargaChatModalLabel" style="color: var(--text-primary);">
                    <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i> Draft / Penawaran Harga
                </h5>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index:10; filter: var(--bs-btn-close-color, invert(0));"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-3" id="draftHargaChatModalBody">
                <!-- Injected via JS -->
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-3 px-4 justify-content-end gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="draftHargaChatDownloadBtn" download class="btn rounded-pill px-4 fw-bold shadow-sm" style="background-color: #17a45c; border-color: #17a45c; color: white;">
                    <i class="bi bi-download me-1"></i> Unduh File
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    function previewAdminFile() {
        const fileInput = document.getElementById('adminAttachment');
        const previewEl = document.getElementById('adminFilePreview');
        const nameEl    = document.getElementById('adminFileName');
        const sizeEl    = document.getElementById('adminFileSize');
        const thumbEl   = document.getElementById('adminFileThumb');
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            // Show image thumbnail if it's an image
            const isImage = file.type.startsWith('image/');
            if (isImage && thumbEl) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    thumbEl.innerHTML = `<img src="${e.target.result}" style="width:44px; height:44px; object-fit:cover; border-radius:8px;">`;
                };
                reader.readAsDataURL(file);
            } else if (thumbEl) {
                thumbEl.innerHTML = '<i class="bi bi-file-earmark-text fs-4 text-primary"></i>';
            }

            previewEl.style.display = 'block';
        } else {
            previewEl.style.display = 'none';
        }
    }

    function removeAdminFile() {
        const fileInput = document.getElementById('adminAttachment');
        const previewEl = document.getElementById('adminFilePreview');
        fileInput.value = '';
        previewEl.style.display = 'none';
    }

    function toggleAdminEmoji() {
        const container = document.getElementById('adminStickerContainer');
        container.style.display = container.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Populate stickers
        const stickerGrid = document.getElementById('adminStickerGrid');
        if (stickerGrid) {
            const stickers = [1,2,3,5,6,8,9,10,11,12,13,14,16,17];
            stickers.forEach(num => {
                const url = `{{ asset('stiker') }}/${num}.png`;
                const div = document.createElement('div');
                div.className = 'sticker-item';
                div.style.cssText = 'cursor:pointer; border-radius:8px; padding:4px; transition:transform 0.15s, background 0.15s; display:flex; align-items:center; justify-content:center;';
                div.onmouseover = () => { div.style.transform = 'scale(1.1)'; div.style.background = 'var(--panel-border)'; };
                div.onmouseout = () => { div.style.transform = 'scale(1)'; div.style.background = 'transparent'; };
                div.onclick = () => sendAdminSticker(url, `${num}.png`);
                div.innerHTML = `<img src="${url}" alt="Stiker ${num}" style="max-width:100%; height:auto;">`;
                stickerGrid.appendChild(div);
            });
        }



        const attachInput = document.getElementById('adminAttachment');
        if (attachInput) {
            attachInput.addEventListener('click', (e) => {
                const previewEl = document.getElementById('adminFilePreview');
                if (previewEl.style.display !== 'none') {
                    e.preventDefault();
                    showAdminFileWarning();
                }
            });
        }
    });

    async function sendAdminSticker(url, filename) {
        document.getElementById('adminStickerContainer').style.display = 'none';
        adminSendBtn.disabled = true;
        try {
            const formData = new FormData();
            formData.append('message', `[STICKER:${filename}]`);
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: formData,
            });
            const data = await res.json();
            if (data.success && data.message) {
                lastMsgId = Math.max(lastMsgId, data.message.id);
                const bubble = buildAdminBubble(data.message, true);
                chatBody.appendChild(bubble);
                scrollToBottom();
            }
        } catch(e) {
            console.error('Gagal mengirim stiker', e);
        } finally {
            adminSendBtn.disabled = false;
        }
    }

    // ── Context Menu & Reply state (Admin) ────────────────────────────────
    let adminContextTargetMsg = null;
    let adminReplyToMsgId = null;

    function showAdminContextMenu(e, msgId, msgText, senderName, attachmentUrl) {
        e.preventDefault();
        e.stopPropagation(); // Prevent document click from closing it immediately
        adminContextTargetMsg = { id: msgId, text: msgText, senderName: senderName, attachment: attachmentUrl };
        const menu = document.getElementById('bubbleContextMenu');
        menu.style.display = 'block';
        let x = e.clientX, y = e.clientY;
        if (x + 160 > window.innerWidth) x = window.innerWidth - 165;
        if (y + 100 > window.innerHeight) y = window.innerHeight - 105;
        menu.style.left = x + 'px';
        menu.style.top  = y + 'px';
    }

    document.addEventListener('click', () => {
        const menu = document.getElementById('bubbleContextMenu');
        if (menu) menu.style.display = 'none';
    });

    function doAdminReply() {
        if (!adminContextTargetMsg) return;
        adminReplyToMsgId = adminContextTargetMsg.id;
        const bar = document.getElementById('adminReplyBar');
        document.getElementById('adminReplyName').textContent = 'Balas ' + adminContextTargetMsg.senderName;
        const replyTextEl = document.getElementById('adminReplyText');
        if (adminContextTargetMsg.attachment) {
            const ext = adminContextTargetMsg.attachment.split('.').pop().toLowerCase();
            const isImage = ['png','jpg','jpeg','gif','webp'].includes(ext);
            const fileName = decodeURIComponent(adminContextTargetMsg.attachment.split('/').pop()).substring(0, 40);
            if (isImage) {
                replyTextEl.innerHTML = `<i class="bi bi-image" style="font-size:0.9rem;"></i> <span>${fileName}</span>`;
            } else {
                replyTextEl.innerHTML = `<i class="bi bi-file-earmark-text" style="font-size:0.9rem;"></i> <span>${fileName}</span>`;
            }
        } else {
            replyTextEl.textContent = adminContextTargetMsg.text || '';
        }
        bar.classList.add('active');
        document.getElementById('adminChatInput').focus();
    }

    function cancelAdminReply() {
        adminReplyToMsgId = null;
        const bar = document.getElementById('adminReplyBar');
        if (bar) bar.classList.remove('active');
    }

    function doAdminCopy() {
        if (!adminContextTargetMsg) return;
        const text = adminContextTargetMsg.text || '';
        if (text) navigator.clipboard.writeText(text);
    }

    async function doAdminDelete() {
        if (!adminContextTargetMsg) return;
        const msgId = adminContextTargetMsg.id;

        const result = await Swal.fire({
            title: 'Hapus Pesan?',
            text: 'Pesan ini akan dihapus dan tidak bisa dikembalikan.',
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal-delete-popup',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
            buttonsStyling: false,
        });
        if (!result.isConfirmed) return;

        try {
            const res = await fetch(`/admin/chat/messages/${msgId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            });
            const data = await res.json();
            if (data.success) {
                // Remove the bubble from DOM
                const el = document.querySelector(`.msg-row[data-msg-id="${msgId}"]`);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'scale(0.9)';
                    el.style.transition = 'all 0.25s ease';
                    setTimeout(() => el.remove(), 260);
                }
            }
        } catch(e) {
            console.error('Gagal menghapus pesan', e);
        }
    }

    function showAdminFileWarning() {
        let existing = document.getElementById('fileWarnToastAdmin');
        if (existing) existing.remove();
        const toast = document.createElement('div');
        toast.id = 'fileWarnToastAdmin';
        toast.style.cssText = 'position:fixed; bottom:100px; left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; padding:12px 22px; border-radius:12px; font-size:0.85rem; font-weight:600; z-index:9999; box-shadow:0 4px 20px rgba(0,0,0,0.3); display:flex; align-items:center; gap:8px; animation:bubbleIn 0.25s ease-out;';
        toast.innerHTML = '<i class="bi bi-exclamation-circle-fill" style="color:#f59e0b;"></i> Hanya bisa 1 file per pesan. Hapus file sebelumnya terlebih dahulu.';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    }

    const userCalibrations = @json($userCalibrations ?? []);

    function previewDraftHarga(regNumber) {
        if (!userCalibrations || !userCalibrations[regNumber] || !userCalibrations[regNumber].draft_harga) {
            alert("Dokumen pratinjau tidak tersedia untuk pesanan ini.");
            return;
        }
        
        const draftPath = userCalibrations[regNumber].draft_harga;
        const url = "{{ asset('storage') }}/" + draftPath;
        const ext = draftPath.split('.').pop().toLowerCase();
        
        previewAttachment(url, ext);
    }

    function previewAttachment(url, ext) {
        const panelBody = document.getElementById('sidePanelBody');
        const downloadBtn = document.getElementById('sidePanelDownloadBtn');
        const titleEl = document.getElementById('sidePanelTitle');
        const chatContainer = document.querySelector('.chat-container');
        
        titleEl.innerHTML = `<i class="bi bi-file-earmark-text"></i> Pratinjau Lampiran`;
        downloadBtn.href = url;
        downloadBtn.setAttribute('download', url.split('/').pop());
        
        if (['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(ext)) {
            panelBody.innerHTML = `
                <div class="text-center" style="padding: 8px 0;">
                    <img src="${url}" alt="Attachment" style="max-width: 100%; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.12);">
                </div>`;
        } else if (ext === 'pdf') {
            panelBody.innerHTML = `
                <div style="height: calc(100vh - 220px); min-height: 400px;">
                    <embed src="${url}" type="application/pdf" width="100%" height="100%" style="border-radius: 8px;" />
                </div>`;
        } else {
            panelBody.innerHTML = `
                <div style="text-align:center; padding: 32px 16px;">
                    <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#0F6EA8,#1E9447);display:inline-flex;align-items:center;justify-content:center;color:white;font-size:2rem;margin-bottom:16px;box-shadow:0 6px 20px rgba(30,148,71,0.3);">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div style="font-size:0.95rem;font-weight:700;color:var(--text-primary);margin-bottom:6px;">${url.split('/').pop()}</div>
                    <div style="font-size:0.8rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:16px;">${ext} Document</div>
                    <p style="font-size:0.82rem;color:var(--text-secondary);">Pratinjau tidak tersedia untuk format <strong>.${ext}</strong>.<br>Silakan unduh dokumen untuk membukanya.</p>
                </div>`;
        }
        
        // Open side panel
        const container = document.querySelector('.chat-container');
        if (container) container.classList.add('preview-open');
    }

    function closeSidePanel() {
        const container = document.querySelector('.chat-container');
        if (container) container.classList.remove('preview-open');
        setTimeout(() => {
            document.getElementById('sidePanelBody').innerHTML = '';
        }, 350);
    }

    // Pindahkan modal ke body agar tidak terhalang backdrop (masalah z-index parent)
    const broadcastModalEl = document.getElementById('broadcastModal');
    if(broadcastModalEl) {
        document.body.appendChild(broadcastModalEl);
    }
    const draftHargaChatModalEl = document.getElementById('draftHargaChatModal');
    if(draftHargaChatModalEl) {
        document.body.appendChild(draftHargaChatModalEl);
    }

    // Auto scroll chat to bottom
    const chatBody = document.getElementById('chatThreadBody');
    if(chatBody) {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Dropdown Live Search
    const searchInput = document.getElementById('chatSearchInput');
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const items = document.querySelectorAll('.chat-search-item');
            
            items.forEach(item => {
                const name = item.querySelector('.chat-search-name').textContent.toLowerCase();
                const email = item.querySelector('.chat-search-email').textContent.toLowerCase();
                
                if(name.includes(filter) || email.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Toggle Broadcast Target Selection
    const targetAll = document.getElementById('targetAll');
    const targetSelected = document.getElementById('targetSelected');
    const broadcastUserSelect = document.getElementById('broadcastUserSelect');
    
    function toggleUserSelect() {
        if(targetSelected.checked) {
            broadcastUserSelect.style.display = 'block';
            const checkboxes = broadcastUserSelect.querySelectorAll('.broadcast-user-checkbox');
            checkboxes.forEach(cb => cb.setAttribute('required', 'required'));
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const anyChecked = Array.from(checkboxes).some(c => c.checked);
                    checkboxes.forEach(c => {
                        if (anyChecked) {
                            c.removeAttribute('required');
                        } else {
                            c.setAttribute('required', 'required');
                        }
                    });
                });
            });
        } else {
            broadcastUserSelect.style.display = 'none';
            const checkboxes = broadcastUserSelect.querySelectorAll('.broadcast-user-checkbox');
            checkboxes.forEach(cb => cb.removeAttribute('required'));
        }
    }

    if(targetAll && targetSelected) {
        targetAll.addEventListener('change', toggleUserSelect);
        targetSelected.addEventListener('change', toggleUserSelect);
    }

    // ── Real-time AJAX Chat (Admin) ────────────────────────────────────────────
    @if($selectedUser)
    const adminInput   = document.getElementById('adminChatInput');
    const adminSendBtn = document.getElementById('adminSendBtn');
    
    // Auto-focus input when ref card is shown
    const refCard = document.getElementById('refCard');
    @if(isset($refCalibration) && $refCalibration)
    const refRegNumber = @json($refCalibration->registration_number);
    @else
    const refRegNumber = null;
    @endif
    if (refCard && adminInput) {
        adminInput.placeholder = 'Ketik balasan mengenai pesanan ini...';
        adminInput.focus();
    }


    const csrfToken    = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const storeUrl     = "{{ route('admin.chat.store', $selectedUser) }}";
    const pollUrl      = "{{ route('admin.chat.messages', $selectedUser) }}";
    const selectedUserName = @json($selectedUser->name);

    let lastMsgId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};

    function scrollToBottom(smooth = true) {
        if (chatBody) chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
    }

    function scrollToTop(smooth = true) {
        if (chatBody) chatBody.scrollTo({ top: 0, behavior: smooth ? 'smooth' : 'instant' });
    }

    let isScrolledUp = false;
    let unreadCount = 0;

    if (chatBody) {
        chatBody.addEventListener('scroll', () => {
            const scrollButtons = document.getElementById('chatScrollButtons');
            const badge = document.getElementById('newMsgBadge');
            
            if (chatBody.scrollTop > 100) {
                scrollButtons.style.display = 'flex';
            } else {
                scrollButtons.style.display = 'none';
            }

            const isAtBottom = chatBody.scrollHeight - chatBody.scrollTop - chatBody.clientHeight < 50;
            if (isAtBottom) {
                isScrolledUp = false;
                unreadCount = 0;
                badge.style.display = 'none';
                badge.innerText = '0';
            } else {
                isScrolledUp = true;
            }
        });
    }

    function buildAdminBubble(msg, isNew = false) {
        const isAdmin = msg.sender_role === 'admin';
        const row = document.createElement('div');
        row.className = `msg-row ${isAdmin ? 'msg-row--right' : 'msg-row--left'}`;
        row.dataset.msgId = msg.id;
        if (isNew) row.style.animation = 'bubbleIn 0.28s ease-out forwards';
        const avatarName = isAdmin ? 'Admin' : encodeURIComponent(selectedUserName);
        const avatarBg   = isAdmin ? '089145' : '406768';
        const senderLabel = isAdmin ? 'Admin (Anda)' : selectedUserName;

        // Reply quote (if this message is a reply to another)
        let replyQuoteHtml = '';
        if (msg.parent) {
            const parentSender = msg.parent.sender_role === 'admin' ? 'Admin (Anda)' : selectedUserName;
            let parentContent = '';
            if (msg.parent.attachment) {
                const pExt = msg.parent.attachment.split('.').pop().toLowerCase();
                const pIsImage = ['png','jpg','jpeg','gif','webp'].includes(pExt);
                const pFileName = decodeURIComponent(msg.parent.attachment.split('/').pop()).substring(0, 40);
                if (pIsImage) {
                    parentContent = `<div class="reply-quote-text" style="display:flex;align-items:center;gap:5px;"><i class="bi bi-image" style="font-size:0.9rem;"></i><span>${pFileName}</span></div>`;
                } else {
                    parentContent = `<div class="reply-quote-text" style="display:flex;align-items:center;gap:5px;"><i class="bi bi-file-earmark-text" style="font-size:0.9rem;"></i><span>${pFileName}</span></div>`;
                }
            } else {
                const pText = (msg.parent.message || '').substring(0, 60) + (msg.parent.message && msg.parent.message.length > 60 ? '...' : '');
                parentContent = `<div class="reply-quote-text">${pText}</div>`;
            }
            replyQuoteHtml = `
            <div class="reply-quote">
                <div class="reply-quote-name">${parentSender}</div>
                ${parentContent}
            </div>`;
        }

        const escapedText = (msg.message || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        const attachUrl = msg.attachment ? msg.attachment.replace(/'/g, "\\'") : '';

        // Check if it's a sticker message
        let isSticker = false;
        let stickerUrl = '';
        let msgTextForBubble = msg.message || '';
        const stickerMatch = msgTextForBubble.match(/^\[STICKER:(.+?)\]$/);
        if (stickerMatch) {
            isSticker = true;
            stickerUrl = `{{ asset('stiker') }}/${stickerMatch[1]}`;
            msgTextForBubble = ''; // Clear text so bubble isn't rendered twice
        }

        // Check for reference card prefix
        let hasRef = false;
        let refDoc = null;
        let refNum = null;
        const docMatch = msgTextForBubble.match(/^\[Dokumen (.*?) \(Pesanan (.*?)\)\]\s*(.*)/);
        const pesananMatch = msgTextForBubble.match(/^\[Pesanan (.*?)\]\s*(.*)/);
        
        if (docMatch) {
            hasRef = true;
            refDoc = docMatch[1];
            refNum = docMatch[2];
            msgTextForBubble = docMatch[3] || '';
        } else if (pesananMatch) {
            hasRef = true;
            refNum = pesananMatch[1];
            msgTextForBubble = pesananMatch[2] || '';
        }

        let refCardHtml = '';
        if (hasRef) {
            refCardHtml = `
            <div class="chat-ref-card-msg" style="display:flex; justify-content:${isAdmin ? 'flex-end' : 'flex-start'}; padding:4px 0; width:100%;">
                <div style="max-width: 360px; min-width: 200px; background: var(--card-bg); border: 1.5px solid rgba(30,148,71,0.25); border-radius: 16px 16px ${isAdmin ? '4px 16px' : '16px 4px'}; padding: 12px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.10); position: relative;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:38px; height:38px; border-radius:10px; flex-shrink:0; background: linear-gradient(135deg, #1E9447, #0F6EA8); display:flex; align-items:center; justify-content:center; color:white; font-size:1.15rem; box-shadow: 0 3px 8px rgba(30,148,71,0.3);">
                            <i class="bi ${refDoc ? 'bi-file-earmark-text' : 'bi-clipboard2-pulse'}"></i>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.65rem; font-weight:800; color:#1E9447; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px;">
                                ${refDoc ? 'DOKUMEN ' + refDoc : 'PESANAN KALIBRASI'}
                            </div>
                            <div style="font-size:0.8rem; font-weight:700; font-family:monospace; color:var(--text-primary);">${refNum}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">Pertanyaan terkait hal ini</div>
                        </div>
                    </div>
                    <div style="margin-top:10px; border-top:1px solid var(--card-border); padding-top:8px; display:flex; justify-content:flex-end;">
                        ${refDoc 
                            ? `<button type="button" onclick="previewDraftHarga('${refNum}')" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';"><i class="bi bi-eye"></i> Pratinjau</button>`
                            : `<a href="/admin/calibrations?search=${refNum}" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';"><i class="bi bi-eye"></i> Detail</a>`
                        }
                    </div>
                </div>
            </div>`;
        }

        let attachmentHtml = '';
        if (msg.attachment) {
            const ext = msg.attachment.split('.').pop().toLowerCase();
            const isImage = ['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(ext);
            const fileNameParts = msg.attachment.split('/');
            let fileName = fileNameParts[fileNameParts.length - 1];
            if (fileName.length > 30) fileName = fileName.substring(0, 30) + '...';
            
            if (isImage) {
                attachmentHtml = `
                <div class="message ${isAdmin ? 'sent' : 'received'} p-2" style="margin-bottom: ${msg.message ? '5px' : '0'};">
                    <div class="msg-chevron" onclick="showAdminContextMenu(event, ${msg.id}, '${escapedText}', '${senderLabel}', '${attachUrl}')"><i class="bi bi-chevron-down"></i></div>
                    ${replyQuoteHtml}
                    <img src="${msg.attachment}" alt="Attachment" style="max-width: 250px; border-radius: 8px; display: block; cursor: pointer;" onclick="previewAttachment('${msg.attachment}', '${ext}')">
                </div>`;
            } else {
                attachmentHtml = `
                <div class="message ${isAdmin ? 'sent' : 'received'} p-3" style="margin-bottom: ${msg.message ? '5px' : '0'}; min-width: 260px;">
                    <div class="msg-chevron" onclick="showAdminContextMenu(event, ${msg.id}, '${escapedText}', '${senderLabel}', '${attachUrl}')"><i class="bi bi-chevron-down"></i></div>
                    ${replyQuoteHtml}
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:48px; height:48px; border-radius:12px; background: linear-gradient(135deg, #0F6EA8, #1E9447); display:flex; align-items:center; justify-content:center; color:white; font-size:1.5rem; flex-shrink:0; box-shadow: 0 4px 10px rgba(30,148,71,0.3);">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div style="flex:1; min-width:0; line-height:1.3;">
                            <div style="font-size:0.75rem; font-weight:800; color:#fff; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${fileName}</div>
                            <div style="font-size:0.75rem; color:rgba(255,255,255,0.8); text-transform:uppercase;">${ext} Document</div>
                            <div style="margin-top:8px;">
                                <button type="button" onclick="previewAttachment('${msg.attachment}', '${ext}')" style="font-size:0.75rem; font-weight:700; padding:4px 12px; border-radius:6px; border:1.5px solid #fff; color:#fff; background:transparent; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)';" onmouseout="this.style.background='transparent';">
                                    <i class="bi bi-eye"></i> Pratinjau
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            replyQuoteHtml = ''; // Already included in attachment bubble
        }

        let textBubble = '';
        if (isSticker) {
            textBubble = `<div style="margin-top:5px; margin-bottom:5px; text-align:${isAdmin ? 'right' : 'left'};">
                <img src="${stickerUrl}" alt="Stiker" style="width: 120px; height: auto; display: inline-block;">
            </div>`;
        } else if (msgTextForBubble) {
            textBubble = `<div class="message ${isAdmin ? 'sent' : 'received'}"><div class="msg-chevron" onclick="showAdminContextMenu(event, ${msg.id}, '${escapedText}', '${senderLabel}', '${attachUrl}')"><i class="bi bi-chevron-down"></i></div>${replyQuoteHtml}${msgTextForBubble}</div>`;
        }

        row.innerHTML = `
            ${refCardHtml}
            <img class="msg-avatar" src="https://ui-avatars.com/api/?name=${avatarName}&background=${avatarBg}&color=fff">
            <div class="msg-body" oncontextmenu="showAdminContextMenu(event, ${msg.id}, '${escapedText}', '${senderLabel}', '${attachUrl}')">
                <div class="msg-header">
                    <span class="msg-sender">${senderLabel}</span>
                    <span class="msg-time-top">${msg.time}</span>
                </div>
                ${attachmentHtml}
                ${textBubble}
            </div>`;
        return row;
    }

    // Build a ref-card "bubble" to inject into the chat thread (same visual as the ref card bar)
    function buildRefCardChatBubble(regNumber, instansi, status, deviceName, detailUrl) {
        const wrapper = document.createElement('div');
        wrapper.className = 'chat-ref-card-msg';
        wrapper.style.cssText = 'display:flex; justify-content:flex-end; padding:4px 0; animation:bubbleIn 0.28s ease-out forwards;';
        wrapper.innerHTML = `
            <div style="
                max-width: 360px; min-width: 200px;
                background: var(--card-bg);
                border: 1.5px solid rgba(30,148,71,0.25);
                border-radius: 16px 16px 4px 16px;
                padding: 12px 14px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.10);
                position: relative;
            ">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="
                        width:38px; height:38px; border-radius:10px; flex-shrink:0;
                        background: linear-gradient(135deg, #1E9447, #0F6EA8);
                        display:flex; align-items:center; justify-content:center;
                        color:white; font-size:1.15rem;
                        box-shadow: 0 3px 8px rgba(30,148,71,0.3);
                    "><i class="bi bi-clipboard2-pulse"></i></div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.65rem; font-weight:800; color:#1E9447; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px;">Pesanan Kalibrasi</div>
                        <div style="font-size:0.8rem; font-weight:700; font-family:monospace; color:var(--text-primary);">${regNumber}</div>
                        <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">
                            ${instansi} · ${status}${deviceName ? ' · ' + deviceName : ''}
                        </div>
                    </div>
                </div>
                <div style="margin-top:10px; border-top:1px solid var(--card-border); padding-top:8px; display:flex; justify-content:flex-end;">
                    <a href="${detailUrl}" style="
                        font-size:0.75rem; font-weight:700; padding:5px 14px;
                        border-radius:8px; border:1.5px solid #1E9447;
                        color:#1E9447; background:transparent;
                        text-decoration:none; display:inline-flex; align-items:center; gap:5px;
                        transition:all 0.2s;
                    " onmouseover="this.style.background='#1E9447';this.style.color='white';"
                       onmouseout="this.style.background='transparent';this.style.color='#1E9447';">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </div>
            </div>`;
        return wrapper;
    }

    function buildDateSep(dateLabel, dateVal) {
        const div = document.createElement('div');
        div.className = 'chat-date-sep';
        div.dataset.date = dateVal;
        div.innerHTML = `<span>${dateLabel}</span>`;
        return div;
    }

    async function sendAdminMessage() {
        let text = adminInput.value.trim();
        const fileInput = document.getElementById('adminAttachment');
        const file = fileInput ? fileInput.files[0] : null;
        const activeRefCard = document.getElementById('refCard');
        
        if (!text && !file) return;
        
        adminSendBtn.disabled = true;

        let textToSend = text;
        if (activeRefCard && typeof refRegNumber !== 'undefined' && refRegNumber) {
            textToSend = `[Pesanan ${refRegNumber}] ` + text;
        }

        const formData = new FormData();
        if (textToSend) formData.append('message', textToSend);
        if (file) formData.append('attachment', file);
        if (adminReplyToMsgId) formData.append('parent_id', adminReplyToMsgId);
        
        adminInput.value = '';
        if (fileInput) fileInput.value = '';
        cancelAdminReply();

        try {
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });
            const data = await res.json();
            if (data.success && data.message) {
                lastMsgId = Math.max(lastMsgId, data.message.id);
                document.getElementById('adminFilePreview').style.display = 'none';

                // Jika kartu referensi aktif, tampilkan kartu dulu di chat thread
                if (activeRefCard && typeof refRegNumber !== 'undefined' && refRegNumber) {
                    @if(isset($refCalibration) && $refCalibration)
                    const cardBubble = buildRefCardChatBubble(
                        @json($refCalibration->registration_number),
                        @json($refCalibration->nama_instansi),
                        @json($refCalibration->status),
                        @json($refCalibration->device_name ?? ''),
                        "{{ route('admin.calibrations.show', $refCalibration) }}"
                    );
                    chatBody.appendChild(cardBubble);
                    @endif
                    
                    // Strip the prefix for the text bubble
                    if (data.message.message && data.message.message.startsWith(`[Pesanan ${refRegNumber}] `)) {
                        data.message.message = data.message.message.substring(`[Pesanan ${refRegNumber}] `.length);
                    }
                }

                // Date separator if needed
                const allSeps = chatBody.querySelectorAll('.chat-date-sep');
                const lastSep = allSeps.length > 0 ? allSeps[allSeps.length - 1] : null;
                const todayDate = data.message.date || new Date().toISOString().slice(0, 10);
                if (!lastSep || lastSep.dataset.date !== todayDate) {
                    const existingSep = chatBody.querySelector(`.chat-date-sep[data-date="${todayDate}"]`);
                    if (!existingSep) {
                        chatBody.appendChild(buildDateSep('Hari ini', todayDate));
                    }
                }

                // Kemudian tampilkan bubble teks admin di bawahnya
                if ((data.message.message && data.message.message.trim() !== '') || data.message.attachment) {
                    const bubble = buildAdminBubble(data.message, true);
                    chatBody.appendChild(bubble);
                }
                scrollToBottom();

                // Dismiss ref card dengan animasi setelah pesan dikirim
                if (activeRefCard) {
                    activeRefCard.style.transition = 'opacity 0.3s, transform 0.3s';
                    activeRefCard.style.opacity = '0';
                    activeRefCard.style.transform = 'translateY(8px)';
                    setTimeout(() => { activeRefCard.remove(); }, 300);
                    adminInput.placeholder = 'Ketik pesan...';
                }
            } else if (!data.success) {
                // Fallback: submit form biasa
                const form = document.createElement('form');
                form.method = 'POST'; form.action = storeUrl;
                form.innerHTML = `<input name="_token" value="${csrfToken}"><input name="message" value="${text}">`;
                document.body.appendChild(form); form.submit();
            }
        } catch (e) {
            adminInput.value = text;
            console.error("ERROR SENDING:", e);
            alert("Gagal mengirim pesan: " + e.message);
        } finally {
            adminSendBtn.disabled = false;
            adminInput.focus();
        }
    }

    adminSendBtn.addEventListener('click', sendAdminMessage);
    adminInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendAdminMessage(); }
    });

    // Poll for new messages from user
    async function pollAdminMessages() {
        try {
            const res  = await fetch(pollUrl + '?_=' + Date.now(), { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            
            const allSeps = chatBody.querySelectorAll('.chat-date-sep');
            let lastSepDate = allSeps.length > 0 ? allSeps[allSeps.length - 1].dataset.date : null;
            const todayDate = new Date().toISOString().slice(0, 10);
            
            let hasNew = false;
            data.messages.forEach(msg => {
                if (msg.id <= lastMsgId) return;
                if (msg.sender_role === 'admin') { lastMsgId = Math.max(lastMsgId, msg.id); return; }
                
                if (msg.date !== lastSepDate) {
                    const existingSep = chatBody.querySelector(`.chat-date-sep[data-date="${msg.date}"]`);
                    if (!existingSep) {
                        chatBody.appendChild(buildDateSep(msg.date_label, msg.date));
                    }
                    lastSepDate = msg.date;
                }
                
                const bubble = buildAdminBubble(msg, true);
                chatBody.appendChild(bubble);
                lastMsgId = Math.max(lastMsgId, msg.id);
                hasNew = true;
            });
            if (hasNew) {
                if (isScrolledUp) {
                    unreadCount++;
                    const badge = document.getElementById('newMsgBadge');
                    badge.innerText = unreadCount;
                    badge.style.display = 'inline-block';
                } else {
                    scrollToBottom();
                }
            }
        } catch (e) { /* silently ignore */ }
    }

    setInterval(pollAdminMessages, 3000);
    @endif
</script>
<style>
@keyframes bubbleIn {
    from { opacity: 0; transform: translateY(10px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* ===== FLOATING SCROLL BUTTONS ===== */
.admin-scroll-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,0.08);
    background: rgba(255,255,255,0.92);
    color: #334;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.18s, transform 0.15s, box-shadow 0.18s;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 0;
}
.admin-scroll-btn:hover {
    background: #fff;
    transform: scale(1.08);
    box-shadow: 0 4px 14px rgba(0,0,0,0.16);
}
[data-theme="dark"] .admin-scroll-btn,
.dark-mode .admin-scroll-btn {
    background: rgba(40,48,60,0.92);
    border-color: rgba(255,255,255,0.1);
    color: #e0e6ef;
}
[data-theme="dark"] .admin-scroll-btn:hover,
.dark-mode .admin-scroll-btn:hover {
    background: rgba(55,65,80,0.98);
}
</style>
@endpush
