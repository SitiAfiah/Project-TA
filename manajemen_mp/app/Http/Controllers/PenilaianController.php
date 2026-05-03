<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Penilaian;
use Carbon\Carbon;
use Illuminate\Http\Request;


class PenilaianController extends Controller
{
    public function index()
    {
        $data_pelatih = Anggota::where('jabatan', 'Pelatih')
                        ->with('kolat')
                        ->get();

        return view('penilaian.index', compact('data_pelatih'));
    }

    public function create($id)
    {
        // BYPASS: Hapus pengecekan auth() agar tidak mental ke login
        $pelatih = Anggota::with('kolat')->findOrFail($id);
        return view('penilaian.create', compact('pelatih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelatih_id' => 'required',
            'metode_pelatihan' => 'required|integer|min:1|max:5',
            'komunikasi' => 'required|integer|min:1|max:5',
            'sikap_kepribadian' => 'required|integer|min:1|max:5',
            'kepemimpinan' => 'required|integer|min:1|max:5',
            'konsistensi_komitmen' => 'required|integer|min:1|max:5',
            'kedekatan_interpersonal' => 'required|integer|min:1|max:5',
            'kritik_saran' => 'nullable|string'
        ]);

        Penilaian::create([
            'pelatih_id' => $request->pelatih_id,
            'anggota_id' => 1, // TESTING: Isi manual ID Anggota penilai (misal ID 1)
            'bulan_evaluasi' => Carbon::now()->startOfMonth(),
            'metode_pelatihan' => $request->metode_pelatihan,
            'komunikasi' => $request->komunikasi,
            'sikap_kepribadian' => $request->sikap_kepribadian,
            'kepemimpinan' => $request->kepemimpinan,
            'konsistensi_komitmen' => $request->konsistensi_komitmen,
            'kedekatan_interpersonal' => $request->kedekatan_interpersonal,
            'kritik_saran' => $request->kritik_saran,
        ]);

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan!');
    }

   public function show($id)
{
    $pelatih = Anggota::with('kolat')->findOrFail($id);

    // Hitung rata-rata tiap kriteria bulan ini
    $rekap = Penilaian::where('pelatih_id', $id)
        ->whereMonth('bulan_evaluasi', now()->month)
        ->selectRaw('
            AVG(metode_pelatihan) as avg_metode,
            AVG(komunikasi) as avg_komunikasi,
            AVG(sikap_kepribadian) as avg_sikap,
            AVG(kepemimpinan) as avg_kepemimpinan,
            AVG(konsistensi_komitmen) as avg_komitmen,
            AVG(kedekatan_interpersonal) as avg_interpersonal
        ')->first();

    // Ambil ulasan/kritik saran (Pluck agar anonim)
    $ulasan = Penilaian::where('pelatih_id', $id)
        ->whereMonth('bulan_evaluasi', now()->month)
        ->whereNotNull('kritik_saran')
        ->pluck('kritik_saran');

    return view('penilaian.rekap', compact('pelatih', 'rekap', 'ulasan'));
}

public function exportPdf($id)
    {
        // 1. Ambil data pelatih
        $pelatih = Anggota::with('kolat')->findOrFail($id);

        // 2. Hitung rata-rata nilai (Rekap)
        $rekap = Penilaian::where('pelatih_id', $id)
            ->whereMonth('bulan_evaluasi', now()->month)
            ->selectRaw('
                AVG(metode_pelatihan) as avg_metode,
                AVG(komunikasi) as avg_komunikasi,
                AVG(sikap_kepribadian) as avg_sikap,
                AVG(kepemimpinan) as avg_kepemimpinan,
                AVG(konsistensi_komitmen) as avg_komitmen,
                AVG(kedekatan_interpersonal) as avg_interpersonal
            ')->first();

        // 3. Ambil ulasan ulasan anggota
        $ulasan = Penilaian::where('pelatih_id', $id)
            ->whereMonth('bulan_evaluasi', now()->month)
            ->whereNotNull('kritik_saran')
            ->pluck('kritik_saran');

        // 4. Panggil file view "laporan_pdf" sesuai nama filemu
        $pdf = Pdf::loadView('penilaian.laporan_pdf', compact('pelatih', 'rekap', 'ulasan'));

        // 5. Download file PDF
        return $pdf->download('Laporan-Performa-' . $pelatih->nama_lengkap . '.pdf');
    }

}
