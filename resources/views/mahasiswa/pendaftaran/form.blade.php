@extends('layouts.mahasiswa')

@section('css')
<style>
    .pend-wrap { max-width: 900px; }
    .pend-page-header { margin-bottom: 24px; }
    .pend-page-header h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
    .pend-page-header p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    /* STATUS BANNER */
    .status-banner { display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-radius: 10px; margin-bottom: 24px; }
    .status-banner.info    { background: rgba(59,130,246,.07); border: 1px solid rgba(59,130,246,.25); color: #1e40af; }
    .status-banner.warning { background: rgba(245,158,11,.07); border: 1px solid rgba(245,158,11,.25); color: #92400e; }
    .status-banner.danger  { background: rgba(239,68,68,.07);  border: 1px solid rgba(239,68,68,.25);  color: #991b1b; }
    .status-banner.success { background: rgba(16,185,129,.07); border: 1px solid rgba(16,185,129,.25); color: #065f46; }
    .status-banner i { font-size: 20px; flex-shrink: 0; }
    .status-banner-text strong { display: block; font-size: 14px; font-weight: 700; }
    .status-banner-text span   { font-size: 13px; }

    /* SECTION CARD */
    .form-section { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .form-section-header { display: flex; align-items: center; gap: 12px; padding: 14px 20px; background: rgba(139,0,0,.04); border-bottom: 1px solid var(--border-light); }
    .form-section-icon { width: 34px; height: 34px; border-radius: 8px; background: rgba(139,0,0,.1); color: var(--maroon-main); display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .form-section-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
    .form-section-body { padding: 20px; }

    /* GRID */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    .col-span-2  { grid-column: span 2; }
    .col-span-3  { grid-column: span 3; }

    /* FORM ELEMENTS */
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .04em; }
    .form-group label .req { color: #ef4444; margin-left: 2px; }
    .form-control { padding: 10px 14px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; color: var(--text-primary); background: white; transition: border-color .2s; width: 100%; box-sizing: border-box; }
    .form-control:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .form-control:disabled, .form-control[readonly] { background: var(--gray-light, #f9fafb); color: var(--text-secondary); cursor: not-allowed; }
    textarea.form-control { min-height: 80px; resize: vertical; }
    .form-error { font-size: 12px; color: #ef4444; margin-top: 2px; }

    /* RADIO GROUP */
    .radio-group { display: flex; gap: 16px; flex-wrap: wrap; padding-top: 2px; }
    .radio-label { display: flex; align-items: center; gap: 7px; font-size: 13px; cursor: pointer; color: var(--text-primary); }
    .radio-label input[type=radio] { accent-color: var(--maroon-main); width: 15px; height: 15px; cursor: pointer; }
    #hamil-row { display: none; }
    .kegiatan-option-info { font-size: 12px; color: var(--text-secondary); margin-top: 4px; }

    /* ACTION BAR */
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 4px; flex-wrap: wrap; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 22px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; font-family: inherit; text-decoration: none; }
    .btn:disabled { opacity: .5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
    .btn-outline { background: white; border: 1.5px solid var(--gray-border); color: var(--text-primary); }
    .btn-outline:hover:not(:disabled) { border-color: var(--maroon-main); color: var(--maroon-main); }
    .btn-primary { background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main)); color: white; }
    .btn-primary:hover:not(:disabled) { box-shadow: 0 4px 14px rgba(139,0,0,.3); transform: translateY(-1px); }
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover:not(:disabled) { background: #059669; box-shadow: 0 4px 14px rgba(16,185,129,.3); transform: translateY(-1px); }
    .btn-danger  { background: #ef4444; color: white; padding: 6px 14px; font-size: 12px; }
    .btn-danger:hover:not(:disabled)  { background: #dc2626; }
    .btn-sm { padding: 7px 14px; font-size: 12px; }

    /* DOKUMEN SECTION */
    .dokumen-section { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .dokumen-section-header { display: flex; align-items: center; gap: 12px; padding: 14px 20px; background: rgba(16,185,129,.04); border-bottom: 1px solid var(--border-light); }
    .dokumen-section-icon { width: 34px; height: 34px; border-radius: 8px; background: rgba(16,185,129,.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .dokumen-section-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
    .dokumen-section-body  { padding: 20px; display: flex; flex-direction: column; gap: 14px; }

    .dokumen-item { border: 1px solid var(--gray-border); border-radius: 10px; overflow: hidden; }
    .dokumen-item.item-diterima { border-color: rgba(16,185,129,.3); }
    .dokumen-item.item-ditolak  { border-color: rgba(239,68,68,.3); }
    .dokumen-item-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: var(--gray-light, #f9fafb); gap: 10px; flex-wrap: wrap; }
    .dokumen-item-name { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .badge-wajib { display: inline-flex; align-items: center; gap: 3px; background: rgba(239,68,68,.1); color: #b91c1c; border: 1px solid rgba(239,68,68,.2); border-radius: 20px; padding: 2px 8px; font-size: 10px; font-weight: 700; }
    .badge-opsional { display: inline-flex; align-items: center; gap: 3px; background: rgba(107,114,128,.08); color: #6b7280; border: 1px solid rgba(107,114,128,.15); border-radius: 20px; padding: 2px 8px; font-size: 10px; font-weight: 700; }
    .dokumen-item-body { padding: 14px 16px; }

    /* VERIFIKASI STATUS */
    .verif-badge { display: inline-flex; align-items: center; gap: 5px; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700; margin-left: 6px; }
    .verif-badge.v-pending  { background: rgba(245,158,11,.1); color: #92400e; border: 1px solid rgba(245,158,11,.2); }
    .verif-badge.v-diterima { background: rgba(16,185,129,.1); color: #065f46; border: 1px solid rgba(16,185,129,.2); }
    .verif-badge.v-ditolak  { background: rgba(239,68,68,.1);  color: #b91c1c; border: 1px solid rgba(239,68,68,.2); }
    .tolak-reason { font-size: 12px; color: #b91c1c; margin-top: 8px; padding: 8px 12px; background: rgba(239,68,68,.04); border-radius: 8px; border: 1px solid rgba(239,68,68,.12); line-height: 1.5; }
    .tolak-reason i { margin-right: 4px; }

    /* File uploaded */
    .file-uploaded { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    .file-info { display: flex; align-items: center; gap: 10px; }
    .file-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .file-icon.fg  { background: rgba(16,185,129,.1); color: #059669; }
    .file-icon.fr  { background: rgba(239,68,68,.1); color: #b91c1c; }
    .file-icon.fy  { background: rgba(245,158,11,.1); color: #92400e; }
    .file-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
    .file-size { font-size: 11px; color: var(--text-secondary); }
    .file-actions { display: flex; align-items: center; gap: 8px; }

    /* Upload */
    .upload-area { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .upload-input-wrap { flex: 1; min-width: 200px; }
    .upload-input-wrap input[type=file] { width: 100%; font-size: 12px; color: var(--text-secondary); border: 1px dashed var(--gray-border); border-radius: 8px; padding: 8px 12px; background: white; cursor: pointer; box-sizing: border-box; }
    .upload-hint { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }

    /* Submit section */
    .submit-section { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .submit-info strong { font-size: 14px; font-weight: 700; color: var(--text-primary); display: block; margin-bottom: 4px; }
    .submit-info span   { font-size: 13px; color: var(--text-secondary); }
    .submit-info.blocked strong { color: #92400e; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 10000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal { background: white; border-radius: 14px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,.25); animation: modalIn .25s ease; overflow: hidden; }
    @keyframes modalIn { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; border-bottom: 1px solid var(--gray-border); }
    .modal-header h3 { font-size: 16px; font-weight: 700; margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; color: var(--text-secondary); cursor: pointer; }
    .modal-body { padding: 22px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 22px; border-top: 1px solid var(--gray-border); background: var(--gray-light, #f9fafb); }
    .confirm-center { text-align: center; }
    .confirm-center i { font-size: 46px; color: #10b981; margin-bottom: 12px; display: block; }
    .confirm-center p { font-size: 14px; color: var(--text-secondary); margin: 0 0 8px; }
    .confirm-center small { font-size: 12px; color: var(--text-secondary); }

    .no-dokumen-info { text-align: center; padding: 24px 16px; color: var(--text-secondary); font-size: 13px; }
    .no-dokumen-info i { font-size: 30px; color: var(--gray-border); display: block; margin-bottom: 10px; }

    /* TABS */
    .pend-tabs { display: flex; gap: 4px; border-bottom: 2px solid var(--border-light); margin-bottom: 20px; }
    .pend-tab-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border: none; background: none; font-size: 13px; font-weight: 600; color: var(--text-secondary); cursor: pointer; border-radius: 8px 8px 0 0; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: color .2s, border-color .2s; font-family: inherit; }
    .pend-tab-btn:hover { color: var(--maroon-main); background: rgba(139,0,0,.04); }
    .pend-tab-btn.active { color: var(--maroon-main); border-bottom-color: var(--maroon-main); background: rgba(139,0,0,.04); }
    .pend-tab-btn .tab-badge { display: inline-flex; align-items: center; justify-content: center; background: #ef4444; color: white; border-radius: 10px; font-size: 10px; font-weight: 700; min-width: 18px; height: 18px; padding: 0 5px; }
    .pend-tab-pane { display: none; }
    .pend-tab-pane.active { display: block; }

    @media (max-width: 768px) {
        .pend-wrap { padding: 16px; }
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        .col-span-2, .col-span-3 { grid-column: span 1; }
        .submit-section { flex-direction: column; align-items: flex-start; }
        .pend-tabs { gap: 2px; }
        .pend-tab-btn { padding: 9px 12px; font-size: 12px; }
    }
</style>
@endsection

@php
    $level = $mahasiswa->mahasiswa_level_id;
@endphp

@section('konten')
<div class="dashboard-content">
<div class="pend-wrap">

    <div class="pend-page-header">
        <h2><i class="fas fa-file-pen" style="color:var(--maroon-main); margin-right:8px;"></i>Form Pendaftaran KKA</h2>
        <p>Lengkapi data di bawah ini untuk mendaftar program Kuliah Kerja Amaliah</p>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,.08); border:1px solid rgba(16,185,129,.25); border-radius:10px; padding:12px 16px; margin-bottom:18px; color:#065f46; font-size:13px; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.25); border-radius:10px; padding:12px 16px; margin-bottom:18px; color:#b91c1c; font-size:13px; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-circle-xmark"></i> {{ session('error') }}
    </div>
    @endif

    {{-- STATUS BANNER --}}
    @if($level == 3)
    <div class="status-banner info">
        <i class="fas fa-hourglass-half"></i>
        <div class="status-banner-text">
            <strong>Menunggu Verifikasi</strong>
            <span>Pendaftaran dikirim {{ $pendaftaran?->submitted_at?->format('d/m/Y H:i') }}. Dokumen sedang diperiksa oleh panitia.</span>
        </div>
    </div>
    @elseif($level == 4 && $hasDitolak)
    <div class="status-banner danger">
        <i class="fas fa-triangle-exclamation"></i>
        <div class="status-banner-text">
            <strong>Ada Dokumen yang Ditolak</strong>
            <span>Beberapa dokumen perlu diperbaiki. Upload ulang dokumen yang ditolak, lalu kirim kembali pendaftaran Anda.</span>
        </div>
    </div>
    @elseif($level == 4 && $canResubmit)
    <div class="status-banner warning">
        <i class="fas fa-paper-plane"></i>
        <div class="status-banner-text">
            <strong>Siap Dikirim Ulang</strong>
            <span>Dokumen yang ditolak sudah di-upload ulang. Klik "Kirim Ulang" untuk mengirim kembali.</span>
        </div>
    </div>
    @elseif($level >= 5)
    <div class="status-banner success">
        <i class="fas fa-circle-check"></i>
        <div class="status-banner-text">
            <strong>Semua Dokumen Diterima</strong>
            <span>Selamat! Seluruh dokumen pendaftaran Anda telah diverifikasi dan diterima.</span>
        </div>
    </div>
    @elseif($pendaftaran && $pendaftaran->status === 'draft')
    <div class="status-banner warning">
        <i class="fas fa-pen-to-square"></i>
        <div class="status-banner-text">
            <strong>Draft Tersimpan</strong>
            <span>Terakhir disimpan {{ $pendaftaran->updated_at->diffForHumans() }}. Lengkapi data dan upload dokumen, lalu kirim pendaftaran.</span>
        </div>
    </div>
    @endif

    {{-- TAB NAVIGATION --}}
    @php
        $pendingDokCount = 0;
        if ($pendaftaran && $level == 2) {
            $pendingDokCount = $dokumenList->filter(fn($d) => $d->is_wajib && !$uploadedDokumen->has($d->id))->count();
        } elseif ($pendaftaran && $level == 4) {
            $pendingDokCount = $uploadedDokumen->where('status', 'ditolak')->count();
        }
    @endphp
    <div class="pend-tabs">
        <button type="button" class="pend-tab-btn active" onclick="switchTab('data-diri', this)">
            <i class="fas fa-id-card"></i> Data Diri
        </button>
        <button type="button" class="pend-tab-btn" onclick="switchTab('upload-dokumen', this)">
            <i class="fas fa-file-arrow-up"></i> Upload Dokumen
            @if($pendingDokCount > 0)
                <span class="tab-badge">{{ $pendingDokCount }}</span>
            @endif
        </button>
    </div>

    {{-- TAB: DATA DIRI --}}
    <div class="pend-tab-pane active" id="tab-data-diri">

    {{-- FORM DATA --}}
    <form id="pendaftaranForm" action="{{ route('mahasiswa.pendaftaran.save') }}" method="POST" autocomplete="off">
        @csrf

        {{-- SECTION 1: Pilih Kegiatan --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="form-section-title">Pilih Kegiatan KKA</div>
            </div>
            <div class="form-section-body">
                <div class="form-group">
                    <label>Kegiatan KKA <span class="req">*</span></label>
                    <select name="kegiatan_id" class="form-control" {{ $isFormReadOnly ? 'disabled' : '' }}>
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($kegiatanList as $keg)
                            <option value="{{ $keg->id }}" {{ old('kegiatan_id', $pendaftaran?->kegiatan_id) == $keg->id ? 'selected' : '' }}>
                                {{ $keg->nama }} ({{ $keg->jenisKka?->nama }} | {{ $keg->tahun?->nama }} | {{ $keg->periode?->nama }})
                            </option>
                        @endforeach
                    </select>
                    @error('kegiatan_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- SECTION 2: Identitas Diri --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-id-card"></i></div>
                <div class="form-section-title">Identitas Diri</div>
            </div>
            <div class="form-section-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Tempat Lahir <span class="req">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pendaftaran?->tempat_lahir) }}" placeholder="Contoh: Kendari" {{ $isFormReadOnly ? 'readonly' : '' }}>
                        @error('tempat_lahir')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="req">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pendaftaran?->tanggal_lahir?->format('Y-m-d')) }}" {{ $isFormReadOnly ? 'readonly' : '' }}>
                        @error('tanggal_lahir')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="req">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $pendaftaran?->jenis_kelamin) === 'L' ? 'checked' : '' }} onchange="toggleHamil(this.value)" {{ $isFormReadOnly ? 'disabled' : '' }}>
                                <i class="fas fa-mars" style="color:#3b82f6"></i> Laki-laki
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $pendaftaran?->jenis_kelamin) === 'P' ? 'checked' : '' }} onchange="toggleHamil(this.value)" {{ $isFormReadOnly ? 'disabled' : '' }}>
                                <i class="fas fa-venus" style="color:#ec4899"></i> Perempuan
                            </label>
                        </div>
                        @error('jenis_kelamin')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Golongan Darah</label>
                        <select name="golongan_darah" class="form-control" {{ $isFormReadOnly ? 'disabled' : '' }}>
                            <option value="">-- Pilih --</option>
                            @foreach(['A','B','AB','O','Tidak Tahu'] as $gd)
                                <option value="{{ $gd }}" {{ old('golongan_darah', $pendaftaran?->golongan_darah) === $gd ? 'selected' : '' }}>{{ $gd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-span-2">
                        <label>Alamat Lengkap <span class="req">*</span></label>
                        <textarea name="alamat" class="form-control" placeholder="Jalan, Kelurahan, Kecamatan, Kota/Kabupaten" {{ $isFormReadOnly ? 'readonly' : '' }}>{{ old('alamat', $pendaftaran?->alamat) }}</textarea>
                        @error('alamat')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp <span class="req">*</span></label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pendaftaran?->no_hp) }}" placeholder="081234567890" maxlength="20" {{ $isFormReadOnly ? 'readonly' : '' }}>
                        @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Data Akademik --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="form-section-title">Data Akademik</div>
            </div>
            <div class="form-section-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label>Semester Saat Ini <span class="req">*</span></label>
                        <select name="semester" class="form-control" {{ $isFormReadOnly ? 'disabled' : '' }}>
                            <option value="">-- Pilih --</option>
                            @for($s = 1; $s <= 14; $s++)
                                <option value="{{ $s }}" {{ old('semester', $pendaftaran?->semester) == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                            @endfor
                        </select>
                        @error('semester')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>SKS yang Telah Ditempuh <span class="req">*</span></label>
                        <input type="number" name="sks_ditempuh" class="form-control" value="{{ old('sks_ditempuh', $pendaftaran?->sks_ditempuh) }}" min="0" max="250" {{ $isFormReadOnly ? 'readonly' : '' }}>
                        @error('sks_ditempuh')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>IPK Terakhir <span class="req">*</span></label>
                        <input type="number" name="ipk" class="form-control" value="{{ old('ipk', $pendaftaran?->ipk) }}" min="0" max="4" step="0.01" {{ $isFormReadOnly ? 'readonly' : '' }}>
                        @error('ipk')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: Ukuran Baju --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-shirt"></i></div>
                <div class="form-section-title">Ukuran Baju</div>
            </div>
            <div class="form-section-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Ukuran Baju <span class="req">*</span></label>
                        <select name="ukuran_baju" class="form-control" {{ $isFormReadOnly ? 'disabled' : '' }}>
                            <option value="">-- Pilih Ukuran --</option>
                            @foreach(['XS','S','M','L','XL','XXL','XXXL'] as $uk)
                                <option value="{{ $uk }}" {{ old('ukuran_baju', $pendaftaran?->ukuran_baju) === $uk ? 'selected' : '' }}>{{ $uk }}</option>
                            @endforeach
                        </select>
                        @error('ukuran_baju')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: Informasi Kesehatan --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon"><i class="fas fa-heart-pulse"></i></div>
                <div class="form-section-title">Informasi Kesehatan</div>
            </div>
            <div class="form-section-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Penyakit yang Diderita</label>
                        <textarea name="penyakit_diderita" class="form-control" placeholder="Tuliskan penyakit yang diderita, atau 'Tidak ada'" {{ $isFormReadOnly ? 'readonly' : '' }}>{{ old('penyakit_diderita', $pendaftaran?->penyakit_diderita) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Catatan Kesehatan Lainnya</label>
                        <textarea name="catatan_kesehatan" class="form-control" placeholder="Alergi obat, kondisi khusus, dll. (opsional)" {{ $isFormReadOnly ? 'readonly' : '' }}>{{ old('catatan_kesehatan', $pendaftaran?->catatan_kesehatan) }}</textarea>
                    </div>
                    <div class="form-group" id="hamil-row">
                        <label>Sedang Hamil? <span class="req">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="sedang_hamil" value="1" {{ old('sedang_hamil', $pendaftaran?->sedang_hamil) == '1' ? 'checked' : '' }} {{ $isFormReadOnly ? 'disabled' : '' }}>
                                <i class="fas fa-check" style="color:#10b981"></i> Ya
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="sedang_hamil" value="0" {{ old('sedang_hamil', $pendaftaran?->sedang_hamil) === false || old('sedang_hamil', $pendaftaran?->sedang_hamil) == '0' ? 'checked' : '' }} {{ $isFormReadOnly ? 'disabled' : '' }}>
                                <i class="fas fa-times" style="color:#ef4444"></i> Tidak
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOMBOL SIMPAN (hanya level 2 / draft) --}}
        @if(!$isFormReadOnly)
        <div class="form-actions" style="margin-bottom: 28px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Data</button>
            @if($pendaftaran)
            <button type="button" class="btn btn-outline" onclick="switchTabByName('upload-dokumen')">
                <i class="fas fa-arrow-right"></i> Lanjut ke Upload Dokumen
            </button>
            @endif
        </div>
        @endif
    </form>

    @if(!$pendaftaran && $level == 2)
    <div style="background:rgba(59,130,246,.07); border:1px solid rgba(59,130,246,.2); border-radius:10px; padding:16px 20px; margin-bottom:24px; font-size:13px; color:#1e40af; display:flex; gap:12px; align-items:center;">
        <i class="fas fa-circle-info" style="font-size:18px; flex-shrink:0;"></i>
        <div>Simpan data pendaftaran terlebih dahulu, lalu bagian upload dokumen akan tersedia.</div>
    </div>
    @endif

    </div>{{-- end tab-data-diri --}}

    {{-- TAB: UPLOAD DOKUMEN --}}
    <div class="pend-tab-pane" id="tab-upload-dokumen">

    {{-- ═══════════════════════════════════
         UPLOAD DOKUMEN
    ═══════════════════════════════════ --}}
    @if($pendaftaran)
    <div class="dokumen-section">
        <div class="dokumen-section-header">
            <div class="dokumen-section-icon"><i class="fas fa-file-arrow-up"></i></div>
            <div>
                <div class="dokumen-section-title">Dokumen Persyaratan</div>
                @if($level == 4 && $hasDitolak)
                <div style="font-size:12px; color:#b91c1c; margin-top:2px; font-weight:600;">
                    Upload ulang dokumen yang ditolak, lalu kirim kembali pendaftaran.
                </div>
                @elseif($level == 2)
                <div style="font-size:12px; color:var(--text-secondary); margin-top:2px;">
                    Upload semua dokumen yang disyaratkan. Dokumen berlabel <strong>Wajib</strong> harus diupload sebelum mengirim.
                </div>
                @endif
            </div>
        </div>
        <div class="dokumen-section-body">
            @if($dokumenList->isEmpty())
                <div class="no-dokumen-info">
                    <i class="fas fa-folder-open"></i>
                    Tidak ada dokumen persyaratan yang ditetapkan untuk kegiatan ini.
                </div>
            @else
                @foreach($dokumenList as $dok)
                @php
                    $uploaded = $uploadedDokumen->get($dok->id);
                    $status   = $uploaded?->status ?? null;
                    $itemClass = $status === 'diterima' ? 'item-diterima' : ($status === 'ditolak' ? 'item-ditolak' : '');
                @endphp
                <div class="dokumen-item {{ $itemClass }}">
                    <div class="dokumen-item-header">
                        <div class="dokumen-item-name">
                            <i class="fas fa-file-lines" style="color:var(--text-secondary);"></i>
                            {{ $dok->nama }}
                            {{-- Badge status verifikasi --}}
                            @if($status === 'diterima')
                                <span class="verif-badge v-diterima"><i class="fas fa-circle-check"></i> Diterima</span>
                            @elseif($status === 'ditolak')
                                <span class="verif-badge v-ditolak"><i class="fas fa-circle-xmark"></i> Ditolak</span>
                            @elseif($status === 'pending' && $level >= 3)
                                <span class="verif-badge v-pending"><i class="fas fa-clock"></i> Menunggu</span>
                            @endif
                        </div>
                        @if($dok->is_wajib)
                            <span class="badge-wajib"><i class="fas fa-asterisk" style="font-size:8px;"></i> Wajib</span>
                        @else
                            <span class="badge-opsional">Opsional</span>
                        @endif
                    </div>
                    <div class="dokumen-item-body">
                        @if($uploaded)
                            @php
                                $ext = pathinfo($uploaded->file_name, PATHINFO_EXTENSION);
                                $iconMap = ['pdf' => 'fa-file-pdf', 'jpg' => 'fa-file-image', 'jpeg' => 'fa-file-image', 'png' => 'fa-file-image'];
                                $iconColor = $status === 'diterima' ? 'fg' : ($status === 'ditolak' ? 'fr' : 'fy');
                            @endphp

                            {{-- Alasan penolakan --}}
                            @if($status === 'ditolak' && $uploaded->catatan_verifikasi)
                            <div class="tolak-reason">
                                <i class="fas fa-comment-dots"></i> <strong>Alasan:</strong> {{ $uploaded->catatan_verifikasi }}
                            </div>
                            @endif

                            <div class="file-uploaded" style="{{ $status === 'ditolak' && $uploaded->catatan_verifikasi ? 'margin-top:10px;' : '' }}">
                                <div class="file-info">
                                    <div class="file-icon {{ $iconColor }}">
                                        <i class="fas {{ $iconMap[$ext] ?? 'fa-file' }}"></i>
                                    </div>
                                    <div>
                                        <div class="file-name">{{ $uploaded->file_name }}</div>
                                        <div class="file-size">{{ $uploaded->file_size_formatted }}</div>
                                    </div>
                                </div>
                                <div class="file-actions">
                                    <a href="{{ Storage::url($uploaded->file_path) }}" target="_blank" class="btn btn-outline btn-sm">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    {{-- Hapus: level 2 (semua kecuali diterima) atau level 4 (hanya ditolak) --}}
                                    @if(($level == 2 && $status !== 'diterima') || ($level == 4 && $status === 'ditolak'))
                                    <form action="{{ route('mahasiswa.pendaftaran.dokumen.hapus', $uploaded->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            </div>

                        @else
                            {{-- Belum diupload --}}
                            @if($level == 2 || $level == 4)
                            <form action="{{ route('mahasiswa.pendaftaran.dokumen.upload', $dok->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-area">
                                    <div class="upload-input-wrap">
                                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="upload-hint">PDF, JPG, atau PNG &mdash; maks. 5 MB</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
                                </div>
                            </form>
                            @else
                            <div style="font-size:13px; color:var(--text-secondary);">
                                <i class="fas fa-minus-circle" style="color:var(--gray-border);"></i> Tidak diupload
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- TOMBOL KIRIM / KIRIM ULANG --}}
    @if($level == 2)
    <div class="submit-section {{ $allWajibUploaded ? '' : 'blocked' }}">
        <div class="submit-info {{ $allWajibUploaded ? '' : 'blocked' }}">
            @if($allWajibUploaded)
                <strong><i class="fas fa-circle-check" style="color:#10b981; margin-right:6px;"></i>Semua dokumen wajib sudah diupload</strong>
                <span>Anda dapat mengirimkan pendaftaran. Setelah dikirim, data tidak dapat diubah.</span>
            @else
                <strong><i class="fas fa-triangle-exclamation" style="color:#f59e0b; margin-right:6px;"></i>Dokumen wajib belum lengkap</strong>
                <span>Upload semua dokumen berlabel <strong>Wajib</strong> untuk mengaktifkan tombol kirim.</span>
            @endif
        </div>
        <button type="button" class="btn btn-success" {{ $allWajibUploaded ? '' : 'disabled' }} onclick="confirmSubmit()">
            <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
        </button>
    </div>
    @elseif($canResubmit)
    <div class="submit-section">
        <div class="submit-info">
            <strong><i class="fas fa-rotate" style="color:#3b82f6; margin-right:6px;"></i>Dokumen sudah diperbaiki</strong>
            <span>Kirim ulang pendaftaran untuk diverifikasi kembali oleh panitia.</span>
        </div>
        <button type="button" class="btn btn-success" onclick="confirmSubmit()">
            <i class="fas fa-paper-plane"></i> Kirim Ulang
        </button>
    </div>
    @endif

    @else
    <div style="background:rgba(59,130,246,.07); border:1px solid rgba(59,130,246,.2); border-radius:10px; padding:16px 20px; margin-bottom:24px; font-size:13px; color:#1e40af; display:flex; gap:12px; align-items:center;">
        <i class="fas fa-circle-info" style="font-size:18px; flex-shrink:0;"></i>
        <div>Silakan isi dan simpan <strong>Data Diri</strong> terlebih dahulu untuk dapat mengupload dokumen.</div>
    </div>
    @endif {{-- end if $pendaftaran --}}

    </div>{{-- end tab-upload-dokumen --}}

</div>
</div>

{{-- MODAL KONFIRMASI --}}
@if($pendaftaran && ($level == 2 || $canResubmit))
<div class="modal-overlay" id="modal-confirm-submit">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-paper-plane" style="color:#10b981; margin-right:8px;"></i>{{ $level == 4 ? 'Kirim Ulang Pendaftaran' : 'Kirim Pendaftaran' }}</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="confirm-center">
                <i class="fas fa-circle-check"></i>
                <p>Apakah Anda yakin ingin {{ $level == 4 ? 'mengirim ulang' : 'mengirimkan' }} pendaftaran?</p>
                <small>Dokumen akan diperiksa kembali oleh panitia.</small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal()">Periksa Lagi</button>
            <form action="{{ route('mahasiswa.pendaftaran.submit') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Ya, Kirim Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('js')
<script>
    function toggleHamil(val) {
        const row = document.getElementById('hamil-row');
        if (!row) return;
        row.style.display = val === 'P' ? 'flex' : 'none';
        if (val !== 'P') row.querySelectorAll('input[type=radio]').forEach(r => r.checked = false);
    }
    document.addEventListener('DOMContentLoaded', function () {
        const checked = document.querySelector('input[name="jenis_kelamin"]:checked');
        if (checked) toggleHamil(checked.value);
    });

    function switchTab(tabName, btn) {
        document.querySelectorAll('.pend-tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.pend-tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tabName)?.classList.add('active');
        btn.classList.add('active');
    }
    function switchTabByName(tabName) {
        const btn = document.querySelector('.pend-tab-btn[onclick*="' + tabName + '"]');
        if (btn) switchTab(tabName, btn);
    }

    function confirmSubmit() { document.getElementById('modal-confirm-submit')?.classList.add('active'); }
    function closeModal() { document.getElementById('modal-confirm-submit')?.classList.remove('active'); }
    document.getElementById('modal-confirm-submit')?.addEventListener('click', function(e) { if (e.target === this) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection
