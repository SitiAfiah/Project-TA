@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header & Breadcrumb -->
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('kolat.index') }}" class="text-muted text-decoration-none small">Daftar Kolat</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">
                    {{ isset($kolat) ? 'Edit Kolat' : 'Tambah Kolat' }}
                </li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">{{ isset($kolat) ? 'Update Data Kolat' : 'Registrasi Kolat Baru' }}</h3>
        <p class="text-muted small">Kelola informasi unit latihan atau Kelompok Latihan (Kolat) PPS Merpati Putih.</p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ isset($kolat) ? route('kolat.update', $kolat->id) : route('kolat.store') }}"
                  method="POST">
                @csrf
                @if(isset($kolat)) @method('PUT') @endif

                <!-- CARD UTAMA: INFORMASI KOLAT -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-primary mb-0"><i class="icon-map-pin me-2"></i>Detail Kelompok Latihan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Kolom ID (Hanya tampil/readonly saat Edit atau sebagai placeholder saat Create) -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small">ID Kolat</label>
                                <input type="text" class="form-control bg-light fw-bold text-primary"
                                       value="{{ $kolat->id ?? 'AUTO (Sistem)' }}" readonly>
                                <small class="text-muted" style="font-size: 11px;">Identitas unik kolat di dalam sistem.</small>
                            </div>

                            <!-- Nama Kolat -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Nama Kelompok Latihan (Kolat)</label>
                                <input type="text" name="nama_kolat" class="form-control"
                                       value="{{ $kolat->nama_kolat ?? old('nama_kolat') }}"
                                       required placeholder="Contoh: UNMUH Jember, POLIJE, dsb.">
                                @error('nama_kolat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Alamat Kolat -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Alamat Lengkap Lokasi Latihan</label>
                                <textarea name="alamat_kolat" class="form-control" rows="4"
                                          placeholder="Masukkan alamat lengkap lokasi latihan"
                                          required>{{ $kolat->alamat_kolat ?? old('alamat_kolat') }}</textarea>
                                @error('alamat_kolat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER CARD UNTUK BUTTON -->
                    <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('kolat.index') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">
                                <i class="icon-x me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">
                                <i class="icon-check me-1"></i> {{ isset($kolat) ? 'Update Kolat' : 'Simpan Kolat' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Mengikuti Style yang Anda berikan */
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
