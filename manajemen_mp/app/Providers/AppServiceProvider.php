<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Models\Jadwal;
use App\Models\Kas;
use App\Models\Spp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();
            $userRole = 'Anggota';
            if ($user->anggota) {
                    if ($user->anggota->roles->contains('nama_role', 'Pengurus')) {
                        $userRole = 'Pengurus';
                    } elseif ($user->anggota->roles->contains('nama_role', 'Pelatih')) {
                        $userRole = 'Pelatih';
                    }
            }// Sesuaikan dengan cara Anda ambil role
            $notifications = [];
            $unreadCount = 0;

           // --- 1. LOGIKA NOTIFIKASI PENGURUS ---
                if ($userRole == 'Pengurus') {

                    // A. Verifikasi SPP Pending
                    $sppPending = Spp::where('status', 'pending')->count();
                    if ($sppPending > 0) {
                        $notifications[] = [
                            'icon'  => 'bi-cash-stack text-warning',
                            'title' => 'Verifikasi SPP',
                            'msg'   => "Ada $sppPending bukti bayar menunggu validasi.",
                            'link'  => route('spp.index')
                        ];
                    }

                    // B. Verifikasi Anggota Baru (Pendaftar)
                    $anggotaBaru = Anggota::where('status', 'Pending')->count();
                    if ($anggotaBaru > 0) {
                        $notifications[] = [
                            'icon'  => 'bi-person-plus text-primary',
                            'title' => 'Verifikasi Anggota',
                            'msg'   => "Ada $anggotaBaru calon anggota baru perlu divalidasi.",
                            'link'  => route('anggota.anggota')
                        ];
                    }

                    // C. Kandidat Pelatih Baru (Kriteria: Usia >= 18 & Tingkatan Balik 1)
                    // Kita hitung menggunakan Carbon untuk tanggal lahir
                    $kandidatPelatih = Anggota::where('tingkatan', 'Balik 1')
                        ->where('status', 'Aktif')
                        ->where('tgl_lahir', '<=', Carbon::now()->subYears(18))
                        ->count();

                    if ($kandidatPelatih > 0) {
                        $notifications[] = [
                            'icon'  => 'bi-star-fill text-info',
                            'title' => 'Kandidat Pelatih',
                            'msg'   => "Ada $kandidatPelatih anggota Balik 1 berusia 18th siap jadi Pelatih.",
                            'link'  => route('anggota.anggota') // Bisa diarahkan ke daftar anggota filter Balik 1
                        ];
                    }

                    // D. Peringatan Saldo Kas Kritis (< 500rb)
                    $saldo = Kas::sum('nominal');
                    if ($saldo < 500000) {
                        $notifications[] = [
                            'icon'  => 'bi-exclamation-triangle text-danger',
                            'title' => 'Peringatan Saldo',
                            'msg'   => "Saldo Kas Cabang kritis: Rp " . number_format($saldo, 0, ',', '.'),
                            'link'  => route('kas.index')
                        ];
                    }
                }

            // --- LOGIKA NOTIFIKASI PELATIH ---
            if ($userRole == 'Pelatih') {
                $jadwalHariIni = Jadwal::whereDate('tanggal', date('Y-m-d'))->count();
                if ($jadwalHariIni > 0) {
                    $notifications[] = [
                        'icon' => 'bi-calendar-event text-primary',
                        'title' => 'Jadwal Latihan',
                        'message' => "Anda memiliki $jadwalHariIni jadwal hari ini.",
                        'link' => route('jadwal.index')
                    ];
                    $unreadCount += 1;
                }
            }

            // --- LOGIKA NOTIFIKASI ANGGOTA ---
            if ($userRole == 'Anggota') {
                $sppSaya = Spp::where('anggota_id', $user->anggota->id)
                               ->where('status', 'belum_lunas')->count();
                if ($sppSaya > 0) {
                    $notifications[] = [
                        'icon' => 'bi-cash-stack text-danger',
                        'title' => 'Tagihan SPP',
                        'message' => "Anda memiliki $sppSaya tagihan belum terbayar.",
                        'link' => route('spp.anggota.index')
                    ];
                    $unreadCount += 1;
                }
            }

            // Kirim data ke SEMUA View
            $view->with('globalNotifications', $notifications);
            $view->with('globalUnreadCount', $unreadCount);
        }
    });
    }
}
