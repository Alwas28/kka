@extends('layouts.mahasiswa')

@section('title', 'Pelaksanaan KKA')

@section('css')
<style>
    .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
    .page-header-icon {
        width: 46px; height: 46px; border-radius: 12px;
        background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 20px; flex-shrink: 0;
    }
    .page-header-text h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin: 0 0 3px; }
    .page-header-text p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    /* ─── TABS ─── */
    .pel-tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--gray-border); margin-bottom: 20px; flex-wrap: wrap; }
    .pel-tab-btn {
        display: flex; align-items: center; gap: 7px;
        padding: 11px 18px; border: none; background: none; cursor: pointer;
        font-size: 13px; font-weight: 600; color: var(--text-secondary); font-family: inherit;
        border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .15s;
    }
    .pel-tab-btn:hover { color: var(--maroon-main); }
    .pel-tab-btn.active { color: var(--maroon-main); border-bottom-color: var(--maroon-main); }
    .pel-tab-btn .tab-badge {
        background: var(--maroon-main); color: white; font-size: 10px; font-weight: 700;
        border-radius: 10px; padding: 1px 6px; min-width: 16px; text-align: center;
    }
    .pel-tab-pane { display: none; }
    .pel-tab-pane.active { display: block; }

    /* ─── CARDS ─── */
    .info-card {
        background: white; border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        overflow: hidden; margin-bottom: 20px;
    }
    .card-header {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 20px;
        background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
        color: white;
    }
    .card-header i  { font-size: 15px; }
    .card-header h3 { font-size: 14px; font-weight: 700; margin: 0; }
    .card-header .badge-koordinator {
        margin-left: auto; font-size: 11px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px;
        background: rgba(255,255,255,0.25); color: white;
    }
    .card-header .badge-wajib {
        font-size: 11px; font-weight: 700;
        padding: 2px 8px; border-radius: 10px;
        background: rgba(239,68,68,0.4); color: white;
    }
    .card-header .btn-header {
        margin-left: auto; display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700;
        background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);
        cursor: pointer; font-family: inherit; transition: background 0.2s;
    }
    .card-header .btn-header:hover { background: rgba(255,255,255,0.3); }
    .card-body { padding: 20px; }

    /* Info kelompok grid */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; }
    .info-item label { display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-item span  { font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .link-value {
        background: none; border: none; padding: 0; cursor: pointer;
        font-family: inherit; font-size: 13px; font-weight: 700; color: var(--maroon-main);
        text-decoration: underline; text-align: left;
    }
    .link-value:hover { color: var(--maroon-dark); }
    .link-value + .link-value::before { content: ', '; color: var(--text-primary); text-decoration: none; font-weight: 600; }

    /* ─── LOGBOOK ─── */
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group label { font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.4px; }
    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 8px 12px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit; color: var(--text-primary); background: white;
        transition: all 0.2s;
    }
    .form-group input:focus,
    .form-group textarea:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .form-group textarea { resize: vertical; min-height: 80px; }

    .logbook-table { width: 100%; border-collapse: collapse; }
    .logbook-table th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 10px 14px; background: var(--gray-light); border-bottom: 1px solid var(--gray-border); text-align: left; }
    .logbook-table td { font-size: 13px; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,0.04); color: var(--text-primary); vertical-align: top; }
    .logbook-table tr:last-child td { border-bottom: none; }
    .logbook-table tr:hover td { background: rgba(165,42,42,0.02); }
    .logbook-table .td-date { white-space: nowrap; font-weight: 600; }
    .logbook-table .td-action { white-space: nowrap; }

    .empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 8px; padding: 40px 20px; color: var(--text-secondary);
    }
    .empty-state i { font-size: 32px; color: var(--gray-border); }
    .empty-state span { font-size: 13px; }

    /* ─── LAPORAN ─── */
    .upload-zone {
        border: 2px dashed var(--gray-border); border-radius: 10px;
        padding: 30px; text-align: center; cursor: pointer;
        transition: all 0.2s; background: var(--gray-light);
        display: block;
    }
    .upload-zone:hover { border-color: var(--maroon-main); background: rgba(165,42,42,0.03); }
    .upload-zone i { font-size: 28px; color: var(--gray-border); margin-bottom: 8px; display: block; }
    .upload-zone span { display: block; font-size: 13px; color: var(--text-secondary); }
    .upload-zone small { font-size: 11px; color: #9ca3af; }
    .upload-zone input[type=file] { display: none; }

    .file-info {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 18px; background: var(--gray-light);
        border: 1px solid var(--gray-border); border-radius: 10px;
    }
    .file-info-icon { width: 40px; height: 40px; border-radius: 8px; background: rgba(239,68,68,0.1); display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 18px; flex-shrink: 0; }
    .file-info-text { flex: 1; min-width: 0; }
    .file-info-text .file-name { font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .file-info-text .file-size { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
    .file-info-actions { display: flex; gap: 8px; flex-shrink: 0; flex-wrap: wrap; }

    /* ─── NILAI ─── */
    .nilai-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; margin-bottom: 16px; }
    .nilai-item {
        background: var(--gray-light); border: 1px solid var(--gray-border);
        border-radius: 10px; padding: 14px 16px; text-align: center;
    }
    .nilai-item label { display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 8px; }
    .nilai-item .nilai-val { font-size: 26px; font-weight: 800; color: var(--maroon-main); line-height: 1; }
    .nilai-item .nilai-val.grade { font-size: 36px; }
    .nilai-item .nilai-val.dash { color: #d1d5db; }
    .nilai-catatan { padding: 12px 14px; background: rgba(245,158,11,0.06); border: 1px solid rgba(245,158,11,0.2); border-radius: 8px; font-size: 13px; color: var(--text-primary); }

    /* ─── INFO LOKASI (detail fields di dalam tab, ringkas) ─── */
    .lokasi-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px 20px; }
    .lokasi-info-grid .info-item .info-value { font-size: 13px; color: var(--text-primary); font-weight: 500; line-height: 1.5; }
    .lokasi-info-grid .info-item .info-value.muted { color: var(--text-secondary); font-weight: 400; }
    .lokasi-info-full { grid-column: 1 / -1; }
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .status-pill.ok  { background: #d1fae5; color: #059669; }
    .status-pill.no  { background: #fee2e2; color: #dc2626; }
    .status-pill.mid { background: #fef3c7; color: #d97706; }
    .gmaps-link {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--maroon-main); font-weight: 600; text-decoration: none; font-size: 13px;
    }
    .gmaps-link:hover { text-decoration: underline; }

    /* ─── MODAL ─── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: white; border-radius: 14px; width: 100%; max-width: 520px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: modalIn 0.2s ease; overflow: hidden; }
    .modal-box.modal-lg { max-width: 640px; }
    @keyframes modalIn { from { opacity:0; transform: scale(0.95) translateY(-10px); } to { opacity:1; transform: none; } }
    .modal-header { display: flex; align-items: center; gap: 12px; padding: 16px 20px; background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main)); color: white; }
    .modal-header h4 { font-size: 15px; font-weight: 700; margin: 0; flex: 1; }
    .modal-close { background: none; border: none; color: rgba(255,255,255,0.7); cursor: pointer; font-size: 18px; padding: 2px; line-height: 1; }
    .modal-close:hover { color: white; }
    .modal-body { padding: 20px; max-height: 70vh; overflow-y: auto; }
    .modal-footer { padding: 12px 20px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--gray-border); }

    /* Anggota list */
    .anggota-list { display: flex; flex-direction: column; gap: 10px; }
    .anggota-item {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 14px; border: 1px solid var(--gray-border);
        border-radius: 10px; background: var(--gray-light);
    }
    .anggota-avatar {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 15px; font-weight: 700;
    }
    .anggota-info { flex: 1; min-width: 0; }
    .anggota-nama { font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .anggota-nim  { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
    .anggota-prodi { font-size: 11px; color: var(--text-secondary); margin-top: 1px; }
    .badge-kor {
        font-size: 10px; font-weight: 700; padding: 3px 8px;
        border-radius: 10px; background: rgba(165,42,42,0.1);
        color: var(--maroon-main); border: 1px solid rgba(165,42,42,0.2);
        flex-shrink: 0;
    }
    .badge-saya {
        font-size: 10px; font-weight: 700; padding: 3px 8px;
        border-radius: 10px; background: rgba(16,185,129,0.1);
        color: #059669; border: 1px solid rgba(16,185,129,0.2);
        flex-shrink: 0;
    }

    /* Detail DPL */
    .dpl-detail-row { display: flex; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--gray-border); }
    .dpl-detail-row:last-child { border-bottom: none; }
    .dpl-detail-row label { flex: 0 0 110px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .4px; }
    .dpl-detail-row span { font-size: 13px; color: var(--text-primary); font-weight: 500; }

    /* ─── BUTTONS ─── */
    .btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
    .btn-primary { background: var(--maroon-main); color: white; }
    .btn-primary:hover { background: var(--maroon-dark); }
    .btn-secondary { background: var(--gray-light); color: var(--text-primary); border: 1px solid var(--gray-border); }
    .btn-secondary:hover { background: #e5e7eb; }
    .btn-danger  { background: rgba(239,68,68,0.1); color: #dc2626; border: 1px solid rgba(239,68,68,0.2); }
    .btn-danger:hover  { background: rgba(239,68,68,0.2); }
    .btn-icon { padding: 6px 10px; font-size: 12px; }
    .btn-sm { padding: 5px 12px; font-size: 12px; }

    .section-hint {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; color: var(--text-secondary); margin-top: 10px;
    }
    .section-hint i { flex-shrink: 0; }

    /* ─── PERIODE LAPORAN BANNER ─── */
    .periode-banner {
        display: flex; align-items: center; gap: 12px;
        border-radius: 10px; padding: 13px 16px; margin-bottom: 16px;
    }
    .periode-banner i { font-size: 20px; flex-shrink: 0; }
    .periode-banner .periode-title { font-size: 13px; font-weight: 700; }
    .periode-banner .periode-sub   { font-size: 11px; margin-top: 2px; }
    .periode-banner.belum { background: #fef3c7; border: 1px solid #fde68a; }
    .periode-banner.belum i { color: #d97706; }
    .periode-banner.belum .periode-title { color: #92400e; }
    .periode-banner.belum .periode-sub   { color: #b45309; }
    .periode-banner.tutup { background: #fef2f2; border: 1px solid #fecaca; }
    .periode-banner.tutup i { color: #dc2626; }
    .periode-banner.tutup .periode-title { color: #991b1b; }
    .periode-banner.tutup .periode-sub   { color: #b91c1c; }
    .periode-banner.buka { background: #eff6ff; border: 1px solid #bfdbfe; }
    .periode-banner.buka i { color: #2563eb; }
    .periode-banner.buka .periode-title { color: #1e40af; }
    .periode-banner.buka .periode-sub   { color: #1d4ed8; }
    .periode-banner.buka .periode-sub strong { font-family: 'Courier New', monospace; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="page-header-icon"><i class="fas fa-tasks"></i></div>
        <div class="page-header-text">
            <h2>Pelaksanaan KKA</h2>
            <p>Logbook, laporan, nilai, dan info kelompok selama masa pelaksanaan KKA</p>
        </div>
    </div>

    @php
        $defaultTab = $hasLogbook ? 'logbook' : 'laporan';
    @endphp

    {{-- TAB NAVIGATION --}}
    <div class="pel-tabs">
        @if($hasLogbook)
        <button type="button" class="pel-tab-btn {{ $defaultTab === 'logbook' ? 'active' : '' }}" onclick="switchPelTab('logbook', this)">
            <i class="fas fa-book-open"></i> Logbook
        </button>
        @endif
        <button type="button" class="pel-tab-btn {{ $defaultTab === 'laporan' ? 'active' : '' }}" onclick="switchPelTab('laporan', this)">
            <i class="fas fa-file-alt"></i> Laporan
        </button>
        <button type="button" class="pel-tab-btn" onclick="switchPelTab('nilai', this)">
            <i class="fas fa-star"></i> Nilai
        </button>
        <button type="button" class="pel-tab-btn" onclick="switchPelTab('lokasi', this)">
            <i class="fas fa-map-marked-alt"></i> Info Lokasi
        </button>
    </div>

    {{-- ═══ TAB: LOGBOOK ═══ --}}
    @if($hasLogbook)
    <div class="pel-tab-pane {{ $defaultTab === 'logbook' ? 'active' : '' }}" id="pel-tab-logbook">
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-book-open"></i>
                <h3>Logbook Kegiatan</h3>
                @if(!$sudahDinilai)
                <button class="btn-header" onclick="openModal('modalTambahLogbook')">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                @else
                <span style="margin-left:auto; font-size:11px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:3px 10px;">
                    <i class="fas fa-lock"></i> Sudah dinilai
                </span>
                @endif
            </div>
            <div class="card-body">
                @if($logbooks->isNotEmpty())
                <div style="overflow-x:auto;">
                    <table class="logbook-table">
                        <thead>
                            <tr>
                                <th style="width:120px;">Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Lokasi</th>
                                @if(!$sudahDinilai)
                                <th style="width:100px; text-align:center;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logbooks as $lb)
                            <tr>
                                <td class="td-date">{{ $lb->tanggal->format('d M Y') }}</td>
                                <td style="white-space: pre-wrap;">{{ $lb->kegiatan_dilakukan }}</td>
                                <td>{{ $lb->lokasi ?? '-' }}</td>
                                @if(!$sudahDinilai)
                                <td class="td-action" style="text-align:center;">
                                    <button onclick="openEditLogbook({{ $lb->id }}, '{{ $lb->tanggal->format('Y-m-d') }}', {{ json_encode($lb->kegiatan_dilakukan) }}, {{ json_encode($lb->lokasi ?? '') }})"
                                        class="btn btn-secondary btn-icon btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('mahasiswa.pelaksanaan.logbook.destroy', $lb->id) }}" style="display:inline;"
                                        onsubmit="return confirm('Hapus logbook ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <span>Belum ada logbook. Klik tombol <strong>Tambah</strong> untuk mencatat kegiatan.</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ TAB: LAPORAN ═══ --}}
    <div class="pel-tab-pane {{ $defaultTab === 'laporan' ? 'active' : '' }}" id="pel-tab-laporan">

        @php $canUploadLaporan = $statusLaporan === 'buka' && !$sudahDinilai; @endphp

        {{-- Banner status periode upload laporan --}}
        @if($statusLaporan === 'belum')
        <div class="periode-banner belum">
            <i class="fas fa-hourglass-half"></i>
            <div>
                <div class="periode-title">Periode Upload Laporan Belum Dibuka</div>
                <div class="periode-sub">
                    @if($tahapanLaporan?->mulai)
                        Upload laporan akan dibuka pada <strong>{{ \Carbon\Carbon::parse($tahapanLaporan->mulai)->translatedFormat('d F Y') }}</strong>.
                    @else
                        Jadwal pembukaan belum diatur oleh panitia.
                    @endif
                </div>
            </div>
        </div>
        @elseif($statusLaporan === 'tutup')
        <div class="periode-banner tutup">
            <i class="fas fa-lock"></i>
            <div>
                <div class="periode-title">Periode Upload Laporan Telah Berakhir</div>
                <div class="periode-sub">
                    Batas akhir upload: <strong>{{ \Carbon\Carbon::parse($tahapanLaporan->selesai)->translatedFormat('d F Y') }}</strong>.
                    Laporan tidak dapat diupload atau diubah lagi.
                </div>
            </div>
        </div>
        @elseif($statusLaporan === 'buka' && $tahapanLaporan?->selesai && !$sudahDinilai)
        <div class="periode-banner buka" id="countdownBanner"
             data-deadline="{{ \Carbon\Carbon::parse($tahapanLaporan->selesai)->endOfDay()->toIso8601String() }}">
            <i class="fas fa-clock"></i>
            <div>
                <div class="periode-title">Batas Upload Laporan</div>
                <div class="periode-sub">
                    Ditutup pada <strong>{{ \Carbon\Carbon::parse($tahapanLaporan->selesai)->translatedFormat('d F Y') }}</strong>
                    &mdash; <span id="countdownText">menghitung...</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Laporan Individu (dinamis per dokumen, jika aktif diset admin) --}}
        @foreach($dokumenIndividu as $dok)
        @php $upload = $uploadIndividu->get($dok->id); @endphp
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-file-alt"></i>
                <h3>{{ $dok->nama }}</h3>
                @if($sudahDinilai)
                    <span style="margin-left:auto; font-size:11px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:3px 10px;">
                        <i class="fas fa-lock"></i> Sudah dinilai
                    </span>
                @elseif($dok->is_wajib)
                    <span class="badge-wajib" style="margin-left:auto;">Wajib</span>
                @endif
            </div>
            <div class="card-body">
                @if($upload)
                    <div class="file-info" style="margin-bottom: 14px;">
                        <div class="file-info-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="file-info-text">
                            <div class="file-name">{{ $upload->file_name }}</div>
                            <div class="file-size">{{ number_format($upload->file_size / 1024, 1) }} KB
                                &mdash; Diunggah {{ $upload->created_at->format('d M Y H:i') }}
                            </div>
                            @if($upload->keterangan)
                            <div style="font-size:12px; color:var(--text-secondary); margin-top:3px;">{{ $upload->keterangan }}</div>
                            @endif
                        </div>
                        <div class="file-info-actions">
                            <a href="{{ Storage::url($upload->file_path) }}" target="_blank"
                               class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Lihat</a>
                            @if($canUploadLaporan)
                            <form method="POST" action="{{ route('mahasiswa.pelaksanaan.laporan-individu.hapus', $dok->id) }}"
                                onsubmit="return confirm('Hapus laporan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @if($canUploadLaporan)
                    <div class="section-hint">
                        <i class="fas fa-info-circle"></i>
                        Untuk mengganti laporan, hapus file lama terlebih dahulu lalu upload yang baru.
                    </div>
                    @endif
                @elseif($canUploadLaporan)
                    <form method="POST" action="{{ route('mahasiswa.pelaksanaan.laporan-individu.upload', $dok->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom:14px;">
                            <label class="upload-zone" for="file-ind-{{ $dok->id }}">
                                <input type="file" id="file-ind-{{ $dok->id }}" name="file" accept=".pdf"
                                       onchange="previewFile(this, 'file-ind-name-{{ $dok->id }}')">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="file-ind-name-{{ $dok->id }}">Klik untuk pilih file PDF</span>
                                <small>Maks. 10 MB, format PDF</small>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:14px;">
                            <label>Keterangan (opsional)</label>
                            <input type="text" name="keterangan" placeholder="Tambahkan keterangan..." maxlength="255">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload {{ $dok->nama }}</button>
                    </form>
                @else
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <span>
                            @if($statusLaporan === 'belum')
                                {{ $dok->nama }} belum diunggah &mdash; menunggu periode upload dibuka.
                            @elseif($statusLaporan === 'tutup')
                                {{ $dok->nama }} tidak diunggah &mdash; periode upload sudah berakhir.
                            @else
                                {{ $dok->nama }} belum diunggah.
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Laporan Akhir Kelompok (dinamis per dokumen, hanya bisa diupload koordinator) --}}
        @foreach($dokumenKelompok as $dok)
        @php $upload = $uploadKelompok->get($dok->id); @endphp
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-file-contract"></i>
                <h3>{{ $dok->nama }}</h3>
                @if($sudahDinilai)
                    <span style="margin-left:auto; font-size:11px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:3px 10px;">
                        <i class="fas fa-lock"></i> Sudah dinilai
                    </span>
                @else
                    @if($dok->is_wajib && !$isKoordinator)
                        <span class="badge-wajib" style="margin-left:auto;">Wajib</span>
                    @endif
                    @if($isKoordinator)
                        <span class="badge-koordinator" style="margin-left:auto;"><i class="fas fa-crown" style="font-size:10px;"></i> Koordinator</span>
                        @if($dok->is_wajib)
                            <span class="badge-wajib">Wajib</span>
                        @endif
                    @endif
                @endif
            </div>
            <div class="card-body">
                @if($upload)
                    <div class="file-info" style="margin-bottom:14px;">
                        <div class="file-info-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="file-info-text">
                            <div class="file-name">{{ $upload->file_name }}</div>
                            <div class="file-size">{{ number_format($upload->file_size / 1024, 1) }} KB
                                &mdash; Diunggah {{ $upload->created_at->format('d M Y H:i') }}
                            </div>
                            @if($upload->keterangan)
                            <div style="font-size:12px; color:var(--text-secondary); margin-top:3px;">{{ $upload->keterangan }}</div>
                            @endif
                        </div>
                        <div class="file-info-actions">
                            <a href="{{ Storage::url($upload->file_path) }}" target="_blank"
                               class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Lihat</a>
                            @if($isKoordinator && $canUploadLaporan)
                            <form method="POST" action="{{ route('mahasiswa.pelaksanaan.laporan-akhir.hapus', $dok->id) }}"
                                onsubmit="return confirm('Hapus laporan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @if($isKoordinator && $canUploadLaporan)
                    <div class="section-hint">
                        <i class="fas fa-info-circle"></i>
                        Untuk mengganti laporan, hapus file lama terlebih dahulu lalu upload yang baru.
                    </div>
                    @endif
                @elseif($isKoordinator && $canUploadLaporan)
                    <form method="POST" action="{{ route('mahasiswa.pelaksanaan.laporan-akhir.upload', $dok->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom:14px;">
                            <label class="upload-zone" for="file-kel-{{ $dok->id }}">
                                <input type="file" id="file-kel-{{ $dok->id }}" name="file" accept=".pdf"
                                       onchange="previewFile(this, 'file-kel-name-{{ $dok->id }}')">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="file-kel-name-{{ $dok->id }}">Klik untuk pilih file PDF</span>
                                <small>Maks. 20 MB, format PDF</small>
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom:14px;">
                            <label>Keterangan (opsional)</label>
                            <input type="text" name="keterangan" placeholder="Tambahkan keterangan..." maxlength="255">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload {{ $dok->nama }}</button>
                    </form>
                @else
                    <div class="empty-state">
                        <i class="fas fa-file-contract"></i>
                        <span>
                            @if($sudahDinilai)
                                {{ $dok->nama }} belum diunggah.
                            @elseif($statusLaporan === 'belum')
                                {{ $dok->nama }} belum diunggah &mdash; menunggu periode upload dibuka.
                            @elseif($statusLaporan === 'tutup')
                                {{ $dok->nama }} tidak diunggah &mdash; periode upload sudah berakhir.
                            @else
                                {{ $dok->nama }} belum diunggah oleh koordinator kelompok.
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>
        @endforeach

        @if($dokumenIndividu->isEmpty() && $dokumenKelompok->isEmpty())
        <div class="info-card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <span>Belum ada jenis laporan yang diaktifkan untuk kegiatan ini.</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ═══ TAB: NILAI ═══ --}}
    <div class="pel-tab-pane" id="pel-tab-nilai">
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-star"></i>
                <h3>Nilai Akhir</h3>
            </div>
            <div class="card-body">
                @if($nilai)
                    @php
                        $naVal    = $nilai->nilai_akhir;
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
                    <div class="nilai-grid" style="max-width:320px;">
                        <div class="nilai-item">
                            <label>Nilai Akhir</label>
                            <div class="nilai-val {{ $naVal === null ? 'dash' : '' }}">
                                {{ $naVal !== null ? number_format($naVal, 1) : '-' }}
                            </div>
                        </div>
                        <div class="nilai-item">
                            <label>Grade</label>
                            <div class="nilai-val grade {{ $gradeStr === null ? 'dash' : '' }}">
                                {{ $gradeStr ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @if($nilai->dpl)
                    <div style="font-size:12px; color:var(--text-secondary); margin-bottom:10px;">
                        Dinilai oleh: <strong>{{ $nilai->dpl->nama }}</strong>
                    </div>
                    @endif
                    @if($nilai->catatan)
                    <div class="nilai-catatan">
                        <strong style="font-size:12px;">Catatan DPL:</strong><br>
                        {{ $nilai->catatan }}
                    </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fas fa-star"></i>
                        <span>Nilai belum diberikan oleh DPL.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ TAB: INFO LOKASI (info kelompok) ═══ --}}
    <div class="pel-tab-pane" id="pel-tab-lokasi">
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-users"></i>
                <h3>Informasi Kelompok</h3>
                @if($isKoordinator)
                    <span class="badge-koordinator"><i class="fas fa-crown" style="font-size:10px;"></i> Koordinator</span>
                @endif
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>No. Kelompok</label>
                        <span>Kelompok {{ $kelompok->kelompok }}</span>
                    </div>
                    <div class="info-item">
                        <label>Lokasi</label>
                        <span>
                            <button type="button" class="link-value" onclick="openModal('modalDetailLokasi')">
                                {{ $kelompok->desa?->nama ?? '-' }}
                                @if($kelompok->desa?->kecamatan)
                                    , Kec. {{ $kelompok->desa->kecamatan->nama }}
                                @endif
                            </button>
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Kegiatan</label>
                        <span>{{ $kegiatan?->nama ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <label>DPL</label>
                        <span>
                            @forelse($kelompok->dosenPembimbing as $dpl)
                                <button type="button" class="link-value" onclick="openDetailDpl({{ $dpl->id }}, {{ json_encode($dpl->nama) }}, {{ json_encode($dpl->nip) }}, {{ json_encode($dpl->email) }}, {{ json_encode($dpl->no_hp) }})">{{ $dpl->nama }}</button>
                            @empty
                                <span style="color:#9ca3af;">Belum ditentukan</span>
                            @endforelse
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Anggota</label>
                        <span>
                            <button type="button" class="link-value" onclick="openModal('modalAnggota')">
                                {{ $kelompok->peserta->count() }} Mahasiswa
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: TAMBAH LOGBOOK
════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalTambahLogbook">
    <div class="modal-box">
        <div class="modal-header">
            <i class="fas fa-plus"></i>
            <h4>Tambah Logbook</h4>
            <button class="modal-close" onclick="closeModal('modalTambahLogbook')">&times;</button>
        </div>
        <form method="POST" action="{{ route('mahasiswa.pelaksanaan.logbook.store') }}">
            @csrf
            <div class="modal-body" style="display:flex; flex-direction:column; gap:14px;">
                <div class="form-group">
                    <label>Tanggal <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" required>
                </div>
                <div class="form-group">
                    <label>Kegiatan yang Dilakukan <span style="color:#ef4444;">*</span></label>
                    <textarea name="kegiatan_dilakukan" rows="4" required
                        placeholder="Deskripsikan kegiatan yang dilakukan hari ini...">{{ old('kegiatan_dilakukan') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Balai Desa, Posko, dst.">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahLogbook')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: EDIT LOGBOOK
════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalEditLogbook">
    <div class="modal-box">
        <div class="modal-header">
            <i class="fas fa-edit"></i>
            <h4>Edit Logbook</h4>
            <button class="modal-close" onclick="closeModal('modalEditLogbook')">&times;</button>
        </div>
        <form id="formEditLogbook" method="POST" action="">
            @csrf @method('PUT')
            <div class="modal-body" style="display:flex; flex-direction:column; gap:14px;">
                <div class="form-group">
                    <label>Tanggal <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal" id="editLogbookTanggal" required>
                </div>
                <div class="form-group">
                    <label>Kegiatan yang Dilakukan <span style="color:#ef4444;">*</span></label>
                    <textarea name="kegiatan_dilakukan" id="editLogbookKegiatan" rows="4" required
                        placeholder="Deskripsikan kegiatan..."></textarea>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" id="editLogbookLokasi" placeholder="Opsional">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditLogbook')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: ANGGOTA TIM
════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalAnggota">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <i class="fas fa-users"></i>
            <h4>Anggota Kelompok {{ $kelompok->kelompok }}</h4>
            <button class="modal-close" onclick="closeModal('modalAnggota')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="anggota-list">
                @foreach($kelompok->peserta->sortByDesc(fn($p) => $p->pivot->is_koordinator) as $anggota)
                <div class="anggota-item">
                    <div class="anggota-avatar">
                        {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                    </div>
                    <div class="anggota-info">
                        <div class="anggota-nama">{{ $anggota->nama }}</div>
                        <div class="anggota-nim">{{ $anggota->nim }}</div>
                        @if($anggota->programStudi)
                        <div class="anggota-prodi">{{ $anggota->programStudi->nama }}</div>
                        @endif
                    </div>
                    <div style="display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;">
                        @if($anggota->pivot->is_koordinator)
                            <span class="badge-kor"><i class="fas fa-crown" style="font-size:9px;"></i> Koordinator</span>
                        @endif
                        @if($anggota->id === $mahasiswa->id)
                            <span class="badge-saya">Saya</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalAnggota')">Tutup</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: DETAIL LOKASI
════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalDetailLokasi">
    <div class="modal-box modal-lg">
        <div class="modal-header">
            <i class="fas fa-map-marked-alt"></i>
            <h4>Detail Lokasi Kelompok {{ $kelompok->kelompok }}</h4>
            <button class="modal-close" onclick="closeModal('modalDetailLokasi')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="lokasi-info-grid">
                <div class="info-item lokasi-info-full">
                    <label>Lokasi</label>
                    <div class="info-value">{{ $kelompok->lokasi_lengkap }}</div>
                </div>
                <div class="info-item">
                    <label>Status Survey</label>
                    <div class="info-value">
                        @php
                            $statusMap = [
                                'belum_survey' => ['Belum Survey', 'no'],
                                'sudah_survey' => ['Sudah Survey', 'mid'],
                                'disetujui'    => ['Disetujui', 'ok'],
                                'ditolak'      => ['Ditolak', 'no'],
                            ];
                            [$statusLbl, $statusClass] = $statusMap[$kelompok->status] ?? [$kelompok->status, 'mid'];
                        @endphp
                        <span class="status-pill {{ $statusClass }}">{{ $statusLbl }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <label>Nama Kepala Desa</label>
                    <div class="info-value {{ $kelompok->nama_kades ? '' : 'muted' }}">{{ $kelompok->nama_kades ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <label>No. HP Kepala Desa</label>
                    <div class="info-value {{ $kelompok->no_hp_kades ? '' : 'muted' }}">{{ $kelompok->no_hp_kades ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <label>Rencana Posko</label>
                    <div class="info-value {{ $kelompok->rencana_posko ? '' : 'muted' }}">
                        @php $poskoMap = ['rumah_kades' => 'Rumah Kepala Desa', 'rumah_warga' => 'Rumah Warga', 'lainnya' => 'Lainnya']; @endphp
                        {{ $kelompok->rencana_posko ? ($poskoMap[$kelompok->rencana_posko] ?? $kelompok->rencana_posko) : '-' }}
                        @if($kelompok->rencana_posko === 'lainnya' && $kelompok->rencana_posko_lainnya)
                            &mdash; {{ $kelompok->rencana_posko_lainnya }}
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <label>Google Maps</label>
                    <div class="info-value">
                        @if($kelompok->gmaps_url)
                        <a href="{{ $kelompok->gmaps_url }}" target="_blank" class="gmaps-link">
                            <i class="fas fa-map-marker-alt"></i> Buka Lokasi
                        </a>
                        @else
                        <span class="muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <label>Kondisi Air</label>
                    <div class="info-value {{ $kelompok->kondisi_air ? '' : 'muted' }}">{{ $kelompok->kondisi_air ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <label>Kondisi Listrik</label>
                    <div class="info-value {{ $kelompok->kondisi_listrik ? '' : 'muted' }}">{{ $kelompok->kondisi_listrik ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <label>Kondisi Transportasi</label>
                    <div class="info-value {{ $kelompok->kondisi_transportasi ? '' : 'muted' }}">{{ $kelompok->kondisi_transportasi ?? '-' }}</div>
                </div>
                <div class="info-item lokasi-info-full">
                    <label>Deskripsi Lokasi</label>
                    <div class="info-value {{ $kelompok->deskripsi ? '' : 'muted' }}">{{ $kelompok->deskripsi ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalDetailLokasi')">Tutup</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: DETAIL DPL (diisi dinamis via JS)
════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalDetailDpl">
    <div class="modal-box">
        <div class="modal-header">
            <i class="fas fa-chalkboard-teacher"></i>
            <h4>Detail Dosen Pembimbing</h4>
            <button class="modal-close" onclick="closeModal('modalDetailDpl')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="dpl-detail-row">
                <label>Nama</label>
                <span id="dplDetailNama">-</span>
            </div>
            <div class="dpl-detail-row">
                <label>NIP</label>
                <span id="dplDetailNip">-</span>
            </div>
            <div class="dpl-detail-row">
                <label>Email</label>
                <span id="dplDetailEmail">-</span>
            </div>
            <div class="dpl-detail-row">
                <label>No. HP</label>
                <span id="dplDetailHp">-</span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalDetailDpl')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function switchPelTab(tabName, btn) {
        document.querySelectorAll('.pel-tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.pel-tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('pel-tab-' + tabName)?.classList.add('active');
        btn.classList.add('active');
    }

    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function openDetailDpl(id, nama, nip, email, noHp) {
        document.getElementById('dplDetailNama').textContent  = nama || '-';
        document.getElementById('dplDetailNip').textContent   = nip || '-';
        document.getElementById('dplDetailEmail').textContent = email || '-';
        document.getElementById('dplDetailHp').textContent    = noHp || '-';
        openModal('modalDetailDpl');
    }

    function openEditLogbook(id, tanggal, kegiatan, lokasi) {
        document.getElementById('formEditLogbook').action = `/mahasiswa/pelaksanaan/logbook/${id}`;
        document.getElementById('editLogbookTanggal').value  = tanggal;
        document.getElementById('editLogbookKegiatan').value = kegiatan;
        document.getElementById('editLogbookLokasi').value   = lokasi;
        openModal('modalEditLogbook');
    }

    function previewFile(input, labelId) {
        const label = document.getElementById(labelId);
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
        }
    }

    // Countdown batas upload laporan
    (function () {
        const banner = document.getElementById('countdownBanner');
        if (!banner) return;
        const deadline = new Date(banner.dataset.deadline).getTime();
        const textEl   = document.getElementById('countdownText');
        let timer;

        function tick() {
            const diff = deadline - Date.now();
            if (diff <= 0) {
                textEl.textContent = 'Periode telah berakhir. Muat ulang halaman ini.';
                clearInterval(timer);
                return;
            }
            const d = Math.floor(diff / 86400000);
            const h = Math.floor((diff % 86400000) / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            const pad = n => String(n).padStart(2, '0');
            textEl.textContent = (d > 0 ? d + ' hari ' : '') + pad(h) + ':' + pad(m) + ':' + pad(s) + ' lagi';
        }

        tick();
        timer = setInterval(tick, 1000);
    })();

    // Tutup modal saat klik overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // Buka modal tambah logbook jika ada error validasi (supaya user tidak bingung)
    @if($errors->any() && old('tanggal'))
        openModal('modalTambahLogbook');
    @endif
</script>
@endsection
