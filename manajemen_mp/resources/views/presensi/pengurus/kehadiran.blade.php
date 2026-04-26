@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('presensi.index') }}" class="text-muted text-decoration-none small">Presensi Latihan</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Verifikasi Kehadiran</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Verifikasi Kehadiran Anggota</h3>
        </div>

        {{-- Menampilkan Pesan Sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Menampilkan Pesan Error (Misal: Input manual ganda) --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <strong>Gagal!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- KARTU INFORMASI JADWAL --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-1">{{ $jadwal->judul_kegiatan }}</h5>
                                <p class="mb-0 small" style="opacity: 0.9;">
                                    <i class="icon-map-pin me-1"></i> {{ $jadwal->lokasi }} |
                                    <i class="icon-calendar me-1"></i> {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} |
                                    <i class="icon-clock me-1"></i> {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : 'Selesai' }}
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm">
                                    Kolat: {{ $jadwal->kolat->nama_kolat ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL PRESENSI --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Absensi Masuk</h5>
                                <p class="text-muted small mb-0">Klik konfirmasi untuk mensahkan kehadiran anggota.</p>
                            </div>

                            {{-- Tombol Buka Modal Tambah Manual --}}
                            <button class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#modalTambahManual">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Manual
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table">
                                <thead>
                                    <tr>
                                        <th width="60" class="text-center">No</th>
                                        <th>Identitas Anggota</th>
                                        <th class="text-center">Waktu Absen</th>
                                        <th class="text-center">Status Verifikasi</th>
                                        <th class="text-center" width="180">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_presensi as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark text-start ps-2">{{ $item->anggota->nama_lengkap }}</div>
                                                <small class="text-muted text-start ps-2 d-block">ID: {{ $item->anggota->no_induk }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border px-3" style="border-radius: 8px;">
                                                    <i class="icon-clock me-1 text-primary"></i> {{ \Carbon\Carbon::parse($item->waktu_presensi)->format('H:i') }} WIB
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($item->is_verified)
                                                    <span class="badge bg-success-soft text-success border-success px-3 border rounded-pill">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Sah
                                                    </span>
                                                    <div class="small text-muted mt-1" style="font-size: 10px;">
                                                        Oleh: {{ $item->pelatihVerifikator->nama_lengkap ?? 'Pelatih' }}
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning text-dark px-3 rounded-pill shadow-xs">
                                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$item->is_verified)
                                                    {{-- Tombol Konfirmasi Hadir --}}
                                                    <form action="{{ route('presensi.konfirmasi', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-action-edit fw-bold px-3 shadow-xs" onclick="return confirm('Konfirmasi kehadiran anggota ini?')">
                                                            <i class="bi bi-check2-square me-1"></i> Konfirmasi
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-light fw-bold px-3 text-muted border disabled" style="border-radius: 10px;">
                                                        Selesai
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted small">Belum ada anggota yang melakukan absensi masuk.</td>
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

    {{-- MODAL TAMBAH MANUAL (KASUS LUPA HP) --}}
    <div class="modal fade" id="modalTambahManual" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i>Input Presensi Manual
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('presensi.storeManual') }}" method="POST">
                    @csrf
                    {{-- Hidden input untuk ID Jadwal --}}
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 shadow-sm small" style="border-radius: 10px;">
                            <i class="bi bi-info-circle-fill me-2"></i> Gunakan form ini untuk anggota yang lupa membawa perangkat atau tidak bisa absen mandiri.
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-dark small">Pilih Nama Anggota</label>
                            <select name="anggota_id" class="form-select shadow-none" style="border-radius: 10px; border-color: #d0e4ff;" required>
                                <option value="">-- Silakan Pilih Anggota --</option>
                                @foreach($daftar_anggota as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->no_induk }} - {{ $anggota->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light px-4 shadow-sm" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-check-lg me-1"></i> Simpan & Sahkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .main-table thead th { background-color: #f8faff; padding: 18px 10px; font-size: 0.75rem; color: #8c98a5; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
        .main-table tbody td { padding: 20px 10px; border-bottom: 1px solid #f1f4f8; color: #495057; font-size: 0.9rem; }
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .btn-action-edit { background-color: #f0f7ff; color: #0d6efd; border: 1px solid #d0e4ff; border-radius: 10px; transition: 0.3s; }
        .btn-action-edit:hover { background-color: #0d6efd !important; color: white !important; transform: translateY(-2px); }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>
@endsection
