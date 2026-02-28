@extends('layouts.users')

@section('css')
<style>
    .dashboard-content { padding: 24px; }

    .breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; color: var(--text-secondary); margin-bottom: 20px; flex-wrap: wrap;
    }
    .breadcrumb a { color: var(--maroon-main); text-decoration: none; font-weight: 600; }
    .breadcrumb a:hover { text-decoration: underline; }

    .kegiatan-header {
        background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
        border-radius: 14px; padding: 22px 26px; margin-bottom: 24px; color: white;
        box-shadow: 0 6px 20px rgba(165,42,42,.25);
        display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
    }
    .kegiatan-icon {
        width: 52px; height: 52px; border-radius: 13px; flex-shrink: 0;
        background: rgba(255,255,255,.2); border: 2px solid rgba(255,255,255,.35);
        display: flex; align-items: center; justify-content: center; font-size: 22px;
    }
    .kegiatan-header-info h2 { font-size: 17px; font-weight: 700; margin: 0 0 4px; }
    .kegiatan-header-info p  { font-size: 12px; margin: 0; opacity: .85; }
    .kegiatan-header-stats { margin-left: auto; display: flex; gap: 24px; flex-wrap: wrap; }
    .header-stat { text-align: center; }
    .header-stat .val { font-size: 22px; font-weight: 800; display: block; }
    .header-stat .lbl { font-size: 10px; opacity: .75; }

    /* Komponen info */
    .komponen-bar {
        background: white; border: 1px solid var(--gray-border); border-radius: 10px;
        padding: 12px 16px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .komponen-label { font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .4px; white-space: nowrap; }
    .komponen-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(165,42,42,.07); border: 1px solid rgba(165,42,42,.15);
        border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 600; color: var(--maroon-main);
    }

    /* Alert */
    .alert-success {
        padding: 11px 16px; border-radius: 8px; margin-bottom: 16px;
        background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;
        font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
    }

    /* Kelompok section */
    .kel-section {
        background: white; border-radius: 14px; border: 1px solid var(--gray-border);
        box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 24px; overflow: hidden;
    }
    .kel-section-header {
        padding: 16px 20px; background: var(--gray-light); border-bottom: 1px solid var(--gray-border);
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .kel-num {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        color: white; font-size: 18px; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
    }
    .kel-head-info h4 { font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0 0 2px; }
    .kel-head-info p  { font-size: 11px; color: var(--text-secondary); margin: 0; }
    .kel-head-stats { margin-left: auto; display: flex; gap: 20px; }
    .kel-stat-mini { text-align: center; }
    .kel-stat-mini .val { font-size: 16px; font-weight: 800; color: var(--text-primary); display: block; }
    .kel-stat-mini .lbl { font-size: 10px; color: var(--text-secondary); }

    .kel-section-body { padding: 20px; }

    /* Sub-section titles */
    .sub-title {
        font-size: 11px; font-weight: 700; color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: .5px; margin-top: 22px; margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .sub-title:first-child { margin-top: 0; }
    .sub-title i { color: var(--maroon-main); }
    .sub-title::after { content: ''; flex: 1; height: 1px; background: var(--gray-border); }

    /* Mahasiswa table */
    .mhs-tbl-wrap { overflow-x: auto; border-radius: 8px; border: 1px solid var(--gray-border); }
    .mhs-tbl { width: 100%; border-collapse: collapse; }
    .mhs-tbl th {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
        color: var(--text-secondary); padding: 9px 12px;
        background: var(--gray-light); border-bottom: 1px solid var(--gray-border); text-align: left;
        white-space: nowrap;
    }
    .mhs-tbl td {
        font-size: 12px; padding: 10px 12px;
        border-bottom: 1px solid rgba(0,0,0,.04); vertical-align: middle;
    }
    .mhs-tbl tbody tr:last-child td { border-bottom: none; }
    .mhs-tbl tbody tr:hover td { background: rgba(165,42,42,.02); }
    .mhs-tbl th.center, .mhs-tbl td.center { text-align: center; }

    .mhs-cell { display: flex; align-items: center; gap: 8px; }
    .mhs-av {
        width: 30px; height: 30px; border-radius: 7px; flex-shrink: 0;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white; font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
    }
    .mhs-av.koord { background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main)); }
    .mhs-nama { font-weight: 600; color: var(--text-primary); font-size: 12px; }
    .mhs-nim  { font-size: 10px; color: var(--text-secondary); }
    .badge-koord {
        font-size: 9px; font-weight: 700; padding: 1px 6px; border-radius: 10px;
        background: rgba(165,42,42,.1); color: var(--maroon-main);
    }
    .logbook-badge {
        display: inline-flex; align-items: center; gap: 3px;
        background: rgba(16,185,129,.1); color: #059669;
        border-radius: 12px; padding: 2px 8px; font-size: 10px; font-weight: 700;
    }
    .laporan-link {
        display: inline-flex; align-items: center; gap: 3px;
        color: var(--maroon-main); font-size: 11px; font-weight: 600; text-decoration: none;
    }
    .laporan-link:hover { text-decoration: underline; }

    .btn-profil {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 6px;
        border: 1px solid var(--gray-border); background: white;
        font-size: 11px; font-weight: 600; color: var(--text-secondary);
        text-decoration: none; transition: all .15s;
    }
    .btn-profil:hover { border-color: var(--maroon-main); color: var(--maroon-main); }

    /* Laporan kelompok */
    .laporan-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 10px; }
    .laporan-card {
        display: flex; align-items: center; gap: 12px;
        border: 1px solid var(--gray-border); border-radius: 10px;
        padding: 12px 14px; background: var(--gray-light);
    }
    .laporan-icon {
        width: 38px; height: 38px; border-radius: 9px; flex-shrink: 0;
        background: rgba(165,42,42,.1); color: var(--maroon-main);
        display: flex; align-items: center; justify-content: center; font-size: 16px;
    }
    .laporan-info { flex: 1; min-width: 0; }
    .laporan-name { font-size: 12px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .laporan-meta { font-size: 10px; color: var(--text-secondary); margin-top: 2px; }
    .btn-download {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 10px; border-radius: 7px; flex-shrink: 0;
        border: 1px solid var(--maroon-main); background: rgba(165,42,42,.06);
        font-size: 11px; font-weight: 600; color: var(--maroon-main);
        text-decoration: none; transition: all .15s;
    }
    .btn-download:hover { background: var(--maroon-main); color: white; }
    .no-laporan {
        padding: 14px 16px; font-size: 12px; color: var(--text-secondary);
        background: var(--gray-light); border-radius: 8px;
    }

    /* Penilaian */
    .nilai-tbl-wrap { overflow-x: auto; border-radius: 8px; border: 1px solid var(--gray-border); }
    .nilai-tbl { width: 100%; border-collapse: collapse; min-width: 680px; }
    .nilai-tbl th {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
        color: var(--text-secondary); padding: 9px 10px;
        background: var(--gray-light); border-bottom: 1px solid var(--gray-border);
        text-align: center; white-space: nowrap;
    }
    .nilai-tbl th:first-child { text-align: left; }
    .nilai-tbl td {
        font-size: 12px; padding: 8px 10px;
        border-bottom: 1px solid rgba(0,0,0,.05); vertical-align: middle; text-align: center;
    }
    .nilai-tbl td:first-child { text-align: left; }
    .nilai-tbl tbody tr:last-child td { border-bottom: none; }
    .nilai-input {
        width: 70px; padding: 5px 8px; border: 1px solid var(--gray-border); border-radius: 6px;
        font-size: 12px; text-align: center; font-family: inherit; background: white;
        transition: border-color .15s;
    }
    .nilai-input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 2px rgba(165,42,42,.1); }
    .nilai-akhir-display {
        display: inline-block; min-width: 52px; padding: 4px 10px; border-radius: 6px;
        background: var(--gray-light); border: 1px solid var(--gray-border);
        font-size: 13px; font-weight: 700; color: var(--text-primary);
    }
    .grade-badge {
        display: inline-block; min-width: 34px; padding: 3px 8px; border-radius: 6px;
        font-size: 12px; font-weight: 800;
        background: var(--gray-light); color: var(--text-secondary);
    }
    .grade-badge.a   { background: #d1fae5; color: #059669; }
    .grade-badge.ab  { background: #dbeafe; color: #2563eb; }
    .grade-badge.b   { background: #e0f2fe; color: #0284c7; }
    .grade-badge.bc  { background: #fef3c7; color: #d97706; }
    .grade-badge.c   { background: #fef9c3; color: #ca8a04; }
    .grade-badge.d   { background: #fee2e2; color: #dc2626; }
    .grade-badge.e   { background: #fecaca; color: #b91c1c; }
    .catatan-input {
        width: 100%; min-width: 130px; padding: 5px 8px;
        border: 1px solid var(--gray-border); border-radius: 6px;
        font-size: 11px; font-family: inherit; resize: vertical; min-height: 38px;
    }
    .catatan-input:focus { outline: none; border-color: var(--maroon-main); }
    .btn-save-nilai {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 8px; margin-top: 14px;
        background: var(--maroon-main); color: white; border: none;
        font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .15s;
    }
    .btn-save-nilai:hover { background: var(--maroon-dark); }

    /* Nilai locked / read-only */
    .nilai-locked-banner {
        display: flex; align-items: center; gap: 12px;
        background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
        padding: 13px 16px; margin-bottom: 14px;
    }
    .nilai-locked-banner i { font-size: 20px; color: #dc2626; flex-shrink: 0; }
    .nilai-locked-banner .lock-title { font-size: 13px; font-weight: 700; color: #991b1b; }
    .nilai-locked-banner .lock-sub   { font-size: 11px; color: #b91c1c; margin-top: 2px; }
    .nilai-input[readonly] {
        background: var(--gray-light); color: var(--text-secondary); cursor: not-allowed;
    }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('dosen-pembimbing.index') }}">
            <i class="fas fa-user-graduate"></i> Dosen Pembimbing
        </a>
        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
        <span>{{ $kegiatan->nama }}</span>
    </div>

    {{-- Header kegiatan --}}
    @php
        $totalMhsAll  = $pesertaByKelompok->map(fn($c) => $c->count())->sum();
        $totalLogAll  = $logbookTotalByKel->sum();
    @endphp
    <div class="kegiatan-header">
        <div class="kegiatan-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="kegiatan-header-info">
            <h2>{{ $kegiatan->nama }}</h2>
            <p>
                <i class="fas fa-calendar" style="font-size:10px;"></i>
                {{ \Carbon\Carbon::parse($kegiatan->kegiatan_mulai)->format('d M Y') }}
                &ndash;
                {{ \Carbon\Carbon::parse($kegiatan->kegiatan_selesai)->format('d M Y') }}
                &nbsp;&bull;&nbsp;
                <i class="fas fa-chalkboard-teacher" style="font-size:10px;"></i>
                {{ $pegawai->nama }}
            </p>
        </div>
        <div class="kegiatan-header-stats">
            <div class="header-stat">
                <span class="val">{{ $kelompokList->count() }}</span>
                <span class="lbl">Kelompok</span>
            </div>
            <div class="header-stat">
                <span class="val">{{ $totalMhsAll }}</span>
                <span class="lbl">Mahasiswa</span>
            </div>
            <div class="header-stat">
                <span class="val">{{ $totalLogAll }}</span>
                <span class="lbl">Logbook</span>
            </div>
        </div>
    </div>

    {{-- Komponen Penilaian info --}}
    @if($komponenPenilaian->isNotEmpty())
    <div class="komponen-bar">
        <span class="komponen-label"><i class="fas fa-sliders-h" style="margin-right:5px;"></i>Komponen Penilaian</span>
        @foreach($komponenPenilaian as $kp)
        <span class="komponen-tag">
            {{ $kp->nama }} &mdash; <strong>{{ $kp->persentase }}%</strong>
        </span>
        @endforeach
    </div>
    @endif

    {{-- Flash success --}}
    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if($kelompokList->isEmpty())
    <div class="empty-state">
        <i class="fas fa-map-marked-alt"></i>
        <h3>Tidak ada kelompok</h3>
        <p>Anda belum ditugaskan ke kelompok manapun dalam kegiatan ini.</p>
    </div>
    @else

    @foreach($kelompokList as $kel)
    @php
        $pesertaKel      = $pesertaByKelompok->get($kel->survey_id, collect());
        $logbookMhs      = $logbookPerMhs->get($kel->survey_id, collect());
        $logbookTotal    = $logbookTotalByKel->get($kel->survey_id, 0);
        $laporanAkhir    = $laporanAkhirByKelompok->get($kel->survey_id, collect());
        $nilaiKompoKel   = $nilaiKomponenBySurvey[$kel->survey_id] ?? [];
        $nilaiAkhirKel   = $nilaiAkhirByKelompok->get($kel->survey_id, collect());
    @endphp

    <div class="kel-section">
        {{-- Kelompok header --}}
        <div class="kel-section-header">
            <div class="kel-num">{{ $kel->kelompok }}</div>
            <div class="kel-head-info">
                <h4>Kelompok {{ $kel->kelompok }}</h4>
                <p>
                    @if($kel->desa)
                        <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                        {{ $kel->desa }}{{ $kel->kecamatan ? ', ' . $kel->kecamatan : '' }}
                    @else
                        Lokasi belum diatur
                    @endif
                </p>
            </div>
            <div class="kel-head-stats">
                <div class="kel-stat-mini">
                    <span class="val">{{ $pesertaKel->count() }}</span>
                    <span class="lbl">Mahasiswa</span>
                </div>
                <div class="kel-stat-mini">
                    <span class="val">{{ $logbookTotal }}</span>
                    <span class="lbl">Logbook</span>
                </div>
                <div class="kel-stat-mini">
                    <span class="val">{{ $laporanAkhir->count() }}</span>
                    <span class="lbl">Laporan</span>
                </div>
            </div>
        </div>

        <div class="kel-section-body">

            {{-- 1. Daftar Mahasiswa --}}
            <div class="sub-title"><i class="fas fa-users"></i> Daftar Mahasiswa</div>

            @if($pesertaKel->isNotEmpty())
            <div class="mhs-tbl-wrap">
                <table class="mhs-tbl">
                    <thead>
                        <tr>
                            <th style="width:34px;">No</th>
                            <th>Nama / NIM</th>
                            <th>Program Studi</th>
                            <th class="center">Logbook</th>
                            <th>Laporan Individu</th>
                            <th class="center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesertaKel as $idx => $mhs)
                        @php
                            $logCnt = $logbookMhs->get($mhs->mahasiswa_id, 0);
                            $lapKey = $kel->survey_id . '_' . $mhs->mahasiswa_id;
                            $lapInd = $laporanIndividuByMhs->get($lapKey, collect());
                        @endphp
                        <tr>
                            <td style="color:var(--text-secondary);">{{ $idx + 1 }}</td>
                            <td>
                                <div class="mhs-cell">
                                    <div class="mhs-av {{ $mhs->is_koordinator ? 'koord' : '' }}">
                                        {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="mhs-nama">
                                            {{ $mhs->nama }}
                                            @if($mhs->is_koordinator)
                                                <span class="badge-koord">Koord.</span>
                                            @endif
                                        </div>
                                        <div class="mhs-nim">{{ $mhs->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:11px; color:var(--text-secondary);">{{ $mhs->prodi ?? '-' }}</td>
                            <td class="center">
                                <span class="logbook-badge">
                                    <i class="fas fa-book" style="font-size:9px;"></i> {{ $logCnt }}
                                </span>
                            </td>
                            <td>
                                @if($lapInd->isNotEmpty())
                                    @foreach($lapInd as $lap)
                                    <a href="{{ asset('storage/' . $lap->file_path) }}" target="_blank" class="laporan-link">
                                        <i class="fas fa-file-alt" style="font-size:10px;"></i>
                                        {{ $lap->dokumen_nama ?? $lap->file_name }}
                                    </a>
                                    @if(!$loop->last)<br>@endif
                                    @endforeach
                                @else
                                    <span style="font-size:11px; color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                            <td class="center">
                                <a href="{{ route('mahasiswa.profil', $mhs->mahasiswa_id) }}?survey_lokasi_id={{ $kel->survey_id }}"
                                   class="btn-profil">
                                    <i class="fas fa-id-card"></i> Profil
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="font-size:12px; color:var(--text-secondary); padding:6px 0;">Belum ada peserta di kelompok ini.</p>
            @endif

            {{-- 2. Laporan Kelompok --}}
            <div class="sub-title"><i class="fas fa-file-archive"></i> Laporan Kelompok</div>

            @if($laporanAkhir->isNotEmpty())
            <div class="laporan-grid">
                @foreach($laporanAkhir as $lap)
                @php
                    $ext  = strtolower(pathinfo($lap->file_name ?? '', PATHINFO_EXTENSION));
                    $icon = $ext === 'pdf' ? 'fa-file-pdf' : (in_array($ext, ['doc','docx']) ? 'fa-file-word' : 'fa-file-alt');
                    $sizeKb = $lap->file_size ? round($lap->file_size / 1024) . ' KB' : '';
                @endphp
                <div class="laporan-card">
                    <div class="laporan-icon"><i class="fas {{ $icon }}"></i></div>
                    <div class="laporan-info">
                        <div class="laporan-name" title="{{ $lap->file_name }}">
                            {{ $lap->dokumen_nama ?? $lap->file_name }}
                        </div>
                        <div class="laporan-meta">
                            {{ $lap->koordinator_nama }}
                            @if($sizeKb) &bull; {{ $sizeKb }} @endif
                            @if($lap->uploaded_at) &bull; {{ \Carbon\Carbon::parse($lap->uploaded_at)->format('d M Y') }} @endif
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $lap->file_path) }}" target="_blank" class="btn-download">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="no-laporan">
                <i class="fas fa-folder-open" style="margin-right:6px;"></i>
                Belum ada laporan yang diunggah koordinator untuk kelompok ini.
            </div>
            @endif

            {{-- 3. Penilaian --}}
            @if($pesertaKel->isNotEmpty())
            <div class="sub-title"><i class="fas fa-star-half-alt"></i> Penilaian Mahasiswa</div>

            {{-- Banner: periode ditutup --}}
            @if(!$nilaiTerbuka)
            <div class="nilai-locked-banner">
                <i class="fas fa-lock"></i>
                <div>
                    <div class="lock-title">Periode penilaian telah berakhir</div>
                    <div class="lock-sub">
                        Batas akhir pelaporan:
                        {{ \Carbon\Carbon::parse($tahapanPelaporan->selesai)->translatedFormat('d F Y') }}.
                        Nilai tidak dapat diubah.
                    </div>
                </div>
            </div>
            @elseif($tahapanPelaporan && $tahapanPelaporan->selesai)
            <div style="font-size:11px; color:var(--text-secondary); margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-clock" style="color:var(--maroon-main);"></i>
                Batas akhir penilaian:
                <strong>{{ \Carbon\Carbon::parse($tahapanPelaporan->selesai)->translatedFormat('d F Y') }}</strong>
                ({{ \Carbon\Carbon::parse($tahapanPelaporan->selesai)->diffForHumans() }})
            </div>
            @endif

            {{-- Tabel nilai dinamis berdasarkan komponenPenilaian --}}
            @if($nilaiTerbuka)
            <form method="POST" action="{{ route('dosen-pembimbing.nilai', $kegiatan->id) }}">
                @csrf
            @endif

            <div class="nilai-tbl-wrap">
                <table class="nilai-tbl">
                    <thead>
                        <tr>
                            <th style="text-align:left; min-width:160px;">Mahasiswa</th>
                            @foreach($komponenPenilaian as $kp)
                            <th>
                                {{ $kp->nama }}<br>
                                <span style="font-weight:400;font-size:9px;opacity:.8;">{{ $kp->persentase }}%</span>
                            </th>
                            @endforeach
                            <th>Nilai Akhir</th>
                            <th>Grade</th>
                            <th style="min-width:140px;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesertaKel as $i => $mhs)
                        @php
                            $mhsNilaiKompo = $nilaiKompoKel[$mhs->mahasiswa_id] ?? [];
                            $nv     = $nilaiAkhirKel->get($mhs->mahasiswa_id);
                            $naVal  = $nv->nilai_akhir ?? null;
                            $gradeStr = '-';
                            if ($naVal !== null) {
                                foreach ($gradeTable as $g) {
                                    if ($naVal >= $g->nilai_min && $naVal <= $g->nilai_max) {
                                        $gradeStr = $g->grade; break;
                                    }
                                }
                            }
                            $readonly = $nilaiTerbuka ? '' : 'readonly';
                            $rowKey   = $kel->survey_id . '_' . $i;
                        @endphp
                        <tr class="nilai-row"
                            data-survey="{{ $kel->survey_id }}"
                            data-mhs="{{ $mhs->mahasiswa_id }}">
                            <td>
                                <div style="font-weight:600;font-size:12px;">{{ $mhs->nama }}</div>
                                <div style="font-size:10px;color:var(--text-secondary);">{{ $mhs->nim }}</div>
                                @if($nilaiTerbuka)
                                <input type="hidden" name="nilai[{{ $rowKey }}][mahasiswa_id]"     value="{{ $mhs->mahasiswa_id }}">
                                <input type="hidden" name="nilai[{{ $rowKey }}][survey_lokasi_id]" value="{{ $kel->survey_id }}">
                                @endif
                            </td>
                            @foreach($komponenPenilaian as $kp)
                            <td>
                                <input type="number" min="0" max="100" step="0.01"
                                    @if($nilaiTerbuka)
                                        name="nilai[{{ $rowKey }}][komponen][{{ $kp->id }}]"
                                        oninput="hitungAkhir(this.closest('.nilai-row'))"
                                    @endif
                                    class="nilai-input"
                                    data-komponen="{{ $kp->id }}"
                                    data-persentase="{{ $kp->persentase }}"
                                    value="{{ $mhsNilaiKompo[$kp->id] ?? '' }}"
                                    {{ $readonly }}>
                            </td>
                            @endforeach
                            <td>
                                <span class="nilai-akhir-display">
                                    {{ $naVal !== null ? number_format($naVal, 2) : '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="grade-badge {{ strtolower(str_replace(['+','-'], ['p','m'], $gradeStr)) }}">{{ $gradeStr }}</span>
                            </td>
                            <td>
                                @if($nilaiTerbuka)
                                <textarea class="catatan-input"
                                    name="nilai[{{ $rowKey }}][catatan]"
                                    placeholder="Catatan...">{{ $nv->catatan ?? '' }}</textarea>
                                @else
                                <span style="font-size:11px; color:var(--text-secondary);">
                                    {{ $nv->catatan ?? '—' }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($nilaiTerbuka)
                <button type="submit" class="btn-save-nilai">
                    <i class="fas fa-save"></i> Simpan Nilai Kelompok {{ $kel->kelompok }}
                </button>
            </form>
            @endif
            @endif

        </div>{{-- /.kel-section-body --}}
    </div>{{-- /.kel-section --}}
    @endforeach

    @endif

</div>

@endsection

@section('js')
<script>
const gradeData = @json($gradeTable->values());

function hitungAkhir(rowEl) {
    // Ambil semua input komponen dalam row ini
    const inputs = rowEl.querySelectorAll('.nilai-input[data-komponen]');
    let totalBobot    = 0;
    let nilaiTertimbang = 0;
    let adaIsi = false;

    inputs.forEach(inp => {
        const persentase = parseFloat(inp.dataset.persentase) || 0;
        const val = inp.value !== '' ? parseFloat(inp.value) : null;
        if (val !== null) {
            nilaiTertimbang += val * (persentase / 100);
            totalBobot      += persentase;
            adaIsi = true;
        }
    });

    let nilai_akhir = null;
    if (adaIsi && totalBobot > 0) {
        // Normalisasi jika tidak semua komponen diisi
        nilai_akhir = totalBobot < 100
            ? (nilaiTertimbang / totalBobot * 100)
            : nilaiTertimbang;
    }

    const dispEl     = rowEl.querySelector('.nilai-akhir-display');
    const gradeBadge = rowEl.querySelector('.grade-badge');

    if (nilai_akhir !== null) {
        if (dispEl) dispEl.textContent = nilai_akhir.toFixed(2);
        updateGrade(gradeBadge, nilai_akhir);
    } else {
        if (dispEl) dispEl.textContent = '—';
        if (gradeBadge) { gradeBadge.textContent = '-'; gradeBadge.className = 'grade-badge'; }
    }
}

function updateGrade(badgeEl, nilai) {
    if (!badgeEl) return;
    for (const g of gradeData) {
        if (nilai >= g.nilai_min && nilai <= g.nilai_max) {
            badgeEl.textContent = g.grade;
            badgeEl.className   = 'grade-badge ' + g.grade.toLowerCase().replace('+','p').replace('-','m');
            return;
        }
    }
    badgeEl.textContent = '-';
    badgeEl.className   = 'grade-badge';
}
</script>
@endsection
