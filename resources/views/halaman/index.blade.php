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

    .table-wrap { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden; }
    table { width:100%; border-collapse:collapse; font-size:13px; }
    thead th { background:var(--gray-light); padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.04em; white-space:nowrap; }
    tbody td { padding:12px 14px; border-bottom:1px solid var(--border-light); vertical-align:middle; }
    tbody tr:last-child td { border-bottom:none; }
    tbody tr:hover td { background:rgba(139,0,0,.02); }

    .badge { display:inline-flex; align-items:center; gap:4px; border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; }
    .badge-published { background:rgba(16,185,129,.1); color:#065f46; border:1px solid rgba(16,185,129,.2); }
    .badge-draft     { background:rgba(107,114,128,.08); color:#6b7280; border:1px solid rgba(107,114,128,.15); }

    .judul-cell { font-weight:600; color:var(--text-primary); max-width:280px; }
    .judul-cell small { font-weight:400; color:var(--text-secondary); display:block; margin-top:2px; font-size:11px; font-family:monospace; }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:white; }
    .btn-primary:hover { box-shadow:0 4px 14px rgba(139,0,0,.3); transform:translateY(-1px); }
    .btn-sm { padding:5px 10px; font-size:11px; }
    .btn-outline { background:white; border:1.5px solid var(--gray-border); color:var(--text-primary); }
    .btn-outline:hover { border-color:var(--maroon-main); color:var(--maroon-main); }
    .btn-danger  { background:#ef4444; color:white; }
    .btn-danger:hover  { background:#dc2626; }

    .actions { display:flex; gap:6px; flex-wrap:wrap; }
    .empty-state { text-align:center; padding:48px 24px; color:var(--text-secondary); }
    .empty-state i { font-size:3rem; opacity:.3; display:block; margin-bottom:12px; }

    .url-pill { display:inline-flex; align-items:center; gap:5px; background:#f3f4f6; border-radius:6px; padding:3px 8px; font-size:11px; font-family:monospace; color:var(--maroon-main); cursor:pointer; }
    .url-pill:hover { background:#e8b4b840; }
</style>
@endsection

@section('konten')
<div class="dashboard-content" style="padding:24px;">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-file-lines" style="color:var(--maroon-main);margin-right:8px;"></i>Halaman Konten</h2>
            <p>Kelola halaman konten yang dapat diakses publik</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.halaman'))
        <a href="{{ route('halaman.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Halaman
        </a>
        @endif
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#065f46;font-size:13px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    <form method="GET" class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari judul halaman..." value="{{ request('search') }}">
        </div>
        @if(request('search'))
        <a href="{{ route('halaman.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
        <span style="margin-left:auto;font-size:13px;color:var(--text-secondary);">
            {{ $halamanList->total() }} halaman
        </span>
    </form>

    @if($halamanList->isEmpty())
        <div class="empty-state">
            <i class="fas fa-file-lines"></i>
            <p>Belum ada halaman. <a href="{{ route('halaman.create') }}" style="color:var(--maroon-main);">Tambah halaman pertama</a>.</p>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul & URL</th>
                    <th>Dibuat Oleh</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($halamanList as $i => $h)
                <tr>
                    <td style="color:var(--text-secondary);font-size:12px;">
                        {{ $halamanList->firstItem() + $i }}
                    </td>
                    <td class="judul-cell">
                        {{ $h->judul }}
                        <small>
                            <span class="url-pill" onclick="copyUrl('/halaman/{{ $h->slug }}')" title="Klik untuk salin URL">
                                <i class="fas fa-link"></i> /halaman/{{ $h->slug }}
                            </span>
                        </small>
                    </td>
                    <td style="font-size:12px;">{{ $h->user?->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $h->is_published ? 'badge-published' : 'badge-draft' }}">
                            <i class="fas {{ $h->is_published ? 'fa-globe' : 'fa-pen' }}"></i>
                            {{ $h->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            @if($h->is_published)
                            <a href="{{ route('halaman.show', $h->slug) }}" target="_blank" class="btn btn-outline btn-sm" title="Lihat halaman">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            @if(auth()->user()->hasAccess('edit.halaman'))
                            <a href="{{ route('halaman.edit', $h) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.halaman'))
                            <form action="{{ route('halaman.destroy', $h) }}" method="POST"
                                  onsubmit="return confirm('Hapus halaman ini?')">
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
        {{ $halamanList->links() }}
    </div>
    @endif

</div>
@endsection

@section('js')
<script>
function copyUrl(path) {
    var url = window.location.origin + path;
    navigator.clipboard.writeText(url).then(function() {
        alert('URL disalin: ' + url);
    });
}
</script>
@endsection
