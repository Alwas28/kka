@extends('layouts.users')

@section('css')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .page-header-left h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
    .page-header-left p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    .table-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .search-box { position: relative; flex: 1; max-width: 300px; }
    .search-box input {
        width: 100%; padding: 9px 14px 9px 36px;
        border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit;
    }
    .search-box input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,.1); }
    .search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 13px; }
    .filter-select {
        padding: 9px 14px; border: 1px solid var(--gray-border); border-radius: 8px;
        font-size: 13px; font-family: inherit; background: white; min-width: 200px;
    }
    .filter-select:focus { outline: none; border-color: var(--maroon-main); }
    .toolbar-count { margin-left: auto; font-size: 13px; color: var(--text-secondary); }

    .mhs-info { display: flex; align-items: center; gap: 10px; }
    .mhs-avatar {
        width: 36px; height: 36px; border-radius: 8px;
        background: linear-gradient(135deg, #059669, #10b981);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 13px; flex-shrink: 0;
    }
    .mhs-name  { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .mhs-email { font-size: 12px; color: var(--text-secondary); }
    .mhs-nim   { font-size: 12px; color: var(--text-secondary); font-family: monospace; }

    .badge-prodi {
        display: inline-flex; gap: 4px;
        background: rgba(16,185,129,.1); color: #065f46;
        border-radius: 20px; padding: 2px 10px; font-size: 11px; font-weight: 600;
    }

    .badge-form {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600;
    }
    .badge-form.draft    { background: rgba(245,158,11,.1); color: #92400e; border: 1px solid rgba(245,158,11,.2); }
    .badge-form.belum    { background: rgba(156,163,175,.1); color: #6b7280; border: 1px solid rgba(156,163,175,.2); }
    .badge-form.submitted{ background: rgba(59,130,246,.1);  color: #1d4ed8; border: 1px solid rgba(59,130,246,.2); }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i  { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas fa-check-circle" style="color:#10b981; margin-right:8px;"></i>
                Disetujui Prodi
            </h2>
            <p>
                Mahasiswa yang telah disetujui prodi dan sedang mengisi form pendaftaran
                @if(!$isAllProdi)
                    &mdash; <span style="color:var(--maroon-main); font-weight:600;">sesuai prodi Anda</span>
                @else
                    &mdash; <span style="color:#059669; font-weight:600;">semua program studi</span>
                @endif
            </p>
        </div>
    </div>

    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama, NIM, atau email..." oninput="filterTable()">
        </div>
        <select class="filter-select" id="filterProdi" onchange="filterTable()">
            <option value="">Semua Program Studi</option>
            @foreach($mahasiswaList->pluck('programStudi')->filter()->unique('id')->sortBy('nama') as $prodi)
                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
            @endforeach
        </select>
        <select class="filter-select" id="filterStatus" onchange="filterTable()" style="min-width:160px;">
            <option value="">Semua Status Form</option>
            <option value="belum">Belum Diisi</option>
            <option value="draft">Draft</option>
            <option value="submitted">Sudah Dikirim</option>
        </select>
        <div class="toolbar-count">Total: <strong id="rowCount">{{ $mahasiswaList->count() }}</strong></div>
    </div>

    <div class="table-container">
        @if($mahasiswaList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:48px">No</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Program Studi</th>
                    <th>Fakultas</th>
                    <th>Tgl. Disetujui</th>
                    <th>Status Form</th>
                </tr>
            </thead>
            <tbody id="regTableBody">
                @foreach($mahasiswaList as $index => $mhs)
                @php
                    $initials = collect(explode(' ', $mhs->nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
                    $p = $mhs->pendaftaran;
                    if (!$p) {
                        $formStatus = 'belum';
                        $formLabel  = 'Belum Diisi';
                        $formIcon   = 'fa-circle-xmark';
                    } elseif ($p->isSubmitted()) {
                        $formStatus = 'submitted';
                        $formLabel  = 'Sudah Dikirim';
                        $formIcon   = 'fa-circle-check';
                    } else {
                        $formStatus = 'draft';
                        $formLabel  = 'Draft';
                        $formIcon   = 'fa-pen-to-square';
                    }
                @endphp
                <tr data-search="{{ strtolower($mhs->nama . ' ' . $mhs->nim . ' ' . $mhs->email) }}"
                    data-prodi="{{ $mhs->program_studi_id }}"
                    data-status="{{ $formStatus }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="mhs-info">
                            <div class="mhs-avatar">{{ $initials }}</div>
                            <div>
                                <div class="mhs-name">{{ $mhs->nama }}</div>
                                <div class="mhs-email">{{ $mhs->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="mhs-nim">{{ $mhs->nim }}</span></td>
                    <td>
                        @if($mhs->programStudi)
                            <span class="badge-prodi">{{ $mhs->programStudi->nama }}</span>
                        @else —
                        @endif
                    </td>
                    <td style="font-size:13px; color:var(--text-secondary);">
                        {{ $mhs->programStudi?->fakultas?->nama ?? '—' }}
                    </td>
                    <td style="font-size:13px; color:var(--text-secondary);">
                        {{ $mhs->updated_at->format('d/m/Y') }}<br>
                        <span style="font-size:11px;">{{ $mhs->updated_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <span class="badge-form {{ $formStatus }}">
                            <i class="fas {{ $formIcon }}"></i>
                            {{ $formLabel }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>Belum ada mahasiswa yang disetujui</h3>
            <p>Mahasiswa yang disetujui prodi akan muncul di sini untuk mengisi form pendaftaran.</p>
        </div>
        @endif
    </div>

</div>
@endsection

@section('js')
<script>
    function filterTable() {
        const q      = document.getElementById('searchInput').value.toLowerCase();
        const prodi  = document.getElementById('filterProdi').value;
        const status = document.getElementById('filterStatus').value;
        let visible  = 0;

        document.querySelectorAll('#regTableBody tr[data-search]').forEach(row => {
            const ok = (!q      || row.dataset.search.includes(q))
                    && (!prodi  || row.dataset.prodi  === prodi)
                    && (!status || row.dataset.status === status);
            row.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        document.getElementById('rowCount').textContent = visible;
    }
</script>
@endsection
