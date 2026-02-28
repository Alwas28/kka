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

    /* TAGS */
    .tag { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; }
    .tag-jenis  { background: rgba(165,42,42,0.1); color: var(--maroon-dark); }
    .tag-tahun  { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .tag-periode{ background: rgba(139,92,246,0.1); color: #6d28d9; }

    .date-range { font-size: 12px; color: var(--text-secondary); white-space: nowrap; }
    .date-range strong { color: var(--text-primary); }

    /* FASE AKTIF */
    .fase-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .fase-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .fase-survey      { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .fase-pendaftaran { background: rgba(16,185,129,0.1); color: #065f46; }
    .fase-verifikasi  { background: rgba(245,158,11,0.1); color: #92400e; }
    .fase-setup       { background: rgba(20,184,166,0.1); color: #0d9488; }
    .fase-pelaksanaan { background: rgba(139,92,246,0.1); color: #4c1d95; }
    .fase-pelaporan   { background: rgba(236,72,153,0.1); color: #831843; }
    .fase-none        { background: rgba(107,114,128,0.1); color: #374151; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
    .fase-badge::before { animation: pulse 1.5s infinite; }

    .notice-berlangsung {
        display: flex; align-items: center; gap: 10px; padding: 12px 16px;
        background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.3);
        border-radius: 8px; font-size: 13px; color: #065f46; margin-bottom: 16px;
    }
    .notice-berlangsung i { color: #10b981; font-size: 16px; flex-shrink: 0; }

    /* Toggle Pelaksanaan */
    .toggle-row { display: flex; flex-direction: column; gap: 6px; }
    .toggle-item { display: flex; align-items: center; gap: 8px; }
    .toggle-item label { font-size: 11px; font-weight: 600; color: var(--text-secondary); min-width: 80px; }
    .toggle-pill {
        position: relative; display: inline-block; width: 38px; height: 20px;
        flex-shrink: 0; cursor: pointer;
    }
    .toggle-pill input { display: none; }
    .toggle-pill-slider {
        position: absolute; inset: 0; background: #d1d5db; border-radius: 20px; transition: 0.3s;
    }
    .toggle-pill-slider::before {
        content: ''; position: absolute; width: 14px; height: 14px;
        left: 3px; bottom: 3px; background: white; border-radius: 50%;
        transition: 0.3s; box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .toggle-pill input:checked + .toggle-pill-slider { background: #10b981; }
    .toggle-pill input:checked + .toggle-pill-slider::before { transform: translateX(18px); }
    .toggle-status { font-size: 11px; font-weight: 700; }
    .toggle-status.on  { color: #059669; }
    .toggle-status.off { color: #9ca3af; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

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
            <h2><i class="fas fa-clock" style="color:#10b981; margin-right:8px;"></i>Sedang Dilaksanakan</h2>
            <p>Kegiatan KKA yang sedang berjalan saat ini</p>
        </div>
        @if(auth()->user()->hasAccess('tambah.kegiatan'))
        <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            <span>Tambah Kegiatan</span>
        </a>
        @endif
    </div>

    <div class="notice-berlangsung">
        <i class="fas fa-circle-notch fa-spin"></i>
        Menampilkan kegiatan yang <strong style="margin:0 4px;">sedang berlangsung</strong> berdasarkan tanggal kegiatan hari ini.
    </div>

    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari kegiatan..." onkeyup="filterTable()">
        </div>
        <div class="table-info">Total: <strong>{{ $kegiatanList->count() }}</strong> kegiatan berlangsung</div>
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
                    <th style="width:140px">Fase Aktif</th>
                    @if(auth()->user()->hasAccess('edit.kegiatan'))
                    <th style="width:160px">Pelaksanaan</th>
                    @endif
                    @if(auth()->user()->hasAccess('edit.kegiatan') || auth()->user()->hasAccess('hapus.kegiatan'))
                    <th style="width:110px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($kegiatanList as $index => $item)
                @php
                    $today = now()->startOfDay();

                    $faseLabel = [
                        'survey'         => ['label' => 'Survey',         'class' => 'fase-survey'],
                        'pendaftaran'    => ['label' => 'Pendaftaran',    'class' => 'fase-pendaftaran'],
                        'verifikasi'     => ['label' => 'Verifikasi',     'class' => 'fase-verifikasi'],
                        'setup_kelompok' => ['label' => 'Setup Kelompok', 'class' => 'fase-setup'],
                        'pelaksanaan'    => ['label' => 'Pelaksanaan',    'class' => 'fase-pelaksanaan'],
                        'pelaporan'      => ['label' => 'Pelaporan',      'class' => 'fase-pelaporan'],
                    ];

                    $faseAktif = [];
                    foreach ($item->tahapan as $t) {
                        if ($t->mulai && $t->selesai && $today->between($t->mulai, $t->selesai)) {
                            $faseAktif[] = $faseLabel[$t->nama] ?? ['label' => $t->nama, 'class' => 'fase-none'];
                        }
                    }
                @endphp
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
                        @if(count($faseAktif) > 0)
                            <div style="display:flex; flex-direction:column; gap:4px;">
                            @foreach($faseAktif as $fase)
                                <span class="fase-badge {{ $fase['class'] }}">{{ $fase['label'] }}</span>
                            @endforeach
                            </div>
                        @else
                            <span class="fase-badge fase-none">—</span>
                        @endif
                    </td>
                    @if(auth()->user()->hasAccess('edit.kegiatan'))
                    <td>
                        <div class="toggle-row">
                            {{-- Toggle Logbook --}}
                            <div class="toggle-item">
                                <form method="POST" action="{{ route('kegiatan.toggle-aktif', $item) }}" style="display:contents;">
                                    @csrf
                                    <input type="hidden" name="field" value="logbook_aktif">
                                    <label class="toggle-pill" title="{{ $item->logbook_aktif ? 'Nonaktifkan Logbook' : 'Aktifkan Logbook' }}">
                                        <input type="checkbox" onchange="this.form.submit()" {{ $item->logbook_aktif ? 'checked' : '' }}>
                                        <span class="toggle-pill-slider"></span>
                                    </label>
                                </form>
                                <span class="toggle-status {{ $item->logbook_aktif ? 'on' : 'off' }}">Logbook</span>
                            </div>
                            {{-- Toggle Laporan --}}
                            <div class="toggle-item">
                                <form method="POST" action="{{ route('kegiatan.toggle-aktif', $item) }}" style="display:contents;">
                                    @csrf
                                    <input type="hidden" name="field" value="laporan_aktif">
                                    <label class="toggle-pill" title="{{ $item->laporan_aktif ? 'Nonaktifkan Laporan' : 'Aktifkan Laporan' }}">
                                        <input type="checkbox" onchange="this.form.submit()" {{ $item->laporan_aktif ? 'checked' : '' }}>
                                        <span class="toggle-pill-slider"></span>
                                    </label>
                                </form>
                                <span class="toggle-status {{ $item->laporan_aktif ? 'on' : 'off' }}">Laporan</span>
                            </div>
                        </div>
                    </td>
                    @endif
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
            <i class="fas fa-clock"></i>
            <h3>Tidak ada kegiatan yang sedang berlangsung</h3>
            <p>Saat ini tidak ada kegiatan yang aktif.</p>
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
