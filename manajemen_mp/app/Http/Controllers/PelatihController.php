<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kolat;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelatihController extends Controller
{
    // Menampilkan daftar anggota yang sudah menjadi pelatih
    public function index()
    {
        // Asumsi: Role pelatih memiliki nama 'Pelatih' di tabel roles
        $data_pelatih = Anggota::whereHas('roles', function($q) {
            $q->where('nama_role', 'Pelatih');
        })->with('kolatLatihan')->get();
        // })->with('kolat')->get();

        return view('pelatih.pelatih', compact('data_pelatih'));
    }

    // Menampilkan form untuk upgrade anggota ke pelatih
    public function create()
    {
        // Ambil anggota yang role-nya masih 'Anggota' (bukan pelatih)
        $calon_pelatih = Anggota::whereHas('roles', function($q) {
            $q->where('nama_role', 'Anggota');
            })->whereDoesntHave('roles', function($q) {
            $q->where('nama_role', 'Pelatih');
        })->get();

        $data_kolat = Kolat::all();

        return view('pelatih.pelatihcreate', compact('calon_pelatih', 'data_kolat'));
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
            'kolat_ids'   => 'required|array',
            'kolat_ids.*' => 'exists:kolat,id',
        ]);

        // Cari data anggota berdasarkan ID yang dipilih
        $anggota = Anggota::findOrFail($request->anggota_id);

        // Cari ID Role Pelatih
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();

        // Data yang akan diupdate
        $updateData = [
            // 'role_id'      => $rolePelatih->id,
            // 'jabatan'      => 'pelatih', // Update status jabatan
            'no_sk'        => $request->no_sk,
            'tgl_sk'       => $request->tgl_sk,
            'masa_berlaku' => $request->masa_berlaku,
            'status'       => 'Aktif',
        ];

        $jabatan_lama = strtolower($anggota->jabatan);
        if (empty($jabatan_lama) || $jabatan_lama == 'anggota') {
            $updateData['jabatan'] = 'Pelatih'; // Jika kosong/anggota biasa, set murni Pelatih
        } else if (!str_contains($jabatan_lama, 'pelatih')) {
            $updateData['jabatan'] = $anggota->jabatan . ', Pelatih'; // Gabungkan, misal: "Sekretaris, Pelatih"
        }

        // Handle upload foto SK jika ada
        if ($request->hasFile('foto_sk')) {
            $path = $request->file('foto_sk')->store('sk_pelatih', 'public');
            $updateData['foto_sk'] = $path;
        }

        $anggota->roles()->syncWithoutDetaching([$rolePelatih->id]);
        $anggota->kolatLatihan()->sync($request->kolat_ids);

        // Eksekusi Update
        $anggota->update($updateData);

        return redirect()->route('pelatih.index')
            ->with('success', $anggota->nama_lengkap . ' berhasil diupgrade menjadi Pelatih!');
    }

    // Menampilkan form edit data kepelatihan (misal perpanjang SK)
    public function edit($id)
    {

    $pelatih = Anggota::findOrFail($id);
    $data_kolat = Kolat::all();

    return view('pelatih.editpelatih', compact('pelatih', 'data_kolat'));
}

    public function update(Request $request, $id)
    {
        $pelatih = Anggota::findOrFail($id);

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $pelatih->user_id, // validasi email user
        'no_hp' => 'required',
        'no_sk' => 'required|unique:anggotas,no_sk,' . $id, // pastikan nama tabel benar 'anggota'
        'tgl_sk' => 'nullable|date', // tambahkan jika di form ada tgl_sk
        'foto_sk' => 'nullable|image|max:2048',
        'kolat_ids'   => 'required|array',
        'kolat_ids.*' => 'exists:kolat,id',
    ]);

        // $data = $request->all();
        $data = $request->except(['kolat_ids', '_token', '_method']);

        if ($request->hasFile('foto_sk')) {
            if ($pelatih->foto_sk) {
                Storage::disk('public')->delete($pelatih->foto_sk);
            }
            $data['foto_sk'] = $request->file('foto_sk')->store('sk_pelatih', 'public');
        }

        $pelatih->update($data);
        $pelatih->kolatLatihan()->sync($request->kolat_ids);

        return redirect()->route('pelatih.index')->with('success', 'Data kepelatihan diperbarui!');
    }
}
