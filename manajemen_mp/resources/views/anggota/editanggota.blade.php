@extends('layout.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header & Breadcrumb -->
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
                <!-- Form Start - Pastikan enctype ada untuk upload foto SK -->
                <form action="{{ route('anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- CARD 1: INFORMASI PRIBADI -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="icon-user me-2"></i>Informasi Pribadi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No Induk</label>
                                    <input type="text" name="no_induk" class="form-control bg-light fw-bold text-primary"
                                        value="{{ $anggota->no_induk }}" readonly>
                                    <small class="text-muted" style="font-size: 11px;">* No. induk permanen dan tidak dapat
                                        diubah</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control bg-light"
                                        value="{{ $anggota->nama_lengkap }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No. WhatsApp (HP)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">+62</span>
                                        <input type="number" name="no_hp" class="form-control"
                                            value="{{ $anggota->no_hp }}" placeholder="81234567xxx" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Alamat Lengkap</label>
                                    <input type="text" name="alamat" class="form-control" value="{{ $anggota->alamat }}"
                                        required>
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
                                    <label class="form-label fw-bold">Jabatan Organisasi</label>
                                    <select name="jabatan" id="jabatanSelect" class="form-select border-primary"
                                        onchange="toggleSKFields()" required>
                                        <option value="anggota" {{ $anggota->jabatan == 'anggota' ? 'selected' : '' }}>
                                            Anggota</option>
                                        <option value="pelatih" {{ $anggota->jabatan == 'pelatih' ? 'selected' : '' }}>
                                            Pelatih</option>
                                        <option value="pengurus" {{ $anggota->jabatan == 'pengurus' ? 'selected' : '' }}>
                                            Pengurus</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tingkatan</label>
                                    <input type="text" name="tingkatan" class="form-control"
                                        value="{{ $anggota->tingkatan }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Status Anggota</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Aktif" {{ $anggota->status == 'Aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="Non-Aktif" {{ $anggota->status == 'Non-Aktif' ? 'selected' : '' }}>
                                            Non-Aktif</option>
                                    </select>
                                </div>

                                <!-- BOX DATA SK (Otomatis muncul jika pilih Pelatih/Pengurus) -->
                                <div id="boxSK"
                                    class="col-12 mt-3 p-4 bg-light rounded-4 border border-dashed border-danger"
                                    style="display: none;">
                                    <h6 class="text-danger fw-bold mb-3"><i class="icon-certificate me-2"></i>Data Legalitas
                                        SK (Wajib diisi untuk Pelatih/Pengurus)</h6>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Nomor SK</label>
                                            <input type="text" name="no_sk" class="form-control"
                                                value="{{ $anggota->no_sk ?? '' }}" placeholder="Contoh: SK/001/2026">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Tanggal SK</label>
                                            <input type="date" name="tgl_sk" class="form-control"
                                                value="{{ $anggota->tgl_sk ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Masa Berlaku</label>
                                            <input type="date" name="masa_berlaku" class="form-control"
                                                value="{{ $anggota->masa_berlaku ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Upload Scan SK</label>
                                            <input type="file" name="foto_sk" class="form-control">
                                            @if ($anggota->foto_sk)
                                                <small class="text-muted mt-1 d-block">File:
                                                    {{ $anggota->foto_sk }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-12 mt-3">
                                <label class="form-label fw-bold">Asal Kolat</label>
                                <input type="text" name="asal_kolat" class="form-control" value="{{ $anggota->asal_kolat }}" required>
                            </div> --}}
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold">Asal Kolat</label>
                                    <select name="kolat_id" class="form-select" required>
                                        <option value="" disabled>-- Pilih Kolat --</option>
                                        @foreach ($data_kolat as $kolat)
                                            <option value="{{ $kolat->id }}"
                                                {{ $anggota->kolat_id == $kolat->id ? 'selected' : '' }}>
                                                {{ $kolat->nama_kolat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER CARD UNTUK BUTTON -->
                        <div class="card-footer bg-light border-0 p-4" style="border-radius: 0 0 20px 20px;">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('anggota.anggota') }}"
                                    class="btn btn-outline-secondary px-4 fw-bold btn-cancel">
                                    <i class="icon-x me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold btn-save shadow">
                                    <i class="icon-check me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT TOGGLE -->
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

        // Jalankan saat pertama kali halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            toggleSKFields();
        });
    </script>

    <style>
        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
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
            background: (45deg, #0d6efd);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4) !important;
        }
    </style>
@endsection
