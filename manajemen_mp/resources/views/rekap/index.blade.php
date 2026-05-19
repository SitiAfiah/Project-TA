@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item active text-primary fw-bold small" aria-current="page">Rekap Kelayakan</li>
            </ol>
        </nav> --}}
        <h3 class="fw-bold text-dark">Rekap Kelayakan Anggota</h3>
        <p class="text-muted small">Penentuan kelayakan ujian berdasarkan presensi dan administrasi SPP.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <!-- Action Form disesuaikan ke rekap.index -->
            <form action="{{ route('rekap.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4 text-start">
                    <label class="form-label small fw-bold">Pilih Kolat</label>
                    <select name="kolat_id" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                        <option value="">Semua Kolat</option>
                        @foreach($data_kolat as $kolat)
                            <option value="{{ $kolat->id }}" {{ request('kolat_id') == $kolat->id ? 'selected' : '' }}>{{ $kolat->nama_kolat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 text-start">
                    <label class="form-label small fw-bold">Status Anggota</label>
                    <select name="status" class="form-select border-0 bg-light" style="border-radius: 12px; padding: 12px 15px;">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm w-100" style="border-radius: 12px; padding: 12px;">
                        <i class="fas fa-search me-2"></i> Tampilkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4 text-start">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="text-start">
                    <h6 class="fw-bold mb-0 text-dark">Hasil Analisis</h6>
                    <p class="text-muted small mb-0">Menampilkan {{ $dataRekap->count() }} data hasil kalkulasi.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle main-table">
                    <thead>
                        <tr class="text-center">
                            <th width="60">No</th>
                            <th>Identitas</th>
                            <th>Presensi</th>
                            <th>Status SPP</th>
                            <th>Kelayakan Ujian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataRekap as $no => $item)
                        <tr>
                            <td class="text-muted small text-center">{{ $no + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark ps-3 text-center">{{ $item['nama'] }}</div>
                                <small class="text-muted text-center ps-3 d-block">Tingkat: {{ $item['tingkatan'] }}</small>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold {{ $item['persentase_presensi'] >= 75 ? 'text-primary' : 'text-warning' }}">
                                    {{ $item['persentase_presensi'] }}%
                                </span>
                                <div style="font-size: 10px;" class="text-muted">{{ $item['status_aktif'] }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $item['status_spp'] == 'Lunas' ? 'bg-success-soft text-success' : 'bg-secondary-soft text-secondary' }} px-3 py-2">
                                    {{ $item['status_spp'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item['layak'])
                                    <span class="badge bg-info-soft text-info px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Layak
                                    </span>
                                @else
                                    <span class="badge bg-secondary-soft text-muted px-3 py-2 rounded-pill">
                                        Belum Layak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <span class="text-muted small">Data tidak ditemukan.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style tetap sama seperti yang Anda miliki */
    .main-table thead th {
        background-color: #f8faff;
        padding: 18px 10px;
        font-size: 0.75rem;
        color: #8c98a5;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-bottom: 2px solid #edf2f9;
    }
    .main-table tbody td {
        padding: 20px 10px;
        border-bottom: 1px solid #f1f4f8;
        color: #495057;
        font-size: 0.9rem;
    }
    .bg-success-soft { background-color: #e6fffa; color: #38b2ac; }
    .bg-info-soft { background-color: #e0f2fe; color: #0ea5e9; }
    .bg-secondary-soft { background-color: #f3f4f6; color: #6b7280; }
</style>
@endsection
