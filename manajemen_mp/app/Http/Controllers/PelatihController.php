<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelatihController extends Controller
{
    // Menampilkan daftar anggota yang sudah menjadi pelatih
    public function index()
    {
        // Asumsi: Role pelatih memiliki nama 'Pelatih' di tabel roles
        $data_pelatih = Anggota::whereHas('role', function($q) {
            $q->where('nama_role', 'Pelatih');
        })->with('kolat')->get();

        return view('pelatih.pelatih', compact('data_pelatih'));
    }

    // Menampilkan form untuk upgrade anggota ke pelatih
    public function create()
    {
        // Ambil anggota yang role-nya masih 'Anggota' (bukan pelatih)
        $calon_pelatih = Anggota::whereHas('role', function($q) {
            $q->where('nama_role', 'Anggota');
        })->get();

        return view('pelatih.pelatihcreate', compact('calon_pelatih'));
    }

    // Proses Upgrade Anggota ke Pelatih
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'   => 'required|exists:anggotas,id',
            'no_sk'        => 'required|unique:anggotas,no_sk',
            'tgl_sk'       => 'required|date',
            'masa_berlaku' => 'required|date',
            'foto_sk'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Cari data anggota berdasarkan ID yang dipilih
        $anggota = Anggota::findOrFail($request->anggota_id);

        // Cari ID Role Pelatih
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();

        // Data yang akan diupdate
        $updateData = [
            'role_id'      => $rolePelatih->id,
            'jabatan'      => 'pelatih', // Update status jabatan
            'no_sk'        => $request->no_sk,
            'tgl_sk'       => $request->tgl_sk,
            'masa_berlaku' => $request->masa_berlaku,
            'status'       => 'Aktif',
        ];

        // Handle upload foto SK jika ada
        if ($request->hasFile('foto_sk')) {
            $path = $request->file('foto_sk')->store('sk_pelatih', 'public');
            $updateData['foto_sk'] = $path;
        }

        // Eksekusi Update
        $anggota->update($updateData);

        return redirect()->route('pelatih.index')
            ->with('success', $anggota->nama_lengkap . ' berhasil diupgrade menjadi Pelatih!');
    }

    // Menampilkan form edit data kepelatihan (misal perpanjang SK)
    public function edit($id)
    {
        $pelatih = Anggota::findOrFail($id);
        return view('pelatih.editpelatih', compact('pelatih'));
    }

    public function update(Request $request, $id)
    {
        $pelatih = Anggota::findOrFail($id);

        $request->validate([
            'no_sk' => 'required|unique:anggotas,no_sk,' . $id,
            'tgl_sk' => 'required|date',
            'foto_sk' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_sk')) {
            if ($pelatih->foto_sk) {
                Storage::disk('public')->delete($pelatih->foto_sk);
            }
            $data['foto_sk'] = $request->file('foto_sk')->store('sk_pelatih', 'public');
        }

        $pelatih->update($data);

        return redirect()->route('pelatih.index')->with('success', 'Data kepelatihan diperbarui!');
    }
}
