<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kolat; // Import model Kolat
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'email'         => 'required|email|unique:users,email',
            'nama_lengkap'  => 'required',
            'no_hp'         => 'required',
            'role_id'       => 'required|exists:role,id',
            'kolat_id'      => 'required|exists:kolat,id',
            'jenis_kelamin' => 'required',
            'tempat_lahir'  => 'required',
            'tgl_lahir'     => 'required|date',
            'tingkatan'     => 'required',
            'tgl_gabung'    => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // A. Buat Akun Login di tabel Users
            $user = User::create([
                'email'    => $request->email,
                'password' => Hash::make('tapakmp123'), // Password default
                'role_id'  => $request->role_id,
            ]);

            // B. Cari nama jabatan berdasarkan role
            $role = Role::find($request->role_id);
            $nama_jabatan = strtolower($role->nama_role);

            // C. Logika No Induk (JB-001)
            $terakhir = Anggota::orderBy('id', 'desc')->first();
            $nomor_urut = $terakhir ? sprintf("%03d", intval(substr($terakhir->no_induk, 3)) + 1) : "001";
            $no_induk_baru = "JB-" . $nomor_urut;

            // D. Simpan Profil di tabel Anggotas
            Anggota::create([
                'user_id'       => $user->id,
                'no_induk'      => $no_induk_baru,
                'nama_lengkap'  => $request->nama_lengkap,
                'role_id'       => $request->role_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir'  => $request->tempat_lahir,
                'tgl_lahir'     => $request->tgl_lahir,
                'no_hp'         => $request->no_hp,
                'kolat_id'      => $request->kolat_id,
                'tingkatan'     => $request->tingkatan,
                'tgl_gabung'    => $request->tgl_gabung,
                'alamat'        => $request->alamat,
                'catatan_medis' => $request->catatan_medis,
                'jabatan'       => $nama_jabatan,
                'status'        => 'Aktif', // Input admin otomatis aktif
            ]);

            DB::commit();
            return redirect()->route('anggota.anggota')
                ->with('success', 'Data berhasil ditambah! Password login: tapakmp123');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal tambah anggota: " . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    // 4. Menampilkan Form Edit
    public function edit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);
        $data_kolat = Kolat::all(); // Ambil daftar kolat lagi untuk pilihan saat edit
        $data_role = Role::all();

        return view('anggota.editanggota', compact('anggota', 'data_kolat', 'data_role'));
    }

    // 5. Update Data Anggota
    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $user = User::findOrFail($anggota->user_id);

        $request->validate([
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'nama_lengkap' => 'required',
            'no_hp'        => 'required',
            'role_id'      => 'required|exists:role,id',
            'kolat_id'     => 'required|exists:kolat,id',
            'status'       => 'required|in:Aktif,Non-Aktif',
        ]);

        DB::beginTransaction();

        try {
            // A. Update tabel User (email & role)
            $user->update([
                'email'   => $request->email,
                'role_id' => $request->role_id
            ]);

            // B. Update jabatan berdasarkan role baru
            $role = Role::find($request->role_id);
            $nama_jabatan = strtolower($role->nama_role);

            // C. Update tabel Anggota
            $data_anggota = $request->all();
            $data_anggota['jabatan'] = $nama_jabatan;

            $anggota->update($data_anggota);

            DB::commit();
            return redirect()->route('anggota.anggota')
                ->with('success', 'Data ' . $anggota->nama_lengkap . ' berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update anggota: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

}
