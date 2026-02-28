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
        font-size:13px; font-family:inherit; background:#fff; min-width:200px;
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
        background:linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        display:inline-flex; align-items:center; justify-content:center;
        color:white; font-size:11px; font-weight:700;
        margin-right:8px; vertical-align:middle;
    }
    .cell-nama strong { font-size:13px; font-weight:600; color:var(--text-primary); }
    .cell-nama small  { display:block; font-size:11px; color:var(--text-secondary); margin-top:1px; }

    .badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; white-space:nowrap; }
    .badge-koordinator { background:rgba(165,42,42,.1); color:var(--maroon-main); border:1px solid rgba(165,42,42,.2); }
    .badge-anggota     { background:rgba(107,114,128,.08); color:#6b7280; border:1px solid rgba(107,114,128,.15); }

    .no-kel {
        display:inline-flex; align-items:center; justify-content:center;
        width:26px; height:26px; border-radius:6px;
        background:linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        color:white; font-size:11px; font-weight:800;
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
            <h2><i class="fas fa-user-graduate" style="color:var(--maroon-main);margin-right:8px;"></i>Peserta KKA</h2>
            <p>Daftar mahasiswa yang telah masuk dalam kelompok KKA</p>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('peserta.index') }}" id="filterForm">
        <div class="filter-bar">
            <select name="kegiatan_id" onchange="document.getElementById('selKelompok').value=''; this.form.submit();">
                <option value="">-- Semua Kegiatan --</option>
                @foreach($kegiatanList as $k)
                    <option value="{{ $k->id }}" {{ $kegiatanId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>

            <select name="kelompok" id="selKelompok" onchange="this.form.submit();">
                <option value="">-- Semua Kelompok --</option>
                @foreach($kelompokNumbers as $no)
                    <option value="{{ $no }}" {{ $kelompokNo == $no ? 'selected' : '' }}>Kelompok {{ $no }}</option>
                @endforeach
            </select>

            @if($kegiatanId || $kelompokNo)
                <a href="{{ route('peserta.index') }}" class="btn-reset">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </div>
    </form>

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="table-toolbar">
            <div class="table-info">
                Total <strong>{{ $peserta->total() }}</strong> peserta
                @if($kegiatanId || $kelompokNo)
                    <span style="color:var(--maroon-main);">(terfilter)</span>
                @endif
            </div>
        </div>

        @if($peserta->isNotEmpty())
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th style="width:100px; text-align:center;">Kelompok</th>
                        <th>Lokasi</th>
                        <th style="width:120px;">Status</th>
                        <th>Kegiatan</th>
                        <th style="width:70px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peserta as $p)
                    <tr>
                        <td style="color:var(--text-secondary); font-size:12px;">
                            {{ $peserta->firstItem() + $loop->index }}
                        </td>
                        <td>
                            <span class="avatar-sm">{{ strtoupper(substr($p->nama, 0, 1)) }}</span>
                            <span class="cell-nama" style="display:inline-block; vertical-align:middle;">
                                <strong>{{ $p->nama }}</strong>
                                <small>{{ $p->nim }}</small>
                            </span>
                        </td>
                        <td style="color:var(--text-secondary); font-size:12px;">{{ $p->prodi ?? '-' }}</td>
                        <td style="text-align:center;">
                            <span class="no-kel">{{ $p->kelompok }}</span>
                        </td>
                        <td style="font-size:12px; color:var(--text-secondary);">{{ $p->desa ?? '-' }}</td>
                        <td>
                            @if($p->is_koordinator)
                                <span class="badge badge-koordinator">
                                    <i class="fas fa-crown" style="font-size:9px;"></i> Koordinator
                                </span>
                            @else
                                <span class="badge badge-anggota">Anggota</span>
                            @endif
                        </td>
                        <td style="font-size:12px; color:var(--text-secondary);">{{ $p->kegiatan ?? '-' }}</td>
                        <td style="text-align:center;">
                            <a href="{{ route('mahasiswa.profil', $p->mahasiswa_id) }}?survey_lokasi_id={{ $p->survey_lokasi_id }}"
                               title="Lihat Profil"
                               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;background:rgba(59,130,246,.1);color:#3b82f6;font-size:13px;text-decoration:none;transition:background .15s;"
                               onmouseover="this.style.background='rgba(59,130,246,.2)'" onmouseout="this.style.background='rgba(59,130,246,.1)'">
                                <i class="fas fa-user"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($peserta->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Halaman {{ $peserta->currentPage() }} dari {{ $peserta->lastPage() }}
                &mdash; {{ $peserta->firstItem() }}–{{ $peserta->lastItem() }} dari {{ $peserta->total() }} data
            </div>
            <div class="pagination-btns">
                {{-- Prev --}}
                @if($peserta->onFirstPage())
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $peserta->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                @endif

                {{-- Page numbers --}}
                @php
                    $start = max(1, $peserta->currentPage() - 2);
                    $end   = min($peserta->lastPage(), $peserta->currentPage() + 2);
                @endphp
                @if($start > 1)
                    <a href="{{ $peserta->url(1) }}" class="page-btn">1</a>
                    @if($start > 2)<span class="page-btn disabled">…</span>@endif
                @endif
                @for($i = $start; $i <= $end; $i++)
                    <a href="{{ $peserta->url($i) }}" class="page-btn {{ $i == $peserta->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($end < $peserta->lastPage())
                    @if($end < $peserta->lastPage() - 1)<span class="page-btn disabled">…</span>@endif
                    <a href="{{ $peserta->url($peserta->lastPage()) }}" class="page-btn">{{ $peserta->lastPage() }}</a>
                @endif

                {{-- Next --}}
                @if($peserta->hasMorePages())
                    <a href="{{ $peserta->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-user-slash"></i>
            <h3>Belum ada peserta</h3>
            <p>
                @if($kegiatanId || $kelompokNo)
                    Tidak ada peserta yang sesuai dengan filter yang dipilih.
                @else
                    Belum ada mahasiswa yang dimasukkan ke dalam kelompok KKA.
                @endif
            </p>
        </div>
        @endif
    </div>

</div>
@endsection
