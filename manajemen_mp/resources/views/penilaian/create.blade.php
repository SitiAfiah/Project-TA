@extends('layout.app')

@section('content')
<!-- Wrapper khusus untuk membuat tampilan full-screen dan berpusat di tengah -->
<div class="d-flex align-items-center justify-content-center py-4" style="min-height: 85vh; background: linear-gradient(135deg, #f8faff 0%, #edf2f9 100%); margin: -1.5rem; padding: 2rem;">

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">

                <a href="{{ route('penilaian.anggota_index') }}" class="text-decoration-none text-muted mb-3 d-inline-flex align-items-center fw-medium btn-batal">
                    <i class="bi bi-arrow-left-circle-fill fs-4 me-2 text-primary"></i> Batal & Kembali
                </a>

                <div class="card border-0 shadow-lg form-kuis-card">

                    <!-- Progress Bar Interaktif -->
                    <div class="progress-wrapper px-4 pt-4 pb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold small text-uppercase tracking-wider" id="progressText">Pertanyaan 1 dari 6</span>
                            <span class="badge bg-primary-soft text-primary rounded-pill px-3"><i class="bi bi-star-fill me-1 text-warning"></i> Evaluasi Bulanan</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 20px; background-color: #e2e8f0;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="formProgressBar" role="progressbar" style="width: 14%; background-color: var(--accent-color, #feb900); border-radius: 20px;" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-sm-5 relative text-center">

                        <!-- Profil Pelatih Mini -->
                        <div class="d-inline-flex align-items-center bg-light rounded-pill p-2 pe-4 mb-4 border shadow-sm mx-auto">
                            @if($pelatih->foto_profil)
                                <img src="{{ asset('storage/' . $pelatih->foto_profil) }}" alt="Foto" class="rounded-circle object-fit-cover me-3" style="width: 40px; height: 40px;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                            <div class="text-start">
                                <div class="fw-bold text-dark lh-1" style="font-size: 0.9rem;"> {{ $pelatih->nama_lengkap }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $pelatih->kolat->nama_kolat ?? '-' }}</div>
                            </div>
                        </div>

                        <form action="{{ route('penilaian.store') }}" method="POST" id="formPenilaian">
                            @csrf
                            <input type="hidden" name="pelatih_id" value="{{ $pelatih->id }}">

                            @php
                                $kriterias = [
                                    'metode_pelatihan' => 'Bagaimana cara Pelatih menyampaikan materi hari ini?',
                                    'komunikasi' => 'Seberapa asik dan jelas komunikasinya dengan anggota?',
                                    'sikap_kepribadian' => 'Bagaimana sikap dan kedisiplinan pelatih di lapangan?',
                                    'kepemimpinan' => 'Seberapa baik jiwa kepemimpinan pelatih saat memandu latihan?',
                                    'konsistensi_komitmen' => 'Seberapa semangat pelatih saat melatih kita?',
                                    'kedekatan_interpersonal' => 'Seberapa dekat dan peduli pelatih dengan para anggota?'
                                ];

                                $emojis = [
                                    1 => ['icon' => '😞', 'text' => 'Kurang'],
                                    2 => ['icon' => '😐', 'text' => 'Biasa'],
                                    3 => ['icon' => '🙂', 'text' => 'Cukup'],
                                    4 => ['icon' => '😄', 'text' => 'Bagus'],
                                    5 => ['icon' => '🤩', 'text' => 'Keren!']
                                ];

                                $step = 1;
                            @endphp

                            <!-- LOOPING PERTANYAAN KUIS -->
                            @foreach($kriterias as $key => $pertanyaan)
                            <div class="form-step" id="step-{{ $step }}" {!! $step > 1 ? 'style="display: none;"' : '' !!}>
                                <h3 class="fw-bolder text-dark mb-5 lh-base px-2 pertanyaan-teks" style="font-size: clamp(1.2rem, 3vw, 1.8rem);">
                                    {{ $pertanyaan }}
                                </h3>

                                <div class="d-flex justify-content-center flex-wrap gap-2 gap-md-3 mb-4">
                                    @foreach(range(1, 5) as $score)
                                        <div class="rating-wrapper">
                                            <input class="rating-input" type="radio" name="{{ $key }}" id="{{ $key.$score }}" value="{{ $score }}" onclick="nextStep({{ $step }})" required>
                                            <label class="rating-label shadow-sm" for="{{ $key.$score }}">
                                                <span class="emoji-rate">{{ $emojis[$score]['icon'] }}</span>
                                                <span class="score-text mt-2">{{ $emojis[$score]['text'] }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @if($step > 1)
                                <button type="button" class="btn btn-link text-muted text-decoration-none mt-3 fw-medium btn-mundur" onclick="prevStep({{ $step }})">
                                    <i class="bi bi-arrow-up text-primary me-1"></i> Ganti jawaban sebelumnya
                                </button>
                                @endif
                            </div>
                            @php $step++; @endphp
                            @endforeach

                            <!-- STEP TERAKHIR: KRITIK & SARAN -->
                            <div class="form-step" id="step-7" style="display: none;">
                                <div class="mb-4">
                                    <span class="emoji-rate d-inline-block mb-3" style="font-size: 4rem; filter: none; opacity: 1;">📝</span>
                                    <h3 class="fw-bolder text-dark mb-2">Satu langkah lagi!</h3>
                                    <p class="text-muted">Ada pesan, kritik, saran, atau pujian untuk {{ $pelatih->nama_lengkap }}? (Boleh dikosongkan kok)</p>
                                </div>

                                <textarea name="kritik_saran" class="form-control bg-light border-0 mb-4 shadow-inner" rows="5" placeholder="Ketik pesan rahasiamu di sini..." style="border-radius: 20px; padding: 25px; resize: none; font-size: 1.1rem;"></textarea>

                                <div class="d-grid gap-3 mt-5">
                                    <button type="submit" class="btn btn-submit-nilai py-3 fw-bold fs-5 shadow">
                                        <i class="bi bi-send-fill me-2"></i> Kirim Penilaian Sekarang
                                    </button>
                                    <button type="button" class="btn btn-light py-3 fw-bold text-muted btn-kembali-akhir" onclick="prevStep(7)">
                                        Koreksi jawaban sebelumnya
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Card Super Halus */
    .form-kuis-card {
        border-radius: 30px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }

    .bg-primary-soft { background-color: rgba(6, 22, 58, 0.1); }
    .tracking-wider { letter-spacing: 1px; }

    /* Tombol Kembali / Batal */
    .btn-batal:hover { transform: translateX(-5px); transition: 0.3s; }
    .btn-mundur:hover, .btn-kembali-akhir:hover { background-color: #f1f5f9; border-radius: 15px; }

    /* Menyembunyikan radio button asli */
    .rating-input { display: none; }

    /* Kotak Pilihan Angka & Emoji */
    .rating-wrapper {
        flex: 1;
        min-width: 75px;
        max-width: 110px;
    }

    .rating-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 25px 10px;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); /* Efek memantul (bouncy) */
        color: #94a3b8;
        height: 100%;
    }

    .emoji-rate {
        font-size: 3rem;
        line-height: 1;
        filter: grayscale(100%);
        opacity: 0.4;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .score-text {
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Efek Saat Di-Hover (Sentuh) */
    .rating-label:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-5px);
    }
    .rating-label:hover .emoji-rate {
        filter: grayscale(0%);
        opacity: 1;
        transform: scale(1.1);
    }

    /* Efek Saat Di-Klik (Dipilih) */
    .rating-input:checked + .rating-label {
        background: var(--accent-color, #feb900);
        border-color: var(--accent-color, #feb900);
        color: var(--dark-blue, #06163a);
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 30px rgba(254, 185, 0, 0.4) !important;
    }

    .rating-input:checked + .rating-label .emoji-rate {
        filter: grayscale(0%);
        opacity: 1;
        transform: scale(1.2);
    }

    /* Animasi Transisi Halus antar Pertanyaan */
    .form-step {
        animation: slideUpFade 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }

    @keyframes slideUpFade {
        0% { opacity: 0; transform: translateY(30px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Styling Textarea */
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    textarea.form-control:focus {
        background: #ffffff !important;
        border: 2px solid var(--accent-color, #feb900) !important;
        box-shadow: 0 0 0 0.3rem rgba(254, 185, 0, 0.15) !important;
    }

    /* Tombol Submit Utama */
    .btn-submit-nilai {
        background: var(--dark-blue, #06163a);
        color: #fff;
        border-radius: 20px;
        border: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .btn-submit-nilai:hover {
        background: var(--accent-color, #feb900);
        color: var(--dark-blue, #06163a);
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(254, 185, 0, 0.5) !important;
    }
</style>

<!-- SCRIPT UNTUK PINDAH HALAMAN OTOMATIS & PROGRESS BAR -->
<script>
    const totalSteps = 7;
    const progressText = document.getElementById('progressText');
    const progressBar = document.getElementById('formProgressBar');

    function nextStep(currentStep) {
        // Beri jeda 400ms agar user bisa melihat animasi emojinya membesar sebelum pindah layar
        setTimeout(() => {
            document.getElementById('step-' + currentStep).style.display = 'none';

            let next = currentStep + 1;
            if (next <= totalSteps) {
                document.getElementById('step-' + next).style.display = 'block';
                updateProgress(next);
            }
        }, 400);
    }

    function prevStep(currentStep) {
        document.getElementById('step-' + currentStep).style.display = 'none';

        let prev = currentStep - 1;
        if (prev >= 1) {
            document.getElementById('step-' + prev).style.display = 'block';
            updateProgress(prev);
        }
    }

    function updateProgress(step) {
        // Update teks (jika di step terakhir ubah teksnya)
        if (step === totalSteps) {
            progressText.innerText = "Langkah Terakhir!";
        } else {
            progressText.innerText = "Pertanyaan " + step + " dari " + (totalSteps - 1);
        }

        // Hitung persentase progress (Step 7 = 100%)
        let percent = (step / totalSteps) * 100;
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);
    }
</script>
@endsection
