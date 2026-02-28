@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-primary  { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-primary:hover  { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .btn-success  { background:#10b981; color:#fff; } .btn-success:hover { background:#059669; color:#fff; }
    .btn-danger   { background:#ef4444; color:#fff; } .btn-danger:hover  { background:#dc2626; color:#fff; }
    .btn-secondary{ background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }

    /* INFO CARD */
    .info-card { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:20px 24px; margin-bottom:24px; display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
    .kelompok-badge { width:64px; height:64px; border-radius:14px; background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); display:flex; align-items:center; justify-content:center; color:#fff; font-size:26px; font-weight:900; flex-shrink:0; }
    .info-card-body { flex:1; min-width:0; }
    .info-card-body h3 { font-size:18px; font-weight:700; color:var(--text-primary); margin:0 0 4px; }
    .info-card-body p  { font-size:13px; color:var(--text-secondary); margin:0; line-height:1.6; }
    .info-card-meta { display:flex; gap:16px; flex-wrap:wrap; margin-top:8px; }
    .info-meta-item { font-size:12px; color:var(--text-secondary); display:flex; align-items:center; gap:5px; }
    .info-meta-item i { color:var(--maroon-main); }

    /* SECTION */
    .section-card { background:#fff; border:1px solid var(--gray-border); border-radius:12px; margin-bottom:24px; overflow:hidden; }
    .section-header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid var(--gray-border); background:var(--gray-light); }
    .section-title { font-size:15px; font-weight:700; color:var(--text-primary); display:flex; align-items:center; gap:8px; }
    .section-count { background:var(--maroon-main); color:#fff; font-size:11px; font-weight:700; padding:2px 8px; border-radius:20px; }

    /* TABLE INSIDE SECTION */
    .section-table { width:100%; border-collapse:collapse; }
    .section-table th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-secondary); background:var(--gray-light); border-bottom:1px solid var(--gray-border); }
    .section-table td { padding:12px 16px; border-bottom:1px solid var(--gray-border); font-size:13px; color:var(--text-primary); vertical-align:middle; }
    .section-table tr:last-child td { border-bottom:none; }
    .section-table tr:hover td { background:rgba(165,42,42,.03); }

    /* AVATAR */
    .mhs-avatar { width:34px; height:34px; border-radius:8px; background:linear-gradient(135deg,#6366f1,#4f46e5); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:13px; flex-shrink:0; }
    .dosen-avatar { width:34px; height:34px; border-radius:8px; background:linear-gradient(135deg,#f59e0b,#d97706); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:13px; flex-shrink:0; }
    .user-info { display:flex; align-items:center; gap:10px; }
    .user-name { font-size:13px; font-weight:600; color:var(--text-primary); }
    .user-sub  { font-size:11px; color:var(--text-secondary); }

    /* KOORDINATOR SWITCH */
    .koordinator-wrap { display:flex; align-items:center; gap:8px; }
    .switch { position:relative; display:inline-block; width:40px; height:22px; }
    .switch input { opacity:0; width:0; height:0; }
    .slider { position:absolute; cursor:pointer; inset:0; background:#d1d5db; border-radius:22px; transition:.3s; }
    .slider:before { position:absolute; content:''; height:16px; width:16px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
    input:checked + .slider { background:var(--maroon-main); }
    input:checked + .slider:before { transform:translateX(18px); }
    .badge-koordinator { background:rgba(165,42,42,.1); color:var(--maroon-main); font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; display:inline-block; }

    /* EMPTY */
    .section-empty { text-align:center; padding:40px 20px; color:var(--text-secondary); }
    .section-empty i { font-size:36px; color:var(--gray-border); margin-bottom:10px; display:block; }
    .section-empty p { font-size:13px; margin:0; }

    /* MODAL */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:540px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .3s ease; overflow:hidden; max-height:90vh; display:flex; flex-direction:column; }
    @keyframes modalIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:18px 22px; border-bottom:1px solid var(--gray-border); flex-shrink:0; }
    .modal-header h3 { font-size:17px; font-weight:700; color:var(--text-primary); margin:0; }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-body { padding:20px 22px; overflow-y:auto; flex:1; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:14px 22px; border-top:1px solid var(--gray-border); background:var(--gray-light); flex-shrink:0; }
    .form-group { margin-bottom:14px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group select { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; box-sizing:border-box; }
    .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-input { width:100%; padding:9px 14px 9px 36px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; box-sizing:border-box; margin-bottom:10px; }
    .search-input:focus { outline:none; border-color:var(--maroon-main); }
    .search-wrap { position:relative; }
    .search-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:13px; }
    .delete-info { text-align:center; padding:8px 0; }
    .delete-info i { font-size:42px; color:#ef4444; margin-bottom:12px; display:block; }
    .delete-info p { font-size:14px; color:var(--text-secondary); margin:0 0 8px; }
    .delete-item-name { background:rgba(239,68,68,.1); color:#dc2626; padding:5px 12px; border-radius:6px; font-size:13px; font-weight:700; display:inline-block; }

    .alert { padding:10px 16px; border-radius:8px; font-size:13px; font-weight:600; margin-bottom:18px; display:flex; align-items:center; gap:8px; }
    .alert-success { background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.3); color:#059669; }
    .alert-error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#dc2626; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    {{-- Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-cog" style="color:var(--maroon-main);margin-right:8px;"></i>Setup Kelompok {{ $survey->kelompok }}</h2>
            <p>Atur peserta dan dosen pembimbing lapangan untuk kelompok ini</p>
        </div>
        <a href="{{ route('survey.data-lokasi') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error"><i class="fas fa-times-circle"></i>{{ session('error') }}</div>
    @endif

    {{-- Info Kelompok --}}
    <div class="info-card">
        <div class="kelompok-badge">{{ $survey->kelompok }}</div>
        <div class="info-card-body">
            <h3>{{ $survey->desa?->nama ?? '-' }}</h3>
            <p>{{ $survey->desa?->kecamatan?->nama }}, {{ $survey->desa?->kecamatan?->kabupaten?->nama }}, {{ $survey->desa?->kecamatan?->kabupaten?->provinsi?->nama }}</p>
            <div class="info-card-meta">
                @if($survey->kegiatan)
                <span class="info-meta-item"><i class="fas fa-calendar-alt"></i> {{ $survey->kegiatan->nama }}</span>
                @endif
                <span class="info-meta-item"><i class="fas fa-users"></i> {{ $survey->peserta->count() }} Peserta</span>
                <span class="info-meta-item"><i class="fas fa-chalkboard-teacher"></i> {{ $survey->dosenPembimbing->count() }} DPL</span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION: DOSEN PEMBIMBING LAPANGAN ===== --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chalkboard-teacher" style="color:#f59e0b;"></i>
                Dosen Pembimbing Lapangan (DPL)
                <span class="section-count">{{ $survey->dosenPembimbing->count() }}</span>
            </div>
            <button class="btn btn-success btn-sm" onclick="openModal('modal-tambah-dosen')">
                <i class="fas fa-plus"></i> Tambah DPL
            </button>
        </div>

        @if($survey->dosenPembimbing->count() > 0)
        <table class="section-table">
            <thead>
                <tr>
                    <th>Dosen Pembimbing</th>
                    <th>Kontak</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($survey->dosenPembimbing as $dosen)
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="dosen-avatar">{{ strtoupper(substr($dosen->nama, 0, 1)) }}</div>
                            <div>
                                <div class="user-name">{{ $dosen->nama }}</div>
                                @if($dosen->nip)
                                <div class="user-sub">NIP: {{ $dosen->nip }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px;line-height:1.7;">
                            @if($dosen->email)<div><i class="fas fa-envelope" style="color:var(--text-secondary);font-size:10px;width:14px;"></i> {{ $dosen->email }}</div>@endif
                            @if($dosen->no_hp)<div><i class="fas fa-phone" style="color:var(--text-secondary);font-size:10px;width:14px;"></i> {{ $dosen->no_hp }}</div>@endif
                            @if(!$dosen->email && !$dosen->no_hp)<span style="color:var(--text-secondary);">—</span>@endif
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('kelompok.hapus-dosen', [$survey->id, $dosen->id]) }}" method="POST"
                              onsubmit="return confirm('Hapus dosen ini dari kelompok?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="section-empty">
            <i class="fas fa-chalkboard-teacher"></i>
            <p>Belum ada dosen pembimbing. Klik "Tambah DPL" untuk menambahkan.</p>
        </div>
        @endif
    </div>

    {{-- ===== SECTION: PESERTA (MAHASISWA) ===== --}}
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-user-graduate" style="color:#6366f1;"></i>
                Peserta Mahasiswa
                <span class="section-count">{{ $survey->peserta->count() }}</span>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openModal('modal-tambah-mhs')">
                <i class="fas fa-plus"></i> Tambah Peserta
            </button>
        </div>

        @if($survey->peserta->count() > 0)
        <table class="section-table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Program Studi</th>
                    <th style="width:150px;text-align:center;">Koordinator</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($survey->peserta as $mhs)
                <tr id="row-mhs-{{ $mhs->id }}">
                    <td>
                        <div class="user-info">
                            <div class="mhs-avatar">{{ strtoupper(substr($mhs->nama, 0, 1)) }}</div>
                            <div>
                                <div class="user-name">{{ $mhs->nama }}</div>
                                <div class="user-sub">{{ $mhs->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;">{{ $mhs->programStudi?->nama ?? '—' }}</td>
                    <td style="text-align:center;">
                        <div class="koordinator-wrap" style="justify-content:center;">
                            <label class="switch">
                                <input type="checkbox"
                                    class="koordinator-toggle"
                                    data-id="{{ $mhs->id }}"
                                    data-url="{{ route('kelompok.koordinator', [$survey->id, $mhs->id]) }}"
                                    {{ $mhs->pivot->is_koordinator ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                            <span class="badge-koordinator" id="badge-{{ $mhs->id }}" style="{{ $mhs->pivot->is_koordinator ? '' : 'display:none;' }}">Koordinator</span>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('kelompok.hapus-mahasiswa', [$survey->id, $mhs->id]) }}" method="POST"
                              onsubmit="return confirm('Hapus mahasiswa ini dari kelompok?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="section-empty">
            <i class="fas fa-user-graduate"></i>
            <p>Belum ada peserta. Klik "Tambah Peserta" untuk menambahkan mahasiswa.</p>
        </div>
        @endif
    </div>

</div>

{{-- ===== MODAL TAMBAH DPL ===== --}}
<div class="modal-overlay" id="modal-tambah-dosen">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-chalkboard-teacher" style="color:#f59e0b;margin-right:8px;"></i>Tambah Dosen Pembimbing</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah-dosen')">&times;</button>
        </div>
        <form action="{{ route('kelompok.tambah-dosen', $survey->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Cari & Pilih Dosen</label>
                    <div class="search-wrap" style="margin-bottom:8px;">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-input" id="search-dosen" placeholder="Ketik nama untuk mencari..." oninput="filterSelect('select-dosen', this.value)">
                    </div>
                    <select name="pegawai_id" id="select-dosen" required size="6" style="height:auto;padding:0;">
                        @forelse($pegawaiList as $p)
                        <option value="{{ $p->id }}" data-search="{{ strtolower($p->nama . ' ' . $p->nip . ' ' . $p->email) }}"
                            style="padding:8px 12px;">
                            {{ $p->nama }}{{ $p->nip ? ' — ' . $p->nip : '' }}
                        </option>
                        @empty
                        <option disabled>Belum ada data pegawai</option>
                        @endforelse
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah-dosen')">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Tambahkan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL TAMBAH PESERTA ===== --}}
<div class="modal-overlay" id="modal-tambah-mhs">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-graduate" style="color:#6366f1;margin-right:8px;"></i>Tambah Peserta</h3>
            <button class="modal-close" onclick="closeModal('modal-tambah-mhs')">&times;</button>
        </div>
        <form action="{{ route('kelompok.tambah-mahasiswa', $survey->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Cari & Pilih Mahasiswa</label>
                    <div class="search-wrap" style="margin-bottom:8px;">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-input" id="search-mhs" placeholder="Ketik nama atau NIM untuk mencari..." oninput="filterSelect('select-mhs', this.value)">
                    </div>
                    <select name="mahasiswa_id" id="select-mhs" required size="7" style="height:auto;padding:0;">
                        @php
                            $pesertaIds = $survey->peserta->pluck('id')->toArray();
                        @endphp
                        @forelse($mahasiswaEligible as $m)
                        @if(!in_array($m->id, $pesertaIds))
                        <option value="{{ $m->id }}" data-search="{{ strtolower($m->nama . ' ' . $m->nim) }}"
                            style="padding:8px 12px;">
                            {{ $m->nama }} — {{ $m->nim }}
                        </option>
                        @endif
                        @empty
                        <option disabled>Belum ada mahasiswa yang eligible</option>
                        @endforelse
                    </select>
                    <p style="font-size:11px;color:var(--text-secondary);margin-top:6px;"><i class="fas fa-info-circle"></i> Hanya mahasiswa yang telah submit pendaftaran yang ditampilkan.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah-mhs')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambahkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
function openModal(id)  { document.getElementById(id)?.classList.add('active'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('active'); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
});

// Filter select options by keyword
function filterSelect(selectId, keyword) {
    const kw = keyword.toLowerCase();
    const select = document.getElementById(selectId);
    Array.from(select.options).forEach(opt => {
        const match = !kw || (opt.dataset.search || '').includes(kw);
        opt.style.display = match ? '' : 'none';
    });
}

// Koordinator toggle (AJAX)
document.querySelectorAll('.koordinator-toggle').forEach(toggle => {
    toggle.addEventListener('change', function () {
        const mahasiswaId  = this.dataset.id;
        const url          = this.dataset.url;
        const isKoordinator = this.checked;
        const badge        = document.getElementById('badge-' + mahasiswaId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                              || '{{ csrf_token() }}',
            },
            body: JSON.stringify({ is_koordinator: isKoordinator }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Jika jadi koordinator → matikan semua badge lain dulu
                if (isKoordinator) {
                    document.querySelectorAll('.koordinator-toggle').forEach(t => {
                        if (t !== toggle) {
                            t.checked = false;
                            const b = document.getElementById('badge-' + t.dataset.id);
                            if (b) b.style.display = 'none';
                        }
                    });
                }
                badge.style.display = isKoordinator ? '' : 'none';
            }
        })
        .catch(() => {
            // Revert on error
            toggle.checked = !isKoordinator;
        });
    });
});
</script>
@endsection
