@extends('layouts.users')

@section('css')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .form-wrap { max-width:920px; }
    .page-header { margin-bottom:20px; }
    .page-header h2 { font-size:20px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .card { background:white; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); padding:24px; margin-bottom:20px; }
    .card-header { display:flex; align-items:center; gap:10px; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--border-light); }
    .card-header-icon { width:34px; height:34px; border-radius:8px; background:rgba(139,0,0,.1); color:var(--maroon-main); display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
    .card-header-title { font-size:14px; font-weight:700; color:var(--text-primary); }

    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    @media(max-width:640px) { .form-grid-2 { grid-template-columns:1fr; } }

    .form-group { display:flex; flex-direction:column; gap:6px; margin-bottom:16px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label { font-size:12px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.04em; }
    .form-group label .req { color:#ef4444; margin-left:2px; }
    .form-control { padding:10px 14px; border:1px solid var(--gray-border); border-radius:8px; font-size:13px; font-family:inherit; color:var(--text-primary); background:white; width:100%; box-sizing:border-box; transition:border-color .2s; }
    .form-control:focus { outline:none; border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .form-error { font-size:12px; color:#ef4444; margin-top:2px; }
    .form-hint  { font-size:11px; color:var(--text-secondary); margin-top:2px; }

    .radio-group { display:flex; gap:16px; padding-top:2px; }
    .radio-label { display:flex; align-items:center; gap:7px; font-size:13px; cursor:pointer; color:var(--text-primary); }
    .radio-label input { accent-color:var(--maroon-main); width:15px; height:15px; }

    .checkbox-label { display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer; color:var(--text-primary); padding-top:4px; }
    .checkbox-label input[type=checkbox] { accent-color:#ef4444; width:16px; height:16px; }

    .thumb-preview { margin-top:8px; }
    .thumb-preview img { max-width:260px; max-height:150px; border-radius:8px; border:1px solid var(--border-light); object-fit:cover; }

    /* Quill editor */
    .editor-wrapper { border:1px solid var(--gray-border); border-radius:8px; overflow:hidden; }
    .editor-wrapper.focused { border-color:var(--maroon-main); box-shadow:0 0 0 3px rgba(165,42,42,.1); }
    .editor-wrapper .ql-toolbar { border:none; border-bottom:1px solid var(--gray-border); background:#fafafa; }
    .editor-wrapper .ql-container { border:none; font-family:'Segoe UI',Arial,sans-serif; font-size:14px; }
    .editor-wrapper .ql-editor { min-height:340px; line-height:1.7; color:var(--text-primary); }
    .editor-wrapper .ql-editor p { margin-bottom:8px; }
    .editor-wrapper .ql-editor img { max-width:100%; height:auto; border-radius:6px; }

    .form-actions { display:flex; justify-content:flex-end; gap:10px; padding-top:4px; flex-wrap:wrap; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:white; }
    .btn-primary:hover { box-shadow:0 4px 14px rgba(139,0,0,.3); transform:translateY(-1px); }
    .btn-outline { background:white; border:1.5px solid var(--gray-border); color:var(--text-primary); }
    .btn-outline:hover { border-color:var(--maroon-main); color:var(--maroon-main); }
</style>
@endsection

@section('konten')
<div class="dashboard-content" style="padding:24px;">
<div class="form-wrap">

    <div class="page-header">
        <h2>
            <i class="fas fa-bullhorn" style="color:var(--maroon-main);margin-right:8px;"></i>
            {{ $pengumuman ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
        </h2>
        <p>{{ $pengumuman ? 'Perbarui informasi pengumuman.' : 'Buat pengumuman baru.' }}</p>
    </div>

    <form action="{{ $pengumuman ? route('pengumuman.update', $pengumuman) : route('pengumuman.store') }}"
          method="POST" enctype="multipart/form-data" id="pengumumanForm">
        @csrf
        @if($pengumuman) @method('PUT') @endif

        {{-- Hidden textarea untuk nilai konten --}}
        <textarea name="konten" id="konten-hidden" style="display:none;">{{ old('konten', $pengumuman?->konten) }}</textarea>

        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-bullhorn"></i></div>
                <div class="card-header-title">Informasi Pengumuman</div>
            </div>

            <div class="form-group">
                <label>Judul Pengumuman <span class="req">*</span></label>
                <input type="text" name="judul" class="form-control"
                       value="{{ old('judul', $pengumuman?->judul) }}"
                       placeholder="Masukkan judul pengumuman...">
                @error('judul')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Isi Pengumuman <span class="req">*</span></label>
                <div class="editor-wrapper" id="editor-wrapper">
                    <div id="quill-editor"></div>
                </div>
                @error('konten')<div class="form-error" style="margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Tanggal Mulai <span class="req">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="{{ old('tanggal_mulai', $pengumuman?->tanggal_mulai?->format('Y-m-d')) }}">
                    @error('tanggal_mulai')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                           value="{{ old('tanggal_selesai', $pengumuman?->tanggal_selesai?->format('Y-m-d')) }}">
                    <div class="form-hint">Kosongkan jika tidak ada batas waktu.</div>
                    @error('tanggal_selesai')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label>Status <span class="req">*</span></label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="status" value="aktif"
                               {{ old('status', $pengumuman?->status ?? 'aktif') === 'aktif' ? 'checked' : '' }}>
                        <i class="fas fa-circle-check" style="color:#10b981"></i> Aktif
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="status" value="tidak_aktif"
                               {{ old('status', $pengumuman?->status) === 'tidak_aktif' ? 'checked' : '' }}>
                        <i class="fas fa-circle-xmark" style="color:#6b7280"></i> Tidak Aktif
                    </label>
                </div>
                @error('status')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Prioritas</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="is_penting" value="1"
                           {{ old('is_penting', $pengumuman?->is_penting) ? 'checked' : '' }}>
                    <i class="fas fa-exclamation-triangle" style="color:#ef4444"></i>
                    <span>Tandai sebagai <strong>Pengumuman Penting</strong></span>
                </label>
                <div class="form-hint">Pengumuman penting akan ditampilkan lebih menonjol di halaman utama.</div>
            </div>
        </div>

        {{-- Gambar Opsional --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-image"></i></div>
                <div class="card-header-title">Gambar / Banner (Opsional)</div>
            </div>

            <div class="form-group">
                <label>Upload Gambar</label>
                <input type="file" name="gambar" class="form-control" accept="image/*"
                       onchange="previewThumb(this)">
                <div class="form-hint">Gambar pendukung pengumuman. Format JPG/PNG/WebP, maks. 2 MB.</div>
                @error('gambar')<div class="form-error">{{ $message }}</div>@enderror
                <div class="thumb-preview">
                    @if($pengumuman?->gambar)
                        <img src="{{ Storage::url($pengumuman->gambar) }}" id="preview-thumb" alt="Gambar">
                    @else
                        <img id="preview-thumb" style="display:none;">
                    @endif
                </div>
                @if($pengumuman?->gambar)
                <div style="margin-top:8px;">
                    <label class="checkbox-label" style="color:#ef4444;">
                        <input type="checkbox" name="hapus_gambar" value="1">
                        <i class="fas fa-trash"></i> Hapus gambar saat ini
                    </label>
                </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('pengumuman.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-floppy-disk"></i>
                {{ $pengumuman ? 'Simpan Perubahan' : 'Simpan Pengumuman' }}
            </button>
        </div>
    </form>

</div>
</div>
@endsection

@section('js')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: {
            container: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ align: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean']
            ],
            handlers: { image: imageUploadHandler }
        }
    }
});

// Load konten yang sudah ada
var initialContent = document.getElementById('konten-hidden').value;
if (initialContent && initialContent.trim() !== '') {
    quill.clipboard.dangerouslyPasteHTML(initialContent);
}

// Fokus border
quill.on('selection-change', function(range) {
    var wrapper = document.getElementById('editor-wrapper');
    if (range) { wrapper.classList.add('focused'); }
    else { wrapper.classList.remove('focused'); }
});

// Sync ke hidden textarea saat submit
document.getElementById('pengumumanForm').addEventListener('submit', function() {
    var content = quill.root.innerHTML;
    document.getElementById('konten-hidden').value = (content === '<p><br></p>') ? '' : content;
});

// Handler upload gambar ke server
function imageUploadHandler() {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/jpeg,image/png,image/webp,image/gif');
    input.click();
    input.onchange = async function () {
        var file = input.files[0];
        if (!file) return;
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        try {
            var resp = await fetch('{{ route("upload.gambar-konten") }}', { method: 'POST', body: formData });
            var data = await resp.json();
            if (data.location) {
                var range = quill.getSelection() || { index: quill.getLength() };
                quill.insertEmbed(range.index, 'image', data.location);
                quill.setSelection(range.index + 1);
            }
        } catch (e) {
            alert('Gagal mengupload gambar. Coba lagi.');
        }
    };
}

function previewThumb(input) {
    var img = document.getElementById('preview-thumb');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { img.src = e.target.result; img.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
