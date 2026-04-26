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
