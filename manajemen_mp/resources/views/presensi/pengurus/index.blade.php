@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Presensi Latihan</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Rekap Presensi Latihan</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-radius: 15px;">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Pilih Jadwal Latihan</h5>
                                <p class="text-muted small mb-0">Klik tombol cek kehadiran untuk memverifikasi absen anggota
                                    dan mengelola data manual.</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table">
                                <thead>
                                    <tr>
                                        <th width="50" class="text-center">No</th>
                                        <th>Kegiatan & Pelatih</th>
                                        <th class="text-center">Status Jadwal</th>
                                        <th>Waktu & Lokasi</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center" width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_jadwal as $no => $item)
                                        @php
                                            // Set timezone ke Jakarta agar sinkron dengan waktu Indonesia
                                            $now = \Carbon\Carbon::now('Asia/Jakarta');
                                            $start = \Carbon\Carbon::parse(
                                                $item->tanggal . ' ' . $item->jam_mulai,
                                                'Asia/Jakarta',
                                            );
                                            $end = $item->jam_selesai
                                                ? \Carbon\Carbon::parse(
                                                    $item->tanggal . ' ' . $item->jam_selesai,
                                                    'Asia/Jakarta',
                                                )
                                                : $start->copy()->addHours(2);

                                            $dbStatus = trim(strtolower($item->status));

                                            // Logika Penentuan Label Status
                                            if ($dbStatus == 'selesai') {
                                                $status_label = 'Selesai';
                                                $status_color = 'success';
                                            } elseif (
                                                $now->greaterThanOrEqualTo($start) &&
                                                $now->lessThanOrEqualTo($end)
                                            ) {
                                                $status_label = 'Berlangsung';
                                                $status_color = 'primary';
                                            } elseif ($now->lt($start)) {
                                                $status_label = 'Mendatang';
                                                $status_color = 'secondary';
                                            } else {
                                                $status_label = 'Menunggu Konfirmasi';
                                                $status_color = 'warning';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-start ps-2">
                                                    {{ $item->judul_kegiatan }}
                                                    @if ($dbStatus == 'selesai')
                                                        <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                    @endif
                                                </div>
                                                <small class="text-muted text-start ps-2 d-block">
                                                    {{-- Pemanggilan dari Query Builder Manual --}}
                                                    <i class="bi bi-person-fill small"></i>
                                                    {{ $item->nama_pelatih ?? 'Belum Ditentukan' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $status_color }} px-3 py-2"
                                                    style="border-radius: 8px; font-size: 11px;">
                                                    {{ $status_label }}
                                                </span>
                                            </td>
                                            <td class="text-start ps-4">
                                                <div class="small fw-bold text-dark">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="bi bi-clock me-1"></i> {{ substr($item->jam_mulai, 0, 5) }} -
                                                    {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : 'Selesai' }}
                                                </div>
                                                <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $item->lokasi }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $bg_type = 'bg-secondary-soft text-secondary';
                                                    if ($item->jenis == 'Rutin') {
                                                        $bg_type = 'bg-success-soft text-success border-success';
                                                    } elseif ($item->jenis == 'Tambahan') {
                                                        $bg_type = 'bg-info-soft text-info border-info';
                                                    } elseif ($item->jenis == 'Pengumuman') {
                                                        $bg_type = 'bg-danger-soft text-danger border-danger';
                                                    }
                                                @endphp
                                                <span class="badge rounded-pill {{ $bg_type }} px-3 border"
                                                    style="font-size: 10px;">
                                                    {{ $item->jenis }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{-- Tombol Cek Kehadiran --}}
                                                <a href="{{ route('presensi.kehadiran', $item->id) }}"
                                                    class="btn btn-sm btn-info fw-bold px-3 shadow-xs text-white"
                                                    style="border-radius: 10px;">
                                                    <i class="bi bi-person-check-fill me-1"></i> Cek Kehadiran
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted small">Belum ada jadwal
                                                latihan tersedia.</td>
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
            padding: 15px 10px;
            font-size: 0.7rem;
            color: #8c98a5;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 2px solid #edf2f9;
        }

        .main-table tbody td {
            padding: 15px 10px;
            border-bottom: 1px solid #f1f4f8;
            color: #495057;
            font-size: 0.85rem;
        }

        .bg-success-soft {
            background-color: #e6fffa;
            color: #38b2ac;
        }

        .bg-info-soft {
            background-color: #e0f2fe;
            color: #0ea5e9;
        }

        .bg-danger-soft {
            background-color: #fff5f5;
            color: #e53e3e;
        }

        .bg-secondary-soft {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-info {
            background-color: #0ea5e9;
            border: none;
            transition: 0.3s;
        }

        .btn-info:hover {
            background-color: #0284c7;
            transform: translateY(-2px);
        }
    </style>
@endsection
