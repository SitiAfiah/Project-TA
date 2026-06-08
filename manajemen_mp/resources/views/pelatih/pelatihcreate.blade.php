@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4">
        <h3 class="fw-bold text-dark">Upgrade Anggota ke Pelatih</h3>
        <p class="text-muted">Proses ini akan mengubah status keanggotaan dan memberikan akses manajemen latihan.</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('pelatih.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilih Anggota -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Pilih Anggota</label>
                            <select name="anggota_id" class="form-control select2 shadow-none" style="border-radius: 12px;" required>
                                <option value="">-- Cari Nama atau No Induk --</option>
                                @foreach($calon_pelatih as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->no_induk }} - {{ $anggota->nama_lengkap }} ({{ $anggota->tingkatan }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hanya anggota yang belum memiliki jabatan pelatih yang muncul di sini.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Tugaskan ke Kolat (Tempat Melatih)</label>
                            <select name="kolat_ids[]" class="form-control select2 shadow-none" multiple="multiple" style="border-radius: 12px;" required>
                                @foreach($data_kolat as $kolat)
                                    <option value="{{ $kolat->id }}">{{ $kolat->nama_kolat }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Bisa memilih lebih dari satu kolat sekaligus.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Nomor SK Pelatih</label>
                                <input type="text" name="no_sk" class="form-control shadow-none" placeholder="Contoh: SK-MP/2024/001" style="border-radius: 10px;" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Tanggal Terbit SK</label>
                                <input type="date" name="tgl_sk" class="form-control shadow-none" style="border-radius: 10px;" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Masa Berlaku Hingga</label>
                                <input type="date" name="masa_berlaku" class="form-control shadow-none" style="border-radius: 10px;" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-dark">Upload Scan SK (Gambar)</label>
                                <input type="file" name="foto_sk" class="form-control shadow-none" style="border-radius: 10px;">
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-3">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm" style="border-radius: 12px;">Simpan & Upgrade</button>
                            <a href="{{ route('pelatih.index') }}" class="btn btn-light px-4" style="border-radius: 12px;">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white p-2 shadow-sm" style="border-radius: 20px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="icon-info me-2"></i>Informasi Pelatih</h5>
                    <ul class="small opacity-8 text-white ps-3">
                        <li class="mb-2">Anggota akan mendapatkan akses untuk mengelola jadwal latihan.</li>
                        <li class="mb-2">ID Pelatih akan otomatis merujuk pada Nomor Induk Anggota.</li>
                        <li>Pastikan SK yang diupload jelas dan dapat terbaca oleh admin cabang.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script Select2 di layout utama atau di sini -->
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "Klik untuk memilih Kolat...",
            allowClear: true,
            width: '100%' // Memastikan dropdown memenuhi lebar kolom
        });
    });
</script>
@endsection
