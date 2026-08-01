<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - UPTD Kalibrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           CSS VARIABLES
        ============================================================ */
        :root {
            --blue-900:  #0a2540;
            --blue-800:  #0d3460;
            --blue-700:  #094a73;
            --blue-600:  #0f6ea8;
            --blue-500:  #1a8fc5;
            --blue-400:  #38b6e8;

            --green-700: #157538;
            --green-600: #1E9447;
            --green-500: #26b857;
            --green-400: #4dd97a;

            --sidebar-w:         260px;
            --sidebar-w-mini:    70px;

            --topbar-bg:         rgba(255,255,255,0.55);
            --content-bg:        #eef2f7;
            --card-bg:           rgba(255,255,255,0.55);
            --card-border:       rgba(255,255,255,0.6);
            --card-blur:         18px;
            --text-primary:      #0f172a;
            --text-secondary:    #475569;
            --text-muted:        #94a3b8;
            --shadow-soft:       0 4px 24px rgba(15,23,42,0.06);
            --shadow-hover:      0 10px 34px rgba(15,23,42,0.12);
        }

        [data-theme="dark"] {
            --topbar-bg:    rgba(8,20,38,0.55);
            --content-bg:   #060f1c;
            --card-bg:      rgba(20,35,58,0.55);
            --card-border:  rgba(255,255,255,0.08);
            --text-primary: #e7edf5;
            --text-secondary:#a3b1c6;
            --text-muted:   #6b7c93;
            --shadow-soft:  0 4px 24px rgba(0,0,0,0.35);
            --shadow-hover: 0 10px 34px rgba(0,0,0,0.5);
        }

        /* ============================================================
           BASE
        ============================================================ */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        /* Modern Select Option styling for both light and dark mode */
        select option,
        .form-select option {
            background-color: #ffffff;
            color: #0f172a;
        }
        [data-theme="dark"] select option,
        [data-theme="dark"] .form-select option {
            background-color: #0d1e36 !important;
            color: #e7edf5 !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background-color 0.3s, color 0.3s;
            position: relative;
        }
        /* Blob gradasi dekoratif di belakang konten agar efek kaca (glassmorphism) terlihat */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.35;
        }
        body::before {
            width: 480px; height: 480px;
            top: -120px; right: -100px;
            background: radial-gradient(circle, var(--green-500), transparent 70%);
        }
        body::after {
            width: 520px; height: 520px;
            bottom: -160px; right: 30%;
            background: radial-gradient(circle, var(--blue-500), transparent 70%);
        }
        [data-theme="dark"] body::before,
        [data-theme="dark"] body::after { opacity: 0.18; }

        /* ============================================================
           MODERN SELECT (pengganti <select> bawaan yang kuno)
        ============================================================ */
        .modern-select { position: relative; width: 100%; }

        .modern-select-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--card-bg);
            border: 1.5px solid var(--card-border);
            border-radius: 12px;
            padding: 0.55rem 0.9rem;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s;
        }
        .modern-select-trigger:hover,
        .modern-select-trigger[aria-expanded="true"] {
            border-color: var(--green-600);
            box-shadow: 0 0 0 3px rgba(30,148,71,0.1);
        }
        .modern-select-trigger-icon { color: var(--green-600); font-size: 1rem; }
        .modern-select-trigger-label { flex: 1; text-align: left; }

        .modern-select-menu {
            padding: 10px;
            border-radius: 16px;
            min-width: 100%;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-hover);
            background: var(--card-bg);
            backdrop-filter: blur(var(--card-blur));
            -webkit-backdrop-filter: blur(var(--card-blur));
        }
        .modern-select-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            border: none;
            background: transparent;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-align: left;
        }
        .modern-select-item i:first-child { font-size: 0.95rem; color: var(--text-muted); width: 18px; }
        .modern-select-item span { flex: 1; }
        .modern-select-check { opacity: 0; font-size: 0.9rem; color: var(--green-600); }
        .modern-select-item:hover { background: rgba(30,148,71,0.08); color: var(--text-primary); }
        .modern-select-item:hover i:first-child { color: var(--green-600); }
        .modern-select-item.active { background: rgba(30,148,71,0.12); color: var(--green-600); font-weight: 700; }
        .modern-select-item.active i:first-child { color: var(--green-600); }
        .modern-select-item.active .modern-select-check { opacity: 1; }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, var(--blue-800) 0%, var(--blue-700) 40%, var(--blue-600) 75%, var(--green-600) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: width 0.3s cubic-bezier(0.4,0,0.2,1);
            z-index: 1000;
            overflow: hidden;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar::-webkit-scrollbar { display: none; }

        /* Collapsed state */
        .sidebar.mini { width: var(--sidebar-w-mini); }
        .sidebar.mini .nav-link-text,
        .sidebar.mini .sidebar-heading,
        .sidebar.mini .sidebar-brand-text,
        .sidebar.mini .sidebar-footer-card { display: none; }
        .sidebar.mini .nav-link { justify-content: center; padding: 12px; }
        .sidebar.mini .sidebar-brand { justify-content: center; }
        .sidebar.mini .nav-link i { font-size: 1.25rem; }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            min-height: 72px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .sidebar-brand img { height: 36px; width: auto; flex-shrink: 0; background: #fff; border-radius: 8px; padding: 3px; }
        .sidebar-brand-text { white-space: nowrap; overflow: hidden; transition: opacity 0.2s; }
        .sidebar-brand-text h6 { font-size: 0.85rem; font-weight: 700; color: #fff; margin: 0; }
        .sidebar-brand-text small { font-size: 0.68rem; color: rgba(255,255,255,0.55); }

        /* Nav */
        .sidebar-nav { padding: 16px 12px; list-style: none; flex: 1; }
        .sidebar-heading {
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: rgba(255,255,255,0.35);
            padding: 16px 10px 6px; white-space: nowrap;
            transition: opacity 0.2s;
        }
        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        .nav-link i { font-size: 1.05rem; flex-shrink: 0; }
        .nav-link-text { transition: opacity 0.2s; }
        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
            transform: translateX(2px);
        }
        .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--green-600), var(--green-500));
            box-shadow: 0 4px 14px rgba(30,148,71,0.4);
        }

        /* Sidebar Footer */
        .sidebar-footer-card {
            margin: 0 12px 16px;
            padding: 14px 16px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: opacity 0.2s;
            flex-shrink: 0;
        }
        .sidebar-footer-card p { font-size: 0.7rem; color: rgba(255,255,255,0.7); margin: 0 0 4px; }
        .sidebar-footer-card small { font-size: 0.62rem; color: rgba(255,255,255,0.4); }

        /* ============================================================
           MAIN CONTENT
        ============================================================ */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            z-index: 1;
        }
        .main-content.expanded { margin-left: var(--sidebar-w-mini); }

        /* ============================================================
           TOPBAR
        ============================================================ */
        .topbar {
            height: 70px;
            background:
                linear-gradient(90deg, rgba(18,128,94,0.10), rgba(16,111,138,0.10)),
                var(--topbar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--card-border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            transition: background 0.3s;
        }

        .topbar-toggler {
            width: 40px; height: 40px;
            background: transparent;
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
        }
        .topbar-toggler:hover { background: rgba(30,148,71,0.08); border-color: var(--green-600); color: var(--green-600); }

        .topbar-left { display: flex; align-items: center; gap: 18px; }
        .topbar-breadcrumb { display: flex; flex-direction: column; line-height: 1.25; }
        .topbar-breadcrumb-eyebrow {
            font-size: 0.66rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--text-muted);
        }
        .topbar-breadcrumb-title { font-size: 0.98rem; font-weight: 700; color: var(--text-primary); }
        @media (max-width: 576px) { .topbar-breadcrumb { display: none; } }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        /* Theme toggle pill */
        .theme-toggle {
            display: flex; align-items: center; gap: 4px;
            background: rgba(0,0,0,0.06);
            border: 1.5px solid var(--card-border);
            border-radius: 30px;
            padding: 4px;
            cursor: pointer;
        }
        .theme-icon {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .theme-icon.is-active {
            background: var(--blue-700);
            color: #fff;
            box-shadow: 0 2px 8px rgba(9,74,115,0.4);
        }
        [data-theme="dark"] .theme-icon.is-active { background: var(--green-600); }

        /* Notification bell */
        .notif-btn {
            width: 40px; height: 40px;
            background: transparent;
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: var(--text-secondary);
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .notif-btn:hover { border-color: var(--blue-600); color: var(--blue-600); }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: var(--green-600); color: #fff;
            font-size: 0.6rem; font-weight: 700;
            min-width: 16px; height: 16px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 3px;
            border: 2px solid var(--content-bg);
        }

        /* Profile */
        .topbar-profile {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 12px 6px 6px;
            border: 1.5px solid var(--card-border);
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .topbar-profile:hover { border-color: var(--green-600); box-shadow: 0 0 0 3px rgba(30,148,71,0.1); }
        .topbar-profile img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
        .topbar-profile .pname { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); }
        .topbar-profile .prole { font-size: 0.7rem; color: var(--text-muted); }

        /* ============================================================
           CONTENT AREA
        ============================================================ */
        .content-area {
            padding: 28px 30px;
            flex-grow: 1;
        }

        .page-header-title { font-size: 1.4rem; font-weight: 800; color: var(--text-primary); }
        .page-header-subtitle { font-size: 0.85rem; color: var(--text-secondary); }

        /* ============================================================
           PAGE BANNER (gradasi biru->hijau, bersih tanpa tekstur)
           Muncul otomatis di SETIAP halaman admin karena berada di layout.
        ============================================================ */
        .page-banner {
            position: relative;
            border-radius: 20px;
            padding: 26px 30px;
            margin-bottom: 24px;
            overflow: hidden;
            background: linear-gradient(120deg, var(--blue-700) 0%, var(--green-600) 100%);
            box-shadow: 0 6px 18px -8px rgba(15,23,42,0.25);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }
        .page-banner .page-header-title { color: #fff; margin-bottom: 4px; }
        .page-banner .page-header-subtitle { color: rgba(255,255,255,0.85); }

        /* ============================================================
           GLASS CARDS
        ============================================================ */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(var(--card-blur));
            -webkit-backdrop-filter: blur(var(--card-blur));
            border: 1px solid var(--card-border);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            transition: box-shadow 0.2s, transform 0.2s, background 0.3s, border-color 0.3s;
        }
        .card:hover { box-shadow: var(--shadow-hover); }

        /* ============================================================
           BUTTONS
        ============================================================ */
        .btn { border-radius: 10px; font-weight: 700; font-size: 0.88rem; padding: 10px 20px; transition: all 0.2s; }
        .btn-primary {
            background: linear-gradient(135deg, var(--green-600), var(--green-500)) !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(30,148,71,0.35);
            color: #fff !important;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(30,148,71,0.45) !important; }

        /* Tombol sekunder: solid & kontras tinggi supaya mudah terlihat siapa pun,
           termasuk saat diletakkan di atas page-banner bergradasi hijau-biru */
        .btn-secondary {
            background: #ffffff !important;
            border: 1.5px solid var(--blue-600) !important;
            color: var(--blue-700) !important;
            box-shadow: 0 2px 8px rgba(15,23,42,0.08);
        }
        .btn-secondary:hover { background: var(--blue-600) !important; color: #fff !important; border-color: var(--blue-600) !important; }
        [data-theme="dark"] .btn-secondary { background: rgba(255,255,255,0.92) !important; color: var(--blue-800) !important; }

        .btn-outline-primary {
            border: 1.5px solid var(--green-600) !important;
            color: var(--green-600) !important;
            background: #fff !important;
        }
        .btn-outline-primary:hover { background: var(--green-600) !important; color: #fff !important; }
        .btn-danger { background: linear-gradient(135deg, #ef4444, #dc2626) !important; border: none !important; box-shadow: 0 4px 12px rgba(220,38,38,0.3); }

        /* Tombol di dalam page-banner (di atas gradasi) selalu solid putih agar tidak "hilang" */
        .page-banner .btn-secondary {
            background: rgba(255,255,255,0.95) !important;
            border: 1.5px solid rgba(255,255,255,0.9) !important;
            color: var(--blue-800) !important;
        }
        .page-banner .btn-secondary:hover { background: #fff !important; }

        /* Pill / badge kategori dibuat lebih tebal & jelas agar mudah dibaca */
        .status-badge, .cat-pill {
            padding: 6px 16px; border-radius: 999px;
            font-size: 0.78rem; font-weight: 700;
            display: inline-block;
            border: 1.5px solid transparent;
        }

        /* ============================================================
           TABLE (sel SELALU transparan -> otomatis ikut warna kaca
           dari .card induknya, konsisten di light & dark, tidak lagi
           cuma "gelap pas di-hover")
        ============================================================ */
        .table {
            border-collapse: separate; border-spacing: 0; color: var(--text-primary);
            --bs-table-bg: transparent;
            --bs-table-striped-bg: transparent;
            --bs-table-hover-bg: transparent;
            --bs-table-accent-bg: transparent;
        }
        .table > :not(caption) > * > * { background-color: transparent !important; box-shadow: none !important; }
        .table thead th {
            background: transparent !important;
            color: var(--text-secondary);
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            padding: 14px 16px;
            border-bottom: 2px solid var(--card-border) !important;
        }
        .table tbody td {
            background: transparent !important;
            padding: 14px 16px;
            border-bottom: 1px solid var(--card-border);
            font-size: 0.86rem;
            vertical-align: middle;
            color: var(--text-primary);
        }
        .table-hover tbody tr { transition: background 0.15s; }
        .table-hover tbody tr:hover td { background: rgba(30,148,71,0.08) !important; }
        [data-theme="dark"] .table-hover tbody tr:hover td { background: rgba(38,184,87,0.12) !important; }

        /* ============================================================
           FORM CONTROLS
        ============================================================ */
        .form-control, .form-select {
            background: var(--card-bg);
            border: 1.5px solid var(--card-border);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.88rem;
            padding: 10px 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--green-600);
            box-shadow: 0 0 0 3px rgba(30,148,71,0.15);
            background: var(--card-bg);
            color: var(--text-primary);
        }
        .form-label { font-size: 0.84rem; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }

        /* ============================================================
           STATUS BADGES
        ============================================================ */
        .badge-pengajuan  { background: rgba(100,116,139,0.12); color: #475569; }
        .badge-penjadwalan{ background: rgba(15,110,168,0.12); color: var(--blue-600); }
        .badge-kalibrasi  { background: rgba(56,182,232,0.16); color: var(--blue-500); }
        .badge-sertifikat { background: rgba(30,148,71,0.12); color: var(--green-600); }
        .badge-pembayaran { background: rgba(245,158,11,0.12); color: #d97706; }
        .badge-ditolak    { background: rgba(239,68,68,0.12); color: #dc2626; }


        /* ============================================================
           DARK MODE OVERRIDES
        ============================================================ */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); color: var(--text-primary); }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus { background: rgba(255,255,255,0.1); color: var(--text-primary); }
        [data-theme="dark"] .btn-secondary { background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.12) !important; color: var(--text-secondary) !important; }
        [data-theme="dark"] body { background-color: var(--content-bg); }

        /* ============================================================
           MISC
        ============================================================ */
        .alert { border: none; border-radius: 12px; font-size: 0.86rem; }
        .alert-success { background: rgba(30,148,71,0.1); color: var(--green-600); }

        @media print {
            html, body, .main-content, .content-area, .row, .col, .card, .stat-card { 
                background: #fff !important; 
                background-color: #fff !important; 
                color: #000 !important; 
                box-shadow: none !important; 
            }
            body::before, body::after { display: none !important; }
            .sidebar, .topbar, .btn { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .content-area { padding: 0 !important; }
            .card, .stat-card { border: 1px solid #000 !important; break-inside: avoid; filter: none !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important; }
            .page-banner { background: #fff !important; border: 1px solid #000 !important; color: #000 !important; }
            .page-banner .page-header-title, .page-banner .page-header-subtitle { color: #000 !important; }
            h1, h2, h3, h4, h5, h6, p, td, th, span, div { color: #000 !important; }
            .brand-strip { background: #fff !important; border: 1px solid #000 !important; color: #000 !important; }
            canvas { max-width: 100% !important; filter: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 10mm; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
        }

        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ======= SIDEBAR ======= -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="javascript:void(0)" class="sidebar-brand" id="sidebarToggler" title="Toggle Sidebar">
        <img src="{{ asset('images/logo-uptd-transparent.png') }}" alt="UPTD">
        <div class="sidebar-brand-text">
            <h6>UPTD Kalibrasi</h6>
            <small>Admin Panel</small>
        </div>
    </a>

    <!-- Navigation -->
    <ul class="sidebar-nav">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </li>

        <li class="sidebar-heading">Menu Utama</li>

        <!-- Pesanan Kalibrasi -->
        <li>
            <a href="{{ route('admin.calibrations.index') }}"
               class="nav-link {{ request()->routeIs('admin.calibrations.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check-fill"></i>
                <span class="nav-link-text">Pesanan Kalibrasi</span>
            </a>
        </li>

        <!-- Pengguna (memakai route users yang sudah ada) -->
        <li>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span class="nav-link-text">Pengguna</span>
            </a>
        </li>

        <!-- Chat -->
        <li>
            <a href="{{ route('admin.chat.index') }}" class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i>
                <span class="nav-link-text d-flex align-items-center justify-content-between w-100">
                    Chat
                    @php
                        $unreadChat = \App\Models\ChatMessage::where('sender_role', 'user')->where('is_read', false)->count();
                    @endphp
                    @if($unreadChat > 0)
                        <span class="badge bg-success rounded-pill">{{ $unreadChat }}</span>
                    @endif
                </span>
            </a>
        </li>

        <!-- Layanan (Gabungan: kategori + alat) -->
        <li>
            <a href="{{ route('admin.services.index') }}"
               class="nav-link {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.service-categories.*') ? 'active' : '' }}">
                <i class="bi bi-heart-pulse-fill"></i>
                <span class="nav-link-text">Layanan</span>
            </a>
        </li>

        <!-- Berita (memakai route articles yang sudah ada) -->
        <li>
            <a href="{{ route('admin.articles.index') }}"
               class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i>
                <span class="nav-link-text">Berita</span>
            </a>
        </li>



        <li class="sidebar-heading">Akun</li>

        <!-- Logout -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-100 border-0 text-start" style="background:none; cursor:pointer;">
                    <i class="bi bi-box-arrow-left"></i>
                    <span class="nav-link-text">Logout</span>
                </button>
            </form>
        </li>
    </ul>

    <!-- Footer info -->
    <div class="sidebar-footer-card">
        <p>Sistem Informasi Kalibrasi<br>UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan</p>
        <small>&copy; 2026 All Rights Reserved</small>
    </div>
</aside>

<!-- ======= MAIN CONTENT ======= -->
<div class="main-content" id="mainContent">

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <div class="topbar-breadcrumb">
                <span class="topbar-breadcrumb-eyebrow">UPTD Kalibrasi</span>
                <span class="topbar-breadcrumb-title">@yield('page_title', 'Dashboard')</span>
            </div>
        </div>

        <div class="topbar-right">
            <!-- Light / Dark Mode Toggle -->
            <div class="theme-toggle" id="themeToggle" title="Ganti Tema">
                <div class="theme-icon" id="iconMoon"><i class="bi bi-moon-fill"></i></div>
                <div class="theme-icon is-active" id="iconSun"><i class="bi bi-sun-fill"></i></div>
            </div>

            <!-- Notification Bell -->
            @php
                $notifNewOrders = \App\Models\CalibrationRequest::where('status', 'Pengajuan')->count();
                $notifChat      = \App\Models\ChatMessage::where('sender_role', 'user')->where('is_read', false)->count();
                $notifTotal     = $notifNewOrders + $notifChat;
                $recentNotifs   = \App\Models\CalibrationRequest::where('status', 'Pengajuan')->latest()->take(3)->get();
            @endphp
            <div class="dropdown">
                <button class="notif-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    @if($notifTotal > 0)
                        <span class="notif-badge">{{ $notifTotal > 9 ? '9+' : $notifTotal }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="min-width:320px; border-radius:14px; overflow:hidden;">
                    <li class="px-3 py-2 border-bottom" style="background: linear-gradient(120deg, var(--blue-700), var(--green-600));">
                        <span style="font-weight:700; font-size:0.82rem; color:#fff;"><i class="bi bi-bell-fill me-1"></i> Notifikasi</span>
                        @if($notifTotal > 0)
                            <span class="badge ms-1" style="background:rgba(255,255,255,0.25); font-size:0.7rem;">{{ $notifTotal }} baru</span>
                        @endif
                    </li>

                    @if($notifChat > 0)
                    <li>
                        <a href="{{ route('admin.chat.index') }}" class="dropdown-item d-flex align-items-start gap-3 py-3">
                            <div style="width:36px;height:36px;border-radius:50%;background:rgba(30,148,71,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-chat-dots-fill" style="color:var(--green-600);"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="font-size:0.83rem;font-weight:700;color:var(--text-primary);">{{ $notifChat }} Pesan Baru</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Pesan dari pelanggan belum dibaca</div>
                            </div>
                            <span class="badge" style="background:var(--green-600);font-size:0.68rem;border-radius:999px;align-self:center;">{{ $notifChat }}</span>
                        </a>
                    </li>
                    @endif

                    @forelse($recentNotifs as $notif)
                    <li>
                        <a href="{{ route('admin.calibrations.index') }}" class="dropdown-item d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:var(--card-border)!important;">
                            <div style="width:36px;height:36px;border-radius:50%;background:rgba(15,110,168,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-clipboard2-check" style="color:var(--blue-600);"></i>
                            </div>
                            <div style="flex:1; overflow:hidden;">
                                <div style="font-size:0.83rem;font-weight:700;color:var(--text-primary);">Pengajuan Baru</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $notif->nama_instansi }}</div>
                                <div style="font-size:0.7rem;color:var(--text-muted);">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </a>
                    </li>
                    @empty
                    @endforelse

                    @if($notifTotal === 0)
                    <li class="text-center py-4" style="color:var(--text-muted);font-size:0.82rem;">
                        <i class="bi bi-check-circle" style="font-size:1.6rem;display:block;margin-bottom:6px;opacity:0.35;"></i>
                        Tidak ada notifikasi baru
                    </li>
                    @endif

                    <li class="border-top" style="border-color:var(--card-border)!important;">
                        <a href="{{ route('admin.calibrations.index') }}" class="dropdown-item text-center py-2" style="font-size:0.8rem;font-weight:600;color:var(--blue-600);">
                            Lihat Semua Pesanan <i class="bi bi-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <div class="topbar-profile" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=1E9447&color=fff" alt="Avatar">
                    <div>
                        <div class="pname">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="prole">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size:0.7rem; color:var(--text-muted);"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="min-width:220px; border-radius:14px; overflow:hidden;">
                    <li class="px-3 py-3 border-bottom" style="border-color:var(--card-border)!important;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=1E9447&color=fff" alt="Avatar" style="width:40px;height:40px;border-radius:50%;">
                            <div>
                                <div style="font-weight:700;font-size:0.88rem;color:var(--text-primary);">{{ auth()->user()->name ?? 'Admin' }}</div>
                                <div style="font-size:0.73rem;color:var(--text-muted);">{{ auth()->user()->email ?? '' }}</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="px-2 py-2">
                            @csrf
                            <button type="submit" class="w-100 d-flex align-items-center gap-2 border-0 rounded-2 px-3 py-2"
                                style="background: linear-gradient(135deg, #ef4444, #dc2626); color:#fff; font-size:0.85rem; font-weight:700; cursor:pointer; transition:all 0.2s;"
                                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(220,38,38,0.4)'"
                                onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <i class="bi bi-box-arrow-left"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <main class="content-area">
        <div class="page-banner">
            <div>
                <h2 class="page-header-title mb-1">@yield('page_title', 'Dashboard')</h2>
                <p class="page-header-subtitle mb-0">@yield('page_subtitle', '')</p>
            </div>
            @yield('page_actions')
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ==============================
//  SIDEBAR TOGGLE
// ==============================
const sidebar     = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const toggler     = document.getElementById('sidebarToggler');
const overlay     = document.getElementById('sidebarOverlay');

toggler.addEventListener('click', () => {
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('mobile-open');
        overlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
    } else {
        sidebar.classList.toggle('mini');
        mainContent.classList.toggle('expanded');
    }
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.style.display = 'none';
});

// ==============================
//  DARK / LIGHT MODE TOGGLE
// ==============================
const html      = document.documentElement;
const themeBtn  = document.getElementById('themeToggle');
const iconMoon  = document.getElementById('iconMoon');
const iconSun   = document.getElementById('iconSun');

function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('admin-theme', theme);
    if (theme === 'dark') {
        iconMoon.classList.add('is-active');
        iconSun.classList.remove('is-active');
    } else {
        iconSun.classList.add('is-active');
        iconMoon.classList.remove('is-active');
    }
}

// Load saved theme
const savedTheme = localStorage.getItem('admin-theme') || 'light';
applyTheme(savedTheme);

themeBtn.addEventListener('click', () => {
    const current = html.getAttribute('data-theme');
    applyTheme(current === 'dark' ? 'light' : 'dark');
});
</script>

<script>
// ==============================
//  GLOBAL DELETE CONFIRMATION
// ==============================
function confirmDelete(formId, message) {
    message = message || 'Data yang dihapus tidak dapat dikembalikan.';
    Swal.fire({
        title: 'Hapus data ini?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-trash3-fill"></i> Ya, Hapus',
        cancelButtonText: '<i class="bi bi-x-lg"></i> Batal',
        customClass: {
            popup: 'swal-delete-popup',
            title: 'swal-delete-title',
            icon: 'swal-delete-icon',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn',
        },
        buttonsStyling: false,
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
document.querySelectorAll('.modern-select').forEach(function (wrap) {
    wrap.querySelectorAll('.modern-select-menu .dropdown-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            wrap.querySelector('.modern-select-label').textContent = this.dataset.label;
            wrap.querySelector('input[type="hidden"]').value = this.dataset.value;
            wrap.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>

<style>
.swal-delete-popup {
    border-radius: 20px !important;
    padding: 28px 24px !important;
    box-shadow: 0 25px 60px rgba(0,0,0,0.2) !important;
    font-family: 'Inter', sans-serif !important;
    border: 1px solid rgba(239,68,68,0.15) !important;
}
.swal-delete-title {
    font-size: 1.2rem !important;
    font-weight: 700 !important;
    color: #0f172a !important;
}
.swal2-html-container {
    font-size: 0.88rem !important;
    color: #64748b !important;
}
.swal-delete-icon {
    border-color: rgba(239,68,68,0.3) !important;
    color: #ef4444 !important;
}
.swal2-icon.swal2-warning {
    border-color: rgba(239,68,68,0.3) !important;
    color: #ef4444 !important;
}
.swal-confirm-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 10px 22px !important;
    font-size: 0.87rem !important;
    font-weight: 700 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 6px 20px rgba(220,38,38,0.35) !important;
    transition: all 0.2s !important;
}
.swal-confirm-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 10px 28px rgba(220,38,38,0.45) !important;
}
.swal-cancel-btn {
    background: #f1f5f9 !important;
    color: #475569 !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 10px 22px !important;
    font-size: 0.87rem !important;
    font-weight: 600 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    transition: all 0.2s !important;
}
.swal-cancel-btn:hover {
    background: #e2e8f0 !important;
    color: #1e293b !important;
}
[data-theme="dark"] .swal-delete-popup {
    background: #0f1f38 !important;
    border-color: rgba(239,68,68,0.2) !important;
}
[data-theme="dark"] .swal-delete-title { color: #e7edf5 !important; }
[data-theme="dark"] .swal2-html-container { color: #94a3b8 !important; }
[data-theme="dark"] .swal-cancel-btn {
    background: #1e3558 !important;
    color: #a3b1c6 !important;
    border-color: rgba(255,255,255,0.08) !important;
}
.modern-select .modern-select-toggle {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-radius: 12px; border: 1.5px solid var(--card-border);
    background: var(--card-bg); font-weight: 600; cursor: pointer;
}
.modern-select-menu {
    border-radius: 14px; padding: 8px; box-shadow: 0 14px 34px rgba(15,23,42,0.14);
}
.modern-select-menu .dropdown-item.active {
    background: linear-gradient(135deg, rgba(30,148,71,0.14), rgba(15,110,168,0.1));
    color: var(--green-700);
}
</style>
<script>
// ==============================
//  MODERN SELECT (dropdown pengganti <select>)
// ==============================
function setModernSelect(btn, hiddenInputId, value, label, icon) {
    document.getElementById(hiddenInputId).value = value;
    const wrapper = btn.closest('.modern-select');
    wrapper.querySelector('.modern-select-trigger-label').textContent = label;
    wrapper.querySelector('.modern-select-trigger-icon').className = 'bi ' + icon + ' modern-select-trigger-icon';
    wrapper.querySelectorAll('.modern-select-item').forEach(function (item) {
        item.classList.remove('active');
    });
    btn.classList.add('active');
}
</script>

@stack('scripts')
</body>
</html>
