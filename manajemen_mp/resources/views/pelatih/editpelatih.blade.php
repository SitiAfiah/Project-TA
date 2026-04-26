@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.index') }}"
                            class="text-muted text-decoration-none small">Daftar Pelatih</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Data Pelatih</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Form Edit Data Pelatih</h3>
            <p class="text-muted small">Perbarui informasi legalitas SK atau status kepelatihan untuk <strong>{{ $pelatih->nama_lengkap }}</strong>.</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('pelatih.update', $pelatih->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Identitas Pelatih</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No Induk</label>
                                    <input type="text" class="form-control bg-light fw-bold"
                                        value="{{ $pelatih->no_induk }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $pelatih->nama_lengkap }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Asal Kolat</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $pelatih->kolat->nama_kolat ?? '-' }}" readonly>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">* Data identitas di atas bersifat tetap. Ubah di menu Anggota jika terdapat kesalahan input nama/induk.</small>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-danger mb-0"><i class="icon-certificate me-2"></i>Legalitas & Masa Aktif SK</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nomor SK Kepelatihan</label>
                                    <input type="text" name="no_sk" class="form-control border-danger"
                                        value="{{ old('no_sk', $pelatih->no_sk) }}" placeholder="Contoh: SK/001/2026" required>
                                    @error('no_sk') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Tanggal Terbit SK</label>
                                    <input type="date" name="tgl_sk" class="form-control"
                                        value="{{ old('tgl_sk', $pelatih->tgl_sk) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Masa Berlaku</label>
                                    <input type="date" name="masa_berlaku" class="form-control"
                                        value="{{ old('masa_berlaku', $pelatih->masa_berlaku) }}" required>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="p-3 bg-light rounded-4 border">
                                        <label class="form-label fw-bold d-block">Update Scan SK (Format: JPG, PNG, PDF | Max: 2MB)</label>
                                        <input type="file" name="foto_sk" class="form-control mb-2">

                                        @if ($pelatih->foto_sk)
                                            <div class="d-flex align-items-center mt-2">
                                                <i class="icon-file-text text-primary me-2"></i>
                                                <small class="text-muted">File saat ini:
                                                    <a href="{{ asset('storage/' . $pelatih->foto_sk) }}" target="_blank" class="text-decoration-none">
                                                        Lihat Dokumen SK <i class="icon-external-link"></i>
                                                    </a>
                                                </small>
                                            </div>
                                        @else
                                            <small class="text-warning">Belum ada file SK yang diunggah.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('pelatih.index') }}"
                                    class="btn btn-outline-secondary px-4 fw-bold btn-cancel">
                                    <i class="icon-x me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">
                                    <i class="icon-check me-1"></i> Simpan Perubahan SK
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        .btn-cancel {
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 2px solid #6c757d;
        }

        .btn-cancel:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .btn-save {
            border-radius: 12px;
            background: linear-gradient(45deg, #0d6efd, #004fb1);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4) !important;
        }
    </style>
@endsection
