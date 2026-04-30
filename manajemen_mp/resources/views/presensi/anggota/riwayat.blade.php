@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER & BREADCRUMB --}}
    <div class="page-header mb-4 text-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('presensi.anggota.index') }}" class="text-muted text-decoration-none small">Kehadiran</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Riwayat Lengkap</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-dark mb-1">Riwayat Kehadiran</h3>
                <p class="text-muted small mb-0">Rekap seluruh partisipasi latihan Anda.</p>
            </div>
            <a href="{{ route('presensi.anggota.index') }}" class="btn btn-light border shadow-sm fw-bold" style="border-radius: 12px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- FILTER BULAN & TAHUN --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 text-start">
                    <form action="{{ route('presensi.anggota.riwayat') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Pilih Bulan</label>
                            <select name="bulan" class="form-select border-2 shadow-none" style="border-radius: 10px;">
                                @php
                                    $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                                @endphp
                                @foreach($namaBulan as $angka => $nama)
                                    <option value="{{ $angka }}" {{ $bulan == $angka ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Pilih Tahun</label>
                            <select name="tahun" class="form-select border-2 shadow-none" style="border-radius: 10px;">
                                @for($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" style="border-radius: 10px;">
                                <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL RIWAYAT --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 text-start">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0">Data Bulan {{ $namaBulan[$bulan] }} {{ $tahun }}</h5>
                        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill">Total: {{ $jadwal_riwayat->count() }} Pertemuan</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle main-table">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="20%">Tanggal Latihan</th>
                                    <th width="30%">Materi / Kegiatan</th>
                                    <th width="15%" class="text-center">Status</th>
                                    <th width="15%" class="text-center">Verifikasi</th>
                                    <th width="15%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwal_riwayat as $no => $jadwal)
                                    @php
                                        // Pengecekan apakah jadwal sudah lewat waktunya
                                        $end = $jadwal->jam_selesai
                                            ? \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_selesai, 'Asia/Jakarta')
                                            : \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam_mulai, 'Asia/Jakarta')->addHours(3);
                                        $sudah_lewat = \Carbon\Carbon::now('Asia/Jakarta')->gt($end);
                                    @endphp

                                    <tr>
                                        <td class="text-muted small text-center">{{ $no + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMM YYYY') }}</div>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ substr($jadwal->jam_mulai, 0, 5) }} WIB</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">{{ $jadwal->judul_kegiatan }}</div>
                                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.65rem;">{{ $jadwal->jenis }}</span>
                                        </td>
                                        <td class="text-center">
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
                                                <span class="badge bg-{{ $statusColor }}-soft text-{{ $statusColor }} px-3 py-2 rounded-pill fw-bold">
                                                    {{ $presensi->status }}
                                                </span>
                                            @else
                                                @if($sudah_lewat || $jadwal->status == 'selesai')
                                                    <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill fw-bold">Alfa</span>
                                                @else
                                                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill border">Belum Mulai</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($presensi_saya[$jadwal->id]))
                                                @if($presensi_saya[$jadwal->id]->is_verified)
                                                    <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Sah</span>
                                                @else
                                                    <span class="text-warning small fw-bold"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($presensi_saya[$jadwal->id]) && $presensi_saya[$jadwal->id]->keterangan)
                                                <span class="small text-muted" style="font-size: 0.75rem;">
                                                    <i class="bi bi-chat-left-text me-1"></i>{{ $presensi_saya[$jadwal->id]->keterangan }}
                                                </span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-folder-x text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                            </div>
                                            <h6 class="fw-bold text-secondary mb-1">Tidak ada data di bulan ini</h6>
                                            <p class="text-muted small">Silakan pilih bulan atau tahun lain pada filter di atas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .main-table thead th { background-color: #f8faff; padding: 16px 12px; font-size: 0.75rem; color: #6c757d; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
    .main-table tbody td { padding: 16px 12px; border-bottom: 1px solid #f1f4f8; vertical-align: middle; }
    .bg-primary-soft { background-color: #e0f2fe; color: #0ea5e9; }
    .bg-success-soft { background-color: #d1e7dd; color: #0f5132; }
    .bg-warning-soft { background-color: #fff3cd; color: #664d03; }
    .bg-info-soft { background-color: #cff4fc; color: #055160; }
    .bg-danger-soft { background-color: #f8d7da; color: #842029; }
    .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
</style>
@endsection
