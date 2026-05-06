@extends('layout.app')

@section('content')
@php
    // Ambil nama role user yang login saat ini
    $userRole = auth()->user()->role->nama_role ?? 'Anggota';
@endphp

<div class="container-fluid py-4">
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

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="d-flex">
            <i class="icon-alert-circle me-2 mt-1"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <form action="{{ isset($anggota) ? route('anggota.update', $anggota->id) : route('anggota.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($anggota)) @method('PUT') @endif

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Informasi Pribadi & Akun</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                       value="{{ old('nama_lengkap', $anggota->nama_lengkap ?? '') }}"
                                       required placeholder="Nama lengkap sesuai identitas">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alamat Email (Untuk Login)</label>
                                <input type="email" name="email" class="form-control {{ isset($anggota) ? 'bg-light' : '' }}"
                                       value="{{ old('email', $anggota->user->email ?? '') }}"
                                       {{ isset($anggota) ? 'readonly' : '' }} required placeholder="email@contoh.com">
                                @if(!isset($anggota))
                                <small class="text-muted">Password default: <strong>tapakmp123</strong></small>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. WhatsApp</label>
                                <div class="input-group">
                                    <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp', $anggota->no_hp ?? '') }}" placeholder="081234567xxx" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tempat & Tanggal Lahir</label>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $anggota->tempat_lahir ?? '') }}" placeholder="Kota" required>
                                    </div>
                                    <div class="col-5">
                                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $anggota->tgl_lahir ?? '') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="1" placeholder="Alamat lengkap domisili..." required>{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <label class="form-label fw-bold">Jabatan / Role</label>
                                <select name="role_id" id="roleSelect" class="form-select border-primary" onchange="toggleSKFields()" required>
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    @foreach($data_role as $role)
                                        {{-- LOGIKA PENGAMANAN ROLE --}}
                                        {{-- Jika yang login BUKAN pengurus, dan pilihan role saat ini BUKAN anggota, maka sembunyikan pilihannya --}}
                                        @if($userRole != 'Pengurus' && strtolower($role->nama_role) != 'anggota')
                                            @continue
                                        @endif

                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $anggota->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                            {{ $role->nama_role }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Pesan informasi tambahan untuk Pelatih --}}
                                @if($userRole != 'Pengurus')
                                    <small class="text-info mt-1 d-block"><i class="icon-info me-1"></i>Anda hanya dapat mendaftarkan Anggota biasa.</small>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Status Keanggotaan</label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif" {{ old('status', $anggota->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ old('status', $anggota->status ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>

                            {{-- BOX SK HANYA DI-RENDER UTUH UNTUK PENGURUS AGAR LEBIH AMAN --}}
                            @if($userRole == 'Pengurus')
                            <div id="boxSK" class="col-12 mt-3 p-4 bg-light rounded-4 border border-dashed border-danger" style="display: none;">
                                <h6 class="text-danger fw-bold mb-3"><i class="icon-file-text me-2"></i>Data Legalitas (Khusus Pelatih/Pengurus)</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Nomor SK</label>
                                        <input type="text" name="no_sk" class="form-control" value="{{ old('no_sk', $anggota->no_sk ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Masa Berlaku SK</label>
                                        <input type="date" name="masa_berlaku" class="form-control" value="{{ old('masa_berlaku', $anggota->masa_berlaku ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Upload SK (Gambar)</label>
                                        <input type="file" name="foto_sk" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6 mt-4">
                                <label class="form-label fw-bold">Asal Kolat</label>
                                <select name="kolat_id" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Kolat --</option>
                                    @foreach($data_kolat as $k)
                                        <option value="{{ $k->id }}" {{ old('kolat_id', $anggota->kolat_id ?? '') == $k->id ? 'selected' : '' }}>
                                            Kolat {{ $k->nama_kolat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <label class="form-label fw-bold">Tingkatan Sabuk</label>
                                <input type="text" name="tingkatan" class="form-control" value="{{ old('tingkatan', $anggota->tingkatan ?? '') }}" placeholder="Contoh: Balik I" required>
                            </div>
                            <div class="col-md-3 mt-4">
                                <label class="form-label fw-bold">Tanggal Gabung</label>
                                <input type="date" name="tgl_gabung" class="form-control" value="{{ old('tgl_gabung', $anggota->tgl_gabung ?? '') }}" required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold text-danger"><i class="icon-heart me-2"></i>Catatan Medis (Opsional)</label>
                                <textarea name="catatan_medis" class="form-control" rows="2" placeholder="Contoh: Riwayat asma atau cedera fisik.">{{ old('catatan_medis', $anggota->catatan_medis ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

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
        const roleSelect = document.getElementById('roleSelect');
        const boxSK = document.getElementById('boxSK');

        
        if (boxSK && roleSelect.selectedIndex > 0) {
            const selectedText = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();

            // Tampilkan box SK jika role mengandung kata Pelatih atau Pengurus
            if (selectedText.includes('pelatih') || selectedText.includes('pengurus')) {
                boxSK.style.display = 'block';
            } else {
                boxSK.style.display = 'none';
            }
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
        border: 1px solid #dce1e7;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .btn-save {
        border-radius: 12px;
        background: linear-gradient(45deg, #0d6efd, #004dc0);
        border: none;
        color: white;
    }
    .btn-cancel {
        border-radius: 12px;
    }
    .bg-light { background-color: #f8f9fa !important; }
</style>
@endsection
