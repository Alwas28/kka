@extends('layouts.users')

@section('css')
<style>
    .dashboard-content { padding: 24px; }

    /* Breadcrumb */
    .breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; color: var(--text-secondary); margin-bottom: 20px; flex-wrap: wrap;
    }
    .breadcrumb a { color: var(--maroon-main); text-decoration: none; font-weight: 600; }
    .breadcrumb a:hover { text-decoration: underline; }
    .breadcrumb i.sep { font-size: 9px; }

    /* Profile header */
    .profil-header {
        background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
        border-radius: 16px; padding: 24px 28px; margin-bottom: 16px; color: white;
        box-shadow: 0 6px 20px rgba(165,42,42,.25);
        display: flex; align-items: center; gap: 22px; flex-wrap: wrap;
    }
    .profil-avatar-lg {
        width: 72px; height: 72px; border-radius: 18px; flex-shrink: 0;
        background: rgba(255,255,255,.2); border: 3px solid rgba(255,255,255,.35);
        display: flex; align-items: center; justify-content: center;
        font-size: 30px; font-weight: 800;
    }
    .profil-main-info h2 { font-size: 20px; font-weight: 800; margin: 0 0 4px; }
    .profil-main-info .nim { font-size: 13px; opacity: .85; margin: 0 0 6px; }
    .profil-main-info .meta { display: flex; gap: 14px; flex-wrap: wrap; }
    .profil-meta-item { font-size: 12px; opacity: .85; display: flex; align-items: center; gap: 5px; }
    .profil-header-badges { margin-left: auto; display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
    .badge-white {
        background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
        border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-koord-hdr {
        background: rgba(255,215,0,.2); border: 1px solid rgba(255,215,0,.4);
        border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 700; color: #ffd700;
        display: inline-flex; align-items: center; gap: 5px;
    }

    /* Kegiatan switcher */
    .kegiatan-switcher {
        background: white; border: 1px solid var(--gray-border); border-radius: 12px;
        padding: 12px 16px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    }
    .switcher-label {
        font-size: 11px; font-weight: 700; color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: .4px; white-space: nowrap;
    }
    .switcher-pills { display: flex; gap: 6px; flex-wrap: wrap; }
    .switcher-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
        border: 1px solid var(--gray-border); background: var(--gray-light);
        color: var(--text-secondary); text-decoration: none; transition: all .15s;
    }
    .switcher-pill:hover { border-color: var(--maroon-main); color: var(--maroon-main); }
    .switcher-pill.active {
        background: var(--maroon-main); border-color: var(--maroon-main);
        color: white; box-shadow: 0 2px 8px rgba(165,42,42,.25);
    }
    .switcher-pill .pill-badge {
        font-size: 10px; background: rgba(255,255,255,.25); border-radius: 8px; padding: 0 5px;
    }
    .switcher-pill:not(.active) .pill-badge {
        background: rgba(0,0,0,.08); color: var(--text-secondary);
    }

    /* Tabs */
    .tab-nav {
        display: flex; gap: 2px; margin-bottom: 20px;
        background: white; border: 1px solid var(--gray-border);
        border-radius: 12px; padding: 5px; overflow-x: auto;
    }
    .tab-btn {
        display: flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 8px; border: none; cursor: pointer;
        font-size: 13px; font-weight: 600; font-family: inherit;
        color: var(--text-secondary); background: transparent; transition: all .15s;
        white-space: nowrap;
    }
    .tab-btn:hover { background: var(--gray-light); color: var(--text-primary); }
    .tab-btn.active {
        background: var(--maroon-main); color: white;
        box-shadow: 0 2px 8px rgba(165,42,42,.3);
    }
    .tab-btn .tab-count {
        background: rgba(255,255,255,.25); border-radius: 10px;
        padding: 1px 7px; font-size: 11px; font-weight: 700;
    }
    .tab-btn:not(.active) .tab-count { background: var(--gray-border); color: var(--text-secondary); }

    /* Tab panels */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Info card */
    .info-card {
        background: white; border-radius: 12px; border: 1px solid var(--gray-border);
        box-shadow: 0 1px 6px rgba(0,0,0,.05); overflow: hidden; margin-bottom: 18px;
    }
    .info-card-header {
        padding: 13px 18px; border-bottom: 1px solid var(--gray-border);
        background: var(--gray-light); display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 700; color: var(--text-primary);
    }
    .info-card-header i { color: var(--maroon-main); }
    .info-card-body { padding: 18px; }

    /* Data grid */
    .data-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
    .data-row {
        padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.04);
        display: flex; flex-direction: column; gap: 3px;
    }
    .data-row.full { grid-column: 1 / -1; }
    .data-lbl { font-size: 10px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .3px; }
    .data-val { font-size: 13px; color: var(--text-primary); font-weight: 500; }

    /* Status badges */
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 10px; font-size: 11px; font-weight: 700;
    }
    .status-submitted { background: #d1fae5; color: #059669; }
    .status-draft     { background: #fef3c7; color: #d97706; }
    .status-diterima  { background: #d1fae5; color: #059669; }
    .status-ditolak   { background: #fee2e2; color: #dc2626; }
    .status-pending   { background: #f3f4f6; color: #6b7280; }

    /* Dokumen table */
    .dok-tbl { width: 100%; border-collapse: collapse; }
    .dok-tbl th {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
        color: var(--text-secondary); padding: 9px 12px;
        background: var(--gray-light); border-bottom: 1px solid var(--gray-border); text-align: left;
    }
    .dok-tbl td { font-size: 12px; padding: 10px 12px; border-bottom: 1px solid rgba(0,0,0,.04); vertical-align: middle; }
    .dok-tbl tbody tr:last-child td { border-bottom: none; }
    .dok-tbl tbody tr:hover td { background: rgba(165,42,42,.02); }

    /* Logbook table */
    .logbook-tbl { width: 100%; border-collapse: collapse; }
    .logbook-tbl th {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
        color: var(--text-secondary); padding: 9px 14px;
        background: var(--gray-light); border-bottom: 1px solid var(--gray-border); text-align: left;
    }
    .logbook-tbl td { font-size: 12px; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.04); vertical-align: top; }
    .logbook-tbl tbody tr:last-child td { border-bottom: none; }
    .logbook-tbl tbody tr:hover td { background: rgba(165,42,42,.02); }
    .logbook-date {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 700; color: var(--maroon-main);
        background: rgba(165,42,42,.07); border-radius: 6px; padding: 3px 8px; white-space: nowrap;
    }

    /* Laporan */
    .laporan-item {
        display: flex; align-items: center; gap: 14px;
        border: 1px solid var(--gray-border); border-radius: 10px;
        padding: 14px 16px; background: var(--gray-light); margin-bottom: 10px;
    }
    .laporan-icon {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        background: rgba(165,42,42,.1); color: var(--maroon-main);
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .laporan-item-info { flex: 1; min-width: 0; }
    .laporan-item-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .laporan-item-meta { font-size: 11px; color: var(--text-secondary); margin-top: 3px; }

    /* Download / action button */
    .btn-dl {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; border-radius: 6px; text-decoration: none; flex-shrink: 0;
        border: 1px solid var(--maroon-main); background: rgba(165,42,42,.06);
        font-size: 11px; font-weight: 600; color: var(--maroon-main); transition: all .15s;
    }
    .btn-dl:hover { background: var(--maroon-main); color: white; }

    /* Nilai cards */
    .nilai-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .nilai-card {
        background: white; border: 1px solid var(--gray-border); border-radius: 12px;
        padding: 16px; text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .nilai-card-label { font-size: 10px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .3px; margin-bottom: 8px; }
    .nilai-card-val { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 4px; }
    .nilai-card-val.na { color: var(--gray-border); }
    .nilai-card-sub { font-size: 10px; color: var(--text-secondary); }
    .nilai-card.akhir { border-color: var(--maroon-main); background: rgba(165,42,42,.03); }
    .nilai-card.akhir .nilai-card-val { color: var(--maroon-main); }

    .grade-display {
        display: flex; align-items: center; gap: 16px;
        padding: 18px 22px; border-radius: 12px; margin-bottom: 18px;
        background: var(--gray-light); border: 1px solid var(--gray-border);
    }
    .grade-letter { font-size: 52px; font-weight: 900; line-height: 1; }
    .grade-info { display: flex; flex-direction: column; gap: 2px; }
    .grade-lbl { font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; }
    .grade-sub { font-size: 12px; color: var(--text-secondary); }
    .grade-a  { color: #059669; } .grade-ab { color: #2563eb; }
    .grade-b  { color: #0284c7; } .grade-bc { color: #d97706; }
    .grade-c  { color: #ca8a04; } .grade-d  { color: #dc2626; }
    .grade-e  { color: #b91c1c; }

    .catatan-box {
        background: #fefce8; border: 1px solid #fef08a; border-radius: 10px;
        padding: 14px 16px; font-size: 13px; color: #713f12; line-height: 1.6;
    }
    .catatan-box i { color: #ca8a04; margin-right: 6px; }

    .komponen-list { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .komponen-chip {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(165,42,42,.07); border: 1px solid rgba(165,42,42,.15);
        border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: var(--maroon-main);
    }

    .empty-state { text-align: center; padding: 48px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 40px; color: var(--gray-border); margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 13px; margin: 0; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '#' }}"
           onclick="if(this.href==='#'){history.back();return false;}">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <i class="fas fa-chevron-right sep"></i>
        <span>Profil Mahasiswa</span>
        <i class="fas fa-chevron-right sep"></i>
        <span>{{ $mahasiswa->nama }}</span>
    </div>

    {{-- Profile header --}}
    <div class="profil-header">
        <div class="profil-avatar-lg">{{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}</div>
        <div class="profil-main-info">
            <h2>{{ $mahasiswa->nama }}</h2>
            <p class="nim">{{ $mahasiswa->nim }}</p>
            <div class="meta">
                @if($mahasiswa->prodi)
                <span class="profil-meta-item">
                    <i class="fas fa-graduation-cap" style="font-size:10px;"></i> {{ $mahasiswa->prodi }}
                </span>
                @endif
                <span class="profil-meta-item">
                    <i class="fas fa-envelope" style="font-size:10px;"></i> {{ $mahasiswa->email }}
                </span>
                @if($surveyLokasi->desa)
                <span class="profil-meta-item">
                    <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                    {{ $surveyLokasi->desa }}{{ $surveyLokasi->kecamatan ? ', ' . $surveyLokasi->kecamatan : '' }}
                </span>
                @endif
            </div>
        </div>
        <div class="profil-header-badges">
            <span class="badge-white">
                <i class="fas fa-calendar-alt"></i> {{ $kegiatan->nama }}
            </span>
            <span class="badge-white">
                <i class="fas fa-users"></i> Kelompok {{ $surveyLokasi->kelompok }}
            </span>
            @if($surveyLokasi->is_koordinator)
            <span class="badge-koord-hdr">
                <i class="fas fa-star"></i> Koordinator
            </span>
            @endif
        </div>
    </div>

    {{-- Kegiatan / kelompok switcher (only if in multiple kegiatan) --}}
    @if($semuaKelompok->count() > 1)
    <div class="kegiatan-switcher">
        <span class="switcher-label"><i class="fas fa-exchange-alt" style="margin-right:4px;"></i>Kegiatan</span>
        <div class="switcher-pills">
            @foreach($semuaKelompok as $kel)
            <a href="{{ route('mahasiswa.profil', $mahasiswa->id) }}?survey_lokasi_id={{ $kel->survey_id }}"
               class="switcher-pill {{ $kel->survey_id == $surveyLokasi->survey_id ? 'active' : '' }}">
                <i class="fas fa-calendar-check" style="font-size:10px;"></i>
                {{ $kel->kegiatan_nama }}
                <span class="pill-badge">Kel. {{ $kel->kelompok }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tab navigation --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('data-diri', this)">
            <i class="fas fa-id-card"></i> Data Diri
        </button>
        <button class="tab-btn" onclick="switchTab('dokumen', this)">
            <i class="fas fa-folder-open"></i> Dok. Pendaftaran
            @if($dokumenPendaftaran->isNotEmpty())
            <span class="tab-count">{{ $dokumenPendaftaran->count() }}</span>
            @endif
        </button>
        <button class="tab-btn" onclick="switchTab('logbook', this)">
            <i class="fas fa-book"></i> Logbook
            <span class="tab-count">{{ $logbookList->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('laporan', this)">
            <i class="fas fa-file-alt"></i> Laporan
        </button>
        <button class="tab-btn" onclick="switchTab('nilai', this)">
            <i class="fas fa-star-half-alt"></i> Nilai
        </button>
    </div>

    {{-- ── TAB: DATA DIRI ── --}}
    <div class="tab-panel active" id="tab-data-diri">
        <div class="info-card">
            <div class="info-card-header"><i class="fas fa-user"></i> Informasi Mahasiswa</div>
            <div class="info-card-body" style="padding:0;">
                <div class="data-grid">
                    <div class="data-row">
                        <span class="data-lbl">NIM</span>
                        <span class="data-val">{{ $mahasiswa->nim }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Nama Lengkap</span>
                        <span class="data-val">{{ $mahasiswa->nama }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Email</span>
                        <span class="data-val">{{ $mahasiswa->email }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Program Studi</span>
                        <span class="data-val">{{ $mahasiswa->prodi ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($pendaftaran)
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-clipboard-list"></i> Data Pendaftaran
                <span class="status-badge status-{{ $pendaftaran->status }}" style="margin-left:auto;">
                    {{ $pendaftaran->status === 'submitted' ? 'Sudah Disubmit' : 'Draft' }}
                </span>
            </div>
            <div class="info-card-body" style="padding:0;">
                <div class="data-grid">
                    <div class="data-row">
                        <span class="data-lbl">Tempat Lahir</span>
                        <span class="data-val">{{ $pendaftaran->tempat_lahir }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Tanggal Lahir</span>
                        <span class="data-val">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Jenis Kelamin</span>
                        <span class="data-val">{{ $pendaftaran->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Golongan Darah</span>
                        <span class="data-val">{{ $pendaftaran->golongan_darah ?? '—' }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">No. HP</span>
                        <span class="data-val">{{ $pendaftaran->no_hp }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Semester</span>
                        <span class="data-val">{{ $pendaftaran->semester }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">SKS Ditempuh</span>
                        <span class="data-val">{{ $pendaftaran->sks_ditempuh }} SKS</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">IPK</span>
                        <span class="data-val">{{ number_format($pendaftaran->ipk, 2) }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-lbl">Ukuran Baju</span>
                        <span class="data-val">{{ $pendaftaran->ukuran_baju }}</span>
                    </div>
                    <div class="data-row full">
                        <span class="data-lbl">Alamat</span>
                        <span class="data-val">{{ $pendaftaran->alamat }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($pendaftaran->penyakit_diderita || $pendaftaran->catatan_kesehatan || $pendaftaran->sedang_hamil !== null)
        <div class="info-card">
            <div class="info-card-header"><i class="fas fa-heartbeat"></i> Informasi Kesehatan</div>
            <div class="info-card-body" style="padding:0;">
                <div class="data-grid">
                    @if($pendaftaran->penyakit_diderita)
                    <div class="data-row full">
                        <span class="data-lbl">Penyakit Diderita</span>
                        <span class="data-val">{{ $pendaftaran->penyakit_diderita }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->sedang_hamil !== null)
                    <div class="data-row">
                        <span class="data-lbl">Sedang Hamil</span>
                        <span class="data-val">{{ $pendaftaran->sedang_hamil ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->catatan_kesehatan)
                    <div class="data-row full">
                        <span class="data-lbl">Catatan Kesehatan</span>
                        <span class="data-val">{{ $pendaftaran->catatan_kesehatan }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @else
        <div class="info-card">
            <div class="info-card-body">
                <div class="empty-state">
                    <i class="fas fa-clipboard"></i>
                    <p>Mahasiswa belum mengisi data pendaftaran.</p>
                </div>
            </div>
        </div>
        @endif
    </div>{{-- /tab data-diri --}}

    {{-- ── TAB: DOKUMEN PENDAFTARAN ── --}}
    <div class="tab-panel" id="tab-dokumen">
        @if($dokumenPendaftaran->isNotEmpty())
        <div class="info-card">
            <div class="info-card-header"><i class="fas fa-file-upload"></i> Dokumen yang Diunggah</div>
            <div class="info-card-body" style="padding:0; overflow-x:auto;">
                <table class="dok-tbl">
                    <thead>
                        <tr>
                            <th style="width:34px;">No</th>
                            <th>Nama Dokumen</th>
                            <th>File</th>
                            <th>Ukuran</th>
                            <th>Status</th>
                            <th>Catatan Verifikasi</th>
                            <th>Unduh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumenPendaftaran as $i => $dok)
                        <tr>
                            <td style="color:var(--text-secondary);">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $dok->dokumen_nama }}</td>
                            <td style="font-size:11px; color:var(--text-secondary);">{{ $dok->file_name }}</td>
                            <td style="font-size:11px; color:var(--text-secondary); white-space:nowrap;">
                                {{ $dok->file_size ? round($dok->file_size / 1024) . ' KB' : '—' }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $dok->status }}">
                                    @if($dok->status === 'diterima') <i class="fas fa-check-circle"></i> Diterima
                                    @elseif($dok->status === 'ditolak') <i class="fas fa-times-circle"></i> Ditolak
                                    @else <i class="fas fa-clock"></i> Pending
                                    @endif
                                </span>
                            </td>
                            <td style="font-size:11px; color:var(--text-secondary);">
                                {{ $dok->catatan_verifikasi ?? '—' }}
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank" class="btn-dl">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="info-card">
            <div class="info-card-body">
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Belum ada dokumen pendaftaran yang diunggah.</p>
                </div>
            </div>
        </div>
        @endif
    </div>{{-- /tab dokumen --}}

    {{-- ── TAB: LOGBOOK ── --}}
    <div class="tab-panel" id="tab-logbook">
        @if($logbookList->isNotEmpty())
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-book-open"></i> Riwayat Logbook
                <span style="margin-left:auto; font-size:11px; color:var(--text-secondary); font-weight:400;">
                    {{ $logbookList->count() }} entri &bull; {{ $kegiatan->nama }}
                </span>
            </div>
            <div class="info-card-body" style="padding:0;">
                <table class="logbook-tbl">
                    <thead>
                        <tr>
                            <th style="width:34px;">No</th>
                            <th style="width:130px;">Tanggal</th>
                            <th>Kegiatan Dilakukan</th>
                            <th style="width:180px;">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logbookList as $i => $lb)
                        <tr>
                            <td style="color:var(--text-secondary);">{{ $i + 1 }}</td>
                            <td>
                                <span class="logbook-date">
                                    <i class="fas fa-calendar-day" style="font-size:9px;"></i>
                                    {{ \Carbon\Carbon::parse($lb->tanggal)->format('d M Y') }}
                                </span>
                            </td>
                            <td style="font-size:12px; line-height:1.5;">{{ $lb->kegiatan_dilakukan }}</td>
                            <td style="font-size:11px; color:var(--text-secondary);">
                                @if($lb->lokasi)
                                <i class="fas fa-map-marker-alt" style="width:12px;"></i> {{ $lb->lokasi }}
                                @else —
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="info-card">
            <div class="info-card-body">
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <p>Belum ada entri logbook untuk kegiatan <strong>{{ $kegiatan->nama }}</strong>.</p>
                </div>
            </div>
        </div>
        @endif
    </div>{{-- /tab logbook --}}

    {{-- ── TAB: LAPORAN ── --}}
    <div class="tab-panel" id="tab-laporan">

        <div class="info-card">
            <div class="info-card-header"><i class="fas fa-file-alt"></i> Laporan Individu</div>
            <div class="info-card-body">
                @if($laporanIndividu->isNotEmpty())
                @foreach($laporanIndividu as $lap)
                @php
                    $ext  = strtolower(pathinfo($lap->file_name ?? '', PATHINFO_EXTENSION));
                    $icon = $ext === 'pdf' ? 'fa-file-pdf' : (in_array($ext, ['doc','docx']) ? 'fa-file-word' : 'fa-file-alt');
                @endphp
                <div class="laporan-item">
                    <div class="laporan-icon"><i class="fas {{ $icon }}"></i></div>
                    <div class="laporan-item-info">
                        <div class="laporan-item-name">{{ $lap->dokumen_nama ?? $lap->file_name }}</div>
                        <div class="laporan-item-meta">
                            {{ $lap->file_name }}
                            @if($lap->file_size) &bull; {{ round($lap->file_size / 1024) }} KB @endif
                            @if($lap->uploaded_at) &bull; {{ \Carbon\Carbon::parse($lap->uploaded_at)->format('d M Y') }} @endif
                            @if($lap->keterangan) &bull; {{ $lap->keterangan }} @endif
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $lap->file_path) }}" target="_blank" class="btn-dl">
                        <i class="fas fa-download"></i> Unduh
                    </a>
                </div>
                @endforeach
                @else
                <div class="empty-state" style="padding:32px 20px;">
                    <i class="fas fa-file-alt"></i>
                    <p>Belum ada laporan individu yang diunggah.</p>
                </div>
                @endif
            </div>
        </div>

        @if($surveyLokasi->is_koordinator)
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-file-archive"></i> Laporan Kelompok
                <span style="margin-left:6px; font-size:11px; background:rgba(165,42,42,.1); color:var(--maroon-main); border-radius:10px; padding:2px 8px; font-weight:700;">
                    Diunggah sebagai Koordinator
                </span>
            </div>
            <div class="info-card-body">
                @if($laporanAkhir)
                @php
                    $ext  = strtolower(pathinfo($laporanAkhir->file_name ?? '', PATHINFO_EXTENSION));
                    $icon = $ext === 'pdf' ? 'fa-file-pdf' : (in_array($ext, ['doc','docx']) ? 'fa-file-word' : 'fa-file-alt');
                @endphp
                <div class="laporan-item">
                    <div class="laporan-icon"><i class="fas {{ $icon }}"></i></div>
                    <div class="laporan-item-info">
                        <div class="laporan-item-name">{{ $laporanAkhir->dokumen_nama ?? $laporanAkhir->file_name }}</div>
                        <div class="laporan-item-meta">
                            {{ $laporanAkhir->file_name }}
                            @if($laporanAkhir->file_size) &bull; {{ round($laporanAkhir->file_size / 1024) }} KB @endif
                            @if($laporanAkhir->uploaded_at) &bull; {{ \Carbon\Carbon::parse($laporanAkhir->uploaded_at)->format('d M Y') }} @endif
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $laporanAkhir->file_path) }}" target="_blank" class="btn-dl">
                        <i class="fas fa-download"></i> Unduh
                    </a>
                </div>
                @else
                <div class="empty-state" style="padding:32px 20px;">
                    <i class="fas fa-file-archive"></i>
                    <p>Laporan kelompok belum diunggah.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>{{-- /tab laporan --}}

    {{-- ── TAB: NILAI ── --}}
    <div class="tab-panel" id="tab-nilai">
        @php
            $naVal    = $nilai->nilai_akhir ?? null;
            $gradeStr = null;
            if ($naVal !== null) {
                foreach ($gradeTable as $g) {
                    if ($naVal >= $g->nilai_min && $naVal <= $g->nilai_max) {
                        $gradeStr = $g->grade;
                        break;
                    }
                }
            }
        @endphp

        @if($naVal !== null)
        <div class="nilai-cards" style="max-width:180px; margin-bottom:18px;">
            <div class="nilai-card akhir">
                <div class="nilai-card-label">Nilai Akhir</div>
                <div class="nilai-card-val">{{ number_format($naVal, 1) }}</div>
                <div class="nilai-card-sub">Final</div>
            </div>
        </div>
        @endif

        @if($naVal !== null && $gradeStr)
        @php $gradeObj = $gradeTable->first(fn($g) => $g->grade === $gradeStr); @endphp
        <div class="grade-display">
            <div class="grade-letter grade-{{ strtolower($gradeStr) }}">{{ $gradeStr }}</div>
            <div class="grade-info">
                <span class="grade-lbl">Grade</span>
                @if($gradeObj)
                <span class="grade-sub">Rentang: {{ $gradeObj->nilai_min }} – {{ $gradeObj->nilai_max }}</span>
                @endif
                <span class="grade-sub">Nilai Akhir: {{ number_format($naVal, 2) }}</span>
            </div>
        </div>
        @elseif($gradeTable->isNotEmpty())
        <div class="grade-display" style="opacity:.5;">
            <div class="grade-letter" style="color:var(--gray-border);">—</div>
            <div class="grade-info">
                <span class="grade-lbl">Belum dinilai</span>
                <span class="grade-sub">Nilai akhir belum tersedia</span>
            </div>
        </div>
        @endif

        @if($nilai && $nilai->catatan)
        <div class="info-card">
            <div class="info-card-header"><i class="fas fa-comment-alt"></i> Catatan DPL</div>
            <div class="info-card-body">
                <div class="catatan-box">
                    <i class="fas fa-quote-left"></i>{{ $nilai->catatan }}
                </div>
            </div>
        </div>
        @endif

        @if(!$nilai)
        <div class="info-card">
            <div class="info-card-body">
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <p>Nilai belum diinputkan untuk kegiatan <strong>{{ $kegiatan->nama }}</strong>.</p>
                </div>
            </div>
        </div>
        @endif
    </div>{{-- /tab nilai --}}

</div>
@endsection

@section('js')
<script>
function switchTab(id, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}
</script>
@endsection
