<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { text-align: center; font-size: 12px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="title">DAFTAR ANGGOTA PPS MERPATI PUTIH</div>
    <div class="subtitle">Cabang Jember - Laporan Data Induk Keanggotaan</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Induk</th>
                <th>Nama Lengkap</th>
                <th>JK</th>
                <th>TTL</th>
                <th>No HP</th>
                <th>Kolat</th>
                <th>Tingkatan</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_anggota as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->no_induk }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td style="text-align:center">{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d/m/Y') }}</td>
                <td>{{ $item->no_hp }}</td>
                <td>{{ $item->kolat->nama_kolat ?? '-' }}</td>
                <td>{{ $item->tingkatan }}</td>
                <td>{{ $item->jabatan }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
