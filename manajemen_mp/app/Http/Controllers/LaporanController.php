<?php

namespace App\Http\Controllers;

use App\Exports\PresensiExport;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\Kolat;
use App\Models\Presensi;
use App\Models\Spp;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // Halaman Laporan Anggota
    public function anggota(Request $request)
    {
        $data_kolat = Kolat::all();
        $query = Anggota::query();

        if ($request->filled('kolat_id')) {
            $query->where('kolat_id', $request->kolat_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data_anggota = $query->with('kolat')->get();

        return view('laporan.anggota', compact('data_anggota', 'data_kolat'));
    }

    // Halaman Laporan Keuangan (Kas)
    public function kas(Request $request)
    {
        $query = Kas::query();

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        $data_kas = $query->orderBy('tanggal', 'asc')->get();

        return view('laporan.kas', compact('data_kas'));
    }

    // Halaman Laporan SPP
    public function spp(Request $request)
{
    // 1. Ambil semua Kolat untuk isi Dropdown Filter
    $data_kolat = Kolat::all();

    $query = Spp::with(['anggota', 'kolat']);

    // 2. Logika Filter Kolat
    if ($request->filled('kolat_id')) {
        $query->where('kolat_id', $request->kolat_id);
    }

    // 3. Filter lainnya
    if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
    if ($request->filled('tahun')) $query->where('tahun', $request->tahun);
    if ($request->filled('status')) $query->where('status', $request->status);

    $data_spp = $query->get();

    return view('laporan.spp', compact('data_spp', 'data_kolat'));
}


  public function presensi(Request $request)
{
    $data_kolat = \App\Models\Kolat::all();
    $query = Presensi::with(['anggota', 'jadwal.kolat', 'jadwal.pelatih', 'pelatihVerifikator']);

    // Filter Kolat
    if ($request->filled('kolat_id')) {
        $query->whereHas('jadwal', function($q) use ($request) {
            $q->where('kolat_id', $request->kolat_id);
        });
    }

    // Filter Tanggal
    if ($request->filled('dari') && $request->filled('sampai')) {
        $query->whereHas('jadwal', function($q) use ($request) {
            $q->whereBetween('tanggal', [$request->dari, $request->sampai]);
        });
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $data_presensi = $query->orderBy('created_at', 'desc')->get();

    return view('laporan.presensi', compact('data_presensi', 'data_kolat'));
}

public function presensiExcel(Request $request)
{
    // Logika query yang sama dengan di atas
    $query = Presensi::with(['anggota', 'jadwal.kolat', 'jadwal.pelatih', 'pelatihVerifikator']);
    if ($request->filled('kolat_id')) {
        $query->whereHas('jadwal', function($q) use ($request) { $q->where('kolat_id', $request->kolat_id); });
    }
    if ($request->filled('dari') && $request->filled('sampai')) {
        $query->whereHas('jadwal', function($q) use ($request) { $q->whereBetween('tanggal', [$request->dari, $request->sampai]); });
    }
    $data = $query->get();

    return Excel::download(new PresensiExport($data), 'Laporan_Presensi_TapakMP_' . date('Ymd') . '.xlsx');
}

public function presensiPdf(Request $request)
{
    $query = Presensi::with(['anggota', 'jadwal.kolat', 'jadwal.pelatih', 'pelatihVerifikator']);
    if ($request->filled('kolat_id')) {
        $query->whereHas('jadwal', function($q) use ($request) { $q->where('kolat_id', $request->kolat_id); });
    }
    if ($request->filled('dari') && $request->filled('sampai')) {
        $query->whereHas('jadwal', function($q) use ($request) { $q->whereBetween('tanggal', [$request->dari, $request->sampai]); });
    }
    $data_presensi = $query->get();

    $pdf = Pdf::loadView('laporan.presensi_pdf', compact('data_presensi'));
    $pdf->setPaper('a4', 'landscape');
    return $pdf->download('Laporan_Presensi_TapakMP_' . date('Ymd') . '.pdf');
}
}
