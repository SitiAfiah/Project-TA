@extends('layout.app')

@section('content')
<div class="container-fluid py-4 text-start">
    <div class="page-header mb-4">
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Laporan</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Laporan Presensi</li>
            </ol>
        </nav> --}}
        <h3 class="fw-bold text-dark">Laporan Kehadiran Latihan</h3>
        <p class="text-muted small">Rekapitulasi absensi anggota untuk bahan evaluasi rapat tahunan.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form action="{{ route('laporan.presensi') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Kolat</label>
                    <select name="kolat_id" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                        <option value="">Semua Kolat</option>
                        @foreach($data_kolat as $k)
                            <option value="{{ $k->id }}" {{ request('kolat_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kolat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="form-control border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 10px 15px;">
                        <option value="">Semua</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Alfa" {{ request('status') == 'Alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 12px; padding: 10px;">
                        <i class="icon-magnifier me-2"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="text-start">
                    <h6 class="fw-bold mb-0 text-dark">Data Kehadiran</h6>
                    <p class="text-muted small mb-0">Ditemukan {{ $data_presensi->count() }} catatan presensi.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('laporan.presensi.excel', request()->all()) }}" class="btn btn-success px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-excel me-2"></i> Excel
                    </a>
                    <a href="{{ route('laporan.presensi.pdf', request()->all()) }}" class="btn btn-danger px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-file-earmark-pdf me-2"></i> PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle main-table">
                    <thead>
                        <tr class="text-center">
                            <th width="50">No</th>
                            <th>Anggota</th>
                            <th>Jadwal & Kolat</th>
                            <th>Status</th>
                            <th>Verifikator</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_presensi as $no => $p)
                        <tr class="text-center">
                            <td class=" text-muted small">{{ $no + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->anggota->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">Waktu: {{ $p->waktu_presensi ? \Carbon\Carbon::parse($p->waktu_presensi)->format('H:i') : '-' }} WIB</small>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($p->jadwal->tanggal)->format('d M Y') }}</div>
                                <div class="small text-muted">Kolat: {{ $p->jadwal->kolat->nama_kolat ?? '-' }}</div>
                            </td>
                            <td>
                                @php
                                    $color = ['Hadir'=>'success', 'Izin'=>'info', 'Sakit'=>'warning', 'Alfa'=>'danger'][$p->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{$color}}-soft text-{{$color}} border-{{$color}} border px-3 py-2 rounded-pill" style="font-size: 11px;">
                                    {{ strtoupper($p->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="small fw-bold text-dark">{{ $p->pelatihVerifikator->nama_lengkap ?? 'Sistem' }}</div>
                                <small class="text-muted" style="font-size: 10px;">{{ $p->is_verified ? 'Terverifikasi' : 'Pending' }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data presensi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .main-table thead th { background-color: #f8faff; padding: 18px 10px; font-size: 0.75rem; color: #8c98a5; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid #edf2f9; }
    .main-table tbody td { padding: 15px 10px; border-bottom: 1px solid #f1f4f8; color: #495057; font-size: 0.85rem; }
    .bg-success-soft { background-color: #f0fdf4; color: #16a34a; }
    .bg-info-soft { background-color: #eff6ff; color: #3b82f6; }
    .bg-warning-soft { background-color: #fffbeb; color: #ca8a04; }
    .bg-danger-soft { background-color: #fef2f2; color: #ef4444; }
</style>
@endsection
