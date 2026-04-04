@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .toolbar { display:flex; align-items:center; gap:10px; margin-bottom:14px; flex-wrap:wrap; }
    .search-box { position:relative; flex:1; max-width:300px; }
    .search-box input { width:100%; padding:9px 14px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; box-sizing:border-box; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }
    .filter-select { padding:9px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:white; }
    .filter-select:focus { outline:none; border-color:var(--maroon-main); }

    .table-wrap { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden; }
    table { width:100%; border-collapse:collapse; font-size:13px; }
    thead th { background:var(--gray-light); padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.04em; white-space:nowrap; }
    tbody td { padding:12px 14px; border-bottom:1px solid var(--border-light); vertical-align:middle; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:rgba(139,0,0,.02); }

    .badge { display:inline-flex; align-items:center; gap:4px; border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; }
    .badge-aktif       { background:rgba(16,185,129,.1); color:#065f46; border:1px solid rgba(16,185,129,.2); }
    .badge-tidak-aktif { background:rgba(107,114,128,.08); color:#6b7280; border:1px solid rgba(107,114,128,.15); }
    .badge-penting     { background:rgba(239,68,68,.1); color:#b91c1c; border:1px solid rgba(239,68,68,.2); }

    .judul-cell { font-weight:600; color:var(--text-primary); max-width:280px; }
    .judul-cell small { font-weight:400; color:var(--text-secondary); display:block; margin-top:2px; font-size:11px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:280px; }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:white; }
    .btn-primary:hover { box-shadow:0 4px 14px rgba(139,0,0,.3); transform:translateY(-1px); }
    .btn-sm { padding:5px 10px; font-size:11px; }
    .btn-outline { background:white; border:1.5px solid var(--gray-border); color:var(--text-primary); }
    .btn-outline:hover { border-color:var(--maroon-main); color:var(--maroon-main); }
    .btn-danger  { background:#ef4444; color:white; }
    .btn-danger:hover  { background:#dc2626; }
    .btn-success { background:#10b981; color:white; }
    .btn-success:hover { background:#059669; }
    .btn-warning { background:#f59e0b; color:white; }
    .btn-warning:hover { background:#d97706; }

    .actions { display:flex; gap:6px; flex-wrap:wrap; }
    .empty-state { text-align:center; padding:48px 24px; color:var(--text-secondary); }
    .empty-state i { font-size:3rem; opacity:.3; display:block; margin-bottom:12px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content" style="padding:24px;">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-bullhorn" style="color:var(--maroon-main);margin-right:8px;"></i>Pengumuman</h2>
            <p>Kelola pengumuman untuk mahasiswa dan civitas akademika</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.pengumuman'))
        <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pengumuman
        </a>
        @endif
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#065f46;font-size:13px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <form method="GET" class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari judul pengumuman..." value="{{ request('search') }}">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif"       {{ request('status') === 'aktif'       ? 'selected' : '' }}>Aktif</option>
            <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        @if(request('search') || request('status'))
        <a href="{{ route('pengumuman.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
        <span style="margin-left:auto;font-size:13px;color:var(--text-secondary);">
            {{ $pengumumanList->total() }} pengumuman
        </span>
    </form>

    @if($pengumumanList->isEmpty())
        <div class="empty-state">
            <i class="fas fa-bullhorn"></i>
            <p>Belum ada pengumuman. <a href="{{ route('pengumuman.create') }}" style="color:var(--maroon-main);">Tambah pengumuman</a>.</p>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Periode</th>
                    <th>Dibuat Oleh</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengumumanList as $i => $p)
                <tr>
                    <td style="color:var(--text-secondary);font-size:12px;">
                        {{ $pengumumanList->firstItem() + $i }}
                    </td>
                    <td class="judul-cell">
                        @if($p->is_penting)
                            <span class="badge badge-penting" style="margin-bottom:4px;">
                                <i class="fas fa-exclamation"></i> Penting
                            </span><br>
                        @endif
                        {{ $p->judul }}
                        <small>{{ Str::limit(strip_tags($p->konten), 80) }}</small>
                    </td>
                    <td style="font-size:12px;white-space:nowrap;">
                        <div>{{ $p->tanggal_mulai->format('d/m/Y') }}</div>
                        @if($p->tanggal_selesai)
                        <div style="color:var(--text-secondary);">s/d {{ $p->tanggal_selesai->format('d/m/Y') }}</div>
                        @else
                        <div style="color:var(--text-secondary);">Tidak terbatas</div>
                        @endif
                    </td>
                    <td style="font-size:12px;">{{ $p->user?->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $p->isAktif() ? 'badge-aktif' : 'badge-tidak-aktif' }}">
                            <i class="fas {{ $p->isAktif() ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            {{ $p->isAktif() ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('pengumuman.show', $p) }}" target="_blank" class="btn btn-outline btn-sm" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->hasAccess('edit.pengumuman'))
                            <form action="{{ route('pengumuman.toggle', $p) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $p->isAktif() ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $p->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas {{ $p->isAktif() ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('pengumuman.edit', $p) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.pengumuman'))
                            <form action="{{ route('pengumuman.destroy', $p) }}" method="POST"
                                  onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">
        {{ $pengumumanList->links() }}
    </div>
    @endif

</div>
@endsection
