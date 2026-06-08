@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Data Anggota</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Manajemen Keanggotaan TapakMP</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Anggota PPS Merpati Putih</h5>
                                <p class="text-muted small mb-0">Cabang Jember - Kelola informasi induk anggota.</p>
                            </div>
                            <a href="{{ route('anggota.create') }}" class="btn btn-primary px-4 py-2 shadow-sm"
                                style="border-radius: 12px;">
                                <i class="icon-plus me-1"></i> Tambah Anggota
                            </a>
                        </div> --}}
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Anggota PPS Merpati Putih</h5>
                                <p class="text-muted small mb-0">Cabang Jember - Kelola informasi induk anggota.</p>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('anggota.export.excel') }}"
                                    class="btn btn-success px-3 py-2 shadow-sm d-flex align-items-center"
                                    style="border-radius: 12px; font-size: 14px;">
                                    <i class="bi bi-file-earmark-excel me-2"></i> Excel
                                </a>

                                <a href="{{ route('anggota.export.pdf') }}"
                                    class="btn btn-danger px-3 py-2 shadow-sm d-flex align-items-center"
                                    style="border-radius: 12px; font-size: 14px;">
                                    <i class="bi bi-file-earmark-pdf me-2"></i> PDF
                                </a>

                                <a href="{{ route('anggota.create') }}"
                                    class="btn btn-primary px-4 py-2 shadow-sm d-flex align-items-center"
                                    style="border-radius: 12px; font-size: 14px;">
                                    <i class="icon-plus me-2"></i> Tambah Anggota
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table">
                                <thead>
                                    <tr class="text-center">
                                        <th width="60">No</th>
                                        <th>Identitas Anggota</th>
                                        <th>Email</th>
                                        <th>JK</th>
                                        <th>Tingkatan & Kolat</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_anggota as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-start ps-3">{{ $item->nama_lengkap }}
                                                </div>
                                                <small class="text-muted text-start ps-3 d-block">ID:
                                                    {{ $item->no_induk }}</small>
                                            </td>
                                            <td class="text-start">
                                                <div class="text-dark small fw-medium">{{ $item->user->email ?? '-' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge-jk {{ $item->jenis_kelamin == 'P' ? 'bg-danger' : 'bg-primary' }} shadow-xs">
                                                    {{ $item->jenis_kelamin }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-info-soft text-info px-3">{{ $item->tingkatan }}</span>
                                                <div class="small text-muted mt-1" style="font-size: 10px;">Kolat
                                                    {{ $item->kolat->nama_kolat ?? '-' }}</div>
                                            </td>
                                            <td class="text-center">
                                                {{-- Karena di filter 'anggota', maka jabatan otomatis Anggota --}}
                                                <span class="badge bg-light text-dark border px-3"
                                                    style="border-radius: 8px;">Anggota</span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $item->status == 'Aktif' ? 'bg-success-soft text-success border-success' : 'bg-secondary-soft text-secondary border-secondary' }} px-3 border">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-action-detail fw-bold px-3 shadow-xs"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetail{{ $item->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>

                                                    <a href="{{ route('anggota.edit', $item->id) }}"
                                                        class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- MODAL DETAIL --}}
                                        <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content"
                                                    style="border-radius: 20px; border: none; overflow: hidden;">
                                                    <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                                                        <h5 class="modal-title fw-bold">
                                                            <i class="icon-user-check me-2"></i>Profil Lengkap Anggota
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center border-end">
                                                                <div class="mb-3">
                                                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                                                        style="width: 120px; height: 120px; border: 4px solid #f8f9fa;">
                                                                        <i class="icon-user text-primary"
                                                                            style="font-size: 60px;"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="fw-bold mb-1">{{ $item->nama_lengkap }}</h5>
                                                                <p class="text-muted small mb-3">ID: {{ $item->no_induk }}
                                                                </p>
                                                                <span
                                                                    class="badge rounded-pill {{ $item->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }} px-4 py-2">
                                                                    {{ $item->status }}
                                                                </span>
                                                            </div>

                                                            <div class="col-md-8 ps-md-4 text-start">
                                                                <div class="row g-3">
                                                                    <div class="col-12">
                                                                        <h6
                                                                            class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                                            Informasi Organisasi</h6>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Asal Kolat</small>
                                                                        <span
                                                                            class="fw-bold text-dark">{{ $item->kolat->nama_kolat ?? '-' }}</span>
                                                                    </div>
                                                                    {{-- <div class="col-6">
                                                                        <small class="text-muted d-block">Jabatan</small>
                                                                        <span
                                                                            class="fw-bold text-primary text-uppercase">{{ $item->role->nama_role ?? 'ANGGOTA' }}</span>
                                                                    </div> --}}
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Jabatan
                                                                            Terdaftar</small>
                                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                                            @forelse($item->roles as $role)
                                                                                <span
                                                                                    class="badge bg-primary text-white text-uppercase"
                                                                                    style="font-size: 10px;">{{ $role->nama_role }}</span>
                                                                            @empty
                                                                                <span
                                                                                    class="badge bg-secondary text-white text-uppercase"
                                                                                    style="font-size: 10px;">ANGGOTA</span>
                                                                            @endforelse
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Tingkatan</small>
                                                                        <span
                                                                            class="fw-bold text-dark">{{ $item->tingkatan }}</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Tanggal
                                                                            Gabung</small>
                                                                        <span
                                                                            class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->tgl_gabung)->format('d M Y') }}</span>
                                                                    </div>

                                                                    <div class="col-12 mt-4">
                                                                        <h6
                                                                            class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                                            Kontak & Pribadi</h6>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Tempat, Tgl
                                                                            Lahir</small>
                                                                        <span
                                                                            class="fw-bold text-dark">{{ $item->tempat_lahir }},
                                                                            {{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d M Y') }}</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">No.
                                                                            WhatsApp</small>
                                                                        <a href="https://wa.me/{{ $item->no_hp }}"
                                                                            target="_blank"
                                                                            class="text-decoration-none fw-bold text-success">
                                                                            <i
                                                                                class="icon-phone me-1"></i>{{ $item->no_hp }}
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <small class="text-muted d-block">Alamat
                                                                            Lengkap</small>
                                                                        <span
                                                                            class="fw-bold text-dark">{{ $item->alamat }}</span>
                                                                    </div>
                                                                    <div class="col-12 mt-3">
                                                                        <div class="p-3 rounded-3"
                                                                            style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                                                            <h6 class="fw-bold text-danger mb-2"><i
                                                                                    class="icon-heart me-2"></i>Catatan
                                                                                Medis</h6>
                                                                            <p class="mb-0 text-dark small">
                                                                                {{ $item->catatan_medis ?? 'Tidak ada riwayat medis khusus.' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-4 pb-4">
                                                        <button type="button" class="btn btn-light px-4"
                                                            data-bs-dismiss="modal"
                                                            style="border-radius: 10px;">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted small">Belum ada data
                                                anggota terdaftar.</td>
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

        .badge-jk {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            color: white;
            border-radius: 50% !important;
        }

        .btn-action-detail {
            background-color: #f0f7ff;
            color: #0d6efd;
            border: 1px solid #d0e4ff;
            border-radius: 10px;
            transition: 0.3s;
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

        .btn-action-detail:hover {
            background-color: #0d6efd !important;
            color: white !important;
            transform: translateY(-2px);
        }

        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
