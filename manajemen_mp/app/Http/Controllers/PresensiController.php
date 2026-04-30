<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
// TAMBAHAN WAJIB: Memanggil Facades dan Model yang digunakan
use App\Models\Jadwal;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
   /**
     * AKTOR: PENGURUS/PELATIH
     * Menampilkan daftar semua jadwal untuk dipilih mana yang mau dicek presensinya
     */
    public function index()
    {
        // Menggunakan Eloquent agar lebih konsisten dengan fungsi lainnya
        $data_jadwal = Jadwal::with(['kolat', 'pelatih'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        return view('presensi.pengurus.index', compact('data_jadwal'));
    }

    /**
     * AKTOR: PENGURUS/PELATIH
     * Menampilkan detail siapa saja yang sudah scan/absen di jadwal tertentu
     */
    public function kehadiran($jadwal_id)
    {
        $jadwal = Jadwal::with(['kolat', 'pelatih'])->findOrFail($jadwal_id);

        $data_presensi = Presensi::with('anggota', 'pelatihVerifikator')
            ->where('jadwal_id', $jadwal_id)
            ->get();

        // 1. Ambil array ID anggota yang SUDAH absen
        $id_sudah_absen = $data_presensi->pluck('anggota_id')->toArray();

        // 2. Ambil anggota yang se-Kolat, status Aktif, tapi ID-nya BELUM ADA di daftar absen
        $anggota_belum_absen = Anggota::where('kolat_id', $jadwal->kolat_id)
            ->where('status', 'Aktif')
            ->whereNotIn('id', $id_sudah_absen)
            ->get();

        // Kirim variabel baru ini ke View
        return view('presensi.pengurus.kehadiran', compact('jadwal', 'data_presensi', 'anggota_belum_absen'));
    }

    /**
     * AKTOR: PENGURUS/PELATIH
     * Mensahkan (Verify) absensi anggota yang masuk lewat scan QR
     */
    public function konfirmasi(Request $request, $presensi_id)
    {
        $presensi = Presensi::findOrFail($presensi_id);

        $request->validate([
            'action' => 'required|in:sah,tolak',
            'keterangan' => 'nullable|string' // <-- Tambahan validasi keterangan
        ]);

        if ($request->action == 'sah') {
            $presensi->update([
                'is_verified' => true,
                'status'      => 'Hadir',
                'verified_by' => Auth::user()->anggota->id,
            ]);
            $pesan = 'Kehadiran ' . $presensi->anggota->nama_lengkap . ' berhasil disahkan!';
        } else {
            $presensi->update([
                'is_verified' => true,
                'status'      => 'Alfa',
                'verified_by' => Auth::user()->anggota->id,
                'keterangan'  => $request->keterangan // <-- Simpan alasan penolakan
            ]);
            $pesan = 'Absensi ' . $presensi->anggota->nama_lengkap . ' ditolak (Alfa).';
        }

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * AKTOR: PENGURUS/PELATIH
     * Input manual jika anggota lupa bawa HP (Otomatis is_verified = true)
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id'  => 'required|exists:jadwals,id',
            'anggota_id' => 'required|exists:anggotas,id',
            'status'     => 'required|in:Hadir,Izin,Sakit,Alfa',
            'keterangan' => 'nullable|string' // <-- Tambahan validasi keterangan
        ]);

        $sudah_absen = Presensi::where('jadwal_id', $request->jadwal_id)
            ->where('anggota_id', $request->anggota_id)
            ->first();

        if ($sudah_absen) {
            return redirect()->back()->with('error', 'Anggota tersebut sudah memiliki catatan kehadiran di jadwal ini!');
        }

        Presensi::create([
            'jadwal_id'      => $request->jadwal_id,
            'anggota_id'     => $request->anggota_id,
            'status'         => $request->status,
            'keterangan'     => $request->keterangan, // <-- Simpan keterangan manual
            'waktu_presensi' => now(),
            'is_verified'    => true,
            'verified_by'    => Auth::user()->anggota->id,
        ]);

        return redirect()->back()->with('success', 'Presensi manual berhasil disimpan!');
    }

    /**
     * AKTOR: ANGGOTA
     * Melihat daftar jadwal latihan hari ini untuk di-scan
     */
    /**
     * AKTOR: ANGGOTA
     * Melihat daftar jadwal latihan hari ini dan riwayat 7 hari terakhir
     */
    public function indexAnggota()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $hari_ini = $now->format('Y-m-d');

        // Ambil tanggal 7 hari yang lalu
        $seminggu_lalu = $now->copy()->subDays(7)->format('Y-m-d');

        if (!$user->anggota) {
            return redirect()->route('presensi.index')->with('error', 'Akun Anda tidak terdaftar sebagai peserta latihan.');
        }

        // Ambil jadwal dalam rentang 7 hari terakhir hingga hari ini
        $jadwal_terbaru = Jadwal::with('pelatih')
            ->whereBetween('tanggal', [$seminggu_lalu, $hari_ini])
            ->where('kolat_id', $user->anggota->kolat_id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        $presensi_saya = Presensi::where('anggota_id', $user->anggota->id)
            ->whereIn('jadwal_id', $jadwal_terbaru->pluck('id'))
            ->get()
            ->keyBy('jadwal_id');

        return view('presensi.anggota.index', compact('jadwal_terbaru', 'presensi_saya', 'now', 'hari_ini'));
    }

    /**
     * AKTOR: ANGGOTA
     * Menampilkan halaman kamera scanner
     */
    public function scan($jadwal_id)
    {
        $jadwal = Jadwal::findOrFail($jadwal_id);
        return view('presensi.anggota.scan', compact('jadwal'));
    }

    /**
     * AKTOR: ANGGOTA
     * Menyimpan data presensi hasil scan (Menunggu Verifikasi Pelatih)
     */
    public function storeMandiri(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $user = Auth::user();
        $profilAnggota = $user->anggota;

        if (!$profilAnggota) {
            return redirect()->back()->with('error', 'Data profil tidak lengkap.');
        }

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $now = \Carbon\Carbon::now('Asia/Jakarta');

        // Validasi Waktu: Gabungkan tanggal dan jam untuk pengecekan presisi
        $jam_selesai = $jadwal->jam_selesai
            ? $jadwal->jam_selesai
            : \Carbon\Carbon::parse($jadwal->jam_mulai)->addHours(3)->format('H:i:s');

        $batas_waktu = \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jam_selesai, 'Asia/Jakarta');

        if ($now->gt($batas_waktu)) {
            return redirect()->route('presensi.anggota.index')->with('error', 'Sesi absensi sudah berakhir pada pukul ' . substr($jam_selesai, 0, 5) . ' WIB.');
        }

        if ($jadwal->status == 'selesai') {
            return redirect()->route('presensi.anggota.index')->with('error', 'Sesi latihan sudah ditutup oleh pelatih.');
        }

        // Cek apakah sudah absen
        $sudah_absen = Presensi::where('jadwal_id', $request->jadwal_id)
            ->where('anggota_id', $profilAnggota->id)
            ->exists();

        if ($sudah_absen) {
            return redirect()->route('presensi.anggota.index')->with('error', 'Kamu sudah melakukan absensi untuk jadwal ini!');
        }

        Presensi::create([
            'jadwal_id' => $request->jadwal_id,
            'anggota_id' => $profilAnggota->id,
            'status' => 'Hadir',
            'waktu_presensi' => $now,
            'is_verified' => false,
            'verified_by' => null,
        ]);

        return redirect()->route('presensi.anggota.index')->with('success', 'Berhasil scan! Silakan minta Pelatih untuk konfirmasi kehadiran.');
    }

    /**
     * AKTOR: ANGGOTA
     * Menampilkan riwayat lengkap presensi dengan filter bulan dan tahun
     */
    public function riwayatAnggota(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->anggota) {
            return redirect()->route('presensi.index')->with('error', 'Profil anggota tidak ditemukan.');
        }

        // Tangkap request filter bulan & tahun (Default: Bulan & Tahun saat ini)
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil semua jadwal sesuai filter bulan & tahun di Kolat anggota tersebut
        $jadwal_riwayat = Jadwal::with('pelatih')
            ->where('kolat_id', $user->anggota->kolat_id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        // Ambil data presensi anggota pada jadwal-jadwal tersebut
        $presensi_saya = Presensi::where('anggota_id', $user->anggota->id)
            ->whereIn('jadwal_id', $jadwal_riwayat->pluck('id'))
            ->get()
            ->keyBy('jadwal_id');

        return view('presensi.anggota.riwayat', compact('jadwal_riwayat', 'presensi_saya', 'bulan', 'tahun'));
    }
}
