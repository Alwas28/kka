<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem KKA – Universitas Muhammadiyah Kendari</title>
    <meta name="description" content="Platform digital untuk mengelola seluruh proses KKA — pendaftaran mahasiswa, pembentukan kelompok, pelaksanaan, hingga penilaian akhir.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="KKA UM Kendari">
    <meta property="og:title"       content="Sistem KKA – Universitas Muhammadiyah Kendari">
    <meta property="og:description" content="Platform digital untuk mengelola seluruh proses KKA — pendaftaran mahasiswa, pembentukan kelompok, pelaksanaan, hingga penilaian akhir.">
    <meta property="og:url"         content="{{ url('/') }}">
    <meta property="og:image"       content="{{ asset('img/logo2.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="Sistem KKA – Universitas Muhammadiyah Kendari">
    <meta name="twitter:description" content="Platform digital untuk mengelola seluruh proses KKA — pendaftaran mahasiswa, pembentukan kelompok, pelaksanaan, hingga penilaian akhir.">
    <meta name="twitter:image"       content="{{ asset('img/logo2.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --maroon-dark:    #800000;
            --maroon-main:    #A52A2A;
            --maroon-light:   #C41E3A;
            --maroon-lighter: #E8B4B8;
            --gray-light:     #f3f4f6;
            --gray-border:    #e5e7eb;
            --text-primary:   #111827;
            --text-secondary: #6b7280;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ── NAVBAR ───────────────────────────────────────────────── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--gray-border);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .navbar-brand img {
            height: 38px;
            object-fit: contain;
        }
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        /* ── DYNAMIC NAV MENUS ────────────────────────────────────── */
        .nav-menus { display:flex; align-items:center; gap:2px; flex:1; justify-content:center; flex-wrap:wrap; }
        .nav-menu-link { padding:6px 13px; border-radius:7px; font-size:13px; font-weight:600; color:var(--text-primary); text-decoration:none; transition:background .15s,color .15s; white-space:nowrap; }
        .nav-menu-link:hover { background:rgba(165,42,42,.08); color:var(--maroon-main); }
        .nav-dropdown { position:relative; }
        .nav-dropdown-btn { padding:6px 13px; border-radius:7px; font-size:13px; font-weight:600; color:var(--text-primary); background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:5px; font-family:inherit; white-space:nowrap; transition:background .15s,color .15s; }
        .nav-dropdown-btn:hover { background:rgba(165,42,42,.08); color:var(--maroon-main); }
        .nav-dropdown-panel { display:none; position:absolute; top:calc(100%+6px); left:0; background:#fff; border:1px solid var(--gray-border); border-radius:10px; min-width:180px; box-shadow:0 8px 24px rgba(0,0,0,.10); z-index:200; padding:6px; }
        .nav-dropdown:hover .nav-dropdown-panel { display:block; }
        .nav-dropdown-item { display:flex; align-items:center; gap:8px; padding:8px 12px; border-radius:7px; font-size:13px; color:var(--text-primary); text-decoration:none; }
        .nav-dropdown-item:hover { background:rgba(165,42,42,.07); color:var(--maroon-main); }

        /* ── TICKER (sticky bawah navbar) ────────────────────────── */
        .ticker-bar { background:#fff8f8; border-bottom:1px solid rgba(165,42,42,.1); padding:0 40px; position:sticky; top:64px; z-index:99; }
        .ticker-inner { max-width:1200px; margin:0 auto; display:flex; align-items:stretch; min-height:40px; }
        .ticker-label { display:flex; align-items:center; gap:7px; background:var(--maroon-main); color:#fff; font-size:.7rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; padding:0 14px; white-space:nowrap; flex-shrink:0; }
        .ticker-scroll { flex:1; overflow:hidden; display:flex; align-items:center; }
        .ticker-track { display:flex; animation:scrollTicker 30s linear infinite; white-space:nowrap; }
        .ticker-track:hover { animation-play-state:paused; }
        .ticker-item { display:inline-flex; align-items:center; gap:6px; font-size:.8rem; color:var(--text-primary); padding:0 24px; border-right:1px solid var(--gray-border); text-decoration:none; transition:color .15s; }
        .ticker-item:hover { color:var(--maroon-main); }
        .ticker-penting { color:#ef4444; font-size:.72rem; }
        @keyframes scrollTicker { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
        .btn-outline {
            padding: 8px 20px;
            border: 1.5px solid var(--maroon-main);
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--maroon-main);
            background: transparent;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-outline:hover {
            background: var(--maroon-main);
            color: #fff;
        }
        .btn-solid {
            padding: 8px 20px;
            border: 1.5px solid var(--maroon-main);
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            background: var(--maroon-main);
            text-decoration: none;
            transition: all .2s;
        }
        .btn-solid:hover {
            background: var(--maroon-dark);
            border-color: var(--maroon-dark);
        }

        /* ── HERO ─────────────────────────────────────────────────── */
        .hero {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 80px 40px;
            background: linear-gradient(150deg, #fff 0%, #fdf2f2 50%, #fff 100%);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(165,42,42,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -150px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(165,42,42,.04) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-inner {
            max-width: 760px;
            position: relative;
            z-index: 1;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(165,42,42,.08);
            color: var(--maroon-main);
            border: 1px solid rgba(165,42,42,.15);
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 28px;
            letter-spacing: .02em;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.25rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--text-primary);
            margin-bottom: 20px;
        }
        .hero h1 span {
            color: var(--maroon-main);
        }
        .hero p {
            font-size: 1.05rem;
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 580px;
            margin: 0 auto 40px;
        }
        .hero-cta {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .hero-cta .btn-hero-solid {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
            color: #fff;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(165,42,42,.3);
            transition: transform .2s, box-shadow .2s;
        }
        .hero-cta .btn-hero-solid:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(165,42,42,.4);
        }
        .hero-cta .btn-hero-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: #fff;
            color: var(--maroon-main);
            border: 1.5px solid var(--gray-border);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .hero-cta .btn-hero-outline:hover {
            border-color: var(--maroon-lighter);
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }
        .hero-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 60px;
            flex-wrap: wrap;
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat-num {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--maroon-main);
        }
        .hero-stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 2px;
        }
        .hero-divider {
            width: 1px;
            background: var(--gray-border);
            align-self: stretch;
        }

        /* ── FEATURES ─────────────────────────────────────────────── */
        .features {
            padding: 80px 40px;
            background: var(--gray-light);
        }
        .section-label {
            text-align: center;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--maroon-main);
            margin-bottom: 12px;
        }
        .section-title {
            text-align: center;
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
        }
        .section-sub {
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-secondary);
            max-width: 520px;
            margin: 0 auto 52px;
            line-height: 1.6;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .feature-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            border: 1px solid var(--gray-border);
            transition: transform .2s, box-shadow .2s;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.10);
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 18px;
        }
        .feature-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        .feature-desc {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* ticker CSS sudah didefinisikan di blok NAVBAR di atas */

        /* ── BERITA ───────────────────────────────────────────────── */
        .berita-section { padding:80px 40px; background:#fff; }
        .berita-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:24px; max-width:1100px; margin:0 auto; }
        .berita-card { border-radius:16px; overflow:hidden; border:1px solid var(--gray-border); background:#fff; transition:transform .2s,box-shadow .2s; display:flex; flex-direction:column; }
        .berita-card:hover { transform:translateY(-4px); box-shadow:0 10px 28px rgba(0,0,0,.10); }
        .berita-img-wrap { width:100%; height:190px; background:linear-gradient(135deg,#fdf2f2,#fee2e2); display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; }
        .berita-img-wrap img { width:100%; height:190px; object-fit:cover; }
        .berita-img-icon { font-size:3rem; color:var(--maroon-main); opacity:.3; }
        .berita-body { padding:20px; flex:1; display:flex; flex-direction:column; gap:8px; }
        .berita-date { font-size:.75rem; color:var(--maroon-main); font-weight:600; }
        .berita-title { font-size:1rem; font-weight:700; color:var(--text-primary); line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .berita-excerpt { font-size:.83rem; color:var(--text-secondary); line-height:1.6; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; flex:1; }
        .berita-empty { text-align:center; padding:48px; color:var(--text-secondary); font-size:.9rem; }
        .berita-empty i { font-size:2.5rem; opacity:.25; display:block; margin-bottom:12px; }
        @media(max-width:600px) { .pengumuman-bar{padding:0 16px;} .berita-section{padding:60px 20px;} }

        /* ── PENGUMUMAN SECTION ───────────────────────────────────── */
        .peng-section { padding:80px 40px; background:var(--gray-light); }
        .peng-list { display:flex; flex-direction:column; gap:14px; max-width:860px; margin:0 auto; }
        .peng-card { background:#fff; border-radius:14px; border:1px solid var(--gray-border); display:flex; align-items:stretch; overflow:hidden; text-decoration:none; color:inherit; transition:transform .2s,box-shadow .2s; }
        .peng-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.09); }
        .peng-card-side { width:6px; flex-shrink:0; background:var(--maroon-main); }
        .peng-card-side.penting { background:#ef4444; }
        .peng-card-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; gap:6px; }
        .peng-card-head { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .peng-card-badge { font-size:10px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; padding:2px 10px; border-radius:20px; }
        .peng-card-badge-aktif   { background:rgba(16,185,129,.1); color:#065f46; border:1px solid rgba(16,185,129,.2); }
        .peng-card-badge-penting { background:rgba(239,68,68,.1); color:#b91c1c; border:1px solid rgba(239,68,68,.2); }
        .peng-card-title { font-size:.95rem; font-weight:700; color:var(--text-primary); line-height:1.4; }
        .peng-card-excerpt { font-size:.82rem; color:var(--text-secondary); line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .peng-card-meta { display:flex; align-items:center; gap:14px; font-size:.75rem; color:var(--text-secondary); margin-top:4px; }
        .peng-card-meta i { color:var(--maroon-main); }
        .peng-card-thumb { width:120px; flex-shrink:0; overflow:hidden; }
        .peng-card-thumb img { width:120px; height:100%; object-fit:cover; }
        .peng-empty { text-align:center; padding:48px; color:var(--text-secondary); font-size:.9rem; }
        .peng-empty i { font-size:2.5rem; opacity:.25; display:block; margin-bottom:12px; }
        @media(max-width:600px) { .peng-section{padding:60px 20px;} .peng-card-thumb{display:none;} }

        /* ── HOW IT WORKS ─────────────────────────────────────────── */
        .how {
            padding: 80px 40px;
            background: #fff;
        }
        .steps {
            display: flex;
            gap: 0;
            max-width: 900px;
            margin: 0 auto;
            flex-wrap: wrap;
            justify-content: center;
        }
        .step {
            flex: 1;
            min-width: 200px;
            text-align: center;
            padding: 20px;
            position: relative;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 36px;
            right: -10px;
            width: 20px;
            height: 2px;
            background: var(--maroon-lighter);
        }
        .step-num {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 4px 12px rgba(165,42,42,.25);
        }
        .step-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }
        .step-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* ── CTA BANNER ───────────────────────────────────────────── */
        .cta-banner {
            padding: 70px 40px;
            background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
            text-align: center;
            color: #fff;
        }
        .cta-banner h2 {
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 800;
            margin-bottom: 14px;
        }
        .cta-banner p {
            font-size: 1rem;
            opacity: .85;
            max-width: 500px;
            margin: 0 auto 32px;
            line-height: 1.6;
        }
        .cta-banner .btn-white {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 32px;
            background: #fff;
            color: var(--maroon-main);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0,0,0,.15);
            transition: transform .2s, box-shadow .2s;
            margin: 0 8px;
        }
        .cta-banner .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.2);
        }
        .cta-banner .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 32px;
            background: rgba(255,255,255,.12);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.3);
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s;
            margin: 0 8px;
        }
        .cta-banner .btn-ghost:hover {
            background: rgba(255,255,255,.22);
        }

        /* ── FOOTER ───────────────────────────────────────────────── */
        .footer {
            background: #1f2937;
            color: #9ca3af;
            text-align: center;
            padding: 28px 40px;
            font-size: 0.83rem;
        }
        .footer strong { color: #e5e7eb; }

        @media (max-width: 600px) {
            .navbar { padding: 0 20px; }
            .hero    { padding: 60px 20px; }
            .features, .how, .cta-banner { padding: 60px 20px; }
            .step:not(:last-child)::after { display: none; }
            .hero-divider { display: none; }
        }
    </style>
</head>
<body>

{{-- ── NAVBAR ────────────────────────────────────────────────── --}}
<nav class="navbar">
    <a class="navbar-brand" href="/">
        <img src="{{ asset('img/logo2.png') }}" alt="UM Kendari">
    </a>

    {{-- Dynamic menus from admin --}}
    @if($navMenus->isNotEmpty())
    <div class="nav-menus">
        @foreach($navMenus as $m)
            @if($m->children->isEmpty())
                <a href="{{ $m->url ?? '#' }}" class="nav-menu-link" target="{{ $m->target }}">
                    @if($m->icon)<i class="{{ $m->icon }}"></i> @endif{{ $m->label }}
                </a>
            @else
                <div class="nav-dropdown">
                    <button class="nav-dropdown-btn">
                        @if($m->icon)<i class="{{ $m->icon }}"></i> @endif{{ $m->label }}
                        <i class="fas fa-chevron-down" style="font-size:10px;opacity:.6;"></i>
                    </button>
                    <div class="nav-dropdown-panel">
                        @foreach($m->children as $c)
                        <a href="{{ $c->url ?? '#' }}" class="nav-dropdown-item" target="{{ $c->target }}">
                            @if($c->icon)<i class="{{ $c->icon }}"></i>@endif {{ $c->label }}
                        </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="navbar-actions">
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-solid">
                <i class="fas fa-tachometer-alt" style="font-size:.8rem;"></i> Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
            <a href="{{ route('mahasiswa.register') }}" class="btn-solid">
                <i class="fas fa-user-plus" style="font-size:.8rem;"></i> Daftar KKA
            </a>
        @endauth
    </div>
</nav>

{{-- ── TICKER PENGUMUMAN (sticky bawah navbar) ─────────────── --}}
@if($pengumumanAktif->isNotEmpty())
<div class="ticker-bar">
    <div class="ticker-inner">
        <div class="ticker-label"><i class="fas fa-bullhorn"></i> Pengumuman</div>
        <div class="ticker-scroll">
            <div class="ticker-track">
                @foreach($pengumumanAktif as $p)
                <a href="{{ route('pengumuman.show', $p) }}" class="ticker-item">
                    @if($p->is_penting)<i class="fas fa-exclamation-circle ticker-penting"></i>@endif
                    {{ $p->judul }}
                </a>
                @endforeach
                @foreach($pengumumanAktif as $p)
                <a href="{{ route('pengumuman.show', $p) }}" class="ticker-item">
                    @if($p->is_penting)<i class="fas fa-exclamation-circle ticker-penting"></i>@endif
                    {{ $p->judul }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── HERO ──────────────────────────────────────────────────── --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <i class="fas fa-graduation-cap"></i>
            Sistem Informasi KKA
        </div>
        <h1>
            Kuliah Kerja <span>Amaliah</span><br>
            Universitas Muhammadiyah Kendari
        </h1>
        <p>
            Platform digital untuk mengelola seluruh proses KKA — mulai dari pendaftaran mahasiswa,
            pembentukan kelompok, pelaksanaan di lapangan, hingga penilaian akhir.
        </p>
        <div class="hero-cta">
            @guest
            <a href="{{ route('mahasiswa.register') }}" class="btn-hero-solid">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn-hero-outline">
                <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
            </a>
            @else
            <a href="{{ url('/dashboard') }}" class="btn-hero-solid">
                <i class="fas fa-tachometer-alt"></i> Buka Dashboard
            </a>
            @endguest
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num"><i class="fas fa-users" style="font-size:1.2rem;"></i></div>
                <div class="hero-stat-label">Manajemen Peserta</div>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-num"><i class="fas fa-map-location-dot" style="font-size:1.2rem;"></i></div>
                <div class="hero-stat-label">Survey Lokasi</div>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-num"><i class="fas fa-book-open" style="font-size:1.2rem;"></i></div>
                <div class="hero-stat-label">Logbook Digital</div>
            </div>
            <div class="hero-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-num"><i class="fas fa-star-half-stroke" style="font-size:1.2rem;"></i></div>
                <div class="hero-stat-label">Penilaian Online</div>
            </div>
        </div>
    </div>
</section>

{{-- ── FEATURES ──────────────────────────────────────────────── --}}
<section class="features">
    <div class="section-label">Fitur Sistem</div>
    <div class="section-title">Semua dalam Satu Platform</div>
    <div class="section-sub">
        Kelola seluruh siklus KKA secara efisien — dari persiapan hingga pelaporan akhir.
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-file-pen"></i></div>
            <div class="feature-title">Pendaftaran Online</div>
            <div class="feature-desc">Mahasiswa mengisi formulir pendaftaran secara digital lengkap dengan unggah dokumen persyaratan.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-map-location-dot"></i></div>
            <div class="feature-title">Survey Lokasi</div>
            <div class="feature-desc">Tim survey mencatat kondisi desa, fasilitas, dan memberi rekomendasi kelayakan lokasi KKA.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-people-group"></i></div>
            <div class="feature-title">Pembentukan Kelompok</div>
            <div class="feature-desc">Panitia membagi mahasiswa ke dalam kelompok per lokasi dan menugaskan Dosen Pembimbing Lapangan.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-book-open"></i></div>
            <div class="feature-title">Logbook Digital</div>
            <div class="feature-desc">Mahasiswa mencatat kegiatan harian selama pelaksanaan KKA melalui sistem logbook online.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-file-contract"></i></div>
            <div class="feature-title">Laporan Terintegrasi</div>
            <div class="feature-desc">Upload laporan individu dan laporan akhir kelompok langsung ke sistem untuk diakses DPL.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-star-half-stroke"></i></div>
            <div class="feature-title">Penilaian oleh DPL</div>
            <div class="feature-desc">Dosen Pembimbing memberikan nilai logbook, laporan individu, laporan akhir, dan nilai akhir secara online.</div>
        </div>
    </div>
</section>


{{-- ── BERITA TERBARU ─────────────────────────────────────────── --}}
<section class="berita-section">
    <div class="section-label">Informasi</div>
    <div class="section-title">Berita Terbaru</div>
    <div class="section-sub">
        Ikuti perkembangan dan informasi terbaru seputar program KKA.
    </div>

    @if($beritaTerbaru->isEmpty())
    <div class="berita-empty">
        <i class="fas fa-newspaper"></i>
        <p>Belum ada berita yang dipublikasikan.</p>
    </div>
    @else
    <div class="berita-grid">
        @foreach($beritaTerbaru as $b)
        <a href="{{ route('berita.show', $b->slug) }}" class="berita-card" style="text-decoration:none;color:inherit;">
            <div class="berita-img-wrap">
                @if($b->gambar)
                    <img src="{{ Storage::url($b->gambar) }}" alt="{{ $b->judul }}">
                @else
                    <i class="fas fa-newspaper berita-img-icon"></i>
                @endif
            </div>
            <div class="berita-body">
                <div class="berita-date">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $b->published_at?->translatedFormat('d F Y') ?? $b->created_at->translatedFormat('d F Y') }}
                </div>
                <div class="berita-title">{{ $b->judul }}</div>
                <div class="berita-excerpt">{{ strip_tags($b->konten) }}</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</section>

{{-- ── PENGUMUMAN SECTION ──────────────────────────────────── --}}
@if($pengumumanAktif->isNotEmpty())
<section class="peng-section">
    <div class="section-label">Informasi</div>
    <div class="section-title">Pengumuman Aktif</div>
    <div class="section-sub">
        Informasi dan pengumuman terbaru dari panitia KKA.
    </div>

    <div class="peng-list">
        @foreach($pengumumanAktif as $p)
        <a href="{{ route('pengumuman.show', $p) }}" class="peng-card">
            <div class="peng-card-side {{ $p->is_penting ? 'penting' : '' }}"></div>
            <div class="peng-card-body">
                <div class="peng-card-head">
                    @if($p->is_penting)
                    <span class="peng-card-badge peng-card-badge-penting">
                        <i class="fas fa-exclamation-circle"></i> Penting
                    </span>
                    @else
                    <span class="peng-card-badge peng-card-badge-aktif">
                        <i class="fas fa-bullhorn"></i> Pengumuman
                    </span>
                    @endif
                </div>
                <div class="peng-card-title">{{ $p->judul }}</div>
                <div class="peng-card-excerpt">{{ strip_tags($p->konten) }}</div>
                <div class="peng-card-meta">
                    <span><i class="fas fa-calendar-alt"></i> {{ $p->tanggal_mulai->translatedFormat('d F Y') }}</span>
                    @if($p->tanggal_selesai)
                    <span><i class="fas fa-calendar-check"></i> s/d {{ $p->tanggal_selesai->translatedFormat('d F Y') }}</span>
                    @else
                    <span><i class="fas fa-infinity"></i> Tidak terbatas</span>
                    @endif
                </div>
            </div>
            @if($p->gambar)
            <div class="peng-card-thumb">
                <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->judul }}">
            </div>
            @endif
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ── HOW IT WORKS ──────────────────────────────────────────── --}}
<section class="how">
    <div class="section-label">Alur Proses</div>
    <div class="section-title">Cara Kerja Sistem</div>
    <div class="section-sub">
        Ikuti tahapan berikut untuk menyelesaikan KKA dengan lancar.
    </div>
    <div class="steps">
        <div class="step">
            <div class="step-num">1</div>
            <div class="step-title">Daftar</div>
            <div class="step-desc">Isi formulir pendaftaran dan unggah dokumen persyaratan.</div>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <div class="step-title">Verifikasi</div>
            <div class="step-desc">Panitia memverifikasi dokumen dan menyetujui pendaftaran.</div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div class="step-title">Penempatan</div>
            <div class="step-desc">Pembentukan kelompok dan penempatan di lokasi KKA.</div>
        </div>
        <div class="step">
            <div class="step-num">4</div>
            <div class="step-title">Pelaksanaan</div>
            <div class="step-desc">Isi logbook harian dan upload laporan selama KKA.</div>
        </div>
        <div class="step">
            <div class="step-num">5</div>
            <div class="step-title">Penilaian</div>
            <div class="step-desc">DPL memberikan nilai akhir berdasarkan kinerja mahasiswa.</div>
        </div>
    </div>
</section>

{{-- ── CTA BANNER ────────────────────────────────────────────── --}}
<section class="cta-banner">
    <h2>Siap Mengikuti KKA?</h2>
    <p>
        Daftarkan diri sekarang dan mulai perjalanan pengabdian masyarakat bersama Universitas Muhammadiyah Kendari.
    </p>
    <div>
        @guest
        <a href="{{ route('mahasiswa.register') }}" class="btn-white">
            <i class="fas fa-user-plus"></i> Daftar Sekarang
        </a>
        <a href="{{ route('login') }}" class="btn-ghost">
            <i class="fas fa-sign-in-alt"></i> Sudah Punya Akun?
        </a>
        @else
        <a href="{{ url('/dashboard') }}" class="btn-white">
            <i class="fas fa-tachometer-alt"></i> Buka Dashboard
        </a>
        @endguest
    </div>
</section>

{{-- ── FOOTER ────────────────────────────────────────────────── --}}
<footer class="footer">
    <strong>&copy; {{ date('Y') }} Universitas Muhammadiyah Kendari</strong>
    &mdash; Sistem Informasi Kuliah Kerja Amaliah (KKA)
</footer>

</body>
</html>
