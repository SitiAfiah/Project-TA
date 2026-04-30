@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- HEADER BREADCRUMB --}}
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

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="bi bi-check-circle-fill me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Gagal!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $now = \Carbon\Carbon::now('Asia/Jakarta');
            $start = \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_mulai, 'Asia/Jakarta');
            $end = $jadwal->jam_selesai
                ? \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_selesai, 'Asia/Jakarta')
                : $start->copy()->addHours(3);

            $is_active = $now->between($start, $end);
        @endphp

        {{-- KARTU INFORMASI JADWAL --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="row align-items-center">
                            <div class="col-md-7 text-start">
                                <span class="badge bg-white text-primary px-3 py-2 rounded-pill shadow-sm mb-3">
                                    <i class="bi bi-shield-fill-check me-1"></i> Kolat: {{ $jadwal->kolat->nama_kolat ?? '-' }}
                                </span>
                                <h4 class="fw-bold mb-2">{{ $jadwal->judul_kegiatan }}</h4>
                                <p class="mb-0 small" style="opacity: 0.9;">
                                    <i class="bi bi-geo-alt-fill me-1"></i> {{ $jadwal->lokasi }} &nbsp;|&nbsp;
                                    <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }} &nbsp;|&nbsp;
                                    <i class="bi bi-clock me-1"></i> {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : 'Selesai' }} WIB
                                </p>
                            </div>
                            <div class="col-md-5 text-md-end mt-4 mt-md-0">
                                @if ($is_active && $jadwal->status != 'selesai')
                                    <button class="btn btn-light text-primary fw-bold px-4 py-3 shadow" style="border-radius: 15px;" data-bs-toggle="modal" data-bs-target="#modalQRCode">
                                        <i class="bi bi-qr-code-scan me-2" style="font-size: 1.2rem;"></i> Tampilkan Barcode
                                    </button>
                                @else
                                    <button class="btn btn-light text-muted fw-bold px-4 py-3 shadow disabled" style="border-radius: 15px; opacity: 0.7;">
                                        <i class="bi bi-lock-fill me-2"></i> Waktu Absen Berakhir
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL DAFTAR ABSENSI MASUK --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-1 fw-bold text-dark">Daftar Absensi Masuk</h5>
                                <p class="text-muted small mb-0">Verifikasi kehadiran anggota yang sudah melakukan absensi.</p>
                            </div>
                            <button class="btn btn-primary px-4 py-2 shadow-sm fw-bold" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#modalTambahManual">
                                <i class="bi bi-person-plus-fill me-1"></i> Tambah Manual
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle main-table text-start">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="30%">Identitas Anggota</th>
                                        <th width="15%" class="text-center">Waktu Absen</th>
                                        <th width="15%" class="text-center">Status Kehadiran</th>
                                        <th width="15%" class="text-center">Verifikasi</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_presensi as $no => $item)
                                        <tr>
                                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $item->anggota->nama_lengkap }}</div>
                                                <small class="text-muted"><i class="bi bi-person-badge me-1"></i>{{ $item->anggota->no_induk }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border px-3 py-2" style="border-radius: 8px;">
                                                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($item->waktu_presensi)->format('H:i') }} WIB
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusColor = match($item->status) {
                                                        'Hadir' => 'success',
                                                        'Izin' => 'info',
                                                        'Sakit' => 'warning',
                                                        'Alfa' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusColor }}-soft text-{{ $statusColor }} px-3 py-2 rounded-pill fw-bold">
                                                    {{ $item->status }}
                                                </span>

                                                @if($item->keterangan)
                                                    <div class="small text-muted mt-2" style="font-size: 0.75rem;">
                                                        <i class="bi bi-chat-left-text text-{{ $statusColor }} me-1"></i> {{ $item->keterangan }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($item->is_verified)
                                                    <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Sah</span>
                                                    <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                                        Oleh: {{ $item->pelatihVerifikator->nama_lengkap ?? 'Sistem' }}
                                                    </div>
                                                @else
                                                    <span class="text-warning small fw-bold"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!$item->is_verified)
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <form action="{{ route('presensi.konfirmasi', $item->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="action" value="sah">
                                                            <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-sm" style="border-radius: 8px;">
                                                                <i class="bi bi-check-lg"></i> Sah
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-danger fw-bold px-3 shadow-sm" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $item->id }}">
                                                            <i class="bi bi-x-lg"></i> Tolak
                                                        </button>
                                                    </div>

                                                    {{-- Modal Khusus Penolakan --}}
                                                    <div class="modal fade text-start" id="modalTolak{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content" style="border-radius: 20px; border: none;">
                                                                <div class="modal-header bg-danger text-white border-0 py-3 px-4">
                                                                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-octagon me-2"></i>Tolak Absensi</h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('presensi.konfirmasi', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="tolak">
                                                                    <div class="modal-body p-4">
                                                                        <p class="text-muted small mb-3">Anda akan menolak kehadiran <strong>{{ $item->anggota->nama_lengkap }}</strong>. Status akan diubah menjadi Alfa.</p>
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold small text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Misal: Scan dari luar area latihan / Titip absen" required style="border-radius: 10px;"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light border-0 px-4 py-3">
                                                                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                                                                        <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm" style="border-radius: 10px;"><i class="bi bi-x-circle me-1"></i> Tolak Kehadiran</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-light text-muted border px-3 py-2" style="border-radius: 8px;">
                                                        <i class="bi bi-lock-fill me-1"></i> Selesai
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" class="mb-3 opacity-50" alt="No Data">
                                                <h6 class="text-muted fw-bold mb-1">Belum ada absensi masuk</h6>
                                                <p class="text-muted small">Anggota yang men-scan barcode akan muncul di sini.</p>
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

        {{-- TABEL DAFTAR ANGGOTA BELUM ABSEN --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-start">
                                <h5 class="card-title mb-1 fw-bold text-dark">
                                    Belum Melakukan Presensi
                                    @if(isset($anggota_belum_absen) && $anggota_belum_absen->count() > 0)
                                        <span class="badge bg-danger ms-2 rounded-pill">{{ $anggota_belum_absen->count() }} Orang</span>
                                    @endif
                                </h5>
                                <p class="text-muted small mb-0">Daftar anggota aktif di kolat ini yang belum tercatat kehadirannya.</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-start main-table">
                                <thead style="background-color: #fff5f5; border-bottom: 2px solid #ffe3e3;">
                                    <tr>
                                        <th width="5%" class="text-center" style="color: #dc3545;">No</th>
                                        <th width="45%" style="color: #dc3545;">Identitas Anggota</th>
                                        <th width="30%" style="color: #dc3545;">No. HP (WhatsApp)</th>
                                        <th width="20%" class="text-center" style="color: #dc3545;">Aksi Cepat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($anggota_belum_absen))
                                        @forelse ($anggota_belum_absen as $no => $belum)
                                            <tr>
                                                <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $belum->nama_lengkap }}</div>
                                                    <small class="text-muted"><i class="bi bi-person-badge me-1"></i>{{ $belum->no_induk }}</small>
                                                </td>
                                                <td>
                                                    @if($belum->no_hp)
                                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $belum->no_hp) }}" target="_blank" class="text-decoration-none text-success small fw-bold btn btn-sm btn-light border" style="border-radius: 8px;">
                                                            <i class="bi bi-whatsapp me-1"></i> Hubungi
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-primary fw-bold px-3 shadow-sm" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalTambahManual" onclick="document.getElementById('pilih_anggota').value = '{{ $belum->id }}'">
                                                        <i class="bi bi-pencil-square me-1"></i> Input
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="text-success fw-bold"><i class="bi bi-check-circle-fill me-2 fs-4"></i><br>Mantap! Semua anggota aktif sudah tercatat kehadirannya.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL QR CODE --}}
    <div class="modal fade" id="modalQRCode" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pb-0 justify-content-center position-relative">
                    <h5 class="modal-title fw-bold text-dark">Scan Barcode Kehadiran</h5>
                    <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-4">Barcode ini hanya berlaku selama jadwal latihan berlangsung.</p>
                    <div class="bg-white p-3 d-inline-block rounded-4 shadow-sm border">
                        {{-- MENGGUNAKAN ROUTE HELPER AGAR SINKRON DENGAN SCANNER --}}
                        {!! QrCode::size(250)->gradient(2, 132, 199, 0, 79, 177, 'diagonal')->margin(1)->generate(route('presensi.anggota.scan', $jadwal->id)) !!}
                    </div>
                    <div class="mt-4">
                        <h6 class="fw-bold mb-0">{{ $jadwal->judul_kegiatan }}</h6>
                        <span class="badge bg-danger mt-2 px-3 py-2 rounded-pill">Batas Waktu: {{ $jadwal->jam_selesai ?: 'Selesai' }} WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH MANUAL --}}
    <div class="modal fade" id="modalTambahManual" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Input Manual Kehadiran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('presensi.storeManual') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                    <div class="modal-body p-4 text-start">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Nama Anggota <span class="text-danger">*</span></label>
                            <select name="anggota_id" id="pilih_anggota" class="form-select border-2 shadow-none" style="border-radius: 10px;" required>
                                <option value="" disabled selected>-- Pilih Anggota (Kolat {{ $jadwal->kolat->nama_kolat }}) --</option>
                                @if(isset($anggota_belum_absen))
                                    @foreach ($anggota_belum_absen as $anggota)
                                        <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }} ({{ $anggota->no_induk }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Status Kehadiran <span class="text-danger">*</span></label>
                            <select name="status" class="form-select border-2 shadow-none" style="border-radius: 10px;" required>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alfa">Alfa</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-bold text-dark small">Keterangan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="keterangan" class="form-control border-2 shadow-none" rows="2" placeholder="Tuliskan alasan izin/sakit atau catatan lain..." style="border-radius: 10px;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer bg-light border-0 px-4 py-3">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" style="border-radius: 10px;"><i class="bi bi-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- KUMPULAN CSS --}}
    <style>
        .main-table thead th { background-color: #f8faff; padding: 16px 12px; font-size: 0.75rem; color: #6c757d; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
        .main-table tbody td { padding: 16px 12px; border-bottom: 1px solid #f1f4f8; vertical-align: middle; }
        .bg-success-soft { background-color: #d1e7dd; color: #0f5132; }
        .bg-info-soft { background-color: #cff4fc; color: #055160; }
        .bg-warning-soft { background-color: #fff3cd; color: #664d03; }
        .bg-danger-soft { background-color: #f8d7da; color: #842029; }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
        .form-select:focus, .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
    </style>
@endsection
