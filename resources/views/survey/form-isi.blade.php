@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:22px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all .3s; font-family:inherit; text-decoration:none; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-primary:hover { box-shadow:0 4px 15px rgba(165,42,42,.4); transform:translateY(-1px); color:#fff; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); } .btn-secondary:hover { background:#d1d5db; }

    .info-card { background:linear-gradient(135deg,rgba(165,42,42,.05),rgba(165,42,42,.02)); border:1px solid rgba(165,42,42,.15); border-radius:12px; padding:20px; margin-bottom:25px; }
    .info-card h4 { font-size:15px; font-weight:700; color:var(--maroon-main); margin-bottom:12px; }
    .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:10px; }
    .info-item label { font-size:11px; font-weight:600; color:var(--text-secondary); text-transform:uppercase; display:block; }
    .info-item span { font-size:14px; color:var(--text-primary); font-weight:500; }

    .form-card { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:24px; margin-bottom:20px; }
    .form-card h4 { font-size:15px; font-weight:700; color:var(--text-primary); margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid var(--gray-border); }
    .form-group { margin-bottom:18px; }
    .form-group label { display:block; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
    .form-group label .required { color:#ef4444; }
    .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; transition:all .3s; background:#fff; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-hint { font-size:11px; color:var(--text-secondary); margin-top:4px; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }

    .radio-group { display:flex; gap:15px; flex-wrap:wrap; }
    .radio-item { display:flex; align-items:center; gap:6px; cursor:pointer; }
    .radio-item input[type="radio"] { accent-color:var(--maroon-main); width:16px; height:16px; }
    .radio-item label { cursor:pointer; font-weight:400; margin-bottom:0; }

    .rekomendasi-box { display:flex; gap:15px; }
    .rekomendasi-option { flex:1; position:relative; }
    .rekomendasi-option input[type="radio"] { display:none; }
    .rekomendasi-option .rekom-label { display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; border:2px solid var(--gray-border); border-radius:10px; cursor:pointer; font-weight:600; font-size:14px; transition:all .3s; }
    .rekomendasi-option input:checked + .rekom-label.rekom-ya { border-color:#10b981; background:rgba(16,185,129,.08); color:#059669; }
    .rekomendasi-option input:checked + .rekom-label.rekom-tidak { border-color:#ef4444; background:rgba(239,68,68,.08); color:#dc2626; }

    .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:25px; padding-top:20px; border-top:1px solid var(--gray-border); }

    @media(max-width:768px){ .form-row{grid-template-columns:1fr} .rekomendasi-box{flex-direction:column} }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-clipboard-list" style="color:var(--maroon-main);margin-right:8px;"></i>Isi Hasil Survey</h2>
            <p>Lengkapi data hasil survey lokasi</p>
        </div>
        <a href="{{ route('survey.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    {{-- Info Lokasi --}}
    <div class="info-card">
        <h4><i class="fas fa-map-marker-alt" style="margin-right:6px;"></i>Informasi Lokasi</h4>
        <div class="info-grid">
            <div class="info-item"><label>Desa/Kelurahan</label><span>{{ $survey->desa?->nama ?? '-' }}</span></div>
            <div class="info-item"><label>Kecamatan</label><span>{{ $survey->desa?->kecamatan?->nama ?? '-' }}</span></div>
            <div class="info-item"><label>Kabupaten/Kota</label><span>{{ $survey->desa?->kecamatan?->kabupaten?->nama ?? '-' }}</span></div>
            <div class="info-item"><label>Provinsi</label><span>{{ $survey->desa?->kecamatan?->kabupaten?->provinsi?->nama ?? '-' }}</span></div>
            <div class="info-item"><label><i class="fas fa-star" style="color:#f59e0b;font-size:10px;margin-right:2px;"></i> Ketua Surveyor</label><span>{{ $survey->surveyor?->name ?? '-' }}</span></div>
            <div class="info-item"><label>Kegiatan</label><span>{{ $survey->kegiatan?->nama ?? '-' }}</span></div>
            @if($survey->tim_anggota)
            <div class="info-item" style="grid-column:1/-1;">
                <label><i class="fas fa-users" style="font-size:10px;margin-right:2px;"></i> Anggota Tim</label>
                <span style="white-space:pre-line;">{{ $survey->tim_anggota }}</span>
            </div>
            @endif
        </div>
    </div>

    <form action="{{ route('survey.simpan', $survey->id) }}" method="POST">
        @csrf

        {{-- Data Pemerintahan Desa --}}
        <div class="form-card">
            <h4><i class="fas fa-user-tie" style="margin-right:6px;color:var(--maroon-main);"></i>Data Pemerintahan Desa</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Kepala Desa</label>
                    <input type="text" name="nama_kades" value="{{ old('nama_kades', $survey->nama_kades) }}" placeholder="Nama lengkap kades">
                </div>
                <div class="form-group">
                    <label>No HP Kades</label>
                    <input type="text" name="no_hp_kades" value="{{ old('no_hp_kades', $survey->no_hp_kades) }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>
            <div class="form-group">
                <label>Pemberi Informasi</label>
                <input type="text" name="pemberi_informasi" value="{{ old('pemberi_informasi', $survey->pemberi_informasi) }}" placeholder="Nama pemberi informasi di lokasi">
                <div class="form-hint">Orang yang memberikan informasi saat survey dilakukan</div>
            </div>
        </div>

        {{-- Rencana Posko --}}
        <div class="form-card">
            <h4><i class="fas fa-home" style="margin-right:6px;color:var(--maroon-main);"></i>Rencana Posko</h4>
            <div class="form-group">
                <label>Lokasi Rencana Posko</label>
                <div class="radio-group">
                    <div class="radio-item">
                        <input type="radio" name="rencana_posko" id="posko-kades" value="rumah_kades" {{ old('rencana_posko', $survey->rencana_posko) == 'rumah_kades' ? 'checked' : '' }} onchange="togglePoskoLainnya()">
                        <label for="posko-kades">Rumah Kades</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" name="rencana_posko" id="posko-warga" value="rumah_warga" {{ old('rencana_posko', $survey->rencana_posko) == 'rumah_warga' ? 'checked' : '' }} onchange="togglePoskoLainnya()">
                        <label for="posko-warga">Rumah Warga</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" name="rencana_posko" id="posko-lainnya" value="lainnya" {{ old('rencana_posko', $survey->rencana_posko) == 'lainnya' ? 'checked' : '' }} onchange="togglePoskoLainnya()">
                        <label for="posko-lainnya">Lainnya</label>
                    </div>
                </div>
            </div>
            <div class="form-group" id="posko-lainnya-group" style="{{ old('rencana_posko', $survey->rencana_posko) == 'lainnya' ? '' : 'display:none;' }}">
                <label>Keterangan Lokasi Lainnya</label>
                <input type="text" name="rencana_posko_lainnya" value="{{ old('rencana_posko_lainnya', $survey->rencana_posko_lainnya) }}" placeholder="Jelaskan lokasi posko...">
            </div>
        </div>

        {{-- Kondisi Wilayah --}}
        <div class="form-card">
            <h4><i class="fas fa-mountain" style="margin-right:6px;color:var(--maroon-main);"></i>Kondisi Wilayah</h4>
            <div class="form-group">
                <label>Kondisi Air</label>
                <textarea name="kondisi_air" rows="2" placeholder="Deskripsikan kondisi air di lokasi...">{{ old('kondisi_air', $survey->kondisi_air) }}</textarea>
            </div>
            <div class="form-group">
                <label>Kondisi Listrik</label>
                <textarea name="kondisi_listrik" rows="2" placeholder="Deskripsikan kondisi listrik di lokasi...">{{ old('kondisi_listrik', $survey->kondisi_listrik) }}</textarea>
            </div>
            <div class="form-group">
                <label>Kondisi Transportasi</label>
                <textarea name="kondisi_transportasi" rows="2" placeholder="Deskripsikan kondisi transportasi/akses jalan...">{{ old('kondisi_transportasi', $survey->kondisi_transportasi) }}</textarea>
            </div>
        </div>

        {{-- Deskripsi & Maps --}}
        <div class="form-card">
            <h4><i class="fas fa-map" style="margin-right:6px;color:var(--maroon-main);"></i>Informasi Tambahan</h4>
            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat tentang lokasi...">{{ old('deskripsi', $survey->deskripsi) }}</textarea>
            </div>
            <div class="form-group">
                <label>Link Google Maps</label>
                <input type="url" name="gmaps_url" value="{{ old('gmaps_url', $survey->gmaps_url) }}" placeholder="https://maps.google.com/...">
                <div class="form-hint">Salin link lokasi dari Google Maps</div>
            </div>
        </div>

        {{-- Rekomendasi --}}
        <div class="form-card">
            <h4><i class="fas fa-thumbs-up" style="margin-right:6px;color:var(--maroon-main);"></i>Rekomendasi</h4>
            <div class="form-group">
                <label>Apakah lokasi ini direkomendasikan? <span class="required">*</span></label>
                <div class="rekomendasi-box">
                    <div class="rekomendasi-option">
                        <input type="radio" name="rekomendasi" id="rekom-ya" value="1" {{ old('rekomendasi', $survey->rekomendasi) == '1' ? 'checked' : '' }} required>
                        <label for="rekom-ya" class="rekom-label rekom-ya"><i class="fas fa-thumbs-up"></i> Ya, Direkomendasikan</label>
                    </div>
                    <div class="rekomendasi-option">
                        <input type="radio" name="rekomendasi" id="rekom-tidak" value="0" {{ old('rekomendasi') === '0' || (old('rekomendasi') === null && $survey->rekomendasi === false) ? 'checked' : '' }}>
                        <label for="rekom-tidak" class="rekom-label rekom-tidak"><i class="fas fa-thumbs-down"></i> Tidak Direkomendasikan</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Alasan Rekomendasi</label>
                <textarea name="alasan_rekomendasi" rows="3" placeholder="Jelaskan alasan rekomendasi Anda...">{{ old('alasan_rekomendasi', $survey->alasan_rekomendasi) }}</textarea>
            </div>
        </div>

        @if($errors->any())
        <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:12px 16px;margin-bottom:20px;">
            <ul style="margin:0;padding-left:20px;font-size:13px;color:#dc2626;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('survey.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Hasil Survey</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
function togglePoskoLainnya() {
    const lainnya = document.getElementById('posko-lainnya');
    document.getElementById('posko-lainnya-group').style.display = lainnya.checked ? '' : 'none';
}
</script>
@endsection
