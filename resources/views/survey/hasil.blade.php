@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-sm { padding:6px 12px; font-size:12px; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-primary:hover { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .btn-success { background:#10b981; color:#fff; } .btn-success:hover { background:#059669; color:#fff; }
    .btn-danger  { background:#ef4444; color:#fff; } .btn-danger:hover  { background:#dc2626; color:#fff; }
    .btn-info    { background:#3b82f6; color:#fff; } .btn-info:hover    { background:#2563eb; color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }
    .table-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; gap:15px; flex-wrap:wrap; }
    .filter-bar { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; align-items:center; }
    .filter-bar select { padding:8px 12px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .search-box { position:relative; flex:1; max-width:350px; }
    .search-box input { width:100%; padding:10px 14px 10px 38px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; }
    .search-box input:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-secondary); font-size:14px; }
    .table-info { font-size:13px; color:var(--text-secondary); }
    .status-badge { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; }
    .status-sudah { background:rgba(59,130,246,.1); color:#1d4ed8; }
    .status-disetujui { background:rgba(16,185,129,.1); color:#059669; }
    .status-ditolak { background:rgba(239,68,68,.1); color:#dc2626; }
    .rekom-ya { color:#059669; font-weight:700; } .rekom-tidak { color:#dc2626; font-weight:700; }
    .lokasi-text { font-size:12px; color:var(--text-secondary); line-height:1.4; }
    .lokasi-text strong { color:var(--text-primary); font-size:13px; }
    .kelompok-badge { display:inline-flex; align-items:center; gap:4px; background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; margin-top:4px; }
    .btn-kelompok { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; } .btn-kelompok:hover { box-shadow:0 4px 12px rgba(165,42,42,.4); color:#fff; }
    .form-group input[type="number"] { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:18px; font-weight:700; font-family:inherit; text-align:center; }
    .form-group input[type="number"]:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .action-btns { display:flex; gap:6px; flex-wrap:wrap; }
    .empty-state { text-align:center; padding:60px 20px; color:var(--text-secondary); }
    .empty-state i { font-size:48px; color:var(--gray-border); margin-bottom:15px; display:block; }
    .empty-state h3 { font-size:16px; color:var(--text-primary); margin-bottom:8px; }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center; padding:20px; }
    .modal-overlay.active { display:flex; }
    .modal { background:#fff; border-radius:16px; width:100%; max-width:700px; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .3s ease; overflow:hidden; }
    @keyframes modalIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid var(--gray-border); }
    .modal-header h3 { font-size:18px; font-weight:700; color:var(--text-primary); margin:0; }
    .modal-close { background:none; border:none; font-size:20px; color:var(--text-secondary); cursor:pointer; padding:4px; }
    .modal-body { padding:24px; max-height:70vh; overflow-y:auto; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; padding:16px 24px; border-top:1px solid var(--gray-border); background:var(--gray-light); }
    .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .detail-item { margin-bottom:12px; }
    .detail-item label { display:block; font-size:11px; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:2px; }
    .detail-item span { font-size:14px; color:var(--text-primary); }
    .detail-full { grid-column:1/-1; }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group textarea, .form-group select { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; background:#fff; }
    .form-group textarea:focus, .form-group select:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    @media(max-width:768px){ .detail-grid{grid-template-columns:1fr} }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-clipboard-check" style="color:var(--maroon-main);margin-right:8px;"></i>Hasil Survey</h2>
            <p>Hasil survey lokasi yang telah dilaporkan</p>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('survey.hasil') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="sudah_survey" {{ request('status')=='sudah_survey'?'selected':'' }}>Menunggu Persetujuan</option>
                <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
            </select>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari desa atau nama kades...">
            </div>
            <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-search"></i> Cari</button>
            @if(request()->hasAny(['status','q']))
            <a href="{{ route('survey.hasil') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>
    </div>

    <div class="table-toolbar">
        <div class="table-info">Total: <strong>{{ $surveys->count() }}</strong> hasil survey</div>
    </div>

    <div class="table-container">
        @if($surveys->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Lokasi</th>
                    <th>Surveyor</th>
                    <th>Nama Kades</th>
                    <th style="width:100px;">Rekomendasi</th>
                    <th style="width:110px;">Status</th>
                    <th style="width:90px;">Kelompok</th>
                    <th style="width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="lokasi-text">
                            <strong>{{ $item->desa?->nama ?? '-' }}</strong><br>
                            {{ $item->desa?->kecamatan?->nama }}, {{ $item->desa?->kecamatan?->kabupaten?->nama }}
                        </div>
                    </td>
                    <td>{{ $item->surveyor?->name ?? '-' }}</td>
                    <td>{{ $item->nama_kades ?? '-' }}</td>
                    <td>
                        @if($item->rekomendasi === true)
                        <span class="rekom-ya"><i class="fas fa-thumbs-up"></i> Ya</span>
                        @elseif($item->rekomendasi === false)
                        <span class="rekom-tidak"><i class="fas fa-thumbs-down"></i> Tidak</span>
                        @else - @endif
                    </td>
                    <td>
                        @if($item->status == 'sudah_survey')
                        <span class="status-badge status-sudah"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                        @elseif($item->status == 'disetujui')
                        <span class="status-badge status-disetujui"><i class="fas fa-check-double"></i> Disetujui</span>
                        @else
                        <span class="status-badge status-ditolak"><i class="fas fa-times"></i> Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($item->kelompok)
                        <span class="kelompok-badge"><i class="fas fa-layer-group"></i> {{ $item->kelompok }}</span>
                        @else
                        <span style="color:var(--text-secondary);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn btn-info btn-sm" title="Detail" onclick="openDetailModal(this)"
                                data-lokasi="{{ $item->lokasi_lengkap }}"
                                data-surveyor="{{ $item->surveyor?->name }}"
                                data-tim="{{ $item->tim_anggota ?? '-' }}"
                                data-surveyed="{{ $item->surveyed_at?->format('d/m/Y H:i') }}"
                                data-kades="{{ $item->nama_kades }}"
                                data-hp="{{ $item->no_hp_kades }}"
                                data-pemberi="{{ $item->pemberi_informasi }}"
                                data-posko="{{ $item->rencana_posko == 'lainnya' ? $item->rencana_posko_lainnya : ucfirst(str_replace('_',' ',$item->rencana_posko ?? '-')) }}"
                                data-air="{{ $item->kondisi_air }}"
                                data-listrik="{{ $item->kondisi_listrik }}"
                                data-transportasi="{{ $item->kondisi_transportasi }}"
                                data-deskripsi="{{ $item->deskripsi }}"
                                data-gmaps="{{ $item->gmaps_url }}"
                                data-rekomendasi="{{ $item->rekomendasi === true ? 'Ya' : ($item->rekomendasi === false ? 'Tidak' : '-') }}"
                                data-alasan="{{ $item->alasan_rekomendasi }}"
                                data-catatan="{{ $item->catatan_panitia }}"
                            ><i class="fas fa-eye"></i></button>

                            @if(auth()->user()->hasAccess('verifikasi.survey') && $item->status == 'sudah_survey')
                            <button class="btn btn-success btn-sm" title="Setujui/Tolak" onclick="openSetujuiModal({{ $item->id }},'{{ addslashes($item->desa?->nama) }}')"><i class="fas fa-gavel"></i></button>
                            @endif
                            @if(auth()->user()->hasAccess('atur.kelompok') && $item->status == 'disetujui')
                            <button class="btn btn-kelompok btn-sm" title="Tentukan Kelompok" onclick="openKelompokModal({{ $item->id }},'{{ addslashes($item->desa?->nama) }}',{{ $item->kelompok ?? 'null' }})"><i class="fas fa-layer-group"></i></button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-check"></i>
            <h3>Belum ada hasil survey</h3>
            <p>Hasil survey akan muncul setelah surveyor mengisi form survey.</p>
        </div>
        @endif
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal-overlay" id="modal-detail">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-info-circle" style="color:#3b82f6;margin-right:8px;"></i>Detail Hasil Survey</h3><button class="modal-close" onclick="closeModal('modal-detail')">&times;</button></div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item detail-full"><label>Lokasi</label><span id="det-lokasi"></span></div>
                <div class="detail-item"><label><i class="fas fa-star" style="color:#f59e0b;font-size:10px;margin-right:3px;"></i>Ketua Surveyor</label><span id="det-surveyor"></span></div>
                <div class="detail-item"><label>Tanggal Survey</label><span id="det-surveyed"></span></div>
                <div class="detail-item detail-full"><label><i class="fas fa-users" style="font-size:10px;margin-right:3px;"></i>Anggota Tim</label><span id="det-tim" style="white-space:pre-line;"></span></div>
                <div class="detail-item"><label>Nama Kades</label><span id="det-kades"></span></div>
                <div class="detail-item"><label>No HP Kades</label><span id="det-hp"></span></div>
                <div class="detail-item"><label>Pemberi Informasi</label><span id="det-pemberi"></span></div>
                <div class="detail-item"><label>Rencana Posko</label><span id="det-posko"></span></div>
                <div class="detail-item detail-full"><label>Kondisi Air</label><span id="det-air"></span></div>
                <div class="detail-item detail-full"><label>Kondisi Listrik</label><span id="det-listrik"></span></div>
                <div class="detail-item detail-full"><label>Kondisi Transportasi</label><span id="det-transportasi"></span></div>
                <div class="detail-item detail-full"><label>Deskripsi Singkat</label><span id="det-deskripsi"></span></div>
                <div class="detail-item detail-full"><label>Google Maps</label><span id="det-gmaps"></span></div>
                <div class="detail-item"><label>Rekomendasi</label><span id="det-rekomendasi"></span></div>
                <div class="detail-item detail-full"><label>Alasan Rekomendasi</label><span id="det-alasan"></span></div>
                <div class="detail-item detail-full"><label>Catatan Panitia</label><span id="det-catatan"></span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('modal-detail')">Tutup</button></div>
    </div>
</div>

{{-- Modal Setujui/Tolak --}}
@if(auth()->user()->hasAccess('verifikasi.survey'))
<div class="modal-overlay" id="modal-setujui">
    <div class="modal">
        <div class="modal-header"><h3><i class="fas fa-gavel" style="color:#10b981;margin-right:8px;"></i>Verifikasi Survey</h3><button class="modal-close" onclick="closeModal('modal-setujui')">&times;</button></div>
        <form id="form-setujui" method="POST">@csrf
            <div class="modal-body">
                <p style="font-size:14px;margin-bottom:16px;">Verifikasi hasil survey untuk: <strong id="setujui-nama"></strong></p>
                <div class="form-group">
                    <label>Keputusan</label>
                    <select name="disetujui" required>
                        <option value="">-- Pilih --</option>
                        <option value="1">Setujui</option>
                        <option value="0">Tolak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan Panitia</label>
                    <textarea name="catatan_panitia" rows="3" placeholder="Catatan tambahan (opsional)..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-setujui')">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Modal Tentukan Kelompok --}}
@if(auth()->user()->hasAccess('atur.kelompok'))
<div class="modal-overlay" id="modal-kelompok">
    <div class="modal" style="max-width:400px;">
        <div class="modal-header"><h3><i class="fas fa-layer-group" style="color:var(--maroon-main);margin-right:8px;"></i>Tentukan Kelompok</h3><button class="modal-close" onclick="closeModal('modal-kelompok')">&times;</button></div>
        <form id="form-kelompok" method="POST">@csrf
            <div class="modal-body">
                <p style="font-size:14px;margin-bottom:20px;color:var(--text-secondary);">Tentukan nomor kelompok untuk lokasi: <strong id="kelompok-nama" style="color:var(--text-primary);"></strong></p>
                <div class="form-group">
                    <label>Nomor Kelompok <span style="color:#ef4444;">*</span></label>
                    <input type="number" id="kelompok-input" name="kelompok" min="1" max="9999" required placeholder="Contoh: 5">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-kelompok')">Batal</button>
                <button type="submit" class="btn btn-kelompok"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
function openModal(id){document.getElementById(id)?.classList.add('active')}
function closeModal(id){document.getElementById(id)?.classList.remove('active')}
document.querySelectorAll('.modal-overlay').forEach(o=>{o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('active')})});
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(m=>m.classList.remove('active'))});

function openDetailModal(btn) {
    const fields = ['lokasi','surveyor','tim','surveyed','kades','hp','pemberi','posko','air','listrik','transportasi','deskripsi','rekomendasi','alasan','catatan'];
    fields.forEach(f => {
        document.getElementById('det-' + f).textContent = btn.dataset[f] || '-';
    });
    // gmaps as link
    const gmaps = btn.dataset.gmaps;
    const gmapsEl = document.getElementById('det-gmaps');
    if (gmaps) {
        gmapsEl.innerHTML = '<a href="' + gmaps + '" target="_blank" style="color:#3b82f6;">' + gmaps + '</a>';
    } else {
        gmapsEl.textContent = '-';
    }
    openModal('modal-detail');
}

function openSetujuiModal(id, nama) {
    document.getElementById('setujui-nama').textContent = nama;
    document.getElementById('form-setujui').action = '{{ url("survey") }}/' + id + '/setujui';
    openModal('modal-setujui');
}

function openKelompokModal(id, nama, existing) {
    document.getElementById('kelompok-nama').textContent = nama;
    document.getElementById('kelompok-input').value = existing || '';
    document.getElementById('form-kelompok').action = '{{ url("survey") }}/' + id + '/kelompok';
    openModal('modal-kelompok');
}
</script>
@endsection
