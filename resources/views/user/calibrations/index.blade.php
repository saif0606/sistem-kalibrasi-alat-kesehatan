@extends('layouts.app')

@section('title', 'Portal Proses Kalibrasi — UPTD Balai Pengujian & Kalibrasi')

@push('styles')
<style>
/* ===========================
   GLASSMORPHISM PORTAL — sama persis mockup
   =========================== */
.portal-wrap {
    padding: 100px 0 80px;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
    color: #0c2438;
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

/* Hero section */
.back-link {
    color: #7189a0; text-decoration: none; font-size: 0.85rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s;
    margin-bottom: 12px;
}
.back-link:hover { color: #17a45c; }

.portal-hero {
    max-width: 1240px;
    margin: 0 auto;
    padding: 24px 28px 8px;
    position: relative;
    z-index: 1;
}
.eyebrow-tag {
    display: inline-flex; align-items: center; gap: 6px;
    color: #0f7a45; font-weight: 700; font-size: 13px;
    background: rgba(34,192,122,0.12); padding: 5px 12px; border-radius: 999px;
    margin-bottom: 14px;
}
.hero-row { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; flex-wrap: wrap; }
.portal-hero h1 { font-size: 34px; font-weight: 800; color: #0c2438; margin-bottom: 8px; letter-spacing: -0.5px; }
.portal-hero p  { color: #3d5468; font-size: 15px; max-width: 520px; }

.btn-ajukan-new {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #17a45c, #2b6ff0);
    color: #fff; font-weight: 700; font-size: 14.5px;
    padding: 13px 24px; border-radius: 999px; border: none; cursor: pointer;
    box-shadow: 0 12px 28px rgba(23,164,92,0.30);
    transition: transform .2s ease, box-shadow .2s ease;
    text-decoration: none; white-space: nowrap;
}
.btn-ajukan-new:hover { transform: translateY(-2px); box-shadow: 0 16px 34px rgba(23,164,92,0.38); color: #fff; }

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
}

/* ========= TRACKER CARD ========= */
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

/* ========= STAGE TRACKER ========= */
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
@keyframes pulseGlow { 0%,100%{ transform:scale(1); opacity:0.65;} 50%{ transform:scale(1.18); opacity:0.9;} }
@media (prefers-reduced-motion: reduce){ .stage.current .node-glow{ animation:none; } }

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

/* ========= TABLE CARD ========= */
.table-card { padding: 28px 32px 32px; }
.table-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
.table-head h2  { font-size: 18px; font-weight: 800; color: #0c2438; }
.table-head .t-count { font-size: 13px; color: #7189a0; font-weight: 600; }

.filter-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 22px; }
.f-chip {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid rgba(15,60,50,0.14); background: rgba(255,255,255,0.55);
    padding: 7px 15px; border-radius: 999px; font-size: 12.5px; font-weight: 700; color: #3d5468;
    cursor: pointer; transition: all .15s ease; white-space: nowrap;
    text-decoration: none;
}
.f-chip:hover { background: rgba(255,255,255,0.9); color: #0c2438; }
.f-chip .f-n { font-size: 11px; font-weight: 700; opacity: 0.65; }
.f-chip.active {
    background: linear-gradient(135deg, #17a45c, #2b6ff0);
    color: #fff; border-color: transparent; box-shadow: 0 6px 16px rgba(23,164,92,0.28);
}
.f-chip.active .f-n { opacity: 0.85; }

.table-scroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; min-width: 640px; }
thead th {
    text-align: left; font-size: 11.5px; letter-spacing: 0.04em; text-transform: uppercase;
    color: #7189a0; font-weight: 700; padding: 0 14px 12px; border-bottom: 1px solid rgba(15,60,50,0.10);
    background: transparent !important;
}
tbody td {
    padding: 16px 14px; font-size: 14px; color: #0c2438; border-bottom: 1px solid rgba(15,60,50,0.06);
    vertical-align: middle; background: transparent !important;
}
tbody tr:last-child td { border-bottom: none; }
.reg-no-cell { font-weight: 700; font-family: 'Plus Jakarta Sans', monospace; }
.alat-sub    { font-size: 12.5px; color: #7189a0; }

/* Status pills in table */
.pill { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 999px; }
.pill.green  { background: rgba(23,164,92,0.14);  color: #0f7a45; }
.pill.amber  { background: rgba(245,165,36,0.15); color: #e08e0b; }
.pill.blue   { background: rgba(43,111,240,0.13); color: #2b6ff0; }
.pill.gray   { background: rgba(113,137,160,0.14); color: #3d5468; }
.pill.purple { background: rgba(147,51,234,0.12); color: #7c3aed; }
.pill.red    { background: rgba(239,68,68,0.12); color: #dc2626; }
.pill.orange { background: rgba(245,158,11,0.14); color: #b45309; }

/* Ghost action button */
.btn-ghost {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid rgba(15,60,50,0.15); background: rgba(255,255,255,0.6);
    padding: 7px 14px; border-radius: 999px; font-size: 12.5px; font-weight: 700; color: #0c2438;
    cursor: pointer; transition: background .15s ease; text-decoration: none;
}
.btn-ghost:hover { background: rgba(255,255,255,0.95); color: #0c2438; }
.btn-ghost.red-ghost { border-color: rgba(239,68,68,0.3); color: #dc2626; }
.btn-ghost.red-ghost:hover { background: rgba(239,68,68,0.08); }

.row-hidden { display: none !important; }
.empty-state-row { display: none; text-align: center; padding: 40px 20px; color: #7189a0; font-size: 14px; }
.empty-state-row.show { display: block; }

/* Alert override */
.alert-wrap { max-width: 1240px; margin: 0 auto; padding: 0 28px 12px; position: relative; z-index: 1; }

@media (max-width: 600px){
    .portal-hero, .portal-main { padding-left: 16px; padding-right: 16px; }
    .portal-hero h1 { font-size: 26px; }
    .tracker-card, .table-card { padding: 22px 18px 26px; }
}

/* ===== DARK MODE ===== */
[data-theme="dark"] .glass-card {
    background: rgba(12,24,38,0.72);
    border-color: rgba(255,255,255,0.07);
    box-shadow: 0 8px 32px rgba(0,0,0,0.40);
}
[data-theme="dark"] .portal-hero h1 { color: #f1f5f9; }
[data-theme="dark"] .portal-hero p { color: #94a3b8; }
[data-theme="dark"] .back-link { color: #64748b; }
[data-theme="dark"] .back-link:hover { color: #4ade80; }
[data-theme="dark"] .eyebrow-tag { background: rgba(34,192,122,0.10); color: #4ade80; }
[data-theme="dark"] .tracker-top .t-label { color: #475569; }
[data-theme="dark"] .tracker-top .t-regno { color: #f1f5f9; }
[data-theme="dark"] .t-timestamp { color: #475569; }
[data-theme="dark"] .tracker-desc { color: #94a3b8; background: rgba(77,139,255,0.07); border-color: rgba(77,139,255,0.13); }
[data-theme="dark"] .tracker-desc b { color: #e2e8f0; }
[data-theme="dark"] .tracker-desc.danger { background: rgba(239,68,68,0.07); border-color: rgba(239,68,68,0.16); }
[data-theme="dark"] .stage-title { color: #e2e8f0; }
[data-theme="dark"] .stage-sub { color: #475569; }
[data-theme="dark"] .stage-index { color: #64748b; }
[data-theme="dark"] .stage.upcoming .node { background: rgba(255,255,255,0.85); border-color: rgba(255,255,255,0.6); }
[data-theme="dark"] .stage.upcoming .node svg { stroke: #334155; }
[data-theme="dark"] .stage.done .stage-index { color: #4ade80; }
[data-theme="dark"] .stage.current .stage-index { color: #60a5fa; }
[data-theme="dark"] .table-head h2 { color: #f1f5f9; }
[data-theme="dark"] .table-head .t-count { color: #64748b; }
[data-theme="dark"] thead th { color: #475569; border-color: rgba(255,255,255,0.06); }
[data-theme="dark"] tbody td { color: #e2e8f0; border-color: rgba(255,255,255,0.04); }
[data-theme="dark"] .alat-sub { color: #64748b; }
[data-theme="dark"] .reg-no-cell { color: #f1f5f9; }
[data-theme="dark"] .f-chip { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.10); color: #94a3b8; }
[data-theme="dark"] .f-chip:hover { background: rgba(255,255,255,0.12); color: #f1f5f9; }
[data-theme="dark"] .btn-ghost { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.10); color: #e2e8f0; }
[data-theme="dark"] .btn-ghost:hover { background: rgba(255,255,255,0.12); color: #fff; }
[data-theme="dark"] .btn-ghost.red-ghost { color: #f87171; border-color: rgba(248,113,113,0.25); }
[data-theme="dark"] .empty-state-row { color: #475569; }

/* Dark mode page background */
[data-theme="dark"] .portal-page-wrap {
    background:
        radial-gradient(1000px 600px at 8% -10%, rgba(34,192,122,0.07), transparent 60%),
        radial-gradient(900px 700px at 100% 0%, rgba(77,139,255,0.07), transparent 55%),
        radial-gradient(1200px 800px at 50% 120%, rgba(34,192,122,0.05), transparent 60%),
        linear-gradient(180deg, #0b1321 0%, #0d1929 45%, #0b1321 100%) !important;
}
</style>
@endpush

@section('content')
{{-- Floating blobs --}}
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<div class="portal-page-wrap" style="padding-top: 25px; padding-bottom: 80px; min-height: 100vh; background:
    radial-gradient(1000px 600px at 8% -10%, rgba(34,192,122,0.20), transparent 60%),
    radial-gradient(900px 700px at 100% 0%, rgba(77,139,255,0.15), transparent 55%),
    radial-gradient(1200px 800px at 50% 120%, rgba(34,192,122,0.10), transparent 60%),
    linear-gradient(180deg, #f3fbf6 0%, #eef6fb 45%, #f6f9fc 100%);">

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert-wrap">
        <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center gap-2" style="font-size:0.88rem; background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.25) !important; color:#065f46;">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- Hero --}}
    <section class="portal-hero">
        <a href="{{ route('home') }}" class="back-link mb-2 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
        <div class="eyebrow-tag">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            Area Member
        </div>
        <div class="hero-row">
            <div>
                <h1>Portal Proses Kalibrasi</h1>
                <p>Pantau dan ajukan kalibrasi alat kesehatan Anda secara online.</p>
            </div>
            <a href="{{ route('user.calibrations.create') }}" class="btn-ajukan-new">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Ajukan Kalibrasi
            </a>
        </div>
    </section>

    <main class="portal-main">

        {{-- ====== TRACKER CARD ====== --}}
        @php
            $allStatuses  = ['Pengajuan','Penjadwalan','Kalibrasi','Pembayaran','Sertifikat','Selesai'];
            $latestCal    = $calibrations->first();
            $isDitolak    = $latestCal && $latestCal->status === 'Ditolak';
            $isBisaDiperbaiki = $isDitolak && $latestCal->canResubmitDocuments();
            $currentIdx   = $latestCal ? array_search($latestCal->status, $allStatuses) : -1;
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
            $pillClass = $isBisaDiperbaiki ? 'sp-ditolak-bisa' : ($latestCal ? ($pillClassMap[$latestCal->status] ?? 'sp-pengajuan') : 'sp-pengajuan');

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

            $currentDesc = $latestCal ? ($stageDescs[$latestCal->status] ?? '') : 'Belum ada pengajuan aktif.';

            // Stage SVG icons
            $stageIcons = [
                'Pengajuan'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>',
                'Penjadwalan' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'Kalibrasi'   => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
                'Pembayaran'  => '<rect x="2" y="6" width="20" height="12" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>',
                'Sertifikat'  => '<path d="M12 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M8.5 14 7 21l5-3 5 3-1.5-7"/>',
                'Selesai'     => '<circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/>',
            ];

            // Determine the rejection step index (for Ditolak, we reject at Pengajuan by default)
            $rejectedAtIdx = 0; // always reject at Pengajuan from user perspective
        @endphp

        <div class="glass-card tracker-card" id="trackerCard">
            <div class="tracker-top">
                <div>
                    <div class="t-label">Menampilkan tahapan untuk</div>
                    <div class="t-regno">{{ $latestCal ? $latestCal->registration_number : '—' }}</div>
                </div>
                <div>
                    @if($latestCal)
                    <span class="status-pill {{ $pillClass }}">
                        <span class="s-dot"></span>
                        <span>{{ $latestCal->status }}</span>
                    </span>
                    <div class="t-timestamp">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        {{ $latestCal->request_date ? $latestCal->request_date->format('d F Y') : '-' }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="tracker-desc {{ $isDitolak ? ($isBisaDiperbaiki ? 'warning' : 'danger') : '' }}">
                {!! $currentDesc !!}
            </div>

            {{-- ====== 6-STEP STAGE ROW ====== --}}
            @php
                $segPaths = [
                    'M 100,20 L160,20 L175,13 L190,20 L195,6 L205,34 L210,20 L225,13 L240,20 L300,20',
                    'M 300,20 L360,20 L375,13 L390,20 L395,6 L405,34 L410,20 L425,13 L440,20 L500,20',
                    'M 500,20 L560,20 L575,13 L590,20 L595,6 L605,34 L610,20 L625,13 L640,20 L700,20',
                    'M 700,20 L760,20 L775,13 L790,20 L795,6 L805,34 L810,20 L825,13 L840,20 L900,20',
                    'M 900,20 L960,20 L975,13 L990,20 L995,6 L1005,34 L1010,20 L1025,13 L1040,20 L1100,20',
                ];
                // Compute rejected stage index
                $statusIndexMap = array_flip($allStatuses);
                $rejectedAtStatus = $latestCal ? $latestCal->rejected_at_status : null;
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
                            @elseif($latestCal && $latestCal->status === 'Selesai')
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
                        } elseif (!$latestCal) {
                            $stageCls = 'upcoming';
                        } elseif ($latestCal->status === 'Pembayaran' && $sIdx === 2) {
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
                                @elseif($stageCls === 'danger')
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
        </div>{{-- /tracker-card --}}

        {{-- ====== TABLE CARD ====== --}}
        @php
            $statusCounts = $calibrations->getCollection()->groupBy('status')->map->count();
            $totalCount   = $calibrations->total();
        @endphp
        <div class="glass-card table-card">
            <div class="table-head">
                <h2>Daftar Pengajuan Saya</h2>
                <div class="t-count" id="rowCountEl">Total: {{ $totalCount }} pengajuan</div>
            </div>

            <div class="filter-row" id="filterRow">
                <button class="f-chip active" data-filter="semua">Semua <span class="f-n">{{ $totalCount }}</span></button>
                @foreach(['Pengajuan','Penjadwalan','Kalibrasi','Pembayaran','Sertifikat','Selesai','Ditolak'] as $f)
                @php $cnt = $statusCounts[$f] ?? 0; @endphp
                @if($cnt > 0 || true)
                <button class="f-chip" data-filter="{{ strtolower($f) }}">{{ $f }} <span class="f-n">{{ $cnt }}</span></button>
                @endif
                @endforeach
            </div>

            @if($calibrations->isEmpty())
            <div style="text-align:center; padding:60px 20px; color:#7189a0;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#7189a0" stroke-width="1.5" style="margin-bottom:14px; opacity:0.5;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <div style="font-size:15px; font-weight:700; margin-bottom:6px;">Belum ada pengajuan</div>
                <p style="font-size:14px; margin-bottom:20px;">Anda belum pernah mengajukan kalibrasi. Mulai sekarang!</p>
                <a href="{{ route('user.calibrations.create') }}" class="btn-ajukan-new" style="display:inline-flex;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Buat Pengajuan Pertama
                </a>
            </div>
            @else
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Alat Kesehatan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($calibrations as $item)
                        @php
                            $rowStatus = strtolower($item->status);
                            $pillColorClass = match($item->status) {
                                'Pengajuan'   => 'gray','Penjadwalan' => 'blue',
                                'Kalibrasi'   => 'purple',
                                'Pembayaran'  => 'amber',
                                'Sertifikat'  => 'amber',
                                'Selesai'     => 'green',
                                'Ditolak'     => $item->canResubmitDocuments() ? 'orange' : 'red',
                                default       => 'gray',
                            };
                        @endphp
                        <tr data-status="{{ $rowStatus }}">
                            <td><div class="reg-no-cell">{{ $item->registration_number }}</div></td>
                            <td>{{ $item->device_name }}</td>
                            <td>{{ $item->request_date ? $item->request_date->format('d M Y') : '-' }}</td>
                            <td><span class="pill {{ $pillColorClass }}">{{ $item->status }}</span></td>
                            <td>
                                <a href="{{ route('user.calibrations.show', $item->id) }}"
                                   class="btn-ghost {{ $item->status === 'Ditolak' ? 'red-ghost' : '' }}">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lacak
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="empty-state-row" id="emptyStateRow">Tidak ada pengajuan dengan status ini.</div>
            </div>
            <div class="mt-3 px-2">{{ $calibrations->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>

    </main>
</div>

@if($calibrations->where('status', 'Pembayaran')->count() > 0)
@php
    $calToPay = $calibrations->where('status', 'Pembayaran')->whereNull('bukti_pembayaran')->first();
@endphp
@if($calToPay)
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
                            Belum punya aplikasinya? Silakan unduh menggunakan tombol dibawah ini.
                        </p>

                        <div class="saibara-btn-row" style="margin-bottom: 20px;">
                            <a href="https://play.google.com/store/apps/details?id=com.saibara.id&pcampaignid=web_share"
                               target="_blank" rel="noopener noreferrer" class="btn-saibara">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Unduh Aplikasi SAIBARA
                            </a>
                        </div>

                        <div style="font-size:0.78rem; font-weight:700; margin-top:18px; margin-bottom:8px; color:#0c2438;">
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
                                <div class="Bstep-title">Selesai</div>
                                <div class="saibara-step-desc">Setelah berhasil melakukan pembayaran pada SAIBARA, silakan menunggu notifikasi untuk mengambil sertifikat.</div>
                            </div>
                        </div>
                        <div class="saibara-note-box mt-3 mb-4">
                            <strong><i class="bi bi-credit-card me-1"></i>Metode Pembayaran</strong>
                            <div class="mt-1">Teller Bank Lampung, QRIS (semua bank/e-wallet), dan Virtual Account.</div>
                        </div>
                    </div>

                    {{-- KANAN: PDF langsung tampil --}}
                    <div class="saibara-right">
                        <div style="font-size:0.85rem; font-weight:700; margin-bottom:10px;">
                            <i class="bi bi-file-earmark-pdf-fill me-1" style="color:#e14434;"></i>Panduan Lengkap Aplikasi SAIBARA
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
            </div>
        </div>
    </div>
</div>
@endif
@endif

@endsection

@push('scripts')
<script>
(function(){
    const chips      = document.querySelectorAll('.f-chip');
    const rows       = document.querySelectorAll('#tableBody tr');
    const countEl    = document.getElementById('rowCountEl');
    const emptyRow   = document.getElementById('emptyStateRow');
    if (!chips.length) return;

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            const filter = chip.dataset.filter;
            let visible = 0;
            rows.forEach(row => {
                const match = filter === 'semua' || row.dataset.status === filter;
                row.classList.toggle('row-hidden', !match);
                if (match) visible++;
            });
            if (countEl) countEl.textContent = `Total: ${visible} pengajuan`;
            if (emptyRow) emptyRow.classList.toggle('show', visible === 0);
        });
    });
})();

@if($calibrations->where('status', 'Pembayaran')->whereNull('bukti_pembayaran')->count() > 0)
    document.addEventListener('DOMContentLoaded', function() {
        const saibaraModal = new bootstrap.Modal(document.getElementById('saibaraModal'));
        saibaraModal.show();
    });

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
@endif
</script>
@endpush