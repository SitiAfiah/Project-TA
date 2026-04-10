@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header & Breadcrumb -->
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('kolat.index') }}" class="text-muted text-decoration-none small">Daftar Kolat</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Kolat</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Update Data Kolat</h3>
        <p class="text-muted small">Perbarui informasi nama atau lokasi Kelompok Latihan (Kolat).</p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Action mengarah ke fungsi Update dengan membawa ID -->
            <form action="{{ route('kolat.update', $kolat->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- PENTING: Untuk proses update di Laravel --}}

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-primary mb-0"><i class="icon-pencil me-2"></i>Edit Detail Kolat</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- ID Kolat (Readonly) -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small">ID Kolat</label>
                                <input type="text" class="form-control bg-light fw-bold text-primary"
                                       value="{{ $kolat->id }}" readonly>
                                <small class="text-muted" style="font-size: 11px;">ID tidak dapat diubah (Permanen).</small>
                            </div>

                            <!-- Nama Kolat -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold text-dark">Nama Kelompok Latihan (Kolat)</label>
                                <input type="text" name="nama_kolat"
                                       class="form-control @error('nama_kolat') is-invalid @enderror"
                                       value="{{ old('nama_kolat', $kolat->nama_kolat) }}"
                                       required placeholder="Masukkan nama kolat baru">
                                @error('nama_kolat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat Kolat -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Alamat Lengkap Lokasi Latihan</label>
                                <textarea name="alamat_kolat" class="form-control @error('alamat_kolat') is-invalid @enderror"
                                          rows="4" required
                                          placeholder="Masukkan alamat lengkap lokasi...">{{ old('alamat_kolat', $kolat->alamat_kolat) }}</textarea>
                                @error('alamat_kolat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer dengan tombol Aksi -->
                    <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('kolat.index') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">
                                <i class="icon-x me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">
                                <i class="icon-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling agar seragam dengan form lainnya */
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #dce1e7;
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
        background: linear-gradient(45deg, #0d6efd, #004dc0);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        background: linear-gradient(45deg, #004dc0, #0d6efd);
        color: white;
    }
    .shadow {
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3) !important;
    }
</style>
@endsection
