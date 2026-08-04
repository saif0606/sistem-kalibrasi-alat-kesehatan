@extends('layouts.app')

@section('title', 'Chat - UPTD Kalibrasi')
@section('hide_chrome', true)
@section('body_class', 'chat-page-body')
@section('main_style', 'flex:1; display:flex; flex-direction:column; overflow:hidden; min-height:0;')

@push('styles')
<style>
/* ===== Full-page chat ===== */
html, body { height: 100%; }
body.chat-page-body { overflow: hidden; display: flex; flex-direction: column; position: relative; }
body.chat-page-body main {
    flex: 1; min-height: 0;
    display: flex; flex-direction: column;
    overflow: hidden;
    z-index: 1; position: relative;
    /* Offset fixed navbar â€” adjust --navbar-h to match your actual navbar height */
    padding-top: 0 !important;
    margin-top: 0 !important;
}
body.chat-page-body nav,
body.chat-page-body .navbar { position: relative !important; flex-shrink: 0; z-index: 100; }
body.chat-page-body footer { display: none !important; }
body.chat-page-body .back-to-top { display: none !important; }

/* Batik on body removed, will use user-chat-wrap::before */

/* Floating ambient blobs */
.blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(60px);
    opacity: 0.35;
    z-index: 0;
    pointer-events: none;
}
.blob-1 { width: 380px; height: 380px; top: -120px; left: -100px; background: radial-gradient(circle, #22c07a, transparent 70%); }
.blob-2 { width: 420px; height: 420px; top: 120px; right: -140px; background: radial-gradient(circle, #4d8bff, transparent 70%); animation: blobFloat 14s ease-in-out infinite; }
.blob-3 { width: 300px; height: 300px; bottom: -80px; left: 35%; background: radial-gradient(circle, #17a45c, transparent 70%); animation: blobFloat 18s ease-in-out infinite reverse; }
@keyframes blobFloat { 0%,100%{ transform:translateY(0px);} 50%{ transform:translateY(30px);} }
@media (prefers-reduced-motion: reduce){ .blob-2, .blob-3{ animation:none; } }

:root {
    --chat-bg: #f0f2f5;
    --panel-bg: rgba(255,255,255,0.72);
    --panel-border: rgba(255,255,255,0.85);
    --input-bg: rgba(255,255,255,0.9);
    --glass-blur: blur(18px);
    --bubble-sent-bg: #406768;
    --bubble-sent-color: #ffffff;
    --bubble-recv-bg: #089145;
    --bubble-recv-color: #ffffff;
    --bubble-shadow: 0 1px 3px rgba(0,0,0,0.15);
    --batik-opacity: 0.07;
    --batik-filter: none;
    --text-muted: #64748b;
    --card-bg: #ffffff;
    --card-border: rgba(0,0,0,0.06);
    --text-primary: #1e293b;
}
[data-theme="dark"] {
    --chat-bg: #0e1a24;
    --panel-bg: rgba(14,26,36,0.85);
    --panel-border: rgba(255,255,255,0.06);
    --input-bg: #1e2d38;
    --bubble-sent-bg: #406768;
    --bubble-sent-color: #ffffff;
    --bubble-recv-bg: #089145;
    --bubble-recv-color: #ffffff;
    --batik-opacity: 0.07;
    --batik-filter: invert(1) grayscale(1) opacity(0.5);
    --text-muted: #94a3b8;
    --card-bg: #1a2738;
    --card-border: rgba(255,255,255,0.08);
    --text-primary: #f8fafc;
}

/* ===== QUICK REPLIES ===== */
.quick-replies-container {
    display: none;
    position: absolute;
    bottom: 70px;
    left: 20px;
    background: var(--chat-bg);
    padding: 12px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    z-index: 100;
    max-width: 300px;
    max-height: 350px;
    overflow-y: auto;
    border: 1px solid var(--panel-border);
}
.quick-replies-scroll {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.quick-replies-container::-webkit-scrollbar { width: 4px; }
.quick-replies-container::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 4px; }
.quick-reply-chip {
    white-space: nowrap;
    padding: 8px 14px;
    border-radius: 20px;
    background: rgba(30, 148, 71, 0.1);
    color: #089145;
    border: 1px solid rgba(30, 148, 71, 0.2);
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}
.quick-reply-chip:hover {
    background: rgba(30, 148, 71, 0.2);
    color: #065f2d;
}
[data-theme="dark"] .quick-reply-chip {
    background: rgba(30, 148, 71, 0.15);
    color: #4ade80;
    border-color: rgba(30, 148, 71, 0.3);
}
[data-theme="dark"] .quick-reply-chip:hover {
    background: rgba(30, 148, 71, 0.25);
    color: #86efac;
}

/* ===== LAYOUT ===== */
.chat-outer-wrap {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    gap: 0;
    width: 100%;
    /* flex:1 inside a flex-column parent = fills remaining height below navbar */
    flex: 1;
    min-height: 0;
    padding: 16px 16px 16px;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.user-chat-wrap {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(22px) saturate(150%);
    -webkit-backdrop-filter: blur(22px) saturate(150%);
    position: relative;
    min-height: 0;
    width: 100%;
    border: 1px solid rgba(255,255,255,0.65);
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(15,60,50,0.10);
    transition: border-radius 0.3s ease;
}

@media (max-width: 600px) {
    .chat-outer-wrap {
        padding: 0;
    }
    .user-chat-wrap {
        border-radius: 0;
        border-left: none;
        border-right: none;
        border-bottom: none;
    }
}

.chat-outer-wrap.preview-open .user-chat-wrap {
    border-radius: 24px 0 0 24px;
}

.chat-outer-wrap.preview-open .header-subtitle,
.chat-outer-wrap.preview-open .header-desc {
    display: none !important;
}

/* ===== DOC SIDE PANEL ===== */
.doc-side-panel {
    width: 0;
    min-width: 0;
    min-height: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(18px) saturate(160%);
    -webkit-backdrop-filter: blur(18px) saturate(160%);
    border: 1px solid rgba(255,255,255,0.65);
    border-left: none;
    border-radius: 0 24px 24px 0;
    box-shadow: 0 8px 32px rgba(15,60,50,0.10);
    transition: width 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;
    opacity: 0;
    pointer-events: none;
    flex-shrink: 0;
    position: relative;
}

.doc-side-panel-resizer {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 6px;
    cursor: ew-resize;
    background: transparent;
    z-index: 10;
    transition: background 0.2s;
}
.doc-side-panel-resizer:hover,
.doc-side-panel-resizer.dragging {
    background: rgba(30,148,71,0.25);
}

[data-theme="dark"] .doc-side-panel {
    background: rgba(14,26,36,0.80);
    border-color: rgba(255,255,255,0.06);
}

.chat-outer-wrap.preview-open .doc-side-panel {
    width: 420px;
    opacity: 1;
    pointer-events: all;
}

@media (max-width: 900px) {
    .chat-outer-wrap.preview-open .doc-side-panel {
        width: 340px;
    }
}

@media (max-width: 600px) {
    .chat-outer-wrap.preview-open {
        flex-direction: column;
    }
    .chat-outer-wrap.preview-open .user-chat-wrap {
        border-radius: 24px 24px 0 0;
    }
    .chat-outer-wrap.preview-open .doc-side-panel {
        width: 100%;
        height: 360px;
        border-radius: 0 0 24px 24px;
        border-left: 1px solid rgba(255,255,255,0.65);
        border-top: none;
    }
}

.doc-side-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--panel-border, rgba(0,0,0,0.08));
    background: linear-gradient(135deg, rgba(9,74,115,0.08), rgba(30,148,71,0.06));
    flex-shrink: 0;
}

.doc-side-panel-header h6 {
    margin: 0;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    display: flex;
    align-items: center;
    gap: 8px;
}

.doc-side-panel-header h6 i {
    color: #1E9447;
    font-size: 1rem;
}

.doc-side-panel-close {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    border: none;
    background: rgba(0,0,0,0.06);
    color: var(--text-muted, #64748b);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.doc-side-panel-close:hover {
    background: rgba(239,68,68,0.12);
    color: #ef4444;
}

.doc-side-panel-body {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.doc-side-panel-download {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 16px;
    border-top: 1px solid var(--panel-border, rgba(0,0,0,0.08));
    flex-shrink: 0;
}

.doc-side-panel-download a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 8px 20px;
    border-radius: 10px;
    border: 1.5px solid #1E9447;
    color: #1E9447;
    text-decoration: none;
    background: transparent;
    transition: all 0.2s;
}

.doc-side-panel-download a:hover {
    background: #1E9447;
    color: white;
}

/* Batik background on the message area */
.user-chat-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url('{{ asset("images/batik.png") }}');
    background-size: 472px 472px;
    opacity: var(--batik-opacity);
    filter: var(--batik-filter);
    pointer-events: none;
    z-index: 0;
}
[data-theme="dark"] .user-chat-wrap {
    background: rgba(14,26,36,0.65);
    border-color: rgba(255,255,255,0.06);
}

/* ===== GLASSMORPHISM HEADER ===== */
.chat-user-header {
    /* Glassmorphism effect with blue-green gradient */
    background: linear-gradient(135deg, rgba(9, 74, 115, 0.75) 0%, rgba(30, 148, 71, 0.65) 100%);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.18);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
    z-index: 2;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255,255,255,0.2);
    flex-shrink: 0;
    border-radius: 24px 24px 0 0;
}
.chat-user-header .avatar {
    width: 46px; height: 46px; border-radius: 50%;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #fff; flex-shrink: 0;
    border: 1.5px solid rgba(255,255,255,0.35);
    box-shadow: 0 4px 14px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.2);
    position: relative;
    z-index: 1;
}
/* Online dot */
.chat-user-header .avatar::after {
    content: '';
    position: absolute;
    bottom: 2px; right: 2px;
    width: 10px; height: 10px;
    border-radius: 50%;
    background: #22c55e;
    border: 2px solid rgba(255,255,255,0.8);
    box-shadow: 0 0 6px rgba(34,197,94,0.7);
}
.chat-user-header .info {
    position: relative;
    z-index: 1;
}
.chat-user-header .info h6 {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    margin: 0 0 2px;
    text-shadow: 0 1px 4px rgba(0,0,0,0.25);
}
.chat-user-header .info small {
    color: rgba(255,255,255,0.8);
    font-size: 0.78rem;
}
.chat-user-header .btn-wa {
    margin-left: auto;
    background: rgba(37, 211, 102, 0.85);
    backdrop-filter: blur(8px);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 30px;
    padding: 9px 20px;
    font-size: 0.82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(37,211,102,0.45), inset 0 1px 0 rgba(255,255,255,0.2);
    transition: all 0.2s;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.chat-user-header .btn-wa:hover {
    background: rgba(30, 190, 90, 0.95);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37,211,102,0.55);
}

/* ===== MESSAGES ===== */
.chat-messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 20px 22px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    position: relative;
    z-index: 1;
}
.chat-messages-area::-webkit-scrollbar { width: 6px; }
.chat-messages-area::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 3px; }

/* Date separator */
.chat-date-sep {
    text-align: center;
    margin: 8px 0;
}
.chat-date-sep span {
    background: rgba(0,0,0,0.15);
    backdrop-filter: blur(8px);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 20px;
}

.msg-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    max-width: 74%;
}
.msg-row--left  { align-self: flex-start; flex-direction: row; }
.msg-row--right { align-self: flex-end;   flex-direction: row-reverse; }

.msg-avatar-sm {
    width: 36px; height: 36px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0; margin-top: 2px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #fff; font-weight: 700;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}

.msg-body { display: flex; flex-direction: column; gap: 4px; min-width: 0; }

.msg-header-row {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.74rem;
}
.msg-row--right .msg-header-row { flex-direction: row-reverse; }

.msg-sender-name {
    font-weight: 700;
    color: #1e293b;
}
[data-theme="dark"] .msg-sender-name { color: #f8fafc; }

.msg-time-label {
    color: #64748b;
    font-size: 0.67rem;
}
[data-theme="dark"] .msg-time-label { color: #94a3b8; }

/* Bubble */
.msg-bubble {
    padding: 9px 14px;
    border-radius: 14px;
    font-size: 0.9rem;
    line-height: 1.55;
    box-shadow: var(--bubble-shadow);
    word-break: break-word;
    position: relative;
}
.msg-bubble.from-user {
    background: var(--bubble-sent-bg);
    color: var(--bubble-sent-color);
    border-top-right-radius: 4px;
}
.msg-bubble.from-admin {
    background: var(--bubble-recv-bg);
    color: var(--bubble-recv-color);
    border-top-left-radius: 4px;
    border: 1px solid rgba(0,0,0,0.05);
}
[data-theme="dark"] .msg-bubble.from-admin { border-color: transparent; }

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
.msg-bubble:hover .msg-chevron { opacity: 1; }
.from-admin .msg-chevron { color: rgba(0,0,0,0.4); background: radial-gradient(circle, rgba(255,255,255,0.5) 0%, transparent 70%); }
[data-theme="dark"] .from-admin .msg-chevron { color: rgba(255,255,255,0.5); background: radial-gradient(circle, rgba(0,0,0,0.3) 0%, transparent 70%); }

/* Bubble entrance animation */
@keyframes bubbleIn {
    from { opacity: 0; transform: translateY(10px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.msg-row.is-new { animation: bubbleIn 0.28s ease-out forwards; }

/* ===== FLOATING SCROLL BUTTONS ===== */
.scroll-float-btn {
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
.scroll-float-btn:hover {
    background: #fff;
    transform: scale(1.08);
    box-shadow: 0 4px 14px rgba(0,0,0,0.16);
}
[data-theme="dark"] .scroll-float-btn,
.dark-mode .scroll-float-btn {
    background: rgba(40,48,60,0.92);
    border-color: rgba(255,255,255,0.1);
    color: #e0e6ef;
}
[data-theme="dark"] .scroll-float-btn:hover,
.dark-mode .scroll-float-btn:hover {
    background: rgba(55,65,80,0.98);
}

/* ===== CONTEXT MENU ===== */
.bubble-context-menu {
    position: fixed;
    z-index: 9999;
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 12px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.25);
    min-width: 140px;
    overflow: hidden;
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

/* ===== REPLY PREVIEW BAR ===== */
.reply-preview-bar {
    display: none;
    margin: 0; padding: 8px 16px;
    background: var(--card-bg);
    border-top: 1px solid var(--panel-border);
    border-left: 3px solid #1E9447;
    align-items: center; gap: 10px;
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
.msg-bubble .reply-quote-name,
.message .reply-quote-name {
    font-weight: 700 !important;
    color: rgba(255,255,255,0.95) !important;
    font-size: 0.72rem;
    margin-bottom: 2px;
}
.msg-bubble .reply-quote-text,
.message .reply-quote-text {
    color: rgba(255,255,255,0.72) !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 240px;
}

/* Empty state */
.chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: rgba(255,255,255,0.7);
    text-align: center;
    padding: 40px;
    position: relative;
    z-index: 1;
}
.chat-empty-state i { font-size: 3.5rem; opacity: 0.5; }
.chat-empty-state p { font-size: 0.9rem; margin: 0; text-shadow: 0 1px 4px rgba(0,0,0,0.3); }

/* === Order Reference Card (Marketplace-style) === */
.chat-ref-card {
    margin: 0;
    padding: 12px 20px;
    background: var(--card-bg);
    backdrop-filter: var(--glass-blur);
    border-top: 1px solid var(--panel-border);
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

/* ===== INPUT AREA ===== */
/* Sticker Picker */
#userStickerContainer {
    display: none;
    position: absolute;
    bottom: 70px;
    left: 20px;
    z-index: 100;
    background: var(--card-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    width: 320px;
    max-height: 300px;
    overflow-y: auto;
}
.sticker-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.sticker-item {
    cursor: pointer;
    border-radius: 8px;
    padding: 4px;
    transition: background 0.2s;
}
.sticker-item:hover {
    background: rgba(0,0,0,0.05);
}
.sticker-item img {
    width: 100%;
    height: auto;
    object-fit: contain;
}

/* ===== CHAT INPUT BAR â€” Modern Redesign ===== */
.chat-input-bar {
    background: var(--panel-bg);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border-top: 1px solid var(--panel-border);
    padding: 14px 18px;
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}

.chat-input-card {
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #f1f5f9;
    border: 1.5px solid rgba(15,23,42,0.15);
    border-radius: 20px;
    padding: 12px 14px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 0 4px 12px rgba(0,0,0,0.05);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.chat-input-card:focus-within {
    border-color: #1E9447;
    box-shadow: 0 0 0 3px rgba(30,148,71,0.12), 0 0 18px rgba(30,148,71,0.08);
}
[data-theme="dark"] .chat-input-card {
    background: rgba(30,45,56,0.9);
    border-color: rgba(255,255,255,0.08);
}
[data-theme="dark"] .chat-input-card:focus-within {
    border-color: #22c07a;
    box-shadow: 0 0 0 3px rgba(34,192,122,0.15), 0 0 20px rgba(34,192,122,0.1);
}

.chat-input-field {
    flex: 1;
    padding: 2px 4px;
    border: none;
    background: transparent;
    color: var(--text-900, #1e293b);
    font-size: 0.92rem;
    outline: none;
    resize: none;
    font-family: inherit;
    line-height: 1.4;
    min-height: 24px;
    max-height: 80px;
    overflow-y: auto;
}
[data-theme="dark"] .chat-input-field { color: #e9edef; }
.chat-input-field::placeholder { color: var(--text-muted); font-style: italic; transition: opacity 0.4s ease; }

.chat-input-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.chat-input-left-actions { display: flex; align-items: center; gap: 6px; }
.typing-indicator { display: inline-flex; align-items: center; gap: 4px; padding: 4px 0; }
.typing-indicator span {
    width: 6px; height: 6px; background-color: #1E9447;
    border-radius: 50%; animation: typingBounce 1.4s infinite ease-in-out both;
}
.typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}

/* Action buttons â€” pill style */
.btn-action-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    border: 1.5px solid var(--panel-border);
    background: rgba(255,255,255,0.6);
    color: var(--text-muted);
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    user-select: none;
}
.btn-action-pill:hover {
    background: rgba(30,148,71,0.1);
    border-color: #1E9447;
    color: #1E9447;
    transform: translateY(-1px);
}
[data-theme="dark"] .btn-action-pill {
    background: rgba(255,255,255,0.05);
    border-color: rgba(255,255,255,0.12);
    color: #94a3b8;
}
[data-theme="dark"] .btn-action-pill:hover {
    background: rgba(34,192,122,0.15);
    border-color: #22c07a;
    color: #22c07a;
}
.btn-action-pill i { font-size: 0.95rem; }

.btn-action-pill.active {
    background: rgba(30,148,71,0.12);
    border-color: #1E9447;
    color: #1E9447;
}
[data-theme="dark"] .btn-action-pill.active {
    background: rgba(34,192,122,0.15);
    border-color: #22c07a;
    color: #22c07a;
}

.btn-send-msg {
    width: 40px; height: 40px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #17a45c, #1E9447);
    color: #fff;
    font-size: 1rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
    box-shadow: 0 3px 12px rgba(30,148,71,0.40);
}
.btn-send-msg:hover { background: linear-gradient(135deg, #15924f, #157538); transform: scale(1.08); box-shadow: 0 5px 18px rgba(30,148,71,0.5); }
.btn-send-msg.sending { opacity: 0.6; pointer-events: none; }

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
<!-- Floating ambient blobs -->
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<div class="container px-0 px-md-3 d-flex flex-column flex-grow-1" style="min-height: 0;">
<div class="chat-outer-wrap" id="chatOuterWrap">
<div class="user-chat-wrap">

    {{-- Glassmorphism Header --}}
    <div class="chat-user-header">
        <div class="avatar"><i class="bi bi-headset"></i></div>
        <div class="info">
            <h6>UPTD Kalibrasi<span class="header-subtitle"> â€“ Customer Service</span></h6>
            <small class="header-desc">Ajukan pertanyaan seputar layanan kalibrasi Anda</small>
        </div>
        <a href="https://api.whatsapp.com/send/?phone=6281292923438&text=Halo%20UPTD%20Kalibrasi%2C%20saya%20ingin%20bertanya%20mengenai%20layanan%20kalibrasi.&type=phone_number&app_absent=0"
           target="_blank" rel="noopener" class="btn-wa">
            <i class="bi bi-whatsapp"></i>
            <span class="d-none d-sm-inline">Chat via WhatsApp</span>
            <span class="d-inline d-sm-none">WA</span>
        </a>
    </div>

    {{-- Messages --}}
    <div class="chat-messages-area" id="chatBody">
        @forelse($messages as $msg)
            @php
                $isUser   = $msg->sender_role === 'user';
                $showDate = $loop->first || $msg->created_at->format('Y-m-d') !== $messages[$loop->index - 1]->created_at->format('Y-m-d');
            @endphp

            @if($showDate)
            <div class="chat-date-sep" data-date="{{ $msg->created_at->format('Y-m-d') }}">
                <span>
                    @if($msg->created_at->isToday()) Hari ini
                    @elseif($msg->created_at->isYesterday()) Kemarin
                    @else {{ $msg->created_at->format('d M Y') }}
                    @endif
                </span>
            </div>
            @endif

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
            <div class="chat-ref-card-msg" style="display:flex; justify-content:{{ $isUser ? 'flex-end' : 'flex-start' }}; padding:4px 0;">
                <div style="max-width: 360px; min-width: 200px; background: var(--card-bg); border: 1.5px solid rgba(30,148,71,0.25); border-radius: 16px 16px {{ $isUser ? '4px 16px' : '16px 4px' }}; padding: 12px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.10); position: relative;">
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
                            @php
                                $calId = $calibrationIdMap[$refNum] ?? null;
                            @endphp
                            <a href="{{ $calId ? route('user.calibrations.show', $calId) : route('user.calibrations.index') }}" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';">
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

            @php
                $isBot = $msg->sender_role === 'bot';
                $avatarName = $isUser ? urlencode(auth()->user()->name) : ($isBot ? 'Bot' : 'Admin+UPTD');
                $avatarBg = $isUser ? '406768' : ($isBot ? '0b5ed7' : '089145');
                $senderName = $isUser ? auth()->user()->name : ($isBot ? 'Asisten Bot' : 'Admin UPTD');
            @endphp
            <div class="msg-row {{ $isUser ? 'msg-row--right' : 'msg-row--left' }}" data-msg-id="{{ $msg->id }}">
                <img class="msg-avatar-sm"
                    src="https://ui-avatars.com/api/?name={{ $avatarName }}&background={{ $avatarBg }}&color=fff"
                    alt="{{ $senderName }}">
                <div class="msg-body">
                    <div class="msg-header-row">
                        <span class="msg-sender-name">{{ $senderName }}</span>
                        <span class="msg-time-label">{{ $msg->created_at->format('H:i') }}</span>
                    </div>

                    @php
                        $renderedReplyQuote = false;
                    @endphp

                    @if($msg->attachment)
                    <div class="msg-bubble {{ $isUser ? 'from-user' : 'from-admin' }} p-2" style="margin-bottom: {{ trim($msgText) !== '' ? '5px' : '0' }}; min-width: 250px;">
                        <div class="msg-chevron" onclick="showContextMenu(event, {{ $msg->id }}, '{{ addslashes($msgText) }}', '{{ addslashes($senderName) }}', '{{ addslashes($msg->attachment) }}')"><i class="bi bi-chevron-down"></i></div>
                        
                        @if($msg->parent)
                            @php
                                $parentSender = $msg->parent->sender_role === 'user' ? (auth()->check() && $msg->parent->user_id === auth()->id() ? auth()->user()->name : 'User') : 'Admin UPTD';
                                if ($msg->parent->attachment) {
                                    $parentAttachExt = strtolower(pathinfo($msg->parent->attachment, PATHINFO_EXTENSION));
                                    $parentAttachName = basename($msg->parent->attachment);
                                    $parentIsImage = in_array($parentAttachExt, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                    $parentPreview = null; // handled via HTML
                                } else {
                                    $parentPreview = Str::limit($msg->parent->message, 60);
                                    $parentAttachExt = null;
                                    $parentAttachName = null;
                                    $parentIsImage = false;
                                }
                                $renderedReplyQuote = true;
                            @endphp
                            <div class="reply-quote">
                                <div class="reply-quote-name">{{ $parentSender }}</div>
                                @if($parentAttachExt)
                                    <div class="reply-quote-text" style="display:flex; align-items:center; gap:5px;">
                                        <i class="bi {{ $parentIsImage ? 'bi-image' : 'bi-file-earmark-text' }}" style="font-size:0.9rem;"></i>
                                        <span>{{ strlen($parentAttachName) > 30 ? substr($parentAttachName, 0, 30) . '...' : $parentAttachName }}</span>
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
                                <button type="button" onclick="previewAttachment('{{ $fileUrl }}', '{{ $ext }}')" style="flex:1; font-size:0.75rem; font-weight:700; padding:6px; border-radius:8px; border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.9); background:rgba(255,255,255,0.12); display:inline-flex; align-items:center; justify-content:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)';" onmouseout="this.style.background='rgba(255,255,255,0.12)';">
                                    <i class="bi bi-eye"></i> Pratinjau
                                </button>
                            </div>
                        @endif
                    </div>
                    @endif
                    @if($isSticker)
                        <div style="margin-top:5px; margin-bottom:5px; text-align:{{ $isUser ? 'right' : 'left' }};">
                            <img src="{{ $stickerUrl }}" alt="Stiker" style="width: 120px; height: auto; display: inline-block;">
                        </div>
                    @endif

                    @if(trim($msgText) !== '')
                    <div class="msg-bubble {{ $isUser ? 'from-user' : 'from-admin' }}">
                        <div class="msg-chevron" onclick="showContextMenu(event, {{ $msg->id }}, '{{ addslashes($msgText) }}', '{{ addslashes($senderName) }}', '{{ addslashes($msg->attachment) }}')"><i class="bi bi-chevron-down"></i></div>
                        
                        @if($msg->parent && !$renderedReplyQuote)
                            @php
                                $parentSender = $msg->parent->sender_role === 'user' ? (auth()->check() && $msg->parent->user_id === auth()->id() ? auth()->user()->name : 'User') : 'Admin UPTD';
                                if ($msg->parent->attachment) {
                                    $parentAttachExt2 = strtolower(pathinfo($msg->parent->attachment, PATHINFO_EXTENSION));
                                    $parentAttachName2 = basename($msg->parent->attachment);
                                    $parentIsImage2 = in_array($parentAttachExt2, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                } else {
                                    $parentAttachExt2 = null;
                                    $parentAttachName2 = null;
                                    $parentIsImage2 = false;
                                    $parentPreview2 = Str::limit($msg->parent->message, 60);
                                }
                            @endphp
                            <div class="reply-quote">
                                <div class="reply-quote-name">{{ $parentSender }}</div>
                                @if($parentAttachExt2)
                                    <div class="reply-quote-text" style="display:flex; align-items:center; gap:5px;">
                                        <i class="bi {{ $parentIsImage2 ? 'bi-image' : 'bi-file-earmark-text' }}" style="font-size:0.9rem;"></i>
                                        <span>{{ strlen($parentAttachName2) > 30 ? substr($parentAttachName2, 0, 30) . '...' : $parentAttachName2 }}</span>
                                    </div>
                                @else
                                    <div class="reply-quote-text">{{ $parentPreview2 }}</div>
                                @endif
                            </div>
                        @endif

                        {{ $msgText }}
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="chat-empty-state" id="emptyState">
                <i class="bi bi-chat-square-dots"></i>
                <p>Belum ada pesan.<br>Mulai percakapan dengan tim UPTD Kalibrasi.</p>
            </div>
        @endforelse
    </div>

    {{-- Ref Card --}}
    <div id="userRefCard" style="display: none;">
        <div class="chat-ref-card">
            <div class="chat-ref-card-inner">
                <div class="chat-ref-icon">
                    <i id="userRefIcon" class="bi bi-clipboard2-pulse"></i>
                </div>
                <div class="chat-ref-body">
                    <div class="chat-ref-label" id="userRefLabel">Pesanan Kalibrasi</div>
                    <div class="chat-ref-number" id="userRefNumber"></div>
                    <div class="chat-ref-meta" id="userRefMeta">Pertanyaan terkait pesanan ini</div>
                </div>
                <div class="chat-ref-actions">
                    <button type="button" class="btn-ref-close" onclick="closeRefCard()" title="Batal">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Input --}}
    <!-- Reply Preview Bar -->
    <div id="userReplyBar" class="reply-preview-bar">
        <div class="reply-preview-content">
            <div class="reply-preview-name" id="userReplyName">Balas Admin UPTD</div>
            <div class="reply-preview-text" id="userReplyText"></div>
        </div>
        <button type="button" class="reply-preview-close" onclick="cancelReply()" title="Batal">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- File Preview Area -->
    <div id="userFilePreview" style="display:none; padding:10px 18px; background:var(--chat-bg); border-top:1px solid var(--panel-border);">
        <div style="display:flex; align-items:center; gap:10px; background:var(--card-bg); padding:8px 12px; border-radius:12px; border:1px solid rgba(0,0,0,0.05); max-width:340px;">
            <div id="userFileThumb" style="flex-shrink:0; width:44px; height:44px; border-radius:8px; overflow:hidden; background:rgba(30,148,71,0.1); display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
            </div>
            <div style="flex:1; min-width:0;">
                <div id="userFileName" style="font-size:0.8rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">filename.pdf</div>
                <div id="userFileSize" style="font-size:0.7rem; color:var(--text-muted);">2.5 MB</div>
            </div>
            <button type="button" class="btn-close" style="font-size:0.7rem;" onclick="removeUserFile()"></button>
        </div>
    </div>

    <!-- Sticker Picker Container -->
    <div id="userStickerContainer">
        <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:10px;">Stiker UPTD</div>
        <div class="sticker-grid" id="stickerGrid">
            <!-- Injected via JS -->
        </div>
    </div>

    <!-- Quick Replies Area (FAQ) -->
    <div class="quick-replies-container" id="faqContainer">
        <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:10px;">FAQ (Pilih Pertanyaan)</div>
        <div class="quick-replies-scroll">
            <button type="button" class="quick-reply-chip" onclick="fillQuickReply('Apakah laboratorium memiliki akreditasi resmi sesuai standar ISO/IEC 17025 dari Komite Akreditasi Nasional (KAN)?')">Info Akreditasi KAN</button>
            <button type="button" class="quick-reply-chip" onclick="fillQuickReply('Bagaimana jika saat pemeriksaan ditemukan alat dalam kondisi rusak atau tidak berfungsi?')">Alat Rusak/Tidak Berfungsi</button>
            <button type="button" class="quick-reply-chip" onclick="fillQuickReply('Berapa harga kalibrasi untuk masing-masing jenis alat kesehatan?')">Daftar Harga Kalibrasi</button>
            <button type="button" class="quick-reply-chip" onclick="fillQuickReply('Apakah ada pajak dalam pembayaran jasa kalibrasi')">Apakah ada pajak?</button>
            <button type="button" class="quick-reply-chip" onclick="fillQuickReply('Melalui sistem apa pembayaran biaya kalibrasi dilakukan?')">Sistem Pembayaran</button>
        </div>
    </div>

    <!-- Floating Scroll Buttons -->
    <div id="chatScrollButtons" style="display:none; position:absolute; bottom:165px; right:16px; z-index:100; flex-direction:column; gap:7px;">
        <button type="button" class="scroll-float-btn" id="scrollUpBtn" onclick="scrollToTop()" title="Ke pesan paling atas">
            <i class="bi bi-chevron-up" style="font-size:0.85rem;"></i>
        </button>
        <button type="button" class="scroll-float-btn position-relative" id="scrollDownBtn" onclick="scrollToBottom()" title="Ke pesan terbaru">
            <i class="bi bi-chevron-down" style="font-size:0.85rem;"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" id="newMsgBadge" style="display:none; font-size:0.6rem; padding:0.28em 0.5em; border:1.5px solid var(--panel-bg); box-shadow:0 2px 4px rgba(0,0,0,0.15);">0</span>
        </button>
    </div>

    <div class="chat-input-bar">
        <div class="chat-input-card">
            <!-- Input text row -->
            <div style="display:flex; align-items:flex-start; gap:10px;">
                <input type="text" id="chatInput" class="chat-input-field"
                       placeholder="Ketik pesan..." autocomplete="off">
                <button type="button" id="sendBtn" class="btn-send-msg" aria-label="Kirim">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <!-- Action buttons row -->
            <div class="chat-input-actions">
                <div class="chat-input-left-actions">
                    <button type="button" id="faqBtn" class="btn-action-pill" aria-label="FAQ" onclick="toggleFAQ()">
                        <i class="bi bi-patch-question-fill"></i>
                        <span>FAQ</span>
                    </button>
                    <button type="button" id="stickerBtn" class="btn-action-pill" aria-label="Stiker" onclick="toggleSticker()">
                        <i class="bi bi-sticky-fill"></i>
                        <span>Stiker</span>
                    </button>
                    <label for="chatAttachment" class="btn-action-pill mb-0" aria-label="Lampiran" style="cursor:pointer;">
                        <i class="bi bi-paperclip"></i>
                        <span>Lampiran</span>
                    </label>
                    <input type="file" id="chatAttachment" hidden accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" onchange="previewUserFile()">
                    <span style="font-size:0.62rem; color:var(--text-muted); opacity:0.75; margin-left:4px;" title="File chat biasa akan dihapus otomatis setelah 24 jam"><i class="bi bi-clock-history"></i> File disimpan 1 hari</span>
                </div>
            </div>
        </div>
    </div>

</div> {{-- .user-chat-wrap --}}

{{-- Doc Side Preview Panel --}}
<div class="doc-side-panel" id="docSidePanel">
    <div class="doc-side-panel-resizer" id="sidePanelResizer"></div>
    <div class="doc-side-panel-header">
        <h6 id="sidePanelTitle"><i class="bi bi-file-earmark-text"></i> Pratinjau Dokumen</h6>
        <button class="doc-side-panel-close" onclick="closeSidePanel()" title="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="doc-side-panel-body" id="sidePanelBody">
        <!-- injected via JS -->
    </div>
    <div class="doc-side-panel-download" id="sidePanelDownloadBar">
        <a href="#" id="sidePanelDownloadBtn" download><i class="bi bi-download"></i> Unduh File</a>
    </div>
</div>

</div> {{-- .chat-outer-wrap --}}
</div> {{-- .container --}}

<div id="bubbleContextMenu" class="bubble-context-menu" style="display:none;">
    <button onclick="doReply()"><i class="bi bi-reply"></i> Balas</button>
    <hr>
    <button onclick="doCopy()"><i class="bi bi-clipboard"></i> Salin</button>
    <hr>
    <button onclick="doDelete()" style="color:#ef4444;"><i class="bi bi-trash3" style="color:#ef4444;"></i> Hapus</button>
</div>


@endsection

@push('scripts')

<script>
const userCalibrations = @json($calibrations);

function previewUserFile() {
    const fileInput = document.getElementById('chatAttachment');
    const previewEl = document.getElementById('userFilePreview');
    const nameEl    = document.getElementById('userFileName');
    const sizeEl    = document.getElementById('userFileSize');
    const thumbEl   = document.getElementById('userFileThumb');
    const MAX_ATTACHMENT_SIZE = 20 * 1024 * 1024; // 20MB, harus sama dengan batas server

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];

        if (file.size > MAX_ATTACHMENT_SIZE) {
            alert('Ukuran file terlalu besar (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB). Maksimal 20MB.');
            fileInput.value = '';
            previewEl.style.display = 'none';
            return;
        }

        nameEl.textContent = file.name;
        sizeEl.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        if (file.type.startsWith('image/') && file.type !== 'image/gif') {
            sizeEl.textContent += ' (akan dikompres otomatis)';
        }

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

function removeUserFile() {
    const fileInput = document.getElementById('chatAttachment');
    const previewEl = document.getElementById('userFilePreview');
    fileInput.value = '';
    previewEl.style.display = 'none';
}

function toggleSticker() {
    const container = document.getElementById('userStickerContainer');
    document.getElementById('faqContainer').style.display = 'none'; // hide faq if open
    container.style.display = container.style.display === 'none' ? 'block' : 'none';
}

function toggleFAQ() {
    const container = document.getElementById('faqContainer');
    document.getElementById('userStickerContainer').style.display = 'none'; // hide stickers if open
    container.style.display = container.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    // Populate stickers
    const stickerGrid = document.getElementById('stickerGrid');
    const stickers = [1,2,3,5,6,8,9,10,11,12,13,14,16,17]; // from public/stiker
    
    stickers.forEach(num => {
        const url = `{{ asset('stiker') }}/${num}.png`;
        const div = document.createElement('div');
        div.className = 'sticker-item';
        div.onclick = () => sendSticker(url, `${num}.png`);
        div.innerHTML = `<img src="${url}" alt="Stiker ${num}">`;
        stickerGrid.appendChild(div);
    });

    // Toggle active state for pill buttons
    document.getElementById('faqBtn').addEventListener('click', function() {
        this.classList.toggle('active');
        document.getElementById('stickerBtn').classList.remove('active');
    });
    document.getElementById('stickerBtn').addEventListener('click', function() {
        this.classList.toggle('active');
        document.getElementById('faqBtn').classList.remove('active');
    });

    // Animated placeholder typing effect
    const questions = [
        'Apakah laboratorium memiliki akreditasi KAN?',
        'Bagaimana jika alat saya rusak saat pemeriksaan?',
        'Apakah sertifikat tersedia dalam bentuk digital?',
        'Berapa harga kalibrasi alat kesehatan?',
        'Melalui sistem apa pembayaran dilakukan?',
    ];
    const input = document.getElementById('chatInput');
    let qi = 0, ci = 0, deleting = false, pauseTimer = null;
    function typePlaceholder() {
        if (document.activeElement === input) { setTimeout(typePlaceholder, 300); return; }
        const q = questions[qi];
        if (!deleting) {
            ci++;
            input.placeholder = q.slice(0, ci);
            if (ci === q.length) {
                deleting = true;
                pauseTimer = setTimeout(typePlaceholder, 2000);
                return;
            }
            setTimeout(typePlaceholder, 55);
        } else {
            ci--;
            input.placeholder = q.slice(0, ci);
            if (ci === 0) {
                deleting = false;
                qi = (qi + 1) % questions.length;
                setTimeout(typePlaceholder, 400);
                return;
            }
            setTimeout(typePlaceholder, 28);
        }
    }
    setTimeout(typePlaceholder, 1200);
});

async function sendSticker(url, filename) {
    document.getElementById('userStickerContainer').style.display = 'none';
    const btn = document.getElementById('stickerBtn');
    
    // Instead of uploading file, send a special text code
    const originalInput = document.getElementById('chatInput').value;
    document.getElementById('chatInput').value = `[STICKER:${filename}]`;
    
    try {
        await sendMessage();
    } catch(e) {
        console.error("Gagal mengirim stiker", e);
        alert("Gagal mengirim stiker");
        document.getElementById('chatInput').value = originalInput;
    }
}

// â”€â”€ Context Menu (Right-click / long-press) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
let contextTargetMsg = null; // { id, text, senderName, attachment }

function showContextMenu(e, msgId, msgText, senderName, attachmentUrl) {
    e.preventDefault();
    e.stopPropagation(); // Prevent document click from immediately closing it
    contextTargetMsg = { id: msgId, text: msgText, senderName: senderName, attachment: attachmentUrl };
    const menu = document.getElementById('bubbleContextMenu');
    menu.style.display = 'block';
    // Position near click, keep within viewport
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

// â”€â”€ Reply state â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
let replyToMsgId   = null;

function doReply() {
    if (!contextTargetMsg) return;
    replyToMsgId = contextTargetMsg.id;
    const bar = document.getElementById('userReplyBar');
    document.getElementById('userReplyName').textContent = 'Balas ' + contextTargetMsg.senderName;
    let preview = '';
    const replyTextEl = document.getElementById('userReplyText');
    if (contextTargetMsg.attachment) {
        const ext = contextTargetMsg.attachment.split('.').pop().toLowerCase();
        const isImage = ['png','jpg','jpeg','gif','webp'].includes(ext);
        const fileName = decodeURIComponent(contextTargetMsg.attachment.split('/').pop()).substring(0, 40);
        if (isImage) {
            replyTextEl.innerHTML = `<i class="bi bi-image" style="font-size:0.9rem;"></i> <span>${fileName}</span>`;
        } else {
            replyTextEl.innerHTML = `<i class="bi bi-file-earmark-text" style="font-size:0.9rem;"></i> <span>${fileName}</span>`;
        }
    } else {
        preview = contextTargetMsg.text || '';
        replyTextEl.textContent = preview;
    }
    bar.classList.add('active');
    document.getElementById('chatInput').focus();
}

function cancelReply() {
    replyToMsgId = null;
    document.getElementById('userReplyBar').classList.remove('active');
}

function doCopy() {
    if (!contextTargetMsg) return;
    const text = contextTargetMsg.text || '';
    if (text) navigator.clipboard.writeText(text);
}

async function doDelete() {
    if (!contextTargetMsg) return;
    const msgId = contextTargetMsg.id;

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
        const res = await fetch(`/chat/messages/${msgId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });
        const data = await res.json();
        if (data.success) {
            if (data.action === 'update_text') {
                // Just reload chat to get updated text safely
                if (typeof fetchChat === 'function') {
                    fetchChat();
                } else {
                    location.reload();
                }
            } else {
                // Admin deleted or full remove
                const el = document.querySelector(`.msg-row[data-msg-id="${msgId}"]`);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transform = 'scale(0.9)';
                    el.style.transition = 'all 0.25s ease';
                    setTimeout(() => el.remove(), 260);
                }
            }
        }
    } catch(e) {
        console.error('Gagal menghapus pesan', e);
    }
}

// Single-file guard â€” show warning toast if file already chosen
document.addEventListener('DOMContentLoaded', () => {
    const attachInput = document.getElementById('chatAttachment');
    if (attachInput) {
        attachInput.addEventListener('click', (e) => {
            const previewEl = document.getElementById('userFilePreview');
            if (previewEl.style.display !== 'none') {
                e.preventDefault();
                showFileWarning();
            }
        });
    }
});

function showFileWarning() {
    // Create a toast-style warning
    let existing = document.getElementById('fileWarnToast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.id = 'fileWarnToast';
    toast.style.cssText = 'position:fixed; bottom:100px; left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; padding:12px 22px; border-radius:12px; font-size:0.85rem; font-weight:600; z-index:9999; box-shadow:0 4px 20px rgba(0,0,0,0.3); display:flex; align-items:center; gap:8px; animation:bubbleIn 0.25s ease-out;';
    toast.innerHTML = '<i class="bi bi-exclamation-circle-fill" style="color:#f59e0b;"></i> Hanya bisa 1 file per pesan. Hapus file sebelumnya terlebih dahulu.';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}


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
        const outerWrap = document.getElementById('chatOuterWrap');
        
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
                <div style="height: calc(100vh - 300px); min-height: 400px;">
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
        outerWrap.classList.add('preview-open');
    }

    function closeSidePanel() {
        const outerWrap = document.getElementById('chatOuterWrap');
        outerWrap.classList.remove('preview-open');
        setTimeout(() => {
            const panelBody = document.getElementById('sidePanelBody');
            panelBody.innerHTML = '';
        }, 350);
    }

    // â”€â”€ Resizable panel â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    (function initResizer() {
        const resizer = document.getElementById('sidePanelResizer');
        const panel   = document.getElementById('docSidePanel');
        if (!resizer || !panel) return;
        let startX, startW;
        resizer.addEventListener('mousedown', e => {
            startX = e.clientX;
            startW = panel.getBoundingClientRect().width;
            panel.style.transition = 'none';
            resizer.classList.add('dragging');
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
            e.preventDefault();
        });
        function onMouseMove(e) {
            const dx  = e.clientX - startX; // Change drag direction logic
            const newW = Math.min(Math.max(startW - dx, 280), 700);
            panel.style.width = newW + 'px';
        }
        function onMouseUp() {
            panel.style.transition = '';
            resizer.classList.remove('dragging');
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        }
    })();


document.addEventListener("DOMContentLoaded", function() {
    // Other DOM ready logic can go here if needed
});

const chatBody     = document.getElementById('chatBody');
const chatInput    = document.getElementById('chatInput');
const sendBtn      = document.getElementById('sendBtn');
const emptyState   = document.getElementById('emptyState');
const userName     = @json(auth()->user()->name);
const storeUrl     = "{{ route('user.chat.store') }}";
const pollUrl      = "{{ route('user.chat.messages') }}";
const csrfToken    = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

const urlParams = new URLSearchParams(window.location.search);
const refParam = urlParams.get('ref');
const docParam = urlParams.get('doc');

const refCard = document.getElementById('userRefCard');
const refIcon = document.getElementById('userRefIcon');
const refLabel = document.getElementById('userRefLabel');
const refNumber = document.getElementById('userRefNumber');
const refMeta = document.getElementById('userRefMeta');
let activeRefText = '';

if (refParam) {
    if (docParam) {
        refLabel.textContent = 'Dokumen ' + docParam;
        refIcon.className = 'bi bi-file-earmark-text';
        refMeta.textContent = 'Pesanan ' + refParam;
        activeRefText = `[Dokumen ${docParam} (Pesanan ${refParam})] `;
    } else {
        refLabel.textContent = 'Pesanan Kalibrasi';
        refIcon.className = 'bi bi-clipboard2-pulse';
        refMeta.textContent = 'Pertanyaan terkait pesanan ini';
        activeRefText = `[Pesanan ${refParam}] `;
    }
    refNumber.textContent = refParam;
    refCard.style.display = 'block';
    chatInput.focus();
    
    // Membersihkan URL tanpa reload
    window.history.replaceState({}, document.title, window.location.pathname);
}

function fillQuickReply(text) {
    const chatInput = document.getElementById('chatInput');
    chatInput.value = text;
    chatInput.focus();
    document.getElementById('faqContainer').style.display = 'none';
}

window.closeRefCard = function() {
    refCard.style.display = 'none';
    activeRefText = '';
};


let lastMsgId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};

function scrollToBottom(smooth = true) {
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
}

function scrollToTop(smooth = true) {
    chatBody.scrollTo({ top: 0, behavior: smooth ? 'smooth' : 'instant' });
}

let isScrolledUp = false;
let unreadCount = 0;

chatBody.addEventListener('scroll', () => {
    const scrollButtons = document.getElementById('chatScrollButtons');
    const badge = document.getElementById('newMsgBadge');
    
    // Show buttons if not at the very top or very bottom
    if (chatBody.scrollTop > 100) {
        scrollButtons.style.display = 'flex';
    } else {
        scrollButtons.style.display = 'none';
    }

    // Check if user is scrolled up from the bottom (margin of error 50px)
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

function buildBubble(msg, isNew = false) {
    const isUser = msg.sender_role === 'user';
    const isBot = msg.sender_role === 'bot';
    const row = document.createElement('div');
    row.className = `msg-row ${isUser ? 'msg-row--right' : 'msg-row--left'}${isNew ? ' is-new' : ''}`;
    row.dataset.msgId = msg.id;
    const avatarName = isUser ? encodeURIComponent(userName) : (isBot ? 'Bot' : 'Admin+UPTD');
    const avatarBg   = isUser ? '406768' : (isBot ? '0b5ed7' : '089145'); // Blue for bot
    const senderName = isUser ? userName : (isBot ? 'Asisten Bot' : 'Admin UPTD');
    
    // Reply quote (if this message is a reply to another)
    let replyQuoteHtml = '';
    if (msg.parent) {
        const parentSender = msg.parent.sender_role === 'user' ? userName : 'Admin UPTD';
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

    let attachmentHtml = '';
    
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
        <div class="chat-ref-card-msg" style="display:flex; justify-content:${isUser ? 'flex-end' : 'flex-start'}; padding:4px 0; width:100%;">
            <div style="max-width: 360px; min-width: 200px; background: var(--card-bg); border: 1.5px solid rgba(30,148,71,0.25); border-radius: 16px 16px ${isUser ? '4px 16px' : '16px 4px'}; padding: 12px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.10); position: relative;">
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
                        : (() => { const cId = calibrationIdMap[refNum]; const dUrl = cId ? calibrationsShowBase + '/' + cId : detailUrl; return `<a href="${dUrl}" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';"><i class="bi bi-eye"></i> Detail</a>`; })()
                    }
                </div>
            </div>
        </div>`;
    }

    if (msg.attachment) {
        const ext = msg.attachment.split('.').pop().toLowerCase();
        const isImage = ['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(ext);
        const fileNameParts = msg.attachment.split('/');
        let fileName = fileNameParts[fileNameParts.length - 1];
        if (fileName.length > 30) fileName = fileName.substring(0, 30) + '...';
        
        if (isImage) {
            attachmentHtml = `
            <div class="msg-bubble ${isUser ? 'from-user' : 'from-admin'} p-2" style="margin-bottom: ${msg.message ? '5px' : '0'};">
                <div class="msg-chevron" onclick="showContextMenu(event, ${msg.id}, '${escapedText}', '${senderName}', '${attachUrl}')"><i class="bi bi-chevron-down"></i></div>
                ${replyQuoteHtml}
                <img src="${msg.attachment}" alt="Attachment" style="max-width: 250px; border-radius: 8px; display: block; cursor: pointer;" onclick="previewAttachment('${msg.attachment}', '${ext}')">
            </div>`;
        } else {
            attachmentHtml = `
            <div class="msg-bubble ${isUser ? 'from-user' : 'from-admin'} p-2" style="margin-bottom: ${msg.message ? '5px' : '0'}; min-width: 250px;">
                <div class="msg-chevron" onclick="showContextMenu(event, ${msg.id}, '${escapedText}', '${senderName}', '${attachUrl}')"><i class="bi bi-chevron-down"></i></div>
                ${replyQuoteHtml}
                <div style="display:flex; align-items:center; gap:10px; background:rgba(255,255,255,0.15); padding:10px; border-radius:12px; border:1px solid rgba(255,255,255,0.2);">
                    <div style="width:40px; height:40px; border-radius:8px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.2rem; flex-shrink:0;">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div style="flex:1; min-width:0; line-height:1.2;">
                        <div style="font-size:0.8rem; font-weight:700; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${fileName}">${fileName}</div>
                        <div style="font-size:0.7rem; color:rgba(255,255,255,0.7); text-transform:uppercase; margin-top:2px;">${ext} File</div>
                    </div>
                </div>
                <div style="margin-top:8px; display:flex; gap:8px;">
                    <button type="button" onclick="previewAttachment('${msg.attachment}', '${ext}')" style="flex:1; font-size:0.75rem; font-weight:700; padding:6px; border-radius:8px; border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.9); background:rgba(255,255,255,0.12); display:inline-flex; align-items:center; justify-content:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)';" onmouseout="this.style.background='rgba(255,255,255,0.12)';">
                        <i class="bi bi-eye"></i> Pratinjau
                    </button>
                </div>
            </div>`;
        }
        replyQuoteHtml = ''; // Already included inside attachment bubble
    }

    let textBubble = '';
    if (isSticker) {
        textBubble = `<div style="margin-top:5px; margin-bottom:5px; text-align:${isUser ? 'right' : 'left'};">
            <img src="${stickerUrl}" alt="Stiker" style="width: 120px; height: auto; display: inline-block;">
        </div>`;
    } else if (msgTextForBubble) {
        textBubble = `<div class="msg-bubble ${isUser ? 'from-user' : 'from-admin'}"><div class="msg-chevron" onclick="showContextMenu(event, ${msg.id}, '${escapedText}', '${senderName}', '${attachUrl}')"><i class="bi bi-chevron-down"></i></div>${replyQuoteHtml}${msgTextForBubble}</div>`;
    }

    row.innerHTML = `
        ${refCardHtml}
        <img class="msg-avatar-sm"
             src="https://ui-avatars.com/api/?name=${avatarName}&background=${avatarBg}&color=fff"
             alt="${senderName}">
        <div class="msg-body" oncontextmenu="showContextMenu(event, ${msg.id}, '${escapedText}', '${senderName}', '${attachUrl}')">
            <div class="msg-header-row">
                <span class="msg-sender-name">${senderName}</span>
                <span class="msg-time-label">${msg.time}</span>
            </div>
            ${attachmentHtml}
            ${textBubble}
        </div>`;
    return row;
}

function buildDateSep(dateLabel, dateVal) {
    const div = document.createElement('div');
    div.className = 'chat-date-sep';
    div.dataset.date = dateVal;
    div.innerHTML = `<span>${dateLabel}</span>`;
    return div;
}

function buildRefCardChatBubble(regNumber, docName = null) {
    const wrapper = document.createElement('div');
    wrapper.className = 'chat-ref-card-msg';
    wrapper.style.cssText = 'display:flex; justify-content:flex-end; padding:4px 0; animation:bubbleIn 0.28s ease-out forwards;';
    
    const labelText = docName ? 'DOKUMEN ' + docName : 'PESANAN KALIBRASI';
    const iconClass = docName ? 'bi-file-earmark-text' : 'bi-clipboard2-pulse';
    const btnText   = docName ? 'Pratinjau' : 'Detail';
    const detailUrl = "{{ route('user.calibrations.index') }}";
    const calibrationIdMap = @json($calibrationIdMap ?? []);
    const calibrationsShowBase = "{{ url('proses') }}";

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
                "><i class="bi ${iconClass}"></i></div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.65rem; font-weight:800; color:#1E9447; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px;">${labelText}</div>
                    <div style="font-size:0.8rem; font-weight:700; font-family:monospace; color:var(--text-primary);">${regNumber}</div>
                    <div style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">
                        Pertanyaan terkait hal ini
                    </div>
                </div>
            </div>
            <div style="margin-top:10px; border-top:1px solid var(--card-border); padding-top:8px; display:flex; justify-content:flex-end;">
                ${docName ? 
                `<button type="button" onclick="previewDraftHarga('${regNumber}')" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';">
                    <i class="bi bi-eye"></i> Pratinjau
                </button>` 
                : 
                (() => { const cId = calibrationIdMap[regNumber]; const dUrl = cId ? calibrationsShowBase + '/' + cId : detailUrl; return `<a href="${dUrl}" style="font-size:0.75rem; font-weight:700; padding:5px 14px; border-radius:8px; border:1.5px solid #1E9447; color:#1E9447; background:transparent; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;" onmouseover="this.style.background='#1E9447';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='#1E9447';"><i class="bi bi-eye"></i> Detail</a>`; })()
                }
            </div>
        </div>`;
    return wrapper;
}

// Kompres & resize gambar di browser sebelum upload, biar hemat kuota
// dan gak kena limit ukuran server. Hasil selalu JPEG.
function resizeImage(file, maxDimension = 1600, quality = 0.8) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                let { width, height } = img;

                // Cuma resize kalau memang lebih besar dari batas
                if (width > maxDimension || height > maxDimension) {
                    if (width > height) {
                        height = Math.round(height * (maxDimension / width));
                        width = maxDimension;
                    } else {
                        width = Math.round(width * (maxDimension / height));
                        height = maxDimension;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    if (!blob) return reject(new Error('Gagal memproses gambar.'));
                    const newName = file.name.replace(/\.[^.]+$/, '') + '.jpg';
                    resolve(new File([blob], newName, { type: 'image/jpeg' }));
                }, 'image/jpeg', quality);
            };
            img.onerror = () => reject(new Error('Gagal memuat gambar.'));
            img.src = e.target.result;
        };
        reader.onerror = () => reject(new Error('Gagal membaca file.'));
        reader.readAsDataURL(file);
    });
}

// â”€â”€ Send message via AJAX â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
async function sendMessage() {
    const rawText = chatInput.value.trim();
    const fileInput = document.getElementById('chatAttachment');
    let file = fileInput.files[0];
    
    if (!rawText && !activeRefText && !file) return;

    sendBtn.classList.add('sending');

    // Kompres gambar (kecuali GIF, biar animasinya gak hilang) sebelum upload
    if (file && file.type.startsWith('image/') && file.type !== 'image/gif') {
        try {
            const original = file;
            file = await resizeImage(file, 1600, 0.8);
            console.log(`Gambar dikompres: ${(original.size/1024/1024).toFixed(2)}MB -> ${(file.size/1024/1024).toFixed(2)}MB`);
        } catch (err) {
            console.error('Resize gagal, upload file asli:', err);
            // fallback: tetap pakai file asli kalau resize gagal
        }
    }
    
    const textToSend = activeRefText ? activeRefText + rawText : rawText;

    const formData = new FormData();
    if (textToSend) formData.append('message', textToSend);
    if (file) formData.append('attachment', file);
    if (replyToMsgId) formData.append('parent_id', replyToMsgId);

    // Clear inputs immediately for better UX
    chatInput.value = '';
    fileInput.value = '';
    cancelReply();

    const tempId = 'typing-' + Date.now();
    const typingHtml = `
        <div class="msg-row bot-msg" id="${tempId}">
            <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="Bot" class="msg-avatar">
            <div class="msg-bubble shadow-sm" style="background:#fff; border:1px solid #e2e8f0; border-radius:16px 16px 16px 4px; padding:12px 16px;">
                <div class="typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    `;
    chatBody.insertAdjacentHTML('beforeend', typingHtml);
    scrollToBottom();

    try {
        const res = await fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
        });
        
        const contentType = res.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            sendBtn.classList.remove('sending');
            if (res.status === 413) {
                alert('File terlalu besar untuk dikirim. Maksimal 20MB.');
            } else {
                alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
            }
            return;
        }
        
        const data = await res.json();

        if (data.success) {
            if (emptyState) emptyState.remove();
            
            document.getElementById('userFilePreview').style.display = 'none';

            // If replying to a reference, append the visual card to the thread first
            if (activeRefText && typeof refParam !== 'undefined' && refParam) {
                const cardBubble = buildRefCardChatBubble(refParam, typeof docParam !== 'undefined' ? docParam : null);
                chatBody.appendChild(cardBubble);
            }

            // Clear ref card after successful send
            if (activeRefText) {
                closeRefCard();
            }
            const msg = data.message;
            lastMsgId = Math.max(lastMsgId, msg.id);

            // Date separator if needed
            const allSeps = chatBody.querySelectorAll('.chat-date-sep');
            const lastSep = allSeps.length > 0 ? allSeps[allSeps.length - 1] : null;
            const todayDate = msg.date || new Date().toISOString().slice(0, 10);
            if (!lastSep || lastSep.dataset.date !== todayDate) {
                const existingSep = chatBody.querySelector(`.chat-date-sep[data-date="${todayDate}"]`);
                if (!existingSep) {
                    chatBody.appendChild(buildDateSep('Hari ini', todayDate));
                }
            }

            // Strip the activeRefText prefix visually from the text bubble so it doesn't look duplicated with the card
            if (activeRefText && msg.message && msg.message.startsWith(activeRefText)) {
                msg.message = msg.message.substring(activeRefText.length);
            }

            const bubble = buildBubble(msg, true);
            chatBody.appendChild(bubble);
            scrollToBottom();
        
        } else {
            chatInput.value = rawText;
            console.error('Send failed (server):', data);
            const validationMsg = data.errors ? Object.values(data.errors).flat().join(' ') : null;
            alert('Gagal mengirim: ' + (validationMsg || data.error || data.message || 'Terjadi kesalahan.'))
        }
    
    } catch (e) {
            chatInput.value = rawText; // restore on failure
            console.error('Send error (network/parse):', e);
    } finally {
        sendBtn.classList.remove('sending');
        chatInput.focus();
    }
}

sendBtn.addEventListener('click', sendMessage);
chatInput.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

// â”€â”€ Poll for new messages from admin â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
async function pollMessages() {
    try {
        const res  = await fetch(pollUrl + '?_=' + Date.now(), { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        const allSeps = chatBody.querySelectorAll('.chat-date-sep');
        let lastSepDate = allSeps.length > 0 ? allSeps[allSeps.length - 1].dataset.date : null;
        const todayDate = new Date().toISOString().slice(0, 10);

        let hasNewMessages = false;
        
        data.messages.forEach(msg => {
            if (msg.id <= lastMsgId) return; // already shown
            if (msg.sender_role === 'user') {
                lastMsgId = Math.max(lastMsgId, msg.id);
                return; // we already added our own messages optimistically
            }

            if (emptyState) emptyState.remove();

            // Date separator if needed
            if (msg.date !== lastSepDate) {
                const existingSep = chatBody.querySelector(`.chat-date-sep[data-date="${msg.date}"]`);
                if (!existingSep) {
                    chatBody.appendChild(buildDateSep(msg.date_label, msg.date));
                }
                lastSepDate = msg.date;
            }

            const bubble = buildBubble(msg, true);
            chatBody.appendChild(bubble);
            lastMsgId = Math.max(lastMsgId, msg.id);
            hasNewMessages = true;
        });

        // Update lastMsgId from all messages just to be safe
        if (data.messages.length > 0) {
            const max = Math.max(...data.messages.map(m => m.id));
            lastMsgId = Math.max(lastMsgId, max);
        }

        if (hasNewMessages) {
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

// Initial scroll
scrollToBottom(false);

// Poll every 3 seconds for new admin messages
setInterval(pollMessages, 3000);
</script>
@endpush



