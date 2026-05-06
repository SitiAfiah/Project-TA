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
        $spp = Spp::with('anggota')->findOrFail($id);

        if ($spp->status == 'lunas') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        DB::beginTransaction();
        try {
            $saldo_terakhir = Kas::saldoTerakhir() ?? 0;
            $saldo_baru = $saldo_terakhir + $spp->nominal;

            // Buat Catatan Kas
            $kas = Kas::create([
                'tanggal' => now(),
                'jenis' => 'masuk',
                'kategori' => 'SPP',
                'nominal' => $spp->nominal,
                'keterangan' => 'Bayar SPP ' . $spp->anggota->nama_lengkap . ' - ' . $spp->bulan . ' ' . $spp->tahun,
                'saldo_akhir' => $saldo_baru,
            ]);

            // Update Status SPP
            $spp->update([
                'status' => 'lunas',
                // Jangan timpa tanggal_bayar jika sudah diisi saat upload bukti TF
                'tanggal_bayar' => $spp->tanggal_bayar ?? now(),
                'kas_id' => $kas->id,
            ]);

            DB::commit();

            $pesan = $spp->status == 'pending' ? 'Pembayaran via transfer berhasil divalidasi!' : 'Pembayaran SPP tunai berhasil dicatat!';
            return redirect()->back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    // public function indexAnggota()
    // {
    //     // Sesuaikan cara pemanggilan ID anggota dengan struktur tabel User kamu
    //     // Contoh ini mengasumsikan di tabel users ada kolom 'anggota_id'
    //     $anggota_id = auth()->user()->anggota_id;

    //     $data_spp = Spp::where('anggota_id', $anggota_id)
    //                    ->orderBy('tahun', 'desc')
    //                    ->orderBy('bulan', 'desc')
    //                    ->get();

    //     return view('spp.anggota.index', compact('data_spp'));
    // }

    public function indexAnggota()
    {
        // 1. Panggil relasi anggota dari user yang sedang login
        $profil_anggota = auth()->user()->anggota;

        // 2. Proteksi jika user belum punya profil anggota (mencegah error)
        if (!$profil_anggota) {
            $data_spp = collect(); // Kirim data kosong agar tidak error di view
            return view('spp.anggota.index', compact('data_spp'))
                   ->with('error', 'Profil anggota Anda belum terhubung. Silakan hubungi pengurus.');
        }

        // 3. Jika aman, ambil ID anggotanya
        $anggota_id = $profil_anggota->id;

        // 4. Cari tagihan SPP
        $data_spp = Spp::where('anggota_id', $anggota_id)
                       ->orderBy('tahun', 'desc')
                       ->orderBy('bulan', 'desc')
                       ->get();

        return view('spp.anggota.index', compact('data_spp'));
    }
    // 2. Menampilkan form upload bukti transfer
    // public function formBayarAnggota($id)
    // {
    //     $spp = Spp::findOrFail($id);

    //     // Keamanan: Pastikan yang diakses adalah tagihannya sendiri
    //     if ($spp->anggota_id != auth()->user()->anggota_id) {
    //         abort(403, 'Akses ditolak.');
    //     }

    //     return view('spp.anggota.bayar', compact('spp'));
    // }
    public function formBayarAnggota($id)
    {
        $spp = Spp::findOrFail($id);
        $profil_anggota = auth()->user()->anggota;

        // Proteksi 1: Pastikan user punya profil anggota
        if (!$profil_anggota) {
            abort(403, 'Profil anggota belum terhubung.');
        }

        // Proteksi 2: Pastikan yang diakses adalah tagihannya sendiri
        if ($spp->anggota_id != $profil_anggota->id) {
            abort(403, 'Akses ditolak. Ini bukan tagihan Anda.');
        }

        return view('spp.anggota.bayar', compact('spp'));
    }

    // 3. Memproses upload gambar bukti TF
    public function prosesBayarAnggota(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ], [
            'bukti_pembayaran.required' => 'Bukti transfer wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
        ]);

        $spp = Spp::findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            // Membuat nama file unik
            $nama_file = time() . "_" . $file->getClientOriginalName();

            // Pindahkan gambar ke folder public/uploads/bukti_spp
            $file->move(public_path('uploads/bukti_spp'), $nama_file);

            // Update status menjadi pending dan simpan nama file
            $spp->update([
                'bukti_pembayaran' => $nama_file,
                'status' => 'pending',
                'tanggal_bayar' => now(), // Mencatat waktu upload
            ]);

            return redirect()->route('spp.anggota.index')->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi pengurus.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
}
