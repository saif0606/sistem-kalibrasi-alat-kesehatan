@extends('layouts.app')

@section('title', 'Lacak Status Kalibrasi — UPTD Balai Pengujian & Kalibrasi')

@push('styles')
<style>
    .cert-notif-close {
    background: none;
    border: none;
    padding: 4px;
    line-height: 1;
    font-size: 1.1rem;
    color: #6b8299;
    cursor: pointer;
    align-self: flex-start;
    transition: color 0.15s;
}
.cert-notif-close:hover { color: #17a45c; }
/* ===========================
   GLASSMORPHISM PORTAL — show page
   =========================== */
.portal-wrap {
    padding: 100px 0 80px;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
    color: #0c2438;
}

.page-bg-gradient {
    padding-top: 25px; padding-bottom: 80px; min-height: 100vh; 
    background:
    radial-gradient(1000px 600px at 8% -10%, rgba(34,192,122,0.20), transparent 60%),
    radial-gradient(900px 700px at 100% 0%, rgba(77,139,255,0.15), transparent 55%),
    radial-gradient(1200px 800px at 50% 120%, rgba(34,192,122,0.10), transparent 60%),
    linear-gradient(180deg, #f3fbf6 0%, #eef6fb 45%, #f6f9fc 100%);
    transition: background 0.3s ease;
}

[data-theme="dark"] .page-bg-gradient {
    background:
    radial-gradient(1000px 600px at 8% -10%, rgba(34,192,122,0.10), transparent 60%),
    radial-gradient(900px 700px at 100% 0%, rgba(77,139,255,0.08), transparent 55%),
    radial-gradient(1200px 800px at 50% 120%, rgba(34,192,122,0.05), transparent 60%),
    linear-gradient(180deg, #0b1120 0%, #0f172a 45%, #1e293b 100%);
}

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

/* Hero / Header */
.portal-hero {
    max-width: 1240px;
    margin: 0 auto;
    padding: 24px 28px 8px;
    position: relative;
    z-index: 1;
}
.back-link {
    color: #7189a0; text-decoration: none; font-size: 0.85rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s;
    margin-bottom: 12px;
}
.back-link:hover { color: #17a45c; }

.portal-hero h1 { font-size: 34px; font-weight: 800; color: #0c2438; margin-bottom: 4px; letter-spacing: -0.5px; }
.reg-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.6); border: 1px solid rgba(15,60,50,0.1);
    padding: 5px 14px; border-radius: 999px; font-size: 0.82rem;
    color: #0c2438; font-weight: 700; font-family: monospace;
}

/* Main container */
.portal-main {
    max-width: 1240px;
    margin: 0 auto;
    padding: 26px 28px 0;
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Glass card base */
.glass-card {
    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(22px) saturate(150%);
    -webkit-backdrop-filter: blur(22px) saturate(150%);
    border: 1px solid rgba(255,255,255,0.65);
    border-radius: 26px;
    box-shadow: 0 8px 32px rgba(15,60,50,0.10);
    padding: 28px;
}

/* ========= TRACKER CARD (Horizontal) ========= */
.tracker-card { padding: 30px 32px 34px; position: relative; }
.tracker-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap; margin-bottom: 26px; }
.tracker-top .t-label { font-size: 13px; color: #7189a0; font-weight: 600; margin-bottom: 6px; }
.tracker-top .t-regno { font-size: 24px; font-weight: 800; color: #0c2438; letter-spacing: 0.5px; font-family: monospace; }

.status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12.5px; font-weight: 700; padding: 7px 14px; border-radius: 999px;
    white-space: nowrap;
}
.status-pill .s-dot { width: 6px; height: 6px; border-radius: 50%; }
.sp-pengajuan   { background: rgba(113,137,160,0.15); color: #3d5468; border: 1px solid rgba(113,137,160,0.25); }
.sp-pengajuan   .s-dot { background: #7189a0; }
.sp-penjadwalan { background: rgba(43,111,240,0.13); color: #2b6ff0; border: 1px solid rgba(43,111,240,0.25); }
.sp-penjadwalan .s-dot { background: #2b6ff0; }
.sp-kalibrasi   { background: rgba(147,51,234,0.12); color: #7c3aed; border: 1px solid rgba(147,51,234,0.20); }
.sp-kalibrasi   .s-dot { background: #7c3aed; }
.sp-pembayaran  { background: rgba(245,165,36,0.15); color: #e08e0b; border: 1px solid rgba(245,165,36,0.30); }
.sp-pembayaran  .s-dot { background: #f5a524; }
.sp-sertifikat  { background: rgba(23,164,92,0.14); color: #0f7a45; border: 1px solid rgba(23,164,92,0.25); }
.sp-sertifikat  .s-dot { background: #17a45c; }
.sp-selesai     { background: rgba(5,150,105,0.14); color: #065f46; border: 1px solid rgba(5,150,105,0.25); }
.sp-selesai     .s-dot { background: #059669; }
.sp-ditolak     { background: rgba(239,68,68,0.14); color: #dc2626; border: 1px solid rgba(239,68,68,0.30); }
.sp-ditolak     .s-dot { background: #ef4444; }
.sp-ditolak-bisa { background: rgba(245,158,11,0.16); color: #b45309; border: 1px solid rgba(245,158,11,0.35); }
.sp-ditolak-bisa .s-dot { background: #f59e0b; }

.t-timestamp { font-size: 12.5px; color: #7189a0; margin-top: 8px; display: flex; align-items: center; gap: 6px; justify-content: flex-end; }

.tracker-desc {
    font-size: 14.5px; color: #3d5468; line-height: 1.6;
    background: rgba(77,139,255,0.08); border: 1px solid rgba(77,139,255,0.18);
    padding: 14px 18px; border-radius: 16px; margin-bottom: 36px;
}
.tracker-desc b { color: #0c2438; }
.tracker-desc.danger { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); }
.tracker-desc.danger b { color: #dc2626; }
.tracker-desc.warning { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.25); }
.tracker-desc.warning b { color: #b45309; }

/* Stage Row styling */
.stages { position: relative; padding: 0 6px; }
.stage-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    align-items: start;
    position: relative;
    z-index: 2;
}
.stage { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 14px; }
.stage-index { font-size: 14px; font-weight: 800; }
.node-wrap { position: relative; width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; }
.node-glow { position: absolute; inset: -14px; border-radius: 50%; filter: blur(16px); opacity: 0.55; }
.node {
    width: 52px; height: 52px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    position: relative; z-index: 2;
    border: 2px solid transparent;
}
.node svg { width: 22px; height: 22px; }

/* EKG sweep animation */
.ekg-sweep {
    stroke-dasharray: 55 145;
    stroke-dashoffset: 0;
    animation: ekgSweep 1.6s linear infinite;
    filter: drop-shadow(0 0 3px rgba(43,111,240,0.45));
}
@keyframes ekgSweep { from{ stroke-dashoffset: 0; } to{ stroke-dashoffset: -200; } }
@media (prefers-reduced-motion: reduce){ .ekg-sweep{ animation:none; stroke-dasharray:none; } }

/* done */
.stage.done .stage-index { color: #0f7a45; }
.stage.done .node-glow   { background: radial-gradient(circle, #22c07a, transparent 70%); }
.stage.done .node        { background: linear-gradient(135deg, #22c07a, #0f7a45); box-shadow: 0 6px 18px rgba(23,164,92,0.35); }
.stage.done .node svg    { stroke: #fff; }

/* current */
.stage.current .stage-index { color: #2b6ff0; }
.stage.current .node-glow   { background: radial-gradient(circle, #4d8bff, transparent 70%); opacity: 0.7; animation: pulseGlow 2.2s ease-in-out infinite; }
.stage.current .node        { background: linear-gradient(135deg, #4d8bff, #2b6ff0); box-shadow: 0 8px 22px rgba(43,111,240,0.4); border-color: rgba(255,255,255,0.7); }
.stage.current .node svg    { stroke: #fff; }

/* upcoming */
.stage.upcoming .stage-index { color: #7189a0; }
.stage.upcoming .node-glow   { background: radial-gradient(circle, #c7d2de, transparent 70%); opacity: 0.4; }
.stage.upcoming .node        { background: rgba(255,255,255,0.9); border: 2px solid #e2e8ee; }
.stage.upcoming .node svg    { stroke: #7189a0; }

/* danger (rejected) state */
.stage.danger .stage-index { color: #dc2626; }
.stage.danger .node-glow   { background: radial-gradient(circle, #ef4444, transparent 70%); opacity: 0.6; }
.stage.danger .node        { background: linear-gradient(135deg, #ef4444, #b91c1c); box-shadow: 0 8px 20px rgba(220,38,38,0.4); }
.stage.danger .node svg    { stroke: #fff; }
.stage.danger .stage-title { color: #dc2626; }
.stage.danger .stage-sub   { color: #dc2626; opacity: 0.75; }
/* warning (ditolak tapi masih bisa diperbaiki) */
.stage.warning .stage-index { color: #b45309; }
.stage.warning .node-glow   { background: radial-gradient(circle, #f59e0b, transparent 70%); opacity: 0.6; }
.stage.warning .node        { background: linear-gradient(135deg, #f59e0b, #b45309); box-shadow: 0 8px 20px rgba(245,158,11,0.4); }
.stage.warning .node svg    { stroke: #fff; }
.stage.warning .stage-title { color: #b45309; }
.stage.warning .stage-sub   { color: #b45309; opacity: 0.75; }

/* Fade out stages after rejection */
.stage.faded { opacity: 0.35; filter: grayscale(0.7); }

.stage-title { font-size: 14.5px; font-weight: 700; color: #0c2438; }
.stage-sub   { font-size: 12px; color: #7189a0; }

/* SVG connector layer */
.connector { position: absolute; left: 0; right: 0; top: 48px; height: 36px; z-index: 1; pointer-events: none; }

@media (max-width: 820px){
    .stage-row { grid-template-columns: repeat(6, 130px); width: max-content; }
    .stages    { overflow-x: auto; padding-bottom: 8px; }
    .connector { width: max-content; }
}

/* ========= DETAIL INFO ROW ========= */
.info-section-title {
    font-size: 1.02rem; font-weight: 800; color: #0c2438;
    border-bottom: 1px solid rgba(15,60,50,0.1); padding-bottom: 12px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}
.detail-row {
    display: flex; justify-content: space-between; padding: 12px 0;
    border-bottom: 1px solid rgba(15,60,50,0.04); font-size: 0.9rem;
}
.detail-row:last-child { border-bottom: none; }
.detail-label { color: #7189a0; font-weight: 600; }
.detail-value { font-weight: 700; color: #0c2438; text-align: right; max-width: 60%; }
.detail-value-mono { font-family: monospace; font-size: 0.95rem; }

/* Highlighted notifications inside left card */
.highlight-box {
    border-radius: 16px; padding: 16px; margin-top: 20px; font-size: 0.86rem; line-height: 1.5;
}
.hb-blue {
    background: rgba(43,111,240,0.07); border: 1px solid rgba(43,111,240,0.18); color: #2b6ff0;
}
.hb-amber {
    background: rgba(245,165,36,0.08); border: 1px solid rgba(245,165,36,0.20); color: #b45309;
}

/* ========= RIGHT COLUMN ACTIONS ========= */
/* Saibara App Card */
.saibara-card {
    border-radius: 20px;
    background: linear-gradient(135deg, #f0fdf8 0%, #e8f5fe 100%);
    border: 1.5px solid rgba(34,192,122,0.3);
    padding: 24px; color: #0c2438; margin-bottom: 20px;
    position: relative; overflow: hidden;
}
.saibara-card::before {
    content: ''; position: absolute; top: -30px; right: -30px;
    width: 120px; height: 120px; border-radius: 50%;
    background: radial-gradient(circle, rgba(34,192,122,0.15) 0%, transparent 70%);
}
.saibara-logo-area { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.saibara-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden; padding: 6px;
}
.saibara-icon img { width: 100%; height: 100%; object-fit: contain; }
.saibara-title { font-weight: 800; font-size: 1.05rem; color: #0c2438; }
.saibara-sub   { font-size: 0.75rem; color: #64748b; }
.saibara-desc  { font-size: 0.78rem; color: #3d5468; margin-bottom: 12px; line-height: 1.5; }
.saibara-desc strong { color: #17a45c; }
.btn-saibara {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #17a45c, #2b6ff0);
    color: #fff; font-weight: 700; font-size: 0.85rem;
    padding: 10px 20px; border-radius: 12px; text-decoration: none;
    transition: all 0.2s; border: none; cursor: pointer; width: 100%;
    justify-content: center; margin-top: 8px;
    box-shadow: 0 4px 14px rgba(23,164,92,0.30);
}
.btn-saibara:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(23,164,92,0.45); color: #fff; }

/* Upload box */
.bukti-upload-card {
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.4);
    border: 2px dashed rgba(15,60,50,0.15);
    padding: 24px; text-align: center; cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.bukti-upload-card:hover { border-color: #17a45c; background: rgba(255,255,255,0.7); }
.bukti-upload-card .upload-icon { font-size: 2.2rem; color: #7189a0; margin-bottom: 10px; }
.bukti-upload-card h6 { font-size: 0.92rem; font-weight: 700; margin-bottom: 4px; color: #0c2438; }
.bukti-upload-card p  { font-size: 0.78rem; color: #7189a0; margin: 0; }

.bukti-submitted {
    border-radius: 16px; background: rgba(34,197,94,0.08);
    border: 1.5px solid #22c55e; padding: 16px;
    display: flex; align-items: center; gap: 12px; margin-bottom: 16px;
}
.bukti-submitted .bi-check-circle-fill { font-size: 1.6rem; color: #22c55e; flex-shrink: 0; }

/* Draft Harga Alert inside Action card */
.draft-action-box {
    background: rgba(34,197,94,0.06); border: 1.5px solid rgba(34,197,94,0.2);
    border-radius: 18px; padding: 20px; margin-bottom: 20px;
}

/* Certificate box - Modernized */
.cert-box-modern {
    background: linear-gradient(135deg, rgba(34,197,94,0.08), rgba(23,164,92,0.02));
    border: 2px solid #22c55e; border-radius: 20px; padding: 24px; color: #0c2438;
    box-shadow: 0 8px 24px rgba(22,163,74,0.08); margin-bottom: 20px;
}
.btn-download-cert {
    background: linear-gradient(135deg, #17a45c, #0f7a45);
    color: #fff; border: none; border-radius: 12px; padding: 12px 24px;
    font-weight: 700; text-decoration: none; display: inline-flex;
    align-items: center; gap: 8px; font-size: 0.9rem; width: 100%;
    justify-content: center; box-shadow: 0 6px 16px rgba(23,164,92,0.25);
    transition: all 0.2s;
}
.btn-download-cert:hover { opacity: 0.95; color: #fff; transform: translateY(-1px); }

/* Global buttons */
.btn-chat-admin {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.6); border: 1.5px solid #2b6ff0;
    color: #2b6ff0; font-weight: 700; font-size: 0.9rem;
    padding: 12px; border-radius: 12px; text-decoration: none;
    transition: all 0.2s; width: 100%; justify-content: center;
}
.btn-chat-admin:hover { background: #2b6ff0; color: #fff; }

/* ===== DARK MODE ===== */
[data-theme="dark"] .glass-card {
    background: rgba(15,30,45,0.72);
    border-color: rgba(255,255,255,0.08);
    box-shadow: 0 8px 32px rgba(0,0,0,0.35);
}
[data-theme="dark"] .portal-hero h1 { color: #f1f5f9; }
[data-theme="dark"] .back-link { color: #94a3b8; }
[data-theme="dark"] .back-link:hover { color: #4ade80; }
[data-theme="dark"] .reg-chip { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.12); color: #e2e8f0; }
[data-theme="dark"] .tracker-top .t-label { color: #64748b; }
[data-theme="dark"] .tracker-top .t-regno { color: #f1f5f9; }
[data-theme="dark"] .t-timestamp { color: #64748b; }
[data-theme="dark"] .tracker-desc { color: #94a3b8; background: rgba(77,139,255,0.08); border-color: rgba(77,139,255,0.15); }
[data-theme="dark"] .tracker-desc b { color: #e2e8f0; }
[data-theme="dark"] .tracker-desc.danger { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.18); }
[data-theme="dark"] .stage-title { color: #e2e8f0; }
[data-theme="dark"] .stage-sub { color: #64748b; }
[data-theme="dark"] .stage.upcoming .node { background: rgba(255,255,255,0.85); border-color: rgba(255,255,255,0.6); }
[data-theme="dark"] .stage.upcoming .node svg { stroke: #475569; }
[data-theme="dark"] .info-section-title { color: #f1f5f9; border-color: rgba(255,255,255,0.08); }
[data-theme="dark"] .detail-label { color: #64748b; }
[data-theme="dark"] .detail-value { color: #e2e8f0; }
[data-theme="dark"] .detail-row { border-color: rgba(255,255,255,0.05); }
[data-theme="dark"] .highlight-box.hb-blue { background: rgba(43,111,240,0.12); border-color: rgba(43,111,240,0.2); color: #93c5fd; }
[data-theme="dark"] .highlight-box.hb-amber { background: rgba(245,165,36,0.10); border-color: rgba(245,165,36,0.2); color: #fbbf24; }
[data-theme="dark"] .bukti-upload-card { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.12); }
[data-theme="dark"] .bukti-submitted { background: rgba(23,164,92,0.12); border-color: rgba(23,164,92,0.25); }
[data-theme="dark"] .draft-action-box { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
[data-theme="dark"] .cert-box-modern { background: rgba(23,164,92,0.08); border-color: rgba(23,164,92,0.2); }
[data-theme="dark"] .btn-chat-admin { background: rgba(43,111,240,0.12); color: #93c5fd; border-color: #2b6ff0; }
[data-theme="dark"] .btn-chat-admin:hover { background: #2b6ff0; color: #fff; }
[data-theme="dark"] .saibara-card { background: linear-gradient(135deg, #0b1a2e 0%, #0f2040 100%); border-color: rgba(34,192,122,0.25); color: #e2e8f0; }
[data-theme="dark"] .saibara-title { color: #e2e8f0; }
[data-theme="dark"] .saibara-sub { color: #64748b; }
[data-theme="dark"] .saibara-desc { color: #94a3b8; }
[data-theme="dark"] .saibara-desc strong { color: #4ade80; }
[data-theme="dark"] .text-muted { color: #64748b !important; }
[data-theme="dark"] .modal-content { background: #0f172a; border-color: rgba(255,255,255,0.1); }
[data-theme="dark"] .modal-header { border-color: rgba(255,255,255,0.1); }
[data-theme="dark"] .modal-footer { border-color: rgba(255,255,255,0.1); }
[data-theme="dark"] .modal-title { color: #f1f5f9; }
[data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
[data-theme="dark"] .preview-container { background: #1e293b !important; border-color: rgba(255,255,255,0.1) !important; }
</style>
@endpush

@section('content')
{{-- Floating blobs --}}
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<div class="page-bg-gradient">

    {{-- Header --}}
    <div class="portal-hero">
        <a href="{{ route('user.calibrations.index') }}" class="back-link mb-2 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengajuan
        </a>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1>Lacak Status Kalibrasi</h1>
                <div class="reg-chip">
                    <i class="bi bi-hash"></i> {{ $calibration->registration_number }}
                </div>
            </div>
        </div>
    </div>

    <main class="portal-main">
    @if($calibration->status === 'Sertifikat' && !$calibration->cert_ready_notif_dismissed_at)
    <div class="glass-card" style="padding:20px 24px; background:linear-gradient(135deg, rgba(23,164,92,0.10), rgba(43,111,240,0.08)); border:1.5px solid rgba(23,164,92,0.3); display:flex; align-items:center; gap:16px; flex-wrap:wrap;" id="certNotifBanner">
    <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg,#17a45c,#0f7a45); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.4rem; flex-shrink:0;">
        <i class="bi bi-patch-check-fill"></i>
    </div>
    <div style="flex:1; min-width:200px;">
        <div style="font-weight:800; color:#0f7a45; font-size:15px;">Sertifikat Kalibrasi Anda Sudah Terbit!</div>
        <div style="font-size:13.5px; color:#3d5468;">Silakan ambil sertifikat fisik Anda di kantor UPTD Balai Pengujian & Kalibrasi.</div>
    </div>
    <button type="button" class="cert-notif-close" onclick="dismissCertNotif({{ $calibration->id }})" aria-label="Tutup notifikasi">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

        {{-- =========================================
             1. TOP SECTION: HORIZONTAL TRACKER CARD
             ========================================= --}}
        @php
            $allStatuses  = ['Pengajuan','Penjadwalan','Kalibrasi','Pembayaran','Sertifikat','Selesai'];
            $isDitolak    = $calibration->status === 'Ditolak';
            $isBisaDiperbaiki = $isDitolak && $calibration->canResubmitDocuments();
            $currentIdx   = array_search($calibration->status, $allStatuses);
            if ($currentIdx === false) $currentIdx = -1;

            // Pill class mapping
            $pillClassMap = [
                'Pengajuan'   => 'sp-pengajuan',
                'Penjadwalan' => 'sp-penjadwalan',
                'Kalibrasi'   => 'sp-kalibrasi',
                'Pembayaran'  => 'sp-pembayaran',
                'Sertifikat'  => 'sp-sertifikat',
                'Selesai'     => 'sp-selesai',
                'Ditolak'     => 'sp-ditolak',
            ];
            $pillClass = $isBisaDiperbaiki ? 'sp-ditolak-bisa' : ($pillClassMap[$calibration->status] ?? 'sp-pengajuan');

            // Stage descriptions
            $stageDescs = [
                'Pengajuan'   => 'Permohonan kalibrasi Anda telah <b>masuk dan sedang diverifikasi</b> oleh tim admin UPTD.',
                'Penjadwalan' => 'Tim teknis kami sedang <b>menjadwalkan kunjungan atau penerimaan alat</b> kesehatan Anda.',
                'Kalibrasi'   => 'Alat kesehatan Anda sedang dalam <b>proses kalibrasi</b> oleh teknisi bersertifikat kami.',
                'Pembayaran'  => 'Proses kalibrasi telah <b>selesai secara teknis</b>. Silakan lakukan pembayaran agar petugas UPTD dapat menerbitkan sertifikat Anda.',
                'Sertifikat'  => 'Pembayaran telah diterima. Sertifikat kalibrasi Anda sedang <b>disiapkan dan akan segera terbit</b>.',
                'Selesai'     => 'Proses kalibrasi <b>telah selesai seluruhnya</b>. Sertifikat fisik dapat diambil di kantor UPTD.',
                'Ditolak'     => 'Pengajuan Anda <b>ditolak</b>. Silakan periksa catatan petugas dan ajukan kembali dengan data yang telah diperbaiki.',
            ];
            $currentDesc = $stageDescs[$calibration->status] ?? '';

            // Stage SVG icons
            $stageIcons = [
                'Pengajuan'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>',
                'Penjadwalan' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'Kalibrasi'   => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
                'Pembayaran'  => '<rect x="2" y="6" width="20" height="12" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>',
                'Sertifikat'  => '<path d="M12 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M8.5 14 7 21l5-3 5 3-1.5-7"/>',
                'Selesai'     => '<circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/>',
            ];
        @endphp

        <div class="glass-card tracker-card" id="trackerCard">
            <div class="tracker-top">
                <div>
                    <div class="t-label">Menampilkan tahapan untuk</div>
                    <div class="t-regno">{{ $calibration->registration_number }}</div>
                </div>
                <div>
                    <span class="status-pill {{ $pillClass }}">
                        <span class="s-dot"></span>
                        <span>{{ $calibration->status }}</span>
                    </span>
                    <div class="t-timestamp">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        {{ $calibration->request_date->format('d F Y') }}
                    </div>
                </div>
            </div>

            <div class="tracker-desc {{ $isDitolak ? ($isBisaDiperbaiki ? 'warning' : 'danger') : '' }}">
                {!! $currentDesc !!}
            </div>

            @php
                $segPaths = [
                    'M 100,20 L160,20 L175,13 L190,20 L195,6 L205,34 L210,20 L225,13 L240,20 L300,20',
                    'M 300,20 L360,20 L375,13 L390,20 L395,6 L405,34 L410,20 L425,13 L440,20 L500,20',
                    'M 500,20 L560,20 L575,13 L590,20 L595,6 L605,34 L610,20 L625,13 L640,20 L700,20',
                    'M 700,20 L760,20 L775,13 L790,20 L795,6 L805,34 L810,20 L825,13 L840,20 L900,20',
                    'M 900,20 L960,20 L975,13 L990,20 L995,6 L1005,34 L1010,20 L1025,13 L1040,20 L1100,20',
                ];
                // Compute the rejected stage index from stored field
                $statusIndexMap = array_flip($allStatuses);
                $rejectedAtStatus = $calibration->rejected_at_status;
                $rejectedAtIdx = ($isDitolak && $rejectedAtStatus && isset($statusIndexMap[$rejectedAtStatus]))
                    ? $statusIndexMap[$rejectedAtStatus]
                    : ($isDitolak ? max(0, $currentIdx) : -1);
            @endphp

            <div class="stages">
                {{-- SVG Connector Layer --}}
                <svg class="connector" width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 1200 40">
                    <g>
                        @foreach($segPaths as $sIdx => $segPath)
                            @php
                                $effCurrentIdx = $currentIdx;
                                if ($currentIdx === 3 && $sIdx === 2) {
                                    $segIsDone = false;
                                    $segIsCurrent = true;
                                } else {
                                    $segIsDone    = !$isDitolak && ($sIdx + 1) < $effCurrentIdx;
                                    $segIsCurrent = !$isDitolak && ($sIdx + 1) === $effCurrentIdx;
                                }
                            @endphp

                            @if($isDitolak && $sIdx < $rejectedAtIdx - 1)
                                <path d="{{ $segPath }}" stroke="#17a45c" stroke-width="3" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @elseif($isDitolak && $sIdx === $rejectedAtIdx - 1)
                                <path d="{{ $segPath }}" stroke="{{ $isBisaDiperbaiki ? '#f59e0b' : '#dc2626' }}" stroke-width="3.4" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @elseif($isDitolak)
                                <path d="{{ $segPath }}" stroke="#d6dee6" stroke-width="2" fill="none" opacity="0.35"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @elseif($segIsDone)
                                <path d="{{ $segPath }}" stroke="#17a45c" stroke-width="3" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @elseif($segIsCurrent)
                                <path class="ekg-base" d="{{ $segPath }}"
                                      stroke="rgba(148,163,184,0.32)" stroke-width="3" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                                <path class="ekg-sweep" pathLength="200" d="{{ $segPath }}"
                                      stroke="#2b6ff0" stroke-width="3.4" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @elseif($calibration->status === 'Selesai')
                                <path d="{{ $segPath }}" stroke="#17a45c" stroke-width="3" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @else
                                <path d="{{ $segPath }}" stroke="#d6dee6" stroke-width="3" fill="none"
                                      stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                            @endif
                        @endforeach
                    </g>
                </svg>

                {{-- Stage nodes --}}
                <div class="stage-row">
                    @foreach($allStatuses as $sIdx => $sName)
                    @php
                        if ($isDitolak) {
                            if ($sIdx < $rejectedAtIdx) $stageCls = 'done';
                            elseif ($sIdx === $rejectedAtIdx) $stageCls = $isBisaDiperbaiki ? 'warning' : 'danger';
                            else $stageCls = 'upcoming faded';
                        } elseif ($calibration->status === 'Pembayaran' && $sIdx === 2) {
                            $stageCls = 'current';
                        } elseif ($sIdx < $currentIdx) {
                            $stageCls = 'done';
                        } elseif ($sIdx === $currentIdx) {
                            $stageCls = 'current';
                        } else {
                            $stageCls = 'upcoming';
                        }
                        $stageIcon = $stageIcons[$sName];
                    @endphp
                    <div class="stage {{ $stageCls }}">
                        <div class="stage-index">{{ $sIdx + 1 }}</div>
                        <div class="node-wrap">
                            <div class="node-glow"></div>
                            <div class="node">
                                @if($stageCls === 'done')
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                @elseif($stageCls === 'danger' || $stageCls === 'warning')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">{!! $stageIcon !!}</svg>
                                @endif
                            </div>
                        </div>
                        <div class="stage-title">
                            @php
                                $stageLabels = ['Pengajuan'=>'Pengajuan','Penjadwalan'=>'Penjadwalan','Kalibrasi'=>'Kalibrasi','Pembayaran'=>'Pembayaran','Sertifikat'=>'Sertifikat','Selesai'=>'Selesai'];
                            @endphp
                            {{ $stageLabels[$sName] }}
                        </div>
                        <div class="stage-sub">
                            @php
                                $stageSubs = ['Pengajuan'=>'Mengisi form data','Penjadwalan'=>'Kesepakatan jadwal','Kalibrasi'=>'Proses teknis','Pembayaran'=>'Konfirmasi biaya','Sertifikat'=>'Dokumen terbit','Selesai'=>'Proses tuntas'];
                            @endphp
                            {{ $stageSubs[$sName] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- =========================================
             2. BOTTOM SECTION: BALANCED 2-COLUMN LAYOUT
             ========================================= --}}
        <div class="row g-4">
            
            {{-- ----- LEFT COLUMN: DETAIL PESANAN & JADWAL ----- --}}
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <h5 class="info-section-title">
                        <i class="bi bi-file-earmark-text text-primary"></i> Detail Pengajuan
                    </h5>

                    <div class="detail-row">
                        <span class="detail-label">No. Pesanan</span>
                        <span class="detail-value detail-value-mono">{{ $calibration->registration_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Alat Kesehatan</span>
                        <span class="detail-value">{{ $calibration->device_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal Pengajuan</span>
                        <span class="detail-value">{{ $calibration->request_date->format('d F Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Pemohon / PIC</span>
                        <span class="detail-value">{{ Auth::user()->name }}</span>
                    </div>
                    @if($calibration->nama_instansi)
                    <div class="detail-row">
                        <span class="detail-label">Nama Instansi</span>
                        <span class="detail-value">{{ $calibration->nama_instansi }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Metode Kalibrasi</span>
                        <span class="detail-value">{{ $calibration->metode_kalibrasi }}</span>
                    </div>

                    {{-- Jadwal Kalibrasi (if scheduled) --}}
                    @if($calibration->tanggal_kalibrasi)
                    <div class="highlight-box hb-blue">
                        <div class="fw-bold mb-1" style="font-size:0.9rem;">
                            <i class="bi bi-calendar-check-fill me-1"></i> Jadwal Kalibrasi Telah Ditetapkan
                        </div>
                        <span style="font-weight:700;">{{ $calibration->tanggal_kalibrasi->format('d F Y') }}</span>
                        @if($calibration->lokasi_kalibrasi)
                        <div><i class="bi bi-geo-alt-fill text-danger me-1"></i> Lokasi: {{ $calibration->lokasi_kalibrasi }}</div>
                        @endif
                        <p class="mb-0 mt-2 small text-muted">Teknisi kami akan memproses alat kesehatan Anda pada tanggal dan tempat tersebut.</p>
                    </div>
                    @endif

                    {{-- Admin Note / Catatan Admin (if exists) --}}
                    {{-- Form Resubmit Dokumen (khusus status Ditolak) --}}
{{-- Form Resubmit Dokumen (khusus ditolak-karena-dokumen & masih dalam batas waktu) --}}
@if($calibration->canResubmitDocuments())
<div class="highlight-box hb-amber mt-3">
    <div class="fw-bold mb-2">
        <i class="bi bi-arrow-repeat me-1"></i> Unggah Ulang Dokumen
    </div>
    <p class="mb-2" style="font-size:0.85rem;">
        Pengajuan Anda ditolak karena dokumen. Nomor pesanan
        <strong>{{ $calibration->registration_number }}</strong> tetap berlaku,
        tapi Anda harus mengunggah dokumen yang benar sebelum:
    </p>
    <p class="mb-3" style="font-size:0.9rem; font-weight:800; color:#b45309;">
        <i class="bi bi-clock-fill me-1"></i>
        {{ $calibration->resubmit_deadline->format('d F Y, H:i') }} WIB
        ({{ $calibration->resubmit_deadline->diffForHumans() }})
    </p>
    <form action="{{ route('user.calibrations.resubmit-dokumen', $calibration) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bukti-upload-card text-center" onclick="document.getElementById('resubmitInput').click()">
            <div class="upload-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
            <h6>Pilih File Daftar Alat (bisa lebih dari satu)</h6>
            <p style="font-size:0.78rem;">Klik untuk memilih file • Maks 10MB per file</p>
            <div id="resubmitFileNames" style="margin-top:8px; font-size:0.82rem; font-weight:700; color:#17a45c;"></div>
        </div>
        <input type="file" id="resubmitInput" name="daftar_alat[]" multiple class="d-none" onchange="handleResubmitFiles(this)">
        @error('daftar_alat')
        <div class="text-danger mt-2" style="font-size:0.82rem;">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-success w-100 mt-3 fw-bold" id="resubmitSubmitBtn" style="display:none; border-radius:12px; padding:10px;">
            <i class="bi bi-send-fill me-1"></i> Kirim Dokumen Baru
        </button>
    </form>
</div>
@elseif($calibration->isResubmitExpired())
<div class="highlight-box mt-3" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color:#dc2626;">
    <div class="fw-bold mb-1">
        <i class="bi bi-x-circle-fill me-1"></i> Batas Waktu Upload Ulang Telah Habis
    </div>
    <p class="mb-2" style="font-size:0.85rem;">
        Nomor pesanan <strong>{{ $calibration->registration_number }}</strong> sudah tidak berlaku
        karena dokumen tidak diunggah ulang dalam 1x24 jam. Silakan ajukan kalibrasi baru.
    </p>
    <a href="{{ route('user.calibrations.create') }}" class="btn btn-danger btn-sm fw-bold" style="border-radius:8px;">
        <i class="bi bi-plus-circle me-1"></i> Ajukan Kalibrasi Baru
    </a>
</div>
@elseif($isDitolak)
<div class="highlight-box mt-3" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color:#dc2626;">
    <div class="fw-bold mb-1">
        <i class="bi bi-x-circle-fill me-1"></i> Pengajuan Ditolak
    </div>
    <p class="mb-2" style="font-size:0.85rem;">
        Pengajuan ini tidak dapat dilanjutkan. Silakan ajukan kalibrasi baru.
    </p>
    <a href="{{ route('user.calibrations.create') }}" class="btn btn-danger btn-sm fw-bold" style="border-radius:8px;">
        <i class="bi bi-plus-circle me-1"></i> Ajukan Kalibrasi Baru
    </a>
</div>
@endif
                    {{-- Pratinjau Dokumen dipindah ke kolom kanan --}}
                </div>
            </div>

            {{-- ----- RIGHT COLUMN: AKSI & DOKUMEN ----- --}}
            <div class="col-lg-6">
                <div class="glass-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="info-section-title">
                            <i class="bi bi-shield-check text-success"></i> Dokumen & Aksi Pembayaran
                        </h5>

                        {{-- DRAFT HARGA / PENAWARAN (if exists) --}}
                        @if($calibration->draft_harga)
                        <div class="draft-action-box">
                            <div class="fw-bold text-success mb-2" style="font-size:0.92rem; display:flex; align-items:center; gap:6px;">
                                <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i> Rincian Biaya / Penawaran Harga
                            </div>
                            <p class="small text-muted mb-3">Admin telah mengunggah rincian biaya kalibrasi. Anda dapat melihat pratinjau rincian biaya di bawah ini.</p>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success flex-grow-1 fw-bold" style="border-radius:8px;" data-bs-toggle="modal" data-bs-target="#draftHargaModal">
                                    <i class="bi bi-eye-fill me-1"></i> Lihat Rincian Biaya
                                </button>
                                <a href="{{ route('user.chat.index', ['ref' => $calibration->registration_number, 'doc' => 'Draft Harga']) }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;" title="Tanya tentang rincian biaya">
                                    <i class="bi bi-reply-fill"></i> Tanya Admin
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- SAIBARA APP CARD & BUKTI UPLOAD (Visible ONLY during Pembayaran stage) --}}
                        @if($calibration->status === 'Pembayaran')
                        <div>
                            {{-- Saibara Box --}}
                            <div class="saibara-card">
                                <div class="saibara-logo-area">
                                    <div class="saibara-icon">
                                        <img src="{{ asset('images/images.jfif') }}" alt="SAIBARA">
                                    </div>
                                    <div>
                                        <div class="saibara-title">Bayar via SAIBARA</div>
                                        <div class="saibara-sub">Sistem Administrasi Bayar UPTD</div>
                                    </div>
                                </div>
                                <p class="saibara-desc">
                                    Lakukan pembayaran biaya kalibrasi melalui aplikasi <strong>SAIBARA</strong>. Silakan unduh di Google Play Store lewat tombol di bawah.
                                </p>
                                <a href="https://play.google.com/store/apps/details?id=com.saibara.id&pcampaignid=web_share"
                                   target="_blank" rel="noopener noreferrer" class="btn-saibara">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Unduh Aplikasi SAIBARA
                                </a>
                            </div>

                            {{-- Bukti Pembayaran Already Submitted --}}
                            @if($calibration->bukti_pembayaran)
                            <div class="bukti-submitted" id="buktiSubmittedBox">
                                <i class="bi bi-check-circle-fill"></i>
                                <div style="flex:1;">
                                    <div style="font-size:0.88rem; font-weight:700; color:#0f7a45;">Bukti Pembayaran Terkirim</div>
                                    <div style="font-size:0.75rem; color:#54708a;">Sedang dalam proses verifikasi</div>
                                </div>
                                <a href="{{ asset('storage/' . $calibration->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-success fw-bold" style="border-radius:8px; white-space:nowrap;">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <form action="{{ route('user.calibrations.bukti-pembayaran.delete', $calibration) }}" method="POST" id="deleteBuktiForm" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger fw-bold" style="border-radius:8px;" onclick="confirmDeleteBukti()" title="Hapus bukti">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endif

                            {{-- Pratinjau PDF Panduan Pembayaran SAIBARA --}}
                            <div class="saibara-guide-preview mt-3">
                                <div class="saibara-guide-preview-head">
                                    <i class="bi bi-file-earmark-pdf-fill" style="color:#e14434;"></i>
                                    Panduan Pembayaran Aplikasi SAIBARA
                                </div>
                                <iframe src="{{ asset('documents/panduan-saibara.pdf') }}" class="saibara-pdf-frame"></iframe>
                                <div class="saibara-pdf-actions">
                                    <a href="{{ asset('documents/panduan-saibara.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-grow-1" style="border-radius:8px; font-size:0.78rem;">
                                        <i class="bi bi-arrows-fullscreen me-1"></i> Buka Layar Penuh
                                    </a>
                                    <a href="{{ asset('documents/panduan-saibara.pdf') }}" download class="btn btn-sm btn-success flex-grow-1" style="border-radius:8px; font-size:0.78rem;">
                                        <i class="bi bi-download me-1"></i> Unduh PDF
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                        @endif

                        {{-- Bagian pembayaran kosong (placeholder icon) telah dihilangkan karena diganti pratinjau --}}
                    </div>

                    {{-- File upload preview (multi-file) dipindahkan ke sini (kolom kanan) --}}
                    @php
                        $alatFiles = [];
                        if ($calibration->daftar_alat) {
                            $decoded = json_decode($calibration->daftar_alat, true);
                            if (is_array($decoded)) {
                                $alatFiles = $decoded;
                            } elseif ($calibration->daftar_alat !== '[]') {
                                $alatFiles = [$calibration->daftar_alat];
                            }
                        }
                    @endphp
                    @if(count($alatFiles) > 0)
                    <div class="mt-4 pt-3 border-top" style="border-color: rgba(15,60,50,0.1) !important;">
                        <h5 class="info-section-title border-0 mb-2">
                            <i class="bi bi-tools text-success"></i> Pratinjau Daftar Alat
                        </h5>

                        @if(count($alatFiles) > 1)
                        <div class="d-flex flex-wrap gap-2 mb-3" id="alatFileTabs">
                            @foreach($alatFiles as $idx => $path)
                            <button type="button"
                                    class="btn btn-sm alat-file-tab {{ $idx === 0 ? 'btn-success' : 'btn-outline-secondary' }}"
                                    style="border-radius:8px; font-size:0.78rem;"
                                    data-idx="{{ $idx }}">
                                <i class="bi bi-file-earmark me-1"></i> File {{ $idx + 1 }}
                            </button>
                            @endforeach
                        </div>
                        @endif

                        @foreach($alatFiles as $idx => $path)
                        @php
                            $alatExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $alatUrl = asset('storage/' . $path);
                        @endphp
                        <div class="alat-file-preview" data-idx="{{ $idx }}" style="{{ $idx === 0 ? '' : 'display:none;' }}">
                            <div class="preview-card border rounded p-2 mt-2" style="background: var(--card-bg); border-color: var(--card-border) !important; height: 350px; display: flex; flex-direction: column;">
                                <div class="d-flex align-items-center justify-content-between mb-2 ps-1 pe-1">
                                    <h6 class="small fw-bold mb-0" style="color: var(--text-secondary);"><i class="bi bi-eye"></i> Pratinjau Dokumen {{ count($alatFiles) > 1 ? '('.($idx+1).'/'.count($alatFiles).')' : '' }}</h6>
                                    @if(count($alatFiles) > 1)
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary alat-nav-btn" data-dir="-1" style="border-radius:8px; padding:2px 8px;" title="Sebelumnya">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary alat-nav-btn" data-dir="1" style="border-radius:8px; padding:2px 8px;" title="Selanjutnya">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 rounded overflow-hidden" style="border: 1px solid var(--card-border); background: var(--input-bg);">
                                    @if(in_array($alatExt, ['png', 'jpg', 'jpeg']))
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <img src="{{ $alatUrl }}" alt="Daftar Alat {{ $idx + 1 }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    @elseif($alatExt === 'pdf')
                                        <embed src="{{ $alatUrl }}" type="application/pdf" width="100%" height="100%" />
                                    @else
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                                            <i class="bi bi-file-earmark-x fs-1 text-warning mb-2"></i>
                                            <span style="font-size: 0.85rem; color: var(--text-muted);">Pratinjau otomatis tidak tersedia untuk format <strong>.{{ $alatExt }}</strong>.<br>Silakan klik tombol unduh di bawah.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ $alatUrl }}" target="_blank" class="btn-saibara" style="margin-top:14px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Lihat Dokumen {{ $idx + 1 }}
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Tanya Admin Chat Button (Always at bottom) --}}
                    <div class="mt-4">
                        <a href="{{ route('user.chat.index', ['ref' => $calibration->registration_number]) }}" class="btn-chat-admin">
                            <i class="bi bi-chat-dots-fill"></i> Tanya Admin via Chat
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </main>
</div>

{{-- Modal Preview Draft Harga --}}
@if($calibration->draft_harga)
<div class="modal fade" id="draftHargaModal" tabindex="-1" aria-labelledby="draftHargaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border:none; overflow:hidden; box-shadow:0 12px 36px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="draftHargaModalLabel">
                    <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i> Draft / Penawaran Harga
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @php
                    $ext = strtolower(pathinfo($calibration->draft_harga, PATHINFO_EXTENSION));
                    $url = asset('storage/' . $calibration->draft_harga);
                @endphp
                @if(in_array($ext, ['png', 'jpg', 'jpeg']))
                    <div class="text-center rounded p-2 preview-container" style="background:#f8fafc; border:1px solid rgba(15,60,50,0.1);">
                        <img src="{{ $url }}" alt="Draft Harga" style="max-width:100%; height:auto; border-radius:12px;">
                    </div>
                @elseif($ext === 'pdf')
                    <div class="ratio ratio-4x3 rounded preview-container" style="background:#f8fafc; border:1px solid rgba(15,60,50,0.1); overflow:hidden;">
                        <embed src="{{ $url }}" type="application/pdf" width="100%" height="100%" />
                    </div>
                @else
                    <div class="alert alert-warning py-3 text-center mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-file-earmark-x fs-3 d-block mb-2 text-warning"></i>
                        Pratinjau tidak tersedia untuk format <strong>.{{ $ext }}</strong>.<br>Silakan unduh dokumen untuk melihat isinya.
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary btn-sm px-3" style="border-radius:8px;" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ Storage::url($calibration->draft_harga) }}" download class="btn btn-success btn-sm px-3" style="border-radius:8px;">
                    <i class="bi bi-download me-1"></i> Unduh File
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Saibara Payment -->
<div class="modal fade" id="saibaraModal" tabindex="-1" aria-labelledby="saibaraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content saibara-modal-content">
            <div class="modal-header border-bottom-0 pb-0 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index:10;"></button>
            </div>
            <div class="modal-body p-0">
                <div class="saibara-split">

                    {{-- KIRI --}}
                    <div class="saibara-left">
                        <div class="mb-2 d-flex justify-content-center">
                            <div style="width:64px; height:64px; border-radius:18px; background:linear-gradient(135deg,#0f172a,#1e3a5f); display:flex; align-items:center; justify-content:center; padding:8px; box-shadow:0 10px 25px rgba(15,23,42,0.3);">
                                <img src="{{ asset('images/images.jfif') }}" alt="SAIBARA" style="width:100%; height:100%; object-fit:cover; border-radius:9px;">
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1 text-center saibara-title">Waktunya Pembayaran!</h4>
                        <p class="mb-3 text-center saibara-desc" style="font-size:0.86rem; line-height:1.6;">
                            Status pesanan Anda adalah <strong style="color:#17a45c;">Pembayaran</strong>.
                            Pembayaran <strong>wajib dilakukan melalui aplikasi SAIBARA</strong>.
                            Belum punya aplikasinya? Silakan unduh menggunakan tombol dibawah ini
                        </p>
                        <div class="saibara-btn-row" style="margin-bottom: 20px;">
                            <a href="https://play.google.com/store/apps/details?id=com.saibara.id&pcampaignid=web_share"
                            target="_blank" rel="noopener noreferrer" class="btn-saibara">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Unduh Aplikasi SAIBARA
                        </a>
                    </div>
                    <div style="font-size:0.78rem; font-weight:700; margin-top: 18px; margin-bottom:8px; color:#0c2438;">
                        <i class="bi bi-signpost-split-fill me-1"></i>Cara Pembayaran Aplikasi SAIBARA
                    </div>
                    <div class="saibara-steps">
                        <div class="saibara-step">
                            <div class="saibara-step-num">1</div>
                            <div class="saibara-step-title">Login / Daftar Akun</div>
                            <div class="saibara-step-desc">Pilih <strong>Perorangan</strong> jika alat milik pribadi. Pilih <strong>Instansi</strong> hanya jika mendaftar atas nama lembaga — pastikan memilih instansi yang benar agar tidak tertukar dengan alat milik instansi lain.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">2</div>
                            <div class="saibara-step-title">Pilih Dinas Kesehatan</div>
                            <div class="saibara-step-desc">Pada Dashboard aplikasi SAIBARA, pilih kategori <strong>Dinas Kesehatan</strong>.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">3</div>
                            <div class="saibara-step-title">Pilih UPT Instalasi Farmasi dan Kalibrasi</div>
                            <div class="saibara-step-desc">Lanjutkan ke unit layanan <strong>UPT Instalasi Farmasi dan Kalibrasi</strong>.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">4</div>
                            <div class="saibara-step-title">Pilih Retribusi Jasa Umum</div>
                            <div class="saibara-step-desc">Pilih jenis layanan <strong>Retribusi Jasa Umum</strong>.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">5</div>
                            <div class="saibara-step-title">Pilih Alat yang Akan Dikalibrasi</div>
                            <div class="saibara-step-desc">Alat lebih dari satu? Gunakan <strong>Tambah ke Keranjang</strong>. Hanya satu alat? Langsung <strong>Checkout</strong>.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">6</div>
                            <div class="saibara-step-title">Cek Keranjang & Checkout</div>
                            <div class="saibara-step-desc">Sesuaikan jumlah alat yang dikalibrasi di halaman Keranjang, lalu klik <strong>Checkout</strong>.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">7</div>
                            <div class="saibara-step-title">Tunggu Validasi UPTD</div>
                            <div class="saibara-step-desc">Petugas UPTD memeriksa dan menyetujui permintaan kalibrasi Anda di aplikasi SAIBARA.</div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">8</div>
                            <div class="saibara-step-title">Lakukan Pembayaran</div>
                            <div class="saibara-step-desc">
                                Setelah disetujui, bayar melalui aplikasi SAIBARA. Sesi pembayaran berlaku:
                                QRIS <strong>5 menit</strong>, Virtual Account <strong>10 menit</strong>,
                                Kode Bayar/Teller Bank Lampung <strong>1 hari</strong>.
                                Jika sesi di aplikasi SAIBARA habis, ajukan ulang pembayaran dari langkah checkout di sana.
                            </div>
                        </div>
                        <div class="saibara-step">
                            <div class="saibara-step-num">9</div>
                            <div class="saibara-step-title">Selesai</div>
                            <div class="saibara-step-desc">Setelah berhasil bayar, unggah bukti pembayarannya pada form di bagian bawah.</div>
                        </div>
                    </div>
                    <div class="saibara-note-box mt-3 mb-4">
                        <strong><i class="bi bi-credit-card me-1"></i>Metode Pembayaran</strong>
                        <div class="mt-1">Teller Bank Lampung, QRIS (semua bank/e-wallet), dan Virtual Account.</div>
                    </div>
                </div>

                    {{-- KANAN: PDF langsung tampil, tanpa tab/klik --}}
                    <div class="saibara-right">
                        <div style="font-size:0.85rem; font-weight:700; margin-bottom:10px;">
                            <i class="bi bi-file-earmark-pdf-fill me-1" style="color:#e14434;"></i>Panduan Lengkap Aplikasi SAIBARA
                        </div>
                        <iframe src="{{ asset('documents/panduan-saibara.pdf') }}" class="saibara-pdf-frame"></iframe>
                        <div class="saibara-pdf-actions">
                            <a href="{{ asset('documents/panduan-saibara.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-grow-1" style="border-radius:8px; font-size:0.78rem;">
                                <i class="bi bi-arrows-fullscreen me-1"></i> Buka Layar Penuh
                            </a>
                            <a href="{{ asset('storage/'.$calibration->daftar_alat) }}" target="_blank" class="btn-saibara" style="margin-top:0; margin-bottom:12px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Perbesar Tampilan
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function handleResubmitFiles(input) {
    const files = input.files;
    if (!files.length) return;
    const namesEl = document.getElementById('resubmitFileNames');
    const submitBtn = document.getElementById('resubmitSubmitBtn');
    const names = Array.from(files).map(f => f.name).join(', ');
    if (namesEl) namesEl.textContent = '📎 ' + names;
    if (submitBtn) submitBtn.style.display = 'block';
}

function handleBuktiFile(input) {
    const file = input.files[0];
    if (!file) return;
    const nameEl = document.getElementById('buktiFileName');
    const submitBtn = document.getElementById('buktiSubmitBtn');
    const dropzone = document.getElementById('buktiDropzone');
    if(nameEl) nameEl.textContent = '📎 ' + file.name;
    if(submitBtn) submitBtn.style.display = 'block';
    if(dropzone) {
        dropzone.style.borderColor = '#17a45c';
        dropzone.style.background = 'rgba(34,197,94,0.06)';
    }
}

// Drag & drop support
const dropzone = document.getElementById('buktiDropzone');
if (dropzone) {
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = '#17a45c';
        dropzone.style.background = 'rgba(34,197,94,0.04)';
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = 'rgba(15,60,50,0.15)';
        dropzone.style.background = 'rgba(255, 255, 255, 0.4)';
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        const input = document.getElementById('buktiInput');
        if(input) {
            input.files = e.dataTransfer.files;
            handleBuktiFile(input);
        }
    });
}

// Logic for Modal File Upload
function handleModalBuktiFile(input) {
    const file = input.files[0];
    if (!file) return;
    const nameEl = document.querySelector('.modal-bukti-filename');
    const submitBtn = document.querySelector('.modal-bukti-submit');
    const modalDropzone = document.querySelector('.modal-bukti-dropzone');
    
    if(nameEl) nameEl.textContent = '📎 ' + file.name;
    if(submitBtn) submitBtn.style.display = 'block';
    if(modalDropzone) {
        modalDropzone.style.borderColor = '#17a45c';
        modalDropzone.style.background = 'rgba(34,197,94,0.06)';
    }
}

const modalDropzone = document.querySelector('.modal-bukti-dropzone');
if (modalDropzone) {
    modalDropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        modalDropzone.style.borderColor = '#17a45c';
        modalDropzone.style.background = 'rgba(34,197,94,0.04)';
    });
    modalDropzone.addEventListener('dragleave', () => {
        modalDropzone.style.borderColor = 'rgba(15,60,50,0.15)';
        modalDropzone.style.background = 'rgba(255, 255, 255, 0.6)';
    });
    modalDropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        const input = document.querySelector('.modal-bukti-input');
        if(input) {
            input.files = e.dataTransfer.files;
            handleModalBuktiFile(input);
        }
    });
}

@if($calibration->status === 'Pembayaran' && !$calibration->bukti_pembayaran)
    document.addEventListener('DOMContentLoaded', function() {
        const saibaraModal = new bootstrap.Modal(document.getElementById('saibaraModal'));
        saibaraModal.show();
    });
@endif

function scrollToBukti() {
    const el = document.getElementById('buktiFormSection') || document.getElementById('buktiDropzone');
    if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
}


/* ==========================================
   Navigasi pratinjau Daftar Alat (multi-file)
========================================== */
(function () {
    const totalAlatFiles = document.querySelectorAll('.alat-file-preview').length;
    if (totalAlatFiles === 0) return;

    function showAlatFile(idx) {
        if (idx < 0) idx = totalAlatFiles - 1;
        if (idx >= totalAlatFiles) idx = 0;
        const idxStr = String(idx);

        document.querySelectorAll('.alat-file-tab').forEach(t => {
            const isActive = t.dataset.idx === idxStr;
            t.classList.toggle('btn-success', isActive);
            t.classList.toggle('btn-outline-secondary', !isActive);
        });

        document.querySelectorAll('.alat-file-preview').forEach(p => {
            p.style.display = p.dataset.idx === idxStr ? 'block' : 'none';
        });
    }

    document.addEventListener('click', function (e) {
        const tab = e.target.closest('.alat-file-tab');
        if (tab) {
            showAlatFile(parseInt(tab.dataset.idx, 10));
            return;
        }

        const navBtn = e.target.closest('.alat-nav-btn');
        if (navBtn) {
            let currentIdx = 0;
            document.querySelectorAll('.alat-file-preview').forEach(p => {
                if (p.style.display !== 'none') currentIdx = parseInt(p.dataset.idx, 10);
            });
            const dir = parseInt(navBtn.dataset.dir, 10);
            showAlatFile(currentIdx + dir);
        }
    });
})();
function dismissCertNotif(calibrationId) {
    fetch(`/proses/${calibrationId}/dismiss-cert-notif`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
    }).then(() => {
        document.getElementById('certNotifBanner').style.display = 'none';
    });
}
</script>
@endpush

