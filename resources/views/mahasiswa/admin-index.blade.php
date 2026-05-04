@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .table-toolbar { display:flex; align-items:center; gap:12px; margin-bottom:12px; flex-wrap:wrap; }
    .search-box { position:relative; flex:1; max-width:280px; }
    .search-box input { width:100%; padding:9px 14px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }
    .filter-select { padding:9px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:white; min-width:160px; }
    .filter-select:focus { outline:none; border-color:var(--maroon-main); }
    .toolbar-count { margin-left:auto; font-size:13px; color:var(--text-secondary); }

    .mhs-info { display:flex; align-items:center; gap:10px; }
    .mhs-avatar { width:36px; height:36px; border-radius:8px; background:linear-gradient(135deg,var(--maroon-main),var(--maroon-light)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:13px; flex-shrink:0; }
    .mhs-name  { font-size:14px; font-weight:600; color:var(--text-primary); }
    .mhs-email { font-size:12px; color:var(--text-secondary); }
    .mhs-nim   { font-size:12px; color:var(--text-secondary); font-family:monospace; }

    .badge-level { display:inline-flex; align-items:center; gap:4px; border-radius:20px; padding:2px 10px; font-size:11px; font-weight:600; }
    .badge-level-1  { background:rgba(156,163,175,.15); color:#374151; }
    .badge-level-2  { background:rgba(16,185,129,.1);   color:#065f46; }
    .badge-level-3  { background:rgba(59,130,246,.1);   color:#1d4ed8; }
    .badge-level-4  { background:rgba(245,158,11,.1);   color:#92400e; }
    .badge-level-5  { background:rgba(139,92,246,.1);   color:#5b21b6; }
    .badge-level-6  { background:rgba(236,72,153,.1);   color:#9d174d; }
    .badge-level-7  { background:rgba(34,197,94,.1);    color:#166534; }

    /* Action buttons */
    .action-cell { display:flex; gap:5px; align-items:center; flex-wrap:nowrap; }
    .btn-edit {
        background:rgba(59,130,246,.1); color:#1d4ed8; border:1px solid rgba(59,130,246,.2);
        border-radius:6px; padding:5px 10px; cursor:pointer; font-size:12px;
        font-family:inherit; font-weight:600; display:inline-flex; align-items:center; gap:4px;
    }
    .btn-edit:hover { background:rgba(59,130,246,.2); }
    .btn-hapus {
        background:rgba(239,68,68,.08); color:#dc2626; border:1px solid rgba(239,68,68,.2);
        border-radius:6px; padding:5px 10px; cursor:pointer; font-size:12px;
        font-family:inherit; font-weight:600; display:inline-flex; align-items:center; gap:4px;
    }
    .btn-hapus:hover { background:#ef4444; color:white; border-color:#ef4444; }

    /* Modal edit */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .25s ease; overflow:hidden; max-height:90vh; overflow-y:auto; }
    @keyframes modalIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:18px 24px; border-bottom:1px solid var(--gray-border); position:sticky; top:0; background:#fff; z-index:1; }
    .modal-header h3 { font-size:17px; font-weight:700; margin:0; color:var(--text-primary); }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-close:hover { color:var(--text-primary); }
    .modal-body  { padding:24px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:14px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light,#f9fafb); position:sticky; bottom:0; }

    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group input, .form-group select { width:100%; padding:9px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .form-group input:focus, .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .form-divider { border:none; border-top:1px dashed var(--gray-border); margin:16px 0; }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; }
    .btn-primary   { background:var(--maroon-main); color:#fff; }
    .btn-primary:hover { background:var(--maroon-dark,#7f1d1d); }
    .btn-secondary { background:var(--gray-border,#e5e7eb); color:var(--text-primary); }
    .btn-secondary:hover { background:#d1d5db; }
    .btn-danger    { background:#ef4444; color:#fff; }
    .btn-danger:hover { background:#dc2626; }
    .btn-danger:disabled { background:#fca5a5; cursor:not-allowed; }

    /* Modal Hapus */
    .modal-hapus { max-width:480px; }
    .modal-header-danger { background:linear-gradient(135deg,#dc2626,#b91c1c); }
    .modal-header-danger h3 { color:#fff; }
    .modal-header-danger .modal-close { color:rgba(255,255,255,.8); }
    .modal-header-danger .modal-close:hover { color:#fff; }

    .danger-icon-wrap { text-align:center; margin-bottom:16px; }
    .danger-icon-wrap i { font-size:56px; color:#dc2626; }

    .danger-identity {
        background:rgba(239,68,68,.07); border:1.5px solid rgba(239,68,68,.25);
        border-radius:10px; padding:12px 16px; margin:12px 0; text-align:center;
    }
    .danger-identity .d-nama { font-size:15px; font-weight:700; color:#dc2626; }
    .danger-identity .d-nim  { font-size:12px; color:var(--text-secondary); font-family:monospace; margin-top:2px; }

    .danger-warning-box {
        background:#fef2f2; border:1px solid #fecaca; border-radius:8px;
        padding:12px 14px; margin:12px 0; font-size:12px; color:#7f1d1d;
    }
    .danger-warning-box ul { margin:6px 0 0 16px; }
    .danger-warning-box ul li { margin-bottom:2px; }
    .danger-warning-box strong { color:#dc2626; }

    .confirm-input-wrap { margin-top:14px; }
    .confirm-input-wrap label { font-size:12px; color:var(--text-secondary); display:block; margin-bottom:6px; }
    .confirm-input-wrap input {
        width:100%; padding:9px 12px; border:1.5px solid var(--gray-border);
        border-radius:8px; font-size:13px; font-family:monospace; box-sizing:border-box;
        transition:border-color .2s;
    }
    .confirm-input-wrap input:focus { outline:none; border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,.15); }
    .confirm-input-wrap input.match { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.15); }

    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }

    @media (max-width:640px) {
        .table-toolbar { flex-direction:column; align-items:stretch; }
        .search-box { max-width:100%; }
        .filter-select { width:100%; }
        .toolbar-count { margin-left:0; }
        .form-row { grid-template-columns:1fr; }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-users" style="color:var(--maroon-main);margin-right:8px;"></i>Data Mahasiswa</h2>
            <p>Kelola data mahasiswa — edit informasi, prodi, level, dan password</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08); border:1px solid rgba(16,185,129,.25); border-radius:10px; padding:12px 16px; margin-bottom:16px; color:#065f46; font-size:13px; display:flex; gap:10px; align-items:center;">
        <i class="fas fa-circle-check"></i> {!! session('success') !!}
    </div>
    @endif

    <form method="GET" action="{{ route('mahasiswa.admin.index') }}" id="filterForm">
        <div class="table-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, email..." oninput="delayFilter()">
            </div>
            <select name="level" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Level</option>
                @foreach($levels as $lv)
                    <option value="{{ $lv->id }}" {{ request('level') == $lv->id ? 'selected' : '' }}>{{ $lv->nama }}</option>
                @endforeach
            </select>
            <select name="prodi" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Prodi</option>
                @foreach($prodiList as $pr)
                    <option value="{{ $pr->id }}" {{ request('prodi') == $pr->id ? 'selected' : '' }}>{{ $pr->nama }}</option>
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
                    <th style="width:48px">No</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Program Studi</th>
                    <th>Level</th>
                    @if(auth()->user()->hasAccess('edit.mahasiswa-admin'))
                    <th style="width:120px">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $index => $mhs)
                @php
                    $initials   = collect(explode(' ', $mhs->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
                    $levelClass = 'badge-level-'.($mhs->mahasiswa_level_id ?? 1);
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
                    <td style="font-size:13px;">{{ $mhs->programStudi?->nama ?? '—' }}</td>
                    <td>
                        <span class="badge-level {{ $levelClass }}">{{ $mhs->level?->nama ?? '—' }}</span>
                    </td>
                    @if(auth()->user()->hasAccess('edit.mahasiswa-admin'))
                    <td>
                        <div class="action-cell">
                            <button class="btn-edit" onclick="openEditModal(
                                {{ $mhs->id }},
                                {{ json_encode($mhs->nim) }},
                                {{ json_encode($mhs->nama) }},
                                {{ json_encode($mhs->email) }},
                                {{ $mhs->program_studi_id ?? 'null' }},
                                {{ $mhs->mahasiswa_level_id ?? 'null' }},
                                {{ $mhs->pendaftaran?->kegiatan_id ?? 'null' }}
                            )">
                                <i class="fas fa-pen"></i> Edit
                            </button>
                            <button class="btn-hapus" onclick="openHapusModal(
                                {{ $mhs->id }},
                                {{ json_encode($mhs->nama) }},
                                {{ json_encode($mhs->nim) }}
                            )">
                                <i class="fas fa-trash"></i> Hapus
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
            <i class="fas fa-users"></i>
            <h3>Tidak ada data mahasiswa</h3>
            <p>Coba ubah filter atau kata kunci pencarian.</p>
        </div>
        @endif
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL EDIT                                                 --}}
{{-- ======================================================== --}}
@if(auth()->user()->hasAccess('edit.mahasiswa-admin'))
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit" style="color:var(--maroon-main);margin-right:8px;"></i>Edit Data Mahasiswa</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" id="edit-nim" required maxlength="20">
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" id="edit-nama" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit-email" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select name="program_studi_id" id="edit-prodi" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodiList as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <select name="mahasiswa_level_id" id="edit-level" required>
                            @foreach($levels as $lv)
                                <option value="{{ $lv->id }}">{{ $lv->id }}. {{ $lv->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Kegiatan / Jenis KKA <span style="font-weight:400;color:var(--text-secondary)">(form pendaftaran)</span></label>
                    <select name="kegiatan_id" id="edit-kegiatan">
                        <option value="">-- Tidak diubah --</option>
                        @foreach($kegiatanList as $kg)
                            <option value="{{ $kg->id }}">{{ $kg->nama }}</option>
                        @endforeach
                    </select>
                    <div class="form-hint" id="kegiatan-hint"></div>
                </div>
                <hr class="form-divider">
                <div class="form-row">
                    <div class="form-group">
                        <label>Password Baru <span style="font-weight:400;color:var(--text-secondary)">(opsional)</span></label>
                        <input type="password" name="password" id="edit-password" placeholder="Kosongkan jika tidak diubah" minlength="8">
                    </div>
                    <div class="form-group" style="display:flex;flex-direction:column;justify-content:flex-end;">
                        <div class="form-hint" style="margin-bottom:8px;">Min. 8 karakter</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL HAPUS — BERBAHAYA                                   --}}
{{-- ======================================================== --}}
<div class="modal-overlay" id="modal-hapus">
    <div class="modal modal-hapus">
        <div class="modal-header modal-header-danger">
            <h3><i class="fas fa-triangle-exclamation" style="margin-right:8px;"></i>Hapus Data Mahasiswa</h3>
            <button class="modal-close" onclick="closeHapusModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="danger-icon-wrap">
                <i class="fas fa-skull-crossbones"></i>
            </div>

            <div class="danger-identity">
                <div class="d-nama" id="hapus-nama"></div>
                <div class="d-nim"  id="hapus-nim"></div>
            </div>

            <div class="danger-warning-box">
                <strong>⚠ Data yang akan dihapus secara permanen:</strong>
                <ul>
                    <li>Akun & profil mahasiswa</li>
                    <li>Form pendaftaran KKA</li>
                    <li>Semua dokumen yang telah diunggah</li>
                    <li>Riwayat notifikasi</li>
                </ul>
                <strong>Tindakan ini TIDAK DAPAT dibatalkan!</strong>
            </div>

            <div class="confirm-input-wrap">
                <label>Ketik <strong id="hapus-nim-label" style="color:#dc2626; font-family:monospace;"></strong> untuk mengaktifkan tombol hapus:</label>
                <input type="text" id="hapus-confirm-input"
                       placeholder="Ketik NIM di sini..."
                       oninput="checkHapusConfirm()"
                       autocomplete="off">
            </div>
        </div>
        <div class="modal-footer" style="background:#fef2f2; border-top:1px solid #fecaca;">
            <button type="button" class="btn btn-secondary" onclick="closeHapusModal()">
                <i class="fas fa-arrow-left"></i> Batal
            </button>
            <form id="form-hapus" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" id="btn-hapus-konfirmasi" class="btn btn-danger" disabled>
                    <i class="fas fa-trash"></i> Ya, Hapus Selamanya
                </button>
            </form>
        </div>
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

    /* ── Modal Edit ── */
    function openEditModal(id, nim, nama, email, prodiId, levelId, kegiatanId) {
        document.getElementById('edit-nim').value    = nim;
        document.getElementById('edit-nama').value   = nama;
        document.getElementById('edit-email').value  = email;
        document.getElementById('edit-prodi').value  = prodiId ?? '';
        document.getElementById('edit-level').value  = levelId ?? '';

        const kegEl = document.getElementById('edit-kegiatan');
        const hint  = document.getElementById('kegiatan-hint');
        kegEl.value = kegiatanId ?? '';
        hint.textContent = kegiatanId
            ? 'Mahasiswa sudah memiliki form pendaftaran.'
            : 'Mahasiswa belum memiliki form pendaftaran. Perubahan kegiatan tidak berpengaruh.';

        document.getElementById('edit-password').value = '';
        document.getElementById('form-edit').action = '/mahasiswa/' + id + '/update-data';
        document.getElementById('modal-edit').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('modal-edit').classList.remove('active');
    }

    /* ── Modal Hapus ── */
    let _hapusNim = '';

    function openHapusModal(id, nama, nim) {
        _hapusNim = nim;
        document.getElementById('hapus-nama').textContent        = nama;
        document.getElementById('hapus-nim').textContent         = 'NIM: ' + nim;
        document.getElementById('hapus-nim-label').textContent   = nim;
        document.getElementById('hapus-confirm-input').value     = '';
        document.getElementById('hapus-confirm-input').classList.remove('match');
        document.getElementById('btn-hapus-konfirmasi').disabled = true;
        document.getElementById('form-hapus').action = '/mahasiswa/' + id + '/hapus';
        document.getElementById('modal-hapus').classList.add('active');
        setTimeout(() => document.getElementById('hapus-confirm-input').focus(), 200);
    }

    function closeHapusModal() {
        document.getElementById('modal-hapus').classList.remove('active');
    }

    function checkHapusConfirm() {
        const input = document.getElementById('hapus-confirm-input');
        const match = input.value.trim() === _hapusNim;
        input.classList.toggle('match', match);
        document.getElementById('btn-hapus-konfirmasi').disabled = !match;
    }

    /* ── Tutup modal saat klik overlay atau tekan Escape ── */
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
</script>
@endsection
