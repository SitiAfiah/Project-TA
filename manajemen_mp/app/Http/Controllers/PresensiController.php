<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// TAMBAHAN WAJIB: Memanggil Facades dan Model yang digunakan
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Anggota;

class PresensiController extends Controller
{
    public function index()
    {
        // Mengambil semua jadwal secara manual menggunakan Query Builder (JOIN)
        $data_jadwal = DB::table('jadwals')
            ->leftJoin('anggotas', 'jadwals.pelatih_id', '=', 'anggotas.id')
            ->leftJoin('kolat', 'jadwals.kolat_id', '=', 'kolat.id') // Sesuaikan jika nama tabel di database kamu adalah 'kolat'
            ->select(
                'jadwals.*',
                'anggotas.nama_lengkap as nama_pelatih',
                'kolat.nama_kolat as nama_kolat'
            )
            ->orderBy('jadwals.tanggal', 'desc')
            ->get();

        // Pastikan nama foldernya konsisten (admin atau pengurus)
        return view('presensi.pengurus.index', compact('data_jadwal'));
    }

    public function kehadiran($jadwal_id)
{
    // Variabel dibuat dengan nama $jadwal (tunggal)
    $jadwal = Jadwal::with(['kolat', 'pelatih'])->findOrFail($jadwal_id);

    $data_presensi = Presensi::with('anggota', 'pelatihVerifikator')
        ->where('jadwal_id', $jadwal_id)
        ->get();

    $daftar_anggota = Anggota::where('kolat_id', $jadwal->kolat_id)
        ->where('status', 'Aktif')
        ->get();

    // Pastikan di sini tulisannya 'jadwal' (sesuai nama variabel di atas)
    return view('presensi.pengurus.kehadiran', compact('jadwal', 'data_presensi', 'daftar_anggota'));
}

    /**
     * 2. Proses Pelatih klik "Konfirmasi Hadir"
     */
    public function konfirmasi($presensi_id)
    {
        $presensi = Presensi::findOrFail($presensi_id);

        // Ubah status menjadi terverifikasi dan catat ID Pelatih yang klik
        $presensi->update([
            'is_verified' => true,
            'verified_by' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Kehadiran ' . $presensi->anggota->nama_lengkap . ' berhasil dikonfirmasi!');
    }

    /**
     * 3. Proses Pelatih input manual dari Modal (Kasus Lupa HP)
     */
    public function storeManual(Request $request)
    {
        // Validasi input dari form modal
        $request->validate([
            'jadwal_id'  => 'required|exists:jadwals,id',
            'anggota_id' => 'required|exists:anggotas,id',
        ]);

        // Cek apakah anggota tersebut sudah absen sebelumnya agar tidak ada data ganda (double input)
        $sudah_absen = Presensi::where('jadwal_id', $request->jadwal_id)
                               ->where('anggota_id', $request->anggota_id)
                               ->first();

        if ($sudah_absen) {
            return redirect()->back()->with('error', 'Anggota tersebut sudah tercatat di daftar kehadiran!');
        }

        // Simpan data presensi baru
        // Karena diinput langsung oleh pelatih, is_verified otomatis bernilai true
        Presensi::create([
            'jadwal_id'      => $request->jadwal_id,
            'anggota_id'     => $request->anggota_id,
            'status'         => 'Hadir',
            'waktu_presensi' => now(), // Waktu dicatat saat form disubmit
            'is_verified'    => true,  // Langsung Sah
            'verified_by'    => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Presensi manual berhasil ditambahkan!');
    }


    public function indexAnggota()
    {
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $hari_ini = $now->format('Y-m-d');
        $user = Auth::user();

        // 1. Ambil jadwal latihan yang terjadi HARI INI sesuai dengan Kolat si Anggota
        // Asumsinya di tabel anggotas (users) ada kolom kolat_id
        $jadwal_hari_ini = Jadwal::with('pelatih')
            ->where('tanggal', $hari_ini)
            ->where('kolat_id', $user->kolat_id)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // 2. Cek apakah anggota ini sudah absen di jadwal tersebut
        // Menggunakan pluck() untuk mengambil ID jadwal hari ini saja
        $presensi_saya = Presensi::where('anggota_id', $user->id)
            ->whereIn('jadwal_id', $jadwal_hari_ini->pluck('id'))
            ->get()
            ->keyBy('jadwal_id'); // Jadikan ID jadwal sebagai key array agar mudah dicek di View

        return view('presensi.anggota.index', compact('jadwal_hari_ini', 'presensi_saya', 'now'));
    }

    public function storeMandiri(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $user = Auth::user();

        // Cek agar tidak bisa klik absen 2 kali (Double submit)
        $sudah_absen = Presensi::where('jadwal_id', $request->jadwal_id)
                               ->where('anggota_id', $user->id)
                               ->exists();

        if ($sudah_absen) {
            return redirect()->back()->with('error', 'Kamu sudah melakukan absensi untuk jadwal ini!');
        }

        // Simpan data presensi dengan status is_verified = false (Menunggu ACC)
        Presensi::create([
            'jadwal_id'      => $request->jadwal_id,
            'anggota_id'     => $user->id,
            'status'         => 'Hadir',
            'waktu_presensi' => now(),
            'is_verified'    => false, // Belum Sah, butuh ACC Pelatih
            'verified_by'    => null
        ]);

        return redirect()->back()->with('success', 'Berhasil! Kehadiranmu sedang menunggu konfirmasi Pelatih.');
    }
}
