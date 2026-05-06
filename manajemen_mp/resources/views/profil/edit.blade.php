@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Profil (Dipercantik dengan Avatar) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 20px; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body p-4 d-flex align-items-center position-relative overflow-hidden">
                    <!-- Elemen dekorasi background -->
                    <div class="position-absolute top-0 end-0 opacity-10 mt-n4 me-n4">
                        <i class="icon-shield" style="font-size: 150px; color: white;"></i>
                    </div>

                    <!-- Foto Default Avatar -->
                    <div class="bg-white rounded-circle d-flex justify-content-center align-items-center shadow p-1 me-4" style="width: 80px; height: 80px; z-index: 1;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=e9ecef&color=0d6efd&size=80" alt="Avatar" class="rounded-circle" style="width: 100%; height: 100%;">
                    </div>

                    <div style="z-index: 1;">
                        <h3 class="fw-bold text-white mb-1">{{ $anggota->nama_lengkap }}</h3>
                        <p class="text-white-50 mb-0"><i class="icon-map-pin me-1"></i> Kolat {{ $anggota->kolat->nama_kolat }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <i class="icon-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- KOLOM KIRI: Data Pribadi & Ganti Password -->
        <div class="col-md-8">

            <!-- FORM 1: DATA PRIBADI -->
            <form action="{{ route('profile.update') }}" method="POST" id="formProfil">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Data Pribadi</h5>
                        <!-- Tombol untuk mengaktifkan Edit Mode -->
                        <button type="button" id="btnEditMode" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                            <i class="icon-edit-2 me-1"></i> Edit Data
                        </button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control profil-input"
                                       value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alamat Email (User ID)</label>
                                <input type="email" class="form-control bg-light"
                                       value="{{ $anggota->user->email }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. WhatsApp</label>
                                <input type="number" name="no_hp" class="form-control profil-input"
                                       value="{{ old('no_hp', $anggota->no_hp) }}" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control profil-input"
                                       value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" disabled required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" class="form-control profil-input"
                                       value="{{ old('tgl_lahir', $anggota->tgl_lahir) }}" disabled required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control profil-input" rows="3" disabled required>{{ old('alamat', $anggota->alamat) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Area Tombol Simpan (Disembunyikan secara default) -->
                    <div class="card-footer bg-light border-0 p-4 d-none" id="footerSimpanProfil" style="border-radius: 0 0 20px 20px;">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="btnBatalEdit" class="btn btn-light px-4 fw-bold shadow-sm" style="border-radius: 10px;">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow" style="border-radius: 10px;">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- FORM 2: UBAH PASSWORD -->
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="icon-lock me-2 text-warning"></i>Keamanan & Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                        <button type="submit" class="btn btn-warning text-dark fw-bold shadow-sm w-100" style="border-radius: 10px;">
                            Perbarui Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- KOLOM KANAN: Status Organisasi -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-success mb-0"><i class="icon-badge me-2"></i>Status Organisasi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Nomor Induk Anggota</label>
                        <span class="fw-bold text-primary fs-5">{{ $anggota->no_induk }}</span>
                    </div>
                    <hr class="opacity-10">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jabatan</label>
                        <div class="p-2 bg-light rounded text-dark fw-semibold border border-light">
                            {{ $anggota->role->nama_role }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Asal Kolat</label>
                        <div class="p-2 bg-light rounded text-dark fw-semibold border border-light">
                            Kolat {{ $anggota->kolat->nama_kolat }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tingkatan Sabuk</label>
                        <div class="p-2 bg-light rounded text-dark fw-semibold border border-light">
                            {{ $anggota->tingkatan ?? 'Belum ada data' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bantuan Card -->
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background-color: #fff4f4;">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle bg-white d-inline-flex p-3 mb-3 shadow-sm">
                        <i class="icon-help-circle text-danger fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-danger">Butuh Bantuan?</h6>
                    <p class="small text-muted mb-0">Jika data organisasi (Kolat/Jabatan/Tingkat) tidak sesuai, silakan hubungi Pengurus Cabang melalui pelatih Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT UNTUK TOGGLE EDIT MODE -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnEditMode = document.getElementById('btnEditMode');
        const btnBatalEdit = document.getElementById('btnBatalEdit');
        const inputs = document.querySelectorAll('.profil-input');
        const footerSimpan = document.getElementById('footerSimpanProfil');

        // Fungsi untuk mengaktifkan mode edit
        btnEditMode.addEventListener('click', function() {
            inputs.forEach(input => {
                input.removeAttribute('disabled');
                input.classList.add('border-primary'); // Highlight input yang bisa diedit
            });
            btnEditMode.classList.add('d-none'); // Sembunyikan tombol Edit
            footerSimpan.classList.remove('d-none'); // Munculkan tombol Simpan
            inputs[0].focus(); // Fokus ke input pertama
        });

        // Fungsi untuk membatalkan mode edit
        btnBatalEdit.addEventListener('click', function() {
            inputs.forEach(input => {
                input.setAttribute('disabled', 'disabled');
                input.classList.remove('border-primary');
            });
            btnEditMode.classList.remove('d-none');
            footerSimpan.classList.add('d-none');

            // Opsi: reset form ke nilai awal jika dibatalkan
            document.getElementById('formProfil').reset();
        });
    });
</script>

<style>
    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #dce1e7;
        transition: all 0.3s;
    }
    .form-control:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
        opacity: 0.8;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .border-primary {
        border-color: #0d6efd !important;
        background-color: #fff !important;
    }
    .bg-light { background-color: #f8f9fa !important; }
</style>
@endsection
