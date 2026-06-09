@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="{{ route('jadwal.index') }}" class="text-muted text-decoration-none small">Jadwal Latihan</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Buat Jadwal Baru</li>
            </ol>
        </nav>
        <h3 class="fw-bold text-dark">Tambah Jadwal Latihan</h3>
    </div>

    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('jadwal.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-dark">Judul Kegiatan / Agenda</label>
                                <input type="text" name="judul_kegiatan" class="form-control @error('judul_kegiatan') is-invalid @enderror"
                                    placeholder="Contoh: Latihan Rutin Sabuk Putih" value="{{ old('judul_kegiatan') }}" required>
                                @error('judul_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Kelompok Latihan (Kolat)</label>
                                <select name="kolat_id" class="form-select @error('kolat_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Kolat</option>
                                    @foreach($data_kolat as $kolat)
                                        <option value="{{ $kolat->id }}" {{ old('kolat_id') == $kolat->id ? 'selected' : '' }}>
                                            {{ $kolat->nama_kolat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kolat_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Pelatih Bertugas</label>
                                {{-- <select name="pelatih_id" class="form-select @error('pelatih_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Pelatih</option>
                                    @foreach($data_pelatih as $pelatih)
                                        <option value="{{ $pelatih->id }}" {{ old('pelatih_id') == $pelatih->id ? 'selected' : '' }}>
                                            {{ $pelatih->nama_lengkap }} ({{ $pelatih->no_induk }})
                                        </option>
                                    @endforeach
                                </select> --}}
                                <select name="pelatih_id" class="form-select @error('pelatih_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Pelatih</option>
                                    @foreach($data_pelatih as $pelatih)
                                        {{-- Menyisipkan kumpulan ID Kolat milik pelatih ke dalam atribut data-kolats --}}
                                        <option value="{{ $pelatih->id }}"
                                                data-kolats="{{ json_encode($pelatih->kolatLatihan->pluck('id')->toArray()) }}"
                                                {{ old('pelatih_id') == $pelatih->id ? 'selected' : '' }}>
                                            {{ $pelatih->nama_lengkap }} ({{ $pelatih->no_induk }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelatih_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Jenis Kegiatan</label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="Rutin" {{ old('jenis') == 'Rutin' ? 'selected' : '' }}>Rutin</option>
                                    <option value="Tambahan" {{ old('jenis') == 'Tambahan' ? 'selected' : '' }}>Tambahan</option>
                                    <option value="Pengumuman" {{ old('jenis') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror"
                                    value="{{ old('jam_mulai') }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Jam Selesai (Opsional)</label>
                                <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror"
                                    value="{{ old('jam_selesai') }}">
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-dark">Lokasi Latihan</label>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                    placeholder="Contoh: Gedung Serbaguna Lt. 2" value="{{ old('lokasi') }}" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold text-dark">Deskripsi / Catatan (Opsional)</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    rows="3" placeholder="Masukkan detail tambahan jika ada...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('jadwal.index') }}" class="btn btn-light px-4 py-2 fw-bold" style="border-radius: 12px;">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
                                <i class="icon-save me-1"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12 mt-4 mt-lg-0">
            <div class="card border-0 bg-primary text-white" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="icon-info me-2"></i> Tips Pengisian</h5>
                    <ul class="small opacity-75 ps-3">
                        <li class="mb-2">Pastikan memilih <strong>Pelatih</strong> yang sedang aktif.</li>
                        <li class="mb-2">Pilih jenis <strong>Pengumuman</strong> jika hanya ingin memberikan informasi tanpa perlu presensi.</li>
                        <li class="mb-2">Gunakan lokasi yang spesifik agar anggota tidak kesulitan mencari tempat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border: 1px solid #edf2f9;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        background-color: #f8faff;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.05);
        background-color: #fff;
    }
    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
</style>
{{-- SCRIPT FILTER PELATIH BERDASARKAN KOLAT --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        const pelatihSelect = $('select[name="pelatih_id"]');
        // Kloning seluruh elemen opsi pelatih bawaan sebagai master copy
        const masterPelatih = pelatihSelect.find('option').clone();

        $('select[name="kolat_id"]').change(function() {
            const kolatId = $(this).val();

            // Bersihkan dropdown pelatih
            pelatihSelect.empty();
            pelatihSelect.append('<option value="" selected disabled>Pilih Pelatih</option>');

            if (kolatId) {
                // Iterasi dan filter opsi pelatih dari master copy
                masterPelatih.each(function() {
                    const kolats = $(this).data('kolats'); // Membaca array dari data-kolats

                    // Jika pelatih bertugas di kolat yang dipilih, lampirkan kembali opsi ke dropdown
                    if (kolats && kolats.includes(parseInt(kolatId))) {
                        pelatihSelect.append($(this).clone());
                    }
                });
            } else {
                // Jika pilihan kolat kosong/direset, tampilkan semua pelatih kembali
                masterPelatih.each(function() {
                    if ($(this).val() !== "") {
                        pelatihSelect.append($(this).clone());
                    }
                });
            }
        });

        // Trigger otomatis saat halaman dimuat (untuk old input)
        const initialKolat = $('select[name="kolat_id"]').val();
        if (initialKolat) {
            const currentPelatih = "{{ old('pelatih_id') }}";
            $('select[name="kolat_id"]').trigger('change');
            if (currentPelatih) {
                pelatihSelect.val(currentPelatih);
            }
        }
    });
</script>
@endsection
