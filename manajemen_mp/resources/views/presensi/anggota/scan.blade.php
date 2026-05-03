@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header mb-4 text-start">
        <a href="{{ route('presensi.anggota.index') }}" class="text-decoration-none text-muted small mb-2 d-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <h3 class="fw-bold text-dark">Scan Barcode Kehadiran</h3>
        <p class="text-muted small">Arahkan kamera Anda ke QR Code yang ditampilkan oleh Pelatih.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 25px;">
                {{-- Area Kamera --}}
                <div id="reader" style="width: 100%; border: none;"></div>

                <div class="card-body p-4 text-center">
                    <div id="result-info">
                        <div class="spinner-grow text-primary spinner-grow-sm me-2" role="status"></div>
                        <span class="text-muted small">Mencari kamera...</span>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3 text-start">
                        <h6 class="fw-bold text-dark small mb-1"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Jadwal:</h6>
                        <p class="mb-0 small text-muted">{{ $jadwal->judul_kegiatan }}</p>
                        <p class="mb-0 small text-muted"><i class="bi bi-geo-alt small"></i> {{ $jadwal->lokasi }}</p>
                    </div>
                </div>
            </div>

            {{-- Form Tersembunyi untuk Submit Data --}}
            <form id="form-scan" action="{{ route('presensi.storeMandiri') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="jadwal_id" id="jadwal_id_input">
            </form>
        </div>
    </div>
</div>

{{-- Script QR Scanner --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // decodedText biasanya berisi URL, misal: http://tapakmp.test/presensi/scan-absen/12
        // Kita hanya butuh ID Jadwal-nya.

        // Logika sederhana: Kita bandingkan apakah URL yang discan mengandung ID jadwal yang benar
        const expectedUrl = "{{ route('presensi.anggota.scan', $jadwal->id) }}";

        if (decodedText === expectedUrl) {
            // Berhenti scan agar tidak submit berkali-kali
            html5QrcodeScanner.clear();

            // Masukkan ID ke form dan submit
            document.getElementById('jadwal_id_input').value = "{{ $jadwal->id }}";

            Swal.fire({
                title: 'Berhasil Mendeteksi!',
                text: 'Sedang memproses absensi Anda...',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });

            document.getElementById('form-scan').submit();
        } else {
            // Jika barcode yang discan salah/milik jadwal lain
            Swal.fire({
                title: 'Barcode Tidak Valid',
                text: 'Pastikan Anda men-scan barcode pelatih untuk latihan ini.',
                icon: 'error',
                confirmButtonColor: '#0d6efd'
            });
        }
    }

    function onScanFailure(error) {
        // Kegagalan scan (biasanya karena kamera tidak fokus atau barcode belum terlihat)
        // Kita biarkan saja agar dia terus mencoba scan otomatis
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: {width: 250, height: 250},
            aspectRatio: 1.0
        },
        /* verbose= */ false
    );

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    // Update status UI
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            document.getElementById('result-info').innerHTML =
                '<span class="badge bg-success-soft text-success rounded-pill px-3 py-2">' +
                '<i class="bi bi-camera-video me-2"></i>Kamera Aktif</span>';
        }, 2000);
    });
</script>

<style>
    /* Styling Scanner agar terlihat seperti aplikasi modern */
    #reader {
        background-color: #000;
    }
    #reader__dashboard_section_csr button {
        background-color: #0d6efd !important;
        color: white !important;
        border: none !important;
        padding: 8px 20px !important;
        border-radius: 10px !important;
        font-weight: bold !important;
        margin: 10px 0 !important;
    }
    #reader__status_span {
        display: none;
    }
    video {
        border-radius: 0 0 0 0 !important;
    }
    .bg-success-soft { background-color: #f0fdf4; color: #16a34a; }
</style>
@endsection
