<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kolat;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request) // Tambahkan Request untuk filter
    {
        // 1. Ambil data Kolat untuk dropdown filter
        $data_kolat = Kolat::all();

        // 2. Query data anggota dengan filter
        $query = Anggota::with(['presensi', 'spp', 'kolat']);

        if ($request->kolat_id) {
            $query->where('kolat_id', $request->kolat_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $anggota = $query->get();

        // 3. Transformasi data untuk Rekap
        $dataRekap = $anggota->map(function ($item) {
            // Logika Presensi
            $totalPertemuan = $item->presensi->count();
            $hadir = $item->presensi->where('status', 'hadir')->count();
            $persentasePresensi = $totalPertemuan > 0 ? ($hadir / $totalPertemuan) * 100 : 0;

            // Logika SPP
            $punyaTunggakan = $item->spp->where('status', 'belum_lunas')->count() > 0;

            // Logika Keaktifan
            $statusAktif = ($totalPertemuan > 0 && $persentasePresensi > 0) ? 'Aktif' : 'Non-Aktif';

            // Syarat Ujian (Presensi >= 75% dan SPP Lunas)
            $layakUjian = ($persentasePresensi >= 75 && !$punyaTunggakan);

            return [
                'nama' => $item->nama_lengkap, // Sesuaikan dengan kolom tabel Anda
                'no_induk' => $item->no_induk,
                'tingkatan' => $item->tingkatan,
                'nama_kolat' => $item->kolat->nama_kolat ?? '-',
                'status_aktif' => $statusAktif,
                'persentase_presensi' => round($persentasePresensi, 1),
                'status_spp' => $punyaTunggakan ? 'Ada Tunggakan' : 'Lunas',
                'layak' => $layakUjian
            ];
        });

        // 4. Kirim variabel yang dibutuhkan view
        return view('rekap.index', compact('dataRekap', 'data_kolat'));
    }
}
