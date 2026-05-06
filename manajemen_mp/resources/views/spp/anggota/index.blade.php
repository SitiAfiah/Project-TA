@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Tagihan SPP Saya</h3>
            <p class="text-muted small mb-0">Lihat riwayat dan lakukan pembayaran SPP bulanan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 10px;">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header border-0 py-3 text-white" style="background-color: #0d6efd;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-file-invoice me-2"></i>Daftar Tagihan</h6>
        </div>
        <div class="card-body p-0">
            @if($data_spp->isEmpty())
                <div class="py-5 text-center">
                    <i class="fas fa-smile text-muted mb-3" style="font-size: 60px; opacity: 0.3;"></i>
                    <h5 class="fw-bold text-muted">Belum ada tagihan SPP untuk Anda.</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted" style="font-size: 13px; text-transform: uppercase;">
                            <tr>
                                <th class="ps-4">Periode</th>
                                <th>Nominal</th>
                                <th>Batas Bayar</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_spp as $spp)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">
                                    {{ $spp->bulan }} {{ $spp->tahun }}
                                </td>
                                <td class="text-dark">Rp {{ number_format($spp->nominal, 0, ',', '.') }}</td>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($spp->jatuh_tempo)->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    @if($spp->status == 'lunas')
                                        <span class="badge bg-success-soft text-success rounded-pill px-3">Lunas</span>
                                    @elseif($spp->status == 'pending')
                                        <span class="badge bg-warning-soft text-warning rounded-pill px-3">Menunggu Verifikasi</span>
                                    @else
                                        <span class="badge bg-danger-soft text-danger rounded-pill px-3">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($spp->status == 'belum_bayar')
                                        <a href="{{ route('spp.anggota.formBayar', $spp->id) }}" class="btn btn-sm btn-primary px-3 shadow-sm" style="border-radius: 8px;">
                                            <i class="fas fa-upload me-1"></i> Upload Bukti
                                        </a>
                                    @elseif($spp->status == 'pending')
                                        <button class="btn btn-sm btn-secondary px-3" style="border-radius: 8px;" disabled>Diproses</button>
                                    @else
                                        <i class="fas fa-check-circle text-success fs-5"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.12); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.12); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.15); }
</style>
@endsection
