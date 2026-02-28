@extends('layouts.users')

@section('css')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .page-header-left h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
    .page-header-left p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    .btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border: none; border-radius: 8px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        transition: all 0.3s ease; font-family: inherit; text-decoration: none;
    }
    .btn-sm { padding: 6px 12px; font-size: 12px; }
    .btn-primary { background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main)); color: white; }
    .btn-primary:hover { box-shadow: 0 4px 15px rgba(165,42,42,0.4); transform: translateY(-1px); color: white; }
    .btn-warning { background: #f59e0b; color: white; }
    .btn-warning:hover { background: #d97706; color: white; }
    .btn-danger  { background: #ef4444; color: white; }
    .btn-danger:hover  { background: #dc2626; color: white; }
    .btn-secondary { background: var(--gray-border); color: var(--text-primary); }
    .btn-secondary:hover { background: #d1d5db; }

    .table-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 15px; flex-wrap: wrap; }
    .search-box { position: relative; flex: 1; max-width: 350px; }
    .search-box input { width: 100%; padding: 10px 14px 10px 38px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; transition: all 0.3s ease; }
    .search-box input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 14px; }
    .table-info { font-size: 13px; color: var(--text-secondary); }
    .action-btns { display: flex; gap: 6px; }

    .nama-badge {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 14px;
    }
    .keterangan-text { color: var(--text-secondary); font-size: 13px; }

    .access-notice {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px;
        background: rgba(245,158,11,0.08);
        border: 1px solid rgba(245,158,11,0.3);
        border-radius: 8px; font-size: 13px; color: #92400e; margin-bottom: 16px;
    }
    .access-notice i { color: #f59e0b; font-size: 16px; flex-shrink: 0; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal { background: white; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalIn 0.3s ease; overflow: hidden; }
    @keyframes modalIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--gray-border); }
    .modal-header h3 { font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--text-secondary); cursor: pointer; padding: 5px; transition: color 0.2s; }
    .modal-close:hover { color: var(--text-primary); }
    .modal-body { padding: 24px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--gray-border); background: var(--gray-light); }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
    .form-group input, .form-group textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; transition: all 0.3s ease; box-sizing: border-box; }
    .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .form-hint { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }
    .delete-info { text-align: center; padding: 10px 0; }
    .delete-info i { font-size: 48px; color: #ef4444; margin-bottom: 15px; display: block; }
    .delete-info p { font-size: 14px; color: var(--text-secondary); margin: 0 0 8px 0; }
    .delete-item-name { background: rgba(239,68,68,0.1); color: #dc2626; padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: 600; display: inline-block; }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .table-toolbar { flex-direction: column; align-items: flex-start; }
        .search-box { max-width: 100%; width: 100%; }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-list" style="color:var(--maroon-main); margin-right:8px;"></i>Jenis KKA</h2>
            <p>Kelola jenis-jenis kegiatan KKA yang tersedia</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.jenis-kka'))
        <button class="btn btn-primary" onclick="openModal('modal-tambah')">
            <i class="fas fa-plus"></i>
            <span>Tambah Jenis KKA</span>
        </button>
        @endif
    </div>

    @if(!auth()->user()->hasAccess('tambah.jenis-kka') && !auth()->user()->hasAccess('edit.jenis-kka') && !auth()->user()->hasAccess('hapus.jenis-kka'))
    <div class="access-notice">
        <i class="fas fa-info-circle"></i>
        Anda hanya memiliki akses untuk melihat data. Hubungi administrator untuk akses lebih lanjut.
    </div>
    @endif

    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari jenis KKA..." onkeyup="filterTable()">
        </div>
        <div class="table-info">Total: <strong>{{ $jenisKkaList->count() }}</strong> jenis KKA</div>
    </div>

    <div class="table-container">
        @if($jenisKkaList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    @if(auth()->user()->hasAccess('edit.jenis-kka') || auth()->user()->hasAccess('hapus.jenis-kka'))
                    <th style="width:120px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($jenisKkaList as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="nama-badge">{{ $item->nama }}</span></td>
                    <td><span class="keterangan-text">{{ $item->keterangan ?? '-' }}</span></td>
                    @if(auth()->user()->hasAccess('edit.jenis-kka') || auth()->user()->hasAccess('hapus.jenis-kka'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.jenis-kka'))
                            <button class="btn btn-warning btn-sm" title="Edit"
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ addslashes($item->keterangan) }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.jenis-kka'))
                            <button class="btn btn-danger btn-sm" title="Hapus"
                                onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama) }}')">
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
            <i class="fas fa-list"></i>
            <h3>Belum ada data jenis KKA</h3>
            @if(auth()->user()->hasAccess('tambah.jenis-kka'))
                <p>Klik tombol "Tambah Jenis KKA" untuk menambahkan data baru.</p>
            @else
                <p>Belum ada data yang tersedia.</p>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH --}}
@if(auth()->user()->hasAccess('tambah.jenis-kka'))
<div class="modal-overlay" id="modal-tambah">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="color:var(--maroon-main); margin-right:8px;"></i>Tambah Jenis KKA</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah')">&times;</button>
        </div>
        <form action="{{ route('jenis-kka.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Jenis KKA <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama" placeholder="contoh: KKA Reguler" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Deskripsi singkat (opsional)">
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

{{-- MODAL EDIT --}}
@if(auth()->user()->hasAccess('edit.jenis-kka'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="color:#f59e0b; margin-right:8px;"></i>Edit Jenis KKA</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Jenis KKA <span style="color:#ef4444">*</span></label>
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
@if(auth()->user()->hasAccess('hapus.jenis-kka'))
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
                    <p>Apakah Anda yakin ingin menghapus jenis KKA ini?</p>
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
    function openModal(id) { document.getElementById(id)?.classList.add('active'); }
    function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('active'); });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    });

    function openEditModal(id, nama, keterangan) {
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-keterangan').value = keterangan || '';
        document.getElementById('form-edit').action = '/jenis-kka/' + id;
        openModal('modal-edit');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('delete-nama').textContent = nama;
        document.getElementById('form-hapus').action = '/jenis-kka/' + id;
        openModal('modal-hapus');
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
