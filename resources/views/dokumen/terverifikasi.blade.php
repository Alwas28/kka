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
        background: linear-gradient(135deg, #10b981, #059669);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 12px;
    }
    .mhs-name  { font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .mhs-nim   { font-size: 11px; color: var(--text-secondary); font-family: monospace; }

    /* BADGES */
    .badge-prodi {
        display: inline-flex; gap: 4px;
        background: rgba(16,185,129,.1); color: #065f46;
        border-radius: 20px; padding: 2px 9px; font-size: 11px; font-weight: 600;
    }
    .badge-verified {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(16,185,129,.1); color: #065f46;
        border: 1px solid rgba(16,185,129,.2);
        border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700;
    }

    /* DOKUMEN LIST */
    .dok-list { display: flex; flex-direction: column; gap: 3px; }
    .dok-item { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-secondary); }
    .dok-item i { font-size: 10px; }
    .dok-item.dok-ok i { color: #10b981; }

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i  { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    /* STAT CARDS */
    .stat-row { display: flex; gap: 14px; margin-bottom: 20px; flex-wrap: wrap; }
    .stat-card {
        flex: 1; min-width: 160px;
        background: var(--bg-card, white); border: 1px solid var(--gray-border);
        border-radius: 10px; padding: 16px 18px;
        display: flex; align-items: center; gap: 14px;
    }
    .stat-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .stat-icon.green  { background: rgba(16,185,129,.1); color: #059669; }
    .stat-icon.blue   { background: rgba(59,130,246,.1);  color: #2563eb; }
    .stat-icon.purple { background: rgba(139,92,246,.1);  color: #7c3aed; }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }

    /* ACTION BUTTON */
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border: none; border-radius: 6px;
        font-size: 11px; font-weight: 700; cursor: pointer;
        font-family: inherit; transition: all .15s;
    }
    .btn-revert { background: rgba(245,158,11,.12); color: #92400e; }
    .btn-revert:hover { background: #f59e0b; color: white; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 10000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal { background: white; border-radius: 14px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,.25); animation: modalIn .2s ease; overflow: hidden; }
    @keyframes modalIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--gray-border); }
    .modal-header h3 { font-size: 15px; font-weight: 700; margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--text-secondary); cursor: pointer; }
    .modal-body { padding: 20px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid var(--gray-border); background: #f9fafb; }
    .btn-modal {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border: none; border-radius: 8px;
        font-size: 13px; font-weight: 700; cursor: pointer; font-family: inherit;
    }
    .btn-modal-outline { background: white; border: 1.5px solid var(--gray-border); color: var(--text-primary); }
    .btn-modal-warning { background: #f59e0b; color: white; }
    .btn-modal-warning:hover { background: #d97706; }
    .confirm-center { text-align: center; }
    .confirm-center i { font-size: 46px; color: #f59e0b; margin-bottom: 12px; display: block; }
    .confirm-center p { font-size: 14px; color: var(--text-secondary); margin: 0 0 8px; }
    .confirm-center strong { color: var(--text-primary); }
    .confirm-center small { font-size: 12px; color: var(--text-secondary); }

    @media (max-width: 640px) {
        .table-toolbar { flex-direction: column; align-items: stretch; }
        .search-box { max-width: 100%; }
        .filter-select { width: 100%; }
        .toolbar-count { margin-left: 0; }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas fa-check-double" style="color:var(--maroon-main); margin-right:8px;"></i>
                Mahasiswa Terverifikasi
            </h2>
            <p>
                Daftar mahasiswa yang seluruh dokumen pendaftarannya telah diterima
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

    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
            <div>
                <div class="stat-value">{{ $statTotal }}</div>
                <div class="stat-label">Total Terverifikasi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-building-columns"></i></div>
            <div>
                <div class="stat-value">{{ $statProdi }}</div>
                <div class="stat-label">Program Studi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <div class="stat-value">{{ $statKegiatan }}</div>
                <div class="stat-label">Kegiatan</div>
            </div>
        </div>
    </div>

    <form method="GET" id="filterForm">
        <div class="table-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, NIM..." oninput="delayFilter()">
            </div>
            @if($isAllProdi)
            <select name="prodi" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Prodi</option>
                @foreach($prodiList as $prodi)
                    <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama }}
                    </option>
                @endforeach
            </select>
            @endif
            <select name="kegiatan" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Kegiatan</option>
                @foreach($kegiatanList as $keg)
                    <option value="{{ $keg->id }}" {{ request('kegiatan') == $keg->id ? 'selected' : '' }}>
                        {{ $keg->nama }}
                    </option>
                @endforeach
            </select>
            <div class="toolbar-count">Total: <strong>{{ $mahasiswaList->total() }}</strong></div>
        </div>
    </form>

    <div class="table-container">
        @if($mahasiswaList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:44px;">No</th>
                    <th>Mahasiswa</th>
                    <th>Program Studi</th>
                    <th>Kegiatan</th>
                    <th>Dokumen</th>
                    <th>Status</th>
                    @if($canEdit)<th style="width:100px;">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $i => $mhs)
                @php
                    $prodi = $mhs->programStudi;
                    $pend  = $mhs->pendaftaran;
                    $docs  = $pend?->dokumen ?? collect();
                    $inits = collect(explode(' ', $mhs->nama))
                                ->map(fn($w) => strtoupper(substr($w,0,1)))
                                ->take(2)->join('');
                @endphp
                <tr>
                    <td>{{ $mahasiswaList->firstItem() + $i }}</td>
                    <td>
                        <div class="mhs-info">
                            <div class="mhs-avatar">{{ $inits }}</div>
                            <div>
                                <div class="mhs-name">{{ $mhs->nama }}</div>
                                <div class="mhs-nim">{{ $mhs->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($prodi)
                            <span class="badge-prodi">{{ $prodi->nama }}</span>
                        @else &mdash;
                        @endif
                    </td>
                    <td style="font-size:12px; color:var(--text-secondary);">
                        {{ $pend?->kegiatan?->nama ?? '&mdash;' }}
                    </td>
                    <td>
                        <div class="dok-list">
                            @foreach($docs as $doc)
                            <div class="dok-item dok-ok">
                                <i class="fas fa-circle-check"></i>
                                <span>{{ $doc->kegiatanDokumen?->nama ?? 'Dokumen' }}</span>
                            </div>
                            @endforeach
                            @if($docs->isEmpty())
                                <span style="font-size:11px; color:var(--text-secondary);">&mdash;</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge-verified">
                            <i class="fas fa-circle-check"></i> Terverifikasi
                        </span>
                    </td>
                    @if($canEdit)
                    <td>
                        <button type="button" class="btn-act btn-revert"
                                data-id="{{ $mhs->id }}"
                                data-nama="{{ $mhs->nama }}"
                                onclick="openRevertModal(this)">
                            <i class="fas fa-rotate-left"></i> Kembalikan
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINATION --}}
        @if($mahasiswaList->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Halaman {{ $mahasiswaList->currentPage() }} dari {{ $mahasiswaList->lastPage() }}
                &mdash; {{ $mahasiswaList->firstItem() }}–{{ $mahasiswaList->lastItem() }} dari {{ $mahasiswaList->total() }} data
            </div>
            <div class="pagination-btns">
                @if($mahasiswaList->onFirstPage())
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $mahasiswaList->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $start = max(1, $mahasiswaList->currentPage() - 2);
                    $end   = min($mahasiswaList->lastPage(), $mahasiswaList->currentPage() + 2);
                @endphp
                @if($start > 1)
                    <a href="{{ $mahasiswaList->url(1) }}" class="page-btn">1</a>
                    @if($start > 2)<span class="page-btn disabled">…</span>@endif
                @endif
                @for($i = $start; $i <= $end; $i++)
                    <a href="{{ $mahasiswaList->url($i) }}" class="page-btn {{ $i == $mahasiswaList->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($end < $mahasiswaList->lastPage())
                    @if($end < $mahasiswaList->lastPage() - 1)<span class="page-btn disabled">…</span>@endif
                    <a href="{{ $mahasiswaList->url($mahasiswaList->lastPage()) }}" class="page-btn">{{ $mahasiswaList->lastPage() }}</a>
                @endif
                @if($mahasiswaList->hasMorePages())
                    <a href="{{ $mahasiswaList->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-check-double"></i>
            <h3>Belum ada mahasiswa terverifikasi</h3>
            <p>Mahasiswa yang seluruh dokumennya telah diterima akan muncul di sini.</p>
        </div>
        @endif
    </div>

</div>
@endsection

{{-- MODAL KEMBALIKAN --}}
@if($canEdit)
<div class="modal-overlay" id="modal-revert">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-rotate-left" style="color:#f59e0b; margin-right:8px;"></i>Kembalikan ke Verifikasi</h3>
            <button class="modal-close" onclick="closeRevertModal()">&times;</button>
        </div>
        <form id="form-revert" method="POST">
            @csrf
            <div class="modal-body">
                <div class="confirm-center">
                    <i class="fas fa-triangle-exclamation"></i>
                    <p>Kembalikan <strong id="revert-nama"></strong> ke tahap verifikasi ulang?</p>
                    <small>Status mahasiswa akan kembali ke "Menunggu Verifikasi" dan semua dokumen akan di-reset ke pending.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-modal-outline" onclick="closeRevertModal()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-warning">
                    <i class="fas fa-rotate-left"></i> Ya, Kembalikan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@section('js')
<script>
    let filterTimer;
    function delayFilter() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(() => document.getElementById('filterForm').submit(), 600);
    }

    function openRevertModal(btn) {
        document.getElementById('form-revert').action = '{{ url("dokumen/terverifikasi") }}/' + btn.dataset.id + '/revert';
        document.getElementById('revert-nama').textContent = btn.dataset.nama;
        document.getElementById('modal-revert').classList.add('active');
    }

    function closeRevertModal() {
        document.getElementById('modal-revert').classList.remove('active');
    }

    document.getElementById('modal-revert')?.addEventListener('click', function(e) {
        if (e.target === this) closeRevertModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeRevertModal();
    });
</script>
@endsection
