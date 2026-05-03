<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KAS PPS MERPATI PUTIH</h2>
        <p>Cabang Jember | Periode: {{ request('dari') ?? 'Semua' }} s/d {{ request('sampai') ?? 'Sekarang' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori / Keterangan</th>
                <th>Masuk (Rp)</th>
                <th>Keluar (Rp)</th>
                <th>Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_kas as $key => $item)
            <tr>
                <td style="text-align:center">{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $item->kategori }}</strong><br>
                    <small>{{ $item->keterangan }}</small>
                </td>
                <td class="text-right">{{ $item->jenis == 'masuk' ? number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $item->jenis == 'keluar' ? number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ number_format($item->saldo_akhir, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="3" style="text-align:center">TOTAL</td>
                <td class="text-right">{{ number_format($total_masuk, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_keluar, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($data_kas->last()->saldo_akhir ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
