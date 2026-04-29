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

    /* BUTTONS */
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
        transition: all 0.2s;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-sm { padding: 5px 11px; font-size: 12px; }

    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; color: white; }
    .btn-danger  { background: #ef4444; color: white; }
    .btn-danger:hover  { background: #dc2626; color: white; }
    .btn-secondary { background: var(--gray-border, #e5e7eb); color: var(--text-primary); }
    .btn-secondary:hover { background: #d1d5db; }

    .action-btns { display: flex; gap: 5px; }

    /* TOOLBAR */
    .table-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 9px 14px 9px 36px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        transition: border-color 0.2s;
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

    .filter-select {
        padding: 9px 14px;
        border: 1px solid var(--gray-border);
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        color: var(--text-primary);
        background: white;
        cursor: pointer;
        transition: border-color 0.2s;
        min-width: 200px;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--maroon-main);
        box-shadow: 0 0 0 3px rgba(165,42,42,0.1);
    }

    .toolbar-count {
        margin-left: auto;
        font-size: 13px;
        color: var(--text-secondary);
    }

    /* MAHASISWA CELL */
    .mhs-info { display: flex; align-items: center; gap: 10px; }
    .mhs-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--maroon-main), var(--maroon-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
    }

    .mhs-name  { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .mhs-email { font-size: 12px; color: var(--text-secondary); }
    .mhs-nim   { font-size: 12px; color: var(--text-secondary); font-family: monospace; }

    /* BADGE PRODI */
    .badge-prodi {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(16,185,129,0.1);
        color: #065f46;
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 600;
    }

    /* NOTICE */
    .info-notice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: rgba(59,130,246,0.07);
        border: 1px solid rgba(59,130,246,0.2);
        border-radius: 8px;
        font-size: 13px;
        color: #1e40af;
        margin-bottom: 16px;
    }
    .info-notice i { font-size: 16px; flex-shrink: 0; }

    /* EMPTY STATE */
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
        max-width: 480px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        animation: modalIn 0.25s ease;
        overflow: hidden;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid var(--gray-border);
    }
    .modal-header h3 { font-size: 17px; font-weight: 700; margin: 0; color: var(--text-primary); }
    .modal-close {
        background: none; border: none; font-size: 20px;
        color: var(--text-secondary); cursor: pointer; padding: 4px;
    }
    .modal-close:hover { color: var(--text-primary); }
    .modal-body  { padding: 24px; }
    .modal-footer {
        display: flex; justify-content: flex-end; gap: 10px;
        padding: 14px 24px;
        border-top: 1px solid var(--gray-border);
        background: var(--gray-light, #f9fafb);
    }

    .confirm-info { text-align: center; padding: 8px 0; }
    .confirm-info i { font-size: 44px; margin-bottom: 14px; display: block; }
    .confirm-info p { font-size: 14px; color: var(--text-secondary); margin: 0 0 10px; }
    .confirm-name {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .confirm-name.danger { background: rgba(239,68,68,0.1); color: #dc2626; }
    .confirm-note { font-size: 12px; color: var(--text-secondary); }

    /* DETAIL CARD in modal */
    .mhs-detail-card {
        background: var(--gray-light, #f9fafb);
        border: 1px solid var(--gray-border);
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mhs-detail-card .mhs-avatar { border-radius: 50%; }
    .mhs-detail-sub { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }

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

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas fa-user-check" style="color:var(--maroon-main); margin-right:8px;"></i>
                Registrasi Mahasiswa
            </h2>
            <p>
                Mahasiswa yang mendaftar dan menunggu persetujuan prodi
                @if(!$isAllProdi)
                    &mdash; <span style="color:var(--maroon-main); font-weight:600;">sesuai prodi Anda</span>
                @else
                    &mdash; <span style="color:#059669; font-weight:600;">semua program studi</span>
                @endif
            </p>
        </div>
    </div>

    @if(!auth()->user()->hasAccess('validasi.register'))
    <div class="info-notice">
        <i class="fas fa-info-circle"></i>
        Anda hanya dapat melihat data registrasi. Untuk menyetujui atau menolak, diperlukan akses <strong>validasi.register</strong>.
    </div>
    @endif

    {{-- TOOLBAR --}}
    <form method="GET" action="{{ route('registrasi.index') }}" id="filterForm">
        <div class="table-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, NIM, atau email..." oninput="delayFilter()">
            </div>

            @if($isAllProdi)
            <select name="prodi" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Program Studi</option>
                @foreach($prodiList as $prodi)
                    <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->nama }}
                    </option>
                @endforeach
            </select>
            @endif

            <div class="toolbar-count">
                Menunggu persetujuan: <strong>{{ $mahasiswaList->total() }}</strong>
            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="table-container">
        @if($mahasiswaList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:48px">No</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Program Studi</th>
                    <th>Fakultas</th>
                    <th>Tgl. Daftar</th>
                    @if(auth()->user()->hasAccess('validasi.register'))
                    <th style="width:160px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $index => $mhs)
                @php
                    $initials = collect(explode(' ', $mhs->nama))
                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                        ->take(2)
                        ->join('');
                @endphp
                <tr>
                    <td>{{ $mahasiswaList->firstItem() + $index }}</td>
                    <td>
                        <div class="mhs-info">
                            <div class="mhs-avatar">{{ $initials }}</div>
                            <div>
                                <div class="mhs-name">{{ $mhs->nama }}</div>
                                <div class="mhs-email">{{ $mhs->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="mhs-nim">{{ $mhs->nim }}</span>
                    </td>
                    <td>
                        @if($mhs->programStudi)
                            <span class="badge-prodi">{{ $mhs->programStudi->nama }}</span>
                        @else
                            <span style="font-size:12px;color:var(--text-secondary)">—</span>
                        @endif
                    </td>
                    <td style="font-size:13px; color:var(--text-secondary);">
                        {{ $mhs->programStudi?->fakultas?->nama ?? '—' }}
                    </td>
                    <td style="font-size:13px; color:var(--text-secondary);">
                        {{ $mhs->created_at->format('d/m/Y') }}<br>
                        <span style="font-size:11px;">{{ $mhs->created_at->format('H:i') }}</span>
                    </td>
                    @if(auth()->user()->hasAccess('validasi.register'))
                    <td>
                        <div class="action-btns">
                            <button class="btn btn-success btn-sm"
                                onclick="openSetujuiModal(
                                    {{ $mhs->id }},
                                    {{ json_encode($mhs->nama) }},
                                    {{ json_encode($mhs->nim) }},
                                    {{ json_encode($mhs->programStudi?->nama ?? '-') }}
                                )">
                                <i class="fas fa-check"></i> Terima
                            </button>
                            <button class="btn btn-danger btn-sm"
                                onclick="openTolakModal(
                                    {{ $mhs->id }},
                                    {{ json_encode($mhs->nama) }},
                                    {{ json_encode($mhs->nim) }}
                                )">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
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
            <i class="fas fa-user-check"></i>
            <h3>Tidak ada registrasi yang menunggu</h3>
            <p>Semua registrasi mahasiswa telah diproses, atau belum ada mahasiswa yang mendaftar.</p>
        </div>
        @endif
    </div>

</div>

{{-- MODAL SETUJUI --}}
@if(auth()->user()->hasAccess('validasi.register'))
<div class="modal-overlay" id="modal-setujui">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-check" style="color:#10b981; margin-right:8px;"></i>Setujui Registrasi</h3>
            <button class="modal-close" onclick="closeModal('modal-setujui')">&times;</button>
        </div>
        <form id="form-setujui" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mhs-detail-card">
                    <div class="mhs-avatar" id="setujui-avatar" style="border-radius:50%; width:42px; height:42px; font-size:15px;"></div>
                    <div>
                        <div class="mhs-name" id="setujui-nama"></div>
                        <div class="mhs-detail-sub"><i class="fas fa-id-card" style="margin-right:4px;"></i><span id="setujui-nim"></span></div>
                        <div class="mhs-detail-sub"><i class="fas fa-graduation-cap" style="margin-right:4px;"></i><span id="setujui-prodi"></span></div>
                    </div>
                </div>
                <p style="font-size:14px; color:var(--text-secondary); margin:0;">
                    Setelah disetujui, mahasiswa akan naik ke <strong>Level 2 — Disetujui Prodi</strong> dan dapat mengisi form pendaftaran serta mengunggah dokumen persyaratan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-setujui')">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Ya, Setujui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TOLAK --}}
<div class="modal-overlay" id="modal-tolak">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-times" style="color:#ef4444; margin-right:8px;"></i>Tolak Registrasi</h3>
            <button class="modal-close" onclick="closeModal('modal-tolak')">&times;</button>
        </div>
        <form id="form-tolak" method="POST">
            @csrf
            <div class="modal-body">
                <div class="confirm-info">
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444;"></i>
                    <p>Apakah Anda yakin ingin menolak registrasi?</p>
                    <div class="confirm-name danger" id="tolak-nama"></div>
                    <div class="mhs-nim" id="tolak-nim" style="margin-top:6px; font-size:13px;"></div>
                    <p class="confirm-note" style="margin-top:12px;">
                        Data registrasi akan dihapus. Mahasiswa dapat mendaftar ulang jika diperlukan.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tolak')">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
    let filterTimer;
    function delayFilter() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(() => document.getElementById('filterForm').submit(), 600);
    }

    // --- Modal helpers ---
    function openModal(id)  { document.getElementById(id)?.classList.add('active'); }
    function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

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

    // --- Setujui Modal ---
    function openSetujuiModal(id, nama, nim, prodi) {
        const initials = nama.split(' ')
            .map(w => w.charAt(0).toUpperCase())
            .slice(0, 2).join('');

        document.getElementById('setujui-avatar').textContent = initials;
        document.getElementById('setujui-nama').textContent   = nama;
        document.getElementById('setujui-nim').textContent    = nim;
        document.getElementById('setujui-prodi').textContent  = prodi;
        document.getElementById('form-setujui').action = '/registrasi/' + id + '/setujui';

        openModal('modal-setujui');
    }

    // --- Tolak Modal ---
    function openTolakModal(id, nama, nim) {
        document.getElementById('tolak-nama').textContent = nama;
        document.getElementById('tolak-nim').textContent  = 'NIM: ' + nim;
        document.getElementById('form-tolak').action = '/registrasi/' + id + '/tolak';

        openModal('modal-tolak');
    }
</script>
@endsection
