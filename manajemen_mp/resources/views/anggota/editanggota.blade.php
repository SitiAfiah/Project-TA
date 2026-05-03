@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 text-start">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('anggota.anggota') }}"
                            class="text-muted text-decoration-none small">Daftar Anggota</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Edit Anggota</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">Form Edit Anggota Merpati Putih</h3>
            <p class="text-muted small">Perbarui informasi identitas atau legalitas SK anggota di bawah ini.</p>
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
                                    <label class="form-label fw-bold">Alamat Email (Login)</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $anggota->user->email ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No Induk</label>
                                    <input type="text" class="form-control bg-light fw-bold text-primary"
                                        value="{{ $anggota->no_induk }}" readonly>
                                    <small class="text-muted" style="font-size: 11px;">* No. induk permanen</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">No. WhatsApp</label>
                                    <input type="number" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $anggota->no_hp) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="L" {{ $anggota->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $anggota->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $anggota->alamat) }}</textarea>
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
                                    <label class="form-label fw-bold">Jabatan / Role</label>
                                    <select name="role_id" id="roleSelect" class="form-select border-primary" onchange="toggleSKFields()" required>
                                        @foreach($data_role as $role)
                                            <option value="{{ $role->id }}" {{ $anggota->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->nama_role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tingkatan</label>
                                    <input type="text" name="tingkatan" class="form-control" value="{{ $anggota->tingkatan }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Status Anggota</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Aktif" {{ $anggota->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Non-Aktif" {{ $anggota->status == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>

                                <div id="boxSK" class="col-12 mt-3 p-4 bg-light rounded-4 border border-dashed border-danger" style="display: none;">
                                    <h6 class="text-danger fw-bold mb-3"><i class="icon-certificate me-2"></i>Legalitas SK</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Nomor SK</label>
                                            <input type="text" name="no_sk" class="form-control" value="{{ $anggota->no_sk }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Masa Berlaku</label>
                                            <input type="date" name="masa_berlaku" class="form-control" value="{{ $anggota->masa_berlaku }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Update Foto SK</label>
                                            <input type="file" name="foto_sk" class="form-control">
                                        </div>
                                    </div>
                                </div>

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
            const roleSelect = document.getElementById('roleSelect');
            const selectedText = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();
            const boxSK = document.getElementById('boxSK');

            if (selectedText.includes('pelatih') || selectedText.includes('pengurus')) {
                boxSK.style.display = 'block';
            } else {
                boxSK.style.display = 'none';
            }
        }
        document.addEventListener("DOMContentLoaded", toggleSKFields);
    </script>
@endsection
