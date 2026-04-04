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

    .card-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px; }

    .berita-card { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden; display:flex; flex-direction:column; transition:box-shadow .2s,transform .2s; }
    .berita-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.12); transform:translateY(-2px); }

    .berita-thumb { width:100%; height:160px; object-fit:cover; background:var(--gray-light); }
    .berita-thumb-placeholder { width:100%; height:160px; background:linear-gradient(135deg,rgba(139,0,0,.06),rgba(139,0,0,.12)); display:flex; align-items:center; justify-content:center; color:var(--maroon-main); font-size:36px; opacity:.5; }

    .berita-body { padding:16px; flex:1; display:flex; flex-direction:column; gap:8px; }
    .berita-title { font-size:14px; font-weight:700; color:var(--text-primary); line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .berita-meta  { font-size:11px; color:var(--text-secondary); display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .berita-konten { font-size:12px; color:var(--text-secondary); line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; flex:1; }

    .berita-footer { padding:10px 16px; border-top:1px solid var(--border-light); display:flex; align-items:center; justify-content:space-between; gap:8px; }

    .badge-status { display:inline-flex; align-items:center; gap:4px; border-radius:20px; padding:3px 10px; font-size:11px; font-weight:700; }
    .badge-published { background:rgba(16,185,129,.1); color:#065f46; border:1px solid rgba(16,185,129,.2); }
    .badge-draft     { background:rgba(107,114,128,.08); color:#6b7280; border:1px solid rgba(107,114,128,.15); }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:white; }
    .btn-primary:hover { box-shadow:0 4px 14px rgba(139,0,0,.3); transform:translateY(-1px); }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-outline { background:white; border:1.5px solid var(--gray-border); color:var(--text-primary); }
    .btn-outline:hover { border-color:var(--maroon-main); color:var(--maroon-main); }
    .btn-danger { background:#ef4444; color:white; }
    .btn-danger:hover { background:#dc2626; }

    .empty-state { text-align:center; padding:48px 24px; color:var(--text-secondary); }
    .empty-state i { font-size:3rem; opacity:.3; display:block; margin-bottom:12px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content" style="padding:24px;">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-newspaper" style="color:var(--maroon-main);margin-right:8px;"></i>Berita</h2>
            <p>Kelola berita dan informasi yang akan ditampilkan</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.berita'))
        <a href="{{ route('berita.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Berita
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
            <input type="text" name="search" placeholder="Cari judul berita..." value="{{ request('search') }}">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
        </select>
        @if(request('search') || request('status'))
        <a href="{{ route('berita.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
        <span style="margin-left:auto;font-size:13px;color:var(--text-secondary);">
            {{ $beritaList->total() }} berita
        </span>
    </form>

    @if($beritaList->isEmpty())
        <div class="empty-state">
            <i class="fas fa-newspaper"></i>
            <p>Belum ada berita. <a href="{{ route('berita.create') }}" style="color:var(--maroon-main);">Tambah berita pertama</a>.</p>
        </div>
    @else
    <div class="card-grid">
        @foreach($beritaList as $berita)
        <div class="berita-card">
            @if($berita->gambar)
                <img src="{{ Storage::url($berita->gambar) }}" alt="{{ $berita->judul }}" class="berita-thumb">
            @else
                <div class="berita-thumb-placeholder"><i class="fas fa-newspaper"></i></div>
            @endif
            <div class="berita-body">
                <div class="berita-title">{{ $berita->judul }}</div>
                <div class="berita-meta">
                    <span><i class="fas fa-user"></i> {{ $berita->user?->name ?? '-' }}</span>
                    <span><i class="fas fa-calendar"></i>
                        {{ $berita->isPublished() ? $berita->published_at?->format('d/m/Y') : $berita->created_at->format('d/m/Y') }}
                    </span>
                </div>
                <div class="berita-konten">{{ strip_tags($berita->konten) }}</div>
            </div>
            <div class="berita-footer">
                <span class="badge-status {{ $berita->isPublished() ? 'badge-published' : 'badge-draft' }}">
                    <i class="fas {{ $berita->isPublished() ? 'fa-globe' : 'fa-pen' }}"></i>
                    {{ $berita->isPublished() ? 'Published' : 'Draft' }}
                </span>
                <div style="display:flex;gap:6px;">
                    @if($berita->isPublished())
                    <a href="{{ route('berita.show', $berita->slug) }}" target="_blank" class="btn btn-outline btn-sm" title="Lihat detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('edit.berita'))
                    <a href="{{ route('berita.edit', $berita) }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    @endif
                    @if(auth()->user()->hasAccess('hapus.berita'))
                    <form action="{{ route('berita.destroy', $berita) }}" method="POST"
                          onsubmit="return confirm('Hapus berita ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:20px;">
        {{ $beritaList->links() }}
    </div>
    @endif

</div>
@endsection
