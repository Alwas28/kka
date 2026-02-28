<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem KKA – Universitas Mulawarman Kendari</title>
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
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .navbar-brand img {
            height: 38px;
            object-fit: contain;
        }
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
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
        Daftarkan diri sekarang dan mulai perjalanan pengabdian masyarakat bersama Universitas Mulawarman Kendari.
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
