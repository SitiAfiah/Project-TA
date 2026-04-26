@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        <h3 class="fw-bold text-dark">Kehadiran Latihan</h3>
        <p class="text-muted small">Silakan lakukan absensi mandiri untuk jadwal hari ini.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($jadwal_hari_ini as $jadwal)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill shadow-xs">
                                {{ $jadwal->jenis }}
                            </span>
                            <span class="small fw-bold text-muted">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</span>
                        </div>

                        <h5 class="fw-bold text-dark mb-1">{{ $jadwal->judul_kegiatan }}</h5>
                        <p class="small text-muted mb-3"><i class="bi bi-person-fill me-1"></i> Pelatih: {{ $jadwal->pelatih->nama_lengkap ?? '-' }}</p>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <div class="small fw-bold text-dark mb-1"><i class="bi bi-clock me-2 text-primary"></i>{{ substr($jadwal->jam_mulai, 0, 5) }} - Selesai</div>
                            <div class="small text-muted"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>{{ $jadwal->lokasi }}</div>
                        </div>

                        {{-- LOGIKA TOMBOL ABSEN --}}
                        @if(isset($presensi_saya[$jadwal->id]))
                            {{-- Jika sudah absen --}}
                            @if($presensi_saya[$jadwal->id]->is_verified)
                                <button class="btn btn-success w-100 fw-bold disabled py-2" style="border-radius: 12px;">
                                    <i class="bi bi-check2-all me-1"></i> Kehadiran Disahkan
                                </button>
                            @else
                                <button class="btn btn-warning w-100 fw-bold disabled py-2" style="border-radius: 12px; color: #854d0e; background-color: #fef08a; border:none;">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Konfirmasi
                                </button>
                            @endif
                        @else
                            {{-- Jika belum absen, tampilkan tombol submit --}}
                            <form action="{{ route('anggota.presensi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm" style="border-radius: 12px;" onclick="return confirm('Apakah kamu yakin ingin absen untuk jadwal ini?')">
                                    <i class="bi bi-hand-index-thumb me-1"></i> Absen Sekarang
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="120" class="mb-3 opacity-50" alt="No Schedule">
                <h5 class="text-muted fw-bold">Tidak ada jadwal latihan hari ini.</h5>
                <p class="text-muted small">Selamat istirahat atau berlatih mandiri di rumah!</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .bg-primary-soft { background-color: #e0f2fe; color: #0ea5e9; }
</style>
@endsection
