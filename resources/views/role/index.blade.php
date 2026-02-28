@extends('layouts.users')

@section('css')
<style>
    /* PAGE HEADER */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
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

    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-light) 0%, var(--maroon-main) 100%);
        color: white;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 15px rgba(165, 42, 42, 0.4);
        transform: translateY(-1px);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        color: white;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        color: white;
    }

    .btn-secondary {
        background: var(--gray-border);
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .btn-info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-info:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        transform: translateY(-1px);
        color: white;
    }

    /* SEARCH & FILTER BAR */
    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 350px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165, 42, 42, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 14px;
    }

    .table-info {
        font-size: 13px;
        color: var(--text-secondary);
    }

    /* TABLE ACTIONS */
    .action-btns {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    /* ROLE NAME STYLE */
    .role-nama {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-primary);
    }

    .role-keterangan {
        color: var(--text-secondary);
        font-size: 13px;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--gray-border);
        margin-bottom: 15px;
    }

    .empty-state h3 {
        font-size: 16px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideUp 0.3s ease;
        overflow: hidden;
    }

    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-border);
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 5px;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: var(--text-primary);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid var(--gray-border);
        background: var(--gray-light);
    }

    /* FORM INSIDE MODAL */
    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165, 42, 42, 0.1);
    }

    /* DELETE MODAL */
    .delete-info {
        text-align: center;
        padding: 10px 0;
    }

    .delete-info i {
        font-size: 48px;
        color: #ef4444;
        margin-bottom: 15px;
    }

    .delete-info p {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0 0 8px 0;
    }

    .delete-info .delete-item-name {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        display: inline-block;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-box {
            max-width: 100%;
            width: 100%;
        }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-user-tag" style="color: var(--maroon-main); margin-right: 8px;"></i>Manajemen Role</h2>
            <p>Kelola role pengguna dan hak akses yang diberikan kepada setiap role</p>
        </div>
        <button class="btn btn-primary" onclick="openModal('modal-tambah')">
            <i class="fas fa-plus"></i>
            <span>Tambah Role</span>
        </button>
    </div>

    <!-- TABLE TOOLBAR -->
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari role..." onkeyup="filterTable()">
        </div>
        <div class="table-info">
            Total: <strong>{{ $roles->count() }}</strong> role
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        @if($roles->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Role</th>
                    <th>Keterangan</th>
                    <th style="width: 220px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="roleTableBody">
                @foreach($roles as $index => $role)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="role-nama">{{ $role->nama }}</span></td>
                    <td><span class="role-keterangan">{{ $role->keterangan ?? '-' }}</span></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('role.access.show', $role) }}" class="btn btn-info btn-sm" title="Kelola Hak Akses">
                                <i class="fas fa-key"></i>
                                <span>Hak Akses</span>
                            </a>
                            <button class="btn btn-warning btn-sm" title="Edit"
                                onclick="openEditModal({{ $role->id }}, '{{ addslashes($role->nama) }}', '{{ addslashes($role->keterangan) }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" title="Hapus"
                                onclick="openDeleteModal({{ $role->id }}, '{{ addslashes($role->nama) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-user-tag"></i>
            <h3>Belum ada data role</h3>
            <p>Klik tombol "Tambah Role" untuk menambahkan role baru</p>
        </div>
        @endif
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="color: var(--maroon-main); margin-right: 8px;"></i>Tambah Role</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button>
        </div>
        <form action="{{ route('role.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="tambah-nama">Nama Role</label>
                    <input type="text" id="tambah-nama" name="nama" placeholder="contoh: Admin, Dosen, Mahasiswa" required>
                </div>
                <div class="form-group">
                    <label for="tambah-keterangan">Keterangan</label>
                    <input type="text" id="tambah-keterangan" name="keterangan" placeholder="Deskripsi singkat (opsional)">
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

<!-- MODAL EDIT -->
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color: #f59e0b; margin-right: 8px;"></i>Edit Role</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-nama">Nama Role</label>
                    <input type="text" id="edit-nama" name="nama" placeholder="contoh: Admin, Dosen, Mahasiswa" required>
                </div>
                <div class="form-group">
                    <label for="edit-keterangan">Keterangan</label>
                    <input type="text" id="edit-keterangan" name="keterangan" placeholder="Deskripsi singkat (opsional)">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit')">Batal</button>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal-overlay" id="modal-hapus">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 8px;"></i>Konfirmasi Hapus</h3>
            <button class="modal-close" onclick="closeModal('modal-hapus')">&times;</button>
        </div>
        <form id="form-hapus" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="delete-info">
                    <i class="fas fa-trash-alt"></i>
                    <p>Apakah Anda yakin ingin menghapus role ini?</p>
                    <span class="delete-item-name" id="delete-nama"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-hapus')">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // Close modal when clicking overlay backdrop
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        }
    });

    function openEditModal(id, nama, keterangan) {
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-keterangan').value = keterangan || '';
        document.getElementById('form-edit').action = '/role/' + id;
        openModal('modal-edit');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('delete-nama').textContent = nama;
        document.getElementById('form-hapus').action = '/role/' + id;
        openModal('modal-hapus');
    }

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#roleTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    }
</script>
@endsection
