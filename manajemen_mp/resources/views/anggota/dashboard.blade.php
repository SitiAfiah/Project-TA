@extends('layout.app')

@section('content')
<style>
    /* Mengatur ulang font agar lebih modern (Opsional jika template Anda sudah punya) */
    body { background-color: #f8fafc; }

    /* Profil Header dengan Gradasi Modern */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }
    /* Dekorasi latar belakang (lingkaran transparan) */
    .bg-gradient-primary::after {
        content: ''; position: absolute; top: -50%; right: -10%;
        width: 300px; height: 300px; background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Kartu Global */
    .card-custom {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Efek Hover untuk Kartu Statistik */
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    /* Badge & Label */
    .badge-soft-primary { background-color: rgba(255,255,255,0.2); color: #fff; backdrop-filter: blur(5px); }
    .icon-box {
        width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;
        border-radius: 12px; font-size: 1.25rem;
    }
    .icon-box-primary { background-color: #e0f2fe; color: #0284c7; }
    .icon-box-warning { background-color: #fef3c7; color: #d97706; }
    .icon-box-success { background-color: #dcfce7; color: #16a34a; }

    /* Tabel Modern */
    .table-modern th {
        font-weight: 600; color: #64748b; font-size: 0.85rem;
        text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f5f9; background-color: transparent;
    }
    .table-modern td {
        vertical-align: middle; border-bottom: 1px solid #f1f5f9;
        color: #334155; padding: 1rem 0.5rem;
    }
    .table-modern tbody tr:hover { background-color: #f8fafc; }

    /* List Tagihan */
    .bill-item {
        border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem;
        transition: all 0.2s ease; border-left: 4px solid #f59e0b;
    }
    .bill-item:hover { background-color: #f8fafc; border-color: #cbd5e1; border-left-color: #f59e0b; }
</style>

<div class="container-fluid p-4">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom bg-gradient-primary p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-center position-relative" style="z-index: 1;">
                    <div class="flex-shrink-0 mb-3 mb-md-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=ffffff&color=0d6efd&size=120&bold=true"
                             class="rounded-circle shadow-sm border border-3 border-white" width="100" alt="Foto Profil">
                    </div>
                    <div class="flex-grow-1 ms-md-4 text-center text-md-start">
                        <h3 class="fw-bold mb-1">Selamat datang, {{ $anggota->nama_lengkap }}!</h3>
                        <p class="mb-0 text-white-50" style="font-size: 1.1rem;">
                            No. Induk: <strong>{{ $anggota->no_induk }}</strong>
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0 text-center text-md-end">
                        <span class="badge badge-soft-primary px-4 py-2 rounded-pill fs-6 border border-light shadow-sm">
                            <i class="fas fa-check-circle me-1"></i> {{ strtoupper($anggota->status ?? 'ANGGOTA AKTIF') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-custom card-hover h-100 p-4 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">TINGKATAN SAAT INI</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $anggota->tingkatan ?? '-' }}</h3>
                    </div>
                    <div class="icon-box icon-box-primary shadow-sm">
                        <i class="bi bi-award"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom card-hover h-100 p-4 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">STATUS TAGIHAN</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $tagihan_spp->count() }} <span class="fs-6 text-muted fw-normal">Bulan Pending</span></h3>
                    </div>
                    <div class="icon-box icon-box-warning shadow-sm">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom card-hover h-100 p-4 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">KEHADIRAN LATIHAN</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $persentase_hadir }}%</h3>
                    </div>
                    <div class="icon-box icon-box-success shadow-sm">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card card-custom h-100 border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0">Jadwal Latihan Terdekat</h5>
                    <a href="{{ route('presensi.anggota.index') }}" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body px-4 pt-3 pb-4 table-responsive">
                    <table class="table table-modern table-borderless align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $j)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3 text-primary"><i class="fas fa-dumbbell"></i></div>
                                        <span class="fw-bold text-dark">{{ $j->judul_kegiatan }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold text-dark">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $j->lokasi }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No Schedule" width="80" class="mb-3 opacity-50">
                                    <h6 class="fw-bold text-secondary">Belum Ada Jadwal</h6>
                                    <p class="text-muted small mb-0">Istirahat dulu, belum ada jadwal latihan dalam waktu dekat.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-custom h-100 border-0 bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark mb-0">Tagihan SPP Anda</h5>
                </div>
                <div class="card-body px-4 pt-4 pb-4">
                    @if($tagihan_spp->isEmpty())
                        <div class="text-center py-5">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                                <i class="fas fa-check fs-1 text-success"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Semua Lunas!</h5>
                            <p class="text-muted mb-0">Terima kasih telah disiplin membayar iuran bulanan.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($tagihan_spp as $spp)
                            <div class="bill-item bg-white shadow-sm d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3">
                                        <i class="bi bi-wallet2 fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">SPP Bulan {{ $spp->bulan ?? 'Ini' }}</h6>
                                        <span class="text-danger fw-bold fs-6">Rp {{ number_format($spp->nominal ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">Bayar</button>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
