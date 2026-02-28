@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    /* Filter */
    .filter-bar { display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap; align-items:center; }
    .filter-bar select {
        padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px;
        font-size:13px; font-family:inherit; background:#fff; min-width:220px;
    }
    .filter-bar select:focus { outline:none; border-color:var(--maroon-main); }
    .btn-reset {
        padding:8px 14px; border:1px solid var(--gray-border); border-radius:8px;
        font-size:12px; font-weight:600; background:#fff; color:var(--text-secondary);
        cursor:pointer; font-family:inherit; text-decoration:none;
        display:inline-flex; align-items:center; gap:6px;
    }
    .btn-reset:hover { background:var(--gray-light); }

    /* Table card */
    .table-card { background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.07); overflow:hidden; }
    .table-toolbar {
        display:flex; justify-content:space-between; align-items:center;
        padding:14px 20px; border-bottom:1px solid var(--gray-border);
        flex-wrap:wrap; gap:10px;
    }
    .table-info { font-size:13px; color:var(--text-secondary); }
    .table-info strong { color:var(--text-primary); }

    .data-table { width:100%; border-collapse:collapse; }
    .data-table th {
        font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
        color:var(--text-secondary); padding:10px 16px;
        background:var(--gray-light); border-bottom:1px solid var(--gray-border);
        text-align:left; white-space:nowrap;
    }
    .data-table td {
        font-size:13px; padding:11px 16px;
        border-bottom:1px solid rgba(0,0,0,.04);
        color:var(--text-primary); vertical-align:middle;
    }
    .data-table tbody tr:last-child td { border-bottom:none; }
    .data-table tbody tr:hover td { background:rgba(165,42,42,.02); }

    .avatar-sm {
        width:30px; height:30px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg, #7c3aed, #a855f7);
        display:inline-flex; align-items:center; justify-content:center;
        color:white; font-size:11px; font-weight:700;
        margin-right:8px; vertical-align:middle;
    }
    .cell-nama strong { font-size:13px; font-weight:600; color:var(--text-primary); }
    .cell-nama small  { display:block; font-size:11px; color:var(--text-secondary); margin-top:1px; }

    .no-kel {
        display:inline-flex; align-items:center; justify-content:center;
        width:26px; height:26px; border-radius:6px;
        background:linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        color:white; font-size:11px; font-weight:800;
    }

    .badge-dpl {
        display:inline-flex; align-items:center; gap:4px;
        font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px;
        background:rgba(139,92,246,.1); color:#7c3aed;
        border:1px solid rgba(139,92,246,.2);
    }

    /* Pagination */
    .pagination-wrap {
        display:flex; justify-content:space-between; align-items:center;
        padding:14px 20px; border-top:1px solid var(--gray-border);
        flex-wrap:wrap; gap:10px;
    }
    .pagination-info { font-size:13px; color:var(--text-secondary); }
    .pagination-btns { display:flex; gap:4px; }
    .page-btn {
        display:inline-flex; align-items:center; justify-content:center;
        min-width:34px; height:34px; padding:0 10px;
        border:1px solid var(--gray-border); border-radius:7px;
        font-size:13px; font-weight:600; color:var(--text-primary);
        background:white; text-decoration:none; cursor:pointer;
        transition:all .2s;
    }
    .page-btn:hover:not(.disabled):not(.active) { border-color:var(--maroon-main); color:var(--maroon-main); }
    .page-btn.active { background:var(--maroon-main); border-color:var(--maroon-main); color:white; }
    .page-btn.disabled { opacity:.4; cursor:default; pointer-events:none; }

    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-chalkboard-teacher" style="color:var(--maroon-main);margin-right:8px;"></i>Dosen Pembimbing Lapangan</h2>
            <p>Daftar DPL yang telah ditugaskan pada kelompok KKA</p>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('dpl.index') }}">
        <div class="filter-bar">
            <select name="kegiatan_id" onchange="this.form.submit()">
                <option value="">-- Semua Kegiatan --</option>
                @foreach($kegiatanList as $k)
                    <option value="{{ $k->id }}" {{ $kegiatanId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>

            @if($kegiatanId)
                <a href="{{ route('dpl.index') }}" class="btn-reset">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </div>
    </form>

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="table-toolbar">
            <div class="table-info">
                Total <strong>{{ $dpl->total() }}</strong> penugasan DPL
                @if($kegiatanId)
                    <span style="color:var(--maroon-main);">(terfilter)</span>
                @endif
            </div>
        </div>

        @if($dpl->isNotEmpty())
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama DPL</th>
                        <th>NIP</th>
                        <th>Kontak</th>
                        <th style="width:100px; text-align:center;">Kelompok</th>
                        <th>Lokasi</th>
                        <th>Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dpl as $d)
                    <tr>
                        <td style="color:var(--text-secondary); font-size:12px;">
                            {{ $dpl->firstItem() + $loop->index }}
                        </td>
                        <td>
                            <span class="avatar-sm">{{ strtoupper(substr($d->nama, 0, 1)) }}</span>
                            <span class="cell-nama" style="display:inline-block; vertical-align:middle;">
                                <strong>{{ $d->nama }}</strong>
                                <span class="badge-dpl"><i class="fas fa-chalkboard-teacher" style="font-size:9px;"></i> DPL</span>
                            </span>
                        </td>
                        <td style="font-size:12px; color:var(--text-secondary);">{{ $d->nip ?? '-' }}</td>
                        <td style="font-size:12px; color:var(--text-secondary);">
                            @if($d->no_hp)
                                <div><i class="fas fa-phone" style="width:14px;"></i> {{ $d->no_hp }}</div>
                            @endif
                            @if($d->email)
                                <div><i class="fas fa-envelope" style="width:14px;"></i> {{ $d->email }}</div>
                            @endif
                            @if(!$d->no_hp && !$d->email) - @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="no-kel">{{ $d->kelompok }}</span>
                        </td>
                        <td style="font-size:12px; color:var(--text-secondary);">{{ $d->desa ?? '-' }}</td>
                        <td style="font-size:12px; color:var(--text-secondary);">{{ $d->kegiatan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($dpl->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Halaman {{ $dpl->currentPage() }} dari {{ $dpl->lastPage() }}
                &mdash; {{ $dpl->firstItem() }}–{{ $dpl->lastItem() }} dari {{ $dpl->total() }} data
            </div>
            <div class="pagination-btns">
                {{-- Prev --}}
                @if($dpl->onFirstPage())
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $dpl->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                @endif

                @php
                    $start = max(1, $dpl->currentPage() - 2);
                    $end   = min($dpl->lastPage(), $dpl->currentPage() + 2);
                @endphp
                @if($start > 1)
                    <a href="{{ $dpl->url(1) }}" class="page-btn">1</a>
                    @if($start > 2)<span class="page-btn disabled">…</span>@endif
                @endif
                @for($i = $start; $i <= $end; $i++)
                    <a href="{{ $dpl->url($i) }}" class="page-btn {{ $i == $dpl->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($end < $dpl->lastPage())
                    @if($end < $dpl->lastPage() - 1)<span class="page-btn disabled">…</span>@endif
                    <a href="{{ $dpl->url($dpl->lastPage()) }}" class="page-btn">{{ $dpl->lastPage() }}</a>
                @endif

                {{-- Next --}}
                @if($dpl->hasMorePages())
                    <a href="{{ $dpl->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Belum ada DPL yang ditugaskan</h3>
            <p>
                @if($kegiatanId)
                    Tidak ada DPL yang ditugaskan untuk kegiatan yang dipilih.
                @else
                    Belum ada Dosen Pembimbing Lapangan yang ditugaskan ke kelompok KKA.
                @endif
            </p>
        </div>
        @endif
    </div>

</div>
@endsection
