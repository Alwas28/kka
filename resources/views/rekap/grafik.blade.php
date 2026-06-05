@extends('layouts.users')

@section('css')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
    .page-header-left h2 { font-size:20px; font-weight:700; color:var(--text-primary); margin-bottom:4px; }
    .page-header-left p  { font-size:13px; color:var(--text-secondary); margin:0; }

    .breadcrumb { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--text-secondary); margin-bottom:18px; }
    .breadcrumb a { color:var(--maroon-main); text-decoration:none; font-weight:600; }
    .breadcrumb a:hover { text-decoration:underline; }

    .kegiatan-info { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:14px 20px; margin-bottom:22px; display:flex; flex-wrap:wrap; gap:22px; align-items:center; }
    .ki-item .ki-label { font-size:11px; color:var(--text-secondary); margin-bottom:2px; }
    .ki-item .ki-val   { font-size:14px; font-weight:700; color:var(--text-primary); }
    .ki-item .ki-val.accent { color:var(--maroon-main); }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; font-family:inherit; }
    .btn-secondary { background:var(--gray-border); color:var(--text-primary); }
    .btn-secondary:hover { background:#d1d5db; }
    .btn-detail { background:linear-gradient(135deg,var(--maroon-light),var(--maroon-main)); color:#fff; }
    .btn-detail:hover { box-shadow:0 3px 10px rgba(165,42,42,.35); transform:translateY(-1px); color:#fff; }

    /* Stat summary */
    .summary-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; margin-bottom:24px; }
    .sum-card { background:#fff; border:1px solid var(--gray-border); border-radius:10px; padding:12px 14px; text-align:center; }
    .sum-card .sum-num   { font-size:24px; font-weight:800; line-height:1; color:var(--maroon-main); }
    .sum-card .sum-label { font-size:11px; color:var(--text-secondary); margin-top:4px; }

    /* Chart grid */
    .chart-grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:18px; }
    .chart-grid-3-2 { display:grid; grid-template-columns:1fr 1fr 1.4fr; gap:18px; margin-bottom:18px; }
    @media(max-width:900px) {
        .chart-grid-3, .chart-grid-3-2 { grid-template-columns:1fr 1fr; }
    }
    @media(max-width:580px) {
        .chart-grid-3, .chart-grid-3-2 { grid-template-columns:1fr; }
    }

    .chart-card { background:#fff; border:1px solid var(--gray-border); border-radius:12px; padding:18px 18px 14px; }
    .chart-card.full { margin-bottom:18px; }

    .chart-title { font-size:13px; font-weight:700; color:var(--text-primary); margin-bottom:14px; display:flex; align-items:center; gap:7px; }
    .chart-title i { color:var(--maroon-main); font-size:13px; }

    .chart-wrap         { position:relative; height:220px; }
    .chart-wrap.donut   { height:200px; }
    .chart-wrap.tall    { height:340px; }
    .chart-wrap.prodi   { height:36px; } /* dikontrol JS berdasarkan jumlah prodi */
</style>
@endsection

@section('konten')
<div class="dashboard-content">

    <div class="breadcrumb">
        <a href="{{ route('rekap.pendaftaran') }}"><i class="fas fa-chart-bar"></i> Rekap Pendaftaran</a>
        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
        <a href="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}">Detail</a>
        <i class="fas fa-chevron-right" style="font-size:10px;"></i>
        <span>Grafik</span>
    </div>

    <div class="page-header">
        <div class="page-header-left">
            <h2><i class="fas fa-chart-pie" style="color:var(--maroon-main);margin-right:8px;"></i>Grafik Data Mahasiswa</h2>
            <p>{{ $kegiatan->nama }}</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('rekap.pendaftaran.detail', $kegiatan->id) }}" class="btn btn-detail">
                <i class="fas fa-table"></i> Lihat Tabel
            </a>
            <a href="{{ route('rekap.pendaftaran') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="kegiatan-info">
        <div class="ki-item">
            <div class="ki-label">Jenis KKA</div>
            <div class="ki-val accent">{{ $kegiatan->jenis_nama ?? '—' }}</div>
        </div>
        <div class="ki-item">
            <div class="ki-label">Periode</div>
            <div class="ki-val">{{ $kegiatan->periode_nama ?? '—' }}</div>
        </div>
        <div class="ki-item">
            <div class="ki-label">Total Mahasiswa</div>
            <div class="ki-val accent">{{ $total }}</div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary-row">
        <div class="sum-card">
            <div class="sum-num">{{ $total }}</div>
            <div class="sum-label">Total Pendaftar</div>
        </div>
        <div class="sum-card">
            <div class="sum-num" style="color:#3b82f6;">{{ $jenisKelamin['data'][0] }}</div>
            <div class="sum-label">Laki-laki</div>
        </div>
        <div class="sum-card">
            <div class="sum-num" style="color:#ec4899;">{{ $jenisKelamin['data'][1] }}</div>
            <div class="sum-label">Perempuan</div>
        </div>
        <div class="sum-card">
            <div class="sum-num" style="color:#10b981;">{{ $kesehatanData['data'][0] }}</div>
            <div class="sum-label">Sehat</div>
        </div>
        <div class="sum-card">
            <div class="sum-num" style="color:#f59e0b;">{{ $kesehatanData['data'][1] }}</div>
            <div class="sum-label">Sehat dg Catatan</div>
        </div>
    </div>

    {{-- Baris 1: Jenis Kelamin | Kesehatan | Ukuran Baju --}}
    <div class="chart-grid-3-2">
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-venus-mars"></i> Jenis Kelamin</div>
            <div class="chart-wrap donut"><canvas id="chartJK"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-heartbeat"></i> Status Kesehatan</div>
            <div class="chart-wrap donut"><canvas id="chartKesehatan"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-tshirt"></i> Ukuran Baju</div>
            <div class="chart-wrap"><canvas id="chartBaju"></canvas></div>
        </div>
    </div>

    {{-- Baris 2: Semester | SKS | IPK --}}
    <div class="chart-grid-3">
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-calendar-alt"></i> Sebaran Semester</div>
            <div class="chart-wrap"><canvas id="chartSemester"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-book-open"></i> Rentang SKS Ditempuh</div>
            <div class="chart-wrap"><canvas id="chartSKS"></canvas></div>
        </div>
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-chart-line"></i> Rentang IPK</div>
            <div class="chart-wrap"><canvas id="chartIPK"></canvas></div>
        </div>
    </div>

    {{-- Baris 3: Program Studi (full width, tinggi dinamis) --}}
    <div class="chart-card full">
        <div class="chart-title"><i class="fas fa-university"></i> Sebaran Program Studi</div>
        <div id="chartProdiWrap" style="position:relative;">
            <canvas id="chartProdi"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ── Palet warna ──────────────────────────────────────────────────────────
    const MAROON  = '#8B0000';
    const palette = [
        '#8B0000','#c53030','#f59e0b','#059669','#2563eb',
        '#7c3aed','#db2777','#0891b2','#65a30d','#ea580c',
        '#475569','#0f766e','#9333ea','#b45309','#1e40af',
    ];

    function paletteFor(n) {
        return Array.from({length: n}, (_, i) => palette[i % palette.length]);
    }

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
            tooltip: { bodyFont: { size: 12 } },
        },
    };

    // ── 1. Jenis Kelamin (doughnut) ───────────────────────────────────────────
    new Chart(document.getElementById('chartJK'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($jenisKelamin['labels']) !!},
            datasets: [{
                data: {!! json_encode($jenisKelamin['data']) !!},
                backgroundColor: ['#3b82f6', '#ec4899'],
                borderWidth: 2,
                borderColor: '#fff',
            }],
        },
        options: {
            ...chartDefaults,
            cutout: '60%',
            plugins: {
                ...chartDefaults.plugins,
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 12 } } },
            },
        },
    });

    // ── 2. Kesehatan (doughnut) ───────────────────────────────────────────────
    new Chart(document.getElementById('chartKesehatan'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kesehatanData['labels']) !!},
            datasets: [{
                data: {!! json_encode($kesehatanData['data']) !!},
                backgroundColor: ['#10b981', '#f59e0b'],
                borderWidth: 2,
                borderColor: '#fff',
            }],
        },
        options: {
            ...chartDefaults,
            cutout: '60%',
            plugins: {
                ...chartDefaults.plugins,
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 12 } } },
            },
        },
    });

    // ── 3. Ukuran Baju (bar) ──────────────────────────────────────────────────
    new Chart(document.getElementById('chartBaju'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($ukuranBaju['labels']) !!},
            datasets: [{
                label: 'Mahasiswa',
                data: {!! json_encode($ukuranBaju['data']) !!},
                backgroundColor: paletteFor({!! count($ukuranBaju['labels']) !!}),
                borderRadius: 5,
                borderSkipped: false,
            }],
        },
        options: {
            ...chartDefaults,
            plugins: { ...chartDefaults.plugins, legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } },
            },
        },
    });

    // ── 4. Semester (bar) ─────────────────────────────────────────────────────
    new Chart(document.getElementById('chartSemester'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($semesterData['labels']) !!},
            datasets: [{
                label: 'Mahasiswa',
                data: {!! json_encode($semesterData['data']) !!},
                backgroundColor: MAROON,
                borderRadius: 5,
                borderSkipped: false,
            }],
        },
        options: {
            ...chartDefaults,
            plugins: { ...chartDefaults.plugins, legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } },
            },
        },
    });

    // ── 5. SKS Ditempuh (bar) ─────────────────────────────────────────────────
    new Chart(document.getElementById('chartSKS'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($sksData['labels']) !!},
            datasets: [{
                label: 'Mahasiswa',
                data: {!! json_encode($sksData['data']) !!},
                backgroundColor: '#2563eb',
                borderRadius: 5,
                borderSkipped: false,
            }],
        },
        options: {
            ...chartDefaults,
            plugins: { ...chartDefaults.plugins, legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } },
            },
        },
    });

    // ── 6. IPK (bar) ──────────────────────────────────────────────────────────
    new Chart(document.getElementById('chartIPK'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($ipkData['labels']) !!},
            datasets: [{
                label: 'Mahasiswa',
                data: {!! json_encode($ipkData['data']) !!},
                backgroundColor: ['#9ca3af','#f59e0b','#10b981','#2563eb','#7c3aed'],
                borderRadius: 5,
                borderSkipped: false,
            }],
        },
        options: {
            ...chartDefaults,
            plugins: { ...chartDefaults.plugins, legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } },
            },
        },
    });

    // ── 7. Program Studi (horizontal bar, tinggi dinamis) ─────────────────────
    const prodiLabels = {!! json_encode($prodiData['labels']) !!};
    const prodiData   = {!! json_encode($prodiData['data']) !!};
    const prodiHeight = Math.max(180, prodiLabels.length * 38);
    document.getElementById('chartProdiWrap').style.height = prodiHeight + 'px';

    new Chart(document.getElementById('chartProdi'), {
        type: 'bar',
        data: {
            labels: prodiLabels,
            datasets: [{
                label: 'Mahasiswa',
                data: prodiData,
                backgroundColor: paletteFor(prodiLabels.length),
                borderRadius: 5,
                borderSkipped: false,
            }],
        },
        options: {
            ...chartDefaults,
            indexAxis: 'y',
            plugins: { ...chartDefaults.plugins, legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                y: { ticks: { font: { size: 11 } }, grid: { display: false } },
            },
        },
    });
})();
</script>
@endsection
