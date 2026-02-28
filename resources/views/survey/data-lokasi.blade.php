@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; } .btn-primary:hover { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .filter-bar { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; align-items:center; }
    .filter-bar select { padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .filter-bar select:focus { outline:none; border-color:var(--maroon-main); }
    .search-box { position:relative; flex:1; max-width:350px; }
    .search-box input { width:100%; padding:10px 14px 10px 38px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:14px; }
    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; gap:15px; flex-wrap:wrap; }
    .table-info { font-size:13px; color:var(--text-secondary); }
    .kelompok-number { display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; font-size:16px; font-weight:800; flex-shrink:0; }
    .lokasi-block { font-size:12px; line-height:1.6; }
    .lokasi-block strong { color:var(--text-primary); font-size:13px; font-weight:700; display:block; }
    .lokasi-block span { color:var(--text-secondary); }
    .surveyor-block { font-size:12px; line-height:1.6; }
    .surveyor-block .ketua { color:var(--text-primary); font-weight:600; display:block; }
    .surveyor-block .anggota { color:var(--text-secondary); white-space:pre-line; }
    .stat-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:16px 20px; text-align:center; }
    .stat-card .stat-num { font-size:28px; font-weight:800; color:var(--maroon-main); line-height:1; }
    .stat-card .stat-label { font-size:12px; color:var(--text-secondary); margin-top:4px; }
    .peserta-info { font-size:12px; line-height:1.7; }
    .peserta-info .pi-row { display:flex; align-items:center; gap:5px; }
    .peserta-info .pi-row i { width:13px; color:var(--text-secondary); font-size:10px; }
    .badge-zero { color:var(--text-secondary); }
    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
    .gmaps-link { color:#3b82f6; font-size:11px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-map-marked-alt" style="color:var(--maroon-main);margin-right:8px;"></i>Data Lokasi KKA</h2>
            <p>Daftar lokasi yang telah ditetapkan nomor kelompoknya</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#059669;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
        <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Stat cards --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-num">{{ $surveys->count() }}</div>
            <div class="stat-label">Total Lokasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $surveys->pluck('kelompok')->unique()->count() }}</div>
            <div class="stat-label">Jumlah Kelompok</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $surveys->pluck('kegiatan_id')->unique()->filter()->count() }}</div>
            <div class="stat-label">Kegiatan Terkait</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $surveys->sum('peserta_count') }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('survey.data-lokasi') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <select name="kegiatan_id" onchange="this.form.submit()">
                <option value="">Semua Kegiatan</option>
                @foreach($kegiatan as $k)
                <option value="{{ $k->id }}" {{ request('kegiatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari desa atau nomor kelompok...">
            </div>
            <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-search"></i> Cari</button>
            @if(request()->hasAny(['kegiatan_id','q']))
            <a href="{{ route('survey.data-lokasi') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-toolbar">
        <div class="table-info">Total: <strong>{{ $surveys->count() }}</strong> lokasi</div>
    </div>

    <div class="table-container">
        @if($surveys->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:70px;text-align:center;">Kelompok</th>
                    <th>Lokasi</th>
                    <th>Ketua Surveyor &amp; Tim</th>
                    <th>Kegiatan</th>
                    <th>Nama Kades</th>
                    <th style="width:110px;">Peserta &amp; DPL</th>
                    <th style="width:100px;">Maps</th>
                    @if(auth()->user()->hasAccess('atur.kelompok'))
                    <th style="width:110px;">Setup</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $item)
                <tr>
                    <td style="text-align:center;">
                        <span class="kelompok-number">{{ $item->kelompok }}</span>
                    </td>
                    <td>
                        <div class="lokasi-block">
                            <strong>{{ $item->desa?->nama ?? '-' }}</strong>
                            <span>{{ $item->desa?->kecamatan?->nama }}, {{ $item->desa?->kecamatan?->kabupaten?->nama }}</span>
                            <span>{{ $item->desa?->kecamatan?->kabupaten?->provinsi?->nama }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="surveyor-block">
                            <span class="ketua"><i class="fas fa-star" style="color:#f59e0b;font-size:10px;margin-right:3px;"></i>{{ $item->surveyor?->name ?? '-' }}</span>
                            @if($item->tim_anggota)
                            <span class="anggota">{{ $item->tim_anggota }}</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $item->kegiatan?->nama ?? '-' }}</td>
                    <td>{{ $item->nama_kades ?? '-' }}</td>
                    <td>
                        <div class="peserta-info">
                            <div class="pi-row {{ $item->peserta_count == 0 ? 'badge-zero' : '' }}">
                                <i class="fas fa-user-graduate"></i>
                                <span>{{ $item->peserta_count }} peserta</span>
                            </div>
                            <div class="pi-row {{ $item->dosen_pembimbing_count == 0 ? 'badge-zero' : '' }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>{{ $item->dosen_pembimbing_count }} DPL</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($item->gmaps_url)
                        <a href="{{ $item->gmaps_url }}" target="_blank" class="gmaps-link">
                            <i class="fas fa-map-marker-alt"></i> Buka Maps
                        </a>
                        @else
                        <span style="color:var(--text-secondary);font-size:12px;">—</span>
                        @endif
                    </td>
                    @if(auth()->user()->hasAccess('atur.kelompok'))
                    <td>
                        <a href="{{ route('kelompok.setup', $item->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-cog"></i> Setup
                        </a>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-map-marked-alt"></i>
            <h3>Belum ada data lokasi</h3>
            <p>Lokasi akan muncul setelah nomor kelompok ditetapkan pada menu Hasil Survey.</p>
        </div>
        @endif
    </div>
</div>
@endsection
