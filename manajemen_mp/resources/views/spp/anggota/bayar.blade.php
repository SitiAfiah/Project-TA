@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('spp.anggota.index') }}" class="btn btn-light shadow-sm me-3" style="border-radius: 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Upload Bukti Pembayaran</h3>
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="border-radius: 10px;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="alert alert-info border-0" style="border-radius: 12px; background-color: rgba(13, 110, 253, 0.1);">
                        <h6 class="fw-bold mb-1"><i class="fas fa-info-circle me-1"></i> Informasi Tagihan</h6>
                        <p class="mb-0 small text-dark">Anda akan melakukan pembayaran SPP periode <b>{{ $spp->bulan }} {{ $spp->tahun }}</b> sebesar <b>Rp {{ number_format($spp->nominal, 0, ',', '.') }}</b>.</p>
                    </div>

                    <!-- PENTING: enctype harus ada untuk upload file -->
                    <form action="{{ route('spp.anggota.prosesBayar', $spp->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4 mt-4">
                            <label class="form-label fw-bold">Pilih Bukti Transfer (Gambar/Foto)</label>
                            <!-- Input File -->
                            <input type="file" name="bukti_pembayaran" class="form-control border-0 bg-light py-2" accept="image/png, image/jpeg, image/jpg" required style="border-radius: 12px;">
                            <div class="form-text small mt-2">* Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran file 2MB.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px; font-size: 1.1rem;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
