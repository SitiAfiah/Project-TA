<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEHADIRAN ANGGOTA PPS MERPATI PUTIH</h2>
        <p>Cabang Jember | Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Kolat</th>
                <th>Tanggal Latihan</th>
                <th>Pelatih</th>
                <th>Status</th>
                <th>Verifikator</th>
                <th>Ket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_presensi as $key => $p)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $p->anggota->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->jadwal->kolat->nama_kolat ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->jadwal->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $p->jadwal->pelatih->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->status }}</td>
                <td>{{ $p->pelatihVerifikator->nama_lengkap ?? 'Sistem' }}</td>
                <td>{{ $p->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
