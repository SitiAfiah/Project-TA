@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            {{-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Penilaian Pelatih</li>
                </ol>
            </nav> --}}
            <h3 class="fw-bold text-dark">SDM Merpati Putih</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Evaluasi Kinerja Pelatih</h5>
                                <p class="text-muted small mb-0">Kelola penilaian bulanan berdasarkan kompetensi pelatih cabang.</p>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table text-center">
                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Identitas Pelatih</th>
                                        <th>No. SK & Masa Berlaku</th>
                                        <th>Kolat</th>
                                        <th>Status SK</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_pelatih as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-center">{{ $item->nama_lengkap }}</div>
                                                <small class="text-muted d-block text-center">ID: {{ $item->no_induk }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="small fw-bold">{{ $item->no_sk ?? '-' }}</div>
                                                <div class="text-muted" style="font-size: 11px;">
                                                    Hingga: {{ $item->masa_berlaku ? \Carbon\Carbon::parse($item->masa_berlaku)->format('d M Y') : '-' }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-soft text-info px-3">{{ $item->kolat->nama_kolat ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $isExpired = $item->masa_berlaku ? \Carbon\Carbon::now()->gt($item->masa_berlaku) : true;
                                                @endphp
                                                <span class="badge rounded-pill {{ !$isExpired ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }} px-3 border">
                                                    {{ !$isExpired ? 'Berlaku' : 'Expired' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('penilaian.create', $item->id) }}"
                                                        class="btn btn-sm btn-action-evaluate fw-bold px-3 shadow-xs">
                                                        <i class="bi bi-star-fill me-1"></i> Beri Nilai
                                                    </a>
                                                    <a href="{{ route('penilaian.show', $item->id) }}" class="btn btn-sm btn-outline-primary fw-bold px-3 shadow-xs" style="border-radius:10px;">
        <i class="bi bi-bar-chart-fill"></i> Rekap
    </a>
</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">Data pelatih tidak ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
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
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .bg-danger-soft { background-color: #fff5f5; color: #e53e3e; }
        .bg-info-soft { background-color: #e0f2fe; color: #0ea5e9; }

        /* Style Tombol Beri Nilai (Warna Biru Indigo agar elegan) */
        .btn-action-evaluate {
            background-color: #eef2ff;
            color: #4f46e5;
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-action-evaluate:hover {
            background-color: #4f46e5 !important;
            color: white !important;
            transform: translateY(-2px);
        }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
