@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-end mb-4 text-start">
        <div>
            <h3 class="fw-bold text-dark mb-1">Kehadiran Latihan</h3>
            <p class="text-muted small mb-0">Jadwal hari ini & riwayat 7 hari terakhir.</p>
        </div>
        {{-- Tombol menuju route riwayat yang akan kita buat nanti --}}
        <a href="{{ route('presensi.anggota.riwayat') }}" class="btn btn-outline-primary shadow-sm fw-bold" style="border-radius: 12px;">
            <i class="bi bi-clock-history me-1"></i> Riwayat Lengkap
        </a>
    </div>

    {{-- Notifikasi --}}
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

    {{-- PISAHKAN JADWAL HARI INI DAN SEBELUMNYA --}}
    @php
        $jadwalHariIni = $jadwal_terbaru->where('tanggal', $hari_ini);
        $jadwalSebelumnya = $jadwal_terbaru->where('tanggal', '!=', $hari_ini);
    @endphp

    {{-- SEGMEN 1: JADWAL HARI INI --}}
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-event-fill text-primary me-2"></i>Jadwal Hari Ini</h5>
    <div class="row mb-5">
        @forelse($jadwalHariIni as $jadwal)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: 0.3s; border-top: 5px solid #0d6efd !important;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill shadow-xs">
                                {{ $jadwal->jenis }}
                            </span>
                            <span class="small fw-bold text-dark text-end">Hari Ini</span>
                        </div>

                        <h5 class="fw-bold text-dark mb-1">{{ $jadwal->judul_kegiatan }}</h5>
                        <p class="small text-muted mb-3"><i class="bi bi-person-fill me-1"></i> Pelatih: {{ $jadwal->pelatih->nama_lengkap ?? '-' }}</p>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <div class="small fw-bold text-dark mb-1">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : 'Selesai' }} WIB
                            </div>
                            <div class="small text-muted text-truncate">
                                <i class="bi bi-geo-alt-fill me-2 text-danger"></i>{{ $jadwal->lokasi }}
                            </div>
                        </div>

                        <div class="mt-auto">
                            @if(isset($presensi_saya[$jadwal->id]))
                                {{-- Sudah Absen Hari Ini --}}
                                @if($presensi_saya[$jadwal->id]->is_verified)
                                    <div class="text-center p-2 rounded-3 bg-success-soft text-success border border-success fw-bold">
                                        <i class="bi bi-patch-check-fill me-1"></i> Kehadiran Disahkan
                                    </div>
                                @else
                                    <div class="text-center p-2 rounded-3 bg-warning-soft text-warning border border-warning fw-bold">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Konfirmasi
                                    </div>
                                @endif
                            @else
                                {{-- Belum Absen Hari Ini --}}
                                @php
                                    $end = $jadwal->jam_selesai
                                        ? \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_selesai, 'Asia/Jakarta')
                                        : \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_mulai, 'Asia/Jakarta')->addHours(3);
                                @endphp

                                @if($now->gt($end))
                                    <button class="btn btn-secondary w-100 fw-bold py-2 disabled" style="border-radius: 12px; opacity: 0.6;">
                                        <i class="bi bi-lock-fill me-1"></i> Sesi Absen Berakhir
                                    </button>
                                @elseif($jadwal->status == 'selesai')
                                    <button class="btn btn-secondary w-100 fw-bold py-2 disabled" style="border-radius: 12px; opacity: 0.6;">
                                        <i class="bi bi-x-circle me-1"></i> Latihan Ditutup Pelatih
                                    </button>
                                @else
                                    <a href="{{ route('presensi.anggota.scan', $jadwal->id) }}" class="btn btn-primary w-100 fw-bold py-2 shadow-sm btn-scan" style="border-radius: 12px;">
                                        <i class="bi bi-qr-code-scan me-2"></i> Scan Barcode Sekarang
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; background-color: #f8f9fa;">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted mb-3 d-block" style="font-size: 3rem; opacity: 0.5;"></i>
                        <h6 class="fw-bold text-secondary mb-0">Tidak ada jadwal latihan untuk Anda hari ini.</h6>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- SEGMEN 2: RIWAYAT 7 HARI TERAKHIR --}}
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-secondary me-2"></i>Riwayat Minggu Ini</h5>
    <div class="row">
        @forelse($jadwalSebelumnya as $jadwal)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background-color: #fafbfc;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $jadwal->judul_kegiatan }}</h6>
                            <span class="small text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMM YYYY') }}</span>
                        </div>

                        <div class="mt-auto">
                            @if(isset($presensi_saya[$jadwal->id]))
                                @php
                                    $presensi = $presensi_saya[$jadwal->id];
                                    $statusColor = match($presensi->status) {
                                        'Hadir' => 'success',
                                        'Izin' => 'info',
                                        'Sakit' => 'warning',
                                        'Alfa' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                @if($presensi->is_verified)
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-{{ $statusColor }}-soft text-{{ $statusColor }} fw-bold" style="font-size: 0.85rem;">
                                        <span><i class="bi bi-check-circle-fill me-1"></i> Disahkan</span>
                                        <span>{{ $presensi->status }}</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-warning-soft text-warning fw-bold" style="font-size: 0.85rem;">
                                        <span><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                        <span>Hadir</span>
                                    </div>
                                @endif
                            @else
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-danger-soft text-danger fw-bold" style="font-size: 0.85rem;">
                                    <span><i class="bi bi-x-circle-fill me-1"></i> Tidak Absen</span>
                                    <span>Alfa</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted small">Belum ada riwayat latihan dalam 7 hari terakhir.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .bg-primary-soft { background-color: #e0f2fe; color: #0ea5e9; }
    .bg-success-soft { background-color: #d1e7dd; color: #0f5132; }
    .bg-warning-soft { background-color: #fff3cd; color: #664d03; }
    .bg-info-soft { background-color: #cff4fc; color: #055160; }
    .bg-danger-soft { background-color: #f8d7da; color: #842029; }
    .btn-scan { background: linear-gradient(45deg, #0d6efd, #004fb1); border: none; transition: 0.3s; }
    .btn-scan:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(13, 110, 253, 0.3) !important; }
    .card:hover { transform: translateY(-3px); }
</style>
@endsection
