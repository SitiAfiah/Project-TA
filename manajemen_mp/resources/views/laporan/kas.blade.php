@extends('layout.app')

@section('content')
<div class="container-fluid py-4 text-start">
    <div class="page-header mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Laporan</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Laporan Kas</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Laporan Keuangan (Kas)</h3>
        <p class="text-muted small">Rekapitulasi dan filter arus kas masuk dan keluar organisasi.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form action="{{ route('laporan.kas') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4 text-start">
                    <label class="form-label small fw-bold">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="form-control border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                </div>
                <div class="col-md-4 text-start">
                    <label class="form-label small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                </div>
                <div class="col-md-4 text-start">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 12px; padding: 12px;">
                        <i class="icon-magnifier me-2"></i> Filter Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="text-start">
                    <h6 class="fw-bold mb-0 text-dark">Rincian Transaksi Keuangan</h6>
                    <p class="text-muted small mb-0">Menampilkan {{ $data_kas->count() }} catatan transaksi.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('kas.export.excel', request()->all()) }}" class="btn btn-success px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-excel me-2"></i> Excel
                    </a>
                    <a href="{{ route('kas.export.pdf', request()->all()) }}" class="btn btn-danger px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-pdf me-2"></i> PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle main-table">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th width="130">Tanggal</th>
                            <th>Kategori & Keterangan</th>
                            <th class="text-end">Masuk (Rp)</th>
                            <th class="text-end">Keluar (Rp)</th>
                            <th class="text-end">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_kas as $no => $kas)
                        <tr>
                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($kas->tanggal)->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $kas->jenis == 'masuk' ? 'bg-success-soft text-success border-success' : 'bg-danger-soft text-danger border-danger' }} border px-3 py-1 mb-1 rounded-pill" style="font-size: 10px;">
                                    {{ strtoupper($kas->kategori) }}
                                </span>
                                <div class="small text-muted mt-1">{{ $kas->keterangan ?? 'Tanpa keterangan' }}</div>
                            </td>
                            <td class="text-end text-success fw-bold">
                                {{ $kas->jenis == 'masuk' ? '+ ' . number_format($kas->nominal, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end text-danger fw-bold">
                                {{ $kas->jenis == 'keluar' ? '- ' . number_format($kas->nominal, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ number_format($kas->saldo_akhir, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="icon-wallet text-muted mb-3 d-block" style="font-size: 40px; opacity: 0.5;"></i>
                                <span class="text-muted small">Data transaksi tidak ditemukan untuk periode ini.</span>
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
        background-color: #e6fffa;
        color: #38b2ac;
    }

    .bg-danger-soft {
        background-color: #fef2f2;
        color: #ef4444;
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
