@extends('layout.app')

@section('content')
<div class="container-fluid py-4 text-start">
    <div class="page-header mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Laporan</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Laporan SPP</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Laporan Pembayaran SPP</h3>
        <p class="text-muted small">Rekapitulasi status tagihan iuran bulanan anggota Cabang Jember.</p>
    </div>

    {{-- <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form action="{{ route('laporan.spp') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3 text-start">
                    <label class="form-label small fw-bold">Pilih Bulan</label>
                    <select name="bulan" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-start">
                    <label class="form-label small fw-bold">Tahun</label>
                    <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="form-control border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                </div>
                <div class="col-md-3 text-start">
                    <label class="form-label small fw-bold">Status Tagihan</label>
                    <select name="status" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 12px; padding: 12px;">
                        <i class="icon-magnifier me-2"></i> Filter Laporan
                    </button>
                </div>
            </form>
        </div>
    </div> --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form action="{{ route('laporan.spp') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3 text-start">
                    <label class="form-label small fw-bold">Pilih Kolat</label>
                    <select name="kolat_id" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                        <option value="">Semua Kolat</option>
                        @foreach($data_kolat as $kolat)
                            <option value="{{ $kolat->id }}" {{ request('kolat_id') == $kolat->id ? 'selected' : '' }}>
                                {{ $kolat->nama_kolat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 text-start">
                    <label class="form-label small fw-bold">Bulan</label>
                    <select name="bulan" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 text-start">
                    <label class="form-label small fw-bold">Tahun</label>
                    <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="form-control border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                </div>

                <div class="col-md-2 text-start">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 12px; padding: 10px;">
                        <i class="icon-magnifier me-2"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="text-start">
                    <h6 class="fw-bold mb-0 text-dark">Rekap Pembayaran SPP</h6>
                    <p class="text-muted small mb-0">Ditemukan {{ $data_spp->count() }} data tagihan.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('spp.export.excel', request()->all()) }}" class="btn btn-success px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-excel me-2"></i> Excel
                    </a>
                    <a href="{{ route('spp.export.pdf', request()->all()) }}" class="btn btn-danger px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-pdf me-2"></i> PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle main-table">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Identitas Anggota</th>
                            <th class="text-center">Periode Tagihan</th>
                            <th class="text-end">Nominal (Rp)</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_spp as $no => $spp)
                        <tr>
                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark text-start">{{ $spp->anggota->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted text-start d-block mt-1" style="font-size: 11px;">Kolat: {{ $spp->kolat->nama_kolat ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $spp->bulan }} {{ $spp->tahun }}</span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ number_format($spp->nominal, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($spp->status == 'lunas')
                                    <span class="badge bg-success-soft text-success border-success border px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> LUNAS
                                    </span>
                                @else
                                    <span class="badge bg-warning-soft text-warning border-warning border px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock-history me-1"></i> BELUM BAYAR
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="icon-docs text-muted mb-3 d-block" style="font-size: 40px; opacity: 0.5;"></i>
                                <span class="text-muted small">Data SPP tidak ditemukan untuk filter tersebut.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .main-table thead th {
        background-color: #f8faff;
        padding: 18px 10px;
        font-size: 0.75rem;
        color: #8c98a5;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-bottom: 2px solid #edf2f9;
    }

    .main-table tbody td {
        padding: 18px 10px;
        border-bottom: 1px solid #f1f4f8;
        color: #495057;
        font-size: 0.9rem;
    }

    .bg-success-soft {
        background-color: #f0fdf4;
        color: #16a34a;
    }

    .bg-warning-soft {
        background-color: #fefce8;
        color: #ca8a04;
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
