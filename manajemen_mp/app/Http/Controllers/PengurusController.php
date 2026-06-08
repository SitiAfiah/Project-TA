<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengurusController extends Controller
{
    // Menampilkan Daftar Pengurus Cabang
    public function index()
    {
        $data_pengurus = Anggota::whereHas('roles', function($q) {
            $q->where('nama_role', 'Pengurus');
        })->latest()->get();

        return view('pengurus.index', compact('data_pengurus'));
    }

    // Menampilkan Form Angkat Pengurus Baru
    public function create()
    {
        // Aturan: Harus sudah memiliki role Pelatih, tetapi BELUM memiliki role Pengurus
        $calon_pengurus = Anggota::whereHas('roles', function($q) {
            $q->where('nama_role', 'Pelatih');
        })->whereDoesntHave('roles', function($q) {
            $q->where('nama_role', 'Pengurus');
        })->get();

        return view('pengurus.create', compact('calon_pengurus'));
    }

    // Menyimpan Data Pengurus Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'         => 'required|exists:anggotas,id',
            'jabatan_struktural' => 'required|string|max:255', // Contoh: Ketua, Sekretaris, Bendahara
        ]);

        DB::beginTransaction();

        try {
            $anggota = Anggota::findOrFail($request->anggota_id);
            $rolePengurus = Role::where('nama_role', 'Pengurus')->firstOrFail();

            // Gabungkan jabatan lama (pelatih) dengan jabatan struktural baru
            $jabatan_baru = $anggota->jabatan . ', Pengurus (' . $request->jabatan_struktural . ')';

            // 1. Tambahkan role Pengurus ke tabel pivot tanpa menghapus role lama
            $anggota->roles()->syncWithoutDetaching([$rolePengurus->id]);

            // 2. Perbarui kolom jabatan strukturalnya
            $anggota->update([
                'jabatan' => $jabatan_baru
            ]);

            DB::commit();
            return redirect()->route('pengurus.index')
                ->with('success', $anggota->nama_lengkap . ' berhasil diangkat menjadi Pengurus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal mengangkat pengurus: " . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses data.');
        }
    }

    // Menampilkan Form Edit Jabatan Struktural Pengurus
    public function edit($id)
    {
        $pengurus = Anggota::whereHas('roles', function($q) {
            $q->where('nama_role', 'Pengurus');
        })->findOrFail($id);

        // Ekstrak nilai di dalam kurung dari string jabatan untuk mempermudah edit di form
        // Contoh: "Pelatih, Pengurus (Sekretaris)" diambil kata "Sekretaris"
        preg_match('#\((.*?)\)#', $pengurus->jabatan, $match);
        $jabatan_sekarang = $match[1] ?? '';

        return view('pengurus.edit', compact('pengurus', 'jabatan_sekarang'));
    }

    // Membarui Jabatan Struktural Pengurus
    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan_struktural' => 'required|string|max:255',
        ]);

        try {
            $pengurus = Anggota::findOrFail($id);

            // Set ulang string jabatannya dengan nilai struktural yang baru
            $jabatan_baru = 'Pelatih, Pengurus (' . $request->jabatan_struktural . ')';

            $pengurus->update([
                'jabatan' => $jabatan_baru
            ]);

            return redirect()->route('pengurus.index')
                ->with('success', 'Jabatan struktural ' . $pengurus->nama_lengkap . ' berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error("Gagal update jabatan pengurus: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui data.');
        }
    }
}
