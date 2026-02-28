@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-primary:hover { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .btn-warning { background:#f59e0b; color:#fff; } .btn-warning:hover { background:#d97706; color:#fff; }
    .btn-danger  { background:#ef4444; color:#fff; } .btn-danger:hover  { background:#dc2626; color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }
    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; gap:15px; flex-wrap:wrap; }
    .search-box { position:relative; flex:1; max-width:350px; }
    .search-box input { width:100%; padding:10px 14px 10px 38px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; transition:all .3s; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:14px; }
    .table-info { font-size:13px; color:var(--text-secondary); }
    .kode-badge { display:inline-block; font-family:'Consolas','Courier New',monospace; font-size:12px; font-weight:700; background:rgba(165,42,42,.08); color:var(--maroon-dark); border:1px solid rgba(165,42,42,.15); padding:3px 10px; border-radius:6px; letter-spacing:.5px; }
    .count-badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; padding:3px 8px; border-radius:20px; background:rgba(59,130,246,.1); color:#1d4ed8; }
    .action-btns { display:flex; gap:6px; }
    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
    .empty-state p  { font-size:13px; margin:0; }
    .access-notice { display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.3); border-radius:8px; font-size:13px; color:#92400e; margin-bottom:20px; }
    .access-notice i { color:#f59e0b; font-size:16px; flex-shrink:0; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .3s ease; overflow:hidden; }
    @keyframes modalIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid var(--gray-border); }
    .modal-header h3 { font-size:18px; font-weight:700; color:var(--text-primary); margin:0; }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-close:hover { color:var(--text-primary); }
    .modal-body { padding:24px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light); }
    .form-group { margin-bottom:18px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group input, .form-group select { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; transition:all .3s; background:#fff; }
    .form-group input:focus, .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .delete-info { text-align:center; padding:10px 0; }
    .delete-info i { font-size:48px; color:#ef4444; margin-bottom:15px; display:block; }
    .delete-info p { font-size:14px; color:var(--text-secondary); margin:0 0 8px; }
    .delete-item-name { background:rgba(239,68,68,.1); color:#dc2626; padding:6px 14px; border-radius:6px; font-size:14px; font-weight:700; display:inline-block; }
    @media(max-width:768px){ .page-header{flex-direction:column;align-items:flex-start} .table-toolbar{flex-direction:column;align-items:flex-start} .search-box{max-width:100%;width:100%} }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-map-marker-alt" style="color:var(--maroon-main);margin-right:8px;"></i>Manajemen Provinsi</h2>
            <p>Data provinsi di seluruh Indonesia</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.provinsi'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')"><i class="fas fa-plus"></i><span>Tambah Provinsi</span></button>
        @endif
    </div>

    @if(!auth()->user()->hasAccess('tambah.provinsi') && !auth()->user()->hasAccess('edit.provinsi') && !auth()->user()->hasAccess('hapus.provinsi'))
    <div class="access-notice"><i class="fas fa-info-circle"></i>Anda hanya memiliki akses untuk melihat data.</div>
    @endif

    <div class="table-toolbar">
        <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Cari kode atau nama provinsi..." onkeyup="filterTable()"></div>
        <div class="table-info">Total: <strong>{{ $provinsi->count() }}</strong> provinsi</div>
    </div>

    <div class="table-container">
        @if($provinsi->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th style="width:120px;">Kode</th>
                    <th>Nama Provinsi</th>
                    <th style="width:130px;">Jml Kabupaten</th>
                    @if(auth()->user()->hasAccess('edit.provinsi') || auth()->user()->hasAccess('hapus.provinsi'))
                    <th style="width:130px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($provinsi as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="kode-badge">{{ $item->kode }}</span></td>
                    <td style="font-weight:600;">{{ $item->nama }}</td>
                    <td><span class="count-badge"><i class="fas fa-city"></i>{{ $item->kabupaten_count }}</span></td>
                    @if(auth()->user()->hasAccess('edit.provinsi') || auth()->user()->hasAccess('hapus.provinsi'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.provinsi'))
                            <button class="btn btn-warning btn-sm" title="Edit" onclick="openEditModal({{ $item->id }},'{{ addslashes($item->kode) }}','{{ addslashes($item->nama) }}')"><i class="fas fa-edit"></i></button>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.provinsi'))
                            <button class="btn btn-danger btn-sm" title="Hapus" onclick="openDeleteModal({{ $item->id }},'{{ addslashes($item->nama) }}')"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-map-marker-alt"></i>
            <h3>Belum ada data provinsi</h3>
            @if(auth()->user()->hasAccess('tambah.provinsi'))<p>Klik tombol "Tambah Provinsi" untuk menambahkan data baru.</p>@else<p>Belum ada data yang tersedia.</p>@endif
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->hasAccess('tambah.provinsi'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-plus-circle" style="color:var(--maroon-main);margin-right:8px;"></i>Tambah Provinsi</h3><button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button></div>
        <form action="{{ route('provinsi.store') }}" method="POST">@csrf
            <div class="modal-body">
                <div class="form-group"><label>Kode Provinsi</label><input type="text" name="kode" placeholder="contoh: 74" required maxlength="10"><div class="form-hint">Kode BPS provinsi</div></div>
                <div class="form-group"><label>Nama Provinsi</label><input type="text" name="nama" placeholder="contoh: Sulawesi Tenggara" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah')">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button></div>
        </form>
    </div>
</div>
@endif

@if(auth()->user()->hasAccess('edit.provinsi'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-edit" style="color:#f59e0b;margin-right:8px;"></i>Edit Provinsi</h3><button class="modal-close" onclick="closeModal('modal-edit')">&times;</button></div>
        <form id="form-edit" method="POST">@csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group"><label>Kode Provinsi</label><input type="text" id="edit-kode" name="kode" required maxlength="10"></div>
                <div class="form-group"><label>Nama Provinsi</label><input type="text" id="edit-nama" name="nama" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit')">Batal</button><button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button></div>
        </form>
    </div>
</div>
@endif

@if(auth()->user()->hasAccess('hapus.provinsi'))
<div class="modal-overlay" id="modal-hapus">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Konfirmasi Hapus</h3><button class="modal-close" onclick="closeModal('modal-hapus')">&times;</button></div>
        <form id="form-hapus" method="POST">@csrf @method('DELETE')
            <div class="modal-body"><div class="delete-info"><i class="fas fa-trash-alt"></i><p>Apakah Anda yakin ingin menghapus provinsi ini?</p><span class="delete-item-name" id="delete-nama"></span></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-hapus')">Batal</button><button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button></div>
        </form>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
function openModal(id){document.getElementById(id)?.classList.add('active')}
function closeModal(id){document.getElementById(id)?.classList.remove('active')}
document.querySelectorAll('.modal-overlay').forEach(o=>{o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('active')})});
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(m=>m.classList.remove('active'))});
function openEditModal(id,kode,nama){document.getElementById('edit-kode').value=kode;document.getElementById('edit-nama').value=nama;document.getElementById('form-edit').action='{{ url("provinsi") }}/'+id;openModal('modal-edit')}
function openDeleteModal(id,nama){document.getElementById('delete-nama').textContent=nama;document.getElementById('form-hapus').action='{{ url("provinsi") }}/'+id;openModal('modal-hapus')}
function filterTable(){const q=document.getElementById('searchInput').value.toLowerCase();document.querySelectorAll('#tableBody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(q)?'':'none'})}
</script>
@endsection
