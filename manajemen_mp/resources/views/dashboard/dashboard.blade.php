@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8 text-start">
            <h3 class="fw-bold text-dark mb-1">Pusat Kendali Operasional</h3>
            <p class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i> {{ date('l, d M Y') }}
                <span class="mx-2">•</span>
                <i class="bi bi-clock me-1"></i> <span id="realtime-clock">--:--</span>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-flex align-items-center bg-white p-2 shadow-sm" style="border-radius: 12px;">
                <div class="bg-primary-soft text-primary p-2 rounded-3 me-3" style="background-color: #e7f0ff; color: #0d6efd;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div class="text-start me-3">
                    <small class="text-muted d-block" style="font-size: 10px;">ROLE AKSES</small>
                    <span class="fw-bold small text-primary">Administrator</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards: Sekarang Bisa Diklik (Linked) -->
    <div class="row g-4 mb-4">
        @php
            $stats = [
                ['label' => 'Anggota Aktif', 'value' => $total_anggota_aktif, 'icon' => 'bi-people', 'color' => '#0d6efd', 'desc' => 'Kelola Anggota', 'link' => route('anggota.anggota')],
                ['label' => 'Total Pelatih', 'value' => $total_pelatih, 'icon' => 'bi-person-badge', 'color' => '#198754', 'desc' => 'Manajemen Pelatih', 'link' => route('pelatih.index')],
                ['label' => 'Unit Kolat', 'value' => $total_kolat, 'icon' => 'bi-building', 'color' => '#0dcaf0', 'desc' => 'Daftar Kolat', 'link' => route('kolat.index')],
                ['label' => 'SPP Tertunda', 'value' => $spp_pending, 'icon' => 'bi-cash-stack', 'color' => '#f21136', 'desc' => 'Verifikasi SPP', 'link' => route('spp.index')]
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-xl-3 col-md-6">
            <a href="{{ $stat['link'] }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 20px; background: white; transition: 0.3s;">
                    <div class="card-body p-4 text-start">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="icon-box rounded-3" style="background-color: {{ $stat['color'] }}15; color: {{ $stat['color'] }}; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi {{ $stat['icon'] }}" style="font-size: 1.2rem;"></i>
                            </div>
                            <span class="text-primary small"><i class="bi bi-arrow-right-short" style="font-size: 1.5rem;"></i></span>
                        </div>
                        <h3 class="fw-bold mb-1 text-dark">{{ $stat['value'] }}</h3>
                        <p class="text-dark fw-bold small mb-1">{{ $stat['label'] }}</p>
                        <p class="text-muted mb-0" style="font-size: 11px;">{{ $stat['desc'] }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4 text-start">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 25px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Tren Kehadiran</h6>
                        <small class="text-muted font-12">Grafik kehadiran 7 hari terakhir</small>
                    </div>
                    <a href="{{ route('laporan.presensi') }}" class="btn btn-light btn-sm text-primary fw-bold" style="border-radius: 10px;">Lihat Laporan</a>
                </div>
                <div style="height: 320px;">
                    <canvas id="chartPresensi"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 25px;">
                <h6 class="fw-bold text-dark mb-1 text-center">Komposisi Sabuk</h6>
                <div style="height: 250px; position: relative;">
                    <canvas id="chartSabuk"></canvas>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -15%); text-align: center;">
                        <h4 class="mb-0 fw-bold">{{ $total_anggota_aktif }}</h4>
                        <small class="text-muted small">Anggota</small>
                    </div>
                </div>
                <div class="mt-4">
                   <div class="d-grid">
                       <a href="{{ route('anggota.anggota') }}" class="btn btn-outline-primary btn-sm rounded-pill">Detail Anggota</a>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Transaksi & Kelayakan -->
    <div class="row g-4 text-start">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 25px; overflow: hidden;">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">Aktivitas Terbaru</h6>
                    <a href="{{ route('spp.index') }}" class="small text-decoration-none">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4 border-0">USER</th>
                                    <th class="border-0">KEGIATAN</th>
                                    <th class="border-0 text-center">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aktivitas as $act)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 12px; background-color: #e7f0ff; color: #0d6efd;">
                                                {{ substr($act['user'], 0, 1) }}
                                            </div>
                                            <span class="small fw-bold text-dark">{{ $act['user'] }}</span>
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $act['kegiatan'] }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ $act['badge'] }} px-3 py-2" style="font-size: 10px; opacity: 0.8;">
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
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 text-center" style="border-radius: 25px; background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);">
                <h6 class="fw-bold text-dark mb-4">Ringkasan Kelayakan</h6>
                <div class="d-inline-block position-relative mb-3 mx-auto" style="width: 130px;">
                    <canvas id="progressUjian"></canvas>
                   <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; width: 100%;">
    <h2 class="fw-bold text-primary mb-0" style="line-height: 1;">{{ $jumlah_layak_ujian }}</h2>
    <small class="text-muted d-block" style="font-size: 10px; margin-top: -2px;">Siswa</small>
</div>
                </div>
                <p class="small text-muted px-2">Siswa dinyatakan layak mengikuti ujian berdasarkan presensi & SPP.</p>
                <div class="d-grid gap-3 mt-auto">
                    <a href="{{ route('rekap.index') }}" class="btn btn-primary py-3 rounded-4 shadow-sm fw-bold">
                        <i class="bi bi-clipboard-check me-2"></i> Buka Rekap Kelayakan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .bg-primary-soft { background-color: #e7f0ff; }
</style>

<script>
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('realtime-clock').textContent = timeStr + ' WIB';
    }
    setInterval(updateClock, 1000); updateClock();

    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart
        const ctxP = document.getElementById('chartPresensi').getContext('2d');
        new Chart(ctxP, {
            type: 'line',
            data: {
                labels: {!! json_encode($grafikPresensi->pluck('date')) !!},
                datasets: [{
                    label: 'Kehadiran',
                    data: {!! json_encode($grafikPresensi->pluck('total')) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f0f0f0' } }
                }
            }
        });

        // Doughnut Chart
        const ctxS = document.getElementById('chartSabuk').getContext('2d');
        new Chart(ctxS, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($persebaran_sabuk->pluck('tingkatan')) !!},
                datasets: [{
                    data: {!! json_encode($persebaran_sabuk->pluck('total')) !!},
                    backgroundColor: ['#0d6efd', '#0dcaf0', '#198754', '#ffc107', '#dc3545', '#6f42c1'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '80%', plugins: { legend: { display: false } } }
        });

        // Progress Ujian
        const ctxU = document.getElementById('progressUjian').getContext('2d');
        new Chart(ctxU, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $jumlah_layak_ujian }}, {{ max(1, $total_anggota_aktif - $jumlah_layak_ujian) }}],
                    backgroundColor: ['#0d6efd', '#f1f5f9'],
                    borderWidth: 0
                }]
            },
            options: { cutout: '85%', plugins: { tooltip: {enabled: false} } }
        });
    });
</script>
@endsection
