<?php

namespace App\Http\Controllers;
use App\Models\Anggota;
use App\Models\Kolat;
use App\Models\Presensi;
use App\Models\Role;
use App\Models\Spp;
use Illuminate\Support\Facades\DB;
// use Illuminate\Http\Request;

class DashboardController extends Controller
{
 public function index()
    {
        // --- 1. DATA STATISTIK UTAMA ---
        $total_anggota_aktif = Anggota::where('status', 'Aktif')->count();
        $total_kolat = Kolat::count();

        // Ambil ID Role Pelatih secara dinamis
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();
        $total_pelatih = $rolePelatih ? Anggota::where('role_id', $rolePelatih->id)->count() : 0;

        // Hitung SPP yang statusnya masih 'pending' atau 'belum_lunas'
        $spp_pending = Spp::whereIn('status', ['pending', 'belum_lunas'])->count();

        // --- 2. DATA GRAFIK LINE (TREN PRESENSI 7 HARI) ---
        $grafikPresensi = Presensi::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('status', 'hadir')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // --- 3. DATA GRAFIK DOUGHNUT (KOMPOSISI SABUK) ---
        $persebaran_sabuk = Anggota::select('tingkatan', DB::raw('count(*) as total'))
            ->groupBy('tingkatan')
            ->get();

        // --- 4. DATA AKTIVITAS TERBARU (REAL DARI SPP) ---
        $aktivitas = Spp::with('anggota')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'waktu' => $item->created_at->format('d/m/Y H:i'),
                    'kegiatan' => 'Pembayaran SPP: ' . ($item->keterangan ?? 'Iuran Bulanan'),
                    'user' => $item->anggota->nama_lengkap ?? 'System',
                    'status' => strtoupper($item->status),
                    'badge' => $item->status == 'lunas' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger')
                ];
            });

        // --- 5. LOGIKA KELAYAKAN UNTUK RINGKASAN ---
        $anggota = Anggota::with('presensi')->get();
        $jumlah_layak_ujian = $anggota->filter(function($item) {
            $total = $item->presensi->count();
            $hadir = $item->presensi->where('status', 'hadir')->count();
            $persen = $total > 0 ? ($hadir / $total) * 100 : 0;
            return $persen >= 75;
        })->count();

        return view('dashboard.dashboard', compact(
            'total_anggota_aktif',
            'total_pelatih',
            'total_kolat',
            'spp_pending',
            'grafikPresensi',
            'persebaran_sabuk',
            'aktivitas',
            'jumlah_layak_ujian'
        ));
    }
}
