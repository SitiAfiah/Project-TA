// index jadwal.blade.php
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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
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
                                        <th width="50" class="text-center">No</th>
                                        <th>Kegiatan & Pelatih</th>
                                        <th class="text-center">Status</th>
                                        <th>Waktu & Lokasi</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center" width="220">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_jadwal as $no => $item)
                                        @php
                                            // Set timezone ke Jakarta agar sinkron dengan waktu Indonesia
                                            $now = \Carbon\Carbon::now('Asia/Jakarta');
                                            $start = \Carbon\Carbon::parse($item->tanggal . ' ' . $item->jam_mulai, 'Asia/Jakarta');
                                            $end = $item->jam_selesai
                                                ? \Carbon\Carbon::parse($item->tanggal . ' ' . $item->jam_selesai, 'Asia/Jakarta')
                                                : $start->copy()->addHours(2);

                                            $dbStatus = trim(strtolower($item->status));

                                            // Logika Penentuan Label Status
                                            if ($dbStatus == 'selesai') {
                                                $status_label = 'Selesai';
                                                $status_color = 'success';
                                            } elseif ($now->greaterThanOrEqualTo($start) && $now->lessThanOrEqualTo($end)) {
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
                                                    @if($dbStatus == 'selesai')
                                                        <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                    @endif
                                                </div>
                                                <small class="text-muted text-start ps-2 d-block">
                                                    <i class="icon-user small"></i> {{ $item->pelatih->nama_lengkap ?? 'Belum Ditentukan' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $status_color }} px-3 py-2" style="border-radius: 8px; font-size: 11px;">
                                                    {{ $status_label }}
                                                </span>
                                            </td>
                                            <td class="text-start ps-4">
                                                <div class="small fw-bold text-dark">
                                                    <i class="icon-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="icon-clock me-1"></i> {{ substr($item->jam_mulai, 0, 5) }} - {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : 'Selesai' }}
                                                </div>
                                                <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                    <i class="icon-map-pin me-1 text-danger"></i> {{ $item->lokasi }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $bg_type = 'bg-secondary-soft text-secondary';
                                                    if($item->jenis == 'Rutin') $bg_type = 'bg-success-soft text-success border-success';
                                                    elseif($item->jenis == 'Tambahan') $bg_type = 'bg-info-soft text-info border-info';
                                                    elseif($item->jenis == 'Pengumuman') $bg_type = 'bg-danger-soft text-danger border-danger';
                                                @endphp
                                                <span class="badge rounded-pill {{ $bg_type }} px-3 border" style="font-size: 10px;">
                                                    {{ $item->jenis }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">

                                                    {{-- TOMBOL KONFIRMASI: Muncul jika sudah masuk jamnya, bukan pengumuman, dan belum selesai --}}
                                                    @if($dbStatus != 'selesai' && $item->jenis != 'Pengumuman' && $now->greaterThanOrEqualTo($start))
                                                       <form action="{{ route('jadwal.konfirmasi', $item->id) }}" method="POST" id="form-selesai-{{ $item->id }}" class="d-inline">
    @csrf
    <button type="button" class="btn btn-sm btn-success fw-bold px-2 shadow-xs"
        style="border-radius: 10px;"
        onclick="konfirmasiLatihan('{{ $item->id }}', '{{ $item->judul_kegiatan }}')">
        <i class="bi bi-check2-square"></i>
    </button>
</form>
                                                    @endif

                                                    <button class="btn btn-sm btn-action-detail fw-bold px-2 shadow-xs"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDetailJadwal{{ $item->id }}">
                                                        <i class="icon-eye"></i>
                                                    </button>

                                                    <a href="{{ route('jadwal.edit', $item->id) }}"
                                                        class="btn btn-sm btn-action-edit fw-bold px-2 shadow-xs">
                                                        <i class="icon-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- MODAL DETAIL JADWAL --}}
                                        <div class="modal fade" id="modalDetailJadwal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
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
                                                                        style="width: 100px; height: 100px; border: 4px solid #f8f9fa;">
                                                                        <i class="icon-clipboard text-primary" style="font-size: 50px;"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="fw-bold mb-1 text-dark">{{ $item->judul_kegiatan }}</h5>
                                                                <p class="text-muted small mb-3">Status: <span class="text-{{ $status_color }} fw-bold">{{ strtoupper($status_label) }}</span></p>
                                                            </div>

                                                            <div class="col-md-8 ps-md-4 text-start">
                                                                <div class="row g-3">
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block small">Pelatih</small>
                                                                        <span class="fw-bold text-dark">{{ $item->pelatih->nama_lengkap ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block small">Kolat</small>
                                                                        <span class="fw-bold text-dark">{{ $item->kolat->nama_kolat ?? '-' }}</span>
                                                                    </div>
                                                                    <div class="col-12 mt-3">
                                                                        <h6 class="fw-bold text-primary border-bottom pb-1 small">Deskripsi Kegiatan</h6>
                                                                        <p class="small text-muted mb-0" style="white-space: pre-line;">{{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-4 pb-4">
                                                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted small">Belum ada jadwal latihan atau pengumuman.</td>
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
        .main-table thead th { background-color: #f8faff; padding: 15px 10px; font-size: 0.7rem; color: #8c98a5; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
        .main-table tbody td { padding: 15px 10px; border-bottom: 1px solid #f1f4f8; color: #495057; font-size: 0.85rem; }
        .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
        .bg-info-soft { background-color: #e0f2fe; color: #0ea5e9; }
        .bg-danger-soft { background-color: #fff5f5; color: #e53e3e; }
        .bg-secondary-soft { background-color: #f3f4f6; color: #6b7280; }
        .btn-action-detail { background-color: #f0f7ff; color: #0d6efd; border: 1px solid #d0e4ff; border-radius: 10px; transition: 0.3s; }
        .btn-action-edit { background-color: #fff8e6; color: #f59e0b; border: 1px solid #ffecb3; border-radius: 10px; transition: 0.3s; }
        .btn-action-edit:hover { background-color: #f59e0b !important; color: white !important; transform: translateY(-2px); }
        .btn-action-detail:hover { background-color: #0d6efd !important; color: white !important; transform: translateY(-2px); }
        .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    </style>

    <script>
    function konfirmasiLatihan(id, judul) {
        Swal.fire({
            title: 'Konfirmasi Selesai',
            text: "Apakah benar latihan '" + judul + "' telah dilaksanakan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Warna hijau sukses
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Sudah Selesai!',
            cancelButtonText: 'Batal',
            border: 'none',
            borderRadius: '20px'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika user klik OK
                document.getElementById('form-selesai-' + id).submit();
            }
        })
    }
</script>
@endsection


{{-- edit --}}
@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}"
                            class="text-muted text-decoration-none small">Daftar Jadwal</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Jadwal</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Edit Agenda Latihan</h3>
            <p class="text-muted small">Perbarui detail waktu, lokasi, atau pelatih yang bertugas untuk agenda ini.</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="icon-calendar me-2"></i>Informasi Agenda</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Judul Kegiatan / Materi Latihan</label>
                                    <input type="text" name="judul_kegiatan" class="form-control"
                                        value="{{ old('judul_kegiatan', $jadwal->judul_kegiatan) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kelompok Latihan (Kolat)</label>
                                    <select name="kolat_id" class="form-select" required>
                                        @foreach($data_kolat as $kolat)
                                            <option value="{{ $kolat->id }}" {{ $jadwal->kolat_id == $kolat->id ? 'selected' : '' }}>
                                                {{ $kolat->nama_kolat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pelatih Bertugas</label>
                                    <select name="pelatih_id" class="form-select" required>
                                        @foreach($data_pelatih as $pelatih)
                                            <option value="{{ $pelatih->id }}" {{ $jadwal->pelatih_id == $pelatih->id ? 'selected' : '' }}>
                                                {{ $pelatih->nama_lengkap }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ old('tanggal', $jadwal->tanggal) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" class="form-control"
                                        value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jam Selesai (Opsional)</label>
                                    <input type="time" name="jam_selesai" class="form-control"
                                        value="{{ old('jam_selesai', $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Lokasi / Tempat Latihan</label>
                                    <input type="text" name="lokasi" class="form-control"
                                        value="{{ old('lokasi', $jadwal->lokasi) }}" placeholder="Contoh: GOR Perjuangan" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipe Agenda</label>
                                    <select name="jenis" class="form-select" required>
                                        <option value="Rutin" {{ $jadwal->jenis == 'Rutin' ? 'selected' : '' }}>Rutin</option>
                                        <option value="Tambahan" {{ $jadwal->jenis == 'Tambahan' ? 'selected' : '' }}>Tambahan</option>
                                        <option value="Pengumuman" {{ $jadwal->jenis == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Deskripsi / Catatan Tambahan</label>
                                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $jadwal->deskripsi) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small text-muted">
                                    Status saat ini: <span class="badge bg-{{ $jadwal->status == 'selesai' ? 'success' : 'secondary' }}">{{ strtoupper($jadwal->status) }}</span>
                                </div>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-2">
                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none small">
                            <i class="icon-trash"></i> Hapus Agenda Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; }
        .btn-cancel { border-radius: 12px; transition: 0.3s; border: 2px solid #6c757d; }
        .btn-save { border-radius: 12px; background: linear-gradient(45deg, #0d6efd, #004fb1); border: none; transition: 0.3s; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4) !important; }
    </style>
@endsection

{{-- create --}}
@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}" class="text-muted text-decoration-none small">Jadwal Latihan</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Buat Jadwal Baru</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Tambah Jadwal Latihan</h3>
    </div>

    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('jadwal.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-dark">Judul Kegiatan / Agenda</label>
                                <input type="text" name="judul_kegiatan" class="form-control @error('judul_kegiatan') is-invalid @enderror"
                                    placeholder="Contoh: Latihan Rutin Sabuk Putih" value="{{ old('judul_kegiatan') }}" required>
                                @error('judul_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Kelompok Latihan (Kolat)</label>
                                <select name="kolat_id" class="form-select @error('kolat_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Kolat</option>
                                    @foreach($data_kolat as $kolat)
                                        <option value="{{ $kolat->id }}" {{ old('kolat_id') == $kolat->id ? 'selected' : '' }}>
                                            {{ $kolat->nama_kolat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kolat_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Pelatih Bertugas</label>
                                <select name="pelatih_id" class="form-select @error('pelatih_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Pelatih</option>
                                    @foreach($data_pelatih as $pelatih)
                                        <option value="{{ $pelatih->id }}" {{ old('pelatih_id') == $pelatih->id ? 'selected' : '' }}>
                                            {{ $pelatih->nama_lengkap }} ({{ $pelatih->no_induk }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelatih_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Jenis Kegiatan</label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="Rutin" {{ old('jenis') == 'Rutin' ? 'selected' : '' }}>Rutin</option>
                                    <option value="Tambahan" {{ old('jenis') == 'Tambahan' ? 'selected' : '' }}>Tambahan</option>
                                    <option value="Pengumuman" {{ old('jenis') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                                    value="{{ old('jam_mulai') }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Jam Selesai (Opsional)</label>
                                <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror"
                                    value="{{ old('jam_selesai') }}">
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-dark">Lokasi Latihan</label>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                    placeholder="Contoh: Gedung Serbaguna Lt. 2" value="{{ old('lokasi') }}" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold text-dark">Deskripsi / Catatan (Opsional)</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3" placeholder="Masukkan detail tambahan jika ada...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('jadwal.index') }}" class="btn btn-light px-4 py-2 fw-bold" style="border-radius: 12px;">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
                                <i class="icon-save me-1"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12 mt-4 mt-lg-0">
            <div class="card border-0 bg-primary text-white" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="icon-info me-2"></i> Tips Pengisian</h5>
                    <ul class="small opacity-75 ps-3">
                        <li class="mb-2">Pastikan memilih <strong>Pelatih</strong> yang sedang aktif.</li>
                        <li class="mb-2">Pilih jenis <strong>Pengumuman</strong> jika hanya ingin memberikan informasi tanpa perlu presensi.</li>
                        <li class="mb-2">Gunakan lokasi yang spesifik agar anggota tidak kesulitan mencari tempat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border: 1px solid #edf2f9;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        background-color: #f8faff;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.05);
        background-color: #fff;
    }
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
</style>
@endsection


