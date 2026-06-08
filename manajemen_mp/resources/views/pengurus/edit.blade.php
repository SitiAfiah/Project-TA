@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('pengurus.index') }}" class="text-muted text-decoration-none small">Daftar Pengurus</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Jabatan</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Ubah Jabatan Struktural</h3>
        <p class="text-muted small">Perbarui data fungsionaris struktural pengurus atas nama <strong>{{ $pengurus->nama_lengkap }}</strong>.</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('pengurus.update', $pengurus->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control bg-light" value="{{ $pengurus->nama_lengkap }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Induk Anggota</label>
                            <input type="text" class="form-control bg-light" value="{{ $pengurus->no_induk }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Posisi Struktural Baru</label>
                            <input type="text" name="jabatan_struktural" class="form-control" value="{{ old('jabatan_struktural', $jabatan_sekarang) }}" required placeholder="Contoh: Ketua Cabang, Bendahara I">
                            <small class="text-muted">*Perubahan posisi ini tidak akan mengganggu data riwayat kepelatihan yang bersangkutan.</small>
                        </div>

                        <div class="d-flex justify-content-start gap-3">
                            <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">Simpan Perubahan</button>
                            <a href="{{ route('pengurus.index') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control { border-radius: 10px; padding: 10px 15px; }
    .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1); }
    .btn-cancel { border-radius: 12px; transition: all 0.3s ease; border: 2px solid #6c757d; color: #6c757d; }
    .btn-cancel:hover { background-color: #6c757d; color: white; transform: translateY(-2px); }
    .btn-save { border-radius: 12px; background: linear-gradient(45deg, #4f46e5, #3730a3); border: none; transition: all 0.3s ease; color: white;}
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4) !important; color: white;}
</style>
@endsection
