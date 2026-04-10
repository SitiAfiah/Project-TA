@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header & Breadcrumb -->
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('anggota.anggota') }}" class="text-muted text-decoration-none small">Daftar Anggota</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">
                    {{ isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota' }}
                </li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">{{ isset($anggota) ? 'Update Data Anggota' : 'Pendaftaran Anggota Baru' }}</h3>
        <p class="text-muted small">Lengkapi data identitas dan legalitas SK untuk Pelatih atau Pengurus.</p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ isset($anggota) ? route('anggota.update', $anggota->id) : route('anggota.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($anggota)) @method('PUT') @endif

                <!-- CARD 1: INFORMASI PRIBADI -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Informasi Pribadi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control {{ isset($anggota) ? 'bg-light' : '' }}"
                                       value="{{ $anggota->nama_lengkap ?? '' }}"
                                       {{ isset($anggota) ? 'readonly' : '' }} required placeholder="Nama lengkap sesuai identitas">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">+62</span>
                                    <input type="number" name="no_hp" class="form-control" value="{{ $anggota->no_hp ?? '' }}" placeholder="81234567xxx" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tempat & Tanggal Lahir</label>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ $anggota->tempat_lahir ?? '' }}" placeholder="Kota" required>
                                    </div>
                                    <div class="col-5">
                                        <input type="date" name="tgl_lahir" class="form-control" value="{{ $anggota->tgl_lahir ?? '' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="L" {{ (isset($anggota) && $anggota->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ (isset($anggota) && $anggota->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap domisili..." required>{{ $anggota->alamat ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: INFORMASI ORGANISASI -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-success mb-0"><i class="icon-badge me-2"></i>Informasi Organisasi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small">No Induk</label>
                                <input type="text" class="form-control bg-light fw-bold text-primary" value="{{ $anggota->no_induk ?? 'JB-XXX (Otomatis)' }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jabatan</label>
                                <select name="role_id" id="roleSelect" class="form-select border-primary" onchange="toggleSKFields()" required>
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    @foreach($data_role as $role)
                                        <option value="{{ $role->id }}"
                                            {{ (isset($anggota) && $anggota->role_id == $role->id) ? 'selected' : '' }}>
                                            {{ $role->nama_role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aktif" {{ (isset($anggota) && $anggota->status == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ (isset($anggota) && $anggota->status == 'Non-Aktif') ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>

                            <!-- BOX SK (Legalitas) -->
                            <div id="boxSK" class="col-12 mt-3 p-4 bg-light rounded-4 border border-dashed border-danger" style="display: none;">
                                <h6 class="text-danger fw-bold mb-3"><i class="bi bi-file-earmark-check me-2"></i>Data Legalitas (SK/Lisensi)</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Nomor SK</label>
                                        <input type="text" name="no_sk" class="form-control" value="{{ $anggota->no_sk ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Masa Berlaku</label>
                                        <input type="date" name="masa_berlaku" class="form-control" value="{{ $anggota->masa_berlaku ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Upload SK (Gambar)</label>
                                        <input type="file" name="foto_sk" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-4">
                                <label class="form-label fw-bold">Asal Kolat</label>
                               <select name="kolat_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kolat --</option>
                            @foreach($data_kolat as $k)
                                <option value="{{ $k->id }}" {{ (isset($anggota) && $anggota->kolat_id == $k->id) ? 'selected' : '' }}>
                                    Kolat {{ $k->nama_kolat }}
                                </option>
                            @endforeach
                        </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <label class="form-label fw-bold">Tingkatan</label>
                                <input type="text" name="tingkatan" class="form-control" value="{{ $anggota->tingkatan ?? '' }}" placeholder="Contoh: Balik I" required>
                            </div>
                            <div class="col-md-3 mt-4">
                                <label class="form-label fw-bold">Tanggal Gabung</label>
                                <input type="date" name="tgl_gabung" class="form-control" value="{{ $anggota->tgl_gabung ?? '' }}" required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold text-danger"><i class="icon-heart me-2"></i>Catatan Medis</label>
                                <textarea name="catatan_medis" class="form-control" rows="3" placeholder="Contoh: Riwayat asma atau cedera fisik.">{{ $anggota->catatan_medis ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER CARD UNTUK BUTTON -->
                    <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                        <div class="d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('anggota.anggota') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">
                                <i class="icon-x me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">
                                <i class="icon-check me-1"></i> {{ isset($anggota) ? 'Update Data' : 'Simpan Data' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSKFields() {
        const jabatan = document.getElementById('jabatanSelect').value;
        const boxSK = document.getElementById('boxSK');
        if (jabatan === 'pelatih' || jabatan === 'pengurus') {
            boxSK.style.display = 'block';
        } else {
            boxSK.style.display = 'none';
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        toggleSKFields();
    });
</script>

<style>
    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    /* Perbaikan Button Styling */
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
        background: (45deg, #0d6efd, #004dc0);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        /* box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4) !important; */
        background:(45deg, #004dc0, #0d6efd);
    }
</style>
@endsection
