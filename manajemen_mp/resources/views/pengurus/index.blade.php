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
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table text-center mb-0">
                                <thead class="bg-light text-muted">
                                    <tr>
                                        <th width="5%" class="py-3 ps-3">No</th>
                                        <th width="25%" class="py-3">Identitas Pengurus</th>
                                        <th width="20%" class="py-3">No. HP / WhatsApp</th>
                                        <th width="25%" class="py-3">Jabatan & Peran Sistem</th>
                                        <th width="15%" class="py-3">Status Akun</th>
                                        <th width="10%" class="py-3 pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_pengurus as $no => $item)
                                        <tr>
                                            <td class="text-muted small ps-3">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $item->nama_lengkap }}</div>
                                                <small class="text-muted d-block">ID: {{ $item->no_induk }}</small>
                                            </td>
                                            <td>
                                                <span class="text-dark">{{ $item->no_hp ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                    @foreach($item->roles as $role)
                                                        <span class="badge bg-primary-soft text-primary px-2 py-1 fw-semibold" style="font-size: 0.75rem; border-radius: 6px;">
                                                            {{ $role->nama_role }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->status == 'Aktif')
                                                    <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fw-bold border border-success-subtle" style="font-size: 0.75rem;">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill fw-bold border border-danger-subtle" style="font-size: 0.75rem;">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-3">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('pengurus.edit', $item->id) }}" class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs" title="Edit Pengurus">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-folder-x mb-3 d-block" style="font-size: 3rem; opacity: 0.3;"></i>
                                                <h6 class="fw-bold">Belum ada anggota yang diangkat menjadi pengurus struktural.</h6>
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
        .main-table thead th {
            background-color: #f8faff;
            font-size: 0.75rem;
            color: #8c98a5;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f9;
        }
        .main-table tbody td {
            padding: 16px 10px;
            border-bottom: 1px solid #f1f4f8;
            color: #495057;
            font-size: 0.9rem;
        }
        .main-table tbody tr:hover {
            background-color: #f8faff;
            transition: 0.2s;
        }

        /* WARNA BADGE CUSTOM */
        .bg-primary-soft { background-color: #e0f2fe; }
        .text-primary { color: #0ea5e9 !important; }

        .bg-success-soft { background-color: #d1e7dd; }
        .text-success { color: #0f5132 !important; }

        .bg-danger-soft { background-color: #f8d7da; }
        .text-danger { color: #842029 !important; }

        /* ANIMASI TOMBOL EDIT */
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
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
