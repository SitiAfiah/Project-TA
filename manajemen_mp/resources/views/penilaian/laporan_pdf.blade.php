<!DOCTYPE html>
<html>
<head>
    <title>Laporan Performa - {{ $pelatih->nama_lengkap }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #4f46e5; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }

        .info-table { width: 100%; margin-bottom: 20px; font-size: 13px; }
        .info-table td { padding: 3px 0; }
        .label { font-weight: bold; width: 120px; }

        .section-title { background: #f0f2f5; padding: 8px; font-weight: bold; font-size: 14px; margin: 20px 0 10px; border-left: 4px solid #4f46e5; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th { background: #4f46e5; color: white; text-align: left; padding: 10px; font-size: 12px; }
        table.data-table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 12px; }

        .bar-container { background: #eee; width: 100%; height: 10px; border-radius: 5px; }
        .bar-fill { background: #4f46e5; height: 10px; border-radius: 5px; }

        .ulasan-item { padding: 10px; border-bottom: 1px solid #f0f0f0; font-size: 11px; font-style: italic; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN EVALUASI PELATIH</h2>
        <p>PPS BETAKO MERPATI PUTIH - CABANG JEMBER</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pelatih</td>
            <td>: {{ $pelatih->nama_lengkap }}</td>
            <td class="label">Periode</td>
            <td>: {{ now()->format('F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Unit Latihan</td>
            {{-- <td>: Kolat {{ $pelatih->kolat->nama_kolat ?? '-' }}</td> --}}
            <td class="label">Unit Latihan</td>
            <td>:
                @foreach($pelatih->kolatLatihan as $kolat)
                    {{ $kolat->nama_kolat }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="section-title">A. STATISTIK PENILAIAN</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Aspek Penilaian</th>
                <th width="50">Skor</th>
                <th width="150">Visual</th>
            </tr>
        </thead>
        <tbody>
            @php
                $scores = [
                    'Metode Pelatihan' => $rekap->avg_metode,
                    'Komunikasi' => $rekap->avg_komunikasi,
                    'Sikap & Etika' => $rekap->avg_sikap,
                    'Kepemimpinan' => $rekap->avg_kepemimpinan,
                    'Komitmen' => $rekap->avg_komitmen,
                    'Interpersonal' => $rekap->avg_interpersonal,
                ];
            @endphp
            @foreach($scores as $label => $val)
            <tr>
                <td>{{ $label }}</td>
                <td><strong>{{ number_format($val, 1) }}</strong></td>
                <td>
                    <div class="bar-container">
                        <div class="bar-fill" style="width: {{ ($val / 5) * 100 }}%;"></div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">B. ULASAN & MASUKAN ANGGOTA</div>
    @forelse($ulasan as $text)
        <div class="ulasan-item">"{{ $text }}"</div>
    @empty
        <p style="font-size: 11px; color: #666;">Belum ada ulasan tertulis.</p>
    @endforelse

    <div class="footer">
        Laporan ini dihasilkan secara otomatis oleh Sistem TapakMP - Merpati Putih Jember
    </div>
</body>
</html>
