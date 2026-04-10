<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kolat; // Import model Kolat
use App\Models\Role;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    // 1. Menampilkan Daftar Anggota
    public function anggota()
    {
        // Menggunakan with('kolat') agar data relasi langsung terbawa (Eager Loading)
        $data_anggota = Anggota::with(['kolat', 'role'])->get();
        return view('anggota.anggota', compact('data_anggota'));
    }

    // 2. Menampilkan Form Tambah
    public function create()
    {
        // Ambil semua daftar kolat untuk ditampilkan di dropdown form
        $data_kolat = Kolat::all();
        return view('anggota.anggotacreate', compact('data_kolat'));
    }

    // 3. Menyimpan Anggota Baru
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_lengkap' => 'required',
            'jabatan'      => 'required|in:pengurus,pelatih,anggota',
            'no_hp'        => 'required',
            'role_id'     => 'required',
            'kolat_id'     => 'required|exists:kolat,id', // Validasi ID kolat harus ada di tabel kolat
        ]);

        // Logika Membuat No Induk Otomatis (JB-001)
        $terakhir = Anggota::orderBy('id', 'desc')->first();

        if (!$terakhir) {
            $nomor_urut = "001";
        } else {
            // Ambil angka setelah "JB-" (JB-001 -> 001)
            $nomor_terakhir = substr($terakhir->no_induk, 3);
            $nomor_urut = sprintf("%03d", intval($nomor_terakhir) + 1);
        }

        $no_induk_baru = "JB-" . $nomor_urut;

        // Gabungkan data input dengan No Induk otomatis
        $data = $request->all();
        $data['no_induk'] = $no_induk_baru;

        // Simpan ke database
        Anggota::create($data);

        return redirect()->route('anggota.anggota')
            ->with('success', 'Anggota ' . $request->nama_lengkap . ' berhasil terdaftar dengan ID: ' . $no_induk_baru);
    }

    // 4. Menampilkan Form Edit
    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $data_kolat = Kolat::all(); // Ambil daftar kolat lagi untuk pilihan saat edit

        return view('anggota.editanggota', compact('anggota', 'data_kolat'));
    }

    // 5. Update Data Anggota
    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'no_induk'     => 'required|unique:anggotas,no_induk,' . $id,
            'nama_lengkap' => 'required',
            'no_hp'        => 'required',
            'jabatan'      => 'required|in:pengurus,pelatih,anggota',
            'kolat_id'     => 'required|exists:kolat,id',
        ]);

        // Update data menggunakan data yang sudah divalidasi
        $anggota->update($request->all());

        return redirect()->route('anggota.anggota')
            ->with('success', 'Data ' . $anggota->nama_lengkap . ' Berhasil Diperbarui!');
    }
}
