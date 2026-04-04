@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:white; }
    .btn-primary:hover { box-shadow:0 4px 14px rgba(139,0,0,.3); transform:translateY(-1px); }
    .btn-sm { padding:5px 10px; font-size:11px; }
    .btn-outline { background:white; border:1.5px solid var(--gray-border); color:var(--text-primary); }
    .btn-outline:hover { border-color:var(--maroon-main); color:var(--maroon-main); }
    .btn-danger  { background:#ef4444; color:white; }
    .btn-danger:hover  { background:#dc2626; }

    .tree-card { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden; margin-bottom:12px; }
    .tree-row  { display:flex; align-items:center; padding:12px 16px; gap:12px; border-bottom:1px solid var(--border-light); }
    .tree-row:last-child { border-bottom:none; }
    .tree-row.parent-row { background:#fafafa; }
    .tree-row.child-row  { background:#fff; padding-left:40px; }

    .menu-icon-preview { width:30px; height:30px; border-radius:7px; background:rgba(139,0,0,.08); color:var(--maroon-main); display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
    .menu-label { font-size:13px; font-weight:700; color:var(--text-primary); flex:1; }
    .menu-url   { font-size:11px; color:var(--text-secondary); font-family:monospace; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .menu-meta  { display:flex; gap:8px; align-items:center; flex-shrink:0; }

    .badge { display:inline-flex; align-items:center; gap:4px; border-radius:20px; padding:2px 9px; font-size:10px; font-weight:700; }
    .badge-aktif   { background:rgba(16,185,129,.1); color:#065f46; border:1px solid rgba(16,185,129,.2); }
    .badge-nonaktif{ background:rgba(107,114,128,.08); color:#6b7280; border:1px solid rgba(107,114,128,.15); }
    .badge-blank   { background:rgba(59,130,246,.1); color:#1d4ed8; border:1px solid rgba(59,130,246,.2); }

    .actions { display:flex; gap:6px; }
    .empty-state { text-align:center; padding:48px 24px; color:var(--text-secondary); }
    .empty-state i { font-size:3rem; opacity:.3; display:block; margin-bottom:12px; }

    .hint-box { background:rgba(59,130,246,.05); border:1px solid rgba(59,130,246,.15); border-radius:10px; padding:14px 18px; margin-bottom:20px; font-size:13px; color:#1d4ed8; }
    .hint-box i { margin-right:6px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content" style="padding:24px;">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-bars" style="color:var(--maroon-main);margin-right:8px;"></i>Menu Navigasi</h2>
            <p>Kelola menu dan sub-menu yang tampil di halaman utama (home page)</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.menu'))
        <a href="{{ route('menu.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Menu
        </a>
        @endif
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#065f46;font-size:13px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    <div class="hint-box">
        <i class="fas fa-info-circle"></i>
        Menu <strong>top-level</strong> tampil langsung di nav bar home page. <strong>Sub-menu</strong> muncul sebagai dropdown di bawah menu induknya.
        Untuk menautkan ke halaman konten, gunakan URL <code>/halaman/{slug}</code>.
    </div>

    @if($menus->isEmpty())
        <div class="empty-state">
            <i class="fas fa-bars"></i>
            <p>Belum ada menu. <a href="{{ route('menu.create') }}" style="color:var(--maroon-main);">Tambah menu pertama</a>.</p>
        </div>
    @else
        @foreach($menus as $menu)
        <div class="tree-card">
            {{-- Parent --}}
            <div class="tree-row parent-row">
                <div class="menu-icon-preview">
                    @if($menu->icon)<i class="{{ $menu->icon }}"></i>@else<i class="fas fa-link"></i>@endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="menu-label">{{ $menu->label }}</div>
                    <div class="menu-url">{{ $menu->url ?: '—' }}</div>
                </div>
                <div class="menu-meta">
                    <span style="font-size:11px;color:var(--text-secondary);">Urutan {{ $menu->urutan }}</span>
                    @if($menu->target === '_blank')
                    <span class="badge badge-blank"><i class="fas fa-arrow-up-right-from-square"></i> _blank</span>
                    @endif
                    <span class="badge {{ $menu->is_active ? 'badge-aktif' : 'badge-nonaktif' }}">
                        <i class="fas {{ $menu->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <div class="actions">
                        @if(auth()->user()->hasAccess('edit.menu'))
                        <a href="{{ route('menu.edit', $menu) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>
                        @endif
                        @if(auth()->user()->hasAccess('hapus.menu'))
                        <form action="{{ route('menu.destroy', $menu) }}" method="POST"
                              onsubmit="return confirm('Hapus menu ini? Sub-menu di dalamnya juga akan terhapus.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Children --}}
            @foreach($menu->children as $child)
            <div class="tree-row child-row">
                <i class="fas fa-corner-down-right" style="color:var(--text-secondary);font-size:12px;margin-right:4px;"></i>
                <div class="menu-icon-preview" style="width:26px;height:26px;font-size:11px;">
                    @if($child->icon)<i class="{{ $child->icon }}"></i>@else<i class="fas fa-link"></i>@endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="menu-label" style="font-size:12px;">{{ $child->label }}</div>
                    <div class="menu-url">{{ $child->url ?: '—' }}</div>
                </div>
                <div class="menu-meta">
                    <span style="font-size:11px;color:var(--text-secondary);">Urutan {{ $child->urutan }}</span>
                    @if($child->target === '_blank')
                    <span class="badge badge-blank"><i class="fas fa-arrow-up-right-from-square"></i> _blank</span>
                    @endif
                    <span class="badge {{ $child->is_active ? 'badge-aktif' : 'badge-nonaktif' }}">
                        <i class="fas {{ $child->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <div class="actions">
                        @if(auth()->user()->hasAccess('edit.menu'))
                        <a href="{{ route('menu.edit', $child) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>
                        @endif
                        @if(auth()->user()->hasAccess('hapus.menu'))
                        <form action="{{ route('menu.destroy', $child) }}" method="POST"
                              onsubmit="return confirm('Hapus sub-menu ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    @endif

</div>
@endsection
