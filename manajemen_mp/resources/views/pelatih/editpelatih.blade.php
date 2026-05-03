@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.index') }}"
                            class="text-muted text-decoration-none small">Daftar Pelatih</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Pelatih</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Form Edit Data Pelatih</h3>
            <p class="text-muted small">Perbarui profil pribadi dan legalitas SK pelatih <strong>{{ $pelatih->nama_lengkap }}</strong>.</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('pelatih.update', $pelatih->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Informasi Akun & Pribadi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Alamat Email (Login)</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $pelatih->user->email ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        value="{{ old('nama_lengkap', $pelatih->nama_lengkap) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No Induk</label>
                                    <input type="text" class="form-control bg-light fw-bold text-primary"
                                        value="{{ $pelatih->no_induk }}" readonly>
                                    <small class="text-muted" style="font-size: 11px;">* No. induk permanen</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No. WhatsApp</label>
                                    <input type="number" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $pelatih->no_hp) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="L" {{ $pelatih->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $pelatih->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $pelatih->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-danger mb-0"><i class="icon-badge me-2"></i>Informasi Kepelatihan & SK</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tingkatan Sabuk</label>
                                    <input type="text" name="tingkatan" class="form-control"
                                        value="{{ old('tingkatan', $pelatih->tingkatan) }}" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Asal Kolat (Tempat Melatih)</label>
                                    <select name="kolat_id" class="form-select" required>
                                        @foreach ($data_kolat as $kolat)
                                            <option value="{{ $kolat->id }}" {{ $pelatih->kolat_id == $kolat->id ? 'selected' : '' }}>
                                                {{ $kolat->nama_kolat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mt-3 p-4 bg-light rounded-4 border border-dashed border-danger">
                                    <h6 class="text-danger fw-bold mb-3"><i class="icon-certificate me-2"></i>Legalitas SK Pelatih</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Nomor SK</label>
                                            <input type="text" name="no_sk" class="form-control" value="{{ old('no_sk', $pelatih->no_sk) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Masa Berlaku</label>
                                            <input type="date" name="masa_berlaku" class="form-control" value="{{ $pelatih->masa_berlaku }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Update File SK (PDF/JPG)</label>
                                            <input type="file" name="foto_sk" class="form-control">
                                        </div>
                                        @if($pelatih->foto_sk)
                                        <div class="col-12">
                                            <small class="text-muted">File SK saat ini:
                                                <a href="{{ asset('storage/' . $pelatih->foto_sk) }}" target="_blank" class="text-primary fw-bold text-decoration-none">
                                                    Lihat Dokumen <i class="icon-external-link"></i>
                                                </a>
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('pelatih.index') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">Batal</a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">Simpan Perubahan Pelatih</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.1); }
        .rounded-4 { border-radius: 15px !important; }
        .btn-cancel { border-radius: 12px; transition: all 0.3s ease; border: 2px solid #6c757d; }
        .btn-cancel:hover { background-color: #6c757d; color: white; transform: translateY(-2px); }
        .btn-save { border-radius: 12px; background: linear-gradient(45deg, #4f46e5, #3730a3); border: none; transition: all 0.3s ease; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4) !important; }
    </style>
@endsection
