@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4">
        <h3 class="fw-bold text-dark">Angkat Pengurus Struktural</h3>
        <p class="text-muted">Proses ini memberikan wewenang administratif tingkat cabang kepada Pelatih terpilih.</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <form action="{{ route('pengurus.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Pilih Pelatih (Calon Pengurus)</label>
                            <select name="anggota_id" class="form-control select2 shadow-none" style="border-radius: 12px;" required>
                                <option value="">-- Cari Nama Pelatih Berdasarkan No Induk --</option>
                                @foreach($calon_pengurus as $item)
                                    <option value="{{ $item->id }}">{{ $item->no_induk }} - {{ $item->nama_lengkap }} ({{ $item->tingkatan }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Sesuai aturan hierarki, hanya fungsionaris bertatus Pelatih yang dapat diangkat ke kepengurusan struktural.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Jabatan Struktural Cabang</label>
                            <input type="text" name="jabatan_struktural" class="form-control shadow-none" placeholder="Contoh: Ketua Cabang, Sekretaris, Bendahara" style="border-radius: 10px;" required>
                            <small class="text-muted">Tuliskan nama posisi spesifik yang akan diemban di kepengurusan.</small>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm" style="border-radius: 12px;">Simpan & Tetapkan</button>
                            <a href="{{ route('pengurus.index') }}" class="btn btn-light px-4" style="border-radius: 12px;">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white p-2 shadow-sm" style="border-radius: 20px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Sistem Multi-Role</h5>
                    <ul class="small opacity-9 text-white ps-3">
                        <li class="mb-2">Akses kepelatihan lapangan yang bersangkutan tidak akan hilang atau terhapus.</li>
                        <li class="mb-2">Sistem secara otomatis akan menggabungkan peran lama dengan posisi struktural baru di riwayat jabatan.</li>
                        <li>Pengurus baru akan langsung diberikan hak penuh untuk mengelola data anggota keseluruhan serta laporan keuangan tingkat cabang.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    });
</script>
@endsection
