@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    /* Stat cards utama */
    .stat-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:16px 20px; text-align:center; }
    .stat-card .stat-num { font-size:30px; font-weight:800; line-height:1; }
    .stat-card .stat-label { font-size:12px; color:var(--text-secondary); margin-top:5px; }
    .stat-card.c-total .stat-num   { color:var(--maroon-main); }
    .stat-card.c-daftar .stat-num  { color:#3b82f6; }
    .stat-card.c-submit .stat-num  { color:#10b981; }
    .stat-card.c-draft .stat-num   { color:#f59e0b; }
    .stat-card.c-belum .stat-num   { color:#9ca3af; }

    /* Level breakdown */
    .level-row { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:25px; }
    .level-pill { display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--gray-border); border-radius:20px; padding:6px 14px; font-size:13px; }
    .level-pill .lp-count { font-weight:700; color:var(--maroon-main); }
    .level-pill .lp-name  { color:var(--text-secondary); }

    /* Section title */
    .section-title { font-size:14px; font-weight:700; color:var(--text-primary); margin-bottom:10px; display:flex; align-items:center; gap:8px; }
    .section-title i { color:var(--maroon-main); }

    /* Badge status */
    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:11px; font-weight:600; }
    .badge-submit { background:rgba(16,185,129,.12); color:#059669; }
    .badge-draft  { background:rgba(245,158,11,.12); color:#d97706; }
    .badge-belum  { background:#f3f4f6; color:#9ca3af; }

    /* Bar visual */
    .bar-wrap { display:flex; height:6px; border-radius:3px; overflow:hidden; background:#f3f4f6; min-width:80px; }
    .bar-submit { background:#10b981; }
    .bar-draft  { background:#f59e0b; }
    .bar-belum  { background:#d1d5db; }

    /* Progress cell */
    .prog-cell { display:flex; flex-direction:column; gap:4px; }
    .prog-nums { display:flex; gap:6px; font-size:11px; flex-wrap:wrap; }

    .kegiatan-block strong { font-size:13px; font-weight:700; color:var(--text-primary); display:block; }
    .kegiatan-block span   { font-size:12px; color:var(--text-secondary); }

    .total-big { font-size:22px; font-weight:800; color:var(--maroon-main); }

    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-chart-bar" style="color:var(--maroon-main);margin-right:8px;"></i>Rekap Pendaftaran KKA</h2>
            <p>Jumlah mahasiswa yang sedang mendaftar/berproses per jenis kegiatan KKA</p>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="stat-row">
        <div class="stat-card c-total">
            <div class="stat-num">{{ $totalMahasiswa }}</div>
            <div class="stat-label">Total Mahasiswa</div>
        </div>
        <div class="stat-card c-daftar">
            <div class="stat-num">{{ $totalPendaftar }}</div>
            <div class="stat-label">Sudah Mendaftar</div>
        </div>
        <div class="stat-card c-submit">
            <div class="stat-num">{{ $totalSubmit }}</div>
            <div class="stat-label">Sudah Submit</div>
        </div>
        <div class="stat-card c-draft">
            <div class="stat-num">{{ $totalDraft }}</div>
            <div class="stat-label">Masih Draft</div>
        </div>
        <div class="stat-card c-belum">
            <div class="stat-num">{{ $totalBelum }}</div>
            <div class="stat-label">Belum Mendaftar</div>
        </div>
    </div>

    {{-- Level breakdown --}}
    @if($levels->count())
    <div class="section-title"><i class="fas fa-layer-group"></i> Sebaran Level Mahasiswa</div>
    <div class="level-row" style="margin-bottom:25px;">
        @foreach($levels as $lvl)
        <div class="level-pill">
            <span class="lp-count">{{ $lvl->mahasiswa_count }}</span>
            <span class="lp-name">{{ $lvl->nama }}</span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabel per kegiatan --}}
    <div class="section-title"><i class="fas fa-list-alt"></i> Rincian per Kegiatan</div>

    <div class="table-container">
        @if($perKegiatan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Kegiatan KKA</th>
                    <th style="width:130px;">Jenis KKA</th>
                    <th style="width:90px;text-align:center;">Total</th>
                    <th style="width:250px;">Progress Status</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($perKegiatan as $row)
                @php
                    $pct_submit = $row->total > 0 ? ($row->sudah_submit / $row->total * 100) : 0;
                    $pct_draft  = $row->total > 0 ? ($row->draft / $row->total * 100) : 0;
                    $pct_belum  = $row->total > 0 ? ($row->belum_isi / $row->total * 100) : 0;
                @endphp
                <tr>
                    <td style="color:var(--text-secondary);font-size:13px;">{{ $no++ }}</td>
                    <td>
                        <div class="kegiatan-block">
                            <strong>{{ $row->kegiatan_nama }}</strong>
                            <span>ID Kegiatan #{{ $row->kegiatan_id }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:12px;font-weight:600;color:var(--maroon-main);">
                            {{ $row->jenis_nama ?? '—' }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span class="total-big" style="font-size:20px;">{{ $row->total }}</span>
                    </td>
                    <td>
                        <div class="prog-cell">
                            <div class="bar-wrap">
                                <div class="bar-submit" style="width:{{ $pct_submit }}%;"></div>
                                <div class="bar-draft"  style="width:{{ $pct_draft }}%;"></div>
                                <div class="bar-belum"  style="width:{{ $pct_belum }}%;"></div>
                            </div>
                            <div class="prog-nums">
                                <span class="badge badge-submit"><i class="fas fa-check"></i> {{ $row->sudah_submit }} submit</span>
                                <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> {{ $row->draft }} draft</span>
                                <span class="badge badge-belum"><i class="fas fa-minus"></i> {{ $row->belum_isi }} belum isi</span>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#fafafa;font-weight:700;">
                    <td colspan="3" style="text-align:right;font-size:13px;padding-right:12px;">Total Keseluruhan</td>
                    <td style="text-align:center;">
                        <span class="total-big" style="font-size:18px;">{{ $perKegiatan->sum('total') }}</span>
                    </td>
                    <td>
                        <div class="prog-nums">
                            <span class="badge badge-submit"><i class="fas fa-check"></i> {{ $perKegiatan->sum('sudah_submit') }} submit</span>
                            <span class="badge badge-draft"><i class="fas fa-pencil-alt"></i> {{ $perKegiatan->sum('draft') }} draft</span>
                            <span class="badge badge-belum"><i class="fas fa-minus"></i> {{ $perKegiatan->sum('belum_isi') }} belum isi</span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-bar"></i>
            <h3>Belum ada data pendaftaran</h3>
            <p>Data akan muncul setelah mahasiswa mulai mengisi formulir pendaftaran KKA.</p>
        </div>
        @endif
    </div>
</div>
@endsection
