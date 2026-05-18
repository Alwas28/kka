@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:20px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .breadcrumb { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--text-secondary); margin-bottom:18px; }
    .breadcrumb a { color:var(--maroon-main); text-decoration:none; font-weight:600; }
    .breadcrumb a:hover { text-decoration:underline; }

    .kegiatan-info { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; flex-wrap:wrap; gap:16px; align-items:center; }
    .ki-item { font-size:13px; }
    .ki-item .ki-label { font-size:11px; color:var(--text-secondary); margin-bottom:2px; }
    .ki-item .ki-val   { font-weight:700; color:var(--text-primary); }
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
    .search-box { position:relative; flex:1; max-width:320px; }
    .search-box input { width:100%; padding:9px 13px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }

    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .table-info { font-size:13px; color:var(--text-secondary); }

    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .badge-submit { background:rgba(16,185,129,.12); color:#059669; }
    .badge-draft  { background:rgba(245,158,11,.12); color:#d97706; }
    .badge-belum  { background:#f3f4f6; color:#9ca3af; }

    .mahasiswa-block strong { font-size:13px; font-weight:700; color:var(--text-primary); display:block; }
    .mahasiswa-block span   { font-size:12px; color:var(--text-secondary); }

    .btn { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; }
    .btn-sm { padding:5px 10px; font-size:11px; }
    .btn-profil { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-profil:hover { box-shadow:0 3px 10px rgba(165,42,42,.35); transform:translateY(-1px); color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); }
    .btn-secondary:hover { background:#d1d5db; }
    .btn-back { background:var(--gray-border); color:var(--text-primary); }
    .btn-back:hover { background:#d1d5db; }

    .no-kelompok { font-size:11px; color:#9ca3af; }

    .empty-state { text-align:center; padding:50px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:40px; color:var(--gray-border); margin-bottom:12px; display:block; }
    .empty-state h3 { font-size:15px; color:var(--text-primary); margin-bottom:6px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('rekap.pendaftaran') }}"><i class="fas fa-chart-bar"></i> Rekap Pendaftaran</a>
        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
        <span>Detail</span>
    </div>

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-users" style="color:var(--maroon-main);margin-right:8px;"></i>{{ $kegiatan->nama }}</h2>
            <p>Daftar mahasiswa yang mendaftar pada kegiatan ini</p>
        </div>
        <a href="{{ route('rekap.pendaftaran') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Info kegiatan --}}
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

    {{-- Stat cards --}}
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

    {{-- Filter --}}
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
            <a href="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-toolbar">
        <div class="table-info">Menampilkan <strong>{{ $mahasiswaList->firstItem() ?? 0 }}–{{ $mahasiswaList->lastItem() ?? 0 }}</strong> dari <strong>{{ $mahasiswaList->total() }}</strong> mahasiswa</div>
    </div>

    <div class="table-container">
        @if($mahasiswaList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:120px;">NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Program Studi</th>
                    <th style="width:100px;">Level</th>
                    <th style="width:120px;">Status Pendaftaran</th>
                    <th style="width:110px;text-align:center;">Profil</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $i => $mhs)
                @php
                    $surveyLokasiId = $kelompokMap->get($mhs->mahasiswa_id);
                @endphp
                <tr>
                    <td style="color:var(--text-secondary);font-size:13px;">{{ $mahasiswaList->firstItem() + $i }}</td>
                    <td style="font-size:13px;font-weight:600;color:var(--text-secondary);">{{ $mhs->nim }}</td>
                    <td>
                        <div class="mahasiswa-block">
                            <strong>{{ $mhs->nama }}</strong>
                            <span>{{ $mhs->email }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:var(--text-secondary);">{{ $mhs->prodi_nama ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--text-secondary);">{{ $mhs->level_nama ?? '—' }}</td>
                    <td>
                        @if($mhs->pendaftaran_status === 'submitted')
                            <span class="badge badge-submit"><i class="fas fa-check-circle"></i> Submit</span>
                        @elseif($mhs->pendaftaran_status === 'draft')
                            <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> Draft</span>
                        @else
                            <span class="badge badge-belum"><i class="fas fa-minus-circle"></i> Belum Isi</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($surveyLokasiId)
                            <a href="{{ route('mahasiswa.profil', $mhs->mahasiswa_id) }}?survey_lokasi_id={{ $surveyLokasiId }}"
                               class="btn btn-profil btn-sm">
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

        {{-- Pagination --}}
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
                    Tidak ada mahasiswa yang sesuai filter yang dipilih.
                @else
                    Belum ada mahasiswa yang mendaftar pada kegiatan ini.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
