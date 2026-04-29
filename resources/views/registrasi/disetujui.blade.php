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
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .search-box { position: relative; flex: 1; max-width: 300px; }
    .search-box input {
        width: 100%; padding: 9px 14px 9px 36px;
        border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit;
    }
    .search-box input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 13px; }
    .filter-select {
        padding: 9px 14px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit; background: white; min-width: 180px;
    }
    .filter-select:focus { outline: none; border-color: var(--maroon-main); }
    .toolbar-count { margin-left: auto; font-size: 13px; color: var(--text-secondary); }

    .mhs-info { display: flex; align-items: center; gap: 10px; }
    .mhs-avatar {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, #059669, #10b981);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 13px; flex-shrink: 0;
    }
    .mhs-name  { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .mhs-email { font-size: 12px; color: var(--text-secondary); }
    .mhs-nim   { font-size: 12px; color: var(--text-secondary); font-family: monospace; }

    .badge-prodi {
        display: inline-flex; gap: 4px;
        background: rgba(16,185,129,.1); color: #065f46;
        border-radius: 20px; padding: 2px 10px; font-size: 11px; font-weight: 600;
    }

    .badge-form {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600;
    }
    .badge-form.draft    { background: rgba(245,158,11,.1); color: #92400e; border: 1px solid rgba(245,158,11,.2); }
    .badge-form.belum    { background: rgba(156,163,175,.1); color: #6b7280; border: 1px solid rgba(156,163,175,.2); }
    .badge-form.submitted{ background: rgba(59,130,246,.1);  color: #1d4ed8; border: 1px solid rgba(59,130,246,.2); }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i  { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    .btn-edit { background:rgba(59,130,246,.1); color:#1d4ed8; border:1px solid rgba(59,130,246,.2); border-radius:6px; padding:5px 10px; cursor:pointer; font-size:12px; font-family:inherit; font-weight:600; display:inline-flex; align-items:center; gap:4px; }
    .btn-edit:hover { background:rgba(59,130,246,.2); }

    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:480px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .25s ease; overflow:hidden; max-height:90vh; overflow-y:auto; }
    @keyframes modalIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:18px 24px; border-bottom:1px solid var(--gray-border); position:sticky; top:0; background:#fff; z-index:1; }
    .modal-header h3 { font-size:17px; font-weight:700; margin:0; color:var(--text-primary); }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-close:hover { color:var(--text-primary); }
    .modal-body  { padding:24px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:14px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light,#f9fafb); }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group input, .form-group select { width:100%; padding:9px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .form-group input:focus, .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .form-divider { border:none; border-top:1px dashed var(--gray-border); margin:16px 0; }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; }
    .btn-primary { background:var(--maroon-main); color:#fff; }
    .btn-primary:hover { background:var(--maroon-dark,#7f1d1d); }
    .btn-secondary-modal { background:var(--gray-border,#e5e7eb); color:var(--text-primary); }
    .btn-secondary-modal:hover { background:#d1d5db; }
    .mhs-detail-card { background:var(--gray-light,#f9fafb); border:1px solid var(--gray-border); border-radius:10px; padding:12px 16px; margin-bottom:16px; display:flex; align-items:center; gap:12px; }
    .mhs-detail-card .mhs-avatar { border-radius:50%; width:40px; height:40px; flex-shrink:0; }
    .detail-sub { font-size:12px; color:var(--text-secondary); margin-top:2px; }

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
                <i class="fas fa-check-circle" style="color:#10b981; margin-right:8px;"></i>
                Disetujui Prodi
            </h2>
            <p>
                Mahasiswa yang telah disetujui prodi dan sedang mengisi form pendaftaran
                @if(!$isAllProdi)
                    &mdash; <span style="color:var(--maroon-main); font-weight:600;">sesuai prodi Anda</span>
                @else
                    &mdash; <span style="color:#059669; font-weight:600;">semua program studi</span>
                @endif
            </p>
        </div>
    </div>

    <form method="GET" action="{{ route('registrasi.disetujui') }}" id="filterForm">
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
            <select name="status" class="filter-select" onchange="document.getElementById('filterForm').submit()" style="min-width:160px;">
                <option value="">Semua Status Form</option>
                <option value="belum"     {{ request('status') === 'belum'     ? 'selected' : '' }}>Belum Diisi</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Sudah Dikirim</option>
            </select>
            <div class="toolbar-count">Total: <strong>{{ $mahasiswaList->total() }}</strong></div>
        </div>
    </form>

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
                    <th>Tgl. Disetujui</th>
                    <th>Status Form</th>
                    @if(auth()->user()->hasAccess('validasi.register') || auth()->user()->hasAccess('edit.mahasiswa-admin'))
                    <th style="width:130px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $index => $mhs)
                @php
                    $initials = collect(explode(' ', $mhs->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
                    $p = $mhs->pendaftaran;
                    if (!$p) {
                        $formStatus = 'belum';
                        $formLabel  = 'Belum Diisi';
                        $formIcon   = 'fa-circle-xmark';
                    } elseif ($p->isSubmitted()) {
                        $formStatus = 'submitted';
                        $formLabel  = 'Sudah Dikirim';
                        $formIcon   = 'fa-circle-check';
                    } else {
                        $formStatus = 'draft';
                        $formLabel  = 'Draft';
                        $formIcon   = 'fa-pen-to-square';
                    }
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
                    <td><span class="mhs-nim">{{ $mhs->nim }}</span></td>
                    <td>
                        @if($mhs->programStudi)
                            <span class="badge-prodi">{{ $mhs->programStudi->nama }}</span>
                        @else —
                        @endif
                    </td>
                    <td style="font-size:13px; color:var(--text-secondary);">
                        {{ $mhs->programStudi?->fakultas?->nama ?? '—' }}
                    </td>
                    <td style="font-size:13px; color:var(--text-secondary);">
                        {{ $mhs->updated_at->format('d/m/Y') }}<br>
                        <span style="font-size:11px;">{{ $mhs->updated_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <span class="badge-form {{ $formStatus }}">
                            <i class="fas {{ $formIcon }}"></i>
                            {{ $formLabel }}
                        </span>
                    </td>
                    @if(auth()->user()->hasAccess('validasi.register') || auth()->user()->hasAccess('edit.mahasiswa-admin'))
                    <td style="white-space:nowrap;">
                        @if(auth()->user()->hasAccess('edit.mahasiswa-admin'))
                        <button class="btn-edit" onclick="openEditModal(
                            {{ $mhs->id }},
                            {{ json_encode($mhs->nim) }},
                            {{ json_encode($mhs->nama) }},
                            {{ json_encode($mhs->email) }},
                            {{ $mhs->program_studi_id ?? 'null' }},
                            {{ $mhs->pendaftaran?->kegiatan_id ?? 'null' }}
                        )" style="margin-bottom:4px;">
                            <i class="fas fa-pen"></i> Edit
                        </button>
                        @endif
                        @if(auth()->user()->hasAccess('validasi.register'))
                        <form method="POST" action="{{ route('registrasi.kembalikan', $mhs) }}"
                              onsubmit="return confirm('Batalkan persetujuan {{ addslashes($mhs->nama) }}?')">
                            @csrf
                            <button type="submit" title="Batalkan Persetujuan"
                                    style="background:rgba(239,68,68,.1); color:#dc2626; border:1px solid rgba(239,68,68,.2); border-radius:6px; padding:5px 10px; cursor:pointer; font-size:12px; font-family:inherit; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                <i class="fas fa-rotate-left"></i> Batal
                            </button>
                        </form>
                        @endif
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
            <i class="fas fa-check-circle"></i>
            <h3>Belum ada mahasiswa yang disetujui</h3>
            <p>Mahasiswa yang disetujui prodi akan muncul di sini untuk mengisi form pendaftaran.</p>
        </div>
        @endif
    </div>

</div>
@endsection

{{-- MODAL EDIT MAHASISWA --}}
@if(auth()->user()->hasAccess('edit.mahasiswa-admin'))
<div class="modal-overlay" id="modal-edit-mhs">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit" style="color:var(--maroon-main);margin-right:8px;"></i>Edit Data Mahasiswa</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="form-edit-mhs" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="nim"                id="em-nim">
            <input type="hidden" name="nama"               id="em-nama">
            <input type="hidden" name="mahasiswa_level_id" value="2">
            <div class="modal-body">
                <div id="em-mhs-card" class="mhs-detail-card">
                    <div class="mhs-avatar" id="em-avatar" style="background:linear-gradient(135deg,#059669,#10b981); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:14px;"></div>
                    <div>
                        <div style="font-size:14px; font-weight:600;" id="em-nama-label"></div>
                        <div class="detail-sub" id="em-nim-label"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="em-email" required>
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <select name="program_studi_id" id="em-prodi" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodiList as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kegiatan / Jenis KKA <span style="font-weight:400;color:var(--text-secondary)">(form pendaftaran)</span></label>
                    <select name="kegiatan_id" id="em-kegiatan">
                        <option value="">-- Tidak diubah --</option>
                        @foreach($kegiatanList as $kg)
                            <option value="{{ $kg->id }}">{{ $kg->nama }}</option>
                        @endforeach
                    </select>
                    <div class="form-hint" id="em-kegiatan-hint"></div>
                </div>

                <hr class="form-divider">

                <div class="form-group">
                    <label>Password Baru <span style="font-weight:400;color:var(--text-secondary)">(opsional, min. 8 karakter)</span></label>
                    <input type="password" name="password" id="em-password" placeholder="Kosongkan jika tidak diubah" minlength="8">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-modal" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
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

    function openEditModal(id, nim, nama, email, prodiId, kegiatanId) {
        const initials = nama.split(' ').map(w => w.charAt(0).toUpperCase()).slice(0,2).join('');
        document.getElementById('em-avatar').textContent     = initials;
        document.getElementById('em-nama-label').textContent = nama;
        document.getElementById('em-nim-label').textContent  = 'NIM: ' + nim;
        document.getElementById('em-nim').value   = nim;
        document.getElementById('em-nama').value  = nama;
        document.getElementById('em-email').value = email;
        document.getElementById('em-prodi').value = prodiId ?? '';

        const kegEl = document.getElementById('em-kegiatan');
        const hint  = document.getElementById('em-kegiatan-hint');
        kegEl.value = kegiatanId ?? '';
        hint.textContent = kegiatanId
            ? 'Kegiatan saat ini ditampilkan. Ubah jika perlu.'
            : 'Mahasiswa belum memiliki form pendaftaran. Perubahan kegiatan tidak berpengaruh.';

        document.getElementById('em-password').value = '';
        document.getElementById('form-edit-mhs').action = '/mahasiswa/' + id + '/update-data';
        document.getElementById('modal-edit-mhs').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('modal-edit-mhs')?.classList.remove('active');
    }

    document.getElementById('modal-edit-mhs')?.addEventListener('click', e => {
        if (e.target === document.getElementById('modal-edit-mhs')) closeEditModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endsection
