@extends('layout.app')

@section('content')
    @php
        // Ambil nama role user yang login saat ini
        $userRole = auth()->user()->role->nama_role ?? 'Anggota';
    @endphp

    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Manajemen Pelatih</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">SDM Merpati Putih</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Pelatih Aktif</h5>
                                <p class="text-muted small mb-0">Kelola sertifikasi dan masa berlaku SK Pelatih Cabang.</p>
                            </div>

                            <!-- HANYA PENGURUS YANG BISA MELIHAT TOMBOL INI -->
                            @if($userRole == 'Pengurus')
                            <a href="{{ route('pelatih.upgrade') }}" class="btn btn-primary px-4 py-2 shadow-sm"
                                style="border-radius: 12px;">
                                <i class="icon-plus me-1"></i> Upgrade Anggota ke Pelatih
                            </a>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table text-center">
                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Identitas Pelatih</th>
                                        <th>No. SK & Masa Berlaku</th>
                                        <th>Kolat</th>
                                        <th>Status SK</th>

                                        <!-- KOLOM AKSI HANYA MUNCUL JIKA PENGURUS -->
                                        @if($userRole == 'Pengurus')
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelatih as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-start text-center">{{ $item->nama_lengkap }}</div>
                                                <small class="text-muted text-start d-block text-center">ID: {{ $item->no_induk }}</small>
                                            </td>
                                            <td class="text-start text-center">
                                                <div class="small fw-bold">{{ $item->no_sk }}</div>
                                                <div class="text-muted" style="font-size: 11px;">Hingga: {{ \Carbon\Carbon::parse($item->masa_berlaku)->format('d M Y') }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-soft text-info px-3">{{ $item->kolat->nama_kolat ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $isExpired = \Carbon\Carbon::now()->gt($item->masa_berlaku);
                                                @endphp
                                                <span class="badge rounded-pill {{ !$isExpired ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }} px-3 border">
                                                    {{ !$isExpired ? 'Berlaku' : 'Expired' }}
                                                </span>
                                            </td>

                                            <!-- TOMBOL EDIT HANYA MUNCUL JIKA PENGURUS -->
                                            @if($userRole == 'Pengurus')
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                     <a href="{{ route('pelatih.edit', $item->id) }}"
                                                        class="btn btn-sm btn-action-edit fw-bold px-2 shadow-xs">
                                                        <i class="bi bi-pencil"></i> 
                                                    </a>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gunakan Style yang sama dengan yang kamu berikan sebelumnya -->
    <style>
        .main-table thead th { background-color: #f8faff; padding: 18px 10px; font-size: 0.75rem; color: #8c98a5; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
        .main-table tbody td { padding: 20px 10px; border-bottom: 1px solid #f1f4f8; color: #495057; font-size: 0.9rem; }
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .bg-danger-soft { background-color: #fff5f5; color: #e53e3e; }
        .bg-info-soft { background-color: #e0f2fe; color: #0ea5e9; }
        .btn-action-edit { background-color: #fff8e6; color: #f59e0b; border: 1px solid #ffecb3; border-radius: 10px; transition: 0.3s; }
        .btn-action-edit:hover { background-color: #f59e0b !important; color: white !important; transform: translateY(-2px); }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
