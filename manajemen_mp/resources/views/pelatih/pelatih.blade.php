@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header & Breadcrumb -->
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-danger fw-bold small" aria-current="page">Data Pelatih</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Manajemen Pelatih TapakMP</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <!-- Header Tabel -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Pelatih PPS Merpati Putih</h5>
                                <p class="text-muted small mb-0">Cabang Jember - Kelola informasi lisensi dan tingkatan pelatih.</p>
                            </div>
                            <a href="#" class="btn btn-danger px-4 py-2 shadow-sm"
                                style="border-radius: 12px; background-color: #b91c1c; border: none; font-size: 14px;">
                                <i class="icon-plus me-1"></i> Tambah Pelatih
                            </a>
                        </div>

                        <!-- Area Tabel -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table">
                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Profil Pelatih</th>
                                        <th class="text-center">Tingkatan</th>
                                        <th class="text-center">Nomor SK / Lisensi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Contoh Baris 1 -->
                                    <tr>
                                        <td class="text-muted small text-center">1</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="text-start">
                                                    <div class="fw-bold text-dark">Agus Setiawan</div>
                                                    <small class="text-muted">ID: MP-00192</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-soft text-info px-3">Mandiri</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary fw-bold small bg-light px-2 py-1 rounded border">SK-JBR-001/2026</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-success-soft text-success border border-success px-3">Aktif</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-action-detail fw-bold px-3 shadow-xs">Detail</button>
                                                <button class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs">Edit</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Contoh Baris 2 -->
                                    <tr>
                                        <td class="text-muted small text-center">2</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <div class="fw-bold text-dark">Siti Aminah</div>
                                                    <small class="text-muted">ID: MP-00205</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-soft text-info px-3">Balik II</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary fw-bold small bg-light px-2 py-1 rounded border">SK-JBR-002/2026</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-success-soft text-success border border-success px-3">Aktif</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-action-detail fw-bold px-3 shadow-xs">Detail</button>
                                                <button class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs">Edit</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Style CSS Khusus Tampilan -->
    <style>
        .main-table thead th {
            background-color: #f8faff;
            padding: 18px 10px;
            font-size: 11px;
            color: #8c98a5;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f9;
        }

        .main-table tbody td {
            padding: 16px 10px;
            border-bottom: 1px solid #f1f4f8;
            color: #495057;
            font-size: 14px;
        }

        /* Avatar Bulat */
        .badge-avatar {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            border-radius: 10px; /* Modern Soft Square */
            font-size: 12px;
        }

        /* Warna Soft UI */
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .bg-info-soft { background-color: #e0f2fe; color: #0ea5e9; }
        .bg-danger-soft { background-color: #fff5f5; color: #e53e3e; }

        /* Button Styling */
        .btn-action-detail {
            background-color: #f0f7ff; color: #0d6efd; border: 1px solid #d0e4ff;
            border-radius: 8px; transition: 0.2s;
        }

        .btn-action-edit {
            background-color: #fff8e6; color: #f59e0b; border: 1px solid #ffecb3;
            border-radius: 8px; transition: 0.2s;
        }

        .btn-action-detail:hover { background-color: #0d6efd !important; color: white !important; }
        .btn-action-edit:hover { background-color: #f59e0b !important; color: white !important; }

        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
        .table-hover tbody tr:hover { background-color: #fcfcfc; }
    </style>
@endsection
