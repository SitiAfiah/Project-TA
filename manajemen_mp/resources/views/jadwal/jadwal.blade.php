@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Jadwal Latihan</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Manajemen Latihan TapakMP</h3>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Agenda PPS Merpati Putih</h5>
                                <p class="text-muted small mb-0">Cabang Jember - Kelola jadwal rutin dan pengumuman latihan.</p>
                            </div>
                            <a href="{{ route('jadwal.create') }}" class="btn btn-primary px-4 py-2 shadow-sm"
                                style="border-radius: 12px;">
                                <i class="icon-plus me-1"></i> Buat Jadwal
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table">
                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Kegiatan & Deskripsi</th>
                                        <th>Kolat</th>
                                        <th>Pelatih Bertugas</th>
                                        <th>Waktu & Lokasi</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_jadwal as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-start ps-3">{{ $item->judul_kegiatan }}</div>
                                                <small class="text-muted text-start ps-3 d-block">{{ Str::limit($item->deskripsi, 40) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border px-3" style="border-radius: 8px;">
                                                    {{ $item->kolat->nama_kolat ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-bold text-dark small">{{ $item->pelatih->nama_lengkap ?? 'Belum Ditentukan' }}</div>
                                                <small class="text-muted" style="font-size: 10px;">ID: {{ $item->pelatih->no_induk ?? '-' }}</small>
                                            </td>
                                            <td class="text-start ps-4">
                                                <div class="small fw-bold text-dark">
                                                    <i class="icon-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="icon-clock me-1"></i> {{ substr($item->jam_mulai, 0, 5) }} - {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : 'Selesai' }}
                                                </div>
                                                <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                    <i class="icon-map-pin me-1"></i> {{ $item->lokasi }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $bg_color = 'bg-secondary-soft text-secondary';
                                                    if($item->jenis == 'Rutin') $bg_color = 'bg-success-soft text-success border-success';
                                                    elseif($item->jenis == 'Tambahan') $bg_color = 'bg-info-soft text-info border-info';
                                                    elseif($item->jenis == 'Pengumuman') $bg_color = 'bg-danger-soft text-danger border-danger';
                                                @endphp
                                                <span class="badge rounded-pill {{ $bg_color }} px-3 border">
                                                    {{ $item->jenis }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-action-detail fw-bold px-3 shadow-xs"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailJadwal{{ $item->id }}">
                                                        <i class="icon-eye"></i> Detail
                                                    </button>

                                                    @if($item->jenis != 'Pengumuman')
                                                        <a href="#" class="btn btn-sm btn-outline-primary fw-bold px-3" style="border-radius: 10px;">
                                                            <i class="icon-check-square"></i> Absen
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('jadwal.edit', $item->id) }}"
                                                        class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs">
                                                        <i class="icon-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- MODAL DETAIL JADWAL (Identik dengan Modal Anggota) --}}
                                        <div class="modal fade" id="modalDetailJadwal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                                                    <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                                                        <h5 class="modal-title fw-bold">
                                                            <i class="icon-calendar me-2"></i>Detail Agenda Latihan
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="row">
                                                            <div class="col-md-4 text-center border-end">
                                                                <div class="mb-3">
                                                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                                                                        style="width: 120px; height: 120px; border: 4px solid #f8f9fa;">
                                                                        <i class="icon-clipboard text-primary" style="font-size: 60px;"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="fw-bold mb-1">{{ $item->judul_kegiatan }}</h5>
                                                                <p class="text-muted small mb-3">ID Jadwal: #JDL-{{ $item->id }}</p>
                                                                <span class="badge rounded-pill {{ $bg_color }} px-4 py-2 border shadow-xs">
                                                                    {{ $item->jenis }}
                                                                </span>
                                                            </div>

                                                            <div class="col-md-8 ps-md-4 text-start">
                                                                <div class="row g-3">
                                                                    <div class="col-12">
                                                                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Informasi Pelaksanaan</h6>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Pelatih Bertugas</small>
                                                                        <span class="fw-bold text-dark">{{ $item->pelatih->nama_lengkap ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Kelompok Latihan (Kolat)</small>
                                                                        <span class="fw-bold text-dark">{{ $item->kolat->nama_kolat ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Hari & Tanggal</small>
                                                                        <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">Waktu</small>
                                                                        <span class="fw-bold text-dark">{{ substr($item->jam_mulai, 0, 5) }} - {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : 'Selesai' }} WIB</span>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <small class="text-muted d-block">Lokasi Tempat</small>
                                                                        <span class="fw-bold text-dark"><i class="icon-map-pin me-1 text-danger"></i>{{ $item->lokasi }}</span>
                                                                    </div>

                                                                    <div class="col-12 mt-4">
                                                                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Deskripsi & Catatan</h6>
                                                                        <div class="p-3 rounded-3" style="background-color: #f8faff; border: 1px solid #edf2f9;">
                                                                            <p class="mb-0 text-dark small" style="white-space: pre-line;">{{ $item->deskripsi ?? 'Tidak ada deskripsi tambahan untuk agenda ini.' }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-4 pb-4">
                                                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                                                        <a href="{{ route('jadwal.edit', $item->id) }}" class="btn btn-warning text-white px-4" style="border-radius: 10px;">Edit Jadwal</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted small">Belum ada jadwal latihan atau pengumuman.</td>
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

        /* Soft Badges */
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .bg-info-soft { background-color: #e0f2fe; color: #0ea5e9; }
        .bg-danger-soft { background-color: #fff5f5; color: #e53e3e; }
        .bg-secondary-soft { background-color: #f3f4f6; color: #6b7280; }

        /* Buttons Action */
        .btn-action-detail { background-color: #f0f7ff; color: #0d6efd; border: 1px solid #d0e4ff; border-radius: 10px; transition: 0.3s; }
        .btn-action-edit { background-color: #fff8e6; color: #f59e0b; border: 1px solid #ffecb3; border-radius: 10px; transition: 0.3s; }

        .btn-action-edit:hover { background-color: #f59e0b !important; color: white !important; transform: translateY(-2px); }
        .btn-action-detail:hover { background-color: #0d6efd !important; color: white !important; transform: translateY(-2px); }

        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
