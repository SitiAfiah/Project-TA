@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Halaman -->
    <div class="page-header mb-4 text-start">
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Penilaian Pelatih</li>
            </ol>
        </nav> --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="fw-bold text-dark mb-1">Daftar Pelatih</h3>
                <p class="text-muted small mb-0">Pilih pelatih di bawah ini untuk menambahkan evaluasi performa latihan.</p>
            </div>
        </div>
    </div>

    <!-- Grid Card Pelatih -->
    <div class="row g-4">
        @forelse($data_pelatih as $pelatih)
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 h-100 pelatih-card">
                <div class="card-body p-4 text-center d-flex flex-column align-items-center">

                    <!-- Foto Pelatih -->
                    <div class="foto-wrapper mb-3">
                        @if($pelatih->foto_profil)
                            <img src="{{ asset('storage/' . $pelatih->foto_profil) }}" alt="Foto {{ $pelatih->nama_lengkap }}" class="rounded-circle object-fit-cover shadow-sm">
                        @else
                            <div class="placeholder-foto rounded-circle d-flex align-items-center justify-content-center shadow-sm text-primary">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Identitas -->
                    <h6 class="fw-bold text-dark mb-1 w-100 text-truncate" title="{{ $pelatih->nama_lengkap }}">
                        {{ $pelatih->nama_lengkap }}
                    </h6>
                    <div class="text-muted small mb-4 w-100 text-truncate" title="{{ $pelatih->kolat->nama_kolat ?? 'Belum ada kolat' }}">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $pelatih->kolat->nama_kolat ?? 'Belum ada kolat' }}
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-auto w-100">
                        <a href="{{ route('penilaian.create', $pelatih->id) }}" class="btn btn-success btn-tambah w-100 shadow-sm fw-bold">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Penilaian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Jika Data Pelatih Kosong -->
        <div class="col-12">
            <div class="card border-0 shadow-sm text-center py-5 empty-state">
                <div class="card-body">
                    <div class="empty-icon-wrapper mb-3 mx-auto">
                        <i class="bi bi-inbox text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Tidak Ada Data Pelatih</h5>
                    <p class="text-muted small mb-0">Saat ini belum ada data pelatih yang dapat dinilai.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- CSS Tambahan Khusus Halaman Ini -->
<style>
    /* Styling Card Utama */
    .pelatih-card {
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    /* Efek Hover (Melayang) pada Card */
    .pelatih-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }

    /* Styling Area Foto */
    .foto-wrapper img, .placeholder-foto {
        width: 100px;
        height: 100px;
        border: 4px solid #ffffff; /* Bingkai putih agar menonjol */
        box-shadow: 0 4px 10px rgba(0,0,0,0.08); /* Bayangan halus di bawah foto */
    }

    /* Jika foto tidak ada */
    .placeholder-foto {
        background-color: #f0f4f8;
        font-size: 3.5rem;
    }

    /* Styling Tombol Tambah Penilaian */
    .btn-tambah {
        border-radius: 12px;
        padding: 10px 0;
        background-color: #198754; /* Hijau *success* */
        border: none;
        transition: all 0.3s ease;
    }

    /* Efek saat tombol disorot kursor */
    .btn-tambah:hover {
        background-color: #146c43;
        transform: scale(1.03); /* Membesar sedikit */
    }

    /* Styling saat data kosong (Empty State) */
    .empty-state {
        border-radius: 20px;
        background-color: #ffffff;
    }
    .empty-icon-wrapper {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('error'))
            Swal.fire({
                title: 'Tidak Bisa Menilai!',
                text: "{{ session('error') }}",
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endsection
