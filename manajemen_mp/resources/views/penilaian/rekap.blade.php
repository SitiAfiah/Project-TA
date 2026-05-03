@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Laporan Performa Pelatih</h3>
            <p class="text-muted small">Periode: {{ now()->format('F Y') }}</p>
        </div>
        <a href="{{ route('penilaian.index') }}" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 12px;">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px; background: linear-gradient(to right, #4f46e5, #6366f1);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pelatih->nama_lengkap) }}&background=fff&color=4f46e5&size=128"
                             class="rounded-circle border border-4 border-white shadow-sm" width="80">
                        <div class="ms-4 text-white">
                            <h4 class="fw-bold mb-1">{{ $pelatih->nama_lengkap }}</h4>
                            <span class="badge bg-white text-primary rounded-pill px-3">Kolat {{ $pelatih->kolat->nama_kolat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                @php
                    $kriteria = [
                        'metode_pelatihan' => ['label' => 'Metode Pelatihan', 'icon' => 'bi-book', 'color' => '#4f46e5'],
                        'komunikasi' => ['label' => 'Komunikasi', 'icon' => 'bi-chat-dots', 'color' => '#10b981'],
                        'sikap_kepribadian' => ['label' => 'Sikap & Etika', 'icon' => 'bi-person-badge', 'color' => '#06b6d4'],
                        'kepemimpinan' => ['label' => 'Kepemimpinan', 'icon' => 'bi-award', 'color' => '#f59e0b'],
                        'konsistensi_komitmen' => ['label' => 'Komitmen', 'icon' => 'bi-shield-check', 'color' => '#ef4444'],
                        'kedekatan_interpersonal' => ['label' => 'Interpersonal', 'icon' => 'bi-people', 'color' => '#6366f1'],
                    ];

                    $scores = [
                        'metode_pelatihan' => $rekap->avg_metode,
                        'komunikasi' => $rekap->avg_komunikasi,
                        'sikap_kepribadian' => $rekap->avg_sikap,
                        'kepemimpinan' => $rekap->avg_kepemimpinan,
                        'konsistensi_komitmen' => $rekap->avg_komitmen,
                        'kedekatan_interpersonal' => $rekap->avg_interpersonal
                    ];
                @endphp

                @foreach($kriteria as $key => $attr)
                <div class="col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; background-color: {{ $attr['color'] }}15; color: {{ $attr['color'] }};">
                                    <i class="bi {{ $attr['icon'] }}"></i>
                                </div>
                                <span class="ms-2 small fw-bold text-muted uppercase">{{ $attr['label'] }}</span>
                            </div>
                            <div class="d-flex align-items-end justify-content-between">
                                <h3 class="fw-bold mb-0 text-dark">{{ number_format($scores[$key], 1) }}</h3>
                                <small class="text-muted">/ 5.0</small>
                            </div>
                            <div class="progress mt-2" style="height: 6px; border-radius: 10px;">
                                <div class="progress-bar" style="width: {{ ($scores[$key] / 5) * 100 }}%; background-color: {{ $attr['color'] }}; border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 24px;">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-chat-square-quote me-2 text-primary"></i>Kritik & Saran Anggota</h5>
                    <hr class="mt-3 mb-0">
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @forelse($ulasan as $teks)
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light border-0 h-100 shadow-xs" style="border-radius: 15px;">
                                <p class="text-dark mb-2 small" style="line-height: 1.5;">"{{ $teks }}"</p>
                                <small class="text-muted italic" style="font-size: 10px;">— Masukan Anonim</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 w-100">
                            <p class="text-muted small">Belum ada ulasan tertulis untuk periode ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-xs { box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .italic { font-style: italic; }
    .progress { background-color: #f0f2f5; }
</style>
@endsection
