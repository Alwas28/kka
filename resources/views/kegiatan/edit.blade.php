@extends('layouts.users')

@section('title', 'Edit Kegiatan')

@section('css')
<style>
    /* ─── PAGE LAYOUT ─────────────────────────── */
    .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
    .back-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px; background: white;
        border: 1px solid var(--gray-border); color: var(--text-secondary);
        cursor: pointer; text-decoration: none; transition: all 0.2s; flex-shrink: 0;
    }
    .back-btn:hover { background: var(--gray-light); color: var(--maroon-main); border-color: var(--maroon-main); }
    .page-header-text h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin: 0 0 3px; }
    .page-header-text p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    .form-section { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 20px; overflow: hidden; }
    .section-header { display: flex; align-items: center; gap: 10px; padding: 16px 22px; background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%); color: white; }
    .section-header i  { font-size: 15px; }
    .section-header h3 { font-size: 14px; font-weight: 700; margin: 0; letter-spacing: 0.3px; }
    .section-header .section-desc { font-size: 12px; opacity: 0.8; margin: 0; margin-left: auto; }
    .section-body { padding: 22px; }

    .form-grid-3 { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 16px; }
    .form-group { margin-bottom: 0; }
    .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; }
    .form-group label .required { color: #ef4444; }
    .form-group input[type=text],
    .form-group input[type=date],
    .form-group select { width: 100%; padding: 9px 12px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; color: var(--text-primary); background: white; transition: all 0.25s; box-sizing: border-box; }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .form-hint { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }

    .tl-icon { width: 30px; height: 30px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
    .tl-kegiatan    { background: rgba(165,42,42,0.1); color: var(--maroon-main); }
    .tl-survey      { background: rgba(59,130,246,0.1); color: #2563eb; }
    .tl-daftar      { background: rgba(16,185,129,0.1); color: #059669; }
    .tl-verifikasi  { background: rgba(245,158,11,0.1); color: #d97706; }
    .tl-setup       { background: rgba(20,184,166,0.1); color: #0d9488; }
    .tl-pelaksanaan { background: rgba(139,92,246,0.1); color: #7c3aed; }
    .tl-pelaporan   { background: rgba(236,72,153,0.1); color: #db2777; }

    .tahapan-row { padding: 10px 0; border-bottom: 1px solid var(--gray-border); }
    .tahapan-row:last-child { border-bottom: none; padding-bottom: 0; }
    .tahapan-row-fixed { padding-bottom: 16px; margin-bottom: 4px; }
    .tahapan-info { display: flex; align-items: center; gap: 10px; padding: 4px 0; }
    .tahapan-name { flex: 1; font-size: 13px; font-weight: 600; color: var(--text-primary); transition: color 0.2s; }
    .tahapan-row.tahapan-active .tahapan-name { color: var(--maroon-main); }
    .tahapan-badge-wajib { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: rgba(165,42,42,0.1); color: var(--maroon-main); }
    .tahapan-dates { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 8px 0 4px 40px; }
    .tahapan-divider { display: flex; align-items: center; gap: 10px; padding: 10px 0 8px; font-size: 11px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; }
    .tahapan-divider::before, .tahapan-divider::after { content: ''; flex: 1; height: 1px; background: var(--gray-border); }

    .date-input-wrap label { font-size: 11px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 5px; display: block; }
    .date-input-wrap input[type=date] { width: 100%; padding: 9px 12px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; color: var(--text-primary); background: white; transition: all 0.25s; box-sizing: border-box; }
    .date-input-wrap input[type=date]:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    @media (max-width: 600px) { .tahapan-dates { grid-template-columns: 1fr; padding-left: 0; } }

    .doc-list { display: flex; flex-direction: column; gap: 10px; }
    .doc-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: var(--gray-light); border-radius: 10px; border: 1px solid var(--gray-border); transition: border-color 0.2s; }
    .doc-item:hover { border-color: rgba(165,42,42,0.3); }
    .doc-icon { width: 34px; height: 34px; border-radius: 8px; background: white; border: 1px solid var(--gray-border); display: flex; align-items: center; justify-content: center; color: var(--maroon-main); font-size: 14px; flex-shrink: 0; }
    .doc-icon.custom { color: var(--text-secondary); }
    .doc-name { flex: 1; font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .doc-name-input { flex: 1; padding: 7px 12px; border: 1px solid var(--gray-border); border-radius: 7px; font-size: 13px; font-family: inherit; background: white; transition: all 0.25s; }
    .doc-name-input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .doc-name-input::placeholder { color: #9ca3af; }

    .wajib-wrap { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .wajib-label { font-size: 12px; font-weight: 600; min-width: 72px; text-align: right; transition: color 0.2s; }
    .wajib-label.off { color: var(--text-secondary); }
    .wajib-label.on  { color: var(--maroon-main); }
    .toggle-switch { position: relative; display: inline-block; width: 42px; height: 23px; flex-shrink: 0; cursor: pointer; }
    .toggle-switch input { display: none; }
    .toggle-slider { position: absolute; inset: 0; background: #d1d5db; border-radius: 23px; transition: 0.3s; }
    .toggle-slider::before { content: ''; position: absolute; width: 17px; height: 17px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    .toggle-switch input:checked + .toggle-slider { background: var(--maroon-main); }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(19px); }
    .btn-remove-doc { width: 28px; height: 28px; border-radius: 6px; border: none; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s; line-height: 1; }
    .btn-remove-doc:hover { background: #ef4444; color: white; }
    .btn-add-doc { display: inline-flex; align-items: center; gap: 7px; margin-top: 10px; padding: 8px 16px; border: 1.5px dashed var(--maroon-lighter); border-radius: 8px; background: transparent; color: var(--maroon-main); font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-family: inherit; width: 100%; justify-content: center; }
    .btn-add-doc:hover { background: rgba(165,42,42,0.05); border-color: var(--maroon-main); }

    .sub-section { border: 1px solid var(--gray-border); border-radius: 10px; overflow: hidden; margin-bottom: 16px; }
    .sub-section:last-child { margin-bottom: 0; }
    .sub-section-header { display: flex; align-items: center; gap: 8px; padding: 11px 16px; background: var(--gray-light); border-bottom: 1px solid var(--gray-border); font-size: 13px; font-weight: 700; color: var(--text-primary); }
    .sub-section-header i { color: var(--maroon-main); font-size: 14px; }
    .sub-section-body { padding: 14px 16px; }

    /* ─── KOMPONEN PENILAIAN ─────────────────────────── */
    .komponen-list { display: flex; flex-direction: column; gap: 8px; }
    .komponen-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--gray-light); border-radius: 10px; border: 1px solid var(--gray-border); transition: border-color .2s; }
    .komponen-item:hover { border-color: rgba(165,42,42,.3); }
    .komponen-num { width: 24px; height: 24px; border-radius: 50%; background: var(--maroon-main); color: white; font-size: 11px; font-weight: 700; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .komponen-nama { flex: 1; padding: 7px 12px; border: 1px solid var(--gray-border); border-radius: 7px; font-size: 13px; font-family: inherit; background: white; }
    .komponen-nama:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .komponen-nama::placeholder { color: #9ca3af; }
    .persen-wrap { display: flex; align-items: center; border: 1px solid var(--gray-border); border-radius: 7px; background: white; overflow: hidden; flex-shrink: 0; }
    .persen-input { width: 56px; padding: 7px 8px; border: none; font-size: 13px; font-family: inherit; text-align: center; background: transparent; }
    .persen-input:focus { outline: none; }
    .persen-input::-webkit-inner-spin-button, .persen-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .persen-suffix { padding: 0 8px 0 2px; font-size: 13px; font-weight: 700; color: var(--text-secondary); }
    .total-bar { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; margin-top: 10px; background: var(--gray-light); border: 1px solid var(--gray-border); font-size: 12px; color: var(--text-secondary); }
    .total-bar strong { font-size: 14px; }

    /* ─── GRADE / FORMAT NILAI ─────────────────────────── */
    .grade-list { display: flex; flex-direction: column; gap: 8px; }
    .grade-item { display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: var(--gray-light); border-radius: 10px; border: 1px solid var(--gray-border); transition: border-color .2s; }
    .grade-item:hover { border-color: rgba(165,42,42,.3); }
    .grade-badge-input { width: 56px; padding: 7px 10px; border: 1px solid var(--gray-border); border-radius: 7px; font-size: 13px; font-family: inherit; font-weight: 700; text-align: center; background: white; flex-shrink: 0; }
    .grade-badge-input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .nilai-range-input { width: 76px; padding: 7px 10px; border: 1px solid var(--gray-border); border-radius: 7px; font-size: 13px; font-family: inherit; text-align: center; background: white; flex-shrink: 0; }
    .nilai-range-input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .grade-sep { font-size: 13px; font-weight: 600; color: var(--text-secondary); flex-shrink: 0; }
    .grade-label-sm { font-size: 11px; font-weight: 700; color: var(--text-secondary); flex-shrink: 0; text-transform: uppercase; letter-spacing: .3px; }
    .btn-preset { margin-left: auto; padding: 3px 12px; border: 1px solid var(--gray-border); border-radius: 6px; font-size: 11px; font-weight: 600; background: white; color: var(--text-secondary); cursor: pointer; font-family: inherit; display: inline-flex; align-items: center; gap: 5px; }
    .btn-preset:hover { background: var(--gray-light); color: var(--maroon-main); border-color: var(--maroon-main); }

    .action-bar { display: flex; justify-content: flex-end; align-items: center; gap: 12px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 16px 22px; margin-bottom: 30px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-family: inherit; text-decoration: none; }
    .btn-secondary { background: var(--gray-border); color: var(--text-primary); }
    .btn-secondary:hover { background: #d1d5db; }
    .btn-warning { background: #f59e0b; color: white; }
    .btn-warning:hover { background: #d97706; box-shadow: 0 4px 15px rgba(245,158,11,0.4); transform: translateY(-1px); color: white; }

    @media (max-width: 900px) {
        .form-grid-3 { grid-template-columns: 1fr; }
        .timeline-row { grid-template-columns: 1fr; gap: 8px; }
    }
    @media (max-width: 600px) { .form-grid-2 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <a href="{{ route('kegiatan.index') }}" class="back-btn" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="page-header-text">
            <h2><i class="fas fa-edit" style="color:#f59e0b; margin-right:8px;"></i>Edit Kegiatan</h2>
            <p>{{ $kegiatan->nama }}</p>
        </div>
    </div>

    <form action="{{ route('kegiatan.update', $kegiatan) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ═══ SECTION 1: INFORMASI DASAR ═══ --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-info-circle"></i>
                <h3>Informasi Dasar</h3>
            </div>
            <div class="section-body">
                <div style="margin-bottom:16px;">
                    <div class="form-group">
                        <label>Nama Kegiatan <span class="required">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $kegiatan->nama) }}" placeholder="contoh: KKA Reguler Semester Ganjil 2025/2026">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label>Jenis KKA <span class="required">*</span></label>
                        <select name="jenis_kka_id">
                            <option value="">-- Pilih Jenis KKA --</option>
                            @foreach($jenisKkaList as $item)
                            <option value="{{ $item->id }}" {{ old('jenis_kka_id', $kegiatan->jenis_kka_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tahun <span class="required">*</span></label>
                        <select name="tahun_id">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach($tahunList as $item)
                            <option value="{{ $item->id }}" {{ old('tahun_id', $kegiatan->tahun_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Periode <span class="required">*</span></label>
                        <select name="periode_id">
                            <option value="">-- Pilih Periode --</option>
                            @foreach($periodeList as $item)
                            <option value="{{ $item->id }}" {{ old('periode_id', $kegiatan->periode_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SECTION 2: TIMELINE ═══ --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-calendar-alt"></i>
                <h3>Timeline Kegiatan</h3>
                <span class="section-desc">Aktifkan tahapan yang digunakan</span>
            </div>
            <div class="section-body">

                {{-- TANGGAL KEGIATAN (selalu wajib) --}}
                <div class="tahapan-row tahapan-row-fixed">
                    <div class="tahapan-info">
                        <span class="tl-icon tl-kegiatan"><i class="fas fa-flag"></i></span>
                        <span class="tahapan-name">Tanggal Kegiatan</span>
                        <span class="tahapan-badge-wajib">Wajib</span>
                    </div>
                    <div class="tahapan-dates" style="display:grid">
                        <div class="date-input-wrap">
                            <label>Mulai <span style="color:#ef4444">*</span></label>
                            <input type="date" name="kegiatan_mulai"
                                value="{{ old('kegiatan_mulai', $kegiatan->kegiatan_mulai?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="date-input-wrap">
                            <label>Selesai <span style="color:#ef4444">*</span></label>
                            <input type="date" name="kegiatan_selesai"
                                value="{{ old('kegiatan_selesai', $kegiatan->kegiatan_selesai?->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                <div class="tahapan-divider"><span>Tahapan Kegiatan</span></div>

                @php
                    $phases = [
                        ['key' => 'survey',         'label' => 'Survey',         'icon' => 'fa-map-marker-alt', 'cls' => 'tl-survey'],
                        ['key' => 'pendaftaran',    'label' => 'Pendaftaran',    'icon' => 'fa-user-plus',      'cls' => 'tl-daftar'],
                        ['key' => 'verifikasi',     'label' => 'Verifikasi',     'icon' => 'fa-check-circle',   'cls' => 'tl-verifikasi'],
                        ['key' => 'setup_kelompok', 'label' => 'Setup Kelompok', 'icon' => 'fa-users-cog',      'cls' => 'tl-setup'],
                        ['key' => 'pelaksanaan',    'label' => 'Pelaksanaan',    'icon' => 'fa-play-circle',    'cls' => 'tl-pelaksanaan'],
                        ['key' => 'pelaporan',      'label' => 'Pelaporan',      'icon' => 'fa-file-alt',       'cls' => 'tl-pelaporan'],
                    ];
                @endphp

                @foreach($phases as $phase)
                @php
                    $t  = $tahapanByNama->get($phase['key']);
                    $on = old('tahapan.' . $phase['key'] . '.aktif', $t !== null ? '1' : null);
                @endphp
                <div class="tahapan-row {{ $on ? 'tahapan-active' : '' }}" id="tr-{{ $phase['key'] }}">
                    <div class="tahapan-info">
                        <span class="tl-icon {{ $phase['cls'] }}"><i class="fas {{ $phase['icon'] }}"></i></span>
                        <span class="tahapan-name">{{ $phase['label'] }}</span>
                        <label class="toggle-switch" style="flex-shrink:0; margin-left:auto;">
                            <input type="checkbox" name="tahapan[{{ $phase['key'] }}][aktif]" value="1"
                                {{ $on ? 'checked' : '' }}
                                onchange="toggleTahapan('{{ $phase['key'] }}', this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="tahapan-dates" id="td-{{ $phase['key'] }}"
                         style="{{ $on ? 'display:grid' : 'display:none' }}">
                        <div class="date-input-wrap">
                            <label>Mulai</label>
                            <input type="date" name="tahapan[{{ $phase['key'] }}][mulai]"
                                value="{{ old('tahapan.' . $phase['key'] . '.mulai', $t?->mulai?->format('Y-m-d')) }}"
                                {{ $on ? '' : 'disabled' }}>
                        </div>
                        <div class="date-input-wrap">
                            <label>Selesai</label>
                            <input type="date" name="tahapan[{{ $phase['key'] }}][selesai]"
                                value="{{ old('tahapan.' . $phase['key'] . '.selesai', $t?->selesai?->format('Y-m-d')) }}"
                                {{ $on ? '' : 'disabled' }}>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        {{-- ═══ SECTION 3: DOKUMEN PENDAFTARAN ═══ --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-folder-open"></i>
                <h3>Dokumen Pendaftaran</h3>
                <span class="section-desc">Dokumen yang wajib diunggah saat mendaftar</span>
            </div>
            <div class="section-body">
                @php
                    $fixedNames  = ['Bukti Pembayaran', 'Sertifikat Baca Quran'];
                    $fixedIcons  = ['fa-receipt', 'fa-book-open'];
                    $fixedDaftar = $dokPendaftaran->where('is_fixed', true)->values();
                    $customDaftar= $dokPendaftaran->where('is_fixed', false)->values();
                    $nextDaftarIdx = 2 + $customDaftar->count();
                @endphp
                <div class="doc-list" id="list-daftar">

                    {{-- FIXED items --}}
                    @foreach($fixedNames as $i => $namaFixed)
                    @php
                        $savedDoc = $fixedDaftar->get($i);
                        $isWajib  = $savedDoc ? $savedDoc->is_wajib : false;
                        $lblId    = 'lbl-daftar-' . $i;
                    @endphp
                    <div class="doc-item">
                        <div class="doc-icon"><i class="fas {{ $fixedIcons[$i] }}"></i></div>
                        <span class="doc-name">{{ $namaFixed }}</span>
                        <div class="wajib-wrap">
                            <span class="wajib-label {{ $isWajib ? 'on' : 'off' }}" id="{{ $lblId }}">
                                {{ $isWajib ? 'Wajib' : 'Tidak Wajib' }}
                            </span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="dok_daftar[{{ $i }}][wajib]" value="1"
                                    {{ $isWajib ? 'checked' : '' }}
                                    onchange="updateWajibLabel(this, '{{ $lblId }}')">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    @endforeach

                    {{-- CUSTOM items --}}
                    @foreach($customDaftar as $ci => $dok)
                    @php
                        $cidx  = 2 + $ci;
                        $lblId = 'lbl-daftar-' . $cidx;
                    @endphp
                    <div class="doc-item" data-custom="1">
                        <div class="doc-icon custom"><i class="fas fa-file"></i></div>
                        <input type="text" name="dok_daftar[{{ $cidx }}][nama]"
                            class="doc-name-input" value="{{ $dok->nama }}" required placeholder="Nama dokumen...">
                        <div class="wajib-wrap">
                            <span class="wajib-label {{ $dok->is_wajib ? 'on' : 'off' }}" id="{{ $lblId }}">
                                {{ $dok->is_wajib ? 'Wajib' : 'Tidak Wajib' }}
                            </span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="dok_daftar[{{ $cidx }}][wajib]" value="1"
                                    {{ $dok->is_wajib ? 'checked' : '' }}
                                    onchange="updateWajibLabel(this, '{{ $lblId }}')">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <button type="button" class="btn-remove-doc"
                            onclick="removeDokumen(this, 'list-daftar', 'dok_daftar')"
                            title="Hapus">&times;</button>
                    </div>
                    @endforeach

                </div>
                <button type="button" class="btn-add-doc" onclick="addDokumen('list-daftar', 'dok_daftar')">
                    <i class="fas fa-plus"></i> Tambah Dokumen
                </button>
            </div>
        </div>

        {{-- ═══ SECTION 4: SETUP LAPORAN ═══ --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-clipboard-list"></i>
                <h3>Setup Laporan</h3>
                <span class="section-desc">Dokumen yang wajib diserahkan sebagai laporan</span>
            </div>
            <div class="section-body">

                {{-- INDIVIDU --}}
                <div class="sub-section">
                    <div class="sub-section-header">
                        <i class="fas fa-user"></i>
                        Laporan Individu
                    </div>
                    <div class="sub-section-body">
                        @php
                            $fixedIndividuNames = ['Logbook'];
                            $fixedInd  = $lapIndividu->where('is_fixed', true)->values();
                            $customInd = $lapIndividu->where('is_fixed', false)->values();
                            $nextIndIdx = 1 + $customInd->count();
                        @endphp
                        <div class="doc-list" id="list-individu">

                            {{-- FIXED: Logbook --}}
                            @php
                                $savedLogbook = $fixedInd->get(0);
                                $logbookWajib = $savedLogbook ? $savedLogbook->is_wajib : false;
                            @endphp
                            <div class="doc-item">
                                <div class="doc-icon"><i class="fas fa-book"></i></div>
                                <span class="doc-name">Logbook</span>
                                <div class="wajib-wrap">
                                    <span class="wajib-label {{ $logbookWajib ? 'on' : 'off' }}" id="lbl-individu-0">
                                        {{ $logbookWajib ? 'Wajib' : 'Tidak Wajib' }}
                                    </span>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="lap_individu[0][wajib]" value="1"
                                            {{ $logbookWajib ? 'checked' : '' }}
                                            onchange="updateWajibLabel(this, 'lbl-individu-0')">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            {{-- CUSTOM Individu --}}
                            @foreach($customInd as $ci => $dok)
                            @php $cidx = 1 + $ci; $lblId = 'lbl-individu-' . $cidx; @endphp
                            <div class="doc-item" data-custom="1">
                                <div class="doc-icon custom"><i class="fas fa-file"></i></div>
                                <input type="text" name="lap_individu[{{ $cidx }}][nama]"
                                    class="doc-name-input" value="{{ $dok->nama }}" required placeholder="Nama dokumen...">
                                <div class="wajib-wrap">
                                    <span class="wajib-label {{ $dok->is_wajib ? 'on' : 'off' }}" id="{{ $lblId }}">
                                        {{ $dok->is_wajib ? 'Wajib' : 'Tidak Wajib' }}
                                    </span>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="lap_individu[{{ $cidx }}][wajib]" value="1"
                                            {{ $dok->is_wajib ? 'checked' : '' }}
                                            onchange="updateWajibLabel(this, '{{ $lblId }}')">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <button type="button" class="btn-remove-doc"
                                    onclick="removeDokumen(this, 'list-individu', 'lap_individu')"
                                    title="Hapus">&times;</button>
                            </div>
                            @endforeach

                        </div>
                        <button type="button" class="btn-add-doc" onclick="addDokumen('list-individu', 'lap_individu')">
                            <i class="fas fa-plus"></i> Tambah Dokumen Individu
                        </button>
                    </div>
                </div>

                {{-- KELOMPOK --}}
                <div class="sub-section">
                    <div class="sub-section-header">
                        <i class="fas fa-users"></i>
                        Laporan Kelompok
                    </div>
                    <div class="sub-section-body">
                        @php $nextKelIdx = $lapKelompok->count(); @endphp
                        <div class="doc-list" id="list-kelompok">

                            @if($lapKelompok->isEmpty())
                            <div id="kelompok-empty" style="padding:16px; text-align:center; color:var(--text-secondary); font-size:13px;">
                                <i class="fas fa-folder-plus" style="font-size:24px; color:var(--gray-border); display:block; margin-bottom:8px;"></i>
                                Belum ada dokumen kelompok. Klik "Tambah" untuk menambahkan.
                            </div>
                            @else
                            <div id="kelompok-empty" style="display:none;"></div>
                            @endif

                            @foreach($lapKelompok as $ki => $dok)
                            @php $lblId = 'lbl-kelompok-' . $ki; @endphp
                            <div class="doc-item" data-custom="1">
                                <div class="doc-icon custom"><i class="fas fa-file"></i></div>
                                <input type="text" name="lap_kelompok[{{ $ki }}][nama]"
                                    class="doc-name-input" value="{{ $dok->nama }}" required placeholder="Nama dokumen...">
                                <div class="wajib-wrap">
                                    <span class="wajib-label {{ $dok->is_wajib ? 'on' : 'off' }}" id="{{ $lblId }}">
                                        {{ $dok->is_wajib ? 'Wajib' : 'Tidak Wajib' }}
                                    </span>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="lap_kelompok[{{ $ki }}][wajib]" value="1"
                                            {{ $dok->is_wajib ? 'checked' : '' }}
                                            onchange="updateWajibLabel(this, '{{ $lblId }}')">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                                <button type="button" class="btn-remove-doc"
                                    onclick="removeDokumen(this, 'list-kelompok', 'lap_kelompok')"
                                    title="Hapus">&times;</button>
                            </div>
                            @endforeach

                        </div>
                        <button type="button" class="btn-add-doc" onclick="addDokumen('list-kelompok', 'lap_kelompok')">
                            <i class="fas fa-plus"></i> Tambah Dokumen Kelompok
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SECTION 5 — SETUP PENILAIAN --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-star-half-alt"></i>
                <h3>Setup Penilaian</h3>
                <span class="section-desc">Komponen dan format nilai akhir (0–100)</span>
            </div>
            <div class="section-body">

                {{-- KOMPONEN PENILAIAN --}}
                <div class="sub-section">
                    <div class="sub-section-header">
                        <i class="fas fa-tasks"></i>
                        Komponen Penilaian
                        <span style="margin-left:auto; font-size:12px; font-weight:600; color:var(--text-secondary);">
                            Total: <span id="total-persen" style="color:var(--maroon-main); transition:color .2s;">0%</span>
                        </span>
                    </div>
                    <div class="sub-section-body">
                        <div class="komponen-list" id="list-komponen">
                            @if($komponenList->isEmpty())
                            <div id="komponen-empty" style="padding:16px; text-align:center; color:var(--text-secondary); font-size:13px;">
                                <i class="fas fa-chart-pie" style="font-size:24px; color:var(--gray-border); display:block; margin-bottom:8px;"></i>
                                Belum ada komponen. Klik "Tambah" untuk menambahkan komponen penilaian.
                            </div>
                            @else
                            <div id="komponen-empty" style="display:none;"></div>
                            @endif
                        </div>
                        <div id="total-bar" class="total-bar" style="{{ $komponenList->isEmpty() ? 'display:none;' : '' }}">
                            <i class="fas fa-info-circle"></i>
                            Total persentase: <strong id="total-persen-bar">0%</strong>
                            <span id="total-hint"></span>
                        </div>
                        <button type="button" class="btn-add-doc" onclick="addKomponen()">
                            <i class="fas fa-plus"></i> Tambah Komponen
                        </button>
                    </div>
                </div>

                {{-- FORMAT NILAI / GRADE --}}
                <div class="sub-section">
                    <div class="sub-section-header">
                        <i class="fas fa-award"></i>
                        Format Nilai
                        <button type="button" class="btn-preset" onclick="presetGrade()">
                            <i class="fas fa-magic"></i> Isi Preset
                        </button>
                    </div>
                    <div class="sub-section-body">
                        <div class="grade-list" id="list-grade">
                            @if($gradeList->isEmpty())
                            <div id="grade-empty" style="padding:16px; text-align:center; color:var(--text-secondary); font-size:13px;">
                                <i class="fas fa-graduation-cap" style="font-size:24px; color:var(--gray-border); display:block; margin-bottom:8px;"></i>
                                Belum ada format nilai. Klik "Tambah" atau gunakan tombol "Isi Preset".
                            </div>
                            @else
                            <div id="grade-empty" style="display:none;"></div>
                            @endif
                        </div>
                        <button type="button" class="btn-add-doc" onclick="addGrade()">
                            <i class="fas fa-plus"></i> Tambah Grade
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- ACTION BAR --}}
        <div class="action-bar">
            <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection

@section('js')
<script>
    // ── Toggle tahapan kegiatan ──
    function toggleTahapan(key, active) {
        const datesDiv = document.getElementById('td-' + key);
        const row      = document.getElementById('tr-' + key);
        if (active) {
            datesDiv.style.display = 'grid';
            datesDiv.querySelectorAll('input').forEach(inp => { inp.disabled = false; });
            row.classList.add('tahapan-active');
        } else {
            datesDiv.style.display = 'none';
            datesDiv.querySelectorAll('input').forEach(inp => { inp.disabled = true; inp.value = ''; });
            row.classList.remove('tahapan-active');
        }
    }

    const counters = {
        'dok_daftar':   {{ $nextDaftarIdx }},
        'lap_individu': {{ $nextIndIdx }},
        'lap_kelompok': {{ $nextKelIdx }},
    };

    function updateWajibLabel(checkbox, labelId) {
        const label = document.getElementById(labelId);
        if (!label) return;
        if (checkbox.checked) {
            label.textContent = 'Wajib';
            label.classList.replace('off', 'on');
        } else {
            label.textContent = 'Tidak Wajib';
            label.classList.replace('on', 'off');
        }
    }

    function addDokumen(listId, fieldName) {
        const list    = document.getElementById(listId);
        const idx     = counters[fieldName]++;
        const labelId = `lbl-${fieldName}-${idx}`;

        const empty = document.getElementById('kelompok-empty');
        if (empty) empty.style.display = 'none';

        const item = document.createElement('div');
        item.className = 'doc-item';
        item.setAttribute('data-custom', '1');
        item.innerHTML = `
            <div class="doc-icon custom"><i class="fas fa-file"></i></div>
            <input type="text"
                   name="${fieldName}[${idx}][nama]"
                   class="doc-name-input"
                   placeholder="Nama dokumen..."
                   required>
            <div class="wajib-wrap">
                <span class="wajib-label off" id="${labelId}">Tidak Wajib</span>
                <label class="toggle-switch">
                    <input type="checkbox"
                           name="${fieldName}[${idx}][wajib]"
                           value="1"
                           onchange="updateWajibLabel(this, '${labelId}')">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <button type="button" class="btn-remove-doc"
                    onclick="removeDokumen(this, '${listId}', '${fieldName}')"
                    title="Hapus">&times;</button>
        `;
        list.appendChild(item);
        item.querySelector('input[type=text]').focus();
    }

    function removeDokumen(btn, listId, fieldName) {
        btn.closest('.doc-item').remove();
        const list    = document.getElementById(listId);
        const customs = list.querySelectorAll('[data-custom]');
        if (customs.length === 0) {
            const empty = document.getElementById('kelompok-empty');
            if (empty) empty.style.display = '';
        }
    }

    // ══════════════════════════════════════════════════
    // KOMPONEN PENILAIAN
    // ══════════════════════════════════════════════════
    let komponenIdx = {{ $komponenList->count() }};

    function addKomponen(nama = '', persen = '') {
        const list  = document.getElementById('list-komponen');
        const empty = document.getElementById('komponen-empty');
        if (empty) empty.style.display = 'none';

        const idx  = komponenIdx++;
        const item = document.createElement('div');
        item.className = 'komponen-item';
        item.dataset.idx = idx;
        item.innerHTML = `
            <div class="komponen-num">${list.querySelectorAll('.komponen-item').length + 1}</div>
            <input type="text"
                   name="komponen[${idx}][nama]"
                   class="komponen-nama"
                   placeholder="Nama komponen, misal: Kehadiran"
                   value="${nama}"
                   required>
            <div class="persen-wrap">
                <input type="number"
                       name="komponen[${idx}][persentase]"
                       class="persen-input"
                       placeholder="0"
                       min="0" max="100"
                       value="${persen}"
                       oninput="updateTotal()"
                       required>
                <span class="persen-suffix">%</span>
            </div>
            <button type="button" class="btn-remove-doc" onclick="removeKomponen(this)" title="Hapus">&times;</button>
        `;
        list.appendChild(item);
        if (!nama) item.querySelector('.komponen-nama').focus();
        updateTotal();
    }

    function removeKomponen(btn) {
        btn.closest('.komponen-item').remove();
        renumberKomponen();
        updateTotal();
        const list = document.getElementById('list-komponen');
        if (!list.querySelector('.komponen-item')) {
            document.getElementById('komponen-empty').style.display = '';
            document.getElementById('total-bar').style.display = 'none';
        }
    }

    function renumberKomponen() {
        document.querySelectorAll('#list-komponen .komponen-item').forEach((item, i) => {
            item.querySelector('.komponen-num').textContent = i + 1;
        });
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('#list-komponen .persen-input').forEach(inp => {
            total += parseInt(inp.value || 0);
        });
        const span    = document.getElementById('total-persen');
        const barSpan = document.getElementById('total-persen-bar');
        const hint    = document.getElementById('total-hint');
        const bar     = document.getElementById('total-bar');
        const hasItems = document.querySelector('#list-komponen .komponen-item');
        bar.style.display = hasItems ? '' : 'none';
        const color = total === 100 ? '#059669' : (total > 100 ? '#ef4444' : 'var(--maroon-main)');
        span.textContent    = total + '%';
        span.style.color    = color;
        barSpan.textContent = total + '%';
        barSpan.style.color = color;
        hint.textContent    = total === 100 ? '✓ Tepat 100%' : (total > 100 ? '⚠ Melebihi 100%' : `(kurang ${100 - total}%)`);
        hint.style.color    = color;
    }

    // ── Pre-populate komponen dari data DB ──
    @foreach($komponenList as $ki => $komp)
    (function() {
        const list  = document.getElementById('list-komponen');
        const idx   = {{ $ki }};
        const item  = document.createElement('div');
        item.className = 'komponen-item';
        item.dataset.idx = idx;
        item.innerHTML = `
            <div class="komponen-num">{{ $ki + 1 }}</div>
            <input type="text"
                   name="komponen[${idx}][nama]"
                   class="komponen-nama"
                   value="{{ addslashes($komp->nama) }}"
                   required>
            <div class="persen-wrap">
                <input type="number"
                       name="komponen[${idx}][persentase]"
                       class="persen-input"
                       min="0" max="100"
                       value="{{ $komp->persentase }}"
                       oninput="updateTotal()"
                       required>
                <span class="persen-suffix">%</span>
            </div>
            <button type="button" class="btn-remove-doc" onclick="removeKomponen(this)" title="Hapus">&times;</button>
        `;
        list.appendChild(item);
    })();
    @endforeach

    // ══════════════════════════════════════════════════
    // FORMAT NILAI / GRADE
    // ══════════════════════════════════════════════════
    let gradeIdx = {{ $gradeList->count() }};

    function addGrade(grade = '', min = '', max = '') {
        const list  = document.getElementById('list-grade');
        const empty = document.getElementById('grade-empty');
        if (empty) empty.style.display = 'none';
        const idx  = gradeIdx++;
        const item = document.createElement('div');
        item.className = 'grade-item';
        item.dataset.idx = idx;
        item.innerHTML = `
            <input type="text"
                   name="grade[${idx}][grade]"
                   class="grade-badge-input"
                   placeholder="A"
                   maxlength="5"
                   value="${grade}"
                   required>
            <span class="grade-label-sm">Nilai:</span>
            <input type="number"
                   name="grade[${idx}][nilai_min]"
                   class="nilai-range-input"
                   placeholder="Min"
                   min="0" max="100" step="0.01"
                   value="${min}"
                   required>
            <span class="grade-sep">–</span>
            <input type="number"
                   name="grade[${idx}][nilai_max]"
                   class="nilai-range-input"
                   placeholder="Max"
                   min="0" max="100" step="0.01"
                   value="${max}"
                   required>
            <button type="button" class="btn-remove-doc" onclick="removeGrade(this)" title="Hapus">&times;</button>
        `;
        list.appendChild(item);
        if (!grade) item.querySelector('.grade-badge-input').focus();
    }

    function removeGrade(btn) {
        btn.closest('.grade-item').remove();
        const list = document.getElementById('list-grade');
        if (!list.querySelector('.grade-item')) {
            document.getElementById('grade-empty').style.display = '';
        }
    }

    function presetGrade() {
        const list = document.getElementById('list-grade');
        list.querySelectorAll('.grade-item').forEach(el => el.remove());
        gradeIdx = 0;
        document.getElementById('grade-empty').style.display = 'none';
        [
            { grade: 'A',  min: 91,  max: 100 },
            { grade: 'A-', min: 80,  max: 90  },
            { grade: 'B+', min: 75,  max: 79  },
            { grade: 'B',  min: 70,  max: 74  },
            { grade: 'B-', min: 65,  max: 69  },
            { grade: 'C+', min: 60,  max: 64  },
            { grade: 'C',  min: 55,  max: 59  },
            { grade: 'D',  min: 40,  max: 54  },
            { grade: 'E',  min: 0,   max: 39  },
        ].forEach(p => addGrade(p.grade, p.min, p.max));
    }

    // ── Pre-populate grade dari data DB ──
    @foreach($gradeList as $gi => $gr)
    (function() {
        const list = document.getElementById('list-grade');
        const idx  = {{ $gi }};
        const item = document.createElement('div');
        item.className = 'grade-item';
        item.dataset.idx = idx;
        item.innerHTML = `
            <input type="text"
                   name="grade[${idx}][grade]"
                   class="grade-badge-input"
                   maxlength="5"
                   value="{{ addslashes($gr->grade) }}"
                   required>
            <span class="grade-label-sm">Nilai:</span>
            <input type="number"
                   name="grade[${idx}][nilai_min]"
                   class="nilai-range-input"
                   min="0" max="100" step="0.01"
                   value="{{ $gr->nilai_min }}"
                   required>
            <span class="grade-sep">–</span>
            <input type="number"
                   name="grade[${idx}][nilai_max]"
                   class="nilai-range-input"
                   min="0" max="100" step="0.01"
                   value="{{ $gr->nilai_max }}"
                   required>
            <button type="button" class="btn-remove-doc" onclick="removeGrade(this)" title="Hapus">&times;</button>
        `;
        list.appendChild(item);
    })();
    @endforeach

    // Run updateTotal setelah semua komponen ter-render
    updateTotal();
</script>
@endsection
