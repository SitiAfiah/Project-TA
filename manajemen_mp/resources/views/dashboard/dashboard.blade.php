@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 text-start">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Pengurus</h3>
            <p class="text-muted small">Ringkasan data Merpati Putih Cabang Jember • {{ date('d M Y') }}</p>
        </div>
        <div>
            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill">
                <i class="fas fa-user-shield me-1"></i> Mode Administrator
            </span>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; border-left: 5px solid #0d6efd;">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary me-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Anggota Aktif</small>
                        <h4 class="fw-bold mb-0">{{ $total_anggota_aktif }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; border-left: 5px solid #198754;">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 text-success me-3">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Pelatih</small>
                        <h4 class="fw-bold mb-0">{{ $total_pelatih }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; border-left: 5px solid #0dcaf0;">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info me-3">
                        <i class="fas fa-university fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Kolat</small>
                        <h4 class="fw-bold mb-0">{{ $total_kolat }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; border-left: 5px solid #ffc107;">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning me-3">
                        <i class="fas fa-file-invoice-dollar fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">SPP Tertunda</small>
                        <h4 class="fw-bold mb-0">{{ $spp_pending }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4 text-start">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <h6 class="fw-bold text-dark mb-4"><i class="fas fa-chart-line me-2 text-primary"></i>Tren Kehadiran Latihan (7 Hari Terakhir)</h6>
                <div style="height: 300px;">
                    <canvas id="chartPresensi"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <h6 class="fw-bold text-dark mb-4"><i class="fas fa-chart-pie me-2 text-primary"></i>Komposisi Tingkatan</h6>
                <div style="height: 300px;">
                    <canvas id="chartSabuk"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities & Quick Actions Row -->
    <div class="row g-4 text-start">
        <!-- Aktivitas Terbaru -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <h6 class="fw-bold text-dark mb-4">Aktivitas Transaksi Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th>WAKTU</th>
                                <th>KEGIATAN</th>
                                <th>USER</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktivitas as $act)
                            <tr>
                                <td class="small text-muted">{{ $act['waktu'] }}</td>
                                <td class="small fw-bold">{{ $act['kegiatan'] }}</td>
                                <td class="small">{{ $act['user'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $act['badge'] }}-soft text-{{ $act['badge'] }} px-2 py-1 rounded small">
                                        {{ $act['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Summary -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);">
                <h6 class="fw-bold text-dark mb-4">Ringkasan Operasional</h6>

                <div class="mb-4">
                    <small class="text-muted d-block mb-2">Kelayakan Ujian Saat Ini</small>
                    <div class="d-flex align-items-center">
                        <h3 class="fw-bold text-primary mb-0 me-2">{{ $jumlah_layak_ujian }}</h3>
                        <span class="text-muted small">Anggota Siap Ujian</span>
                    </div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-primary" style="width: {{ ($total_anggota_aktif > 0) ? ($jumlah_layak_ujian/$total_anggota_aktif)*100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('rekap.index') }}" class="btn btn-primary py-3 rounded-3 shadow-sm">
                        <i class="fas fa-clipboard-check me-2"></i> Buka Rekap Kelayakan
                    </a>
                    <a href="{{ route('spp.index') }}" class="btn btn-outline-primary py-3 rounded-3">
                        <i class="fas fa-wallet me-2"></i> Verifikasi SPP
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Presensi (Line Chart)
    const ctxP = document.getElementById('chartPresensi').getContext('2d');
    new Chart(ctxP, {
        type: 'line',
        data: {
            labels: {!! json_encode($grafikPresensi->pluck('date')) !!},
            datasets: [{
                label: 'Jumlah Hadir',
                data: {!! json_encode($grafikPresensi->pluck('total')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.05)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
        }
    });

    // Grafik Sabuk (Doughnut Chart)
    const ctxS = document.getElementById('chartSabuk').getContext('2d');
    new Chart(ctxS, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($persebaran_sabuk->pluck('tingkatan')) !!},
            datasets: [{
                data: {!! json_encode($persebaran_sabuk->pluck('total')) !!},
                backgroundColor: ['#0d6efd', '#0dcaf0', '#198754', '#ffc107', '#dc3545', '#6610f2']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 10 } } }
        }
    });
</script>

<style>
    .bg-primary-soft { background-color: #e7f0ff; }
    .bg-success-soft { background-color: #e6fffa; }
    .bg-warning-soft { background-color: #fffaf0; }
    .bg-info-soft { background-color: #e0f2fe; }
    .bg-danger-soft { background-color: #fff5f5; }
</style>
@endsection
