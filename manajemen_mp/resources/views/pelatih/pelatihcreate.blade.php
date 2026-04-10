@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header & Breadcrumb -->
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Data Kolat</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Manajemen Kelompok Latihan</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <!-- Title & Action Button -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                hide<h5 class="card-title mb-0 fw-bold text-dark">Daftar Kolat PPS Merpati Putih</h5>
                                <p class="text-muted small mb-0">Cabang Jember - Kelola lokasi dan unit latihan.</p>
                            </div>
                            <a href="{{ route('kolat.create') }}" class="btn btn-primary px-4 py-2 shadow-sm"
                                style="border-radius: 12px;">
                                <i class="icon-plus me-1"></i> Tambah Kolat
                            </a>
                        </div>

                        <!-- Alert Success -->
                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                                <i class="icon-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table">
                                <thead>
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Nama Kelompok Latihan</th>
                                        <th>Alamat Lokasi</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_kolat as $item)
                                        <tr>
                                            <td class="text-muted small text-center fw-bold">#{{ $item->id }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">Kolat {{ $item->nama_kolat }}</div>
                                                <small class="text-muted d-block">Pusat Latihan Unit</small>
                                            </td>
                                            <td class="text-start">
                                                <span class="text-muted small">
                                                    <i class="icon-map-pin me-1"></i> {{ $item->alamat_kolat ?? 'Alamat belum diatur' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('kolat.edit', $item->id) }}"
                                                        class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs">
                                                        <i class="icon-pencil"></i> Edit
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form action="{{ route('kolat.destroy', $item->id) }}" method="POST"
                                                          onsubmit="return confirm('Hapus Kolat ini? Anggota di dalamnya mungkin akan terdampak.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-action-delete fw-bold px-3 shadow-xs">
                                                            <i class="icon-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <p class="text-muted">Belum ada data kolat yang tersedia.</p>
                                            </td>
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
        /* Samakan dengan Style Blade Anggota Anda */
        .main-table { text-align: center; vertical-align: middle; }
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
        .btn-action-edit {
            background-color: #fff8e6;
            color: #f59e0b;
            border: 1px solid #ffecb3;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-action-edit:hover {
            background-color: #f59e0b !important;
            color: white !important;
            transform: translateY(-2px);
        }
        .btn-action-delete {
            background-color: #fff5f5;
            color: #dc3545;
            border: 1px solid #ffe5e5;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-action-delete:hover {
            background-color: #dc3545 !important;
            color: white !important;
            transform: translateY(-2px);
        }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
