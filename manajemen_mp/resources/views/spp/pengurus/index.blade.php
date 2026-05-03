@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    {{-- <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Manajemen SPP</h3>
            <p class="text-muted small mb-0">Kelola tagihan dan pembayaran SPP anggota Merpati Putih.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('spp.generate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning text-white px-3 py-2 shadow-sm" style="border-radius: 10px;" onclick="return confirm('Generate tagihan otomatis untuk bulan ini?')">
                    <i class="fas fa-cog me-1"></i> Generate Tagihan
                </button>
            </form>

            <a href="{{ route('spp.create') }}" class="btn btn-danger px-3 py-2 shadow-sm" style="border-radius: 10px; display: flex; align-items: center; text-decoration: none;">
                <i class="fas fa-plus me-1"></i> Tambah Manual
            </a>

            <button class="btn btn-info text-white px-3 py-2 shadow-sm" style="border-radius: 10px; background-color: #0099bc;">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div> --}}

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="text-start">
            <h3 class="fw-bold text-dark mb-1">Manajemen SPP</h3>
            <p class="text-muted small mb-0">Kelola tagihan dan pembayaran SPP anggota Merpati Putih.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <form action="{{ route('spp.generate') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-warning text-white px-3 py-2 shadow-sm d-flex align-items-center h-100" style="border-radius: 10px;" onclick="return confirm('Generate tagihan otomatis untuk bulan ini?')">
                    <i class="fas fa-cog me-2"></i> Generate
                </button>
            </form>

            <a href="{{ route('spp.export.excel', request()->all()) }}" class="btn btn-success px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 10px; text-decoration: none;">
                <i class="fas fa-file-excel me-2"></i> Excel
            </a>

            <a href="{{ route('spp.export.pdf', request()->all()) }}" class="btn btn-danger px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 10px; text-decoration: none;">
                <i class="fas fa-file-pdf me-2"></i> PDF
            </a>

            <a href="{{ route('spp.create') }}" class="btn btn-primary px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 10px; text-decoration: none;">
                <i class="fas fa-plus me-2"></i> Tambah Manual
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-success border-4 h-100" style="border-radius: 15px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-success small fw-bold mb-1 uppercase">SPP LUNAS</p>
                        <h3 class="fw-bold mb-0">{{ $stats['lunas'] }}</h3>
                    </div>
                    <i class="fas fa-check-circle text-success fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-warning border-4 h-100" style="border-radius: 15px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-warning small fw-bold mb-1 uppercase">PENDING</p>
                        <h3 class="fw-bold mb-0">{{ $stats['pending'] }}</h3>
                    </div>
                    <i class="fas fa-clock text-warning fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger border-4 h-100" style="border-radius: 15px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-danger small fw-bold mb-1 uppercase">BELUM BAYAR</p>
                        <h3 class="fw-bold mb-0">{{ $stats['belum_bayar'] }}</h3>
                    </div>
                    <i class="fas fa-exclamation-triangle text-danger fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary border-4 h-100" style="border-radius: 15px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-primary small fw-bold mb-1 uppercase">TOTAL MASUK</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_pemasukan'], 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-money-bill-wave text-primary fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Data SPP -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header border-0 py-3 text-white" style="background-color: #0d6efd;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter Data SPP</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('spp.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Tahun</label>
                    <select name="tahun" class="form-select border-0 bg-light">
                        <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                        <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Bulan</label>
                    <select name="bulan" class="form-select border-0 bg-light">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select border-0 bg-light">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Cari Anggota</label>
                    <input type="text" name="cari" class="form-control border-0 bg-light" placeholder="Nama anggota..." value="{{ request('cari') }}">
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="background-color: #0d6efd; border-radius: 10px;">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('spp.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Tagihan Section -->
    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header border-0 py-3 text-white d-flex justify-content-between align-items-center" style="background-color: #0d6efd;">
            <h6 class="mb-0 fw-bold"><i class="fas fa-money-check me-2"></i>Daftar Tagihan SPP</h6>
        </div>
        <div class="card-body p-0">
            @if($data_spp->isEmpty())
                <div class="py-5 text-center">
                    <i class="fas fa-file-invoice-dollar text-muted mb-4" style="font-size: 80px; opacity: 0.2;"></i>
                    <h5 class="fw-bold text-muted">Belum ada data tagihan SPP</h5>
                    <p class="text-muted small">Silakan tambah manual atau generate otomatis.</p>
                    <a href="{{ route('spp.create') }}" class="btn btn-danger text-white px-4 py-2 mt-2" style="border-radius: 10px;">Tambah Manual</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted uppercase">
                            <tr>
                                <th class="ps-4" style="width: 30%;">Anggota</th>
                                <th>Periode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_spp as $spp)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar Inisial -->
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm"
                                             style="width: 40px; height: 40px; font-size: 14px; font-weight: bold; background: linear-gradient(45deg, #0d6efd, #0099bc);">
                                            {{ strtoupper(substr($spp->anggota->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $spp->anggota->nama }}</div>
                                            <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.75rem;">
                                                ID: {{ $spp->anggota_id }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $spp->bulan }}</div>
                                    <div class="small text-muted">{{ $spp->tahun }}</div>
                                </td>
                                <td class="fw-bold text-dark">
                                    Rp {{ number_format($spp->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($spp->status == 'lunas')
                                        <span class="badge bg-success-soft text-success rounded-pill px-3">
                                            <i class="fas fa-check-circle me-1"></i> Lunas
                                        </span>
                                    @elseif($spp->status == 'belum_bayar' && \Carbon\Carbon::parse($spp->jatuh_tempo)->isPast())
                                        <span class="badge bg-danger-soft text-danger rounded-pill px-3">
                                            <i class="fas fa-exclamation-circle me-1"></i> Terlambat
                                        </span>
                                    @else
                                        <span class="badge bg-warning-soft text-warning rounded-pill px-3">
                                            <i class="fas fa-clock me-1"></i> Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($spp->status != 'lunas')
                                        <form action="{{ route('spp.bayar', $spp->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm"
                                                    style="border-radius: 8px;"
                                                    onclick="return confirm('Konfirmasi pembayaran untuk {{ $spp->anggota->nama }}?')">
                                                Bayar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success small fw-bold">
                                            <i class="fas fa-check-double me-1"></i> Terbayar
                                        </span>
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
    .bg-light { background-color: #f8f9fa !important; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.8px; font-size: 10px; font-weight: 700; }
    .fs-1 { font-size: 2.2rem !important; opacity: 0.2; }

    /* Soft Badge Styles */
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.12); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.12); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.15); }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
        transition: 0.2s;
    }

    .form-select, .form-control {
        border-radius: 10px;
        padding: 0.6rem 1rem;
    }

    .card {
        transition: transform 0.2s;
    }
</style>
@endsection
