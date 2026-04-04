<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>{{ $pengumuman->judul }} – KKA UM Kendari</title>
    <meta name="description" content="{{ Str::limit(strip_tags($pengumuman->konten), 160) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="article">
    <meta property="og:site_name"   content="KKA UM Kendari">
    <meta property="og:title"       content="{{ $pengumuman->judul }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($pengumuman->konten), 160) }}">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="{{ $pengumuman->gambar ? asset(Storage::url($pengumuman->gambar)) : asset('img/logo2.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="article:published_time" content="{{ $pengumuman->created_at->toIso8601String() }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $pengumuman->judul }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($pengumuman->konten), 160) }}">
    <meta name="twitter:image"       content="{{ $pengumuman->gambar ? asset(Storage::url($pengumuman->gambar)) : asset('img/logo2.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *  { margin:0; padding:0; box-sizing:border-box; }
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
        body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; background:#f9fafb; color:var(--text-primary); }

        /* Navbar */
        .navbar { position:sticky; top:0; z-index:100; background:rgba(255,255,255,.95); backdrop-filter:blur(10px); border-bottom:1px solid var(--gray-border); padding:0 40px; height:64px; display:flex; align-items:center; justify-content:space-between; }
        .navbar-brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .navbar-brand img { height:36px; }
        .navbar-actions { display:flex; gap:10px; align-items:center; }
        .btn-nav-outline { padding:8px 18px; border:1.5px solid var(--maroon-main); color:var(--maroon-main); border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; }
        .btn-nav-solid   { padding:8px 18px; background:var(--maroon-main); color:#fff; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; }

        /* Layout */
        .page-wrap { max-width:820px; margin:0 auto; padding:40px 24px 60px; }

        /* Breadcrumb */
        .breadcrumb { display:flex; align-items:center; gap:8px; font-size:13px; color:var(--text-secondary); margin-bottom:28px; flex-wrap:wrap; }
        .breadcrumb a { color:var(--maroon-main); text-decoration:none; }
        .breadcrumb a:hover { text-decoration:underline; }
        .breadcrumb i { font-size:10px; }

        /* Article header */
        .peng-badges { display:flex; gap:8px; align-items:center; margin-bottom:14px; flex-wrap:wrap; }
        .badge-peng-label { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:var(--maroon-main); background:rgba(165,42,42,.08); border:1px solid rgba(165,42,42,.15); padding:3px 12px; border-radius:20px; }
        .badge-penting { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:#b91c1c; background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); padding:3px 12px; border-radius:20px; }

        .article-title { font-size:clamp(1.4rem,3vw,2rem); font-weight:800; line-height:1.3; color:var(--text-primary); margin-bottom:16px; }
        .article-meta  { display:flex; align-items:center; gap:18px; flex-wrap:wrap; font-size:13px; color:var(--text-secondary); margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--gray-border); }
        .article-meta i { color:var(--maroon-main); margin-right:5px; }

        /* Period banner */
        .period-banner { background:linear-gradient(135deg,rgba(165,42,42,.06),rgba(165,42,42,.1)); border:1px solid rgba(165,42,42,.15); border-radius:10px; padding:14px 18px; margin-bottom:28px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; font-size:13px; }
        .period-banner i { color:var(--maroon-main); }

        /* Hero image */
        .article-hero { width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:32px; box-shadow:0 4px 24px rgba(0,0,0,.1); }

        /* Content */
        .article-content { font-size:1rem; line-height:1.85; color:var(--text-primary); }
        .article-content h1,.article-content h2,.article-content h3 { margin:28px 0 12px; font-weight:700; line-height:1.3; }
        .article-content h1 { font-size:1.6rem; }
        .article-content h2 { font-size:1.3rem; }
        .article-content h3 { font-size:1.1rem; }
        .article-content p  { margin-bottom:16px; }
        .article-content ul,.article-content ol { padding-left:24px; margin-bottom:16px; }
        .article-content li { margin-bottom:6px; }
        .article-content img { max-width:100%; height:auto; border-radius:10px; margin:16px 0; box-shadow:0 2px 12px rgba(0,0,0,.08); }
        .article-content a  { color:var(--maroon-main); text-decoration:underline; }
        .article-content blockquote { border-left:4px solid var(--maroon-lighter); padding:12px 20px; background:rgba(165,42,42,.04); margin:20px 0; border-radius:0 8px 8px 0; font-style:italic; }
        .article-content table { width:100%; border-collapse:collapse; margin-bottom:16px; font-size:.9rem; }
        .article-content th,.article-content td { border:1px solid var(--gray-border); padding:8px 12px; }
        .article-content th { background:var(--gray-light); font-weight:700; }

        /* Share section */
        .share-section { margin-top:40px; padding-top:24px; border-top:1px solid var(--gray-border); }
        .share-title { font-size:13px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.06em; margin-bottom:14px; }
        .share-buttons { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
        .btn-share { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:transform .15s,box-shadow .15s; font-family:inherit; }
        .btn-share:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.15); }
        .btn-fb  { background:#1877f2; color:#fff; }
        .btn-tw  { background:#000; color:#fff; }
        .btn-wa  { background:#25d366; color:#fff; }
        .btn-copy { background:white; color:var(--text-primary); border:1.5px solid var(--gray-border); }
        .btn-copy.copied { background:#10b981; color:#fff; border-color:#10b981; }

        /* Back button */
        .back-btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:1.5px solid var(--gray-border); border-radius:8px; font-size:13px; font-weight:600; color:var(--text-secondary); text-decoration:none; margin-top:32px; transition:all .2s; }
        .back-btn:hover { border-color:var(--maroon-main); color:var(--maroon-main); }

        /* Footer */
        .footer { background:#1f2937; color:#9ca3af; text-align:center; padding:24px 40px; font-size:0.83rem; }
        .footer strong { color:#e5e7eb; }

        @media(max-width:600px) { .navbar{padding:0 16px;} .page-wrap{padding:24px 16px 48px;} }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar">
    <a class="navbar-brand" href="/">
        <img src="{{ asset('img/logo2.png') }}" alt="UM Kendari">
    </a>
    <div class="navbar-actions">
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-nav-solid">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-nav-outline">Masuk</a>
            <a href="{{ route('mahasiswa.register') }}" class="btn-nav-solid">Daftar KKA</a>
        @endauth
    </div>
</nav>

<div class="page-wrap">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="/"><i class="fas fa-home"></i> Beranda</a>
        <i class="fas fa-chevron-right"></i>
        <span>Pengumuman</span>
        <i class="fas fa-chevron-right"></i>
        <span style="color:var(--text-primary);">{{ Str::limit($pengumuman->judul, 50) }}</span>
    </nav>

    {{-- Badges --}}
    <div class="peng-badges">
        <span class="badge-peng-label"><i class="fas fa-bullhorn"></i> Pengumuman</span>
        @if($pengumuman->is_penting)
        <span class="badge-penting"><i class="fas fa-exclamation-circle"></i> Penting</span>
        @endif
    </div>

    <h1 class="article-title">{{ $pengumuman->judul }}</h1>

    <div class="article-meta">
        <span><i class="fas fa-calendar-alt"></i> {{ $pengumuman->created_at->translatedFormat('d F Y') }}</span>
        @if($pengumuman->user)
        <span><i class="fas fa-user"></i> {{ $pengumuman->user->name }}</span>
        @endif
    </div>

    {{-- Period banner --}}
    <div class="period-banner">
        <i class="fas fa-clock"></i>
        <span>
            <strong>Berlaku:</strong>
            {{ $pengumuman->tanggal_mulai->translatedFormat('d F Y') }}
            @if($pengumuman->tanggal_selesai)
                – {{ $pengumuman->tanggal_selesai->translatedFormat('d F Y') }}
            @else
                – <em>Tidak terbatas</em>
            @endif
        </span>
    </div>

    {{-- Hero image --}}
    @if($pengumuman->gambar)
    <img src="{{ Storage::url($pengumuman->gambar) }}" alt="{{ $pengumuman->judul }}" class="article-hero">
    @endif

    {{-- Content --}}
    <div class="article-content">
        {!! $pengumuman->konten !!}
    </div>

    {{-- Share --}}
    <div class="share-section">
        <div class="share-title"><i class="fas fa-share-nodes"></i> Bagikan</div>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
               target="_blank" rel="noopener" class="btn-share btn-fb">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($pengumuman->judul) }}"
               target="_blank" rel="noopener" class="btn-share btn-tw">
                <i class="fab fa-x-twitter"></i> X / Twitter
            </a>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($pengumuman->judul . ' – ' . url()->current()) }}"
               target="_blank" rel="noopener" class="btn-share btn-wa">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <button class="btn-share btn-copy" id="btnCopy" onclick="copyLink()">
                <i class="fas fa-link"></i> Salin Tautan
            </button>
        </div>
    </div>

    <a href="/" class="back-btn">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

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
