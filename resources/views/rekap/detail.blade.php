@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:20px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .breadcrumb { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--text-secondary); margin-bottom:18px; }
    .breadcrumb a { color:var(--maroon-main); text-decoration:none; font-weight:600; }
    .breadcrumb a:hover { text-decoration:underline; }

    .kegiatan-info { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; flex-wrap:wrap; gap:20px; align-items:center; }
    .ki-item .ki-label { font-size:11px; color:var(--text-secondary); margin-bottom:2px; }
    .ki-item .ki-val   { font-size:14px; font-weight:700; color:var(--text-primary); }
    .ki-item .ki-val.accent { color:var(--maroon-main); }

    .stat-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px; margin-bottom:20px; }
    .stat-card { background:#fff; border:1px solid var(--gray-border); border-radius:10px; padding:14px 16px; text-align:center; }
    .stat-card .stat-num   { font-size:26px; font-weight:800; line-height:1; }
    .stat-card .stat-label { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .stat-card.c-total  .stat-num { color:var(--maroon-main); }
    .stat-card.c-submit .stat-num { color:#10b981; }
    .stat-card.c-draft  .stat-num { color:#f59e0b; }
    .stat-card.c-belum  .stat-num { color:#9ca3af; }

    .filter-bar { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; align-items:center; }
    .filter-bar select { padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .filter-bar select:focus { outline:none; border-color:var(--maroon-main); }
    .search-box { position:relative; flex:1; max-width:300px; }
    .search-box input { width:100%; padding:9px 13px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }

    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; gap:10px; flex-wrap:wrap; }
    .table-info { font-size:13px; color:var(--text-secondary); }

    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .badge-submit { background:rgba(16,185,129,.12); color:#059669; }
    .badge-draft  { background:rgba(245,158,11,.12); color:#d97706; }
    .badge-belum  { background:#f3f4f6; color:#9ca3af; }

    .mahasiswa-block strong { font-size:13px; font-weight:700; color:var(--text-primary); display:block; }
    .mahasiswa-block span   { font-size:12px; color:var(--text-secondary); }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-sm { padding:5px 10px; font-size:11px; }
    .btn-profil   { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-profil:hover { box-shadow:0 3px 10px rgba(165,42,42,.35); transform:translateY(-1px); color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); }
    .btn-secondary:hover { background:#d1d5db; }
    .btn-export { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; }
    .btn-export:hover { box-shadow:0 3px 10px rgba(22,163,74,.35); transform:translateY(-1px); color:#fff; }

    .no-kelompok { font-size:11px; color:#9ca3af; }

    .empty-state { text-align:center; padding:50px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:40px; color:var(--gray-border); margin-bottom:12px; display:block; }
    .empty-state h3 { font-size:15px; color:var(--text-primary); margin-bottom:6px; }

    /* ── Modal Export ── */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; padding:16px; }
    .modal-overlay.active { display:flex; }
    .modal-box { background:#fff; border-radius:14px; width:100%; max-width:520px; box-shadow:0 20px 60px rgba(0,0,0,.25); overflow:hidden; }
    .modal-header { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; padding:18px 22px; display:flex; align-items:center; justify-content:space-between; }
    .modal-header h3 { font-size:16px; font-weight:700; margin:0; }
    .modal-close { background:none; border:none; color:rgba(255,255,255,.8); font-size:20px; cursor:pointer; line-height:1; padding:0 4px; }
    .modal-close:hover { color:#fff; }
    .modal-body { padding:20px 22px; }
    .modal-body p { font-size:13px; color:var(--text-secondary); margin:0 0 16px; }

    .col-list { display:grid; grid-template-columns:1fr 1fr; gap:8px 20px; margin-bottom:16px; }
    .col-item { display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; color:var(--text-primary); user-select:none; }
    .col-item input[type=checkbox] { width:16px; height:16px; accent-color:var(--maroon-main); cursor:pointer; flex-shrink:0; }
    .col-item:hover { color:var(--maroon-main); }

    .col-actions { display:flex; gap:8px; margin-bottom:16px; }
    .col-actions button { background:none; border:1px solid var(--gray-border); border-radius:6px; padding:4px 10px; font-size:12px; font-weight:600; cursor:pointer; color:var(--text-secondary); transition:all .2s; font-family:inherit; }
    .col-actions button:hover { border-color:var(--maroon-main); color:var(--maroon-main); }

    .filter-notice { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 14px; font-size:12px; color:#166534; display:flex; align-items:center; gap:8px; }
    .filter-notice i { flex-shrink:0; }

    .modal-footer { padding:14px 22px; border-top:1px solid var(--gray-border); display:flex; justify-content:flex-end; gap:10px; background:#fafafa; }

    .btn-export-submit { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:7px; font-family:inherit; transition:all .2s; }
    .btn-export-submit:hover { box-shadow:0 4px 14px rgba(22,163,74,.4); transform:translateY(-1px); }
    .btn-cancel-modal { background:var(--gray-border); color:var(--text-primary); border:none; border-radius:8px; padding:9px 16px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; }
    .btn-cancel-modal:hover { background:#d1d5db; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('rekap.pendaftaran') }}"><i class="fas fa-chart-bar"></i> Rekap Pendaftaran</a>
        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
        <span>Detail</span>
    </div>

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-users" style="color:var(--maroon-main);margin-right:8px;"></i>{{ $kegiatan->nama }}</h2>
            <p>Daftar mahasiswa yang mendaftar pada kegiatan ini</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="button" onclick="openExportModal()" class="btn btn-export">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <a href="{{ route('rekap.pendaftaran') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Info kegiatan --}}
    <div class="kegiatan-info">
        <div class="ki-item">
            <div class="ki-label">Jenis KKA</div>
            <div class="ki-val accent">{{ $kegiatan->jenis_nama ?? '—' }}</div>
        </div>
        <div class="ki-item">
            <div class="ki-label">Periode</div>
            <div class="ki-val">{{ $kegiatan->periode_nama ?? '—' }}</div>
        </div>
        <div class="ki-item">
            <div class="ki-label">Total Pendaftar</div>
            <div class="ki-val accent">{{ $statTotal }} mahasiswa</div>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="stat-row">
        <div class="stat-card c-total">
            <div class="stat-num">{{ $statTotal }}</div>
            <div class="stat-label">Total Pendaftar</div>
        </div>
        <div class="stat-card c-submit">
            <div class="stat-num">{{ $statSubmit }}</div>
            <div class="stat-label">Sudah Submit</div>
        </div>
        <div class="stat-card c-draft">
            <div class="stat-num">{{ $statDraft }}</div>
            <div class="stat-label">Masih Draft</div>
        </div>
        <div class="stat-card c-belum">
            <div class="stat-num">{{ $statBelum }}</div>
            <div class="stat-label">Belum Mengisi</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIM...">
            </div>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Sudah Submit</option>
                <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="belum"     {{ request('status') == 'belum'     ? 'selected' : '' }}>Belum Mengisi</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Cari</button>
            @if(request()->hasAny(['q','status']))
            <a href="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <div class="table-toolbar">
        <div class="table-info">
            Menampilkan <strong>{{ $mahasiswaList->firstItem() ?? 0 }}–{{ $mahasiswaList->lastItem() ?? 0 }}</strong>
            dari <strong>{{ $mahasiswaList->total() }}</strong> mahasiswa
        </div>
    </div>

    <div class="table-container">
        @if($mahasiswaList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:120px;">NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Program Studi</th>
                    <th style="width:100px;">Level</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:110px;text-align:center;">Profil</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswaList as $i => $mhs)
                @php $surveyLokasiId = $kelompokMap->get($mhs->mahasiswa_id); @endphp
                <tr>
                    <td style="color:var(--text-secondary);font-size:13px;">{{ $mahasiswaList->firstItem() + $i }}</td>
                    <td style="font-size:13px;font-weight:600;color:var(--text-secondary);">{{ $mhs->nim }}</td>
                    <td>
                        <div class="mahasiswa-block">
                            <strong>{{ $mhs->nama }}</strong>
                            <span>{{ $mhs->email }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:var(--text-secondary);">{{ $mhs->prodi_nama ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--text-secondary);">{{ $mhs->level_nama ?? '—' }}</td>
                    <td>
                        @if($mhs->pendaftaran_status === 'submitted')
                            <span class="badge badge-submit"><i class="fas fa-check-circle"></i> Submit</span>
                        @elseif($mhs->pendaftaran_status === 'draft')
                            <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> Draft</span>
                        @else
                            <span class="badge badge-belum"><i class="fas fa-minus-circle"></i> Belum Isi</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($surveyLokasiId)
                            <a href="{{ route('mahasiswa.profil', $mhs->mahasiswa_id) }}"
                               class="btn btn-profil btn-sm">
                                <i class="fas fa-id-card"></i> Profil
                            </a>
                        @else
                            <span class="no-kelompok"><i class="fas fa-clock"></i> Belum kelompok</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

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
                @for($pg = $start; $pg <= $end; $pg++)
                    <a href="{{ $mahasiswaList->url($pg) }}" class="page-btn {{ $pg == $mahasiswaList->currentPage() ? 'active' : '' }}">{{ $pg }}</a>
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
            <i class="fas fa-user-slash"></i>
            <h3>Tidak ada data</h3>
            <p>
                @if(request()->hasAny(['q','status']))
                    Tidak ada mahasiswa yang sesuai filter.
                @else
                    Belum ada mahasiswa yang mendaftar pada kegiatan ini.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

{{-- ── Modal Export Excel ── --}}
<div id="modal-export" class="modal-overlay" onclick="if(event.target===this)closeExportModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-file-excel" style="margin-right:8px;"></i>Export ke Excel</h3>
            <button type="button" class="modal-close" onclick="closeExportModal()">&#x2715;</button>
        </div>

        <form id="form-export" method="GET" action="{{ route('rekap.pendaftaran.export', $kegiatan->id) }}">
            {{-- Preserve active filters --}}
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="modal-body">
                <p>Centang kolom yang ingin disertakan dalam file Excel:</p>

                <div class="col-actions">
                    <button type="button" onclick="toggleAllColumns(true)">
                        <i class="fas fa-check-square"></i> Pilih Semua
                    </button>
                    <button type="button" onclick="toggleAllColumns(false)">
                        <i class="far fa-square"></i> Hapus Semua
                    </button>
                </div>

                <div class="col-list" id="col-list">
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="no" checked>
                        <span><i class="fas fa-hashtag" style="width:14px;color:var(--text-secondary);"></i> No</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="nim" checked>
                        <span><i class="fas fa-id-badge" style="width:14px;color:var(--text-secondary);"></i> NIM</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="nama" checked>
                        <span><i class="fas fa-user" style="width:14px;color:var(--text-secondary);"></i> Nama Mahasiswa</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="email" checked>
                        <span><i class="fas fa-envelope" style="width:14px;color:var(--text-secondary);"></i> Email</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="prodi" checked>
                        <span><i class="fas fa-graduation-cap" style="width:14px;color:var(--text-secondary);"></i> Program Studi</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="level" checked>
                        <span><i class="fas fa-layer-group" style="width:14px;color:var(--text-secondary);"></i> Level</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="status_pendaftaran" checked>
                        <span><i class="fas fa-tasks" style="width:14px;color:var(--text-secondary);"></i> Status Pendaftaran</span>
                    </label>
                    <label class="col-item">
                        <input type="checkbox" name="kolom[]" value="kelompok" checked>
                        <span><i class="fas fa-users" style="width:14px;color:var(--text-secondary);"></i> No. Kelompok</span>
                    </label>
                </div>

                @if(request()->hasAny(['q','status']))
                <div class="filter-notice">
                    <i class="fas fa-filter"></i>
                    <span>
                        Filter aktif akan ikut diterapkan pada data yang diekspor
                        @if(request('q'))<strong>(kata kunci: "{{ request('q') }}")</strong>@endif
                        @if(request('status'))<strong>(status: {{ request('status') }})</strong>@endif
                    </span>
                </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeExportModal()">Batal</button>
                <button type="submit" class="btn-export-submit" id="btn-do-export">
                    <i class="fas fa-download"></i> Download Excel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openExportModal() {
    document.getElementById('modal-export').classList.add('active');
}
function closeExportModal() {
    document.getElementById('modal-export').classList.remove('active');
}
function toggleAllColumns(checked) {
    document.querySelectorAll('#col-list input[type=checkbox]').forEach(cb => cb.checked = checked);
    validateExportBtn();
}
function validateExportBtn() {
    const anyChecked = [...document.querySelectorAll('#col-list input[type=checkbox]')].some(cb => cb.checked);
    document.getElementById('btn-do-export').disabled = !anyChecked;
    document.getElementById('btn-do-export').style.opacity = anyChecked ? '1' : '0.5';
}
document.querySelectorAll('#col-list input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', validateExportBtn);
});
</script>
@endsection
