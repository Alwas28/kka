@extends('layouts.users')

@section('css')
<style>
    /* PAGE HEADER */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header-left h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .page-header-left p {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, var(--maroon-light) 0%, var(--maroon-main) 100%);
        color: white;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        margin-left: 8px;
        vertical-align: middle;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-light) 0%, var(--maroon-main) 100%);
        color: white;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 15px rgba(165, 42, 42, 0.4);
        transform: translateY(-1px);
        color: white;
    }

    .btn-secondary {
        background: var(--gray-border);
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background: #d1d5db;
        color: var(--text-primary);
    }

    /* SUMMARY BAR */
    .summary-bar {
        display: flex;
        align-items: center;
        gap: 20px;
        background: white;
        border-radius: 12px;
        padding: 16px 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        flex-wrap: wrap;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .summary-item strong {
        font-size: 20px;
        font-weight: 700;
        color: var(--maroon-main);
    }

    .summary-divider {
        width: 1px;
        height: 30px;
        background: var(--gray-border);
    }

    /* MASTER TOGGLE */
    .master-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .select-controls {
        display: flex;
        gap: 8px;
    }

    .btn-link {
        background: none;
        border: none;
        color: var(--maroon-main);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background 0.2s;
        font-family: inherit;
    }

    .btn-link:hover {
        background: rgba(165, 42, 42, 0.08);
    }

    .btn-link.deselect {
        color: var(--text-secondary);
    }

    /* FEATURE CARD */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 16px;
    }

    .feature-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        transition: box-shadow 0.2s;
    }

    .feature-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .feature-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background: var(--gray-light);
        border-bottom: 1px solid var(--gray-border);
        cursor: pointer;
        user-select: none;
    }

    .feature-card-header:hover {
        background: #e9eaec;
    }

    .feature-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feature-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--maroon-light), var(--maroon-main));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }

    .feature-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        text-transform: capitalize;
    }

    .feature-count {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 1px;
    }

    .feature-toggle-all {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-secondary);
        background: white;
        border: 1px solid var(--gray-border);
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        font-weight: 600;
        white-space: nowrap;
    }

    .feature-toggle-all:hover {
        border-color: var(--maroon-main);
        color: var(--maroon-main);
        background: rgba(165,42,42,0.05);
    }

    .feature-card-body {
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* CHECKBOX ITEM */
    .access-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 8px;
        transition: background 0.15s;
        cursor: pointer;
    }

    .access-item:hover {
        background: var(--gray-light);
    }

    .access-item input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: var(--maroon-main);
        cursor: pointer;
        flex-shrink: 0;
    }

    .access-item-info {
        flex: 1;
        min-width: 0;
    }

    .access-item-nama {
        font-family: 'Consolas', 'Courier New', monospace;
        font-size: 13px;
        font-weight: 600;
        color: var(--maroon-dark);
        background: rgba(165,42,42,0.07);
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .access-item-keterangan {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    /* AKSI BADGE */
    .aksi-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    .aksi-lihat    { background: rgba(59,130,246,0.12); color: #2563eb; }
    .aksi-tambah   { background: rgba(16,185,129,0.12); color: #059669; }
    .aksi-edit     { background: rgba(245,158,11,0.12); color: #d97706; }
    .aksi-hapus    { background: rgba(239,68,68,0.12);  color: #dc2626; }
    .aksi-other    { background: rgba(139,92,246,0.12); color: #7c3aed; }

    /* EMPTY STATE */
    .empty-access {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .empty-access i {
        font-size: 40px;
        color: var(--gray-border);
        margin-bottom: 12px;
    }

    .empty-access p {
        font-size: 14px;
        margin: 0;
    }

    /* STICKY SAVE BAR */
    .sticky-save {
        position: sticky;
        bottom: 0;
        background: white;
        border-top: 1px solid var(--gray-border);
        padding: 14px 24px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        box-shadow: 0 -4px 16px rgba(0,0,0,0.06);
        z-index: 100;
        margin-top: 20px;
    }

    .sticky-save-info {
        font-size: 13px;
        color: var(--text-secondary);
        margin-right: auto;
    }

    .sticky-save-info span {
        font-weight: 700;
        color: var(--maroon-main);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .feature-grid {
            grid-template-columns: 1fr;
        }

        .summary-bar {
            gap: 12px;
        }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-left">
            <h2>
                <i class="fas fa-key" style="color: var(--maroon-main); margin-right: 8px;"></i>
                Kelola Hak Akses
                <span class="role-badge"><i class="fas fa-user-tag"></i>{{ $role->nama }}</span>
            </h2>
            <p>{{ $role->keterangan ?? 'Pilih access yang dimiliki oleh role ini' }}</p>
        </div>
        <a href="{{ route('role.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- SUMMARY BAR -->
    <div class="summary-bar">
        <div class="summary-item">
            <i class="fas fa-shield-alt" style="color: var(--maroon-main);"></i>
            <div>
                <strong id="count-assigned">{{ count($assignedIds) }}</strong>
                <div style="font-size:12px;">Access dipilih</div>
            </div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <i class="fas fa-list" style="color: var(--text-secondary);"></i>
            <div>
                <strong style="color:var(--text-secondary);">{{ $allAccesses->flatten()->count() }}</strong>
                <div style="font-size:12px;">Total access</div>
            </div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <i class="fas fa-layer-group" style="color: var(--text-secondary);"></i>
            <div>
                <strong style="color:var(--text-secondary);">{{ $allAccesses->count() }}</strong>
                <div style="font-size:12px;">Grup fitur</div>
            </div>
        </div>
    </div>

    <form action="{{ route('role.access.update', $role) }}" method="POST" id="form-access">
        @csrf

        <!-- MASTER TOOLBAR -->
        <div class="master-toolbar">
            <div class="select-controls">
                <button type="button" class="btn-link" onclick="selectAll()">
                    <i class="fas fa-check-double"></i> Pilih Semua
                </button>
                <button type="button" class="btn-link deselect" onclick="deselectAll()">
                    <i class="fas fa-times"></i> Hapus Semua
                </button>
            </div>
        </div>

        @if($allAccesses->count() > 0)
        <!-- FEATURE GRID -->
        <div class="feature-grid">
            @foreach($allAccesses as $fitur => $accesses)
            @php
                $assignedInGroup = $accesses->filter(fn($a) => in_array($a->id, $assignedIds))->count();
                $totalInGroup    = $accesses->count();
            @endphp
            <div class="feature-card">
                <div class="feature-card-header" onclick="toggleGroup('group-{{ Str::slug($fitur) }}')">
                    <div class="feature-title">
                        <div class="feature-icon">
                            <i class="fas fa-puzzle-piece"></i>
                        </div>
                        <div>
                            <div class="feature-name">{{ $fitur }}</div>
                            <div class="feature-count" id="label-{{ Str::slug($fitur) }}">
                                {{ $assignedInGroup }} / {{ $totalInGroup }} dipilih
                            </div>
                        </div>
                    </div>
                    <button type="button" class="feature-toggle-all"
                        onclick="event.stopPropagation(); toggleGroupAll('group-{{ Str::slug($fitur) }}')">
                        <i class="fas fa-check"></i> Pilih Semua
                    </button>
                </div>
                <div class="feature-card-body" id="group-{{ Str::slug($fitur) }}">
                    @foreach($accesses->sortBy('nama') as $access)
                    @php
                        $aksi = explode('.', $access->nama)[0] ?? 'other';
                        $aksiClass = in_array($aksi, ['lihat','tambah','edit','hapus'])
                            ? "aksi-{$aksi}"
                            : 'aksi-other';
                    @endphp
                    <label class="access-item">
                        <input
                            type="checkbox"
                            name="access_ids[]"
                            value="{{ $access->id }}"
                            class="access-checkbox"
                            data-group="group-{{ Str::slug($fitur) }}"
                            {{ in_array($access->id, $assignedIds) ? 'checked' : '' }}
                            onchange="updateCount()">
                        <div class="access-item-info">
                            <div class="access-item-nama">{{ $access->nama }}</div>
                            @if($access->keterangan)
                            <div class="access-item-keterangan">{{ $access->keterangan }}</div>
                            @endif
                        </div>
                        <span class="aksi-badge {{ $aksiClass }}">{{ $aksi }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="table-container">
            <div class="empty-access">
                <i class="fas fa-shield-alt"></i>
                <p>Belum ada data access. Tambahkan access terlebih dahulu di menu <strong>Manajemen Access</strong>.</p>
            </div>
        </div>
        @endif

        <!-- STICKY SAVE BAR -->
        <div class="sticky-save">
            <div class="sticky-save-info">
                <span id="save-count">{{ count($assignedIds) }}</span> access dipilih untuk role <strong>{{ $role->nama }}</strong>
            </div>
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Hak Akses
            </button>
        </div>

    </form>
</div>
@endsection

@section('js')
<script>
    // Update counter jumlah yang dipilih
    function updateCount() {
        const total = document.querySelectorAll('.access-checkbox:checked').length;
        document.getElementById('count-assigned').textContent = total;
        document.getElementById('save-count').textContent = total;
        updateGroupLabels();
    }

    // Update label "x / y dipilih" per grup
    function updateGroupLabels() {
        document.querySelectorAll('.feature-card').forEach(card => {
            const groupId = card.querySelector('.feature-card-body').id;
            const checkboxes = card.querySelectorAll('.access-checkbox');
            const checked   = card.querySelectorAll('.access-checkbox:checked').length;
            const label     = card.querySelector('.feature-count');
            if (label) label.textContent = `${checked} / ${checkboxes.length} dipilih`;
        });
    }

    // Pilih semua access di seluruh halaman
    function selectAll() {
        document.querySelectorAll('.access-checkbox').forEach(cb => cb.checked = true);
        updateCount();
    }

    // Hapus semua pilihan
    function deselectAll() {
        document.querySelectorAll('.access-checkbox').forEach(cb => cb.checked = false);
        updateCount();
    }

    // Toggle semua checkbox dalam satu grup fitur
    function toggleGroupAll(groupId) {
        const checkboxes = document.querySelectorAll(`#${groupId} .access-checkbox`);
        const allChecked = [...checkboxes].every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        updateCount();
    }

    // Toggle expand/collapse kartu fitur (klik header)
    function toggleGroup(groupId) {
        const body = document.getElementById(groupId);
        body.style.display = body.style.display === 'none' ? '' : 'none';
    }

    // Init
    updateGroupLabels();
</script>
@endsection
