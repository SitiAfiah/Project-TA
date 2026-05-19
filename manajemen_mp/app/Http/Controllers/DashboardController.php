<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kolat;
use App\Models\Presensi;
use App\Models\Role;
use App\Models\Spp;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. DATA STATISTIK UTAMA ---
        // Menghitung anggota dengan status Aktif

        // // Menghitung jumlah Kolat yang terdaftar
        $total_kolat = Kolat::count();

        $roleAnggota = Role::where('nama_role', 'Anggota')->first();
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();

        // 2. Hitung jumlah "Anggota" (Hanya yang ber-role 'Anggota' DAN berstatus 'Aktif')
        $total_anggota_aktif = Anggota::where('status', 'Aktif')
            ->where('role_id', $roleAnggota->id)
            ->count();

        // 3. Hitung jumlah "Pelatih" (Hanya yang ber-role 'Pelatih' DAN berstatus 'Aktif')
        $total_pelatih = Anggota::where('status', 'Aktif')
            ->where('role_id', $rolePelatih->id)
            ->count();

        // Menghitung SPP yang belum lunas (Pending atau Belum Lunas)
        // Pastikan 'pending' dan 'belum_lunas' sesuai dengan isi kolom status di database Anda
        $spp_pending = Spp::whereIn('status', ['pending', 'belum_lunas'])->count();

        // --- 2. DATA GRAFIK LINE (TREN PRESENSI 7 HARI) ---
        // Mengambil data kehadiran 7 hari terakhir untuk grafik
        $grafikPresensi = Presensi::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->where('status', 'hadir')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // --- 3. DATA GRAFIK DOUGHNUT (KOMPOSISI TINGKATAN) ---
        // Menghitung persebaran anggota berdasarkan kolom 'tingkatan'
        $persebaran_sabuk = Anggota::select('tingkatan', DB::raw('count(*) as total'))
            ->groupBy('tingkatan')
            ->get();

        // --- 4. DATA AKTIVITAS TERBARU (DARI TRANSAKSI SPP) ---
        // Mengambil 5 transaksi SPP terbaru beserta data anggotanya
        $aktivitas = Spp::with('anggota')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                // Menentukan warna badge berdasarkan status
                $statusLower = strtolower($item->status);
                $badge = 'danger'; // Default
                if ($statusLower == 'lunas') $badge = 'success';
                if ($statusLower == 'pending') $badge = 'warning';

                return [
                    'waktu' => $item->created_at->format('d/m/Y H:i'),
                    'kegiatan' => 'Pembayaran SPP: ' . ($item->keterangan ?? 'Iuran Bulanan'),
                    'user' => $item->anggota->nama_lengkap ?? 'Anggota', // Pastikan kolom 'nama_lengkap' ada
                    'status' => strtoupper($item->status),
                    'badge' => $badge
                ];
            });

        // --- 5. LOGIKA KELAYAKAN (RINGKASAN REKAP) ---
        // Menarik data anggota yang memenuhi syarat kehadiran 75%
        // Eager Loading 'presensi' agar query lebih cepat
        $anggota = Anggota::with('presensi')->get();
        $jumlah_layak_ujian = $anggota->filter(function ($item) {
            $total = $item->presensi->count();
            $hadir = $item->presensi->where('status', 'hadir')->count();
            $persen = $total > 0 ? ($hadir / $total) * 100 : 0;
            return $persen >= 75;
        })->count();

        // --- 6. RETURN KE VIEW ---
        // Pastikan path view sesuai (folder dashboard, file dashboard.blade.php)
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
