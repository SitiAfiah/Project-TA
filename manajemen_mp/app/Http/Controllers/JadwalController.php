<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kolat;
use App\Models\Anggota;
use App\Models\Role;
use Illuminate\Http\Request;

class JadwalController extends Controller
{

    public function index()
    {
        // Mengambil data jadwal beserta relasi kolat dan pelatih
        $data_jadwal = Jadwal::with(['kolat', 'pelatih'])->orderBy('tanggal', 'desc')->get();

        return view('jadwal.jadwal', compact('data_jadwal'));
    }


    public function create()
    {
        $data_kolat = Kolat::all();
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();

        // Ambil Anggota yang memiliki role 'Pelatih' saja (filter lewat relasi)
        $data_pelatih = Anggota::whereHas('role', function($query) {
            $query->where('nama_role', 'Pelatih');
        })->get();

        return view('jadwal.jadwalcreate', compact('data_kolat', 'data_pelatih'));
    }


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

    public function show($id)
{
    // Ambil satu data dengan relasinya
    $jadwal = Jadwal::with(['kolat', 'pelatih'])->findOrFail($id);

    return view('jadwal.show', compact('jadwal'));
}

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $data_kolat = Kolat::all();

        // Tetap filter agar hanya muncul daftar pelatih
        $data_pelatih = Anggota::whereHas('role', function($query) {
            $query->where('nama_role', 'Pelatih');
        })->get();

        return view('jadwal.edit', compact('jadwal', 'data_kolat', 'data_pelatih'));
    }


    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'judul_kegiatan' => 'required|string',
            'kolat_id'       => 'required',
            'pelatih_id'     => 'required',
            'tanggal'        => 'required|date',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal telah dihapus dari sistem.');
    }
}
