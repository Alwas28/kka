@extends('layouts.users')

@section('css')
<style>
/* ── Dashboard Layout ─────────────────────────────────────── */
.dash-wrap {
    padding: 24px;
    overflow-y: auto;
    height: 100%;
}

/* ── Page Header ──────────────────────────────────────────── */
.dash-header {
    margin-bottom: 24px;
}
.dash-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--maroon-dark);
    margin: 0 0 4px;
}
.dash-header p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

/* ── Stat Cards ───────────────────────────────────────────── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    display: flex;
    align-items: center;
    gap: 16px;
    border-left: 4px solid transparent;
    transition: transform .15s, box-shadow .15s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.12); }
.stat-card.red    { border-left-color: var(--maroon-main); }
.stat-card.green  { border-left-color: var(--success); }
.stat-card.yellow { border-left-color: var(--warning); }
.stat-card.blue   { border-left-color: var(--info); }
.stat-card.purple { border-left-color: #8b5cf6; }
.stat-card.teal   { border-left-color: #14b8a6; }
.stat-card.orange { border-left-color: #f97316; }
.stat-card.pink   { border-left-color: #ec4899; }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.stat-icon.red    { background: #fee2e2; color: var(--maroon-main); }
.stat-icon.green  { background: #d1fae5; color: var(--success); }
.stat-icon.yellow { background: #fef3c7; color: var(--warning); }
.stat-icon.blue   { background: #dbeafe; color: var(--info); }
.stat-icon.purple { background: #ede9fe; color: #8b5cf6; }
.stat-icon.teal   { background: #ccfbf1; color: #14b8a6; }
.stat-icon.orange { background: #ffedd5; color: #f97316; }
.stat-icon.pink   { background: #fce7f3; color: #ec4899; }

.stat-body {}
.stat-value {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-label {
    font-size: 0.78rem;
    color: var(--text-secondary);
    font-weight: 500;
}
.stat-sub {
    font-size: 0.72rem;
    color: var(--text-secondary);
    margin-top: 4px;
}

/* ── Section Title ────────────────────────────────────────── */
.section-heading {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-heading i { color: var(--maroon-main); }

/* ── Content Grid ─────────────────────────────────────────── */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 900px) { .content-grid { grid-template-columns: 1fr; } }

/* ── White Box ────────────────────────────────────────────── */
.wbox {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}

/* ── Kegiatan Berlangsung ─────────────────────────────────── */
.kegiatan-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.kegiatan-table th {
    background: var(--gray-light);
    padding: 8px 10px;
    text-align: left;
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: .03em;
}
.kegiatan-table td {
    padding: 10px 10px;
    border-bottom: 1px solid var(--gray-border);
    vertical-align: middle;
}
.kegiatan-table tr:last-child td { border-bottom: none; }
.kegiatan-table tr:hover td { background: var(--gray-light); }

/* ── Badge ────────────────────────────────────────────────── */
.badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
}
.badge-success  { background: #d1fae5; color: #065f46; }
.badge-warning  { background: #fef3c7; color: #92400e; }
.badge-info     { background: #dbeafe; color: #1e40af; }
.badge-danger   { background: #fee2e2; color: #991b1b; }
.badge-purple   { background: #ede9fe; color: #5b21b6; }
.badge-teal     { background: #ccfbf1; color: #134e4a; }
.badge-gray     { background: #f3f4f6; color: #6b7280; }
.badge-orange   { background: #ffedd5; color: #9a3412; }

/* ── Mini List (Activity Feed) ────────────────────────────── */
.activity-list { list-style: none; padding: 0; margin: 0; }
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid var(--gray-border);
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 5px;
    flex-shrink: 0;
}
.activity-dot.red    { background: var(--maroon-main); }
.activity-dot.yellow { background: var(--warning); }
.activity-dot.blue   { background: var(--info); }
.activity-dot.green  { background: var(--success); }
.activity-body { flex: 1; min-width: 0; }
.activity-name { font-weight: 600; font-size: 0.82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.activity-meta { font-size: 0.73rem; color: var(--text-secondary); margin-top: 1px; }

/* ── Empty State ──────────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 28px;
    color: var(--text-secondary);
    font-size: 0.85rem;
}
.empty-state i { font-size: 1.8rem; margin-bottom: 8px; display: block; opacity: .4; }

/* ── Donut-style mini summary ─────────────────────────────── */
.mini-bar {
    display: flex;
    gap: 6px;
    margin-top: 10px;
    font-size: 0.75rem;
}
.mini-bar-item {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.mini-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ── Tahapan badge ────────────────────────────────────────── */
.tahapan-label {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 4px;
}
</style>
@endsection

@section('konten')
<div class="dash-wrap">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="dash-header">
        <h1><i class="fas fa-tachometer-alt" style="color:var(--maroon-main);margin-right:8px;"></i>Dashboard Admin</h1>
        <p>Ringkasan data sistem KKA &mdash; {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- ── Row 1: Kegiatan + Mahasiswa + Dokumen + Survey ─────── --}}
    <div class="stats-row">

        {{-- Kegiatan --}}
        <div class="stat-card red">
            <div class="stat-icon red"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalKegiatan }}</div>
                <div class="stat-label">Total Kegiatan</div>
                <div class="stat-sub">
                    {{ $kegiatanAktif }} berlangsung &middot; {{ $kegiatanSelesai }} selesai
                </div>
            </div>
        </div>

        {{-- Mahasiswa --}}
        <div class="stat-card green">
            <div class="stat-icon green"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($totalMahasiswa) }}</div>
                <div class="stat-label">Total Mahasiswa</div>
                <div class="stat-sub">
                    {{ $pendaftaranSubmit }} mendaftar &middot; {{ $pendaftaranDraft }} draft
                </div>
            </div>
        </div>

        {{-- Dokumen Pending --}}
        <div class="stat-card {{ $dokumenPending > 0 ? 'yellow' : 'green' }}">
            <div class="stat-icon {{ $dokumenPending > 0 ? 'yellow' : 'green' }}">
                <i class="fas fa-file-circle-exclamation"></i>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $dokumenPending }}</div>
                <div class="stat-label">Dokumen Pending</div>
                <div class="stat-sub">
                    {{ $dokumenDiterima }} diterima &middot; {{ $dokumenDitolak }} ditolak
                </div>
            </div>
        </div>

        {{-- Survey Lokasi --}}
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fas fa-map-location-dot"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalSurvey }}</div>
                <div class="stat-label">Lokasi Survey</div>
                <div class="stat-sub">
                    {{ $surveySetuju }} disetujui &middot; {{ $surveyBelum }} belum survey
                </div>
            </div>
        </div>

        {{-- Kelompok --}}
        <div class="stat-card purple">
            <div class="stat-icon purple"><i class="fas fa-people-group"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalKelompok }}</div>
                <div class="stat-label">Total Kelompok</div>
                <div class="stat-sub">{{ $totalMahasiswaKelompok }} mhs dalam kelompok</div>
            </div>
        </div>

        {{-- Logbook --}}
        <div class="stat-card teal">
            <div class="stat-icon teal"><i class="fas fa-book-open"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($totalLogbook) }}</div>
                <div class="stat-label">Total Logbook</div>
                <div class="stat-sub">{{ $totalLaporanIndividu }} laporan individu</div>
            </div>
        </div>

        {{-- Laporan Akhir --}}
        <div class="stat-card orange">
            <div class="stat-icon orange"><i class="fas fa-file-contract"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalLaporanAkhir }}</div>
                <div class="stat-label">Laporan Akhir</div>
                <div class="stat-sub">dari {{ $totalKelompok }} kelompok</div>
            </div>
        </div>

        {{-- Nilai --}}
        <div class="stat-card pink">
            <div class="stat-icon pink"><i class="fas fa-star-half-stroke"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalDinilai }}</div>
                <div class="stat-label">Mhs Telah Dinilai</div>
                <div class="stat-sub">memiliki nilai akhir</div>
            </div>
        </div>

    </div>{{-- /stats-row --}}

    {{-- ── Kegiatan Berlangsung ──────────────────────────────── --}}
    <div class="wbox" style="margin-bottom:24px;">
        <div class="section-heading">
            <i class="fas fa-circle-play"></i> Kegiatan Berlangsung
            <span class="badge badge-info" style="margin-left:4px;">{{ $kegiatanBerlangsung->count() }}</span>
        </div>

        @if($kegiatanBerlangsung->isEmpty())
            <div class="empty-state">
                <i class="fas fa-calendar-xmark"></i>
                Tidak ada kegiatan yang sedang berlangsung.
            </div>
        @else
        <div style="overflow-x:auto;">
        <table class="kegiatan-table">
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Tahun</th>
                    <th>Tahapan Aktif</th>
                    <th style="text-align:center;">Kelompok</th>
                    <th style="text-align:center;">Peserta</th>
                    <th style="text-align:center;">Logbook</th>
                    <th style="text-align:center;">Dinilai</th>
                    <th>Periode</th>
                </tr>
            </thead>
            <tbody>
            @foreach($kegiatanBerlangsung as $kg)
            @php
                $tahapanNow = $tahapanAktif[$kg->id] ?? null;
                $tahapanBadgeClass = match($tahapanNow) {
                    'survey'        => 'badge-info',
                    'pendaftaran'   => 'badge-warning',
                    'verifikasi'    => 'badge-orange',
                    'setup_kelompok'=> 'badge-purple',
                    'pelaksanaan'   => 'badge-teal',
                    'pelaporan'     => 'badge-success',
                    default         => 'badge-gray',
                };
                $tahapanLabel = match($tahapanNow) {
                    'survey'        => 'Survey',
                    'pendaftaran'   => 'Pendaftaran',
                    'verifikasi'    => 'Verifikasi',
                    'setup_kelompok'=> 'Setup Kelompok',
                    'pelaksanaan'   => 'Pelaksanaan',
                    'pelaporan'     => 'Pelaporan',
                    default         => 'Di luar jadwal',
                };
                $jmlKelompok = $kelompokPerKegiatan[$kg->id] ?? 0;
                $jmlPeserta  = $pesertaPerKegiatan[$kg->id]  ?? 0;
                $jmlLogbook  = $logbookPerKegiatan[$kg->id]  ?? 0;
                $jmlNilai    = $nilaiPerKegiatan[$kg->id]    ?? 0;
            @endphp
            <tr>
                <td>
                    <div style="font-weight:600;">{{ $kg->nama }}</div>
                    <div style="font-size:.73rem;color:var(--text-secondary);">{{ $kg->jenis }}</div>
                </td>
                <td>{{ $kg->tahun }}</td>
                <td><span class="badge {{ $tahapanBadgeClass }}">{{ $tahapanLabel }}</span></td>
                <td style="text-align:center;font-weight:600;">{{ $jmlKelompok }}</td>
                <td style="text-align:center;font-weight:600;">{{ $jmlPeserta }}</td>
                <td style="text-align:center;font-weight:600;">{{ number_format($jmlLogbook) }}</td>
                <td style="text-align:center;">
                    @if($jmlPeserta > 0)
                        <span class="badge {{ $jmlNilai >= $jmlPeserta ? 'badge-success' : ($jmlNilai > 0 ? 'badge-warning' : 'badge-gray') }}">
                            {{ $jmlNilai }} / {{ $jmlPeserta }}
                        </span>
                    @else
                        <span class="badge badge-gray">—</span>
                    @endif
                </td>
                <td style="font-size:.76rem;color:var(--text-secondary);">
                    {{ \Carbon\Carbon::parse($kg->kegiatan_mulai)->format('d M Y') }}<br>
                    s/d {{ \Carbon\Carbon::parse($kg->kegiatan_selesai)->format('d M Y') }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

    {{-- ── Two-Column: Recent Activity ──────────────────────── --}}
    <div class="content-grid">

        {{-- Pendaftaran Terbaru --}}
        <div class="wbox">
            <div class="section-heading">
                <i class="fas fa-user-plus"></i> Pendaftaran Terbaru
                @if($dokumenPending > 0)
                    <a href="{{ route('dokumen.pembayaran') }}" class="badge badge-warning" style="margin-left:auto;text-decoration:none;">
                        {{ $dokumenPending }} pending
                    </a>
                @endif
            </div>
            @if($pendaftaranTerbaru->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    Belum ada pendaftaran masuk.
                </div>
            @else
            <ul class="activity-list">
                @foreach($pendaftaranTerbaru as $p)
                <li class="activity-item">
                    <div class="activity-dot blue"></div>
                    <div class="activity-body">
                        <div class="activity-name">{{ $p->nama }} <span style="font-weight:400;color:var(--text-secondary);">({{ $p->nim }})</span></div>
                        <div class="activity-meta">
                            {{ $p->prodi }} &middot; {{ $p->kegiatan }}
                        </div>
                        <div class="activity-meta">
                            {{ $p->submitted_at ? \Carbon\Carbon::parse($p->submitted_at)->diffForHumans() : '—' }}
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @if($pendaftaranSubmit > 8)
                <div style="text-align:center;margin-top:10px;">
                    <a href="{{ route('registrasi.index') }}" style="font-size:.8rem;color:var(--maroon-main);font-weight:600;">
                        Lihat semua ({{ $pendaftaranSubmit }}) &rarr;
                    </a>
                </div>
            @endif
            @endif
        </div>

        {{-- Dokumen Pending --}}
        <div class="wbox">
            <div class="section-heading">
                <i class="fas fa-file-circle-question"></i> Dokumen Menunggu Verifikasi
            </div>
            @if($dokumenPendingTerbaru->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    Semua dokumen telah diverifikasi.
                </div>
            @else
            <ul class="activity-list">
                @foreach($dokumenPendingTerbaru as $d)
                <li class="activity-item">
                    <div class="activity-dot yellow"></div>
                    <div class="activity-body">
                        <div class="activity-name">{{ $d->nama }} <span style="font-weight:400;color:var(--text-secondary);">({{ $d->nim }})</span></div>
                        <div class="activity-meta">{{ $d->dokumen }}</div>
                        <div class="activity-meta">
                            {{ \Carbon\Carbon::parse($d->uploaded_at)->diffForHumans() }}
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @if($dokumenPending > 8)
                <div style="text-align:center;margin-top:10px;">
                    <a href="{{ route('dokumen.pembayaran') }}" style="font-size:.8rem;color:var(--maroon-main);font-weight:600;">
                        Lihat semua ({{ $dokumenPending }}) &rarr;
                    </a>
                </div>
            @endif
            @endif
        </div>

    </div>{{-- /content-grid --}}

    {{-- ── Survey Lokasi Status Summary ─────────────────────── --}}
    <div class="wbox" style="margin-bottom:24px;">
        <div class="section-heading"><i class="fas fa-map-marked-alt"></i> Status Lokasi Survey</div>
        @if($totalSurvey === 0)
            <div class="empty-state">
                <i class="fas fa-map"></i>
                Belum ada data lokasi survey.
            </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;">
            <div style="background:var(--gray-light);border-radius:8px;padding:16px;text-align:center;">
                <div style="font-size:1.5rem;font-weight:800;color:var(--text-secondary);">{{ $surveyBelum }}</div>
                <div class="badge badge-gray" style="margin-top:6px;">Belum Survey</div>
            </div>
            <div style="background:var(--gray-light);border-radius:8px;padding:16px;text-align:center;">
                <div style="font-size:1.5rem;font-weight:800;color:var(--info);">{{ $surveySudah }}</div>
                <div class="badge badge-info" style="margin-top:6px;">Sudah Survey</div>
            </div>
            <div style="background:var(--gray-light);border-radius:8px;padding:16px;text-align:center;">
                <div style="font-size:1.5rem;font-weight:800;color:var(--success);">{{ $surveySetuju }}</div>
                <div class="badge badge-success" style="margin-top:6px;">Disetujui</div>
            </div>
            <div style="background:var(--gray-light);border-radius:8px;padding:16px;text-align:center;">
                <div style="font-size:1.5rem;font-weight:800;color:#dc2626;">{{ $surveyDitolak }}</div>
                <div class="badge badge-danger" style="margin-top:6px;">Ditolak</div>
            </div>
        </div>
        @if($totalSurvey > 0)
        <div style="margin-top:14px;background:var(--gray-border);border-radius:999px;height:8px;overflow:hidden;">
            @php
                $pctSetuju  = $totalSurvey ? round($surveySetuju  / $totalSurvey * 100) : 0;
                $pctSudah   = $totalSurvey ? round($surveySudah   / $totalSurvey * 100) : 0;
                $pctDitolak = $totalSurvey ? round($surveyDitolak / $totalSurvey * 100) : 0;
                $pctBelum   = max(0, 100 - $pctSetuju - $pctSudah - $pctDitolak);
            @endphp
            <div style="display:flex;height:100%;">
                <div style="width:{{ $pctSetuju }}%;background:var(--success);"></div>
                <div style="width:{{ $pctSudah }}%;background:var(--info);"></div>
                <div style="width:{{ $pctDitolak }}%;background:#dc2626;"></div>
                <div style="width:{{ $pctBelum }}%;background:#d1d5db;"></div>
            </div>
        </div>
        <div class="mini-bar" style="margin-top:8px;flex-wrap:wrap;">
            <div class="mini-bar-item"><div class="mini-dot" style="background:var(--success);"></div> Disetujui {{ $pctSetuju }}%</div>
            <div class="mini-bar-item"><div class="mini-dot" style="background:var(--info);"></div> Sudah Survey {{ $pctSudah }}%</div>
            <div class="mini-bar-item"><div class="mini-dot" style="background:#dc2626;"></div> Ditolak {{ $pctDitolak }}%</div>
            <div class="mini-bar-item"><div class="mini-dot" style="background:#d1d5db;"></div> Belum Survey {{ $pctBelum }}%</div>
        </div>
        @endif
        @endif
    </div>

    {{-- ── Quick Links ───────────────────────────────────────── --}}
    <div class="wbox">
        <div class="section-heading"><i class="fas fa-bolt"></i> Aksi Cepat</div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            @if(auth()->user()->hasAccess('lihat.kegiatan'))
            <a href="{{ route('kegiatan.index') }}" class="btn-quick">
                <i class="fas fa-calendar-alt"></i> Manajemen Kegiatan
            </a>
            @endif
            @if(auth()->user()->hasAccess('lihat.registrasi'))
            <a href="{{ route('registrasi.index') }}" class="btn-quick">
                <i class="fas fa-user-check"></i> Registrasi Mahasiswa
            </a>
            @endif
            @if(auth()->user()->hasAccess('lihat.dokumen'))
            <a href="{{ route('dokumen.pembayaran') }}" class="btn-quick">
                <i class="fas fa-file-invoice"></i> Verifikasi Dokumen
            </a>
            @endif
            @if(auth()->user()->hasAccess('lihat.survey'))
            <a href="{{ route('survey.index') }}" class="btn-quick">
                <i class="fas fa-map-location-dot"></i> Survey Lokasi
            </a>
            @endif
            @if(auth()->user()->hasAccess('lihat.peserta'))
            <a href="{{ route('peserta.index') }}" class="btn-quick">
                <i class="fas fa-users"></i> Data Peserta
            </a>
            @endif
        </div>
    </div>

</div>{{-- /dash-wrap --}}

<style>
.btn-quick {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: var(--gray-light);
    border: 1px solid var(--gray-border);
    border-radius: 8px;
    font-size: 0.83rem;
    font-weight: 600;
    color: var(--text-primary);
    text-decoration: none;
    transition: background .15s, border-color .15s;
}
.btn-quick:hover {
    background: #e8f0fe;
    border-color: var(--info);
    color: var(--info);
}
.btn-quick i { font-size: .85rem; }
</style>
@endsection
