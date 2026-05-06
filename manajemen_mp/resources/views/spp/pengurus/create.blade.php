@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Halaman -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('spp.index') }}" class="btn btn-light shadow-sm me-3" style="border-radius: 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Tambah Tagihan SPP Manual</h3>
            </div>

            <!-- Alert Notifikasi -->
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">

                    <form action="{{ route('spp.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Pilih Anggota -->
                            <div class="col-12">
                                <label class="form-label small fw-bold">Pilih Anggota</label>
                                <select name="anggota_id" class="form-select border-0 bg-light py-3 @error('anggota_id') is-invalid @enderror" style="border-radius: 12px;" required>
                                    <option value="" selected disabled>Cari nama anggota...</option>
                                    @foreach($data_anggota as $agt)
                                        <option value="{{ $agt->id }}" {{ old('anggota_id') == $agt->id ? 'selected' : '' }}>
                                            {{ $agt->nama_lengkap }} (ID: {{ $agt->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('anggota_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Periode Bulan -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Untuk Bulan</label>
                                <select name="bulan" class="form-select border-0 bg-light py-3" style="border-radius: 12px;" required>
                                    @php
                                        $bulanArr = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        $currentMonth = date('F'); // Default bulan sekarang
                                    @endphp
                                    @foreach($bulanArr as $bln)
                                        <option value="{{ $bln }}" {{ (old('bulan') ?? $bln) == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Periode Tahun -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tahun</label>
                                <select name="tahun" class="form-select border-0 bg-light py-3" style="border-radius: 12px;" required>
                                    <option value="2025" {{ old('tahun') == '2025' ? 'selected' : '' }}>2025</option>
                                    <option value="2026" {{ (old('tahun') ?? '2026') == '2026' ? 'selected' : '' }}>2026</option>
                                </select>
                            </div>

                            <!-- Nominal -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nominal SPP (Rp)</label>
                                <input type="number" name="nominal" class="form-control border-0 bg-light py-3"
                                       placeholder="Contoh: 50000" value="{{ old('nominal', 50000) }}" style="border-radius: 12px;" required>
                            </div>

                            <!-- Jatuh Tempo -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Jatuh Tempo</label>
                                <input type="date" name="jatuh_tempo" class="form-control border-0 bg-light py-3"
                                       value="{{ old('jatuh_tempo', date('Y-m-10')) }}" style="border-radius: 12px;" required>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-4">
                                <hr class="text-muted opacity-25">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-light px-4 py-2 fw-bold" style="border-radius: 10px;">Reset</button>
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" style="border-radius: 10px; background-color: #0d6efd;">
                                        <i class="fas fa-save me-1"></i> Simpan Tagihan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light { background-color: #f8f9fa !important; }
    .form-label { color: #555; }
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
        background-color: #fff !important;
        border: 1px solid #0d6efd !important;
    }
</style>
@endsection
