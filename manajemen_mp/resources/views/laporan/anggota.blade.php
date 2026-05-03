@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Laporan</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Laporan Anggota</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Laporan Keanggotaan</h3>
        <p class="text-muted small">Rekapitulasi dan filter data anggota Merpati Putih Cabang Jember.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form action="{{ route('laporan.anggota') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4 text-start">
                    <label class="form-label small fw-bold">Pilih Kolat</label>
                    <select name="kolat_id" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                        <option value="">Semua Kolat</option>
                        @foreach($data_kolat as $kolat)
                            <option value="{{ $kolat->id }}" {{ request('kolat_id') == $kolat->id ? 'selected' : '' }}>{{ $kolat->nama_kolat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-start">
                    <label class="form-label small fw-bold">Status Anggota</label>
                    <select name="status" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 12px; padding: 12px;">
                        <i class="icon-magnifier me-2"></i> Tampilkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4 text-start">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="text-start">
                    <h6 class="fw-bold mb-0 text-dark">Hasil Pencarian</h6>
                    <p class="text-muted small mb-0">Menampilkan {{ $data_anggota->count() }} data anggota.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('anggota.export.excel', request()->all()) }}" class="btn btn-success px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-excel me-2"></i> Excel
                    </a>
                    <a href="{{ route('anggota.export.pdf', request()->all()) }}" class="btn btn-danger px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-pdf me-2"></i> PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle main-table">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">No</th>
                            <th>Identitas Anggota</th>
                            <th class="text-center">Tingkatan & Kolat</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_anggota as $no => $item)
                        <tr>
                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark text-start ps-3">{{ $item->nama_lengkap }}</div>
                                <small class="text-muted text-start ps-3 d-block">ID: {{ $item->no_induk }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-soft text-info px-3 py-2 rounded-pill">{{ $item->tingkatan }}</span>
                                <div class="small text-muted mt-2" style="font-size: 10px;">
                                    Kolat {{ $item->kolat->nama_kolat ?? '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $item->status == 'Aktif' ? 'bg-success-soft text-success border-success' : 'bg-secondary-soft text-secondary border-secondary' }} px-3 py-2 border">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="icon-folder-alt text-muted mb-3 d-block" style="font-size: 40px; opacity: 0.5;"></i>
                                <span class="text-muted small">Data tidak ditemukan. Silakan sesuaikan filter Anda.</span>
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
        padding: 20px 10px;
        border-bottom: 1px solid #f1f4f8;
        color: #495057;
        font-size: 0.9rem;
    }

    .bg-success-soft {
        background-color: #e6fffa;
        color: #38b2ac;
    }

    .bg-info-soft {
        background-color: #e0f2fe;
        color: #0ea5e9;
    }

    .bg-secondary-soft {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .shadow-xs {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
