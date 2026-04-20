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
        // Filter data: Ambil anggota yang relasi 'role'-nya bernama 'Anggota'
    $data_anggota = Anggota::whereHas('role', function($query) {
        $query->where('nama_role', 'Anggota');
    })->with(['kolat', 'role'])->get();

    return view('anggota.anggota', compact('data_anggota'));
    }

    // 2. Menampilkan Form Tambah
    public function create()
    {
        // Ambil semua daftar kolat untuk ditampilkan di dropdown form
        $data_kolat = Kolat::all();
        $data_role = Role::all();

        return view('anggota.anggotacreate', compact('data_kolat', 'data_role'));
    }

    public function store(Request $request)
{
    // 1. Validasi diperbaiki (Hapus jabatan dari required karena kita isi manual)
    $request->validate([
        'nama_lengkap' => 'required',
        'no_hp'        => 'required',
        'role_id'      => 'required|exists:role,id', // pastikan nama tabel benar
        'kolat_id'     => 'required|exists:kolat,id',
        'jenis_kelamin'=> 'required',
        'tempat_lahir' => 'required',
        'tgl_lahir'    => 'required|date',
        'tingkatan'    => 'required',
        'tgl_gabung'   => 'required|date',
    ]);

    // 2. Ambil nama role untuk mengisi kolom jabatan secara otomatis
    $role = Role::find($request->role_id);
    $nama_jabatan = strtolower($role->nama_role);

    // 3. Logika No Induk
    $terakhir = Anggota::orderBy('id', 'desc')->first();
    $nomor_urut = $terakhir ? sprintf("%03d", intval(substr($terakhir->no_induk, 3)) + 1) : "001";
    $no_induk_baru = "JB-" . $nomor_urut;

    // 4. Gabungkan semua data
    $data = $request->all();
    $data['no_induk'] = $no_induk_baru;
    $data['jabatan'] = $nama_jabatan; // Mengisi kolom jabatan otomatis

    // 5. Simpan (Pastikan fillable di Model sudah lengkap)
    Anggota::create($data);

    return redirect()->route('anggota.anggota')
        ->with('success', 'Anggota berhasil terdaftar!');
}

    // 4. Menampilkan Form Edit
    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $data_kolat = Kolat::all(); // Ambil daftar kolat lagi untuk pilihan saat edit
        $data_role = Role::all();

        return view('anggota.editanggota', compact('anggota', 'data_kolat', 'data_role'));
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
