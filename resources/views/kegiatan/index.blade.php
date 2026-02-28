@extends('layouts.users')

@section('css')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; flex-wrap: wrap; gap: 15px;
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

    .table-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 12px; flex-wrap: wrap; }
    .search-box { position: relative; flex: 1; max-width: 340px; }
    .search-box input { width: 100%; padding: 9px 14px 9px 36px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; transition: all 0.3s; }
    .search-box input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 13px; }
    .table-info { font-size: 13px; color: var(--text-secondary); }
    .action-btns { display: flex; gap: 6px; }

    /* STATUS BADGES */
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .status-akan_datang  { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .status-akan_datang::before { background: #3b82f6; }
    .status-berlangsung  { background: rgba(16,185,129,0.1); color: #065f46; }
    .status-berlangsung::before { background: #10b981; animation: pulse 1.5s infinite; }
    .status-selesai      { background: rgba(107,114,128,0.1); color: #374151; }
    .status-selesai::before { background: #9ca3af; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* TAGS */
    .tag { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; }
    .tag-jenis  { background: rgba(165,42,42,0.1); color: var(--maroon-dark); }
    .tag-tahun  { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .tag-periode{ background: rgba(139,92,246,0.1); color: #6d28d9; }

    .date-range { font-size: 12px; color: var(--text-secondary); white-space: nowrap; }
    .date-range strong { color: var(--text-primary); }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    .access-notice {
        display: flex; align-items: center; gap: 10px; padding: 12px 16px;
        background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.3);
        border-radius: 8px; font-size: 13px; color: #92400e; margin-bottom: 16px;
    }
    .access-notice i { color: #f59e0b; font-size: 16px; flex-shrink: 0; }

    /* HAPUS MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal { background: white; border-radius: 16px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalIn 0.3s ease; overflow: hidden; }
    @keyframes modalIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--gray-border); }
    .modal-header h3 { font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--text-secondary); cursor: pointer; padding: 5px; }
    .modal-body { padding: 24px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--gray-border); background: var(--gray-light); }
    .delete-info { text-align: center; padding: 10px 0; }
    .delete-info i { font-size: 48px; color: #ef4444; margin-bottom: 15px; display: block; }
    .delete-info p { font-size: 14px; color: var(--text-secondary); margin: 0 0 8px 0; }
    .delete-item-name { background: rgba(239,68,68,0.1); color: #dc2626; padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: 600; display: inline-block; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-clipboard-list" style="color:var(--maroon-main); margin-right:8px;"></i>Kegiatan KKA</h2>
            <p>Daftar kegiatan KKA beserta timeline dan konfigurasinya</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.kegiatan'))
        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            <span>Tambah Kegiatan</span>
        </a>
        @endif
    </div>

    @if(!auth()->user()->hasAccess('tambah.kegiatan') && !auth()->user()->hasAccess('edit.kegiatan') && !auth()->user()->hasAccess('hapus.kegiatan'))
    <div class="access-notice">
        <i class="fas fa-info-circle"></i>
        Anda hanya memiliki akses untuk melihat data. Hubungi administrator untuk akses lebih lanjut.
    </div>
    @endif

    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari kegiatan..." onkeyup="filterTable()">
        </div>
        <div class="table-info">Total: <strong>{{ $kegiatanList->count() }}</strong> kegiatan</div>
    </div>

    <div class="table-container">
        @if($kegiatanList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Kegiatan</th>
                    <th>Jenis / Tahun / Periode</th>
                    <th>Tanggal Kegiatan</th>
                    <th style="width:120px">Status</th>
                    @if(auth()->user()->hasAccess('edit.kegiatan') || auth()->user()->hasAccess('hapus.kegiatan'))
                    <th style="width:110px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($kegiatanList as $index => $item)
                <tr data-search="{{ strtolower($item->nama . ' ' . $item->jenisKka?->nama . ' ' . $item->tahun?->nama . ' ' . $item->periode?->nama) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight:600; font-size:14px; margin-bottom:2px;">{{ $item->nama }}</div>
                    </td>
                    <td>
                        <div style="display:flex; flex-wrap:wrap; gap:4px;">
                            @if($item->jenisKka)
                            <span class="tag tag-jenis">{{ $item->jenisKka->nama }}</span>
                            @endif
                            @if($item->tahun)
                            <span class="tag tag-tahun">{{ $item->tahun->nama }}</span>
                            @endif
                            @if($item->periode)
                            <span class="tag tag-periode">{{ $item->periode->nama }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="date-range">
                            <strong>{{ $item->kegiatan_mulai->format('d M Y') }}</strong>
                            <span style="margin:0 4px;">—</span>
                            <strong>{{ $item->kegiatan_selesai->format('d M Y') }}</strong>
                        </div>
                    </td>
                    <td>
                        @php $status = $item->status; @endphp
                        <span class="status-badge status-{{ $status }}">
                            @if($status === 'akan_datang') Akan Datang
                            @elseif($status === 'berlangsung') Berlangsung
                            @else Selesai
                            @endif
                        </span>
                    </td>
                    @if(auth()->user()->hasAccess('edit.kegiatan') || auth()->user()->hasAccess('hapus.kegiatan'))
                    <td>
                        <div class="action-btns">
                            @if(auth()->user()->hasAccess('edit.kegiatan'))
                            <a href="{{ route('kegiatan.edit', $item) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if(auth()->user()->hasAccess('hapus.kegiatan'))
                            <button class="btn btn-danger btn-sm" title="Hapus"
                                onclick="openDeleteModal({{ $item->id }}, {{ json_encode($item->nama) }})">
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
            <i class="fas fa-clipboard-list"></i>
            <h3>Belum ada data kegiatan</h3>
            @if(auth()->user()->hasAccess('tambah.kegiatan'))
                <p>Klik tombol "Tambah Kegiatan" untuk membuat kegiatan baru.</p>
            @else
                <p>Belum ada data yang tersedia.</p>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- MODAL HAPUS --}}
@if(auth()->user()->hasAccess('hapus.kegiatan'))
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
                    <p>Apakah Anda yakin ingin menghapus kegiatan ini?</p>
                    <span class="delete-item-name" id="delete-nama"></span>
                    <p style="margin-top:10px; font-size:12px; color:#ef4444;">Semua dokumen yang terkait akan ikut terhapus.</p>
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
    document.querySelectorAll('.modal-overlay').forEach(o => {
        o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    });

    function openDeleteModal(id, nama) {
        document.getElementById('delete-nama').textContent = nama;
        document.getElementById('form-hapus').action = '/kegiatan/' + id;
        openModal('modal-hapus');
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#tableBody tr[data-search]').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
