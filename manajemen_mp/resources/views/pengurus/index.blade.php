@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Manajemen Pengurus</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Struktural Pengurus Cabang</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Pengurus Aktif</h5>
                                <p class="text-muted small mb-0">Kelola fungsionaris struktural dan kepengurusan organisasi.</p>
                            </div>

                            <a href="{{ route('pengurus.create') }}" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 12px;">
                                <i class="bi bi-shield-plus me-1"></i> Angkat Pengurus Baru
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 mb-3 shadow-sm" style="border-radius: 10px;">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table text-center">
                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Identitas Pengurus</th>
                                        <th>No. HP / WhatsApp</th>
                                        <th>Jabatan & Peran Sistem</th>
                                        <th>Status Akun</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_pengurus as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-center">{{ $item->nama_lengkap }}</div>
                                                <small class="text-muted d-block text-center">ID: {{ $item->no_induk }}</small>
                                            </td>
                                            <td>{{ $item->no_hp }}</td>
                                            <td>
                                                <div class="fw-bold text-primary mb-1">{{ ucwords($item->jabatan) }}</div>
                                                @foreach($item->roles as $role)
                                                    <span class="badge bg-secondary-soft text-secondary px-2 py-1 small" style="font-size: 10px;">{{ $role->nama_role }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-success-soft text-success px-3 border">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('pengurus.edit', $item->id) }}" class="btn btn-sm btn-action-edit fw-bold px-2 shadow-xs">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-muted py-4">Belum ada anggota yang diangkat menjadi pengurus struktural.</td>
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
        .main-table thead th { background-color: #f8faff; padding: 18px 10px; font-size: 0.75rem; color: #8c98a5; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
        .main-table tbody td { padding: 20px 10px; border-bottom: 1px solid #f1f4f8; color: #495057; font-size: 0.9rem; }
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .bg-secondary-soft { background-color: #f1f5f9; color: #475569; }
        .btn-action-edit { background-color: #fff8e6; color: #f59e0b; border: 1px solid #ffecb3; border-radius: 10px; transition: 0.3s; }
        .btn-action-edit:hover { background-color: #f59e0b !important; color: white !important; transform: translateY(-2px); }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
