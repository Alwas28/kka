@extends('layouts.users')

@section('css')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; flex-wrap: wrap; gap: 15px;
    }
    .page-header-left h2 { font-size: 22px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
    .page-header-left p  { font-size: 13px; color: var(--text-secondary); margin: 0; }

    .table-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 12px; flex-wrap: wrap; }
    .search-box { position: relative; flex: 1; max-width: 340px; }
    .search-box input { width: 100%; padding: 9px 14px 9px 36px; border: 1px solid var(--gray-border); border-radius: 8px; font-size: 13px; font-family: inherit; transition: all 0.3s; }
    .search-box input:focus { outline: none; border-color: var(--maroon-main); box-shadow: 0 0 0 3px rgba(165,42,42,0.1); }
    .search-box i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 13px; }
    .table-info { font-size: 13px; color: var(--text-secondary); }

    /* TAGS */
    .tag { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; }
    .tag-jenis  { background: rgba(165,42,42,0.1); color: var(--maroon-dark); }
    .tag-tahun  { background: rgba(59,130,246,0.1); color: #1d4ed8; }
    .tag-periode{ background: rgba(139,92,246,0.1); color: #6d28d9; }

    .date-range { font-size: 12px; color: var(--text-secondary); white-space: nowrap; }
    .date-range strong { color: var(--text-primary); }

    /* BADGE SELESAI */
    .badge-selesai {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
        background: rgba(107,114,128,0.1); color: #374151;
    }
    .badge-selesai::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #9ca3af; }

    .notice-selesai {
        display: flex; align-items: center; gap: 10px; padding: 12px 16px;
        background: rgba(107,114,128,0.08); border: 1px solid rgba(107,114,128,0.25);
        border-radius: 8px; font-size: 13px; color: #374151; margin-bottom: 16px;
    }
    .notice-selesai i { color: #6b7280; font-size: 16px; flex-shrink: 0; }

    /* Baris tabel sedikit diredupkan untuk kesan "arsip" */
    #tableBody tr { opacity: 0.85; }
    #tableBody tr:hover { opacity: 1; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
    .empty-state i { font-size: 48px; color: var(--gray-border); margin-bottom: 15px; display: block; }
    .empty-state h3 { font-size: 16px; color: var(--text-primary); margin-bottom: 8px; }
    .empty-state p  { font-size: 13px; margin: 0; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-check-circle" style="color:#6b7280; margin-right:8px;"></i>Selesai Dilaksanakan</h2>
            <p>Arsip kegiatan KKA yang telah selesai dilaksanakan</p>
        </div>
    </div>

    <div class="notice-selesai">
        <i class="fas fa-lock"></i>
        Kegiatan yang sudah selesai <strong style="margin:0 4px;">tidak dapat diedit</strong>. Halaman ini hanya untuk keperluan arsip dan referensi.
    </div>

    <div class="table-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari kegiatan..." onkeyup="filterTable()">
        </div>
        <div class="table-info">Total: <strong>{{ $kegiatanList->count() }}</strong> kegiatan selesai</div>
    </div>

    <div class="table-container">
        @if($kegiatanList->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Kegiatan</th>
                    <th>Jenis / Tahun / Periode</th>
                    <th>Tanggal Kegiatan</th>
                    <th style="width:120px">Status</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($kegiatanList as $index => $item)
                <tr data-search="{{ strtolower($item->nama . ' ' . $item->jenisKka?->nama . ' ' . $item->tahun?->nama . ' ' . $item->periode?->nama) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight:600; font-size:14px; margin-bottom:2px;">{{ $item->nama }}</div>
                    </td>
                    <td>
                        <div style="display:flex; flex-wrap:wrap; gap:4px;">
                            @if($item->jenisKka)
                            <span class="tag tag-jenis">{{ $item->jenisKka->nama }}</span>
                            @endif
                            @if($item->tahun)
                            <span class="tag tag-tahun">{{ $item->tahun->nama }}</span>
                            @endif
                            @if($item->periode)
                            <span class="tag tag-periode">{{ $item->periode->nama }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="date-range">
                            <strong>{{ $item->kegiatan_mulai->format('d M Y') }}</strong>
                            <span style="margin:0 4px;">—</span>
                            <strong>{{ $item->kegiatan_selesai->format('d M Y') }}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge-selesai">Selesai</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>Belum ada kegiatan yang selesai</h3>
            <p>Kegiatan yang telah melewati tanggal selesai akan muncul di sini.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#tableBody tr[data-search]').forEach(row => {
            row.style.display = row.dataset.search.includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
