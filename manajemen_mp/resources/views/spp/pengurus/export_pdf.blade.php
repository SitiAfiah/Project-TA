<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TAGIHAN SPP ANGGOTA</h2>
        <p>PPS Merpati Putih Cabang Jember | Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Kolat</th>
                <th>Periode</th>
                <th>Nominal</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_spp as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item->anggota->nama_lengkap ?? '-' }}</td>
                <td>{{ $item->kolat->nama_kolat ?? '-' }}</td>
                <td class="text-center">{{ $item->bulan }} {{ $item->tahun }}</td>
                <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/Y') }}</td>
                <td class="text-center">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
