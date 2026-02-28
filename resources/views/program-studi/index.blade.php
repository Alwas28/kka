@extends('layouts.users')

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

    .btn-primary:hover {
        box-shadow: 0 4px 15px rgba(165, 42, 42, 0.4);
        transform: translateY(-1px);
        color: white;
    }

    .btn-warning { background: #f59e0b; color: white; }
    .btn-warning:hover { background: #d97706; color: white; }
    .btn-danger  { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; color: white; }
    .btn-secondary { background: var(--gray-border); color: var(--text-primary); }
    .btn-secondary:hover { background: #d1d5db; }

    /* FILTER BAR */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        background: white;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        flex-wrap: wrap;
    }

    .filter-bar label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-bar label i { color: var(--maroon-main); }

    .filter-select {
        flex: 1;
        min-width: 200px;
        max-width: 350px;
        padding: 9px 14px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165, 42, 42, 0.1);
    }

    .filter-count {
        font-size: 12px;
        color: var(--text-secondary);
        margin-left: auto;
    }

    .filter-count strong { color: var(--maroon-main); }

    /* FAKULTAS TABS */
    .fakultas-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .fakultas-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        border: 1.5px solid var(--gray-border);
        color: var(--text-secondary);
        background: white;
    }

    .fakultas-tab:hover {
        border-color: var(--maroon-main);
        color: var(--maroon-main);
        background: rgba(165,42,42,0.04);
    }

    .fakultas-tab.active {
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main));
        color: white;
        border-color: transparent;
        box-shadow: 0 3px 10px rgba(165, 42, 42, 0.3);
    }

    .fakultas-tab .tab-count {
        background: rgba(255,255,255,0.25);
        border-radius: 10px;
        padding: 1px 7px;
        font-size: 11px;
    }

    .fakultas-tab:not(.active) .tab-count {
        background: var(--gray-border);
        color: var(--text-secondary);
    }

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
        box-shadow: 0 0 0 3px rgba(165, 42, 42, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 13px;
    }

    /* BADGE STYLES */
    .kode-badge {
        display: inline-block;
        font-family: 'Consolas', 'Courier New', monospace;
        font-size: 12px;
        font-weight: 700;
        background: rgba(165, 42, 42, 0.08);
        color: var(--maroon-dark);
        border: 1px solid rgba(165, 42, 42, 0.15);
        padding: 3px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }

    .jenjang-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }

    .jenjang-S1 { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .jenjang-S2 { background: rgba(139,92,246,0.1); color: #6d28d9; }
    .jenjang-S3 { background: rgba(16,185,129,0.1); color: #065f46; }
    .jenjang-D3 { background: rgba(245,158,11,0.1); color: #92400e; }
    .jenjang-D4 { background: rgba(236,72,153,0.1); color: #9d174d; }

    .fakultas-label {
        font-size: 12px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .action-btns { display: flex; gap: 6px; }

    /* EMPTY */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--gray-border);
        margin-bottom: 15px;
        display: block;
    }

    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    /* ACCESS NOTICE */
    .access-notice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 8px;
        font-size: 13px;
        color: #92400e;
        margin-bottom: 16px;
    }

    .access-notice i { color: #f59e0b; font-size: 16px; flex-shrink: 0; }

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

    .modal-overlay.active { display: flex; }

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
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-border);
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

    .modal-body { padding: 24px; }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid var(--gray-border);
        background: var(--gray-light);
    }

    .form-group { margin-bottom: 16px; }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165, 42, 42, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .form-hint { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }

    .delete-info { text-align: center; padding: 10px 0; }
    .delete-info i { font-size: 48px; color: #ef4444; margin-bottom: 15px; display: block; }
    .delete-info p { font-size: 14px; color: var(--text-secondary); margin: 0 0 8px 0; }
    .delete-item-name {
        background: rgba(239, 68, 68, 0.1);
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
        .fakultas-tabs { gap: 6px; }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas fa-graduation-cap" style="color: var(--maroon-main); margin-right: 8px;"></i>
                Program Studi
            </h2>
            <p>Kelola data program studi berdasarkan fakultas</p>
        </div>

        @if(auth()->user()->hasAccess('tambah.program-studi'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')">
            <i class="fas fa-plus"></i>
            <span>Tambah Program Studi</span>
        </button>
        @endif
    </div>

    {{-- Access notice --}}
    @if(!auth()->user()->hasAccess('tambah.program-studi') && !auth()->user()->hasAccess('edit.program-studi') && !auth()->user()->hasAccess('hapus.program-studi'))
    <div class="access-notice">
        <i class="fas fa-info-circle"></i>
        Anda hanya memiliki akses untuk melihat data. Hubungi administrator untuk akses lebih lanjut.
    </div>
    @endif

    <!-- FILTER FAKULTAS (TABS) -->
    <div class="fakultas-tabs">
        {{-- Tab "Semua" --}}
        <a href="{{ route('program-studi.index') }}"
            class="fakultas-tab {{ !$selectedFakultas ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i>
            Semua
            <span class="tab-count">{{ $programStudi->count() }}</span>
        </a>

        {{-- Tab per Fakultas --}}
        @foreach($fakultasList as $fak)
        @php $count = $fak->programStudi()->count(); @endphp
        <a href="{{ route('program-studi.index', ['fakultas_id' => $fak->id]) }}"
            class="fakultas-tab {{ $selectedFakultas == $fak->id ? 'active' : '' }}">
            {{ $fak->kode }}
            <span class="tab-count">{{ $count }}</span>
        </a>
        @endforeach
    </div>

    <!-- TABLE TOOLBAR -->
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari kode atau nama prodi..." onkeyup="filterTable()">
        </div>
        <div style="font-size:13px; color: var(--text-secondary);">
            Menampilkan: <strong>{{ $programStudi->count() }}</strong> program studi
            @if($selectedFakultas && $fakultasList->firstWhere('id', $selectedFakultas))
                &mdash; <strong>{{ $fakultasList->firstWhere('id', $selectedFakultas)->nama }}</strong>
            @endif
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        @if($programStudi->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th style="width:110px;">Kode</th>
                    <th>Nama Program Studi</th>
                    <th style="width:90px;">Jenjang</th>
                    @if(!$selectedFakultas)
                    <th>Fakultas</th>
                    @endif
                    <th>Keterangan</th>
                    @if(auth()->user()->hasAccess('edit.program-studi') || auth()->user()->hasAccess('hapus.program-studi'))
                    <th style="width:110px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="prodiTableBody">
                @foreach($programStudi as $index => $prodi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="kode-badge">{{ $prodi->kode }}</span></td>
                    <td style="font-weight:600;">{{ $prodi->nama }}</td>
                    <td><span class="jenjang-badge jenjang-{{ $prodi->jenjang }}">{{ $prodi->jenjang }}</span></td>
                    @if(!$selectedFakultas)
                    <td>
                        <span class="fakultas-label">
                            <i class="fas fa-building"></i>
                            {{ $prodi->fakultas->nama ?? '-' }}
                        </span>
                    </td>
                    @endif
                    <td style="color:var(--text-secondary); font-size:13px;">{{ $prodi->keterangan ?? '-' }}</td>
                    @if(auth()->user()->hasAccess('edit.program-studi') || auth()->user()->hasAccess('hapus.program-studi'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.program-studi'))
                            <button class="btn btn-warning btn-sm" title="Edit"
                                onclick="openEditModal(
                                    {{ $prodi->id }},
                                    {{ $prodi->fakultas_id }},
                                    '{{ addslashes($prodi->kode) }}',
                                    '{{ addslashes($prodi->nama) }}',
                                    '{{ $prodi->jenjang }}',
                                    '{{ addslashes($prodi->keterangan) }}'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif

                            @if(auth()->user()->hasAccess('hapus.program-studi'))
                            <button class="btn btn-danger btn-sm" title="Hapus"
                                onclick="openDeleteModal({{ $prodi->id }}, '{{ addslashes($prodi->nama) }}')">
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
            <i class="fas fa-graduation-cap"></i>
            <h3>Belum ada program studi</h3>
            @if($selectedFakultas)
                <p>Fakultas ini belum memiliki program studi.</p>
            @else
                @if(auth()->user()->hasAccess('tambah.program-studi'))
                    <p>Klik tombol "Tambah Program Studi" untuk menambahkan data baru.</p>
                @else
                    <p>Belum ada data yang tersedia.</p>
                @endif
            @endif
        </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH --}}
@if(auth()->user()->hasAccess('tambah.program-studi'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="color:var(--maroon-main); margin-right:8px;"></i>Tambah Program Studi</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button>
        </div>
        <form action="{{ route('program-studi.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Fakultas</label>
                    <select name="fakultas_id" id="tambah-fakultas" required>
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}"
                            {{ $selectedFakultas == $fak->id ? 'selected' : '' }}>
                            {{ $fak->kode }} — {{ $fak->nama }}
                        </option>
                        @endforeach
                    </select>
                    @if($fakultasList->isEmpty())
                    <div class="form-hint" style="color:#ef4444;">
                        <i class="fas fa-exclamation-circle"></i>
                        Belum ada fakultas. Tambahkan fakultas terlebih dahulu.
                    </div>
                    @endif
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kode Prodi</label>
                        <input type="text" name="kode" placeholder="contoh: TI, SI, FH" required style="text-transform:uppercase;">
                        <div class="form-hint">Kode unik singkatan prodi</div>
                    </div>
                    <div class="form-group">
                        <label>Jenjang</label>
                        <select name="jenjang" required>
                            <option value="S1">S1 — Sarjana</option>
                            <option value="S2">S2 — Magister</option>
                            <option value="S3">S3 — Doktor</option>
                            <option value="D3">D3 — Diploma 3</option>
                            <option value="D4">D4 — Diploma 4</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nama Program Studi</label>
                    <input type="text" name="nama" placeholder="contoh: Teknik Informatika" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Deskripsi singkat (opsional)">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah')">Batal</button>
                <button type="submit" class="btn btn-primary" {{ $fakultasList->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- MODAL EDIT --}}
@if(auth()->user()->hasAccess('edit.program-studi'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color:#f59e0b; margin-right:8px;"></i>Edit Program Studi</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Fakultas</label>
                    <select name="fakultas_id" id="edit-fakultas" required>
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}">{{ $fak->kode }} — {{ $fak->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kode Prodi</label>
                        <input type="text" id="edit-kode" name="kode" required style="text-transform:uppercase;">
                        <div class="form-hint">Kode unik singkatan prodi</div>
                    </div>
                    <div class="form-group">
                        <label>Jenjang</label>
                        <select id="edit-jenjang" name="jenjang" required>
                            <option value="S1">S1 — Sarjana</option>
                            <option value="S2">S2 — Magister</option>
                            <option value="S3">S3 — Doktor</option>
                            <option value="D3">D3 — Diploma 3</option>
                            <option value="D4">D4 — Diploma 4</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nama Program Studi</label>
                    <input type="text" id="edit-nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" id="edit-keterangan" name="keterangan" placeholder="Deskripsi singkat (opsional)">
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

{{-- MODAL HAPUS --}}
@if(auth()->user()->hasAccess('hapus.program-studi'))
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
                    <i class="fas fa-trash-alt"></i>
                    <p>Apakah Anda yakin ingin menghapus program studi ini?</p>
                    <span class="delete-item-name" id="delete-nama"></span>
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

    function openEditModal(id, fakultasId, kode, nama, jenjang, keterangan) {
        document.getElementById('edit-fakultas').value    = fakultasId;
        document.getElementById('edit-kode').value        = kode;
        document.getElementById('edit-nama').value        = nama;
        document.getElementById('edit-jenjang').value     = jenjang;
        document.getElementById('edit-keterangan').value  = keterangan || '';
        document.getElementById('form-edit').action       = '/program-studi/' + id;
        openModal('modal-edit');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('delete-nama').textContent = nama;
        document.getElementById('form-hapus').action       = '/program-studi/' + id;
        openModal('modal-hapus');
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#prodiTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
