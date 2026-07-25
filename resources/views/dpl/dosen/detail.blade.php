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

    .kelompok-header {
        background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
        border-radius: 14px; padding: 22px 26px; margin-bottom: 20px; color: white;
        box-shadow: 0 6px 20px rgba(165,42,42,.25);
        display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
    }
    .kel-num-lg {
        width: 52px; height: 52px; border-radius: 13px; flex-shrink: 0;
        background: rgba(255,255,255,.2); border: 2px solid rgba(255,255,255,.35);
        display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;
    }
    .kelompok-header-info h2 { font-size: 17px; font-weight: 700; margin: 0 0 4px; }
    .kelompok-header-info p  { font-size: 12px; margin: 0; opacity: .85; }
    .kelompok-header-stats { margin-left: auto; display: flex; gap: 24px; flex-wrap: wrap; }
    .header-stat { text-align: center; }
    .header-stat .val { font-size: 22px; font-weight: 800; display: block; }
    .header-stat .lbl { font-size: 10px; opacity: .75; }

    /* Alert */
    .alert-success {
        padding: 11px 16px; border-radius: 8px; margin-bottom: 16px;
        background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;
        font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;
    }

    /* Tabs */
    .kel-tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--gray-border); margin-bottom: 20px; flex-wrap: wrap; }
    .kel-tab-btn {
        display: flex; align-items: center; gap: 7px;
        padding: 11px 18px; border: none; background: none; cursor: pointer;
        font-size: 13px; font-weight: 600; color: var(--text-secondary); font-family: inherit;
        border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .15s;
    }
    .kel-tab-btn:hover { color: var(--maroon-main); }
    .kel-tab-btn.active { color: var(--maroon-main); border-bottom-color: var(--maroon-main); }
    .kel-tab-pane { display: none; }
    .kel-tab-pane.active { display: block; }

    .card-box {
        background: white; border-radius: 14px; border: 1px solid var(--gray-border);
        box-shadow: 0 2px 8px rgba(0,0,0,.06); padding: 20px; margin-bottom: 20px;
    }

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

    /* Komponen info */
    .komponen-bar {
        background: var(--gray-light); border: 1px solid var(--gray-border); border-radius: 10px;
        padding: 12px 16px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .komponen-label { font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .4px; white-space: nowrap; }
    .komponen-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(165,42,42,.07); border: 1px solid rgba(165,42,42,.15);
        border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 600; color: var(--maroon-main);
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

    /* Info Lokasi */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px 24px; }
    .info-item .info-label {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
        color: var(--text-secondary); margin-bottom: 4px;
    }
    .info-item .info-value { font-size: 13px; color: var(--text-primary); font-weight: 500; line-height: 1.5; }
    .info-item .info-value.muted { color: var(--text-secondary); font-weight: 400; }
    .info-full { grid-column: 1 / -1; }
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .status-pill.ok   { background: #d1fae5; color: #059669; }
    .status-pill.no   { background: #fee2e2; color: #dc2626; }
    .status-pill.mid  { background: #fef3c7; color: #d97706; }
    .gmaps-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--maroon-main); font-weight: 600; text-decoration: none; font-size: 13px;
    }
    .gmaps-link:hover { text-decoration: underline; }

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
        <span>Kelompok {{ $survey->kelompok }}</span>
    </div>

    {{-- Header kelompok --}}
    <div class="kelompok-header">
        <div class="kel-num-lg">{{ $survey->kelompok }}</div>
        <div class="kelompok-header-info">
            <h2>Kelompok {{ $survey->kelompok }} &mdash; {{ $kegiatan->nama }}</h2>
            <p>
                <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                {{ $survey->lokasi_lengkap }}
                &nbsp;&bull;&nbsp;
                <i class="fas fa-chalkboard-teacher" style="font-size:10px;"></i>
                {{ $pegawai->nama }}
            </p>
        </div>
        <div class="kelompok-header-stats">
            <div class="header-stat">
                <span class="val">{{ $peserta->count() }}</span>
                <span class="lbl">Mahasiswa</span>
            </div>
            <div class="header-stat">
                <span class="val">{{ $logbookPerMhs->sum() }}</span>
                <span class="lbl">Logbook</span>
            </div>
        </div>
    </div>

    {{-- Flash success --}}
    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="kel-tabs">
        <button type="button" class="kel-tab-btn active" onclick="switchKelTab('peserta', this)">
            <i class="fas fa-users"></i> Peserta
        </button>
        <button type="button" class="kel-tab-btn" onclick="switchKelTab('nilai', this)">
            <i class="fas fa-star-half-alt"></i> Nilai
        </button>
        <button type="button" class="kel-tab-btn" onclick="switchKelTab('lokasi', this)">
            <i class="fas fa-map-marked-alt"></i> Info Lokasi
        </button>
    </div>

    {{-- TAB: PESERTA --}}
    <div class="kel-tab-pane active" id="kel-tab-peserta">
        <div class="card-box">
            <div class="sub-title"><i class="fas fa-users"></i> Daftar Mahasiswa</div>

            @if($peserta->isNotEmpty())
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
                        @foreach($peserta as $idx => $mhs)
                        @php
                            $logCnt = $logbookPerMhs->get($mhs->mahasiswa_id, 0);
                            $lapInd = $laporanIndividuByMhs->get($mhs->mahasiswa_id, collect());
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
                                    <span style="font-size:11px; color:var(--text-secondary);">&mdash;</span>
                                @endif
                            </td>
                            <td class="center">
                                <a href="{{ route('mahasiswa.profil', $mhs->mahasiswa_id) }}?survey_lokasi_id={{ $survey->id }}"
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
        </div>
    </div>

    {{-- TAB: NILAI --}}
    <div class="kel-tab-pane" id="kel-tab-nilai">
        <div class="card-box">
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

            @if($peserta->isEmpty())
            <p style="font-size:12px; color:var(--text-secondary);">Belum ada peserta di kelompok ini untuk dinilai.</p>
            @else

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

            @if($nilaiTerbuka)
            <form method="POST" action="{{ route('dosen-pembimbing.nilai', $survey->id) }}">
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
                        @foreach($peserta as $i => $mhs)
                        @php
                            $mhsNilaiKompo = $nilaiKomponenByMhs[$mhs->mahasiswa_id] ?? [];
                            $nv     = $nilaiAkhirByMhs->get($mhs->mahasiswa_id);
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
                        @endphp
                        <tr class="nilai-row" data-mhs="{{ $mhs->mahasiswa_id }}">
                            <td>
                                <div style="font-weight:600;font-size:12px;">{{ $mhs->nama }}</div>
                                <div style="font-size:10px;color:var(--text-secondary);">{{ $mhs->nim }}</div>
                                @if($nilaiTerbuka)
                                <input type="hidden" name="nilai[{{ $i }}][mahasiswa_id]" value="{{ $mhs->mahasiswa_id }}">
                                @endif
                            </td>
                            @foreach($komponenPenilaian as $kp)
                            <td>
                                <input type="number" min="0" max="100" step="0.01"
                                    @if($nilaiTerbuka)
                                        name="nilai[{{ $i }}][komponen][{{ $kp->id }}]"
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
                                    name="nilai[{{ $i }}][catatan]"
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
                    <i class="fas fa-save"></i> Simpan Nilai Kelompok {{ $survey->kelompok }}
                </button>
            </form>
            @endif
            @endif
        </div>
    </div>

    {{-- TAB: INFO LOKASI --}}
    <div class="kel-tab-pane" id="kel-tab-lokasi">
        <div class="card-box">
            <div class="sub-title"><i class="fas fa-map-marked-alt"></i> Data Lokasi Survey</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Lokasi</div>
                    <div class="info-value">{{ $survey->lokasi_lengkap }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status Survey</div>
                    <div class="info-value">
                        @php
                            $statusMap = [
                                'belum_survey' => ['Belum Survey', 'no'],
                                'sudah_survey' => ['Sudah Survey', 'mid'],
                                'disetujui'    => ['Disetujui', 'ok'],
                                'ditolak'      => ['Ditolak', 'no'],
                            ];
                            [$statusLbl, $statusClass] = $statusMap[$survey->status] ?? [$survey->status, 'mid'];
                        @endphp
                        <span class="status-pill {{ $statusClass }}">{{ $statusLbl }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nama Kepala Desa</div>
                    <div class="info-value {{ $survey->nama_kades ? '' : 'muted' }}">{{ $survey->nama_kades ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. HP Kepala Desa</div>
                    <div class="info-value {{ $survey->no_hp_kades ? '' : 'muted' }}">{{ $survey->no_hp_kades ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pemberi Informasi</div>
                    <div class="info-value {{ $survey->pemberi_informasi ? '' : 'muted' }}">{{ $survey->pemberi_informasi ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Rencana Posko</div>
                    <div class="info-value {{ $survey->rencana_posko ? '' : 'muted' }}">
                        @php
                            $poskoMap = ['rumah_kades' => 'Rumah Kepala Desa', 'rumah_warga' => 'Rumah Warga', 'lainnya' => 'Lainnya'];
                        @endphp
                        {{ $survey->rencana_posko ? ($poskoMap[$survey->rencana_posko] ?? $survey->rencana_posko) : '-' }}
                        @if($survey->rencana_posko === 'lainnya' && $survey->rencana_posko_lainnya)
                            &mdash; {{ $survey->rencana_posko_lainnya }}
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tim Survey</div>
                    <div class="info-value {{ $survey->tim_anggota ? '' : 'muted' }}" style="white-space:pre-line;">{{ $survey->tim_anggota ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Google Maps</div>
                    <div class="info-value">
                        @if($survey->gmaps_url)
                        <a href="{{ $survey->gmaps_url }}" target="_blank" class="gmaps-link">
                            <i class="fas fa-map-marker-alt"></i> Buka Lokasi
                        </a>
                        @else
                        <span class="muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kondisi Air</div>
                    <div class="info-value {{ $survey->kondisi_air ? '' : 'muted' }}">{{ $survey->kondisi_air ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kondisi Listrik</div>
                    <div class="info-value {{ $survey->kondisi_listrik ? '' : 'muted' }}">{{ $survey->kondisi_listrik ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kondisi Transportasi</div>
                    <div class="info-value {{ $survey->kondisi_transportasi ? '' : 'muted' }}">{{ $survey->kondisi_transportasi ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Rekomendasi Surveyor</div>
                    <div class="info-value">
                        @if($survey->rekomendasi === null)
                            <span class="muted">-</span>
                        @elseif($survey->rekomendasi)
                            <span class="status-pill ok"><i class="fas fa-check"></i> Direkomendasikan</span>
                        @else
                            <span class="status-pill no"><i class="fas fa-times"></i> Tidak Direkomendasikan</span>
                        @endif
                    </div>
                </div>
                <div class="info-item info-full">
                    <div class="info-label">Deskripsi Lokasi</div>
                    <div class="info-value {{ $survey->deskripsi ? '' : 'muted' }}">{{ $survey->deskripsi ?? '-' }}</div>
                </div>
                <div class="info-item info-full">
                    <div class="info-label">Alasan Rekomendasi</div>
                    <div class="info-value {{ $survey->alasan_rekomendasi ? '' : 'muted' }}">{{ $survey->alasan_rekomendasi ?? '-' }}</div>
                </div>
            </div>

            <div class="sub-title"><i class="fas fa-user-check"></i> Persetujuan Panitia</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Status Persetujuan</div>
                    <div class="info-value">
                        @if($survey->disetujui === null)
                            <span class="status-pill mid">Belum Diproses</span>
                        @elseif($survey->disetujui)
                            <span class="status-pill ok"><i class="fas fa-check"></i> Disetujui</span>
                        @else
                            <span class="status-pill no"><i class="fas fa-times"></i> Ditolak</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Survey</div>
                    <div class="info-value {{ $survey->surveyed_at ? '' : 'muted' }}">
                        {{ $survey->surveyed_at ? $survey->surveyed_at->translatedFormat('d F Y, H:i') : '-' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Persetujuan</div>
                    <div class="info-value {{ $survey->approved_at ? '' : 'muted' }}">
                        {{ $survey->approved_at ? $survey->approved_at->translatedFormat('d F Y, H:i') : '-' }}
                    </div>
                </div>
                <div class="info-item info-full">
                    <div class="info-label">Catatan Panitia</div>
                    <div class="info-value {{ $survey->catatan_panitia ? '' : 'muted' }}">{{ $survey->catatan_panitia ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script>
function switchKelTab(tabName, btn) {
    document.querySelectorAll('.kel-tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.kel-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('kel-tab-' + tabName)?.classList.add('active');
    btn.classList.add('active');
}

const gradeData = @json($gradeTable->values());

function hitungAkhir(rowEl) {
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
