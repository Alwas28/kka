<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>{{ $page->judul }} – KKA UM Kendari</title>
    <meta name="description" content="{{ $page->meta_description ?: Str::limit(strip_tags($page->konten), 160) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="article">
    <meta property="og:site_name"   content="KKA UM Kendari">
    <meta property="og:title"       content="{{ $page->judul }}">
    <meta property="og:description" content="{{ $page->meta_description ?: Str::limit(strip_tags($page->konten), 160) }}">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="{{ $page->gambar ? asset(Storage::url($page->gambar)) : asset('img/logo2.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $page->judul }}">
    <meta name="twitter:description" content="{{ $page->meta_description ?: Str::limit(strip_tags($page->konten), 160) }}">
    <meta name="twitter:image"       content="{{ $page->gambar ? asset(Storage::url($page->gambar)) : asset('img/logo2.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --maroon-dark:#800000; --maroon-main:#A52A2A; --maroon-light:#C41E3A;
            --maroon-lighter:#E8B4B8; --gray-light:#f3f4f6; --gray-border:#e5e7eb;
            --text-primary:#111827; --text-secondary:#6b7280;
        }
        body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; background:#f9fafb; color:var(--text-primary); }

        /* Navbar */
        .navbar { position:sticky; top:0; z-index:100; background:rgba(255,255,255,.95); backdrop-filter:blur(10px); border-bottom:1px solid var(--gray-border); padding:0 40px; height:64px; display:flex; align-items:center; justify-content:space-between; }
        .navbar-brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .navbar-brand img { height:36px; }
        .navbar-actions { display:flex; gap:10px; align-items:center; }
        .btn-nav-outline { padding:8px 18px; border:1.5px solid var(--maroon-main); color:var(--maroon-main); border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; }
        .btn-nav-solid   { padding:8px 18px; background:var(--maroon-main); color:#fff; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; }

        /* Dynamic nav menus */
        .nav-menus { display:flex; align-items:center; gap:4px; flex:1; justify-content:center; flex-wrap:wrap; }
        .nav-menu-link { padding:6px 14px; border-radius:7px; font-size:13px; font-weight:600; color:var(--text-primary); text-decoration:none; transition:background .15s,color .15s; white-space:nowrap; }
        .nav-menu-link:hover { background:rgba(165,42,42,.08); color:var(--maroon-main); }
        .nav-dropdown { position:relative; }
        .nav-dropdown-btn { padding:6px 14px; border-radius:7px; font-size:13px; font-weight:600; color:var(--text-primary); background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:6px; font-family:inherit; white-space:nowrap; }
        .nav-dropdown-btn:hover { background:rgba(165,42,42,.08); color:var(--maroon-main); }
        .nav-dropdown-panel { display:none; position:absolute; top:calc(100%+6px); left:0; background:#fff; border:1px solid var(--gray-border); border-radius:10px; min-width:180px; box-shadow:0 8px 24px rgba(0,0,0,.10); z-index:200; padding:6px; }
        .nav-dropdown:hover .nav-dropdown-panel,
        .nav-dropdown-panel:hover { display:block; }
        .nav-dropdown-item { display:flex; align-items:center; gap:8px; padding:8px 12px; border-radius:7px; font-size:13px; color:var(--text-primary); text-decoration:none; }
        .nav-dropdown-item:hover { background:rgba(165,42,42,.07); color:var(--maroon-main); }

        /* Ticker */
        .ticker-bar { background:#fff8f8; border-bottom:1px solid rgba(165,42,42,.1); padding:0 40px; position:sticky; top:64px; z-index:99; }
        .ticker-inner { max-width:1200px; margin:0 auto; display:flex; align-items:stretch; min-height:40px; }
        .ticker-label { display:flex; align-items:center; gap:7px; background:var(--maroon-main); color:#fff; font-size:.7rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; padding:0 14px; white-space:nowrap; flex-shrink:0; }
        .ticker-scroll { flex:1; overflow:hidden; display:flex; align-items:center; }
        .ticker-track { display:flex; animation:scrollTicker 30s linear infinite; white-space:nowrap; }
        .ticker-track:hover { animation-play-state:paused; }
        .ticker-item { display:inline-flex; align-items:center; gap:6px; font-size:.8rem; color:var(--text-primary); padding:0 24px; border-right:1px solid var(--gray-border); text-decoration:none; }
        .ticker-item:hover { color:var(--maroon-main); }
        .ticker-penting { color:#ef4444; font-size:.72rem; }
        @keyframes scrollTicker { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

        /* Layout */
        .page-wrap { max-width:820px; margin:0 auto; padding:40px 24px 60px; }

        /* Breadcrumb */
        .breadcrumb { display:flex; align-items:center; gap:8px; font-size:13px; color:var(--text-secondary); margin-bottom:28px; flex-wrap:wrap; }
        .breadcrumb a { color:var(--maroon-main); text-decoration:none; }
        .breadcrumb i { font-size:10px; }

        /* Header */
        .page-label { display:inline-block; font-size:11px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--maroon-main); background:rgba(165,42,42,.08); border:1px solid rgba(165,42,42,.15); padding:3px 12px; border-radius:20px; margin-bottom:14px; }
        .page-title { font-size:clamp(1.4rem,3vw,2rem); font-weight:800; line-height:1.3; color:var(--text-primary); margin-bottom:16px; }
        .page-meta  { font-size:13px; color:var(--text-secondary); margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid var(--gray-border); }
        .page-meta i { color:var(--maroon-main); margin-right:5px; }

        /* Hero image */
        .page-hero { width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:32px; box-shadow:0 4px 24px rgba(0,0,0,.1); }

        /* Content */
        .page-content { font-size:1rem; line-height:1.85; color:var(--text-primary); }
        .page-content h1,.page-content h2,.page-content h3 { margin:28px 0 12px; font-weight:700; line-height:1.3; }
        .page-content h1 { font-size:1.6rem; } .page-content h2 { font-size:1.3rem; } .page-content h3 { font-size:1.1rem; }
        .page-content p { margin-bottom:16px; }
        .page-content ul,.page-content ol { padding-left:24px; margin-bottom:16px; }
        .page-content li { margin-bottom:6px; }
        .page-content img { max-width:100%; height:auto; border-radius:10px; margin:16px 0; box-shadow:0 2px 12px rgba(0,0,0,.08); }
        .page-content a { color:var(--maroon-main); text-decoration:underline; }
        .page-content blockquote { border-left:4px solid var(--maroon-lighter); padding:12px 20px; background:rgba(165,42,42,.04); margin:20px 0; border-radius:0 8px 8px 0; font-style:italic; }
        .page-content table { width:100%; border-collapse:collapse; margin-bottom:16px; font-size:.9rem; }
        .page-content th,.page-content td { border:1px solid var(--gray-border); padding:8px 12px; }
        .page-content th { background:var(--gray-light); font-weight:700; }

        /* Share */
        .share-section { margin-top:40px; padding-top:24px; border-top:1px solid var(--gray-border); }
        .share-title { font-size:13px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.06em; margin-bottom:14px; }
        .share-buttons { display:flex; gap:10px; flex-wrap:wrap; }
        .btn-share { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:transform .15s,box-shadow .15s; font-family:inherit; }
        .btn-share:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.15); }
        .btn-fb  { background:#1877f2; color:#fff; }
        .btn-tw  { background:#000; color:#fff; }
        .btn-wa  { background:#25d366; color:#fff; }
        .btn-copy { background:white; color:var(--text-primary); border:1.5px solid var(--gray-border); }
        .btn-copy.copied { background:#10b981; color:#fff; border-color:#10b981; }

        .back-btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:1.5px solid var(--gray-border); border-radius:8px; font-size:13px; font-weight:600; color:var(--text-secondary); text-decoration:none; margin-top:32px; transition:all .2s; }
        .back-btn:hover { border-color:var(--maroon-main); color:var(--maroon-main); }

        .footer { background:#1f2937; color:#9ca3af; text-align:center; padding:24px 40px; font-size:.83rem; }
        .footer strong { color:#e5e7eb; }

        @media(max-width:600px) { .navbar{padding:0 16px;} .page-wrap{padding:24px 16px 48px;} .ticker-bar{padding:0 12px;} .nav-menus{display:none;} }
    </style>
</head>
<body>

<nav class="navbar">
    <a class="navbar-brand" href="/"><img src="{{ asset('img/logo2.png') }}" alt="UM Kendari"></a>
    <div class="nav-menus">
        @foreach(\App\Models\Menu::activeNav() as $m)
            @if($m->children->isEmpty())
                <a href="{{ $m->url ?? '#' }}" class="nav-menu-link" target="{{ $m->target }}">
                    @if($m->icon)<i class="{{ $m->icon }}"></i> @endif{{ $m->label }}
                </a>
            @else
                <div class="nav-dropdown">
                    <button class="nav-dropdown-btn">
                        @if($m->icon)<i class="{{ $m->icon }}"></i> @endif{{ $m->label }}
                        <i class="fas fa-chevron-down" style="font-size:10px;"></i>
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
    <div class="navbar-actions">
        @auth <a href="{{ url('/dashboard') }}" class="btn-nav-solid">Dashboard</a>
        @else <a href="{{ route('login') }}" class="btn-nav-outline">Masuk</a>
              <a href="{{ route('mahasiswa.register') }}" class="btn-nav-solid">Daftar KKA</a>
        @endauth
    </div>
</nav>

@php
    $pengumumanTicker = \App\Models\Pengumuman::where('status','aktif')
        ->where(function($q){ $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai','>=',today()); })
        ->where('tanggal_mulai','<=',today())
        ->orderByDesc('is_penting')->orderByDesc('created_at')->take(10)->get();
@endphp
@if($pengumumanTicker->isNotEmpty())
<div class="ticker-bar">
    <div class="ticker-inner">
        <div class="ticker-label"><i class="fas fa-bullhorn"></i> Pengumuman</div>
        <div class="ticker-scroll">
            <div class="ticker-track">
                @foreach($pengumumanTicker as $pt)
                <a href="{{ route('pengumuman.show', $pt) }}" class="ticker-item">
                    @if($pt->is_penting)<i class="fas fa-exclamation-circle ticker-penting"></i>@endif
                    {{ $pt->judul }}
                </a>
                @endforeach
                @foreach($pengumumanTicker as $pt)
                <a href="{{ route('pengumuman.show', $pt) }}" class="ticker-item">
                    @if($pt->is_penting)<i class="fas fa-exclamation-circle ticker-penting"></i>@endif
                    {{ $pt->judul }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<div class="page-wrap">
    <nav class="breadcrumb">
        <a href="/"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right"></i>
        <span style="color:var(--text-primary);">{{ Str::limit($page->judul, 50) }}</span>
    </nav>

    <div class="page-label"><i class="fas fa-file-lines"></i> Halaman</div>
    <h1 class="page-title">{{ $page->judul }}</h1>
    <div class="page-meta">
        <i class="fas fa-calendar-alt"></i>
        Diperbarui {{ $page->updated_at->translatedFormat('d F Y') }}
    </div>

    @if($page->gambar)
    <img src="{{ Storage::url($page->gambar) }}" alt="{{ $page->judul }}" class="page-hero">
    @endif

    <div class="page-content">
        {!! $page->konten !!}
    </div>

    <div class="share-section">
        <div class="share-title"><i class="fas fa-share-nodes"></i> Bagikan</div>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
               target="_blank" rel="noopener" class="btn-share btn-fb">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($page->judul) }}"
               target="_blank" rel="noopener" class="btn-share btn-tw">
                <i class="fab fa-x-twitter"></i> X / Twitter
            </a>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($page->judul . ' – ' . url()->current()) }}"
               target="_blank" rel="noopener" class="btn-share btn-wa">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <button class="btn-share btn-copy" id="btnCopy" onclick="copyLink()">
                <i class="fas fa-link"></i> Salin Tautan
            </button>
        </div>
    </div>

    <a href="/" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
</div>

<footer class="footer">
    <strong>&copy; {{ date('Y') }} Universitas Muhammadiyah Kendari</strong>
    &mdash; Sistem Informasi Kuliah Kerja Amaliah (KKA)
</footer>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        var btn = document.getElementById('btnCopy');
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        setTimeout(function() {
            btn.classList.remove('copied');
            btn.innerHTML = '<i class="fas fa-link"></i> Salin Tautan';
        }, 2500);
    });
}
</script>
</body>
</html>
