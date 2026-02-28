@extends('layouts.mahasiswa')

@section('css')
<style>
    .mhs-dashboard-wrap {
        max-width: 1100px;
    }

    /* Welcome Card */
    .mhs-welcome-card {
        background: linear-gradient(135deg, var(--maroon-main) 0%, var(--maroon-dark) 100%);
        border-radius: 16px;
        padding: 32px 36px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 20px rgba(139, 0, 0, 0.25);
    }

    .mhs-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        letter-spacing: 1px;
    }

    .mhs-welcome-info h2 {
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .mhs-welcome-info p {
        margin: 0;
        opacity: 0.85;
        font-size: 14px;
    }

    /* Info Cards */
    .mhs-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .mhs-info-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .mhs-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: rgba(139, 0, 0, 0.08);
        color: var(--maroon-main);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .mhs-info-label {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mhs-info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Level / Status Section */
    .mhs-level-card {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 28px 32px;
        margin-bottom: 28px;
    }

    .mhs-level-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .mhs-level-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .mhs-level-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        background: rgba(139, 0, 0, 0.1);
        color: var(--maroon-main);
        border: 1px solid rgba(139, 0, 0, 0.2);
    }

    .mhs-level-keterangan {
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mhs-level-keterangan i { color: var(--maroon-main); }

    /* Progress Steps */
    .mhs-steps {
        display: flex;
        align-items: flex-start;
        gap: 0;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .mhs-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        min-width: 80px;
        position: relative;
    }

    .mhs-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 16px;
        left: calc(50% + 16px);
        right: calc(-50% + 16px);
        height: 2px;
        background: var(--border-light);
        z-index: 0;
    }

    .mhs-step.done:not(:last-child)::after,
    .mhs-step.current:not(:last-child)::after {
        background: var(--maroon-main);
    }

    .mhs-step-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        z-index: 1;
        background: var(--bg-main, #f5f5f5);
        border: 2px solid var(--border-light);
        color: var(--text-secondary);
        transition: all 0.2s;
    }

    .mhs-step.done .mhs-step-dot {
        background: var(--maroon-main);
        border-color: var(--maroon-main);
        color: #fff;
    }

    .mhs-step.current .mhs-step-dot {
        background: #fff;
        border-color: var(--maroon-main);
        color: var(--maroon-main);
        box-shadow: 0 0 0 4px rgba(139, 0, 0, 0.12);
    }

    .mhs-step-label {
        margin-top: 8px;
        font-size: 11px;
        text-align: center;
        color: var(--text-secondary);
        line-height: 1.3;
        max-width: 80px;
    }

    .mhs-step.done .mhs-step-label,
    .mhs-step.current .mhs-step-label {
        color: var(--maroon-main);
        font-weight: 600;
    }

    /* Notice */
    .mhs-notice {
        background: rgba(59, 130, 246, 0.07);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 10px;
        padding: 18px 22px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        color: var(--text-primary);
        font-size: 14px;
    }

    .mhs-notice i {
        color: #3b82f6;
        font-size: 18px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .mhs-notice p { margin: 0; line-height: 1.6; }
</style>
@endsection

@section('konten')
<div class="dashboard-content">
<div class="mhs-dashboard-wrap">

    {{-- Welcome Card --}}
    <div class="mhs-welcome-card">
        @php
            $initials = collect(explode(' ', $mahasiswa->nama))
                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                ->take(2)
                ->join('');
        @endphp
        <div class="mhs-avatar-lg">{{ $initials }}</div>
        <div class="mhs-welcome-info">
            <h2>Selamat datang, {{ $mahasiswa->nama }}!</h2>
            <p>Portal Mahasiswa &mdash; Kuliah Kerja Amaliah UM Kendari</p>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="mhs-info-grid">
        <div class="mhs-info-card">
            <div class="mhs-info-icon"><i class="fas fa-id-card"></i></div>
            <div>
                <div class="mhs-info-label">NIM</div>
                <div class="mhs-info-value">{{ $mahasiswa->nim }}</div>
            </div>
        </div>

        <div class="mhs-info-card">
            <div class="mhs-info-icon"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="mhs-info-label">Email</div>
                <div class="mhs-info-value">{{ $mahasiswa->email }}</div>
            </div>
        </div>

        <div class="mhs-info-card">
            <div class="mhs-info-icon"><i class="fas fa-graduation-cap"></i></div>
            <div>
                <div class="mhs-info-label">Program Studi</div>
                <div class="mhs-info-value">{{ $mahasiswa->programStudi?->nama ?? '-' }}</div>
            </div>
        </div>

        <div class="mhs-info-card">
            <div class="mhs-info-icon"><i class="fas fa-building"></i></div>
            <div>
                <div class="mhs-info-label">Fakultas</div>
                <div class="mhs-info-value">{{ $mahasiswa->programStudi?->fakultas?->nama ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Level / Status Progress --}}
    @php
        $currentLevel = $mahasiswa->mahasiswa_level_id ?? 1;

        $levels = [
            1 => 'Registrasi',
            2 => 'Disetujui Prodi',
            3 => 'Submit Pendaftaran',
            4 => 'Perbaikan Dokumen',
            5 => 'Disetujui Panitia',
            6 => 'Pelaksanaan',
            7 => 'Selesai',
        ];

        $levelIcons = [
            1 => 'fa-user-plus',
            2 => 'fa-check-circle',
            3 => 'fa-paper-plane',
            4 => 'fa-file-pen',
            5 => 'fa-circle-check',
            6 => 'fa-users',
            7 => 'fa-flag-checkered',
        ];
    @endphp

    <div class="mhs-level-card">
        <div class="mhs-level-header">
            <div class="mhs-level-title">
                <i class="fas fa-chart-line" style="color: var(--maroon-main); margin-right: 8px;"></i>
                Status Pendaftaran KKA
            </div>
            <div class="mhs-level-badge">
                <i class="fas {{ $levelIcons[$currentLevel] ?? 'fa-circle' }}"></i>
                Level {{ $currentLevel }} &mdash; {{ $levels[$currentLevel] ?? '-' }}
            </div>
        </div>

        <div class="mhs-level-keterangan">
            <i class="fas fa-info-circle"></i>
            {{ $mahasiswa->level?->keterangan ?? '-' }}
        </div>

        <div class="mhs-steps">
            @foreach($levels as $lvl => $label)
                @php
                    $isDone = $lvl < $currentLevel || ($lvl === $currentLevel && $currentLevel === 7);
                    $state  = $isDone ? 'done' : ($lvl === $currentLevel ? 'current' : '');
                @endphp
                <div class="mhs-step {{ $state }}">
                    <div class="mhs-step-dot">
                        @if($lvl <= $currentLevel)
                            <i class="fas fa-check" style="font-size:12px;"></i>
                        @else
                            {{ $lvl }}
                        @endif
                    </div>
                    <div class="mhs-step-label">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Notice --}}
    <div class="mhs-notice">
        <i class="fas fa-info-circle"></i>
        <p>Halaman ini masih dalam tahap pengembangan. Fitur-fitur lengkap seperti pendaftaran KKA, upload dokumen, dan pemantauan kelompok akan segera tersedia.</p>
    </div>

</div>{{-- /mhs-dashboard-wrap --}}
</div>{{-- /dashboard-content --}}
@endsection
