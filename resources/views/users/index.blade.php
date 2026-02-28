@extends('layouts.users')

@section('title', 'Manajemen User')

@section('css')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header-left h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .page-header-left p {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-sm { padding: 6px 12px; font-size: 12px; }

    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main));
        color: white;
    }
    .btn-primary:hover { box-shadow: 0 4px 15px rgba(165,42,42,0.4); transform: translateY(-1px); color: white; }

    .btn-warning { background: #f59e0b; color: white; }
    .btn-warning:hover { background: #d97706; color: white; }
    .btn-danger  { background: #ef4444; color: white; }
    .btn-danger:hover  { background: #dc2626; color: white; }
    .btn-secondary { background: var(--gray-border); color: var(--text-primary); }
    .btn-secondary:hover { background: #d1d5db; }

    /* TOOLBAR */
    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 320px;
    }

    .search-box input {
        width: 100%;
        padding: 9px 14px 9px 36px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165,42,42,0.1);
    }

    .search-box i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 13px;
    }

    .action-btns { display: flex; gap: 6px; }

    /* AVATAR */
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--maroon-main), var(--maroon-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-info { display: flex; align-items: center; gap: 10px; }
    .user-name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .user-email { font-size: 12px; color: var(--text-secondary); }

    /* PILLS */
    .role-pill {
        display: inline-block;
        background: rgba(99,102,241,0.1);
        color: #3730a3;
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 600;
        margin: 1px 2px;
    }

    .prodi-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(16,185,129,0.1);
        color: #065f46;
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 600;
        margin: 1px 2px;
    }

    .jenjang-mini {
        font-size: 10px;
        opacity: 0.7;
    }

    /* JENJANG BADGE */
    .jenjang-badge {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .jenjang-S1 { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .jenjang-S2 { background: rgba(139,92,246,0.1); color: #6d28d9; }
    .jenjang-S3 { background: rgba(16,185,129,0.1); color: #065f46; }
    .jenjang-D3 { background: rgba(245,158,11,0.1); color: #92400e; }
    .jenjang-D4 { background: rgba(236,72,153,0.1); color: #9d174d; }

    /* ACCESS NOTICE */
    .access-notice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: rgba(245,158,11,0.08);
        border: 1px solid rgba(245,158,11,0.3);
        border-radius: 8px;
        font-size: 13px;
        color: #92400e;
        margin-bottom: 16px;
    }
    .access-notice i { color: #f59e0b; font-size: 16px; flex-shrink: 0; }

    /* EMPTY */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.active { display: flex; }

    .modal {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 560px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        animation: modalIn 0.3s ease;
        overflow: hidden;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-border);
        flex-shrink: 0;
    }

    .modal-header h3 { font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0; }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 4px;
        transition: color 0.2s;
    }
    .modal-close:hover { color: var(--text-primary); }

    .modal-body { padding: 24px; overflow-y: auto; flex: 1; }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid var(--gray-border);
        background: var(--gray-light);
        flex-shrink: 0;
    }

    .form-group { margin-bottom: 16px; }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .form-group input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165,42,42,0.1);
    }

    .input-with-toggle {
        position: relative;
    }
    .input-with-toggle input { padding-right: 44px; }
    .toggle-pw-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 4px;
        font-size: 14px;
        transition: color 0.2s;
    }
    .toggle-pw-btn:hover { color: var(--maroon-main); }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .form-hint { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }

    /* PRODI SELECTION AREA */
    .prodi-select-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
        display: block;
    }

    .prodi-scroll-area {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        padding: 10px 12px;
    }

    .prodi-scroll-area::-webkit-scrollbar { width: 5px; }
    .prodi-scroll-area::-webkit-scrollbar-track { background: var(--gray-light); border-radius: 10px; }
    .prodi-scroll-area::-webkit-scrollbar-thumb { background: var(--maroon-lighter); border-radius: 10px; }

    .prodi-group-header {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 6px 0 4px;
        border-bottom: 1px solid var(--gray-border);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .prodi-checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 4px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s;
        font-size: 13px;
    }
    .prodi-checkbox-item:hover { background: rgba(165,42,42,0.04); }
    .prodi-checkbox-item input[type=checkbox] {
        accent-color: var(--maroon-main);
        width: 14px;
        height: 14px;
        flex-shrink: 0;
        cursor: pointer;
    }
    .prodi-checkbox-item span { flex: 1; }
    .prodi-spacer { margin-bottom: 8px; }

    /* DELETE INFO */
    .delete-info { text-align: center; padding: 10px 0; }
    .delete-info i { font-size: 48px; color: #ef4444; margin-bottom: 15px; display: block; }
    .delete-info p { font-size: 14px; color: var(--text-secondary); margin: 0 0 8px 0; }
    .delete-item-name {
        background: rgba(239,68,68,0.1);
        color: #dc2626;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas fa-users" style="color:var(--maroon-main); margin-right:8px;"></i>
                Manajemen User
            </h2>
            <p>Kelola data pengguna beserta tanggung jawab program studi</p>
        </div>

        @if(auth()->user()->hasAccess('tambah.user'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')">
            <i class="fas fa-user-plus"></i>
            <span>Tambah User</span>
        </button>
        @endif
    </div>

    @if(!auth()->user()->hasAccess('tambah.user') && !auth()->user()->hasAccess('edit.user') && !auth()->user()->hasAccess('hapus.user'))
    <div class="access-notice">
        <i class="fas fa-info-circle"></i>
        Anda hanya memiliki akses untuk melihat data. Hubungi administrator untuk akses lebih lanjut.
    </div>
    @endif

    {{-- TOOLBAR --}}
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau email..." oninput="filterTable()">
        </div>
        <div style="font-size:13px; color:var(--text-secondary);">
            Total: <strong>{{ $users->count() }}</strong> user
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        @if($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Pengguna</th>
                    <th>Roles</th>
                    <th>Program Studi</th>
                    @if(auth()->user()->hasAccess('edit.user') || auth()->user()->hasAccess('hapus.user'))
                    <th style="width:110px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="userTableBody">
                @foreach($users as $index => $user)
                <tr data-search="{{ strtolower($user->name . ' ' . $user->email) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="role-pill">{{ $role->nama }}</span>
                        @empty
                            <span style="font-size:12px; color:var(--text-secondary);">—</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($user->programStudi as $prodi)
                            <span class="prodi-pill">
                                {{ $prodi->nama }}
                                <span class="jenjang-mini">({{ $prodi->jenjang }})</span>
                            </span>
                        @empty
                            <span style="font-size:12px; color:var(--text-secondary);">—</span>
                        @endforelse
                    </td>
                    @if(auth()->user()->hasAccess('edit.user') || auth()->user()->hasAccess('hapus.user'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.user'))
                            <button class="btn btn-warning btn-sm" title="Edit"
                                onclick="openEditModal(
                                    {{ $user->id }},
                                    {{ json_encode($user->name) }},
                                    {{ json_encode($user->email) }},
                                    {{ json_encode($user->programStudi->pluck('id')->toArray()) }}
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.user') && $user->id !== auth()->id())
                            <button class="btn btn-danger btn-sm" title="Hapus"
                                onclick="openDeleteModal({{ $user->id }}, {{ json_encode($user->name) }})">
                                <i class="fas fa-trash"></i>
                            </button>
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
            <i class="fas fa-users"></i>
            <h3>Belum ada data user</h3>
            @if(auth()->user()->hasAccess('tambah.user'))
                <p>Klik tombol "Tambah User" untuk menambahkan pengguna baru.</p>
            @else
                <p>Belum ada data yang tersedia.</p>
            @endif
        </div>
        @endif
    </div>

</div>

{{-- ===== MODAL TAMBAH ===== --}}
@if(auth()->user()->hasAccess('tambah.user'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus" style="color:var(--maroon-main); margin-right:8px;"></i>Tambah User</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:#ef4444">*</span></label>
                        <input type="text" name="name" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span style="color:#ef4444">*</span></label>
                        <input type="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password <span style="color:#ef4444">*</span></label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="tambah-password" placeholder="Min. 8 karakter" required minlength="8">
                        <button type="button" class="toggle-pw-btn" onclick="togglePassword('tambah-password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="prodi-select-label">Program Studi yang Bertanggung Jawab</label>
                    <div class="prodi-scroll-area">
                        @forelse($fakultasList as $fak)
                            @if($fak->programStudi->isNotEmpty())
                            <div class="prodi-group-header">
                                <i class="fas fa-university"></i> {{ $fak->nama }}
                            </div>
                            @foreach($fak->programStudi as $prodi)
                            <label class="prodi-checkbox-item">
                                <input type="checkbox" name="program_studi_ids[]" value="{{ $prodi->id }}">
                                <span>{{ $prodi->nama }}</span>
                                <span class="jenjang-badge jenjang-{{ $prodi->jenjang }}">{{ $prodi->jenjang }}</span>
                            </label>
                            @endforeach
                            <div class="prodi-spacer"></div>
                            @endif
                        @empty
                            <div style="text-align:center; padding:20px; color:var(--text-secondary); font-size:13px;">
                                Belum ada data program studi.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ===== MODAL EDIT ===== --}}
@if(auth()->user()->hasAccess('edit.user'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit" style="color:#f59e0b; margin-right:8px;"></i>Edit User</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span style="color:#ef4444">*</span></label>
                        <input type="text" name="name" id="edit-name" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span style="color:#ef4444">*</span></label>
                        <input type="email" name="email" id="edit-email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="edit-password" placeholder="Kosongkan jika tidak diubah" minlength="8">
                        <button type="button" class="toggle-pw-btn" onclick="togglePassword('edit-password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-hint">Biarkan kosong jika tidak ingin mengubah password.</div>
                </div>
                <div class="form-group">
                    <label class="prodi-select-label">Program Studi yang Bertanggung Jawab</label>
                    <div class="prodi-scroll-area">
                        @forelse($fakultasList as $fak)
                            @if($fak->programStudi->isNotEmpty())
                            <div class="prodi-group-header">
                                <i class="fas fa-university"></i> {{ $fak->nama }}
                            </div>
                            @foreach($fak->programStudi as $prodi)
                            <label class="prodi-checkbox-item">
                                <input type="checkbox" name="program_studi_ids[]" value="{{ $prodi->id }}" class="edit-prodi-check">
                                <span>{{ $prodi->nama }}</span>
                                <span class="jenjang-badge jenjang-{{ $prodi->jenjang }}">{{ $prodi->jenjang }}</span>
                            </label>
                            @endforeach
                            <div class="prodi-spacer"></div>
                            @endif
                        @empty
                            <div style="text-align:center; padding:20px; color:var(--text-secondary); font-size:13px;">
                                Belum ada data program studi.
                            </div>
                        @endforelse
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

{{-- ===== MODAL HAPUS ===== --}}
@if(auth()->user()->hasAccess('hapus.user'))
<div class="modal-overlay" id="modal-hapus">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color:#ef4444; margin-right:8px;"></i>Konfirmasi Hapus</h3>
            <button class="modal-close" onclick="closeModal('modal-hapus')">&times;</button>
        </div>
        <form id="form-hapus" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="delete-info">
                    <i class="fas fa-user-slash"></i>
                    <p>Apakah Anda yakin ingin menghapus user ini?</p>
                    <span class="delete-item-name" id="delete-name"></span>
                    <p style="margin-top:10px; font-size:12px;">Tindakan ini tidak dapat dibatalkan.</p>
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
    // --- Modal helpers ---
    function openModal(id) {
        document.getElementById(id)?.classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.remove('active');
    }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) overlay.classList.remove('active');
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        }
    });

    // --- Toggle password ---
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // --- Modal Edit ---
    function openEditModal(userId, name, email, assignedProdiIds) {
        document.getElementById('edit-name').value  = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-password').value = '';
        document.getElementById('form-edit').action = '/users/' + userId;

        document.querySelectorAll('.edit-prodi-check').forEach(cb => {
            cb.checked = assignedProdiIds.includes(parseInt(cb.value));
        });

        openModal('modal-edit');
    }

    // --- Modal Hapus ---
    function openDeleteModal(userId, name) {
        document.getElementById('delete-name').textContent = name;
        document.getElementById('form-hapus').action = '/users/' + userId;
        openModal('modal-hapus');
    }

    // --- Search filter ---
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#userTableBody tr[data-search]').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
