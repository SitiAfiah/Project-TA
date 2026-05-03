<?php

namespace App\Http\Controllers;

use App\Exports\SppExport;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\Spp;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SppController extends Controller
{
    public function index(Request $request)
    {
        $query = Spp::with(['anggota', 'kolat']);

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('cari')) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->cari.'%');
            });
        }

        $data_spp = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $stats = [
            'lunas' => Spp::where('status', 'lunas')->count(),
            'pending' => Spp::where('status', 'pending')->count(),
            'belum_bayar' => Spp::where('status', 'belum_bayar')->count(),
            'total_pemasukan' => Spp::where('status', 'lunas')->sum('nominal'),
        ];

        return view('spp.pengurus.index', compact('data_spp', 'stats'));
    }

    public function create()
    {
        // Pastikan nama kolom di database adalah 'nama', bukan 'nama_lengkap' (sesuai diskusi sebelumnya)
        $data_anggota = Anggota::orderBy('nama_lengkap', 'asc')->get();

        return view('spp.pengurus.create', compact('data_anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'bulan' => 'required',
            'tahun' => 'required',
            'nominal' => 'required|numeric',
            'jatuh_tempo' => 'required|date',
        ]);

        try {
            $anggota = Anggota::findOrFail($request->anggota_id);

            Spp::create([
                'anggota_id' => $request->anggota_id,
                'kolat_id' => $anggota->kolat_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'nominal' => $request->nominal,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => 'belum_bayar',
            ]);

            return redirect()->route('spp.index')->with('success', 'Tagihan berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: '.$e->getMessage());
        }
    }

    public function bayar(Request $request, $id)
    {
        // Perbaikan: Pakai 'anggota' (singular) sesuai relasi model biasanya
        $spp = Spp::with('anggota')->findOrFail($id);

        if ($spp->status == 'lunas') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        DB::beginTransaction();
        try {
            $saldo_terakhir = Kas::saldoTerakhir() ?? 0;
            $saldo_baru = $saldo_terakhir + $spp->nominal;

            $kas = Kas::create([
                'tanggal' => now(),
                'jenis' => 'masuk',
                'kategori' => 'SPP',
                'nominal' => $spp->nominal,
                'keterangan' => 'Bayar SPP '.$spp->anggota->nama.' - '.$spp->bulan.' '.$spp->tahun,
                'saldo_akhir' => $saldo_baru,
            ]);

            $spp->update([
                'status' => 'lunas',
                'tanggal_bayar' => now(),
                'kas_id' => $kas->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pembayaran SPP berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function generateTagihan(Request $request)
    {
        $bulanIni = Carbon::now()->translatedFormat('F');
        $tahunIni = Carbon::now()->year;
        $jatuhTempo = Carbon::now()->day(10);

        $anggota = Anggota::all();

        DB::beginTransaction();
        try {
            foreach ($anggota as $agt) {
                $exists = Spp::where('anggota_id', $agt->id)
                    ->where('bulan', $bulanIni)
                    ->where('tahun', $tahunIni)
                    ->exists();

                if (! $exists) {
                    Spp::create([
                        'anggota_id' => $agt->id,
                        'kolat_id' => $agt->kolat_id,
                        'bulan' => $bulanIni,
                        'tahun' => $tahunIni,
                        'nominal' => 50000,
                        'jatuh_tempo' => $jatuhTempo,
                        'status' => 'belum_bayar',
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('spp.index')->with('success', 'Tagihan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Gagal: '.$e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $query = Spp::with(['anggota', 'kolat']);

        // Terapkan filter yang sama dengan index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('cari')) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->cari.'%');
            });
        }

        $data = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        return Excel::download(new SppExport($data), 'Laporan_SPP_TapakMP_'.date('Ymd').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Spp::with(['anggota', 'kolat']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('cari')) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->cari.'%');
            });
        }

        $data_spp = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        $pdf = Pdf::loadView('spp.pengurus.export_pdf', compact('data_spp'));
        $pdf->setPaper('a4', 'landscape'); // Landscape agar tabel lega

        return $pdf->download('Laporan_SPP_TapakMP_'.date('Ymd').'.pdf');
    }
}
