<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Jadwal;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{
    // public function index()
    // {
    //     // 1. Ambil data profil anggota yang sedang login
    //     $user = Auth::user();
    //     $anggota = Anggota::where('user_id', $user->id)->first();

    //     if (!$anggota) {
    //         return redirect('/')->with('error', 'Data anggota tidak ditemukan.');
    //     }

    //     // 2. Ambil Jadwal Latihan (Hanya yang sesuai dengan Kolat si anggota)
    //     $jadwal = Jadwal::where('kolat_id', $anggota->kolat_id)
    //                     ->where('tanggal', '>=', now())
    //                     ->orderBy('tanggal', 'asc')
    //                     ->take(5)
    //                     ->get();

    //     // 3. Ambil Tagihan SPP (Contoh: mencari data di tabel kas yang belum lunas)
    //     // Asumsi Anda punya kolom 'anggota_id' dan 'status' di tabel Kas/SPP
    //     $tagihan_spp = Kas::where('no_induk', $anggota->no_induk)
    //                       ->where('status', 'Pending')
    //                       ->get();

    //     // 4. Hitung Ringkasan (Contoh: Total kehadiran)
    //     // Ini hanya dummy, silakan sesuaikan dengan tabel absensi Anda
    //     $persentase_hadir = 85;

    //     return view('anggota.dashboard', compact('anggota', 'jadwal', 'tagihan_spp', 'persentase_hadir'));
    // }
   public function index()
    {
        // 1. Ambil data profil anggota yang sedang login
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect('/')->with('error', 'Data anggota tidak ditemukan.');
        }

        // 2. Ambil Jadwal Latihan
        // PERBAIKAN: Gunakan today() atau now()->toDateString() alih-alih now().
        // now() membawa jam (misal 19:42), sehingga jadwal hari ini yang jamnya sudah lewat bisa tidak terbaca.
        $jadwal = Jadwal::where('kolat_id', $anggota->kolat_id)
                        ->where('tanggal', '>=', today())
                        ->orderBy('tanggal', 'asc')
                        ->take(5)
                        ->get();

        // 3. Ambil Tagihan SPP
        // PERBAIKAN PENTING: Tadi kita sepakat status awalnya adalah 'belum_bayar', bukan 'Pending'
        $tagihan_spp = $anggota->spp()
                               ->where('status', 'belum_bayar')
                               ->get();

        // 4. Hitung Ringkasan Kehadiran
        $total_latihan = $anggota->riwayatPresensi()->count();
        $total_hadir = $anggota->riwayatPresensi()->where('status', 'Hadir')->count();

        $persentase_hadir = 0;
        if ($total_latihan > 0) {
            $persentase_hadir = round(($total_hadir / $total_latihan) * 100);
        }

        return view('anggota.dashboard', compact('anggota', 'jadwal', 'tagihan_spp', 'persentase_hadir'));
    }
};
