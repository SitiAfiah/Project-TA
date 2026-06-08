<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kolat;
use App\Models\Anggota;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class JadwalController extends Controller
{

   // 1. Menampilkan Daftar Jadwal
    public function index()
    {
        // Tambahkan 'presensi.anggota' agar modal konfirmasi bisa nampilin siapa aja yang hadir
        $data_jadwal = Jadwal::with(['kolat', 'pelatih', 'presensi.anggota'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        return view('jadwal.jadwal', compact('data_jadwal'));
    }

    // 2. Proses Konfirmasi Latihan Selesai (Update Status + Upload Foto Kegiatan)
    public function konfirmasiSelesai(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Validasi input dari modal
        $request->validate([
            'foto_bukti'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'catatan_latihan' => 'nullable|string'
        ]);

        // Proses upload foto kegiatan pelatih ke folder storage/app/public/kegiatan_pelatih
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('kegiatan_pelatih', 'public');
            $jadwal->foto_bukti = $path;
        }

        // Simpan catatan dan ubah status menjadi selesai
        $jadwal->catatan_latihan = $request->catatan_latihan;
        $jadwal->status = 'selesai';
        $jadwal->save();

        return redirect()->route('jadwal.index')
            ->with('success', 'Latihan ' . $jadwal->judul_kegiatan . ' telah dikonfirmasi selesai!');
    }

    // 3. Menampilkan Form Tambah Jadwal
    public function create()
    {
        $data_kolat = Kolat::all();

        // Ambil Anggota yang memiliki role 'Pelatih' dan statusnya 'Aktif'
        $data_pelatih = Anggota::whereHas('roles', function($query) {
            $query->where('nama_role', 'Pelatih');
        })
        ->where('status', 'Aktif')
        ->orderBy('nama_lengkap', 'asc')
        ->get();

        return view('jadwal.jadwalcreate', compact('data_kolat', 'data_pelatih'));
    }

    // 4. Proses Simpan Jadwal Baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kolat_id'       => 'required|exists:kolat,id',
            'pelatih_id'     => 'required|exists:anggotas,id',
            'jenis'          => 'required|in:Rutin,Tambahan,Pengumuman',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'lokasi'         => 'required|string',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal latihan berhasil ditambahkan!');
    }

    // 5. Menampilkan Detail Jadwal (Opsional jika pakai modal)
    public function show($id)
    {
        $jadwal = Jadwal::with(['kolat', 'pelatih'])->findOrFail($id);
        return view('jadwal.show', compact('jadwal'));
    }

    // 6. Menampilkan Form Edit Jadwal
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $data_kolat = Kolat::all();

        // Tetap filter agar hanya muncul daftar pelatih
        $data_pelatih = Anggota::whereHas('roles', function($query) {
            $query->where('nama_role', 'Pelatih');
        })->get();

        return view('jadwal.editjadwal', compact('jadwal', 'data_kolat', 'data_pelatih'));
    }

    // 7. Proses Update Jadwal
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kolat_id'       => 'required|exists:kolat,id',
            'pelatih_id'     => 'required|exists:anggotas,id',
            'jenis'          => 'required|in:Rutin,Tambahan,Pengumuman',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'lokasi'         => 'required|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    // 8. Proses Hapus Jadwal
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Cek dan hapus file foto bukti dari storage jika ada, biar hosting nggak penuh
        if ($jadwal->foto_bukti) {
            Storage::disk('public')->delete($jadwal->foto_bukti);
        }

        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal telah dihapus dari sistem.');
    }

}
