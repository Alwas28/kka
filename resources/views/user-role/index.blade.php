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

    /* SEARCH */
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

    /* AVATAR */
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--maroon-main), var(--maroon-light));
        color: white;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-cell-info .user-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .user-cell-info .user-email {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 1px;
    }

    /* ROLE BADGES */
    .role-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(165, 42, 42, 0.1);
        color: var(--maroon-dark);
        border: 1px solid rgba(165, 42, 42, 0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-pill i {
        font-size: 10px;
        opacity: 0.7;
    }

    .no-role {
        font-size: 12px;
        color: var(--text-secondary);
        font-style: italic;
    }

    /* BUTTON */
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

    .btn-sm {
        padding: 6px 14px;
        font-size: 12px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main));
        color: white;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 15px rgba(165, 42, 42, 0.4);
        transform: translateY(-1px);
        color: white;
    }

    .btn-secondary {
        background: var(--gray-border);
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
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
        max-width: 520px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        animation: modalIn 0.3s ease;
        overflow: hidden;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-border);
    }

    .modal-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-header h3 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 2px 0;
    }

    .modal-header p {
        font-size: 12px;
        color: var(--text-secondary);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 4px;
        line-height: 1;
        transition: color 0.2s;
        flex-shrink: 0;
    }

    .modal-close:hover { color: var(--text-primary); }

    .modal-body {
        padding: 20px 24px;
        max-height: 60vh;
        overflow-y: auto;
    }

    .modal-body::-webkit-scrollbar { width: 5px; }
    .modal-body::-webkit-scrollbar-track { background: var(--gray-light); }
    .modal-body::-webkit-scrollbar-thumb { background: var(--maroon-lighter); border-radius: 10px; }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid var(--gray-border);
        background: var(--gray-light);
    }

    /* ROLE CHECKBOXES INSIDE MODAL */
    .modal-roles-hint {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 14px;
        padding: 10px 14px;
        background: rgba(165, 42, 42, 0.05);
        border-left: 3px solid var(--maroon-main);
        border-radius: 4px;
    }

    .role-check-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s;
        border: 1px solid transparent;
        margin-bottom: 8px;
    }

    .role-check-item:hover {
        background: var(--gray-light);
        border-color: var(--gray-border);
    }

    .role-check-item:has(input:checked) {
        background: rgba(165, 42, 42, 0.06);
        border-color: rgba(165, 42, 42, 0.2);
    }

    .role-check-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--maroon-main);
        cursor: pointer;
        flex-shrink: 0;
    }

    .role-check-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        flex-shrink: 0;
    }

    .role-check-info {
        flex: 1;
        min-width: 0;
    }

    .role-check-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .role-check-desc {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 1px;
    }

    .empty-roles {
        text-align: center;
        padding: 30px 20px;
        color: var(--text-secondary);
        font-size: 13px;
    }

    .empty-roles i {
        font-size: 36px;
        color: var(--gray-border);
        margin-bottom: 10px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .table-toolbar { flex-direction: column; align-items: flex-start; }
        .search-box { max-width: 100%; width: 100%; }
        .user-cell-info .user-email { display: none; }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-id-badge" style="color: var(--maroon-main); margin-right: 8px;"></i>User Roles</h2>
            <p>Kelola role yang dimiliki setiap pengguna. Satu user dapat memiliki lebih dari satu role.</p>
        </div>
    </div>

    <!-- TABLE TOOLBAR -->
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau email..." onkeyup="filterTable()">
        </div>
        <div class="table-info">
            Total: <strong>{{ $users->count() }}</strong> user
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        @if($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>User</th>
                    <th>Role Saat Ini</th>
                    <th style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="userRoleTableBody">
                @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <div class="user-cell-info">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="role-list">
                            @forelse($user->roles as $role)
                                <span class="role-pill">
                                    <i class="fas fa-user-tag"></i>
                                    {{ $role->nama }}
                                </span>
                            @empty
                                <span class="no-role">Belum ada role</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm"
                            onclick="openModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', [{{ $user->roles->pluck('id')->join(',') }}])">
                            <i class="fas fa-edit"></i>
                            <span>Atur Role</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center; padding: 60px 20px; color: var(--text-secondary);">
            <i class="fas fa-users" style="font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block;"></i>
            <h3 style="font-size:16px; color:var(--text-primary); margin-bottom:8px;">Belum ada data user</h3>
            <p style="font-size:13px; margin:0;">Tambahkan user terlebih dahulu.</p>
        </div>
        @endif
    </div>
</div>

<!-- MODAL ATUR ROLE -->
<div class="modal-overlay" id="modal-atur-role">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-info">
                <div class="user-avatar" id="modal-avatar" style="width:44px;height:44px;font-size:16px;"></div>
                <div>
                    <h3 id="modal-user-name">-</h3>
                    <p id="modal-user-email">-</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>

        <form id="form-atur-role" method="POST">
            @csrf
            @method('POST')

            <div class="modal-body">
                <div class="modal-roles-hint">
                    <i class="fas fa-info-circle" style="margin-right:5px;"></i>
                    Centang satu atau lebih role untuk user ini. Kosongkan semua untuk menghapus semua role.
                </div>

                @if($roles->count() > 0)
                    @foreach($roles as $role)
                    <label class="role-check-item">
                        <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="modal-role-cb">
                        <div class="role-check-icon"><i class="fas fa-user-tag"></i></div>
                        <div class="role-check-info">
                            <div class="role-check-name">{{ $role->nama }}</div>
                            @if($role->keterangan)
                            <div class="role-check-desc">{{ $role->keterangan }}</div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                @else
                    <div class="empty-roles">
                        <i class="fas fa-user-tag"></i>
                        <p>Belum ada role tersedia. Buat role terlebih dahulu di menu <strong>Role</strong>.</p>
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary" {{ $roles->count() === 0 ? 'disabled' : '' }}>
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    // Data semua role (untuk reset state)
    const allRoleIds = @json($roles->pluck('id'));

    function openModal(userId, userName, userEmail, assignedRoleIds) {
        // Set header info
        document.getElementById('modal-user-name').textContent  = userName;
        document.getElementById('modal-user-email').textContent = userEmail;
        document.getElementById('modal-avatar').textContent     = userName.substring(0, 2).toUpperCase();

        // Set form action
        document.getElementById('form-atur-role').action = '/user-role/' + userId;

        // Reset & set checkboxes
        document.querySelectorAll('.modal-role-cb').forEach(cb => {
            cb.checked = assignedRoleIds.includes(parseInt(cb.value));
        });

        document.getElementById('modal-atur-role').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modal-atur-role').classList.remove('active');
    }

    // Close on backdrop click
    document.getElementById('modal-atur-role').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });

    // Search filter
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#userRoleTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
