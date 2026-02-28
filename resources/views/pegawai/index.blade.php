@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-primary  { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-primary:hover  { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .btn-success  { background:#10b981; color:#fff; } .btn-success:hover  { background:#059669; color:#fff; }
    .btn-warning  { background:#f59e0b; color:#fff; } .btn-warning:hover  { background:#d97706; color:#fff; }
    .btn-danger   { background:#ef4444; color:#fff; } .btn-danger:hover   { background:#dc2626; color:#fff; }
    .btn-secondary{ background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }
    .filter-bar { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; align-items:center; }
    .filter-bar select, .filter-bar input[type=text] { padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .filter-bar select:focus, .filter-bar input:focus { outline:none; border-color:var(--maroon-main); }
    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .table-info { font-size:13px; color:var(--text-secondary); }
    .action-btns { display:flex; gap:6px; flex-wrap:wrap; }
    .pegawai-avatar { width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,var(--maroon-main),var(--maroon-light)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:15px; flex-shrink:0; }
    .pegawai-info { display:flex; align-items:center; gap:10px; }
    .pegawai-name { font-size:13px; font-weight:600; color:var(--text-primary); }
    .pegawai-sub  { font-size:11px; color:var(--text-secondary); }
    .badge-akun-ada   { display:inline-flex; align-items:center; gap:5px; background:rgba(16,185,129,.1); color:#059669; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; }
    .badge-akun-tidak { display:inline-flex; align-items:center; gap:5px; background:rgba(107,114,128,.1); color:#6b7280; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; }
    .status-aktif    { display:inline-block; background:rgba(16,185,129,.1); color:#059669; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; }
    .status-nonaktif { display:inline-block; background:rgba(107,114,128,.1); color:#4b5563; font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px; }
    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:580px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .3s ease; overflow:hidden; max-height:90vh; display:flex; flex-direction:column; }
    @keyframes modalIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid var(--gray-border); flex-shrink:0; }
    .modal-header h3 { font-size:18px; font-weight:700; color:var(--text-primary); margin:0; }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-body { padding:24px; overflow-y:auto; flex:1; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light); flex-shrink:0; }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group input, .form-group select { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; box-sizing:border-box; }
    .form-group input:focus, .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .form-divider { border:none; border-top:1px dashed var(--gray-border); margin:16px 0; }
    .section-label { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-secondary); margin-bottom:12px; display:flex; align-items:center; gap:6px; }
    .input-with-toggle { position:relative; }
    .input-with-toggle input { padding-right:44px; }
    .toggle-pw-btn { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:4px; font-size:14px; }
    .toggle-pw-btn:hover { color:var(--maroon-main); }
    .toggle-switch { display:flex; align-items:center; gap:10px; }
    .toggle-switch input[type=checkbox] { accent-color:var(--maroon-main); width:16px; height:16px; cursor:pointer; }
    .delete-info { text-align:center; padding:10px 0; }
    .delete-info i { font-size:48px; color:#ef4444; margin-bottom:15px; display:block; }
    .delete-info p { font-size:14px; color:var(--text-secondary); margin:0 0 8px; }
    .delete-item-name { background:rgba(239,68,68,.1); color:#dc2626; padding:6px 14px; border-radius:6px; font-size:14px; font-weight:700; display:inline-block; }
    .alert-info { background:rgba(59,130,246,.08); border:1px solid rgba(59,130,246,.25); color:#1e40af; padding:10px 14px; border-radius:8px; font-size:12px; margin-bottom:16px; display:flex; align-items:flex-start; gap:8px; }
    .alert-info i { flex-shrink:0; margin-top:1px; }
    @media(max-width:768px){ .form-row{grid-template-columns:1fr} }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-user-tie" style="color:var(--maroon-main);margin-right:8px;"></i>Data Pegawai</h2>
            <p>Kelola data pegawai beserta akun login mereka</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.pegawai'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')"><i class="fas fa-user-plus"></i><span>Tambah Pegawai</span></button>
        @endif
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#059669;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
        <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#dc2626;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
        <i class="fas fa-times-circle" style="margin-right:6px;"></i>{{ session('error') }}
    </div>
    @endif

    <div class="filter-bar">
        <form method="GET" action="{{ route('pegawai.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NIP, email..." style="min-width:200px;">
            <select name="akun" onchange="this.form.submit()">
                <option value="">Semua Akun</option>
                <option value="ada"    {{ request('akun') == 'ada'    ? 'selected' : '' }}>Sudah Punya Akun</option>
                <option value="tidak"  {{ request('akun') == 'tidak'  ? 'selected' : '' }}>Belum Punya Akun</option>
            </select>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
            </select>
            <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-search"></i> Cari</button>
            @if(request()->hasAny(['q','akun','status']))
            <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-toolbar">
        <div class="table-info">Total: <strong>{{ $pegawai->count() }}</strong> pegawai</div>
    </div>

    <div class="table-container">
        @if($pegawai->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Pegawai</th>
                    <th>Kontak</th>
                    <th style="width:150px;">Akun Login</th>
                    <th style="width:90px;">Status</th>
                    @if(auth()->user()->hasAccess('edit.pegawai') || auth()->user()->hasAccess('hapus.pegawai'))
                    <th style="width:120px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="pegawai-info">
                            <div class="pegawai-avatar">{{ strtoupper(substr($p->nama, 0, 1)) }}</div>
                            <div>
                                <div class="pegawai-name">{{ $p->nama }}</div>
                                @if($p->nip)
                                <div class="pegawai-sub">NIP: {{ $p->nip }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px;line-height:1.8;">
                            @if($p->email)
                            <div><i class="fas fa-envelope" style="color:var(--text-secondary);font-size:10px;width:14px;"></i> {{ $p->email }}</div>
                            @endif
                            @if($p->no_hp)
                            <div><i class="fas fa-phone" style="color:var(--text-secondary);font-size:10px;width:14px;"></i> {{ $p->no_hp }}</div>
                            @endif
                            @if(!$p->email && !$p->no_hp)
                            <span style="color:var(--text-secondary);">—</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($p->hasAccount())
                        <span class="badge-akun-ada"><i class="fas fa-check-circle"></i> Ada Akun</span>
                        <div class="pegawai-sub" style="margin-top:4px;">{{ $p->user->email }}</div>
                        @else
                        <span class="badge-akun-tidak"><i class="fas fa-times-circle"></i> Belum Ada</span>
                        @if(auth()->user()->hasAccess('edit.pegawai'))
                        <div style="margin-top:5px;">
                            <button class="btn btn-success btn-sm" style="font-size:10px;padding:4px 8px;"
                                onclick="openBuatAkunModal({{ $p->id }}, {{ json_encode($p->nama) }}, {{ json_encode($p->email ?? '') }})">
                                <i class="fas fa-key"></i> Buat Akun
                            </button>
                        </div>
                        @endif
                        @endif
                    </td>
                    <td>
                        @if($p->is_active)
                        <span class="status-aktif"><i class="fas fa-circle" style="font-size:7px;"></i> Aktif</span>
                        @else
                        <span class="status-nonaktif"><i class="fas fa-circle" style="font-size:7px;"></i> Non-aktif</span>
                        @endif
                    </td>
                    @if(auth()->user()->hasAccess('edit.pegawai') || auth()->user()->hasAccess('hapus.pegawai'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.pegawai'))
                            <button class="btn btn-warning btn-sm" title="Edit"
                                onclick="openEditModal(
                                    {{ $p->id }},
                                    {{ json_encode($p->nip ?? '') }},
                                    {{ json_encode($p->nama) }},
                                    {{ json_encode($p->email ?? '') }},
                                    {{ json_encode($p->no_hp ?? '') }},
                                    {{ $p->is_active ? 'true' : 'false' }},
                                    {{ $p->hasAccount() ? 'true' : 'false' }}
                                )"><i class="fas fa-edit"></i></button>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.pegawai'))
                            <button class="btn btn-danger btn-sm" title="Hapus"
                                onclick="openDeleteModal({{ $p->id }}, {{ json_encode($p->nama) }})"><i class="fas fa-trash"></i></button>
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
            <i class="fas fa-user-tie"></i>
            <h3>Belum ada data pegawai</h3>
            @if(auth()->user()->hasAccess('tambah.pegawai'))
            <p>Klik "Tambah Pegawai" untuk menambahkan data pegawai.</p>
            @else
            <p>Belum ada data yang tersedia.</p>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- ===== MODAL TAMBAH ===== --}}
@if(auth()->user()->hasAccess('tambah.pegawai'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus" style="color:var(--maroon-main);margin-right:8px;"></i>Tambah Pegawai</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button>
        </div>
        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="section-label"><i class="fas fa-id-card"></i> Data Pegawai</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nama" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label>NIP / NIDN</label>
                        <input type="text" name="nip" placeholder="Nomor induk pegawai">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="tambah-email" placeholder="email@umkendari.ac.id">
                    </div>
                    <div class="form-group">
                        <label>No. HP / WA</label>
                        <input type="text" name="no_hp" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <hr class="form-divider">
                <div class="section-label"><i class="fas fa-key"></i> Akun Login <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opsional)</span></div>
                <div class="alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Isi password di bawah jika pegawai ini memerlukan akun login. Email di atas akan digunakan sebagai username. Kosongkan jika tidak perlu akun.</span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="tambah-password" placeholder="Min. 8 karakter — kosongkan jika tidak perlu akun" minlength="8">
                        <button type="button" class="toggle-pw-btn" onclick="togglePw('tambah-password', this)"><i class="fas fa-eye"></i></button>
                    </div>
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

{{-- ===== MODAL EDIT ===== --}}
@if(auth()->user()->hasAccess('edit.pegawai'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit" style="color:#f59e0b;margin-right:8px;"></i>Edit Pegawai</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="section-label"><i class="fas fa-id-card"></i> Data Pegawai</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="edit-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>NIP / NIDN</label>
                        <input type="text" id="edit-nip" name="nip">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="edit-email" name="email">
                    </div>
                    <div class="form-group">
                        <label>No. HP / WA</label>
                        <input type="text" id="edit-no-hp" name="no_hp">
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <div class="toggle-switch">
                        <input type="checkbox" id="edit-aktif" name="is_active" value="1">
                        <label for="edit-aktif" style="font-weight:400;font-size:13px;margin-bottom:0;cursor:pointer;">Pegawai aktif</label>
                    </div>
                </div>

                <div id="edit-akun-section">
                    <hr class="form-divider">
                    <div class="section-label"><i class="fas fa-key"></i> Reset Password Akun</div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <div class="input-with-toggle">
                            <input type="password" name="password" id="edit-password" placeholder="Kosongkan jika tidak ingin mengubah password" minlength="8">
                            <button type="button" class="toggle-pw-btn" onclick="togglePw('edit-password', this)"><i class="fas fa-eye"></i></button>
                        </div>
                        <div class="form-hint">Isi jika ingin mengubah password akun login pegawai ini.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ===== MODAL BUAT AKUN ===== --}}
@if(auth()->user()->hasAccess('edit.pegawai'))
<div class="modal-overlay" id="modal-buat-akun">
    <div class="modal" style="max-width:460px;">
        <div class="modal-header">
            <h3><i class="fas fa-key" style="color:#10b981;margin-right:8px;"></i>Buat Akun Login</h3>
            <button class="modal-close" onclick="closeModal('modal-buat-akun')">&times;</button>
        </div>
        <form id="form-buat-akun" method="POST">
            @csrf
            <div class="modal-body">
                <p style="font-size:13px;color:var(--text-secondary);margin:0 0 16px;">
                    Membuat akun login untuk: <strong id="buat-akun-nama" style="color:var(--text-primary);"></strong>
                </p>
                <div class="form-group">
                    <label>Email (Username) <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" id="buat-akun-email" placeholder="email@umkendari.ac.id" required>
                    <div class="form-hint">Akan digunakan sebagai username login.</div>
                </div>
                <div class="form-group">
                    <label>Password <span style="color:#ef4444;">*</span></label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="buat-akun-password" placeholder="Min. 8 karakter" minlength="8" required>
                        <button type="button" class="toggle-pw-btn" onclick="togglePw('buat-akun-password', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-buat-akun')">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-key"></i> Buat Akun</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ===== MODAL HAPUS ===== --}}
@if(auth()->user()->hasAccess('hapus.pegawai'))
<div class="modal-overlay" id="modal-hapus">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Konfirmasi Hapus</h3>
            <button class="modal-close" onclick="closeModal('modal-hapus')">&times;</button>
        </div>
        <form id="form-hapus" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body">
                <div class="delete-info">
                    <i class="fas fa-user-slash"></i>
                    <p>Apakah Anda yakin ingin menghapus pegawai ini?</p>
                    <span class="delete-item-name" id="delete-nama"></span>
                    <p style="margin-top:10px;font-size:12px;">Data pegawai akan dihapus. Akun login <strong>tidak ikut dihapus</strong>.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-hapus')">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
function openModal(id)  { document.getElementById(id)?.classList.add('active'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
});

function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function openEditModal(id, nip, nama, email, noHp, isActive, hasAccount) {
    document.getElementById('edit-nip').value      = nip || '';
    document.getElementById('edit-nama').value     = nama;
    document.getElementById('edit-email').value    = email || '';
    document.getElementById('edit-no-hp').value    = noHp || '';
    document.getElementById('edit-aktif').checked  = isActive;
    document.getElementById('edit-password').value = '';
    document.getElementById('form-edit').action    = '/pegawai/' + id;

    // Tampilkan section reset password hanya jika pegawai sudah punya akun
    document.getElementById('edit-akun-section').style.display = hasAccount ? '' : 'none';

    openModal('modal-edit');
}

function openBuatAkunModal(id, nama, email) {
    document.getElementById('buat-akun-nama').textContent   = nama;
    document.getElementById('buat-akun-email').value        = email || '';
    document.getElementById('buat-akun-password').value     = '';
    document.getElementById('form-buat-akun').action        = '/pegawai/' + id + '/buat-akun';
    openModal('modal-buat-akun');
}

function openDeleteModal(id, nama) {
    document.getElementById('delete-nama').textContent = nama;
    document.getElementById('form-hapus').action = '/pegawai/' + id;
    openModal('modal-hapus');
}
</script>
@endsection
