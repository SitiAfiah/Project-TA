@extends('layout.app')

@section('content')
@php
    // Ambil nama role user yang login saat ini
    $userRole = auth()->user()->role->nama_role ?? 'Anggota';
@endphp

<div class="container-fluid py-4">
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

    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('anggota.anggota') }}" class="text-muted text-decoration-none small">Daftar Anggota</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Anggota</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Form Edit Anggota Merpati Putih</h3>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Informasi Akun & Pribadi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Login</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $anggota->user->email) }}" required>
                                <small class="text-muted" style="font-size: 11px;">* Email digunakan untuk login sistem</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Role / Hak Akses</label>
                                <select name="role_id" class="form-select" required>
                                    @foreach($data_role as $role)
                                        {{-- LOGIKA PENGAMANAN ROLE --}}
                                        @if($userRole != 'Pengurus' && strtolower($role->nama_role) != 'anggota')
                                            @continue
                                        @endif

                                        <option value="{{ $role->id }}" {{ $anggota->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->nama_role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No Induk</label>
                                <input type="text" name="no_induk" class="form-control bg-light fw-bold text-primary" value="{{ $anggota->no_induk }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">No. WhatsApp</label>
                                <div class="input-group">
                                    <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alamat</label>
                                <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $anggota->alamat) }}" required>
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
                                <label class="form-label fw-bold">Jabatan Khusus</label>
                                <select name="jabatan" id="jabatanSelect" class="form-select border-primary" onchange="toggleSKFields()" required>
                                    <option value="anggota" {{ $anggota->jabatan == 'anggota' ? 'selected' : '' }}>Anggota</option>

                                    {{-- HANYA PENGURUS YANG BISA JADIKAN PELATIH/PENGURUS --}}
                                    @if($userRole == 'Pengurus')
                                        <option value="pelatih" {{ $anggota->jabatan == 'pelatih' ? 'selected' : '' }}>Pelatih</option>
                                        <option value="pengurus" {{ $anggota->jabatan == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                    @endif
                                </select>

                                @if($userRole != 'Pengurus')
                                    <small class="text-info mt-1 d-block"><i class="icon-info me-1"></i>Anda hanya dapat mengubah data Anggota biasa.</small>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tingkatan</label>
                                <input type="text" name="tingkatan" class="form-control" value="{{ old('tingkatan', $anggota->tingkatan) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aktif" {{ $anggota->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Non-Aktif" {{ $anggota->status == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>

                            {{-- BOX SK HANYA DIBUKA UNTUK PENGURUS --}}
                            @if($userRole == 'Pengurus')
                            <div id="boxSK" class="col-12 mt-3 p-4 bg-light rounded-4 border border-dashed border-danger" style="display: none;">
                                <h6 class="text-danger fw-bold mb-3">Data Legalitas SK</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Nomor SK</label>
                                        <input type="text" name="no_sk" class="form-control" value="{{ $anggota->no_sk }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Tanggal SK</label>
                                        <input type="date" name="tgl_sk" class="form-control" value="{{ $anggota->tgl_sk }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Masa Berlaku</label>
                                        <input type="date" name="masa_berlaku" class="form-control" value="{{ $anggota->masa_berlaku }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Upload Scan SK (Gambar)</label>
                                        <input type="file" name="foto_sk" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-12 mt-3">
                                <label class="form-label fw-bold">Asal Kolat</label>
                                <select name="kolat_id" class="form-select" required>
                                    @foreach ($data_kolat as $kolat)
                                        <option value="{{ $kolat->id }}" {{ $anggota->kolat_id == $kolat->id ? 'selected' : '' }}>
                                            {{ $kolat->nama_kolat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('anggota.anggota') }}" class="btn btn-outline-secondary px-4 fw-bold btn-cancel">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSKFields() {
        const jabatanSelect = document.getElementById('jabatanSelect');
        const boxSK = document.getElementById('boxSK');

        
        if (boxSK && jabatanSelect) {
            const jabatan = jabatanSelect.value;
            boxSK.style.display = (jabatan === 'pelatih' || jabatan === 'pengurus') ? 'block' : 'none';
        }
    }
    document.addEventListener("DOMContentLoaded", toggleSKFields);
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
