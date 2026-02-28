@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-primary:hover { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .btn-warning { background:#f59e0b; color:#fff; } .btn-warning:hover { background:#d97706; color:#fff; }
    .btn-danger  { background:#ef4444; color:#fff; } .btn-danger:hover  { background:#dc2626; color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }
    .filter-bar { display:flex; align-items:center; gap:12px; background:#fff; border-radius:12px; padding:14px 20px; margin-bottom:16px; box-shadow:0 2px 8px rgba(0,0,0,.06); flex-wrap:wrap; }
    .filter-bar label { font-size:13px; font-weight:600; color:var(--text-primary); white-space:nowrap; display:flex; align-items:center; gap:6px; }
    .filter-bar label i { color:var(--maroon-main); }
    .filter-select { flex:1; min-width:160px; max-width:260px; padding:9px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; cursor:pointer; }
    .filter-select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .filter-count { font-size:12px; color:var(--text-secondary); margin-left:auto; }
    .filter-count strong { color:var(--maroon-main); }
    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; gap:12px; flex-wrap:wrap; }
    .search-box { position:relative; flex:1; max-width:320px; }
    .search-box input { width:100%; padding:9px 14px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }
    .kode-badge { display:inline-block; font-family:'Consolas','Courier New',monospace; font-size:12px; font-weight:700; background:rgba(165,42,42,.08); color:var(--maroon-dark); border:1px solid rgba(165,42,42,.15); padding:3px 10px; border-radius:6px; letter-spacing:.5px; }
    .parent-label { font-size:12px; color:var(--text-secondary); display:flex; align-items:center; gap:4px; }
    .action-btns { display:flex; gap:6px; }
    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; } .empty-state p { font-size:13px; margin:0; }
    .access-notice { display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.3); border-radius:8px; font-size:13px; color:#92400e; margin-bottom:16px; }
    .access-notice i { color:#f59e0b; font-size:16px; flex-shrink:0; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .3s ease; overflow:hidden; }
    @keyframes modalIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid var(--gray-border); }
    .modal-header h3 { font-size:18px; font-weight:700; color:var(--text-primary); margin:0; }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; } .modal-close:hover { color:var(--text-primary); }
    .modal-body { padding:24px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light); }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group input, .form-group select { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .form-group input:focus, .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .delete-info { text-align:center; padding:10px 0; }
    .delete-info i { font-size:48px; color:#ef4444; margin-bottom:15px; display:block; }
    .delete-info p { font-size:14px; color:var(--text-secondary); margin:0 0 8px; }
    .delete-item-name { background:rgba(239,68,68,.1); color:#dc2626; padding:6px 14px; border-radius:6px; font-size:14px; font-weight:700; display:inline-block; }
    @media(max-width:768px){ .page-header{flex-direction:column;align-items:flex-start} .form-row{grid-template-columns:1fr} .filter-bar{flex-direction:column;align-items:flex-start} .filter-select{max-width:100%;width:100%} }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-home" style="color:var(--maroon-main);margin-right:8px;"></i>Manajemen Desa/Kelurahan</h2>
            <p>Data desa/kelurahan berdasarkan kecamatan</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.desa'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')"><i class="fas fa-plus"></i><span>Tambah Desa</span></button>
        @endif
    </div>

    @if(!auth()->user()->hasAccess('tambah.desa') && !auth()->user()->hasAccess('edit.desa') && !auth()->user()->hasAccess('hapus.desa'))
    <div class="access-notice"><i class="fas fa-info-circle"></i>Anda hanya memiliki akses untuk melihat data.</div>
    @endif

    <div class="filter-bar">
        <label><i class="fas fa-map-marker-alt"></i>Provinsi</label>
        <select class="filter-select" id="filterProvinsi" onchange="onProvinsiChange(this.value)">
            <option value="">-- Semua --</option>
            @foreach($provinsiList as $prov)
            <option value="{{ $prov->id }}" {{ $selectedProvinsi == $prov->id ? 'selected' : '' }}>{{ $prov->nama }}</option>
            @endforeach
        </select>
        <label><i class="fas fa-city"></i>Kabupaten</label>
        <select class="filter-select" id="filterKabupaten" onchange="onKabupatenChange(this.value)">
            <option value="">-- Semua --</option>
            @foreach($kabupatenList as $kab)
            <option value="{{ $kab->id }}" {{ $selectedKabupaten == $kab->id ? 'selected' : '' }}>{{ $kab->nama }}</option>
            @endforeach
        </select>
        <label><i class="fas fa-map-signs"></i>Kecamatan</label>
        <select class="filter-select" id="filterKecamatan" onchange="onKecamatanChange(this.value)">
            <option value="">-- Semua --</option>
            @foreach($kecamatanList as $kec)
            <option value="{{ $kec->id }}" {{ $selectedKecamatan == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
            @endforeach
        </select>
        <div class="filter-count">Total: <strong>{{ $desa->count() }}</strong></div>
    </div>

    <div class="table-toolbar">
        <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Cari kode atau nama..." onkeyup="filterTable()"></div>
    </div>

    <div class="table-container">
        @if($desa->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th style="width:110px;">Kode</th>
                    <th>Nama Desa/Kelurahan</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten/Kota</th>
                    @if(auth()->user()->hasAccess('edit.desa') || auth()->user()->hasAccess('hapus.desa'))
                    <th style="width:110px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($desa as $i => $item)
                @php
                    $kec = $item->kecamatan;
                    $kab = $kec?->kabupaten;
                    $prov = $kab?->provinsi;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="kode-badge">{{ $item->kode }}</span></td>
                    <td style="font-weight:600;">{{ $item->nama }}</td>
                    <td><span class="parent-label"><i class="fas fa-map-signs"></i>{{ $kec->nama ?? '-' }}</span></td>
                    <td><span class="parent-label"><i class="fas fa-city"></i>{{ $kab->nama ?? '-' }}</span></td>
                    @if(auth()->user()->hasAccess('edit.desa') || auth()->user()->hasAccess('hapus.desa'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.desa'))
                            <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $item->id }},{{ $prov->id ?? 'null' }},{{ $kab->id ?? 'null' }},{{ $kec->id ?? 'null' }},'{{ addslashes($item->kode) }}','{{ addslashes($item->nama) }}')"><i class="fas fa-edit"></i></button>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.desa'))
                            <button class="btn btn-danger btn-sm" onclick="openDeleteModal({{ $item->id }},'{{ addslashes($item->nama) }}')"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state"><i class="fas fa-home"></i><h3>Belum ada data desa/kelurahan</h3><p>Pilih provinsi, kabupaten, dan kecamatan, lalu tambahkan data desa/kelurahan.</p></div>
        @endif
    </div>
</div>

@if(auth()->user()->hasAccess('tambah.desa'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-plus-circle" style="color:var(--maroon-main);margin-right:8px;"></i>Tambah Desa/Kelurahan</h3><button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button></div>
        <form action="{{ route('desa.store') }}" method="POST">@csrf
            <div class="modal-body">
                <div class="form-group"><label>Provinsi</label>
                    <select id="tambah-provinsi" onchange="loadKabupaten(this.value,'tambah-kabupaten')" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsiList as $prov)<option value="{{ $prov->id }}" {{ $selectedProvinsi == $prov->id ? 'selected' : '' }}>{{ $prov->nama }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label>Kabupaten/Kota</label>
                    <select id="tambah-kabupaten" onchange="loadKecamatan(this.value,'tambah-kecamatan')" required>
                        <option value="">-- Pilih Kabupaten --</option>
                        @foreach($kabupatenList as $kab)<option value="{{ $kab->id }}" {{ $selectedKabupaten == $kab->id ? 'selected' : '' }}>{{ $kab->nama }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label>Kecamatan</label>
                    <select id="tambah-kecamatan" name="kecamatan_id" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatanList as $kec)<option value="{{ $kec->id }}" {{ $selectedKecamatan == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>@endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Kode</label><input type="text" name="kode" placeholder="contoh: 7401011001" required maxlength="10"><div class="form-hint">Kode BPS desa/kelurahan</div></div>
                    <div class="form-group"><label>Nama Desa/Kelurahan</label><input type="text" name="nama" placeholder="contoh: Anawai" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah')">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button></div>
        </form>
    </div>
</div>
@endif

@if(auth()->user()->hasAccess('edit.desa'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-edit" style="color:#f59e0b;margin-right:8px;"></i>Edit Desa/Kelurahan</h3><button class="modal-close" onclick="closeModal('modal-edit')">&times;</button></div>
        <form id="form-edit" method="POST">@csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group"><label>Provinsi</label>
                    <select id="edit-provinsi" onchange="loadKabupaten(this.value,'edit-kabupaten')">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsiList as $prov)<option value="{{ $prov->id }}">{{ $prov->nama }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label>Kabupaten/Kota</label>
                    <select id="edit-kabupaten" onchange="loadKecamatan(this.value,'edit-kecamatan')">
                        <option value="">-- Pilih Kabupaten --</option>
                    </select>
                </div>
                <div class="form-group"><label>Kecamatan</label>
                    <select id="edit-kecamatan" name="kecamatan_id" required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Kode</label><input type="text" id="edit-kode" name="kode" required maxlength="10"></div>
                    <div class="form-group"><label>Nama Desa/Kelurahan</label><input type="text" id="edit-nama" name="nama" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit')">Batal</button><button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button></div>
        </form>
    </div>
</div>
@endif

@if(auth()->user()->hasAccess('hapus.desa'))
<div class="modal-overlay" id="modal-hapus">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Konfirmasi Hapus</h3><button class="modal-close" onclick="closeModal('modal-hapus')">&times;</button></div>
        <form id="form-hapus" method="POST">@csrf @method('DELETE')
            <div class="modal-body"><div class="delete-info"><i class="fas fa-trash-alt"></i><p>Apakah Anda yakin ingin menghapus desa/kelurahan ini?</p><span class="delete-item-name" id="delete-nama"></span></div></div>
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

const baseUrl = '{{ url("/") }}';

function onProvinsiChange(val){
    window.location.href = '{{ route("desa.index") }}' + (val ? '?provinsi_id='+val : '');
}
function onKabupatenChange(val){
    const p = document.getElementById('filterProvinsi').value;
    let params = '?';
    if(p) params+='provinsi_id='+p+'&';
    if(val) params+='kabupaten_id='+val;
    window.location.href = '{{ route("desa.index") }}'+params;
}
function onKecamatanChange(val){
    const p = document.getElementById('filterProvinsi').value;
    const k = document.getElementById('filterKabupaten').value;
    let params = '?';
    if(p) params+='provinsi_id='+p+'&';
    if(k) params+='kabupaten_id='+k+'&';
    if(val) params+='kecamatan_id='+val;
    window.location.href = '{{ route("desa.index") }}'+params;
}

function loadKabupaten(provId, targetId, selectedVal){
    const sel = document.getElementById(targetId);
    sel.innerHTML='<option value="">-- Memuat... --</option>';
    if(!provId){sel.innerHTML='<option value="">-- Pilih Kabupaten --</option>';return;}
    fetch(baseUrl+'/api/kabupaten?provinsi_id='+provId).then(r=>r.json()).then(data=>{
        sel.innerHTML='<option value="">-- Pilih Kabupaten --</option>';
        data.forEach(k=>{const o=document.createElement('option');o.value=k.id;o.textContent=k.nama;if(selectedVal&&k.id==selectedVal)o.selected=true;sel.appendChild(o);});
    });
}

function loadKecamatan(kabId, targetId, selectedVal){
    const sel = document.getElementById(targetId);
    sel.innerHTML='<option value="">-- Memuat... --</option>';
    if(!kabId){sel.innerHTML='<option value="">-- Pilih Kecamatan --</option>';return;}
    fetch(baseUrl+'/api/kecamatan?kabupaten_id='+kabId).then(r=>r.json()).then(data=>{
        sel.innerHTML='<option value="">-- Pilih Kecamatan --</option>';
        data.forEach(k=>{const o=document.createElement('option');o.value=k.id;o.textContent=k.nama;if(selectedVal&&k.id==selectedVal)o.selected=true;sel.appendChild(o);});
    });
}

function openEditModal(id,provId,kabId,kecId,kode,nama){
    document.getElementById('edit-provinsi').value=provId||'';
    document.getElementById('edit-kode').value=kode;
    document.getElementById('edit-nama').value=nama;
    document.getElementById('form-edit').action=baseUrl+'/desa/'+id;
    if(provId){
        loadKabupaten(provId,'edit-kabupaten',kabId);
        setTimeout(()=>{ loadKecamatan(kabId,'edit-kecamatan',kecId); },300);
    }
    openModal('modal-edit');
}
function openDeleteModal(id,nama){document.getElementById('delete-nama').textContent=nama;document.getElementById('form-hapus').action=baseUrl+'/desa/'+id;openModal('modal-hapus')}
function filterTable(){const q=document.getElementById('searchInput').value.toLowerCase();document.querySelectorAll('#tableBody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(q)?'':'none'})}
</script>
@endsection
