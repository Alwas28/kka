@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:20px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .breadcrumb { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--text-secondary); margin-bottom:18px; }
    .breadcrumb a { color:var(--maroon-main); text-decoration:none; font-weight:600; }
    .breadcrumb a:hover { text-decoration:underline; }

    .kegiatan-info { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; flex-wrap:wrap; gap:20px; align-items:center; }
    .ki-item .ki-label { font-size:11px; color:var(--text-secondary); margin-bottom:2px; }
    .ki-item .ki-val   { font-size:14px; font-weight:700; color:var(--text-primary); }
    .ki-item .ki-val.accent { color:var(--maroon-main); }

    .stat-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:20px; }
    .stat-card { background:#fff; border:1px solid var(--gray-border); border-radius:10px; padding:14px 16px; text-align:center; }
    .stat-card .stat-num   { font-size:26px; font-weight:800; line-height:1; }
    .stat-card .stat-label { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .stat-card.c-total  .stat-num { color:var(--maroon-main); }
    .stat-card.c-submit .stat-num { color:#10b981; }
    .stat-card.c-draft  .stat-num { color:#f59e0b; }
    .stat-card.c-belum  .stat-num { color:#9ca3af; }

    .filter-bar { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; align-items:center; }
    .filter-bar select { padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .filter-bar select:focus { outline:none; border-color:var(--maroon-main); }
    .search-box { position:relative; flex:1; max-width:300px; }
    .search-box input { width:100%; padding:9px 13px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }

    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; gap:10px; flex-wrap:wrap; }
    .table-info { font-size:13px; color:var(--text-secondary); }

    /* Tabel identitas — compact, banyak kolom */
    .table-container table th { font-size:11px; white-space:nowrap; padding:8px 10px; }
    .table-container table td { font-size:12px; padding:7px 10px; vertical-align:top; }
    .cell-2line strong { display:block; font-size:12px; font-weight:700; color:var(--text-primary); }
    .cell-2line span   { font-size:11px; color:var(--text-secondary); }
    .cell-wrap { max-width:200px; white-space:normal; word-break:break-word; font-size:12px; color:var(--text-secondary); }
    .cell-mono { font-family:monospace; font-size:12px; }

    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }
    .badge-submit { background:rgba(16,185,129,.12); color:#059669; }
    .badge-draft  { background:rgba(245,158,11,.12); color:#d97706; }
    .badge-belum  { background:#f3f4f6; color:#9ca3af; }
    .badge-l  { background:#dbeafe; color:#1d4ed8; }
    .badge-p  { background:#fce7f3; color:#be185d; }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-sm { padding:5px 10px; font-size:11px; }
    .btn-profil   { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-profil:hover { box-shadow:0 3px 10px rgba(165,42,42,.35); transform:translateY(-1px); color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); }
    .btn-secondary:hover { background:#d1d5db; }
    .btn-grafik { background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; }
    .btn-grafik:hover { box-shadow:0 3px 10px rgba(37,99,235,.35); transform:translateY(-1px); color:#fff; }
    .btn-export { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; }
    .btn-export:hover { box-shadow:0 3px 10px rgba(22,163,74,.35); transform:translateY(-1px); color:#fff; }
    .no-kelompok { font-size:11px; color:#9ca3af; white-space:nowrap; }

    .empty-state { text-align:center; padding:50px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:40px; color:var(--gray-border); margin-bottom:12px; display:block; }
    .empty-state h3 { font-size:15px; color:var(--text-primary); margin-bottom:6px; }

    /* ── Modal Export ── */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; padding:16px; }
    .modal-overlay.active { display:flex; }
    .modal-box { background:#fff; border-radius:14px; width:100%; max-width:600px; max-height:90vh; box-shadow:0 20px 60px rgba(0,0,0,.25); overflow:hidden; display:flex; flex-direction:column; }
    .modal-header { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
    .modal-header h3 { font-size:15px; font-weight:700; margin:0; }
    .modal-close { background:none; border:none; color:rgba(255,255,255,.8); font-size:20px; cursor:pointer; line-height:1; padding:0 4px; }
    .modal-close:hover { color:#fff; }
    .modal-body { padding:18px 20px; overflow-y:auto; flex:1; }
    .modal-body > p { font-size:13px; color:var(--text-secondary); margin:0 0 14px; }

    .col-group { margin-bottom:14px; }
    .col-group-title { font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px; padding-bottom:4px; border-bottom:1px solid var(--gray-border); }
    .col-list { display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px 14px; }
    .col-item { display:flex; align-items:center; gap:7px; cursor:pointer; font-size:12px; color:var(--text-primary); user-select:none; padding:3px 0; }
    .col-item input[type=checkbox] { width:15px; height:15px; accent-color:#16a34a; cursor:pointer; flex-shrink:0; }
    .col-item:hover { color:#16a34a; }

    .col-actions { display:flex; gap:8px; margin-bottom:14px; }
    .col-actions button { background:none; border:1px solid var(--gray-border); border-radius:6px; padding:4px 10px; font-size:12px; font-weight:600; cursor:pointer; color:var(--text-secondary); transition:all .2s; font-family:inherit; }
    .col-actions button:hover { border-color:#16a34a; color:#16a34a; }

    .filter-notice { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:9px 13px; font-size:12px; color:#166534; display:flex; align-items:flex-start; gap:8px; margin-top:12px; }

    .modal-footer { padding:12px 20px; border-top:1px solid var(--gray-border); display:flex; justify-content:flex-end; gap:10px; background:#fafafa; flex-shrink:0; }
    .btn-export-submit { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:7px; font-family:inherit; transition:all .2s; }
    .btn-export-submit:hover:not(:disabled) { box-shadow:0 4px 14px rgba(22,163,74,.4); transform:translateY(-1px); }
    .btn-export-submit:disabled { opacity:.5; cursor:default; transform:none; }
    .btn-cancel-modal { background:var(--gray-border); color:var(--text-primary); border:none; border-radius:8px; padding:9px 16px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; }
    .btn-cancel-modal:hover { background:#d1d5db; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="breadcrumb">
        <a href="{{ route('rekap.pendaftaran') }}"><i class="fas fa-chart-bar"></i> Rekap Pendaftaran</a>
        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
        <span>Detail</span>
    </div>

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-users" style="color:var(--maroon-main);margin-right:8px;"></i>{{ $kegiatan->nama }}</h2>
            <p>Daftar mahasiswa beserta seluruh data identitas</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('rekap.pendaftaran.grafik', $kegiatan->id) }}" class="btn btn-grafik">
                <i class="fas fa-chart-pie"></i> Lihat Grafik
            </a>
            <button type="button" onclick="openExportModal()" class="btn btn-export">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <a href="{{ route('rekap.pendaftaran') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="kegiatan-info">
        <div class="ki-item">
            <div class="ki-label">Jenis KKA</div>
            <div class="ki-val accent">{{ $kegiatan->jenis_nama ?? '—' }}</div>
        </div>
        <div class="ki-item">
            <div class="ki-label">Periode</div>
            <div class="ki-val">{{ $kegiatan->periode_nama ?? '—' }}</div>
        </div>
        <div class="ki-item">
            <div class="ki-label">Total Pendaftar</div>
            <div class="ki-val accent">{{ $statTotal }} mahasiswa</div>
        </div>
    </div>

    <div class="stat-row">
        <div class="stat-card c-total">
            <div class="stat-num">{{ $statTotal }}</div>
            <div class="stat-label">Total Pendaftar</div>
        </div>
        <div class="stat-card c-submit">
            <div class="stat-num">{{ $statSubmit }}</div>
            <div class="stat-label">Sudah Submit</div>
        </div>
        <div class="stat-card c-draft">
            <div class="stat-num">{{ $statDraft }}</div>
            <div class="stat-label">Masih Draft</div>
        </div>
        <div class="stat-card c-belum">
            <div class="stat-num">{{ $statBelum }}</div>
            <div class="stat-label">Belum Mengisi</div>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIM...">
            </div>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Sudah Submit</option>
                <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="belum"     {{ request('status') == 'belum'     ? 'selected' : '' }}>Belum Mengisi</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request()->hasAny(['q','status']))
            <a href="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <div class="table-toolbar">
        <div class="table-info">
            Menampilkan <strong>{{ $mahasiswaList->firstItem() ?? 0 }}–{{ $mahasiswaList->lastItem() ?? 0 }}</strong>
            dari <strong>{{ $mahasiswaList->total() }}</strong> mahasiswa
        </div>
    </div>

    <div class="table-container">
        @if($mahasiswaList->count() > 0)
        <table>
            <thead>
                <tr>
                    {{-- Identitas Dasar --}}
                    <th style="width:36px;">No</th>
                    <th style="width:110px;">NIM</th>
                    <th style="min-width:160px;">Nama</th>
                    <th style="min-width:130px;">Email</th>
                    <th style="min-width:140px;">Program Studi</th>
                    <th style="width:80px;">Level</th>
                    {{-- Identitas Diri --}}
                    <th style="width:65px;">JK</th>
                    <th style="min-width:140px;">Tempat / Tgl Lahir</th>
                    <th style="width:110px;">No. HP</th>
                    <th style="width:70px;">Gol. Darah</th>
                    <th style="min-width:180px;">Alamat</th>
                    {{-- Akademik --}}
                    <th style="width:60px;">Smt</th>
                    <th style="width:60px;">SKS</th>
                    <th style="width:55px;">IPK</th>
                    {{-- Lainnya --}}
                    <th style="width:80px;">Baju</th>
                    <th style="min-width:150px;">Kesehatan</th>
                    {{-- Status & Aksi --}}
                    <th style="width:105px;">Status</th>
                    <th style="width:100px;text-align:center;">Profil</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $i => $mhs)
                @php $km = $kelompokMap->get($mhs->mahasiswa_id); @endphp
                <tr>
                    <td style="color:var(--text-secondary);">{{ $mahasiswaList->firstItem() + $i }}</td>
                    <td class="cell-mono">{{ $mhs->nim }}</td>
                    <td>
                        <div class="cell-2line">
                            <strong>{{ $mhs->nama }}</strong>
                        </div>
                    </td>
                    <td style="font-size:11px;color:var(--text-secondary);">{{ $mhs->email }}</td>
                    <td style="font-size:12px;color:var(--text-secondary);">{{ $mhs->prodi_nama ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--text-secondary);">{{ $mhs->level_nama ?? '—' }}</td>
                    {{-- JK --}}
                    <td>
                        @if($mhs->jenis_kelamin === 'L')
                            <span class="badge badge-l"><i class="fas fa-mars"></i> L</span>
                        @elseif($mhs->jenis_kelamin === 'P')
                            <span class="badge badge-p"><i class="fas fa-venus"></i> P</span>
                        @else
                            <span style="color:var(--text-secondary);font-size:12px;">—</span>
                        @endif
                    </td>
                    {{-- Tempat/Tgl Lahir --}}
                    <td>
                        <div class="cell-2line">
                            <strong>{{ $mhs->tempat_lahir ?? '—' }}</strong>
                            <span>{{ $mhs->tanggal_lahir ? \Carbon\Carbon::parse($mhs->tanggal_lahir)->format('d/m/Y') : '—' }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;">{{ $mhs->no_hp ?? '—' }}</td>
                    <td style="font-size:12px;text-align:center;">{{ $mhs->golongan_darah ?? '—' }}</td>
                    <td><div class="cell-wrap">{{ $mhs->alamat ?? '—' }}</div></td>
                    {{-- Akademik --}}
                    <td style="text-align:center;font-weight:600;">{{ $mhs->semester ?? '—' }}</td>
                    <td style="text-align:center;font-weight:600;">{{ $mhs->sks_ditempuh ?? '—' }}</td>
                    <td style="text-align:center;font-weight:600;">{{ $mhs->ipk !== null ? number_format((float)$mhs->ipk, 2) : '—' }}</td>
                    {{-- Baju --}}
                    <td style="text-align:center;font-size:12px;font-weight:600;">{{ $mhs->ukuran_baju ?? '—' }}</td>
                    {{-- Kesehatan --}}
                    <td>
                        <div style="font-size:11px;color:var(--text-secondary);line-height:1.6;">
                            @if($mhs->penyakit_diderita)
                                <div><i class="fas fa-notes-medical" style="color:#ef4444;margin-right:3px;"></i>{{ $mhs->penyakit_diderita }}</div>
                            @endif
                            @if($mhs->sedang_hamil)
                                <div><i class="fas fa-baby" style="color:#ec4899;margin-right:3px;"></i>Sedang hamil</div>
                            @endif
                            @if($mhs->catatan_kesehatan)
                                <div><i class="fas fa-comment-medical" style="color:#6b7280;margin-right:3px;"></i>{{ $mhs->catatan_kesehatan }}</div>
                            @endif
                            @if(!$mhs->penyakit_diderita && !$mhs->sedang_hamil && !$mhs->catatan_kesehatan)
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </div>
                    </td>
                    {{-- Status --}}
                    <td>
                        @if($mhs->pendaftaran_status === 'submitted')
                            <span class="badge badge-submit"><i class="fas fa-check-circle"></i> Submit</span>
                        @elseif($mhs->pendaftaran_status === 'draft')
                            <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> Draft</span>
                        @else
                            <span class="badge badge-belum"><i class="fas fa-minus-circle"></i> Belum</span>
                        @endif
                    </td>
                    {{-- Profil --}}
                    <td style="text-align:center;">
                        @if($km)
                            <a href="{{ route('mahasiswa.profil', $mhs->mahasiswa_id) }}" class="btn btn-profil btn-sm">
                                <i class="fas fa-id-card"></i> Profil
                            </a>
                        @else
                            <span class="no-kelompok"><i class="fas fa-clock"></i> Belum kelompok</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($mahasiswaList->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Halaman {{ $mahasiswaList->currentPage() }} dari {{ $mahasiswaList->lastPage() }}
                &mdash; {{ $mahasiswaList->firstItem() }}–{{ $mahasiswaList->lastItem() }} dari {{ $mahasiswaList->total() }} data
            </div>
            <div class="pagination-btns">
                @if($mahasiswaList->onFirstPage())
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $mahasiswaList->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $start = max(1, $mahasiswaList->currentPage() - 2);
                    $end   = min($mahasiswaList->lastPage(), $mahasiswaList->currentPage() + 2);
                @endphp
                @if($start > 1)
                    <a href="{{ $mahasiswaList->url(1) }}" class="page-btn">1</a>
                    @if($start > 2)<span class="page-btn disabled">…</span>@endif
                @endif
                @for($pg = $start; $pg <= $end; $pg++)
                    <a href="{{ $mahasiswaList->url($pg) }}" class="page-btn {{ $pg == $mahasiswaList->currentPage() ? 'active' : '' }}">{{ $pg }}</a>
                @endfor
                @if($end < $mahasiswaList->lastPage())
                    @if($end < $mahasiswaList->lastPage() - 1)<span class="page-btn disabled">…</span>@endif
                    <a href="{{ $mahasiswaList->url($mahasiswaList->lastPage()) }}" class="page-btn">{{ $mahasiswaList->lastPage() }}</a>
                @endif
                @if($mahasiswaList->hasMorePages())
                    <a href="{{ $mahasiswaList->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-user-slash"></i>
            <h3>Tidak ada data</h3>
            <p>
                @if(request()->hasAny(['q','status']))
                    Tidak ada mahasiswa yang sesuai filter.
                @else
                    Belum ada mahasiswa yang mendaftar pada kegiatan ini.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

{{-- ── Modal Export Excel ── --}}
<div id="modal-export" class="modal-overlay" onclick="if(event.target===this)closeExportModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-file-excel" style="margin-right:8px;"></i>Export ke Excel</h3>
            <button type="button" class="modal-close" onclick="closeExportModal()">&#x2715;</button>
        </div>

        <form id="form-export" method="GET" action="{{ route('rekap.pendaftaran.export', $kegiatan->id) }}">
            @if(request('q'))    <input type="hidden" name="q"      value="{{ request('q') }}"> @endif
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif

            <div class="modal-body">
                <p>Centang kolom yang ingin disertakan dalam file Excel:</p>

                <div class="col-actions">
                    <button type="button" onclick="toggleAllColumns(true)">
                        <i class="fas fa-check-square"></i> Pilih Semua
                    </button>
                    <button type="button" onclick="toggleAllColumns(false)">
                        <i class="far fa-square"></i> Hapus Semua
                    </button>
                </div>

                {{-- Grup: Identitas Dasar --}}
                <div class="col-group">
                    <div class="col-group-title"><i class="fas fa-id-card"></i> Identitas Dasar</div>
                    <div class="col-list">
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="no"    checked> No</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="nim"   checked> NIM</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="nama"  checked> Nama Mahasiswa</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="email" checked> Email</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="prodi" checked> Program Studi</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="level" checked> Level</label>
                    </div>
                </div>

                {{-- Grup: Identitas Diri --}}
                <div class="col-group">
                    <div class="col-group-title"><i class="fas fa-user"></i> Identitas Diri</div>
                    <div class="col-list">
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="jenis_kelamin"  checked> Jenis Kelamin</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="tempat_lahir"   checked> Tempat Lahir</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="tanggal_lahir"  checked> Tanggal Lahir</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="no_hp"          checked> No. HP</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="golongan_darah" checked> Gol. Darah</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="alamat"         checked> Alamat</label>
                    </div>
                </div>

                {{-- Grup: Akademik --}}
                <div class="col-group">
                    <div class="col-group-title"><i class="fas fa-graduation-cap"></i> Akademik</div>
                    <div class="col-list">
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="semester"     checked> Semester</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="sks_ditempuh" checked> SKS Ditempuh</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="ipk"          checked> IPK</label>
                    </div>
                </div>

                {{-- Grup: Lainnya --}}
                <div class="col-group">
                    <div class="col-group-title"><i class="fas fa-clipboard-list"></i> Lainnya</div>
                    <div class="col-list">
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="ukuran_baju"       checked> Ukuran Baju</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="penyakit_diderita"  checked> Penyakit Diderita</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="sedang_hamil"       checked> Sedang Hamil</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="catatan_kesehatan"  checked> Catatan Kesehatan</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="status_pendaftaran" checked> Status Pendaftaran</label>
                        <label class="col-item"><input type="checkbox" name="kolom[]" value="kelompok"           checked> No. Kelompok</label>
                    </div>
                </div>

                @if(request()->hasAny(['q','status']))
                <div class="filter-notice">
                    <i class="fas fa-filter" style="flex-shrink:0;margin-top:1px;"></i>
                    <span>
                        Filter aktif akan ikut diterapkan pada data yang diekspor
                        @if(request('q'))(kata kunci: <strong>"{{ request('q') }}"</strong>)@endif
                        @if(request('status'))(status: <strong>{{ request('status') }}</strong>)@endif
                    </span>
                </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeExportModal()">Batal</button>
                <button type="submit" class="btn-export-submit" id="btn-do-export">
                    <i class="fas fa-download"></i> Download Excel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openExportModal()  { document.getElementById('modal-export').classList.add('active'); }
function closeExportModal() { document.getElementById('modal-export').classList.remove('active'); }

function toggleAllColumns(checked) {
    document.querySelectorAll('#form-export input[type=checkbox]').forEach(cb => cb.checked = checked);
    syncExportBtn();
}
function syncExportBtn() {
    const any = [...document.querySelectorAll('#form-export input[type=checkbox]')].some(cb => cb.checked);
    const btn = document.getElementById('btn-do-export');
    btn.disabled      = !any;
}
document.querySelectorAll('#form-export input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', syncExportBtn);
});
</script>
@endsection
