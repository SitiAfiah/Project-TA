@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold">Penilaian: <span class="text-primary">{{ $pelatih->nama_lengkap }}</span></h5>
                    <p class="text-muted small">Kolat: {{ $pelatih->kolat->nama_kolat }} | Periode: {{ now()->format('F Y') }}</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('penilaian.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pelatih_id" value="{{ $pelatih->id }}">

                        @php
                            $kriterias = [
                                'metode_pelatihan' => 'Metode Pelatihan',
                                'komunikasi' => 'Komunikasi Pelatih & Anggota',
                                'sikap_kepribadian' => 'Sikap & Kepribadian',
                                'kepemimpinan' => 'Kepemimpinan',
                                'konsistensi_komitmen' => 'Konsistensi & Komitmen',
                                'kedekatan_interpersonal' => 'Kedekatan Interpersonal'
                            ];
                        @endphp

                        @foreach($kriterias as $key => $label)
                        <div class="mb-4 border-bottom pb-3">
                            <label class="fw-bold d-block mb-2">{{ $label }}</label>
                            <div class="d-flex gap-3">
                                @foreach(range(1, 5) as $score)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="{{ $key }}" id="{{ $key.$score }}" value="{{ $score }}" required>
                                    <label class="form-check-label" for="{{ $key.$score }}">{{ $score }} ★</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="mb-4">
                            <label class="fw-bold mb-2">Kritik & Saran (Opsional)</label>
                            <textarea name="kritik_saran" class="form-control" rows="3" placeholder="Tulis masukan Anda di sini..." style="border-radius: 12px;"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold" style="border-radius: 12px;">Kirim Penilaian Anonim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
