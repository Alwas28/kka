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

    /* Filter bar */
    .filter-bar {
        display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
        background: white; border: 1px solid var(--gray-border); border-radius: 12px;
        padding: 14px 16px; margin-bottom: 22px;
    }
    .filter-bar select {
        padding: 8px 12px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit; background: #fff; min-width: 180px;
    }
    .filter-reset {
        padding: 8px 14px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 12px; font-weight: 600; background: #fff; color: var(--text-secondary);
        text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    }

    /* Kelompok grid */
    .kelompok-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px; }

    .kelompok-card {
        display: block; text-decoration: none; color: inherit;
        background: white; border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        border: 1px solid var(--gray-border);
        overflow: hidden; transition: box-shadow .2s, border-color .2s, transform .15s;
    }
    .kelompok-card:hover { box-shadow: 0 4px 18px rgba(165,42,42,.12); border-color: rgba(165,42,42,.3); transform: translateY(-1px); }

    .kelompok-card-header {
        padding: 16px 18px; display: flex; align-items: center; gap: 14px;
        border-bottom: 1px solid var(--gray-border); background: var(--gray-light);
    }
    .kel-num {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--maroon-dark), var(--maroon-main));
        color: white; font-size: 18px; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
    }
    .kelompok-card-header h3 { font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0 0 3px; }
    .kelompok-card-header p  { font-size: 11px; color: var(--text-secondary); margin: 0; }

    .kelompok-card-body { padding: 14px 18px; }
    .kelompok-stats { display: flex; gap: 20px; }
    .kelompok-stat { text-align: center; }
    .kelompok-stat .stat-val { font-size: 20px; font-weight: 800; color: var(--text-primary); display: block; }
    .kelompok-stat .stat-lbl { font-size: 10px; color: var(--text-secondary); }

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
        <p>Kelompok KKA yang Anda bimbing sebagai Dosen Pembimbing Lapangan</p>
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
            <i class="fas fa-chalkboard-teacher" style="margin-right:4px;"></i> DPL
        </span>
    </div>

    {{-- Filter Tahun & Jenis KKA --}}
    <form method="GET" action="{{ route('dosen-pembimbing.index') }}" class="filter-bar">
        <select name="tahun_id" onchange="this.form.submit()">
            <option value="">-- Pilih Tahun --</option>
            @foreach($tahunList as $t)
                <option value="{{ $t->id }}" {{ $tahunId == $t->id ? 'selected' : '' }}>{{ $t->nama }}</option>
            @endforeach
        </select>
        <select name="jenis_kka_id" onchange="this.form.submit()">
            <option value="">-- Pilih Jenis KKA --</option>
            @foreach($jenisKkaList as $jk)
                <option value="{{ $jk->id }}" {{ $jenisKkaId == $jk->id ? 'selected' : '' }}>{{ $jk->nama }}</option>
            @endforeach
        </select>
        @if($tahunId || $jenisKkaId)
            <a href="{{ route('dosen-pembimbing.index') }}" class="filter-reset">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif
    </form>

    @if(!$tahunId || !$jenisKkaId)
    <div class="empty-state">
        <i class="fas fa-filter"></i>
        <h3>Pilih Tahun dan Jenis KKA</h3>
        <p>Pilih kedua filter di atas untuk menampilkan kelompok yang Anda bimbing.</p>
    </div>

    @elseif($kelompokList->isEmpty())
    <div class="empty-state">
        <i class="fas fa-map-marked-alt"></i>
        <h3>Tidak ada kelompok</h3>
        <p>Anda belum ditugaskan sebagai DPL pada kelompok manapun untuk kombinasi filter ini.</p>
    </div>

    @else
    <div class="kelompok-grid">
        @foreach($kelompokList as $kel)
        <a href="{{ route('dosen-pembimbing.detail', $kel->survey_id) }}" class="kelompok-card">
            <div class="kelompok-card-header">
                <div class="kel-num">{{ $kel->kelompok }}</div>
                <div>
                    <h3>Kelompok {{ $kel->kelompok }}</h3>
                    <p>
                        @if($kel->desa)
                            <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                            {{ $kel->desa }}{{ $kel->kecamatan ? ', ' . $kel->kecamatan : '' }}
                        @else
                            Lokasi belum diatur
                        @endif
                    </p>
                </div>
            </div>
            <div class="kelompok-card-body">
                <div class="kelompok-stats">
                    <div class="kelompok-stat">
                        <span class="stat-val">{{ $kel->jumlah_peserta }}</span>
                        <span class="stat-lbl">Mahasiswa</span>
                    </div>
                    <div class="kelompok-stat">
                        <span class="stat-val">{{ $kel->jumlah_dinilai }}/{{ $kel->jumlah_peserta }}</span>
                        <span class="stat-lbl">Sudah Dinilai</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    @endif

</div>
@endsection
