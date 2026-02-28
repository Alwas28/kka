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
    .page-header-left h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
    .page-header-left p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    .table-toolbar {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 12px; flex-wrap: wrap;
    }
    .search-box { position: relative; flex: 1; max-width: 320px; }
    .search-box input {
        width: 100%; padding: 9px 14px 9px 36px;
        border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit;
    }
    .search-box input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 13px; }
    .filter-select {
        padding: 9px 14px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit; background: white; min-width: 160px;
    }
    .filter-select:focus { outline: none; border-color: var(--maroon-main); }
    .toolbar-count { margin-left: auto; font-size: 13px; color: var(--text-secondary); }

    /* MAHASISWA INFO */
    .mhs-info { display: flex; align-items: center; gap: 10px; }
    .mhs-avatar {
        width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 12px;
    }
    .mhs-name  { font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .mhs-nim   { font-size: 11px; color: var(--text-secondary); font-family: monospace; }

    /* BADGES */
    .badge-status {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700;
        white-space: nowrap;
    }
    .badge-status.pending  { background: rgba(245,158,11,.1);  color: #92400e; border: 1px solid rgba(245,158,11,.2); }
    .badge-status.diterima { background: rgba(16,185,129,.1);  color: #065f46; border: 1px solid rgba(16,185,129,.2); }
    .badge-status.ditolak  { background: rgba(239,68,68,.1);   color: #b91c1c; border: 1px solid rgba(239,68,68,.2); }

    .badge-prodi {
        display: inline-flex; gap: 4px;
        background: rgba(16,185,129,.1); color: #065f46;
        border-radius: 20px; padding: 2px 9px; font-size: 11px; font-weight: 600;
    }

    /* FILE LINK */
    .file-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12px; color: var(--maroon-main); font-weight: 600;
        text-decoration: none;
    }
    .file-link:hover { text-decoration: underline; }
    .file-size-txt { font-size: 11px; color: var(--text-secondary); }

    /* ACTION BUTTONS */
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border: none; border-radius: 6px;
        font-size: 11px; font-weight: 700; cursor: pointer;
        font-family: inherit; text-decoration: none; transition: all .15s;
    }
    .btn-terima { background: rgba(16,185,129,.12); color: #065f46; }
    .btn-terima:hover { background: #10b981; color: white; }
    .btn-tolak  { background: rgba(239,68,68,.1);  color: #b91c1c; }
    .btn-tolak:hover  { background: #ef4444; color: white; }
    .actions-wrap { display: flex; gap: 6px; flex-wrap: wrap; }

    /* CATATAN VERIFIKASI */
    .catatan-txt {
        font-size: 11px; color: #b91c1c;
        margin-top: 4px; line-height: 1.4;
        max-width: 200px;
    }

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i  { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 10000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal { background: white; border-radius: 14px; width: 100%; max-width: 460px; box-shadow: 0 20px 60px rgba(0,0,0,.25); animation: modalIn .2s ease; overflow: hidden; }
    @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--gray-border); }
    .modal-header h3 { font-size: 15px; font-weight: 700; margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--text-secondary); cursor: pointer; }
    .modal-body   { padding: 20px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid var(--gray-border); background: #f9fafb; }
    .form-group-modal { display: flex; flex-direction: column; gap: 6px; }
    .form-group-modal label { font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .04em; }
    .form-control-modal {
        padding: 10px 14px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit; width: 100%; box-sizing: border-box; resize: vertical;
    }
    .form-control-modal:focus { outline: none; border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
    .btn-modal {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border: none; border-radius: 8px;
        font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit;
    }
    .btn-modal-outline { background: white; border: 1.5px solid var(--gray-border); color: var(--text-primary); }
    .btn-modal-danger  { background: #ef4444; color: white; }
    .btn-modal-danger:hover { background: #dc2626; }
    .modal-dok-info { font-size: 13px; color: var(--text-secondary); margin-bottom: 14px; padding: 10px 14px; background: #f9fafb; border-radius: 8px; border: 1px solid var(--gray-border); }
    .modal-dok-info strong { color: var(--text-primary); }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas {{ $icon }}" style="color:var(--maroon-main); margin-right:8px;"></i>
                {{ $title }}
            </h2>
            <p>
                Verifikasi dokumen {{ strtolower($title) }} yang dikirimkan mahasiswa
                @if(!$isAllProdi)
                    &mdash; <span style="color:var(--maroon-main); font-weight:600;">sesuai prodi Anda</span>
                @else
                    &mdash; <span style="color:#059669; font-weight:600;">semua program studi</span>
                @endif
            </p>
        </div>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08); border:1px solid rgba(16,185,129,.25); border-radius:10px; padding:12px 16px; margin-bottom:16px; color:#065f46; font-size:13px; display:flex; gap:10px; align-items:center;">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama, NIM..." oninput="filterTable()">
        </div>
        <select class="filter-select" id="filterStatus" onchange="filterTable()">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Verifikasi</option>
            <option value="diterima">Diterima</option>
            <option value="ditolak">Ditolak</option>
        </select>
        @if($isAllProdi)
        <select class="filter-select" id="filterProdi" onchange="filterTable()">
            <option value="">Semua Prodi</option>
            @foreach($dokumenList->pluck('pendaftaran.mahasiswa.programStudi')->filter()->unique('id')->sortBy('nama') as $prodi)
                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
            @endforeach
        </select>
        @endif
        <div class="toolbar-count">Total: <strong id="rowCount">{{ $dokumenList->count() }}</strong></div>
    </div>

    <div class="table-container">
        @if($dokumenList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:44px;">No</th>
                    <th>Mahasiswa</th>
                    <th>Program Studi</th>
                    <th>Kegiatan</th>
                    <th>Dokumen</th>
                    <th>File</th>
                    <th>Status</th>
                    @if($canVerifikasi)<th style="width:160px;">Aksi</th>@endif
                </tr>
            </thead>
            <tbody id="verTableBody">
                @foreach($dokumenList as $i => $dok)
                @php
                    $mhs   = $dok->pendaftaran?->mahasiswa;
                    $prodi = $mhs?->programStudi;
                    $inits = collect(explode(' ', $mhs?->nama ?? '-'))
                                ->map(fn($w) => strtoupper(substr($w,0,1)))
                                ->take(2)->join('');
                @endphp
                <tr data-search="{{ strtolower(($mhs?->nama ?? '') . ' ' . ($mhs?->nim ?? '')) }}"
                    data-status="{{ $dok->status }}"
                    data-prodi="{{ $prodi?->id }}">
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="mhs-info">
                            <div class="mhs-avatar">{{ $inits }}</div>
                            <div>
                                <div class="mhs-name">{{ $mhs?->nama ?? '—' }}</div>
                                <div class="mhs-nim">{{ $mhs?->nim ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($prodi)
                            <span class="badge-prodi">{{ $prodi->nama }}</span>
                        @else —
                        @endif
                    </td>
                    <td style="font-size:12px; color:var(--text-secondary);">
                        {{ $dok->kegiatanDokumen?->kegiatan?->nama ?? '—' }}
                    </td>
                    <td style="font-size:13px; font-weight:600;">
                        {{ $dok->kegiatanDokumen?->nama ?? '—' }}
                    </td>
                    <td>
                        <a href="{{ Storage::url($dok->file_path) }}" target="_blank" class="file-link">
                            <i class="fas fa-file-arrow-down"></i> Lihat File
                        </a>
                        <div class="file-size-txt">{{ $dok->file_size_formatted }}</div>
                    </td>
                    <td>
                        <span class="badge-status {{ $dok->status }}">
                            @if($dok->status === 'pending')
                                <i class="fas fa-clock"></i> Menunggu
                            @elseif($dok->status === 'diterima')
                                <i class="fas fa-circle-check"></i> Diterima
                            @else
                                <i class="fas fa-circle-xmark"></i> Ditolak
                            @endif
                        </span>
                        @if($dok->status === 'ditolak' && $dok->catatan_verifikasi)
                            <div class="catatan-txt"><i class="fas fa-comment-dots"></i> {{ $dok->catatan_verifikasi }}</div>
                        @endif
                    </td>
                    @if($canVerifikasi)
                    <td>
                        <div class="actions-wrap">
                            @if($dok->status !== 'diterima')
                            <form action="{{ route($routeTerima, $dok->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-act btn-terima"
                                        onclick="return confirm('Terima dokumen ini?')">
                                    <i class="fas fa-check"></i> Terima
                                </button>
                            </form>
                            @endif
                            @if($dok->status !== 'ditolak')
                            <button type="button" class="btn-act btn-tolak"
                                    data-url="{{ route($routeTolak, $dok->id) }}"
                                    data-dok="{{ $dok->kegiatanDokumen?->nama }}"
                                    data-mhs="{{ $mhs?->nama }}"
                                    onclick="openTolakModal(this)">
                                <i class="fas fa-xmark"></i> Tolak
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
            <i class="fas {{ $icon }}"></i>
            <h3>Belum ada dokumen {{ strtolower($title) }}</h3>
            <p>Dokumen yang dikirimkan mahasiswa akan muncul di sini setelah mereka submit pendaftaran.</p>
        </div>
        @endif
    </div>

</div>

{{-- MODAL TOLAK --}}
@if($canVerifikasi)
<div class="modal-overlay" id="modal-tolak">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-circle-xmark" style="color:#ef4444; margin-right:8px;"></i>Tolak Dokumen</h3>
            <button class="modal-close" onclick="closeTolakModal()">&times;</button>
        </div>
        <form id="form-tolak" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-dok-info" id="modal-dok-info"></div>
                <div class="form-group-modal">
                    <label>Alasan / Catatan Penolakan <span style="color:#ef4444;">*</span></label>
                    <textarea name="catatan_verifikasi" id="modal-catatan"
                              class="form-control-modal" rows="4"
                              placeholder="Tuliskan alasan penolakan agar mahasiswa dapat memperbaiki dokumen..."></textarea>
                    <div style="font-size:11px; color:var(--text-secondary); margin-top:2px;">Catatan ini akan ditampilkan ke mahasiswa.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-outline" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-danger">
                    <i class="fas fa-circle-xmark"></i> Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
    function filterTable() {
        const q      = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const prodiEl = document.getElementById('filterProdi');
        const prodi  = prodiEl ? prodiEl.value : '';
        let visible  = 0;

        document.querySelectorAll('#verTableBody tr[data-search]').forEach(row => {
            const ok = (!q      || row.dataset.search.includes(q))
                    && (!status || row.dataset.status === status)
                    && (!prodi  || row.dataset.prodi  === prodi);
            row.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });
        document.getElementById('rowCount').textContent = visible;
    }

    function openTolakModal(btn) {
        document.getElementById('form-tolak').action = btn.dataset.url;
        document.getElementById('modal-dok-info').innerHTML =
            `Dokumen: <strong>${btn.dataset.dok}</strong><br>Mahasiswa: <strong>${btn.dataset.mhs}</strong>`;
        document.getElementById('modal-catatan').value = '';
        document.getElementById('modal-tolak').classList.add('active');
    }

    function closeTolakModal() {
        document.getElementById('modal-tolak').classList.remove('active');
    }

    document.getElementById('modal-tolak')?.addEventListener('click', function(e) {
        if (e.target === this) closeTolakModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeTolakModal();
    });
</script>
@endsection
