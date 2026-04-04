@extends('layouts.users')

@section('css')
<style>
    .form-wrap { max-width:700px; }
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
    .form-hint  { font-size:11px; color:var(--text-secondary); margin-top:2px; line-height:1.5; }

    .icon-preview { display:inline-flex; align-items:center; gap:8px; margin-top:8px; font-size:13px; color:var(--text-secondary); }
    .icon-preview i { font-size:18px; color:var(--maroon-main); }

    .checkbox-label { display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer; color:var(--text-primary); padding-top:4px; }
    .checkbox-label input[type=checkbox] { accent-color:var(--maroon-main); width:16px; height:16px; }

    .form-actions { display:flex; justify-content:flex-end; gap:10px; padding-top:4px; flex-wrap:wrap; }
    .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-primary { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:white; }
    .btn-primary:hover { box-shadow:0 4px 14px rgba(139,0,0,.3); transform:translateY(-1px); }
    .btn-outline { background:white; border:1.5px solid var(--gray-border); color:var(--text-primary); }
    .btn-outline:hover { border-color:var(--maroon-main); color:var(--maroon-main); }

    .url-hint-box { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px 14px; margin-top:10px; font-size:12px; color:#166534; }
    .url-hint-box strong { display:block; margin-bottom:4px; }
    .url-hint-box code { background:#dcfce7; padding:2px 6px; border-radius:4px; font-family:monospace; }
</style>
@endsection

@section('konten')
<div class="dashboard-content" style="padding:24px;">
<div class="form-wrap">

    <div class="page-header">
        <h2>
            <i class="fas fa-bars" style="color:var(--maroon-main);margin-right:8px;"></i>
            {{ $menu ? 'Edit Menu' : 'Tambah Menu' }}
        </h2>
        <p>{{ $menu ? 'Perbarui data menu navigasi.' : 'Buat menu atau sub-menu baru untuk home page.' }}</p>
    </div>

    <form action="{{ $menu ? route('menu.update', $menu) : route('menu.store') }}" method="POST" id="menuForm">
        @csrf
        @if($menu) @method('PUT') @endif

        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="fas fa-pen-to-square"></i></div>
                <div class="card-header-title">Informasi Menu</div>
            </div>

            <div class="form-group">
                <label>Label Menu <span class="req">*</span></label>
                <input type="text" name="label" class="form-control"
                       value="{{ old('label', $menu?->label) }}"
                       placeholder="Contoh: Tentang KKA, Kontak, Panduan...">
                @error('label')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>URL Tujuan</label>
                <input type="text" name="url" id="urlInput" class="form-control"
                       value="{{ old('url', $menu?->url) }}"
                       placeholder="Contoh: /halaman/tentang-kka atau https://example.com">
                <div class="form-hint">
                    Kosongkan jika menu ini hanya sebagai induk (dropdown tanpa link).<br>
                    Untuk halaman yang dibuat di sistem, gunakan: <code>/halaman/{slug}</code>
                </div>
                @error('url')<div class="form-error">{{ $message }}</div>@enderror

                <div class="url-hint-box">
                    <strong><i class="fas fa-lightbulb"></i> Contoh URL yang valid:</strong>
                    <code>/halaman/tentang-kka</code> — halaman internal<br>
                    <code>/halaman/panduan-pendaftaran</code> — halaman internal<br>
                    <code>https://umkendari.ac.id</code> — tautan eksternal<br>
                    <code>/</code> — beranda
                </div>
            </div>

            <div class="form-group">
                <label>Menu Induk (opsional)</label>
                <select name="parent_id" class="form-control">
                    <option value="">— Tidak ada (menu top-level) —</option>
                    @foreach($parents as $parent)
                    <option value="{{ $parent->id }}"
                        {{ old('parent_id', $menu?->parent_id) == $parent->id ? 'selected' : '' }}>
                        {{ $parent->label }}
                    </option>
                    @endforeach
                </select>
                <div class="form-hint">Pilih menu induk untuk menjadikan ini sub-menu (dropdown).</div>
                @error('parent_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Icon (opsional)</label>
                    <input type="text" name="icon" id="iconInput" class="form-control"
                           value="{{ old('icon', $menu?->icon) }}"
                           placeholder="fas fa-home"
                           oninput="previewIcon(this.value)">
                    <div class="form-hint">Class FontAwesome, contoh: <code>fas fa-home</code>, <code>fas fa-book</code></div>
                    <div class="icon-preview" id="iconPreview" style="{{ old('icon', $menu?->icon) ? '' : 'display:none' }}">
                        <i id="iconEl" class="{{ old('icon', $menu?->icon) }}"></i>
                        <span id="iconLabel">{{ old('icon', $menu?->icon) }}</span>
                    </div>
                    @error('icon')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Urutan</label>
                    <input type="number" name="urutan" class="form-control" min="0"
                           value="{{ old('urutan', $menu?->urutan ?? 0) }}"
                           placeholder="0">
                    <div class="form-hint">Urutan tampil dari kiri ke kanan (angka terkecil duluan).</div>
                    @error('urutan')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Buka di</label>
                    <select name="target" class="form-control">
                        <option value="_self"  {{ old('target', $menu?->target ?? '_self')  === '_self'  ? 'selected' : '' }}>Tab yang sama (_self)</option>
                        <option value="_blank" {{ old('target', $menu?->target ?? '_self') === '_blank' ? 'selected' : '' }}>Tab baru (_blank)</option>
                    </select>
                    @error('target')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <label class="checkbox-label" style="margin-top:10px;">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $menu ? $menu->is_active : true) ? 'checked' : '' }}>
                        <i class="fas fa-circle-check" style="color:#10b981"></i>
                        <span>Menu aktif (tampil di home page)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('menu.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-floppy-disk"></i>
                {{ $menu ? 'Simpan Perubahan' : 'Simpan Menu' }}
            </button>
        </div>
    </form>

</div>
</div>
@endsection

@section('js')
<script>
function previewIcon(val) {
    var preview = document.getElementById('iconPreview');
    var el      = document.getElementById('iconEl');
    var label   = document.getElementById('iconLabel');
    if (val.trim()) {
        el.className = val.trim();
        label.textContent = val.trim();
        preview.style.display = 'inline-flex';
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endsection
