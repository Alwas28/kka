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
    .btn-info    { background:#3b82f6; color:#fff; } .btn-info:hover    { background:#2563eb; color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }
    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; gap:15px; flex-wrap:wrap; }
    .search-box { position:relative; flex:1; max-width:350px; }
    .search-box input { width:100%; padding:10px 14px 10px 38px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:14px; }
    .table-info { font-size:13px; color:var(--text-secondary); }
    .filter-bar { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; align-items:center; }
    .filter-bar select { padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .status-badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; }
    .status-belum     { background:rgba(107,114,128,.1); color:#4b5563; }
    .status-sudah     { background:rgba(59,130,246,.1); color:#1d4ed8; }
    .status-disetujui { background:rgba(16,185,129,.1); color:#059669; }
    .status-ditolak   { background:rgba(239,68,68,.1); color:#dc2626; }
    .lokasi-text { font-size:12px; color:var(--text-secondary); line-height:1.5; }
    .lokasi-text strong { color:var(--text-primary); font-size:13px; display:block; }
    .surveyor-block { font-size:12px; line-height:1.6; }
    .surveyor-block .ketua { color:var(--text-primary); font-weight:600; }
    .surveyor-block .ketua-email { color:var(--text-secondary); }
    .surveyor-block .anggota-label { font-size:11px; font-weight:600; color:var(--text-secondary); margin-top:4px; display:block; }
    .surveyor-block .anggota-list { color:var(--text-primary); font-size:12px; white-space:pre-line; }
    .action-btns { display:flex; gap:6px; flex-wrap:wrap; }
    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:620px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .3s ease; overflow:hidden; }
    @keyframes modalIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid var(--gray-border); }
    .modal-header h3 { font-size:18px; font-weight:700; color:var(--text-primary); margin:0; }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-body { padding:24px; max-height:75vh; overflow-y:auto; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light); }
    .form-group { margin-bottom:18px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; transition:all .3s; background:#fff; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
    .delete-info { text-align:center; padding:10px 0; }
    .delete-info i { font-size:48px; color:#ef4444; margin-bottom:15px; display:block; }
    .delete-info p { font-size:14px; color:var(--text-secondary); margin:0 0 8px; }
    .delete-item-name { background:rgba(239,68,68,.1); color:#dc2626; padding:6px 14px; border-radius:6px; font-size:14px; font-weight:700; display:inline-block; }
    @media(max-width:768px){ .form-row{grid-template-columns:1fr} }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-users" style="color:var(--maroon-main);margin-right:8px;"></i>TIM Survey</h2>
            <p>{{ $isAdmin ? 'Semua lokasi survey' : 'Lokasi survey yang ditugaskan kepada Anda' }}</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.survey'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')"><i class="fas fa-plus"></i><span>Tambah Lokasi</span></button>
        @endif
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#059669;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
        <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
    </div>
    @endif

    @if($isAdmin)
    <div class="filter-bar">
        <form method="GET" action="{{ route('survey.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="belum_survey" {{ request('status')=='belum_survey'?'selected':'' }}>Belum Survey</option>
                <option value="sudah_survey" {{ request('status')=='sudah_survey'?'selected':'' }}>Sudah Survey</option>
                <option value="disetujui"    {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                <option value="ditolak"      {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            </select>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari desa atau surveyor...">
            </div>
            <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-search"></i> Cari</button>
            @if(request()->hasAny(['status','q']))
            <a href="{{ route('survey.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>
    @endif

    <div class="table-toolbar">
        <div class="table-info">Total: <strong>{{ $surveys->count() }}</strong> lokasi survey</div>
    </div>

    <div class="table-container">
        @if($surveys->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Lokasi (Desa)</th>
                    <th>Ketua Surveyor &amp; Tim</th>
                    @if($isAdmin)<th>Kegiatan</th>@endif
                    <th style="width:110px;">Status</th>
                    @if(auth()->user()->hasAccess('edit.survey') || auth()->user()->hasAccess('hapus.survey') || auth()->user()->hasAccess('isi.survey'))
                    <th style="width:150px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="lokasi-text">
                            <strong>{{ $item->desa?->nama ?? '-' }}</strong>
                            {{ $item->desa?->kecamatan?->nama }}, {{ $item->desa?->kecamatan?->kabupaten?->nama }}<br>
                            {{ $item->desa?->kecamatan?->kabupaten?->provinsi?->nama }}
                        </div>
                    </td>
                    <td>
                        <div class="surveyor-block">
                            <span class="ketua"><i class="fas fa-star" style="color:#f59e0b;font-size:10px;margin-right:3px;"></i>{{ $item->surveyor?->name ?? '-' }}</span>
                            <span class="ketua-email">{{ $item->surveyor?->email }}</span>
                            @if($item->tim_anggota)
                            <span class="anggota-label"><i class="fas fa-users" style="font-size:9px;"></i> Anggota Tim:</span>
                            <span class="anggota-list">{{ $item->tim_anggota }}</span>
                            @endif
                        </div>
                    </td>
                    @if($isAdmin)<td>{{ $item->kegiatan?->nama ?? '-' }}</td>@endif
                    <td>
                        @if($item->status == 'belum_survey')
                        <span class="status-badge status-belum"><i class="fas fa-clock"></i> Belum</span>
                        @elseif($item->status == 'sudah_survey')
                        <span class="status-badge status-sudah"><i class="fas fa-check"></i> Sudah</span>
                        @elseif($item->status == 'disetujui')
                        <span class="status-badge status-disetujui"><i class="fas fa-check-double"></i> Disetujui</span>
                        @else
                        <span class="status-badge status-ditolak"><i class="fas fa-times"></i> Ditolak</span>
                        @endif
                    </td>
                    @if(auth()->user()->hasAccess('edit.survey') || auth()->user()->hasAccess('hapus.survey') || auth()->user()->hasAccess('isi.survey'))
                    <td>
                        @php $periodOpen = $item->isSurveyPeriodOpen(); @endphp
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('isi.survey') && $item->status == 'belum_survey')
                                @if($periodOpen)
                                <a href="{{ route('survey.isi', $item->id) }}" class="btn btn-info btn-sm" title="Isi Survey"><i class="fas fa-clipboard-list"></i></a>
                                @endif
                            @endif
                            @if(auth()->user()->hasAccess('edit.survey') && $item->status == 'belum_survey')
                                @if($periodOpen)
                                <button class="btn btn-warning btn-sm" title="Edit"
                                    onclick="openEditModal(
                                        {{ $item->id }},
                                        {{ $item->surveyor_id }},
                                        {{ $item->kegiatan_id ?? 'null' }},
                                        '{{ addslashes($item->tim_anggota ?? '') }}',
                                        '{{ $item->desa?->kecamatan?->kabupaten?->provinsi?->id }}',
                                        '{{ $item->desa?->kecamatan?->kabupaten?->id }}',
                                        '{{ $item->desa?->kecamatan?->id }}',
                                        {{ $item->desa_id }}
                                    )"><i class="fas fa-edit"></i></button>
                                @endif
                            @endif
                            @if(auth()->user()->hasAccess('hapus.survey'))
                                @if($periodOpen)
                                <button class="btn btn-danger btn-sm" title="Hapus" onclick="openDeleteModal({{ $item->id }},'{{ addslashes($item->desa?->nama) }}')"><i class="fas fa-trash"></i></button>
                                @endif
                            @endif
                            @if(!$periodOpen && $item->kegiatan_id)
                            <span title="Di luar periode survey{{ $item->getSurveyPeriodInfo() ? ': '.$item->getSurveyPeriodInfo() : '' }}" style="font-size:12px;color:var(--text-secondary);display:inline-flex;align-items:center;gap:4px;"><i class="fas fa-lock"></i></span>
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
            <h3>Belum ada lokasi survey</h3>
            @if($isAdmin && auth()->user()->hasAccess('tambah.survey'))
            <p>Klik "Tambah Lokasi" untuk menambahkan data.</p>
            @else
            <p>Tidak ada lokasi survey yang ditugaskan kepada Anda.</p>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah --}}
@if(auth()->user()->hasAccess('tambah.survey'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-plus-circle" style="color:var(--maroon-main);margin-right:8px;"></i>Tambah Lokasi Survey</h3><button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button></div>
        <form action="{{ route('survey.store') }}" method="POST">@csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Provinsi</label>
                    <select id="tambah-provinsi" onchange="loadKabupaten(this.value,'tambah-kabupaten','tambah-kecamatan','tambah-desa')">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsi as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kabupaten/Kota</label>
                        <select id="tambah-kabupaten" onchange="loadKecamatan(this.value,'tambah-kecamatan','tambah-desa')">
                            <option value="">-- Pilih Kabupaten --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select id="tambah-kecamatan" onchange="loadDesa(this.value,'tambah-desa')">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Desa/Kelurahan <span style="color:#ef4444;">*</span></label>
                    <select id="tambah-desa" name="desa_id" required>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ketua Surveyor <span style="color:#ef4444;">*</span></label>
                    <select name="surveyor_id" required>
                        <option value="">-- Pilih Ketua Surveyor --</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                        @endforeach
                    </select>
                    <div class="form-hint"><i class="fas fa-star" style="color:#f59e0b;font-size:10px;"></i> Ketua surveyor bisa mengisi form hasil survey</div>
                </div>
                <div class="form-group">
                    <label>Anggota Tim</label>
                    <textarea name="tim_anggota" rows="4" placeholder="Masukkan nama anggota tim, satu per baris:&#10;Nama Anggota 1&#10;Nama Anggota 2&#10;Nama Anggota 3"></textarea>
                    <div class="form-hint">Tulis satu nama per baris. Anggota tim tidak perlu memiliki akun.</div>
                </div>
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select name="kegiatan_id">
                        <option value="">-- Pilih Kegiatan (Opsional) --</option>
                        @foreach($kegiatan as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Modal Edit --}}
@if(auth()->user()->hasAccess('edit.survey'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-edit" style="color:#f59e0b;margin-right:8px;"></i>Edit Lokasi Survey</h3><button class="modal-close" onclick="closeModal('modal-edit')">&times;</button></div>
        <form id="form-edit" method="POST">@csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Provinsi</label>
                    <select id="edit-provinsi" onchange="loadKabupaten(this.value,'edit-kabupaten','edit-kecamatan','edit-desa')">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsi as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kabupaten/Kota</label>
                        <select id="edit-kabupaten" onchange="loadKecamatan(this.value,'edit-kecamatan','edit-desa')">
                            <option value="">-- Pilih Kabupaten --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select id="edit-kecamatan" onchange="loadDesa(this.value,'edit-desa')">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Desa/Kelurahan <span style="color:#ef4444;">*</span></label>
                    <select id="edit-desa" name="desa_id" required>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ketua Surveyor <span style="color:#ef4444;">*</span></label>
                    <select id="edit-surveyor" name="surveyor_id" required>
                        <option value="">-- Pilih Ketua Surveyor --</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Anggota Tim</label>
                    <textarea id="edit-tim" name="tim_anggota" rows="4" placeholder="Masukkan nama anggota tim, satu per baris"></textarea>
                    <div class="form-hint">Tulis satu nama per baris.</div>
                </div>
                <div class="form-group">
                    <label>Kegiatan</label>
                    <select id="edit-kegiatan" name="kegiatan_id">
                        <option value="">-- Pilih Kegiatan (Opsional) --</option>
                        @foreach($kegiatan as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Modal Hapus --}}
@if(auth()->user()->hasAccess('hapus.survey'))
<div class="modal-overlay" id="modal-hapus">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Konfirmasi Hapus</h3><button class="modal-close" onclick="closeModal('modal-hapus')">&times;</button></div>
        <form id="form-hapus" method="POST">@csrf @method('DELETE')
            <div class="modal-body"><div class="delete-info"><i class="fas fa-trash-alt"></i><p>Apakah Anda yakin ingin menghapus lokasi survey ini?</p><span class="delete-item-name" id="delete-nama"></span></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-hapus')">Batal</button><button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button></div>
        </form>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
function openModal(id){ document.getElementById(id)?.classList.add('active'); }
function closeModal(id){ document.getElementById(id)?.classList.remove('active'); }
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
});

function loadKabupaten(provId, kabId, kecId, desaId) {
    const kab  = document.getElementById(kabId);
    const kec  = document.getElementById(kecId);
    const desa = document.getElementById(desaId);
    kab.innerHTML  = '<option value="">-- Pilih Kabupaten --</option>';
    kec.innerHTML  = '<option value="">-- Pilih Kecamatan --</option>';
    desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
    if (!provId) return Promise.resolve();
    return fetch('{{ url("api/kabupaten") }}?provinsi_id=' + provId)
        .then(r => r.json())
        .then(data => { data.forEach(d => { kab.innerHTML += '<option value="' + d.id + '">' + d.nama + '</option>'; }); });
}

function loadKecamatan(kabId, kecId, desaId) {
    const kec  = document.getElementById(kecId);
    const desa = document.getElementById(desaId);
    kec.innerHTML  = '<option value="">-- Pilih Kecamatan --</option>';
    desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
    if (!kabId) return Promise.resolve();
    return fetch('{{ url("api/kecamatan") }}?kabupaten_id=' + kabId)
        .then(r => r.json())
        .then(data => { data.forEach(d => { kec.innerHTML += '<option value="' + d.id + '">' + d.nama + '</option>'; }); });
}

function loadDesa(kecId, desaId) {
    const desa = document.getElementById(desaId);
    desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
    if (!kecId) return Promise.resolve();
    return fetch('{{ url("api/desa") }}?kecamatan_id=' + kecId)
        .then(r => r.json())
        .then(data => { data.forEach(d => { desa.innerHTML += '<option value="' + d.id + '">' + d.nama + '</option>'; }); });
}

function openEditModal(id, surveyorId, kegiatanId, timAnggota, provId, kabId, kecId, desaId) {
    document.getElementById('form-edit').action = '{{ url("survey") }}/' + id;
    document.getElementById('edit-surveyor').value = surveyorId;
    document.getElementById('edit-kegiatan').value = kegiatanId || '';
    document.getElementById('edit-tim').value = timAnggota;

    if (provId) {
        document.getElementById('edit-provinsi').value = provId;
        loadKabupaten(provId, 'edit-kabupaten', 'edit-kecamatan', 'edit-desa').then(() => {
            if (kabId) {
                document.getElementById('edit-kabupaten').value = kabId;
                return loadKecamatan(kabId, 'edit-kecamatan', 'edit-desa');
            }
        }).then(() => {
            if (kecId) {
                document.getElementById('edit-kecamatan').value = kecId;
                return loadDesa(kecId, 'edit-desa');
            }
        }).then(() => {
            if (desaId) document.getElementById('edit-desa').value = desaId;
        });
    } else {
        document.getElementById('edit-provinsi').value = '';
    }
    openModal('modal-edit');
}

function openDeleteModal(id, nama) {
    document.getElementById('delete-nama').textContent = nama;
    document.getElementById('form-hapus').action = '{{ url("survey") }}/' + id;
    openModal('modal-hapus');
}
</script>
@endsection
