@extends('layouts.users')

@section('css')
<style>
    .dashboard-content { padding: 24px; }

    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px; }
    .page-header p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    /* DPL identity */
    .dpl-identity {
        display: flex; align-items: center; gap: 16px;
        background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
        border-radius: 14px; padding: 20px 24px; margin-bottom: 24px; color: white;
        box-shadow: 0 6px 20px rgba(165,42,42,.25);
    }
    .dpl-avatar {
        width: 52px; height: 52px; border-radius: 13px; flex-shrink: 0;
        background: rgba(255,255,255,.2); border: 2px solid rgba(255,255,255,.35);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; font-weight: 800;
    }
    .dpl-identity-info h3 { font-size: 16px; font-weight: 700; margin: 0 0 4px; }
    .dpl-identity-info p  { font-size: 12px; margin: 0; opacity: .85; }
    .dpl-identity-badge {
        margin-left: auto; background: rgba(255,255,255,.15); border-radius: 20px;
        padding: 5px 14px; font-size: 12px; font-weight: 700;
        border: 1px solid rgba(255,255,255,.25);
    }

    /* Kegiatan grid */
    .kegiatan-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 18px; }

    .kegiatan-card {
        background: white; border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        border: 1px solid var(--gray-border);
        overflow: hidden; transition: box-shadow .2s, border-color .2s;
    }
    .kegiatan-card:hover { box-shadow: 0 4px 18px rgba(165,42,42,.12); border-color: rgba(165,42,42,.3); }

    .kegiatan-card-header {
        padding: 16px 18px; border-bottom: 1px solid var(--gray-border);
        background: var(--gray-light);
    }
    .kegiatan-card-header h3 {
        font-size: 14px; font-weight: 700; color: var(--text-primary);
        margin: 0 0 6px; line-height: 1.4;
    }
    .kegiatan-date {
        font-size: 11px; color: var(--text-secondary);
        display: flex; align-items: center; gap: 5px;
    }
    .kegiatan-date i { color: var(--maroon-main); font-size: 10px; }

    .kegiatan-card-body { padding: 14px 18px; }
    .kegiatan-stats { display: flex; gap: 20px; margin-bottom: 14px; }
    .kegiatan-stat { text-align: center; }
    .kegiatan-stat .stat-val { font-size: 20px; font-weight: 800; color: var(--text-primary); display: block; }
    .kegiatan-stat .stat-lbl { font-size: 10px; color: var(--text-secondary); }

    .btn-detail-kg {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        width: 100%; padding: 9px 0; border-radius: 8px;
        border: 1px solid var(--maroon-main); background: rgba(165,42,42,.04);
        font-size: 13px; font-weight: 600; color: var(--maroon-main);
        text-decoration: none; transition: all .15s;
    }
    .btn-detail-kg:hover { background: var(--maroon-main); color: white; }

    /* Not DPL state */
    .not-dpl-card {
        background: white; border-radius: 14px; padding: 48px 24px;
        text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,.07);
        border: 1px solid var(--gray-border);
    }
    .not-dpl-card i { font-size: 52px; color: var(--gray-border); margin-bottom: 16px; display: block; }
    .not-dpl-card h3 { font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
    .not-dpl-card p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <h2><i class="fas fa-user-graduate" style="color:var(--maroon-main);margin-right:8px;"></i>Dosen Pembimbing</h2>
        <p>Kegiatan KKA yang Anda bimbing sebagai Dosen Pembimbing Lapangan</p>
    </div>

    @if(!$pegawai)
    {{-- User tidak terdaftar sebagai DPL --}}
    <div class="not-dpl-card">
        <i class="fas fa-user-slash"></i>
        <h3>Anda tidak terdaftar sebagai DPL</h3>
        <p>
            Akun <strong>{{ auth()->user()->email }}</strong> tidak terhubung dengan data Dosen Pembimbing Lapangan.<br>
            Hubungi administrator untuk menghubungkan akun Anda dengan data pegawai.
        </p>
    </div>

    @elseif($kegiatanList->isEmpty())
    {{-- Terdaftar tapi belum ada assignment --}}
    <div class="dpl-identity">
        <div class="dpl-avatar">{{ strtoupper(substr($pegawai->nama, 0, 1)) }}</div>
        <div class="dpl-identity-info">
            <h3>{{ $pegawai->nama }}</h3>
            <p>
                @if($pegawai->nip) NIP: {{ $pegawai->nip }} &nbsp;&bull;&nbsp; @endif
                <i class="fas fa-envelope" style="font-size:11px;"></i> {{ $pegawai->email_user ?? auth()->user()->email }}
            </p>
        </div>
        <span class="dpl-identity-badge"><i class="fas fa-chalkboard-teacher" style="margin-right:4px;"></i> DPL</span>
    </div>

    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <h3>Belum ada kegiatan</h3>
        <p>Anda belum ditugaskan sebagai DPL pada kegiatan KKA manapun.</p>
    </div>

    @else
    {{-- Info DPL --}}
    <div class="dpl-identity">
        <div class="dpl-avatar">{{ strtoupper(substr($pegawai->nama, 0, 1)) }}</div>
        <div class="dpl-identity-info">
            <h3>{{ $pegawai->nama }}</h3>
            <p>
                @if($pegawai->nip) NIP: {{ $pegawai->nip }} &nbsp;&bull;&nbsp; @endif
                <i class="fas fa-envelope" style="font-size:11px;"></i> {{ $pegawai->email_user ?? auth()->user()->email }}
            </p>
        </div>
        <span class="dpl-identity-badge">
            <i class="fas fa-chalkboard-teacher" style="margin-right:4px;"></i> DPL &bull; {{ $kegiatanList->count() }} Kegiatan
        </span>
    </div>

    {{-- Filter Tahun --}}
    @if($tahunList->isNotEmpty())
    <form method="GET" action="{{ route('dosen-pembimbing.index') }}" style="margin-bottom:20px;">
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <select name="tahun_id" onchange="this.form.submit()"
                style="padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px;
                       font-size:13px; font-family:inherit; background:#fff; min-width:180px;">
                <option value="">-- Semua Tahun --</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t->id }}" {{ $tahunId == $t->id ? 'selected' : '' }}>{{ $t->nama }}</option>
                @endforeach
            </select>
            @if($tahunId)
                <a href="{{ route('dosen-pembimbing.index') }}"
                   style="padding:8px 14px; border:1px solid var(--gray-border); border-radius:8px;
                          font-size:12px; font-weight:600; background:#fff; color:var(--text-secondary);
                          text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </div>
    </form>
    @endif

    {{-- Grid Kegiatan --}}
    <div class="kegiatan-grid">
        @foreach($kegiatanList as $kg)
        @php
            $jmlMhs = $mahasiswaCounts->get($kg->id, 0);
        @endphp
        <div class="kegiatan-card">
            <div class="kegiatan-card-header">
                <h3>{{ $kg->nama }}</h3>
                <div class="kegiatan-date">
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($kg->kegiatan_mulai)->format('d M Y') }}
                    &ndash;
                    {{ \Carbon\Carbon::parse($kg->kegiatan_selesai)->format('d M Y') }}
                </div>
            </div>
            <div class="kegiatan-card-body">
                <div class="kegiatan-stats">
                    <div class="kegiatan-stat">
                        <span class="stat-val">{{ $kg->jumlah_kelompok }}</span>
                        <span class="stat-lbl">Kelompok</span>
                    </div>
                    <div class="kegiatan-stat">
                        <span class="stat-val">{{ $jmlMhs }}</span>
                        <span class="stat-lbl">Mahasiswa</span>
                    </div>
                </div>
                <a href="{{ route('dosen-pembimbing.detail', $kg->id) }}" class="btn-detail-kg">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @endif

</div>
@endsection
